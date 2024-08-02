<?php
session_start();
include('../connection.php'); 

session_regenerate_id(true);
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT username, email, phone FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if (!$result) {
    die('Get result failed: ' . htmlspecialchars($stmt->error));
}
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);
    $new_phone = trim($_POST['phone']);

    if (empty($new_username) || empty($new_email) || empty($new_phone)) {
        $_SESSION['error_message'] = 'All fields are required.';
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = 'Invalid email format.';
    } else {
        // Update user details in the database
        $update_query = "UPDATE users SET username = ?, email = ?, phone = ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_query);
        if (!$update_stmt) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $update_stmt->bind_param("ssss", $new_username, $new_email, $new_phone, $user_id);
        
        if ($update_stmt->execute()) {
            $_SESSION['success_message'] = 'Profile updated successfully!';
            header('Location: profile.php');
            exit();
        } else {
            $_SESSION['error_message'] = 'Failed to update profile.';
        }
        $update_stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ahe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="d-flex align-items-center justify-content-center vh-100" style="background-image: url('../assets/p.jpg'); background-size: cover; background-position: center;">
    <div class="card shadow p-4" style="max-width: 600px; width: 100%; border-radius: 10px; background-color: rgba(255, 255, 255, 0.9);">
        <div class="d-flex align-items-center mb-4">
            <a href="../home/beranda.php" class="btn btn-link text-dark"><i class="fas fa-arrow-left"></i></a>
            <h2 class="text-center w-100 m-0">Profile Anda</h2>
        </div>
        
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <?= htmlspecialchars($_SESSION['success_message']) ?>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <?= htmlspecialchars($_SESSION['error_message']) ?>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <form action="profile.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Nama Lengkap:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Nomor Telepon:</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Simpan</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if (isset($_SESSION['success_message'])): ?>
                const successToast = new bootstrap.Toast(document.getElementById('successToast'));
                successToast.show();
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
                errorToast.show();
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
        });
    </script>
</body>
</html>
