<?php
/**
 * Desktop E-Pipelane - Report SLA
 * Performance per AO + per stage breakdown
 */

// Per AO
$aoReport = [];
foreach ($aoFiltered as $ao) {
    $myPl = array_values(array_filter($pipelaneFiltered, fn($pl) => $pl['ao_id'] === $ao['id']));
    $myAprv = count(array_filter($myPl, fn($pl) => $pl['status'] === 'approved'));
    $myRej  = count(array_filter($myPl, fn($pl) => $pl['status'] === 'rejected'));
    $myAct  = count(array_filter($myPl, fn($pl) => $pl['status'] === 'in_progress'));
    $myOver = 0;
    foreach ($myPl as $pl) {
        [$s] = epl_sla_status($pl);
        if ($s === 'overdue') $myOver++;
    }
    $totalC = count($myPl);
    $conv = $totalC > 0 ? round($myAprv / $totalC * 100) : 0;

    // Avg duration (yang sudah selesai)
    $durations = [];
    foreach ($myPl as $pl) {
        if ($pl['status'] !== 'in_progress') {
            $start = strtotime($pl['started_at']);
            $end = strtotime($pl['stages'][7]['finished_at'] ?? $pl['stages'][6]['finished_at'] ?? $pl['stages'][5]['finished_at'] ?? $pl['started_at']);
            $durations[] = ($end - $start) / 86400; // dalam hari
        }
    }
    $avgDur = count($durations) > 0 ? round(array_sum($durations) / count($durations), 1) : 0;

    $aoReport[] = [
        'ao' => $ao, 'total' => $totalC, 'aktif' => $myAct,
        'approved' => $myAprv, 'rejected' => $myRej, 'overdue' => $myOver,
        'conversion' => $conv, 'avg_duration' => $avgDur,
    ];
}
usort($aoReport, fn($a, $b) => $b['total'] <=> $a['total']);

// SLA per Stage (avg actual vs target)
$stageReport = [];
foreach ($stagesCfg as $no => $cfg) {
    $durs = [];
    foreach ($pipelaneFiltered as $pl) {
        $st = $pl['stages'][$no] ?? null;
        if ($st && !empty($st['started_at']) && !empty($st['finished_at'])) {
            $d = (strtotime($st['finished_at']) - strtotime($st['started_at'])) / 86400;
            if ($d > 0) $durs[] = $d;
        }
    }
    $avg = count($durs) > 0 ? round(array_sum($durs) / count($durs), 1) : 0;
    $bocor = count(array_filter($durs, fn($d) => $d > $cfg['sla_days']));
    $stageReport[$no] = [
        'config' => $cfg, 'count' => count($durs), 'avg' => $avg, 'bocor' => $bocor,
    ];
}

$totalAll = count($pipelaneFiltered);
$totalApproved = count(array_filter($pipelaneFiltered, fn($pl) => $pl['status'] === 'approved'));
$convAll = $totalAll > 0 ? round($totalApproved / $totalAll * 100, 1) : 0;
?>

<!-- Aggregate -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-card p-5 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase">Approval Rate</p>
        <p class="text-3xl font-extrabold text-emerald-700 mt-2"><?= $convAll ?>%</p>
        <p class="text-[11px] text-textSub mt-0.5"><?= $totalApproved ?> dari <?= $totalAll ?></p>
    </div>
    <div class="bg-white rounded-2xl shadow-card p-5 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase">Total SLA Bocor</p>
        <p class="text-3xl font-extrabold text-red-700 mt-2"><?= $pipelaneSlaBreakdown['overdue'] ?></p>
        <p class="text-[11px] text-textSub mt-0.5">Pipeline dengan SLA terlewat</p>
    </div>
    <div class="bg-white rounded-2xl shadow-card p-5 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase">Pipeline Aktif</p>
        <p class="text-3xl font-extrabold text-blue-700 mt-2"><?= count(array_filter($pipelaneFiltered, fn($pl) => $pl['status'] === 'in_progress')) ?></p>
        <p class="text-[11px] text-textSub mt-0.5">Sedang diproses</p>
    </div>
    <div class="bg-white rounded-2xl shadow-card p-5 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase">Reject Rate</p>
        <p class="text-3xl font-extrabold text-rose-700 mt-2"><?= $totalAll > 0 ? round(count(array_filter($pipelaneFiltered, fn($pl) => $pl['status'] === 'rejected')) / $totalAll * 100, 1) : 0 ?>%</p>
        <p class="text-[11px] text-textSub mt-0.5">Ditolak komite</p>
    </div>
