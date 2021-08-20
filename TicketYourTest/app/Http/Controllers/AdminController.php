<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use Illuminate\Http\Request;

/**
 * Class LoginController
 * Classe per gestire tutta la logica legata all'admin
 */
class AdminController extends Controller
{
    public function convenzionaLaboratorioById(Request $request) {
        // Informazioni del laboratorio
        $laboratorio = $request->all();

        // Controllo sull'inserimento delle coordinate
        if(!isset($laboratorio['coordinata_x']) and !isset($laboratorio['coordinata_y'])) {
            return back()->with('coordinate-non-inserite', 'Non sono state inserite le coordinate per ' . $laboratorio['nome'] . '!');
        }

        // Inserimento delle coordinate
        Laboratorio::setCoordinateById($laboratorio['id'], $laboratorio['coordinata_x'], $laboratorio['coordinata_y']);
        // Convenzionamento
        Laboratorio::convenzionaById($laboratorio['id']);
    }
}
