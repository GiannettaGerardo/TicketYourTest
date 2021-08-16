<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title> Accedi </title>

   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
   <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

   <link rel="stylesheet" href="css/stile2.css">

   <style>
      
      header {
         position: fixed !important;
         top: 0;
         left: 0;
         z-index: 2 !important;

      }

      body {
         overflow: hidden;
      }

      .creaProfilo:hover {
         color: white;
      }
      
   </style>

</head>

<body>

   <x-header.header />

   <div class="sidenav">
      <div class="login-main-text">
         <img src="images/logo.png" class="img">
      </div>
   </div>
   <div class="main">
      <div class="col-md-6 col-sm-12">
         <div class="login-form">
            <form method="post" action="{{ route('login.auth') }}">
               @csrf

               @error('email')
               <span>{{ $message }}</span><br>
               @enderror
               @error('password')
               <span>{{ $message }}</span><br>
               @enderror
               @if (Session::has('email'))
               <span>{{ Session::get('email') }}</span><br>
               @endif
               @if (Session::has('password'))
               <span>{{ Session::get('password') }}</span><br>
               @endif

               <div class="form-group">
                  <label>E-mail</label>
                  <input type="email" class="form-control" placeholder="E-mail" name="email" id="email" required>
               </div>
               <div class="form-group">
                  <label>Password</label>
                  <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
               </div>
               <button type="submit" class="btn btn-black">Accedi</button>
               <br>

            </form>
            <hr color="#2C8F5B">
            <a href="{{url('registrazioneCittadino')}}" class="btn btn-black creaProfilo"> Crea nuovo account </a>
         </div>
      </div>
   </div>

</body>

</html>