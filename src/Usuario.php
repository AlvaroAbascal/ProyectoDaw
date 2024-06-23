<?php
//Clases porque se llama así en composer.json
/*namespace Clases;

//Se utilizan para importar clases o espacios de sueldoHs
use PDO;
use PDOException;
*/

require_once 'Conexion.php';
//Creamos la clase Usuario que hereda de Conexión
class Usuario extends Conexion
{
    private $id;
    private $email;
    private $pass;
    private $nombre;
    private $apellidos;
    private $fechaAlta;
    private $idNivel;
    private $idCategoria;
    private $foto;

    //Constructor de Usuario
    public function __construct($id, $email, $pass, $nombre, $apellidos, $fechaAlta, $idNivel, $idCategoria, $foto)
    {
        parent::__construct();  //Conexion
        $this->id = $id;
        $this->email = $email;
        $this->pass = $pass;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->fechaAlta = $fechaAlta;
        $this->idNivel = $idNivel;
        $this->idCategoria = $idCategoria;
        $this->foto = $foto;
    }

    //Diseñado para consultar la bbdd
    public function devolverUsuario()
    {
        $consulta = "SELECT * FROM usuario WHERE estado = 1";
        $array = array();
        try {
            $stmt = $this->conexion->prepare($consulta);
            //Ejecuta la consulta preparada con los valores proporcionados
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $usuario = new Usuario($row['id'], $row['email'], $row['pass'], $row['nombre'], $row['apellidos'], $row['fechaAlta'], $row['idNivel'], $row['idCategoria'], $row['foto']);
                array_push($array, $usuario);
            }
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $array;
    }

    public function devolverIdPorNombre($nombreUsuario)
    {
        $consulta = "SELECT id FROM usuario WHERE email = :nombreUsuario AND estado = 1";
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute([":nombreUsuario" => $nombreUsuario]);
            $descripcion = $stmt->fetchColumn();
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $descripcion;
    }

    public function devolverEmailPorNombre($nombreUsuario)
    {
        $consulta = "SELECT email FROM usuario WHERE email = :nombreUsuario AND estado = 1";
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute([":nombreUsuario" => $nombreUsuario]);
            $descripcion = $stmt->fetchColumn();
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $descripcion;
    }

    public function devolverNombrePorNombre($nombreUsuario)
    {
        $consulta = "SELECT nombre FROM usuario WHERE email = :nombreUsuario AND estado = 1";
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute([":nombreUsuario" => $nombreUsuario]);
            $descripcion = $stmt->fetchColumn();
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $descripcion;
    }

    public function devolverApellidosPorNombre($nombreUsuario)
    {
        $consulta = "SELECT apellidos FROM usuario WHERE email = :nombreUsuario AND estado = 1";
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute([":nombreUsuario" => $nombreUsuario]);
            $descripcion = $stmt->fetchColumn();
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $descripcion;
    }

    public function devolverAltaPorNombre($nombreUsuario)
    {
        $consulta = "SELECT fechaAlta FROM usuario WHERE email = :nombreUsuario AND estado = 1";
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute([":nombreUsuario" => $nombreUsuario]);
            $descripcion = $stmt->fetchColumn();
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $descripcion;
    }

