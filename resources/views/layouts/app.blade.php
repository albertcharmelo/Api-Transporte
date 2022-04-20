

 <!DOCTYPE html>
 <html>
 
 <head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Panel ASAD | Atubuses Online</title>
   <!-- Favicon -->
   <link rel="icon" href="/img/brand/favicon.png" type="image/png">
   <!-- Fonts -->
   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
   <!-- Icons -->
   <link rel="stylesheet" href="/vendor/nucleo/css/nucleo.css" type="text/css">
   <link rel="stylesheet" href="/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <link rel="stylesheet" href="/vendor/sweetalert2/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="/vendor/select2/dist/css/select2.min.css">
   <!-- Page plugins -->
   <!-- Argon CSS -->
  <link rel="stylesheet" href="/vendor/animate.css/animate.min.css">
   <link rel="stylesheet" href="/css/argon.css?v=1.1.0" type="text/css">
   <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
   @yield('css')
 </head>
 
 <body>
   <!-- Sidenav -->
   <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
     <div class="scrollbar-inner">
       <!-- Brand -->
       <div class="sidenav-header d-flex align-items-center">
         <a class="navbar-brand" href="../../pages/dashboards/dashboard.html">
           <img src="/img/brand/blue.png" class="navbar-brand-img" alt="...">
         </a>
         <div class="ml-auto">
           <!-- Sidenav toggler -->
           <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
             <div class="sidenav-toggler-inner">
               <i class="sidenav-toggler-line"></i>
               <i class="sidenav-toggler-line"></i>
               <i class="sidenav-toggler-line"></i>
             </div>
           </div>
         </div>
       </div>
      @include('panel.partials.navbar')
     </div>
   </nav>
   <!-- Main content -->
   <div class="main-content" id="panel">
     <!-- Topnav -->
     <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
       <div class="container-fluid">
         <div class="collapse navbar-collapse" id="navbarSupportedContent">
          
           <!-- Navbar links -->
           <ul class="navbar-nav align-items-center ml-md-auto">
             <li class="nav-item d-xl-none">
               <!-- Sidenav toggler -->
               <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                 <div class="sidenav-toggler-inner">
                   <i class="sidenav-toggler-line"></i>
                   <i class="sidenav-toggler-line"></i>
                   <i class="sidenav-toggler-line"></i>
                 </div>
               </div>
             </li>
         
           </ul>
           
         </div>
       </div>
     </nav>
     <!-- Header -->
     <!-- Header -->
     <div class="header bg-primary pb-6">
       <div class="container-fluid">
         <div class="header-body">
           <div class="row align-items-center py-4">
            
             @yield('header')
           </div>
         </div>
       </div>
     </div>
     <!-- Page content -->
     <div class="container-fluid mt--6">
       
        @yield('content')
       <!-- Footer -->
       <footer class="footer pt-0">
         <div class="row align-items-center justify-content-lg-between">
           <div class="col-lg-6">
             <div class="copyright text-center text-lg-left text-muted">
               &copy; 2019 <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">Creative Tim</a>
             </div>
           </div>
           <div class="col-lg-6">
             <ul class="nav nav-footer justify-content-center justify-content-lg-end">
               <li class="nav-item">
                 <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
               </li>
               <li class="nav-item">
                 <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
               </li>
               <li class="nav-item">
                 <a href="http://blog.creative-tim.com" class="nav-link" target="_blank">Blog</a>
               </li>
               <li class="nav-item">
                 <a href="https://www.creative-tim.com/license" class="nav-link" target="_blank">License</a>
               </li>
             </ul>
           </div>
         </div>
       </footer>
     </div>
   </div>
   <!-- Argon Scripts -->
   <!-- Core -->
   <script src="/vendor/jquery/dist/jquery.min.js"></script>
   <script src="/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
   <script src="/vendor/js-cookie/js.cookie.js"></script>
   <script src="/vendor/sweetalert2/dist/sweetalert2.min.js"></script>
   <script src="/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
   <script src="/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
   <!-- Optional JS -->
   <script src="/vendor/chart.js/dist/Chart.min.js"></script>
   <script src="/vendor/chart.js/dist/Chart.extension.js"></script>
  <script src="/vendor/select2/dist/js/select2.min.js"></script>

   <!-- Argon JS -->
   <script src="/js/argon.js?v=1.1.0"></script>
   <!-- Demo JS - remove this in your project -->
   <script src="/js/demo.min.js"></script>
   @yield('js')
 </body>
 
 </html>