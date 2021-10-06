<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Aggiungi Dipendente </title>

    <!-- Foglio di stile -->
    <link rel="stylesheet" href="{{ URL::asset('/css/stile.css') }}">
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

</head>

<body style="overflow-x: hidden">

    <!-- Navbar -->
    <x-header.header />

    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="aggiungi-form">
                    <form action="{{route("prenotazione.singola")}}" class="mt-5 p-4 bg-light border" method="POST">
                        @csrf
                        <h3 class="mb-4">
                            <!--Da dinamicizzare ancora -->
                            Laboratorio Fittizio
                            <small class="text-muted">Prenotazione tampone per terzo</small>
                          </h3>

                          <!-- input utilizzato per poter restituire id del laboratorio -->
                          <input name="id_lab" value="#" type="hidden">

                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label>Nome:</label>
                                <input class="form-control" id="nome" type="text" placeholder="Nome">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Cognome:</label>
                                <input class="form-control" id="cognome" type="text" placeholder="Cognome">
                            </div>

                            <div class="mb-3 col-md-12">
                                <label>Codice Fiscale:</label>
                                <input class="form-control" id="cod_fiscale" name="cod_fiscale" type="text" placeholder="Codice Fiscale">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>E-mail:</label>
                                <input type="text" id="email_prenotante" name="email_prenotante" class="form-control" placeholder="E-mail">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Cellulare:</label>
                                <input id="numero_cellulare" name="numero_cellulare" class="form-control"  placeholder="Cellulare">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Città di residenza:</label>
                                <input type="text" id="citta_residenza" name="citta_residenza" class="form-control" placeholder="Città di residenza">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Provincia di residenza:</label>
                                <input id="provincia_residenza" name="provincia_residenza" class="form-control"  placeholder="Provincia di residenza">
                            </div>
                            <div class="mb-3 col-md-12">
                                <label>Tampone:</label>

                                <select id="tampone" name="tampone" class="form-control">
                                    <option selected disabled>Scegli tampone... </option>
                                    <option>Tampone-1</option>
                                    <option>Tampone-2</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label>Scegli il giorno:</label>

                                <select id="data_tampone" name="data_tampone" class="form-control">
                                    <option  selected disabled>Scegli il giorno... </option>
                                    <option>12-12-2021</option>
                                    <option>12-12-2021</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-12">
                                <button type="submit" class="btn btn-success btn-lg btn-block">Conferma prenotazione</button>
                            </div>
                        </div>
                        
                        <!--Errori -->
                        @error('nome')
                            <x-err-msg>{{$message}} </x-err-msg>
                        @enderror

                        @error('cognome')
                            <x-err-msg>{{$message}} </x-err-msg>
                        @enderror

                        @error('cod_fiscale')
                            <x-err-msg>{{$message}} </x-err-msg>
                        @enderror

                        @error('email_prenotante')
                            <x-err-msg>{{$message}} </x-err-msg>
                        @enderror

                        @error('numero_cellulare')
                            <x-err-msg>{{$message}} </x-err-msg>
                        @enderror

                        @error('citta_residenza')
                            <x-err-msg>{{$message}} </x-err-msg>
                        @enderror

                        @error('provincia_residenza')
                            <x-err-msg>{{$message}} </x-err-msg>
                        @enderror

                        @error('tampone')
                            <x-err-msg>{{$message}} </x-err-msg>
                        @enderror

                        @error('data_tampone')
                            <x-err-msg>{{$message}} </x-err-msg>
                        @enderror

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