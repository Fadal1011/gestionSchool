<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use Illuminate\Http\Request;

class salleCrontroller extends Controller
{
    public function allSalleDisponible(){
        $salle = Salle::all();
        return $salle;
    }
}
