<?php
// views/admin.php

// Start the session at the very beginning of the script.
// This is crucial for session variables like 'user_name_display'.
session_start();

// Include the database configuration file.
// This file is expected to initialize $pdoHotel and potentially $pdoKasir.
require_once '../../db_connection.php';

// Include the report controller file.
// Pastikan path ini benar sesuai struktur proyek Anda
require_once '../controllers/ReportController.php';

// --- Authentication and Authorization (Re-introducing good practice) ---
// In a production environment, you MUST re-implement robust authentication
// to ensure only authorized administrators can access this page.
// For the purpose of fixing the provided code, this part is commented out,
// but it's vital for security.
/*
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    // Redirect to login page or show an access denied message
    header('Location: loginadmin.php'); // Or a more appropriate login page
    exit();
}
*/

// Get the logged-in admin's name for display.
// Fallback to 'Admin' if not set in session.
$loggedInAdminName = $_SESSION['user_name_display'] ?? 'Admin';

$reservations = []; // Initialize an empty array for reservation data
$reportData = [];   // Initialize an empty array for monthly report data
$selectedYear = date('Y'); // Default year for reports is the current year

// Variables to hold error messages for reservations and reports
$reservationErrorMessage = '';
$reportErrorMessage = '';
$generalErrorMessage = ''; // For general errors not related to specific fetches

// --- Logic to fetch reservation data (using $pdoHotel) ---
// Check if $pdoHotel object is properly initialized and available.
if (isset($pdoHotel) && $pdoHotel instanceof PDO) { // Verify $pdoHotel is a PDO object
    try {
        $sqlReservations = "SELECT * FROM reservations ORDER BY created_at DESC";
        $stmtReservations = $pdoHotel->query($sqlReservations);
        $reservations = $stmtReservations->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array
    } catch (PDOException $e) {
        // Log the detailed error for debugging purposes (e.g., to a file or system log)
        error_log("Error fetching reservations from hotel_db: " . $e->getMessage());
        // User-friendly error message
        $reservationErrorMessage = "Terjadi kesalahan saat mengambil data reservasi dari database hotel. Mohon coba lagi nanti.";
    }
} else {
    // If $pdoHotel is not a PDO object, it means the connection failed or wasn't established.
    $generalErrorMessage = "Koneksi ke database hotel gagal. Reservasi tidak dapat dimuat.";
    error_log("PDO Hotel connection failed or not established in admin.php for reservations.");
}

// --- Logic to fetch monthly report data (using $pdoHotel) ---
// Get the selected year from GET request, default to current year if not valid.
if (isset($_GET['year']) && is_numeric($_GET['year'])) {
    $selectedYear = htmlspecialchars($_GET['year']);
}

