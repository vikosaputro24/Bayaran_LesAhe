<?php
session_start();

// Cek apakah pengguna telah login
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php"); // Arahkan ke halaman login jika tidak login
    exit();
}

// Hapus pesan kesalahan jika ada
$error = '';
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
    .jumbotron {
      background-color: #f8f9fa; /* Warna latar belakang */
      padding: 3rem 2rem; /* Padding di dalam jumbotron */
      margin-bottom: 2rem; /* Jarak ke bagian bawah */
      border-radius: 0.5rem; /* Sudut rounded */
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); /* Shadow */
    }

    .jumbotron h1 {
      font-size: 3.5rem; /* Ukuran teks judul */
      color: #343a40; /* Warna teks judul */
    }

    .jumbotron p {
      font-size: 1.5rem; /* Ukuran teks deskripsi */
      color: #6c757d; /* Warna teks deskripsi */
    }

    .jumbotron hr {
      border-top-color: #007bff; /* Warna garis pemisah */
      margin: 2rem 0; /* Margin di atas dan bawah garis */
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

  <div class="center-screen">
    <div class="jumbotron text-center">
      <h1 class="display-4">Selamat Datang di Admin Anak Hebat</h1>
      <p class="lead">Anda memiliki akses penuh untuk mengelola data dan informasi penting.</p>
      <hr class="my-4">
      <p>Gunakan menu di atas untuk navigasi dan mengelola berbagai fitur admin.</p>
    </div>
  </div>

  <!-- Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


