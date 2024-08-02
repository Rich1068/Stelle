<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Certificate;
use App\Models\CertTemplate;
use App\Models\Event;
use PDF;

class CertificateController extends Controller
{
    public function create($eventId)
    {
        $event = Event::findOrFail($eventId);
        // Fetch all templates, including user-uploaded ones, based on event or globally available ones
        $templates = CertTemplate::where('user_id', 1)->orWhereNull('user_id')->get();

        return view('certificate.create', compact('event', 'templates'));
    }

    public function saveCanvas(Request $request, $id)
    {
        $canvasData = $request->input('canvas');

        // Check if a certificate for the given event ID already exists
        $certificate = Certificate::where('event_id', $id)->first();

        if ($certificate === null) {
            // Create a new certificate
            $certificate = new Certificate();
            $certificate->event_id = $id;
            $certificate->design = json_encode($canvasData);
            $certificate->save();
            return response()->json(['message' => 'Certificate saved!', 'certificateId' => $certificate->id]);
        } else {
            // Update existing certificate
            $certificate->design = json_encode($canvasData);
            $certificate->save();
            return response()->json(['message' => 'Certificate updated!']);
        }
    }
    public function loadCanvas($id, $certId)
    {
        // Find the certificate by ID and get its design
        $certificate = Certificate::where('event_id', $id)->where('id', $certId)->first();

        if ($certificate) {
            return response()->json(json_decode($certificate->design, true));
        } else {
            return response()->json(['message' => 'Certificate not found!'], 404);
        }
    }

    public function getCertificateId($id)
    {
        // Find the certificate by event ID
        $certificate = Certificate::where('event_id', $id)->first();

        if ($certificate) {
            return response()->json(['certificateId' => $certificate->id]);
        } else {
            return response()->json(['certificateId' => null]);
        }
    }
}
