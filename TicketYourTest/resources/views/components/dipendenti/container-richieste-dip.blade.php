<tr>
    <td>{{$dipendente["nome"]}}</td>
    <td>{{$dipendente["cognome"]}}</td>
    <td>{{$dipendente["codice_fiscale"]}}</td>
    <td>{{$dipendente["citta_residenza"]}}</td>
    <td>{{$dipendente["provincia_residenza"]}}</td>
    <td>{{$dipendente["email"]}}</td>
        <form action="">
             <td><button type="submit" class="btn btn-success" name="accetta">Accetta</button></td>
             <td><button type="submit" class="btn btn-danger" rifiuta="rifiuta">Rifiuta</button></td>
            <input value="{{$dipendente["codice_fiscale"]}}" type="hidden">
        </form>
</tr>