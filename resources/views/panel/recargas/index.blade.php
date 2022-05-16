@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="sweetalert2.min.css">

@endsection
@section('header')
<div class="col-lg-6 col-7">
    <h6 class="h2 text-white d-inline-block mb-0">Recarga de créditos</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
      <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="#">Recarga de créditos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Cargar Excel de Referencias Bancarias</li>
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
      <h3 class="mb-0">Cargar Excel de Referencias Bancarias</h3>
    </div>
    <!-- Card body -->
    <div class="card-body">
      <!-- Single -->
        <form action="/recargas/subirReferencias" method="post" enctype="multipart/form-data">
            @csrf
            <div class="custom-file">
                <input type="file" class="custom-file-input" name="archivo">
                <label class="custom-file-label" for="projectCoverUploads">Insertar Excel bancario</label>
            </div>
            <div class="text-left">
                <button type="submit" class="btn btn-primary mt-4">Cargar Excel</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('js')

<script>
  $('input[type="file"]').change(function(e){
    var fileName = e.target.files[0].name;
    $('.custom-file-label').html(fileName);
  });

  $(document).ready(function () {

    if ($('#success').val() != '') {
      Swal.fire({
        title: 'Existoso!',
        text: $('#success').val(),
        type: 'success',
        confirmButtonText: 'Aceptar'
      })
    }


   
  });

</script>
    
@endsection