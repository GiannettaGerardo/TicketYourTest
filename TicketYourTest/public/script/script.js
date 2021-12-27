/*******************************************************************************
 * fuunzioni relative all'aquisitizione e invio di dati del profilo modificato *
 *******************************************************************************/
/**
 * funzione per prendere i nuovi eventuali dati modificati sulla pagina profilo
 * e assegnarli all'utente
 * @param data l'oggetto utente di cui aggionare i dati
 */
function getDataProfilePage(data) {

    data["email"] = document.querySelector("#emailField").textContent; //prendo l'eventuale nuova email

    data["citta_residenza"] = document.querySelector("#cittaResidenzaField").textContent; //prendo l'eventuale nuova citta di esidenza

    data["provincia_residenza"] = document.querySelector("#provinciaResidenzaField").textContent; //prendo l'eventuale nuova provincia di esidenza

    if (document.querySelector("#partitaIvaField")) { //se esiste il campo partitaIva potrebbe essere medico o datore

        data["partita_iva"] = document.querySelector("#partitaIvaField").textContent; //prendo l'eventuale nuova partita iva

        if (document.querySelector("#nomeAziendaField")) { //se esiste anche il campo nomaAzienda allora è un datore

            data["citta_sede_aziendale"] = document.querySelector("#cittaSedeAziendaleField").textContent; //prendo l'eventuale nuova citta per la sede aziendale

            data["provincia_sede_aziendale"] = document.querySelector("#provinciaSedeAziendaleField").textContent; //prendo l'eventuale nuova provincia per la sede aziendale

            data["nome_azienda"] = document.querySelector("#nomeAziendaField").textContent; //prendo l'eventuale nuova nomeAzienda

        }
    }
}


/**
 * funzione per inviare al server i nuovi dati modificate dall'utente
 * @param {*} data dati da inviare
 * @param url pagina a cui inviare i dati
 * @param csrfToken token csrf di sessione
 * @return false se si verificano errori di convalida, true altrimenti
 */
async function sendDataProfilePage(data, url, csrfToken) {

    var formData = new FormData();

    formData.append("_token", csrfToken);

    for (key in data) { //insersco i dati da mandare al server nel form "virtuale"

        if (key == "codice_fiscale") { //poiche per l'update servono due input codice fiscale con nome diversi

            formData.append("cf", data[key]);
            formData.append("cf_attuale", data[key]);

        } else if (key == "password") { //poiche come il controller vuole la chiava password rinominato con psw

            formData.append("psw", data[key]);

        } else if (key == "partita_iva") { //poiche come il controller vuole la chiave partita_iva rinominata con iva

            formData.append("iva", data[key]);

        } else {

            formData.append(key, data[key])
        }

    }


    let errorComponent = document.getElementsByClassName("modificheProfiloInvalide");

    let response = await fetch(url, { //effettuo la richiesta
        headers: {
            "Accept": "application/json, text-plain, */*",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": csrfToken
        },
        method: 'post',
        body: formData
    });


    let responseObject;

    if (response.status == 422) { //ho ricevuto un errore di convalida

        responseObject = await response.text(); //converto la risposta in un formato leggibile
    }

    if (responseObject != undefined) {

        //visualizzo la componente di errore
        errorComponent[0].classList.remove("hiddenDisplay");
        errorComponent[0].textContent = responseObject;

        let timeout = await resolvePromise(3500);

        //nascondo la componente di errore
        errorComponent[0].classList.add("hiddenDisplay");

        return false;

    }

    return true;

}


async function resolvePromise(timeForResolve) {

    return new Promise((resolve, reject) => {

        setTimeout(resolve, timeForResolve);
    })
}



/********************************************************************************
 * fuunzioni relative al convenzionamento di un laboratorio da parte dell'admin *
 ********************************************************************************/

/**
 * metodo per visualzzare un errore in caso di mancate cordinate alla conferma di convenzionamento di un laboratorio da parte di dell'amministratore
 * 
 * @param {*} msg il messaggio di errore da far visualizzare
 */
function showCoordinatesError(msg) {

    let body = document.getElementsByTagName("body");

    //creao la componente per visualizzare l'errore
    let errMsgComponent = document.createElement("div");
    errMsgComponent.classList.add("alert");
    errMsgComponent.classList.add("alert-danger");

    //assegno il testo da far visualizzare alla componente
    errMsgComponent.innerText = msg;

    body[0].insertBefore(errMsgComponent, body[0].childNodes[8]);

}



