console.log("estructuras.js Funcionando")

const isDirector = usrRol; 
mostrarAnalistaEjecutante(isDirector);

// Obtener la fecha actual
const today = new Date().toISOString().split('T')[0];
    
document.addEventListener('DOMContentLoaded', function() {
    // Establecer la fecha actual en el input
    document.getElementById('fechaRegistro').value = today;
    document.getElementById('fechaActualizacion').value = today;

    usersAdmin = ['ADMININSTRADOR', 'SUPERUSER', 'DIRECTOR'];

    if (usersAdmin.includes(usersAdmin)) {
        document.getElementById('analistaEjecutante').removeAttribute('readonly'); // Elimina el atributo readonly
        //console.log(usrRol)
    } else {
        document.getElementById('analistaEjecutante').setAttribute('readonly', 'readonly'); // Asegúrate de que sea readonly
        //console.log(usrRol)
    }
});
    // Manejar el formulario de guardar
    document.getElementById('formReporte').addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar el envío normal del formulario
        const formData = new FormData(this);
        const url = baseurl + "/backend/estructuras/guardarEstructura.php"; // Asegúrate de definir baseurl
        // Verificar si hay datos en formData
        //console.log("FormData:", Array.from(formData.entries()));

        fetch(url, {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data); // Mostrar la respuesta del servidor
            // Aquí puedes actualizar la tabla o los campos según sea necesario
            // Por ejemplo, puedes limpiar el formulario
            this.reset();
            document.getElementById('analistaEjecutante').value = nickname;
            actualizarTabla();
        })
        .catch(error => {
            alert("Error al guardar la estructura: " + error);
        });
    });


function actualizarTabla() {
    const url = baseurl + "/backend/estructuras/actualizarTabla.php"; // Cambia esto a la URL de tu API

    fetch(url)
    .then(response => {
        if (!response.ok) {
            throw new Error("Error al obtener los datos");
        }
        return response.json(); // Asumiendo que el servidor devuelve JSON
    })
    .then(data => {
        const tbody = document.querySelector('#tablaReportes tbody');
        tbody.innerHTML = ''; // Limpiar la tabla existente
        /* console.log("al actualizar al tabla:", data); */ // Verifica los datos recibidos
        // Llenar la tabla con los nuevos datos
        data.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.solicitante}</td>
                <td>${item.direccion_solicitante}</td>
                <td>${item.ruc}</td>
                <td>${item.estructura}</td>
                <td>${item.fechaCorte}</td>
                <td>${item.fecha_solicitud}</td>
                <td>${item.fechaInicio}</td>
                <td>${item.fechaFin}</td>
                <td>${item.createdAt}</td>
                <td>${item.detalle}</td>
                <td class="${item.estado === 'PENDIENTE' ? 'PENDIENTE' : ''}">
                    ${item.estado}
                </td>
                <td>${item.analista_ejecutante}</td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-info edit-btn btn-sm" data-id="${item.id}" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger delete-btn btn-sm" data-id="${item.id}" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
        // Asignar eventos a los botones después de actualizar la tabla
        asignarEventosBotones();
    })
    .catch(error => {
        alert("Error al actualizar la tabla: " + error);
    });
}

function asignarEventosBotones() {
    const tbody = document.querySelector('#tablaReportes tbody');

    tbody.addEventListener('click', function(event) {
        if (event.target.closest('.edit-btn')) {
            const id = event.target.closest('.edit-btn').dataset.id;
            const url = baseurl + "/backend/estructuras/obtenerEstructura.php?id=" + id;
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error('Error: ' + response.status + ' - ' + text);
                        });
                    }
                    return response.json();
                })
                .then(estructura => {
                    console.log(estructura);
                    // Rellenar los campos según sea necesario
                    document.getElementById('idEstructura').value = estructura.id;
                    document.getElementById('analistaSolicitante').value = estructura.solicitante;
                    document.getElementById('direccionSolicitante').value = estructura.direccion_solicitante;
                    document.getElementById('ruc').value = estructura.ruc;
                    document.getElementById('nombreReporte').value = estructura.estructura;
                    document.getElementById('fechaCorte').value = estructura.fechaCorte;
                    document.getElementById('fechaSolicitud').value = estructura.fecha_solicitud;
                    document.getElementById('analistaEjecutante').value = estructura.analista_ejecutante;
                    document.getElementById('estadoSolicitud').value = estructura.estado;
                    document.getElementById('fechaInicio').value = estructura.fechaInicio != null ? estructura.fechaInicio : '';
                    document.getElementById('fechaFin').value = estructura.fechaFin != null ? estructura.fechaFin : '';
                    document.getElementById('fechaCreacion').value = estructura.createdAt;
                    document.getElementById('detalleObs').value = estructura.detalle;
                    // Las fechas de registro y actualización son solo para mostrar, no se editan
                    document.getElementById('fechaRegistro').value = estructura.createdAt;
                    document.getElementById('fechaActualizacion').value = estructura.updatedAt;
                })
                .catch(error => {
                    alert("Error al cargar los datos: " + error.message);
                });

        }

        if (event.target.closest('.delete-btn')) {
            const id = event.target.closest('.delete-btn').dataset.id;
            // Lógica para eliminar
            //baseurl viene del archivo partials scripsts.php
            const url = baseurl + "/backend/estructuras/eliminarEstructura.php?id=" + id;
            //console.log("URL de la solicitud:", url); // Verifica la URL
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
    });
}

function seleccionarCategoria(nombre) {
    // Actualiza el campo oculto o el input que necesitas
    //document.getElementById('categoriaSeleccionada').value = id; // Si tienes un input oculto
    document.getElementById('nombreReporte').value = nombre; // Llena el input con el nombre de la categoría
    //alert("Categoría seleccionada: " + nombre); // Mensaje opcional
}

function filtrarEntidades() {
    const input = document.getElementById('busquedaEntidad');
    const filter = input.value.toUpperCase();
    const ul = document.getElementById('entidadesList');
    const li = ul.getElementsByTagName('li');
    for (let i = 0; i < li.length; i++) {
        const txtValue = li[i].textContent || li[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = ""; // Muestra el elemento
        } else {
            li[i].style.display = "none"; // Oculta el elemento
        }
    }
}





