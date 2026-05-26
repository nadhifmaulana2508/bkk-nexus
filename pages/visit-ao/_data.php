<?php
/**
 * Visit AO - Shared Dummy Data + Helpers
 * 
 * File ini menyediakan data dummy untuk seluruh modul Visit AO.
 * Saat backend siap, ganti $debiturs/$kunjungans/dll dengan hasil query API/DB
 * yang menerima param: kode_kantor, closing_date, harian_date.
 * 
 * Konvensi:
 *  - kode_kantor "000" = Pusat (akses semua cabang)
 *  - kode_kantor "001"+ = Cabang spesifik
 */

// ================================
// FILTER PARAMS (dari query string)
// ================================
$filterKodeKantor  = $_GET['kode_kantor']  ?? '000';
$filterClosingDate = $_GET['closing_date'] ?? date('Y-m-d', strtotime('last day of last month'));
$filterHarianDate  = $_GET['harian_date']  ?? date('Y-m-d');

// ================================
// MASTER: KANTOR / CABANG
// ================================
$kantors = [
    ['kode' => '000', 'nama' => 'Kantor Pusat (Semua Cabang)'],
    ['kode' => '001', 'nama' => 'Cabang Surabaya'],
    ['kode' => '002', 'nama' => 'Cabang Malang'],
    ['kode' => '003', 'nama' => 'Cabang Sidoarjo'],
];

// ================================
// MASTER: AO
// ================================
$aos = [
    // Cabang Surabaya (001)
    ['id' => 1, 'nama' => 'Harry Pratama',   'role' => 'ao_kredit',       'kode_kantor' => '001', 'inisial' => 'HP'],
    ['id' => 2, 'nama' => 'Budi Santoso',    'role' => 'ao_remedial_fe',  'kode_kantor' => '001', 'inisial' => 'BS'],
    ['id' => 3, 'nama' => 'Sari Wulan',      'role' => 'ao_remedial_be',  'kode_kantor' => '001', 'inisial' => 'SW'],
    ['id' => 4, 'nama' => 'Ahmad Fadli',     'role' => 'ao_kredit',       'kode_kantor' => '001', 'inisial' => 'AF'],

    // Cabang Malang (002)
    ['id' => 5, 'nama' => 'Dewi Kartika',    'role' => 'ao_kredit',       'kode_kantor' => '002', 'inisial' => 'DK'],
    ['id' => 6, 'nama' => 'Rizki Hidayat',   'role' => 'ao_remedial_fe',  'kode_kantor' => '002', 'inisial' => 'RH'],
    ['id' => 7, 'nama' => 'Indah Purnama',   'role' => 'ao_remedial_be',  'kode_kantor' => '002', 'inisial' => 'IP'],

    // Cabang Sidoarjo (003)
    ['id' => 8, 'nama' => 'Eko Prasetyo',    'role' => 'ao_kredit',       'kode_kantor' => '003', 'inisial' => 'EP'],
    ['id' => 9, 'nama' => 'Lina Marlina',    'role' => 'ao_remedial_fe',  'kode_kantor' => '003', 'inisial' => 'LM'],
    ['id' => 10,'nama' => 'Galih Permadi',   'role' => 'ao_remedial_be',  'kode_kantor' => '003', 'inisial' => 'GP'],
];

// Default AO yang sedang login (untuk view mobile lapangan)
// Saat SSO terintegrasi, ganti dengan AO dari token JWT.
$currentAoId = 1; // Harry Pratama

// ================================
// MASTER: DEBITUR
// ================================
// Field movement: 'new', 'paid_off', 'improve', 'stay', 'worse'
//   - new      : debitur baru bulan ini (belum ada bucket bulan lalu)
//   - paid_off : sudah lunas / berhasil bayar penuh
//   - improve  : bucket DPD turun (perbaikan)
//   - stay     : bucket DPD sama
//   - worse    : bucket DPD naik (pemburukan)
//
// Field bucket_prev menyimpan bucket bulan lalu untuk visualisasi pergerakan.

