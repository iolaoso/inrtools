
const menuItems = [
    {
        title: 'HOME',
        subMenu: [],
        url: '/INRtools/frontend/main.php',
        icon: 'fa-solid fa-home',
        direccion: ['ALL'],
        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
    },
    {
        title: 'INR',
        subMenu: [
            {   title: 'Gestión INR', url: '/INRtools/frontend/inr/gestioninr/gestioninr.php', 
                direccion: ['ALL'],
                rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
            },
            {   title: 'Validar Estructuras', 
                url: '/INRtools/frontend/inr/valestructuras/valestructuras.php',
                direccion: ['ALL'],
                rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
            },
            {
                title: 'Reportes',
                subMenu: [
                    {   title: 'Comite', 
                        url: '/INRtools/frontend/inr/reportes/comite/comiteTecnico.php',
                        direccion: ['ALL'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
                    },
                    {   title: 'Boletin INR', 
                        url: '/INRtools/frontend/inr/reportes/boletin/boletininr.php',
                        direccion: ['ALL'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
                    }
                ],
                direccion: ['ALL'],
                rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
            } 
        ],
        icon: 'fa-solid fa-building',
        direccion: ['ALL'],
        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
        
    },
    {
        title: 'DNR',
        subMenu: [
            {   title: 'Estructuras', url: '/INRtools/frontend/dnr/estructuras/estructuras.php',
                direccion: ['DNR'],
                rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
            },
            {   title: 'PSI', url: '/INRtools/frontend/dnr/psi/psi.php',
                direccion: ['DNR'],
                rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
            },
            {
                title: 'Reportes',
                subMenu: [
                    {   title: 'Brechas', 
                        url: '/INRtools/frontend/dnr/reportes/brechas/brechas.php',
                        direccion: ['ALL'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
                    },
                    {   title: 'Endeudamiento', 
                        url: '/INRtools/frontend/dnr/reportes/endeudamiento/endeudamiento.php',
                        direccion: ['ALL'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
                    },
                    {   title: 'Saras', 
                        url: '/INRtools/frontend/dnr/reportes/saras/saras.php',
                        direccion: ['ALL'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
                    },
                    {   title: 'Conafips', 
                        url: '/INRtools/frontend/dnr/reportes/conafips/conafips.php',
                        direccion: ['ALL'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
                    },
                    {   title: 'Riesgo Ambiental', 
                        url: '/INRtools/frontend/dnr/reportes/rambiental/rambiental.php',
                        direccion: ['ALL'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
                    }
                ],
                direccion: ['ALL'],
                rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
            }  
        ],
        icon: 'fa-solid fa-chart-line',
        direccion: ['ALL'],
        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
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
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
                    },
                ],
                direccion: ['ALL'],
                rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
            } 
        ],
        icon: 'fa-solid fa-chart-simple',
        direccion: ['ALL'],
        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
    },
    {
        title: 'DNS',
        subMenu: [
            { title: 'Informes', 
                url: '/INRtools/frontend/enConstruccion.php',
                //url: '/INRtools/frontend/dns/informes/informes.php',
                //direccion: ['DNS'],
                direccion: (usrRol === 'SUPERUSER') ? ['ALL'] : ['DNS'], 
                rol: ['SUPERUSER']//,'ADMINISTRADOR','ANALISTA','DIRECTOR']
            },
            {
                title: 'Reportes',
                subMenu: [
                    {   title: 'Hallazgos EPS', 
                        url: '/INRtools/frontend/dns/reportes/hallazgoseps/hallazgoseps.php',
                        direccion: ['DNR','DNS'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
                    },
                    {   title: 'Diag. Situacionales', 
                        url: '/INRtools/frontend/dns/reportes/diagsituacional/diagsituacional.php',
                        direccion: ['DNR','DNS'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
                    },
                    {   title: 'Hal General', 
                        url: '/INRtools/frontend/dns/reportes/halgeneraleps/halgeneraleps.php',
                        direccion: ['DNR','DNS'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
                    },
                    {   title: 'As AGSEPS', 
                        url: '/INRtools/frontend/dns/reportes/asagseps/asagseps.php',
                        direccion: ['DNR','DNS'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
                    },
                    {   title: 'A. Externos', 
                        url: '/INRtools/frontend/dns/reportes/aexternos/aexternos.php',
                        direccion: ['DNR','DNS'],
                        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
                    }
                ],
                direccion: ['ALL'],
                rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
            },
                
        ],
        icon: 'fa-solid fa-network-wired',
        direccion: ['ALL'],
        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
    },
    {
        title: 'DNPLA',
        subMenu: [],
        icon: 'fa-solid fa-film',
        direccion: ['ALL'],
        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
    },
    {
        title: 'Configuración',
        subMenu: [
            { title: 'New Person', url: '/INRtools/frontend/configuracion/users/newPerson.php',
                direccion: ['ALL'],
                rol: ['SUPERUSER','ADMINISTRADOR'] 
            },
            { title: 'Add/Del User', url: '/INRtools/frontend/configuracion/users/addDelUser.php',
                direccion: ['ALL'],
                rol: ['SUPERUSER','ADMINISTRADOR'] 
            },
        ],
        icon: 'fa-solid fa-cog',
        direccion: ['ALL'],
        rol: ['SUPERUSER','ADMINISTRADOR']
    },
    {
        title: 'Perfil',
        subMenu: [],
        icon: 'fa-solid fa-user',
        url: '/INRtools/frontend/profile.php',
        direccion: ['ALL'],
        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
    },
    {
        title: 'Acerca de',
        subMenu: [],
        icon: 'fa-solid fa-info-circle',
        url: '/INRtools/frontend/about.php',
        direccion: ['ALL'],
        rol: ['SUPERUSER','ADMINISTRADOR','ANALISTA','DIRECTOR']
    }
];