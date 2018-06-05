/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-04-20 15:37:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_attachment`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_attachment`;
CREATE TABLE `zxt_attachment` (
`id`  int(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID' ,
`title`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`admin_id`  int(11) NOT NULL DEFAULT 0 ,
`url`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '物理路径' ,
`imagewidth`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '宽度' ,
`imageheight`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '高度' ,
`imagetype`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '图片类型' ,
`imageframes`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '图片帧数' ,
`filesize`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小' ,
`mimetype`  varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'mime类型' ,
`extparam`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '透传数据' ,
`createtime`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建日期' ,
`updatetime`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间' ,
`uploadtime`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上传时间' ,
`storage`  enum('local','upyun','qiniu') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'local' COMMENT '存储位置' ,
`sha1`  varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件 sha1编码' ,
PRIMARY KEY (`id`),
UNIQUE INDEX `sha1` (`sha1`, `id`) USING BTREE ,
INDEX `adminid_and_title` (`admin_id`, `title`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='附件表'
AUTO_INCREMENT=24

;

-- ----------------------------
-- Auto increment value for `zxt_attachment`
-- ----------------------------
ALTER TABLE `zxt_attachment` AUTO_INCREMENT=24;
