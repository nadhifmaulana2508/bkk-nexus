<?php
/**
 * Mobile E-Prospek - Detail Prospek + Actions
 */

$id = (int)($_GET['id'] ?? 0);
$p = ep_prospek_by_id($prospeks, $id);

if (!$p) {
    echo '<div class="px-4 pt-6"><div class="bg-white rounded-2xl shadow-card p-6 text-center text-textSub">Prospek tidak ditemukan.</div></div>';
    return;
}

$ao = $p['ao_id'] ? vao_ao_by_id($aos, $p['ao_id']) : null;
$inputter = vao_ao_by_id($aos, $p['inputter_id']);

$nextAction = match ($p['status']) {
    'open'   => ['Submit ke Pipeline', 'submit'],
    'pending'=> ['Submit ke Pipeline', 'submit'],
    'submit' => $p['produk_jenis'] === 'kredit'
        ? ['Lihat Pipeline →', 'pipelane']
        : ['Tandai Realisasi', 'realisasi'],
    default  => null,
};
?>

<!-- Hero card -->
<div class="px-4 -mt-4 relative z-10 animate-fade-in">
    <div class="bg-white rounded-2xl shadow-card-hover p-5">
        <div class="flex items-start gap-3 mb-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white text-base font-bold flex-shrink-0">
                <?= strtoupper(substr($p['nama'], 0, 2)) ?>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-base font-bold text-textMain truncate"><?= htmlspecialchars($p['nama']) ?></p>
                <p class="text-xs text-textSub mt-0.5"><?= htmlspecialchars($p['pemilik']) ?> • <?= $p['telp'] ?></p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full <?= ep_status_color($p['status']) ?>">
                <?= ep_status_label($p['status']) ?>
            </span>
            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full <?= ep_produk_color($p['produk_jenis']) ?>">
                <?= ep_produk_label($p['produk_jenis']) ?> · <?= htmlspecialchars($p['produk_sub'] ?? '-') ?>
            </span>
            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full <?= ep_score_color($p['score']) ?>">
                <?= ep_score_label($p['score']) ?> <?= $p['score'] ?>%
            </span>
        </div>

        <div class="grid grid-cols-2 gap-2 mt-4">
            <div class="p-3 bg-surface rounded-xl">
                <p class="text-[10px] text-textSub font-semibold uppercase">Nominal</p>
                <p class="text-base font-bold text-textMain mt-1"><?= vao_fmt_rp((int)$p['nominal']) ?></p>
            </div>
            <div class="p-3 bg-surface rounded-xl">
                <p class="text-[10px] text-textSub font-semibold uppercase">Sumber</p>
                <p class="text-sm font-bold text-textMain mt-1"><?= ep_sumber_label($p['sumber']) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<?php if ($nextAction): ?>
<div class="px-4 mt-4 flex items-center gap-2 animate-fade-in">
    <?php if ($nextAction[1] === 'pipelane'): ?>
        <a href="<?= BASE_URL ?>e-pipelane?tab=detail&id=<?= $p['id'] ?>" class="flex-1 bg-gradient-to-r from-primary to-secondary text-white font-bold text-sm rounded-2xl px-4 py-3 shadow-lg shadow-primary/30 inline-flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <?= $nextAction[0] ?>
        </a>
    <?php elseif ($nextAction[1] === 'realisasi'): ?>
        <button onclick="markRealisasi(<?= $p['id'] ?>)" class="flex-1 bg-emerald-500 text-white font-bold text-sm rounded-2xl px-4 py-3 inline-flex items-center justify-center gap-2 hover:bg-emerald-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <?= $nextAction[0] ?>
        </button>
    <?php else: ?>
        <button onclick="submitProspek(<?= $p['id'] ?>)" class="flex-1 bg-primary text-white font-bold text-sm rounded-2xl px-4 py-3 inline-flex items-center justify-center gap-2 hover:bg-primary/90">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <?= $nextAction[0] ?>
        </button>
    <?php endif; ?>
    <a href="https://wa.me/62<?= ltrim($p['telp'], '0') ?>" target="_blank" class="bg-green-500 text-white font-bold text-sm rounded-2xl px-4 py-3 inline-flex items-center justify-center gap-2 hover:bg-green-600">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
    </a>
</div>
<?php endif; ?>

