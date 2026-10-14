<?php
require 'koneksi.php';
require 'layout.php';
cek_login();

$n = fn($t) => (int)$conn->query("SELECT COUNT(*) c FROM $t")->fetch_assoc()['c'];
$rata = $conn->query("SELECT AVG(tugas*0.3+uts*0.3+uas*0.4) r FROM nilai")->fetch_assoc()['r'];

$perMapel = $conn->query("SELECT m.nama, AVG(n.tugas*0.3+n.uts*0.3+n.uas*0.4) r, COUNT(n.id) jml
  FROM mapel m LEFT JOIN nilai n ON n.mapel_id=m.id GROUP BY m.id ORDER BY m.nama");

$dist = ['A'=>0,'B'=>0,'C'=>0,'D'=>0,'E'=>0];
$all = $conn->query("SELECT tugas*0.3+uts*0.3+uas*0.4 a FROM nilai");
while ($x = $all->fetch_assoc()) $dist[grade($x['a'])]++;
$maxd = max(1, max($dist));

$top = $conn->query("SELECT s.nama, s.kelas, AVG(n.tugas*0.3+n.uts*0.3+n.uas*0.4) r
  FROM nilai n JOIN siswa s ON s.id=n.siswa_id GROUP BY s.id ORDER BY r DESC LIMIT 5");

$h = (int)date('H');
$sapa = $h < 11 ? 'pagi' : ($h < 15 ? 'siang' : ($h < 18 ? 'sore' : 'malam'));

page_start('Dashboard', 'dashboard');
?>
<div class="card hero">
  <div class="ht">
    <h1>Selamat <?= $sapa ?>, <?= e($_SESSION['user']['nama']) ?></h1>
    <p><?= e(APP_NAME) ?> &middot; <span id="jam"></span></p>
  </div>
  <?= walk_scene() ?>
</div>

<div class="grid">
  <a class="stat" href="siswa/index.php"><b data-count="<?= $n('siswa') ?>">0</b><span>Siswa</span></a>
  <a class="stat" href="guru/index.php"><b data-count="<?= $n('guru') ?>">0</b><span>Guru</span></a>
  <a class="stat" href="mapel/index.php"><b data-count="<?= $n('mapel') ?>">0</b><span>Mata Pelajaran</span></a>
  <a class="stat" href="nilai/index.php"><b data-count="<?= $rata !== null ? round($rata,1) : 0 ?>" data-dec="1">0</b><span>Rata-rata Nilai</span></a>
</div>

<div class="two">
  <div class="card">
    <h3 style="margin-bottom:16px">Rata-rata Nilai per Mapel</h3>
    <?php $ada = false; while ($r = $perMapel->fetch_assoc()): $ada = true; $v = (float)$r['r']; ?>
      <div class="hb">
        <div class="t"><span><?= e($r['nama']) ?></span><span><?= $r['jml'] ? number_format($v,1) : '-' ?></span></div>
        <div class="tr2"><div class="f" style="--w:<?= min(100,$v) ?>%"></div></div>
      </div>
    <?php endwhile; if (!$ada) echo '<p class="sub">Belum ada mapel.</p>'; ?>
  </div>
  <div class="card">
    <h3 style="margin-bottom:16px">Sebaran Predikat</h3>
    <div class="cols">
      <?php foreach ($dist as $g => $c): ?>
        <div><span><?= $c ?></span><i style="--h:<?= max(4, round($c / $maxd * 100)) ?>%"></i><?= $g ?></div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<div class="card">
  <h3 style="margin-bottom:12px">Peringkat Siswa Teratas</h3>
  <div class="tw"><table>
    <tr><th>#</th><th>Nama</th><th>Kelas</th><th>Rata-rata</th><th>Predikat</th></tr>
    <?php $i = 1; while ($r = $top->fetch_assoc()): ?>
      <tr><td><?= $i++ ?></td><td><?= e($r['nama']) ?></td><td><?= e($r['kelas']) ?></td>
      <td><?= number_format($r['r'], 1) ?></td><td><span class="badge"><?= grade($r['r']) ?></span></td></tr>
    <?php endwhile; if ($i === 1): ?><tr><td colspan="5">Belum ada data nilai.</td></tr><?php endif; ?>
  </table></div>
</div>
<script>
(function(){var j=document.getElementById('jam');function t(){j.textContent=new Date().toLocaleString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric',hour:'2-digit',minute:'2-digit',second:'2-digit'})}t();setInterval(t,1000)})();
</script>
<?php page_end();
