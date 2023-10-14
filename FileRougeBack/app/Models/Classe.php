<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classe extends Model
{
    use HasFactory;

    public function niveau(){
        return $this->belongsTo(Niveau::class);
    }

    public function filiere(){
        return $this->belongsTo(Filiere::class);
    }

    // public function cours_classes(){
    //     return $this->hasMany(Cours_classe::class);
    // }

    public function classe_annees(){
        return $this->HasMany(Classe_annee::class);
    }

    protected $guarded = [];
}
