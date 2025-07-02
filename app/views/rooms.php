<?php
// rooms.php - Main page for room selection
// Include the database configuration and data retrieval functions
require_once '../controllers/db.php'; // Use require_once to ensure it's included only once

// Get rooms data using the function defined in db_config.php
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

            <section class="room-list">
                <?php if (!empty($rooms)): ?>
                    <?php foreach ($rooms as $row):?>
                        <div class="room-card">
                            <?php 
                            $imageSrc = !empty($row["gambar_url"]) ? '../../' . htmlspecialchars($row["gambar_url"]) : "https://via.placeholder.com/400x250?text=Gambar+Tidak+Tersedia";
                            ?>
                            <img src="<?= $imageSrc ?>" alt="<?= htmlspecialchars($row["nama_kamar"]) ?>" class="room-image">
                            <div class="room-info">
                                <h3 class="room-name"><?= htmlspecialchars($row["nama_kamar"]) ?></h3>
                                <p class="room-description"><?= htmlspecialchars($row["deskripsi"]) ?></p>
                                <div class="room-price">Rp <?= number_format($row["harga_per_malam"], 0, ',', '.') ?> <span class="per-night">/ Malam</span></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else:  ?>
                    <p style="text-align: center; grid-column: 1 / -1;">Tidak ada kamar yang tersedia saat ini.</p>
                <?php endif; ?>
            </section>

            <div class="booking-button" style="text-align: center; margin-top: 20px;">
                <a href="pesankamar.php" class="btn btn-primary">Pesan Sekarang</a>
            </div>
        </div>
    </main>

    <footer class="main-footer">
        <div class="container">
            <p>&copy; 2025 ReservasiHotel. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
