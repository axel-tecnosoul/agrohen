<?php 
session_start();
include_once('models/conexion.php');
date_default_timezone_set("America/Buenos_Aires");
$hora = date('Hi');
$hoy = date('Y-m-d');
if (!isset($_SESSION['rowUsers']['id_usuario'])) {
  header("location:./models/redireccionar.php");
}?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="endless admin es un template responsivo de administración con posibilidades ilimitadas.">
    <meta name="keywords" content="admin template, endless admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <title>Imprimir Carga</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <!-- icofont -->
    <link rel="stylesheet" href="assets/css/icofont.css">
    <!-- Themify icon -->
    <link rel="stylesheet" href="assets/css/themify.css">
    <!-- Flag icon -->
    <link rel="stylesheet" href="assets/css/flag-icon.css">
    <!-- Feather icon -->
    <link rel="stylesheet" href="assets/css/feather-icon.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link id="color" rel="stylesheet" href="assets/css/light-1.css" media="screen">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="assets/css/responsive.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
        }

        /* Para el modal */
        .modal-dialog {
            width: 100%;
            margin: 0;
            max-width: none; 
        }

        .modal-content {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .modal-body {
            padding: 20px;
        }

        /* Para las columnas y filas */
        .row {
            margin: 0;
            padding: 0;
        }

        .col-lg-3, .col-lg-12 {
            padding: 0;
            margin: 0;
            width: 100%;
        }

        /* Ajuste para la tabla */
        .table-responsive {
            width: 100%;
            margin: 0;
            padding: 0;
            overflow: auto; /* Asegura que el contenido de la tabla sea desplazable si es necesario */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto; 
        }

        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd; 
        }

        /* Ajuste de márgenes de la tabla */
        .table-container {
            margin: 0;
            padding: 0;
        }

        @media print {
            /* Ajuste de márgenes y configuración de página */
            @page {
                size: A4;
                margin: 1cm; /* Ajusta el margen según sea necesario */
            }

            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
            }

            /* Estilos para modal y tabla */
            .modal-dialog {
                width: auto;
                margin: 0;
            }

            .modal-content {
                width: 100%;
                margin: 0;
                padding: 2%;
            }

            .modal-body {
                padding: 0;
            }

            .row {
                display: block;
                margin: 0;
                padding: 0;
            }
            .row-detalle {
                text-align: justify; 
                display: flex;
                flex-wrap: wrap;
            }
            .row-detalle::after {
                content: "";
                display: inline-block;
                width: 100%; 
            }
            .row-detalle .item {
                display: inline-block;
                width: calc(33.33% - 10px); 
                margin: 5px;
                padding: 10px;
                box-sizing: border-box; 
            }
            }
            .col-lg-3, .col-lg-12 {
                width: 100%;
                padding: 0;
                margin: 0;
            }

            .form-group {
                margin: 0 0 10px 0; /* Ajusta la separación de los campos */
            }

            /* Ajuste para tabla */
            .table-responsive {
                display: block;
                width: 100%;
                overflow: visible;
            }

            table {
                width: 100%;
                table-layout: fixed;
                border-collapse: collapse;
            }

            th, td {
                word-wrap: break-word;
                text-align: left;
                padding: 8px;
                border: 1px solid #ddd;
            }

            /* Asegurar que el contenido del footer de la tabla sea visible */
            tfoot {
                display: table-footer-group;
            }

            /* Ocultar botones o elementos no necesarios en la impresión */
            /* .modal-header, .modal-footer, .btn, .no-print {
                display: none;
            } */
        }
    </style>
