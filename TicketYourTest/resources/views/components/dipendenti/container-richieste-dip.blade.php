<tr>
    <td>{{$dipendente["nome"]}}</td>
    <td>{{$dipendente["cognome"]}}</td>
    <td>{{$dipendente["codice_fiscale"]}}</td>
    <td>{{$dipendente["citta_residenza"]}}</td>
    <td>{{$dipendente["provincia_residenza"]}}</td>
    <td>{{$dipendente["email"]}}</td>

    <!-- Bottone per accettare la richiesta di un dipendente -->

    <td>
        <form action="{{route('accetta.dipendente')}}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success" name="accetta">Accetta</button>
            <input value="{{$dipendente['codice_fiscale']}}" name="codice_fiscale" type="hidden">
        </form>
    </td>

    <!-- Bottone per rifiutare la richiesta di un dipendente -->
    <td>
        <form action="{{route('rifiuta.dipendente')}}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger" name="rifiuta">Rifiuta</button>
            <input value="{{$dipendente['codice_fiscale']}}" name="codice_fiscale" type="hidden">
        </form>
    </td>
</tr>