# 📐 DOKUMEN DESAIN PERANGKAT LUNAK (SOFTWARE DESIGN DOCUMENT - SDD)
## **SILAKAN — Sistem Informasi Layanan Kantor**
### **Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara**

---

## 📑 **DAFTAR ISI**
1. [BAB I: PENDAHULUAN](#bab-i-pendahuluan)
   - 1.1 Tujuan Dokumen
   - 1.2 Cakupan Sistem (System Scope)
   - 1.3 Definisi & Akronim
2. [BAB II: ARSITEKTUR SISTEM KESELURUHAN](#bab-ii-arsitektur-sistem-keseluruhan)
   - 2.1 Pola Arsitektur MVC (Model-View-Controller)
   - 2.2 Diagram Blok Arsitektur Tingkat Tinggi (High-Level Architecture)
   - 2.3 Technology Stack & Lingkungan Pengembangan
3. [BAB III: DESAIN DATABASE & DESAIN DATA](#bab-iii-desain-database--desain-data)
   - 3.1 Entity Relationship Diagram (ERD) & Relasi Data
   - 3.2 Spesifikasi & Kamus Data Tabel Database
   - 3.3 Enumerasi & Casting Data (`Role` & `PemesananStatus`)
   - 3.4 Pengecekan Integritas & Database Transaction Control
4. [BAB IV: DESAIN KOMPONEN & ARSITEKTUR KODE](#bab-iv-desain-komponen--arsitektur-kode)
   - 4.1 Action Pattern Layer (Business Logic Scoping)
   - 4.2 Controller & Routing Layer
   - 4.3 Form Request Validation Layer
   - 4.4 Shared Service Layer (Audit Log & WhatsApp Gateway)
5. [BAB V: DESAIN ANTARMUKA & PERILAKU PENGGUNA](#bab-v-desain-antarmuka--perilaku-pengguna)
   - 5.1 System Design Tokens & Branding (Warna Khas BI)
   - 5.2 Antarmuka Pengguna (User Interface) & Smart Filtering
   - 5.3 Antarmuka Layar Kiosk TV Lobby (`/display`)
   - 5.4 Peta Route & Endpoint URL Sistem
6. [BAB VI: ARSITEKTUR KEAMANAN SISTEM](#bab-vi-arsitektur-keamanan-sistem)
   - 6.1 Autentikasi & Otorisasi Berbasis Role
   - 6.2 Proteksi Injeksi Data, CSRF & Upload Hygiene
   - 6.3 Proteksi Sesi Active Self-Delete Guard
7. [BAB VII: ARSITEKTUR DEPLOYMENT & KONTAINERISASI](#bab-vii-arsitektur-deployment--kontainerisasi)
   - 7.1 Kontainerisasi Docker (`Dockerfile`)
   - 7.2 Panduan Deploy Cloud & Database Managed Service

---

## 📌 **BAB I: PENDAHULUAN**

### **1.1 Tujuan Dokumen**
Dokumen Desain Perangkat Lunak (**Software Design Document - SDD**) ini disusun sebagai acuan teknis komprehensif yang menguraikan struktur arsitektur, desain basis data, pola kode, mekanisme keamanan, dan antarmuka dari sistem **SILAKAN (Sistem Informasi Layanan Kantor)** di **Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara (KPwBI Prov. Sulut)**.

### **1.2 Cakupan Sistem (System Scope)**
Sistem SILAKAN mencakup modul operasional utama:
1. **Modul Pemesanan Ruangan Rapat**: Pengajuan pemesanan ruangan, pengecekan bentrok jam otomatis (WITA), penyesuaian kapasitas & layout dinamis, serta lampiran disposisi.
2. **Modul Approval & Tinjauan Admin**: Peninjauan berkas, persetujuan/penolakan dengan catatan, serta notifikasi otomatis via WhatsApp & Web.
3. **Modul Live Monitoring & TV Lobby Kiosk**: Pemantauan ruangan aktif dengan *Live Countdown Timer* dan layar TV Lobby auto-refresh real-time.
4. **Modul Master Data & Sistem**: Pengelolaan Data Ruangan, Layout, Hari Libur/Sync API, Users, Audit Log, dan Laporan Rekapitulasi PDF/Excel.

### **1.3 Definisi & Akronim**
- **KPwBI Prov. Sulut**: Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara.
- **SILAKAN**: Sistem Informasi Layanan Kantor.
- **MVC**: Model-View-Controller (Pola arsitektur lunak Laravel).
- **WITA**: Waktu Indonesia Tengah (UTC+8).
- **ERD**: Entity Relationship Diagram.

---

## 📌 **BAB II: ARSITEKTUR SISTEM KESELURUHAN**

### **2.1 Pola Arsitektur MVC (Model-View-Controller)**
SILAKAN dibangun di atas framework **Laravel 12** menggunakan prinsip pembagian tugas (*Separation of Concerns*):
- **Model**: Mengelola objek bisnis, relasi Eloquent, casting enum, dan pemanggilan database scope.
- **View**: Menyajikan antarmuka responsif berbasis Blade Templating Engine & Vite CSS/JS bundler.
- **Controller & Action**: Mengatur alur logika bisnis, validasi request, serta eksekusi transaksi database.

```
                  +-----------------------------------+
                  |         HTTP Request (User)       |
                  +-----------------------------------+
                                    |
                                    v
                  +-----------------------------------+
                  |       Middleware & Routing        |
                  |     (auth, role:admin/user)       |
                  +-----------------------------------+
                                    |
                                    v
                  +-----------------------------------+
                  |     Controllers & Actions Layer   |
                  | (CreatePemesanan, Approve, etc.)  |
                  +-----------------------------------+
                       /            |            \
                      /             |             \
                     v              v              v
        +------------------+ +--------------+ +--------------------+
        |   Database / DB  | | View (Blade) | | Services (WhatsApp |
        | Transaction Lock | | & CSS Tokens | | & Audit Log)       |
        +------------------+ +--------------+ +--------------------+
```

### **2.2 Technology Stack & Lingkungan Pengembangan**
- **Framework Utama**: Laravel 12.0 (PHP 8.2+).
- **Frontend & Asset Bundler**: Vite v6.4, Custom Vanilla CSS (Sistem Tokoh Warna Bank Indonesia), Bootstrap Icons.
- **Database Engine**: MySQL 8.0+ / SQLite.
- **Pustaka Ekspor & Cetak**: `barryvdh/laravel-dompdf`, `maatwebsite/excel`, `phpoffice/phpword`.
- **Kontainerisasi**: Docker & Docker Compose.

---

## 📌 **BAB III: DESAIN DATABASE & DESAIN DATA**

### **3.1 Entity Relationship Diagram (ERD) & Relasi Data**
Database SILAKAN tersusun atas 8 tabel utama yang saling terhubung:

1. **`users`** 1 — N **`pemesanan`** (Pengguna mengajukan banyak pemesanan).
2. **`ruangan`** 1 — N **`pemesanan`** (Satu ruangan memiliki banyak riwayat pemesanan).
3. **`ruangan`** N — N **`layout_ruangan`** via pivot **`ruangan_layout`** (Relasi dinamis layout per ruangan).
4. **`layout_ruangan`** 1 — N **`pemesanan`** (Satu pemesanan memilih satu layout spesifik).
5. **`users`** 1 — N **`audit_logs`** (Catatan aktivitas audit per pengguna).

---

### **3.2 Spesifikasi & Kamus Data Tabel Database**

#### **Tabel `ruangan`**
| Field | Tipe Data | Aturan / Keterangan |
| :--- | :--- | :--- |
| `id` | BigInt (PK) | Auto Increment |
| `nama_ruangan` | Varchar(255) | Nama Ruangan Rapat (e.g. Balai Kerapuan, Bunaken) |
| `lokasi` | Varchar(255) | Gedung / Lantai |
| `kapasitas` | Integer | Kapasitas Maksimal Tamu/Peserta |
| `status` | Varchar(50) | Status Ruangan (`aktif` / `nonaktif`) |
| `foto` | Varchar(255) | Path Berkas Foto Ruangan |

#### **Tabel `layout_ruangan`**
| Field | Tipe Data | Aturan / Keterangan |
| :--- | :--- | :--- |
| `id` | BigInt (PK) | Auto Increment |
| `nama_layout` | Varchar(255) | Opsi Layout (U-Shape, Classroom, Teater, etc) |
| `deskripsi` | Text | Penjelasan/Keterangan Tambahan |
| `ruangan_id` | BigInt (FK) | Nullable Foreign Key |

#### **Tabel `pemesanan`**
| Field | Tipe Data | Aturan / Keterangan |
| :--- | :--- | :--- |
| `id` | BigInt (PK) | Auto Increment |
| `kode_pemesanan` | Varchar(50) | Unique Code (`SIL-YYYYMMDD-XXXXX`) |
| `user_id` | BigInt (FK) | Relasi ke `users.id` |
| `ruangan_id` | BigInt (FK) | Relasi ke `ruangan.id` |
| `layout_ruangan_id` | BigInt (FK) | Nullable Relasi ke `layout_ruangan.id` |
| `tanggal_kegiatan` | Date | Tanggal Pelaksanaan Rapat |
| `waktu_mulai` | Time | Jam Mulai (WITA) |
| `waktu_selesai` | Time | Jam Selesai (WITA) |
| `judul_kegiatan` | Varchar(150) | Nama Agenda Rapat |
| `pic_kegiatan` | Varchar(255) | Penanggung Jawab Rapat |
| `jenis_pic` | Enum | `Organik` / `Non Organik` |
| `no_wa_pic` | Varchar(20) | Nomor WhatsApp PIC |
| `jumlah_tamu` | Integer | Jumlah Orang (Min: 1) |
| `file_disposisi` | Varchar(255) | Path Berkas Upload Disposisi |
| `status` | Varchar(50) | Enum Status (`PENDING`, `DISETUJUI`, `DITOLAK`, `DIBATALKAN`) |

---

### **3.3 Enumerasi & Casting Data (`Role` & `PemesananStatus`)**
- **Enum `Role`** (`app/Enums/Role.php`):
  - `ADMIN = 'admin'` (Administrator System)
  - `USER = 'user'` (Pegawai Unit Kerja)
- **Enum `PemesananStatus`** (`app/Enums/PemesananStatus.php`):
  - `PENDING = 'PENDING'`
  - `DISETUJUI = 'DISETUJUI'`
  - `DITOLAK = 'DITOLAK'`
  - `DIBATALKAN = 'DIBATALKAN'`

---

### **3.4 Pengecekan Integritas & Database Transaction Control**
Proses transaksi pembuatan dan persetujuan pemesanan dibungkus menggunakan `DB::transaction()` dan pessimistic row locking `lockForUpdate()` untuk mencegah *race condition* (dua pengajuan yang masuk secara bersamaan pada mili-detik yang sama).

---

## 📌 **BAB IV: DESAIN KOMPONEN & ARSITEKTUR KODE**

### **4.1 Action Pattern Layer (Business Logic Scoping)**
Pola Action digunakan untuk memisahkan eksekusi bisnis inti dari Controller:
- `CreatePemesananAction.php`: Mengunci ruangan, validasi kapasitas, pengecekan bentrok jam rapat, pembuatan record `pemesanan`, perekaman `AuditLogService`, dan mentrigger notifikasi WhatsApp & Web.
- `ApprovePemesananAction.php`: Mengubah status menjadi `DISETUJUI`, mengunci ketersediaan jadwal, dan mengirim notifikasi WhatsApp persetujuan.
- `RejectPemesananAction.php`: Mengubah status menjadi `DITOLAK`, mencatat alasan penolakan, dan mentrigger notifikasi penolakan.
- `CancelPemesananAction.php`: Mengubah status menjadi `DIBATALKAN` atas permintaan pengguna.

---

### **4.2 Controller & Routing Layer**
- `PemesananController.php`: Menangani halaman pengajuan user, pembatalan, dan API pengecekan bentrok real-time (`/api/pemesanan/check-conflict`).
- `LayoutController.php`: Menampilkan API JSON layout terikat ruangan (`/api/ruangan/{id}/layouts`) tanpa *fallback error*.
- `ApprovalController.php`: Menangani antarmuka tinjauan admin untuk menyetujui atau menolak pemesanan.
- `DisplayController.php`: Menangani layar TV Lobby (`/display`) dan API polling 30 detik (`/api/display-data`).

---

### **4.3 Form Request Validation Layer (`StorePemesananRequest.php`)**
Menangani validasi input form pemesanan secara ketat:
```php
protected function prepareForValidation(): void
{
    if (empty($this->layout_ruangan_id)) {
        $this->merge(['layout_ruangan_id' => null]);
    }
}
```
Metode ini secara otomatis mengonversi opsi kosong layout (`""`) menjadi `null` sebelum proses validasi database.

---

### **4.4 Shared Service Layer**
- **`AuditLogService`** (`app/Services/AuditLogService.php`): Perekam log aktivitas sistem terpusat (Mencatat Waktu, User, Modul, IP, dan Keterangan).
- **`WhatsAppService`** (`app/Services/WhatsAppService.php`): Integrasi gateway pengiriman notifikasi WhatsApp otomatis ke PIC & Admin.

---

## 📌 **BAB V: DESAIN ANTARMUKA & PERILAKU PENGGUNA**

### **5.1 System Design Tokens & Branding BI**
Palet warna utama SILAKAN mengikuti standar identitas visual Bank Indonesia:
- **BI Deep Blue (Primary)**: `#003B73`
- **BI Blue (Secondary)**: `#005BAA`
- **BI Light Blue (Accent)**: `#0EA5E9`
- **BI Dark Gray (Text)**: `#0F172A`
- **Status Green (Approved)**: `#10B981`
- **Status Red (Rejected)**: `#EF4444`

---

### **5.2 Antarmuka Pengguna & Smart Filtering**
- **Dropdown Layout Dinamis**: Saat user memilih ruangan, skrip JavaScript secara otomatis melakukan fetch API ke `/api/ruangan/{id}/layouts`. Jika ruangan memiliki 0 layout (seperti *Ruangan Bunaken*), dropdown tetap tampil namun berisi pesan `-- Tidak ada layout khusus untuk ruangan ini --`.
- **Badge Rounded Layout Checkbox**: Pada form admin ruangan, pilihan layout dilengkapi badge bulat biru dengan ikon centang `✓` interaktif.

---

### **5.3 Antarmuka Layar Kiosk TV Lobby (`/display`)**
Menggunakan mode tampilan *Dark Mode Kiosk* dengan KOP Bank Indonesia. Halaman ini mengeksekusi skrip AJAX polling ke `/api/display-data` setiap 30 detik untuk memperbarui status rapat secara otomatis tanpa reload browser.

---

## 📌 **BAB VI: ARSITEKTUR KEAMANAN SISTEM**

### **6.1 Autentikasi & Otorisasi Berbasis Role**
Dilindungi oleh Middleware Laravel:
- `auth`: Memastikan user telah terautentikasi.
- `role:admin`: Membatasi seluruh route `/admin/*` hanya untuk role `ADMIN`.
- `role:user`: Membatasi dashboard khusus unit pengguna.

### **6.2 Proteksi Injeksi Data, CSRF & Upload Hygiene**
- **CSRF Token**: Seluruh form POST/PUT/DELETE wajib menyertakan `@csrf`.
- **SQL Injection Prevention**: Menggunakan Eloquent ORM & PDO Prepared Statements.
- **Upload Hygiene**: Unggahan berkas disposisi dibatasi ekstensi `pdf,jpg,jpeg,png` dengan ukuran maksimal 5120 KB (5MB).

### **6.3 Proteksi Sesi Active Self-Delete Guard**
Pada `UserController::destroy()`, sistem mengeksekusi proteksi:
```php
if ($user->id === auth()->id()) {
    return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
}
```
Mencegah Admin yang sedang aktif login terhapus secara tidak sengaja.

---

## 📌 **BAB VII: ARSITEKTUR DEPLOYMENT & KONTAINERISASI**

### **7.1 Kontainerisasi Docker (`Dockerfile`)**
Sistem dilengkapi `Dockerfile` berbasis `php:8.2-cli` yang memuat Node.js, Composer, ekstensi `pdo_mysql`, dan otomatis menjalankan migrasi & seeder saat kontainer di-spin up.

### **7.2 Panduan Deploy Cloud & Database Managed Service**
Aplikasi dapat dideploy dengan mudah di platform cloud (seperti Render / Koyeb / InfinityFree) dan dihubungkan ke Managed Cloud Database MySQL (seperti Aiven.io / TiDB Cloud).
