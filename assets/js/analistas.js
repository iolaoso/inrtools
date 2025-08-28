function mostrarAnalistaEjecutante(isDirector) {
    const select = document.getElementById('analistaEjecutanteSelect');
    const label = document.getElementById('lbAnalistaEjecutanteSelect');
    if (isDirector == 'DIRECTOR' || 
        isDirector == 'ADMNINSTRADOR' || 
        isDirector == 'SUPERUSER' || 
        isDirector == 'DIRADMINDNR' || 
        isDirector == 'DIRADMINDNS' ||
        isDirector == 'DIRADMINDNSES' ||  
        isDirector == 'DIRADMINDNPLA'
    ) {
        select.style.display = 'block'; // Muestra el select
        label.style.display ='block';
    } else {
        select.style.display = 'none';    // Oculta el select
        label.style.display ='none';
    }
}

// Función para actualizar el valor del input basado en la selección del select
function actualizarInputAnalista() {
    const select = document.getElementById('analistaEjecutanteSelect');
    const input = document.getElementById('analistaEjecutante');

    input.value = select.value; // Actualiza el input con el valor seleccionado
}