<?php
session_start();
include '../connection.php';

// Fetch all records
$result = $conn->query("SELECT * FROM wisuda");

// Handle form submission for Create and Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $paket = $_POST['paket'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $jumlah_pembayaran = $_POST['jumlah_pembayaran'];
    $bukti_pembayaran = $_FILES['bukti_pembayaran'];

    if ($action == 'create') {
        if ($username && $email && $phone && $paket && $metode_pembayaran && $jumlah_pembayaran && $bukti_pembayaran['name']) {
            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir . basename($bukti_pembayaran['name']);

            if (move_uploaded_file($bukti_pembayaran['tmp_name'], $uploadFile)) {
                $stmt = $conn->prepare("INSERT INTO wisuda (username, email, phone, paket, metode_pembayaran, jumlah_pembayaran, bukti_pembayaran) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssss", $username, $email, $phone, $paket, $metode_pembayaran, $jumlah_pembayaran, $uploadFile);
                $stmt->execute();
                $stmt->close();
            }
        }
    } elseif ($action == 'edit') {
        $id = $_POST['id'];
        $existingFile = $_POST['existing_file'];

        if ($bukti_pembayaran['name']) {
            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir . basename($bukti_pembayaran['name']);
            move_uploaded_file($bukti_pembayaran['tmp_name'], $uploadFile);
        } else {
            $uploadFile = $existingFile;
        }

        $stmt = $conn->prepare("UPDATE wisuda SET username=?, email=?, phone=?, paket=?, metode_pembayaran=?, jumlah_pembayaran=?, bukti_pembayaran=? WHERE id=?");
        $stmt->bind_param("sssssssi", $username, $email, $phone, $paket, $metode_pembayaran, $jumlah_pembayaran, $uploadFile, $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM wisuda WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ahe</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body style="font-family: 'Roboto', sans-serif; background: #f8f9fa; padding: 20px;">
    <div class="container">
        <div class="bg-light p-4 rounded shadow-sm">
            <div class="d-flex align-items-center mb-4">
                <a href="../admin/beranda.php" class="btn btn-link text-dark"><i class="fas fa-arrow-left"></i></a>
                <h2 class="text-center w-100 m-0">Pembayaran Wisuda</h2>
            </div>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Tambah Pembayaran</button>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Nomor Telepon</th>
                        <th>Paket</th>
                        <th>Metode Pembayaran</th>
                        <th>Jumlah Pembayaran</th>
                        <th>Bukti Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) : ?>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['phone']; ?></td>
                                <td><?php echo $row['paket']; ?></td>
                                <td><?php echo $row['metode_pembayaran']; ?></td>
                                <td><?php echo $row['jumlah_pembayaran']; ?></td>
                                <td><a href="<?php echo $row['bukti_pembayaran']; ?>" target="_blank">Lihat Bukti</a></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">Edit</button>
                                    <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini?')">Delete</a>
                                </td>
                            </tr>
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?php echo $row['id']; ?>">Edit Pembayaran</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="action" value="edit">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <input type="hidden" name="existing_file" value="<?php echo $row['bukti_pembayaran']; ?>">
                                                <div class="mb-3">
                                                    <label for="username" class="form-label">Nama Lengkap</label>
                                                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Nama Lengkap ..." required value="<?php echo $row['username']; ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email ..." required value="<?php echo $row['email']; ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="phone" class="form-label">Nomor Telepon</label>
                                                    <input type="number" class="form-control" id="phone" name="phone" placeholder="Masukkan Nomor Telepon ..." required value="<?php echo $row['phone']; ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="paket" class="form-label">Paket Wisuda</label>
                                                    <select class="form-select" id="paket<?php echo $row['id']; ?>" name="paket" required>
                                                        <option value="">Pilih Paket Wisuda</option>
                                                        <option value="standard" data-harga="500000" <?php if ($row['paket'] == 'standard') echo 'selected'; ?>>Paket Wisuda Standard - Rp. 500,000</option>
                                                        <option value="premium" data-harga="750000" <?php if ($row['paket'] == 'premium') echo 'selected'; ?>>Paket Wisuda Premium - Rp. 750,000</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                                                    <select class="form-select" id="metode_pembayaran" name="metode_pembayaran" required>
                                                        <option value="">Pilih Metode Pembayaran</option>
                                                        <option value="BCA (214153542)" <?php if ($row['metode_pembayaran'] == 'BCA (214153542)') echo 'selected'; ?>>BCA (214153542)</option>
                                                        <option value="MANDIRI (2423265458697)" <?php if ($row['metode_pembayaran'] == 'MANDIRI (2423265458697)') echo 'selected'; ?>>MANDIRI (2423265458697)</option>
                                                        <option value="BANK DKI (3005842644)" <?php if ($row['metode_pembayaran'] == 'BANK DKI (3005842644)') echo 'selected'; ?>>BANK DKI (3005842644)</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="jumlah_pembayaran" class="form-label">Jumlah Pembayaran (IDR)</label>
                                                    <input type="number" class="form-control" id="jumlah_pembayaran<?php echo $row['id']; ?>" name="jumlah_pembayaran" placeholder="Total Pembayaran ..." readonly required value="<?php echo $row['jumlah_pembayaran']; ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran (JPG/PNG)</label>
                                                    <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" accept=".jpg, .jpeg, .png">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="9" class="text-center">No records found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Tambah Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create">
                        <div class="mb-3">
                            <label for="username" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Nama Lengkap ..." required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email ..." required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="number" class="form-control" id="phone" name="phone" placeholder="Masukkan Nomor Telepon ..." required>
                        </div>
                        <div class="mb-3">
                            <label for="paket" class="form-label">Paket Wisuda</label>
                            <select class="form-select" id="paket" name="paket" required>
                                <option value="">Pilih Paket Wisuda</option>
                                <option value="standard" data-harga="500000">Paket Wisuda Standard - Rp. 500,000</option>
                                <option value="premium" data-harga="750000">Paket Wisuda Premium - Rp. 750,000</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                            <select class="form-select" id="metode_pembayaran" name="metode_pembayaran" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="BCA (214153542)">BCA (214153542)</option>
                    <option value="MANDIRI (2423265458697)">MANDIRI (2423265458697)</option>
                    <option value="BANK DKI (3005842644)">BANK DKI (3005842644)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_pembayaran" class="form-label">Jumlah Pembayaran (IDR)</label>
                            <input type="number" class="form-control" id="jumlah_pembayaran" name="jumlah_pembayaran" placeholder="Total Pembayaran ..." readonly required>
                        </div>
                        <div class="mb-3">
                            <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran (JPG/PNG)</label>
                            <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" accept=".jpg, .jpeg, .png" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('select[id^="paket"]').forEach(select => {
            select.addEventListener('change', function() {
                var harga = this.options[this.selectedIndex].getAttribute('data-harga');
                this.closest('.modal-body').querySelector('input[name="jumlah_pembayaran"]').value = harga;
            });
        });

        document.getElementById('paket').addEventListener('change', function() {
            var harga = this.options[this.selectedIndex].getAttribute('data-harga');
            document.getElementById('jumlah_pembayaran').value = harga;
        });
    </script>
</body>

</html>