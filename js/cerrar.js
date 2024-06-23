window.onload = function () {

    alert('Funciona');
    window.addEventListener('beforeunload', function (event) {
        //Enviar solicitud AJAX para notificar el cierre de la ventana
        navigator.sendBeacon('cerrar.php');
    });
}
