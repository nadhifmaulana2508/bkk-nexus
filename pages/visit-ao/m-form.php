<?php
/**
 * Mobile - Form Kunjungan
 * GPS check-in + foto + hasil + janji bayar + catatan
 */

// Pre-select debitur dari query param
$preDebiturId = (int)($_GET['debitur_id'] ?? 0);
$preDebitur = $preDebiturId ? vao_debitur_by_id($debiturs, $preDebiturId) : null;
// Pastikan debitur memang kelolaan AO ini
if ($preDebitur && $preDebitur['ao_id'] !== $currentAoId) $preDebitur = null;
?>

<!-- Header card -->
<div class="px-4 -mt-4 relative z-10 animate-fade-in">
    <div class="bg-white rounded-2xl shadow-card-hover p-5">
        <div class="flex items-start gap-3">
            <div class="w-11 h-11 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center text-white flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-base font-bold text-textMain">Form Kunjungan</p>
                <p class="text-xs text-textSub mt-0.5">Catat hasil visit ke debitur kelolaan Anda.</p>
            </div>
        </div>
    </div>
</div>

<form id="formVisit" class="px-4 mt-4 space-y-4 pb-6 animate-fade-in" onsubmit="submitVisit(event)">

    <!-- Pilih Debitur -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4">
        <label class="block text-xs font-bold text-textSub uppercase tracking-wider mb-2">1. Pilih Debitur</label>
        <select name="debitur_id" id="selectDebitur" required onchange="onDebiturChange(this.value)"
                class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            <option value="">— Pilih debitur kelolaan —</option>
            <?php foreach ($myDebiturs as $d): ?>
                <option value="<?= $d['id'] ?>"
                        data-bucket="<?= $d['dpd_bucket'] ?>"
                        data-bakidebet="<?= $d['baki_debet'] ?>"
                        data-angsuran="<?= $d['angsuran'] ?>"
                        data-alamat="<?= htmlspecialchars($d['alamat']) ?>"
                        data-pemilik="<?= htmlspecialchars($d['pemilik']) ?>"
                        data-telp="<?= $d['telp'] ?>"
                        data-lat="<?= $d['lat'] ?>"
                        data-lng="<?= $d['lng'] ?>"
                        <?= $preDebitur && $preDebitur['id'] === $d['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($d['nama']) ?> (<?= vao_bucket_label($d['dpd_bucket']) ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Detail debitur (akan terisi via JS / atau pre-fill dari $preDebitur) -->
        <div id="debiturDetail" class="<?= $preDebitur ? '' : 'hidden' ?> mt-3 p-3 bg-indigo-50 border border-indigo-100 rounded-xl text-xs space-y-1.5">
            <div class="flex justify-between"><span class="text-textSub">Pemilik</span><span id="dPemilik" class="font-semibold text-textMain"><?= $preDebitur ? htmlspecialchars($preDebitur['pemilik']) : '-' ?></span></div>
            <div class="flex justify-between"><span class="text-textSub">Bucket</span><span id="dBucket" class="font-semibold"><?= $preDebitur ? vao_bucket_label($preDebitur['dpd_bucket']) : '-' ?></span></div>
            <div class="flex justify-between"><span class="text-textSub">Baki Debet</span><span id="dBakiDebet" class="font-semibold text-textMain"><?= $preDebitur ? vao_fmt_rp((int)$preDebitur['baki_debet']) : '-' ?></span></div>
            <div class="flex justify-between"><span class="text-textSub">Angsuran</span><span id="dAngsuran" class="font-semibold text-textMain"><?= $preDebitur ? vao_fmt_rp((int)$preDebitur['angsuran']) : '-' ?></span></div>
            <div class="flex justify-between gap-3"><span class="text-textSub">Alamat</span><span id="dAlamat" class="font-semibold text-textMain text-right"><?= $preDebitur ? htmlspecialchars($preDebitur['alamat']) : '-' ?></span></div>
        </div>
    </div>

    <!-- GPS Check-in -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4">
        <label class="block text-xs font-bold text-textSub uppercase tracking-wider mb-2">2. Check-in Lokasi (GPS)</label>
        <button type="button" onclick="captureLocation()" id="btnGps"
                class="w-full bg-success text-white font-semibold text-sm rounded-xl px-4 py-3 inline-flex items-center justify-center gap-2 hover:bg-success/90">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span id="btnGpsLabel">Aktifkan GPS Saya</span>
        </button>
        <input type="hidden" name="lat" id="inputLat">
        <input type="hidden" name="lng" id="inputLng">
        <div id="gpsResult" class="hidden mt-3 p-3 bg-emerald-50 border border-emerald-100 rounded-xl text-xs">
            <p class="font-semibold text-emerald-700 inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Lokasi tercatat
            </p>
            <p class="text-emerald-700/80 mt-1" id="gpsCoords"></p>
            <p class="text-emerald-700/60 mt-0.5" id="gpsDistance"></p>
        </div>
    </div>

    <!-- Foto -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4">
        <label class="block text-xs font-bold text-textSub uppercase tracking-wider mb-2">3. Foto Bukti Kunjungan</label>
        <label for="inputFoto" class="block w-full bg-surface border-2 border-dashed border-gray-300 rounded-xl py-6 text-center cursor-pointer hover:border-primary transition-colors">
            <svg class="w-10 h-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span class="text-sm font-semibold text-textMain">Ambil Foto / Pilih</span>
            <p class="text-xs text-textSub mt-1">JPG / PNG, max 5MB</p>
        </label>
        <input type="file" id="inputFoto" name="foto" accept="image/*" capture="environment" class="hidden" onchange="previewFoto(event)">
        <img id="previewFoto" class="hidden mt-3 w-full rounded-xl border border-gray-200" alt="Preview">
    </div>

    <!-- Hasil Kunjungan -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4">
        <label class="block text-xs font-bold text-textSub uppercase tracking-wider mb-3">4. Hasil Kunjungan</label>
        <div class="grid grid-cols-2 gap-2">
            <?php
            $hasilOpts = [
                'kontak' => ['Kontak', 'bg-blue-50 border-blue-200 text-blue-700'],
                'tidak_kontak' => ['Tidak Kontak', 'bg-gray-50 border-gray-200 text-gray-700'],
                'janji_bayar' => ['Janji Bayar', 'bg-amber-50 border-amber-200 text-amber-700'],
                'sudah_bayar' => ['Sudah Bayar', 'bg-emerald-50 border-emerald-200 text-emerald-700'],
                'keberatan' => ['Keberatan', 'bg-orange-50 border-orange-200 text-orange-700'],
                'pindah_domisili' => ['Pindah Domisili', 'bg-rose-50 border-rose-200 text-rose-700'],
                'nomor_mati' => ['Nomor Mati', 'bg-gray-50 border-gray-200 text-gray-700'],
            ];
            foreach ($hasilOpts as $val => [$label, $cls]):
            ?>
                <label class="cursor-pointer">
                    <input type="radio" name="hasil" value="<?= $val ?>" required class="peer sr-only" onchange="onHasilChange(this.value)">
                    <div class="border-2 rounded-xl px-3 py-2.5 text-xs font-semibold text-center transition-all
                                <?= $cls ?> peer-checked:ring-2 peer-checked:ring-primary peer-checked:scale-[0.98]">
                        <?= $label ?>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Janji Bayar (conditional) -->
    <div id="sectionJanji" class="hidden bg-white rounded-2xl shadow-card border border-gray-50 p-4">
        <label class="block text-xs font-bold text-textSub uppercase tracking-wider mb-3">5. Detail Janji Bayar / Realisasi</label>
        <div class="space-y-3">
            <div>
                <label class="block text-xs text-textSub mb-1.5">Tanggal Janji / Bayar</label>
                <input type="date" name="tanggal_janji"
                       class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div>
                <label class="block text-xs text-textSub mb-1.5">Nominal (Rp)</label>
                <input type="number" name="nominal_janji" placeholder="0" min="0" step="100000"
                       class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
        </div>
    </div>

    <!-- Catatan -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4">
        <label class="block text-xs font-bold text-textSub uppercase tracking-wider mb-2">6. Catatan</label>
        <textarea name="catatan" rows="4" placeholder="Tulis kondisi usaha, alasan tunggakan, atau hal penting lainnya…"
                  class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40"></textarea>
    </div>

    <!-- Submit -->
    <button type="submit"
            class="w-full bg-gradient-to-r from-primary to-secondary text-white font-bold text-sm rounded-2xl px-4 py-3.5 shadow-lg shadow-primary/30 inline-flex items-center justify-center gap-2 hover:opacity-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        Simpan Kunjungan
    </button>
</form>

<script>
// Toast feedback (simple)
function toast(msg, type = 'info') {
    const el = document.createElement('div');
    el.textContent = msg;
    el.className = 'fixed top-5 left-1/2 -translate-x-1/2 z-[100] px-4 py-2.5 rounded-xl text-sm font-semibold shadow-lg ' +
        (type === 'error' ? 'bg-red-500 text-white' : type === 'success' ? 'bg-emerald-500 text-white' : 'bg-textMain text-white');
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 2500);
}

