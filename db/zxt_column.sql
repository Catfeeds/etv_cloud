/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-21 11:25:34
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_column`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_column`;
CREATE TABLE `zxt_column` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL COMMENT '账号ID',
  `pid` int(11) NOT NULL COMMENT '父ID',
  `fpid` int(11) NOT NULL COMMENT '一级Pid',
  `level` tinyint(2) NOT NULL COMMENT '层级',
  `column_type` varchar(20) DEFAULT 'resource' COMMENT '栏目类型',
  `title` varchar(40) NOT NULL COMMENT '栏目标题',
  `filepath` varchar(100) DEFAULT NULL COMMENT '图标',
  `language_type` varchar(10) NOT NULL DEFAULT 'chinese' COMMENT '语言类型',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `updatetime` int(10) NOT NULL COMMENT '更新时间',
  `audit_status` varchar(20) NOT NULL DEFAULT 'unaudited' COMMENT '审核状态',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`) USING BTREE,
  KEY `fpid` (`fpid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='栏目管理';

-- ----------------------------
-- Records of zxt_column
-- ----------------------------
