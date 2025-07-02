<?php
// views/contact.php
// Sertakan controller yang memproses form
require_once '../controllers/ContactController.php'; // Sesuaikan path jika perlu

// Tidak perlu memanggil getRooms atau menutup $conn di sini,
// karena ContactController sudah menangani logic database untuk form ini.
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kami - ReservasiHotel</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/css_contact.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

    <main class="contact-page">
        <div class="container">
            <div class="contact-form-container">
                <h2>Hubungi Kami</h2>
                <p style="text-align: center; color: #666; margin-bottom: 30px;">
                    Kami siap membantu Anda! Silakan isi formulir di bawah ini untuk pertanyaan atau bantuan apa pun.
                </p>

                <?php if (!empty($message)): ?>
                    <div class="message-box <?= htmlspecialchars($messageType) ?>">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <form action="contact.php" method="POST">
                    <div class="form-group">
                        <label for="nama">Nama Lengkap:</label>
                        <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($nama ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Alamat Email:</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="subjek">Subjek:</label>
                        <input type="text" id="subjek" name="subjek" value="<?= htmlspecialchars($subjek ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="pesan">Pesan Anda:</label>
                        <textarea id="pesan" name="pesan" required><?= htmlspecialchars($pesan ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Kirim Pesan</button>
                </form>

                <div class="back-button" style="text-align: center; margin-top: 20px;">
                    <a href="home.php" class="btn btn-secondary">Kembali</a>
                </div>
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
