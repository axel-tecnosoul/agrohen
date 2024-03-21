 <?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
  // session isn't started
  session_start();
}
require_once('./conexion.php');
class Proveedores{
  private $id_proveedor;
  private $id_contacto;
  private $id_domicilio;
  private $id_empresa;

  public function __construct(){
      $this->conexion = new Conexion();
      date_default_timezone_set("America/Buenos_Aires");
  }

  public function traerDatosIniciales($id_empresa){

    $this->id_empresa = $id_empresa;

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

    /*RUBROS*/
    $queryRubros = "SELECT id as id_rubro, rubro FROM rubros";
    $getRubros = $this->conexion->consultaRetorno($queryRubros);


    $datosIniciales = array();
    $pvcias = array();
    $tiposIva = array();
    $arrayCargos = array();
    $arrayTipoDireccion = array();
    $arrayRubros = array();

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

    /*CARGAR ARRAY RUBROS*/
    while ($rowRubros = $getRubros->fetch_assoc()) {
      $id_rubro = $rowRubros['id_rubro'];
      $rubro = $rowRubros['rubro'];
      $arrayRubros[]= array('id_rubro' => $id_rubro, 'rubro' =>$rubro);
    }


    $datosIniciales["provincias"] = $pvcias;
    $datosIniciales["condicion_iva"] = $tiposIva;
    $datosIniciales["cargos"] = $arrayCargos;
    $datosIniciales["tipo_domicilio"] = $arrayTipoDireccion;
    $datosIniciales["rubros"] = $arrayRubros;

    echo json_encode($datosIniciales);
  }

  public function agregarProveedor($id_empresa, $razon_social, $cuit, $rubro, $estado, $id_tipo_iva, $saldo_prov){
    
    $this->id_empresa = $id_empresa;

    $fecha_alta = date('Y-m-d');

    if ($estado == 'Activo') {
      $estado = 1;
    }else{
      $estado = 0;
    }

    $sqlInsertProv = "INSERT INTO proveedores(razon_social, cuit, fecha_alta, activo, id_tipo_iva_responsable, saldo, rubro, id_empresa) VALUES('$razon_social', '$cuit', '$fecha_alta', $estado, $id_tipo_iva, 0, $rubro, $this->id_empresa)";
    $insertProveedor = $this->conexion->consultaSimple($sqlInsertProv);



  }

  public function traerProveedores($id_empresa){

    $this->id_empresa = $id_empresa;

    $sqlTraerProveedores = "SELECT prv.id as id_proveedor, razon_social, cuit, 
              case 
                when activo = 1 then 'Activo' 
                else 'Inactivo' 
              end activo, saldo, 
              fecha_alta, iva.tipo as tipo_iva, rub.rubro
              FROM proveedores prv join tipos_iva_responsable as iva
              ON(prv.id_tipo_iva_responsable = iva.id)
              join rubros as rub
              ON(prv.rubro = rub.id)
              WHERE prv.id_empresa = $this->id_empresa";
    $traerProveedores = $this->conexion->consultaRetorno($sqlTraerProveedores);

    $proveedores = array(); //creamos un array

    while ($row = $traerProveedores->fetch_array()) {
          $id_proveedor = $row['id_proveedor'];
          $razon_social = $row['razon_social'];
          $cuit = $row['cuit'];
          $activo = $row['activo'];
          $saldo = number_format($row['saldo'],0,',','.');
          $fechaAlataDate = new DateTime($row['fecha_alta']);
          $fecha_alta = date_format($fechaAlataDate, "d/m/Y");
          $iva = $row['tipo_iva'];
          $rubro = $row['rubro'];
          $proveedores[] = array('id_proveedor'=> $id_proveedor, 'razon_social'=> $razon_social, 'cuit'=> $cuit, 'activo'=> $activo, 'saldo'=>"$".$saldo,'fecha_alta'=> $fecha_alta, 'iva'=>$iva, 'rubro'=>$rubro);
      }

      return json_encode($proveedores);

  }

