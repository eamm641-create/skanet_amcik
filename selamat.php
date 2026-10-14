<?php
require 'koneksi.php';
require 'animasi.php';
cek_login();

// layar selamat datang hanya muncul sekali setelah login
if (empty($_SESSION['baru_masuk'])) { header('Location: dashboard.php'); exit; }
unset($_SESSION['baru_masuk']);

$h = (int)date('H');
$sapa = $h < 11 ? 'pagi' : ($h < 15 ? 'siang' : ($h < 18 ? 'sore' : 'malam'));
transition_page('Selamat ' . $sapa . ', ' . $_SESSION['user']['nama'], 'Menyiapkan dashboard ' . APP_NAME . '...', 'dashboard.php', 3000, 'r');
