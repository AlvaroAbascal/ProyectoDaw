<?php
//Clases porque se llama así en composer.json
/*namespace Clases;

//Se utilizan para importar clases o espacios de sueldoHs
use PDO;
use PDOException;
*/

require_once 'Conexion.php';
//Creamos la clase Categoria que hereda de Conexión
class Categoria extends Conexion
{
    private $id;
    private $sueldoH;

    //Constructor de Categoria
    public function __construct($id, $sueldoH)
    {
        parent::__construct();  //Conexion
        $this->id = $id;
        $this->sueldoH = $sueldoH;
    }

    //Diseñado para consultar la bbdd
    public function devolverCategoria()
    {
        $consulta = "SELECT * FROM categoria";
        $array = array();
        try {
            $stmt = $this->conexion->prepare($consulta);
            //Ejecuta la consulta preparada con los valores proporcionados
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categoria = new Categoria($row['id'], $row['sueldoH']);
                array_push($array, $categoria);
            }
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $array;
    }

    //Diseñado para consultar la bbdd por id
    public function devolverCategoriaId($id)
    {
        $consulta = "SELECT * FROM categoria WHERE id = :id";
        try {
            $stmt = $this->conexion->prepare($consulta);
            //Ejecuta la consulta preparada con los valores proporcionados
            $stmt->execute([":id" => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $categoria = new Categoria($row['id'], $row['sueldoH']);
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $categoria;
    }

    //Diseñado para insertar un registro en la bbdd
    public function insertarCategoria()
    {
        $consulta = "INSERT INTO categoria (id, sueldoH) VALUES (:id, :sueldoH)";
        $stmt = $this->conexion->prepare($consulta);

        $id = $this->getId();
        $sueldoH = $this->getSueldoH();
        try {
            //bindParam. Sirve para asignar los valores a los parámetros de la consulta preparada
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':sueldoH', $sueldoH);

            //Ejecuta la consulta preparada con los valores proporcionados
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $stmt->fetchall(PDO::FETCH_OBJ);
    }

    /*Diseñado para actualizar un registro de la bbdd
    $id. es el identificador único del registro que se desea actualizar.
    $input. es un array asociativo que contiene los nuevos valores que se utilizarán en la actualización*/
    public function modificarCategoria($id, array $input)
    {
        $consulta = "UPDATE categoria SET id = :id, sueldoH = :sueldoH WHERE id = :id";
        try {
            $stmt = $this->conexion->prepare($consulta);
            /*Se ejecuta la consulta preparada con los valores reales proporcionados en el array asociativo*/
            $stmt->execute(array('id' => $id, 'sueldoH' => $input['sueldoH'], 'id' => $id));
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return "Línea modificada";
    }

    //Diseñado para eliminar un registro de la bbdd basado en el id
    public function borrarCategoria($id)
    {
        $consulta = "DELETE FROM categoria WHERE id = :id";
        try {
            $stmt = $this->conexion->prepare($consulta);
            //Ejecuta la consulta preparada con los valores proporcionados
            $stmt->execute(array('id' => $id));
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return "Categoría borrada";
    }


    //Getters
    public function getId()
    {
        return $this->id;
    }

    public function getSueldoH()
    {
        return $this->sueldoH;
    }


    //Setters
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setSueldoH($sueldoH)
    {
        $this->sueldoH = $sueldoH;
        return $this;
    }
}

/*
$miCategoria = new Categoria(2,3);
print_r($miCategoria->devolverCategoria());
print_r('<p><br>');
*/


/*
$categoria=$miCategoria->devolverCategoriaId(2);
echo $categoria->getSueldoH();
*/