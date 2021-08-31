<section class=" columnP formCalendarioDisponibilita">

    <h3>Calendiario disponibilita</h3>

    <div id="formCalendarioDisponibilita_structure" class="columnP">

    <script>
        let weekDays = ["lunedi", "martedi", "mercoledi", "giovedi", "venerdi", "sabato", "domenica"];

        thisFormStructure = document.querySelector("#formCalendarioDisponibilita_structure");

        for (let day in weekDays) {

            

            thisFormStructure.innerHTML += `         
                <div class="rowP">

                <label>${weekDays[day]}:</label>
                <div class="rowP formCalendiarioDiposnibilita_structure_timeRow">

                    <div class="columnP">
                        <label>Ora Apertura</label>
                        <input type="time"  class="form-time" placeholder="0.00 $" name="calendario[${weekDays[day]}][oraApertura]">
                    </div>

                    <div class="columnP">
                        <label>Ora Chiusura</label>
                        <input type="time"  class="form-time" placeholder="0.00 $" name="calendario[${weekDays[day]}][oraChiusura]">
                    </div>

                </div>

                </div>`;



        }
    </script>


    </div>



</section>