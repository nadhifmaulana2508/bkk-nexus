# BKK Nexus — AO Credit Operating System

> Sistem operasional terintegrasi untuk **AO Kredit, AO Remedial, dan CS** beserta jajaran atasannya (Kabid Pemasaran, Kepala Cabang, Pincab) di lingkungan Bank Perkreditan Rakyat (BPR).
>
> Tujuan utama: **memudahkan pendataan calon debitur, mempercepat delegasi tugas ke AO yang tepat, mengontrol SLA proses kredit, serta merapikan kunjungan & komitmen awal bulan AO.**

---

## Daftar Isi

1. [Ringkasan & Tujuan Bisnis](#1-ringkasan--tujuan-bisnis)
2. [Pengguna & Hak Akses (Roles)](#2-pengguna--hak-akses-roles)
3. [Mapping Debitur Awal Bulan](#3-mapping-debitur-awal-bulan)
4. [Modul Utama](#4-modul-utama)
   - 4.1 [Dashboard](#41-dashboard)
   - 4.2 [E-Prospek](#42-e-prospek)
   - 4.3 [E-Pipelane (Credit SLA)](#43-e-pipelane-credit-sla)
   - 4.4 [Visit AO](#44-visit-ao)
   - 4.5 [Call AO](#45-call-ao)
5. [Aturan Delegasi](#5-aturan-delegasi)
6. [Diagram Alur Sistem](#6-diagram-alur-sistem)
7. [Notifikasi & SLA](#7-notifikasi--sla)
8. [Model Data (ERD Ringkas)](#8-model-data-erd-ringkas)
9. [Rencana API](#9-rencana-api)
10. [Autentikasi (SSO)](#10-autentikasi-sso)
11. [Tech Stack](#11-tech-stack)
12. [Struktur Folder](#12-struktur-folder)
13. [Setup & Pengembangan Lokal](#13-setup--pengembangan-lokal)
14. [Roadmap Pengembangan](#14-roadmap-pengembangan)

---

## 1. Ringkasan & Tujuan Bisnis

**BKK Nexus** adalah sistem internal yang menjadi *single source of truth* untuk seluruh siklus calon debitur — dari pertama kali ditemukan/diinput, didelegasikan ke AO, dikunjungi, sampai diproses kredit (atau langsung di-update untuk produk tabungan/deposito).

### Masalah yang Diselesaikan

| Masalah Saat Ini | Solusi BKK Nexus |
| --- | --- |
| Data calon debitur tersebar di buku/Excel/WA | Modul **E-Prospek** terpusat dengan kategori produk |
| Atasan kesulitan mendelegasi prospek ke AO yang tepat | **Auto-suggest & wajib delegasi** sesuai jenis produk |
| Proses kredit tidak terukur waktunya (SLA bocor) | **E-Pipelane** dengan timeline status & SLA per tahap |
| Kunjungan AO tidak terpantau atasan | **Visit AO** dengan history, rekap komitmen, dan mapping debitur |
| Tidak ada pemisahan beban kerja AO Kredit & Remedial | **Mapping debitur otomatis** berdasarkan hari menunggak |
| Tabungan/Deposito harus melalui prosedur panjang | **Direct update** dari E-Prospek ke core system |

### Pengguna Sasaran

- **AO Kredit** — kelola prospek baru, proses pengajuan kredit, kunjungan debitur lancar
- **AO Remedial (Front End / Back End)** — tagih & visit debitur menunggak
- **CS (Customer Service)** — input prospek dari walk-in / telepon
- **Kabid Pemasaran** — delegasi prospek, monitor pipeline AO
- **Kepala Cabang / Pincab** — approval, monitor cabang, mendelegasi
- **Admin** — manajemen master data, role, mapping

---

## 2. Pengguna & Hak Akses (Roles)

| Role | Bisa Input Prospek | Bisa Delegasi | Bisa Update Pipeline | Bisa Visit | Lihat Semua AO |
| --- | --- | --- | --- | --- | --- |
| `admin` | ✅ | ✅ | ✅ | — | ✅ |
| `pincab` (Pimpinan Cabang) | ✅ | ✅ (wajib) | Approve | — | ✅ (cabangnya) |
| `kacab` (Kepala Cabang) | ✅ | ✅ (wajib) | Approve | — | ✅ (cabangnya) |
| `kabid_pemasaran` | ✅ | ✅ (wajib) | Monitor | — | ✅ (timnya) |
| `ao_kredit` | ✅ | — | ✅ (sendiri) | ✅ | ❌ (sendiri) |
| `ao_remedial_fe` | ✅ | — | ✅ (sendiri) | ✅ | ❌ (sendiri) |
| `ao_remedial_be` | ✅ | — | ✅ (sendiri) | ✅ | ❌ (sendiri) |
| `cs` | ✅ | — | — | — | ❌ |

### Aturan Penting

- Jika **inputter bukan AO** (CS, Kabid, Kacab, Pincab), sistem **wajib menampilkan dialog delegasi** sebelum data tersimpan final. Prospek tidak bisa "mengambang" tanpa AO.
- AO hanya bisa melihat & mengelola prospek/debitur **yang didelegasikan kepadanya**.
- Atasan (Kacab/Pincab/Kabid) bisa **override** delegasi dan memindahkan prospek ke AO lain.

---

## 3. Mapping Debitur Awal Bulan

Setiap **tanggal 1**, sistem secara otomatis membagi (atau menyarankan pembagian) debitur ke AO berdasarkan **hari menunggak**:

| Kategori | Hari Menunggak | Ditangani Oleh | Modul Aktif |
| --- | --- | --- | --- |
| Lancar / Calon Baru | 0 – 30 hari | **AO Kredit** | E-Prospek, E-Pipelane, Visit AO |
| Menunggak Ringan | 31 – 180 hari | **AO Remedial FE** (Front End) | Visit AO, Call AO |
| Menunggak Berat / PH | 181+ hari atau status PH (Penghapusbukuan) | **AO Remedial BE** (Back End) | Visit AO, Call AO |

> **Catatan:** Saat akhir bulan, sistem snapshot posisi semua debitur. Saat awal bulan baru, jika kategori berubah (misal dari 28 hari → 35 hari), debitur otomatis berpindah dari AO Kredit ke AO Remedial FE. Notifikasi serah-terima dikirim ke kedua AO.

---

## 4. Modul Utama

### 4.1 Dashboard

Halaman utama yang menampilkan **ringkasan performa sesuai role**.

- **AO**: target pipeline bulan, progress visit, prospek panas (hot leads), rekap komitmen vs realisasi.
- **Kabid / Kacab / Pincab**: agregat semua AO di bawahnya, ranking AO, alert SLA terlewat, prospek yang belum didelegasikan.
- **CS**: jumlah prospek yang ia input, status delegasinya.

### 4.2 E-Prospek

Modul input data **calon debitur** untuk semua produk Bank.

#### Jenis Produk yang Didukung

| Produk | Alur Selanjutnya | Dikerjakan Oleh |
| --- | --- | --- |
| **Tabungan** | Update langsung di E-Prospek → mark sebagai "Realisasi" | AO Kredit |
| **Deposito** | Update langsung di E-Prospek → mark sebagai "Realisasi" | AO Kredit |
| **Kredit** (KMK, KI, KPR, Multiguna, dst) | Otomatis masuk ke **E-Pipelane** untuk proses SLA | AO Kredit |
| **Aset** (jaminan/agunan terkait remedial) | Diteruskan ke AO Remedial untuk follow-up | AO Remedial |

#### Field Wajib

- Identitas: nama usaha/debitur, pemilik, NIK, no. telepon
- Lokasi: alamat lengkap, kab/kec/kel (cascading dropdown), geolocation (lat/lng), foto lokasi/usaha
- Produk: jenis produk (tabungan/deposito/kredit/aset), nominal, tujuan
- Score otomatis: HOT (≥80) / WARM (60–79) / COLD (<60) berdasarkan rule engine
- Catatan & sumber prospek (walk-in, referral, kanvasing, dll)

#### Status Prospek

`open` → `pending` → `submit` → (Kredit: lanjut Pipelane) / (Tabungan & Deposito: `realisasi`) atau `reject`

#### Aturan Khusus

- **Jika inputter bukan AO** → modal delegasi muncul setelah submit form. Inputter wajib pilih AO target sesuai mapping produk.
- **Jika prospek sudah diambil/diproses AO** → tombol delete dikunci. Hanya bisa di-update.
- AO yang ditugaskan **wajib mengisi data tambahan AO** (jenis usaha, lama usaha, omzet, jaminan) sebelum bisa submit ke pipeline.

### 4.3 E-Pipelane (Credit SLA)

Modul khusus untuk produk **Kredit** — meneruskan dari E-Prospek (status `submit`) sampai keputusan akhir.

#### Tahapan Proses (Stages)

| # | Stage | SLA Default | PIC |
| --- | --- | --- | --- |
| 1 | Pengumpulan Berkas | 2 hari | AO Kredit |
| 2 | BI Checking / SLIK | 1 hari | AO Kredit |
| 3 | Survey Lapangan | 2 hari | AO Kredit |
| 4 | Analisa Kredit | 2 hari | AO Kredit |
| 5 | Komite Kredit / Approval | 2 hari | Kacab / Komite |
| 6 | Akad / Realisasi | 1 hari | AO + CS |
| 7 | **Diterima** atau **Ditolak** | — | Final state |

> Setiap perpindahan stage **wajib di-update** oleh AO. Jika stage melebihi SLA, akan muncul alert di dashboard atasan.

#### Fitur Utama

- Timeline visual per pengajuan (kanban/horizontal stepper)
- Upload dokumen per stage (KTP, KK, NPWP, slip gaji, agunan, hasil survey, dll)
- Catatan per stage + log perubahan (audit trail)
- Notifikasi otomatis saat SLA mendekati / terlewat
- Laporan SLA (per AO, per cabang, per produk)

### 4.4 Visit AO

Modul untuk mencatat **kunjungan AO ke debitur/calon debitur**.

#### Sub-fitur

1. **Jadwal Visit** — kalender harian/mingguan kunjungan
2. **Riwayat Visit** — daftar kunjungan yang sudah dilakukan + foto + GPS check-in
3. **Tambah Visit** — form input kunjungan dengan geotagging wajib
4. **Peta Lokasi** — visualisasi semua debitur di peta (clustering by status)
5. **Komitmen Awal Bulan** — AO input target jumlah visit & nominal closing/penagihan di awal bulan
6. **Rekap Bulanan** — perbandingan komitmen vs realisasi (target vs actual)

#### Daftar Debitur AO

Setiap AO memiliki **daftar debitur yang di-mapping** ke dirinya (lihat [bagian 3](#3-mapping-debitur-awal-bulan)).

- AO Kredit: hanya debitur **0–30 hari menunggak** + calon debitur dari E-Prospek
- AO Remedial FE: debitur **31–180 hari**
- AO Remedial BE: debitur **181+ hari & PH**

Setiap visit harus terikat ke debitur dalam mapping AO tersebut. Jika kunjungan ke debitur di luar mapping, perlu approval atasan.

### 4.5 Call AO

Modul untuk mencatat **panggilan telepon** ke debitur (terutama untuk AO Remedial & follow-up).

- Riwayat call (in/out, durasi, hasil call)
- Tambah call (status: kontak, tidak kontak, janji bayar, dll)
- Jadwal Follow-up
- Laporan CCL (Call Center Log)

---

## 5. Aturan Delegasi

Inilah inti yang dirumuskan user — **siapa input, didelegasi ke siapa**:

```
                    [ INPUTTER ]
                          |
              +-----------+------------+
              |                        |
         AO langsung               Bukan AO (CS, Kabid,
        (auto = dirinya)            Kacab, Pincab)
              |                        |
              |                        v
              |                 [ WAJIB DELEGASI ]
              |                        |
              v                        v
      +---------------+        +---------------+
      | Jenis Produk? |        | Jenis Produk? |
      +-------+-------+        +-------+-------+
              |                        |
   ___________|___________   __________|___________
   |       |        |    |   |       |       |    |
 Tab.   Depo.    Kredit Aset Tab.  Depo.  Kredit Aset
   |       |        |    |   |       |       |    |
   v       v        v    v   v       v       v    v
 Update  Update  E-Pipe Remed Deleg Deleg  Deleg  Deleg
 langsg  langsg  -lane  ial   ke AO ke AO  ke AO  ke AO
 (real)  (real)  proses follow Kredit Kredit Kredit Remed
                 SLA   -up                       ial
```

### Matriks Delegasi

| Produk | Delegasi Otomatis Ke | Modul Tujuan |
| --- | --- | --- |
| Tabungan | AO Kredit | E-Prospek (update langsung → realisasi) |
| Deposito | AO Kredit | E-Prospek (update langsung → realisasi) |
| Kredit | AO Kredit | E-Pipelane (proses SLA) |
| Aset | AO Remedial (FE atau BE sesuai mapping debitur) | Visit AO + Call AO |

### Rule Validasi

- Form delegasi **tidak bisa dilewati** jika inputter bukan AO.
- Jika di cabang tertentu **tidak ada AO** untuk jenis produk tersebut → fallback ke Kacab / muncul peringatan.
- Atasan dapat **re-delegasi** kapan saja (perubahan tercatat di audit log).

---

## 6. Diagram Alur Sistem

### 6.1 Alur E-Prospek → E-Pipelane (Kredit)

```mermaid
flowchart TD
    A[Inputter buat Prospek] --> B{Inputter = AO?}
    B -- Ya --> C[Auto-assign ke dirinya]
    B -- Tidak --> D[Form Delegasi Wajib]
    D --> E{Pilih AO sesuai produk}
    C --> F{Jenis Produk?}
    E --> F
    F -- Tabungan/Deposito --> G[Update langsung di E-Prospek]
    G --> H[Status: Realisasi]
    F -- Kredit --> I[Status: Submit]
    I --> J[Auto masuk E-Pipelane]
    J --> K[Stage 1: Berkas]
    K --> L[Stage 2: BI Checking]
    L --> M[Stage 3: Survey]
    M --> N[Stage 4: Analisa]
    N --> O[Stage 5: Komite]
    O --> P{Approve?}
    P -- Ya --> Q[Stage 6: Akad/Realisasi]
    P -- Tidak --> R[Status: Ditolak]
    F -- Aset --> S[Delegasi ke AO Remedial]
    S --> T[Visit AO + Call AO]
```

### 6.2 Alur Mapping Debitur Awal Bulan

```mermaid
flowchart LR
    A[Debitur Aktif] --> B{Hari Menunggak?}
    B -- 0-30 hari --> C[AO Kredit]
    B -- 31-180 hari --> D[AO Remedial FE]
    B -- 181+ atau PH --> E[AO Remedial BE]
    C --> F[Visit AO]
    D --> F
    E --> F
    F --> G[Call AO]
```

---

## 7. Notifikasi & SLA

### Channel Notifikasi (rencana)

- In-app notification (bell di navbar)
- Email (untuk delegasi & SLA terlewat)
- WhatsApp (opsional, integrasi via gateway)

### Trigger Notifikasi

| Event | Penerima |
| --- | --- |
| Prospek baru didelegasikan ke saya | AO target |
| Stage E-Pipelane dipindahkan | AO + atasan |
| SLA stage **mendekati** (H-1) | AO |
| SLA stage **terlewat** | AO + atasan |
| Komitmen visit awal bulan belum diisi (tgl 3) | AO + atasan |
| Visit hari ini belum dilakukan (akhir hari) | AO |
| Mapping debitur berubah (pindah AO) | AO lama + AO baru |

---

## 8. Model Data (ERD Ringkas)

### Entitas Utama

```
users (id, employee_id, full_name, email, role, branch_id, ...)
branches (id, name, kacab_id, ...)
prospek (id, nama, pemilik, telp, alamat, kab, kec, kel,
         lat, lng, foto, produk_jenis, nominal, status, score,
         ao_id, inputter_id, created_at, ...)
prospek_logs (id, prospek_id, action, by_user_id, note, created_at)
pipelane (id, prospek_id, current_stage, sla_due_at, ao_id, ...)
pipelane_stages (id, pipelane_id, stage_no, started_at, finished_at,
                 status, note, by_user_id)
pipelane_documents (id, pipelane_id, stage_no, file_path, uploaded_by, ...)
debitur (id, nama, no_rekening, produk, plafon, baki_debet,
         hari_menunggak, status_kolektibilitas, ao_id, branch_id, ...)
visits (id, debitur_id, ao_id, tanggal, lat, lng, foto,
        hasil, catatan, created_at)
visit_commitments (id, ao_id, bulan, target_visit, target_nominal,
                   realisasi_visit, realisasi_nominal)
calls (id, debitur_id, ao_id, tanggal, durasi, hasil, catatan, ...)
delegations (id, prospek_id, from_user_id, to_user_id, reason, created_at)
```

### Relasi Penting

- 1 `prospek` (kredit) → 1 `pipelane`
- 1 `pipelane` → banyak `pipelane_stages`
- 1 `debitur` → 1 `ao` (assignment per bulan, tersimpan di `debitur_assignments` dengan `effective_month`)
- 1 `ao` → banyak `visits`, `calls`, `visit_commitments`

---

## 9. Rencana API

API berbasis **REST** dengan struktur folder yang sudah disiapkan di `/api`.

### Konvensi

- Base URL: `/api/...`
- Format: JSON
- Auth: Bearer Token (JWT dari SSO)
- Response standar: `{ "status": 200, "message": "OK", "data": {...} }`

### Endpoint Utama (rencana)

```
POST   /api/auth/login                # delegate ke SSO
GET    /api/auth/whoami               # ambil profil user dari SSO

GET    /api/prospek                   # list (filtered by role)
POST   /api/prospek                   # create + auto/manual delegasi
GET    /api/prospek/{id}
PUT    /api/prospek/{id}
DELETE /api/prospek/{id}              # hanya jika belum diambil AO
POST   /api/prospek/{id}/delegate     # re-delegasi
POST   /api/prospek/{id}/realisasi    # untuk tabungan/deposito

GET    /api/pipelane
POST   /api/pipelane/{id}/advance     # pindah stage
POST   /api/pipelane/{id}/reject
POST   /api/pipelane/{id}/approve

GET    /api/visits
POST   /api/visits                    # check-in dengan GPS+foto
GET    /api/visits/commitments
POST   /api/visits/commitments        # input komitmen awal bulan

GET    /api/calls
POST   /api/calls

GET    /api/debitur                   # list debitur per AO
GET    /api/debitur/mapping           # mapping bulanan
POST   /api/debitur/remap             # admin only
```

---

## 10. Autentikasi (SSO)

Sistem ini **tidak punya tabel user sendiri**, melainkan terintegrasi dengan **REST API SSO internal**.

### Flow

1. User login di SSO (`localhost/rest_api_sso/...`).
2. SSO mengembalikan JWT token.
3. BKK Nexus menyimpan token di session/cookie.
4. Setiap request ke `/api/...` menyertakan `Authorization: Bearer <token>`.
5. BKK Nexus memverifikasi token via endpoint `/api/auth/whoami` dan memetakan field SSO ke role internal.

### Mapping Field SSO → Role Internal

Field dari `whoami` SSO:

```json
{
  "kode": "000",
  "employee_id": "102-119",
  "full_name": "...",
  "email": "...",
  "telp": "...",
  "branch_name": "Kantor Pusat",
  "unit_kerja": "Divisi Operasional",
  "job_position": "Staf Sistem dan Jaringan TI",
  "level": "Staf",
  "group_jabatan": "Staf"
}
```

Mapping otomatis (rule-based di BKK Nexus):

| Kombinasi `job_position` + `unit_kerja` | Role di BKK Nexus |
| --- | --- |
| `Pimpinan Cabang` / `Kepala Cabang` | `pincab` / `kacab` |
| `Kabid Pemasaran` | `kabid_pemasaran` |
| `AO Kredit` | `ao_kredit` |
| `AO Remedial` (Front End) | `ao_remedial_fe` |
| `AO Remedial` (Back End) | `ao_remedial_be` |
| `Customer Service` | `cs` |
| `Staf Sistem dan Jaringan TI` (atau Admin IT) | `admin` |

> Mapping ini disimpan di `config/role_mapping.php` agar mudah disesuaikan tanpa ubah kode.

---

## 11. Tech Stack

| Kategori | Teknologi | Catatan |
| --- | --- | --- |
| Backend | **PHP 8+ (raw, tanpa framework)** | Sederhana, mudah deploy ke aaPanel |
| Database | **MySQL 8** | Via PDO |
| Frontend | **TailwindCSS (CDN)** + Vanilla JS | Tidak ada build step |
| Routing | Custom front controller (`index.php` + `.htaccess`) | Clean URL `/dashboard`, `/e-prospek`, dst |
| Layout | **Responsive**: desktop (sidebar) + mobile (bottom-nav) | File `pages/{page}.php` & `pages/mobile-{page}.php` |
| Auth | **External SSO** (REST + JWT) | Tidak ada tabel user lokal |
| Peta | Leaflet / Google Maps (TBD) | Untuk Visit AO |

---

## 12. Struktur Folder

```
bkk-nexus/
├── .htaccess                # Rewrite untuk clean URL
├── README.md                # File ini
├── index.php                # Front controller (routing + render layout)
│
├── config/
│   ├── env.php              # BASE_URL, APP_NAME, dll
│   ├── database.php         # Koneksi PDO MySQL
│   └── role_mapping.php     # (rencana) mapping SSO → role
│
├── api/                     # REST endpoints
│   ├── index.php            # API router
│   ├── controllers/         # Logic per resource
│   ├── routes/              # Definisi route
│   └── middlewares/         # Auth (JWT verify), role guard, dll
│
├── pages/                   # Halaman desktop
│   ├── dashboard.php
│   ├── e-prospek.php
│   ├── e-pipelane.php
│   ├── visit-ao.php
│   ├── call-ao.php
│   └── mobile-*.php         # Versi mobile setiap halaman
│
├── views/                   # Komponen layout
│   ├── header.php
│   ├── footer.php
│   ├── navbar.php           # Top navbar desktop
│   ├── sidebar.php          # Sidebar desktop
│   ├── mobile-header.php    # Header mobile
│   └── bottom-nav.php       # Bottom navigation mobile
│
└── assets/
    ├── img/
    └── js/
```

---

## 13. Setup & Pengembangan Lokal

### Prasyarat

- PHP 8.0+
- MySQL 8+
- Apache dengan `mod_rewrite` aktif (atau aaPanel)
- Akses ke REST API SSO (`localhost/rest_api_sso`)

### Langkah

1. Clone repo ke folder web server (`htdocs` / `www`).
2. Buat database `bkk_nexus` di MySQL.
3. Edit `config/database.php` sesuai kredensial lokal.
4. Edit `config/env.php`, set `BASE_URL` sesuai folder deployment.
5. Pastikan `.htaccess` aktif.
6. Buka `http://localhost/bkk-nexus/`.

### Mode Dummy

`index.php` saat ini menggunakan **dummy role** untuk testing UI sebelum SSO terintegrasi:

```php
$userRole = 'ao';   // ganti: admin, ao, kacab, pincab, kabid_pemasaran, cs
$userName = 'Harry';
$userInitial = 'H';
```

---

## 14. Roadmap Pengembangan

### Fase 1 — Fondasi (UI & Skeleton) ✅ *sebagian selesai*

- [x] Layout responsive (desktop sidebar + mobile bottom-nav)
- [x] Routing front controller
- [x] Halaman Dashboard (dummy)
- [x] Halaman E-Prospek (UI + dummy data)
- [ ] Halaman E-Pipelane (UI + dummy)
- [ ] Halaman Visit AO (UI + dummy)
- [ ] Halaman Call AO (UI + dummy)

### Fase 2 — Database & API

- [ ] Skema database lengkap (migration script)
- [ ] Integrasi SSO (login, whoami, role mapping)
- [ ] Middleware auth & role guard
- [ ] API CRUD E-Prospek
- [ ] API CRUD E-Pipelane (advance, approve, reject)
- [ ] API CRUD Visit AO + komitmen
- [ ] API CRUD Call AO

### Fase 3 — Otomasi & Integrasi

- [ ] Job tanggal 1: mapping debitur otomatis
- [ ] SLA monitor (cron) → notifikasi
- [ ] Notifikasi in-app (bell)
- [ ] Notifikasi email
- [ ] Integrasi WhatsApp gateway
- [ ] Export laporan PDF/Excel

### Fase 4 — Lanjutan

- [ ] Mobile app PWA
- [ ] Integrasi peta untuk Visit AO
- [ ] Dashboard analitik atasan (drilldown per AO)
- [ ] Audit log lengkap dengan filter
- [ ] Multi-cabang & hierarchy approval

---

## Lisensi & Kontribusi

Proyek internal — untuk penggunaan terbatas di lingkungan BPR. Hubungi tim IT untuk akses dan kontribusi.

---

> **Catatan untuk pengembang:** Sebelum mengimplementasi modul, pastikan sudah membaca seksi [Aturan Delegasi](#5-aturan-delegasi) dan [Mapping Debitur](#3-mapping-debitur-awal-bulan). Dua bagian ini adalah inti bisnis yang membedakan BKK Nexus dari sistem CRM kredit biasa.
