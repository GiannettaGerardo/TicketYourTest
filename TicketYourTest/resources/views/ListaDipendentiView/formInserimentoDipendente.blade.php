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

<body>

    <!-- Navbar -->
    <x-header.header />
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="aggiungi-form">
                    <form action="{{route('inserisci.dipendente')}}" class="mt-5 p-4 bg-light border" method="POST">
                        @csrf
                        <h4 class="mb-4 text-secondary">Aggiungi un Dipendente</h4>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label>Nome:</label>
                                <input type="text" name="nome" class="form-control" placeholder="Nome">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Cognome:</label>
                                <input type="text" name="cognome" class="form-control"  placeholder="Cognome">
                            </div>

                            <div class="mb-3 col-md-12">
                                <label>Codice Fiscale:</label>
                                <input type="text" name="codfiscale" class="form-control"  placeholder="Codice Fiscale">
                            </div>
                            <div class="mb-3 col-md-12">
                                <label>E-mail:</label>
                                <input type="text" name="email" class="form-control"  placeholder="E-mail">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Città di residenza:</label>
                                <input type="text" name="citta_residenza" class="form-control"  placeholder="Città di Residenza">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Provincia:</label>
                                <input type="text" name="provincia_residenza" class="form-control"  placeholder="Provincia">
                            </div>
                            <div class="mb-3 col-md-12">
                            <button type="submit" class="btn btn-success float-right mt-2">Aggiungi dipendente</button>
                            </div>
                        </div>
                        @error('nome')
                            <x-err-msg>{{$message}} </x-err-msg>  
                        @enderror
                        @error('cognome')
                            <x-err-msg>{{$message}} </x-err-msg>  
                        @enderror
                        @error('citta_residenza')
                            <x-err-msg>{{$message}} </x-err-msg>  
                        @enderror
                        @error('provincia_residenza')
                            <x-err-msg>{{$message}} </x-err-msg>  
                        @enderror

                        @if (Session::has('inserimento-success'))
                            <x-succes-msg>{{ Session::get('inserimento-success') }}</x-succes-msg>
                        @endif

                        @if (Session::has('cittadino-esistente'))
                            <x-err-msg>{{ Session::get('cittadino-esistente') }}</x-err-msg>
                        @endif

                    </form>
                        
                </div>
            </div>
        </div>
    </div>



</body>

</html>