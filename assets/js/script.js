function loadSection(section) {
    const contentDiv = document.getElementById('content');
    const pageTitle = document.getElementById('page-title');

    // Cambiar el título de la página
    pageTitle.textContent = `Sección: ${section.charAt(0).toUpperCase() + section.slice(1)}`;

    // Cargar el contenido de la sección
    fetch(`views/${section}.php`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Sección no encontrada');
            }
            return response.text();
        })
        .then(data => {
            contentDiv.innerHTML = data;
        })
        .catch(error => {
            contentDiv.innerHTML = `<p>${error.message}</p>`;
        });
}

// Cargar la sección por defecto al iniciar
document.addEventListener('DOMContentLoaded', () => {
    loadSection('general');
});