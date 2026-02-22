# Rencana Pengembangan: SATU DATU SANGGUL
## Portal Satu Pintu RSUD Datu Sanggul

---

## Konteks

SATU DATU SANGGUL adalah aplikasi portal dashboard satu pintu untuk RSUD Dokter H. Andi Abdurrahman Noor (Datu Sanggul) di Kabupaten Tapin. Aplikasi ini dibuat untuk memudahkan pengunjung (pasien/keluarga) dan pegawai mengakses berbagai layanan digital rumah sakit tanpa harus menghafal banyak URL.

**Masalah yang diselesaikan:**
- Sulitnya mengakses layanan digital RSUD tersebar di berbagai platform
- Pengunjung dan pegawai harus menghafal multiple URL untuk berbagai layanan
- Tidak ada hub terpadu untuk mengakses informasi jadwal, ketersediaan kamar, dan layanan internal

**Tujuan:**
- Satu pintu akses untuk semua layanan digital RSUD
- Navigasi cepat dan mudah dengan pencarian
- Responsif untuk berbagai perangkat (desktop dan mobile)
- PWA-ready untuk instalasi di HP

---

## Arsitektur Teknis

### Teknologi yang Digunakan

| Komponen | Teknologi | Alasan |
|----------|-----------|--------|
| Frontend | HTML5 | Standar web modern, semantik |
| Styling | Tailwind CSS | Modern, responsif, ringan |
| Interaktivitas | Alpine.js | Ringan, mudah dipelajari, deklaratif |
| Backend API | PHP (PDO) / Node.js / Python | Sesuai environment server RSUD |
| Database | MySQL/MariaDB | Sesuai database SIMRS |
| Icons | Heroicons / Phosphor | SVG ringan, customizable |

### Arsitektur API

```
Frontend (index.html + Alpine.js)
        ↓
API Endpoint (api/*.php)
        ↓
Database SIMRS
```

**Endpoints yang dibutuhkan:**

1. `POST /api/auth.php` - Verifikasi password untuk akses Internal
   ```json
   Request: { "password": "xxxxx" }
   Response: { "success": true, "token": "xxx" }
   ```

2. `GET /api/kamar.php` - Data ketersediaan kamar
   ```json
   Response: {
     "kelas_vip": { "terisi": 5, "kosong": 2 },
     "kelas_1": { "terisi": 15, "kosong": 3 },
     ...
   }
   ```

3. `GET /api/jadwal.php` - Data jadwal dokter
   ```json
   Response: [
     { "nama": "dr. Andi", "spesialis": "Penyakit Dalam", "jadwal": "Senin-Jumat 08:00-14:00" },
     ...
   ]
   ```

### Struktur Proyek

```
satu/
├── index.html              # Halaman utama
├── assets/
│   ├── css/               # Tailwind output (opsional)
│   ├── js/                # Alpine.js + custom script
│   └── img/               # Logo, favicon
├── api/
│   ├── auth.php           # Endpoint auth
│   ├── kamar.php          # Endpoint kamar
│   └── jadwal.php         # Endpoint jadwal dokter
├── manifest.json          # PWA manifest
├── service-worker.js      # PWA offline support
├── robots.txt             # SEO
├── .env                   # Environment variables
└── README.md              # Dokumentasi
```

---

## Fitur Utama

### 1. Navigasi Berbasis Tab
- **Layanan Publik** (default) - untuk pengunjung
- **Layanan Internal** - untuk pegawai (dengan proteksi password)

### 2. Komponen Layanan

#### Layanan Publik
| Layanan | Deskripsi | Aksi |
|---------|-----------|------|
| Pendaftaran Online | Link ke antrean poliklinik/Mobile JKN | Buka di tab baru |
| Cek Kamar | Informasi ketersediaan bed (Database SIMRS) | Integrasi API local |
| Jadwal Dokter | Info praktik dokter spesialis real-time (Database SIMRS) | Integrasi API local |

#### Layanan Internal
| Layanan | Deskripsi | Aksi |
|---------|-----------|------|
| SIMRS Khanza | Akses sistem manajemen pasien | Buka di tab baru |
| E-Kinerja | Portal laporan harian | Buka di tab baru |
| Absensi | Portal kehadiran | Buka di tab baru |
| E-Arsip/TU | Digitalisasi surat menyurat | Buka di tab baru |

### 3. Fitur Tambahan
- **Search Bar**: Filter layanan berdasarkan nama/kata kunci
- **New Tab Redirect**: Semua link membuka di tab baru dengan `target="_blank"`
- **Dark Mode Toggle**: Mode gelap untuk kenyamanan mata
- **Quick Access**: Pinned services di halaman utama
- **Password Protection**: Authentication untuk akses Layanan Internal
- **API Integration**: Fetch data dari API local untuk Cek Kamar dan Jadwal Dokter

---

## Langkah Implementasi

### Fase 1: Setup Proyek
1. Inisialisasi struktur folder
2. Setup Tailwind CSS (via CDN untuk kecepatan development)
3. Siapkan Alpine.js
4. Buat file dasar HTML

### Fase 2: Struktur HTML
1. Header dengan logo dan tagline
2. Tab navigation (Publik/Internal)
3. Search bar
4. Grid layout untuk kartu layanan
5. Footer dengan info RSUD

