<?php
function getPdoConnection(): PDO {
  static $pdo = null;
  if ($pdo === null) {
    $dsn = 'mysql:host=localhost;dbname=agrohen;charset=utf8';
    $pdo = new PDO($dsn, 'root', '', [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
  }
  return $pdo;
}

function getEnumValues(PDO $db, string $table, string $column): array {
  $stmt = $db->prepare("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
  $stmt->execute([$table, $column]);
  $row = $stmt->fetch();
  if (!$row) {
    return [];
  }
  if (preg_match("/^enum\('(.*)'\)$/", $row['COLUMN_TYPE'], $matches)) {
    return array_map(function ($value) {
      return str_replace("''", "'", $value);
    }, explode("','", $matches[1]));
  }
  return [];
}
?>
