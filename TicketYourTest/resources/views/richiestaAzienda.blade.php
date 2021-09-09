<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ URL::asset('/css/stile.css') }}">
  <title>Richiesta</title>
</head>

<x-header.header />


<div class="container columnP">
  <div class="searchCompanyCover">

  </div>

  <form class="col-md-6 col-lg-6 col-11 mx-auto my-auto search-box" action="{{route('richiedi.inserimento.lista')}}" method="POST">
    @csrf
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
      
    

    

  </form>

</div>
</body>

</html>