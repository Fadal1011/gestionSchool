<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use Illuminate\Http\Request;

class FiliereController extends Controller
{
    public function index()
    {
        $filieres = Filiere::all();

        return response()->json([
            'success' => true,
            'data' => $filieres,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Valider les données de la requête
        $validatedData = $request->validate([
            'libelle' => 'required|unique:filieres,libelle,' . $id . '|max:255',
        ]);

        // Vérifier si la filière existe
        $filiere = Filiere::findOrFail($id);

        // Mettre à jour les données de la filière
        $filiere->libelle = $validatedData['libelle'];
        $filiere->save();

        return response()->json([
            'success' => true,
            'message' => 'Filière mise à jour avec succès.',
            'data' => $filiere,
        ]);
    }


    public function store(Request $request)
    {
        // Valider les données de la requête
        $validatedData = $request->validate([
            'libelle' => 'required|unique:filieres,libelle|max:255',
        ]);

        // Vérifier si la filière existe déjà
        $filiereExiste = Filiere::where('libelle', $validatedData['libelle'])->exists();

        if ($filiereExiste) {
            return response()->json([
                'success' => false,
                'message' => 'La filière existe déjà.',
            ], 422);
        }

        // Créer le nouvel enregistrement de filière
        $filiere = Filiere::create([
            'libelle' => $validatedData['libelle'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Filière ajoutée avec succès.',
            'data' => $filiere,
        ]);
    }

    public function delete($id)
    {
        // Vérifier si la filière existe
        $filiere = Filiere::findOrFail($id);

        // Supprimer la filière
        $filiere->delete();

        return response()->json([
            'success' => true,
            'message' => 'Filière supprimée avec succès.',
        ]);
    }
}
