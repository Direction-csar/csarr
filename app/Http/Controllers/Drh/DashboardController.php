<?php

namespace App\Http\Controllers\DRH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Afficher le tableau de bord DRH
     */
    public function index()
    {
        try {
            // Statistiques du personnel
            $stats = [
                'total_personnel' => \App\Models\Personnel::count(),
                'active_personnel' => \App\Models\Personnel::where('status', 'active')->count(),
                'new_this_month' => \App\Models\Personnel::whereMonth('created_at', now()->month)->count(),
                'departments' => \App\Models\Personnel::distinct('department')->count('department')
            ];

            // Personnel récent
            $recentPersonnel = \App\Models\Personnel::orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Présences du jour
            $todayAttendance = \App\Models\WorkAttendance::whereDate('date', today())
                ->count();

            // Documents RH récents
            $recentDocuments = \App\Models\HrDocument::orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Fiches de paie du mois
            $currentMonthSlips = \App\Models\SalarySlip::whereMonth('month', now()->month)
                ->whereYear('year', now()->year)
                ->count();

            return view('drh.dashboard', compact(
                'stats',
                'recentPersonnel',
                'todayAttendance',
                'recentDocuments',
                'currentMonthSlips'
            ));

        } catch (\Exception $e) {
            Log::error('Erreur dans DRH DashboardController@index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return view('drh.dashboard', [
                'stats' => [
                    'total_personnel' => 0,
                    'active_personnel' => 0,
                    'new_this_month' => 0,
                    'departments' => 0
                ],
                'recentPersonnel' => collect(),
                'todayAttendance' => 0,
                'recentDocuments' => collect(),
                'currentMonthSlips' => 0
            ]);
        }
    }
}

