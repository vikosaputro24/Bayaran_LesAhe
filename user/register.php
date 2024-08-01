<?php
session_start();
include '../connection.php';

  function generate_uuid() {
      return sprintf(
          '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0, 0xffff),
          mt_rand(0, 0xffff),
          mt_rand(0, 0xffff),
          mt_rand(0, 0x0fff) | 0x4000,
          mt_rand(0, 0x3fff) | 0x8000,
          mt_rand(0, 0xffff),
          mt_rand(0, 0xffff),
          mt_rand(0, 0xffff)
      );
  }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    if (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        $uuid = generate_uuid();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (user_id, username, email, phone, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $uuid, $username, $email, $phone, $hashedPassword);

        if ($stmt->execute()) {
            $_SESSION['registration_success'] = true;
            header("Location: register.php");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $conn->close();
}

$showToast = isset($error) || isset($_SESSION['registration_success']);
$toastMessage = isset($error) ? $error : (isset($_SESSION['registration_success']) ? "Selamat pendaftaran anda berhasil ..." : '');
$toastClass = isset($error) ? 'bg-danger' : 'bg-success';
unset($_SESSION['registration_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Anak Hebat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="d-flex align-items-center justify-content-center vh-100" style="background-image: url('../assets/p.jpg'); background-size: cover; background-position: center;">
  <div class="card shadow p-4" style="max-width: 400px; width: 100%; border-radius: 10px; background: rgba(255, 255, 255, 0.9);">
    <h2 class="text-center mb-4">Daftar</h2>
    <form action="register.php" method="POST">
      <div class="mb-3 position-relative">
        <label for="username" class="form-label">
          <i class="fa fa-user me-2"></i>Nama Lengkap
        </label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Nama Lengkap ..." required>
      </div>
      <div class="mb-3 position-relative">
        <label for="email" class="form-label">
          <i class="fa fa-envelope me-2"></i>Email
        </label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email ..." required>
      </div>
      <div class="mb-3 position-relative">
        <label for="phone" class="form-label">
          <i class="fa fa-phone me-2"></i>Nomor Telepon
        </label>
        <input type="number" class="form-control" id="phone" name="phone" placeholder="Masukkan Nomor Telepon ..." required>
      </div>
      <div class="mb-3 position-relative">
        <label for="password" class="form-label d-flex justify-content-between align-items-center">
          <span><i class="fa fa-lock me-2"></i>Kata Sandi</span>
        </label>
        <div class="position-relative">
          <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Kata Sandi ..." required minlength="8">
          <i class="fa fa-eye position-absolute" id="togglePassword" style="top: 12px; right: 15px; cursor: pointer;"></i>
        </div>
      </div>
      <button type="submit" class="btn btn-primary w-100">Daftar</button>
    </form>
    <div class="text-center mt-3">
      <p>Sudah punya akun ? <a href="./login.php" style="text-decoration: none;">Masuk disini</a></p>
    </div>
  </div>

  <!-- Toast Notification -->
  <div class="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 1050;">
    <div id="toast" class="toast align-items-center text-white <?php echo $toastClass; ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          <?php echo $toastMessage; ?>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('togglePassword').addEventListener('click', function () {
      const passwordField = document.getElementById('password');
      const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordField.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });

    <?php if ($showToast): ?>
    document.addEventListener('DOMContentLoaded', function () {
      var toast = new bootstrap.Toast(document.getElementById('toast'));
      toast.show();
      setTimeout(function() {
        window.location.href = './login.php';
      }, 3000);
    });
    <?php endif; ?>
  </script>
</body>
</html>
