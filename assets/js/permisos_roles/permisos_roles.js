let permisos = [];
let modalPermisos = null;

document.addEventListener('DOMContentLoaded', () => {

    modalPermisos = new bootstrap.Modal(
        document.getElementById('modalPermisos')
    );

    cargarPermisos();

    document
        .getElementById('btnActualizar')
        ?.addEventListener(
            'click',
            cargarPermisos
        );

});

/* ====================================================
   CARGAR DATOS
==================================================== */

async function cargarPermisos() {

    try {

        const response = await fetch(
            `${base_url}/backend/permisos_roles/listarMenusPermisos.php`
        );

        permisos = await response.json();

        renderTabla();
        actualizarKPIs();

    } catch (error) {

        console.error(
            'Error cargando permisos:',
            error
        );

        mostrarMensaje(
            'Error al cargar permisos',
            'danger'
        );

    }

}

/* ====================================================
   TABLA
==================================================== */

function renderTabla() {

    const tbody =
        document.querySelector(
            '#tblPermisos tbody'
        );

    tbody.innerHTML = '';

    permisos.forEach(row => {

        const tr =
            document.createElement('tr');

        tr.innerHTML = `
            <td class="text-center">
                ${row.id}
            </td>

            <td>
                ${row.padre ?? 'RAIZ'}
            </td>

            <td>
                ${row.titulo}
            </td>

            <td class="text-center">
                <span class="badge bg-success">
                    ${row.roles}
                </span>
            </td>

            <td class="text-center">
                <span class="badge bg-warning text-dark">
                    ${row.direcciones}
                </span>
            </td>

            <td class="text-center">
                <span class="badge bg-info">
                    ${row.usuarios}
                </span>
            </td>

            <td class="text-center">

                <button
                    class="btn btn-sm btn-primary"
                    onclick="editarMenu(${row.id})">

                    <i class="fas fa-edit"></i>

                </button>

            </td>
        `;

        tbody.appendChild(tr);

    });

    if ($.fn.DataTable.isDataTable('#tblPermisos')) {
        $('#tblPermisos').DataTable().destroy();
    }

    $('#tblPermisos').DataTable({
        autoWidth: true,
        dom: '<"botones"B><"filtro"f><"ctabla"rt><"pie"ip>',
        pageLength: 10,
        paging: true,
        info: true,
        ordering: true,
        searching: true,
        lengthChange: false,
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Reporte_Permisos_INR'
            },
            {
                extend: 'pdfHtml5',
                title: 'Reporte_Permisos_INR'
            },
            {
                extend: 'print',
                title: 'Reporte_Permisos_INR'
            },
            'colvis'
        ],
        language: {
            zeroRecords: "No se encontraron resultados",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "No hay registros disponibles",
            infoFiltered: "(filtrado de _MAX_ registros)",
            search: "Buscar:",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            }
        }
    });                         

}

/* ====================================================
   KPI
==================================================== */

function actualizarKPIs() {

    document.getElementById(
        'kpiMenus'
    ).textContent = permisos.length;

    let totalRoles = 0;
    let totalDirecciones = 0;
    let totalUsuarios = 0;

    permisos.forEach(row => {

        totalRoles +=
            Number(row.roles);

        totalDirecciones +=
            Number(row.direcciones);

        totalUsuarios +=
            Number(row.usuarios);

    });

    document.getElementById(
        'kpiRoles'
    ).textContent = totalRoles;

    document.getElementById(
        'kpiDirecciones'
    ).textContent = totalDirecciones;

    document.getElementById(
        'kpiUsuarios'
    ).textContent = totalUsuarios;

}

/* ====================================================
   EDITAR MENU
==================================================== */

function editarMenu(menuId) {

    const menu =
        permisos.find(
            x => Number(x.id) === Number(menuId)
        );

    if (!menu) {
        return;
    }

    document.getElementById(
        'menu_id'
    ).value = menu.id;

    document.getElementById(
        'rolesContainer'
    ).innerHTML =
        '<div class="text-muted">Cargando roles...</div>';

    document.getElementById(
        'direccionesContainer'
    ).innerHTML =
        '<div class="text-muted">Cargando direcciones...</div>';

    document.getElementById(
        'usuariosContainer'
    ).innerHTML =
        '<div class="text-muted">Cargando usuarios...</div>';

    modalPermisos.show();

    cargarDetalleMenu(menuId);

}

/* ====================================================
   DETALLE MENU
==================================================== */

async function cargarDetalleMenu(menuId) {

    try {

        const response =
            await fetch(
                `${base_url}/backend/permisos_roles/obtenerPermisosMenu.php?id=${menuId}`
            );

        const data = await response.json(); 

        // para ver el resultado en consola 
        /* const texto = await response.text();
        console.log(texto);
        return; */

        renderRoles(
            data.roles || []
        );

        renderDirecciones(
            data.direcciones || []
        );

        renderUsuarios(
            data.usuarios || []
        );

    } catch (error) {

        console.error(error);

        mostrarMensaje(
            'Error obteniendo permisos',
            'danger'
        );

    }

}

/* ====================================================
   ROLES
==================================================== */

function renderRoles(roles) {

    const container = document.getElementById('rolesContainer');
    container.innerHTML = '';

    roles.forEach(item => {

    container.insertAdjacentHTML(
            'beforeend',
            `
            <div class="col-md-3 col-lg-2">

                <div class="form-check">

                    <input
                        class="form-check-input rolCheck"
                        type="checkbox"
                        value="${item.id}"
                        ${item.asignado ? 'checked' : ''}>

                    <label class="form-check-label">
                        ${item.nombre}
                    </label>

                </div>

            </div>
            `
        );

    });

}

