<?php
//Iniciamos la sesión o recuperamos la anterior sesión existente
session_start();

if (!isset($_SESSION['emailUsuario'])) {
    //No podemos acceder al menu si antes no estamos registrados
    header('Location:login.php');
    die();
}

require_once 'src/Nivel.php';
require_once 'src/Movimientos.php';


//Si se ha pulsado salir
if (isset($_POST['salir'])) {
    $ahora = date("Y-m-d H:i:s");
    $fin = date("Y-m-d H:i:s", strtotime('+2 hours', strtotime($ahora)));
    $fichaje = new Movimientos("", "", "", "");
    $fichaje->ficharSalida($_SESSION['id'], $fin);

    //Destruimos la sesión
    session_destroy();
    //Navegamos a la página incial
    header('Location: login.php');
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu principal</title>

    <link rel="icon" href="librerias/assets/logo.ico" sizes="32x32" type="image/x-icon" />

    <style type="text/css">
        h2 {
            text-align: center;
            font-size: 200%;
        }
    </style>

    <!-- css para usar Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" />
</head>


<body>

    <br><br><br>
    <h2>MENU</h2>
    <br>

    <form method="post" name="formularioCuerpo">
        <main>
            <nav> <!--Barra de navegacion-->
                <h1></h1>
                <ul>
                    <li>
                        <label>&#128100;</label>
                        <input type="text" placeholder="<?php echo ($_SESSION['emailUsuario']) ?>" id='usuarioSesion' name='usuarioSesion' readonly>

                        <?php
                        $niveUsuario = new Nivel(null, "", null);
                        $nivel = $niveUsuario->devolverNivelDescripcionPorEmail($_SESSION['emailUsuario']);
                        ?>
                        <input type="text" id="nivelUsuario" name="nivelUsuario" value="<?php echo $nivel ?>" readonly>
                        <button type='submit' class='btn btn-warning mr-3' id='salir' name='salir'>Salir</button>
                    </li>
                </ul>
            </nav>
            <br><br>

            <?php
            if ($nivel == "Administrador") {
                echo "<div class='administrador'>

                    </div>";
            } else if ($nivel == "Supervisor") {
                echo "<div class='supervisor'>

                </div>";
            } else if ($nivel == "Usuario") {
                echo "<div class='usuario'>

                </div>";
            }
            ?>
        </main>
    </form>
    <script src="js/app.js"></script>
</body>

</html>