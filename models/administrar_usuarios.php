<?php
session_start();
require_once('./conexion.php');
class Usuarios{
  private $conexion;
  private $id_usuario;
  private $mail;
  private $pass;
  private $id_perfil;
  private $id_deposito;
  private $usuario;
  private $email;
  
  public function __construct(){
      $this->conexion = new Conexion();
      date_default_timezone_set("America/Buenos_Aires");
  }

  public function traerDatosIniciales(){

    $datosIniciales = array();
    
    /*PERFILES*/
    $queryPerfiles = "SELECT id as id_perfil, perfil FROM perfiles WHERE activo = 1";
    $getPerfiles = $this->conexion->consultaRetorno($queryPerfiles);

    $arrayPerfiles = array();
    /*CARGO ARRAY PERFILES*/
    while ($rowPerfiles = $getPerfiles->fetch_array()) {
      $arrayPerfiles[]= array(
        'id_perfil' =>$rowPerfiles['id_perfil'],
        'perfil' =>$rowPerfiles['perfil'],
      );
    }

    /*DEPOSITOS*/
    $queryDepositos = "SELECT id as id_deposito, nombre FROM destinos WHERE activo = 1";
    $getDepositoss = $this->conexion->consultaRetorno($queryDepositos);

    $arrayDepositos = array();
    /*CARGO ARRAY DEPOSITOS*/
    while ($rowDepositos = $getDepositoss->fetch_array()) {
      $arrayDepositos[]= array(
        'id_deposito' =>$rowDepositos['id_deposito'],
        'nombre' =>$rowDepositos['nombre'],
      );
    }

    $datosIniciales["perfiles"] = $arrayPerfiles;
    $datosIniciales["depositos"] = $arrayDepositos;
    echo json_encode($datosIniciales);
  }

  public function traerUsuarios(){
    $sqlTraerClientes = "SELECT u.id AS id_usuario, u.usuario, u.email, u.activo, u.fecha_hora_alta, u.id_perfil, p.perfil, d.nombre AS deposito FROM usuarios u INNER JOIN perfiles p ON u.id_perfil=p.id LEFT JOIN destinos d ON u.id_deposito = d.id WHERE u.id!=1";
    $traerUsuarios = $this->conexion->consultaRetorno($sqlTraerClientes);
    $usuarios = array(); //creamos un array
    while ($row = $traerUsuarios->fetch_array()) {
      $usuarios[] = array(
        'id_usuario'=>$row['id_usuario'],
        'usuario'=>$row['usuario'],
        'email'=>$row['email'],
        'activo'=>$row['activo'],
        'fecha_alta'=>formatFechaHora($row['fecha_hora_alta']),
        'id_perfil'=>$row['id_perfil'],
        'perfil'=>$row['perfil'],
        'deposito'=>$row['deposito']
      );
    }
    return json_encode($usuarios);
  }

  public function traerUsuarioUpdate($id_usuario){
    $this->id_usuario = $id_usuario;
    $sqlTraerUsuario = "SELECT id as id_usuario, usuario, email, password, activo, id_perfil, id_deposito FROM usuarios WHERE id = $this->id_usuario";
    $traerUsuario = $this->conexion->consultaRetorno($sqlTraerUsuario);

    $usuarios = array(); //creamos un array
    while ($row = $traerUsuario->fetch_array()) {
      $usuarios = array(
        'id_usuario'=> $row['id_usuario'],
        'usuario'=> $row['usuario'],
        'email'=>$row['email'],
        'password'=> $row['password'],
        'activo'=> $row['activo'],
        'id_perfil'=>$row['id_perfil'],
        'id_deposito'=>$row['id_deposito'],
      );
    }
    return json_encode($usuarios);
  }

