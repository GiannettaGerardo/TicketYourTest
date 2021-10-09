<tr>
    <td>{{$prenotazione->data_prenotazione}}</td>
    <td>{{$prenotazione->data_tampone}}</td>
    <td>{{$prenotazione->nome_tampone}}</td>
    <td>{{$prenotazione->laboratorio}}</td>
    @if (property_exists($prenotazione,'nome_prenotante'))
    <td>{{$prenotazione->nome_prenotante}} {{$prenotazione->cognome_prenotante}}</td>
    @endif
    @if (property_exists($prenotazione,'nome_paziente'))
    <td>{{$prenotazione->nome_paziente}} {{$prenotazione->cognome_paziente}}</td>
    @endif
</tr>
