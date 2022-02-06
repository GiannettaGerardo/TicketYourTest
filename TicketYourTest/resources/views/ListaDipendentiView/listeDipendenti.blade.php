<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Lista dipendenti </title>

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
    
    <div class="container-fluid">
    <h1 class="h1">
        Lista aziende
    </h1>
    
    <a href="{{route('richiedi.inserimento.lista.vista')}}" class="btn btn-success mb-2">Inserisci nuova azienda</a>

        @if (count($listeCittadino) > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="col-sm-10">Nome azienda</th>
                    <th scope="col">Abbandona Lista</th>
                </tr>
            </thead>
    
            
            <tbody>
                @foreach ($listeCittadino as $azienda)
                    <x-dipendenti.liste-aziende :azienda="$azienda" /> 
                @endforeach
            </tbody>
        </table>
        @else
        <x-succes-msg>Non ci sono aziende da visualizzare</x-succes-msg>
        @endif

    </div>


</body>

</html>