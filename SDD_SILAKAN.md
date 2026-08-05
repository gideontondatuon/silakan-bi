# SOFTWARE DESIGN DOCUMENT (SDD)
## SILAKAN — Sistem Informasi Layanan Kantor
### Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara

---

**Nama Dokumen** : Software Design Document (SDD) — SILAKAN
**Versi Dokumen** : 1.0
**Tanggal Penerbitan** : 5 Agustus 2026
**Instansi** : Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara
**Status** : Final

---

## DAFTAR ISI

| Bab | Judul | Halaman |
|:----|:------|:--------|
| I | Pendahuluan | 3 |
| II | Arsitektur Sistem | 5 |
| III | Desain Database & Data | 8 |
| IV | Desain Komponen & Logika Bisnis | 12 |
| V | Desain Antarmuka Pengguna | 16 |
| VI | Desain Keamanan Sistem | 18 |
| VII | Desain Notifikasi & Integrasi Gateway | 20 |
| VIII | Desain Laporan & Ekspor | 22 |
| IX | Arsitektur Deployment | 24 |
| X | Glosarium & Referensi | 26 |

---

## BAB I — PENDAHULUAN

### 1.1 Tujuan Dokumen

Dokumen **Software Design Document (SDD)** ini disusun sebagai panduan teknis lengkap yang merinci seluruh aspek desain dan arsitektur dari sistem **SILAKAN (Sistem Informasi Layanan Kantor)**. Dokumen ini menjadi acuan utama bagi:

- **Tim Pengembang**: Referensi desain dan implementasi kode.
- **Administrator Sistem**: Memahami arsitektur sistem untuk keperluan pemeliharaan dan pengembangan lanjutan.
- **Auditor & Peninjau Teknis Internal**: Memverifikasi kepatuhan arsitektur terhadap standar keamanan dan kualitas.
- **Manajemen & Pemangku Kepentingan**: Memahami kapabilitas teknis, skala, dan potensi ekspansi sistem.

### 1.2 Cakupan Sistem

**SILAKAN** adalah sistem informasi berbasis web yang dikembangkan untuk mengotomatisasi dan mendigitalisasi seluruh proses pemesanan ruangan rapat di lingkungan **Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara (KPwBI Prov. Sulut)** beserta seluruh layanan pendukungnya.

Sistem mencakup delapan modul fungsional utama:

| No. | Modul | Deskripsi Fungsional |
|:----|:------|:---------------------|
| 1 | **Pemesanan Ruangan** | Pengajuan, pengecekan bentrok otomatis, validasi kapasitas, upload disposisi |
| 2 | **Approval & Rejection** | Persetujuan/penolakan oleh admin dengan catatan alasan |
| 3 | **Live Monitoring** | Pemantauan ruangan aktif dengan countdown timer real-time |
| 4 | **TV Lobby Kiosk** | Layar informasi publik lobby dengan auto-refresh 30 detik |
| 5 | **Master Data** | Kelola ruangan, layout, hari libur, API sinkronisasi libur nasional |
| 6 | **Manajemen User** | Kelola akun pegawai unit, role, dan department |
| 7 | **Laporan & Analitik** | Rekap bulanan, ekspor Excel, cetak PDF ber-KOP resmi BI |
| 8 | **Audit Log** | Rekam jejak seluruh aktivitas user/admin terpusat |

### 1.3 Ruang Lingkup Pengguna

Sistem SILAKAN mengakomodasi dua peran pengguna utama:

**Pengguna (User / Unit Kerja)**
- Seluruh pegawai unit kerja internal KPwBI Prov. Sulut.
- Dapat mengajukan, memantau, dan membatalkan pemesanan ruangan rapat.
- Menerima notifikasi status melalui WhatsApp dan Web Notification.

**Administrator**
- Pengelola Sarana & Prasarana / Rumah Tangga / Sekretariat.
- Menyetujui atau menolak pengajuan pemesanan dengan catatan.
- Mengelola seluruh master data dan memantau laporan aktivitas.

### 1.4 Prasyarat Teknis

| Komponen | Spesifikasi Minimum |
|:---------|:--------------------|
| PHP | 8.2 atau lebih tinggi |
| Composer | 2.x |
| Node.js | 18.x atau lebih tinggi |
| NPM | 9.x atau lebih tinggi |
| Database | MySQL 8.0+ atau SQLite 3.x |
| Web Server | Apache 2.4+ atau Nginx 1.20+ |
| RAM Server | Minimum 512 MB |
| Storage | Minimum 2 GB |

### 1.5 Definisi & Akronim

| Singkatan | Kepanjangan |
|:----------|:------------|
| **KPwBI Prov. Sulut** | Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara |
| **SILAKAN** | Sistem Informasi Layanan Kantor |
| **MVC** | Model-View-Controller |
| **WITA** | Waktu Indonesia Tengah (UTC+8) |
| **ERD** | Entity Relationship Diagram |
| **ORM** | Object-Relational Mapping |
| **CSRF** | Cross-Site Request Forgery |
| **PIC** | Person In Charge (Penanggung Jawab) |
| **SDD** | Software Design Document |
| **API** | Application Programming Interface |

---

## BAB II — ARSITEKTUR SISTEM

### 2.1 Gambaran Arsitektur Tingkat Tinggi

Sistem SILAKAN dibangun menggunakan framework **Laravel 12** yang mengimplementasikan pola arsitektur **MVC (Model-View-Controller)** yang diperluas dengan lapisan **Action Pattern** untuk memisahkan logika bisnis kompleks dari Controller.

Diagram alur arsitektur sistem secara keseluruhan:

```
[Pengguna / Browser]
        |
        | HTTP Request (HTTPS)
        v
[Web Server: Apache / Nginx]
        |
        v
[Laravel Routing Layer — routes/web.php]
        |
        v
[Middleware Pipeline]
   |-- auth (Session Guard)
   |-- role (RoleMiddleware)
   |-- csrf (VerifyCsrfToken)
        |
        v
[Controller Layer]
   |-- PemesananController
   |-- ApprovalController
   |-- DisplayController
   |-- Admin\DashboardController
   |-- ... (9 Controllers Admin + 6 Controllers User)
        |
        v
[Action Layer — Business Logic]
   |-- CreatePemesananAction
   |-- ApprovePemesananAction
   |-- RejectPemesananAction
   |-- CancelPemesananAction
        |
        v
[Service Layer]
   |-- AuditLogService
   |-- WhatsAppService
        |
        v
[Model / ORM Layer — Eloquent]
   |-- Pemesanan, Ruangan, LayoutRuangan
   |-- User, Department, HariLibur, AuditLog
        |
        v
[Database — MySQL / SQLite]

[Notification Layer — Async]
   |-- Web Notification (Laravel Notifications)
   |-- WhatsApp Gateway (HTTP API)
```

### 2.2 Pola Arsitektur MVC + Action Pattern

SILAKAN mengadopsi **pola MVC yang diperluas** dengan tambahan lapisan Action (Command Pattern). Pemisahan ini memberikan manfaat teknis yang signifikan:

#### 2.2.1 Model Layer
Model bertanggung jawab untuk:
- Mendefinisikan struktur tabel dan kolom (`$fillable`, `$casts`).
- Menyatakan relasi antar entitas (belongsTo, hasMany, belongsToMany).
- Menyediakan **Query Scope** yang dapat digunakan kembali (`scopeApproved`, `scopePending`, `scopeIsLive`, `scopeConflict`).
- Menyediakan **Accessor** untuk atribut kalkulasi (`getDurasiAttribute`, `getDurasiFormatAttribute`).

