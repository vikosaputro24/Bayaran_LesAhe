<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ahe</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        table th {
            background-color: #f2f2f2;
            text-align: left;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .no-data {
            text-align: center;
            color: #999;
            font-style: italic;
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
<body class="bg-secondary">

<div class="container">
    <h2>Data Siswa yang Sudah Lulus</h2>
    <div class="mb-3">
        <a href="../home/beranda.php" class="btn btn-primary">Kembali</a>
    </div>
    <?php
session_start();
include '../connection.php';

    // Query untuk mengambil data siswa yang sudah ditambahkan
    $sql = "SELECT id, username, email, tanggal_lulus, alamat, phone FROM siswa_lulus";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table class='table'>";
        echo "<thead class='thead-light'>";
        echo "<tr><th>ID</th><th>Nama</th><th>Email</th><th>Tanggal Lulus</th><th>Alamat</th><th>Nomor Telepon</th></tr>";
        echo "</thead>";
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
</body>
</html>
