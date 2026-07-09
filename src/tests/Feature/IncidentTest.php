<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Utilisateur;
use App\Models\TypeIncident;
use App\Models\Commune;
use Laravel\Sanctum\Sanctum;

class IncidentTest extends TestCase
{
    

    public function test_user_can_create_incident()
    {
        $user = Utilisateur::factory()->create();

        Sanctum::actingAs($user);

        $type = TypeIncident::factory()->create();
        $commune = Commune::factory()->create();

        $response = $this->postJson('/api/v1/incidents', [
            'titre' => 'Test',
            'description' => 'Test desc',
            'type_incident_id' => $type->id,
            'latitude' => -4.3,
            'longitude' => 15.3,
            'commune_id' => $commune->id
        ]);

        $response->assertStatus(201);
    }
}