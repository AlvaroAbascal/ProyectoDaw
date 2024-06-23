<?php
/**/
require '../vendor/autoload.php';

require_once "../librerias/generarQR.php";

// Generar el código QR utilizando la función crearQr()


// Mostrar el código QR en pantalla
echo  crearQr("Soy Alvaro y funcina" , "Just in Time");