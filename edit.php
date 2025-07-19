<?php
require 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) exit('ID tidak ditemukan.');

$stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) exit('Data tidak ditemukan.');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $pdo->prepare("UPDATE reservations SET
        full_name = ?, email = ?, phone = ?, checkin = ?, checkout = ?,
        guests = ?, roomType = ?, requests = ?
        WHERE id = ?");
    $stmt->execute([
        $_POST['fullName'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['checkin'],
        $_POST['checkout'],
        $_POST['guests'],
        $_POST['roomType'],
        $_POST['requests'],
        $id
    ]);

    header("Location: reservation-list.php");
    exit;
}

session_start();
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Reservasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2>Edit Reservasi</h2>
    <form method="post">
      <div class="mb-3">
        <label>Nama Lengkap</label>
        <input type="text" name="fullName" class="form-control" value="<?= htmlspecialchars($data['full_name']) ?>">
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= $data['email'] ?>">
      </div>
      <div class="mb-3">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control" value="<?= $data['phone'] ?>">
      </div>
      <div class="mb-3 row">
        <div class="col">
          <label>Check-in</label>
          <input type="date" name="checkin" class="form-control" value="<?= $data['checkin'] ?>">
        </div>
        <div class="col">
          <label>Check-out</label>
          <input type="date" name="checkout" class="form-control" value="<?= $data['checkout'] ?>">
        </div>
      </div>
      <div class="mb-3">
        <label>Jumlah Tamu</label>
        <input type="number" name="guests" class="form-control" value="<?= $data['guests'] ?>">
      </div>
      <div class="mb-3">
        <label>Tipe Kamar</label>
        <select name="roomType" class="form-select">
          <option <?= $data['roomType'] === 'standard' ? 'selected' : '' ?>>standard</option>
          <option <?= $data['roomType'] === 'deluxe' ? 'selected' : '' ?>>deluxe</option>
          <option <?= $data['roomType'] === 'suite' ? 'selected' : '' ?>>suite</option>
        </select>
      </div>
      <div class="mb-3">
        <label>Permintaan Khusus</label>
        <textarea name="requests" class="form-control"><?= htmlspecialchars($data['requests']) ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      <a href="reservation-list.php" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</body>
</html>
