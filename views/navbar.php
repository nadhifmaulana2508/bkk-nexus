<?php
/**
 * Navbar - Desktop only (Modern)
 */
?>
<header class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-gray-100">
    <div class="flex items-center justify-between h-16 px-6">
        <div>
            <h1 class="text-xl font-bold text-textMain"><?= $pageTitle ?></h1>
            <p class="text-xs text-textSub">Selamat datang kembali, <?= $userName ?> 👋</p>
        </div>
        <div class="flex items-center space-x-3">
            <!-- Search -->
            <div class="hidden xl:flex items-center bg-gray-100 rounded-xl px-3 py-2">
                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Cari prospek..." class="bg-transparent text-sm text-textMain placeholder-gray-400 outline-none w-48">
            </div>
            <!-- Notification -->
            <button class="relative p-2.5 rounded-xl text-gray-500 hover:text-primary hover:bg-primary/5 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="absolute top-2 right-2 w-2 h-2 bg-danger rounded-full ring-2 ring-white"></span>
            </button>
            <!-- User -->
            <div class="flex items-center space-x-3 pl-3 border-l border-gray-200">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-textMain"><?= $userName ?></p>
                    <p class="text-xs text-textSub capitalize"><?= $userRole ?></p>
                </div>
                <div class="w-9 h-9 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-md">
                    <?= $userInitial ?>
                </div>
            </div>
        </div>
    </div>
</header>
