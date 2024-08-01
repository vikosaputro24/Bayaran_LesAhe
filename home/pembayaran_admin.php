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
<body class="bg-white">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Anak Hebat</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <a class="nav-link" href="../admin/beranda.php">Beranda <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../admin/registrasi_user.php">Data Pengguna</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Data Siswa
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="./pembayaran_admin.php">Riwayat Pembayaran</a>
            <a class="dropdown-item" href="../admin/status_admin.php">Status Pembayaran</a>
            <a class="dropdown-item" href="../admin/siswa_lulus.php">Siswa Lulus</a>
            <a class="dropdown-item" href="../admin/omset.php">Pemasukan</a>
          </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../home/wisuda_admin.php">Data Wisuda</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="./pengumuman.php">Pengumuman</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../admin/penilaian_admin.php">Penilaian</a>
        </li>
        <!-- Add more menu items as needed -->
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="../admin/login_admin.php">Keluar</a>
        </li>
      </ul>
    </div>
  </nav>
<div class="container mt-5">
  <h2>Riwayat Pembayaran</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Nama</th>
        <th>Nomor Telepon</th>
        <th>Email</th>
        <th>Alamat</th>
        <th>Kelas</th>
        <th>Bulan Pembayaran</th>
        <th>Jumlah Pembayaran</th>
        <th>Metode Pembayaran</th>
        <th>Aksi</th>
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

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          if (isset($_POST['edit'])) {
            $bayar_id = $_POST['bayar_id'];
            $username = $_POST['username'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $alamat = $_POST['alamat'];
            $kelas = $_POST['kelas'];
            $bulan_pembayaran = $_POST['bulan_pembayaran'];
            $jumlah_pembayaran = $_POST['jumlah_pembayaran'];
            $metode_pembayaran = $_POST['metode_pembayaran'];

            $query = "UPDATE bayar SET username='$username', phone='$phone', email='$email', alamat='$alamat', kelas='$kelas', bulan_pembayaran='$bulan_pembayaran', jumlah_pembayaran='$jumlah_pembayaran', metode_pembayaran='$metode_pembayaran' WHERE bayar_id='$bayar_id'";

            if ($conn->query($query) === TRUE) {
              echo "<div class='alert alert-success'>Data berhasil diperbarui.</div>";
            } else {
              echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
            }
          }

          if (isset($_POST['delete'])) {
            $bayar_id = $_POST['bayar_id'];

            $query = "DELETE FROM bayar WHERE bayar_id='$bayar_id'";

            if ($conn->query($query) === TRUE) {
              echo "<div class='alert alert-success'>Data berhasil dihapus.</div>";
            } else {
              echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
            }
          }
        }

        $query = "SELECT * FROM bayar";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $totalPerBulan[$row['bulan_pembayaran']] += $row['jumlah_pembayaran'];

            echo "<tr>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['alamat'] . "</td>";
            echo "<td>" . $row['kelas'] . "</td>";
            echo "<td>" . $row['bulan_pembayaran'] . "</td>";
            echo "<td>" . $row['jumlah_pembayaran'] . "</td>";
            echo "<td>" . $row['metode_pembayaran'] . "</td>";
            echo "<td>";
            echo "<a href='#' class='btn btn-primary' data-toggle='modal' data-target='#paymentModal{$row['bayar_id']}'>Lihat</a> ";
            echo "<a href='#' class='btn btn-warning' data-toggle='modal' data-target='#editModal{$row['bayar_id']}'>Ubah</a> ";
            echo "<form method='post' style='display:inline-block;'>";
            echo "<input type='hidden' name='bayar_id' value='{$row['bayar_id']}'>";
            echo "<button type='submit' name='delete' class='btn btn-danger' onclick='return confirm(\"Apakah kamu yakin ingin menghapus data ini?\")'>Hapus</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";

            echo "<div class='modal fade' id='paymentModal{$row['bayar_id']}' tabindex='-1' role='dialog' aria-labelledby='paymentModalLabel' aria-hidden='true'>";
            echo "<div class='modal-dialog' role='document'>";
            echo "<div class='modal-content'>";
            echo "<div class='modal-header'>";
            echo "<h5 class='modal-title' id='paymentModalLabel'>Bukti Pembayaran</h5>";
            echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
            echo "<span aria-hidden='true'>&times;</span>";
            echo "</button>";
            echo "</div>";
            echo "<div class='modal-body text-center'>";
            echo "<img src='{$row['bukti_pembayaran']}' class='img-fluid' alt='Payment Proof'>";
            echo "</div>";
            echo "<div class='modal-footer'>";
            echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Tutup</button>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";

            echo "<div class='modal fade' id='editModal{$row['bayar_id']}' tabindex='-1' role='dialog' aria-labelledby='editModalLabel' aria-hidden='true'>";
            echo "<div class='modal-dialog' role='document'>";
            echo "<div class='modal-content'>";
            echo "<div class='modal-header'>";
            echo "<h5 class='modal-title' id='editModalLabel'>Edit Pembayaran</h5>";
            echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
            echo "<span aria-hidden='true'>&times;</span>";
            echo "</button>";
            echo "</div>";
            echo "<div class='modal-body'>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='bayar_id' value='{$row['bayar_id']}'>";
            echo "<div class='form-group'>";
            echo "<label>Nama</label>";
            echo "<input type='text' name='username' class='form-control' value='{$row['username']}' required>";
            echo "</div>";
            echo "<div class='form-group'>";
            echo "<label>Nomor Telepon</label>";
            echo "<input type='text' name='phone' class='form-control' value='{$row['phone']}' required>";
            echo "</div>";
            echo "<div class='form-group'>";
            echo "<label>Email</label>";
            echo "<input type='email' name='email' class='form-control' value='{$row['email']}' required>";
            echo "</div>";
            echo "<div class='form-group'>";
            echo "<label>Alamat</label>";
            echo "<input type='text' name='alamat' class='form-control' value='{$row['alamat']}' required>";
            echo "</div>";
            echo "<div class='form-group'>";
            echo "<label>Kelas</label>";
            echo "<input type='text' name='kelas' class='form-control' value='{$row['kelas']}' required>";
            echo "</div>";
            echo "<div class='form-group'>";
            echo "<label>Bulan Pembayaran</label>";
            echo "<input type='text' name='bulan_pembayaran' class='form-control' value='{$row['bulan_pembayaran']}' required>";
            echo "</div>";
            echo "<div class='form-group'>";
            echo "<label>Jumlah Pembayaran</label>";
            echo "<input type='text' name='jumlah_pembayaran' class='form-control' value='{$row['jumlah_pembayaran']}' required>";
            echo "</div>";
            echo "<div class='form-group'>";
            echo "<label>Metode Pembayaran</label>";
            echo "<input type='text' name='metode_pembayaran' class='form-control' value='{$row['metode_pembayaran']}' required>";
            echo "</div>";
            echo "<button type='submit' name='edit' class='btn btn-primary'>Perbarui</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
          }
        } else {
          echo "<tr><td colspan='9'>Tidak ada data ditemukan</td></tr>";
        }

        $conn->close();
      ?>
    </tbody>
  </table>

  <h3>Total Pembayaran per Bulan</h3>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Bulan</th>
        <th>Total Pembayaran</th>
      </tr>
    </thead>
    <tbody>
      <?php
        foreach ($totalPerBulan as $bulan => $total) {
          echo "<tr>";
          echo "<td>{$bulan}</td>";
          echo "<td>{$total}</td>";
          echo "</tr>";
        }
      ?>
    </tbody>
  </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
