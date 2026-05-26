<?php
/**
 * Desktop Dashboard - Modern Indigo Theme
 */
?>

<!-- Welcome + Target -->
<div class="mb-6 animate-fade-in">
    <h2 class="text-2xl font-bold text-textMain">Halo, <?= $userName ?>! 👋</h2>
    <p class="text-sm text-textSub mt-1">Berikut ringkasan performa Anda hari ini.</p>
</div>

<!-- Target Pipeline Banner -->
<div class="bg-gradient-to-r from-primaryDeep via-primaryDark to-primary rounded-2xl p-6 text-white mb-6 shadow-glow-primary animate-fade-in">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm opacity-80 uppercase tracking-wider font-medium">Target Pipeline Bulan Ini</p>
            <p class="text-3xl font-bold mt-1">Rp 3,5 Miliar</p>
        </div>
        <div class="mt-4 md:mt-0 text-right">
            <p class="text-sm opacity-80">Capaian</p>
            <p class="text-5xl font-extrabold">68<span class="text-2xl">%</span></p>
        </div>
    </div>
    <div class="w-full bg-white/20 rounded-full h-3 mt-5">
        <div class="bg-gradient-to-r from-green-400 to-emerald-300 h-3 rounded-full transition-all shadow-glow-success" style="width: 68%"></div>
    </div>
    <div class="grid grid-cols-3 gap-4 mt-5">
        <div class="text-center p-3 bg-white/10 rounded-xl backdrop-blur-sm">
            <p class="text-xl font-bold">Rp 2,4 M</p>
            <p class="text-xs opacity-80">Closing</p>
        </div>
        <div class="text-center p-3 bg-white/10 rounded-xl backdrop-blur-sm">
            <p class="text-xl font-bold">42</p>
            <p class="text-xs opacity-80">Pipeline Aktif</p>
        </div>
        <div class="text-center p-3 bg-white/10 rounded-xl backdrop-blur-sm">
            <p class="text-xl font-bold">8</p>
            <p class="text-xs opacity-80">Ready Survey</p>
        </div>
    </div>
</div>

<!-- Funnel Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6 animate-fade-in">
    <div class="card-green rounded-2xl p-5 text-white relative overflow-hidden hover:scale-[1.02] transition-transform cursor-pointer">
        <p class="text-sm font-semibold opacity-90">Total Pipeline</p>
        <p class="text-3xl font-bold mt-2">1.278</p>
        <p class="text-xs opacity-80 mt-1">Rp 62,3 M</p>
        <div class="absolute -bottom-3 -right-3 opacity-20">
            <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/></svg>
        </div>
    </div>
    <div class="card-orange rounded-2xl p-5 text-white relative overflow-hidden hover:scale-[1.02] transition-transform cursor-pointer">
        <p class="text-sm font-semibold opacity-90">Follow Up</p>
        <p class="text-3xl font-bold mt-2">936</p>
        <p class="text-xs opacity-80 mt-1">73,2% diproses</p>
        <div class="absolute -bottom-3 -right-3 opacity-20">
            <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg>
        </div>
    </div>
    <div class="card-blue rounded-2xl p-5 text-white relative overflow-hidden hover:scale-[1.02] transition-transform cursor-pointer">
        <p class="text-sm font-semibold opacity-90">Ready Survey</p>
        <p class="text-3xl font-bold mt-2">410</p>
        <p class="text-xs opacity-80 mt-1">Siap survey lapangan</p>
        <div class="absolute -bottom-3 -right-3 opacity-20">
            <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
        </div>
    </div>
    <div class="card-red rounded-2xl p-5 text-white relative overflow-hidden hover:scale-[1.02] transition-transform cursor-pointer">
        <p class="text-sm font-semibold opacity-90">Overdue</p>
        <p class="text-3xl font-bold mt-2">56</p>
        <p class="text-xs opacity-80 mt-1">Perlu perhatian</p>
        <div class="absolute -bottom-3 -right-3 opacity-20">
            <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
        </div>
    </div>
</div>

