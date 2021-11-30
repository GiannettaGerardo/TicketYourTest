<div class="container-fluid mt-3">

    @if (Session::has('referto-error'))

        <div class="containerErroreReferto">
            <x-err-msg>{{ Session::get('referto-error') }}</x-err-msg><br>
        </div>
        <script>
            removeAlertContainer("containerErroreReferto", 3000)

        </script>
    @endif

    @if (Session::has('referto-success'))
        <div class="containerSuccessReferto">
            <x-succes-msg>{{ Session::get('referto-success') }}</x-succes-msg><br>
        </div>
        <script>
            removeAlertContainer("containerSuccessReferto", 3000)

        </script>
    @endif

    @error('esito_tampone')
        <div class="containeValidationError">
            <x-err-msg>{{ $message }}</x-err-msg><br>
        </div>
        <script>
            removeAlertContainer("containeValidationError", 3000)

        </script>
    @enderror
    <h3>Inserisci esito</h3>

    <table class="table table-striped">

        <thead>
            <tr>
                <th scope="col">codice fiscale</th>
                <th scope="col">nome e cognome</th>
                <th scope="col">Tipo Tampone</th>
                <th scope="col">esito</th>

            </tr>
        </thead>


        <tbody>



            @foreach ($prenotazioni as $prenotazione)

                <tr>
                    <td>{{ $prenotazione->codice_fiscale }}</td>
                    <td>{{ $prenotazione->nome }} {{ $prenotazione->cognome }}</td>
                    <td>{{ $prenotazione->nome_tampone }}</td>
                    <td>
                        <form action="{{ route('conferma.esito') }}" class="formInserimentoEsito"
                            id="formInserimentoEsito" method="post">

                            @csrf

                            <input type="hidden" name="id_prenotazione" value="{{ $prenotazione->id_prenotazione }}">
                            <input type="hidden" name="cf_paziente" value="{{ $prenotazione->codice_fiscale }}">
                            <input type="hidden" id="esito_tampone" name="esito_tampone">
                            <input type="hidden" id="quantita" name="quantita" class="form-control">

                            <input type="button" value="negativo" class="btn btn-success "
                                onClick="sendNegativeResult()">
                            <input type="button" value="positivo" class="btn btn-danger "
                                onClick="sendPositiveResult()">
                            <input type="button" value="indeterminato" class="btn btn-secondary "
                                onClick="sendUndifnedResult()">

                            <input type="hidden" id="submitButton" class="btn btn-secondary">

                        </form>
                    </td>
                </tr>
            @endforeach

        </tbody>

    </table>

</div>
