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
    $ctacte = new ctacte($id_cuenta);
    $ctacteJson = $ctacte->getCtacte($desde, $hasta, $id_cuenta, $id_deposito, $tipo, $tipo_aumento_extra, $valor_extra);

    // Decodificar el JSON
    $aCtaCte = json_decode($ctacteJson, true);

    // Verificar si la decodificación fue exitosa
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['error' => 'Datos inválidos recibidos']);
        exit;
    }

    // Verificar si $aCtaCte es un array válido
    if (!is_array($aCtaCte)) {
        echo json_encode(['error' => 'Datos inválidos recibidos']);
        exit;
    }

    // Filtrar solo los datos necesarios
    $aCtaCte_filtrados = array();
    foreach ($aCtaCte as $mov) {
        // Verificar que los campos necesarios existen en cada movimiento
        if (isset($mov['fecha_hora_formatted'], $mov['descripcion'], $mov['debe'], $mov['haber'], $mov['saldo'])) {
            // Manejar la descripción de acuerdo a las nuevas reglas
            if (isset($mov['id_carga'])) {
                $descripcion = "Carga #" . $mov['id_carga'];
            } else if (isset($mov['id_movimiento'])) {
                $descripcion = "Movimiento #" . $mov['id_movimiento'];
            } else {
                $descripcion = strip_tags($mov['descripcion']);
            }

            $aCtaCte_filtrados[] = array(
                'fecha_hora_formatted' => $mov['fecha_hora_formatted'],
                'descripcion' => $descripcion, // Usar la descripción modificada
                'debe' => (float) $mov['debe'],
                'haber' => (float) $mov['haber'],
                'saldo' => (float) $mov['saldo']
            );
        }
    }

    class PDF extends FPDF {
        // Encabezado
        function Header() {
            global $id_cuenta;
            $this->Image('assets/images/logo horizontal.png',12,7,48); // Logo
            $this->SetFont('Arial', '', 8);
            $this->Cell(0, 10, date("d M Y H:i"), 0, 1, 'R');
            $this->SetY(10);
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, 'Cuenta Corriente ID ' . $id_cuenta, 0, 1, 'C');
            $this->Ln(5);
            // Encabezado de la tabla
            $this->SetFont('Arial', 'B', 10);
            $this->SetFillColor(143, 143, 143); // Color de fondo gris
            $this->Cell(40, 8, 'Fecha y Hora', 1, 0, 'C', true);
            $this->Cell(60, 8, 'Descripcion', 1, 0, 'C', true);
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

        // Método para cortar texto
        function cortarTexto($texto, $longitudMaxima) {
            if (strlen($texto) > $longitudMaxima) {
                return substr($texto, 0, $longitudMaxima) . '...';
            } else {
                return $texto;
            }
        }

        // Tabla con los movimientos
        function TablaMovimientos($aCtaCte) {
            $this->SetFont('Arial', '', 10);
            foreach ($aCtaCte as $mov) {
                $this->Cell(40, 8, $mov['fecha_hora_formatted'], 1);
                $this->Cell(60, 8, $this->cortarTexto($mov['descripcion'], 30), 1);
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
    $pdf->TablaMovimientos($aCtaCte_filtrados);
    $pdf->Output();
    exit;
} else {
    echo json_encode(['error' => 'ID no válido']);
    exit;
}
?>