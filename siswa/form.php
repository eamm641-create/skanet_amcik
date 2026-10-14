<?php
require '../koneksi.php'; require '../layout.php'; cek_login('../');
$id = (int)($_GET['id'] ?? 0);
$d = ['nis'=>'','nama'=>'','kelas'=>'','jk'=>'L','alamat'=>''];
if ($id) { $s = $conn->prepare("SELECT * FROM siswa WHERE id=?"); $s->bind_param('i',$id); $s->execute(); $d = $s->get_result()->fetch_assoc() ?: $d; }
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nis=trim($_POST['nis']); $nama=trim($_POST['nama']); $kelas=trim($_POST['kelas']); $jk=$_POST['jk']; $alamat=trim($_POST['alamat']);
    if ($id) { $s=$conn->prepare("UPDATE siswa SET nis=?,nama=?,kelas=?,jk=?,alamat=? WHERE id=?"); $s->bind_param('sssssi',$nis,$nama,$kelas,$jk,$alamat,$id); }
    else     { $s=$conn->prepare("INSERT INTO siswa (nis,nama,kelas,jk,alamat) VALUES (?,?,?,?,?)"); $s->bind_param('sssss',$nis,$nama,$kelas,$jk,$alamat); }
    if ($s->execute()) { header('Location: index.php?msg=' . urlencode('Data siswa tersimpan.')); exit; }
    $err = 'Gagal menyimpan (NIS mungkin sudah dipakai).'; $d = compact('nis','nama','kelas','jk','alamat');
}
page_start($id ? 'Edit Siswa' : 'Tambah Siswa', 'siswa', '../');
?>
<h1><?= $id ? 'Edit' : 'Tambah' ?> Siswa</h1><p class="sub">Isi data dengan lengkap</p>
<div class="card" style="max-width:560px">
<?php if ($err): ?><div class="alert"><?= e($err) ?></div><?php endif; ?>
<form method="post">
  <label>NIS</label><input name="nis" value="<?= e($d['nis']) ?>" required>
  <label>Nama</label><input name="nama" value="<?= e($d['nama']) ?>" required>
  <label>Kelas</label><input name="kelas" value="<?= e($d['kelas']) ?>" required>
  <label>Jenis Kelamin</label>
  <select name="jk"><option value="L" <?= $d['jk']==='L'?'selected':'' ?>>Laki-laki</option><option value="P" <?= $d['jk']==='P'?'selected':'' ?>>Perempuan</option></select>
  <label>Alamat</label><textarea name="alamat" rows="3"><?= e($d['alamat']) ?></textarea>
  <div style="margin-top:20px;display:flex;gap:10px"><button class="btn">Simpan</button><a class="btn o" href="index.php">Batal</a></div>
</form></div>
<?php page_end();
