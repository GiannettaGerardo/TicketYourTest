<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Test Anamnesi</title>
</head>

<body>
    <table style="width: 100%">
        <caption style="text-align: left">
            <h4>
                TicketYourTest<br>
                <small>Questionario Anamnesi</small>
            </h4>
        </caption>
        <tbody>
            <tr>
                <td  style="width=50%">
                    <label> <strong>Nome:</strong> </label> <br>
                    <input style="width: 100%" type="text" name="nome_paziente" value="{{$questionario['nome_paziente']}}" readonly>
                </td>

                <td  style="width=50%" >
                    <label> <strong>Cognome:</strong> </label> <br>
                    <input style="width: 100%" type="text" name="cognome" value="{{$questionario['cognome']}}" readonly>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <label> <strong>Codice Fiscale:</strong> </label> <br>
                    <input style="width: 100%" type="text" name="codice_fiscale" value="{{$questionario['codice_fiscale']}}" readonly>
                </td>

            </tr>
            <tr>
                <td style="width: 50%">
                    <label> <strong>città di residenza:</strong> </label> <br>
                    <input style="width: 100%" type="text" name="citta_residenza" value="{{$questionario['citta_residenza']}}" readonly="true">
                </td>
                <td style="width: 50%">
                    <label> <strong>Provincia di residenza:</strong> </label> <br>
                    <input style="width:100%" type="text" name="provincia_residenza" value="{{$questionario['provincia_residenza']}}" readonly="true">
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <label> <strong>1- Indicare la motivazione per la quale si intende effettuare il tampone:</strong> </label> <br>
                    <p style="margin-top:0,5px">{{$questionario['motivazione']}}</p>
                </td>
                <td valign="top">
                    <label> <strong>2- In questo periodo sta lavorando?</strong> </label>
                    <p style="margin-top:0,5px">{{$questionario['lavoro']}}</p>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <label>
                        <strong>
                            3- È stato in contatto con persone risultate positive
                            al Covid-19?
                        </strong>
                    </label>
                    <p style="margin-top:0,5px">{{$questionario['contatto']}}</p>
                </td>
                <td valign="top">
                    <label>
                        <strong>
                            4- In caso positivo sono passati almeno quindici
                            giorni dall’ultimo contatto?
                        </strong>
                    </label>
                    <p style="margin-top:0,5px">{{$questionario['quindici_giorni_dopo_contatto']}}</p>
                </td>
            </tr>

            <tr>
                <td valign="top">
                    <label>
                        <strong>
                            5- Negli ultimi 21 giorni ha eseguito tampone e/o
                            test sierologici per ricerca coronavirus?
                        </strong>
                    </label>
                    <p style="margin-top:0,5px">{{$questionario['tampone_fatto']}}</p>
                </td>
                <td valign="top">
                    <label class="font-weight-bold">
                        <strong>
                            6- E’ stato in isolamento fiduciario domiciliare
                            (quarantena)?
                        </strong>
                    </label>
                    <p style="margin-top:0,5px">{{$questionario['isolamento']}}</p>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <label>
                        <strong>
                            7- Al momento è affetto, o sospetta di essere affetto
                            da Covid-19?
                        </strong>
                    </label>
                    <p style="margin-top:0,5px">{{$questionario['contagiato']}}</p>
                </td>
                <td valign="top">
                    <label>
                        <strong>
                            8- Ha avuto o ha presente qualcuno di questi
                            sintomi?
                        </strong>
                    </label>
                    <p style="margin-top:0,5px; margin-bottom:0px">
                        <ul>
                            @foreach ($questionario['sintomi'] as $sintomo)
                            <li>{{$sintomo}}</li>
                            @endforeach
                        </ul>
                    </p>
                </td>
            </tr>
        </tbody>
        
    </table>
</body>
</html>
