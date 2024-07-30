<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\Certificate;
use App\Models\CertTemplate;
use App\Models\Event;
use Dompdf\Dompdf;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function create($eventId)
    {
        // Fetch the event details if needed (assuming an Event model exists)
        $event = Event::findOrFail($eventId);

        return view('certificate.create', compact('event'));
    }

    // Method to load a template image
    public function loadTemplate($templateName)
    {
        $templatePath = 'storage/images/event_banners/' . $templateName;

        if (file_exists($templatePath)) {
            return response()->file($templatePath);
        }

        return response('Template not found', 404);
    }

    // Method to save the edited certificate
    public function saveCertificate(Request $request)
    {
        $validatedData = $request->validate([
            'html' => 'required|string',
            'css' => 'required|string',
            'event_id' => 'required|exists:events,id',
        ]);

        // Save the certificate details to the database
        \DB::table('certificates')->insert([
            'html' => $validatedData['html'],
            'css' => $validatedData['css'],
            'event_id' => $validatedData['event_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
