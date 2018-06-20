/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-20 15:15:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_jump_custom`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_jump_custom`;
CREATE TABLE `zxt_jump_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_id` int(11) NOT NULL COMMENT '客户编号',
  `rid` int(11) NOT NULL COMMENT '资源ID',
  `status` varchar(10) NOT NULL DEFAULT 'hidden' COMMENT '状态',
  `audit_status` varchar(20) NOT NULL DEFAULT 'no release' COMMENT '发布状态',
  PRIMARY KEY (`id`),
  KEY `all` (`rid`,`custom_id`,`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxt_jump_custom
-- ----------------------------
