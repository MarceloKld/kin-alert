<?php

namespace App\Filters;

use Illuminate\Http\Request;

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

    public function apply($query)
    {
        foreach ($this->allowedFilters as $filter) {
            if ($this->request->has($filter)) {
                $query->where($filter, $this->request->get($filter));
            }
        }

        // Recherche texte
        if ($this->request->has('search')) {
            $search = $this->request->get('search');

            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query;
    }
}