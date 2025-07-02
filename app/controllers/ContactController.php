<?php
// controllers/ContactController.php

// Pastikan untuk mengimpor file koneksi database Anda
// MENGUBAH require_once dari '../../config/config.php' menjadi 'db.php'
// karena db.php berisi fungsi saveContactMessage dan koneksi $conn,
// dan db.php berada di direktori yang sama dengan ContactController.php (controllers/).
require_once 'db.php';

$message = ''; // Untuk menyimpan pesan sukses atau error
$messageType = ''; // 'success' atau 'error'

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subjek = trim($_POST['subjek'] ?? '');
    $pesan = trim($_POST['pesan'] ?? '');

    // Validasi input
    if (empty($nama) || empty($email) || empty($pesan)) {
        $message = "Nama, Email, dan Pesan tidak boleh kosong.";
        $messageType = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Format email tidak valid.";
        $messageType = 'error';
    } else {
        // --- Contoh Dengan MySQLi ---
        // Asumsi $conn sudah terdefinisi dari db.php karena require_once di atas.
        // Cek apakah $conn sudah ada dan merupakan objek mysqli
        if (isset($conn) && $conn instanceof mysqli) {
            // Memanggil fungsi saveContactMessage dengan $conn sebagai argumen pertama
            if (saveContactMessage($conn, $nama, $email, $subjek, $pesan)) {
                $message = "Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.";
                $messageType = 'success';
                // Bersihkan form setelah sukses
                $nama = $email = $subjek = $pesan = '';
            } else {
                $message = "Gagal mengirim pesan. Silakan coba lagi nanti.";
                $messageType = 'error';
            }
        } else {
            $message = "Kesalahan koneksi database. Mohon coba lagi nanti.";
            $messageType = 'error';
            // Log error untuk debugging lebih lanjut
            error_log("ContactController: MySQLi connection \$conn is not established or not an object.");
        }

        // --- Contoh Dengan PDO (Jika Anda memutuskan beralih ke PDO dan uncomment kode ini) ---
        /*
        // Asumsi $pdo sudah terdefinisi dari config.php
        if (!($pdo instanceof PDO)) {
            $message = "Kesalahan koneksi database.";
            $messageType = 'error';
            error_log("ContactController: PDO connection is not established.");
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO pesan_kontak (nama, email, subjek, pesan) VALUES (:nama, :email, :subjek, :pesan)");
                $stmt->bindParam(':nama', $nama);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':subjek', $subjek);
                $stmt->bindParam(':pesan', $pesan);

                if ($stmt->execute()) {
                    $message = "Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.";
                    $messageType = 'success';
                    // Bersihkan form setelah sukses
                    $nama = $email = $subjek = $pesan = '';
                } else {
                    $message = "Gagal mengirim pesan. Silakan coba lagi nanti.";
                    $messageType = 'error';
                    error_log("PDO execute failed for contact form: " . implode(" ", $stmt->errorInfo()));
                }
            } catch (PDOException $e) {
                $message = "Terjadi kesalahan database: " . $e->getMessage();
                $messageType = 'error';
                error_log("Error saving contact message: " . $e->getMessage());
            }
        }
        */
    }
}
// Tidak perlu menutup koneksi MySQLi di sini jika db.php akan digunakan lagi,
// tapi untuk PDO, koneksi biasanya dibiarkan terbuka atau ditutup di akhir script utama.
?>