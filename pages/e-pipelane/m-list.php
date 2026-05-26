<?php
/**
 * Mobile E-Pipelane - List
 */

$filter = $_GET['filter'] ?? 'all';

$listed = $myPipelanes;
$counts = [
    'all'       => count($myPipelanes),
    'in_progress' => count(array_filter($myPipelanes, fn($pl) => $pl['status'] === 'in_progress')),
    'overdue'   => 0, 'warning' => 0,
    'approved'  => count(array_filter($myPipelanes, fn($pl) => $pl['status'] === 'approved')),
    'rejected'  => count(array_filter($myPipelanes, fn($pl) => $pl['status'] === 'rejected')),
];
foreach ($myPipelanes as $pl) {
    [$s] = epl_sla_status($pl);
    if ($s === 'overdue') $counts['overdue']++;
    elseif ($s === 'warning') $counts['warning']++;
}

if ($filter === 'in_progress') $listed = array_values(array_filter($listed, fn($pl) => $pl['status'] === 'in_progress'));
elseif ($filter === 'approved') $listed = array_values(array_filter($listed, fn($pl) => $pl['status'] === 'approved'));
elseif ($filter === 'rejected') $listed = array_values(array_filter($listed, fn($pl) => $pl['status'] === 'rejected'));
elseif ($filter === 'overdue' || $filter === 'warning') {
    $listed = array_values(array_filter($listed, function ($pl) use ($filter) {
        [$s] = epl_sla_status($pl);
        return $s === $filter;
    }));
}
?>

<!-- Header -->
<div class="px-4 -mt-4 relative z-10 animate-fade-in">
    <div class="bg-white rounded-2xl shadow-card-hover p-4">
        <p class="text-sm font-bold text-textMain">Pipeline Saya</p>
        <p class="text-xs text-textSub mt-0.5"><?= count($myPipelanes) ?> total · Credit SLA tracking</p>
    </div>
</div>

<!-- Filter chips -->
<div class="px-4 mt-4 animate-fade-in">
    <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide pb-1">
        <?php
        $filterTabs = [
            'all'         => 'Semua',
            'in_progress' => 'Dalam Proses',
            'overdue'     => '⚠ Overdue',
            'warning'     => 'SLA H-1',
            'approved'    => 'Disetujui',
            'rejected'    => 'Ditolak',
        ];
        foreach ($filterTabs as $key => $label):
            $isActive = $filter === $key;
            $url = BASE_URL . 'e-pipelane?tab=list' . ($key !== 'all' ? '&filter=' . $key : '');
        ?>
            <a href="<?= $url ?>"
               class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold transition-colors
                      <?= $isActive ? 'bg-primary text-white shadow-sm' : 'bg-white text-textSub border border-gray-200' ?>">
                <?= $label ?>
                <span class="<?= $isActive ? 'bg-white/25' : 'bg-gray-100' ?> px-1.5 py-0.5 rounded-full text-[10px]"><?= $counts[$key] ?? 0 ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- List -->
<div class="px-4 mt-4 pb-6 animate-fade-in">
    <?php if (count($listed) === 0): ?>
        <div class="bg-white rounded-2xl shadow-card p-6 border border-gray-50 text-center">
            <p class="text-sm font-semibold text-textMain">Tidak ada pipeline</p>
            <p class="text-xs text-textSub mt-1">Coba ubah filter di atas.</p>
        </div>
    <?php else: ?>
        <div class="space-y-2.5">
            <?php foreach ($listed as $pl):
                $prospek = ep_prospek_by_id($prospeks, $pl['prospek_id']);
                if (!$prospek) continue;
                [$slaStatus, $slaText] = epl_sla_status($pl);
                $stageInfo = $stagesCfg[$pl['current_stage']];
                $progress = round($pl['current_stage'] / 7 * 100);
            ?>
                <a href="<?= BASE_URL ?>e-pipelane?tab=detail&id=<?= $pl['id'] ?>" class="block bg-white rounded-2xl shadow-card p-4 border border-gray-50 hover:shadow-card-hover transition-shadow
                    <?= $slaStatus === 'overdue' ? 'border-l-4 border-l-red-500' : ($slaStatus === 'warning' ? 'border-l-4 border-l-amber-500' : '') ?>">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-400 to-orange-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                            <?= strtoupper(substr($prospek['nama'], 0, 2)) ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-textMain truncate"><?= htmlspecialchars($prospek['nama']) ?></p>
                            <p class="text-xs text-textSub mt-0.5"><?= htmlspecialchars($prospek['produk_sub'] ?? 'Kredit') ?> · <?= vao_fmt_rp((int)$prospek['nominal']) ?></p>
                        </div>
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full flex-shrink-0 <?= epl_pipelane_status_color($pl['status']) ?>">
                            <?= epl_pipelane_status_label($pl['status']) ?>
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-[11px] mb-1.5">
                        <span class="font-semibold text-textMain">Stage <?= $pl['current_stage'] ?>/7: <?= $stageInfo['label'] ?></span>
                        <span class="<?= $slaStatus === 'overdue' ? 'text-red-600 font-bold' : ($slaStatus === 'warning' ? 'text-amber-600 font-semibold' : 'text-textSub') ?>">
                            <?= $slaText ?>
                        </span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                        <div class="<?= $pl['status'] === 'rejected' ? 'bg-rose-500' : ($pl['status'] === 'approved' ? 'bg-emerald-500' : 'bg-gradient-to-r from-primary to-secondary') ?> h-2 rounded-full" style="width: <?= $progress ?>%"></div>
                    </div>

                    <div class="flex items-center justify-between mt-2 text-[11px] text-textSub">
                        <span>Mulai: <?= vao_fmt_tgl(substr($pl['started_at'], 0, 10)) ?></span>
                        <span>Durasi: <?= epl_total_duration($pl) ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
