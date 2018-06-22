/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-22 13:54:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_popup_resource`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_popup_resource`;
CREATE TABLE `zxt_popup_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL COMMENT '账号ID',
  `title` varchar(40) NOT NULL COMMENT '标题',
  `filepath` varchar(100) NOT NULL COMMENT '资源路径',
  `file_type` varchar(10) NOT NULL COMMENT '文件类型',
  `size` float(8,3) NOT NULL,
  `bind_cid` text COMMENT '绑定客户编号',
  `audit_status` varchar(20) NOT NULL DEFAULT 'unaudited' COMMENT '审核状态',
  `createtime` int(10) NOT NULL,
  `updatetime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='弹窗广告资源管理';

-- ----------------------------
-- Records of zxt_popup_resource
-- ----------------------------
