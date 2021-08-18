<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tableName = 'amministratore';
        $data = [
            [
                'nome' => 'admin-01',
                'email' => 'admin-01@gmail.com',
                'password' => Hash::make('admin-01')
            ]
        ];
        DB::table($tableName)->insert($data);
    }
}