$debiturs = [
    // ===== Cabang 001 - AO 1 (Harry, ao_kredit) =====
    ['id'=>1,  'nama'=>'Toko Maju Jaya',    'pemilik'=>'Pak Ahmad',     'no_rek'=>'001-2024-0001', 'nik'=>'3578011234567890', 'telp'=>'081234567001',
     'plafon'=>250_000_000,'baki_debet'=>187_500_000,'angsuran'=>5_500_000,'next_due'=>'2026-06-20','last_payment'=>'2026-05-20',
     'dpd'=>0,  'dpd_bucket'=>'dpd_0',     'bucket_prev'=>'dpd_0',     'movement'=>'stay',
     'kode_kantor'=>'001','ao_id'=>1,'lat'=>-7.2575,'lng'=>112.7521,'alamat'=>'Jl. Merdeka No 12, Surabaya'],

    ['id'=>2,  'nama'=>'CV Berkah Mandiri','pemilik'=>'Bu Siti',        'no_rek'=>'001-2024-0002','nik'=>'3578011234567891','telp'=>'081234567002',
     'plafon'=>500_000_000,'baki_debet'=>410_000_000,'angsuran'=>11_000_000,'next_due'=>'2026-06-15','last_payment'=>'2026-05-14',
     'dpd'=>3,  'dpd_bucket'=>'dpd_1_7',   'bucket_prev'=>'dpd_8_30',  'movement'=>'improve',
     'kode_kantor'=>'001','ao_id'=>1,'lat'=>-7.2756,'lng'=>112.6420,'alamat'=>'Jl. Diponegoro 88, Surabaya'],

    ['id'=>3,  'nama'=>'UD Sumber Rezeki', 'pemilik'=>'Pak Joko',       'no_rek'=>'001-2024-0003','nik'=>'3578011234567892','telp'=>'081234567003',
     'plafon'=>150_000_000,'baki_debet'=>95_000_000, 'angsuran'=>3_500_000,'next_due'=>'2026-06-10','last_payment'=>'2026-05-08',
     'dpd'=>15, 'dpd_bucket'=>'dpd_8_30',  'bucket_prev'=>'dpd_1_7',   'movement'=>'worse',
     'kode_kantor'=>'001','ao_id'=>1,'lat'=>-7.2891,'lng'=>112.7340,'alamat'=>'Jl. Kalianyar No 5, Surabaya'],

    ['id'=>4,  'nama'=>'Sumber Barokah',   'pemilik'=>'Pak Hasan',      'no_rek'=>'001-2024-0004','nik'=>'3578011234567893','telp'=>'081234567004',
     'plafon'=>100_000_000,'baki_debet'=>78_000_000, 'angsuran'=>2_400_000,'next_due'=>'2026-06-25','last_payment'=>'2026-05-25',
     'dpd'=>0,  'dpd_bucket'=>'dpd_0',     'bucket_prev'=>'dpd_0',     'movement'=>'stay',
     'kode_kantor'=>'001','ao_id'=>1,'lat'=>-7.2630,'lng'=>112.7480,'alamat'=>'Jl. Pasar Turi 22, Surabaya'],

    ['id'=>5,  'nama'=>'Toko Sembako Bahagia','pemilik'=>'Bu Tini',     'no_rek'=>'001-2023-0188','nik'=>'3578011234567894','telp'=>'081234567005',
     'plafon'=>75_000_000, 'baki_debet'=>0,         'angsuran'=>0,         'next_due'=>null,         'last_payment'=>'2026-05-31',
     'dpd'=>0,  'dpd_bucket'=>'lunas',     'bucket_prev'=>'dpd_1_7',   'movement'=>'paid_off',
     'kode_kantor'=>'001','ao_id'=>1,'lat'=>-7.2510,'lng'=>112.7490,'alamat'=>'Jl. Indrapura 9, Surabaya'],

    // ===== Cabang 001 - AO 2 (Budi, ao_remedial_fe) =====
    ['id'=>6,  'nama'=>'Bengkel Motor Andalan','pemilik'=>'Pak Hendro','no_rek'=>'001-2023-0099','nik'=>'3578011234567895','telp'=>'081234567006',
     'plafon'=>120_000_000,'baki_debet'=>96_000_000, 'angsuran'=>3_000_000,'next_due'=>'2026-04-15','last_payment'=>'2026-03-15',
     'dpd'=>45, 'dpd_bucket'=>'dpd_31_60', 'bucket_prev'=>'dpd_8_30',  'movement'=>'worse',
     'kode_kantor'=>'001','ao_id'=>2,'lat'=>-7.2920,'lng'=>112.7150,'alamat'=>'Jl. Raya Ngagel 121, Surabaya'],

    ['id'=>7,  'nama'=>'Warung Bu Endang','pemilik'=>'Bu Endang',       'no_rek'=>'001-2023-0145','nik'=>'3578011234567896','telp'=>'081234567007',
     'plafon'=>50_000_000, 'baki_debet'=>32_000_000, 'angsuran'=>1_400_000,'next_due'=>'2026-04-20','last_payment'=>'2026-03-20',
     'dpd'=>52, 'dpd_bucket'=>'dpd_31_60', 'bucket_prev'=>'dpd_31_60', 'movement'=>'stay',
     'kode_kantor'=>'001','ao_id'=>2,'lat'=>-7.3010,'lng'=>112.7290,'alamat'=>'Jl. Jagir Wonokromo 44, Surabaya'],

    ['id'=>8,  'nama'=>'Toko Plastik Murah','pemilik'=>'Pak Wibowo',    'no_rek'=>'001-2023-0212','nik'=>'3578011234567897','telp'=>'081234567008',
     'plafon'=>200_000_000,'baki_debet'=>165_000_000,'angsuran'=>4_800_000,'next_due'=>'2026-03-10','last_payment'=>'2026-02-10',
     'dpd'=>85, 'dpd_bucket'=>'dpd_61_90', 'bucket_prev'=>'dpd_31_60', 'movement'=>'worse',
     'kode_kantor'=>'001','ao_id'=>2,'lat'=>-7.2780,'lng'=>112.7060,'alamat'=>'Jl. Demak 17, Surabaya'],

    ['id'=>9,  'nama'=>'CV Karya Mandiri','pemilik'=>'Pak Slamet',      'no_rek'=>'001-2023-0078','nik'=>'3578011234567898','telp'=>'081234567009',
     'plafon'=>180_000_000,'baki_debet'=>140_000_000,'angsuran'=>4_200_000,'next_due'=>'2026-04-05','last_payment'=>'2026-04-25',
     'dpd'=>5,  'dpd_bucket'=>'dpd_1_7',   'bucket_prev'=>'dpd_61_90', 'movement'=>'improve',
     'kode_kantor'=>'001','ao_id'=>2,'lat'=>-7.2650,'lng'=>112.7330,'alamat'=>'Jl. Kapasari 33, Surabaya'],

    // ===== Cabang 001 - AO 3 (Sari, ao_remedial_be) =====
    ['id'=>10, 'nama'=>'Toko Kelontong Sederhana','pemilik'=>'Bu Marni','no_rek'=>'001-2022-0033','nik'=>'3578011234567899','telp'=>'081234567010',
     'plafon'=>40_000_000, 'baki_debet'=>38_500_000, 'angsuran'=>1_200_000,'next_due'=>'2025-11-10','last_payment'=>'2025-10-10',
     'dpd'=>225,'dpd_bucket'=>'dpd_181_plus','bucket_prev'=>'dpd_181_plus','movement'=>'stay',
     'kode_kantor'=>'001','ao_id'=>3,'lat'=>-7.3120,'lng'=>112.7600,'alamat'=>'Jl. Wonorejo 8, Surabaya'],

    ['id'=>11, 'nama'=>'CV Mitra Jaya',    'pemilik'=>'Pak Bambang',   'no_rek'=>'001-2022-0050','nik'=>'3578011234567900','telp'=>'081234567011',
     'plafon'=>300_000_000,'baki_debet'=>275_000_000,'angsuran'=>7_500_000,'next_due'=>'2025-09-20','last_payment'=>'2025-08-20',
     'dpd'=>275,'dpd_bucket'=>'dpd_181_plus','bucket_prev'=>'dpd_91_180','movement'=>'worse',
     'kode_kantor'=>'001','ao_id'=>3,'lat'=>-7.2980,'lng'=>112.7780,'alamat'=>'Jl. Mulyosari 90, Surabaya'],

    ['id'=>12, 'nama'=>'Toko Bangunan Setia','pemilik'=>'Pak Yusuf',   'no_rek'=>'001-2021-0011','nik'=>'3578011234567901','telp'=>'081234567012',
     'plafon'=>500_000_000,'baki_debet'=>485_000_000,'angsuran'=>12_500_000,'next_due'=>'2025-06-15','last_payment'=>'2025-05-15',
     'dpd'=>365,'dpd_bucket'=>'ph',        'bucket_prev'=>'dpd_181_plus','movement'=>'worse',
     'kode_kantor'=>'001','ao_id'=>3,'lat'=>-7.2410,'lng'=>112.7280,'alamat'=>'Jl. Perak Barat 12, Surabaya'],

    // ===== Cabang 002 - AO 5 (Dewi, ao_kredit) =====
    ['id'=>13, 'nama'=>'Warung Soto Cak Ali','pemilik'=>'Pak Ali',     'no_rek'=>'002-2024-0021','nik'=>'3573011234567902','telp'=>'081234567013',
     'plafon'=>60_000_000, 'baki_debet'=>45_000_000, 'angsuran'=>1_700_000,'next_due'=>'2026-06-12','last_payment'=>'2026-05-12',
     'dpd'=>0,  'dpd_bucket'=>'dpd_0',     'bucket_prev'=>'dpd_0',     'movement'=>'stay',
     'kode_kantor'=>'002','ao_id'=>5,'lat'=>-7.9660,'lng'=>112.6326,'alamat'=>'Jl. Soekarno Hatta 23, Malang'],

    ['id'=>14, 'nama'=>'Toko Buah Segar',  'pemilik'=>'Bu Rini',       'no_rek'=>'002-2024-0034','nik'=>'3573011234567903','telp'=>'081234567014',
     'plafon'=>90_000_000, 'baki_debet'=>72_000_000, 'angsuran'=>2_200_000,'next_due'=>'2026-06-18','last_payment'=>'2026-05-16',
     'dpd'=>2,  'dpd_bucket'=>'dpd_1_7',   'bucket_prev'=>'dpd_0',     'movement'=>'worse',
     'kode_kantor'=>'002','ao_id'=>5,'lat'=>-7.9810,'lng'=>112.6210,'alamat'=>'Jl. Ijen 47, Malang'],

    ['id'=>15, 'nama'=>'CV Inti Cipta',    'pemilik'=>'Pak Yanto',     'no_rek'=>'002-2024-0045','nik'=>'3573011234567904','telp'=>'081234567015',
     'plafon'=>200_000_000,'baki_debet'=>0,         'angsuran'=>0,         'next_due'=>null,         'last_payment'=>'2026-05-28',
     'dpd'=>0,  'dpd_bucket'=>'lunas',     'bucket_prev'=>'dpd_8_30',  'movement'=>'paid_off',
     'kode_kantor'=>'002','ao_id'=>5,'lat'=>-7.9550,'lng'=>112.6510,'alamat'=>'Jl. Borobudur 11, Malang'],

    // ===== Cabang 002 - AO 6 (Rizki, ao_remedial_fe) =====
    ['id'=>16, 'nama'=>'Toko Elektronik Maju','pemilik'=>'Pak Iwan',   'no_rek'=>'002-2023-0066','nik'=>'3573011234567905','telp'=>'081234567016',
     'plafon'=>150_000_000,'baki_debet'=>118_000_000,'angsuran'=>3_900_000,'next_due'=>'2026-04-08','last_payment'=>'2026-03-08',
     'dpd'=>62, 'dpd_bucket'=>'dpd_61_90', 'bucket_prev'=>'dpd_31_60', 'movement'=>'worse',
     'kode_kantor'=>'002','ao_id'=>6,'lat'=>-7.9420,'lng'=>112.6710,'alamat'=>'Jl. Letjen S. Parman 19, Malang'],

    ['id'=>17, 'nama'=>'UD Sumber Pangan', 'pemilik'=>'Bu Lastri',     'no_rek'=>'002-2023-0089','nik'=>'3573011234567906','telp'=>'081234567017',
     'plafon'=>80_000_000, 'baki_debet'=>62_000_000, 'angsuran'=>2_000_000,'next_due'=>'2026-04-22','last_payment'=>'2026-04-30',
     'dpd'=>22, 'dpd_bucket'=>'dpd_8_30',  'bucket_prev'=>'dpd_61_90', 'movement'=>'improve',
     'kode_kantor'=>'002','ao_id'=>6,'lat'=>-7.9870,'lng'=>112.6480,'alamat'=>'Jl. Kawi 56, Malang'],

    // ===== Cabang 002 - AO 7 (Indah, ao_remedial_be) =====
    ['id'=>18, 'nama'=>'CV Tunas Harapan', 'pemilik'=>'Pak Subroto',   'no_rek'=>'002-2022-0017','nik'=>'3573011234567907','telp'=>'081234567018',
     'plafon'=>250_000_000,'baki_debet'=>235_000_000,'angsuran'=>6_800_000,'next_due'=>'2025-08-15','last_payment'=>'2025-07-15',
     'dpd'=>310,'dpd_bucket'=>'dpd_181_plus','bucket_prev'=>'dpd_181_plus','movement'=>'stay',
     'kode_kantor'=>'002','ao_id'=>7,'lat'=>-7.9340,'lng'=>112.6190,'alamat'=>'Jl. Tlogomas 99, Malang'],

    ['id'=>19, 'nama'=>'Toko Kosmetik Jelita','pemilik'=>'Bu Yuli',    'no_rek'=>'002-2021-0044','nik'=>'3573011234567908','telp'=>'081234567019',
     'plafon'=>100_000_000,'baki_debet'=>92_000_000, 'angsuran'=>3_000_000,'next_due'=>'2025-04-10','last_payment'=>'2025-03-10',
     'dpd'=>440,'dpd_bucket'=>'ph',        'bucket_prev'=>'ph',        'movement'=>'stay',
     'kode_kantor'=>'002','ao_id'=>7,'lat'=>-7.9190,'lng'=>112.6280,'alamat'=>'Jl. Kahuripan 21, Malang'],

    // ===== Cabang 003 - AO 8 (Eko, ao_kredit) =====
    ['id'=>20, 'nama'=>'Toko Sepatu Modern','pemilik'=>'Pak Chandra', 'no_rek'=>'003-2024-0018','nik'=>'3515011234567909','telp'=>'081234567020',
     'plafon'=>110_000_000,'baki_debet'=>88_000_000, 'angsuran'=>2_700_000,'next_due'=>'2026-06-22','last_payment'=>'2026-05-22',
     'dpd'=>0,  'dpd_bucket'=>'dpd_0',     'bucket_prev'=>'dpd_0',     'movement'=>'stay',
     'kode_kantor'=>'003','ao_id'=>8,'lat'=>-7.4480,'lng'=>112.7200,'alamat'=>'Jl. Pahlawan 33, Sidoarjo'],

    ['id'=>21, 'nama'=>'Konveksi Berkah',  'pemilik'=>'Bu Wiwik',      'no_rek'=>'003-2024-0029','nik'=>'3515011234567910','telp'=>'081234567021',
     'plafon'=>180_000_000,'baki_debet'=>148_000_000,'angsuran'=>4_500_000,'next_due'=>'2026-06-30','last_payment'=>'2026-05-30',
     'dpd'=>0,  'dpd_bucket'=>'dpd_0',     'bucket_prev'=>null,        'movement'=>'new',
     'kode_kantor'=>'003','ao_id'=>8,'lat'=>-7.4380,'lng'=>112.7080,'alamat'=>'Jl. KH. Mukmin 15, Sidoarjo'],

    // ===== Cabang 003 - AO 9 (Lina, ao_remedial_fe) =====
    ['id'=>22, 'nama'=>'Bengkel Las Bersaudara','pemilik'=>'Pak Hartono','no_rek'=>'003-2023-0067','nik'=>'3515011234567911','telp'=>'081234567022',
     'plafon'=>140_000_000,'baki_debet'=>110_000_000,'angsuran'=>3_500_000,'next_due'=>'2026-04-12','last_payment'=>'2026-03-12',
     'dpd'=>58, 'dpd_bucket'=>'dpd_31_60', 'bucket_prev'=>'dpd_31_60', 'movement'=>'stay',
     'kode_kantor'=>'003','ao_id'=>9,'lat'=>-7.4540,'lng'=>112.7340,'alamat'=>'Jl. Gajah Mada 88, Sidoarjo'],

    ['id'=>23, 'nama'=>'Toko Aksesoris Indah','pemilik'=>'Bu Lilis',  'no_rek'=>'003-2023-0091','nik'=>'3515011234567912','telp'=>'081234567023',
     'plafon'=>50_000_000, 'baki_debet'=>40_000_000, 'angsuran'=>1_500_000,'next_due'=>'2026-04-25','last_payment'=>'2026-05-02',
     'dpd'=>4,  'dpd_bucket'=>'dpd_1_7',   'bucket_prev'=>'dpd_31_60', 'movement'=>'improve',
     'kode_kantor'=>'003','ao_id'=>9,'lat'=>-7.4290,'lng'=>112.7150,'alamat'=>'Jl. A. Yani 45, Sidoarjo'],

    // ===== Cabang 003 - AO 10 (Galih, ao_remedial_be) =====
    ['id'=>24, 'nama'=>'Toko Furniture Megah','pemilik'=>'Pak Jaka',  'no_rek'=>'003-2022-0008','nik'=>'3515011234567913','telp'=>'081234567024',
     'plafon'=>350_000_000,'baki_debet'=>320_000_000,'angsuran'=>9_500_000,'next_due'=>'2025-10-18','last_payment'=>'2025-09-18',
     'dpd'=>240,'dpd_bucket'=>'dpd_181_plus','bucket_prev'=>'dpd_181_plus','movement'=>'stay',
     'kode_kantor'=>'003','ao_id'=>10,'lat'=>-7.4610,'lng'=>112.7460,'alamat'=>'Jl. Diponegoro 12, Sidoarjo'],

    ['id'=>25, 'nama'=>'CV Bumi Lestari',  'pemilik'=>'Pak Darmono',   'no_rek'=>'003-2021-0023','nik'=>'3515011234567914','telp'=>'081234567025',
     'plafon'=>200_000_000,'baki_debet'=>195_000_000,'angsuran'=>5_500_000,'next_due'=>'2025-05-08','last_payment'=>'2025-04-08',
     'dpd'=>415,'dpd_bucket'=>'ph',        'bucket_prev'=>'dpd_181_plus','movement'=>'worse',
     'kode_kantor'=>'003','ao_id'=>10,'lat'=>-7.4720,'lng'=>112.7110,'alamat'=>'Jl. Raden Wijaya 7, Sidoarjo'],
];

