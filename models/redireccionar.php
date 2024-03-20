<?php
	session_start();
	include_once('conexion.php');

	class RedirUsuarios{

		private $conexion;
		private $id_usuario;

		public function __construct(){
			$this->conexion = new Conexion();
			date_default_timezone_set('UTC'); 
			date_default_timezone_set("America/Buenos_Aires");
		}

		public function redir(){
			header('Location: ../home_users.php');
		}

		public function redirFinanciera(){
			$this->id_usuario = $_SESSION['rowUsers']['id_usuario'];

      $parametros = array();
			
      /*$query = "SELECT up.id_parametro, p.parametro, up.id_usuario, up.valor 
						FROM usuarios_parametros as up LEFT JOIN parametros as p
						ON (up.id_parametro = p.id)
						WHERE id_usuario = $this->id_usuario";
			$getParametros = $this->conexion->consultaRetorno($query);
			
			while ($datos = $getParametros->fetch_assoc()) {
				$id_parametro = $datos['id_parametro'];
				$valor = $datos['valor'];
				$parametros[$id_parametro] = $valor; 
				$id_parametro = "";
				$valor = "";
			}*/

			$_SESSION['parametros'] = $parametros;
			header('Location: ./home_users.php');

		}
	}




if (isset($_SESSION['rowUsers']['id_perfil'])) {
  $redirUsuarios = new RedirUsuarios();
  switch ($_SESSION['rowUsers']['id_perfil']) {
    case 5:
      $redirUsuarios->redir();
      break;

    default:
      $redirUsuarios->redir();
      break;
  }
}else{
  header('Location: ../login.php');
}