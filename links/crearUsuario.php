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
    <title>Crear usuario</title>

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
        input[type="email"],
        input[type="password"],
        input[type="date"],
        input[type="number"],
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

    <?php
    /*Se se ha pulsado en crear*/
    if (isset($_POST['crearUsuario'])) {
        $crearUsuario = new Usuario("", "", "", "", "", "", "", "", "");

        $crearUsuario->setEmail($_POST['email']);
        $crearUsuario->setNombre($_POST['nombre']);
        $crearUsuario->setApellidos($_POST['apellidos']);
        $crearUsuario->setPass($_POST['contrasena']);
        $crearUsuario->setFechaAlta($_POST['fecha_alta']);
        $crearUsuario->setIdNivel($_POST['id_nivel']);
        $crearUsuario->setIdCategoria($_POST['id_categoria']);

        try {
            $crearUsuario->insertarUsuario();
            $mensaje = "Usuario creado con éxito.";
            cargarFoto();
        } catch (Exception $e) {
            $mensaje = "Error al crear usuario: " . $e->getMessage();
        }
    }
    ?>

    <?php if (!empty($mensaje)) : ?>
        <div class="alert alert-info" role="alert">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <?php
    if (isset($_POST['confirmar'])) {

        //subo los archivos
        $nombreImagen = $_FILES['archivo']['name'];
        $tamanoImagen = $_FILES['archivo']['size'];
        $tiposImagen = $_FILES['archivo']['type'];
        $temporalImagen = $_FILES['archivo']['tmp_name'];

        //echo $array_tamanos[$i]."<br/>";
        if ($tamanoImagen > 1000000) {
            $status = "ERROR, " . $nombreImagen . " demasiado grande";
        } else {
            $prefijo = substr(md5(uniqid(rand())), 0, 6); // ---------- contruir un prefijo para evitar nombre repetidos
            // guardamos el archivo a la carpeta física creada
            $destino = "../librerias/assets/fotoEmpleados/" . $prefijo . "_" . $nombreImagen;
            if (move_uploaded_file($temporalImagen, $destino)) {
                $status = "Archivo subido: <b>" . $nombreImagen . "</b>";
            } else {
                $status = "Error al subir el archivo " . $nombreImagen;
            }
        }

        echo $status . "<br>";
    }
    ?>

    <h2>Crear Nuevo Usuario</h2>
    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <label for="email">Email:</label>
        <br>
        <input type="email" id="email" name="email" required>
        <br><br>

        <label for="nombre">Nombre:</label>
        <br>
        <input type="text" id="nombre" name="nombre" required>
        <br><br>

        <label for="apellidos">Apellidos:</label>
        <br>
        <input type="text" id="apellidos" name="apellidos" required>
        <br><br>

        <label for="contrasena">Contraseña:</label>
        <br>
        <input type="password" id="contrasena" name="contrasena" required>
        <br><br>

        <label for="fecha_alta">Fecha de Alta:</label>
        <br>
        <input type="date" id="fecha_alta" name="fecha_alta" required>
        <br><br>

        <label for="id_nivel">ID de Nivel:</label>
        <br>
        <input type="number" id="id_nivel" name="id_nivel" required>
        <br><br>

        <label for="id_categoria">ID de Categoría:</label>
        <br>
        <input type="number" id="id_categoria" name="id_categoria" required>
        <br><br>
        <input name="archivo" type="file" />

        <input type="submit" value="Crear Usuario" id="crearUsuario" name='crearUsuario'>
    </form>

    <form name='volver' id="volver" method="post">
        <input type="submit" value="Volver" id="volver" name='volver'>
    </form>



</body>

<?php
function cargarFoto()
{
    $nombreImagen = $_FILES['archivo']['name'];
    $tamanoImagen = $_FILES['archivo']['size'];
    $tiposImagen = $_FILES['archivo']['type'];
    $temporalImagen = $_FILES['archivo']['tmp_name'];

    //echo $array_tamanos[$i]."<br/>";
    if ($tamanoImagen > 100000000000000) {
        $status = "ERROR, " . $nombreImagen . " demasiado grande";
    } else {
        $prefijo = substr(md5(uniqid(rand())), 0, 6);   //Contruir un prefijo para evitar nombre repetidos
        //Guardamos el archivo a la carpeta física creada
        $destino = "../librerias/assets/fotoEmpleadosRegistrados/" . $prefijo . "_" . $nombreImagen;
        if (move_uploaded_file($temporalImagen, $destino)) {
            $status = "Archivo subido: <b>" . $nombreImagen . "</b>";
        } else {
            $status = "Error al subir el archivo " . $nombreImagen;
        }
    }

    $modidicarUsuario = new Usuario("", "", "", "", "", "", "", "", "");
    $modidicarUsuario->actualizarFoto($prefijo . "_" . $nombreImagen, $_POST['email']);

    echo $status . "<br>";
}
?>

</html>