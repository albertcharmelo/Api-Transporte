$(document).ready(function () {
    getLineasTransporte()
});


function getLineasTransporte(){
    $.ajax({
        type: "POST",
        url: "/lineaTransporte/getlineasTransporte",
        data: "",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            loadLineasTransporte(response);
        }
    });
}

function loadLineasTransporte (data){
    let html = '';
    for (const lineaTransporte of data) {
        
        let tarifas="";
        for (const tarifa of lineaTransporte.tarifas) {
            tarifas += `<a href="#" class="btn btn-sm btn-info mr-4 d-inline">${tarifa.tarifa} Bs</a>`
        }


        html+=`<tr>
        <td class="table-user">
        <svg class="avatar rounded-circle mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M573.19 402.67l-139.79-320C428.43 71.29 417.6 64 405.68 64h-97.59l2.45 23.16c.5 4.72-3.21 8.84-7.96 8.84h-29.16c-4.75 0-8.46-4.12-7.96-8.84L267.91 64h-97.59c-11.93 0-22.76 7.29-27.73 18.67L2.8 402.67C-6.45 423.86 8.31 448 30.54 448h196.84l10.31-97.68c.86-8.14 7.72-14.32 15.91-14.32h68.8c8.19 0 15.05 6.18 15.91 14.32L348.62 448h196.84c22.23 0 36.99-24.14 27.73-45.33zM260.4 135.16a8 8 0 0 1 7.96-7.16h39.29c4.09 0 7.53 3.09 7.96 7.16l4.6 43.58c.75 7.09-4.81 13.26-11.93 13.26h-40.54c-7.13 0-12.68-6.17-11.93-13.26l4.59-43.58zM315.64 304h-55.29c-9.5 0-16.91-8.23-15.91-17.68l5.07-48c.86-8.14 7.72-14.32 15.91-14.32h45.15c8.19 0 15.05 6.18 15.91 14.32l5.07 48c1 9.45-6.41 17.68-15.91 17.68z"/></svg>
        
        <b>${lineaTransporte.linea_nombre}</b>
        </td>
        <td>
            ${tarifas}
        </td>   
        <td>
            <span class="text-muted">${lineaTransporte.users.length} Chóferes Activos</span>
        </td>    
        
    </tr>`;


    }

    $('#listaDeLineas').empty().append(html);
}

var lineaAmount=[];
function agregarmonto(){
    
    let monto = $('#amountLinea').val();
    if (monto != 0 || monto != '') {
        lineaAmount.push(monto);
        $('#amountLinea').val('');
        actualizarListadeMontos(lineaAmount);
    }
    
}

function actualizarListadeMontos(data){
    let html = '<div><h3>Lista de Tarifas</h3></div>';

    for (const monto in data) {
        if (Object.hasOwnProperty.call(data, monto)) {
            const amount = data[monto];
            html+=`<a href="#" class="btn btn-sm btn-info mr-4 d-inline" onclick="retirarMonto(${monto})">${amount} Bs</a>`
        }
    }

    $('#listaDeMontos').empty().append(html);
}

function retirarMonto(position){
    console.log(position);
    lineaAmount.splice(position,1);
    actualizarListadeMontos(lineaAmount);
}

function guardarLineadeBus(){
    if (lineaAmount.length != 0 || lineaAmount.length != '') {
        $.ajax({
            type: "POST",
            url: "/lineaTransporte/guardarLinea",
            data: {
                linea_nombre: $('#linea_nombre').val(),
                lineaTarifas:lineaAmount
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                
                $('#modalCrearLineaBus').modal('hide');
            
                    Swal.fire({
                        title: 'Linea Creada Correctamente',
                        text: 'la linea de bus ha sido creada correctamente',
                        type:"success",
                        confirmButtonText: 'OK'
                    })
                    limpiarDatos();
                    getLineasTransporte();
             
            }
        });
    }
}

function limpiarDatos(){
    $('#linea_nombre').val('');
    lineaAmount=[];
    actualizarListadeMontos(lineaAmount);
}



function getLineaTransporte(id){
    $.ajax({
        type: "POST",
        url: "/lineaTransporte/getLinea",
        data: {
            id: id
        },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
            console.log(response);
            $('#linea_nombre').val(response.linea_nombre);
            lineaAmount = response.lineaTarifas;
            actualizarListadeMontos(lineaAmount);
        }
    });

}