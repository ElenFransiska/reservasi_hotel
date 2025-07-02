<?php
// models/proses_reservation.php (sesuai struktur folder Anda)

// Sertakan file koneksi database
// Sesuaikan path ini berdasarkan lokasi db.php di folder controllers Anda
// Dari models/proses_reservation.php ke controllers/db.php
require_once '../../db_connection.php';

// Periksa apakah form disubmit dengan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form dan sanitasi (membersihkan) input
    $guest_name = htmlspecialchars($_POST['guest_name'] ?? '');
    $check_in_date = htmlspecialchars($_POST['check_in_date'] ?? '');
    $check_out_date = htmlspecialchars($_POST['check_out_date'] ?? '');
    $room_type = htmlspecialchars($_POST['room_type'] ?? '');
    $num_guests = (int)($_POST['num_guests'] ?? 0); // Pastikan ini integer
    $email = htmlspecialchars($_POST['email'] ?? '');
    $phone_number = htmlspecialchars($_POST['phone_number'] ?? '');
    $special_requests = htmlspecialchars($_POST['special_requests'] ?? '');

    // Validasi dasar
    if (empty($guest_name) || empty($check_in_date) || empty($check_out_date) || empty($room_type) || $num_guests <= 0) {
        // Redirect ke pesankamar.php dengan status error jika ada validasi gagal
        header("Location: ../views/pesankamar.php?status=error_validation");
        exit();
    }

    // Menggunakan Prepared Statement untuk mencegah SQL Injection
    // Pastikan nama tabel di sini adalah 'reservations' sesuai SQL yang kita buat
    $stmt = $conn->prepare("INSERT INTO reservations (guest_name, check_in_date, check_out_date, room_type, num_guests, email, phone_number, special_requests) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // Periksa apakah statement berhasil dipersiapkan
    if ($stmt === false) {
        // Handle error persiapan statement
        error_log("Error preparing statement for reservation: " . $conn->error); // Log error untuk debugging
        // Redirect ke pesankamar.php dengan status error
        header("Location: ../views/pesankamar.php?status=error_db_prepare");
        exit();
    }

    // Bind parameter (s = string, i = integer)
    $stmt->bind_param("ssssisss", $guest_name, $check_in_date, $check_out_date, $room_type, $num_guests, $email, $phone_number, $special_requests);

    // Jalankan query
    if ($stmt->execute()) {
        // Pemesanan berhasil
        // Redirect ke pesankamar.php dengan status success
        header("Location: ../views/pesankamar.php?status=success");
        exit();
    } else {
        // Terjadi kesalahan saat menyimpan ke database
        error_log("Error inserting reservation data: " . $stmt->error); // Log error untuk debugging
        // Redirect ke pesankamar.php dengan status error
        header("Location: ../views/pesankamar.php?status=error_db_insert");
        exit();
    }

    // Tutup statement dan koneksi (akan ditutup otomatis saat skrip berakhir, tapi baik untuk kejelasan)
    $stmt->close();
    $conn->close();
} else {
    // Jika diakses langsung tanpa POST request, redirect kembali ke form dengan status indikasi
    header("Location: ../views/pesankamar.php?status=no_post");
    exit();
}
?>