    //Diseñado para consultar la bbdd por PK
    public function devolverUsuarioPK($email)
    {
        $consulta = "SELECT * FROM usuario WHERE email = :email AND estado = 1";
        try {
            $stmt = $this->conexion->prepare($consulta);
            //Ejecuta la consulta preparada con los valores proporcionados
            $stmt->execute([":email" => $email]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $usuario = new Usuario($row['id'], $row['email'], $row['pass'], $row['nombre'], $row['apellidos'], $row['fechaAlta'], $row['idNivel'], $row['idCategoria'], $row['foto']);
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $usuario;
    }

    //Devolver todos los usuarios con su nivel
    public function devolverEmailYNivelDescripcion()
    {
        $consulta = "SELECT u.email, n.descripcion, n.nivelId
        FROM usuario u
        JOIN nivel n ON u.idNivel = n.nivelId";
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultados;
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    //Devolver idNivel por email
    public function devolverIdNivelPorEmail($email)
    {
        $consulta = "SELECT idNivel FROM usuario WHERE email = :email AND estado = 1";
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute([":email" => $email]);
            $descripcion = $stmt->fetchColumn();
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $descripcion;
    }


    //Diseñado para insertar un registro en la bbdd
    public function insertarUsuario()
    {
        $consulta = "INSERT INTO usuario (email, pass, nombre, apellidos, fechaAlta, idNivel, idCategoria) VALUES (:email, :pass, :nombre, :apellidos, :fechaAlta, :idNivel, :idCategoria)";
        $stmt = $this->conexion->prepare($consulta);

        $email = $this->getEmail();
        $pass = $this->getPass();
        $nombre = $this->getNombre();
        $apellidos = $this->getApellidos();
        $fechaAlta = $this->getFechaAlta();
        $idNivel = $this->getIdNivel();
        $idCategoria = $this->getIdCategoria();
        try {
            //bindParam. Sirve para asignar los valores a los parámetros de la consulta preparada
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':pass', $pass);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':fechaAlta', $fechaAlta);
            $stmt->bindParam(':idNivel', $idNivel);
            $stmt->bindParam(':idCategoria', $idCategoria);

            //Ejecuta la consulta preparada con los valores proporcionados
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $stmt->fetchall(PDO::FETCH_OBJ);
    }

    /*Diseñado para actualizar un registro de la bbdd
    $email. es el identificador único del registro que se desea actualizar.
    $pass. el nuevo valor que se utilizará en la actualización.*/
    public function modificarUsuario($email, $pass)
    {
        $consulta = "UPDATE usuario SET pass = :pass WHERE email = :email";
        try {
            $stmt = $this->conexion->prepare($consulta);
            /*Se ejecuta la consulta preparada con los valores reales proporcionados en el array asociativo*/
            $stmt->execute(array('email' => $email, 'pass' => $pass));
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return "Línea modificada";
    }

    //Diseñado para modificar nivel de usuario
    public function modificarNivelUsuario($email, $nuevoNivel)
    {
        $consulta = "UPDATE usuario SET idNivel = :nuevoNivel WHERE email = :email";
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute(array(':email' => $email, ':nuevoNivel' => $nuevoNivel));
            return true;
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
            return false;
        }
    }

    //Disñado para actualizar foto del usuario
    public function actualizarFoto($foto, $email)
    {
        $consulta = "UPDATE usuario SET foto = :foto WHERE email = :email";
        try {
            $stmt = $this->conexion->prepare($consulta);
            /*Se ejecuta la consulta preparada con los valores reales proporcionados en el array asociativo*/
            $stmt->execute(array('foto' => $foto, 'email' => $email));
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return "Línea modificada";
    }

    //Diseñado para verificar la contraseña
    public function verificarContraseña($email, $contraseña)
    {
        $consulta = "SELECT pass FROM usuario WHERE email = :email";
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute(array('email' => $email));
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            //Comprueba si la contraseña proporcionada coincide con la almacenada en la base de datos
            if ($resultado !== false && $resultado['pass'] == $contraseña) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    //Diseñada para actualizar la contraseña
    public function actualizarContraseña($contraseña, $email)
    {
        $consulta = "UPDATE usuario SET pass = :contraseña WHERE email = :email";
        try {
            $stmt = $this->conexion->prepare($consulta);
            /*Se ejecuta la consulta preparada con los valores reales proporcionados en el array asociativo*/
            $stmt->execute(array('pass' => $contraseña, 'email' => $email));
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return "Contraseña actualizada";
    }

    //Diseñado para eliminar un registro de la bbdd basado en el PK
    public function borrarUsuario($id)
    {
        $consulta = "DELETE FROM usuario WHERE id = :id";
        try {
            $stmt = $this->conexion->prepare($consulta);
            //Ejecuta la consulta preparada con los valores proporcionados
            $stmt->execute(array('id' => $id));
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return "Usuario borrado";
    }

    //Diseñado para modificar el hash la bbdd
    public function modificarHashUsuario($id, $nuevoHash)
    {
        $consulta = "UPDATE usuario SET hash = :nuevoHash WHERE id = :id";
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute(array(':id' => $id, ':nuevoHash' => $nuevoHash));
            return true;
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
            return false;
        }
    }

    //Diseñado para modificar el nivel
    public function modificarNivel($email, $nuevoNivel)
    {
        $consulta = "UPDATE usuario SET idNivel = :nuevoNivel WHERE email = :email";
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->bindParam(':nuevoNivel', $nuevoNivel);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return "Nivel modificado";
    }

    //Diseñado para devolver la foto del usuario
    public function devolverFoto($id)
    {
        $consulta = "SELECT foto FROM usuario WHERE id = :id AND estado = 1";
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute([":id" => $id]);
            $descripcion = $stmt->fetchColumn();
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $descripcion;
    }

    public function loguear($email, $pass, $hash)
    {
        $consulta = "SELECT COUNT(*) AS total FROM usuario WHERE email = :email AND pass = :pass AND hash = :hash AND estado = 1";
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute([":email" => $email, ":pass" => $pass, ":hash" => $hash]);
            $total = $stmt->fetchColumn();
            if ($total == 1) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    //Diseñado para devolver el ID de usuario
    public function devolverUsuarioEmail($email)
    {
        $consulta = "SELECT id FROM usuario WHERE email = :email AND estado = 1";
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute([":email" => $email]);
            $descripcion = $stmt->fetchColumn();
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $descripcion;
    }

    //Diseñado para dar de baja un usuario
    public function anularUsuario($id)
    {
        $consulta = "UPDATE usuario SET estado = 0 WHERE id = :id";
        try {
            $stmt = $this->conexion->prepare($consulta);
            //Ejecuta la consulta preparada con los valores proporcionados
            $stmt->execute(array('id' => $id));
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return "Estado de usuario actualizado a 0";
    }

    //Diseñado para buscar resultados por un ID
    public function buscarPorId($id)
    {
        $consulta = "SELECT * FROM usuario WHERE id = :id AND estado = 1";
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute([":id" => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $usuario = new Usuario($row['id'], $row['email'], $row['pass'], $row['nombre'], $row['apellidos'], $row['fechaAlta'], $row['idNivel'], $row['idCategoria'], $row['foto']);
                return $usuario;
            }
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return null;
    }


    //Getters
    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function getFechaAlta()
    {
        return $this->fechaAlta;
    }

    public function getIdNivel()
    {
        return $this->idNivel;
    }

    public function getIdCategoria()
    {
        return $this->idCategoria;
    }
    public function getFoto()
    {
        return $this->foto;
    }



    //Setters
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setPass($pass)
    {
        $this->pass = $pass;
        return $this;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
        return $this;
    }

    public function setFechaAlta($fechaAlta)
    {
        $this->fechaAlta = $fechaAlta;
        return $this;
    }

    public function setIdNivel($idNivel)
    {
        $this->idNivel = $idNivel;
        return $this;
    }

    public function setIdCategoria($idCategoria)
    {
        $this->idCategoria = $idCategoria;
        return $this;
    }
}

/*
$miUsuario = new Usuario("", "", "", "", "", 2, 2, 2);
print_r($miUsuario->devolverUsuario());
print_r('<p><br>');
*/

/*
$usuario = $miUsuario->devolverUsuarioPK("alvaro@alvaro.com");
echo $usuario->getFechaAlta();
print_r('<p><br>');
*/

/*
$creandoUsuario = $miUsuario = new Usuario("", "", "", "", "2024-03-10", 2, 2, 2);
print_r($creandoUsuario->insertarUsuario("prueba@prueba.com", "prueba", "prueba", "pruebaprueba", "2024-03-10", 2, 2, 2));
print_r('<p><br>');
*/

/*
$creandoUsuario = $miUsuario = new Usuario("", "", "", "", "", 2, 2, 2);
$creandoUsuario->modificarUsuario("alex@alex.com", "alex2");
print_r('<p><br>');
*/