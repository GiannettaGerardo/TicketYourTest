<div class="container">

<h3>Tamponi offerti e costo</h3>
    <form action="">

        @csrf

        <div id="checkBoxTamponiOfferti">
            <div id="checkBoxTamponiOfferti_items">

                <input type="checkbox" name="tamponeRapido" id="tamponeRapido" value="tamponeRapido">
                <label> Tampone rapido</label>
                <input type="number" min="0" class="form-control" placeholder="0.00 $" name="costoTamponeRapido" id="costoTamponeRapido">
                <br>

                <input type="checkbox" name="tamponeMolecolare" id="tamponeMolecolare" value="tamponeMolecolare">
                <label> Tampone molecolare</label><br>
                <input type="number" min="0" class="form-control" placeholder="0.00 $" name="costoTamponeMolecolare" id="costoTamponeMolecolare">
            </div>

        </div>
        <button type="submit" class="btn btn-submit" style="margin-top: 0.5em;">modifica</button>

    </form>
</div>