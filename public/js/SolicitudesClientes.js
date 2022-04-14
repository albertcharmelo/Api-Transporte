$(document).ready(function () {
    getSolicitudesChoferes();
});


function getSolicitudesChoferes(page = null) {
    let url;
    page != null ? url = page : url = '/getsolicitudeschoferes';

  
    $.ajax({
        url: url,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (resp) {
            console.log(resp)
            loadSolicitudesChoferes(resp.data);
            loadPagination(resp.prev_page_url,resp.next_page_url);
            
          
        }
    });

}

function loadPagination(prev_page,next_page){
    let html = '';
    if(prev_page != null){
        html+=`<li class="page-item" >
        <a class="page-link" href="#!"  aria-label="Previous" onclick="getSolicitudesChoferes('${prev_page}')">
          <span aria-hidden="true">&laquo;</span>
          <span class="sr-only">Previous</span>
        </a>
      </li>`;
    }
    if(next_page != null){
        html+=`<li class="page-item">
        <a class="page-link" href="#!" aria-label="Next"  onclick="getSolicitudesChoferes('${next_page}')">
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
        let status = `
        <td>
        <span class="badge badge-dot mr-4">
          <i class="bg-success"></i>
          <span class="status">completed</span>
        </span>
      </td>
        `;


        
        if (solicitud.estado_solicitud == "PENDIENTE") {
            status = `
            <td>
                <span class="badge badge-dot mr-4">
                    <i class="bg-warning"></i>
                    <span class="status">Pendiente</span>
                </span>
            </td>
            <td class="table-actions">
            <a href="#!" class="table-action table-action-approve" onclick="aceptarSolicitud(${solicitud.id})" data-toggle="tooltip" data-original-title="Aceptar Solicitud">
               <i class="fas fa-check"></i>
            </a>
            <a href="#!" class="table-action table-action-delete" onclick="eliminarSolicitud(${solicitud.id})" data-toggle="tooltip" data-original-title="Eliminar Solicitud">
              <i class="fas fa-trash"></i>
            </a>
          </td>
            `;
        }else{
            status = `
            <td>
                <span class="badge badge-dot mr-4">
                <i class="bg-success"></i>
                <span class="status">Aprovado</span>
                </span>
            </td>
            `;
        }



        html+=`<tr>
            <td class="table-user" onclick="showDataSolicitud(${solicitud.id})">
            <img src="/img/driver.png" class="avatar rounded-circle mr-3">
            <b>${solicitud.name_user}</b>
            </td>
            <td>
            <span class="text-muted">${solicitud.user.email}</span>
            </td>       
            ${status}
        </tr>`;
    }
    $('#listSolicitudes').empty()
    $('#listSolicitudes').append(html);
}

function eliminarSolicitud(id){
    $.ajax({
        url: '/deleteSolicitud',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            id: id
        },
        success: function (resp) {
            getSolicitudesChoferes();
        }
    });
}

function showDataSolicitud(id){
    $.ajax({
        url: '/getsolicitudData',
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
    $('#modalDatosSolicitudesChofer').modal('show');
    $('#choferName').val(data.name_user);
    $('#choferEmail').val(data.user.email);
    $('#choferPlaca').val(data.placa);
    $('#choferMarca').val(data.marca_vehiculo);
    $('#choferAño').val(data.año_vehiculo);
    $('#choferCombustible').val(data.tipo_combustible);
}

function aceptarSolicitud(id){
    $.ajax({
        url: '/aceptarsolicitud',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            id: id
        },
        success: function (resp) {
            getSolicitudesChoferes();
            $('#modalDatosSolicitudesChofer').modal('hide');
            Swal.fire({
                title: 'Solicitud Aceptada',
                text: 'La solicitud de chófer ha sido aceptada',
                type:"success",
                confirmButtonText: 'OK'
            })
        }
    });


}


function searchSolicitudes(){
    let search = $('#searchSolicitudes').val();
    if(search != ''){
        $.ajax({
            url: '/searchsolicitudes',
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
        getSolicitudesChoferes();
    }
 
}