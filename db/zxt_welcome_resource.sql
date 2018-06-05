/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-04-24 15:15:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_welcome_resource`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_welcome_resource`;
CREATE TABLE `zxt_welcome_resource` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`admin_id`  int(11) NOT NULL COMMENT '账号ID' ,
`title`  varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '标题' ,
`describe`  varchar(160) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述' ,
`filepath`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '资源地址' ,
`file_type`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'image' COMMENT '资源类型' ,
`createtime`  int(10) NOT NULL COMMENT '创建时间' ,
`updatetime`  int(10) NOT NULL COMMENT '修改时间' ,
`size`  float(5,3) NOT NULL DEFAULT 0.000 COMMENT '资源大小' ,
`audit_status`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '审核状态' ,
PRIMARY KEY (`id`),
INDEX `admin_id` (`admin_id`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='欢迎图片资源表'
AUTO_INCREMENT=3

;

-- ----------------------------
-- Auto increment value for `zxt_welcome_resource`
-- ----------------------------
ALTER TABLE `zxt_welcome_resource` AUTO_INCREMENT=3;
