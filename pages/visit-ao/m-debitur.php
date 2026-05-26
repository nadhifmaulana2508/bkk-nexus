<?php
/**
 * Mobile - List Debitur Kelolaan AO
 * Filter bucket DPD via query string ?bucket=
 * Search by nama via ?q=
 */

$filterBucket = $_GET['bucket'] ?? 'all';
$query        = trim($_GET['q'] ?? '');

// Apply filters
$listed = $myDebiturs;
if ($filterBucket !== 'all') {
    $listed = array_values(array_filter($listed, fn($d) => $d['dpd_bucket'] === $filterBucket));
}
if ($query !== '') {
    $listed = array_values(array_filter($listed, fn($d) =>
        stripos($d['nama'], $query) !== false ||
        stripos($d['pemilik'], $query) !== false ||
        stripos($d['no_rek'], $query) !== false
    ));
}

// Bucket counts (untuk tab atas)
$bucketCounts = ['all' => count($myDebiturs)];
foreach ($myDebiturs as $d) {
    $b = $d['dpd_bucket'];
    $bucketCounts[$b] = ($bucketCounts[$b] ?? 0) + 1;
}

// Tampilkan bucket sesuai role AO
$shownBuckets = match ($currentAo['role'] ?? '') {
    'ao_kredit'      => ['dpd_0', 'dpd_1_7', 'dpd_8_30', 'lunas'],
    'ao_remedial_fe' => ['dpd_31_60', 'dpd_61_90', 'dpd_91_180'],
    'ao_remedial_be' => ['dpd_181_plus', 'ph'],
    default          => ['dpd_0', 'dpd_1_7', 'dpd_8_30', 'dpd_31_60', 'dpd_61_90', 'dpd_181_plus'],
};
?>

<!-- Header Section -->
<div class="px-4 -mt-4 relative z-10 animate-fade-in">
    <div class="bg-white rounded-2xl shadow-card-hover p-4">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="text-sm font-bold text-textMain">Debitur Kelolaan</p>
                <p class="text-xs text-textSub mt-0.5"><?= count($myDebiturs) ?> debitur • <?= vao_role_label($currentAo['role']) ?></p>
            </div>
            <a href="<?= BASE_URL ?>visit-ao?tab=form" class="bg-primary text-white text-xs font-semibold px-3 py-2 rounded-xl inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Visit
            </a>
        </div>

        <!-- Search -->
        <form method="GET" action="" class="relative">
            <input type="hidden" name="page" value="visit-ao">
            <input type="hidden" name="tab" value="debitur">
            <?php if ($filterBucket !== 'all'): ?><input type="hidden" name="bucket" value="<?= htmlspecialchars($filterBucket) ?>"><?php endif; ?>
            <input type="search" name="q" value="<?= htmlspecialchars($query) ?>"
                   placeholder="Cari nama, pemilik, atau no rek…"
                   class="w-full bg-surface border border-gray-200 rounded-xl pl-10 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </form>
    </div>
</div>

<!-- Bucket Filter Tabs (horizontal scroll) -->
<div class="px-4 mt-4 animate-fade-in">
    <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide pb-1">
        <a href="<?= BASE_URL ?>visit-ao?tab=debitur"
           class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold transition-colors
                  <?= $filterBucket === 'all' ? 'bg-primary text-white shadow-sm' : 'bg-white text-textSub border border-gray-200' ?>">
            Semua
            <span class="<?= $filterBucket === 'all' ? 'bg-white/25' : 'bg-gray-100' ?> px-1.5 py-0.5 rounded-full text-[10px]"><?= $bucketCounts['all'] ?></span>
        </a>
        <?php foreach ($shownBuckets as $b):
            $count = $bucketCounts[$b] ?? 0;
            $isActive = $filterBucket === $b;
        ?>
            <a href="<?= BASE_URL ?>visit-ao?tab=debitur&bucket=<?= $b ?>"
               class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold transition-colors
                      <?= $isActive ? 'bg-primary text-white shadow-sm' : 'bg-white text-textSub border border-gray-200' ?>">
                <?= vao_bucket_label($b) ?>
                <span class="<?= $isActive ? 'bg-white/25' : 'bg-gray-100' ?> px-1.5 py-0.5 rounded-full text-[10px]"><?= $count ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- Debitur List -->
<div class="px-4 mt-4 pb-6 animate-fade-in">
    <?php if (count($listed) === 0): ?>
        <div class="bg-white rounded-2xl shadow-card p-6 border border-gray-50 text-center">
            <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm font-semibold text-textMain">Tidak ada debitur</p>
            <p class="text-xs text-textSub mt-1">Coba ubah filter atau pencarian.</p>
        </div>
    <?php else: ?>
        <div class="space-y-2.5">
            <?php foreach ($listed as $d):
                $movement = $d['movement'] ?? 'stay';
            ?>
                <div class="bg-white rounded-2xl shadow-card p-4 border border-gray-50 hover:shadow-card-hover transition-shadow">
                    <div class="flex items-start gap-3">
                        <!-- Avatar inisial -->
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                            <?= strtoupper(substr($d['nama'], 0, 2)) ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-textMain truncate"><?= htmlspecialchars($d['nama']) ?></p>
                                    <p class="text-xs text-textSub mt-0.5 truncate">
                                        <?= htmlspecialchars($d['pemilik']) ?> • <?= $d['no_rek'] ?>
                                    </p>
                                </div>
                                <span class="text-[11px] font-bold px-2 py-0.5 rounded-full <?= vao_bucket_color($d['dpd_bucket']) ?> flex-shrink-0">
                                    <?= vao_bucket_label($d['dpd_bucket']) ?>
                                </span>
                            </div>

                            <div class="flex items-center gap-3 mt-2 text-xs text-textSub">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    <?= vao_fmt_rp((int)$d['baki_debet']) ?>
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <?= $d['dpd'] ?> hari
                                </span>
                            </div>

                            <div class="flex items-center gap-2 mt-2.5">
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full <?= vao_movement_color($movement) ?>">
                                    <?= vao_movement_label($movement) ?>
                                    <?php if ($movement === 'improve' && $d['bucket_prev']): ?>
                                        : <?= vao_bucket_label($d['bucket_prev']) ?> → <?= vao_bucket_label($d['dpd_bucket']) ?>
                                    <?php elseif ($movement === 'worse' && $d['bucket_prev']): ?>
                                        : <?= vao_bucket_label($d['bucket_prev']) ?> → <?= vao_bucket_label($d['dpd_bucket']) ?>
                                    <?php endif; ?>
                                </span>
                            </div>

                            <div class="flex items-center gap-2 mt-3">
                                <a href="<?= BASE_URL ?>visit-ao?tab=form&debitur_id=<?= $d['id'] ?>"
                                   class="flex-1 text-xs bg-primary text-white px-3 py-2 rounded-lg font-semibold text-center inline-flex items-center justify-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Visit
                                </a>
                                <a href="https://wa.me/62<?= ltrim($d['telp'], '0') ?>" target="_blank"
                                   class="text-xs bg-green-500 text-white px-3 py-2 rounded-lg font-semibold inline-flex items-center justify-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/></svg>
                                </a>
                                <a href="https://maps.google.com/?q=<?= $d['lat'] ?>,<?= $d['lng'] ?>" target="_blank"
                                   class="text-xs bg-gray-100 text-textMain px-3 py-2 rounded-lg font-semibold inline-flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
