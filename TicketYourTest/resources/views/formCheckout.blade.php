<!doctype html>
<html lang="en">
    <!--Script utilizzato per effettuare la scelta tra i 2 metodi di pagamento -->
    <script>
        function viewFormCreditCard(x) {
            if( x == 0 ) {
                document.getElementById("pagamentoStrutturaForm").style.display="none";
                document.getElementById("formCreditCard").style.display="block";
            }
            else {
                document.getElementById("formCreditCard").style.display="none";
                document.getElementById("pagamentoStrutturaForm").style.display="block";
            }
        return;
        }
    </script>
    <!--Fine Script inerente alla scelta dei due metodi di pagamento -->
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
      <script src="{{ URL::asset('/script/script.js') }}"></script>
    <title>Checkout</title>
  </head>
  <body class="bg-light" style="overflow-x: hidden">
    <!--Navbar del sito -->
    <x-header.header />

    <!-- Errori -->

    @if (Session::has('prenotazione-success'))
        <div class="prenotazioneSuccessAlertContainer hiddenDisplay">
            <x-succes-msg>{{ Session::get('prenotazione-success') }}</x-succes-msg>
        </div>
        <script>
            showAlertContainer("prenotazioneSuccessAlertContainer");
            hiddenAlertContainer("prenotazioneSuccessAlertContainer", 3000);
        </script>
    @endif

    @if (Session::has('prenotazione-esistente') && Session::get('prenotazione-esistente') !== null)
        <div class="prenotazioneEsistenteAlertContainer hiddenDisplay">
            <x-err-msg>{{ Session::get('prenotazione-esistente') }}</x-err-msg>
        </div>
        <script>
            showAlertContainer("prenotazioneEsistenteAlertContainer");
            hiddenAlertContainer("prenotazioneEsistenteAlertContainer", 3000);
        </script>
    @endif

    @if (Session::has('giorni-prenotazioni-superati') && Session::get('giorni-prenotazioni-superati') != null)
        <div class="prenotazioneErrorAlertContainer hiddenDisplay">
            <x-err-msg>{{ Session::get('giorni-prenotazioni-superati') }}</x-err-msg>
        </div>
        <script>
            showAlertContainer("prenotazioneErrorAlertContainer");
            hiddenAlertContainer("prenotazioneErrorAlertContainer", 3000);
        </script>
    @endif

    <!-- Parte iniziale della pagina che svolge il compito di far visualizzare il logo e alcune info inerenti al pagamento -->
    @if ( Session::has('prenotazioni'))
        @php
            $prenotazioniEffettuate = Session::get("prenotazioni");
            $importo_totale = 0;
        @endphp

        <div class="container">
            <div class="py-5 text-center">
                <img class="mb-2 d-block mx-auto" src="images/logo.png" alt="ticketYourTestLogo" width="165" height="80">

                <h2>{{$prenotazioniEffettuate[0]["nome_laboratorio"]}}</h2>
                    <small>
                        <b>Informazioni genenerali:</b> <br>
                        <b>Tipo tampone:</b>{{$prenotazioniEffettuate[0]['nome_tampone']. ' (' .  $prenotazioniEffettuate[0]['costo_tampone'] . ') ' . 'x' . count($prenotazioniEffettuate)}}<br>
                        <b>Nome completo:</b> <br>

                            @foreach ($prenotazioniEffettuate as $prenotazione)
                            {{"-".$prenotazione["nome_paziente"]." ".$prenotazione["cognome_paziente"]}}<br>
                            @php
                                $importo_totale += $prenotazione["costo_tampone"];
                            @endphp
                            @endforeach
                        <br>
                        <b>Importo da pagare:</b> {{$importo_totale}}&euro;
                    </small>
            </div>
        </div>
        <!--Fine informazioni inerenti al pagamento e alla conferma della prenotazione -->

        <!--Parte di codice inerente alla possibilità di scegliere il tipo di pagamento di un tampone -->
        <div class="container w-50 mt-0">
            <hr class="my-4">
            <h4 class="mb-3">Pagamento</h4>
        <div class="form-check">
            <input id="creditCard" name="metodo_di_pagamento" type="radio" class="form-check-input" onclick="viewFormCreditCard(0)" checked>
            <label for="creditCard">Carta di credito</label>
        </div>
        <div class="form-check">
                <input id="contanti" name="metodo_di_pagamento" type="radio" class="form-check-input" onclick="viewFormCreditCard(1)">
                <label for="contanti">Presso la struttura</label>
        </div>
        <!--Fine radio (scelta pagamento tampone) -->

        <hr class="my-4">

        <div id="pagamentoStrutturaForm" style="display: none">
            <form action="{{route('calendario.prenotazioni')}}" method="get">
                @csrf
                <div class="row">
                    <button type="submit" class="btn btn-success btn-lg btn-block mb-5"> Conferma </button>
                </div>
            </form>
        </div>

        <!--Form inerente all'inserimento delle credenziali della carta di credito e indirizzo di fatturazione -->
        <div id="formCreditCard">
            <form action="{{route('pagamento.carta')}}" method="POST">
                @csrf

                <!-- Errori -->
                @error('nome_indirizzo_fatt')
                    <div class="errorAlertContainer hiddenDisplay">
                        <x-err-msg>Nome dell'indirizzo di fatturazione mancante</x-err-msg>
                    </div>

                    <script>
                        showAlertContainer("errorAlertContainer");
                        hiddenAlertContainer("errorAlertContainer", 2500);
                    </script>
                @enderror

                @error('cognome_indirizzo_fatt')
                    <div class="errorAlertContainer hiddenDisplay">
                        <x-err-msg>Cognome dell'indirizzo di fatturazione mancante</x-err-msg>
                    </div>

                    <script>
                        showAlertContainer("errorAlertContainer");
                        hiddenAlertContainer("errorAlertContainer", 2500);
                    </script>
                @enderror

                @error('indirizzo')
                    <div class="errorAlertContainer hiddenDisplay">
                        <x-err-msg>Indirizzo mancante</x-err-msg>
                    </div>

                    <script>
                        showAlertContainer("errorAlertContainer");
                        hiddenAlertContainer("errorAlertContainer", 2500);
                    </script>
                @enderror

                @error('paese')
                    <div class="errorAlertContainer hiddenDisplay">
                        <x-err-msg>Paese mancante</x-err-msg>
                    </div>

                    <script>
                        showAlertContainer("errorAlertContainer");
                        hiddenAlertContainer("errorAlertContainer", 2500);
                    </script>
                @enderror

                @error('citta')
                    <div class="errorAlertContainer hiddenDisplay">
                        <x-err-msg>Città mancante</x-err-msg>
                    </div>

                    <script>
                        showAlertContainer("errorAlertContainer");
                        hiddenAlertContainer("errorAlertContainer", 2500);
                    </script>
                @enderror

                @error('cap')
                    <div class="errorAlertContainer hiddenDisplay">
                        <x-err-msg>CAP mancante</x-err-msg>
                    </div>

                    <script>
                        showAlertContainer("errorAlertContainer");
                        hiddenAlertContainer("errorAlertContainer", 2500);
                    </script>
                @enderror

                @error('nome_proprietario')
                    <div class="errorAlertContainer hiddenDisplay">
                        <x-err-msg>Proprietario della carta mancante</x-err-msg>
                    </div>

                    <script>
                        showAlertContainer("errorAlertContainer");
                        hiddenAlertContainer("errorAlertContainer", 2500);
                    </script>
                @enderror

                @error('numero_carta')
                    <div class="errorAlertContainer hiddenDisplay">
                        <x-err-msg>Numero della carta mancante o errato</x-err-msg>
                    </div>

                    <script>
                        showAlertContainer("errorAlertContainer");
                        hiddenAlertContainer("errorAlertContainer", 2500);
                    </script>
                @enderror

                @error('exp_month')
                    <div class="errorAlertContainer hiddenDisplay">
                        <x-err-msg>Mese di scadenza della carta mancante o errato</x-err-msg>
                    </div>

                    <script>
                        showAlertContainer("errorAlertContainer");
                        hiddenAlertContainer("errorAlertContainer", 2500);
                    </script>
                @enderror

                @error('exp_year')
                    <div class="errorAlertContainer hiddenDisplay">
                        <x-err-msg>Anno di scadenza della carta mancante o errato</x-err-msg>
                    </div>

                    <script>
                        showAlertContainer("errorAlertContainer");
                        hiddenAlertContainer("errorAlertContainer", 2500);
                    </script>
                @enderror

                @error('cvv')
                    <div class="errorAlertContainer hiddenDisplay">
                        <x-err-msg>CVV mancante o errato. Puoi trovarlo sul retro della carta</x-err-msg>
                    </div>

                    <script>
                        showAlertContainer("errorAlertContainer");
                        hiddenAlertContainer("errorAlertContainer", 2500);
                    </script>
                @enderror

                @if (Session::has('checkout-error'))
                    <div class="errorAlertContainer hiddenDisplay">
                        <x-err-msg>{{Session::get('checkout-error')}}</x-err-msg>
                    </div>

                    <script>
                        showAlertContainer("errorAlertContainer");
                        hiddenAlertContainer("errorAlertContainer", 2500);
                    </script>
                @endif
                <!-- Fine errori -->

                @foreach ($prenotazioniEffettuate as $prenotazione)
                    <input type="hidden" name="id_prenotazioni[]" value="{{$prenotazione['id_prenotazione']}}">
                    <input type="hidden" name="importi[]" value="{{$prenotazione['costo_tampone']}}">
                @endforeach
                <input type="hidden" name="id_laboratorio" value="{{$prenotazioniEffettuate[0]['id_laboratorio']}}">
                <!--Informazioni inerenti all'indirizzo di fatturazione -->
                <h4>Indirizzo di fatturazione:</h4>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label for="firstName" class="form-label"> Nome: </label>
                        <input id="firstName" type="text" class="form-control" placeholder="Nome" name="nome_indirizzo_fatt">
                    </div>
                    <div class="col-sm-6">
                        <label for="LastName" class="form-label"> Cognome: </label>
                        <input id="LastName" type="text" class="form-control" placeholder="Cognome" name="cognome_indirizzo_fatt">
                    </div>
                    <div class="col-12">
                        <label for="indirizzoFatturazione" class="form-label"> Indirizzo: </label>
                        <input id="indirizzoFatturazione" type="text" class="form-control" placeholder="Via/Viale Rossi, 19" name="indirizzo">
                    </div>
                    <div class="col-md-4">
                        <label for="paese" class="form-label">Paese: </label>
                        <input id="paese" type="text" class="form-control" placeholder="Paese" name="paese">
                    </div>
                    <div class="col-md-4">
                        <label for="citta" class="form-label">Città: </label>
                        <input id="citta" type="text" class="form-control" placeholder="Città" name="citta">
                    </div>
                    <div class="col-md-4">
                        <label for="codice_postale" class="form-label">CAP: </label>
                        <input id="codice_postale" type="text" class="form-control" placeholder="CAP" name="cap">
                    </div>
                    <!--Fine informazioni inerenti all'indirizzo di fatturazione -->

                    <hr class="my-4">
                </div>

                <!-- Informazioni inerenti all'inserimento dei dati per effettuare il pagamento -->
                <div class="row my-3 gy-3">
                    <h4>Carta di credito:</h4>
                    <div class="col-md-6">
                        Nome sulla carta:
                        <input type="text" class="form-control" placeholder="Nome completo" name="nome_proprietario">
                    </div>
                    <div class="col-md-6">
                        Numero della carta:
                        <input type="text" class="form-control" placeholder="Numero della carta" name="numero_carta">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        Mese:
                        <input type="text" class="form-control" placeholder="MM" name="exp_month">
                    </div>
                    <div class="col-md-4">
                        Anno:
                        <input type="text" class="form-control" placeholder="YY" name="exp_year">
                        <small class="text-muted">Inserire solo le ultime due cifre dell'anno di scadenza </small>
                    </div>
                    <div class="col-md-4">
                        CVV:
                        <input type="text" class="form-control" placeholder="123" name="cvv">
                        <small class="text-muted">Il codice di sicurezza è visibile nel retro della carta </small>
                    </div>
                </div>
                <hr class="my-4">

                <div class="row">
                <button type="submit" class="btn btn-success btn-lg btn-block mb-5">Conferma pagamento </button>
                </div>
            <!-- Fine inserimento delle credenziali inerenti alla carta di credito -->
            </form>
        </div>
        <!--Fine form inerente all'inserimento delle credenziali -->
    </div>
    @else
        <x-err-msg> Impossibile accedere a questa pagina </x-err-msg>
    @endif


</body>
</html>
