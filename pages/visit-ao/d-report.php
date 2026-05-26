<?php
/**
 * Desktop - Laporan Visit AO (lengkap)
 * Statistik per AO, movement bucket, ranking, drilldown kelolaan.
 */

// Stats per AO
$aoReport = [];
foreach ($aoFiltered as $ao) {
    $myDebs   = vao_filter_debitur_by_ao($debiturFiltered, $ao['id']);
    $myVisits = array_values(array_filter($kunjunganFiltered, fn($k) => $k['ao_id'] === $ao['id']));
    $myJB     = array_values(array_filter($janjiBayarFiltered, fn($j) => $j['ao_id'] === $ao['id']));

    $jbAktif = array_filter($myJB, fn($j) => $j['status'] === 'aktif');
    $jbReal  = array_filter($myJB, fn($j) => $j['status'] === 'terealisasi');

    $movement = vao_compute_movement_stats($myDebs);
    $totalJB  = count($myJB);
    $conv     = $totalJB > 0 ? round(count($jbReal) / $totalJB * 100) : 0;

    $aoReport[] = [
        'ao'           => $ao,
        'total_debitur'=> count($myDebs),
        'total_baki'   => array_sum(array_column($myDebs, 'baki_debet')),
        'total_visit'  => count($myVisits),
        'jb_aktif'     => count($jbAktif),
        'jb_realisasi' => count($jbReal),
        'jb_nominal'   => array_sum(array_column($jbReal, 'realisasi_nominal')),
        'conversion'   => $conv,
        'movement'     => $movement,
    ];
}

// Sort default by total_visit desc
usort($aoReport, fn($a, $b) => $b['total_visit'] <=> $a['total_visit']);

// Aggregate movement
$totalMovement = vao_compute_movement_stats($debiturFiltered);
$totalDebitur  = count($debiturFiltered);
$totalBaki     = array_sum(array_column($debiturFiltered, 'baki_debet'));
$totalVisit    = count($kunjunganFiltered);
$totalRealisasi= array_sum(array_column(array_filter($janjiBayarFiltered, fn($j)=>$j['status']==='terealisasi'),'realisasi_nominal'));
?>

<!-- Aggregate Top Cards -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-card p-4 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase">Berhasil Bayar</p>
        <p class="text-3xl font-extrabold text-emerald-600 mt-2"><?= $totalMovement['paid_off'] ?></p>
        <p class="text-[11px] text-textSub mt-0.5">Lunas / closing penuh</p>
    </div>
    <div class="bg-white rounded-2xl shadow-card p-4 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase">Perbaikan Bucket</p>
        <p class="text-3xl font-extrabold text-green-600 mt-2"><?= $totalMovement['improve'] ?></p>
        <p class="text-[11px] text-textSub mt-0.5">DPD turun ke bucket lebih ringan</p>
    </div>
    <div class="bg-white rounded-2xl shadow-card p-4 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase">Stay</p>
        <p class="text-3xl font-extrabold text-gray-600 mt-2"><?= $totalMovement['stay'] ?></p>
        <p class="text-[11px] text-textSub mt-0.5">Bucket sama</p>
    </div>
    <div class="bg-white rounded-2xl shadow-card p-4 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase">Pemburukan</p>
        <p class="text-3xl font-extrabold text-red-600 mt-2"><?= $totalMovement['worse'] ?></p>
        <p class="text-[11px] text-textSub mt-0.5">DPD naik — perlu dikejar</p>
    </div>
    <div class="bg-white rounded-2xl shadow-card p-4 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase">Debitur Baru</p>
        <p class="text-3xl font-extrabold text-blue-600 mt-2"><?= $totalMovement['new'] ?></p>
        <p class="text-[11px] text-textSub mt-0.5">Bulan berjalan</p>
    </div>
</div>

