/*
SQLyog Community v13.1.7 (64 bit)
MySQL - 10.6.4-MariaDB 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

insert into `mst_menu` (`menu_id`, `app_code`, `menu_name`, `menu_url`, `icon`, `menu_group`, `description`, `menu_no`, `group_no`, `is_active`) values('1','ADM','Menu','admin/menu','fas fa-user-tie','Admin',NULL,'1','3','1');
insert into `mst_menu` (`menu_id`, `app_code`, `menu_name`, `menu_url`, `icon`, `menu_group`, `description`, `menu_no`, `group_no`, `is_active`) values('2','ADM','Users','admin/users','fas fa-user-tie','Admin',NULL,'2','3','1');
insert into `mst_menu` (`menu_id`, `app_code`, `menu_name`, `menu_url`, `icon`, `menu_group`, `description`, `menu_no`, `group_no`, `is_active`) values('3','ADM','User Access','admin/access','fas fa-user-tie','Admin',NULL,'3','3','1');
insert into `mst_menu` (`menu_id`, `app_code`, `menu_name`, `menu_url`, `icon`, `menu_group`, `description`, `menu_no`, `group_no`, `is_active`) values('4','MST','Biodata','master/biodata','fas fa-desktop','Master',NULL,'4','1','1');
insert into `mst_menu` (`menu_id`, `app_code`, `menu_name`, `menu_url`, `icon`, `menu_group`, `description`, `menu_no`, `group_no`, `is_active`) values('20','MST','Salary','master/mt_salary','fas fa-desktop','Master',NULL,'5','1','1');
insert into `mst_menu` (`menu_id`, `app_code`, `menu_name`, `menu_url`, `icon`, `menu_group`, `description`, `menu_no`, `group_no`, `is_active`) values('21','MST','Bank','master/mt_bank','fas fa-desktop','Master',NULL,'6','1','1');
insert into `mst_menu` (`menu_id`, `app_code`, `menu_name`, `menu_url`, `icon`, `menu_group`, `description`, `menu_no`, `group_no`, `is_active`) values('22','MST','Konfigurasi','master/mt_config','fas fa-desktop','Master',NULL,'7','1','1');
insert into `mst_menu` (`menu_id`, `app_code`, `menu_name`, `menu_url`, `icon`, `menu_group`, `description`, `menu_no`, `group_no`, `is_active`) values('23','TRN','Download','transaction/download','fas fa-file-alt','Transaction',NULL,'8','2','1');
insert into `mst_menu` (`menu_id`, `app_code`, `menu_name`, `menu_url`, `icon`, `menu_group`, `description`, `menu_no`, `group_no`, `is_active`) values('24','TRN','Upload','transaction/upload','fas fa-file-alt','Transaction',NULL,'9','2','1');
insert into `mst_menu` (`menu_id`, `app_code`, `menu_name`, `menu_url`, `icon`, `menu_group`, `description`, `menu_no`, `group_no`, `is_active`) values('25','TRN','Timesheet','transaction/tr_timesheet','fas fa-file-alt','Transaction',NULL,'10','2','1');
insert into `mst_menu` (`menu_id`, `app_code`, `menu_name`, `menu_url`, `icon`, `menu_group`, `description`, `menu_no`, `group_no`, `is_active`) values('26','RPN','Martabe Invoice','report/mtb_invoice','fas fa-align-justify','Report',NULL,'11','4','1');
