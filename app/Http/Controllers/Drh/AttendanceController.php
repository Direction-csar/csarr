<?php

namespace App\Http\Controllers\DRH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * Afficher la liste des présences
     */
    public function index()
    {
        try {
            $attendance = \App\Models\WorkAttendance::with('personnel')
                ->orderBy('date', 'desc')
                ->paginate(15);

            return view('drh.attendance.index', compact('attendance'));

        } catch (\Exception $e) {
            Log::error('Erreur dans DRH AttendanceController@index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return view('drh.attendance.index', ['attendance' => collect()]);
        }
    }
}

