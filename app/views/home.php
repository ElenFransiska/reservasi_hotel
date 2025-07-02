<?php
session_start();
// Logika untuk login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Contoh data pengguna (seharusnya diambil dari database)
    $users = [
        'user' => [
            'password' => 'passworduser',
            'role' => 'user'
        ],
        'admin' => [
            'password' => 'passwordadmin',
            'role' => 'admin'
        ]
    ];

    if (isset($users[$username]) && $users[$username]['password'] === $password) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $users[$username]['role'];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reservasi Hotel - Beranda</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/css_home.css"> <!-- Link ke file CSS terpisah -->
</head>
<body>
    <div class="container">
        <header>
            <a href="#" class="logo">
                <i class="fas fa-hotel"></i>
                <span>ReservasiHotel</span>
            </a>
            <div class="logout-button">
                <a href="../../index.php" class="btn btn-outline">Logout</a>
            </div>
        </header>
        <section class="hero">
            <h1>Selamat Datang di Sistem Reservasi Hotel</h1>
            <p class="subtitle">
                Pesan kamar dengan mudah dan nikmati pengalaman menginap yang tak terlupakan. 
                Kami siap melayani Anda dengan fasilitas terbaik.
            </p>
            
            <div class="features">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bed"></i>
                    </div>
                    <h3 class="feature-title">Pesan Kamar</h3>
                    <p class="feature-desc">
                        Pilih kamar sesuai kebutuhan Anda dan lakukan reservasi dengan cepat.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <h3 class="feature-title">Layanan 24 Jam</h3>
                    <p class="feature-desc">
                        Tim kami siap membantu Anda kapan saja selama 24 jam.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h3 class="feature-title">Kontak Kami</h3>
                    <p class="feature-desc">
                        Hubungi kami untuk pertanyaan atau bantuan lebih lanjut.
                    </p>
                </div>
            </div>
        </section>
        
        <div class="cta-section">
            <div class="buttons">
                <a href="rooms.php" class="btn btn-primary">
                    <i class="fas fa-book"></i> Lihat Kamar
                </a>
                <a href="contact.php" class="btn btn-outline">
                    <i class="fas fa-envelope"></i> Kontak Kami
                </a>
            </div>
        </div>
        
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> ReservasiHotel. All rights reserved.</p>
    </footer>
</body>
</html>
