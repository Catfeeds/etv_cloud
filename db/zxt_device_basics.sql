/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-07-03 18:10:51
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
  `last_visit_time` int(10) DEFAULT NULL COMMENT '访问时间',
  `last_visit_ip` varchar(20) NOT NULL DEFAULT '000.000.000.000' COMMENT '访问IP',
  `usage` varchar(20) NOT NULL DEFAULT 'official' COMMENT '用途',
  `status` varchar(10) NOT NULL DEFAULT 'normal' COMMENT '状态',
  `lately_order` varchar(20) NOT NULL DEFAULT 'no-set' COMMENT '最近指令[no-set, reboot, clean rom, clean all, clean sd, wifi set, sleep set]',
  `lately_order_result` varchar(20) NOT NULL DEFAULT 'pending' COMMENT '最近指令结果',
  `reboot_set` varchar(20) NOT NULL DEFAULT 'no-set',
  `reboot_set_time` int(10) DEFAULT NULL,
  `reboot_result` varchar(20) NOT NULL DEFAULT 'pending',
  `reboot_result_time` int(10) DEFAULT NULL,
  `clean_set` varchar(20) NOT NULL DEFAULT 'no-set',
  `clean_set_time` int(10) DEFAULT NULL,
  `clean_result` varchar(20) NOT NULL DEFAULT 'pending',
  `clean_result_time` int(10) DEFAULT NULL,
  `wifi_set` varchar(20) NOT NULL DEFAULT 'no-set',
  `wifi_set_time` int(10) DEFAULT NULL,
  `wifi_result` varchar(20) NOT NULL DEFAULT 'pending',
  `wifi_result_time` int(10) DEFAULT NULL,
  `sleep_set` varchar(20) NOT NULL DEFAULT 'no-set',
  `sleep_result` varchar(20) NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mac` (`mac`) USING BTREE,
  KEY `custom_id` (`custom_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='设备基础信息表';

-- ----------------------------
-- Records of zxt_device_basics
-- ----------------------------
