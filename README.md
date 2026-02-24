# SATU DATU SANGGUL

**Portal Satu Pintu RSUD Datu Sanggul**

SATU DATU SANGGUL adalah aplikasi portal dashboard satu pintu untuk RSUD Datu Sanggul di Kabupaten Tapin. Aplikasi ini dibuat untuk memudahkan pengunjung (pasien/keluarga) dan pegawai mengakses berbagai layanan digital rumah sakit tanpa harus menghafal banyak URL.

---

## Fitur Utama

### Untuk Pengunjung (Layanan Publik)
- **Pendaftaran Online** - Akses langsung ke antrean poliklinik dan Mobile JKN
- **Cek Ketersediaan Kamar** - Informasi real-time ketersediaan tempat tidur
- **Jadwal Dokter** - Info praktik dokter spesialis
- **Layanan IGD 24 Jam** - Informasi layanan gawat darurat
- **Hasil Laboratorium & Radiologi** - Akses hasil pemeriksaan

### Untuk Pegawai (Layanan Internal)
- **SIMRS Khanza** - Sistem manajemen rumah sakit
- **E-Kinerja** - Portal laporan kinerja harian
- **Absensi** - Portal kehadiran pegawai
- **E-Arsip/Tata Usaha** - Digitalisasi surat menyurat
- **Manajemen Farmasi** - Sistem logistik obat
- **Sistem Keuangan** - Portal keuangan RSUD

### Fitur Tambahan
- 🔍 **Search Bar** - Cari layanan dengan cepat
- 🌙 **Dark Mode** - Mode gelap untuk kenyamanan mata
- 📱 **PWA Ready** - Bisa diinstall di smartphone
- 🔒 **Password Protection** - Proteksi untuk layanan internal
- 🎯 **Tab Navigation** - Navigasi cepat antara publik dan internal

---

## Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Frontend | HTML5, Tailwind CSS (CDN) |
| Interaktivitas | Alpine.js |
| Backend API | PHP (PDO) |
| Database | MySQL/MariaDB |
| Icons | Heroicons (SVG inline) |

---

## Instalasi

### Prasyarat
- Web server (Apache/Nginx) dengan PHP 7.4+
- MySQL/MariaDB (untuk integrasi data real-time)
- Akses ke database SIMRS (opsional)

### Langkah Instalasi

1. **Clone atau download proyek**
   ```bash
   cd /var/www/html
   git clone <repository-url> satu
   cd satu
   ```

2. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   nano .env
   ```

   Edit file `.env`:
   ```env
   # Database Configuration
   DB_HOST=localhost
   DB_NAME=simrs
   DB_USER=root
   DB_PASS=password

   # Enable Database Integration
   USE_DATABASE=true

   # Internal Services Password
   INTERNAL_PASSWORD=ganti_password_anda
   ```

3. **Setup Permission**
   ```bash
   chmod -R 755 .
   chmod -R 777 api/  # Pastikan API dapat write jika diperlukan
   ```

4. **Generate App Icons**

   Cara 1: Menggunakan icon-placeholder.html
   ```bash
   # Buka file berikut di browser
   assets/img/icon-placeholder.html
   # Download semua icon yang tergenerate
   ```

   Cara 2: Menggunakan ImageMagick
   ```bash
   cd assets/img
   ./generate_icons.sh
   ```

   Cara 3: Manual
   - Buka `assets/img/icon.svg` di editor gambar
   - Export ke ukuran: 72, 96, 128, 144, 152, 192, 384, 512 px

5. **Test di Browser**
   - Buka `http://localhost/satu` atau `http://domain-anda`
   - Pastikan semua fitur berfungsi

---

## API Endpoints

### 1. Authentication API

**Endpoint:** `POST /api/auth.php`

**Request:**
```json
{
  "password": "rahasia123"
}
```

**Response (Success):**
```json
{
  "success": true,
  "token": "abc123...",
  "expires_at": "2025-01-01T12:00:00Z"
}
```

**Response (Error):**
```json
{
  "success": false,
  "message": "Password salah"
}
```

---

### 2. Kamar API

**Endpoint:** `GET /api/kamar.php`

**Response:**
```json
{
  "kelas_vip": {
    "terisi": 5,
    "kosong": 2,
    "total": 7
  },
  "kelas_1": {
    "terisi": 15,
    "kosong": 3,
    "total": 18
  },
  "kelas_2": {
    "terisi": 25,
    "kosong": 5,
    "total": 30
  },
  "kelas_3": {
    "terisi": 40,
    "kosong": 10,
    "total": 50
  }
}
```

---

### 3. Jadwal Dokter API

**Endpoint:** `GET /api/jadwal.php`

