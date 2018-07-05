/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-07-05 08:53:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_device_visit_log_4`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_device_visit_log_4`;
CREATE TABLE `zxt_device_visit_log_4` (
  `id` bigint(15) NOT NULL AUTO_INCREMENT,
  `mac` varchar(32) NOT NULL,
  `mac_id` int(10) NOT NULL,
  `message` varchar(512) NOT NULL,
  `post_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxt_device_visit_log_4
-- ----------------------------
