# Dokumentasi Teknis Website Company Profile Restaurant Monalisa Bogor

Dokumentasi ini menyajikan panduan arsitektur sistem, struktur basis data, tata letak kode, serta penjelasan fungsionalitas backend dan frontend untuk proyek website Monalisa Resto Bogor.

---

## 1. Dokumentasi Database

Sistem basis data Monalisa Resto menggunakan MySQL/MariaDB dengan driver PDO (PHP Data Objects) untuk menjamin keamanan dari serangan injeksi SQL (SQL Injection).

### Entity Relationship Diagram (ERD) Deskriptif
Sistem memiliki arsitektur relasional yang efisien untuk mencatat data master dan log aktivitas operasional admin:
* **Relasi users dengan log_aktivitas (1 to Many / One to Many)**:
  * Satu pengguna (admin) pada tabel `users` dapat melakukan banyak tindakan operasional yang dicatat di dalam tabel `log_aktivitas`.
  * Kunci asing `user_id` pada tabel `log_aktivitas` merujuk ke kolom utama `id` pada tabel `users` dengan aturan `ON DELETE SET NULL`. Jika akun admin dihapus, catatan riwayat log aktivitas tetap tersimpan secara historis dengan nilai pengidentifikasi kosong (NULL).
* **Tabel Mandiri (Operational Masters & Configurations)**:
  * **Tabel menu**: Menyimpan data master hidangan kuliner secara mandiri. Pengelompokan kategori dioptimalkan langsung di dalam kolom `kategori` untuk mempercepat pencarian dan pembagian data tanpa overhead join tabel tambahan.
  * **Tabel galeri**: Menyimpan kumpulan foto dokumentasi suasana interior dan eksterior restoran secara mandiri untuk kebutuhan galeri publik.
  * **Tabel konten_halaman**: Menggunakan pendekatan pasangan unik (*Unique Key*) kombinasi kolom `halaman` dan `bagian` untuk mengelola teks dinamis secara mandiri di seluruh halaman frontend.

---

### Spesifikasi Tabel Basis Data

#### A. Tabel: `users`
Tabel ini digunakan untuk mengelola data akun administrator yang memiliki hak akses penuh ke panel pengelolaan konten backend.

| Nama Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | INT(11) | PK, NOT NULL, AUTO_INCREMENT | Pengidentifikasi unik setiap administrator |
| `username` | VARCHAR(50) | UNIQUE, NOT NULL | Nama pengguna untuk login |
| `password_hash` | VARCHAR(255) | NOT NULL | Kata sandi aman hasil enkripsi bcrypt |
| `created_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu pendaftaran akun admin |

#### B. Tabel: `menu`
Tabel ini menyimpan seluruh daftar kuliner makanan dan minuman yang ditawarkan oleh restoran.

| Nama Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | INT(11) | PK, NOT NULL, AUTO_INCREMENT | Pengidentifikasi unik setiap item menu |
| `nama_menu` | VARCHAR(100) | NOT NULL | Nama masakan atau minuman |
| `asal_daerah` | VARCHAR(100) | NOT NULL | Asal daerah kuliner masakan |
| `deskripsi_singkat`| TEXT | NOT NULL | Ringkasan deskripsi untuk kartu menu frontend |
| `deskripsi_lengkap`| LONGTEXT | NOT NULL | Penjelasan detail untuk halaman rincian menu |
| `bahan_utama` | TEXT | NOT NULL | Informasi daftar bahan utama masakan |
| `info_alergen` | TEXT | NULL, DEFAULT NULL | Catatan peringatan alergen masakan |
| `kategori` | VARCHAR(50) | NOT NULL | Kategori menu (contoh: AYAM, SEAFOOD, MINUMAN) |
| `harga` | DECIMAL(10,2)| NOT NULL | Harga item menu |
| `foto_url` | VARCHAR(255) | NOT NULL | Jalur direktori penyimpanan berkas gambar menu |
| `status` | ENUM('aktif', 'nonaktif') | DEFAULT 'aktif' | Status ketersediaan menu di katalog publik |
| `created_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu pertama kali menu ditambahkan |
| `updated_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Waktu terakhir kali menu diubah |

#### C. Tabel: `galeri`
Tabel ini menyimpan daftar berkas foto dokumentasi restoran yang ditampilkan pada halaman galeri publik.

| Nama Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | INT(11) | PK, NOT NULL, AUTO_INCREMENT | Pengidentifikasi unik setiap berkas galeri |
| `judul` | VARCHAR(100) | NOT NULL | Deskripsi singkat atau judul foto |
| `foto_url` | VARCHAR(255) | NOT NULL | Jalur direktori penyimpanan berkas gambar |
| `urutan` | INT(11) | DEFAULT 0 | Nomor urutan penayangan gambar di halaman depan |
| `created_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu unggah berkas gambar |

