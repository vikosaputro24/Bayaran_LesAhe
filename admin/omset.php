<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ahe</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-secondary">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Anak Hebat</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="./beranda.php">Beranda</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="./registrasi_user.php">Data Pengguna</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Data Siswa <span class="sr-only">(current)</span>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="../home/pembayaran_admin.php">Riwayat Pembayaran</a>
            <a class="dropdown-item" href="./status_admin.php">Status Pembayaran</a>
            <a class="dropdown-item" href="./siswaLulus_admin.php">Siswa Lulus</a>
            <a class="dropdown-item" href="./omset.php">Pemasukan</a>
          </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../home/wisuda_admin.php">Data Wisuda</a>
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
<div class="container mt-5">
  <h2 class="text-white">Total Pembayaran per Bulan</h2>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th class="text-white">Bulan</th>
        <th class="text-white">Total Pembayaran</th>
      </tr>
    </thead>
    <tbody>
      <?php
        session_start();
        include '../connection.php';

        $totalPerBulan = [
            'Januari' => 0,
            'Februari' => 0,
            'Maret' => 0,
            'April' => 0,
            'Mei' => 0,
            'Juni' => 0,
            'Juli' => 0,
            'Agustus' => 0,
            'September' => 0,
            'Oktober' => 0,
            'November' => 0,
            'Desember' => 0
        ];

        $query = "SELECT bulan_pembayaran, SUM(jumlah_pembayaran) AS total_pembayaran FROM bayar GROUP BY bulan_pembayaran";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $totalPerBulan[$row['bulan_pembayaran']] = $row['total_pembayaran'];
            }
        }

        // Hitung total keseluruhan
        $totalKeseluruhan = array_sum($totalPerBulan);

        $conn->close();

        foreach ($totalPerBulan as $bulan => $total) {
            echo "<tr><td class='text-white'>$bulan</td><td class='text-white'>$total</td></tr>";
        }

        echo "<tr><td class='text-white'><strong>Total Keseluruhan</strong></td><td class='text-white'><strong>$totalKeseluruhan</strong></td></tr>";
      ?>
    </tbody>
  </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