// ================================
// MASTER: KUNJUNGAN (riwayat visit)
// ================================
$kunjungans = [
    ['id'=>1,'debitur_id'=>1,'ao_id'=>1,'tanggal'=>'2026-05-26','waktu'=>'09:30','lat'=>-7.2575,'lng'=>112.7521,'foto'=>null,
     'hasil'=>'kontak','catatan'=>'Debitur ditemui di toko, usaha lancar, tidak ada masalah pembayaran.','tanggal_janji'=>null,'nominal_janji'=>0],
    ['id'=>2,'debitur_id'=>3,'ao_id'=>1,'tanggal'=>'2026-05-26','waktu'=>'10:45','lat'=>-7.2891,'lng'=>112.7340,'foto'=>null,
     'hasil'=>'janji_bayar','catatan'=>'Debitur janji bayar tunggakan tanggal 30 Mei 2026.','tanggal_janji'=>'2026-05-30','nominal_janji'=>3_500_000],
    ['id'=>3,'debitur_id'=>6,'ao_id'=>2,'tanggal'=>'2026-05-25','waktu'=>'14:20','lat'=>-7.2920,'lng'=>112.7150,'foto'=>null,
     'hasil'=>'janji_bayar','catatan'=>'Janji bayar 2 angsuran tertunggak hari Senin.','tanggal_janji'=>'2026-06-02','nominal_janji'=>6_000_000],
    ['id'=>4,'debitur_id'=>7,'ao_id'=>2,'tanggal'=>'2026-05-25','waktu'=>'15:30','lat'=>-7.3010,'lng'=>112.7290,'foto'=>null,
     'hasil'=>'tidak_kontak','catatan'=>'Tempat usaha tutup. Tetangga bilang debitur sedang ke luar kota.','tanggal_janji'=>null,'nominal_janji'=>0],
    ['id'=>5,'debitur_id'=>8,'ao_id'=>2,'tanggal'=>'2026-05-24','waktu'=>'13:15','lat'=>-7.2780,'lng'=>112.7060,'foto'=>null,
     'hasil'=>'keberatan','catatan'=>'Debitur keberatan, omzet turun drastis. Akan eskalasi ke Kacab.','tanggal_janji'=>null,'nominal_janji'=>0],
    ['id'=>6,'debitur_id'=>10,'ao_id'=>3,'tanggal'=>'2026-05-23','waktu'=>'09:00','lat'=>-7.3120,'lng'=>112.7600,'foto'=>null,
     'hasil'=>'kontak','catatan'=>'Debitur masih usaha kecil, angsuran dicicil per minggu Rp 300rb.','tanggal_janji'=>'2026-06-01','nominal_janji'=>1_200_000],
    ['id'=>7,'debitur_id'=>2,'ao_id'=>1,'tanggal'=>'2026-05-22','waktu'=>'11:00','lat'=>-7.2756,'lng'=>112.6420,'foto'=>null,
     'hasil'=>'sudah_bayar','catatan'=>'Debitur sudah transfer Rp 11 juta hari ini, posisi DPD turun.','tanggal_janji'=>null,'nominal_janji'=>11_000_000],
    ['id'=>8,'debitur_id'=>13,'ao_id'=>5,'tanggal'=>'2026-05-26','waktu'=>'08:30','lat'=>-7.9660,'lng'=>112.6326,'foto'=>null,
     'hasil'=>'kontak','catatan'=>'Warung ramai, omzet bagus. Confirm pembayaran tepat waktu.','tanggal_janji'=>null,'nominal_janji'=>0],
    ['id'=>9,'debitur_id'=>16,'ao_id'=>6,'tanggal'=>'2026-05-25','waktu'=>'13:45','lat'=>-7.9420,'lng'=>112.6710,'foto'=>null,
     'hasil'=>'janji_bayar','catatan'=>'Janji bayar 1 angsuran tanggal 31 Mei.','tanggal_janji'=>'2026-05-31','nominal_janji'=>3_900_000],
    ['id'=>10,'debitur_id'=>22,'ao_id'=>9,'tanggal'=>'2026-05-24','waktu'=>'10:30','lat'=>-7.4540,'lng'=>112.7340,'foto'=>null,
     'hasil'=>'janji_bayar','catatan'=>'Bengkel ramai, debitur janji bayar 2 angsuran tanggal 5 Juni.','tanggal_janji'=>'2026-06-05','nominal_janji'=>7_000_000],
    ['id'=>11,'debitur_id'=>11,'ao_id'=>3,'tanggal'=>'2026-05-20','waktu'=>'14:00','lat'=>-7.2980,'lng'=>112.7780,'foto'=>null,
     'hasil'=>'pindah_domisili','catatan'=>'Rumah kosong, tetangga konfirmasi debitur pindah ke Sidoarjo. Perlu skip tracing.','tanggal_janji'=>null,'nominal_janji'=>0],
    ['id'=>12,'debitur_id'=>14,'ao_id'=>5,'tanggal'=>'2026-05-26','waktu'=>'10:15','lat'=>-7.9810,'lng'=>112.6210,'foto'=>null,
     'hasil'=>'kontak','catatan'=>'Akan transfer minggu ini.','tanggal_janji'=>'2026-05-29','nominal_janji'=>2_200_000],
    ['id'=>13,'debitur_id'=>17,'ao_id'=>6,'tanggal'=>'2026-05-23','waktu'=>'15:00','lat'=>-7.9870,'lng'=>112.6480,'foto'=>null,
     'hasil'=>'sudah_bayar','catatan'=>'Sudah bayar tunggakan, posisi DPD turun signifikan.','tanggal_janji'=>null,'nominal_janji'=>4_000_000],
];

