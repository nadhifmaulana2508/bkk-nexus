<?php
/**
 * Desktop - Mapping Debitur ke AO Remedial
 * Untuk: Kabid Pemasaran / Kacab / Pincab.
 * 
 * Aturan:
 *   - AO Kredit: mapping OTOMATIS dari core (informasi only)
 *   - AO Remedial FE/BE: WAJIB mapping manual setiap awal bulan
 *
 * Filter sub: ?segment=fe|be (default fe)
 */

$segment = $_GET['segment'] ?? 'fe';
if (!in_array($segment, ['fe', 'be', 'kredit'])) $segment = 'fe';

// Bucket scope per segment
$bucketScope = match ($segment) {
    'kredit' => ['dpd_0', 'dpd_1_7', 'dpd_8_30', 'lunas'],
    'fe'     => ['dpd_31_60', 'dpd_61_90', 'dpd_91_180'],
    'be'     => ['dpd_181_plus', 'ph'],
};

$roleScope = match ($segment) {
    'kredit' => 'ao_kredit',
    'fe'     => 'ao_remedial_fe',
    'be'     => 'ao_remedial_be',
};

// Debitur dalam scope segment
$debiturInScope = array_values(array_filter($debiturFiltered, fn($d) => in_array($d['dpd_bucket'], $bucketScope, true)));

// AO yang valid untuk delegasi
$aoEligible = array_values(array_filter($aoFiltered, fn($a) => $a['role'] === $roleScope));

// Beban kerja per AO (jumlah debitur saat ini)
$beban = [];
foreach ($debiturInScope as $d) {
    $beban[$d['ao_id']] = ($beban[$d['ao_id']] ?? 0) + 1;
}

// Stats segment
$totalSegment    = count($debiturInScope);
$totalBakiSegment= array_sum(array_column($debiturInScope, 'baki_debet'));
?>

<!-- Tabs Sub Segment -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 mb-4 p-3 flex flex-wrap items-center gap-2">
    <span class="text-xs font-semibold text-textSub uppercase tracking-wider mr-2 px-2">Segment:</span>
    <?php foreach (['kredit'=>'AO Kredit (otomatis)','fe'=>'AO Remedial FE','be'=>'AO Remedial BE'] as $key => $lbl):
        $isActive = $segment === $key;
        $url = BASE_URL . 'visit-ao' . vao_query_with(['tab'=>'mapping','segment'=>$key]);
    ?>
        <a href="<?= $url ?>"
           class="px-4 py-2 rounded-xl text-xs font-semibold transition-colors
                  <?= $isActive ? 'bg-primary text-white shadow-sm' : 'bg-gray-100 text-textSub hover:bg-gray-200' ?>">
            <?= $lbl ?>
        </a>
    <?php endforeach; ?>
</div>

