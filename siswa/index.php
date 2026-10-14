<?php
require '../koneksi.php'; require '../layout.php'; cek_login('../');
$q = '%' . ($_GET['q'] ?? '') . '%';
$st = $conn->prepare("SELECT * FROM siswa WHERE nama LIKE ? OR nis LIKE ? OR kelas LIKE ? ORDER BY nama");
$st->bind_param('sss', $q, $q, $q); $st->execute();
$res = $st->get_result();
page_start('Data Siswa', 'siswa', '../');
?>
<h1>Data Siswa</h1><p class="sub">Kelola data seluruh siswa</p>
<?php if (!empty($_GET['msg'])): ?><div class="alert"><?= e($_GET['msg']) ?></div><?php endif; ?>
<div class="card">
  <div class="bar">
    <form class="search" method="get"><input name="q" placeholder="Cari nama / NIS / kelas" value="<?= e($_GET['q'] ?? '') ?>"><button class="btn">Cari</button></form>
    <a class="btn" href="form.php">+ Tambah Siswa</a>
  </div>
  <div class="tw"><table>
    <tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>JK</th><th>Alamat</th><th>Aksi</th></tr>
    <?php while ($r = $res->fetch_assoc()): ?>
    <tr><td><?= e($r['nis']) ?></td><td><?= e($r['nama']) ?></td><td><?= e($r['kelas']) ?></td><td><?= e($r['jk']) ?></td><td><?= e($r['alamat']) ?></td>
      <td><a class="btn o s" href="form.php?id=<?= $r['id'] ?>">Edit</a>
      <form class="inl" method="post" action="hapus.php" onsubmit="return confirm('Hapus siswa ini? Nilainya ikut terhapus.')"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="btn s">Hapus</button></form></td></tr>
    <?php endwhile; ?>
  </table></div>
</div>
<?php page_end();
