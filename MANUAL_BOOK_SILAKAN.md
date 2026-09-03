# 📘 MANUAL BOOK SILAKAN — Versi 1.0
## **Sistem Informasi Layanan Kantor**
### **Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara**

---

**Nama Dokumen** : Buku Panduan Penggunaan Sistem (Manual Book) — SILAKAN  
**Versi Aplikasi** : Versi 1.0  
**Tanggal Rilis** : September 2026  
**Instansi Pemilik** : Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara  
**Pengelola Dokumen** : Tim Administrator SILAKAN  
**Format Dokumen Resmi** : `Manual_Book_SILAKAN_v1.0.pdf` (A4 Portrait — 14 Halaman)  
**Teks Catatan Kaki (Footer)** : `Manual Book Silakan v.1.0 - Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara`

---

## 📑 **DAFTAR ISI**

### **Bagian Awal**
- [Pendahuluan & Gambaran Umum Sistem](#bagian-awal-pendahuluan) — *Halaman 3*

### **BAB 1: Panduan Administrator**
- [1.1 Login Administrator](#11-login-administrator) — *Halaman 4*
- [1.2 Dashboard Analitik Administrator](#12-dashboard-analitik-administrator) — *Halaman 4*
- [1.3 Manajemen Persetujuan (Approval) & Penolakan Pemesanan](#13-manajemen-persetujuan-approval--penolakan-pemesanan) — *Halaman 5*
- [1.4 Live Monitoring Kegiatan Berlangsung (Early Finish)](#14-live-monitoring-kegiatan-berlangsung-early-finish) — *Halaman 6*
- [1.5 Kalender Jadwal Ruangan](#15-kalender-jadwal-ruangan) — *Halaman 6*
- [1.6 Pengelolaan Master Data Ruangan (9 Ruangan Resmi)](#16-pengelolaan-master-data-ruangan-9-ruangan-resmi) — *Halaman 7*
- [1.7 Pengelolaan Master Layout Ruangan](#17-pengelolaan-master-layout-ruangan) — *Halaman 7*
- [1.8 Master Hari Libur & Sinkronisasi API Otomatis](#18-master-hari-libur--sinkronisasi-api-otomatis) — *Halaman 8*
- [1.9 Manajemen Akun Pengguna (10 Unit Kerja Resmi)](#19-manajemen-akun-pengguna-10-unit-kerja-resmi) — *Halaman 8*
- [1.10 Laporan Pemesanan & Ekspor Data (Excel & PDF)](#110-laporan-pemesanan--ekspor-data-excel--pdf) — *Halaman 9*
- [1.11 Profil Administrator & Logout](#111-profil-administrator--logout) — *Halaman 9*

### **BAB 2: Panduan Pengguna (User — Unit Kerja)**
- [2.1 Login Akun Unit Kerja (tmi_kpwbisulut)](#21-login-akun-unit-kerja-tmi_kpwbisulut) — *Halaman 10*
- [2.2 Dashboard Pengguna (Halaman Utama)](#22-dashboard-pengguna-halaman-utama) — *Halaman 10*
- [2.3 Melihat Ketersediaan Ruangan](#23-melihat-ketersediaan-ruangan) — *Halaman 10*
- [2.4 Mengisi Formulir Pemesanan Ruangan & Layout Dinamis](#24-mengisi-formulir-pemesanan-ruangan--layout-dinamis) — *Halaman 11*
- [2.5 Ketentuan Berkas Disposisi & Popup Validasi 5 MB](#25-ketentuan-berkas-disposisi--popup-validasi-5-mb) — *Halaman 12*
- [2.6 Riwayat Pemesanan & Pemantauan Status](#26-riwayat-pemesanan--pemantauan-status) — *Halaman 12*
- [2.7 Melihat Detail Pemesanan & Unduh Disposisi](#27-melihat-detail-pemesanan--unduh-disposisi) — *Halaman 13*
- [2.8 Membatalkan Pemesanan Ruangan](#28-membatalkan-pemesanan-ruangan) — *Halaman 13*
- [2.9 Notifikasi Otomatis WhatsApp Gateway](#29-notifikasi-otomatis-whatsapp-gateway) — *Halaman 13*
- [2.10 Profil Pengguna & Logout](#210-profil-pengguna--logout) — *Halaman 13*

### **BAB 3: Informasi Tambahan**
- [3.1 Tips & Prosedur Penggunaan Sistem](#31-tips--prosedur-penggunaan-sistem) — *Halaman 14*
- [3.2 Layanan Bantuan & Kontak Dukungan Admin](#32-layanan-bantuan--kontak-dukungan-admin) — *Halaman 14*

---

## 📌 **BAGIAN AWAL: PENDAHULUAN**
### **Pendahuluan & Gambaran Umum Sistem**

#### **1. Apa Itu Sistem SILAKAN?**
**SILAKAN (Sistem Informasi Layanan Kantor)** adalah aplikasi resmi berbasis website internal yang dikembangkan khusus untuk Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara guna memfasilitasi seluruh proses pemesanan, verifikasi persetujuan, hingga pemantauan penggunaan ruangan rapat secara transparan, terpadu, dan otomatis.

#### **2. Tujuan Sistem**
- **Eliminasi Bentrok Jadwal**: Sistem memvalidasi ketersediaan secara otomatis dan menolak pemesanan pada jam yang sama secara real-time.
- **Efisiensi Verifikasi**: Mempercepat alur persetujuan lembar disposisi pimpinan secara digital tanpa berkas fisik yang tercecer.
- **Notifikasi Otomatis**: Informasi persetujuan langsung dikirimkan ke nomor WhatsApp PIC unit kegiatan.
- **Transparansi Pemakaian**: Seluruh pegawai dapat melihat ketersediaan ruangan melalui kalender visual interaktif.

#### **3. Pengguna Sistem (Hak Akses)**
1. **ADMINISTRATOR (Admin)**:
   Mengelola persetujuan permohonan, memantau rapat berlangsung, mengelola 9 master ruangan rapat, master layout, master hari libur, akun 10 unit kerja, dan mengunduh laporan eksekutif (Excel & PDF).
2. **USER (Pegawai Unit Kerja)**:
   Mengecek ketersediaan jadwal, membuat permohonan peminjaman ruangan, melampirkan lembar disposisi (maks. 5 MB), memantau status persetujuan, mengunduh disposisi, dan membatalkan pemesanan.

#### **4. Standar Operasional & Waktu Resmi**
Seluruh pengisian jam kegiatan, penghitungan durasi, dan validasi bentrok jadwal menggunakan acuan **Waktu Indonesia Tengah (WITA / GMT+8)**.

---

# 🏛️ **BAB 1: PANDUAN ADMINISTRATOR**
*(Garis pembatas hanya digunakan pada judul bab utama di atas)*

### **1.1 Login Administrator**
**Fungsi:**  
Digunakan oleh tim Administrator untuk mengautentikasi hak akses penuh dalam mengelola seluruh sistem pemesanan ruangan SILAKAN.

**Cara Menggunakan:**
1. Buka peramban web (Google Chrome / Microsoft Edge) dan akses alamat sistem SILAKAN.
2. Masukkan username Administrator (`admin`) atau alamat email resmi.
3. Masukkan kata sandi akun Admin (klik ikon mata untuk memastikan ketikan benar).
4. Klik tombol biru **Masuk**.

### **1.2 Dashboard Analitik Administrator**
**Fungsi:**  
Menampilkan ringkasan data operasional secara visual, meliputi total pemesanan, permohonan pending yang perlu ditindaklanjuti, dan pemantauan kegiatan aktif hari ini.

**Cara Menggunakan:**
1. Masuk ke halaman utama setelah login.
2. Periksa **Kartu Indikator**: Total Pemesanan, Menunggu Persetujuan (Pending), Disetujui, dan Kegiatan Hari Ini.
3. Klik tombol aksi cepat atau menu navigasi sebelah kiri untuk memproses pengajuan masuk.

### **1.3 Manajemen Persetujuan (Approval) & Penolakan Pemesanan**
**Fungsi:**  
Memeriksa keabsahan lembar disposisi pimpinan, kesesuaian kapasitas ruangan, dan memutuskan apakah pengajuan pemesanan disetujui atau ditolak dengan alasan resmi.

**Cara Menyetujui Pemesanan:**
1. Pilih menu **Persetujuan / Approval** pada navigasi sidebar.
2. Pilih baris pemesanan berstatus **Pending**, lalu klik tombol **Tinjau / Review**.
3. Periksa rincian kegiatan, kapasitas, waktu WITA, dan buka lampiran lembar disposisi.
4. Jika sesuai, klik tombol hijau **Setujui Pemesanan**. Notifikasi konfirmasi akan otomatis dikirimkan ke WhatsApp pemohon.

**Cara Menolak Pemesanan:**
1. Pada halaman peninjauan pemesanan, klik tombol merah **Tolak Pemesanan**.
2. Jendela modal popup konfirmasi penolakan akan terbuka.
3. Wajib ketikkan **Alasan Penolakan** secara jelas (contoh: *"Ruangan digunakan untuk kegiatan mendadak Pimpinan"* atau *"Disposisi belum ditandatangani"*).
4. Klik **Ya, Tolak Permohonan**. Alasan tersebut otomatis tersimpan dan dikirimkan ke WhatsApp PIC pemohon.

### **1.4 Live Monitoring Kegiatan Berlangsung (Early Finish)**
**Fungsi:**  
Memantau kegiatan rapat yang sedang aktif saat ini secara real-time serta menyediakan fitur untuk menyelesaikan penggunaan ruangan lebih awal agar ruangan dapat segera dipakai kembali.

**Cara Menggunakan:**
1. Buka menu **Kegiatan Berlangsung / Monitoring**.
2. Lihat daftar ruangan yang sedang berstatus **Berlangsung** beserta sisa durasi waktu rapat.
3. Jika rapat selesai lebih cepat dari jadwal semula, klik tombol **Selesaikan Sekarang (Early Finish)** untuk mengosongkan status ruangan seketika.

### **1.5 Kalender Jadwal Ruangan**
**Fungsi:**  
Menyajikan visualisasi matriks agenda rapat kantor secara bulanan dan mingguan untuk mempermudah pengecekan ketersediaan seluruh ruangan rapat.

**Cara Menggunakan:**
1. Pilih menu **Kalender Ruangan** di sidebar.
2. Pilih filter ruangan tertentu atau biarkan menampilkan seluruh ruangan.
3. Klik pada balok agenda kegiatan untuk melihat rincian nama unit peminjam, jam rapat, dan nama PIC unit.

### **1.6 Pengelolaan Master Data Ruangan (9 Ruangan Resmi)**
**Fungsi:**  
Menambah ruangan baru, memperbarui kapasitas, mengatur lokasi lantai gedung, dan mengaktifkan/menonaktifkan ruangan yang sedang dalam masa perawatan (*maintenance*).

**9 Ruangan Rapat Resmi KPwBI Sulut:**
1. **Tondano** (Kapasitas: 300 orang — Lantai 3)
2. **Klabat** (Kapasitas: 70 orang — Lantai 4)
3. **Bunaken** (Kapasitas: 23 orang — Lantai 2)
4. **Tomohon** (Kapasitas: 23 orang — Lantai 3)
5. **Karangetang Besar** (Kapasitas: 53 orang — Lantai 4)
6. **Karangetang Kecil** (Kapasitas: 25 orang — Lantai 4)
7. **Linow 1** (Kapasitas: 7 orang — Lantai 1)
8. **Linow 2** (Kapasitas: 8 orang — Lantai 1)
9. **Lokon** (Kapasitas: 20 orang — Lantai 1)

**Cara Menggunakan:**
1. Buka menu **Master Data > Data Ruangan**.
2. Klik tombol **+ Tambah Ruangan** untuk menambah ruangan baru, atau klik **Edit** pada ruangan yang ingin diubah.
3. Tentukan nama ruangan, kapasitas maksimum, lokasi lantai, dan centang layout yang didukung.

### **1.7 Pengelolaan Master Layout Ruangan**
**Fungsi:**  
Mengelola standar tata letak meja dan kursi rapat (misalnya *U-Shape, Classroom, Teater, Interview Set, Transit Room, Round Table*) beserta kapasitas rekomendasi.

**Cara Menggunakan:**
1. Buka menu **Master Data > Layout Ruangan**.
2. Periksa daftar model tata letak yang tersedia di sistem.
3. Tambahkan atau sesuaikan kapasitas layout bila ada pengaturan fasilitas baru dari tim teknisi.

### **1.8 Master Hari Libur & Sinkronisasi API Otomatis**
**Fungsi:**  
Menetapkan kalender libur nasional dan cuti bersama agar sistem otomatis memblokir pemesanan ruangan pada hari libur resmi.

**Cara Menggunakan:**
1. Buka menu **Master Data > Hari Libur**.
2. Klik tombol hijau **Sinkronisasi Hari Libur** untuk memperbarui daftar libur nasional secara otomatis dari server API resmi.
3. Klik tombol **+ Tambah Manual** bila terdapat hari libur lokal atau cuti khusus intern kantor.

### **1.9 Manajemen Akun Pengguna (10 Unit Kerja Resmi)**
**Fungsi:**  
Mengelola akun 10 Unit Kerja resmi KPwBI Sulut, melakukan reset kata sandi, serta melihat dan menyalin kata sandi unit kerja dengan aman.

**Daftar 10 Akun Unit Kerja:**
- `fiksp_kpwbisulut` (Fungsi Implementasi Kebijakan Sistem Pembayaran)
- `pur_kpwbisulut` (Pengelolaan Uang Rupiah)
- `tmi_kpwbisulut` (Tim Manajemen Internal)
- `uk_kpwbisulut` (Unit Kehumasan)
- `fdsek_kpwbisulut` (Fungsi Data dan Statistik Ekonomi & Keuangan)
- `fpkp_kpwbisulut` (Fasilitator Pengendalian Inflasi dan Kebijakan Publik)
- `fppu_kpwbisulut` (Fungsi Pelaksana Pengembangan UMKM)
- `pipebi_kpwbisulut` (Persatuan Istri Pegawai Bank Indonesia)
- `ppbi_kpwbisulut` (Persatuan Pegawai Bank Indonesia)
- `ca_kpwbisulut` (Change Agent)

**Cara Menggunakan:**
1. Buka menu **Data User** pada navigasi sidebar.
2. Pada kolom *Password Akun*, kata sandi tersamarkan secara aman (`••••••••`).
3. Klik **Ikon Mata (Lihat Sandi)** untuk menampilkan teks kata sandi akun unit.
4. Klik **Ikon Salin (Clipboard)** untuk menyalin kata sandi langsung ke clipboard.
5. Klik tombol **Edit** untuk mengubah informasi nama unit atau mengatur ulang password baru.

### **1.10 Laporan Pemesanan & Ekspor Data (Excel & PDF)**
**Fungsi:**  
Menyajikan rekapitulasi penggunaan ruangan rapat berdasarkan rentang tanggal, status, atau unit kerja, serta menyediakan unduhan data dalam format spreadsheet Excel dan dokumen cetak PDF resmi.

**Cara Menggunakan:**
1. Buka menu **Laporan** pada sidebar.
2. Tentukan rentang **Tanggal Mulai** dan **Tanggal Selesai**.
3. Pilih filter ruangan atau biarkan *Semua Ruangan*, lalu klik **Tampilkan Data**.
4. Klik tombol hijau **Ekspor Excel (.xlsx)** untuk mengunduh rekapitulasi data tabular secara instan tanpa hambatan loading.
5. Klik tombol merah **Cetak PDF** untuk mencetak dokumen laporan resmi berformat A4.

### **1.11 Profil Administrator & Logout**
**Fungsi:**  
Memperbarui informasi profil admin, mengganti password akun admin, dan keluar dari sistem dengan aman.

**Cara Menggunakan:**
1. Klik nama profil admin di pojok kanan atas, lalu pilih **Profil Saya** untuk mengubah data diri atau kata sandi.
2. Untuk keluar dari sesi, klik tombol **Logout** di pojok kanan atas atau bagian bawah sidebar.

---

# 👤 **BAB 2: PANDUAN PENGGUNA (USER — UNIT KERJA)**
*(Garis pembatas hanya digunakan pada judul bab utama di atas)*

### **2.1 Login Akun Unit Kerja (tmi_kpwbisulut)**
**Fungsi:**  
Digunakan oleh pegawai unit kerja untuk masuk ke SILAKAN menggunakan akun resmi masing-masing fungsi/unit di KPwBI Sulut (seperti Tim Manajemen Internal / `tmi_kpwbisulut`).

**Cara Menggunakan:**
1. Buka aplikasi SILAKAN pada peramban web.
2. Masukkan username unit kerja resmi Anda (contoh: `tmi_kpwbisulut`).
3. Ketikkan kata sandi unit kerja Anda, lalu klik tombol **Masuk**.

### **2.2 Dashboard Pengguna (Halaman Utama)**
**Fungsi:**  
Menampilkan ringkasan status permohonan rapat milik unit kerja Anda, agenda rapat aktif hari ini, dan tombol pintasan pengajuan pemesanan baru.

**Cara Menggunakan:**
1. Setelah login, periksa kartu status pemesanan: *Menunggu Persetujuan*, *Disetujui*, dan *Selesai*.
2. Pantau jadwal rapat aktif pada bagian **Kegiatan Hari Ini** (misalnya *Rapat Koordinasi Manajemen Internal*).
3. Klik tombol biru **+ Buat Pemesanan Baru** untuk mulai mengajukan peminjaman ruangan.

### **2.3 Melihat Ketersediaan Ruangan**
**Fungsi:**  
Melihat jadwal pemakaian ruangan secara visual sebelum mengajukan permohonan agar terhindar dari bentrok jadwal.

**Cara Menggunakan:**
1. Klik menu **Kalender Ruangan** pada sidebar.
2. Periksa tanggal dan jam rapat yang diinginkan.
3. Pastikan tidak ada blok agenda rapat berstatus *Disetujui* pada ruangan dan jam yang hendak Anda gunakan.

### **2.4 Mengisi Formulir Pemesanan Ruangan & Layout Dinamis**
**Fungsi:**  
Mengajukan permohonan peminjaman fasilitas ruangan rapat dengan mengisi rincian kegiatan secara lengkap dan benar.

**Panduan Kolom Formulir:**
- **Pilihan Ruangan** *(Wajib)*: Pilih salah satu dari 9 ruangan rapat KPwBI Sulut.
- **Pilihan Layout** *(Opsional)*: Pilihan tata letak meja-kursi (U-Shape, Classroom, Teater, Round Table). Pilihan otomatis menyesuaikan dengan fisik ruangan yang dipilih.
- **Tanggal Kegiatan** *(Wajib)*: Tanggal pelaksanaan rapat (tidak boleh hari libur nasional).
- **Waktu Mulai & Selesai** *(Wajib)*: Jam dalam zona **WITA**. Sistem otomatis menolak jika bentrok atau waktu mulai sudah terlewat.
- **Nama & Jenis PIC** *(Wajib)*: Nama penanggung jawab kegiatan unit (otomatis nama unit kerja pemohon) dan kategori (Organik / Non Organik).
- **Nomor WhatsApp PIC** *(Wajib)*: Nomor WA aktif (contoh: `08123456789`) untuk menerima notifikasi otomatis.
- **Jumlah Tamu** *(Wajib)*: Jumlah peserta (tidak boleh melebihi kapasitas maksimum ruangan).
- **Upload Disposisi** *(Wajib)*: Unggah berkas lembar disposisi / nota dinas (format PDF/JPG/PNG, maks. 5 MB).

**Cara Mengajukan:**
1. Isi seluruh kolom formulir sesuai panduan di atas.
2. Klik tombol **Kirim Pengajuan Pemesanan**.
3. Permohonan Anda akan tercatat dengan status **Pending** dan diteruskan ke Admin.

### **2.5 Ketentuan Berkas Disposisi & Popup Validasi 5 MB**
**Fungsi:**  
Menjamin berkas yang diunggah memenuhi standar sistem. Jika berkas yang dipilih melebihi 5 MB, sistem secara otomatis langsung menampilkan jendela popup peringatan seketika saat berkas dipilih.

**Cara Kerja & Penanganan:**
1. Klik area dropzone unggah atau seret berkas (*drag and drop*) lembar disposisi Anda.
2. Jika ukuran berkas **lebih dari 5 MB**, sistem langsung membatalkan pilihan berkas dan memunculkan modal popup:
   > ⚠️ **Ukuran Berkas Melebihi Batas**  
   > *Ukuran berkas lembar disposisi yang Anda pilih adalah 6.85 MB. Sesuai ketentuan sistem, ukuran berkas tidak boleh lebih dari 5 MB.*
3. Klik tombol **Mengerti** pada popup, kompres berkas PDF/gambar Anda, lalu unggah kembali berkas dengan ukuran di bawah 5 MB.

### **2.6 Riwayat Pemesanan & Pemantauan Status**
**Fungsi:**  
Melihat seluruh daftar pengajuan yang pernah diajukan unit kerja beserta tahapan status persetujuannya.

**Arti Label Status Pemesanan:**
- **Pending (Kuning)**: Permohonan berhasil dikirim dan sedang menunggu pemeriksaan Admin.
- **Disetujui (Hijau)**: Pemesanan disetujui resmi dan ruangan siap dipergunakan.
- **Ditolak (Merah)**: Permohonan ditolak oleh Admin (alasan penolakan dapat dibaca pada detail).
- **Berlangsung (Biru)**: Kegiatan rapat saat ini sedang aktif berlangsung di ruangan.
- **Selesai (Abu-abu)**: Kegiatan rapat telah selesai dilaksanakan.
- **Dibatalkan (Abu-abu Gelap)**: Pemesanan dibatalkan atas permohonan pemohon sendiri.

### **2.7 Melihat Detail Pemesanan & Unduh Disposisi**
**Fungsi:**  
Memeriksa seluruh informasi lengkap pengajuan dan mengunduh berkas lembar disposisi yang telah diunggah sebelumnya.

**Cara Menggunakan:**
1. Buka menu **Riwayat Pemesanan**.
2. Klik tombol **Detail** pada pengajuan yang dipilih.
3. Rincian tanggal, jam, ruangan, PIC unit, dan status tampil lengkap. Klik tautan **Unduh Berkas** untuk mengunduh kembali dokumen disposisi.

### **2.8 Membatalkan Pemesanan Ruangan**
**Fungsi:**  
Membatalkan pemesanan yang tidak jadi diselenggarakan agar jadwal ruangan kembali terbuka untuk unit kerja lain.

**Cara Menggunakan:**
1. Buka halaman Detail Pemesanan yang ingin dibatalkan (berstatus *Pending* atau *Disetujui*).
2. Klik tombol merah **Batalkan Pemesanan**.
3. Ketikkan alasan pembatalan singkat, lalu konfirmasi. Status pemesanan akan berubah menjadi **Dibatalkan**.

### **2.9 Notifikasi Otomatis WhatsApp Gateway**
**Fungsi:**  
Memberikan kepastian status persetujuan secara instan langsung ke ponsel PIC kegiatan tanpa harus selalu membuka website.

**Cara Kerja:**
1. Setiap kali Admin menyetujui atau menolak permohonan Anda, server SILAKAN otomatis mengirimkan pesan WhatsApp ke nomor PIC yang tertera.
2. Pesan berisi rincian ruangan, tanggal, jam WITA, dan catatan alasan jika ditolak.

### **2.10 Profil Pengguna & Logout**
**Fungsi:**  
Mengubah kata sandi akun unit kerja dan keluar dari aplikasi secara aman.

**Cara Menggunakan:**
1. Klik nama akun di pojok kanan atas, lalu pilih **Profil Saya** jika ingin mengganti kata sandi.
2. Klik tombol **Logout** untuk keluar dari sesi akun setelah selesai menggunakan sistem.

---

# 💡 **BAB 3: INFORMASI TAMBAHAN**
*(Garis pembatas hanya digunakan pada judul bab utama di atas)*

### **3.1 Tips & Prosedur Penggunaan Sistem**
- **1. Ketentuan Berkas Lembar Disposisi (Maksimal 5 MB):** Pastikan format berkas didukung (PDF, JPG, JPEG, atau PNG).
- **2. Acuan Zona Waktu Operasional (WITA / GMT+8):** Seluruh jam pemesanan mengacu pada waktu WITA.
- **3. Saluran Konfirmasi WhatsApp Gateway:** Pastikan nomor WhatsApp PIC ditulis dengan benar dan dalam kondisi aktif.
- **4. Etika Pemakaian & Penyelesaian Rapat Lebih Awal (*Early Finish*):** Selesaikan ruangan melalui sistem jika rapat selesai lebih awal.

### **3.2 Layanan Bantuan & Kontak Dukungan Admin**
Bila mengalami kendala teknis dalam pengoperasian sistem pemesanan ruangan SILAKAN, hubungi:
- **Pengelola Sistem**: Tim Administrator SILAKAN KPwBI Provinsi Sulawesi Utara
- **Lokasi**: Gedung Kantor Perwakilan Bank Indonesia Prov. Sulawesi Utara — Lantai 3
- **Kontak Internal**: Ext. Telepon Admin SILAKAN / Email: `admin_silakan@bi.go.id`

---
*Manual Book Silakan v.1.0 - Kantor Perwakilan Bank Indonesia Provinsi Sulawesi Utara*
