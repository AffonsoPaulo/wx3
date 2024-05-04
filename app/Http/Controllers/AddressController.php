<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $address = Address::all();
        if ($address->isEmpty())
            return response()->json(['message' => 'No addresses found'], 404);
        return response()->json($address);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'street' => 'required|string',
            'city' => 'required|string',
            'neighborhood' => 'required|string',
            'state' => 'required|string|uf',
            'number' => 'required|numeric',
            'zipCode' => 'required|formato_cep',
            'client_id' => 'required|exists:clients,id',
        ]);

        $address = Address::create($request->all());
        return response()->json(['message' => 'Address created successfully', $address], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $address = Address::find($id);
        if ($address === null)
            return response()->json(['message' => 'Address not found'], 404);
        return response()->json($address);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $address = Address::find($id);
        if ($address === null)
            return response()->json(['message' => 'Address not found'], 404);

        $request->validate([
            'street' => 'required|string',
            'city' => 'required|string',
            'neighborhood' => 'required|string',
            'state' => 'required|string|uf',
            'number' => 'required|numeric',
            'zipCode' => 'required|formato_cep',
            'client_id' => 'required|exists:clients,id',
        ]);

        $address->update($request->all());
        return response()->json(['message' => 'Address updated successfully', $address]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $address = Address::find($id);
        if ($address === null)
            return response()->json(['message' => 'Address not found'], 404);
        $address->delete();
        return response()->json(['message' => 'Address deleted successfully']);
    }
}
