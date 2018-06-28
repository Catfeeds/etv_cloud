/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-27 16:02:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_config`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_config`;
CREATE TABLE `zxt_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '变量名',
  `group` varchar(30) NOT NULL DEFAULT '' COMMENT '分组',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '变量标题',
  `tip` varchar(100) NOT NULL DEFAULT '' COMMENT '变量描述',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '类型:string,text,int,bool,array,datetime,date,file',
  `value` text NOT NULL COMMENT '变量值',
  `content` text NOT NULL COMMENT '变量字典数据',
  `rule` varchar(100) NOT NULL DEFAULT '' COMMENT '验证规则',
  `extend` varchar(255) NOT NULL DEFAULT '' COMMENT '扩展属性',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='系统配置';

-- ----------------------------
-- Records of zxt_config
-- ----------------------------
INSERT INTO `zxt_config` VALUES ('1', 'name', 'basic', 'Site name', '请填写站点名称', 'string', '中信通云平台', '', 'required', '');
INSERT INTO `zxt_config` VALUES ('2', 'beian', 'basic', 'Beian', '粤ICP备15054802号-4', 'string', '', '', '', '');
INSERT INTO `zxt_config` VALUES ('3', 'cdnurl', 'basic', 'Cdn url', '如果静态资源使用第三方云储存请配置该值', 'string', '', '', '', '');
INSERT INTO `zxt_config` VALUES ('4', 'version', 'basic', 'Version', '如果静态资源有变动请重新配置该值', 'string', '0.0.1', '', 'required', '');
INSERT INTO `zxt_config` VALUES ('5', 'timezone', 'basic', 'Timezone', '', 'string', 'Asia/Shanghai', '', 'required', '');
INSERT INTO `zxt_config` VALUES ('6', 'forbiddenip', 'basic', 'Forbidden ip', '一行一条记录', 'text', '', '', '', '');
INSERT INTO `zxt_config` VALUES ('7', 'languages', 'basic', 'Languages', '', 'array', '{\"backend\":\"zh-cn\",\"frontend\":\"zh-cn\"}', '', 'required', '');
INSERT INTO `zxt_config` VALUES ('8', 'fixedpage', 'basic', 'Fixed page', '请尽量输入左侧菜单栏存在的链接', 'string', 'dashboard', '', 'required', '');
INSERT INTO `zxt_config` VALUES ('9', 'categorytype', 'dictionary', 'Category type', '', 'array', '{\"default\":\"Default\",\"page\":\"Page\",\"article\":\"Article\",\"test\":\"Test\"}', '', '', '');
INSERT INTO `zxt_config` VALUES ('10', 'configgroup', 'dictionary', 'Config group', '', 'array', '{\"basic\":\"Basic\",\"email\":\"Email\",\"dictionary\":\"Dictionary\",\"user\":\"User\",\"example\":\"Example\"}', '', '', '');
INSERT INTO `zxt_config` VALUES ('11', 'mail_type', 'email', 'Mail type', '选择邮件发送方式', 'select', '1', '[\"Please select\",\"SMTP\",\"Mail\"]', '', '');
INSERT INTO `zxt_config` VALUES ('12', 'mail_smtp_host', 'email', 'Mail smtp host', '错误的配置发送邮件会导致服务器超时', 'string', 'smtp.qq.com', '', '', '');
INSERT INTO `zxt_config` VALUES ('13', 'mail_smtp_port', 'email', 'Mail smtp port', '(不加密默认25,SSL默认465,TLS默认587)', 'string', '465', '', '', '');
INSERT INTO `zxt_config` VALUES ('14', 'mail_smtp_user', 'email', 'Mail smtp user', '（填写完整用户名）', 'string', '10000', '', '', '');
INSERT INTO `zxt_config` VALUES ('15', 'mail_smtp_pass', 'email', 'Mail smtp password', '（填写您的密码）', 'string', 'password', '', '', '');
INSERT INTO `zxt_config` VALUES ('16', 'mail_verify_type', 'email', 'Mail vertify type', '（SMTP验证方式[推荐SSL]）', 'select', '2', '[\"None\",\"TLS\",\"SSL\"]', '', '');
INSERT INTO `zxt_config` VALUES ('17', 'mail_from', 'email', 'Mail from', '', 'string', '10000@qq.com', '', '', '');
INSERT INTO `zxt_config` VALUES ('18', 'resource_column', 'account_management', 'Column admin manage', '请填写账号ID作为键名,填写需分配的客户ID作为键值,多个客户ID用逗号分隔,尽可能配置少的客户ID,不支持*号查询全部客户', 'array', '{\"1\":\"1,2,3,4,5\",\"2\":\"2,3,4\"}', ' ', '', '');
INSERT INTO `zxt_config` VALUES ('19', 'resource_attachment', 'account_management', 'Attachment admin manage', '请填写账号ID作为键名,填写需查看客户的ID作为键值,多个客户ID用逗号分隔,全部客户用*号替代', 'array', '{\"2\":\"*\"}', ' ', '', '');
INSERT INTO `zxt_config` VALUES ('20', 'resource_allot', 'account_management', 'Allot admin manage', '请填写账号ID作为键名,填写需分配的客户ID作为键值,多个客户ID用逗号分隔,全部客户用*号替代', 'array', '{\"2\":\"1,2,3,4,5\"}\r\n', ' ', '', '');
INSERT INTO `zxt_config` VALUES ('21', 'content_set', 'account_management', 'Content setting', '请填写账号ID作为键名,填写需查看内容设置的客户ID作为键值,多个客户ID用逗号分隔,全部客户用*号替代; PS:不包括内容设置中的栏目模块', 'array', '{\"1\":\"1,2.3,4,5\"}', ' ', '', '');
INSERT INTO `zxt_config` VALUES ('22', 'content_set_column', 'account_management', 'Content column setting', '请填写账号ID作为键名,填写需查看栏目设置中客户ID作为键值,多个客户ID用逗号分隔,暂不支持*号查看所有客户;', 'array', '{\"2\":\"1,2,3,4\"}', ' ', '', '');
INSERT INTO `zxt_config` VALUES ('23', 'device_manage', 'account_management', 'Devices manage', '请填写账号ID作为键名,填写需查看客户ID作为键值,多个客户ID用逗号分隔,查看所有客户用*号代替;', 'array', '{\"2\":\"*\"}', ' ', '', '');
INSERT INTO `zxt_config` VALUES ('24', 'dashboard_group', 'account_management', 'Dashboard group', '请填写可在控制台查看所有客户及其设备数据的组别ID,多个组别ID用逗号隔开', 'string', '1,2', ' ', 'required', '');
