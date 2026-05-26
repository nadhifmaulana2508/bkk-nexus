<?php
/**
 * Desktop E-Pipelane - List dengan filter stage / sla / status
 */

$filterStage = $_GET['stage'] ?? 'all';
$filterSla   = $_GET['sla']   ?? 'all';
$filterStat  = $_GET['stat']  ?? 'all';

$listed = $pipelaneFiltered;
if ($filterStage !== 'all') $listed = array_values(array_filter($listed, fn($pl) => (string)$pl['current_stage'] === $filterStage));
if ($filterStat  !== 'all') $listed = array_values(array_filter($listed, fn($pl) => $pl['status'] === $filterStat));
if ($filterSla   !== 'all') {
    $listed = array_values(array_filter($listed, function ($pl) use ($filterSla) {
        [$s] = epl_sla_status($pl);
        return $s === $filterSla;
    }));
}

usort($listed, fn($a, $b) => strcmp($a['sla_due_at'], $b['sla_due_at']));
?>

<!-- Filter chips -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 mb-4 px-4 py-3 flex flex-wrap items-center gap-2">
    <span class="text-xs font-semibold text-textSub uppercase mr-2">Stage:</span>
    <a href="<?= BASE_URL ?>e-pipelane?tab=list" class="text-xs px-3 py-1.5 rounded-lg font-semibold <?= $filterStage === 'all' ? 'bg-primary text-white' : 'bg-gray-100 text-textSub hover:bg-gray-200' ?>">Semua</a>
    <?php foreach ($stagesCfg as $no => $cfg):
        $isActive = $filterStage === (string)$no;
        $params = $_GET; $params['tab']='list'; $params['stage']=$no;
        $url = BASE_URL.'e-pipelane?'.http_build_query($params);
    ?>
        <a href="<?= $url ?>" class="text-xs px-3 py-1.5 rounded-lg font-semibold <?= $isActive ? 'bg-primary text-white' : 'bg-gray-100 text-textSub hover:bg-gray-200' ?>"><?= $no ?>. <?= $cfg['label'] ?></a>
    <?php endforeach; ?>

    <span class="mx-3 text-gray-300">|</span>
    <span class="text-xs font-semibold text-textSub uppercase mr-2">SLA:</span>
    <?php
    $slaOpts = ['all'=>'Semua','overdue'=>'⚠ Overdue','warning'=>'H-1','ontrack'=>'On Track','done'=>'Selesai'];
    foreach ($slaOpts as $k => $lbl):
        $isActive = $filterSla === $k;
        $params = $_GET; $params['tab']='list';
        if ($k === 'all') unset($params['sla']); else $params['sla']=$k;
        $url = BASE_URL.'e-pipelane?'.http_build_query($params);
    ?>
        <a href="<?= $url ?>" class="text-xs px-3 py-1.5 rounded-lg font-semibold <?= $isActive ? 'bg-secondary text-white' : 'bg-gray-100 text-textSub hover:bg-gray-200' ?>"><?= $lbl ?></a>
    <?php endforeach; ?>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-base font-bold text-textMain">Pipeline <span class="text-textSub font-normal">(<?= count($listed) ?>)</span></h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-surface">
                <tr class="text-xs text-textSub uppercase tracking-wider">
                    <th class="text-left p-4 font-semibold">Prospek</th>
                    <th class="text-left p-4 font-semibold">AO</th>
                    <th class="text-right p-4 font-semibold">Nominal</th>
                    <th class="text-left p-4 font-semibold">Stage Saat Ini</th>
                    <th class="text-center p-4 font-semibold">Progress</th>
                    <th class="text-center p-4 font-semibold">SLA</th>
                    <th class="text-center p-4 font-semibold">Durasi</th>
                    <th class="text-center p-4 font-semibold">Status</th>
                    <th class="text-center p-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listed as $pl):
                    $prospek = ep_prospek_by_id($prospeks, $pl['prospek_id']);
                    $ao = vao_ao_by_id($aos, $pl['ao_id']);
                    [$slaStatus, $slaText] = epl_sla_status($pl);
                    $progress = round($pl['current_stage'] / 7 * 100);
                    if (!$prospek) continue;
                ?>
                    <tr class="border-t border-gray-50 hover:bg-surface/40 <?= $slaStatus === 'overdue' ? 'bg-red-50/40' : '' ?>">
                        <td class="p-4">
                            <p class="font-semibold text-textMain"><?= htmlspecialchars($prospek['nama']) ?></p>
                            <p class="text-[11px] text-textSub mt-0.5"><?= htmlspecialchars($prospek['pemilik']) ?> · <?= htmlspecialchars($prospek['produk_sub'] ?? '') ?></p>
                        </td>
                        <td class="p-4 text-xs"><?= $ao ? htmlspecialchars($ao['nama']) : '-' ?></td>
                        <td class="p-4 text-right font-bold text-textMain"><?= vao_fmt_rp((int)$prospek['nominal']) ?></td>
                        <td class="p-4">
                            <p class="text-xs font-bold text-textMain">Stage <?= $pl['current_stage'] ?>/7</p>
                            <p class="text-[10px] text-textSub mt-0.5"><?= $stagesCfg[$pl['current_stage']]['label'] ?></p>
                        </td>
                        <td class="p-4">
                            <div class="w-24 mx-auto">
                                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                    <div class="<?= $pl['status'] === 'rejected' ? 'bg-rose-500' : ($pl['status'] === 'approved' ? 'bg-emerald-500' : 'bg-gradient-to-r from-primary to-secondary') ?> h-2 rounded-full" style="width: <?= $progress ?>%"></div>
                                </div>
                                <p class="text-[10px] text-textSub text-center mt-1"><?= $progress ?>%</p>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full
                                <?= $slaStatus === 'overdue' ? 'bg-red-100 text-red-700' :
                                    ($slaStatus === 'warning' ? 'bg-amber-100 text-amber-700' :
                                    ($slaStatus === 'done' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700')) ?>">
                                <?= $slaText ?>
                            </span>
                        </td>
                        <td class="p-4 text-center text-xs"><?= epl_total_duration($pl) ?></td>
                        <td class="p-4 text-center"><span class="text-[11px] font-bold px-2 py-0.5 rounded-full <?= epl_pipelane_status_color($pl['status']) ?>"><?= epl_pipelane_status_label($pl['status']) ?></span></td>
                        <td class="p-4 text-center">
                            <a href="<?= BASE_URL ?>e-pipelane?tab=detail&id=<?= $pl['id'] ?>" class="text-[11px] bg-primary/10 text-primary px-3 py-1.5 rounded-lg font-semibold hover:bg-primary/20">Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($listed) === 0): ?>
                    <tr><td colspan="9" class="p-8 text-center text-sm text-textSub">Tidak ada pipeline dengan filter ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