#### 2.2.2 View Layer
View bertanggung jawab untuk:
- Merender antarmuka HTML menggunakan **Blade Templating Engine**.
- Menggunakan **Blade Component** (`<x-sidebar.user />`, `<x-navbar />`) untuk komponen yang dapat digunakan ulang.
- Mengonsumsi data yang diteruskan controller via `compact()` / `with()`.

#### 2.2.3 Controller Layer
Controller bertanggung jawab untuk:
- Menerima HTTP Request dari Routing.
- Memanggil Form Request untuk validasi.
- Mendelegasikan eksekusi bisnis ke Action class.
- Mengembalikan HTTP Response (View / Redirect / JSON).

#### 2.2.4 Action Layer
Action class bertanggung jawab untuk:
- Mengeksekusi logika bisnis inti yang kompleks.
- Mengelola transaksi database (`DB::transaction`).
- Memanggil layanan pendukung (AuditLog, WhatsApp, Notification).

### 2.3 Technology Stack & Dependensi

#### Backend Dependencies (`composer.json`)
| Package | Versi | Fungsi |
|:--------|:------|:-------|
| `laravel/framework` | ^12.0 | Framework PHP utama |
| `barryvdh/laravel-dompdf` | ^3.1 | Generate laporan PDF ber-KOP resmi |
| `maatwebsite/excel` | ^3.1 | Ekspor data laporan ke Excel (.xlsx) |
| `phpoffice/phpword` | ^1.4 | Generate dokumen Word (.doc/.docx) |
| `laravel/sanctum` | ^4.0 | Autentikasi API Token berbasis Sanctum |
| `laravel/breeze` | ^2.4 | Scaffolding autentikasi berbasis session |

#### Frontend Dependencies (`package.json`)
| Package | Versi | Fungsi |
|:--------|:------|:-------|
| `vite` | ^6.4 | Asset bundler dan hot module replacement |
| `@vitejs/plugin-vue` | N/A | Plugin Vue.js untuk Vite |
| `bootstrap-icons` | ^1.13 | Ikon antarmuka Bank Indonesia |
| `chart.js` | Terbaru | Library grafik interaktif dashboard admin |

### 2.4 Struktur Direktori Utama

```
silakan/
├── app/
│   ├── Actions/                  # Business logic actions
│   │   ├── ApprovePemesananAction.php
│   │   ├── CancelPemesananAction.php
│   │   ├── CreatePemesananAction.php
│   │   └── RejectPemesananAction.php
│   ├── Console/
│   │   └── Commands/             # Artisan CLI commands
│   ├── Enums/                    # Enumerasi PHP 8.1+
│   │   ├── PemesananStatus.php
│   │   └── Role.php
│   ├── Exports/                  # Excel Export classes
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/            # 9 Admin Controllers
│   │   │   ├── Api/              # API JSON Controllers
│   │   │   ├── Auth/             # Auth scaffolding
│   │   │   └── *.php             # User Controllers
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php
│   │   └── Requests/             # Form Request Validation
│   ├── Models/                   # Eloquent Models
│   ├── Notifications/            # Notification classes
│   ├── Observers/                # Model observers
│   ├── Services/                 # Shared service classes
│   │   ├── AuditLogService.php
│   │   └── WhatsAppService.php
│   └── Traits/                   # Reusable traits
├── database/
│   ├── migrations/               # 26 migration files
│   └── seeders/                  # Database seeders
├── resources/
│   ├── css/                      # Vite CSS entry
│   ├── js/                       # Vite JS entry
│   └── views/                    # Blade templates
├── routes/
│   └── web.php                   # Route definitions
└── public/
    ├── assets/css/silakan.css    # Compiled CSS
    └── build/                    # Vite build output
```

---

## BAB III — DESAIN DATABASE & DATA

### 3.1 Deskripsi Skema Database

Database SILAKAN menggunakan **MySQL 8.0** (production) atau **SQLite** (development/testing) dan terdiri dari **13 tabel** utama yang saling berelasi.

### 3.2 Daftar Tabel & Fungsinya

| No. | Nama Tabel | Fungsi |
|:----|:-----------|:-------|
| 1 | `users` | Akun pegawai unit kerja dan administrator |
| 2 | `departments` | Master data departemen/unit kerja |
| 3 | `ruangan` | Master data ruangan rapat |
| 4 | `layout_ruangan` | Master data konfigurasi layout ruangan |
| 5 | `ruangan_layout` | Tabel pivot relasi many-to-many ruangan-layout |
| 6 | `pemesanan` | Record pemesanan ruangan rapat |
| 7 | `pemesanan_status_history` | Riwayat perubahan status pemesanan |
| 8 | `hari_libur` | Hari libur nasional dan cuti bersama |
| 9 | `audit_logs` | Rekam jejak aktivitas seluruh pengguna |
| 10 | `notifications` | Web notification sistem Laravel |
| 11 | `sessions` | Manajemen session autentikasi pengguna |
| 12 | `cache` | Cache data sistem Laravel |
| 13 | `personal_access_tokens` | Token API Laravel Sanctum |

### 3.3 Spesifikasi Detail Tabel

#### 3.3.1 Tabel `users`
Menyimpan seluruh akun pengguna sistem (unit kerja dan administrator).

| Kolom | Tipe | Nullable | Default | Keterangan |
|:------|:-----|:---------|:--------|:-----------|
| `id` | BIGINT UNSIGNED | N | AUTO | Primary Key |
| `name` | VARCHAR(255) | N | - | Nama lengkap pengguna |
| `username` | VARCHAR(255) | N | - | Username login (unik) |
| `email` | VARCHAR(255) | Y | NULL | Email pengguna (unik) |
| `no_wa` | VARCHAR(20) | Y | NULL | Nomor WhatsApp aktif |
| `password` | VARCHAR(255) | N | - | Password terenkripsi (bcrypt) |
| `role` | ENUM | N | `user` | Role akses: `admin` / `user` |
| `nama_unit` | VARCHAR(255) | Y | NULL | Nama unit kerja |
| `kode_unit` | VARCHAR(50) | Y | NULL | Kode inisial unit |
| `department_id` | BIGINT FK | Y | NULL | Referensi ke `departments.id` |
| `created_at` | TIMESTAMP | Y | NULL | Waktu pembuatan |
| `updated_at` | TIMESTAMP | Y | NULL | Waktu pembaruan |

**Casting & Proteksi:**
- `password` di-*hash* otomatis oleh Laravel cast `hashed`.
- `role` di-cast ke PHP Enum `App\Enums\Role`.
- Kolom `password` dan `remember_token` disembunyikan dari serialisasi JSON.

---

#### 3.3.2 Tabel `ruangan`
Menyimpan data master ruangan rapat yang tersedia.

| Kolom | Tipe | Nullable | Default | Keterangan |
|:------|:-----|:---------|:--------|:-----------|
| `id` | BIGINT UNSIGNED | N | AUTO | Primary Key |
| `nama_ruangan` | VARCHAR(255) | N | - | Nama ruangan (Balai Kerapuan, Bunaken, dll.) |
| `kapasitas` | INT | N | - | Kapasitas maksimal orang |
| `status` | VARCHAR(50) | N | `aktif` | Status ruangan: `aktif` / `nonaktif` |
| `lokasi` | VARCHAR(255) | Y | NULL | Lokasi gedung/lantai |
| `created_at` | TIMESTAMP | Y | NULL | Waktu pembuatan |
| `updated_at` | TIMESTAMP | Y | NULL | Waktu pembaruan |

