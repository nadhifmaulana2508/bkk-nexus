<?php
/**
 * Mobile Dashboard - Modern App-like layout
 */
?>

<!-- Target Pipeline Card (overlap header) -->
<div class="px-4 -mt-4 relative z-10 animate-fade-in">
    <div class="bg-white rounded-2xl shadow-card-hover p-5">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs font-semibold text-textSub uppercase tracking-wider">Target Pipeline Bulan Ini</p>
            <div class="text-right">
                <p class="text-xs text-textSub">Capaian</p>
                <p class="text-lg font-bold text-primary">68%</p>
            </div>
        </div>
        <p class="text-2xl font-bold text-textMain mb-3">Rp 3,5 M</p>
        
        <!-- Progress bar -->
        <div class="w-full bg-gray-100 rounded-full h-2.5 mb-4">
            <div class="bg-gradient-to-r from-primary to-secondary h-2.5 rounded-full transition-all" style="width: 68%"></div>
        </div>

        <!-- Stats row -->
        <div class="grid grid-cols-3 gap-2">
            <div class="text-center p-2.5 bg-surface rounded-xl">
                <p class="text-sm font-bold text-textMain">Rp 2,4 M</p>
                <p class="text-xs text-textSub">Closing</p>
            </div>
            <div class="text-center p-2.5 bg-surface rounded-xl">
                <p class="text-sm font-bold text-textMain">42</p>
                <p class="text-xs text-textSub">Pipeline Aktif</p>
            </div>
            <div class="text-center p-2.5 bg-surface rounded-xl">
                <p class="text-sm font-bold text-textMain">8</p>
                <p class="text-xs text-textSub">Ready Survey</p>
            </div>
        </div>
    </div>
</div>

<!-- Menu Cepat -->
<div class="px-4 mt-6 animate-fade-in">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-bold text-textMain">Menu Cepat</h2>
        <a href="#" class="text-sm text-primary font-semibold">Journey AO</a>
    </div>
    
    <div class="grid grid-cols-4 gap-3 mb-3">
        <a href="<?= BASE_URL ?>e-pipelane" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Pipeline</span>
        </a>
        <a href="<?= BASE_URL ?>visit-ao" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2">
                <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Visit</span>
        </a>
        <a href="<?= BASE_URL ?>e-prospek" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2">
                <svg class="w-6 h-6 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Survey</span>
        </a>
        <a href="<?= BASE_URL ?>e-prospek" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2">
                <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Prospek</span>
        </a>
    </div>
    <div class="grid grid-cols-4 gap-3">
        <a href="<?= BASE_URL ?>call-ao" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2">
                <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Follow Up</span>
        </a>
        <a href="#" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2">
                <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Closing</span>
        </a>
        <a href="#" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Ranking</span>
        </a>
        <a href="#" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                </svg>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Target</span>
        </a>
    </div>
</div>

<!-- Funnel Hari Ini -->
<div class="px-4 mt-6 animate-fade-in">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-bold text-textMain">Funnel Hari Ini</h2>
        <a href="#" class="text-sm text-primary font-semibold">Detail</a>
    </div>
    
    <div class="grid grid-cols-2 gap-3 mb-3">
        <div class="card-green rounded-2xl p-4 text-white relative overflow-hidden">
            <p class="text-xs font-semibold opacity-90">Total Pipeline</p>
            <p class="text-3xl font-bold mt-1">1.278</p>
            <p class="text-xs opacity-80 mt-1">Rp 62,3 M</p>
            <div class="absolute -bottom-2 -right-2 opacity-20">
                <svg class="w-14 h-14" fill="currentColor" viewBox="0 0 24 24"><path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/></svg>
            </div>
        </div>
        <div class="card-orange rounded-2xl p-4 text-white relative overflow-hidden">
            <p class="text-xs font-semibold opacity-90">Follow Up</p>
            <p class="text-3xl font-bold mt-1">936</p>
            <p class="text-xs opacity-80 mt-1">73,2% diproses</p>
            <div class="absolute -bottom-2 -right-2 opacity-20">
                <svg class="w-14 h-14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div class="card-blue rounded-2xl p-4 text-white relative overflow-hidden">
            <p class="text-xs font-semibold opacity-90">Ready Survey</p>
            <p class="text-3xl font-bold mt-1">410</p>
            <p class="text-xs opacity-80 mt-1">Siap survey lapangan</p>
            <div class="absolute -bottom-2 -right-2 opacity-20">
                <svg class="w-14 h-14" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            </div>
        </div>
        <div class="card-red rounded-2xl p-4 text-white relative overflow-hidden">
            <p class="text-xs font-semibold opacity-90">Overdue</p>
            <p class="text-3xl font-bold mt-1">56</p>
            <p class="text-xs opacity-80 mt-1">Perlu perhatian</p>
            <div class="absolute -bottom-2 -right-2 opacity-20">
                <svg class="w-14 h-14" fill="currentColor" viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
            </div>
        </div>
    </div>
