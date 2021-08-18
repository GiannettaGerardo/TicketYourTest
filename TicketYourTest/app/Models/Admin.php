<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Admin extends Model
{
    use HasFactory;

    /**
     * @param $email
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     */
    static function getByEmail($email) {
        return DB::table('amministratore')
            ->where('amministratore.email', $email)->first();
    }
}