**Relasi:**
- Relasi N:N ke `layout_ruangan` via pivot `ruangan_layout`.
- Relasi 1:N ke `pemesanan`.

---

#### 3.3.3 Tabel `layout_ruangan`
Menyimpan master konfigurasi layout penataan kursi ruangan rapat.

| Kolom | Tipe | Nullable | Default | Keterangan |
|:------|:-----|:---------|:--------|:-----------|
| `id` | BIGINT UNSIGNED | N | AUTO | Primary Key |
| `ruangan_id` | BIGINT FK | Y | NULL | FK opsional ke `ruangan.id` |
| `nama_layout` | VARCHAR(255) | N | - | Nama layout (U-Shape, Classroom, dll.) |
| `kapasitas_layout` | INT | Y | NULL | Kapasitas khusus layout (opsional) |
| `created_at` | TIMESTAMP | Y | NULL | Waktu pembuatan |
| `updated_at` | TIMESTAMP | Y | NULL | Waktu pembaruan |

**Data Layout Bawaan:**
- U-Shape
- Classroom
- Teater
- Interview Set
- Transit Room
- Round Table

---

#### 3.3.4 Tabel `ruangan_layout` (Pivot Many-to-Many)
Tabel penghubung relasi many-to-many antara ruangan dan layout yang tersedia.

| Kolom | Tipe | Nullable | Keterangan |
|:------|:-----|:---------|:-----------|
| `id` | BIGINT | N | Primary Key |
| `ruangan_id` | BIGINT FK | N | FK ke `ruangan.id` |
| `layout_ruangan_id` | BIGINT FK | N | FK ke `layout_ruangan.id` |
| `kapasitas_layout` | INT | Y | Kapasitas khusus layout di ruangan ini |
| `created_at` | TIMESTAMP | Y | Waktu pembuatan |
| `updated_at` | TIMESTAMP | Y | Waktu pembaruan |

---

#### 3.3.5 Tabel `pemesanan` (Tabel Inti Utama)
Menyimpan seluruh rekaman pengajuan dan pemesanan ruangan rapat.

| Kolom | Tipe | Nullable | Default | Keterangan |
|:------|:-----|:---------|:--------|:-----------|
| `id` | BIGINT | N | AUTO | Primary Key |
| `kode_pemesanan` | VARCHAR(50) | N | - | Kode unik (Format: `SIL-YYYYMMDD-XXXXX`) |
| `user_id` | BIGINT FK | N | - | FK ke `users.id` → CASCADE DELETE |
| `ruangan_id` | BIGINT FK | N | - | FK ke `ruangan.id` → CASCADE DELETE |
| `layout_ruangan_id` | BIGINT FK | Y | NULL | FK ke `layout_ruangan.id` → SET NULL |
| `tanggal_kegiatan` | DATE | N | - | Tanggal pelaksanaan rapat |
| `waktu_mulai` | TIME | N | - | Jam mulai rapat (format WITA) |
| `waktu_selesai` | TIME | N | - | Jam selesai rapat (format WITA) |
| `judul_kegiatan` | VARCHAR(150) | N | - | Nama agenda/kegiatan rapat |
| `pic_kegiatan` | VARCHAR(255) | N | - | Nama penanggung jawab |
| `jenis_pic` | ENUM | N | - | `Organik` / `Non Organik` |
| `no_wa_pic` | VARCHAR(20) | Y | NULL | WhatsApp PIC untuk notifikasi |
| `jumlah_tamu` | INT | N | - | Jumlah peserta (Min: 1) |
| `keterangan_layout` | TEXT | Y | NULL | Catatan khusus layout |
| `catatan_user` | TEXT | Y | NULL | Catatan tambahan dari pemohon |
| `file_disposisi` | VARCHAR(255) | Y | NULL | Path berkas upload disposisi |
| `status` | ENUM | N | `Pending` | Status: `Pending`, `Disetujui`, `Ditolak`, `Cancel` |
| `approved_by` | BIGINT FK | Y | NULL | FK ke `users.id` (admin yang menyetujui) |
| `approved_at` | TIMESTAMP | Y | NULL | Waktu persetujuan |
| `rejected_by` | BIGINT FK | Y | NULL | FK ke `users.id` (admin yang menolak) |
| `rejected_at` | TIMESTAMP | Y | NULL | Waktu penolakan |
| `alasan_penolakan` | TEXT | Y | NULL | Alasan tertulis penolakan admin |
| `catatan_admin` | TEXT | Y | NULL | Catatan tambahan admin saat approval |
| `cancelled_by` | BIGINT FK | Y | NULL | FK ke `users.id` yang membatalkan |
| `cancelled_at` | TIMESTAMP | Y | NULL | Waktu pembatalan |
| `alasan_pembatalan` | TEXT | Y | NULL | Alasan pembatalan |
| `created_at` | TIMESTAMP | Y | NULL | Waktu pengajuan dibuat |
| `updated_at` | TIMESTAMP | Y | NULL | Waktu pembaruan terakhir |

**Database Index yang Diterapkan:**
```sql
-- Composite index untuk optimasi query pengecekan bentrok jadwal
INDEX idx_pemesanan_conflict (ruangan_id, tanggal_kegiatan, status);
```
Index ini memastikan pengecekan bentrok jadwal berjalan dalam waktu konstan O(log n) meskipun tabel `pemesanan` berisi ratusan ribu record.

**Model Casting:**
```php
protected $casts = [
    'tanggal_kegiatan' => 'date',     // Carbon Date instance
    'jumlah_tamu'      => 'integer',   // Casting ke integer PHP
    'status'           => PemesananStatus::class, // PHP 8.1 Enum
    'approved_at'      => 'datetime',  // Carbon DateTime instance
];
```

---

#### 3.3.6 Tabel `pemesanan_status_history`
Menyimpan riwayat perubahan status pemesanan untuk keperluan audit trail yang lebih granular.

| Kolom | Tipe | Nullable | Keterangan |
|:------|:-----|:---------|:-----------|
| `id` | BIGINT | N | Primary Key |
| `pemesanan_id` | BIGINT FK | N | FK ke `pemesanan.id` |
| `status_lama` | VARCHAR(50) | N | Status sebelum perubahan |
| `status_baru` | VARCHAR(50) | N | Status setelah perubahan |
| `changed_by` | BIGINT FK | N | FK ke `users.id` yang mengubah |
| `changed_at` | TIMESTAMP | N | Waktu perubahan terjadi |
| `keterangan` | TEXT | Y | Keterangan tambahan |

---

#### 3.3.7 Tabel `hari_libur`
Menyimpan data hari libur nasional, cuti bersama, dan hari libur internal BI.

| Kolom | Tipe | Nullable | Keterangan |
|:------|:-----|:---------|:-----------|
| `id` | BIGINT | N | Primary Key |
| `tanggal` | DATE | N | Tanggal hari libur |
| `nama` | VARCHAR(255) | N | Nama hari libur |
| `kategori` | VARCHAR(100) | Y | Jenis: Nasional / Internal BI |
| `created_at` | TIMESTAMP | Y | Waktu pembuatan |
| `updated_at` | TIMESTAMP | Y | Waktu pembaruan |

---

#### 3.3.8 Tabel `audit_logs`
Menyimpan rekam jejak seluruh aktivitas yang terjadi di sistem untuk keperluan audit komprehensif.

