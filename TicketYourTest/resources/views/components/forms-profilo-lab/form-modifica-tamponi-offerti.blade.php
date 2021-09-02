<section class="formModificaTamponiOfferti columnP">
    <h3>Tamponi offerti e costo</h3>

    <div id="formModificaTamponiOfferti_structure" class="columnP">

        <div class="rowP formModificaTamponiOfferti_structure_checkBoxRow">

            <input type="checkbox" name="tamponeRapido" id="tamponeRapido" value="tamponeRapido">
            <label> Tampone rapido</label>
        </div>
        <div class="rowP" class="containerInputCosto">
            <input type="number" min="0" class="form-control" placeholder="0.00 $" name="costoTamponeRapido" id="costoTamponeRapido">
            <span style="margin-left: 10px;">$</span>
        </div>
        <br>

        <div class="rowP formModificaTamponiOfferti_structure_checkBoxRow">
            <input type="checkbox" name="tamponeMolecolare" id="tamponeMolecolare" value="tamponeMolecolare">
            <label> Tampone molecolare</label><br>
        </div>
        <div class="rowP" class="containerInputCosto">
            <input type="number" min="0" class="form-control" placeholder="0.00 $" name="costoTamponeMolecolare" id="costoTamponeMolecolare">
            <span style="margin-left: 10px;">$</span>
        </div>

    </div>
</section>