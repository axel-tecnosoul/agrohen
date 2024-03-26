<?php
session_start();
include_once('conexion.php');
class choferes{
  private $id_chofer;
  private $nombre;
  private $telefono;
  private $mail;
  
  public function __construct(){
      $this->conexion = new Conexion();
      date_default_timezone_set("America/Buenos_Aires");
  }

   public function traerDatosIniciales(){

   /*Usuarios*/
    $queryUsuarios = "SELECT id as id_usuario, usuario FROM usuarios";
    $getUsuarios = $this->conexion->consultaRetorno($queryUsuarios);

    $datosIniciales = array();
    $arrayUsuarios = array();

    /*CARGO ARRAY usuarios*/
    while ($rowUsuarios = $getUsuarios->fetch_array()) {
      $id_usuario = $rowUsuarios['id_usuario'];
      $usuario = $rowUsuarios['usuario'];
      $arrayUsuarios[]= array('id_usuario' => $id_usuario, 'usuario' =>$usuario);
    }

    $datosIniciales["usuarios"] = $arrayUsuarios;
    echo json_encode($datosIniciales);
  }

  public function traerChoferes(){
    $sqlTraerClientes = "SELECT id AS id_chofer, nombre, telefono, email FROM choferes WHERE id != 1";
    $traerChoferes = $this->conexion->consultaRetorno($sqlTraerClientes);
    $choferes = array(); //creamos un array
    while ($row = $traerChoferes->fetch_array()) {
      $choferes[] = array(
        'id_chofer'=>$row['id_chofer'],
        'nombre'=>$row['nombre'],
        'telefono'=>$row['telefono'],
        'email'=>$row['email'],
      );
    }
    return json_encode($choferes);
  }

  public function traerChoferUpdate($id_chofer){
    $this->id_chofer = $id_chofer;
    $sqlTraerchofer = "SELECT id as id_chofer, nombre,telefono, email FROM choferes WHERE id = $this->id_chofer";
    $traerchofer = $this->conexion->consultaRetorno($sqlTraerchofer);

    $choferes = array(); //creamos un array
    while ($row = $traerchofer->fetch_array()) {
      $choferes = array(
        'id_chofer'=> $row['id_chofer'],
        'chofer'=> $row['chofer'],
        'telefono'=> $row['telefono'],
        'email'=>$row['email']
      );
    }
    return json_encode($choferes);
  }

  public function choferesUpdate($id_chofer, $nombre, $telefono,$email){

    $this->id_chofer = $id_chofer;

    $sqlUpdatechofer = "UPDATE choferes SET nombre ='$nombre', telefono= '$telefono', email ='$email' WHERE id=$this->id_chofer";
    $updatechofer = $this->conexion->consultaSimple($sqlUpdatechofer);
    $mensajeError=$this->conexion->conectar->error;
    
    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$sqlUpdatechofer;
    }else{
      $respuesta=1;
    }

    return $respuesta;
  }

  public function deleteChofer($id_chofer){
    $this->id_chofer = $id_chofer;

    /*ELIMINO ALMACEN*/
    $sqlDeletechofer = "DELETE FROM choferes WHERE id = $this->id_chofer";
    $delchofer = $this->conexion->consultaSimple($sqlDeletechofer);
  }

  public function cambiarEstado($id_chofer, $estado){

    $this->id_chofer = $id_chofer;
    
    /*if ($estado == 'Activo') {
          $estado = 1;
        }else{
          $estado = 0;
        }*/

    $queryUpdateEstado = "UPDATE choferes SET activo = $estado WHERE id = $this->id_chofer";
    $updateEstado = $this->conexion->consultaSimple($queryUpdateEstado);
  }

  public function registrarChofer($mail, $telefono, $nombre){
    $this->email = $mail;
    $this->telefono = $telefono;
    $this->nombre = $nombre;

    $queryInsertUser = "INSERT INTO choferes (chofer, email, telefono) VALUES('$chofer', '$this->nombre','$this->telefono','$this->email', NOW())";
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
		
}	

if (isset($_POST['accion'])) {
  $choferes = new choferes();
  switch ($_POST['accion']) {
    case 'traerAlmacenes':
      $almacenes->traerTodosClientes();
      break;
    case 'traerChoferUpdate':
        $id_chofer = $_POST['id_chofer'];
        echo $choferes->traerChoferUpdate($id_chofer);
      break;
    case 'updateChofer':
        $id_chofer = $_POST['id_chofer'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        echo $choferes->choferesUpdate($id_chofer, $nombre, $telefono, $email);
      break;
    case 'cambiarEstado':
        $id_chofer = $_POST['id_chofer'];
        $choferes->cambiarEstado($id_chofer);
      break;
    case 'eliminarchofer':
        $id_chofer = $_POST['id_chofer'];
        $choferes->deletechofer($id_chofer);
      break;
    case 'traerDatosIniciales':
      $choferes->traerDatosIniciales();
      break;
    case 'verificarCuenta':
      $mail = $_POST['email'];
      $choferes->verificarCuentaExitente($mail);
      break;
    case 'addchofer':
      $mail = $_POST['email'];
      $nombre = $_POST['nombre'];
      $telefono = $_POST['telefono'];
      $id_perfil = $_POST['id_perfil'];
      echo $choferes->registrarchofer($mail, $nombre, $telefono);
      break;
  }
}else{
  if (isset($_GET['accion'])) {
    $choferes = new choferes();
    echo $choferes->traerChoferes();
  }
}
?>