/*************************************************************************************
 * funzioni relative alla visualizzazione e alla modifica dei dati di un laboratorio *
 *************************************************************************************/

/**
 * funzione per settare i valori dei tamponi da visalizzare sul form di modifica del profilo di un laboratorio
 * @param {*} listaTamponiOfferti la lista dei tamponi che offre un dato laboratorio
 */
function setValueCheckBoxTamponiOfferti(listaTamponiOfferti) {


    //seleziono gli input relativi ai tamponi rapidi
    let checkBoxInputTamponeRapido = document.querySelector("#checkBoxTamponeRapido");
    let costoInputTamponeRapido = document.querySelector("#costoTamponeRapido");

    //seleziono gli input relativi ai tamponi molecolari
    let checkBoxInputTamponeMolecolare = document.querySelector("#checkBoxTamponeMolecolare");
    let costoInputTamponeMolecolare = document.querySelector("#costoTamponeMolecolare");

    for (let tampone of listaTamponiOfferti) { //finche ho tamponi da visualizzare

        if (tampone.id_tampone == 1) { //il laboratorio effettua tamponi rapidi

            //aggiorno i relativi input
            checkBoxInputTamponeRapido.checked = true;
            costoInputTamponeRapido.value = tampone.costo;
        }

        if (tampone.id_tampone == 2) { //il laboratorio effettua tamponi molecolari

            //aggiorno i relativi input
            checkBoxInputTamponeMolecolare.checked = true;
            costoInputTamponeMolecolare.value = tampone.costo;
        }
    }
}

/**
 * funzione per settare i valori del calendario disponibilita da visalizzare sul form di modifica del profilo di un laboratorio
 * @param {*} calendarioDisponibilita il calendario disponibilita del laboratorio
 */
function setValueInputCalendarioDisponibilita(calendarioDisponibilita, capienzaLaboratorio) {

    for (let day of calendarioDisponibilita) {

        //credo dinamicamente l'id di ogni input per gli orario di chiusura e apertura dei risepttivi giorni
        let idInputOraApertura = "#oraApertura" + day.giorno_settimana;
        let idInputOraChiusura = "#oraChiusura" + day.giorno_settimana;

        //seleziono i relativi input per gli id creati
        let inputOraApertura = document.querySelector(idInputOraApertura);
        let inputOraChiusura = document.querySelector(idInputOraChiusura);

        //aggiorno il valori dei relativi input
        inputOraApertura.value = day.oraApertura;
        inputOraChiusura.value = day.oraChiusura;
    }

    let inputCapienzaGiornaliera = document.querySelector("#capienzaGiornaliera");
    inputCapienzaGiornaliera.value = capienzaLaboratorio;
}


/**
 * funzione per rendere editabile o meno l'input relativo al costo di un dato tampone solo se questo è selezionato o meno
 */
function setEnableCostoTampone() {

    //seleziono gli input relativi ai tamponi rapidi
    let checkBoxTamponeRapido = document.getElementById("checkBoxTamponeRapido");
    let inputCostoTamponeRapido = document.getElementById("costoTamponeRapido");

    //seleziono gli input relativi ai tamponi molecolari
    let checkBoxTamponeMolecolare = document.getElementById("checkBoxTamponeMolecolare");
    let inputCostoTamponeMolecolare = document.getElementById("costoTamponeMolecolare");


    //setto il valore di base di enable relativo al costo del tampone rapido
    if (checkBoxTamponeRapido.checked == true) {

        inputCostoTamponeRapido.disabled = false;

    } else if (checkBoxTamponeRapido.checked == false) {

        inputCostoTamponeRapido.disabled = true;

    }

    //setto il valore di base di enable relativo al costo del tampone molecolare
    if (checkBoxTamponeMolecolare.checked == true) {

        inputCostoTamponeMolecolare.disabled = false;

    } else if (checkBoxTamponeMolecolare.checked == false) {

        inputCostoTamponeMolecolare.disabled = true;

    }


    //se selezione o deseleziono la checkbox relativa al tampone rapido
    checkBoxTamponeRapido.addEventListener("change", () => {

        if (checkBoxTamponeRapido.checked) {

            inputCostoTamponeRapido.disabled = false;

        } else {

            inputCostoTamponeRapido.disabled = true;
            inputCostoTamponeRapido.value = null;

        }

    });

    //se selezione o deseleziono la checkbox relativa al tampone molecolare
    checkBoxTamponeMolecolare.addEventListener("change", () => {

        if (checkBoxTamponeMolecolare.checked) {

            inputCostoTamponeMolecolare.disabled = false;

        } else {

            inputCostoTamponeMolecolare.disabled = true;
            inputCostoTamponeMolecolare.value = null;

        }

    });

}