| Kolom | Tipe | Nullable | Keterangan |
|:------|:-----|:---------|:-----------|
| `id` | BIGINT | N | Primary Key |
| `user_id` | BIGINT FK | Y | FK ke `users.id` (NULL = sistem/anonim) |
| `aksi` | VARCHAR(255) | N | Nama aksi (contoh: `Membuat Pemesanan`) |
| `modul` | VARCHAR(255) | N | Nama modul (contoh: `Pemesanan`) |
| `keterangan` | TEXT | N | Deskripsi lengkap aktivitas |
| `created_at` | TIMESTAMP | Y | Waktu log dicatat |
| `updated_at` | TIMESTAMP | Y | Waktu pembaruan |

---

### 3.4 Entity Relationship Diagram (ERD)

Berikut deskripsi lengkap relasi antar entitas database SILAKAN:

```
[users] 1 ──────── N [pemesanan]
    │                   └── ruangan_id ──── [ruangan] 1 ── N:N ── [layout_ruangan]
    │                                                               └── via [ruangan_layout]
    │
    └── 1 ──── N [audit_logs]
    └── 1 ──── N [pemesanan_status_history] (sebagai changed_by)
    └── N ──── 1 [departments]
    └── 1 ──── N [notifications]

[pemesanan]
    ├── user_id           ──── FK → users.id (CASCADE DELETE)
    ├── ruangan_id        ──── FK → ruangan.id (CASCADE DELETE)
    ├── layout_ruangan_id ──── FK → layout_ruangan.id (SET NULL)
    ├── approved_by       ──── FK → users.id (SET NULL)
    ├── rejected_by       ──── FK → users.id (SET NULL)
    └── cancelled_by      ──── FK → users.id (SET NULL)
```

### 3.5 PHP Enum Specification

#### 3.5.1 Enum `Role` (`app/Enums/Role.php`)
```php
enum Role: string
{
    case ADMIN = 'admin';   // Administrator sistem
    case USER  = 'user';    // Pegawai unit kerja
}
```
Digunakan dalam `User::$casts` untuk melakukan casting otomatis dari string `'admin'`/`'user'` ke instance Enum PHP 8.1.

#### 3.5.2 Enum `PemesananStatus` (`app/Enums/PemesananStatus.php`)
```php
enum PemesananStatus: string
{
    case PENDING   = 'Pending';    // Menunggu tinjauan admin
    case DISETUJUI = 'Disetujui'; // Disetujui, ruangan dikonfirmasi
    case DITOLAK   = 'Ditolak';   // Ditolak dengan alasan
    case CANCEL    = 'Cancel';    // Dibatalkan pengguna
}
```
Enum ini juga menyediakan method `label()` dan `color()` untuk keperluan antarmuka tampilan badge status.

---

## BAB IV — DESAIN KOMPONEN & LOGIKA BISNIS

### 4.1 Action Pattern Layer

Lapisan Action dirancang untuk memisahkan logika bisnis yang kompleks dari Controller, mengikuti prinsip **Single Responsibility Principle (SRP)**. Setiap Action class bertanggung jawab atas satu operasi bisnis spesifik.

#### 4.1.1 `CreatePemesananAction` — Alur Pembuatan Pemesanan Baru

`CreatePemesananAction::execute(array $data, User $user): Pemesanan`

**Urutan Eksekusi:**

```
1. DB::transaction() DIMULAI
   │
   ├─► Ruangan::lockForUpdate()->findOrFail($ruangan_id)
   │   [Pessimistic row lock mencegah race condition]
   │
   ├─► if ($jumlah_tamu > $ruangan->kapasitas)
   │   [Validasi kapasitas maksimal]
   │   └─► throw Exception('Jumlah tamu melebihi kapasitas...')
   │
   ├─► Pemesanan::where(ruangan_id, tanggal_kegiatan, DISETUJUI)
   │   ->where(waktu_mulai < waktu_selesai_baru)
   │   ->where(waktu_selesai > waktu_mulai_baru)
   │   ->lockForUpdate()
   │   ->exists()
   │   [Deteksi bentrok jadwal dengan algoritma interval overlap]
   │   └─► if $bentrok: throw Exception('Ruangan sudah digunakan...')
   │
   ├─► Upload file disposisi ke storage 'public/disposisi/'
   │
   ├─► Pemesanan::create([...])
   │   [Simpan record pemesanan baru dengan status PENDING]
   │   kode_pemesanan: SIL-YYYYMMDD-[5 karakter random unik]
   │
2. DB::transaction() SELESAI (COMMIT)
   │
3. AuditLogService::create('Membuat Pemesanan', 'Pemesanan', ...)
   │
4. [try] Notifikasi Admin
   ├─► User::where(role, admin)->each()->notify(PemesananNotification)
   └─► WhatsAppService->notifyAdminNewBooking($pemesanan)
   └─► WhatsAppService->notifyUserBookingSubmitted($pemesanan)
   [catch Exception → report() tanpa membatalkan pemesanan]
```

**Algoritma Deteksi Bentrok Jadwal:**

Sistem menggunakan **interval overlap detection** untuk mendeteksi apakah dua rentang waktu bertabrakan. Dua interval [A_mulai, A_selesai] dan [B_mulai, B_selesai] dinyatakan BENTROK jika:

```
A_mulai < B_selesai  AND  A_selesai > B_mulai
```

Implementasi dalam kode:
```php
->where('waktu_mulai', '<', $data['waktu_selesai'])
->where('waktu_selesai', '>', $data['waktu_mulai'])
```

**Algoritma Pembangkitan Kode Pemesanan:**
```php
do {
    $kode = 'SIL-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
} while (Pemesanan::where('kode_pemesanan', $kode)->exists());
```
Loop do-while memastikan kode pemesanan selalu unik, mencegah duplikasi pada kasus concurrent submissions.

---

#### 4.1.2 `ApprovePemesananAction` — Alur Persetujuan Pemesanan

`ApprovePemesananAction::execute(Pemesanan $pemesanan, User $admin, ?string $catatanAdmin): Pemesanan`

**Urutan Eksekusi:**

```
1. DB::transaction() DIMULAI
   │
   ├─► Pemesanan::lockForUpdate()->findOrFail($pemesanan->id)
   │   [Re-acquire row lock untuk konsistensi data]
   │
   ├─► if ($pemesanan->status !== PENDING)
   │   └─► throw Exception('Pemesanan sudah diproses...')
   │
   ├─► Cek Bentrok Ulang Saat Approval
   │   [Cek kembali apakah ada pemesanan lain yang sudah disetujui
   │    pada slot waktu yang sama sejak pengajuan dibuat]
   │   └─► if $bentrok: throw Exception('Jadwal sudah digunakan...')
   │
   ├─► $pemesanan->update([
   │       status      => DISETUJUI,
   │       approved_by => $admin->id,
   │       approved_at => now(),
   │       catatan_admin => $catatanAdmin
   │   ])
   │
   ├─► PemesananStatusHistory::create([...])
   │   [Catat perubahan status ke history table]
   │
   ├─► AuditLogService::create('Menyetujui Pemesanan', ...)
   │
2. DB::transaction() SELESAI (COMMIT)
   │
3. [try] Notifikasi User
   ├─► $pemesanan->user->notify(StatusPemesananNotification)
   └─► WhatsAppService->notifyUserBookingApproved($pemesanan)
```

