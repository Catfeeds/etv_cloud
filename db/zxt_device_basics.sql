/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-05-30 17:57:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_device_basics`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_device_basics`;
CREATE TABLE `zxt_device_basics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mac` varchar(40) NOT NULL,
  `custom_id` int(11) NOT NULL COMMENT '客户编号',
  `room` varchar(40) NOT NULL DEFAULT '' COMMENT '房间号',
  `room_remark` varchar(60) DEFAULT '' COMMENT '房间备注',
  `model` varchar(60) NOT NULL DEFAULT '' COMMENT '型号',
  `firmware_version` varchar(60) NOT NULL DEFAULT '' COMMENT '软件版本',
  `lately_order` varchar(20) NOT NULL DEFAULT 'no-set' COMMENT '最近指令',
  `lately_order_result` varchar(20) NOT NULL DEFAULT 'pending' COMMENT '最近指令结果',
  `last_visit_time` int(10) DEFAULT NULL COMMENT '访问时间',
  `last_visit_ip` varchar(20) NOT NULL DEFAULT '000.000.000.000' COMMENT '访问IP',
  `usage` varchar(20) NOT NULL DEFAULT 'official' COMMENT '用途',
  `status` varchar(10) NOT NULL DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `mac` (`mac`),
  KEY `custom_id` (`custom_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='设备基础信息表';

-- ----------------------------
-- Records of zxt_device_basics
-- ----------------------------
