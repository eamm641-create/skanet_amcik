<?php
require '../koneksi.php'; require '../layout.php'; cek_login('../');
$q = '%' . ($_GET['q'] ?? '') . '%';
$st = $conn->prepare("SELECT n.*, s.nama siswa, s.kelas, m.nama mapel FROM nilai n
  JOIN siswa s ON s.id=n.siswa_id JOIN mapel m ON m.id=n.mapel_id
  WHERE s.nama LIKE ? OR m.nama LIKE ? OR s.kelas LIKE ? ORDER BY s.nama, m.nama");
$st->bind_param('sss', $q, $q, $q); $st->execute();
$res = $st->get_result();
page_start('Data Nilai', 'nilai', '../');
?>
<h1>Data Nilai</h1><p class="sub">Nilai akhir = Tugas 30% + UTS 30% + UAS 40%</p>
<?php if (!empty($_GET['msg'])): ?><div class="alert"><?= e($_GET['msg']) ?></div><?php endif; ?>
<div class="card">
  <div class="bar">
    <form class="search" method="get"><input name="q" placeholder="Cari siswa / mapel / kelas" value="<?= e($_GET['q'] ?? '') ?>"><button class="btn">Cari</button></form>
    <a class="btn" href="form.php">+ Input Nilai</a>
  </div>
  <div class="tw"><table>
    <tr><th>Siswa</th><th>Kelas</th><th>Mapel</th><th>Tugas</th><th>UTS</th><th>UAS</th><th>Akhir</th><th>Predikat</th><th>Aksi</th></tr>
    <?php while ($r = $res->fetch_assoc()): $a = $r['tugas']*0.3 + $r['uts']*0.3 + $r['uas']*0.4; ?>
    <tr><td><?= e($r['siswa']) ?></td><td><?= e($r['kelas']) ?></td><td><?= e($r['mapel']) ?></td>
      <td><?= (float)$r['tugas'] ?></td><td><?= (float)$r['uts'] ?></td><td><?= (float)$r['uas'] ?></td>
      <td><b><?= number_format($a, 1) ?></b></td><td><span class="badge"><?= grade($a) ?></span></td>
      <td><a class="btn o s" href="form.php?id=<?= $r['id'] ?>">Edit</a>
      <form class="inl" method="post" action="hapus.php" onsubmit="return confirm('Hapus nilai ini?')"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="btn s">Hapus</button></form></td></tr>
    <?php endwhile; ?>
  </table></div>
</div>
<?php page_end();
