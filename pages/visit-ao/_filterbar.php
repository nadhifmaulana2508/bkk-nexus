<?php
/**
 * Visit AO - Shared Filter Bar
 * 
 * Pakai dengan: include __DIR__ . '/_filterbar.php';
 * Set var $filterCompact = true untuk versi mobile-friendly (lebih compact).
 * 
 * Form ini hanya GET, tidak mengubah backend. Saat backend siap, AO yang login
 * akan otomatis di-scope (kalau bukan kode_kantor 000, hanya bisa lihat cabangnya).
 */
$filterCompact = $filterCompact ?? false;
?>
<div class="bg-white rounded-2xl shadow-card border border-gray-100 p-4 mb-4 animate-fade-in">
    <form method="GET" action="" class="<?= $filterCompact ? 'space-y-3' : 'grid grid-cols-1 md:grid-cols-4 gap-3 items-end' ?>">
        <!-- Preserve current page & tab -->
        <input type="hidden" name="page" value="<?= htmlspecialchars($page ?? 'visit-ao') ?>">
        <input type="hidden" name="tab"  value="<?= htmlspecialchars($_GET['tab'] ?? '') ?>">

        <!-- Kode Kantor -->
        <div>
            <label class="block text-xs font-semibold text-textSub uppercase tracking-wider mb-1.5">
                <span class="inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Kode Kantor
                </span>
            </label>
            <select name="kode_kantor" class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-textMain focus:outline-none focus:ring-2 focus:ring-primary/40">
                <?php foreach ($kantors as $k): ?>
                    <option value="<?= $k['kode'] ?>" <?= $filterKodeKantor === $k['kode'] ? 'selected' : '' ?>>
                        <?= $k['kode'] ?> — <?= $k['nama'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Closing Date (akhir bulan / posisi snapshot) -->
        <div>
            <label class="block text-xs font-semibold text-textSub uppercase tracking-wider mb-1.5">
                <span class="inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Closing Date
                </span>
            </label>
            <input type="date" name="closing_date" value="<?= htmlspecialchars($filterClosingDate) ?>"
                   class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-textMain focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>

        <!-- Harian Date (tanggal kerja) -->
        <div>
            <label class="block text-xs font-semibold text-textSub uppercase tracking-wider mb-1.5">
                <span class="inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Harian Date
                </span>
            </label>
            <input type="date" name="harian_date" value="<?= htmlspecialchars($filterHarianDate) ?>"
                   class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-textMain focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>

        <!-- Submit -->
        <div class="<?= $filterCompact ? 'flex gap-2' : 'flex items-end gap-2' ?>">
            <button type="submit" class="flex-1 bg-primary text-white font-semibold text-sm rounded-xl px-4 py-2.5 hover:bg-primary/90 transition-colors shadow-sm inline-flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Terapkan
            </button>
            <?php if (count($_GET) > 1): // ada filter aktif selain page ?>
                <a href="<?= BASE_URL . ($page ?? 'visit-ao') . (isset($_GET['tab']) ? '?tab=' . $_GET['tab'] : '') ?>"
                   class="bg-gray-100 text-textSub font-semibold text-sm rounded-xl px-4 py-2.5 hover:bg-gray-200 transition-colors inline-flex items-center justify-center"
                   title="Reset filter">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </a>
            <?php endif; ?>
        </div>
    </form>

    <!-- Active Filter Chips -->
    <?php if ($filterKodeKantor !== '000'): ?>
        <div class="mt-3 pt-3 border-t border-gray-100 flex flex-wrap items-center gap-2 text-xs">
            <span class="text-textSub">Filter aktif:</span>
            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg font-medium">
                Cabang: <?= htmlspecialchars(vao_kantor_nama($kantors, $filterKodeKantor)) ?>
            </span>
        </div>
    <?php endif; ?>
</div>