/***********************************************************************************************
 * funzioni relative alla geolocalizzazione e visualizzazione dei laboratori vicini all'utente *
 ***********************************************************************************************/
/**
 * funzione per creare e inizilizizzare la mappa su cui vedere i laboratori vicini all'utente
 * @returns la mappa creata
 */
function mapInit() {

    var map = L.map('map').setView([42.26027044258784, 12.860928315671886], 6.5);

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoiZmxhdmlvMDEiLCJhIjoiY2t0MzYwajh1MHF3NjJvczI2a29rYzB2biJ9.2U0ge4nZLC3t_xLsJSqwOg', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1IjoiZmxhdmlvMDEiLCJhIjoiY2t0MzYwajh1MHF3NjJvczI2a29rYzB2biJ9.2U0ge4nZLC3t_xLsJSqwOg'
    }).addTo(map);

    if (window.screen.width <= 768) //schermi di piccoli dimensioni

        map.flyTo([42.26027044258784, 12.860928315671886], 5.7);

    else

        map.flyTo([42.26027044258784, 12.860928315671886], 6.5);

    return map;
}

/**
 * funzione per visualizare i laboratorii di tutta italia se la geolocalizzazione fallisce
 * @param {*} map mappa su cui visualizzare i laboratori
 * @param {*} listaLaboratori i laboratori da visualizzare
 * @param {*} listaTamponiOfferti i taponi offeti dai rispettivi laboratori
 */
function loadAllLab(map, listaLaboratori, tamponiProposti) {

    let markerPersonaID;

    let laboratorio;

    for (laboratorio of listaLaboratori) { //per ogni laboratorio convenzionato

        //aggiungo il marker sulla mappa
        let marker = L.marker([laboratorio.coordinata_x, laboratorio.coordinata_y]).addTo(map);

        //aggiungo il nome del laboratorio al testo del popup del relativo marker
        let infoLab = `<p  class="markerLabName" id='${laboratorio.id}'"><b onClick="redirectToPrenotationForm(${laboratorio.id})">${laboratorio.nome}</b></p>`;

        marker.bindPopup(infoLab).openPopup(); //aggiungo il popup col nome del lab al marker del relativo laboratorio

        //aggiungo, con relativo costo, i tamponi offerti da un dato laboratorio
        for (let i in tamponiProposti) {

            if (i == laboratorio.id) {

                for (let tamponePerLab of tamponiProposti[i]) {

                    if (i == laboratorio.id) {
                        if (tamponePerLab.id_tampone == 1)

                            infoLab += `</br><span style="margin: 0;">Tampone rapido: ${tamponePerLab.costo} $</span>`;

                        if (tamponePerLab.id_tampone == 2)

                            infoLab += `</br><span style="margin: 0;">Tampone molecolare: ${tamponePerLab.costo} $</span>`;
                    }

                }

            }
        }


        markerPersonaID = `markerLab${laboratorio.id}`

        marker.id = markerPersonaID; //aggiungo un id personalizzato al merker per distinguerlo dagli altri
        marker.infoLab = infoLab; // aggiungo al marker le informazione relative al laboraotiorio a lui correlato
        marker.infoLabComplete = false; //aggiungo al marker un flag per segnalare che le info relative al laboratorio a lui correlato sono incomplete
        marker.idLab = laboratorio.id; //aggiungo al marker un campo che indica l'id del laboratorio a lui correlato

        marker.on("click", markerClickEvent(marker));
    }

}


/**
 * funzione per implementare le azione da svolgere al click su un dato marker
 * @param {} clickedMarker il marker cliccato
 * @returns 
 */
function markerClickEvent(clickedMarker) {

    return function () {

        if (clickedMarker.infoLabComplete == false) { //se per il dato laboratorio non ho ancora mai recuperato la prima data disponibile

            //richiedo la prima data disponibile per il laboratorio scelto attraverso il marker
            getLabAvailability(clickedMarker.idLab).then(disponibilitaLab => {

                clickedMarker.infoLab += "</br><span style='margin: 0;'>Prima data disponibile per effettuare un tampone: " + disponibilitaLab + "</span>";
                clickedMarker.infoLabComplete = true; //per il dato marker segnalo che la prima data disponbile è gia stata recuperata altre volte

                showInfoPanel(clickedMarker.infoLab); //mostro il pannello con le info

            }).catch(e => {
                console.log(e)
            });

        } else {

            showInfoPanel(clickedMarker.infoLab);
        }

    }

}

