/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-04-24 15:16:08
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_propaganda_resource`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_propaganda_resource`;
CREATE TABLE `zxt_propaganda_resource` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`admin_id`  int(11) NOT NULL COMMENT '账号ID' ,
`title`  varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`filepath`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '资源路径' ,
`createtime`  int(10) NOT NULL COMMENT '创建时间' ,
`updatetime`  int(10) NOT NULL COMMENT '更新时间' ,
`size`  float(8,3) NOT NULL COMMENT '资源大小' ,
`audit_status`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '审核状态' ,
`file_type`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文件类型' ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='宣传轮播资源管理'
AUTO_INCREMENT=2

;

-- ----------------------------
-- Auto increment value for `zxt_propaganda_resource`
-- ----------------------------
ALTER TABLE `zxt_propaganda_resource` AUTO_INCREMENT=2;