  public function traerProveedorUpdate($id_proveedor){
    $this->id_proveedor = $id_proveedor;
    $sqlTraerProveedor = "SELECT id as id_proveedor, razon_social, cuit, case when activo = 1 then 'Activo' else 'Inactivo' end activo, id_tipo_iva_responsable, saldo, rubro 
              FROM proveedores
              WHERE id = $this->id_proveedor";
    $traerProveedor = $this->conexion->consultaRetorno($sqlTraerProveedor);

    $proveedor = array(); //creamos un array

    while ($row = $traerProveedor->fetch_array()) {
          $id_prov = $row['id_proveedor'];
          $razon_social = $row['razon_social'];
          $cuit = $row['cuit'];
          $activo = $row['activo'];
          $condicion_IVA = $row['id_tipo_iva_responsable'];
          $saldo_prov = $row['saldo'];
          $rubro_prov = $row['rubro'];
          $proveedor[] = array('id_proveedor'=> $id_prov, 'razon_social'=>$razon_social, 'cuit'=>$cuit, 'activo'=> $activo, 'iva'=>$condicion_IVA, 'saldo'=>$saldo_prov, 'rubro_prov'=>$rubro_prov);
      }

      echo json_encode($proveedor);
  }

  public function proveedorUpdate($id_proveedor, $razon_social, $estado, $cuit, $condicion_iva, $rubro){

    if ($estado == 'Activo') {
      $estado = 1;
    }else{
      $estado = 0;
    }

    $sqlUpdateProveedor = "UPDATE proveedores SET razon_social ='$razon_social', cuit= '$cuit', activo = $estado, id_tipo_iva_responsable= $condicion_iva, rubro= $rubro
              WHERE id=$id_proveedor";
    $updateProveedor = $this->conexion->consultaSimple($sqlUpdateProveedor);
  }

  public function deleteProveedor($id_proveedor){
    $this->id_proveedor = $id_proveedor;

    /*ELIMINO CONTACTOS DE PROVEEDORES*/
    $sqlDeleteContactos = "DELETE FROM contactos_proveedores WHERE id_proveedor = $this->id_proveedor";
    $deleteContacto = $this->conexion->consultaSimple($sqlDeleteContactos);

    /*ELIMINO DIRECCIONES DE PROVEEDORES*/
    $sqlDeleteDirecciones = "DELETE FROM direcciones_proveedores WHERE id_proveedor = $this->id_proveedor";
    $deleteDirecciones = $this->conexion->consultaSimple($sqlDeleteDirecciones);

    /*ELIMINO PROVEEDOR*/
    $sqlDeleteProveedor = "DELETE FROM proveedores WHERE id = $this->id_proveedor";
    $delProveedor = $this->conexion->consultaSimple($sqlDeleteProveedor);
  }

  public function traerContactos($id_proveedor){
    $this->id_proveedor = $id_proveedor;

    $query = "SELECT ccl.id as id_contacto, nombre_completo, email, telefono, 
        cg.cargo cargo, cg.id as id_cargo, case
                          when activo = 1 then 'Activo'
                                else 'Inactivo'
                          end activo
        FROM contactos_proveedores ccl join cargos cg
        ON(ccl.id_cargo = cg.id) 
        WHERE id_proveedor = $this->id_proveedor";
    $getContactos = $this->conexion->consultaRetorno($query);

    $contactos = array(); //creamos un array
    
    if($getContactos){
      while ($row = $getContactos->fetch_array()) {
            $id_contacto = $row['id_contacto'];
            $nombre_completo = $row['nombre_completo'];
            $email = $row['email'];
            $telefono = $row['telefono'];
            $cargo = $row['cargo'];
            $id_cargo = $row['id_cargo'];
            $activo = $row['activo'];
            $contactos[] = array('id_contacto'=> $id_contacto, 'nombre_completo'=>$nombre_completo, 'email'=>$email, 'telefono'=>$telefono, 'cargo'=>$cargo, 'id_cargo' =>$id_cargo, 'activo'=> $activo);
          }
    }

    


      echo json_encode($contactos);

  }

  public function agregarContacto($id_proveedor, $nombreContacto, $emailContacto, $telContacto, $estadoContacto, $cargoContacto){

    if ($estadoContacto == 'Activo') {
      $estado = 1;
    }else{
      $estado = 0;
    }

    $query = "INSERT INTO contactos_proveedores(nombre_completo, email, telefono, id_proveedor, id_cargo, activo) VALUES('$nombreContacto', '$emailContacto', '$telContacto', $id_proveedor, $cargoContacto, $estado)";
    $addContacto = $this->conexion->consultaSimple($query);
  }