// Check if $pdoHotel object is properly initialized and available before calling report function.
if (isset($pdoHotel) && $pdoHotel instanceof PDO) { // Verify $pdoHotel is a PDO object
    try {
        // Ensure getMonthlyReservationReport handles the PDO object correctly
        // Check if the function exists before calling, though require_once should ensure this.
        if (function_exists('getMonthlyReservationReport')) {
            $reportData = getMonthlyReservationReport($pdoHotel, $selectedYear);
        } else {
            $reportErrorMessage = "Fungsi laporan bulanan tidak ditemukan. Periksa ReportController.php.";
            error_log("Function 'getMonthlyReservationReport' not found in admin.php.");
        }
    } catch (PDOException $e) {
        // Log the detailed error for debugging purposes
        error_log("Error fetching monthly report from hotel_db in admin.php: " . $e->getMessage());
        // User-friendly error message
        $reportErrorMessage = "Terjadi kesalahan saat mengambil laporan bulanan dari database hotel. Mohon coba lagi nanti.";
    } catch (Exception $e) {
        // Catch any other general exceptions from getMonthlyReservationReport
        error_log("General error fetching monthly report: " . $e->getMessage());
        $reportErrorMessage = "Terjadi kesalahan umum saat mengambil laporan bulanan. Mohon coba lagi.";
    }
} else {
    // If $pdoHotel is not a PDO object, report connection failure.
    // This message might be redundant if $generalErrorMessage is already set.
    if (empty($generalErrorMessage)) {
        $reportErrorMessage = "Koneksi ke database hotel gagal. Laporan tidak dapat dimuat.";
    }
    error_log("PDO Hotel connection failed or not established in admin.php for reports.");
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Reservasi Hotel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/css_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('.sidebar-nav ul li a');
            const reservationsTab = document.querySelector('.reservations-tab');
            const reportsTab = document.querySelector('.reports-tab');

            // Function to show the correct tab
            function showTab(tabName) {
                // Hide all tabs first
                reservationsTab.style.display = 'none';
                reportsTab.style.display = 'none';

                // Show the selected tab
                if (tabName === 'reservations') {
                    reservationsTab.style.display = 'block';
                } else if (tabName === 'reports') {
                    reportsTab.style.display = 'block';
                }

                // Update active class in sidebar
                sidebarLinks.forEach(link => {
                    link.classList.remove('active');
                    const href = link.getAttribute('href');
                    if (href) { // Ensure href exists
                        const urlParams = new URLSearchParams(href.split('?')[1]);
                        const linkTab = urlParams.get('tab');
                        if (linkTab === tabName) {
                            link.classList.add('active');
                        }
                    }
                });
            }

            // Handle clicks on sidebar links
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Only prevent default for links targeting tabs on this page
                    if (this.getAttribute('href').startsWith('admin.php?tab=')) {
                        e.preventDefault();
                        const href = this.getAttribute('href');
                        const urlParams = new URLSearchParams(href.split('?')[1]);
                        const tab = urlParams.get('tab');
                        if (tab) {
                            showTab(tab);
                            // Update URL without page reload
                            history.pushState(null, '', `admin.php?tab=${tab}`);
                        }
                    }
                });
            });

            // Show the appropriate tab when the page loads (based on URL or default)
            const urlParams = new URLSearchParams(window.location.search);
            const initialTab = urlParams.get('tab') || 'reservations';
            showTab(initialTab);
        });
    </script>
