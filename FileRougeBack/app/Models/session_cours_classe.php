<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class session_cours_classe extends Model
{
    use HasFactory;

    public function cours_classe_annee(){
        return $this->belongsTo(CoursClasseAnnee::class);
    }

    public function session(){
        return $this->belongsTo(Session::class);
    }
}
