# Sistem Informasi Manajemen Karyawan (SIMK) - Web Simulation

SIMK adalah aplikasi berbasis web yang dirancang untuk mensimulasikan sistem pendataan pegawai secara digital. Proyek ini dikembangkan menggunakan **PHP Native** dan **MySQL** sebagai bagian dari portofolio teknis untuk pendaftaran **Staff PIT (Publikasi & IT) RAJA Brawijaya 2026**.

## Deskripsi Proyek
Aplikasi ini memungkinkan admin untuk mengelola data karyawan, mulai dari autentikasi keamanan hingga pengelolaan profil lengkap dengan unggah foto. Fokus utama proyek ini adalah pada implementasi **logika backend** yang aman dan **manajemen database relasional**.

## Fitur Utama
- **Secure Authentication**: Login & Register dengan hashing password (`password_hash`) dan manajemen sesi yang aman.
- **CSRF Protection**: Implementasi token unik pada setiap form untuk mencegah serangan *Cross-Site Request Forgery*.
- **CRUD Operasi**: Manajemen data karyawan (Tambah, Baca, Update, Hapus) yang terintegrasi langsung ke database.
- **Relational Database**: Penggunaan SQL `JOIN` untuk menghubungkan data Karyawan dengan tabel Divisi dan Jabatan secara dinamis.
- **Image Handling**: Penanganan unggahan foto profil ke server lokal dengan validasi ekstensi berkas.
- **Responsive Hybrid UI**: Antarmuka modern menggunakan Bootstrap 5 dengan efek animasi *fade-in* saat halaman dimuat.

## Tech Stack
- **Bahasa:** PHP 8.x, JavaScript (ES6+)
- **Database:** MySQL / MariaDB
- **UI Framework:** Bootstrap 5.3
- **Library/API:** Intersection Observer API (untuk animasi), Bootstrap Icons.

## Struktur Repositori
- `auth.php` - Logika dan antarmuka autentikasi (Login/Register).
- `db.php` - Konfigurasi koneksi database menggunakan PDO.
- `database.php` - Kumpulan fungsi *helper* untuk kueri database.
- `index.php` - Dashboard utama dengan ringkasan statistik.
- `karyawan.php` - Form manajemen profil karyawan secara individual.
- `data-karyawan.php` - Tabel daftar seluruh karyawan dengan kueri JOIN.
- `simpan-hapus-karyawan.php` - Backend pemrosesan data (Insert/Update/Delete).
- `script.js` - Logika frontend, animasi, dan pratinjau foto.
- `uploads/` - Direktori penyimpanan berkas gambar (pastikan folder ini ada).

## Cara Menjalankan di Localhost
1. Clone repositori ini ke folder `htdocs` (XAMPP) atau `www` (Laragon).
2. Buat database baru di phpMyAdmin dengan nama `login_db`.
3. Jalankan kueri SQL yang terdapat dalam file `database_setup.sql` (atau salin dari bagian DDL di bawah).
4. Sesuaikan kredensial database di file `db.php` jika diperlukan (user default: `root`, pass: ``).
5. Buka browser dan akses: `http://localhost/[nama-folder-anda]/auth.php`.

## Catatan
Proyek ini merupakan bentuk simulasi mandiri untuk mendalami alur *backend development* dan pengelolaan data sistem informasi dalam skala kepanitiaan universitas.
