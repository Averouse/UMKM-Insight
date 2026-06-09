# UMKM Insight - Platform Analitik Bisnis UMKM

UMKM Insight adalah platform berbasis web yang membantu pelaku UMKM memantau performa bisnis mereka melalui visualisasi data transaksi riil. Sistem ini terintegrasi dengan **3 sumber data eksternal** (simulasi): SmartBank untuk data keuangan, WarungPOS untuk penjualan kasir lokal, dan PasarKita untuk tren pasar global. Proyek ini dikembangkan menggunakan PHP Native dan MySQL.

## 🚀 Fitur Utama

- **Dashboard Analytics**: Ringkasan pendapatan, pengeluaran, dan laba bersih secara real-time dari seluruh sumber data.
- **Laporan Penjualan**: Tren harian dan histori transaksi dengan filter tier (Free vs Premium).
- **Manajemen Arus Kas**: Visualisasi cashflow masuk dan keluar secara mendalam.
- **Performa Produk**: Analisis produk terlaris WarungPOS vs tren global PasarKita.
- **Integrasi Multi-API**: Koneksi independen ke SmartBank dan WarungPOS masing-masing dengan ID & PIN terpisah.
- **Sinkronisasi Data**: Tombol sync di Dashboard menarik data terbaru dari semua API yang terhubung.
- **Sistem Operasional**: Pengajuan upgrade tier, sistem notifikasi, dan pusat bantuan.
- **Multi-Role Access**: Admin, Operator, dan Client (UMKM).
- **Dark/Light Mode**: Tema gelap dan terang yang dapat diubah secara global di seluruh halaman.

## 🛠️ Tech Stack

- **Backend**: PHP 8.x (Native, tanpa framework)
- **Database**: MySQL / MariaDB
- **Frontend**: Tailwind CSS (CDN), Phosphor Icons
- **Visualisasi**: Chart.js
- **Server Lokal**: FlyEnv / XAMPP / Laragon

---

## 🔌 Arsitektur Integrasi API

Sistem menggunakan arsitektur **simulasi API** untuk mendemonstrasikan integrasi dengan layanan eksternal. Setiap layanan memiliki ID koneksi yang independen:

```
┌──────────────┐     ┌──────────────────────┐      ┌──────────────────────┐
│  SmartBank   │────►│                      │◄──── │     WarungPOS        │
│  (Keuangan)  │     │   UMKM Insight       │      │   (Kasir Lokal)      │
│              │     │   api/sync_data.php  │      │                      │
│ smartbank_id │     │                      │      │  warungpos_id        │
└──────────────┘     │                      │      └──────────────────────┘
                     │                      │
                     │                      │◄────┌──────────────────────┐
                     │                      │     │      PasarKita       │
                     └──────────────────────┘     │  (Tren Marketplace)  │
                                                  │  Data Global/Publik  │
                                                  └──────────────────────┘
```

### Sumber Data

| Sumber | Deskripsi | ID Koneksi | PIN Demo |
| :--- | :--- | :--- | :--- |
| **SmartBank** | Data transaksi keuangan (Income/Expense) | `smartbank_id` | `123456` |
| **WarungPOS** | Data penjualan kasir lokal (produk fisik) | `warungpos_id` | `654321` |
| **PasarKita** | Tren produk marketplace secara global | Tanpa ID (data publik) | — |

### Alur Sinkronisasi

1. User menghubungkan **SmartBank ID** dan/atau **WarungPOS ID** di halaman **Profil** (`profile.php`).
2. User menekan tombol **Sinkronisasi Data** di **Dashboard** (`dashboard.php`).
3. Endpoint `api/sync_data.php` menarik data dari masing-masing sumber secara independen:
   - SmartBank → tabel `smartbank_transactions` → masuk ke `transaction_cache` (source: `SmartBank`)
   - WarungPOS → tabel `external_sales` (filter `warungpos_id`) → masuk ke `transaction_cache` (source: `WarungPOS`)
   - PasarKita → tabel `external_sales` (filter `GLOBAL`) → masuk ke `market_trends_cache`
4. Seluruh halaman statistik (Dashboard, Laporan, Performa Produk, Arus Kas) membaca dari `transaction_cache`.

> **Penting**: SmartBank dan WarungPOS bersifat **independen**. Menghubungkan salah satu saja sudah cukup untuk melakukan sinkronisasi. Tidak perlu keduanya terhubung secara bersamaan.

### Simulator Endpoint

Simulator API tersedia di folder `/simulators/` untuk keperluan pengujian:

