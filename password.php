<?php
require 'koneksi.php';
require 'layout.php';
cek_login();
$msg = ''; $ok = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lama = $_POST['lama'] ?? ''; $baru = $_POST['baru'] ?? ''; $ulang = $_POST['ulang'] ?? '';
    $uid = (int)$_SESSION['user']['id'];
    $s = $conn->prepare("SELECT password FROM users WHERE id=?"); $s->bind_param('i', $uid); $s->execute();
    $row = $s->get_result()->fetch_assoc();
    if (!$row || !password_verify($lama, $row['password'])) $msg = 'Password lama salah.';
    elseif (strlen($baru) < 6) $msg = 'Password baru minimal 6 karakter.';
    elseif ($baru !== $ulang) $msg = 'Konfirmasi password tidak cocok.';
    else {
        $h = password_hash($baru, PASSWORD_DEFAULT);
        $u = $conn->prepare("UPDATE users SET password=? WHERE id=?"); $u->bind_param('si', $h, $uid); $u->execute();
        $ok = true; $msg = 'Password berhasil diubah.';
    }
}
page_start('Ubah Password', 'password');
?>
<h1>Ubah Password</h1><p class="sub">Gunakan password yang sulit ditebak</p>
<div class="card" style="max-width:480px">
<?php if ($msg): ?><div class="alert"><?= e($msg) ?></div><?php endif; ?>
<form method="post" autocomplete="off">
  <label>Password Lama</label><input type="password" name="lama" required>
  <label>Password Baru</label><input type="password" name="baru" required>
  <label>Ulangi Password Baru</label><input type="password" name="ulang" required>
  <div style="margin-top:20px"><button class="btn">Simpan</button></div>
</form></div>
<?php page_end();
