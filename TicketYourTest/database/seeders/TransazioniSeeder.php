<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransazioniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'importo' => 10.00,
                'id_prenotazione' => 1,
                'id_laboratorio' => 1,
                'pagamento_online' => 0,
                'pagamento_effettuato' => 0
            ],

            [
                'importo' => 13.00,
                'id_prenotazione' => 2,
                'id_laboratorio' => '13',
                'pagamento_online' => 1,
                'pagamento_effettuato' => 1
            ],

            [
                'importo' => 11.00,
                'id_prenotazione' => 3,
                'id_laboratorio' => '11',
                'pagamento_online' => 1,
                'pagamento_effettuato' => 1
            ],

            [
                'importo' => 15.00,
                'id_prenotazione' => 4,
                'id_laboratorio' => 11,
                'pagamento_online' => 1,
                'pagamento_effettuato' => 1
            ],

            [
                'importo' => 15.00,
                'id_prenotazione' => 5,
                'id_laboratorio' => '11',
                'pagamento_online' => 0,
                'pagamento_effettuato' => 1
            ],

            [
                'importo' => 10.00,
                'id_prenotazione' => 6,
                'id_laboratorio' => 1,
                'pagamento_online' => 0,
                'pagamento_effettuato' => 1
            ],

            [
                'importo' => 15.00,
                'id_prenotazione' => 7,
                'id_laboratorio' => 11,
                'pagamento_online' => 1,
                'pagamento_effettuato' => 1
            ]
        ];

        DB::table('transazioni')->insert($data);
    }
}
