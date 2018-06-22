/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-22 15:02:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_popup_setting`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_popup_setting`;
CREATE TABLE `zxt_popup_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_id` varchar(32) NOT NULL COMMENT '客户',
  `ad_type` varchar(10) NOT NULL COMMENT '广告类型',
  `save_set` tinyint(1) NOT NULL COMMENT '存储设置',
  `repeat_set` varchar(16) NOT NULL COMMENT '重复设置',
  `break_set` tinyint(1) NOT NULL COMMENT '退出设置',
  `weekday` varchar(16) NOT NULL COMMENT '周期',
  `no_repeat_date` date NOT NULL COMMENT '指定日期',
  `start_time` varchar(10) NOT NULL COMMENT '开始时间',
  `stay_time` int(10) NOT NULL COMMENT '停留时间',
  `position` tinyint(2) NOT NULL DEFAULT '0' COMMENT '位置设定',
  `words_tips` varchar(200) NOT NULL DEFAULT '',
  `resource_id` varchar(100) NOT NULL COMMENT '资源ID',
  `status` varchar(10) NOT NULL COMMENT '状态',
  `audit_status` varchar(20) NOT NULL DEFAULT 'no release' COMMENT '发布状态',
  PRIMARY KEY (`id`),
  KEY `custom_id` (`custom_id`,`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='弹窗设置';

-- ----------------------------
-- Records of zxt_popup_setting
-- ----------------------------
