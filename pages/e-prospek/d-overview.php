<?php
/**
 * Desktop E-Prospek - Overview
 */

$totalProspek = count($prospekFiltered);
$totalNominal = array_sum(array_column($prospekFiltered, 'nominal'));
$totalRealisasi = count($prospekByStatus['realisasi']);
$nominalRealisasi = array_sum(array_column($prospekByStatus['realisasi'], 'nominal'));
$conversion = $totalProspek > 0 ? round($totalRealisasi / $totalProspek * 100) : 0;

$totalHotLeads = count(array_filter($prospekFiltered, fn($p) => $p['score'] >= 80 && in_array($p['status'], ['open','pending','submit'])));
$totalReject = count($prospekByStatus['reject']);

// Recent prospek (sort by created_at desc)
$recent = $prospekFiltered;
usort($recent, fn($a, $b) => strcmp($b['created_at'], $a['created_at']));
$recent = array_slice($recent, 0, 8);
?>

<!-- KPI Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card-blue rounded-2xl p-5 text-white relative overflow-hidden">
        <p class="text-xs opacity-90 font-semibold uppercase tracking-wider">Total Prospek</p>
        <p class="text-3xl font-extrabold mt-2"><?= number_format($totalProspek, 0, ',', '.') ?></p>
        <p class="text-xs opacity-80 mt-1"><?= vao_fmt_rp((int)$totalNominal) ?></p>
    </div>
    <div class="card-orange rounded-2xl p-5 text-white relative overflow-hidden">
        <p class="text-xs opacity-90 font-semibold uppercase tracking-wider">Pending Delegasi</p>
        <p class="text-3xl font-extrabold mt-2"><?= count($prospekPendingDelegasi) ?></p>
        <p class="text-xs opacity-80 mt-1">Belum ada AO</p>
    </div>
    <div class="card-purple rounded-2xl p-5 text-white relative overflow-hidden">
        <p class="text-xs opacity-90 font-semibold uppercase tracking-wider">Hot Leads</p>
        <p class="text-3xl font-extrabold mt-2"><?= $totalHotLeads ?></p>
        <p class="text-xs opacity-80 mt-1">Score ≥ 80</p>
    </div>
    <div class="card-green rounded-2xl p-5 text-white relative overflow-hidden">
        <p class="text-xs opacity-90 font-semibold uppercase tracking-wider">Realisasi</p>
        <p class="text-3xl font-extrabold mt-2"><?= $totalRealisasi ?></p>
        <p class="text-xs opacity-80 mt-1"><?= $conversion ?>% conversion</p>
    </div>
</div>

