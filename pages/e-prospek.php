<?php
/**
 * Desktop E-Prospek - Dispatcher
 * Tabs: overview (default), delegasi, list, report
 */

require __DIR__ . '/e-prospek/_data.php';

$allowedTabs = ['overview', 'delegasi', 'list', 'report'];
$activeTab   = $_GET['tab'] ?? 'overview';
if (!in_array($activeTab, $allowedTabs, true)) $activeTab = 'overview';

$tabs = [
    'overview' => ['Overview',  '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>'],
    'delegasi' => ['Delegasi',  '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
    'list'     => ['Semua Prospek', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>'],
    'report'   => ['Report',    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
];
?>

<div class="animate-fade-in">
    <div class="bg-white rounded-2xl shadow-card border border-gray-100 mb-4 overflow-hidden">
        <div class="px-6 pt-5 pb-3 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="text-lg font-bold text-textMain">E-Prospek — Admin Console</h2>
                <p class="text-xs text-textSub mt-0.5">Pendataan calon debitur, delegasi ke AO, dan tracking konversi.</p>
            </div>
            <div class="text-right text-xs">
                <p class="text-textSub">Cabang aktif</p>
                <p class="font-bold text-textMain"><?= htmlspecialchars(vao_kantor_nama($kantors, $filterKodeKantor)) ?></p>
            </div>
        </div>
        <div class="px-2 flex items-center gap-1 overflow-x-auto scrollbar-hide">
            <?php foreach ($tabs as $tabKey => [$lbl, $icon]):
                $isActive = $activeTab === $tabKey;
                $url = BASE_URL . 'e-prospek' . ep_query_with(['tab' => $tabKey]);
            ?>
                <a href="<?= $url ?>"
                   class="inline-flex items-center gap-2 px-4 py-3 text-sm font-semibold border-b-2 transition-colors whitespace-nowrap
                          <?= $isActive ? 'border-primary text-primary' : 'border-transparent text-textSub hover:text-textMain hover:bg-gray-50' ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $icon ?></svg>
                    <?= $lbl ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include __DIR__ . '/e-prospek/_filterbar.php'; ?>

    <?php
    $partial = __DIR__ . '/e-prospek/d-' . $activeTab . '.php';
    if (file_exists($partial)) include $partial;
    else echo '<div class="bg-white rounded-2xl shadow-card p-8 text-center text-textSub">Halaman dalam pengembangan.</div>';
    ?>
</div>
