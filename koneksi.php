<?php
// ====== KONFIGURASI ======
define('APP_NAME', 'skanet_amcik');   // ganti nama aplikasi di sini
define('KODE_RESET', 'RESET2026');    // kode rahasia untuk reset.php

date_default_timezone_set('Asia/Jakarta');

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'skanet_amcik';

mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error . '. Pastikan database sudah di-import (skanet_amcik.sql).');
}
$conn->set_charset('utf8mb4');

if (session_status() === PHP_SESSION_NONE) session_start();
if (!headers_sent()) header('Cache-Control: no-store, no-cache, must-revalidate');

// Buat akun admin otomatis saat pertama kali (admin / admin123)
$cek = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc();
if ((int)$cek['c'] === 0) {
    $h = password_hash('admin123', PASSWORD_DEFAULT);
    $st = $conn->prepare("INSERT INTO users (username,password,nama) VALUES ('admin',?,'Administrator')");
    $st->bind_param('s', $h);
    $st->execute();
}

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function cek_login($base = '') {
    if (empty($_SESSION['user'])) {
        header('Location: ' . $base . 'login.php');
        exit;
    }
}
