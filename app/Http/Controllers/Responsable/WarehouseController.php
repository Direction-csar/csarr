<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function edit()
    {
        return view('responsable.warehouse.edit');
    }

    public function update(Request $request)
    {
        return redirect()->route('responsable.dashboard')->with('success', 'Entrepôt mis à jour !');
    }
}
