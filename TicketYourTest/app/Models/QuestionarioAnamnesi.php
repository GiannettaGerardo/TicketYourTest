<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class QuestionarioAnamnesi
 * Model della tabella del database questionario_anamnesi
 */
class QuestionarioAnamnesi extends Model
{
    use HasFactory;


    /**
     * Inserisce o modifica un questionario anamnesi, con tutte le risposte alle domande.
     * @param int $id_prenotazione L'id della prenotazione
     * @param str $cf_paziente Codice fiscale del paziente
     * @param str $token Token univoco per generare un link associato al questionario anamnesi
     * @param bool $token_scaduto Informazione sul token scaduto
     * @param str $motivazione La motivazione del tampone
     * @param bool $lavoro
     * @param bool $contatto
     * @param bool $quindici_giorni_dopo_contatto
     * @param bool $tampone_fatto
     * @param bool $isolamento
     * @param bool $contagiato
     * @param bool $febbre
     * @param bool $tosse
     * @param bool $difficolta_respiratorie
     * @param bool $raffreddore
     * @param bool $mal_di_gola
     * @param bool $mancanza_gusto
     * @param bool $dolori_muscolari
     * @param bool $cefalea
     * @return mixed
     */
    static function upsertNewQuestionarioAnamnesi(
        $id_prenotazione,
        $cf_paziente,
        $token,
        $token_scaduto,
        $motivazione,
        $lavoro,
        $contatto,
        $quindici_giorni_dopo_contatto,
        $tampone_fatto,
        $isolamento,
        $contagiato,
        $febbre,
        $tosse,
        $difficolta_respiratorie,
        $raffreddore,
        $mal_di_gola,
        $mancanza_gusto,
        $dolori_muscolari,
        $cefalea
    ) {
        return DB::table('questionario_anamnesi')
            ->upsert([
                'id_prenotazione' => $id_prenotazione,
                'cf_paziente' => $cf_paziente,
                'token' => $token,
                'token_scaduto' => $token_scaduto,
                'motivazione' => $motivazione,
                'lavoro' => $lavoro,
                'contatto' => $contatto,
                'quindici-giorni-dopo-contatto' => $quindici_giorni_dopo_contatto,
                'tampone-fatto' => $tampone_fatto,
                'isolamento' => $isolamento,
                'contagiato' => $contagiato,
                'febbre' => $febbre,
                'tosse' => $tosse,
                'difficolta-respiratorie' => $difficolta_respiratorie,
                'raffreddore' => $raffreddore,
                'mal-di-gola' => $mal_di_gola,
                'mancanza-gusto' => $mancanza_gusto,
                'dolori-muscolari' => $dolori_muscolari,
                'cefalea' => $cefalea
            ], ['id_prenotazione', 'cf_paziente', 'token']);
    }


    /**
     * Restituisce il questionario anamnesi a partire dal suo token.
     * @param $token
     * @return mixed
     */
    static function getQuestionarioAnamnesiByToken($token) {
        return DB::table('questionario_anamnesi')
            ->where('token', '=', $token)
            ->first();
    }


    /**
     * Controlla l'esistenza di un questionario anamnesi tramite il suo token
     * @param $token
     * @return bool
     */
    static function exsistsQuestionarioAnamnesiByToken($token) {
        $questionario = DB::table('questionario_anamnesi')
            ->where('token', '=', $token)
            ->first();

        return !$questionario===null;
    }


    /**
     * // Ritorna il questionario anamnesi di un paziente per una specifica prenotazione
     * @param $id_prenotazione // identificativo univoco della prenotazione
     * @param $cf_paziente // codice fiscale del paziente
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     */
    static function getQuestionarioByIdCf($id_prenotazione, $cf_paziente)
    {
        return DB::table('questionario_anamnesi')
            ->where('id_prenotazione', $id_prenotazione)
            ->where('cf_paziente', $cf_paziente)
            ->first();
    }
}
