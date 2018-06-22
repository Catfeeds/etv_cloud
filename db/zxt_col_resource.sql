/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-22 09:11:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_col_resource`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_col_resource`;
CREATE TABLE `zxt_col_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL COMMENT '账号ID',
  `column_fpid` int(11) NOT NULL COMMENT '栏目一级ID',
  `column_pid` int(11) NOT NULL COMMENT '栏目ID',
  `title` varchar(40) NOT NULL COMMENT '标题',
  `describe` varchar(128) DEFAULT NULL COMMENT '描述',
  `resource_type` varchar(20) NOT NULL COMMENT '资源类型',
  `resource` varchar(100) NOT NULL COMMENT '资源',
  `size` float(8,3) NOT NULL DEFAULT '0.000' COMMENT '大小',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `updatetime` int(10) NOT NULL,
  `audit_status` varchar(20) NOT NULL DEFAULT 'unaudited' COMMENT '审核状态',
  PRIMARY KEY (`id`),
  KEY `column_fpid` (`column_fpid`) USING BTREE,
  KEY `pid_title` (`column_pid`,`title`,`audit_status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='栏目资源';

-- ----------------------------
-- Records of zxt_col_resource
-- ----------------------------
