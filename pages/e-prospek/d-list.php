<?php
/**
 * Desktop E-Prospek - Semua Prospek (table view)
 */

$filterStatus = $_GET['status'] ?? 'all';
$filterProduk = $_GET['produk'] ?? 'all';
$filterScore  = $_GET['score']  ?? 'all';

$listed = $prospekFiltered;
if ($filterStatus !== 'all') $listed = array_values(array_filter($listed, fn($p) => $p['status'] === $filterStatus));
if ($filterProduk !== 'all') $listed = array_values(array_filter($listed, fn($p) => $p['produk_jenis'] === $filterProduk));
if ($filterScore !== 'all') {
    $listed = array_values(array_filter($listed, function ($p) use ($filterScore) {
        if ($filterScore === 'hot')  return $p['score'] >= 80;
        if ($filterScore === 'warm') return $p['score'] >= 60 && $p['score'] < 80;
        if ($filterScore === 'cold') return $p['score'] < 60;
        return true;
    }));
}
?>

<!-- Sub-filter chips -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 mb-4 px-4 py-3 flex flex-wrap items-center gap-2">
    <span class="text-xs font-semibold text-textSub uppercase tracking-wider mr-2">Status:</span>
    <?php
    $statusFilters = ['all'=>'Semua','open'=>'Open','pending'=>'Pending','submit'=>'Submit','realisasi'=>'Realisasi','reject'=>'Reject'];
    foreach ($statusFilters as $k => $lbl):
        $isActive = $filterStatus === $k;
        $params = $_GET; $params['tab']='list';
        if ($k === 'all') unset($params['status']); else $params['status']=$k;
        $url = BASE_URL.'e-prospek?'.http_build_query($params);
    ?>
        <a href="<?= $url ?>" class="text-xs px-3 py-1.5 rounded-lg font-semibold <?= $isActive ? 'bg-primary text-white' : 'bg-gray-100 text-textSub hover:bg-gray-200' ?>"><?= $lbl ?></a>
    <?php endforeach; ?>

    <span class="mx-3 text-gray-300">|</span>
    <span class="text-xs font-semibold text-textSub uppercase tracking-wider mr-2">Produk:</span>
    <?php
    $produkFilters = ['all'=>'Semua','tabungan'=>'Tabungan','deposito'=>'Deposito','kredit'=>'Kredit','aset'=>'Aset'];
    foreach ($produkFilters as $k => $lbl):
        $isActive = $filterProduk === $k;
        $params = $_GET; $params['tab']='list';
        if ($k === 'all') unset($params['produk']); else $params['produk']=$k;
        $url = BASE_URL.'e-prospek?'.http_build_query($params);
    ?>
        <a href="<?= $url ?>" class="text-xs px-3 py-1.5 rounded-lg font-semibold <?= $isActive ? 'bg-secondary text-white' : 'bg-gray-100 text-textSub hover:bg-gray-200' ?>"><?= $lbl ?></a>
    <?php endforeach; ?>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-base font-bold text-textMain">Daftar Prospek <span class="text-textSub font-normal">(<?= count($listed) ?>)</span></h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-surface">
                <tr class="text-xs text-textSub uppercase tracking-wider">
                    <th class="text-left p-4 font-semibold">Tgl Input</th>
                    <th class="text-left p-4 font-semibold">Prospek</th>
                    <th class="text-left p-4 font-semibold">Cabang</th>
                    <th class="text-left p-4 font-semibold">AO</th>
                    <th class="text-center p-4 font-semibold">Produk</th>
                    <th class="text-right p-4 font-semibold">Nominal</th>
                    <th class="text-center p-4 font-semibold">Score</th>
                    <th class="text-center p-4 font-semibold">Status</th>
                    <th class="text-center p-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listed as $p):
                    $ao = $p['ao_id'] ? vao_ao_by_id($aos, $p['ao_id']) : null;
                ?>
                    <tr class="border-t border-gray-50 hover:bg-surface/40">
                        <td class="p-4 text-xs text-textSub"><?= htmlspecialchars(substr($p['created_at'], 0, 10)) ?></td>
                        <td class="p-4">
                            <p class="font-semibold text-textMain"><?= htmlspecialchars($p['nama']) ?></p>
                            <p class="text-[11px] text-textSub mt-0.5"><?= htmlspecialchars($p['pemilik']) ?> • <?= $p['telp'] ?></p>
                        </td>
                        <td class="p-4 text-xs"><?= htmlspecialchars(vao_kantor_nama($kantors, $p['kode_kantor'])) ?></td>
                        <td class="p-4 text-xs"><?= $ao ? htmlspecialchars($ao['nama']) : '<span class="text-amber-600">⚠ Belum</span>' ?></td>
                        <td class="p-4 text-center">
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full <?= ep_produk_color($p['produk_jenis']) ?>"><?= ep_produk_label($p['produk_jenis']) ?></span>
                            <p class="text-[10px] text-textSub mt-0.5"><?= htmlspecialchars($p['produk_sub'] ?? '') ?></p>
                        </td>
                        <td class="p-4 text-right font-bold text-textMain"><?= vao_fmt_rp((int)$p['nominal']) ?></td>
                        <td class="p-4 text-center">
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full <?= ep_score_color($p['score']) ?>"><?= ep_score_label($p['score']) ?> <?= $p['score'] ?>%</span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full <?= ep_status_color($p['status']) ?>"><?= ep_status_label($p['status']) ?></span>
                        </td>
                        <td class="p-4 text-center">
                            <a href="<?= BASE_URL ?>e-prospek?tab=list&detail=<?= $p['id'] ?>" class="text-[11px] bg-gray-100 text-textMain px-2.5 py-1 rounded-lg font-semibold hover:bg-gray-200">Detail</a>
                            <?php if ($p['status'] === 'submit' && $p['produk_jenis'] === 'kredit'): ?>
                                <a href="<?= BASE_URL ?>e-pipelane?tab=detail&id=<?= $p['id'] ?>" class="text-[11px] bg-primary/10 text-primary px-2.5 py-1 rounded-lg font-semibold ml-1">Pipeline</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($listed) === 0): ?>
                    <tr><td colspan="9" class="p-8 text-center text-sm text-textSub">Tidak ada prospek dengan filter ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
