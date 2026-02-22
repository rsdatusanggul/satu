# Dokumentasi Konsep Aplikasi: SATU DATU SANGGUL

## 1. Pendahuluan
SATU DATU SANGGUL adalah aplikasi portal satu pintu (dashboard) yang dirancang untuk RSUD Datu Sanggul. Aplikasi ini berfungsi sebagai hub sentral untuk memudahkan pengunjung dan pegawai mengakses berbagai layanan digital rumah sakit tanpa harus menghafal banyak URL.

## 2. Identitas Aplikasi
* Nama Aplikasi: SATU DATU SANGGUL
* Kepanjangan: Sistem Akses Terpadu & Unggul - RSUD Datu Sanggul
* Tagline: *"Satu Pintu, Semua Layanan Terbantu"*
* Konsep Visual: Dashboard berbasis kartu (*card-based*) yang responsif dan ringan.

## 3. Struktur Framework & Navigasi
Aplikasi dibagi menjadi dua kategori utama untuk menghindari kebingungan pengguna:

### A. Layanan Publik (Pengunjung)
Ditujukan untuk pasien dan keluarga pasien. Fokus pada kemudahan navigasi dan akses cepat.
* Pendaftaran Online: Link ke antrean poliklinik/Mobile JKN.
* Cek Kamar: Integrasi informasi ketersediaan bed (Siranap).
* Jadwal Dokter: Informasi real-time praktik dokter spesialis.

### B. Layanan Internal (Pegawai)
Ditujukan untuk mendukung produktivitas staf RSUD.
* SIMRS Khanza: Akses cepat ke sistem manajemen pasien.
* E-Kinerja & Absensi: Portal laporan harian dan kehadiran.
* E-Arsip/TU: Digitalisasi surat menyurat dan dokumen internal.

## 4. Spesifikasi Teknis (Rekomendasi)
* Frontend: HTML5, Tailwind CSS (modern & responsif).
* Interaktivitas: JavaScript Vanilla / Alpine.js (ringan).
* Fitur Utama:
    * Search Bar: Mempermudah pencarian layanan dalam hitungan detik.
    * New Tab Redirect: Menggunakan target="_blank" agar portal tetap terbuka saat aplikasi tujuan diakses.
    * PWA Ready: Dapat diinstal di HP tanpa melalui Play Store (*Add to Home Screen*).

## 5. Filosofi Nama
Pemilihan nama SATU DATU SANGGUL didasari oleh:
1.  SATU: Melambangkan integrasi (Satu Pintu) dan standar kualitas (Unggul).
2.  DATU SANGGUL: Penghormatan terhadap tokoh dan identitas lokal Kabupaten Tapin agar aplikasi terasa lebih dekat dengan masyarakat.

---
*Dokumen ini dibuat sebagai kerangka awal pengembangan aplikasi Portal RSUD Datu Sanggul.*
