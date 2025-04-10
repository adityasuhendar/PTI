/*!40101 SET NAMES utf8 */;
SET foreign_key_checks = 0;

/*!40101 SET SQL_MODE=''*/;

DROP TABLE IF EXISTS `barang`;

CREATE TABLE `barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_barang` varchar(255) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `nama_barang` text NOT NULL,
  `merk` varchar(255) NOT NULL,
  `harga_beli` varchar(255) NOT NULL,
  `harga_jual` varchar(255) NOT NULL,
  `satuan_barang` varchar(255) NOT NULL,
  `stok` text NOT NULL,
  `tgl_input` varchar(255) NOT NULL,
  `tgl_update` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO `barang`(`id`,`id_barang`,`id_kategori`,`nama_barang`,`merk`,`harga_beli`,`harga_jual`,`satuan_barang`,`stok`,`tgl_input`,`tgl_update`) VALUES 
(1,'BR001',1,'Pensil','Fabel Castel','1500','3000','PCS','98','6 October 2020, 0:41',NULL),
(2,'BR002',5,'Sabun','Lifeboy','1800','3000','PCS','38','6 October 2020, 0:41','6 October 2020, 0:54'),
(3,'BR003',1,'Pulpen','Standard','1500','2000','PCS','70','6 October 2020, 1:34',NULL);

DROP TABLE IF EXISTS `kategori`;

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) NOT NULL,
  `tgl_input` varchar(255) NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

INSERT INTO `kategori`(`id_kategori`,`nama_kategori`,`tgl_input`) VALUES 
(1,'ATK','7 May 2017, 10:23'),
(5,'Sabun','7 May 2017, 10:28'),
(6,'Snack','6 October 2020, 0:19'),
(7,'Minuman','6 October 2020, 0:20');

DROP TABLE IF EXISTS `member`;

CREATE TABLE `member` (
  `id_member` int(11) NOT NULL AUTO_INCREMENT,
  `nm_member` varchar(255) NOT NULL,
  `alamat_member` text NOT NULL,
  `telepon` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gambar` text NOT NULL,
  `NIK` text NOT NULL,
  PRIMARY KEY (`id_member`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `member`(`id_member`,`nm_member`,`alamat_member`,`telepon`,`email`,`gambar`,`NIK`) VALUES 
(1,'Fauzan Falah','Ujung Harapan','081234567890','admin@gmail.com','unnamed.jpg','12314121'),
(2,'Kasir 1','Cikarang','082112345678','kasir1@gmail.com','unnamed.jpg','987654321');

DROP TABLE IF EXISTS `login`;

CREATE TABLE `login` (
  `id_login` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL,
  `pass` char(32) NOT NULL,
  `id_member` int(11) NOT NULL,
  `role` enum('admin','kasir') NOT NULL,
  PRIMARY KEY (`id_login`),
  FOREIGN KEY (`id_member`) REFERENCES `member`(`id_member`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- password: 123
INSERT INTO `login`(`id_login`,`user`,`pass`,`id_member`,`role`) VALUES 
(1,'admin','202cb962ac59075b964b07152d234b70',1,'admin'),
(2,'kasir','202cb962ac59075b964b07152d234b70',2,'kasir');

DROP TABLE IF EXISTS `nota`;

CREATE TABLE `nota` (
  `id_nota` int(11) NOT NULL AUTO_INCREMENT,
  `id_barang` varchar(255) NOT NULL,
  `id_member` int(11) NOT NULL,
  `jumlah` varchar(255) NOT NULL,
  `total` varchar(255) NOT NULL,
  `tanggal_input` varchar(255) NOT NULL,
  `periode` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_nota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `penjualan`;

CREATE TABLE `penjualan` (
  `id_penjualan` int(11) NOT NULL AUTO_INCREMENT,
  `id_barang` varchar(255) NOT NULL,
  `id_member` int(11) NOT NULL,
  `jumlah` varchar(255) NOT NULL,
  `total` varchar(255) NOT NULL,
  `tanggal_input` varchar(255) NOT NULL,
  PRIMARY KEY (`id_penjualan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `toko`;

CREATE TABLE `toko` (
  `id_toko` int(11) NOT NULL AUTO_INCREMENT,
  `nama_toko` varchar(255) NOT NULL,
  `alamat_toko` text NOT NULL,
  `tlp` varchar(255) NOT NULL,
  `nama_pemilik` varchar(255) NOT NULL,
  PRIMARY KEY (`id_toko`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `toko`(`id_toko`,`nama_toko`,`alamat_toko`,`tlp`,`nama_pemilik`) VALUES 
(1,'CV Daruttaqwa','Ujung Harapan','081234567890','Fauzan Falah');

SET foreign_key_checks = 1;
