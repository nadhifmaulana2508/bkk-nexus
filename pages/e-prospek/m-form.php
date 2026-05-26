<?php
/**
 * Mobile E-Prospek - Form Tambah Prospek Baru
 */
?>

<!-- Header -->
<div class="px-4 -mt-4 relative z-10 animate-fade-in">
    <div class="bg-white rounded-2xl shadow-card-hover p-5">
        <div class="flex items-start gap-3">
            <div class="w-11 h-11 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center text-white flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <p class="text-base font-bold text-textMain">Tambah Prospek</p>
                <p class="text-xs text-textSub mt-0.5">Input calon debitur baru — tabungan, deposito, kredit, atau aset.</p>
            </div>
        </div>
    </div>
</div>

<form id="formProspek" class="px-4 mt-4 space-y-4 pb-6 animate-fade-in" onsubmit="submitProspek(event)">

    <!-- 1. Jenis Produk -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4">
        <label class="block text-xs font-bold text-textSub uppercase tracking-wider mb-3">1. Jenis Produk</label>
        <div class="grid grid-cols-2 gap-2">
            <?php
            $produkOpts = [
                'tabungan' => ['Tabungan', 'bg-cyan-50 border-cyan-200 text-cyan-700', 'bank'],
                'deposito' => ['Deposito', 'bg-purple-50 border-purple-200 text-purple-700', 'lock'],
                'kredit'   => ['Kredit',   'bg-orange-50 border-orange-200 text-orange-700', 'credit'],
                'aset'     => ['Aset',     'bg-pink-50 border-pink-200 text-pink-700', 'home'],
            ];
            foreach ($produkOpts as $val => [$label, $cls]):
            ?>
                <label class="cursor-pointer">
                    <input type="radio" name="produk_jenis" value="<?= $val ?>" required class="peer sr-only" onchange="onProdukChange(this.value)">
                    <div class="border-2 rounded-xl px-3 py-3 text-sm font-semibold text-center transition-all
                                <?= $cls ?> peer-checked:ring-2 peer-checked:ring-primary peer-checked:scale-[0.98]">
                        <?= $label ?>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
        <p id="produkHint" class="hidden text-xs text-textSub mt-3 p-3 bg-indigo-50 border border-indigo-100 rounded-xl"></p>
    </div>

    <!-- 2. Identitas Debitur -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4 space-y-3">
        <label class="block text-xs font-bold text-textSub uppercase tracking-wider mb-1">2. Identitas Debitur</label>
        <div>
            <label class="block text-xs text-textSub mb-1.5">Nama Usaha / Nasabah <span class="text-danger">*</span></label>
            <input type="text" name="nama" required placeholder="Contoh: Toko Maju Jaya"
                   class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>
        <div>
            <label class="block text-xs text-textSub mb-1.5">Nama Pemilik <span class="text-danger">*</span></label>
            <input type="text" name="pemilik" required placeholder="Contoh: Pak Ahmad"
                   class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs text-textSub mb-1.5">NIK</label>
                <input type="text" name="nik" inputmode="numeric" placeholder="16 digit"
                       class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div>
                <label class="block text-xs text-textSub mb-1.5">No. Telepon <span class="text-danger">*</span></label>
                <input type="tel" name="telp" required placeholder="08xxxxxxxxxx"
                       class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
        </div>
    </div>

    <!-- 3. Lokasi -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4 space-y-3">
        <label class="block text-xs font-bold text-textSub uppercase tracking-wider mb-1">3. Lokasi</label>
        <textarea name="alamat" rows="2" placeholder="Alamat lengkap"
                  class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40"></textarea>
        <div class="grid grid-cols-3 gap-2">
            <input type="text" name="kab" placeholder="Kab/Kota" class="bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-primary/40">
            <input type="text" name="kec" placeholder="Kec" class="bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-primary/40">
            <input type="text" name="kel" placeholder="Kel" class="bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>
        <button type="button" onclick="captureProspekGps()" id="btnGpsProspek"
                class="w-full bg-success text-white font-semibold text-sm rounded-xl px-4 py-2.5 inline-flex items-center justify-center gap-2 hover:bg-success/90">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span id="btnGpsProspekLabel">Tandai Lokasi (GPS)</span>
        </button>
        <input type="hidden" name="lat" id="prospekLat">
        <input type="hidden" name="lng" id="prospekLng">
        <p id="gpsProspekResult" class="hidden text-xs p-2.5 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700"></p>
    </div>

    <!-- 4. Detail Produk + Nominal -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4 space-y-3">
        <label class="block text-xs font-bold text-textSub uppercase tracking-wider mb-1">4. Detail Produk</label>
        <div>
            <label class="block text-xs text-textSub mb-1.5">Sub-produk</label>
            <input type="text" name="produk_sub" id="produkSub" placeholder="KMK / KI / Tabungan Bagus / dll"
                   class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>
        <div>
            <label class="block text-xs text-textSub mb-1.5">Nominal (Rp) <span class="text-danger">*</span></label>
            <input type="number" name="nominal" required min="0" step="100000" placeholder="0"
                   class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>
        <div>
            <label class="block text-xs text-textSub mb-1.5">Tujuan / Keperluan</label>
            <input type="text" name="tujuan" placeholder="Contoh: Tambah modal kerja"
                   class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>
    </div>

    <!-- 5. Detail Usaha (untuk kredit/aset) -->
    <div id="sectionUsaha" class="hidden bg-white rounded-2xl shadow-card border border-gray-50 p-4 space-y-3">
        <label class="block text-xs font-bold text-textSub uppercase tracking-wider mb-1">5. Detail Usaha</label>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs text-textSub mb-1.5">Jenis Usaha</label>
                <input type="text" name="jenis_usaha" placeholder="Perdagangan/Jasa/dll" class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div>
                <label class="block text-xs text-textSub mb-1.5">Lama Usaha (thn)</label>
                <input type="number" name="lama_usaha_thn" min="0" placeholder="0" class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
        </div>
        <div>
            <label class="block text-xs text-textSub mb-1.5">Omzet / Bulan (Rp)</label>
            <input type="number" name="omzet" min="0" step="100000" placeholder="0" class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>
        <div>
            <label class="block text-xs text-textSub mb-1.5">Jaminan</label>
            <input type="text" name="jaminan" placeholder="BPKB / Sertifikat / dll" class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>
    </div>

    <!-- 6. Sumber + Foto -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4 space-y-3">
        <label class="block text-xs font-bold text-textSub uppercase tracking-wider mb-1">6. Sumber Prospek & Foto</label>
        <select name="sumber" class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            <option value="walk_in">Walk-in</option>
            <option value="referral">Referral</option>
            <option value="kanvasing">Kanvasing</option>
            <option value="online">Online</option>
            <option value="hot_lead">Hot Lead</option>
            <option value="monbis">MonBis</option>
            <option value="existing">Existing Nasabah</option>
        </select>
        <label for="prospekFoto" class="block w-full bg-surface border-2 border-dashed border-gray-300 rounded-xl py-5 text-center cursor-pointer hover:border-primary">
            <svg class="w-8 h-8 mx-auto text-gray-400 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span class="text-sm font-semibold text-textMain">Foto Lokasi / Usaha (opsional)</span>
        </label>
        <input type="file" id="prospekFoto" name="foto" accept="image/*" capture="environment" class="hidden" onchange="previewProspekFoto(event)">
        <img id="previewProspekFoto" class="hidden w-full rounded-xl border border-gray-200" alt="Preview">
    </div>

    <!-- 7. Catatan -->
    <div class="bg-white rounded-2xl shadow-card border border-gray-50 p-4">
        <label class="block text-xs font-bold text-textSub uppercase tracking-wider mb-2">7. Catatan</label>
        <textarea name="catatan" rows="3" placeholder="Catatan tambahan, kondisi usaha, riwayat hubungan, dll…"
                  class="w-full bg-surface border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40"></textarea>
    </div>

    <!-- Submit -->
    <button type="submit"
            class="w-full bg-gradient-to-r from-primary to-secondary text-white font-bold text-sm rounded-2xl px-4 py-3.5 shadow-lg shadow-primary/30 inline-flex items-center justify-center gap-2 hover:opacity-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        Simpan Prospek
    </button>
