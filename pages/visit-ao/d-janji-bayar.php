<?php
/**
 * Desktop - Monitor Janji Bayar Lintas AO
 */

$today = $filterHarianDate;
$subFilter = $_GET['filter'] ?? 'all';

$jbAll = $janjiBayarFiltered;

// Sub-filter
$jbList = match ($subFilter) {
    'overdue'     => array_values(array_filter($jbAll, fn($j) => $j['status'] === 'aktif' && strtotime($j['tanggal_janji']) < strtotime($today))),
    'today'       => array_values(array_filter($jbAll, fn($j) => $j['status'] === 'aktif' && $j['tanggal_janji'] === $today)),
    'upcoming'    => array_values(array_filter($jbAll, fn($j) => $j['status'] === 'aktif' && strtotime($j['tanggal_janji']) > strtotime($today))),
    'realisasi'   => array_values(array_filter($jbAll, fn($j) => $j['status'] === 'terealisasi')),
    'gagal'       => array_values(array_filter($jbAll, fn($j) => $j['status'] === 'gagal')),
    default       => $jbAll,
};

$counts = [
    'all'       => count($jbAll),
    'overdue'   => count(array_filter($jbAll, fn($j) => $j['status']==='aktif' && strtotime($j['tanggal_janji']) < strtotime($today))),
    'today'     => count(array_filter($jbAll, fn($j) => $j['status']==='aktif' && $j['tanggal_janji'] === $today)),
    'upcoming'  => count(array_filter($jbAll, fn($j) => $j['status']==='aktif' && strtotime($j['tanggal_janji']) > strtotime($today))),
    'realisasi' => count(array_filter($jbAll, fn($j) => $j['status']==='terealisasi')),
    'gagal'     => count(array_filter($jbAll, fn($j) => $j['status']==='gagal')),
];

$nominalAktif     = array_sum(array_column(array_filter($jbAll, fn($j)=>$j['status']==='aktif'), 'nominal_janji'));
$nominalRealisasi = array_sum(array_column(array_filter($jbAll, fn($j)=>$j['status']==='terealisasi'), 'realisasi_nominal'));
$nominalGagal     = array_sum(array_column(array_filter($jbAll, fn($j)=>$j['status']==='gagal'), 'nominal_janji'));
?>

<!-- KPI Janji Bayar -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-5">
        <p class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Janji Aktif</p>
        <p class="text-3xl font-extrabold text-amber-700 mt-2"><?= count(array_filter($jbAll, fn($j)=>$j['status']==='aktif')) ?></p>
        <p class="text-[11px] text-amber-600/80 mt-0.5"><?= vao_fmt_rp((int)$nominalAktif) ?> komitmen</p>
    </div>
    <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
        <p class="text-xs font-semibold text-red-700 uppercase tracking-wider">Terlewat</p>
        <p class="text-3xl font-extrabold text-red-700 mt-2"><?= $counts['overdue'] ?></p>
        <p class="text-[11px] text-red-600/80 mt-0.5">Perlu eskalasi</p>
    </div>
    <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5">
        <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Terealisasi</p>
        <p class="text-3xl font-extrabold text-emerald-700 mt-2"><?= $counts['realisasi'] ?></p>
        <p class="text-[11px] text-emerald-600/80 mt-0.5"><?= vao_fmt_rp((int)$nominalRealisasi) ?> dibayar</p>
    </div>
    <div class="bg-rose-50 border border-rose-100 rounded-2xl p-5">
        <p class="text-xs font-semibold text-rose-700 uppercase tracking-wider">Gagal</p>
        <p class="text-3xl font-extrabold text-rose-700 mt-2"><?= $counts['gagal'] ?></p>
        <p class="text-[11px] text-rose-600/80 mt-0.5"><?= vao_fmt_rp((int)$nominalGagal) ?> hilang</p>
    </div>
</div>

<!-- Sub-filter Tabs -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 mb-4 px-3 py-2 flex flex-wrap items-center gap-1.5">
    <?php
    $subTabs = [
        'all'       => ['Semua',      'bg-primary',     'text-primary'],
        'overdue'   => ['⚠ Terlewat', 'bg-red-500',     'text-red-600'],
        'today'     => ['Hari Ini',   'bg-amber-500',   'text-amber-600'],
        'upcoming'  => ['Mendatang',  'bg-blue-500',    'text-blue-600'],
        'realisasi' => ['Terealisasi','bg-emerald-500', 'text-emerald-600'],
        'gagal'     => ['Gagal',      'bg-rose-500',    'text-rose-600'],
    ];
    foreach ($subTabs as $key => [$label, $cls, $textCls]):
        $isActive = $subFilter === $key;
        $url = BASE_URL . 'visit-ao' . vao_query_with(['tab'=>'janji-bayar','filter'=>$key]);
    ?>
        <a href="<?= $url ?>"
           class="px-3 py-2 rounded-xl text-xs font-semibold transition-colors inline-flex items-center gap-1.5
                  <?= $isActive ? $cls . ' text-white shadow-sm' : 'text-textSub hover:bg-gray-100' ?>">
            <?= $label ?>
            <span class="<?= $isActive ? 'bg-white/25' : 'bg-gray-100' ?> px-1.5 py-0.5 rounded-full text-[10px] font-bold"><?= $counts[$key] ?></span>
        </a>
    <?php endforeach; ?>
