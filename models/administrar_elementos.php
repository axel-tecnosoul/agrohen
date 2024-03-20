<?php
require_once('../../conexion.php');
require_once('administrar_clientes.php');
require_once('administrar_rubros.php');
require_once('administrar_subrubros.php');
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    // session isn't started
    session_start();
}
//var_dump($_SESSION);
extract($_REQUEST);
class Elemento{

		private $id_elemento;

		public function __construct(){
			$this->conexion = new Conexion();
      $this->id_empresa = $_SESSION["rowUsers"]["id_empresa"];
			date_default_timezone_set("America/Buenos_Aires");
		}

		public function traerDatosInicialesElementos(){
      $datosIniciales = array();
      $tecnicos = array();
      $direcciones_cliente = array();
			//$totalizadores = array();

      $cliente = new Clientes();
      $listaClientes=$cliente->traerClientes($this->id_empresa);
      $listaClientes=json_decode($listaClientes,true);
      //var_dump($listaClientes);

      $rubro = new Rubros();
      $listaRubros=$rubro->traerRubros();
      $listaRubros=json_decode($listaRubros,true);

      /*DIRECCIONES CLIENTES*/
			$query = "SELECT id as id_direccion_cliente, direccion FROM direcciones_clientes";
			$getDatos = $this->conexion->consultaRetorno($query);
      /*CARGO ARRAY TECNICOS*/
			while ($row= $getDatos->fetch_array()) {
				$direcciones_cliente[] = array(
          'id_direccion_cliente' => $row['id_direccion_cliente'],
          'direccion' =>$row['direccion']
        );
			}
      //var_dump($direcciones_cliente);

			//$datosIniciales["proveedores"] = $proveedores;
      //$datosIniciales["tecnicos"] = $tecnicos;
      $datosIniciales["direcciones_cliente"] = $direcciones_cliente;
      $datosIniciales["clientes"] = $listaClientes;
			$datosIniciales["rubros"] = $listaRubros;

			echo json_encode($datosIniciales);
		}

		public function traerElementos($filtros=0){

      $filtro_elemento="";
      $filtro_cliente="";
      $filtro_ubicacion="";
      if($filtros!=0){
          //var_dump($filtros);
          if(isset($filtros["id_elemento"]) and $filtros["id_elemento"]!=""){
              $filtro_elemento=" AND ac.id = ".$filtros["id_elemento"];
          }
          if(isset($filtros["id_cliente"]) and $filtros["id_cliente"]!=""){
              $filtro_cliente=" AND c.id = ".$filtros["id_cliente"];
          }
          if(isset($filtros["id_ubicacion"]) and $filtros["id_ubicacion"]!=""){
              $filtro_ubicacion=" AND dc.id = ".$filtros["id_ubicacion"];
          }
      }
      
			$arrayElementos = array();

			$queryGet = "SELECT ac.id AS id_elemento,ac.id_direccion_cliente,ac.descripcion,ac.ubicacion,ac.fecha_alta,ac.id_tecnico_ultima_revision,ac.fecha_hora_ultima_revision,ac.activo,IF(ac.activo=1,'Activo','Inactivo') AS estado,c.id AS id_cliente,c.razon_social,dc.direccion,u.email AS tecnico_ultima_revision,id_subrubro, datos_adicionales, hash, imagen
      FROM activos_cliente ac 
      INNER JOIN direcciones_clientes dc ON ac.id_direccion_cliente=dc.id 
      INNER JOIN clientes c ON dc.id_cliente=c.id
      LEFT JOIN usuarios u ON u.id=ac.id_tecnico_ultima_revision
      WHERE c.id_empresa = $this->id_empresa $filtro_elemento $filtro_cliente $filtro_ubicacion";
      //var_dump($queryGet);
			$getDatos = $this->conexion->consultaRetorno($queryGet);

			while ($row = $getDatos->fetch_array()) {
        /*$id_elemento = $row['id_elemento'];
				$id_direccion_cliente = $row['id_direccion_cliente'];
        $id_cliente= $row["id_cliente"];*/

        $fecha_alta = $row['fecha_alta'];
        $fecha_hora_ultima_revision = $row['fecha_hora_ultima_revision'];

        $subrubro = new Subrubros();
        $subrubro=$subrubro->traerSubrubros($row['id_subrubro']);
        $subrubro=json_decode($subrubro,true);
        $subrubro=$subrubro[0];

        $arrayElementos[] = array(
          'id_elemento'=>$row['id_elemento'],
          'id_direccion_cliente'=>$row['id_direccion_cliente'],
          'id_cliente'=>$row['id_cliente'],
          'cliente'=>$row['razon_social'],
          'descripcion' => $row['descripcion'],
          'ubicacion' => $row['ubicacion'],
          'direccion' => $row['direccion'],
          'id_tecnico_ultima_revision' => $row['id_tecnico_ultima_revision'],
          'tecnico_ultima_revision' => $row['tecnico_ultima_revision'],
          'activo' => $row['activo'],
          'estado'=>$row['estado'],
          'subrubro'=>$subrubro,
          'datos_adicionales'=>$row['datos_adicionales'],
          'codigo'=>$row['hash'],
          'imagen'=>$row["imagen"],
          'fecha_alta'=>$fecha_alta,
          'fecha_alta_mostrar'=>date("d/m/Y", strtotime($fecha_alta)),
          'fecha_hora_ultima_revision'=>$fecha_hora_ultima_revision,
          'fecha_hora_ultima_revision_mostrar'=>date("d/m/Y H:i", strtotime($fecha_hora_ultima_revision))."hs",
        );
			}
			//echo json_encode($arrayElementos);
      return json_encode($arrayElementos);
		}

