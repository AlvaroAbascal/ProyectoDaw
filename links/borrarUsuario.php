<?php
// Iniciamos la sesión o recuperamos la anterior sesión existente
session_start();

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
    <title>Borrar usuario</title>

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
    </style>

    <script src="../js/app.js"></script>

</head>

<body>

    <?php
    $usu = new Usuario("", "", "", "", "", "", "", "", "");
    $usuarios = $usu->devolverUsuario(); //Obtener todos los usuarios de la base de datos


    //Verificar si se ha enviado el formulario de borrarUsuario
    if (isset($_POST['borrarUsuario'])) {
        //Obtener el ID del usuario seleccionado
        $usuario_id_seleccionado = $_POST['usuario'];

        try {
            $usuarios = $usu->anularUsuario($usuario_id_seleccionado);
            $mensaje = "Usuario borrado con éxito.";
        } catch (Exception $e) {
            $mensaje = "Error al borrar usuario: " . $e->getMessage();
        }
    }
    ?>

    <?php if (!empty($mensaje)) : ?>
        <div class="alert alert-info" role="alert">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <h2>Borrar usuario</h2>
    <form name='borrarUsuario' id="borrarUsuario" method="post">

        <label for="usuario">Usuario:</label>
        <br>
        <select id="usuario" name="usuario">
            <?php foreach ($usuarios as $usuario) : ?>
                <option value="<?php echo $usuario->getId(); ?>" <?php if (isset($_POST['usuario']) && $_POST['usuario'] == $usuario->getId()) echo "selected"; ?>><?php echo $usuario->getNombre(); ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <input type="submit" value="Borrar" id="borrarUsuario" name='borrarUsuario'>
    </form>

    <form name='volver' id="volver" method="post">
        <input type="submit" value="Volver" id="volver" name='volver'>
    </form>

</body>

</html>