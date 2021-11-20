<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CarteCreditoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(dirname(__DIR__, 2) . '/app/Utility/carte_di_credito.json');
        $carte = json_decode($json);
        $data = [];

        foreach($carte as $carta) {
            array_push($data, [
                'numero' => $carta->{'cardNumber'},
                'exp' => Carbon::createFromFormat('m/y', $carta->{'exp'})->format('Y-m-d'),
                'cvv' => $carta->{'cvv'},
                'cf_possessore' => $carta->{'codice_fiscale'}
            ]);
        }

        DB::table('carte_credito')->insert($data);

    }
}
