@extends('layouts.app')
@section('css')
@endsection
@section('header')
<div class="col-lg-6 col-7">
    <h6 class="h2 text-white d-inline-block mb-0">Línea de Autobuses</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
      <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="#">Línea de Autobuses</a></li>
        <li class="breadcrumb-item active" aria-current="page">lista de lineas de autobuses</li>
      </ol>
    </nav>
</div>
@endsection
@section('content')
@include('panel.partials.modalCrearLineaBus')
<div class="card">
  <!-- Card header -->
  <div class="card-header border-0">
    <div class="row">
      <div class="col-6">
        <h3 class="mb-0">Lista de linea de Autobuses</h3>
      </div>
  
      <div class="col-6 text-right">
        <a href="#" class="btn btn-sm btn-primary btn-round btn-icon" onclick="limpiarDatos()" data-toggle="tooltip" data-original-title="Crear línea de bus">
          <span class="btn-inner--icon"><i class="fas fa-bus"></i></span>
          <span class="btn-inner--text"  data-toggle="modal" data-target="#modalCrearLineaBus">Nueva Línea de bus</span>
        </a>
      </div>
    </div>
  </div>
  <!-- Light table -->
  
    <div class="table-responsive">
      <table class="table align-items-center table-flush">
        <thead class="thead-light">
          <tr>
            <th>Nombre de la línea</th>
            <th>Tarífas</th>
            <th>Total de Chóferes</th>
            
          </tr>
        </thead>
        <tbody id="listaDeLineas">
        </tbody>
      </table>
    </div>
  


  <div class="card-footer py-4">
  </div>
</div>
@endsection
@section('js')
<script src="{{ asset('js/LineaTransporte.js') }}"></script>
@endsection

