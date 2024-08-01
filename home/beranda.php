<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anak Hebat</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-RlvE3NzMp+O8Jw+V2Cefw6Ht1PAd75c8ATx4pv3yEt5aNsefLOeFtLsW5GcK8u0keCZNP5tJFX0tW/aV7lBxtw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body style="height: 100%; margin: 0; font-family: Arial, sans-serif;">

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #343a40;">
        <a class="navbar-brand" href="#">Ahe.</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="../home/home.php">Beranda <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./pembayaran.php">Pembayaran</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#pengumuman">Pengumuman</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./penilaian.php">Penilaian</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./wisuda.php">Bayar Wisuda</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Akun
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="../user/profile.php">Profil</a>
                        <a class="dropdown-item" href="./status_pembayaran.php">Status Pembayaran</a>
                        <a class="dropdown-item" href="./siswa_lulus.php">Siswa Lulus</a>
                        <a class="dropdown-item" href="../home/logout.php">Keluar</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="bg-image" style="background-image: url('../assets/p.jpg'); height: calc(100vh - 56px); background-position: center; background-repeat: no-repeat; background-size: cover; position: relative; margin-top: 56px;">
        <div class="bg-text" style="background-color: rgba(0, 0, 0, 0.5); color: white; font-weight: bold; border: 3px solid #f1f1f1; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 80%; padding: 20px; text-align: center;">
            <h1>Selamat Datang di Website Pembayaran Anak Hebat</h1>
            <p>Website ini merupakan sebuah website pembayaran untuk anak-anak yang berada di les anak hebat atau Ahe.</p>
        </div>
    </div>

    <div class="about" style="padding: 50px 20px; text-align: center; background-color: white; border-bottom: 1px solid #ddd;">
        <img src="../assets/banner.jpg" alt="Profile Banner" style="max-width: 100%; height: auto; border-radius: 50%; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); border: 5px solid #343a40;">
        <h2 style="font-size: 2.5rem; margin-bottom: 20px; color: #343a40;">Tentang Website Ini</h2>
        <p style="font-size: 1.125rem; line-height: 1.8; color: #555; max-width: 800px; margin: 0 auto;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet.</p>
        <p style="font-size: 1.125rem; line-height: 1.8; color: #555; max-width: 800px; margin: 20px auto 0;">Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla.</p>
    </div>

    <footer class="bg-dark text-white" style="padding: 20px 0; text-align: center;">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-4">
                    <h5 style="margin-bottom: 20px;">KONTAK</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope"></i> <a href="mailto:info@example.com" class="text-white">info@example.com</a></li>
                        <li><i class="fas fa-phone"></i> <a href="tel:+123456789" class="text-white">+1 (234) 567-89</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 style="margin-bottom: 20px;">SOSIAL MEDIA</h5>
                    <ul class="list-inline">
                        <li class="list-inline-item"><a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a></li>
                        <li class="list-inline-item"><a href="#" class="text-white"><i class="fab fa-twitter"></i></a></li>
                        <li class="list-inline-item"><a href="#" class="text-white"><i class="fab fa-instagram"></i></a></li>
                        <li class="list-inline-item"><a href="#" class="text-white"><i class="fab fa-linkedin"></i></a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 style="margin-bottom: 20px;">ALAMAT</h5>
                    <p>123 Street, City, Country</p>
                </div>
            </div>
            <hr>
        </div>
    </footer>

    <div class="modal fade" id="pengumumanModal" tabindex="-1" aria-labelledby="pengumumanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pengumumanModalLabel">Pengumuman</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><?php echo file_get_contents("pengumuman.txt"); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="pengumumanModal" tabindex="-1" aria-labelledby="pengumumanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pengumumanModalLabel">Pengumuman</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><?php echo file_get_contents("pengumuman.txt"); ?></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pengumumanButton = document.querySelector('a[href="#pengumuman"]');
            if (pengumumanButton) {
                pengumumanButton.addEventListener('click', function(event) {
                    event.preventDefault();
                    const pengumumanModal = new bootstrap.Modal(document.getElementById('pengumumanModal'));
                    pengumumanModal.show();
                });
            }
        });
    </script>
</body>

</html>