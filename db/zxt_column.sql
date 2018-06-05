/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-04-25 09:26:07
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_column`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_column`;
CREATE TABLE `zxt_column` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`admin_id`  int(10) NOT NULL COMMENT '账号ID' ,
`pid`  int(11) NOT NULL COMMENT '父ID' ,
`fpid`  int(11) NOT NULL COMMENT '一级Pid' ,
`level`  int(11) NOT NULL COMMENT '层级' ,
`title`  varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '栏目标题' ,
`filepath`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '图标' ,
`size`  float(5,3) NOT NULL DEFAULT 0.000 ,
`language_type`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'chinese' COMMENT '语言类型' ,
`createtime`  int(10) NOT NULL COMMENT '创建时间' ,
`updatetime`  int(10) NOT NULL COMMENT '更新时间' ,
`audit_status`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '审核状态' ,
PRIMARY KEY (`id`),
INDEX `get_titlelist_byadminid` (`admin_id`, `id`, `pid`, `title`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='栏目管理'
AUTO_INCREMENT=1

;

-- ----------------------------
-- Auto increment value for `zxt_column`
-- ----------------------------
ALTER TABLE `zxt_column` AUTO_INCREMENT=1;
