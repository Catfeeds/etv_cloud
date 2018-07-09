/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-07-09 18:20:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_audit_process_log`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_audit_process_log`;
CREATE TABLE `zxt_audit_process_log` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL COMMENT '账号ID',
  `run_time` varchar(10) NOT NULL COMMENT '操作时间',
  `audit_type` varchar(20) NOT NULL COMMENT '操作类型',
  `audit_module` varchar(50) NOT NULL COMMENT '操作模块',
  `audit_list_id` int(10) NOT NULL COMMENT '操作列表ID',
  `audit_value` varchar(20) NOT NULL COMMENT '操作值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxt_audit_process_log
-- ----------------------------
