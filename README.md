<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Inventory TKJ

Inventory TKJ adalah aplikasi manajemen inventaris berbasis web untuk kebutuhan laboratorium atau sekolah kejuruan, dibangun menggunakan Laravel. Aplikasi ini memudahkan pengelolaan alat, peminjaman, pengembalian, serta monitoring data pengguna dan laporan secara profesional.

## Fitur Utama
- Manajemen data alat (tambah, edit, hapus, lihat detail)
- Peminjaman & pengembalian alat
- Dashboard statistik untuk admin & siswa
- Manajemen user (admin & siswa)
- Laporan peminjaman
- Responsive & modern UI (sidebar sticky, header sticky, card, tabel, dsb)
- Hak akses terpisah untuk admin dan siswa

## Instalasi
1. Clone repository:
   ```bash
   git clone https://github.com/username/inventory-tkj.git
   cd inventory-tkj
   ```
2. Install dependency PHP & JS:
   ```bash
   composer install
   npm install && npm run build
   ```
3. Copy file environment:
   ```bash
   cp .env.example .env
   ```
4. Generate key & migrate database:
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```
5. Jalankan server lokal:
   ```bash
   php artisan serve
   ```

## Struktur Folder Penting
- `app/Models` — Model Eloquent (Alat, Peminjaman, User, dsb)
- `app/Http/Controllers` — Controller utama aplikasi
- `resources/views` — Blade template (layouts, dashboard, auth, alat, peminjaman, dsb)
- `routes/web.php` — Routing utama aplikasi
- `database/migrations` — Struktur tabel database
- `database/seeders` — Seeder data awal (admin, dsb)

## Kontribusi
Kontribusi sangat terbuka! Silakan fork repo ini, buat branch baru, dan ajukan pull request.

## Lisensi
Aplikasi ini menggunakan [MIT License](https://opensource.org/licenses/MIT).

---

> Dibangun dengan Laravel & Bootstrap. Untuk kebutuhan laboratorium, sekolah, atau organisasi yang membutuhkan sistem inventarisasi alat yang modern dan mudah digunakan.
