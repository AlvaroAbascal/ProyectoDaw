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
<html>

<head>
    <title>Consultar datos de usuario</title>

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

        h3 {
            text-align: center;
        }

        .datosUsuarioSeleccionado table {
            border: 1px solid black;
            margin: auto;
        }

        .datosUsuarioSeleccionado th,
        .datosUsuarioSeleccionado td {
            border: 1px solid black;
            padding: 5px;
        }
    </style>

</head>

<body>

    <?php
    $usu = new Usuario("", "", "", "", "", "", "", "", "");
    $usuarios = $usu->devolverUsuario(); //Obtener todos los usuarios de la base de datos
    $usuarioSeleccionado = null;

    if (isset($_POST['usuario'])) {
        $usuarioSeleccionado = $usu->buscarPorId($_POST['usuario']); //Buscar usuario por ID
    }
    ?>

    <h2>Consultar datos de usuario</h2>
    <form action="consultarDatosUsuario.php" method="post">
        <label for="email">Correo electrónico:</label><br>
        <select id="usuario" name="usuario">
            <?php foreach ($usuarios as $usuario) : ?>
                <option value="<?php echo $usuario->getId(); ?>" <?php if (isset($_POST['usuario']) && $_POST['usuario'] == $usuario->getId()) echo "selected"; ?>><?php echo $usuario->getEmail(); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Buscar">
    </form>

    <div class="datosUsuarioSeleccionado">
        <?php if ($usuarioSeleccionado != null) : ?>
            <br>
            <h3>Datos del usuario seleccionado:</h3>
            <br>
            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Correo electrónico</th>
                    <th>Fecha de alta</th>
                    <th>Nivel</th>
                </tr>
                <tr>
                    <td><?php echo $usuarioSeleccionado->getNombre(); ?></td>
                    <td><?php echo $usuarioSeleccionado->getApellidos(); ?></td>
                    <td><?php echo $usuarioSeleccionado->getEmail(); ?></td>
                    <td><?php echo $usuarioSeleccionado->getFechaAlta(); ?></td>
                    <td><?php echo $usuarioSeleccionado->getIdNivel(); ?></td>
                </tr>
            </table>
            <br>
        <?php endif; ?>
    </div>

    <form name='volver' id="volver" method="post">
        <input type="submit" value="Volver" id="volver" name='volver'>
    </form>
</body>

</html>