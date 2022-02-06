<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>prenotazioni odierne</title>

    <link rel="stylesheet" href="{{ URL::asset('/css/stile2.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


    <script src="{{ URL::asset('/script/script.js') }}"></script>

</head>

<body>

    <x-header.header />

    @if (Session::has('referto-success'))
        <div class="refertoSuccessAlertContainer hiddenDisplay">
            <x-succes-msg>{{ Session::get('referto-success') }}</x-succes-msg>
        </div>

        <script>
            showAlertContainer("refertoSuccessAlertContainer");
            hiddenAlertContainer("refertoSuccessAlertContainer", 3000);
        </script>
    @endif

    @if (Session::has('referto-error'))
        <div class="refertoErrorAlertContainer hiddenDisplay">
            <x-err-msg>{{ Session::get('referto-error') }}</x-err-msg>
        </div>

        <script>
            showAlertContainer("refertoErrorAlertContainer");
            hiddenAlertContainer("refertoErrorAlertContainer");
        </script>
    @endif

    @if (!$pazienti_odierni->isEmpty())

        <x-elenco-pazienti-odierni.tabella-elenco :prenotazioni="$pazienti_odierni" />

    @else

        <div class="container nessunTamponeContainer">
            <x-succes-msg style="">nessuna prenotazione</x-succes-msg><br>
        </div>

    @endif

</body>

</html>
