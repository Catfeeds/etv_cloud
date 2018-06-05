/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-02 11:28:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_device_wifiset`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_device_wifiset`;
CREATE TABLE `zxt_device_wifiset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mac` varchar(40) NOT NULL,
  `wifi_ssid` varchar(50) NOT NULL COMMENT '账号',
  `wifi_passwd` varchar(50) DEFAULT NULL COMMENT '密码',
  `wifi_psk_type` varchar(10) NOT NULL DEFAULT 'psk2' COMMENT '安全类型',
  `wifi_hot_spot` varchar(10) NOT NULL DEFAULT 'close' COMMENT '热点',
  `status` varchar(10) NOT NULL DEFAULT 'hidden' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mac` (`mac`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxt_device_wifiset
-- ----------------------------
