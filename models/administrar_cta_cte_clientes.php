<?php
	session_start();
	require_once('../../conexion.php');
	class CtaCteClientes{
		private $id_empresa;
		private $id_tipo_caja;
		private $id_usuario;
		private $id_cliente;
		
		public function __construct(){
				$this->conexion = new Conexion();
				date_default_timezone_set("America/Buenos_Aires");
		}

		public function traerDatosIniciales($id_empresa){

			$this->id_empresa = $id_empresa;

			/*PROVEEDORES*/
			$queryClientes = "SELECT id as id_cliente, razon_social as cliente
							FROM clientes
							WHERE id_empresa = $this->id_empresa
							AND activo = 1";
			$getClientes = $this->conexion->consultaRetorno($queryClientes);

			$datosIniciales = array();
			$arrayClientes = array();

			/*CARGO ARRAY PROVEEDORES*/
			while ($rowClientes= $getClientes->fetch_array()) {
				$id_cliente = $rowClientes['id_cliente'];
				$cliente = $rowClientes['cliente'];
				$arrayClientes[] = array('id_cliente' => $id_cliente, 'cliente' =>$cliente);
			}

			$datosIniciales["clientes"] = $arrayClientes;
			echo json_encode($datosIniciales);

		}


		public function traerCtaCteCliente($id_empresa, $id_cliente){

			$this->id_empresa = $id_empresa;
			$this->id_cliente = $id_cliente;

			$query = "SELECT cl.id as id_cliente, cl.razon_social as cliente, sum(mc.monto) AS saldo
					FROM movimientos_clientes as mc join clientes as cl
					ON(mc.id_cliente = cl.id)
					WHERE cl.id_empresa = $this->id_empresa
					AND cl.id = $this->id_cliente
					group by cl.razon_social";
			$getCtaCteCliente = $this->conexion->consultaRetorno($query);

			$arrayCtaCteCliente = array(); //creamos un array

			while ($row = $getCtaCteCliente->fetch_array()) {
            $id_cliente = $row['id_cliente'];
            $cliente = $row['cliente'];
            $saldo = $row['saldo'];

            $arrayCtaCteCliente[] = array('id_cliente'=> $id_cliente, 'cliente'=>$cliente, 'saldo'=>$saldo);
        }

        echo json_encode($arrayCtaCteCliente);

		}


		public function traerDetalleCtaCte($id_cliente){
			$this->id_cliente = $id_cliente;

			
			$arrayDetalleCtaCte = array();

			$queryDetalleCtaCte = "SELECT mc.id, mcta.tipo, mc.monto, 
								mc.monto as saldo 
								FROM movimientos_clientes as mc JOIN tipos_movimientos_ctacte as mcta
								ON(mc.id_tipo_movimiento = mcta.id)
								WHERE  id_cliente = $this->id_cliente";
			$getDetalleCtaCte = $this->conexion->consultaRetorno($queryDetalleCtaCte);

			while ($rowDetalle = $getDetalleCtaCte->fetch_assoc()) {
				
				$id_movimiento = $rowDetalle['id'];
				$tipo = $rowDetalle['tipo'];
				$monto = "$".number_format($rowDetalle['monto'],2,',','.');
				$saldo = $rowDetalle['saldo'];

				$arrayDetalleCtaCte[] = array('id_movimiento'=>$id_movimiento, 'tipo'=>$tipo, 'monto'=>$monto, 'saldo'=>$saldo);
			}

			echo json_encode($arrayDetalleCtaCte);

		}
}	

	if (isset($_POST['accion'])) {
		$ctaCteClientes = new CtaCteClientes();
		switch ($_POST['accion']) {
			case 'traerDatosIniciales':
				$id_empresa = $_POST['id_empresa'];
				$ctaCteClientes->traerDatosIniciales($id_empresa);
				break;
		}
	}else{
		if (isset($_GET['accion'])) {
			$ctaCteClientes = new CtaCteClientes();

			switch ($_GET['accion']) {
				case 'traerCtacCteClientes':
					$id_empresa = $_GET['id_empresa'];
					$id_cliente = $_GET['id_cliente'];
					$ctaCteClientes->traerCtaCteCliente($id_empresa, $id_cliente);
					break;
				case 'traerDetalleCtaCte':
					$id_cliente = $_GET['id_cliente'];
					$ctaCteClientes->traerDetalleCtaCte($id_cliente);
					break;
				default:
					// code...
					break;
			}
		}
	}
?>