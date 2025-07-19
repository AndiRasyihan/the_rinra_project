<?php
require 'db.php';

$email = "aan@gmail.com";
$password = password_hash("aan123", PASSWORD_DEFAULT); // hash password

$stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$stmt->execute([$email, $password]);

echo "Admin berhasil dibuat!";
