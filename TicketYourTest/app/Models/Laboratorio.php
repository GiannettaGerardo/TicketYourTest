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
     * Imposta le coordinate del laboratorio a partire dal suo id.
     * @param $id L'id del laboratorio.
     * @param $coordinata_x La coordinata x.
     * @param $coordinata_y La coordinata y.
     * @return int Se l'update e' avvenuto con successo.
     */
    static function setCoordinateById($id, $coordinata_x, $coordinata_y) {
        return DB::table('laboratorio_analisi')
            ->where('id', $id)
            ->update([
                'coordinata_x' => $coordinata_x,
                'coordinata_y' => $coordinata_y
            ]);
    }


    /**
     * Effettua il convenzionamento di un laboratorio tramite il suo id. Viene aggiornato il valore dell'attributo 'convenzionato'.
     * @param $id L'id del laboratorio.
     * @return int Se l'update e' avvenuto con successo.
     */
    static function convenzionaById($id) {
        return DB::table('laboratorio_analisi')->where('id', $id)->update(['convenzionato' => '1']);
    }


    /**
     * Restituisce una collection contenente tutti i laboratori ancora non convenzionati.
     * In particolare restituisce:
     * - l'id
     * - la partita iva
     * - il nome
     * - la provincia
     * - la citta'
     * - l'indirizzo
     * - l'email
     * @return \Illuminate\Support\Collection I laboratori o nulla
     */
    static function getLaboratoriNonConvenzionati() {
        return DB::table('laboratorio_analisi')
            ->select(['id', 'partita_iva', 'nome', 'provincia', 'citta', 'indirizzo', 'email'])
            ->where('convenzionato', '=', 0)
            ->get();
    }


    /**
     * Ritorna tutti i laboratori dal database che sono convenzionati e
     * hanno compilato il loro calendario delle disponibilità
     * @return \Illuminate\Support\Collection
     */
    static function getLaboratoriAttivi() {
        return DB::table('laboratorio_analisi')
            ->where('convenzionato', 1)
            ->where('calendario_compilato', 1)
            ->get();
    }


    /**
     * Imposta il flag calendario compilato di un laboratorio
     * @param $id
     * @param $flag
     * @return int
     */
    static function setFlagCalendarioCompilato($id, $flag) {
        return DB::table('laboratorio_analisi')->where('id', $id)->update(['calendario_compilato' => $flag]);
    }
}
