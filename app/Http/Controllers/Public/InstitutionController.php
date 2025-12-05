<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PublicContent;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    public function index()
    {
        $institutionContent = PublicContent::where('section', 'institution')->get()->keyBy('key_name');
        return view('public.institution', compact('institutionContent'));
    }
}
