@extends('layouts.app')
@section('css')
    
@endsection
@section('header')
<div class="col-lg-6 col-7">
    <h6 class="h2 text-white d-inline-block mb-0">Chófer</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
      <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="#">Chófer</a></li>
        <li class="breadcrumb-item active" aria-current="page">lista de chóferes</li>
      </ol>
    </nav>
</div>
@endsection
@section('content')
{{-- @include('panel.partials.modalDataSolicitudesChofer') --}}
@include('panel.partials.modalAsignarLineaAutobuses')

<div class="card">
  <!-- Card header -->
  <div class="card-header border-0">
    <div class="row">
      <div class="col-6">
        <h3 class="mb-0">Lista de Chóferes</h3>
      </div>
      <div class="col-6 text-right">
        <div class="form-group mb-0">
          <div class="input-group input-group-alternative input-group-merge">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
            <input class="form-control" id="searchChofere" onkeyup="searchChofer()" placeholder="Buscar Chofer Aquí" type="text">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Light table -->
  
    <div class="table-responsive">
      <table class="table align-items-center table-flush">
        <thead class="thead-light">
          <tr>
            <th>Nombre Completo</th>
            <th>Correo Eléctronico</th>
            <th>Estado de solicitud</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody id="listaChoferes">
        </tbody>
      </table>
    </div>
  


  <div class="card-footer py-4">
    <nav aria-label="...">
      <ul class="pagination justify-content-end mb-0" id="pagination">
      </ul>
    </nav>

    

  </div>
</div>
    
@endsection 
@section('js')

    <script src="/vendor/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="{{ asset('js/ListaChoferes.js') }}"></script>
@endsection