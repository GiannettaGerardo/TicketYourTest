<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>calendario</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>

    <x-header.header />

    @if (!$prenotazioni_mie->isEmpty())

    <div class="container-fluid mt-3">

        <h3>Prenotazione personali</h3>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Data Prenotazione</th>
                    <th scope="col">Data tampone</th>
                    <th scope="col">Tipo Tampone</th>
                    <th scope="col">Laboratorio scelto</th>
                </tr>
            </thead>


            <tbody>

                @foreach ($prenotazioni_mie as $prenotazione)

                <x-calendario-prenotazioni.riga-tabella-prenotazioni :prenotazione="$prenotazione" />

                @endforeach

            </tbody>
        </table>

    </div>

    @endif

    @if(!$prenotazioni_per_terzi->isEmpty())

    <hr>

    <div class="container-fluid mt-3">

        <h3>Prenotazioni per terzi</h3>

        <table class="table table-striped">

            <thead>
                <tr>
                    <th scope="col">Data Prenotazione</th>
                    <th scope="col">Data tampone</th>
                    <th scope="col">Tipo Tampone</th>
                    <th scope="col">Laboratorio scelto</th>
                    <th scope="col">Per...</th>
                </tr>
            </thead>


            <tbody>
                @foreach ($prenotazioni_per_terzi as $prenotazione)

                <x-calendario-prenotazioni.riga-tabella-prenotazioni :prenotazione="$prenotazione" />

                @endforeach

            </tbody>

        </table>

    </div>

    @endif

    @if(!$prenotazioni_da_terzi->isEmpty())

    <hr>

    <div class="container-fluid mt-3">

        <h3>Prenotazioni personali</h3>

        <table class="table table-striped">

            <thead>
                <tr>
                    <th scope="col">Data Prenotazione</th>
                    <th scope="col">Data tampone</th>
                    <th scope="col">Tipo Tampone</th>
                    <th scope="col">Laboratorio scelto</th>
                    <th scope="col">Da parte di...</th>
                </tr>
            </thead>


            <tbody>

                @foreach ($prenotazioni_da_terzi as $prenotazione)

                <x-calendario-prenotazioni.riga-tabella-prenotazioni :prenotazione="$prenotazione" />

                @endforeach

            </tbody>

        </table>

    </div>

    @endif

</body>

</html>