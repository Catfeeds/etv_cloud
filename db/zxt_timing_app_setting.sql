/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-09 10:27:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_timing_app_setting`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_timing_app_setting`;
CREATE TABLE `zxt_timing_app_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_id` varchar(32) NOT NULL COMMENT '客户编号',
  `title` varchar(40) NOT NULL COMMENT '标题',
  `data_params` varchar(256) NOT NULL DEFAULT '' COMMENT '备注(详细解释,参数,数据)',
  `repeat_set` varchar(20) NOT NULL COMMENT '重复设置',
  `weekday` varchar(16) NOT NULL COMMENT '周期',
  `no_repeat_date` date NOT NULL COMMENT '日期',
  `start_time` varchar(10) NOT NULL COMMENT '开启时间',
  `end_time` varchar(10) NOT NULL COMMENT '结束时间',
  `out_to` varchar(20) NOT NULL COMMENT '跳转处',
  `status` varchar(10) NOT NULL DEFAULT 'normal',
  `mac_ids` text NOT NULL COMMENT 'Mac列表',
  PRIMARY KEY (`id`),
  KEY `custom_id` (`custom_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='APP定时启动管理';

-- ----------------------------
-- Records of zxt_timing_app_setting
-- ----------------------------
