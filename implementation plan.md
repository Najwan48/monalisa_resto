# PROJECT BRIEF & PROMPT PENGEMBANGAN: WEBSITE COMPANY PROFILE RESTAURANT MONALISA BOGOR
*(Terverifikasi & Disinkronisasi 100% dengan Proposal_CompanyProfile_Restaurant_Monalisa.docx & BRD_CompanyProfile_Restaurant_Monalisa.docx — April 2026)*

---

## 1. INFORMASI DASAR PROYEK
| Aspek | Detail |
|-------|--------|
| **Nama Proyek** | Website Company Profile Restaurant Monalisa Bogor |
| **Klien / Mitra** | Restaurant Monalisa — Jl. Raya Tajur No. 30, Kota Bogor |
| **Jenis Sistem** | Company Profile Informatif (Non-Transaksional) |
| **Durasi Proyek** | 12 Minggu (April – Juni 2026) |
| **Stack Teknologi** | HTML5, CSS3, JavaScript ES6+ Vanilla, PHP Native, MySQL 8.x |
| **Server** | VPS Linux (Apache/Nginx), konfigurasi manual tanpa container |
| **Status Dokumen** | Draft for Approval -> Konfidensial Internal |

---

## 2. VISI & TUJUAN BISNIS
> **Visi Transformasi Digital:**  
> *"Menghadirkan wajah digital profesional bagi Restaurant Monalisa agar setiap calon pelanggan—warga lokal maupun wisatawan—dapat menemukan, mengenal, dan merasakan kekayaan kuliner Jawa Tengah restoran ini secara online, sebelum mereka memutuskan untuk datang langsung."*

| Kode | Objektif Bisnis | Target Terukur | Prioritas |
|------|----------------|----------------|-----------|
| OBJ-01 | Kehadiran Digital Profesional | Muncul di Google dalam 30 hari pasca-live | TINGGI |
| OBJ-02 | Katalog Menu Online Lengkap | 100% menu tersedia online dengan foto, deskripsi, harga | TINGGI |
| OBJ-03 | Tautan Pemesanan Online | Tombol GoFood & GrabFood redirect ke halaman resmi < 1 detik | TINGGI |
| OBJ-04 | Profil Restoran Informatif | Halaman Tentang Kami komprehensif & mudah dipahami | TINGGI |
| OBJ-05 | Galeri Visual Menarik | Minimal 20 foto berkualitas tinggi | TINGGI |
| OBJ-06 | Pengelolaan Konten Mandiri | Update menu/foto/konten tanpa bantuan teknis | TINGGI |
| OBJ-07 | Peningkatan Jangkauan Pelanggan | >=20% pelanggan baru non-lokal dalam 3 bulan | SEDANG |
| OBJ-08 | Keamanan & Integritas Sistem | Panel admin terproteksi dari SQLi, XSS, CSRF | TINGGI |

---

## 3. SPESIFIKASI MENU (SESUAI PROPOSAL BAB 2.3 & BRD BR-01/BR-02)
> **Instruksi Wajib:** Seluruh struktur, field, dan data awal menu **HARUS** mengikuti spesifikasi berikut secara eksak. Tidak boleh ada penambahan field atau perubahan deskripsi tanpa persetujuan formal.

