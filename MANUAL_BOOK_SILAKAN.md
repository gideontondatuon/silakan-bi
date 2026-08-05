# 📘 BUKU PANDUAN PENGGUNAAN SISTEM (MANUAL BOOK)
## **SILAKAN — Sistem Informasi Layanan Kantor**
### **Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara**

---

## 📑 **DAFTAR ISI**
1. [BAB I: PENDAHULUAN](#bab-i-pendahuluan)
   - 1.1 Latar Belakang & Tujuan Sistem
   - 1.2 Hak Akses Pengguna (Role User & Admin)
   - 1.3 Akses Perangkat & Alamat URL Sistem
2. [BAB II: PANDUAN PENGGUNA (USER / PEGAWAI UNIT)](#bab-ii-panduan-pengguna-user--pegawai-unit)
   - 2.1 Login & Dashboard User
   - 2.2 Tata Cara Mengajukan Pemesanan Ruangan Rapat
   - 2.3 Memantau Status & Riwayat Pemesanan
   - 2.4 Pembatalan Pemesanan Ruangan
   - 2.5 Mengelola Notifikasi & Profil Pengguna
3. [BAB III: PANDUAN ADMINISTRATOR (ADMIN)](#bab-iii-panduan-administrator-admin)
   - 3.1 Dashboard Analytics Administrator
   - 3.2 Alur Persetujuan (Approval & Rejection) Pemesanan
   - 3.3 Pemantauan Kegiatan Berlangsung (Live Monitoring)
   - 3.4 Kelola Master Data Ruangan & Penghubung Layout
   - 3.5 Kelola Master Data Layout Ruangan
   - 3.6 Kelola Master Data Hari Libur & Sync Cuti Bersama
   - 3.7 Manajemen User & Proteksi Keamanan Akun
   - 3.8 Laporan Rekapitulasi & Ekspor Data (Excel & Cetak PDF)
   - 3.9 Audit Log System (Rekam Jejak Aktivitas)
4. [BAB IV: PANDUAN TAMPILAN TV MONITOR LOBBY (KIOSK MODE)](#bab-iv-panduan-tampilan-tv-monitor-lobby-kiosk-mode)
   - 4.1 Mengakses Mode Layar TV Lobby (`/display`)
   - 4.2 Pembaruan Otomatis (Real-Time Auto Refresh)
5. [BAB V: TROUBLESHOOTING & FAQ](#bab-v-troubleshooting--faq)
   - 5.1 FAQ Pemesanan & Bentrok Jadwal
   - 5.2 Penanganan Kendala Teknis & Bantuan

---

## 📌 **BAB I: PENDAHULUAN**

### **1.1 Latar Belakang & Tujuan Sistem**
Sistem Informasi Layanan Kantor (**SILAKAN**) dikembangkan untuk mengotomatisasi, memodernisasi, dan mentransparansikan alur pemesanan ruangan rapat di lingkungan **Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara (KPwBI Prov. Sulut)**. 

Dengan SILAKAN, pengajuan pemesanan ruangan rapat yang sebelumnya manual kini dapat dilakukan secara digital dengan fitur pengecekan bentrok jadwal otomatis, penyesuaian layout ruangan khusus, notifikasi WhatsApp real-time, serta pemantauan layar TV lobby.

### **1.2 Hak Akses Pengguna (Role System)**
Sistem SILAKAN membagi tingkat akses menjadi 2 kelompok utama:
1. **User (Pegawai / Unit Kerja)**: Berhak mengajukan pemesanan ruangan rapat, melihat status pengajuan, membatalkan pengajuan, melihat kalender kegiatan, dan mengelola profil akun unit.
2. **Administrator (Admin Service / Sekpr/ Rumah Tangga)**: Berhak melakukan persetujuan (*Approval/Reject*) pengajuan, mengelola master data ruangan & layout, mengelola data user, memantau kegiatan berlangsung, mencetak laporan rekapitulasi, dan melihat log aktivitas audit.

### **1.3 Akses Perangkat & Alamat URL Sistem**
- **Akses Web Browser**: Dapat dibuka melalui PC/Laptop dan Smartphone (Android & iOS) di alamat lokal `http://localhost:8000` atau IP Server Jaringan Lokal BI.
- **Akses TV Monitor Lobby**: Dibuat khusus untuk layar monitor kiosk di alamat `http://localhost:8000/display`.

---

## 📌 **BAB II: PANDUAN PENGGUNA (USER / PEGAWAI UNIT)**

### **2.1 Login & Dashboard User**
1. Buka halaman utama SILAKAN di browser.
2. Masukkan **Username ID** dan **Password** unit kerja Anda (Contoh: `humas` / `keuangan` / `sdm`).
3. Klik tombol **Login Masuk**.
4. Halaman Dashboard User akan menampilkan statistik ringkas:
   - Total Pemesanan Unit
   - Pemesanan Menunggu Persetujuan (*Pending*)
   - Pemesanan Disetujui & Agenda Mendatang
   - Daftar Kegiatan Berlangsung Hari Ini di KPwBI Sulut.

---

### **2.2 Tata Cara Mengajukan Pemesanan Ruangan Rapat**

> **Aturan Pemesanan**: Sistem akan menolak otomatis jika waktu rapat bentrok dengan pengajuan lain yang sudah disetujui, atau jika jumlah tamu melebihi kapasitas maksimal ruangan.

Langkah-langkah pengajuan pemesanan:
1. Klik menu **Pemesanan** $\rightarrow$ **Buat Pemesanan Baru** pada sidebar.
2. **Pilih Ruangan Rapat**: Pilih ruangan rapat yang akan digunakan (misal: *Balai Kerapuan, Ruangan Siladen, Manado, Minahasa, Lokon, Bunaken*).
3. **Pilih Layout Ruangan**:
   - Sistem akan memuat opsi layout secara otomatis yang terhubung dengan ruangan terpilih (*U-Shape, Classroom, Teater, Interview Set, Transit Room, Round Table*).
   - Jika ruangan tidak memiliki pilihan layout khusus (seperti *Ruangan Bunaken*), dropdown akan menampilkan keterangan *`-- Tidak ada layout khusus untuk ruangan ini --`*.
4. **Isi Detail Kegiatan**:
   - **Tanggal Kegiatan**: Tanggal pelaksanaan acara.
   - **Waktu Mulai & Waktu Selesai**: Format jam (WITA).
   - **Judul Kegiatan**: Nama agenda rapat / kegiatan.
   - **PIC Kegiatan & Jenis PIC**: Nama penanggung jawab dan kategori (*Organik / Non Organik*).
   - **Nomor WhatsApp PIC**: Nomor WA aktif untuk menerima notifikasi status otomatis.
   - **Jumlah Tamu / Peserta**: Jumlah orang (sistem akan mengecek otomatis terhadap kapasitas ruangan).
5. **Unggah Lembar Disposisi (Opsional)**: Lampirkan berkas bukti disposisi/nota dinas (Format: PDF/JPG/PNG, Max: 5MB).
6. Klik **Kirim Pengajuan Pemesanan**.

---

### **2.3 Memantau Status & Riwayat Pemesanan**
1. Klik menu **Riwayat** pada sidebar.
2. Pengguna dapat melihat daftar seluruh pengajuan beserta label status:
   - <span style="color:#d97706;font-weight:bold;">🟡 Menunggu Approval</span>: Pengajuan sedang ditinjau oleh Admin.
   - <span style="color:#16a34a;font-weight:bold;">🟢 Disetujui</span>: Pemesanan telah disetujui Admin dan ruangan siap digunakan.
   - <span style="color:#dc2626;font-weight:bold;">🔴 Ditolak</span>: Pengajuan ditolak Admin (beserta alasan penolakan).
   - <span style="color:#64748b;font-weight:bold;">⚪ Dibatalkan</span>: Pengajuan yang telah dibatalkan oleh pengguna.
3. Klik tombol **Detail** untuk melihat rincian lengkap pemesanan.

---

### **2.4 Pembatalan Pemesanan Ruangan**
1. Buka menu **Riwayat** $\rightarrow$ Klik **Detail** pada pemesanan yang ingin dibatalkan.
2. Klik tombol **Batalkan Pemesanan** di bagian bawah.
3. Konfirmasi pembatalan. Status akan berubah menjadi *Dibatalkan* dan ruangan kembali terbuka untuk pemesanan lain.

---

### **2.5 Mengelola Notifikasi & Profil Pengguna**
1. **Melihat Notifikasi**: Klik ikon lonceng di bagian kanan atas navbar atau buka menu **Notifikasi**.
2. **Menghapus Notifikasi**: 
   - Klik ikon tempat sampah di kanan notifikasi untuk menghapus pesan tertentu.
   - Klik tombol **Hapus Semua** di header untuk membersihkan seluruh riwayat notifikasi.
3. **Mengubah Profil & Password**:
   - Buka menu **Profil Saya**.
   - Pengguna dapat mengupdate Nomor WhatsApp, Alamat Email, serta memperbarui Password Akun.

---

## 📌 **BAB III: PANDUAN ADMINISTRATOR (ADMIN)**

### **3.1 Dashboard Analytics Administrator**
Admin memiliki akses ke dashboard analitis lengkap:
- Kartu Ringkasan Total Ruangan, Total Pemesanan, Pengajuan Pending, dan Disetujui.
- **Grafik Tren Pemesanan 6 Bulan Terakhir**.
- **Diagram Ruangan Terpopuler**.
- **Diagram Distribusi Pemakaian per Unit Kerja**.
- Tabel *Waiting List Approval* & *Kegiatan Sedang Berlangsung*.

---

### **3.2 Alur Persetujuan (Approval & Rejection) Pemesanan**
1. Buka menu **Waiting List** pada sidebar Admin.
2. Klik **Review Approval** pada pengajuan yang masuk.
3. Periksa rincian kegiatan, jadwal, ketersediaan layout, dan berkas disposisi.
4. **Jika Disetujui**: Klik tombol hijau **Setujui Pemesanan** (Otomatis mengirimkan notifikasi WA & Web ke pengguna).
5. **Jika Ditolak**: Klik tombol merah **Tolak Pemesanan**, masukkan alasan penolakan secara tertulis, lalu klik **Kirim Penolakan**.

---

### **3.3 Pemantauan Kegiatan Berlangsung (Live Monitoring)**
1. Buka menu **Kegiatan Berlangsung** pada sidebar Admin.
2. Halaman ini memantau ruangan rapat yang sedang terpakai saat ini secara real-time.
3. Dilengkapi **Live Countdown Timer** yang menghitung sisa waktu rapat secara mundur hingga rapat berakhir.

---

### **3.4 Kelola Master Data Ruangan & Penghubung Layout**
1. Buka menu **Master Data** $\rightarrow$ **Data Ruangan**.
2. **Menambah Ruangan Baru**: Klik tombol **+ Tambah Ruangan**, isi nama ruangan, lokasi/lantai, kapasitas maksimal, status (*Aktif/Nonaktif*), dan foto ruangan.
3. **Menghubungkan Layout ke Ruangan**:
   - Pada form Tambah/Edit Ruangan, centang opsi **Layout yang Tersedia**.
   - Pilihan layout menggunakan *custom rounded badge* biru BI yang interaktif.
   - Hanya layout yang dicentang yang akan muncul di dropdown saat user memilih ruangan ini.
4. Klik **Simpan Data Ruangan**.

---

### **3.5 Kelola Master Data Layout Ruangan**
1. Buka menu **Master Data** $\rightarrow$ **Data Layout**.
2. Admin dapat menambah, mengubah nama, atau menghapus master layout (seperti *U-Shape, Classroom, Teater, Interview Set, Transit Room, Round Table*).

---

### **3.6 Kelola Master Data Hari Libur & Sync Cuti Bersama**
1. Buka menu **Master Data** $\rightarrow$ **Hari Libur**.
2. **Sync Otomatis**: Klik tombol **Sync API Libur Nasional** untuk menarik data hari libur resmi pemerintah.
3. **Tambah Manual**: Admin dapat menambahkan Hari Libur Internal Bank Indonesia atau Cuti Bersama secara manual.
4. Pemesanan pada tanggal libur akan diberi peringatan oleh sistem.

---

### **3.7 Manajemen User & Proteksi Keamanan Akun**
1. Buka menu **Master Data** $\rightarrow$ **Data User**.
2. Admin dapat menambah akun pegawai/unit baru, mereset password, dan mengubah role (*Admin / User*).
3. **Proteksi Keamanan Self-Delete**: Sistem secara otomatis melarang Admin yang sedang aktif login untuk menghapus akunnya sendiri untuk mencegah kehilangan akses.

---

### **3.8 Laporan Rekapitulasi & Ekspor Data (Excel & Cetak PDF)**
1. Buka menu **Sistem** $\rightarrow$ **Laporan**.
2. Filter data berdasarkan **Rentang Tanggal**, **Ruangan**, **Unit Kerja**, atau **Status Pemesanan**.
3. **Ekspor Excel**: Klik **Export Excel** untuk mengunduh berkas `.xlsx` resmi.
4. **Cetak PDF**: Klik **Cetak Laporan / PDF** untuk membuka pratinjau siap cetak ber-KOP resmi Bank Indonesia KPwBI Prov. Sulut.

---

### 3.9 Audit Log System (Rekam Jejak Aktivitas)
1. Buka menu **Sistem** $\rightarrow$ **Audit Log**.
2. Mencatat seluruh rekam jejak aktivitas penting pengguna dan admin (Waktu, User, Aksi, IP Address, dan Keterangan).

---

## 📌 **BAB IV: PANDUAN TAMPILAN TV MONITOR LOBBY (KIOSK MODE)**

### **4.1 Mengakses Mode Layar TV Lobby (`/display`)**
1. Pada Komputer / Smart TV di Lobby Kantor, buka browser dan ketik alamat:
   `http://localhost:8000/display`
2. Tampilan dirancang khusus berlayar penuh (*Full Screen / Kiosk Mode*) dengan tema gelap khas Bank Indonesia.

### **4.2 Pembaruan Otomatis (Real-Time Auto Refresh)**
- Layar TV Lobby akan secara otomatis memperbarui data kegiatan berlangsung dan agenda hari ini via API JSON latar belakang setiap 30 detik **tanpa perlu di-reload manual**.

---

## 📌 **BAB V: TROUBLESHOOTING & FAQ**

### **5.1 FAQ Pemesanan & Bentrok Jadwal**
- **Q: Mengapa pilihan layout tidak muncul saat memilih Ruangan Bunaken?**
  - *A: Karena Ruangan Bunaken disetting tidak memiliki layout khusus. Dropdown akan secara benar menampilkan `-- Tidak ada layout khusus untuk ruangan ini --`.*
- **Q: Mengapa sistem menolak waktu pemesanan saya?**
  - *A: Sistem mendeteksi adanya bentrok jam rapat dengan pengajuan lain yang sudah disetujui di ruangan yang sama.*

### **5.2 Penanganan Kendala Teknis & Bantuan**
Jika mengalami kendala teknis pada sistem SILAKAN, silakan hubungi:
- **Tim Administrator SILAKAN**: Sekpr / Tim Rumah Tangga KPwBI Prov. Sulut.
- **Lokasi**: Gedung Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara, Manado.
