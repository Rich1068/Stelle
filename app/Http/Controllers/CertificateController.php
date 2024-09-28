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

}
