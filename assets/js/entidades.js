function seleccionarEntidad(ruc, razonSocial) {
    // Llenar el input con el RUC
    document.getElementById('ruc').value = ruc;

    // Verificar si existe el elemento con ID 'razon_social'
    const razonSocialInput = document.getElementById('tbrazonSocial');
    if (razonSocialInput) {
        // Si existe, llenar el input con la razón social
        razonSocialInput.value = razonSocial;
    }
}

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
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
}

