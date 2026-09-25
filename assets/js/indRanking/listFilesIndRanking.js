console.log("listFilesIndRanking.js funcionando");

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
            
            let fechaCarga = 'N/A';
            let fechaCorte = 'N/A';
            
            // Formato esperado: ind_ranking_coacs_preliminar_nov2025_31dic2025
            // La fecha de carga es el penúltimo elemento (nov2025)
            // La fecha de corte es el último elemento (31dic2025)
            if (partes.length >= 2) {
                fechaCarga = partes[partes.length - 1]; // Penúltimo elemento
                fechaCorte = partes[partes.length - 2];  // Último elemento
            }
            
            // Formatear fechas para mostrarlas más legibles (opcional)
            const fechaCargaFormateada = formatearFechaArchivo(fechaCarga);
            const fechaCorteFormateada = formatearFechaArchivo(fechaCorte, true); // true para formato día-mes-año
            
            const version = extraerVersionNumerica(archivo.name);
            const fechaFormateada = new Date(archivo.lastModified).toLocaleDateString('es-ES', {
                day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
            });
            
            return `
                <tr>
                    <td class="text-center">${fechaCorteFormateada}</td>
                    <td class="text-center">${fechaCargaFormateada}</td>
                    <td>${archivo.name}</td>
                    <td class="text-center">${archivo.formato}</td>
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
function formatearFechaArchivo(fechaStr, esFechaCorte = false) {
    if (!fechaStr || fechaStr === 'N/A') return fechaStr;
    
    // Convertir mes de abreviatura a número
    const meses = {
        'ene': '01', 'feb': '02', 'mar': '03', 'abr': '04',
        'may': '05', 'jun': '06', 'jul': '07', 'ago': '08',
        'sep': '09', 'oct': '10', 'nov': '11', 'dic': '12'
    };
    
    if (esFechaCorte) {
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
    ['rTBodyIndRankingCoacsPreDTA',
        'rTBodyIndRankingCoacsPreXLSX',
        'rTBodyIndRankingMutualistasPreDTA',
        'rTBodyIndRankingMutualistasPreXLSX',
        'rTBodyIndRankingUltBalCoacsMutPreXLSX'
    ].forEach(id => {
        const tbody = document.getElementById(id);
        if (tbody) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center text-danger">${mensaje}</td></tr>`;
        }
    });
}

// Función principal para obtener y mostrar reportes
function fetchIndRanking(carpetaReportes) {
    const url = `${baseurl}/backend/reportes/listFilesRepVersion.php?carpeta=${encodeURIComponent(carpetaReportes)}`;
    //console.log("Fetching URL:", url);

    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
            return response.json();
        })
        .then(data => {
            //console.log("Datos recibidos:", data);
            const categorias = {
                coacsXlsx: {
                    archivos: data.filter(item => (item.name.includes('ind_ranking_coacs')) && 
                    (item.name.endsWith('.xlsx') || item.name.endsWith('.dta') || item.name.endsWith('.rds'))),
                    tablaId: 'rTBodyIndRankingCoacsPreXLSX'
                },
                 mutualistasXlsx: {
                    archivos: data.filter(item => item.name.includes('ind_ranking_mutualistas')  && 
                    (item.name.endsWith('.xlsx') || item.name.endsWith('.dta') || item.name.endsWith('.rds'))),
                    tablaId: 'rTBodyIndRankingMutualistasPreXLSX'
                },
                coacsMutualistasXlsx: {
                    archivos: data.filter(item => item.name.includes('ind_ranking_ult_bal_coacs_y_mutualistas')),
                    tablaId: 'rTBodyIndRankingUltBalCoacsMutPreXLSX'
                },
                /* coacsDta: {
                    archivos: data.filter(item => item.name.includes('ind_ranking_coacs') && 
                    (item.name.endsWith('.dta') || item.name.endsWith('.rds'))),
                    tablaId: 'rTBodyIndRankingCoacsPreDTA'
                }, */
                /* mutualistasDta: {
                    archivos: data.filter(item => item.name.includes('ind_ranking_mutualistas') && 
                    (item.name.endsWith('.dta') || item.name.endsWith('.rds'))),
                    tablaId: 'rTBodyIndRankingMutualistasPreDTA' 
                },*/
                /* PARA LAS OTRAS INTENDENCIAS */
                INFMR: {
                    archivos: data.filter(item => item.name.includes('ind_ranking_ult_bal_coacs_y_mutualistas_preliminar_INFMR') && 
                    item.name.endsWith('.xlsx')),
                    tablaId: 'rTBodyIndRankingINFMR'
                },
                INSESF: {
                    archivos: data.filter(item => item.name.includes('ind_ranking_coacs_y_mutualistas_preliminar_INSESF') && 
                    item.name.endsWith('.xlsx')),
                    tablaId: 'rTBodyIndRankingINSESF'
                },
                /* Para el apartado de reporte de Calificación */
                CALFRIESGO: {
                    archivos: data.filter(item => item.name.includes('reporte_calificacion_riesgo') && 
                    item.name.endsWith('.xlsx')),
                    tablaId: 'rTBodyRepCalfRiegso'
                },
                INDCALFRIESGO: {
                    archivos: data.filter(item => item.name.includes('reporte_indicadores_calificacion_riesgo') && 
                    item.name.endsWith('.xlsx')),
                    tablaId: 'rTBodyRepIndCalfRiesgo'
                }
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
        fetchIndRanking(carpetaReportes);
    } else {
        console.error("La variable 'carpetaReportes' no está definida");
        mostrarErrorEnTodasTablas("Error de configuración: ruta no definida");
    }
});

/* Carga datos en la datatable del riesgo por entidad */
$(document).ready(function() {
    $('#tablaRankingCalf').DataTable({
        "autoWidth": false, // Habilita el ajuste automático de ancho
        "dom": '<"botones"B><"filtro"f><"ctabla"rt><"pie"ip>',
        "buttons": [{
                        extend: 'excelHtml5',
                        title: 'Reporte_Calificacion_Riesgo',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },
                    {
                        extend: 'pdfHtml5',
                        messageTop: 'Reporte_Calificacion_Riesgo',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        download: 'open'
                    },
                    {
                        extend: 'print',
                        messageTop: 'Reporte_Calificacion_Riesgo',
                        orientation: 'landscape',
                        exportOptions: {columns: ':visible'},
                        customize: function ( win ) {
                            $(win.document.body)
                                .css( 'font-size', '12pt' );
                            $(win.document.body).find( 'table' )
                                .addClass( 'compact' )
                                .css( 'font-size', 'inherit' );
                        }
                    },
                    'colvis',
        ],
        "paging": true, // Activa la paginación
        "lengthChange": false, // Oculta el menú de selección de entradas
        "pageLength": 5, // Número de registros por página
        "ordering": true, // Habilita la ordenación
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
        columnDefs: [
        {
            targets: [5,6], // columna c_n_cr_nivel
            searchable: false
        }
    ]

    }); 
});