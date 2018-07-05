/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-07-05 10:17:54
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_device_para_log`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_device_para_log`;
CREATE TABLE `zxt_device_para_log` (
  `id` bigint(15) NOT NULL AUTO_INCREMENT,
  `mac` varchar(32) NOT NULL,
  `runtime` varchar(10) DEFAULT NULL,
  `before_info` text,
  `after_info` text,
  PRIMARY KEY (`id`),
  KEY `mac` (`mac`,`runtime`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxt_device_para_log
-- ----------------------------
