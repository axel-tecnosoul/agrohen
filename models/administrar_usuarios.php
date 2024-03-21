<?php
	session_start();
	require_once('./conexion.php');
	class Usuarios{
		private $id_usuario;
		private $mail;
		private $pass;
		private $id_perfil;
		private $usuario;
		
		public function __construct(){
				$this->conexion = new Conexion();
				date_default_timezone_set("America/Buenos_Aires");
		}

		public function traerDatosIniciales(){

			/*PERFILES*/
			$queryPerfiles = "SELECT id as id_perfil, perfil FROM perfiles";
			$getPerfiles = $this->conexion->consultaRetorno($queryPerfiles);

			$datosIniciales = array();
			$arrayPerfiles = array();

			/*CARGO ARRAY PERFILES*/
			while ($rowPerfiles = $getPerfiles->fetch_array()) {
				$id_perfil = $rowPerfiles['id_perfil'];
				$perfil = $rowPerfiles['perfil'];
				$arrayPerfiles[]= array('id_perfil' => $id_perfil, 'perfil' =>$perfil);
			}

			$datosIniciales["perfiles"] = $arrayPerfiles;
			echo json_encode($datosIniciales);
		}

		public function traerUsuarios(){
			$sqlTraerClientes = "SELECT u.id AS id_usuario, u.usuario, u.email, u.activo, u.fecha_hora_alta, u.id_perfil, p.perfil FROM usuarios u INNER JOIN perfiles p ON u.id_perfil=p.id";
			$traerUsuarios = $this->conexion->consultaRetorno($sqlTraerClientes);
			$usuarios = array(); //creamos un array
			while ($row = $traerUsuarios->fetch_array()) {
        $usuarios[] = array(
          'id_usuario'=>$row['id_usuario'],
          'usuario'=>$row['usuario'],
          'email'=>$row['email'],
          'activo'=>$row['activo'],
          'fecha_alta'=>date("d/m/Y H:i",strtotime($row['fecha_hora_alta'])),
          'id_perfil'=>$row['id_perfil'],
          'perfil'=>$row['perfil']
        );
      }
      return json_encode($usuarios);
		}

		public function traerUsuarioUpdate($id_usuario){
			$this->id_usuario = $id_usuario;
			$sqlTraerUsuario = "SELECT id as id_usuario, usuario, email, password, activo, id_perfil FROM usuarios WHERE id = $this->id_usuario";
			$traerUsuario = $this->conexion->consultaRetorno($sqlTraerUsuario);

			$usuarios = array(); //creamos un array
			while ($row = $traerUsuario->fetch_array()) {
        $usuarios = array(
          'id_usuario'=> $row['id_usuario'],
          'usuario'=> $row['usuario'],
          'email'=>$row['email'],
          'password'=> $row['password'],
          'activo'=> $row['activo'],
          'id_perfil'=>$row['id_perfil']
        );
      }
      return json_encode($usuarios);
		}

		public function usuariosUpdate($id_usuario, $usuario, $email, $password, $id_perfil){

			$this->id_usuario = $id_usuario;

			$sqlUpdateUsuario = "UPDATE usuarios SET usuario ='$usuario', email ='$email', password= '$password', id_perfil = $id_perfil WHERE id=$this->id_usuario";
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
		}

		public function cambiarEstado($id_usuario, $estado){

			$this->id_usuario = $id_usuario;
			
			/*if ($estado == 'Activo') {
		        $estado = 1;
		      }else{
		        $estado = 0;
		      }*/

			$queryUpdateEstado = "UPDATE usuarios SET activo = $estado 
								WHERE id = $this->id_usuario";
			$updateEstado = $this->conexion->consultaSimple($queryUpdateEstado);
		}

		public function registrarUsuario($mail, $password, $id_perfil, $usuario){
			$this->email = $mail;
			$this->pass = $password;
			$this->id_perfil = $id_perfil;
			$this->usuario = $usuario;

			$queryInsertUser = "INSERT INTO usuarios (usuario, email, password, activo, fecha_hora_alta, id_perfil) VALUES('$usuario', '$this->email', '$this->pass', 1, NOW(), $this->id_perfil)";
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
					echo $usuarios->usuariosUpdate($id_usuario, $usuario, $email, $password, $id_perfil);
				break;
			case 'cambiarEstado':
					$id_usuario = $_POST['id_usuario'];
					$estado = $_POST['estado'];
					$usuarios->cambiarEstado($id_usuario, $estado);
				break;
			case 'eliminarUsuario':
					$id_usuario = $_POST['id_usuario'];
					$usuarios->deleteUsuario($id_usuario);
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
				echo $usuarios->registrarUsuario($mail, $password, $id_perfil, $usuario);
				break;
		}
	}else{
		if (isset($_GET['accion'])) {
			$usuarios = new Usuarios();
			echo $usuarios->traerUsuarios();
		}
	}
?>