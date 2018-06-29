/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-06-28 18:25:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_auth_rule`;
CREATE TABLE `zxt_auth_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('menu','file') NOT NULL DEFAULT 'file' COMMENT 'menu为菜单,file为权限节点',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则名称',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '规则名称',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标',
  `condition` varchar(255) NOT NULL DEFAULT '' COMMENT '条件',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为菜单',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE,
  KEY `pid` (`pid`),
  KEY `weigh` (`weigh`)
) ENGINE=InnoDB AUTO_INCREMENT=335 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='节点表';

-- ----------------------------
-- Records of zxt_auth_rule
-- ----------------------------
INSERT INTO `zxt_auth_rule` VALUES ('1', 'file', '0', 'dashboard', 'Dashboard', 'fa fa-dashboard', '', 'Dashboard tips', '1', '1497429920', '1497429920', '2', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('2', 'file', '0', 'general', 'General', 'fa fa-cogs', '', '', '1', '1497429920', '1497430169', '141', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('4', 'file', '0', 'addon', 'Addon', 'fa fa-rocket', '', 'Addon tips', '1', '1502035509', '1523342653', '14', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('5', 'file', '0', 'auth', 'Auth', 'fa fa-group', '', '', '1', '1497429920', '1497430092', '129', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('6', 'file', '2', 'general/config', 'Config', 'fa fa-cog', '', 'Config tips', '1', '1497429920', '1497430683', '107', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('7', 'file', '2', 'general/attachment', 'Attachment', 'fa fa-file-image-o', '', 'Attachment tips', '1', '1497429920', '1497430699', '100', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('8', 'file', '2', 'general/profile', 'Profile', 'fa fa-user', '', '', '1', '1497429920', '1497429920', '94', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('9', 'file', '5', 'auth/admin', 'Admin', 'fa fa-user', '', 'Admin tips', '1', '1497429920', '1497430320', '128', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('10', 'file', '5', 'auth/adminlog', 'Admin log', 'fa fa-list-alt', '', 'Admin log tips', '1', '1497429920', '1497430307', '123', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('11', 'file', '5', 'auth/group', 'Group', 'fa fa-group', '', 'Group tips', '1', '1497429920', '1497429920', '119', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('12', 'file', '5', 'auth/rule', 'Rule', 'fa fa-bars', '', 'Rule tips', '1', '1497429920', '1497430581', '114', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('13', 'file', '1', 'dashboard/index', 'View', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '134', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('14', 'file', '1', 'dashboard/add', 'Add', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '133', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('15', 'file', '1', 'dashboard/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '131', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('16', 'file', '1', 'dashboard/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '132', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('17', 'file', '1', 'dashboard/multi', 'Multi', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '130', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('18', 'file', '6', 'general/config/index', 'View', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '99', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('19', 'file', '6', 'general/config/add', 'Add', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '98', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('20', 'file', '6', 'general/config/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '97', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('21', 'file', '6', 'general/config/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '96', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('22', 'file', '6', 'general/config/multi', 'Multi', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '95', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('23', 'file', '7', 'general/attachment/index', 'View', 'fa fa-circle-o', '', 'Attachment tips', '0', '1497429920', '1497429920', '106', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('24', 'file', '7', 'general/attachment/select', 'Select attachment', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '105', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('25', 'file', '7', 'general/attachment/add', 'Add', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '104', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('26', 'file', '7', 'general/attachment/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '103', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('27', 'file', '7', 'general/attachment/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '102', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('28', 'file', '7', 'general/attachment/multi', 'Multi', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '101', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('29', 'file', '8', 'general/profile/index', 'View', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '93', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('30', 'file', '8', 'general/profile/update', 'Update profile', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '92', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('31', 'file', '8', 'general/profile/add', 'Add', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '91', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('32', 'file', '8', 'general/profile/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '90', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('33', 'file', '8', 'general/profile/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '89', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('34', 'file', '8', 'general/profile/multi', 'Multi', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '88', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('40', 'file', '9', 'auth/admin/index', 'View', 'fa fa-circle-o', '', 'Admin tips', '0', '1497429920', '1497429920', '127', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('41', 'file', '9', 'auth/admin/add', 'Add', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '126', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('42', 'file', '9', 'auth/admin/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '125', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('43', 'file', '9', 'auth/admin/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '124', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('44', 'file', '10', 'auth/adminlog/index', 'View', 'fa fa-circle-o', '', 'Admin log tips', '0', '1497429920', '1497429920', '122', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('45', 'file', '10', 'auth/adminlog/detail', 'Detail', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '121', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('46', 'file', '10', 'auth/adminlog/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '120', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('47', 'file', '11', 'auth/group/index', 'View', 'fa fa-circle-o', '', 'Group tips', '0', '1497429920', '1497429920', '118', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('48', 'file', '11', 'auth/group/add', 'Add', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '117', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('49', 'file', '11', 'auth/group/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '116', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('50', 'file', '11', 'auth/group/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '115', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('51', 'file', '12', 'auth/rule/index', 'View', 'fa fa-circle-o', '', 'Rule tips', '0', '1497429920', '1497429920', '113', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('52', 'file', '12', 'auth/rule/add', 'Add', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '111', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('53', 'file', '12', 'auth/rule/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '110', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('54', 'file', '12', 'auth/rule/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1497429920', '1497429920', '109', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('55', 'file', '4', 'addon/index', 'View', 'fa fa-circle-o', '', 'Addon tips', '0', '1502035509', '1502035509', '3', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('56', 'file', '4', 'addon/add', 'Add', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '4', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('57', 'file', '4', 'addon/edit', 'Edit', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '5', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('58', 'file', '4', 'addon/del', 'Delete', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '6', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('59', 'file', '4', 'addon/local', 'Local install', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '7', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('60', 'file', '4', 'addon/state', 'Update state', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '8', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('61', 'file', '4', 'addon/install', 'Install', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '9', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('62', 'file', '4', 'addon/uninstall', 'Uninstall', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '10', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('63', 'file', '4', 'addon/config', 'Setting', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '11', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('64', 'file', '4', 'addon/refresh', 'Refresh', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '12', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('65', 'file', '4', 'addon/multi', 'Multi', 'fa fa-circle-o', '', '', '0', '1502035509', '1502035509', '13', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('85', 'file', '2', 'general/crontab', '定时任务', 'fa fa-tasks', '', '类似于Linux的Crontab定时任务,可以按照设定的时间进行任务的执行,目前仅支持三种任务:请求URL、执行SQL、执行Shell', '1', '1523171626', '1523171626', '33', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('86', 'file', '85', 'general/crontab/index', '查看', 'fa fa-circle-o', '', '', '0', '1523171626', '1523171626', '34', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('87', 'file', '85', 'general/crontab/add', '添加', 'fa fa-circle-o', '', '', '0', '1523171626', '1523171626', '35', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('88', 'file', '85', 'general/crontab/edit', '编辑 ', 'fa fa-circle-o', '', '', '0', '1523171626', '1523171626', '36', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('89', 'file', '85', 'general/crontab/del', '删除', 'fa fa-circle-o', '', '', '0', '1523171626', '1523171626', '37', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('90', 'file', '85', 'general/crontab/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1523171626', '1523171626', '38', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('91', 'file', '2', 'general/database', '数据库管理', 'fa fa-database', '', '可在线进行一些简单的数据库表优化或修复,查看表结构和数据。也可以进行SQL语句的操作', '1', '1523171632', '1523171632', '39', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('92', 'file', '91', 'general/database/index', '查看', 'fa fa-circle-o', '', '', '0', '1523171632', '1523171632', '40', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('93', 'file', '91', 'general/database/query', '查询', 'fa fa-circle-o', '', '', '0', '1523171632', '1523171632', '41', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('140', 'file', '0', 'customcontro', '客户管理', 'fa fa-list', '', '', '1', '1523841459', '1526263602', '1', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('141', 'file', '140', 'customcontro/custom', 'Custom', 'fa fa-circle-o\r', '', '', '1', '1523841459', '1523841459', '221', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('142', 'file', '141', 'customcontro/custom/index', '查看', 'fa fa-circle-o', '', '', '0', '1523841459', '1523841459', '220', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('143', 'file', '141', 'customcontro/custom/add', '添加', 'fa fa-circle-o', '', '', '0', '1523841459', '1523841459', '219', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('144', 'file', '141', 'customcontro/custom/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1523841459', '1523841459', '218', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('145', 'file', '141', 'customcontro/custom/del', '删除', 'fa fa-circle-o', '', '', '0', '1523841459', '1523841459', '217', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('146', 'file', '141', 'customcontro/custom/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1523841459', '1523841459', '216', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('154', 'file', '0', 'resources', 'resources', 'fa fa-list', '', '', '1', '1524190645', '1526698031', '215', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('155', 'file', '154', 'resources/welcomeresource', '欢迎图片资源管理', 'fa fa-circle-o\r', '', '', '1', '1524190645', '1524190645', '214', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('156', 'file', '155', 'resources/welcomeresource/index', '查看', 'fa fa-circle-o', '', '', '0', '1524190645', '1524190645', '213', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('157', 'file', '155', 'resources/welcomeresource/add', '添加', 'fa fa-circle-o', '', '', '0', '1524190645', '1524190645', '212', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('158', 'file', '155', 'resources/welcomeresource/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1524190645', '1524190645', '211', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('159', 'file', '155', 'resources/welcomeresource/del', '删除', 'fa fa-circle-o', '', '', '0', '1524190645', '1524190645', '210', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('160', 'file', '155', 'resources/welcomeresource/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1524190645', '1524190645', '209', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('161', 'file', '7', 'ajax/myupload', '自定义上传', 'fa fa-circle-o', '', '', '0', '1524193616', '1524193616', '112', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('162', 'file', '154', 'resources/jumpresource', '跳转资源资源', 'fa fa-circle-o\r', '', '', '1', '1524467661', '1524467661', '208', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('163', 'file', '162', 'resources/jumpresource/index', '查看', 'fa fa-circle-o', '', '', '0', '1524467661', '1524467661', '207', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('164', 'file', '162', 'resources/jumpresource/add', '添加', 'fa fa-circle-o', '', '', '0', '1524467661', '1524467661', '206', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('165', 'file', '162', 'resources/jumpresource/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1524467661', '1524467661', '205', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('166', 'file', '162', 'resources/jumpresource/del', '删除', 'fa fa-circle-o', '', '', '0', '1524467661', '1524467661', '204', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('167', 'file', '162', 'resources/jumpresource/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1524467661', '1524467661', '203', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('168', 'file', '154', 'resources/propagandaresource', '宣传轮播资源管理', 'fa fa-circle-o\r', '', '', '1', '1524477660', '1524477660', '202', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('169', 'file', '168', 'resources/propagandaresource/index', '查看', 'fa fa-circle-o', '', '', '0', '1524477660', '1524477660', '201', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('170', 'file', '168', 'resources/propagandaresource/add', '添加', 'fa fa-circle-o', '', '', '0', '1524477660', '1524477660', '200', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('171', 'file', '168', 'resources/propagandaresource/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1524477660', '1524477660', '199', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('172', 'file', '168', 'resources/propagandaresource/del', '删除', 'fa fa-circle-o', '', '', '0', '1524477660', '1524477660', '198', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('173', 'file', '168', 'resources/propagandaresource/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1524477660', '1524477660', '197', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('174', 'file', '154', 'resources/popupresource', '弹窗广告资源管理', 'fa fa-circle-o\r', '', '', '1', '1524541229', '1524541229', '196', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('175', 'file', '174', 'resources/popupresource/index', '查看', 'fa fa-circle-o', '', '', '0', '1524541229', '1524541229', '195', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('176', 'file', '174', 'resources/popupresource/add', '添加', 'fa fa-circle-o', '', '', '0', '1524541229', '1524541229', '194', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('177', 'file', '174', 'resources/popupresource/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1524541229', '1524541229', '193', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('178', 'file', '174', 'resources/popupresource/del', '删除', 'fa fa-circle-o', '', '', '0', '1524541229', '1524541229', '192', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('179', 'file', '174', 'resources/popupresource/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1524541229', '1524541229', '191', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('180', 'file', '154', 'resources/column', '栏目管理', 'fa fa-circle-o\r', '', 'Resources tips', '1', '1524555112', '1524555112', '190', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('181', 'file', '180', 'resources/column/index', '查看', 'fa fa-circle-o', '', '', '0', '1524555112', '1524555112', '189', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('182', 'file', '180', 'resources/column/add', '添加', 'fa fa-circle-o', '', '', '0', '1524555112', '1524555112', '188', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('183', 'file', '180', 'resources/column/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1524555112', '1524555112', '187', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('184', 'file', '180', 'resources/column/del', '删除', 'fa fa-circle-o', '', '', '0', '1524555112', '1524555112', '186', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('185', 'file', '180', 'resources/column/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1524555112', '1524555112', '185', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('188', 'file', '180', 'resources/colresource/index', '栏目资源管理', 'fa fa-file-picture-o', '', '', '0', '1524906196', '1524906465', '184', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('189', 'file', '180', 'resources/colresource/add', '新增栏目资源', 'fa fa-circle-o', '', '', '0', '1524907723', '1524907723', '183', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('190', 'file', '180', 'resources/colresource/edit', '修改栏目资源', 'fa fa-circle-o', '', '', '0', '1524907748', '1524907748', '182', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('191', 'file', '180', 'resources/colresource/del', '删除栏目资源', 'fa fa-circle-o', '', '', '0', '1524907824', '1524907824', '181', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('192', 'file', '155', 'resources/bindresource/welcome_allot', '欢迎图片分配至客户', 'fa fa-chain', '', '', '0', '1525660435', '1525750557', '180', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('193', 'file', '162', 'resources/bindresource/jump_allot', '跳转资源分配至客户', 'fa fa-link', '', '', '0', '1525855880', '1525855880', '179', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('194', 'file', '168', 'resources/bindresource/propaganda_allot', '宣传资源分配至客户', 'fa fa-chain', '', '', '0', '1525915450', '1525915450', '178', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('195', 'file', '174', 'resources/bindresource/popup_allot', '弹窗广告资源分配至客户', 'fa fa-chain', '', '', '0', '1525918254', '1525918254', '177', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('196', 'file', '180', 'resources/bindresource/column_allot', '栏目资源分配至客户', 'fa fa-chain', '', '', '0', '1525936076', '1525936076', '176', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('197', 'file', '0', 'contentset', '内容设置', 'fa fa-align-justify', '', '', '1', '1526263730', '1526263730', '175', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('198', 'file', '197', 'contentset/welcomeset', '欢迎图设置', 'fa fa-circle-o', '', '', '1', '1526264287', '1526610932', '174', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('199', 'file', '198', 'contentset/welcomeset/index', '查看', 'fa fa-circle-o', '', '', '0', '1526264287', '1526264287', '173', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('200', 'file', '198', 'contentset/welcomeset/add', '添加', 'fa fa-circle-o', '', '', '0', '1526264287', '1526264287', '172', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('201', 'file', '198', 'contentset/welcomeset/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1526264287', '1526264287', '171', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('202', 'file', '198', 'contentset/welcomeset/del', '删除', 'fa fa-circle-o', '', '', '0', '1526264287', '1526264287', '170', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('203', 'file', '198', 'contentset/welcomeset/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1526264287', '1526264287', '169', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('204', 'file', '197', 'contentset/languageset', '语言管理设置', 'fa fa-circle-o', '', '', '1', '1526345179', '1526610946', '168', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('205', 'file', '204', 'contentset/languageset/index', '查看', 'fa fa-circle-o', '', '', '0', '1526345179', '1526345179', '167', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('206', 'file', '204', 'contentset/languageset/add', '添加', 'fa fa-circle-o', '', '', '0', '1526345179', '1526345179', '166', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('207', 'file', '204', 'contentset/languageset/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1526345179', '1526345179', '165', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('208', 'file', '204', 'contentset/languageset/del', '删除', 'fa fa-circle-o', '', '', '0', '1526345179', '1526345179', '164', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('209', 'file', '204', 'contentset/languageset/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1526345179', '1526345179', '163', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('210', 'file', '197', 'contentset/jumpset', '跳转设置', 'fa fa-circle-o\r', '', '', '1', '1526372729', '1526372729', '162', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('211', 'file', '210', 'contentset/jumpset/index', '查看', 'fa fa-circle-o', '', '', '0', '1526372729', '1526372729', '161', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('212', 'file', '210', 'contentset/jumpset/add', '添加', 'fa fa-circle-o', '', '', '0', '1526372729', '1526372729', '160', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('213', 'file', '210', 'contentset/jumpset/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1526372729', '1526372729', '159', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('214', 'file', '210', 'contentset/jumpset/del', '删除', 'fa fa-circle-o', '', '', '0', '1526372729', '1526372729', '158', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('215', 'file', '210', 'contentset/jumpset/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1526372729', '1526372729', '157', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('216', 'file', '210', 'contentset/jumpset/resources', '资源管理', 'fa fa-link', '', '', '0', '1526440862', '1526461053', '156', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('217', 'file', '210', 'contentset/jumpset/multi_resource', '资源使用', 'fa fa-circle-o', '', '', '0', '1526463658', '1526463658', '155', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('218', 'file', '197', 'contentset/propagandaset', '宣传资源设置', 'fa fa-circle-o', '', '', '1', '1526464945', '1526610958', '154', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('219', 'file', '218', 'contentset/propagandaset/index', '查看', 'fa fa-circle-o', '', '', '0', '1526464945', '1526464945', '153', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('220', 'file', '218', 'contentset/propagandaset/add', '添加', 'fa fa-circle-o', '', '', '0', '1526464945', '1526464945', '152', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('221', 'file', '218', 'contentset/propagandaset/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1526464945', '1526464945', '151', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('222', 'file', '218', 'contentset/propagandaset/del', '删除', 'fa fa-circle-o', '', '', '0', '1526464945', '1526464945', '150', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('223', 'file', '218', 'contentset/propagandaset/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1526464945', '1526464945', '149', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('224', 'file', '218', 'contentset/propagandaset/dragsort', '排序', 'fa fa-circle-o', '', '', '0', '1526547545', '1526547545', '148', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('225', 'file', '197', 'contentset/columnset', '栏目资源设置', 'fa fa-circle-o', '', '', '1', '1526610876', '1526610971', '147', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('226', 'file', '225', 'contentset/columnset/index', '查看', 'fa fa-circle-o', '', '', '0', '1526610876', '1526610876', '146', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('227', 'file', '225', 'contentset/columnset/add', '添加', 'fa fa-circle-o', '', '', '0', '1526610876', '1526610876', '145', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('228', 'file', '225', 'contentset/columnset/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1526610876', '1526610876', '144', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('229', 'file', '225', 'contentset/columnset/del', '删除', 'fa fa-circle-o', '', '', '0', '1526610876', '1526610876', '143', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('230', 'file', '225', 'contentset/columnset/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1526610876', '1526610876', '142', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('231', 'file', '225', 'contentset/columnset/status', '启用禁用状态', 'fa fa-circle-o', '', '', '0', '1526950795', '1526950795', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('232', 'file', '225', 'contentset/columnset/resources', '资源列表', 'fa fa-circle-o', '', '', '0', '1526956948', '1526956948', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('233', 'file', '225', 'contentset/jumpset/resource_dragsort', '资源排序', 'fa fa-circle-o', '', '', '0', '1526982369', '1527123625', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('234', 'file', '225', 'contentset/jumpset/resource_status', '栏目资源状态', 'fa fa-circle-o', '', '', '0', '1527123681', '1527125238', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('235', 'file', '197', 'contentset/popupset', '弹窗设置', 'fa fa-circle-o\r', '', '', '1', '1527125146', '1527125146', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('236', 'file', '235', 'contentset/popupset/index', '查看', 'fa fa-circle-o', '', '', '0', '1527125146', '1527125146', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('237', 'file', '235', 'contentset/popupset/add', '添加', 'fa fa-circle-o', '', '', '0', '1527125146', '1527125146', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('238', 'file', '235', 'contentset/popupset/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1527125146', '1527125146', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('239', 'file', '235', 'contentset/popupset/del', '删除', 'fa fa-circle-o', '', '', '0', '1527125146', '1527125146', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('240', 'file', '235', 'contentset/popupset/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1527125146', '1527125146', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('241', 'file', '235', 'contentset/popupset/select', '选择资源', 'fa fa-circle-o', '', '', '0', '1527151689', '1527151689', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('242', 'file', '154', 'resources/simpleadresource', '简易广告管理', 'fa fa-circle-o\r', '', '', '1', '1527562375', '1527562375', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('243', 'file', '242', 'resources/simpleadresource/index', '查看', 'fa fa-circle-o', '', '', '0', '1527562375', '1527562375', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('244', 'file', '242', 'resources/simpleadresource/add', '添加', 'fa fa-circle-o', '', '', '0', '1527562375', '1527562375', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('245', 'file', '242', 'resources/simpleadresource/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1527562375', '1527562375', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('246', 'file', '242', 'resources/simpleadresource/del', '删除', 'fa fa-circle-o', '', '', '0', '1527562375', '1527562375', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('247', 'file', '242', 'resources/simpleadresource/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1527562375', '1527562375', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('248', 'file', '242', 'resources/bindresource/simplead_allot', '简易广告资源分配至客户', 'fa fa-circle-o', '', '', '0', '1527575753', '1527575826', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('249', 'file', '197', 'contentset/simpleadset', '简易广告设置', 'fa fa-circle-o', '', '', '1', '1527581202', '1527583234', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('250', 'file', '249', 'contentset/simpleadset/index', '查看', 'fa fa-circle-o', '', '', '0', '1527581202', '1527581202', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('251', 'file', '249', 'contentset/simpleadset/add', '添加', 'fa fa-circle-o', '', '', '0', '1527581202', '1527581202', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('252', 'file', '249', 'contentset/simpleadset/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1527581202', '1527581202', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('253', 'file', '249', 'contentset/simpleadset/del', '删除', 'fa fa-circle-o', '', '', '0', '1527581202', '1527581202', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('254', 'file', '249', 'contentset/simpleadset/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1527581202', '1527581202', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('255', 'file', '0', 'devices', '设备管理', 'fa fa-list', '', '', '1', '1527642525', '1527642632', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('256', 'file', '255', 'devices/basics', '设备信息管理', 'fa fa-circle-o', '', '', '1', '1527642525', '1527642614', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('257', 'file', '256', 'devices/basics/index', '查看', 'fa fa-circle-o', '', '', '0', '1527642525', '1527642525', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('258', 'file', '256', 'devices/basics/add', '添加', 'fa fa-circle-o', '', '', '0', '1527642525', '1527642525', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('259', 'file', '256', 'devices/basics/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1527642525', '1527642525', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('260', 'file', '256', 'devices/basics/del', '删除', 'fa fa-circle-o', '', '', '0', '1527642525', '1527642525', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('261', 'file', '256', 'devices/basics/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1527642525', '1527642525', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('262', 'file', '256', 'devices/basics/detail', '详情', 'fa fa-circle-o', '', '', '0', '1527674145', '1527674145', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('263', 'file', '256', 'devices/basics/directive', '指令集', 'fa fa-circle-o', '', '', '0', '1527674173', '1527674173', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('264', 'file', '256', 'devices/basics/order', '设置指令', 'fa fa-circle-o', '', '', '0', '1527729515', '1527729719', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('265', 'file', '255', 'devices/wifiset', 'WIFI设置管理', 'fa fa-circle-o', '', '', '1', '1527815435', '1527815867', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('266', 'file', '265', 'devices/wifiset/index', '查看', 'fa fa-circle-o', '', '', '0', '1527815435', '1527815435', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('267', 'file', '265', 'devices/wifiset/add', '添加', 'fa fa-circle-o', '', '', '0', '1527815435', '1527815435', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('268', 'file', '265', 'devices/wifiset/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1527815435', '1527815435', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('269', 'file', '265', 'devices/wifiset/del', '删除', 'fa fa-circle-o', '', '', '0', '1527815435', '1527815435', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('270', 'file', '265', 'devices/wifiset/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1527815435', '1527815435', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('271', 'file', '265', 'devices/wifiset/batch_set', '批量设置', 'fa fa-circle-o', '', '', '0', '1528076864', '1528170820', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('272', 'file', '255', 'devices/sleepset', '休眠设置管理', 'fa fa-circle-o', '', '', '1', '1528188763', '1528188986', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('273', 'file', '272', 'devices/sleepset/index', '查看', 'fa fa-circle-o', '', '', '0', '1528188763', '1528188763', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('274', 'file', '272', 'devices/sleepset/add', '添加', 'fa fa-circle-o', '', '', '0', '1528188763', '1528188763', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('275', 'file', '272', 'devices/sleepset/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1528188763', '1528188763', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('276', 'file', '272', 'devices/sleepset/del', '删除', 'fa fa-circle-o', '', '', '0', '1528188763', '1528188763', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('277', 'file', '272', 'devices/sleepset/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1528188763', '1528188763', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('278', 'file', '272', 'devices/sleepset/multi_add', '批量新增', 'fa fa-circle-o', '', '', '0', '1528271570', '1528271675', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('279', 'file', '272', 'devices/sleepset/multi_edit', '批量设置', 'fa fa-circle-o', '', '', '0', '1528271594', '1528271661', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('280', 'file', '197', 'contentset/messageset', '消息通知设置', 'fa fa-circle-o', '', '', '1', '1528423930', '1528424002', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('281', 'file', '280', 'contentset/messageset/index', '查看', 'fa fa-circle-o', '', '', '0', '1528423930', '1528423930', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('282', 'file', '280', 'contentset/messageset/add', '添加', 'fa fa-circle-o', '', '', '0', '1528423930', '1528423930', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('283', 'file', '280', 'contentset/messageset/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1528423930', '1528423930', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('284', 'file', '280', 'contentset/messageset/del', '删除', 'fa fa-circle-o', '', '', '0', '1528423930', '1528423930', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('285', 'file', '280', 'contentset/messageset/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1528423930', '1528423930', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('286', 'file', '154', 'resources/timeappresource', '定时APP资源管理', 'fa fa-circle-o\r', '', '', '1', '1528449337', '1528449337', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('287', 'file', '286', 'resources/timeappresource/index', '查看', 'fa fa-circle-o', '', '', '0', '1528449337', '1528449337', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('288', 'file', '286', 'resources/timeappresource/add', '添加', 'fa fa-circle-o', '', '', '0', '1528449337', '1528449337', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('289', 'file', '286', 'resources/timeappresource/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1528449337', '1528449337', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('290', 'file', '286', 'resources/timeappresource/del', '删除', 'fa fa-circle-o', '', '', '0', '1528449337', '1528449337', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('291', 'file', '286', 'resources/timeappresource/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1528449337', '1528449337', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('299', 'file', '197', 'contentset/timeappset', 'APP定时启动管理', 'fa fa-circle-o\r', '', '', '1', '1528452127', '1528452127', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('300', 'file', '299', 'contentset/timeappset/index', '查看', 'fa fa-circle-o', '', '', '0', '1528452127', '1528452127', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('301', 'file', '299', 'contentset/timeappset/add', '添加', 'fa fa-circle-o', '', '', '0', '1528452127', '1528452127', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('302', 'file', '299', 'contentset/timeappset/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1528452127', '1528452127', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('303', 'file', '299', 'contentset/timeappset/del', '删除', 'fa fa-circle-o', '', '', '0', '1528452127', '1528452127', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('304', 'file', '299', 'contentset/timeappset/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1528452127', '1528452127', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('305', 'file', '2', 'general/skinset', '皮肤管理', 'fa fa-circle-o\r', '', '', '1', '1528709065', '1528709065', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('306', 'file', '305', 'general/skinset/index', '查看', 'fa fa-circle-o', '', '', '0', '1528709065', '1528709065', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('307', 'file', '305', 'general/skinset/add', '添加', 'fa fa-circle-o', '', '', '0', '1528709065', '1528709065', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('308', 'file', '305', 'general/skinset/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1528709065', '1528709065', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('309', 'file', '305', 'general/skinset/del', '删除', 'fa fa-circle-o', '', '', '0', '1528709065', '1528709065', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('310', 'file', '305', 'general/skinset/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1528709065', '1528709065', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('311', 'file', '255', 'devices/appstore', 'AppStore管理', 'fa fa-circle-o', '', '', '1', '1528787173', '1528794368', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('312', 'file', '311', 'devices/appstore/index', '查看', 'fa fa-circle-o', '', '', '0', '1528787173', '1528787173', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('313', 'file', '311', 'devices/appstore/add', '添加', 'fa fa-circle-o', '', '', '0', '1528787173', '1528787173', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('314', 'file', '311', 'devices/appstore/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1528787173', '1528787173', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('315', 'file', '311', 'devices/appstore/del', '删除', 'fa fa-circle-o', '', '', '0', '1528787173', '1528787173', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('316', 'file', '311', 'devices/appstore/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1528787173', '1528787173', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('317', 'file', '311', 'devices/appstore/allot', '分配至设备', 'fa fa-circle-o', '', '', '0', '1528794329', '1528794345', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('318', 'file', '2', 'general/systemset', '系统管理', 'fa fa-circle-o\r', '', '', '1', '1528879036', '1528879036', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('319', 'file', '318', 'general/systemset/index', '查看', 'fa fa-circle-o', '', '', '0', '1528879036', '1528879036', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('320', 'file', '318', 'general/systemset/add', '添加', 'fa fa-circle-o', '', '', '0', '1528879036', '1528879036', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('321', 'file', '318', 'general/systemset/edit', '编辑', 'fa fa-circle-o', '', '', '0', '1528879036', '1528879036', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('322', 'file', '318', 'general/systemset/del', '删除', 'fa fa-circle-o', '', '', '0', '1528879036', '1528879036', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('323', 'file', '318', 'general/systemset/multi', '批量更新', 'fa fa-circle-o', '', '', '0', '1528879036', '1528879036', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('324', 'file', '318', 'general/systemset/allot', '分配至设备', 'fa fa-circle-o', '', '', '0', '1528893395', '1528893395', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('325', 'file', '225', 'resources/column/resource_dragsort', '资源排序', 'fa fa-circle-o', '', '', '0', '1528944985', '1528944985', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('326', 'file', '140', 'customcontro/bindcustom', '客户绑定', 'fa fa-circle-o\r', '', '', '1', '1528957481', '1528957481', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('327', 'file', '326', 'customcontro/bindcustom/index', '查看', 'fa fa-circle-o', '', '', '0', '1528957481', '1528957481', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('330', 'file', '326', 'customcontro/bindcustom/delete', '删除', 'fa fa-circle-o', '', '', '0', '1528957481', '1529047451', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('332', 'file', '326', 'customcontro/bindcustom/bind', '绑定', 'fa fa-circle-o', '', '', '0', '1529043655', '1529043655', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('333', 'file', '256', 'devices/basics/app_setting', 'APP设置', 'fa fa-circle-o', '', '', '0', '1530149109', '1530149109', '0', 'normal');
INSERT INTO `zxt_auth_rule` VALUES ('334', 'file', '286', 'resources/bindresource/time_app_allot', '分配', 'fa fa-circle-o', '', '', '0', '1530177964', '1530177964', '0', 'normal');
