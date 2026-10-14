<?php
require '../koneksi.php'; require '../layout.php'; cek_login('../');
$id = (int)($_GET['id'] ?? 0);
$d = ['kode'=>'','nama'=>'','guru_id'=>null];
if ($id) { $s = $conn->prepare("SELECT * FROM mapel WHERE id=?"); $s->bind_param('i',$id); $s->execute(); $d = $s->get_result()->fetch_assoc() ?: $d; }
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode=trim($_POST['kode']); $nama=trim($_POST['nama']); $gid = $_POST['guru_id'] !== '' ? (int)$_POST['guru_id'] : null;
    if ($id) { $s=$conn->prepare("UPDATE mapel SET kode=?,nama=?,guru_id=? WHERE id=?"); $s->bind_param('ssii',$kode,$nama,$gid,$id); }
    else     { $s=$conn->prepare("INSERT INTO mapel (kode,nama,guru_id) VALUES (?,?,?)"); $s->bind_param('ssi',$kode,$nama,$gid); }
    if ($s->execute()) { header('Location: index.php?msg=' . urlencode('Data mapel tersimpan.')); exit; }
    $err = 'Gagal menyimpan (kode mungkin sudah dipakai).'; $d = ['kode'=>$kode,'nama'=>$nama,'guru_id'=>$gid];
}
$guru = $conn->query("SELECT id,nama FROM guru ORDER BY nama");
page_start($id ? 'Edit Mapel' : 'Tambah Mapel', 'mapel', '../');
?>
<h1><?= $id ? 'Edit' : 'Tambah' ?> Mapel</h1><p class="sub">Isi data dengan lengkap</p>
<div class="card" style="max-width:560px">
<?php if ($err): ?><div class="alert"><?= e($err) ?></div><?php endif; ?>
<form method="post">
  <label>Kode</label><input name="kode" value="<?= e($d['kode']) ?>" required>
  <label>Nama Mapel</label><input name="nama" value="<?= e($d['nama']) ?>" required>
  <label>Guru Pengampu</label>
  <select name="guru_id"><option value="">- Belum ada -</option>
    <?php while ($g = $guru->fetch_assoc()): ?><option value="<?= $g['id'] ?>" <?= (int)$d['guru_id']===(int)$g['id']?'selected':'' ?>><?= e($g['nama']) ?></option><?php endwhile; ?>
  </select>
  <div style="margin-top:20px;display:flex;gap:10px"><button class="btn">Simpan</button><a class="btn o" href="index.php">Batal</a></div>
</form></div>
<?php page_end();
