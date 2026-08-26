let tablaBitacora = null;


document.addEventListener('DOMContentLoaded', function () {

    inicializarTabla();

    cargarBitacora();


    document
        .getElementById('btnBuscar')
        .addEventListener('click', cargarBitacora);


    document
        .getElementById('btnActualizar')
        .addEventListener('click', cargarBitacora);


    document
        .getElementById('btnDescargarExcel')
        .addEventListener('click', descargarExcel);

});


function inicializarTabla() {

    tablaBitacora = $('#tablaBitacora').DataTable({
        "dom": '<"botones"B><"filtro"f><"ctabla"rt><"pie"ip>',
        "buttons": [{
                        extend: 'excelHtml5',
                        title: 'Reporte_Gestiones_Simp_INR',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },
                    {
                        extend: 'pdfHtml5',
                        messageTop: 'Reporte_Gestiones_Simp_INR',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        download: 'open'
                    },
                    {
                        extend: 'print',
                        messageTop: 'Reporte_Gestiones_Simp_INR',
                        orientation: 'landscape',
                        exportOptions: {columns: ':visible'},
                        customize: function ( win ) {
                            $(win.document.body)
                                .css( 'font-size', '10pt' );
                            $(win.document.body).find( 'table' )
                                .addClass( 'compact' )
                                .css( 'font-size', 'inherit' );
                        }
                    },
                    'colvis',
        ],
        "paging": true, // Activa la paginación
        "lengthChange": false, // Oculta el menú de selección de entradas
        "pageLength": 10,      
        "order": [[0, 'desc']],
        "language": {
            url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
        },
        "columnDefs": [{
                    targets: [10],
                    orderable: false,
                    searchable: false
                }]
    });
}


async function cargarBitacora() {

    mostrarCarga(true);


    try {

        const parametros = new URLSearchParams({

            fechaDesde:
                document.getElementById('fechaDesde').value,

            fechaHasta:
                document.getElementById('fechaHasta').value,

            usuario:
                document.getElementById('usuario').value.trim(),

            accion:
                document.getElementById('accion').value,

            modulo:
                document.getElementById('modulo').value.trim()

        });


        const response = await fetch(
            `${base_url}/backend/bitacora/listarBitacoraUsuarios.php?${parametros}`
        );


        const texto = await response.text();

        //console.log(texto);
        if (!response.ok) {

            console.error(texto);

            throw new Error(
                `Error HTTP ${response.status}`
            );

        }


        const resultado = JSON.parse(texto);


        if (!resultado.success) {

            throw new Error(
                resultado.message ||
                'No fue posible obtener la bitácora.'
            );

        }


        actualizarKPI(resultado.kpi);

        llenarTabla(resultado.data);


    } catch (error) {

        console.error(
            'Error cargando bitácora:',
            error
        );

        alert(
            'No fue posible cargar la bitácora.'
        );

    } finally {

        mostrarCarga(false);

    }

}


function actualizarKPI(kpi) {

    document.getElementById('kpiTotal').textContent =
        kpi.total.toLocaleString('es-EC');


    document.getElementById('kpiOK').textContent =
        kpi.ok.toLocaleString('es-EC');


    document.getElementById('kpiDescargas').textContent =
        kpi.descargas.toLocaleString('es-EC');


    document.getElementById('kpiErrores').textContent =
        kpi.errores.toLocaleString('es-EC');

}


function llenarTabla(registros) {

    tablaBitacora.clear();


    registros.forEach(function (registro) {

        tablaBitacora.row.add([

            formatearFecha(
                registro.fecha_evento
            ),

            escaparHTML(
                registro.nickname || '-'
            ),

            badgeAccion(
                registro.accion
            ),

            escaparHTML(
                registro.modulo || '-'
            ),

            escaparHTML(
                registro.descripcion || '-'
            ),

            escaparHTML(
                registro.nombre_archivo || '-'
            ),

            escaparHTML(
                registro.tipo_archivo || '-'
            ),

            formatearTamano(
                registro.tamanio_archivo
            ),

            escaparHTML(
                registro.ip || '-'
            ),

            badgeEstado(
                registro.estado
            ),

            `
            <button
                type="button"
                class="btn btn-sm btn-primary btn-detalle"
                data-id="${registro.id}"
                title="Ver detalle">

                <i class="fas fa-eye"></i>

            </button>
            `

        ]);

    });


    tablaBitacora.draw();


    document
        .querySelectorAll('.btn-detalle')
        .forEach(function (boton) {

            boton.addEventListener(
                'click',
                function () {

                    const id =
                        Number(
                            this.dataset.id
                        );

                    const registro =
                        registros.find(
                            item =>
                                Number(item.id) === id
                        );

                    mostrarDetalle(registro);

                }
            );

        });

}


