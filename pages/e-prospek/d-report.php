<?php
/**
 * Desktop E-Prospek - Report
 * Per AO: total prospek, conversion, breakdown by produk + score
 */

$aoReport = [];
foreach ($aoFiltered as $ao) {
    $myProspek = array_values(array_filter($prospekFiltered, fn($p) =>
        $p['ao_id'] === $ao['id'] || $p['inputter_id'] === $ao['id']));

    $byStatus = [
        'open'      => 0, 'pending' => 0, 'submit' => 0, 'realisasi' => 0, 'reject' => 0,
    ];
    foreach ($myProspek as $p) $byStatus[$p['status']]++;

    $totalNominal = array_sum(array_column($myProspek, 'nominal'));
    $realisasiNominal = array_sum(array_column(array_filter($myProspek, fn($p)=>$p['status']==='realisasi'), 'nominal'));

    $hot = count(array_filter($myProspek, fn($p) => $p['score'] >= 80));
    $totalCount = count($myProspek);
    $conv = $totalCount > 0 ? round($byStatus['realisasi'] / $totalCount * 100) : 0;

    $aoReport[] = [
        'ao'              => $ao,
        'total'           => $totalCount,
        'total_nominal'   => $totalNominal,
        'realisasi'       => $byStatus['realisasi'],
        'realisasi_nominal' => $realisasiNominal,
        'submit'          => $byStatus['submit'],
        'pending'         => $byStatus['pending'] + $byStatus['open'],
        'reject'          => $byStatus['reject'],
        'hot'             => $hot,
        'conversion'      => $conv,
    ];
}
usort($aoReport, fn($a, $b) => $b['realisasi_nominal'] <=> $a['realisasi_nominal']);

// Aggregate
$totalAll = count($prospekFiltered);
$totalRealisasi = count($prospekByStatus['realisasi']);
$nominalRealisasi = array_sum(array_column($prospekByStatus['realisasi'], 'nominal'));
$nominalAll = array_sum(array_column($prospekFiltered, 'nominal'));
$convAll = $totalAll > 0 ? round($totalRealisasi / $totalAll * 100, 1) : 0;
?>

<!-- Aggregate Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-card p-5 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase">Conversion Rate</p>
        <p class="text-3xl font-extrabold text-primary mt-2"><?= $convAll ?>%</p>
        <p class="text-[11px] text-textSub mt-0.5"><?= $totalRealisasi ?> / <?= $totalAll ?> realisasi</p>
    </div>
    <div class="bg-white rounded-2xl shadow-card p-5 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase">Total Nominal Realisasi</p>
        <p class="text-2xl font-extrabold text-emerald-700 mt-2"><?= vao_fmt_rp((int)$nominalRealisasi) ?></p>
        <p class="text-[11px] text-textSub mt-0.5">Booking bulan ini</p>
    </div>
    <div class="bg-white rounded-2xl shadow-card p-5 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase">Total Pipeline</p>
        <p class="text-2xl font-extrabold text-blue-700 mt-2"><?= vao_fmt_rp((int)$nominalAll) ?></p>
        <p class="text-[11px] text-textSub mt-0.5">Semua prospek</p>
    </div>
    <div class="bg-white rounded-2xl shadow-card p-5 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase">Submit Rate</p>
        <p class="text-3xl font-extrabold text-amber-700 mt-2"><?= $totalAll > 0 ? round((count($prospekByStatus['submit']) + $totalRealisasi) / $totalAll * 100) : 0 ?>%</p>
        <p class="text-[11px] text-textSub mt-0.5">Open → Submit/Realisasi</p>
    </div>
</div>