</form>

<script>
function epToast(msg, type='info') {
    const el = document.createElement('div');
    el.textContent = msg;
    el.className = 'fixed top-5 left-1/2 -translate-x-1/2 z-[100] px-4 py-2.5 rounded-xl text-sm font-semibold shadow-lg ' +
        (type === 'error' ? 'bg-red-500 text-white' : type === 'success' ? 'bg-emerald-500 text-white' : 'bg-textMain text-white');
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 2500);
}

function onProdukChange(val) {
    const hint = document.getElementById('produkHint');
    const sub = document.getElementById('produkSub');
    const usaha = document.getElementById('sectionUsaha');
    const hints = {
        tabungan: '✓ Setelah submit, bisa langsung di-update sebagai realisasi (tidak melalui pipelane).',
        deposito: '✓ Setelah submit, bisa langsung di-update sebagai realisasi (tidak melalui pipelane).',
        kredit:   '⚠ Setelah submit, akan otomatis masuk E-Pipelane untuk proses SLA kredit.',
        aset:     '⚠ Akan diteruskan ke AO Remedial untuk follow-up jaminan.',
    };
    const subDefault = {
        tabungan: 'Tabungan Bagus',
        deposito: 'Deposito 6 bulan',
        kredit:   'KMK',
        aset:     'Aset jaminan',
    };
    hint.textContent = hints[val] ?? '';
    hint.classList.toggle('hidden', !hints[val]);
    if (sub && !sub.value) sub.value = subDefault[val] ?? '';
    if (usaha) usaha.classList.toggle('hidden', !['kredit','aset'].includes(val));
}

