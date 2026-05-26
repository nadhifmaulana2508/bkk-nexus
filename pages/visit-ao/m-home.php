<?php
/**
 * Mobile - Visit AO Home
 * Dashboard ringkas untuk AO di lapangan: greeting, stats kelolaan, shortcut.
 */

// Hitung statistik kelolaan saya
$myMovement = vao_compute_movement_stats($myDebiturs);
$myBucket   = vao_compute_bucket_stats($myDebiturs);

// Total baki debet kelolaan saya
$totalBakiDebet = array_sum(array_column($myDebiturs, 'baki_debet'));

// Janji bayar aktif
$janjiAktif = array_values(array_filter($myJanjiBayars, fn($j) => $j['status'] === 'aktif'));
$janjiTerealisasi = array_values(array_filter($myJanjiBayars, fn($j) => $j['status'] === 'terealisasi'));

// Visit hari ini
$today = $filterHarianDate;
$visitsHariIni = array_values(array_filter($myKunjungans, fn($k) => $k['tanggal'] === $today));

// Quick wins & worries
$debiturBerhasil  = array_values(array_filter($myDebiturs, fn($d) => $d['movement'] === 'paid_off' || $d['movement'] === 'improve'));
$debiturPemburukan = array_values(array_filter($myDebiturs, fn($d) => $d['movement'] === 'worse'));
?>

<!-- Stats Card (overlap header) -->
<div class="px-4 -mt-4 relative z-10 animate-fade-in">
    <div class="bg-white rounded-2xl shadow-card-hover p-5">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="text-xs font-semibold text-textSub uppercase tracking-wider">Kelolaan Saya</p>
                <p class="text-xs text-textSub mt-0.5"><?= vao_role_label($currentAo['role']) ?> • <?= htmlspecialchars(vao_kantor_nama($kantors, $currentAo['kode_kantor'])) ?></p>
            </div>
            <div class="text-right">
                <p class="text-xs text-textSub">Total Baki Debet</p>
                <p class="text-base font-bold text-primary"><?= vao_fmt_rp($totalBakiDebet) ?></p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-2">
            <div class="text-center p-3 bg-surface rounded-xl">
                <p class="text-xl font-bold text-textMain"><?= count($myDebiturs) ?></p>
                <p class="text-[11px] text-textSub mt-0.5">Debitur</p>
            </div>
            <div class="text-center p-3 bg-emerald-50 rounded-xl">
                <p class="text-xl font-bold text-emerald-700"><?= count($debiturBerhasil) ?></p>
                <p class="text-[11px] text-emerald-600/80 mt-0.5">Berhasil</p>
            </div>
            <div class="text-center p-3 bg-red-50 rounded-xl">
                <p class="text-xl font-bold text-red-700"><?= count($debiturPemburukan) ?></p>
                <p class="text-[11px] text-red-600/80 mt-0.5">Pemburukan</p>
            </div>
        </div>
    </div>
</div>

<!-- Aksi Cepat -->
<div class="px-4 mt-6 animate-fade-in">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-base font-bold text-textMain">Aksi Cepat</h2>
    </div>

    <div class="grid grid-cols-4 gap-3">
        <a href="<?= BASE_URL ?>visit-ao?tab=form" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Visit Baru</span>
        </a>
        <a href="<?= BASE_URL ?>visit-ao?tab=debitur" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2">
                <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Debitur</span>
        </a>
        <a href="<?= BASE_URL ?>visit-ao?tab=janji-bayar" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2 relative">
                <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <?php if (count($janjiAktif) > 0): ?>
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-danger text-white text-[10px] font-bold rounded-full flex items-center justify-center"><?= count($janjiAktif) ?></span>
                <?php endif; ?>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Janji Bayar</span>
        </a>
        <a href="<?= BASE_URL ?>visit-ao?tab=report" class="flex flex-col items-center">
            <div class="menu-icon-box mb-2">
                <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <span class="text-xs text-textMain text-center font-medium">Report</span>
        </a>
    </div>
</div>

