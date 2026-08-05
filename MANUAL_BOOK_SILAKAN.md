# 📘 BUKU PANDUAN PENGGUNAAN SISTEM (MANUAL BOOK)
## **SILAKAN — Sistem Informasi Layanan Kantor**
### **Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara**

---

## 📑 **DAFTAR ISI**
1. [BAB I: PENDAHULUAN](#bab-i-pendahuluan)
   - 1.1 Latar Belakang & Tujuan Sistem
   - 1.2 Hak Akses Pengguna (Role User & Admin)
   - 1.3 Perangkat, Browser & Alamat Akses Sistem
2. [BAB II: PANDUAN PENGGUNA (USER / PEGAWAI UNIT)](#bab-ii-panduan-pengguna-user--pegawai-unit)
   - 2.1 Proses Login & Memahami Dashboard User
   - 2.2 Tata Cara Mengajukan Pemesanan Ruangan Rapat (Langkah Demi Langkah)
   - 2.3 Pemilihan Ruangan & Smart Layout Filtering
   - 2.4 Pengecekan Bentrok Jadwal & Kapasitas Tamu
   - 2.5 Pengungahan Lembar Disposisi / Nota Dinas
   - 2.6 Memantau Status & Riwayat Pemesanan
   - 2.7 Pembatalan Pemesanan Ruangan
   - 2.8 Mengelola Notifikasi & Fitur Hapus Pesan
   - 2.9 Mengelola Profil & Pengaturan Keamanan Password
3. [BAB III: PANDUAN ADMINISTRATOR (ADMIN)](#bab-iii-panduan-administrator-admin)
   - 3.1 Dashboard Analytics Administrator & Diagram Stat
   - 3.2 Alur Persetujuan (Approval & Rejection) Pemesanan
   - 3.3 Pemantauan Kegiatan Berlangsung (Live Monitoring) & Countdown Timer
   - 3.4 Kelola Master Data Ruangan & Centang Rounded Badge Layout
   - 3.5 Kelola Master Data Layout Ruangan
   - 3.6 Kelola Master Data Hari Libur & Sync API Cuti Bersama
   - 3.7 Manajemen User & Proteksi Keamanan Self-Delete
   - 3.8 Laporan Rekapitulasi, Ekspor Excel & Pratinjau Cetak PDF BI
   - 3.9 Audit Log System (Rekam Jejak Aktivitas Terpusat)
4. [BAB IV: PANDUAN TAMPILAN TV MONITOR LOBBY (KIOSK MODE)](#bab-iv-panduan-tampilan-tv-monitor-lobby-kiosk-mode)
   - 4.1 Mengakses Mode Layar TV Lobby (`/display`)
   - 4.2 Pembaruan Otomatis Latar Belakang (Auto Refresh Real-Time)
5. [BAB V: TROUBLESHOOTING & FREQUENTLY ASKED QUESTIONS (FAQ)](#bab-v-troubleshooting--frequently-asked-questions-faq)
   - 5.1 FAQ Pemesanan, Bentrok & Layout Ruangan
   - 5.2 Penanganan Kendala Teknis & Layanan Bantuan

---

## 📌 **BAB I: PENDAHULUAN**

### **1.1 Latar Belakang & Tujuan Sistem**
Sistem Informasi Layanan Kantor (**SILAKAN**) dikembangkan khusus untuk mengotomatisasi, memodernisasi, dan mentransparansikan seluruh alur layanan operasional internal di lingkungan **Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara (KPwBI Prov. Sulut)**.

Dengan SILAKAN, pemesanan ruangan rapat kini dilakukan secara digital dengan fitur unggulan:
- **Pengecekan bentrok jadwal otomatis (WITA)**.
- **Filtering layout dinamis berbasis relasi ruangan**.
- **Notifikasi instan via WhatsApp & Web Notification**.
- **Live Countdown Timer kegiatan berlangsung**.
- **Integrasi layar TV Monitor Lobby (Kiosk Mode)**.

> 🖼️ **[TANGKAPAN LAYAR 1.1: Tampilan Utama Branding SILAKAN Bank Indonesia]**
> *Sisipkan gambar tangkapan layar Halaman Utama / Branding SILAKAN di bawah ini:*
> `![Branding SILAKAN KPwBI Prov Sulut](images/manual/01_branding_silakan.png)`

---

### **1.2 Hak Akses Pengguna (Role System)**
Sistem SILAKAN membagi tingkat akses menjadi 2 tingkat kewenangan:

| Role | Deskripsi Hak Akses | Akses Fitur Utama |
| :--- | :--- | :--- |
| **User (Unit Kerja)** | Diberikan kepada pegawai/unit kerja internal KPwBI Sulut. | Input Pemesanan, Riwayat, Pembatalan, Kalender, Notifikasi, Edit Profil. |
| **Administrator** | Diberikan kepada Pengelola Sarpras / Rumah Tangga / Sekpr. | Approval/Reject, Live Monitoring, Master Ruangan/Layout/User/Libur, Audit Log, Laporan PDF/Excel. |

---

### **1.3 Perangkat, Browser & Alamat Akses Sistem**
- **Browser yang Didukung**: Google Chrome, Microsoft Edge, Mozilla Firefox, Safari.
- **Perangkat**: Laptop, Komputer Desktop, Tablet, dan Smartphone (Android & iOS).
- **Alamat URL Penggunaan**:
  - **Akses Pengguna & Admin**: `http://localhost:8000` (atau IP Server Lokal BI).
  - **Akses TV Lobby**: `http://localhost:8000/display`.

> 🖼️ **[TANGKAPAN LAYAR 1.2: Tampilan SILAKAN di Laptop & Smartphone]**
> *Sisipkan gambar tangkapan layar responsif SILAKAN di bawah ini:*
> `![Tampilan Multiperangkat SILAKAN](images/manual/02_multiperangkat.png)`

---

## 📌 **BAB II: PANDUAN PENGGUNA (USER / PEGAWAI UNIT)**

### **2.1 Proses Login & Memahami Dashboard User**
1. Buka browser dan masukkan alamat URL SILAKAN.
2. Masukkan **Username ID** dan **Password** unit kerja Anda.
   *(Default Username: `humas`, `keuangan`, `sdm`, `user` | Password: `password`)*.
3. Klik tombol **Login Masuk**.

> 🖼️ **[TANGKAPAN LAYAR 2.1: Halaman Form Login User]**
> *Sisipkan gambar tangkapan layar Form Login di bawah ini:*
> `![Form Login SILAKAN](images/manual/03_login_page.png)`

4. Setelah login berhasil, Anda akan diarahkan ke **Dashboard User** yang menampilkan:
   - **Kartu Statistik**: Total Pemesanan Unit, Pemesanan Pending, dan Pemesanan Disetujui.
   - **Agenda Hari Ini**: Jadwal rapat di seluruh ruangan KPwBI Sulut hari ini.
   - **Kegiatan Berlangsung**: Status ruangan yang sedang terpakai saat ini.

> 🖼️ **[TANGKAPAN LAYAR 2.2: Halaman Dashboard User Unit Kerja]**
> *Sisipkan gambar tangkapan layar Dashboard User di bawah ini:*
> `![Dashboard User SILAKAN](images/manual/04_dashboard_user.png)`

---

### **2.2 Tata Cara Mengajukan Pemesanan Ruangan Rapat (Langkah Demi Langkah)**

1. Klik menu **Pemesanan** $\rightarrow$ **Buat Pemesanan Baru** pada sidebar sebelah kiri.

> 🖼️ **[TANGKAPAN LAYAR 2.3: Form Pembuatan Pemesanan Ruangan Baru]**
> *Sisipkan gambar tangkapan layar Form Pemesanan Baru di bawah ini:*
> `![Form Pemesanan Baru](images/manual/05_form_pemesanan.png)`

2. **Langkah 1: Pilih Ruangan Rapat**:
   Pilih salah satu ruangan rapat yang tersedia (*Balai Kerapuan, Ruangan Siladen, Ruangan Manado, Ruangan Minahasa, Ruangan Lokon, Ruangan Bunaken*).

3. **Langkah 2: Pilih Layout Ruangan (Dinamis)**:
   - Setelah ruangan dipilih, dropdown layout akan **otomatis memuat layout yang terkoneksi** saja (*U-Shape, Classroom, Teater, Interview Set, Transit Room, Round Table*).
   - Jika ruangan **tidak memiliki layout khusus** (seperti *Ruangan Bunaken*), dropdown akan secara tepat menampilkan keterangan:
     *`-- Tidak ada layout khusus untuk ruangan ini --`*.

> 🖼️ **[TANGKAPAN LAYAR 2.4: Perbandingan Dropdown Layout Dinamis]**
> *Sisipkan gambar perbandingan dropdown layout (Balai Kerapuan vs Ruangan Bunaken) di bawah ini:*
> `![Dropdown Layout Dinamis](images/manual/06_dropdown_layout.png)`

4. **Langkah 3: Mengisi Detail Waktu & Pelaksanaan**:
   - **Tanggal Kegiatan**: Tanggal dilaksanakannya rapat.
   - **Waktu Mulai & Waktu Selesai**: Format jam WITA (misal: `09:00` s/d `11:30`).
   - **Judul Kegiatan**: Nama agenda rapat / acara secara lengkap.
   - **PIC Kegiatan & Kategori PIC**: Nama penanggung jawab dan opsi (*Organik / Non Organik*).
   - **Nomor WhatsApp PIC**: Nomor WhatsApp aktif untuk pengiriman notifikasi otomatis.
   - **Jumlah Tamu / Peserta**: Jumlah orang yang hadir.

5. **Langkah 4: Validasi Bentrok Jadwal & Kapasitas**:
   - Jika jam yang dipilih bentrok dengan pemesanan lain yang telah disetujui, sistem akan menampilkan pesan peringatan merah.
   - Jika jumlah tamu melebihi kapasitas maksimal ruangan, sistem akan memberikan peringatan kapasitas.

6. **Langkah 5: Unggah Lembar Disposisi / Nota Dinas (Opsional)**:
   - Lampirkan file disposisi/nota dinas sebagai dokumen pendukung.
   - Format yang diterima: **PDF, JPG, JPEG, PNG** (Ukuran Maksimal: **5MB**).

7. Klik tombol **Kirim Pengajuan Pemesanan**.

> 🖼️ **[TANGKAPAN LAYAR 2.5: Notifikasi Sukses Pengajuan Pemesanan]**
> *Sisipkan gambar tangkapan layar pesan sukses pemesanan di bawah ini:*
> `![Notifikasi Sukses Pemesanan](images/manual/07_pemesanan_sukses.png)`

---

### **2.3 Memantau Status & Riwayat Pemesanan**
1. Buka menu **Riwayat** pada sidebar.
2. Daftar pengajuan ditampilkan dalam bentuk tabel/kartu dengan label indikator status:
   - <span style="color:#d97706;font-weight:bold;">🟡 PENDING</span>: Menunggu tinjauan dan persetujuan Admin.
   - <span style="color:#16a34a;font-weight:bold;">🟢 DISETUJUI</span>: Telah disetujui Admin, ruangan terkonfirmasi.
   - <span style="color:#dc2626;font-weight:bold;">🔴 DITOLAK</span>: Pengajuan ditolak Admin (dapat dilihat alasan penolakannya).
   - <span style="color:#64748b;font-weight:bold;">⚪ DIBATALKAN</span>: Pengajuan telah dibatalkan oleh pengguna.

> 🖼️ **[TANGKAPAN LAYAR 2.6: Halaman Daftar Riwayat Pemesanan & Status Badge]**
> *Sisipkan gambar tangkapan layar Halaman Riwayat di bawah ini:*
> `![Riwayat Pemesanan SILAKAN](images/manual/08_riwayat_pemesanan.png)`

3. Klik tombol **Detail** (<i class="bi bi-eye"></i>) pada baris pengajuan untuk membuka rincian lengkap informasi pemesanan.

> 🖼️ **[TANGKAPAN LAYAR 2.7: Halaman Detail Pemesanan Ruangan]**
> *Sisipkan gambar tangkapan layar Detail Pemesanan di bawah ini:*
> `![Detail Pemesanan](images/manual/09_detail_pemesanan.png)`

---

### **2.4 Pembatalan Pemesanan Ruangan**
1. Buka menu **Riwayat** $\rightarrow$ Klik **Detail** pada pemesanan yang bersangkutan.
2. Gulir ke bagian bawah halaman rincian $\rightarrow$ Klik tombol **Batalkan Pemesanan**.
3. Konfirmasi dialog pembatalan. Status otomatis berubah menjadi *Dibatalkan* dan jadwal ruangan kembali terbuka untuk pemesanan lain.

> 🖼️ **[TANGKAPAN LAYAR 2.8: Dialog Konfirmasi Pembatalan Pemesanan]**
> *Sisipkan gambar tangkapan layar Konfirmasi Pembatalan di bawah ini:*
> `![Pembatalan Pemesanan](images/manual/10_batal_pemesanan.png)`

---

### **2.5 Mengelola Notifikasi & Fitur Hapus Pesan**
1. Klik ikon lonceng (<i class="bi bi-bell"></i>) pada header kanan atas atau buka menu **Notifikasi**.
2. **Membaca Notifikasi**: Klik pada kartu notifikasi untuk langsung menuju ke halaman detail pemesanan terkait.
3. **Menghapus Notifikasi Satuan**: Klik ikon tempat sampah (<i class="bi bi-trash-fill"></i>) di sebelah kanan notifikasi yang ingin dihapus.
4. **Menghapus Semua Notifikasi**: Klik tombol **Hapus Semua** di bagian atas untuk membersihkan seluruh riwayat pesan notifikasi.

> 🖼️ **[TANGKAPAN LAYAR 2.9: Halaman Daftar Notifikasi & Tombol Hapus]**
> *Sisipkan gambar tangkapan layar Halaman Notifikasi & Fitur Hapus di bawah ini:*
> `![Halaman Notifikasi](images/manual/11_halaman_notifikasi.png)`

---

### **2.6 Mengelola Profil & Pengaturan Keamanan Password**
1. Klik menu **Profil Saya** di sidebar atau klik foto profil di kanan atas.
2. **Informasi Akun**: Pengguna dapat memperbarui **Alamat Email** dan **Nomor WhatsApp**.
3. **Keamanan & Password**: Masukkan *Password Saat Ini*, *Password Baru* (Min. 8 Karakter), dan *Konfirmasi Password Baru*, lalu klik **Perbarui Password**.

> 🖼️ **[TANGKAPAN LAYAR 2.10: Halaman Pengaturan Profil Saya & Password]**
> *Sisipkan gambar tangkapan layar Halaman Profil di bawah ini:*
> `![Profil Saya & Keamanan](images/manual/12_halaman_profil.png)`

---

## 📌 **BAB III: PANDUAN ADMINISTRATOR (ADMIN)**

### **3.1 Dashboard Analytics Administrator & Diagram Stat**
Admin disajikan dashboard eksekutif berbasis grafik interaktif:
- **Tren Pemesanan 6 Bulan Terakhir** (Line Chart).
- **Ruangan Terpopuler** (Bar Chart).
- **Distribusi Pemakaian per Unit Kerja** (Donut / Bar Chart).
- List *Waiting List Approval* & *Kegiatan Sedang Berlangsung*.

> 🖼️ **[TANGKAPAN LAYAR 3.1: Dashboard Analytics Administrator & Grafik]**
> *Sisipkan gambar tangkapan layar Dashboard Admin di bawah ini:*
> `![Dashboard Admin Analytics](images/manual/13_dashboard_admin.png)`

---

### **3.2 Alur Persetujuan (Approval & Rejection) Pemesanan**
1. Buka menu **Waiting List** pada sidebar Admin.
2. Klik tombol **Review Approval** pada pengajuan yang masuk.

> 🖼️ **[TANGKAPAN LAYAR 3.2: Halaman Waiting List Approval Admin]**
> *Sisipkan gambar tangkapan layar Waiting List Approval di bawah ini:*
> `![Waiting List Approval](images/manual/14_waiting_list.png)`

3. Periksa kesesuaian berkas disposisi, kapasitas, dan jadwal.
4. **Untuk Menyetujui**: Klik tombol hijau **Setujui Pemesanan**. System otomatis mengirim pesan WA & notifikasi ke pengguna.
5. **Untuk Menolak**: Klik tombol merah **Tolak Pemesanan**, ketikkan alasan penolakan pada kolom yang tersedia, lalu klik **Kirim Penolakan**.

> 🖼️ **[TANGKAPAN LAYAR 3.3: Form Input Alasan Penolakan Pemesanan]**
> *Sisipkan gambar tangkapan layar Modal Penolakan Pemesanan di bawah ini:*
> `![Penolakan Pemesanan](images/manual/15_form_penolakan.png)`

---

### **3.3 Pemantauan Kegiatan Berlangsung (Live Monitoring) & Countdown Timer**
1. Buka menu **Kegiatan Berlangsung** pada sidebar Admin.
2. Menampilkan kartu ruangan rapat yang sedang aktif digunakan saat ini.
3. Fitur **Live Countdown Timer** menghitung sisa waktu rapat secara mundur (*real-time*).

> 🖼️ **[TANGKAPAN LAYAR 3.4: Halaman Live Monitoring & Countdown Timer]**
> *Sisipkan gambar tangkapan layar Kegiatan Berlangsung di bawah ini:*
> `![Live Monitoring Rapat](images/manual/16_live_monitoring.png)`

---

### **3.4 Kelola Master Data Ruangan & Centang Rounded Badge Layout**
1. Buka menu **Master Data** $\rightarrow$ **Data Ruangan**.

> 🖼️ **[TANGKAPAN LAYAR 3.5: Daftar Master Data Ruangan Rapat]**
> *Sisipkan gambar tangkapan layar Master Ruangan di bawah ini:*
> `![Master Data Ruangan](images/manual/17_master_ruangan.png)`

2. **Menambah / Mengedit Ruangan**:
   - Isi Nama Ruangan, Lokasi, Kapasitas, dan Status (*Aktif/Nonaktif*).
   - Pada bagian **Layout yang Tersedia**, centang layout yang dapat diterapkan di ruangan ini.
   - Pilihan layout dilengkapi ikon centang bulat biru BI (<i class="bi bi-check-lg"></i>) yang interaktif.

> 🖼️ **[TANGKAPAN LAYAR 3.6: Form Input Ruangan & Custom Checkmark Layout]**
> *Sisipkan gambar tangkapan layar Form Ruangan & Checkbox Layout di bawah ini:*
> `![Form Input Ruangan & Layout](images/manual/18_form_ruangan_layout.png)`

---

### **3.5 Kelola Master Data Layout Ruangan**
1. Buka menu **Master Data** $\rightarrow$ **Data Layout**.
2. Admin dapat membuat master nama layout baru (misal: *U-Shape, Classroom, Teater, Interview Set, Transit Room, Round Table*).

> 🖼️ **[TANGKAPAN LAYAR 3.7: Halaman Master Data Layout Ruangan]**
> *Sisipkan gambar tangkapan layar Master Layout di bawah ini:*
> `![Master Data Layout](images/manual/19_master_layout.png)`

---

### **3.6 Kelola Master Data Hari Libur & Sync API Cuti Bersama**
1. Buka menu **Master Data** $\rightarrow$ **Hari Libur**.
2. **Sinkronisasi Otomatis**: Klik **Sync API Libur Nasional** untuk menarik data hari libur resmi dari API pemerintah.
3. **Tambah Manual**: Tambahkan Hari Libur Internal BI / Cuti Bersama secara manual.

> 🖼️ **[TANGKAPAN LAYAR 3.8: Halaman Hari Libur & Tombol Sync API]**
> *Sisipkan gambar tangkapan layar Halaman Hari Libur di bawah ini:*
> `![Master Hari Libur](images/manual/20_hari_libur.png)`

---

### **3.7 Manajemen User & Proteksi Keamanan Self-Delete**
1. Buka menu **Master Data** $\rightarrow$ **Data User**.
2. Admin dapat menambah akun unit baru, mengedit data user, dan memilih Role (*Admin/User*).
3. **Fitur Proteksi Akun Active**: Admin yang sedang login dilarang keras oleh sistem untuk menghapus akunnya sendiri guna menjaga kestabilan sesi.

> 🖼️ **[TANGKAPAN LAYAR 3.9: Halaman Manajemen User & Proteksi Akun]**
> *Sisipkan gambar tangkapan layar Manajemen User di bawah ini:*
> `![Manajemen User SILAKAN](images/manual/21_manajemen_user.png)`

---

### **3.8 Laporan Rekapitulasi, Ekspor Excel & Pratinjau Cetak PDF BI**
1. Buka menu **Sistem** $\rightarrow$ **Laporan**.
2. Tentukan **Rentang Tanggal**, **Ruangan**, **Unit Kerja**, atau **Status**.

> 🖼️ **[TANGKAPAN LAYAR 3.10: Halaman Filter Laporan & Tombol Ekspor]**
> *Sisipkan gambar tangkapan layar Filter Laporan di bawah ini:*
> `![Halaman Filter Laporan](images/manual/22_laporan_filter.png)`

3. **Ekspor Excel**: Klik **Export Excel** untuk mengunduh dokumen `.xlsx`.
4. **Cetak PDF**: Klik **Cetak Laporan / PDF** untuk membuka pratinjau siap cetak ber-KOP resmi Bank Indonesia KPwBI Prov. Sulut.

> 🖼️ **[TANGKAPAN LAYAR 3.11: Dokumen Cetak Laporan PDF ber-KOP Resmi BI]**
> *Sisipkan gambar tangkapan layar Pratinjau Cetak PDF di bawah ini:*
> `![Pratinjau Cetak Laporan PDF](images/manual/23_cetak_laporan_pdf.png)`

---

### **3.9 Audit Log System (Rekam Jejak Aktivitas Terpusat)**
1. Buka menu **Sistem** $\rightarrow$ **Audit Log**.
2. Memantau riwayat setiap tindakan user/admin (Aksi, Modul, Alamat IP, Keterangan, dan Waktu).

> 🖼️ **[TANGKAPAN LAYAR 3.12: Halaman Audit Log System]**
> *Sisipkan gambar tangkapan layar Halaman Audit Log di bawah ini:*
> `![Audit Log System](images/manual/24_audit_log.png)`

---

## 📌 **BAB IV: PANDUAN TAMPILAN TV MONITOR LOBBY (KIOSK MODE)**

### **4.1 Mengakses Mode Layar TV Lobby (`/display`)**
1. Pada Komputer / Smart TV Lobby Kantor, jalankan browser dan akses alamat:
   `http://localhost:8000/display`
2. Layar akan otomatis beradaptasi dengan mode tampilan gelap (*Dark Mode Kiosk*) ber-KOP Bank Indonesia.

> 🖼️ **[TANGKAPAN LAYAR 4.1: Tampilan Monitor TV Lobby Kiosk Mode]**
> *Sisipkan gambar tangkapan layar Display TV Lobby di bawah ini:*
> `![Tampilan TV Lobby Display](images/manual/25_tv_display.png)`

### **4.2 Pembaruan Otomatis Latar Belakang (Auto Refresh Real-Time)**
Sistem TV Lobby secara otomatis mengambil pembaruan data via API JSON latar belakang setiap 30 detik tanpa perlu di-refresh manual.

---

## 📌 **BAB V: TROUBLESHOOTING & FREQUENTLY ASKED QUESTIONS (FAQ)**

### **5.1 FAQ Pemesanan, Bentrok & Layout Ruangan**

- **Q1: Kenapa saat memilih Ruangan Bunaken opsi layout tidak bisa dicentang/dipilih?**
  - *Jawab*: Ruangan Bunaken disetting tidak memiliki layout khusus. Dropdown secara otomatis dan benar menampilkan keterangan `-- Tidak ada layout khusus untuk ruangan ini --`.
- **Q2: Mengapa jam pengajuan saya tidak dapat disimpan?**
  - *Jawab*: Sistem mendeteksi adanya jadwal rapat lain yang sudah disetujui pada jam & ruangan yang sama.
- **Q3: Bagaimana jika jumlah tamu lebih besar dari kapasitas ruangan?**
  - *Jawab*: Sistem akan menolak pengajuan dan menampilkan pesan batas kapasitas maksimal ruangan.

---

### **5.2 Penanganan Kendala Teknis & Layanan Bantuan**
Jika mengalami masalah sistem atau membutuhkan reset password akun unit, hubungi:
- **Tim Administrator SILAKAN**: Unit Rumah Tangga / Sekpr KPwBI Prov. Sulut.
- **Lokasi Kantor**: Gedung Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara, Manado.
