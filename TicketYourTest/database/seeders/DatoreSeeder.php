<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table_name = 'datore_lavoro';
        $data = [
            [
                'codice_fiscale' => 'RSSMRO65M05A404R',
                'partita_iva' => '00358400943',
                'nome_azienda' => 'Microsoft Corp. - Italy',
                'citta_sede_aziendale' => 'Milano',
                'provincia_sede_aziendale' => 'Milano'
            ]
        ];

        DB::table($table_name)->insert($data);
    }
}
