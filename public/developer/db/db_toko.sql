/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 10.3.16-MariaDB : Database - db_toko
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_toko` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `db_toko`;

/*Table structure for table `tb_barang` */

DROP TABLE IF EXISTS `tb_barang`;

CREATE TABLE `tb_barang` (
  `id_barang` int(12) NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(100) DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `harga_barang` int(6) DEFAULT NULL,
  `foto_barang_1` varchar(255) DEFAULT NULL,
  `foto_barang_2` varchar(255) DEFAULT NULL,
  `id_jenis_barang` int(12) DEFAULT NULL,
  PRIMARY KEY (`id_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `tb_barang` */

insert  into `tb_barang`(`id_barang`,`kode_barang`,`nama_barang`,`harga_barang`,`foto_barang_1`,`foto_barang_2`,`id_jenis_barang`) values 
(1,'A1','Bayam',2000,'1/1_1.png','1/1_2.png',3),
(2,'sdfdsf','dsfds',341,'2/2_1.png','2/2_2.png',3);

/*Table structure for table `tb_customer` */

DROP TABLE IF EXISTS `tb_customer`;

CREATE TABLE `tb_customer` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `nama_customer` varchar(100) DEFAULT NULL,
  `telp_customer` varchar(15) DEFAULT NULL,
  `alamat_customer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tb_customer` */

/*Table structure for table `tb_jenis_barang` */

DROP TABLE IF EXISTS `tb_jenis_barang`;

CREATE TABLE `tb_jenis_barang` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `nama_jenis_barang` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `tb_jenis_barang` */

insert  into `tb_jenis_barang`(`id`,`nama_jenis_barang`) values 
(1,'Daging'),
(2,'Elektronik'),
(3,'Sayuran'),
(4,'Kebersihan'),
(5,'Alat Tulis'),
(6,'Buku');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
