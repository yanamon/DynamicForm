/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 10.3.16-MariaDB : Database - dynamic_form
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`dynamic_form` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `dynamic_form`;

/*Table structure for table `form_inputs` */

DROP TABLE IF EXISTS `form_inputs`;

CREATE TABLE `form_inputs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `form_id` bigint(20) DEFAULT NULL,
  `html` text DEFAULT NULL,
  `input_key` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `option_file` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `form_id` (`form_id`),
  CONSTRAINT `form_inputs_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=217 DEFAULT CHARSET=latin1;

/*Data for the table `form_inputs` */

insert  into `form_inputs`(`id`,`form_id`,`html`,`input_key`,`created_at`,`updated_at`,`option_file`) values 
(17,7,'<div id=card-input-0 data-id=0 class=card-input><div class=form-group><label>Product Name</label><input class=form-control type=text name=input_value[] placeholder=text></div></div><input type=hidden name=input_label[] value=Product Name>',NULL,'2019-06-03 01:50:01','2019-06-03 01:50:01',NULL),
(18,7,'<div id=card-input-1 data-id=1 class=card-input><div class=form-group><label>Price</label><input class=form-control type=number name=input_value[] placeholder=number></div></div><input type=hidden name=input_label[] value=Price>',NULL,'2019-06-03 01:50:01','2019-06-03 01:50:01',NULL),
(19,8,'<div id=card-input-0 data-id=0 class=card-input><div class=form-group><label>Nama</label><input class=form-control type=text name=input_value[] placeholder=text></div></div><input type=hidden name=input_label[] value=Nama>',NULL,'2019-06-03 01:53:42','2019-06-03 01:53:42',NULL),
(20,8,'<div id=card-input-1 data-id=1 class=card-input><div class=form-group><label>Name</label><input class=form-control type=text name=input_value[] placeholder=text></div></div><input type=hidden name=input_label[] value=Name>',NULL,'2019-06-03 01:53:43','2019-06-03 01:53:43',NULL),
(21,8,'<div id=card-input-2 data-id=2 class=card-input><div class=form-group><label>Name</label><div class=check><div class=form-check><label class=form-check-label><input type=radio name=input_value[] value=A>A</label></div><div class=form-check><label class=form-check-label><input type=radio name=input_value[] value=B>B</label></div><div class=form-check><label class=form-check-label><input type=radio name=input_value[] value=C>C</label></div></div></div></div><input type=hidden name=input_label[] value=Name>',NULL,'2019-06-03 01:53:43','2019-06-03 01:53:43',NULL),
(22,8,'<div id=card-input-3 data-id=3 class=card-input><div class=form-group><label>Name</label><div class=check><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[] value=1>1</label></div><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[] value=2>2</label></div><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[] value=3>3</label></div></div></div></div><input type=hidden name=input_label[] value=Name>',NULL,'2019-06-03 01:53:43','2019-06-03 01:53:43',NULL),
(23,8,'<div id=card-input-4 data-id=4 class=card-input><div class=form-group><label>Name</label><select class=form-control name=input_value[]><option>yes</option><option>yes</option></select></div></div><input type=hidden name=input_label[] value=Name>',NULL,'2019-06-03 01:53:43','2019-06-03 01:53:43',NULL),
(24,9,'<div id=card-input-0 data-id=0 class=card-input><div class=form-group><label>ads</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=ads></div></div>',NULL,'2019-06-03 02:48:55','2019-06-03 02:48:55',NULL),
(25,9,'<div id=card-input-1 data-id=1 class=card-input><div class=form-group><label>ads</label><div class=check><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=asd>asd</label></div><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=das>das</label></div></div></div></div>',NULL,'2019-06-03 02:48:55','2019-06-03 02:48:55',NULL),
(26,9,'<div id=card-input-2 data-id=2 class=card-input><div class=form-group><label>ads</label><div class=check><input type=hidden name=input_label[2] value=ads><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2] value=sad>sad</label></div><input type=hidden name=input_label[2] value=ads><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2] value=sda>sda</label></div></div><input type=hidden name=input_label[2] value=ads></div></div>',NULL,'2019-06-03 02:48:55','2019-06-03 02:48:55',NULL),
(27,10,'<div id=card-input-0 data-id=0 class=card-input><div class=form-group><label>sad</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=sad></div></div>',NULL,'2019-06-03 02:51:43','2019-06-03 02:51:43',NULL),
(28,10,'<div id=card-input-1 data-id=1 class=card-input><div class=form-group><label>sad</label><div class=check><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=asd>asd</label></div><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=sad>sad</label></div></div><input type=hidden name=input_label[1] value=sad></div></div>',NULL,'2019-06-03 02:51:43','2019-06-03 02:51:43',NULL),
(29,10,'<div id=card-input-2 data-id=2 class=card-input><div class=form-group><label>sad</label><div class=check><input type=hidden name=input_label[2] value=sad><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2] value=sad>sad</label></div><input type=hidden name=input_label[2] value=sad><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2] value=das>das</label></div></div></div></div>',NULL,'2019-06-03 02:51:43','2019-06-03 02:51:43',NULL),
(30,11,'<div id=card-input-0 data-id=0 class=card-input><div class=form-group><label>sad</label><div class=check><div class=form-check><label class=form-check-label><input type=radio name=input_value[0] value=asd>asd</label></div><div class=form-check><label class=form-check-label><input type=radio name=input_value[0] value=sad>sad</label></div></div><input type=hidden name=input_label[0] value=sad></div></div>',NULL,'2019-06-03 02:56:45','2019-06-03 02:56:45',NULL),
(31,11,'<div id=card-input-1 data-id=1 class=card-input><div class=form-group><label>sad</label><div class=check><input type=hidden name=input_label[1] value=sad><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2] value=asd>asd</label></div><input type=hidden name=input_label[2] value=sad><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[3] value=dsa>dsa</label></div></div></div></div>',NULL,'2019-06-03 02:56:45','2019-06-03 02:56:45',NULL),
(32,11,'<div id=card-input-4 data-id=4 class=card-input><div class=form-group><label>sad</label><input class=form-control type=text name=input_value[4] placeholder=text><input type=hidden name=input_label[4] value=sad></div></div>',NULL,'2019-06-03 02:56:45','2019-06-03 02:56:45',NULL),
(33,12,'<div id=card-input-0 data-id=0 class=card-input><div class=form-group><label>sad</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=sad></div></div>',NULL,'2019-06-03 03:01:36','2019-06-03 03:01:36',NULL),
(34,12,'<div id=card-input-1 data-id=1 class=card-input><div class=form-group><label>qeer</label><div class=check><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=ad>ad</label></div><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=sdf>sdf</label></div></div><input type=hidden name=input_label[1] value=qeer></div></div>',NULL,'2019-06-03 03:01:36','2019-06-03 03:01:36',NULL),
(35,12,'<div id=card-input-2 data-id=2 class=card-input><div class=form-group><label>qeer</label><div class=check><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2] value=gfh>gfh</label></div><input type=hidden name=input_label[2] value=qeer><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[3] value=rew4>rew4</label></div><input type=hidden name=input_label[3] value=qeer></div></div></div>',NULL,'2019-06-03 03:01:36','2019-06-03 03:01:36',NULL),
(36,12,'<div id=card-input-5 data-id=5 class=card-input><div class=form-group><label>hgt</label><input class=form-control type=number name=input_value[5] placeholder=number><input type=hidden name=input_label[5] value=hgt></div></div>',NULL,'2019-06-03 03:01:37','2019-06-03 03:01:37',NULL),
(37,13,'<div id=card-input-0 data-id=0 class=card-input><div class=form-group><label>Nama Mhs</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=nama_mhs></div></div>',NULL,'2019-06-03 05:50:46','2019-06-03 05:50:46',NULL),
(38,13,'<div id=card-input-1 data-id=1 class=card-input><div class=form-group><label>Semester</label><div class=check><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=1>1</label></div><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=2>2</label></div></div><input type=hidden name=input_label[1] value=semester></div></div>',NULL,'2019-06-03 05:50:46','2019-06-03 05:50:46',NULL),
(39,13,'<div id=card-input-2 data-id=2 class=card-input><div class=form-group><label>Hobi</label><div class=check><div class=form-check><label class=form-check-label><input type=radio name=input_value[2] value=makan>makan</label></div><div class=form-check><label class=form-check-label><input type=radio name=input_value[2] value=tidur>tidur</label></div><div class=form-check><label class=form-check-label><input type=radio name=input_value[2] value=minum>minum</label></div></div><input type=hidden name=input_label[2] value=hobi></div></div>',NULL,'2019-06-03 05:50:46','2019-06-03 05:50:46',NULL),
(40,14,'<div id=card-input-0 data-id=0 class=card-input><div class=form-group><label>Nama Mahasiswa</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=nama_mhs></div></div>',NULL,'2019-06-03 05:52:14','2019-06-03 05:52:14',NULL),
(41,14,'<div id=card-input-1 data-id=1 class=card-input><div class=form-group><label>Semester</label><div class=check><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=1>1</label></div><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=2>2</label></div></div><input type=hidden name=input_label[1] value=semester></div></div>',NULL,'2019-06-03 05:52:14','2019-06-03 05:52:14',NULL),
(42,14,'<div id=card-input-2 data-id=2 class=card-input><div class=form-group><label>Hobi</label><div class=check><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2] value=makan>makan</label></div><input type=hidden name=input_label[2] value=hobi2><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[3] value=minum>minum</label></div><input type=hidden name=input_label[3] value=hobi3><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[4] value=tidur>tidur</label></div><input type=hidden name=input_label[4] value=hobi4></div></div></div>',NULL,'2019-06-03 05:52:14','2019-06-03 05:52:14',NULL),
(43,15,'<div id=card-input-0 data-id=0 class=card-input><div class=form-group><label>Nama</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=nama></div></div>',NULL,'2019-06-03 05:58:08','2019-06-03 05:58:08',NULL),
(44,15,'<div id=card-input-1 data-id=1 class=card-input><div class=form-group><label>Semester</label><div class=check><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=1>1</label></div><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=2>2</label></div></div><input type=hidden name=input_label[1] value=semester></div></div>',NULL,'2019-06-03 05:58:08','2019-06-03 05:58:08',NULL),
(45,15,'<div id=card-input-2 data-id=2 class=card-input><div class=form-group><label>Hobi</label><div class=check><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2] value=makan>makan</label></div><input type=hidden name=input_label[2][0] value=hobi><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[3] value=mandi>mandi</label></div><input type=hidden name=input_label[3][1] value=hobi><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[4] value=tidur>tidur</label></div><input type=hidden name=input_label[4][2] value=hobi></div></div></div>',NULL,'2019-06-03 05:58:09','2019-06-03 05:58:09',NULL),
(46,16,'<div id=card-input-0 data-id=0 class=card-input><div class=form-group><label>Nama</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=nama></div></div>',NULL,'2019-06-03 06:00:48','2019-06-03 06:00:48',NULL),
(47,16,'<div id=card-input-1 data-id=1 class=card-input><div class=form-group><label>Semester</label><div class=check><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=1>1</label></div><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=2>2</label></div></div><input type=hidden name=input_label[1] value=semester></div></div>',NULL,'2019-06-03 06:00:49','2019-06-03 06:00:49',NULL),
(48,16,'<div id=card-input-2 data-id=2 class=card-input><div class=form-group><label>Hobi</label><div class=check><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2] value=makan>makan</label></div><input type=hidden name=input_label[2][0] value=hobi><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2] value=mandi>mandi</label></div><input type=hidden name=input_label[2][1] value=hobi><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2] value=tidur>tidur</label></div><input type=hidden name=input_label[2][2] value=hobi></div></div></div>',NULL,'2019-06-03 06:00:49','2019-06-03 06:00:49',NULL),
(49,17,'<div id=card-input-0 data-id=0 class=card-input><div class=form-group><label>Nama Siswa</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=nama_siswa></div></div>',NULL,'2019-06-03 06:04:49','2019-06-03 06:04:49',NULL),
(50,17,'<div id=card-input-1 data-id=1 class=card-input><div class=form-group><label>Semester</label><div class=check><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=1>1</label></div><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=2>2</label></div></div><input type=hidden name=input_label[1] value=semester></div></div>',NULL,'2019-06-03 06:04:49','2019-06-03 06:04:49',NULL),
(51,17,'<div id=card-input-2 data-id=2 class=card-input><div class=form-group><label>Hobi</label><div class=check><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2][0] value=makan>makan</label></div><input type=hidden name=input_label[2][0] value=hobi><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2][1] value=mandi>mandi</label></div><input type=hidden name=input_label[2][1] value=hobi><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2][2] value=tidur>tidur</label></div><input type=hidden name=input_label[2][2] value=hobi></div></div></div>',NULL,'2019-06-03 06:04:49','2019-06-03 06:04:49',NULL),
(52,18,'<div id=card-input-0 data-id=0 class=card-input><div class=form-group><label>Nama</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=nama></div></div>',NULL,'2019-06-03 06:21:46','2019-06-03 06:21:46',NULL),
(53,18,'<div id=card-input-1 data-id=1 class=card-input><div class=form-group><label>Semester</label><div class=check><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=1>1</label></div><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=2>2</label></div></div><input type=hidden name=input_label[1] value=semester></div></div>',NULL,'2019-06-03 06:21:47','2019-06-03 06:21:47',NULL),
(54,18,'<div id=card-input-2 data-id=2 class=card-input><div class=form-group><label>Hobi</label><div class=check><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2][0] value=makan>makan</label></div><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2][1] value=mandi>mandi</label></div><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2][2] value=tidur>tidur</label></div></div><input type=hidden name=input_label[2] value=hobi></div></div>',NULL,'2019-06-03 06:21:47','2019-06-03 06:21:47',NULL),
(55,19,'<div id=card-input-0 data-key=nama data-id=0 class=card-input><div class=form-group><label>Nama</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=nama></div></div>',NULL,'2019-06-03 11:50:40','2019-06-03 11:50:40',NULL),
(56,19,'<div id=card-input-1 data-key=semester data-id=1 class=card-input><div class=form-group><label>Semester</label><div class=check><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=1>1</label></div><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=2>2</label></div></div><input type=hidden name=input_label[1] value=semester></div></div>',NULL,'2019-06-03 11:50:40','2019-06-03 11:50:40',NULL),
(57,19,'<div id=card-input-2 data-key=hobi data-id=2 class=card-input><div class=form-group><label>Hobi</label><div class=check><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2][0] value=makan>makan</label></div><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2][1] value=mandi>mandi</label></div><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2][2] value=tidur>tidur</label></div></div><input type=hidden name=input_label[2] value=hobi></div></div>',NULL,'2019-06-03 11:50:40','2019-06-03 11:50:40',NULL),
(58,20,'<div id=card-input-0 data-key=nama data-id=0 class=card-input><div class=form-group><label>Nama</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=nama></div></div>',NULL,'2019-06-04 03:42:18','2019-06-04 03:42:18',NULL),
(59,20,'<div id=card-input-1 data-key=semester data-id=1 class=card-input><div class=form-group><label>Semester</label><div class=check><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=1>1</label></div><div class=form-check><label class=form-check-label><input type=radio name=input_value[1] value=2>2</label></div></div><input type=hidden name=input_label[1] value=semester></div></div>',NULL,'2019-06-04 03:42:18','2019-06-04 03:42:18',NULL),
(60,20,'<div id=card-input-2 data-key=hobi data-id=2 class=card-input><div class=form-group><label>Hobi</label><div class=check><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2][0] value=makan>makan</label></div><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2][1] value=tidur>tidur</label></div><div class=form-check><label class=form-check-label><input type=checkbox name=input_value[2][2] value=mandi>mandi</label></div></div><input type=hidden name=input_label[2] value=hobi></div></div>',NULL,'2019-06-04 03:42:18','2019-06-04 03:42:18',NULL),
(61,21,'<div id=card-input-0 data-key=a data-id=0 class=card-input><div class=form-group><label>a</label><input class=form-control type=file name=input_value[0] placeholder=file><input type=hidden name=input_label[0] value=a></div></div>',NULL,'2019-06-04 04:00:59','2019-06-04 04:00:59',NULL),
(62,22,'<div id=card-input-0 data-key=nama data-id=0 class=card-input><div class=form-group><label>Nama</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=nama></div></div>',NULL,'2019-06-10 04:41:18','2019-06-10 04:41:18',NULL),
(63,23,'<div id=card-input-0 data-key=asd data-id=0 class=card-input><div class=form-group><label>sad</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=asd></div></div>',NULL,'2019-06-15 13:26:10','2019-06-15 13:26:10',NULL),
(64,24,'<div id=card-input-0 data-key=nama_dosen data-id=0 class=card-input><div class=form-group><label>Nama Dosen</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=nama_dosen></div></div>',NULL,'2019-06-19 17:38:17','2019-06-19 17:38:17',NULL),
(65,24,'<div id=card-input-1 data-key=nip data-id=1 class=card-input><div class=form-group><label>NIP</label><input class=form-control type=text name=input_value[1] placeholder=text><input type=hidden name=input_label[1] value=nip></div></div>',NULL,'2019-06-19 17:38:17','2019-06-19 17:38:17',NULL),
(66,24,'<div id=card-input-2 data-key=alamat data-id=2 class=card-input><div class=form-group><label>Alamat</label><input class=form-control type=text name=input_value[2] placeholder=text><input type=hidden name=input_label[2] value=alamat></div></div>',NULL,'2019-06-19 17:38:17','2019-06-19 17:38:17',NULL),
(67,24,'<div id=card-input-3 data-key=tanggal_lahir data-id=3 class=card-input><div class=form-group><label>Tanggal Lahir</label><input class=form-control type=date name=input_value[3] placeholder=date><input type=hidden name=input_label[3] value=tanggal_lahir></div></div>',NULL,'2019-06-19 17:38:17','2019-06-19 17:38:17',NULL),
(68,25,'<div id=card-input-0 data-key=a data-id=0 class=card-input><div class=form-group><label>A</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=a></div></div>',NULL,'2019-06-19 17:58:50','2019-06-19 17:58:50',NULL),
(69,25,'<div id=card-input-1 data-key=asad data-id=1 class=card-input><div class=form-group><label>A</label><input class=form-control type=text name=input_value[1] placeholder=text><input type=hidden name=input_label[1] value=asad></div></div>',NULL,'2019-06-19 17:58:50','2019-06-19 17:58:50',NULL),
(70,26,'<div id=card-input-0 data-key=nama_barang data-id=0 class=card-input><div class=form-group><label>Nama Barang</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=nama_barang></div></div>',NULL,'2019-09-25 07:02:01','2019-09-25 07:02:01',NULL),
(71,26,'<div id=card-input-1 data-key=harga_barang data-id=1 class=card-input><div class=form-group><label>Harga Barang</label><input class=form-control type=number name=input_value[1] placeholder=number><input type=hidden name=input_label[1] value=harga_barang></div></div>',NULL,'2019-09-25 07:02:01','2019-09-25 07:02:01',NULL),
(72,27,'<div id=card-input-0 data-key=ad data-id=0 class=card-input><div class=form-group><label>asdas</label><input class=form-control type=file name=input_value[0] placeholder=file><input type=hidden name=input_label[0] value=ad></div></div>',NULL,'2019-09-25 07:14:43','2019-09-25 07:14:43',NULL),
(73,28,'<div id=card-input-0 data-key=a data-id=0 class=card-input><div class=form-group><label>a</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=a></div></div>',NULL,'2019-09-25 07:41:48','2019-09-25 07:41:48',NULL),
(74,28,'<div id=card-input-1 data-key=aasd data-id=1 class=card-input><div class=form-group><label>a</label><input class=form-control type=text name=input_value[1] placeholder=text><input type=hidden name=input_label[1] value=aasd></div></div>',NULL,'2019-09-25 07:41:48','2019-09-25 07:41:48',NULL),
(75,29,'<div id=card-input-0 data-key=asd data-id=0 class=card-input><div class=form-group><label>asd</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=asd></div></div>',NULL,'2019-09-25 07:44:59','2019-09-25 07:44:59',NULL),
(76,30,'<div id=card-input-0 data-key=asd data-id=0 class=card-input><div class=form-group><label>asd</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=asd></div></div>',NULL,'2019-09-25 07:44:59','2019-09-25 07:44:59',NULL),
(77,31,'<div id=card-input-0 data-key=nama_barang data-id=0 class=card-input><div class=form-group><label>Nama Barang</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=nama_barang></div></div>',NULL,'2019-09-25 14:07:58','2019-09-25 14:07:58',NULL),
(78,31,'<div id=card-input-1 data-key=harga_barang data-id=1 class=card-input><div class=form-group><label>Harga Barang</label><input class=form-control type=text name=input_value[1] placeholder=text><input type=hidden name=input_label[1] value=harga_barang></div></div>',NULL,'2019-09-25 14:07:58','2019-09-25 14:07:58',NULL),
(79,32,'<div id=card-input-0 data-key=nama_barang data-id=0 class=card-input><div class=form-group><label>Nama Barang</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=nama_barang></div></div>',NULL,'2019-09-25 14:12:33','2019-09-25 14:12:33',NULL),
(80,32,'<div id=card-input-1 data-key=harga_barang data-id=1 class=card-input><div class=form-group><label>Harga Barang</label><input class=form-control type=text name=input_value[1] placeholder=text><input type=hidden name=input_label[1] value=harga_barang></div></div>',NULL,'2019-09-25 14:12:33','2019-09-25 14:12:33',NULL),
(81,33,'<div id=card-input-0 data-key=saad data-id=0 class=card-input><div class=form-group><label>ads</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=saad></div></div>',NULL,'2019-09-25 14:13:59','2019-09-25 14:13:59',NULL),
(82,34,'<div id=card-input-0 data-key=ads data-id=0 class=card-input><div class=form-group><label>asf</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=ads></div></div>',NULL,'2019-09-25 14:15:51','2019-09-25 14:15:51',NULL),
(83,37,'<div id=card-input-0 data-key=tert data-id=0 class=card-input><div class=form-group><label>tert</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=tert></div></div>',NULL,'2019-09-25 14:54:21','2019-09-25 14:54:21',NULL),
(84,38,'<div id=card-input-0 data-key=asd data-id=0 class=card-input><div class=form-group><label>asd</label><input class=form-control type=datetime-local name=input_value[0] placeholder=datetime-local><input type=hidden name=input_label[0] value=asd></div></div>',NULL,'2019-09-25 15:05:23','2019-09-25 15:05:23',NULL),
(85,39,'<div id=card-input-0 data-key=asd data-id=0 class=card-input><div class=form-group><label>asd</label><input class=form-control type=datetime-local name=input_value[0] placeholder=datetime-local><input type=hidden name=input_label[0] value=asd></div></div>',NULL,'2019-09-25 15:07:35','2019-09-25 15:07:35',NULL),
(86,40,'<div id=card-input-0 data-key=asda data-id=0 class=card-input><div class=form-group><label>asda</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=asda></div></div>',NULL,'2019-09-25 15:18:30','2019-09-25 15:18:30',NULL),
(87,40,'<div id=card-input-1 data-key=asdaasda data-id=1 class=card-input><div class=form-group><label>asda</label><input class=form-control type=text name=input_value[1] placeholder=text><input type=hidden name=input_label[1] value=asdaasda></div></div>',NULL,'2019-09-25 15:18:30','2019-09-25 15:18:30',NULL),
(88,40,'<div id=card-input-2 data-key=asdaasdaadd data-id=2 class=card-input><div class=form-group><label>asda</label><input class=form-control type=text name=input_value[2] placeholder=text><input type=hidden name=input_label[2] value=asdaasdaadd></div></div>',NULL,'2019-09-25 15:18:30','2019-09-25 15:18:30',NULL),
(89,40,'<div id=card-input-3 data-key=asdaasdaadddasdas data-id=3 class=card-input><div class=form-group><label>asda</label><input class=form-control type=text name=input_value[3] placeholder=text><input type=hidden name=input_label[3] value=asdaasdaadddasdas></div></div>',NULL,'2019-09-25 15:18:30','2019-09-25 15:18:30',NULL),
(90,41,'<div id=card-input-0 data-key=asd data-id=0 class=card-input><div class=form-group><label>asd</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=asd></div></div>',NULL,'2019-09-25 15:23:57','2019-09-25 15:23:57',NULL),
(91,41,'<div id=card-input-1 data-key=asdsd data-id=1 class=card-input><div class=form-group><label>asd</label><input class=form-control type=text name=input_value[1] placeholder=text><input type=hidden name=input_label[1] value=asdsd></div></div>',NULL,'2019-09-25 15:23:57','2019-09-25 15:23:57',NULL),
(92,41,'<div id=card-input-2 data-key=asdsddsd data-id=2 class=card-input><div class=form-group><label>asd</label><input class=form-control type=text name=input_value[2] placeholder=text><input type=hidden name=input_label[2] value=asdsddsd></div></div>',NULL,'2019-09-25 15:23:57','2019-09-25 15:23:57',NULL),
(93,41,'<div id=card-input-3 data-key=asdsddsddsd data-id=3 class=card-input><div class=form-group><label>asd</label><input class=form-control type=text name=input_value[3] placeholder=text><input type=hidden name=input_label[3] value=asdsddsddsd></div></div>',NULL,'2019-09-25 15:23:57','2019-09-25 15:23:57',NULL),
(94,42,'<div id=card-input-0 data-key=asd data-id=0 class=card-input><div class=form-group><label>asd</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=asd></div></div>',NULL,'2019-09-25 15:24:17','2019-09-25 15:24:17',NULL),
(95,42,'<div id=card-input-1 data-key=asdsd data-id=1 class=card-input><div class=form-group><label>asd</label><input class=form-control type=text name=input_value[1] placeholder=text><input type=hidden name=input_label[1] value=asdsd></div></div>',NULL,'2019-09-25 15:24:17','2019-09-25 15:24:17',NULL),
(96,42,'<div id=card-input-2 data-key=asdsddsd data-id=2 class=card-input><div class=form-group><label>asd</label><input class=form-control type=text name=input_value[2] placeholder=text><input type=hidden name=input_label[2] value=asdsddsd></div></div>',NULL,'2019-09-25 15:24:17','2019-09-25 15:24:17',NULL),
(97,42,'<div id=card-input-3 data-key=asdsddsddsd data-id=3 class=card-input><div class=form-group><label>asd</label><input class=form-control type=text name=input_value[3] placeholder=text><input type=hidden name=input_label[3] value=asdsddsddsd></div></div>',NULL,'2019-09-25 15:24:17','2019-09-25 15:24:17',NULL),
(98,43,'<div id=card-input-0 data-key=asdsad data-id=0 class=card-input><div class=form-group><label>adsad</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=asdsad></div></div>',NULL,'2019-09-25 15:30:46','2019-09-25 15:30:46',NULL),
(99,43,'<div id=card-input-1 data-key=asdsad213 data-id=1 class=card-input><div class=form-group><label>adsad</label><input class=form-control type=text name=input_value[1] placeholder=text><input type=hidden name=input_label[1] value=asdsad213></div></div>',NULL,'2019-09-25 15:30:46','2019-09-25 15:30:46',NULL),
(100,43,'<div id=card-input-2 data-key=asdsad21323 data-id=2 class=card-input><div class=form-group><label>adsad</label><input class=form-control type=text name=input_value[2] placeholder=text><input type=hidden name=input_label[2] value=asdsad21323></div></div>',NULL,'2019-09-25 15:30:46','2019-09-25 15:30:46',NULL),
(101,44,'<div id=card-input-0 data-key=sad data-id=0 class=card-input><div class=form-group><label>adas</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=sad></div></div>',NULL,'2019-09-25 15:39:35','2019-09-25 15:39:35',NULL),
(102,45,'<div id=card-input-0 data-key=asda data-id=0 class=card-input><div class=form-group><label>adas</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=asda></div></div>',NULL,'2019-09-25 15:40:50','2019-09-25 15:40:50',NULL),
(103,46,'<div id=card-input-0 data-key=adas data-id=0 class=card-input><div class=form-group><label>sdaas</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=adas></div></div>',NULL,'2019-09-25 15:43:20','2019-09-25 15:43:20',NULL),
(104,47,'<div id=card-input-0 data-key=dasdsa data-id=0 class=card-input><div class=form-group><label>adas</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=dasdsa></div></div>',NULL,'2019-09-25 15:46:57','2019-09-25 15:46:57',NULL),
(105,48,'<div id=card-input-0 data-key=asd data-id=0 class=card-input><div class=form-group><label>asd</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=asd></div></div>',NULL,'2019-09-25 15:48:59','2019-09-25 15:48:59',NULL),
(106,49,'<div id=card-input-0 data-key=asdas data-id=0 class=card-input><div class=form-group><label>ads</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=asdas></div></div>',NULL,'2019-09-25 15:53:02','2019-09-25 15:53:02',NULL),
(107,50,'<div id=card-input-0 data-key=sdfd data-id=0 class=card-input><div class=form-group><label>sdfd</label><input class=form-control type=text name=input_value[0] placeholder=text><input type=hidden name=input_label[0] value=sdfd></div></div>',NULL,'2019-09-29 10:57:08','2019-09-29 10:57:08',NULL),
(108,51,'<div id=card-input-0 data-key=nama_mhs data-id=0 class=card-input><div class=form-group><label>Nama Mahasiswa</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=nama_mhs></div></div>',NULL,'2019-10-02 05:50:01','2019-10-02 05:50:01',NULL),
(109,51,'<div id=card-input-1 data-key=nim_mhs data-id=1 class=card-input><div class=form-group><label>NIM Mahasiswa</label><input class=form-control type=text name=input_value[1] placeholder=text required><input type=hidden name=input_label[1] value=nim_mhs></div></div>',NULL,'2019-10-02 05:50:01','2019-10-02 05:50:01',NULL),
(110,52,'<div id=card-input-0 data-key=nama data-id=0 class=card-input><div class=form-group><label>Nama</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=nama></div></div>',NULL,'2019-10-02 06:22:44','2019-10-02 06:22:44',NULL),
(111,52,'<div id=card-input-1 data-key=nim data-id=1 class=card-input><div class=form-group><label>NIM</label><input class=form-control type=text name=input_value[1] placeholder=text required><input type=hidden name=input_label[1] value=nim></div></div>',NULL,'2019-10-02 06:22:44','2019-10-02 06:22:44',NULL),
(112,53,'<div id=card-input-0 data-key=nama data-id=0 class=card-input><div class=form-group><label>Nama</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=nama></div></div>',NULL,'2019-10-02 06:23:28','2019-10-02 06:23:28',NULL),
(113,53,'<div id=card-input-1 data-key=nim data-id=1 class=card-input><div class=form-group><label>NIM</label><input class=form-control type=text name=input_value[1] placeholder=text required><input type=hidden name=input_label[1] value=nim></div></div>',NULL,'2019-10-02 06:23:28','2019-10-02 06:23:28',NULL),
(114,54,'<div id=card-input-0 data-key=sdf data-id=0 class=card-input><div class=form-group><label>sdf</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=sdf></div></div>',NULL,'2019-10-02 06:36:49','2019-10-02 06:36:49',NULL),
(115,55,'<div id=card-input-0 data-key=nim data-id=0 class=card-input><div class=form-group><label>NIM</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=nim></div></div>',NULL,'2019-10-02 06:38:10','2019-10-02 06:38:10',NULL),
(116,55,'<div id=card-input-1 data-key=nama data-id=1 class=card-input><div class=form-group><label>Nama</label><input class=form-control type=text name=input_value[1] placeholder=text required><input type=hidden name=input_label[1] value=nama></div></div>',NULL,'2019-10-02 06:38:10','2019-10-02 06:38:10',NULL),
(117,56,'<div id=card-input-0 data-key=nama data-id=0 class=card-input><div class=form-group><label>Nama</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=nama></div></div>',NULL,'2019-10-02 06:40:22','2019-10-02 06:40:22',NULL),
(118,56,'<div id=card-input-1 data-key=nim data-id=1 class=card-input><div class=form-group><label>NIM</label><input class=form-control type=text name=input_value[1] placeholder=text required><input type=hidden name=input_label[1] value=nim></div></div>',NULL,'2019-10-02 06:40:22','2019-10-02 06:40:22',NULL),
(119,57,'<div id=card-input-0 data-key=asdas data-id=0 class=card-input><div class=form-group><label>sada</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=asdas></div></div>',NULL,'2019-10-09 07:29:42','2019-10-09 07:29:42',NULL),
(120,57,'<div id=card-input-1 data-key=asdassad data-id=1 class=card-input><div class=form-group><label>sadaasd</label><input class=form-control type=text name=input_value[1] placeholder=text required><input type=hidden name=input_label[1] value=asdassad></div></div>',NULL,'2019-10-09 07:29:42','2019-10-09 07:29:42',NULL),
(121,58,'<div id=card-input-0 data-key=asdas data-id=0 class=card-input><div class=form-group><label>sada</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=asdas></div></div>',NULL,'2019-10-09 07:30:10','2019-10-09 07:30:10',NULL),
(122,58,'<div id=card-input-1 data-key=asdassad data-id=1 class=card-input><div class=form-group><label>sadaasd</label><input class=form-control type=text name=input_value[1] placeholder=text required><input type=hidden name=input_label[1] value=asdassad></div></div>',NULL,'2019-10-09 07:30:10','2019-10-09 07:30:10',NULL),
(123,59,'<div id=card-input-0 data-key=sad data-id=0 class=card-input><div class=form-group><label>sad</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=sad></div></div>',NULL,'2019-10-09 07:35:21','2019-10-09 07:35:21',NULL),
(124,59,'<div id=card-input-1 data-key=sadasd data-id=1 class=card-input><div class=form-group><label>sad</label><input class=form-control type=text name=input_value[1] placeholder=text required><input type=hidden name=input_label[1] value=sadasd></div></div>',NULL,'2019-10-09 07:35:21','2019-10-09 07:35:21',NULL),
(125,60,'<div id=card-input-0 data-key=adas data-id=0 class=card-input><div class=form-group><label>adas</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=adas></div></div>',NULL,'2019-10-09 07:44:02','2019-10-09 07:44:02',NULL),
(126,60,'<div id=card-input-1 data-key=adasasd data-id=1 class=card-input><div class=form-group><label>adasads</label><input class=form-control type=text name=input_value[1] placeholder=text required><input type=hidden name=input_label[1] value=adasasd></div></div>',NULL,'2019-10-09 07:44:02','2019-10-09 07:44:02',NULL),
(127,61,'<div id=card-input-0 data-key=adasasd data-id=0 class=card-input><div class=form-group><label>adasads</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=adasasd></div></div>',NULL,'2019-10-09 07:44:43','2019-10-09 07:44:43',NULL),
(128,61,'<div id=card-input-1 data-key=adasasdasd data-id=1 class=card-input><div class=form-group><label>adasadsasd</label><input class=form-control type=text name=input_value[1] placeholder=text required><input type=hidden name=input_label[1] value=adasasdasd></div></div>',NULL,'2019-10-09 07:44:43','2019-10-09 07:44:43',NULL),
(129,62,'<div id=card-input-0 data-key=adas data-id=0 class=card-input><div class=form-group><label>asd</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=adas></div></div>',NULL,'2019-10-09 08:51:13','2019-10-09 08:51:13',NULL),
(130,63,'<div id=card-input-0 data-key=adas data-id=0 class=card-input><div class=form-group><label>asd</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=adas></div></div>',NULL,'2019-10-09 08:51:22','2019-10-09 08:51:22',NULL),
(131,64,'<div id=card-input-0 data-key=adas data-id=0 class=card-input><div class=form-group><label>asd</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=adas></div></div>',NULL,'2019-10-09 08:57:22','2019-10-09 08:57:22',NULL),
(132,65,'<div id=card-input-0 data-key=adas data-id=0 class=card-input><div class=form-group><label>asd</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=adas></div></div>',NULL,'2019-10-09 08:57:50','2019-10-09 08:57:50',NULL),
(133,66,'<div id=card-input-0 data-key=adas data-id=0 class=card-input><div class=form-group><label>asd</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=adas></div></div>',NULL,'2019-10-09 08:58:04','2019-10-09 08:58:04',NULL),
(134,67,'<div id=card-input-0 data-key=adas data-id=0 class=card-input><div class=form-group><label>asd</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=adas></div></div>',NULL,'2019-10-09 08:58:11','2019-10-09 08:58:11',NULL),
(135,68,'<div id=card-input-0 data-key=wer data-id=0 class=card-input><div class=form-group><label>wer</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=wer></div></div>',NULL,'2019-10-09 09:04:00','2019-10-09 09:04:00',NULL),
(196,85,'<div id=card-input-0 data-required=Yes data-key=kode_barang data-id=0 class=card-input><div class=form-group><label>Kode Barang</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=kode_barang></div></div>','kode_barang','2019-10-27 05:13:37','2019-10-27 05:13:37',NULL),
(197,85,'<div id=card-input-1 data-required=Yes data-key=nama_barang data-id=1 class=card-input><div class=form-group><label>Nama Barang</label><input class=form-control type=text name=input_value[1] placeholder=text required><input type=hidden name=input_label[1] value=nama_barang></div></div>','nama_barang','2019-10-27 05:13:37','2019-10-27 05:13:37',NULL),
(198,85,'<div id=card-input-2 data-required=Yes data-key=harga_barang data-id=2 class=card-input><div class=form-group><label>Harga Barang</label><input class=form-control type=text name=input_value[2] placeholder=text required><input type=hidden name=input_label[2] value=harga_barang></div></div>','harga_barang','2019-10-27 05:13:37','2019-10-27 05:13:37',NULL),
(202,86,'<div id=card-input-0 data-required=Yes data-key=nama_customer data-id=0 class=card-input><div class=form-group><label>Nama Customer</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=nama_customer></div></div>','nama_customer','2019-10-27 05:15:37','2019-10-27 05:15:37',NULL),
(203,86,'<div id=card-input-1 data-required=Yes data-key=telp_customer data-id=1 class=card-input><div class=form-group><label>No Hp/Telp</label><input class=form-control type=number name=input_value[1] placeholder=number required><input type=hidden name=input_label[1] value=telp_customer></div></div>','telp_customer','2019-10-27 05:15:38','2019-10-27 05:15:38',NULL),
(204,86,'<div id=card-input-2 data-required=Yes data-key=alamat_customer data-id=2 class=card-input><div class=form-group><label>Alamat Customer</label><input class=form-control type=text name=input_value[2] placeholder=text required><input type=hidden name=input_label[2] value=alamat_customer></div></div>','alamat_customer','2019-10-27 05:15:38','2019-10-27 05:15:38',NULL),
(215,87,'<div id=card-input-0 data-required=Yes data-key=nama_barang data-id=0 class=card-input><div class=form-group><label>Nama Barang</label><input class=form-control type=text name=input_value[0] placeholder=text required><input type=hidden name=input_label[0] value=nama_barang></div></div>','nama_barang','2019-10-29 11:50:22','2019-10-29 11:50:22',NULL),
(216,87,'<div id=card-input-2 data-required=Yes data-key=sad data-id=2 class=card-input><div class=form-group><label>das</label><select class=select2 name=input_value[2]><option data-is-empty=1>-- Choose das --</option><option>a</option><option>v</option><input type=hidden name=input_label[2] value=sad></div></div>','sad','2019-10-29 11:50:22','2019-10-29 11:50:22',NULL);

