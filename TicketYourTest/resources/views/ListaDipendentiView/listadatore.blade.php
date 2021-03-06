<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Lista dipendenti </title>

    <script rel="stylesheet" href="{{ URL::asset('/script/script.js') }}"> </script>
    <!-- Foglio di stile -->
    <link rel="stylesheet" href="{{ URL::asset('/css/stile.css') }}">
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>

</head>

<body>
    <!-- Navbar -->
    <x-header.header />

    <div class="container-fluid">
        <h1 class="h1">
            Lista dipendenti
        </h1>

        <a href="{{ route('inserisci.dipendente.form') }}" class="btn btn-success mb-2">Aggiungi Dipendente +</a>


        @if (count($listaDipendenti) > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Cognome</th>
                        <th scope="col">Codice Fiscale</th>
                        <th scope="col">Città</th>
                        <th scope="col">Provincia</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Elimina</th>
                    </tr>
                </thead>


                <tbody>
                    @foreach ($listaDipendenti as $dipendente)
                        <x-dipendenti.container-dip :dipendente="$dipendente" />
                    @endforeach

                </tbody>
            </table>




        @else
            <x-succes-msg>Non ci sono dipendeti da visualizzare</x-succes-msg>
        @endif

    </div>


</body>

</html>
