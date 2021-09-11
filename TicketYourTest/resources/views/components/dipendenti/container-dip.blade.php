<tr>
    <td>{{$dipendente["nome"]}}</td>
    <td>{{$dipendente["cognome"]}}</td>
    <td>{{$dipendente["codice_fiscale"]}}</td>
    <td>{{$dipendente["citta_residenza"]}}</td>
    <td>{{$dipendente["provincia_residenza"]}}</td>
    <td>{{$dipendente["email"]}}</td>
    <td>
        <form action="{{route('elimina.dipendente')}}" method="POST">
            @csrf

            <button type="submit" class="btn btn-danger">Elimina dipendente</button>
            <input name="codice_fiscale" value="{{$dipendente['codice_fiscale']}}" type="hidden">
        </form>
    </td>
</tr>
