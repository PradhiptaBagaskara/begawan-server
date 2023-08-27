-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: Aug 27, 2023 at 05:51 PM
-- Server version: 5.7.43
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";


START TRANSACTION;


SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blanslil_begawan`
--
 -- --------------------------------------------------------
 --
-- Table structure for table `gaji`
--

CREATE TABLE `gaji` ( `id` bigint(20) NOT NULL,
                                      `gaji` varchar(254) DEFAULT NULL,
                                                                  `keterangan` varchar(254) DEFAULT NULL,
                                                                                                    `id_user` bigint(20) DEFAULT NULL,
                                                                                                                                 `id_proyek` bigint(20) DEFAULT NULL,
                                                                                                                                                                `id_pemilik` bigint(20) DEFAULT NULL,
                                                                                                                                                                                                `created_date` varchar(254) DEFAULT NULL,
                                                                                                                                                                                                                                    `file_name` text, `bulan` varchar(254) DEFAULT NULL) ENGINE=InnoDB DEFAULT
CHARSET=latin1;

--
-- Dumping data for table `gaji`
--

INSERT INTO `gaji` (`id`, `gaji`, `keterangan`, `id_user`, `id_proyek`, `id_pemilik`, `created_date`, `file_name`, `bulan`)
VALUES (1,
        '1000000',
        'rwe',
        3,
        3,
        2,
        NULL,
        'download2.jpeg',
        '2023-08'), (2,
                     '900000',
                     'ddd',
                     3,
                     3,
                     2,
                     NULL,
                     'download3.jpeg',
                     '2023-08'), (3,
                                  '50000',
                                  'mmm',
                                  2,
                                  3,
                                  2,
                                  NULL,
                                  'download4.jpeg',
                                  '2023-08');

-- --------------------------------------------------------
 --
-- Table structure for table `khas_history`
--

CREATE TABLE `khas_history` ( `id` bigint(20) NOT NULL,
                                              `id_pemodal` bigint(20) DEFAULT NULL,
                                                                              `saldo_awal` varchar(254) DEFAULT NULL,
                                                                                                                `saldo_masuk` varchar(254) DEFAULT NULL,
                                                                                                                                                   `saldo_total` varchar(254) DEFAULT NULL,
                                                                                                                                                                                      `id_proyek` bigint(254) DEFAULT NULL,
                                                                                                                                                                                                                      `jenis` varchar(254) DEFAULT NULL,
                                                                                                                                                                                                                                                   `keterangan` text, `created_date` varchar(254) DEFAULT NULL,
                                                                                                                                                                                                                                                                                                          `id_user` bigint(20) DEFAULT NULL,
                                                                                                                                                                                                                                                                                                                                       `file_name` text, `id_gaji` bigint(20) DEFAULT NULL) ENGINE=InnoDB DEFAULT
CHARSET=latin1;

--
-- Dumping data for table `khas_history`
--

INSERT INTO `khas_history` (`id`, `id_pemodal`, `saldo_awal`, `saldo_masuk`, `saldo_total`, `id_proyek`, `jenis`, `keterangan`, `created_date`, `id_user`, `file_name`, `id_gaji`)
VALUES (2,
        2,
        '60000000',
        '5000000',
        '65000000',
        NULL,
        NULL,
        'test',
        NULL,
        2,
        NULL,
        NULL), (3,
                2,
                '65000000',
                '5000000',
                '60000000',
                NULL,
                NULL,
                'test',
                NULL,
                2,
                NULL,
                NULL), (4,
                        2,
                        '60000000',
                        '10000000',
                        '50000000',
                        3,
                        'pekerjaan',
                        'test',
                        NULL,
                        2,
                        NULL,
                        NULL), (5,
                                2,
                                '50000000',
                                '500000',
                                '50500000',
                                3,
                                'pekerjaan',
                                'Mengurangi Nilai Pekerjaan',
                                NULL,
                                2,
                                NULL,
                                NULL), (6,
                                        2,
                                        '0',
                                        '500000',
                                        '500000',
                                        NULL,
                                        NULL,
                                        'Menambahkan Saldo',
                                        NULL,
                                        3,
                                        NULL,
                                        NULL), (7,
                                                2,
                                                '500000',
                                                '100000',
                                                '600000',
                                                NULL,
                                                NULL,
                                                'Menambahkan Saldo',
                                                NULL,
                                                3,
                                                'download.jpeg',
                                                NULL), (8,
                                                        2,
                                                        '48900000',
                                                        '900000',
                                                        '48000000',
                                                        3,
                                                        'gaji',
                                                        'joho',
                                                        NULL,
                                                        3,
                                                        NULL,
                                                        2), (9,
                                                             2,
                                                             '48000000',
                                                             '50000',
                                                             '47950000',
                                                             3,
                                                             'gaji',
                                                             'john',
                                                             NULL,
                                                             2,
                                                             NULL,
                                                             3);

-- --------------------------------------------------------
 --
-- Table structure for table `khas_proyek`
--

CREATE TABLE `khas_proyek` ( `id` bigint(20) NOT NULL,
                                             `id_proyek` bigint(20) DEFAULT NULL,
                                                                            `id_pemodal` bigint(20) DEFAULT NULL,
                                                                                                            `saldo_awal` bigint(20) DEFAULT NULL,
                                                                                                                                            `saldo_masuk` bigint(20) DEFAULT NULL,
                                                                                                                                                                             `saldo_akhir` bigint(20) DEFAULT NULL,
                                                                                                                                                                                                              `keterangan` text, `jenis` varchar(254) DEFAULT NULL,
                                                                                                                                                                                                                                                              `created_date` varchar(254) DEFAULT NULL,
                                                                                                                                                                                                                                                                                                  `file_name` text) ENGINE=InnoDB DEFAULT
CHARSET=latin1;

--
-- Dumping data for table `khas_proyek`
--

INSERT INTO `khas_proyek` (`id`, `id_proyek`, `id_pemodal`, `saldo_awal`, `saldo_masuk`, `saldo_akhir`, `keterangan`, `jenis`, `created_date`, `file_name`)
VALUES (3,
        3,
        2,
        0,
        10000000,
        10000000,
        'test',
        NULL,
        NULL,
        NULL), (4,
                3,
                2,
                10000000,
                500000,
                9500000,
                'test',
                NULL,
                NULL,
                NULL);

-- --------------------------------------------------------
 --
-- Table structure for table `pdf`
--

CREATE TABLE `pdf` ( `id` bigint(20) NOT NULL,
                                     `id_user` bigint(20) DEFAULT NULL,
                                                                  `file_name` text, `nama_laporan` text) ENGINE=InnoDB DEFAULT
CHARSET=latin1;

-- --------------------------------------------------------
 --
-- Table structure for table `proyek`
--

CREATE TABLE `proyek` ( `id` bigint(20) NOT NULL,
                                        `nama_proyek` text, `tgl_mulai` varchar(254) DEFAULT NULL,
                                                                                             `tgl_selesai` varchar(254) DEFAULT NULL,
                                                                                                                                `keterangan` text, `modal` varchar(254) DEFAULT NULL,
                                                                                                                                                                                `modal_awal` varchar(254) DEFAULT NULL,
                                                                                                                                                                                                                  `created_date` varchar(254) DEFAULT NULL) ENGINE=InnoDB DEFAULT
CHARSET=latin1;

--
-- Dumping data for table `proyek`
--

INSERT INTO `proyek` (`id`, `nama_proyek`, `tgl_mulai`, `tgl_selesai`, `keterangan`, `modal`, `modal_awal`, `created_date`)
VALUES (3,
        'pekerjaan',
        'Sabtu, 26 Agustus 2023',
        'Kamis, 26 Oktober 2023',
        'test',
        '9500000',
        '10000000',
        NULL);

-- --------------------------------------------------------
 --
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` ( `id` bigint(20) NOT NULL,
                                           `dana` varchar(254) DEFAULT NULL,
                                                                       `nama_transaksi` varchar(254) DEFAULT NULL,
                                                                                                             `file_name` text, `created_date` varchar(254) DEFAULT NULL,
                                                                                                                                                                   `jenis` varchar(254) DEFAULT NULL,
                                                                                                                                                                                                `keterangan` text, `id_proyek` bigint(20) DEFAULT NULL,
                                                                                                                                                                                                                                                  `id_user` bigint(20) DEFAULT NULL,
                                                                                                                                                                                                                                                                               `current_saldo` int(11) DEFAULT NULL,
                                                                                                                                                                                                                                                                                                               `status` varchar(254) DEFAULT NULL) ENGINE=InnoDB DEFAULT
