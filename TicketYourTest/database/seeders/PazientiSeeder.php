<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PazientiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table_name = 'pazienti';
        $data = [
            [
                'id_prenotazione' => 1,
                'codice_fiscale' => 'CTGFNC00B10E716C',
                'nome' => null,
                'cognome' => null,
                'email' => null,
                'citta_residenza' => null,
                'provincia_residenza' => null,
                'questionario_anamnesi' => null,
                'esito_tampone' => null
            ],

            [
                'id_prenotazione' => 2,
                'codice_fiscale' => 'BSCBCM54E50G372U',
                'nome' => null,
                'cognome' => null,
                'email' => null,
                'citta_residenza' => null,
                'provincia_residenza' => null,
                'questionario_anamnesi' => null,
                'esito_tampone' => null
            ],

            [
                'id_prenotazione' => 3,
                'codice_fiscale' => 'MSSVLR07R67I930S',
                'nome' => 'Valeria',
                'cognome' => 'Massari',
                'email' => 'nerobiancorosso@hotmail.it',
                'citta_residenza' => 'Caserta',
                'provincia_residenza' => 'Caserta',
                'questionario_anamnesi' => null,
                'esito_tampone' => null
            ]
        ];

        DB::table($table_name)->insert($data);
    }
}