  public function editarContacto($id_contacto, $nombreContacto, $emailContacto, $telContacto, $estadoContacto, $cargoContacto){
    if ($estadoContacto == 'Activo') {
      $estado = 1;
    }else{
      $estado = 0;
    }
    $query="UPDATE contactos_proveedores SET nombre_completo = '$nombreContacto', email = '$emailContacto', telefono='$telContacto', id_cargo = $cargoContacto, activo = $estado
      WHERE id = $id_contacto";
    $updateContacto = $this->conexion->consultaSimple($query);
  }
  
  public function deleteContacto($id_contacto){
    $this->id_contacto = $id_contacto;

    /*ELIMINO CONTACTO*/

    $sql = "DELETE FROM contactos_proveedores WHERE id = $this->id_contacto";
    $deleteContacto = $this->conexion->consultaSimple($sql);
  }

  public function traerDomicilios($id_proveedor){
    $this->id_proveedor = $id_proveedor;

    $query = "SELECT dl.id as id_direccion, direccion, id_provincia, provincia,
        td.tipo tipo_direccion, td.id as id_tipo_dir, case
                          when activa = 1 then 'Activo'
                                else 'Inactivo'
                          end activa
        FROM direcciones_proveedores dl join tipos_direccion td
        ON(dl.id_tipo_direccion = td.id) 
                  join provincias pvcias
                  ON(dl.id_provincia = pvcias.id)
        WHERE id_proveedor = $this->id_proveedor
        ORDER BY dl.id";
    $getDirecciones = $this->conexion->consultaRetorno($query);

    $direcciones = array(); //creamos un array
    
    if($getDirecciones){
      while ($row = $getDirecciones->fetch_array()) {
            $id_direccion = $row['id_direccion'];
            $direccion = $row['direccion'];
            $id_provincia = $row['id_provincia'];
            $provincia = $row['provincia'];
            $tipo_direccion = $row['tipo_direccion'];
            $id_tipo_dir = $row['id_tipo_dir'];
            $activo = $row['activa'];
            $direcciones[] = array('id_direccion'=> $id_direccion, 'direccion'=>$direccion, 'id_provincia'=>$id_provincia, 'provincia'=>$provincia, 'tipo_direccion'=>$tipo_direccion, 'id_tipo_dir'=>$id_tipo_dir, 'activo' =>$activo);
          }
    }

    


      echo json_encode($direcciones);

  }

  public function addDomicilios($id_proveedor, $direccion, $tipoDireccion, $provincia, $estadoDomicilio){
    if ($estadoDomicilio == 'Activo') {
      $estado = 1;
    }else{
      $estado = 0;
    }

    $query = "INSERT INTO direcciones_proveedores(id_proveedor, direccion, id_provincia, id_tipo_direccion, activa) VALUES($id_proveedor, '$direccion',$provincia, $tipoDireccion, $estado)";
    $addDomicilio = $this->conexion->consultaSimple($query);
  }

  public function updateDomicilio($id_domicilio, $direccion, $tipoDireccion, $provincia, $estadoDomicilio){
    $this->id_domicilio = $id_domicilio;
    if ($estadoDomicilio == 'Activo') {
      $estado = 1;
    }else{
      $estado = 0;
    }

    $query = "UPDATE direcciones_proveedores SET direccion='$direccion', 				id_provincia = $provincia, id_tipo_direccion = $tipoDireccion, 			activa = $estado
        WHERE id=$this->id_domicilio";
    $updateDomicilio = $this->conexion->consultaSimple($query);
  }

  public function deleteDomicilio($id_domicilio){
    $this->id_domicilio = $id_domicilio;

    /*ELIMINO CONTACTO*/

    $sql = "DELETE FROM direcciones_proveedores WHERE id = $this->id_domicilio";
    $deleteDomicilio = $this->conexion->consultaSimple($sql);
  }
}

