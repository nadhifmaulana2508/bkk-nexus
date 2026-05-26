<?php
/**
 * Desktop E-Prospek - Delegasi
 * Untuk Kabid Pemasaran / Kacab / Pincab.
 */

// List prospek yang perlu delegasi (open/pending tanpa AO atau dengan AO tapi inputter bukan AO)
$needDelegasi = array_values(array_filter($prospekFiltered, function ($p) {
    if (in_array($p['status'], ['realisasi','reject','submit'])) return false;
    if (empty($p['ao_id'])) return true;
    // Inputter bukan AO (CS / kabid / dll) — perlu delegasi konfirmasi
    return false;
}));

// AO yang tersedia per role
$aoKredit       = array_values(array_filter($aoFiltered, fn($a) => $a['role'] === 'ao_kredit'));
$aoRemedialFE   = array_values(array_filter($aoFiltered, fn($a) => $a['role'] === 'ao_remedial_fe'));
$aoRemedialBE   = array_values(array_filter($aoFiltered, fn($a) => $a['role'] === 'ao_remedial_be'));
?>

<!-- Aturan Delegasi -->
<div class="bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-200 rounded-2xl p-6 mb-4">
    <div class="flex items-start gap-4">
        <div class="w-12 h-12 bg-indigo-500 rounded-2xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="flex-1">
            <p class="font-bold text-indigo-900">Aturan Delegasi Otomatis</p>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-3 text-xs">
                <div class="p-3 bg-white rounded-xl border border-indigo-100">
                    <p class="font-semibold text-cyan-700">Tabungan</p>
                    <p class="text-textSub mt-1">→ AO Kredit (update langsung)</p>
                </div>
                <div class="p-3 bg-white rounded-xl border border-indigo-100">
                    <p class="font-semibold text-purple-700">Deposito</p>
                    <p class="text-textSub mt-1">→ AO Kredit (update langsung)</p>
                </div>
                <div class="p-3 bg-white rounded-xl border border-indigo-100">
                    <p class="font-semibold text-orange-700">Kredit</p>
                    <p class="text-textSub mt-1">→ AO Kredit → E-Pipelane SLA</p>
                </div>
                <div class="p-3 bg-white rounded-xl border border-indigo-100">
                    <p class="font-semibold text-pink-700">Aset</p>
                    <p class="text-textSub mt-1">→ AO Remedial (FE/BE)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats summary -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
    <div class="bg-white rounded-2xl shadow-card p-5 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase tracking-wider">Perlu Delegasi</p>
        <p class="text-3xl font-extrabold text-amber-700 mt-2"><?= count($needDelegasi) ?></p>
        <p class="text-[11px] text-textSub mt-0.5">Belum ada AO</p>
    </div>
    <div class="bg-white rounded-2xl shadow-card p-5 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase tracking-wider">AO Kredit</p>
        <p class="text-3xl font-extrabold text-orange-700 mt-2"><?= count($aoKredit) ?></p>
        <p class="text-[11px] text-textSub mt-0.5">Tersedia</p>
    </div>
    <div class="bg-white rounded-2xl shadow-card p-5 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase tracking-wider">AO Remedial FE</p>
        <p class="text-3xl font-extrabold text-pink-700 mt-2"><?= count($aoRemedialFE) ?></p>
        <p class="text-[11px] text-textSub mt-0.5">Tersedia</p>
    </div>
    <div class="bg-white rounded-2xl shadow-card p-5 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase tracking-wider">AO Remedial BE</p>
        <p class="text-3xl font-extrabold text-rose-700 mt-2"><?= count($aoRemedialBE) ?></p>
        <p class="text-[11px] text-textSub mt-0.5">Tersedia</p>
    </div>
</div>

