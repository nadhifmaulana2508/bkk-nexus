<?php
/**
 * Mobile E-Pipelane - Detail timeline 7 stages
 */

$id = (int)($_GET['id'] ?? 0);
// Boleh juga dipanggil dengan ?id=prospek_id
$pl = epl_pipelane_by_id($pipelanes, $id) ?? epl_pipelane_by_prospek($pipelanes, $id);

if (!$pl) {
    echo '<div class="px-4 pt-6"><div class="bg-white rounded-2xl shadow-card p-6 text-center text-textSub">Pipeline tidak ditemukan.</div></div>';
    return;
}

$prospek = ep_prospek_by_id($prospeks, $pl['prospek_id']);
$ao = vao_ao_by_id($aos, $pl['ao_id']);
[$slaStatus, $slaText] = epl_sla_status($pl);
$progress = round($pl['current_stage'] / 7 * 100);
?>

<!-- Hero card -->
<div class="px-4 -mt-4 relative z-10 animate-fade-in">
    <div class="bg-white rounded-2xl shadow-card-hover p-5">
        <div class="flex items-start gap-3 mb-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-400 to-orange-500 flex items-center justify-center text-white text-base font-bold flex-shrink-0">
                <?= strtoupper(substr($prospek['nama'], 0, 2)) ?>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-base font-bold text-textMain truncate"><?= htmlspecialchars($prospek['nama']) ?></p>
                <p class="text-xs text-textSub mt-0.5"><?= htmlspecialchars($prospek['pemilik']) ?> · <?= htmlspecialchars($prospek['produk_sub'] ?? 'Kredit') ?></p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap mb-4">
            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full <?= epl_pipelane_status_color($pl['status']) ?>">
                <?= epl_pipelane_status_label($pl['status']) ?>
            </span>
            <?php if ($pl['priority'] === 'high'): ?>
                <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700">⚡ High Priority</span>
            <?php endif; ?>
            <?php if ($pl['status'] === 'in_progress'): ?>
                <span class="text-[11px] font-bold px-2 py-0.5 rounded-full
                    <?= $slaStatus === 'overdue' ? 'bg-red-100 text-red-700' : ($slaStatus === 'warning' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') ?>">
                    SLA: <?= $slaText ?>
                </span>
            <?php endif; ?>
        </div>

        <div class="grid grid-cols-3 gap-2">
            <div class="p-3 bg-surface rounded-xl text-center">
                <p class="text-[10px] text-textSub font-semibold uppercase">Nominal</p>
                <p class="text-sm font-bold text-textMain mt-1"><?= vao_fmt_rp((int)$prospek['nominal']) ?></p>
            </div>
            <div class="p-3 bg-surface rounded-xl text-center">
                <p class="text-[10px] text-textSub font-semibold uppercase">Stage</p>
                <p class="text-sm font-bold text-textMain mt-1"><?= $pl['current_stage'] ?>/7</p>
            </div>
            <div class="p-3 bg-surface rounded-xl text-center">
                <p class="text-[10px] text-textSub font-semibold uppercase">Durasi</p>
                <p class="text-sm font-bold text-textMain mt-1"><?= epl_total_duration($pl) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Action -->
<?php if ($pl['status'] === 'in_progress'): ?>
<div class="px-4 mt-4 animate-fade-in">
    <a href="<?= BASE_URL ?>e-pipelane?tab=update&id=<?= $pl['id'] ?>" class="block w-full bg-gradient-to-r from-primary to-secondary text-white font-bold text-sm rounded-2xl px-4 py-3.5 shadow-lg shadow-primary/30 inline-flex items-center justify-center gap-2 hover:opacity-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        Update / Advance Stage
    </a>
</div>
<?php endif; ?>

<!-- Timeline 7 Stages -->
<div class="px-4 mt-5 animate-fade-in">
    <h2 class="text-base font-bold text-textMain mb-3">Timeline SLA</h2>
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4">
        <?php foreach ($stagesCfg as $no => $cfg):
            $stData = $pl['stages'][$no] ?? null;
            $isDone = $stData && $stData['status'] === 'done';
            $isProgress = $stData && $stData['status'] === 'in_progress';
            $isUpcoming = !$stData;

            $iconBg = $isDone ? 'bg-emerald-500' : ($isProgress ? 'bg-blue-500 animate-pulse' : 'bg-gray-300');
            $textColor = $isUpcoming ? 'text-textSub' : 'text-textMain';
        ?>
            <div class="flex gap-3 <?= $no < 7 ? 'pb-4 border-l-2 border-gray-100 ml-4' : '' ?> relative">
                <div class="absolute -left-[9px] w-4 h-4 rounded-full <?= $iconBg ?> ring-4 ring-white flex items-center justify-center">
                    <?php if ($isDone): ?>
                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    <?php endif; ?>
                </div>
                <div class="ml-6 flex-1 min-w-0 pt-0.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-sm font-bold <?= $textColor ?>">Stage <?= $no ?>: <?= $cfg['label'] ?></p>
                            <p class="text-[11px] text-textSub mt-0.5">PIC: <?= $cfg['pic'] ?> · SLA <?= $cfg['sla_days'] ?> hari</p>
                        </div>
                        <?php if ($isDone): ?>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 flex-shrink-0">DONE</span>
                        <?php elseif ($isProgress): ?>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 flex-shrink-0">PROSES</span>
                        <?php endif; ?>
                    </div>
                    <?php if ($stData && !empty($stData['note'])): ?>
                        <div class="mt-2 p-2.5 bg-surface rounded-lg text-xs">
                            <p class="text-textMain"><?= htmlspecialchars($stData['note']) ?></p>
                            <p class="text-[10px] text-textSub mt-1">
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

<!-- Info Tambahan -->
<div class="px-4 mt-4 pb-6 animate-fade-in space-y-3">
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4 space-y-2">
        <p class="text-xs font-bold text-textSub uppercase tracking-wider">Info Tambahan</p>
        <div class="flex justify-between text-xs"><span class="text-textSub">AO</span><span class="font-semibold text-textMain"><?= $ao ? htmlspecialchars($ao['nama']) : '-' ?></span></div>
        <div class="flex justify-between text-xs"><span class="text-textSub">Cabang</span><span class="font-semibold text-textMain"><?= htmlspecialchars(vao_kantor_nama($kantors, $pl['kode_kantor'])) ?></span></div>
        <div class="flex justify-between text-xs"><span class="text-textSub">Mulai Pipeline</span><span class="font-semibold text-textMain"><?= htmlspecialchars($pl['started_at']) ?></span></div>
        <div class="flex justify-between text-xs"><span class="text-textSub">SLA Due</span><span class="font-semibold <?= $slaStatus === 'overdue' ? 'text-red-600' : 'text-textMain' ?>"><?= htmlspecialchars($pl['sla_due_at']) ?></span></div>
        <div class="flex justify-between text-xs"><span class="text-textSub">Dokumen</span><span class="font-semibold text-textMain"><?= $pl['docs_count'] ?> file</span></div>
    </div>
</div>
