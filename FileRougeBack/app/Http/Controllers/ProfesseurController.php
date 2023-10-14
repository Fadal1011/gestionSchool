<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfesseurController extends Controller
{
    public function index()
    {
        $professeurs = User::where('role', 'professeur')->get();

        return response()->json([
            'success' => true,
            'data' => $professeurs,
        ]);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'specialite' => 'required|string|max:255',
            'grade' => 'required|string|max:255',
        ]);

        // Vérifier si le professeur existe déjà
        $professeurExist = User::where('username', $validatedData['username'])->where('role', 'professeur')->first();
        if ($professeurExist) {
            return response()->json([
                'success' => false,
                'message' => "Ce professeur existe déjà.",
            ]);
        }

        // Vérifier si le nom d'utilisateur est déjà attribué à un autre professeur
        $usernameExist = User::where('username', $validatedData['username'])->where('role', '!=', 'professeur')->first();
        if ($usernameExist) {
            return response()->json([
                'success' => false,
                'message' => "Ce nom d'utilisateur est déjà attribué à un autre utilisateur.",
            ]);
        }

        // Créer le professeur
        $professeur = User::create([
            "nom" => $validatedData['nom'],
            "prenom" => $validatedData['prenom'],
            "username" => $validatedData['username'],
            "specialité" => $validatedData['specialite'],
            "grade" => $validatedData['grade'],
            "role" => "professeur",
            "password" => "0000",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Professeur ajouté avec succès.',
            'data' => $professeur,
        ]);
    }


    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'username' => 'sometimes',
            'specialite' => 'sometimes|string|max:255',
            'grade' => 'sometimes|string|max:255',
        ]);

        // Vérifier si le professeur existe
        $professeur = User::where('id', $id)->where('role', 'professeur')->first();
        if (!$professeur) {
            return response()->json([
                'success' => false,
                'message' => "Ce professeur n'existe pas.",
            ]);
        }


        // Mettre à jour le professeur
        $professeur->update([
            'nom' => $validatedData['nom'],
            'prenom' => $validatedData['prenom'],
            'username' => $validatedData['username'],
            'specialité' => $validatedData['specialite'],
            'grade' => $validatedData['grade'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Professeur mis à jour avec succès.',
            'data' => $professeur,
        ]);
    }


    

    public function destroy($id)
    {
        // Vérifier si le professeur existe
        $professeur = User::where('id', $id)->where('role', 'professeur')->first();
        if (!$professeur) {
            return response()->json([
                'success' => false,
                'message' => "Ce professeur n'existe pas.",
            ]);
        }

        // Supprimer le professeur
        $professeur->delete();

        return response()->json([
            'success' => true,
            'message' => 'Professeur supprimé avec succès.',
        ]);
    }

}
