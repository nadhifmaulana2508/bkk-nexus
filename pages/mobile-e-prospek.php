<?php
/**
 * Mobile E-Prospek - Dispatcher (auto untuk AO)
 * Tabs: home (default), list, form, detail
 */

require __DIR__ . '/e-prospek/_data.php';

$allowedTabs = ['home', 'list', 'form', 'detail'];
$activeTab   = $_GET['tab'] ?? 'home';
if (!in_array($activeTab, $allowedTabs, true)) $activeTab = 'home';

$partial = __DIR__ . '/e-prospek/m-' . $activeTab . '.php';
if (file_exists($partial)) include $partial;
else echo '<div class="px-4 pt-6"><div class="bg-white rounded-2xl shadow-card p-6 text-center text-textSub">Halaman dalam pengembangan.</div></div>';
