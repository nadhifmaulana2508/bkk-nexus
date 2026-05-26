<?php
/**
 * E-Prospek - Shared Filter Bar (re-use struktur visit-ao tapi bawa $page=e-prospek).
 */
$filterCompact = $filterCompact ?? false;
?>
<div class="bg-white rounded-2xl shadow-card border border-gray-100 p-4 mb-4 animate-fade-in">
    <form method="GET" action="" class="<?= $filterCompact ? 'space-y-3' : 'grid grid-cols-1 md:grid-cols-4 gap-3 items-end' ?>">
        <input type="hidden" name="page" value="<?= htmlspecialchars($page ?? 'e-prospek') ?>">
        <input type="hidden" name="tab"  value="<?= htmlspecialchars($_GET['tab'] ?? '') ?>">

        <div>
            <label class="block text-xs font-semibold text-textSub uppercase tracking-wider mb-1.5">Kode Kantor</label>
            <select name="kode_kantor" class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
                <?php foreach ($kantors as $k): ?>
                    <option value="<?= $k['kode'] ?>" <?= $filterKodeKantor === $k['kode'] ? 'selected' : '' ?>>
                        <?= $k['kode'] ?> — <?= $k['nama'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-textSub uppercase tracking-wider mb-1.5">Closing Date</label>
            <input type="date" name="closing_date" value="<?= htmlspecialchars($filterClosingDate) ?>"
                   class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>

        <div>
            <label class="block text-xs font-semibold text-textSub uppercase tracking-wider mb-1.5">Harian Date</label>
            <input type="date" name="harian_date" value="<?= htmlspecialchars($filterHarianDate) ?>"
                   class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>

        <div class="<?= $filterCompact ? 'flex gap-2' : 'flex items-end gap-2' ?>">
            <button type="submit" class="flex-1 bg-primary text-white font-semibold text-sm rounded-xl px-4 py-2.5 hover:bg-primary/90 inline-flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Terapkan
            </button>
            <?php if (count($_GET) > 1): ?>
                <a href="<?= BASE_URL . ($page ?? 'e-prospek') . (isset($_GET['tab']) ? '?tab=' . $_GET['tab'] : '') ?>"
                   class="bg-gray-100 text-textSub font-semibold text-sm rounded-xl px-4 py-2.5 hover:bg-gray-200 inline-flex items-center justify-center" title="Reset">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </a>
            <?php endif; ?>
        </div>
    </form>

    <?php if ($filterKodeKantor !== '000'): ?>
        <div class="mt-3 pt-3 border-t border-gray-100 flex flex-wrap items-center gap-2 text-xs">
            <span class="text-textSub">Filter:</span>
            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg font-medium">
                Cabang: <?= htmlspecialchars(vao_kantor_nama($kantors, $filterKodeKantor)) ?>
            </span>
        </div>
    <?php endif; ?>
</div>
