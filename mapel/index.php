<?php
require '../koneksi.php'; require '../layout.php'; cek_login('../');
$q = '%' . ($_GET['q'] ?? '') . '%';
$st = $conn->prepare("SELECT m.*, g.nama guru FROM mapel m LEFT JOIN guru g ON g.id=m.guru_id WHERE m.nama LIKE ? OR m.kode LIKE ? ORDER BY m.nama");
$st->bind_param('ss', $q, $q); $st->execute();
$res = $st->get_result();
page_start('Data Mapel', 'mapel', '../');
?>
<h1>Data Mata Pelajaran</h1><p class="sub">Kelola mapel dan guru pengampu</p>
<?php if (!empty($_GET['msg'])): ?><div class="alert"><?= e($_GET['msg']) ?></div><?php endif; ?>
<div class="card">
  <div class="bar">
    <form class="search" method="get"><input name="q" placeholder="Cari kode / nama mapel" value="<?= e($_GET['q'] ?? '') ?>"><button class="btn">Cari</button></form>
    <a class="btn" href="form.php">+ Tambah Mapel</a>
  </div>
  <div class="tw"><table>
    <tr><th>Kode</th><th>Mata Pelajaran</th><th>Guru Pengampu</th><th>Aksi</th></tr>
    <?php while ($r = $res->fetch_assoc()): ?>
    <tr><td><?= e($r['kode']) ?></td><td><?= e($r['nama']) ?></td><td><?= e($r['guru'] ?? '-') ?></td>
      <td><a class="btn o s" href="form.php?id=<?= $r['id'] ?>">Edit</a>
      <form class="inl" method="post" action="hapus.php" onsubmit="return confirm('Hapus mapel ini? Nilainya ikut terhapus.')"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="btn s">Hapus</button></form></td></tr>
    <?php endwhile; ?>
  </table></div>
</div>
<?php page_end();
