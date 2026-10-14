<?php
require 'koneksi.php';
require 'animasi.php';
$msg = ''; $ok = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = $_POST['kode'] ?? '';
    $u    = trim($_POST['username'] ?? '');
    $p    = $_POST['password'] ?? '';
    if ($kode !== KODE_RESET) {
        $msg = 'Kode reset salah.';
    } elseif (strlen($p) < 6) {
        $msg = 'Password minimal 6 karakter.';
    } else {
        $h = password_hash($p, PASSWORD_DEFAULT);
        $st = $conn->prepare("UPDATE users SET password=? WHERE username=?");
        $st->bind_param('ss', $h, $u);
        $st->execute();
        if ($st->affected_rows > 0) { $ok = true; $msg = 'Password berhasil direset. Silakan login.'; }
        else $msg = 'Username tidak ditemukan.';
    }
}
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Reset Password | <?= e(APP_NAME) ?></title>
<?= favicon() ?>
<?= walk_css() ?>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',sans-serif;background:#000;color:#fff;min-height:100vh;display:flex;align-items:center;justify-content:center}
.box{position:relative;z-index:3;width:380px;max-width:92vw;background:#0d0d0d;border:1px solid #333;border-radius:16px;padding:34px;animation:m .6s both}
@keyframes m{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:none}}
h1{font-size:22px;margin-bottom:4px}p{color:#888;font-size:14px;margin-bottom:10px}
label{display:block;margin:14px 0 6px;font-size:13px;color:#bbb}
input{width:100%;padding:12px;background:#000;border:1px solid #333;border-radius:8px;color:#fff;font:inherit}
input:focus{outline:none;border-color:#fff}
button{width:100%;margin-top:20px;padding:12px;border-radius:8px;border:1px solid #fff;background:#fff;color:#000;font-weight:700;cursor:pointer}
button:hover{background:#000;color:#fff}
.a{border:1px solid #fff;padding:10px;border-radius:8px;margin:10px 0;font-size:14px}
.l{text-align:center;margin-top:16px;font-size:13px}.l a{color:#888}.l a:hover{color:#fff}
</style></head><body>
<div class="box">
  <h1>Reset Password</h1>
  <p><?= e(APP_NAME) ?></p>
  <?php if ($msg): ?><div class="a"><?= e($msg) ?></div><?php endif; ?>
  <form method="post" autocomplete="off">
    <label>Kode Reset</label><input type="password" name="kode" required>
    <label>Username</label><input type="text" name="username" value="admin" required>
    <label>Password Baru</label><input type="password" name="password" required>
    <button>RESET</button>
  </form>
  <div class="l"><a href="login.php">&larr; Kembali ke login</a></div>
</div>
<?= walk_scene('full') ?>
</body></html>
