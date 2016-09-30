/*
Navicat MySQL Data Transfer

Source Server         : localhost-3307
Source Server Version : 50541
Source Host           : localhost:3306
Source Database       : bbs

Target Server Type    : MYSQL
Target Server Version : 50541
File Encoding         : 65001

Date: 2016-09-30 15:40:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `category`
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(55) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `status` tinyint(3) unsigned DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of category
-- ----------------------------
-- ----------------------------
-- Table structure for `forum`
-- ----------------------------
DROP TABLE IF EXISTS `forum`;
CREATE TABLE `forum` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `content` text,
  `image` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `status` tinyint(3) unsigned DEFAULT '0',
  `isshow` tinyint(3) unsigned DEFAULT NULL,
  `istop` tinyint(3) unsigned DEFAULT '0',
  `sort` int(11) DEFAULT NULL,
  `create_date` varchar(22) DEFAULT NULL,
  `username` varchar(22) DEFAULT NULL,
  `view_count` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of forum
-- ----------------------------
-- ----------------------------
-- Table structure for `forum_comment`
-- ----------------------------
DROP TABLE IF EXISTS `forum_comment`;
CREATE TABLE `forum_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` text,
  `image` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `forum_id` int(11) DEFAULT NULL,
  `p_id` int(11) unsigned DEFAULT '0',
  `create_date` varchar(22) DEFAULT NULL,
  `username` varchar(22) DEFAULT NULL,
  `likes` varchar(22) DEFAULT NULL,
  `likes_num` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of forum_comment
-- ----------------------------
-- ----------------------------
-- Table structure for `forum_read`
-- ----------------------------
DROP TABLE IF EXISTS `forum_read`;
CREATE TABLE `forum_read` (
  `id` int(11) NOT NULL DEFAULT '0',
  `forum_id` int(11) DEFAULT NULL,
  `username` varchar(22) DEFAULT NULL,
  `date` varchar(22) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of forum_read
-- ----------------------------

-- ----------------------------
-- Table structure for `log`
-- ----------------------------
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` varchar(22) DEFAULT NULL,
  `operator` varchar(22) DEFAULT NULL,
  `info` varchar(200) DEFAULT NULL,
  `type` varchar(22) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of log
-- ----------------------------
-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(22) DEFAULT NULL,
  `name` varchar(22) DEFAULT NULL,
  `position` varchar(22) DEFAULT NULL,
  `mobile` varchar(22) DEFAULT NULL,
  `gender` tinyint(3) unsigned DEFAULT NULL,
  `email` varchar(55) DEFAULT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=198 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
