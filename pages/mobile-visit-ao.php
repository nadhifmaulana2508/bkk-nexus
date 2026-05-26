<?php
/**
 * Mobile Visit AO - Dispatcher untuk AO di lapangan
 * 
 * Tabs (pakai bottom-nav):
 *   - home        : dashboard ringkas AO (default)
 *   - debitur     : list debitur kelolaan + filter bucket
 *   - form        : form kunjungan baru (GPS + foto + hasil)
 *   - janji-bayar : list janji bayar yang harus diingat
 *   - report      : statistik kelolaan AO bulan ini
 */

// Load shared data + helpers
require __DIR__ . '/visit-ao/_data.php';

// Tab routing (default 'home')
$allowedTabs = ['home', 'debitur', 'form', 'janji-bayar', 'report'];
$activeTab   = $_GET['tab'] ?? 'home';
if (!in_array($activeTab, $allowedTabs, true)) $activeTab = 'home';

// AO yang sedang login (saat SSO siap, ambil dari token)
$currentAo = vao_ao_by_id($aos, $currentAoId);
$myDebiturs    = vao_filter_debitur_by_ao($debiturs, $currentAoId);
$myKunjungans  = array_values(array_filter($kunjungans, fn($k) => $k['ao_id'] === $currentAoId));
$myJanjiBayars = array_values(array_filter($janjiBayars, fn($j) => $j['ao_id'] === $currentAoId));
?>

<?php
    $partialFile = __DIR__ . '/visit-ao/m-' . $activeTab . '.php';
    if (file_exists($partialFile)) {
        include $partialFile;
    } else {
        echo '<div class="px-4 pt-6"><div class="bg-white rounded-2xl shadow-card p-6 text-center text-textSub">Halaman dalam pengembangan.</div></div>';
    }
?>
