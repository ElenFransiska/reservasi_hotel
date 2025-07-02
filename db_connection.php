<?php
// config/config.php
$dbHostHotel = 'localhost';
$dbNameHotel = 'hotel'; // Your hotel database name
$dbUserHotel = 'root';     // Your database username
$dbPassHotel = '';         // Your database password

$pdoHotel = null; // Initialize to null

try {
    $dsnHotel = "mysql:host=$dbHostHotel;dbname=$dbNameHotel;charset=utf8mb4";
    $optionsHotel = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     // Default fetch mode to associative arrays
        PDO::ATTR_EMULATE_PREPARES   => false,                 // Disable emulation for better security and performance
    ];
    $pdoHotel = new PDO($dsnHotel, $dbUserHotel, $dbPassHotel, $optionsHotel);
} catch (PDOException $e) {
    // Log the connection error, but don't expose sensitive details to the user
    error_log("Hotel DB Connection Error: " . $e->getMessage());
    // You might want to redirect to an error page or show a generic message
    // die("Could not connect to the hotel database. Please try again later.");
}

// If you have a kasir_db, you would set up $pdoKasir similarly
// $dbHostKasir = 'localhost';
// $dbNameKasir = 'kasir_db';
// $dbUserKasir = 'root';
// $dbPassKasir = '';
// $pdoKasir = null;
// try {
//     $dsnKasir = "mysql:host=$dbHostKasir;dbname=$dbNameKasir;charset=utf8mb4";
//     $pdoKasir = new PDO($dsnKasir, $dbUserKasir, $dbPassKasir, $optionsHotel); // Reusing options
// } catch (PDOException $e) {
//     error_log("Kasir DB Connection Error: " . $e->getMessage());
// }
?>