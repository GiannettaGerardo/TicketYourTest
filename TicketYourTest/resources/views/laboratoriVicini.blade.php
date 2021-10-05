<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratori vicini</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

    <link rel="stylesheet" href="{{ URL::asset('/css/stile.css') }}">

    <script src="{{ URL::asset('/script/script.js') }}"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="firstDateAvaibleUrl" content="{{ route('primo.giorno.disponibile') }}">

</head>

<body>

    <x-header.header />

    <h2 class="titlePageLaboratoriVicini">Laboratori nell vicinanze</h2>
    <h6 class="titleDescriptionLaboratoriVicini">Cliccare sul nome del laboratorio d'interesse per procedere alla prenotazione</h6>

    <div class="columnP mapContainer positionRelative">

        <div class="container localizzazioneFallitaAlertContainer columnP hiddenDisplay">
            <x-err-msg>Posizione non rilevata</br>Vi verranno mostrati tutti i laboratori convenzionati al servizio</x-err-msg><br>
        </div>

        <div id="map" class="positionRelative">

        </div>

        <section class="infoPanel hiddenDisplay positionRelative columnP" id="infoPanel">
            
        </section>
    </div>



    <script defer>
        <?php //coverto i dati in dati trattabili in javascript 
        ?>
        let listaLaboratori = <?php echo $laboratori ?>;
        let tamponiProposti = <?php echo json_encode($tamponi_proposti) ?>;

        let map = mapInit(); //inizializzo la mappa per visualizzare tutta l'italia

        loadAllLab(map, listaLaboratori, tamponiProposti) //faccio visualizzare i marker per ogni laboratorio

        locate(map); //geolocalizzo l'utente
    </script>

</body>

</html>