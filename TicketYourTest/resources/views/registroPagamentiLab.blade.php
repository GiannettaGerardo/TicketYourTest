<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Registro Pagamenti</title>

        <!-- Foglio di stile -->
        <link rel="stylesheet" href="{{ URL::asset('/css/stile.css') }}">

        <!-- CDN Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-W8fXfP3gkOKtndU4JGtKDvXbO53Wy8SZCQHczT5FMiiqmQfUpWbYdTil/SxwZgAN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script>

    </head>
    <body>
        <!-- Navbar -->
        <x-header.header />

        <div class="container-fluid">

            <h1 class="h1">
                Registro Pagamenti
            </h1>

            @if( $listaUtentiPagamentoInContantiNonEffettuato->isEmpty() && $listaUtentiPagamentoInContantiEffettuato->isEmpty() ) 
                    <x-succes-msg>Al momento, non esistono transazioni effettuate presso questa struttura. </x-succes-msg>
            @endif
            
            @if( !$listaUtentiPagamentoInContantiNonEffettuato->isEmpty() ) 
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col"> Codice Fiscale </th>
                                <th scope="col"> E-mail </th>
                                <th scope="col"> Data </th>
                                <th scope="col"> Test </th>
                                <th scope="col"> Prezzo </th>
                                <th scope="col"> # </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listaUtentiPagamentoInContantiNonEffettuato as $registroPagamenti) 
                                <x-registro-lab-pagamenti.container-lista-pagamenti-lab :registroPagamenti="$registroPagamenti" :flagBottone="true" />
                            @endforeach
                        </tbody>
                    </table>
                    <hr class="my-4">
            @endif
            
                
            @if( !$listaUtentiPagamentoInContantiEffettuato->isEmpty() )
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col"> Codice Fiscale </th>
                                <th scope="col"> E-mail </th>
                                <th scope="col"> Data </th>
                                <th scope="col"> Test </th>
                                <th scope="col"> Prezzo </th>
                                <th scope="col"> # </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listaUtentiPagamentoInContantiEffettuato as $registroPagamenti) 
                                <x-registro-lab-pagamenti.container-lista-pagamenti-lab :registroPagamenti="$registroPagamenti" :flagBottone="false" />
                            @endforeach
                        </tbody>
                    </table>
            @endif

            

        </div>
    </body>
</html>