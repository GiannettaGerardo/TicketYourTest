<section class=" columnP formCalendarioDisponibilita">

    <h3>Calendiario disponibilita</h3>

    <div id="formCalendarioDisponibilita_structure" class="columnP">

    <script>
        let weekDays = ["lunedi", "martedi", "mercoledi", "giovedi", "venerdi", "sabato", "domenica"];

        thisFormStructure = document.querySelector("#formCalendarioDisponibilita_structure");

        for (let day in weekDays) {

            

            thisFormStructure.innerHTML += `         
                <div class="rowP">

                <label for="">${weekDays[day]}:</label>
                <div class="rowP formCalendiarioDiposnibilita_structure_timeRow">

                    <div class="columnP">
                        <label for="oraApertura${weekDays[day]}">Ora Apertura</label>
                        <input type="time" min="0" class="form-time" placeholder="0.00 $" name="oraApertura${weekDays[day]}">
                    </div>

                    <div class="columnP">
                        <label for="oraChiusura${weekDays[day]}">Ora Chiusura</label>
                        <input type="time" min="0" class="form-time" placeholder="0.00 $" name="oraChiusura${weekDays[day]}">
                    </div>

                </div>

                </div>`;



        }
    </script>


    </div>



</section>