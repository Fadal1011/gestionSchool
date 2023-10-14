<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function cours_classe_annee(){
        return $this->belongsToMany(CoursClasseAnnee::class,"session_cours_classes");
    }

    
}