</div>

<!-- Tabel Janji Bayar -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-base font-bold text-textMain">Daftar Janji Bayar</h3>
        <p class="text-xs text-textSub mt-0.5">Filter: <span class="font-semibold capitalize"><?= htmlspecialchars($subFilter) ?></span> • <?= count($jbList) ?> entri</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-surface">
                <tr class="text-xs text-textSub uppercase tracking-wider">
                    <th class="text-left p-4 font-semibold">Tgl Janji</th>
                    <th class="text-left p-4 font-semibold">Debitur</th>
                    <th class="text-left p-4 font-semibold">AO</th>
                    <th class="text-center p-4 font-semibold">Bucket</th>
                    <th class="text-right p-4 font-semibold">Nominal Janji</th>
                    <th class="text-right p-4 font-semibold">Realisasi</th>
                    <th class="text-center p-4 font-semibold">Status</th>
                    <th class="text-center p-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Sort by tanggal_janji asc
                usort($jbList, fn($a, $b) => strcmp($a['tanggal_janji'], $b['tanggal_janji']));
                foreach ($jbList as $jb):
                    $deb = vao_debitur_by_id($debiturs, $jb['debitur_id']);
                    $ao  = vao_ao_by_id($aos, $jb['ao_id']);
                    if (!$deb || !$ao) continue;
                    $isOverdue = $jb['status'] === 'aktif' && strtotime($jb['tanggal_janji']) < strtotime($today);
                    $isToday   = $jb['status'] === 'aktif' && $jb['tanggal_janji'] === $today;
                ?>
                    <tr class="border-t border-gray-50 hover:bg-surface/40 <?= $isOverdue ? 'bg-red-50/50' : '' ?>">
                        <td class="p-4 text-xs">
                            <p class="font-semibold text-textMain"><?= vao_fmt_tgl($jb['tanggal_janji']) ?></p>
                            <?php if ($isOverdue): ?>
                                <p class="text-[10px] text-red-600 font-bold mt-0.5">TERLEWAT</p>
                            <?php elseif ($isToday): ?>
                                <p class="text-[10px] text-amber-600 font-bold mt-0.5">HARI INI</p>
                            <?php endif; ?>
                        </td>
                        <td class="p-4">
                            <p class="font-semibold text-textMain"><?= htmlspecialchars($deb['nama']) ?></p>
                            <p class="text-[11px] text-textSub mt-0.5"><?= htmlspecialchars($deb['pemilik']) ?> • <?= $deb['no_rek'] ?></p>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">
                                    <?= $ao['inisial'] ?>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-textMain truncate"><?= htmlspecialchars($ao['nama']) ?></p>
                                    <p class="text-[10px] text-textSub"><?= vao_kantor_nama($kantors, $ao['kode_kantor']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full <?= vao_bucket_color($deb['dpd_bucket']) ?>"><?= vao_bucket_label($deb['dpd_bucket']) ?></span>
                        </td>
                        <td class="p-4 text-right font-semibold text-textMain"><?= vao_fmt_rp((int)$jb['nominal_janji']) ?></td>
                        <td class="p-4 text-right">
                            <?php if (!empty($jb['realisasi_nominal'])): ?>
                                <span class="font-semibold text-emerald-700"><?= vao_fmt_rp((int)$jb['realisasi_nominal']) ?></span>
                                <p class="text-[10px] text-textSub"><?= vao_fmt_tgl($jb['realisasi_tanggal']) ?></p>
                            <?php else: ?>
                                <span class="text-textSub">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-center">
                            <?php if ($jb['status'] === 'aktif' && $isOverdue): ?>
                                <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700">⚠ Terlewat</span>
                            <?php elseif ($jb['status'] === 'aktif'): ?>
                                <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Aktif</span>
                            <?php elseif ($jb['status'] === 'terealisasi'): ?>
                                <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">✓ Terealisasi</span>
                            <?php else: ?>
                                <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700">✗ Gagal</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-center">
                            <?php if ($jb['status'] === 'aktif'): ?>
                                <button class="text-[11px] bg-gray-100 text-textMain px-2.5 py-1 rounded-lg font-semibold hover:bg-gray-200 mr-1">Eskalasi</button>
                                <button class="text-[11px] bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-lg font-semibold hover:bg-emerald-200">Realisasi</button>
                            <?php else: ?>
                                <span class="text-[11px] text-textSub">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($jbList) === 0): ?>
                    <tr><td colspan="8" class="p-8 text-center text-sm text-textSub">Tidak ada janji bayar dengan filter ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
