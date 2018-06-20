/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-20 15:16:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_jump_resource`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_jump_resource`;
CREATE TABLE `zxt_jump_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `title` varchar(40) NOT NULL COMMENT '标题',
  `filepath` varchar(100) NOT NULL,
  `file_type` varchar(10) NOT NULL COMMENT '文件类型',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `updatetime` int(10) NOT NULL COMMENT '修改时间',
  `size` float(7,3) NOT NULL COMMENT '资源大小',
  `audit_status` varchar(20) NOT NULL DEFAULT 'unaudited' COMMENT '审核状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='跳转资源资源';

-- ----------------------------
-- Records of zxt_jump_resource
-- ----------------------------
