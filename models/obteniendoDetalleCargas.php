<?php
include_once('conexion.php');
include_once("./administrar_cargas.php");
$cargas = new cargas();

$id_carga=$_GET["id_carga"];

$detalleCargas=$cargas->traerDatosVerDetalleCarga($id_carga);

var_dump(json_decode($detalleCargas,true));