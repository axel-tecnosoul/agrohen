<!-- page-wrapper Start-->
<div class="page-wrapper">
<div class="page-main-header">
  <div class="main-header-right row">
    <div class="main-header-left d-lg-none">
      <div class="logo-wrapper"><a href="dashboard.php"></a></div>
    </div>
    <div class="mobile-sidebar d-block">
      <div class="media-body text-right switch-sm">
        <label class="switch"><a href="#"><i id="sidebar-toggle" data-feather="align-left"></i></a></label>
      </div>
    </div>
    <div class="nav-right col p-0">
      <ul class="nav-menus">
        <li style="text-align: left;width:100%">
          
          <div class="page-header py-3"><h3 class="mb-0"><?=$mainHeaderTitle?></h3></div>
          
          <?php
          $currentFile = basename($_SERVER['PHP_SELF']);
          if ($_SESSION['rowUsers']['id_perfil'] == 2 && $currentFile !== 'home_users.php') {
            include_once 'models/administrar_deposito.php';
            include_once 'models/funciones.php';
            $depositos = new depositos();
            $id_deposito = $_SESSION['rowUsers']['id_deposito'];
              
            // Verificar saldo
            $verificacionSaldo = $depositos->verificarSaldo($id_deposito);
            if ($verificacionSaldo['excede_maximo']) {?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 0;padding: 7px;">
                    <strong>Atención!</strong> El saldo de la cuenta corriente (<?= formatCurrency($verificacionSaldo['saldo_cta_cte']) ?>) excede el máximo permitido (<?= formatCurrency($verificacionSaldo['saldo_maximo_permitido']) ?>).
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div><?php
            }
          }
          
        /*$vencimientoCRT="2024-09-26 10:55:18";
          //$vencimientoCRT="2023-04-26 10:55:18";//pruebas
          $fechaActual=date("Y-m-d");
          $fechaVencimiento=date("Y-m-d",strtotime($vencimientoCRT."- 1 month"));
          if(strtotime($fechaActual)>strtotime($fechaVencimiento)){
            setlocale(LC_TIME, "es_AR");
            $dia=date("d",strtotime($vencimientoCRT));
            $mes=date("F",strtotime($vencimientoCRT));
            $anio=date("Y",strtotime($vencimientoCRT));
            $hora=date("H:i:s",strtotime($vencimientoCRT));
            $fecha_formateada=$dia." de ".$mes." de ".$anio." a las ".$hora;
            //echo "$fechaActual es mayor a $fechaVencimiento.";?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 0;padding: 7px;">
              <strong>Atencion!</strong> El <?=$fecha_formateada//echo strftime("%d de %B de %Y a las %H:%M:%S",strtotime($vencimientoCRT))?> vence el certificado de AFIP que permite la facturacion electronica. Por favor pongase en contacto con el desarrollador del sistema para generar un nuevo.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
            </div><?php
          }else{
            //echo "$fechaActual NO es mayor a $fechaVencimiento";
          }*/?>
        </li>
        <li class="onhover-dropdown">
            <h6><b><?php echo $_SESSION['rowUsers']['usuario']?></b></h6>
        </li>
        <li class="onhover-dropdown">
          <div class="media align-items-center"><a href="./models/logOut.php"><img class="align-self-center pull-right rounded-circle" src="assets/images/cerrar-sesion.png" width="25px" alt="header-user"></a>
          </div>
        </li>
      </ul>
      <div class="d-lg-none mobile-toggle pull-right"><i data-feather="more-horizontal"></i></div>
    </div>
  </div>
</div>