### Field Database & UI Menu (Wajib)
| Field | Tipe | Keterangan | Sumber Dokumen |
|-------|------|------------|----------------|
| `id` | INT PK AUTO_INCREMENT | Primary Key | BRD BR-02 |
| `nama_menu` | VARCHAR(100) | Nama hidangan | Proposal BAB 2.3 |
| `asal_daerah` | VARCHAR(100) | Asal kuliner (Kudus, Semarang, Kalasan, dll.) | Proposal BAB 2.3 |
| `deskripsi_singkat` | TEXT | Tampilan di Katalog Menu (<=150 karakter) | BRD BR-01 |
| `deskripsi_lengkap` | LONGTEXT | Tampilan di Halaman Detail Menu | BRD BR-01 |
| `bahan_utama` | TEXT | Komposisi utama hidangan | BRD BR-01 |
| `info_alergen` | TEXT | Opsional, jika tersedia | BRD BR-01 |
| `kategori` | VARCHAR(50) | Grup menu (Soto & Sup, Nasi & Utama, Camilan, dll.) | BRD BR-01 |
| `harga` | DECIMAL(10,2) | Harga per porsi (Rp) | BRD BR-01 |
| `foto_url` | VARCHAR(255) | Path file gambar menu | BRD BR-01 |
| `status` | ENUM('aktif','nonaktif') | Kontrol tampilan publik | BRD BR-02 |
| `created_at`, `updated_at` | TIMESTAMP | Audit trail | BRD BR-02 |

### Data Awal Menu (Seed Data Wajib)
Gunakan persis 5 hidangan berikut sebagai dataset awal & contoh konten:
| No | Nama Menu | Asal Daerah | Deskripsi (Sesuai Dokumen) |
|----|-----------|-------------|----------------------------|
| 1 | Soto Kudus | Kudus, Jawa Tengah | Soto ayam kuah bening khas Kudus yang kaya rempah pilihan, disajikan dalam mangkuk kecil dengan taoge segar dan bawang goreng harum. |
| 2 | Lontong Cap Gomeh | Semarang | Hidangan meriah khas peranakan Semarang: lontong, opor ayam gurih, sambal goreng ati, lodeh labu siam, dan telur pindang dalam satu sajian. |
| 3 | Lumpia Semarang | Semarang | Lumpia goreng renyah berisi rebung, udang, dan telur berbumbu khas, disajikan dengan saus manis-pedas dan acar. |
| 4 | Sop Buntut | Jawa Tengah | Sup buntut sapi dengan kuah bening segar dan rempah pilihan, daging empuk lepas dari tulang, disajikan hangat dengan nasi dan emping. |
| 5 | Ayam Kalasan | Kalasan, Jawa Tengah | Ayam goreng bumbu meresap sempurna dengan teknik tradisional, menghasilkan tekstur empuk di dalam dan renyah di luar dengan cita rasa gurih khas. |

---

## 4. RUANG LINGKUP (SCOPE)
### Termasuk (In-Scope)
1. 7 halaman publik: Beranda, Tentang Kami, Katalog Menu, Detail Menu, Galeri Foto, Kontak & Lokasi, Order Online
2. Tombol/banner GoFood & GrabFood (redirect link langsung ke platform masing-masing)
3. Panel Admin dengan 4 modul + Dashboard + Log Aktivitas
4. Sistem keamanan: autentikasi login, proteksi SQL injection, XSS, CSRF
5. Tampilan responsif (desktop, tablet, smartphone)
6. Deployment pada VPS Linux (Apache/Nginx) konfigurasi manual
7. Dokumentasi teknis lengkap: ERD, SRS, user manual, panduan server, BAST, log mingguan

### Tidak Termasuk (Out-of-Scope)
1. Fitur pemesanan langsung, reservasi meja, atau transaksi online di dalam website
2. Integrasi API GoFood/GrabFood atau payment gateway
3. Penggunaan framework PHP (Laravel, CodeIgniter) maupun framework CSS/JS (Bootstrap, React, Tailwind, dll.)
4. Aplikasi mobile native (Android/iOS)
5. Perpanjangan durasi proyek di luar 12 minggu tanpa persetujuan formal

---

## 5. ARSITEKTUR & TEKNOLOGI (WAJIB & TANPA KECUALI)
| Layer | Teknologi | Catatan Kepatuhan |
|-------|-----------|-------------------|
| Frontend | HTML5, CSS3 (Grid & Flexbox native), JavaScript ES6+ Vanilla | Mobile-first, tanpa framework CSS/JS |
| Backend | PHP 8.x Native | Tanpa MVC framework, routing & session manual |
| Database | MySQL 8.x | Relational, query native, prepared statements wajib |
| Server | Apache/Nginx di Linux VPS | Konfigurasi manual, tanpa container/Docker |
| Desain UI/UX | Figma | Wireframe & mockup wajib disetujui klien sebelum coding |

