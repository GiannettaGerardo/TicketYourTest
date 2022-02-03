<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TicketYourTest</title>

    <link href="{{ URL::asset('/css/stile.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ URL::asset('/script/script.js') }}"></script>
</head>

<body>


    <x-header.header />

    @if (Session::has('questionario-anamnesi-success'))
        <div class="questionarioSuccessAlertContainer hiddenDisplay">
            <x-succes-msg>{{ Session::get('questionario-anamnesi-success') }}</x-succes-msg>
        </div>

        <script>
            showAlertContainer("questionarioSuccessAlertContainer");
            hiddenAlertContainer("questionarioSuccessAlertContainer", 3000);
        </script>
    @endif

    @if (Session::has('Attore') && Session::get('Attore') == 4)
        <script>
            window.location.href = "{{ route('profiloLab') }}";
        </script>
    @endif

    @if (Session::has('Attore') && Session::get('Attore') == 0)

        <script>
            window.location.href = "{{ route('convenziona.laboratorio.vista') }}";
        </script>

    @else
        <section class="coverHome">
            <div class="coverLogo">

            </div>
            <a href="{{ route('marca.laboratorii.vicini') }}"> Prenota un tampone</a>
        </section>

    @endif

</body>

</html>
