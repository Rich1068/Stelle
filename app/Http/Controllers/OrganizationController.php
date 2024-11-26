<?php

namespace App\Http\Controllers;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
class OrganizationController extends Controller
{
    public function list(Request $request)
    {
        // Query the organizations
        $searchTerm = $request->input('search', '');

        $query = Organization::query()->withCount([
            'members as current_members' => function ($query) {
                $query->where('status_id', 1)
                    ->whereHas('member', function ($userQuery) {
                        $userQuery->withTrashed();
                    });
            }
        ]);
        if (!empty($searchTerm)) {
            $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
        }
        $organizations = $query->paginate(10);
        if ($request->ajax()) {
            return response()->json([
                'organizationsHtml' => view('organization.partials.organizationlist', compact('organizations'))->render(),
                'paginationHtml' => $organizations->links('vendor.pagination.custom1')->render(),
                'hasOrganizations' => $organizations->count() > 0,
            ]);
        }

        return view('organization.organizationlist', compact('organizations'));
    }
    public function mylist(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user
        $searchTerm = $request->input('search', ''); // Get the search term

        // Query organizations created or joined by the user
        $query = Organization::query()
            ->withCount([
                'members as current_members' => function ($query) {
                    $query->where('status_id', 1) // Only accepted members
                        ->whereHas('member', function ($userQuery) {
                            $userQuery->withTrashed(); // Include soft-deleted users
                        });
                }
            ])
            ->where(function ($query) use ($user, $searchTerm) {
                $query->where('owner_id', $user->id) // Organizations created by the user
                    ->orWhereHas('members', function ($memberQuery) use ($user) {
                        $memberQuery->where('user_id', $user->id) // Organizations joined by the user
                                    ->where('status_id', 1); // Only accepted members
                    });
            });

        // Apply search filter if a search term is provided
        if (!empty($searchTerm)) {
            $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
        }

        // Paginate the organizations
        $organizations = $query->paginate(10);

        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'organizationsHtml' => view('organization.partials.organizationlist', compact('organizations'))->render(),
                'paginationHtml' => $organizations->links('vendor.pagination.custom1')->render(),
                'hasOrganizations' => $organizations->count() > 0,
            ]);
        }

        // Return the main organization list view
        return view('organization.myorganizationlist', compact('organizations'));
    }
    public function view($id): View
    {
        $organization = Organization::withTrashed()->findOrFail($id);

        $totalMembers = OrganizationMember::where('organization_id', $id)
                        ->where('status_id', 1)
                        ->count();
        $pendingMembersCount= OrganizationMember::where('organization_id', $id)
                            ->where('status_id', 3)
                            ->count();

        $members = OrganizationMember::where('organization_id', $id)
                        ->where('status_id', 1)
                        ->with(['member' => function ($query) {
                            $query->withTrashed(); // Include soft-deleted users
                        }])
                        ->get();
        $overallMember = OrganizationMember::where('organization_id', $id)
                        ->where('user_id', Auth::id())
                        ->first();

        return view('organization.organization', compact('organization', 'totalMembers','pendingMembersCount', 'members', 'overallMember'));
    }
    public function create(): View
    {
        return view('organization.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:organizations'],
            'description' => ['nullable', 'string', 'max:500'],
            'contact_email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:11', 'regex:/^[\d\-]+$/'],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048']
        ]);
        // Start a database transaction
        DB::beginTransaction();
        try {
            // Handle file upload if present
            $relativePath = null;
            if ($request->hasFile('icon')) {
                $file = $request->file('icon');
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $relativePath = 'storage/images/org_icons/' . $filename;
                // Move the uploaded file to the desired directory
                $file->storeAs('/images/org_icons', $filename);
            }
            // Create the event record
            $organization = Organization::create([
                'name' => $request->name,
                'description' => $request->description,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'icon' => $relativePath,
                'owner_id' => Auth::id(),
                'is_open' => true
            ]);

            // Create a user-event record
            OrganizationMember::create([
                'user_id' => Auth::id(),
                'organization_id' => $organization->id,
                'status_id' => 1
            ]);

            // Commit the transaction
            DB::commit();

            return redirect()->route('organization.view', $organization->id)->with('success', 'Organization created successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction if any errors occur
            DB::rollBack();

            // Optionally delete the uploaded file if something goes wrong
            if ($relativePath && Storage::exists($relativePath)) {
                Storage::delete($relativePath);
            }

            // Log the error (optional) and redirect back with an error message
            \Log::error('Error creating event: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create event. Please try again.');
        }
    }

    public function edit($id): View
    {
        $organization = Organization::where('id', $id)->firstOrFail();

        return view('organization.edit', compact('organization'));
    }
    public function update(Request $request, $id): RedirectResponse 
    {
        $organization = Organization::where('id', $id)->firstOrFail();
        $relativePath = $organization->icon;
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('organizations')->ignore($organization->id)
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'contact_email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:11', 'regex:/^[\d\-]+$/'],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048']
        ]);
        DB::beginTransaction();
        try{
            if ($request->hasFile('icon')) {
                $file = $request->file('icon');
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $newPath = 'storage/images/org_icons/' . $filename;
    
                // Store new file
                $file->storeAs('/images/org_icons', $filename);
    
                // Delete old file if it exists
                if ($relativePath && File::exists($relativePath)) {
                    File::delete($relativePath);
                }

                $relativePath = $newPath;
            }
    
            // Update the validated data with the new file path (if any)
            $validatedData['icon'] = $relativePath;
    
            // Update the organization record
            $organization->fill($validatedData);
            $organization->save();
    
            DB::commit();
            return redirect()->route('organization.view', $organization->id)->with('success', 'Organization updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating organization: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update organization. Please try again.');
        }
    }
    public function join($id)
    {
        $organization = Organization::findOrFail($id);

        // Check if the user is already a participant
        $member = OrganizationMember::where('user_id', Auth::user()->id)
            ->where('organization_id', $organization->id)
            ->first();

        if (!$member && $organization->is_open == true) {
            // Create new EventParticipant record with status 'Pending'
            OrganizationMember::create([
                'user_id' => Auth::user()->id,
                'organization_id' => $organization->id,
                'status_id' => 3, // Pending
            ]);

            return redirect()->route('organization.view', $organization->id)->with('success', 'You have requested to join the organization!');
        }

        return redirect()->route('organization.view', $organization->id)->with('error', 'You have already requested to join this event.');
    }
