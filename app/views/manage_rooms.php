<?php
// views/manage_rooms.php

// Removed session_start() and login logic as requested.
// WARNING: This page is now publicly accessible. Re-implement authentication for production!

// Include the PHP file that fetches room data
// Pastikan path ini benar sesuai struktur proyek Anda
require_once '../controllers/RoomDataFetcher.php';

$loggedInAdminName = 'Admin (Guest Access)'; // Placeholder since login is removed

$errorMessage = '';
$successMessage = '';

// Use error message from data fetcher if any
// Memastikan $roomDataFetcherErrorMessage didefinisikan sebelum digunakan
if (isset($roomDataFetcherErrorMessage) && !empty($roomDataFetcherErrorMessage)) {
    $errorMessage = $roomDataFetcherErrorMessage;
}

// Handle messages from room operations (Add, Update, Delete)
if (isset($_GET['status'])) {
    $status = htmlspecialchars($_GET['status']);
    $message = htmlspecialchars($_GET['message'] ?? ''); // Menggunakan null coalescing operator
    if ($status === 'success') {
        if ($message === 'add_success') {
            $successMessage = "Kamar berhasil ditambahkan!";
        } elseif ($message === 'update_success') {
            $successMessage = "Data kamar berhasil diperbarui!";
        } elseif ($message === 'delete_success') {
            $successMessage = "Kamar berhasil dihapus!";
        } else {
            $successMessage = "Operasi berhasil!";
        }
    } elseif ($status === 'error') {
        if ($message === 'invalid_input') {
            $errorMessage = "Input tidak valid. Mohon lengkapi semua bidang yang diperlukan.";
        } elseif ($message === 'db_error') {
            $errorMessage = "Terjadi kesalahan database saat operasi.";
        } elseif ($message === 'not_found') {
            $errorMessage = "Kamar tidak ditemukan.";
        } elseif ($message === 'delete_failed_integrity') { // Specific error for integrity violation
            $errorMessage = "Gagal menghapus kamar. Mungkin ada reservasi yang terkait dengan kamar ini.";
        } else {
            $errorMessage = "Terjadi kesalahan: " . $message;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Kelola Kamar</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/css_admin.css">
    <link rel="stylesheet" href="../../assets/css/css_manage_room.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>Admin Panel</h3>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="admin.php?tab=reservations"><i class="fas fa-book"></i> Kelola Pemesanan</a></li>
                    <li><a href="admin.php?tab=reports"><i class="fas fa-chart-line"></i> Laporan Bulanan</a></li>
                    <li><a href="manage_rooms.php" class="active"><i class="fas fa-hotel"></i> Kelola Kamar</a></li>
                    <li><a href="../controllers/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="admin-content">
            <header class="admin-header">
                <h1>Selamat Datang, <?= htmlspecialchars($loggedInAdminName) ?>!</h1>
                <div class="user-info">
                    <p>Role: Admin (Direct Access)</p>
                </div>
            </header>

            <div class="content-section">
                <?php if (!empty($successMessage)): ?>
                    <p class="message-box success-message"><?= $successMessage ?></p>
                <?php endif; ?>
                <?php if (!empty($errorMessage)): ?>
                    <p class="message-box error-message"><?= $errorMessage ?></p>
                <?php endif; ?>

                <div class="form-container">
                    <h3>Tambah Kamar Baru</h3>
                    <form action="../controllers/AddRoom.php" method="POST">
                        <div class="form-group">
                            <label for="nama">Nama Kamar:</label>
                            <input type="text" id="nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi:</label>
                            <textarea id="deskripsi" name="deskripsi" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="tipe_kamar">Tipe Kamar:</label>
                            <input type="text" id="tipe_kamar" name="tipe_kamar" required>
                        </div>
                        <div class="form-group">
                            <label for="kapasitas_dewasa">Kapasitas Dewasa:</label>
                            <input type="number" id="kapasitas_dewasa" name="kapasitas_dewasa" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="kapasitas_anak">Kapasitas Anak:</label>
                            <input type="number" id="kapasitas_anak" name="kapasitas_anak" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="harga_per_malam">Harga Per Malam:</label>
                            <input type="number" id="harga_per_malam" name="harga_per_malam" min="0" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="jumlah_tempat_tidur">Jumlah Tempat Tidur:</label>
                            <input type="number" id="jumlah_tempat_tidur" name="jumlah_tempat_tidur" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="tipe_tempat_tidur">Tipe Tempat Tidur:</label>
                            <input type="text" id="tipe_tempat_tidur" name="tipe_tempat_tidur" required>
                        </div>
                        <div class="form-group">
                            <label for="ukuran_kamar">Ukuran Kamar (m²):</label>
                            <input type="number" id="ukuran_kamar" name="ukuran_kamar" min="1" step="0.1" required>
                        </div>
                        <div class="form-group">
                            <label for="stock_available">Stok Tersedia:</label>
                            <input type="number" id="stock_available" name="stock_available" min="0" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Tambahkan Kamar">
                        </div>
                    </form>
                </div>

                <h3>Daftar Kamar Tersedia</h3>
                <?php if (!empty($rooms)): ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Kamar</th>
                                    <th>Tipe Kamar</th>
                                    <th>Kapasitas (D/A)</th>
                                    <th>Harga/Malam</th>
                                    <th>Jumlah Tempat Tidur</th>
                                    <th>Tipe Tempat Tidur</th>
                                    <th>Ukuran Kamar</th>
                                    <th>Stok Tersedia</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rooms as $room): ?>
                                <tr>
                                    <td><?= htmlspecialchars($room['id'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($room['nama_kamar'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($room['tipe_kamar'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($room['kapasitas_dewasa'] ?? '') ?>/<?= htmlspecialchars($room['kapasitas_anak'] ?? '') ?></td>
                                    <td>Rp <?= number_format($room['harga_per_malam'] ?? 0, 0, ',', '.') ?></td>
                                    <td><?= htmlspecialchars($room['jumlah_tempat_tidur'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($room['tipe_tempat_tidur'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($room['ukuran_kamar'] ?? '') ?> m²</td>
                                    <td><?= htmlspecialchars($room['stock_available'] ?? 'N/A') ?></td>
                                    <td class="action-buttons">
                                        <button class="btn-edit" onclick="openEditModal(<?= htmlspecialchars(json_encode($room)) ?>)"><i class="fas fa-edit"></i> Edit</button>
                                        <form action="../controllers/DeleteRoom.php" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar ini?');">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($room['id'] ?? '') ?>">
                                            <button type="submit" class="btn-delete"><i class="fas fa-trash"></i> Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>Tidak ada data kamar yang tersedia.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <div id="editRoomModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3>Edit Kamar</h3>
            <form action="../controllers/UpdateRoom.php" method="POST">
                <input type="hidden" id="edit_id" name="id">
                <div class="form-group">
                    <label for="edit_nama">Nama Kamar:</label>
                    <input type="text" id="edit_nama" name="nama_kamar" required>
                </div>
                <div class="form-group">
                    <label for="edit_deskripsi">Deskripsi:</label>
                    <textarea id="edit_deskripsi" name="deskripsi" required></textarea>
                </div>
                <div class="form-group">
                    <label for="edit_tipe_kamar">Tipe Kamar:</label>
                    <input type="text" id="edit_tipe_kamar" name="tipe_kamar" required>
                </div>
                <div class="form-group">
                    <label for="edit_kapasitas_dewasa">Kapasitas Dewasa:</label>
                    <input type="number" id="edit_kapasitas_dewasa" name="kapasitas_dewasa" min="1" required>
                </div>
                <div class="form-group">
                    <label for="edit_kapasitas_anak">Kapasitas Anak:</label>
                    <input type="number" id="edit_kapasitas_anak" name="kapasitas_anak" min="0" required>
                </div>
                <div class="form-group">
                    <label for="edit_harga_per_malam">Harga Per Malam:</label>
                    <input type="number" id="edit_harga_per_malam" name="harga_per_malam" min="0" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="edit_jumlah_tempat_tidur">Jumlah Tempat Tidur:</label>
                    <input type="number" id="edit_jumlah_tempat_tidur" name="jumlah_tempat_tidur" min="1" required>
                </div>
                <div class="form-group">
                    <label for="edit_tipe_tempat_tidur">Tipe Tempat Tidur:</label>
                    <input type="text" id="edit_tipe_tempat_tidur" name="tipe_tempat_tidur" required>
                </div>
                <div class="form-group">
                    <label for="edit_ukuran_kamar">Ukuran Kamar (m²):</label>
                    <input type="number" id="edit_ukuran_kamar" name="ukuran_kamar" min="1" step="0.1" required>
                </div>
                <div class="form-group">
                    <label for="edit_stock_available">Stok Tersedia:</label>
                    <input type="number" id="edit_stock_available" name="stock_available" min="0" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Simpan Perubahan">
                </div>
            </form>
        </div>
    </div>

    <script>
        // JavaScript for Modal
        var modal = document.getElementById("editRoomModal");
        var span = document.getElementsByClassName("close-button")[0];

        function openEditModal(roomData) {
            // Pastikan properti ada sebelum mengaksesnya, atau berikan nilai default
            document.getElementById('edit_id').value = roomData.id || '';
            document.getElementById('edit_nama').value = roomData.nama_kamar || '';
            document.getElementById('edit_deskripsi').value = roomData.deskripsi || '';
            document.getElementById('edit_tipe_kamar').value = roomData.tipe_kamar || '';
            document.getElementById('edit_kapasitas_dewasa').value = roomData.kapasitas_dewasa || 1;
            document.getElementById('edit_kapasitas_anak').value = roomData.kapasitas_anak || 0;
            document.getElementById('edit_harga_per_malam').value = roomData.harga_per_malam || 0;
            document.getElementById('edit_jumlah_tempat_tidur').value = roomData.jumlah_tempat_tidur || 1;
            document.getElementById('edit_tipe_tempat_tidur').value = roomData.tipe_tempat_tidur || '';
            document.getElementById('edit_ukuran_kamar').value = roomData.ukuran_kamar || 1;
            document.getElementById('edit_stock_available').value = roomData.stock_available || 0;
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Script for sidebar active link
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('.sidebar-nav ul li a');
            // Remove active class from all
            sidebarLinks.forEach(link => link.classList.remove('active'));
            // Add active class to the current page's link
            const currentPath = window.location.pathname.split('/').pop();
            sidebarLinks.forEach(link => {
                if (link.getAttribute('href').includes(currentPath)) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>