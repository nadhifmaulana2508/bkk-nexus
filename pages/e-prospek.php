<?php
/**
 * Desktop E-Prospek
 * Status: reject, open, pending, submit
 * Submit kredit → otomatis masuk E-Pipelane
 * Form: dropdown kab/kec/kel, geolocation, upload foto
 * Rule: jika sudah diambil AO → tidak bisa delete, AO wajib isi data
 */

// Dummy data prospek
$prospekList = [
    [
        'id' => 'PRO-001', 'nama' => 'Toko Maju Jaya', 'pemilik' => 'Pak Budi',
        'telepon' => '081234567890', 'alamat' => 'Jl. Raya Bogor No. 45',
        'kabupaten' => 'Kota Bogor', 'kecamatan' => 'Bogor Tengah', 'kelurahan' => 'Panaragan',
        'produk' => 'Kredit Modal Kerja', 'nominal' => 250000000,
        'status' => 'open', 'score' => 92, 'ao' => 'Harry',
        'tanggal' => '2026-05-20', 'catatan' => 'Existing lancar, saldo < 50%',
        'lat' => '-6.5971', 'lng' => '106.7972', 'foto' => 'foto_toko_maju.jpg',
        'diambil_ao' => true,
        'data_ao' => ['jenis_usaha' => 'Perdagangan', 'lama_usaha' => '5 tahun', 'omzet' => 80000000, 'jaminan' => 'SHM Tanah'],
    ],
    [
        'id' => 'PRO-002', 'nama' => 'Sumber Barokah', 'pemilik' => 'Pak Ahmad',
        'telepon' => '081298765432', 'alamat' => 'Jl. Merdeka No. 12',
        'kabupaten' => 'Kota Bogor', 'kecamatan' => 'Bogor Selatan', 'kelurahan' => 'Bondongan',
        'produk' => 'KMK', 'nominal' => 150000000,
        'status' => 'pending', 'score' => 74, 'ao' => 'Harry',
        'tanggal' => '2026-05-18', 'catatan' => 'Prospek baru, usaha dagang',
        'lat' => '-6.6200', 'lng' => '106.8000', 'foto' => '',
        'diambil_ao' => true,
        'data_ao' => ['jenis_usaha' => 'Dagang', 'lama_usaha' => '3 tahun', 'omzet' => 50000000, 'jaminan' => ''],
    ],
    [
        'id' => 'PRO-003', 'nama' => 'CV Berkah Mandiri', 'pemilik' => 'Ibu Sari',
        'telepon' => '081377788899', 'alamat' => 'Jl. Sudirman No. 88',
        'kabupaten' => 'Kab. Bogor', 'kecamatan' => 'Cibinong', 'kelurahan' => 'Tengah',
        'produk' => 'Kredit Investasi', 'nominal' => 500000000,
        'status' => 'submit', 'score' => 88, 'ao' => 'Harry',
        'tanggal' => '2026-05-15', 'catatan' => 'Referral Kacab, kontraktor',
        'lat' => '-6.4800', 'lng' => '106.8500', 'foto' => 'foto_cv_berkah.jpg',
        'diambil_ao' => true,
        'data_ao' => ['jenis_usaha' => 'Kontraktor', 'lama_usaha' => '8 tahun', 'omzet' => 200000000, 'jaminan' => 'SHM + BPKB'],
    ],
    [
        'id' => 'PRO-004', 'nama' => 'UD Sejahtera', 'pemilik' => 'Pak Doni',
        'telepon' => '081255544433', 'alamat' => 'Jl. Gatot Subroto No. 5',
        'kabupaten' => 'Kota Bogor', 'kecamatan' => 'Bogor Utara', 'kelurahan' => 'Bantarjati',
        'produk' => 'KMK', 'nominal' => 100000000,
        'status' => 'reject', 'score' => 35, 'ao' => 'Rina',
        'tanggal' => '2026-05-10', 'catatan' => 'BI Checking tidak lolos',
        'lat' => '', 'lng' => '', 'foto' => '',
        'diambil_ao' => false,
        'data_ao' => [],
    ],
    [
        'id' => 'PRO-005', 'nama' => 'PT Karya Utama', 'pemilik' => 'Pak Joko',
        'telepon' => '081366677788', 'alamat' => 'Jl. Ahmad Yani No. 33',
        'kabupaten' => 'Kab. Bogor', 'kecamatan' => 'Gunung Putri', 'kelurahan' => 'Tlajung Udik',
        'produk' => 'Kredit Modal Kerja', 'nominal' => 300000000,
        'status' => 'open', 'score' => 80, 'ao' => '',
        'tanggal' => '2026-05-22', 'catatan' => 'Usaha berjalan 5 tahun, belum ada AO',
        'lat' => '', 'lng' => '', 'foto' => '',
        'diambil_ao' => false,
        'data_ao' => [],
    ],
];

