  <!DOCTYPE html>

  <?php
  require 'db.php';

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $stmt = $pdo->prepare("INSERT INTO reservations 
          (full_name, email, phone, checkin, checkout, guests, roomType, requests) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

      $stmt->execute([
          $_POST['fullName'],
          $_POST['email'],
          $_POST['phone'],
          $_POST['checkin'],
          $_POST['checkout'],
          $_POST['guests'],
          $_POST['roomType'],
          $_POST['requests']
      ]); 

      header("Location: resevation.php?success=1");
      exit;
  }
  ?>

  <html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Reservasi Kamar - The Rinra</title>
    <link rel="icon" href="img/logo2.png" type="image/x-icon" />
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
      body {
        padding-top: 70px;
        background-image: url('img/login.jpeg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
      }
      .reservation-form {
        max-width: 600px;
        margin: auto;
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 10px 4px 10px rgba(0,0,0,0.1);
      }
      .custom-btn {
        background-color: #1E2125;
        border-color: #1E2125;
        color: white; /* Menambahkan warna teks putih */
      }

      .custom-btn:hover {
        background-color: #333;
        border-color: #333;
      }

    </style>
  </head>
  <body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top mb-5">
          <div class="container">
            <a class="navbar-brand" href="https://therinra.com/"><img src="img/Logo1.png" class="logo" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                  <a class="nav-link " aria-current="page" href="index.html">Home</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="resevation.php">Reservation</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="event.html">Event</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="login.php">Login</a>
                </li>
              </ul>
            </div>
          </div>
        </nav>

    <!-- Reservation Form -->
    <div class="container mt-5 mb-4">
      <div class="reservation-form mt-4">
        <h3 class="mb-4 text-center">Reservation Room Hotel</h3>
        <?php if (isset($_GET['success'])): ?>
          <div class="alert alert-success text-center">Reservasi berhasil disimpan!</div>
        <?php endif; ?>
        <div class="form-container fade-in">
        <form method="post">
          <div class="mb-3">
            <label for="fullName" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="fullName" name="fullName" required />
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required />
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" id="phone" name="phone" required />
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="checkin" class="form-label">Check-in</label>
              <input type="date" class="form-control" id="checkin" name="checkin" required />
            </div>
            <div class="col-md-6 mb-3">
              <label for="checkout" class="form-label">Check-out</label>
              <input type="date" class="form-control" id="checkout" name="checkout" required />
            </div>
          </div>
          <div class="mb-3">
            <label for="guests" class="form-label">Number Of Guests</label>
            <input type="number" class="form-control" id="guests" name="guests" min="1" required />
          </div>
          <div class="mb-3">
            <label for="roomType" class="form-label">Room Type</label>
            <select class="form-select" id="roomType" name="roomType" required>
              <option value="">Select Room Type</option>
              <option value="standard">Standard Room</option>
              <option value="deluxe">Deluxe Room</option>
              <option value="suite">Suite Room</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="requests" class="form-label">Special Request</label>
            <textarea class="form-control" id="requests" name="requests" rows="3"></textarea>
          </div>
          <button type="submit" class="btn custom-btn w-100">Send Reservation</button>
        </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

  </body>
  </html>
