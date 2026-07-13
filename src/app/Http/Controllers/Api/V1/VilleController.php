<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Ville;

class VilleController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return Ville::all();
    }

    public function store(Request $request)
    {
        $this->authorize('create', Ville::class);

        $request->validate([
            'nom' => 'required|string|max:100'
        ]);

        $ville = Ville::create([
            'nom' => $request->nom
        ]);

        return response()->json($ville, 201);
    }
}