---

## 6. STRUKTUR HALAMAN PUBLIK (DIPERBARUI SESUAI BRD BR-01)
| Halaman | Konten Utama | Tujuan |
|---------|--------------|--------|
| **Beranda** | Hero image restoran, tagline, highlight menu andalan (Soto Kudus, Lumpia Semarang, dll.), pengantar singkat, navigasi, banner Order Online | Kesan pertama & arahan eksplorasi |
| **Tentang Kami** | Sejarah & perjalanan Restaurant Monalisa, nilai & filosofi, visi kuliner, keunggulan (BRD BAGIAN 1) | Membangun kepercayaan & koneksi emosional |
| **Katalog Menu** | Daftar 5+ menu dengan foto, nama, asal daerah, deskripsi singkat, kategori, harga; filter kategori responsif | Membantu perencanaan kunjungan |
| **Detail Menu** | Foto besar, deskripsi lengkap, asal daerah, bahan utama, info alergen (opsional) | Informasi mendalam per hidangan |
| **Galeri Foto** | Grid foto suasana, hidangan, area dining; efek hover + lightbox JS vanilla | Daya tarik visual pengalaman bersantap |
| **Kontak & Lokasi** | Alamat lengkap (Jl. Raya Tajur No. 30), telepon, jam operasional (07.00–22.00 WIB), embed peta, formulir pesan | Kemudahan kontak & navigasi |
| **Order Online** | Tombol/banner GoFood & GrabFood menonjol -> redirect langsung ke halaman resmi | Memudahkan pemesanan tanpa integrasi API |

---

## 7. PANEL ADMIN (CMS) (DIPERBARUI SESUAI BRD BR-02)
| Modul | Fungsi |
|-------|--------|
| **Dashboard** | Ringkasan: jumlah menu aktif, foto galeri, status halaman publik |
| **Manajemen Menu** | CRUD menu + foto, kategori, deskripsi singkat/lengkap, bahan utama, harga, status aktif/nonaktif |
| **Manajemen Galeri** | Unggah, atur urutan, hapus foto |
| **Manajemen Konten** | Edit teks Beranda, Tentang Kami, Kontak tanpa edit kode |
| **Manajemen Akun** | Ubah username & password admin |
| **Log Aktivitas** | Pencatatan otomatis setiap perubahan konten (tambah/edit/hapus) |
| **UX Admin** | Tabel & form bersih, intuitif untuk staf non-teknis, notifikasi aksi jelas |

---

## 8. KEAMANAN & PERFORMA (BR-04)
| Aspek | Implementasi Wajib |
|-------|-------------------|
| **Autentikasi** | `password_hash()` (bcrypt), session timeout otomatis |
| **Database** | `prepared statements` di semua query -> proteksi SQL Injection |
| **Output** | `htmlspecialchars()` + sanitasi input/output -> proteksi XSS |
| **Form Admin** | CSRF token validasi di setiap aksi -> proteksi CSRF |
| **Akses** | Middleware autentikasi: halaman admin terkunci tanpa login |
| **Upload** | Validasi tipe & ukuran file -> cegah unggahan berbahaya |
| **Performa** | Load time <= 3 detik (koneksi standar), uptime >= 99% (30 hari pertama) |
| **Kompatibilitas** | Chrome, Firefox, Safari, Edge; desktop & mobile |
| **Bug** | 0 bug kritis pasca go-live |

---

