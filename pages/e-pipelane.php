<?php
/**
 * Desktop E-Pipelane - Dispatcher
 * Tabs: overview, list, detail, report
 */

require __DIR__ . '/e-pipelane/_data.php';

$allowedTabs = ['overview', 'list', 'detail', 'report'];
$activeTab   = $_GET['tab'] ?? 'overview';
if (!in_array($activeTab, $allowedTabs, true)) $activeTab = 'overview';

$tabs = [
    'overview' => ['Overview',  '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2"/>'],
    'list'     => ['Pipeline Aktif', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>'],
    'detail'   => ['Detail',    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>'],
    'report'   => ['Report SLA','<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
];

function epl_query_with(array $override = []): string {
    $merged = array_merge($_GET, $override);
    return '?' . http_build_query($merged);
}
?>

<div class="animate-fade-in">
    <div class="bg-white rounded-2xl shadow-card border border-gray-100 mb-4 overflow-hidden">
        <div class="px-6 pt-5 pb-3 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="text-lg font-bold text-textMain">E-Pipelane — Credit SLA</h2>
                <p class="text-xs text-textSub mt-0.5">Tracking 7 stage proses kredit dari pengumpulan berkas sampai akad.</p>
            </div>
            <div class="text-right text-xs">
                <p class="text-textSub">Cabang aktif</p>
                <p class="font-bold text-textMain"><?= htmlspecialchars(vao_kantor_nama($kantors, $filterKodeKantor)) ?></p>
            </div>
        </div>
        <div class="px-2 flex items-center gap-1 overflow-x-auto scrollbar-hide">
            <?php foreach ($tabs as $tabKey => [$lbl, $icon]):
                $isActive = $activeTab === $tabKey;
                $url = BASE_URL . 'e-pipelane' . epl_query_with(['tab' => $tabKey]);
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

    <?php
    // Re-use filterbar dari e-prospek (struktur sama)
    $page = 'e-pipelane';
    include __DIR__ . '/e-prospek/_filterbar.php';
    ?>

    <?php
    $partial = __DIR__ . '/e-pipelane/d-' . $activeTab . '.php';
    if (file_exists($partial)) include $partial;
    else echo '<div class="bg-white rounded-2xl shadow-card p-8 text-center text-textSub">Halaman dalam pengembangan.</div>';
    ?>
</div>
