<?php
/**
 * Mobile E-Prospek - List dengan filter (status, produk, score) + search
 */

$filterStatus = $_GET['status'] ?? 'all';
$filterProduk = $_GET['produk'] ?? 'all';
$filterScore  = $_GET['score']  ?? 'all';
$query        = trim($_GET['q'] ?? '');

$listed = $myProspeks;
if ($filterStatus !== 'all') {
    $listed = array_values(array_filter($listed, fn($p) => $p['status'] === $filterStatus));
}
if ($filterProduk !== 'all') {
    $listed = array_values(array_filter($listed, fn($p) => $p['produk_jenis'] === $filterProduk));
}
if ($filterScore !== 'all') {
    $listed = array_values(array_filter($listed, function ($p) use ($filterScore) {
        if ($filterScore === 'hot')  return $p['score'] >= 80;
        if ($filterScore === 'warm') return $p['score'] >= 60 && $p['score'] < 80;
        if ($filterScore === 'cold') return $p['score'] < 60;
        return true;
    }));
}
if ($query !== '') {
    $listed = array_values(array_filter($listed, fn($p) =>
        stripos($p['nama'], $query) !== false ||
        stripos($p['pemilik'], $query) !== false ||
        stripos($p['telp'], $query) !== false
    ));
}

$statusCounts = ['all' => count($myProspeks)];
foreach ($myProspeks as $p) {
    $statusCounts[$p['status']] = ($statusCounts[$p['status']] ?? 0) + 1;
}
?>

<!-- Header -->
<div class="px-4 -mt-4 relative z-10 animate-fade-in">
    <div class="bg-white rounded-2xl shadow-card-hover p-4">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="text-sm font-bold text-textMain">Prospek Saya</p>
                <p class="text-xs text-textSub mt-0.5"><?= count($myProspeks) ?> prospek total</p>
            </div>
            <a href="<?= BASE_URL ?>e-prospek?tab=form" class="bg-primary text-white text-xs font-semibold px-3 py-2 rounded-xl inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah
            </a>
        </div>
        <form method="GET" action="" class="relative">
            <input type="hidden" name="page" value="e-prospek">
            <input type="hidden" name="tab" value="list">
            <?php foreach (['status','produk','score'] as $hf): if (($_GET[$hf] ?? 'all') !== 'all'): ?>
                <input type="hidden" name="<?= $hf ?>" value="<?= htmlspecialchars($_GET[$hf]) ?>">
            <?php endif; endforeach; ?>
            <input type="search" name="q" value="<?= htmlspecialchars($query) ?>" placeholder="Cari nama, pemilik, atau no telp…"
                   class="w-full bg-surface border border-gray-200 rounded-xl pl-10 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </form>
    </div>
</div>

<!-- Status Filter Tabs -->
<div class="px-4 mt-4 animate-fade-in">
    <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide pb-1">
        <?php
        $statusTabs = [
            'all'       => 'Semua',
            'open'      => 'Open',
            'pending'   => 'Pending',
            'submit'    => 'Submitted',
            'realisasi' => 'Realisasi',
            'reject'    => 'Ditolak',
        ];
        foreach ($statusTabs as $key => $label):
            $count = $statusCounts[$key] ?? 0;
            $isActive = $filterStatus === $key;
            $url = BASE_URL . 'e-prospek?tab=list' . ($key !== 'all' ? '&status=' . $key : '');
        ?>
            <a href="<?= $url ?>"
               class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold transition-colors
                      <?= $isActive ? 'bg-primary text-white shadow-sm' : 'bg-white text-textSub border border-gray-200' ?>">
                <?= $label ?>
                <span class="<?= $isActive ? 'bg-white/25' : 'bg-gray-100' ?> px-1.5 py-0.5 rounded-full text-[10px]"><?= $count ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- Produk + Score chips -->
<div class="px-4 mt-3 flex flex-wrap gap-2 animate-fade-in">
    <?php
    $produkChips = ['all'=>'Semua Produk','tabungan'=>'Tabungan','deposito'=>'Deposito','kredit'=>'Kredit','aset'=>'Aset'];
    foreach ($produkChips as $k => $lbl):
        $isActive = $filterProduk === $k;
        $params = $_GET; $params['tab']='list';
        if ($k === 'all') unset($params['produk']); else $params['produk']=$k;
        $url = BASE_URL.'e-prospek?'.http_build_query($params);
    ?>
        <a href="<?= $url ?>" class="text-[11px] px-2.5 py-1 rounded-full font-semibold <?= $isActive ? 'bg-secondary text-white' : 'bg-white text-textSub border border-gray-200' ?>">
            <?= $lbl ?>
        </a>
    <?php endforeach; ?>

    <span class="w-full"></span>

    <?php
    $scoreChips = ['all'=>'Semua Score','hot'=>'🔥 HOT','warm'=>'🟠 WARM','cold'=>'❄️ COLD'];
    foreach ($scoreChips as $k => $lbl):
        $isActive = $filterScore === $k;
        $params = $_GET; $params['tab']='list';
        if ($k === 'all') unset($params['score']); else $params['score']=$k;
        $url = BASE_URL.'e-prospek?'.http_build_query($params);
    ?>
        <a href="<?= $url ?>" class="text-[11px] px-2.5 py-1 rounded-full font-semibold <?= $isActive ? 'bg-secondary text-white' : 'bg-white text-textSub border border-gray-200' ?>">
            <?= $lbl ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- List -->
<div class="px-4 mt-4 pb-6 animate-fade-in">
    <?php if (count($listed) === 0): ?>
        <div class="bg-white rounded-2xl shadow-card p-6 border border-gray-50 text-center">
            <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm font-semibold text-textMain">Tidak ada prospek</p>
            <p class="text-xs text-textSub mt-1">Coba ubah filter atau pencarian.</p>
        </div>
    <?php else: ?>
        <div class="space-y-2.5">
            <?php foreach ($listed as $p): ?>
                <a href="<?= BASE_URL ?>e-prospek?tab=detail&id=<?= $p['id'] ?>" class="block bg-white rounded-2xl shadow-card p-4 border border-gray-50 hover:shadow-card-hover transition-shadow">
                    <div class="flex items-start gap-3">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                            <?= strtoupper(substr($p['nama'], 0, 2)) ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-textMain truncate"><?= htmlspecialchars($p['nama']) ?></p>
                                    <p class="text-xs text-textSub mt-0.5 truncate"><?= htmlspecialchars($p['pemilik']) ?> • <?= $p['telp'] ?></p>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full <?= ep_status_color($p['status']) ?> flex-shrink-0">
                                    <?= ep_status_label($p['status']) ?>
                                </span>
                            </div>
                            <div class="flex items-center justify-between gap-2 mt-2">
                                <div class="flex items-center gap-2 flex-wrap min-w-0">
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full <?= ep_produk_color($p['produk_jenis']) ?>">
                                        <?= ep_produk_label($p['produk_jenis']) ?>
                                    </span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full <?= ep_score_color($p['score']) ?>">
                                        <?= ep_score_label($p['score']) ?> <?= $p['score'] ?>%
                                    </span>
                                </div>
                                <p class="text-sm font-bold text-textMain whitespace-nowrap"><?= vao_fmt_rp((int)$p['nominal']) ?></p>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
