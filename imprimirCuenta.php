<?php
session_start();
require_once('models/fpdf/fpdf.php');
include_once('models/conexion.php');
date_default_timezone_set("America/Buenos_Aires");

$hora = date('Hi');
$hoy = date('Y-m-d');
if (!isset($_SESSION['rowUsers']['id_usuario'])) {
    header("location:./models/redireccionar.php");
    exit;
}

// Sanitizar y validar el parámetro
$id_cuenta = intval($_GET['id_cuenta']);
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
$id_deposito = $_GET['id_deposito'];
$tipo = $_GET['tipo'];
$tipo_aumento_extra = $_GET['tipo_aumento_extra'];
$valor_extra = $_GET['valor_extra'];

include_once('models/administrar_cta_cte.php');

if ($id_cuenta > 0) {
    $cargas = new ctacte($id_cuenta);
    $movimientos = $cargas->getCtacte($desde, $hasta, $id_cuenta, $id_deposito, $tipo, $tipo_aumento_extra, $valor_extra);

    // Verificar si $movimientos es un array válido
    if (!is_array($movimientos)) {
        echo json_encode(['error' => 'Datos invalidos recibidos']);
        exit;
    }

    // Filtrar solo los datos necesarios
    $movimientos_filtrados = array();
    foreach ($movimientos as $mov) {
        $movimientos_filtrados[] = array(
            'fecha_hora_formatted' => $mov['fecha_hora_formatted'],
            'descripcion' => strip_tags($mov['descripcion']), // Eliminar etiquetas HTML de la descripción
            'debe' => (float) $mov['debe'],
            'haber' => (float) $mov['haber'],
            'saldo' => (float) $mov['saldo']
        );
    }

    class PDF extends FPDF {
        // Encabezado
        function Header() {
            global $id_cuenta;
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, 'Cuenta Corriente ID ' . $id_cuenta, 0, 1, 'C');
            $this->Ln(5);
            // Encabezado de la tabla
            $this->SetFont('Arial', 'B', 10);
            $this->SetFillColor(200, 200, 200); // Color de fondo gris claro
            $this->Cell(40, 8, 'Fecha y Hora', 1, 0, 'C', true);
            $this->Cell(80, 8, 'Descripcion', 1, 0, 'C', true);
            $this->Cell(30, 8, 'Debe', 1, 0, 'C', true);
            $this->Cell(30, 8, 'Haber', 1, 0, 'C', true);
            $this->Cell(30, 8, 'Saldo', 1, 1, 'C', true);
        }

        // Pie de página
        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }

        // Tabla con los movimientos
        function TablaMovimientos($movimientos) {
            $this->SetFont('Arial', '', 10);
            foreach ($movimientos as $mov) {
                $this->Cell(40, 8, $mov['fecha_hora_formatted'], 1);
                $this->Cell(80, 8, $mov['descripcion'], 1);
                $this->Cell(30, 8, '$ ' . number_format($mov['debe'], 2, ",", "."), 1, 0, 'R');
                $this->Cell(30, 8, '$ ' . number_format($mov['haber'], 2, ",", "."), 1, 0, 'R');
                $this->Cell(30, 8, '$ ' . number_format($mov['saldo'], 2, ",", "."), 1, 1, 'R');
            }
        }
    }

    // Creación del PDF
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->TablaMovimientos($movimientos_filtrados);
    $pdf->Output();
    exit;
} else {
    echo json_encode(['error' => 'ID no válido']);
    exit;
}
?>
