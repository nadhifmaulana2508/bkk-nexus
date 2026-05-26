<?php
/**
 * Mobile E-Pipelane - Dispatcher (untuk AO)
 * Tabs: home (default), list, detail, update
 */

require __DIR__ . '/e-pipelane/_data.php';

$allowedTabs = ['home', 'list', 'detail', 'update'];
$activeTab   = $_GET['tab'] ?? 'home';
if (!in_array($activeTab, $allowedTabs, true)) $activeTab = 'home';

$partial = __DIR__ . '/e-pipelane/m-' . $activeTab . '.php';
if (file_exists($partial)) include $partial;
else echo '<div class="px-4 pt-6"><div class="bg-white rounded-2xl shadow-card p-6 text-center text-textSub">Halaman dalam pengembangan.</div></div>';
