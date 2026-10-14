<?php
require '../koneksi.php'; require '../layout.php'; cek_login('../');
$id = (int)($_GET['id'] ?? 0);
$d = ['siswa_id'=>0,'mapel_id'=>0,'tugas'=>0,'uts'=>0,'uas'=>0];
if ($id) { $s = $conn->prepare("SELECT * FROM nilai WHERE id=?"); $s->bind_param('i',$id); $s->execute(); $d = $s->get_result()->fetch_assoc() ?: $d; }
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sid=(int)$_POST['siswa_id']; $mid=(int)$_POST['mapel_id'];
    $t=min(100,max(0,(float)$_POST['tugas'])); $u=min(100,max(0,(float)$_POST['uts'])); $a=min(100,max(0,(float)$_POST['uas']));
    if ($id) { $s=$conn->prepare("UPDATE nilai SET siswa_id=?,mapel_id=?,tugas=?,uts=?,uas=? WHERE id=?"); $s->bind_param('iidddi',$sid,$mid,$t,$u,$a,$id); }
    else     { $s=$conn->prepare("INSERT INTO nilai (siswa_id,mapel_id,tugas,uts,uas) VALUES (?,?,?,?,?)"); $s->bind_param('iiddd',$sid,$mid,$t,$u,$a); }
    if ($sid && $mid && $s->execute()) { header('Location: index.php?msg=' . urlencode('Nilai tersimpan.')); exit; }
    $err = 'Gagal menyimpan. Pilih siswa dan mapel.'; $d = ['siswa_id'=>$sid,'mapel_id'=>$mid,'tugas'=>$t,'uts'=>$u,'uas'=>$a];
}
$siswa = $conn->query("SELECT id,nis,nama FROM siswa ORDER BY nama");
$mapel = $conn->query("SELECT id,nama FROM mapel ORDER BY nama");
page_start($id ? 'Edit Nilai' : 'Input Nilai', 'nilai', '../');
?>
<h1><?= $id ? 'Edit' : 'Input' ?> Nilai</h1><p class="sub">Nilai 0 - 100</p>
<div class="card" style="max-width:560px">
<?php if ($err): ?><div class="alert"><?= e($err) ?></div><?php endif; ?>
<form method="post">
  <label>Siswa</label>
  <select name="siswa_id" required><option value="">- Pilih siswa -</option>
    <?php while ($r = $siswa->fetch_assoc()): ?><option value="<?= $r['id'] ?>" <?= (int)$d['siswa_id']===(int)$r['id']?'selected':'' ?>><?= e($r['nis'].' - '.$r['nama']) ?></option><?php endwhile; ?>
  </select>
  <label>Mata Pelajaran</label>
  <select name="mapel_id" required><option value="">- Pilih mapel -</option>
    <?php while ($r = $mapel->fetch_assoc()): ?><option value="<?= $r['id'] ?>" <?= (int)$d['mapel_id']===(int)$r['id']?'selected':'' ?>><?= e($r['nama']) ?></option><?php endwhile; ?>
  </select>
  <label>Nilai Tugas</label><input type="number" step="0.01" min="0" max="100" name="tugas" value="<?= e($d['tugas']) ?>" required>
  <label>Nilai UTS</label><input type="number" step="0.01" min="0" max="100" name="uts" value="<?= e($d['uts']) ?>" required>
  <label>Nilai UAS</label><input type="number" step="0.01" min="0" max="100" name="uas" value="<?= e($d['uas']) ?>" required>
  <div style="margin-top:20px;display:flex;gap:10px"><button class="btn">Simpan</button><a class="btn o" href="index.php">Batal</a></div>
</form></div>
<?php page_end();
