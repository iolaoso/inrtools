const menuItems = [
    {
        title: 'HOME',
        subMenu: [],
        url: '/INRtools/frontend/main.php',
        icon: 'fa-solid fa-home',
        direccion: ['ALL'],
        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
        usuarios: [] // Opcional: lista de usuarios específicos permitidos
    },
    {
        title: 'INR',
        subMenu: [
            {   title: 'Gestión INR', 
                url: '/INRtools/frontend/inr/gestioninr/gestioninr.php', 
                direccion: ['ALL'],
                rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                usuarios: [] 
            },
            {   title: 'Validar Estructuras', 
                url: '/INRtools/frontend/inr/valestructuras/valestructuras.php',
                direccion: ['ALL'],
                rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                usuarios: [] 
            },
            {
                title: 'Reportes',
                subMenu: [
                    {   title: 'Reporte de Diagnóstico', 
                        url: '/INRtools/frontend/inr/reportes/rdiagnostico/rdiagnostico.php',
                        direccion: ['ALL'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: [] 
                    },
                    {   title: 'Comite', 
                        url: '/INRtools/frontend/inr/reportes/comite/comiteTecnico.php',
                        direccion: ['ALL'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: [] 
                    },
                    {   title: 'Boletin INR', 
                        url: '/INRtools/frontend/inr/reportes/boletin/boletininr.php',
                        direccion: ['ALL'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: [] 
                    }
                ],
                direccion: ['ALL'],
                rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                usuarios: [] 
            } 
        ],
        icon: 'fa-solid fa-building',
        direccion: ['ALL'],
        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
        usuarios: [] 
    },
    {
        title: 'DNR',
        subMenu: [
            {   title: 'Estructuras', 
                url: '/INRtools/frontend/dnr/estructuras/estructuras.php',
                direccion: ['DNR'],
                rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                usuarios: [] 
            },
            {   title: 'PSI', 
                url: '/INRtools/frontend/dnr/psi/psi.php',
                direccion: ['DNR'],
                rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                usuarios: [] 
            },
            {
                title: 'Reportes',
                subMenu: [
                    {   title: 'Riesgo de Crédito', 
                        subMenu: [
                            {   title: 'Perdidas Esperadas', 
                                url: '/INRtools/frontend/dnr/reportes/alertas/perdidas_esperadas.php',
                                //url: '/INRtools/frontend/enConstruccion.php',
                                direccion: ['ALL'],
                                rol: ['SUPERUSER','ADMINISTRADOR','DIRECTOR'],
                                usuarios: [] 
                            },
                            {   title: 'Monitoreo Mora', 
                                //url: '/INRtools/frontend/dnr/reportes/alertas/monitoreo_mora.php',
                                url: '/INRtools/frontend/enConstruccion.php',
                                direccion: ['ALL'],
                                rol: ['SUPERUSER','ADMINISTRADOR','DIRECTOR'],
                                usuarios: [] 
                            },
                            {   title: 'Reporte Cosechas Cubo', 
                                //url: '/INRtools/frontend/dnr/reportes/alertas/reporte_cosechas.php',
                                url: '/INRtools/frontend/enConstruccion.php',
                                direccion: ['ALL'],
                                rol: ['SUPERUSER','ADMINISTRADOR','DIRECTOR'],
                                usuarios: [] 
                            },
                            {   title: 'Cartera Socios Clientes', 
                                //url: '/INRtools/',
                                url: '/INRtools/frontend/enConstruccion.php',
                                direccion: ['ALL'],
                                rol: ['SUPERUSER','ADMINISTRADOR','DIRECTOR'],
                                usuarios: [] 
                            },
                        ],
                        direccion: ['DNR'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: []
                    },
                    {   title: 'Riesgo de Liquidez',    
                        subMenu: [
                            {   title: 'Cumplimiento Liquidez Estructural', 
                                //url: '/INRtools/',
                                url: '/INRtools/frontend/enConstruccion.php',
                                direccion: ['ALL'],
                                rol: ['SUPERUSER','ADMINISTRADOR','DIRECTOR'],
                                usuarios: [] 
                            },
                            {   title: 'Reporte liquidez diaria', 
                                //url: '/INRtools/frontend/dnr/reportes/alertas/reporte_liquidez_diaria.php',
                                url: '/INRtools/frontend/enConstruccion.php',
                                direccion: ['ALL'],
                                rol: ['SUPERUSER','ADMINISTRADOR','DIRECTOR'],
                                usuarios: [] 
                            },
                            {   title: 'Reporte liquidez', 
                                //url: '/INRtools/frontend/dnr/reportes/alertas/reporte_liquidez.php',
                                url: '/INRtools/frontend/enConstruccion.php',
                                direccion: ['ALL'],
                                rol: ['SUPERUSER','ADMINISTRADOR','DIRECTOR'],
                                usuarios: [] 
                            }
                        ],
                        direccion: ['DNR'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: []
                    },
                    {   title: 'Calidad de Activos',
                        subMenu: [
                            {   title: 'Reporte Inversiones y fondos disponibles', 
                                //url: '/INRtools/frontend/dnr/reportes/alertas/reporte_inversiones.php',
                                url: '/INRtools/frontend/enConstruccion.php',
                                direccion: ['ALL'],
                                rol: ['SUPERUSER','ADMINISTRADOR','DIRECTOR'],
                                usuarios: [] 
                            }
                        ],
                        direccion: ['DNR'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: []
                    },
                    {   title: 'Calidad de Resultados',
                        subMenu: [
                            {   title: 'Alertas Rentabilidad', 
                                //url: '/INRtools/frontend/dnr/reportes/alertas/alertas_rentabilidad.php',
                                url: '/INRtools/frontend/enConstruccion.php',
                                direccion: ['ALL'],
                                rol: ['SUPERUSER','ADMINISTRADOR','DIRECTOR'],
                                usuarios: [] 
                            }
                        ],
                        direccion: ['DNR'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: []
                    },
                    {   title: 'Riesgo de Mercado',
                        subMenu: [
                            {   title: 'Tasas ponderadas activas', 
                                //url: '/INRtools/frontend/dnr/reportes/alertas/tasas_ponderadas_activas.php',
                                url: '/INRtools/frontend/enConstruccion.php',
                                direccion: ['ALL'],
                                rol: ['SUPERUSER','ADMINISTRADOR','DIRECTOR'],
                                usuarios: [] 
                            }
                        ],
                        direccion: ['DNR'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: []
                    },
                    {   title: 'Riesgo de Interconexión',
                        subMenu: [
                            {   title: 'Base de cálculo ranking total               ', 
                                //url: '/INRtools/frontend/dnr/reportes/alertas/base_calculo_ranking.php',
                                url: '/INRtools/frontend/enConstruccion.php',
                                direccion: ['ALL'],
                                rol: ['SUPERUSER','ADMINISTRADOR','DIRECTOR'],
                                usuarios: [] 
                            },
                        ],
                        direccion: ['DNR'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: [] 
                    },
                    {   title: 'Brechas', 
                        url: '/INRtools/frontend/dnr/reportes/brechas/brechas.php',
                        direccion: ['DNR'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: [] 
                    },
                    {   title: 'Endeudamiento', 
                        url: '/INRtools/frontend/dnr/reportes/endeudamiento/endeudamiento.php',
                        direccion: ['DNR'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: [] 
                    },
                    {   title: 'Saras', 
                        url: '/INRtools/frontend/dnr/reportes/saras/saras.php',
                        direccion: ['DNR', 'DNS'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: [] 
                    },
                    {   title: 'Conafips', 
                        url: '/INRtools/frontend/dnr/reportes/conafips/conafips.php',
                        direccion: ['DNR'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: [] 
                    },
                    {   title: 'Riesgo Ambiental', 
                        url: '/INRtools/frontend/dnr/reportes/rambiental/rambiental.php',
                        direccion: ['DNR','DNS'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: [] 
                    }
                ],
                direccion: ['ALL'],
                rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                usuarios: [] 
            }  
        ],
        icon: 'fa-solid fa-chart-line',
        direccion: ['ALL'],
        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
        usuarios: [] 
    },
    {
        title: 'DNSES',
        subMenu: [
            {
                title: 'Reportes',
                subMenu: [
                    {   title: 'Variaciones B11', 
                        url: '/INRtools/frontend/dnses/reportes/variacionesb11/variacionesb11.php',
                        direccion: ['DNSES','DNR'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: [] 
                    },
                ],
                direccion: ['ALL'],
                rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                usuarios: [] 
            } 
        ],
        icon: 'fa-solid fa-chart-simple',
        direccion: ['ALL'],
        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
        usuarios: [] 
    },
    {
        title: 'DNS',
        subMenu: [
            { title: 'Informes', 
                url: '/INRtools/frontend/dns/informes/informes.php',
                direccion: (usrRol === 'SUPERUSER') ? ['ALL'] : ['DNS'], 
                rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                usuarios: [] 
            },
            {
                title: 'Reportes',
                subMenu: [
                    {   title: 'Hallazgos EPS', 
                        url: '/INRtools/frontend/dns/reportes/hallazgoseps/hallazgoseps.php',
                        direccion: ['DNR','DNS'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: [] 
                    },
                    {   title: 'Diag. Situacionales', 
                        url: '/INRtools/frontend/dns/reportes/diagsituacional/diagsituacional.php',
                        direccion: ['DNR','DNS'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: [] 
                    },
                    {   title: 'Hal General', 
                        url: '/INRtools/frontend/dns/reportes/halgeneraleps/halgeneraleps.php',
                        direccion: ['DNR','DNS'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: [] 
                    },
                    {   title: 'As AGSEPS', 
                        url: '/INRtools/frontend/dns/reportes/asagseps/asagseps.php',
                        direccion: ['DNR','DNS'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: [] 
                    },
                    {   title: 'A. Externos', 
                        url: '/INRtools/frontend/dns/reportes/aexternos/aexternos.php',
                        direccion: ['DNR','DNS'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                        usuarios: [] 
                    }
                ],
                direccion: ['ALL'],
                rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
                usuarios: [] 
            },
        ],
        icon: 'fa-solid fa-network-wired',
        direccion: ['ALL'],
        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
        usuarios: [] 
    },
    {
        title: 'DNPLA',
        subMenu: [],
        icon: 'fa-solid fa-film',
        direccion: ['ALL'],
        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
        usuarios: [] 
    },
    {
        title: 'Configuración',
        subMenu: [
            { title: 'New Person', 
                url: '/INRtools/frontend/configuracion/users/newPerson.php',
                direccion: ['ALL'],
                rol: ['SUPERUSER','ADMINISTRADOR'],
                usuarios: [] 
            },
            { title: 'Add/Del User', 
                url: '/INRtools/frontend/configuracion/users/addDelUser.php',
                direccion: ['ALL'],
                rol: ['SUPERUSER','ADMINISTRADOR'],
                usuarios: [] 
            },
        ],
        icon: 'fa-solid fa-cog',
        direccion: ['ALL'],
        rol: ['SUPERUSER','ADMINISTRADOR'],
        usuarios: [] 
    },
    {
        title: 'Perfil',
        subMenu: [],
        icon: 'fa-solid fa-user',
        url: '/INRtools/frontend/profile.php',
        direccion: ['ALL'],
        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
        usuarios: [] 
    },
    {
        title: 'Acerca de',
        subMenu: [],
        icon: 'fa-solid fa-info-circle',
        url: '/INRtools/frontend/about.php',
        direccion: ['ALL'],
        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR'],
        usuarios: [] 
    },
    {
        title: 'EN CONSTRUCCIÓN',
        subMenu: [
            { 
              title: 'Tareas', 
              url: '/INRtools/frontend/mUtilidades/tareas/gestionTareas.php',
              direccion: ['DNR'],
              rol: ['SUPERUSER'],
              usuarios: [] 
            },
        ],
        url: '/INRtools/frontend/porUsuario.php',
        icon: 'fa fa-file-text',
        direccion: ['ALL'],
        rol: ['SUPERUSER'],
        usuarios: ['ILOPEZ'] // Opcional: lista de usuarios específicos permitidos
    },
];