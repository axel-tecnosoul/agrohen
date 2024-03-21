<?php
	//session_start();
	require_once('./conexion.php');
  if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    // session isn't started
    session_start();
  }
  extract($_REQUEST);
	class Vehiculo{

		private $id_vehiculo;

		public function __construct(){
			$this->conexion = new Conexion();
      $this->id_empresa = $_SESSION["rowUsers"]["id_empresa"];
			date_default_timezone_set("America/Buenos_Aires");
		}

		public function traerDatosIniciales(){
      $datosIniciales = array();
      $tecnicos = array();
      $marcas = array();
			//$totalizadores = array();

      /*TECNICOS*/
			$queryTecnicos = "SELECT id as id_tecnico, nombre_completo FROM tecnicos WHERE activo = 1 and id_empresa = $this->id_empresa";
			$getTecnicos = $this->conexion->consultaRetorno($queryTecnicos);
      /*CARGO ARRAY TECNICOS*/
			while ($rowTecnico= $getTecnicos->fetch_array()) {
				$id_tecnico = $rowTecnico['id_tecnico'];
				$nombre_completo = $rowTecnico['nombre_completo'];
				$tecnicos[] = array('id_tecnico' => $id_tecnico, 'nombre_completo' =>$nombre_completo);
			}

      /*MARCAS*/
			$queryMarcas = "SELECT id as id_marca, marca FROM marcas_vehiculos";
			$getMarcas = $this->conexion->consultaRetorno($queryMarcas);
      /*CARGO ARRAY TECNICOS*/
			while ($rowMarca= $getMarcas->fetch_array()) {
				$id_marca = $rowMarca['id_marca'];
				$marca = $rowMarca['marca'];
				$marcas[] = array('id_marca' => $id_marca, 'marca' =>$marca);
			}

      /*TOTALIZADORES*/
			/*$queryGetTotalizadores = "SELECT sum(cantidad_disponible) as totItem, 
									sum(cantidad_disponible) as cantDisp, 
									sum(cantidad_reservada) as cantReserv,
									sum(cantidad_disponible*precio_unitario) as valoracion
									FROM stock";
			$getTotalizadores = $this->conexion->consultaRetorno($queryGetTotalizadores);*/
			/*CARGO ARRAY TOTALIZADORES*/
			/*while ($rowTotalizador = $getTotalizadores->fetch_array()) {
				$totItem = $rowTotalizador['totItem'];
				$cantDisp = $rowTotalizador['cantDisp'];
				$cantReserv = $rowTotalizador['cantReserv'];
				$valoracion = "$".number_format($rowTotalizador['valoracion'],2,',','.');
				$totalizadores[]=array("totItem"=>$totItem, "cantDisp"=>$cantDisp, "cantReserv"=>$cantReserv, "valoracion"=>$valoracion);
			}*/

			//$datosIniciales["proveedores"] = $proveedores;
      $datosIniciales["tecnicos"] = $tecnicos;
      $datosIniciales["marcas"] = $marcas;
			//$datosIniciales["totalizadores"] = $totalizadores;

			echo json_encode($datosIniciales);
		}

		public function traerVehiculos($id_vehiculo=0){

      $filtro_vehiculo="";
      if($id_vehiculo!=0){
        $filtro_vehiculo=" AND v.id = $id_vehiculo";
      }
			
			$arrayVehiculos = array();

			$queryGetVehiculos = "SELECT v.id AS id_vehiculo,patente,id_marca,mv.marca,modelo,anio,codigo_motor,codigo_chasis,nro_cedula_verde,v.fecha_alta,IF(v.activo=1,'Activo','Inactivo') AS estado,fecha_adquirido,v.fecha_baja,id_tecnico_asignado,t.nombre_completo AS tecnico,comentarios,proximo_service_general,proximo_vencimiento_vtv,km_adquirido,km_actuales,fecha_ultima_actualizacion,u.email AS usuario_ultima_actualizacion, numero_movil, vencimiento_cedula_verde, vencimiento_seguro, vencimiento_gnc
      FROM vehiculos v 
      LEFT JOIN marcas_vehiculos mv ON v.id_marca=mv.id
      INNER JOIN tecnicos t ON v.id_tecnico_asignado=t.id
      INNER JOIN usuarios u ON v.id_usuario_ultima_actualizacion=u.id
      WHERE v.id_empresa = $this->id_empresa $filtro_vehiculo";
			$getVehiculos = $this->conexion->consultaRetorno($queryGetVehiculos);

			while ($row = $getVehiculos->fetch_array()) {
				$patente = strtoupper($row['patente']);
        $marca = strtoupper($row['marca']);
        $modelo = strtoupper($row['modelo']);
        $anio = strtoupper($row['anio']);
        $vehiculo = $marca." ".$modelo." ".$anio. "(".$patente.")";

        $km_adquirido = $row['km_adquirido'];
        $km_adquirido_mostrar = number_format($km_adquirido,0,",",".");
				$km_actuales = $row['km_actuales'];
        $km_actuales_mostrar = number_format($km_actuales,0,",",".");

        $fecha_alta = $row['fecha_alta'];
        $fecha_alta_mostrar = date("d/m/Y", strtotime($fecha_alta));
				
        $fecha_adquirido = $row['fecha_adquirido'];
        $fecha_adquirido_mostrar = date("d/m/Y", strtotime($fecha_adquirido));
        
        $fecha_baja = $row['fecha_baja'];
        $fecha_baja = ($fecha_baja == 0) ? "" : $fecha_baja;
        $fecha_baja_mostrar = ($fecha_baja == "") ? "" : date("d/m/Y", strtotime($fecha_baja));

        $proximo_service_general = $row['proximo_service_general'];
        $proximo_service_general = ($proximo_service_general == 0) ? "" : $proximo_service_general;
        $proximo_service_general_mostrar = ($proximo_service_general == "") ? "" : date("d/m/Y", strtotime($proximo_service_general));

        $proximo_vencimiento_vtv = $row['proximo_vencimiento_vtv'];
        $proximo_vencimiento_vtv = ($proximo_vencimiento_vtv == 0) ? "" : $proximo_vencimiento_vtv;
        $proximo_vencimiento_vtv_mostrar = ($proximo_vencimiento_vtv == "") ? "" : date("d/m/Y", strtotime($proximo_vencimiento_vtv));

        $vencimiento_cedula_verde = $row['vencimiento_cedula_verde'];
        $vencimiento_cedula_verde = ($vencimiento_cedula_verde == 0) ? "" : $vencimiento_cedula_verde;
        $vencimiento_cedula_verde_mostrar = ($vencimiento_cedula_verde == "") ? "" : date("d/m/Y", strtotime($vencimiento_cedula_verde));
        
        $vencimiento_seguro = $row['vencimiento_seguro'];
        $vencimiento_seguro = ($vencimiento_seguro == 0) ? "" : $vencimiento_seguro;
        $vencimiento_seguro_mostrar = ($vencimiento_seguro == "") ? "" : date("d/m/Y", strtotime($vencimiento_seguro));
        
        $vencimiento_gnc = $row['vencimiento_gnc'];
        $vencimiento_gnc = ($vencimiento_gnc == 0) ? "" : $vencimiento_gnc;
        $vencimiento_gnc_mostrar = ($vencimiento_gnc == "") ? "" : date("d/m/Y", strtotime($vencimiento_gnc));
        
        $fecha_ultima_actualizacion = date("d/m/Y H:m:s", strtotime($row['fecha_ultima_actualizacion']))."hs";
				$usuario_ultima_actualizacion = $row['usuario_ultima_actualizacion'];

				$arrayVehiculos[] = array(
          'id_vehiculo'                     =>$row['id_vehiculo'],
          'numero_movil'                    =>$row['numero_movil'],
          'vehiculo'                        =>$vehiculo,
          'patente'                         =>$patente,
          'id_marca'                        =>$row['id_marca'],
          'marca'                           =>$marca,
          'modelo'                          =>$modelo,
          'anio'                            =>$anio,
          'codigo_motor'                    =>strtoupper($row['codigo_motor']),
          'codigo_chasis'                   =>strtoupper($row['codigo_chasis']),
          'nro_cedula_verde'                =>strtoupper($row['nro_cedula_verde']),
          'fecha_alta'                      =>$fecha_alta_mostrar,
          'estado'                          =>$row['estado'],
          'fecha_adquirido_mostrar'         =>$fecha_adquirido_mostrar,
          'fecha_adquirido'                 =>$fecha_adquirido,
          'fecha_baja_mostrar'              =>$fecha_baja_mostrar,
          'fecha_baja'                      =>$fecha_baja,
          'id_tecnico_asignado'             =>$row['id_tecnico_asignado'],
          'tecnico'                         =>$row['tecnico'],
          'comentarios'                     =>$row['comentarios'],
          'proximo_service_general_mostrar' =>$proximo_service_general_mostrar,
          'proximo_service_general'         =>$proximo_service_general,
          'proximo_vencimiento_vtv'         =>$proximo_vencimiento_vtv,
          'proximo_vencimiento_vtv_mostrar' =>$proximo_vencimiento_vtv_mostrar,
          'km_adquirido_mostrar'            =>$km_adquirido_mostrar,
          'km_adquirido'                    =>$km_adquirido,
          'km_actuales_mostrar'             =>$km_actuales_mostrar,
          'km_actuales'                     =>$km_actuales,
          'fecha_ultima_actualizacion'      =>$fecha_ultima_actualizacion,
          'usuario_ultima_actualizacion'    =>$usuario_ultima_actualizacion,
          'vencimiento_cedula_verde'        =>$vencimiento_cedula_verde,
          'vencimiento_cedula_verde_mostrar'=>$vencimiento_cedula_verde_mostrar,
          'vencimiento_seguro'              =>$vencimiento_seguro,
          'vencimiento_seguro_mostrar'      =>$vencimiento_seguro_mostrar,
          'vencimiento_gnc'                 =>$vencimiento_gnc,
          'vencimiento_gnc_mostrar'         =>$vencimiento_gnc_mostrar,
        );
			}
			//echo json_encode($arrayVehiculos);
      return json_encode($arrayVehiculos);
		}

		public function agregarVehiculo($patente, $marca, $modelo, $anio, $codigo_motor, $codigo_chasis, $nro_cedula_verde, $fecha_alta, $fecha_adquirido, $fecha_baja, $tecnico, $comentarios, $proximo_service_general, $km_adquirido, $proximo_vencimiento_vtv, $km_actuales, $numero_movil, $vencimiento_cedula_verde, $vencimiento_seguro, $vencimiento_gnc, $adjuntos, $cantAdjuntos){
			//$fecha_alta = date('Y-m-d H:i:s');
      $activo = 1;
      $id_usuario_ultima_actualizacion=$_SESSION["rowUsers"]["id_usuario"];

			/*GUARDO EN TABLA EMPRESA*/
			$queryInsertVehiculo = "INSERT INTO vehiculos (id_empresa, patente, id_marca, modelo, anio, codigo_motor, codigo_chasis, nro_cedula_verde, fecha_alta, activo, fecha_adquirido, fecha_baja, id_tecnico_asignado, comentarios, proximo_service_general, km_adquirido, proximo_vencimiento_vtv, km_actuales, fecha_ultima_actualizacion, id_usuario_ultima_actualizacion, numero_movil, vencimiento_cedula_verde, vencimiento_seguro, vencimiento_gnc) VALUES('$this->id_empresa', '$patente', '$marca', '$modelo', '$anio', '$codigo_motor', '$codigo_chasis', '$nro_cedula_verde', '$fecha_alta', '$activo', '$fecha_adquirido', '$fecha_baja', '$tecnico', '$comentarios','$proximo_service_general','$km_adquirido','$proximo_vencimiento_vtv','$km_actuales',NOW(),'$id_usuario_ultima_actualizacion','$numero_movil','$vencimiento_cedula_verde','$vencimiento_seguro','$vencimiento_gnc')";
      //echo $queryInsertVehiculo;
			$insertarVehiculo= $this->conexion->consultaSimple($queryInsertVehiculo);
      $id_vehiculo=$this->conexion->conectar->insert_id;

      $this->agregarDocumentosVehiculo($id_vehiculo, $adjuntos, $cantAdjuntos);

		}

    public function agregarDocumentosVehiculo($id_vehiculo, $adjuntos, $cantAdjuntos){
      $id_usuario_alta=$_SESSION["rowUsers"]["id_usuario"];
      //SI VIENEN ADJUNTOS LOS GUARDO.
      $comentarios="";
      if ($adjuntos > 0) {
        for ($i=0; $i < $cantAdjuntos; $i++) { 
          $indice = "file".$i;
          $nombreADJ = $_FILES[$indice]['name'];
  
          //INSERTO DATOS EN LA TABLA ADJUNTOS ORDEN_COMPRA
          $queryInsertAdjuntos = "INSERT INTO documentos_vehiculo (id_vehiculo, documento, comentario, fecha_hora_alta, id_usuario_alta)VALUES($id_vehiculo, '$nombreADJ', '$comentarios', NOW(),'$id_usuario_alta')";
          //var_dump($queryInsertAdjuntos);
          $insertAdjuntos = $this->conexion->consultaSimple($queryInsertAdjuntos);
  
          $mensajeError=$this->conexion->conectar->error;
      
          echo $mensajeError;
          if($mensajeError!=""){
            echo "<br><br>".$queryInsert;
          }
          
          //INGRESO ARCHIVOS EN EL DIRECTORIO
          $directorio = "../views/documentos_vehiculo/";
          $nombre_archivo_guardado=$directorio."adj_".$id_vehiculo."_".$nombreADJ;
  
          $imagenGuardada=move_uploaded_file($_FILES[$indice]['tmp_name'], $nombre_archivo_guardado);
        }
      }
    }

    /*public function traerVechiculoUpdate($id_vehiculo){
			$this->id_vehiculo = $id_vehiculo;

			$queryGetVehiculoUpdate = "SELECT id, patente, fecha_adquirido, fecha_baja, id_tecnico_asignado, comentarios, proximo_service_general, km_adquirido, proximo_vencimiento_vtv, km_actuales
								FROM vehiculos
								WHERE id = $this->id_vehiculo";
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

    public function updateVehiculo($id_vehiculo, $patente, $marca, $modelo, $anio, $codigo_motor, $codigo_chasis, $nro_cedula_verde, $fecha_adquirido, $fecha_baja, $tecnico, $comentarios, $proximo_service_general, $km_adquirido, $proximo_vencimiento_vtv, $km_actuales, $numero_movil, $vencimiento_cedula_verde, $vencimiento_seguro, $vencimiento_gnc, $adjuntos, $cantAdjuntos){

			$this->id_vehiculo=$id_vehiculo;
      $id_usuario_ultima_actualizacion=$_SESSION["rowUsers"]["id_usuario"];

			//Actualizo datos del vehiculo
      $queryUpdateVehiculo = "UPDATE vehiculos set patente = '$patente', id_marca = '$marca', modelo = '$modelo', anio = '$anio', codigo_motor = '$codigo_motor', codigo_chasis = '$codigo_chasis', nro_cedula_verde = '$nro_cedula_verde', fecha_adquirido = '$fecha_adquirido', fecha_baja = '$fecha_baja', id_tecnico_asignado = '$tecnico', comentarios = '$comentarios', proximo_service_general = '$proximo_service_general', km_adquirido = '$km_adquirido', proximo_vencimiento_vtv = '$proximo_vencimiento_vtv', km_actuales = '$km_actuales', fecha_ultima_actualizacion = NOW(), id_usuario_ultima_actualizacion = '$id_usuario_ultima_actualizacion', numero_movil = '$numero_movil', vencimiento_cedula_verde = '$vencimiento_cedula_verde', vencimiento_seguro = '$vencimiento_seguro', vencimiento_gnc = '$vencimiento_gnc'
      WHERE id = $this->id_vehiculo";
      //echo $queryUpdateVehiculo;
			$updateVehiculo = $this->conexion->consultaSimple($queryUpdateVehiculo);
      //var_dump("afectados: ".$this->conexion->conectar->affected_rows);
      $mensajeError=$this->conexion->conectar->error;
      //var_dump($mensajeError);
      echo $mensajeError;
      /*$error=0;
      if($mensajeError!=""){
        $error=1;
      }*/
      $this->agregarDocumentosVehiculo($id_vehiculo, $adjuntos, $cantAdjuntos);

		}

    public function traerDetalleVehiculo($id_vehiculo){
			$this->id_vehiculo = $id_vehiculo;

      $arrayDatosVehiculos=$this->traerVehiculos($id_vehiculo);
      $arrayDatosVehiculos=json_decode($arrayDatosVehiculos,true);

      $arrayDocumentosVehiculos=$this->traerDocumentosVehiculo($id_vehiculo);
      $arrayDocumentosVehiculos=json_decode($arrayDocumentosVehiculos,true);

      $filtros["id_vehiculo"]=$id_vehiculo;
      $tareasMantenimientoVehiculos=$this->traerTareasMantenimientoVehiculos($filtros);
      $tareasMantenimientoVehiculos=json_decode($tareasMantenimientoVehiculos,true);

      /*
			//TRAIGO DATOS DE TAREAS DE MANTENIMINETO
			$queryGetVehiculoUpdate = "SELECT u.email AS usuario,fecha_hora,detalle,costo_estimado,IF(realizado=1,'Si','No') AS realizado, comentarios FROM mantenimientos_vehiculares mv INNER JOIN usuarios u ON mv.id_usuario=u.id
      WHERE id_vehiculo = $this->id_vehiculo ORDER BY fecha_hora DESC";
      //var_dump($queryGetVehiculoUpdate);
			$getVehiculoUpdate= $this->conexion->consultaRetorno($queryGetVehiculoUpdate);

			//$arrayDetalleVehiculos= array();
			$arrayMantenimientoVehiculo = array();

			//CARGO ARRAY CON DATOS DE LAs TAREAS DE MANTENIMINETO
			while ($rowVehiculo = $getVehiculoUpdate->fetch_assoc()) {
				$usuario = $rowVehiculo['usuario'];
				//$fecha_alta = $rowVehiculo['fecha_alta'];
				$fecha_hora = date("d/m/Y H:i", strtotime($rowVehiculo['fecha_hora']))."hs";
				$detalle = $rowVehiculo['detalle'];
				$costo_estimado = number_format($rowVehiculo['costo_estimado'],0,",",".");
        $realizado = $rowVehiculo['realizado'];
				$comentarios = $rowVehiculo['comentarios'];
				
				$arrayMantenimientoVehiculo[] = array('usuario'=>$usuario, 'fecha_hora'=>$fecha_hora, 'detalle'=>$detalle, 'costo_estimado'=>$costo_estimado, 'realizado'=>$realizado, 'comentarios'=>$comentarios);
			}*/

      //var_dump($arrayDatosVehiculos);
			$arrayDetalleVehiculos=[
        'datos_vehiculo' => $arrayDatosVehiculos[0],
        //'mantenimiento_vehiculo' => $arrayMantenimientoVehiculo,
        'mantenimiento_vehiculo' => $tareasMantenimientoVehiculos,
        'documentos_vehiculo' => $arrayDocumentosVehiculos
      ];
			echo json_encode($arrayDetalleVehiculos);
		}

    public function eliminarDocumento($id_documento, $nombre_adjunto){

      $this->id_documento = $id_documento;
  
      $queryGet = "SELECT id_vehiculo FROM documentos_vehiculo WHERE id = $this->id_documento";
      $getDatos = $this->conexion->consultaRetorno($queryGet);
      $row = $getDatos->fetch_array();
      $id_vehiculo=$row["id_vehiculo"];
  
      $queryDelAdjunto= "DELETE FROM documentos_vehiculo WHERE id = $this->id_documento";
      $delAdjunto = $this->conexion->consultaSimple($queryDelAdjunto);
  
      $directorio = "../views/documentos_vehiculo/";
  
      $rutaCompleta = $directorio."adj_".$id_vehiculo."_".$nombre_adjunto;
      
      unlink($rutaCompleta);
  
    }

    public function traerDocumentosVehiculo($id_vehiculo){

      $arrayDocumentosVehiculo = [];
  
      $queryGet = "SELECT dv.id AS id_documento,dv.documento,dv.fecha_hora_alta,u.email AS usuario
      FROM documentos_vehiculo dv 
        INNER JOIN usuarios u ON dv.id_usuario_alta=u.id 
      WHERE dv.id_vehiculo = $id_vehiculo";
      //var_dump($queryGet);
      $getDatos = $this->conexion->consultaRetorno($queryGet);
  
      while ($row = $getDatos->fetch_array()) {
        $arrayDocumentosVehiculo[] =[
          "id_documento"  =>$row["id_documento"],
          "documento"     =>$row["documento"],
          "fecha_hora_alta"  =>date("d-M-Y H:i",strtotime($row["fecha_hora_alta"]))."hs",
          "usuario"     =>$row["usuario"],
        ];
      }
      //echo json_encode($arrayDocumentosVehiculo);
      return json_encode($arrayDocumentosVehiculo);
    }

    public function deleteVehiculo($id_vehiculo){
			$this->id_vehiculo = $id_vehiculo;

			/*Eliminamos registros de la base de datos*/

			/*Tabla vehiculos*/
			$queryDelVehiculo = "DELETE FROM vehiculos WHERE id=$this->id_vehiculo";
			$delVehiculo = $this->conexion->consultaSimple($queryDelVehiculo);

		}

    public function agregarTareaMantenimiento($id_vehiculo, $detalle, $costo_estimado, $fecha, $hora, $comentarios){

			$this->id_vehiculo=$id_vehiculo;
      $id_usuario=$_SESSION["rowUsers"]["id_usuario"];
      $fecha_hora=$fecha." ".$hora;

      $queryInsertTareaMantenimiento = "INSERT INTO mantenimientos_vehiculares (id_vehiculo, id_usuario, fecha_hora, detalle, costo_estimado, realizado, comentarios) VALUES('$id_vehiculo', '$id_usuario', '$fecha_hora', '$detalle', '$costo_estimado', 0, '$comentarios')";
      echo $queryInsertTareaMantenimiento;
			$insertarTareaMantenimiento= $this->conexion->consultaSimple($queryInsertTareaMantenimiento);

		}

    public function traerTareasMantenimientoVehiculos($filtros=0){

      $filtro_tarea_mantenimiento="";
      $filtro_vehiculo="";
      $filtro_desde="";
      $filtro_hasta="";
      if($filtros!=0){
          //var_dump($filtros);
          if(isset($filtros["id_tarea_mantenimiento"]) and $filtros["id_tarea_mantenimiento"]!=""){
              $filtro_tarea_mantenimiento=" AND mv.id = ".$filtros["id_tarea_mantenimiento"];
          }
          if(isset($filtros["id_vehiculo"]) and $filtros["id_vehiculo"]!=""){
              $filtro_vehiculo=" AND mv.id_vehiculo = ".$filtros["id_vehiculo"];
          }
          if(isset($filtros["desde"]) and $filtros["desde"]!=""){
              $filtro_desde=" AND DATE(fecha_hora) >= '".$filtros["desde"]."'";
          }
          if(isset($filtros["hasta"]) and $filtros["hasta"]!=""){
              $filtro_hasta=" AND DATE(fecha_hora) <= '".$filtros["hasta"]."'";
          }
      }
			
			$tareasMantenimiento = array();

			/*$queryGetTareasMantenimiento = "SELECT manv.id AS id_tarea_mantenimiento,manv.id_vehiculo,patente,marv.marca,v.modelo,v.anio,fecha_hora,DATE(fecha_hora) AS fecha,TIME(fecha_hora) AS hora,detalle,manv.comentarios,manv.realizado,v.id_tecnico_asignado,t.nombre_completo,costo_estimado
      FROM mantenimientos_vehiculares manv 
      INNER JOIN vehiculos v ON manv.id_vehiculo=v.id 
      INNER JOIN marcas_vehiculos marv ON v.id_marca=marv.id
      INNER JOIN tecnicos t ON v.id_tecnico_asignado=t.id
      WHERE 1 $filtro_tarea_mantenimiento $filtro_vehiculo $filtro_desde $filtro_hasta";*/
      $queryGetTareasMantenimiento = "SELECT mv.id AS id_tarea_mantenimiento,mv.id_vehiculo,mv.fecha_hora,DATE(mv.fecha_hora) AS fecha,TIME(mv.fecha_hora) AS hora,mv.detalle,mv.comentarios,mv.realizado,mv.costo_estimado,u.email AS usuario
      FROM mantenimientos_vehiculares mv 
        INNER JOIN usuarios u ON mv.id_usuario=u.id
      WHERE 1 $filtro_tarea_mantenimiento $filtro_vehiculo $filtro_desde $filtro_hasta";
      //echo $queryGetTareasMantenimiento;
			$getTareasMantenimiento = $this->conexion->consultaRetorno($queryGetTareasMantenimiento);

			while ($row = $getTareasMantenimiento->fetch_array()) {
				$id_tarea_mantenimiento=$row['id_tarea_mantenimiento'];
        /*$id_vehiculo=$row['id_vehiculo'];
				$patente=strtoupper($row['patente']);
        $marca=strtoupper($row['marca']);
        $modelo=strtoupper($row['modelo']);
        $anio=strtoupper($row['anio']);*/
        $fecha_hora=$row['fecha_hora'];
        $fecha_hora_mostrar=date("d/m/Y H:i",strtotime($fecha_hora))."hs";
        $fecha=$row['fecha'];
        $fecha_mostrar=date("d/m/Y",strtotime($fecha));
        $costo_estimado=$row['costo_estimado'];
        $costo_estimado_mostrar="$ ".number_format($costo_estimado,"2",",",".");
        $realizado=$row['realizado'];
        $realizado_mostrar=($realizado==1) ? "Si" : "No";
        /*$id_tecnico_asignado =$row['id_tecnico_asignado'];
				$tecnico =$row['nombre_completo'];*/

        $vehiculo=$this->traerVehiculos($row['id_vehiculo']);
        $vehiculo=json_decode($vehiculo,true);
        $vehiculo=$vehiculo[0];
        
        $adjuntosMantenimientoVehiculos=$this->traerAdjuntosMantenimientoVehiculos($id_tarea_mantenimiento);
        $adjuntosMantenimientoVehiculos=json_decode($adjuntosMantenimientoVehiculos,true);

        $tareasMantenimiento[]=[
          "id_tarea_mantenimiento"  =>$id_tarea_mantenimiento,
          /*"vehiculo"                =>$marca." ".$modelo." - ".$patente,
          "patente"                 =>$patente,
          "marca"                   =>$marca,
          "modelo"                  =>$modelo,
          "anio"                    =>$anio,*/
          "vehiculo"                =>$vehiculo,
          "fecha_hora"              =>$fecha_hora,
          "fecha"                   =>$fecha,
          "fecha_mostrar"           =>$fecha_mostrar,
          "hora"                    =>$row['hora'],
          "costo_estimado"          =>$costo_estimado,
          "costo_estimado_mostrar"  =>$costo_estimado_mostrar,
          "realizado"               =>$realizado,
          "realizado_mostrar"       =>$realizado_mostrar,
          "detalle"                 =>$row['detalle'],
          /*"id_tecnico_asignado"     =>$id_tecnico_asignado,
          "tecnico"                 =>$tecnico,*/
          "usuario"                 =>$row['usuario'],
          "comentarios"             =>$row['comentarios'],
          "adjuntosMantenimientoVehiculos"=>$adjuntosMantenimientoVehiculos
        ];

			}
			//echo json_encode($tareasMantenimiento);
      return json_encode($tareasMantenimiento);
		}

    public function traerAdjuntosMantenimientoVehiculos($id_tarea_mantenimiento){

      $arrayDocumentosVehiculo = [];
  
      $queryGet = "SELECT amv.id AS id_adjunto,amv.adjunto,amv.comentario,amv.fecha_hora_alta,u.email AS usuario
      FROM adjuntos_mantenimiento_vehicular amv 
        INNER JOIN usuarios u ON amv.id_usuario_alta=u.id 
      WHERE amv.id_mantenimiento_vehicular = $id_tarea_mantenimiento";
      //var_dump($queryGet);
      $getDatos = $this->conexion->consultaRetorno($queryGet);
  
      while ($row = $getDatos->fetch_array()) {
        $arrayDocumentosVehiculo[] =[
          "id_adjunto"      =>$row["id_adjunto"],
          "adjunto"         =>$row["adjunto"],
          "comentario"      =>$row["comentario"],
          "fecha_hora_alta" =>date("d-M-Y H:i",strtotime($row["fecha_hora_alta"]))."hs",
          "usuario"         =>$row["usuario"],
        ];
      }
      //echo json_encode($arrayDocumentosVehiculo);
      return json_encode($arrayDocumentosVehiculo);
    }

    public function marcarTareaMantenimientoCompleta($id_tarea_mantenimiento, $archivo, $comentarios_mantenimiento_vehicular){

      $id_usuario_ultima_actualizacion=$_SESSION["rowUsers"]["id_usuario"];

      $queryMarcarTareaCompleta = "UPDATE mantenimientos_vehiculares set realizado = 1
      WHERE id = $id_tarea_mantenimiento";
      //echo $queryMarcarTareaCompleta;
			$marcarTareaCompleta = $this->conexion->consultaSimple($queryMarcarTareaCompleta);

      /*BUSCO EL ID DEL VEHICULO CREADO PARA GUARDAR EL HISTORIAL DE TECNICOS*/
      /*$queryGetRealizadoTareaMantenimiento = "SELECT realizado FROM mantenimientos_vehiculares WHERE id_tarea_mantenimiento = '$id_tarea_mantenimiento'";
      $getRealizadoTareaMantenimiento = $this->conexion->consultaRetorno($queryGetRealizadoTareaMantenimiento);
      
      $realizado=0;
      if ($getRealizadoTareaMantenimiento->num_rows > 0 ) {
        $idRow = $getRealizadoTareaMantenimiento->fetch_assoc();
        $realizado = $idRow['realizado'];
      }*/

      $this->agregarAdjuntoMantenimientoVehicular($id_tarea_mantenimiento, $archivo, $comentarios_mantenimiento_vehicular);

      //echo $realizado;
		}

    public function agregarAdjuntoMantenimientoVehicular($id_tarea_mantenimiento, $archivo, $comentarios_mantenimiento_vehicular){
			if($archivo !=""){
        $id_usuario_alta=$_SESSION["rowUsers"]["id_usuario"];

				$nombreImagen = $archivo['name'];
				$directorio = "../views/adjuntos_mantenimiento_vehicular/";
				$nombreFinalArchivo = $nombreImagen;

        $nombre_archivo = $id_tarea_mantenimiento."_".$nombreFinalArchivo;

				move_uploaded_file($archivo['tmp_name'], $directorio.$nombre_archivo);
				//$ruta_completa_imagen = $directorio.$nombreFinalArchivo;

        $queryInsertAdjuntos = "INSERT INTO adjuntos_mantenimiento_vehicular (id_mantenimiento_vehicular, adjunto, comentario, fecha_hora_alta, id_usuario_alta)VALUES($id_tarea_mantenimiento, '$nombreFinalArchivo', '$comentarios_mantenimiento_vehicular', NOW(),'$id_usuario_alta')";
				//$queryUpdateImageName = "UPDATE tecnicos SET adjunto = '$archivo' WHERE id = $id_tarea_mantenimiento";
				$updateImageName = $this->conexion->consultaSimple($queryInsertAdjuntos);
			}
		}

    public function updateTareaMantenimiento($id_tarea_mantenimiento,$id_vehiculo, $detalle, $costo_estimado, $fecha, $hora, $comentarios){

			$this->id_tarea_mantenimiento=$id_tarea_mantenimiento;
      $id_usuario_ultima_actualizacion=$_SESSION["rowUsers"]["id_usuario"];
      $fecha_hora=$fecha." ".$hora;

      $queryUpdateTareaMantenimiento = "UPDATE mantenimientos_vehiculares set fecha_hora = '$fecha_hora', detalle = '$detalle', costo_estimado = '$costo_estimado', comentarios = '$comentarios'
      WHERE id = $this->id_tarea_mantenimiento";
      //echo $queryUpdateTareaMantenimiento;
			$updateTareaMantenimiento = $this->conexion->consultaSimple($queryUpdateTareaMantenimiento);

		}

    public function deleteTarea($id_tarea_mantenimiento){
			$this->id_tarea_mantenimiento = $id_tarea_mantenimiento;

			/*Eliminamos registros de la base de datos*/

			/*Tabla vehiculos*/
			$queryDelTarea = "DELETE FROM mantenimientos_vehiculares WHERE id=$this->id_tarea_mantenimiento";
			$delTarea = $this->conexion->consultaSimple($queryDelTarea);

		}

}

	if (isset($_POST['accion'])) {
		$vehiculo = new Vehiculo();
		switch ($_POST['accion']) {
			case 'traerDatosIniciales':
			  	$vehiculo->traerDatosIniciales();
			break;
      case 'traerVechiculoUpdate':
					//$id_vehiculo = $_POST['id_vehiculo'];
					//echo $vehiculo->traerVechiculoUpdate($id_vehiculo);
          echo $vehiculo->traerVehiculos($id_vehiculo);
			break;
			case 'addVehiculo':
          $fecha_alta = date('Y-m-d H:i:s');
          /*echo 1;
          var_dump($_FILES);*/

          if(isset($_FILES['file0'])) {
            $adjuntos = 1;
          }else{
            $adjuntos = 0;
          }
					$vehiculo->agregarVehiculo($patente, $marca, $modelo, $anio, $codigo_motor, $codigo_chasis, $nro_cedula_verde, $fecha_alta, $fecha_adquirido, $fecha_baja, $tecnico, $comentarios, $proximo_service_general, $km_adquirido, $proximo_vencimiento_vtv, $km_actuales,$numero_movil,$vencimiento_cedula_verde,$vencimiento_seguro,$vencimiento_gnc, $adjuntos, $cantAdjuntos);
			break;
      case 'updateVehiculo':
        if(isset($_FILES['file0'])) {
          $adjuntos = 1;
        }else{
          $adjuntos = 0;
        }

        $vehiculo->updateVehiculo($id_vehiculo, $patente, $marca, $modelo, $anio, $codigo_motor, $codigo_chasis, $nro_cedula_verde, $fecha_adquirido, $fecha_baja, $tecnico, $comentarios, $proximo_service_general, $km_adquirido, $proximo_vencimiento_vtv, $km_actuales, $numero_movil ,$vencimiento_cedula_verde, $vencimiento_seguro, $vencimiento_gnc, $adjuntos, $cantAdjuntos);
      break;
      case 'traerDetalleVehiculo':
        //$id_vehiculo = $_POST['id_vehiculo'];
        $vehiculo->traerDetalleVehiculo($id_vehiculo);
      break;
      case 'borrarDocumento':
        $vehiculo->eliminarDocumento($id_documento, $nombre_adjunto);
      break;
      case 'eliminarVehiculo':
        //$id_vehiculo = $_POST['id_vehiculo'];
        $vehiculo->deleteVehiculo($id_vehiculo);
      break;
      case 'addTareaMantenimiento':
        //$id_vehiculo = $_POST['id_vehiculo'];
        $vehiculo->agregarTareaMantenimiento($id_vehiculo, $detalle, $costo_estimado, $fecha_mantenimiento, $hora_mantenimiento, $comentarios);
      break;
      case 'marcarTareaMantenimientoRealizada':
        //$id_vehiculo = $_POST['id_vehiculo'];
        if(isset($_FILES['file'])) {
          $archivo = $_FILES['file'];
        }else{
          $archivo = "";
        }
        $vehiculo->marcarTareaMantenimientoCompleta($id_tarea_mantenimiento, $archivo, $comentarios_mantenimiento_vehicular);
      break;
      case 'traerTareaMantenimientoUpdate':
        //$id_vehiculo = $_POST['id_vehiculo'];
        $filtros=[
          "id_tarea_mantenimiento"=>$id_tarea_mantenimiento
        ];
        echo $vehiculo->traerTareasMantenimientoVehiculos($filtros);
      break;
      case 'updateTareaMantenimiento':
        //$id_vehiculo = $_POST['id_vehiculo'];
        echo $vehiculo->updateTareaMantenimiento($id_tarea_mantenimiento,$id_vehiculo, $detalle, $costo_estimado, $fecha_mantenimiento, $hora_mantenimiento, $comentarios);
      break;
      case 'eliminarTarea':
        //$id_vehiculo = $_POST['id_vehiculo'];
        $vehiculo->deleteTarea($id_tarea_mantenimiento);
      break;
		}
	}else{
		if (isset($_GET['accion'])) {
			$vehiculo = new Vehiculo();

			switch ($_GET['accion']) {
				case 'traerVehiculos':
					echo $vehiculo->traerVehiculos();
				break;
        case 'traerTareasMantenimientoVehiculos':
          $tareasMantenimientoVehiculos=$vehiculo->traerTareasMantenimientoVehiculos();
          $tareasMantenimientoVehiculos=json_decode($tareasMantenimientoVehiculos,true);

          $tareasMantenimientoCalendario=[];
          foreach ($tareasMantenimientoVehiculos as $tareaMantenimiento) {
            $id_tarea_mantenimiento= $tareaMantenimiento['id_tarea_mantenimiento'];
            $patente= strtoupper($tareaMantenimiento['vehiculo']['patente']);
            $fecha_hora= $tareaMantenimiento['fecha_hora'];
            $realizado= $tareaMantenimiento['realizado'];
            $color="orange";
            if($realizado==1){
                $color="green";
            }
            
            $detalle= $tareaMantenimiento['detalle'];
            $tecnico = $tareaMantenimiento['vehiculo']['tecnico'];
            $costo_estimado = $tareaMantenimiento['costo_estimado_mostrar'];
            $comentarios = $tareaMantenimiento['comentarios'];
            
            $descripcion="<u>Fecha y hora:</u> ".date("d/m/Y H:i", strtotime($fecha_hora))."<br><u>Detalle:</u> ".$detalle."<br><u>Tecnico:</u> ".$tecnico."<br><u>Costo estimado:</u> ".$costo_estimado."<br><u>Comentarios:</u> ".$comentarios;
    
            $tareasMantenimientoCalendario[]=[
              "id"          =>$id_tarea_mantenimiento,
              "title"       =>$patente ?? "(Vacío)",
              //"url"       =>"verEnvioLogistica.php?id=".$row["id"],
              "start"       =>$fecha_hora,
              "description" =>$descripcion,
              "realizado"   =>$realizado,
              "color"       =>$color,
              //"classNames"=>"bg-success border-success"
            ];
    
          }
          echo json_encode($tareasMantenimientoCalendario);
				break;
        case 'traerTareasMantenimientoInforme':
          //$id_vehiculo = $_POST['id_vehiculo'];
          $filtros=[
            //"id_vehiculo"=>$id_vehiculo,
            "desde"=>$desde,
            "hasta"=>$hasta
          ];
          echo $vehiculo->traerTareasMantenimientoVehiculos($filtros);
        break;
			}
		}
	}
?>