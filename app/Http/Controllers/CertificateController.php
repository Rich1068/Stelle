<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\Certificate;
use App\Models\CertTemplate;
use App\Models\Event;
use PDF;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function create($eventId)
    {
        $event = Event::findOrFail($eventId);
        $templates = CertTemplate::where('id', 1)->get();

        return view('certificate.create', compact('event', 'templates'));
    }

    public function saveTemplate(Request $request, $eventId)
    {
        $templateHtml = $request->input('template_html');

        $certTemplate = new CertTemplate();
        $certTemplate->event_id = $eventId;
        $certTemplate->html = $templateHtml;
        $certTemplate->save();

        return response()->json(['success' => true]);
    }

    public function downloadCertificate(Request $request, $eventId)
    {
        $templateHtml = $request->input('template_html');

        // Generate PDF from HTML
        $pdf = PDF::loadHTML($templateHtml);
        return $pdf->download('certificate.pdf');
    }

    public function save(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        $certificate = Certificate::updateOrCreate(
            ['event_id' => $event->id],
            ['cert_path' => $request->template_json]
        );

        return response()->json(['success' => true, 'certificate' => $certificate]);
    }

    public function load($eventId)
    {
        $event = Event::findOrFail($eventId);
        $certificate = Certificate::where('event_id', $event->id)->first();

        return response()->json(['certificate' => $certificate]);
    }
}
