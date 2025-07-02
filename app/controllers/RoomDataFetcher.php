<?php
// controllers/RoomDataFetcher.php

// Sertakan file koneksi database.
require_once '../../db_connection.php'; // Adjust path if necessary

$rooms = [];
$roomDataFetcherErrorMessage = '';

// Check if $pdoHotel object is properly initialized and available.
if (!($pdoHotel instanceof PDO)) {
    $roomDataFetcherErrorMessage = "Koneksi ke database hotel gagal. Data kamar tidak dapat dimuat.";
    error_log("PDO Hotel connection failed or not established in RoomDataFetcher.php.");
} else {
    try {
        // Fetch all rooms from the 'kamar' table
        $sql = "SELECT * FROM kamar ORDER BY tipe_kamar ASC";
        $stmt = $pdoHotel->query($sql);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching rooms from hotel_db in RoomDataFetcher.php: " . $e->getMessage());
        $roomDataFetcherErrorMessage = "Terjadi kesalahan saat mengambil data kamar dari database. Mohon coba lagi nanti.";
    }
}

// Variables $rooms and $roomDataFetcherErrorMessage are now available to files that include this.
?>