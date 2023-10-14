<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CoursCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($cours) {
                return [
                    'id' => $cours->id,
                    'nombres_cours_global' => $cours->nombres_cours_global,
                    'semestre' => $cours->semestre->libelle_semestre,
                    'etat' => $cours->etat,
                    'anneeScolaire' => $cours->anneeScolaire->annee_scolaire_libelle,
                    'user' => $cours->User_module->user,
                    'module' => $cours->User_module->module,
                    'classe' => $cours->cours_classe_annees->map(function ($item) {
                        return $item->classe_annee->classe;
                    }),
                ];
            }),
        ];
    }
}
