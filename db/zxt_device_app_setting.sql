/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-28 10:52:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_device_app_setting`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_device_app_setting`;
CREATE TABLE `zxt_device_app_setting` (
  `id` int(11) NOT NULL COMMENT '设备ID',
  `weigh` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxt_device_app_setting
-- ----------------------------
