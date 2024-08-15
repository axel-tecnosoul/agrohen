<?php
session_start();
require_once('./conexion.php');
class validateUsers{
  private $usuario;
  private $password;
  private $conexion;

  public function __construct(){
    $this->conexion = new Conexion();
    date_default_timezone_set('UTC'); 
    date_default_timezone_set("America/Buenos_Aires");
  }

  public function validateUsr($usuario, $password){
    $this->usuario = $usuario;
    $this->password = $password;

    /*Buscar usuario*/
    $queryGetUser = "SELECT u.id AS id_usuario, u.usuario, u.email, u.password, u.activo, u.id_perfil, u.id_deposito, d.nombre AS deposito FROM usuarios u LEFT JOIN destinos d ON u.id_deposito=d.id WHERE usuario = '$this->usuario'";
    //echo $queryGetUser;
    $getUser = $this->conexion->consultaRetorno($queryGetUser);
    //var_dump($getUser);
    
    if($getUser->num_rows == 0){
      echo "El usuario no existe";
    }else{
      $userRows = $getUser->fetch_assoc();
      /*Verificamos que la contraseña admin sea correcta*/
      if($this->password == $userRows['password']){
        /*Verificamos si está activo*/
        if ($userRows['activo'] > 0) {
          $_SESSION['rowUsers'] = $userRows;
          echo "1";
          $queryGetUser = "UPDATE usuarios SET fecha_hora_ultimo_login = NOW() WHERE id = ".$userRows["id_usuario"];
          $getUser = $this->conexion->consultaRetorno($queryGetUser);
        }else{
          echo "El usuario no está activo";
        }
      }else{
        echo "Contraseña incorrecta</br>";
      }
    }
  }
}

if ($_POST['accion']) {
  $validateUs = new validateUsers();
  switch ($_POST['accion']) {
    case 'validateUser':
      $usuario = $_POST['usuario'];
      $clave = $_POST['clave'];
      $validateUs->validateUsr($usuario, $clave);
      break;
    
    default:
      // code...
      break;
  }
}
