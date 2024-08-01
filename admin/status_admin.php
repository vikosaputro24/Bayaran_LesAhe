<?php
// Koneksi dan session start
include '../connection.php';
session_start();


// Query untuk mendapatkan semua pengguna dan status pembayaran mereka
$sql_users = "SELECT u.username, u.phone, u.email, b.bulan_pembayaran
              FROM users u
              LEFT JOIN bayar b ON u.username = b.username";

// Cek apakah ada parameter pencarian
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = $_GET['search'];
    $sql_users .= " WHERE u.username LIKE '%$search_term%'";
}

$sql_users .= " ORDER BY u.username, b.bulan_pembayaran";
$result_users = mysqli_query($conn, $sql_users);

// Mengatur data pengguna dan pembayaran ke dalam array
$users = [];
while ($row = mysqli_fetch_assoc($result_users)) {
    $username = $row['username'];
    if (!isset($users[$username])) {
        $users[$username] = [
            'phone' => $row['phone'],
            'email' => $row['email'],
            'bulan_bayar' => []
        ];
    }
    if ($row['bulan_pembayaran']) {
        $users[$username]['bulan_bayar'][] = $row['bulan_pembayaran'];
    }
}

// Daftar bulan yang tersedia
$daftar_bulan = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom styles -->
  <style>
            .detail-table {
            display: none;
        }
        .status-sudah {
            background-color: #d4edda; /* Hijau */
        }
        .status-belum {
            background-color: #f8d7da; /* Merah */
        }
        
    .navbar-brand {
      font-size: 1.5rem; /* Ukuran teks navbar brand */
    }

    .navbar-nav .nav-link {
      font-size: 1.2rem; /* Ukuran teks link navbar */
    }

    /* CSS untuk tengah layar */
    .center-screen {
      height: 100vh; /* Set tinggi 100% dari viewport */
      display: flex; /* Gunakan flexbox */
      justify-content: center; /* Posisikan konten secara horizontal di tengah */
      align-items: center; /* Posisikan konten secara vertikal di tengah */
    }
  </style>
</head>
<body class="bg-secondary">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Anak Hebat</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <a class="nav-link" href="./beranda.php">Beranda <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="./registrasi_user.php">Data Pengguna</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Data Siswa
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="../home/pembayaran_admin.php">Riwayat Pembayaran</a>
            <a class="dropdown-item" href="../admin/statusAdmin.php">Status Pembayaran</a>
            <a class="dropdown-item" href="./siswaLulus_admin.php">Siswa Lulus</a>
            <a class="dropdown-item" href="../admin/omset.php">Pemasukan</a>
          </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="./pengumuman.php">Pengumuman</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./penilaian_admin.php">Penilaian</a>
        </li>
        <!-- Add more menu items as needed -->
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="./login.php">Keluar</a>
        </li>
      </ul>
    </div>
  </nav>
<div class="container">
    <h2 class="mt-4 text-white">Informasi Pembayaran Pengguna</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Daftar Pengguna</h5>
            <?php foreach ($users as $username => $info): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Nama Pembayar: <?php echo htmlspecialchars($username); ?></h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">No Telepon: <?php echo htmlspecialchars($info['phone']); ?></li>
                            <li class="list-group-item">Email: <?php echo htmlspecialchars($info['email']); ?></li>
                        </ul>
                        <br>
                        <button class="btn btn-primary toggle-details" data-username="<?php echo htmlspecialchars($username); ?>">
                            <i class="bi bi-eye"></i> Lihat Informasi Pembayaran
                        </button>
                        <div class="detail-table" id="details-<?php echo htmlspecialchars($username); ?>">
                            <h5 class="card-title mt-4">Bulan Pembayaran</h5>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Bulan</th>
                                    <th class="text-center">Status Pembayaran</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($daftar_bulan as $bulan): ?>
                                    <tr>
                                        <td class="text-center"><?php echo $bulan; ?></td>
                                        <td class="text-center <?php echo in_array($bulan, $info['bulan_bayar']) ? 'status-sudah' : 'status-belum'; ?>">
                                            <?php echo in_array($bulan, $info['bulan_bayar']) ? 'Sudah dibayar' : 'Belum dibayar'; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- JavaScript untuk Bootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.toggle-details').forEach(button => {
        button.addEventListener('click', function() {
            const username = this.getAttribute('data-username');
            const details = document.getElementById('details-' + username);
            details.style.display = details.style.display === 'none' ? 'block' : 'none';
        });
    });
</script>
</body>
</html>
