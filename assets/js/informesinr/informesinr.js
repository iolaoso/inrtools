console.log("informesinr.js Funcionando");

function mostrarAnalista(isDirector) {
    const select = document.getElementById('analistaSelect');
    const label = document.getElementById('lbanalistaSelect');
    const tbNewTipoInf = document.getElementById('btCrearTinf');
    const deleteButtons = document.querySelectorAll('.delete-btn');
    if (isDirector == 'DIRECTOR' || isDirector == 'ADMNINSTRADOR' || isDirector == 'SUPERUSER') {
        select.style.display = 'block'; // Muestra el select
        label.style.display ='block';   
        tbNewTipoInf.style.display ='block'; // Muestra boton new Cat
        deleteButtons.forEach(button => {
            button.style.display = 'block'; 
        });
    }else{
        select.style.display = 'none';    // Oculta el select
        label.style.display ='none'; 
        tbNewTipoInf.style.display ='none';
        deleteButtons.forEach(button => {
            button.style.display = 'none'; // Oculta el botón
        });
    }
}

// Función para actualizar el valor del input basado en la selección del select
function actualizarAnalista() {
    const select = document.getElementById('analistaSelect');
    const input = document.getElementById('analista');
    //console.log(select.value);
    input.value = select.value; // Actualiza el input con el valor seleccionado
}

//eliminar registro
function eliminarInforme(boton) {
    const codInforme = boton.getAttribute('data-id');
    const nickname = boton.getAttribute('logUser');
    const url = baseurl + "/backend/informesinr/eliminarInformeinr.php?id=" + codInforme + "&delUser=" + nickname;
    if (confirm('¿Estás seguro de eliminar este registro?')) {
        fetch(url)
        .then(response => response.text())
        .then(data => {
            alert(data);
            // Recargar la tabla o el contenido según sea necesario
            location.reload(); // O puedes actualizar la tabla sin recargar
        })
        .catch(error => {
            alert("Error al eliminar el registro: " + error);
        });
    }
}

// botones editar
function editarInforme(boton){
    const codInforme = boton.getAttribute('data-id');
    const url = baseurl + "/backend/informesinr/informesinrList.php?id=" + codInforme;
    console.log(codInforme);
    fetch(url)
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error('Error: ' + response.status + ' - ' + text);
                });
            }
            return response.json();
        })
        .then(async informeData => {
            limpiarForm(frmInformesInr);
            // Rellenar los campos según sea necesario
            document.getElementById('codInforme').value = informeData.COD_INFORME;
            document.getElementById('ruc').value = informeData.RUC_ENTIDAD;
            document.getElementById('tbrazonSocial').value = informeData.RAZON_SOCIAL;
            document.getElementById('cbTipoInforme').value = informeData.COD_TIPO_INFORME;
            document.getElementById('areaRequiriente').value = informeData.AREA_REQUIRIENTE;
            document.getElementById('fasignacion').value = informeData.FECHA_ASIGNACION;
            document.getElementById('fsolicitaRev').value = informeData.FECHA_SOLICITUD_REVISION;
            const cadena = informeData.NUM_INFORME;
            // Encontrar la posición del último guion
            const posicionGuion = cadena.lastIndexOf('-');
            // Obtener la parte de la cadena antes del último guion
            const inf = cadena.substring(0, posicionGuion);
            document.getElementById('informe').innerHTML = inf + '-'; 
            // Obtener la parte de la cadena desde el último guion hasta el final
            const ninf = cadena.substring(posicionGuion + 1);
            document.getElementById('numinforme').value = ninf; 
            document.getElementById('numinformeFull').value = informeData.NUM_INFORME
            document.getElementById('fInforme').value = informeData.FECHA_INFORME;
            document.getElementById('nummemorando').value = informeData.NUM_MEMORANDO;
            document.getElementById('fmemorando').value = informeData.FECHA_MEMORANDO;
            document.getElementById('fCargaCompartida').value = informeData.FECHA_CARGA_COMPARTIDA;
            document.getElementById('tbObs').value = informeData.OBSERVACIONES;
            document.getElementById('cbEstado').value = informeData.COD_ESTADO
        })
        .catch(error => {
            alert("Error al cargar los datos: " + error.message);
        });

}

