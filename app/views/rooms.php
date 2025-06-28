<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Kamar - ReservasiHotel</title>
    <link rel="stylesheet" href="style.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Styling & Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: #f0f2f5; /* Warna latar belakang halaman, sedikit abu-abu */
    color: #333;
    line-height: 1.6;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Header Styling (Mirip dengan gambar yang Anda berikan) */
.main-header {
    background-color: #ffffff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    padding: 15px 0;
    border-bottom: 1px solid #e0e0e0; /* Garis bawah tipis */
}

.main-header .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.main-header .logo {
    font-size: 24px;
    font-weight: 700;
    color: #4CAF50; /* Hijau untuk logo */
    display: flex;
    align-items: center;
    gap: 8px;
}

.main-header .logo i {
    color: #4CAF50;
    font-size: 28px;
}

.main-nav .bookmark-link {
    color: #666;
    text-decoration: none;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 8px 12px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.main-nav .bookmark-link i {
    font-size: 18px;
}

.main-nav .bookmark-link:hover {
    background-color: #f0f0f0;
}


/* Main Page Content */
.room-selection-page {
    padding: 40px 0;
}

.page-title {
    font-size: 38px;
    font-weight: 700;
    color: #2c3e50; /* Biru tua, mirip judul di gambar */
    text-align: center;
    margin-bottom: 15px;
}

.page-intro {
    font-size: 18px;
    color: #555;
    text-align: center;
    max-width: 800px;
    margin: 0 auto 40px auto;
}

/* Filter Section */
.room-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
    margin-bottom: 40px;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.room-filters input,
.room-filters select {
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    outline: none;
    flex: 1 1 auto; /* Allow items to grow and shrink */
    min-width: 150px; /* Minimum width for inputs */
}

.room-filters button {
    padding: 12px 25px;
    background-color: #007bff; /* Biru untuk tombol filter */
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    transition: background-color 0.3s ease;
}

.room-filters button:hover {
    background-color: #0056b3;
}


/* Room List Grid */
.room-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* Responsive grid */
    gap: 30px; /* Jarak antar kartu kamar */
}

/* Room Card Styling (Mirip dengan 3 kotak di gambar) */
.room-card {
    background-color: #ffffff;
    border-radius: 12px;
    overflow: hidden; /* Penting agar gambar tidak keluar dari sudut rounded */
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08); /* Bayangan yang lebih menonjol */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column; /* Tata letak konten dalam kartu secara vertikal */
}

.room-card:hover {
    transform: translateY(-5px); /* Efek melayang saat hover */
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
}

.room-image {
    width: 100%;
    height: 220px; /* Tinggi gambar yang konsisten */
    object-fit: cover; /* Memastikan gambar menutupi area tanpa terdistorsi */
    border-bottom: 1px solid #eee; /* Garis tipis di bawah gambar */
}

.room-info {
    padding: 25px;
    display: flex;
    flex-direction: column;
    flex-grow: 1; /* Agar konten info mengisi sisa ruang */
}

