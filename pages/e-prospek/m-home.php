<?php
/**
 * Mobile E-Prospek - Home (AO dashboard)
 */

$myStats = [
    'open'      => count(array_filter($myProspeks, fn($p) => $p['status'] === 'open')),
    'pending'   => count(array_filter($myProspeks, fn($p) => $p['status'] === 'pending')),
    'submit'    => count(array_filter($myProspeks, fn($p) => $p['status'] === 'submit')),
    'realisasi' => count(array_filter($myProspeks, fn($p) => $p['status'] === 'realisasi')),
    'reject'    => count(array_filter($myProspeks, fn($p) => $p['status'] === 'reject')),
];

$myHotLeads  = array_values(array_filter($myProspeks, fn($p) => $p['score'] >= 80 && in_array($p['status'], ['open','pending','submit'])));
$myActive    = array_values(array_filter($myProspeks, fn($p) => in_array($p['status'], ['open','pending'])));
$totalNominal = array_sum(array_column($myProspeks, 'nominal'));
?>

<!-- Stats Card -->
<div class="px-4 -mt-4 relative z-10 animate-fade-in">
    <div class="bg-white rounded-2xl shadow-card-hover p-5">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="text-xs font-semibold text-textSub uppercase tracking-wider">Prospek Saya</p>
                <p class="text-xs text-textSub mt-0.5"><?= htmlspecialchars($currentInputter['nama'] ?? '-') ?> • <?= vao_role_label($currentInputterRole) ?></p>
            </div>
            <div class="text-right">
                <p class="text-xs text-textSub">Total Nominal</p>
                <p class="text-base font-bold text-primary"><?= vao_fmt_rp($totalNominal) ?></p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-2">
            <div class="text-center p-3 bg-surface rounded-xl">
                <p class="text-xl font-bold text-textMain"><?= count($myProspeks) ?></p>
                <p class="text-[11px] text-textSub mt-0.5">Total</p>
            </div>
            <div class="text-center p-3 bg-blue-50 rounded-xl">
                <p class="text-xl font-bold text-blue-700"><?= $myStats['submit'] ?></p>
                <p class="text-[11px] text-blue-600/80 mt-0.5">Submitted</p>
            </div>
            <div class="text-center p-3 bg-emerald-50 rounded-xl">
                <p class="text-xl font-bold text-emerald-700"><?= $myStats['realisasi'] ?></p>
                <p class="text-[11px] text-emerald-600/80 mt-0.5">Realisasi</p>
            </div>
        </div>
    </div>
</div>

<!-- Aksi Cepat -->
<div class="px-4 mt-6 animate-fade-in">
    <h2 class="text-base font-bold text-textMain mb-3">Aksi Cepat</h2>
    <div class="grid grid-cols-4 gap-3">
        <a href="<?= BASE_URL ?>e-prospek?tab=form" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Tambah</span>
        </a>
        <a href="<?= BASE_URL ?>e-prospek?tab=list" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2">
                <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Semua</span>
        </a>
        <a href="<?= BASE_URL ?>e-prospek?tab=list&score=hot" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2 relative">
                <svg class="w-6 h-6 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                <?php if (count($myHotLeads) > 0): ?>
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-danger text-white text-[10px] font-bold rounded-full flex items-center justify-center"><?= count($myHotLeads) ?></span>
                <?php endif; ?>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Hot Leads</span>
        </a>
        <a href="<?= BASE_URL ?>e-pipelane" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2">
                <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Pipeline</span>
        </a>
    </div>
</div>