## 9. JADWAL & FASE PENGEMBANGAN (12 MINGGU)
| Periode | Fase Utama | Kegiatan Kunci |
|---------|------------|----------------|
| **M1–M2** (Apr) | Planning & Requirement | Pembentukan tim, WBS, analisis kebutuhan konten/visual/sistem |
| **M2–M4** (Apr) | Design | ERD database, wireframe & mockup Figma, finalisasi desain & approval |
| **M5–M8** (Mei) | Implementasi | Setup VPS, backend (PHP+MySQL) & frontend (HTML/CSS/JS) paralel, laporan kemajuan M8 |
| **M7–M10** (Mei–Jun) | Testing | Unit testing, integration & system testing, cross-browser & responsive testing, UAT |
| **M11** (Jun) | Deployment & Pelatihan | Deploy ke VPS produksi, pelatihan staf penggunaan panel admin |
| **M12** (Jun) | Serah Terima & Monitoring | Monitoring pasca-launch, perbaikan bug minor, finalisasi laporan, BAST |

---

## 10. STRUKTUR TIM & TANGGUNG JAWAB
| Nama | NPM | Peran | Tanggung Jawab Utama |
|------|-----|-------|----------------------|
| Muhammad Najwan Busaman | 231106040884 | Project Manager | Manajemen stakeholder, timeline, sprint review mingguan, koordinasi bisnis-teknis, serah terima & BAST |
| Irgi Febryansyah | 231106040944 | Frontend & UI/UX Designer | Figma design system, implementasi HTML/CSS/JS vanilla responsif, seluruh halaman publik |
| Muhammad Rama | 231106040937 | Backend & Database Developer | Arsitektur MySQL, logika PHP native, CRUD admin, keamanan sistem, dokumentasi database & server |
| Razan Dzakisetiawan | 231106040864 | QA Tester & Technical Writer | Test plan, functional/cross-browser testing, bug tracking, user manual, panduan teknis, BAST |

---

## 11. ANGGARAN & SKEMA PEMBAYARAN
| Komponen | Rincian | Total (Rp) |
|----------|---------|------------|
| **Honor Tenaga Ahli** | PM (3x5.5jt), Frontend (3x4.5jt), Backend (3x4.5jt), QA (3x4jt) | 55.500.000 |
| **Biaya Operasional** | VPS 3bln (4.5jt), Tools (2jt), Foto Konten (2jt), Transport (2.1jt), Cetak (0.8jt) | 11.400.000 |
| **Cadangan 10%** | Antisipasi risiko & biaya tak terduga | 6.690.000 |
| **TOTAL ANGGARAN** | | **73.590.000** |

### Tahap Pembayaran (30% – 40% – 30%)
| Tahap | Pemicu | Persentase | Estimasi (Rp) |
|-------|--------|------------|---------------|
| **1** | Dokumen proposal disetujui & BAST Tahap 1 ditandatangani | 30% | 22.077.000 |
| **2** | Laporan Kemajuan M8 (desain Figma approved, frontend/backend 50%) | 40% | 29.436.000 |
| **3** | UAT selesai, website live, pelatihan selesai, BAST Final & laporan akhir | 30% | 22.077.000 |

> **Catatan:** Tahap 1 **bukan DP**. Setiap tahap wajib disertai BAST tertanda tangan.

---

## 12. KRITERIA KEBERHASILAN & KPI
### KPI Fungsional & Operasional (30 Hari Pasca-Launch)
| KPI | Target | Verifikasi |
|-----|--------|------------|
| Aksesibilitas menu online | 100% tersedia 24/7 | Audit halaman katalog |
| Kelengkapan profil digital | 7 halaman + tombol order | Checklist deployment |
| Galeri foto online | >=20 foto berkualitas | Hitung manual |
| Update menu mandiri | Staf bisa tanpa bantuan teknis | Observasi sesi latihan |
| Visibilitas Google | Muncul dalam 30 hari | Search keyword nama restoran |
| Respon formulir | Diproses <24 jam | Uji kirim & verifikasi |

