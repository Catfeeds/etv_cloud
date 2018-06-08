/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-08 16:59:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_simplead_resource`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_simplead_resource`;
CREATE TABLE `zxt_simplead_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `title` varchar(40) NOT NULL COMMENT '标题',
  `filepath` varchar(100) NOT NULL COMMENT '资源',
  `rid` int(11) NOT NULL,
  `file_type` varchar(10) NOT NULL COMMENT '类型',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `updatetime` int(10) NOT NULL,
  `size` float(5,3) NOT NULL COMMENT '资源大小',
  `audit_status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '审核状态',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='简易广告管理';

-- ----------------------------
-- Records of zxt_simplead_resource
-- ----------------------------
