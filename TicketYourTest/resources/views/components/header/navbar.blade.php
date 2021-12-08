<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />

<nav id="mainNavBar" class="hiddenDisplay">
    <ul id="mainNavBar_items" class="rowP">

        @if (!Session::has('Attore'))

        <li class="rowP"><a href="{{route('visualizza.guida.unica')}}">Guida tamponi</a></li>
        <li class="rowP"><a href="{{route('login')}}">Login</a></li>
        <li class="rowP"><a href="{{route('registrazione.cittadino')}}">Registrati</a></li>

        @else

        @if (Session::get('Attore') == 0){{--navbar items per l'amministratore--}}

        <li class="rowP"><a href="{{route('convenziona.laboratorio.vista')}}">Richieste convenzionamento</a></li>
        <li class="rowP"><a href="{{route('logout')}}">Logout</a></li>

        @elseif (Session::get('Attore') == 1){{--navbar items per il cittadino privato--}}

        <li class="rowP"><a href="{{route('visualizza.guida.unica')}}">Guida tamponi</a></li>

        <li class="rowP">
            <div class="dropdown">Prenota tampone <i class="fas fa-caret-down"></i>
                <div class="dropdown-content">
                    <a href="{{route('marca.laboratorii.vicini',['tipoPrenotazione'=>'prenotaPerSe'])}}" id="prenotaPerSe">Per te</a>
                    <a href="{{route('marca.laboratorii.vicini',['tipoPrenotazione'=>'prenotaPerTerzi'])}}" id="prenotaPerTerzi">Per terzi</a>
                </div>
            </div>
            </div>
        </li>

        <li class="rowP">
            <div class="dropdown">Prenotazioni <i class="fas fa-caret-down"></i>
                <div class="dropdown-content">
                    <a href="{{route('storico.prenotazioni')}}">Storico tamponi</a>
                    <a href="{{route('calendario.prenotazioni')}}">Calendario</a>
                </div>
            </div>
            </div>
        </li>

        <li class="rowP"><a href="{{route('abbandona.lista.vista')}}">Iscrizioni aziendali</a></li>

        <li class="rowP">
            <div class="dropdown">{{Session::get('Nome')}} <i class="fas fa-caret-down"></i>
                <div class="dropdown-content">
                    <a href="{{route('profiloUtente.visualizza')}}">Profilo</a>
                    <a href="{{route('logout')}}">Logout</a>
                </div>
            </div>
            </div>
        </li>

        @elseif (Session::get('Attore') == 2){{--navbar items per il datore--}}

        <li class="rowP"><a href="{{route('visualizza.guida.unica')}}">Guida tamponi</a></li>

        <li class="rowP">
            <div class="dropdown">Prenota tampone <i class="fas fa-caret-down"></i>
                <div class="dropdown-content">
                    <a href="{{route('marca.laboratorii.vicini',['tipoPrenotazione'=>'prenotaPerSe'])}}" id="prenotaPerSe">Per te</a>
                    <a href="{{route('marca.laboratorii.vicini',['tipoPrenotazione'=>'prenotaPerDipendenti'])}}" id="prenotaPerDipendenti">Per dipendenti</a>
                </div>
            </div>
            </div>
        </li>

        <li class="rowP">
            <div class="dropdown">Prenotazioni <i class="fas fa-caret-down"></i>
                <div class="dropdown-content">
                    <a href="{{route('storico.prenotazioni')}}">Storico</a>
                    <a href="{{route('calendario.prenotazioni')}}">Calendario</a>
                </div>
            </div>
            </div>
        </li>

        <li class="rowP">
            <div class="dropdown">Dipendenti <i class="fas fa-caret-down"></i>
                <div class="dropdown-content">
                    <a href="{{route('richieste.inserimento.lista')}}">Richieste</a>
                    <a href="{{route('visualizza.lista.dipendenti')}}">Elenco</a>
                </div>
            </div>
            </div>
        </li>

        <li class="rowP">
            <div class="dropdown"> {{Session::get('Nome')}} <i class="fas fa-caret-down"></i>
                <div class="dropdown-content">
                    <a href="{{route('profiloUtente.visualizza')}}">Profilo</a>
                    <a href="{{route('logout')}}">Logout</a>
                </div>
            </div>
            </div>
        </li>

        @elseif (Session::get('Attore') == 3){{--navbar items per il medico--}}

        <li class="rowP"><a href="{{route('visualizza.guida.unica')}}">Guida tamponi</a></li>

        <li class="rowP">
            <div class="dropdown">Prenota tampone <i class="fas fa-caret-down"></i>
                <div class="dropdown-content">
                    <a href="{{route('marca.laboratorii.vicini',['tipoPrenotazione'=>'prenotaPerSe'])}}" id="prenotaPerSe">Per te</a>
                    <a href="{{route('marca.laboratorii.vicini',['tipoPrenotazione'=>'prenotaPerTerzi'])}}" id="prenotaPerTerzi">Per paziente</a>
                </div>
            </div>
            </div>
        </li>

        <li class="rowP">
            <div class="dropdown">Prenotazioni <i class="fas fa-caret-down"></i>
                <div class="dropdown-content">
                    <a href="{{route('storico.prenotazioni')}}">Storico</a>
                    <a href="{{route('calendario.prenotazioni')}}">Calendario</a>
                </div>
            </div>
            </div>
        </li>


        <li class="rowP">
            <div class="dropdown"> {{Session::get('Nome')}} <i class="fas fa-caret-down"></i>
                <div class="dropdown-content">
                    <a href="{{route('profiloUtente.visualizza')}}">Profilo</a>
                    <a href="{{route('logout')}}">Logout</a>
                </div>
            </div>
            </div>
        </li>

        @elseif (Session::get('Attore') == 4){{--navbar items per il laboratorio--}}

        <li class="rowP">
            <div class="dropdown">prenotazioni <i class="fas fa-caret-down"></i>
                <div class="dropdown-content">
                    <a href="{{route('form.prenotazione')}}">Da effettuare...</a>
                    <a href="{{route('visualizza.elenco.pazienti.odierni')}}">Carica risultato</a>
                </div>
            </div>
            </div>
        </li>

        <li class="rowP"><a href="{{route('registrazione.pagamenti')}}">pagamenti</a></li>

        <li class="rowP" style="margin-right: 1em">
            <div class="dropdown"> {{Session::get('Nome')}} <i class="fas fa-caret-down"></i>
                <div class="dropdown-content">
                    <a href="{{route('profiloLab')}}">Profilo</a>
                    <a href="{{route('logout')}}">Logout</a>
                </div>
            </div>
            </div>
        </li>

        @endif

        @endif


    </ul>
</nav>