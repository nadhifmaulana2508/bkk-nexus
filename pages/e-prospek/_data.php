<?php
/**
 * E-Prospek - Shared dummy data + helpers.
 * Saat backend siap, ganti $prospeks dengan hasil fetch ke /api/prospek
 * yang menerima param: kode_kantor, closing_date, harian_date.
 */

// Re-use master kantor & ao dari visit-ao agar konsisten
require_once __DIR__ . '/../visit-ao/_data.php';

// Filter params
$filterKodeKantor  = $_GET['kode_kantor']  ?? '000';
$filterClosingDate = $_GET['closing_date'] ?? date('Y-m-d', strtotime('last day of last month'));
$filterHarianDate  = $_GET['harian_date']  ?? date('Y-m-d');

// Inputter saat ini (saat SSO siap, dari token)
$currentInputterId   = 1;             // Harry (ao_kredit)
$currentInputter     = vao_ao_by_id($aos, $currentInputterId);
$currentInputterRole = $currentInputter['role'] ?? 'ao_kredit';

// =============================
// MASTER PROSPEK
// =============================
// Status: open → pending → submit → realisasi / reject
// Produk: tabungan / deposito / kredit / aset
// Score: hot (>=80) / warm (60-79) / cold (<60)

$prospeks = [
    // ========== Cabang 001 (Surabaya) ==========
    ['id'=>1,'nama'=>'Toko Elektronik Cahaya','pemilik'=>'Pak Bambang','nik'=>'3578010505800001','telp'=>'081234567101',
     'alamat'=>'Jl. Genteng Kali 14, Surabaya','kab'=>'Kota Surabaya','kec'=>'Genteng','kel'=>'Genteng','lat'=>-7.2620,'lng'=>112.7480,
     'produk_jenis'=>'kredit','produk_sub'=>'KMK','nominal'=>200_000_000,'tujuan'=>'Tambah modal usaha elektronik',
     'jenis_usaha'=>'Perdagangan','lama_usaha_thn'=>5,'omzet'=>50_000_000,'jaminan'=>'BPKB Mobil + Sertifikat',
     'status'=>'submit','score'=>92,'sumber'=>'monbis','catatan'=>'Existing lancar, top-up 200jt',
     'kode_kantor'=>'001','ao_id'=>1,'inputter_id'=>1,'created_at'=>'2026-05-18 09:30','submitted_at'=>'2026-05-22 14:00'],

    ['id'=>2,'nama'=>'Sumber Barokah','pemilik'=>'Pak Hasan','nik'=>'3578011010820002','telp'=>'081234567102',
     'alamat'=>'Jl. Pasar Turi 22, Surabaya','kab'=>'Kota Surabaya','kec'=>'Bubutan','kel'=>'Tembok Dukuh','lat'=>-7.2630,'lng'=>112.7480,
     'produk_jenis'=>'kredit','produk_sub'=>'KMK','nominal'=>100_000_000,'tujuan'=>'Modal kerja warung sembako',
     'jenis_usaha'=>'Perdagangan','lama_usaha_thn'=>3,'omzet'=>20_000_000,'jaminan'=>'BPKB Motor',
     'status'=>'pending','score'=>74,'sumber'=>'walk_in','catatan'=>'Prospek dari kunjungan langsung CS',
     'kode_kantor'=>'001','ao_id'=>1,'inputter_id'=>11,'created_at'=>'2026-05-20 10:15','submitted_at'=>null],

    ['id'=>3,'nama'=>'Bu Wati Tabungan','pemilik'=>'Bu Wati Suryani','nik'=>'3578011215750003','telp'=>'081234567103',
     'alamat'=>'Jl. Ngagel Jaya 5, Surabaya','kab'=>'Kota Surabaya','kec'=>'Wonokromo','kel'=>'Ngagel','lat'=>-7.2920,'lng'=>112.7150,
     'produk_jenis'=>'tabungan','produk_sub'=>'Tabungan Bagus','nominal'=>50_000_000,'tujuan'=>'Buka rekening tabungan',
     'jenis_usaha'=>null,'lama_usaha_thn'=>null,'omzet'=>null,'jaminan'=>null,
     'status'=>'realisasi','score'=>85,'sumber'=>'referral','catatan'=>'Referral dari nasabah existing',
     'kode_kantor'=>'001','ao_id'=>1,'inputter_id'=>11,'created_at'=>'2026-05-15 11:00','submitted_at'=>'2026-05-15 13:30','realisasi_at'=>'2026-05-15 14:00'],

    ['id'=>4,'nama'=>'CV Mitra Konstruksi','pemilik'=>'Pak Surya','nik'=>'3578010401780004','telp'=>'081234567104',
     'alamat'=>'Jl. Diponegoro 90, Surabaya','kab'=>'Kota Surabaya','kec'=>'Tegalsari','kel'=>'Wonorejo','lat'=>-7.2756,'lng'=>112.6420,
     'produk_jenis'=>'kredit','produk_sub'=>'KI','nominal'=>500_000_000,'tujuan'=>'Investasi alat berat',
     'jenis_usaha'=>'Konstruksi','lama_usaha_thn'=>8,'omzet'=>200_000_000,'jaminan'=>'Sertifikat Tanah + BPKB',
     'status'=>'submit','score'=>88,'sumber'=>'referral','catatan'=>'Referral Kacab. Potensi besar.',
     'kode_kantor'=>'001','ao_id'=>4,'inputter_id'=>4,'created_at'=>'2026-05-12 14:20','submitted_at'=>'2026-05-19 10:00'],

    ['id'=>5,'nama'=>'Pak Hendra Deposito','pemilik'=>'Pak Hendra Wijaya','nik'=>'3578010810700005','telp'=>'081234567105',
     'alamat'=>'Jl. Mulyosari 90, Surabaya','kab'=>'Kota Surabaya','kec'=>'Mulyorejo','kel'=>'Mulyorejo','lat'=>-7.2980,'lng'=>112.7780,
     'produk_jenis'=>'deposito','produk_sub'=>'Deposito 6 bulan','nominal'=>500_000_000,'tujuan'=>'Investasi deposito 6 bulan',
     'jenis_usaha'=>null,'lama_usaha_thn'=>null,'omzet'=>null,'jaminan'=>null,
     'status'=>'realisasi','score'=>95,'sumber'=>'monbis','catatan'=>'Nasabah prima, top deposan.',
     'kode_kantor'=>'001','ao_id'=>1,'inputter_id'=>1,'created_at'=>'2026-05-10 10:00','submitted_at'=>'2026-05-10 11:00','realisasi_at'=>'2026-05-10 14:30'],

    ['id'=>6,'nama'=>'Toko Pakaian Anak Lucu','pemilik'=>'Bu Lina','nik'=>'3578011815850006','telp'=>'081234567106',
     'alamat'=>'Jl. Kapasari 33, Surabaya','kab'=>'Kota Surabaya','kec'=>'Genteng','kel'=>'Kapasari','lat'=>-7.2650,'lng'=>112.7330,
     'produk_jenis'=>'kredit','produk_sub'=>'KMK','nominal'=>75_000_000,'tujuan'=>'Tambah stok lebaran',
     'jenis_usaha'=>'Perdagangan','lama_usaha_thn'=>2,'omzet'=>15_000_000,'jaminan'=>'BPKB Motor',
     'status'=>'open','score'=>68,'sumber'=>'walk_in','catatan'=>'Baru kontak, perlu follow up.',
     'kode_kantor'=>'001','ao_id'=>1,'inputter_id'=>11,'created_at'=>'2026-05-25 13:00','submitted_at'=>null],

    ['id'=>7,'nama'=>'CV Maju Bersama','pemilik'=>'Pak Anto','nik'=>'3578010101700007','telp'=>'081234567107',
     'alamat'=>'Jl. Demak 17, Surabaya','kab'=>'Kota Surabaya','kec'=>'Asemrowo','kel'=>'Asemrowo','lat'=>-7.2780,'lng'=>112.7060,
     'produk_jenis'=>'kredit','produk_sub'=>'KMK','nominal'=>150_000_000,'tujuan'=>'Modal kerja distributor',
     'jenis_usaha'=>'Distribusi','lama_usaha_thn'=>4,'omzet'=>40_000_000,'jaminan'=>'BPKB + Sertifikat',
     'status'=>'reject','score'=>55,'sumber'=>'kanvasing','catatan'=>'Ditolak komite — usaha kurang stabil.',
     'kode_kantor'=>'001','ao_id'=>4,'inputter_id'=>4,'created_at'=>'2026-04-28 09:00','submitted_at'=>'2026-05-08 14:00','rejected_at'=>'2026-05-15 10:00','reject_reason'=>'Cash flow tidak konsisten 6 bulan terakhir.'],

    ['id'=>8,'nama'=>'Aset CV Karya','pemilik'=>'Pak Slamet','nik'=>'3578010101750008','telp'=>'081234567009',
     'alamat'=>'Jl. Kapasari 33, Surabaya','kab'=>'Kota Surabaya','kec'=>'Genteng','kel'=>'Kapasari','lat'=>-7.2650,'lng'=>112.7330,
     'produk_jenis'=>'aset','produk_sub'=>'Aset jaminan','nominal'=>180_000_000,'tujuan'=>'Lelang aset jaminan macet',
     'jenis_usaha'=>'Industri','lama_usaha_thn'=>10,'omzet'=>0,'jaminan'=>'Tanah & Bangunan',
     'status'=>'pending','score'=>70,'sumber'=>'existing','catatan'=>'Diteruskan ke AO Remedial untuk follow-up.',
     'kode_kantor'=>'001','ao_id'=>2,'inputter_id'=>1,'created_at'=>'2026-05-20 11:00','submitted_at'=>null],

    // ========== Cabang 002 (Malang) ==========
    ['id'=>9,'nama'=>'Warung Soto Lezat','pemilik'=>'Pak Iwan','nik'=>'3573010505800009','telp'=>'081234567109',
     'alamat'=>'Jl. Soekarno Hatta 50, Malang','kab'=>'Kota Malang','kec'=>'Lowokwaru','kel'=>'Mojolangu','lat'=>-7.9660,'lng'=>112.6326,
     'produk_jenis'=>'kredit','produk_sub'=>'KMK','nominal'=>80_000_000,'tujuan'=>'Buka cabang ke-2',
     'jenis_usaha'=>'Kuliner','lama_usaha_thn'=>6,'omzet'=>25_000_000,'jaminan'=>'BPKB Mobil',
     'status'=>'submit','score'=>82,'sumber'=>'walk_in','catatan'=>'Owner aktif, usaha berkembang.',
     'kode_kantor'=>'002','ao_id'=>5,'inputter_id'=>5,'created_at'=>'2026-05-15 10:00','submitted_at'=>'2026-05-21 13:00'],

    ['id'=>10,'nama'=>'Bu Rini Tabungan Anak','pemilik'=>'Bu Rini','nik'=>'3573010505900010','telp'=>'081234567110',
     'alamat'=>'Jl. Ijen 47, Malang','kab'=>'Kota Malang','kec'=>'Klojen','kel'=>'Oro-oro Dowo','lat'=>-7.9810,'lng'=>112.6210,
     'produk_jenis'=>'tabungan','produk_sub'=>'Tabungan Pelajar','nominal'=>10_000_000,'tujuan'=>'Tabungan untuk anak sekolah',
     'jenis_usaha'=>null,'lama_usaha_thn'=>null,'omzet'=>null,'jaminan'=>null,
     'status'=>'realisasi','score'=>78,'sumber'=>'walk_in','catatan'=>'Walk-in, langsung buka rekening.',
     'kode_kantor'=>'002','ao_id'=>5,'inputter_id'=>11,'created_at'=>'2026-05-22 09:30','submitted_at'=>'2026-05-22 09:45','realisasi_at'=>'2026-05-22 10:30'],

    ['id'=>11,'nama'=>'Toko Pertanian Subur','pemilik'=>'Pak Joko','nik'=>'3573010101720011','telp'=>'081234567111',
     'alamat'=>'Jl. Kawi 56, Malang','kab'=>'Kota Malang','kec'=>'Klojen','kel'=>'Sukoharjo','lat'=>-7.9870,'lng'=>112.6480,
     'produk_jenis'=>'kredit','produk_sub'=>'KMK','nominal'=>120_000_000,'tujuan'=>'Modal pengadaan pupuk',
     'jenis_usaha'=>'Perdagangan','lama_usaha_thn'=>7,'omzet'=>30_000_000,'jaminan'=>'BPKB + Sertifikat',
     'status'=>'pending','score'=>76,'sumber'=>'referral','catatan'=>'Need delegasi ke AO Kredit Malang.',
     'kode_kantor'=>'002','ao_id'=>null,'inputter_id'=>11,'created_at'=>'2026-05-24 14:00','submitted_at'=>null],

    ['id'=>12,'nama'=>'Pak Subroto Deposito','pemilik'=>'Pak Subroto','nik'=>'3573010101680012','telp'=>'081234567112',
     'alamat'=>'Jl. Tlogomas 99, Malang','kab'=>'Kota Malang','kec'=>'Lowokwaru','kel'=>'Tlogomas','lat'=>-7.9340,'lng'=>112.6190,
     'produk_jenis'=>'deposito','produk_sub'=>'Deposito 12 bulan','nominal'=>1_000_000_000,'tujuan'=>'Deposito jangka panjang',
     'jenis_usaha'=>null,'lama_usaha_thn'=>null,'omzet'=>null,'jaminan'=>null,
     'status'=>'submit','score'=>97,'sumber'=>'monbis','catatan'=>'Top deposan, perlu approval Kacab.',
     'kode_kantor'=>'002','ao_id'=>5,'inputter_id'=>5,'created_at'=>'2026-05-26 09:00','submitted_at'=>'2026-05-26 10:30'],

    // ========== Cabang 003 (Sidoarjo) ==========
    ['id'=>13,'nama'=>'CV Konveksi Berkah','pemilik'=>'Bu Wiwik','nik'=>'3515010505820013','telp'=>'081234567113',
     'alamat'=>'Jl. KH. Mukmin 15, Sidoarjo','kab'=>'Kab Sidoarjo','kec'=>'Sidoarjo','kel'=>'Lemahputro','lat'=>-7.4380,'lng'=>112.7080,
     'produk_jenis'=>'kredit','produk_sub'=>'KMK','nominal'=>180_000_000,'tujuan'=>'Tambah mesin jahit',
     'jenis_usaha'=>'Manufaktur','lama_usaha_thn'=>4,'omzet'=>45_000_000,'jaminan'=>'Sertifikat Tanah',
     'status'=>'open','score'=>72,'sumber'=>'kanvasing','catatan'=>'Baru ditemukan saat kanvasing pasar.',
     'kode_kantor'=>'003','ao_id'=>8,'inputter_id'=>8,'created_at'=>'2026-05-26 15:00','submitted_at'=>null],

    ['id'=>14,'nama'=>'Toko Sepatu Modern','pemilik'=>'Pak Chandra','nik'=>'3515010101750014','telp'=>'081234567114',
     'alamat'=>'Jl. Pahlawan 33, Sidoarjo','kab'=>'Kab Sidoarjo','kec'=>'Sidoarjo','kel'=>'Sidoklumpuk','lat'=>-7.4480,'lng'=>112.7200,
     'produk_jenis'=>'kredit','produk_sub'=>'Top Up KMK','nominal'=>50_000_000,'tujuan'=>'Top up modal kerja',
     'jenis_usaha'=>'Perdagangan','lama_usaha_thn'=>5,'omzet'=>20_000_000,'jaminan'=>'BPKB Motor',
     'status'=>'submit','score'=>86,'sumber'=>'existing','catatan'=>'Existing lancar, top-up.',
     'kode_kantor'=>'003','ao_id'=>8,'inputter_id'=>8,'created_at'=>'2026-05-19 11:00','submitted_at'=>'2026-05-23 09:00'],

    ['id'=>15,'nama'=>'Bu Lilis Tabungan','pemilik'=>'Bu Lilis','nik'=>'3515010505900015','telp'=>'081234567115',
     'alamat'=>'Jl. A. Yani 45, Sidoarjo','kab'=>'Kab Sidoarjo','kec'=>'Sidoarjo','kel'=>'Sidokare','lat'=>-7.4290,'lng'=>112.7150,
     'produk_jenis'=>'tabungan','produk_sub'=>'Tabungan Berkah','nominal'=>25_000_000,'tujuan'=>'Tabungan haji',
     'jenis_usaha'=>null,'lama_usaha_thn'=>null,'omzet'=>null,'jaminan'=>null,
     'status'=>'pending','score'=>80,'sumber'=>'referral','catatan'=>'Belum sempat ke kantor.',
     'kode_kantor'=>'003','ao_id'=>8,'inputter_id'=>11,'created_at'=>'2026-05-25 16:00','submitted_at'=>null],

    ['id'=>16,'nama'=>'Bengkel Kreatif','pemilik'=>'Pak Budi','nik'=>'3515010101800016','telp'=>'081234567116',
     'alamat'=>'Jl. Gajah Mada 88, Sidoarjo','kab'=>'Kab Sidoarjo','kec'=>'Buduran','kel'=>'Sukorejo','lat'=>-7.4540,'lng'=>112.7340,
     'produk_jenis'=>'kredit','produk_sub'=>'KI','nominal'=>250_000_000,'tujuan'=>'Beli alat las baru',
     'jenis_usaha'=>'Jasa','lama_usaha_thn'=>3,'omzet'=>22_000_000,'jaminan'=>'BPKB Mobil',
     'status'=>'open','score'=>62,'sumber'=>'walk_in','catatan'=>'Score COLD, perlu follow up intensif.',
     'kode_kantor'=>'003','ao_id'=>8,'inputter_id'=>11,'created_at'=>'2026-05-26 10:00','submitted_at'=>null],
];

