<?php
session_start();
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $tanggal_lulus = $_POST['tanggal_lulus'];
    $alamat = $_POST['alamat'];
    $phone = $_POST['phone'];

    // Validasi data (opsional)
    if (empty($username) || empty($email) || empty($tanggal_lulus) || empty($alamat) || empty($phone)) {
        echo "Semua field harus diisi!";
        exit;
    }

    // Query untuk menyimpan data
    $sql = "INSERT INTO siswa_lulus (username, email, tanggal_lulus, alamat, phone) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss', $username, $email, $tanggal_lulus, $alamat, $phone);

    if ($stmt->execute()) {
        echo "Simpan data berhasil";
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit; // Prevent further PHP processing after data save
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Input Siswa Lulus</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand {
            color: #fff;
            font-weight: bold;
        }
        .navbar-nav .nav-link {
            color: #fff;
        }
        .navbar-nav .nav-link:hover {
            color: #f8f9fa;
        }
    </style>
</head>
<body>
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
            <a class="dropdown-item" href="./status_admin.php">Status Pembayaran</a>
            <a class="dropdown-item" href="./siswaLulus_admin.php">Siswa Lulus</a>
            <a class="dropdown-item" href="./omset.php">Pemasukan</a>
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
    <h2>Data Siswa yang Sudah Ditambahkan</h2>
    
    <!-- Button to open the modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#siswaModal">
      Tambah Data Siswa
    </button>

    <!-- Modal -->
    <div class="modal fade" id="siswaModal" tabindex="-1" role="dialog" aria-labelledby="siswaModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="siswaModalLabel">Form Input Data Siswa Lulus</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formSiswa">
                <div class="form-group">
                    <label for="username">Nama User:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_lulus">Tanggal Lulus:</label>
                    <input type="date" class="form-control" id="tanggal_lulus" name="tanggal_lulus" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat:</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="phone">Nomor Telepon:</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php
    // Query untuk mengambil data siswa yang sudah ditambahkan
    $sql = "SELECT id, username, email, tanggal_lulus, alamat, phone FROM siswa_lulus";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table class='table table-striped'>";
        echo "<thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Tahun Lulus</th><th>Alamat</th><th>Nomor Telepon</th></tr></thead>";
        echo "<tbody>";
        // Output data dari setiap baris
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["username"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["tanggal_lulus"] . "</td>";
            echo "<td>" . $row["alamat"] . "</td>";
            echo "<td>" . $row["phone"] . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p class='no-data'>Tidak ada data siswa yang sudah ditambahkan.</p>";
    }

    $conn->close();
    ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js"></script>
<script>
document.getElementById('formSiswa').addEventListener('submit', function(event) {
    event.preventDefault(); // Mencegah form dari submit secara default

    // Lakukan submit form menggunakan Ajax atau fetch
    fetch('', { // Kirim data ke halaman yang sama
        method: 'POST',
        body: new FormData(this)
    }).then(response => response.text())
    .then(data => {
        if (data === "Simpan data berhasil") {
            // Tampilkan alert SweetAlert2
            Swal.fire({
                icon: 'success',
                title: 'SISWA LULUS BERHASIL DITAMBAHKAN',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                $('#siswaModal').modal('hide'); // Tutup modal
                location.reload(); // Reload halaman untuk menampilkan data terbaru
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: data
            });
        }
    }).catch(error => {
        console.error('Error:', error);
    });
});
</script>
</body>
</html>
