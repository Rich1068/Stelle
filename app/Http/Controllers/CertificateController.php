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

    public function saveTemplate(Request $request, $eventId)
    {
        $templateJson = $request->input('template_json');
        $isUploaded = $request->input('is_uploaded', false);

        $certTemplate = CertTemplate::create([
            'event_id' => $eventId,
            'json' => $templateJson,
            'is_uploaded' => $isUploaded,
        ]);

        return response()->json(['success' => true, 'template' => $certTemplate]);
    }

    public function downloadCertificate(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $template = CertTemplate::where('event_id', $eventId)->firstOrFail();

        $templateHtml = $this->convertJsonToHtml($template->json);

        $pdf = PDF::loadHTML($templateHtml);
        return $pdf->download('certificate.pdf');
    }

    public function save(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $certPath = $request->input('cert_path');

        $certificate = Certificate::updateOrCreate(
            ['event_id' => $event->id],
            ['cert_path' => $certPath]
        );

        return response()->json(['success' => true, 'certificate' => $certificate]);
    }

    public function load($eventId)
    {
        $event = Event::findOrFail($eventId);
        $certificate = Certificate::where('event_id', $event->id)->first();

        return response()->json(['certificate' => $certificate]);
    }

    private function convertJsonToHtml($json)
    {
        $templateData = json_decode($json, true);
        $html = '<div>Your HTML content based on JSON goes here</div>';
        return $html;
    }
}
