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


    <table class="refertoTable">
        <thead>
            <tr>
                <th>Intestazione</th>
            </tr>
        </thead>

        <tbody>

            <tr>
                <td><b>codice fiscale</b><br>{{$referto->cf_paziente}}</td>
                <td><b>nome e cognome</b><br>{{$referto->nome_paziente}} {{$referto->cognome_paziente}}</td>
            </tr>

            <tr>
                <td><b>data tampone</b><br>{{$referto->data_tampone}}</td>
                <td><b>data referto</b><br>{{$referto->data_referto}}</td>
            </tr>

            <tr>
                <td><b>esito</b><br>{{$referto->esito_tampone}}</td>
                @if($referto->esito_tampone !== "negativo")
                <td><b>quantita</b><br>{{$referto->quantita}}</td>
                @endif
            </tr>

        </tbody>
    </table>

</body>

</html>