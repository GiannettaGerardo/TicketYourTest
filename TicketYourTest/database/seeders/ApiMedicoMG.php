<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApiMedicoMG extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tableName = 'api_medici_italiani';
        $data = [
            ['partita_iva' => '99937489293'], ['partita_iva' => '07780910019'],
            ['partita_iva' => '00010002900'], ['partita_iva' => '12237260158'],
            ['partita_iva' => '12123131123'], ['partita_iva' => '14437331003'],
            ['partita_iva' => '40404244444'], ['partita_iva' => '14437331999'],
            ['partita_iva' => '42380980342'], ['partita_iva' => '00749900049'],
            ['partita_iva' => '31235467899'], ['partita_iva' => '00936260736'],
            ['partita_iva' => '77772828277'], ['partita_iva' => '00936260561'],
            ['partita_iva' => '10980980980'], ['partita_iva' => '30350008712'],
            ['partita_iva' => '00662726326'], ['partita_iva' => '41122223333'],
            ['partita_iva' => '00123400000'], ['partita_iva' => '88888856666'],
            ['partita_iva' => '00123400001'], ['partita_iva' => '55555543210'],
            ['partita_iva' => '00123400012'], ['partita_iva' => '11122222099'],
            ['partita_iva' => '00123400021'], ['partita_iva' => '10121212121'],
            ['partita_iva' => '00123400002'], ['partita_iva' => '09099900000'],
            ['partita_iva' => '02090909120'], ['partita_iva' => '77777777771']
        ];
        DB::table($tableName)->insert($data);
    }
}
