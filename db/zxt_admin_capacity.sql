/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-29 11:26:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_admin_capacity`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_admin_capacity`;
CREATE TABLE `zxt_admin_capacity` (
  `admin_id` int(11) NOT NULL COMMENT '账号ID',
  `application_capacity` float(11,3) NOT NULL COMMENT '申请容量',
  `used_capacity` float(11,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `admin_id` (`admin_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxt_admin_capacity
-- ----------------------------
INSERT INTO `zxt_admin_capacity` VALUES ('1', '10000.000', '0.000');
