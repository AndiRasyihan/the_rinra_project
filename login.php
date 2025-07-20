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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["admin_logged_in"] = true;
        $_SESSION["admin_email"] = $user["email"];
        $_SESSION["login_time"] = time();

        setcookie("nama_user", $user["email"], time() + 3600);
        header("Location: reservation-list.php");
        exit;
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - The Rinra</title>
  <link rel="icon" href="img/logo2.png" type="image/x-icon" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="login.css">
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
          <a class="nav-link active" aria-current="page" href="login.php">Login</a>
        </li>
      </ul>
    </div>
  </div>
</nav>


  <!-- Login Form -->
  <div class="login-page d-flex align-items-center justify-content-center">
    <div class="wrapper fade-in">
      <h2>Login</h2>
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <?php if (isset($_GET['timeout'])): ?>
        <div class="alert alert-warning text-center">Sesi Anda telah habis. Silakan login kembali.</div>
      <?php endif; ?>
      <div class="form-container">
        <form method="post" action="login.php">
          <div class="input-box">
            <input type="text" name="email" placeholder="Email" required />
          </div>
          <div class="input-box">
            <input type="password" name="password" id="password" placeholder="Password" required />
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
            <input type="submit" value="Login Now" />
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    function togglePassword() {
      var passwordInput = document.getElementById("password");
      passwordInput.type = (passwordInput.type === "password") ? "text" : "password";
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
