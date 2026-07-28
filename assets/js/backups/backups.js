console.log("backups.js funcionando");

/* Maneja la carga y visualización de información */

// VARIABLES GLOBALES

// URL base del sistema
const baseUrl = document.getElementById('base_url').dataset.baseUrl;
// Endpoint del backend
const urlBackups = baseUrl + '/backend/backups/listarBackups.php';
// ESTADO GLOBAL
let datosBackups = null;
// Gráfico de estado Backups
let chartEstadoBackups = null;


// GRÁFICO ESTADO GENERAL
function generarGraficoEstado(bases) {
    const ctx = document.getElementById('chartEstadoBackups');
    if (!ctx) {
        return;
    }
    let estados = {
        OK: 0,
        ADVERTENCIA: 0,
        CRITICO: 0,
        "SIN BACKUPS": 0
    };
    bases.forEach(base => {
        if (estados.hasOwnProperty(base.estado)) {
            estados[base.estado]++;
        }
    });
    if (chartEstadoBackups) {
        chartEstadoBackups.destroy();
    }
    chartEstadoBackups = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                'Correctos',
                'Advertencia',
                'Crítico',
                'Sin respaldo'
            ],
            datasets: [{
                data: [
                    estados.OK,
                    estados.ADVERTENCIA,
                    estados.CRITICO,
                    estados["SIN BACKUPS"]
                ],
                backgroundColor: [
                    '#198754', // OK - Verde
                    '#ffc107', // Advertencia - Amarillo
                    '#dc3545', // Crítico - Rojo
                    '#6c757d'  // Sin backups - Gris
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
}

// CONTROL SPINNER
function mostrarCarga(visible) {
    const spinner = document.getElementById(
        "spinnerCarga"
    );
    if (!spinner) {
        return;
    }
    spinner.style.display = visible
        ? "inline-block"
        : "none";
}

// PROCESAR RESUMEN
function procesarResumen(resumen) {
    if (!resumen) {
        console.warn("No existe resumen");
        return;
    }
    //console.log("Resumen recibido:", resumen);
    actualizarKPIs(resumen);
    actualizarEstadoGeneral(resumen)
}

// ACTUALIZAR KPI DEL DASHBOARD
function actualizarKPIs(resumen) {
    actualizarTexto("kpiTotalBases", resumen.totalBases);
    actualizarTexto("kpiOK", resumen.ok);
    actualizarTexto("kpiAdvertencias", resumen.advertencias);
    actualizarTexto("kpiCriticas", resumen.criticas);
    actualizarTexto("kpiEspacio", resumen.espacioTotal);
}

// ACTUALIZAR TEXTO DE UN ELEMENTO
function actualizarTexto(id, valor) {
    const elemento = document.getElementById(id);
    if (!elemento) {
        console.warn("No existe:", id);
        return;
    }
    elemento.textContent = valor;
}

// ACTUALIZAR ESTADO GENERAL
function actualizarEstadoGeneral(resumen) {
    const estado = document.getElementById("estadoGeneral");
    const fecha = document.getElementById("lblUltimaActualizacion");
    if (fecha && datosBackups.fechaActualizacion) {
        fecha.textContent = datosBackups.fechaActualizacion;
    }
    if (!estado) {
        return;
    }
    estado.className = "badge";
    if (resumen.criticas > 0) {
        estado.classList.add("bg-danger");
        estado.textContent = "CRÍTICO";
        return;
    }
    if (resumen.advertencias > 0) {
        estado.classList.add("bg-warning");
        estado.textContent = "ADVERTENCIA";
        return;
    }
    estado.classList.add("bg-success");
    estado.textContent = "OPERATIVO";
}

// PROCESAR BASES
function procesarBases(bases) {
    if (!bases || bases.length === 0) {
        console.warn("No existen bases registradas");
        return;
    }
    //console.log("Bases recibidas:", bases);
    construirTablaBackups(bases);
}

// PANEL DE ALERTAS
function actualizarPanelAlertas(bases) {
    const panel = document.getElementById("panelAlertas");
    if (!panel) {
        return;
    }
    panel.innerHTML = "";
    let totalAlertas = 0;
    bases.forEach(base => {
        switch (base.estado) {
            case "CRITICO":
                panel.appendChild(
                    crearAlerta(
                        "danger",
                        "fa-exclamation-triangle",
                        `${base.nombre}: respaldo crítico.`
                    )
                );
                totalAlertas++;
                break;
            case "ADVERTENCIA":
                panel.appendChild(
                    crearAlerta(
                        "warning",
                        "fa-exclamation-circle",
                        `${base.nombre}: revisar antigüedad del respaldo.`
                    )
                );
                totalAlertas++;
                break;
            case "SIN BACKUPS":
                panel.appendChild(
                    crearAlerta(
                        "secondary",
                        "fa-database",
                        `${base.nombre}: no existen respaldos.`
                    )
                );
                totalAlertas++;
                break;
        }
    });
    if (totalAlertas === 0) {
        panel.innerHTML = `
            <div class="alert alert-success mb-0">
                <i class="fas fa-check-circle me-2"></i>
                Todos los respaldos se encuentran en estado correcto.
            </div>`;
    }
}

// CREAR ALERTA
function crearAlerta(color, icono, mensaje) {
    const div = document.createElement("div");
    div.className = `alert alert-${color} py-2 mb-2`;
    div.innerHTML = `
        <i class="fas ${icono} me-2"></i>
        ${mensaje}`;
    return div;
}

// CONSTRUIR TABLA DE BACKUPS
function construirTablaBackups(bases) {
    const tbody = document.getElementById("tbodyBackups");
    if (!tbody) {
        console.warn("No existe tbodyBackups");
        return;
    }
    // Limpiar contenido anterior
    tbody.innerHTML = "";
    bases.forEach(base => {
            tbody.appendChild(crearFilaBackup(base));
        });
}

// CREAR FILA DE LA TABLA
function crearFilaBackup(base) {
    const fila = document.createElement("tr");
    fila.innerHTML = `
        <td>${base.nombre}</td>
        <td class="text-center">${crearBadgeEstado(base.estado)}</td>
        <td>${base.fechaBackup ?? "-"}</td>
        <td class="text-center">${base.dias ?? "-"}</td>
        <td class="text-end">${base.tamano}</td>
        <td class="text-end">${base.tamanoTotal}</td>
        <td class="text-center">${base.cantidadBackups}</td>
        <td>${base.ultimoArchivo}</td>
        <td class="text-center">${crearBotonDescarga(base)}</td>
    `;
    return fila;
}

// BADGE DE ESTADO
function crearBadgeEstado(estado) {
    switch (estado) {
        case "OK":
            return '<span class="badge bg-success">OK</span>';
        case "ADVERTENCIA":
            return '<span class="badge bg-warning text-dark">ADVERTENCIA</span>';
        case "CRITICO":
            return '<span class="badge bg-danger">CRÍTICO</span>';
        case "SIN BACKUPS":
            return '<span class="badge bg-secondary">SIN BACKUPS</span>';
        default:
            return '<span class="badge bg-dark">DESCONOCIDO</span>';
    }
}

// BOTÓN DE DESCARGA
function crearBotonDescarga(base) {
    if (!base.ultimoArchivo) {
        return "-";
    }
    return `
        <a href="${baseUrl}/backend/backups/descargarBackup.php?carpeta=${encodeURIComponent(base.carpeta)}&archivo=${encodeURIComponent(base.ultimoArchivo)}"
           class="btn btn-success btn-sm"
           target="_blank"
           rel="noopener noreferrer"
           title="Descargar último respaldo">
            <i class="fas fa-download"></i>
        </a>`;
}

// CARGAR INFORMACIÓN DE BACKUPS
async function cargarBackups() {
    try {
        console.log("Consultando backups...");
        /* Mostrar carga */
        mostrarCarga(true);
        /* Consulta al backend */
        const respuesta = await fetch(urlBackups);
        /* Validar respuesta HTTP */
        if (!respuesta.ok) {
            throw new Error(
                "Error HTTP: " + respuesta.status
            );
        }
        /* Convertir respuesta JSON */
        const data = await respuesta.json();
        /* Guardar información global */
        datosBackups = data;
        /* Procesar información recibida */
        procesarDatosBackups();
        /* Consola para pruebas */
        console.log("Información recibida:",datosBackups);
    } catch (error) {
        console.error("Error cargando backups:",error);
    } finally {
        /* Ocultar carga */
        mostrarCarga(false);
    }
}

// PROCESAR INFORMACIÓN DEL BACKEND
function procesarDatosBackups() {
    if (!datosBackups) {
        console.warn("No existen datos de backups");
        return;
    }

    /* Validar estado general */
    if (!datosBackups.estado) {
        console.error("Backend devolvió error:",datosBackups.mensaje);
        return;
    }

    /* Procesar resumen */
    procesarResumen(datosBackups.resumen);

    /* Procesar bases */
    procesarBases(datosBackups.bases);

    /* Procesar Panel de Alertas */
    actualizarPanelAlertas(datosBackups.bases);

    /* Genera el gráfico */
    generarGraficoEstado(datosBackups.bases);
}

// INICIO DEL MODULO 
document.addEventListener("DOMContentLoaded",
                          function () {cargarBackups();}
                        );