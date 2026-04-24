const sidebar = document.getElementById('sidebar');

// Función para expandir el sidebar cuando el mouse pasa por encima
function expandSidebarOnHover() {
    sidebar.classList.remove('collapsed'); // Remover la clase colapsada
    sidebar.classList.add('expanded'); // Añadir la clase expandida
}

// Función para colapsar el sidebar cuando el mouse sale
function collapseSidebarOnLeave() {
    sidebar.classList.remove('expanded'); // Remover la clase expandida
    sidebar.classList.add('collapsed'); // Añadir la clase colapsada
}

// Agregar los eventos de hover para expandir y colapsar el sidebar
sidebar.addEventListener('mouseenter', expandSidebarOnHover);
sidebar.addEventListener('mouseleave', collapseSidebarOnLeave);

// Al cargar la página, asegurarnos de que el menú esté colapsado por defecto
window.addEventListener('load', () => {
    sidebar.classList.add('collapsed');
});


function generateMenu(usrDir, usrRol, usrName) {
    const menu = document.getElementById('menu');
    const fragment = document.createDocumentFragment();

    console.log('usrDir:', usrDir);
    console.log('usrRol:', usrRol);
    console.log('usrName:', usrName);

    menuItems.forEach(item => {
        // Validar acceso al menú principal
        const hasUserRestriction = item.usuarios && item.usuarios.length > 0;
        const userAllowed = hasUserRestriction ? item.usuarios.includes(usrName) : true;
        
        if (userAllowed && 
            (item.direccion.includes(usrDir) || item.direccion.includes('ALL')) && 
            (item.rol.includes(usrRol) || item.rol.includes('ALL'))) {

            const li = document.createElement('li');
            li.classList.add('menu-item');

            const header = document.createElement('div');
            header.classList.add('menu-header');

            const icon = document.createElement('i');
            icon.className = `${item.icon} icon`;
            const titleSpan = document.createElement('span');
            titleSpan.textContent = item.title;

            header.appendChild(icon);
            header.appendChild(titleSpan);
            li.appendChild(header);

            if (item.subMenu.length > 0) {
                const arrow = document.createElement('i');
                arrow.className = 'fas fa-chevron-right arrow';
                header.appendChild(arrow);

                const subMenu = document.createElement('ul');
                subMenu.classList.add('sub-menu');

                item.subMenu.forEach(subItem => {
                    // Validar acceso al submenú
                    const subHasUserRestriction = subItem.usuarios && subItem.usuarios.length > 0;
                    const subUserAllowed = subHasUserRestriction ? subItem.usuarios.includes(usrName) : true;
                    
                    if (subUserAllowed &&
                        (subItem.direccion.includes(usrDir) || subItem.direccion.includes('ALL')) && 
                        (subItem.rol.includes(usrRol) || subItem.rol.includes('ALL'))) {

                        const subLi = document.createElement('li');
                        const subHeader = document.createElement('div');
                        subHeader.classList.add('menu-header');

                        const subAnchor = document.createElement('a');
                        subAnchor.href = subItem.url || '#';
                        subAnchor.textContent = subItem.title;
                        subAnchor.style.color = '#fff';
                        subAnchor.style.textDecoration = 'none';

                        if (subItem.url && subItem.url.endsWith(currentPage)) {
                            subLi.classList.add('active');
                        }

                        subLi.appendChild(subHeader);
                        subHeader.appendChild(subAnchor);

                        if (subItem.subMenu && subItem.subMenu.length > 0) {
                            const subArrow = document.createElement('i');
                            subArrow.className = 'fas fa-chevron-right arrow';
                            subHeader.appendChild(subArrow);
                        
                            const thirdMenu = document.createElement('ul');
                            thirdMenu.classList.add('third-menu');
                            thirdMenu.style.display = 'none'; // Ocultar por defecto
                        
                            subItem.subMenu.forEach(thirdItem => {
                                // Validar acceso al tercer nivel
                                const thirdHasUserRestriction = thirdItem.usuarios && thirdItem.usuarios.length > 0;
                                const thirdUserAllowed = thirdHasUserRestriction ? thirdItem.usuarios.includes(usrName) : true;
                                
                                if (thirdUserAllowed &&
                                    (thirdItem.direccion.includes(usrDir) || thirdItem.direccion.includes('ALL')) && 
                                    (thirdItem.rol.includes(usrRol) || thirdItem.rol.includes('ALL'))) {

                                    const thirdLi = document.createElement('li');
                                    const thirdHeader = document.createElement('div');
                                    thirdHeader.classList.add('menu-header');
                            
                                    const thirdAnchor = document.createElement('a');
                                    thirdAnchor.href = thirdItem.url || '#';
                                    thirdAnchor.textContent = thirdItem.title;
                                    thirdAnchor.style.color = '#fff';
                                    thirdAnchor.style.textDecoration = 'none';
                            
                                    thirdLi.appendChild(thirdHeader);
                                    thirdHeader.appendChild(thirdAnchor);
                                    thirdMenu.appendChild(thirdLi);
                                }
                            });
                        
                            subLi.appendChild(thirdMenu);
                        
                            subHeader.addEventListener('click', (e) => {
                                e.stopPropagation();
                                thirdMenu.style.display = thirdMenu.style.display === 'none' ? 'block' : 'none';
                                subArrow.classList.toggle('rotate');
                            });
                        }

                        subMenu.appendChild(subLi);
                    }
                });

                li.appendChild(subMenu);

                header.addEventListener('click', (e) => {
                    e.stopPropagation();
                    li.classList.toggle('active');
                    arrow.classList.toggle('rotate');
                });
            } else if (item.url) {
                if (item.url.endsWith(currentPage)) {
                    li.classList.add('active');
                }

                header.addEventListener('click', () => {
                    window.location.href = item.url;
                });
            }

            fragment.appendChild(li);
        }
    });

    menu.innerHTML = '';
    menu.appendChild(fragment);
}

// Inicializar el menú con las variables usrDir, usrRol y usrName definidas en tu contexto
generateMenu(usrDir, usrRol, usrName);



