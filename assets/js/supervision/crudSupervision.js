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
        
        // Recoje los datos del formulario y los guarda em data
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

// Funcion peguntar si eliminar registro 
async function eliminarSupervision(){

    // VALLIDAR RUC 
    const ruc = document.getElementById('ruc').value;
    if (!ruc || ruc.trim() === '') {
        mostrarAlerta('No hay RUC especificado', 'warning');
        return;
    } 
    //VALIDAR ID
    const idAvances = document.getElementById('id_avances').value;
    if (!idAvances || idAvances.trim() === '') {
        mostrarAlerta('No hay ID especificado', 'warning');
        return;
    } 
    //VALIDAR CODUNICO
    const codUnico = document.getElementById('cod_unico_avances').value;
    if (!codUnico || codUnico.trim() === '') {
        mostrarAlerta('No hay CODUNICO especificado', 'warning');
        return;
    }

    // Mostrar confirmación con SweetAlert
    const result = await Swal.fire({
        title: '¿Eliminar la Supervisión?',
        text: "¿Está seguro de eliminar los datos del registro?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, Eliminar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            
            const idavances = document.getElementById('id_avances');
            const codUnico = document.getElementById('cod_unico_avances');
            const usuario = nickname;
            return eliminarDatosSupervision(idavances, codUnico, usuario);
        }
    });
    
    if (result.isConfirmed) {
        await Swal.fire({
            icon: 'success',
            title: '¡Eliminado!',
            text: 'Supervisión eliminada correctamente',
            timer: 2000,
            showConfirmButton: false
        });
        
        // limpiar formulario
        limpiarTodosLosFormularios();
        ocultarTodosLosFormularios();
    }
}

async function eliminarDatosSupervision(idAvances, codUnico, usuario = null) {

    try {
        // Validar que tenemos el ID requerido
        if (!idAvances) {
            throw new Error('El ID de avances es requerido para eliminar la supervisión');
        }

        // Obtener el usuario actual si no se proporciona
        let usuarioActual = usuario;
        if (!usuarioActual) {
            // Aquí puedes obtener el usuario de tu sistema de autenticación
            // Por ejemplo: localStorage, sessionStorage, o contexto de la aplicación
            usuarioActual = localStorage.getItem('currentUser') || 'UsuarioSistema';
        }

        // Construir el objeto data correctamente
        const data = {
            idavances: idAvances,
            codUnico: codUnico, 
            usuario: usuarioActual
        };

        // Construir la URL correctamente (sin parámetro adicional en la URL)
        const url = baseurl + '/backend/supervision/crudSupervision.php?action=eliminar_supervision';

        console.log('Enviando solicitud de eliminación:', {
            url: url,
            data: data
        });

        const response = await fetch(url, {  
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },  
            body: JSON.stringify(data) 
        });

        console.log("Respuesta del servidor - Status:", response.status);
        
        if (!response.ok) {
            // Intentar obtener más información del error
            const errorText = await response.text();
            console.error("Detalles del error HTTP:", errorText);
            throw new Error(`Error HTTP: ${response.status} ${response.statusText}`);
        }
            
        // Obtener el texto de la respuesta
        const responseText = await response.text();
        console.log("Respuesta completa:", responseText);
        
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
        
        console.log("Resultado parseado:", result);
        
        // Verificar si la operación fue exitosa según el backend
        if (!result.success) {
            throw new Error(result.message || 'Error al eliminar la supervisión');
        }
        
        return { 
            success: true, 
            data: result,
            message: result.message || 'Supervisión eliminada exitosamente'
        };
        
    } catch (error) {
        console.error('Error al eliminar supervisión:', error);
        
        // Retornar objeto de error consistente
        return { 
            success: false, 
            error: error.message,
            message: 'Error al eliminar la supervisión: ' + error.message
        };
    }
}