// Función para limpiar el formulario
function limpiarForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        // Resetea los campos del formulario
        form.reset();
        
        // Limpia selects2 si los usas
        if ($().select2) {
            $(form).find('select').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).val(null).trigger('change');
                }
            });
        }
        document.getElementById('analista').value = nickname;
        // Muestra feedback al usuario
        console.log('Formulario limpiado correctamente');
    }
}

function unirInforme() {
    //obtener el valor del span
    const valInforme = document.getElementById('informe').innerHTML
    // Obtener el valor del campo de entrada
    const numInforme = document.getElementById('numinforme').value; 
    // Actualizar el campo de texto completo
    document.getElementById('numinformeFull').value = valInforme + numInforme;
}

// Función para cargar datos en el modal
function cargarDatos(button) {
    const codInforme = button.getAttribute('data-id');
    console.log(codInforme);
    const url = baseurl + "/backend/informesinr/informesinrList.php"; // URL de tu script PHP
    console.log(url);
    // Realizar la solicitud AJAX usando fetch
    fetch(`${url}?id=${codInforme}`)
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
        }
        return response.json();
    })
    .then(selectedData => {
        if (selectedData) {
            //console.log("ID DATOS: " + selectedData.COD_INFORME);
            //console.log('Datos obtenidos:', JSON.stringify(selectedData));
            document.getElementById("detalleId").value = selectedData.COD_INFORME;
            document.getElementById("mRucEntidad").value = selectedData.RUC_ENTIDAD ;
            document.getElementById("mRazonSocial").value = selectedData.RAZON_SOCIAL;
            document.getElementById("mtipoInforme").value = selectedData.TIPO_INFORME ;
            document.getElementById("mareaRequiriente").value = selectedData.AREA_REQUIRIENTE;
            document.getElementById("mFechaAsignacion").value = selectedData.FECHA_ASIGNACION;
            document.getElementById("mfechaSolicitud").value = selectedData.FECHA_SOLICITUD_REVISION;
            document.getElementById("mfechaSolicitud").value = selectedData.FECHA_SOLICITUD_REVISION;
            document.getElementById("mMemorando").value = selectedData.NUM_MEMORANDO;
            document.getElementById("mfechaMemorando").value = selectedData.FECHA_MEMORANDO;
            document.getElementById("mInforme").value = selectedData.NUM_INFORME;
            document.getElementById("mfechaInforme").value = selectedData.FECHA_INFORME;
            document.getElementById("mtbObservaciones").value = selectedData.OBSERVACIONES;
            document.getElementById("mEstado").value = selectedData.ESTADO;
            document.getElementById("mAnalista").value = selectedData.ANALISTA;
            document.getElementById("mfechaCargaCompartida").value = selectedData.FECHA_CARGA_COMPARTIDA;
        }
    })
    .catch(error => {
        console.error('Error en la solicitud:', error);
    });
}

//consulta areas requirientes 
function consultarAreasRequirientes() {
    const url = baseurl + "/backend/informesinr/consultarAreasRequirientes.php";
    fetch(url)
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
        }
        return response.json();
    }).then(data => {
        const select = document.getElementById('selectAreaRequiriente');
        if (select) {
            select.innerHTML = ''; // Limpiar opciones existentes
            data.forEach(area => {
                const option = document.createElement('option');
                option.value = area.AREA_REQUIRIENTE;
                option.textContent = area.AREA_REQUIRIENTE;
                option.setAttribute('areaReq', area.AREA_REQUIRIENTE); // Añadir atributo personalizado
                select.appendChild(option);
            });
        } else {
            console.error('El elemento selectAreaRequiriente no se encontró en el DOM.');
        }
    }).catch(error => {
        console.error('Error al consultar áreas requerientes:', error);
    });
}

function selectAreaExistente() {
    const selectArea = document.getElementById('selectAreaRequiriente').value;
    document.getElementById('newAreaRequiriente').value = selectArea;
}

