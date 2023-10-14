<?php

namespace App\Http\Controllers;

use App\Mail\SendEmailEleve;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Classe_annee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class eleveController extends Controller
{

    public function importerCsv(Request $request)
    {
        $anneeActif = AnneeScolaire::where('etat',1)->first();
        $file = $request->file('csv_file');
        $errors = []; // Tableau pour stocker les lignes avec des erreurs

        // Vérifiez si le fichier a été téléchargé avec succès
        if ($file) {
            $path = $file->getRealPath();

            // Ouvrir le fichier CSV en lecture
            $handle = fopen($path, 'r');

            // Ignorer la ligne d'en-tête
            $header = fgetcsv($handle);

            // Parcourir les lignes du fichier CSV
            while (($data = fgetcsv($handle)) !== false) {
                // Créer une nouvelle instance du modèle et assigner les valeurs

                $modèle = new User(); // Remplacez "Modèle" par le nom de votre modèle Eloquent


                $classeChoisir = Classe::where('nom_classe',$data[5])->first();
                if(!$classeChoisir){
                    $errors[] = $data;
                    continue; // Passer à la prochaine ligne

                }
                $classeAnnee = Classe_annee::where('classe_id',$classeChoisir->id)->where("annee_scolaire_id",$anneeActif->id)->first();

                if(!$classeAnnee){
                    $errors[] = $data;
                    continue;
                }
                // Assigner les valeurs aux colonnes du modèle
                $modèle->nom = $data[0];
                $modèle->prenom = $data[1];
                $modèle->username = $data[2];
                $dateNaissance = \DateTime::createFromFormat('d/m/Y', $data[3]);
                $modèle->date_naissance = $dateNaissance->format('Y-m-d');
                $modèle->password = "1234567";
                $modèle->role = "eleve";
                // Assigner d'autres colonnes...

                // Enregistrer le modèle dans la base de données
                $modèle->save();

                $modèle->classe_annee()->attach($classeAnnee);

                Mail::to($modèle->username)->send(new SendEmailEleve($modèle->username));

            }

            // Fermer le fichier CSV
            fclose($handle);

            if (empty($errors)) {
                return response()->json(["Toutes les données ont été enregistrées."]);
            } else {
                return response()->json(
                    [
                        'message' => 'Certaines lignes n\'ont pas pu être insérées.',
                        'errors' => $errors
                    ]
                    );
            }

            // Rediriger ou retourner une réponse appropriée
        } else {
            return response()->json(['message' => "le fichier n'a pas été telecharger"], 404);
        }
    }


    public function changePassword(Request $request,$email)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('username', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Mot de passe modifié avec succès']);
    }
}

