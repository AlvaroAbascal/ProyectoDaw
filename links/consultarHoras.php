<?php
// Iniciamos la sesión o recuperamos la anterior sesión existente
session_start();

require_once '../src/Movimientos.php';

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
    <title>Informe</title>

    <link rel="icon" href="../librerias/assets/logo.ico" sizes="32x32" type="image/x-icon" />

    <!-- css para usar Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css" />

    <style>
        form {
            width: 500px;
            margin: 0 auto;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        input[type="text"],
        table,
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

        th {
            text-align: center;
        }

        .myChart {
            text-align: center;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

    <h2>Horas trabajadas en:</h2>
    <br>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <input name="fechaInicial" id="fechaInicial" type="date">
        <input name="fechaFinal" id="fechaFinal" type="date">
        <br>
        <br>
        <input type="submit" name="mostrar" value="Mostrar horas">
    </form>

    <?php
    if (isset($_POST['mostrar'])) {
        $movimientos = new Movimientos("", "", "", "");
        // Utiliza la función obtenerHorasDelMesEnCurso()
        $horasDelMesEnCurso = $movimientos->obtenerHorasDelMesEnCurso($_SESSION['id'], $_POST['fechaInicial'], $_POST['fechaFinal']);
        // Muestra las horas obtenidas
        echo "<h3>Horas del mes en curso: $horasDelMesEnCurso</h3>";
    }
    ?>

    <br>

    <div style="display: block; width: 80%; height: 80%; margin: auto;">
        <canvas id="myChart"></canvas>
    </div>

    


    <form name='volver' id="volver" method="post">
        <input type="submit" value="Volver" id="volver" name='volver'>
    </form>

</body>

</html>