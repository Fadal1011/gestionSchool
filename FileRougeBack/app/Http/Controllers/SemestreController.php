<?php

namespace App\Http\Controllers;

use App\Http\Resources\SemestreResource;
use App\Models\Semestre;
use Illuminate\Http\Request;

class SemestreController extends Controller
{
    public function index()
    {
        $semestres = Semestre::all();

        return SemestreResource::collection($semestres);
    }


    public function store(Request $request)
    {
        // Valider les données de la requête
        $validatedData = $request->validate([
            'libelle_semestre' => 'required|unique:semestres,libelle_semestre',
        ]);

        // Vérifier si d'autres enregistrements de semestre existent
        $autresSemestresExistants = Semestre::exists();

        // Définir la valeur de l'attribut "actif" en fonction de la présence d'autres enregistrements
        $actif = !$autresSemestresExistants;

        // Créer le nouvel enregistrement de semestre
        $semestre = Semestre::create([
            'libelle_semestre' => $validatedData['libelle_semestre'],
            'actif' => $actif,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Semestre ajouté avec succès.',
            'data' => new SemestreResource($semestre),
        ]);
    }

    public function updateActif(Request $request, $id)
    {
        // Valider les données de la requête


        $semestre = Semestre::findOrFail($id);

        $semesttreActive = Semestre::where('actif', true)->first();

            if ($semesttreActive) {
                // Désactiver le semestre active actuellement
                $semesttreActive->actif = false;
                $semesttreActive->save();
            }

            $semestre->actif = true;
            $semestre->save();

            return response()->json([
                'success' => true,
                'message' => 'Attribut "actif" du semestre mis à jour avec succès.',
                'data' => new SemestreResource($semestre),
            ]);
    }


    public function destroy($id)
    {
        $semestre = Semestre::findOrFail($id);
        $semestre->delete();

        return response()->json([
            'success' => true,
            'message' => 'Semestre supprimé avec succès.',
        ]);
    }

}
