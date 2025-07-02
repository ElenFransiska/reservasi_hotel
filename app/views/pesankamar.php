<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemesanan Kamar</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/css_form.css"> 
    
</head>
<body>
    <div class="container reservation-form-container">
        <h2>Form Pemesanan Kamar</h2>

        <?php
        // Menampilkan pesan sukses atau error dari PHP proses
        if (isset($_GET['status'])) {
            $status = htmlspecialchars($_GET['status']);
            $message = htmlspecialchars($_GET['message'] ?? ''); // Ambil pesan detail

            if ($status == 'success') {
                echo "<p class='message-box success-message'>Pemesanan kamar berhasil ditempatkan!</p>";
            } elseif ($status == 'error') {
                echo "<p class='message-box error-message'>Terjadi kesalahan saat menempatkan pemesanan. ";
                if ($message == 'validation_failed') {
                    echo "Mohon lengkapi semua data yang wajib diisi.";
                } elseif ($message == 'db_prepare_failed') {
                    echo "Kesalahan internal server (persiapan database).";
                } elseif ($message == 'db_insert_failed') {
                    echo "Kesalahan internal server (penyimpanan data).";
                } elseif ($message == 'duplicate_entry') {
                    echo "Data pemesanan duplikat terdeteksi. (Mungkin ID reservasi sudah ada)"; // Ini penting untuk error primary key
                } elseif ($message == 'invalid_access') {
                    echo "Akses tidak valid. Mohon gunakan form untuk pemesanan.";
                } else {
                    echo "Silakan coba lagi.";
                }
                echo "</p>";
            }
        }
        ?>

        <form method="POST" action="../controllers/ReservationController.php"> <div class="form-group">
                <label for="guest_name">Nama Lengkap Tamu:</label>
                <input type="text" id="guest_name" name="guest_name" placeholder="Masukkan nama lengkap Anda" required>
            </div>

            <div class="form-group">
                <label for="check_in_date">Tanggal Check-in:</label>
                <input type="date" id="check_in_date" name="check_in_date" required>
            </div>

            <div class="form-group">
                <label for="check_out_date">Tanggal Check-out:</label>
                <input type="date" id="check_out_date" name="check_out_date" required>
            </div>

            <div class="form-group">
                <label for="room_type">Jenis Kamar:</label>
                <select id="room_type" name="room_type" required>
                    <option value="">-- Pilih Jenis Kamar --</option>
                    <option value="Standard">Standard</option>
                    <option value="Deluxe">Deluxe</option>
                    <option value="Suite">Suite</option>
                </select>
            </div>

            <div class="form-group">
                <label for="num_guests">Jumlah Tamu:</label>
                <input type="number" id="num_guests" name="num_guests" min="1" placeholder="Berapa tamu?" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="contoh@email.com">
            </div>

            <div class="form-group">
                <label for="phone_number">Nomor Telepon:</label>
                <input type="tel" id="phone_number" name="phone_number" placeholder="Contoh: 081234567890">
            </div>

            <div class="form-group">
                <label for="special_requests">Permintaan Khusus (Opsional):</label>
                <textarea id="special_requests" name="special_requests" rows="4" placeholder="Contoh: Kamar non-smoking, tambahan bantal, dll."></textarea>
            </div>

            <button type="submit" class="submit-button">Pesan Sekarang</button>
            <p class="back-link"><a href="../views/home.php">&larr; Kembali ke Home</a></p>
        </form>
    </div>
</body>
</html>