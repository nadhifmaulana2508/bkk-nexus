<?php
/**
 * Desktop E-Pipelane - Overview SLA
 */

$totalAll = count($pipelaneFiltered);
$totalAktif = count(array_filter($pipelaneFiltered, fn($pl) => $pl['status'] === 'in_progress'));
$totalApproved = count(array_filter($pipelaneFiltered, fn($pl) => $pl['status'] === 'approved'));
$totalRejected = count(array_filter($pipelaneFiltered, fn($pl) => $pl['status'] === 'rejected'));

$totalOverdue = $pipelaneSlaBreakdown['overdue'];
$totalWarning = $pipelaneSlaBreakdown['warning'];

$nominalAktif = 0;
foreach ($pipelaneFiltered as $pl) {
    if ($pl['status'] === 'in_progress') {
        $prospek = ep_prospek_by_id($prospeks, $pl['prospek_id']);
        if ($prospek) $nominalAktif += $prospek['nominal'];
    }
}

// Recent updates
$recent = $pipelaneFiltered;
usort($recent, fn($a, $b) => strcmp($b['started_at'], $a['started_at']));
$recent = array_slice($recent, 0, 6);
?>

<!-- KPI Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card-blue rounded-2xl p-5 text-white relative overflow-hidden">
        <p class="text-xs opacity-90 font-semibold uppercase tracking-wider">Pipeline Aktif</p>
        <p class="text-3xl font-extrabold mt-2"><?= $totalAktif ?></p>
        <p class="text-xs opacity-80 mt-1"><?= vao_fmt_rp($nominalAktif) ?></p>
    </div>
    <div class="card-red rounded-2xl p-5 text-white relative overflow-hidden">
        <p class="text-xs opacity-90 font-semibold uppercase tracking-wider">⚠ Overdue</p>
        <p class="text-3xl font-extrabold mt-2"><?= $totalOverdue ?></p>
        <p class="text-xs opacity-80 mt-1">Perlu eskalasi segera</p>
    </div>
    <div class="card-orange rounded-2xl p-5 text-white relative overflow-hidden">
        <p class="text-xs opacity-90 font-semibold uppercase tracking-wider">SLA H-1</p>
        <p class="text-3xl font-extrabold mt-2"><?= $totalWarning ?></p>
        <p class="text-xs opacity-80 mt-1">≤ 24 jam tersisa</p>
    </div>
    <div class="card-green rounded-2xl p-5 text-white relative overflow-hidden">
        <p class="text-xs opacity-90 font-semibold uppercase tracking-wider">Disetujui</p>
        <p class="text-3xl font-extrabold mt-2"><?= $totalApproved ?></p>
        <p class="text-xs opacity-80 mt-1"><?= $totalAll > 0 ? round($totalApproved / $totalAll * 100) : 0 ?>% conversion</p>
    </div>
</div>

<!-- Distribusi per Stage -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 p-6 mb-6">
    <h3 class="text-base font-bold text-textMain mb-4">Distribusi per Stage</h3>
    <div class="grid grid-cols-2 lg:grid-cols-7 gap-3">
        <?php foreach ($stagesCfg as $stage => $cfg):
            $count = count($pipelaneByStage[$stage] ?? []);
        ?>
            <div class="p-4 bg-surface rounded-xl text-center">
                <div class="w-10 h-10 mx-auto mb-2 rounded-xl bg-gradient-to-br from-primary/10 to-secondary/10 flex items-center justify-center text-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $cfg['icon'] ?></svg>
                </div>
                <p class="text-2xl font-extrabold text-textMain"><?= $count ?></p>
                <p class="text-[11px] font-semibold text-textSub mt-0.5">Stage <?= $stage ?></p>
                <p class="text-[10px] text-textSub mt-0.5"><?= $cfg['label'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-bold text-textMain">Pipeline Terbaru</h3>
        <a href="<?= BASE_URL ?>e-pipelane<?= epl_query_with(['tab'=>'list']) ?>" class="text-sm text-primary font-semibold hover:underline">Lihat semua →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-textSub uppercase tracking-wider border-b border-gray-100">
                    <th class="text-left py-2 font-semibold">Prospek</th>
                    <th class="text-left py-2 font-semibold">AO</th>
                    <th class="text-right py-2 font-semibold">Nominal</th>
                    <th class="text-center py-2 font-semibold">Stage</th>
                    <th class="text-center py-2 font-semibold">SLA</th>
                    <th class="text-center py-2 font-semibold">Durasi</th>
                    <th class="text-center py-2 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent as $pl):
                    $prospek = ep_prospek_by_id($prospeks, $pl['prospek_id']);
                    $ao = vao_ao_by_id($aos, $pl['ao_id']);
                    [$slaStatus, $slaText] = epl_sla_status($pl);
                    if (!$prospek) continue;
                ?>
                    <tr class="border-b border-gray-50 hover:bg-surface/40">
                        <td class="py-3">
                            <p class="font-semibold text-textMain"><?= htmlspecialchars($prospek['nama']) ?></p>
                            <p class="text-[11px] text-textSub mt-0.5"><?= htmlspecialchars($prospek['produk_sub'] ?? '') ?></p>
                        </td>
                        <td class="py-3 text-xs"><?= $ao ? htmlspecialchars($ao['nama']) : '-' ?></td>
                        <td class="py-3 text-right font-semibold text-textMain"><?= vao_fmt_rp((int)$prospek['nominal']) ?></td>
                        <td class="py-3 text-center">
                            <span class="text-[11px] font-bold text-textMain">Stage <?= $pl['current_stage'] ?>/7</span>
                            <p class="text-[10px] text-textSub"><?= $stagesCfg[$pl['current_stage']]['label'] ?></p>
                        </td>
                        <td class="py-3 text-center">
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full
                                <?= $slaStatus === 'overdue' ? 'bg-red-100 text-red-700' :
                                    ($slaStatus === 'warning' ? 'bg-amber-100 text-amber-700' :
                                    ($slaStatus === 'done' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700')) ?>">
                                <?= $slaText ?>
                            </span>
                        </td>
                        <td class="py-3 text-center text-xs"><?= epl_total_duration($pl) ?></td>
                        <td class="py-3 text-center"><span class="text-[11px] font-bold px-2 py-0.5 rounded-full <?= epl_pipelane_status_color($pl['status']) ?>"><?= epl_pipelane_status_label($pl['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($recent) === 0): ?>
                    <tr><td colspan="7" class="py-6 text-center text-xs text-textSub">Belum ada pipeline.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
