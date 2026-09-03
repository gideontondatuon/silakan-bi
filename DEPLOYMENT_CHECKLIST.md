# PANDUAN KESIAPAN & CHECKLIST DEPLOYMENT SILAKAN
### Sistem Informasi Layanan Kantor — Bank Indonesia (KPwBI Provinsi Sulawesi Utara)

Dokumen ini merupakan panduan resmi langkah-demi-langkah bagi Tim IT / Administrator Infrastruktur untuk menerapkan (deploy) aplikasi **SILAKAN** ke lingkungan server produksi (Virtual Private Server, On-Premises Server BI, atau Cloud Container).

---

## 1. Persyaratan Server (Server Requirements)

Pastikan server produksi memenuhi spesifikasi minimum berikut:

| Komponen | Spesifikasi Minimum | Rekomendasi Produksi |
|---|---|---|
| **Sistem Operasi** | Ubuntu 22.04 LTS / Debian 12 / RHEL 9 | Ubuntu 24.04 LTS 64-bit |
| **Web Server** | Nginx 1.22+ atau Apache 2.4+ | Nginx (Reverse Proxy & HTTP/2) |
| **PHP Runtime** | PHP 8.2+ | PHP 8.3 dengan PHP-FPM |
| **Database** | MySQL 8.0+ atau MariaDB 10.6+ | MariaDB 10.11 LTS |
| **Node.js** | Node.js 18.x LTS & NPM | Node.js 20.x LTS |
| **PHP Extensions** | `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `gd`, `intl`, `json`, `mbstring`, `openssl`, `pcre`, `pdo_mysql`, `tokenizer`, `xml`, `zip` | Wajib aktif (terutama `intl` untuk format tanggal ID) |
| **Memory (RAM)** | 2 GB | 4 GB+ |
| **Storage** | 20 GB SSD | 50 GB NVMe SSD |

---

## 2. Checklist Langkah Deployment Langkah-demi-Langkah

### Langkah 1: Kloning & Pengaturan Repositori
```bash
cd /var/www
git clone <URL_REPOSITORY_SILAKAN> silakan
cd silakan
```

### Langkah 2: Konfigurasi File Environment (.env)
Salin `.env.example` ke `.env`:
```bash
cp .env.example .env
nano .env
```
Sesuaikan parameter penting berikut:
```dotenv
APP_NAME="SILAKAN Bank Indonesia"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://silakan-sulut.bi.go.id  # Ganti dengan domain/IP server produksi Anda
APP_TIMEZONE=Asia/Makassar

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=silakan_bi
DB_USERNAME=user_silakan
DB_PASSWORD=PasswordKuatDatabase!

# WhatsApp Gateway Fonnte
WA_GATEWAY_ENABLED=true
WA_GATEWAY_TOKEN=token_fonnte_resmi_bi
ADMIN_WA_NUMBER=081234567890
```

Generate application key:
```bash
php artisan key:generate
```

### Langkah 3: Instalasi Dependensi & Build Aset Frontend
```bash
# Instal dependensi PHP tanpa paket development
composer install --no-dev --optimize-autoloader

# Instal dependensi frontend & kompilasi aset produksi
npm install
npm run build
```

### Langkah 4: Migrasi & Database Seeder
```bash
# Jalankan migrasi database
php artisan migrate --force

# Jalankan seeder data awal (Akun Admin & Master Ruangan/Layout)
php artisan db:seed --force
```

### Langkah 5: Storage Link & Hak Akses Direktori
```bash
# Buat symlink publik untuk unggahan lampiran disposisi
php artisan storage:link

# Berikan izin akses folder storage dan cache ke user web server (www-data)
chown -R www-data:www-data /var/www/silakan
chmod -R 775 /var/www/silakan/storage
chmod -R 775 /var/www/silakan/bootstrap/cache
```

### Langkah 6: Optimasi Cache Laravel Produksi
Jalankan perintah cache untuk performa respons aplikasi yang maksimal:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 3. Konfigurasi Web Server (Nginx)

Buat file konfigurasi `/etc/nginx/sites-available/silakan`:
```nginx
server {
    listen 80;
    server_name silakan-sulut.bi.go.id; # Sesuaikan domain Anda
    root /var/www/silakan/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php index.html;
    charset utf-8;

    # Ukuran maksimum unggahan berkas lembar disposisi (PDF/DOC)
    client_max_body_size 15M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock; # Sesuaikan versi PHP
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Aktifkan konfigurasi Nginx dan muat ulang:
```bash
ln -s /etc/nginx/sites-available/silakan /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx
```

---

## 4. Konfigurasi SSL / HTTPS (Let's Encrypt Certbot)

```bash
apt-get install -y certbot python3-certbot-nginx
certbot --nginx -d silakan-sulut.bi.go.id
```

---

## 5. Konfigurasi Task Scheduler (Cron Job)

Aplikasi SILAKAN memiliki tugas terjadwal (pemeriksaan bentrok jadwal, pembebasan ruangan otomatis, update kalender). Tambahkan ke crontab server:
```bash
crontab -e -u www-data
```
Tambahkan baris berikut di bagian akhir:
```cron
* * * * * cd /var/www/silakan && php artisan schedule:run >> /dev/null 2>&1
```

---

## 6. Konfigurasi Background Queue Worker (Systemd)

Untuk pemrosesan notifikasi pesan WhatsApp dan database background job:
Buat file `/etc/systemd/system/silakan-worker.service`:
```ini
[Unit]
Description=SILAKAN Laravel Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/silakan/artisan queue:work --sleep=3 --tries=3 --max-time=3600

[Install]
WantedBy=multi-user.target
```
Aktifkan service:
```bash
systemctl daemon-reload
systemctl enable silakan-worker
systemctl start silakan-worker
```

---

## 7. Strategi Backup Database Berkala

Buat skrip backup otomatis `/usr/local/bin/backup-silakan.sh`:
```bash
#!/bin/bash
BACKUP_DIR="/var/backups/silakan"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
mkdir -p $BACKUP_DIR
mysqldump -u user_silakan -p'PasswordKuatDatabase!' silakan_bi | gzip > "$BACKUP_DIR/silakan_$TIMESTAMP.sql.gz"
# Hapus backup yang lebih tua dari 30 hari
find $BACKUP_DIR -type f -name "*.sql.gz" -mtime +30 -exec rm {} \;
```
Jadwalkan backup setiap hari pukul 02:00 WITA:
```cron
0 2 * * * /usr/local/bin/backup-silakan.sh
```

---

## 8. Verifikasi Pasca-Deployment

- [x] Halaman login dapat diakses dan responsif (`/login`).
- [x] Admin dapat login dengan akun default (`admin` / `password`) dan segera mengganti password di Profil.
- [x] Kalender interaktif FullCalendar memuat jadwal ruangan dengan benar (`/kalender`).
- [x] Layar TV Kiosk Lobby menampilkan jadwal live dan countdown (`/display`).
- [x] Pengajuan pemesanan ruangan berhasil memvalidasi bentrok jadwal seketika.
- [x] WhatsApp Gateway merespons dan mengirim notifikasi saat pemesanan disetujui / ditolak.
- [x] Unggah dan unduh lembar disposisi berfungsi optimal.
- [x] Halaman error 403, 404, 419, dan 500 tampil resmi bernuansa Bank Indonesia.
- [x] Seluruh unit kerja kantor perwakilan telah terdaftar dan siap digunakan.
