<?php
/**
 * Mobile E-Pipelane - Home (AO dashboard)
 */

$totalMyPipelane = count($myPipelanes);
$myInProgress = array_values(array_filter($myPipelanes, fn($pl) => $pl['status'] === 'in_progress'));
$myApproved   = array_values(array_filter($myPipelanes, fn($pl) => $pl['status'] === 'approved'));
$myRejected   = array_values(array_filter($myPipelanes, fn($pl) => $pl['status'] === 'rejected'));

// SLA alert
$myOverdue = [];
$myWarning = [];
foreach ($myInProgress as $pl) {
    [$slaStatus] = epl_sla_status($pl);
    if ($slaStatus === 'overdue') $myOverdue[] = $pl;
    elseif ($slaStatus === 'warning') $myWarning[] = $pl;
}
?>

<!-- Stats Card -->
<div class="px-4 -mt-4 relative z-10 animate-fade-in">
    <div class="bg-white rounded-2xl shadow-card-hover p-5">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="text-xs font-semibold text-textSub uppercase tracking-wider">Pipeline Saya</p>
                <p class="text-xs text-textSub mt-0.5"><?= htmlspecialchars($currentInputter['nama'] ?? '-') ?> • Credit SLA</p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-2">
            <div class="text-center p-3 bg-blue-50 rounded-xl">
                <p class="text-xl font-bold text-blue-700"><?= count($myInProgress) ?></p>
                <p class="text-[11px] text-blue-600/80 mt-0.5">Dalam Proses</p>
            </div>
            <div class="text-center p-3 bg-emerald-50 rounded-xl">
                <p class="text-xl font-bold text-emerald-700"><?= count($myApproved) ?></p>
                <p class="text-[11px] text-emerald-600/80 mt-0.5">Disetujui</p>
            </div>
            <div class="text-center p-3 bg-rose-50 rounded-xl">
                <p class="text-xl font-bold text-rose-700"><?= count($myRejected) ?></p>
                <p class="text-[11px] text-rose-600/80 mt-0.5">Ditolak</p>
            </div>
        </div>
    </div>
</div>

<!-- SLA Alert -->
<?php if (count($myOverdue) > 0): ?>
<div class="px-4 mt-4 animate-fade-in">
    <div class="bg-red-50 border-l-4 border-l-red-500 rounded-2xl p-4">
        <div class="flex items-start gap-3">
            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse mt-1.5 flex-shrink-0"></span>
            <div class="flex-1">
                <p class="text-sm font-bold text-red-900"><?= count($myOverdue) ?> Pipeline SLA Terlewat</p>
                <p class="text-xs text-red-700 mt-1">Segera advance stage atau eskalasi ke atasan.</p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Aksi Cepat -->
<div class="px-4 mt-5 animate-fade-in">
    <h2 class="text-base font-bold text-textMain mb-3">Aksi Cepat</h2>
    <div class="grid grid-cols-4 gap-3">
        <a href="<?= BASE_URL ?>e-pipelane?tab=list" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Semua</span>
        </a>
        <a href="<?= BASE_URL ?>e-pipelane?tab=list&filter=overdue" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2 relative">
                <svg class="w-6 h-6 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <?php if (count($myOverdue) > 0): ?><span class="absolute -top-1 -right-1 w-5 h-5 bg-danger text-white text-[10px] font-bold rounded-full flex items-center justify-center"><?= count($myOverdue) ?></span><?php endif; ?>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Overdue</span>
        </a>
        <a href="<?= BASE_URL ?>e-pipelane?tab=list&filter=warning" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2">
                <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xs text-textMain text-center font-medium">SLA H-1</span>
        </a>
        <a href="<?= BASE_URL ?>e-prospek?tab=form" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2">
                <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Prospek</span>
        </a>
    </div>
</div>

<!-- Pipeline Saya yang Aktif -->
<div class="px-4 mt-6 pb-6 animate-fade-in">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-base font-bold text-textMain">Pipeline Aktif</h2>
        <span class="text-xs text-textSub"><?= count($myInProgress) ?> aktif</span>
    </div>

    <?php if (count($myInProgress) === 0): ?>
        <div class="bg-white rounded-2xl shadow-card p-6 border border-gray-50 text-center">
            <p class="text-sm font-semibold text-textMain">🎉 Tidak ada pipeline aktif</p>
            <p class="text-xs text-textSub mt-1">Saatnya tambah prospek baru.</p>
        </div>
    <?php else: ?>
        <div class="space-y-2.5">
            <?php foreach ($myInProgress as $pl):
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
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full flex-shrink-0
                            <?= $slaStatus === 'overdue' ? 'bg-red-100 text-red-700' :
                                ($slaStatus === 'warning' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') ?>">
                            <?= $slaText ?>
                        </span>
                    </div>

                    <!-- Progress -->
                    <div class="flex items-center justify-between text-[11px] mb-1.5">
                        <span class="font-semibold text-textMain">Stage <?= $pl['current_stage'] ?>/7: <?= $stageInfo['label'] ?></span>
                        <span class="text-textSub"><?= $progress ?>%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-primary to-secondary h-2 rounded-full" style="width: <?= $progress ?>%"></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
