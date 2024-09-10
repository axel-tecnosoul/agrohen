<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include_once('conexion.php');
class depositos{
  private $id_deposito;
  private $nombre;
  private $id_responsable;
  private $porcentaje_extra;
  
  public function __construct(){
      $this->conexion = new Conexion();
      date_default_timezone_set("America/Buenos_Aires");
  }

  public function traerDatosIniciales(){

    /*Usuarios*/
    $queryResponsable = "SELECT id as id_responsable, nombre FROM responsables_deposito WHERE activo = 1";
    $getResponsable = $this->conexion->consultaRetorno($queryResponsable);

    $datosIniciales = array();
    $arrayResponsable[] = [
      'id_responsable' => "",
      'responsable' =>"Seleccione..."
    ];

    /*CARGO ARRAY responsables*/
    while ($rowResponsable = $getResponsable->fetch_array()) {
      $arrayResponsable[]= array(
        'id_responsable' =>$rowResponsable['id_responsable'],
        'responsable' =>$rowResponsable['nombre'],
      );
    }

    $datosIniciales["responsable"] = $arrayResponsable;
    echo json_encode($datosIniciales);
  }

  public function traerDepositos(){
    $sqltraerDepositos = "SELECT d.id AS id_deposito, d.nombre, rd.nombre AS responsable, d.tipo_aumento_extra, saldo_maximo_permitido, saldo_cta_cte, d.valor_extra, d.activo FROM destinos d LEFT JOIN responsables_deposito rd ON d.id_responsable=rd.id WHERE 1";
    $traerDepositos = $this->conexion->consultaRetorno($sqltraerDepositos);
    $depositos = array(); //creamos un array
    
    while ($row = $traerDepositos->fetch_array()) {
      $depositos[] = array(
        'id_deposito'=>$row['id_deposito'],
        'nombre'=>$row['nombre'],
        'responsable'=>$row['responsable'],
        'tipo_aumento_extra'=>$row['tipo_aumento_extra'],
        'valor_extra'=>$row['valor_extra'],
        'saldo_max'=>$row['saldo_maximo_permitido'],
        'saldo_cta_cte'=>$row["saldo_cta_cte"],
        'activo'=>$row['activo'],
      );
    }
    return json_encode($depositos);
  }

  public function traerDepositoUpdate($id_deposito){
    $this->id_deposito = $id_deposito;
    $sqlTraerdeposito = "SELECT id as id_deposito, nombre, id_responsable, tipo_aumento_extra, saldo_maximo_permitido, valor_extra, activo FROM destinos WHERE id = $this->id_deposito";
    $traerdeposito = $this->conexion->consultaRetorno($sqlTraerdeposito);

    $depositos = array(); //creamos un array
    while ($row = $traerdeposito->fetch_array()) {
      $depositos = array(
        'id_deposito'=> $row['id_deposito'],
        'nombre'=> $row['nombre'],
        'id_responsable'=> $row['id_responsable'],
        'tipo_aumento_extra'=> $row['tipo_aumento_extra'],
        'saldo_max'=>$row['saldo_maximo_permitido'],
        'valor_extra'=> $row['valor_extra'],
      );
    }
    return json_encode($depositos);
  }

  public function depositoUpdate($id_deposito, $nombre, $id_responsable, $opcion,$saldo_max, $valor){

    $this->id_deposito = $id_deposito;
    $this->nombre = $nombre;
    $this->saldo_max = $saldo_max;
    $this->opcion = $opcion;
    if($valor==""){
      $valor="NULL";
    }
    if($id_responsable==""){
      $id_responsable="NULL";
    }
    if($saldo_max==""){
      $saldo_max="NULL";
    }

    $sqlUpdateDeposito = "UPDATE destinos SET nombre ='$this->nombre',  id_responsable =$id_responsable, tipo_aumento_extra ='$opcion',  saldo_maximo_permitido = $saldo_max, valor_extra = $valor WHERE id = $this->id_deposito";
    $updateDeposito = $this->conexion->consultaSimple($sqlUpdateDeposito);
    $mensajeError=$this->conexion->conectar->error;
    
    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$sqlUpdateDeposito;
    }else{
      $respuesta=1;
    }