<!-- Funnel Status -->
<div class="px-4 mt-6 animate-fade-in">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-base font-bold text-textMain">Funnel Konversi</h2>
        <a href="<?= BASE_URL ?>e-prospek?tab=list" class="text-xs text-primary font-semibold">Detail →</a>
    </div>
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4 space-y-3">
        <?php
        $cfg = [
            ['key' => 'open',      'label' => 'Open',          'cls' => 'bg-gray-400',     'text' => 'text-gray-700'],
            ['key' => 'pending',   'label' => 'Pending',       'cls' => 'bg-amber-500',    'text' => 'text-amber-700'],
            ['key' => 'submit',    'label' => 'Submitted',     'cls' => 'bg-blue-500',     'text' => 'text-blue-700'],
            ['key' => 'realisasi', 'label' => 'Realisasi',     'cls' => 'bg-emerald-500',  'text' => 'text-emerald-700'],
            ['key' => 'reject',    'label' => 'Ditolak',       'cls' => 'bg-rose-500',     'text' => 'text-rose-700'],
        ];
        $maxVal = max(1, max($myStats));
        foreach ($cfg as $c):
            $val = $myStats[$c['key']];
            $pct = $maxVal > 0 ? round($val / $maxVal * 100) : 0;
        ?>
            <div>
                <div class="flex items-center justify-between text-xs mb-1.5">
                    <span class="font-semibold text-textMain inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full <?= $c['cls'] ?>"></span><?= $c['label'] ?>
                    </span>
                    <span class="font-bold <?= $c['text'] ?>"><?= $val ?></span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                    <div class="<?= $c['cls'] ?> h-2 rounded-full" style="width: <?= $pct ?>%"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Hot Leads -->
<?php if (count($myHotLeads) > 0): ?>
<div class="px-4 mt-6 animate-fade-in">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-base font-bold text-textMain">🔥 Hot Leads</h2>
        <span class="text-xs text-textSub"><?= count($myHotLeads) ?> prospek</span>
    </div>
    <div class="space-y-2.5">
        <?php foreach (array_slice($myHotLeads, 0, 4) as $p): ?>
            <a href="<?= BASE_URL ?>e-prospek?tab=detail&id=<?= $p['id'] ?>" class="block bg-white rounded-2xl shadow-card p-4 border border-red-100 border-l-4 border-l-red-500">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-rose-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                        <?= strtoupper(substr($p['nama'], 0, 2)) ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-textMain truncate"><?= htmlspecialchars($p['nama']) ?></p>
                        <p class="text-xs text-textSub mt-0.5"><?= htmlspecialchars($p['pemilik']) ?></p>
                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full <?= ep_score_color($p['score']) ?>">
                                <?= ep_score_label($p['score']) ?> <?= $p['score'] ?>%
                            </span>
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full <?= ep_produk_color($p['produk_jenis']) ?>">
                                <?= ep_produk_label($p['produk_jenis']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-sm font-bold text-textMain"><?= vao_fmt_rp((int)$p['nominal']) ?></p>
                        <p class="text-[10px] text-textSub mt-0.5"><?= ep_status_label($p['status']) ?></p>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Aktif perlu di-follow up -->
<div class="px-4 mt-6 pb-6 animate-fade-in">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-base font-bold text-textMain">Perlu Follow Up</h2>
        <span class="text-xs text-textSub"><?= count($myActive) ?> aktif</span>
    </div>
    <?php if (count($myActive) === 0): ?>
        <div class="bg-white rounded-2xl shadow-card p-6 border border-gray-50 text-center">
            <p class="text-sm font-semibold text-textMain">🎉 Semua prospek sudah di-follow up</p>
            <p class="text-xs text-textSub mt-1">Saatnya tambah prospek baru.</p>
            <a href="<?= BASE_URL ?>e-prospek?tab=form" class="mt-3 inline-flex items-center gap-1.5 bg-primary text-white text-xs font-semibold px-4 py-2 rounded-xl">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Prospek
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-2.5">
            <?php foreach (array_slice($myActive, 0, 5) as $p): ?>
                <a href="<?= BASE_URL ?>e-prospek?tab=detail&id=<?= $p['id'] ?>" class="block bg-white rounded-2xl shadow-card p-4 border border-gray-50">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                            <?= strtoupper(substr($p['nama'], 0, 2)) ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-textMain truncate"><?= htmlspecialchars($p['nama']) ?></p>
                            <p class="text-xs text-textSub mt-0.5"><?= htmlspecialchars($p['pemilik']) ?> • <?= $p['telp'] ?></p>
                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full <?= ep_status_color($p['status']) ?>">
                                    <?= ep_status_label($p['status']) ?>
                                </span>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full <?= ep_produk_color($p['produk_jenis']) ?>">
                                    <?= ep_produk_label($p['produk_jenis']) ?>
                                </span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full <?= ep_score_color($p['score']) ?>">
                                    <?= ep_score_label($p['score']) ?>
                                </span>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-sm font-bold text-textMain"><?= vao_fmt_rp((int)$p['nominal']) ?></p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