/*Table structure for table `forms` */

DROP TABLE IF EXISTS `forms`;

CREATE TABLE `forms` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `form_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `forms_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=latin1;

/*Data for the table `forms` */

insert  into `forms`(`id`,`project_id`,`title`,`description`,`form_name`,`created_at`,`updated_at`) values 
(7,NULL,'Product Input Form',NULL,NULL,'2019-06-03 01:50:01','2019-06-03 01:50:01'),
(8,NULL,'Test Form',NULL,NULL,'2019-06-03 01:53:42','2019-06-03 01:53:42'),
(9,NULL,'asdas',NULL,NULL,'2019-06-03 02:48:55','2019-06-03 02:48:55'),
(10,NULL,'sad',NULL,NULL,'2019-06-03 02:51:43','2019-06-03 02:51:43'),
(11,NULL,'sad',NULL,NULL,'2019-06-03 02:56:45','2019-06-03 02:56:45'),
(12,NULL,'asd',NULL,NULL,'2019-06-03 03:01:36','2019-06-03 03:01:36'),
(13,NULL,'Form Mhs',NULL,NULL,'2019-06-03 05:50:46','2019-06-03 05:50:46'),
(14,NULL,'Form Mhs',NULL,NULL,'2019-06-03 05:52:14','2019-06-03 05:52:14'),
(15,NULL,'MHS',NULL,NULL,'2019-06-03 05:58:08','2019-06-03 05:58:08'),
(16,NULL,'MHS',NULL,NULL,'2019-06-03 06:00:48','2019-06-03 06:00:48'),
(17,NULL,'Siswa',NULL,NULL,'2019-06-03 06:04:49','2019-06-03 06:04:49'),
(18,NULL,'Siswa',NULL,NULL,'2019-06-03 06:21:46','2019-06-03 06:21:46'),
(19,NULL,'Mhs',NULL,NULL,'2019-06-03 11:50:39','2019-06-03 11:50:39'),
(20,NULL,'Siswa',NULL,NULL,'2019-06-04 03:42:18','2019-06-04 03:42:18'),
(21,NULL,'q',NULL,NULL,'2019-06-04 04:00:59','2019-06-04 04:00:59'),
(22,NULL,'Form Anjay',NULL,NULL,'2019-06-10 04:41:17','2019-06-10 04:41:17'),
(23,NULL,'adsad','d',NULL,'2019-06-15 13:26:10','2019-06-15 13:26:10'),
(24,NULL,'Form Data Dosen',NULL,NULL,'2019-06-19 17:38:16','2019-06-19 17:38:16'),
(25,NULL,'AA','sdsd',NULL,'2019-06-19 17:58:50','2019-06-19 17:58:50'),
(26,NULL,'Penginputan Barang',NULL,NULL,'2019-09-25 07:02:01','2019-09-25 07:02:01'),
(27,NULL,'asdas',NULL,NULL,'2019-09-25 07:14:43','2019-09-25 07:14:43'),
(28,NULL,'asdas',NULL,NULL,'2019-09-25 07:41:48','2019-09-25 07:41:48'),
(29,NULL,'sadas',NULL,NULL,'2019-09-25 07:44:59','2019-09-25 07:44:59'),
(30,NULL,'sadas',NULL,NULL,'2019-09-25 07:44:59','2019-09-25 07:44:59'),
(31,NULL,'Barang',NULL,NULL,'2019-09-25 14:07:58','2019-09-25 14:07:58'),
(32,NULL,'Barang',NULL,NULL,'2019-09-25 14:12:33','2019-09-25 14:12:33'),
(33,NULL,'adasd',NULL,NULL,'2019-09-25 14:13:59','2019-09-25 14:13:59'),
(34,NULL,'ads','ad',NULL,'2019-09-25 14:15:51','2019-09-25 14:15:51'),
(35,NULL,'tertert','erter',NULL,'2019-09-25 14:23:24','2019-09-25 14:23:24'),
(36,NULL,'tertert','erter',NULL,'2019-09-25 14:23:56','2019-09-25 14:23:56'),
(37,NULL,'tertert','erter',NULL,'2019-09-25 14:54:21','2019-09-25 14:54:21'),
(38,NULL,'!!!',NULL,NULL,'2019-09-25 15:05:23','2019-09-25 15:05:23'),
(39,NULL,'!!!',NULL,NULL,'2019-09-25 15:07:35','2019-09-25 15:07:35'),
(40,NULL,'anjay',NULL,NULL,'2019-09-25 15:18:30','2019-09-25 15:18:30'),
(41,NULL,'anjytt',NULL,NULL,'2019-09-25 15:23:57','2019-09-25 15:23:57'),
(42,NULL,'anjytt',NULL,NULL,'2019-09-25 15:24:17','2019-09-25 15:24:17'),
(43,NULL,'Form Barang','form untuk penginputan barang',NULL,'2019-09-25 15:30:46','2019-09-25 15:30:46'),
(44,NULL,'FormBG','adad',NULL,'2019-09-25 15:39:35','2019-09-25 15:39:35'),
(45,NULL,'FormBG2','asdsad',NULL,'2019-09-25 15:40:50','2019-09-25 15:40:50'),
(46,NULL,'FormBG2','adasd',NULL,'2019-09-25 15:43:20','2019-09-25 15:43:20'),
(47,NULL,'FormBG2','adasd',NULL,'2019-09-25 15:46:57','2019-09-25 15:46:57'),
(48,NULL,'FormBG2','asdad',NULL,'2019-09-25 15:48:59','2019-09-25 15:48:59'),
(49,NULL,'FormBG2','asdas',NULL,'2019-09-25 15:53:02','2019-09-25 15:53:02'),
(50,NULL,'fsdsf',NULL,NULL,'2019-09-29 10:57:08','2019-09-29 10:57:08'),
(51,NULL,'Form Mahasiswa','input data mahasiswa unud',NULL,'2019-10-02 05:50:01','2019-10-02 05:50:01'),
(52,NULL,'Form Mahasiswa',NULL,NULL,'2019-10-02 06:22:44','2019-10-02 06:22:44'),
(53,NULL,'Form Mahasiswa',NULL,NULL,'2019-10-02 06:23:28','2019-10-02 06:23:28'),
(54,NULL,'sfsd',NULL,NULL,'2019-10-02 06:36:49','2019-10-02 06:36:49'),
(55,NULL,'Form Mahasiswa','Input Data Mhs Unud',NULL,'2019-10-02 06:38:10','2019-10-02 06:38:10'),
(56,NULL,'Form Mahasiswa',NULL,NULL,'2019-10-02 06:40:22','2019-10-02 06:40:22'),
(57,NULL,'FormMahasiswa','asdasdfas',NULL,'2019-10-09 07:29:42','2019-10-09 07:29:42'),
(58,NULL,'FormMahasiswa','asdasdfas',NULL,'2019-10-09 07:30:10','2019-10-09 07:30:10'),
(59,NULL,'asd','asd',NULL,'2019-10-09 07:35:21','2019-10-09 07:35:21'),
(60,NULL,'FormMahasiswa','dasdas',NULL,'2019-10-09 07:44:02','2019-10-09 07:44:02'),
(61,NULL,'FormMahasiswa','dasdas',NULL,'2019-10-09 07:44:43','2019-10-09 07:44:43'),
(62,NULL,'asd','asd',NULL,'2019-10-09 08:51:13','2019-10-09 08:51:13'),
(63,NULL,'asd','asd',NULL,'2019-10-09 08:51:22','2019-10-09 08:51:22'),
(64,NULL,'asd','asd',NULL,'2019-10-09 08:57:22','2019-10-09 08:57:22'),
(65,NULL,'asd','asd',NULL,'2019-10-09 08:57:50','2019-10-09 08:57:50'),
(66,NULL,'asd','asd',NULL,'2019-10-09 08:58:04','2019-10-09 08:58:04'),
(67,NULL,'asd','asd',NULL,'2019-10-09 08:58:11','2019-10-09 08:58:11'),
(68,NULL,'weer','wer',NULL,'2019-10-09 09:04:00','2019-10-09 09:04:00'),
(85,14,'Form Barang',NULL,'master_barang','2019-10-27 05:13:37','2019-10-27 05:13:37'),
(86,14,'Form Customer',NULL,'master_customer','2019-10-27 05:15:06','2019-10-27 05:15:06'),
(87,6,'Form Barang',NULL,'tb_barang','2019-10-29 08:45:37','2019-10-29 08:45:37');

