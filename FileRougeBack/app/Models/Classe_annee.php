<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe_annee extends Model
{
    use HasFactory;

    public function classe(){
        return $this->belongsTo(Classe::class);
    }

    public function cours_classe_annees(){
        return $this->hasMany(CoursClasseAnnee::class);
    }


}
