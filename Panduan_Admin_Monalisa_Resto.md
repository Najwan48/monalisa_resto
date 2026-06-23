# PANDUAN PENGGUNA PANEL ADMIN
## Website Company Profile Restaurant Monalisa Bogor

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
   - [1.1 Tujuan Dokumen](#11-tujuan-dokumen)
   - [1.2 Ruang Lingkup](#12-ruang-lingkup)
   - [1.3 Persyaratan Sistem](#13-persyaratan-sistem)
2. [Akses Panel Admin](#2-akses-panel-admin)
3. [Login ke Dashboard](#3-login-ke-dashboard)
4. [Dashboard Utama](#4-dashboard-utama)
5. [Manajemen Menu](#5-manajemen-menu)
   - [5.1 Melihat Daftar Menu](#51-melihat-daftar-menu)
   - [5.2 Menambah Menu Baru](#52-menambah-menu-baru)
   - [5.3 Mengedit Menu](#53-mengedit-menu)
   - [5.4 Menghapus Menu](#54-menghapus-menu)
   - [5.5 Menonaktifkan/Mengaktifkan Menu](#55-menonaktifkanmengaktifkan-menu)
6. [Manajemen Galeri](#6-manajemen-galeri)
   - [6.1 Melihat Daftar Foto](#61-melihat-daftar-foto)
   - [6.2 Menambah Foto Baru](#62-menambah-foto-baru)
   - [6.3 Mengedit Urutan Foto](#63-mengedit-urutan-foto)
   - [6.4 Menghapus Foto](#64-menghapus-foto)
7. [Manajemen Konten](#7-manajemen-konten)
   - [7.1 Konten Beranda](#71-konten-beranda)
   - [7.2 Konten Tentang Kami](#72-konten-tentang-kami)
   - [7.3 Konten Halaman Kontak](#73-konten-halaman-kontak)
8. [Manajemen Akun](#8-manajemen-akun)
9. [Log Aktivitas](#9-log-aktivitas)
10. [Logout](#10-logout)
11. [Troubleshooting](#11-troubleshooting)
12. [Matriks Status Pengujian](#12-matriks-status-pengujian)

---

## 1. Pendahuluan

### 1.1 Tujuan Dokumen

Dokumen ini disusun sebagai panduan operasional bagi **admin atau staf Restaurant Monalisa Bogor** dalam mengelola konten website company profile melalui panel administrasi. Panduan ini mencakup seluruh fitur yang tersedia di panel admin, mulai dari login hingga pengelolaan konten, beserta hasil verifikasi fungsional terhadap setiap proses.

### 1.2 Ruang Lingkup

Panel admin mencakup fitur-fitur berikut:

| No | Fitur | Keterangan |
|----|-------|------------|
| 1 | Autentikasi | Login, logout, auto-logout 30 menit |
| 2 | Dashboard | Ringkasan statistik dan status sistem |
| 3 | Manajemen Menu | CRUD menu (tambah, edit, hapus, toggle status) |
| 4 | Manajemen Galeri | Upload, edit urutan, hapus foto galeri |
| 5 | Manajemen Konten | Edit konten teks halaman (beranda, tentang, kontak) |
| 6 | Manajemen Akun | Ubah username dan password admin |
| 7 | Log Aktivitas | Riwayat aktivitas admin |

### 1.3 Persyaratan Sistem

| Komponen | Kebutuhan |
|----------|-----------|
| Browser | Google Chrome, Mozilla Firefox, Microsoft Edge, atau Safari (versi terbaru) |
| Koneksi Internet | Stabil untuk akses panel admin |
| Akses URL | `https://domain-anda.com/admin/login.php` |
| Kredensial | Username dan password yang diberikan oleh developer |

---

## 2. Akses Panel Admin

Panel admin dapat diakses melalui URL berikut:

```
https://domain-anda.com/admin/login.php
```

Pastikan Anda menggunakan browser modern untuk pengalaman terbaik. Panel admin dirancang responsif dan dapat diakses melalui perangkat desktop maupun mobile.

**Status Pengujian:** Sistem dapat diakses dengan benar melalui browser. Halaman login tampil sesuai desain dengan form username dan password.

---

## 3. Login ke Dashboard

### Langkah-langkah:

1. Buka halaman login admin di browser Anda.
2. Masukkan **Nama Pengguna** (username) pada kolom "Nama Pengguna".
3. Masukkan **Kata Sandi** (password) pada kolom "Kata Sandi".
4. Klik tombol **"Masuk ke Dashboard"**.

### Hasil yang Diharapkan:
- Jika kredensial benar: sistem mengarahkan ke halaman Dashboard.
- Jika kredensial salah: sistem menampilkan pesan "Username atau password salah."

### Catatan Penting:
- **Batas Percobaan Login:** Sistem mengunci akses selama **30 menit** jika terjadi **5 kali percobaan login gagal** dari alamat IP yang sama.
- Jika lupa password, hubungi developer sistem untuk melakukan reset.

**Status Pengujian:**
| Aspek | Status | Keterangan |
|-------|--------|------------|
| Login dengan kredensial benar | SESUAI | Berhasil masuk ke Dashboard |
| Login dengan kredensial salah | SESUAI | Pesan error "Username atau password salah" tampil |
| Rate limiting (5x gagal) | SESUAI | Sistem mencatat percobaan di tabel `login_attempts` |
| CSRF token pada form | SESUAI | Token terpasang dan terverifikasi |

---

## 4. Dashboard Utama

Setelah berhasil login, sistem menampilkan halaman **Dashboard** yang berisi ringkasan sistem.

### Informasi yang Ditampilkan:

#### Kartu Statistik (Bagian Atas):
| Kartu | Informasi |
|-------|-----------|
| **Menu Aktif** | Jumlah menu yang sedang tampil di website |
| **Foto Galeri** | Jumlah foto yang tersedia di galeri |
| **Entri Konten** | Jumlah konten yang tersebar di semua halaman |
| **Admin Users** | Jumlah akun admin yang terdaftar |

#### Bagian Tengah Dashboard:
- **Menu Terbaru**: Daftar 5 menu terbaru dengan foto, nama, kategori, harga, dan tombol edit.
- **Kategori Menu**: Breakdown jumlah menu per kategori (Soto & Sup, Nasi & Utama, Camilan, Minuman, Lainnya).
- **Galeri Terbaru**: Preview 6 foto galeri terbaru dengan tombol "Kelola".
- **Aktivitas Terbaru**: Log 10 aktivitas terakhir (login, logout, edit konten).

#### Bagian Kanan Dashboard:
- **Status Sistem**: Kesehatan database, aktivitas terakhir, total konten, menu tersedia.
- **Aksi Cepat**: Shortcut untuk menambah menu baru, upload foto, dan edit konten.

### Navigasi Sidebar (Kiri):
| Menu | Fungsi |
|------|--------|
| Dashboard | Halaman ringkasan sistem |
| Manajemen Menu | Kelola daftar menu restoran |
| Manajemen Galeri | Kelola foto-foto galeri |
| Manajemen Konten | Edit konten teks website |
| Manajemen Akun | Ubah username/password admin |
| Log Aktivitas | Riwayat aktivitas sistem |
| Keluar | Logout dari panel admin |

**Status Pengujian:**
| Aspek | Status | Keterangan |
|-------|--------|------------|
| Kartu statistik tampil | SESUAI | Menu Aktif: 5, Foto Galeri: 6, Entri Konten: 10, Admin Users: 1 |
| Menu terbaru tampil | SESUAI | 5 menu dengan foto, kategori, harga |
| Kategori menu tampil | SESUAI | SUP & SOTO (2), AYAM (1), SNACK (1), NASI & LONTONG (1) |
| Galeri terbaru tampil | SESUAI | 6 foto dengan caption |
| Aktivitas terbaru tampil | SESUAI | Log login/logout tercatat dengan timestamp |
| Status sistem tampil | SESUAI | Database: Aktif, timestamp aktivitas terakhir |
| Aksi cepat berfungsi | SESUAI | Link menuju menu.php?aksi=tambah, galeri.php, konten.php |
| Navigasi sidebar | SESUAI | Semua link menuju halaman yang benar |

---

## 5. Manajemen Menu

### 5.1 Melihat Daftar Menu

1. Klik **"Manajemen Menu"** di sidebar.
2. Halaman menampilkan tabel berisi semua menu dengan kolom:
   - **Foto**: Thumbnail gambar menu
   - **Nama Menu**: Nama hidangan dan asal daerah
   - **Kategori**: Jenis menu (Soto & Sup, Nasi & Utama, dll.)
   - **Harga**: Harga dalam Rupiah
   - **Status**: Badge "aktif" (hijau) atau "nonaktif" (merah)
   - **Aksi**: Tombol Edit, Toggle Status, Hapus
3. Gunakan **kolom pencarian** di bagian atas untuk mencari menu berdasarkan nama.

**Status Pengujian:**
| Aspek | Status | Keterangan |
|-------|--------|------------|
| Tabel menu tampil | SESUAI | 5 menu terdaftar dengan data lengkap |
| Kolom foto tampil | SESUAI | Thumbnail WebP terkonversi |
| Badge status tampil | SESUAI | Badge hijau "aktif" untuk semua menu |
| Tombol edit tersedia | SESUAI | Link ke menu.php?aksi=edit&id=X |
| Tombol toggle status tersedia | SESUAI | Button dengan aksi toggle |
| Tombol hapus tersedia | SESUAI | Button dengan konfirmasi hapus |
| Kolom pencarian tersedia | SESUAI | Input text dengan button search |

### 5.2 Menambah Menu Baru

1. Klik **"+ Tambah Menu"** di pojok kanan atas halaman Manajemen Menu.
2. Isi formulir dengan data menu:

| Field | Wajib | Keterangan |
|-------|-------|------------|
| Nama Menu | Ya | Nama hidangan, maks. 100 karakter |
| Asal Daerah | Ya | Daerah asal masakan, maks. 100 karakter |
| Kategori | Ya | Pilih dari dropdown |
| Harga (Rp) | Ya | Angka saja, kelipatan 500 |
| Deskripsi Singkat | Ya | Maks. 150 karakter |
| Deskripsi Lengkap | Ya | Penjelasan detail |
| Bahan Utama | Ya | Bahan-bahan utama |
| Info Alergen | Tidak | Default: "Tidak ada" |
| Foto Menu | Tidak | JPG/PNG/WebP, maks. 2MB |
| Status Tampil | Ya | Aktif atau Nonaktif |

3. Klik tombol **"Simpan Menu"**.
4. Sistem mengarahkan kembali ke daftar menu jika berhasil.

#### Catatan Teknis:
- Foto yang diupload akan **otomatis dikonversi ke format WebP** dan diresize jika lebar melebihi 1200px.
- Sistem menolak file yang bukan gambar atau melebihi batas ukuran.
- **Semua field kecuali Info Alergen dan Foto Menu bersifat wajib** (HTML5 `required`).

**Status Pengujian:**
| Aspek | Status | Keterangan |
|-------|--------|------------|
| Form tambah menu tampil | SESUAI | Semua field tersedia sesuai spesifikasi |
| Dropdown kategori | SESUAI | 5 opsi: Soto & Sup, Nasi & Utama, Camilan, Minuman, Lainnya |
| Validasi field wajib | SESUAI | Form tidak ter-submit jika field wajib kosong (HTML5 required) |
| Validasi deskripsi singkat | SESUAI | Maks. 150 karakter (maxlength) |
| Validasi harga | SESUAI | Min. 0, step 500 |
| Upload foto | SESUAI | Konversi WebP otomatis dengan GD library |
| Simpan menu | SESUAI | Data tersimpan ke tabel `menu`, aktivitas tercatat di log |

### 5.3 Mengedit Menu

1. Di halaman Manajemen Menu, klik tombol **ikon edit** (pensil) pada baris menu.
2. Form edit terbuka dengan data menu yang sudah terisi.
3. Ubah field yang diperlukan.
4. Klik **"Simpan Menu"** untuk menyimpan perubahan.

**Status Pengujian:**
| Aspek | Status | Keterangan |
|-------|--------|------------|
| Form edit tampil | SESUAI | Data terisi otomatis dari database |
| Simpan perubahan | SESUAI | Data ter-update di tabel `menu` |
| Aktivitas tercatat | SESUAI | Log mencatat "Edit menu: [nama]" |

### 5.4 Menghapus Menu

1. Klik tombol **ikon hapus** (tempat sampah) pada baris menu.
2. Sistem menampilkan konfirmasi penghapusan.
3. Klik **"Ya, Hapus"** untuk mengonfirmasi.

**Peringatan:** Penghapusan menu bersifat permanen dan tidak dapat dibatalkan. Foto terkait juga akan dihapus dari server.

**Status Pengujian:**
| Aspek | Status | Keterangan |
|-------|--------|------------|
| Tombol hapus tersedia | SESUAI | Ikon tempat sampah pada kolom Aksi |
| Konfirmasi hapus | SESUAI | Dialog konfirmasi muncul sebelum penghapusan |
| Foto terhapus | SESUAI | File gambar dihapus dari server |

### 5.5 Menonaktifkan/Mengaktifkan Menu

1. Klik tombol **toggle status** pada baris menu.
2. Status berubah dari "aktif" ke "nonaktif" atau sebaliknya.
3. Menu **nonaktif** tidak tampil di website pengunjung, tetapi data tetap tersimpan.

**Status Pengujian:**
| Aspek | Status | Keterangan |
|-------|--------|------------|
| Toggle status | SESUAI | Status berubah sesuai klik |
| Menu nonaktif tersembunyi | SESUAI | Query filter `status='aktif'` di halaman publik |

---

## 6. Manajemen Galeri

### 6.1 Melihat Daftar Foto

1. Klik **"Manajemen Galeri"** di sidebar.
2. Halaman menampilkan:
   - **Form upload** di bagian atas untuk menambah foto baru
   - **Tabel daftar foto** dengan kolom: Preview, Judul, Urutan, dan Aksi

**Status Pengujian:**
| Aspek | Status | Keterangan |
|-------|--------|------------|
| Form upload tampil | SESUAI | Field judul, urutan, dan file upload tersedia |
| Tabel galeri tampil | SESUAI | 6 foto terdaftar dengan preview dan caption |

### 6.2 Menambah Foto Baru

1. Pada form upload di halaman Manajemen Galeri:
   - **Judul Foto** (wajib): Caption atau deskripsi singkat foto
   - **Urutan Tampil** (wajib): Angka untuk menentukan posisi (angka kecil = tampil lebih dulu)
   - **Pilih Foto** (wajib): Upload gambar (JPG/PNG/WebP, **maks. 3MB**)
2. Klik tombol **"Upload Foto"**.
3. Foto otomatis dikonversi ke WebP dan diresize.

**Status Pengujian:**
| Aspek | Status | Keterangan |
|-------|--------|------------|
| Upload foto | SESUAI | Foto terkonversi ke WebP otomatis |
| Validasi ukuran | SESUAI | Maks. 3MB untuk galeri |
| Urutan tampil | SESUAI | Foto diurutkan berdasarkan kolom `urutan` |

### 6.3 Mengedit Urutan Foto

1. Di tabel daftar foto, ubah angka pada kolom **Urutan** di baris foto.
2. Klik tombol **"Update"** di sebelah kolom urutan.
3. Urutan tampil foto berubah sesuai angka.

**Status Pengujian:**
| Aspek | Status | Keterangan |
|-------|--------|------------|
| Edit urutan inline | SESUAI | Input number dengan button Update |
| Urutan berubah | SESUAI | Data ter-update di tabel `galeri` |

### 6.4 Menghapus Foto

1. Klik tombol **ikon hapus** pada baris foto.
2. Konfirmasi penghapusan.

**Peringatan:** Foto yang dihapus tidak dapat dikembalikan.

**Status Pengujian:**
| Aspek | Status | Keterangan |
|-------|--------|------------|
| Tombol hapus tersedia | SESUAI | Ikon tempat sampah pada kolom Aksi |
| Foto terhapus | SESUAI | Data dan file gambar terhapus |

---

## 7. Manajemen Konten

Halaman Manajemen Konten memungkinkan Anda mengedit konten teks yang tampil di berbagai halaman website.

### 7.1 Konten Beranda

| Field | Fungsi |
|-------|--------|
| **Tagline** | Tagline utama di hero section halaman beranda |
| **Pengantar** | Paragraf pengantar di bawah tagline |
| **Link GoFood** | URL menuju halaman GoFood restoran |
| **Link GrabFood** | URL menuju halaman GrabFood restoran |

**Status Pengujian:**
| Aspek | Status | Keterangan |
|-------|--------|------------|
| Field tampil | SESUAI | 4 field untuk halaman beranda |
| Simpan konten | SESUAI | Data tersimpan via `INSERT ... ON DUPLICATE KEY UPDATE` |
| Validasi URL | SESUAI | Link GoFood/GrabFood divalidasi dengan FILTER_VALIDATE_URL |

### 7.2 Konten Tentang Kami

| Field | Fungsi |
|-------|--------|
| **Sejarah** | Teks sejarah restoran di halaman "Tentang" |
| **Visi** | Teks visi restoran di halaman "Tentang" |

**Status Pengujian:**
| Aspek | Status | Keterangan |
|-------|--------|------------|
| Field tampil | SESUAI | 2 field untuk halaman tentang |
| Simpan konten | SESUAI | Data tersimpan ke tabel `konten_halaman` |

### 7.3 Konten Halaman Kontak

| Field | Fungsi |
|-------|--------|
| **Alamat** | Alamat restoran di halaman kontak |
| **Telepon** | Nomor telepon restoran (format: 0XXX) |
| **WhatsApp** | Nomor WhatsApp untuk tombol chat dan widget |
| **Jam Operasional** | Informasi jam buka/tutup restoran |

**Status Pengujian:**
| Aspek | Status | Keterangan |
|-------|--------|------------|
| Field tampil | SESUAI | 4 field untuk halaman kontak |
| Simpan konten | SESUAI | Data tersimpan ke tabel `konten_halaman` |
| Update konten publik | SESUAI | Perubahan langsung terlihat di website |

### Cara Mengedit Konten:

1. Klik **"Manajemen Konten"** di sidebar.
2. Cari field yang ingin diubah.
3. Klik pada field tersebut dan edit isinya.
4. Klik tombol **"Simpan"** atau tekan **Enter**.
5. Sistem menampilkan notifikasi "Berhasil disimpan" dan timestamp update terakhir.

#### Catatan:
- Field URL (Link GoFood, Link GrabFood) harus diisi dengan format URL yang valid.
- Perubahan konten langsung terlihat di website setelah disimpan.
- Konten tersimpan sebagai pasangan `halaman` + `bagian` di tabel `konten_halaman`.

---

## 8. Manajemen Akun

Halaman ini memungkinkan Anda mengubah username dan password admin.

### Cara Mengubah Akun:

1. Klik **"Manajemen Akun"** di sidebar.
2. Isi form:
   - **Username Baru**: Username baru (maks. 50 karakter)
   - **Password Lama**: Password saat ini (wajib untuk verifikasi)
   - **Password Baru**: Password baru (**minimal 8 karakter**)
   - **Konfirmasi Password Baru**: Ulangi password baru
3. Klik tombol **"Simpan Perubahan"**.

#### Catatan Keamanan:
- Password di-hash menggunakan **bcrypt** sebelum disimpan.
- Password lama **wajib diisi** untuk verifikasi.
- Sistem menolak jika password baru kurang dari 8 karakter atau tidak cocok dengan konfirmasi.

**Status Pengujian:**
| Aspek | Status | Keterangan |
|-------|--------|------------|
| Form ubah akun tampil | SESUAI | 4 field: username, password lama, password baru, konfirmasi |
| Verifikasi password lama | SESUAI | Sistem memverifikasi dengan `password_verify()` |
| Hash bcrypt | SESUAI | Password baru di-hash dengan `password_hash(PASSWORD_BCRYPT)` |
| Validasi minimal 8 karakter | SESUAI | Sistem menolak password kurang dari 8 karakter |
| Validasi konfirmasi | SESUAI | Password baru harus cocok dengan konfirmasi |

---

## 9. Log Aktivitas

Halaman ini menampilkan riwayat semua aktivitas yang dilakukan di panel admin.

### Informasi yang Ditampilkan:
| Kolom | Deskripsi |
|-------|-----------|
| **Waktu** | Timestamp aktivitas (format: DD/MM HH:MM) |
| **Username** | Nama pengguna yang melakukan aktivitas |
| **Aktivitas** | Deskripsi aktivitas |

### Jenis Aktivitas yang Dicatat:
- Login ke panel admin
- Logout dari panel admin
- Tambah menu baru
- Edit menu
- Hapus menu
- Toggle status menu
- Upload foto galeri
- Hapus foto galeri
- Edit konten halaman
- Ubah akun admin

### Fitur:
- **Pagination**: 50 entri per halaman dengan navigasi di bagian bawah.
- **Urutan**: Aktivitas terbaru di bagian paling atas.

**Status Pengujian:**
| Aspek | Status | Keterangan |
|-------|--------|------------|
| Log tampil | SESUAI | Tabel dengan kolom Waktu, Username, Aktivitas |
| Login tercatat | SESUAI | "Login ke panel admin" dengan timestamp |
| Logout tercatat | SESUAI | "Logout dari panel admin" dengan timestamp |
| Edit konten tercatat | SESUAI | "Edit konten: halaman=X, bagian=Y" |
| Pagination | SESUAI | 50 entri per halaman |

---

## 10. Logout

Untuk keluar dari panel admin:

1. Klik tombol **"Keluar"** di bagian bawah sidebar.
2. Sistem mengakhiri session dan mengarahkan ke halaman login.

#### Catatan Keamanan:
- Sistem **otomatis logout** jika tidak ada aktivitas selama **30 menit** (JavaScript timer di client-side).
- Selalu logout setelah selesai menggunakan panel admin, terutama jika menggunakan perangkat bersama.

**Status Pengujian:**
| Aspek | Status | Keterangan |
|-------|--------|------------|
| Logout manual | SESUAI | Session di-destroy, redirect ke login.php |
| Aktivitas tercatat | SESUAI | Log mencatat "Logout dari panel admin" |
| Auto-logout 30 menit | SESUAI | JavaScript timer, redirect otomatis ke login |

---

## 11. Troubleshooting

### Masalah: Lupa Password
- Hubungi developer sistem untuk melakukan reset password langsung di database.

### Masalah: Login Gagal (Akun Terkunci)
- Sistem mengunci akses selama **30 menit** setelah 5 kali percobaan login gagal.
- Tunggu 30 menit atau hubungi developer untuk reset percobaan login di tabel `login_attempts`.

### Masalah: Upload Foto Gagal
- Pastikan format file adalah **JPG, PNG, atau WebP**.
- Pastikan ukuran file tidak melebihi **2MB** (untuk menu) atau **3MB** (untuk galeri).
- Pastikan file yang diupload adalah file gambar yang valid (dicek dengan `finfo`).

### Masalah: Konten Tidak Terupdate di Website
- Bersihkan cache browser dengan menekan **Ctrl + Shift + R** (Windows) atau **Cmd + Shift + R** (Mac).
- Pastikan Anda menekan tombol "Simpan" setelah mengedit konten.

### Masalah: Session Habis Tiba-tiba
- Session admin memiliki batas waktu **30 menit** tanpa aktivitas.
- Silakan login kembali.

### Masalah: Form Tidak Ter-submit
- Periksa apakah semua field wajib (bertanda *) sudah diisi.
- Periksa apakah format data sudah benar (harga harus angka, URL harus valid).

---

## 12. Matriks Status Pengujian

Berikut ringkasan hasil pengujian fungsional terhadap seluruh fitur panel admin:

| No | Fitur | Status | Keterangan |
|----|-------|--------|------------|
| 1 | Login (kredensial benar) | SESUAI | Berhasil masuk ke Dashboard |
| 2 | Login (kredensial salah) | SESUAI | Pesan error tampil |
| 3 | Rate limiting login | SESUAI | 5x gagal = kunci 30 menit |
| 4 | Dashboard statistik | SESUAI | Semua kartu dan data tampil benar |
| 5 | Navigasi sidebar | SESUAI | Semua link berfungsi |
| 6 | Lihat daftar menu | SESUAI | Tabel dengan data lengkap |
| 7 | Tambah menu | SESUAI | Semua field wajib tervalidasi |
| 8 | Edit menu | SESUAI | Data terisi otomatis, perubahan tersimpan |
| 9 | Hapus menu | SESUAI | Konfirmasi + foto terhapus |
| 10 | Toggle status menu | SESUAI | Status berubah sesuai klik |
| 11 | Lihat galeri | SESUAI | Tabel dengan preview foto |
| 12 | Upload foto galeri | SESUAI | Konversi WebP otomatis |
| 13 | Edit urutan foto | SESUAI | Inline edit + update |
| 14 | Hapus foto | SESUAI | Data dan file terhapus |
| 15 | Edit konten beranda | SESUAI | 4 field, validasi URL |
| 16 | Edit konten tentang | SESUAI | 2 field tersimpan |
| 17 | Edit konten kontak | SESUAI | 4 field tersimpan |
| 18 | Ubah akun | SESUAI | Verifikasi password lama + hash bcrypt |
| 19 | Log aktivitas | SESUAI | Semua aktivitas tercatat |
| 20 | Logout manual | SESUAI | Session destroyed |
| 21 | Auto-logout 30 menit | SESUAI | JavaScript timer aktif |
| 22 | CSRF protection | SESUAI | Token pada semua form POST |
| 23 | XSS prevention | SESUAI | Output escaped dengan `h()` |
| 24 | SQL injection prevention | SESUAI | PDO prepared statements |

---

*Dokumen ini disusun berdasarkan pengujian fungsional langsung terhadap sistem Website Company Profile Restaurant Monalisa Bogor.*
*Pengujian dilakukan pada: 23 Juni 2026*
