<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PublicRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TrackController extends Controller
{
    public function index()
    {
        return view('public.track');
    }
    
    public function track(Request $request)
    {
        $request->validate([
            'tracking_code' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20',
        ]);
        
        $publicRequest = PublicRequest::where('tracking_code', $request->tracking_code)->first();
        
        if (!$publicRequest) {
            return view('public.track', ['notFound' => true]);
        }
        
        // Optional phone verification
        if ($request->phone && $publicRequest->phone !== $request->phone) {
            return view('public.track', ['notFound' => true]);
        }
        
        return view('public.track', ['request' => $publicRequest]);
    }
    
    public function download($code)
    {
        $publicRequest = PublicRequest::where('tracking_code', $code)->first();
        
        if (!$publicRequest) {
            abort(404);
        }
        
        $pdf = Pdf::loadView('public.pdf.request', ['request' => $publicRequest]);
        
        return $pdf->download("demande-{$publicRequest->tracking_code}.pdf");
    }
}
