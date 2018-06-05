/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-05-10 17:05:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_column_custom`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_column_custom`;
CREATE TABLE `zxt_column_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_id` int(11) NOT NULL COMMENT '客户列表ID',
  `rid` int(11) NOT NULL COMMENT '栏目ID',
  `save_set` tinyint(1) NOT NULL DEFAULT '1' COMMENT '资源保存路径',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `weigh` int(5) NOT NULL COMMENT '权重',
  `column_weigh` text COMMENT '栏目权重',
  `column_status` text COMMENT '栏目状态',
  `resource_weigh` text COMMENT '资源权重',
  `resource_status` text COMMENT '资源状态',
  PRIMARY KEY (`id`),
  KEY `rid_customid` (`rid`,`custom_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客户栏目资源绑定表';

-- ----------------------------
-- Records of zxt_column_custom
-- ----------------------------
