<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClasseCollection;
use App\Http\Resources\ClasseRessource;
use App\Http\Resources\ProfesseurCoursResource;
use App\Http\Resources\sessionEnCoursRessource;
use App\Http\Resources\UserSessionResource;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Cours;
use App\Models\CoursClasseAnnee;
use App\Models\Session;
use App\Models\session_cours_classe;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;


class sessionCrontroller extends Controller
{

    public function AllClassesModule($id){
        // $all = Classe::where('id', $id)->get();
        // return ClasseRessource::collection($all);

        $all = Classe::with("classe_annees")->where("id",$id)->get();
        return ClasseRessource::collection($all);
    }

    public function anneeActive(){
        $annee = AnneeScolaire::where('etat',1)->first();
        return $annee;
    }



    public function store(Request $request)
    {

        $heure_debut_convertie = $request->heure_debut . ":00";
        $heure_fin_convertie = $request->heure_fin . ":00";

           // -------------------SAVOIR SI LE PROFESSEUR EST DISPNIBLE ------------------------
           $Allprofesseurs = User::where("role",'=',"professeur")->get();
           $professeurs = UserSessionResource::collection($Allprofesseurs)->toArray($request);
           $recupCours = Cours::where("id",'=',$request->id_cours)->get();
           $coursProfs = ProfesseurCoursResource::collection($recupCours)->first();
           $professeurCours = $coursProfs->toArray($request)['professeur'];

           foreach ($professeurs as $professeur) {
            if ($professeur['id'] === $professeurCours['id']) {
                foreach ($professeur['sessions'] as $session) {
                    if ($session['date'] && $session['heure_debut'] == $heure_debut_convertie && $session['heure_fin'] == $heure_fin_convertie) {
                        return response()->json(['error' => 'Ce professeur a déjà un cours programmé à cette date et aux mêmes heures'], 400);
                    }

                    // // Vérifier l'intervalle entre l'heure de début et l'heure de fin
                    // if ($session['date'] && $heure_debut_convertie > $session['heure_debut'] && $heure_fin_convertie < $session['heure_fin']) {
                    //     return response()->json(['error' => 'Cet horaire est déjà occupé par un cours pour ce professeur à cette date'], 400);
                    // }
                }
            }
        }


        // -------------------SAVOIR SI LE PROFESSEUR EST DISPNIBLE ------------------------

    // -------------------SAVOIR SI CETTE CLASSE EST DISPNIBLE ------------------------

    $all = Classe::with("classe_annees")->get();
    $collection = new ClasseCollection($all);
    $classes=  $collection->toArray($request);







    $cours = Cours::join("cours_classe_annees","cours.id","=","cours_classe_annees.cours_id")
            ->join("classe_annees","classe_annees.id","=","cours_classe_annees.classe_annee_id")
            ->select("classes.id","classes.nom_classe")
            ->join("classes","classes.id","=","classe_annees.classe_id")->where("cours.id", $request->id_cours)->get();


            foreach ($cours as $cour) {
                foreach ($classes as $classe) {
                    if (isset($classe['id']) && $classe['id'] == $cour->id) {
                        foreach($classe['session_cours'] as $sessionClasse){
                            foreach($sessionClasse['sessions'] as $ses){
                                if($ses['date']== $request->date && $ses['heure_debut']== $heure_debut_convertie){
                                    return response()->json(['error' => 'une des classe a deja un cours planifier a cette heure'], 400);
                                }
                            }
                        };
                    }
                }
            }

            // -------------------SAVOIR SI CETTE CLASSE EST DISPNIBLE ------------------------




            $validator = Validator::make($request->all(), [
                'date' => 'required|date',
                'heure_debut' => 'required|date_format:H:i',
                'heure_fin' => 'required|date_format:H:i|after:heure_debut',
                'type' => ['required', Rule::in(['en ligne', 'presentiel'])],
                'salle_id' => 'required|exists:salles,id',
                'annulation' => 'boolean'
            ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $heureDebut = Carbon::createFromFormat('H:i', $request->heure_debut);
    $heureFin = Carbon::createFromFormat('H:i', $request->heure_fin);

    if ($heureDebut->greaterThanOrEqualTo($heureFin)) {
        return response()->json(['error' => 'L\'heure de début doit être inférieure à l\'heure de fin.'], 400);
    }
        $id_cours = $request->id_cours;
        $cours_classe = CoursClasseAnnee::all();
        $tabIdcours_classe = [];

        foreach ($cours_classe as $id_cour) {
            if ($id_cour->cours_id === $id_cours) {
                array_push($tabIdcours_classe, $id_cour->id);
            }
        }

        $sessionExistsInInterval = Session::where('date', $request->date)
        ->where(function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('heure_debut', '>=', $request->heure_debut)
                    ->where('heure_debut', '<', $request->heure_fin);
            })->orWhere(function ($query) use ($request) {
                $query->where('heure_fin', '>', $request->heure_debut)
                    ->where('heure_fin', '<=', $request->heure_fin);
            });
        })
        ->where('salle_id', $request->salle_id)
        ->exists();



        if ($sessionExistsInInterval) {
            return response()->json(['error' => 'La salle est déjà occupée pour cet intervalle de temps.'], 400);
        }


        $session = Session::create([
            "date" => $request->date,
            "heure_debut" => $request->heure_debut,
            "heure_fin" => $request->heure_fin,
            "type" => $request->type,
            "salle_id" => $request->salle_id,
            "annulation" => $request->annulation
        ]);

        $session->cours_classe_annee()->attach($tabIdcours_classe);



        // Autres opérations...

        return response()->json($session);
    }


    public function demanderAnnulationSession($idSession)
{

    $session = Session::findOrFail($idSession);

    // Modifie l'état d'annulation de la session
    $session->annulation = "en cours";
    $session->save();

    return response()->json(['message' => "demande d'annulation envoyer avec succées"]);
}

public function AccepterAnnulationSession($idSession)
{

    $session = Session::findOrFail($idSession);

    // Modifie l'état d'annulation de la session
    $session->annulation = "accepter";
    $session->save();

    return response()->json(['message' => "demande Accepter"]);
}

public function RefuserAnnulationSession($idSession)
{

    $session = Session::findOrFail($idSession);

    // Modifie l'état d'annulation de la session
    $session->annulation = "refuser";
    $session->save();

    return response()->json(['message' => "demande Refuser"]);
}


public function ValidationSession($idSession){
    $session = Session::findOrFail($idSession);

    $session->validation = true;
    $session->save();

    return response()->json(['message' => "Session Terminer"]);
}

public function sessionAnnulationEncours(Request $request){
    $sessionAnnulationEncours = Session::where("annulation","en cours")->get();
    return sessionEnCoursRessource::collection($sessionAnnulationEncours)->toArray($request);
}

}
