@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="sweetalert2.min.css">

@endsection
@section('header')
<div class="col-lg-6 col-7">
    <h6 class="h2 text-white d-inline-block mb-0">Exportar Liquidaciones</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
      <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="#">Exportar Liquidación</a></li>
        <li class="breadcrumb-item active" aria-current="page">Exportar Liquidacion de Choferes</li>
      </ol>
    </nav>
</div>
@endsection
@section('content')
<div class="card">
    <!-- Card header -->
    @if (session()->has('success'))
        <input type="hidden" id="success" value="{{ session()->get('success') }}" >
    @else
        <input type="hidden" id="success" value="">
    @endif
    <div class="card-header">
      <h3 class="mb-0">Obetner lista de liquidaciones</h3>
    </div>
    <!-- Card body -->
    <div class="card-body">
      <!-- Single -->
        <form action="/liquidaciones/getLiquidaciones" id="formularioExport" method="post">
            @csrf
            <div class="form-group">
                <label class="" for="">Pulsar Botón para exportar Excel</label>
                
            </div>
            <div class="text-left">
                <button type="submit" id="" class="btn btn-primary">Exportar Excel</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('js')

<script>
    var formularioExport = document.getElementById('formularioExport');
    formularioExport.addEventListener('submit', function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Estás seguro de generar una petición de Liquidación?',
            text: "¡No podrás revertir esto!, Las wallet de los choferes serán actualizadas",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '¡Sí, exportar!'
        }).then((result) => {
            if (result.value) {
                formularioExport.submit();
            }
        })
    });

   
 

</script>
    
@endsection