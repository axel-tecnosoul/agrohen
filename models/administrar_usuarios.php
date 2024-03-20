<?php
	session_start();
	require_once('../../conexion.php');
	class Usuarios{
		private $id_usuario;
		private $id_proveedor;
		private $id_cliente;
		private $mail;
		private $pass;
		private $id_perfil;
		private $id_empresa;
		
		public function __construct(){
				$this->conexion = new Conexion();
				date_default_timezone_set("America/Buenos_Aires");
		}

		public function traerDatosIniciales(){

			/*PROVEEDORES*/
			$queryProveedores = "SELECT id as id_proveedor, razon_social as 			proveedor
							FROM proveedores";
			$getProveedores = $this->conexion->consultaRetorno($queryProveedores);

			/*CLIENTES*/
			$queryClientes = "SELECT id as id_cliente, razon_social as 					cliente
							FROM clientes";
			$getClientes = $this->conexion->consultaRetorno($queryClientes);

			/*CLIENTES*/
			$queryPerfiles = "SELECT id as id_perfil, perfil 
							FROM perfiles";
			$getPerfiles = $this->conexion->consultaRetorno($queryPerfiles);

			/*EMPRESAS*/
			$queryEmpresas = "SELECT id as id_empresa, nombre as empresa
							FROM empresas";
			$getEmpresas = $this->conexion->consultaRetorno($queryEmpresas);


			$datosIniciales = array();
			$arrayProveedores = array();
			$arrayClientes = array();
			$arrayPerfiles = array();
			$arrayEmpresas = array();


			/*CARGO ARRAY PROVEEDORES*/
			while ($rowsProv= $getProveedores->fetch_array()) {
				$id_proveedor = $rowsProv['id_proveedor'];
				$proveedor = $rowsProv['proveedor'];
				$arrayProveedores[] = array('id_proveedor' => $id_proveedor, 'proveedor' =>$proveedor);
			}

			/*CARGO ARRAY CLIENTES*/
			while ($rowsClientes= $getClientes->fetch_array()) {
				$id_cliente = $rowsClientes['id_cliente'];
				$cliente = $rowsClientes['cliente'];
				$arrayClientes[] = array('id_cliente' => $id_cliente, 'cliente' =>$cliente);
			}

			/*CARGO ARRAY PERFILES*/
			while ($rowPerfiles = $getPerfiles->fetch_array()) {
				$id_perfil = $rowPerfiles['id_perfil'];
				$perfil = $rowPerfiles['perfil'];
				$arrayPerfiles[]= array('id_perfil' => $id_perfil, 'perfil' =>$perfil);
			}

			/*CARGO ARRAY EMPRESAS*/
			while ($rowEmpresas = $getEmpresas->fetch_array()) {
				$id_empresa = $rowEmpresas['id_empresa'];
				$empresa = $rowEmpresas['empresa'];
				$arrayEmpresas[]= array('id_empresa' => $id_empresa, 'empresa' =>$empresa);
			}


			$datosIniciales["proveedores"] = $arrayProveedores;
			$datosIniciales["clientes"] = $arrayClientes;
			$datosIniciales["perfiles"] = $arrayPerfiles;
			$datosIniciales["empresas"] = $arrayEmpresas;
			echo json_encode($datosIniciales);
		}

		public function traerUsuarios(){

			$sqlTraerClientes = "SELECT usr.id as id_usuario, usr.email, usr.activo, 
							case 
								when usr.activo = 0 then 'Inactivo'
             					else 'Activo' 
							end estado, usr.fecha_alta, id_perfil, perfil,
        					case 
        						when id_cliente IS NULL then '' 
        						else id_cliente
        					end id_cliente,
        					case
        						when cli.razon_social IS NULL then '' 
        						else cli.razon_social
        					end cliente, 
        					case 
        						when id_proveedor IS NULL then ''
        						else id_proveedor
        					end id_proveedor, 
        					case 
        						when prv.razon_social is NULL then ''
        						else prv.razon_social
        					end proveedor, nombre as empresa
							FROM usuarios as usr JOIN perfiles perf
							ON(usr.id_perfil = perf.id)
							LEFT JOIN clientes as cli
							ON(usr.id_cliente = cli.id)
							LEFT JOIN proveedores as prv
							ON(usr.id_proveedor = prv.id)
							LEFT JOIN empresas as emp
							ON(usr.id_empresa = emp.id)";
			$traerUsuarios = $this->conexion->consultaRetorno($sqlTraerClientes);

			$usuarios = array(); //creamos un array

			while ($row = $traerUsuarios->fetch_array()) {
            $id_usuario = $row['id_usuario'];
            $email = $row['email'];
            $activo = $row['activo'];
            $estado = $row['estado'];
            $fechaAlataDate = new DateTime($row['fecha_alta']);
            $fecha_alta = date_format($fechaAlataDate, "d/m/Y");
            $id_perfi = $row['id_perfil'];
            $perfil = $row['perfil'];
            $id_cliente = $row['id_cliente'];
            $cliente = $row['cliente'];
            $id_proveedor = $row['id_proveedor'];
            $proveedor = $row['proveedor'];
            $empresa = $row['empresa'];
            $usuarios[] = array('id_usuario'=>$id_usuario, 'email'=>$email, 'activo'=>$activo, 'estado' => $estado, 'fecha_alta'=>$fecha_alta, 'id_perfi'=>$id_perfi, 'perfil'=>$perfil, 'id_cliente'=>$id_cliente, 'cliente'=>$cliente, 'id_proveedor'=>$id_proveedor, 'proveedor'=>$proveedor, 'empresa'=>$empresa);
        }

        echo json_encode($usuarios);

		}
		public function traerUsuarioUpdate($id_usuario){
			$this->id_usuario = $id_usuario;
			$sqlTraerUsuario = "SELECT id as id_usuario, email, clave, 						activo, id_perfil, 
								case 
									when id_cliente IS NULL then 0
									else id_cliente
								end id_cliente,
								case 
									when id_proveedor IS NULL then 0
									else id_proveedor
								end id_proveedor,
								case 
									when id_empresa IS NULL then 0
									else id_empresa
								end id_empresa
								FROM usuarios
								WHERE id = $this->id_usuario";
			$traerUsuario = $this->conexion->consultaRetorno($sqlTraerUsuario);

			$usuarios = array(); //creamos un array

			while ($row = $traerUsuario->fetch_array()) {
            $id_usuario = $row['id_usuario'];
            $email = $row['email'];
            $clave = $row['clave'];
            $activo = $row['activo'];
            $id_perfil = $row['id_perfil'];
            $id_cliente = $row['id_cliente'];
            $id_proveedor = $row['id_proveedor'];
            $id_empresa = $row['id_empresa'];
            $usuarios[] = array('id_usuario'=> $id_usuario, 'email'=>$email, 'clave'=> $clave, 'activo'=> $activo, 'id_perfil'=>$id_perfil, 'id_cliente'=>$id_cliente, 'id_proveedor'=>$id_proveedor, 'id_empresa'=>$id_empresa);
        }

        echo json_encode($usuarios);
		}
		public function usuariosUpdate($id_usuario, $email, $clave, $proveedor, $cliente, $perfil, $id_empresa){

			$this->id_usuario = $id_usuario;

			$this->id_cliente = $cliente;
			$this->id_proveedor = $proveedor;
			$this->id_empresa = $id_empresa;

			$sqlUpdateUsuario = "UPDATE usuarios SET email ='$email', 
							clave= '$clave', id_cliente = $this->id_cliente, 
							id_proveedor = $this->id_proveedor, id_empresa = $this->id_empresa, id_perfil = $perfil
							WHERE id=$this->id_usuario";
			$updateUsuario = $this->conexion->consultaSimple($sqlUpdateUsuario);
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

		public function verificarCuentaExitente($mail){
			$this->mail = $mail;

			$queryConsultarCuenta = "SELECT email FROM usuarios WHERE email='$this->mail'";
			$getCuenta = $this->conexion->consultaRetorno($queryConsultarCuenta);

			if($getCuenta->num_rows > 0){
				$resultado = 1;
			}else{
				$resultado = 0;
			}

			echo $resultado;
		}

		Public function registrarUsuario($mail, $clave, $idPerfil, $aprobMin, $aprobMax, $idEmpresa){
			$this->email = $mail;
			$this->pass = $clave;
			$this->id_perfil = $idPerfil;
			$this->id_empresa = $idEmpresa;
			$fecha_alta = date("Y-m-d");

			$queryInsertUser = "INSERT INTO usuarios(email, clave, activo, fecha_alta,id_perfil, id_empresa, monto_aprobacion_minimo, monto_aprobacion_maximo)VALUES('$this->email', '$this->pass', 0, '$fecha_alta', $this->id_perfil, $this->id_empresa, $aprobMin, $aprobMax)";
			$insertUser = $this->conexion->consultaSimple($queryInsertUser);
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
					$usuarios->traerUsuarioUpdate($id_usuario);
				break;
			case 'updateUsuario':
					$id_usuario = $_POST['id_usuario'];
					$email = $_POST['email'];
					$clave = $_POST['clave'];
					$proveedor = $_POST['proveedor'];
					$cliente = $_POST['cliente'];
					$perfil = $_POST['id_perfil'];
					$id_empresa = $_POST['id_empresa'];
					$usuarios->usuariosUpdate($id_usuario, $email, $clave, $proveedor, $cliente, $perfil, $id_empresa);
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
			case 'addAdmin':
				$mail = $_POST['email'];
				$clave = $_POST['clave'];
				$aprobMin = $_POST['aprobMin'];
				$aprobMax = $_POST['aprobMax'];
				$idPerfil = $_POST['id_perfil'];
				$idEmpresa = $_POST['id_empresa'];
				$usuarios->registrarUsuario($mail, $clave, $idPerfil, $aprobMin, $aprobMax, $idEmpresa);
				break;
		}
	}else{
		if (isset($_GET['accion'])) {
			$usuarios = new Usuarios();
			$usuarios->traerUsuarios();
		}
	}
?>