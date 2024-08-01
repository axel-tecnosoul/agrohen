<?php
include_once('conexion.php');
include_once("./administrar_cargas.php");
$cargas = new cargas();

$conexion = new Conexion();

$query = "SELECT cp.id_carga,cpd.id_destino FROM cargas_productos_destinos cpd INNER JOIN cargas_productos cp ON cpd.id_carga_producto=cp.id WHERE cp.id_carga NOT IN (12,14) GROUP BY cp.id_carga,cpd.id_destino";
$get = $conexion->consultaRetorno($query);
while($row = $get->fetch_array()){
  $id_carga=$row["id_carga"];
  $id_destino=$row["id_destino"];

  $queryInsertCarga = "INSERT INTO cargas_destinos (id_carga, id_destino) VALUES($id_carga, $id_destino)";
  $insertCarga = $conexion->consultaSimple($queryInsertCarga);
  $mensajeError = $conexion->conectar->error;

  $cargas->updateTotalesCargasDestinos($id_carga);
}