<!-- Prioritas AO + Activity -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 animate-fade-in">
    <!-- Prioritas AO -->
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-card p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-textMain">Prioritas AO</h3>
            <a href="#" class="text-sm text-primary font-semibold hover:underline">Smart Queue →</a>
        </div>

        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 mb-5">
            <p class="text-xs text-textSub leading-relaxed">
                <span class="font-semibold text-textMain">💡 Catatan Flow:</span> 
                Survey mengambil calon debitur dari Pipeline yang statusnya sudah Follow Up/Visit dan siap disurvey.
            </p>
        </div>

        <div class="space-y-3">
            <?php
            $prospects = [
                ['init' => 'MJ', 'gradient' => 'from-primary to-secondary', 'name' => 'Toko Maju Jaya', 'desc' => 'Existing lancar • Saldo < 50% • Potensi top up Rp 250 juta', 'score' => 'HOT 92%', 'scoreColor' => 'bg-red-100 text-red-700', 'tag' => 'MonBis', 'tagColor' => 'bg-indigo-100 text-indigo-700'],
                ['init' => 'SB', 'gradient' => 'from-warning to-amber-400', 'name' => 'Sumber Barokah', 'desc' => 'Prospek baru • Usaha dagang • Produk kredit modal kerja', 'score' => 'WARM 74%', 'scoreColor' => 'bg-amber-100 text-amber-700', 'tag' => 'KMK', 'tagColor' => 'bg-purple-100 text-purple-700'],
                ['init' => 'CV', 'gradient' => 'from-success to-emerald-400', 'name' => 'CV Berkah Mandiri', 'desc' => 'Referral Kacab • Kontraktor • Potensi Rp 500 juta', 'score' => 'HOT 88%', 'scoreColor' => 'bg-red-100 text-red-700', 'tag' => 'KI', 'tagColor' => 'bg-green-100 text-green-700'],
            ];
            foreach ($prospects as $p):
            ?>
            <div class="flex items-center p-4 bg-surface rounded-xl hover:bg-gray-100 transition-all hover:shadow-sm">
                <div class="w-11 h-11 bg-gradient-to-br <?= $p['gradient'] ?> rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0 shadow-md">
                    <?= $p['init'] ?>
                </div>
                <div class="flex-1 ml-4 min-w-0">
                    <p class="text-sm font-bold text-textMain"><?= $p['name'] ?></p>
                    <p class="text-xs text-textSub mt-0.5 truncate"><?= $p['desc'] ?></p>
                    <div class="flex items-center space-x-2 mt-1.5">
                        <span class="text-xs <?= $p['scoreColor'] ?> px-2 py-0.5 rounded-full font-semibold"><?= $p['score'] ?></span>
                        <span class="text-xs <?= $p['tagColor'] ?> px-2 py-0.5 rounded-full font-medium"><?= $p['tag'] ?></span>
                    </div>
                </div>
                <div class="flex items-center space-x-2 ml-4 flex-shrink-0">
                    <button class="text-xs bg-primary text-white px-3 py-1.5 rounded-lg font-medium hover:bg-primary/90 shadow-sm transition-colors">Visit</button>
                    <button class="text-xs bg-success text-white px-3 py-1.5 rounded-lg font-medium hover:bg-success/90 shadow-sm transition-colors">Survey</button>
                    <button class="text-xs bg-gray-100 text-textMain px-3 py-1.5 rounded-lg font-medium hover:bg-gray-200 transition-colors">WA</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="bg-white rounded-2xl shadow-card p-6 border border-gray-100">
        <h3 class="text-lg font-bold text-textMain mb-4">Aktivitas Terbaru</h3>
        <div class="space-y-4">
            <?php
            $activities = [
                ['time' => '09:30', 'text' => 'Prospek baru: PT Maju Jaya', 'color' => 'bg-primary', 'bg' => 'bg-indigo-50'],
                ['time' => '10:15', 'text' => 'Visit CV Berkah selesai', 'color' => 'bg-success', 'bg' => 'bg-green-50'],
                ['time' => '11:00', 'text' => 'Menunggu approval Kacab', 'color' => 'bg-warning', 'bg' => 'bg-amber-50'],
                ['time' => '13:45', 'text' => 'Follow-up Pak Ahmad', 'color' => 'bg-secondary', 'bg' => 'bg-purple-50'],
                ['time' => '14:20', 'text' => 'Prospek ditolak: UD Sejahtera', 'color' => 'bg-danger', 'bg' => 'bg-red-50'],
                ['time' => '15:00', 'text' => 'Survey dijadwalkan besok', 'color' => 'bg-info', 'bg' => 'bg-cyan-50'],
            ];
            foreach ($activities as $act):
            ?>
            <div class="flex items-start space-x-3 p-2.5 <?= $act['bg'] ?> rounded-xl">
                <div class="flex-shrink-0 mt-1">
                    <div class="w-2.5 h-2.5 rounded-full <?= $act['color'] ?> ring-4 ring-white"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-textMain font-medium"><?= $act['text'] ?></p>
                    <p class="text-xs text-textSub"><?= $act['time'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
