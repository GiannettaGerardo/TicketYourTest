<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>referto</title>

    <link rel="stylesheet" href="{{ URL::asset('/css/stile.css') }}">
</head>

<body>

<table style="width: 100%">

        <caption style="text-align: left">
            <h2>
                TicketYourTest<br>
                <small>referto</small>
            </h2>
        </caption>

        <tbody style="font-size: 1.2em">

            <tr>

                <td style="width=50%">
                    <label> <strong>Codice Fiscale:</strong> </label> <br>
                    <span>{{ $referto->cf_paziente}}</span>
                </td>

                <td style="width=50%">
                    <label> <strong>Nome e cognome:</strong> </label> <br>
                    <span>{{ $referto->nome_paziente}} {{ $referto->cognome_paziente}}</span>
                </td>

            </tr>

            <tr>

                <td style="width=50%">
                    <label> <strong>Data tampone:</strong> </label> <br>
                    <span>{{ $referto->data_tampone}}</span>
                </td>

                <td style="width=50%">
                    <label> <strong>Data referto:</strong> </label> <br>
                    <span>{{ $referto->data_referto}}</span>
                </td>

            </tr>

            <tr>

                <td style="width=50%">
                    <label> <strong>Desito tampone:</strong> </label> <br>
                    <span>{{ $referto->esito_tampone}}</span>
                </td>

                @if ($referto->esito_tampone !== "negativo")
                    <td style="width=50%">
                        <label> <strong>Carica virale:</strong> </label> <br>
                        <span>{{ $referto->quantita}}</span>
                    </td>
                @endif

            </tr>
           
        </tbody>

    </table>

    
</body>

</html>
