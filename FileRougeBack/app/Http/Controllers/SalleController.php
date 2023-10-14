<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{

    public function index(){
        $salle = Salle::all();
        return response()->json([
            'success' => true,
            'message' => 'voici le liste de tout les salles.',
            'data' => $salle,
        ]);
    }

    public function store(Request $request)
    {
        // Vérifier si le nom de salle existe déjà
        $salleExist = Salle::where('nom_salle', $request->nom_salle)->first();
        if ($salleExist) {
            return response()->json([
                'success' => false,
                'message' => "Une salle avec ce nom existe déjà.",
            ]);
        }

        // Vérifier si le numéro de salle est déjà attribué
        $numeroExist = Salle::where('numero', $request->numero)->first();
        if ($numeroExist) {
            return response()->json([
                'success' => false,
                'message' => "Ce numéro de salle est déjà attribué.",
            ]);
        }

        // Vérifier si le nombre de places est supérieur à 20
        if ($request->nombre_place <= 20) {
            return response()->json([
                'success' => false,
                'message' => "Le nombre de places doit être supérieur à 20.",
            ]);
        }

        // Créer la salle
        $salle = Salle::create([
            "nom_salle" => $request->nom_salle,
            "numero" => $request->numero,
            "nombre_place" => $request->nombre_place
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Salle ajoutée avec succès.',
            'data' => $salle,
        ]);
    }


    public function update(Request $request, $id)
    {
        // Vérifier si la salle existe
        $salle = Salle::find($id);
        if (!$salle) {
            return response()->json([
                'success' => false,
                'message' => 'Salle non trouvée.',
            ]);
        }

        // Valider les données de la requête
        $validatedData = $request->validate([
            'nom_salle' => 'sometimes|unique:salles,nom_salle,' . $id . '|max:255',
            'numero' => 'sometimes|unique:salles,numero,' . $id,
            'nombre_place' => 'sometimes|numeric|min:21',
        ]);

        // Mettre à jour les données de la salle
        if ($request->has('nom_salle')) {
            $salle->nom_salle = $validatedData['nom_salle'];
        }
        if ($request->has('numero')) {
            $salle->numero = $validatedData['numero'];
        }
        if ($request->has('nombre_place')) {
            $salle->nombre_place = $validatedData['nombre_place'];
        }
        $salle->save();

        return response()->json([
            'success' => true,
            'message' => 'Salle mise à jour avec succès.',
            'data' => $salle,
        ]);
    }


    public function destroy($id)
    {
        // Vérifier si la salle existe
        $salle = Salle::find($id);
        if (!$salle) {
            return response()->json([
                'success' => false,
                'message' => 'Salle non trouvée.',
            ]);
        }

        // Supprimer la salle
        $salle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Salle supprimée avec succès.',
        ]);
    }
}
