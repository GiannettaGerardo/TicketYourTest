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
     * @return \Illuminate\Database\Query\Builder
     */
    static function getProvinceRegioni()
    {
        return DB::table('Italia');
    }
}