<!-- Alert Pending Delegasi -->
<?php if (count($prospekPendingDelegasi) > 0): ?>
<div class="mb-6 bg-amber-50 border-l-4 border-l-amber-500 rounded-2xl p-5 flex items-start gap-4">
    <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857"/></svg>
    </div>
    <div class="flex-1">
        <p class="text-sm font-bold text-amber-900">⚠ <?= count($prospekPendingDelegasi) ?> Prospek Belum Didelegasikan</p>
        <p class="text-xs text-amber-700 mt-1">Inputter bukan AO atau cabang belum punya AO untuk produk tersebut.</p>
        <a href="<?= BASE_URL ?>e-prospek<?= ep_query_with(['tab'=>'delegasi']) ?>" class="inline-flex items-center gap-1 text-xs font-semibold text-amber-700 mt-2 hover:underline">
            Lakukan delegasi
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Funnel + Produk -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
    <!-- Funnel -->
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-card p-6 border border-gray-100">
        <h3 class="text-lg font-bold text-textMain mb-4">Funnel Status Prospek</h3>
        <div class="space-y-3">
            <?php
            $funnelCfg = [
                'open'      => ['Open',      'bg-gray-400',     'text-gray-700'],
                'pending'   => ['Pending',   'bg-amber-500',    'text-amber-700'],
                'submit'    => ['Submitted', 'bg-blue-500',     'text-blue-700'],
                'realisasi' => ['Realisasi', 'bg-emerald-500',  'text-emerald-700'],
                'reject'    => ['Ditolak',   'bg-rose-500',     'text-rose-700'],
            ];
            foreach ($funnelCfg as $key => [$lbl, $cls, $textCls]):
                $count = count($prospekByStatus[$key]);
                $pct = $totalProspek > 0 ? round($count / $totalProspek * 100, 1) : 0;
            ?>
                <div>
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="font-semibold text-textMain inline-flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full <?= $cls ?>"></span>
                            <?= $lbl ?>
                        </span>
                        <span class="font-bold <?= $textCls ?>"><?= $count ?> <span class="font-normal opacity-60">(<?= $pct ?>%)</span></span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        <div class="<?= $cls ?> h-3 rounded-full" style="width: <?= $pct ?>%"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Per Produk -->
    <div class="bg-white rounded-2xl shadow-card p-6 border border-gray-100">
        <h3 class="text-lg font-bold text-textMain mb-4">Per Jenis Produk</h3>
        <div class="space-y-3">
            <?php foreach (['kredit','tabungan','deposito','aset'] as $prk):
                $stats = $prospekByProduk[$prk] ?? ['count'=>0,'nominal'=>0];
            ?>
                <div class="flex items-center justify-between p-3 bg-surface rounded-xl">
                    <span class="inline-flex items-center gap-2 text-xs font-semibold px-2 py-1 rounded-full <?= ep_produk_color($prk) ?>">
                        <?= ep_produk_label($prk) ?>
                    </span>
                    <div class="text-right">
                        <p class="text-base font-bold text-textMain"><?= $stats['count'] ?></p>
                        <p class="text-[11px] text-textSub"><?= vao_fmt_rp((int)$stats['nominal']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-2xl shadow-card p-6 border border-gray-100">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-textMain">Prospek Terbaru</h3>
        <a href="<?= BASE_URL ?>e-prospek<?= ep_query_with(['tab'=>'list']) ?>" class="text-sm text-primary font-semibold hover:underline">Lihat semua →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-textSub uppercase tracking-wider border-b border-gray-100">
                    <th class="text-left py-2 font-semibold">Tanggal</th>
                    <th class="text-left py-2 font-semibold">Prospek</th>
                    <th class="text-left py-2 font-semibold">AO</th>
                    <th class="text-center py-2 font-semibold">Produk</th>
                    <th class="text-right py-2 font-semibold">Nominal</th>
                    <th class="text-center py-2 font-semibold">Score</th>
                    <th class="text-center py-2 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent as $p):
                    $ao = $p['ao_id'] ? vao_ao_by_id($aos, $p['ao_id']) : null;
                ?>
                    <tr class="border-b border-gray-50 hover:bg-surface/40">
                        <td class="py-3 text-xs text-textSub"><?= htmlspecialchars(substr($p['created_at'], 0, 10)) ?></td>
                        <td class="py-3">
                            <p class="font-semibold text-textMain"><?= htmlspecialchars($p['nama']) ?></p>
                            <p class="text-[11px] text-textSub mt-0.5"><?= htmlspecialchars($p['pemilik']) ?></p>
                        </td>
                        <td class="py-3 text-xs"><?= $ao ? htmlspecialchars($ao['nama']) : '<span class="text-amber-600 font-semibold">⚠ Belum</span>' ?></td>
                        <td class="py-3 text-center"><span class="text-[11px] font-semibold px-2 py-0.5 rounded-full <?= ep_produk_color($p['produk_jenis']) ?>"><?= ep_produk_label($p['produk_jenis']) ?></span></td>
                        <td class="py-3 text-right font-semibold text-textMain"><?= vao_fmt_rp((int)$p['nominal']) ?></td>
                        <td class="py-3 text-center"><span class="text-[11px] font-bold px-2 py-0.5 rounded-full <?= ep_score_color($p['score']) ?>"><?= ep_score_label($p['score']) ?></span></td>
                        <td class="py-3 text-center"><span class="text-[11px] font-bold px-2 py-0.5 rounded-full <?= ep_status_color($p['status']) ?>"><?= ep_status_label($p['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($recent) === 0): ?>
                    <tr><td colspan="7" class="py-6 text-center text-xs text-textSub">Belum ada prospek.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
