<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class sessionEnCoursRessource extends JsonResource
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
            'id' => $this->id,
            'date' => $this->date,
            'heure_debut' => $this->heure_debut,
            'heure_fin' => $this->heure_fin,
            'professeur'=>$this->cours_classe_annee->first()->cours->User_module->user,
            'module'=>$this->cours_classe_annee->first()->cours->User_module->module,
        ];
    }
}
