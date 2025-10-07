console.log("gestioninr.js Funcionando");

document.addEventListener('DOMContentLoaded', function(e) {
    // Evento para llenar subcategorías
   document.getElementById('cbCategoria').addEventListener('change', subCategoryChange);

   document.getElementById('verTablaCompleta').addEventListener('click', function() {
        window.location.href = 'tablaCompleta.php'; // Cambia 'paginaCompleta.html' al nombre de tu nueva página
    });
});

$(document).ready(function() {

    const isDirector = usrRol; 
    mostrarAnalista(isDirector);

    $('#tablaActividades').DataTable({
        "autoWidth": true, // Habilita el ajuste automático de ancho
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
        //"lengthMenu": [5, 10, 25, 50], // Opciones de número de filas por página
        "lengthChange": false, // Oculta el menú de selección de entradas
        "pageLength": 10, // Número de registros por página
        "ordering": true, // Habilita la ordenación
        "order": [[0, 'desc'],], // Ordena la primera columna (ID) en orden descendente
        "columnDefs": [
            { "orderable": false, "targets": [1, 2, 3, 4, 5, 6, 7, 8] }], // Deshabilita la ordenación para las demás columnas
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

function subCategoryChange () {
    const categoriaId = this.value; // Obtenemos el ID de la categoría seleccionada
    //console.log("este es el id : " + this.value)
    const dirInrId = document.getElementById('direccionid').value;
    const cbSubCategoria = document.getElementById('cbSubCategoria');

    // Limpiamos el select de subcategorías
    cbSubCategoria.innerHTML = '<option value="">Selecciona una subcategoría</option>';

    if (categoriaId) {
        const xhr = new XMLHttpRequest();
        const url = baseurl + "/backend/gestioninr/gestioninrList.php"; // Ruta al script PHP
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            //console.log(xhr.responseText); // Muestra la respuesta en la consola
            if (xhr.status === 200) {
                try {
                    const subcategorias = JSON.parse(xhr.responseText);
                    //console.log('Subcategorías:', subcategorias); // Verifica el contenido
                    if (Array.isArray(subcategorias)) { // Comprueba si es un array
                        subcategorias.forEach(subcategoria => {
                            const option = document.createElement('option');
                            option.value = subcategoria.COD_SUBCATEGORIA; // Asegúrate de que el JSON devuelva 'id'
                            option.textContent = subcategoria.SUBCATEGORIA; // Asegúrate de que el JSON devuelva 'nombre'
                            cbSubCategoria.appendChild(option);
                        });
                    } else {
                        console.error('La respuesta no es un array:', subcategorias);
                    }
                } catch (e) {
                    console.error('Error al parsear JSON:', e);
                }
            } else {
                console.error('Error al obtener las subcategorías:', xhr.statusText);
            }
        };

        xhr.onerror = function() {
            console.error('Error de conexión');
        };

        // Envía el ID de la categoría
        xhr.send('categoria_id=' + encodeURIComponent(categoriaId) + '&direccion_id=' + encodeURIComponent(dirInrId));
    }
};


function mostrarAnalista(isDirector) {
    const select = document.getElementById('analistaSelect');
    const label = document.getElementById('lbanalistaSelect');
    const tbNewCat = document.getElementById('btCrearCat');
    const deleteButtons = document.querySelectorAll('.delete-btn');
    if (isDirector == 'DIRECTOR' || 
        isDirector == 'ADMNINSTRADOR' || 
        isDirector == 'SUPERUSER' || 
        isDirector == 'DIRADMINDNR' || 
        isDirector == 'DIRADMINDNS' ||
        isDirector == 'DIRADMINDNSES' ||  
        isDirector == 'DIRADMINDNPLA'
    ) {
        select.style.display = 'block'; // Muestra el select
        label.style.display ='block';   
        tbNewCat.style.display ='block'; // Muestra boton new Cat
        deleteButtons.forEach(button => {
            button.style.display = 'block'; 
        });
    }else{
        select.style.display = 'none';    // Oculta el select
        label.style.display ='none'; 
        tbNewCat.style.display ='none';
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


// Función para cargar datos en el modal
function cargarDatos(button) {
    const codGestion = button.getAttribute('data-id');
    //console.log(codGestion);
    const url = baseurl + "/backend/gestioninr/gestioninrList.php"; // URL de tu script PHP

    // Realizar la solicitud AJAX usando fetch
    // /backend/gestioninr/gestioninrList.php?codGestion
    fetch(`${url}?id=${codGestion}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la red');
            }
            return response.json();
        })
        .then(selectedData => {
            if (selectedData) {
                console.log({selectedData});
                document.getElementById("detalleId").value = selectedData.COD_GESTION ? selectedData.COD_GESTION : '';
                document.getElementById("mDireccion").value = selectedData.DIRECCION ? selectedData.DIRECCION : '';
                document.getElementById("mCategoria").value = selectedData.CATEGORIA ? selectedData.CATEGORIA : '';
                document.getElementById("mSubCategoria").value = selectedData.SUBCATEGORIA ? selectedData.SUBCATEGORIA : '';
                document.getElementById("mFechaRegistro").value = selectedData.FECHA_REGISTRO ? selectedData.FECHA_REGISTRO : '';
                document.getElementById("mAnalista").value = selectedData.ANALISTA ? selectedData.ANALISTA : '';
                document.getElementById("mGestion").value = selectedData.GESTION ? selectedData.GESTION : '';
                document.getElementById("mfecIncio").value = selectedData.FECHA_INICIO ? selectedData.FECHA_INICIO : '';
                document.getElementById("mfecFin").value = selectedData.FECHA_FIN ? selectedData.FECHA_FIN : '';
                document.getElementById("mEstado").value = selectedData.ESTADO ? selectedData.ESTADO : '';
                document.getElementById("mRucEntidad").value = selectedData.RUC_ENTIDAD ? selectedData.RUC_ENTIDAD : '';
                document.getElementById("mRazonSocial").value = selectedData.RAZON_SOCIAL ? selectedData.RAZON_SOCIAL : '';
                document.getElementById("mFechaOficTram").value = selectedData.FECHA_OFIC_TRAM ? selectedData.FECHA_OFIC_TRAM : '';
                document.getElementById("mOficioTramite").value = selectedData.OFICIO_TRAMITE ? selectedData.OFICIO_TRAMITE : '';
                document.getElementById("mComentario").value = selectedData.COMENTARIO ? selectedData.COMENTARIO : ''; 
            }
        })
        .catch(error => {
            console.error("Error al cargar los datos:", error);
        });
}

// botones editar
function cargarDatosForm(boton){
    const codGestion = boton.getAttribute('data-id');
    const url = baseurl + "/backend/gestioninr/gestioninrList.php?id=" + codGestion;
    //console.log(codGestion);
    fetch(url)
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error('Error: ' + response.status + ' - ' + text);
                });
            }
            return response.json();
        })
        .then(async gestionData => {
            limpiarForm(frmGestionesInr);
            // Rellenar los campos según sea necesario
            document.getElementById('codGestion').value = gestionData.COD_GESTION;
            document.getElementById('direccionid').value = gestionData.COD_DIRECCION;
            document.getElementById('direccion').value = gestionData.DIRECCION;
            document.getElementById('cbCategoria').value = gestionData.COD_CATEGORIA;
            // Si deseas disparar el evento 'change' manualmente después de asignar el valor
            await subCategoryChange.call(document.getElementById('cbCategoria'));
            // Usa un temporizador para esperar un momento
            setTimeout(() => {
                const subCategoriaElement = document.getElementById('cbSubCategoria');
                if (subCategoriaElement) {
                    subCategoriaElement.value = gestionData.COD_SUBCATEGORIA;
                }
            }, 200); // Ajusta el tiempo según sea necesario
            document.getElementById('tbGestion').value = gestionData.GESTION;
            document.getElementById('fechaInicio').value = gestionData.FECHA_INICIO;
            document.getElementById('fechaFin').value = gestionData.FECHA_FIN;
            document.getElementById('estado').value = gestionData.ESTADO;
            document.getElementById('ruc').value = gestionData.RUC_ENTIDAD;
            document.getElementById('tbrazonSocial').value = gestionData.RAZON_SOCIAL;
            document.getElementById('fechaOficio').value = gestionData.FECHA_OFIC_TRAM;
            document.getElementById('oficio').value = gestionData.OFICIO_TRAMITE;
            document.getElementById('analista').value = gestionData.ANALISTA;
            document.getElementById('tbcomentario').value = gestionData.COMENTARIO;
        })
        .catch(error => {
            alert("Error al cargar los datos: " + error.message);
        });

}

//eliminar 
function eliminarRegistro(boton) {
    const codGestion = boton.getAttribute('data-id');
    const nickname = boton.getAttribute('logUser');
    const url = baseurl + "/backend/gestioninr/eliminarGestioninr.php?id=" + codGestion + "&delUser=" + nickname;
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


function guardarForm(formId,event) {
    event.preventDefault(); // Evita el envío tradicional
    console.log("guardarForm");
    // Selecciona el formulario por su ID
    const form = document.getElementById(formId);
    if (!form) {
         console.error("Formulario no encontrado con el ID:", formId);
         return;
    }
    // Crear un objeto FormData a partir del formulario
    const formToSave = new FormData(form);
    console.log({formToSave})
    // Opciones para la solicitud fetch
    const options = {
        method: 'POST',
        body: formToSave
    };
    const url = baseurl + "/backend/gestioninr/guardarGestioninr.php"; // Cambia esto a la URL de tu API
    console.log(url);
    // Enviar los datos al servidor
    fetch(url, options) // Ajusta la URL según sea necesario
         .then(response => {
            if (!response.ok) {
                throw new Error('Error en la red: ' + response.statusText);
            }
          return response.json(); // Suponiendo que la respuesta es JSON
         })
          .then(data => {
            // Aquí puedes manejar la respuesta del servidor
            //console.log("Server Response:", data);
            // Concatenar los valores y mostrar en un alert
            alert(data.status + ' - Transacción: ' + data.transaccion + ' - Mensaje: ' + data.message);
            limpiarForm('frmGestionesInr');
            // Actualizar el DataTable
            console.log("se actualiza");
            location.reload();

          })
          .catch(error => {
              console.error("Error al enviar el formulario:", error);
          });
}

function selecCatExistente() {
    const selectCategoria = document.getElementById('cbCat');
    const inputCatId = document.getElementById('tbnCatid');
    const inputNewCat = document.getElementById('tbnewCat');

    // Obtener el valor seleccionado
    const valorSeleccionado = selectCategoria.value;

    // Verificar si se ha seleccionado una categoría válida
    if (valorSeleccionado !== "0") {
        // Obtener el texto de la opción seleccionada usando el atributo data-text
        const textoSeleccionado = selectCategoria.options[selectCategoria.selectedIndex].dataset.text;

        // Asignar el valor y el texto a los inputs
        inputCatId.value = valorSeleccionado; // Asigna el valor al input tbnCatid
        inputNewCat.value = textoSeleccionado; // Asigna el texto al input tbnewCat
    } else {
        // Limpiar los inputs si no hay selección válida
        inputCatId.value = '0';
        inputNewCat.value = '';
    }
}

function addCategoria(formId,event) {
    event.preventDefault(); // Evita el envío tradicional
    console.log("addCategoria");
    // Selecciona el formulario por su ID
    const form = document.getElementById(formId);
    if (!form) {
         console.error("Formulario no encontrado con el ID:", formId);
         return;
    }
    // Crear un objeto FormData a partir del formulario
    const formToSave = new FormData(form);
    // Mostrar los datos en la consola
    // for (const [key, value] of formToSave.entries()) {
    //     console.log(`${key}: ${value}`);}
    // Opciones para la solicitud fetch
    const options = {
        method: 'POST',
        body: formToSave
    };
    const url = baseurl + "/backend/gestioninr/addCategoria.php"; // Cambia esto a la URL de tu API
    // Enviar los datos al servidor
    fetch(url, options) // Ajusta la URL según sea necesario
         .then(response => {
            if (!response.ok) {
                throw new Error('Error en la red: ' + response.statusText);
            }
          return response.json(); // Suponiendo que la respuesta es JSON
         })
          .then(data => {
            alert(data.message); // Muestra la respuesta en la consola
            console.log(data);
            
            limpiarForm('frmAddCat');
            location.reload();
          })
          .catch(error => {
              console.error("Error al enviar el formulario:", error);
          });
}

