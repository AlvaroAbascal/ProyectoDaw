<?php
//Clases porque se llama así en composer.json
/*namespace Clases;

//Se utilizan para importar clases o espacios de sueldoHs
use PDO;
use PDOException;
*/

require_once 'Conexion.php';
//Creamos la clase Nivel que hereda de Conexión
class Nivel extends Conexion
{
    private $id;
    private $descripcion;

    //Constructor de Nivel
    public function __construct($id, $descripcion)
    {
        parent::__construct();  //Conexion
        $this->id = $id;
        $this->descripcion = $descripcion;
    }

    //Diseñado para consultar la bbdd
    public function devolverNivel()
    {
        $consulta = "SELECT * FROM nivel";
        $array = array();
        try {
            $stmt = $this->conexion->prepare($consulta);
            //Ejecuta la consulta preparada con los valores proporcionados
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $nivel = new Nivel($row['nivelId'], $row['descripcion']);
                array_push($array, $nivel);
            }
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $array;
    }

    public function devolverDescripcionNivel()
    {
        $consulta = "SELECT descripcion FROM nivel";
        $array = array();
        try {
            $stmt = $this->conexion->prepare($consulta);
            //Ejecuta la consulta preparada con los valores proporcionados
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($array, $row['descripcion']);
            }
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $array;
    }
    
    //Diseñado para consultar la bbdd por id
    public function devolverNivelId($id)
    {
        $consulta = "SELECT * FROM nivel WHERE nivelId = :id";
        try {
            $stmt = $this->conexion->prepare($consulta);
            //Ejecuta la consulta preparada con los valores proporcionados
            $stmt->execute([":id" => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $nivel = new Nivel($row['id'], $row['descripcion']);
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $nivel;
    }

    //Diseñado para devolver la descripción del nivel de la bbdd por nombre
    public function devolverNivelDescripcionPorEmail($emailUsuario)
    {
        $consulta = "SELECT n.descripcion
            FROM nivel n
            JOIN usuario u ON n.nivelId = u.idNivel
            WHERE u.email = :emailUsuario";
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute([":emailUsuario" => $emailUsuario]);
            $descripcion = $stmt->fetchColumn();
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $descripcion;
    }

    //Diseñado para insertar un registro en la bbdd
    public function insertarNivel()
    {
        $consulta = "INSERT INTO nivel (nivelId, descripcion) VALUES (:id, :descripcion)";
        $stmt = $this->conexion->prepare($consulta);

        $id = $this->getId();
        $descripcion = $this->getDescripcion();
        try {
            //bindParam. Sirve para asignar los valores a los parámetros de la consulta preparada
            $stmt->bindParam(':nivelId', $id);
            $stmt->bindParam(':descripcion', $descripcion);

            //Ejecuta la consulta preparada con los valores proporcionados
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $stmt->fetchall(PDO::FETCH_OBJ);
    }

    //Diseñado para eliminar un registro de la bbdd basado en el id
    public function borrarNivel($id)
    {
        $consulta = "DELETE FROM nivel WHERE nivelId = :id";
        try {
            $stmt = $this->conexion->prepare($consulta);
            //Ejecuta la consulta preparada con los valores proporcionados
            $stmt->execute(array('nivelId' => $id));
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return "Nivel borrado";
    }


    //Getters
    public function getId()
    {
        return $this->id;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }


    //Setters
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
        return $this;
    }

}

/*
$miNivel = new Nivel(1, "");
print_r($miNivel->devolverNivel());
*/