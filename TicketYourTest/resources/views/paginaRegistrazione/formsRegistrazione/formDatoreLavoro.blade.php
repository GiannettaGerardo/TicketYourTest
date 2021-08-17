<div class="main">
    <div class="col-md-12 col-sm-12">
        <div class="register-form">
            <form method="post" action="{{ route('registrazione.datore.richiesta') }}">

                @csrf

                <!-- errori -->
                @error('cf')
                <x-err-msg>{{ $message }}</x-err-msg><br>
                @enderror

                @error('iva')
                <x-err-msg>{{ $message }}</x-err-msg><br>
                @enderror

                @error('nome_azienda')
                <x-err-msg>{{ $message }}</x-err-msg><br>
                @enderror

                @error('citta_sede_aziendale')
                <x-err-msg>{{ $message }}</x-err-msg><br>
                @enderror

                @error('provincia_sede_aziendale')
                <x-err-msg>{{ $message }}</x-err-msg><br>
                @enderror

                @error('nome')
                <x-err-msg>{{ $message }}</x-err-msg><br>
                @enderror

                @error('cognome')
                <x-err-msg>{{ $message }}</x-err-msg><br>
                @enderror

                @error('citta_residenza')
                <x-err-msg>{{ $message }}</x-err-msg><br>
                @enderror

                @error('provincia_residenza')
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

                @if (Session::has('psw-repeat-error'))
                <x-err-msg>{{ Session::get('psw-repeat-error') }}</x-err-msg><br>
                @endif
                
                @if (Session::has('email-already-exists'))
                <x-err-msg>{{ Session::get('email-already-exists') }}</x-err-msg><br>
                @endif

                <!-- successo -->
                @if (Session::has('register-success'))
                <x-succes-msg>{{ Session::get('register-success') }}</x-succes-msg><br>
                @endif

                <div class="inputNomeCognome">

                    <div class="form-group">
                        <label>nome</label>
                        <input type="text" class="form-control" placeholder="nome" name="nome" id="nome" required>
                    </div>

                    <div class="form-group">
                        <label>Cognome</label>
                        <input type="text" class="form-control" placeholder="cognome" name="cognome" id="cognome" required>
                    </div>

                </div>

                <div class="form-group">

                    <label>Codice Fiscale</label>
                    <input type="text" class="form-control" placeholder="Codice Fiscale" name="cf" id="cf" required>

                </div>

                <div class="rowP inputCittaProvincia">

                    <div class="form-group">
                        <label>Citta Residenza</label>
                        <input type="text" class="form-control inputCitta" placeholder="Citta residenza" name="citta_residenza" id="citta_residenza" required>
                    </div>

                    <div class="form-group">
                        <label id="labelProvincia">Provincia</label>
                        <input type="text" class="form-control inputProvincia" placeholder="Provincia" name="provincia_residenza" id="provincia_residenza" required>
                    </div>

                </div>

                <div class="form-group">

                    <label>Nome azienda</label>
                    <input type="text" class="form-control" placeholder="Nome azienda" name="nome_azienda" id="nome_azienda" required>

                </div>

                <div class="form-group">

                    <label>Partita iva</label>
                    <input type="text" class="form-control" placeholder="Partita iva" name="iva" id="iva" required>

                </div>

                <div class="rowP inputCittaProvincia">

                    <div class="form-group">
                        <label style="display:flex;flex-direction:row;flex-wrap:no-wrap">sede aziendale</label>
                        <input type="text" class="form-control inputCitta" placeholder="Citta" name="citta_sede_aziendale" id="citta_sede_aziendale" required>
                    </div>

                    <div class="form-group">
                        <label id="labelProvincia">Provincia</label>
                        <input type="text" class="form-control inputProvincia" placeholder="Provincia" name="provincia_sede_aziendale" id="provincia_sede_aziendale" required>
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
                <li><a href="{{url('registrazioneCittadino')}}">Cittadino Privato</a></li>
                <li><a href="{{url('registrazioneMedico')}}">Medico Curante</a></li>
                <li><a href="{{url('registrazioneLaboratorio')}}">Laboratorio analisi</a></li>
            </ul>

        </div>

    </div>
</div>
</div>
