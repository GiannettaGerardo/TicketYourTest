<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>calendario</title>

    <link rel="stylesheet" href="{{ URL::asset('/css/stile2.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    
</head>

<body>

    <x-header.header />

    @if (Session::has('questionario-anamnesi-success'))

    <div class="containerSuccessPrenotationMsg">
        <x-succes-msg>{{Session::get('questionario-anamnesi-success')}}</x-succes-msg>
    </div>
    <script src="{{ URL::asset('/script/script.js') }}"></script>
    <script>
        hiddenAlertContainer(containerSuccessPrenotationMsg,3000)
    </script>
        
    @endif

    @if(($prenotazioni_mie->isEmpty()) && ($prenotazioni_per_terzi->isEmpty()) && ($prenotazioni_da_terzi->isEmpty()))

    <div class="container nessunTamponeContainer">
        <x-succes-msg>Nessuna Prenotazionegit! <br> <a href="{{route('marca.laboratorii.vicini',['tipoPrenotazione'=>'prenotaPerSe'])}}">Clicca qui per prenotare subito un tampone</a> </x-succes-msg>
    </div>

    @else

    @if (!$prenotazioni_mie->isEmpty())
{{$prenotazioni_mie}}
    <x-calendario-prenotazioni.tabella-prenotazioni-per-se :prenotazioni="$prenotazioni_mie" />

    @endif

    @if(!$prenotazioni_per_terzi->isEmpty())

    <hr>

    <x-calendario-prenotazioni.tabella-prenotazioni-per-terzi :prenotazioni="$prenotazioni_per_terzi" />

    @endif

    @if(!$prenotazioni_da_terzi->isEmpty())

    <hr>

    <x-calendario-prenotazioni.tabella-prenotazioni-da-terzi :prenotazioni="$prenotazioni_da_terzi" />

    @endif
    @endif


</body>

</html>