<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $client = Client::all();
        if ($client->isEmpty())
            return response()->json(['message' => 'No clients found'], 404);
        return response()->json($client);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|min:1|max:255',
            'cpf' => 'required|cpf|formato_cpf|unique:clients',
            'birthDate' => 'required|date_format:Y-m-d|before:today|after:1900-01-01',
        ]);

        $client = Client::create($request->all());
        return response()->json(['message' => 'Client created successfully', $client], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        $client = Client::find($id);
        if ($client === null)
            return response()->json(['message' => 'Client not found'], 404);
        return response()->json($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        $client = Client::find($id);
        if ($client === null)
            return response()->json(['message' => 'Client not found'], 404);

        $request->validate([
            'name' => 'required|string|min:1|max:255',
            'cpf' => ['required', 'cpf', 'formato_cpf', Rule::unique('clients')->ignore($client->id)],
            'birthDate' => 'required|date_format:Y-m-d|before:today|after:1900-01-01',
        ]);

        $client->update($request->all());
        return response()->json(['message' => 'Client updated successfully', $client]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        $client = Client::find($id);
        if ($client === null)
            return response()->json(['message' => 'Client not found'], 404);
        $client->address()->delete();
        $client->delete();
        return response()->json(['message' => 'Client deleted successfully'], 200);
    }
}
