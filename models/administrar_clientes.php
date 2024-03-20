<?php
	//session_start();
	require_once('../../conexion.php');
  require_once("administrar_almacenes.php");

  if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    // session isn't started
    session_start();
  }
  extract($_REQUEST);
	class Clientes{
		private $id_cliente;
		private $id_contacto;
		private $id_domicilio;
		private $id_empresa;

		public function __construct(){
				$this->conexion = new Conexion();
        $this->id_empresa = $_SESSION["rowUsers"]["id_empresa"];
				date_default_timezone_set("America/Buenos_Aires");
		}

		public function traerDatosIniciales(){

			$datosIniciales = array();
			$pvcias = array();
			$tiposIva = array();
			$arrayCargos = array();
			$arrayTipoDireccion = array();
      $arrayCentrosCosto = array();

      /*PROVINCAIS*/
			$queryProvincia = "SELECT id, provincia FROM provincias";
			$getProvincias = $this->conexion->consultaRetorno($queryProvincia);
			/*CARGO ARRAY PROVINCIAS*/
			while ($rowsProv= $getProvincias->fetch_array()) {
				$id_provincia = $rowsProv['id'];
				$provincia = $rowsProv['provincia'];
				$pvcias[] = array('id_provincia' => $id_provincia, 'nombreProv' =>$provincia);
			}

      /*TIPOS DE IVA*/
			$queryTipoIva = "SELECT id as idIva, tipo FROM tipos_iva_responsable";
			$getTipoIva = $this->conexion->consultaRetorno($queryTipoIva);
			/*CARGO ARRAY TIPO IVA*/
			while ($rowsIva= $getTipoIva->fetch_array()) {
				$id_iva = $rowsIva['idIva'];
				$tipoIva = $rowsIva['tipo'];
				$tiposIva[] = array('id_iva' => $id_iva, 'tipoIva' =>$tipoIva);
			}

      /*CARGOS*/
			$queryCargos = "SELECT id as idCargo, cargo FROM cargos";
			$getCargos = $this->conexion->consultaRetorno($queryCargos);
			/*CARGO ARRAY CARGOS*/
			while ($rowCargos = $getCargos->fetch_array()) {
				$id_cargo = $rowCargos['idCargo'];
				$cargo = $rowCargos['cargo'];
				$arrayCargos[]= array('id_cargo' => $id_cargo, 'cargo' =>$cargo);
			}

      /*TIPO DIRECCIÓN*/
			$queryTipoDireccion = "SELECT id as idTipoDireccion, tipo as tipoDireccion FROM tipos_direccion";
			$getTipoDireccion = $this->conexion->consultaRetorno($queryTipoDireccion);
			/*CARGO ARRAY TIPO DIRECCION*/
			while ($rowTipoDir = $getTipoDireccion->fetch_array()) {
				$idTipoDireccion = $rowTipoDir['idTipoDireccion'];
				$tipoDireccion = $rowTipoDir['tipoDireccion'];
				$arrayTipoDireccion[]= array('idTipoDireccion' => $idTipoDireccion, 'tipoDireccion' =>$tipoDireccion);
			}

      $almacenes=new Almacenes();
      $almacenes=$almacenes->traerAlmacenes();
      $almacenes=json_decode($almacenes,true);
      //var_dump($almacenes);

      /*CENTROS DE COSTO*/
			$queryCentrosCosto = "SELECT id as idCentroCosto, nombre as centroCosto FROM centro_costos WHERE id_empresa = $this->id_empresa";
			$getCentrosCosto = $this->conexion->consultaRetorno($queryCentrosCosto);
			/*CARGO ARRAY CENTROS DE COSTO*/
			while ($rowCentroCosto = $getCentrosCosto->fetch_array()) {
				$idCentroCosto = $rowCentroCosto['idCentroCosto'];
				$centroCosto = $rowCentroCosto['centroCosto'];
				$arrayCentrosCosto[]= array('idCentroCosto' => $idCentroCosto, 'centroCosto' =>$centroCosto);
			}

			$datosIniciales["provincias"] = $pvcias;
			$datosIniciales["condicion_iva"] = $tiposIva;
			$datosIniciales["cargos"] = $arrayCargos;
			$datosIniciales["tipo_domicilio"] = $arrayTipoDireccion;
      $datosIniciales["almacenes"] = $almacenes;
      $datosIniciales["centros_costo"] = $arrayCentrosCosto;
			echo json_encode($datosIniciales);
		}

		Public function agregarCliente($alias, $razon_social, $cuit, $tel1, $tel2, $fax, $email, $direccionCli, $localidad, $provinciaCli, $estado, $id_tipo_iva, $id_empresa){
			$fecha_alta = date('Y-m-d');

			if ($estado == 'Activo') {
				$estado = 1;
			}else{
				$estado = 0;
			}

			$this->id_empresa = $id_empresa;

			/*TERMINAR MAÑANA*/

			$sqlInsertCliente = "INSERT INTO clientes(alias, razon_social, cuit, tel1, tel2, fax, email, direccion_cliente, localidad_cliente, id_provincia, fecha_alta, activo, id_tipo_iva_responsable, id_empresa) VALUES('$alias', '$razon_social', '$cuit','$tel1', '$tel2', '$fax', '$email', '$direccionCli', '$localidad', $provinciaCli, '$fecha_alta', $estado, $id_tipo_iva, $this->id_empresa)";
			$insertCliente = $this->conexion->consultaSimple($sqlInsertCliente);

		}

		public function traerClientes($id_empresa){

			$this->id_empresa = $id_empresa;

			$sqlTraerClientes = "SELECT cl.id as id_cliente, alias, razon_social, 
								direccion_cliente, localidad_cliente, tel1, cuit, 
								case 
									when activo = 1 then 'Activo' 
									else 'Inactivo' 
								end activo
								FROM clientes as cl
								WHERE id_empresa = $this->id_empresa";
			$traerClientes = $this->conexion->consultaRetorno($sqlTraerClientes);

			$clientes = array(); //creamos un array

			while ($row = $traerClientes->fetch_array()) {
            $id_cliente = $row['id_cliente'];
            $alias = $row['alias'];
            $razon_social = $row['razon_social'];
            $direccion_cliente = $row['direccion_cliente'];
            $localidad_cliente = $row['localidad_cliente'];
            $tel1 = $row['tel1'];
            $cuit = $row['cuit'];
            $activo = $row['activo'];
            $clientes[] = array('id_cliente'=> $id_cliente, 'alias'=>$alias, 'razon_social'=> $razon_social, 'direccion_cliente'=>$direccion_cliente, 'localidad_cliente'=>$localidad_cliente, 'tel1'=>$tel1, 'cuit'=> $cuit, 'activo'=> $activo);
        }

        //echo json_encode($clientes);
        return json_encode($clientes);

		}
		
    public function traerClienteUpdate($id_cliente){
			$this->id_cliente = $id_cliente;
			$sqlTraerCliente = "SELECT id as id_cliente, alias, razon_social, 
							cuit, tel1, tel2, fax, email, direccion_cliente,
							localidad_cliente, id_provincia,
							case 
								when activo = 1 then 'Activo' 
								else 'Inactivo' 
							end activo, id_tipo_iva_responsable
								FROM clientes
								WHERE id = $this->id_cliente";
			$traerCliente = $this->conexion->consultaRetorno($sqlTraerCliente);

			$cliente = array(); //creamos un array

			while ($row = $traerCliente->fetch_array()) {
            $id_cliente = $row['id_cliente'];
            $alias = $row['alias'];
            $razon_social = $row['razon_social'];
            $tel1 = $row['tel1'];
            $tel2 = $row['tel2'];
            $fax = $row['fax'];
            $email = $row['email'];
            $direccion_cliente = $row['direccion_cliente'];
            $localidad_cliente = $row['localidad_cliente'];
            $provincia = $row['id_provincia'];
            $cuit = $row['cuit'];
            $activo = $row['activo'];
            $condicion_IVA = $row['id_tipo_iva_responsable'];
            $cliente[] = array('id_cliente'=> $id_cliente, 'alias'=>$alias, 'razon_social'=>$razon_social, 'tel1'=>$tel1, 'tel2'=>$tel2, 'fax'=>$fax, 'direccion_cliente'=>$direccion_cliente, 'localidad_cliente'=>$localidad_cliente, 'provincia'=>$provincia, 'email'=>$email, 'cuit'=>$cuit, 'activo'=> $activo, 'iva'=>$condicion_IVA);
        }

        echo json_encode($cliente);
		}
		
    public function clienteUpdate($id_cliente, $alias, $razon_social, $cuit, $tel1, $tel2, $fax, $email, $direccionCli, $localidad, $provinciaCli, $estado, $id_tipo_iva){

			if ($estado == 'Activo') {
				$estado = 1;
			}else{
				$estado = 0;
			}

			$sqlUpdateCliente = "UPDATE clientes SET alias='$alias', razon_social ='$razon_social', cuit= '$cuit', tel1='$tel1', tel2='$tel2', fax='$fax',email = '$email', direccion_cliente = '$direccionCli', localidad_cliente = '$localidad', id_provincia = $provinciaCli, activo = $estado, id_tipo_iva_responsable= $id_tipo_iva
								WHERE id=$id_cliente";
			$updateCliente = $this->conexion->consultaSimple($sqlUpdateCliente);
		}

		public function deleteCliente($id_cliente){
			$this->id_cliente = $id_cliente;

			/*ELIMINO CONTACTOS DE CLIENTES*/
			$sqlDeleteContactos = "DELETE FROM contactos_clientes WHERE id_cliente = $this->id_cliente";
			$deleteContacto = $this->conexion->consultaSimple($sqlDeleteContactos);

			/*ELIMINO DIRECCIONES DE CLIENTES*/
			$sqlDeleteDirecciones = "DELETE FROM direcciones_clientes WHERE id_cliente = $this->id_cliente";
			$deleteDirecciones = $this->conexion->consultaSimple($sqlDeleteDirecciones);

			/*ELIMINO CLIENTE*/
			$sqlDeleteCliente = "DELETE FROM clientes WHERE id = $this->id_cliente";
			$delCliente = $this->conexion->consultaSimple($sqlDeleteCliente);
		}

		public function traerContactos($filtros=0){
      $contactos = array(); //creamos un array

      $filtro_contacto="";
      $filtro_cliente="";
      $filtro_ubicacion="";
      if($filtros!=0){
          //var_dump($filtros);
          if(isset($filtros["id_contacto"]) and $filtros["id_contacto"]!=""){
              $filtro_contacto=" AND ccl.id = ".$filtros["id_contacto"];
          }
          if(isset($filtros["id_cliente"]) and $filtros["id_cliente"]!=""){
              $filtro_cliente=" AND dc.id_cliente = ".$filtros["id_cliente"];
          }
          if(isset($filtros["id_ubicacion"]) and $filtros["id_ubicacion"]!=""){
              $filtro_ubicacion=" AND dc.id = ".$filtros["id_ubicacion"];
          }
      }
			//$this->id_cliente = $id_cliente;

			$query = "SELECT ccl.id as id_contacto, nombre_completo, email, telefono, cg.cargo cargo, cg.id as id_cargo, IF(activo = 1,'Activo','Inactivo') AS activo, dc.id as id_direccion, dc.direccion
			FROM contactos_clientes ccl 
        join cargos cg ON(ccl.id_cargo = cg.id)
				JOIN direcciones_clientes as dc ON (ccl.id_direccion = dc.id)
			WHERE 1 $filtro_contacto $filtro_cliente $filtro_ubicacion ORDER BY ccl.id";//ccl.id_cliente = $this->id_cliente
      //var_dump($query);
			$getContactos = $this->conexion->consultaRetorno($query);

			$arrayContactos = array(); //creamos un array
			
			/*lleno array contactos*/
			if($getContactos){
				while ($row = $getContactos->fetch_array()) {
            	$arrayContactos[] = array(
                'id_contacto'     =>$row['id_contacto'],
                'nombre_completo' =>$row['nombre_completo'],
                'email'           =>$row['email'],
                'telefono'        =>$row['telefono'],
                'cargo'           =>$row['cargo'],
                'id_cargo'        =>$row['id_cargo'],
                'activo'          => $row['activo'],
                'id_direccion'    =>$row['id_direccion'],
                'direccion'       =>$row['direccion'],
              );
        		}
			}

      $queryGetLocaciones = "SELECT id as id_direccion, direccion 
								FROM direcciones_clientes dc
								WHERE 1 $filtro_cliente $filtro_ubicacion";//id_cliente = $this->id_cliente
      //var_dump($queryGetLocaciones);
			$getLocaciones = $this->conexion->consultaRetorno($queryGetLocaciones);

      $arrayLocaciones = array(); //creamos un array

			/*Lleno array locaciones*/
			if($getLocaciones){
				while ($rowLocaciones = $getLocaciones->fetch_array()) {
          $arrayLocaciones[] = array(
            'id_locaciones'=>$rowLocaciones['id_direccion'],
            'direccion'=>$rowLocaciones['direccion'],
          );
        }
			}

			$contactos['locaciones']= $arrayLocaciones;
			$contactos['contactos']= $arrayContactos;
      return json_encode($contactos);

		}

		public function agregarContacto($id_cliente, $nombreContacto, $emailContacto, $telContacto, $estadoContacto, $cargoContacto, $locaciones_contacto){

			if ($estadoContacto == 'Activo') {
				$estado = 1;
			}else{
				$estado = 0;
			}

			$query = "INSERT INTO contactos_clientes(nombre_completo, email, telefono, id_cliente, id_cargo, activo, id_direccion) VALUES('$nombreContacto', '$emailContacto', '$telContacto', $id_cliente, $cargoContacto, $estado, $locaciones_contacto)";
			$addContacto = $this->conexion->consultaSimple($query);
		}

		public function editarContacto($id_contacto, $nombreContacto, $emailContacto, $telContacto, $estadoContacto, $cargoContacto, $locaciones){
			if ($estadoContacto == 'Activo') {
				$estado = 1;
			}else{
				$estado = 0;
			}
			$query="UPDATE contactos_clientes SET nombre_completo = '$nombreContacto', email = '$emailContacto', telefono='$telContacto', id_cargo = $cargoContacto, activo = $estado, id_direccion = $locaciones
				WHERE id = $id_contacto";
			$updateContacto = $this->conexion->consultaSimple($query);
		}

		public function deleteContacto($id_contacto){
			$this->id_contacto = $id_contacto;

			/*ELIMINO CONTACTO*/

			$sql = "DELETE FROM contactos_clientes WHERE id = $this->id_contacto";
			$deleteContacto = $this->conexion->consultaSimple($sql);
		}

		public function traerDomicilios($id_cliente){
			$this->id_cliente = $id_cliente;

			$query = "SELECT dl.id as id_direccion, dl.direccion, dl.id_provincia, provincia, td.tipo tipo_direccion, td.id as id_tipo_dir, IF(activa = 1,'Activo','Inactivo') AS activa, dl.id_centro_costos, cc.nombre AS centro_costo, dl.id_almacen, a.almacen
			FROM direcciones_clientes dl 
        join tipos_direccion td ON(dl.id_tipo_direccion = td.id) 
        join provincias pvcias ON(dl.id_provincia = pvcias.id)
        LEFT JOIN centro_costos cc ON dl.id_centro_costos=cc.id
        LEFT JOIN almacenes a ON dl.id_almacen=a.id
			WHERE id_cliente = $this->id_cliente ORDER BY dl.id";
      //echo $query;
			$getDirecciones = $this->conexion->consultaRetorno($query);

			$direcciones = array(); //creamos un array
			
			if($getDirecciones){
				while ($row = $getDirecciones->fetch_array()) {
          $direcciones[] = array(
            'id_direccion'=> $row['id_direccion'],
            'direccion'=>$row['direccion'],
            'id_provincia'=>$row['id_provincia'],
            'provincia'=>$row['provincia'],
            'tipo_direccion'=>$row['tipo_direccion'],
            'id_tipo_dir'=>$row['id_tipo_dir'],
            'activo' =>$row['activa'],
            'id_centro_costos'=>$row['id_centro_costos'],
            'centro_costo'=>$row['centro_costo'],
            'id_almacen'=>$row['id_almacen'],
            'almacen' =>$row['almacen'],
          );
        }
			}

      return json_encode($direcciones);

		}

		public function addDomicilios($id_cliente, $direccion, $tipoDireccion, $provincia, $estadoDomicilio, $centro_costos_ubicacion, $almacen_ubicacion){
			if ($estadoDomicilio == 'Activo') {
				$estado = 1;
			}else{
				$estado = 0;
			}

			$query = "INSERT INTO direcciones_clientes (id_cliente, direccion, id_provincia, id_tipo_direccion, activa, id_centro_costos, id_almacen) VALUES ($id_cliente, '$direccion',$provincia, $tipoDireccion, $estado, $centro_costos_ubicacion, $almacen_ubicacion)";
			$addDomicilio = $this->conexion->consultaSimple($query);
		}

		public function updateDomicilio($id_domicilio, $direccion, $tipoDireccion, $provincia, $estadoDomicilio, $centro_costos_ubicacion, $almacen_ubicacion){
			$this->id_domicilio = $id_domicilio;
			if ($estadoDomicilio == 'Activo') {
				$estado = 1;
			}else{
				$estado = 0;
			}

			$query = "UPDATE direcciones_clientes SET direccion = '$direccion', id_provincia = $provincia, id_tipo_direccion = $tipoDireccion, activa = $estado , id_centro_costos = $centro_costos_ubicacion, id_almacen = $almacen_ubicacion WHERE id=$this->id_domicilio";
			$updateDomicilio = $this->conexion->consultaSimple($query);
      $mensajeError=$this->conexion->conectar->error;
      //echo $mensajeError;
      if($mensajeError!=""){
        echo $mensajeError."<br><br>".$query."<br><br>";
      }
		}

		public function deleteDomicilio($id_domicilio){
			$this->id_domicilio = $id_domicilio;

			/*ELIMINO CONTACTO*/

			$sql = "DELETE FROM direcciones_clientes WHERE id = $this->id_domicilio";
			$deleteDomicilio = $this->conexion->consultaSimple($sql);
		}
    
}	

	if (isset($_POST['accion'])) {
		$clientes = new Clientes();

    $filtros=[];
    if(isset($id_cliente)) $filtros["id_cliente"]=$id_cliente;
    if(isset($id_ubicacion)) $filtros["id_ubicacion"]=$id_ubicacion;

		switch ($_POST['accion']) {
			case 'traerTodosClientes':// EX traerClientes
				$clientes->traerTodosClientes();
				break;
      case 'traerClientes':// EX traerClientes
        echo $clientes->traerClientes($id_empresa);
        break;
			case 'traerClienteUpdate':
					$id_cliente = $_POST['id_cliente'];
					$clientes->traerClienteUpdate($id_cliente);
				break;
			case 'traerContactos':
					//$id_cliente = $_POST['id_cliente'];
					echo $clientes->traerContactos($filtros);
				break;
			case 'addContacto':
					$id_cliente = $_POST['id_cliente'];
					$nombreContacto= $_POST['nombreContacto'];
					$emailContacto= $_POST['emailContacto'];
					$telContacto= $_POST['telContacto'];
					$estadoContacto= $_POST['estadoContacto'];
					$cargoContacto= $_POST['cargoContacto'];
					$locaciones_contacto = $_POST['locaciones'];
					$clientes->agregarContacto($id_cliente, $nombreContacto, $emailContacto, $telContacto, $estadoContacto, $cargoContacto, $locaciones_contacto);
				break;
			case 'editarContacto':
					$id_contacto = $_POST['id_contacto'];
					$nombreContacto= $_POST['nombreContacto'];
					$emailContacto= $_POST['emailContacto'];
					$telContacto= $_POST['telContacto'];
					$estadoContacto= $_POST['estadoContacto'];
					$cargoContacto= $_POST['cargoContacto'];
					$locaciones = $_POST['locaciones'];
					$clientes->editarContacto($id_contacto, $nombreContacto, $emailContacto, $telContacto, $estadoContacto, $cargoContacto, $locaciones);
				break;
			case 'updateCliente':
					$id_cliente = $_POST['id_cliente'];
					$alias= $_POST['alias'];
					$razon_social = $_POST['razon_social'];
					$cuit = $_POST['cuit'];
					$tel1 = $_POST['tel1'];
					$tel2 = $_POST['tel2'];
					$fax = $_POST['fax'];
					$email= $_POST['email'];
					$direccionCli = $_POST['direccionCliente'];
					$localidad = $_POST['localidad'];
					$provinciaCli = $_POST['provinciaCli'];
					$estado = $_POST['estado'];
					$id_tipo_iva = $_POST['condicion_iva'];
					$clientes->clienteUpdate($id_cliente, $alias, $razon_social, $cuit, $tel1, $tel2, $fax, $email, $direccionCli, $localidad, $provinciaCli, $estado, $id_tipo_iva);
				break;
			case 'nuevoCliente':
					$alias= $_POST['alias'];
					$razon_social = $_POST['razon_social'];
					$cuit = $_POST['cuit'];
					$tel1 = $_POST['tel1'];
					$tel2 = $_POST['tel2'];
					$fax = $_POST['fax'];
					$email= $_POST['email'];
					$direccionCli = $_POST['direccionCliente'];
					$localidad = $_POST['localidad'];
					$provinciaCli = $_POST['provinciaCli'];
					$estado = $_POST['estado'];
					$id_tipo_iva = $_POST['condicion_iva'];
					$id_empresa = $_POST['id_empresa'];
					$clientes->agregarCliente($alias, $razon_social, $cuit, $tel1, $tel2, $fax, $email, $direccionCli, $localidad, $provinciaCli, $estado, $id_tipo_iva, $id_empresa);
				break;
			case 'eliminarCliente':
					$id_cliente = $_POST['cliente_id'];
					$clientes->deleteCliente($id_cliente);
				break;
			case 'eliminarContacto':
					$id_contacto = $_POST['id_contacto'];
					$clientes->deleteContacto($id_contacto);
				break;
			case 'traerDirecciones':
					$id_cliente = $_POST['id_cliente'];
					echo $clientes->traerDomicilios($id_cliente);
				break;
			case 'addDomicilio':
					$id_cliente =$_POST['id_cliente'];
					$direccion =$_POST['direccion'];
					$tipoDireccion =$_POST['tipoDireccion'];
					$provincia =$_POST['provincia'];
					$estadoDomicilio =$_POST['estadoDomicilio'];
          $centro_costos_ubicacion =$_POST['centro_costos_ubicacion'];
          $almacen_ubicacion =$_POST['almacen_ubicacion'];
					$clientes->addDomicilios($id_cliente, $direccion, $tipoDireccion, $provincia, $estadoDomicilio, $centro_costos_ubicacion, $almacen_ubicacion);
				break;
			case 'editarDomicilio':
					$id_domicilio = $_POST['id_domicilio'];
					$direccion = $_POST['direccion'];
					$tipoDireccion = $_POST['tipoDireccion'];
					$provincia = $_POST['provincia'];
					$estadoDomicilio = $_POST['estadoDomicilio'];
          $centro_costos_ubicacion =$_POST['centro_costos_ubicacion'];
          $almacen_ubicacion =$_POST['almacen_ubicacion'];
					$clientes->updateDomicilio($id_domicilio, $direccion, $tipoDireccion, $provincia, $estadoDomicilio, $centro_costos_ubicacion, $almacen_ubicacion);
				break;
			case 'eliminarDomicilio':
					$id_domicilio = $_POST['id_domicilio'];
					$clientes->deleteDomicilio($id_domicilio);
				break;
			case 'traerDatosInicialesClientes':
				$clientes->traerDatosIniciales();
				break;
		}
	}else{
		/*if (isset($_GET['accion']) and $_GET['accion']=="traerClientes") {
			$clientes = new Clientes();
			$id_empresa = $_GET['id_empresa'];
			echo $clientes->traerClientes($id_empresa);
		}*/
    /*var_dump($accion);
    var_dump($id_empresa);*/
    
    if (isset($accion) and $accion=="traerClientes") {
			$clientes = new Clientes();
			//$id_empresa = $_GET['id_empresa'];
			echo $clientes->traerClientes($id_empresa);
		}
	}
?>