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
                         <h4 class="mb-4 text-secondary">Questionario Anamnesi</h4>
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label>Nome:</label>
                                <input type="text" name="nome" class="form-control" value="Fabio" disabled>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Cognome:</label>
                                <input type="text" name="cognome" class="form-control"  value="Bonsanto" disabled>
                            </div>
                            <!--Prima domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">1- Attualmente è malato? </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="malato" id="ismalato">
                                    <label class="form-check-label" for="ismalato">
                                      Si
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="malato" id="notmalato">
                                    <label class="form-check-label" for="notmalato">
                                      No
                                    </label>
                                  </div>
                            </div>
                             <!--Seconda domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">2- Ha febbre? </label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="febbre" id="isfebbre">
                                        <label class="form-check-label" for="isfebbre">
                                        Si
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="febbre" id="notfebbre">
                                        <label class="form-check-label" for="notfebbre">
                                        No
                                        </label>
                                    </div>
                            </div>
                            <!--Terza domanda -->
                            <div class="mb-3 col-md-12">
                                <label class="font-weight-bold">
                                    3- Soffre di allergie al lattice, a qualche cibo, a farmaci o ai componenti del vaccino?
                                </label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="allergie" id="isallergia">
                                        <label class="form-check-label" for="isallergia">
                                        Si
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="allergie" id="notallergia">
                                        <label class="form-check-label" for="notallergia">
                                        No
                                        </label>
                                    </div>
                                <label class="font-weight-bold">Se si, specificare:</label>
                                <textarea class="form-control" id="allergieVarie" rows="3"></textarea>
                            </div>
                            <!--Quarta domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">
                                    4- Ha mai avuto una reazione grave dopo aver ricevuto un vaccino?
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reazioneAllergica" id="isReazioneGrave">
                                    <label class="form-check-label" for="isReazioneGrave">
                                    Si
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reazioneAllergica" id="notReazioneGrave">
                                    <label class="form-check-label" for="notReazioneGrave">
                                    No
                                    </label>
                                </div>
                            </div>
                            <!--Quinta domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">
                                    5- Soffre di malattie cardiache o polmonari, asma, malattie
                                    renali, diabete, anemia o altre malattie del sangue?
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="malattieVarie" id="isMalattieVarie">
                                    <label class="form-check-label" for="isMalattieVarie">
                                    Si
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="malattieVarie" id="notMalattieVarie">
                                    <label class="form-check-label" for="notMalattieVarie">
                                    No
                                    </label>
                                </div>
                            </div>
                            <!--Sesta domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">
                                    6- Si trova in una condizione di compromissione del sistema
                                    immunitario? (Esempio: cancro, leucemia, linfoma, HIV/AIDS,
                                    trapianto)?
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="compromissioniVarie" id="isCompromissioneVarie">
                                    <label class="form-check-label" for="isCompromissioneVarie">
                                    Si
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="compromissioniVarie" id="notCompromissioneVarie">
                                    <label class="form-check-label" for="notCompromissioneVarie">
                                    No
                                    </label>
                                </div>
                            </div>
                            <!--Settima domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">
                                    7- Negli ultimi 3 mesi, ha assunto farmaci che indeboliscono il
                                    sistema immunitario (esempio: cortisone, prednisone o altri
                                    steroidi) o farmaci antitumorali, oppure ha subito trattamenti
                                    con radiazioni?
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="assunzioneFarmaci" id="isFarmaci">
                                    <label class="form-check-label" for="isFarmaci">
                                    Si
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="assunzioneFarmaci" id="notFarmaci">
                                    <label class="form-check-label" for="notFarmaci">
                                    No
                                    </label>
                                </div>
                            </div>
                            <!--Terza domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">
                                    8- Durante lo scorso anno, ha ricevuto una trasfusione di
                                    sangue o prodotti ematici, oppure le sono stati somministrati
                                    immunoglobuline (gamma) o farmaci antivirali?                                    
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoTrasfusione" id="isTrasfusione">
                                    <label class="form-check-label" for="isTrasfusione">
                                    Si
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoTrasfusione" id="notTrasfusione">
                                    <label class="form-check-label" for="notTrasfusione">
                                    No
                                    </label>
                                </div>
                            </div>
                            <!--Nona domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">
                                    9- Ha avuto attacchi di convulsioni o qualche problema al
                                    cervello o al sistema nervoso?                                    
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoConvulsione" id="isConvulsione">
                                    <label class="form-check-label" for="isConvulsione">
                                    Si
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoConvulsione" id="notConvulsione">
                                    <label class="form-check-label" for="notConvulsione">
                                    No
                                    </label>
                                </div>
                            </div>
                            <!--Decima domanda -->
                            <div class="mb-3 col-md-12">
                                <label class="font-weight-bold">
                                    10- Ha ricevuto vaccinazioni nelle ultime 4 settimane?
                                    Se sì, quale/i?                                    
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoVaccini" id="pfizer">
                                    <label class="form-check-label" for="pfizer">
                                    Pfizer
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoVaccini" id="Astrazeneca">
                                    <label class="form-check-label" for="Astrazeneca">
                                    Astrazeneca
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoVaccini" id="Moderna">
                                    <label class="form-check-label" for="Moderna">
                                    Moderna
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoVaccini" id="Johnson">
                                    <label class="form-check-label" for="Johnson">
                                    Johnson&Johnson
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoVaccini" id="notVaccino">
                                    <label class="form-check-label" for="notVaccino">
                                    No
                                    </label>
                                </div>
                            </div>
                            <!-- Undicesima domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">
                                    11- (Per le donne)  è incinta o sta pensando di rimanere incinta nel mese
                                    successivo alla prima o alla seconda somministrazione?                                    
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoGravidanza" id="isGravidanza">
                                    <label class="form-check-label" for="isGravidanza">
                                    Si
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoGravidanza" id="notGravidanza">
                                    <label class="form-check-label" for="notGravidanza">
                                    No
                                    </label>
                                </div>
                            </div>
                            <!-- Dodicesima domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">
                                    12- (Per le donne) Sta allattando?                                    
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoAllattamento" id="isAllattamento">
                                    <label class="form-check-label" for="isAllattamento">
                                    Si
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoAllattamento" id="notAllattamento">
                                    <label class="form-check-label" for="notAllattamento">
                                    No
                                    </label>
                                </div>
                            </div>
                            <!-- Tredicesima domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">
                                    13- Nell'ultimo mese è stato in contatto con una Persona
                                    contagiata da Sars-CoV2 o affetta da COVID-19?                                
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoContattoVirus" id="isContattoVirus">
                                    <label class="form-check-label" for="isContattoVirus">
                                    Si
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoContattoVirus" id="notContattoVirus">
                                    <label class="form-check-label" for="notContattoVirus">
                                    No
                                    </label>
                                </div>
                            </div>
                            <!-- Quattordicesima domanda -->
                            <div class="mb-3 col-md-6">
                                <label class="font-weight-bold">
                                    14- Ha fatto qualche viaggio internazionale nell'ultimo
                                    mese?                                
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoViaggio" id="isViaggio">
                                    <label class="form-check-label" for="isViaggio">
                                    Si
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="infoViaggio" id="notViaggio">
                                    <label class="form-check-label" for="notViaggio">
                                    No
                                    </label>
                                </div>
                            </div>
                            <!-- Quindicesima domanda -->
                            <div class="mb-3 col-md-12">
                                <label class="font-weight-bold">
                                    15- Manifesta uno dei seguenti sintomi:                                
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="sintomi">
                                    <label class="form-check-label" for="sintomi">
                                        Tosse/raffreddore/febbre/dispnea o sintomi similinfluenzali
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="sintomi2">
                                    <label class="form-check-label" for="sintomi2">
                                    Mal di gola/perdita dell'olfatto o del gusto
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="sintomi3">
                                    <label class="form-check-label" for="sintomi3">
                                    Dolore addominale/diarrea
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="sintomi4">
                                    <label class="form-check-label" for="sintomi4">
                                        Lividi anormali o sanguinamento/arrossamento degli occhi
                                    </label>
                                </div>
                            </div>
                            <!-- Quindicesima domanda -->
                            <div class="mb-3 col-md-12">
                                <label class="font-weight-bold">
                                16- Test Covid-19:                                
                                </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <input type="checkbox">
                                    <label class="form-check-label">
                                        Data test negativo Covid-19
                                    </label>
                                  </div>
                                </div>
                                <input type="text" class="form-control" placeholder="dd/mm/aaaa">
                              </div>
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                    <input type="checkbox">
                                    <label class="form-check-label">
                                        Data test positivo Covid-19
                                    </label>
                                  </div>
                                </div>
                                <input type="text" class="form-control" placeholder="dd/mm/aaaa">
                              </div>
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