<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApiDatoreLavoro extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tableName = 'api_datori_lavoro_italiani';
        $data = [
            ['partita_iva' => '00358400943'], ['partita_iva' => '12474851008'],
            ['partita_iva' => '00936180942'], ['partita_iva' => '01320751009'],
            ['partita_iva' => '00922160940'], ['partita_iva' => '13055221009'],
            ['partita_iva' => '00209800945'], ['partita_iva' => '14151531002'],
            ['partita_iva' => '11853171004'], ['partita_iva' => '11059561008'],
            ['partita_iva' => '00058250945'], ['partita_iva' => '23358466643'],
            ['partita_iva' => '00936760941'], ['partita_iva' => '20358477791'],
            ['partita_iva' => '00815110945'], ['partita_iva' => '30358222943'],
            ['partita_iva' => '00891470940'], ['partita_iva' => '41111400943'],
            ['partita_iva' => '00408920940'], ['partita_iva' => '50098443442'],
            ['partita_iva' => '00934530940'], ['partita_iva' => '69909863892'],
            ['partita_iva' => '00893150946'], ['partita_iva' => '11123435627'],
            ['partita_iva' => '00895180941'], ['partita_iva' => '10101010124'],
            ['partita_iva' => '12238081009'], ['partita_iva' => '09000987621'],
            ['partita_iva' => '02717570598'], ['partita_iva' => '11911233212']
        ];
        DB::table($tableName)->insert($data);
    }
}
