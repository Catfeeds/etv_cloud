/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-05-15 10:53:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_admin_custom_bind`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_admin_custom_bind`;
CREATE TABLE `zxt_admin_custom_bind` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL COMMENT '账号ID',
  `custom_id` text NOT NULL COMMENT '客户列表ID',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='账号与客户绑定表';

-- ----------------------------
-- Records of zxt_admin_custom_bind
-- ----------------------------
