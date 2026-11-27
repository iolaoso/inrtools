let menuItems = [];

async function cargarMenu() {

    const response =
        await fetch(
            '/INRtools/backend/menu/getMenu.php'
        );

    menuItems =
        await response.json();

    console.log(
        'MENU CARGADO:',
        menuItems
    );

    generateMenu();
}




async function cargarMenu2() {

    try {

        const response =
            await fetch(
                '/INRtools/backend/menu/getMenu.php'
            );

        const texto =
            await response.text();

        console.log(texto);

    } catch(error) {

        console.error(error);

    }
}

async function cargarMenu1() {

    try {

        const response =
            await fetch(
                '/INRtools/backend/menu/getMenu.php'
            );

        menuItems =
            await response.json();

        generateMenu();

    } catch(error) {

        console.error(
            'Error cargando menú:',
            error
        );

    }
}


cargarMenu();


//console.log(JSON.stringify(menuItems,null,4));