// =============================
// HELPER E-PROSPEK
// =============================

if (!function_exists('ep_status_label')) {
    function ep_status_label(?string $s): string {
        return [
            'open'      => 'Open',
            'pending'   => 'Pending Delegasi',
            'submit'    => 'Submitted',
            'realisasi' => 'Realisasi',
            'reject'    => 'Ditolak',
        ][$s] ?? '-';
    }
}

if (!function_exists('ep_status_color')) {
    function ep_status_color(?string $s): string {
        return [
            'open'      => 'bg-gray-100 text-gray-700 ring-1 ring-gray-200',
            'pending'   => 'bg-amber-100 text-amber-700 ring-1 ring-amber-200',
            'submit'    => 'bg-blue-100 text-blue-700 ring-1 ring-blue-200',
            'realisasi' => 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200',
            'reject'    => 'bg-rose-100 text-rose-700 ring-1 ring-rose-200',
        ][$s] ?? 'bg-gray-100 text-gray-600';
    }
}

if (!function_exists('ep_produk_label')) {
    function ep_produk_label(string $p): string {
        return [
            'tabungan' => 'Tabungan',
            'deposito' => 'Deposito',
            'kredit'   => 'Kredit',
            'aset'     => 'Aset',
        ][$p] ?? ucfirst($p);
    }
}

