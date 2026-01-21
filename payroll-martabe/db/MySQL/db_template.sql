/*
SQLyog Community v13.1.1 (64 bit)
MySQL - 10.1.34-MariaDB : Database - db_template
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_template` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `db_template`;

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `mst_menu` */

insert  into `mst_menu`(`menu_id`,`app_code`,`menu_name`,`menu_url`,`icon`,`menu_group`,`description`,`menu_no`,`group_no`,`is_active`) values 
(1,'ADM','Menu','admin/menu/','fas fa-user-tie','Admin',NULL,1,3,1),
(2,'ADM','Users','admin/users/','fas fa-user-tie','Admin',NULL,2,3,1),
(3,'ADM','User Access','admin/access/','fas fa-user-tie','Admin',NULL,3,3,1),
(4,'MST','Items','master/items/','fas fa-desktop','Master',NULL,4,1,1),
(5,'TRN','Sales','transaction/sales/','fas fa-file-alt','Transaction',NULL,1,2,1),
(6,'TLS','Recalcullate','tools/recalculate/','fas fa-tools','Tools',NULL,1,4,1),
(7,'HLP','Tutorial','help/tutorial/','fas fa-question','Help',NULL,1,5,1);

/*Table structure for table `mst_user` */

DROP TABLE IF EXISTS `mst_user`;

CREATE TABLE `mst_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `nip` varchar(15) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `user_password` varchar(30) NOT NULL,
  `signature` varchar(200) DEFAULT NULL,
  `phone_imei` varchar(25) DEFAULT NULL,
  `id_user_group` varchar(30) NOT NULL,
  `id_user_level` int(5) NOT NULL,
  `user_app_level` int(1) DEFAULT NULL COMMENT 'Tingkatan pada approve PR',
  `last_login` datetime DEFAULT NULL,
  `last_ip` varchar(45) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `pic_input` int(11) DEFAULT NULL,
  `input_time` datetime DEFAULT NULL,
  `pic_edit` int(11) DEFAULT NULL,
  `edit_time` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `mst_user` */

insert  into `mst_user`(`user_id`,`nip`,`full_name`,`user_name`,`user_password`,`signature`,`phone_imei`,`id_user_group`,`id_user_level`,`user_app_level`,`last_login`,`last_ip`,`is_active`,`pic_input`,`input_time`,`pic_edit`,`edit_time`) values 
(1,'123456789','Admin','admin','asd','admin.png',NULL,'1',3,1,'2018-10-15 16:03:32','192.168.10.38',1,1,'2018-06-11 08:15:13',NULL,NULL);

/*Table structure for table `mst_user_activity` */

DROP TABLE IF EXISTS `mst_user_activity`;

CREATE TABLE `mst_user_activity` (
  `activity_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `activity` varchar(255) DEFAULT NULL,
  `module` varchar(100) DEFAULT NULL,
  `table` varchar(100) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `pic_input` int(11) DEFAULT NULL,
  `input_time` datetime DEFAULT NULL,
  PRIMARY KEY (`activity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `mst_user_activity` */

insert  into `mst_user_activity`(`activity_id`,`activity`,`module`,`table`,`ip`,`pic_input`,`input_time`) values 
(1,'logged in','User','mst_user','192.168.10.47',1,'2018-07-02 10:56:57'),
(2,'logged out','User','mst_user','192.168.10.47',1,'2018-07-02 10:57:15');

/*Table structure for table `trn_user_menu` */

DROP TABLE IF EXISTS `trn_user_menu`;

CREATE TABLE `trn_user_menu` (
  `user_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  UNIQUE KEY `user_id` (`user_id`,`menu_id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `trn_user_menu_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `mst_user` (`user_id`) ON UPDATE CASCADE,
  CONSTRAINT `trn_user_menu_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `mst_menu` (`menu_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `trn_user_menu` */

insert  into `trn_user_menu`(`user_id`,`menu_id`) values 
(NULL,NULL),
(1,1),
(1,2),
(1,3),
(1,4),
(1,5),
(1,6),
(1,7);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
