<?php
// controllers/AddRoom.php

// Removed session_start() and login logic as requested.
// WARNING: This script is now publicly accessible. Re-implement authentication for production!

require_once '../../config/config.php'; // Adjust path if necessary

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input
    $nama = filter_input(INPUT_POST, 'nama', FILTER_SANITIZE_STRING);
    $deskripsi = filter_input(INPUT_POST, 'deskripsi', FILTER_SANITIZE_STRING);
    $tipe_kamar = filter_input(INPUT_POST, 'tipe_kamar', FILTER_SANITIZE_STRING);
    $kapasitas_dewasa = filter_input(INPUT_POST, 'kapasitas_dewasa', FILTER_VALIDATE_INT);
    $kapasitas_anak = filter_input(INPUT_POST, 'kapasitas_anak', FILTER_VALIDATE_INT);
    $harga_per_malam = filter_input(INPUT_POST, 'harga_per_malam', FILTER_VALIDATE_FLOAT);
    $jumlah_tempat_tidur = filter_input(INPUT_POST, 'jumlah_tempat_tidur', FILTER_VALIDATE_INT);
    $tipe_tempat_tidur = filter_input(INPUT_POST, 'tipe_tempat_tidur', FILTER_SANITIZE_STRING);
    $ukuran_kamar = filter_input(INPUT_POST, 'ukuran_kamar', FILTER_VALIDATE_FLOAT);
    $stock_available = filter_input(INPUT_POST, 'stock_available', FILTER_VALIDATE_INT);

    // Validate inputs
    if (
        $nama === null || $nama === false ||
        $deskripsi === null || $deskripsi === false ||
        $tipe_kamar === null || $tipe_kamar === false ||
        $kapasitas_dewasa === false || $kapasitas_dewasa < 1 ||
        $kapasitas_anak === false || $kapasitas_anak < 0 ||
        $harga_per_malam === false || $harga_per_malam < 0 ||
        $jumlah_tempat_tidur === false || $jumlah_tempat_tidur < 1 ||
        $tipe_tempat_tidur === null || $tipe_tempat_tidur === false ||
        $ukuran_kamar === false || $ukuran_kamar < 1 ||
        $stock_available === false || $stock_available < 0
    ) {
        header("Location: ../views/manage_rooms.php?status=error&message=invalid_input");
        exit();
    }

    if (!($pdoHotel instanceof PDO)) {
        error_log("AddRoom: PDO Hotel connection is not established.");
        header("Location: ../views/manage_rooms.php?status=error&message=db_connection_error_hotel");
        exit();
    }

    try {
        $sql = "INSERT INTO kamar (nama_kamar, deskripsi, tipe_kamar, kapasitas_dewasa, kapasitas_anak, harga_per_malam, jumlah_tempat_tidur, tipe_tempat_tidur, ukuran_kamar, stock_available)
                VALUES (:nama_kamar, :deskripsi, :tipe_kamar, :kapasitas_dewasa, :kapasitas_anak, :harga_per_malam, :jumlah_tempat_tidur, :tipe_tempat_tidur, :ukuran_kamar, :stock_available)";
        $stmt = $pdoHotel->prepare($sql);

        // Corrected line: Bind ':nama_kamar' to the $nama variable
        $stmt->bindParam(':nama_kamar', $nama); // Use $nama variable from the form input
        $stmt->bindParam(':deskripsi', $deskripsi);
        $stmt->bindParam(':tipe_kamar', $tipe_kamar);
        $stmt->bindParam(':kapasitas_dewasa', $kapasitas_dewasa, PDO::PARAM_INT);
        $stmt->bindParam(':kapasitas_anak', $kapasitas_anak, PDO::PARAM_INT);
        $stmt->bindParam(':harga_per_malam', $harga_per_malam);
        $stmt->bindParam(':jumlah_tempat_tidur', $jumlah_tempat_tidur, PDO::PARAM_INT);
        $stmt->bindParam(':tipe_tempat_tidur', $tipe_tempat_tidur);
        $stmt->bindParam(':ukuran_kamar', $ukuran_kamar);
        $stmt->bindParam(':stock_available', $stock_available, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: ../views/manage_rooms.php?status=success&message=add_success");
        } else {
            header("Location: ../views/manage_rooms.php?status=error&message=db_error");
        }
    } catch (PDOException $e) {
        error_log("Error adding room: " . $e->getMessage());
        header("Location: ../views/manage_rooms.php?status=error&message=db_error");
    }
} else {
    header("Location: ../views/manage_rooms.php"); // Redirect if not a POST request
}
exit();
?>