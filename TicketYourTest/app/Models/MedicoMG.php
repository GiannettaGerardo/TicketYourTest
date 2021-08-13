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
     * Inserisce un medico di medicina generale del database
     * @param $codice_fiscale // codice fiscale
     * @param $partita_iva    // partita iva
     */
    static function insertNewMedico($codice_fiscale, $partita_iva) {
        DB::table('medico_medicina_generale')->insert([
            'codice_fiscale' => $codice_fiscale,
            'partita_iva' => $partita_iva
        ]);
    }
}
