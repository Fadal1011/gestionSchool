<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ClasseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            return [
                "id" => $item->id,
                "nom" => $item->nom_classe,
                "niveau" => $item->niveau->libelle_niveau,
                "filiere" => $item->filiere->libelle,
                "session_cours" => $item->classe_annees
                    ->filter(function ($classeAnnee) {
                        return !$classeAnnee->cours_classe_annees->isEmpty();
                    })
                    ->flatMap(function ($classeAnnee) {
                        return $classeAnnee->cours_classe_annees
                            ->map(function ($coursClasseAnnee) {
                                return [
                                    "cours" => $coursClasseAnnee->cours->User_module->module,
                                    "sessions" => $coursClasseAnnee->session,
                                ];
                            })
                            ->toArray(); // Convert to array to remove empty arrays
                    })
                    ->toArray(),
            ];
        });
    }
}
