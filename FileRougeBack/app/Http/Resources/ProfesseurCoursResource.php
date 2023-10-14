<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfesseurCoursResource extends JsonResource
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
            'semestre' => $this->semestre_id,
            'etat' => $this->etat,
            'professeur'=>$this->User_module->user,
        ];
    }
}