/* ====================================================
   DIRECCIONES
==================================================== */

function renderDirecciones(direcciones) {

    const container = document.getElementById('direccionesContainer');
    container.innerHTML = '';

    direcciones.forEach(item => {

        container.insertAdjacentHTML(
            'beforeend',
            `
            <div class="form-check">

                <input
                    class="form-check-input direccionCheck"
                    type="checkbox"
                    value="${item.id}"
                    ${item.asignado ? 'checked' : ''}>

                <label class="form-check-label">
                    ${item.direccion}
                </label>

            </div>
            `
        );

    });

}

/* ====================================================
   USUARIOS
==================================================== */

function renderUsuarios(usuarios) {

    const container =
        document.getElementById(
            'usuariosContainer'
        );

    container.innerHTML = '';

    usuarios.forEach(item => {

        container.insertAdjacentHTML(
            'beforeend',
            `
            <div
                class="form-check usuario-item"
                data-nickname="${item.nickname.toLowerCase()}">

                <input
                    class="form-check-input usuarioCheck"
                    type="checkbox"
                    value="${item.id}"
                    ${item.asignado ? 'checked' : ''}>

                <label class="form-check-label">
                    ${item.nickname}
                </label>

            </div>
            `
        );

    });

}


/* ====================================================
   MENSAJES
==================================================== */

function mostrarMensaje(
    mensaje,
    tipo = 'success'
) {

    console.log(
        `[${tipo}] ${mensaje}`
    );

}

document.addEventListener(
    'click',
    function(e){

        if(
            e.target.id !==
            'btnSeleccionarTodosRoles'
        ){
            return;
        }

        document
            .querySelectorAll(
                '.rolCheck'
            )
            .forEach(check => {

                check.checked = true;

            });

    }
);

document.addEventListener(
    'click',
    function(e){

        if(
            e.target.id !==
            'btnDeseleccionarTodosRoles'
        ){
            return;
        }

        document
            .querySelectorAll(
                '.rolCheck'
            )
            .forEach(check => {

                check.checked = false;

            });

    }
);

document.addEventListener(
    'click',
    function(e){

        if(
            e.target.id !==
            'btnSeleccionarTodasDirecciones'
        ){
            return;
        }

        document
            .querySelectorAll(
                '.direccionCheck'
            )
            .forEach(check => {

                check.checked = true;

            });

    }
);

document.addEventListener(
    'click',
    function(e){

        if(
            e.target.id !==
            'btnDeseleccionarTodasDirecciones'
        ){
            return;
        }

        document
            .querySelectorAll(
                '.direccionCheck'
            )
            .forEach(check => {

                check.checked = false;

            });

    }
);



document.addEventListener(
    'input',
    function(e){

        if(
            e.target.id !==
            'txtBuscarUsuario'
        ){
            return;
        }

        const texto =
            e.target.value
            .toLowerCase();

        document
            .querySelectorAll(
                '.usuario-item'
            )
            .forEach(item => {

                const usuario =
                    item.dataset.nickname;

                item.style.display =
                    usuario.includes(texto)
                    ? ''
                    : 'none';

            });

    }
);

document.addEventListener(
    'click',
    function(e){

        if(
            e.target.id !==
            'btnSeleccionarTodosUsuarios'
        ){
            return;
        }

        document
            .querySelectorAll(
                '.usuarioCheck'
            )
            .forEach(check => {

                check.checked = true;

            });

    }
);

document.addEventListener(
    'click',
    function(e){

        if(
            e.target.id !==
            'btnDeseleccionarTodosUsuarios'
        ){
            return;
        }

        document
            .querySelectorAll(
                '.usuarioCheck'
            )
            .forEach(check => {

                check.checked = false;

            });

    }
);

document
    .getElementById('btnGuardar')
    ?.addEventListener(
        'click',
        guardarPermisos
    );

    async function guardarPermisos() {

    try {

        const menuId =
            document.getElementById(
                'menu_id'
            ).value;

        const roles =
            [...document.querySelectorAll(
                '.rolCheck:checked'
            )].map(x => x.value);

        const direcciones =
            [...document.querySelectorAll(
                '.direccionCheck:checked'
            )].map(x => x.value);

        const usuarios =
            [...document.querySelectorAll(
                '.usuarioCheck:checked'
            )].map(x => x.value);

        const payload = {

            menu_id: menuId,

            roles,

            direcciones,

            usuarios

        };

        console.log(
            'Payload permisos:',
            payload
        );

        const response =
            await fetch(
                `${base_url}/backend/permisos_roles/guardarPermisosMenu.php`,
                {
                    method: 'POST',

                    headers: {
                        'Content-Type':
                            'application/json'
                    },

                    body:
                        JSON.stringify(payload)
                }
            );

        const data =
            await response.json();

        if (data.success) {

            mostrarMensaje(
                'Permisos actualizados',
                'success'
            );

            bootstrap.Modal
                .getInstance(
                    document.getElementById(
                        'modalPermisos'
                    )
                )
                .hide();

            cargarPermisos();

        } else {

            mostrarMensaje(
                data.message,
                'danger'
            );

        }

    } catch (error) {

        console.error(error);

        mostrarMensaje(
            'Error al guardar',
            'danger'
        );

    }
}

