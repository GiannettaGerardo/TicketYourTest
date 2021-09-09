<tr>
    <td>{{$dipendente["nome"]}}</td>
    <td>{{$dipendente["cognome"]}}</td>
    <td>{{$dipendente["codice_fiscale"]}}</td>
    <td>{{$dipendente["citta_residenza"]}}</td>
    <td>{{$dipendente["provincia_residenza"]}}</td>
    <td>{{$dipendente["email"]}}</td>

        <!-- Bottone per accettare la richiesta di un dipendente -->
        
        <form action="{{route('accetta.dipendente')}}" method="POST">
            @csrf
            <td><button type="submit" class="btn btn-success" name="accetta">Accetta</button></td>
            <input value="{{$dipendente["codice_fiscale"]}}" name="codice_fiscale" type="hidden">
        </form>

        <!-- Bottone per rifiutare la richiesta di un dipendente -->
        <form action="{{route('rifiuta.dipendente')}}" method="POST">
            @csrf
            <td><button type="submit" class="btn btn-danger" name="rifiuta">Rifiuta</button></td>
            <input value="{{$dipendente["codice_fiscale"]}}" name="codice_fiscale" type="hidden">
        </form>
</tr>