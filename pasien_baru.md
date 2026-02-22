## II. Best Practice Pendaftaran Pasien Mandiri (Self-Registration)

Agar pasien bisa menginput data sendiri ke database dengan valid dan aman, berikut adalah panduannya:

### 1. Desain FormulirMulti-step Form: Form:** Pecah formulir menjadi beberapa bagian (Identitas, Kontak, Data MeReal-time Validation:ation:** * NIK wajib 16 digit.
    * Cek duplikasi NIK di database secara otomatis.
    * Gunakan *Date Picker* untuk Tanggal Lahir.

### 2. Integrasi & Keamanan (TechnOTP Verification:ation:** Kirim kode ke WhatsApp/SMS untuk memastikan nomor aHTTPS & Encryption:ption:** Enkripsi NIK di database untuk melindungi privasi paBridging BPJS/SatuSehat:Sehat:** Tarik data otomatis berdasarkan NIK jika sistem Anda sudah memiliki akses API resmi.

### 3. Skema Database Sederhana

| Field | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| patient_id | UUID | Primary Key | ID unik pasien |
| nik | VARCHAR(16) | UNIQUE | Kunci identitas utama |
| full_name | VARCHAR(100) | NOT NULL | Nama sesuai KTP |
| dob | DATE | NOT NULL | Tanggal Lahir |
| reg_status | ENUM | 'Pending', 'Verified' | Status verifikasi |

---

## III. Contoh Script Pendaftaran (Web-Based)

Gunakan logika ini untuk mengarahkan pengguna ke aplikasi atau ke form pendaftaran:

`javascript
function redirectPasien() {
    var userAgent = navigator.userAgent;
    var appScheme = "mobilejkn://";
    var start = Date.now();

    // Mencoba membuka aplikasi Mobile JKN
    window.location.href = appScheme;

    // Fallback jika aplikasi tidak terbuka dalam 2.5 detik
    setTimeout(function() {
        if (Date.now() - start < 3000) {
            // Arahkan ke Form Pendaftaran Internal jika App tidak ada
            window.location.href = "/pendaftaran-pasien-baru";
        }
    }, 2500);
}