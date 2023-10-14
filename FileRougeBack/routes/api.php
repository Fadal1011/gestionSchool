<?php

use App\Http\Controllers\AnneeScolaireController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\coursController;
use App\Http\Controllers\eleveController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\NiveauController;
use App\Http\Controllers\ProfesseurController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\salleCrontroller;
use App\Http\Controllers\SemestreController;
use App\Http\Controllers\sessionCrontroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/cours',[coursController::class,"store"]);

Route::get('/module',[coursController::class,"all_module"]);

Route::post('/session',[sessionCrontroller::class,"store"]);

Route::get('/cours',[coursController::class,"AllCours"]);

Route::get('/salle',[salleCrontroller::class,"allSalleDisponible"]);

Route::get('/classeSession/{id}',[sessionCrontroller::class,"AllClassesModule"]);

Route::get('/anneeActive',[sessionCrontroller::class,"anneeActive"]);

Route::post('/insererEleve',[eleveController::class,"importerCsv"]);
Route::put('/changePassword/{email}',[eleveController::class,"changePassword"]);





Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);






Route::get('AnneeScolaire', [AnneeScolaireController::class, 'index']);
Route::post('AnneeScolaire', [AnneeScolaireController::class, 'store']);
Route::delete('AnneeScolaire/{id}', [AnneeScolaireController::class, 'delete']);
Route::put('AnneeScolaire/{id}/etat', [AnneeScolaireController::class, 'updateEtat']);




Route::get('/semestres', [SemestreController::class, 'index']);
Route::post('/semestres', [SemestreController::class, 'store']);
Route::put('/semestres/{id}/actif', [SemestreController::class, 'updateActif']);
Route::delete('/semestres/{id}', [SemestreController::class, 'destroy']);




Route::get('/modules', [ModuleController::class, 'index']);
Route::post('/modules', [ModuleController::class, 'store']);
Route::put('/modules/{id}', [ModuleController::class, 'update']);
Route::delete('/modules/{id}', [ModuleController::class, 'destroy']);



Route::get('/niveaux', [NiveauController::class, 'index']);
Route::post('/niveaux', [NiveauController::class, 'store']);
Route::put('/niveaux/{id}', [NiveauController::class, 'update']);
Route::delete('/niveaux/{id}', [NiveauController::class, 'destroy']);



Route::get('/filieres', [FiliereController::class, 'index']);
Route::post('/filieres', [FiliereController::class, 'store']);
Route::put('/filieres/{id}', [FiliereController::class, 'update']);
Route::delete('/filieres/{id}', [FiliereController::class, 'delete']);



Route::post('/classes',[ClasseController::class, 'store']);
Route::get('/classes',[ClasseController::class, 'index']);
Route::put('/classes/{id}',[ClasseController::class, 'update']);


Route::get('/salles',[SalleController::class,"index"]);
Route::post('/salles', [SalleController::class, 'store']);
Route::put('/salles/{id}', [SalleController::class, 'update']);
Route::delete('/salles/{id}', [SalleController::class, 'destroy']);


Route::get('/professeurs', [ProfesseurController::class, 'index']);
Route::post('/professeurs', [ProfesseurController::class, 'store']);
Route::put('/professeurs/{id}', [ProfesseurController::class, 'update']);
Route::delete('/professeurs/{id}', [ProfesseurController::class, 'destroy']);



Route::post('/occuper',[sessionCrontroller::class, 'checkClassOccupancy']);




Route::get('sessions/{idSession}/demande-annulation', [sessionCrontroller::class, 'demanderAnnulationSession']);
Route::get('sessions/{idSession}/accepter-annulation', [sessionCrontroller::class, 'accepterAnnulationSession']);
Route::get('sessions/{idSession}/refuser-annulation', [sessionCrontroller::class, 'refuserAnnulationSession']);
Route::get('sessions/list-encours', [sessionCrontroller::class, 'sessionAnnulationEncours']);


Route::get('sessions/{idSession}/validation', [sessionCrontroller::class, 'ValidationSession']);






