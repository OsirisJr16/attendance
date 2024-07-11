<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContractType;

class ContractTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contractTypes = ContractType::all();
        return response()->json($contractTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:contract_types',
        ]);

        $contractType = ContractType::create($request->all());
        return response()->json($contractType, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contractType = ContractType::findOrFail($id);
        return response()->json($contractType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:contract_types,name,' . $id,
        ]);

        $contractType = ContractType::findOrFail($id);
        $contractType->update($request->all());
        return response()->json($contractType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        ContractType::destroy($id);
        return response()->json(null, 204);
    }
}
