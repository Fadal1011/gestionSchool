<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours_classe extends Model
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
}
