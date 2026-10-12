<?php
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Email dan password wajib diisi!";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        // Verifikasi password hash
        if ($user && password_verify($password, $user['password'])) {
            // Mencegah Session Fixation Attack
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nama'] = $user['nama'];
            $_SESSION['user_email'] = $user['email'];

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Email atau password salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Tugas Rutin 7</title>
    <style>
        body { font-family: sans-serif; background: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 320px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #28a745; border: none; color: white; font-weight: bold; border-radius: 4px; cursor: pointer; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px; font-size: 14px; }
    </style>
</head>
<body>
<div class="card">
    <h2>Login User</h2>
    <?php if ($error): ?><div class="alert-error"><?= sanitize($error) ?></div><?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Masuk</button>
    </form>
    <p style="text-align: center; font-size: 14px; margin-top: 15px;">
        Belum punya akun? <a href="register.php">Daftar</a>
    </p>
</div>
</body>
</html>