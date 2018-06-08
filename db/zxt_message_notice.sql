/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-08 16:24:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_message_notice`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_message_notice`;
CREATE TABLE `zxt_message_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_id` int(11) NOT NULL COMMENT '客户',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `content` varchar(256) NOT NULL,
  `push_type` varchar(30) NOT NULL COMMENT '推送类型',
  `push_start_time` datetime DEFAULT NULL COMMENT '推送开启时间',
  `push_end_time` datetime NOT NULL COMMENT '推送结束时间',
  `status` varchar(10) NOT NULL DEFAULT 'normal' COMMENT '状态',
  `mac_ids` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_id` (`custom_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxt_message_notice
-- ----------------------------