Pengecekan bentrok **dua kali** (saat create dan saat approve) memberikan **jaminan konsistensi ganda**, melindungi dari skenario di mana dua admin secara bersamaan menyetujui dua pemesanan yang bertabrakan.

---

#### 4.1.3 `RejectPemesananAction` — Alur Penolakan Pemesanan

`RejectPemesananAction::execute(Pemesanan $pemesanan, User $admin, string $alasan): Pemesanan`

Proses penolakan berjalan dalam `DB::transaction()` yang mencakup:
1. Validasi status masih `PENDING`.
2. Update status ke `DITOLAK` dengan catatan `alasan_penolakan`.
3. Mencatat ke `PemesananStatusHistory`.
4. Mencatat ke `AuditLog`.
5. Mengirim Web Notification dan WhatsApp ke pengguna pemohon.

---

#### 4.1.4 `CancelPemesananAction` — Alur Pembatalan oleh Pengguna

`CancelPemesananAction::execute(Pemesanan $pemesanan, User $user): Pemesanan`

Proses pembatalan memvalidasi bahwa:
- Pengguna yang membatalkan adalah pemilik pengajuan tersebut.
- Status pemesanan masih dalam kondisi `PENDING` atau `DISETUJUI`.
- Status diubah ke `CANCEL` dan dicatat di `PemesananStatusHistory`.

---

### 4.2 Query Scope yang Tersedia di Model `Pemesanan`

| Scope | Deskripsi | Penggunaan |
|:------|:----------|:-----------|
| `scopeApproved($q)` | Filter status = Disetujui | `Pemesanan::approved()->get()` |
| `scopePending($q)` | Filter status = Pending | `Pemesanan::pending()->get()` |
| `scopeRejected($q)` | Filter status = Ditolak | `Pemesanan::rejected()->get()` |
| `scopeToday($q)` | Filter tanggal kegiatan = hari ini | `Pemesanan::today()->get()` |
| `scopeIsLive($q)` | Filter rapat sedang berlangsung saat ini | `Pemesanan::isLive()->get()` |
| `scopeConflict($q, $data)` | Deteksi bentrok jadwal | Digunakan di Action |

**Implementasi `scopeIsLive` (Live Monitoring):**
```php
public function scopeIsLive($query)
{
    return $query
        ->where('status', PemesananStatus::DISETUJUI)
        ->whereDate('tanggal_kegiatan', today())
        ->whereTime('waktu_mulai', '<=', now()->format('H:i:s'))
        ->whereTime('waktu_selesai', '>=', now()->format('H:i:s'));
}
```
Scope ini secara efisien mengidentifikasi rapat yang sedang berlangsung dalam waktu saat ini (WITA) tanpa memerlukan cron job atau scheduler background.

---

### 4.3 Form Request Validation Layer

#### 4.3.1 `StorePemesananRequest`

**Aturan Validasi yang Diterapkan:**

| Field | Aturan Validasi |
|:------|:----------------|
| `ruangan_id` | `required`, `exists:ruangan,id` |
| `layout_ruangan_id` | `nullable`, `exists:layout_ruangan,id` |
| `tanggal_kegiatan` | `required`, `date`, `after_or_equal:today`, bukan hari libur |
| `waktu_mulai` | `required`, `date_format:H:i` |
| `waktu_selesai` | `required`, `date_format:H:i`, `after:waktu_mulai` |
| `judul_kegiatan` | `required`, `string`, `max:150` |
| `pic_kegiatan` | `required`, `string`, `max:255` |
| `jenis_pic` | `required`, `in:Organik,Non Organik` |
| `no_wa_pic` | `nullable`, `string`, `max:20` |
| `jumlah_tamu` | `required`, `integer`, `min:1` |
| `catatan_user` | `nullable`, `string`, `max:500` |
| `file_disposisi` | `nullable`, `file`, `mimes:pdf,jpg,jpeg,png`, `max:5120` |

**Metode `prepareForValidation()`:**
```php
protected function prepareForValidation(): void
{
    if (empty($this->layout_ruangan_id)) {
        $this->merge(['layout_ruangan_id' => null]);
    }
}
```
Metode ini penting: string kosong `""` dari dropdown kosong dikonversi menjadi `null` sebelum validasi berjalan, mencegah kegagalan validasi foreign key constraint pada ruangan yang tidak memiliki layout.

---

### 4.4 Controller Layer — Daftar & Tanggung Jawab

| Controller | Route Prefix | Tanggung Jawab |
|:-----------|:------------|:---------------|
| `Admin\DashboardController` | `/admin/dashboard` | Render dashboard analytics, chart data, live monitoring |
| `Admin\ApprovalController` | `/admin/approval` | Halaman waiting list, detail review, trigger approve/reject |
| `Admin\RuanganController` | `/admin/ruangan` | CRUD ruangan + sinkronisasi relasi layout |
| `Admin\LayoutRuanganController` | `/admin/layout-ruangan` | CRUD master layout ruangan |
| `Admin\UserController` | `/admin/user` | CRUD akun pengguna, validasi self-delete guard |
| `Admin\HariLiburController` | `/admin/hari-libur` | CRUD hari libur + trigger sync API libur nasional |
| `Admin\LaporanController` | `/admin/laporan` | Filter laporan, ekspor Excel, cetak PDF |
| `Admin\AuditLogController` | `/admin/audit-log` | Tampilkan halaman rekam jejak aktivitas |
| `PemesananController` | `/pemesanan` | Tampilkan form, create, detail, cancel pemesanan user |
| `KalenderController` | `/kalender` | Tampilan kalender interaktif kegiatan rapat |
| `DisplayController` | `/display` | Render halaman TV Lobby + API JSON polling endpoint |
| `NotificationController` | `/notification` | Daftar notifikasi, hapus satuan, hapus semua |
| `ProfileController` | `/profile` | Tampilkan dan update informasi profil pengguna |
| `DashboardController` | `/dashboard` | Dashboard utama pengguna unit kerja |
| `KegiatanBerlangsungController` | `/kegiatan-berlangsung` | Halaman live monitoring kegiatan user |

---

## BAB V — DESAIN ANTARMUKA PENGGUNA

### 5.1 Sistem Desain Tokens & Branding Bank Indonesia

Seluruh elemen visual SILAKAN mengikuti standar identitas visual Bank Indonesia yang diimplementasikan sebagai **CSS Custom Properties (Design Tokens)** pada berkas `public/assets/css/silakan.css`.

**Palet Warna Utama:**
| Token | Nilai HEX | Kegunaan |
|:------|:----------|:---------|
| `--bi-deep-blue` | `#003B73` | Warna primer utama (header, judul bab, border resmi) |
| `--bi-blue` | `#005BAA` | Warna sekunder (link aktif, tombol utama, badge) |
| `--bi-light-blue` | `#0EA5E9` | Aksen (highlight interaktif, hover state) |
| `--bi-dark` | `#0F172A` | Teks utama (body text, label form) |
| `--bi-gray` | `#475569` | Teks sekunder (subtitle, metadata) |
| `--status-pending` | `#D97706` | Status badge Pending |
| `--status-approved` | `#10B981` | Status badge Disetujui |
| `--status-rejected` | `#EF4444` | Status badge Ditolak |
| `--status-cancelled` | `#64748B` | Status badge Dibatalkan |

### 5.2 Komponen UI Utama

#### 5.2.1 Sidebar Navigasi
Sistem menggunakan Blade Component `<x-sidebar.admin />` dan `<x-sidebar.user />` yang dirender secara berbeda berdasarkan role pengguna yang terautentikasi.

