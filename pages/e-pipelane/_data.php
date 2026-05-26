<?php
/**
 * E-Pipelane - Shared dummy data + helpers.
 * Track SLA proses kredit (7 stages) — extends data dari e-prospek (status='submit'/realisasi/reject).
 */

require_once __DIR__ . '/../e-prospek/_data.php';

// =============================
// MASTER STAGES (config)
// =============================
$stagesCfg = [
    1 => ['key'=>'berkas',     'label'=>'Pengumpulan Berkas',  'sla_days'=>2, 'pic'=>'AO Kredit',     'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
    2 => ['key'=>'bi_check',   'label'=>'BI Checking / SLIK',  'sla_days'=>1, 'pic'=>'AO Kredit',     'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>'],
    3 => ['key'=>'survey',     'label'=>'Survey Lapangan',     'sla_days'=>2, 'pic'=>'AO Kredit',     'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>'],
    4 => ['key'=>'analisa',    'label'=>'Analisa Kredit',      'sla_days'=>2, 'pic'=>'AO Kredit',     'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'],
    5 => ['key'=>'komite',     'label'=>'Komite Kredit',       'sla_days'=>2, 'pic'=>'Kacab/Komite',  'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
    6 => ['key'=>'akad',       'label'=>'Akad / Realisasi',    'sla_days'=>1, 'pic'=>'AO + CS',       'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'],
    7 => ['key'=>'final',      'label'=>'Final (Diterima/Ditolak)', 'sla_days'=>0, 'pic'=>'-',     'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
];

// =============================
// MASTER PIPELANES (linked ke prospek)
// =============================
// 1 prospek (kredit, status submit/realisasi/reject) = 1 pipelane.
// Status pipelane: 'in_progress' / 'approved' / 'rejected'

$pipelanes = [
    // Prospek #1 (Toko Elektronik Cahaya, KMK 200jt) - di stage 4 Analisa
    [
        'id'=>1, 'prospek_id'=>1, 'ao_id'=>1, 'kode_kantor'=>'001',
        'current_stage'=>4, 'status'=>'in_progress', 'priority'=>'normal',
        'started_at'=>'2026-05-22 14:00',
        'sla_due_at'=>'2026-05-29 17:00',
        'stages'=>[
            1 => ['started_at'=>'2026-05-22 14:00','finished_at'=>'2026-05-23 16:30','status'=>'done','note'=>'Berkas lengkap','by_user'=>'Harry Pratama'],
            2 => ['started_at'=>'2026-05-23 17:00','finished_at'=>'2026-05-24 11:00','status'=>'done','note'=>'BI Checking clean, kol 1','by_user'=>'Harry Pratama'],
            3 => ['started_at'=>'2026-05-24 11:30','finished_at'=>'2026-05-26 14:00','status'=>'done','note'=>'Survey ke lokasi, usaha aktif','by_user'=>'Harry Pratama'],
            4 => ['started_at'=>'2026-05-26 15:00','finished_at'=>null,'status'=>'in_progress','note'=>'Sedang analisa NTI & cash flow','by_user'=>'Harry Pratama'],
        ],
        'docs_count'=>5,
    ],
    // Prospek #4 (CV Mitra Konstruksi, KI 500jt) - di stage 5 Komite, hampir overdue
    [
        'id'=>2, 'prospek_id'=>4, 'ao_id'=>4, 'kode_kantor'=>'001',
        'current_stage'=>5, 'status'=>'in_progress', 'priority'=>'high',
        'started_at'=>'2026-05-19 10:00',
        'sla_due_at'=>'2026-05-27 17:00',
        'stages'=>[
            1 => ['started_at'=>'2026-05-19 10:00','finished_at'=>'2026-05-20 14:00','status'=>'done','note'=>'Berkas lengkap','by_user'=>'Ahmad Fadli'],
            2 => ['started_at'=>'2026-05-20 14:30','finished_at'=>'2026-05-21 09:00','status'=>'done','note'=>'BI clean kol 1','by_user'=>'Ahmad Fadli'],
            3 => ['started_at'=>'2026-05-21 10:00','finished_at'=>'2026-05-22 16:00','status'=>'done','note'=>'Survey alat berat di lokasi','by_user'=>'Ahmad Fadli'],
            4 => ['started_at'=>'2026-05-22 16:30','finished_at'=>'2026-05-25 14:00','status'=>'done','note'=>'Analisa OK, NTI sehat','by_user'=>'Ahmad Fadli'],
            5 => ['started_at'=>'2026-05-25 15:00','finished_at'=>null,'status'=>'in_progress','note'=>'Menunggu rapat komite kacab','by_user'=>'Ahmad Fadli'],
        ],
        'docs_count'=>8,
    ],
    // Prospek #9 (Warung Soto Lezat, 80jt) - di stage 3 Survey
    [
        'id'=>3, 'prospek_id'=>9, 'ao_id'=>5, 'kode_kantor'=>'002',
        'current_stage'=>3, 'status'=>'in_progress', 'priority'=>'normal',
        'started_at'=>'2026-05-21 13:00',
        'sla_due_at'=>'2026-05-28 17:00',
        'stages'=>[
            1 => ['started_at'=>'2026-05-21 13:00','finished_at'=>'2026-05-22 15:00','status'=>'done','note'=>'Berkas lengkap','by_user'=>'Dewi Kartika'],
            2 => ['started_at'=>'2026-05-22 15:30','finished_at'=>'2026-05-23 10:00','status'=>'done','note'=>'BI Checking OK','by_user'=>'Dewi Kartika'],
            3 => ['started_at'=>'2026-05-23 11:00','finished_at'=>null,'status'=>'in_progress','note'=>'Sedang survey lokasi cabang baru','by_user'=>'Dewi Kartika'],
        ],
        'docs_count'=>4,
    ],
    // Prospek #14 (Toko Sepatu Modern, top up 50jt) - stage 2 BI Checking
    [
        'id'=>4, 'prospek_id'=>14, 'ao_id'=>8, 'kode_kantor'=>'003',
        'current_stage'=>2, 'status'=>'in_progress', 'priority'=>'normal',
        'started_at'=>'2026-05-23 09:00',
        'sla_due_at'=>'2026-05-30 17:00',
        'stages'=>[
            1 => ['started_at'=>'2026-05-23 09:00','finished_at'=>'2026-05-24 14:00','status'=>'done','note'=>'Berkas existing top-up','by_user'=>'Eko Prasetyo'],
            2 => ['started_at'=>'2026-05-24 15:00','finished_at'=>null,'status'=>'in_progress','note'=>'Cek SLIK','by_user'=>'Eko Prasetyo'],
        ],
        'docs_count'=>3,
    ],
    // Prospek #12 (Pak Subroto Deposito) - actually this is deposito not kredit, skip
    // Prospek #7 (CV Maju Bersama) - sudah final REJECTED
    [
        'id'=>5, 'prospek_id'=>7, 'ao_id'=>4, 'kode_kantor'=>'001',
        'current_stage'=>7, 'status'=>'rejected', 'priority'=>'normal',
        'started_at'=>'2026-05-08 14:00',
        'sla_due_at'=>'2026-05-15 17:00',
        'stages'=>[
            1 => ['started_at'=>'2026-05-08 14:00','finished_at'=>'2026-05-09 11:00','status'=>'done','note'=>'Berkas lengkap','by_user'=>'Ahmad Fadli'],
            2 => ['started_at'=>'2026-05-09 13:00','finished_at'=>'2026-05-10 09:00','status'=>'done','note'=>'BI: kol 2 ada di bank lain','by_user'=>'Ahmad Fadli'],
            3 => ['started_at'=>'2026-05-10 10:00','finished_at'=>'2026-05-11 16:00','status'=>'done','note'=>'Survey: usaha kurang ramai','by_user'=>'Ahmad Fadli'],
            4 => ['started_at'=>'2026-05-12 09:00','finished_at'=>'2026-05-14 11:00','status'=>'done','note'=>'Cash flow tidak konsisten','by_user'=>'Ahmad Fadli'],
            5 => ['started_at'=>'2026-05-14 13:00','finished_at'=>'2026-05-15 10:00','status'=>'done','note'=>'Komite REJECT','by_user'=>'Komite'],
            7 => ['started_at'=>'2026-05-15 10:00','finished_at'=>'2026-05-15 10:00','status'=>'done','note'=>'Status: DITOLAK','by_user'=>'Sistem'],
        ],
        'docs_count'=>6,
    ],
];

// =============================
// HELPER E-PIPELANE
// =============================

if (!function_exists('epl_pipelane_status_label')) {
    function epl_pipelane_status_label(string $s): string {
        return ['in_progress'=>'Dalam Proses','approved'=>'Disetujui','rejected'=>'Ditolak'][$s] ?? $s;
    }
}

if (!function_exists('epl_pipelane_status_color')) {
    function epl_pipelane_status_color(string $s): string {
        return [
            'in_progress' => 'bg-blue-100 text-blue-700 ring-1 ring-blue-200',
            'approved'    => 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200',
            'rejected'    => 'bg-rose-100 text-rose-700 ring-1 ring-rose-200',
        ][$s] ?? 'bg-gray-100 text-gray-600';
    }
}

if (!function_exists('epl_priority_color')) {
    function epl_priority_color(string $p): string {
        return [
            'high'   => 'bg-red-100 text-red-700',
            'normal' => 'bg-gray-100 text-gray-600',
            'low'    => 'bg-blue-100 text-blue-700',
        ][$p] ?? 'bg-gray-100 text-gray-600';
    }
}

if (!function_exists('epl_pipelane_by_id')) {
    function epl_pipelane_by_id(array $list, int $id): ?array {
        foreach ($list as $p) if ($p['id'] === $id) return $p;
        return null;
    }
}

if (!function_exists('epl_pipelane_by_prospek')) {
    function epl_pipelane_by_prospek(array $list, int $prospekId): ?array {
        foreach ($list as $p) if ($p['prospek_id'] === $prospekId) return $p;
        return null;
    }
}

if (!function_exists('epl_sla_status')) {
    /** Returns ['ontrack'|'warning'|'overdue', text label, jam_tersisa]. */
    function epl_sla_status(array $pipelane): array {
        if ($pipelane['status'] !== 'in_progress') return ['done', 'Selesai', 0];
        $now  = strtotime(date('Y-m-d H:i:s'));
        $due  = strtotime($pipelane['sla_due_at']);
        $diff = $due - $now;
        if ($diff < 0) return ['overdue', 'Terlewat', abs((int)round($diff / 3600))];
        $h = (int)round($diff / 3600);
        if ($h <= 24) return ['warning', "$h jam lagi", $h];
        $d = (int)round($diff / 86400);
        return ['ontrack', "$d hari lagi", $d];
    }
}

if (!function_exists('epl_total_duration')) {
    /** Total durasi proses dari started_at sampai sekarang/selesai. Format human-readable. */
    function epl_total_duration(array $pipelane): string {
        $start = strtotime($pipelane['started_at']);
        $end = $pipelane['status'] !== 'in_progress'
            ? strtotime($pipelane['stages'][7]['finished_at'] ?? $pipelane['stages'][6]['finished_at'] ?? $pipelane['stages'][5]['finished_at'] ?? $pipelane['started_at'])
            : strtotime(date('Y-m-d H:i:s'));
        $sec = $end - $start;
        $d = (int)floor($sec / 86400);
        $h = (int)floor(($sec % 86400) / 3600);
        if ($d >= 1) return "$d hari" . ($h > 0 ? " $h jam" : '');
        return "$h jam";
    }
}

// =============================
// DATA TURUNAN setelah filter
// =============================
$pipelaneFiltered = vao_filter_by_kantor($pipelanes, $filterKodeKantor);

// Group by current_stage
$pipelaneByStage = [];
foreach ($pipelaneFiltered as $pl) {
    $s = $pl['current_stage'];
    $pipelaneByStage[$s] = $pipelaneByStage[$s] ?? [];
    $pipelaneByStage[$s][] = $pl;
}

// SLA breakdown
$pipelaneSlaBreakdown = ['ontrack'=>0,'warning'=>0,'overdue'=>0,'done'=>0];
foreach ($pipelaneFiltered as $pl) {
    [$status] = epl_sla_status($pl);
    $pipelaneSlaBreakdown[$status]++;
}

// Pipelane milik AO sekarang (untuk mobile)
$myPipelanes = array_values(array_filter($pipelanes, fn($pl) => $pl['ao_id'] === $currentInputterId));
