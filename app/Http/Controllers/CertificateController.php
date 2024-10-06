<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Certificate;
use App\Models\CertTemplate;
use App\Models\Event;
use App\Models\User;
use App\Models\CertUser;
use App\Models\EventTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class CertificateController extends Controller
{
    public function certlist()
    {
        $certificates = CertTemplate::where('created_by', Auth::id())
                        ->where('status_id', 1)
                        ->get();
        return view('certificate.certlist', compact('certificates'));
    }

    public function create($certificateId = null)
    {
        $certificate = null;
    
        // Check if there is a certificate to edit
        if ($certificateId) {
            $certificate = CertTemplate::find($certificateId);
        }
    
        return view('certificate.create', compact('certificate'));
    }
    public function event_create($eventId)
    {
        $event = Event::findOrFail($eventId);
        $templates = CertTemplate::where('created_by', 1)->orWhereNull('created_by')->get();
        return view('certificate.event_create', compact('event', 'templates'));
    }
    public function saveCanvas(Request $request)
    {
        // Log the incoming request data
        Log::info('saveCanvas certificate_id:', ['certificate_id' => $request->input('certificate_id')]);

        // Start a database transaction
        DB::beginTransaction();

        // Store the image path in case we need to delete it later on failure
        $tempImagePath = null;

        try {
            // Validate the input
            $request->validate([
                'cert_name' => 'required|string|max:255',
                'canvas' => 'required|array',
                'image' => 'required|string',
                'certificate_id' => 'nullable|exists:cert_templates,id'
            ]);

            // Extract the canvas data and image data
            $canvasData = $request->input('canvas');
            $imageData = $request->input('image');
            $certName = $request->input('cert_name');
            $certificateId = $request->input('certificate_id'); // Optionally pass a certificate ID for update

            // Validate the image data format (base64)
            if (strpos($imageData, ',') === false) {
                return response()->json(['message' => 'Invalid image data format'], 400);
            }

            // Decode the base64 image data
            $imageBase64 = explode(',', $imageData)[1];

            // Generate file name and store the image temporarily
            $imageName = 'cert_template' . $certName . time() . '.png';
            $relativePath = 'storage/images/certificates/cert_templates/' . $imageName;
            $imagePath = storage_path('app/public/images/certificates/cert_templates/' . $imageName);
            $tempImagePath = $imagePath; // Store the temp path for rollback

            // Save the image to storage temporarily
            file_put_contents($imagePath, base64_decode($imageBase64));

            // If certificate ID is provided, update the existing certificate, else create a new one
            if ($certificateId) {
                // Updating existing certificate
                $certificate = CertTemplate::find($certificateId);

                if (!$certificate) {
                    return response()->json(['message' => 'Certificate not found'], 404);
                }

                $relPath = 'public/images/certificates/cert_templates/' . basename($certificate->cert_path);

                // Delete the old certificate image if it exists
                if (Storage::exists($relPath)) {
                    Storage::delete($relPath);
                }

                // Update the certificate details
                $certificate->update([
                    'template_name' => $certName,
                    'design' => json_encode($canvasData),
                    'path' => $relativePath,
                ]);

                Log::info('Certificate updated successfully: ' . $certName);

                $message = 'Certificate updated!';
            } else {
                // Creating a new certificate
                $certificate = CertTemplate::create([
                    'created_by' => Auth::id(),
                    'template_name' => $certName,
                    'design' => json_encode($canvasData),
                    'path' => $relativePath,
                    'status_id' => 1,  // Assuming status '1' is for active
                ]);

                Log::info('Certificate Template created successfully: ' . $certName);

                $message = 'Certificate Template created!';
            }

            // Commit the transaction
            DB::commit();

            // Return success response
            return response()->json([
                'message' => $message,
                'certificateId' => $certificate->id
            ]);

        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();

            // If the image was saved but the transaction failed, delete the saved image
            if ($tempImagePath && file_exists($tempImagePath)) {
                unlink($tempImagePath);
            }

            Log::error('Error saving certificate template: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while saving the certificate. Please try again.'
            ], 500);
        }
    }
    

    public function edit($id)
    {
        // Retrieve the certificate to be edited
        $certificate = Certificate::findOrFail($id);

        // Return the edit view with the certificate data
        return view('certificate.edit', compact('certificate'));
    }

    public function event_saveCanvas(Request $request, $id)
    {
        // Log the incoming request data
        Log::info('saveCanvas request data:', $request->all());

        $canvasData = $request->input('canvas');
        $imageData = $request->input('image');

        // Debugging: Check the type of $imageData
        if (is_array($imageData)) {
            Log::error('Invalid image data format: received array instead of string');
            return response()->json(['message' => 'Invalid image data format: received array instead of string'], 400);
        }

        // Validate the image data
        if (strpos($imageData, ',') === false) {
            Log::error('Invalid image data, missing comma');
            return response()->json(['message' => 'Invalid image data'], 400);
        }

        // Decode the base64 image
        $imageParts = explode(',', $imageData);
        if (count($imageParts) < 2) {
            Log::error('Invalid image data format, less than 2 parts');
            return response()->json(['message' => 'Invalid image data format'], 400);
        }
        $imageBase64 = $imageParts[1];

        $imageName = 'certificate_' . $id . '.png';
        $relativePath = 'storage/images/certificates/' . $imageName;
        $imagePath = storage_path('app/public/images/certificates/' . $imageName);

        $certificate = Certificate::where('event_id', $id)->first();
        
        if ($certificate !== null) {
            // Delete the old image if it exists
            if (Storage::exists('public/images/certificates/' . basename($certificate->cert_path))) {
                Storage::delete('public/images/certificates/' . basename($certificate->cert_path));
            }
        }

        file_put_contents($imagePath, base64_decode($imageBase64));

        // Check if a certificate for the given event ID already exists
        

        if ($certificate === null) {
            // Create a new certificate
            $certificate = new Certificate();
            $certificate->created_by = Auth::id();
            $certificate->event_id = $id;
            $certificate->design = json_encode($canvasData);
            $certificate->cert_path = $relativePath;
            $certificate->status_id = 2;
            $certificate->save();
        } else {
            // Update existing certificate
            $certificate->design = json_encode($canvasData);
            $certificate->cert_path = $relativePath;
            $certificate->save();
        }

        Log::info('Certificate saved successfully for event ID: ' . $id);

        return response()->json(['message' => 'Certificate saved!']);
    }

    public function event_saveCanvas_asTemplate(Request $request)
    {
        // Log incoming data
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Validate the incoming request
            $request->validate([
                'template_name' => 'required|string|max:255', // Validate template name
                'canvas' => 'required|array', // Validate the canvas design (JSON)
                'image' => 'required|string', // Validate base64 image data
                'event_id' => 'required|exists:events,id', 

            ]);

            // Extract data from the request
            $templateName = $request->input('template_name');
            $canvasData = $request->input('canvas');
            $imageData = $request->input('image');
            $eventId = $request->input('event_id');

            // Validate the image data format (base64)
            if (strpos($imageData, ',') === false) {
                return response()->json(['message' => 'Invalid image data format'], 400);
            }

            // Decode the base64 image data
            $imageBase64 = explode(',', $imageData)[1];

            // Generate the file name for the template image
            $imageName = 'template_' . $templateName. '_' . time() . '.png';
            $relativePath = 'storage/images/certificates/cert_templates/' . $imageName;
            $imagePath = storage_path('app/public/images/certificates/cert_templates/' . $imageName);

            
            $relation = EventTemplate::where('event_id', $eventId)->first();
            // Check if this is an update or a new creation
            if ($relation != null) {
                // Updating an existing certificate template
                $template = CertTemplate::find($relation->template_id);

                if (!$template) {
                    return response()->json(['message' => 'Template not found'], 404);
                }

                // Delete the old image if it exists
                if (Storage::exists('images/certificates/cert_templates/' . basename($template->path))) {
                    Storage::delete('images/certificates/cert_templates/' . basename($template->path));
                }
                Log::info('path name: ' . basename($template->path));
                // Update the existing template
                $template->update([
                    'template_name' => $templateName,
                    'design' => json_encode($canvasData),
                    'path' => $relativePath
                ]);

                
                Log::info('Certificate template updated successfully: ' . $templateName);

                $message = 'Template updated successfully!';
            } else {
                // Creating a new certificate template
                $template = CertTemplate::create([
                    'created_by' => Auth::id(),
                    'template_name' => $templateName,
                    'design' => json_encode($canvasData),
                    'path' => $relativePath,
                    'status_id' => 1,
                ]);

                EventTemplate::create([
                    'event_id' => $eventId,
                    'template_id' => $template->id,
                ]);

                Log::info('Certificate template created successfully: ' . $templateName);

                $message = 'Template created successfully!';
            }

            
            // Commit the transaction
            DB::commit();
            file_put_contents($imagePath, base64_decode($imageBase64));
            // Return success response
            return response()->json([
                'message' => $message,
                'templateId' => $template->id, 
            ]);
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();

            Log::error('Error saving certificate template: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while saving the template. Please try again.',
            ], 500);
        }
    }
    public function loadCanvas($id)
    {
        $certificate = CertTemplate::find($id);

        if ($certificate) {
            return response()->json(json_decode($certificate->design, true));
        } else {
            return response()->json(['message' => 'Certificate not found!'], 404);
        }
    }

    public function event_loadCanvas($id, $certId)
    {
        $certificate = Certificate::where('event_id', $id)->where('id', $certId)->first();

        if ($certificate) {
            return response()->json(json_decode($certificate->design, true));
        } else {
            return response()->json(['message' => 'Certificate not found!'], 404);
        }
    }

    public function getCertificateId($id)
    {
        $certificate = Certificate::where('event_id', $id)->first();

        if ($certificate) {
            return response()->json(['certificateId' => $certificate->id]);
        } else {
            return response()->json(['certificateId' => null]);
        }
    }
    public function showCertificateImage($id, $certId)
    {
        return view('certificate.view', compact('id', 'certId'));
    }

    public function viewImage($id, $certId)
    {
        $certificate = Certificate::where('event_id', $id)->where('id', $certId)->firstOrFail();

        $imagePath = $certificate->cert_path;
        if (Storage::exists($imagePath)) {
            return response()->file($imagePath);
        } else {
            return abort(404, 'Image not found');
        }
    }


    public function sendCertificates(Request $request, $event_id)
    {
        
        $data = $request->input('data'); // The array of {user_id, image_data} pairs

        foreach ($data as $certData) {
            $userId = $certData['userId'];
            $imageData = $certData['imageData'];

            // Create a unique path for each certificate image
            $pathDatabase = 'storage/images/certificates/'. $userId . '-' . $event_id . '_certificate.png';
            $imagePath = 'images/certificates/' . $userId . '-' . $event_id . '_certificate.png';

            // Store the image (assuming base64 encoded data)
            $imageContent = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
            Storage::disk('public')->put($imagePath, $imageContent);
            $certificate = Certificate::where('event_id', $event_id)->first();
            CertUser::create([
                'user_id' => $userId,
                'cert_id' => $certificate->id,
                'cert_path' => $pathDatabase,
            ]);
        }

        return response()->json(['message' => 'Certificates sent successfully!']);
    }
    public function getCertificateDesign($eventId)
    {
        // Find the certificate by event ID
        $certificate = Certificate::where('event_id', $eventId)->first();

        // If no certificate is found, return a 404 response
        if (!$certificate) {
            return response()->json(['message' => 'Certificate design not found'], 404);
        }

        // Return the design JSON from the certificate
        return response()->json(json_decode($certificate->design));
    }

    public function getTemplates()
    {
        // Fetch all certificates (or only those meant to be templates)
        $certificates = CertTemplate::where('status_id', 1)
                        ->where(function ($query) {
                            $query->where('created_by', Auth::id())
                                ->orWhereNull('created_by'); // Add this condition for null values
                        })
                        ->get(); // Adjust this query as needed

        // Return the certificate names and their design JSONs
        $templates = $certificates->map(function ($certificate) {
            return [
                'id' => $certificate->id,
                'name' => $certificate->template_name,
                'design' => $certificate->design,
                'path' =>$certificate->path  // The saved design JSON
            ];
        });

        return response()->json($templates);
    }


    public function deactivate($id)
    {
        $certificate = CertTemplate::findOrFail($id);

        // Set status_id to 2 for deactivation
        $certificate->status_id = 2;
        $certificate->save();

        return redirect()->route('certificate.list')->with('success', 'Certificate deleted successfully!');
    }

}