- **Sidebar Admin**: Menampilkan menu Waiting List (dengan badge angka real-time), Kegiatan Berlangsung, Master Data (Ruangan, Layout, Hari Libur, User), Laporan, Audit Log, Notifikasi.
- **Sidebar User**: Menampilkan menu Dashboard, Buat Pemesanan, Riwayat Pemesanan, Kalender, Notifikasi, Kegiatan Berlangsung.

#### 5.2.2 Badge Status Pemesanan
Badge status menggunakan warna yang terdefinisi dari PemesananStatus Enum:

```html
<span class="status-badge status-{{ $pemesanan->status->color() }}">
    {{ $pemesanan->status->label() }}
</span>
```

#### 5.2.3 Dropdown Layout Dinamis (AJAX Filtering)
Saat pengguna memilih ruangan pada form pemesanan, JavaScript secara otomatis melakukan request ke endpoint API:

```javascript
ruanganSelect.addEventListener('change', async function() {
    const ruanganId = this.value;
    const response  = await fetch(`/api/ruangan/${ruanganId}/layouts`);
    const data      = await response.json();

    layoutSelect.innerHTML = '';

    if (data.layouts.length === 0) {
        layoutSelect.innerHTML =
            '<option value="">-- Tidak ada layout khusus untuk ruangan ini --</option>';
    } else {
        data.layouts.forEach(layout => {
            layoutSelect.innerHTML +=
                `<option value="${layout.id}">${layout.nama_layout}</option>`;
        });
    }
});
```

Endpoint `/api/ruangan/{id}/layouts` dikelola oleh **`LayoutController`** yang mengembalikan JSON hanya berisi layout yang terkoneksi ke ruangan tersebut, tanpa fallback ke semua layout.

#### 5.2.4 Live Countdown Timer (Kegiatan Berlangsung)
Halaman Kegiatan Berlangsung menampilkan countdown timer real-time yang menghitung sisa waktu rapat secara mundur menggunakan JavaScript:

```javascript
function updateCountdown(endTime, element) {
    const now  = new Date().getTime();
    const end  = new Date(endTime).getTime();
    const diff = end - now;

    if (diff <= 0) {
        element.textContent = 'Selesai';
        return;
    }

    const hours   = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

    element.textContent = `${hours}j ${minutes}m ${seconds}d`;
}
setInterval(() => updateCountdown(endTime, element), 1000);
```

---

### 5.3 Antarmuka TV Lobby Kiosk Mode (`/display`)

#### 5.3.1 Deskripsi Tampilan
Halaman `/display` dirancang khusus untuk ditampilkan pada layar monitor TV besar di lobby kantor. Tampilan menggunakan **Dark Mode Kiosk** ber-KOP Bank Indonesia dan menampilkan:

1. **Informasi Waktu Real-Time**: Jam digital WITA yang berjalan secara real-time.
2. **Kegiatan Sedang Berlangsung**: Kartu berwarna hijau untuk setiap ruangan yang aktif digunakan.
3. **Agenda Hari Ini**: Seluruh jadwal rapat yang disetujui untuk hari ini.
4. **Status Ketersediaan Ruangan**: Indikator visual untuk setiap ruangan (tersedia / dipakai).

#### 5.3.2 Mekanisme Auto-Refresh (Polling AJAX)
Halaman TV Lobby tidak pernah di-reload penuh. Sebagai gantinya, JavaScript menjalankan polling AJAX setiap 30 detik ke endpoint `/api/display-data`:

```javascript
function fetchDisplayData() {
    fetch('/api/display-data')
        .then(response => response.json())
        .then(data => {
            updateLiveSection(data.live);
            updateTodaySection(data.today);
        });
}
setInterval(fetchDisplayData, 30000);
```

Endpoint `/api/display-data` mengembalikan JSON:
```json
{
    "status": "success",
    "timestamp": "Selasa, 05 Agustus 2026 09:30:00",
    "live_count": 2,
    "live": [...],
    "today": [...]
}
```

---

### 5.4 Desain Responsif Mobile

SILAKAN mengimplementasikan desain responsif untuk memastikan antarmuka bekerja optimal di perangkat mobile (smartphone) maupun layar desktop menggunakan CSS Media Queries:

```css
@media (max-width: 768px) {
    /* Mobile: input padding aman untuk ikon kiri */
    .input-group input {
        padding-top: 12px !important;
        padding-bottom: 12px !important;
        padding-right: 14px !important;
        padding-left: 14px;
    }

    /* Input dengan ikon di sebelah kiri mendapat padding-left lebih lebar */
    div[style*="position:relative"] > input {
        padding-left: 42px !important;
    }

    /* Profile banner responsif */
    .profile-banner {
        flex-direction: column;
        text-align: center;
    }
}
```

---

### 5.5 Peta Route & Endpoint URL Sistem

**Route Publik (Tanpa Autentikasi):**
| Method | URL | Fungsi |
|:-------|:----|:-------|
| GET | `/display` | Halaman TV Lobby Kiosk |
| GET | `/kiosk` | Alias Halaman Kiosk |
| GET | `/api/display-data` | API JSON auto-refresh TV |
| GET | `/download-manual-book` | Download Manual Book .doc |
| GET | `/download-sdd` | Download SDD .doc |

**Route User Terautentikasi (Middleware: `auth`, `role:user`):**
| Method | URL | Fungsi |
|:-------|:----|:-------|
| GET | `/dashboard` | Dashboard utama pengguna |
| GET | `/pemesanan` | Daftar riwayat pemesanan |
| GET | `/pemesanan/create` | Form buat pemesanan baru |
| POST | `/pemesanan` | Simpan pemesanan baru |
| GET | `/pemesanan/{id}` | Detail pemesanan |
| DELETE | `/pemesanan/{id}` | Batalkan pemesanan |
| GET | `/kalender` | Kalender jadwal ruangan |
| GET | `/notification` | Daftar notifikasi |
| DELETE | `/notification/{id}` | Hapus notifikasi satuan |
| DELETE | `/notifications/destroy-all` | Hapus semua notifikasi |

**Route Admin Terautentikasi (Middleware: `auth`, `role:admin`):**
| Method | URL | Fungsi |
|:-------|:----|:-------|
| GET | `/admin/dashboard` | Dashboard analytics admin |
| GET | `/admin/waiting-list` | Waiting list approval |
| GET | `/admin/approval/{id}` | Halaman review approval |
| POST | `/admin/approval/{id}/approve` | Setujui pemesanan |
| POST | `/admin/approval/{id}/reject` | Tolak pemesanan |
| GET/POST/PUT/DELETE | `/admin/ruangan/*` | CRUD master ruangan |
| GET/POST/PUT/DELETE | `/admin/layout-ruangan/*` | CRUD master layout |
| GET/POST/PUT/DELETE | `/admin/user/*` | CRUD manajemen user |
| GET/POST/DELETE | `/admin/hari-libur/*` | Kelola hari libur |
| POST | `/admin/hari-libur/sync-api` | Sync API libur nasional |
| GET | `/admin/laporan` | Filter & tampilkan laporan |
| GET | `/admin/laporan/excel` | Download laporan Excel |
| GET | `/admin/laporan/cetak` | Cetak laporan PDF |
| GET | `/admin/audit-log` | Halaman audit log |

**Route API Internal:**
| Method | URL | Fungsi |
|:-------|:----|:-------|
| GET | `/api/ruangan/{id}/layouts` | JSON layout per ruangan (AJAX) |