<!-- Pergerakan Bucket Bulan Ini -->
<div class="px-4 mt-6 animate-fade-in">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-base font-bold text-textMain">Pergerakan Bucket</h2>
        <a href="<?= BASE_URL ?>visit-ao?tab=report" class="text-xs text-primary font-semibold">Lihat detail →</a>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div class="card-green rounded-2xl p-4 text-white">
            <p class="text-xs opacity-90 font-semibold">Berhasil Bayar</p>
            <p class="text-3xl font-extrabold mt-1"><?= $myMovement['paid_off'] ?></p>
            <p class="text-[11px] opacity-80 mt-1">Lunas / closing penuh</p>
        </div>
        <div class="card-blue rounded-2xl p-4 text-white">
            <p class="text-xs opacity-90 font-semibold">Perbaikan Bucket</p>
            <p class="text-3xl font-extrabold mt-1"><?= $myMovement['improve'] ?></p>
            <p class="text-[11px] opacity-80 mt-1">DPD turun ke bucket lebih ringan</p>
        </div>
        <div class="card-orange rounded-2xl p-4 text-white">
            <p class="text-xs opacity-90 font-semibold">Stay</p>
            <p class="text-3xl font-extrabold mt-1"><?= $myMovement['stay'] ?></p>
            <p class="text-[11px] opacity-80 mt-1">Bucket sama</p>
        </div>
        <div class="card-red rounded-2xl p-4 text-white">
            <p class="text-xs opacity-90 font-semibold">Pemburukan</p>
            <p class="text-3xl font-extrabold mt-1"><?= $myMovement['worse'] ?></p>
            <p class="text-[11px] opacity-80 mt-1">DPD naik — perlu dikejar</p>
        </div>
    </div>
</div>

<!-- Janji Bayar Hari Ini / Mendatang -->
<?php if (count($janjiAktif) > 0): ?>
<div class="px-4 mt-6 animate-fade-in">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-base font-bold text-textMain">Janji Bayar Aktif</h2>
        <a href="<?= BASE_URL ?>visit-ao?tab=janji-bayar" class="text-xs text-primary font-semibold">Semua →</a>
    </div>

    <div class="space-y-2.5">
        <?php foreach (array_slice($janjiAktif, 0, 3) as $jb):
            $deb = vao_debitur_by_id($debiturs, $jb['debitur_id']);
            if (!$deb) continue;
            $isOverdue = strtotime($jb['tanggal_janji']) < strtotime($filterHarianDate);
            $isToday   = $jb['tanggal_janji'] === $filterHarianDate;
        ?>
            <div class="bg-white rounded-2xl shadow-card p-4 border border-gray-50">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-textMain truncate"><?= htmlspecialchars($deb['nama']) ?></p>
                        <p class="text-xs text-textSub mt-0.5"><?= htmlspecialchars($deb['pemilik']) ?> • <?= $deb['no_rek'] ?></p>
                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full
                                <?= $isOverdue ? 'bg-red-100 text-red-700' : ($isToday ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') ?>">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <?= vao_fmt_tgl($jb['tanggal_janji']) ?>
                                <?php if ($isOverdue): ?> · Terlewat<?php elseif ($isToday): ?> · Hari ini<?php endif; ?>
                            </span>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-sm font-bold text-textMain"><?= vao_fmt_rp((int)$jb['nominal_janji']) ?></p>
                        <a href="<?= BASE_URL ?>visit-ao?tab=form&debitur_id=<?= $deb['id'] ?>"
                           class="mt-1.5 inline-flex items-center gap-1 text-[11px] bg-primary text-white px-2.5 py-1 rounded-lg font-medium">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            Visit
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Visit Hari Ini -->
<div class="px-4 mt-6 pb-6 animate-fade-in">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-base font-bold text-textMain">Aktivitas Visit Hari Ini</h2>
        <span class="text-xs text-textSub"><?= vao_fmt_tgl($filterHarianDate) ?></span>
    </div>

    <?php if (count($visitsHariIni) === 0): ?>
        <div class="bg-white rounded-2xl shadow-card p-6 border border-gray-50 text-center">
            <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-textMain">Belum ada visit hari ini</p>
            <p class="text-xs text-textSub mt-1">Mulai kunjungan ke debitur kelolaan Anda.</p>
            <a href="<?= BASE_URL ?>visit-ao?tab=form" class="mt-3 inline-flex items-center gap-1.5 bg-primary text-white text-xs font-semibold px-4 py-2 rounded-xl">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Visit Sekarang
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-2.5">
            <?php foreach ($visitsHariIni as $v):
                $deb = vao_debitur_by_id($debiturs, $v['debitur_id']);
                if (!$deb) continue;
            ?>
                <div class="bg-white rounded-2xl shadow-card p-4 border border-gray-50">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-success/80 to-emerald-400 rounded-xl flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            <?= $v['waktu'] ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-textMain truncate"><?= htmlspecialchars($deb['nama']) ?></p>
                            <p class="text-xs text-textSub mt-0.5"><?= htmlspecialchars($v['catatan']) ?></p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full <?= vao_visit_hasil_color($v['hasil']) ?>">
                                    <?= vao_visit_hasil_label($v['hasil']) ?>
                                </span>
                                <?php if (!empty($v['nominal_janji'])): ?>
                                    <span class="text-[11px] text-textSub"><?= vao_fmt_rp((int)$v['nominal_janji']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
