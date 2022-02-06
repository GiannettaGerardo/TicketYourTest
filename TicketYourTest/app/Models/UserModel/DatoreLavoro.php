<?php

namespace App\Models\UserModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class DatoreLavoro
 * Model della tabella del database datore_lavoro
 * @package App\Models
 */
class DatoreLavoro extends Model
{
    use HasFactory;

    /**
     * Ritorna il datore di lavoro che ha la stessa mail passata in input
     * @param $email // email
     * @return Model|\Illuminate\Database\Query\Builder|object|null // datore di lavoro o nulla
     */
    static function getByEmail($email) {
        return DB::table('datore_lavoro')
            ->join('users', 'datore_lavoro.codice_fiscale', '=', 'users.codice_fiscale')
            ->where('users.email', $email)->first();
    }

    /**
     * Ritorna tutti i dati di un datore di lavoro, in base al suo id
     * @param $id  // id univoco del datore di lavoro
     * @return Model|\Illuminate\Database\Query\Builder|object|null // datore di lavoro o nulla
     */
    static function getById($id) {
        return DB::table('users')
            ->join('datore_lavoro', 'datore_lavoro.codice_fiscale', '=', 'users.codice_fiscale')
            ->where('users.id', $id)->first();
    }

    /**
     * Cerca e restituisce le informazioni di un'azienda a partire dal nome.
     * @param $nome Il nome dell'azienda da cercare
     * @return Model|\Illuminate\Database\Query\Builder|object|null L'azienda o nulla
     */
    static function getAziendaByNome($nome) {
        return DB::table('datore_lavoro')
            ->select(['partita_iva', 'nome_azienda', 'citta_sede_aziendale', 'provincia_sede_aziendale'])
            ->where('nome_azienda', '=', $nome)
            ->first();
    }

    /**
     * Inserisce un datore di lavoro del database
     * @param $codice_fiscale     // codice fiscale
     * @param $partita_iva        // partita iva del datore
     * @param $nome_azienda       // nome dell'azienda del datore
     * @param $citta_azienda      // città sede aziendale
     * @param $provincia_azienda  // provincia sede aziendale
     * @return bool
     */
    static function insertNewDatore($codice_fiscale, $partita_iva, $nome_azienda, $citta_azienda, $provincia_azienda) {
        return DB::table('datore_lavoro')->insert([
            'codice_fiscale' => $codice_fiscale,
            'partita_iva' => $partita_iva,
            'nome_azienda' => $nome_azienda,
            'citta_sede_aziendale' => $citta_azienda,
            'provincia_sede_aziendale' => $provincia_azienda
        ]);
    }

    /**
     * Modifica i dati di un datore di lavoro nel database
     * @param $codice_fiscale     // codice fiscale
     * @param $partita_iva        // partita iva del datore
     * @param $nome_azienda       // nome dell'azienda del datore
     * @param $citta_azienda      // città sede aziendale
     * @param $provincia_azienda  // provincia sede aziendale
     * @return int
     */
    static function updateDatore($codice_fiscale_attuale, $nuovo_codice_fiscale, $partita_iva, $nome_azienda, $citta_azienda, $provincia_azienda) {
        return DB::table('datore_lavoro')->where('codice_fiscale', $codice_fiscale_attuale)
            ->update([
                'codice_fiscale' => $nuovo_codice_fiscale,
                'partita_iva' => $partita_iva,
                'nome_azienda' => $nome_azienda,
                'citta_sede_aziendale' => $citta_azienda,
                'provincia_sede_aziendale' => $provincia_azienda
            ]);
    }
}