/**
 * funzione per mostare il pannello su cui visualizzare le info del laboratorio selezionato attraverso il marker
 * @param {*} info le info da visualizzare relative al laboratroio selezionato
 */
function showInfoPanel(info) {

    let infoPanel = document.querySelector("#infoPanel");

    infoPanel.innerHTML = info + "<i class='fas fa-times closeInfoLabPanelIcon' id='closeInfoLabPanelIcon'></i>";

    if (infoPanel.classList.contains("hiddenDisplay"))

        infoPanel.classList.remove("hiddenDisplay");

    document.querySelector("#closeInfoLabPanelIcon").addEventListener("click", () => {
        infoPanel.classList.add("hiddenDisplay");
    })
}

/**
 * funzione per richiedere al server quale sia la prima data disponibile per effetuale un tampone presso un dato laboratorio
 * @param {*} idLab laboratorio di cui si vuole sapere la disponibilita
 * @returns promise contente la data in cui il laboratorio è disponibile
 */
async function getLabAvailability(idLab) {

    //prendo il token di sessione    
    let csrfToken = document.querySelector('meta[name="csrf-token"]');
    csrfToken = csrfToken.content;

    //prendo l'url a cui inviare la richiesta
    let url = document.querySelector('meta[name="firstDateAvaibleUrl"]');
    url = url.content;

    //creo il contenuto da inviare nella richiesta
    let bodyContent = {
        "idLab": idLab
    }
    bodyContent = JSON.stringify(bodyContent);

    let response = await fetch(url, { //effettuo la richiesta
        headers: {
            "Accept": "application/json, text-plain",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json"
        },
        method: "post",
        body: bodyContent
    });

    response = await response.text();

    return new Promise((resolve, reject) => {
        resolve(response);
    });


}


/**
 * funzione per geolocalizzare l'utene quindi mostrare i soli laboratori vicini
 * @param {*} map mappa su cui localizzare
 */
function locate(map) {

    //rilevo la posizione dell'utente
    map.locate({
        setView: true,
        maxZoom: 16
    });

    map.on('locationfound', (e) => {
        onLocationFound(e, map)
    }); //se rilevo la posizione

    map.on('locationerror', (e) => {
        onLocationError(e);
    }); //errore di rilevazione posizione
}

/**
 * funzione per descrivere il comportamento della mappa se la posizione viene rilevata
 * @param {} e 
 */
function onLocationFound(e, map) {

    var radius = e.accuracy + 15000;

    L.marker(e.latlng).addTo(map)
        .bindPopup("Tu sei qui").openPopup();

    let circle = L.circle(e.latlng, radius).addTo(map);


    if (window.screen.width <= 768) //schermi di piccoli dimensioni

        map.flyTo(e.latlng, 10);

    else

        map.flyTo(e.latlng, 12);


    //setto l'area oltre la quale l'utente non puo andare
    var corner1 = L.latLng(e.latlng.lat - 0.14, e.latlng.lng - 0.18),
        corner2 = L.latLng(e.latlng.lat + 0.14, e.latlng.lng + 0.18),
        bounds = L.latLngBounds(corner1, corner2);
    map.setMaxBounds(bounds);

    map.eachLayer(function (layer) { //per ogni layer sulla mappa

        if (layer instanceof L.Marker) { //se il layer è un marcatore

            let distance = map.distance(layer.getLatLng(), circle.getLatLng()) //calcolo la distanza dal centro della zona evidenziata

            if (distance > circle.getRadius()) { //il marker risulta al di fuori della zona evidenziata

                layer.remove(); //rimuovo il marker poiche non interessa la zona evidenziata

            }
        }

    });
}

/**
 * funzione per descrivere il comportamento della mappa se la posizione non viene rilevata
 * @param {} e 
 */
function onLocationError(e) {

    //alert("Imposibile rilevare posizione\nPer cui verranno mostrati tutti i laboratori italiani");
    showAlertContainer("localizzazioneFallitaAlertContainer");
    hiddenAlertContainer("localizzazioneFallitaAlertContainer", 3500);

    if (window.screen.width <= 768) //schermi di piccoli dimensioni

        map.flyTo([42.26027044258784, 12.860928315671886], 5.7);

    else

        map.flyTo([42.26027044258784, 12.860928315671886], 6.5);

}


/***********************************************************************************************
 * funzioni relative alla geolocalizzazione e visualizzazione dei laboratori vicini all'utente *
 ***********************************************************************************************/
