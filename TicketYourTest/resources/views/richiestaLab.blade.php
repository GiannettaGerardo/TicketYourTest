<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Richieste </title>

    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="{{ URL::asset('/css/stile.css') }}">

    <script src="{{ URL::asset('/script/script.js') }}"></script>

    <style>
        .validation {
            border: 1px solid;
            margin: 10px 0px;
            padding: 15px 10px 15px 10px;
            color: #D63301;
            background-color: #FFCCBA;
        }

    </style>

</head>

<body style="overflow-x: hidden; align-items: center;" class="columnP">

    <x-header.header />


    <h1>Richiesta convenzioni</h1>

    @if (count($laboratori) == 0)

        <x-succes-msg>non ci sono richieste</x-succes-msg>

    @endif

    @foreach ($laboratori as $laboratorio)

        <x-richiesta-lab.container-laboratori :laboratorio="$laboratorio" :id='$laboratorio["id"]' />

    @endforeach

    @error('coordinata_x')

        <script>
            showCoordinatesError("{{ $laboratorio['id'] }}", "{{ $message }}")
        </script>-->
        <div class="validation">{{ $message }}</div>

    @enderror

    @error('coordinata_y')

        <script>
            showCoordinatesError("{{ $laboratorio['id'] }}", "{{ $message }}")
        </script>-->
        <div class="validation">{{ $message }}</div>

    @enderror

    @if (Session::has('coordinate-errate'))
        <div class="validation">{{ Session::get('coordinate-errate') }}</div>
    @endif

</body>

</html>
