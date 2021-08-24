<div class="row effettohover w-50 ml-0 mr-0 containerLab justify-content-start mb-2">
    <div class="col-xs-10 col-md-11">
        <div>
            <h3 style="color: #2C8F5B;">
                {{$laboratorio["nome"]}}
            </h3>
            <div class="mic-info">
                <p> E-mail: {{$laboratorio["email"]}} </p>
                <p>{{$laboratorio["indirizzo"]}}</p>
                <p>{{$laboratorio["partita_iva"]}}</p>
                <form class="row justify-content-around" method="post" action="">

                    <input type="hidden" value="{{$laboratorio['id']}}">

                    <div class="row" style="align-items: center;">
                        <label for="Xcordinate" style="margin-right: 0.5em;">X:</label>
                        <span></span>
                        <input type="text" id="Xcordinate" name="Xcordinate">
                    </div>

                    <div class="row" style="align-items: center;">
                        <label for="Ycordinate" style="margin-right: 0.5em;">Y:</label>
                        <input type="text" id="Ycordinate" name="Ycordinate">
                    </div>

                    <div class="action">
                        <button type="submit" class="btn btn-success btn-xs" title="Approved">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                <path d="M13.485 1.431a1.473 1.473 0 0 1 2.104 2.062l-7.84 9.801a1.473 1.473 0 0 1-2.12.04L.431 8.138a1.473 1.473 0 0 1 2.084-2.083l4.111 4.112 6.82-8.69a.486.486 0 0 1 .04-.045z" />
                            </svg>
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>