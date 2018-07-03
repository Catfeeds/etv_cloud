/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : etv2018

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-07-03 18:11:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zxt_device_detail`
-- ----------------------------
DROP TABLE IF EXISTS `zxt_device_detail`;
CREATE TABLE `zxt_device_detail` (
  `mac` varchar(32) NOT NULL,
  `aaa_account` varchar(64) DEFAULT NULL COMMENT '业务账号',
  `aaa_passwd` varchar(64) DEFAULT NULL COMMENT '业务密码',
  `brand` varchar(64) DEFAULT NULL COMMENT '品牌',
  `board` varchar(64) DEFAULT NULL,
  `network_mode` varchar(32) DEFAULT NULL COMMENT '网络模式',
  `itv_mode` varchar(16) DEFAULT NULL COMMENT 'itv模式',
  `wan_mode` varchar(16) DEFAULT NULL,
  `itv_dhcp_user` varchar(32) DEFAULT NULL COMMENT 'dhcp账号',
  `itv_dhcp_pwd` varchar(32) DEFAULT NULL COMMENT 'dhcp密码',
  `itv_pppoe_user` varchar(32) DEFAULT NULL COMMENT 'pppoe账号',
  `itv_pppoe_pwd` varchar(32) DEFAULT NULL,
  `itv_static_ip` varchar(16) DEFAULT NULL,
  `itv_netmask` varchar(16) DEFAULT NULL,
  `itv_gateway` varchar(16) DEFAULT NULL,
  `itv_dns1` varchar(16) DEFAULT NULL,
  `itv_dns2` varchar(16) DEFAULT NULL,
  `wan_pppoe_user` varchar(32) DEFAULT NULL,
  `wan_pppoe_pwd` varchar(32) DEFAULT NULL,
  `wan_static_ip` varchar(32) DEFAULT NULL,
  `wan_netmask` varchar(32) DEFAULT NULL,
  `wan_gateway` varchar(32) DEFAULT NULL,
  `wan_dns1` varchar(16) DEFAULT NULL,
  `wan_dns2` varchar(16) DEFAULT NULL,
  `itv_version` varchar(64) DEFAULT NULL COMMENT 'IPTV版本',
  `route_firmware_version` varchar(64) DEFAULT NULL COMMENT '路由固件',
  `wan_pppoe_ip` varchar(16) DEFAULT NULL,
  `wan_dhcp_ip` varchar(16) DEFAULT NULL,
  `vlan_number` varchar(32) DEFAULT NULL COMMENT 'VLAN号',
  `vlan_status` tinyint(1) DEFAULT NULL,
  `itv_pppoe_ip` varchar(16) DEFAULT NULL,
  `itv_dhcp_ip` varchar(16) DEFAULT NULL,
  `itv_dhcp_plus_ip` varchar(16) DEFAULT NULL,
  `boot_time` varchar(11) DEFAULT NULL COMMENT '开机时间',
  PRIMARY KEY (`mac`),
  UNIQUE KEY `mac` (`mac`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='设备信息详细表';

-- ----------------------------
-- Records of zxt_device_detail
-- ----------------------------
