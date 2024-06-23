<?php
// Iniciamos la sesión o recuperamos la anterior sesión existente
session_start();

//Incluir la clase Usuario
require_once 'src/Usuario.php';
require_once 'src/Movimientos.php';

//Verificar si se han enviado datos de usuario, contraseña y hash
if (isset($_POST['usu']) && isset($_POST['pass'])) {
    $_SESSION['emailUsuario'] = $_POST['usu'];
    $_SESSION['contrasena'] = $_POST['pass'];
    $_SESSION['hash'] = $_POST['hash'];

    $usu = new Usuario("", "", "", "", "", "", "", "", "");
    if ($usu->loguear($_POST['usu'], $_POST['pass'], $_POST['hash'])) {
        $_SESSION['usu'] = $_POST['usu'];



        
        //Datos de fichaje
        $_SESSION['id']= $usu->devolverUsuarioEmail($_POST['usu']);
        echo  $_SESSION['id'];
  
        $ahora = date("Y-m-d H:i:s");
        $inicio = date("Y-m-d H:i:s", strtotime('+2 hours', strtotime($ahora)));

        //insertar entrada
        $fichaje = new Movimientos("", "", "", "");
        $fichaje->fichar($_SESSION['id'], $inicio);
        

        header("Location: menu.php");
    } else {
        echo '<div class="alert alert-danger" role="alert">
            Error al iniciar sesión
        </div>';
    }
}
?>




<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!--Fontawesome CDN-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <script src="librerias/assets/qrCode.min.js"></script>
    <script src="js/acceso.js"></script>
    <title>Login</title>

    <link rel="icon" href="librerias/assets/logo.ico" sizes="32x32" type="image/x-icon" />
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-center h-100">
            <div class="card" style='width:24rem;'>
                <div class="card-header">
                    <h3><i class="fa fa-cog mr-1"></i>Acceso</h3>
                </div>
                <div class="card-body">
                    <form name='logueo' id="logueo" method="post">
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Usuario" id='usu' name='usu' required>
                        </div>

                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" class="form-control" placeholder="Contraseña" id='pass' name='pass' required>
                        </div>
                        <div class="input-group form-group">
                            <input type="hidden" class="form-control" placeholder="text" id='hash' name='hash' required>
                        </div>

                        <div class="row justify-content-center mt-5">
                            <div class="">
                                <h5 class="text-center">Escanear código QR</h5>
                                <div class="text-center">
                                    <a id="btn-scan-qr" href="">
                                        <img src="https://dab1nmslvvntp.cloudfront.net/wp-content/uploads/2017/07/1499401426qr_icon.svg" class="img-fluid center" width="200">
                                    </a>
                                    <canvas hidden="" id="qr-canvas" class="img-fluid"></canvas>
                                </div>
                                <div class="row mx-5 my-3 d-flex justify-content-center">
                                    <button class="btn btn-success btn-sm rounded-3 mb-2" onclick="encenderCamara()">Encender cámara</button>
                                    <button class="btn btn-danger btn-sm rounded-3 mb-2" onclick="cerrarCamara()">Detener cámara</button>
                                </div>
                            </div>
                        </div>
                        <audio id="audioScaner" src="librerias/assets/sonido.mp3"></audio>

                        <!--Boton acceso en caso de que no funcione el QR
                            <div class="form-group">
                            <input type="submit" value="Acceder" class="btn float-right btn-info" id="acceder" name='acceder'>
                        </div>
                        -->

                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="js/index.js"></script>
    <br>
    <hr>
</body>

</html>