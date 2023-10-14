<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CoursRessource extends JsonResource
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
                'nombres_cours_global' => $this->nombres_cours_global,
                'semestre' => $this->semestre,
                'etat'=>$this->etat,
                'anneeScolaire'=>$this->anneeScolaire,
                'User_module_id'=>$this->User_module,
                'classe'=>$this->classe->nom_classe,
                'session'=>$this->cours_classes

            // 'message' => $this->message,
        ];
    }
}