</head>
<body><?php
    // Sanitizar y validar el parámetro
    $con_precio = json_decode($_GET['cp']);
    $data = json_decode($_GET['id_carga']);
    include_once('models/administrar_cargas.php');
    $id_carga = intval($data);

    if ($id_carga > 0) {
        $cargas = new Cargas($id_carga);
        $detalles = $cargas->traerDatosVerDetalleCarga($id_carga);
        $cargasArray = json_decode($detalles, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($cargasArray)) {
            
            $productos = $cargasArray['productos'] ?? []; 
             //var_dump($productos);
             //die;
        } else {
            $productos = []; 
            echo '<p>Error al decodificar los datos de carga.</p>';
            echo '<pre>' . htmlspecialchars($detalles) . '</pre>';
            // var_dump($productos);
            // die;
        }?>
    
        <div class="modal fade show" id="modalCRUDadminVer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detalles de la Carga</h5>
                    </div>
                    <form id="formAdmin" style="display: contents;">
                        <div class="modal-body">
                            <div class="row row-detalle">
                                <div class="col-lg-3 item">
                                    <div class="form-group">
                                        <label class="col-form-label font-weight-bold">Fecha Carga: </label>
                                        <span id="lbl_fecha_carga"><?= htmlspecialchars($cargasArray['fecha_formatted']); ?></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 item">
                                    <div class="form-group">
                                        <label class="col-form-label font-weight-bold">Origen:</label>
                                        <span id="lbl_origen"><?= htmlspecialchars($cargasArray['origen']); ?></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 item">
                                    <div class="form-group">
                                        <label class="col-form-label font-weight-bold">Chofer:</label>
                                        <span id="lbl_chofer"><?= htmlspecialchars($cargasArray['chofer']); ?></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 item">
                                    <div class="form-group">
                                        <label class="col-form-label font-weight-bold">Datos adicionales:</label>
                                        <span id="lbl_datos_adicionales_chofer"><?= htmlspecialchars($cargasArray['datos_adicionales_chofer']); ?></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 item">
                                    <div class="form-group">
                                        <label class="col-form-label font-weight-bold">Proveedor:</label>
                                        <span id="lbl_proveedor"><?= htmlspecialchars($cargasArray['proveedor']); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table id="tableProductosVer" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 30%">Producto</th>
                                                    <th style="width: 25%">Cantidad de bultos</th>
                                                    <th style="width: 15%">Kg Total</th>
                                                    <?php if ($con_precio == 1): ?>
                                                        <th style="width: 15%">Precio</th>
                                                        <th style="width: 20%">Monto Total</th>
                                                    <?php endif; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $totales_bultos = 0;
                                                    $totales_kilos = 0;
                                                    $totales_precios = 0;
                                                    $totales_montos = 0;

                                                    foreach ($productos as $carga) {
                                                        $totales_bultos += floatval($carga['total_bultos']);
                                                        if ($con_precio == 1): 
                                                            $totales_precios += floatval($carga['precio']);
                                                            $totales_montos += floatval($carga['total_monto']);
                                                        endif; 
                                                        $totales_kilos += floatval($carga['total_kilos']);
                                                ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($carga['familia'] . " " . $carga['producto']); ?></td>
                                                            <td><?= htmlspecialchars(number_format(floatval($carga['total_bultos']), 0, ',', '.')); ?></td>
                                                            <td><?= htmlspecialchars(number_format(floatval($carga['total_kilos']), 0, ',', '.')); ?></td>
                                                            <?php if ($con_precio == 1): ?>
                                                            <td><?= htmlspecialchars("$" . number_format(floatval($carga['precio']), 2, ',', '.')); ?></td>
                                                            <td><?= htmlspecialchars("$ " . number_format(floatval($carga['total_monto']), 2, ',', '.')); ?></td>
                                                            <?php endif; ?>
                                                        </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th style="text-align:right">Totales</th>
                                                    <th class="text-left" id="total_bultos_ver"><?= number_format($totales_bultos, 0, ',', '.'); ?></th>
                                                    <th class="text-left" id="total_kilos_ver"><?= number_format($totales_kilos, 0, ',', '.'); ?></th>
                                                    <?php if ($con_precio == 1): ?>
                                                        <th class="text-left" id="total_precios_ver"><?= "$ " . number_format($totales_precios, 2, ',', '.'); ?></th>
                                                        <th class="text-left" id="total_monto_ver"><?= "$ " . number_format($totales_montos, 2, ',', '.'); ?></th>
                                                    <?php endif; ?>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div><?php
    } else {
        echo json_encode(['error' => 'ID no válido']);
    }?>
    <!-- Latest jQuery -->
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap/popper.min.js"></script>
    <script src="assets/js/bootstrap/bootstrap.js"></script>
    <!-- Feather icon JS -->
    <script src="assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="assets/js/icons/feather-icon/feather-icon.js"></script>
    <script type="text/javascript">
        window.addEventListener("load", function(){
            window.print();
        });
    </script>
</body>
</html>
