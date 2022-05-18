@extends('layouts.app')
@section('css')
@endsection
@section('header')
<div class="header pb-6 d-flex align-items-center"
    style="min-height: 500px; background-image: url(../../assets/img/theme/profile-cover.jpg); background-size: cover; background-position: center top;">
    <!-- Mask -->
    <span class="mask bg-gradient-default opacity-8"></span>
    <!-- Header container -->
    <div class="container-fluid d-flex align-items-center">
        <div class="row">
            <div class="col-lg-7 col-md-10">
                <h1 class="display-2 text-white">Hola {{ auth()->user()->full_name }}</h1>
                <p class="text-white mt-0 mb-5">Esta es tu pagina de perfil donde podrás modificar tus datos y a su vez
                    agregar tus datos bancarios para la liquidación</p>
            </div>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="container-fluid mt--6">
    <div class="row">
       
        <div class="col-xl-12 order-xl-1">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card bg-gradient-info border-0">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0 text-white">Créditos Recaudados</h5>
                                    <span class="h2 font-weight-bold mb-0 text-white">{{ auth()->user()->wallet->creditos }} Bs.</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                                        <i class="fas fa-coins"></i>
                                    </div>
                                </div>
                            </div>
                            {{-- <p class="mt-3 mb-0 text-sm">
                                <span class="text-white mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                <span class="text-nowrap text-light">Since last month</span>
                            </p> --}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card bg-gradient-danger border-0">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0 text-white">Linea de Transporte</h5>
                                    <span class="h2 font-weight-bold mb-0 text-white">{{ $user_linea_transporte->linea_nombre }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                                        <i class="fas fa-road"></i>
                                    </div>
                                </div>
                            </div>
                            {{-- <p class="mt-3 mb-0 text-sm">
                                <span class="text-white mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                <span class="text-nowrap text-light">Since last month</span>
                            </p> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h3 class="mb-0">Mi perfil </h3>
                        </div>
            
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="/miperfil/actualizarDatos" >
                        @csrf
                        <h6 class="heading-small text-muted mb-4">Información del usuario</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Nombre Completo</label>
                                        <input type="text" id="input-username" name="full_name" class="form-control"
                                            placeholder="Nombre del usuario" value="{{ auth()->user()->full_name }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-email">Correo Eléctronico</label>
                                        <input type="email" id="input-email" name="email" value="{{ auth()->user()->email }}" class="form-control"
                                            placeholder="Correo Eléctronico">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-first-name">Cédula de identidad</label>
                                        <input type="text" id="input-first-name" readonly class="form-control"
                                            placeholder="Cédula de identidad" value="{{ auth()->user()->type_id_card }}-{{ auth()->user()->id_card }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-last-name">Género</label>
                                        {{-- <input type="text" id="input-last-name" class="form-control"
                                            placeholder="Last name" value="Jesse"> --}}
                                            <select name="gender" class="form-control" id="">
                                                <option @if (auth()->user()->gender == 'NO ESPECIFICO')
                                                    selected
                                                @endif value="NO ESPECIFICO">No especifico</option>
                                                <option @if (auth()->user()->gender == 'MALE')
                                                    selected
                                                @endif value="MALE">Masculino</option>
                                                <option @if (auth()->user()->gender == 'FEMALE')
                                                    selected
                                                @endif value="FEMALE">Femenino</option>
                                            </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4" />
                        <!-- Address -->
                        <h6 class="heading-small text-muted mb-4">Información Bancaria</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-address">Número de cuenta</label>
                                        <input id="input-address" class="form-control" placeholder="Número de cuenta bancaria"
                                            value="
                                                @if(auth()->user()->datos_bancarios != null)
                                                {{ auth()->user()->datos_bancarios->numero_de_cuenta}}
                                                @else
                                                ""
                                                @endif" name="numero_de_cuenta" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="20" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-first-name">Cédula de identidad</label>
                                        <input type="text" id="input-first-name" name="id_card" readonly class="form-control"
                                            placeholder="Cédula de identidad" value="{{ auth()->user()->type_id_card }}-{{ auth()->user()->id_card }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-country">Tipo de cuenta</label>
                                        <select name="tipo_cuenta" class="form-control" id="">
                                            @if(auth()->user()->datos_bancarios == null)
                                                <option disabled selected value="">Seleccione el tipo de cuenta</option>
                                            @endif

                                            <option @if ( auth()->user()->datos_bancarios != null && auth()->user()->datos_bancarios->tipo_cuenta == 'AHORRO')
                                                selected
                                            @endif value="AHORRO">Ahorro</option>
                                            <option @if ( auth()->user()->datos_bancarios != null && auth()->user()->datos_bancarios->tipo_cuenta == 'CORRIENTE')
                                                selected
                                            @endif value="CORRIENTE">Corriente</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-country">Entidad Bancaria</label>
                                        <input type="text" id="input-postal-code" name="banco" readonly class="form-control"
                                            value="Bancamiga Banco Universal">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4" />
                        <button type="submit" class="btn btn-primary ">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')

@endsection