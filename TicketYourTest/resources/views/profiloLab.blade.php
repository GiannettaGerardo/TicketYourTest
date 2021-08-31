<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo</title>

    <link rel="stylesheet" href="{{ URL::asset('/css/stile.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</head>

<body>

    <x-header.header />

    @if ($fornisci_calendario == false)

        <form class="formModificaDatiLaboratorio columnP" id="formModificaDatiLaboratorio">

            @csrf

            <div class="formModificaDatiLaboratorio_forms">
                <x-forms-profilo-lab.form-modifica-tamponi-offerti />

                <span class="hr"></span>

                <x-forms-profilo-lab.form-calendario-disponibilita />
            </div>
            <button type="submit" class="btn btn-submit" style="margin-top: 0.5em;">Modifica</button>

        </form>

    @else

        <form class="submitFormCalendiarioDisponibilita columnP" id="submitFormCalendiarioDisponibilita" action="{{route('inserisci.calendario.disponibilita')}}" method="POST">

            @csrf

            <div class="formModificaDatiLaboratorio_forms">
                <x-forms-profilo-lab.form-calendario-disponibilita />
            </div>

            <input type="hidden" value="{{$laboratorio_esiste->id}}" name="id_laboratorio">

            <button type="submit" class="btn btn-submit" style="margin-top: 0.5em;">Conferma</button>

        </form>

    @endif





</body>

</html>