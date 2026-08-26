console.log("listFilescosede.js funcionando");

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
    
    // Validar si el elemento existe - salir silenciosamente si no existe
    if (!tbody) {
        return; // Salir sin errores ni mensajes
    }
    
    tbody.innerHTML = archivos.length === 0
        ? `<tr><td colspan="6" class="text-center text-muted">No se encontraron archivos</td></tr>`
        : archivos.map(archivo => {
            // Extraer nombre sin extensión
            const nombreCompleto = archivo.name;
            const ultimoPunto = nombreCompleto.lastIndexOf('.');
            const nombreSinExtension = ultimoPunto !== -1 ? nombreCompleto.substring(0, ultimoPunto) : nombreCompleto;
            
            // Dividir por guiones bajos
            const partes = nombreSinExtension.split('_');
            
            let anioCorte = 'N/A';
            let mesCorte = 'N/A';
            
            if (partes.length >= 2) {
                anioCorte = partes[partes.length - 1]; // Penúltimo elemento
                mesCorte = partes[partes.length - 2];  // Último elemento
            }
            
            // Formatear fechas para mostrarlas más legibles (opcional)
            const anioCorteFormateada = formatearFechaArchivo(anioCorte);
            const mesCorteFormateada = formatearFechaArchivo(mesCorte, true); // true para formato día-mes-año
            
            const version = extraerVersionNumerica(archivo.name);
            const fechaFormateada = new Date(archivo.lastModified).toLocaleDateString('es-ES', {
                day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
            });
            
            return `
                <tr>
                    <td class="text-center">${mesCorteFormateada}</td>
                    <td class="text-center">${anioCorteFormateada}</td>
                    <td>${archivo.name}</td>
                    <td class="text-center">${(archivo.size / (1024 * 1024)).toFixed(2)} MB</td>
                    <td class="text-center">${fechaFormateada}</td>
                    <td class="text-center">
                        <a href="${baseurl}/${archivo.path}/${archivo.name}"
                           target="_blank"
                           class="btn btn-sm btn-outline-primary btn-descargar"
                           data-nombre="${archivo.name}"
                           data-ruta="${archivo.path}"
                           data-tamanio="${archivo.size}"
                           data-url="${baseurl}/${archivo.path}/${archivo.name}"
                           title="Descargar ${archivo.name}">
                                <i class="fas fa-download"></i> Descargar
                        </a>
                    </td>
                </tr>
            `;
        }).join('');
}

// Función auxiliar para formatear fechas como "nov2025" o "31dic2025"
function formatearFechaArchivo(fechaStr, esmesCorte = false) {
    if (!fechaStr || fechaStr === 'N/A') return fechaStr;
    
    // Convertir mes de abreviatura a número
    const meses = {
        'ene': '01', 'feb': '02', 'mar': '03', 'abr': '04',
        'may': '05', 'jun': '06', 'jul': '07', 'ago': '08',
        'sep': '09', 'oct': '10', 'nov': '11', 'dic': '12'
    };
    
    if (esmesCorte) {
        // Es fecha de Corte (formato: nov2025)
        const match = fechaStr.match(/([a-z]{3})(\d{4})/i);
        if (match) {
            const mes = match[1];
            const año = match[2];
            
            const mesNum = meses[mes.toLowerCase()] || mes;
            /* return `${mesNum}/${año}`; */
            return `${mes.toUpperCase()} ${año}`;
        }
    } else {
        // Si es fecha de Carga (formato: 31dic2025)
        // Extraer día, mes y año
        const match = fechaStr.match(/(\d{1,2})([a-z]{3})(\d{4})/i);
        if (match) {
            const dia = match[1];
            const mes = match[2];
            const año = match[3];
            
            const mesNum = meses[mes.toLowerCase()] || mes;
            return `${dia}/${mesNum}/${año}`;
        }
        
    }
    
    return fechaStr; // Si no se puede formatear, devolver el original
}

// Función para mostrar errores en todas las tablas
function mostrarErrorEnTodasTablas(mensaje) {
    [
     'rTBodycosede',
     'rTBodyetap',
    ].forEach(id => {
        const tbody = document.getElementById(id);
        if (tbody) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center text-danger">${mensaje}</td></tr>`;
        }
    });
}

// Función principal para obtener y mostrar reportes
function fetchCosede(carpetaReportes) {
    const url = `${baseurl}/backend/reportes/listFilesReportesMesAnio.php?carpeta=${encodeURIComponent(carpetaReportes)}`;
    //console.log("Fetching URL:", url);

    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
            return response.json();
        })
        .then(data => {
            //console.log("Datos recibidos:", data);
            const categorias = {
                cosede: {
                    archivos: data.filter(item => item.name.includes('COSEDE_PSI')),
                    tablaId: 'rTBodycosede'
                },
                etap: {
                    archivos: data.filter(item => item.name.includes('etap_1_PSI')),
                    tablaId: 'rTBodyetap'
                },
                cosedeDNLESF: {
                    archivos: data.filter(item => item.name.includes('COSEDE_DNLESF')),
                    tablaId: 'rTBodycosedednlesf'
                },
                etapDNLESF: {
                    archivos: data.filter(item => item.name.includes('etap_1_DNLESF')),
                    tablaId: 'rTBodyetapdnlesf'
                },
            };
            // Iterar sobre las categorías de forma segura
            Object.values(categorias).forEach(({ archivos, tablaId }) => {
                // Validación adicional: si no hay archivos, aún así intentamos mostrar (mostrará "No se encontraron archivos")
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
        fetchCosede(carpetaReportes);
    } else {
        console.error("La variable 'carpetaReportes' no está definida");
        mostrarErrorEnTodasTablas("Error de configuración: ruta no definida");
    }
});




