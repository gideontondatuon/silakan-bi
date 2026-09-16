# SILAKAN - Backend API (NestJS + Prisma)

Migrasi Backend SILAKAN (Sistem Informasi Layanan dan Peminjaman Ruangan BI Sulut) dari Laravel ke **NestJS** & **Prisma ORM**.

---

## 🚀 Fitur yang Dimigrasikan dari Laravel

| Modul | Endpoint Prefix | Deskripsi | Status |
|-------|-----------------|-----------|--------|
| **Auth** | `/api/auth` | Login JWT, Profile Update, Password Update, Logout | ✅ Selesai |
| **Ruangan** | `/api/ruangan`, `/api/admin/ruangan` | CRUD Ruangan & Layout Ruangan | ✅ Selesai |
| **Pemesanan** | `/api/pemesanan` | Pemesanan ruangan, cek konflik jadwal, batalkan, selesai awal | ✅ Selesai |
| **Admin Approval** | `/api/admin/approval` | Approve/reject pemesanan, filter status, WhatsApp auto-notify | ✅ Selesai |
| **Admin Users** | `/api/admin/users` | Manajemen pengguna, role ADMIN/USER | ✅ Selesai |
| **Dashboard** | `/api/dashboard`, `/api/kegiatan-berlangsung` | Statistik user & admin, status kegiatan hari ini | ✅ Selesai |
| **Display Kiosk** | `/api/display-data` | Data display jadwal ruangan & pengumuman layar TV/Kiosk | ✅ Selesai |
| **Kalender** | `/api/kalender` | Event jadwal ruangan format Kalender | ✅ Selesai |
| **Laporan** | `/api/admin/laporan` | Laporan pemesanan & Export Excel (.xlsx) | ✅ Selesai |
| **Hari Libur** | `/api/admin/hari-libur` | CRUD Hari Libur & Kalender Nasional | ✅ Selesai |
| **Audit Log** | `/api/admin/audit-log` | Riwayat aktivitas dan log sistem | ✅ Selesai |
| **WhatsApp Gateway** | *Internal Service* | Integrasi gateway Fonnte untuk notifikasi otomatis | ✅ Selesai |

---

## 🛠️ Persyaratan Lingkungan

1. **Node.js**: v18+ atau v20+ / v24+
2. **Database**: MySQL / MariaDB (XAMPP, Laragon, atau MySQL Server standalone)
3. **Database Name**: `silakan` (menggunakan database yang sama persis dengan Laravel)

---

## ⚙️ Konfigurasi `.env`

File `.env` terletak di folder `server/.env`:

```env
APP_NAME="SILAKAN | KPwBI Prov. Sulut"
NODE_ENV=development
PORT=3001

# Database (Gunakan akun MySQL Anda)
DATABASE_URL="mysql://root:@127.0.0.1:3306/silakan"

# JWT Auth
JWT_SECRET="silakan-nestjs-secret-kpwbi-sulut-2026"
JWT_EXPIRES_IN="7d"

# WhatsApp Gateway (Fonnte)
WA_GATEWAY_ENABLED=true
WA_GATEWAY_TOKEN=qgbWr6TesXwK7UepJici
WA_GATEWAY_URL=https://api.fonnte.com/send
ADMIN_WA_NUMBER=081340693458

# Timezone
TZ=Asia/Makassar
```

---

## 💻 Cara Menjalankan

### 1. Masuk ke direktori server
```bash
cd server
```

### 2. Generate Prisma Client (jika skema diubah)
```bash
npx prisma generate
```

### 3. Jalankan Mode Development (Watch Mode)
```bash
npm run start:dev
```
Server akan berjalan di `http://localhost:3001/api`.

### 4. Build untuk Production
```bash
npm run build
npm run start:prod
```

---

## 🌐 Koneksi dengan Frontend React (Vite)

Di file `vite.config.js` root, proxy sudah disetting otomatis meneruskan request `/api` ke port 3001:

```javascript
server: {
    proxy: {
        '/api': {
            target: 'http://localhost:3001',
            changeOrigin: true,
        },
    },
}
```

Cukup jalankan frontend seperti biasa di folder utama:
```bash
npm run dev
```
Maka frontend React akan langsung berkomunikasi dengan Backend NestJS.
