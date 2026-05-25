# Laravel Restaurant POS

Sistem Kasir Restoran Modern berbasis web yang dibuat menggunakan Laravel, Tailwind CSS, dan MySQL. Website ini membantu restoran atau cafe dalam mengelola menu, transaksi kasir, pemesanan pelanggan, manajemen meja, dan laporan penjualan secara efisien.

## Fitur Utama

### Autentikasi
- Login Admin & Kasir
- Sistem Role Management
- Logout Sistem

### Dashboard
- Total Penjualan Hari Ini
- Total Pesanan
- Statistik Pendapatan
- Menu Terlaris
- Grafik Penjualan

### Manajemen Menu
- Tambah Menu
- Edit Menu
- Hapus Menu
- Upload Gambar Menu
- Kategori Menu
- Status Tersedia / Habis
- Manajemen Harga

### Sistem Kasir
- Pemesanan Menu
- Pilih Nomor Meja
- Keranjang Pesanan
- Tambah Jumlah Item
- Catatan Pesanan
- Perhitungan Total Otomatis
- Pajak & Biaya Layanan
- Input Pembayaran
- Hitung Kembalian
- Cetak Struk

### Manajemen Meja
- Status Meja Kosong / Terisi
- Pengaturan Jumlah Meja

### Riwayat Transaksi
- Detail Pesanan
- Filter Berdasarkan Tanggal
- Riwayat Pembayaran

### Laporan
- Laporan Harian
- Laporan Mingguan
- Laporan Bulanan
- Menu Paling Laris
- Export PDF & Excel

### Tampilan UI
- Responsive Design
- Dashboard Modern
- Dark Mode
- Search & Pagination
- Notifikasi Modern
- Loading Animation

---

## Teknologi yang Digunakan

### Backend
- Laravel

### Frontend
- Tailwind CSS
- JavaScript

### Database
- MySQL

### Tools Tambahan
- Laravel Breeze / Filament
- Chart.js / ApexCharts
- Heroicons / Lucide Icons

---

## Cara Install

Clone repository:

```bash
git clone https://github.com/username/laravel-restaurant-pos.git
```

Masuk ke folder project:

```bash
cd laravel-restaurant-pos
```

Install dependency:

```bash
composer install
npm install
```

Copy file environment:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Atur konfigurasi database pada file `.env`

```env
DB_DATABASE=restaurant_pos
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migrasi database:

```bash
php artisan migrate
```

Jalankan server Laravel:

```bash
php artisan serve
```

Jalankan Vite:

```bash
npm run dev
```

Buka di browser:

```txt
http://127.0.0.1:8000
```

---

## Struktur Project

```txt
app/
resources/
routes/
database/
public/
```

---

## Pengembangan Selanjutnya

- Integrasi Pemesanan Online
- QR Code Menu
- Kitchen Display System
- Multi Cabang Restoran
- Sistem Member Pelanggan
- Integrasi Mobile App

---

## Screenshot

Tambahkan screenshot project di sini.

---

## Lisensi

Project ini menggunakan lisensi MIT dan dapat digunakan untuk pembelajaran maupun pengembangan lebih lanjut.

---

## Developer

Dikembangkan oleh Ludfi Eric Fernando.
