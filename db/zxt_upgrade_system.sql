/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-13 19:19:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_upgrade_system`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_upgrade_system`;
CREATE TABLE `zxt_upgrade_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utc` varchar(32) NOT NULL COMMENT 'UTC版本',
  `version` varchar(32) NOT NULL COMMENT '版本号',
  `size` varchar(30) NOT NULL COMMENT '文件大小',
  `name` varchar(40) NOT NULL COMMENT '文件名称',
  `filepath` varchar(100) NOT NULL COMMENT '存储路径',
  `discription` varchar(250) NOT NULL COMMENT '描述',
  `sha1` varchar(40) NOT NULL COMMENT 'sha1',
  `createtime` int(10) NOT NULL,
  `updatetime` int(10) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'hidden',
  `audit_status` varchar(20) NOT NULL DEFAULT 'unaudited',
  `audit_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统管理';

-- ----------------------------
-- Records of zxt_upgrade_system
-- ----------------------------