<?php if ($segment === 'kredit'): ?>
    <!-- Info: AO Kredit auto-mapped -->
    <div class="bg-gradient-to-br from-emerald-50 to-green-50 border border-emerald-200 rounded-2xl p-6 mb-4">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <p class="font-bold text-emerald-900">AO Kredit di-mapping OTOMATIS</p>
                <p class="text-sm text-emerald-700 mt-1 leading-relaxed">
                    Sistem akan menarik mapping AO Kredit langsung dari database core (kolom <code class="bg-white px-1 rounded">kode_ao</code>) setiap tanggal 1.
                    Tabel di bawah hanya untuk monitoring & koreksi manual jika diperlukan.
                </p>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- Warning: Wajib mapping manual -->
    <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-6 mb-4">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-amber-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/></svg>
            </div>
            <div class="flex-1">
                <p class="font-bold text-amber-900">⚠️ Wajib Mapping Manual Setiap Awal Bulan</p>
                <p class="text-sm text-amber-700 mt-1">
                    Untuk AO Remedial <?= strtoupper($segment) ?>, atasan (Kabid/Kacab/Pincab) wajib menentukan AO penanggung jawab tiap bulan.
                </p>
                <div class="flex items-center gap-2 mt-3">
                    <button class="bg-amber-500 text-white text-xs font-semibold px-3 py-2 rounded-lg hover:bg-amber-600 inline-flex items-center gap-1.5"
                            onclick="bulkAssign()">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Mapping Bulk
                    </button>
                    <button class="bg-white text-amber-700 text-xs font-semibold px-3 py-2 rounded-lg border border-amber-200 hover:bg-amber-100 inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                        Salin Mapping Bulan Lalu
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Stats summary -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <div class="bg-white rounded-2xl shadow-card p-5 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase tracking-wider">Total Debitur Segment</p>
        <p class="text-3xl font-extrabold text-textMain mt-2"><?= $totalSegment ?></p>
        <p class="text-xs text-textSub mt-1"><?= vao_fmt_rp((int)$totalBakiSegment) ?> baki debet</p>
    </div>
    <div class="bg-white rounded-2xl shadow-card p-5 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase tracking-wider">AO Tersedia</p>
        <p class="text-3xl font-extrabold text-textMain mt-2"><?= count($aoEligible) ?></p>
        <p class="text-xs text-textSub mt-1"><?= vao_role_label($roleScope) ?></p>
    </div>
    <div class="bg-white rounded-2xl shadow-card p-5 border border-gray-100">
        <p class="text-xs font-semibold text-textSub uppercase tracking-wider">Rata-rata Beban</p>
        <p class="text-3xl font-extrabold text-textMain mt-2"><?= count($aoEligible) > 0 ? round($totalSegment / count($aoEligible), 1) : 0 ?></p>
        <p class="text-xs text-textSub mt-1">debitur per AO</p>
    </div>
</div>

