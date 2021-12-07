<tr>
    <td scope="col"> {{$registroPagamenti->codice_fiscale_paziente}} </td>
    <td scope="col"> {{$registroPagamenti->email_prenotante}} </td>
    <td scope="col"> {{$registroPagamenti->data_tampone}} </td>
    <td scope="col"> {{$registroPagamenti->nome_tampone}} </td>
    <td scope="col"> {{$registroPagamenti->costo_tampone}}&euro; </td>

     <!-- Bottone utilizzato per scaricare il questionario anamnesi dell'utente -->
     <td>
         <form action="{{route('registrazione.pagamenti.registra')}}" method="POST">
            @csrf
            <input type="hidden" name="id_transazione" value="{{$registroPagamenti->id_transazione}}">

            @if($flagBottone == false ) 
                <p style="color: grey"><i>Pagamento effettuato</i></p>
            @elseif($flagBottone == true) 
                <button type="submit" class="btn btn-success">Conferma pagamento</button>
            @endif
         </form>
     </td>
</tr>
