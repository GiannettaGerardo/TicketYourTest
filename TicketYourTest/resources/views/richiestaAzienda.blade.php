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

  <form class="col-md-6 col-lg-6 col-11 mx-auto my-auto search-box">

    <div class="input-group form-container">

      <input type="text" name="search" class="form-control search-input" placeholder="Nome azienda" autofocus="autofocus" autocomplete="off">

      <span class="input-group-btn">

        <button type="button" class="btn btn-success">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"></path>
          </svg>
          Search
        </button>

      </span>

    </div>

  </form>

</div>
</body>

</html>