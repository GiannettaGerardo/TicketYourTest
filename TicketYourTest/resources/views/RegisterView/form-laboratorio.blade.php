<div class="main">
    <div class="col-md-12 col-sm-12">
        <div class="register-form">
            <form method="post" action="{{ route('registrazione.laboratorio.richiesta') }}">

                @csrf

                @error('nome')
                <x-err-msg>{{ $message }}</x-err-msg><br>
                @enderror

                @error('iva')
                <x-err-msg>{{ $message }}</x-err-msg><br>
                @enderror

                @error('citta')
                <x-err-msg>{{ $message }}</x-err-msg><br>
                @enderror

                @error('provincia')
                <x-err-msg>{{ $message }}</x-err-msg><br>
                @enderror


                @error('indirizzo')
                <x-err-msg>{{ $message }}</x-err-msg><br>
                @enderror

                @error('email')
                <x-err-msg>{{ $message }}</x-err-msg><br>
                @enderror

                @error('psw')
                <x-err-msg>{{ $message }}</x-err-msg><br>
                @enderror

                @error('psw-repeat')
                <x-err-msg>{{ $message }}</x-err-msg><br>
                @enderror

                @if (Session::has('tampone-non-scelto'))
                <x-err-msg>{{ Session::get('tampone-non-scelto') }}</x-err-msg><br>
                @endif

                @if (Session::has('costo-tampone-non-inserito'))
                <x-err-msg>{{ Session::get('costo-tampone-non-inserito') }}</x-err-msg><br>
                @endif

                @if (Session::has('psw-repeat-error'))
                <x-err-msg>{{ Session::get('psw-repeat-error') }}</x-err-msg><br>
                @endif

                @if (Session::has('email-already-exists'))
                <x-err-msg>{{ Session::get('email-already-exists') }}</x-err-msg><br>
                @endif

                <!-- successo -->
                @if (Session::has('register-success'))
                <x-succes-msg>{{ Session::get('register-success') }}<br> <a href="{{url('login')}}"><b>Accedi per vedere se sei stato convezionato dall'amministratore</b></a></x-succes-msg><br>
                @endif


                <div class="form-group">
                    <label>Nome Laboratorio</label>
                    <input type="text" class="form-control" placeholder="nome" name="nome" required>
                </div>


                <div class="form-group">

                    <label>Partita iva</label>
                    <input type="text" class="form-control" placeholder="Partita iva" name="iva" id="iva" required>

                </div>

                <div class="rowP inputCittaProvincia">

                    <div class="form-group">
                        <label>Citta</label>
                        <input type="text" class="form-control inputCitta" placeholder="Citta" name="citta" id="citta" required>
                    </div>

                    <div class="form-group">
                        <label id="labelProvincia">Provincia</label>
                        <input type="text" class="form-control inputProvincia" placeholder="Provincia" name="provincia" id="provincia" required>
                    </div>

                </div>

                <div class="form-group">

                    <label>Indirizzo</label>
                    <input type="text" class="form-control" placeholder="Indirizzo" name="indirizzo" id="indirizzo" required>

                </div>

                <div id="checkBoxTamponiOfferti">

                    <label>Tamponi offerti e costo</label>

                    <div class="form-group" id="checkBoxTamponiOfferti_items">

                        <input type="checkbox" name="tamponeRapido" id="checkBoxTamponeRapido" value="tamponeRapido">
                        <label> Tampone rapido</label>
                        <input type="number" min="0" class="form-control" placeholder="0.00 $" name="costoTamponeRapido" id="costoTamponeRapido" step=".01">
                        <br>

                        <input type="checkbox" name="tamponeMolecolare" id="checkBoxTamponeMolecolare" value="tamponeMolecolare">
                        <label> Tampone molecolare</label><br>
                        <input type="number" min="0" class="form-control" placeholder="0.00 $" name="costoTamponeMolecolare" id="costoTamponeMolecolare" step=".01">
                    </div>

                </div>

                <div class="form-group">

                    <label>Email</label>
                    <input type="email" class="form-control" placeholder="Email" name="email" id="email" required>

                </div>

                <div class="form-group">

                    <label>Password</label>
                    <input type="password" class="form-control" placeholder="Password" name="psw" id="psw" required>

                </div>

                <div class="form-group">

                    <label>Conferma Password</label>
                    <input type="password" class="form-control" placeholder="Conferma Password" name="psw-repeat" id="psw-repeat" required>

                </div>

        </div>

        <button type="submit" class="btn btn-submit">Registrati</button>
        </form>

        <div id="tipoRegistrazione" class="columnP">

            <h6>O registrati come:</h6>

            <ul class="rowP">
                <li><a href="{{url('registrazioneDatore')}}">Datore di lavoro</a></li>
                <li><a href="{{url('registrazioneMedico')}}">Medico curante</a></li>
                <li><a href="{{url('registrazioneCittadino')}}">Cittadino Privato</a></li>
            </ul>

        </div>

    </div>
</div>
</div>
<script src="{{ URL::asset('/script/script.js') }}"></script>
<script>
    setEnableCostoTampone();
</script>