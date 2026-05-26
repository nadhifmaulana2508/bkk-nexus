<?php
/**
 * Front Controller - Routing utama & penempatan Dummy Role
 * ePipeline - AO Credit Operating System
 */

// Load konfigurasi (FE only, tanpa database)
require_once __DIR__ . '/config/env.php';

// ============================================
// DUMMY ROLE (ganti sesuai kebutuhan testing)
// ============================================
$userRole = 'ao'; // Options: 'admin', 'ao', 'kacab', 'pincab', 'guest'
$userName = 'Harry';
$userInitial = 'H';

// ============================================
// ROUTING (clean URL: /bkk-nexus/dashboard)
// ============================================
$page = $_GET['page'] ?? 'dashboard';

// Bersihkan trailing slash
$page = trim($page, '/');

// Default ke dashboard jika kosong
if (empty($page)) {
    $page = 'dashboard';
}

// Daftar halaman yang diizinkan
$allowedPages = [
    'dashboard',
    'e-prospek',
    'e-pipelane',
    'visit-ao',
    'call-ao',
];

// Validasi halaman
if (!in_array($page, $allowedPages)) {
    $page = 'dashboard';
}

// Judul halaman
$pageTitles = [
    'dashboard'  => 'Dashboard',
    'e-prospek'  => 'E-Prospek',
    'e-pipelane' => 'E-Pipelane',
    'visit-ao'   => 'Visit AO',
    'call-ao'    => 'Call AO',
];
$pageTitle = $pageTitles[$page] ?? 'Dashboard';

// ============================================
// RENDER - Responsive Layout
// Mobile: app-like (bottom nav per halaman)
// Desktop: sidebar auto-collapse + content area
// ============================================
include __DIR__ . '/views/header.php';
?>

<!-- DESKTOP LAYOUT (hidden on mobile) -->
<div class="hidden lg:flex min-h-screen">
    <?php include __DIR__ . '/views/sidebar.php'; ?>
    <div id="desktopContent" class="flex-1 flex flex-col transition-all duration-300 ease-in-out" style="margin-left: 64px;">
        <?php include __DIR__ . '/views/navbar.php'; ?>
        <main class="flex-1 p-6 bg-surface">
            <?php include __DIR__ . '/pages/' . $page . '.php'; ?>
        </main>
        <?php include __DIR__ . '/views/footer.php'; ?>
    </div>
</div>

<!-- MOBILE LAYOUT (hidden on desktop) -->
<div class="lg:hidden flex flex-col min-h-screen pb-16">
    <?php include __DIR__ . '/views/mobile-header.php'; ?>
    <main class="flex-1 bg-surface">
        <?php include __DIR__ . '/pages/mobile-' . $page . '.php'; ?>
    </main>
    <?php include __DIR__ . '/views/bottom-nav.php'; ?>
</div>

</body>
</html>
