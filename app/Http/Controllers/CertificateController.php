<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Certificate;
use App\Models\CertTemplate;
use App\Models\Event;
use App\Models\User;
use App\Models\CertUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class CertificateController extends Controller
{
    public function certlist()
    {
        $certificates = Certificate::where('created_by', Auth::id())
                        ->where('status_id', 1)
                        ->get();
        return view('certificate.certlist', compact('certificates'));
    }

    public function create($certificateId = null)
    {
        $templates = CertTemplate::all();
        $certificate = null;
    
        // Check if there is a certificate to edit
        if ($certificateId) {
            $certificate = Certificate::find($certificateId);
        }
    
        return view('certificate.create', compact('templates', 'certificate'));
    }
    public function event_create($eventId)
    {
        $event = Event::findOrFail($eventId);
        $templates = CertTemplate::where('user_id', 1)->orWhereNull('user_id')->get();
        return view('event_certificate.create', compact('event', 'templates'));
    }
    public function saveCanvas(Request $request)
    {
        // Log the incoming request data
        Log::info('saveCanvas certificate_id:', ['certificate_id' => $request->input('certificate_id')]);
    
        // Start a database transaction
        DB::beginTransaction();
    
        try {
            // Validate the input
            $request->validate([
                'cert_name' => 'required|string|max:255',
                'canvas' => 'required|array',
                'image' => 'required|string',
                'certificate_id' => 'nullable|exists:certificates,id'
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
    
            // Generate file name and store the image
            $imageName = 'certificate_' . $certName . time() . '.png';
            $relativePath = 'storage/images/certificates/' . $imageName;
            $imagePath = storage_path('app/public/images/certificates/' . $imageName);
    
            // Save the image to storage
            file_put_contents($imagePath, base64_decode($imageBase64));
    
            // If certificate ID is provided, update the existing certificate, else create a new one
            if ($certificateId) {
                // Updating existing certificate
                $certificate = Certificate::find($certificateId);
                
                if (!$certificate) {
                    return response()->json(['message' => 'Certificate not found'], 404);
                }
    
                $relPath = 'images/certificates/' . basename($certificate->cert_path);

                if (Storage::exists($relPath)) {
                    Storage::delete($relPath);
                }
    
                // Update the certificate details
                $certificate->update([
                    'cert_name' => $certName,
                    'design' => json_encode($canvasData),
                    'cert_path' => $relativePath,
                ]);
    
                Log::info('Certificate updated successfully: ' . $certName);
    
                $message = 'Certificate updated!';
    
            } else {
                // Creating a new certificate
                $certificate = Certificate::create([
                    'created_by' => Auth::id(),
                    'cert_name' => $certName,
                    'design' => json_encode($canvasData),
                    'cert_path' => $relativePath,
                    'status_id' => 1,  // Assuming status '1' is for active
                ]);
    
                Log::info('Certificate created successfully: ' . $certName);
    
                $message = 'Certificate created!';
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
            Log::error('Error saving certificate: ' . $e->getMessage());
    
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

    public function update(Request $request, $id)
    {
        // Log the incoming request data
        Log::info('update request data:', $request->all());

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Validate the input
            $request->validate([
                'cert_name' => 'required|string|max:255',
                'canvas' => 'required|array',
                'image' => 'required|string',
            ]);

            // Get the canvas data and the new image data
            $canvasData = $request->input('canvas');
            $imageData = $request->input('image');
            $certName = $request->input('cert_name');

            // Validate the image data format (base64)
            if (strpos($imageData, ',') === false) {
                return response()->json(['message' => 'Invalid image data format'], 400);
            }

            // Find the certificate by ID
            $certificate = Certificate::findOrFail($id);

            // Delete the old certificate image from storage if it exists
            if ($certificate->cert_path && Storage::exists('public/images/certificates/' . basename($certificate->cert_path))) {
                Storage::delete('public/images/certificates/' . basename($certificate->cert_path));
            }

            // Decode the new base64 image data
            $imageBase64 = explode(',', $imageData)[1];

            // Generate file name for the new image
            $imageName = 'certificate_' . time() . '.png';
            $relativePath = 'storage/images/certificates/' . $imageName;
            $imagePath = storage_path('app/public/images/certificates/' . $imageName);

            // Save the new image to storage
            file_put_contents($imagePath, base64_decode($imageBase64));

            // Update the certificate with the new name, design, and image path
            $certificate->update([
                'cert_name' => $certName,
                'design' => json_encode($canvasData),
                'cert_path' => $relativePath,
            ]);

            // Commit the transaction
            DB::commit();

            // Log success and return response
            Log::info('Certificate updated successfully: ' . $certName);

            return response()->json([
                'message' => 'Certificate updated!',
                'certificateId' => $certificate->id
            ]);

        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            Log::error('Error updating certificate: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while updating the certificate. Please try again.'
            ], 500);
        }
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
            $certificate->event_id = $id;
            $certificate->design = json_encode($canvasData);
            $certificate->cert_path = $relativePath;
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
    public function loadCanvas($id)
    {
        $certificate = Certificate::find($id);

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


    public function deactivate($id)
    {
        $certificate = Certificate::findOrFail($id);

        // Set status_id to 2 for deactivation
        $certificate->status_id = 2;
        $certificate->save();

        return redirect()->route('certificate.list')->with('success', 'Certificate deleted successfully!');
    }

}
