/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-07-03 18:12:19
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_upgrade_system_log`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_upgrade_system_log`;
CREATE TABLE `zxt_upgrade_system_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_id` int(11) NOT NULL,
  `mac_id` int(11) NOT NULL,
  `pass_utc` varchar(32) NOT NULL,
  `current_utc` varchar(32) NOT NULL,
  `version` varchar(50) NOT NULL,
  `room` varchar(64) NOT NULL DEFAULT '-',
  `message` varchar(200) NOT NULL DEFAULT '-',
  `runtime` int(10) NOT NULL,
  `login_ip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='升级管理日志';

-- ----------------------------
-- Records of zxt_upgrade_system_log
-- ----------------------------
