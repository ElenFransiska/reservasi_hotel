<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - Reservasi Hotel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Dashboard</h2>
        <p>Selamat datang, <?php echo $_SESSION['username']; ?>!</p>
        
        <?php if ($_SESSION['role'] == 'admin'): ?>
            <h3>Kelola Kamar</h3>
            <a href="rooms.php">Lihat Kamar</a>
            <h3>Status Customer</h3>
            <a href="reservations.php">Lihat Penyewaan</a>
        <?php else: ?>
            <h3>Penyewaan Kamar</h3>
            <a href="reservations.php">Lihat Penyewaan Anda</a>
        <?php endif; ?>
        
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
