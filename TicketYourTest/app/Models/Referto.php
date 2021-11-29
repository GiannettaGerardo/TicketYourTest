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
     * Restituisce gli ultimi referti dei pazienti del medico la cui email e' passata in input.
     * @param string $email_medico
     * @return \Illuminate\Support\Collection
     */
    static function getElencoRefertiByEmailMedico($email_medico) {
        $pazienti = Paziente::getQueryForAllPazienti();

        return DB::table('referti')
            ->fromSub($pazienti, 'pazienti')
            ->join('prenotazioni', 'prenotazioni.id', '=', 'pazienti.id_prenotazione')
            ->join('questionario_anamnesi', 'questionario_anamnesi.cf_paziente', '=', 'pazienti.cf_paziente')
            ->join('referti', function($join) {
                $join->on('referti.id_prenotazione', '=', 'prenotazioni.id')
                    ->on('referti.cf_paziente', '=', 'pazienti.cf_paziente');
            })
            ->where('questionario_anamnesi.email_medico', '=', $email_medico)
            ->whereNotNull('referti.data_referto')
            ->selectRaw(
                'pazienti.nome_paziente, pazienti.cognome_paziente, pazienti.cf_paziente, max(referti.data_referto) as data_referto'
            )
            ->groupBy( 'pazienti.cf_paziente', 'pazienti.nome_paziente', 'pazienti.cognome_paziente')
            ->get();
    }
}
