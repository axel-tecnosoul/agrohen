<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
  // session isn't started
  session_start();
}
require_once('./conexion.php');
class Unidades{
  private $conexion;
  private $id_uMedida;
  
  public function __construct(){
      $this->conexion = new Conexion();
      date_default_timezone_set("America/Buenos_Aires");
  }

  public function traerDatosIniciales(){

    /*PROVINCAIS*/
    $queryProvincia = "SELECT id, provincia FROM provincias";
    $getProvincias = $this->conexion->consultaRetorno($queryProvincia);

    /*TIPOS DE IVA*/
    $queryTipoIva = "SELECT id as idIva, tipo FROM tipos_iva_responsable";
    $getTipoIva = $this->conexion->consultaRetorno($queryTipoIva);

    /*CARGOS*/
    $queryCargos = "SELECT id as idCargo, cargo FROM cargos";
    $getCargos = $this->conexion->consultaRetorno($queryCargos);

    /*TIPO DIRECCIÓN*/
    $queryTipoDireccion = "SELECT id as idTipoDireccion, tipo as tipoDireccion
                FROM tipos_direccion";
    $getTipoDireccion = $this->conexion->consultaRetorno($queryTipoDireccion);


    $datosIniciales = array();
    $pvcias = array();
    $tiposIva = array();
    $arrayCargos = array();
    $arrayTipoDireccion = array();


    /*CARGO ARRAY PROVINCIAS*/
    while ($rowsProv= $getProvincias->fetch_array()) {
      $id_provincia = $rowsProv['id'];
      $provincia = $rowsProv['provincia'];
      $pvcias[] = array('id_provincia' => $id_provincia, 'nombreProv' =>$provincia);
    }

    /*CARGO ARRAY TIPO IVA*/
    while ($rowsIva= $getTipoIva->fetch_array()) {
      $id_iva = $rowsIva['idIva'];
      $tipoIva = $rowsIva['tipo'];
      $tiposIva[] = array('id_iva' => $id_iva, 'tipoIva' =>$tipoIva);
    }

    /*CARGO ARRAY CARGOS*/
    while ($rowCargos = $getCargos->fetch_array()) {
      $id_cargo = $rowCargos['idCargo'];
      $cargo = $rowCargos['cargo'];
      $arrayCargos[]= array('id_cargo' => $id_cargo, 'cargo' =>$cargo);
    }

    /*CARGO ARRAY TIPO DIRECCION*/
    while ($rowTipoDir = $getTipoDireccion->fetch_array()) {
      $idTipoDireccion = $rowTipoDir['idTipoDireccion'];
      $tipoDireccion = $rowTipoDir['tipoDireccion'];
      $arrayTipoDireccion[]= array('idTipoDireccion' => $idTipoDireccion, 'tipoDireccion' =>$tipoDireccion);
    }


    $datosIniciales["provincias"] = $pvcias;
    $datosIniciales["condicion_iva"] = $tiposIva;
    $datosIniciales["cargos"] = $arrayCargos;
    $datosIniciales["tipo_domicilio"] = $arrayTipoDireccion;
    echo json_encode($datosIniciales);
  }


  Public function agregarUnidadMedida($uMedida){

    $sql = "INSERT INTO unidades_medida(unidad_medida) VALUES('$uMedida')";
    $insertUMedida = $this->conexion->consultaSimple($sql);

  }
  
  public function traerUnidades(){

    $sqlTraerUnidades = "SELECT id as id_unidad, unidad_medida FROM unidades_medida";
    $traerUnidades = $this->conexion->consultaRetorno($sqlTraerUnidades);

    $unidades = array(); //creamos un array

    while ($row = $traerUnidades->fetch_array()) {
          $id_unidad = $row['id_unidad'];
          $unidad_medida = $row['unidad_medida'];
          $unidades[] = array('id_unidad'=> $id_unidad, 'unidad_medida'=>$unidad_medida);
      }

      return json_encode($unidades);

  }
  
  public function traerUnidadMedidaUpdate($id_uMedida){
    $this->id_uMedida = $id_uMedida;

    $sqlTraerUnidadMedida = "SELECT id as id_unidad, unidad_medida
              FROM unidades_medida
              WHERE id = $this->id_uMedida";
    $traerUMedidas = $this->conexion->consultaRetorno($sqlTraerUnidadMedida);

    $uMedida = array(); //creamos un array

    while ($row = $traerUMedidas->fetch_array()) {
          $id_unidad = $row['id_unidad'];
          $unidad_medida = $row['unidad_medida'];

          $uMedida[] = array('id_unidad'=> $id_unidad, 'unidad_medida'=>$unidad_medida);
      }

      echo json_encode($uMedida);

  }

  public function updateUnidadMedida($id_uMedida, $uMedida){

    $this->id_uMedida = $id_uMedida;

    $sqlUpdateUMedida = "UPDATE unidades_medida SET unidad_medida ='$uMedida'
              WHERE id=$this->id_uMedida";
    $updateUMedida = $this->conexion->consultaSimple($sqlUpdateUMedida);
  }

  public function deleteUnidadMedida($id_uMedida){
    $this->id_uMedida = $id_uMedida;

    /*ELIMINO CATEGORIA*/
    $sqlDeleteUMedida = "DELETE FROM unidades_medida WHERE id = $this->id_uMedida";
    $delUmedida = $this->conexion->consultaSimple($sqlDeleteUMedida);
  }
		
}	

if (isset($_POST['accion'])) {
  $unidades = new Unidades();
  switch ($_POST['accion']) {
    case 'traerAlmacenes':
      $unidades->traerTodosClientes();
      break;
    case 'traerUMedidaUpdate':
        $id_uMedida = $_POST['id_uMedida'];
        $unidades->traerUnidadMedidaUpdate($id_uMedida);
      break;
    case 'updateUmedida':
        $id_uMedida = $_POST['id_uMedida'];
        $uMedida = $_POST['uMedida'];
        $unidades->updateUnidadMedida($id_uMedida, $uMedida);
      break;
    case 'addUMedida':
        $uMedida = $_POST['uMedida'];
        $unidades->agregarUnidadMedida($uMedida);
      break;
    case 'eliminarUMedida':
        $id_uMedida = $_POST['id_uMedida'];
        $unidades->deleteUnidadMedida($id_uMedida);
      break;
    case 'traerDatosIniciales':
      $unidades->traerDatosIniciales();
      break;
  }
}else{
  if (isset($_GET['accion']) and $_GET['accion']=="traerUnidades") {
    $unidades = new Unidades();
    echo $unidades->traerUnidades();
  }
}
?>