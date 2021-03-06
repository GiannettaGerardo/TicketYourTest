<div class="container-fluid " style="height: calc(100vh - 6vh);">
    <div class="row d-flex justify-content-center  align-items-stretch h-100">
        <div class="col-md-10 met-5 pt-2 pb-2 row  align-items-stretch justify-content-stretch w-100">
            <div class="row z-depth-3 row align-items-stretch justify-content-stretch">

                <div class="col-sm-4 bg-success rounded-left">

                    <div class="card-block text-center text-white">
                        <i class="fas fa-user fa-7x mt-5"></i>
                        <h2 class="font-weight-bold mt-4"> {{$cittadinoPrivato->nome}} </h2>
                        <h2 class="font-weight-bold mt-4"> {{$cittadinoPrivato->cognome}} </h2>
                        <p> Cittadino Privato </p>
                        <!-- Icona di modifica profilo personale -->
                        <i class="far fa-edit fa-2x mb-4 effettohover" id="editProfileButton"></i>
                        <!--pulsante di conferma modifiche-->
                        <button type="button" class="btn btn-primary hiddenDisplay" id="confirmEditButton">Conferma modifiche</button>
                    </div>

                </div>

                <div class="col-sm-8 bg-white rounded-right row align-items-between">
                    <h3 class="mt-3 text-center"> Informazioni </h3>
                    <hr class="bg-primary">

                    <div class="row">

                        <div class="col-sm-6">
                            <p class="font-weight-bold">E-mail:</p>
                            <p class="text-muted editableField" id="emailField">{{$cittadinoPrivato->email}}</p>
                        </div>

                    </div>

                    <hr class="bg-primary">

                    <div class="row">

                        <div class="col-sm-6">
                            <p class="font-weight-bold">Codice Fiscale:</p>
                            <p class="text-muted">{{$cittadinoPrivato->codice_fiscale}}</p>
                        </div>

                    </div>

                    <hr class="bg-primary">

                    <div class="row">

                        <div class="col-sm-6">
                            <p class="font-weight-bold">Citt?? residenza: </p>
                            <h6 class="text-muted editableField" id="cittaResidenzaField"> {{$cittadinoPrivato->citta_residenza}} </h6>
                        </div>

                        <div class="col-sm-6">
                            <p class="font-weight-bold">Provincia:</p>
                            <p class="text-muted editableField" id="provinciaResidenzaField"> {{$cittadinoPrivato->provincia_residenza}} </p>
                        </div>

                    </div>

                    <hr class="bg-primary">

                </div>
            </div>
        </div>
    </div>
</div>