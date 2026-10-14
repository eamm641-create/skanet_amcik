<?php
require '../koneksi.php'; require '../layout.php'; cek_login('../');
$id = (int)($_GET['id'] ?? 0);
$d = ['nip'=>'','nama'=>'','no_hp'=>'','alamat'=>''];
if ($id) { $s = $conn->prepare("SELECT * FROM guru WHERE id=?"); $s->bind_param('i',$id); $s->execute(); $d = $s->get_result()->fetch_assoc() ?: $d; }
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip=trim($_POST['nip']); $nama=trim($_POST['nama']); $hp=trim($_POST['no_hp']); $alamat=trim($_POST['alamat']);
    if ($id) { $s=$conn->prepare("UPDATE guru SET nip=?,nama=?,no_hp=?,alamat=? WHERE id=?"); $s->bind_param('ssssi',$nip,$nama,$hp,$alamat,$id); }
    else     { $s=$conn->prepare("INSERT INTO guru (nip,nama,no_hp,alamat) VALUES (?,?,?,?)"); $s->bind_param('ssss',$nip,$nama,$hp,$alamat); }
    if ($s->execute()) { header('Location: index.php?msg=' . urlencode('Data guru tersimpan.')); exit; }
    $err = 'Gagal menyimpan (NIP mungkin sudah dipakai).'; $d = ['nip'=>$nip,'nama'=>$nama,'no_hp'=>$hp,'alamat'=>$alamat];
}
page_start($id ? 'Edit Guru' : 'Tambah Guru', 'guru', '../');
?>
<h1><?= $id ? 'Edit' : 'Tambah' ?> Guru</h1><p class="sub">Isi data dengan lengkap</p>
<div class="card" style="max-width:560px">
<?php if ($err): ?><div class="alert"><?= e($err) ?></div><?php endif; ?>
<form method="post">
  <label>NIP</label><input name="nip" value="<?= e($d['nip']) ?>" required>
  <label>Nama</label><input name="nama" value="<?= e($d['nama']) ?>" required>
  <label>No. HP</label><input name="no_hp" value="<?= e($d['no_hp']) ?>">
  <label>Alamat</label><textarea name="alamat" rows="3"><?= e($d['alamat']) ?></textarea>
  <div style="margin-top:20px;display:flex;gap:10px"><button class="btn">Simpan</button><a class="btn o" href="index.php">Batal</a></div>
</form></div>
<?php page_end();
