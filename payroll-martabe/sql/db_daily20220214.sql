/*
SQLyog Community v13.1.6 (64 bit)
MySQL - 10.1.45-MariaDB : Database - db_hris_daily
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_hris_daily` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `db_hris_daily`;

/*Table structure for table `mst_menu` */

DROP TABLE IF EXISTS `mst_menu`;

CREATE TABLE `mst_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `app_code` varchar(4) DEFAULT NULL,
  `menu_name` varchar(50) NOT NULL,
  `menu_url` varchar(75) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `menu_group` varchar(20) NOT NULL,
  `description` text,
  `menu_no` smallint(2) DEFAULT NULL,
  `group_no` smallint(2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

/*Data for the table `mst_menu` */

insert  into `mst_menu`(`menu_id`,`app_code`,`menu_name`,`menu_url`,`icon`,`menu_group`,`description`,`menu_no`,`group_no`,`is_active`) values 
(1,'ADM','Menu','admin/menu','fas fa-user-tie','Admin',NULL,1,3,1),
(2,'ADM','Users','admin/users','fas fa-user-tie','Admin',NULL,2,3,1),
(3,'ADM','User Access','admin/access','fas fa-user-tie','Admin',NULL,3,3,1),
(4,'MST','Biodata','master/biodata','fas fa-desktop','Master',NULL,4,1,1),
(20,'MST','Salary','master/mt_salary','fas fa-desktop','Master',NULL,5,1,1),
(21,'MST','Bank','master/mt_bank','fas fa-desktop','Master',NULL,6,1,1),
(22,'MST','Konfigurasi','master/mt_config','fas fa-desktop','Master',NULL,7,1,1);

/*Table structure for table `mst_user` */

DROP TABLE IF EXISTS `mst_user`;

CREATE TABLE `mst_user` (
  `user_id` varchar(11) NOT NULL,
  `nip` varchar(15) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `user_name` varchar(25) NOT NULL,
  `user_password` varchar(30) NOT NULL,
  `user_group` varchar(20) DEFAULT NULL,
  `phone_imei` varchar(25) DEFAULT NULL,
  `user_level` int(1) DEFAULT NULL COMMENT 'Tingkatan pada approve PR',
  `last_login` datetime DEFAULT NULL,
  `last_ip` varchar(45) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `pic_input` int(15) DEFAULT NULL,
  `input_time` datetime DEFAULT NULL,
  `pic_edit` int(15) DEFAULT NULL,
  `edit_time` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `mst_user` */

insert  into `mst_user`(`user_id`,`nip`,`full_name`,`user_name`,`user_password`,`user_group`,`phone_imei`,`user_level`,`last_login`,`last_ip`,`is_active`,`pic_input`,`input_time`,`pic_edit`,`edit_time`) values 
('19','1999','Venny','venny','1234',NULL,NULL,1,NULL,NULL,1,NULL,NULL,NULL,NULL),
('20','2021','Ricky','ricky','1234',NULL,NULL,1,NULL,NULL,1,NULL,NULL,NULL,NULL),
('21','12154','tmk','tmk','password',NULL,NULL,1,NULL,NULL,1,NULL,NULL,NULL,NULL),
('tmk','123456789','Admin','admin','212',NULL,NULL,1,'2018-10-15 16:03:32','192.168.10.38',1,1,'2018-06-11 08:15:13',NULL,NULL);

/*Table structure for table `mt_attendance` */

DROP TABLE IF EXISTS `mt_attendance`;

CREATE TABLE `mt_attendance` (
  `att_id` varchar(15) NOT NULL,
  `biodata_id` varchar(15) DEFAULT NULL,
  `year_period` int(4) NOT NULL,
  `month_period` varchar(2) NOT NULL,
  `day_count` int(2) NOT NULL DEFAULT '0' COMMENT 'Jumlah Hari',
  PRIMARY KEY (`att_id`),
  KEY `biodata_id` (`biodata_id`),
  CONSTRAINT `mt_attendance_ibfk_1` FOREIGN KEY (`biodata_id`) REFERENCES `mt_biodata_01` (`biodata_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `mt_attendance` */

