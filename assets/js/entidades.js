// assets/js/entidades.js


// función para seleccionar una entidad y llenar los campos correspondientes
function seleccionarEntidad(ruc, razonSocial, segmento, estadoJuridico, tipoOrganizacion, zonal, fecUltBalance, nvlRiesgo) {
    // Llenar el input con el RUC
    document.getElementById('ruc').value = ruc;

    // Verificar si existe el elemento con ID 'razon_social'
    const razonSocialInput = document.getElementById('tbrazonSocial');
    if (razonSocialInput) {
        // Si existe, llenar el input con la razón social
        razonSocialInput.value = razonSocial;
    }
    // Verificar si existe el elemento con ID 'segmento'
    const segmentoInput = document.getElementById('tbsegmento');
    if (segmentoInput) {
        // Si existe, llenar el input con el segmento
        segmentoInput.value = segmento;
    }
    // Verificar si existe el elemento con ID 'estado_juridico'
    const estadoJuridicoInput = document.getElementById('estado_juridico');
    if (estadoJuridicoInput) {
        // Si existe, llenar el input con el estado jurídico
        estadoJuridicoInput.value = estadoJuridico;
    }
    // Verificar si existe el elemento con ID 'tipo_organizacion'
    const tipoOrganizacionInput = document.getElementById('tipo_organizacion');
    if (tipoOrganizacionInput) {
        // Si existe, llenar el input con el tipo de organización
        tipoOrganizacionInput.value = tipoOrganizacion;
    }
    // Verificar si existe el elemento con ID 'zonal'
    const zonalInput = document.getElementById('zonal');
    if (zonalInput) {
        // Si existe, llenar el input con la zonal
        zonalInput.value = zonal;
    }
    // Verificar si existe el elemento con ID 'fec_ult_balance'
    const fecUltBalanceInput = document.getElementById('fec_ult_balance');  
    if (fecUltBalanceInput) {
        // Si existe, llenar el input con la fecha del último balance
        fecUltBalanceInput.value = fecUltBalance;
    }
    // Verificar si existe el elemento con ID 'nvl_riesgo'
    const nvlRiesgoInput = document.getElementById('nvl_riesgo');
    if (nvlRiesgoInput) {
        // Si existe, llenar el input con el nivel de riesgo
        nvlRiesgoInput.value = nvlRiesgo;
    }
    // Cerrar el modal
    const catastroModal = new bootstrap.Modal(document.getElementById('catastroModal'));
    catastroModal.hide();
}

// función para buscar una entidad por RUC y llenar los campos correspondientes
function buscarEntidad() {
    const pageName = document.getElementById('ruc').getAttribute('data-page'); // Obtener el nombre de la página
    const ruc = document.getElementById('ruc').value;
    if (ruc.length >= 13) { // Ejecutar solo si hay 13 o más caracteres
        fetch(`${pageName}?ruc=${encodeURIComponent(ruc)}`)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                const tbrazonSocial = document.getElementById('tbrazonSocial');
                if (data.success) {
                    tbrazonSocial.value = data.entidad; // Colocar resultado en el textarea
                } else {
                    tbrazonSocial.value ='';
                }
                const tbsegmento = document.getElementById('tbsegmento');
                if (data.success) {
                    tbsegmento.value = data.segmento; // Colocar resultado en el input
                } else {
                    tbrazontbsegmentoSocial.value ='';
                }
                const estadoJuridicoInput = document.getElementById('estado_juridico');
                if (data.success) {
                    estadoJuridicoInput.value = data.estado_juridico;
                } else {
                    estadoJuridicoInput.value = '';
                }
                const tipoOrganizacionInput = document.getElementById('tipo_organizacion');
                if (data.success) {
                    tipoOrganizacionInput.value = data.tipo_organizacion;
                } else {
                    tipoOrganizacionInput.value = '';
                }
                const zonalInput = document.getElementById('zonal');
                if (data.success) {
                    zonalInput.value = data.zonal;
                } else {
                    zonalInput.value = '';
                }
                const fecUltBalanceInput = document.getElementById('fec_ult_balance');
                if (data.success) {
                    fecUltBalanceInput.value = data.fec_ult_balance;
                } else {
                    fecUltBalanceInput.value = '';
                }
                const nvlRiesgoInput = document.getElementById('nvl_riesgo');
                if (data.success) {
                    nvlRiesgoInput.value = data.nvl_riesgo;
                } else {
                    nvlRiesgoInput.value = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
           }
        );
    }
    //cerrar el modal si está abierto
    const catastroModal = new bootstrap.Modal(document.getElementById('catastroModal'));
    catastroModal.hide();
}