.room-name {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

.room-description {
    font-size: 15px;
    color: #666;
    margin-bottom: 15px;
    flex-grow: 1; /* Memungkinkan deskripsi mengambil ruang yang dibutuhkan */
}

.room-price {
    font-size: 26px;
    font-weight: 700;
    color: #4CAF50; /* Warna hijau untuk harga, agar menarik perhatian */
    margin-top: auto; /* Dorong harga ke bawah jika deskripsi pendek */
    margin-bottom: 20px;
}

.room-price .per-night {
    font-size: 16px;
    font-weight: 500;
    color: #888;
}

/* Tombol "Pesan Sekarang" */
.btn {
    display: inline-block;
    padding: 12px 25px;
    border: none;
    border-radius: 8px; /* Lebih membulat dari filter */
    cursor: pointer;
    font-size: 17px;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-primary {
    background-color: #007bff; /* Biru utama */
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
    transform: translateY(-2px);
}

/* Footer Styling */
.main-footer {
    background-color: #2c3e50; /* Warna gelap, mirip header bawah di gambar utama */
    color: #f0f2f5;
    padding: 20px 0;
    text-align: center;
    margin-top: 50px;
}

.main-footer p {
    font-size: 14px;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .page-title {
        font-size: 32px;
    }

    .page-intro {
        font-size: 16px;
    }

    .main-header .container {
        flex-direction: column;
        gap: 10px;
    }

    .main-header .logo {
        font-size: 20px;
    }

    .main-nav .bookmark-link {
        font-size: 14px;
        padding: 6px 10px;
    }

    .room-filters {
        flex-direction: column; /* Stack filters vertically on small screens */
        align-items: stretch;
    }

    .room-filters input,
    .room-filters select,
    .room-filters button {
        width: 100%; /* Make filter inputs full width */
        min-width: unset;
    }

    .room-list {
        grid-template-columns: 1fr; /* Satu kolom untuk mobile */
    }

    .room-card {
        margin: 0 10px; /* Sedikit margin samping untuk kartu di mobile */
    }
}

@media (max-width: 480px) {
    .room-image {
        height: 180px;
    }
    .room-name {
        font-size: 20px;
    }
    .room-price {
        font-size: 22px;
    }
    .btn {
        font-size: 15px;
        padding: 10px 20px;
    }
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

            <section class="room-list">
                <?php
                // === KODE KONEKSI DATABASE PHP DIMULAI DI SINI ===
                $servername = "localhost"; // Ganti jika server database Anda berbeda
                $username = "root";        // Ganti dengan username database Anda
                $password = "";            // Ganti dengan password database Anda
                $dbname = "hotel";         // Nama database Anda (dari gambar: hotel)

                // Membuat koneksi
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Memeriksa koneksi
                if ($conn->connect_error) {
                    die('<p style="text-align: center; color: red;">Koneksi ke database gagal: ' . $conn->connect_error . '</p>');
                }

                // Query untuk mengambil data kamar
                // Sesuaikan kolom yang diambil dengan yang ada di tabel Kamar Anda (berdasarkan gambar phpMyAdmin)
                $sql = "SELECT id, nama_kamar, deskripsi, harga_per_malam, gambar_url FROM Kamar WHERE is_aktif = TRUE ORDER BY nama_kamar ASC";
                $result = $conn->query($sql);

                // Cek apakah ada data yang ditemukan
                if ($result->num_rows > 0) {
                    // Loop melalui setiap baris data yang ditemukan
                    while($row = $result->fetch_assoc()) {
                        // Tampilkan HTML untuk setiap kamar menggunakan data dari database
                        echo '<div class="room-card">';
                        
                        // Menentukan URL gambar. Gunakan gambar_url dari DB, atau placeholder jika kosong/NULL.
                        $imageSrc = !empty($row["gambar_url"]) ? htmlspecialchars($row["gambar_url"]) : "https://via.placeholder.com/400x250?text=Gambar+Tidak+Tersedia";
                        
                        echo '<img src="' . $imageSrc . '" alt="' . htmlspecialchars($row["nama_kamar"]) . '" class="room-image">';
                        echo '<div class="room-info">';
                        echo '<h3 class="room-name">' . htmlspecialchars($row["nama_kamar"]) . '</h3>';
                        echo '<p class="room-description">' . htmlspecialchars($row["deskripsi"]) . '</p>';
                        // Format harga ke mata uang Rupiah
                        echo '<div class="room-price">Rp ' . number_format($row["harga_per_malam"], 0, ',', '.') . ' <span class="per-night">/ Malam</span></div>';
                        echo '<button class="btn btn-primary">Pesan Sekarang</button>'; 
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    // Pesan jika tidak ada kamar ditemukan
                    echo '<p style="text-align: center; grid-column: 1 / -1;">Tidak ada kamar yang tersedia saat ini.</p>';
                }

                // Tutup koneksi database
                $conn->close();
                // === KODE KONEKSI DATABASE PHP BERAKHIR DI SINI ===
                ?>
            </section>
        </div>
    </main>

    <footer class="main-footer">
        <div class="container">
            <p>&copy; 2025 ReservasiHotel. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>