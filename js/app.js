//Creación de vistas------------------------------------------------------------------------------------------------
const administrador = document.querySelector('.administrador');

const imgAdministrador = [
    //{ name: "Fichaje de jornada", image: "images/fichaje.jpg", link: "links/fichaje.php" },
    { name: "Datos personales", image: "images/user.jpg", link: "links/datosPersonales.php" },
    { name: "Cambiar contraseña", image: "images/pass.jpg", link: "links/cambiarContraseña.php" },
    { name: "Ticket restaurant", image: "images/ticket.jpg", link: "links/ticket.php" },
    { name: "Consultar horas", image: "images/consultarHoras.jpg", link: "links/consultarHoras.php" },
    { name: "Informe", image: "images/informe.jpg", link: "links/informe.php" },
    { name: "Consultar datos usuario", image: "images/consultarDatosUsuario.jpg", link: "links/consultarDatosUsuario.php" },
    { name: "Crear usuario", image: "images/crearUsuario.jpg", link: "links/crearUsuario.php" },
    { name: "Borrar usuario", image: "images/borrarUsuario.jpg", link: "links/borrarUsuario.php" },
    { name: "Modificar nivel", image: "images/modificarNivel.jpg", link: "links/modificarNivel.php" },
    { name: "Crear carnet", image: "images/crearCarnet.jpg", link: "links/crearCarnet.php" },
]

const imgAdministradors = () => {
    let output = ""
    imgAdministrador.forEach(({ name, image, link }) => {
        output += `
            <div class="card">
            <a href="${link}">
              <img class="card--avatar" src=${image} />
              <h1 class="card--title">${name}</h1>
              <a class="card--link"></a>
            </div>
        `;
    });
    administrador.innerHTML = output;
}

document.addEventListener("DOMContentLoaded", imgAdministradors)
//------------------------------------------------------------------------------------------------------------------



//------------------------------------------------------------------------------------------------------------------
const supervisor = document.querySelector('.supervisor');

const imgSupervisor = [
    //{ name: "Fichaje de jornada", image: "images/fichaje.jpg", link: "links/fichaje.php" },
    { name: "Datos personales", image: "images/user.jpg", link: "links/datosPersonales.php" },
    { name: "Cambiar contraseña", image: "images/pass.jpg", link: "links/cambiarContraseña.php" },
    { name: "Ticket restaurant", image: "images/ticket.jpg", link: "links/ticket.php" },
    { name: "Consultar horas", image: "images/consultarHoras.jpg", link: "links/consultarHoras.php" },
    { name: "Informe", image: "images/informe.jpg", link: "links/informe.php" },
    { name: "Consultar datos usuario", image: "images/consultarDatosUsuario.jpg", link: "links/consultarDatosUsuario.php" },
]

const imgSupervisors = () => {
    let output = ""
    imgSupervisor.forEach(({ name, image, link }) => {
        output += `
            <div class="card">
            <a href="${link}">
              <img class="card--avatar" src=${image} />
              <h1 class="card--title">${name}</h1>
              <a class="card--link"></a>
            </div>
        `;
    });
    supervisor.innerHTML = output;
}

document.addEventListener("DOMContentLoaded", imgSupervisors)
//------------------------------------------------------------------------------------------------------------------



//------------------------------------------------------------------------------------------------------------------
const usuario = document.querySelector('.usuario');

const imgUsuario = [
    //{ name: "Fichaje de jornada", image: "images/fichaje.jpg", link: "links/fichaje.php" },
    { name: "Datos personales", image: "images/user.jpg", link: "links/datosPersonales.php" },
    { name: "Cambiar contraseña", image: "images/pass.jpg", link: "links/cambiarContraseña.php" },
    { name: "Ticket restaurant", image: "images/ticket.jpg", link: "links/ticket.php" },
    { name: "Consultar horas", image: "images/consultarHoras.jpg", link: "links/consultarHoras.php" },
]

const imgUsuarios = () => {
    let output = ""
    imgUsuario.forEach(({ name, image, link }) => {
        output += `
            <div class="card">
            <a href="${link}">
              <img class="card--avatar" src=${image} />
              <h1 class="card--title">${name}</h1>
              <a class="card--link"></a>
            </div>
        `;
    });
    usuario.innerHTML = output;
}

document.addEventListener("DOMContentLoaded", imgUsuarios)
//------------------------------------------------------------------------------------------------------------------



//Funcion para imprimir div del carnet------------------------------------------------------------------------------
function PrintElem(id) {
    let elem = document.getElementById(id);
    let ventana = window.open('', 'PRINT', 'height=400,width=600');
    ventana.document.write('<html><head><title>' + document.title + '</title>');
    ventana.document.write('<style>img{max-width: 200px;max-height: 200px;} .carnetImpr {border: 1px solid #ccc;border-radius: 10px;padding: 20px;width: 600px;margin: 0 auto;background-color: white;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);display: flex;align-items: center;} .info {flex: 1;}.qrCode {flex: 1;text-align: center;}</style >');
    ventana.document.write('</head><body ><div class="carnetImpr">');
    ventana.document.write(elem.innerHTML);
    ventana.document.write('</div></body></html>');
    ventana.document.close();
    ventana.focus();
    ventana.print();
    return true;
}
//------------------------------------------------------------------------------------------------------------------