<!-- Movement Bar Chart -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 p-6 mb-6">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="text-base font-bold text-textMain">Distribusi Pergerakan</h3>
            <p class="text-xs text-textSub mt-0.5">Total <?= $totalDebitur ?> debitur • <?= vao_fmt_rp((int)$totalBaki) ?> baki debet</p>
        </div>
    </div>

    <?php
    $movementCfg = [
        'paid_off' => ['label' => 'Berhasil Bayar / Lunas', 'cls' => 'bg-emerald-500', 'text' => 'text-emerald-700'],
        'improve'  => ['label' => 'Perbaikan Bucket',       'cls' => 'bg-green-500',   'text' => 'text-green-700'],
        'stay'     => ['label' => 'Stay (Tetap)',           'cls' => 'bg-gray-400',    'text' => 'text-gray-700'],
        'worse'    => ['label' => 'Pemburukan',             'cls' => 'bg-red-500',     'text' => 'text-red-700'],
        'new'      => ['label' => 'Debitur Baru',           'cls' => 'bg-blue-500',    'text' => 'text-blue-700'],
    ];
    $maxVal = max(1, max(array_values(array_intersect_key($totalMovement, $movementCfg))));
    ?>
    <div class="space-y-3">
        <?php foreach ($movementCfg as $key => $cfg):
            $val = $totalMovement[$key];
            $pct = $totalDebitur > 0 ? round($val / $totalDebitur * 100, 1) : 0;
        ?>
            <div>
                <div class="flex items-center justify-between text-xs mb-1.5">
                    <span class="font-semibold text-textMain inline-flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full <?= $cfg['cls'] ?>"></span>
                        <?= $cfg['label'] ?>
                    </span>
                    <span class="font-bold <?= $cfg['text'] ?>"><?= $val ?> <span class="font-normal opacity-60">(<?= $pct ?>%)</span></span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                    <div class="<?= $cfg['cls'] ?> h-3 rounded-full transition-all" style="width: <?= $totalDebitur > 0 ? round($val / $totalDebitur * 100, 1) : 0 ?>%"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Ranking AO -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 mb-6 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h3 class="text-base font-bold text-textMain">Performance per AO</h3>
            <p class="text-xs text-textSub mt-0.5">Drilldown kelolaan, visit, dan janji bayar.</p>
        </div>
        <button class="text-xs bg-gray-100 text-textMain px-3 py-1.5 rounded-lg font-semibold hover:bg-gray-200 inline-flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
            Export
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-surface">
                <tr class="text-xs text-textSub uppercase tracking-wider">
                    <th class="text-left p-4 font-semibold">AO</th>
                    <th class="text-center p-4 font-semibold">Cabang</th>
                    <th class="text-right p-4 font-semibold">Debitur</th>
                    <th class="text-right p-4 font-semibold">Baki Debet</th>
                    <th class="text-right p-4 font-semibold">Visit</th>
                    <th class="text-center p-4 font-semibold">Janji (A/R)</th>
                    <th class="text-right p-4 font-semibold">Realisasi</th>
                    <th class="text-center p-4 font-semibold">Conv. %</th>
                    <th class="text-center p-4 font-semibold">Movement</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($aoReport as $r):
                    $ao = $r['ao'];
                    $isPositive = ($r['movement']['paid_off'] + $r['movement']['improve']) > $r['movement']['worse'];
                ?>
                    <tr class="border-t border-gray-50 hover:bg-surface/40">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    <?= $ao['inisial'] ?>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-textMain truncate"><?= htmlspecialchars($ao['nama']) ?></p>
                                    <p class="text-[11px] text-textSub"><?= vao_role_label($ao['role']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-xs text-center"><?= htmlspecialchars(vao_kantor_nama($kantors, $ao['kode_kantor'])) ?></td>
                        <td class="p-4 text-right font-bold text-textMain"><?= $r['total_debitur'] ?></td>
                        <td class="p-4 text-right text-xs"><?= vao_fmt_rp((int)$r['total_baki']) ?></td>
                        <td class="p-4 text-right">
                            <span class="font-bold text-primary"><?= $r['total_visit'] ?></span>
                        </td>
                        <td class="p-4 text-center text-xs">
                            <span class="font-semibold text-textMain"><?= $r['jb_aktif'] ?></span>
                            <span class="text-textSub mx-0.5">/</span>
                            <span class="font-semibold text-emerald-600"><?= $r['jb_realisasi'] ?></span>
                        </td>
                        <td class="p-4 text-right text-xs font-semibold text-emerald-700"><?= vao_fmt_rp((int)$r['jb_nominal']) ?></td>
                        <td class="p-4 text-center">
                            <div class="inline-flex items-center gap-1">
                                <span class="text-xs font-bold <?= $r['conversion'] >= 70 ? 'text-emerald-600' : ($r['conversion'] >= 40 ? 'text-amber-600' : 'text-red-600') ?>">
                                    <?= $r['conversion'] ?>%
                                </span>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-1 justify-center">
                                <span title="Berhasil/Lunas" class="text-[10px] font-bold w-6 h-6 rounded-md bg-emerald-100 text-emerald-700 flex items-center justify-center"><?= $r['movement']['paid_off'] ?></span>
                                <span title="Improve" class="text-[10px] font-bold w-6 h-6 rounded-md bg-green-100 text-green-700 flex items-center justify-center"><?= $r['movement']['improve'] ?></span>
                                <span title="Stay" class="text-[10px] font-bold w-6 h-6 rounded-md bg-gray-100 text-gray-700 flex items-center justify-center"><?= $r['movement']['stay'] ?></span>
                                <span title="Worse" class="text-[10px] font-bold w-6 h-6 rounded-md bg-red-100 text-red-700 flex items-center justify-center"><?= $r['movement']['worse'] ?></span>
                                <span title="New" class="text-[10px] font-bold w-6 h-6 rounded-md bg-blue-100 text-blue-700 flex items-center justify-center"><?= $r['movement']['new'] ?></span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($aoReport) === 0): ?>
                    <tr><td colspan="9" class="p-8 text-center text-sm text-textSub">Tidak ada AO pada cabang terpilih.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Yang Perlu Dikejar (worse + bucket tertinggi) + Yang Stay -->
