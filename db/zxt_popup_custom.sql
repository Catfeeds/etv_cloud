/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-05-10 17:04:19
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_popup_custom`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_popup_custom`;
CREATE TABLE `zxt_popup_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_id` int(11) NOT NULL,
  `rid` int(11) NOT NULL COMMENT '弹窗资源ID',
  PRIMARY KEY (`id`),
  KEY `all` (`rid`,`custom_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客户与弹窗资源绑定表';

-- ----------------------------
-- Records of zxt_popup_custom
-- ----------------------------
