<?php

namespace Database\Seeders;

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
                'catignano' => null,
                'email' => null,
                'citta_residenza' => null,
                'provincia_residenza' => null,
                'questionario_anamnesi' => null,
                'esito_tampone' => null
            ]
        ];

        DB::table($table_name)->insert($data);
    }
}