// ================================
// MASTER: JANJI BAYAR (turunan dari kunjungan dengan hasil = 'janji_bayar')
// ================================
$janjiBayars = [
    ['id'=>1,'visit_id'=>2,'debitur_id'=>3, 'ao_id'=>1,'tanggal_janji'=>'2026-05-30','nominal_janji'=>3_500_000,'status'=>'aktif',     'realisasi_tanggal'=>null,'realisasi_nominal'=>0],
    ['id'=>2,'visit_id'=>3,'debitur_id'=>6, 'ao_id'=>2,'tanggal_janji'=>'2026-06-02','nominal_janji'=>6_000_000,'status'=>'aktif',     'realisasi_tanggal'=>null,'realisasi_nominal'=>0],
    ['id'=>3,'visit_id'=>6,'debitur_id'=>10,'ao_id'=>3,'tanggal_janji'=>'2026-06-01','nominal_janji'=>1_200_000,'status'=>'aktif',     'realisasi_tanggal'=>null,'realisasi_nominal'=>0],
    ['id'=>4,'visit_id'=>9,'debitur_id'=>16,'ao_id'=>6,'tanggal_janji'=>'2026-05-31','nominal_janji'=>3_900_000,'status'=>'aktif',     'realisasi_tanggal'=>null,'realisasi_nominal'=>0],
    ['id'=>5,'visit_id'=>10,'debitur_id'=>22,'ao_id'=>9,'tanggal_janji'=>'2026-06-05','nominal_janji'=>7_000_000,'status'=>'aktif',    'realisasi_tanggal'=>null,'realisasi_nominal'=>0],
    ['id'=>6,'visit_id'=>12,'debitur_id'=>14,'ao_id'=>5,'tanggal_janji'=>'2026-05-29','nominal_janji'=>2_200_000,'status'=>'aktif',    'realisasi_tanggal'=>null,'realisasi_nominal'=>0],
    // Realisasi (sudah dipenuhi)
    ['id'=>7,'visit_id'=>13,'debitur_id'=>17,'ao_id'=>6,'tanggal_janji'=>'2026-05-23','nominal_janji'=>4_000_000,'status'=>'terealisasi','realisasi_tanggal'=>'2026-05-23','realisasi_nominal'=>4_000_000],
    ['id'=>8,'visit_id'=>7, 'debitur_id'=>2, 'ao_id'=>1,'tanggal_janji'=>'2026-05-22','nominal_janji'=>11_000_000,'status'=>'terealisasi','realisasi_tanggal'=>'2026-05-22','realisasi_nominal'=>11_000_000],
    // Gagal (terlewat tanpa pembayaran)
    ['id'=>9,'visit_id'=>null,'debitur_id'=>11,'ao_id'=>3,'tanggal_janji'=>'2026-05-15','nominal_janji'=>5_000_000,'status'=>'gagal',  'realisasi_tanggal'=>null,'realisasi_nominal'=>0],
    ['id'=>10,'visit_id'=>null,'debitur_id'=>18,'ao_id'=>7,'tanggal_janji'=>'2026-05-18','nominal_janji'=>6_800_000,'status'=>'gagal', 'realisasi_tanggal'=>null,'realisasi_nominal'=>0],
];

