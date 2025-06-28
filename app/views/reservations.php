<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Penyewaan Kamar - Reservasi Hotel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2 class="reservations-title">Penyewaan Kamar Anda</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="create_reservation.php">
            <div class="form-group">
                <label for="room_id">Pilih Kamar</label>
                <select id="room_id" name="room_id" required>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?php echo $room['id']; ?>"><?php echo $room['room_type']; ?> - Rp <?php echo number_format($room['price'], 0, ',', '.'); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="check_in">Check-in</label>
                <input type="date" id="check_in" name="check_in" required>
            </div>
            <div class="form-group">
                <label for="check_out">Check-out</label>
                <input type="date" id="check_out" name="check_out" required>
            </div>
            <button type="submit" class="btn-login">Sewa Kamar</button>
        </form>

        <h3>Reservasi Anda</h3>
        <ul>
            <?php foreach ($reservations as $reservation): ?>
                <li>
                    Kamar ID: <?php echo $reservation['room_id']; ?>, Check-in: <?php echo $reservation['check_in']; ?>, Check-out: <?php echo $reservation['check_out']; ?>
                    <a href="cancel_reservation.php?id=<?php echo $reservation['id']; ?>">Batalkan</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
