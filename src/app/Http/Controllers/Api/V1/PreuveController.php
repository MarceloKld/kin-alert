<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Preuve;
use App\Models\Incident;

class PreuveController extends Controller
{
    use AuthorizesRequests;

    /**
     * ✅ Lister preuves d'un incident
     */
    public function index(Incident $incident)
    {
        return Preuve::where('incident_id', $incident->id)->get();
    }

    /**
     * ✅ Ajouter preuve à un incident
     */
    public function store(Request $request, Incident $incident)
    {
        $request->validate([
            'fichier' => 'required|string',
            'type_fichier' => 'required|string|max:50'
        ]);

        $preuve = Preuve::create([
            'incident_id' => $incident->id,
            'fichier' => $request->fichier,
            'type_fichier' => $request->type_fichier
        ]);

        return response()->json($preuve, 201);
    }
}