<?php
function formatFecha($fecha){
  return strftime("%d %b %Y",strtotime($fecha));
}

function formatFechaHora($fechaHora){
  return strftime("%d %b %Y, %H:%M",strtotime($fechaHora));
}

function formatCurrency($number) {
  $formattedNumber = number_format($number, 2, ',', '.');
  return "$" . $formattedNumber;
}