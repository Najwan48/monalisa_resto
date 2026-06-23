# DOKUMEN TESTING HALAMAN GUEST (PUBLIK)
## Website Company Profile Restaurant Monalisa Bogor

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
   - [1.1 Tujuan Dokumen](#11-tujuan-dokumen)
   - [1.2 Ruang Lingkup Pengujian](#12-ruang-lingkup-pengujian)
   - [1.3 Lingkungan Pengujian](#13-lingkungan-pengujian)
2. [Halaman Beranda (index.php)](#2-halaman-beranda-indexphp)
3. [Halaman Katalog Menu (katalog.php)](#3-halaman-katalog-menu-katalogphp)
4. [Halaman Detail Menu (detail.php)](#4-halaman-detail-menu-detailphp)
5. [Halaman Galeri (galeri.php)](#5-halaman-galeri-galeriphp)
6. [Halaman Kontak (kontak.php)](#6-halaman-kontak-kontakphp)
7. [Halaman Tentang (tentang.php)](#7-halaman-tentang-tentangphp)
8. [API Kontak (api_kontak.php)](#8-api-kontak-api_kontakphp)
9. [Widget WhatsApp](#9-widget-whatsapp)
10. [Navigasi dan Responsivitas](#10-navigasi-dan-responsivitas)
11. [Matriks Status Pengujian](#11-matriks-status-pengujian)

---

## 1. Pendahuluan

### 1.1 Tujuan Dokumen

Dokumen ini berisi hasil pengujian fungsional terhadap seluruh halaman guest (publik) pada Website Company Profile Restaurant Monalisa Bogor. Pengujian dilakukan secara langsung melalui browser untuk memverifikasi bahwa setiap fitur berjalan sesuai dengan spesifikasi yang telah ditetapkan dalam dokumen SRS dan Proposal.

### 1.2 Ruang Lingkup Pengujian

Pengujian mencakup seluruh halaman yang dapat diakses oleh pengunjung (guest) tanpa autentikasi:

| No | Halaman | URL |
|----|---------|-----|
| 1 | Beranda | /index.php |
| 2 | Katalog Menu | /katalog.php |
| 3 | Detail Menu | /detail.php?id=N |
| 4 | Galeri Foto | /galeri.php |
| 5 | Kontak & Lokasi | /kontak.php |
| 6 | Tentang Kami | /tentang.php |
| 7 | API Kontak | /api_kontak.php |

### 1.3 Lingkungan Pengujian

| Komponen | Versi/Detail |
|----------|-------------|
| Server | XAMPP 8.2.12 (Apache + MariaDB 10.4) |
| Backend | PHP 8.2 dengan PDO MySQL |
| Browser | Playwright (Chromium-based) |
| Database | monalisa_resto (MariaDB 10.4) |
| Tanggal Pengujian | 23 Juni 2026 |

---

## 2. Halaman Beranda (index.php)

### 2.1 Deskripsi

Halaman utama website yang menampilkan hero section, tagline, menu signature, value propositions, dan link pemesanan online.

### 2.2 Komponen yang Diuji

| No | Komponen | Ekspektasi | Hasil | Status |
|----|----------|------------|-------|--------|
| 1 | Page Title | "Beranda — Monalisa Resto" | Sesuai | SESUAI |
| 2 | Hero Section | Menampilkan tagline dari database | Tagline tampil dari tabel konten_halaman | SESUAI |
| 3 | Navigasi | Menu Beranda, Menu, Galeri, Kontak & Lokasi, Tentang | Semua link tampil dan berfungsi | SESUAI |
| 4 | Menu Signature | Menampilkan 5 menu acak yang aktif | 5 menu tampil dengan foto, nama, kategori, harga | SESUAI |
| 5 | Value Propositions | 3 kartu: Fresh Ingredients, Heritage Recipes, Premium Experience | Semua tampil | SESUAI |
| 6 | Online Order | Link GoFood dan GrabFood | Link tampil sesuai data di konten_halaman | SESUAI |
| 7 | Footer | Copyright dan info kontak | Tampil dengan tahun 2026 | SESUAI |
| 8 | WhatsApp Widget | Tombol floating WhatsApp | Tampil di pojok kanan bawah | SESUAI |
| 9 | XSS Prevention | Output escaped dengan h() | Semua output aman dari XSS | SESUAI |

### 2.3 Status Keseluruhan: SESUAI

---

## 3. Halaman Katalog Menu (katalog.php)

### 3.1 Deskripsi

Halaman yang menampilkan daftar lengkap menu restoran dengan fitur pencarian, filter kategori, dan paginasi.

### 3.2 Komponen yang Diuji

| No | Komponen | Ekspektasi | Hasil | Status |
|----|----------|------------|-------|--------|
| 1 | Page Title | Tampil | Tampil | SESUAI |
| 2 | Daftar Menu | Menampilkan menu aktif dengan paginasi 12 item/halaman | 5 menu tampil (data seed) | SESUAI |
| 3 | Filter Kategori | Dropdown/nav filter berdasarkan kategori | Filter "SUP & SOTO" mengembalikan 2 item | SESUAI |
| 4 | Pencarian | Input search berdasarkan nama menu | Search "soto" mengembalikan hasil yang relevan | SESUAI |
| 5 | Paginasi | Navigasi halaman jika item > 12 | Tersedia, AJAX pagination berfungsi | SESUAI |
| 6 | AJAX Pagination | Parameter _ajax=1 mengembalikan JSON | Response: {html, page, total_pages} | SESUAI |
| 7 | Link Detail | Klik menu mengarahkan ke detail.php?id=N | Berfungsi | SESUAI |
| 8 | Kategori Kosong | Pesan jika tidak ada menu dalam kategori | Tampil pesan yang sesuai | SESUAI |

### 3.3 Status Keseluruhan: SESUAI

---

## 4. Halaman Detail Menu (detail.php)

### 4.1 Deskripsi

Halaman yang menampilkan detail lengkap satu menu hidangan, termasuk foto, deskripsi, bahan, info alergen, dan link pemesanan.

### 4.2 Komponen yang Diuji

| No | Komponen | Ekspektasi | Hasil | Status |
|----|----------|------------|-------|--------|
| 1 | Menu Valid (id=1) | Menampilkan detail Soto Kudus | Foto, nama, asal daerah, kategori, harga, deskripsi, bahan, alergen tampil | SESUAI |
| 2 | Menu Invalid (id=999) | Pesan "Menu tidak ditemukan" | Tampil pesan + link "Kembali ke Katalog" | SESUAI |
| 3 | Menu Nonaktif | Tidak dapat diakses | Menu nonaktif tidak tampil (filter status='aktif') | SESUAI |
| 4 | Foto Menu | Gambar WebP tampil | Foto tampil dengan benar | SESUAI |
| 5 | Info Alergen | Tampil jika ada | Tampil sesuai data | SESUAI |
| 6 | Link GoFood | Eksternal link ke GoFood | Tersedia | SESUAI |
| 7 | Link GrabFood | Eksternal link ke GrabFood | Tersedia | SESUAI |
| 8 | Link WhatsApp | Pre-filled message dengan nama menu dan harga | Link valid dengan format wa.me | SESUAI |
| 9 | Link Telepon | Click-to-call | Format tel: valid | SESUAI |
| 10 | Back Link | Kembali ke katalog | Link berfungsi | SESUAI |

### 4.3 Status Keseluruhan: SESUAI

---

## 5. Halaman Galeri (galeri.php)

### 5.1 Deskripsi

Halaman yang menampilkan galeri foto suasana restoran dengan tampilan grid dan fitur lightbox.

### 5.2 Komponen yang Diuji

| No | Komponen | Ekspektasi | Hasil | Status |
|----|----------|------------|-------|--------|
| 1 | Grid Foto | CSS grid layout | Foto tampil dalam grid yang rapi | SESUAI |
| 2 | Urutan Foto | Diurutkan berdasarkan kolom urutan ASC | Urutan sesuai | SESUAI |
| 3 | Caption/Overlay | Judul foto tampil sebagai overlay | Caption tampil saat hover | SESUAI |
| 4 | Lightbox | Klik foto membuka lightbox | Lightbox terbuka dengan foto besar | SESUAI |
| 5 | Close Lightbox | Tombol close atau Escape key | Lightbox tertutup dengan benar | SESUAI |
| 6 | Responsivitas | Grid responsif di mobile | Grid menyesuaikan layar | SESUAI |

### 5.3 Status Keseluruhan: SESUAI

---

## 6. Halaman Kontak (kontak.php)

### 6.1 Deskripsi

Halaman yang menampilkan informasi kontak restoran, peta lokasi interaktif, dan link WhatsApp.

### 6.2 Komponen yang Diuji

| No | Komponen | Ekspektasi | Hasil | Status |
|----|----------|------------|-------|--------|
| 1 | Alamat | Menampilkan alamat dari database | Alamat tampil sesuai konten_halaman | SESUAI |
| 2 | Telepon | Nomor telepon clickable | Format tel: valid, copy-to-clipboard berfungsi | SESUAI |
| 3 | WhatsApp | Link ke WhatsApp | Format wa.me valid dengan nomor dari database | SESUAI |
| 4 | Jam Operasional | Tampil dari database | Jam operasional tampil | SESUAI |
| 5 | Peta Leaflet | OpenStreetMap interaktif | Peta tampil di koordinat (-6.6263927, 106.8214916) | SESUAI |
| 6 | Marker Peta | Pin lokasi restoran | Marker tampil dengan popup | SESUAI |
| 7 | Copy to Clipboard | Tombol salin nomor telepon | Berfungsi, feedback visual tampil | SESUAI |

### 6.3 Status Keseluruhan: SESUAI

---

## 7. Halaman Tentang (tentang.php)

### 7.1 Deskripsi

Halaman yang menampilkan sejarah dan visi restoran, dikelola melalui panel admin.

### 7.2 Komponen yang Diuji

| No | Komponen | Ekspektasi | Hasil | Status |
|----|----------|------------|-------|--------|
| 1 | Sejarah | Teks sejarah dari database | Tampil sesuai konten_halaman (halaman=tentang_kami, bagian=sejarah) | SESUAI |
| 2 | Visi | Teks visi dari database | Tampil sesuai konten_halaman (halaman=tentang_kami, bagian=visi) | SESUAI |
| 3 | Gambar Pendukung | Foto ilustrasi | Tampil dengan benar | SESUAI |
| 4 | CMS Editable | Konten dapat diubah dari admin | Perubahan di admin langsung terlihat | SESUAI |

### 7.3 Status Keseluruhan: SESUAI

---

## 8. API Kontak (api_kontak.php)

### 8.1 Deskripsi

Endpoint JSON yang mengembalikan informasi kontak (telepon dan WhatsApp) untuk digunakan oleh widget atau integrasi eksternal.

### 8.2 Komponen yang Diuji

| No | Komponen | Ekspektasi | Hasil | Status |
|----|----------|------------|-------|--------|
| 1 | HTTP Status | 200 OK | 200 | SESUAI |
| 2 | Content-Type | application/json | application/json | SESUAI |
| 3 | CORS | Access-Control-Allow-Origin: * | Header terpasang | SESUAI |
| 4 | Response Format | {telepon, whatsapp} | {"telepon": "081281141923", "whatsapp": "6281281141923"} | SESUAI |
| 5 | Normalisasi WhatsApp | Konversi 0xxx ke 62xxx | 081281141923 -> 6281281141923 | SESUAI |

### 8.3 Response Contoh

```json
{
    "telepon": "081281141923",
    "whatsapp": "6281281141923"
}
```

### 8.4 Status Keseluruhan: SESUAI

---

## 9. Widget WhatsApp

### 9.1 Deskripsi

Floating chat widget yang tampil di semua halaman publik, memungkinkan pengunjung mengirim pesan WhatsApp langsung ke restoran.

### 9.2 Komponen yang Diuji

| No | Komponen | Ekspektasi | Hasil | Status |
|----|----------|------------|-------|--------|
| 1 | Tombol Floating | Ikon WhatsApp di pojok kanan bawah | Tampil di semua halaman guest | SESUAI |
| 2 | Popup Chat | Klik membuka popup dengan header dan input | Popup tampil dengan benar | SESUAI |
| 3 | Quick Reply Buttons | 3 tombol: Detail Menu, Pesan Menu, Lokasi | Semua tampil dan berfungsi | SESUAI |
| 4 | Input Pesan | Text input untuk pesan custom | Input tersedia | SESUAI |
| 5 | Kirim Pesan | Link ke wa.me dengan nomor dan pesan | Format wa.me/62xxx?text= valid | SESUAI |
| 6 | Close Popup | Tombol X untuk menutup popup | Popup tertutup | SESUAI |

### 9.3 Status Keseluruhan: SESUAI

---

## 10. Navigasi dan Responsivitas

### 10.1 Navigasi

| No | Komponen | Ekspektasi | Hasil | Status |
|----|----------|------------|-------|--------|
| 1 | Logo/Brand | Link ke beranda | Klik logo mengarahkan ke index.php | SESUAI |
| 2 | Menu Beranda | Link aktif di halaman beranda | Highlight aktif sesuai halaman | SESUAI |
| 3 | Menu Menu | Link ke katalog.php | Berfungsi | SESUAI |
| 4 | Menu Galeri | Link ke galeri.php | Berfungsi | SESUAI |
| 5 | Menu Kontak | Link ke kontak.php | Berfungsi | SESUAI |
| 6 | Menu Tentang | Link ke tentang.php | Berfungsi | SESUAI |
| 7 | Active State | Highlight halaman aktif | Navigasi menandai halaman yang sedang dikunjungi | SESUAI |
| 8 | Mobile Hamburger | Menu hamburger di mobile | Tampil dan berfungsi | SESUAI |

### 10.2 Responsivitas

| No | Breakpoint | Ekspektasi | Hasil | Status |
|----|------------|------------|-------|--------|
| 1 | Desktop (>1024px) | Layout multi-kolom | Tampil dengan benar | SESUAI |
| 2 | Tablet (768-1024px) | Layout menyesuaikan | Responsif | SESUAI |
| 3 | Mobile (<768px) | Layout single-kolom, hamburger menu | Responsif | SESUAI |

### 10.3 Status Keseluruhan: SESUAI

---

## 11. Matriks Status Pengujian

Berikut ringkasan hasil pengujian fungsional terhadap seluruh halaman guest:

| No | Halaman/Fitur | Status | Keterangan |
|----|---------------|--------|------------|
| 1 | Beranda - Hero Section | SESUAI | Tagline dari database tampil |
| 2 | Beranda - Menu Signature | SESUAI | 5 menu acak aktif tampil |
| 3 | Beranda - Value Propositions | SESUAI | 3 kartu tampil |
| 4 | Beranda - Online Order | SESUAI | Link GoFood/GrabFood valid |
| 5 | Katalog - Daftar Menu | SESUAI | Menu aktif tampil dengan paginasi |
| 6 | Katalog - Filter Kategori | SESUAI | Filter berfungsi, hasil sesuai |
| 7 | Katalog - Pencarian | SESUAI | Search mengembalikan hasil relevan |
| 8 | Katalog - AJAX Pagination | SESUAI | _ajax=1 mengembalikan JSON valid |
| 9 | Detail - Menu Valid | SESUAI | Semua field tampil lengkap |
| 10 | Detail - Menu Invalid | SESUAI | Pesan error + link kembali |
| 11 | Detail - Menu Nonaktif | SESUAI | Tidak dapat diakses |
| 12 | Detail - Link WhatsApp | SESUAI | Pre-filled message dengan nama & harga |
| 13 | Detail - Link GoFood/GrabFood | SESUAI | Link eksternal valid |
| 14 | Galeri - Grid Foto | SESUAI | Layout grid rapi |
| 15 | Galeri - Lightbox | SESUAI | Buka/tutup berfungsi |
| 16 | Galeri - Urutan | SESUAI | Sesuai kolom urutan |
| 17 | Kontak - Info Kontak | SESUAI | Alamat, telepon, WA dari database |
| 18 | Kontak - Peta Leaflet | SESUAI | OpenStreetMap interaktif di koordinat benar |
| 19 | Kontak - Copy to Clipboard | SESUAI | Tombol salin nomor berfungsi |
| 20 | Tentang - Sejarah | SESUAI | Konten dari database |
| 21 | Tentang - Visi | SESUAI | Konten dari database |
| 22 | API Kontak - Response | SESUAI | JSON valid dengan normalisasi nomor |
| 23 | API Kontak - CORS | SESUAI | Header CORS terpasang |
| 24 | WhatsApp Widget | SESUAI | Floating button + popup + quick reply |
| 25 | Navigasi - Semua Link | SESUAI | Semua menu navigasi berfungsi |
| 26 | Navigasi - Active State | SESUAI | Highlight halaman aktif |
| 27 | Responsivitas - Desktop | SESUAI | Layout multi-kolom |
| 28 | Responsivitas - Mobile | SESUAI | Hamburger menu + layout responsif |
| 29 | Keamanan - XSS Prevention | SESUAI | Output escaped dengan h() |
| 30 | Keamanan - SQL Injection | SESUAI | PDO prepared statements |
| 31 | Keamanan - No-cache Headers | SESUAI | Header terpasang di semua halaman |

**Total: 31 item pengujian | Semua SESUAI**

---

## 12. Kesimpulan

Seluruh halaman guest (publik) Website Company Profile Restaurant Monalisa Bogor telah diuji dan dinyatakan **SESUAI** dengan spesifikasi. Tidak ditemukan bug atau kegagalan fungsi pada saat pengujian dilakukan.

Fitur-fitur utama yang telah terverifikasi:
- 6 halaman publik berfungsi dengan benar
- Pencarian dan filter menu berfungsi
- AJAX pagination berfungsi
- Error handling untuk menu tidak ditemukan berfungsi
- API endpoint mengembalikan data valid
- WhatsApp widget berintegrasi dengan baik
- Peta Leaflet/OpenStreetMap berfungsi
- Navigasi dan responsivitas berfungsi
- Keamanan (XSS, SQL injection, no-cache) terimplementasi

---

*Dokumen ini disusun berdasarkan pengujian fungsional langsung terhadap sistem Website Company Profile Restaurant Monalisa Bogor.*
*Pengujian dilakukan pada: 23 Juni 2026*
