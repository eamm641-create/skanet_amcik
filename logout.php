<?php
require 'koneksi.php';
require 'animasi.php';

$nama = $_SESSION['user']['nama'] ?? '';
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $c = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $c['path'], $c['domain'], $c['secure'], $c['httponly']);
}
session_destroy();

transition_page('Sampai jumpa' . ($nama !== '' ? ', ' . $nama : ''), 'Kamu berhasil keluar dari ' . APP_NAME, 'login.php', 2600, 'l');
