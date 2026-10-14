<?php
/* Animasi orang berjalan + kota malam (hitam putih). Dipakai di login, dashboard & sidebar. */
function walk_css() {
return <<<'CSS'
<style>
.scene{position:relative;overflow:hidden;height:var(--h,230px);background:linear-gradient(#000,#0d0d0d 70%,#171717)}
.scene.full{position:fixed;left:0;right:0;bottom:0;--h:280px;background:linear-gradient(transparent,rgba(255,255,255,.05));z-index:1;pointer-events:none}
.stars i{position:absolute;width:2px;height:2px;background:#fff;border-radius:50%;animation:twk 3s ease-in-out infinite}
@keyframes twk{0%,100%{opacity:.12}50%{opacity:1}}
.moon{position:absolute;top:22px;right:12%;width:44px;height:44px;border-radius:50%;background:#fff;box-shadow:0 0 40px 10px rgba(255,255,255,.22);animation:moon 8s ease-in-out infinite alternate}
@keyframes moon{to{transform:translateY(12px)}}
.city{position:absolute;left:0;right:0;bottom:16px;height:62%;display:flex;align-items:flex-end;gap:3px;padding:0 6px}
.city b{flex:1;min-width:14px;background:#0f0f0f;border:1px solid #242424;border-bottom:0;position:relative}
.city b::after{content:"";position:absolute;inset:6px 4px;background-image:radial-gradient(#fff 1px,transparent 1.6px);background-size:9px 11px;opacity:.22;animation:win 5s ease-in-out infinite alternate}
.city b:nth-child(3n)::after{animation-duration:3s}.city b:nth-child(4n)::after{animation-duration:7s}
@keyframes win{to{opacity:.06}}
.ground{position:absolute;left:0;right:0;bottom:0;height:16px;background:#050505;border-top:1px solid #555;overflow:hidden}
.ground::before{content:"";position:absolute;left:0;right:0;top:6px;height:2px;background:repeating-linear-gradient(90deg,#777 0 22px,transparent 22px 44px);animation:jalan 1.4s linear infinite}
@keyframes jalan{to{background-position:-44px 0}}
/* ---- orang berjalan ---- */
.man{position:absolute;width:40px;height:90px;opacity:var(--op,1);animation-duration:var(--sp,18s);animation-timing-function:linear;animation-iteration-count:infinite;animation-delay:var(--dl,0s)}
.man.s,.man.sl{left:50%;margin-left:-20px}
.man.r{animation-name:goR}.man.l{animation-name:goL}
@keyframes goR{from{left:-60px}to{left:100%}}
@keyframes goL{from{left:100%}to{left:-60px}}
.fig{position:relative;width:40px;height:90px;transform-origin:50% 100%;transform:scale(calc(var(--sc,1) * var(--dir,1)),var(--sc,1))}
.man::after{content:"";position:absolute;left:4px;right:4px;bottom:-3px;height:6px;border-radius:50%;background:rgba(255,255,255,.12);filter:blur(2px)}
.bob{position:absolute;inset:0;animation:bob calc(var(--st,.6s)) ease-in-out infinite}
@keyframes bob{50%{transform:translateY(-3px)}}
.fig i{position:absolute;display:block;background:#fff;border-radius:5px}
.hd{top:0;left:12px;width:16px;height:16px;border-radius:50%!important}
.tr{top:18px;left:17px;width:6px;height:30px;transform:rotate(4deg)}
.tas{top:20px;left:10px;width:9px;height:18px;background:#8d8d8d!important;border-radius:4px 0 0 4px!important}
.ar{top:20px;left:18px;width:4px;height:15px;transform-origin:50% 2px}
.ar .lw{top:13px;left:0;width:4px;height:14px;transform-origin:50% 2px;animation:elbow calc(var(--st,.6s)*2) ease-in-out infinite}
.lg{top:46px;left:17px;width:6px;height:22px;transform-origin:50% 3px}
.lg .lw{top:19px;left:0;width:6px;height:22px;transform-origin:50% 3px;animation:knee calc(var(--st,.6s)*2) ease-in-out infinite}
.a1,.g2{animation:swingA var(--st,.6s) ease-in-out infinite alternate}
.a2,.g1{animation:swingA var(--st,.6s) ease-in-out infinite alternate-reverse}
.g1,.g2{animation-name:swingL}
.g2 .lw{animation-delay:calc(var(--st,.6s) * -1)}
.a2 .lw{animation-delay:calc(var(--st,.6s) * -1)}
.a2,.a2 .lw,.g2,.g2 .lw{background:#8a8a8a!important}
@keyframes swingL{from{transform:rotate(-30deg)}to{transform:rotate(30deg)}}
@keyframes swingA{from{transform:rotate(-26deg)}to{transform:rotate(26deg)}}
@keyframes knee{0%,100%{transform:rotate(4deg)}50%{transform:rotate(42deg)}}
@keyframes elbow{0%,100%{transform:rotate(-8deg)}50%{transform:rotate(-38deg)}}
.mini{position:relative;height:56px;overflow:hidden;margin:auto 0 12px;border-bottom:1px solid #2a2a2a}
.mini .man{bottom:2px}
@media (prefers-reduced-motion:reduce){.man,.bob,.fig i,.ground::before,.stars i,.moon,.city b::after{animation-play-state:paused}}
</style>
CSS;
}

function walker($dir, $sp, $dl, $sc, $op, $bt, $st, $bag = 0) {
    return '<div class="man '.$dir.'" style="--sp:'.$sp.'s;--dl:'.$dl.'s;--op:'.$op.';--sc:'.$sc.';--st:'.$st.'s;--dir:'.(in_array($dir, ['l','sl']) ? -1 : 1).';bottom:'.$bt.'px">'
        .'<div class="fig"><div class="bob">'
        .'<i class="hd"></i><i class="tr"></i>'.($bag ? '<i class="tas"></i>' : '')
        .'<i class="ar a2"><i class="lw"></i></i><i class="lg g2"><i class="lw"></i></i>'
        .'<i class="lg g1"><i class="lw"></i></i><i class="ar a1"><i class="lw"></i></i>'
        .'</div></div></div>';
}

function walk_scene($extra = '', $inject = '') {
    mt_srand(11);
    $o = '<div class="scene '.$extra.'"><div class="stars">';
    for ($i = 0; $i < 45; $i++) {
        $o .= '<i style="left:'.mt_rand(0,100).'%;top:'.mt_rand(2,55).'%;animation-delay:-'.(mt_rand(0,30) / 10).'s"></i>';
    }
    $o .= '</div><div class="moon"></div><div class="city">';
    foreach ([38,62,48,80,55,70,42,90,58,66,36,76,50,84,60,44,72,54,88,46,68,40,78,56] as $h) {
        $o .= '<b style="height:'.$h.'%"></b>';
    }
    $o .= '</div>';
    // [arah, kecepatan(s), delay, skala, opacity, bottom, langkah, tas]
    $men = [
        ['r',34,-4,.60,.40,30,.80,1], ['l',40,-22,.58,.38,30,.85,0], ['r',46,-30,.62,.40,28,.90,0],
        ['r',20,-3,1,1,10,.55,1], ['l',24,-14,1,1,10,.60,0], ['r',17,-11,.95,1,10,.50,1],
        ['l',27,-2,.9,.9,13,.62,1], ['r',22,-19,1,1,10,.58,0],
    ];
    foreach ($men as $m) $o .= walker(...$m);
    return $o . $inject . '<div class="ground"></div></div>';
}

function walk_mini() {
    return '<div class="mini">'.walker('r', 11, 0, .5, 1, 0, .5, 1).walker('l', 14, -6, .45, .7, 0, .55, 0).'</div>';
}


function favicon() {
    return '<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 32 32%27%3E%3Crect width=%2732%27 height=%2732%27 rx=%277%27 fill=%27%23000%27/%3E%3Crect x=%279%27 y=%279%27 width=%2714%27 height=%2714%27 fill=%27%23fff%27/%3E%3C/svg%3E">';
}

/* Layar transisi penuh dengan orang berjalan (setelah masuk & saat keluar). */
function transition_page($judul, $sub, $tujuan, $ms = 2800, $arah = 'r') {
    $dur = $ms / 1000;
    $big = walker($arah === 'l' ? 'sl' : 's', 0, 0, 2.1, 1, 12, .42, 1);
    header('Cache-Control: no-store');
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($judul) ?> | <?= e(APP_NAME) ?></title>
<?= favicon() ?>
<?= walk_css() ?>
<style>
*{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#000;color:#fff;overflow:hidden;display:flex;justify-content:center;padding-top:11vh;animation:fin .5s both}
@keyframes fin{from{opacity:0}to{opacity:1}}
.bg{position:fixed;inset:0;background-image:linear-gradient(#191919 1px,transparent 1px),linear-gradient(90deg,#191919 1px,transparent 1px);background-size:48px 48px;animation:geser 10s linear infinite;opacity:.8}
@keyframes geser{to{background-position:<?= $arah === 'l' ? '-' : '' ?>48px 48px}}
.glow{position:fixed;left:50%;top:28%;width:520px;height:520px;margin:-260px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,.14),transparent 65%);animation:napas 3s ease-in-out infinite alternate}
@keyframes napas{to{transform:scale(1.25);opacity:.6}}
.tx{position:relative;z-index:3;text-align:center;padding:0 20px}
.logo{width:56px;height:56px;margin:0 auto 20px;border:2px solid #fff;border-radius:12px;display:flex;align-items:center;justify-content:center;animation:naik .7s both,denyut 2s 1s ease-in-out infinite}
.logo i{width:22px;height:22px;background:#fff;animation:putar 3s linear infinite}
@keyframes putar{to{transform:rotate(360deg)}}
@keyframes denyut{50%{box-shadow:0 0 0 10px rgba(255,255,255,.08)}}
h1{font-size:clamp(24px,4.4vw,40px);letter-spacing:.5px;animation:naik .8s .2s both}
p{color:#999;margin-top:10px;animation:naik .8s .45s both}
@keyframes naik{from{opacity:0;transform:translateY(22px)}to{opacity:1;transform:none}}
.pg{width:280px;max-width:80vw;height:4px;background:#222;border-radius:4px;margin:28px auto 0;overflow:hidden}
.pg i{display:block;height:100%;width:0;background:#fff;border-radius:4px;animation:isi <?= $dur ?>s linear forwards}
@keyframes isi{to{width:100%}}
.skip{display:inline-block;margin-top:16px;font-size:13px;color:#666;text-decoration:none;animation:naik .8s .8s both}
.skip:hover{color:#fff}
</style>
</head>
<body>
<div class="bg"></div><div class="glow"></div>
<div class="tx">
  <div class="logo"><i></i></div>
  <h1><?= e($judul) ?></h1>
  <p><?= e($sub) ?></p>
  <div class="pg"><i></i></div>
  <a class="skip" href="<?= e($tujuan) ?>">Lewati &rarr;</a>
</div>
<?= walk_scene('full', $big) ?>
<noscript><meta http-equiv="refresh" content="<?= ceil($dur) ?>;url=<?= e($tujuan) ?>"></noscript>
<script>setTimeout(function(){location.replace(<?= json_encode($tujuan) ?>)},<?= (int)$ms ?>);</script>
</body>
</html>
<?php
    exit;
}
