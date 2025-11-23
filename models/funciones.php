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

function getEnumValues(PDO $db, string $table, string $column): array {
  $stmt = $db->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$row) return [];
  if (!preg_match("/^enum\((.*)\)$/", $row['Type'], $matches)) return [];
  return str_getcsv($matches[1], ',', "'");
}
