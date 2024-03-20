<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Cosmpatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="endless admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, endless admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
   
    <title>Imprimir orde de compra</title>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="assets/css/fontawesome.css">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="assets/css/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/flag-icon.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/feather-icon.css">
    <!-- Plugins css start-->
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link id="color" rel="stylesheet" href="assets/css/light-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
  </head>
<body class="bg-white">
    <div class="container">
        <div class="row">
            <!--<div class="col-12 offset-11">
                <button id="imprimir">Imprimir</button>
            </div>-->
            <div class="col-12 text-center mb-3 mt-5">
                <?php $data = json_decode($_GET['datos']); ?>
                <h3>ORDEN DE COMPRA #<?php echo $data[0]->id_orden; ?></h3>
                <?php

               //echo count($data[0]->detalle)
                ?>
                
            </div>
            <div class="col-3 border">
                <label>Proveedor: </label> <span class="font-weight-bold"><?php echo $data[0]->proveedor; ?></span>
            </div>
            <div class="col-3 border">
                <label>Almacen: </label> <span class="font-weight-bold"><?php echo $data[0]->almacen; ?></span>
            </div>
            <div class="col-3 border">
                <label>Estado: </label> <span class="font-weight-bold"><?php echo $data[0]->estado; ?></span>
            </div> 
            <div class="col-3 border">
                <label>Fecha: </label> <span class="font-weight-bold"><?php echo $data[0]->fecha; ?></span>
            </div>            
        </div>
        <table class="table mt-3">
            <thead>
                <th>Código</th>
                <th>Detalle</th>
                <th>Cantidad</th>
                <th>Precio unit.</th>
                <th>Total</th>
            </thead>
            <tbody>
                <?php for ($i=0; $i < count($data[0]->detalle); $i++) { ?>
                <tr>
                    <td><?php echo $data[0]->detalle[$i]->id_item; ?></td>
                    <td><?php echo $data[0]->detalle[$i]->item; ?></td>
                    <td><?php echo $data[0]->detalle[$i]->cantidad; ?></td>
                    <td><?php echo $data[0]->detalle[$i]->precioUnit; ?></td>
                    <td><?php echo $data[0]->detalle[$i]->precio_total;?></td>
                </tr>   
                <?php } ?>
            </tbody>
            <tf>
                <tr>
                    <td>
                    <label>TOTAL:&nbsp;</label><span class="font-weight-bold"><?php echo $data[0]->total; ?></span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    

	<!-- latest jquery-->
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap js-->
    <script src="assets/js/bootstrap/popper.min.js"></script>
    <script src="assets/js/bootstrap/bootstrap.js"></script>
    <!-- feather icon js-->
    <script src="assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="assets/js/icons/feather-icon/feather-icon.js"></script>
    <script type="text/javascript">
        /*$btnImprimir = document.getElementById("imprimir");
        $btnImprimir.addEventListener("click", imprimir);*/

        window.addEventListener("load", function(){
            window.print();
        });
        
    </script>
</body>
</html>