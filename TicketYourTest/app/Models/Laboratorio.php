<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class Laboratorio
 * Model della tabella del database
 */
class Laboratorio extends Model
{
    use HasFactory;

    protected $table = 'laboratorio_analisi';   // Nome della tabella a cui questo model fa riferimento

    /**
     * Viene effettuata una query al database per capire se il laboratorio e' stato trovato o meno.
     *
     * @param $email L'email del laboratorio da cercare.
     * @return Model|\Illuminate\Database\Query\Builder|object|null Il laboratorio o nulla.
     */
    static function getByEmail($email) {
        return DB::table('laboratorio_analisi')->where('email', $email)->first();
    }

    /**
     * Controlla se un laboratorio d'analisi e' stato convenzionato oppure no.
     * @param $email L'email del laboratorio di cui si vuole sapere il convenzionamento.
     * @return mixed|null L'eventuale convenzionamento o nulla.
     */
    static function isConvenzionatoByEmail($email) {
        return DB::table('laboratorio_analisi')->where('email', $email)->value('convenzionato');
    }

    /**
     * Inserisce un nuovo laboratorio nel database
     * @param $partita_iva
     * @param $nome
     * @param $citta
     * @param $provincia
     * @param $indirizzo
     * @param $email
     * @param $password
     * @param $coordinata_x
     * @param $coordinata_y
     * @return bool
     */
    static function insertNewLaboratorio($partita_iva, $nome, $citta, $provincia, $indirizzo, $email, $password, $coordinata_x = null, $coordinata_y = null) {
        return DB::table('laboratorio_analisi')->insert([
            'partita_iva' => $partita_iva,
            'nome' => $nome,
            'citta' => $citta,
            'provincia' => $provincia,
            'indirizzo' => $indirizzo,
            'email' => $email,
            'password' => Hash::make($password),
            'coordinata_x' => $coordinata_x,
            'coordinata_y' => $coordinata_y
        ]);
    }

    /**
     * Effettua il convenzionamento di un laboratorio tramite la sua mail. Viene aggiornato il valore dell'attributo 'convenzionato'.
     * @param $email
     * @return int
     */
    static function convenzionaByEmail($email) {
        return DB::table('laboratorio_analisi')->where('email', $email)->update(['convenzionato' => '1']);
    }
}