#### D. Tabel: `konten_halaman`
Tabel ini mengelola teks dinamis berkonsep Key-Value untuk mempermudah administrator mengubah konten statis seperti sejarah, alamat, visi-misi, dan jam operasional tanpa mengubah baris kode.

| Nama Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | INT(11) | PK, NOT NULL, AUTO_INCREMENT | Pengidentifikasi unik baris konfigurasi |
| `halaman` | VARCHAR(50) | NOT NULL | Nama halaman terkait (contoh: beranda, tentang_kami) |
| `bagian` | VARCHAR(50) | NOT NULL | Identifikasi bagian spesifik (contoh: alamat, sejarah) |
| `isi` | TEXT | NOT NULL | Nilai konten teks dinamis |
| `updated_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Waktu terakhir konten diperbarui |

*Catatan: Kolom `halaman` dan `bagian` memiliki indeks `UNIQUE KEY unique_konten`.*

#### E. Tabel: `log_aktivitas`
Tabel audit trail untuk memantau aktivitas perubahan data yang dilakukan oleh para administrator.

| Nama Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | INT(11) | PK, NOT NULL, AUTO_INCREMENT | Pengidentifikasi unik rekaman log aktivitas |
| `user_id` | INT(11) | FK, NULL, DEFAULT NULL | ID akun admin pelaksana (merujuk `users.id`) |
| `aksi` | VARCHAR(255) | NOT NULL | Deskripsi ringkas aksi (contoh: Tambah menu baru) |
| `waktu` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu eksekusi aksi tersebut |

---

### Query SQL Pembuatan Tabel (CREATE TABLE)

Berikut adalah perintah SQL murni tanpa komentar untuk mendefinisikan seluruh struktur tabel basis data proyek Monalisa Resto:

```sql
CREATE TABLE users (
  id INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (id),
  UNIQUE KEY username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE menu (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nama_menu VARCHAR(100) NOT NULL,
  asal_daerah VARCHAR(100) NOT NULL,
  deskripsi_singkat TEXT NOT NULL,
  deskripsi_lengkap LONGTEXT NOT NULL,
  bahan_utama TEXT NOT NULL,
  info_alergen TEXT DEFAULT NULL,
  kategori VARCHAR(50) NOT NULL,
  harga DECIMAL(10,2) NOT NULL,
  foto_url VARCHAR(255) NOT NULL,
  status ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE galeri (
  id INT(11) NOT NULL AUTO_INCREMENT,
  judul VARCHAR(100) NOT NULL,
  foto_url VARCHAR(255) NOT NULL,
  urutan INT(11) DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE konten_halaman (
  id INT(11) NOT NULL AUTO_INCREMENT,
  halaman VARCHAR(50) NOT NULL,
  bagian VARCHAR(50) NOT NULL,
  isi TEXT NOT NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  PRIMARY KEY (id),
  UNIQUE KEY unique_konten (halaman, bagian)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE log_aktivitas (
  id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) DEFAULT NULL,
  aksi VARCHAR(255) NOT NULL,
  waktu TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (id),
  KEY user_id (user_id),
  CONSTRAINT log_aktivitas_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

---

## 2. Dokumentasi Kode

### Struktur Direktori Proyek

```
monalisa_resto/
├── .htaccess
├── database.sql
├── index.php
├── katalog.php
├── detail.php
├── galeri.php
├── kontak.php
├── tentang.php
├── api_kontak.php
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── main.js
│   └── images/
│       ├── menu/
│       └── galeri/
├── includes/
│   ├── db.php
│   ├── header.php
│   ├── footer.php
│   ├── admin_header.php
│   ├── admin_footer.php
│   └── functions.php
└── admin/
    ├── index.php
    ├── login.php
    ├── logout.php
    ├── menu.php
    ├── galeri.php
    ├── konten.php
    ├── log.php
    └── akun.php
```

---

### Penjelasan Setiap File dan Folder Utama

#### A. Direktori Root
* **`index.php`**: Beranda utama publik. Menampilkan penawaran unggulan, sambutan hangat, dan highlight hidangan terbaik secara dinamis dari database.
* **`katalog.php`**: Halaman pencarian dan penyaringan item menu restoran. Dilengkapi fitur penyaringan kategori berbasis AJAX demi performa interaksi tanpa jeda memuat ulang (*refresh*) halaman penuh.
* **`detail.php`**: Halaman yang menampilkan informasi mendalam mengenai satu item hidangan pilihan, termasuk deskripsi lengkap, daftar bahan utama, informasi alergen, dan integrasi tombol pemesanan.
* **`galeri.php`**: Halaman penayangan visual suasana fisik restoran dan hidangan estetis Monalisa Resto.
* **`kontak.php`**: Halaman informasi detail lokasi, telepon, jam operasional, serta peta interaktif OpenStreetMaps.
* **`tentang.php`**: Halaman yang mengisahkan sejarah panjang restoran Monalisa sejak tahun 1972 serta misi kenyamanan kuliner keluarga.
* **`api_kontak.php`**: Layanan endpoint JSON lokal untuk menyediakan koordinat lokasi GPS dan nomor kontak yang dibutuhkan oleh widget interaktif frontend.
* **`database.sql`**: Berkas dump SQL awal untuk instalasi skema tabel dan isian data bawaan restoran.
* **`.htaccess`**: Berkas pengamanan web server Apache. Berfungsi mematikan visualisasi eror PHP langsung ke layar publik, mengaktifkan pencatatan log eror tersembunyi, menonaktifkan directory listing (`Options -Indexes`), serta memblokir unduhan langsung berkas-berkas sensitif.

#### B. Folder `assets/`
* **`css/style.css`**: Lembar gaya tunggal kustom dengan pendekatan modern. Mengintegrasikan variabel CSS, tata letak Grid & Flexbox, transisi halus, efek *glassmorphism*, dan desain responsif skala tinggi untuk ponsel hingga desktop.
* **`js/main.js`**: Pusat pengendali interaktivitas client-side. Mengatur menu hamburger mobile, inisialisasi transisi animasi scroll (*reveal*), proses request penyaringan menu berbasis AJAX, serta kontrol kemudahan widget obrolan mengambang WhatsApp.

#### C. Folder `includes/`
* **`db.php`**: Pusat konfigurasi konektivitas database menggunakan ekstensi PDO. Menggunakan pendekatan pendeteksian variabel lingkungan (*environment variables*) lokal maupun produksi secara dinamis.
* **`header.php` / `footer.php`**: Tata letak global navigasi atas dan bawah untuk halaman publik publik. Footer memuat markup panel popup obrolan cepat WhatsApp kustom.
* **`admin_header.php` / `admin_footer.php`**: Header dan footer terisolasi khusus panel kontrol admin. Mengintegrasikan sistem pengecekan kredensial sesi (*session validation*), perlindungan session hijacking, dan tata letak panel kontrol yang terstruktur.
* **`functions.php`**: Kumpulan fungsi pembantu global seperti sanitasi enkoding `h()` untuk memproteksi elemen HTML dari serangan Cross-Site Scripting (XSS).

#### D. Folder `admin/`
* **`login.php` / `logout.php`**: Proses manajemen otentikasi masuk dan keluar sistem bagi administrator secara aman.
* **`index.php`**: Dasbor utama admin yang menampilkan ringkasan visual kuantitas menu, galeri, log tindakan terakhir, serta shortcut operasional cepat.
* **`menu.php`**: Sistem CRUD (*Create, Read, Update, Delete*) terpadu untuk hidangan masakan, lengkap dengan validasi unggah berkas foto secara lokal.
* **`galeri.php`**: Pengelolaan foto promosi restoran, proses upload gambar baru, dan penghapusan otomatis berkas fisik gambar di server saat data dihapus dari database.
* **`konten.php`**: Halaman khusus untuk memperbarui nilai teks dinamis di beranda, tentang kami, alamat, telepon, dan jam operasional.
* **`log.php`**: Halaman audit log aktivitas admin yang memaparkan siapa melakukan tindakan apa dan kapan secara runtut waktu (*chronological order*).
* **`akun.php`**: Manajemen penggantian kredensial username dan kata sandi admin dengan enkripsi *hash* satu arah.

---

### Daftar Endpoint / Rute Sistem

Sistem navigasi menggunakan rute file PHP mandiri yang bersih dengan penanganan query parameter:

#### A. Rute Publik (Frontend)
* **GET `/index.php`**: Menampilkan halaman beranda publik.
* **GET `/katalog.php`**: Menampilkan katalog menu. Mendukung parameter pencarian `?search=nama_menu` dan penyaringan kategori `?kategori=nama_kategori`.
* **GET `/katalog.php` (AJAX Request)**: Dipanggil oleh javascript melalui fetch API dengan header request AJAX untuk mengembalikan fragmen HTML katalog menu saja tanpa menyertakan header/footer global.
* **GET `/detail.php?id={id_menu}`**: Menampilkan detail lengkap masakan sesuai ID menu terkait.
* **GET `/galeri.php`**: Menampilkan kumpulan foto suasana restoran.
* **GET `/tentang.php`**: Menampilkan sejarah, visi, dan filosofi restoran.
* **GET `/kontak.php`**: Menampilkan peta interaktif dan detail kontak operasional.
* **GET `/api_kontak.php`**: Menyediakan response berformat JSON berupa koordinat GPS, telepon, dan whatsapp.

#### B. Rute Panel Admin (Backend Dashboard)
* **GET `/admin/login.php`**: Menampilkan halaman form masuk bagi admin.
* **POST `/admin/login.php`**: Memproses pengiriman formulir masuk, memvalidasi sandi bcrypt, dan menginisialisasi session yang aman.
* **GET `/admin/logout.php`**: Memproses penghancuran session admin dan meredireksi ke halaman login.
* **GET `/admin/index.php`**: Menampilkan rangkuman statistik data restoran.
* **GET `/admin/menu.php`**: Menampilkan daftar tabel manajemen menu dan formulir tambah/edit.
* **POST `/admin/menu.php`**: Memproses aksi penyimpanan data tambah menu baru atau perubahan menu lama serta mengunggah file foto masakan.
* **POST `/admin/menu.php?action=delete&id={id}`**: Menghapus data menu masakan dan berkas foto fisik terkait.
* **GET `/admin/galeri.php`**: Menampilkan antarmuka manajemen gambar galeri restoran.
* **POST `/admin/galeri.php`**: Memproses unggah gambar suasana interior/eksterior baru.
* **POST `/admin/galeri.php?action=delete&id={id}`**: Menghapus baris galeri dan berkas fisik foto di direktori server.
* **GET `/admin/konten.php`**: Menampilkan formulir pembaruan teks statis web.
* **POST `/admin/konten.php`**: Menyimpan pembaruan teks statis secara dinamis ke tabel `konten_halaman`.
* **GET `/admin/log.php`**: Menampilkan riwayat mutasi data yang terekam.
* **GET `/admin/akun.php`**: Menampilkan formulir ubah kata sandi administrator.
* **POST `/admin/akun.php`**: Memproses pembaruan username dan kata sandi baru admin.

---

### Alur Autentikasi Admin

Proses autentikasi admin diimplementasikan secara aman melalui tiga fase utama:

#### 1. Fase Login
* Administrator memasukkan username dan password pada formulir `/admin/login.php`.
* Input dibersihkan dan disanitasi menggunakan helper filter untuk mengantisipasi masukan berbahaya.
* Mengambil baris data admin pada tabel `users` berdasarkan `username` yang dimasukkan.
* Melakukan verifikasi kecocokan kata sandi menggunakan fungsi bawaan PHP `password_verify()`. Fungsi ini memverifikasi teks biasa inputan dengan hash bcrypt satu arah aman yang tersimpan di database (`password_hash`).
* Jika cocok, data session diinisialisasi melalui `session_start()`.
* Atribut session pengenal unik admin seperti `$_SESSION['admin_id']` dan `$_SESSION['admin_username']` disematkan ke memori server.
* Administrator diarahkan menuju halaman dasbor utama `/admin/index.php`.

#### 2. Proteksi Session Hardening
Untuk memitigasi serangan pencurian sesi (*Session Hijacking*) dan infiltrasi skrip berbahaya (*Cross-Site Scripting*), sistem menerapkan pengamanan session secara ketat:
* Sebelum perintah `session_start()` dieksekusi di seluruh file admin, opsi keamanan session cookie diatur terlebih dahulu secara terprogram melalui baris kode berikut:
```php
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
```
  Aturan **cookie_httponly** mengunci cookie session agar sama sekali tidak dapat diakses atau dibaca melalui skrip sisi klien (seperti JavaScript `document.cookie`).
* Jika koneksi server mendeteksi penggunaan sertifikat SSL/HTTPS, flag **cookie_secure** akan diaktifkan secara otomatis agar transmisi session cookie hanya dikirim melalui lalu lintas jaringan terenkripsi.
* Pada berkas `includes/admin_header.php` yang disertakan di bagian paling atas seluruh berkas panel admin, terdapat pemeriksaan otentikasi wajib:
```php
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
```
  Jika pengguna terindikasi mencoba mengakses halaman panel admin secara langsung tanpa session valid, server akan memutus proses eksekusi dan mengarahkan kembali ke halaman login.

#### 3. Fase Logout
* Ketika admin mengeklik tombol keluar, mereka diarahkan ke `/admin/logout.php`.
* Sistem memanggil `session_start()` untuk mendeteksi session aktif yang berjalan.
* Semua variabel session yang terdaftar akan dibersihkan menggunakan perintah `session_unset()`.
* Session yang tersimpan pada memori server dihancurkan sepenuhnya dengan memanggil `session_destroy()`.
* Cookie session fisik yang tersimpan di browser klien dibersihkan secara paksa dengan mengatur waktu kedaluwarsa ke masa lampau.
* Administrator diarahkan kembali secara aman ke halaman masuk publik `/admin/login.php`.

---

### Penjelasan Fungsi Utama Backend (PHP + MySQL)

#### A. CRUD Menu
* **Create (Tambah Menu)**:
  * Memvalidasi form input seperti nama menu, kategori, harga, dan file gambar masakan.
  * Memproses file unggahan gambar dengan melakukan validasi ekstensi berkas gambar (hanya memperbolehkan format `.jpg`, `.jpeg`, `.png`) dan ukuran berkas maksimum.
  * Membuat nama berkas unik acak guna menghindari tabrakan nama berkas sejenis di server.
  * Menyimpan berkas ke direktori `/assets/images/menu/` dan menyimpan jalur teks URL ke database menggunakan query PDO prepared statement `INSERT INTO menu`.
* **Read (Baca Menu)**:
  * Mengambil data dari tabel `menu` menggunakan query database `SELECT * FROM menu`.
  * Memiliki logika pemisahan halaman (*pagination*) untuk mencegah penurunan kecepatan pemuatan data dalam jumlah besar.
* **Update (Ubah Menu)**:
  * Membuka form yang sudah terisi dengan data lama berdasarkan ID menu terpilih.
  * Jika administrator mengunggah foto baru, sistem akan memvalidasi foto baru tersebut, mengunggahnya ke server, dan menghapus berkas foto lama secara fisik dari direktori server menggunakan fungsi `unlink()` guna menghemat kapasitas penyimpanan.
  * Menyimpan perubahan data ke database menggunakan query `UPDATE menu SET ... WHERE id = :id`.
* **Delete (Hapus Menu)**:
  * Sistem mengambil nama berkas foto menu dari database berdasarkan ID yang dituju.
  * Berkas gambar fisik dihapus secara aman dari direktori lokal server.
  * Baris data dihapus secara permanen dari database menggunakan query `DELETE FROM menu WHERE id = :id`.

#### B. Upload & Manajemen Foto Galeri
* Sistem galeri mempermudah admin menambahkan foto suasana interior dan eksterior baru ke dalam tabel `galeri`.
* **Validasi Folder**: Sebelum menyimpan berkas gambar, backend melakukan verifikasi keberadaan folder penyimpanan di `/assets/images/galeri/`. Jika belum terbentuk, folder akan dibuat secara otomatis dengan izin penulisan berkas yang aman:
```php
if (!is_dir($upload_directory)) {
    mkdir($upload_directory, 0755, true);
}
```
* **Manajemen Berkas Fisik**: Sama halnya dengan CRUD menu, penghapusan item foto galeri juga akan mengeksekusi penghapusan berkas fisik di server terlebih dahulu sebelum menghapus baris data di database. Hal ini menjamin tidak ada tumpukan berkas sampah tak terpakai di media penyimpanan web server.

#### C. Manajemen Konten Halaman
* Manajemen konten beroperasi di halaman `/admin/konten.php`.
* Halaman ini memuat form isian konten dinamis per bagian halaman.
* Saat formulir dikirimkan, backend memproses data menggunakan query database `INSERT INTO konten_halaman` dengan memanfaatkan klausa `ON DUPLICATE KEY UPDATE` bawaan MySQL:
```sql
INSERT INTO konten_halaman (halaman, bagian, isi) 
VALUES (:halaman, :bagian, :isi) 
ON DUPLICATE KEY UPDATE isi = :isi
```
* **Keuntungan**: Query ini secara cerdas mendeteksi jika kombinasi kolom `halaman` dan `bagian` sudah terisi, maka database hanya akan memperbarui isian kolom `isi` tanpa perlu membuat baris data baru. Jika belum terisi, baris baru akan dibuat secara otomatis.

#### D. Log Aktivitas Admin
* Setiap kali terjadi mutasi data penting pada database (seperti menambah menu baru, mengubah galeri foto, menghapus item, atau memperbarui kata sandi admin), sistem backend secara otomatis memicu query internal untuk mencatat aktivitas tersebut ke dalam tabel `log_aktivitas`:
```sql
INSERT INTO log_aktivitas (user_id, aksi) VALUES (:user_id, :aksi)
```
* Catatan log ini secara akurat merekam pengidentifikasi administrator (`user_id`), tindakan operasional yang dieksekusi (`aksi`), serta runtut waktu kejadian otomatis dari sistem database (`waktu`).
* Data ini bersifat *read-only* (hanya dapat dibaca) bagi admin di halaman `/admin/log.php` demi transparansi riwayat keamanan mutasi sistem.

---

### Integrasi Teknologi Pendukung

#### 1. Peta Interaktif (OpenStreetMaps & Leaflet.js)
* Halaman kontak mengintegrasikan peta interaktif ramah seluler menggunakan pustaka JavaScript open-source **Leaflet.js** dan peta **OpenStreetMaps**.
* **Keunggulan**: Menghindari biaya tagihan berbayar API Google Maps dengan performa rendering visual peta yang tetap halus dan responsif.
* **Cara Kerja**: Pustaka memanggil koordinat lokasi GPS Monalisa Resto Bogor secara dinamis, menginisialisasi kontainer peta pada elemen HTML dengan penanda lokasi (*marker*), serta menyematkan tombol navigasi eksternal menuju aplikasi navigasi seperti Google Maps arah jalan bagi pengunjung.

#### 2. Widget Aksi Cepat WhatsApp (WhatsApp Chat Popup kustom)
* Mengintegrasikan widget WhatsApp melayang premium yang bertema mewah di sudut kanan bawah setiap halaman publik.
* **Integrasi Desain**: Tombol mengambang di-*hover* akan menampilkan transisi panel chat popup elegan dengan header bernuansa emas dan arang gelap khas Monalisa Resto.
* **Aksi Cepat (Quick Options)**: Menyediakan tiga tombol pintasan instan yang dapat diklik pengunjung:
  1. *Detail Menu* -> Mengirim pesan *"Halo Monalisa Resto, saya ingin menanyakan detail menu."*
  2. *Memesan Menu* -> Mengirim pesan *"Halo Monalisa Resto, saya ingin melakukan pemesanan menu."*
  3. *Lokasi* -> Mengirim pesan *"Halo Monalisa Resto, saya ingin menanyakan alamat dan lokasi restoran."*
* Pengunjung juga dapat mengetik pesan kustom secara langsung pada area input teks yang tersedia. Saat dikirim, data pesan dikodekan dengan aman (`encodeURIComponent`) dan diarahkan langsung ke WhatsApp API.

#### 3. Integrasi Pemesanan Online (GoFood & GrabFood)
* Pada halaman detail hidangan (`detail.php`), sistem backend mendeteksi ketersediaan menu secara otomatis.
* Halaman ini menyediakan tombol integrasi pemesanan makanan luar yang mengarah langsung ke tautan toko resmi Monalisa Resto di platform pengantaran makanan **GoFood** dan **GrabFood**.
* Memberikan kebebasan bagi pengunjung lokal kota Bogor maupun wisatawan luar kota untuk memilih cara terbaik menikmati sajian legendaris kuliner Monalisa Resto secara langsung atau dikirim ke rumah.