		public function agregarElemento($id_direccion_cliente, $descripcion, $ubicacion, $id_subrubro, $codigo, $datos_adicionales, $archivo){
			//$fecha_alta = date('Y-m-d H:i:s');
      $activo = 1;

			/*GUARDO EN TABLA EMPRESA*/
			$queryInsert = "INSERT INTO activos_cliente (id_direccion_cliente, descripcion, ubicacion, hash, activo, id_subrubro, datos_adicionales) VALUES('$id_direccion_cliente', '$descripcion', '$ubicacion', '$codigo', '$activo', '$id_subrubro', '$datos_adicionales')";
      //echo $queryInsert;
			$insertar= $this->conexion->consultaSimple($queryInsert);

      $mensajeError=$this->conexion->conectar->error;
      //var_dump($mensajeError);
      echo $mensajeError;
      if($mensajeError!=""){
        echo "<br><br>".$queryInsert;
      }
      $id_elemento=$this->conexion->conectar->insert_id;

      /*GUARDO IMAGEN EN EL DIRECTORIO*/
			if($archivo !=""){
				$nombreImagen = $archivo['name'];
				$directorio = "../views/elementos/";
				$nombreFinalArchivo = $nombreImagen;
				move_uploaded_file($archivo['tmp_name'], $directorio.$id_elemento."_".$nombreFinalArchivo);
				//$ruta_completa_imagen = $directorio.$nombreFinalArchivo;
				$archivo = $id_elemento."_".$nombreFinalArchivo;
			}

			//ACTUALIZO NOMBRE IMAGEN DEL ITEM
			$queryUpdateLogoName = "UPDATE activos_cliente SET imagen='$archivo' WHERE id = $id_elemento";
			$updateLogoName = $this->conexion->consultaSimple($queryUpdateLogoName);

		}

    /*public function traerVechiculoUpdate($id_elemento){
			$this->id_elemento = $id_elemento;

			$queryGetVehiculoUpdate = "SELECT id, patente, fecha_adquirido, fecha_baja, id_tecnico_asignado, comentarios, proximo_service_general, km_adquirido, proximo_vencimiento_vtv, km_actuales
								FROM vehiculos
								WHERE id = $this->id_elemento";
			$getVehiculoUpdate= $this->conexion->consultaRetorno($queryGetVehiculoUpdate);

			$arrayDatosVehiculos= array();
			$arrayVehiculos = array();

        $rowVehiculo = $getVehiculoUpdate->fetch_assoc();

				$patente = $rowVehiculo['patente'];
				//$fecha_alta = $rowVehiculo['fecha_alta'];
				$fecha_adquirido = $rowVehiculo['fecha_adquirido'];
				$fecha_baja = $rowVehiculo['fecha_baja'];
				$tecnico = $rowVehiculo['id_tecnico_asignado'];
				$comentarios = $rowVehiculo['comentarios'];
				$proximo_service_general = $rowVehiculo['proximo_service_general'];
				$km_adquirido = $rowVehiculo['km_adquirido'];
				$proximo_vencimiento_vtv = $rowVehiculo['proximo_vencimiento_vtv'];
				$km_actuales = $rowVehiculo['km_actuales'];
				
				$arrayVehiculos = array('patente'=>$patente, 'fecha_adquirido'=>$fecha_adquirido, 'fecha_baja'=>$fecha_baja, 'tecnico'=>$tecnico, 'comentarios'=>$comentarios, 'proximo_service_general'=>$proximo_service_general, 'km_adquirido'=>$km_adquirido, 'proximo_vencimiento_vtv'=>$proximo_vencimiento_vtv, 'km_actuales'=>$km_actuales);

			$arrayDatosVehiculos['datos_vehiculo'] = $arrayVehiculos;
      return json_encode($arrayDatosVehiculos);
		}*/

