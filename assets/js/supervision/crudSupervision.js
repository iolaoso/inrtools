// crudSupervision.js - funciones para crear, leer, actualizar y eliminar supervisiones

// Función para crear un nuevo registro de supervisión
function nuevoRegistroSupervision(){
    limpiarTodosLosFormularios();
    mostrarFormularioIndividual('sAvancesSupervision'); // Mostrar siempre el formulario de avances    
}
    
// Función guardarSupervision actualizada para onsubmit
async function guardarSupervision(event) {
    event.preventDefault();  // Prevenir el envío tradicional del formulario
    
    // Mostrar confirmación con SweetAlert
    const result = await Swal.fire({
        title: '¿Guardar supervisión?',
        text: "¿Está seguro de guardar los datos de supervisión?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return guardarDatosSupervision();
        }
    });
    
    if (result.isConfirmed) {
        await Swal.fire({
            icon: 'success',
            title: '¡Guardado!',
            text: 'Supervisión guardada correctamente',
            timer: 2000,
            showConfirmButton: false
        });
        
        // limpiar formulario
        limpiarTodosLosFormularios();
        ocultarTodosLosFormularios();
    }
    
    return false; // Siempre retornar false para prevenir envío tradicional
}

// Función para guardar datos via AJAX
async function guardarDatosSupervision() {
    try {
        const form = document.getElementById('frmDatosFull');
        const formData = new FormData(form);
        
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });
        
        console.log("Datos a guardar:", data);
        
        // Simular guardado (reemplazar con tu API real)
        await new Promise(resolve => setTimeout(resolve, 2000));
        
        // Descomentar cuando tengas tu API real
        /*
        const url = baseurl + 'backend/supervision/crudSupervision.php?action=guardarSupervision';
        const response = await fetch(url, {  
            method: 'POST',  
            headers: {
                'Content-Type': 'application/json'
            },  
            body: JSON.stringify(data) 
        });
        
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        
        const result = await response.json();
        
        if (!result.success) {
            throw new Error(result.message || 'Error al guardar');
        }
        */
        
        return { success: true };
        
    } catch (error) {
        console.error('Error al guardar:', error);
        throw new Error('Error al guardar la supervisión: ' + error.message);
    }
}
