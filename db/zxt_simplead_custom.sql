/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-21 10:08:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_simplead_custom`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_simplead_custom`;
CREATE TABLE `zxt_simplead_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_id` int(11) NOT NULL COMMENT '客户编号',
  `rid` int(11) NOT NULL,
  `title` varchar(80) DEFAULT NULL COMMENT '标题',
  `updatetime` int(10) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'hidden' COMMENT '状态',
  `url_to` varchar(120) DEFAULT NULL COMMENT '跳转地址',
  `audit_status` varchar(20) NOT NULL DEFAULT 'no release',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE,
  KEY `custom_id` (`custom_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='简易广告管理';

-- ----------------------------
-- Records of zxt_simplead_custom
-- ----------------------------
