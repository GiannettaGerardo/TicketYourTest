<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Paziente;
use App\Models\MedicoMG;

/**
 * Class Referto
 * Model che rappresenta la tabella 'referti'
 */
class Referto extends Model
{
    use HasFactory;

    /**
     * Ritorna l'ID di un referto in base all'id della prenotazione
     * @param $id_prenotazione // id della prenotazione (unico all'interno della tabella)
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     */
    static function getIdRefertoByIdPrenotazione($id_prenotazione)
    {
        return DB::table('referti')
            ->where('id_prenotazione', $id_prenotazione)
            ->select('id')
            ->first();
    }


    /**
     * Crea o modifica un referto di un tampone.
     * @param int $id_prenotazione L'id della prenotazione
     * @param string $cf_paziente Il codice fiscale del paziente
     * @param null $esito_tampone L'esito del tampone
     * @param null $quantita La carica virale
     * @param null $data_referto La data del referto
     * @return int
     */
    static function insertNewReferto($id_prenotazione, $cf_paziente, $esito_tampone=null, $quantita=null, $data_referto=null) {
        return DB::table('referti')
            ->insert([
                'id_prenotazione' => $id_prenotazione,
                'cf_paziente' => $cf_paziente,
                'esito_tampone' => $esito_tampone,
                'quantita' => $quantita,
                'data_referto' => $data_referto
            ]);
    }

    /**
     * Modifica un referto a partire dall'id della prenotazione e dal codice fiscale del paziente.
     * @param int $id_prenotazione L'id della prenotazione
     * @param string $cf_paziente Il codice fiscale del paziente
     * @param string $esito L'esito del tampone
     * @param string $data_referto La data del referto
     * @param double $quantita La carica virale
     * @return int
     */
    static function updateRefertoByIdPrenotazioneCfPaziente($id_prenotazione, $cf_paziente, $esito, $data_referto, $quantita=null) {
        return DB::table('referti')
            ->where('id_prenotazione', '=', $id_prenotazione)
            ->where('cf_paziente', '=', $cf_paziente)
            ->update([
                'esito_tampone' => $esito,
                'quantita' => $quantita,
                'data_referto' => $data_referto
            ]);
    }


    /**
     * Viene restituito il numero di referti in un dato giorno. Questo perche' corrisponde al numero di tamponi
     * con un esito certificato.
     * @param string $giorno
     * @return int
     */
    static function getNumeroTamponiByGiorno(string $giorno) {
        return DB::table('referti')
            ->where('data_referto', '=', $giorno)
            ->whereNotNull('esito_tampone')
            ->count();
    }


    /**
     * Restituisce l'ultimo referto compilato di un paziente partendo dal suo codice fiscale.
     * @param string $cf_paziente Il codice fiscale del paziente
     * @return mixed
     */
    static function getUltimoRefertoPazienteByCodiceFiscale($cf_paziente) {
        return DB::table('referti')
            ->where('cf_paziente', '=', $cf_paziente)
            ->whereNotNull('esito_tampone')
            ->orderBy('data_referto', 'desc')
            ->first();
    }


    /**
     * Restituisce tutte le informazioni di un referto partendo dal suo id.
     * @param int $id L'id del referto
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     */
    static function getRefertoById($id) {
        $pazienti = Paziente::getQueryForAllPazienti();

        return DB::table('referti')
            ->fromSub($pazienti, 'pazienti')
            ->join('referti', 'referti.cf_paziente', '=', 'pazienti.cf_paziente')
            ->join('prenotazioni', 'prenotazioni.id', '=', 'pazienti.id_prenotazione')
            ->whereNotNull('esito_tampone')
            ->where('referti.id', '=', $id)
            ->select(
                'pazienti.cf_paziente',
                'pazienti.nome_paziente',
                'pazienti.cognome_paziente',
                'pazienti.email_paziente',
                'prenotazioni.data_tampone',
                'referti.data_referto',
                'referti.esito_tampone',
                'referti.quantita'
            )
            ->first();
    }
}
