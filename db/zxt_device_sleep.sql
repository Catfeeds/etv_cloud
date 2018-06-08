/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-07 16:19:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_device_sleep`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_device_sleep`;
CREATE TABLE `zxt_device_sleep` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mac` varchar(40) NOT NULL,
  `sleep_time_start` varchar(10) NOT NULL,
  `sleep_time_end` varchar(10) NOT NULL,
  `sleep_marked_word` varchar(100) NOT NULL,
  `sleep_countdown_time` int(11) NOT NULL,
  `sleep_image` varchar(20) NOT NULL DEFAULT 'black',
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mac` (`mac`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxt_device_sleep
-- ----------------------------
