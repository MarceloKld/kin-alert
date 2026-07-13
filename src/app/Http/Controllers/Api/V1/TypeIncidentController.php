<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\TypeIncident;

class TypeIncidentController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return TypeIncident::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:100',
            'description' => 'nullable|string'
        ]);

        $type = TypeIncident::create([
            'titre' => $request->titre,
            'description' => $request->description
        ]);

        return response()->json($type, 201);
    }
}