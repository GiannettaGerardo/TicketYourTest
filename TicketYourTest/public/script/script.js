/**
 * funzione per prendere i nuovi eventuali dati modificati sulla pagina profilo
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
 */
function sendDataProfilePage(data, url, csrfToken) {

    var formData = new FormData();

    formData.append("_token", csrfToken);

    for (key in data) { //insersco i dati da mandare al server nel form "virtuale"

        if (key == "codice_fiscale") { //poiche per l'update servono due input codice fiscale con nome diversi

            formData.append("cf", data[key]);
            formData.append("cf_attuale", data[key]);

        } else if (key == "password") { //poiche come il controller vuole la chiava password rinominato con psw

            formData.append("psw", data[key]);

        } else if (key == "partita_iva") { //poiche come il controller vuole la chiava partita_iva rinominata con iva

            formData.append("iva", data[key]);

        } else {

            formData.append(key, data[key])
        }

    }

    //send data
    var request = new XMLHttpRequest();
    request.open("POST", url);

    request.setRequestHeader("X-CSRF-TOKEN", csrfToken);

    request.onreadystatechange = function () {

        console.log("ready state " + request.readyState);
        console.log("status  " + request.status);

        if (request.readyState === XMLHttpRequest.DONE) {

            var status = request.status;

            if (status === 0 || (status >= 200 && status < 400)) { //richiesta avvenuta con successo

                console.log("responseStatus  " + request.status + " " + request.statusText);

                console.log("response text " + request.response);

                console.log("responseType" + request.responseType);

            } else {
                console.log("responseStatus  " + request.status + " " + request.statusText);
            }
        }
    };

    request.send(formData);

}


/**
 * metodo per visualzzare un errore in caso di mancate cordinate alla conferma di convenzionamento di un laboratorio da parte di dell'amministratore
 * @param {} idContainer id del container nel quale fa visualzzare il messaggio
 * @param {*} msg il messaggio di errore da far visualizzare
 */
function showCoordinatesError(idContainer, msg) {

    let body = document.getElementsByTagName("body");

    //creao la componente per visualizzare l'errore
    let errMsgComponent = document.createElement("div");
    errMsgComponent.classList.add("alert");
    errMsgComponent.classList.add("alert-danger");

    //assegno il testo da far visualizzare alla componente
    errMsgComponent.innerText = msg;

    body[0].insertBefore(errMsgComponent,body[0].childNodes[8]);

}
