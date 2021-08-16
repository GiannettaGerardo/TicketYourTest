<div class="main">
    <div class="col-md-12 col-sm-12">
        <div class="register-form">
            <form method="post" action="">

                @csrf

                <!-- errori -->
                @error('cf')
                <span>{{ $message }}</span><br>
                @enderror
                @error('nome')
                <span>{{ $message }}</span><br>
                @enderror
                @error('cognome')
                <span>{{ $message }}</span><br>
                @enderror
                @error('citta_residenza')
                <span>{{ $message }}</span><br>
                @enderror
                @error('provincia_residenza')
                <span>{{ $message }}</span><br>
                @enderror
                @error('email')
                <span>{{ $message }}</span><br>
                @enderror
                @error('psw')
                <span>{{ $message }}</span><br>
                @enderror
                @error('psw-repeat')
                <span>{{ $message }}</span><br>
                @enderror
                @if (Session::has('psw-repeat-error'))
                <span>{{ Session::get('psw-repeat-error') }}</span><br>
                @endif
                @if (Session::has('email-already-exists'))
                <span>{{ Session::get('email-already-exists') }}</span><br>
                @endif

                <!-- successo -->
                @if (Session::has('register-success'))
                <span>{{ Session::get('register-success') }}</span><br>
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
                        <input type="text" class="form-control inputCitta" placeholder="CittaResidenza" name="citta_residenza" id="citta_residenza" required>
                    </div>

                    <div class="form-group">
                        <label id="labelProvincia">Provincia</label>
                        <input type="text" class="form-control inputProvincia" placeholder="Provincia" name="provincia_residenza" id="provincia_residenza" required>
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
                <li><a href="{{url('registrazioneMedico')}}">Medico Curante</a></li>
                <li><a href="{{url('registrazioneLaboratorio')}}">Laboratorio analisi</a></li>
            </ul>

        </div>

    </div>
</div>
</div>