</div>

<!-- SLA per Stage -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 p-6 mb-6">
    <h3 class="text-base font-bold text-textMain mb-4">SLA Performance per Stage</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-surface">
                <tr class="text-xs text-textSub uppercase tracking-wider">
                    <th class="text-left p-3 font-semibold">Stage</th>
                    <th class="text-center p-3 font-semibold">Target SLA</th>
                    <th class="text-center p-3 font-semibold">Sample</th>
                    <th class="text-center p-3 font-semibold">Avg Aktual</th>
                    <th class="text-center p-3 font-semibold">Performance</th>
                    <th class="text-center p-3 font-semibold">Bocor</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stageReport as $no => $r):
                    $cfg = $r['config'];
                    $isOver = $r['avg'] > $cfg['sla_days'];
                    $perfPct = $cfg['sla_days'] > 0 ? min(200, round($cfg['sla_days'] / max(0.01, $r['avg']) * 100)) : 100;
                ?>
                    <tr class="border-t border-gray-50">
                        <td class="p-3">
                            <p class="text-sm font-bold text-textMain">Stage <?= $no ?></p>
                            <p class="text-[11px] text-textSub"><?= $cfg['label'] ?></p>
                        </td>
                        <td class="p-3 text-center font-bold text-textMain"><?= $cfg['sla_days'] ?> hari</td>
                        <td class="p-3 text-center text-xs text-textSub"><?= $r['count'] ?> pipeline</td>
                        <td class="p-3 text-center font-bold <?= $isOver ? 'text-red-600' : 'text-emerald-600' ?>"><?= $r['avg'] ?> hari</td>
                        <td class="p-3 text-center">
                            <div class="w-32 mx-auto">
                                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                    <div class="<?= $isOver ? 'bg-red-500' : 'bg-emerald-500' ?> h-2 rounded-full" style="width: <?= min(100, $perfPct) ?>%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="p-3 text-center">
                            <span class="text-[11px] font-bold w-8 h-6 rounded-md inline-flex items-center justify-center <?= $r['bocor'] > 0 ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' ?>">
                                <?= $r['bocor'] ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Per AO -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-base font-bold text-textMain">Performance per AO Kredit</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-surface">
                <tr class="text-xs text-textSub uppercase tracking-wider">
                    <th class="text-left p-4 font-semibold">AO</th>
                    <th class="text-center p-4 font-semibold">Cabang</th>
                    <th class="text-right p-4 font-semibold">Total</th>
                    <th class="text-right p-4 font-semibold">Aktif</th>
                    <th class="text-right p-4 font-semibold">Approved</th>
                    <th class="text-right p-4 font-semibold">Rejected</th>
                    <th class="text-center p-4 font-semibold">Overdue</th>
                    <th class="text-center p-4 font-semibold">Conv. %</th>
                    <th class="text-center p-4 font-semibold">Avg Durasi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($aoReport as $r): $ao = $r['ao']; if ($r['total'] === 0) continue; ?>
                    <tr class="border-t border-gray-50 hover:bg-surface/40">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    <?= $ao['inisial'] ?>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-textMain"><?= htmlspecialchars($ao['nama']) ?></p>
                                    <p class="text-[11px] text-textSub"><?= vao_role_label($ao['role']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-center text-xs"><?= htmlspecialchars(vao_kantor_nama($kantors, $ao['kode_kantor'])) ?></td>
                        <td class="p-4 text-right font-bold text-textMain"><?= $r['total'] ?></td>
                        <td class="p-4 text-right font-bold text-blue-700"><?= $r['aktif'] ?></td>
                        <td class="p-4 text-right font-bold text-emerald-700"><?= $r['approved'] ?></td>
                        <td class="p-4 text-right font-bold text-rose-700"><?= $r['rejected'] ?></td>
                        <td class="p-4 text-center"><span class="text-[11px] font-bold w-7 h-6 rounded-md inline-flex items-center justify-center <?= $r['overdue'] > 0 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-500' ?>"><?= $r['overdue'] ?></span></td>
                        <td class="p-4 text-center">
                            <span class="text-xs font-bold <?= $r['conversion'] >= 60 ? 'text-emerald-600' : ($r['conversion'] >= 30 ? 'text-amber-600' : 'text-red-600') ?>">
                                <?= $r['conversion'] ?>%
                            </span>
                        </td>
                        <td class="p-4 text-center text-xs font-bold text-textMain"><?= $r['avg_duration'] ?> hari</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
