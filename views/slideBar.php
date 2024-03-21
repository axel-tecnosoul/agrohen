<style>
  .sidebar-menu a:hover{
    text-decoration: initial !important;
    color: white !important;
  }  
</style>

<div class="main-header-left d-none d-lg-block">
  <div class="logo-wrapper"><a href="home_users.php"><img src="assets/images/logo horizontal.png" alt="" width="200px"></a></div>
</div>

<div class="sidebar custom-scrollbar">
  
  <ul class="sidebar-menu"><?php
    $script_name=explode("/",$_SERVER["SCRIPT_NAME"]);
    $script_name=$script_name[count($script_name)-1];
  
    if ($_SESSION['rowUsers']['id_perfil'] == 1) {?>
      <li><a class="sidebar-header" href="cargas.php"><i data-feather="user"></i><span>Cargas</span><i class="fa fa-angle-right pull-right"></i></a></li>
      <li><a class="sidebar-header" href="cuenta_corriente.php"><i data-feather="user"></i><span>Cta Cte</span><i class="fa fa-angle-right pull-right"></i></a></li>

      <li><a class="sidebar-header" href="#"><i data-feather="plus"></i><span>Maestro</span><i class="fa fa-angle-right pull-right"></i></a>
        <ul class="sidebar-submenu">
          <li><a href="productos.php"><i class="fa fa-circle"></i> Productos</a></li>
          <li><a href="depositos.php"><i class="fa fa-circle"></i> Depositos</a></li>
          <li><a href="origenes.php"><i class="fa fa-circle"></i> Origenes</a></li>
          <li><a href="choferes.php"><i class="fa fa-circle"></i> Choferes</a></li>
          <li><a href="usuarios.php"><i class="fa fa-circle"></i> Usuarios</a></li>
        </ul>
      </li><?php
    } else{
      
    }?>
  </ul>
</div>
<script type='text/javascript'>
  //PARA "OCULTAR" LAS OPCIONES DEL MENÚ QUE AÚN NO SE USAN
  document.querySelectorAll('.sidebar-menu a').forEach((anchor) => {
      if(anchor.classList.length==0){
        //console.log(anchor);
        if(anchor.getAttribute("href")=="#"){
          anchor.style.textDecoration="line-through";
          anchor.style.color="black";
        }
      }
  });

  document.addEventListener("DOMContentLoaded", function(event) {
    //let anchor=document.querySelector("a[href='<?=$script_name?>']")
    let script_name=window.location.href.split("/")
    script_name=script_name[script_name.length-1]
    script_name=script_name.split("?")
    script_name=script_name[0].replace("#", '');
    console.log(script_name);
    let anchor=document.querySelector("a[href='"+script_name+"']")
    let el=anchor.parentElement;
    el.style.border="solid 1px white";
    el.style.borderRadius="20px";
    el.style.paddingLeft="10px";
    el.style.backgroundColor="rgb(255 255 255 / 10%)";
    while(!el.classList.contains('sidebar-menu')){
      if(el.nodeName=="LI"){
        el.classList.add('active')
      }
      if(el.nodeName=="UL" && el.classList.contains('sidebar-submenu')){
        el.classList.add('menu-open')
      }
      el=el.parentElement;
    }
  });
</script>