    return $respuesta;
  }

  public function deletedeposito($id_deposito){
    $this->id_deposito = $id_deposito;

    /*ELIMINO ALMACEN*/
    $sqldeletedeposito = "DELETE FROM destinos WHERE id = $this->id_deposito";
    $deletedeposito = $this->conexion->consultaSimple($sqldeletedeposito);
    $mensajeError=$this->conexion->conectar->error;
    if($mensajeError==""){
      $r=1;
    }else{
      if (strpos($mensajeError, "Cannot delete or update a parent row") === 0) {
        // La cadena comienza con "Cannot delete or update a parent row"
        $r="El registro está siendo utilizado en la base de datos";
      } else {
        $r=$mensajeError;
      }
    }
    return json_encode($r);
  }

  public function cambiarEstado($id_deposito, $estado){
    $this->id_deposito = $id_deposito;
    
    /*if ($estado == 'Activo') {
      $estado = 1;
    }else{
      $estado = 0;
    }*/

    $queryUpdateEstado = "UPDATE destinos SET activo = $estado WHERE id = $this->id_deposito";
    $updateEstado = $this->conexion->consultaSimple($queryUpdateEstado);
  }

  public function getSaldoCtaCte($id_deposito){
    $query = "SELECT saldo_cta_cte FROM destinos WHERE id = $id_deposito";
    $getData = $this->conexion->consultaRetorno($query);
    $row = $getData->fetch_array();
    return $row["saldo_cta_cte"];
  }

  public function actualizarSaldoCtaCte($id_deposito){
    $query = "SELECT SUM(cd.total_monto_valor_extra) AS saldo_carga FROM cargas_destinos cd INNER JOIN cargas c ON cd.id_carga=c.id WHERE c.anulado=0 AND c.fecha_hora_despacho IS NOT NULL AND cd.id_destino=$id_deposito";
    //echo $query."<br>";
    $getData = $this->conexion->consultaRetorno($query);
    $row = $getData->fetch_array();
    $saldo_carga=$row["saldo_carga"];
    //var_dump($saldo_carga);

    $query = "SELECT SUM(IF(tipo_movimiento='debe',monto,(monto*-1))) AS saldo_movimientos FROM movimientos_cta_cte WHERE anulado=0 AND id_destino=$id_deposito";
    //echo $query."<br>";
    $getData = $this->conexion->consultaRetorno($query);
    $row = $getData->fetch_array();
    $saldo_movimientos=$row["saldo_movimientos"];
    //var_dump($saldo_movimientos);

    $saldo_cta_cte=$saldo_carga+$saldo_movimientos;
    //var_dump($saldo_cta_cte);

    $queryUpdateEstado = "UPDATE destinos SET saldo_cta_cte = $saldo_cta_cte WHERE id = $id_deposito";
    //echo $queryUpdateEstado."<hr>";
    $updateEstado = $this->conexion->consultaSimple($queryUpdateEstado);
    $mensajeError=$this->conexion->conectar->error;
    if($mensajeError!=""){
      echo $queryUpdateEstado."<br>".$mensajeError;
      return 0;
    }else{
      return 1;
    }
  }

  private function actualizarSaldoCtaCte2($id_deposito, $monto, $operacion){
    $saldo_viejo=$this->getSaldoCtaCte($id_deposito);
    //var_dump($saldo_viejo);

    //var_dump($monto);
    if ($operacion==='+') {
      $proximo_nuevo_saldo=$saldo_viejo+$monto;
    } elseif ($operacion==='-') {
      $proximo_nuevo_saldo=$saldo_viejo-$monto;
    }
    //var_dump($proximo_nuevo_saldo);

    $queryUpdateEstado = "UPDATE destinos SET saldo_cta_cte = saldo_cta_cte $operacion $monto WHERE id = $id_deposito";
    $updateEstado = $this->conexion->consultaSimple($queryUpdateEstado);
    $mensajeError=$this->conexion->conectar->error;
    if($mensajeError!=""){
      echo $queryUpdateEstado."<br>".$mensajeError;
    }

    $saldo_nuevo=$this->getSaldoCtaCte($id_deposito);
    //var_dump($saldo_nuevo);

    if($proximo_nuevo_saldo==$saldo_nuevo){
      return 1;
    }else{
      return 0;
    }
  }

  public function aumentarSaldoCtaCte($id_deposito, $monto){
    $operacion="+";
    return $this->actualizarSaldoCtaCte($id_deposito, $monto, $operacion);
  }

  public function disminuirSaldoCtaCte($id_deposito, $monto){
    $operacion="-";
    return $this->actualizarSaldoCtaCte($id_deposito, $monto, $operacion);
  }

  public function registrardeposito($nombre,$id_responsable,$opcion, $saldo_max, $valor){
    $this->nombre = $nombre;
    $this->opcion = $opcion;
    $this->valor = $valor;
    $this->saldo_max = $saldo_max;
    if($valor==""){
      $valor="NULL";
    }
    if($id_responsable==""){
      $id_responsable="NULL";
    }
    if($saldo_max==""){
      $saldo_max="NULL";
    }
    $id_usuario = $_SESSION['rowUsers']['id_usuario'];

    $queryInsertUser = "INSERT INTO destinos (id_usuario, nombre, id_responsable, tipo_aumento_extra, saldo_maximo_permitido, valor_extra, activo) VALUES($id_usuario, '$this->nombre', $id_responsable, '$opcion', $saldo_max, $valor, 1)";
    //echo $queryInsertUser;
    $insertUser = $this->conexion->consultaSimple($queryInsertUser);
    $mensajeError=$this->conexion->conectar->error;
    
    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$queryInsertUser;
    }else{
      $respuesta=1;
    }
    
    return $respuesta;
  }

  public function verificarSaldo($id_deposito) {
    $query = "SELECT saldo_maximo_permitido, saldo_cta_cte FROM destinos WHERE id = $id_deposito";
    $result = $this->conexion->consultaRetorno($query);
    
    if ($result && $row = $result->fetch_array()) {
        $saldo_maximo_permitido = $row['saldo_maximo_permitido'];
        $saldo_cta_cte = $row['saldo_cta_cte'];

        $excede_maximo = ($saldo_maximo_permitido !== null && $saldo_maximo_permitido > 0) 
            ? $saldo_cta_cte > $saldo_maximo_permitido 
            : false;
        
        return array(
            'excede_maximo' => $excede_maximo,
            'saldo_maximo_permitido' => $saldo_maximo_permitido,
            'saldo_cta_cte' => $saldo_cta_cte
        );
    }
    
    return array(
        'excede_maximo' => false,
        'saldo_maximo_permitido' => null,
        'saldo_cta_cte' => null
    );
  }
}	

