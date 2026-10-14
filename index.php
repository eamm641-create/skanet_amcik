<?php
require 'koneksi.php';
header('Location: ' . (empty($_SESSION['user']) ? 'login.php' : 'dashboard.php'));
exit;