/*Table structure for table `input_types` */

DROP TABLE IF EXISTS `input_types`;

CREATE TABLE `input_types` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `input_type` varchar(50) DEFAULT NULL,
  `html` varchar(50) DEFAULT NULL,
  `is_option` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `input_types` */

insert  into `input_types`(`id`,`input_type`,`html`,`is_option`,`created_at`,`updated_at`) values 
(1,'Text','text',0,'2019-10-09 21:34:20','2019-10-09 21:34:20'),
(2,'Number','number',0,'2019-10-09 21:34:20','2019-10-09 21:34:20'),
(3,'Multiple Choice','radio',1,'2019-10-09 21:34:20','2019-10-09 21:34:20'),
(4,'Checkbox','checkbox',1,'2019-10-09 21:34:20','2019-10-09 21:34:20'),
(5,'Dropdown','dropdown',1,'2019-10-09 21:34:20','2019-10-09 21:34:20'),
(6,'File Upload','file',0,'2019-10-09 21:34:20','2019-10-09 21:34:20'),
(7,'Date','date',0,'2019-10-09 21:34:20','2019-10-09 21:34:20'),
(8,'Time','time',0,'2019-10-09 21:34:20','2019-10-09 21:34:20'),
(9,'Datetime','datetime-local',0,'2019-10-09 21:34:20','2019-10-09 21:34:20'),
(10,'Table Modal','tablemodal',2,'2019-10-09 21:34:20','2019-10-09 21:34:20');

