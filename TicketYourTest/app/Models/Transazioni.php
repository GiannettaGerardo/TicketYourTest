<?php

namespace App\Models;

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
     * @param int $id_prenotazione L'id della prenotazione
     * @param int $id_laboratorio L'id del laboratorio
     * @param double $importo L'importo della transazione
     * @param boolean $pagamento_online Se e' stato scelto il pagamento online
     * @param boolean $pagamento_effettuato Se e' stato effettuato il pagamento
     * @return void
     */
    static function upsertTransazione($id_prenotazione, $id_laboratorio, $importo, $pagamento_online=false, $pagamento_effettuato=false) {
        DB::table('transazioni')->upsert([
            'importo' => $importo,
            'id_prenotazione' => $id_prenotazione,
            'id_laboratorio' => $id_laboratorio,
            'pagamento_online' => $pagamento_online,
            'pagamento_effettuato' => $pagamento_effettuato
        ], ['id_prenotazione', 'id_laboratorio']);
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
     * Imposta un nuovo valore tra true o false all'attributo pagamento_effettuato
     * @param $id_transazione
     * @param $new_pagamento_effettuato // true se è stato effettuato, false se non è stato effettuato
     * @return int
     */
    static function setPagamentoEffettuato($id_transazione, $new_pagamento_effettuato) {
        return DB::table('transazioni')
            ->where('id', $id_transazione)
            ->update(['pagamento_effettuato' => $new_pagamento_effettuato]);

    }


    /**
     * Metodo generale per ottenere le persone che devono
     * pagare o che hanno pagato in contanti il tampone
     * @param $id_lab // id laboratorio
     * @return \Illuminate\Database\Query\Builder
     */
    private static function getUtentiConPagamentoContantiByLabGenerale($id_lab)
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
            ->where('transazioni.pagamento_online', '=', 0)
            ->selectRaw(
                'pazienti.codice_fiscale as codice_fiscale_paziente, '.
                'date(prenotazioni.data_tampone) as data_tampone, '.
                'prenotazioni.email as email_prenotante, '.
                'tamponi.nome as nome_tampone, '.
                'tamponi_proposti.costo as costo_tampone, '.
                'transazioni.id as id_transazione'
            );
    }


    /**
     * Metodo specifico per ottenere le persone che devono
     * pagare o che hanno pagato in contanti il tampone.
     * Fa differenza in base al pagamento effettuato o meno
     * @param $id_lab // id laboratorio
     * @param $pagamento_eseguito // true se il paamento è stato eseguito, false altrimenti
     * @return \Illuminate\Support\Collection
     */
    static function getUtentiConPagamentoContantiByLab($id_lab, $pagamento_eseguito)
    {
        return self::getUtentiConPagamentoContantiByLabGenerale($id_lab)
            ->where('transazioni.pagamento_effettuato', '=', $pagamento_eseguito)
            ->get();
    }
}
