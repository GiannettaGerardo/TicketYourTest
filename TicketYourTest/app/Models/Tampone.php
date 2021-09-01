<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tampone extends Model
{
    use HasFactory;

    protected $table = 'tamponi';

    /**
     * Restituisce il tampone a partire dall'id. In particolare restituisce:
     * - l'id del tampone in questione;
     * - il nome;
     * - la descrizione.
     * @param $id    // L'id del tampone che si vuole cercare.
     * @return mixed // Il tampone o nulla.
     */
    static function getTamponeById($id) {
        return DB::table('tamponi')->where('id', $id)->first();
    }

    /**
     * Restituisce il tampone a partire dal nome. In particolare restituisce:
     * - l'id del tampone in questione;
     * - il nome;
     * - la descrizione.
     * @param $nome  // Il nome del tampone che si vuole cercare.
     * @return mixed // Il tampone o nulla.
     */
    static function getTamponeByNome($nome) {
        return DB::table('tamponi')->where('nome', $nome)->first();
    }


    /**
     * Restituisce tutti i tamponi della tabella
     * @return \Illuminate\Support\Collection
     */
    static function getTamponi() {
        return DB::table('tamponi')->get();
    }
}
