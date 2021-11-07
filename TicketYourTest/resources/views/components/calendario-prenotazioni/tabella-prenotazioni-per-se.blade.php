<div class="container-fluid mt-3">

    <h3>Prenotazione personali</h3>

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Data Prenotazione</th>
                <th scope="col">Data tampone</th>
                <th scope="col">Tipo Tampone</th>
                <th scope="col">Laboratorio scelto</th>
                <th scope="col">
                    <form action="{{ route('annulla.prenotazioni') }}" method="post" class="formAnnullaPrenotazione">
                        @csrf

                        @php
                            
                            $i = 0;
                            foreach ($prenotazioni as $prenotazione) {
                                echo '<input type="hidden" value="' . $prenotazione->id_prenotazione . '" name="prenotazioni[' . $i . '][id_prenotazione]">';
                                echo '<input type="hidden" value="' . $prenotazione->codice_fiscale . '" name="prenotazioni[' . $i . '][codice_fiscale]">';
                            
                                $i++;
                            }
                            
                        @endphp

                        <button type="submit" class="btn btn-danger">annulla tutte</button>
                    </form>
                </th>
                <th scope="col">questionario anamnesi</th>
            </tr>
        </thead>


        <tbody>

            @foreach ($prenotazioni as $prenotazione)
                <tr>
                    <td>{{ $prenotazione->data_prenotazione }}</td>
                    <td>{{ $prenotazione->data_tampone }}</td>
                    <td>{{ $prenotazione->nome_tampone }}</td>
                    <td>{{ $prenotazione->laboratorio }}</td>
                    <td>
                        <form action="{{ route('annulla.prenotazioni') }}" method="post"
                            class="formAnnullaPrenotazione">
                            @csrf

                            <input type="hidden" value="{{ $prenotazione->id_prenotazione }}"
                                name="prenotazioni[0][id_prenotazione]">
                            <input type="hidden" value="{{ $prenotazione->codice_fiscale }}"
                                name="prenotazioni[0][codice_fiscale]">



                            <button type="submit" class="btn btn-danger">annulla</button>
                        </form>
                    </td>
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
