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
     * Ritorna tutti i dati di un cittadino, in base al suo id
     * @param $id   // id univoco del cittadino
     * @return Model|\Illuminate\Database\Query\Builder|object|null // cittadino privato o nulla
     */
    static function getById($id) {
        return DB::table('users')
            ->join('cittadino_privato', 'cittadino_privato.codice_fiscale', '=', 'users.codice_fiscale')
            ->where('users.id', $id)->first();
    }

    /**
     * Inserisce un cittadino del database
     * @param $codice_fiscale // codice fiscale
     * @return bool
     */
    static function insertNewCittadino($codice_fiscale) {
        return DB::table('cittadino_privato')->insert([
            'codice_fiscale' => $codice_fiscale
        ]);
    }

    /**
     * Modifica i dati di un cittadino privato nel database
     * @param $codice_fiscale_attuale // cod fiscale prima della modifica
     * @param $nuovo_codice_fiscale   // cod fiscale dopo la modifica
     * @return int
     */
    static function updateCittadino($codice_fiscale_attuale, $nuovo_codice_fiscale)
    {
        return DB::table('cittadino_privato')->where('codice_fiscale', $codice_fiscale_attuale)
            ->update(['codice_fiscale' => $nuovo_codice_fiscale]);
    }
}
