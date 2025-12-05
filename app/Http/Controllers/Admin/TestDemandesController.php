<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use Illuminate\Http\Request;

class TestDemandesController extends Controller
{
    public function index()
    {
        try {
            // Test simple
            $demandes = Demande::all();
            return response()->json([
                "success" => true,
                "count" => $demandes->count(),
                "demandes" => $demandes->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString()
            ]);
        }
    }
}