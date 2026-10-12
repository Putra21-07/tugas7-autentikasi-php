<?php
require_once 'config.php';

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitasi & Validasi Input
    $nama     = trim($_POST['nama'] ?? '');
    $email    = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (empty($nama) || empty($email) || empty($password)) {
        $error = "Semua bidang formulir wajib diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal terdiri dari 6 karakter!";
    } else {
        // Cek apakah email sudah terdaftar
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        if ($stmt->fetch()) {
            $error = "Email sudah digunakan!";
        } else {
            // Hash Password secara aman (BCRYPT)
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("INSERT INTO users (nama, email, password) VALUES (:nama, :email, :password)");
            if ($stmt->execute(['nama' => $nama, 'email' => $email, 'password' => $hashedPassword])) {
                $success = "Registrasi berhasil! Silakan <a href='login.php'>login di sini</a>.";
            } else {
                $error = "Gagal mendaftar, terjadi kesalahan sistem.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Tugas Rutin 7</title>
    <style>
        body { font-family: sans-serif; background: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 320px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007bff; border: none; color: white; font-weight: bold; border-radius: 4px; cursor: pointer; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; font-size: 14px; }
        .alert-error { background: #f8d7da; color: #721c24; }
        .alert-success { background: #d4edda; color: #155724; }
    </style>
</head>
<body>
<div class="card">
    <h2>Registrasi Akun</h2>
    <?php if ($error): ?><div class="alert alert-error"><?= sanitize($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" required maxlength="100">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required maxlength="100">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required minlength="6">
        </div>
        <button type="submit">Daftar</button>
    </form>
    <p style="text-align: center; font-size: 14px; margin-top: 15px;">
        Sudah punya akun? <a href="login.php">Login</a>
    </p>
</div>
</body>
</html>