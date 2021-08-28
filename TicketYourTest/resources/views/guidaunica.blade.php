<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Guida Unica </title>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link rel="stylesheet" href="{{ URL::asset('/css/stile.css') }}">

    <style>


      .jumbotron {
        margin-bottom: 0rem;}
  </style>

</head>

<body style="overflow-x: hidden;">
    <!-- Navbar -->
    <x-header.header />
    <!--Fine Navabr -->

    <section>
   <div class="container">
        <div class="row no-gutters">
             <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                  <!--img-->
                  <img src="Images/guida1.png" alt="demo-img" class="img-fluid w-100">
             </div>
             <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                  <!--text-->
                  <div class="position">
                    <div class="container">
                      <h1 class="text-center">Tampone molecolare</h1>
                      <p class="text-justify">Viene eseguito su un campione prelevato con un tampone a livello naso/oro-faringeo, e quindi analizzato attraverso metodi molecolari di real-time RT-PCR (Reverse Transcription-Polymerase Chain Reaction) per l’amplificazione dei geni virali maggiormente espressi durante l’infezione.</p>
                    </div>
                  </div>
             </div>
        </div>
        <!--2-->
        <div class="row no-gutters">
             <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                  <!--text-->
                  <div class="position">
                    <div class="container">
                      <h1 class="text-center">Tampone antigenico (rapido)</h1>
                      <p class="text-justify">Questa tipologia di test è basata sulla ricerca, nei campioni respiratori, di proteine virali (antigeni). Le modalità di raccolta del campione sono del tutto analoghe a quelle dei test molecolari (tampone naso-faringeo), i tempi di risposta sono molto brevi (circa 15 minuti), ma la sensibilità e specificità di questo test sembrano essere inferiori a quelle del test molecolare.</p>
                    </div>
                  </div>
             </div>
             <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                  <!--img-->
                  <img src="Images/guida2.png" alt="demo-img" class="img-fluid w-100">
             </div>
        </div>
   </div>  
</section>


</body>

</html>