/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-11 18:09:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_skin`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_skin`;
CREATE TABLE `zxt_skin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(40) NOT NULL COMMENT '皮肤名称',
  `apk_filepath` varchar(100) NOT NULL COMMENT 'APK皮肤',
  `sha1` varchar(32) NOT NULL COMMENT '文件sha1值',
  `web_sign` varchar(40) NOT NULL COMMENT 'web版标志',
  `image_filepath` varchar(100) NOT NULL,
  `status` varchar(10) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='皮肤管理';

-- ----------------------------
-- Records of zxt_skin
-- ----------------------------
