-- MySQL dump 10.16  Distrib 10.1.33-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: shadow
-- ------------------------------------------------------
-- Server version	10.1.33-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member` (
  `m_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `m_name` char(15) NOT NULL COMMENT '用户名称',
  `m_salt` char(12) NOT NULL COMMENT '密码随机数',
  `m_email` varchar(100) NOT NULL COMMENT '用户邮箱',
  `m_password` char(64) NOT NULL COMMENT '密码生成值',
  `m_avatar` varchar(255) DEFAULT NULL COMMENT '用户头像信息',
  `m_login_token` char(40) NOT NULL COMMENT '用户登录有效值',
  `m_login_expire` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录超时时间,当时间超过此值登录无效',
  `m_access_token` varchar(51) DEFAULT NULL COMMENT '唯一验证字符，格式为sha256.time',
  PRIMARY KEY (`m_id`),
  UNIQUE KEY `m_email` (`m_email`),
  UNIQUE KEY `m_access_token` (`m_access_token`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1006 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `member_google`
--

DROP TABLE IF EXISTS `member_google`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member_google` (
  `m_id` int(10) unsigned NOT NULL,
  `mg_uid` char(25) NOT NULL COMMENT '谷歌用户唯一标示符 来自SUB字段',
  `mg_app_uid` varchar(128) NOT NULL COMMENT '相对于客户端的用户ID',
  `mg_avatar` varchar(255) NOT NULL COMMENT '用户头像地址',
  PRIMARY KEY (`m_id`),
  UNIQUE KEY `mg_uid` (`mg_uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `share`
--

DROP TABLE IF EXISTS `share`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `share` (
  `s_id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '分享ID',
  `s_uname` char(10) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '分享ID对应的名称，bin区分大小写',
  `s_key` char(12) DEFAULT NULL COMMENT '如果需要密码验证',
  `s_type` int(10) unsigned NOT NULL COMMENT '分享类型，0:文本,1:文件,2:代码,3:多段文本,4:网址,5:图片,6:Markdown,7:图文',
  `s_mid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID,0为匿名用户',
  `s_status` tinyint(10) unsigned NOT NULL DEFAULT '0' COMMENT '状态，0:正常,1:失效,2:被删除',
  `s_deny` tinyint(10) unsigned NOT NULL DEFAULT '0' COMMENT '是否被禁止，0:正常,1:普通禁用,2:违规禁用',
  `s_view` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '访问次数',
  `s_share_max` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '最大分享次数',
  `s_share_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '已经分享次数',
  `s_share_over` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享被禁止次数',
  `s_time_share` int(10) unsigned NOT NULL COMMENT '开始时间',
  `s_time_delete` int(10) unsigned DEFAULT NULL COMMENT '删除时间',
  `s_time_renewal` int(10) unsigned DEFAULT NULL COMMENT '续期时间',
  `s_time_last` int(10) unsigned DEFAULT NULL COMMENT '最后分享',
  PRIMARY KEY (`s_id`),
  UNIQUE KEY `s_uname` (`s_uname`),
  KEY `s_mid` (`s_mid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=125 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `share_code`
--

DROP TABLE IF EXISTS `share_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `share_code` (
  `s_id` int(20) unsigned NOT NULL COMMENT '主键描述',
  `sc_code` text NOT NULL COMMENT '主要分享内容',
  `sc_lang` varchar(10) DEFAULT NULL COMMENT '代码语言',
  PRIMARY KEY (`s_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `share_file`
--

DROP TABLE IF EXISTS `share_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `share_file` (
  `s_id` int(20) unsigned NOT NULL COMMENT '分享ID',
  `sf_md5` char(32) NOT NULL COMMENT '文件MD5值',
  `sf_sha1` char(40) NOT NULL COMMENT '文件hash值',
  `sf_name` varchar(255) NOT NULL COMMENT '文件上传名称',
  `sf_type` varchar(20) DEFAULT NULL COMMENT '文件MIME类型',
  `sf_size` int(11) unsigned NOT NULL COMMENT '文件大小',
  `sf_save_name` char(82) NOT NULL COMMENT '保存的文件名',
  `sf_save_path` char(10) NOT NULL COMMENT '保存子路径',
  PRIMARY KEY (`s_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `share_markdown`
--

DROP TABLE IF EXISTS `share_markdown`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `share_markdown` (
  `s_id` int(20) unsigned NOT NULL COMMENT '引用分享ID',
  `sm_content` text NOT NULL COMMENT '内容',
  `sm_title` varchar(255) DEFAULT NULL COMMENT '标题',
  PRIMARY KEY (`s_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `share_multi_text`
--

DROP TABLE IF EXISTS `share_multi_text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `share_multi_text` (
  `s_id` int(10) unsigned NOT NULL,
  `smt_text` text NOT NULL COMMENT '文本内容',
  `smt_index` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '当前阅读指针',
  `smt_max` int(10) unsigned NOT NULL COMMENT '文本行数',
  `smt_expire` int(10) unsigned NOT NULL COMMENT '超时时间，单IP有效时间,0为不过期',
  PRIMARY KEY (`s_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `share_multi_text_map`
--

DROP TABLE IF EXISTS `share_multi_text_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `share_multi_text_map` (
  `s_id` int(10) unsigned NOT NULL,
  `smtm_ip` char(15) NOT NULL COMMENT 'IP地址',
  `smtm_time` int(10) unsigned NOT NULL COMMENT '初次访问时间',
  `smtm_count` int(10) unsigned NOT NULL COMMENT '该时间段的访问计数',
  `smt_index` int(10) unsigned NOT NULL COMMENT '在多条记录中的索引',
  KEY `s_id` (`s_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `share_picture`
--

DROP TABLE IF EXISTS `share_picture`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `share_picture` (
  `s_id` int(10) unsigned NOT NULL COMMENT '分享ID',
  `sp_width` int(10) unsigned NOT NULL COMMENT '图片宽度',
  `sp_height` int(10) unsigned NOT NULL COMMENT '图片高度',
  PRIMARY KEY (`s_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `share_picture_text`
--

DROP TABLE IF EXISTS `share_picture_text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `share_picture_text` (
  `s_id` int(10) unsigned NOT NULL,
  `spt_text` varchar(255) NOT NULL COMMENT '文本内容',
  `spt_image_width` int(10) unsigned NOT NULL COMMENT '图片宽度',
  `spt_image_height` int(10) unsigned NOT NULL COMMENT '图片高度',
  `spt_position` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '图片位置，0:顶部居中,1:左浮动,2右浮动,3:底部',
  PRIMARY KEY (`s_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `share_text`
--

DROP TABLE IF EXISTS `share_text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `share_text` (
  `s_id` int(20) unsigned NOT NULL COMMENT '主键描述',
  `st_text` text NOT NULL COMMENT '主要分享内容',
  PRIMARY KEY (`s_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `share_url`
--

DROP TABLE IF EXISTS `share_url`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `share_url` (
  `s_id` int(20) unsigned NOT NULL COMMENT '对应的主键ID',
  `su_url` varchar(255) NOT NULL COMMENT '分享的地址',
  `su_title` varchar(100) DEFAULT NULL COMMENT '分享的标题',
  `su_description` varchar(1024) DEFAULT NULL COMMENT '分享的摘要',
  PRIMARY KEY (`s_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-07-01 17:29:14