    public function updateElemento($id_elemento, $id_direccion_cliente, $descripcion, $ubicacion, $id_subrubro, $codigo, $datos_adicionales, $archivo){

			$this->id_elemento=$id_elemento;

			//Actualizo datos del vehiculo
      $queryUpdate = "UPDATE activos_cliente set id_direccion_cliente = '$id_direccion_cliente', descripcion = '$descripcion', ubicacion = '$ubicacion' , id_subrubro = '$id_subrubro', hash = '$codigo', datos_adicionales = '$datos_adicionales'
      WHERE id = $this->id_elemento";
      //echo $queryUpdate;
			$update = $this->conexion->consultaSimple($queryUpdate);
      //var_dump("afectados: ".$this->conexion->conectar->affected_rows);
      $mensajeError=$this->conexion->conectar->error;
      //var_dump($mensajeError);
      echo $mensajeError;
      /*$error=0;
      if($mensajeError!=""){
        $error=1;
      }*/

      if($archivo !=""){

				$directorio = "../views/elementos/";
				$queryGetLogo = "SELECT imagen FROM activos_cliente WHERE id= $this->id_elemento";
				$getLogo = $this->conexion->consultaRetorno($queryGetLogo);
				$nombreRow = $getLogo->fetch_assoc();
				$nombreAnterior = $nombreRow['imagen'];

        $nombre_completo =  $this->id_elemento."_".$archivo['name'];
				if($nombreAnterior != ""){
					//Elimino archivo existente en directorio y agrego el nuevo
					unlink($directorio.$nombreAnterior);
				}

        $queryUpdateLogo = "UPDATE activos_cliente SET imagen = '$nombre_completo' WHERE id = $this->id_elemento";
				$updateLogo = $this->conexion->consultaSimple($queryUpdateLogo);
        
        move_uploaded_file($archivo['tmp_name'], $directorio.$nombre_completo);

			}

		}

    public function deleteElemento($id_elemento){
			$this->id_elemento = $id_elemento;

			/*Eliminamos registros de la base de datos*/

			/*Tabla activos_cliente*/
			$queryDelete = "DELETE FROM activos_cliente WHERE id=$this->id_elemento";
			$delte = $this->conexion->consultaSimple($queryDelete);

		}

    public function eliminarImagen($id_elemento){
			$this->id_elemento = $id_elemento;

      $directorio = "../views/elementos/";
      $queryGetLogo = "SELECT imagen FROM activos_cliente WHERE id= $this->id_elemento";
      $getLogo = $this->conexion->consultaRetorno($queryGetLogo);
      $nombreRow = $getLogo->fetch_assoc();
      $nombreAnterior = $nombreRow['imagen'];

      //Elimino archivo existente en directorio y agrego el nuevo
      unlink($directorio.$nombreAnterior);

			/*Eliminamos registros de la base de datos*/
			$queryDelete = "UPDATE activos_cliente SET imagen = '' WHERE id=$this->id_elemento";
			$delte = $this->conexion->consultaSimple($queryDelete);

		}

}

	if (isset($_POST['accion'])) {
		$elemento = new Elemento();
    
    $filtros=[];
    if(isset($id_elemento)) $filtros["id_elemento"]=$id_elemento;
    if(isset($id_cliente)) $filtros["id_cliente"]=$id_cliente;
    if(isset($id_ubicacion)) $filtros["id_ubicacion"]=$id_ubicacion;
		
    switch ($_POST['accion']) {
			case 'traerDatosInicialesElementos':
			  	$elemento->traerDatosInicialesElementos();
			break;
			case 'addElemento':
          $archivo = "";
          if(isset($_FILES['file'])) {
            $archivo = $_FILES['file'];
          }
					$elemento->agregarElemento($id_direccion_cliente, $descripcion, $ubicacion, $id_subrubro, $codigo, $datos_adicionales, $archivo);
			break;
      case 'traerElementoUpdate':
      case 'trerDetalleElemento':
      case 'traerElementosCliente':
					//$id_elemento = $_POST['id_elemento'];
					//echo $elemento->traerVechiculoUpdate($id_elemento);
          echo $elemento->traerElementos($filtros);
			break;
      case 'updateElemento':
        $archivo = "";
        if(isset($_FILES['file'])) {
          $archivo = $_FILES['file'];
        }
        $elemento->updateElemento($id_elemento, $id_direccion_cliente, $descripcion, $ubicacion, $id_subrubro, $codigo, $datos_adicionales, $archivo);
      break;
      case 'eliminarElemento':
        //$id_elemento = $_POST['id_elemento'];
        $elemento->deleteElemento($id_elemento);
      break;
      case 'eliminarImagen':
        $elemento->eliminarImagen($id_elemento);
    break;
		}
	}else{
		if (isset($_GET['accion'])) {
			$elemento = new Elemento();

			switch ($_GET['accion']) {
				case 'traerElementos':
					echo $elementos=$elemento->traerElementos();
          /*$elementos=json_decode($elementos,true);

          //var_dump($elementos);
          
          $listarElementos=[];
          foreach ($elementos as $elemento) {
            $listarElementos[]=[
              "id_elemento"               =>$elemento["id_elemento"],
              "direccion"                 =>$elemento["descripcion"],
              "descripcion"               =>$elemento["descripcion"],
              "ubicacion"                 =>$elemento["ubicacion"],
              "id_tecnico_ultima_revision"=>$elemento["id_tecnico_ultima_revision"],
              "tecnico_ultima_revision"   =>$elemento["id_tecnico_ultima_revision"],
              "fecha_hora_ultima_revision"=>$elemento["fecha_hora_ultima_revision"],
              "estado"                    =>$elemento["estado"],
            ];
          }
          echo json_encode($listarElementos);*/
				break;
			}
		}
	}
?>