if (isset($_POST['accion'])) {
  $depositos = new depositos();
  switch ($_POST['accion']) {
    case 'traerAlmacenes':
      $almacenes->traerTodosdepositos();
      break;
    case 'traerDepositoUpdate':
      $id_deposito = $_POST['id_deposito'];
      echo $depositos->traerDepositoUpdate($id_deposito);
      break;
    case 'updateDeposito':
      $id_deposito = $_POST['id_deposito'];
      $nombre = $_POST['nombre'];
      $id_responsable = $_POST['id_responsable'];
      $opcion = $_POST['opcion'];
      $saldo_max = $_POST['saldo_max'];
      $valor = $_POST['valor'];
      echo $depositos->depositoUpdate($id_deposito, $nombre, $id_responsable, $opcion, $saldo_max, $valor);
      break;
    case 'cambiarEstado':
      $id_deposito = $_POST['id_deposito'];
      $estado = $_POST['estado'];
      $depositos->cambiarEstado($id_deposito, $estado);
      break;
    case 'eliminarDeposito':
      $id_deposito = $_POST['id_deposito'];
      echo $depositos->deletedeposito($id_deposito);
      break;
    case 'traerDatosInicialesDepositos':
      $depositos->traerDatosIniciales();
      break;
    case 'verificarCuenta':
      $email = $_POST['email'];
      $depositos->verificarCuentaExitente($email);
      break;
    case 'addDeposito':
      $nombre = $_POST['nombre'];
      $id_responsable = $_POST['id_responsable'];
      $opcion = $_POST['opcion'];
      $saldo_max = $_POST['saldo_max'];
      $valor = $_POST['valor'];
      echo $depositos->registrardeposito($nombre,$id_responsable,$opcion, $saldo_max, $valor);
      break;
  }
}else{
  if (isset($_GET['accion'])) {
    $depositos = new depositos();
    switch ($_GET['accion']) {
      case 'traerDeposito':
        echo $depositos->traerDepositos();
      break;
    }
  }
}
?>