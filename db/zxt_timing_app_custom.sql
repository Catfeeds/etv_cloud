/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-28 15:38:43
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_timing_app_custom`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_timing_app_custom`;
CREATE TABLE `zxt_timing_app_custom` (
  `time_app_id` int(11) NOT NULL COMMENT '定时APP列表ID',
  `custom_id` text NOT NULL COMMENT '客户列表ID',
  PRIMARY KEY (`time_app_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxt_timing_app_custom
-- ----------------------------