function captureProspekGps() {
    if (!navigator.geolocation) { epToast('Browser tidak mendukung GPS', 'error'); return; }
    const btn = document.getElementById('btnGpsProspek');
    const lbl = document.getElementById('btnGpsProspekLabel');
    btn.disabled = true; lbl.textContent = 'Mendeteksi…';
    navigator.geolocation.getCurrentPosition(
        pos => {
            const lat = pos.coords.latitude.toFixed(6);
            const lng = pos.coords.longitude.toFixed(6);
            document.getElementById('prospekLat').value = lat;
            document.getElementById('prospekLng').value = lng;
            const r = document.getElementById('gpsProspekResult');
            r.textContent = `Lokasi: ${lat}, ${lng} (~${Math.round(pos.coords.accuracy)}m)`;
            r.classList.remove('hidden');
            lbl.textContent = 'Update Lokasi';
            btn.disabled = false;
            epToast('GPS tersimpan', 'success');
        },
        err => {
            lbl.textContent = 'Tandai Lokasi (GPS)';
            btn.disabled = false;
            epToast('GPS gagal: ' + err.message, 'error');
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );
}

function previewProspekFoto(e) {
    const file = e.target.files[0];
    if (!file) return;
    const img = document.getElementById('previewProspekFoto');
    img.src = URL.createObjectURL(file);
    img.classList.remove('hidden');
}

function submitProspek(e) {
    e.preventDefault();
    const data = new FormData(e.target);
    console.log('PROSPEK_PAYLOAD', Object.fromEntries(data.entries()));
    epToast('Prospek tersimpan (dummy)', 'success');
    setTimeout(() => { window.location = '<?= BASE_URL ?>e-prospek?tab=home'; }, 800);
}
</script>
