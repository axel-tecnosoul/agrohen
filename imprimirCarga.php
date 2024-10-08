<?php
session_start();
//require_once('models/fpdf/fpdf.php');
require_once('models/fpdf/multicellTable.php');
include_once('models/conexion.php');
//date_default_timezone_set("America/Buenos_Aires");

if (!isset($_SESSION['rowUsers']['id_usuario'])) {
  header("location:./models/redireccionar.php");
  exit;
}

// Sanitizar y validar el parámetro
$con_precio = $_GET['cp'];
$data = $_GET['id_carga'];
$destinos = $_GET['destinos'];

include_once('models/administrar_cargas.php');
$id_carga = intval($data);

if ($id_carga > 0) {

    $cargas = new Cargas($id_carga);
    
    list($datosNecesarios, $aProductosDestinos) = $cargas->getDatosVerDetalleCarga($id_carga);

    $destinos_unicos = $cargas->getDestinoUnicosFromCargaProductosDestinos($aProductosDestinos);
    //var_dump($destinos_unicos);

    if($destinos!=''){
      $aDestinos=explode(",",$destinos);
      //var_dump($aDestinos);
      $destinos_unicos = array_filter($destinos_unicos, function($destino) use ($aDestinos) {
        return in_array($destino['id_destino'], $aDestinos);
      });
    }

    //var_dump($destinos_unicos);

    $cant_destinos=count($destinos_unicos);

    $orientacion="P";//vertical
    $ancho_repartir_destinos=120;//210 (ancho) - 20 (margenes) - 50 - 20
    if($cant_destinos>4){
      $orientacion="L";//horizontal
      $ancho_repartir_destinos=207;///297 (ancho) - 20 (margenes) - 50 - 20
    }

    if ($con_precio) {
      $ancho_repartir_destinos-=20;//descontamos lo que ocupa la columna del precio
    }

    $ancho_destino=$ancho_repartir_destinos/$cant_destinos;

    class PDF extends PDF_MC_Table {
      // Encabezado
      function Header() {
        global $destinos_unicos,$con_precio,$ancho_destino;

        $this->Image('assets/images/logo horizontal.png',12,7,48);

        $this->SetFont('Arial', '', 8);
        // $this->Cell(0, 10, date("d M Y H:i"), 0, 1, 'R');

        $this->Cell(0, 10, strftime("%A, %d de %B de %Y, %H:%M", strtotime(date("d M Y H:i"))) , 0, 1, 'R');
        $this->SetY(10);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 10, 'Orden de Carga ID ' . $_GET['id_carga'], 0, 1, 'C');
        $this->Ln(5);
        // Encabezado de la tabla
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(143, 143, 143); // Color de fondo gris
        $fill=1;
        $height=6;
        $data=$anchos=$aligns=[];

        //$this->Cell(50, $height, 'Producto', $fill, 0, 'C', true);
        $anchos[]=50;
        $aligns[]="C";
        $data[]='Producto';
        if ($con_precio) {
          //$this->Cell(20, $height, 'Precio', $fill, 0, 'C', true);
          $anchos[]=20;
          $aligns[]="C";
          $data[]='Precio';
        }

        //$cant_caracteres=$ancho_destino/2;
        foreach ($destinos_unicos as $destino) {
          $anchos[]=$ancho_destino;
          $aligns[]="C";
          $data[]=$destino["destino"];
          //$this->Cell($ancho_destino, 6, $this->cortarTexto(utf8_decode($destino["destino"]), $cant_caracteres), 1, 0, 'C', true);
        }

        //$this->Cell(20, $height, 'Total Bultos', $fill, 1, 'C', true);
        $anchos[]=20;
        $aligns[]="C";
        $data[]='Total Bultos';

        $this->SetWidths($anchos);
        $this->SetAligns($aligns);
        $this->Row($data,$height,$fill,$textColors="empty");

        $fill=0;
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

      // Tabla con los productos y destinos
      function TablaProductos($aProductosDestinos, $destinos_unicos, $con_precio) {
        global $ancho_destino;

        $this->SetFont('Arial', '', 8);
        // Datos de la tabla
        $totals = [];
        $total_precio = 0;
        $fill=0;
        $height=6;

        foreach ($aProductosDestinos as $product) {
          
          $data=$anchos=$aligns=[];


          //$nombre_producto=$this->cortarTexto($product['familia']." ".$product['producto']." (".$product['presentacion']." - ".$product['unidad_medida'].")", 30);
          $nombre_producto=$product['familia']." ".$product['producto']." (".$product['presentacion']." - ".$product['unidad_medida'].")";
          //$this->Cell(50,6,utf8_decode($nombre_producto),1);
          $anchos[]=50;
          $aligns[]="L";
          $data[]=$nombre_producto;

          if ($con_precio) {
            $precio = $product['precio_general'];
            $total_precio += $precio;
            //$this->Cell(20, 6, '$ ' . number_format($precio, 2, ",", "."), 1, 0, 'R');
            $anchos[]=20;
            $aligns[]="R";
            $data[]='$ ' . number_format($precio, 2, ",", ".");
          }

          $destinos_actuales = [];
          foreach ($product['destinos'] as $destino) {
            $destinos_actuales[$destino['id_destino']] = $destino;
          }

          $sumaBultos=0;
          foreach ($destinos_unicos as $destino) {
            $id_destino=$destino['id_destino'];
            
            $cantidad_bultos = 0;
            if (isset($destinos_actuales[$id_destino])) {
              $cantidad_bultos = $destinos_actuales[$id_destino]['cantidad_bultos'];
            }

            if (!isset($totals[$id_destino])) {
              $totals[$id_destino] = ['bultos' => 0];
            }
            $totals[$id_destino]['bultos'] += $cantidad_bultos;
            $sumaBultos+=$cantidad_bultos;


            //$this->Cell($ancho_destino, 6, number_format($cantidad_bultos, 0, ",", "."), 1, 0, 'R');
            $anchos[]=$ancho_destino;
            $aligns[]="R";
            $data[]=number_format($cantidad_bultos, 0, ",", ".");
          }

          //$this->Cell(20, 6, number_format($sumaBultos, 0, ",", "."), 1, 1, 'R');
          $anchos[]=20;
          $aligns[]="R";
          $data[]=number_format($sumaBultos, 0, ",", ".");

          $this->SetWidths($anchos);
          $this->SetAligns($aligns);
          $this->Row($data,$height,$fill,$textColors="empty");
        }

        // Totales
        $this->SetFont('Arial', 'B', 8);
        $anchoTotales=50;
        if ($con_precio) {
          //$this->Cell(20, 6, '$ ' . number_format($total_precio, 2, ",", "."), 1, 0, 'R');
          $anchoTotales+=20;
        }
        $this->Cell($anchoTotales, 6, 'Totales', 1, 0, 'C');
        $sumaBultos=0;
        foreach ($destinos_unicos as $destino) {
          $bultos=$totals[$destino['id_destino']]['bultos'];
          $sumaBultos+=$bultos;
          $this->Cell($ancho_destino, 6, number_format($bultos, 0, ",", "."), 1, 0, 'R');
        }
        $this->Cell(20, 6, number_format($sumaBultos, 0, ",", "."), 1, 1, 'R');
      }
    }

    // Creación del PDF
    $pdf = new PDF($orientacion);
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
