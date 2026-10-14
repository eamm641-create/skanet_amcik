<?php
require '../koneksi.php'; require '../layout.php'; cek_login('../');
$q = '%' . ($_GET['q'] ?? '') . '%';
$st = $conn->prepare("SELECT * FROM guru WHERE nama LIKE ? OR nip LIKE ? ORDER BY nama");
$st->bind_param('ss', $q, $q); $st->execute();
$res = $st->get_result();
page_start('Data Guru', 'guru', '../');
?>
<h1>Data Guru</h1><p class="sub">Kelola data seluruh guru</p>
<?php if (!empty($_GET['msg'])): ?><div class="alert"><?= e($_GET['msg']) ?></div><?php endif; ?>
<div class="card">
  <div class="bar">
    <form class="search" method="get"><input name="q" placeholder="Cari nama / NIP" value="<?= e($_GET['q'] ?? '') ?>"><button class="btn">Cari</button></form>
    <a class="btn" href="form.php">+ Tambah Guru</a>
  </div>
  <div class="tw"><table>
    <tr><th>NIP</th><th>Nama</th><th>No. HP</th><th>Alamat</th><th>Aksi</th></tr>
    <?php while ($r = $res->fetch_assoc()): ?>
    <tr><td><?= e($r['nip']) ?></td><td><?= e($r['nama']) ?></td><td><?= e($r['no_hp']) ?></td><td><?= e($r['alamat']) ?></td>
      <td><a class="btn o s" href="form.php?id=<?= $r['id'] ?>">Edit</a>
      <form class="inl" method="post" action="hapus.php" onsubmit="return confirm('Hapus guru ini?')"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="btn s">Hapus</button></form></td></tr>
    <?php endwhile; ?>
  </table></div>
</div>
<?php page_end();
