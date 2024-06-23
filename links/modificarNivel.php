<?php
// Iniciamos la sesión o recuperamos la anterior sesión existente
session_start();

require_once '../src/Nivel.php';
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
    <title>Modificar nivel</title>

    <link rel="icon" href="../librerias/assets/logo.ico" sizes="32x32" type="image/x-icon" />

    <!-- css para usar Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css" />

    <style>
        body {
            width: 500px;
            margin: 0 auto;
            font-family: Arial, sans-serif;
        }

        table,
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

        .error {
            color: red;
        }

        h2 {
            text-align: center;
            font-size: 200%;
        }

        input[type="image"] {
            width: 20px;
            height: 20px;
        }
    </style>

</head>

<body>

    <?php
    $nivelUsuario = new Nivel(null, "");
    @$nivel = $nivelUsuario->devolverNivelDescripcionPorEmail($_SESSION['nombreUsuario']);

    $nivelSeleccionable = $nivelUsuario->devolverDescripcionNivel();

    $usu = new Usuario("", "", "", "", "", "", "", "", "");
    $usuarios = $usu->devolverEmailYNivelDescripcion();
    ?>

    <h2>Cambiar nivel</h2>
    <br>

    <div class="cuerpo">
        <?php
        if (!empty($usuarios)) {
            echo "<table border='1'>";
            echo "<tr>
                    <th>Email</th>
                    <th>Nivel Actual</th>
                    <th>Editar</th>
                </tr>";

            //Iterar sobre cada resultado y mostrarlos en la tabla
            foreach ($usuarios as $usuario) {
                echo "<tr>";
                echo "<td>" . $usuario['email'] . "</td>";
                echo "<td>" . $usuario['descripcion'] . "</td>";
                echo "<input type='hidden' name='nivelId[]' value'" . $usuario['nivelId'] . "'>";
                echo "<td> 
                        <form method='post' action='modificarNivelCambio.php'>
                        <input type='hidden' name='emailSeleccionado' value='" . $usuario['email'] . "'/>
                        <input type='image' src='../librerias/assets/lapiz.png' alt='Editar'/></form> 
                     </td>";
                echo "</tr>";
            }

            echo "</table>";
            echo "<br>";
        } else {
            echo "No hay usuarios registrados en la base de datos.";
        }
        ?>
    </div>

    <br>

    <!-- Formulario para volver -->
    <form name='volver' id="volver" method="post">
        <input type="submit" value="Volver" id="volver" name='volver'>
    </form>
</body>

</html>