<?php
$debWorse = array_filter($debiturFiltered, fn($d) => $d['movement'] === 'worse');
usort($debWorse, fn($a,$b) => $b['baki_debet'] <=> $a['baki_debet']);
$debWorse = array_slice($debWorse, 0, 8);

$debStay = array_filter($debiturFiltered, fn($d) =>
    $d['movement'] === 'stay' && in_array($d['dpd_bucket'], ['dpd_31_60','dpd_61_90','dpd_91_180','dpd_181_plus','ph']));
usort($debStay, fn($a,$b) => $b['baki_debet'] <=> $a['baki_debet']);
$debStay = array_slice($debStay, 0, 8);
?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Perlu dikejar -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-100 p-6">
        <div class="flex items-center gap-2 mb-4">
            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
            <h3 class="text-base font-bold text-textMain">Perlu Dikejar (Pemburukan)</h3>
        </div>
        <?php if (count($debWorse) === 0): ?>
            <p class="text-sm text-textSub text-center py-6">Tidak ada debitur dengan status pemburukan.</p>
        <?php else: ?>
            <div class="space-y-2">
                <?php foreach ($debWorse as $d):
                    $ao = vao_ao_by_id($aos, $d['ao_id']);
                ?>
                    <div class="flex items-center gap-3 p-3 bg-red-50 rounded-xl border border-red-100">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-textMain truncate"><?= htmlspecialchars($d['nama']) ?></p>
                            <p class="text-[11px] text-textSub mt-0.5">
                                <?= vao_bucket_label($d['bucket_prev']) ?> → <span class="font-semibold text-red-600"><?= vao_bucket_label($d['dpd_bucket']) ?></span>
                                • <?= $ao ? htmlspecialchars($ao['nama']) : 'No AO' ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-textMain"><?= vao_fmt_rp((int)$d['baki_debet']) ?></p>
                            <p class="text-[11px] text-red-600 font-semibold"><?= $d['dpd'] ?> hari</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Stay di bucket berat -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-100 p-6">
        <div class="flex items-center gap-2 mb-4">
            <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
            <h3 class="text-base font-bold text-textMain">Stay di Bucket Berat</h3>
        </div>
        <?php if (count($debStay) === 0): ?>
            <p class="text-sm text-textSub text-center py-6">Tidak ada debitur stay di bucket berat.</p>
        <?php else: ?>
            <div class="space-y-2">
                <?php foreach ($debStay as $d):
                    $ao = vao_ao_by_id($aos, $d['ao_id']);
                ?>
                    <div class="flex items-center gap-3 p-3 bg-amber-50 rounded-xl border border-amber-100">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-textMain truncate"><?= htmlspecialchars($d['nama']) ?></p>
                            <p class="text-[11px] text-textSub mt-0.5">
                                <span class="font-semibold text-amber-700"><?= vao_bucket_label($d['dpd_bucket']) ?></span>
                                • <?= $ao ? htmlspecialchars($ao['nama']) : 'No AO' ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-textMain"><?= vao_fmt_rp((int)$d['baki_debet']) ?></p>
                            <p class="text-[11px] text-amber-700 font-semibold"><?= $d['dpd'] ?> hari</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
