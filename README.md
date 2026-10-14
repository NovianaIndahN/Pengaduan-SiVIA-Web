# Pengaduan-SiVIA-Web
SiVIA (Sistem Pengaduan Layanan Warga) — Web aplikasi pengaduan masyarakat berbasis PHP &amp; MySQL dengan antarmuka modern SaaS-Style, visual Elevated Glassmorphism, dan Multi-Role System (Warga, Admin, Teknisi).
##  Fitur Utama

### 1. Hak Akses Bertingkat (Multi-Role)
* **Warga**:
  * Mengajukan laporan/pengaduan baru dilengkapi dengan upload bukti foto.
  * Memantau status penanganan laporan (*Menunggu*, *Diproses*, *Selesai*) secara *real-time*.
  * Melihat riwayat seluruh pengaduan yang pernah diajukan.
* **Admin / Petugas**:
  * Mengelola dan memverifikasi laporan masuk dari warga.
  * Mengatur penetapan status pengaduan dan menugaskan teknisi.
  * Manajemen akun pengguna (Warga, Teknisi, dan Admin baru).
  * Mencetak rekapitulasi laporan pengaduan.
* **Teknisi**:
  * Menerima dan memproses daftar tugas perbaikan fasilitas yang telah diverifikasi oleh Admin.

### 2. Antarmuka & Visual (UI/UX)
* **Design System**: *Warm Cream Aesthetic & Deep Ocean Teal Accent*.
* **Responsive Sidebar**: Navigation bar dengan dukungan mode *minimize/collapse*.
* **Elevated Cards**: Tampilan statistik dan tabel data dengan efek *glassmorphism* serta bayangan terangkat (*elevated shadow*).
* **Cross-Browser Styling**: Antarmuka konsisten dan bersih di berbagai browser modern.

---

## Teknologi yang Digunakan

* **Bahasa Pemrograman**: PHP 8.1
* **Database**: MySQL / MariaDB
* **Styling & Framework UI**: 
  * Bootstrap 5.3
  * Custom CSS3 (Flexbox, CSS Variables, SVG Gradients)
  * Bootstrap Icons v1.11.1
* **Font**: Inter (Google Fonts)
* **Local Server Environment**: Laragon

## 🔑 Akun Demo / Testing Login

Untuk mencoba berbagai hak akses (*role*) di dalam aplikasi, gunakan kredensial bawaan berikut:

| Peran (Role) | Username / NIK | Password | Akses & Fitur |
| :--- | :--- | :--- | :--- |
| **Admin** | `2222222222222222` | `123` | Verifikasi laporan, kelola pengguna, cetak rekap |
| **Warga** | `1111111111111111` | `123` | Buat laporan baru, lihat riwayat pengaduan |
| **Teknisi** | `3333333333333333` | `123` | Lihat daftar tugas perbaikan |

---

## 📂 Struktur Direktori Project

```text
Pengaduan/
├── uploads/              # Folder penyimpanan foto bukti pengaduan warga
├── buat_laporan.php      # Form pengajuan laporan baru (Warga)
├── cetak.php             # Halaman rekapitulasi & cetak laporan (Admin)
├── dashboard.php         # Ringkasan statistik & laporan terbaru
├── detail.php            # Rincian detail pengaduan & tindak lanjut
├── kelola_pengguna.php   # Manajemen akun & registrasi role (Admin)
├── koneksi.php           # Konfigurasi koneksi database MySQL
├── login.php             # Halaman autentikasi masuk sistem
├── logout.php            # Proses keluar dari sistem
├── logoweb.png           # Aset logo identitas SiVIA
├── register.php          # Pendaftaran akun baru warga
├── riwayat.php           # Daftar histori pengaduan milik warga
├── tugas.php             # Daftar tugas perbaikan (Teknisi)
└── verifikasi.php        # Halaman verifikasi & validasi laporan (Admin)