  public function usuariosUpdate($id_usuario, $usuario, $email, $password, $id_perfil, $id_deposito){
    $this->email = $email;
    $this->pass = $password;
    $this->id_perfil = $id_perfil;
    $this->id_deposito = $id_deposito;
    $this->usuario = $usuario;
    $this->id_usuario = $id_usuario;

    $sqlUpdateUsuario = "UPDATE usuarios SET usuario ='$this->usuario', email ='$this->email', password= '$this->pass', id_perfil = $this->id_perfil, id_deposito = $this->id_deposito WHERE id=$this->id_usuario";
    $updateUsuario = $this->conexion->consultaSimple($sqlUpdateUsuario);
    $mensajeError=$this->conexion->conectar->error;
    
    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$sqlUpdateUsuario;
    }else{
      $respuesta=1;
    }

    return $respuesta;
  }

  public function deleteUsuario($id_usuario){
    $this->id_usuario = $id_usuario;

    /*ELIMINO ALMACEN*/
    $sqlDeleteUsuario = "DELETE FROM usuarios WHERE id = $this->id_usuario";
    $delUsuario = $this->conexion->consultaSimple($sqlDeleteUsuario);
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

  public function cambiarEstado($id_usuario, $estado){

    $this->id_usuario = $id_usuario;
    
    /*if ($estado == 'Activo') {
          $estado = 1;
        }else{
          $estado = 0;
        }*/

    $queryUpdateEstado = "UPDATE usuarios SET activo = $estado WHERE id = $this->id_usuario";
    $updateEstado = $this->conexion->consultaSimple($queryUpdateEstado);
  }

  public function registrarUsuario($mail, $password, $id_perfil, $usuario, $id_deposito){
    $this->email = $mail;
    $this->pass = $password;
    $this->id_perfil = $id_perfil;
    $this->id_deposito = $id_deposito;
    $this->usuario = $usuario;

    $queryInsertUser = "INSERT INTO usuarios (usuario, email, password, id_perfil, id_deposito) VALUES('$this->usuario', '$this->email', '$this->pass', $this->id_perfil, $this->id_deposito)";
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
  $usuarios = new Usuarios();
  switch ($_POST['accion']) {
    case 'traerAlmacenes':
      $almacenes->traerTodosClientes();
      break;
    case 'traerUsuarioUpdate':
        $id_usuario = $_POST['id_usuario'];
        echo $usuarios->traerUsuarioUpdate($id_usuario);
      break;
    case 'updateUsuario':
        $id_usuario = $_POST['id_usuario'];
        $usuario = $_POST['usuario'];
        $email = $_POST['email'];
        $password = $_POST['clave'];
        $id_perfil = $_POST['id_perfil'];
        $id_deposito = $_POST['id_deposito'];
        if(empty($id_deposito)){
          $id_deposito="NULL";
        }
        echo $usuarios->usuariosUpdate($id_usuario, $usuario, $email, $password, $id_perfil, $id_deposito);
      break;
    case 'cambiarEstado':
        $id_usuario = $_POST['id_usuario'];
        $estado = $_POST['estado'];
        $usuarios->cambiarEstado($id_usuario, $estado);
      break;
    case 'eliminarUsuario':
        $id_usuario = $_POST['id_usuario'];
        echo $usuarios->deleteUsuario($id_usuario);
      break;
    case 'traerDatosIniciales':
      $usuarios->traerDatosIniciales();
      break;
    case 'verificarCuenta':
      $mail = $_POST['email'];
      $usuarios->verificarCuentaExitente($mail);
      break;
    case 'addUsuario':
      $mail = $_POST['email'];
      $password = $_POST['clave'];
      $usuario = $_POST['usuario'];
      $id_perfil = $_POST['id_perfil'];
      $id_deposito = $_POST['id_deposito'];
      if(empty($id_deposito)){
        $id_deposito="NULL";
      }
      echo $usuarios->registrarUsuario($mail, $password, $id_perfil, $usuario, $id_deposito);
      break;
  }
}else{
  if (isset($_GET['accion'])) {
    $usuarios = new Usuarios();
    echo $usuarios->traerUsuarios();
  }
}
?>