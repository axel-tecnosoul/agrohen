<?php
	class Conexion{
		private $host = "localhost";
		private $user = "root";
		private $pass = "";
		private $db = "myla";
		public $conectar;

		public function __construct(){
			$this->conectar = new mysqli($this->host, $this->user, $this->pass, $this->db);
		}

		public function consultaSimple($sql){
			$this->conectar->query($sql);
		}

		public function consultaRetorno($sql){
      $this->conectar->set_charset("utf8");
			$datos = $this->conectar->query($sql);
			return $datos;
		}
	}
?>