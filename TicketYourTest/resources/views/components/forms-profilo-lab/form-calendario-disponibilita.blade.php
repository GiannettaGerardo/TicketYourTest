<div class="container">

    <h3>Calendiario disponibilita</h3>
    <form action="" id="formDisponibilita" class="columnP">

        @csrf

        <div class="columnP">

            <script>
                let weekDays = ["lunedi", "martedi", "mercoledi", "giovedi", "venerdi", "sabato", "domenica"];

                let thisForm;
                for (let day in weekDays) {

                    thisForm = document.querySelector("#formDisponibilita");

                    thisForm.innerHTML += ` 
                                    <div class="rowP containerFormField">

                                        <label> ${weekDays[day]}: </label>

                                        <div class="rowP containerFormField_timeRow">

                                            <div class="columnP">
                                                <label for="orarioApertura${weekDays[day]}">Ora Apertura</label>
                                                <input type="time" min="0" class="form-control" placeholder="0.00 $" name="orarioApertura${weekDays[day]}">
                                            </div>

                                            <div class="columnP">
                                                <label for="orarioChiusura${weekDays[day]}">Ora Chiusura</label>
                                                <input type="time" min="0" class="form-control" placeholder="0.00 $" name="orarioChiusura${weekDays[day]}">
                                            </div>

                                        </div>

                                    </div>`, 'text/html';



                }

                thisForm.innerHTML += `<button type="submit" class="btn btn-submit">modifica</button>`;
            </script>
        </div>

    </form>
</div>