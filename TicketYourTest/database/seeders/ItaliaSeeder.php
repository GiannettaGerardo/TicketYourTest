<?php

namespace Database\Seeders;

use App\Utility\Italia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItaliaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table_name = 'italia';
        $italia = new Italia();
        $italia->createItaly();
        $data = $italia->createDataForDBTableInsert();
        DB::table($table_name)->insert($data);
    }
}
