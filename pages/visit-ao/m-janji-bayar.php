<?php
/**
 * Mobile - List Janji Bayar AO
 * Section: Hari ini, Mendatang, Terlewat, Terealisasi.
 */

$today = $filterHarianDate;

$jbAktif       = array_values(array_filter($myJanjiBayars, fn($j) => $j['status'] === 'aktif'));
$jbTerealisasi = array_values(array_filter($myJanjiBayars, fn($j) => $j['status'] === 'terealisasi'));
$jbGagal       = array_values(array_filter($myJanjiBayars, fn($j) => $j['status'] === 'gagal'));

$jbHariIni   = array_values(array_filter($jbAktif, fn($j) => $j['tanggal_janji'] === $today));
$jbMendatang = array_values(array_filter($jbAktif, fn($j) => strtotime($j['tanggal_janji']) > strtotime($today)));
$jbTerlewat  = array_values(array_filter($jbAktif, fn($j) => strtotime($j['tanggal_janji']) < strtotime($today)));

// Sort
usort($jbHariIni,   fn($a, $b) => strcmp($a['tanggal_janji'], $b['tanggal_janji']));
usort($jbMendatang, fn($a, $b) => strcmp($a['tanggal_janji'], $b['tanggal_janji']));
usort($jbTerlewat,  fn($a, $b) => strcmp($a['tanggal_janji'], $b['tanggal_janji']));

$totalNominalAktif       = array_sum(array_column($jbAktif, 'nominal_janji'));
$totalNominalTerealisasi = array_sum(array_column($jbTerealisasi, 'realisasi_nominal'));