// ================================
// HELPER FUNCTIONS
// ================================

if (!function_exists('vao_filter_by_kantor')) {
    /** Filter array by kode_kantor. '000' = lihat semua. */
    function vao_filter_by_kantor(array $rows, string $kodeKantor, string $field = 'kode_kantor'): array {
        if ($kodeKantor === '000' || $kodeKantor === '') return $rows;
        return array_values(array_filter($rows, fn($r) => ($r[$field] ?? null) === $kodeKantor));
    }
}

if (!function_exists('vao_filter_debitur_by_ao')) {
    /** Ambil debitur yang ditugaskan ke AO tertentu. */
    function vao_filter_debitur_by_ao(array $debiturs, int $aoId): array {
        return array_values(array_filter($debiturs, fn($d) => $d['ao_id'] === $aoId));
    }
}

if (!function_exists('vao_kantor_nama')) {
    function vao_kantor_nama(array $kantors, string $kode): string {
        foreach ($kantors as $k) if ($k['kode'] === $kode) return $k['nama'];
        return $kode;
    }
}

if (!function_exists('vao_ao_by_id')) {
    function vao_ao_by_id(array $aos, int $id): ?array {
        foreach ($aos as $a) if ($a['id'] === $id) return $a;
        return null;
    }
}

if (!function_exists('vao_debitur_by_id')) {
    function vao_debitur_by_id(array $debiturs, int $id): ?array {
        foreach ($debiturs as $d) if ($d['id'] === $id) return $d;
        return null;
    }
}