if (!function_exists('ep_produk_color')) {
    function ep_produk_color(string $p): string {
        return [
            'tabungan' => 'bg-cyan-100 text-cyan-700 ring-1 ring-cyan-200',
            'deposito' => 'bg-purple-100 text-purple-700 ring-1 ring-purple-200',
            'kredit'   => 'bg-orange-100 text-orange-700 ring-1 ring-orange-200',
            'aset'     => 'bg-pink-100 text-pink-700 ring-1 ring-pink-200',
        ][$p] ?? 'bg-gray-100 text-gray-600';
    }
}

if (!function_exists('ep_score_label')) {
    function ep_score_label(int $score): string {
        if ($score >= 80) return 'HOT';
        if ($score >= 60) return 'WARM';
        return 'COLD';
    }
}

if (!function_exists('ep_score_color')) {
    function ep_score_color(int $score): string {
        if ($score >= 80) return 'bg-red-100 text-red-700 ring-1 ring-red-200';
        if ($score >= 60) return 'bg-amber-100 text-amber-700 ring-1 ring-amber-200';
        return 'bg-blue-100 text-blue-700 ring-1 ring-blue-200';
    }
}

if (!function_exists('ep_sumber_label')) {
    function ep_sumber_label(?string $s): string {
        return [
            'walk_in'    => 'Walk-in',
            'referral'   => 'Referral',
            'kanvasing'  => 'Kanvasing',
            'online'     => 'Online',
            'hot_lead'   => 'Hot Lead',
            'monbis'     => 'MonBis',
            'existing'   => 'Existing',
        ][$s] ?? ucfirst($s ?? '-');
    }
}

