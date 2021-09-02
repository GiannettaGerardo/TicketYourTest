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

</head>

<body>

    <x-header.header />


    <div class="columnP mapContainer">
        <div id="map">

        </div>
    </div>

    <script defer>

        //inizializzo la mappa per visualizzare tutta l'italia
        var map = L.map('map').setView([42.37562576998477, 13.406502452703046], 6.5);

        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoiZmxhdmlvMDEiLCJhIjoiY2t0MzYwajh1MHF3NjJvczI2a29rYzB2biJ9.2U0ge4nZLC3t_xLsJSqwOg', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoiZmxhdmlvMDEiLCJhIjoiY2t0MzYwajh1MHF3NjJvczI2a29rYzB2biJ9.2U0ge4nZLC3t_xLsJSqwOg'
        }).addTo(map);


        let listaLaboratori = <?php echo $laboratori ?>;

        for (let laboratorio of listaLaboratori) { //per ogni laboratorio convenzionato

            //aggiungo il marker sulla mappa
            var marker = L.marker([laboratorio.coordinata_x, laboratorio.coordinata_y]).addTo(map);

            //aggiungo la descrizione al popup del relativo marker
            let infoLab = `<h5>${laboratorio.nome}</h5></br>`;
            marker.bindPopup(infoLab).openPopup();
        }

        map.flyTo([42.37562576998477, 13.406502452703046],7);

        //rilevo la posizione dell'utente
        map.locate({
            setView: true,
            maxZoom: 16
        });

        map.on('locationfound', onLocationFound);//se rilevo la posizione

        map.on('locationerror', onLocationError);//errore di rilevazione posizione

        function onLocationFound(e) {
            var radius = e.accuracy;

            L.marker(e.latlng).addTo(map)
                .bindPopup("Tu sei qui").openPopup();

            L.circle(e.latlng, radius).addTo(map);
        }


        function onLocationError(e) {
            alert("Imposibile rilevare posizione\nPer cui verranno mostrati tutti i laboratori italiani");
        }

        
    </script>

</body>

</html>