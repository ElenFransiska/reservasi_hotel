<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login Karyawan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Pastikan path CSS ini benar dan berisi gaya dasar yang konsisten -->
    <link rel="stylesheet" href="../../assets/css/css_login.css"> 
    
</head>
<body>
    <div class="container login-form-container">
        <h2>Login User</h2>
        <?php
        if(isset($_GET['error'])){
            echo "<p class='error-message'>Salah username atau password</p>";
        }
        ?>
        <form method="post" action="../controllers/prosesloginUser2.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" size="30" placeholder="Masukkan username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" size="30" placeholder="Masukkan password" required/>
            </div>
            <button type="submit" class="login-button">
                <span class="arrow-icon">&rarr;</span> OK
            </button>
            <p class="back-link"><a href="../../index.php">&larr; Kembali ke Home</a></p>
        </form>
    </div>
</body>
</html>