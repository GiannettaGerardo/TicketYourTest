<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class MedicoMG
 * Model della tabella del database medico_medicina_generale
 * @package App\Models
 */
class MedicoMG extends Model
{
    use HasFactory;

    /**
     * Ritorna il datore di lavoro che ha la stessa mail passata in input
     * @param $email // email
     * @return Model|\Illuminate\Database\Query\Builder|object|null // medico o nulla
     */
    static function getByEmail($email) {
        return DB::table('medico_medicina_generale')
            ->join('users', 'medico_medicina_generale.codice_fiscale', '=', 'users.codice_fiscale')
            ->where('users.email', $email)->first();
    }

    /**
     * Ritorna tutti i dati di un medico di medicina generale, in base al suo id
     * @param $id  // id univoco del  medico di medicina generale
     * @return Model|\Illuminate\Database\Query\Builder|object|null //  medico di medicina generale o nulla
     */
    static function getById($id) {
        return DB::table('users')
            ->join('medico_medicina_generale', 'medico_medicina_generale.codice_fiscale', '=', 'users.codice_fiscale')
            ->where('users.id', $id)->first();
    }

    /**
     * Inserisce un medico di medicina generale del database
     * @param $codice_fiscale // codice fiscale
     * @param $partita_iva    // partita iva
     * @return bool
     */
    static function insertNewMedico($codice_fiscale, $partita_iva) {
        return DB::table('medico_medicina_generale')->insert([
            'codice_fiscale' => $codice_fiscale,
            'partita_iva' => $partita_iva
        ]);
    }

    /**
     * Modifica i dati di un medico di medicina generale nel database
     * @param $codice_fiscale // codice fiscale
     * @param $partita_iva    // partita iva
     * @return int
     */
    static function updateMedico($codice_fiscale, $partita_iva) {
        return DB::table('medico_medicina_generale')->where('codice_fiscale', $codice_fiscale)
            ->update([
                'codice_fiscale' => $codice_fiscale,
                'partita_iva' => $partita_iva
            ]);
    }
}
