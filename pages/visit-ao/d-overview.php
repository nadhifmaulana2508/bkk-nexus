<?php
/**
 * Desktop - Visit AO Overview
 * Ringkasan agregat untuk atasan (Kabid/Kacab/Pincab/PE).
 */

$totalDebitur     = count($debiturFiltered);
$totalKunjungan   = count($kunjunganFiltered);
$totalJanjiAktif  = count(array_filter($janjiBayarFiltered, fn($j) => $j['status'] === 'aktif'));
$totalJanjiOk     = count(array_filter($janjiBayarFiltered, fn($j) => $j['status'] === 'terealisasi'));
$totalAktifBaki   = array_sum(array_column($debiturFiltered, 'baki_debet'));
$totalJanjiNominal= array_sum(array_column(array_filter($janjiBayarFiltered, fn($j)=>$j['status']==='aktif'), 'nominal_janji'));

$movementAll = vao_compute_movement_stats($debiturFiltered);

// Janji bayar terlewat (eskalasi)
$today = $filterHarianDate;
$janjiTerlewat = array_values(array_filter($janjiBayarFiltered, fn($j) =>
    $j['status'] === 'aktif' && strtotime($j['tanggal_janji']) < strtotime($today)));

// AO yang belum mapping (debitur Remedial belum punya AO)
// Untuk dummy: anggap semua debitur sudah punya AO. Tampilkan placeholder ringan.
$pendingMapping = array_values(array_filter($debiturFiltered, fn($d) =>
    in_array($d['dpd_bucket'], ['dpd_31_60','dpd_61_90','dpd_91_180','dpd_181_plus','ph']) && empty($d['ao_id'])));

// Top performer (AO dengan visit terbanyak)
$visitByAo = [];
foreach ($kunjunganFiltered as $k) {
    $visitByAo[$k['ao_id']] = ($visitByAo[$k['ao_id']] ?? 0) + 1;
}
arsort($visitByAo);
$topAoList = array_slice($visitByAo, 0, 5, true);

// Recent activity
$recentVisits = $kunjunganFiltered;
usort($recentVisits, fn($a, $b) => strcmp($b['tanggal'].$b['waktu'], $a['tanggal'].$a['waktu']));
$recentVisits = array_slice($recentVisits, 0, 8);
?>

<!-- KPI Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card-blue rounded-2xl p-5 text-white relative overflow-hidden">
        <p class="text-xs opacity-90 font-semibold uppercase tracking-wider">Total Debitur</p>
        <p class="text-3xl font-extrabold mt-2"><?= number_format($totalDebitur, 0, ',', '.') ?></p>
        <p class="text-xs opacity-80 mt-1"><?= vao_fmt_rp((int)$totalAktifBaki) ?> baki debet</p>
        <svg class="absolute -bottom-2 -right-2 w-20 h-20 opacity-15" fill="currentColor" viewBox="0 0 24 24"><path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A1.5 1.5 0 0 0 18.54 8H17.5c-.65 0-1.21.41-1.42 1.01L13.55 16H16v6h4zM8.5 13c1.38 0 2.5-1.12 2.5-2.5S9.88 8 8.5 8 6 9.12 6 10.5 7.12 13 8.5 13zm2.5 9v-7H6v7h5z"/></svg>
    </div>
    <div class="card-purple rounded-2xl p-5 text-white relative overflow-hidden">
        <p class="text-xs opacity-90 font-semibold uppercase tracking-wider">Visit</p>
        <p class="text-3xl font-extrabold mt-2"><?= number_format($totalKunjungan, 0, ',', '.') ?></p>
        <p class="text-xs opacity-80 mt-1">Bulan berjalan</p>
        <svg class="absolute -bottom-2 -right-2 w-20 h-20 opacity-15" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z"/></svg>
    </div>
    <div class="card-orange rounded-2xl p-5 text-white relative overflow-hidden">
        <p class="text-xs opacity-90 font-semibold uppercase tracking-wider">Janji Bayar Aktif</p>
        <p class="text-3xl font-extrabold mt-2"><?= $totalJanjiAktif ?></p>
        <p class="text-xs opacity-80 mt-1"><?= vao_fmt_rp((int)$totalJanjiNominal) ?> komitmen</p>
        <svg class="absolute -bottom-2 -right-2 w-20 h-20 opacity-15" fill="currentColor" viewBox="0 0 24 24"><path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg>
    </div>
    <div class="card-green rounded-2xl p-5 text-white relative overflow-hidden">
        <p class="text-xs opacity-90 font-semibold uppercase tracking-wider">Berhasil Bayar / Improve</p>
        <p class="text-3xl font-extrabold mt-2"><?= $movementAll['paid_off'] + $movementAll['improve'] ?></p>
        <p class="text-xs opacity-80 mt-1"><?= $totalJanjiOk ?> realisasi janji</p>
        <svg class="absolute -bottom-2 -right-2 w-20 h-20 opacity-15" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
    </div>