<!-- AO Workload (kartu beban kerja per AO) -->
<?php if (count($aoEligible) > 0): ?>
<div class="bg-white rounded-2xl shadow-card border border-gray-100 p-6 mb-4">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-bold text-textMain">Beban Kerja AO</h3>
        <span class="text-xs text-textSub"><?= vao_role_label($roleScope) ?></span>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <?php foreach ($aoEligible as $ao):
            $count = $beban[$ao['id']] ?? 0;
            $maxBeban = max(array_values($beban) ?: [1]);
            $pct = $maxBeban > 0 ? round($count / $maxBeban * 100) : 0;
        ?>
            <div class="bg-surface rounded-xl p-4 border border-gray-100">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-9 h-9 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        <?= $ao['inisial'] ?>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-textMain truncate"><?= htmlspecialchars($ao['nama']) ?></p>
                        <p class="text-[11px] text-textSub"><?= vao_kantor_nama($kantors, $ao['kode_kantor']) ?></p>
                    </div>
                </div>
                <p class="text-2xl font-extrabold text-primary"><?= $count ?> <span class="text-xs font-normal text-textSub">debitur</span></p>
                <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2 overflow-hidden">
                    <div class="bg-gradient-to-r from-primary to-secondary h-1.5 rounded-full" style="width: <?= $pct ?>%"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Tabel Debitur + Bulk Action -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
        <div>
            <h3 class="text-base font-bold text-textMain">Daftar Debitur Segment <?= strtoupper($segment) ?></h3>
            <p class="text-xs text-textSub mt-0.5">Centang baris untuk mapping bulk ke AO yang dipilih.</p>
        </div>
        <div class="flex items-center gap-2">
            <span id="selectedCount" class="text-xs text-textSub">0 dipilih</span>
            <select id="bulkAoSelect" class="bg-surface border border-gray-200 rounded-lg px-3 py-2 text-xs">
                <option value="">— Pilih AO target —</option>
                <?php foreach ($aoEligible as $ao): ?>
                    <option value="<?= $ao['id'] ?>"><?= htmlspecialchars($ao['nama']) ?> (<?= vao_kantor_nama($kantors, $ao['kode_kantor']) ?>)</option>
                <?php endforeach; ?>
            </select>
            <button onclick="bulkAssign()" class="bg-primary text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-primary/90 inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Apply
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-surface">
                <tr class="text-xs text-textSub uppercase tracking-wider">
                    <th class="text-left p-4 font-semibold w-10">
                        <input type="checkbox" id="checkAll" onchange="toggleAll(this.checked)" class="rounded border-gray-300 text-primary focus:ring-primary/40">
                    </th>
                    <th class="text-left p-4 font-semibold">Debitur</th>
                    <th class="text-left p-4 font-semibold">No. Rek</th>
                    <th class="text-left p-4 font-semibold">Cabang</th>
                    <th class="text-right p-4 font-semibold">Baki Debet</th>
                    <th class="text-center p-4 font-semibold">Bucket</th>
                    <th class="text-left p-4 font-semibold">AO Saat Ini</th>
                    <th class="text-center p-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($debiturInScope as $d):
                    $ao = vao_ao_by_id($aos, $d['ao_id']);
                ?>
                    <tr class="border-t border-gray-50 hover:bg-surface/40">
                        <td class="p-4">
                            <input type="checkbox" class="rowCheck rounded border-gray-300 text-primary focus:ring-primary/40" value="<?= $d['id'] ?>" onchange="updateSelectedCount()">
                        </td>
                        <td class="p-4">
                            <p class="font-semibold text-textMain"><?= htmlspecialchars($d['nama']) ?></p>
                            <p class="text-xs text-textSub mt-0.5"><?= htmlspecialchars($d['pemilik']) ?></p>
                        </td>
                        <td class="p-4 text-xs text-textSub"><?= $d['no_rek'] ?></td>
                        <td class="p-4 text-xs"><?= vao_kantor_nama($kantors, $d['kode_kantor']) ?></td>
                        <td class="p-4 text-right">
                            <span class="font-bold text-textMain"><?= vao_fmt_rp((int)$d['baki_debet']) ?></span>
                            <p class="text-xs text-textSub mt-0.5"><?= $d['dpd'] ?> hari</p>
                        </td>
                        <td class="p-4 text-center">
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full <?= vao_bucket_color($d['dpd_bucket']) ?>"><?= vao_bucket_label($d['dpd_bucket']) ?></span>
                        </td>
                        <td class="p-4">
                            <?php if ($ao): ?>
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white text-[10px] font-bold"><?= $ao['inisial'] ?></div>
                                    <div>
                                        <p class="text-xs font-semibold text-textMain"><?= htmlspecialchars($ao['nama']) ?></p>
                                        <p class="text-[10px] text-textSub"><?= vao_role_label($ao['role']) ?></p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <span class="text-xs text-amber-600 font-semibold">⚠ Belum dimapping</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-center">
                            <button onclick="quickAssign(<?= $d['id'] ?>)" class="text-xs bg-gray-100 text-textMain px-3 py-1.5 rounded-lg font-semibold hover:bg-gray-200">
                                Re-map
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($debiturInScope) === 0): ?>
                    <tr><td colspan="8" class="p-8 text-center text-sm text-textSub">
                        Tidak ada debitur dalam segment ini pada cabang terpilih.
                    </td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleAll(checked) {
    document.querySelectorAll('.rowCheck').forEach(c => c.checked = checked);
    updateSelectedCount();
}
function updateSelectedCount() {
    const n = document.querySelectorAll('.rowCheck:checked').length;
    document.getElementById('selectedCount').textContent = n + ' dipilih';
}
function bulkAssign() {
    const ids = Array.from(document.querySelectorAll('.rowCheck:checked')).map(c => c.value);
    const aoId = document.getElementById('bulkAoSelect').value;
    if (ids.length === 0) { alert('Pilih minimal 1 debitur dulu.'); return; }
    if (!aoId) { alert('Pilih AO target dulu.'); return; }
    // TODO: POST /api/debitur/mapping/manual { debitur_ids: [...], ao_id }
    console.log('BULK_ASSIGN', { debitur_ids: ids, ao_id: aoId });
    alert(`Dummy: ${ids.length} debitur akan di-assign ke AO #${aoId}.\n\nSaat backend siap, request akan POST ke /api/debitur/mapping/manual.`);
}
function quickAssign(debiturId) {
    const aoId = prompt('Masukkan ID AO target untuk debitur #' + debiturId + ':');
    if (!aoId) return;
    console.log('QUICK_ASSIGN', { debitur_id: debiturId, ao_id: aoId });
    alert(`Dummy: debitur #${debiturId} akan di-assign ke AO #${aoId}.`);
}
</script>
