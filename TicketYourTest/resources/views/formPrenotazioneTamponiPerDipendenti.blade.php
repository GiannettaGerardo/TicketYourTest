<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Prenota tampone </title>

    <script src="{{ URL::asset('/script/script.js') }}"></script>

    <!-- Foglio di stile -->
    <link rel="stylesheet" href="{{ URL::asset('/css/stile.css') }}">
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
        integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
        integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous">
    </script>

</head>

<body style="overflow-x: hidden">

    <!-- Navbar -->
    <x-header.header />

    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="aggiungi-form">
                
                    <form action="{{ route('prenotazione.dipendenti') }}" class="mt-5 p-4 bg-light border" method="POST"
                        id="formPrenotazioneTamponePerDipendenti" onsubmit="preventDobleSubmit('formPrenotazioneTamponePerDipendenti')">
                        <!--Messaggi di errore e successo -->
                        @error('tampone')
                            <x-err-msg>{{ $message }} </x-err-msg>
                        @enderror

                        @error('data_tampone')
                            <x-err-msg>{{ $message }} </x-err-msg>
                        @enderror

                        @if (Session::has('prenotazione-esistente'))
                            <x-err-msg>{{ Session::get('prenotazione-esistente') }}</x-err-msg>
                        @endif

                        @if (Session::has('prenotazione-success'))
                            <x-succes-msg>{{ Session::get('prenotazione-success') }}</x-succes-msg>
                        @endif
                        <!--Fine messaggi di errore e successo -->
                        <h3 class="mb-3">
                            {{ $laboratorio_scelto->nome }}
                            <small class="text-muted">Prenotazione tampone per dipendenti</small>
                        </h3>
                        @csrf
                        <!-- input utilizzato per poter restituire id del laboratorio -->
                        <input name="id_lab" value="{{ $laboratorio_scelto->id }}" type="hidden">

                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label>Tampone:</label>
                                <select id="tampone" name="tampone" class="form-control">
                                    <option selected disabled>Scegli tampone... </option>
                                    @foreach ($tamponi_prenotabili as $tampone)
                                        <option>{{ $tampone->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Scegli il giorno:</label>
                                <select id="data_tampone" name="data_tampone" class="form-control"
                                    onchange="changeOption()">
                                    <option selected disabled>Scegli il giorno... </option>
                                    @foreach ($giorni_prenotabili as $giorno)
                                        <option>{{ $giorno['data'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Posti disponibili:</label>
                                <input class="form-control" id="posti_disponibili" name="posti_disponibili"
                                    type="text" readonly>

                                <!-- Script inerente alla stampa dei posti disponibili per effettuare una prenotazione -->
                                <script>
                                    function changeOption() {
                                        //Questa variabile assumerà il valore che è definito all'interno della variabile $giorni_prenotabili in php
                                        let giorni_prenotabili = @php echo json_encode($giorni_prenotabili); @endphp;
                                        //Seleziona tutte le possibili date del tampone in una variabile chiamata selectDataTampone
                                        let selectDataTampone = document.querySelector("#data_tampone");
                                        //Seleziona la singola opzione scelta dall'utente e ne restituisce una stringa inerente all'opzione scelta.
                                        let optionValueDataTampone = selectDataTampone.options[selectDataTampone.selectedIndex].value;

                                        for (let giorno of giorni_prenotabili) {
                                            if (giorno.data == optionValueDataTampone) {
                                                let inputPostiDisponibili = document.querySelector("#posti_disponibili");
                                                inputPostiDisponibili.value = giorno.posti_disponibili;
                                            }
                                        }
                                    }
                                </script>

                            </div>
                            <div class="mb-3 col-md-12">
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong>Gentile utente</strong>
                                    Le informiamo che se il numero di prenotazioni richieste superano il numero delle
                                    possibili prenotazioni odierne,
                                    le eccessive richieste verranno confermate nei giorni a seguire.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                                        id="remove_msg">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3 col-md-12">
                                <h4 class="mt-3">Scegli uno o più dipendenti:</h4>
                                <div class="scrollable">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Nome</th>
                                                <th scope="col">Cognome</th>
                                                <th scope="col">Codice fiscale</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dipendenti as $dipendenti)
                                                <x-prenotazione-dipendenti.prenota-dipendente
                                                    :dipendenti="$dipendenti" />
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class=" mt-3 mb-3 col-md-12">
                                <button type="submit" class="btn btn-success btn-lg btn-block">Conferma
                                    prenotazione</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



</body>

</html>