<!-- Tabel prospek perlu delegasi -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
        <div>
            <h3 class="text-base font-bold text-textMain">Prospek Perlu Delegasi</h3>
            <p class="text-xs text-textSub mt-0.5">Tentukan AO penanggung jawab. Sistem akan suggest sesuai jenis produk.</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-surface">
                <tr class="text-xs text-textSub uppercase tracking-wider">
                    <th class="text-left p-4 font-semibold">Prospek</th>
                    <th class="text-left p-4 font-semibold">Inputter</th>
                    <th class="text-center p-4 font-semibold">Produk</th>
                    <th class="text-right p-4 font-semibold">Nominal</th>
                    <th class="text-center p-4 font-semibold">Score</th>
                    <th class="text-left p-4 font-semibold">Suggested AO</th>
                    <th class="text-center p-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($needDelegasi as $p):
                    $inputter = vao_ao_by_id($aos, $p['inputter_id']);
                    // Suggested AO based on product
                    $suggestPool = match ($p['produk_jenis']) {
                        'tabungan','deposito','kredit' => $aoKredit,
                        'aset'                          => array_merge($aoRemedialFE, $aoRemedialBE),
                        default                         => $aoKredit,
                    };
                    // Filter by kantor (kalau ada cabang)
                    $suggestPool = array_values(array_filter($suggestPool, fn($a) => $a['kode_kantor'] === $p['kode_kantor']));
                    $suggestion = $suggestPool[0] ?? null;
                ?>
                    <tr class="border-t border-gray-50 hover:bg-surface/40">
                        <td class="p-4">
                            <p class="font-semibold text-textMain"><?= htmlspecialchars($p['nama']) ?></p>
                            <p class="text-[11px] text-textSub mt-0.5"><?= htmlspecialchars($p['pemilik']) ?> • <?= vao_kantor_nama($kantors, $p['kode_kantor']) ?></p>
                        </td>
                        <td class="p-4 text-xs">
                            <?= $inputter ? htmlspecialchars($inputter['nama']) : '-' ?>
                            <p class="text-[10px] text-textSub mt-0.5"><?= $inputter ? vao_role_label($inputter['role']) : '' ?></p>
                        </td>
                        <td class="p-4 text-center">
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full <?= ep_produk_color($p['produk_jenis']) ?>">
                                <?= ep_produk_label($p['produk_jenis']) ?>
                            </span>
                        </td>
                        <td class="p-4 text-right font-bold text-textMain"><?= vao_fmt_rp((int)$p['nominal']) ?></td>
                        <td class="p-4 text-center">
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full <?= ep_score_color($p['score']) ?>">
                                <?= ep_score_label($p['score']) ?>
                            </span>
                        </td>
                        <td class="p-4">
                            <select class="aoSelect-<?= $p['id'] ?> w-full bg-surface border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs">
                                <option value="">— Pilih AO —</option>
                                <?php foreach ($suggestPool as $ao): ?>
                                    <option value="<?= $ao['id'] ?>" <?= $suggestion && $ao['id'] === $suggestion['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($ao['nama']) ?> (<?= vao_role_label($ao['role']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="p-4 text-center">
                            <button onclick="delegate(<?= $p['id'] ?>)" class="text-xs bg-primary text-white px-3 py-1.5 rounded-lg font-semibold hover:bg-primary/90">
                                Delegasi
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($needDelegasi) === 0): ?>
                    <tr><td colspan="7" class="p-8 text-center text-sm text-textSub">🎉 Semua prospek sudah didelegasikan ke AO.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function delegate(prospekId) {
    const sel = document.querySelector('.aoSelect-' + prospekId);
    const aoId = sel?.value;
    if (!aoId) { alert('Pilih AO target dulu.'); return; }
    console.log('DELEGATE_PROSPEK', { prospek_id: prospekId, ao_id: aoId });
    alert('Dummy: prospek #' + prospekId + ' didelegasikan ke AO #' + aoId + '.\nSaat backend siap, POST /api/prospek/' + prospekId + '/delegate');
}
</script>
