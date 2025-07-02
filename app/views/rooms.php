<?php
// rooms.php - Main page for room selection
// Include the database configuration and data retrieval functions
require_once '../controllers/db.php'; // Use require_once to ensure it's included only once

// Get rooms data using the function defined in db_config.php
// Ensure $conn is initialized and properly connected in db.php
if (!isset($conn) || $conn->connect_error) {
    // Handle database connection error
    die("Koneksi database gagal: " . ($conn->connect_error ?? 'Tidak dapat terhubung ke database.'));
}
$rooms = getRooms($conn);

// Close the database connection after fetching all data
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Kamar - ReservasiHotel</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/css_room.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Additional CSS for the single button at the bottom */
        .book-all-rooms-btn-container {
            text-align: center;
            margin-top: 40px; /* Space above the button */
            margin-bottom: 40px; /* Space below the button */
        }
        .book-all-rooms-btn {
            background-color: #28a745; /* A distinct green for booking action */
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1.2em;
            cursor: pointer;
            text-decoration: none; /* For anchor tag */
            transition: background-color 0.3s ease;
            display: inline-block; /* Allows padding and margin */
        }
        .book-all-rooms-btn:hover {
            background-color: #218838;
        }
        /* Adjustments for room-card to better fill space if button is removed */
        .room-card .room-info {
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Distribute content vertically */
            flex-grow: 1; /* Allow info section to grow */
        }
        .room-card .room-price {
            margin-top: auto; /* Push price to the bottom of the info section */
            padding-top: 15px; /* Add some space above price */
        }

        /* Basic styling for no rooms message */
        .no-rooms-message {
            text-align: center;
            padding: 30px;
            color: #555;
            font-size: 1.1em;
            background-color: #f0f0f0;
            border-radius: 8px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <header class="main-header">
        <div class="container">
            <div class="logo">
                <i class="fas fa-hotel"></i> ReservasiHotel
            </div>
            <nav class="main-nav">
                <a href="#" class="bookmark-link"><i class="fas fa-bookmark"></i> All Bookmarks</a>
            </nav>
        </div>
    </header>

    <main class="room-selection-page">
        <div class="container">
            <h1 class="page-title">Pilih Kamar Anda</h1>
            <p class="page-intro">Telusuri berbagai pilihan kamar kami yang nyaman dan sesuaikan dengan kebutuhan Anda. Nikmati pengalaman menginap tak terlupakan!</p>

            <?php /*
            <section class="room-filters">
                <input type="text" placeholder="Cari Kamar...">
                <select>
                    <option value="">Tipe Kamar</option>
                    <option value="standard">Standard</option>
                    <option value="deluxe">Deluxe</option>
                    <option value="suite">Suite</option>
                </select>
                <input type="number" placeholder="Jumlah Tamu">
                <input type="date" placeholder="Check-in">
                <input type="date" placeholder="Check-out">
                <button class="btn btn-primary">Filter</button>
            </section>
            */ ?>

            <section class="room-list">
                <?php if (!empty($rooms)): // Check if the $rooms array is not empty ?>
                    <?php foreach ($rooms as $row): // Loop through each room data ?>
                        <div class="room-card">
                            <?php
                            // Determine the image source: use database URL with corrected path if available, otherwise a placeholder
                            // Assuming 'gambar_url' from the database is like 'images/kamar_deluxe_twin.jpg'
                            // and the actual image files are in '../../assets/images/' from this 'views/rooms.php' file.
                            $imageSrc = !empty($row["gambar_url"]) ? '../../assets/' . htmlspecialchars($row["gambar_url"]) : "https://via.placeholder.com/400x250?text=Gambar+Tidak+Tersedia";
                            ?>
                            <img src="<?= $imageSrc ?>" alt="<?= htmlspecialchars($row["nama_kamar"]) ?>" class="room-image">
                            <div class="room-info">
                                <h3 class="room-name"><?= htmlspecialchars($row["nama_kamar"]) ?></h3>
                                <p class="room-description"><?= htmlspecialchars($row["deskripsi"]) ?></p>
                                <div class="room-price">Rp <?= number_format($row["harga_per_malam"], 0, ',', '.') ?> <span class="per-night">/ Malam</span></div>
                                </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: // If no rooms are found ?>
                    <p class="no-rooms-message">Tidak ada kamar yang tersedia saat ini.</p>
                <?php endif; ?>
            </section>

            <?php if (!empty($rooms)): ?>
            <div class="book-all-rooms-btn-container">
                <a href="reservations.php" class="book-all-rooms-btn">Pesan Kamar Sekarang</a>
            </div>
            <?php endif; ?>

        </div>
    </main>

    <footer class="main-footer">
        <div class="container">
            <p>&copy; 2025 ReservasiHotel. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>