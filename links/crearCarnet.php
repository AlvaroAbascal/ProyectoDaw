<?php
// Iniciamos la sesión o recuperamos la anterior sesión existente
session_start();

require_once '../vendor/autoload.php';
require_once "../librerias/generarQR.php";
require_once '../src/Usuario.php';

//Si se ha pulsado volver
if (isset($_POST['volver'])) {
    //Navegamos a la página incial
    header('Location: ../menu.php');
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear carnet</title>

    <link rel="icon" href="../librerias/assets/logo.ico" sizes="32x32" type="image/x-icon" />

    <!-- css para usar Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css" />

    <style>
        form {
            width: 500px;
            margin: 0 auto;
            font-family: Arial, sans-serif;
        }

        select,
        input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        h2 {
            text-align: center;
            font-size: 200%;
        }

        .carnetImpr {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            width: 600px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }

        .carnetImpr .info {
            flex: 1;
        }

        .carnetImpr .qrCode {
            flex: 1;
            text-align: center;
        }

        .qrCode img {
            max-width: 300px;
            max-height: 300px;
        }

        img {
            max-width: 200px;
            max-height: 200px;
        }

        button {
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            margin: 10px auto;
            display: block;
        }
    </style>

    <script src="../js/app.js"></script>


</head>

<body>

    <?php
    $usu = new Usuario("", "", "", "", "", "", "", "", "");
    $usuarios = $usu->devolverUsuario(); //Obtener todos los usuarios de la base de datos

    $nombre_usuario_seleccionado = '';
    $apellidos_usuario_seleccionado = '';
    $fecha_alta_usuario_seleccionado = '';
    //Verificar si se ha enviado el formulario de crearCarnet
    if (isset($_POST['crearCarnet'])) {
        //Obtener el ID del usuario seleccionado
        $usuario_id_seleccionado = $_POST['usuario'];

        //Buscar datos usuario correspondiente al ID seleccionado
        foreach ($usuarios as $usuario) {
            if ($usuario->getId() == $usuario_id_seleccionado) {
                $nombre_usuario_seleccionado = $usuario->getNombre();
                $apellidos_usuario_seleccionado = $usuario->getApellidos();
                $fecha_alta_usuario_seleccionado = $usuario->getFechaAlta();

                $email_usuario_seleccionado = $usuario->getEmail(); //Obtienes email para generar líneas después el QR

                $foto_usuario_seleccionado = $usuario->getFoto(); //Obtienes foto para generar líneas después mostrarla
                break;
            }
        }
    }
    ?>

    <h2>Crear Nuevo Carnet</h2>
    <form name='crearCarnet' id="crearCarnet" method="post">

        <label for="usuario">Usuario:</label>
        <br>
        <select id="usuario" name="usuario">
            <?php foreach ($usuarios as $usuario) : ?>
                <option value="<?php echo $usuario->getId(); ?>" <?php if (isset($_POST['usuario']) && $_POST['usuario'] == $usuario->getId()) echo "selected"; ?>><?php echo $usuario->getNombre(); ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <input type="submit" value="Crear" id="crearCarnet" name='crearCarnet'>
    </form>

    <form name='volver' id="volver" method="post">
        <input type="submit" value="Volver" id="volver" name='volver'>
    </form>

    <br><br>

    <?php
    //
    if (isset($_POST['crearCarnet'])) {
        $fecha_hora_actual = date("Y-m-d_H:i:s");
        $qrGenerado = md5($email_usuario_seleccionado . $fecha_hora_actual);

        $usu->modificarHashUsuario($_POST['usuario'], $qrGenerado);

        echo '<div class="carnetImpr" id="carnetImpr" name="carnetImpr">';
        echo '<div class="info">';
        echo '<p><img src="../librerias/assets/fotoEmpleadosRegistrados/' . $foto_usuario_seleccionado . '" alt="imagenUsuario"></p>';
        echo '<p><strong>Nombre completo:</strong> ' . $nombre_usuario_seleccionado . ' ' . $apellidos_usuario_seleccionado . '</p>';
        echo '<p><strong>Fecha de Alta:</strong> ' . $fecha_alta_usuario_seleccionado . '</p>';
        echo '</div>';
        echo '<div class="qrCode">';

        echo crearQr($qrGenerado, " ");
        echo '</div>';
        echo '</div>';
        $elemento = 'carnetImpr';

        echo '<br><button value="imprimir" id="imprimir" name="Imprimir" onClick=PrintElem(&apos;carnetImpr&apos;)>Imprimir</button>';
    }
    ?>

</body>

</html>