// Render helper
function renderJanjiCard(array $jb, array $debiturs, string $today, string $variant = 'normal'): string {
    $deb = vao_debitur_by_id($debiturs, $jb['debitur_id']);
    if (!$deb) return '';
    $isOverdue = strtotime($jb['tanggal_janji']) < strtotime($today);
    $isToday   = $jb['tanggal_janji'] === $today;
    $borderCls = $variant === 'overdue' ? 'border-l-4 border-l-red-400' : ($variant === 'today' ? 'border-l-4 border-l-amber-400' : ($variant === 'success' ? 'border-l-4 border-l-emerald-400' : ''));

    ob_start(); ?>
    <div class="bg-white rounded-2xl shadow-card p-4 border border-gray-50 <?= $borderCls ?>">
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-textMain truncate"><?= htmlspecialchars($deb['nama']) ?></p>
                <p class="text-xs text-textSub mt-0.5"><?= htmlspecialchars($deb['pemilik']) ?> • <?= $deb['no_rek'] ?></p>
                <div class="flex items-center gap-2 mt-2 flex-wrap">
                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full <?= vao_bucket_color($deb['dpd_bucket']) ?>">
                        <?= vao_bucket_label($deb['dpd_bucket']) ?>
                    </span>
                    <?php if ($jb['status'] === 'terealisasi'): ?>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">✓ Terealisasi</span>
                    <?php elseif ($jb['status'] === 'gagal'): ?>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700">✗ Gagal</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="text-right flex-shrink-0">
                <p class="text-sm font-bold text-textMain"><?= vao_fmt_rp((int)$jb['nominal_janji']) ?></p>
                <p class="text-[11px] text-textSub mt-0.5"><?= vao_fmt_tgl($jb['tanggal_janji']) ?></p>
            </div>
        </div>

        <?php if ($jb['status'] === 'aktif'): ?>
            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                <a href="<?= BASE_URL ?>visit-ao?tab=form&debitur_id=<?= $deb['id'] ?>"
                   class="flex-1 text-xs bg-primary text-white px-3 py-2 rounded-lg font-semibold text-center inline-flex items-center justify-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    Visit Ulang
                </a>
                <a href="https://wa.me/62<?= ltrim($deb['telp'], '0') ?>" target="_blank"
                   class="text-xs bg-green-500 text-white px-3 py-2 rounded-lg font-semibold inline-flex items-center justify-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    Reminder
                </a>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
?>

<!-- Header summary -->
<div class="px-4 -mt-4 relative z-10 animate-fade-in">
    <div class="bg-white rounded-2xl shadow-card-hover p-5">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="text-sm font-bold text-textMain">Janji Bayar</p>
                <p class="text-xs text-textSub mt-0.5">Monitor komitmen pembayaran debitur Anda</p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-2">
            <div class="text-center p-3 bg-amber-50 rounded-xl">
                <p class="text-xl font-bold text-amber-700"><?= count($jbAktif) ?></p>
                <p class="text-[11px] text-amber-600/80 mt-0.5">Aktif</p>
            </div>
            <div class="text-center p-3 bg-emerald-50 rounded-xl">
                <p class="text-xl font-bold text-emerald-700"><?= count($jbTerealisasi) ?></p>
                <p class="text-[11px] text-emerald-600/80 mt-0.5">Terealisasi</p>
            </div>
            <div class="text-center p-3 bg-rose-50 rounded-xl">
                <p class="text-xl font-bold text-rose-700"><?= count($jbGagal) ?></p>
                <p class="text-[11px] text-rose-600/80 mt-0.5">Gagal</p>
            </div>
        </div>

        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
            <span class="text-textSub">Nominal Aktif</span>
            <span class="font-bold text-textMain"><?= vao_fmt_rp((int)$totalNominalAktif) ?></span>
        </div>
        <div class="flex items-center justify-between text-xs mt-1">
            <span class="text-textSub">Nominal Terealisasi</span>
            <span class="font-bold text-emerald-700"><?= vao_fmt_rp((int)$totalNominalTerealisasi) ?></span>
        </div>
    </div>
</div>

<!-- Terlewat (paling urgent) -->
<?php if (count($jbTerlewat) > 0): ?>
<div class="px-4 mt-5 animate-fade-in">
    <div class="flex items-center gap-2 mb-3">
        <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
        <h2 class="text-base font-bold text-textMain">Terlewat <span class="text-red-600 font-extrabold">(<?= count($jbTerlewat) ?>)</span></h2>
    </div>
    <div class="space-y-2.5">
        <?php foreach ($jbTerlewat as $jb): echo renderJanjiCard($jb, $debiturs, $today, 'overdue'); endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Hari Ini -->
<div class="px-4 mt-5 animate-fade-in">
    <div class="flex items-center gap-2 mb-3">
        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
        <h2 class="text-base font-bold text-textMain">Hari Ini <span class="text-amber-600 font-extrabold">(<?= count($jbHariIni) ?>)</span></h2>
    </div>
    <?php if (count($jbHariIni) === 0): ?>
        <p class="text-xs text-textSub text-center py-4">Tidak ada janji bayar hari ini.</p>
    <?php else: ?>
        <div class="space-y-2.5">
            <?php foreach ($jbHariIni as $jb): echo renderJanjiCard($jb, $debiturs, $today, 'today'); endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Mendatang -->
<?php if (count($jbMendatang) > 0): ?>
<div class="px-4 mt-5 animate-fade-in">
    <div class="flex items-center gap-2 mb-3">
        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
        <h2 class="text-base font-bold text-textMain">Mendatang <span class="text-blue-600 font-extrabold">(<?= count($jbMendatang) ?>)</span></h2>
    </div>
    <div class="space-y-2.5">
        <?php foreach ($jbMendatang as $jb): echo renderJanjiCard($jb, $debiturs, $today); endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Terealisasi (recent) -->
<?php if (count($jbTerealisasi) > 0): ?>
<div class="px-4 mt-5 pb-6 animate-fade-in">
    <div class="flex items-center gap-2 mb-3">
        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
        <h2 class="text-base font-bold text-textMain">Terealisasi <span class="text-emerald-600 font-extrabold">(<?= count($jbTerealisasi) ?>)</span></h2>
    </div>
    <div class="space-y-2.5">
        <?php foreach (array_slice($jbTerealisasi, 0, 5) as $jb): echo renderJanjiCard($jb, $debiturs, $today, 'success'); endforeach; ?>
    </div>
</div>
<?php else: ?>
<div class="pb-6"></div>
<?php endif; ?>
