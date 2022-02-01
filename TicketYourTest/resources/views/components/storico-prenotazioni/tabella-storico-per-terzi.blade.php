<div class="container-fluid mt-3">

    <h3>
        @if (Session::get('Attore') == 2){{--titolo pagina per il datore--}}
            Storico tamponi dipendenti
        @endif

        @if (Session::get('Attore') == 3){{--titolo pagina per il medico--}}
            Storico tamponi pazienti
        @endif

        @if (Session::get('Attore') == 1){{--titolo pagina per il normale utente--}}
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
                @if (Session::get('Attore') == 3)
                    <th>notifica positivita ASL</th>
                @endif
            </tr>
        </thead>


        <tbody>

            @foreach ($prenotazioni as $prenotazione)


                <tr>
                    <td>{{$prenotazione->data_tampone}}</td>
                    <td>{{$prenotazione->tipo_tampone}}</td>
                    <td>{{$prenotazione->laboratorio_scelto}}</td>
                    <td>{{$prenotazione->nome_terzo}}  {{$prenotazione->cognome_terzo}}</td>
                    <td><a class="btn btn-primary" href="{{ route('referto.tampone', $prenotazione->id_referto) }}">scarica</a></td>
                    @if (Session::get('Attore') == 3)

                    @if ($prenotazione->risultato_comunicato == 0)
                        <td>
                            <form action="{{route('storico.prenotazioni.medico')}}" method="POST" style="justify-content: space-around; align-items: center" class="rowP">

                                @csrf

                                <input type="hidden" name="cf_terzo" value="{{$prenotazione->cf_terzo}}">

                                <input type="submit" value="invia notifica" class="btn btn-primary">

                            </form>
                        </td>
                    @else
                        <td>notifica avvenuta</td>
                    @endif

                    @endif

                </tr>

            @endforeach

        </tbody>

    </table>

</div>