### Fase 3: Styling & Responsif
1. Implementasi desain card-based
2. Responsive grid (mobile: 1 kolom, tablet: 2 kolom, desktop: 3-4 kolom)
3. Hover effects dan transitions
4. Dark mode styles

### Fase 4: Interaktivitas (Alpine.js)
1. State management untuk tab aktif
2. Search/filter functionality
3. Dark mode toggle
4. Password protection untuk Layanan Internal
5. Loading states untuk eksternal links
6. Fetch data dari API local (Cek Kamar, Jadwal Dokter)

### Fase 5: PWA Setup
1. Buat `manifest.json` dengan metadata aplikasi
2. Implementasi service worker untuk offline support
3. Favicon dan app icons (berbagai ukuran)
4. Meta tags untuk "Add to Home Screen"

### Fase 6: Testing & Optimization
1. Test responsiveness di berbagai ukuran layar
2. Test PWA installation di Android/iOS
3. Validasi W3C HTML
4. Optimize untuk SEO

---

## Mockup Layout (Konsep Visual)

```
┌─────────────────────────────────────────────────────┐
│  [Logo]  SATU DATU SANGGUL    [🌙]  [🔍 Search]   │
│  "Satu Pintu, Semua Layanan Terbantu"             │
├─────────────────────────────────────────────────────┤
│  [Layanan Publik]  [Layanan Internal]              │
├─────────────────────────────────────────────────────┤
│                                                     │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐ │
│  │ 🏥          │  │ 🛏️          │  │ 👨‍⚕️         │ │
│  │ Pendaftaran │  │ Cek Kamar   │  │ Jadwal      │ │
│  │ Online      │  │ Tersedia    │  │ Dokter      │ │
│  └─────────────┘  └─────────────┘  └─────────────┘ │
│                                                     │
└─────────────────────────────────────────────────────┘
```

---

## File Kritis untuk Dimodifikasi/Dibuat

| File | Tipe | Deskripsi |
|------|------|-----------|
| `index.html` | Baru | Halaman utama dengan struktur lengkap |
| `api/` | Baru | Folder untuk API endpoints (PHP/Node.js/Python) |
| `api/auth.php` | Baru | Endpoint untuk password authentication |
| `api/kamar.php` | Baru | Endpoint untuk data ketersediaan kamar |
| `api/jadwal.php` | Baru | Endpoint untuk data jadwal dokter |
| `manifest.json` | Baru | PWA manifest |
| `service-worker.js` | Baru | Service worker untuk offline support |
| `tailwind.config.js` | Baru (opsional) | Konfigurasi Tailwind custom |
| `.env` | Baru | Environment variables untuk kredensial database |

---

## Verifikasi

### Functional Testing
- [ ] Tab Publik/Internal berfungsi
- [ ] Password protection untuk Layanan Internal berfungsi
- [ ] Search memfilter kartu layanan dengan benar
- [ ] Data Cek Kamar terambil dari API dengan benar
- [ ] Data Jadwal Dokter terambil dari API dengan benar
- [ ] Semua link membuka di tab baru
- [ ] Dark mode toggle berfungsi
- [ ] Kartu responsif di mobile/tablet/desktop

### API Testing
- [ ] Auth endpoint mengembalikan token/session yang benar
- [ ] Kamar endpoint mengembalikan data real-time
- [ ] Jadwal dokter endpoint mengembalikan data valid
- [ ] Error handling untuk kegagalan koneksi API

### PWA Testing
- [ ] Manifest terdeteksi dengan benar
- [ ] Bisa diinstall di HP (Android/iOS)
- [ ] App icon muncul di home screen
- [ ] Service worker terdaftar

### Cross-browser Testing
- [ ] Chrome/Edge (Chromium)
- [ ] Firefox
- [ ] Safari (iOS/Mac)

---

## Clarification dari User

### Integrasi Data
- **Cek Kamar & Jadwal Dokter**: Data akan diambil dari database SIMRS melalui API local untuk keamanan

### Keamanan Layanan Internal
- **Proteksi Password**: Layanan Internal hanya dapat diakses setelah autentikasi password

### Deployment
- **Local Development**: Fokus pada development lokal, domain akan ditentukan kemudian

### Tambahan yang Diperlukan
1. Logo RSUD Datu Sanggul dalam format digital (PNG/SVG)
2. Kredensial dan informasi database SIMRS untuk integrasi API
3. Password untuk akses Layanan Internal

---

## Timeline Estimasi

| Fase | Pekerjaan |
|------|-----------|
| Setup & HTML | Struktur dasar, styling awal |
| Fitur Utama | Tabs, cards, search, links |
| API Integration | Backend untuk auth, kamar, jadwal dokter |
| PWA | Manifest, service worker, icons |
| Testing & Fix | Validasi, optimasi, perbaikan bug |

Total: Implementasi dapat dilakukan dalam sesi coding terpadu. Backend API dapat dibuat menggunakan PHP (jika server web Apache/Nginx) atau Node.js/Python sesuai preferensi.

---

*Dokumen ini dibuat sebagai rencana pengembangan awal untuk aplikasi SATU DATU SANGGUL*