$statusConfig = [
    'open'    => ['label' => 'Open', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'dot' => 'bg-blue-500'],
    'pending' => ['label' => 'Pending', 'bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'dot' => 'bg-amber-500'],
    'submit'  => ['label' => 'Submit', 'bg' => 'bg-green-100', 'text' => 'text-green-700', 'dot' => 'bg-green-500'],
    'reject'  => ['label' => 'Reject', 'bg' => 'bg-red-100', 'text' => 'text-red-700', 'dot' => 'bg-red-500'],
];

$countOpen = count(array_filter($prospekList, fn($p) => $p['status'] === 'open'));
$countPending = count(array_filter($prospekList, fn($p) => $p['status'] === 'pending'));
$countSubmit = count(array_filter($prospekList, fn($p) => $p['status'] === 'submit'));
$countReject = count(array_filter($prospekList, fn($p) => $p['status'] === 'reject'));
?>

<div class="animate-fade-in">

<!-- Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-textMain">E-Prospek</h2>
        <p class="text-sm text-textSub mt-1">Kelola data calon debitur dan prospek kredit</p>
    </div>
    <button onclick="openModal('modalTambah')" class="mt-3 md:mt-0 inline-flex items-center px-4 py-2.5 bg-primary text-white text-sm font-medium rounded-xl hover:bg-primary/90 shadow-md hover:shadow-lg transition-all">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Prospek
    </button>
</div>

<!-- Status Cards -->
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <button onclick="filterStatus('all')" class="filter-btn bg-white rounded-xl p-4 border border-gray-100 shadow-card hover:shadow-card-hover transition-all text-left ring-2 ring-primary ring-offset-2" data-filter="all">
        <p class="text-xs text-textSub font-medium">Total Prospek</p>
        <p class="text-2xl font-bold text-textMain mt-1"><?= count($prospekList) ?></p>
    </button>
    <button onclick="filterStatus('open')" class="filter-btn bg-white rounded-xl p-4 border border-gray-100 shadow-card hover:shadow-card-hover transition-all text-left" data-filter="open">
        <p class="text-xs text-textSub font-medium">Open</p>
        <p class="text-2xl font-bold text-blue-600 mt-1"><?= $countOpen ?></p>
    </button>
    <button onclick="filterStatus('pending')" class="filter-btn bg-white rounded-xl p-4 border border-gray-100 shadow-card hover:shadow-card-hover transition-all text-left" data-filter="pending">
        <p class="text-xs text-textSub font-medium">Pending</p>
        <p class="text-2xl font-bold text-amber-600 mt-1"><?= $countPending ?></p>
    </button>
    <button onclick="filterStatus('submit')" class="filter-btn bg-white rounded-xl p-4 border border-gray-100 shadow-card hover:shadow-card-hover transition-all text-left" data-filter="submit">
        <p class="text-xs text-textSub font-medium">Submit → Pipeline</p>
        <p class="text-2xl font-bold text-green-600 mt-1"><?= $countSubmit ?></p>
    </button>
</div>

<!-- Search & Filter -->
<div class="bg-white rounded-xl border border-gray-100 shadow-card p-4 mb-4">
    <div class="flex flex-col md:flex-row md:items-center gap-3">
        <div class="relative flex-1 md:max-w-xs">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" id="searchInput" onkeyup="searchProspek()" placeholder="Cari nama debitur..." class="w-full pl-9 pr-4 py-2 bg-surface border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
        </div>
        <select id="filterAO" onchange="filterByAO()" class="px-3 py-2 bg-surface border border-gray-200 rounded-lg text-sm">
            <option value="all">Semua AO</option>
            <option value="Harry">Harry</option>
            <option value="Rina">Rina</option>
        </select>
        <span id="activeFilterLabel" class="text-xs text-textSub bg-surface px-3 py-1.5 rounded-lg">Filter: Semua</span>
    </div>
</div>

<!-- Prospek Table -->
<div class="bg-white rounded-xl border border-gray-100 shadow-card overflow-hidden">
    <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-3 bg-surface border-b border-gray-100 text-xs font-semibold text-textSub uppercase tracking-wider">
        <div class="col-span-3">Debitur</div>
        <div class="col-span-2">Lokasi</div>
        <div class="col-span-2">Produk / Nominal</div>
        <div class="col-span-1">AO</div>
        <div class="col-span-1">Score</div>
        <div class="col-span-1">Status</div>
        <div class="col-span-2 text-right">Aksi</div>
    </div>

    <div id="prospekTableBody">
        <?php foreach ($prospekList as $p): ?>
            <?php $sc = $statusConfig[$p['status']]; ?>
            <?php 
                $scoreColor = $p['score'] >= 80 ? 'text-red-600 bg-red-50' : ($p['score'] >= 60 ? 'text-amber-600 bg-amber-50' : 'text-gray-600 bg-gray-100');
                $scoreLabel = $p['score'] >= 80 ? 'HOT' : ($p['score'] >= 60 ? 'WARM' : 'COLD');
            ?>
            <div class="prospek-row border-b border-gray-50 hover:bg-surface/50 transition-colors" 
                 data-status="<?= $p['status'] ?>" data-ao="<?= $p['ao'] ?>" data-nama="<?= strtolower($p['nama']) ?>"
                 data-id="<?= $p['id'] ?>" data-diambil="<?= $p['diambil_ao'] ? '1' : '0' ?>">
                <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 items-center">
                    <div class="col-span-3">
                        <p class="text-sm font-semibold text-textMain"><?= $p['nama'] ?></p>
                        <p class="text-xs text-textSub"><?= $p['pemilik'] ?> • <?= $p['telepon'] ?></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs text-textMain"><?= $p['kecamatan'] ?></p>
                        <p class="text-xs text-textSub"><?= $p['kabupaten'] ?></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm font-semibold text-textMain">Rp <?= number_format($p['nominal'] / 1000000, 0, ',', '.') ?> jt</p>
                        <p class="text-xs text-textSub"><?= $p['produk'] ?></p>
                    </div>
                    <div class="col-span-1">
                        <?php if ($p['ao']): ?>
                            <span class="text-xs font-medium text-primary bg-indigo-50 px-2 py-0.5 rounded-full"><?= $p['ao'] ?></span>
                        <?php else: ?>
                            <span class="text-xs text-gray-400 italic">Belum</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-span-1">
                        <span class="text-xs font-bold <?= $scoreColor ?> px-2 py-1 rounded-lg"><?= $scoreLabel ?></span>
                    </div>
                    <div class="col-span-1">
                        <span class="inline-flex items-center text-xs font-medium <?= $sc['bg'] ?> <?= $sc['text'] ?> px-2 py-0.5 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full <?= $sc['dot'] ?> mr-1"></span><?= $sc['label'] ?>
                        </span>
                    </div>
                    <div class="col-span-2 flex items-center justify-end space-x-1.5">
                        <button onclick='openDetailModal(<?= json_encode($p) ?>)' class="p-2 rounded-lg text-gray-500 hover:text-primary hover:bg-primary/5" title="Detail">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                        <?php if ($p['status'] === 'open' || $p['status'] === 'pending'): ?>
                        <button onclick="changeStatus('<?= $p['id'] ?>', 'submit')" class="px-2.5 py-1.5 text-xs font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 shadow-sm">Submit</button>
                        <?php endif; ?>
                        <?php if (!$p['diambil_ao'] && $p['status'] !== 'submit'): ?>
                        <button onclick="deleteProspek('<?= $p['id'] ?>')" class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        <?php else: ?>
                        <span class="p-2 text-gray-300" title="Tidak bisa dihapus (sudah diambil AO)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL: TAMBAH PROSPEK (dengan lokasi, foto) -->
<!-- ============================================ -->
<div id="modalTambah" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalTambah')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl bg-white rounded-2xl shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 rounded-t-2xl z-10">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-textMain">Tambah Prospek Baru</h3>
                <button onclick="closeModal('modalTambah')" class="p-2 rounded-lg hover:bg-gray-100 text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
        </div>
        <form id="formTambahProspek" onsubmit="submitProspek(event)" class="p-6">
            <!-- Section: Data Debitur -->
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-textMain mb-3 flex items-center">
                    <span class="w-6 h-6 bg-primary/10 text-primary rounded-full flex items-center justify-center text-xs font-bold mr-2">1</span>
                    Data Debitur
                </h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-textSub mb-1">Nama Usaha / Debitur <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="Contoh: Toko Maju Jaya">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-textSub mb-1">Pemilik <span class="text-red-500">*</span></label>
                            <input type="text" name="pemilik" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-textSub mb-1">No. Telepon <span class="text-red-500">*</span></label>
                            <input type="tel" name="telepon" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Lokasi -->
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-textMain mb-3 flex items-center">
                    <span class="w-6 h-6 bg-primary/10 text-primary rounded-full flex items-center justify-center text-xs font-bold mr-2">2</span>
                    Lokasi
                </h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-textSub mb-1">Alamat Lengkap</label>
                        <input type="text" name="alamat" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="Jl. Raya ...">
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-textSub mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
                            <select name="kabupaten" id="selKab" required onchange="loadKecamatan()" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="">Pilih Kab/Kota</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-textSub mb-1">Kecamatan <span class="text-red-500">*</span></label>
                            <select name="kecamatan" id="selKec" required onchange="loadKelurahan()" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-textSub mb-1">Kelurahan <span class="text-red-500">*</span></label>
                            <select name="kelurahan" id="selKel" required class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="">Pilih Kelurahan</option>
                            </select>
                        </div>
                    </div>
                    <!-- Auto Geolocation -->
                    <div class="flex items-center space-x-3">
                        <button type="button" onclick="getLocation()" class="inline-flex items-center px-4 py-2 bg-green-50 text-green-700 text-xs font-medium rounded-lg hover:bg-green-100 transition-colors border border-green-200">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Ambil Lokasi Otomatis
                        </button>
                        <span id="locationStatus" class="text-xs text-textSub"></span>
                        <input type="hidden" name="latitude" id="inputLat">
                        <input type="hidden" name="longitude" id="inputLng">
                    </div>
                </div>
            </div>

            <!-- Section: Produk & Kredit -->
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-textMain mb-3 flex items-center">
                    <span class="w-6 h-6 bg-primary/10 text-primary rounded-full flex items-center justify-center text-xs font-bold mr-2">3</span>
                    Produk Kredit
                </h4>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-textSub mb-1">Produk <span class="text-red-500">*</span></label>
                        <select name="produk" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="">Pilih produk</option>
                            <option>Kredit Modal Kerja</option>
                            <option>Kredit Investasi</option>
                            <option>KMK</option>
                            <option>KPR</option>
                            <option>Kredit Multiguna</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-textSub mb-1">Nominal (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="nominal" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="250000000">
                    </div>
                </div>
            </div>

            <!-- Section: Upload Foto -->
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-textMain mb-3 flex items-center">
                    <span class="w-6 h-6 bg-primary/10 text-primary rounded-full flex items-center justify-center text-xs font-bold mr-2">4</span>
                    Foto Lokasi / Usaha
                </h4>
                <div id="uploadArea" class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-primary/50 transition-colors cursor-pointer" onclick="document.getElementById('inputFoto').click()">
                    <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <p class="text-sm text-textSub">Klik untuk upload foto</p>
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG max 5MB</p>
                </div>
                <input type="file" id="inputFoto" name="foto" accept="image/*" class="hidden" onchange="previewFoto(this)">
                <div id="fotoPreview" class="mt-3 hidden">
                    <img id="fotoImg" class="w-32 h-32 object-cover rounded-xl border border-gray-200" src="" alt="Preview">
                    <button type="button" onclick="removeFoto()" class="text-xs text-red-500 mt-1 hover:underline">Hapus foto</button>
                </div>
            </div>

            <!-- Section: Catatan -->
            <div class="mb-6">
                <label class="block text-xs font-medium text-textSub mb-1">Catatan</label>
                <textarea name="catatan" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary resize-none" placeholder="Catatan tambahan..."></textarea>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal('modalTambah')" class="px-4 py-2.5 text-sm font-medium text-textSub bg-gray-100 rounded-xl hover:bg-gray-200">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-primary rounded-xl hover:bg-primary/90 shadow-md">Simpan Prospek</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL: DETAIL PROSPEK + DATA AO             -->
<!-- ============================================ -->
<div id="modalDetail" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalDetail')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-2xl shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 rounded-t-2xl z-10">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-textMain">Detail Prospek</h3>
                <button onclick="closeModal('modalDetail')" class="p-2 rounded-lg hover:bg-gray-100 text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
        </div>
        <div id="detailContent" class="p-6">
            <!-- Filled by JS -->
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast" class="fixed top-6 right-6 z-[60] hidden">
    <div class="bg-white border border-gray-100 rounded-xl shadow-xl px-5 py-4 flex items-center space-x-3">
        <div id="toastIcon" class="w-8 h-8 rounded-full flex items-center justify-center"></div>
        <div>
            <p id="toastTitle" class="text-sm font-semibold text-textMain"></p>
            <p id="toastMsg" class="text-xs text-textSub"></p>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- JAVASCRIPT                                  -->
<!-- ============================================ -->
<script>
// ========== DUMMY DATA WILAYAH ==========
const wilayahData = {
    kabupaten: [
        { id: '3271', nama: 'Kota Bogor' },
        { id: '3201', nama: 'Kab. Bogor' },
        { id: '3275', nama: 'Kota Bekasi' },
        { id: '3216', nama: 'Kab. Bekasi' },
        { id: '3273', nama: 'Kota Bandung' },
    ],
    kecamatan: {
        '3271': [
            { id: '327101', nama: 'Bogor Selatan' },
            { id: '327102', nama: 'Bogor Timur' },
            { id: '327103', nama: 'Bogor Utara' },
            { id: '327104', nama: 'Bogor Tengah' },
            { id: '327105', nama: 'Bogor Barat' },
            { id: '327106', nama: 'Tanah Sareal' },
        ],
        '3201': [
            { id: '320101', nama: 'Cibinong' },
            { id: '320102', nama: 'Gunung Putri' },
            { id: '320103', nama: 'Cileungsi' },
            { id: '320104', nama: 'Jonggol' },
            { id: '320105', nama: 'Parung' },
        ],
        '3275': [
            { id: '327501', nama: 'Bekasi Timur' },
            { id: '327502', nama: 'Bekasi Barat' },
            { id: '327503', nama: 'Bekasi Utara' },
            { id: '327504', nama: 'Bekasi Selatan' },
        ],
        '3216': [
            { id: '321601', nama: 'Cikarang Utara' },
            { id: '321602', nama: 'Cikarang Selatan' },
            { id: '321603', nama: 'Tambun Selatan' },
        ],
        '3273': [
            { id: '327301', nama: 'Coblong' },
            { id: '327302', nama: 'Cicendo' },
            { id: '327303', nama: 'Bandung Wetan' },
        ],
    },
    kelurahan: {
        '327101': ['Bondongan', 'Batutulis', 'Empang', 'Lawang Gintung'],
        '327102': ['Baranangsiang', 'Sukasari', 'Tajur'],
        '327103': ['Bantarjati', 'Tegalgundil', 'Tanah Baru', 'Cimahpar'],
        '327104': ['Panaragan', 'Cibogor', 'Babakan', 'Sempur'],
        '327105': ['Menteng', 'Sindang Barang', 'Bubulak'],
        '327106': ['Kedung Waringin', 'Sukaresmi', 'Sukadamai'],
        '320101': ['Tengah', 'Cirimekar', 'Ciriung', 'Pakansari'],
        '320102': ['Tlajung Udik', 'Bojong Nangka', 'Cicadas'],
        '320103': ['Cileungsi', 'Jatisari', 'Cipeucang'],
        '320104': ['Jonggol', 'Sukajaya', 'Sukamanah'],
        '320105': ['Parung', 'Waru', 'Iwul'],
        '327501': ['Margahayu', 'Bekasi Jaya', 'Duren Jaya'],
        '327502': ['Bintara', 'Kranji', 'Kota Baru'],
        '327503': ['Harapan Jaya', 'Kaliabang', 'Perwira'],
        '327504': ['Jaka Setia', 'Jaka Mulya', 'Pekayon Jaya'],
        '321601': ['Karangraharja', 'Cikarang Kota'],
        '321602': ['Serang', 'Sukadami'],
        '321603': ['Tambun', 'Mangunjaya'],
        '327301': ['Dago', 'Lebak Siliwangi', 'Sadang Serang'],
        '327302': ['Pajajaran', 'Husein Sastranegara'],
        '327303': ['Cihapit', 'Tamansari'],
    }
};

// Load kabupaten on page load
document.addEventListener('DOMContentLoaded', function() {
    const selKab = document.getElementById('selKab');
    if (selKab) {
        wilayahData.kabupaten.forEach(k => {
            selKab.innerHTML += `<option value="${k.id}">${k.nama}</option>`;
        });
    }
});

function loadKecamatan() {
    const kabId = document.getElementById('selKab').value;
    const selKec = document.getElementById('selKec');
    const selKel = document.getElementById('selKel');
    selKec.innerHTML = '<option value="">Pilih Kecamatan</option>';
    selKel.innerHTML = '<option value="">Pilih Kelurahan</option>';
    if (kabId && wilayahData.kecamatan[kabId]) {
        wilayahData.kecamatan[kabId].forEach(k => {
            selKec.innerHTML += `<option value="${k.id}">${k.nama}</option>`;
        });
    }
}

function loadKelurahan() {
    const kecId = document.getElementById('selKec').value;
    const selKel = document.getElementById('selKel');
    selKel.innerHTML = '<option value="">Pilih Kelurahan</option>';
    if (kecId && wilayahData.kelurahan[kecId]) {
        wilayahData.kelurahan[kecId].forEach(k => {
            selKel.innerHTML += `<option value="${k}">${k}</option>`;
        });
    }
}

// ========== GEOLOCATION ==========
function getLocation() {
    const status = document.getElementById('locationStatus');
    status.textContent = '📍 Mengambil lokasi...';
    status.className = 'text-xs text-amber-600';

    if (!navigator.geolocation) {
        status.textContent = '❌ Browser tidak support geolocation';
        status.className = 'text-xs text-red-500';
        return;
    }

    navigator.geolocation.getCurrentPosition(
        (pos) => {
            document.getElementById('inputLat').value = pos.coords.latitude.toFixed(6);
            document.getElementById('inputLng').value = pos.coords.longitude.toFixed(6);
            status.textContent = `✅ ${pos.coords.latitude.toFixed(4)}, ${pos.coords.longitude.toFixed(4)}`;
            status.className = 'text-xs text-green-600 font-medium';
        },
        (err) => {
            status.textContent = '❌ Gagal: ' + err.message;
            status.className = 'text-xs text-red-500';
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );
}

// ========== UPLOAD FOTO ==========
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('fotoImg').src = e.target.result;
            document.getElementById('fotoPreview').classList.remove('hidden');
            document.getElementById('uploadArea').classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function removeFoto() {
    document.getElementById('inputFoto').value = '';
    document.getElementById('fotoPreview').classList.add('hidden');
    document.getElementById('uploadArea').classList.remove('hidden');
}

// ========== FILTER & SEARCH ==========
let currentFilter = 'all';

function filterStatus(status) {
    currentFilter = status;
    applyFilters();
    const labels = { all: 'Semua', open: 'Open', pending: 'Pending', submit: 'Submit', reject: 'Reject' };
    document.getElementById('activeFilterLabel').textContent = 'Filter: ' + labels[status];
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('ring-2', 'ring-primary', 'ring-offset-2');
        if (btn.dataset.filter === status) btn.classList.add('ring-2', 'ring-primary', 'ring-offset-2');
    });
}

