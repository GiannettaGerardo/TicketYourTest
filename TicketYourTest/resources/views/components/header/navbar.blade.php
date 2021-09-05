<nav id="mainNavBar" class="hiddenDisplay">
    <ul id="mainNavBar_items" class="rowP">

        @if (!Session::has('Attore'))

            <li class="rowP"><a href="">Guida tamponi</a></li>
            <li class="rowP"><a href="">Chi siamo</a></li>
            <li class="rowP"><a href="">Login</a></li>
            <li class="rowP"><a href="">Registrati</a></li>

        @else

            @if (Session::get('Attore') == 0){{--navbar items per l'amministratore--}}

                <li class="rowP"><a href="">Richieste convenzionamento</a></li>
                <li class="rowP"><a href="">Logout</a></li>

            @elseif (Session::get('Attore') == 1){{--navbar items per il cittadino privato--}}

                <li class="rowP"><a href="">Guida tamponi</a></li>
                <li class="rowP"><a href="">Prenota tampone{{--dropdwon per se o per terzi--}}</a></li>
                <li class="rowP"><a href="">Prenotazioni{{--dropdow storico e calendario--}}</a></li>
                <li class="rowP"><a href="">Iscrizioni aziendali</a></li>
                <li class="rowP"><a href="">nome utente{{--dropdown con logout e priflo--}}</a></li>

            @elseif (Session::get('Attore') == 2){{--navbar items per il datore--}}

                <li class="rowP"><a href="">Guida tamponi</a></li>
                <li class="rowP"><a href="">Prenota tampone{{--dropdwon per se o per dipendete--}}</a></li>
                <li class="rowP"><a href="">Prenotazioni{{--dropdow storico e calendario--}}</a></li>
                <li class="rowP"><a href="">Dipendenti{{--dropdown richieste e dip gia accettai--}}</a></li>
                <li class="rowP"><a href="">nome utente{{--dropdown con logout e priflo--}}</a></li>

            @elseif (Session::get('Attore') == 3){{--navbar items per il medico--}}

                <li class="rowP"><a href="">Guida tamponi</a></li>
                <li class="rowP"><a href="">Prenota tampone</a></li>
                <li class="rowP"><a href="">nome utente{{--dropdown con logout e priflo--}}</a></li>

            @elseif (Session::get('Attore') == 4){{--navbar items per il laboratorio--}}

                <li class="rowP"><a href="">Prenotazioni</a></li>
                <li class="rowP"><a href="">Nomelab{{--dropdown logout e profilo--}}</a></li>

            @endif

        @endif


    </ul>
</nav>