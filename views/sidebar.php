<?php
/**
 * Sidebar Navigation - Desktop only
 * Auto collapse/expand on hover + Sub menus toggle tanpa reload
 */
$currentPage = $page ?? 'dashboard';

$menuItems = [
    [
        'page' => 'dashboard',
        'label' => 'Dashboard',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
        'sub' => [
            ['label' => 'Overview', 'page' => 'dashboard'],
            ['label' => 'Target Bulanan', 'page' => 'dashboard'],
            ['label' => 'Ranking AO', 'page' => 'dashboard'],
        ]
    ],
    [
        'page' => 'e-prospek',
        'label' => 'E-Prospek',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
        'sub' => [
            ['label' => 'Semua Prospek', 'page' => 'e-prospek'],
            ['label' => 'Tambah Prospek', 'page' => 'e-prospek'],
            ['label' => 'Hot Leads', 'page' => 'e-prospek'],
            ['label' => 'Warm Leads', 'page' => 'e-prospek'],
        ]
    ],
    [
        'page' => 'e-pipelane',
        'label' => 'E-Pipelane',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>',
        'sub' => [
            ['label' => 'Pipeline Aktif', 'page' => 'e-pipelane'],
            ['label' => 'Dalam Proses', 'page' => 'e-pipelane'],
            ['label' => 'Selesai / CCL', 'page' => 'e-pipelane'],
            ['label' => 'Laporan SLA', 'page' => 'e-pipelane'],
        ]
    ],
    [
        'page' => 'visit-ao',
        'label' => 'Visit AO',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>',
        'sub' => [
            ['label' => 'Overview', 'page' => 'visit-ao', 'tab' => 'overview'],
            ['label' => 'Mapping Debitur', 'page' => 'visit-ao', 'tab' => 'mapping'],
            ['label' => 'Janji Bayar', 'page' => 'visit-ao', 'tab' => 'janji-bayar'],
            ['label' => 'Report', 'page' => 'visit-ao', 'tab' => 'report'],
        ]
    ],
    [
        'page' => 'call-ao',
        'label' => 'Call AO',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>',
        'sub' => [
            ['label' => 'Riwayat Call', 'page' => 'call-ao'],
            ['label' => 'Tambah Call', 'page' => 'call-ao'],
            ['label' => 'Jadwal Follow Up', 'page' => 'call-ao'],
            ['label' => 'Laporan CCL', 'page' => 'call-ao'],
        ]
    ],
];
?>

<aside id="desktopSidebar" 
       class="fixed top-0 left-0 z-40 h-screen gradient-sidebar transition-all duration-300 ease-in-out w-16 overflow-hidden group/sidebar"
       onmouseenter="expandSidebar()" 
       onmouseleave="collapseSidebar()">
    
    <!-- Logo -->
    <div class="flex items-center h-16 px-3.5 border-b border-white/10">
        <div class="w-9 h-9 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center flex-shrink-0 shadow-glow-primary">
            <span class="text-white font-bold text-sm">eP</span>
        </div>
        <div class="ml-3 sidebar-label whitespace-nowrap">
            <p class="text-white font-bold text-sm"><?= APP_NAME ?></p>
            <p class="text-indigo-300 text-xs"><?= APP_TAGLINE ?></p>
        </div>
    </div>

    <!-- Menu with Sub Items -->
    <nav class="mt-4 px-2 space-y-0.5 overflow-y-auto scrollbar-hide" style="max-height: calc(100vh - 140px);">
        <?php foreach ($menuItems as $index => $item): ?>
            <?php 
                $isActive = ($currentPage === $item['page']); 
                $menuId = 'submenu-' . $index;
            ?>
            <div class="sidebar-menu-group">
                <!-- Parent Menu Button (klik untuk toggle sub menu, bukan navigate) -->
                <button type="button"
                   onclick="toggleSubMenu('<?= $menuId ?>', this)"
                   class="w-full flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 relative
                          <?= $isActive 
                              ? 'bg-white/15 text-white shadow-lg backdrop-blur-sm border border-white/10' 
                              : 'text-indigo-200 hover:bg-white/10 hover:text-white' ?>"
                   title="<?= $item['label'] ?>">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <?= $item['icon'] ?>
                    </svg>
                    <span class="ml-3 sidebar-label whitespace-nowrap flex-1 text-left"><?= $item['label'] ?></span>
                    <!-- Arrow -->
                    <svg class="w-4 h-4 flex-shrink-0 sidebar-label transition-transform duration-300 submenu-arrow <?= $isActive ? 'rotate-90' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <!-- Sub Menu (toggle via JS, tanpa reload) -->
                <?php if (!empty($item['sub'])): ?>
                <div id="<?= $menuId ?>" class="sub-menu ml-5 mt-1 space-y-0.5 overflow-hidden transition-all duration-300 <?= $isActive ? 'max-h-48 opacity-100' : 'max-h-0 opacity-0' ?>">
                    <?php
                        $currentSubTab = $_GET['tab'] ?? null;
                        foreach ($item['sub'] as $subIndex => $sub):
                            $subHref = BASE_URL . $sub['page'] . (!empty($sub['tab']) ? '?tab=' . $sub['tab'] : '');
                            // Sub aktif jika: parent aktif DAN (tab cocok ATAU tab tidak diset & subIndex 0)
                            $isSubActive = $isActive && (
                                (!empty($sub['tab']) && $sub['tab'] === $currentSubTab) ||
                                (empty($sub['tab']) && $currentSubTab === null && $subIndex === 0)
                            );
                    ?>
                        <a href="<?= $subHref ?>"
                           class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition-colors whitespace-nowrap sidebar-label
                                  <?= $isSubActive ? 'text-white bg-white/10' : 'text-indigo-300 hover:text-white hover:bg-white/5' ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?= $isSubActive ? 'bg-primary' : 'bg-indigo-400/50' ?> mr-2.5 flex-shrink-0"></span>
                            <?= $sub['label'] ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </nav>

    <!-- User info bottom -->
    <div class="absolute bottom-0 left-0 right-0 p-2 border-t border-white/10">
        <div class="flex items-center p-2 rounded-xl bg-white/5">
            <div class="w-9 h-9 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-bold text-sm shadow-md flex-shrink-0">
                <?= $userInitial ?>
            </div>
            <div class="ml-3 flex-1 min-w-0 sidebar-label whitespace-nowrap">
                <p class="text-sm font-medium text-white truncate"><?= $userName ?></p>
                <p class="text-xs text-indigo-300 capitalize"><?= $userRole ?></p>
            </div>
        </div>
    </div>
