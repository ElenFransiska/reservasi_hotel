<?php
// views/manage_messages.php

// Sertakan controller yang memproses data pesan
require_once '../controllers/MessageController.php'; // Ini akan memuat db.php dan mengambil pesan

// Pastikan hanya admin yang bisa mengakses halaman ini (sesuai dengan logic otentikasi Anda)
// Contoh sederhana:
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Arahkan ke halaman login jika tidak diautentikasi sebagai admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesan - Admin Panel ReservasiHotel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin_panel.css">
    
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>Admin Panel</h3>
            </div>
            <ul class="sidebar-menu">
                <li><a href="admin.php"><i class="fas fa-tachometer-alt"></i> Kelola Pemesanan</a></li>
                <li><a href="laporan_bulanan.php"><i class="fas fa-chart-bar"></i> Laporan Bulanan</a></li>
                <li><a href="manage_rooms.php"><i class="fas fa-bed"></i> Kelola Kamar</a></li>
                <li><a href="manage_users.php"><i class="fas fa-users"></i> Kelola Pengguna</a></li>
                <li class="active"><a href="manage_messages.php"><i class="fas fa-envelope"></i> Kelola Pesan</a></li> <li><a href="../controllers/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="content">
            <header class="navbar">
                <div class="welcome-text">
                    Selamat Datang, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>!
                </div>
                <div class="user-info">
                    Role: <?php echo htmlspecialchars($_SESSION['role'] ?? 'Guest'); ?>
                </div>
            </header>

            <div class="main-content">
                <h1>Kelola Pesan Masuk</h1>

                <?php if (!empty($error_message)): ?>
                    <div class="error-message-box">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($messages)): ?>
                    <table class="message-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Subjek</th>
                                <th>Pesan</th>
                                <th>Tanggal Kirim</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $msg): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($msg['id']); ?></td>
                                <td><?php echo htmlspecialchars($msg['nama']); ?></td>
                                <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                <td><?php echo htmlspecialchars($msg['subjek']); ?></td>
                                <td><div class="message-content"><?php echo nl2br(htmlspecialchars($msg['pesan'])); ?></div></td>
                                <td><?php echo htmlspecialchars(date('d F Y H:i', strtotime($msg['tanggal_kirim']))); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <?php if (empty($error_message)): // Hanya tampilkan ini jika tidak ada error koneksi/data ?>
                        <div class="no-messages">Tidak ada pesan masuk saat ini.</div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>