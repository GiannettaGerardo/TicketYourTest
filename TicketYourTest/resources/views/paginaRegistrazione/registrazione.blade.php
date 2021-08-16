<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

    <link rel="stylesheet" href="css/stile.css">


</head>

<body>

    <x-header.header/>

    <section id="sectionRegistrazione">

        <div id="coverForm">
        </div>

        <div id="form">

            <div id="headerForm" class="columnP">

                <h3>Registrati</h3>

                <h6>{{$categoriaUtente}}</h6>

            </div>


            @switch($categoriaUtente)

                @case('Cittadino privato')

                    @include("paginaRegistrazione.formsRegistrazione.formCittadinoPrivato")

                    @break

                @case('Datore di lavoro')

                    @include("paginaRegistrazione.formsRegistrazione.formDatoreLavoro")

                    @break

                @case('Medico curante')

                    @include("paginaRegistrazione.formsRegistrazione.formMedicoCurante")

                    @break

                @case('Laboratorio analisi')

                    @include("paginaRegistrazione.formsRegistrazione.formLaboratorioAnalisi")

                    @break

            @default

            @endswitch

        </div>

        <script>
            function showErrorMSG(){
                document.getElementsByClassName(formErrorMessage).classList.remove("hiddenDisplay");
            }
        </script>

    </section>

</body>

</html>