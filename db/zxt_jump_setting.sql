/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-05-15 18:05:00
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_jump_setting`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_jump_setting`;
CREATE TABLE `zxt_jump_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_id` int(11) NOT NULL,
  `play_set` tinyint(1) NOT NULL COMMENT '播放设置',
  `save_set` tinyint(1) NOT NULL COMMENT '资源保存地址',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `updatetime` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `custom_id` (`custom_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='跳转设置';

-- ----------------------------
-- Records of zxt_jump_setting
-- ----------------------------
