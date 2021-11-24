<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Checkout</title>
  </head>
  <body class="bg-light" style="overflow-x: hidden">
    <!--Navbar del sito -->
    <x-header.header />

    <div class="container">
        <div class="py-5 text-center">
            <img class="mb-2 d-block mx-auto" src="images/logo.png" alt="ticketYourTestLogo" width="165" height="80">
            <h2>Checkout form</h2>
            <p><small>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Enim soluta quasi rerum, in ea harum ut nihil.</small></p>
        </div>
    </div>
    
    <div class="container w-50 mt-0">
        <h4>Indirizzo di fatturazione:</h4>
        <form action="#" method="POST">
            <div class="row g-3">
                <div class="col-sm-6">
                    <label for="firstName" class="form-label"> Nome: </label>
                    <input id="firstName" type="text" class="form-control" placeholder="Nome">
                </div>
                <div class="col-sm-6">
                    <label for="LastName" class="form-label"> Cognome: </label>
                    <input id="LastName" type="text" class="form-control" placeholder="Cognome">
                </div>
                <div class="col-12">
                    <label for="indirizzoFatturazione" class="form-label"> Indirizzo: </label>
                    <input id="indirizzoFatturazione" type="text" class="form-control" placeholder="Via/Viale Rossi, 19">
                </div>
                <div class="col-md-4">
                    <label for="paese" class="form-label">Paese: </label>
                    <input id="paese" type="text" class="form-control" placeholder="Paese">
                </div>
                <div class="col-md-4">
                    <label for="citta" class="form-label">Città: </label>
                    <input id="citta" type="text" class="form-control" placeholder="Città">
                </div>
                <div class="col-md-4">
                    <label for="codice_postale" class="form-label">CAP: </label>
                    <input id="codice_postale" type="text" class="form-control" placeholder="CAP">
                </div>
            </div>
            <hr class="my-4">

            <h4 class="mb-3">Pagamento</h4>
           <div class="form-check">
               <input id="creditCard" name="metodo_di_pagamento" type="radio" class="form-check-input">
               <label for="creditCard">Carta di credito</label>
           </div>
           <div class="form-check">
                <input id="contanti" name="metodo_di_pagamento" type="radio" class="form-check-input">
                <label for="contanti">Presso la struttura</label>
            </div>
            <div class="row my-3 gy-3">
                <div class="col-md-6">
                    Nome sulla carta:
                    <input type="text" class="form-control" placeholder="Nome completo">
                </div>
                <div class="col-md-6">
                    Numero della carta:
                    <input type="text" class="form-control" placeholder="Numero della carta">
                </div>
            </div>
           <div class="row">
                <div class="col-md-4">
                    Mese: 
                    <input type="text" class="form-control" placeholder="MM">
                </div>
                <div class="col-md-4">
                    Anno: 
                    <input type="text" class="form-control" placeholder="YY">
                    <small class="text-muted">Inserire solo le ultime due cifre dell'anno di scadenza </small>
                </div>
                <div class="col-md-4">
                    CVV:
                    <input type="text" class="form-control" placeholder="NNN">
                    <small class="text-muted">Il codice di sicurezza è visibile nel retro della carta </small>
           </div>
           <hr class="my-4">
           <button type="submit" class="btn btn-success btn-lg btn-block mb-5">Conferma pagamento </button>
        </form>
    </div>
  </body>
</html>