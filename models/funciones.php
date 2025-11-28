<?php
function formatFecha($fecha){
  return date("d M Y",strtotime($fecha));
}

function formatFechaHora($fechaHora){
  return date("d M Y, H:i",strtotime($fechaHora));
}

function formatCurrency($number) {
  $formattedNumber = number_format($number, 2, ',', '.');
  return "$" . $formattedNumber;
}