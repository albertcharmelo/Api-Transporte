$(document).ready(function () {
    getChoferes();
});
function NotifiacionSuccess(titulo,mensaje, placement, align, icon, type, animIn, animOut) {
    $.notify({
        icon: icon,
        title: titulo,
        message: mensaje,
        url: ''
    }, {
        element: 'body',
        type: type,
        allow_dismiss: true,
        placement: {
            from: placement,
            align: align
        },
        offset: {
            x: 15, // Keep this as default
            y: 15 // Unless there'll be alignment issues as this value is targeted in CSS
        },
        spacing: 10,
        z_index: 1080,
        delay: 2500,
        timer: 25000,
        url_target: '_blank',
        mouse_over: false,
        animate: {
            // enter: animIn,
            // exit: animOut
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        },
        template: '<div data-notify="container" class="alert alert-dismissible alert-{0} alert-notify" role="alert">' +
            '<span class="alert-icon" data-notify="icon"></span> ' +
            '<div class="alert-text"</div> ' +
            '<span class="alert-title" data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '</div>' +
            // '<div class="progress" data-notify="progressbar">' +
            // '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            // '</div>' +
            // '<a href="{3}" target="{4}" data-notify="url"></a>' +
            '<button type="button" class="close" data-notify="dismiss" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '</div>'
    });
}

function getChoferes(page = null) {
    let url;
    page != null ? url = page : url = '/choferes/get';

  
    $.ajax({
        url: url,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (resp) {
            loadSolicitudesChoferes(resp.data);
            loadPagination(resp.prev_page_url,resp.next_page_url);
        }
    });

}

function loadPagination(prev_page,next_page){
    let html = '';
    if(prev_page != null){
        html+=`<li class="page-item" >
        <a class="page-link" href="#!"  aria-label="Previous" onclick="getChoferes('${prev_page}')">
          <span aria-hidden="true">&laquo;</span>
          <span class="sr-only">Previous</span>
        </a>
      </li>`;
    }
    if(next_page != null){
        html+=`<li class="page-item">
        <a class="page-link" href="#!" aria-label="Next"  onclick="getChoferes('${next_page}')">
          <span aria-hidden="true">&raquo;</span>
          <span class="sr-only">Next</span>
        </a>
      </li>`;
    }
    $('#pagination').empty()
    $('#pagination').append(html);

}
            



function loadSolicitudesChoferes(data){
    let html = '';
    
    for (const solicitud of data) {
    
        html+=`<tr>
            <td class="table-user">
            <img src="/img/driver.png" class="avatar rounded-circle mr-3">
            <b>${solicitud.name_user}</b>
            </td>
            <td>
            <span class="text-muted">${solicitud.user.email}</span>
            </td>     
            <td>
                <span class="text-muted">${solicitud.placa}</span>
            </td>
            <td>
            <a href="#!" class="table-action table-action-approve" onclick="ShowDataChofer(${solicitud.user_id})" data-toggle="tooltip" data-original-title="Aceptar Solicitud">
            <i class="fas fa-exchange-alt"></i>
            </a>
            </td>
        </tr>`;
    }  
    $('#listaChoferes').empty()
    $('#listaChoferes').append(html);
}

function changeLinea(){
    //show modal modalAsignarBusChofer
    $('#modalAsignarBusChofer').modal('show');
    let id = $('#listLineaAutobuses').val()
    
    $.ajax({
        url: '/choferes/changeLinea',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            id:$('#choferID').val(),
            lineaTransporte_id: id
        },
        success: function (resp) {
            NotifiacionSuccess('Asignado Satisfactoriamente','Se asigno la linea de transporte al chofer', 'top','center','ni ni-bell-55','success');
            
            if(resp.status == 'success'){
                $('#modalDatosSolicitudesChofer').modal('hide');
                getChoferes();
            }
        }
    });

}





function ShowDataChofer(id){
    $.ajax({
        url: '/getDatosChoferes',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            id: id
        },
        success: function (resp) {
            showModalDataSolicitud(resp)
            
        }
    });


}


function showModalDataSolicitud(data){
    $('#modalAsignarBusChofer').modal('show');
    $('#choferName').val(data.datos.name_user);
    $('#choferEmail').val(data.datos.user.email);
    $('#choferPlaca').val(data.datos.placa);
    $('#choferMarca').val(data.datos.marca_vehiculo);
    $('#choferAño').val(data.datos.año_vehiculo);
    $('#choferID').val(data.datos.user_id);
    $('#choferCombustible').val(data.datos.tipo_combustible);
    let lbuses = ''
    for (const lineasBus of data.lineasAutobuses) {
        if (data.datos.lineaTransporte == lineasBus.linea_nombre) {
            
            lbuses+=`<option value="${lineasBus.id}" selected >${lineasBus.linea_nombre}</option>`
        }else{
            lbuses+=`<option value="${lineasBus.id}">${lineasBus.linea_nombre}</option>`

        }
    }
    $('#listLineaAutobuses').empty()
    $('#listLineaAutobuses').append(lbuses)
}


function searchChofer(){
    let search = $('#searchChofere').val();
    if(search != ''){
        $.ajax({
            url: '/choferes/searchChofer',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                search: search
            },
            success: function (resp) {
                loadSolicitudesChoferes(resp.data);
                loadPagination(resp.prev_page_url,resp.next_page_url);
            }
        });
    }else{
        getChoferes();
    }
 
}


