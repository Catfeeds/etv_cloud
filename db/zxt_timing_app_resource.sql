/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-08 17:50:07
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_timing_app_resource`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_timing_app_resource`;
CREATE TABLE `zxt_timing_app_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL COMMENT '账号',
  `title` varchar(50) NOT NULL COMMENT '名称',
  `classname` varchar(100) NOT NULL COMMENT '类名',
  `packagename` varchar(100) NOT NULL COMMENT '包名',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='定时APP资源管理';

-- ----------------------------
-- Records of zxt_timing_app_resource
-- ----------------------------
INSERT INTO `zxt_timing_app_resource` VALUES ('1', '2', '微信', 'weixin.classname.app', 'weixin.package.app');
