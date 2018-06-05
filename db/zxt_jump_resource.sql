/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-04-24 15:14:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_jump_resource`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_jump_resource`;
CREATE TABLE `zxt_jump_resource` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`admin_id`  int(11) NOT NULL ,
`title`  varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '标题' ,
`filepath`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`file_type`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文件类型' ,
`createtime`  int(10) NOT NULL COMMENT '创建时间' ,
`updatetime`  int(10) NOT NULL COMMENT '修改时间' ,
`size`  float(7,3) NOT NULL COMMENT '资源大小' ,
`bind_cid`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '绑定客户编号' ,
`audit_status`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '审核状态' ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='跳转资源资源'
AUTO_INCREMENT=2

;

-- ----------------------------
-- Auto increment value for `zxt_jump_resource`
-- ----------------------------
ALTER TABLE `zxt_jump_resource` AUTO_INCREMENT=2;
