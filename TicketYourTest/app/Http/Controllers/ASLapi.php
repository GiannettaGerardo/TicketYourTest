<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ASLapi extends Controller
{
    public function getPositiviPerTempoESpazio()
    {
        $json = file_get_contents(storage_path() . '/../app/Utility/province-regioni.json');
        $province_regioni = json_decode($json, true);
    }
}
