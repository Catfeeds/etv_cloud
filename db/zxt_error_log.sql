/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-07-05 10:18:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_error_log`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_error_log`;
CREATE TABLE `zxt_error_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mac` varchar(32) NOT NULL,
  `error_type` varchar(30) NOT NULL COMMENT '错误类型',
  `error_time` varchar(10) NOT NULL COMMENT '错误时间',
  `error_name` varchar(100) NOT NULL,
  `error_message` text NOT NULL COMMENT '错误消息',
  `error_stack` text,
  `agent` varchar(256) DEFAULT NULL COMMENT '代理',
  `mode` varchar(30) DEFAULT 'debug' COMMENT '开发环境',
  `referer` varchar(100) DEFAULT '' COMMENT '错误来源页面',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='设备错误日志表';

-- ----------------------------
-- Records of zxt_error_log
-- ----------------------------
