<?php
/**
 * Bottom Navigation - Mobile only
 * Dinamis per halaman:
 * - Dashboard: Home, Pipeline, Visit, Prospek, CCL
 * - Visit: Home, Jadwal, Riwayat, Tambah, Map
 * - E-Prospek: Home, Semua, Tambah, Hot, Filter
 * - E-Pipelane: Home, Aktif, Proses, Selesai, Laporan
 * - Call AO: Home, Riwayat, Tambah, Jadwal, CCL
 */
$currentPage = $page ?? 'dashboard';

// Default nav (Dashboard/Home)
$navItems = [
    ['page' => 'dashboard', 'label' => 'Home', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
    ['page' => 'e-pipelane', 'label' => 'Pipeline', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>'],
    ['page' => 'visit-ao', 'label' => 'Visit', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>'],
    ['page' => 'e-prospek', 'label' => 'Prospek', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
    ['page' => 'call-ao', 'label' => 'CCL', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
];

// Contextual nav per halaman
if ($currentPage === 'visit-ao') {
    $navItems = [
        ['page' => 'dashboard', 'label' => 'Home', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
        ['page' => 'visit-ao', 'label' => 'Jadwal', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
        ['page' => 'visit-ao', 'label' => 'Riwayat', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
        ['page' => 'visit-ao', 'label' => 'Tambah', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'],
        ['page' => 'visit-ao', 'label' => 'Map', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>'],
    ];
} elseif ($currentPage === 'e-prospek') {
    $navItems = [
        ['page' => 'dashboard', 'label' => 'Home', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
        ['page' => 'e-prospek', 'label' => 'Semua', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>'],
        ['page' => 'e-prospek', 'label' => 'Tambah', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'],
        ['page' => 'e-prospek', 'label' => 'Hot', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>'],
        ['page' => 'e-prospek', 'label' => 'Filter', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>'],
    ];
} elseif ($currentPage === 'e-pipelane') {
    $navItems = [
        ['page' => 'dashboard', 'label' => 'Home', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
        ['page' => 'e-pipelane', 'label' => 'Aktif', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>'],
        ['page' => 'e-pipelane', 'label' => 'Proses', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>'],
        ['page' => 'e-pipelane', 'label' => 'Selesai', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
        ['page' => 'e-pipelane', 'label' => 'Laporan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
    ];
} elseif ($currentPage === 'call-ao') {
    $navItems = [
        ['page' => 'dashboard', 'label' => 'Home', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
        ['page' => 'call-ao', 'label' => 'Riwayat', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
        ['page' => 'call-ao', 'label' => 'Tambah', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'],
        ['page' => 'call-ao', 'label' => 'Jadwal', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
        ['page' => 'call-ao', 'label' => 'CCL', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
    ];
}
?>

<nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-gray-100 z-50 lg:hidden shadow-lg">
    <div class="flex items-center justify-around h-16 px-1">
        <?php foreach ($navItems as $item): ?>
            <?php $isActive = ($currentPage === $item['page'] && $item['label'] !== 'Home') || ($currentPage === 'dashboard' && $item['label'] === 'Home'); ?>
            <a href="<?= BASE_URL . $item['page'] ?>" class="flex flex-col items-center justify-center flex-1 py-1 transition-all <?= $isActive ? 'bottom-nav-active' : 'text-gray-400' ?>">
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
