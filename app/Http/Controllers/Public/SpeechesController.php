<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Speech;
use Illuminate\Http\Request;

class SpeechesController extends Controller
{
    public function index()
    {
        $speeches = Speech::orderBy('date', 'desc')->get();
        return view('public.speeches', compact('speeches'));
    }
    
    public function show($id)
    {
        $speech = Speech::findOrFail($id);
        $relatedSpeeches = Speech::where('id', '!=', $id)
            ->orderBy('date', 'desc')
            ->limit(3)
            ->get();
            
        return view('public.speech', compact('speech', 'relatedSpeeches'));
    }
}
