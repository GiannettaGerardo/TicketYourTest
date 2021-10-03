<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.6.0/mdb.min.css" rel="stylesheet" />
    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.6.0/mdb.min.js"></script>

    <link rel="stylesheet" href="{{ URL::asset('/css/stile.css') }}">

    <script src="{{ URL::asset('/script/script.js') }}"></script>

    <meta name="csrf-token" content="{{csrf_token()}}">

</head>

<body>

    <x-header.header />

    @if (!property_exists($utente,'partita_iva'))

    <x-dashboard-profilo.dashboard-cittadino :cittadinoPrivato="$utente" />

    @elseif (property_exists($utente,'partita_iva') && property_exists($utente,'citta_sede_aziendale'))

    <x-dashboard-profilo.dashboard-datore :datoreLavoro="$utente" />

    @else

    <x-dashboard-profilo.dashboard-medico :medico="$utente" />

    @endif

    <div class="alert alert-danger modificheProfiloInvalide hiddenDisplay"></div>

    <script defer>
        const editButton = document.getElementById("editProfileButton");
        const confirmEditButton = document.getElementById("confirmEditButton");

        //rilevo il click sull'icona di modifica
        editButton.addEventListener("click", () => {

            var editableField = document.getElementsByClassName("editableField");

            for (field of editableField) {

                //rendo editabili i campi
                field.setAttribute("contenteditable", "true");
                field.classList.add("editMode")

                //aggiungo il pulsante di conferma e nascondo l'icona di modifica
                confirmEditButton.classList.remove("hiddenDisplay");
                editButton.classList.add("hiddenDisplay");
            }

        });

        //rilevo il click sull'icona di conferma
        confirmEditButton.addEventListener("click", () => {

            var editableField = document.getElementsByClassName("editableField");

            for (field of editableField) {

                //rendo non editabili i campi
                field.setAttribute("contenteditable", "false");
                field.classList.remove("editMode")

                //aggiungo il pulsante di modifica e nascondo il pulsante di conferma
                confirmEditButton.classList.add("hiddenDisplay");
                editButton.classList.remove("hiddenDisplay");
            }

            //converto i dati dell'utente in oggetto trattabilie con js
            var data = '<?php echo json_encode($utente) ?>';
            data = JSON.parse(data);

            getDataProfilePage(data); //leggo i nuovi eventuali valori dei campi modificati

            sendDataProfilePage(data, "{{ route('modifica.profilo') }}", "{{csrf_token()}}");//invio i dati e gestico la risposta


        });
    </script>
</body>

</html>