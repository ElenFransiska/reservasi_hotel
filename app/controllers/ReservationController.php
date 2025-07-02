<?php
// controllers/ReservationController.php

// Sertakan file koneksi database.
// Asumsi: Anda memiliki file koneksi database di ../controllers/db.php.
// Jika file koneksi Anda ada di ../config/config.php, sesuaikan require_once.
require_once '../../config/config.php'; // Atau '../../config/config.php' jika itu yang benar

// Periksa apakah request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil dan sanitasi data dari form
    $guest_name = htmlspecialchars($_POST['guest_name'] ?? '');
    $check_in_date = htmlspecialchars($_POST['check_in_date'] ?? '');
    $check_out_date = htmlspecialchars($_POST['check_out_date'] ?? '');
    $room_type = htmlspecialchars($_POST['room_type'] ?? '');
    $num_guests = (int)($_POST['num_guests'] ?? 0);
    $email = htmlspecialchars($_POST['email'] ?? '');
    $phone_number = htmlspecialchars($_POST['phone_number'] ?? '');
    $special_requests = htmlspecialchars($_POST['special_requests'] ?? '');

    // Validasi dasar
    if (empty($guest_name) || empty($check_in_date) || empty($check_out_date) || empty($room_type) || $num_guests <= 0) {
        header("Location: ../views/pesankamar.php?status=error&message=validation_failed");
        exit();
    }

    // Menggunakan Prepared Statement
    $stmt = $conn->prepare("INSERT INTO reservations (guest_name, check_in_date, check_out_date, room_type, num_guests, email, phone_number, special_requests) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // Periksa jika persiapan statement gagal
    if ($stmt === false) {
        error_log("Error preparing reservation statement: " . $conn->error);
        header("Location: ../views/pesankamar.php?status=error&message=db_prepare_failed");
        exit();
    }

    // Bind parameter
    $stmt->bind_param("ssssisss", $guest_name, $check_in_date, $check_out_date, $room_type, $num_guests, $email, $phone_number, $special_requests);

    // Jalankan query
    if ($stmt->execute()) {
        // Pemesanan berhasil!
        header("Location: ../views/pesankamar.php?status=success");
        exit();
    } else {
        // Terjadi kesalahan saat menyimpan data
        error_log("Error inserting reservation data: " . $stmt->error);
        // Tangkap error duplikat entry primary key jika terjadi pada tabel 'reservations'
        if ($conn->errno == 1062) { // 1062 adalah kode error untuk Duplicate entry for key 'PRIMARY'
             header("Location: ../views/pesankamar.php?status=error&message=duplicate_entry");
        } else {
             header("Location: ../views/pesankamar.php?status=error&message=db_insert_failed");
        }
        exit();
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();

} else {
    // Jika diakses langsung tanpa POST request
    header("Location: ../views/pesankamar.php?status=error&message=invalid_access");
    exit();
}
?>