<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

/**
 * Class User
 * Model della tabella del database users
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // QUERY
    /**
     * Inserisce un nuovo utente nella tabella users del database
     * @param $cod_fiscale    // codice fiscale
     * @param $nome           // nome dell'utente
     * @param $cognome        // cognome dell'utente
     * @param $citta_res      // città di residenza
     * @param $provincia_res  // provincia di residenza
     * @param $email          // email dell'account
     * @param $password       // password dell'account
     */
    static function insertNewUtenteRegistrato($cod_fiscale, $nome, $cognome, $citta_res, $provincia_res, $email, $password) {
        DB::table('users')->insert([
            'codice_fiscale' => $cod_fiscale,
            'nome' => $nome,
            'cognome' => $cognome,
            'citta_residenza' => $citta_res,
            'provincia_residenza' => $provincia_res,
            'email' => $email,
            'password' => Hash::make($password)
        ]);
    }
}
