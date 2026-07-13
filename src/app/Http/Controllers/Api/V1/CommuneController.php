<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Commune;

class CommuneController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return Commune::with('ville')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'ville_id' => 'required|exists:villes,id'
        ]);

        $commune = Commune::create([
            'nom' => $request->nom,
            'ville_id' => $request->ville_id
        ]);

        return response()->json($commune, 201);
    }
}