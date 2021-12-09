<div class="container-fluid mt-3">

    <h3>
        @if (Session::get('Attore') == 2)
            Storico tamponi dipendenti
        @endif

        @if (Session::get('Attore') == 3)
            Storico tamponi pazienti
        @endif

        @if (Session::get('Attore') == 1)
            Storico tamponi per terzi
        @endif
    </h3>

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
                    <td>{{$prenotazione->nome_terzo}}  {{$prenotazione->cognome_terzo}}</td>             
                    <td><a class="btn btn-primary" href="{{ route('referto.tampone', $prenotazione->id_referto) }}" download>scarica</a></td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>