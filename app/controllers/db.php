<?php
// controllers/db.php (Contoh dengan MySQLi)

$servername = "localhost";
$username = "root"; // Ganti dengan username database Anda
$password = "";     // Ganti dengan password database Anda
$dbname = "hotel"; // PASTIKAN NAMA DATABASE INI BENAR SESUAI DENGAN PHPMYADMIN ANDA

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    // Lebih baik lempar exception atau log error tanpa die() di lingkungan produksi
    // Untuk debugging, die() bisa diterima.
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk mendapatkan data kamar (jika Anda masih menggunakannya)
function getRooms($conn) {
    $sql = "SELECT id, nama_kamar, deskripsi, tipe_kamar, kapasitas_dewasa, kapasitas_anak, harga_per_malam, jumlah_tempat_tidur, tipe_tempat_tidur, ukuran_kamar, stock_available, gambar_url FROM kamar WHERE is_aktif = 1 AND stock_available > 0";
    $result = $conn->query($sql);
    $rooms = [];
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
    }
    return $rooms;
}

// Fungsi untuk Menyimpan Pesan Kontak
function saveContactMessage($conn, $nama, $email, $subjek, $pesan) {
    // Gunakan parameterized query untuk mencegah SQL Injection
    $stmt = $conn->prepare("INSERT INTO pesan_kontak (nama, email, subjek, pesan) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }
    // "ssss" menunjukkan 4 string sebagai tipe parameter
    $stmt->bind_param("ssss", $nama, $email, $subjek, $pesan);
    $success = $stmt->execute();
    if (!$success) {
        error_log("Execute failed: " . $stmt->error);
    }
    $stmt->close();
    return $success;
}

?>