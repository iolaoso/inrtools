/* ---------------------------------------------------- */
/* Funciones para el manejor de los select */
/* ---------------------------------------------------- */


// Función para establecer el valor seleccionado de un select por su ID
async function establecerValorSelect(selectId, valor) {
    const select = document.getElementById(selectId);
    if (!select) {
        console.error(`Select con ID ${selectId} no encontrado`);
        return false;
    }
    
    // Buscar la opción que coincida con el valor
    for (let i = 0; i < select.options.length; i++) {
        //console.log(`Comparando opción ${select.options[i].value} con valor ${valor}`);
        if (select.options[i].value === valor.toString()) {
            select.selectedIndex = i;

            // Esperar a que todos los micro-tasks se completen
            await new Promise(resolve => setTimeout(resolve, 0));

            // Disparar eventos
            const clickProcessed = new Promise(resolve => {
                const handleClick = () => {
                    select.removeEventListener('click', handleClick);
                    resolve(true);
                };
                select.addEventListener('click', handleClick);
                
                // Disparar el evento
                 select.dispatchEvent(new Event('click', { bubbles: true }));
            });
            
            await clickProcessed;
            
            const inputProcessed = new Promise(resolve => {
                const handleInput = () => {
                    select.removeEventListener('input', handleInput);
                    resolve(true);
                };
                select.addEventListener('input', handleInput);  
                // Disparar el evento
                select.dispatchEvent(new Event('input', { bubbles: true }));
            });
            
            await inputProcessed;
            const changeProcessed = new Promise(resolve => {
                const handleChange = () => {
                    select.removeEventListener('change', handleChange);
                    resolve(true);
                };
                select.addEventListener('change', handleChange);
                
                // Disparar el evento
                select.dispatchEvent(new Event('change', { bubbles: true }));
            });
            await changeProcessed;

            // Esperar adicional para cualquier proceso async interno
            await new Promise(resolve => setTimeout(resolve, 100));

            return true;
        }
    }
    
    // Si no encuentra el valor, mostrar advertencia
    console.warn(`⚠️ Valor ${valor} no encontrado en las opciones del select ${selectId}`);
    return false;
}

// Función para establecer el valor seleccionado de un select buscando por texto
async function establecerValorSelectPorTexto(selectId, textoBuscado) {
    const select = document.getElementById(selectId);
    if (!select) return false;
    
    const texto = textoBuscado?.toString().toLowerCase().trim() || '';
    
    for (let i = 0; i < select.options.length; i++) {
        //console.log(`Comparando opción "${select.options[i].text.toLowerCase()}" con texto "${texto}"`);
        if (select.options[i].text.toLowerCase().includes(texto)) {
            select.selectedIndex = i;
            
            // Esperar a que todos los micro-tasks se completen
            await new Promise(resolve => setTimeout(resolve, 0));

            // Disparar otros eventos si es necesario
            const clickProcessed = new Promise(resolve => {
                const handleClick = () => {
                    select.removeEventListener('click', handleClick);
                    resolve(true);
                };
                select.addEventListener('click', handleClick);
                
                // Disparar el evento
                 select.dispatchEvent(new Event('click', { bubbles: true }));
            });
            
            await clickProcessed;

            // Disparar evento input
            const inputProcessed = new Promise(resolve => {
                const handleInput = () => {
                    select.removeEventListener('input', handleInput);
                    resolve(true);
                };
                select.addEventListener('input', handleInput);

                // Disparar el evento
                select.dispatchEvent(new Event('input', { bubbles: true }));
            });
            
            await inputProcessed;
            
            // Disparar evento change y ESPERAR a que se procese completamente
            const changeProcessed = new Promise(resolve => {
                const handleChange = () => {
                    select.removeEventListener('change', handleChange);
                    resolve(true);
                };
                select.addEventListener('change', handleChange);
                
                // Disparar el evento
                select.dispatchEvent(new Event('change', { bubbles: true }));
            });
            
            await changeProcessed;

            
            
            // Esperar adicional para cualquier proceso async interno
            await new Promise(resolve => setTimeout(resolve, 100));
            
            return true;
        }
    }
    
    return false;
}