function mostrarDetalle(registro) {

    if (!registro) {
        return;
    }


    document.getElementById('detRequestId').textContent =
        registro.request_id || '-';


    document.getElementById('detUsuario').textContent =
        registro.nickname || '-';


    document.getElementById('detCorreo').textContent =
        registro.correo || '-';


    document.getElementById('detDireccion').textContent =
        registro.direccion || '-';


    document.getElementById('detRol').textContent =
        registro.rol || '-';


    document.getElementById('detIP').textContent =
        registro.ip || '-';


    document.getElementById('detHost').textContent =
        registro.host_cliente || '-';


    document.getElementById('detSession').textContent =
        registro.session_id || '-';


    document.getElementById('detFecha').textContent =
        formatearFecha(registro.fecha_evento);


    document.getElementById('detDescripcion').textContent =
        registro.descripcion || '-';


    document.getElementById('detMensaje').textContent =
        registro.mensaje || '-';


    document.getElementById('detUserAgent').textContent =
        registro.user_agent || '-';


    let datosExtra = registro.datos_extra;


    if (datosExtra) {

        try {

            datosExtra =
                JSON.stringify(
                    JSON.parse(datosExtra),
                    null,
                    4
                );

        } catch (e) {

            // Se mantiene el contenido original

        }

    } else {

        datosExtra = '-';

    }


    document.getElementById('detDatosExtra').textContent =
        datosExtra;


    const modal =
        new bootstrap.Modal(
            document.getElementById('modalDetalle')
        );


    modal.show();

}


function badgeAccion(accion) {

    const valor =
        escaparHTML(accion || '-');


    let clase = 'bg-secondary';


    switch (accion) {

        case 'DESCARGA':
            clase = 'bg-primary';
            break;

        case 'LOGIN':
            clase = 'bg-success';
            break;

        case 'LOGOUT':
            clase = 'bg-secondary';
            break;

        case 'ELIMINACION':
            clase = 'bg-danger';
            break;

        case 'MODIFICACION':
            clase = 'bg-warning text-dark';
            break;

        case 'CONSULTA':
            clase = 'bg-info text-dark';
            break;

    }


    return `
        <span class="badge ${clase}">
            ${valor}
        </span>
    `;

}


function badgeEstado(estado) {

    if (estado === 'OK') {

        return `
            <span class="badge bg-success">
                OK
            </span>
        `;

    }


    return `
        <span class="badge bg-danger">
            ${escaparHTML(estado || 'ERROR')}
        </span>
    `;

}


function formatearTamano(bytes) {

    if (!bytes || bytes <= 0) {
        return '-';
    }


    const unidades = [
        'B',
        'KB',
        'MB',
        'GB',
        'TB'
    ];


    const indice =
        Math.floor(
            Math.log(bytes) /
            Math.log(1024)
        );


    return (
        bytes /
        Math.pow(1024, indice)
    ).toFixed(2)
    + ' '
    + unidades[indice];

}


function formatearFecha(fecha) {

    if (!fecha) {
        return '-';
    }


    const fechaObj =
        new Date(
            fecha.replace(' ', 'T')
        );


    if (isNaN(fechaObj)) {
        return fecha;
    }


    return fechaObj.toLocaleString(
        'es-EC'
    );

}


function escaparHTML(valor) {

    return String(valor)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

}


function mostrarCarga(mostrar) {

    document.getElementById(
        'spinnerCarga'
    ).style.display =
        mostrar ? 'inline-block' : 'none';

}


function descargarExcel() {

    const parametros = new URLSearchParams({

        fechaDesde:
            document.getElementById('fechaDesde').value,

        fechaHasta:
            document.getElementById('fechaHasta').value,

        usuario:
            document.getElementById('usuario').value.trim(),

        accion:
            document.getElementById('accion').value,

        modulo:
            document.getElementById('modulo').value.trim()

    });


    window.open(
        `${base_url}/backend/bitacora/descargarBitacoraUsuarios.php?${parametros}`,
        '_blank'
    );

}