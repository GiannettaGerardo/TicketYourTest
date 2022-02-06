<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ URL::asset('/css/stile.css') }}">

    <style>
        .color-red {
            color: red;
        }
        .dim {
            font-size: 20px;
            font-family: "Lato", sans-serif;
        }
    </style>
  <title>Richiesta</title>
</head>

<x-header.header />


<div class="container columnP">
  <div class="searchCompanyCover">

  </div>

  <form class="col-md-6 col-lg-6 col-11 mx-auto my-auto search-box" action="{{route('richiedi.inserimento.lista')}}" method="POST">
    @csrf

      <p class="dim">
          <span class="color-red">Attenzione:</span>
          il nome dell'azienda per la ricerca deve essere inserito esattamente così come è stato inserito dal
          datore di lavoro in fase di registrazione. Se anche un solo spazio o una sola virgola saranno diversi,
          l'azienda non sarà trovata e un messaggio di errore apparirà.
      </p>
      </br>
    <div class="input-group form-container">

      <input type="text" name="nomeAzienda" class="form-control search-input" placeholder="Nome azienda" autofocus="autofocus" autocomplete="off">

      <span class="input-group-btn">

        <button type="submit" class="btn btn-success">
          Richiedi
        </button>

      </span>

    </div>
    <!-- Errori di successo e di insuccesso -->
    @if (Session::has('nome-azienda-errato'))
        <x-err-msg>{{ Session::get('nome-azienda-errato') }}</x-err-msg>
    @endif

    @if (Session::has('richiesta-avvenuta'))
        <x-succes-msg>{{ Session::get('richiesta-avvenuta') }}</x-succes-msg>
    @endif

    @if (Session::has('inserimento-gia-effettuato'))
        <x-err-msg>{{ Session::get('inserimento-gia-effettuato') }}</x-err-msg>
    @endif





  </form>

</div>
</body>

</html>