if (!function_exists('vao_bucket_label')) {
    function vao_bucket_label(?string $bucket): string {
        return [
            'lunas'        => 'Lunas',
            'dpd_0'        => 'DPD 0',
            'dpd_1_7'      => 'DPD 1-7',
            'dpd_8_30'     => 'DPD 8-30',
            'dpd_31_60'    => 'DPD 31-60',
            'dpd_61_90'    => 'DPD 61-90',
            'dpd_91_180'   => 'DPD 91-180',
            'dpd_181_plus' => 'DPD 181+',
            'ph'           => 'PH',
        ][$bucket] ?? ($bucket ?? '-');
    }
}

if (!function_exists('vao_bucket_color')) {
    /** Tailwind classes (bg + text) untuk bucket badge. */
    function vao_bucket_color(?string $bucket): string {
        return [
            'lunas'        => 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200',
            'dpd_0'        => 'bg-green-100 text-green-700 ring-1 ring-green-200',
            'dpd_1_7'      => 'bg-lime-100 text-lime-700 ring-1 ring-lime-200',
            'dpd_8_30'     => 'bg-amber-100 text-amber-700 ring-1 ring-amber-200',
            'dpd_31_60'    => 'bg-orange-100 text-orange-700 ring-1 ring-orange-200',
            'dpd_61_90'    => 'bg-red-100 text-red-700 ring-1 ring-red-200',
            'dpd_91_180'   => 'bg-rose-100 text-rose-700 ring-1 ring-rose-200',
            'dpd_181_plus' => 'bg-rose-200 text-rose-800 ring-1 ring-rose-300',
            'ph'           => 'bg-gray-200 text-gray-800 ring-1 ring-gray-300',
        ][$bucket] ?? 'bg-gray-100 text-gray-600';
    }
}