function filterByAO() { applyFilters(); }
function searchProspek() { applyFilters(); }

function applyFilters() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const aoFilter = document.getElementById('filterAO').value;
    document.querySelectorAll('.prospek-row').forEach(row => {
        const matchStatus = (currentFilter === 'all' || row.dataset.status === currentFilter);
        const matchAO = (aoFilter === 'all' || row.dataset.ao === aoFilter);
        const matchSearch = row.dataset.nama.includes(query);
        row.style.display = (matchStatus && matchAO && matchSearch) ? '' : 'none';
    });
}

// ========== MODAL ==========
function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.body.style.overflow = ''; }

// ========== DETAIL MODAL ==========
function openDetailModal(data) {
    const content = document.getElementById('detailContent');
    const sc = { open: 'bg-blue-100 text-blue-700', pending: 'bg-amber-100 text-amber-700', submit: 'bg-green-100 text-green-700', reject: 'bg-red-100 text-red-700' };
    
    let aoDataHtml = '';
    if (data.diambil_ao && data.data_ao && Object.keys(data.data_ao).length > 0) {
        aoDataHtml = `
            <div class="mt-4 p-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                <h4 class="text-sm font-bold text-primary mb-3 flex items-center">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Data Wajib AO (sudah diisi)
                </h4>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div><p class="text-xs text-textSub">Jenis Usaha</p><p class="font-medium text-textMain">${data.data_ao.jenis_usaha || '-'}</p></div>
                    <div><p class="text-xs text-textSub">Lama Usaha</p><p class="font-medium text-textMain">${data.data_ao.lama_usaha || '-'}</p></div>
                    <div><p class="text-xs text-textSub">Omzet/bulan</p><p class="font-medium text-textMain">Rp ${data.data_ao.omzet ? (data.data_ao.omzet/1000000).toFixed(0) + ' jt' : '-'}</p></div>
                    <div><p class="text-xs text-textSub">Jaminan</p><p class="font-medium text-textMain">${data.data_ao.jaminan || '-'}</p></div>
                </div>
            </div>`;
    } else if (data.diambil_ao) {
        aoDataHtml = `
            <div class="mt-4 p-4 bg-amber-50 border border-amber-100 rounded-xl">
                <h4 class="text-sm font-bold text-amber-700 mb-1">⚠️ Data AO Belum Lengkap</h4>
                <p class="text-xs text-textSub">AO wajib mengisi: Jenis Usaha, Lama Usaha, Omzet, Jaminan</p>
                <button onclick="closeModal('modalDetail')" class="mt-2 px-3 py-1.5 text-xs font-medium bg-primary text-white rounded-lg">Isi Sekarang</button>
            </div>`;
    } else {
        aoDataHtml = `
            <div class="mt-4 p-4 bg-gray-50 border border-gray-100 rounded-xl">
                <p class="text-xs text-textSub">Prospek belum diambil oleh AO. Data wajib AO akan muncul setelah didelegasikan.</p>
            </div>`;
    }

    content.innerHTML = `
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-xs text-textSub">${data.id}</p>
                <h4 class="text-lg font-bold text-textMain">${data.nama}</h4>
                <p class="text-sm text-textSub">${data.pemilik} • ${data.telepon}</p>
            </div>
            <span class="text-xs font-medium px-2.5 py-1 rounded-full ${sc[data.status] || ''}">${data.status.toUpperCase()}</span>
        </div>
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div class="bg-surface rounded-xl p-3">
                <p class="text-xs text-textSub">Produk</p>
                <p class="font-medium text-textMain">${data.produk}</p>
            </div>
            <div class="bg-surface rounded-xl p-3">
                <p class="text-xs text-textSub">Nominal</p>
                <p class="font-medium text-textMain">Rp ${(data.nominal/1000000).toFixed(0)} jt</p>
            </div>
            <div class="bg-surface rounded-xl p-3">
                <p class="text-xs text-textSub">Lokasi</p>
                <p class="font-medium text-textMain">${data.kecamatan}, ${data.kabupaten}</p>
            </div>
            <div class="bg-surface rounded-xl p-3">
                <p class="text-xs text-textSub">AO</p>
                <p class="font-medium text-textMain">${data.ao || 'Belum ada'}</p>
            </div>
            <div class="bg-surface rounded-xl p-3">
                <p class="text-xs text-textSub">Koordinat</p>
                <p class="font-medium text-textMain">${data.lat && data.lng ? data.lat + ', ' + data.lng : 'Belum ada'}</p>
            </div>
            <div class="bg-surface rounded-xl p-3">
                <p class="text-xs text-textSub">Tanggal Input</p>
                <p class="font-medium text-textMain">${data.tanggal}</p>
            </div>
        </div>
        <div class="mt-3 bg-surface rounded-xl p-3">
            <p class="text-xs text-textSub">Catatan</p>
            <p class="text-sm text-textMain">${data.catatan || '-'}</p>
        </div>
        ${data.foto ? '<div class="mt-3"><p class="text-xs text-textSub mb-1">Foto</p><div class="w-full h-32 bg-gray-200 rounded-xl flex items-center justify-center text-xs text-gray-500">📷 ' + data.foto + '</div></div>' : ''}
        ${aoDataHtml}
        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
            <div>
                ${!data.diambil_ao ? '<button onclick="deleteProspek(\'' + data.id + '\'); closeModal(\'modalDetail\');" class="text-xs text-red-500 hover:underline">🗑️ Hapus Prospek</button>' : '<span class="text-xs text-gray-400">🔒 Tidak bisa dihapus</span>'}
            </div>
            <div class="flex space-x-2">
                ${(data.status === 'open' || data.status === 'pending') ? '<button onclick="changeStatus(\'' + data.id + '\', \'submit\'); closeModal(\'modalDetail\');" class="px-4 py-2 text-xs font-medium bg-green-600 text-white rounded-lg shadow-sm">Submit ke Pipeline</button>' : ''}
            </div>
        </div>
    `;
    openModal('modalDetail');
}

