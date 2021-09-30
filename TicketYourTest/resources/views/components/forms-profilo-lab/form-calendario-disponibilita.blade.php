<section class=" columnP formCalendarioDisponibilita">

    <h3>Calendiario disponibilita</h3>
    <h6 id="descrizioneCalendarioDipsonibilita" class="hiddenDisplay">Inserire nel seguente form gli orari di apertura e chiusura del vostro laboratorio. <br>
        Lasciare libere le caselle degli orari nel qual caso il dato giorno non siete aperti
    </h6>

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
                        <input type="time"  class="form-time" placeholder="0.00 $" name="calendario[${weekDays[day]}][oraApertura]" id="oraApertura${weekDays[day]}">
                    </div>

                    <div class="columnP">
                        <label>Ora Chiusura</label>
                        <input type="time"  class="form-time" placeholder="0.00 $" name="calendario[${weekDays[day]}][oraChiusura]" id="oraChiusura${weekDays[day]}">
                    </div>

                </div>

                </div>`;



            }
        </script>


    </div>

    <div class="rowP containerCapienzaGiornaliera">
        <label>Capienza Giornaliera:</label><input type="number" name="capienzaGiornaliera" placeholder="quanti tamponi al giorno" min="1" id="capienzaGiornaliera">
    </div>


</section>