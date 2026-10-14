<?php
require 'koneksi.php';
require 'animasi.php';
if (!empty($_SESSION['user'])) { header('Location: dashboard.php'); exit; }

$error = '';
$now = time();
if (($_SESSION['kunci'] ?? 0) > $now) {
    $error = 'Terlalu banyak percobaan. Coba lagi dalam ' . ($_SESSION['kunci'] - $now) . ' detik.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    $st = $conn->prepare("SELECT id,username,password,nama FROM users WHERE username=?");
    $st->bind_param('s', $u);
    $st->execute();
    $row = $st->get_result()->fetch_assoc();
    if ($row && password_verify($p, $row['password'])) {
        session_regenerate_id(true);
        unset($_SESSION['gagal'], $_SESSION['kunci']);
        $_SESSION['user'] = ['id' => $row['id'], 'username' => $row['username'], 'nama' => $row['nama']];
        $_SESSION['baru_masuk'] = true;
        header('Location: selamat.php');
        exit;
    }
    $_SESSION['gagal'] = ($_SESSION['gagal'] ?? 0) + 1;
    if ($_SESSION['gagal'] >= 5) { $_SESSION['kunci'] = $now + 60; $_SESSION['gagal'] = 0; }
    $error = 'Username atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login | <?= e(APP_NAME) ?></title>
<?= favicon() ?>
<?= walk_css() ?>
<style>
*{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%}
body{font-family:'Segoe UI',system-ui,sans-serif;color:#fff;background:#000;overflow:hidden;display:flex;align-items:flex-start;justify-content:center;padding-top:7vh;position:relative}
.bg{position:fixed;inset:0;background-image:linear-gradient(#191919 1px,transparent 1px),linear-gradient(90deg,#191919 1px,transparent 1px);background-size:48px 48px;animation:geser 14s linear infinite;opacity:.85}
@keyframes geser{to{background-position:48px 48px}}
.glow{position:fixed;left:50%;top:30%;width:560px;height:560px;margin:-280px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,.13),transparent 65%);animation:melayang 10s ease-in-out infinite alternate}
@keyframes melayang{from{transform:translate(-26vw,-8vh)}to{transform:translate(26vw,8vh)}}
.box{position:relative;z-index:3;width:380px;max-width:92vw;background:rgba(8,8,8,.9);border:1px solid #3a3a3a;border-radius:16px;padding:34px;backdrop-filter:blur(6px);animation:muncul .9s cubic-bezier(.2,.8,.2,1) both;box-shadow:0 20px 60px rgba(0,0,0,.8)}
@keyframes muncul{from{opacity:0;transform:translateY(40px) scale(.95)}to{opacity:1;transform:none}}
.logo{width:46px;height:46px;margin:0 auto 14px;border:2px solid #fff;display:flex;align-items:center;justify-content:center;border-radius:10px;animation:denyut 3s ease-in-out infinite}
.logo i{width:18px;height:18px;background:#fff;animation:putar 5s linear infinite}
@keyframes denyut{50%{box-shadow:0 0 0 8px rgba(255,255,255,.08)}}
@keyframes putar{to{transform:rotate(360deg)}}
h1{font-family:Consolas,'Courier New',monospace;font-size:26px;letter-spacing:1px;text-align:center;white-space:nowrap}
.ty{display:inline-block;overflow:hidden;vertical-align:bottom;width:0;animation:ketik 1.5s steps(var(--n)) .5s forwards}
@keyframes ketik{to{width:calc(var(--n) * 1ch)}}
.cursor{display:inline-block;width:3px;height:24px;background:#fff;margin-left:3px;vertical-align:-3px;animation:kedip 1s steps(1) infinite}
@keyframes kedip{50%{opacity:0}}
p.s{text-align:center;color:#888;margin:8px 0 18px;font-size:14px}
label{display:block;margin:14px 0 6px;font-size:13px;color:#bbb}
.f{position:relative}
input{width:100%;padding:12px 14px;background:#000;border:1px solid #333;border-radius:8px;color:#fff;font:inherit;transition:.2s}
input:focus{outline:none;border-color:#fff;box-shadow:0 0 0 3px rgba(255,255,255,.12)}
.eye{position:absolute;right:8px;top:50%;transform:translateY(-50%);width:auto;margin:0;padding:5px 9px;background:none;border:1px solid #444;color:#aaa;font-size:12px;border-radius:6px;cursor:pointer}
.eye:hover{background:#fff;color:#000}
.go{width:100%;margin-top:22px;padding:13px;border-radius:8px;border:1px solid #fff;background:#fff;color:#000;font-weight:700;font-size:15px;cursor:pointer;transition:.25s}
.go:hover{background:#000;color:#fff;letter-spacing:1px}
.go.load{pointer-events:none;opacity:.7}
.err{background:#1a1a1a;border:1px solid #fff;padding:10px 12px;border-radius:8px;margin-bottom:4px;font-size:14px;animation:getar .4s}
@keyframes getar{25%{transform:translateX(-7px)}75%{transform:translateX(7px)}}
.lnk{text-align:center;margin-top:16px;font-size:13px}.lnk a{color:#888}.lnk a:hover{color:#fff}
</style>
</head>
<body>
<div class="bg"></div><div class="glow"></div>

<div class="box">
  <div class="logo"><i></i></div>
  <h1><span class="ty" style="--n:<?= strlen(APP_NAME) ?>"><?= e(APP_NAME) ?></span><span class="cursor"></span></h1>
  <p class="s">Sistem Data Siswa, Guru, Mapel &amp; Nilai</p>
  <?php if ($error): ?><div class="err"><?= e($error) ?></div><?php endif; ?>
  <form method="post" autocomplete="off" id="fl">
    <label>Username</label>
    <input type="text" name="username" required autofocus>
    <label>Password</label>
    <div class="f"><input type="password" name="password" id="pw" required style="padding-right:64px"><button type="button" class="eye" id="eye">Lihat</button></div>
    <button type="submit" class="go" id="go">MASUK</button>
  </form>
  <div class="lnk"><a href="reset.php">Lupa password?</a></div>
</div>

<?= walk_scene('full') ?>

<script>
document.getElementById('eye').onclick=function(){var p=document.getElementById('pw');var s=p.type==='password';p.type=s?'text':'password';this.textContent=s?'Sembunyi':'Lihat'};
document.getElementById('fl').onsubmit=function(){var b=document.getElementById('go');b.classList.add('load');b.textContent='Memproses...'};
</script>
</body>
</html>
