<?php
function formatFecha($fecha){
  return strftime("%d %b %Y",strtotime($fecha));
}

function formatFechaHora($fechaHora){
  return strftime("%d %b %Y, %H:%M",strtotime($fechaHora));
}