if (isset($_POST['accion'])) {
  $proveedores = new Proveedores();
  switch ($_POST['accion']) {
    case 'traerProveedores':
      $id_empresa = $_POST['id_empresa'];
      $proveedores->traerTodosClientes($id_empresa);
      break;
    case 'traerProveedorUpdate':
        $id_proveedor = $_POST['id_proveedor'];
        $proveedores->traerProveedorUpdate($id_proveedor);
      break;
    case 'traerContactos':
        $id_proveedor = $_POST['id_proveedor'];
        $proveedores->traerContactos($id_proveedor);
      break;
    case 'addContacto':
        $id_proveedor = $_POST['id_proveedor'];
        $nombreContacto= $_POST['nombreContacto'];
        $emailContacto= $_POST['emailContacto'];
        $telContacto= $_POST['telContacto'];
        $estadoContacto= $_POST['estadoContacto'];
        $cargoContacto= $_POST['cargoContacto'];
        $proveedores->agregarContacto($id_proveedor, $nombreContacto, $emailContacto, $telContacto, $estadoContacto, $cargoContacto);
      break;
    case 'editarContacto':
        $id_contacto = $_POST['id_contacto'];
        $nombreContacto= $_POST['nombreContacto'];
        $emailContacto= $_POST['emailContacto'];
        $telContacto= $_POST['telContacto'];
        $estadoContacto= $_POST['estadoContacto'];
        $cargoContacto= $_POST['cargoContacto'];
        $proveedores->editarContacto($id_contacto, $nombreContacto, $emailContacto, $telContacto, $estadoContacto, $cargoContacto);
      break;
    case 'updateProveedor':
        $id_proveedor = $_POST['id_proveedor'];
        $razon_social = $_POST['razon_social'];
        $estado = $_POST['estado'];
        $cuit = $_POST['cuit'];
        $condicion_iva = $_POST['condicion_iva'];
        $rubro = $_POST['rubro'];
        //$saldo_prov = $_POST['saldo'];
        $proveedores->proveedorUpdate($id_proveedor, $razon_social, $estado, $cuit, $condicion_iva, $rubro);
      break;
    case 'nuevoProveedor':
        $id_empresa= $_POST['id_empresa'];
        $razon_social = $_POST['razon_social'];
        $estado = $_POST['estado'];
        $cuit = $_POST['cuit'];
        $rubro = $_POST['rubro'];
        $id_tipo_iva = $_POST['condicion_iva'];
        $saldo_prov = $_POST['saldo'];
        $proveedores->agregarProveedor($id_empresa, $razon_social, $cuit, $rubro, $estado, $id_tipo_iva, $saldo_prov);
      break;
    case 'eliminarProveedor':
        $id_proveedor = $_POST['proveedor_id'];
        $proveedores->deleteProveedor($id_proveedor);
      break;
    case 'eliminarContacto':
        $id_contacto = $_POST['id_contacto'];
        $proveedores->deleteContacto($id_contacto);
      break;
    case 'traerDirecciones':
        $id_proveedor = $_POST['id_proveedor'];
        $proveedores->traerDomicilios($id_proveedor);
      break;
    case 'addDomicilio':
        $id_proveedor =$_POST['id_proveedor'];
        $direccion =$_POST['direccion'];
        $tipoDireccion =$_POST['tipoDireccion'];
        $provincia =$_POST['provincia'];
        $estadoDomicilio =$_POST['estadoDomicilio'];
        $proveedores->addDomicilios($id_proveedor, $direccion, $tipoDireccion, $provincia, $estadoDomicilio);
      break;
    case 'editarDomicilio':
        $id_domicilio = $_POST['id_domicilio'];
        $direccion = $_POST['direccion'];
        $tipoDireccion = $_POST['tipoDireccion'];
        $provincia = $_POST['provincia'];
        $estadoDomicilio = $_POST['estadoDomicilio'];
        $proveedores->updateDomicilio($id_domicilio, $direccion, $tipoDireccion, $provincia, $estadoDomicilio);
      break;
    case 'eliminarDomicilio':
        $id_domicilio = $_POST['id_domicilio'];
        $proveedores->deleteDomicilio($id_domicilio);
      break;
    case 'traerDatosIniciales':
      $id_empresa = $_POST['id_empresa'];
      $proveedores->traerDatosIniciales($id_empresa);
      break;
  }
}else{
  if (isset($_GET['accion']) and $_GET['accion']=="traerProveedores") {
    $proveedores = new Proveedores();
    $id_empresa = $_GET['id_empresa'];
    echo $proveedores->traerProveedores($id_empresa);
  }
}
?>