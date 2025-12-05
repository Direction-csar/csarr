<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\PublicRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestsController extends Controller
{
    /**
     * Afficher la liste des demandes (lecture seule pour DG)
     */
    public function index(Request $request)
    {
        try {
            $query = PublicRequest::with(['warehouse', 'user'])
                ->orderBy('created_at', 'desc');

            // Filtres
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('warehouse_id')) {
                $query->where('warehouse_id', $request->warehouse_id);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $requests = $query->paginate(20);

            // Statistiques
            $stats = [
                'total' => PublicRequest::count(),
                'pending' => PublicRequest::where('status', 'pending')->count(),
                'approved' => PublicRequest::where('status', 'approved')->count(),
                'rejected' => PublicRequest::where('status', 'rejected')->count(),
                'today' => PublicRequest::whereDate('created_at', today())->count(),
            ];

            // Entrepôts pour le filtre
            $warehouses = DB::table('warehouses')
                ->where('is_active', 1)
                ->orderBy('name')
                ->get();

            return view('dg.requests.index', compact('requests', 'stats', 'warehouses'));

        } catch (\Exception $e) {
            \Log::error('Erreur dans DG RequestsController@index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Erreur lors du chargement des demandes');
        }
    }

    /**
     * Afficher les détails d'une demande
     */
    public function show(PublicRequest $request)
    {
        try {
            $request->load(['warehouse', 'user', 'stockMovements']);

            return view('dg.requests.show', compact('request'));

        } catch (\Exception $e) {
            \Log::error('Erreur dans DG RequestsController@show', [
                'error' => $e->getMessage(),
                'request_id' => $request->id,
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Erreur lors du chargement de la demande');
        }
    }

    /**
     * Télécharger un document de demande
     */
    public function download(PublicRequest $request)
    {
        try {
            if (!$request->document_path || !file_exists(storage_path('app/' . $request->document_path))) {
                return redirect()->back()->with('error', 'Document non trouvé');
            }

            return response()->download(storage_path('app/' . $request->document_path));

        } catch (\Exception $e) {
            \Log::error('Erreur dans DG RequestsController@download', [
                'error' => $e->getMessage(),
                'request_id' => $request->id,
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Erreur lors du téléchargement du document');
        }
    }
}

