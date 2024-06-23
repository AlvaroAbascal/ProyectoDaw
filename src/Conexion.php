<?php


//Creamos la clase conexión para poder conectarnos a la bbdd
class Conexion
{
    private $host;
    private $bd;
    private $user;
    private $pass;
    private $dsn;
    protected $conexion;

    //Constructor
    public function __construct()
    {
        $this->host = "localhost";
        $this->bd = "control";
        $this->user = "gestor";
        $this->pass = "secreto";
        $this->dsn = "mysql:host={$this->host};dbname={$this->bd};charset=utf8mb4";

        $this->crearConexion();
    }

    //Función encargada de establecer una conexión a la bbdd MySQL utilizando la extensión PDO
    public function crearConexion()
    {
        try {
            $this->conexion = new PDO($this->dsn, $this->user, $this->pass);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            die("Error en la conexion: " . $ex->getMessage());
        }

        return $this->conexion;
    }
}
