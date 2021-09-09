<tr>
    <td>{{$azienda["nome_azienda"]}}</td>
    
        <form action="{{route('abbandona.lista')}}" method="POST">
            @csrf
             <td><button type="submit" class="btn btn-danger" name="abbandona">Abbandona</button></td>
             <input name="iva" value="{{$azienda["partita_iva"]}}" type="hidden">
        </form>
</tr>