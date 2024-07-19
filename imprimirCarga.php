<?php
session_start();
require_once('models/fpdf/fpdf.php');
include_once('models/conexion.php');
date_default_timezone_set("America/Buenos_Aires");

ob_start(); // Inicia la captura de salida

$hora = date('Hi');
$hoy = date('Y-m-d');
if (!isset($_SESSION['rowUsers']['id_usuario'])) {
    header("location:./models/redireccionar.php");
    exit;
}

// Sanitizar y validar el parámetro
$con_precio = json_decode($_GET['cp']);
$data = json_decode($_GET['id_carga']);
include_once('models/administrar_cargas.php');
$id_carga = intval($data);

if ($id_carga > 0) {
    $cargas = new Cargas($id_carga);
    $detalles = $cargas->traerDatosVerDetalleCarga($id_carga);
    echo '<pre>';
    echo 'Detalles obtenidos de la base de datos:';
    var_dump($detalles);
    echo '</pre>';
    $cargasArray = json_decode($detalles, true);
    // Depurar el resultado de json_decode
    echo '<pre>';
    echo 'Array decodificado de detalles:';
    var_dump($cargasArray);
    echo '</pre>';

    if (json_last_error() === JSON_ERROR_NONE && is_array($cargasArray)) {
        $productos = $cargasArray['productos'] ?? [];
        echo '<pre>';
        echo 'Array de productos:';
        var_dump($productos);
        echo '</pre>';
        die;
    } else {
        $productos = [];
        echo '<p>Error al decodificar los datos de carga.</p>';
        echo '<pre>' . htmlspecialchars($detalles) . '</pre>';
        var_dump($productos);
        die;
        exit;
    }
    
    // Inicio de maquetar Factura con FPDF
    class Factura extends FPDF {
        function Header() {
            // Logotipo
            // $this->Image('assets/images/logo.jpg', 10, 10, 30); 

            // Encabezado
            $this->SetFont('Arial', 'B', 15);
            $this->Cell(0, 10, 'Ver Carga ID: ' . $_GET['id_carga'], 0, 0, 'C');
            $this->Ln(20);
        }

        function Footer() {
            // Pie de página
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }

        function CargaDetalles($fechaCarga, $origen, $chofer, $datosAdicionales) {
            $this->SetFont('Arial', '', 12);
            $this->Cell(50, 10, 'Fecha Carga: ' . $fechaCarga, 0, 0, 'L');
            $this->Cell(50, 10, 'Origen: ' . $origen, 0, 0, 'L');
            $this->Cell(50, 10, 'Chofer: ' . $chofer, 0, 0, 'L');
            $this->Cell(50, 10, 'Datos adicionales: ' . $datosAdicionales, 0, 1, 'L');
            $this->Ln(10);
        }

        function TablaProductos($productos) {
            $this->SetFont('Arial', 'B', 12);
            // Encabezado de la tabla
            $this->Cell(40, 10, 'Producto', 1, 0, 'C');
            $this->Cell(40, 10, 'Proveedor', 1, 0, 'C');
            $this->Cell(25, 10, 'Precio', 1, 0, 'C');
            $this->Cell(25, 10, 'Kg x bulto', 1, 0, 'C');
            $this->Cell(25, 10, 'Bultos PR', 1, 0, 'C');
            $this->Cell(25, 10, 'Kilos PR', 1, 0, 'C');
            $this->Cell(25, 10, 'Monto PR', 1, 0, 'C');
            $this->Cell(25, 10, 'Bultos Pos', 1, 0, 'C');
            $this->Cell(25, 10, 'Kilos Pos', 1, 0, 'C');
            $this->Cell(25, 10, 'Monto Pos', 1, 1, 'C');

            $this->SetFont('Arial', '', 12);
            foreach ($productos as $producto) {
                $this->Cell(40, 10, $producto['nombre'], 1);
                $this->Cell(40, 10, $producto['proveedor'], 1);
                $this->Cell(25, 10, '$' . number_format($producto['precio'], 2), 1, 0, 'R');
                $this->Cell(25, 10, number_format($producto['kg_bulto'], 2), 1, 0, 'R');
                $this->Cell(25, 10, $producto['bultos_pr'], 1, 0, 'R');
                $this->Cell(25, 10, number_format($producto['kilos_pr'], 2), 1, 0, 'R');
                $this->Cell(25, 10, '$' . number_format($producto['monto_pr'], 2), 1, 0, 'R');
                $this->Cell(25, 10, $producto['bultos_pos'], 1, 0, 'R');
                $this->Cell(25, 10, number_format($producto['kilos_pos'], 2), 1, 0, 'R');
                $this->Cell(25, 10, '$' . number_format($producto['monto_pos'], 2), 1, 1, 'R');
            }

            // Totales
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(40, 10, 'Totales', 1);
            $this->Cell(40, 10, '', 1);
            $this->Cell(25, 10, '', 1);
            $this->Cell(25, 10, '', 1);
            $this->Cell(25, 10, array_sum(array_column($productos, 'bultos_pr')), 1, 0, 'R');
            $this->Cell(25, 10, number_format(array_sum(array_column($productos, 'kilos_pr')), 2), 1, 0, 'R');
            $this->Cell(25, 10, '$' . number_format(array_sum(array_column($productos, 'monto_pr')), 2), 1, 0, 'R');
            $this->Cell(25, 10, array_sum(array_column($productos, 'bultos_pos')), 1, 0, 'R');
            $this->Cell(25, 10, number_format(array_sum(array_column($productos, 'kilos_pos')), 2), 1, 0, 'R');
            $this->Cell(25, 10, '$' . number_format(array_sum(array_column($productos, 'monto_pos')), 2), 1, 1, 'R');
        }
    }

    // Datos de ejemplo para los detalles de la carga
    $fechaCarga = "14-05-2024";
    $origen = "San Juan";
    $chofer = "Jose Perez";
    $datosAdicionales = "asd";
    $proveedor = "Pepinito SA";

    // Creación del PDF
    $pdf = new Factura();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->CargaDetalles($fechaCarga, $origen, $chofer, $datosAdicionales);
    $pdf->TablaProductos($productos);

    // Captura cualquier salida pendiente y limpia el buffer
    ob_end_clean();

    // Salida del PDF
    $pdf->Output();
    exit;
} else {
    echo json_encode(['error' => 'ID no válido']);
    exit;
}
?>
