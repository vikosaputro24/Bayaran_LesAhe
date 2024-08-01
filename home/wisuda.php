<?php
session_start();
include '../connection.php';

// Assuming you have a user_id to fetch the details for a specific user
$user_id = $_SESSION['user_id']; // or however you retrieve the user_id

// Fetch user details from the database
$stmt = $conn->prepare("SELECT username, email FROM users WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = $user['username'];
    $email = $user['email'];
} else {
    $username = '';
    $email = '';
}

$stmt->close();

// Initialize toast variables
$toastClass = '';
$toastMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $paket = $_POST['paket'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $jumlah_pembayaran = $_POST['jumlah_pembayaran'];
    $bukti_pembayaran = $_FILES['bukti_pembayaran'];

    // Validate the form data
    if (!$username || !$email || !$phone || !$paket || !$metode_pembayaran || !$jumlah_pembayaran || !$bukti_pembayaran['name']) {
        $toastClass = 'toast-danger';
        $toastMessage = 'Semua data harus diisi!';
    } else {
        // Handle file upload
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($bukti_pembayaran['name']);

        if (move_uploaded_file($bukti_pembayaran['tmp_name'], $uploadFile)) {
            // Insert data into the database
            $stmt = $conn->prepare("INSERT INTO wisuda (username, email, phone, paket, metode_pembayaran, jumlah_pembayaran, bukti_pembayaran) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $username, $email, $phone, $paket, $metode_pembayaran, $jumlah_pembayaran, $uploadFile);

            if ($stmt->execute()) {
                $toastClass = 'toast-success';
                $toastMessage = 'Pembayaran berhasil dikirim!';
            } else {
                $toastClass = 'toast-danger';
                $toastMessage = 'Gagal menyimpan data ke database.';
            }

            $stmt->close();
        } else {
            $toastClass = 'toast-danger';
            $toastMessage = 'Gagal mengunggah bukti pembayaran.';
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pembayaran Wisuda</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
        .toast-success {
            background-color: #28a745;
            color: #fff;
        }
        .toast-danger {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body style="font-family: 'Roboto', sans-serif; background-image: url('../assets/p.jpg'); background-size: cover; background-position: center; padding: 20px;">
    <div class="container">
        <div class="payment-form bg-light p-4 rounded shadow-sm max-width-600 mx-auto">
            <h2 class="text-center mb-4">Form Pembayaran Wisuda</h2>
            <form action="" method="POST" enctype="multipart/form-data" id="paymentForm">
                <div class="mb-3">
                    <label for="username" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Nama Lengkap ..." required value="<?php echo htmlspecialchars($username); ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email ..." required value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Nomor Telepon</label>
                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Masukkan Nomor Telepon ..." required>
                </div>
                <div class="mb-3">
                    <label for="paket" class="form-label">Paket Wisuda</label>
                    <select class="form-select" id="paket" name="paket" required>
                        <option value="">Pilih Paket Wisuda</option>
                        <option value="500000">Paket Wisuda Standard - Rp. 500,000</option>
                        <option value="750000">Paket Wisuda Premium - Rp. 750,000</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                    <select class="form-select" id="metode_pembayaran" name="metode_pembayaran" required>
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="transfer_bank">Transfer Bank</option>
                        <option value="e-wallet">E-Wallet</option>
                        <option value="tunai">Tunai</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="jumlah_pembayaran" class="form-label">Jumlah Pembayaran (IDR)</label>
                    <input type="number" class="form-control" id="jumlah_pembayaran" name="jumlah_pembayaran" placeholder="Total Pembayaran ..." readonly required>
                </div>
                <div class="mb-3">
                    <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran</label>
                    <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" accept=".jpg, .jpeg, .png" required>
                </div>
                <div class="d-grid gap-2">
                    <input type="submit" class="btn btn-primary" value="Submit Pembayaran">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='./beranda.php';">Kembali ke Beranda</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Message and Toast -->
    <div class="toast-container">
        <?php if (!empty($toastMessage)): ?>
        <div id="toast" class="toast align-items-center text-white <?php echo $toastClass; ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <?php echo htmlspecialchars($toastMessage); ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('paket').addEventListener('change', function() {
            var paketValue = this.value;
            document.getElementById('jumlah_pembayaran').value = paketValue;
        });

        document.addEventListener('DOMContentLoaded', function() {
            var toastElements = document.querySelectorAll('.toast');
            toastElements.forEach(function(toast) {
                var bsToast = new bootstrap.Toast(toast);
                bsToast.show();
            });
        });
    </script>
</body>
</html>
