/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-12 15:25:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_appstore`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_appstore`;
CREATE TABLE `zxt_appstore` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL DEFAULT 'common' COMMENT '类型',
  `name` varchar(40) NOT NULL COMMENT '名称',
  `version` varchar(20) NOT NULL COMMENT '版本',
  `sha1` varchar(40) NOT NULL,
  `package` varchar(100) NOT NULL COMMENT '包名',
  `remarks` varchar(256) DEFAULT NULL COMMENT '备注',
  `filepath` varchar(100) NOT NULL COMMENT '文件路径',
  `icon` varchar(100) DEFAULT NULL COMMENT '图标',
  `status` varchar(10) NOT NULL DEFAULT 'hidden' COMMENT '状态',
  `audit_status` varchar(20) NOT NULL DEFAULT 'unaudited' COMMENT '审核状态',
  `audit_time` datetime DEFAULT NULL COMMENT '审核日期',
  `mac_ids` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxt_appstore
-- ----------------------------
