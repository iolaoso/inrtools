// assets/js/direcciones/direcciones.js
document.addEventListener('DOMContentLoaded', function() {
    console.log('Direcciones JS cargado correctamente');
    
    const base_url = document.getElementById('base_url').getAttribute('data-base-url');
    
    // Variable para controlar si estamos en modo edición
    let isEditing = false;
    
    // Función para formatear el estado (1 = Activo, 0 = Inactivo)
    function formatearEstado(valor) {
        // Convertir a número por si viene como string
        const estadoNum = parseInt(valor);
        return estadoNum === 1 ? 'Activo' : 'Inactivo';
    }
    
    // ===========================================
    // MANEJAR BOTONES DE EDITAR
    // ===========================================
    const botonesEditar = document.querySelectorAll('.btn-editar');
    
    botonesEditar.forEach(function(boton) {
        boton.addEventListener('click', function(event) {
            event.preventDefault();
            
            console.log('Botón editar clickeado');
            
            // Marcar que estamos en modo edición
            isEditing = true;
            
            // Obtener datos de los atributos data-
            const id = this.getAttribute('data-id');
            const direccion = this.getAttribute('data-direccion');
            const dirNombre = this.getAttribute('data-dirnombre');
            const estado = this.getAttribute('data-estado');
            
            console.log('Datos obtenidos:', { 
                id, 
                direccion, 
                dirNombre, 
                estado, 
                estadoFormateado: formatearEstado(estado) 
            });
            
            // Cambiar título del modal
            document.getElementById('modalTitle').textContent = 'Editar Dirección';
            
            // Asignar valores a los inputs
            document.getElementById('id').value = id;
            document.getElementById('direccion').value = direccion;
            document.getElementById('dirNombre').value = dirNombre;
            
            // Asignar valor formateado al input de estado (Activo/Inactivo)
            document.getElementById('estRegistro').value = formatearEstado(estado);
            
            // Mostrar el modal (Bootstrap 5)
            const modalElement = document.getElementById('modalDireccion');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        });
    });
    
    // ===========================================
    // MANEJAR APERTURA DEL MODAL
    // ===========================================
    const modalElement = document.getElementById('modalDireccion');
    
    modalElement.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        
        console.log('Modal abriéndose. Botón:', button ? button.className : 'null');
        console.log('isEditing:', isEditing);
        
        // SOLO limpiar si es nueva dirección (botón verde) y NO estamos en modo edición
        if (button && button.classList.contains('btn-success') && !isEditing) {
            console.log('Limpiando formulario para nueva dirección');
            document.getElementById('modalTitle').textContent = 'Nueva Dirección';
            document.getElementById('formDireccion').reset();
            document.getElementById('id').value = '';
            // Para nueva dirección, el estado por defecto es Activo (1)
            document.getElementById('estRegistro').value = 'Activo';
        }
    });
    
    // ===========================================
    // MANEJAR CIERRE DEL MODAL
    // ===========================================
    modalElement.addEventListener('hidden.bs.modal', function() {
        console.log('Modal cerrado, reseteando bandera isEditing');
        isEditing = false;
    });
    
    // ===========================================
    // MANEJAR BOTONES DE ELIMINAR
    // ===========================================
    const botonesEliminar = document.querySelectorAll('.btn-eliminar');
    
    botonesEliminar.forEach(function(boton) {
        boton.addEventListener('click', function(event) {
            event.preventDefault();
            
            const id = this.getAttribute('data-id');
            console.log('Eliminar dirección ID:', id);
            
            const deleteUrl = base_url + '/backend/direcciones/eliminarDireccion.php?id=' + id;
            document.getElementById('btnConfirmarEliminar').setAttribute('href', deleteUrl);
            
            const modalEliminar = new bootstrap.Modal(document.getElementById('modalEliminar'));
            modalEliminar.show();
        });
    });
    
    // ===========================================
    // VALIDAR FORMULARIO ANTES DE ENVIAR
    // ===========================================
    const formulario = document.getElementById('formDireccion');
    
    formulario.addEventListener('submit', function(event) {
        const direccion = document.getElementById('direccion').value.trim();
        const dirNombre = document.getElementById('dirNombre').value.trim();
        
        console.log('Validando formulario:', { direccion, dirNombre });
        
        if (direccion === '' || dirNombre === '') {
            event.preventDefault();
            alert('Todos los campos son obligatorios');
            return false;
        }
        
        // Deshabilitar botón de envío
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Guardando...';
    });
    
    // ===========================================
    // OCULTAR ALERTAS AUTOMÁTICAMENTE
    // ===========================================
    setTimeout(function() {
        const alertas = document.querySelectorAll('.alert-success, .alert-danger');
        alertas.forEach(function(alerta) {
            alerta.style.transition = 'opacity 0.5s';
            alerta.style.opacity = '0';
            setTimeout(function() {
                alerta.style.display = 'none';
            }, 500);
        });
    }, 3000);
    
    console.log('Eventos configurados correctamente');
});