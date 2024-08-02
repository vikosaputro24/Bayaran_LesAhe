<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ahe</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-RlvE3NzMp+O8Jw+V2Cefw6Ht1PAd75c8ATx4pv3yEt5aNsefLOeFtLsW5GcK8u0keCZNP5tJFX0tW/aV7lBxtw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body style="height: 100%; margin: 0; font-family: Arial, sans-serif;">

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #343a40;">
        <a class="navbar-brand" href="#">Ahe</a>
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
                        <a class="dropdown-item" href="./logout.php">Keluar</a>
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
        <h1 style="font-size: 2.5rem; margin-bottom: 20px; color: #343a40;">Tentang Website Ini</h1>
        <p style="line-height: 1.8; color: black; max-width: 800px; margin: 0 auto;">Website ini merupakan sebuah website pembayaran dari les Anak Hebat dimana siswa atau siswi dapat melakukan pembayaran di website ini. Website ini bukan hanya untuk membayar spp saja namun dapat membayar paket wisuda ketika sudah lulus.</p>
        <h5 style="margin-top: 20px; font-size: 1.25rem; color: #343a40;">VISI AHE</h5>
        <p style="line-height: 1.8; color: black; max-width: 800px; margin: 0 auto;">Pada tahun 2025 menjadi Sekolah Baca dengan metode paling jitu yang melayani di 7000 desa dan atau kelurahan</p>
        <h5 style="margin-top: 20px; font-size: 1.25rem; color: #343a40;">MISI AHE</h5>
        <p style="line-height: 1.8; color: black; max-width: 800px; margin: 0 auto;">1. Menyediakan tempat belajar baca dengan metode yang asyik.</p>
        <p style="line-height: 1.8; color: black; max-width: 800px; margin: 0 auto;">2. Membantu anak Indonesia supaya bisa membaca saat kelas satu sehingga mudah mengikuti pelajaran.</p>
        <p style="line-height: 1.8; color: black; max-width: 800px; margin: 0 auto;">3. Membantu ibu rumah tangga terdidik supaya bermanfaat bagi lingkungannya melalui AHE.</p>
        <p style="line-height: 1.8; color: black; max-width: 800px; margin: 0 auto;">4. Menambah penghasilan para guru TK & guru honorer MI/SD melalui AHE di rumahnya.</p>
        <p style="line-height: 1.8; color: black; max-width: 800px; margin: 0 auto;">5. Menyediakan program belajar baca untuk lembaga bimbingan belajar yang telah berjalan.</p>
    </div>

    <footer class="bg-dark text-white" style="padding: 20px 0; text-align: center;">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-4">
                    <h5 style="margin-bottom: 20px;">KONTAK</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone"></i> <a href="tel:+123456789" class="text-white">085691101993</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 style="margin-bottom: 20px;">MEDIA SOSIAL</h5>
                    <p><a href="https://www.instagram.com/aherancagong_lesbaca?igsh=MWlqaXg3d2UwNnFmaQ==">INSTAGRAM</a></p>
                    <p><a href="https://m.facebook.com/titik.atikah.79/">FACEBOOK</a></p>
                </div>
                <div class="col-md-4">
                    <h5 style="margin-bottom: 20px;">ALAMAT</h5>
                    <p>Perum Griya Curug Blok E2 no 9, Rancagong, Legok</p>
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