////////////////////////////////////////////////////////////////////
    public function showPendingMembers(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);

        // Count accepted members
        $totalMembers = OrganizationMember::where('organization_id', $id)
            ->where('status_id', 1)
            ->count();

        // Query for pending members
        $query = OrganizationMember::where('organization_id', $id)
            ->where('status_id', 3);

        // Apply search filter if provided
        if ($request->has('search')) {
            $searchTerm = $request->get('search');
            $query->whereHas('member', function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        // Paginate members
        $members = $query->paginate(10);

        // Handle AJAX requests for filtering and pagination
        if ($request->ajax()) {
            return response()->json([
                'html' => view('organization.partials.pendingmembers', compact('members', 'organization'))->render(),
            ]);
        }

        // Return the main view
        return view('organization.pendingmembers', compact('organization', 'members', 'totalMembers'));
    }
///////////////////////////////////////////////////////////////
    public function updateMemberStatus(Request $request, $organizationId, $memberId)
    {
        // Validate the request
        $request->validate([
            'status_id' => ['required', 'integer', 'in:1,2'], // Accept or Decline
        ]);

        // Find the organization
        $organization = Organization::findOrFail($organizationId);

        // Check if the organization is open
        if (!$organization->is_open) {
            return response()->json([
                'message' => 'This organization is currently closed. You cannot accept members.',
            ], 403);
        }

        // Check if the member exists in the organization
        $member = OrganizationMember::where('organization_id', $organizationId)
            ->where('user_id', $memberId)
            ->where('status_id', 3) // Ensure the member is in 'pending' status
            ->firstOrFail();

        // If accepting the member (status_id = 1)
        if ($request->status_id == 1) {
            $member->update(['status_id' => 1]);
            return response()->json([
                'message' => 'Member has been accepted successfully.',
            ], 200);
        }

        // If declining the member (status_id = 2)
        if ($request->status_id == 2) {
            $member->update(['status_id' => 2]);
            return response()->json([
                'message' => 'Member has been declined successfully.',
            ], 200);
        }

        // If something goes wrong, return an error response
        return response()->json([
            'message' => 'Invalid operation.',
        ], 400);
    }


    public function toggleStatus($id)
    {
        $organization = Organization::findOrFail($id);

        // Ensure only the owner can toggle the status
        if (Auth::id() !== $organization->owner_id) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }
    
        // Toggle the is_open status
        $organization->is_open = !$organization->is_open;
        $organization->save();
    
        return response()->json([
            'message' => $organization->is_open ? 'Organization is now open.' : 'Organization is now closed.',
            'is_open' => $organization->is_open,
        ]);
    }
}