function redirectToPrenotationForm(idLab) {


    let prenotationType = window.location.href;


    //ricavo dall'url il tipo di prenotazione richiesta dall'utente
    prenotationType = prenotationType.split("/");

    if (prenotationType.indexOf("prenotaPerSe") != -1)

        prenotationType = prenotationType[prenotationType.indexOf("prenotaPerSe")];

    else
        prenotationType = prenotationType[prenotationType.indexOf("prenotaPerTerzi")];

    let url;
    let data = {
        "id_lab": idLab
    };

    //in base alla richiesta dell'utente selezione l'url a cui rendirizzare
    if (prenotationType == "prenotaPerSe") {

        url = document.querySelector('meta[name="prenotationFormForMe"]').content;


    } else if (prenotationType == "prenotaPerTerzi") {

        url = document.querySelector('meta[name="prenotationFormForThey"]').content;

    } else { //prenotazione per dipendenti

        url = document.querySelector('meta[name="prenotationFormForEmployees"]').content;

    }

    fetch(url + "?id_lab=" + idLab).then(response => window.location.replace(response.url)).catch(e => console.log(e));



}




/**************************************************************
 * funzioni relative all'inserimento dell'esito di un tampone *
 **************************************************************/
/**
 * permette l'invio in caso di resultato negativo
 * 
 * @param idFormInserimentoEsito id del form da cui estratte i dati da inviare
 */
function sendNegativeResult(idFormInserimentoEsito) {

    let formInserimentoEsito = document.querySelector("#" + idFormInserimentoEsito);

    //setto  il valore da inviare su negativo
    let esitoTampone = formInserimentoEsito.querySelector("#esito_tampone");
    esitoTampone.value = "negativo";


    //invio il form
    formInserimentoEsito.submit();
}


/**
 * permette l'invio in caso di resultato positivo
 * 
 * @param idFormInserimentoEsito id del form da cui estratte i dati da inviare
 */
function sendPositiveResult(idFormInserimentoEsito) {

    let formInserimentoEsito = document.querySelector("#" + idFormInserimentoEsito);

    //setto  il valore da inviare su positivo
    let esitoTampone = formInserimentoEsito.querySelector("#esito_tampone");
    esitoTampone.value = "positivo";

    console.log(idFormInserimentoEsito);

    //nascondo i bottoni e faccio comparire la casella d'input per la quantita virale e il bottone di conferma
    let inputButtons = formInserimentoEsito.querySelectorAll('input[type=button]');
    for (let button of inputButtons) {

        if(button.id !== submitButton)
            button.remove();

    }

    let quantita = formInserimentoEsito.querySelector("#quantita");
    quantita.type = "number";
    quantita.min = 1;

    formInserimentoEsito.querySelector("#submitButton").type = "submit";
}


/**
 * permette l'invio in caso di resultato indefinito
 * 
 * @param idFormInserimentoEsito id del form da cui estratte i dati da inviare
 */
function sendUndifnedResult(idFormInserimentoEsito) {

    let formInserimentoEsito = document.querySelector("#" + idFormInserimentoEsito);

    //setto  il valore da inviare su indeterminato
    let esitoTampone = formInserimentoEsito.querySelector("#esito_tampone");
    esitoTampone.value = "indeterminato";


    //invio il form
    formInserimentoEsito.submit();
}






/*********************
 * funzioni generali *
 *********************/

/**
 * funzione per nascondere dopo pochi secondi eventuali messaggi di errore o successo
 * @param className la classe css per selezione gli elementi da rimuovere
 */
function hiddenAlertContainer(className, timeToHidden) {

    setTimeout(() => {
        let alert = document.getElementsByClassName(className);

        for (let msgAlert of alert) {
            msgAlert.classList.toggle("hiddenDisplay");
        }
    }, timeToHidden);
}

/**
 * funzione per visulizzare eventuali messaggi di errore o successo
 * @param className la classe css per selezione gli elementi da rimuovere
 */
function showAlertContainer(className) {


    let alert = document.getElementsByClassName(className);

    for (let msgAlert of alert) {
        msgAlert.classList.toggle("hiddenDisplay");
    }
}



/**
 * funzione per rimuovere dopo pochi secondi eventuali messaggi di errore o successo
 * @param className la classe css per selezione gli elementi da rimuovere
 */
function removeAlertContainer(className, timeToRemove) {

    setTimeout(() => {
        let alert = document.getElementsByClassName(className);

        for (let msgAlert of alert) {
            msgAlert.remove();
        }
    }, timeToRemove);
}
