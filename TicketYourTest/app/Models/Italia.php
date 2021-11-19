<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Italia extends Model
{
    use HasFactory;

    /**
     * Ritorna tutta la tabella
     * @return \Illuminate\Support\Collection
     */
    static function getProvinceRegioni()
    {
        return DB::table('italia')->get();
    }
}
