<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Prenota tampone </title>

    <!-- Foglio di stile -->
    <link rel="stylesheet" href="{{ URL::asset('/css/stile.css') }}">
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <script>
        
    </script>
</head>

<body style="overflow-x: hidden">

    <!-- Navbar -->
    <x-header.header />

    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="aggiungi-form">
                    <form action="{{route("form.prenotazione.dipendenti")}}" class="mt-5 p-4 bg-light border" method="POST">
                        <h3 class="mb-3">
                            {{$laboratorio_scelto->nome}}
                            <small class="text-muted">Prenotazione tampone per dipendenti</small>
                        </h3>
                        @csrf
                          <!-- input utilizzato per poter restituire id del laboratorio -->
                          <input name="id_lab" value="{{$laboratorio_scelto->id}}" type="hidden">
                        
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label>Tampone:</label>
                                <select id="tampone" name="tampone" class="form-control">
                                    <option selected disabled>Scegli tampone... </option>
                                    @foreach ($tamponi_prenotabili as $tampone)
                                        <option>{{$tampone->nome}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Scegli il giorno:</label>
                                <select id="data_tampone" name="data_tampone" class="form-control">
                                    <option  selected disabled>Scegli il giorno... </option>
                                    @foreach ($giorni_prenotabili as $giorno)
                                        <option id="data">{{$giorno["data"]}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Posti disponibili:</label>
                                <input class="form-control" id="posti_disponibili" name="posti_disponibili" type="text" readonly>
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
                                                <x-prenotazione-dipendenti.prenota-dipendente :dipendenti="$dipendenti"/>
                                            @endforeach
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                                <div class=" mt-3 mb-3 col-md-12">
                                    <button type="submit" class="btn btn-success btn-lg btn-block">Conferma prenotazione</button>
                                </div>
                        </div>

                        @error('numero_cellulare')
                            <x-err-msg>{{$message}} </x-err-msg>
                        @enderror

                        @error('tampone')
                            <x-err-msg>{{$message}} </x-err-msg>
                        @enderror

                        @error('data_tampone')
                            <x-err-msg>{{$message}} </x-err-msg>
                        @enderror

                        @if (Session::has('prenotazione-esistente'))
                            <x-err-msg>{{ Session::get('prenotazione-esistente') }}</x-err-msg>
                        @endif

                        @if (Session::has('prenotazione-success'))
                            <x-succes-msg>{{ Session::get('prenotazione-success') }}</x-succes-msg>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
