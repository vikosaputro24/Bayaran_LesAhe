<?php
session_start();
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $hashedPassword);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['user_id'] = $user_id;
                header("Location: ../home/beranda.php");
                exit();
            } else {
                $error = "Kata sandi salah.";
            }
        } else {
            $error = "Email tidak ditemukan.";
        }

        $stmt->close();
    } else {
        $error = "Harap isi semua kolom.";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ahe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <style>
    .password-toggle-icon {
      position: absolute;
      top: 12px;
      right: 15px;
      cursor: pointer;
      display: none;
    }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100" style="background-image: url('../assets/p.jpg'); background-size: cover; background-position: center;">
  <div class="card shadow p-4" style="max-width: 400px; width: 100%; border-radius: 10px; background: rgba(255, 255, 255, 0.9);">
    <h2 class="text-center mb-4">Masuk</h2>
    <form action="login.php" method="POST">
      <div class="mb-3 position-relative">
        <label for="email" class="form-label">
          <i class="fa fa-envelope me-2"></i>Email
        </label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email ..." required>
      </div>
      <div class="mb-3 position-relative">
        <label for="password" class="form-label d-flex justify-content-between align-items-center">
          <span><i class="fa fa-lock me-2"></i>Kata Sandi</span>
          <a href="./forget.php" class="small" style="text-decoration: none;">Lupa Kata Sandi?</a>
        </label>
        <div class="position-relative">
          <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Kata Sandi ..." required>
          <i class="fa fa-eye password-toggle-icon" id="togglePassword"></i>
        </div>
      </div>
      <button type="submit" class="btn btn-primary w-100">Masuk</button>
    </form>
    <div class="text-center mt-3">
      <p>Belum punya akun ? <a href="./register.php" style="text-decoration: none;">Daftar disini</a></p>
    </div>
  </div>

  <!-- Toast Container -->
  <div class="toast-container" style="position: fixed; top: 1rem; right: 1rem; z-index: 1050;">
    <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const passwordField = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    passwordField.addEventListener('input', function () {
      togglePassword.style.display = passwordField.value ? 'block' : 'none';
    });

    togglePassword.addEventListener('click', function () {
      const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordField.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });

    <?php if (isset($error)): ?>
    document.addEventListener('DOMContentLoaded', function () {
      const toastElement = document.getElementById('errorToast');
      const toastBody = toastElement.querySelector('.toast-body');
      toastBody.textContent = "<?php echo htmlspecialchars($error); ?>";
      const toast = new bootstrap.Toast(toastElement);
      toast.show();
    });
    <?php endif; ?>
  </script>
</body>
</html>