// ========== ACTIONS ==========
function changeStatus(id, newStatus) {
    if (newStatus === 'submit' && !confirm('Submit prospek ' + id + ' ke E-Pipelane?')) return;
    if (newStatus === 'reject' && !confirm('Reject prospek ' + id + '?')) return;

    const reqBody = {
        prospek_id: id,
        new_status: newStatus,
        ao: '<?= $userName ?>',
        role: '<?= $userRole ?>',
        timestamp: new Date().toISOString()
    };
    console.log('📤 Change Status:', JSON.stringify(reqBody, null, 2));
    showToast('success', newStatus === 'submit' ? 'Submit Berhasil' : 'Status Diubah', id + (newStatus === 'submit' ? ' masuk E-Pipelane' : ' → ' + newStatus));
}

function deleteProspek(id) {
    if (!confirm('Hapus prospek ' + id + '? Aksi ini tidak bisa dibatalkan.')) return;
    const reqBody = { prospek_id: id, action: 'delete', ao: '<?= $userName ?>', role: '<?= $userRole ?>', timestamp: new Date().toISOString() };
    console.log('📤 Delete Prospek:', JSON.stringify(reqBody, null, 2));
    showToast('danger', 'Prospek Dihapus', id + ' berhasil dihapus');
}

function submitProspek(e) {
    e.preventDefault();
    const form = e.target;
    const fd = new FormData(form);
    const reqBody = {
        nama: fd.get('nama'),
        pemilik: fd.get('pemilik'),
        telepon: fd.get('telepon'),
        alamat: fd.get('alamat'),
        kabupaten: document.getElementById('selKab').selectedOptions[0]?.text || '',
        kecamatan: document.getElementById('selKec').selectedOptions[0]?.text || '',
        kelurahan: fd.get('kelurahan'),
        latitude: fd.get('latitude'),
        longitude: fd.get('longitude'),
        produk: fd.get('produk'),
        nominal: parseInt(fd.get('nominal')),
        foto: fd.get('foto')?.name || null,
        catatan: fd.get('catatan'),
        ao: '<?= $userName ?>',
        role: '<?= $userRole ?>',
        status: 'open',
        timestamp: new Date().toISOString()
    };
    console.log('📤 Tambah Prospek:', JSON.stringify(reqBody, null, 2));
    showToast('success', 'Prospek Ditambahkan', reqBody.nama + ' berhasil disimpan');
    closeModal('modalTambah');
    form.reset();
}

// ========== TOAST ==========
function showToast(type, title, msg) {
    const toast = document.getElementById('toast');
    document.getElementById('toastTitle').textContent = title;
    document.getElementById('toastMsg').textContent = msg;
    const icon = document.getElementById('toastIcon');
    icon.className = 'w-8 h-8 rounded-full flex items-center justify-center ' + (type === 'success' ? 'bg-green-100' : 'bg-red-100');
    icon.innerHTML = type === 'success'
        ? '<svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
        : '<svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3000);
}
</script>

</div><!-- end .animate-fade-in -->
