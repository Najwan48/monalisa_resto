-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: monalisa_resto
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `monalisa_resto`;
USE `monalisa_resto`;

--
-- Table structure for table `galeri`
--

DROP TABLE IF EXISTS `galeri`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `galeri` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(100) NOT NULL,
  `foto_url` varchar(255) NOT NULL,
  `urutan` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galeri`
--

LOCK TABLES `galeri` WRITE;
/*!40000 ALTER TABLE `galeri` DISABLE KEYS */;
INSERT INTO `galeri` VALUES (1,'Selamat datang di Monalisa Resto, perpaduan kenyamanan dan kelezatan.','assets/images/galeri/IMG_4222.webp',1,'2026-05-12 03:47:49'),(2,'Ruang makan yang luas dan nyaman untuk berkumpul bersama keluarga.','assets/images/galeri/IMG_4223.webp',2,'2026-05-12 03:47:49'),(3,'Suasana pagi yang cerah di sudut favorit Monalisa Resto.','assets/images/galeri/IMG_4224.webp',3,'2026-05-12 03:47:49'),(4,'Berbagai pilihan camilan tradisional tersedia untuk melengkapi hidangan Anda.','assets/images/galeri/IMG_4226.webp',4,'2026-05-12 03:47:49'),(5,'Sentuhan seni klasik yang menambah kehangatan suasana interior kami.','assets/images/galeri/IMG_4231.webp',5,'2026-05-12 03:47:49'),(6,'Cahaya alami yang mempercantik setiap sudut ruang makan kami.','assets/images/galeri/IMG_4232.webp',6,'2026-05-12 03:47:49');
/*!40000 ALTER TABLE `galeri` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `konten_halaman`
--

DROP TABLE IF EXISTS `konten_halaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `konten_halaman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `halaman` varchar(50) NOT NULL,
  `bagian` varchar(50) NOT NULL,
  `isi` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_konten` (`halaman`,`bagian`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `konten_halaman`
--

LOCK TABLES `konten_halaman` WRITE;
/*!40000 ALTER TABLE `konten_halaman` DISABLE KEYS */;
INSERT INTO `konten_halaman` VALUES (1,'beranda','tagline','Kekayaan Kuliner Jawa Tengah di Jantung Kota Bogor','2026-05-12 03:47:49'),(2,'beranda','pengantar','Selamat datang di Restaurant Monalisa, destinasi kuliner yang menyajikan cita rasa autentik khas Jawa Tengah. Nikmati hidangan legendaris kami dalam suasana yang nyaman dan hangat.','2026-05-12 03:47:49'),(3,'tentang_kami','sejarah','Sejak tahun 1972, Restoran Monalisa telah menjadi ikon kuliner di Jalan Raya Tajur, Bogor. Di tengah bermunculannya kafe-kafe kekinian, restoran ini tetap bertahan dengan pesona klasiknya. Monalisa bukan sekadar tempat makan, melainkan ruang nostalgia bagi banyak keluarga dan wisatawan yang melintasi jalur Bogor-Puncak.\n\nDaya tarik utamanya terletak pada menu lintas budaya yang unik, memadukan cita rasa Semarang, Sunda, dan Chinese Food. Hidangan seperti Lontong Cap Gomeh dan Lumpia Semarang menjadi menu wajib yang kualitas rasanya tetap terjaga selama puluhan tahun. Selain makanannya, fasilitas area parkir yang sangat luas dan lokasinya yang menyatu dengan hotel menjadikannya tempat transit paling ideal bagi rombongan besar maupun bus pariwisata. Restoran Monalisa adalah bukti nyata bahwa dedikasi pada tradisi dan pelayanan mampu membuat sebuah bisnis tetap relevan melintasi zaman.','2026-05-12 03:47:49'),(4,'tentang_kami','visi','Monalisa hadir dengan visi sederhana namun bermakna: membawa kehangatan rumah ke meja makan Anda. Melalui resep legendaris yang terjaga sejak puluhan tahun lalu, kami ingin setiap tamu merasakan keaslian cita rasa Semarang dan Nusantara di ruang yang luas namun tetap terasa akrab, menjadikannya tempat pulang yang selalu dirindukan bagi keluarga dan rombongan.','2026-05-12 03:47:49'),(5,'kontak','alamat','Jl. Raya Tajur No.30, RT.04/RW.01, Pakuan, Kec. Bogor Sel., Kota Bogor, Jawa Barat 16134','2026-05-12 03:47:49'),(6,'kontak','telepon','+62 812-8114-1923','2026-05-12 03:47:49'),(7,'kontak','jam_operasional','Setiap Hari: 08.00 - 21.00 WIB','2026-05-12 03:47:49'),
(8,'beranda','link_gofood','https://gofood.link/a/BMMv8Pb','2026-05-12 03:47:49'),
(9,'beranda','link_grabfood','https://r.grab.com/g/6-20260510_203031_8a7e66d9e9694765be4c04cda49c0859_MEXMPS-6-C2XANPEKCVDGNT','2026-05-12 03:47:49'),
(10,'kontak','whatsapp','081281141923','2026-05-12 03:47:49');
/*!40000 ALTER TABLE `konten_halaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `attempt_time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_ip_address` (`ip_address`),
  KEY `idx_attempt_time` (`attempt_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `log_aktivitas`
--

DROP TABLE IF EXISTS `log_aktivitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_aktivitas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `aksi` varchar(255) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_aktivitas`
--

LOCK TABLES `log_aktivitas` WRITE;
/*!40000 ALTER TABLE `log_aktivitas` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_aktivitas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_menu` varchar(100) NOT NULL,
  `asal_daerah` varchar(100) NOT NULL,
  `deskripsi_singkat` text NOT NULL,
  `deskripsi_lengkap` longtext NOT NULL,
  `bahan_utama` text NOT NULL,
  `info_alergen` text DEFAULT NULL,
  `kategori` varchar(50) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `foto_url` varchar(255) NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,'Soto Kudus','Bogor','Soto Kudus lezat khas Monalisa Resto.','Soto Kudus adalah salah satu menu favorit kami yang disajikan dengan cita rasa autentik.','-','-','SUP & SOTO',27500.00,'assets/images/menu/soto_kudus.webp','aktif','2026-05-12 04:30:25','2026-05-12 04:35:05'),(2,'Ayam Kalasan','Bogor','Ayam Kalasan lezat khas Monalisa Resto.','Ayam Kalasan adalah salah satu menu favorit kami yang disajikan dengan cita rasa autentik.','-','-','AYAM',55000.00,'assets/images/menu/ayam_kalasan.webp','aktif','2026-05-12 04:30:22','2026-05-12 04:35:05'),(3,'Lontong Cap Gomeh','Bogor','Lontong Cap Gomeh lezat khas Monalisa Resto.','Lontong Cap Gomeh adalah salah satu menu favorit kami yang disajikan dengan cita rasa autentik.','-','-','NASI & LONTONG',45000.00,'assets/images/menu/lontong_cap_gomeh.webp','aktif','2026-05-12 04:30:21','2026-05-12 04:32:27'),(4,'Lumpia Semarang','Bogor','Lumpia Semarang lezat khas Monalisa Resto.','Lumpia Semarang adalah salah satu menu favorit kami yang disajikan dengan cita rasa autentik.','-','-','SNACK',37500.00,'assets/images/menu/lumpia_semarang.webp','aktif','2026-05-12 04:30:25','2026-05-12 04:35:05'),(5,'Sop Buntut','Bogor','Sop Buntut lezat khas Monalisa Resto.','Sop Buntut adalah salah satu menu favorit kami yang disajikan dengan cita rasa autentik.','-','-','SUP & SOTO',75000.00,'assets/images/menu/sop_buntut.webp','aktif','2026-05-12 04:30:25','2026-05-12 04:35:05');
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$kWaACjkZUFSeF5ZqK3CMKuM0Lt13M2wqlYzt9.wd0fhq2pCzQPoPi','2026-05-12 03:47:49');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-12 11:37:37

ALTER TABLE log_aktivitas ADD COLUMN ip_address VARCHAR(45) AFTER aksi;
