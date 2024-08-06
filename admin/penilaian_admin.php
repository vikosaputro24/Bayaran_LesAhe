<?php
session_start();
include '../connection.php';

// Pagination settings
$results_per_page = 10; // Number of results per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;

// Handle form submissions for creating, updating, and deleting ratings
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $username = $_POST['username'];
        $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
        $comments = trim($_POST['comments']);

        if ($rating > 0 && $rating <= 5 && !empty($comments)) {
            $stmt = $conn->prepare("INSERT INTO user_ratings (username, rating, comments) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $username, $rating, $comments);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Penilaian berhasil disimpan!";
            } else {
                $_SESSION['error_message'] = "Gagal menyimpan penilaian.";
            }

            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Pastikan rating dan komentar telah diisi dengan benar.";
        }
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
        $comments = trim($_POST['comments']);

        if ($rating > 0 && $rating <= 5 && !empty($comments)) {
            $stmt = $conn->prepare("UPDATE user_ratings SET rating = ?, comments = ? WHERE id = ?");
            $stmt->bind_param("isi", $rating, $comments, $id);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Penilaian berhasil diperbarui!";
            } else {
                $_SESSION['error_message'] = "Gagal memperbarui penilaian.";
            }

            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Pastikan rating dan komentar telah diisi dengan benar.";
        }
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM user_ratings WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Penilaian berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus penilaian.";
        }

        $stmt->close();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch total number of ratings for pagination
$total_sql = "SELECT COUNT(id) AS total FROM user_ratings";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $results_per_page);

// Fetch ratings with pagination
$sql = "SELECT id, username, rating, comments FROM user_ratings LIMIT $start_from, $results_per_page";
$result = $conn->query($sql);
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
        <li class="nav-item">
          <a class="nav-link" href="./beranda.php">Beranda</a>
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
        <li class="nav-item active">
          <a class="nav-link" href="./penilaian_admin.php">Penilaian <span class="sr-only">(current)</span></a>
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
    <h2 class="text-center mb-4">Daftar Penilaian</h2>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createModal">Tambah Penilaian</button>
    
    <!-- Success/Error Toasts -->
    <div class="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 1055;">
        <?php if (isset($_SESSION['success_message'])) : ?>
            <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?= htmlspecialchars($_SESSION['success_message']) ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])) : ?>
            <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?= htmlspecialchars($_SESSION['error_message']) ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
    </div>

    <!-- Ratings Table -->
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nama</th>
            <th>Penilaian</th>
            <th>Komentar</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['rating']) ?></td>
                    <td><?= htmlspecialchars($row['comments']) ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal" data-id="<?= htmlspecialchars($row['id']) ?>" data-username="<?= htmlspecialchars($row['username']) ?>" data-rating="<?= htmlspecialchars($row['rating']) ?>" data-comments="<?= htmlspecialchars($row['comments']) ?>">Ubah</button>
                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" data-id="<?= htmlspecialchars($row['id']) ?>">Hapus</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">Tidak ada penilaian.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<!-- Create Rating Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Penilaian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="createUsername">Nama</label>
                        <input type="text" class="form-control" name="username" id="createUsername" required>
                    </div>
                    <div class="form-group">
                        <label for="createRating">Penilaian</label>
                        <input type="number" class="form-control" name="rating" id="createRating" min="1" max="5" required>
                    </div>
                    <div class="form-group">
                        <label for="createComments">Komentar</label>
                        <textarea class="form-control" name="comments" id="createComments" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" name="create" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Rating Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Penilaian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="">
                <input type="hidden" name="id" id="editId">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editUsername">Nama</label>
                        <input type="text" class="form-control" name="username" id="editUsername" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editRating">Penilaian</label>
                        <input type="number" class="form-control" name="rating" id="editRating" min="1" max="5" required>
                    </div>
                    <div class="form-group">
                        <label for="editComments">Komentar</label>
                        <textarea class="form-control" name="comments" id="editComments" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" name="update" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Rating Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Hapus Penilaian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="">
                <div class="modal-body">
                    <input type="hidden" name="id" id="deleteId">
                    <p>Apakah anda yakin ingin menghapus penilaian ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" name="delete" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

  <!-- Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id');
        var username = button.data('username');
        var rating = button.data('rating');
        var comments = button.data('comments');
        
        var modal = $(this);
        modal.find('#editId').val(id);
        modal.find('#editUsername').val(username);
        modal.find('#editRating').val(rating);
        modal.find('#editComments').val(comments);
    });

    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id');
        
        var modal = $(this);
        modal.find('#deleteId').val(id);
    });
</script>
</body>
</html>

<?php $conn->close(); ?>