</div>

<!-- Prioritas AO -->
<div class="px-4 mt-6 pb-6 animate-fade-in">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-bold text-textMain">Prioritas AO</h2>
        <a href="#" class="text-sm text-primary font-semibold">Smart Queue</a>
    </div>

    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 mb-4">
        <p class="text-sm font-semibold text-textMain mb-1">💡 Catatan Flow</p>
        <p class="text-xs text-textSub leading-relaxed">Survey mengambil calon debitur dari Pipeline yang statusnya sudah Follow Up/Visit dan siap disurvey.</p>
    </div>

    <div class="space-y-3">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl shadow-card p-4 border border-gray-50 hover:shadow-card-hover transition-shadow">
            <div class="flex items-start space-x-3">
                <div class="w-11 h-11 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-bold text-xs flex-shrink-0">MJ</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-textMain">Toko Maju Jaya</p>
                    <p class="text-xs text-textSub mt-0.5">Existing lancar • Saldo < 50% • Potensi top up Rp 250 juta</p>
                    <div class="flex items-center space-x-2 mt-2">
                        <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-semibold">HOT 92%</span>
                        <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-medium">MonBis</span>
                    </div>
                    <div class="flex items-center space-x-2 mt-3">
                        <button class="text-xs bg-primary text-white px-3 py-1.5 rounded-lg font-medium shadow-sm">Visit</button>
                        <button class="text-xs bg-success text-white px-3 py-1.5 rounded-lg font-medium shadow-sm">Survey</button>
                        <button class="text-xs bg-gray-100 text-textMain px-3 py-1.5 rounded-lg font-medium">WA</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-2xl shadow-card p-4 border border-gray-50 hover:shadow-card-hover transition-shadow">
            <div class="flex items-start space-x-3">
                <div class="w-11 h-11 bg-gradient-to-br from-warning to-amber-400 rounded-full flex items-center justify-center text-white font-bold text-xs flex-shrink-0">SB</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-textMain">Sumber Barokah</p>
                    <p class="text-xs text-textSub mt-0.5">Prospek baru • Usaha dagang • Produk kredit modal kerja</p>
                    <div class="flex items-center space-x-2 mt-2">
                        <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-semibold">WARM 74%</span>
                        <span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full font-medium">KMK</span>
                    </div>
                    <div class="flex items-center space-x-2 mt-3">
                        <button class="text-xs bg-primary text-white px-3 py-1.5 rounded-lg font-medium shadow-sm">Visit</button>
                        <button class="text-xs bg-success text-white px-3 py-1.5 rounded-lg font-medium shadow-sm">Survey</button>
                        <button class="text-xs bg-gray-100 text-textMain px-3 py-1.5 rounded-lg font-medium">WA</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-2xl shadow-card p-4 border border-gray-50 hover:shadow-card-hover transition-shadow">
            <div class="flex items-start space-x-3">
                <div class="w-11 h-11 bg-gradient-to-br from-success to-emerald-400 rounded-full flex items-center justify-center text-white font-bold text-xs flex-shrink-0">CV</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-textMain">CV Berkah Mandiri</p>
                    <p class="text-xs text-textSub mt-0.5">Referral Kacab • Kontraktor • Potensi Rp 500 juta</p>
                    <div class="flex items-center space-x-2 mt-2">
                        <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-semibold">HOT 88%</span>
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">KI</span>
                    </div>
                    <div class="flex items-center space-x-2 mt-3">
                        <button class="text-xs bg-primary text-white px-3 py-1.5 rounded-lg font-medium shadow-sm">Visit</button>
                        <button class="text-xs bg-success text-white px-3 py-1.5 rounded-lg font-medium shadow-sm">Survey</button>
                        <button class="text-xs bg-gray-100 text-textMain px-3 py-1.5 rounded-lg font-medium">WA</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
