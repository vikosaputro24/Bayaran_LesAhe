<?php
session_start();
include '../connection.php';

// Ensure generate_uuid() is defined or included here
function generate_uuid() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}


// Handle Create, Update, Delete operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        // Create
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phone = $_POST['phone'];

        if (strlen($password) < 8) {
            $error = "Password must be at least 8 characters long.";
        } else {
            $uuid = generate_uuid();
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (user_id, username, email, phone, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $uuid, $username, $email, $phone, $hashedPassword);

            if ($stmt->execute()) {
                $success = "User added successfully.";
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    } elseif (isset($_POST['update'])) {
        // Update
        $user_id = $_POST['user_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ? WHERE user_id = ?");
        $stmt->bind_param("ssss", $username, $email, $phone, $user_id);

        if ($stmt->execute()) {
            $success = "User updated successfully.";
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['delete'])) {
        // Delete
        $user_id = $_POST['user_id'];
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);

        if ($stmt->execute()) {
            $success = "User deleted successfully.";
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$users = $conn->query("SELECT * FROM users")->fetch_all(MYSQLI_ASSOC);
$conn->close();
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
          <a class="nav-link" href="./penilaian_admin.php">Penilaian</a>
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
  <div class="container mt-4">
    <h1 class="mb-4">Kelola Pengguna</h1>

    <!-- Success/Error Message -->
    <?php if (isset($success)): ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
    <?php elseif (isset($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Create User Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Tambah</button>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user): ?>
          <tr>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['phone']); ?></td>
            <td>
              <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal" data-id="<?php echo htmlspecialchars($user['user_id']); ?>" data-username="<?php echo htmlspecialchars($user['username']); ?>" data-email="<?php echo htmlspecialchars($user['email']); ?>" data-phone="<?php echo htmlspecialchars($user['phone']); ?>">Ubah</button>
              <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php echo htmlspecialchars($user['user_id']); ?>">Hapus</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Create User Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createModalLabel">Tambah Pengguna</h5>
        </div>
        <form action="registrasi_user.php" method="POST">
          <input type="hidden" name="create" value="1">
          <div class="modal-body">
            <div class="mb-3">
              <label for="create-username" class="form-label">Nama</label>
              <input type="text" class="form-control" id="create-username" name="username" required>
            </div>
            <div class="mb-3">
              <label for="create-email" class="form-label">Email</label>
              <input type="email" class="form-control" id="create-email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="create-phone" class="form-label">Nomor Telepon</label>
              <input type="text" class="form-control" id="create-phone" name="phone" required>
            </div>
            <div class="mb-3">
              <label for="create-password" class="form-label">Password</label>
              <input type="password" class="form-control" id="create-password" name="password" required minlength="8">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Tambah</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Update User Modal -->
  <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateModalLabel">Ubah pengguna</h5>
        </div>
        <form action="registrasi_user.php" method="POST">
          <input type="hidden" name="update" value="1">
          <input type="hidden" id="update-user-id" name="user_id">
          <div class="modal-body">
            <div class="mb-3">
              <label for="update-username" class="form-label">Nama</label>
              <input type="text" class="form-control" id="update-username" name="username" required>
            </div>
            <div class="mb-3">
              <label for="update-email" class="form-label">Email</label>
              <input type="email" class="form-control" id="update-email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="update-phone" class="form-label">Nomor Telepon</label>
              <input type="text" class="form-control" id="update-phone" name="phone" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Ubah</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Delete User Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Hapus Pengguna</h5>
        </div>
        <form action="registrasi_user.php" method="POST">
          <input type="hidden" name="delete" value="1">
          <input type="hidden" id="delete-user-id" name="user_id">
          <div class="modal-body">
            <p>Apa kamu yakin ingin menghapus data ini?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-danger">Hapus</button>
          </div>
        </form>
      </div>
    </div>
  </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Populate Update Modal
    var updateModal = document.getElementById('updateModal');
    updateModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var userId = button.getAttribute('data-id');
      var username = button.getAttribute('data-username');
      var email = button.getAttribute('data-email');
      var phone = button.getAttribute('data-phone');

      var modal = bootstrap.Modal.getInstance(updateModal);
      modal._element.querySelector('#update-user-id').value = userId;
      modal._element.querySelector('#update-username').value = username;
      modal._element.querySelector('#update-email').value = email;
      modal._element.querySelector('#update-phone').value = phone;
    });

    // Populate Delete Modal
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var userId = button.getAttribute('data-id');

      var modal = bootstrap.Modal.getInstance(deleteModal);
      modal._element.querySelector('#delete-user-id').value = userId;
    });
  </script>
</body>
</html>
