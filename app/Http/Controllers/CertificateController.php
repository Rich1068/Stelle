<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Certificate;
use App\Models\CertTemplate;
use App\Models\Event;
use App\Models\User;
use App\Models\CertUser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
class CertificateController extends Controller
{
    public function create($eventId)
    {
        $event = Event::findOrFail($eventId);
        $templates = CertTemplate::where('user_id', 1)->orWhereNull('user_id')->get();
        return view('certificate.create', compact('event', 'templates'));
    }

    public function saveCanvas(Request $request, $id)
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

    public function loadCanvas($id, $certId)
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


    public function sendCertificates(Request $request, $eventId)
    {
        // Validate the incoming request
        $request->validate([
            'participants' => 'required|array',  // Ensure we have an array of selected participants
            'participants.*' => 'exists:users,id',  // Validate that each participant ID exists in the users table
            'images' => 'required|array',  // Ensure images for each participant are provided
            'images.*' => 'required|string',  // Each image must be a base64 string
        ]);

        $userIds = $request->input('participants');  // Get the selected participant IDs
        $images = $request->input('images');  // Get the base64 images for each participant

        // Get the template certificate for the event
        $certificate = Certificate::where('event_id', $eventId)->first();

        if (!$certificate) {
            return redirect()->back()->with('error', 'Certificate design not found for this event.');
        }

        // Loop through each selected user and generate their certificate
        foreach ($userIds as $index => $userId) {
            $user = User::findOrFail($userId);  // Get user by ID
            $fullname = $user->first_name . ' ' . ($user->middle_name ? $user->middle_name . ' ' : '') . $user->last_name;

            // Load and personalize the certificate design JSON for this user
            $canvasData = json_decode($certificate->design, true);
            $personalizedCanvas = $this->replacePlaceholdersInDesign($canvasData, $fullname);

            // Convert the personalized JSON into a base64 image (use the front-end toDataURL)
            $imageData = $images[$index];  // Personalized image data for this user

            // Save the personalized certificate image
            $personalizedImagePath = $this->savePersonalizedCertificateImage($imageData, $eventId, $fullname);

            // Save the personalized certificate in the 'cert_users' table
            CertUser::create([
                'user_id' => $userId,
                'cert_id' => $certificate->id,
                'cert_path' => $personalizedImagePath,  // Save the personalized certificate path
            ]);
        }

        return redirect()->back()->with('success', 'Personalized certificates generated and saved successfully!');
    }

    private function replacePlaceholdersInDesign($canvasData, $userName)
    {
        // Replace the {{name}} placeholder with the actual user's name
        array_walk_recursive($canvasData, function (&$item) use ($userName) {
            $item = str_replace('{{name}}', $userName, $item);
        });

        return $canvasData;
    }

    private function savePersonalizedCertificateImage($imageData, $eventId, $userName)
    {
        // Validate the image data format
        if (strpos($imageData, ',') === false) {
            Log::error('Invalid image data format, missing comma');
            return response()->json(['message' => 'Invalid image data format'], 400);
        }

        // Split the base64 string into two parts
        $imageParts = explode(',', $imageData);

        // Check if there are exactly two parts (data prefix and the base64-encoded image)
        if (count($imageParts) < 2) {
            Log::error('Invalid image data format, less than 2 parts');
            return response()->json(['message' => 'Invalid image data format'], 400);
        }

        // Get the base64-encoded image (the second part)
        $imageBase64 = $imageParts[1];

        // Generate a unique name for the personalized certificate
        $imageName = 'certificate_' . $eventId . '_' . Str::slug($userName) . '.png';
        $relativePath = 'storage/images/certificates/' . $imageName;
        $imagePath = storage_path('app/public/images/certificates/' . $imageName);

        // Save the base64-decoded image to the file system
        try {
            file_put_contents($imagePath, base64_decode($imageBase64));

            // Return the relative path for saving in the database
            return $relativePath;
        } catch (\Exception $e) {
            Log::error('Error saving the image: ' . $e->getMessage());
            return response()->json(['message' => 'Error saving image'], 500);
        }

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

}
