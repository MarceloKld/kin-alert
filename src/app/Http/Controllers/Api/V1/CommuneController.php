<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Commune;
use Illuminate\Http\Request;

class CommuneController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
       $communes = Commune::with('ville')
             ->orderBy('nom')
            ->paginate(10);

        return response()->json($communes);

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