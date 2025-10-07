console.log("listFilesRiesgoMercado.js funcionando");

// Función para ordenar archivos (por fecha y luego por versión)
function ordenarArchivos(archivos) {
    return archivos.sort((a, b) => {
        // Primero compara por fecha de modificación (de más reciente a más antiguo)
        const fechaA = new Date(a.lastModified);
        const fechaB = new Date(b.lastModified);
        
        if (fechaB - fechaA !== 0) {
            return fechaB - fechaA;
        }
        
        // Si las fechas son iguales, compara por versión (de mayor a menor)
        const versionA = extraerVersionNumerica(a.name);
        const versionB = extraerVersionNumerica(b.name);
        
        return versionB - versionA;
    });
}


// Función mejorada para extraer versión numérica
function extraerVersionNumerica(nombreArchivo) {
    const regexVersiones = [
        /(?:v|versión|version)[\s_]*(\d+)\.(\d+)/i,
        /(?:v|versión|version)[\s_]*(\d+)/i,
        /(\d+)\.(\d+)/,
        /(\d+)/
    ];
    
    for (const regex of regexVersiones) {
        const match = nombreArchivo.match(regex);
        if (match) {
            return parseFloat(`${parseInt(match[1]) || 0}.${parseInt(match[2]) || 0}`);
        }
    }
    
    return 0; // Valor por defecto
}

// Función para mostrar archivos en una tabla específica
function mostrarArchivosEnTabla(archivos, tablaId) {
    const tbody = document.getElementById(tablaId);
    if (!tbody) return console.error(`No se encontró el elemento con ID: ${tablaId}`);
    tbody.innerHTML = archivos.length === 0
        ? `<tr><td colspan="4" class="text-center text-muted">No se encontraron archivos</td></tr>`
        : archivos.map(archivo => {
            const version = extraerVersionNumerica(archivo.name);
            const fechaFormateada = new Date(archivo.lastModified).toLocaleDateString('es-ES', {
                day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
            });
            return `
                <tr>
                    <td class="text-center">v${version.toFixed(1)}</td>
                    <td>${archivo.name}</td>
                    <td class="text-center">${(archivo.size / (1024 * 1024)).toFixed(2)} MB</td>
                    <td class="text-center">${fechaFormateada}</td>
                    <td class="text-center">
                        <a href="${baseurl}/${archivo.path}/${archivo.name}"
                           class="btn btn-sm btn-primary" title="Descargar ${archivo.name}">
                            <i class="fas fa-download"></i> Descargar
                        </a>
                    </td>
                </tr>
            `;
        }).join('');
}

// Función para mostrar errores en todas las tablas
function mostrarErrorEnTodasTablas(mensaje) {
    ['rTBodyDiag', 'rTBodyDiagSimpli', 'rTBodyDiagSNF'].forEach(id => {
        const tbody = document.getElementById(id);
        if (tbody) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center text-danger">${mensaje}</td></tr>`;
        }
    });
}

// Función reportes Perdidas Esperadas
function fetchRiesgoMercado(carpetaReportes) {
    const url = `${baseurl}/backend/reportes/listFilesReportesAlertas.php?carpeta=${encodeURIComponent(carpetaReportes)}`;
    console.log("Fetching URL:", url);

    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
            return response.json();
        })
        .then(data => {
            console.log("Datos recibidos:", data);
            const categorias = {
                tasasPondAct: {
                    archivos: data.filter(item => item.name.includes('Tasas ponderadas activas')),
                    tablaId: 'rTBodyTasasPondAct'
                },
            };
            Object.values(categorias).forEach(({ archivos, tablaId }) => {
                mostrarArchivosEnTabla(ordenarArchivos(archivos), tablaId);
            });
        })
        .catch(error => {
            console.error("Error al obtener reportes:", error);
            mostrarErrorEnTodasTablas(`Error al cargar archivos: ${error.message}`);
        });
}

// Inicialización cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", () => {
    if (typeof carpetaReportes !== 'undefined') {
        fetchRiesgoMercado(carpetaReportes);
    } else {
        console.error("La variable 'carpetaReportes' no está definida");
        mostrarErrorEnTodasTablas("Error de configuración: ruta no definida");
    }
});