---

## BAB VI — DESAIN KEAMANAN SISTEM

### 6.1 Autentikasi Berbasis Session (Laravel Breeze)

Sistem SILAKAN menggunakan **Laravel Breeze** sebagai scaffolding autentikasi. Autentikasi dikelola menggunakan:
- **Session-based Authentication**: Sesi pengguna tersimpan di server-side dalam tabel `sessions` (bukan di cookie client).
- **Bcrypt Password Hashing**: Seluruh password dienkripsi menggunakan algoritma bcrypt dengan cost factor 12 (default Laravel).
- **CSRF Protection**: Token CSRF `@csrf` wajib disertakan di setiap form POST/PUT/DELETE.

### 6.2 Otorisasi Berbasis Role (RoleMiddleware)

Sistem mengimplementasikan **Custom Middleware** `RoleMiddleware` untuk kontrol akses berbasis role:

```php
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$allowedRoles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        $currentRole = $user->role instanceof Role
            ? $user->role->value
            : (string) $user->role;

        abort_unless(
            in_array($currentRole, $allowedRoles, true),
            403
        );

        return $next($request);
    }
}
```

Middleware ini terdaftar sebagai alias `role` dan digunakan pada seluruh route grup admin:
```php
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function() {
    // Seluruh route admin
});
```

### 6.3 Proteksi SQL Injection

Seluruh interaksi database menggunakan **Eloquent ORM** dan **Laravel Query Builder** yang secara otomatis menggunakan **PDO Prepared Statements**. Tidak ada query raw SQL yang mengandung input pengguna langsung tanpa binding.

### 6.4 Proteksi Upload File

File upload berkas disposisi diproteksi dengan pembatasan ketat:

| Aspek | Implementasi |
|:------|:-------------|
| **Ekstensi yang Diizinkan** | `pdf`, `jpg`, `jpeg`, `png` |
| **Ukuran Maksimal** | 5MB (5120 KB) |
| **Lokasi Penyimpanan** | `storage/app/public/disposisi/` via symlink |
| **Validasi via Request** | `mimes:pdf,jpg,jpeg,png`, `max:5120` |

### 6.5 Self-Delete Admin Guard

Sistem mengimplementasikan proteksi keamanan unik yang mencegah administrator yang sedang login menghapus akun mereka sendiri, sehingga menghindari kehilangan akses sistem secara tidak sengaja:

```php
// AdminUserController::destroy()
public function destroy(User $user): RedirectResponse
{
    if ($user->id === auth()->id()) {
        return back()->with(
            'error',
            'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.'
        );
    }

    $user->delete();
    // ...
}
```

### 6.6 Nullsafe Operator & Defensive Coding

Seluruh view Blade menggunakan **PHP 8.0 Nullsafe Operator (`?->`)** untuk mengakses relasi yang berpotensi null, mencegah fatal error pada data yang tidak lengkap:

```php
// Contoh penggunaan Nullsafe Operator di view
{{ $pemesanan->ruangan?->nama_ruangan ?? 'Ruangan tidak tersedia' }}
{{ $pemesanan->layout?->nama_layout ?? '-' }}
{{ $pemesanan->user?->nama_unit ?? $pemesanan->user?->name ?? 'Unit Internal' }}
```

---

## BAB VII — DESAIN NOTIFIKASI & INTEGRASI GATEWAY

### 7.1 Arsitektur Notifikasi Dua Jalur

SILAKAN mengimplementasikan sistem notifikasi dua jalur yang saling melengkapi:

```
Sumber Event (Action)
        │
        ├── Jalur 1: Web Notification
        │   └── $user->notify(new PemesananNotification(...))
        │       └── Tersimpan di tabel `notifications`
        │           └── Ditampilkan di badge lonceng + halaman notifikasi
        │
        └── Jalur 2: WhatsApp Gateway
            └── WhatsAppService->sendMessage($target, $message)
                └── HTTP POST ke WhatsApp API Gateway
```

### 7.2 WhatsAppService

`WhatsAppService` bertindak sebagai abstraksi layer antara logika bisnis dan WhatsApp API Gateway eksternal.

**Konfigurasi** (`.env`):
```ini
WHATSAPP_API_URL=https://your-wa-gateway.com/api/send
WHATSAPP_API_TOKEN=your_api_token_here
WHATSAPP_ADMIN_NUMBER=628xxxxxxxxxxxx
WHATSAPP_ENABLED=true
```

**Format Nomor WhatsApp:**
```php
public function formatNumber(?string $number): ?string
{
    $number = preg_replace('/[^0-9]/', '', $number);

    if (str_starts_with($number, '0')) {
        $number = '62' . substr($number, 1);     // 08xx -> 628xx
    } elseif (str_starts_with($number, '8')) {
        $number = '62' . $number;                 // 8xx -> 628xx
    }

    return $number;
}
```

**Simulation / Log Mode:**
Apabila token API kosong atau `WHATSAPP_ENABLED=false`, layanan berfungsi dalam mode simulasi: pesan yang seharusnya dikirim dicatat ke Laravel Log file tanpa membuat request ke API eksternal. Fitur ini sangat berguna saat pengembangan lokal.

**Daftar Metode Notifikasi WhatsApp:**
| Metode | Trigger | Penerima |
|:-------|:--------|:---------|
| `notifyAdminNewBooking()` | Pengajuan baru dibuat | Semua admin (via config & no_wa) |
| `notifyUserBookingSubmitted()` | Pengajuan berhasil terkirim | PIC / User pemohon |
| `notifyUserBookingApproved()` | Pemesanan disetujui admin | PIC / User pemohon |
| `notifyUserBookingRejected()` | Pemesanan ditolak admin | PIC / User pemohon |

**Template Pesan WhatsApp:**
Setiap template pesan menggunakan format WhatsApp Markdown (bold dengan `*teks*`, header informatif, dan link langsung ke halaman detail web):

```
✅ *[SILAKAN BI] PEMESANAN RUANGAN DISETUJUI*

Halo Budi Santoso, pengajuan pemesanan Anda telah *DISETUJUI*:

📌 *Kode:* SIL-20260805-ABCDE
🏢 *Ruangan:* Balai Kerapuan
📐 *Layout:* U-Shape
📅 *Tanggal:* Selasa, 5 Agustus 2026
⏰ *Waktu:* 09:00 - 11:30 WITA
📝 *Kegiatan:* Rapat Koordinasi Unit Humas

Detail: http://localhost:8000/pemesanan/123
```

### 7.3 Web Notification System

Web Notification menggunakan infrastruktur bawaan Laravel `Illuminate\Notifications\Notifiable` yang menyimpan notifikasi ke tabel `notifications` (format JSON) di database.

**Kelas Notifikasi yang Tersedia:**
- `PemesananNotification`: Untuk admin, saat ada pengajuan pemesanan baru.
- `StatusPemesananNotification`: Untuk user, saat status pemesanan berubah (approve/reject).

**Pengelolaan Notifikasi:**
- Pengguna dapat menghapus notifikasi satu per satu melalui tombol ikon trash.
- Pengguna dapat menghapus semua notifikasi sekaligus melalui tombol "Hapus Semua".
- Badge jumlah notifikasi belum dibaca ditampilkan secara real-time di ikon lonceng navbar.

---

## BAB VIII — DESAIN LAPORAN & EKSPOR

### 8.1 Modul Laporan Rekapitulasi

`Admin\LaporanController` menyediakan laporan rekapitulasi pemesanan dengan opsi filter yang fleksibel:

**Parameter Filter yang Tersedia:**
| Parameter | Tipe | Keterangan |
|:----------|:-----|:-----------|
| `tanggal_dari` | DATE | Rentang awal laporan |
| `tanggal_sampai` | DATE | Rentang akhir laporan |
| `ruangan_id` | INT | Filter berdasarkan ruangan spesifik |
| `user_id` | INT | Filter berdasarkan unit kerja |
| `status` | ENUM | Filter berdasarkan status pemesanan |

### 8.2 Ekspor Excel (XLSX)

Menggunakan library `maatwebsite/excel` dengan kelas `LaporanExport.php` di direktori `app/Exports/`. Export menghasilkan file `.xlsx` yang mencakup seluruh kolom pemesanan sesuai filter yang diterapkan.

### 8.3 Cetak PDF Ber-KOP Resmi Bank Indonesia

Menggunakan library `barryvdh/laravel-dompdf` untuk menghasilkan laporan PDF resmi yang mencakup:
- **KOP Resmi**: Logo dan nama lengkap Bank Indonesia / KPwBI Prov. Sulut.
- **Judul Dokumen**: Rekapitulasi Pemesanan Ruangan Rapat.
- **Rentang Periode**: Tanggal awal s/d tanggal akhir filter.
- **Tabel Rekap**: Kolom Kode, Ruangan, Tanggal, Waktu, Kegiatan, Unit, Layout, Status.
- **Tanda Tangan**: Area tanda tangan pejabat yang berwenang.

### 8.4 Sinkronisasi API Hari Libur Nasional

`HariLiburController` menyediakan fitur sinkronisasi otomatis dengan API hari libur nasional pemerintah:

```php
public function syncApi(): RedirectResponse
{
    $response = Http::get('https://api.harilibur.net/api');

    if ($response->successful()) {
        $holidays = $response->json();
        foreach ($holidays as $holiday) {
            HariLibur::updateOrCreate(
                ['tanggal' => $holiday['tanggal']],
                ['nama' => $holiday['nama'], 'kategori' => 'Nasional']
            );
        }
        return redirect()->back()->with('success', 'Sinkronisasi berhasil.');
    }
}
```

---

## BAB IX — ARSITEKTUR DEPLOYMENT

### 9.1 Lingkungan Pengembangan Lokal

**Menjalankan Server Development:**
```bash
# Menjalankan semua services sekaligus (Laravel, Queue, Logs, Vite)
composer run dev
```

Perintah ini menjalankan secara bersamaan:
- `php artisan serve` — Laravel development server
- `php artisan queue:listen` — Queue worker untuk background jobs
- `php artisan pail` — Log viewer real-time
- `npm run dev` — Vite HMR untuk CSS/JS

### 9.2 Kontainerisasi Docker

Sistem dilengkapi `Dockerfile` berbasis **php:8.2-cli** yang mencakup instalasi otomatis seluruh dependensi sistem:

```dockerfile
FROM php:8.2-cli

# Install Node.js, Composer, ekstensi PHP
RUN apt-get update && apt-get install -y \
    nodejs npm curl zip unzip \
    libpng-dev libzip-dev libonig-dev

RUN docker-php-ext-install pdo_mysql gd zip mbstring

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm ci && npm run build

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
```

### 9.3 Pipeline Deployment ke Production

**Urutan Langkah Deployment:**

```bash
# 1. Migrasi Database
php artisan migrate --force

# 2. Seed Data Awal (jika deployment baru)
php artisan db:seed

# 3. Build Asset Production
npm run build

# 4. Cache Konfigurasi & Route
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Buat storage symlink
php artisan storage:link

# 6. Set Permission
chmod -R 775 storage bootstrap/cache
```

### 9.4 Managed Database Cloud

Untuk deployment cloud, database MySQL dapat dihubungkan ke layanan **Managed Database Cloud** seperti:

| Layanan | Keterangan | Tier Gratis |
|:--------|:-----------|:------------|
| **Aiven.io** | MySQL 8.0, free tier 1 node | Ya (500MB) |
| **TiDB Cloud** | MySQL-compatible, serverless | Ya (5GB) |
| **PlanetScale** | Vitess-MySQL serverless | Ya (terbatas) |

Konfigurasi pada `.env` production:
```ini
DB_CONNECTION=mysql
DB_HOST=your-cloud-db-host.com
DB_PORT=3306
DB_DATABASE=silakan_production
DB_USERNAME=silakan_user
DB_PASSWORD=your_secure_password
DB_SSLMODE=REQUIRED
```

---

## BAB X — GLOSARIUM & REFERENSI

### 10.1 Glosarium Teknis

| Istilah | Definisi |
|:--------|:---------|
| **Eloquent ORM** | Object-Relational Mapping bawaan Laravel untuk interaksi database melalui objek PHP |
| **Blade** | Template engine bawaan Laravel untuk merender HTML di sisi server |
| **Migration** | File PHP yang mendefinisikan skema tabel database secara programatik (version control database) |
| **Seeder** | File PHP untuk mengisi data awal ke database saat instalasi |
| **Middleware** | Layer kode yang berjalan sebelum/sesudah request HTTP mencapai Controller |
| **Action Pattern** | Pola desain yang memisahkan satu operasi bisnis ke satu kelas independen |
| **Query Scope** | Metode di Model Eloquent yang mengenkapsulasi kondisi query yang sering digunakan |
| **Pessimistic Locking** | Teknik database locking (`lockForUpdate`) yang mencegah dua transaksi memodifikasi row yang sama secara bersamaan |
| **Race Condition** | Kondisi di mana dua proses bersamaan mengakses data yang sama dan menghasilkan hasil yang tidak konsisten |
| **CSRF Token** | Token anti-pemalsuan yang memvalidasi request berasal dari pengguna yang sah |
| **Vite HMR** | Hot Module Replacement — pembaruan aset (CSS/JS) di browser tanpa reload penuh saat development |
| **Accessor** | Metode di Model Eloquent yang memanipulasi nilai atribut sebelum dikembalikan |
| **Pivot Table** | Tabel penghubung relasi many-to-many antara dua tabel entitas |
| **Casting** | Konversi otomatis tipe data saat membaca dari / menulis ke database |

### 10.2 Referensi

| No. | Dokumen / Sumber | Tautan |
|:----|:-----------------|:-------|
| 1 | Laravel 12 Documentation | https://laravel.com/docs/12.x |
| 2 | Eloquent ORM Documentation | https://laravel.com/docs/12.x/eloquent |
| 3 | PHP 8.2 Enum Documentation | https://www.php.net/manual/en/language.enumerations.php |
| 4 | Laravel Notifications | https://laravel.com/docs/12.x/notifications |
| 5 | Vite Integration Laravel | https://laravel.com/docs/12.x/vite |
| 6 | barryvdh/laravel-dompdf | https://github.com/barryvdh/laravel-dompdf |
| 7 | maatwebsite/excel | https://laravel-excel.com/ |
| 8 | phpoffice/phpword | https://phpword.readthedocs.io/ |
| 9 | MySQL 8.0 Reference | https://dev.mysql.com/doc/refman/8.0/en/ |

---

*Dokumen ini disusun berdasarkan analisis langsung terhadap codebase sistem SILAKAN versi 1.0.0 yang telah diimplementasikan dan di-deploy di lingkungan KPwBI Prov. Sulut.*

*— Tim Pengembang SILAKAN, Agustus 2026 —*
