# Product Manager

Aplikasi web **manajemen produk** berbasis PHP dan MySQL untuk mini project tugas akhir. Aplikasi menyediakan fitur tambah, lihat, ubah, dan hapus produk (CRUD), dengan validasi input dan perlindungan keamanan dasar.

## Tujuan

Menerapkan konsep pengembangan aplikasi web PHP–MySQL, meliputi:

- Pengelolaan data produk melalui operasi CRUD.
- Penggunaan PDO prepared statements untuk query database.
- Validasi input di sisi server dan pencegahan nama produk duplikat.
- Perlindungan output HTML serta request yang mengubah data.
- Antarmuka katalog produk yang responsif.

## Persyaratan

| Komponen | Persyaratan |
|---|---|
| PHP | 8.1 atau lebih baru |
| Database | MySQL 8+ atau MariaDB 10.5+ |
| Browser | Chrome, Firefox, atau Edge versi terbaru |

Ekstensi PHP `pdo_mysql` harus aktif.

## Fitur

| Fitur | Keterangan |
|---|---|
| Lihat produk | Katalog berbentuk kartu yang menampilkan nama, kategori, harga, dan stok |
| Tambah produk | Form pembuatan produk dengan validasi server-side |
| Edit produk | Form terisi dengan data produk yang dipilih |
| Hapus produk | Menggunakan request POST, token CSRF, dan konfirmasi |
| Cari dan filter | Cari produk berdasarkan nama/kategori dan filter kategori |
| Validasi | Nama minimal 3 karakter dan unik; kategori wajib; harga > 0; stok bilangan bulat ≥ 0 |
| Antarmuka | Responsif, berbahasa Indonesia, dengan latar gelap dan aksen merah |

## Struktur Proyek

```text
product-manager/
├── public/
│   ├── index.php                   # Entry point dan routing
│   └── assets/
│       ├── css/style.css           # Styling antarmuka
│       └── js/app.js               # Interaksi UI, pencarian/filter, konfirmasi hapus
├── app/
│   ├── config/config.php           # Konfigurasi aplikasi dan database
│   ├── database/connection.php     # Koneksi PDO
│   ├── helpers.php                 # Helper escape, redirect, flash, CSRF, format harga
│   ├── validators.php              # Validasi produk
│   ├── controllers/                # Alur request aplikasi
│   ├── models/Product.php          # Operasi database produk
│   └── views/                      # Template halaman
├── database/schema.sql             # Skema database
├── .env.example                    # Contoh konfigurasi koneksi
├── .gitignore
└── README.md
```

## Cara Menjalankan di Windows dengan XAMPP

### 1. Aktifkan layanan

Buka **XAMPP Control Panel**, lalu klik **Start** pada **MySQL**. Untuk menggunakan phpMyAdmin, aktifkan **Apache** juga.

### 2. Impor skema database

1. Buka `http://localhost/phpmyadmin`.
2. Pilih menu **Import**.
3. Pilih file `database/schema.sql` dari folder proyek.
4. Klik **Import** atau **Go**.
5. Pastikan database bernama `product_manager` berhasil dibuat.

Jika file SQL tidak membuat database secara otomatis, buat database bernama `product_manager` di phpMyAdmin terlebih dahulu, pilih database tersebut, lalu impor `schema.sql`.

### 3. Atur koneksi database

Di folder utama proyek, salin `.env.example` menjadi `.env`:

```powershell
Copy-Item .env.example .env
```

Edit `.env` agar sesuai dengan konfigurasi MySQL lokal:

```dotenv
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=product_manager
DB_USER=root
DB_PASS=
```

Pada instalasi XAMPP standar, user MySQL biasanya `root` dengan password kosong (`DB_PASS=`). Jika kamu telah mengatur password MySQL, isi `DB_PASS` dengan password tersebut. Jangan unggah atau commit `.env` yang berisi kredensial pribadi.

### 4. Jalankan aplikasi

Buka PowerShell di folder proyek yang berisi folder `public`, `app`, dan `database`. Jika folder proyek bernama `product-manager`, contoh perintahnya:

```powershell
cd "C:\Users\<NamaUser>\Documents\Tugas Product Manager\product-manager"
C:\xampp\php\php.exe -S localhost:8080 -t public
```

Jika perintah `php` sudah tersedia di PATH Windows, kamu juga dapat menjalankan:

```powershell
php -S localhost:8080 -t public
```

Setelah PowerShell menampilkan pesan bahwa server dimulai, buka browser ke:

```text
http://localhost:8080
```

**Biarkan jendela PowerShell tetap terbuka** selama aplikasi digunakan. Untuk menghentikan server, tekan `Ctrl+C` di jendela PowerShell.

> Pastikan perintah dijalankan dari folder proyek yang memiliki folder `public`. Jika muncul `Directory public does not exist`, masuk ke folder proyek yang benar terlebih dahulu.

## Keamanan dan Aturan Validasi

- **PDO prepared statements:** query database memakai parameter terikat untuk membantu mencegah SQL injection.
- **Validasi server-side:** nama wajib dan minimal 3 karakter; kategori wajib; harga harus lebih dari 0; stok harus berupa bilangan bulat minimal 0.
- **Nama unik:** duplikasi diperiksa oleh aplikasi dan dibatasi dengan indeks `UNIQUE` pada database. Nama yang sama dengan kapitalisasi berbeda mengikuti collation database.
- **Escaping output:** data dinamis di-escape saat ditampilkan menggunakan `htmlspecialchars($value, ENT_QUOTES, 'UTF-8')` untuk membantu mencegah XSS.
- **CSRF dan metode request:** operasi yang mengubah data menggunakan POST dan token CSRF. Penghapusan tidak dilakukan melalui GET.
- **Post/Redirect/Get (PRG):** setelah form POST diproses, aplikasi melakukan redirect agar refresh tidak mengirim ulang form.
- **Kredensial:** simpan konfigurasi lokal di `.env`; jangan gunakan konfigurasi pengembangan sebagai pengaturan produksi.


## Catatan Implementasi

- Harga disimpan sebagai `DECIMAL(12,2)` agar sesuai untuk data uang dan menghindari masalah presisi `float`.
- Database menggunakan `utf8mb4` untuk mendukung karakter Unicode.
- Fitur login, unggah gambar, multi-user, dan role pengguna tidak termasuk dalam ruang lingkup mini project ini.

---

**Catatan:** Aplikasi ini dibuat untuk pembelajaran dan penggunaan lokal. Sebelum dipublikasikan, tinjau kembali konfigurasi server, kredensial database, dan pengaturan keamanan produksi.