async function guardarForm(formId, event) {
    event.preventDefault();
    const form = document.getElementById(formId);
    const formData = new FormData(form);
    const url = baseurl + "/backend/informesinr/guardarInforme.php";
    if (!form.checkValidity()) {
        return; // Salir si el formulario no es válido
    }
    // llamada fetch a la ruta 
    try {
        const response = await fetch(url, {
            method: "POST",
            body: formData
        });
        if (!response.ok) throw new Error("Error en la respuesta del servidor");
        const message = await response.json();
        console.log("Mensaje del servidor:", message);
        await Swal.fire({
            icon: 'info',
            title: message.message,
            text: message.code + ' - ' + message.error,
        });
        form.reset();
        //actualizar tabla
        location.reload(); // O puedes actualizar la tabla sin recargar
    } catch (error) {
        console.error("Error al guardar la estructura:", error);
        alert(`Error al guardar la estructura: ${error.message}`);
    }
}

async function guardarNewTipoInf(formId, event) {
    event.preventDefault();
    const form = document.getElementById(formId);
    const formData = new FormData(form);
    const url = baseurl + "/backend/informesinr/guardarNewArea.php";
    if (!form.checkValidity()) {
        return; // Salir si el formulario no es válido
    }
    // llamada fetch a la ruta 
    try {
        const response = await fetch(url, {
            method: "POST",
            body: formData
        });
        if (!response.ok) throw new Error("Error en la respuesta del servidor");
        const message = await response.json();
        console.log("Mensaje del servidor:", message);
        await Swal.fire({
            icon: 'info',
            title: message.message,
            text: message.code + ' - ' + message.error,
        });
        form.reset();
        location.reload();
    } catch (error) {
        console.error("Error al guardar la estructura:", error);
        alert(`Error al guardar la estructura: ${error.message}`);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    
    const denominacionInf = 'SEPS-INR-DNS-'

    // Obtener el año actual
    const year = new Date().getFullYear();
    
    // Obtener el elemento input
    const span = document.getElementById('informe');
    
    // Actualizar el valor del input
    span.innerHTML = denominacionInf +  year + '-';
    
    // para mostrar el select de analistas 
    const isDirector = usrRol; 
    mostrarAnalista(isDirector);

    function actualizarAreaRequiriente() {
        const select = document.getElementById('cbTipoInforme');
        const input = document.getElementById('areaRequiriente');

        if (select) { // Verifica si el select existe
            const selectedOption = select.options[select.selectedIndex];
            
            if (selectedOption) { // Verifica si hay una opción seleccionada
                const areaReq = selectedOption.getAttribute('areaReq');
                input.value= areaReq; // Muestra el valor del atributo areaReq 
            } else {
                input.value= 'No hay opción seleccionada.';
            }
        } else {
            input.value= 'El elemento "cbTipoInforme" no se encontró.';
        }
    }

    // Añadir el listener al select
    const selectElement = document.getElementById('cbTipoInforme');
    if (selectElement) {
        selectElement.addEventListener('change', actualizarAreaRequiriente);
    } else {
        console.error('No se pudo añadir el listener porque el elemento no existe.');
    }

    document.getElementById('btRepInformes').addEventListener('click', function() {
    window.location.href = 'informesRepGeneral.php'; // Cambia 'paginaCompleta.html' al nombre de tu nueva página
});
   
});


$(document).ready(function() {
    $('#tablaInformes').DataTable({
        "autoWidth": true,
        "dom": '<"row mb-3"<"col-md-6"B><"col-md-6"f>><"row"<"col-12"tr>><"row"<"col-md-5"i><"col-md-7"p>>',
        "buttons": [
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                title: 'Información de los Informes realizadas por la Intendencia Nacional de Riesgos',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                className: 'btn btn-danger btn-sm mr-2',
                exportOptions: {
                    columns: ':visible'
                },
                customize: function(doc) {
                    doc.content[1].margin = [50, 0, 50, 0] // márgenes izquierdo y derecho
                }
            },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                title: 'Información de los Informes realizadas por la Intendencia Nacional de Riesgos',
                className: 'btn btn-success btn-sm mr-2',
                exportOptions: {
                    columns: ':visible',
                    modifier: {
                        page: 'all'
                    }
                },
                filename: 'Informes_intendencia_riesgos_' + new Date().toISOString().slice(0,10)
            },
          
        ],
        "paging": true,
        "lengthChange": false,
        "pageLength": 10,
        "ordering": true,
        "order": [[0, 'desc']],
        "columnDefs": [
            { "orderable": false, "targets": [1, 2, 3, 4, 5, 6, 7] }
        ],
        "language": {
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Buscar:",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        "responsive": true,
        "stateSave": true
    });
}); 










