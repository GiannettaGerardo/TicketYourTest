<?php

namespace App\Http\Controllers;

/**
 * Class Attore
 * Contiene i flag degli attori usati per i controlli sulla tipologia di utente
 * @package App\Http\Controllers
 */
class Attore
{
    public const AMMINISTRATORE = 0;           // costante per indicare l'amministratore
    public const CITTADINO_PRIVATO = 1;        // costante per indicare il cittadino privato
    public const DATORE_LAVORO = 2;            // costante per indicare il datore di lavoro
    public const MEDICO_MEDICINA_GENERALE = 3; // costante per indicare il medico di medicina generale
    public const LABORATORIO_ANALISI = 4;      // costante per indicare il laboratorio di analisi
}
