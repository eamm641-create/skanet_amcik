<?php
require_once __DIR__ . '/animasi.php';

function page_start($title, $active = '', $base = '') {
    $menu = [
        'dashboard' => ['Dashboard', 'dashboard.php'],
        'siswa'     => ['Data Siswa', 'siswa/index.php'],
        'guru'      => ['Data Guru', 'guru/index.php'],
        'mapel'     => ['Data Mapel', 'mapel/index.php'],
        'nilai'     => ['Data Nilai', 'nilai/index.php'],
        'password'  => ['Ubah Password', 'password.php'],
    ];
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> | <?= e(APP_NAME) ?></title>
<?= favicon() ?>
<?= walk_css() ?>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0a0a0a;color:#f5f5f5;display:flex;min-height:100vh}
a{color:inherit;text-decoration:none}
.side{width:240px;background:#000;border-right:1px solid #2a2a2a;padding:24px 16px;position:sticky;top:0;height:100vh;display:flex;flex-direction:column}
.brand{font-size:19px;font-weight:800;letter-spacing:1px;padding:0 10px 22px;border-bottom:1px solid #2a2a2a;margin-bottom:16px;display:flex;align-items:center;gap:8px}
.brand i{width:12px;height:12px;background:#fff;display:inline-block;animation:putar 4s linear infinite}
@keyframes putar{to{transform:rotate(360deg)}}
.side a.m{display:block;padding:11px 14px;border-radius:8px;color:#aaa;margin-bottom:4px;transition:.2s}
.side a.m:hover{background:#1a1a1a;color:#fff;transform:translateX(4px)}
.side a.m.on{background:#fff;color:#000;font-weight:600}
.side .out{border:1px solid #444;text-align:center;padding:10px;border-radius:8px;color:#ccc;transition:.2s}
.side .out:hover{background:#fff;color:#000}
main{flex:1;padding:32px;min-width:0;animation:masuk .5s ease both}
@keyframes masuk{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
h1{font-size:26px;margin-bottom:6px}
.sub{color:#888;margin-bottom:24px}
.card{background:#111;border:1px solid #2a2a2a;border-radius:12px;padding:20px;margin-bottom:20px}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:20px}
.stat{background:#111;border:1px solid #2a2a2a;border-radius:12px;padding:20px;transition:.25s;animation:masuk .6s both}
.stat:nth-child(2){animation-delay:.08s}.stat:nth-child(3){animation-delay:.16s}.stat:nth-child(4){animation-delay:.24s}
.stat:hover{background:#fff;color:#000;transform:translateY(-4px)}
.stat b{display:block;font-size:34px}
.stat span{color:#888;font-size:14px}.stat:hover span{color:#333}
table{width:100%;border-collapse:collapse}
th,td{padding:11px 12px;text-align:left;border-bottom:1px solid #222;font-size:14px}
th{background:#1a1a1a;color:#ddd;font-weight:600}
tbody tr,tr.rw{animation:masuk .4s both}
tr:hover td{background:#161616}
.btn{display:inline-block;padding:9px 16px;border-radius:8px;border:1px solid #fff;background:#fff;color:#000;font-size:14px;font-weight:600;cursor:pointer;transition:.2s}
.btn:hover{background:#000;color:#fff}
.btn.o{background:transparent;color:#fff;border-color:#555}
.btn.o:hover{background:#fff;color:#000}
.btn.s{padding:5px 11px;font-size:12px}
.bar{display:flex;gap:10px;justify-content:space-between;flex-wrap:wrap;margin-bottom:16px}
input,select,textarea{width:100%;padding:11px 13px;background:#000;border:1px solid #333;border-radius:8px;color:#fff;font:inherit;transition:.2s}
input:focus,select:focus,textarea:focus{outline:none;border-color:#fff;box-shadow:0 0 0 3px rgba(255,255,255,.1)}
label{display:block;margin:14px 0 6px;color:#bbb;font-size:13px}
.search{display:flex;gap:8px;max-width:360px}
.alert{padding:12px 14px;border-radius:8px;border:1px solid #fff;margin-bottom:16px;transition:.5s}
.tw{overflow-x:auto}
.badge{padding:3px 9px;border:1px solid #666;border-radius:20px;font-size:12px}
form.inl{display:inline}
.hero{position:relative;padding:0!important;overflow:hidden}
.hero .ht{position:absolute;z-index:5;left:24px;top:20px;text-shadow:0 2px 12px #000}
.hero .ht h1{margin-bottom:2px}.hero .ht p{color:#bbb;font-size:14px}
.two{display:grid;grid-template-columns:2fr 1fr;gap:20px}
.hb{margin-bottom:14px}.hb .t{display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px;color:#ccc}
.hb .tr2{height:10px;background:#1c1c1c;border-radius:10px;overflow:hidden}
.hb .f{height:100%;width:0;background:#fff;border-radius:10px;animation:isi 1.2s .3s cubic-bezier(.2,.8,.2,1) forwards}
@keyframes isi{to{width:var(--w)}}
.cols{display:flex;gap:10px;align-items:flex-end;height:150px}
.cols div{flex:1;text-align:center;font-size:12px;color:#aaa;display:flex;flex-direction:column;justify-content:flex-end;height:100%}
.cols div i{display:block;background:#fff;border-radius:6px 6px 0 0;height:0;animation:naik 1s .3s cubic-bezier(.2,.8,.2,1) forwards;margin-bottom:6px}
@keyframes naik{to{height:var(--h)}}
.pager{display:flex;gap:8px;align-items:center;justify-content:flex-end;margin-top:14px;font-size:13px;color:#888}
.btn:disabled{opacity:.3;cursor:not-allowed}
body{transition:opacity .2s}body.out{opacity:0}
::selection{background:#fff;color:#000}
::-webkit-scrollbar{width:10px;height:10px}::-webkit-scrollbar-thumb{background:#333;border-radius:10px}::-webkit-scrollbar-track{background:#0a0a0a}
@media(max-width:900px){.two{grid-template-columns:1fr}}
@media(max-width:760px){body{flex-direction:column}.side{width:100%;height:auto;position:static}main{padding:20px 14px}}
</style>
</head>
<body>
<aside class="side">
  <div class="brand"><i></i><?= e(APP_NAME) ?></div>
  <?php foreach ($menu as $k => $m): ?>
    <a class="m <?= $active === $k ? 'on' : '' ?>" href="<?= $base . $m[1] ?>"><?= $m[0] ?></a>
  <?php endforeach; ?>
  <?= walk_mini() ?>
  <a class="out" href="<?= $base ?>logout.php">Keluar</a>
</aside>
<main>
<?php
}

function page_end() {
    ?>
</main>
<script>
// hitung naik pada angka
document.querySelectorAll('[data-count]').forEach(function(el){
  var to=parseFloat(el.dataset.count), dec=parseInt(el.dataset.dec||0), t0=null;
  function step(t){ if(!t0)t0=t; var p=Math.min((t-t0)/1000,1), v=to*(1-Math.pow(1-p,3)); el.textContent=v.toFixed(dec); if(p<1)requestAnimationFrame(step); }
  requestAnimationFrame(step);
});
// baris tabel muncul bergantian
document.querySelectorAll('tbody tr, table tr:not(:first-child)').forEach(function(tr,i){ tr.style.animationDelay=Math.min(i*0.04,0.8)+'s'; });
// notifikasi hilang otomatis
document.querySelectorAll('.alert').forEach(function(a){ setTimeout(function(){a.style.opacity=0;setTimeout(function(){a.remove()},500)},4000); });
// halaman tabel (10 baris) + keadaan kosong
document.querySelectorAll('.tw table').forEach(function(tb){
  var all=[].slice.call(tb.querySelectorAll('tr')), rows=all.slice(1);
  if(!rows.length){var r=tb.insertRow(-1),c=r.insertCell(0);c.colSpan=all[0].children.length;c.style.color='#777';c.textContent='Belum ada data.';return;}
  var per=10,pg=1,n=Math.ceil(rows.length/per);
  if(n<2)return;
  var bar=document.createElement('div');bar.className='pager';tb.parentNode.after(bar);
  function show(){
    rows.forEach(function(r,i){r.style.display=(i>=(pg-1)*per&&i<pg*per)?'':'none'});
    bar.innerHTML='';var info=document.createElement('span');info.textContent='Halaman '+pg+' / '+n+' ('+rows.length+' data)';bar.appendChild(info);
    [['\u2039',pg-1],['\u203a',pg+1]].forEach(function(x){var b=document.createElement('button');b.className='btn o s';b.textContent=x[0];b.disabled=x[1]<1||x[1]>n;b.onclick=function(){pg=x[1];show()};bar.appendChild(b)});
  }
  show();
});
// transisi halus antar halaman
document.addEventListener('click',function(e){
  var a=e.target.closest('a[href]'); if(!a||a.target||e.ctrlKey||e.metaKey||e.shiftKey)return;
  var h=a.getAttribute('href'); if(!h||h[0]==='#'||/^(https?:|mailto:|javascript:)/.test(h))return;
  e.preventDefault(); document.body.classList.add('out'); setTimeout(function(){location.href=a.href},200);
});
window.addEventListener('pageshow',function(){document.body.classList.remove('out')});
</script>
</body>
</html>
<?php
}

function grade($n) {
    if ($n >= 90) return 'A';
    if ($n >= 80) return 'B';
    if ($n >= 70) return 'C';
    if ($n >= 60) return 'D';
    return 'E';
}
