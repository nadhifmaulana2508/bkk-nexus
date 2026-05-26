<?php
/**
 * Mobile - Report Kelolaan AO
 * Statistik bucket, movement, dan top yang perlu dikejar.
 */

$movement     = vao_compute_movement_stats($myDebiturs);
$bucketStats  = vao_compute_bucket_stats($myDebiturs);
$totalBaki    = array_sum(array_column($myDebiturs, 'baki_debet'));

// Janji bayar performance
$jbAktif       = array_values(array_filter($myJanjiBayars, fn($j) => $j['status'] === 'aktif'));
$jbTerealisasi = array_values(array_filter($myJanjiBayars, fn($j) => $j['status'] === 'terealisasi'));
$jbGagal       = array_values(array_filter($myJanjiBayars, fn($j) => $j['status'] === 'gagal'));
$jbTotal       = count($myJanjiBayars);
$conversion    = $jbTotal > 0 ? round(count($jbTerealisasi) / $jbTotal * 100) : 0;

// Top 5 perlu dikejar (worse + bucket tertinggi)
$perluDikejar = array_filter($myDebiturs, fn($d) => $d['movement'] === 'worse');
usort($perluDikejar, fn($a, $b) => $b['baki_debet'] <=> $a['baki_debet']);
$perluDikejar = array_slice($perluDikejar, 0, 5);

// Top 5 perbaikan (improve)
$perbaikan = array_filter($myDebiturs, fn($d) => $d['movement'] === 'improve' || $d['movement'] === 'paid_off');
usort($perbaikan, fn($a, $b) => $b['baki_debet'] <=> $a['baki_debet']);
$perbaikan = array_slice($perbaikan, 0, 5);

// Total visit
$totalVisit = count($myKunjungans);
$visitsByHasil = [];
foreach ($myKunjungans as $k) {
    $h = $k['hasil'];
    $visitsByHasil[$h] = ($visitsByHasil[$h] ?? 0) + 1;
}
?>

<!-- Header -->
<div class="px-4 -mt-4 relative z-10 animate-fade-in">
    <div class="bg-white rounded-2xl shadow-card-hover p-5">
        <div class="flex items-start justify-between mb-3">
            <div>
                <p class="text-sm font-bold text-textMain">Report Kelolaan</p>
                <p class="text-xs text-textSub mt-0.5"><?= htmlspecialchars($currentAo['nama']) ?> • <?= vao_role_label($currentAo['role']) ?></p>
            </div>
            <div class="text-right">
                <p class="text-xs text-textSub">Closing</p>
                <p class="text-xs font-bold text-textMain"><?= vao_fmt_tgl($filterClosingDate) ?></p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div class="p-3 bg-indigo-50 rounded-xl">
                <p class="text-[11px] text-indigo-600/80 font-semibold uppercase">Total Kelolaan</p>
                <p class="text-xl font-bold text-indigo-700 mt-1"><?= count($myDebiturs) ?> debitur</p>
                <p class="text-[11px] text-indigo-600/80 mt-0.5"><?= vao_fmt_rp((int)$totalBaki) ?></p>
            </div>
            <div class="p-3 bg-purple-50 rounded-xl">
                <p class="text-[11px] text-purple-600/80 font-semibold uppercase">Total Visit</p>
                <p class="text-xl font-bold text-purple-700 mt-1"><?= $totalVisit ?> kunjungan</p>
                <p class="text-[11px] text-purple-600/80 mt-0.5">Bulan ini</p>
            </div>
        </div>
    </div>
</div>

<!-- Pergerakan Bucket -->
<div class="px-4 mt-5 animate-fade-in">
    <h2 class="text-base font-bold text-textMain mb-3">Pergerakan Bucket</h2>
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4">
        <?php
        $movementCfg = [
            ['key' => 'paid_off', 'label' => 'Berhasil Bayar / Lunas', 'color' => 'bg-emerald-500',  'bg' => 'bg-emerald-50',  'text' => 'text-emerald-700'],
            ['key' => 'improve',  'label' => 'Perbaikan Bucket',       'color' => 'bg-green-500',    'bg' => 'bg-green-50',    'text' => 'text-green-700'],
            ['key' => 'stay',     'label' => 'Stay (Tetap)',           'color' => 'bg-gray-400',     'bg' => 'bg-gray-50',     'text' => 'text-gray-700'],
            ['key' => 'worse',    'label' => 'Pemburukan',             'color' => 'bg-red-500',      'bg' => 'bg-red-50',      'text' => 'text-red-700'],
            ['key' => 'new',      'label' => 'Debitur Baru',           'color' => 'bg-blue-500',     'bg' => 'bg-blue-50',     'text' => 'text-blue-700'],
        ];
        $total = max(1, $movement['total']);
        foreach ($movementCfg as $cfg):
            $val = $movement[$cfg['key']];
            $pct = $total > 0 ? round($val / $total * 100) : 0;
        ?>
            <div class="mb-3 last:mb-0">
                <div class="flex items-center justify-between text-xs mb-1.5">
                    <span class="font-semibold text-textMain inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full <?= $cfg['color'] ?>"></span>
                        <?= $cfg['label'] ?>
                    </span>
                    <span class="font-bold <?= $cfg['text'] ?>"><?= $val ?> <span class="font-normal opacity-60">(<?= $pct ?>%)</span></span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                    <div class="<?= $cfg['color'] ?> h-2 rounded-full transition-all" style="width: <?= $pct ?>%"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Distribusi Bucket -->
