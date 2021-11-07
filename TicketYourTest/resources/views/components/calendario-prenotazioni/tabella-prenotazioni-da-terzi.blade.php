<div class="container-fluid mt-3">

    <h3>Prenotazioni da Terzi</h3>

    <table class="table table-striped">

        <thead>
            <tr>
                <th scope="col">Data Prenotazione</th>
                <th scope="col">Data tampone</th>
                <th scope="col">Tipo Tampone</th>
                <th scope="col">Laboratorio scelto</th>
                <th scope="col">Da parte di...</th>
                <th scope="col">questionario anamnesi</th>
            </tr>
        </thead>


        <tbody>

            @foreach ($prenotazioni as $prenotazione)

            <tr>
                <td>{{$prenotazione->data_prenotazione}}</td>
                <td>{{$prenotazione->data_tampone}}</td>
                <td>{{$prenotazione->nome_tampone}}</td>
                <td>{{$prenotazione->laboratorio}}</td>
                <td>{{$prenotazione->nome_prenotante}} {{$prenotazione->cognome_prenotante}}</td>
                <td>
                    @if ($prenotazione->token_questionario_scaduto == 1)

                        <span>compilato <i class="fas fa-check"></i></span>

                    @else

                        <a href="{{ route('questionario.anamnesi', $prenotazione->token_questionario) }}" class="btn btn-success">compila</a>

                    @endif

                </td>
            </tr>
            @endforeach

        </tbody>

    </table>

</div>