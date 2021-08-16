<div class="main">
    <div class="col-md-12 col-sm-12">
        <div class="register-form">
            <form method="post" action="">

                @csrf


                <div class="form-group">
                    <label>Nome Laboratorio</label>
                    <input type="text" class="form-control" placeholder="nome" name="nomeLaboratorio" required>
                </div>


                <div class="form-group">

                    <label>Partita iva</label>
                    <input type="text" class="form-control" placeholder="Partita iva" name="iva" id="iva" required>

                </div>

                <div class="rowP inputCittaProvincia">

                    <div class="form-group">
                        <label>Citta</label>
                        <input type="text" class="form-control inputCitta" placeholder="Citta" required>
                    </div>

                    <div class="form-group">
                        <label id="labelProvincia">Provincia</label>
                        <input type="text" class="form-control inputProvincia" placeholder="Provincia" required>
                    </div>

                </div>

                <div class="form-group">

                    <label>Indirizzo</label>
                    <input type="text" class="form-control" placeholder="Indirizzo">

                </div>

                <div id="checkBoxTamponiOfferti">

                    <label>Tamponi offerti e costo</label>

                    <div class="form-group" id="checkBoxTamponiOfferti_items">
 
                        <input type="checkbox" name="tamponeRapido" value="tamponeRapido">
                        <label> Tampone rapido</label>
                        <input type="number" min="0" class="form-control" placeholder="0.00 $" name="costoTamponeRapido">
                        <br>

                        <input type="checkbox" name="tamponeMolecolare" value="tamponeMolecolare">
                        <label> Tampone molecolare</label><br>
                        <input type="number" min="0" class="form-control" placeholder="0.00 $" name="costoTamponeMolecolare">
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