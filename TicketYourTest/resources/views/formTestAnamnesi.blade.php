<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test Anamnesi</title>
    <!--CDN Bootstrap -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

</head>
<body style="overflow-x: hidden">
    <!-- Navbar -->
    <x-header.header />
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="aggiungi-form">
                    <form action="#" class="mt-5 p-4 bg-light border" method="POST">
                         <h4 class="mb-4 text-secondary">
                             Laboratorio Fittizio
                             <small>Questionario Anamnesi</small>
                         </h4>
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label>Nome:</label>
                                <input type="text" name="nome" class="form-control" value="Fabio" readonly="true">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Cognome:</label>
                                <input type="text" name="cognome" class="form-control"  value="Bonsanto" readonly="true">
                            </div>
                            <div class="mb-3 col-md-12">
                                <label>Codice Fiscale:</label>
                                <input type="text" name="codice_fiscale" class="form-control"  value="BNSFBA98L19I158N" readonly="true">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Città di residenza:</label>
                                <input type="text" name="citta_residenza" class="form-control" value="San Severo" readonly="true">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Provincia di residenza:</label>
                                <input type="text" name="provincia_residenza" class="form-control"  value="Foggia" readonly="true">
                            </div>
                            <!--Prima domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">1- Indicare la motivazione per la quale si intende effettuare il tampone: </label> <br>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="motivazione" id="motivazione_1" value="sintomi">
                                    <label class="form-check-label" for="motivazione_1">
                                      Presenza di sintomi
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="motivazione" id="motivazione_2" value="contatto">
                                    <label class="form-check-label" for="motivazione_2">
                                      Contatto con positivi
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="motivazione" id="motivazione_3" value="controllo">
                                    <label class="form-check-label" for="motivazione_3">
                                      Controllo
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="motivazione" id="motivazione_4" value="accesso-struttura-sanitaria">
                                    <label class="form-check-label" for="motivazione_4">
                                      Accesso struttura sanitaria
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="motivazione" id="motivazione_5" value="viaggi-trasferta">
                                    <label class="form-check-label" for="motivazione_5">
                                      Viaggi e trasferte
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="motivazione" id="motivazione_6" value="lavoro">
                                    <label class="form-check-label" for="motivazione_6">
                                      Attività lavorativa
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="motivazione" id="motivazione_7" value="sport">
                                    <label class="form-check-label" for="motivazione_7">
                                      Attività sportiva
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="motivazione" id="motivazione_8" value="scuola">
                                    <label class="form-check-label" for="motivazione_8">
                                      Attività scolastica
                                    </label>
                                  </div>
                            </div>
                             <!--Seconda domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">2- In questo periodo sta lavorando? </label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="lavoro" id="islavoro" value="si">
                                        <label class="form-check-label" for="islavoro">
                                        Si
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="lavoro" id="notlavoro" value="no">
                                        <label class="form-check-label" for="notlavoro">
                                        No
                                        </label>
                                    </div>
                            </div>
                            <!--Terza domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">
                                    3- È stato in contatto con persone risultate positive
                                    al Covid-19? 
                                </label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="contatto" id="iscontatto" value="si">
                                        <label class="form-check-label" for="iscontatto">
                                        Si
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="contatto" id="notcontatto" value="no">
                                        <label class="form-check-label" for="notcontatto">
                                        No
                                        </label>
                                    </div>
                            </div>
                            <!--Quarta domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">
                                    4- In caso positivo sono passati almeno quindici
                                    giorni dall’ultimo contatto?
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="quindici-giorni-dopo-contatto" id="giorni-dopo-contatto" value="si">
                                    <label class="form-check-label" for="giorni-dopo-contatto">
                                    Si
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="quindici-giorni-dopo-contatto" id="not-giorni-dopo-contatto" value="no">
                                    <label class="form-check-label" for="not-giorni-dopo-contatto">
                                    No
                                    </label>
                                </div>
                            </div>
                            <!--Quinta domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">
                                    5- Negli ultimi 21 giorni ha eseguito tampone e/o
                                    test sierologici per ricerca coronavirus?
                                    
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tampone-fatto" id="tampone-fatto" value="si">
                                    <label class="form-check-label" for="tampone-fatto">
                                    Si
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tampone-fatto" id="not-tampone-fatto" value="no">
                                    <label class="form-check-label" for="not-tampone-fatto">
                                    No
                                    </label>
                                </div>
                            </div>
                            <!--Sesta domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">
                                    6- E’ stato in isolamento fiduciario domiciliare
                                    (quarantena)?
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="isolamento" id="is-isolamento" value="si">
                                    <label class="form-check-label" for="is-isolamento">
                                    Si
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="isolamento" id="not-isolamento" value="no">
                                    <label class="form-check-label" for="not-isolamento">
                                    No
                                    </label>
                                </div>
                            </div>
                            <!--Settima domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">
                                    7- Al momento è affetto, o sospetta di essere affetto
                                    da Covid-19?                                    
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="info-contagio-covid" id="is-contagiato-covid" value="si">
                                    <label class="form-check-label" for="is-contagiato-covid">
                                    Si
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="info-contagio-covid" id="not-contagiato-covid" value="no">
                                    <label class="form-check-label" for="not-contagiato-covid">
                                    No
                                    </label>
                                </div>
                            </div>
                            <!--ottava domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">
                                    8- Ha avuto o ha presente qualcuno di questi
                                    sintomi?                                   
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="sintomi[]" id="febbre" value="si-febbre">
                                    <label class="form-check-label" for="febbre">
                                    Febbre
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="sintomi[]" id="tosse" value="si-tosse">
                                    <label class="form-check-label" for="tosse">
                                    Tosse
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="sintomi[]" id="difficolta_respiratorie" value="si-difficoltà-respiratorie">
                                    <label class="form-check-label" for="difficolta_respiratorie">
                                    Difficoltà respiratorie
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="sintomi[]" id="raffreddore" value="si-raffreddore">
                                    <label class="form-check-label" for="raffreddore">
                                    Raffreddore
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="sintomi[]" id="malDiGola" value="si-malDiGola">
                                    <label class="form-check-label" for="malDiGola">
                                    Mal di gola
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="sintomi[]" id="mancanzaGusto" value="si-alterazione-gusto">
                                    <label class="form-check-label" for="mancanzaGusto">
                                    Alterazione del gusto
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="sintomi[]" id="doloriMuscolari" value="si-dolori-muscolari">
                                    <label class="form-check-label" for="doloriMuscolari">
                                    Dolori muscolari
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="sintomi[]" id="cefalea" value="si-cefalea">
                                    <label class="form-check-label" for="cefalea">
                                    Cefalea
                                    </label>
                                </div>
                            </div>
                           
                            <div class="mb-4 col-md-12">
                                <button type="submit" class="btn btn-success float-right mt-2">Conferma</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>