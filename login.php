<!DOCTYPE html>
<html lang="en"><?php
  include_once('header.html');?>
  <body>
    <!-- Loader starts-->
    <div class="loader-wrapper">
      <div class="loader bg-white">
        <div class="whirly-loader"> </div>
      </div>
    </div>
    <!-- Loader ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper">
      <div class="auth-bg">
        <div class="authentication-box">
          <!--<div class="text-center"><img src="assets/images/favicon.png" alt=""></div>-->
          <div class="card mt-4">
            <div class="card-body">
              <div class="text-center">
                <h4>LOGIN</h4>
                <h6>Ingrese su usuario y contraseña</h6>
              </div>
              <!--<form class="theme-form">-->
                <div class="form-group">
                  <label class="col-form-label pt-0">Usuario</label>
                  <input class="form-control" type="text" id="usuario">
                </div>
                <div class="form-group">
                  <label class="col-form-label">Contraseña</label>
                  <input class="form-control" type="password" id="pass">
                </div>
                <div class="alert alert-danger dark alert-dismissible fade show" role="alert" style="display: none;" id="msgErrorLogin">
                  <i class="icon-thumb-down"></i>
                  <span id="msgErrorLoginText">
                  </span>
                </div>
                <div class="form-group form-row mt-3 mb-0">
                  <button class="btn btn-primary btn-block" type="submit" id="btnIngresar">Ingresar</button>
                </div>
              <!--</form>-->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- latest jquery-->
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap js-->
    <script src="assets/js/bootstrap/popper.min.js"></script>
    <script src="assets/js/bootstrap/bootstrap.js"></script>
    <!-- feather icon js-->
    <script src="assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="assets/js/icons/feather-icon/feather-icon.js"></script>
    <!-- Sidebar jquery-->
    <script src="assets/js/sidebar-menu.js"></script>
    <script src="assets/js/config.js"></script>
    <!-- Plugins JS start-->
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="assets/js/script.js"></script>
    <!-- Plugin used-->
    <script type="text/javascript">
      btnIngresar = document.getElementById('btnIngresar');
      btnIngresar.addEventListener('click', validarCamposIngreso, false);

      usuario = document.getElementById('usuario');
      pass = document.getElementById('pass');

      usuario.addEventListener('keyup', ingresarConEnter, false);
      pass.addEventListener('keyup', ingresarConEnter, false);

      function ingresarConEnter(e){
        console.log(e);
        if(e.code=="Enter"){
          btnIngresar.click();
        }
      }

      function validarCamposIngreso(){

        usuario = document.getElementById('usuario').value;
        clave = document.getElementById('pass').value;
        msgErrorLogin = document.getElementById('msgErrorLogin');

        if (usuario == '' || clave =='') {
          document.getElementById('msgErrorLoginText').innerHTML="debe completar los campos usuario y contraseñas";
          msgErrorLogin.style.display="block";
        }else{
          msgErrorLogin.style.display="none";
          
          var datosLogin = new FormData();
          datosLogin.append('usuario', usuario);
          datosLogin.append('clave', clave);
          datosLogin.append('accion', 'validateUser');

          $.ajax({
            data: datosLogin,
            url: './models/validateUsers.php',
            method: "post",
            cache: false,
            contentType: false,
            processData: false,
            beforeSed: function(){
              //$('#addProdLocal').modal('hide');
            },
            success: function(respuesta){
              if(respuesta != '1'){
                document.getElementById('msgErrorLoginText').innerHTML=respuesta;
                msgErrorLogin.style.display="block";
              }else{
                location.href='./models/redireccionar.php';
              }
            }
          });
        }
      }
    </script>
  </body>
</html>