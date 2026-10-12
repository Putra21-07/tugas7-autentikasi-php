<?php
require_once 'config.php';

// Validasi Session: Jika belum login, tendang ke login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Tugas Rutin 7</title>
    <style>
        body { font-family: sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 15px; }
        .btn-logout { background: #dc3545; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-weight: bold; }
        .btn-logout:hover { background: #c82333; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Dashboard</h1>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
    
    <h3>Selamat Datang, <?= sanitize($_SESSION['user_nama']) ?>! 👋</h3>
    <p>Email Anda: <strong><?= sanitize($_SESSION['user_email']) ?></strong></p>
    
    <hr>
    
    <p>Aplikasi berhasil dijalankan dengan standar keamanan:</p>
    <ul>
        <li>Pencegahan SQL Injection via PDO Prepared Statements[cite: 1]</li>
        <li>Pencegahan XSS via Output Sanitization[cite: 1]</li>
        <li>Password dikriptografi dengan BCRYPT Hash[cite: 1]</li>
        <li>Manajemen Session terlindungi[cite: 1]</li>
    </ul>
</div>
</body>
</html>