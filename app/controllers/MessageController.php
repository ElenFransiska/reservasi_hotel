<?php
// controllers/MessageController.php

require_once 'db.php'; // Memuat koneksi database dan fungsi getAllContactMessages

$messages = [];
$error_message = '';

// Ambil semua pesan dari database
if (isset($conn) && $conn instanceof mysqli) {
    $messages = getAllContactMessages($conn);
    if (empty($messages) && $conn->error) {
        // Ini akan menangkap error jika getAllContactMessages mengembalikan array kosong tapi ada error di koneksi
        $error_message = "Terjadi kesalahan saat mengambil pesan dari database. Mohon coba lagi nanti.";
        error_log("MessageController: Error from getAllContactMessages: " . $conn->error);
    } else if (empty($messages)) {
        // Jika tidak ada error koneksi tapi array kosong, berarti memang belum ada pesan.
        $error_message = "Belum ada pesan yang masuk.";
    }
} else {
    $error_message = "Kesalahan koneksi database. Mohon coba lagi nanti.";
    error_log("MessageController: MySQLi connection \$conn is not established or not an object.");
}

// Tidak perlu menutup $conn di sini karena akan digunakan di halaman lain (admin.php, dll.)
?>  