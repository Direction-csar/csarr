<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        return response()->json(['status' => 'OK', 'message' => 'Test réussi']);
    }
    
    public function testForm()
    {
        return view('public.demande');
    }
    
    public function testSubmit(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Test de soumission réussi',
            'data' => $request->all()
        ]);
    }
}

