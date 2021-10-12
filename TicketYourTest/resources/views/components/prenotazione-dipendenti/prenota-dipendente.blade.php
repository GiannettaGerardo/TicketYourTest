<tr>
    <td>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="{{$dipendenti->codice_fiscale}}" id="dipendenti[]" name="dipendenti[]">
        </div>
    </td>
    <td>{{$dipendenti->nome}}</td>
    <td>{{$dipendenti->cognome}}</td>
    <td>{{$dipendenti->codice_fiscale}}</td>
</tr>