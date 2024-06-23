<?php
session_start();

require_once 'src/Movimientos.php';


$ahora = date("Y-m-d H:i:s");
$fin = date("Y-m-d H:i:s", strtotime('+2 hours', strtotime($ahora)));
$fichaje = new Movimientos("", "", "", "");
$fichaje->ficharSalida($_SESSION['id'], $fin);