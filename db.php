<?php
$host = '127.0.0.1';
$db   = 'hotel';  // Sesuaikan dengan nama database kamu
$user = 'root';
$pass = '';              // Biarkan kosong jika belum ada password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  die("Koneksi gagal: " . $e->getMessage());
}
