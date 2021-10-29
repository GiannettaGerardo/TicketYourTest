<tr>
    <td scope="col"> {{$prenotazione->nome_paziente}} </td>
    <td scope="col"> {{$prenotazione->cognome_paziente}} </td>
    <td scope="col"> {{$prenotazione->cf_paziente}} </td>
    <td scope="col"> {{$prenotazione->data_tampone}} </td>
    <td scope="col"> {{$prenotazione->nome_tampone}} </td>

    <!-- Bottone utilizzato per scaricare il questionario anamnesi dell'utente -->
    <td>
        <form action="{{route('visualizza.questionario.anamnesi')}}" method="POST">
            @csrf
            <input type="hidden" name="id_prenotazione" value="{{$prenotazione->id_prenotazione}}">
            <input type="hidden" name="cf_paziente" value="{{$prenotazione->cf_paziente}}">

            @if ($prenotazione->token_scaduto != 0)
                <button type="submit" class="btn btn-success">
                    <i class="far fa-file-pdf"></i>
                    Visualizza
                </button>
            @else
                <p style="color:grey"><i>Questionario in compilazione...</i> </p>
            @endif
            
            
            
        </form>
    </td>
    <!-- Fine Bottone per questionario anamnesi -->

</tr>