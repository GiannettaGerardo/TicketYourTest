<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>storico tamponi</title>

    <link rel="stylesheet" href="{{ URL::asset('/css/stile2.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>

    <x-header.header />

    
    @if ($storicoPersonale->isEmpty() && (($storicoPerTerzi->isEmpty()) || ($storicoPerTerzi == null)))

        <div class="container nessunTamponeContainer">
            <x-succes-msg>Nessuna Prenotazione! <br> <a
                    href="{{ route('marca.laboratorii.vicini', ['tipoPrenotazione' => 'prenotaPerSe']) }}">Clicca qui per
                    prenotare subito un tampone</a> </x-succes-msg>
        </div>

    @else

        @if (!$storicoPersonale->isEmpty())

            <x-storico-prenotazioni.tabella-storico-per-se :prenotazioni="$storicoPersonale" />

        @endif

        <hr>
        
        @if (((Session::get('Attore') == 2) || (Session::get('Attore') == 3)) && (!$storicoPerTerzi->isEmpty()))

            <x-storico-prenotazioni.tabella-storico-per-terzi :prenotazioni="$storicoPerTerzi" />
            <hr>

        @endif
    @endif

</body>

</html>
