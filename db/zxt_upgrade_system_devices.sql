/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-07-03 18:12:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_upgrade_system_devices`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_upgrade_system_devices`;
CREATE TABLE `zxt_upgrade_system_devices` (
  `sys_id` int(11) NOT NULL,
  `custom_id` int(11) NOT NULL,
  `mac_ids` text NOT NULL,
  UNIQUE KEY `sys_id` (`sys_id`,`custom_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxt_upgrade_system_devices
-- ----------------------------
