<?php
// Iniciamos la sesión o recuperamos la anterior sesión existente
session_start();
$_SESSION['email'] = 'email_del_usuario';

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
    <title>Cambiar contraseña</title>

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

        input[type="password"],
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

        .error {
            color: red;
        }

        h2 {
            text-align: center;
            font-size: 200%;
        }
    </style>

</head>

<body>

    <?php
    /*Se se ha pulsado en guardarNuevaContraseña*/
    $cambiarContraseña = new Usuario("", "", "", "", "", "", "", "", "");
    if (isset($_POST['guardarNuevaContraseña'])) {
        //Verificar que la nueva contraseña y la confirmación de la contraseña coinciden
        if ($_POST['nuevaContraseña'] != $_POST['confirmarContraseña']) {
            $error = "La nueva contraseña y la confirmación de la contraseña no coinciden.";
        } else {
            //Verificar la contraseña actual
            $contraseñaActualCorrecta = $cambiarContraseña->verificarContraseña($_SESSION['email'], $_POST['contraseñaActual']);

            if ($contraseñaActualCorrecta) {
                //Si la contraseña actual es correcta, actualizar la contraseña
                $cambiarContraseña->setPass($_POST['nuevaContraseña']);
                $cambiarContraseña->actualizarContraseña($_POST['nuevaContraseña'], $_SESSION['email']);

                // Redirección después de la actualización
                header("Location:../menu.php");
            } else {
                //Si la contraseña actual no es correcta, mostrar un mensaje de error
                $error = "La contraseña actual es incorrecta.";
            }
        }
    }
    ?>

    <span class="error" id="contraseñaErronea"><?php if (isset($error)) echo $error; ?></span>

    <h2>Cambiar contraseña</h2>
    <br>
    <form name='cambiarContraseña' id="cambiarContraseña" method="post">
        <label for="contraseñaActual">Contraseña Actual:</label>
        <input type="password" id="contraseñaActual" name="contraseñaActual" required>
        <br>

        <label for="nuevaContraseña">Nueva Contraseña:</label>
        <input type="password" id="nuevaContraseña" name="nuevaContraseña" required>
        <br>

        <label for="confirmarContraseña">Confirmar Nueva Contraseña:</label>
        <input type="password" id="confirmarContraseña" name="confirmarContraseña" required>
        <br>

        <span class="error" id="contraseñaErronea"></span>
        <br>
        <input type="submit" value="Guardar Nueva Contraseña" id="guardarNuevaContraseña" name='guardarNuevaContraseña'>
    </form>

    <form name='volver' id="volver" method="post">
        <input type="submit" value="Volver" id="volver" name='volver'>
    </form>

</body>

</html>