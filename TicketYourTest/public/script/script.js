/**
 * funzione per prendere i nuovi eventuali dati modificati sulla pagina profilo
 * @param data l'oggetto utente di cui aggionare i dati
 */
function getDataProfilePage(data){

    data["email"] = document.querySelector("#emailField").textContent;//prendo l'eventuale nuova email

    data["citta_residenza"] = document.querySelector("#cittaResidenzaField").textContent;//prendo l'eventuale nuova citta di esidenza

    data["provincia_residenza"] = document.querySelector("#provinciaResidenzaField").textContent;//prendo l'eventuale nuova provincia di esidenza

    if (document.querySelector("#partitaIvaField")){//se esiste il campo partitaIva potrebbe essere medico o datore
    
        data["partita_iva"] = document.querySelector("#partitaIVaField").textContent;//prendo l'eventuale nuova partitaIva

        if(document.querySelector("#nomeAziendaField")){//se esiste anche il campo nomaAzienda allora è un datore

            data("citta_sede_aziendale") = document.querySelector("#cittaSedeAziendaleResidenzaField").textContent;//prendo l'eventuale nuova citta per la sede aziendale

            data("provincia_sede_aziendale") = document.querySelector("#provinciaSedeAziendaleResidenzaField").textContent;//prendo l'eventuale nuova provincia per la sede aziendale
        
            data("nome_azienda") = document.querySelector("#nomeAziendaField").textContent;//prendo l'eventuale nuova nomeAzienda
        
        }
    }
}

/**
 * funzione per inviare al server i nuovi dati modificate dall'utente
 * @param {*} data dati da inviare
 */
function sendDataProfilePage(data){

}