<!-- Detail sections -->
<div class="px-4 mt-4 space-y-3 pb-6 animate-fade-in">

    <!-- Lokasi -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4">
        <p class="text-xs font-bold text-textSub uppercase tracking-wider mb-2">Lokasi</p>
        <p class="text-sm text-textMain"><?= htmlspecialchars($p['alamat']) ?></p>
        <p class="text-xs text-textSub mt-1"><?= htmlspecialchars($p['kel']) ?>, <?= htmlspecialchars($p['kec']) ?>, <?= htmlspecialchars($p['kab']) ?></p>
        <?php if (!empty($p['lat']) && !empty($p['lng'])): ?>
            <a href="https://maps.google.com/?q=<?= $p['lat'] ?>,<?= $p['lng'] ?>" target="_blank"
               class="inline-flex items-center gap-1 text-xs text-primary font-semibold mt-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                Buka di Maps
            </a>
        <?php endif; ?>
    </div>

    <?php if ($p['produk_jenis'] === 'kredit' || $p['produk_jenis'] === 'aset'): ?>
    <!-- Detail Usaha -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4 space-y-2">
        <p class="text-xs font-bold text-textSub uppercase tracking-wider">Detail Usaha</p>
        <div class="flex justify-between text-xs"><span class="text-textSub">Jenis Usaha</span><span class="font-semibold text-textMain"><?= htmlspecialchars($p['jenis_usaha'] ?? '-') ?></span></div>
        <div class="flex justify-between text-xs"><span class="text-textSub">Lama Usaha</span><span class="font-semibold text-textMain"><?= $p['lama_usaha_thn'] ?? '-' ?> tahun</span></div>
        <div class="flex justify-between text-xs"><span class="text-textSub">Omzet/Bulan</span><span class="font-semibold text-textMain"><?= !empty($p['omzet']) ? vao_fmt_rp((int)$p['omzet']) : '-' ?></span></div>
        <div class="flex justify-between text-xs gap-3"><span class="text-textSub">Jaminan</span><span class="font-semibold text-textMain text-right"><?= htmlspecialchars($p['jaminan'] ?? '-') ?></span></div>
        <div class="flex justify-between text-xs"><span class="text-textSub">Tujuan</span><span class="font-semibold text-textMain text-right"><?= htmlspecialchars($p['tujuan'] ?? '-') ?></span></div>
    </div>
    <?php endif; ?>

    <!-- Delegasi & Inputter -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4 space-y-2">
        <p class="text-xs font-bold text-textSub uppercase tracking-wider">Penanganan</p>
        <div class="flex justify-between text-xs">
            <span class="text-textSub">Diinput oleh</span>
            <span class="font-semibold text-textMain"><?= $inputter ? htmlspecialchars($inputter['nama']) . ' (' . vao_role_label($inputter['role']) . ')' : '-' ?></span>
        </div>
        <div class="flex justify-between text-xs">
            <span class="text-textSub">AO Penanggung Jawab</span>
            <span class="font-semibold <?= $ao ? 'text-textMain' : 'text-amber-600' ?>"><?= $ao ? htmlspecialchars($ao['nama']) : '⚠ Belum delegasi' ?></span>
        </div>
        <div class="flex justify-between text-xs">
            <span class="text-textSub">Dibuat</span>
            <span class="font-semibold text-textMain"><?= htmlspecialchars($p['created_at']) ?></span>
        </div>
        <?php if (!empty($p['submitted_at'])): ?>
        <div class="flex justify-between text-xs">
            <span class="text-textSub">Submitted</span>
            <span class="font-semibold text-textMain"><?= htmlspecialchars($p['submitted_at']) ?></span>
        </div>
        <?php endif; ?>
        <?php if (!empty($p['realisasi_at'])): ?>
        <div class="flex justify-between text-xs">
            <span class="text-textSub">Realisasi</span>
            <span class="font-semibold text-emerald-700"><?= htmlspecialchars($p['realisasi_at']) ?></span>
        </div>
        <?php endif; ?>
        <?php if (!empty($p['rejected_at'])): ?>
        <div class="flex justify-between text-xs">
            <span class="text-textSub">Ditolak</span>
            <span class="font-semibold text-rose-700"><?= htmlspecialchars($p['rejected_at']) ?></span>
        </div>
        <?php if (!empty($p['reject_reason'])): ?>
            <p class="text-xs text-rose-700 bg-rose-50 border border-rose-100 rounded-lg p-2 mt-1"><?= htmlspecialchars($p['reject_reason']) ?></p>
        <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Catatan -->
    <?php if (!empty($p['catatan'])): ?>
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4">
        <p class="text-xs font-bold text-textSub uppercase tracking-wider mb-2">Catatan</p>
        <p class="text-sm text-textMain leading-relaxed"><?= nl2br(htmlspecialchars($p['catatan'])) ?></p>
    </div>
    <?php endif; ?>
</div>

<script>
function submitProspek(id) {
    if (!confirm('Submit prospek ini ke pipeline / proses lanjut?')) return;
    console.log('SUBMIT_PROSPEK', { id });
    alert('Dummy: prospek #' + id + ' akan di-submit. Saat backend siap, POST /api/prospek/' + id + '/submit');
}
function markRealisasi(id) {
    if (!confirm('Tandai prospek ini sebagai realisasi (untuk tabungan/deposito)?')) return;
    console.log('REALISASI_PROSPEK', { id });
    alert('Dummy: prospek #' + id + ' akan di-tandai realisasi. POST /api/prospek/' + id + '/realisasi');
}
</script>
