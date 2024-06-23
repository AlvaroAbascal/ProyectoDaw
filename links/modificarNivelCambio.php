<?php
session_start();

require_once '../src/Nivel.php';
require_once '../src/Usuario.php';

// Si se ha pulsado volver
if (isset($_POST['volver'])) {
    header('Location: ./modificarNivel.php');
    exit;
}

//Verificamos si se ha pasado el email seleccionado
$emailSeleccionado = isset($_POST['emailSeleccionado']) ? $_POST['emailSeleccionado'] : '';

// Procesar la actualización del nivel
if (isset($_POST['actualizar'])) {
    $nuevoNivel = $_POST['nuevoNivel'];
    $usu = new Usuario("", "", "", "", "", "", "", "", "");
    $resultado = $usu->modificarNivel($_POST['email'], $nuevoNivel);
    echo $resultado;

    header('Location: ./modificarNivel.php');
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar el nivel</title>

    <link rel="icon" href="../librerias/assets/logo.ico" sizes="32x32" type="image/x-icon" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css" />

    <style>
        body {
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

        .error {
            color: red;
        }

        h2 {
            text-align: center;
            font-size: 200%;
        }

        input[type="radio"] {
            margin-right: 10px;
        }
    </style>

</head>

<body>
    <div class="cabecera">
        <h2>Actualización de nivel</h2>
    </div>

    <div class="cuerpo">
        <form method="post" action="./modificarNivelCambio.php">
            <label>Usuario:</label>
            <input type="text" name="email" id="email" value="<?php echo $emailSeleccionado; ?>" readonly>
            <br><br>
            <label>Nuevo nivel:</label>
            <?php
            $nivelUsuario = new Nivel(null, "");
            $nivelesSeleccionables = $nivelUsuario->devolverNivel();
            foreach ($nivelesSeleccionables as $nivel) {
                echo "<div>";
                echo "<input type='radio' name='nuevoNivel' value='" . $nivel->getId() . "'> " . $nivel->getDescripcion();
                echo "</div>";
            }
            ?>
            <br><br>
            <input type="submit" name="actualizar" value="Actualizar">
        </form>

        <form name='volver' id="volver" method="post">
            <input type="submit" value="Volver" id="volver" name='volver'>
        </form>
    </div>
</body>

</html>