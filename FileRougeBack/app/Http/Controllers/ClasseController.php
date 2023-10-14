<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index()
    {
        $classes = Classe::all();

        return response()->json([
            'success' => true,
            'data' => $classes,
        ]);

        
    }


    public function store(Request $request){

        $filiere = Filiere::where("id",$request->filiere_id)->first();
        $niveaux = Niveau::find($request->niveau_id);
        $classe_nom = Classe::where("nom_classe",$request->nom_classe)->first();

        if(!$filiere){
            return response()->json([
                'success' => false,
                'message' => "cette filiere n'existe pas ",
            ]);
        }

        if(!$niveaux){
            return response()->json([
                'success' => false,
                'message' => "cette niveaux n'existe pas ",
            ]);
        }


        if($classe_nom){
            return response()->json([
                'success' => false,
                'message' => "cette classe existe deja dans le base de donnée ",
            ]);
        }


        $classe = Classe::create([
            "nom_classe"=>$request->nom_classe,
            "filiere_id"=>$filiere->id,
            "niveau_id"=>$niveaux->id
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Classe ajoutée avec succès.',
            'data' => $classe,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Valider les données de la requête
        $validatedData = $request->validate([
            'nom_classe' => 'sometimes|unique:classes,nom_classe,' . $id . '|max:255',
            'filiere_id' => 'sometimes|exists:filieres,id',
            'niveau_id' => 'sometimes|exists:niveaux,id',
        ]);

        // Vérifier si la classe existe
        $classe = Classe::findOrFail($id);

        // Mettre à jour les données de la classe
        if ($request->has('nom_classe')) {
            $classe->nom_classe = $validatedData['nom_classe'];
        }
        if ($request->has('filiere_id')) {
            $classe->filiere_id = $validatedData['filiere_id'];
        }
        if ($request->has('niveau_id')) {
            $classe->niveau_id = $validatedData['niveau_id'];
        }
        $classe->save();

        return response()->json([
            'success' => true,
            'message' => 'Classe mise à jour avec succès.',
            'data' => $classe,
        ]);
    }


    public function delete($id)
    {
        // Vérifier si la classe existe
        $classe = Classe::findOrFail($id);

        // Supprimer la classe
        $classe->delete();

        return response()->json([
            'success' => true,
            'message' => 'Classe supprimée avec succès.',
        ]);
    }

}
