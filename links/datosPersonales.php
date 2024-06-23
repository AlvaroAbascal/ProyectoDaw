<?php
// Iniciamos la sesión o recuperamos la anterior sesión existente
session_start();

require_once '../src/Usuario.php';
require_once '../src/Nivel.php';

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
    <title>Datos personales</title>

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

        input[type="text"],
        input[type="submit"] {
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
    </style>
</head>

<body>
    <h2>Datos personales</h2>
    <br>
    <form name='datosPersonales' id="datosPersonales" method="post">
        <?php
        $usu = new Usuario("", "", "", "", "", "", "", "", "");
        $id = $usu->devolverIdPorNombre($_SESSION['emailUsuario']);
        $email = $usu->devolverEmailPorNombre($_SESSION['emailUsuario']);
        $nombre = $usu->devolverNombrePorNombre($_SESSION['emailUsuario']);
        $apellidos = $usu->devolverApellidosPorNombre($_SESSION['emailUsuario']);
        $fechaAlta = $usu->devolverAltaPorNombre($_SESSION['emailUsuario']);

        $niveUsuario = new Nivel(null, "", null);
        $nivel = $niveUsuario->devolverNivelDescripcionPorEmail($_SESSION['emailUsuario']);
        ?>

        <label for="id">Id:</label>
        <br>
        <input type="text" id="id" name="id" value="<?php echo $id ?>" readonly><br><br>

        <label for="email">Email:</label>
        <br>
        <input type="text" id="email" name="email" value="<?php echo $email ?>" readonly><br><br>

        <label for="nombre">Nombre:</label>
        <br>
        <input type="text" id="nombre" name="nombre" value="<?php echo $nombre ?>" readonly><br><br>

        <label for="apellidos">Apellidos:</label>
        <br>
        <input type="text" id="apellidos" name="apellidos" value="<?php echo $apellidos ?>" readonly><br><br>

        <label for="fecha_alta">Fecha de Alta en la empresa:</label>
        <br>
        <input type="text" id="fecha_alta" name="fecha_alta" value="<?php echo $fechaAlta ?>" readonly><br><br>

        <label for="nivel">Nivel:</label>
        <br>
        <input type="text" id="nivel" name="nivel" value="<?php echo $nivel ?>" readonly><br><br>

        <input type="submit" value="Volver" id="volver" name='volver'>
    </form>

</body>

</html>