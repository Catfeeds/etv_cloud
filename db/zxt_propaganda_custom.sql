/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-05-10 16:59:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_propaganda_custom`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_propaganda_custom`;
CREATE TABLE `zxt_propaganda_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_id` int(11) NOT NULL COMMENT '客户列表ID',
  `rid` int(11) NOT NULL COMMENT '宣传栏目ID',
  `weigh` int(6) NOT NULL DEFAULT '0' COMMENT '权重',
  `save_set` tinyint(1) NOT NULL DEFAULT '0' COMMENT '资源保存地址',
  `status` varchar(10) NOT NULL DEFAULT 'hidden' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `rid_customid` (`rid`,`custom_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='宣传客户绑定表';

-- ----------------------------
-- Records of zxt_propaganda_custom
-- ----------------------------
