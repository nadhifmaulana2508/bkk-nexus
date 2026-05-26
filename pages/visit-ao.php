<?php
/**
 * Desktop Visit AO - Dispatcher dengan tab routing
 * 
 * Tabs:
 *   - overview      : ringkasan + alert (default)
 *   - mapping       : delegasi mapping debitur ke AO Remedial (Kabid/Kacab/Pincab)
 *   - report        : laporan kunjungan + statistik movement bucket
 *   - janji-bayar   : monitor janji bayar lintas AO
 */

// Load shared data + helpers
require __DIR__ . '/visit-ao/_data.php';

// Tab routing
$allowedTabs = ['overview', 'mapping', 'report', 'janji-bayar'];
$activeTab   = $_GET['tab'] ?? 'overview';
if (!in_array($activeTab, $allowedTabs, true)) $activeTab = 'overview';

// Tab metadata
$tabs = [
    'overview' => [
        'label' => 'Overview',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>',
    ],
    'mapping' => [
        'label' => 'Mapping Debitur',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
    ],
    'report' => [
        'label' => 'Report',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
    ],
    'janji-bayar' => [
        'label' => 'Janji Bayar',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
    ],
];
?>

<div class="animate-fade-in">
    <!-- Header + Tab Navigation -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-100 mb-4 overflow-hidden">
        <div class="px-6 pt-5 pb-3 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="text-lg font-bold text-textMain">Visit AO — Admin Console</h2>
                <p class="text-xs text-textSub mt-0.5">
                    Mapping debitur, kunjungan, janji bayar, dan laporan pergerakan bucket per AO.
                </p>
            </div>
            <div class="text-right text-xs">
                <p class="text-textSub">Cabang aktif</p>
                <p class="font-bold text-textMain"><?= htmlspecialchars(vao_kantor_nama($kantors, $filterKodeKantor)) ?></p>
            </div>
        </div>

        <!-- Tabs -->
        <div class="px-2 flex items-center gap-1 overflow-x-auto scrollbar-hide">
            <?php foreach ($tabs as $tabKey => $tab):
                $isActive = ($activeTab === $tabKey);
                $url = BASE_URL . 'visit-ao' . vao_query_with(['tab' => $tabKey]);
            ?>
                <a href="<?= $url ?>"
                   class="inline-flex items-center gap-2 px-4 py-3 text-sm font-semibold border-b-2 transition-colors whitespace-nowrap
                          <?= $isActive ? 'border-primary text-primary' : 'border-transparent text-textSub hover:text-textMain hover:bg-gray-50' ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $tab['icon'] ?></svg>
                    <?= $tab['label'] ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Filter Bar -->
    <?php include __DIR__ . '/visit-ao/_filterbar.php'; ?>

    <!-- Tab Content -->
    <?php
        $partialFile = __DIR__ . '/visit-ao/d-' . $activeTab . '.php';
        if (file_exists($partialFile)) {
            include $partialFile;
        } else {
            echo '<div class="bg-white rounded-2xl shadow-card p-8 text-center text-textSub">Halaman dalam pengembangan.</div>';
        }
    ?>
</div>
