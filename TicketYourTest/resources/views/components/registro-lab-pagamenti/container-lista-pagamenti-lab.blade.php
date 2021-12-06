<tr>
    <td scope="col"> {{$registroPagamenti->codice_fiscale_paziente}} </td>
    <td scope="col"> {{$registroPagamenti->email_prenotante}} </td>
    <td scope="col"> {{$registroPagamenti->data_tampone}} </td>
    <td scope="col"> {{$registroPagamenti->nome_tampone}} </td>
    <td scope="col"> {{$registroPagamenti->costo_tampone}} </td>

     <!-- Bottone utilizzato per scaricare il questionario anamnesi dell'utente -->
     <td>
         <form action="{{route('registrazione.pagamenti.registra')}}" method="POST">
            @csrf
            <input type="hidden" name="id_transazione" value="{{$registroPagamenti->id_transazione}}">

            @if ($flagBottone == false ) 
                </p> Pagamento effettuato con successo </p>
            @else 
                <input type="submit" class="btn btn-success">
            @endif

            
                
            @endif
            
         </form>
     </td>
</tr>
