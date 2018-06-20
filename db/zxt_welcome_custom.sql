/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-20 09:05:27
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_welcome_custom`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_welcome_custom`;
CREATE TABLE `zxt_welcome_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_id` int(11) NOT NULL COMMENT '客户列表ID',
  `rid` int(11) NOT NULL COMMENT '资源ID',
  `title` varchar(40) NOT NULL DEFAULT '-',
  `stay_set` tinyint(1) NOT NULL DEFAULT '1' COMMENT '停留设置',
  `stay_time` int(10) NOT NULL DEFAULT '0' COMMENT '停留时间',
  `weigh` int(3) NOT NULL DEFAULT '1' COMMENT '排序',
  `updatetime` int(10) NOT NULL COMMENT '更新时间',
  `status` varchar(10) NOT NULL DEFAULT '' COMMENT '权重',
  `audit_status` varchar(20) DEFAULT 'no release' COMMENT '发布',
  PRIMARY KEY (`id`),
  KEY `rid_customid` (`rid`,`custom_id`) USING BTREE,
  KEY `custom_id` (`custom_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客户与资源绑定表';

-- ----------------------------
-- Records of zxt_welcome_custom
-- ----------------------------
