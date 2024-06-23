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
    <div class="cabecera">
        <h2>Informe</h2>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <input name="fechaInicial" id="fechaInicial" type="date">
            <input name="fechaFinal" id="fechaFinal" type="date">
            <br>
            <br>
            <input type="submit" name="mostrar" value="Mostrar informe">
        </form>
    </div>
    <br><br>

    <div class="cuerpo">
        <?php
        if (isset($_POST['mostrar'])) {
            $fechaInicial = $_POST['fechaInicial'];
            $fechaFinal = $_POST['fechaFinal'];

            //Llama a la función para obtener los movimientos entre las fechas
            $movimientos = new Movimientos("", "", "", "");
            $movimientos = $movimientos->devolverMovimientosEntreFechas($fechaInicial, $fechaFinal);

            //Si hay movimientos, mostrarlos en una tabla
            if (!empty($movimientos)) {
                echo "<h3>Movimientos entre las fechas $fechaInicial y $fechaFinal:</h3><br>";
                echo "<table border='1'>";
                echo "<tr><th>Nº de movimiento</th><th>ID Usuario</th><th>Inicio</th><th>Fin</th><th>Tiempo Trabajado</th></tr>";

                //Itera sobre cada movimiento y muestra en la tabla
                foreach ($movimientos as $movimiento) {
                    echo "<tr>";
                    echo "<td>" . $movimiento->getId() . "</td>";
                    echo "<td>" . $movimiento->getidUsuario() . "</td>";
                    echo "<td>" . $movimiento->getInicio() . "</td>";
                    echo "<td>" . $movimiento->getFin() . "</td>";
                    //Llama a la función calcularTiempoTrabajado() para obtener el tiempo trabajado
                    echo "<td>" . $movimiento->calcularTiempoTrabajado($movimiento->getInicio(), $movimiento->getFin()) . "</td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                //Si no hay movimientos, mostrar un mensaje
                echo "No hay movimientos entre las fechas seleccionadas.";
            }
        }
        ?>

        <br>

        <div>
            <?php
            $movimientosObj = new Movimientos("", "", "", "");
            @$movimientos = $movimientosObj->devolverMovimientosEntreFechas($fechaInicial, $fechaFinal);

            $totalDiasTrabajadosPorUsuario = [];
            $totalHorasTrabajadasPorUsuario = [];
            $totalMinutosTrabajadosPorUsuario = [];
            $totalSegundosTrabajadosPorUsuario = [];

            foreach ($movimientos as $movimiento) {
                $idUsuario = $movimiento->getidUsuario();
                $fechaInicio = $movimiento->getInicio();
                $fechaFin = $movimiento->getFin();

                //Llama a la función devolverHorasTrabajadas()
                $tiempoTrabajado = $movimientosObj->devolverHorasTrabajadas($idUsuario, $fechaInicio, $fechaFin);

                //Suma las horas, minutos y segundos trabajados al total para este usuario
                if (!isset($totalDiasTrabajadosPorUsuario[$idUsuario])) {
                    $totalDiasTrabajadosPorUsuario[$idUsuario] = 0;
                    $totalHorasTrabajadasPorUsuario[$idUsuario] = 0;
                    $totalMinutosTrabajadosPorUsuario[$idUsuario] = 0;
                    $totalSegundosTrabajadosPorUsuario[$idUsuario] = 0;
                }
                $totalDiasTrabajadosPorUsuario[$idUsuario] += $tiempoTrabajado['dias'];
                $totalHorasTrabajadasPorUsuario[$idUsuario] += $tiempoTrabajado['horas'];
                $totalMinutosTrabajadosPorUsuario[$idUsuario] += $tiempoTrabajado['minutos'];
                $totalSegundosTrabajadosPorUsuario[$idUsuario] += $tiempoTrabajado['segundos'];

                //Si los segundos superan los 60, convertirlos en minutos
                if ($totalSegundosTrabajadosPorUsuario[$idUsuario] >= 60) {
                    $totalMinutosTrabajadosPorUsuario[$idUsuario] += floor($totalSegundosTrabajadosPorUsuario[$idUsuario] / 60);
                    $totalSegundosTrabajadosPorUsuario[$idUsuario] %= 60;
                }

                //Si los minutos superan los 60, convertirlos en horas
                if ($totalMinutosTrabajadosPorUsuario[$idUsuario] >= 60) {
                    $totalHorasTrabajadasPorUsuario[$idUsuario] += floor($totalMinutosTrabajadosPorUsuario[$idUsuario] / 60);
                    $totalMinutosTrabajadosPorUsuario[$idUsuario] %= 60;
                }

                //Si las horas superan las 24, convertirlos en días
                if ($totalHorasTrabajadasPorUsuario[$idUsuario] >= 24) {
                    $totalDiasTrabajadosPorUsuario[$idUsuario] += floor($totalHorasTrabajadasPorUsuario[$idUsuario] / 24);
                    $totalHorasTrabajadasPorUsuario[$idUsuario] %= 24;
                }
            }

            //Muestra los días, horas, minutos y segundos trabajados totales por usuario
            foreach ($totalDiasTrabajadosPorUsuario as $idUsuario => $totalDias) {
                $totalHoras = $totalHorasTrabajadasPorUsuario[$idUsuario];
                $totalMinutos = $totalMinutosTrabajadosPorUsuario[$idUsuario];
                $totalSegundos = $totalSegundosTrabajadosPorUsuario[$idUsuario];
                echo "El total de tiempo trabajado para el usuario $idUsuario es: $totalDias días, $totalHoras horas, $totalMinutos minutos y $totalSegundos segundos<br>";
            }
            ?>
            <br>
        </div>

        <br>

        <div style="display: block; width: 80%; height: 80%; margin: auto;">
            <canvas id="myChart"></canvas>
        </div>

        <script>
            //Crear arrays para los labels y los datos
            var labels = [];
            var data = [];

            <?php
            foreach ($totalDiasTrabajadosPorUsuario as $idUsuario => $totalDias) {
                $totalHoras = $totalHorasTrabajadasPorUsuario[$idUsuario];
                $totalMinutos = $totalMinutosTrabajadosPorUsuario[$idUsuario];
                $totalSegundos = $totalSegundosTrabajadosPorUsuario[$idUsuario];

                //Convertir el tiempo total trabajado a un solo valor. Aquí convertimos todo a segundos.
                $totalTiempoTrabajado = ($totalDias * 86400 + $totalHoras * 3600 + $totalMinutos * 60 + $totalSegundos) / 3600;
            ?>

                //Agregar el id del usuario y el tiempo total trabajado al array de labels y datos
                labels.push("<?php echo $idUsuario; ?>");
                data.push(<?php echo $totalTiempoTrabajado; ?>);

            <?php
            }
            ?>

            //Crear el gráfico de barras
            var colors = [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                // ... añade más colores aquí
            ];

            var borderColors = [
                'rgba(255, 99, 132, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255, 205, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(54, 162, 235, 1)',
                // ... añade más colores aquí
            ];
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Tiempo trabajado en horas',
                        data: data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 205, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(201, 203, 207, 0.2)',
                            'rgba(255, 0, 0, 0.2)',
                            'rgba(0, 255, 0, 0.2)',
                            'rgba(0, 0, 255, 0.2)',
                            'rgba(255, 255, 0, 0.2)',
                            'rgba(0, 255, 255, 0.2)',
                            'rgba(255, 0, 255, 0.2)',
                            'rgba(192, 192, 192, 0.2)',
                            'rgba(128, 0, 0, 0.2)',
                            'rgba(128, 128, 0, 0.2)',
                            'rgba(0, 128, 128, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 205, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(201, 203, 207, 1)',
                            'rgba(255, 0, 0, 1)',
                            'rgba(0, 255, 0, 1)',
                            'rgba(0, 0, 255, 1)',
                            'rgba(255, 255, 0, 1)',
                            'rgba(0, 255, 255, 1)',
                            'rgba(255, 0, 255, 1)',
                            'rgba(192, 192, 192, 1)',
                            'rgba(128, 0, 0, 1)',
                            'rgba(128, 128, 0, 1)',
                            'rgba(0, 128, 128, 1)'
                        ],
                        borderWidth: 5
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                }
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: 18,
                                    weight: 'bold'
                                }
                            }
                        },
                        tooltip: {
                            titleFont: {
                                size: 16,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 14,
                                weight: 'bold'
                            }
                        }
                    }
                }
            });
        </script>

        <script>
            //Crear arrays para los labels y los datos
            var labels = [];
            var data = [];

            <?php
            foreach ($totalDiasTrabajadosPorUsuario as $idUsuario => $totalDias) {
                $totalHoras = $totalHorasTrabajadasPorUsuario[$idUsuario];
                $totalMinutos = $totalMinutosTrabajadosPorUsuario[$idUsuario];
                $totalSegundos = $totalSegundosTrabajadosPorUsuario[$idUsuario];

                //Convertir el tiempo total trabajado a un solo valor. Aquí convertimos todo a segundos.
                $totalTiempoTrabajado = $totalDias * 86400 + $totalHoras * 3600 + $totalMinutos * 60 + $totalSegundos;
            ?>

                //Agregar el id del usuario y el tiempo total trabajado al array de labels y datos
                labels.push("<?php echo $idUsuario; ?>");
                data.push(<?php echo $totalTiempoTrabajado; ?>);

            <?php
            }
            ?>

            //Crear el gráfico de barras
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Tiempo trabajado en segundos',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>

    </div>

    <br>
    <form name='volver' id="volver" method="post">
        <input type="submit" value="Volver" id="volver" name='volver'>
    </form>

</body>

</html>