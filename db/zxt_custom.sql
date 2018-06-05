/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-05-10 17:42:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_custom`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_custom`;
CREATE TABLE `zxt_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `custom_id` varchar(32) NOT NULL COMMENT '客户编号',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父组别',
  `status` varchar(8) NOT NULL DEFAULT 'hidden' COMMENT '状态',
  `province_id` int(5) NOT NULL COMMENT '省份ID',
  `city_id` int(5) NOT NULL COMMENT '城市ID',
  `area_id` int(5) NOT NULL COMMENT '地区ID',
  `custom_name` varchar(64) NOT NULL COMMENT '客户名称',
  `full_name` varchar(64) NOT NULL COMMENT '全称',
  `custom_type` varchar(20) NOT NULL DEFAULT 'hospital' COMMENT '客户类型',
  `handler` varchar(32) NOT NULL COMMENT '负责人',
  `phone` char(11) NOT NULL COMMENT '联系电话',
  `detail_address` varchar(80) NOT NULL COMMENT '详细地址',
  `lng` varchar(20) NOT NULL COMMENT '经度',
  `lat` varchar(20) NOT NULL COMMENT '纬度',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `updatetime` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `pid_and_name` (`id`,`pid`,`custom_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxt_custom
-- ----------------------------
