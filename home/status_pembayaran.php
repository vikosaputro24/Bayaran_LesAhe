<?php
// Koneksi dan session start
include '../connection.php';
session_start();

// Redirect jika tidak ada user yang login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit();
}

// Ambil data pengguna dari session
$username = $_SESSION['username'] ?? '';
$phone = $_SESSION['phone'] ?? '';
$email = $_SESSION['email'] ?? '';

// Query untuk mendapatkan bulan-bulan dan tanggal pembayaran
$sql_bulan_bayar = "SELECT bulan_pembayaran, tanggal_pembayaran FROM bayar WHERE username = '$username'";
$result_bulan_bayar = mysqli_query($conn, $sql_bulan_bayar);
$bulan_bayar = [];
while ($row = mysqli_fetch_assoc($result_bulan_bayar)) {
    $bulan_bayar[$row['bulan_pembayaran']] = $row['tanggal_pembayaran'];
}

// Daftar bulan yang tersedia
$daftar_bulan = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

// Hitung bulan yang belum dibayar
$bulan_belum_bayar = array_diff($daftar_bulan, array_keys($bulan_bayar));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Pengguna - Informasi Pembayaran</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            margin-top: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .card {
            margin-top: 20px;
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.25rem;
            margin-bottom: 10px;
        }

        .list-group-item {
            border-color: rgba(0,0,0,.125);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        td {
            vertical-align: middle;
        }

        .bg-primary {
            background-color: #007bff !important;
        }

        .text-primary {
            color: #007bff !important;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .text-success {
            color: #28a745 !important;
        }
    </style>
</head>
<body style="background-color: #686D76;">
    <div class="container">
        <a href="../home/beranda.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i></a>
        <h2 class="text-center mb-4">Informasi Pembayaran</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Detail Pengguna</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Nama Pengguna: <?php echo htmlspecialchars($username); ?></li>
                    <li class="list-group-item">No Telepon: <?php echo htmlspecialchars($phone); ?></li>
                    <li class="list-group-item">Email: <?php echo htmlspecialchars($email); ?></li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Status Pembayaran</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Status</th>
                            <th>Tanggal Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($daftar_bulan as $bulan): ?>
                            <tr>
                                <td><?php echo $bulan; ?></td>
                                <td>
                                    <?php if (array_key_exists($bulan, $bulan_bayar)): ?>
                                        <span class="badge bg-success">Sudah dibayar</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Belum dibayar</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo array_key_exists($bulan, $bulan_bayar) ? htmlspecialchars($bulan_bayar[$bulan]) : '-'; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