</aside>

<script>
// Sidebar expand/collapse
function expandSidebar() {
    const sidebar = document.getElementById('desktopSidebar');
    const content = document.getElementById('desktopContent');
    sidebar.style.width = '256px';
    if (content) content.style.marginLeft = '256px';
    // Show labels
    document.querySelectorAll('.sidebar-label').forEach(el => {
        el.style.opacity = '1';
        el.style.visibility = 'visible';
    });
    // Show open submenus
    document.querySelectorAll('.sub-menu').forEach(el => {
        if (el.dataset.open === 'true') {
            el.style.maxHeight = '200px';
            el.style.opacity = '1';
        }
    });
}

function collapseSidebar() {
    const sidebar = document.getElementById('desktopSidebar');
    const content = document.getElementById('desktopContent');
    sidebar.style.width = '64px';
    if (content) content.style.marginLeft = '64px';
    // Hide labels
    document.querySelectorAll('.sidebar-label').forEach(el => {
        el.style.opacity = '0';
        el.style.visibility = 'hidden';
    });
    // Hide all submenus visually (but keep state)
    document.querySelectorAll('.sub-menu').forEach(el => {
        el.style.maxHeight = '0';
        el.style.opacity = '0';
    });
}

// Toggle sub menu tanpa reload
function toggleSubMenu(menuId, btn) {
    const submenu = document.getElementById(menuId);
    if (!submenu) return;

    const isOpen = submenu.dataset.open === 'true';
    const arrow = btn.querySelector('.submenu-arrow');

    if (isOpen) {
        // Close
        submenu.style.maxHeight = '0';
        submenu.style.opacity = '0';
        submenu.dataset.open = 'false';
        if (arrow) arrow.style.transform = 'rotate(0deg)';
    } else {
        // Close all others first
        document.querySelectorAll('.sub-menu').forEach(el => {
            el.style.maxHeight = '0';
            el.style.opacity = '0';
            el.dataset.open = 'false';
        });
        document.querySelectorAll('.submenu-arrow').forEach(el => {
            el.style.transform = 'rotate(0deg)';
        });

        // Open this one
        submenu.style.maxHeight = '200px';
        submenu.style.opacity = '1';
        submenu.dataset.open = 'true';
        if (arrow) arrow.style.transform = 'rotate(90deg)';
    }
}

// Initialize: mark active submenu as open
document.addEventListener('DOMContentLoaded', function() {
    // Set initial state for labels (hidden when collapsed)
    document.querySelectorAll('.sidebar-label').forEach(el => {
        el.style.opacity = '0';
        el.style.visibility = 'hidden';
        el.style.transition = 'opacity 0.3s, visibility 0.3s';
    });

    // Mark active submenus
    document.querySelectorAll('.sub-menu').forEach(el => {
        if (el.classList.contains('opacity-100')) {
            el.dataset.open = 'true';
        } else {
            el.dataset.open = 'false';
            el.style.maxHeight = '0';
            el.style.opacity = '0';
        }
    });
});
</script>
