<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class CittadinoPrivato
 * Model della tabella del database cittadino_privato
 * @package App\Models
 */
class CittadinoPrivato extends Model
{
    use HasFactory;

    /**
     * Ritorna il cittadino privato che ha la stessa mail passata in input
     * @param $email // email
     * @return mixed // cittadino privato o nulla
     */
    static function getByEmail($email) {
        return DB::table('cittadino_privato')
            ->join('users', 'cittadino_privato.codice_fiscale', '=', 'users.codice_fiscale')
            ->where('users.email', $email)->first();
    }

    /**
     * Inserisce un cittadino del database
     * @param $codice_fiscale // codice fiscale
     */
    static function insertNewCittadino($codice_fiscale) {
        DB::table('cittadino_privato')->insert([
            'codice_fiscale' => $codice_fiscale
        ]);
    }
}
