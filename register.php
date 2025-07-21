<?php
session_start();
require 'db.php';

if (
    isset($_SESSION['admin_logged_in']) &&
    $_SESSION['admin_logged_in'] === true &&
    isset($_SESSION['login_time']) &&
    (time() - $_SESSION['login_time']) < 600
) {
    header("Location: reservation-list.php");
    exit;
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    $confirm_password = $_POST["confirm_password"] ?? '';

    // Validasi input
    if (empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Semua field harus diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak sama!";
    } else {
        // Cek apakah email sudah terdaftar
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $existing_user = $stmt->fetch();

        if ($existing_user) {
            $error = "Email sudah terdaftar!";
        } else {
            // Hash password dan simpan ke database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            try {
                $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
                $stmt->execute([$email, $hashed_password]);
                
                // Redirect ke login dengan pesan sukses
                header("Location: login.php?registered=1");
                exit;
            } catch (PDOException $e) {
                $error = "Terjadi kesalahan: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - The Rinra</title>
  <link rel="icon" href="img/logo2.png" type="image/x-icon" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="register.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="https://therinra.com/">
      <img src="img/Logo1.png" class="logo" alt="Logo" />
    </a>
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="index.html">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="resevation.php">Reservation</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="event.html">Event</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="login.php">Login</a>
      </ul>
    </div>
  </div>
</nav>

  <!-- Register Form -->
  <div class="register-page d-flex align-items-center justify-content-center">
    <div class="wrapper fade-in">
      <h2>Register</h2>
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <?php if (!empty($success)): ?>
        <div class="alert alert-success text-center">
          <?= htmlspecialchars($success) ?>
          <br><a href="login.php" class="text-decoration-none">Klik di sini untuk login</a>
        </div>
      <?php endif; ?>
      <div class="form-container">
        <form method="post" action="register.php">
          <div class="input-box">
            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email ?? '') ?>" required />
          </div>
          <div class="input-box">
            <input type="password" name="password" id="password" placeholder="Password" required />
          </div>
          <div class="input-box">
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required />
          </div>
          <div class="form-check mb-2">
            <input type="checkbox" class="form-check-input" id="showPassword" onclick="togglePassword()" />
            <label class="form-check-label" for="showPassword">Show Password</label>
          </div>
          <div class="policy form-check mb-3">
            <input type="checkbox" class="form-check-input" id="terms" required />
            <label class="form-check-label" for="terms"> I accept all terms & conditions</label>
          </div>
          <div class="input-box button">
            <input type="submit" value="Register Now" />
          </div>
        </form>
        <div class="login-link text-center mt-3">
          <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    function togglePassword() {
      var passwordInput = document.getElementById("password");
      var confirmPasswordInput = document.getElementById("confirm_password");
      
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        confirmPasswordInput.type = "text";
      } else {
        passwordInput.type = "password";
        confirmPasswordInput.type = "password";
      }
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>