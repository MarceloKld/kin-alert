<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class IncidentFilter
{
    protected Request $request;

    protected array $allowedFilters = [
        'statut',
        'commune_id',
        'type_incident_id'
    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query): Builder
    {
        foreach ($this->allowedFilters as $filter) {

            if ($this->request->filled($filter)) {
                $query->where($filter, $this->request->get($filter));
            }
        }

        // 🔍 Recherche texte sécurisée
        if ($this->request->filled('search')) {

            $search = $this->request->get('search');

            $query->where(function ($q) use ($search) {
                $q->where('titre', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        return $query;
    }
}