**Response:**
```json
[
  {
    "nama": "dr. H. Ahmad Fauzi, Sp.PD",
    "spesialis": "Penyakit Dalam",
    "jadwal": "Senin-Kamis 08:00-14:00",
    "poli": "Poli Penyakit Dalam"
  },
  ...
]
```

---

## Integrasi Database SIMRS

Untuk menghubungkan dengan database SIMRS yang sebenarnya, lakukan:

1. **Buat koneksi database** di file `.env`:
   ```env
   DB_HOST=localhost
   DB_NAME=simrs
   DB_USER=simrs_user
   DB_PASS=simrs_password
   USE_DATABASE=true
   ```

2. **Sesuaikan query** di file API:
   - `api/kamar.php` - Sesuaikan query untuk tabel kamar
   - `api/jadwal.php` - Sesuaikan query untuk tabel jadwal dokter

3. **Test endpoint API**:
   ```bash
   curl http://localhost/satu/api/kamar.php
   curl http://localhost/satu/api/jadwal.php
   ```

---

## Struktur Proyek

```
satu/
├── index.html              # Halaman utama
├── manifest.json          # PWA manifest
├── service-worker.js      # Service worker untuk offline
├── robots.txt             # SEO
├── README.md              # Dokumentasi ini
├── .env                   # Environment variables (buat dari .env.example)
├── .env.example           # Contoh konfigurasi environment
├── api/                   # API endpoints
│   ├── .htaccess          # Security & CORS
│   ├── auth.php           # Authentication
│   ├── kamar.php          # Data ketersediaan kamar
│   └── jadwal.php         # Data jadwal dokter
└── assets/
    └── img/               # Icon dan gambar
        ├── icon.svg       # Source icon SVG
        ├── icon-192.png   # App icon 192x192
        ├── icon-512.png   # App icon 512x512
        ├── favicon.png   # Favicon
        ├── generate_icons.sh  # Script generate icon
        └── icon-placeholder.html # HTML untuk generate icon
```

---

## PWA Installation

### Android (Chrome)
1. Buka aplikasi di Chrome
2. Tap menu (tiga titik) → "Add to Home Screen"
3. Ikuti instruksi

### iOS (Safari)
1. Buka aplikasi di Safari
2. Tap share icon → "Add to Home Screen"
3. Tap "Add"

---

## Deployment

### Local Development
```bash
# Jalankan PHP built-in server
php -S localhost:8000
```

### Production (Apache/Nginx)

**Apache:**
Pastikan mod_rewrite diaktifkan:
```bash
a2enmod rewrite
systemctl restart apache2
```

**Nginx:**
Konfigurasi untuk PWA:
```nginx
server {
    listen 80;
    server_name satu.rsuddatusanggul.go.id;
    root /var/www/satu;
    index index.html;

    location / {
        try_files $uri $uri/ /index.html;
    }

    location /api/ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### HTTPS (Required for PWA)
Gunakan Let's Encrypt:
```bash
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d satu.rsuddatusanggul.go.id
```

---

## Troubleshooting

### API tidak berfungsi
- Pastikan PHP berjalan: `php -v`
- Cek error log: `tail -f /var/log/apache2/error.log`
- Pastikan permission file API benar: `chmod 644 api/*.php`

### Service Worker tidak terdaftar
- Pastikan HTTPS (atau localhost)
- Cek browser console untuk error
- Clear cache dan reload

### Icons tidak muncul
- Generate ulang icons menggunakan `icon-placeholder.html`
- Pastikan file icon ada di `assets/img/`
- Cek path di `manifest.json`

### Dark mode tidak tersimpan
- Pastikan localStorage aktif di browser
- Cek browser tidak dalam mode private

---

## Security Notes

1. **Ganti password default** di `.env`
2. **Jangan commit `.env`** ke repository
3. **Gunakan HTTPS** untuk production
4. **Rate limiting** untuk endpoint auth (perlu implementasi)
5. **Sanitize input** di API (sudah implementasi PDO prepared statements)

---

## Future Enhancements

- [ ] Login pegawai dengan SSO
- [ ] Push notification untuk jadwal dokter
- [ ] Integrasi dengan sistem antrean real-time
- [ ] Multi-language support (Indonesia/Inggris)
- [ ] Analytics dashboard untuk admin
- [ ] Feedback form dari pengunjung

---

## Support

Untuk pertanyaan atau kendala, hubungi:
- Tim IT RSUD Datu Sanggul
- Email: it@rsuddatusanggul.go.id

---

## License

Copyright (c) 2025 RSUD Dokter H. Andi Abdurrahman Noor (Datu Sanggul). All rights reserved.

---

*Dibuat dengan ❤️ untuk masyarakat Tapin*