<!-- Per Produk + Score -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-card p-6 border border-gray-100">
        <h3 class="text-base font-bold text-textMain mb-4">Distribusi per Produk</h3>
        <?php foreach (['kredit','tabungan','deposito','aset'] as $prk):
            $stats = $prospekByProduk[$prk] ?? ['count'=>0,'nominal'=>0];
            $pct = $totalAll > 0 ? round($stats['count'] / $totalAll * 100) : 0;
        ?>
            <div class="mb-3">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="inline-flex items-center gap-2 text-xs font-semibold px-2 py-0.5 rounded-full <?= ep_produk_color($prk) ?>">
                        <?= ep_produk_label($prk) ?>
                    </span>
                    <span class="text-xs"><span class="font-bold text-textMain"><?= $stats['count'] ?></span> · <?= vao_fmt_rp((int)$stats['nominal']) ?></span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-gradient-to-r from-primary to-secondary h-2 rounded-full" style="width: <?= $pct ?>%"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="bg-white rounded-2xl shadow-card p-6 border border-gray-100">
        <h3 class="text-base font-bold text-textMain mb-4">Distribusi per Score</h3>
        <?php
        $hot = count(array_filter($prospekFiltered, fn($p) => $p['score'] >= 80));
        $warm = count(array_filter($prospekFiltered, fn($p) => $p['score'] >= 60 && $p['score'] < 80));
        $cold = count(array_filter($prospekFiltered, fn($p) => $p['score'] < 60));
        $scoreCfg = [
            ['HOT (≥80)',  $hot,  'bg-red-500',    'text-red-700'],
            ['WARM (60-79)', $warm, 'bg-amber-500', 'text-amber-700'],
            ['COLD (<60)',  $cold, 'bg-blue-500',  'text-blue-700'],
        ];
        foreach ($scoreCfg as [$lbl, $val, $cls, $textCls]):
            $pct = $totalAll > 0 ? round($val / $totalAll * 100) : 0;
        ?>
            <div class="mb-3">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="inline-flex items-center gap-2 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full <?= $cls ?>"></span><?= $lbl ?>
                    </span>
                    <span class="font-bold <?= $textCls ?>"><?= $val ?> (<?= $pct ?>%)</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="<?= $cls ?> h-2 rounded-full" style="width: <?= $pct ?>%"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Per AO -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-base font-bold text-textMain">Performance per AO</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-surface">
                <tr class="text-xs text-textSub uppercase tracking-wider">
                    <th class="text-left p-4 font-semibold">AO</th>
                    <th class="text-center p-4 font-semibold">Cabang</th>
                    <th class="text-right p-4 font-semibold">Total</th>
                    <th class="text-right p-4 font-semibold">Nominal</th>
                    <th class="text-right p-4 font-semibold">Realisasi</th>
                    <th class="text-right p-4 font-semibold">Nominal Realisasi</th>
                    <th class="text-center p-4 font-semibold">Hot</th>
                    <th class="text-center p-4 font-semibold">Pending</th>
                    <th class="text-center p-4 font-semibold">Conv.</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($aoReport as $r): $ao = $r['ao']; ?>
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
                        <td class="p-4 text-center text-xs"><?= htmlspecialchars(vao_kantor_nama($kantors, $ao['kode_kantor'])) ?></td>
                        <td class="p-4 text-right font-bold text-textMain"><?= $r['total'] ?></td>
                        <td class="p-4 text-right text-xs"><?= vao_fmt_rp((int)$r['total_nominal']) ?></td>
                        <td class="p-4 text-right font-bold text-emerald-700"><?= $r['realisasi'] ?></td>
                        <td class="p-4 text-right text-xs font-semibold text-emerald-700"><?= vao_fmt_rp((int)$r['realisasi_nominal']) ?></td>
                        <td class="p-4 text-center"><span class="text-[11px] font-bold w-6 h-6 rounded-md bg-red-100 text-red-700 inline-flex items-center justify-center"><?= $r['hot'] ?></span></td>
                        <td class="p-4 text-center"><span class="text-[11px] font-bold w-6 h-6 rounded-md bg-amber-100 text-amber-700 inline-flex items-center justify-center"><?= $r['pending'] ?></span></td>
                        <td class="p-4 text-center">
                            <span class="text-xs font-bold <?= $r['conversion'] >= 50 ? 'text-emerald-600' : ($r['conversion'] >= 25 ? 'text-amber-600' : 'text-red-600') ?>">
                                <?= $r['conversion'] ?>%
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($aoReport) === 0): ?>
                    <tr><td colspan="9" class="p-8 text-center text-sm text-textSub">Tidak ada AO pada cabang ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
