<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListaDipendentiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = 'lista_dipendenti';
        $data = [
            [
                'partita_iva_datore' => '00358400943',
                'codice_fiscale' => 'CTGFNC00B10E716C',
                'nome' => null,
                'cognome' => null,
                'email' => null,
                'citta_residenza' => null,
                'provincia_residenza' => null,
                'accettato' => '1'
            ],

            [
                'partita_iva_datore' => '00358400943',
                'codice_fiscale' => 'BSCBCM54E50G372U',
                'nome' => null,
                'cognome' => null,
                'email' => null,
                'citta_residenza' => null,
                'provincia_residenza' => null,
                'accettato' => '1'
            ],

            [
                'partita_iva_datore' => '00358400943',
                'codice_fiscale' => 'PSNYNG59B52F721R',
                'nome' => null,
                'cognome' => null,
                'email' => null,
                'citta_residenza' => null,
                'provincia_residenza' => null,
                'accettato' => '0'
            ],

            [
                'partita_iva_datore' => '00358400943',
                'codice_fiscale' => 'KLAFLN46R60D918G',
                'nome' => null,
                'cognome' => null,
                'email' => null,
                'citta_residenza' => null,
                'provincia_residenza' => null,
                'accettato' => '0'
            ],

            [
                'partita_iva_datore' => '00358400943',
                'codice_fiscale' => 'TLNVSC89H19G139V',
                'nome' => 'Vasco',
                'cognome' => 'Taliano',
                'email' => 'vasco.taliano@email.com',
                'citta_residenza' => 'Maruggio',
                'provincia_residenza' => 'Taranto',
                'accettato' => '1'
            ],

            [
                'partita_iva_datore' => '00358400943',
                'codice_fiscale' => 'PLLMRO47C22I214Z',
                'nome' => 'Omar',
                'cognome' => 'Pellegrino',
                'email' => 'omar.pellegrino@email.com',
                'citta_residenza' => 'Roma',
                'provincia_residenza' => 'Roma',
                'accettato' => '1'
            ]
        ];

        DB::table($table)->insert($data);
    }
}
