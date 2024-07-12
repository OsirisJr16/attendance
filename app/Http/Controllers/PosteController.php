<?php

namespace App\Http\Controllers;

use App\Models\Poste;
use Illuminate\Http\Request;
use Psy\CodeCleaner\ReturnTypePass;

class PosteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $postes = Poste::all();
        return response()->json($postes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255|unique:postes'
        ]);
        $postes = Poste::create($request->all());
        return response()->json($postes, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $postes = Poste::findOrFail($id);
        return response()->json($postes);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255|unique:postes'
        ]);
        $postes = Poste::findOrFail($id);
        $postes->update($request->all());
        return response()->json($postes);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Poste::destroy($id);
        return response()->json(null, 204);
    }
}