</div>

<!-- Alerts -->
<?php if (count($janjiTerlewat) > 0 || count($pendingMapping) > 0): ?>
<div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
    <?php if (count($janjiTerlewat) > 0): ?>
        <div class="bg-red-50 border-l-4 border-l-red-500 rounded-2xl p-5 flex items-start gap-4">
            <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-red-900">⚠️ <?= count($janjiTerlewat) ?> Janji Bayar Terlewat</p>
                <p class="text-xs text-red-700 mt-1">Perlu eskalasi ke AO Remedial atau follow-up langsung.</p>
                <a href="<?= BASE_URL ?>visit-ao?tab=janji-bayar&filter=overdue" class="inline-flex items-center gap-1 text-xs font-semibold text-red-700 mt-2 hover:underline">
                    Lihat detail
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <?php if (count($pendingMapping) > 0): ?>
        <div class="bg-amber-50 border-l-4 border-l-amber-500 rounded-2xl p-5 flex items-start gap-4">
            <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857"/></svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-amber-900">⏳ <?= count($pendingMapping) ?> Debitur Remedial Belum Dimapping</p>
                <p class="text-xs text-amber-700 mt-1">Wajib dimapping awal bulan oleh Kabid/Kacab.</p>
                <a href="<?= BASE_URL ?>visit-ao?tab=mapping" class="inline-flex items-center gap-1 text-xs font-semibold text-amber-700 mt-2 hover:underline">
                    Lakukan mapping
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Movement Summary + Top AO -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
    <!-- Movement Summary -->
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-card p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-textMain">Pergerakan Bucket</h3>
            <a href="<?= BASE_URL ?>visit-ao?tab=report" class="text-sm text-primary font-semibold hover:underline">Detail laporan →</a>
        </div>
        <p class="text-xs text-textSub mb-5">Snapshot per <?= vao_fmt_tgl($filterClosingDate) ?> dibanding bulan sebelumnya.</p>

        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
            <?php
            $cards = [
                ['key' => 'paid_off', 'label' => 'Berhasil Bayar', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'sub' => 'Lunas / closing'],
                ['key' => 'improve',  'label' => 'Perbaikan',     'bg' => 'bg-green-50',   'text' => 'text-green-700',   'sub' => 'Bucket turun'],
                ['key' => 'stay',     'label' => 'Stay',          'bg' => 'bg-gray-50',    'text' => 'text-gray-700',    'sub' => 'Bucket sama'],
                ['key' => 'worse',    'label' => 'Pemburukan',    'bg' => 'bg-red-50',     'text' => 'text-red-700',     'sub' => 'Bucket naik'],
                ['key' => 'new',      'label' => 'Debitur Baru',  'bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'sub' => 'Bulan ini'],
            ];
            foreach ($cards as $c): ?>
                <div class="<?= $c['bg'] ?> rounded-xl p-4 text-center border border-current/5">
                    <p class="text-3xl font-extrabold <?= $c['text'] ?>"><?= $movementAll[$c['key']] ?></p>
                    <p class="text-xs font-semibold <?= $c['text'] ?> mt-1"><?= $c['label'] ?></p>
                    <p class="text-[11px] <?= $c['text'] ?> opacity-60 mt-0.5"><?= $c['sub'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Top AO -->
    <div class="bg-white rounded-2xl shadow-card p-6 border border-gray-100">
        <h3 class="text-lg font-bold text-textMain mb-4">Top AO (Visit)</h3>
        <?php if (count($topAoList) === 0): ?>
            <p class="text-xs text-textSub text-center py-4">Belum ada visit.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php $rank = 1; foreach ($topAoList as $aoId => $count):
                    $ao = vao_ao_by_id($aos, $aoId);
                    if (!$ao) continue;
                    $medal = $rank === 1 ? '🥇' : ($rank === 2 ? '🥈' : ($rank === 3 ? '🥉' : '#' . $rank));
                ?>
                    <div class="flex items-center gap-3 p-3 bg-surface rounded-xl">
                        <div class="text-lg w-8 text-center"><?= $medal ?></div>
                        <div class="w-9 h-9 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            <?= $ao['inisial'] ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-textMain truncate"><?= htmlspecialchars($ao['nama']) ?></p>
                            <p class="text-[11px] text-textSub"><?= vao_role_label($ao['role']) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-base font-extrabold text-primary"><?= $count ?></p>
                            <p class="text-[10px] text-textSub">visit</p>
                        </div>
                    </div>
                <?php $rank++; endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-2xl shadow-card p-6 border border-gray-100">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-textMain">Aktivitas Visit Terbaru</h3>
        <a href="<?= BASE_URL ?>visit-ao?tab=report" class="text-sm text-primary font-semibold hover:underline">Lihat semua →</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-textSub uppercase tracking-wider border-b border-gray-100">
                    <th class="text-left py-2 font-semibold">Tanggal</th>
                    <th class="text-left py-2 font-semibold">Debitur</th>
                    <th class="text-left py-2 font-semibold">AO</th>
                    <th class="text-left py-2 font-semibold">Bucket</th>
                    <th class="text-left py-2 font-semibold">Hasil</th>
                    <th class="text-right py-2 font-semibold">Janji</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentVisits as $v):
                    $deb = vao_debitur_by_id($debiturs, $v['debitur_id']);
                    $ao  = vao_ao_by_id($aos, $v['ao_id']);
                    if (!$deb || !$ao) continue;
                ?>
                    <tr class="border-b border-gray-50 hover:bg-surface/50">
                        <td class="py-3 text-xs text-textSub"><?= vao_fmt_tgl($v['tanggal']) ?> <span class="text-gray-400"><?= $v['waktu'] ?></span></td>
                        <td class="py-3"><span class="font-semibold text-textMain"><?= htmlspecialchars($deb['nama']) ?></span></td>
                        <td class="py-3 text-xs"><?= htmlspecialchars($ao['nama']) ?></td>
                        <td class="py-3"><span class="text-[11px] font-semibold px-2 py-0.5 rounded-full <?= vao_bucket_color($deb['dpd_bucket']) ?>"><?= vao_bucket_label($deb['dpd_bucket']) ?></span></td>
                        <td class="py-3"><span class="text-[11px] font-semibold px-2 py-0.5 rounded-full <?= vao_visit_hasil_color($v['hasil']) ?>"><?= vao_visit_hasil_label($v['hasil']) ?></span></td>
                        <td class="py-3 text-right text-xs">
                            <?= !empty($v['nominal_janji']) ? '<span class="font-semibold text-textMain">' . vao_fmt_rp((int)$v['nominal_janji']) . '</span>' : '<span class="text-gray-400">—</span>' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($recentVisits) === 0): ?>
                    <tr><td colspan="6" class="py-6 text-center text-xs text-textSub">Belum ada visit pada periode ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
