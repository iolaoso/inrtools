let tiempoLimite = 20 * 60; // 5 minutos en segundos
let avisoTiempo = 2 * 60; // 2 minutos para el aviso
let tiempoActividad;
let intervalo;

function iniciarContador() {
    let tiempoRestante = tiempoLimite;

    // Actualizar el contador en el DOM
    intervalo = setInterval(function() {
        let minutos = Math.floor(tiempoRestante / 60);
        let segundos = tiempoRestante % 60;
        document.getElementById('tiempo-restante').innerText = 
            `${minutos}:${segundos < 10 ? '0' : ''}${segundos}`;
        
        if (tiempoRestante <= 0) {
            clearInterval(intervalo);
            window.location.href = `${baseurl}`;
        }

        tiempoRestante--;
    }, 1000);

    // Avisar al usuario 5 minutos antes de caducar
    setTimeout(function() {
        //alert("Tu sesión está a punto de caducar. Por favor, guarda tu trabajo.");
    }, (tiempoLimite - avisoTiempo) * 1000);
}

function resetContador() {
    clearInterval(intervalo); // Detener el contador existente
    iniciarContador(); // Reiniciar el contador
}

// Iniciar el contador al cargar la página
window.onload = iniciarContador;

// Agregar listeners para eventos de interacción
document.addEventListener('mousemove', resetContador);
document.addEventListener('keypress', resetContador);