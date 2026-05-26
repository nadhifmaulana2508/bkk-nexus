<?php
/**
 * Mobile Header - Modern gradient indigo/violet
 */
?>
<div class="gradient-header rounded-b-[28px] px-5 pt-5 pb-8 shadow-lg">
    <!-- Top bar: Logo + Notification + Avatar -->
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center space-x-2.5">
            <div class="w-9 h-9 bg-white/15 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/20">
                <span class="text-white font-bold text-xs">eP</span>
            </div>
            <div>
                <p class="text-white font-bold text-sm"><?= APP_NAME ?></p>
                <p class="text-indigo-200 text-xs"><?= APP_TAGLINE ?></p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <button class="relative p-2 rounded-xl bg-white/10 text-white/90 hover:bg-white/20 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-danger rounded-full ring-2 ring-indigo-900"></span>
            </button>
            <div class="w-9 h-9 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-bold text-sm ring-2 ring-white/20">
                <?= $userInitial ?>
            </div>
        </div>
    </div>

    <!-- Greeting -->
    <h1 class="text-white text-xl font-bold">Halo, <?= $userName ?> 👋</h1>
    <p class="text-indigo-200 text-sm mt-0.5">Semangat kerja hari ini!</p>
</div>
