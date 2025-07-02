<?php
ob_start(); // Mulai output buffering untuk mencegah "headers already sent"
session_start(); // Mulai sesi

// Sertakan file yang berisi fungsi otentik dan koneksi DB
include('../models/crusedur2.php');

$username = $_POST['username'] ?? ''; // Ambil username, gunakan null coalescing operator untuk keamanan
$password = $_POST['password'] ?? ''; // Ambil password

// Panggil fungsi otentik
if (otentik($username, $password)) {
    // Jika otentikasi berhasil, redirect ke halaman admin
    header("Location: ../views/home.php");
    exit();
} else {
    // Jika otentikasi gagal, redirect kembali ke form login dengan parameter error
    header("Location: ../views/loginerror.php");
    exit();
}

ob_end_flush(); // Akhiri output buffering dan kirimkan output
?>
