<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClasseRessource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" =>$this->id,
            "nom" => $this->nom_classe,
            "niveau" => $this->niveau->libelle_niveau,
            "filiere" => $this->filiere->libelle,
            "cours" => $this->classe_annees
                ->filter(function ($classeAnnee) {
                    return !$classeAnnee->cours_classe_annees->isEmpty();
                })
                ->flatmap(function ($classeAnnee) {
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
    }
}
