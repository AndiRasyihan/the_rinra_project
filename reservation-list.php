<?php
require 'db.php';
$data = $pdo->query("SELECT * FROM reservations ORDER BY id DESC")->fetchAll();

session_start();
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit;
}

// Durasi login
$max_login_time = 10 * 60;
$now = time();

if (isset($_SESSION["login_time"]) && ($now - $_SESSION["login_time"]) > $max_login_time) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Data Reservasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
  <?php if (isset($_COOKIE["nama_user"])): ?>
    <script>
      alert("Selamat datang kembali, <?= htmlspecialchars($_COOKIE["nama_user"]) ?>!");
    </script>
    <?php setcookie("nama_user", "", time() - 3600, "/"); // hapus cookie ?>
  <?php endif; ?>
  <div class="container mt-5">
    <h2 class="mb-4 text-center">Daftar Reservasi</h2>

    <?php if (count($data) === 0): ?>
      <div class="alert alert-info text-center">Belum ada data reservasi.</div>
    <?php else: ?>
      
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>No. HP</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Tamu</th>
            <th>Tipe Kamar</th>
            <th>Permintaan Khusus</th>
            <th>Waktu Pesan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data as $i => $r): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($r['full_name']) ?></td>
              <td><?= htmlspecialchars($r['email']) ?></td>
              <td><?= htmlspecialchars($r['phone']) ?></td>
              <td><?= $r['checkin'] ?></td>
              <td><?= $r['checkout'] ?></td>
              <td><?= $r['guests'] ?></td>
              <td><?= htmlspecialchars($r['roomType']) ?></td>
              <td><?= nl2br(htmlspecialchars($r['requests'])) ?></td>
              <td><?= $r['created_at'] ?></td>
              <td>
                <a href="edit.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="delete.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-danger"
                   onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <p class="text-end"><strong>Login sebagai:</strong> <?= $_SESSION['admin_email'] ?></p>
      <form action="logout.php" method="post" class="text-end mb-3">
          <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    <?php endif; ?>
    <a href="resevation.php" class="btn btn-secondary mt-3">Kembali ke Form Reservasi</a>
  </div>
</script>
</body>
</html>
