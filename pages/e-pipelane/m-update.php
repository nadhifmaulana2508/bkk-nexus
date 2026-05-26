<?php
/**
 * Mobile E-Pipelane - Update / Advance Stage
 */

$id = (int)($_GET['id'] ?? 0);
$pl = epl_pipelane_by_id($pipelanes, $id);

if (!$pl) {
    echo '<div class="px-4 pt-6"><div class="bg-white rounded-2xl shadow-card p-6 text-center text-textSub">Pipeline tidak ditemukan.</div></div>';
    return;
}

$prospek = ep_prospek_by_id($prospeks, $pl['prospek_id']);
$currentStageInfo = $stagesCfg[$pl['current_stage']];
$nextStage = $pl['current_stage'] + 1;
$nextStageInfo = $stagesCfg[$nextStage] ?? null;
?>

<!-- Header -->
<div class="px-4 -mt-4 relative z-10 animate-fade-in">
    <div class="bg-white rounded-2xl shadow-card-hover p-5">
        <div class="flex items-start gap-3 mb-3">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $currentStageInfo['icon'] ?></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-base font-bold text-textMain truncate"><?= htmlspecialchars($prospek['nama']) ?></p>
                <p class="text-xs text-textSub mt-0.5">Stage <?= $pl['current_stage'] ?>: <?= $currentStageInfo['label'] ?></p>
            </div>
        </div>
        <?php if ($nextStageInfo): ?>
        <div class="p-3 bg-indigo-50 border border-indigo-100 rounded-xl">
            <p class="text-xs text-indigo-700">Setelah update, pipeline akan advance ke:</p>
            <p class="text-sm font-bold text-indigo-900 mt-1">→ Stage <?= $nextStage ?>: <?= $nextStageInfo['label'] ?> <span class="text-xs font-normal opacity-70">(SLA <?= $nextStageInfo['sla_days'] ?> hari)</span></p>
        </div>
        <?php endif; ?>
    </div>
</div>

<form id="formUpdateStage" class="px-4 mt-4 space-y-4 pb-6 animate-fade-in" onsubmit="updateStage(event)">

    <!-- Action -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4">
        <label class="block text-xs font-bold text-textSub uppercase tracking-wider mb-3">Aksi</label>
        <div class="space-y-2">
            <label class="cursor-pointer block">
                <input type="radio" name="action" value="advance" class="peer sr-only" checked>
                <div class="border-2 rounded-xl px-4 py-3 text-sm font-semibold transition-all bg-emerald-50 border-emerald-200 text-emerald-700 peer-checked:ring-2 peer-checked:ring-primary">
                    ✓ Selesaikan stage ini & advance ke berikutnya
                </div>
            </label>
            <label class="cursor-pointer block">
                <input type="radio" name="action" value="hold" class="peer sr-only">
                <div class="border-2 rounded-xl px-4 py-3 text-sm font-semibold transition-all bg-amber-50 border-amber-200 text-amber-700 peer-checked:ring-2 peer-checked:ring-primary">
                    ⏸ Hold (tidak advance, tambah catatan)
                </div>
            </label>
            <?php if ($pl['current_stage'] >= 5): ?>
                <label class="cursor-pointer block">
                    <input type="radio" name="action" value="reject" class="peer sr-only">
                    <div class="border-2 rounded-xl px-4 py-3 text-sm font-semibold transition-all bg-rose-50 border-rose-200 text-rose-700 peer-checked:ring-2 peer-checked:ring-primary">
                        ✗ Tolak / Reject (komite menolak)
                    </div>
                </label>
            <?php endif; ?>
            <?php if ($pl['current_stage'] === 6): ?>
                <label class="cursor-pointer block">
                    <input type="radio" name="action" value="approve" class="peer sr-only">
                    <div class="border-2 rounded-xl px-4 py-3 text-sm font-semibold transition-all bg-blue-50 border-blue-200 text-blue-700 peer-checked:ring-2 peer-checked:ring-primary">
                        🎉 Akad selesai & realisasi (final approve)
                    </div>
                </label>
            <?php endif; ?>
        </div>
    </div>

    <!-- Catatan -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4">
        <label class="block text-xs font-bold text-textSub uppercase tracking-wider mb-2">Catatan Stage</label>
        <textarea name="note" rows="4" required placeholder="Tulis hasil/temuan stage ini, atau alasan hold/reject…"
                  class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40"></textarea>
    </div>

    <!-- Upload Dokumen -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4">
        <label class="block text-xs font-bold text-textSub uppercase tracking-wider mb-2">Lampiran (opsional)</label>
        <label for="stageDoc" class="block w-full bg-surface border-2 border-dashed border-gray-300 rounded-xl py-4 text-center cursor-pointer hover:border-primary">
            <svg class="w-8 h-8 mx-auto text-gray-400 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
            <span class="text-xs font-semibold text-textMain">Upload dokumen (PDF/JPG/PNG)</span>
        </label>
        <input type="file" id="stageDoc" name="dokumen[]" multiple accept=".pdf,image/*" class="hidden" onchange="onDocChange(event)">
        <p id="docList" class="hidden mt-2 text-xs text-textSub"></p>
    </div>

    <!-- Submit -->
    <button type="submit" class="w-full bg-gradient-to-r from-primary to-secondary text-white font-bold text-sm rounded-2xl px-4 py-3.5 shadow-lg shadow-primary/30 inline-flex items-center justify-center gap-2 hover:opacity-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        Simpan Update
    </button>
</form>

<script>
function onDocChange(e) {
    const list = document.getElementById('docList');
    const files = Array.from(e.target.files).map(f => f.name).join(', ');
    list.textContent = files ? '📎 ' + files : '';
    list.classList.toggle('hidden', !files);
}

function updateStage(e) {
    e.preventDefault();
    const data = new FormData(e.target);
    data.append('pipelane_id', '<?= $pl['id'] ?>');
    data.append('current_stage', '<?= $pl['current_stage'] ?>');
    console.log('STAGE_UPDATE', Object.fromEntries(data.entries()));
    alert('Dummy: stage akan di-update.\nSaat backend siap, POST /api/pipelane/<?= $pl['id'] ?>/advance');
    setTimeout(() => { window.location = '<?= BASE_URL ?>e-pipelane?tab=detail&id=<?= $pl['id'] ?>'; }, 800);
}
</script>
