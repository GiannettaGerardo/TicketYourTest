<?php

namespace App\Models\PagamentiModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Transazioni
 * Model della tabella del database
 */
class Transazioni extends Model
{
    use HasFactory;


    /**
     * Inserisce (o modifica) una transazione per il pagamento di un tampone.
     * @param int $id_prenotazione // L'id della prenotazione
     * @param int $id_laboratorio // L'id del laboratorio
     * @param double $importo // L'importo della transazione
     * @param boolean $pagamento_online // Se e' stato scelto il pagamento online
     * @param boolean $pagamento_effettuato // Se e' stato effettuato il pagamento
     * @return void
     */
    static function insertNewTransazione($id_prenotazione, $id_laboratorio, $importo, $pagamento_online=0, $pagamento_effettuato=0) {
        DB::table('transazioni')->insert([
            'importo' => $importo,
            'id_prenotazione' => $id_prenotazione,
            'id_laboratorio' => $id_laboratorio,
            'pagamento_online' => $pagamento_online,
            'pagamento_effettuato' => $pagamento_effettuato
        ]);
    }

    /**
     * Ritorna l'id della transazione
     * @param $id // id transazione
     * @return Model|\Illuminate\Database\Query\Builder|mixed|object|null
     */
    static function getTransazioneById($id) {
        return DB::table('transazioni')
            ->find($id);
    }


    /**
     * Restituisce una transazione a partire dall'id della prenotazione a cui e' associata.
     * @param int $id_prenotazione // L'id della prenotazione
     * @return Model|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection|object
     */
    static function getTransazioneByIdPrenotazione($id_prenotazione) {
        return DB::table('transazioni')
            ->where('id_prenotazione', '=', $id_prenotazione)->first();
    }


    /**
     * Imposta un nuovo valore tra true o false all'attributo pagamento_effettuato
     * @param $id_transazione
     * @param $new_pagamento_effettuato // true se è stato effettuato, false se non è stato effettuato
     * @param int $pagamento_online
     * @return int
     */
    static function setPagamentoEffettuato($id_transazione, $new_pagamento_effettuato, $pagamento_online=0) {
        return DB::table('transazioni')
            ->where('id', $id_transazione)
            ->update([
                'pagamento_effettuato' => $new_pagamento_effettuato,
                'pagamento_online' => $pagamento_online
            ]);
    }


    /**
     * Metodo generale per ottenere le persone che devono
     * pagare o che hanno pagato il tampone
     * @param $id_lab // id laboratorio
     * @return \Illuminate\Database\Query\Builder
     */
    private static function getUtentiConPagamentoByLabGenerale($id_lab)
    {
        return DB::table('transazioni')
            ->join('prenotazioni', 'prenotazioni.id', '=', 'transazioni.id_prenotazione')
            ->join('pazienti', 'pazienti.id_prenotazione', '=', 'prenotazioni.id')
            ->join('tamponi', 'prenotazioni.id_tampone', '=', 'tamponi.id')
            ->join('laboratorio_analisi', 'laboratorio_analisi.id', '=', 'prenotazioni.id_laboratorio')
            ->join('tamponi_proposti', function ($join) {
                $join->on('tamponi_proposti.id_laboratorio', '=', 'laboratorio_analisi.id')
                    ->on('tamponi_proposti.id_tampone', '=', 'tamponi.id');
            })
            ->where('laboratorio_analisi.id', $id_lab)
            ->selectRaw(
                'pazienti.codice_fiscale as codice_fiscale_paziente, '.
                'date(prenotazioni.data_tampone) as data_tampone, '.
                'prenotazioni.email as email_prenotante, '.
                'pazienti.email as email_paziente, '.
                'pazienti.codice_fiscale as cf_paziente, '.
                'tamponi.nome as nome_tampone, '.
                'tamponi_proposti.costo as costo_tampone, '.
                'transazioni.id as id_transazione'
            );
    }


    /**
     * Metodo specifico per ottenere le persone che devono ancora pagare il tampone
     * e lo devono fare in contanti.
     * @param $id_lab // id laboratorio
     * @return \Illuminate\Support\Collection
     */
    static function getUtentiConPagamentoContantiDaEffettuareByLab($id_lab)
    {
        return self::getUtentiConPagamentoByLabGenerale($id_lab)
            ->where('transazioni.pagamento_online', '=', 0)
            ->where('transazioni.pagamento_effettuato', '=', 0)
            ->get();
    }


    /**
     * Metodo specifico per ottenere le persone che hanno pagato il tampone
     * in base all'id del laboratorio.
     * @param $id_lab // id laboratorio
     * @return \Illuminate\Support\Collection
     */
    static function getUtentiCheHannoPagatoByLab($id_lab)
    {
        return self::getUtentiConPagamentoByLabGenerale($id_lab)
            ->where('transazioni.pagamento_effettuato', '=', 1)
            ->get();
    }


    /**
     * Metodo specifico per ottenere un paziente specifico a partire
     * da una transazione e inviare poi una email di ricevuta pagamento
     *
     * VINCOLO: Il metodo funziona solo nell'ipotesi che l'id transazione sia univocamente
     * associato ad un solo paziente di una sola prenotazione
     *
     * VINCOLO ATTUALMENTE RISPETTATO: True
     *
     * @param $id_lab // id laboratorio
     * @param $id_transazione // id della transazione
     * @return Model|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection|object
     */
    static function getPazienteByTransazionePerRicevutaPagamento($id_lab, $id_transazione) {
        return self::getUtentiConPagamentoByLabGenerale($id_lab)
            ->where('transazioni.id', '=', $id_transazione)
            ->first();
    }
}
