CREATE DATABASE IF NOT EXISTS monalisa_resto;
USE monalisa_resto;

DROP TABLE IF EXISTS log_aktivitas;
DROP TABLE IF EXISTS galeri;
DROP TABLE IF EXISTS konten_halaman;
DROP TABLE IF EXISTS menu;
DROP TABLE IF EXISTS users;

CREATE TABLE IF NOT EXISTS menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_menu VARCHAR(100) NOT NULL,
    asal_daerah VARCHAR(100) NOT NULL,
    deskripsi_singkat TEXT NOT NULL,
    deskripsi_lengkap LONGTEXT NOT NULL,
    bahan_utama TEXT NOT NULL,
    info_alergen TEXT,
    kategori VARCHAR(50) NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    foto_url VARCHAR(255) NOT NULL,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS galeri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(100) NOT NULL,
    foto_url VARCHAR(255) NOT NULL,
    urutan INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS konten_halaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    halaman VARCHAR(50) NOT NULL,
    bagian VARCHAR(50) NOT NULL,
    isi TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_konten (halaman, bagian)
);

CREATE TABLE IF NOT EXISTS log_aktivitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    aksi VARCHAR(255) NOT NULL,
    waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

INSERT IGNORE INTO users (username, password_hash) VALUES 
('admin', '$2y$10$kWaACjkZUFSeF5ZqK3CMKuM0Lt13M2wqlYzt9.wd0fhq2pCzQPoPi');

INSERT IGNORE INTO menu (nama_menu, asal_daerah, deskripsi_singkat, deskripsi_lengkap, bahan_utama, info_alergen, kategori, harga, foto_url, status) VALUES
('Soto Kudus', 'Kudus, Jawa Tengah', 'Soto ayam kuah bening khas Kudus yang kaya rempah pilihan, disajikan dalam mangkuk kecil dengan taoge segar dan bawang goreng harum.', 'Soto ayam kuah bening khas Kudus yang kaya rempah pilihan, disajikan dalam mangkuk kecil dengan taoge segar dan bawang goreng harum. Cita rasanya gurih manis yang khas, sangat cocok dinikmati kapan saja.', 'Ayam, Bawang Merah, Bawang Putih, Kunyit, Jahe, Kemiri, Taoge', 'Tidak ada', 'Soto & Sup', 25000.00, 'assets/images/soto_kudus.jpg', 'aktif'),
('Lontong Cap Gomeh', 'Semarang', 'Hidangan meriah khas peranakan Semarang: lontong, opor ayam gurih, sambal goreng ati, lodeh labu siam, dan telur pindang dalam satu sajian.', 'Hidangan meriah khas peranakan Semarang: lontong, opor ayam gurih, sambal goreng ati, lodeh labu siam, dan telur pindang dalam satu sajian. Harmoni rasa gurih, pedas, dan sedikit manis yang kaya.', 'Lontong, Ayam, Hati Sapi, Labu Siam, Telur, Santan, Rempah', 'Kacang (jika menggunakan bubuk kedelai)', 'Nasi & Utama', 35000.00, 'assets/images/lontong_cap_gomeh.jpg', 'aktif'),
('Lumpia Semarang', 'Semarang', 'Lumpia goreng renyah berisi rebung, udang, dan telur berbumbu khas, disajikan dengan saus manis-pedas dan acar.', 'Lumpia goreng renyah berisi rebung, udang, dan telur berbumbu khas, disajikan dengan saus manis-pedas dan acar. Kuliner otentik yang wajib dicoba sebagai camilan atau hidangan pembuka.', 'Rebung, Udang, Telur, Kulit Lumpia', 'Udang, Telur', 'Camilan', 15000.00, 'assets/images/lumpia_semarang.jpg', 'aktif'),
('Sop Buntut', 'Jawa Tengah', 'Sup buntut sapi dengan kuah bening segar dan rempah pilihan, daging empuk lepas dari tulang, disajikan hangat dengan nasi dan emping.', 'Sup buntut sapi dengan kuah bening segar dan rempah pilihan, daging empuk lepas dari tulang, disajikan hangat dengan nasi dan emping. Sangat cocok dinikmati bersama keluarga.', 'Buntut Sapi, Wortel, Kentang, Seledri, Bawang Merah, Bawang Putih, Pala, Cengkeh', 'Tidak ada', 'Soto & Sup', 65000.00, 'assets/images/sop_buntut.jpg', 'aktif'),
('Ayam Kalasan', 'Kalasan, Jawa Tengah', 'Ayam goreng bumbu meresap sempurna dengan teknik tradisional, menghasilkan tekstur empuk di dalam dan renyah di luar dengan cita rasa gurih khas.', 'Ayam goreng bumbu meresap sempurna dengan teknik tradisional, menghasilkan tekstur empuk di dalam dan renyah di luar dengan cita rasa gurih khas. Disajikan dengan sambal dan lalapan.', 'Ayam, Air Kelapa, Bawang Putih, Ketumbar, Gula Merah', 'Tidak ada', 'Nasi & Utama', 30000.00, 'assets/images/ayam_kalasan.jpg', 'aktif');

INSERT IGNORE INTO konten_halaman (halaman, bagian, isi) VALUES
('beranda', 'tagline', 'Kekayaan Kuliner Jawa Tengah di Jantung Kota Bogor'),
('beranda', 'pengantar', 'Selamat datang di Restaurant Monalisa, destinasi kuliner yang menyajikan cita rasa autentik khas Jawa Tengah. Nikmati hidangan legendaris kami dalam suasana yang nyaman dan hangat.'),
('tentang_kami', 'sejarah', 'Sejak tahun 1972, Restoran Monalisa telah menjadi ikon kuliner di Jalan Raya Tajur, Bogor. Di tengah bermunculannya kafe-kafe kekinian, restoran ini tetap bertahan dengan pesona klasiknya. Monalisa bukan sekadar tempat makan, melainkan ruang nostalgia bagi banyak keluarga dan wisatawan yang melintasi jalur Bogor-Puncak.\n\nDaya tarik utamanya terletak pada menu lintas budaya yang unik, memadukan cita rasa Semarang, Sunda, dan Chinese Food. Hidangan seperti Lontong Cap Gomeh dan Lumpia Semarang menjadi menu wajib yang kualitas rasanya tetap terjaga selama puluhan tahun. Selain makanannya, fasilitas area parkir yang sangat luas dan lokasinya yang menyatu dengan hotel menjadikannya tempat transit paling ideal bagi rombongan besar maupun bus pariwisata. Restoran Monalisa adalah bukti nyata bahwa dedikasi pada tradisi dan pelayanan mampu membuat sebuah bisnis tetap relevan melintasi zaman.'),
('tentang_kami', 'visi', 'Monalisa hadir dengan visi sederhana namun bermakna: membawa kehangatan rumah ke meja makan Anda. Melalui resep legendaris yang terjaga sejak puluhan tahun lalu, kami ingin setiap tamu merasakan keaslian cita rasa Semarang dan Nusantara di ruang yang luas namun tetap terasa akrab, menjadikannya tempat pulang yang selalu dirindukan bagi keluarga dan rombongan.'),
('kontak', 'alamat', 'Jl. Raya Tajur No.30, RT.04/RW.01, Pakuan, Kec. Bogor Sel., Kota Bogor, Jawa Barat 16134'),
('kontak', 'telepon', '0812-3456-7890'),
('kontak', 'jam_operasional', 'Setiap Hari: 07.00 - 22.00 WIB');