if (!function_exists('ep_query_with')) {
    function ep_query_with(array $override = []): string {
        $merged = array_merge($_GET, $override);
        return '?' . http_build_query($merged);
    }
}

if (!function_exists('ep_prospek_by_id')) {
    function ep_prospek_by_id(array $prospeks, int $id): ?array {
        foreach ($prospeks as $p) if ($p['id'] === $id) return $p;
        return null;
    }
}

// =============================
// DATA TURUNAN
// =============================
$prospekFiltered = vao_filter_by_kantor($prospeks, $filterKodeKantor);

$prospekByStatus = [
    'open'      => array_values(array_filter($prospekFiltered, fn($p) => $p['status'] === 'open')),
    'pending'   => array_values(array_filter($prospekFiltered, fn($p) => $p['status'] === 'pending')),
    'submit'    => array_values(array_filter($prospekFiltered, fn($p) => $p['status'] === 'submit')),
    'realisasi' => array_values(array_filter($prospekFiltered, fn($p) => $p['status'] === 'realisasi')),
    'reject'    => array_values(array_filter($prospekFiltered, fn($p) => $p['status'] === 'reject')),
];

$prospekByProduk = [];
foreach ($prospekFiltered as $p) {
    $k = $p['produk_jenis'];
    if (!isset($prospekByProduk[$k])) $prospekByProduk[$k] = ['count' => 0, 'nominal' => 0];
    $prospekByProduk[$k]['count']++;
    $prospekByProduk[$k]['nominal'] += $p['nominal'];
}

// Prospek milik AO sekarang (untuk mobile)
$myProspeks = array_values(array_filter($prospeks, fn($p) =>
    $p['ao_id'] === $currentInputterId || $p['inputter_id'] === $currentInputterId));

// Prospek pending delegasi (untuk admin)
$prospekPendingDelegasi = array_values(array_filter($prospekFiltered, fn($p) =>
    in_array($p['status'], ['open','pending']) && empty($p['ao_id'])));
