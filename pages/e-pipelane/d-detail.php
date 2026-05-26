<?php
/**
 * Desktop E-Pipelane - Detail Timeline
 */

$id = (int)($_GET['id'] ?? 0);
$pl = epl_pipelane_by_id($pipelanes, $id);

if (!$pl) {
    echo '<div class="bg-white rounded-2xl shadow-card p-8 text-center text-textSub">Pipeline tidak ditemukan.</div>';
    return;
}

$prospek = ep_prospek_by_id($prospeks, $pl['prospek_id']);
$ao = vao_ao_by_id($aos, $pl['ao_id']);
[$slaStatus, $slaText] = epl_sla_status($pl);
$progress = round($pl['current_stage'] / 7 * 100);
?>

<!-- Header -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 p-6 mb-4">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-500 flex items-center justify-center text-white text-lg font-bold flex-shrink-0">
                <?= strtoupper(substr($prospek['nama'], 0, 2)) ?>
            </div>
            <div>
                <h2 class="text-xl font-bold text-textMain"><?= htmlspecialchars($prospek['nama']) ?></h2>
                <p class="text-sm text-textSub mt-0.5"><?= htmlspecialchars($prospek['pemilik']) ?> · <?= htmlspecialchars($prospek['produk_sub'] ?? 'Kredit') ?></p>
                <div class="flex items-center gap-2 mt-2 flex-wrap">
                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full <?= epl_pipelane_status_color($pl['status']) ?>"><?= epl_pipelane_status_label($pl['status']) ?></span>
                    <?php if ($pl['priority'] === 'high'): ?><span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700">⚡ High Priority</span><?php endif; ?>
                    <?php if ($pl['status'] === 'in_progress'): ?>
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full
                            <?= $slaStatus === 'overdue' ? 'bg-red-100 text-red-700' : ($slaStatus === 'warning' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') ?>">
                            SLA: <?= $slaText ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="text-right">
            <p class="text-xs text-textSub">Nominal Pengajuan</p>
            <p class="text-2xl font-extrabold text-textMain"><?= vao_fmt_rp((int)$prospek['nominal']) ?></p>
            <p class="text-xs text-textSub mt-1">Durasi proses: <span class="font-semibold text-textMain"><?= epl_total_duration($pl) ?></span></p>
        </div>
    </div>

    <!-- Progress bar -->
    <div class="mt-5">
        <div class="flex items-center justify-between text-xs mb-1.5">
            <span class="font-semibold text-textMain">Stage <?= $pl['current_stage'] ?>/7: <?= $stagesCfg[$pl['current_stage']]['label'] ?></span>
            <span class="text-textSub"><?= $progress ?>%</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
            <div class="<?= $pl['status'] === 'rejected' ? 'bg-rose-500' : ($pl['status'] === 'approved' ? 'bg-emerald-500' : 'bg-gradient-to-r from-primary to-secondary') ?> h-3 rounded-full" style="width: <?= $progress ?>%"></div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Timeline (kiri) -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-card border border-gray-100 p-6">
        <h3 class="text-base font-bold text-textMain mb-4">Timeline 7 Stages</h3>
        <div class="relative">
            <?php $isLast = false; foreach ($stagesCfg as $no => $cfg):
                $stData = $pl['stages'][$no] ?? null;
                $isDone = $stData && $stData['status'] === 'done';
                $isProgress = $stData && $stData['status'] === 'in_progress';
                $iconBg = $isDone ? 'bg-emerald-500' : ($isProgress ? 'bg-blue-500 animate-pulse' : 'bg-gray-300');
            ?>
                <div class="flex gap-4 <?= $no < 7 ? 'pb-5' : '' ?> relative">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-xl <?= $iconBg ?> flex items-center justify-center text-white shadow-md">
                            <?php if ($isDone): ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            <?php else: ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $cfg['icon'] ?></svg>
                            <?php endif; ?>
                        </div>
                        <?php if ($no < 7): ?><div class="w-0.5 flex-1 bg-gray-200 mt-2"></div><?php endif; ?>
                    </div>
                    <div class="flex-1 min-w-0 pb-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="text-sm font-bold text-textMain">Stage <?= $no ?>: <?= $cfg['label'] ?></p>
                                <p class="text-[11px] text-textSub mt-0.5">PIC: <?= $cfg['pic'] ?> · SLA <?= $cfg['sla_days'] ?> hari</p>
                            </div>
                            <?php if ($isDone): ?>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">DONE</span>
                            <?php elseif ($isProgress): ?>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">IN PROGRESS</span>
                            <?php endif; ?>
                        </div>
                        <?php if ($stData && !empty($stData['note'])): ?>
                            <div class="mt-2 p-3 bg-surface rounded-xl text-xs">
                                <p class="text-textMain font-medium"><?= htmlspecialchars($stData['note']) ?></p>
                                <p class="text-[10px] text-textSub mt-1.5">
                                    <?= htmlspecialchars($stData['by_user'] ?? '-') ?>
                                    <?php if (!empty($stData['finished_at'])): ?>
                                        · Selesai <?= htmlspecialchars($stData['finished_at']) ?>
                                    <?php elseif (!empty($stData['started_at'])): ?>
                                        · Mulai <?= htmlspecialchars($stData['started_at']) ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Info (kanan) -->
    <div class="space-y-4">
        <div class="bg-white rounded-2xl shadow-card border border-gray-100 p-5">
            <h3 class="text-sm font-bold text-textMain mb-3">Info Pipeline</h3>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between"><span class="text-textSub">AO</span><span class="font-semibold text-textMain"><?= $ao ? htmlspecialchars($ao['nama']) : '-' ?></span></div>
                <div class="flex justify-between"><span class="text-textSub">Cabang</span><span class="font-semibold text-textMain"><?= htmlspecialchars(vao_kantor_nama($kantors, $pl['kode_kantor'])) ?></span></div>
                <div class="flex justify-between"><span class="text-textSub">Mulai</span><span class="font-semibold text-textMain"><?= htmlspecialchars($pl['started_at']) ?></span></div>
                <div class="flex justify-between"><span class="text-textSub">SLA Due</span><span class="font-semibold <?= $slaStatus === 'overdue' ? 'text-red-600' : 'text-textMain' ?>"><?= htmlspecialchars($pl['sla_due_at']) ?></span></div>
                <div class="flex justify-between"><span class="text-textSub">Dokumen</span><span class="font-semibold text-textMain"><?= $pl['docs_count'] ?> file</span></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-card border border-gray-100 p-5">
            <h3 class="text-sm font-bold text-textMain mb-3">Detail Pengajuan</h3>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between gap-3"><span class="text-textSub">Tujuan</span><span class="font-semibold text-textMain text-right"><?= htmlspecialchars($prospek['tujuan'] ?? '-') ?></span></div>
                <div class="flex justify-between"><span class="text-textSub">Jenis Usaha</span><span class="font-semibold text-textMain"><?= htmlspecialchars($prospek['jenis_usaha'] ?? '-') ?></span></div>
                <div class="flex justify-between"><span class="text-textSub">Lama Usaha</span><span class="font-semibold text-textMain"><?= $prospek['lama_usaha_thn'] ?? '-' ?> tahun</span></div>
                <div class="flex justify-between"><span class="text-textSub">Omzet/Bulan</span><span class="font-semibold text-textMain"><?= !empty($prospek['omzet']) ? vao_fmt_rp((int)$prospek['omzet']) : '-' ?></span></div>
                <div class="flex justify-between gap-3"><span class="text-textSub">Jaminan</span><span class="font-semibold text-textMain text-right"><?= htmlspecialchars($prospek['jaminan'] ?? '-') ?></span></div>
            </div>
        </div>

        <?php if ($pl['status'] === 'in_progress'): ?>
            <a href="<?= BASE_URL ?>e-pipelane?tab=detail&id=<?= $pl['id'] ?>&action=update" class="block w-full bg-primary text-white font-bold text-sm rounded-xl px-4 py-3 text-center hover:bg-primary/90">
                Advance Stage →
            </a>
        <?php endif; ?>
    </div>
</div>
