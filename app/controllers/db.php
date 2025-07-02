<?php
// db_config.php - Database connection configuration

$servername = "localhost"; // Your database server name
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "hotel";         // Your database name (from the phpMyAdmin image: hotel)

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    // If connection fails, stop script execution and display an error message
    die('<p style="text-align: center; color: red;">Koneksi ke database gagal: ' . $conn->connect_error . '</p>');
}

// Function to safely retrieve rooms data from the database
function getRooms($conn) {
    // SQL query to select relevant room data from the 'Kamar' table
    // It filters for active rooms and orders them by name
    $sql = "SELECT id, nama_kamar, deskripsi, harga_per_malam, gambar_url FROM Kamar WHERE is_aktif = TRUE ORDER BY nama_kamar ASC";
    
    // Execute the query
    $result = $conn->query($sql);

    $rooms = []; // Initialize an empty array to store room data
    
    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Fetch each row as an associative array and add it to the $rooms array
        while($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
    }
    return $rooms; // Return the array of rooms
}

// Note: The database connection ($conn) is closed in the main file
// after all data retrieval is complete to ensure resources are freed.
?>