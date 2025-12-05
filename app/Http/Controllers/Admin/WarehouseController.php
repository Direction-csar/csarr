<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        return view('admin.warehouse.index');
    }
    
    public function create()
    {
        return view('admin.warehouse.create');
    }
    
    public function store(Request $request)
    {
        // Logique de création
        return redirect()->route('admin.warehouse.index')
            ->with('success', 'Enregistrement créé avec succès.');
    }
    
    public function show($id)
    {
        // Logique d'affichage
        return view('admin.warehouse.show', compact('id'));
    }
    
    public function edit($id)
    {
        // Logique d'édition
        return view('admin.warehouse.edit', compact('id'));
    }
    
    public function update(Request $request, $id)
    {
        // Logique de mise à jour
        return redirect()->route('admin.warehouse.index')
            ->with('success', 'Enregistrement mis à jour avec succès.');
    }
    
    public function destroy($id)
    {
        // Logique de suppression
        return redirect()->route('admin.warehouse.index')
            ->with('success', 'Enregistrement supprimé avec succès.');
    }
}