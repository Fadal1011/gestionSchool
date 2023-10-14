<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{

    public function index()
    {
        $modules = Module::all();

        return response()->json([
            'success' => true,
            'data' => $modules,
        ]);
    }


    public function update(Request $request, $id)
    {
        // Valider les données de la requête
        $validatedData = $request->validate([
            'nom_module' => 'required|unique:modules,nom_module,' . $id,
        ]);

        $module = Module::findOrFail($id);
        $module->nom_module = $validatedData['nom_module'];
        $module->save();

        return response()->json([
            'success' => true,
            'message' => 'Module mis à jour avec succès.',
            'data' => $module,
        ]);
    }




    public function store(Request $request)
    {
        // Valider les données de la requête
        $validatedData = $request->validate([
            'nom_module' => 'required|unique:modules,nom_module',
        ]);

        // Vérifier si le module existe déjà
        $moduleExistant = Module::where('nom_module', $validatedData['nom_module'])->exists();

        if ($moduleExistant) {
            return response()->json([
                'success' => false,
                'message' => 'Le module existe déjà.',
            ], 422);
        }

        // Créer le nouvel enregistrement de module
        $module = Module::create([
            'nom_module' => $validatedData['nom_module'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Module ajouté avec succès.',
            'data' => $module,
        ]);
    }
    

    public function destroy($id)
    {
        $module = Module::findOrFail($id);
        $module->delete();

        return response()->json([
            'success' => true,
            'message' => 'Module supprimé avec succès.',
        ]);
    }
}
