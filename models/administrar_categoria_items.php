<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
  // session isn't started
  session_start();
}
require_once('../../conexion.php');
class Categoria{
  private $id_categoria;
  
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

  Public function agregarCategoria($nombre_categoria){

    $sql = "INSERT INTO categorias_item(categoria) VALUES('$nombre_categoria')";
    $insertCategoria = $this->conexion->consultaSimple($sql);

  }

  public function traerCategorias(){

    $sqlTraerCategorias = "SELECT id as id_categoria, categoria FROM categorias_item";
    $traercategorias = $this->conexion->consultaRetorno($sqlTraerCategorias);

    $categorias = array(); //creamos un array

    while ($row = $traercategorias->fetch_array()) {
          $id_categoria = $row['id_categoria'];
          $categoria = $row['categoria'];
          $categorias[] = array('id_categoria'=> $id_categoria, 'categoria'=>$categoria);
      }

      return json_encode($categorias);

  }

  public function traerCategoriaUpdate($id_categoria){
    $this->id_categoria = $id_categoria;

    $sqlTraerCategorias = "SELECT id as id_categoria, categoria
              FROM categorias_item
              WHERE id = $this->id_categoria";
    $traerCategorias = $this->conexion->consultaRetorno($sqlTraerCategorias);

    $rubro = array(); //creamos un array

    while ($row = $traerCategorias->fetch_array()) {
          $id_categoria = $row['id_categoria'];
          $categoria_nombre = $row['categoria'];

          $rubros[] = array('id_categoria'=> $id_categoria, 'categoria'=>$categoria_nombre);
      }

      echo json_encode($rubros);

  }

  public function updateCategoria($id_categoria, $categoria_nombre){

    $this->id_categoria = $id_categoria;

    $sqlUpdateCategoria = "UPDATE categorias_item SET categoria ='$categoria_nombre'
              WHERE id=$this->id_categoria";
    $updateCategoria = $this->conexion->consultaSimple($sqlUpdateCategoria);
  }

  public function deleteCategoria($id_categoria){
    $this->id_categoria = $id_categoria;

    /*ELIMINO CATEGORIA*/
    $sqlDeleteCategoria = "DELETE FROM categorias_item WHERE id = $this->id_categoria";
    $delCategoria = $this->conexion->consultaSimple($sqlDeleteCategoria);
  }
}

if (isset($_POST['accion'])) {
  $categoria = new Categoria();
  switch ($_POST['accion']) {
    case 'traerAlmacenes':
      $categoria->traerTodosClientes();
      break;
    case 'traerCategoriaUpdate':
        $id_categoria = $_POST['id_categoria'];
        $categoria->traerCategoriaUpdate($id_categoria);
      break;
    case 'updateCategoria':
        $id_categoria = $_POST['id_categoria'];
        $categoria_nombre = $_POST['categoria'];
        $categoria->updateCategoria($id_categoria, $categoria_nombre);
      break;
    case 'addCategoria':
        $nombre_categoria = $_POST['categoria'];
        $categoria->agregarCategoria($nombre_categoria);
      break;
    case 'eliminarCategoria':
        $id_categoria = $_POST['id_categoria'];
        $categoria->deleteCategoria($id_categoria);
      break;
    case 'traerDatosIniciales':
      $categoria->traerDatosIniciales();
      break;
  }
}else{
  if (isset($_GET['accion']) and $_GET['accion']=="traerCategorias") {
    $categoria = new Categoria();
    echo $categoria->traerCategorias();
  }
}
?>