if (!function_exists('vao_movement_label')) {
    function vao_movement_label(?string $m): string {
        return [
            'new'      => 'Debitur Baru',
            'paid_off' => 'Lunas / Berhasil',
            'improve'  => 'Perbaikan Bucket',
            'stay'     => 'Stay',
            'worse'    => 'Pemburukan',
        ][$m] ?? '-';
    }
}

if (!function_exists('vao_movement_color')) {
    function vao_movement_color(?string $m): string {
        return [
            'new'      => 'bg-blue-100 text-blue-700',
            'paid_off' => 'bg-emerald-100 text-emerald-700',
            'improve'  => 'bg-green-100 text-green-700',
            'stay'     => 'bg-gray-100 text-gray-600',
            'worse'    => 'bg-red-100 text-red-700',
        ][$m] ?? 'bg-gray-100 text-gray-600';
    }
}

if (!function_exists('vao_visit_hasil_label')) {
    function vao_visit_hasil_label(?string $h): string {
        return [
            'kontak'          => 'Kontak',
            'tidak_kontak'    => 'Tidak Kontak',
            'janji_bayar'     => 'Janji Bayar',
            'sudah_bayar'     => 'Sudah Bayar',
            'keberatan'       => 'Keberatan',
            'pindah_domisili' => 'Pindah Domisili',
            'nomor_mati'      => 'Nomor Mati',
        ][$h] ?? '-';
    }
}