/*Table structure for table `mt_bank` */

DROP TABLE IF EXISTS `mt_bank`;

CREATE TABLE `mt_bank` (
  `bank_id` varchar(8) NOT NULL,
  `bank_code` varchar(8) DEFAULT NULL,
  `bank_name` varchar(35) DEFAULT NULL,
  `is_local` tinyint(1) DEFAULT '1',
  `is_active` tinyint(1) DEFAULT '1',
  `pic_input` varchar(15) DEFAULT NULL,
  `input_time` datetime DEFAULT NULL,
  `pic_edit` varchar(15) DEFAULT NULL,
  `edit_time` datetime DEFAULT NULL,
  PRIMARY KEY (`bank_id`),
  UNIQUE KEY `bank_code` (`bank_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `mt_bank` */

insert  into `mt_bank`(`bank_id`,`bank_code`,`bank_name`,`is_local`,`is_active`,`pic_input`,`input_time`,`pic_edit`,`edit_time`) values 
('0','0','BNI',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('0020307','0020307','BRIIIIII',0,1,'dian.puspi','0000-00-00 00:00:00','venny','2020-08-26 13:30:20'),
('0080017','0080017','MANDIRI',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('0110042','0110042','DANAMON',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('0130307','0130307','PERMATA',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('0140397','0140397','BCA',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('0160131','0160131','MayBANK',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('0190017','0190017','PANIN',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('0220026','0220026','CIMB Niaga',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('0229920','0229920','CIMB Niaga Syariah',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('0230016','0230016','UOB',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('0280024','0280024','OCBC NISP',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('0310305','0310305','CITIBANK',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('0500306','0500306','STANDARD CHARTERED',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('0870010','0870010','HSBC',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('10100','10100','Bank 9',0,1,'Venny','2020-12-08 08:49:09',NULL,NULL),
('1110012','1110012','DKI',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('1114','1114','Foreign1',0,1,'venny','2020-08-25 08:17:36',NULL,NULL),
('1115','1115','foreign2',0,1,'venny','2020-08-25 08:17:48',NULL,NULL),
('1116','1116','Foreign3',0,1,'venny','2020-08-25 08:17:59',NULL,NULL),
('123456','test','cek cek',1,1,'venny','2022-01-17 08:39:58','venny','2022-01-17 08:40:21'),
('1320019','1320019','PAPUA',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('1470011','1470011','MUAMALAT',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('1525','1525','BNI',1,1,'venny','2020-08-19 14:18:19',NULL,NULL),
('1555','1555','BANK INDONESIA',1,1,'venny','2020-08-19 14:18:39','venny','2020-08-19 15:18:57'),
('1586','BNI','Bank Negara Indonesia',1,1,'venny','2021-08-06 09:30:45',NULL,NULL),
('1717','1717','DIAMONDD',1,1,'venny','2020-08-19 15:31:35','venny','2020-08-19 15:32:39'),
('1789','1789','MANDIRIIIIII',0,1,'venny','2020-08-19 15:24:39','venny','2020-08-25 15:45:42'),
('1989','1989','Bank Foreign 19',0,1,'venny','2021-03-01 13:49:57',NULL,NULL),
('1999','1999','Bank Local 19',1,1,'ricky','2021-03-01 13:31:49',NULL,NULL),
('3265','TEST4','TEST4',0,1,'venny','2020-09-28 10:17:52',NULL,NULL),
('4220051','4220051','BRI Syariah',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('4260121','4260121','MEGA',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('4270027','4270027','BNI Syariah',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('4410010','4410010','BUKOPIN',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('4444','FRGN 5','Foreign 5',1,1,'venny','2020-12-21 08:54:27','venny','2020-12-21 08:55:04'),
('4510017','4510017','MANDIRI Syariah',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('457','new ni','bank new',0,1,'venny','2022-01-14 08:32:42','venny','2022-01-14 09:56:01'),
('5060016','5060016','MEGA Syariah',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('5210031','5210031','BUKOPIN Syariah',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('5360017','5360017','BCA Syariah',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('555','SRV1','Server 1',1,1,'venny','2021-01-04 11:19:04','venny','2021-01-04 11:19:29'),
('6554','215','TEST 20',0,1,'venny','2020-12-30 10:50:55',NULL,NULL),
('777','FRGN 8','Foreign 7',0,1,'venny','2020-12-07 10:54:55','Venny','2020-12-17 15:22:53'),
('888','MNDR3','Mandiri3',0,1,'venny','2020-09-28 09:23:49','Venny','2020-12-08 08:50:11'),
('9500307','9500307','COMMONWEALTH',1,1,'dian.puspi','0000-00-00 00:00:00','','0000-00-00 00:00:00'),
('999','MDRI','Mandiri2',1,1,'venny','2020-09-28 09:23:07',NULL,NULL);

/*Table structure for table `mt_biodata_01` */

DROP TABLE IF EXISTS `mt_biodata_01`;

CREATE TABLE `mt_biodata_01` (
  `biodata_id` varchar(15) NOT NULL,
  `dept_id` int(10) DEFAULT NULL,
  `full_name` varchar(35) DEFAULT NULL,
  `first_name` varchar(15) DEFAULT NULL,
  `middle_name` varchar(15) DEFAULT NULL,
  `last_name` varchar(15) DEFAULT NULL,
  `place_of_birth` varchar(30) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `ethnic` varchar(30) DEFAULT NULL,
  `nationality` varchar(30) DEFAULT NULL,
  `company` varchar(15) DEFAULT NULL,
  `dept` varchar(50) DEFAULT NULL,
  `emp_position` varchar(50) DEFAULT NULL,
  `passport_no` varchar(30) DEFAULT NULL,
  `placement` varchar(15) DEFAULT NULL,
  `approve_level` varchar(15) DEFAULT NULL,
  `internal_id` varchar(11) DEFAULT NULL,
  `position_level` smallint(6) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `id_card_no` varchar(30) DEFAULT NULL,
  `id_card_address` varchar(150) DEFAULT NULL,
  `current_address` varchar(150) DEFAULT NULL,
  `residence_status` varchar(30) DEFAULT NULL,
  `religion` varchar(15) DEFAULT NULL,
  `driving_license` varchar(10) DEFAULT NULL,
  `marital_status` varchar(15) DEFAULT NULL,
  `emp_height` smallint(6) DEFAULT '0',
  `emp_weight` smallint(6) DEFAULT '0',
  `blood_type` varchar(5) DEFAULT NULL,
  `email_address` varchar(40) DEFAULT NULL,
  `telp_no` varchar(15) DEFAULT NULL,
  `cell_no` varchar(15) DEFAULT NULL,
  `is_glasses` smallint(6) DEFAULT '0',
  `emp_status` varchar(25) DEFAULT NULL,
  `tax_no` varchar(20) DEFAULT NULL,
  `bpjs_no` varchar(20) DEFAULT NULL,
  `bpjs_tk_no` varchar(20) DEFAULT NULL,
  `is_active` smallint(6) DEFAULT '1',
  `pic_input` varchar(15) DEFAULT NULL,
  `input_time` datetime DEFAULT NULL,
  `pic_edit` varchar(15) DEFAULT NULL,
  `edit_time` datetime DEFAULT NULL,
  PRIMARY KEY (`biodata_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `mt_biodata_01` */

insert  into `mt_biodata_01`(`biodata_id`,`dept_id`,`full_name`,`first_name`,`middle_name`,`last_name`,`place_of_birth`,`date_of_birth`,`gender`,`ethnic`,`nationality`,`company`,`dept`,`emp_position`,`passport_no`,`placement`,`approve_level`,`internal_id`,`position_level`,`join_date`,`id_card_no`,`id_card_address`,`current_address`,`residence_status`,`religion`,`driving_license`,`marital_status`,`emp_height`,`emp_weight`,`blood_type`,`email_address`,`telp_no`,`cell_no`,`is_glasses`,`emp_status`,`tax_no`,`bpjs_no`,`bpjs_tk_no`,`is_active`,`pic_input`,`input_time`,`pic_edit`,`edit_time`) values 
('121212',0,'hallo venny yoanit','hallo','venny','yoanit','jakarta','1999-12-08','','jawa','Indonesia','SANGATI LM','IT','Staff','457854','Jakart','II','-1',5,'2021-12-12','1542656845212458','Jl. Juanda','Jl. Juanda','no','Islam','no','K0',55,156,'o','venny@yahoo.com','021242222','12215454',0,'Aktif','120112121','21212122','12445',0,'venny','2022-01-13 11:20:48','venny','2022-01-13 11:35:15'),
('1987',0,'Hyuk Test H','Hyuk','Test','H','Jakarta','1999-12-10','Female','jawa','Indonesia','LM System','IT','Staff','457854','Jakarta','3','1545452',5,'2022-01-11','15454','Jl. Juanda','Jl. Juanda','no','Islam','no','S0',44,98,'O','hyuk@gmail.com','12121','2121212',0,'Aktif','21215','1551','512',0,'venny','2022-01-11 10:54:42','venny','2022-01-11 15:46:56');

/*Table structure for table `mt_biodata_02` */

DROP TABLE IF EXISTS `mt_biodata_02`;

CREATE TABLE `mt_biodata_02` (
  `education_id` varchar(15) NOT NULL,
  `biodata_id` varchar(15) DEFAULT NULL,
  `school_name` varchar(50) DEFAULT NULL,
  `education_year` smallint(4) DEFAULT NULL,
  `major` varchar(35) DEFAULT NULL,
  `city_name` varchar(30) DEFAULT NULL,
  `is_certified` tinyint(1) DEFAULT NULL,
  `pic_input` varchar(15) DEFAULT NULL,
  `input_time` datetime DEFAULT NULL,
  `pic_edit` varchar(15) DEFAULT NULL,
  `edit_time` datetime DEFAULT NULL,
  PRIMARY KEY (`education_id`),
  KEY `biodata_id` (`biodata_id`),
  CONSTRAINT `mt_biodata_02_ibfk_1` FOREIGN KEY (`biodata_id`) REFERENCES `mt_biodata_01` (`biodata_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `mt_biodata_02` */

insert  into `mt_biodata_02`(`education_id`,`biodata_id`,`school_name`,`education_year`,`major`,`city_name`,`is_certified`,`pic_input`,`input_time`,`pic_edit`,`edit_time`) values 
('220100001','121212','test',1999,'test','test',1,'venny','2022-01-13 11:35:15','','0000-00-00 00:00:00'),
('220100002','121212','ff',2000,'halo','Jakarta',1,'venny','2022-01-13 11:35:15','','0000-00-00 00:00:00'),
('220100003','121212','ggg',2000,'halo','Jakarta',1,'venny','2022-01-13 11:35:15','','0000-00-00 00:00:00');

/*Table structure for table `mt_biodata_03` */

DROP TABLE IF EXISTS `mt_biodata_03`;

CREATE TABLE `mt_biodata_03` (
  `experience_id` varchar(15) NOT NULL,
  `biodata_id` varchar(15) DEFAULT NULL,
  `company_name` varchar(60) DEFAULT NULL,
  `work_year` smallint(4) DEFAULT NULL,
  `work_period` smallint(1) DEFAULT NULL,
  `job_position` varchar(100) DEFAULT NULL,
  `job_desc` varchar(150) DEFAULT NULL,
  `moving_reason` varchar(180) DEFAULT NULL,
  `pic_input` varchar(15) DEFAULT NULL,
  `input_time` datetime DEFAULT NULL,
  `pic_edit` varchar(15) DEFAULT NULL,
  `edit_time` datetime DEFAULT NULL,
  PRIMARY KEY (`experience_id`),
  KEY `biodata_id` (`biodata_id`),
  CONSTRAINT `mt_biodata_03_ibfk_1` FOREIGN KEY (`biodata_id`) REFERENCES `mt_biodata_01` (`biodata_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `mt_biodata_03` */

insert  into `mt_biodata_03`(`experience_id`,`biodata_id`,`company_name`,`work_year`,`work_period`,`job_position`,`job_desc`,`moving_reason`,`pic_input`,`input_time`,`pic_edit`,`edit_time`) values 
('220100001','121212','nnknk',2022,1,'nknknk','nknk','Resign','venny','2022-01-13 11:35:15','','1700-01-01 00:00:00'),
('220100002','121212','gd',2000,1,'gd','gd','Resign','venny','2022-01-13 11:35:15','','1700-01-01 00:00:00');

/*Table structure for table `mt_biodata_04` */

DROP TABLE IF EXISTS `mt_biodata_04`;

CREATE TABLE `mt_biodata_04` (
  `skill_id` varchar(15) NOT NULL,
  `biodata_id` varchar(15) DEFAULT NULL,
  `skill` varchar(50) DEFAULT NULL,
  `remarks` varchar(200) DEFAULT NULL,
  `pic_input` varchar(15) DEFAULT NULL,
  `input_time` datetime DEFAULT NULL,
  `pic_edit` varchar(15) DEFAULT NULL,
  `edit_time` datetime DEFAULT NULL,
  PRIMARY KEY (`skill_id`),
  KEY `biodata_id` (`biodata_id`),
  CONSTRAINT `mt_biodata_04_ibfk_1` FOREIGN KEY (`biodata_id`) REFERENCES `mt_biodata_01` (`biodata_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `mt_biodata_04` */

insert  into `mt_biodata_04`(`skill_id`,`biodata_id`,`skill`,`remarks`,`pic_input`,`input_time`,`pic_edit`,`edit_time`) values 
('220100001','121212','fsf','add','venny','2022-01-13 11:35:15','','1700-01-01 00:00:00'),
('220100002','121212','dfd','affd','venny','2022-01-13 11:35:15','','1700-01-01 00:00:00'),
('220100003','121212','gg','gg','venny','2022-01-13 11:35:15','','1700-01-01 00:00:00');

/*Table structure for table `mt_configuration` */

DROP TABLE IF EXISTS `mt_configuration`;

CREATE TABLE `mt_configuration` (
  `conf_id` varchar(11) NOT NULL,
  `conf_code` varchar(10) NOT NULL,
  `conf_name` varchar(20) NOT NULL,
  `conf_value` varchar(20) NOT NULL,
  `remarks` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`conf_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `mt_configuration` */

insert  into `mt_configuration`(`conf_id`,`conf_code`,`conf_name`,`conf_value`,`remarks`) values 
('2021020100','123565','Test','1500000','test'),
('BPJ-001','BPJ-001','BPJS Kesehatan','4','Dalam Persen'),
('JHT-001','JHT-001','JHT','2','Dalam Persen'),
('JKK-001','JKK-001','JKK','0.24','Dalam Persen'),
('JKM-001','JKM-001','JKM','0.3','Dalam Persen'),
('PEN-001','PEN-001','PENSIUN','1','Maksimal 87.500.46');

/*Table structure for table `mt_dept` */

DROP TABLE IF EXISTS `mt_dept`;

CREATE TABLE `mt_dept` (
  `dept_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Table Id',
  `dept_code` varchar(10) DEFAULT NULL COMMENT 'Class Code',
  `dept_name` varchar(25) DEFAULT NULL COMMENT 'Class Name',
  `is_active` tinyint(1) DEFAULT '1',
  `pic_input` varchar(15) DEFAULT NULL,
  `input_time` datetime DEFAULT NULL,
  `pic_edit` varchar(15) DEFAULT NULL,
  `edit_time` datetime DEFAULT NULL,
  PRIMARY KEY (`dept_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

/*Data for the table `mt_dept` */

insert  into `mt_dept`(`dept_id`,`dept_code`,`dept_name`,`is_active`,`pic_input`,`input_time`,`pic_edit`,`edit_time`) values 
(10,'326','tes',1,'venny','2021-04-09 10:28:43',NULL,NULL),
(11,'659','tes2',0,'venny','2021-04-09 10:28:51',NULL,NULL),
(12,'123','test3',1,'venny','2021-04-09 10:29:01',NULL,NULL),
(17,'111','tes',1,'venny','2021-05-11 10:20:05','venny','2021-05-11 10:20:15'),
(18,'1010','Test4',1,'venny','2021-05-27 14:39:39',NULL,NULL),
(19,'2805','ServerTes',1,'venny','2021-05-28 09:14:53','venny','2021-05-28 09:15:00'),
(20,'GBC Area 1','GBC Area 1',1,'ricky','2021-08-13 07:57:30','ricky','2021-08-13 08:05:42'),
(21,'Raisebore','Raisebore',1,'ricky','2021-08-13 07:57:47','ricky','2021-08-13 08:09:19'),
(22,'Engineerin','Engineering',1,'ricky','2021-08-13 07:58:04','ricky','2021-08-13 08:05:25'),
(23,'GBC Area 2','GBC Area 2',1,'ricky','2021-08-13 07:58:30','ricky','2021-08-13 08:06:01'),
(24,'Alimak','Alimak',1,'ricky','2021-08-13 07:58:56','ricky','2021-08-13 08:02:06'),
(25,'Adm - S&T','Admin, Safety & Training',1,'ricky','2021-08-13 07:59:12','ricky','2021-08-13 08:01:58'),
(26,'DMLZ','DMLZ',1,'ricky','2021-08-13 07:59:40','ricky','2021-08-13 08:02:12'),
(27,'MCM','MCM',1,'ricky','2021-08-13 07:59:52','ricky','2021-08-13 08:09:08'),
(28,'GBC Area 3','GBC Area 3',1,'ricky','2021-08-13 08:00:06','ricky','2021-08-13 08:08:43'),
(29,'Electric','Electric',1,'ricky','2021-08-13 08:00:20','ricky','2021-08-13 08:02:21'),
(30,'GBC Produc','GBC Production',1,'ricky','2021-08-13 08:00:38','ricky','2021-08-13 08:09:00'),
(31,'GBC Constr','GBC Construction',1,'ricky','2021-08-13 08:00:55','ricky','2021-08-13 08:08:51');

/*Table structure for table `mt_salary` */

DROP TABLE IF EXISTS `mt_salary`;

CREATE TABLE `mt_salary` (
  `salary_id` varchar(15) DEFAULT NULL,
  `biodata_id` varchar(15) DEFAULT NULL,
  `id_no` varchar(20) DEFAULT NULL,
  `bank_id` varchar(8) DEFAULT NULL,
  `monthly` decimal(12,2) DEFAULT '0.00',
  `daily` decimal(12,2) DEFAULT '0.00',
  `is_active` tinyint(1) DEFAULT '1',
  `pic_input` varchar(15) DEFAULT NULL,
  `input_time` datetime DEFAULT NULL,
  `pic_edit` varchar(15) DEFAULT NULL,
  `edit_time` datetime DEFAULT NULL,
  UNIQUE KEY `badge_no` (`id_no`),
  KEY `biodata_id` (`biodata_id`),
  KEY `bank_id` (`bank_id`),
  CONSTRAINT `mt_salary_ibfk_1` FOREIGN KEY (`biodata_id`) REFERENCES `mt_biodata_01` (`biodata_id`) ON UPDATE CASCADE,
  CONSTRAINT `mt_salary_ibfk_2` FOREIGN KEY (`bank_id`) REFERENCES `mt_bank` (`bank_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `mt_salary` */

insert  into `mt_salary`(`salary_id`,`biodata_id`,`id_no`,`bank_id`,`monthly`,`daily`,`is_active`,`pic_input`,`input_time`,`pic_edit`,`edit_time`) values 
('220100001','1987','14522','0020307',350000.00,35000.00,1,'venny','2022-01-12 13:49:58','venny','2022-01-13 11:39:33'),
('220100002','121212','223312','457',3500000.00,1500000.00,1,'venny','2022-01-18 08:58:31',NULL,NULL),
('220200001','1987','123123123123','0020307',1.00,1.00,1,'venny','2022-02-02 15:11:51',NULL,NULL);

/*Table structure for table `tr_slip` */

DROP TABLE IF EXISTS `tr_slip`;

CREATE TABLE `tr_slip` (
  `slip_id` int(11) NOT NULL COMMENT 'Id Tabel',
  `biodata_id` varchar(15) NOT NULL COMMENT 'Referensi mst_bio_rec',
  `ts_id` int(10) DEFAULT NULL COMMENT 'Referensi tr_timesheet',
  `year_period` int(4) DEFAULT NULL COMMENT 'Periode Tahun Gaji',
  `month_period` varchar(2) DEFAULT NULL COMMENT 'Periode Bulan Gaji',
  `slip_period` smallint(1) DEFAULT NULL COMMENT 'Periode Gaji (1,2)',
  `full_name` varchar(30) DEFAULT NULL COMMENT 'Nama Karyawan',
  `dept` varchar(25) DEFAULT NULL COMMENT 'Dept Karyawan',
  `position` varchar(25) DEFAULT NULL COMMENT 'Posisi',
  `marital_status` varchar(15) DEFAULT NULL COMMENT 'Status Pernikahan',
  `work_total` decimal(5,2) DEFAULT '0.00' COMMENT 'Jumlah Hari/ Jam Kerja',
  `currency` varchar(4) DEFAULT NULL COMMENT 'Nama Mata Uang',
  `base_wage` decimal(11,2) DEFAULT '0.00' COMMENT 'Gaji Pokok',
  `daily_count` int(2) DEFAULT '0' COMMENT 'Jumlah Hari',
  `daily_wage` decimal(11,2) DEFAULT '0.00' COMMENT 'Total Uang Absen',
  `variable_bonus` decimal(11,2) DEFAULT '0.00',
  `allowance_01` decimal(11,2) DEFAULT '0.00',
  `allowance_02` decimal(11,2) DEFAULT '0.00' COMMENT 'Bonus Shift',
  `allowance_03` decimal(11,2) DEFAULT '0.00' COMMENT 'Bonus Lembur',
  `allowance_04` decimal(11,2) DEFAULT '0.00' COMMENT 'Insentif Bonus',
  `allowance_05` decimal(11,2) DEFAULT '0.00' COMMENT 'Bonus Produksi',
  `adjustment` decimal(11,2) DEFAULT '0.00' COMMENT 'Nilai Berupa +/-',
  `thr` decimal(11,2) DEFAULT '0.00' COMMENT 'Tunjangan Hari Raya',
  `bpjs` decimal(11,2) DEFAULT '0.00' COMMENT 'Iuran BPJS Kesehatan (Dalam persentase)',
  `jk` decimal(11,2) DEFAULT '0.00',
  `jkk` decimal(11,2) DEFAULT '0.00' COMMENT 'Jaminan Keselamatan Kerja & Kematian (Persentase)',
  `jkm` decimal(11,2) DEFAULT '0.00',
  `jp` decimal(11,2) DEFAULT '0.00' COMMENT 'Jaminan Pensiun Tanggungan Perusahaan',
  `jht` decimal(11,2) DEFAULT '0.00' COMMENT 'Jaminan Hari Tua',
  `emp_bpjs` decimal(11,2) DEFAULT '0.00' COMMENT 'Nilai BPJS Tanggungan Karyawan',
  `emp_jp` decimal(11,2) DEFAULT '0.00' COMMENT 'Jaminan Pensiun Tanggungan Karyawan',
  `emp_jht` decimal(11,2) DEFAULT '0.00' COMMENT 'Nilai JHT Tanggungan Karyawan',
  `non_tax_allowance` decimal(11,2) DEFAULT '0.00' COMMENT 'Nilai Tidak Potong Pajak',
  `ptkp_total` decimal(11,2) DEFAULT '0.00' COMMENT 'Nilai Total PTKP',
  `irregular_tax` decimal(15,2) DEFAULT '0.00' COMMENT 'Penyesuaian Keluar',
  `regular_tax` decimal(15,2) DEFAULT '0.00' COMMENT 'Nilai Pajak',
  `salary_status` varchar(10) DEFAULT NULL COMMENT 'Status Salary',
  `status_remarks` varchar(200) DEFAULT NULL COMMENT 'Keterangan Status',
  `gross_earn` decimal(10,2) DEFAULT '0.00',
  `pic_edit` varchar(15) DEFAULT NULL,
  `edit_time` datetime DEFAULT NULL,
  `pic_input` varchar(15) DEFAULT NULL,
  `input_time` datetime DEFAULT NULL,
  PRIMARY KEY (`slip_id`),
  UNIQUE KEY `bio_rec_id` (`biodata_id`,`year_period`,`month_period`,`slip_period`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tr_slip` */

insert  into `tr_slip`(`slip_id`,`biodata_id`,`ts_id`,`year_period`,`month_period`,`slip_period`,`full_name`,`dept`,`position`,`marital_status`,`work_total`,`currency`,`base_wage`,`daily_count`,`daily_wage`,`variable_bonus`,`allowance_01`,`allowance_02`,`allowance_03`,`allowance_04`,`allowance_05`,`adjustment`,`thr`,`bpjs`,`jk`,`jkk`,`jkm`,`jp`,`jht`,`emp_bpjs`,`emp_jp`,`emp_jht`,`non_tax_allowance`,`ptkp_total`,`irregular_tax`,`regular_tax`,`salary_status`,`status_remarks`,`gross_earn`,`pic_edit`,`edit_time`,`pic_input`,`input_time`) values 
(211200001,'836579',210600001,2021,'05',1,'ANTONIO  ANTIPUESTO','111','','K0',0.00,'USD',23.10,0,0.00,4.50,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,54000000.00,0.00,0.00,'','',0.00,'','1700-01-01 00:00:00','venny','2021-12-20 10:14:42'),
(211200002,'908276',210600002,2021,'05',1,'ELMER  AGUID','326','','K1',154.00,'USD',23.10,0,0.00,4.50,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,10.67,61.90,0.00,0.00,0.00,0.00,0.00,0.00,0.00,54000000.00,1042.01,15630168.33,'','',4132.63,'','1700-01-01 00:00:00','venny','2021-12-20 10:14:42'),
(211200003,'908338',210600003,2021,'05',1,'MONSERO  GONSADAN','326','','K1',154.00,'USD',23.10,0,0.00,4.50,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,10.67,61.90,0.00,0.00,0.00,0.00,0.00,0.00,0.00,54000000.00,1042.01,15630168.33,'','',4132.63,'','1700-01-01 00:00:00','venny','2021-12-20 10:14:42');

/*Table structure for table `trn_user_menu` */

DROP TABLE IF EXISTS `trn_user_menu`;

CREATE TABLE `trn_user_menu` (
  `user_id` varchar(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  UNIQUE KEY `user_id` (`user_id`,`menu_id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `trn_user_menu_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `mst_menu` (`menu_id`) ON UPDATE CASCADE,
  CONSTRAINT `trn_user_menu_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `mst_user` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `trn_user_menu` */

insert  into `trn_user_menu`(`user_id`,`menu_id`) values 
(NULL,NULL),
(NULL,NULL),
('19',NULL),
('19',1),
('19',2),
('19',3),
('19',4),
('19',20),
('19',21),
('19',22);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
