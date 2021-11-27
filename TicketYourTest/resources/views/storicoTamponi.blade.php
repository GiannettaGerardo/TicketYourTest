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


    @if ($prenotazioni_mie->isEmpty() && $prenotazioni_dipendenti->isEmpty())

        <div class="container nessunTamponeContainer">
            <x-succes-msg>Nessuna Prenotazione! <br> <a
                    href="{{ route('marca.laboratorii.vicini', ['tipoPrenotazione' => 'prenotaPerSe']) }}">Clicca qui per
                    prenotare subito un tampone</a> </x-succes-msg>
        </div>

    @else

        @if (!$prenotazioni_mie->isEmpty())

            <x-storico-prenotazioni.tabella-storico-per-se :prenotazioni="$prenotazioni_mie" />

        @endif


        
        @if ((Session::get('Attore') == 2) && (!$prenotazioni_dipendenti->isEmpty()))
{{$prenotazioni_dipendenti}}
            <hr>

            <x-storico-prenotazioni.tabella-storico-per-terzi :prenotazioni="$prenotazioni_dipendenti" />

        @endif
    @endif

</body>

</html>
