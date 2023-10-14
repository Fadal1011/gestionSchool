<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cours extends Model
{
    use HasFactory;


    public function User_module(){
        return $this->belongsTo(User_module::class,"user_module_id");
    }

    public function semestre(){
        return $this->belongsTo(Semestre::class);
    }

    public function anneeScolaire(){
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function cours_classe_annees(){
        return $this->hasMany(CoursClasseAnnee::class);
    }

    public function classe_annee(): BelongsToMany
    {
        return $this->belongsToMany(Classe_annee::class, 'cours_classe_annees', 'cours_id', 'classe_annee_id');
    }

    protected $guarded = [];
}
