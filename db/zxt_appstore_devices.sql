/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-28 10:05:54
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_appstore_devices`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_appstore_devices`;
CREATE TABLE `zxt_appstore_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `custom_id` int(11) NOT NULL,
  `mac_ids` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `appid_customid` (`app_id`,`custom_id`) USING BTREE,
  KEY `custom_id` (`custom_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxt_appstore_devices
-- ----------------------------
