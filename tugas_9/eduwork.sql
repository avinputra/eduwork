-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2026 at 07:34 PM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eduwork`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id_cart` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id_cart`, `id_product`, `quantity`, `session_id`, `created_at`) VALUES
(2, 4, 2, '7adedfc774193dd722b5614567867829', '2026-11-19 18:27:13');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id_product` int(11) NOT NULL,
  `nama_product` varchar(100) NOT NULL,
  `deskripsi_product` text NOT NULL,
  `gambar1_product` varchar(255) DEFAULT NULL,
  `gambar2_product` varchar(255) DEFAULT NULL,
  `gambar3_product` varchar(255) DEFAULT NULL,
  `kategori_product` int(11) NOT NULL,
  `harga_product` decimal(15,2) NOT NULL,
  `stok_product` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id_product`, `nama_product`, `deskripsi_product`, `gambar1_product`, `gambar2_product`, `gambar3_product`, `kategori_product`, `harga_product`, `stok_product`, `created_at`, `updated_at`) VALUES
(1, 'kaos polos dewasa', 'BARANG SEMUA RAEDY AKUN AKTIF SETIAP HARI JANGAN RAGU ORDER. PENGIRIMAN BARANG SETIAP HARI.\r\n\r\n\r\n\r\n	BISA PENGIRIMAN INSTAN JIKALAU PAKE INSTAN\r\n\r\n\r\n\r\n	KAOS OBLONG INI COCOK UNTUK SEMUA COWOK. ATAU CEWEK.\r\n\r\n	DAN COCOK UNTUK SEMUA KONDISI\r\n\r\n	BAHAN LEMBUT NYAMAN DI PAKE TIDAK PANAS.\r\n\r\n	PRODUK PT INDONESIA\r\n\r\n	JAHITAN RAPI DAN BERGERANSI\r\n\r\n\r\n\r\n	(UKURAN PANJANG LEBAR BAJU)\r\n\r\n\r\n\r\n	M. LD 84.PANJANG 63. 35-40 kg\r\n\r\n	L. LD 96.PANJANG 65. 40-55 kg\r\n\r\n	XL.LD 100.PANJANG 68. 55-70 kg\r\n\r\n	XXL. LD 114.PANJANG 75. 70-100 kg\r\n\r\n	3XL LD 122 PANJANG 76. 100-120 KG \r\n\r\n\r\n\r\n	1 kg isi 7 baju', 'P1_G1.webp', 'P1_G2.webp', 'P1_G3.webp', 1, 15000.00, 50, '2026-11-19 17:10:31', '2026-11-19 17:10:31'),
(2, 'KEMEJA PRIA MENS POLOSAN LENGAN PANJANG', 'SIZE STANDAR INDONESIA\r\n\r\n	Bahan : Catton Signature Original, Halus lembut adem dan nyaman\r\n\r\n\r\n\r\n	👉Dimensi ukuran :\r\n\r\n\r\n\r\n\r\n\r\n	✓size M lebar dada 48cm panjang baju 70cm lingkar badan 100cm\r\n\r\n	✓size L lebar dada 50cm panjang baju 74cm lingkar badan 104cm\r\n\r\n	✓size XL lebar dada 53cm panjang baju 74cm lingkar badan 108cm\r\n\r\n	✓size XXL lebar dada 55cm panjang baju 76cm lingkar badan 112cm\r\n\r\n	✓size XXXL lebar dada 56cm panjang baju 78cm lingkar badan 114cm\r\n\r\n\r\n\r\n	NB  : TOLERANSI SIZE 1-3cm ya kak\r\n\r\n\r\n\r\n	✓Polos dan simple model kerah ada variasi kancing  kecil dan ada saku sebelah kiri..\r\n\r\n	Cocok buat pakaian kerja, ngampus, santai nongkrong maupun acara Formal rekomended\r\n\r\n	pilih varian warna dan tambah koleksi kemeja kesukaan', 'P2_G1.webp', 'P2_G2.webp', 'P2_G3.webp', 1, 28000.00, 100, '2026-11-19 17:13:50', '2026-11-19 17:13:50'),
(3, 'Dress Natasya Motif Kekinian', 'Detail bahan\r\n\r\n	BAhan :katun mikro\r\n\r\n	lingkar dada:100cm\r\n\r\n	panjang baju:100cm\r\n\r\n\r\n\r\n	99%READY Y SAY.....BURUAN DIORDER SAY SBLM KEHABISAN...\r\n\r\n\r\n\r\n	 MOHON DIBACA TERLEBIH DAHULU SEBELUM MELAKUKAN PEMBELIAN \r\n\r\n\r\n\r\n	- JIKA ADA KESALAHAN DALAM PENGIRIMAN TOLONG LAMPIRKAN VIDEO KETIKA PAKET DIBUKA DAN SILAHKAN LANGSUNG MENGHUBUNGI ADMIN\r\n\r\n\r\n\r\n	- JIKA TIDAK MELAMPIRKAN YANG KAMI MINTA, CASE AKAN KAMI CANCEL KARNA TIDAK MEMENUHI SYARAT :)\r\n\r\n	JADI MOHON KERJA SAMANYA YA, AGAR SEGERA KAMI PROSES :)\r\n\r\n\r\n\r\n	 TIDAK MENERIMA PESANAN MELALUI CATATAN DAN CHAT \r\n\r\n\r\n\r\n	Contoh : Pesan 5 PUTIH , tapi DICATATAN PINK 1 , MUSTARD 2 , ARMY 1, NAVY 1\r\n\r\n\r\n\r\n	Dikarenakan agar stock kami tetap balance ( seimbang / tidak kacau ), jika sudah membeli berarti anda SETUJU dengan\r\n\r\n	ketentuan dan syarat yang sudah kami berikan :)\r\n\r\n\r\n\r\n	 MOHON UNTUK MENGISI ALAMAT TUJUAN DENGAN LENGKAP DAN BENAR, DI SARANKAN DENGAN RT/RW DAN JUGA KODE POS DAN SERTAKAN NO HANDPHONE YANG SELALU AKTIF! \r\n\r\n\r\n\r\n	Agar dapat di hubungi jika ada kendala dalam proses pencarian lokasi penerimaan\r\n\r\n	paket:)\r\n\r\n\r\n\r\n	 MEMBELI = SETUJU \r\n\r\n\r\n\r\n\r\n\r\n	 PENGIRIMAN SENIN - SABTU \r\n\r\n	( SABTU ORDERAN YANG MASUK DIATAS JAM 13.00 , AKAN KAMI PROSES DI HARI SENIN )\r\n\r\n	 BARANG DIKIRIM H+1 SETELAH TRANSFER \r\n\r\n	 JIKA ADA BARANG YANG KOSONG / HABIS MAKA AKAN KAMI INFOKAN TERLRBIH DAHULU', 'P3_G1.webp', 'P3_G2.webp', 'P3_G3.webp', 2, 37000.00, 123, '2026-11-19 17:16:07', '2026-11-19 18:22:56'),
(4, 'Legging soft Rayon Kaos L - XXXL/ Legging Panjang Bahan Kaos', 'Be a Smart Buyer, \"Mohon dibaca dulu baik-baik ya kk cantik, agar menghindari Salah size/ukuran, Terimakasih 🙏 \" \r\n\r\n\r\n\r\n	New Collection Legging Kaos Premium\r\n\r\n	Packing 2 Lapis & Dilengkapi Quality Control \r\n\r\n	Streetch & Tidak membuat lipatan perut terlihat\r\n\r\n	Bahan Lembut Dan Halus,-', 'P4_G1.webp', 'P4_G2.webp', 'P4_G3.webp', 2, 22000.00, 42, '2026-11-19 17:17:58', '2026-11-19 18:28:26'),
(5, 'HP REDMI 15C NFC RAM 8/256GB BATTERY 6000mAh LAYAR 6,74INCH', 'DESKRIPSI :\r\n\r\n	Network	\r\n\r\n	Technology	                          GSM / LTE\r\n\r\n	Body	\r\n\r\n	Dimensions	                          171.56 x 79.47 x 7.99 mm\r\n\r\n	Weight	                                  205 g \r\n\r\n	SIM	                                          Nano-SIM + Nano-SIM\r\n\r\n	Display	\r\n\r\n	Type	                                   IPS LCD, 120Hz, 660 nits (typ), 810 nits (HBM)\r\n\r\n	Size                                            6.9 inches\r\n\r\n	Resolution	                           720 x 1600 pixels\r\n\r\n	Platform	\r\n\r\n	OS	                                           Android 15, HyperOS\r\n\r\n	Chipset	                                   Mediatek Helio G81 Ultra Octa-core\r\n\r\n	CPU	                                           (2x2.0 GHz Cortex-A75 & 6x1.8 GHz Cortex-A55)\r\n\r\n	GPU	                                           Mali-G52 MC2\r\n\r\n	Memory\r\n\r\n	Card slot	                           microSDXC (dedicated slot)\r\n\r\n	Internal	                                   256GB 8GB RAM\r\n\r\n												  eMMC 5.1\r\n\r\n	Main Camera	\r\n\r\n	Single	                                    50 MP, f/1.8, 28mm (wide), PDAF\r\n\r\n														Auxiliary lens\r\n\r\n	Features	                                    LED flash, HDR\r\n\r\n	Video	                                    1080p@30fps\r\n\r\n	Selfie camera	\r\n\r\n	Single	                                   8 MP, f/2.0, (wide)\r\n\r\n	Features	                                    HDR\r\n\r\n	Video	                                    1080p@30fps\r\n\r\n	Sound	\r\n\r\n	Loudspeaker                             Yes\r\n\r\n	3.5mm jack	                             Yes\r\n\r\n	Comms	\r\n\r\n	WLAN	                                      Wi-Fi 802.11 a/b/g/n/ac, dual-band\r\n\r\n	Bluetooth	                              5.4, A2DP, LE\r\n\r\n	Positioning	                              GPS, GLONASS, GALILEO, BDS\r\n\r\n	NFC	                                              Yes (market/region dependent)\r\n\r\n	Radio	                                      FM radio\r\n\r\n	USB	                                              USB Type-C 2.0\r\n\r\n	Features	\r\n\r\n	Sensors	                                       Fingerprint (side-mounted), accelerometer, compass\r\n\r\n													 Virtual proximity sensing\r\n\r\n	Battery	\r\n\r\n	Type	                                       6000 mAh\r\n\r\n	Charging	                               33W wired, PD\r\n\r\n	Misc	\r\n\r\n	Colors	                                        Midnight Black, Mint Green, Moonlight Blue, Twilight Orange', 'P5_G1.webp', 'P5_G2.webp', NULL, 3, 1988000.00, 98, '2026-11-19 17:19:35', '2026-11-19 17:37:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id_cart`),
  ADD KEY `id_product` (`id_product`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id_product`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id_cart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `product` (`id_product`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
