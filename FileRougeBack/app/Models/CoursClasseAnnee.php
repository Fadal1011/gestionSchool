<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursClasseAnnee extends Model
{
    use HasFactory;
    public function session(){
        return $this->belongsToMany(Session::class,"session_cours_classes");
    }

    public function cours(){
        return $this->belongsTo(Cours::class);
    }
    public function classe(){
        return $this->belongsTo(Classe::class);
    }

    public function classe_annee(){
        return $this->belongsTo(Classe_annee::class,"classe_annee_id");
    }

    public function session_cours_classe(){
        return $this->hasMany(session_cours_classe::class);
    }
}
