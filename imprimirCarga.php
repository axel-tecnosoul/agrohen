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
$con_precio = json_decode($_GET['cp']);
$data = json_decode($_GET['id_carga']);
include_once('models/administrar_cargas.php');
$id_carga = intval($data);

if ($id_carga > 0) {
    $cargas = new Cargas($id_carga);
    list($datosNecesarios, $aProductosDestinos) = $cargas->traerDatosVerDetalleCarga($id_carga);

    $destinos_unicos = $cargas->getDestinoUnicosFromCargaProductosDestinos($aProductosDestinos);

    class PDF extends FPDF {
        // Encabezado
        function Header() {
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(0, 10, 'Reporte de Carga ID: ' . $_GET['id_carga'], 0, 1, 'C');
            $this->Ln(5);
        }

        // Pie de página
        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }

        // Método para cortar texto
        function cortarTexto($texto, $longitudMaxima) {
            if (strlen($texto) > $longitudMaxima) {
                return substr($texto, 0, $longitudMaxima) . '...';
            } else {
                return $texto;
            }
        }

        // Tabla con los productos y destinos
        function TablaProductos($aProductosDestinos, $destinos_unicos, $con_precio) {
            // Encabezado de la tabla
            $this->SetFont('Arial', 'B', 8);
            $this->SetFillColor(143, 143, 143); // Color de fondo gris

            $this->Cell(60, 6, 'Producto', 1, 0, 'C', true);
            if ($con_precio) {
                $this->Cell(20, 6, 'Precio', 1, 0, 'C', true);
            }

            foreach ($destinos_unicos as $destino) {
                $this->Cell(20, 6, $this->cortarTexto($destino, 10), 1, 0, 'C', true);
            }

            $this->Cell(20, 6, 'Total Bultos', 1, 1, 'C', true);
            $this->SetFont('Arial', '', 8);

            // Datos de la tabla
            $totals = [];
            $total_precio = 0;
            foreach ($aProductosDestinos as $product) {
                $this->Cell(60, 6, $product['familia'] . " " . $product['producto'] . " (" . $product['presentacion'] . " - " . $product['unidad_medida'] . ")", 1);
                if ($con_precio) {
                    $precio = $product['precio'];
                    $total_precio += $precio;
                    $this->Cell(20, 6, '$ ' . number_format($precio, 2, ",", "."), 1, 0, 'R');
                }

                $destinos_actuales = [];
                foreach ($product['destinos'] as $destino) {
                    $destinos_actuales[$destino['destino']] = $destino;
                }

                foreach ($destinos_unicos as $destino) {
                    $cantidad_bultos = 0;
                    if (isset($destinos_actuales[$destino])) {
                        $cantidad_bultos = $destinos_actuales[$destino]['cantidad_bultos'];
                    }

                    if (!isset($totals[$destino])) {
                        $totals[$destino] = ['bultos' => 0];
                    }
                    $totals[$destino]['bultos'] += $cantidad_bultos;

                    $this->Cell(20, 6, number_format($cantidad_bultos, 0, ",", "."), 1, 0, 'R');
                }

                $this->Cell(20, 6, number_format($product['total_bultos'], 0, ",", "."), 1, 1, 'R');
            }

            // Totales
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(60, 6, 'Totales', 1, 0, 'C');
            if ($con_precio) {
                $this->Cell(20, 6, '$ ' . number_format($total_precio, 2, ",", "."), 1, 0, 'R');
            }
            foreach ($destinos_unicos as $destino) {
                $this->Cell(20, 6, number_format($totals[$destino]['bultos'], 0, ",", "."), 1, 0, 'R');
            }
            $this->Cell(20, 6, number_format(array_sum(array_column($aProductosDestinos, 'total_bultos')), 0, ",", "."), 1, 1, 'R');
        }
    }

    // Creación del PDF
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->TablaProductos($aProductosDestinos, $destinos_unicos, $con_precio);
    $pdf->Output();
    exit;
} else {
    echo json_encode(['error' => 'ID no válido']);
    exit;
}
?>
