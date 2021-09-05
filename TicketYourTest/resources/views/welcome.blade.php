<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <link href="{{ URL::asset('/css/stile.css') }}" rel="stylesheet" type="text/css">

</head>

<body>


    <x-header.header />

    <section class="coverHome">

        <div class="coverLogo">

        </div>
        <a href="{{route('marca.laboratorii.vicini')}}"> Prenota un tampone</a>
    </section>
</body>

</html>