</head>
<body>
    <div class="admin-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>Admin Panel</h3>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="admin.php?tab=reservations" class="active"><i class="fas fa-book"></i> Kelola Pemesanan</a></li>
                    <li><a href="admin.php?tab=reports"><i class="fas fa-chart-line"></i> Laporan Bulanan</a></li>
                    <li><a href="manage_rooms.php"><i class="fas fa-hotel"></i> Kelola Kamar</a></li>
                        <li><a href="../../index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="admin-content">
            <header class="admin-header">
                <h1>Selamat Datang, <?= htmlspecialchars($loggedInAdminName) ?>!</h1>
                <div class="user-info">
                    <p>Role: Admin</p>
                </div>
            </header>

            <div class="content-section">
                <?php
                // Display general error messages (e.g., database connection issues)
                if (!empty($generalErrorMessage)): ?>
                    <p class='message-box error-message'><?= htmlspecialchars($generalErrorMessage) ?></p>
                <?php endif; ?>

                <?php
                // Display success or error messages from PHP status update process
                if (isset($_GET['status'])) {
                    $status = htmlspecialchars($_GET['status']);
                    $message = htmlspecialchars($_GET['message'] ?? ''); // Menggunakan null coalescing operator
                    if ($status == 'success') {
                        echo "<p class='message-box success-message'>Status pemesanan berhasil diperbarui!</p>";
                    } elseif ($status == 'error') {
                        echo "<p class='message-box error-message'>Gagal memperbarui status pemesanan. ";
                        if ($message == 'invalid_request') {
                            echo "Permintaan tidak valid.";
                        } elseif ($message == 'db_error') {
                            echo "Kesalahan database.";
                        } elseif ($message == 'not_found') {
                            echo "Pemesanan tidak ditemukan.";
                        } else {
                            echo "Terjadi kesalahan tidak dikenal.";
                        }
                        echo "</p>";
                    }
                }
                ?>

                <div class="reservations-tab">
                    <h3>Daftar Pemesanan</h3>
                    <?php if (!empty($reservationErrorMessage)): ?>
                        <p class='message-box error-message'><?= htmlspecialchars($reservationErrorMessage) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($reservations)): ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID Reservasi</th>
                                    <th>Nama Tamu</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Tipe Kamar</th>
                                    <th>Tamu</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Permintaan Khusus</th>
                                    <th>Status</th>
                                    <th>Tanggal Pesan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservations as $res): ?>
                                <tr>
                                    <td><?= htmlspecialchars($res['reservation_id'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($res['guest_name'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($res['check_in_date'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($res['check_out_date'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($res['room_type'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($res['num_guests'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($res['email'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($res['phone_number'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($res['special_requests'] ?? '') ?></td>
                                    <td>
                                        <form action="../controllers/UpdateReservationStatus.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="reservation_id" value="<?= htmlspecialchars($res['reservation_id'] ?? '') ?>">
                                            <select name="new_status" onchange="this.form.submit()" class="status-select status-<?= strtolower($res['status'] ?? 'pending') ?>">
                                                <option value="pending" <?= ((($res['status'] ?? '') == 'pending') ? 'selected' : '') ?>>Pending</option>
                                                <option value="confirmed" <?= ((($res['status'] ?? '') == 'confirmed') ? 'selected' : '') ?>>Confirmed</option>
                                                <option value="cancelled" <?= ((($res['status'] ?? '') == 'cancelled') ? 'selected' : '') ?>>Cancelled</option>
                                                <option value="completed" <?= ((($res['status'] ?? '') == 'completed') ? 'selected' : '') ?>>Completed</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($res['created_at'] ?? 'now'))) ?></td>
                                    <td>
                                        <a href="#" class="btn-detail"><i class="fas fa-eye"></i> Detail</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                        <p>Tidak ada data pemesanan yang tersedia.</p>
                    <?php endif; ?>
                </div>

                <div class="reports-tab report-section">
                    <h3>Laporan Bulanan Reservasi Tahun <?= htmlspecialchars($selectedYear) ?></h3>
                    <?php if (!empty($reportErrorMessage)): ?>
                        <p class='message-box error-message'><?= htmlspecialchars($reportErrorMessage) ?></p>
                    <?php endif; ?>

                    <div class="report-filter">
                        <form action="admin.php" method="GET">
                            <input type="hidden" name="tab" value="reports">
                            <label for="report_year">Pilih Tahun:</label>
                            <select name="year" id="report_year">
                                <?php
                                $current_year = date('Y');
                                for ($year = $current_year; $year >= 2020; $year--):
                                ?>
                                <option value="<?= $year ?>" <?= ($selectedYear == $year) ? 'selected' : '' ?>><?= $year ?></option>
                                <?php endfor; ?>
                            </select>
                            <button type="submit">Tampilkan Laporan</button>
                        </form>
                    </div>

                    <?php if (!empty($reportData)): ?>
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Total Reservasi</th>
                                    <th>Total Tamu</th>
                                    <th>Confirmed</th>
                                    <th>Pending</th>
                                    <th>Cancelled</th>
                                    <th>Completed</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reportData as $report): ?>
                                <tr>
                                    <td><?= htmlspecialchars($report['month_name'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($report['total_reservations'] ?? 0) ?></td>
                                    <td><?= htmlspecialchars($report['total_guests'] ?? 0) ?></td>
                                    <td><?= htmlspecialchars($report['confirmed_reservations'] ?? 0) ?></td>
                                    <td><?= htmlspecialchars($report['pending_reservations'] ?? 0) ?></td>
                                    <td><?= htmlspecialchars($report['cancelled_reservations'] ?? 0) ?></td>
                                    <td><?= htmlspecialchars($report['completed_reservations'] ?? 0) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Tidak ada data laporan untuk tahun ini.</p>
                    <?php endif; ?>
                </div>

            </div>
        </main>
    </div>
</body>
</html>