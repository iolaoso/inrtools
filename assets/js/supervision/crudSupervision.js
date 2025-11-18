// crudSupervision.js - funciones para crear, leer, actualizar y eliminar supervisiones

// Función para crear un nuevo registro de supervisión
function nuevoRegistroSupervision(){

    const ruc = document.getElementById('ruc').value;

    if (!ruc || ruc.trim() === '') {
        mostrarAlerta('No hay RUC especificado', 'warning');
        limpiarTodosLosFormularios();
        return;
    } 
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
        
        //console.log("Datos a guardar:", data);
                
        // API para guardar datos
        
        const url = baseurl + '/backend/supervision/crudSupervision.php?action=guardar_supervision';
        const response = await fetch(url, {  
            method: 'POST',  
            headers: {
                'Content-Type': 'application/json'
            },  
            body: JSON.stringify(data) 
        });

        //console.log("Respuesta del servidor - Status:", response.status);
        
        if (!response.ok) {
             throw new Error(`Error HTTP: ${response.status} ${response.statusText}`);
        }
        
       // Obtener el texto de la respuesta para debugging
        const responseText = await response.text();
        //console.log("Respuesta completa:", responseText);
        
        if (!responseText.trim()) {
            throw new Error('El servidor respondió con una respuesta vacía');
        }
        
        // Intentar parsear como JSON
        let result;
        try {
            result = JSON.parse(responseText);
        } catch (parseError) {
            console.error('Error parseando JSON:', parseError);
            console.error('Respuesta recibida:', responseText);
            throw new Error('Respuesta del servidor no es JSON válido');
        }
        
        //console.log("Resultado parseado:", result);
        
        return { success: true, data: result };
        
    } catch (error) {
        console.error('Error al guardar:', error);
        throw new Error('Error al guardar la supervisión: ' + error.message);
    }
}
