async function registrarDescarga(nombreArchivo, rutaArchivo, tamanioArchivo, modulo) {
    //console.log ("registrarDescarga");
    try {
        const response = await fetch(
            `${baseurl}/backend/bitacora/registrarBitacora.php`,
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    accion: 'DESCARGA',
                    modulo: modulo,
                    descripcion: 'Descarga de archivo',
                    nombreArchivo: nombreArchivo,
                    rutaArchivo: rutaArchivo,
                    tipoArchivo: nombreArchivo.split('.').pop(),
                    tamanioArchivo: tamanioArchivo
                })
            }
        );
        const resultado = await response.json();
        //console.log (resultado);
        if (!resultado.success) {
            console.warn(
                'No fue posible registrar la descarga:',
                resultado.message
            );
        }
    } catch (error) {
        console.error(
            'Error registrando descarga:',
            error
        );
    }
}

// IMPORTANTE PARA GUARDAR LA BITACORA CUANDO HACEN CLICK EN DESCARGAR 
// ESCUCHA LOS CLICK DE LOS BOTONES QUE TINEN LA CLASE btn-descargar
document.addEventListener('click', async function (e) {
    //console.log ("click boton en el modulo ");
    const boton = e.target.closest('.btn-descargar');
    if (!boton) {
        return;
    }
    const nombreArchivo =
        boton.dataset.nombre;
    const rutaArchivo =
        boton.dataset.ruta;
    const tamanioArchivo =
        Number(boton.dataset.tamanio);
    const partes = boton.dataset.ruta.replace(/\/+$/, '').split('/');
    const modulo = partes[partes.length - 1];
    //console.log (modulo);
    registrarDescarga(
        nombreArchivo,
        rutaArchivo,
        tamanioArchivo,
        modulo
    );
});