if (!function_exists('vao_visit_hasil_color')) {
    function vao_visit_hasil_color(?string $h): string {
        return [
            'kontak'          => 'bg-blue-100 text-blue-700',
            'tidak_kontak'    => 'bg-gray-100 text-gray-600',
            'janji_bayar'     => 'bg-amber-100 text-amber-700',
            'sudah_bayar'     => 'bg-emerald-100 text-emerald-700',
            'keberatan'       => 'bg-orange-100 text-orange-700',
            'pindah_domisili' => 'bg-rose-100 text-rose-700',
            'nomor_mati'      => 'bg-gray-200 text-gray-700',
        ][$h] ?? 'bg-gray-100 text-gray-600';
    }
}

if (!function_exists('vao_role_label')) {
    function vao_role_label(string $role): string {
        return [
            'ao_kredit'       => 'AO Kredit',
            'ao_remedial_fe'  => 'AO Remedial FE',
            'ao_remedial_be'  => 'AO Remedial BE',
        ][$role] ?? $role;
    }
}

if (!function_exists('vao_fmt_rp')) {
    /** Format rupiah ringkas. Untuk < 1jt tampil utuh, > 1jt pakai juta/M. */
    function vao_fmt_rp(int $n): string {
        if ($n >= 1_000_000_000) return 'Rp ' . number_format($n / 1_000_000_000, 1, ',', '.') . ' M';
        if ($n >= 1_000_000)     return 'Rp ' . number_format($n / 1_000_000, 1, ',', '.') . ' jt';
        return 'Rp ' . number_format($n, 0, ',', '.');
    }
}

if (!function_exists('vao_fmt_rp_full')) {
    function vao_fmt_rp_full(int $n): string {
        return 'Rp ' . number_format($n, 0, ',', '.');
    }
}

if (!function_exists('vao_fmt_tgl')) {
    function vao_fmt_tgl(?string $ymd): string {
        if (!$ymd) return '-';
        $bulan = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $parts = explode('-', $ymd);
        if (count($parts) !== 3) return $ymd;
        return (int)$parts[2] . ' ' . ($bulan[(int)$parts[1]] ?? $parts[1]) . ' ' . $parts[0];
    }
}

if (!function_exists('vao_query_with')) {
    /** Build URL query string yang merge filter aktif + override. */
    function vao_query_with(array $override = []): string {
        $merged = array_merge($_GET, $override);
        return '?' . http_build_query($merged);
    }
}

if (!function_exists('vao_compute_movement_stats')) {
    /**
     * Hitung statistik pergerakan bucket dari list debitur.
     * Output: ['new'=>x,'paid_off'=>x,'improve'=>x,'stay'=>x,'worse'=>x,'total'=>x]
     */
    function vao_compute_movement_stats(array $debiturs): array {
        $stats = ['new'=>0,'paid_off'=>0,'improve'=>0,'stay'=>0,'worse'=>0,'total'=>0];
        foreach ($debiturs as $d) {
            $m = $d['movement'] ?? 'stay';
            if (isset($stats[$m])) $stats[$m]++;
            $stats['total']++;
        }
        return $stats;
    }
}

if (!function_exists('vao_compute_bucket_stats')) {
    /**
     * Hitung jumlah debitur + total baki debet per bucket.
     * Output: ['dpd_0'=>['count'=>x,'baki_debet'=>y], ...]
     */
    function vao_compute_bucket_stats(array $debiturs): array {
        $stats = [];
        foreach ($debiturs as $d) {
            $b = $d['dpd_bucket'] ?? 'unknown';
            if (!isset($stats[$b])) $stats[$b] = ['count'=>0,'baki_debet'=>0];
            $stats[$b]['count']++;
            $stats[$b]['baki_debet'] += (int)($d['baki_debet'] ?? 0);
        }
        return $stats;
    }
}

// ================================
// DATA TURUNAN: setelah filter kantor
// ================================
$debiturFiltered    = vao_filter_by_kantor($debiturs,    $filterKodeKantor);
$kunjunganFiltered  = array_values(array_filter($kunjungans, function($v) use ($debiturFiltered) {
    foreach ($debiturFiltered as $d) if ($d['id'] === $v['debitur_id']) return true;
    return false;
}));
$janjiBayarFiltered = array_values(array_filter($janjiBayars, function($jb) use ($debiturFiltered) {
    foreach ($debiturFiltered as $d) if ($d['id'] === $jb['debitur_id']) return true;
    return false;
}));
$aoFiltered         = vao_filter_by_kantor($aos, $filterKodeKantor);
