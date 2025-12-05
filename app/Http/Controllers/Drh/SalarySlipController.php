<?php

namespace App\Http\Controllers\DRH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SalarySlipController extends Controller
{
    /**
     * Afficher la liste des fiches de paie
     */
    public function index()
    {
        try {
            $salarySlips = \App\Models\SalarySlip::with('personnel')
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return view('drh.salary-slips.index', compact('salarySlips'));

        } catch (\Exception $e) {
            Log::error('Erreur dans DRH SalarySlipController@index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return view('drh.salary-slips.index', ['salarySlips' => collect()]);
        }
    }
}