// Detail debitur on change
function onDebiturChange(id) {
    const sel = document.getElementById('selectDebitur');
    const opt = sel.options[sel.selectedIndex];
    const detail = document.getElementById('debiturDetail');
    if (!id) { detail.classList.add('hidden'); return; }
    detail.classList.remove('hidden');
    document.getElementById('dPemilik').textContent = opt.dataset.pemilik;
    document.getElementById('dBucket').textContent = opt.text.match(/\(([^)]+)\)/)?.[1] || '-';
    document.getElementById('dBakiDebet').textContent = 'Rp ' + Number(opt.dataset.bakidebet).toLocaleString('id-ID');
    document.getElementById('dAngsuran').textContent = 'Rp ' + Number(opt.dataset.angsuran).toLocaleString('id-ID');
    document.getElementById('dAlamat').textContent = opt.dataset.alamat;
}

// GPS Capture
function captureLocation() {
    if (!navigator.geolocation) {
        toast('Browser tidak mendukung GPS', 'error');
        return;
    }
    const btn = document.getElementById('btnGps');
    const lbl = document.getElementById('btnGpsLabel');
    btn.disabled = true;
    lbl.textContent = 'Mendeteksi lokasi…';

    navigator.geolocation.getCurrentPosition(
        pos => {
            const lat = pos.coords.latitude.toFixed(6);
            const lng = pos.coords.longitude.toFixed(6);
            document.getElementById('inputLat').value = lat;
            document.getElementById('inputLng').value = lng;

            const result = document.getElementById('gpsResult');
            result.classList.remove('hidden');
            document.getElementById('gpsCoords').textContent = `${lat}, ${lng} (akurasi ~${Math.round(pos.coords.accuracy)}m)`;

            // Hitung jarak ke alamat debitur (kalau ada)
            const sel = document.getElementById('selectDebitur');
            const opt = sel.options[sel.selectedIndex];
            if (opt && opt.dataset.lat && opt.dataset.lng) {
                const d = haversine(parseFloat(lat), parseFloat(lng), parseFloat(opt.dataset.lat), parseFloat(opt.dataset.lng));
                document.getElementById('gpsDistance').textContent = `Jarak ke alamat debitur: ~${d < 1 ? Math.round(d * 1000) + ' m' : d.toFixed(2) + ' km'}`;
            }

            lbl.textContent = 'Update Lokasi';
            btn.disabled = false;
            toast('Lokasi berhasil ditangkap', 'success');
        },
        err => {
            lbl.textContent = 'Aktifkan GPS Saya';
            btn.disabled = false;
            toast('Gagal: ' + err.message, 'error');
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );
}

function haversine(lat1, lng1, lat2, lng2) {
    const R = 6371; // km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = Math.sin(dLat/2) ** 2 + Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) * Math.sin(dLng/2) ** 2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
}

// Foto preview
function previewFoto(e) {
    const file = e.target.files[0];
    if (!file) return;
    const img = document.getElementById('previewFoto');
    img.src = URL.createObjectURL(file);
    img.classList.remove('hidden');
}

// Conditional janji bayar
function onHasilChange(val) {
    const section = document.getElementById('sectionJanji');
    if (val === 'janji_bayar' || val === 'sudah_bayar') {
        section.classList.remove('hidden');
    } else {
        section.classList.add('hidden');
    }
}

// Submit (dummy — saat backend siap, ganti dengan fetch ke /api/visits)
function submitVisit(e) {
    e.preventDefault();
    const form = e.target;
    if (!document.getElementById('inputLat').value) {
        toast('Mohon aktifkan GPS dulu', 'error');
        return;
    }
    const data = new FormData(form);
    console.log('VISIT_PAYLOAD', Object.fromEntries(data.entries()));
    toast('Kunjungan tersimpan (dummy)', 'success');
    setTimeout(() => { window.location = '<?= BASE_URL ?>visit-ao?tab=home'; }, 800);
}

// Pre-select dari URL
document.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('selectDebitur');
    if (sel.value) onDebiturChange(sel.value);
});
</script>