| Endpoint | Method | Parameter | Deskripsi |
| :--- | :--- | :--- | :--- |
| `simulators/smartbank/api.php` | GET | `smartbank_id` | Ambil saldo & riwayat transaksi bank |
| `simulators/warungpos/api.php` | GET | `warungpos_id` | Ambil data penjualan kasir lokal |
| `simulators/pasarkita/api.php` | GET | — | Ambil produk trending global marketplace |

---

## 💻 Cara Install di Lokal (Setup)

Ikuti langkah-langkah berikut untuk menjalankan proyek di PC Anda:

### 1. Persiapan Server
Gunakan **FlyEnv**, **XAMPP**, atau **Laragon**.
- Pastikan layanan **Apache** dan **MySQL** sudah berjalan.
- Letakkan folder proyek ini di dalam direktori server Anda (misal: `htdocs` untuk XAMPP atau melalui modul *Hosts* di FlyEnv).

### 2. Konfigurasi Database
1. Buka browser dan akses **phpMyAdmin**.
2. Buat database baru dengan nama `umkm_insight`.
3. Impor file database utama: `dokumentasi/database.sql`.
4. Impor file patch tambahan (berurutan):
   - `dokumentasi/database_patch.sql`
   - `dokumentasi/database_patch_2.sql`

### 3. Pengaturan Koneksi
Buka file `config/db.php` dan sesuaikan kredensial database Anda jika berbeda:
```php
$host = 'localhost';
$db   = 'umkm_insight';
$user = 'root';
$pass = 'root'; // Gunakan '' jika Anda menggunakan XAMPP default
```

### 4. Jalankan Aplikasi
Akses melalui browser di alamat yang Anda tentukan (misal: `http://localhost/RPL/` atau `https://umkm.test`).

### 5. Hubungkan API (Opsional)
Setelah login sebagai client, buka halaman **Profil** untuk menghubungkan:
- **SmartBank**: Masukkan SmartBank ID → PIN: `123456`
- **WarungPOS**: Masukkan WarungPOS ID → PIN: `654321`

Lalu klik tombol **Sinkronisasi Data** di Dashboard untuk menarik data.

---

## 🔑 Akun Pengujian (Demo)

| Role | Username | Password | SmartBank ID | WarungPOS ID |
| :--- | :--- | :--- | :--- | :--- |
| **Client (Free)** | `budi` | `password` | `SB-8829-102` | `WP-8829-102` |
| **Client (Premium)** | `sari` | `password` | `SB-9938-204` | `WP-9938-204` |
| **Operator** | `op_jaya` | `password` | — | — |
| **Admin** | `admin_super` | `password` | — | — |

---

## 📂 Struktur Folder

```
RPL/
├── api/                    # Endpoint internal (sync_data.php)
├── assets/                 # File CSS, JS, dan Gambar
│   ├── css/style.css       # Desain utama (Dark/Light mode)
│   ├── js/app.js           # Logika frontend global
│   └── image/              # Aset gambar
├── config/                 # Konfigurasi database dan sistem
├── controllers/            # Logika pemrosesan data
├── dokumentasi/            # Skema database, perencanaan, dan panduan
│   ├── database.sql        # Skema utama
│   ├── database_patch.sql  # Patch tabel tambahan
│   ├── database_patch_2.sql# Patch integrasi API & data dummy
│   └── simulasi.md         # Panduan alur simulasi API
├── includes/               # Komponen UI (Header, Sidebar, Footer, Topbar)
├── models/                 # Model data
├── simulators/             # Simulator API Eksternal
│   ├── smartbank/api.php   # Endpoint SmartBank
│   ├── warungpos/api.php   # Endpoint WarungPOS
│   └── pasarkita/api.php   # Endpoint PasarKita
└── Archives/               # File prototype HTML sebelum migrasi PHP
```

---

## 📊 Skema Database Utama

| Tabel | Fungsi |
| :--- | :--- |
| `users` | Data pengguna, role, tier, `smartbank_id`, `warungpos_id` |
| `products` | Produk yang didaftarkan oleh user |
| `transaction_cache` | Cache transaksi dari semua sumber (SmartBank, WarungPOS) |
| `smartbank_accounts` | Simulasi akun bank (saldo) |
| `smartbank_transactions` | Simulasi riwayat transaksi bank |
| `external_sales` | Simulasi penjualan POS & marketplace |
| `market_trends_cache` | Cache tren produk global PasarKita |
| `notifications` | Sistem notifikasi pengguna |
| `complaints` | Sistem pengaduan/bantuan |

---
