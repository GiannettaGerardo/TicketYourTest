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
     * Crea o modifica un referto di un tampone.
     * @param int $id_prenotazione L'id della prenotazione
     * @param string $cf_paziente Il codice fiscale del paziente
     * @param null $esito_tampone L'esito del tampone
     * @param null $quantita La carica virale
     * @param null $data_referto La data del referto
     * @return int
     */
    static function upsertReferto($id_prenotazione, $cf_paziente, $esito_tampone=null, $quantita=null, $data_referto=null) {
        return DB::table('referti')
            ->upsert([
                'id_prenotazione' => $id_prenotazione,
                'cf_paziente' => $cf_paziente,
                'esito_tampone' => $esito_tampone,
                'quantita' => $quantita,
                'data_referto' => $data_referto
            ], ['id_prenotazione', 'cf_paziente']);
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
}
