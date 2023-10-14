<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSessionResource extends JsonResource
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
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'specialité' => $this->specialité,
            'grade' => $this->grade,
            'username' => $this->username,
            'role' => $this->role,
            'sessions' => $this->user_module->flatMap(function ($module) {
                return $module->cours->flatMap(function ($cours) {
                    return $cours->cours_classe_annees->flatMap(function ($cours_classe_annee) {
                        return $cours_classe_annee->session_cours_classe->map(function ($session_cours_classe) {
                            return $session_cours_classe->session;
                        });
                    });
                });
            }),
        ];
    }
}
