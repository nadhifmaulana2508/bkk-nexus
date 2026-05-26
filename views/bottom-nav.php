<?php
/**
 * Bottom Navigation - Mobile only
 * Dinamis per halaman dengan dukungan tab (?tab=) untuk modul Visit AO.
 */
$currentPage = $page ?? 'dashboard';
$currentTab  = $_GET['tab'] ?? null;

// Default nav (Dashboard/Home)
$navItems = [
    ['page' => 'dashboard',  'label' => 'Home',     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
    ['page' => 'e-pipelane', 'label' => 'Pipeline', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>'],
    ['page' => 'visit-ao',   'label' => 'Visit',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>'],
    ['page' => 'e-prospek',  'label' => 'Prospek',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
    ['page' => 'call-ao',    'label' => 'CCL',      'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
];

// ====================================
// Contextual nav per halaman
// ====================================
if ($currentPage === 'visit-ao') {
    // Tab-aware navigation untuk Visit AO Mobile
    $navItems = [
        ['page' => 'visit-ao', 'tab' => 'home',        'label' => 'Home',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
        ['page' => 'visit-ao', 'tab' => 'debitur',     'label' => 'Debitur', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
        ['page' => 'visit-ao', 'tab' => 'form',        'label' => 'Visit',   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'],
        ['page' => 'visit-ao', 'tab' => 'janji-bayar', 'label' => 'Janji',   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>'],
        ['page' => 'visit-ao', 'tab' => 'report',      'label' => 'Report',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
    ];
} elseif ($currentPage === 'e-prospek') {
    $navItems = [
        ['page' => 'e-prospek', 'tab' => 'home',     'label' => 'Home',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
        ['page' => 'e-prospek', 'tab' => 'list',     'label' => 'Semua',   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>'],
        ['page' => 'e-prospek', 'tab' => 'form',     'label' => 'Tambah',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'],
        ['page' => 'e-prospek', 'tab' => 'list',     'label' => 'Hot',     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>', 'extra' => 'score=hot'],
        ['page' => 'dashboard',  'label' => 'Home Utama', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
    ];
} elseif ($currentPage === 'e-pipelane') {
    $navItems = [
        ['page' => 'e-pipelane', 'tab' => 'home',   'label' => 'Home',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
        ['page' => 'e-pipelane', 'tab' => 'list',   'label' => 'Pipeline','icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>'],
        ['page' => 'e-pipelane', 'tab' => 'list',   'label' => 'Overdue', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>', 'extra' => 'filter=overdue'],
        ['page' => 'dashboard',  'label' => 'Home Utama', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
    ];
} elseif ($currentPage === 'call-ao') {
    $navItems = [
        ['page' => 'dashboard', 'label' => 'Home',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
        ['page' => 'call-ao',   'label' => 'Riwayat', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
        ['page' => 'call-ao',   'label' => 'Tambah',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'],
        ['page' => 'call-ao',   'label' => 'Jadwal',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
        ['page' => 'call-ao',   'label' => 'CCL',     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
    ];
}

/** Build href untuk nav item, support tab + extra params. */
function bnav_href(array $item): string {
    $href = BASE_URL . $item['page'];
    $params = [];
    if (!empty($item['tab'])) $params[] = 'tab=' . $item['tab'];
    if (!empty($item['extra'])) $params[] = $item['extra'];
    if (!empty($params)) $href .= '?' . implode('&', $params);
    return $href;
}

/** Cek apakah item aktif. */
function bnav_is_active(array $item, string $currentPage, ?string $currentTab): bool {
    // Tab-aware nav untuk page yang punya tab
    if (in_array($currentPage, ['visit-ao', 'e-prospek', 'e-pipelane'], true) && isset($item['tab'])) {
        $effectiveTab = $currentTab ?: 'home';
        return $item['page'] === $currentPage && $item['tab'] === $effectiveTab;
    }
    // Default: cocokkan page (Home label = dashboard)
    return ($currentPage === $item['page'] && ($item['label'] !== 'Home'))
        || ($currentPage === 'dashboard' && $item['label'] === 'Home');
}
?>

<nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-gray-100 z-50 lg:hidden shadow-lg">
    <div class="flex items-center justify-around h-16 px-1">
        <?php foreach ($navItems as $item): ?>
            <?php $isActive = bnav_is_active($item, $currentPage, $currentTab); ?>
            <a href="<?= bnav_href($item) ?>" class="flex flex-col items-center justify-center flex-1 py-1 transition-all <?= $isActive ? 'bottom-nav-active' : 'text-gray-400' ?>">
                <?php if ($isActive): ?>
                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center mb-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $item['icon'] ?></svg>
                    </div>
                <?php else: ?>
                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $item['icon'] ?></svg>
                <?php endif; ?>
                <span class="text-[10px] font-medium"><?= $item['label'] ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</nav>
