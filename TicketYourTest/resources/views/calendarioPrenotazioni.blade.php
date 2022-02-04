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

    <script src="{{ URL::asset('/script/script.js') }}"></script>
</head>

<body>

    <x-header.header />

    <!-- Messaggi di errore -->
    @if (Session::has('questionario-anamnesi-success'))
        <div class="containerSuccessQuestionarionMsg hiddenDisplay">
            <x-succes-msg>{{ Session::get('questionario-anamnesi-success') }}</x-succes-msg>
        </div>
        <script>
            showAlertContainer("containerSuccessQuestionarioMsg", 2500)
            hiddenAlertContainer("containerSuccessQuestionarioMsg", 2500)
        </script>
    @endif

    @if(Session::has('prenotazione-success'))
        <div class="containerSuccessPrenotazione hiddenDisplay">
            <x-succes-msg>{{ Session::get('prenotazione-success') }}</x-succes-msg>
        </div>
        <script>
            showAlertContainer("containerSuccessPrenotazione", 2500)
            hiddenAlertContainer("containerSuccessPrenotazione", 2500)
        </script>
    @endif

    @if(Session::has('checkout-success'))
        <div class="containerSuccessCheckout hiddenDisplay">
            <x-succes-msg>{{ Session::get('checkout-success') }}</x-succes-msg>
        </div>
        <script>
            showAlertContainer("containerSuccessCheckout", 2500)
            hiddenAlertContainer("containerSuccessCheckout", 2500)
        </script>
    @endif

    @if(($prenotazioni_mie->isEmpty()) && ($prenotazioni_per_terzi->isEmpty()) && ($prenotazioni_da_terzi === null || $prenotazioni_da_terzi->isEmpty()))

    <div class="container nessunTamponeContainer">
        <x-succes-msg>Nessuna Prenotazione! <br> <a href="{{route('marca.laboratorii.vicini',['tipoPrenotazione'=>'prenotaPerSe'])}}">Clicca qui per prenotare subito un tampone</a> </x-succes-msg>
    </div>

    @else

    @if (!$prenotazioni_mie->isEmpty())

    <x-calendario-prenotazioni.tabella-prenotazioni-per-se :prenotazioni="$prenotazioni_mie" />

    @endif

    @if(!$prenotazioni_per_terzi->isEmpty())

    <hr>

    <x-calendario-prenotazioni.tabella-prenotazioni-per-terzi :prenotazioni="$prenotazioni_per_terzi" />

    @endif

    @if($prenotazioni_da_terzi !== null && !$prenotazioni_da_terzi->isEmpty())

    <hr>

    <x-calendario-prenotazioni.tabella-prenotazioni-da-terzi :prenotazioni="$prenotazioni_da_terzi" />

    @endif
    @endif


</body>

</html>
