<?php
include_once('conexion.php');
include_once("./administrar_cargas.php");
$cargas = new cargas();

$id_carga=$_GET["id_carga"];

$detalleCargas=$cargas->traerDatosVerDetalleCarga($id_carga);

$detalleCargas=json_decode($detalleCargas,true);

/*$aProductosDestinos=$detalleCargas["productos"];

var_dump($aProductosDestinos);*/

//$cargas->ordenarInfoProductosDestinos($aProductosDestinos);