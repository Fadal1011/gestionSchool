<?php

namespace App\Http\Controllers;

use App\Models\Niveau;
use Illuminate\Http\Request;

class NiveauController extends Controller
{
    public function index()
    {
        $niveaux = Niveau::all();

        return response()->json([
            'success' => true,
            'data' => $niveaux,
        ]);
    }



    public function store(Request $request)
    {
        // Valider les données de la requête
        $validatedData = $request->validate([
            'libelle_niveau' => 'required|unique:niveaux,libelle_niveau',
        ]);

        // Vérifier si le niveau existe déjà
        $niveauExistant = Niveau::where('libelle_niveau', $validatedData['libelle_niveau'])->exists();

        if ($niveauExistant) {
            return response()->json([
                'success' => false,
                'message' => 'Le niveau existe déjà.',
            ], 422);
        }

        // Créer le nouvel enregistrement de niveau
        $niveau = Niveau::create([
            'libelle_niveau' => $validatedData['libelle_niveau'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Niveau ajouté avec succès.',
            'data' => $niveau,
        ]);
    }

    

    public function update(Request $request, $id)
    {
        // Valider les données de la requête
        $validatedData = $request->validate([
            'libelle_niveau' => 'required|unique:niveaux,libelle_niveau,' . $id,
        ]);

        $niveau = Niveau::findOrFail($id);
        $niveau->libelle_niveau = $validatedData['libelle_niveau'];
        $niveau->save();

        return response()->json([
            'success' => true,
            'message' => 'Niveau mis à jour avec succès.',
            'data' => $niveau,
        ]);
    }

    public function destroy($id)
    {
        $niveau = Niveau::findOrFail($id);
        $niveau->delete();

        return response()->json([
            'success' => true,
            'message' => 'Niveau supprimé avec succès.',
        ]);
    }
}
