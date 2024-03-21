<?php
	session_start();
	require_once('./conexion.php');
	class AccesosWeb{
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

			/*MENUS*/
			$queryGetMenus = "SELECT id as id_menu, menu
							FROM menu_web";
			$getMenus = $this->conexion->consultaRetorno($queryGetMenus);


			$datosIniciales = array();
			$arrayMenus = array();


			/*CARGO ARRAY MENUS*/
			while ($rowsMenu= $getMenus->fetch_array()) {
				$id_menu = $rowsMenu['id_menu'];
				$menu = $rowsMenu['menu'];
				$arrayMenus[] = array('id_menu' => $id_menu, 'menu' =>$menu);
			}

			
			$datosIniciales["menus"] = $arrayMenus;
			echo json_encode($datosIniciales);
		}

		public function traerUsuarios(){

			$sqlTraerUsuarios = "SELECT usr.id as id_usuario, usr.email, perfil, 
								nombre as empresa
							FROM usuarios as usr JOIN perfiles perf
							ON(usr.id_perfil = perf.id)
							LEFT JOIN empresas as emp
							ON(usr.id_empresa = emp.id)
							WHERE id_perfil = 1";
			$traerUsuarios = $this->conexion->consultaRetorno($sqlTraerUsuarios);

			$usuarios = array(); //creamos un array

			while ($row = $traerUsuarios->fetch_array()) {
            $id_usuario = $row['id_usuario'];
            $email = $row['email'];
            $perfil = $row['perfil'];
            $empresa = $row['empresa'];
            $usuarios[] = array('id_usuario'=>$id_usuario, 'email'=>$email, 'perfil'=>$perfil, 'empresa'=>$empresa);
        }

        echo json_encode($usuarios);

		}
		public function traerPermisosUpdate($id_usuario){
			$this->id_usuario = $id_usuario;

			//TRAIGO PERMISOS POR USUARIO
			$sqlTraerPermisos = "SELECT mw.id, mw.menu, 
								case 
									when pmw.acceso IS NULL then 0
                        			else pmw.acceso
								end acceso
								FROM menu_web mw LEFT JOIN permisos_menu_web pmw
								ON(mw.id = pmw.id_menu_web)
								WHERE pmw.id_usuario = $this->id_usuario";
			$traerPermisos = $this->conexion->consultaRetorno($sqlTraerPermisos);

			$permisos = array(); //creamos un array

			while ($row = $traerPermisos->fetch_array()) {
            $id_menu = $row['id'];
            $menu = $row['menu'];
            $acceso = $row['acceso'];
            $permisos[] = array('id_menu'=> $id_menu, 'menu'=>$menu, 'acceso'=> $acceso);
        }

        echo json_encode($permisos);
		}
		public function updateAccesos($id_usuario, $id_menu, $acceso){

			$this->id_usuario = $id_usuario;

			/*VERIFICO SI TENGO DATOS EN LA TABLA PERMISOS_MENU_WEB*/
			
			$queryGetPermisos = "SELECT * FROM permisos_menu_web 
								WHERE id_usuario = $this->id_usuario
								AND id_menu_web = $id_menu";
			$getPermisos = $this->conexion->consultaRetorno($queryGetPermisos);

			if($getPermisos->num_rows > 0){
				$queryUpdateAccesos = "UPDATE permisos_menu_web set acceso = $acceso 
									WHERE id_usuario = $this->id_usuario 
									AND id_menu_web = $id_menu";
				$updateAccesos = $this->conexion->consultaSimple($queryUpdateAccesos);
			}else{
				$queryInsertAccesos = "INSERT INTO permisos_menu_web(id_usuario, id_menu_web, acceso)VALUES($this->id_usuario, $id_menu, $acceso)";
				$insertAccesos = $this->conexion->consultaSimple($queryInsertAccesos);
			}



		}

		
}	

	if (isset($_POST['accion'])) {
		$accesosWeb = new AccesosWeb();
		switch ($_POST['accion']) {
			case 'traerPermisos':
					$id_usuario = $_POST['id_usuario'];
					$accesosWeb->traerPermisosUpdate($id_usuario);
				break;
			case 'updateAccesos':
					$id_usuario = $_POST['idUsuario'];
					$id_menu = $_POST['idMenu'];
					$acceso = $_POST['acceso'];
					$accesosWeb->updateAccesos($id_usuario, $id_menu, $acceso);
				break;
			case 'traerDatosIniciales':
				$accesosWeb->traerDatosIniciales();
				break;
		}
	}else{
		if (isset($_GET['accion'])) {
			$accesosWeb = new AccesosWeb();
			$accesosWeb->traerUsuarios();
		}
	}
?>