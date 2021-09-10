<tr>
    <td>{{$azienda["nome_azienda"]}}</td>

    <td>
        <form action="{{route('abbandona.lista')}}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger" name="abbandona">Abbandona</button>
            <input name="iva" value="{{$azienda["partita_iva"]}}" type="hidden">
        </form>
    </td>
</tr>