console.log("informesinr.js Fuuncionando");

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
   
});

// Combierte la Tabla a Datatable 
$(document).ready(function() {   
    $('#tablaInformes').DataTable({
        "autoWidth": true, // Habilita el ajuste automático de ancho
        "dom": '<"botones"B><"filtro"f><"ctabla"rt><"pie"ip>',
        "buttons": [{
                        extend: 'pdfHtml5',
                        messageTop: 'Información de las Gestiones realizadas por la Intendencia Nacional de Riesgos',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        download: 'open'
                    },
                    'colvis',
        ],
        "paging": true, // Activa la paginación
        //"lengthMenu": [5, 10, 25, 50], // Opciones de número de filas por página
        "lengthChange": false, // Oculta el menú de selección de entradas
        "pageLength": 10, // Número de registros por página
        "ordering": true, // Habilita la ordenación
        "order": [[0, 'desc'],], // Ordena la primera columna (ID) en orden descendente
        "columnDefs": [
            { "orderable": false, "targets": [1, 2, 3, 4, 5, 6, 7] }], // Deshabilita la ordenación para las demás columnas
        "language": {
            //"lengthMenu": "Mostrar _MENU_ registros por página",
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
        }
    });

});


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
    //console.log(codInforme);
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

function limpiarForm(formId){
    //console.log("limiarForm");
    // Selecciona el formulario por su ID (ajusta el ID según sea necesario)
    const form = document.getElementById(formId);

    // Limpia todos los campos de entrada en el formulario
    if (form) {
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.value = ''; // Limpia el valor
            if (input.type === 'checkbox' || input.type === 'radio') {
                input.checked = false; // Desmarca checkboxes y radios
            }
        });
    }
    document.getElementById('analista').value = nickname;
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
    //console.log(codInforme);
    const url = baseurl + "/backend/informesinr/informesinrList.php"; // URL de tu script PHP

    // Realizar la solicitud AJAX usando fetch
    fetch(`${url}?id=${codInforme}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la red');
            }
            return response.json();
        })
        .then(selectedData => {
            if (selectedData) {
                //console.log(selectedData.COD_INFORME);
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
                document.getElementById("mfechaCargaCompartida").value = selectedData.FEC_CARGA_COMP;
            }
        })
        .catch(error => {
            console.error("Error al cargar los datos:", error);
        });
}







