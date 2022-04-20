 <!DOCTYPE html>
 <html>
 
 <head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
   <meta name="author" content="Creative Tim">
   <title>Panel | ASAD</title>
   <!-- Fonts -->
   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
   <!-- Icons -->
   <link rel="stylesheet" href="/vendor/nucleo/css/nucleo.css" type="text/css">
   <link rel="stylesheet" href="/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
   <!-- Argon CSS -->
   <link rel="stylesheet" href="/css/argon.css?v=1.1.0" type="text/css">
 </head>
 
 <body class="bg-default">
   <!-- Main content -->
   <div class="main-content">
     <!-- Header -->
     <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
       <div class="container">
         <div class="header-body text-center mb-7">
           <div class="row justify-content-center">
             <div class="col-xl-5 col-lg-6 col-md-8 px-5">
               <h1 class="text-white">Bienvenidos!</h1>
               <p class="text-lead text-white">Para acceder al panel administrativo de la app, complete el siguiente formulario</p>
             </div>
           </div>
         </div>
       </div>
       <div class="separator separator-bottom separator-skew zindex-100">
         <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
           <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
         </svg>
       </div>
     </div>
     <!-- Page content -->
     <div class="container mt--8 pb-5">
       <div class="row justify-content-center">
         <div class="col-lg-5 col-md-7">
           <div class="card bg-secondary border-0 mb-0">
   
             <div class="card-body px-lg-5 py-lg-5">
             
                <form role="form" method="POST" action="{{ route('login') }}">
                @csrf
                 <div class="form-group mb-3">
                   <div class="input-group input-group-merge input-group-alternative">
                     <div class="input-group-prepend">
                       <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                     </div>
                     <input id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Correo electrónico" type="email" name="email" value="{{ old('email') }}" autocomplete="email" autofocus >
                     @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    </div>
                 </div>
                 <div class="form-group">
                   <div class="input-group input-group-merge input-group-alternative">
                     <div class="input-group-prepend">
                       <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                     </div>
                     <input id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Contraseña" type="password" name="password" required autocomplete="current-password">
                     @error('password')
                     <span class="invalid-feedback" role="alert">
                         <strong>{{ $message }}</strong>
                     </span>
                    @enderror
                   </div>
                 </div>
                 <div class="custom-control custom-control-alternative custom-checkbox">
                   <input class="custom-control-input" id=" customCheckLogin" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                   <label class="custom-control-label" for=" customCheckLogin">
                     <span class="text-muted">Recordar mis datos</span>
                   </label>
                 </div>
                 <div class="text-center">
                   <button type="submit" class="btn btn-primary my-4">Iniciar sesión</button>
                   <span class="d-block"><a href="/password/reset">¿Olvidaste tu contraseña?</a></span>
                 </div>
               </form>
             </div>
           </div>
           <div class="col-12 d-flex justify-content-center">
            <img src="{{ asset('img/andorid.png') }}" height="100" alt="">
            <img src="{{ asset('img/DownloadApkpng.png') }}" height="100" alt="">
          </div>
         </div>
        
       </div>
     </div>
   </div>

   <!-- Core -->
   <script src="/vendor/jquery/dist/jquery.min.js"></script>
   <script src="/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
   <script src="/vendor/js-cookie/js.cookie.js"></script>
   <script src="/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
   <script src="/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
   <!-- Argon JS -->
   <script src="/js/argon.js?v=1.1.0"></script>
   <!-- Demo JS - remove this in your project -->
   <script src="/js/demo.min.js"></script>
 </body>
 
 </html>