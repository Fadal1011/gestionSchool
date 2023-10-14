<?php

namespace App\Http\Controllers;

use App\Http\Resources\CoursCollection;
use App\Http\Resources\CoursRessource;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Classe_annee;
use App\Models\Cours;
use App\Models\Module;
use App\Models\Semestre;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class coursController extends Controller
{

    public function all_module(){
        // $modules = Module::all();
        return response([
            "module" => Module::orderBy('created_at','desc')->with("user:id,nom,prenom,specialité,grade,username,date_naissance,role")->get(),
            "classes"=> Classe::with("niveau")->with("filiere")->get(),
        ]);
    }

    public function AllCours(){

        $cours = Cours::all();

        return new CoursCollection($cours);

        // return response([
        //     "cours"=>Cours::with("classe:nom_classe")->get(),
        // ]);
    }


    public function store(Request $request){

        $semestreActive = Semestre::where("Actif",true)->first();
        $anneeScolaireActive = AnneeScolaire::where("etat",true)->first();


        $classes = $request->classes;
        $classes_id = [];

        foreach($classes as $classe){
            $id_classe_annee = Classe_annee::where("classe_id", $classe)->where("annee_scolaire_id",$anneeScolaireActive->id)->first();
            array_push($classes_id, $id_classe_annee->id);
        }



        if(!$semestreActive || !$anneeScolaireActive){
            return response("invalide", Response::HTTP_CREATED);
        }
        $cours = Cours::create([
            "nombres_cours_global"=>$request->nombres_cours_global,
            "semestre_id"=>$semestreActive->id,
            "etat"=>1,
            "User_module_id"=>$request->user_module_id,
            "annee_scolaire_id"=>$anneeScolaireActive->id
        ]);



        $cours->classe_annee()->attach($classes_id);

        return $cours;
    }
}