/*Table structure for table `projects` */

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `project_name` varchar(100) DEFAULT NULL,
  `dropbox_app_key` varchar(100) DEFAULT NULL,
  `dropbox_app_secret` varchar(100) DEFAULT NULL,
  `dropbox_access_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

/*Data for the table `projects` */

insert  into `projects`(`id`,`user_id`,`project_name`,`dropbox_app_key`,`dropbox_app_secret`,`dropbox_access_token`,`created_at`,`updated_at`) values 
(6,5,'Project2','pguozkqfb6vn8w9','dw5h7xegfdm356a','apa_LdNqwrsAAAAAAAABfUSb9a8JZ5YuUMOK9FWi3oQp2AnPKyl8bARec7pjPns2','2019-10-09 21:39:13','2019-10-29 08:49:08'),
(14,5,'Toko','pguozkqfb6vn8w9','dw5h7xegfdm356a','apa_LdNqwrsAAAAAAAABfUSb9a8JZ5YuUMOK9FWi3oQp2AnPKyl8bARec7pjPns2','2019-10-09 13:42:10','2019-10-27 06:14:04');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`remember_token`,`created_at`,`updated_at`) values 
(5,'Yanamon','gusyana124@gmail.com',NULL,'$2y$10$hEpqb.ZEFSmiXsgV7KcCr.dT89t6V5oYIB0RFEN5jF2OTHX6AxGkC',NULL,'2019-10-09 11:55:11','2019-10-09 11:55:11');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
