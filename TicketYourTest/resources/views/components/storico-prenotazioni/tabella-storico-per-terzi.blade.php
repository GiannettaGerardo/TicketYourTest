<div class="container-fluid mt-3">

    <h3>Storico tamponi dipendenti</h3>

    <table class="table table-striped">

        <thead>
            <tr>
                <th scope="col">Data tampone</th>
                <th scope="col">Tipo Tampone</th>
                <th scope="col">Laboratorio scelto</th>
                <th scope="col">dipendente</th>
                <th scope="col">referto</th>
            </tr>
        </thead>


        <tbody>

            @foreach ($prenotazioni as $prenotazione)

                <tr>
                    <td>{{$prenotazione->data_tampone}}</td>
                    <td>{{$prenotazione->tipo_tampone}}</td>
                    <td>{{$prenotazione->laboratorio_scelto}}</td>
                    <td>{{$prenotazione->nome_dipendente}} {{$prenotazione->cognome_dipendente}}</td>
                    <td><a class="btn btn-primary" href="">scarica</a></td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>