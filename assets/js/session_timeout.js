/* const TIEMPO_LIMITE = 20 * 60; // 20 minutos en segundos (constante en mayúsculas)
const AVISO_TIEMPO = 5 * 60; // 5 minutos para el aviso
let intervaloContador;

function iniciarContador() {
    let tiempoRestante = TIEMPO_LIMITE;
    
    // Limpiar intervalo previo si existe
    if (intervaloContador) clearInterval(intervaloContador);
    
    // Función para formatear el tiempo
    const formatearTiempo = (segundos) => {
        const minutos = Math.floor(segundos / 60);
        const segundosRestantes = segundos % 60;
        return `${minutos}:${segundosRestantes < 10 ? '0' : ''}${segundosRestantes}`;
    };

    // Actualizar el contador inmediatamente
    document.getElementById('tiempo-restante').textContent = formatearTiempo(tiempoRestante);
    
    intervaloContador = setInterval(() => {
        tiempoRestante--;
        document.getElementById('tiempo-restante').textContent = formatearTiempo(tiempoRestante);
        
        if (tiempoRestante <= 0) {
            clearInterval(intervaloContador);
            realizarLogout();
        }
    }, 1000);
}

function realizarLogout() {
    fetch('logout.php', {
        method: 'GET',
        credentials: 'include'
    })
    .catch(error => console.error('Error al llamar logout.php:', error))
    .finally(() => {
        window.location.href = baseurl;
    });
}

function resetContador() {
    iniciarContador();
}

// Iniciar el contador al cargar la página y configurar event listeners
//window.addEventListener('load', iniciarContador);
//document.addEventListener('mousemove', resetContador);
//document.addEventListener('keypress', resetContador); */