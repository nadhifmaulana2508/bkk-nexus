<?php
/**
 * Mobile E-Prospek
 */
$prospekList = [
    ['id' => 'PRO-001', 'nama' => 'Toko Maju Jaya', 'pemilik' => 'Pak Budi', 'telepon' => '081234567890', 'produk' => 'Kredit Modal Kerja', 'nominal' => 250000000, 'status' => 'open', 'score' => 92, 'ao' => 'Harry', 'tanggal' => '2026-05-20', 'kecamatan' => 'Bogor Tengah', 'diambil_ao' => true],
    ['id' => 'PRO-002', 'nama' => 'Sumber Barokah', 'pemilik' => 'Pak Ahmad', 'telepon' => '081298765432', 'produk' => 'KMK', 'nominal' => 150000000, 'status' => 'pending', 'score' => 74, 'ao' => 'Harry', 'tanggal' => '2026-05-18', 'kecamatan' => 'Bogor Selatan', 'diambil_ao' => true],
    ['id' => 'PRO-003', 'nama' => 'CV Berkah Mandiri', 'pemilik' => 'Ibu Sari', 'telepon' => '081377788899', 'produk' => 'Kredit Investasi', 'nominal' => 500000000, 'status' => 'submit', 'score' => 88, 'ao' => 'Harry', 'tanggal' => '2026-05-15', 'kecamatan' => 'Cibinong', 'diambil_ao' => true],
    ['id' => 'PRO-004', 'nama' => 'UD Sejahtera', 'pemilik' => 'Pak Doni', 'telepon' => '081255544433', 'produk' => 'KMK', 'nominal' => 100000000, 'status' => 'reject', 'score' => 35, 'ao' => 'Rina', 'tanggal' => '2026-05-10', 'kecamatan' => 'Bogor Utara', 'diambil_ao' => false],
    ['id' => 'PRO-005', 'nama' => 'PT Karya Utama', 'pemilik' => 'Pak Joko', 'telepon' => '081366677788', 'produk' => 'Kredit Modal Kerja', 'nominal' => 300000000, 'status' => 'open', 'score' => 80, 'ao' => '', 'tanggal' => '2026-05-22', 'kecamatan' => 'Gunung Putri', 'diambil_ao' => false],
];
$statusConfig = [
    'open' => ['label' => 'Open', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'dot' => 'bg-blue-500'],
    'pending' => ['label' => 'Pending', 'bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'dot' => 'bg-amber-500'],
    'submit' => ['label' => 'Submit', 'bg' => 'bg-green-100', 'text' => 'text-green-700', 'dot' => 'bg-green-500'],
    'reject' => ['label' => 'Reject', 'bg' => 'bg-red-100', 'text' => 'text-red-700', 'dot' => 'bg-red-500'],
];
?>

<!-- Filter Tabs -->
<div class="px-4 pt-4 animate-fade-in">
    <div class="flex items-center space-x-2 overflow-x-auto pb-2 scrollbar-hide">
        <button onclick="mFilter('all')" class="m-filter-btn flex-shrink-0 px-4 py-2 rounded-full text-xs font-semibold bg-primary text-white" data-f="all">Semua (<?= count($prospekList) ?>)</button>
        <button onclick="mFilter('open')" class="m-filter-btn flex-shrink-0 px-4 py-2 rounded-full text-xs font-medium bg-blue-50 text-blue-700" data-f="open">Open</button>
        <button onclick="mFilter('pending')" class="m-filter-btn flex-shrink-0 px-4 py-2 rounded-full text-xs font-medium bg-amber-50 text-amber-700" data-f="pending">Pending</button>
        <button onclick="mFilter('submit')" class="m-filter-btn flex-shrink-0 px-4 py-2 rounded-full text-xs font-medium bg-green-50 text-green-700" data-f="submit">Submit</button>
        <button onclick="mFilter('reject')" class="m-filter-btn flex-shrink-0 px-4 py-2 rounded-full text-xs font-medium bg-red-50 text-red-700" data-f="reject">Reject</button>
    </div>
</div>

<!-- Search -->
<div class="px-4 mt-3">
    <div class="relative">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" id="mSearch" onkeyup="mSearchFn()" placeholder="Cari debitur..." class="w-full pl-9 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
    </div>
</div>

<!-- Cards -->
<div class="px-4 mt-4 pb-6 space-y-3" id="mList">
    <?php foreach ($prospekList as $p): ?>
        <?php $sc = $statusConfig[$p['status']]; ?>
        <?php $scoreColor = $p['score'] >= 80 ? 'text-red-600 bg-red-50' : ($p['score'] >= 60 ? 'text-amber-600 bg-amber-50' : 'text-gray-600 bg-gray-100'); ?>
        <?php $scoreLabel = $p['score'] >= 80 ? 'HOT' : ($p['score'] >= 60 ? 'WARM' : 'COLD'); ?>
        <div class="m-card bg-white rounded-2xl shadow-card p-4 border border-gray-50" data-status="<?= $p['status'] ?>" data-nama="<?= strtolower($p['nama']) ?>">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-3 flex-1 min-w-0">
                    <div class="w-11 h-11 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                        <?= strtoupper(substr($p['nama'], 0, 2)) ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-textMain truncate"><?= $p['nama'] ?></p>
                        <p class="text-xs text-textSub"><?= $p['pemilik'] ?> • <?= $p['kecamatan'] ?></p>
                        <div class="flex items-center space-x-2 mt-1.5">
                            <span class="text-xs font-bold <?= $scoreColor ?> px-2 py-0.5 rounded-full"><?= $scoreLabel ?></span>
                            <span class="inline-flex items-center text-xs font-medium <?= $sc['bg'] ?> <?= $sc['text'] ?> px-2 py-0.5 rounded-full"><?= $sc['label'] ?></span>
                            <?php if ($p['diambil_ao']): ?>
                                <span class="text-xs text-gray-400">🔒</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <p class="text-sm font-bold text-textMain ml-2">Rp <?= number_format($p['nominal'] / 1000000, 0, ',', '.') ?>jt</p>
            </div>
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-50">
                <span class="text-xs text-textSub"><?= $p['ao'] ?: 'Belum ada AO' ?> • <?= date('d M', strtotime($p['tanggal'])) ?></span>
                <div class="flex items-center space-x-2">
                    <?php if ($p['status'] === 'open' || $p['status'] === 'pending'): ?>
                    <button class="px-3 py-1.5 text-xs font-medium bg-green-600 text-white rounded-lg shadow-sm">Submit</button>
                    <?php endif; ?>
                    <?php if (!$p['diambil_ao'] && $p['status'] !== 'submit'): ?>
                    <button class="px-3 py-1.5 text-xs font-medium bg-red-50 text-red-600 rounded-lg">Hapus</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- FAB -->
<button onclick="openMobileForm()" class="fixed bottom-20 right-4 w-14 h-14 bg-primary text-white rounded-full shadow-xl flex items-center justify-center z-40 lg:hidden">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
</button>

<!-- Mobile Form Bottom Sheet -->
<div id="mFormSheet" class="fixed inset-0 z-50 hidden lg:hidden">
    <div class="absolute inset-0 bg-black/40" onclick="closeMobileForm()"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl max-h-[90vh] overflow-y-auto animate-fade-in">
        <div class="sticky top-0 bg-white px-5 pt-4 pb-3 border-b border-gray-100 rounded-t-3xl z-10">
            <div class="w-10 h-1 bg-gray-300 rounded-full mx-auto mb-3"></div>
            <h3 class="text-lg font-bold text-textMain">Tambah Prospek</h3>
        </div>
        <form onsubmit="mSubmit(event)" class="p-5 space-y-3">
            <input type="text" name="nama" required placeholder="Nama Usaha / Debitur *" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
            <div class="grid grid-cols-2 gap-2">
                <input type="text" name="pemilik" required placeholder="Pemilik *" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                <input type="tel" name="telepon" required placeholder="No. Telepon *" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
            </div>
            <input type="text" name="alamat" placeholder="Alamat lengkap" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
            <select name="kabupaten" id="mSelKab" onchange="mLoadKec()" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                <option value="">Kabupaten/Kota *</option>
            </select>
            <div class="grid grid-cols-2 gap-2">
                <select name="kecamatan" id="mSelKec" onchange="mLoadKel()" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"><option value="">Kecamatan *</option></select>
                <select name="kelurahan" id="mSelKel" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"><option value="">Kelurahan *</option></select>
            </div>
            <!-- Geolocation -->
            <button type="button" onclick="mGetLoc()" class="w-full flex items-center justify-center px-4 py-2.5 bg-green-50 text-green-700 text-sm font-medium rounded-xl border border-green-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span id="mLocText">📍 Ambil Lokasi Otomatis</span>
            </button>
            <input type="hidden" name="lat" id="mLat">
            <input type="hidden" name="lng" id="mLng">
            <div class="grid grid-cols-2 gap-2">
                <select name="produk" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                    <option value="">Produk *</option>
                    <option>Kredit Modal Kerja</option>
                    <option>Kredit Investasi</option>
                    <option>KMK</option>
                    <option>KPR</option>
                </select>
                <input type="number" name="nominal" required placeholder="Nominal (Rp) *" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
            </div>
            <!-- Upload foto -->
            <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center" onclick="document.getElementById('mFotoInput').click()">
                <p class="text-sm text-textSub">📷 Tap untuk upload foto lokasi</p>
                <p class="text-xs text-gray-400">JPG, PNG max 5MB</p>
            </div>
            <input type="file" id="mFotoInput" name="foto" accept="image/*" capture="environment" class="hidden">
            <textarea name="catatan" rows="2" placeholder="Catatan..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm resize-none"></textarea>
            <button type="submit" class="w-full py-3 bg-primary text-white font-semibold rounded-xl shadow-md">Simpan Prospek</button>
        </form>
    </div>
</div>

<script>
// Wilayah data (simplified)
const mWilayah = {
    kab: [{id:'3271',n:'Kota Bogor'},{id:'3201',n:'Kab. Bogor'},{id:'3275',n:'Kota Bekasi'}],
    kec: {
        '3271': [{id:'327101',n:'Bogor Selatan'},{id:'327102',n:'Bogor Timur'},{id:'327103',n:'Bogor Utara'},{id:'327104',n:'Bogor Tengah'}],
        '3201': [{id:'320101',n:'Cibinong'},{id:'320102',n:'Gunung Putri'},{id:'320103',n:'Cileungsi'}],
        '3275': [{id:'327501',n:'Bekasi Timur'},{id:'327502',n:'Bekasi Barat'}],
    },
    kel: {
        '327101': ['Bondongan','Batutulis','Empang'],
        '327102': ['Baranangsiang','Sukasari'],
        '327103': ['Bantarjati','Tegalgundil','Tanah Baru'],
        '327104': ['Panaragan','Cibogor','Babakan'],
        '320101': ['Tengah','Cirimekar','Pakansari'],
        '320102': ['Tlajung Udik','Bojong Nangka'],
        '320103': ['Cileungsi','Jatisari'],
        '327501': ['Margahayu','Bekasi Jaya'],
        '327502': ['Bintara','Kranji'],
    }
};

function openMobileForm() {
    document.getElementById('mFormSheet').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    // Load kab
    const sel = document.getElementById('mSelKab');
    if (sel.options.length <= 1) {
        mWilayah.kab.forEach(k => sel.innerHTML += `<option value="${k.id}">${k.n}</option>`);
    }
}
function closeMobileForm() {
    document.getElementById('mFormSheet').classList.add('hidden');
    document.body.style.overflow = '';
}
function mLoadKec() {
    const id = document.getElementById('mSelKab').value;
    const sel = document.getElementById('mSelKec');
    sel.innerHTML = '<option value="">Kecamatan *</option>';
    document.getElementById('mSelKel').innerHTML = '<option value="">Kelurahan *</option>';
    if (id && mWilayah.kec[id]) mWilayah.kec[id].forEach(k => sel.innerHTML += `<option value="${k.id}">${k.n}</option>`);
}
function mLoadKel() {
    const id = document.getElementById('mSelKec').value;
    const sel = document.getElementById('mSelKel');
    sel.innerHTML = '<option value="">Kelurahan *</option>';
    if (id && mWilayah.kel[id]) mWilayah.kel[id].forEach(k => sel.innerHTML += `<option value="${k}">${k}</option>`);
}
function mGetLoc() {
    const txt = document.getElementById('mLocText');
    txt.textContent = '⏳ Mengambil lokasi...';
    navigator.geolocation.getCurrentPosition(
        p => { document.getElementById('mLat').value = p.coords.latitude.toFixed(6); document.getElementById('mLng').value = p.coords.longitude.toFixed(6); txt.textContent = '✅ Lokasi didapat'; },
        e => { txt.textContent = '❌ Gagal: ' + e.message; },
        { enableHighAccuracy: true, timeout: 10000 }
    );
}
function mSubmit(e) {
    e.preventDefault();
    const fd = new FormData(e.target);
    const body = { nama: fd.get('nama'), pemilik: fd.get('pemilik'), telepon: fd.get('telepon'), alamat: fd.get('alamat'), kabupaten: document.getElementById('mSelKab').selectedOptions[0]?.text, kecamatan: document.getElementById('mSelKec').selectedOptions[0]?.text, kelurahan: fd.get('kelurahan'), lat: fd.get('lat'), lng: fd.get('lng'), produk: fd.get('produk'), nominal: parseInt(fd.get('nominal')), foto: fd.get('foto')?.name || null, catatan: fd.get('catatan'), ao: '<?= $userName ?>', role: '<?= $userRole ?>', status: 'open', timestamp: new Date().toISOString() };
    console.log('📤 Tambah Prospek (Mobile):', JSON.stringify(body, null, 2));
    alert('✅ Prospek "' + body.nama + '" berhasil disimpan!');
    closeMobileForm();
    e.target.reset();
}
function mFilter(s) {
    document.querySelectorAll('.m-card').forEach(c => c.style.display = (s === 'all' || c.dataset.status === s) ? '' : 'none');
    document.querySelectorAll('.m-filter-btn').forEach(b => {
        b.className = b.dataset.f === s ? 'm-filter-btn flex-shrink-0 px-4 py-2 rounded-full text-xs font-semibold bg-primary text-white' : 'm-filter-btn flex-shrink-0 px-4 py-2 rounded-full text-xs font-medium bg-gray-100 text-gray-600';
    });
}
function mSearchFn() {
    const q = document.getElementById('mSearch').value.toLowerCase();
    document.querySelectorAll('.m-card').forEach(c => c.style.display = c.dataset.nama.includes(q) ? '' : 'none');
}
</script>
