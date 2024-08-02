<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, phone, email FROM users WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($username, $phone, $email);
$stmt->fetch();
$stmt->close();

$payment_success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $alamat = htmlspecialchars(trim($_POST['alamat']));
    $kelas = htmlspecialchars(trim($_POST['kelas']));
    $bulan_pembayaran = htmlspecialchars(trim($_POST['bulan_pembayaran']));
    $jumlah_pembayaran = filter_var($_POST['jumlah_pembayaran'], FILTER_VALIDATE_FLOAT);
    $metode_pembayaran = htmlspecialchars(trim($_POST['metode_pembayaran']));
    $bayar_id = uniqid('', true);
    $tanggal_pembayaran = date('Y-m-d H:i:s');

    if ($jumlah_pembayaran === false) {
        echo "Jumlah pembayaran tidak valid.";
        exit();
    }

    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        if (!mkdir($target_dir, 0777, true)) {
            echo "Gagal membuat direktori upload.";
            exit();
        }
    }

    $file_name = basename($_FILES["bukti_pembayaran"]["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["bukti_pembayaran"]["tmp_name"]);
    if ($check === false) {
        echo "File bukan gambar.";
        exit();
    }

    if (!in_array($imageFileType, ["jpg", "png"])) {
        echo "Hanya file JPG dan PNG yang diperbolehkan.";
        exit();
    }

    if (!move_uploaded_file($_FILES["bukti_pembayaran"]["tmp_name"], $target_file)) {
        echo "Gagal mengunggah file.";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO bayar (bayar_id, username, phone, email, alamat, kelas, bulan_pembayaran, tanggal_pembayaran, jumlah_pembayaran, metode_pembayaran, bukti_pembayaran) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $bayar_id, $username, $phone, $email, $alamat, $kelas, $bulan_pembayaran, $tanggal_pembayaran, $jumlah_pembayaran, $metode_pembayaran, $target_file);

    if ($stmt->execute()) {
        $payment_success = true;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ahe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('../assets/p.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 90%; 
            max-width: 500px;
            overflow: hidden;
            margin: auto; 
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"],
        textarea,
        select {
            width: calc(100% - 20px); 
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="button"], input[type="submit"], .back-button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        input[type="button"]:hover, input[type="submit"]:hover, .back-button:hover {
            background-color: #218838;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .toast {
            visibility: hidden;
            max-width: 300px;;
            height: 50px;
            margin: auto;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 2px;
            position: fixed;
            z-index: 1;
            left: 0; right: 0;
            top: 30px;
            font-size: 17px;
            white-space: nowrap;
        }
        .toast #desc {
            color: #fff;
            padding: 16px;
            overflow: hidden;
            white-space: nowrap;
        }
        .toast.show {
            visibility: visible;
            -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }
        @-webkit-keyframes fadein {
            from {bottom: 0; opacity: 0;}
            to {bottom: 30px; opacity: 1;}
        }
        @keyframes fadein {
            from {bottom: 0; opacity: 0;}
            to {bottom: 30px; opacity: 1;}
        }
        @-webkit-keyframes fadeout {
            from {bottom: 30px; opacity: 1;}
            to {bottom: 0; opacity: 0;}
        }
        @keyframes fadeout {
            from {bottom: 30px; opacity: 1;}
            to {bottom: 0; opacity: 0;}
        }
        .thumbnail {
            display: block;
            margin: auto;
            width: 100%;
            max-width: 300px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <h2>Bayar</h2>
        <form id="paymentForm" action="pembayaran.php" method="POST" enctype="multipart/form-data">
            <label>Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" class="readonly" readonly><br>
            <label>No Telpon:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" class="readonly" readonly><br>
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="readonly" readonly><br>
            <label>Alamat:</label>
            <textarea name="alamat" required></textarea><br>
            <label>Kelas:</label>
            <select name="kelas" id="kelas" required onchange="updateTotal()">
                <option value="" disabled selected>Pilih Kelas</option>
                <option value="Regular" data-harga="200000">Regular</option>
                <option value="Platinum" data-harga="400000">Platinum</option>
            </select><br>
            <label>Bulan Pembayaran:</label>
            <select name="bulan_pembayaran" required>
            <option value="" disabled selected>Pilih Bulan</option>
                <option value="Januari">Januari</option>
                <option value="Februari">Februari</option>
                <option value="Maret">Maret</option>
                <option value="April">April</option>
                <option value="Mei">Mei</option>
                <option value="Juni">Juni</option>
                <option value="Juli">Juli</option>
                <option value="Agustus">Agustus</option>
                <option value="September">September</option>
                <option value="Oktober">Oktober</option>
                <option value="November">November</option>
                <option value="Desember">Desember</option>
            </select><br>
            <label>Jumlah Pembayaran:</label>
            <input type="number" name="jumlah_pembayaran" id="jumlah_pembayaran" required readonly><br>
            <input type="button" value="Bayar" onclick="showModal()">
            <input type="button" class="back-button" value="Kembali" onclick="history.back()">
        </form>
    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Konfirmasi Pembayaran</h2>
            <p>Total Pembayaran: <span id="totalBayar"></span></p>
            <img id="bukti_pembayaran_modal" class="thumbnail" src="" onclick="openImageModal()" alt="Bukti Pembayaran">
            <form id="modalForm" action="pembayaran.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                <input type="hidden" name="alamat">
                <input type="hidden" name="kelas">
                <input type="hidden" name="bulan_pembayaran">
                <input type="hidden" name="jumlah_pembayaran">
                <label for="metode_pembayaran">Metode Pembayaran:</label>
                <select name="metode_pembayaran" required>
                <option value="" disabled selected>Pilih Metode</option>
                    <option value="BCA (214153542)">BCA (214153542)</option>
                    <option value="MANDIRI (2423265458697)">MANDIRI (2423265458697)</option>
                    <option value="BANK DKI (3005842644)">BANK DKI (3005842644)</option>
                </select><br>
                <label for="bukti_pembayaran">Unggah Bukti Pembayaran (JPG/PNG):</label>
                <input type="file" name="bukti_pembayaran" required accept=".jpg, .jpeg, .png"><br>
                <input type="submit" value="Konfirmasi Pembayaran">
            </form>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeImageModal()">&times;</span>
        <img class="modal-content" id="bukti_pembayaran_full" src="">
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <div id="desc">Pembayaran Berhasil!</div>
    </div>

    <script>
    function updateTotal() {
        const kelasSelect = document.getElementById('kelas');
        const selectedOption = kelasSelect.options[kelasSelect.selectedIndex];
        const harga = selectedOption.getAttribute('data-harga');
        document.getElementById('jumlah_pembayaran').value = harga;
    }

    function showModal() {
        var modal = document.getElementById("myModal");
        modal.style.display = "block";

        var jumlahPembayaran = document.getElementById("jumlah_pembayaran").value;
        document.getElementById("totalBayar").textContent = parseInt(jumlahPembayaran).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });

        document.getElementById("modalForm").elements["alamat"].value = document.getElementById("paymentForm").elements["alamat"].value;
        document.getElementById("modalForm").elements["kelas"].value = document.getElementById("paymentForm").elements["kelas"].value;
        document.getElementById("modalForm").elements["bulan_pembayaran"].value = document.getElementById("paymentForm").elements["bulan_pembayaran"].value;
        document.getElementById("modalForm").elements["jumlah_pembayaran"].value = jumlahPembayaran;
    }

    function closeModal() {
        var modal = document.getElementById("myModal");
        modal.style.display = "none";
    }

    function openImageModal() {
        var modal = document.getElementById("imageModal");
        var img = document.getElementById("bukti_pembayaran_modal");
        var modalImg = document.getElementById("bukti_pembayaran_full");
        modal.style.display = "block";
        modalImg.src = img.src;
    }

    function closeImageModal() {
        var modal = document.getElementById("imageModal");
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        var modal = document.getElementById("myModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
        var imgModal = document.getElementById("imageModal");
        if (event.target == imgModal) {
            imgModal.style.display = "none";
        }
    }

    function showToast() {
        var toast = document.getElementById("toast");
        toast.className = "toast show";
        setTimeout(function(){ 
            toast.className = toast.className.replace("show", ""); 
            window.location.href = './beranda.php';
        }, 3000);
    }

    window.onload = function() {
        <?php if ($payment_success): ?>
            showToast();
        <?php endif; ?>
    }
    </script>
</body>
</html>
