<?php
session_start();
include '../connection.php';

$sql = "SELECT username FROM users LIMIT 1";
$result = $conn->query($sql);
$userName = "";
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $userName = $row["username"];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $userName;
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

  header("Location: " . $_SERVER['REQUEST_URI']);
  exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ahe</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>

<body class="d-flex align-items-center justify-content-center vh-100" style="background-image: url('../assets/p.jpg'); background-size: cover; background-position: center;">
  <div class="container mt-5" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); padding: 20px; max-width: 600px; margin: auto;">
    <div class="d-flex align-items-center mb-4">
      <a href="./beranda.php" class="btn btn-link text-dark"><i class="fas fa-arrow-left"></i></a>
      <h2 class="text-center w-100 m-0">Penilaian Anda</h2>
    </div>
    <div class="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 1055;">
      <?php if (isset($_SESSION['success_message'])) : ?>
        <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body">
              <?= htmlspecialchars($_SESSION['success_message']) ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close" style="font-size: 1.25rem; color: white; opacity: 1; background: none; border: none; cursor: pointer;">
              <i class="fas fa-times"></i>
            </button>
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
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close" style="font-size: 1.25rem; color: white; opacity: 1; background: none; border: none; cursor: pointer;">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <?php unset($_SESSION['error_message']); ?>
      <?php endif; ?>
    </div>

    <form id="ratingForm" method="post" action="">
      <div class="form-group">
        <label for="userName">Nama</label>
        <input type="text" class="form-control" name="username" id="username" value="<?= htmlspecialchars($userName); ?>" readonly>
      </div>
      <div class="form-group">
        <label for="userRating">Rating</label>
        <div class="rating" style="display: flex; justify-content: space-around; font-size: 1.5em;">
          <?php for ($i = 1; $i <= 5; $i++) : ?>
            <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" style="display: none;">
            <label for="star<?= $i ?>" title="<?= $i ?> star" style="color: lightgray; cursor: pointer; transition: color 0.2s;">&#9733;</label>
          <?php endfor; ?>
        </div>
      </div>
      <div class="form-group">
        <label for="userComments">Komentar</label>
        <textarea class="form-control" name="comments" id="userComments" rows="4" placeholder="Tulis komentar Anda di sini"></textarea>
      </div>
      <button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var toastElList = [].slice.call(document.querySelectorAll('.toast'));
      var toastList = toastElList.map(function(toastEl) {
        return new bootstrap.Toast(toastEl, {
          delay: 5000
        });
      });
      toastList.forEach(toast => toast.show());

      document.querySelectorAll('.rating label').forEach((label) => {
        label.addEventListener('mouseover', function() {
          var value = this.getAttribute('for').replace('star', '');
          for (var i = 1; i <= 5; i++) {
            document.querySelector('label[for="star' + i + '"]').style.color = i <= value ? 'gold' : 'lightgray';
          }
        });

        label.addEventListener('mouseout', function() {
          document.querySelectorAll('.rating label').forEach((lbl) => {
            lbl.style.color = 'lightgray';
          });
          var checkedInput = document.querySelector('.rating input:checked');
          if (checkedInput) {
            var value = checkedInput.getAttribute('id').replace('star', '');
            for (var i = 1; i <= value; i++) {
              document.querySelector('label[for="star' + i + '"]').style.color = 'gold';
            }
          }
        });

        label.addEventListener('click', function() {
          var input = document.getElementById(this.getAttribute('for'));
          input.checked = true;
        });
      });
    });
  </script>
</body>
</html>