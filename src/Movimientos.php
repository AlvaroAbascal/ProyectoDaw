<?php
//Clases porque se llama así en composer.json
/*namespace Clases;

//Se utilizan para importar clases o espacios de sueldoHs
use PDO;
use PDOException;
*/

require_once 'Conexion.php';
//Creamos la clase Movimientos que hereda de Conexión
class Movimientos extends Conexion
{
    private $id;
    private $idUsuario;
    private $inicio;
    private $fin;

    //Constructor de Movimientos
    public function __construct($id, $idUsuario, $inicio, $fin)
    {
        parent::__construct();  //Conexion
        $this->id = $id;
        $this->idUsuario = $idUsuario;
        $this->inicio = $inicio;
        $this->fin = $fin;
    }

    //Diseñado para consultar movimientos por día específico
    public function devolverMovimientoPorDia($dia)
    {
        $consulta = "SELECT * FROM movimientos WHERE DATE(inicio) = :dia";
        $array = array();
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute(array(':dia' => $dia));
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $movimiento = new Movimientos($row['id'], $row['idUsuario'], $row['inicio'], $row['fin']);
                array_push($array, $movimiento);
            }
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $array;
    }

    //Diseñador para consultar movimientos
    public function devolverMovimientosEntreFechas($fechaInicial, $fechaFinal)
    {
        if ($fechaFinal == "") {
            $consulta = "SELECT * FROM movimientos WHERE DATE(inicio) = :fechaInicial";
        } else {
            $consulta = "SELECT * FROM movimientos WHERE DATE(inicio) BETWEEN :fechaInicial AND  :fechaFinal";
        }
        $array = array();
        try {
            $stmt = $this->conexion->prepare($consulta);
            if ($fechaFinal == "") {
                $stmt->execute(array(':fechaInicial' => $fechaInicial));
            } else {
                $stmt->execute(array(':fechaInicial' => $fechaInicial, ':fechaFinal' => $fechaFinal));
            }

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $movimiento = new Movimientos($row['id'], $row['idUsuario'], $row['inicio'], $row['fin']);
                array_push($array, $movimiento);
            }
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return $array;
    }

    //Diseñado para obtener horas en curso
    public function obtenerHorasDelMesEnCurso($idUsuario, $inicio, $fin)
    {
        $consulta = " SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF (fin, inicio)))) AS Total
        FROM movimientos
        WHERE idUsuario = :idUsuario AND inicio BETWEEN :inicio AND :fin";
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute(array(':idUsuario' => $idUsuario, ':inicio' => $inicio, ':fin' => $fin));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['Total'];
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return null;
    }

    public function obtenerHorasDelos6UltimosMeses($idUsuario, $inicio, $fin)
    {

        $consulta = "SELECT month(inicio), SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF (fin, inicio)))) AS Total
        FROM movimientos
        WHERE idUsuario = :idUsuario AND inicio BETWEEN :inicio AND :fin
        GROUP BY month (inicio)";
        $array = array();
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute(array(':idUsuario' => $idUsuario, ':inicio' => $inicio, ':fin' => $fin));
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $array[$row['month(inicio)']] = $row['Total'];
            };
            return $array;
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return null;
    }

    public function obtenerHorasUltimosMeses($idUsuario)
    {
        $fechaActual = new DateTime();
        $fechaActual->modify('first day of this month');
        $fechaInicio = $fechaActual;
        $fechaInicio->modify('- 6 months');
        $datos = self::obtenerHorasDelos6UltimosMeses($idUsuario, $fechaInicio, $fechaActual);

        return $datos;
    }

    //Diseñado para mostrar el timepo trabajado para el informe
    public function calcularTiempoTrabajado($horaEntrada, $horaSalida)
    {
        //Convierte las horas a objetos DateTime
        $entrada = new DateTime($horaEntrada);
        @$salida = new DateTime($horaSalida);

        //Calcula la resultado entre las horas
        $resultado = $entrada->diff($salida);

        //Devuelve el resultado formateado
        return $resultado->format('%H horas, %I minutos y %S segundos');
    }

    //Diseñado para consultar las horas trabajadas de un usuario
    public function devolverHorasTrabajadas($idUsuario, $fechaInicio, $fechaFin)
    {
        $consulta = "SELECT idUsuario, SUM(TIMESTAMPDIFF(SECOND, inicio, fin))
        FROM movimientos
        WHERE idUsuario = :idUsuario AND inicio >= :fechaInicio AND fin <= :fechaFin GROUP BY idUsuario";

        $diasTrabajados = 0;
        $horasTrabajadas = 0;
        $minutosTrabajados = 0;
        $segundosTrabajados = 0;
        try {
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute(array(':idUsuario' => $idUsuario, ':fechaInicio' => $fechaInicio, ':fechaFin' => $fechaFin));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $totalSegundos = $row['SUM(TIMESTAMPDIFF(SECOND, inicio, fin))'];
                $diasTrabajados = floor($totalSegundos / 86400);
                $horasTrabajadas = floor(($totalSegundos % 86400) / 3600);
                $minutosTrabajados = floor(($totalSegundos % 3600) / 60);
                $segundosTrabajados = $totalSegundos % 60;
            }
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
        return array('dias' => $diasTrabajados, 'horas' => $horasTrabajadas, 'minutos' => $minutosTrabajados, 'segundos' => $segundosTrabajados);
    }

    //Diseñado para hacer la inserción de un movimiento (fichar)
    public function fichar($idUsuario, $inicio)
    {
        $consulta = "INSERT INTO movimientos (idUsuario, inicio) VALUES (:idUsuario, :inicio)";
        $stmt = $this->conexion->prepare($consulta);

        try {
            $stmt->bindParam(':idUsuario', $idUsuario);
            $stmt->bindParam(':inicio', $inicio);

            $stmt->execute();
            // Comprueba si la inserción fue exitosa
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    //
    public function ficharSalida($idUsuario, $fin)
    {
        $consulta = "UPDATE movimientos SET fin = :fin WHERE idUsuario = :idUsuario AND fin is null";
        $stmt = $this->conexion->prepare($consulta);

        try {
            $stmt->bindParam(':idUsuario', $idUsuario);
            $stmt->bindParam(':fin', $fin);

            $stmt->execute();
            // Comprueba si la inserción fue exitosa
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    //Getters
    public function getId()
    {
        return $this->id;
    }

    public function getidUsuario()
    {
        return $this->idUsuario;
    }

    public function getInicio()
    {
        return $this->inicio;
    }

    public function getFin()
    {
        return $this->fin;
    }


    //Setters
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setidUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
        return $this;
    }

    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
        return $this;
    }

    public function setFin($fin)
    {
        $this->fin = $fin;
        return $this;
    }
}
