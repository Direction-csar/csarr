<?php

namespace App\Http\Controllers\DRH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DocumentsController extends Controller
{
    /**
     * Afficher la liste des documents RH
     */
    public function index()
    {
        try {
            $documents = \App\Models\HrDocument::orderBy('created_at', 'desc')->paginate(15);
            return view('drh.documents.index', compact('documents'));

        } catch (\Exception $e) {
            Log::error('Erreur dans DRH DocumentsController@index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return view('drh.documents.index', ['documents' => collect()]);
        }
    }
}

