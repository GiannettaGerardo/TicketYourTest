<div class="container-fluid mt-3">

    <h3>Prenotazione personali</h3>

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Data Prenotazione</th>
                <th scope="col">Data tampone</th>
                <th scope="col">Tipo Tampone</th>
                <th scope="col">Laboratorio scelto</th>
            </tr>
        </thead>


        <tbody>

            @foreach ($prenotazioni as $prenotazione)

            <tr>
                <td>{{$prenotazione->data_prenotazione}}</td>
                <td>{{$prenotazione->data_tampone}}</td>
                <td>{{$prenotazione->nome_tampone}}</td>
                <td>{{$prenotazione->laboratorio}}</td>
            </tr>


            @endforeach

        </tbody>
    </table>

</div>