CHARSET=latin1;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `dana`, `nama_transaksi`, `file_name`, `created_date`, `jenis`, `keterangan`, `id_proyek`, `id_user`, `current_saldo`, `status`)
VALUES (1,
        '100000',
        'Kerja Bagai kuda',
        'download_(1).jpeg',
        NULL,
        'utang',
        'oke',
        3,
        3,
        600000,
        'belum lunas');

-- --------------------------------------------------------
 --
-- Table structure for table `user`
--

CREATE TABLE `user` ( `id` int(11) NOT NULL,
                                   `username` varchar(254) DEFAULT NULL,
                                                                   `password` text, `role` enum('0','1','2','3') NOT NULL,
                                                                                                                 `is_active` tinyint(1) DEFAULT '1',
                                                                                                                                                `nama` text, `foto` text, `saldo` bigint(20) NOT NULL DEFAULT '0',
                                                                                                                                                                                                              `nomer` text, `device_token` text, `created_date` varchar(245) DEFAULT NULL,
                                                                                                                                                                                                                                                                                     `is_admin` tinyint(1) NOT NULL DEFAULT '0') ENGINE=InnoDB DEFAULT
CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `role`, `is_active`, `nama`, `foto`, `saldo`, `nomer`, `device_token`, `created_date`, `is_admin`)
VALUES (2,
        'test',
        '$2y$12$bk52oRnaG63IcKMXPwYjB.lfMa1AaqcEjsbtOHiAoFmhIyGWk7tKy',
        '2',
        1,
        'john',
        '',
        47950000,
        '0',
        'fVvpVsb_SCayp-W3QHg-hs:APA91bFdfGvDdzmigf9_usrg-qweZmoKSXwsqjdxXAtJt-V9cpAe0IDq8Tmxfh6YbQOfWKsqyzcIxrVqqDRxGY4Qes-YKLvUi0UHgRzN1YSN-LRutpe5SG-FiVgOb91Ns8x5qmA7tf_I',
        NULL,
        0), (3,
             'joho68',
             '$2y$12$W1vLbs3zTF4ugugLv26TyeDo5hrJhy.u8s.I0y3Iu0NOHuefdrFBK',
             '0',
             1,
             'joho',
             'thumbnail.png',
             600000,
             NULL,
             'fVvpVsb_SCayp-W3QHg-hs:APA91bFdfGvDdzmigf9_usrg-qweZmoKSXwsqjdxXAtJt-V9cpAe0IDq8Tmxfh6YbQOfWKsqyzcIxrVqqDRxGY4Qes-YKLvUi0UHgRzN1YSN-LRutpe5SG-FiVgOb91Ns8x5qmA7tf_I',
             NULL,
             0);

--
-- Indexes for dumped tables
--
 --
-- Indexes for table `gaji`
--

ALTER TABLE `gaji` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `khas_history`
--

ALTER TABLE `khas_history` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `khas_proyek`
--

ALTER TABLE `khas_proyek` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pdf`
--

ALTER TABLE `pdf` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proyek`
--

ALTER TABLE `proyek` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--

ALTER TABLE `transaksi` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--

ALTER TABLE `user` ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--
 --
-- AUTO_INCREMENT for table `gaji`
--

ALTER TABLE `gaji` MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,
                                                   AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `khas_history`
--

ALTER TABLE `khas_history` MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,
                                                           AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `khas_proyek`
--

ALTER TABLE `khas_proyek` MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,
                                                          AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pdf`
--

ALTER TABLE `pdf` MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,
                                                  AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `proyek`
--

ALTER TABLE `proyek` MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,
                                                     AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaksi`
--

ALTER TABLE `transaksi` MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,
                                                        AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--

ALTER TABLE `user` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
                                                AUTO_INCREMENT=4;


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

