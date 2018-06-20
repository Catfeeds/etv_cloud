/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-20 14:00:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_weather`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_weather`;
CREATE TABLE `zxt_weather` (
  `city_id` int(10) NOT NULL,
  `city` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `low` varchar(10) NOT NULL,
  `high` varchar(10) NOT NULL,
  `code_day` int(10) NOT NULL,
  `code_night` int(10) NOT NULL,
  `text_day` varchar(20) NOT NULL,
  `text_night` varchar(20) NOT NULL,
  UNIQUE KEY `code_id` (`city_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxt_weather
-- ----------------------------
