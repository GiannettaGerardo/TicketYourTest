<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Aggiungi Dipendente </title>

    <!-- Foglio di stile -->
    <link rel="stylesheet" href="{{ URL::asset('/css/stile.css') }}">
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

</head>

<body>

    <!-- Navbar -->
    <x-header.header />
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="aggiungi-form">
                    <form action="#" class="mt-5 p-4 bg-light border" method="POST">
                        @csrf
                        <h3 class="mb-4">
                            Laboratorio Bonsanto
                            <small class="text-muted">Prenotazione tampone</small>
                          </h3>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label>Nome:</label>
                                <input class="form-control" id="disabledInput" type="text" placeholder="Fabio" disabled>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Cognome:</label>
                                <input class="form-control" id="disabledInput" type="text" placeholder="Bonsanto" disabled>
                            </div>

                            <div class="mb-3 col-md-12">
                                <label>Codice Fiscale:</label>
                                <input class="form-control" id="disabledInput" type="text" placeholder="BNSFBA98L19I158N" disabled>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>E-mail:</label>
                                <input type="text" name="email" class="form-control"  placeholder="fabio-bonsanto@gmail.com" disabled>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Cellulare:</label>
                                <input type="text" name="Cellulare" class="form-control"  placeholder="Cellulare">
                            </div>
                            <div class="mb-3 col-md-12">
                                <label>Tampone:</label>
                                <select class="form-control">
                                    <option selected disabled>Scegli tampone... </option>
                                    <option>Tampone molecolare</option>
                                    <option>Tampone rapido</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-12">
                            <button type="submit" class="btn btn-success btn-lg btn-block">Conferma prenotazione</button>
                            </div>
                        </div>
                    </form>
                        
                </div>
            </div>
        </div>
    </div>



</body>

</html>