### KPI Kualitas Sistem
| KPI | Target | Verifikasi |
|-----|--------|------------|
| Uptime | >=99% | Monitoring server |
| Bug kritis pasca go-live | 0 | Laporan QA & ticket |
| UAT sign-off rate | 100% skenario lulus | Laporan UAT tertanda tangan |
| Waktu muat halaman | <=3 detik | Load testing Chrome/Firefox |
| Kepuasan admin (staf) | >=80% puas/sangat puas | Survei Likert 1–5 |

### KPI Bisnis (3 Bulan Pertama)
- Adopsi panel admin: 100% pengelola aktif
- Kemandirian update konten: 100% tanpa bantuan teknis
- Peningkatan pelanggan non-lokal: >=20%
- Penurunan pertanyaan telepon: >=50%

### KRITERIA GO / NO-GO (6 Poin Wajib)
1. 7 halaman publik error-free di >=3 browser (desktop & mobile)
2. Tombol GoFood/GrabFood redirect ke halaman restoran yang benar
3. Panel admin login, CRUD, unggah foto, update konten tanpa bug kritis
4. Load time <=3 detik pada koneksi standar
5. 100% skenario UAT lulus & ditandatangani pihak restoran
6. Pelatihan penggunaan panel admin untuk staf selesai dilaksanakan

---

## 13. MANAJEMEN RISIKO, CONSTRAINT & ASUMSI
### Matriks Risiko Utama
| ID | Risiko | Level | Mitigasi |
|----|--------|-------|----------|
| R-01 | Konten restoran terlambat | TINGGI | Checklist konten wajib M1–M2, PM monitor mingguan |
| R-04 | Scope creep (reservasi/order) | TINGGI | Dokumentasi scope eksplisit, Change Request Form wajib |
| R-06 | Kerentanan keamanan admin | TINGGI | Prepared statements, sanitasi, CSRF, code review keamanan |
| R-07 | Kehilangan data website | TINGGI | Backup otomatis harian, prosedur restore terdokumentasi |
| R-08 | Koneksi internet tidak stabil saat pelatihan | RENDAH | Demo localhost, rekam video panduan sebagai backup |

### Constraint
- Durasi tetap 12 minggu
- Stack teknologi fixed (vanilla only)
- Garansi perbaikan bug: 30 hari pasca-deployment

### Asumsi
- Klien menyediakan foto & teks konten dalam 2 minggu pertama
- Figma disetujui sebelum development
- VPS mendukung PHP 8.x & MySQL 8.x
- Staf terlibat aktif di UAT & pelatihan

---

## 14. DELIVERABLES & DOKUMENTASI
1. Source code lengkap (struktur rapi, komentar, config terpisah)
2. Skema database, ERD, & SQL dump siap impor (termasuk seed 5 menu sesuai Proposal)
3. Panduan instalasi & hardening VPS Linux
4. User Manual Panel Admin (Bahasa Indonesia, non-teknis, step-by-step)
5. Test Plan & Laporan UAT (ceklist skenario, hasil cross-browser/responsive)
6. Template BAST & panduan sign-off
7. Log dokumentasi mingguan (periode, kegiatan, deliverable, kendala, solusi, rencana)
8. Laporan Awal (M1), Laporan Kemajuan (M8), Laporan Akhir (M12)

---

## 15. INSTRUKSI EKSEKUSI (PROMPT UNTUK AI/DEVELOPER)
> **Peran:** Bertindak sebagai Senior Full-Stack Web Developer & Security-Aware Architect.  
> **Bahasa:** Gunakan Bahasa Indonesia untuk UI publik, panel admin, dan seluruh dokumentasi.  
> **Konten:** Gunakan placeholder `[CONTOH: ...]` untuk teks klien, link GoFood/GrabFood, dan foto. **Kecuali 5 menu utama** yang WAJIB menggunakan deskripsi persis dari Proposal BAB 2.3.  
> **Kepatuhan:** Jangan gunakan dependency eksternal. Semua kode harus modular, terdokumentasi, dan siap audit. Prioritaskan keamanan & performa sejak baris pertama.

### Struktur Output Per Fase