<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnneeScolaireRessource;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class AnneeScolaireController extends Controller
{

    public function index()
    {
        $anneesScolaires = AnneeScolaire::all();

        return response()->json([
            'success' => true,
            'data' => AnneeScolaireRessource::collection($anneesScolaires)
        ]);
    }


    public function store(Request $request)
    {
        $anneeScolaire = $request->anneeScolaire;

        // Vérifier si c'est la première année à insérer dans la table
        $premiereAnnee = AnneeScolaire::count() === 0;

        // Vérifier si l'année scolaire est unique
        $anneeScolaireUnique = AnneeScolaire::where('annee_scolaire_libelle', $anneeScolaire)->exists();

        // Vérifier si l'année est valide
        $annees = explode('/', $anneeScolaire);
        $anneeDebut = intval($annees[0]);
        $anneeFin = intval($annees[1]);

        $anneeValide = $anneeFin - $anneeDebut === 1;

        if ($anneeValide !=1){
            return response()->json([
                'success' => false,
                'message' => "l'Annee scolaire saisi est invalide la difference doit etre a 1",
            ]);
        }

        if($anneeScolaireUnique){
             return response()->json([
                'success' => false,
                'message' => "l'Annee scolaire saisi existe deja vous pouvez l'activer ou le modifier",
            ]);
        }

        if ($premiereAnnee) {
            $etat = true;
        } else {
            $etat = false;
        }

        $AnneeScolaire = AnneeScolaire::create([
            "annee_scolaire_libelle" => $anneeScolaire,
            "etat" => $etat
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Année scolaire ajoutée avec succès.',
            'data' =>new AnneeScolaireRessource($AnneeScolaire)
        ]);
    }


    public function delete($id)
    {
        $anneeScolaire = AnneeScolaire::find($id);

        if (!$anneeScolaire) {
            return response()->json([
                'success' => false,
                'message' => "L'année scolaire spécifiée n'existe pas.",
            ]);
        }

        $anneeScolaire->delete();

        return response()->json([
            'success' => true,
            'message' => 'Année scolaire supprimée avec succès.',
        ]);
    }


    public function updateEtat(Request $request, $id)
    {
        $anneeScolaire = AnneeScolaire::find($id);

        if (!$anneeScolaire) {
            return response()->json([
                'success' => false,
                'message' => "L'année scolaire spécifiée n'existe pas.",
            ]);
        }

        if ($anneeScolaire->etat) {
            return response()->json([
                'success' => false,
                'message' => "Impossible de modifier l'état. L'année scolaire est déjà activée.",
            ]);
        }

           // Rechercher l'année scolaire active actuellement
            $anneeActive = AnneeScolaire::where('etat', true)->first();

            if ($anneeActive) {
                // Désactiver l'année scolaire active actuellement
                $anneeActive->etat = false;
                $anneeActive->save();
            }

        $anneeScolaire->etat = true;
        $anneeScolaire->save();

        return response()->json([
            'success' => true,
            'message' => 'État de l\'année scolaire modifié avec succès.',
            'data' =>new AnneeScolaireRessource($anneeScolaire)
        ]);
    }
}
