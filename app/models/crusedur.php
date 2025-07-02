<?php
// controllers/AuthService.php

// --- Fungsi untuk koneksi Database MySQL (menggunakan PDO) ---
function getDbConnection() {
    // Sesuaikan kredensial database Anda
    $dbHost = 'localhost';
    $dbName = 'hotel'; // Menggunakan 'kasir_db' agar konsisten dengan konteks proyek Anda
    $dbUser = 'root';      // Ganti dengan username database Anda
    $dbPass = '';          // Ganti dengan password database Anda

    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Set default fetch mode
        return $pdo;
    } catch (PDOException $e) {
        // Dalam produksi, log error ini, jangan tampilkan ke pengguna
        error_log("Database connection error: " . $e->getMessage());
        // Untuk pengembangan, bisa tampilkan
        die("Koneksi database gagal: " . $e->getMessage());
    }
}

/**
 * Fungsi untuk mengotentikasi pengguna admin.
 *
 * @param string $username Username yang dimasukkan pengguna.
 * @param string $password Password yang dimasukkan pengguna (plaintext).
 * @return bool True jika otentikasi berhasil sebagai admin, false jika gagal.
 */
function authenticateAdmin($username, $password) {
    $pdo = getDbConnection(); // Dapatkan koneksi PDO

    try {
        // Buat tabel admin jika belum ada
        // Menambahkan kolom 'role' untuk membedakan admin dari user lain jika diperlukan
        $pdo->exec("CREATE TABLE IF NOT EXISTS admin (
            id_admin INT(11) PRIMARY KEY AUTO_INCREMENT,
            nama VARCHAR(100) DEFAULT NULL,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL, -- VARCHAR(255) untuk menyimpan hash password yang aman
            role VARCHAR(50) NOT NULL DEFAULT 'user' -- Menambahkan kolom role
        )");

        // Periksa apakah ada admin ber-role 'admin'. Jika tidak, tambahkan admin default.
        // Ini hanya untuk setup awal. Di produksi, admin harus dibuat melalui proses pendaftaran yang aman.
        $stmtCount = $pdo->query("SELECT COUNT(*) FROM admin WHERE role = 'admin'");
        if ($stmtCount->fetchColumn() == 0) {
            // Hash password menggunakan password_hash() yang aman (BUKAN MD5!)
            $hashedAdminPass = password_hash('admin', PASSWORD_DEFAULT); // Password default: admin
            $hashedElenPass = password_hash('elen', PASSWORD_DEFAULT);   // Password default: elen

            // Gunakan prepared statement untuk insert default admin
            $stmtInsertAdmin = $pdo->prepare("INSERT INTO admin (username, password, nama, role) VALUES (?, ?, ?, ?)");
            
            // Insert admin 'admin'
            $stmtInsertAdmin->execute(['admin', $hashedAdminPass, 'Admin Utama', 'admin']);

            // Insert admin 'elen'
            $stmtInsertAdmin->execute(['elen', $hashedElenPass, 'Elen Kasir', 'admin']);
        }

        // Query database untuk username yang cocok dari tabel 'admin'
        // Mengambil kolom 'role' juga
        $stmt = $pdo->prepare("SELECT id_admin, username, password, nama, role FROM admin WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verifikasi password menggunakan password_verify() (AMAN)
            // Ini adalah pengganti md5($password) === $user['password']
            if (password_verify($password, $user['password'])) {
                // Verifikasi role (pastikan hanya admin yang bisa login ke dashboard admin)
                if ($user['role'] === 'admin') {
                    // Otentikasi berhasil sebagai admin
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_id'] = $user['id_admin'];
                    $_SESSION['user_name_display'] = $user['nama'];
                    $_SESSION['user_role'] = $user['role']; // Simpan role yang sebenarnya
                    return true;
                }
            }
        }
    } catch (PDOException $e) {
        error_log("Authentication query error: " . $e->getMessage());
        return false; // Ada error database, anggap otentikasi gagal
    } finally {
        // Tutup koneksi database (PDO akan otomatis ditutup saat skrip berakhir,
        // tapi ini untuk kejelasan jika Anda ingin menutupnya secara eksplisit)
        $pdo = null;
    }
    return false; // Default: otentikasi gagal atau bukan admin
}
?>