<div class="px-4 mt-5 animate-fade-in">
    <h2 class="text-base font-bold text-textMain mb-3">Distribusi Bucket Saat Ini</h2>
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4">
        <?php
        $orderedBuckets = ['lunas','dpd_0','dpd_1_7','dpd_8_30','dpd_31_60','dpd_61_90','dpd_91_180','dpd_181_plus','ph'];
        $bucketTotal = max(1, count($myDebiturs));
        $hasAny = false;
        foreach ($orderedBuckets as $b):
            if (!isset($bucketStats[$b])) continue;
            $hasAny = true;
            $count  = $bucketStats[$b]['count'];
            $baki   = $bucketStats[$b]['baki_debet'];
            $pct    = round($count / $bucketTotal * 100);
        ?>
            <div class="flex items-center gap-3 py-2.5 border-b border-gray-100 last:border-0">
                <span class="text-[11px] font-bold px-2 py-0.5 rounded-full w-20 text-center <?= vao_bucket_color($b) ?>"><?= vao_bucket_label($b) ?></span>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-textMain"><?= $count ?> debitur</span>
                        <span class="text-textSub"><?= vao_fmt_rp((int)$baki) ?></span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1 overflow-hidden">
                        <div class="bg-gradient-to-r from-primary to-secondary h-1.5 rounded-full" style="width: <?= $pct ?>%"></div>
                    </div>
                </div>
            </div>
        <?php endforeach;
        if (!$hasAny): ?>
            <p class="text-xs text-textSub text-center py-3">Belum ada data bucket.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Performance Janji Bayar -->
<div class="px-4 mt-5 animate-fade-in">
    <h2 class="text-base font-bold text-textMain mb-3">Performance Janji Bayar</h2>
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-textSub uppercase">Conversion Rate</p>
            <p class="text-2xl font-extrabold text-primary"><?= $conversion ?>%</p>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden mb-3">
            <div class="bg-gradient-to-r from-primary to-secondary h-3 rounded-full" style="width: <?= $conversion ?>%"></div>
        </div>
        <div class="grid grid-cols-3 gap-2 text-center">
            <div class="p-2.5 bg-amber-50 rounded-xl">
                <p class="text-base font-bold text-amber-700"><?= count($jbAktif) ?></p>
                <p class="text-[10px] text-amber-600/80">Aktif</p>
            </div>
            <div class="p-2.5 bg-emerald-50 rounded-xl">
                <p class="text-base font-bold text-emerald-700"><?= count($jbTerealisasi) ?></p>
                <p class="text-[10px] text-emerald-600/80">Realisasi</p>
            </div>
            <div class="p-2.5 bg-rose-50 rounded-xl">
                <p class="text-base font-bold text-rose-700"><?= count($jbGagal) ?></p>
                <p class="text-[10px] text-rose-600/80">Gagal</p>
            </div>
        </div>
    </div>
</div>

<!-- Top 5 Perlu Dikejar -->
<?php if (count($perluDikejar) > 0): ?>
<div class="px-4 mt-5 animate-fade-in">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-base font-bold text-textMain">⚠️ Top Perlu Dikejar</h2>
        <span class="text-xs text-textSub">Pemburukan</span>
    </div>
    <div class="space-y-2">
        <?php foreach ($perluDikejar as $i => $d): ?>
            <div class="bg-white rounded-2xl shadow-card p-3 border border-red-100 border-l-4 border-l-red-500">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-red-100 text-red-700 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0"><?= $i + 1 ?></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-textMain truncate"><?= htmlspecialchars($d['nama']) ?></p>
                        <p class="text-xs text-textSub mt-0.5">
                            <?= vao_bucket_label($d['bucket_prev']) ?> → <?= vao_bucket_label($d['dpd_bucket']) ?>
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-textMain"><?= vao_fmt_rp((int)$d['baki_debet']) ?></p>
                        <a href="<?= BASE_URL ?>visit-ao?tab=form&debitur_id=<?= $d['id'] ?>" class="text-[11px] text-primary font-semibold mt-1 inline-block">Visit →</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Top 5 Perbaikan -->
<?php if (count($perbaikan) > 0): ?>
<div class="px-4 mt-5 pb-6 animate-fade-in">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-base font-bold text-textMain">🌟 Top Perbaikan</h2>
        <span class="text-xs text-textSub">Improve / Lunas</span>
    </div>
    <div class="space-y-2">
        <?php foreach ($perbaikan as $i => $d): ?>
            <div class="bg-white rounded-2xl shadow-card p-3 border border-emerald-100 border-l-4 border-l-emerald-500">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0"><?= $i + 1 ?></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-textMain truncate"><?= htmlspecialchars($d['nama']) ?></p>
                        <p class="text-xs text-textSub mt-0.5">
                            <?= $d['movement'] === 'paid_off' ? '✓ Lunas' : (vao_bucket_label($d['bucket_prev']) . ' → ' . vao_bucket_label($d['dpd_bucket'])) ?>
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-textMain"><?= vao_fmt_rp((int)$d['plafon']) ?></p>
                        <p class="text-[11px] text-emerald-700 font-semibold mt-1">Plafon</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php else: ?>
<div class="pb-6"></div>
<?php endif; ?>
