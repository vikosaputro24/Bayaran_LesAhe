<?php
session_start();
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];

    if (strlen($newPassword) < 8) {
        $message = "Password must be at least 8 characters long.";
    } else {
        $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt->close();

            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashedPassword, $email);
            $stmt->execute();

            if ($stmt->affected_rows === 1) {
                $message = "Password successfully updated.";
            } else {
                $message = "Failed to update password. Please try again.";
            }
        } else {
            $message = "No user found with that email.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ahe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100"
      style="background-image: url('../assets/p.jpg'); background-size: cover; background-position: center;">
  <div class="card shadow p-4" style="max-width: 400px; width: 100%; border-radius: 10px; background: rgba(255, 255, 255, 0.9);">
    <h2 class="text-center mb-4">Forgot Password</h2>
    <?php if (isset($message)): ?>
    <div class="alert alert-info" role="alert">
      <?php echo htmlspecialchars($message); ?>
    </div>
    <?php endif; ?>
    <form action="forget.php" method="POST">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email ..." required>
      </div>
      <div class="mb-3">
        <label for="new_password" class="form-label">New Password</label>
        <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Masukkan kata sandi baru ..." required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Simpan kata sandi baru</button>
    </form>
    <div class="text-center mt-3">
      <p>Sudah ganti kata sandi ? <a href="./login.php" style="text-decoration: none;">Masuk disini</a></p>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
