-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 13, 2014 at 06:36 AM
-- Server version: 5.6.20
-- PHP Version: 5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `Health_4_Life`
--
CREATE DATABASE IF NOT EXISTS `Health_4_Life` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `Health_4_Life`;

-- --------------------------------------------------------

--
-- Table structure for table `ACTION_TOKENS_MASTER`
--

DROP TABLE IF EXISTS `ACTION_TOKENS_MASTER`;
CREATE TABLE IF NOT EXISTS `ACTION_TOKENS_MASTER` (
`ACTION_TOKEN_ID` int(10) unsigned NOT NULL,
  `ACTION_TOKEN_DESCR` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for action tokens' AUTO_INCREMENT=1 ;

--
-- Triggers `ACTION_TOKENS_MASTER`
--
DROP TRIGGER IF EXISTS `ACTION_TOKENS_BU`;
DELIMITER //
CREATE TRIGGER `ACTION_TOKENS_BU` BEFORE UPDATE ON `action_tokens_master`
 FOR EACH ROW BEGIN
	IF EXISTS (SELECT 1 FROM ACTION_TOKENS_MASTER WHERE ACTION_TOKEN_DESCR = NEW.ACTION_TOKEN_DESCR AND NEW.ACTION_TOKEN_DESCR <> OLD.ACTION_TOKEN_DESCR) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical action token exists, please revise.';
    END IF;
    IF OLD.ACTION_TOKEN_DESCR <> NEW.ACTION_TOKEN_DESCR THEN
        INSERT INTO ACTION_TOKENS_MOD_DET 
        SET 
        ACTION_TOKEN_ID = OLD.ACTION_TOKEN_ID,
        COLUMN_NAME = 'ACTION_TOKEN_DESCR',
        COLUMN_VALUE = OLD.ACTION_TOKEN_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO ACTION_TOKENS_MOD_DET 
        SET 
        ACTION_TOKEN_ID = OLD.ACTION_TOKEN_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ACTION_TOKENS_MOD_DET`
--

DROP TABLE IF EXISTS `ACTION_TOKENS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `ACTION_TOKENS_MOD_DET` (
`ACTION_TOKENS_MOD_DET_ID` int(10) unsigned NOT NULL,
  `ACTION_TOKEN_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of action tokens' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ACTIVITY_SECTION_MASTER`
--

DROP TABLE IF EXISTS `ACTIVITY_SECTION_MASTER`;
CREATE TABLE IF NOT EXISTS `ACTIVITY_SECTION_MASTER` (
`ACTIVITY_SECTION_ID` int(10) unsigned NOT NULL,
  `ACTIVITY_SECTION_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table of notification activity section' AUTO_INCREMENT=1 ;

--
-- Triggers `ACTIVITY_SECTION_MASTER`
--
DROP TRIGGER IF EXISTS `ACTIVITY_SECTION_BU`;
DELIMITER //
CREATE TRIGGER `ACTIVITY_SECTION_BU` BEFORE UPDATE ON `activity_section_master`
 FOR EACH ROW BEGIN
	IF EXISTS (SELECT 1 FROM ACTIVITY_SECTION_MASTER WHERE ACTIVITY_SECTION_NAME = NEW.ACTIVITY_SECTION_NAME AND NEW.ACTIVITY_SECTION_NAME <> OLD.ACTIVITY_SECTION_NAME) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical activity section exists, please revise.';
    END IF;
    IF OLD.ACTIVITY_SECTION_NAME <> NEW.ACTIVITY_SECTION_NAME THEN
        INSERT INTO ACTIVITY_SECTION_MOD_DET 
        SET 
        ACTIVITY_SECTION_ID = OLD.ACTIVITY_SECTION_ID,
        COLUMN_NAME = 'ACTIVITY_SECTION_NAME',
        COLUMN_VALUE = OLD.ACTIVITY_SECTION_NAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO ACTIVITY_SECTION_MOD_DET 
        SET 
        ACTIVITY_SECTION_ID = OLD.ACTIVITY_SECTION_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ACTIVITY_SECTION_MOD_DET`
--

DROP TABLE IF EXISTS `ACTIVITY_SECTION_MOD_DET`;
CREATE TABLE IF NOT EXISTS `ACTIVITY_SECTION_MOD_DET` (
`ACTIVITY_SECTION_MOD_DET_ID` int(10) unsigned NOT NULL,
  `ACTIVITY_SECTION_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of notification activity section' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat`
--

DROP TABLE IF EXISTS `arrowchat`;
CREATE TABLE IF NOT EXISTS `arrowchat` (
`id` int(10) unsigned NOT NULL,
  `from` varchar(25) NOT NULL,
  `to` varchar(25) NOT NULL,
  `message` text NOT NULL,
  `sent` int(10) unsigned NOT NULL,
  `read` int(10) unsigned NOT NULL,
  `user_read` tinyint(1) NOT NULL DEFAULT '0',
  `direction` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_admin`
--

DROP TABLE IF EXISTS `arrowchat_admin`;
CREATE TABLE IF NOT EXISTS `arrowchat_admin` (
`id` int(3) unsigned NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_applications`
--

DROP TABLE IF EXISTS `arrowchat_applications`;
CREATE TABLE IF NOT EXISTS `arrowchat_applications` (
`id` int(3) unsigned NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `folder` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `icon` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `width` int(4) unsigned NOT NULL,
  `height` int(4) unsigned NOT NULL,
  `bar_width` int(3) unsigned DEFAULT NULL,
  `bar_name` varchar(100) DEFAULT NULL,
  `dont_reload` tinyint(1) unsigned DEFAULT '0',
  `default_bookmark` tinyint(1) unsigned DEFAULT '1',
  `show_to_guests` tinyint(1) unsigned DEFAULT '1',
  `link` varchar(255) DEFAULT NULL,
  `update_link` varchar(255) DEFAULT NULL,
  `version` varchar(20) DEFAULT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_banlist`
--

DROP TABLE IF EXISTS `arrowchat_banlist`;
CREATE TABLE IF NOT EXISTS `arrowchat_banlist` (
`ban_id` int(10) unsigned NOT NULL,
  `ban_userid` varchar(25) DEFAULT NULL,
  `ban_ip` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_chatroom_banlist`
--

DROP TABLE IF EXISTS `arrowchat_chatroom_banlist`;
CREATE TABLE IF NOT EXISTS `arrowchat_chatroom_banlist` (
  `user_id` varchar(25) COLLATE utf8_bin NOT NULL,
  `chatroom_id` int(10) unsigned NOT NULL,
  `ban_length` int(10) unsigned NOT NULL,
  `ban_time` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_chatroom_messages`
--

DROP TABLE IF EXISTS `arrowchat_chatroom_messages`;
CREATE TABLE IF NOT EXISTS `arrowchat_chatroom_messages` (
`id` int(10) unsigned NOT NULL,
  `chatroom_id` int(10) unsigned NOT NULL,
  `user_id` varchar(25) COLLATE utf8_bin NOT NULL,
  `username` varchar(100) COLLATE utf8_bin NOT NULL,
  `message` text COLLATE utf8_bin NOT NULL,
  `global_message` tinyint(1) unsigned DEFAULT '0',
  `is_mod` tinyint(1) unsigned DEFAULT '0',
  `is_admin` tinyint(1) unsigned DEFAULT '0',
  `sent` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_chatroom_rooms`
--

DROP TABLE IF EXISTS `arrowchat_chatroom_rooms`;
CREATE TABLE IF NOT EXISTS `arrowchat_chatroom_rooms` (
`id` int(10) unsigned NOT NULL,
  `author_id` varchar(25) COLLATE utf8_bin NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `password` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `length` int(10) unsigned NOT NULL,
  `max_users` int(10) NOT NULL DEFAULT '0',
  `session_time` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_chatroom_users`
--

DROP TABLE IF EXISTS `arrowchat_chatroom_users`;
CREATE TABLE IF NOT EXISTS `arrowchat_chatroom_users` (
  `user_id` varchar(25) COLLATE utf8_bin NOT NULL,
  `chatroom_id` int(10) unsigned NOT NULL,
  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_mod` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `block_chats` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `session_time` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_config`
--

DROP TABLE IF EXISTS `arrowchat_config`;
CREATE TABLE IF NOT EXISTS `arrowchat_config` (
  `config_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `config_value` text,
  `is_dynamic` tinyint(1) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_graph_log`
--

DROP TABLE IF EXISTS `arrowchat_graph_log`;
CREATE TABLE IF NOT EXISTS `arrowchat_graph_log` (
`id` int(6) unsigned NOT NULL,
  `date` varchar(30) NOT NULL,
  `user_messages` int(10) unsigned DEFAULT '0',
  `chat_room_messages` int(10) unsigned DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_notifications`
--

DROP TABLE IF EXISTS `arrowchat_notifications`;
CREATE TABLE IF NOT EXISTS `arrowchat_notifications` (
`id` int(25) unsigned NOT NULL,
  `to_id` varchar(25) NOT NULL,
  `author_id` varchar(25) NOT NULL,
  `author_name` varchar(100) NOT NULL,
  `misc1` varchar(255) DEFAULT NULL,
  `misc2` varchar(255) DEFAULT NULL,
  `misc3` varchar(255) DEFAULT NULL,
  `type` int(3) unsigned NOT NULL,
  `alert_read` int(1) unsigned NOT NULL DEFAULT '0',
  `user_read` int(1) unsigned NOT NULL DEFAULT '0',
  `alert_time` int(15) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_notifications_markup`
--

DROP TABLE IF EXISTS `arrowchat_notifications_markup`;
CREATE TABLE IF NOT EXISTS `arrowchat_notifications_markup` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` int(3) unsigned NOT NULL,
  `markup` text NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_smilies`
--

DROP TABLE IF EXISTS `arrowchat_smilies`;
CREATE TABLE IF NOT EXISTS `arrowchat_smilies` (
`id` int(3) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `code` varchar(10) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_status`
--

DROP TABLE IF EXISTS `arrowchat_status`;
CREATE TABLE IF NOT EXISTS `arrowchat_status` (
  `userid` varchar(25) NOT NULL,
  `guest_name` varchar(50) DEFAULT NULL,
  `message` text,
  `status` varchar(10) DEFAULT NULL,
  `theme` int(3) unsigned DEFAULT NULL,
  `popout` int(11) unsigned DEFAULT NULL,
  `typing` text,
  `hide_bar` tinyint(1) unsigned DEFAULT NULL,
  `play_sound` tinyint(1) unsigned DEFAULT '1',
  `window_open` tinyint(1) unsigned DEFAULT NULL,
  `only_names` tinyint(1) unsigned DEFAULT NULL,
  `chatroom_window` varchar(2) NOT NULL DEFAULT '-1',
  `chatroom_stay` varchar(2) NOT NULL DEFAULT '-1',
  `chatroom_block_chats` tinyint(1) unsigned DEFAULT NULL,
  `chatroom_sound` tinyint(1) unsigned DEFAULT NULL,
  `announcement` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `unfocus_chat` text,
  `focus_chat` varchar(50) DEFAULT NULL,
  `last_message` text,
  `clear_chats` text,
  `apps_bookmarks` text,
  `apps_other` text,
  `apps_open` int(10) unsigned DEFAULT NULL,
  `apps_load` text,
  `block_chats` text,
  `session_time` int(20) unsigned NOT NULL DEFAULT '0',
  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hash_id` varchar(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_themes`
--

DROP TABLE IF EXISTS `arrowchat_themes`;
CREATE TABLE IF NOT EXISTS `arrowchat_themes` (
`id` int(3) unsigned NOT NULL,
  `folder` varchar(25) NOT NULL,
  `name` varchar(100) NOT NULL,
  `active` tinyint(1) unsigned NOT NULL,
  `update_link` varchar(255) DEFAULT NULL,
  `version` varchar(20) DEFAULT NULL,
  `default` tinyint(1) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_trayicons`
--

DROP TABLE IF EXISTS `arrowchat_trayicons`;
CREATE TABLE IF NOT EXISTS `arrowchat_trayicons` (
`id` int(3) unsigned NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `icon` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `target` varchar(25) DEFAULT NULL,
  `width` int(4) unsigned DEFAULT NULL,
  `height` int(4) unsigned DEFAULT NULL,
  `tray_width` int(3) unsigned DEFAULT NULL,
  `tray_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `tray_location` int(3) unsigned NOT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ATTRIBUTES_MASTER`
--

DROP TABLE IF EXISTS `ATTRIBUTES_MASTER`;
CREATE TABLE IF NOT EXISTS `ATTRIBUTES_MASTER` (
`ATTRIBUTE_ID` int(11) unsigned NOT NULL,
  `ATTRIBUTE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `ATTRIBUTE_TYPE_ID` int(10) unsigned NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all user attributes' AUTO_INCREMENT=71 ;

--
-- Triggers `ATTRIBUTES_MASTER`
--
DROP TRIGGER IF EXISTS `ATTRIBUTE_MASTER_BU`;
DELIMITER //
CREATE TRIGGER `ATTRIBUTE_MASTER_BU` BEFORE UPDATE ON `attributes_master`
 FOR EACH ROW BEGIN
    IF OLD.ATTRIBUTE_TYPE_ID <> NEW.ATTRIBUTE_TYPE_ID THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Attribute type cannot be altered.';
    END IF;
    IF EXISTS (SELECT 1 FROM ATTRIBUTES_MASTER WHERE ATTRIBUTE_TYPE_ID = OLD.ATTRIBUTE_TYPE_ID AND ATTRIBUTE_DESCR = NEW.ATTRIBUTE_DESCR AND NEW.ATTRIBUTE_DESCR <> OLD.ATTRIBUTE_DESCR) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate record with identical attribute type and description exists, please revise.';
    END IF;
    IF OLD.ATTRIBUTE_DESCR <> NEW.ATTRIBUTE_DESCR THEN
        INSERT INTO ATTRIBUTE_MASTER_MOD_DET 
        SET 
        ATTRIBUTE_ID = OLD.ATTRIBUTE_ID,
        COLUMN_NAME = 'ATTRIBUTE_DESCR',
        COLUMN_VALUE = OLD.ATTRIBUTE_DESCR,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO ATTRIBUTE_MASTER_MOD_DET 
        SET 
        ATTRIBUTE_ID = OLD.ATTRIBUTE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ATTRIBUTE_MASTER_MOD_DET`
--

DROP TABLE IF EXISTS `ATTRIBUTE_MASTER_MOD_DET`;
CREATE TABLE IF NOT EXISTS `ATTRIBUTE_MASTER_MOD_DET` (
`ATTRIBUTE_MASTER_MOD_DET_ID` int(10) unsigned NOT NULL,
  `ATTRIBUTE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of attribute master' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ATTRIBUTE_TYPE_MASTER`
--

DROP TABLE IF EXISTS `ATTRIBUTE_TYPE_MASTER`;
CREATE TABLE IF NOT EXISTS `ATTRIBUTE_TYPE_MASTER` (
`ATTRIBUTE_TYPE_ID` int(10) unsigned NOT NULL,
  `ATTRIBUTE_TYPE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for attribute types' AUTO_INCREMENT=9 ;

--
-- Triggers `ATTRIBUTE_TYPE_MASTER`
--
DROP TRIGGER IF EXISTS `ATTRIBUTE_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `ATTRIBUTE_TYPE_BU` BEFORE UPDATE ON `attribute_type_master`
 FOR EACH ROW BEGIN
	IF EXISTS (SELECT 1 FROM ATTRIBUTE_TYPE_MASTER WHERE ATTRIBUTE_TYPE_DESCR = NEW.ATTRIBUTE_TYPE_DESCR AND NEW.ATTRIBUTE_TYPE_DESCR <> OLD.ATTRIBUTE_TYPE_DESCR) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical attribute type description already exists, please revise.';
    END IF;
    IF OLD.ATTRIBUTE_TYPE_DESCR <> NEW.ATTRIBUTE_TYPE_DESCR THEN
        INSERT INTO ATTRIBUTE_TYPE_MOD_DET 
        SET 
        ATTRIBUTE_TYPE_ID = OLD.ATTRIBUTE_TYPE_ID,
        COLUMN_NAME = 'ATTRIBUTE_TYPE_DESCR',
        COLUMN_VALUE = OLD.ATTRIBUTE_TYPE_DESCR,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO ATTRIBUTE_TYPE_MOD_DET 
        SET 
        ATTRIBUTE_TYPE_ID = OLD.ATTRIBUTE_TYPE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ATTRIBUTE_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `ATTRIBUTE_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `ATTRIBUTE_TYPE_MOD_DET` (
`ATTRIBUTE_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `ATTRIBUTE_TYPE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of attribute types' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `BLOCKED_USERS`
--

DROP TABLE IF EXISTS `BLOCKED_USERS`;
CREATE TABLE IF NOT EXISTS `BLOCKED_USERS` (
`BLOCKED_USER_ID` int(11) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `BLOCKED_USER` int(10) unsigned NOT NULL,
  `BLOCKED_ON` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Last blocked on',
  `BLOCKED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last blocked by',
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='List of all blocked users for a particular user' AUTO_INCREMENT=1 ;

--
-- Triggers `BLOCKED_USERS`
--
DROP TRIGGER IF EXISTS `BLOCKED_USERS_BU`;
DELIMITER //
CREATE TRIGGER `BLOCKED_USERS_BU` BEFORE UPDATE ON `blocked_users`
 FOR EACH ROW BEGIN
IF (OLD.USER_ID <> NEW.USER_ID) OR (OLD.BLOCKED_USER <> NEW.BLOCKED_USER) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'User and/or blocked user cannot be altered.';
END IF;
IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
INSERT INTO BLOCKED_USER_MOD_DET 
SET 
BLOCKED_USER_ID = OLD.BLOCKED_USER_ID,
COLUMN_NAME = 'STATUS_ID',
COLUMN_VALUE = OLD.STATUS_ID,
MODIFIED_BY = NEW.BLOCKED_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `BLOCKED_USER_MOD_DET`
--

DROP TABLE IF EXISTS `BLOCKED_USER_MOD_DET`;
CREATE TABLE IF NOT EXISTS `BLOCKED_USER_MOD_DET` (
`BLOCKED_USER_MOD_DET_ID` int(10) unsigned NOT NULL,
  `BLOCKED_USER_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for blocked users' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `CAREGIVER_RELATIONSHIP_MASTER`
--

DROP TABLE IF EXISTS `CAREGIVER_RELATIONSHIP_MASTER`;
CREATE TABLE IF NOT EXISTS `CAREGIVER_RELATIONSHIP_MASTER` (
`RELATIONSHIP_ID` int(10) unsigned NOT NULL,
  `RELATIONSHIP_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for care giver relationships' AUTO_INCREMENT=1 ;

--
-- Triggers `CAREGIVER_RELATIONSHIP_MASTER`
--
DROP TRIGGER IF EXISTS `CAREGIVER_RELATIONSHIP_BU`;
DELIMITER //
CREATE TRIGGER `CAREGIVER_RELATIONSHIP_BU` BEFORE UPDATE ON `caregiver_relationship_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM CAREGIVER_RELATIONSHIP_MASTER WHERE RELATIONSHIP_DESCR = NEW.RELATIONSHIP_DESCR AND NEW.RELATIONSHIP_DESCR <> OLD.RELATIONSHIP_DESCR) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical caregiver relationship exists, please revise.';
    END IF;
    IF OLD.RELATIONSHIP_DESCR <> NEW.RELATIONSHIP_DESCR THEN
        INSERT INTO CAREGIVER_RELATIONSHIP_MOD_DET 
        SET 
        RELATIONSHIP_ID = OLD.RELATIONSHIP_ID,
        COLUMN_NAME = 'RELATIONSHIP_DESCR',
        COLUMN_VALUE = OLD.RELATIONSHIP_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO CAREGIVER_RELATIONSHIP_MOD_DET 
        SET 
        RELATIONSHIP_ID = OLD.RELATIONSHIP_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `CAREGIVER_RELATIONSHIP_MOD_DET`
--

DROP TABLE IF EXISTS `CAREGIVER_RELATIONSHIP_MOD_DET`;
CREATE TABLE IF NOT EXISTS `CAREGIVER_RELATIONSHIP_MOD_DET` (
`CAREGIVER_RELATIONSHIP_MOD_DET_ID` int(10) unsigned NOT NULL,
  `RELATIONSHIP_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of caregiver relationships' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `CARE_CALENDAR_EVENTS`
--

DROP TABLE IF EXISTS `CARE_CALENDAR_EVENTS`;
CREATE TABLE IF NOT EXISTS `CARE_CALENDAR_EVENTS` (
`CARE_EVENT_ID` int(10) unsigned NOT NULL,
  `ASSIGNED_TO` int(10) unsigned NOT NULL COMMENT 'Patient/user id',
  `STATUS_ID` int(11) DEFAULT NULL,
  `CARE_EVENT_TYPE_ID` int(10) unsigned NOT NULL,
  `CARE_EVENT_FREQUENCY` float unsigned NOT NULL,
  `ADDITIONAL_NOTES` text COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Care events' AUTO_INCREMENT=1 ;

--
-- Triggers `CARE_CALENDAR_EVENTS`
--
DROP TRIGGER IF EXISTS `CARE_EVENTS_BU`;
DELIMITER //
CREATE TRIGGER `CARE_EVENTS_BU` BEFORE UPDATE ON `care_calendar_events`
 FOR EACH ROW BEGIN
	IF (OLD.ASSIGNED_TO <> NEW.ASSIGNED_TO) OR (OLD.CARE_EVENT_TYPE_ID <> NEW.CARE_EVENT_TYPE_ID) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Assigned user or care event type cannot be modified.';
    END IF;
    IF OLD.CARE_EVENT_FREQUENCY <> NEW.CARE_EVENT_FREQUENCY THEN
        INSERT INTO CARE_EVENTS_MOD_DET 
        SET 
        CARE_EVENT_ID = OLD.CARE_EVENT_ID,
        COLUMN_NAME = 'CARE_EVENT_FREQUENCY',
        COLUMN_VALUE = OLD.CARE_EVENT_FREQUENCY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.ADDITIONAL_NOTES <> NEW.ADDITIONAL_NOTES THEN
        INSERT INTO CARE_EVENTS_MOD_DET 
        SET 
        CARE_EVENT_ID = OLD.CARE_EVENT_ID,
        COLUMN_NAME = 'ADDITIONAL_NOTES',
        COLUMN_VALUE = OLD.ADDITIONAL_NOTES,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO CARE_EVENTS_MOD_DET 
        SET 
        CARE_EVENT_ID = OLD.CARE_EVENT_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `CARE_EVENTS_MOD_DET`
--

DROP TABLE IF EXISTS `CARE_EVENTS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `CARE_EVENTS_MOD_DET` (
`CARE_EVENTS_MOD_DET_ID` int(10) unsigned NOT NULL,
  `CARE_EVENT_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of care calendar events' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `CARE_GIVER_ATTRIBUTES`
--

DROP TABLE IF EXISTS `CARE_GIVER_ATTRIBUTES`;
CREATE TABLE IF NOT EXISTS `CARE_GIVER_ATTRIBUTES` (
`CARE_GIVER_ATTRIBUTE_ID` int(10) unsigned NOT NULL,
  `PATIENT_CARE_GIVER_ID` int(10) unsigned NOT NULL,
  `ATTRIBUTE_ID` int(10) unsigned NOT NULL,
  `ATTRIBUTE_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `EFF_DATE_FROM` datetime DEFAULT CURRENT_TIMESTAMP,
  `EFF_DATE_TO` datetime DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Attributes of patient care givers' AUTO_INCREMENT=1 ;

--
-- Triggers `CARE_GIVER_ATTRIBUTES`
--
DROP TRIGGER IF EXISTS `CARE_GIVER_ATTRIBUTES_BU`;
DELIMITER //
CREATE TRIGGER `CARE_GIVER_ATTRIBUTES_BU` BEFORE UPDATE ON `care_giver_attributes`
 FOR EACH ROW BEGIN
    IF (OLD.PATIENT_CARE_GIVER_ID <> NEW.PATIENT_CARE_GIVER_ID) OR (OLD.ATTRIBUTE_ID <> NEW.ATTRIBUTE_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Care giver or attribute type cannot be altered.';
    END IF;
    IF OLD.ATTRIBUTE_VALUE <> NEW.ATTRIBUTE_VALUE THEN
        INSERT INTO CARE_GIVER_ATTRIBUTE_MOD_DET 
        SET 
        CARE_GIVER_ATTRIBUTE_ID = OLD.CARE_GIVER_ATTRIBUTE_ID,
        COLUMN_NAME = 'ATTRIBUTE_VALUE',
        COLUMN_VALUE = OLD.ATTRIBUTE_VALUE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO CARE_GIVER_ATTRIBUTE_MOD_DET 
        SET 
        CARE_GIVER_ATTRIBUTE_ID = OLD.CARE_GIVER_ATTRIBUTE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.EFF_DATE_FROM <> NEW.EFF_DATE_FROM THEN
        INSERT INTO CARE_GIVER_ATTRIBUTE_MOD_DET 
        SET 
        CARE_GIVER_ATTRIBUTE_ID = OLD.CARE_GIVER_ATTRIBUTE_ID,
        COLUMN_NAME = 'EFF_DATE_FROM',
        COLUMN_VALUE = DATE_FORMAT(OLD.EFF_DATE_FROM,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.EFF_DATE_TO <> NEW.EFF_DATE_TO THEN
        INSERT INTO CARE_GIVER_ATTRIBUTE_MOD_DET 
        SET 
        CARE_GIVER_ATTRIBUTE_ID = OLD.CARE_GIVER_ATTRIBUTE_ID,
        COLUMN_NAME = 'EFF_DATE_TO',
        COLUMN_VALUE = DATE_FORMAT(OLD.EFF_DATE_TO,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `CARE_GIVER_ATTRIBUTE_MOD_DET`
--

DROP TABLE IF EXISTS `CARE_GIVER_ATTRIBUTE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `CARE_GIVER_ATTRIBUTE_MOD_DET` (
`CARE_GIVER_ATTRIBUTE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `CARE_GIVER_ATTRIBUTE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of care giver attributes' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `CITIES_MASTER`
--

DROP TABLE IF EXISTS `CITIES_MASTER`;
CREATE TABLE IF NOT EXISTS `CITIES_MASTER` (
`CITY_ID` int(11) NOT NULL,
  `DESCRIPTION` varchar(250) COLLATE latin1_general_cs NOT NULL,
  `SHORT_DESCRIPTION` varchar(15) COLLATE latin1_general_cs DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `STATE_ID` int(11) DEFAULT NULL,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for states' AUTO_INCREMENT=113990 ;

--
-- Triggers `CITIES_MASTER`
--
DROP TRIGGER IF EXISTS `CITIES_BU`;
DELIMITER //
CREATE TRIGGER `CITIES_BU` BEFORE UPDATE ON `cities_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM CITIES_MASTER WHERE DESCRIPTION = OLD.DESCRIPTION AND STATE_ID = NEW.STATE_ID AND NEW.STATE_ID <> OLD.STATE_ID) OR EXISTS (SELECT 1 FROM CITIES_MASTER WHERE DESCRIPTION = NEW.DESCRIPTION AND STATE_ID = OLD.STATE_ID AND NEW.DESCRIPTION <> OLD.DESCRIPTION) OR EXISTS (SELECT 1 FROM CITIES_MASTER WHERE DESCRIPTION = NEW.DESCRIPTION AND STATE_ID = NEW.STATE_ID AND (NEW.DESCRIPTION <> OLD.DESCRIPTION OR NEW.STATE_ID <> OLD>STATE_ID)) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical record exists with same state and city description, please revise.';
    END IF;
    IF OLD.DESCRIPTION <> NEW.DESCRIPTION THEN
        INSERT INTO CITIES_MOD_DET 
        SET 
        CITY_ID = OLD.CITY_ID,
        COLUMN_NAME = 'DESCRIPTION',
        COLUMN_VALUE = OLD.DESCRIPTION,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.SHORT_DESCRIPTION <> NEW.SHORT_DESCRIPTION THEN
        INSERT INTO CITIES_MOD_DET 
        SET 
        CITY_ID = OLD.CITY_ID,
        COLUMN_NAME = 'SHORT_DESCRIPTION',
        COLUMN_VALUE = OLD.SHORT_DESCRIPTION,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.STATE_ID <> NEW.STATE_ID THEN
        INSERT INTO CITIES_MOD_DET 
        SET 
        CITY_ID = OLD.CITY_ID,
        COLUMN_NAME = 'STATE_ID',
        COLUMN_VALUE = OLD.STATE_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO CITIES_MOD_DET 
        SET 
        CITY_ID = OLD.CITY_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `CITIES_MOD_DET`
--

DROP TABLE IF EXISTS `CITIES_MOD_DET`;
CREATE TABLE IF NOT EXISTS `CITIES_MOD_DET` (
`CITIES_MOD_DET_ID` int(10) unsigned NOT NULL,
  `CITY_ID` int(10) NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(250) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of cities' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `COMMUNITIES`
--

DROP TABLE IF EXISTS `COMMUNITIES`;
CREATE TABLE IF NOT EXISTS `COMMUNITIES` (
`COMMUNITY_ID` int(10) unsigned NOT NULL,
  `COMMUNITY_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COMMUNITY_DESCR` varchar(500) COLLATE latin1_general_cs NOT NULL,
  `COMMUNITY_TYPE_ID` int(10) unsigned NOT NULL,
  `MEMBER_CAN_INVITE` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'If member can invite then 1, else 0',
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MEMBER_COUNT` int(10) unsigned NOT NULL,
  `DISCUSSION_COUNT` int(10) unsigned NOT NULL,
  `EVENT_COUNT` int(10) unsigned NOT NULL,
  `IS_SLIDESHOW_ENABLED` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'if slideshow enabled then 1, else 0',
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Community table' AUTO_INCREMENT=1 ;

--
-- Triggers `COMMUNITIES`
--
DROP TRIGGER IF EXISTS `COMMUNITY_BU`;
DELIMITER //
CREATE TRIGGER `COMMUNITY_BU` BEFORE UPDATE ON `communities`
 FOR EACH ROW BEGIN
	IF EXISTS (SELECT 1 FROM COMMUNITIES WHERE COMMUNITY_NAME = NEW.COMMUNITY_NAME AND NEW.COMMUNITY_NAME <> OLD.COMMUNITY_NAME) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical community already exists, please revise.';
    END IF;
    IF OLD.COMMUNITY_NAME <> NEW.COMMUNITY_NAME THEN
        INSERT INTO COMMUNITY_MOD_DET 
        SET 
        COMMUNITY_ID = OLD.COMMUNITY_ID,
        COLUMN_NAME = 'COMMUNITY_NAME',
        COLUMN_VALUE = OLD.COMMUNITY_NAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.COMMUNITY_DESCR <> NEW.COMMUNITY_DESCR THEN
        INSERT INTO COMMUNITY_MOD_DET 
        SET 
        COMMUNITY_ID = OLD.COMMUNITY_ID,
        COLUMN_NAME = 'COMMUNITY_DESCR',
        COLUMN_VALUE = OLD.COMMUNITY_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.COMMUNITY_TYPE_ID <> NEW.COMMUNITY_TYPE_ID THEN
        INSERT INTO COMMUNITY_MOD_DET 
        SET 
        COMMUNITY_ID = OLD.COMMUNITY_ID,
        COLUMN_NAME = 'COMMUNITY_TYPE_ID',
        COLUMN_VALUE = OLD.COMMUNITY_TYPE_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.MEMBER_CAN_INVITE <> NEW.MEMBER_CAN_INVITE THEN
        INSERT INTO COMMUNITY_MOD_DET 
        SET 
        COMMUNITY_ID = OLD.COMMUNITY_ID,
        COLUMN_NAME = 'MEMBER_CAN_INVITE',
        COLUMN_VALUE = OLD.MEMBER_CAN_INVITE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_BY <> NEW.CREATED_BY THEN
        INSERT INTO COMMUNITY_MOD_DET 
        SET 
        COMMUNITY_ID = OLD.COMMUNITY_ID,
        COLUMN_NAME = 'CREATED_BY',
        COLUMN_VALUE = OLD.CREATED_BY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.IS_SLIDESHOW_ENABLED <> NEW.IS_SLIDESHOW_ENABLED THEN
        INSERT INTO COMMUNITY_MOD_DET 
        SET 
        COMMUNITY_ID = OLD.COMMUNITY_ID,
        COLUMN_NAME = 'IS_SLIDESHOW_ENABLED',
        COLUMN_VALUE = OLD.IS_SLIDESHOW_ENABLED,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO COMMUNITY_MOD_DET 
        SET 
        COMMUNITY_ID = OLD.COMMUNITY_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.MEMBER_COUNT <> NEW.MEMBER_COUNT THEN
        INSERT INTO COMMUNITY_MOD_DET 
        SET 
        COMMUNITY_ID = OLD.COMMUNITY_ID,
        COLUMN_NAME = 'MEMBER_COUNT',
        COLUMN_VALUE = OLD.MEMBER_COUNT;
    END IF;
    IF OLD.DISCUSSION_COUNT <> NEW.DISCUSSION_COUNT THEN
        INSERT INTO COMMUNITY_MOD_DET 
        SET 
        COMMUNITY_ID = OLD.COMMUNITY_ID,
        COLUMN_NAME = 'DISCUSSION_COUNT',
        COLUMN_VALUE = OLD.DISCUSSION_COUNT;
    END IF;
    IF OLD.EVENT_COUNT <> NEW.EVENT_COUNT THEN
        INSERT INTO COMMUNITY_MOD_DET 
        SET 
        COMMUNITY_ID = OLD.COMMUNITY_ID,
        COLUMN_NAME = 'EVENT_COUNT',
        COLUMN_VALUE = OLD.EVENT_COUNT;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `COMMUNITY_ATTRIBUTES`
--

DROP TABLE IF EXISTS `COMMUNITY_ATTRIBUTES`;
CREATE TABLE IF NOT EXISTS `COMMUNITY_ATTRIBUTES` (
`COMMUNITY_ATTRIBUTE_ID` int(10) unsigned NOT NULL,
  `COMMUNITY_ID` int(10) unsigned NOT NULL,
  `ATTRIBUTE_ID` int(10) unsigned NOT NULL,
  `ATTRIBUTE_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `EFF_DATE_FROM` datetime DEFAULT NULL,
  `EFF_DATE_TO` datetime DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Attribute values for the community' AUTO_INCREMENT=1 ;

--
-- Triggers `COMMUNITY_ATTRIBUTES`
--
DROP TRIGGER IF EXISTS `COMMUNITY_ATTRIBUTES_BU`;
DELIMITER //
CREATE TRIGGER `COMMUNITY_ATTRIBUTES_BU` BEFORE UPDATE ON `community_attributes`
 FOR EACH ROW BEGIN
    IF (OLD.COMMUNITY_ID <> NEW.COMMUNITY_ID) OR (OLD.ATTRIBUTE_ID <> NEW.ATTRIBUTE_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Community or attribute type cannot be altered.';
    END IF;
    IF OLD.ATTRIBUTE_VALUE <> NEW.ATTRIBUTE_VALUE THEN
        INSERT INTO COMMUNITY_ATTRIBUTES_MOD_DET 
        SET 
        COMMUNITY_ATTRIBUTE_ID = OLD.COMMUNITY_ATTRIBUTE_ID,
        COLUMN_NAME = 'ATTRIBUTE_VALUE',
        COLUMN_VALUE = OLD.ATTRIBUTE_VALUE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO COMMUNITY_ATTRIBUTES_MOD_DET 
        SET 
        COMMUNITY_ATTRIBUTE_ID = OLD.COMMUNITY_ATTRIBUTE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.EFF_DATE_FROM <> NEW.EFF_DATE_FROM THEN
        INSERT INTO COMMUNITY_ATTRIBUTES_MOD_DET 
        SET 
        COMMUNITY_ATTRIBUTE_ID = OLD.COMMUNITY_ATTRIBUTE_ID,
        COLUMN_NAME = 'EFF_DATE_FROM',
        COLUMN_VALUE = DATE_FORMAT(OLD.EFF_DATE_FROM,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.EFF_DATE_TO <> NEW.EFF_DATE_TO THEN
        INSERT INTO COMMUNITY_ATTRIBUTES_MOD_DET 
        SET 
        COMMUNITY_ATTRIBUTE_ID = OLD.COMMUNITY_ATTRIBUTE_ID,
        COLUMN_NAME = 'EFF_DATE_TO',
        COLUMN_VALUE = DATE_FORMAT(OLD.EFF_DATE_TO,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `COMMUNITY_ATTRIBUTES_MOD_DET`
--

DROP TABLE IF EXISTS `COMMUNITY_ATTRIBUTES_MOD_DET`;
CREATE TABLE IF NOT EXISTS `COMMUNITY_ATTRIBUTES_MOD_DET` (
`COMMUNITY_ATTRIBUTE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `COMMUNITY_ATTRIBUTE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of community attributes' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `COMMUNITY_DISEASES`
--

DROP TABLE IF EXISTS `COMMUNITY_DISEASES`;
CREATE TABLE IF NOT EXISTS `COMMUNITY_DISEASES` (
`COMMUNITY_DISEASE_ID` int(10) unsigned NOT NULL,
  `COMMUNITY_ID` int(10) unsigned NOT NULL,
  `DISEASE_ID` int(10) unsigned NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Community diseases' AUTO_INCREMENT=1 ;

--
-- Triggers `COMMUNITY_DISEASES`
--
DROP TRIGGER IF EXISTS `COMMUNITY_DISEASES_BU`;
DELIMITER //
CREATE TRIGGER `COMMUNITY_DISEASES_BU` BEFORE UPDATE ON `community_diseases`
 FOR EACH ROW BEGIN
	IF (OLD.COMMUNITY_ID <> NEW.COMMUNITY_ID) OR (OLD.DISEASE_ID <> NEW.DISEASE_ID) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Community or disease cannot be altered.';
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO COMMUNITY_DISEASES_MOD_DET 
        SET 
        COMMUNITY_DISEASE_ID = OLD.COMMUNITY_DISEASE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `COMMUNITY_DISEASES_MOD_DET`
--

DROP TABLE IF EXISTS `COMMUNITY_DISEASES_MOD_DET`;
CREATE TABLE IF NOT EXISTS `COMMUNITY_DISEASES_MOD_DET` (
`COMMUNITY_DISEASE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `COMMUNITY_DISEASE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of community diseases' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `COMMUNITY_MEMBERS`
--

DROP TABLE IF EXISTS `COMMUNITY_MEMBERS`;
CREATE TABLE IF NOT EXISTS `COMMUNITY_MEMBERS` (
`COMMUNITY_MEMBER_ID` int(10) unsigned NOT NULL,
  `COMMUNITY_ID` int(10) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `USER_TYPE_ID` int(10) NOT NULL,
  `INVITED_BY` int(10) unsigned DEFAULT NULL,
  `INVITED_ON` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Invitation or request date',
  `JOINED_ON` datetime DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Community members' AUTO_INCREMENT=1 ;

--
-- Triggers `COMMUNITY_MEMBERS`
--
DROP TRIGGER IF EXISTS `COMMUNITY_MEMBERS_BU`;
DELIMITER //
CREATE TRIGGER `COMMUNITY_MEMBERS_BU` BEFORE UPDATE ON `community_members`
 FOR EACH ROW BEGIN
    IF (OLD.USER_ID <> NEW.USER_ID) OR (OLD.COMMUNITY_ID <> NEW.COMMUNITY_ID) OR (OLD.USER_TYPE_ID <> NEW.USER_TYPE_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'User or community or user role cannot be altered.';
    END IF;
    IF OLD.INVITED_BY <> NEW.INVITED_BY THEN
        INSERT INTO COMMUNITY_MEMBERS_MOD_DET 
        SET 
        COMMUNITY_MEMBER_ID = OLD.COMMUNITY_MEMBER_ID,
        COLUMN_NAME = 'INVITED_BY',
        COLUMN_VALUE = OLD.INVITED_BY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.INVITED_ON <> NEW.INVITED_ON THEN
        INSERT INTO COMMUNITY_MEMBERS_MOD_DET 
        SET 
        COMMUNITY_MEMBER_ID = OLD.COMMUNITY_MEMBER_ID,
        COLUMN_NAME = 'INVITED_ON',
        COLUMN_VALUE = DATE_FORMAT(OLD.INVITED_ON,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.JOINED_ON <> NEW.JOINED_ON THEN
        INSERT INTO COMMUNITY_MEMBERS_MOD_DET 
        SET 
        COMMUNITY_MEMBER_ID = OLD.COMMUNITY_MEMBER_ID,
        COLUMN_NAME = 'JOINED_ON',
        COLUMN_VALUE = DATE_FORMAT(OLD.JOINED_ON,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO COMMUNITY_MEMBERS_MOD_DET 
        SET 
        COMMUNITY_MEMBER_ID = OLD.COMMUNITY_MEMBER_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `COMMUNITY_MEMBERS_MOD_DET`
--

DROP TABLE IF EXISTS `COMMUNITY_MEMBERS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `COMMUNITY_MEMBERS_MOD_DET` (
`COMMUNITY_MEMBER_MOD_DET_ID` int(10) unsigned NOT NULL,
  `COMMUNITY_MEMBER_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of community members' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `COMMUNITY_MOD_DET`
--

DROP TABLE IF EXISTS `COMMUNITY_MOD_DET`;
CREATE TABLE IF NOT EXISTS `COMMUNITY_MOD_DET` (
`COMMUNITY_MOD_DET_ID` int(10) unsigned NOT NULL,
  `COMMUNITY_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of communities' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `COMMUNITY_PHOTOS`
--

DROP TABLE IF EXISTS `COMMUNITY_PHOTOS`;
CREATE TABLE IF NOT EXISTS `COMMUNITY_PHOTOS` (
`COMMUNITY_PHOTO_ID` int(10) unsigned NOT NULL,
  `COMMUNITY_ID` int(10) unsigned NOT NULL,
  `FILE_NAME` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `PHOTO_TYPE_ID` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Community photos' AUTO_INCREMENT=1 ;

--
-- Triggers `COMMUNITY_PHOTOS`
--
DROP TRIGGER IF EXISTS `COMMUNITY_PHOTOS_BU`;
DELIMITER //
CREATE TRIGGER `COMMUNITY_PHOTOS_BU` BEFORE UPDATE ON `community_photos`
 FOR EACH ROW BEGIN
    IF OLD.COMMUNITY_ID <> NEW.COMMUNITY_ID THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Community cannot be altered.';
    END IF;
    IF OLD.FILE_NAME <> NEW.FILE_NAME THEN
        INSERT INTO COMMUNITY_PHOTOS_MOD_DET 
        SET 
        COMMUNITY_PHOTO_ID = OLD.COMMUNITY_PHOTO_ID,
        COLUMN_NAME = 'FILE_NAME',
        COLUMN_VALUE = OLD.FILE_NAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.PHOTO_TYPE_ID <> NEW.PHOTO_TYPE_ID THEN
        INSERT INTO COMMUNITY_PHOTOS_MOD_DET 
        SET 
        COMMUNITY_PHOTO_ID = OLD.COMMUNITY_PHOTO_ID,
        COLUMN_NAME = 'PHOTO_TYPE_ID',
        COLUMN_VALUE = OLD.PHOTO_TYPE_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO COMMUNITY_PHOTOS_MOD_DET 
        SET 
        COMMUNITY_PHOTO_ID = OLD.COMMUNITY_PHOTO_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `COMMUNITY_PHOTOS_MOD_DET`
--

DROP TABLE IF EXISTS `COMMUNITY_PHOTOS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `COMMUNITY_PHOTOS_MOD_DET` (
`COMMUNITY_PHOTO_MOD_DET_ID` int(10) unsigned NOT NULL,
  `COMMUNITY_PHOTO_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of community photos' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `COMMUNITY_TYPE_MASTER`
--

DROP TABLE IF EXISTS `COMMUNITY_TYPE_MASTER`;
CREATE TABLE IF NOT EXISTS `COMMUNITY_TYPE_MASTER` (
`COMMUNITY_TYPE_ID` int(10) unsigned NOT NULL,
  `COMMUNITY_TYPE_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for community types' AUTO_INCREMENT=1 ;

--
-- Triggers `COMMUNITY_TYPE_MASTER`
--
DROP TRIGGER IF EXISTS `COMMUNITY_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `COMMUNITY_TYPE_BU` BEFORE UPDATE ON `community_type_master`
 FOR EACH ROW BEGIN
	IF EXISTS (SELECT 1 FROM COMMUNITY_TYPE_MASTER WHERE COMMUNITY_TYPE_NAME = NEW.COMMUNITY_TYPE_NAME AND NEW.COMMUNITY_TYPE_NAME <> OLD.COMMUNITY_TYPE_NAME) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical community type already exists, please revise.';
    END IF;
    IF OLD.COMMUNITY_TYPE_NAME <> NEW.COMMUNITY_TYPE_NAME THEN
        INSERT INTO COMMUNITY_TYPE_MOD_DET 
        SET 
        COMMUNITY_TYPE_ID = OLD.COMMUNITY_TYPE_ID,
        COLUMN_NAME = 'COMMUNITY_TYPE_NAME',
        COLUMN_VALUE = OLD.COMMUNITY_TYPE_NAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO COMMUNITY_TYPE_MOD_DET 
        SET 
        COMMUNITY_TYPE_ID = OLD.COMMUNITY_TYPE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `COMMUNITY_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `COMMUNITY_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `COMMUNITY_TYPE_MOD_DET` (
`COMMUNITY_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `COMMUNITY_TYPE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of community types' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `CONFIGURATIONS`
--

DROP TABLE IF EXISTS `CONFIGURATIONS`;
CREATE TABLE IF NOT EXISTS `CONFIGURATIONS` (
`CONFIGURATION_ID` int(10) unsigned NOT NULL,
  `CONFIGURATION_NAME` varchar(300) COLLATE latin1_general_cs NOT NULL,
  `CONFIGURATION_VALUE` text COLLATE latin1_general_cs NOT NULL,
  `CONFIGURATION_LABEL` varchar(300) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Configuration items' AUTO_INCREMENT=1 ;

--
-- Triggers `CONFIGURATIONS`
--
DROP TRIGGER IF EXISTS `CONFIGURATIONS_BU`;
DELIMITER //
CREATE TRIGGER `CONFIGURATIONS_BU` BEFORE UPDATE ON `configurations`
 FOR EACH ROW BEGIN
	IF EXISTS (SELECT 1 FROM CONFIGURATIONS WHERE CONFIGURATION_NAME = NEW.CONFIGURATION_NAME AND NEW.CONFIGURATION_NAME <> OLD.CONFIGURATION_NAME) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical configuration item already exists, please revise.';
    END IF;
    IF OLD.CONFIGURATION_NAME <> NEW.CONFIGURATION_NAME THEN
        INSERT INTO CONFIGURATIONS_MOD_DET 
        SET 
        CONFIGURATION_ID = OLD.CONFIGURATION_ID,
        COLUMN_NAME = 'CONFIGURATION_NAME',
        COLUMN_VALUE = OLD.CONFIGURATION_NAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CONFIGURATION_VALUE <> NEW.CONFIGURATION_VALUE THEN
        INSERT INTO CONFIGURATIONS_MOD_DET 
        SET 
        CONFIGURATION_ID = OLD.CONFIGURATION_ID,
        COLUMN_NAME = 'CONFIGURATION_VALUE',
        COLUMN_VALUE = OLD.CONFIGURATION_VALUE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CONFIGURATION_LABEL <> NEW.CONFIGURATION_LABEL THEN
        INSERT INTO CONFIGURATIONS_MOD_DET 
        SET 
        CONFIGURATION_ID = OLD.CONFIGURATION_ID,
        COLUMN_NAME = 'CONFIGURATION_LABEL',
        COLUMN_VALUE = OLD.CONFIGURATION_LABEL,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO CONFIGURATIONS_MOD_DET 
        SET 
        CONFIGURATION_ID = OLD.CONFIGURATION_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `CONFIGURATIONS_MOD_DET`
--

DROP TABLE IF EXISTS `CONFIGURATIONS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `CONFIGURATIONS_MOD_DET` (
`CONFIGURATION_MOD_DET_ID` int(10) unsigned NOT NULL,
  `CONFIGURATION_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of configuration items' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `COUNTRY_MASTER`
--

DROP TABLE IF EXISTS `COUNTRY_MASTER`;
CREATE TABLE IF NOT EXISTS `COUNTRY_MASTER` (
`COUNTRY_ID` int(11) NOT NULL,
  `ISO2` char(2) COLLATE latin1_general_cs DEFAULT NULL,
  `SHORT_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LONG_NAME` varchar(250) COLLATE latin1_general_cs DEFAULT NULL,
  `ISO3` char(3) COLLATE latin1_general_cs DEFAULT NULL,
  `NUMCODE` varchar(6) COLLATE latin1_general_cs DEFAULT NULL,
  `UN_MEMBER` tinyint(3) unsigned DEFAULT '0',
  `CALLING_CODE` varchar(8) COLLATE latin1_general_cs DEFAULT NULL,
  `CCTLD` varchar(5) COLLATE latin1_general_cs DEFAULT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all countries' AUTO_INCREMENT=246 ;

--
-- Triggers `COUNTRY_MASTER`
--
DROP TRIGGER IF EXISTS `COUNTRY_BU`;
DELIMITER //
CREATE TRIGGER `COUNTRY_BU` BEFORE UPDATE ON `country_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM COUNTRY_MASTER WHERE SHORT_NAME = NEW.SHORT_NAME AND NEW.SHORT_NAME <> OLD.SHORT_NAME) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical country name already exists, please revise.';
    END IF;
    IF OLD.ISO2 <> NEW.ISO2 THEN
        INSERT INTO COUNTRY_MOD_DET 
        SET 
        COUNTRY_ID = OLD.COUNTRY_ID,
        COLUMN_NAME = 'ISO2',
        COLUMN_VALUE = OLD.ISO2,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.SHORT_NAME <> NEW.SHORT_NAME THEN
        INSERT INTO COUNTRY_MOD_DET 
        SET 
        COUNTRY_ID = OLD.COUNTRY_ID,
        COLUMN_NAME = 'SHORT_NAME',
        COLUMN_VALUE = OLD.SHORT_NAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.LONG_NAME <> NEW.LONG_NAME THEN
        INSERT INTO COUNTRY_MOD_DET 
        SET 
        COUNTRY_ID = OLD.COUNTRY_ID,
        COLUMN_NAME = 'LONG_NAME',
        COLUMN_VALUE = OLD.LONG_NAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.ISO3 <> NEW.ISO3 THEN
        INSERT INTO COUNTRY_MOD_DET 
        SET 
        COUNTRY_ID = OLD.COUNTRY_ID,
        COLUMN_NAME = 'ISO3',
        COLUMN_VALUE = OLD.ISO3,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.NUMCODE <> NEW.NUMCODE THEN
        INSERT INTO COUNTRY_MOD_DET 
        SET 
        COUNTRY_ID = OLD.COUNTRY_ID,
        COLUMN_NAME = 'NUMCODE',
        COLUMN_VALUE = OLD.NUMCODE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.UN_MEMBER <> NEW.UN_MEMBER THEN
        INSERT INTO COUNTRY_MOD_DET 
        SET 
        COUNTRY_ID = OLD.COUNTRY_ID,
        COLUMN_NAME = 'UN_MEMBER',
        COLUMN_VALUE = OLD.UN_MEMBER,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CALLING_CODE <> NEW.CALLING_CODE THEN
        INSERT INTO COUNTRY_MOD_DET 
        SET 
        COUNTRY_ID = OLD.COUNTRY_ID,
        COLUMN_NAME = 'CALLING_CODE',
        COLUMN_VALUE = OLD.CALLING_CODE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CCTLD <> NEW.CCTLD THEN
        INSERT INTO COUNTRY_MOD_DET 
        SET 
        COUNTRY_ID = OLD.COUNTRY_ID,
        COLUMN_NAME = 'CCTLD',
        COLUMN_VALUE = OLD.CCTLD,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO COUNTRY_MOD_DET 
        SET 
        COUNTRY_ID = OLD.COUNTRY_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `COUNTRY_MOD_DET`
--

DROP TABLE IF EXISTS `COUNTRY_MOD_DET`;
CREATE TABLE IF NOT EXISTS `COUNTRY_MOD_DET` (
`COUNTRY_MOD_DET_ID` int(10) unsigned NOT NULL,
  `COUNTRY_ID` int(11) NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(250) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of countries' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `CRON_TASKS`
--

DROP TABLE IF EXISTS `CRON_TASKS`;
CREATE TABLE IF NOT EXISTS `CRON_TASKS` (
`TASK_ID` int(10) unsigned NOT NULL,
  `TASK_TYPE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `TASK_TITLE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `TASK_PARAMS` text COLLATE latin1_general_cs,
  `TASK_NAME` varchar(300) COLLATE latin1_general_cs DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `WORKER_KEY` varchar(100) COLLATE latin1_general_cs DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL,
  `EFF_DATE_FROM` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `EFF_DATE_TO` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Cron jobs' AUTO_INCREMENT=1 ;

--
-- Triggers `CRON_TASKS`
--
DROP TRIGGER IF EXISTS `CRON_TASKS_BU`;
DELIMITER //
CREATE TRIGGER `CRON_TASKS_BU` BEFORE UPDATE ON `cron_tasks`
 FOR EACH ROW BEGIN
	IF EXISTS (SELECT 1 FROM CRON_TASKS WHERE TASK_TITLE = NEW.TASK_TITLE AND NEW.TASK_TITLE <> OLD.TASK_TITLE) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical cron task already exists, please revise.';
    END IF;
    IF OLD.TASK_TYPE <> NEW.TASK_TYPE THEN
        INSERT INTO CRON_TASKS_MOD_DET 
        SET 
        TASK_ID = OLD.TASK_ID,
        COLUMN_NAME = 'TASK_TYPE',
        COLUMN_VALUE = OLD.TASK_TYPE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.TASK_TITLE <> NEW.TASK_TITLE THEN
        INSERT INTO CRON_TASKS_MOD_DET 
        SET 
        TASK_ID = OLD.TASK_ID,
        COLUMN_NAME = 'TASK_TITLE',
        COLUMN_VALUE = OLD.TASK_TITLE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.TASK_PARAMS <> NEW.TASK_PARAMS THEN
        INSERT INTO CRON_TASKS_MOD_DET 
        SET 
        TASK_ID = OLD.TASK_ID,
        COLUMN_NAME = 'TASK_PARAMS',
        COLUMN_VALUE = OLD.TASK_PARAMS,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.TASK_NAME <> NEW.TASK_NAME THEN
        INSERT INTO CRON_TASKS_MOD_DET 
        SET 
        TASK_ID = OLD.TASK_ID,
        COLUMN_NAME = 'TASK_NAME',
        COLUMN_VALUE = OLD.TASK_NAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_BY <> NEW.CREATED_BY THEN
        INSERT INTO CRON_TASKS_MOD_DET 
        SET 
        TASK_ID = OLD.TASK_ID,
        COLUMN_NAME = 'CREATED_BY',
        COLUMN_VALUE = OLD.CREATED_BY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_ON <> NEW.CREATED_ON THEN
        INSERT INTO CRON_TASKS_MOD_DET 
        SET 
        TASK_ID = OLD.TASK_ID,
        COLUMN_NAME = 'CREATED_ON',
        COLUMN_VALUE = DATE_FORMAT(OLD.CREATED_ON,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.WORKER_KEY <> NEW.WORKER_KEY THEN
        INSERT INTO CRON_TASKS_MOD_DET 
        SET 
        TASK_ID = OLD.TASK_ID,
        COLUMN_NAME = 'WORKER_KEY',
        COLUMN_VALUE = OLD.WORKER_KEY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.EFF_DATE_FROM <> NEW.EFF_DATE_FROM THEN
        INSERT INTO CRON_TASKS_MOD_DET 
        SET 
        TASK_ID = OLD.TASK_ID,
        COLUMN_NAME = 'EFF_DATE_FROM',
        COLUMN_VALUE = DATE_FORMAT(OLD.EFF_DATE_FROM,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.EFF_DATE_TO <> NEW.EFF_DATE_TO THEN
        INSERT INTO CRON_TASKS_MOD_DET 
        SET 
        TASK_ID = OLD.TASK_ID,
        COLUMN_NAME = 'EFF_DATE_TO',
        COLUMN_VALUE = DATE_FORMAT(OLD.EFF_DATE_TO,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO CRON_TASKS_MOD_DET 
        SET 
        TASK_ID = OLD.TASK_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `CRON_TASKS_MOD_DET`
--

DROP TABLE IF EXISTS `CRON_TASKS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `CRON_TASKS_MOD_DET` (
`CRON_TASK_MOD_DET_ID` int(10) unsigned NOT NULL,
  `TASK_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details for cron taks' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `CRON_TASK_EXEC_LOG`
--

DROP TABLE IF EXISTS `CRON_TASK_EXEC_LOG`;
CREATE TABLE IF NOT EXISTS `CRON_TASK_EXEC_LOG` (
`CRON_TASK_EXEC_LOG_ID` int(10) unsigned NOT NULL,
  `TASK_ID` int(10) unsigned NOT NULL,
  `START_TIME` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FINISH_TIME` datetime NOT NULL,
  `MESSAGE_DETAILS` text COLLATE latin1_general_cs,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Cron job execution log' AUTO_INCREMENT=1 ;

--
-- Triggers `CRON_TASK_EXEC_LOG`
--
DROP TRIGGER IF EXISTS `CRON_TASK_EXEC_LOG_BU`;
DELIMITER //
CREATE TRIGGER `CRON_TASK_EXEC_LOG_BU` BEFORE UPDATE ON `cron_task_exec_log`
 FOR EACH ROW BEGIN
    IF (OLD.TASK_ID <> NEW.TASK_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cron task cannot be altered.';
    END IF;
    IF OLD.MESSAGE_DETAILS <> NEW.MESSAGE_DETAILS THEN
        INSERT INTO CRON_TASK_EXEC_LOG_MOD_DET 
        SET 
        CRON_TASK_EXEC_LOG_ID = OLD.CRON_TASK_EXEC_LOG_ID,
        COLUMN_NAME = 'MESSAGE_DETAILS',
        COLUMN_VALUE = OLD.MESSAGE_DETAILS,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO CRON_TASK_EXEC_LOG_MOD_DET 
        SET 
        CRON_TASK_EXEC_LOG_ID = OLD.CRON_TASK_EXEC_LOG_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.START_TIME <> NEW.START_TIME THEN
        INSERT INTO CRON_TASK_EXEC_LOG_MOD_DET 
        SET 
        CRON_TASK_EXEC_LOG_ID = OLD.CRON_TASK_EXEC_LOG_ID,
        COLUMN_NAME = 'START_TIME',
        COLUMN_VALUE = DATE_FORMAT(OLD.START_TIME,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.FINISH_TIME <> NEW.FINISH_TIME THEN
        INSERT INTO CRON_TASK_EXEC_LOG_MOD_DET 
        SET 
        CRON_TASK_EXEC_LOG_ID = OLD.CRON_TASK_EXEC_LOG_ID,
        COLUMN_NAME = 'FINISH_TIME',
        COLUMN_VALUE = DATE_FORMAT(OLD.FINISH_TIME,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `CRON_TASK_EXEC_LOG_MOD_DET`
--

DROP TABLE IF EXISTS `CRON_TASK_EXEC_LOG_MOD_DET`;
CREATE TABLE IF NOT EXISTS `CRON_TASK_EXEC_LOG_MOD_DET` (
`CRON_TASK_EXEC_LOG_MOD_DET_ID` int(10) unsigned NOT NULL,
  `CRON_TASK_EXEC_LOG_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of cron job execution log' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `DATES`
--

DROP TABLE IF EXISTS `DATES`;
CREATE TABLE IF NOT EXISTS `DATES` (
`DATE_ID` int(10) unsigned NOT NULL,
  `DATE_VALUE` varchar(5) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='List of all possible date values' AUTO_INCREMENT=32 ;

--
-- Triggers `DATES`
--
DROP TRIGGER IF EXISTS `CALENDAR_DATE_BU`;
DELIMITER //
CREATE TRIGGER `CALENDAR_DATE_BU` BEFORE UPDATE ON `dates`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM DATES WHERE DATE_VALUE <> NEW.DATE_VALUE AND NEW.DATE_VALUE <> OLD.DATE_VALUE) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical record with the same date already exists.';
    END IF;
    IF OLD.DATE_VALUE <> NEW.DATE_VALUE THEN
        INSERT INTO DATES_MOD_DET 
        SET 
        DATE_ID = OLD.DATE_ID,
        COLUMN_NAME = 'DATE_VALUE',
        COLUMN_VALUE = OLD.DATE_VALUE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO DATES_MOD_DET 
        SET 
        DATE_ID = OLD.DATE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `DATES_MOD_DET`
--

DROP TABLE IF EXISTS `DATES_MOD_DET`;
CREATE TABLE IF NOT EXISTS `DATES_MOD_DET` (
`DATES_MOD_DET_ID` int(10) unsigned NOT NULL,
  `DATE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of calendar dates' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `DISEASE_MASTER`
--

DROP TABLE IF EXISTS `DISEASE_MASTER`;
CREATE TABLE IF NOT EXISTS `DISEASE_MASTER` (
`DISEASE_ID` int(10) unsigned NOT NULL,
  `DISEASE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `PARENT_DISEASE_ID` int(10) unsigned DEFAULT NULL,
  `DISEASE_DESCR` text COLLATE latin1_general_cs,
  `DISEASE_LIBRARY` text COLLATE latin1_general_cs,
  `DISEASE_DASHBOARD_DATA` text COLLATE latin1_general_cs,
  `STATUS_ID` int(11) DEFAULT NULL,
  `DISEASE_SURVEY_ID` int(10) unsigned DEFAULT NULL,
  `FOLLOWER_COUNT` int(10) unsigned DEFAULT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last edited on'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for diseases' AUTO_INCREMENT=1 ;

--
-- Triggers `DISEASE_MASTER`
--
DROP TRIGGER IF EXISTS `DISEASE_BU`;
DELIMITER //
CREATE TRIGGER `DISEASE_BU` BEFORE UPDATE ON `disease_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM DISEASE_MASTER WHERE DISEASE = NEW.DISEASE AND NEW.DISEASE <> OLD.DISEASE) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical disease name already exists, please revise.';
    END IF;
    IF OLD.DISEASE <> NEW.DISEASE THEN
        INSERT INTO DISEASE_MOD_DET 
        SET 
        DISEASE_ID = OLD.DISEASE_ID,
        COLUMN_NAME = 'DISEASE',
        COLUMN_VALUE = OLD.DISEASE,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.PARENT_DISEASE_ID <> NEW.PARENT_DISEASE_ID THEN
        INSERT INTO DISEASE_MOD_DET 
        SET 
        DISEASE_ID = OLD.DISEASE_ID,
        COLUMN_NAME = 'PARENT_DISEASE_ID',
        COLUMN_VALUE = OLD.PARENT_DISEASE_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.DISEASE_DESCR <> NEW.DISEASE_DESCR THEN
        INSERT INTO DISEASE_MOD_DET 
        SET 
        DISEASE_ID = OLD.DISEASE_ID,
        COLUMN_NAME = 'DISEASE_DESCR',
        COLUMN_VALUE = OLD.DISEASE_DESCR,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.DISEASE_LIBRARY <> NEW.DISEASE_LIBRARY THEN
        INSERT INTO DISEASE_MOD_DET 
        SET 
        DISEASE_ID = OLD.DISEASE_ID,
        COLUMN_NAME = 'DISEASE_LIBRARY',
        COLUMN_VALUE = OLD.DISEASE_LIBRARY,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.DISEASE_DASHBOARD_DATA <> NEW.DISEASE_DASHBOARD_DATA THEN
        INSERT INTO DISEASE_MOD_DET 
        SET 
        DISEASE_ID = OLD.DISEASE_ID,
        COLUMN_NAME = 'DISEASE_DASHBOARD_DATA',
        COLUMN_VALUE = OLD.DISEASE_DASHBOARD_DATA,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO DISEASE_MOD_DET 
        SET 
        DISEASE_ID = OLD.DISEASE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.DISEASE_SURVEY_ID <> NEW.DISEASE_SURVEY_ID THEN
        INSERT INTO DISEASE_MOD_DET 
        SET 
        DISEASE_ID = OLD.DISEASE_ID,
        COLUMN_NAME = 'DISEASE_SURVEY_ID',
        COLUMN_VALUE = OLD.DISEASE_SURVEY_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.FOLLOWER_COUNT <> NEW.FOLLOWER_COUNT THEN
        INSERT INTO DISEASE_MOD_DET 
        SET 
        DISEASE_ID = OLD.DISEASE_ID,
        COLUMN_NAME = 'FOLLOWER_COUNT',
        COLUMN_VALUE = OLD.FOLLOWER_COUNT,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `DISEASE_MOD_DET`
--

DROP TABLE IF EXISTS `DISEASE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `DISEASE_MOD_DET` (
`DISEASE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `DISEASE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of diseases' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `DISEASE_SYMPTOMS`
--

DROP TABLE IF EXISTS `DISEASE_SYMPTOMS`;
CREATE TABLE IF NOT EXISTS `DISEASE_SYMPTOMS` (
`DISEASE_SYMPTOM_ID` int(10) unsigned NOT NULL,
  `DISEASE_ID` int(10) unsigned NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last edited on',
  `STATUS_ID` int(11) DEFAULT NULL,
  `SYMPTOM_ID` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all disease symptoms' AUTO_INCREMENT=1 ;

--
-- Triggers `DISEASE_SYMPTOMS`
--
DROP TRIGGER IF EXISTS `DISEASE_SYMPTOMS_BU`;
DELIMITER //
CREATE TRIGGER `DISEASE_SYMPTOMS_BU` BEFORE UPDATE ON `disease_symptoms`
 FOR EACH ROW BEGIN
IF (OLD.DISEASE_ID <> NEW.DISEASE_ID) OR (OLD.SYMPTOM_ID <> NEW.SYMPTOM_ID) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Disease and/or symptom cannot be altered.';
END IF;
IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
INSERT INTO DISEASE_SYMPTOMS_MOD_DET 
SET 
DISEASE_SYMPTOM_ID = OLD.DISEASE_SYMPTOM_ID,
COLUMN_NAME = 'STATUS_ID',
COLUMN_VALUE = OLD.STATUS_ID,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `DISEASE_SYMPTOMS_MOD_DET`
--

DROP TABLE IF EXISTS `DISEASE_SYMPTOMS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `DISEASE_SYMPTOMS_MOD_DET` (
`DISEASE_SYMPTOM_MOD_DET_ID` int(10) unsigned NOT NULL,
  `DISEASE_SYMPTOM_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for disease symptoms' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `DISEASE_TYPE_MASTER`
--

DROP TABLE IF EXISTS `DISEASE_TYPE_MASTER`;
CREATE TABLE IF NOT EXISTS `DISEASE_TYPE_MASTER` (
`DISEASE_TYPE_ID` int(10) unsigned NOT NULL,
  `DISEASE_TYPE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for disease types' AUTO_INCREMENT=1 ;

--
-- Triggers `DISEASE_TYPE_MASTER`
--
DROP TRIGGER IF EXISTS `DISEASE_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `DISEASE_TYPE_BU` BEFORE UPDATE ON `disease_type_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM DISEASE_TYPE_MASTER WHERE DISEASE_TYPE = NEW.DISEASE_TYPE AND NEW.DISEASE_TYPE <> OLD.DISEASE_TYPE) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical disease type already exists, please revise.';
    END IF;
    IF OLD.DISEASE_TYPE <> NEW.DISEASE_TYPE THEN
        INSERT INTO DISEASE_TYPE_MOD_DET 
        SET 
        DISEASE_TYPE_ID = OLD.DISEASE_TYPE_ID,
        COLUMN_NAME = 'DISEASE_TYPE',
        COLUMN_VALUE = OLD.DISEASE_TYPE,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO DISEASE_TYPE_MOD_DET 
        SET 
        DISEASE_TYPE_ID = OLD.DISEASE_TYPE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `DISEASE_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `DISEASE_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `DISEASE_TYPE_MOD_DET` (
`DISEASE_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `DISEASE_TYPE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of diseases' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `EMAILS`
--

DROP TABLE IF EXISTS `EMAILS`;
CREATE TABLE IF NOT EXISTS `EMAILS` (
`EMAIL_ID` int(10) unsigned NOT NULL,
  `EMAIL_TEMPLATE_ID` int(10) unsigned DEFAULT NULL,
  `INSTANCE_ID` varchar(300) COLLATE latin1_general_cs NOT NULL,
  `CONTENT` text COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `SENT_DATE` datetime NOT NULL,
  `MODULE_INFO` varchar(200) COLLATE latin1_general_cs DEFAULT NULL,
  `PRIORITY_ID` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Emails' AUTO_INCREMENT=1 ;

--
-- Triggers `EMAILS`
--
DROP TRIGGER IF EXISTS `EMAIL_BU`;
DELIMITER //
CREATE TRIGGER `EMAIL_BU` BEFORE UPDATE ON `emails`
 FOR EACH ROW BEGIN
    IF (OLD.EMAIL_ID <> NEW.EMAIL_ID) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Email identifier cannot be altered.';
    END IF;
    IF OLD.EMAIL_TEMPLATE_ID <> NEW.EMAIL_TEMPLATE_ID THEN
        INSERT INTO EMAIL_MOD_DET 
        SET 
        EMAIL_ID = OLD.EMAIL_ID,
        COLUMN_NAME = 'EMAIL_TEMPLATE_ID',
        COLUMN_VALUE = OLD.EMAIL_TEMPLATE_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO EMAIL_MOD_DET 
        SET 
        EMAIL_ID = OLD.EMAIL_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_ON <> NEW.CREATED_ON THEN
        INSERT INTO EMAIL_MOD_DET 
        SET 
        EMAIL_ID = OLD.EMAIL_ID,
        COLUMN_NAME = 'CREATED_ON',
        COLUMN_VALUE = DATE_FORMAT(OLD.CREATED_ON,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.SENT_DATE <> NEW.SENT_DATE THEN
        INSERT INTO EMAIL_MOD_DET 
        SET 
        EMAIL_ID = OLD.EMAIL_ID,
        COLUMN_NAME = 'SENT_DATE',
        COLUMN_VALUE = DATE_FORMAT(OLD.SENT_DATE,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.INSTANCE_ID <> NEW.INSTANCE_ID THEN
        INSERT INTO EMAIL_MOD_DET 
        SET 
        EMAIL_ID = OLD.EMAIL_ID,
        COLUMN_NAME = 'INSTANCE_ID',
        COLUMN_VALUE = OLD.INSTANCE_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CONTENT <> NEW.CONTENT THEN
        INSERT INTO EMAIL_MOD_DET 
        SET 
        EMAIL_ID = OLD.EMAIL_ID,
        COLUMN_NAME = 'CONTENT',
        COLUMN_VALUE = OLD.CONTENT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_BY <> NEW.CREATED_BY THEN
        INSERT INTO EMAIL_MOD_DET 
        SET 
        EMAIL_ID = OLD.EMAIL_ID,
        COLUMN_NAME = 'CREATED_BY',
        COLUMN_VALUE = OLD.CREATED_BY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.MODULE_INFO <> NEW.MODULE_INFO THEN
        INSERT INTO EMAIL_MOD_DET 
        SET 
        EMAIL_ID = OLD.EMAIL_ID,
        COLUMN_NAME = 'MODULE_INFO',
        COLUMN_VALUE = OLD.MODULE_INFO,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.PRIORITY_ID <> NEW.PRIORITY_ID THEN
        INSERT INTO EMAIL_MOD_DET 
        SET 
        EMAIL_ID = OLD.EMAIL_ID,
        COLUMN_NAME = 'PRIORITY_ID',
        COLUMN_VALUE = OLD.PRIORITY_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `EMAIL_ATTRIBUTES`
--

DROP TABLE IF EXISTS `EMAIL_ATTRIBUTES`;
CREATE TABLE IF NOT EXISTS `EMAIL_ATTRIBUTES` (
`EMAIL_ATTRIBUTE_ID` int(10) unsigned NOT NULL,
  `EMAIL_ID` int(10) unsigned NOT NULL,
  `ATTRIBUTE_ID` int(10) unsigned NOT NULL,
  `ATTRIBUTE_VALUE` varchar(300) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Email attributes' AUTO_INCREMENT=1 ;

--
-- Triggers `EMAIL_ATTRIBUTES`
--
DROP TRIGGER IF EXISTS `EMAIL_ATTRIBUTES_BU`;
DELIMITER //
CREATE TRIGGER `EMAIL_ATTRIBUTES_BU` BEFORE UPDATE ON `email_attributes`
 FOR EACH ROW BEGIN
    IF (OLD.EMAIL_ID <> NEW.EMAIL_ID) OR (OLD.ATTRIBUTE_ID <> NEW.ATTRIBUTE_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Email or attribute identifier cannot be altered.';
    END IF;
    IF OLD.ATTRIBUTE_VALUE <> NEW.ATTRIBUTE_VALUE THEN
        INSERT INTO EMAIL_ATTRIBUTES_MOD_DET 
        SET 
        EMAIL_ATTRIBUTE_ID = OLD.EMAIL_ATTRIBUTE_ID,
        COLUMN_NAME = 'ATTRIBUTE_VALUE',
        COLUMN_VALUE = OLD.ATTRIBUTE_VALUE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO EMAIL_ATTRIBUTES_MOD_DET 
        SET 
        EMAIL_ATTRIBUTE_ID = OLD.EMAIL_ATTRIBUTE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `EMAIL_ATTRIBUTES_MOD_DET`
--

DROP TABLE IF EXISTS `EMAIL_ATTRIBUTES_MOD_DET`;
CREATE TABLE IF NOT EXISTS `EMAIL_ATTRIBUTES_MOD_DET` (
`EMAIL_ATTRIBUTES_MOD_DET_ID` int(10) unsigned NOT NULL,
  `EMAIL_ATTRIBUTE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(300) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of email attributes' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `EMAIL_HISTORY`
--

DROP TABLE IF EXISTS `EMAIL_HISTORY`;
CREATE TABLE IF NOT EXISTS `EMAIL_HISTORY` (
`EMAIL_HISTORY_ID` int(10) unsigned NOT NULL,
  `EMAIL_TEMPLATE_ID` int(10) unsigned DEFAULT NULL,
  `INSTANCE_ID` varchar(300) COLLATE latin1_general_cs NOT NULL,
  `CONTENT` text COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `SENT_DATE` datetime NOT NULL,
  `MODULE_INFO` varchar(200) COLLATE latin1_general_cs DEFAULT NULL,
  `PRIORITY_ID` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs AUTO_INCREMENT=1 ;

--
-- Triggers `EMAIL_HISTORY`
--
DROP TRIGGER IF EXISTS `EMAIL_HISTORY_BU`;
DELIMITER //
CREATE TRIGGER `EMAIL_HISTORY_BU` BEFORE UPDATE ON `email_history`
 FOR EACH ROW BEGIN
    IF (OLD.EMAIL_HISTORY_ID <> NEW.EMAIL_HISTORY_ID) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Email history identifier cannot be altered.';
    END IF;
    IF OLD.EMAIL_TEMPLATE_ID <> NEW.EMAIL_TEMPLATE_ID THEN
        INSERT INTO EMAIL_HISTORY_MOD_DET 
        SET 
        EMAIL_HISTORY_ID = OLD.EMAIL_HISTORY_ID,
        COLUMN_NAME = 'EMAIL_TEMPLATE_ID',
        COLUMN_VALUE = OLD.EMAIL_TEMPLATE_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO EMAIL_HISTORY_MOD_DET 
        SET 
        EMAIL_HISTORY_ID = OLD.EMAIL_HISTORY_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_ON <> NEW.CREATED_ON THEN
        INSERT INTO EMAIL_HISTORY_MOD_DET 
        SET 
        EMAIL_HISTORY_ID = OLD.EMAIL_HISTORY_ID,
        COLUMN_NAME = 'CREATED_ON',
        COLUMN_VALUE = DATE_FORMAT(OLD.CREATED_ON,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.SENT_DATE <> NEW.SENT_DATE THEN
        INSERT INTO EMAIL_HISTORY_MOD_DET 
        SET 
        EMAIL_HISTORY_ID = OLD.EMAIL_HISTORY_ID,
        COLUMN_NAME = 'SENT_DATE',
        COLUMN_VALUE = DATE_FORMAT(OLD.SENT_DATE,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.INSTANCE_ID <> NEW.INSTANCE_ID THEN
        INSERT INTO EMAIL_HISTORY_MOD_DET 
        SET 
        EMAIL_HISTORY_ID = OLD.EMAIL_HISTORY_ID,
        COLUMN_NAME = 'INSTANCE_ID',
        COLUMN_VALUE = OLD.INSTANCE_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CONTENT <> NEW.CONTENT THEN
        INSERT INTO EMAIL_HISTORY_MOD_DET 
        SET 
        EMAIL_HISTORY_ID = OLD.EMAIL_HISTORY_ID,
        COLUMN_NAME = 'CONTENT',
        COLUMN_VALUE = OLD.CONTENT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_BY <> NEW.CREATED_BY THEN
        INSERT INTO EMAIL_HISTORY_MOD_DET 
        SET 
        EMAIL_HISTORY_ID = OLD.EMAIL_HISTORY_ID,
        COLUMN_NAME = 'CREATED_BY',
        COLUMN_VALUE = OLD.CREATED_BY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.MODULE_INFO <> NEW.MODULE_INFO THEN
        INSERT INTO EMAIL_HISTORY_MOD_DET 
        SET 
        EMAIL_HISTORY_ID = OLD.EMAIL_HISTORY_ID,
        COLUMN_NAME = 'MODULE_INFO',
        COLUMN_VALUE = OLD.MODULE_INFO,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.PRIORITY_ID <> NEW.PRIORITY_ID THEN
        INSERT INTO EMAIL_HISTORY_MOD_DET 
        SET 
        EMAIL_HISTORY_ID = OLD.EMAIL_HISTORY_ID,
        COLUMN_NAME = 'PRIORITY_ID',
        COLUMN_VALUE = OLD.PRIORITY_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `EMAIL_HISTORY_ATTRIBUTES`
--

DROP TABLE IF EXISTS `EMAIL_HISTORY_ATTRIBUTES`;
CREATE TABLE IF NOT EXISTS `EMAIL_HISTORY_ATTRIBUTES` (
`EMAIL_HISTORY_ATTRIBUTE_ID` int(10) unsigned NOT NULL,
  `EMAIL_HISTORY_ID` int(10) unsigned NOT NULL,
  `ATTRIBUTE_ID` int(10) unsigned NOT NULL,
  `ATTRIBUTE_VALUE` varchar(300) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs AUTO_INCREMENT=1 ;

--
-- Triggers `EMAIL_HISTORY_ATTRIBUTES`
--
DROP TRIGGER IF EXISTS `EMAIL_HISTORY_ATTRIBUTES_BU`;
DELIMITER //
CREATE TRIGGER `EMAIL_HISTORY_ATTRIBUTES_BU` BEFORE UPDATE ON `email_history_attributes`
 FOR EACH ROW BEGIN
    IF (OLD.EMAIL_HISTORY_ID <> NEW.EMAIL_HISTORY_ID) OR (OLD.ATTRIBUTE_ID <> NEW.ATTRIBUTE_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Email history or attribute identifier cannot be altered.';
    END IF;
    IF OLD.ATTRIBUTE_VALUE <> NEW.ATTRIBUTE_VALUE THEN
        INSERT INTO EMAIL_HISTORY_ATTRIBUTES_MOD_DET 
        SET 
        EMAIL_HISTORY_ATTRIBUTE_ID = OLD.EMAIL_HISTORY_ATTRIBUTE_ID,
        COLUMN_NAME = 'ATTRIBUTE_VALUE',
        COLUMN_VALUE = OLD.ATTRIBUTE_VALUE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO EMAIL_HISTORY_ATTRIBUTES_MOD_DET 
        SET 
        EMAIL_HISTORY_ATTRIBUTE_ID = OLD.EMAIL_HISTORY_ATTRIBUTE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `EMAIL_HISTORY_ATTRIBUTES_MOD_DET`
--

DROP TABLE IF EXISTS `EMAIL_HISTORY_ATTRIBUTES_MOD_DET`;
CREATE TABLE IF NOT EXISTS `EMAIL_HISTORY_ATTRIBUTES_MOD_DET` (
`EMAIL_HISTORY_ATTRIBUTES_MOD_DET_ID` int(10) unsigned NOT NULL,
  `EMAIL_HISTORY_ATTRIBUTE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(300) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `EMAIL_HISTORY_MOD_DET`
--

DROP TABLE IF EXISTS `EMAIL_HISTORY_MOD_DET`;
CREATE TABLE IF NOT EXISTS `EMAIL_HISTORY_MOD_DET` (
`EMAIL_HISTORY_MOD_DET_ID` int(10) unsigned NOT NULL,
  `EMAIL_HISTORY_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `EMAIL_MOD_DET`
--

DROP TABLE IF EXISTS `EMAIL_MOD_DET`;
CREATE TABLE IF NOT EXISTS `EMAIL_MOD_DET` (
`EMAIL_MOD_DET_ID` int(10) unsigned NOT NULL,
  `EMAIL_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Email modification history' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `EMAIL_PRIORITY_MASTER`
--

DROP TABLE IF EXISTS `EMAIL_PRIORITY_MASTER`;
CREATE TABLE IF NOT EXISTS `EMAIL_PRIORITY_MASTER` (
`EMAIL_PRIORITY_ID` int(10) unsigned NOT NULL,
  `EMAIL_PRIORITY_DESCR` varchar(50) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for email priority' AUTO_INCREMENT=1 ;

--
-- Triggers `EMAIL_PRIORITY_MASTER`
--
DROP TRIGGER IF EXISTS `EMAIL_PRIORITY_BU`;
DELIMITER //
CREATE TRIGGER `EMAIL_PRIORITY_BU` BEFORE UPDATE ON `email_priority_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM EMAIL_PRIORITY_MASTER WHERE EMAIL_PRIORITY_DESCR = NEW.EMAIL_PRIORITY_DESCR AND NEW.EMAIL_PRIORITY_DESCR <> OLD.EMAIL_PRIORITY_DESCR) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical priority already exists, please revise.';
    END IF;
    IF OLD.EMAIL_PRIORITY_DESCR <> NEW.EMAIL_PRIORITY_DESCR THEN
        INSERT INTO EMAIL_PRIORITY_MOD_DET 
        SET 
        EMAIL_PRIORITY_ID = OLD.EMAIL_PRIORITY_ID,
        COLUMN_NAME = 'EMAIL_PRIORITY_DESCR',
        COLUMN_VALUE = OLD.EMAIL_PRIORITY_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO EMAIL_PRIORITY_MOD_DET 
        SET 
        EMAIL_PRIORITY_ID = OLD.EMAIL_PRIORITY_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `EMAIL_PRIORITY_MOD_DET`
--

DROP TABLE IF EXISTS `EMAIL_PRIORITY_MOD_DET`;
CREATE TABLE IF NOT EXISTS `EMAIL_PRIORITY_MOD_DET` (
`EMAIL_PRIORITY_MOD_DET_ID` int(10) unsigned NOT NULL,
  `EMAIL_PRIORITY_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(50) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of email priority' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `EMAIL_TEMPLATES`
--

DROP TABLE IF EXISTS `EMAIL_TEMPLATES`;
CREATE TABLE IF NOT EXISTS `EMAIL_TEMPLATES` (
`TEMPLATE_ID` int(10) unsigned NOT NULL,
  `TEMPLATE_NAME` varchar(350) COLLATE latin1_general_cs NOT NULL,
  `TEMPLATE_SUBJECT` varchar(500) COLLATE latin1_general_cs DEFAULT NULL,
  `TEMPLATE_BODY` text COLLATE latin1_general_cs,
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Email templates' AUTO_INCREMENT=1 ;

--
-- Triggers `EMAIL_TEMPLATES`
--
DROP TRIGGER IF EXISTS `EMAIL_TEMPLATES_BU`;
DELIMITER //
CREATE TRIGGER `EMAIL_TEMPLATES_BU` BEFORE UPDATE ON `email_templates`
 FOR EACH ROW BEGIN
    IF OLD.TEMPLATE_ID <> NEW.TEMPLATE_ID THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Email template id cannot be altered.';
    END IF;
    IF EXISTS (SELECT 1 FROM EMAIL_TEMPLATES WHERE TEMPLATE_NAME = NEW.TEMPLATE_NAME AND NEW.TEMPLATE_NAME <> OLD.TEMPLATE_NAME) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate email template name exists, please revise.';
    END IF;
    IF OLD.TEMPLATE_NAME <> NEW.TEMPLATE_NAME THEN
        INSERT INTO EMAIL_TEMPLATES_MOD_DET 
        SET 
        TEMPLATE_ID = OLD.TEMPLATE_ID,
        COLUMN_NAME = 'TEMPLATE_NAME',
        COLUMN_VALUE = OLD.TEMPLATE_NAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.TEMPLATE_SUBJECT <> NEW.TEMPLATE_SUBJECT THEN
        INSERT INTO EMAIL_TEMPLATES_MOD_DET 
        SET 
        TEMPLATE_ID = OLD.TEMPLATE_ID,
        COLUMN_NAME = 'TEMPLATE_SUBJECT',
        COLUMN_VALUE = OLD.TEMPLATE_SUBJECT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.TEMPLATE_BODY <> NEW.TEMPLATE_BODY THEN
        INSERT INTO EMAIL_TEMPLATES_MOD_DET 
        SET 
        TEMPLATE_ID = OLD.TEMPLATE_ID,
        COLUMN_NAME = 'TEMPLATE_BODY',
        COLUMN_VALUE = OLD.TEMPLATE_BODY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_BY <> NEW.CREATED_BY THEN
        INSERT INTO EMAIL_TEMPLATES_MOD_DET 
        SET 
        TEMPLATE_ID = OLD.TEMPLATE_ID,
        COLUMN_NAME = 'CREATED_BY',
        COLUMN_VALUE = OLD.CREATED_BY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO EMAIL_TEMPLATES_MOD_DET 
        SET 
        TEMPLATE_ID = OLD.TEMPLATE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_ON <> NEW.CREATED_ON THEN
        INSERT INTO EMAIL_TEMPLATES_MOD_DET 
        SET 
        TEMPLATE_ID = OLD.TEMPLATE_ID,
        COLUMN_NAME = 'CREATED_ON',
        COLUMN_VALUE = DATE_FORMAT(OLD.CREATED_ON,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `EMAIL_TEMPLATES_MOD_DET`
--

DROP TABLE IF EXISTS `EMAIL_TEMPLATES_MOD_DET`;
CREATE TABLE IF NOT EXISTS `EMAIL_TEMPLATES_MOD_DET` (
`EMAIL_TEMPLATE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `TEMPLATE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Email template modification history' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `EVENTS`
--

DROP TABLE IF EXISTS `EVENTS`;
CREATE TABLE IF NOT EXISTS `EVENTS` (
`EVENT_ID` int(10) unsigned NOT NULL,
  `EVENT_NAME` varchar(250) COLLATE latin1_general_cs NOT NULL,
  `EVENT_DESCR` text COLLATE latin1_general_cs NOT NULL,
  `EVENT_TYPE_ID` int(10) unsigned NOT NULL,
  `COMMUNITY_ID` int(10) unsigned DEFAULT NULL,
  `GUEST_CAN_INVITE` tinyint(3) unsigned NOT NULL,
  `REPEAT_TYPE_ID` int(10) unsigned NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `START_DATE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `END_DATE` datetime DEFAULT CURRENT_TIMESTAMP,
  `VIRTUAL_EVENT` tinyint(3) unsigned NOT NULL,
  `ONLINE_EVENT_DETAILS` varchar(500) COLLATE latin1_general_cs NOT NULL,
  `PUBLISH_TYPE_ID` int(10) unsigned NOT NULL,
  `SECTION_TYPE_ID` int(10) unsigned NOT NULL,
  `SECTION_TEAM_ID` int(10) unsigned DEFAULT NULL COMMENT 'Section team id',
  `SECTION_COMMUNITY_ID` int(10) unsigned DEFAULT NULL COMMENT 'Section community id',
  `REPEAT_MODE_TYPE_ID` int(10) unsigned NOT NULL,
  `REPEAT_INTERVAL` tinyint(3) unsigned NOT NULL COMMENT '1 to 30',
  `REPEAT_BY_TYPE_ID` int(10) unsigned NOT NULL,
  `REPEAT_END_TYPE_ID` int(10) unsigned NOT NULL,
  `REPEAT_OCCURENCES` int(10) unsigned NOT NULL,
  `INVITED_COUNT` int(10) unsigned NOT NULL,
  `ATTENDING_COUNT` int(10) unsigned NOT NULL,
  `MAYBE_COUNT` int(10) unsigned NOT NULL,
  `NOT_ATTENDING_COUNT` int(10) unsigned NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `IS_SLIDESHOW_ENABLED` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs AUTO_INCREMENT=1 ;

--
-- Triggers `EVENTS`
--
DROP TRIGGER IF EXISTS `EVENT_BU`;
DELIMITER //
CREATE TRIGGER `EVENT_BU` BEFORE UPDATE ON `events`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM EVENTS WHERE EVENT_NAME = NEW.EVENT_NAME AND NEW.EVENT_NAME <> OLD.EVENT_NAME) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical event name exists, please revise.';
    END IF;
    IF OLD.EVENT_NAME <> NEW.EVENT_NAME THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'EVENT_NAME',
        COLUMN_VALUE = OLD.EVENT_NAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.EVENT_DESCR <> NEW.EVENT_DESCR THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'EVENT_DESCR',
        COLUMN_VALUE = OLD.EVENT_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.EVENT_TYPE_ID <> NEW.EVENT_TYPE_ID THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'EVENT_TYPE_ID',
        COLUMN_VALUE = OLD.EVENT_TYPE_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.COMMUNITY_ID <> NEW.COMMUNITY_ID THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'COMMUNITY_ID',
        COLUMN_VALUE = OLD.COMMUNITY_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.GUEST_CAN_INVITE <> NEW.GUEST_CAN_INVITE THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'GUEST_CAN_INVITE',
        COLUMN_VALUE = OLD.GUEST_CAN_INVITE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.REPEAT_TYPE_ID <> NEW.REPEAT_TYPE_ID THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'REPEAT_TYPE_ID',
        COLUMN_VALUE = OLD.REPEAT_TYPE_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_BY <> NEW.CREATED_BY THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'CREATED_BY',
        COLUMN_VALUE = OLD.CREATED_BY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_ON <> NEW.CREATED_ON THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'CREATED_ON',
        COLUMN_VALUE = DATE_FORMAT(OLD.CREATED_ON,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.START_DATE <> NEW.START_DATE THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'START_DATE',
        COLUMN_VALUE = DATE_FORMAT(OLD.START_DATE,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.END_DATE <> NEW.END_DATE THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'END_DATE',
        COLUMN_VALUE = DATE_FORMAT(OLD.END_DATE,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.VIRTUAL_EVENT <> NEW.VIRTUAL_EVENT THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'VIRTUAL_EVENT',
        COLUMN_VALUE = OLD.VIRTUAL_EVENT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.ONLINE_EVENT_DETAILS <> NEW.ONLINE_EVENT_DETAILS THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'ONLINE_EVENT_DETAILS',
        COLUMN_VALUE = OLD.ONLINE_EVENT_DETAILS,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.PUBLISH_TYPE_ID <> NEW.PUBLISH_TYPE_ID THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'PUBLISH_TYPE_ID',
        COLUMN_VALUE = OLD.PUBLISH_TYPE_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.SECTION_TYPE_ID <> NEW.SECTION_TYPE_ID THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'SECTION_TYPE_ID',
        COLUMN_VALUE = OLD.SECTION_TYPE_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.SECTION_TEAM_ID <> NEW.SECTION_TEAM_ID THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'SECTION_TEAM_ID',
        COLUMN_VALUE = OLD.SECTION_TEAM_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.SECTION_COMMUNITY_ID <> NEW.SECTION_COMMUNITY_ID THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'SECTION_COMMUNITY_ID',
        COLUMN_VALUE = OLD.SECTION_COMMUNITY_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.REPEAT_MODE_TYPE_ID <> NEW.REPEAT_MODE_TYPE_ID THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'REPEAT_MODE_TYPE_ID',
        COLUMN_VALUE = OLD.REPEAT_MODE_TYPE_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.REPEAT_END_TYPE_ID <> NEW.REPEAT_END_TYPE_ID THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'REPEAT_END_TYPE_ID',
        COLUMN_VALUE = OLD.REPEAT_END_TYPE_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.REPEAT_INTERVAL <> NEW.REPEAT_INTERVAL THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'REPEAT_INTERVAL',
        COLUMN_VALUE = OLD.REPEAT_INTERVAL,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.REPEAT_BY_TYPE_ID <> NEW.REPEAT_BY_TYPE_ID THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'REPEAT_BY_TYPE_ID',
        COLUMN_VALUE = OLD.REPEAT_BY_TYPE_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.REPEAT_OCCURENCES <> NEW.REPEAT_OCCURENCES THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'REPEAT_OCCURENCES',
        COLUMN_VALUE = OLD.REPEAT_OCCURENCES,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.INVITED_COUNT <> NEW.INVITED_COUNT THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'INVITED_COUNT',
        COLUMN_VALUE = OLD.INVITED_COUNT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.ATTENDING_COUNT <> NEW.ATTENDING_COUNT THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'ATTENDING_COUNT',
        COLUMN_VALUE = OLD.ATTENDING_COUNT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.MAYBE_COUNT <> NEW.MAYBE_COUNT THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'MAYBE_COUNT',
        COLUMN_VALUE = OLD.MAYBE_COUNT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.NOT_ATTENDING_COUNT <> NEW.NOT_ATTENDING_COUNT THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'NOT_ATTENDING_COUNT',
        COLUMN_VALUE = OLD.NOT_ATTENDING_COUNT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.IS_SLIDESHOW_ENABLED <> NEW.IS_SLIDESHOW_ENABLED THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'IS_SLIDESHOW_ENABLED',
        COLUMN_VALUE = OLD.IS_SLIDESHOW_ENABLED,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO EVENT_MOD_DET 
        SET 
        EVENT_ID = OLD.EVENT_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `EVENT_ATTRIBUTES`
--

DROP TABLE IF EXISTS `EVENT_ATTRIBUTES`;
CREATE TABLE IF NOT EXISTS `EVENT_ATTRIBUTES` (
`EVENT_ATTRIBUTE_ID` int(10) unsigned NOT NULL,
  `EVENT_ID` int(10) unsigned NOT NULL,
  `ATTRIBUTE_ID` int(10) unsigned NOT NULL,
  `ATTRIBUTE_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL,
  `EFF_DATE_FROM` datetime DEFAULT CURRENT_TIMESTAMP,
  `EFF_DATE_TO` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Event attributes' AUTO_INCREMENT=1 ;

--
-- Triggers `EVENT_ATTRIBUTES`
--
DROP TRIGGER IF EXISTS `EVENT_ATTRIBUTES_BU`;
DELIMITER //
CREATE TRIGGER `EVENT_ATTRIBUTES_BU` BEFORE UPDATE ON `event_attributes`
 FOR EACH ROW BEGIN
    IF (OLD.EVENT_ID <> NEW.EVENT_ID) OR (OLD.ATTRIBUTE_ID <> NEW.ATTRIBUTE_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Event or attribute type cannot be altered.';
    END IF;
    IF OLD.ATTRIBUTE_VALUE <> NEW.ATTRIBUTE_VALUE THEN
        INSERT INTO EVENT_ATTRIBUTES_MOD_DET 
        SET 
        EVENT_ATTRIBUTE_ID = OLD.EVENT_ATTRIBUTE_ID,
        COLUMN_NAME = 'ATTRIBUTE_VALUE',
        COLUMN_VALUE = OLD.ATTRIBUTE_VALUE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO EVENT_ATTRIBUTES_MOD_DET 
        SET 
        EVENT_ATTRIBUTE_ID = OLD.EVENT_ATTRIBUTE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.EFF_DATE_FROM <> NEW.EFF_DATE_FROM THEN
        INSERT INTO EVENT_ATTRIBUTES_MOD_DET 
        SET 
        EVENT_ATTRIBUTE_ID = OLD.EVENT_ATTRIBUTE_ID,
        COLUMN_NAME = 'EFF_DATE_FROM',
        COLUMN_VALUE = DATE_FORMAT(OLD.EFF_DATE_FROM,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.EFF_DATE_TO <> NEW.EFF_DATE_TO THEN
        INSERT INTO EVENT_ATTRIBUTES_MOD_DET 
        SET 
        EVENT_ATTRIBUTE_ID = OLD.EVENT_ATTRIBUTE_ID,
        COLUMN_NAME = 'EFF_DATE_TO',
        COLUMN_VALUE = DATE_FORMAT(OLD.EFF_DATE_TO,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `EVENT_ATTRIBUTES_MOD_DET`
--

DROP TABLE IF EXISTS `EVENT_ATTRIBUTES_MOD_DET`;
CREATE TABLE IF NOT EXISTS `EVENT_ATTRIBUTES_MOD_DET` (
`EVENT_ATTRIBUTE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `EVENT_ATTRIBUTE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of event attributes' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `EVENT_DISEASES`
--

DROP TABLE IF EXISTS `EVENT_DISEASES`;
CREATE TABLE IF NOT EXISTS `EVENT_DISEASES` (
`EVENT_DISEASE_ID` int(10) unsigned NOT NULL,
  `EVENT_ID` int(10) unsigned NOT NULL,
  `DISEASE_ID` int(10) unsigned NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Event diseases' AUTO_INCREMENT=1 ;

--
-- Triggers `EVENT_DISEASES`
--
DROP TRIGGER IF EXISTS `EVENT_DISEASES_BU`;
DELIMITER //
CREATE TRIGGER `EVENT_DISEASES_BU` BEFORE UPDATE ON `event_diseases`
 FOR EACH ROW BEGIN
    IF (OLD.EVENT_ID <> NEW.EVENT_ID) OR (OLD.DISEASE_ID <> NEW.DISEASE_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Event or disease cannot be altered.';
    END IF;
    IF OLD.CREATED_BY <> NEW.CREATED_BY THEN
        INSERT INTO EVENT_DISEASES_MOD_DET 
        SET 
        EVENT_DISEASE_ID = OLD.EVENT_DISEASE_ID,
        COLUMN_NAME = 'CREATED_BY',
        COLUMN_VALUE = OLD.CREATED_BY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO EVENT_DISEASES_MOD_DET 
        SET 
        EVENT_DISEASE_ID = OLD.EVENT_DISEASE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_ON <> NEW.CREATED_ON THEN
        INSERT INTO EVENT_DISEASES_MOD_DET 
        SET 
        EVENT_DISEASE_ID = OLD.EVENT_DISEASE_ID,
        COLUMN_NAME = 'CREATED_ON',
        COLUMN_VALUE = DATE_FORMAT(OLD.CREATED_ON,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `EVENT_DISEASES_MOD_DET`
--

DROP TABLE IF EXISTS `EVENT_DISEASES_MOD_DET`;
CREATE TABLE IF NOT EXISTS `EVENT_DISEASES_MOD_DET` (
`EVENT_DISEASE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `EVENT_DISEASE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT NULL,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of event diseases' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `EVENT_MEMBERS`
--

DROP TABLE IF EXISTS `EVENT_MEMBERS`;
CREATE TABLE IF NOT EXISTS `EVENT_MEMBERS` (
`EVENT_MEMBER_ID` int(10) unsigned NOT NULL,
  `EVENT_ID` int(10) unsigned NOT NULL,
  `MEMBER_ID` int(10) unsigned NOT NULL,
  `MEMBER_ROLE_ID` int(11) NOT NULL,
  `INVITED_BY` int(10) unsigned DEFAULT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `CREATED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Event members' AUTO_INCREMENT=1 ;

--
-- Triggers `EVENT_MEMBERS`
--
DROP TRIGGER IF EXISTS `EVENT_MEMBERS_BU`;
DELIMITER //
CREATE TRIGGER `EVENT_MEMBERS_BU` BEFORE UPDATE ON `event_members`
 FOR EACH ROW BEGIN
    IF (OLD.EVENT_ID <> NEW.EVENT_ID) OR (OLD.MEMBER_ID <> NEW.MEMBER_ID) OR (OLD.MEMBER_ROLE_ID <> NEW.MEMBER_ROLE_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Event or member or member role cannot be altered.';
    END IF;
    IF OLD.INVITED_BY <> NEW.INVITED_BY THEN
        INSERT INTO EVENT_MEMBER_MOD_DET 
        SET 
        EVENT_MEMBER_ID = OLD.EVENT_MEMBER_ID,
        COLUMN_NAME = 'INVITED_BY',
        COLUMN_VALUE = OLD.INVITED_BY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_BY <> NEW.CREATED_BY THEN
        INSERT INTO EVENT_MEMBER_MOD_DET 
        SET 
        EVENT_MEMBER_ID = OLD.EVENT_MEMBER_ID,
        COLUMN_NAME = 'CREATED_BY',
        COLUMN_VALUE = OLD.CREATED_BY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO EVENT_MEMBER_MOD_DET 
        SET 
        EVENT_MEMBER_ID = OLD.EVENT_MEMBER_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_ON <> NEW.CREATED_ON THEN
        INSERT INTO EVENT_MEMBER_MOD_DET 
        SET 
        EVENT_MEMBER_ID = OLD.EVENT_MEMBER_ID,
        COLUMN_NAME = 'CREATED_ON',
        COLUMN_VALUE = DATE_FORMAT(OLD.CREATED_ON,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `EVENT_MEMBERS_MOD_DET`
--

DROP TABLE IF EXISTS `EVENT_MEMBERS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `EVENT_MEMBERS_MOD_DET` (
`EVENT_MEMBER_MOD_DET_ID` int(10) unsigned NOT NULL,
  `EVENT_MEMBER_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of event members' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `EVENT_MOD_DET`
--

DROP TABLE IF EXISTS `EVENT_MOD_DET`;
CREATE TABLE IF NOT EXISTS `EVENT_MOD_DET` (
`EVENT_MOD_DET_ID` int(10) unsigned NOT NULL,
  `EVENT_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of events' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `EVENT_TYPE_MASTER`
--

DROP TABLE IF EXISTS `EVENT_TYPE_MASTER`;
CREATE TABLE IF NOT EXISTS `EVENT_TYPE_MASTER` (
`EVENT_TYPE_ID` int(10) unsigned NOT NULL,
  `EVENT_TYPE_DESCR` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for event types' AUTO_INCREMENT=1 ;

--
-- Triggers `EVENT_TYPE_MASTER`
--
DROP TRIGGER IF EXISTS `EVENT_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `EVENT_TYPE_BU` BEFORE UPDATE ON `event_type_master`
 FOR EACH ROW BEGIN
	IF EXISTS (SELECT 1 FROM EVENT_TYPE_MASTER WHERE EVENT_TYPE_DESCR = NEW.EVENT_TYPE_DESCR AND NEW.EVENT_TYPE_DESCR <> OLD.EVENT_TYPE_DESCR) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical event type exists, please revise.';
    END IF;
    IF NEW.EVENT_TYPE_DESCR <> OLD.EVENT_TYPE_DESCR THEN
        INSERT INTO EVENT_TYPE_MOD_DET 
        SET 
        EVENT_TYPE_ID = OLD.EVENT_TYPE_ID,
        COLUMN_NAME = 'EVENT_TYPE_DESCR',
        COLUMN_VALUE = OLD.EVENT_TYPE_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF NEW.STATUS_ID <> OLD.STATUS_ID THEN
        INSERT INTO EVENT_TYPE_MOD_DET 
        SET 
        EVENT_TYPE_ID = OLD.EVENT_TYPE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `EVENT_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `EVENT_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `EVENT_TYPE_MOD_DET` (
`EVENT_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `EVENT_TYPE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of event types' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `FOLLOWING_PAGES`
--

DROP TABLE IF EXISTS `FOLLOWING_PAGES`;
CREATE TABLE IF NOT EXISTS `FOLLOWING_PAGES` (
`FOLLOWING_PAGE_ID` int(10) unsigned NOT NULL,
  `PAGE_ID` int(10) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `NOTIFICATION_REQUESTED` int(10) unsigned DEFAULT '1' COMMENT '1: Yes 0: No',
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='List of pages being followed by an user' AUTO_INCREMENT=1 ;

--
-- Triggers `FOLLOWING_PAGES`
--
DROP TRIGGER IF EXISTS `FOLLOWING_PAGES_BU`;
DELIMITER //
CREATE TRIGGER `FOLLOWING_PAGES_BU` BEFORE UPDATE ON `following_pages`
 FOR EACH ROW BEGIN
	IF (OLD.PAGE_ID <> NEW.PAGE_ID) OR (OLD.USER_ID <> NEW.USER_ID) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'User or page cannot be altered.';
    END IF;
    IF OLD.CREATED_BY <> NEW.CREATED_BY THEN
        INSERT INTO FOLLOWING_PAGES_MOD_DET 
        SET 
        FOLLOWING_PAGE_ID = OLD.FOLLOWING_PAGE_ID,
        COLUMN_NAME = 'CREATED_BY',
        COLUMN_VALUE = OLD.CREATED_BY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.NOTIFICATION_REQUESTED <> NEW.NOTIFICATION_REQUESTED THEN
        INSERT INTO FOLLOWING_PAGES_MOD_DET 
        SET 
        FOLLOWING_PAGE_ID = OLD.FOLLOWING_PAGE_ID,
        COLUMN_NAME = 'NOTIFICATION_REQUESTED',
        COLUMN_VALUE = OLD.NOTIFICATION_REQUESTED,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO FOLLOWING_PAGES_MOD_DET 
        SET 
        FOLLOWING_PAGE_ID = OLD.FOLLOWING_PAGE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_ON <> NEW.CREATED_ON THEN
        INSERT INTO FOLLOWING_PAGES_MOD_DET 
        SET 
        FOLLOWING_PAGE_ID = OLD.FOLLOWING_PAGE_ID,
        COLUMN_NAME = 'CREATED_ON',
        COLUMN_VALUE = DATE_FORMAT(OLD.CREATED_ON,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `FOLLOWING_PAGES_MOD_DET`
--

DROP TABLE IF EXISTS `FOLLOWING_PAGES_MOD_DET`;
CREATE TABLE IF NOT EXISTS `FOLLOWING_PAGES_MOD_DET` (
`FOLLOWING_PAGE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `FOLLOWING_PAGE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of pages followed by users' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `HEALTH_CONDITION_GROUPS`
--

DROP TABLE IF EXISTS `HEALTH_CONDITION_GROUPS`;
CREATE TABLE IF NOT EXISTS `HEALTH_CONDITION_GROUPS` (
`HEALTH_CONDITION_GROUP_ID` int(11) NOT NULL,
  `HEALTH_CONDITION_GROUP_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='User health condition groups' AUTO_INCREMENT=1 ;

--
-- Triggers `HEALTH_CONDITION_GROUPS`
--
DROP TRIGGER IF EXISTS `HEALTH_COND_GROUP_BU`;
DELIMITER //
CREATE TRIGGER `HEALTH_COND_GROUP_BU` BEFORE UPDATE ON `health_condition_groups`
 FOR EACH ROW BEGIN
    IF (SELECT 1 FROM HEALTH_CONDITION_GROUPS WHERE HEALTH_CONDITION_GROUP_DESCR = NEW.HEALTH_CONDITION_GROUP_DESCR AND NEW.HEALTH_CONDITION_GROUP_DESCR <> OLD.HEALTH_CONDITION_GROUP_DESCR) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical health condition group already exists, please revise.';
    END IF;
    IF OLD.HEALTH_CONDITION_GROUP_DESCR <> NEW.HEALTH_CONDITION_GROUP_DESCR THEN
        INSERT INTO HEALTH_COND_GROUP_MOD_DET 
        SET 
        HEALTH_CONDITION_GROUP_ID = OLD.HEALTH_CONDITION_GROUP_ID,
        COLUMN_NAME = 'HEALTH_CONDITION_GROUP_DESCR',
        COLUMN_VALUE = OLD.HEALTH_CONDITION_GROUP_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO HEALTH_COND_GROUP_MOD_DET 
        SET 
        HEALTH_CONDITION_GROUP_ID = OLD.HEALTH_CONDITION_GROUP_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `HEALTH_CONDITION_MASTER`
--

DROP TABLE IF EXISTS `HEALTH_CONDITION_MASTER`;
CREATE TABLE IF NOT EXISTS `HEALTH_CONDITION_MASTER` (
`HEALTH_CONDITION_ID` int(11) NOT NULL,
  `HEALTH_CONDITION_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `HEALTH_CONDITION_GROUP_ID` int(11) NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for health conditions' AUTO_INCREMENT=1 ;

--
-- Triggers `HEALTH_CONDITION_MASTER`
--
DROP TRIGGER IF EXISTS `HEALTH_CONDITION_BU`;
DELIMITER //
CREATE TRIGGER `HEALTH_CONDITION_BU` BEFORE UPDATE ON `health_condition_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM HEALTH_CONDITION_MASTER WHERE HEALTH_CONDITION_GROUP_ID = NEW.HEALTH_CONDITION_GROUP_ID AND HEALTH_CONDITION_DESCR = OLD.HEALTH_CONDITION_DESCR AND NEW.HEALTH_CONDITION_GROUP_ID <> OLD.HEALTH_CONDITION_GROUP_ID) OR EXISTS (SELECT 1 FROM HEALTH_CONDITION_MASTER WHERE HEALTH_CONDITION_GROUP_ID = OLD.HEALTH_CONDITION_GROUP_ID AND HEALTH_CONDITION_DESCR = NEW.HEALTH_CONDITION_DESCR AND NEW.HEALTH_CONDITION_DESCR <> OLD.HEALTH_CONDITION_DESCR) OR EXISTS (SELECT 1 FROM HEALTH_CONDITION_MASTER WHERE HEALTH_CONDITION_GROUP_ID = NEW.HEALTH_CONDITION_GROUP_ID AND HEALTH_CONDITION_DESCR = NEW.HEALTH_CONDITION_DESCR AND NEW.HEALTH_CONDITION_GROUP_ID <> OLD.HEALTH_CONDITION_GROUP_ID AND NEW.HEALTH_CONDITION_DESCR <> OLD.HEALTH_CONDITION_DESCR) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical health condition for the same condition type exists, please revise.';
    END IF;
    IF OLD.HEALTH_CONDITION_DESCR <> NEW.HEALTH_CONDITION_DESCR THEN
        INSERT INTO HEALTH_CONDITION_MOD_DET 
        SET 
        HEALTH_CONDITION_ID = OLD.HEALTH_CONDITION_ID,
        COLUMN_NAME = 'HEALTH_CONDITION_DESCR',
        COLUMN_VALUE = OLD.HEALTH_CONDITION_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.HEALTH_CONDITION_GROUP_ID <> NEW.HEALTH_CONDITION_GROUP_ID THEN
        INSERT INTO HEALTH_CONDITION_MOD_DET 
        SET 
        HEALTH_CONDITION_ID = OLD.HEALTH_CONDITION_ID,
        COLUMN_NAME = 'HEALTH_CONDITION_GROUP_ID',
        COLUMN_VALUE = OLD.HEALTH_CONDITION_GROUP_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO HEALTH_CONDITION_MOD_DET 
        SET 
        HEALTH_CONDITION_ID = OLD.HEALTH_CONDITION_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `HEALTH_CONDITION_MOD_DET`
--

DROP TABLE IF EXISTS `HEALTH_CONDITION_MOD_DET`;
CREATE TABLE IF NOT EXISTS `HEALTH_CONDITION_MOD_DET` (
`HEALTH_CONDITION_MOD_DET_ID` int(10) unsigned NOT NULL,
  `HEALTH_CONDITION_ID` int(10) NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of health condition' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `HEALTH_COND_GROUP_MOD_DET`
--

DROP TABLE IF EXISTS `HEALTH_COND_GROUP_MOD_DET`;
CREATE TABLE IF NOT EXISTS `HEALTH_COND_GROUP_MOD_DET` (
`HEALTH_COND_GROUP_MOD_DET_ID` int(10) unsigned NOT NULL,
  `HEALTH_CONDITION_GROUP_ID` int(10) NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details for health condition groups' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `INVITED_USERS`
--

DROP TABLE IF EXISTS `INVITED_USERS`;
CREATE TABLE IF NOT EXISTS `INVITED_USERS` (
`INVITED_USER_ID` int(10) unsigned NOT NULL,
  `INVITED_USER_EMAIL` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `INVITED_BY` int(10) unsigned NOT NULL,
  `INVITED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Last invited on',
  `JOINED_ON` datetime DEFAULT NULL,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all user invitations' AUTO_INCREMENT=1 ;

--
-- Triggers `INVITED_USERS`
--
DROP TRIGGER IF EXISTS `INVITED_USERS_BU`;
DELIMITER //
CREATE TRIGGER `INVITED_USERS_BU` BEFORE UPDATE ON `invited_users`
 FOR EACH ROW BEGIN
IF (OLD.INVITED_USER_EMAIL <> NEW.INVITED_USER_EMAIL) OR (OLD.INVITED_BY <> NEW.INVITED_BY) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Invitee email or sender's id cannot be altered.';
END IF;
IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
INSERT INTO INVITED_USERS_MOD_DET 
SET 
INVITED_USER_ID = OLD.INVITED_USER_ID,
COLUMN_NAME = 'STATUS_ID',
COLUMN_VALUE = OLD.STATUS_ID,
MODIFIED_BY = NEW.INVITED_BY;
END IF;
IF OLD.JOINED_ON <> NEW.INVITED_ON THEN
INSERT INTO INVITED_USERS_MOD_DET 
SET 
INVITED_USER_ID = OLD.INVITED_USER_ID,
COLUMN_NAME = 'INVITED_ON',
COLUMN_VALUE = DATE_FORMAT(OLD.INVITED_ON,'YYYYMMDDHH24MISS'),
MODIFIED_BY = NEW.INVITED_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `INVITED_USERS_MOD_DET`
--

DROP TABLE IF EXISTS `INVITED_USERS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `INVITED_USERS_MOD_DET` (
`INVITED_USERS_MOD_DET_ID` int(10) unsigned NOT NULL,
  `INVITED_USER_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for invited users' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `LANGUAGES`
--

DROP TABLE IF EXISTS `LANGUAGES`;
CREATE TABLE IF NOT EXISTS `LANGUAGES` (
`LANGUAGE_ID` int(10) unsigned NOT NULL,
  `LANGUAGE_ABBREV` varchar(10) COLLATE latin1_general_cs DEFAULT NULL,
  `LANGUAGE` varchar(100) COLLATE latin1_general_cs DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs AUTO_INCREMENT=2 ;

--
-- Triggers `LANGUAGES`
--
DROP TRIGGER IF EXISTS `LANGUAGE_BU`;
DELIMITER //
CREATE TRIGGER `LANGUAGE_BU` BEFORE UPDATE ON `languages`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM LANGUAGES WHERE LANGUAGE = NEW.LANGUAGE AND NEW.LANGUAGE <> OLD.LANGUAGE) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical language name already exists, please revise.';
    END IF;
    IF OLD.LANGUAGE_ABBREV <> NEW.LANGUAGE_ABBREV THEN
        INSERT INTO LANGUAGE_MOD_DET 
        SET 
        LANGUAGE_ID = OLD.LANGUAGE_ID,
        COLUMN_NAME = 'LANGUAGE_ABBREV',
        COLUMN_VALUE = OLD.LANGUAGE_ABBREV,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.LANGUAGE <> NEW.LANGUAGE THEN
        INSERT INTO LANGUAGE_MOD_DET 
        SET 
        LANGUAGE_ID = OLD.LANGUAGE_ID,
        COLUMN_NAME = 'LANGUAGE',
        COLUMN_VALUE = OLD.LANGUAGE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO LANGUAGE_MOD_DET 
        SET 
        LANGUAGE_ID = OLD.LANGUAGE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `LANGUAGE_MOD_DET`
--

DROP TABLE IF EXISTS `LANGUAGE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `LANGUAGE_MOD_DET` (
`LANGUAGE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `LANGUAGE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of languages' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `MEDIA_TYPE_MASTER`
--

DROP TABLE IF EXISTS `MEDIA_TYPE_MASTER`;
CREATE TABLE IF NOT EXISTS `MEDIA_TYPE_MASTER` (
`MEDIA_TYPE_ID` int(10) unsigned NOT NULL,
  `MEDIA_TYPE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all media types' AUTO_INCREMENT=1 ;

--
-- Triggers `MEDIA_TYPE_MASTER`
--
DROP TRIGGER IF EXISTS `MEDIA_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `MEDIA_TYPE_BU` BEFORE UPDATE ON `media_type_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM MEDIA_TYPE_MASTER WHERE MEDIA_TYPE_DESCR = NEW.MEDIA_TYPE_DESCR AND NEW.MEDIA_TYPE_DESCR <> OLD.MEDIA_TYPE_DESCR) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical media type description exists, please revise.';
    END IF;
    IF OLD.MEDIA_TYPE_DESCR <> NEW.MEDIA_TYPE_DESCR THEN
        INSERT INTO MEDIA_TYPE_MOD_DET 
        SET 
        MEDIA_TYPE_ID = OLD.MEDIA_TYPE_ID,
        COLUMN_NAME = 'MEDIA_TYPE_DESCR',
        COLUMN_VALUE = OLD.MEDIA_TYPE_DESCR,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO MEDIA_TYPE_MOD_DET 
        SET 
        MEDIA_TYPE_ID = OLD.MEDIA_TYPE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `MEDIA_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `MEDIA_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `MEDIA_TYPE_MOD_DET` (
`MEDIA_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `MEDIA_TYPE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of media type' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `MESSAGE_RECIPIENT_ROLES`
--

DROP TABLE IF EXISTS `MESSAGE_RECIPIENT_ROLES`;
CREATE TABLE IF NOT EXISTS `MESSAGE_RECIPIENT_ROLES` (
`MESSAGE_RECIPIENT_ROLE_ID` int(10) unsigned NOT NULL,
  `ROLE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='List of message recipient roles - To, CC, BCC' AUTO_INCREMENT=5 ;

--
-- Triggers `MESSAGE_RECIPIENT_ROLES`
--
DROP TRIGGER IF EXISTS `MESSAGE_ROLE_BU`;
DELIMITER //
CREATE TRIGGER `MESSAGE_ROLE_BU` BEFORE UPDATE ON `message_recipient_roles`
 FOR EACH ROW BEGIN
    IF (SELECT COUNT(*) FROM MESSAGE_RECIPIENT_ROLES WHERE ROLE_DESCR = NEW.ROLE_DESCR AND NEW.ROLE_DESCR <> OLD.ROLE_DESCR) > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical recipient role exists, please revise.';
    END IF;
    IF OLD.ROLE_DESCR <> NEW.ROLE_DESCR THEN
        INSERT INTO MESSAGE_ROLE_MOD_DET 
        SET 
        MESSAGE_RECIPIENT_ROLE_ID = OLD.MESSAGE_RECIPIENT_ROLE_ID,
        COLUMN_NAME = 'ROLE_DESCR',
        COLUMN_VALUE = OLD.ROLE_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO MESSAGE_ROLE_MOD_DET 
        SET 
        MESSAGE_RECIPIENT_ROLE_ID = OLD.MESSAGE_RECIPIENT_ROLE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `MESSAGE_ROLE_MOD_DET`
--

DROP TABLE IF EXISTS `MESSAGE_ROLE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `MESSAGE_ROLE_MOD_DET` (
`MESSAGE_ROLE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `MESSAGE_RECIPIENT_ROLE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details for message recipient roles' AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `MODULE_MASTER`
--

DROP TABLE IF EXISTS `MODULE_MASTER`;
CREATE TABLE IF NOT EXISTS `MODULE_MASTER` (
`MODULE_ID` int(11) unsigned NOT NULL,
  `MODULE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all Health4Life user modules' AUTO_INCREMENT=1 ;

--
-- Triggers `MODULE_MASTER`
--
DROP TRIGGER IF EXISTS `MODULE_BU`;
DELIMITER //
CREATE TRIGGER `MODULE_BU` BEFORE UPDATE ON `module_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM MODULE_MASTER WHERE MODULE_DESCR = NEW.MODULE_DESCR AND NEW.MODULE_DESCR <> OLD.MODULE_DESCR) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical module description already exists, please revise.';
    END IF;
    IF OLD.MODULE_DESCR <> NEW.MODULE_DESCR THEN
        INSERT INTO MODULE_MOD_DET 
        SET 
        MODULE_ID = OLD.MODULE_ID,
        COLUMN_NAME = 'MODULE_DESCR',
        COLUMN_VALUE = OLD.MODULE_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO MODULE_MOD_DET 
        SET 
        MODULE_ID = OLD.MODULE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `MODULE_MOD_DET`
--

DROP TABLE IF EXISTS `MODULE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `MODULE_MOD_DET` (
`MODULE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `MODULE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of module details' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `MONTHS_MASTER`
--

DROP TABLE IF EXISTS `MONTHS_MASTER`;
CREATE TABLE IF NOT EXISTS `MONTHS_MASTER` (
`MONTH_ID` int(10) unsigned NOT NULL,
  `MONTH_NAME` varchar(20) COLLATE latin1_general_cs NOT NULL,
  `MONTH_ABBREV` varchar(5) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for months' AUTO_INCREMENT=13 ;

--
-- Triggers `MONTHS_MASTER`
--
DROP TRIGGER IF EXISTS `MONTH_BU`;
DELIMITER //
CREATE TRIGGER `MONTH_BU` BEFORE UPDATE ON `months_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM MONTHS_MASTER WHERE MONTH_NAME = NEW.MONTH_NAME AND NEW.MONTH_NAME <> OLD.MONTH_NAME) OR EXISTS (SELECT 1 FROM MONTHS_MASTER WHERE MONTH_ABBREV = NEW.MONTH_ABBREV AND NEW.MONTH_ABBREV <> OLD.MONTH_ABBREV) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical month name or abbreviation already exists, please revise.';
    END IF;
    IF OLD.MONTH_NAME <> NEW.MONTH_NAME THEN
        INSERT INTO MONTH_MOD_DET 
        SET 
        MONTH_ID = OLD.MONTH_ID,
        COLUMN_NAME = 'MONTH_NAME',
        COLUMN_VALUE = OLD.MONTH_NAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.MONTH_ABBREV <> NEW.MONTH_ABBREV THEN
        INSERT INTO MONTH_MOD_DET 
        SET 
        MONTH_ID = OLD.MONTH_ID,
        COLUMN_NAME = 'MONTH_ABBREV',
        COLUMN_VALUE = OLD.MONTH_ABBREV,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO MONTH_MOD_DET 
        SET 
        MONTH_ID = OLD.MONTH_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `MONTH_MOD_DET`
--

DROP TABLE IF EXISTS `MONTH_MOD_DET`;
CREATE TABLE IF NOT EXISTS `MONTH_MOD_DET` (
`MONTH_MOD_DET_ID` int(10) unsigned NOT NULL,
  `MONTH_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of months table' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `MOOD_MASTER`
--

DROP TABLE IF EXISTS `MOOD_MASTER`;
CREATE TABLE IF NOT EXISTS `MOOD_MASTER` (
`USER_MOOD_ID` int(11) NOT NULL,
  `USER_MOOD_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all user moods' AUTO_INCREMENT=1 ;

--
-- Triggers `MOOD_MASTER`
--
DROP TRIGGER IF EXISTS `MOOD_BU`;
DELIMITER //
CREATE TRIGGER `MOOD_BU` BEFORE UPDATE ON `mood_master`
 FOR EACH ROW BEGIN
	IF EXISTS (SELECT 1 FROM MOOD_MASTER WHERE USER_MOOD_DESCR = NEW.USER_MOOD_DESCR AND NEW.USER_MOOD_DESCR <> OLD.USER_MOOD_DESCR) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical mood description exists, please revise.';
    END IF;
    IF OLD.USER_MOOD_DESCR <> NEW.USER_MOOD_DESCR THEN
        INSERT INTO MOOD_MOD_DET 
        SET 
        MOOD_ID = OLD.USER_MOOD_ID,
        COLUMN_NAME = 'USER_MOOD_DESCR',
        COLUMN_VALUE = OLD.USER_MOOD_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO MOOD_MOD_DET 
        SET 
        MOOD_ID = OLD.USER_MOOD_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `MOOD_MOD_DET`
--

DROP TABLE IF EXISTS `MOOD_MOD_DET`;
CREATE TABLE IF NOT EXISTS `MOOD_MOD_DET` (
`MOOD_MOD_DET_ID` int(10) unsigned NOT NULL,
  `MOOD_ID` int(11) NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of mood master' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `MY_FRIENDS`
--

DROP TABLE IF EXISTS `MY_FRIENDS`;
CREATE TABLE IF NOT EXISTS `MY_FRIENDS` (
`MY_FRIEND_ID` int(10) unsigned NOT NULL,
  `MY_USER_ID` int(10) unsigned NOT NULL,
  `PENDING_REQUEST_COUNT` int(10) unsigned NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='My friends and pending friend requests' AUTO_INCREMENT=1 ;

--
-- Triggers `MY_FRIENDS`
--
DROP TRIGGER IF EXISTS `MY_FRIENDS_BU`;
DELIMITER //
CREATE TRIGGER `MY_FRIENDS_BU` BEFORE UPDATE ON `my_friends`
 FOR EACH ROW BEGIN
    IF (OLD.MY_USER_ID <> NEW.MY_USER_ID) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Owner's user id cannot be altered.';
    END IF;
    IF OLD.PENDING_REQUEST_COUNT <> NEW.PENDING_REQUEST_COUNT THEN
        INSERT INTO MY_FRIEND_REQUEST_COUNT 
        SET 
        MY_FRIEND_ID = OLD.MY_FRIEND_ID,
        COLUMN_NAME = 'PENDING_REQUEST_COUNT',
        COLUMN_VALUE = OLD.PENDING_REQUEST_COUNT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO MY_FRIEND_REQUEST_COUNT 
        SET 
        MY_FRIEND_ID = OLD.MY_FRIEND_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `MY_FRIENDS_DETAILS`
--

DROP TABLE IF EXISTS `MY_FRIENDS_DETAILS`;
CREATE TABLE IF NOT EXISTS `MY_FRIENDS_DETAILS` (
`MY_FRIENDS_DETAIL_ID` int(10) unsigned NOT NULL,
  `MY_FRIEND_ID` int(10) unsigned NOT NULL,
  `FRIEND_USER_ID` int(10) unsigned NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Details of my friends user id' AUTO_INCREMENT=1 ;

--
-- Triggers `MY_FRIENDS_DETAILS`
--
DROP TRIGGER IF EXISTS `MY_FRIENDS_DETAIL_BU`;
DELIMITER //
CREATE TRIGGER `MY_FRIENDS_DETAIL_BU` BEFORE UPDATE ON `my_friends_details`
 FOR EACH ROW BEGIN
    IF (OLD.MY_FRIEND_ID <> NEW.MY_FRIEND_ID) OR (OLD.FRIEND_USER_ID <> NEW.FRIEND_USER_ID) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Record identifier or friend's user id cannot be altered (referencing columns).';
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO MY_FRIENDS_DETAIL_MOD_DET 
        SET 
        MY_FRIENDS_DETAIL_ID = OLD.MY_FRIENDS_DETAIL_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `MY_FRIENDS_DETAIL_MOD_DET`
--

DROP TABLE IF EXISTS `MY_FRIENDS_DETAIL_MOD_DET`;
CREATE TABLE IF NOT EXISTS `MY_FRIENDS_DETAIL_MOD_DET` (
`MY_FRIENDS_DETAIL_MOD_DET_ID` int(10) unsigned NOT NULL,
  `MY_FRIENDS_DETAIL_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of my friends user id' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `MY_FRIEND_MOD_DET`
--

DROP TABLE IF EXISTS `MY_FRIEND_MOD_DET`;
CREATE TABLE IF NOT EXISTS `MY_FRIEND_MOD_DET` (
`MY_FRIEND_MOD_DET_ID` int(10) unsigned NOT NULL,
  `MY_FRIEND_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of my-friends table' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `NEWSLETTERS`
--

DROP TABLE IF EXISTS `NEWSLETTERS`;
CREATE TABLE IF NOT EXISTS `NEWSLETTERS` (
`NEWSLETTER_ID` int(10) unsigned NOT NULL,
  `SUBJECT` varchar(300) COLLATE latin1_general_cs NOT NULL,
  `CONTENT` text COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Newsletter details' AUTO_INCREMENT=1 ;

--
-- Triggers `NEWSLETTERS`
--
DROP TRIGGER IF EXISTS `NEWSLETTERS_BU`;
DELIMITER //
CREATE TRIGGER `NEWSLETTERS_BU` BEFORE UPDATE ON `newsletters`
 FOR EACH ROW BEGIN
    IF (NEW.SUBJECT IS NULL) OR (NEW.CONTENT IS NULL) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Newsletter subject or content cannot be BLANK.';
    END IF;
    IF OLD.CONTENT <> NEW.CONTENT THEN
        INSERT INTO NEWSLETTER_MOD_DET 
        SET 
        NEWSLETTER_ID = OLD.NEWSLETTER_ID,
        COLUMN_NAME = 'CONTENT',
        COLUMN_VALUE = OLD.CONTENT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.SUBJECT <> NEW.SUBJECT THEN
        INSERT INTO NEWSLETTER_MOD_DET 
        SET 
        NEWSLETTER_ID = OLD.NEWSLETTER_ID,
        COLUMN_NAME = 'SUBJECT',
        COLUMN_VALUE = OLD.SUBJECT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_ON <> NEW.CREATED_ON THEN
        INSERT INTO NEWSLETTER_MOD_DET 
        SET 
        NEWSLETTER_ID = OLD.NEWSLETTER_ID,
        COLUMN_NAME = 'CREATED_ON',
        COLUMN_VALUE = DATE_FORMAT(OLD.CREATED_ON,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_BY <> NEW.CREATED_BY THEN
        INSERT INTO NEWSLETTER_MOD_DET 
        SET 
        NEWSLETTER_ID = OLD.NEWSLETTER_ID,
        COLUMN_NAME = 'CREATED_BY',
        COLUMN_VALUE = OLD.CREATED_BY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO NEWSLETTER_MOD_DET 
        SET 
        NEWSLETTER_ID = OLD.NEWSLETTER_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `NEWSLETTER_MOD_DET`
--

DROP TABLE IF EXISTS `NEWSLETTER_MOD_DET`;
CREATE TABLE IF NOT EXISTS `NEWSLETTER_MOD_DET` (
`NEWSLETTER_MOD_DET_ID` int(10) unsigned NOT NULL,
  `NEWSLETTER_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of newsletter details' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `NEWSLETTER_QUEUE_MOD_DET`
--

DROP TABLE IF EXISTS `NEWSLETTER_QUEUE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `NEWSLETTER_QUEUE_MOD_DET` (
`NEWSLETTER_QUEUE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `NEWSLETTER_QUEUE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(300) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of newsletter queue' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `NEWSLETTER_QUEUE_STATUS`
--

DROP TABLE IF EXISTS `NEWSLETTER_QUEUE_STATUS`;
CREATE TABLE IF NOT EXISTS `NEWSLETTER_QUEUE_STATUS` (
`NEWSLETTER_QUEUE_ID` int(10) unsigned NOT NULL,
  `INSTANCE_ID` varchar(300) COLLATE latin1_general_cs NOT NULL,
  `NEWSLETTER_ID` int(10) unsigned NOT NULL,
  `SUBJECT` varchar(300) COLLATE latin1_general_cs NOT NULL,
  `TOTAL_COUNT` int(10) unsigned NOT NULL,
  `SENT_COUNT` int(10) unsigned NOT NULL,
  `FAIL_COUNT` int(10) unsigned NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Newsletter queue' AUTO_INCREMENT=1 ;

--
-- Triggers `NEWSLETTER_QUEUE_STATUS`
--
DROP TRIGGER IF EXISTS `NEWSLETTER_QUEUE_BU`;
DELIMITER //
CREATE TRIGGER `NEWSLETTER_QUEUE_BU` BEFORE UPDATE ON `newsletter_queue_status`
 FOR EACH ROW BEGIN
    IF (OLD.NEWSLETTER_ID <> NEW.NEWSLETTER_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Newsletter cannot be altered.';
    END IF;
    IF OLD.INSTANCE_ID <> NEW.INSTANCE_ID THEN
        INSERT INTO NEWSLETTER_QUEUE_MOD_DET 
        SET 
        NEWSLETTER_QUEUE_ID = OLD.NEWSLETTER_QUEUE_ID,
        COLUMN_NAME = 'INSTANCE_ID',
        COLUMN_VALUE = OLD.INSTANCE_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.SUBJECT <> NEW.SUBJECT THEN
        INSERT INTO NEWSLETTER_QUEUE_MOD_DET 
        SET 
        NEWSLETTER_QUEUE_ID = OLD.NEWSLETTER_QUEUE_ID,
        COLUMN_NAME = 'SUBJECT',
        COLUMN_VALUE = OLD.SUBJECT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.TOTAL_COUNT <> NEW.TOTAL_COUNT THEN
        INSERT INTO NEWSLETTER_QUEUE_MOD_DET 
        SET 
        NEWSLETTER_QUEUE_ID = OLD.NEWSLETTER_QUEUE_ID,
        COLUMN_NAME = 'TOTAL_COUNT',
        COLUMN_VALUE = OLD.TOTAL_COUNT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.SENT_COUNT <> NEW.SENT_COUNT THEN
        INSERT INTO NEWSLETTER_QUEUE_MOD_DET 
        SET 
        NEWSLETTER_QUEUE_ID = OLD.NEWSLETTER_QUEUE_ID,
        COLUMN_NAME = 'SENT_COUNT',
        COLUMN_VALUE = OLD.SENT_COUNT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.FAIL_COUNT <> NEW.FAIL_COUNT THEN
        INSERT INTO NEWSLETTER_QUEUE_MOD_DET 
        SET 
        NEWSLETTER_QUEUE_ID = OLD.NEWSLETTER_QUEUE_ID,
        COLUMN_NAME = 'FAIL_COUNT',
        COLUMN_VALUE = OLD.FAIL_COUNT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_BY <> NEW.CREATED_BY THEN
        INSERT INTO NEWSLETTER_QUEUE_MOD_DET 
        SET 
        NEWSLETTER_QUEUE_ID = OLD.NEWSLETTER_QUEUE_ID,
        COLUMN_NAME = 'CREATED_BY',
        COLUMN_VALUE = OLD.CREATED_BY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO NEWSLETTER_QUEUE_MOD_DET 
        SET 
        NEWSLETTER_QUEUE_ID = OLD.NEWSLETTER_QUEUE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_ON <> NEW.CREATED_ON THEN
        INSERT INTO NEWSLETTER_QUEUE_MOD_DET 
        SET 
        NEWSLETTER_QUEUE_ID = OLD.NEWSLETTER_QUEUE_ID,
        COLUMN_NAME = 'CREATED_ON',
        COLUMN_VALUE = DATE_FORMAT(OLD.CREATED_ON,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `NEWSLETTER_TEMPLATES`
--

DROP TABLE IF EXISTS `NEWSLETTER_TEMPLATES`;
CREATE TABLE IF NOT EXISTS `NEWSLETTER_TEMPLATES` (
`NEWSLETTER_TEMPLATE_ID` int(10) unsigned NOT NULL,
  `TEMPLATE_NAME` varchar(350) COLLATE latin1_general_cs NOT NULL,
  `TEMPLATE_BODY` text COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Newsletter templates' AUTO_INCREMENT=1 ;

--
-- Triggers `NEWSLETTER_TEMPLATES`
--
DROP TRIGGER IF EXISTS `NEWSLETTER_TEMPLATES_BU`;
DELIMITER //
CREATE TRIGGER `NEWSLETTER_TEMPLATES_BU` BEFORE UPDATE ON `newsletter_templates`
 FOR EACH ROW BEGIN
    IF OLD.TEMPLATE_NAME <> NEW.TEMPLATE_NAME THEN
        INSERT INTO NEWSLETTER_TEMPLATE_MOD_DET 
        SET 
        NEWSLETTER_TEMPLATE_ID = OLD.NEWSLETTER_TEMPLATE_ID,
        COLUMN_NAME = 'TEMPLATE_NAME',
        COLUMN_VALUE = OLD.TEMPLATE_NAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.TEMPLATE_BODY <> NEW.TEMPLATE_BODY THEN
        INSERT INTO NEWSLETTER_TEMPLATE_MOD_DET 
        SET 
        NEWSLETTER_TEMPLATE_ID = OLD.NEWSLETTER_TEMPLATE_ID,
        COLUMN_NAME = 'TEMPLATE_BODY',
        COLUMN_VALUE = OLD.TEMPLATE_BODY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_BY <> NEW.CREATED_BY THEN
        INSERT INTO NEWSLETTER_TEMPLATE_MOD_DET 
        SET 
        NEWSLETTER_TEMPLATE_ID = OLD.NEWSLETTER_TEMPLATE_ID,
        COLUMN_NAME = 'CREATED_BY',
        COLUMN_VALUE = OLD.CREATED_BY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO NEWSLETTER_TEMPLATE_MOD_DET 
        SET 
        NEWSLETTER_TEMPLATE_ID = OLD.NEWSLETTER_TEMPLATE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_ON <> NEW.CREATED_ON THEN
        INSERT INTO NEWSLETTER_TEMPLATE_MOD_DET 
        SET 
        NEWSLETTER_TEMPLATE_ID = OLD.NEWSLETTER_TEMPLATE_ID,
        COLUMN_NAME = 'CREATED_ON',
        COLUMN_VALUE = DATE_FORMAT(OLD.CREATED_ON,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `NEWSLETTER_TEMPLATE_MOD_DET`
--

DROP TABLE IF EXISTS `NEWSLETTER_TEMPLATE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `NEWSLETTER_TEMPLATE_MOD_DET` (
`NEWSLETTER_TEMPLATE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `NEWSLETTER_TEMPLATE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of newsletter templates' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `NOTIFICATIONS`
--

DROP TABLE IF EXISTS `NOTIFICATIONS`;
CREATE TABLE IF NOT EXISTS `NOTIFICATIONS` (
`NOTIFICATION_ID` int(10) unsigned NOT NULL,
  `NOTIFICATION_ACTIVITY_TYPE_ID` int(10) unsigned NOT NULL,
  `ACTIVITY_ID` int(10) unsigned NOT NULL,
  `OBJECT_ID` int(10) unsigned NOT NULL,
  `NOTIFICATION_OBJECT_TYPE_ID` int(10) unsigned NOT NULL,
  `SENDER_ID` int(10) unsigned NOT NULL,
  `ADDITIONAL_INFO` text COLLATE latin1_general_cs NOT NULL,
  `ACTIVITY_SECTION_ID` int(10) unsigned NOT NULL,
  `NOTIFICATION_ACTIVITY_SECTION_TYPE_ID` int(10) unsigned NOT NULL,
  `OBJECT_OWNER_ID` int(10) unsigned DEFAULT NULL,
  `IS_ANONYMOUS` int(11) NOT NULL DEFAULT '0' COMMENT '0: No; 1: Yes',
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table of notifications' AUTO_INCREMENT=1 ;

--
-- Triggers `NOTIFICATIONS`
--
DROP TRIGGER IF EXISTS `NOTIFICATION_BU`;
DELIMITER //
CREATE TRIGGER `NOTIFICATION_BU` BEFORE UPDATE ON `notifications`
 FOR EACH ROW BEGIN
    IF (NEW.SENDER_ID <> OLD.SENDER_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Notification sender cannot be altered.';
    END IF;
    IF OLD.NOTIFICATION_ACTIVITY_TYPE_ID <> NEW.NOTIFICATION_ACTIVITY_TYPE_ID THEN
        INSERT INTO NOTIFICATION_MOD_DET 
        SET 
        NOTIFICATION_ID = OLD.NOTIFICATION_ID,
        COLUMN_NAME = 'NOTIFICATION_ACTIVITY_TYPE_ID',
        COLUMN_VALUE = OLD.NOTIFICATION_ACTIVITY_TYPE_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.ACTIVITY_ID <> NEW.ACTIVITY_ID THEN
        INSERT INTO NOTIFICATION_MOD_DET 
        SET 
        NOTIFICATION_ID = OLD.NOTIFICATION_ID,
        COLUMN_NAME = 'ACTIVITY_ID',
        COLUMN_VALUE = OLD.ACTIVITY_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.OBJECT_ID <> NEW.OBJECT_ID THEN
        INSERT INTO NOTIFICATION_MOD_DET 
        SET 
        NOTIFICATION_ID = OLD.NOTIFICATION_ID,
        COLUMN_NAME = 'OBJECT_ID',
        COLUMN_VALUE = OLD.OBJECT_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_ON <> NEW.CREATED_ON THEN
        INSERT INTO NOTIFICATION_MOD_DET 
        SET 
        NOTIFICATION_ID = OLD.NOTIFICATION_ID,
        COLUMN_NAME = 'CREATED_ON',
        COLUMN_VALUE = DATE_FORMAT(OLD.CREATED_ON,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_BY <> NEW.CREATED_BY THEN
        INSERT INTO NOTIFICATION_MOD_DET 
        SET 
        NOTIFICATION_ID = OLD.NOTIFICATION_ID,
        COLUMN_NAME = 'CREATED_BY',
        COLUMN_VALUE = OLD.CREATED_BY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO NOTIFICATION_MOD_DET 
        SET 
        NOTIFICATION_ID = OLD.NOTIFICATION_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.NOTIFICATION_OBJECT_TYPE_ID <> NEW.NOTIFICATION_OBJECT_TYPE_ID THEN
        INSERT INTO NOTIFICATION_MOD_DET 
        SET 
        NOTIFICATION_ID = OLD.NOTIFICATION_ID,
        COLUMN_NAME = 'NOTIFICATION_OBJECT_TYPE_ID',
        COLUMN_VALUE = OLD.NOTIFICATION_OBJECT_TYPE_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.ADDITIONAL_INFO <> NEW.ADDITIONAL_INFO THEN
        INSERT INTO NOTIFICATION_MOD_DET 
        SET 
        NOTIFICATION_ID = OLD.NOTIFICATION_ID,
        COLUMN_NAME = 'ADDITIONAL_INFO',
        COLUMN_VALUE = OLD.ADDITIONAL_INFO,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.ACTIVITY_SECTION_ID <> NEW.ACTIVITY_SECTION_ID THEN
        INSERT INTO NOTIFICATION_MOD_DET 
        SET 
        NOTIFICATION_ID = OLD.NOTIFICATION_ID,
        COLUMN_NAME = 'ACTIVITY_SECTION_ID',
        COLUMN_VALUE = OLD.ACTIVITY_SECTION_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.NOTIFICATION_ACTIVITY_SECTION_TYPE_ID <> NEW.NOTIFICATION_ACTIVITY_SECTION_TYPE_ID THEN
        INSERT INTO NOTIFICATION_MOD_DET 
        SET 
        NOTIFICATION_ID = OLD.NOTIFICATION_ID,
        COLUMN_NAME = 'NOTIFICATION_ACTIVITY_SECTION_TYPE_ID',
        COLUMN_VALUE = OLD.NOTIFICATION_ACTIVITY_SECTION_TYPE_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.OBJECT_OWNER_ID <> NEW.OBJECT_OWNER_ID THEN
        INSERT INTO NOTIFICATION_MOD_DET 
        SET 
        NOTIFICATION_ID = OLD.NOTIFICATION_ID,
        COLUMN_NAME = 'OBJECT_OWNER_ID',
        COLUMN_VALUE = OLD.OBJECT_OWNER_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.IS_ANONYMOUS <> NEW.IS_ANONYMOUS THEN
        INSERT INTO NOTIFICATION_MOD_DET 
        SET 
        NOTIFICATION_ID = OLD.NOTIFICATION_ID,
        COLUMN_NAME = 'IS_ANONYMOUS',
        COLUMN_VALUE = OLD.IS_ANONYMOUS,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `NOTIFICATION_ACTIVITY_MOD_DET`
--

DROP TABLE IF EXISTS `NOTIFICATION_ACTIVITY_MOD_DET`;
CREATE TABLE IF NOT EXISTS `NOTIFICATION_ACTIVITY_MOD_DET` (
`NOTIFICATION_ACTIVITY_MOD_DET_ID` int(10) unsigned NOT NULL,
  `NOTIFICATION_ACTIVITY_TYPE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of notification activity types' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `NOTIFICATION_ACTIVITY_TYPE_MASTER`
--

DROP TABLE IF EXISTS `NOTIFICATION_ACTIVITY_TYPE_MASTER`;
CREATE TABLE IF NOT EXISTS `NOTIFICATION_ACTIVITY_TYPE_MASTER` (
`NOTIFICATION_ACTIVITY_TYPE_ID` int(10) unsigned NOT NULL,
  `NOTIFICATION_ACTIVITY_TYPE_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for notification activity types' AUTO_INCREMENT=1 ;

--
-- Triggers `NOTIFICATION_ACTIVITY_TYPE_MASTER`
--
DROP TRIGGER IF EXISTS `NOTIFICATION_ACTIVITY_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `NOTIFICATION_ACTIVITY_TYPE_BU` BEFORE UPDATE ON `notification_activity_type_master`
 FOR EACH ROW BEGIN
	IF EXISTS (SELECT 1 FROM NOTIFICATION_ACTIVITY_TYPE_MASTER WHERE NOTIFICATION_ACTIVITY_TYPE_NAME = NEW.NOTIFICATION_ACTIVITY_TYPE_NAME AND NEW.NOTIFICATION_ACTIVITY_TYPE_NAME <> OLD.NOTIFICATION_ACTIVITY_TYPE_NAME) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical notification activity type exists, please revise.';
    END IF;
    IF NEW.NOTIFICATION_ACTIVITY_TYPE_NAME <> OLD.NOTIFICATION_ACTIVITY_TYPE_NAME THEN
        INSERT INTO NOTIFICATION_ACTIVITY_MOD_DET 
        SET 
        NOTIFICATION_ACTIVITY_TYPE_ID = OLD.NOTIFICATION_ACTIVITY_TYPE_ID,
        COLUMN_NAME = 'NOTIFICATION_ACTIVITY_TYPE_NAME',
        COLUMN_VALUE = OLD.NOTIFICATION_ACTIVITY_TYPE_NAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF NEW.STATUS_ID <> OLD.STATUS_ID THEN
        INSERT INTO NOTIFICATION_ACTIVITY_MOD_DET 
        SET 
        NOTIFICATION_ACTIVITY_TYPE_ID = OLD.NOTIFICATION_ACTIVITY_TYPE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `NOTIFICATION_FREQUENCY_MASTER`
--

DROP TABLE IF EXISTS `NOTIFICATION_FREQUENCY_MASTER`;
CREATE TABLE IF NOT EXISTS `NOTIFICATION_FREQUENCY_MASTER` (
`NOTIFICATION_FREQUENCY_ID` int(10) unsigned NOT NULL,
  `NOTIFICATION_FREQUENCY_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for notification frequency' AUTO_INCREMENT=1 ;

--
-- Triggers `NOTIFICATION_FREQUENCY_MASTER`
--
DROP TRIGGER IF EXISTS `NOTIFICATION_FREQUENCY_BU`;
DELIMITER //
CREATE TRIGGER `NOTIFICATION_FREQUENCY_BU` BEFORE UPDATE ON `notification_frequency_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM NOTIFICATION_FREQUENCY_MASTER WHERE NOTIFICATION_FREQUENCY_NAME = NEW.NOTIFICATION_FREQUENCY_NAME AND NEW.NOTIFICATION_FREQUENCY_NAME <> OLD.NOTIFICATION_FREQUENCY_NAME) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical notification frequency already exists, please revise.';
    END IF;
    IF OLD.NOTIFICATION_FREQUENCY_NAME <> NEW.NOTIFICATION_FREQUENCY_NAME THEN
        INSERT INTO NOTIFICATION_FREQUENCY_MOD_DET 
        SET 
        NOTIFICATION_FREQUENCY_ID = OLD.NOTIFICATION_FREQUENCY_ID,
        COLUMN_NAME = 'NOTIFICATION_FREQUENCY_NAME',
        COLUMN_VALUE = OLD.NOTIFICATION_FREQUENCY_NAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO NOTIFICATION_FREQUENCY_MOD_DET 
        SET 
        NOTIFICATION_FREQUENCY_ID = OLD.NOTIFICATION_FREQUENCY_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `NOTIFICATION_FREQUENCY_MOD_DET`
--

DROP TABLE IF EXISTS `NOTIFICATION_FREQUENCY_MOD_DET`;
CREATE TABLE IF NOT EXISTS `NOTIFICATION_FREQUENCY_MOD_DET` (
`NOTIFICATION_FREQUENCY_MOD_DET_ID` int(10) unsigned NOT NULL,
  `NOTIFICATION_FREQUENCY_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of notification frequency' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `NOTIFICATION_MOD_DET`
--

DROP TABLE IF EXISTS `NOTIFICATION_MOD_DET`;
CREATE TABLE IF NOT EXISTS `NOTIFICATION_MOD_DET` (
`NOTIFICATION_MOD_DET_ID` int(10) unsigned NOT NULL,
  `NOTIFICATION_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of notifications' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `NOTIFICATION_OBJECT_TYPE_MASTER`
--

DROP TABLE IF EXISTS `NOTIFICATION_OBJECT_TYPE_MASTER`;
CREATE TABLE IF NOT EXISTS `NOTIFICATION_OBJECT_TYPE_MASTER` (
`NOTIFICATION_OBJECT_TYPE_ID` int(10) unsigned NOT NULL,
  `NOTIFICATION_OBJECT_TYPE_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for notification object types' AUTO_INCREMENT=1 ;

--
-- Triggers `NOTIFICATION_OBJECT_TYPE_MASTER`
--
DROP TRIGGER IF EXISTS `NOTIFICATION_OBJECT_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `NOTIFICATION_OBJECT_TYPE_BU` BEFORE UPDATE ON `notification_object_type_master`
 FOR EACH ROW BEGIN
	IF EXISTS (SELECT 1 FROM NOTIFICATION_OBJECT_TYPE_MASTER WHERE NOTIFICATION_OBJECT_TYPE_NAME = NEW.NOTIFICATION_OBJECT_TYPE_NAME AND NEW.NOTIFICATION_OBJECT_TYPE_NAME <> OLD.NOTIFICATION_OBJECT_TYPE_NAME) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical notification object type exists, please revise.';
    END IF;
    IF NEW.NOTIFICATION_OBJECT_TYPE_NAME <> OLD.NOTIFICATION_OBJECT_TYPE_NAME THEN
        INSERT INTO NOTIFICATION_OBJECT_MOD_DET 
        SET 
        NOTIFICATION_OBJECT_TYPE_ID = OLD.NOTIFICATION_OBJECT_TYPE_ID,
        COLUMN_NAME = 'NOTIFICATION_OBJECT_TYPE_NAME',
        COLUMN_VALUE = OLD.NOTIFICATION_OBJECT_TYPE_NAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF NEW.STATUS_ID <> OLD.STATUS_ID THEN
        INSERT INTO NOTIFICATION_OBJECT_MOD_DET 
        SET 
        NOTIFICATION_OBJECT_TYPE_ID = OLD.NOTIFICATION_OBJECT_TYPE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `NOTIFICATION_OBJECT_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `NOTIFICATION_OBJECT_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `NOTIFICATION_OBJECT_TYPE_MOD_DET` (
`NOTIFICATION_OBJECT_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `NOTIFICATION_OBJECT_TYPE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of notification object types' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `NOTIFICATION_RECIPIENTS`
--

DROP TABLE IF EXISTS `NOTIFICATION_RECIPIENTS`;
CREATE TABLE IF NOT EXISTS `NOTIFICATION_RECIPIENTS` (
`NOTIFICATION_RECIPIENT_ID` int(10) unsigned NOT NULL,
  `NOTIFICATION_ID` int(10) unsigned NOT NULL,
  `RECIPIENT_ID` int(10) unsigned NOT NULL,
  `IS_READ` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0: False; 1: True',
  `ADDITIONAL_INFO` text COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Notification recipients' AUTO_INCREMENT=1 ;

--
-- Triggers `NOTIFICATION_RECIPIENTS`
--
DROP TRIGGER IF EXISTS `NOTIFICATION_RECIPIENT_BU`;
DELIMITER //
CREATE TRIGGER `NOTIFICATION_RECIPIENT_BU` BEFORE UPDATE ON `notification_recipients`
 FOR EACH ROW BEGIN
    IF (OLD.NOTIFICATION_ID <> NEW.NOTIFICATION_ID) OR (OLD.RECIPIENT_ID <> NEW.RECIPIENT_ID) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Notification or recipient id cannot be altered.';
    END IF;
    IF OLD.IS_READ <> NEW.IS_READ THEN
        INSERT INTO NOTIFICATION_RECIPIENT_MOD_DET 
        SET 
        NOTIFICATION_RECIPIENT_ID = OLD.NOTIFICATION_RECIPIENT_ID,
        COLUMN_NAME = 'IS_READ',
        COLUMN_VALUE = OLD.IS_READ,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.ADDITIONAL_INFO <> NEW.ADDITIONAL_INFO THEN
        INSERT INTO NOTIFICATION_RECIPIENT_MOD_DET 
        SET 
        NOTIFICATION_RECIPIENT_ID = OLD.NOTIFICATION_RECIPIENT_ID,
        COLUMN_NAME = 'ADDITIONAL_INFO',
        COLUMN_VALUE = OLD.ADDITIONAL_INFO,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO NOTIFICATION_RECIPIENT_MOD_DET 
        SET 
        NOTIFICATION_RECIPIENT_ID = OLD.NOTIFICATION_RECIPIENT_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `NOTIFICATION_RECIPIENT_MOD_DET`
--

DROP TABLE IF EXISTS `NOTIFICATION_RECIPIENT_MOD_DET`;
CREATE TABLE IF NOT EXISTS `NOTIFICATION_RECIPIENT_MOD_DET` (
`NOTIFICATION_RECIPIENT_MOD_DET_ID` int(10) unsigned NOT NULL,
  `NOTIFICATION_RECIPIENT_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of notification recipients' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `NOTIFICATION_SETTINGS`
--

DROP TABLE IF EXISTS `NOTIFICATION_SETTINGS`;
CREATE TABLE IF NOT EXISTS `NOTIFICATION_SETTINGS` (
`NOTIFICATION_SETTING_ID` int(10) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `EMAIL_SETTINGS` text COLLATE latin1_general_cs NOT NULL,
  `HEIGHT_UNIT` int(10) unsigned DEFAULT NULL,
  `WEIGHT_UNIT` int(10) unsigned DEFAULT NULL,
  `TEMP_UNIT` int(10) unsigned DEFAULT NULL,
  `NOTIFICATION_COUNT` int(10) unsigned NOT NULL DEFAULT '0',
  `NOTIFICATION_LAST_VIEWED` datetime NOT NULL,
  `NOTIFICATION_FREQUENCY_ID` int(10) unsigned NOT NULL,
  `LAST_RECOMMENDED` datetime DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Notification settings' AUTO_INCREMENT=1 ;

--
-- Triggers `NOTIFICATION_SETTINGS`
--
DROP TRIGGER IF EXISTS `NOTIFICATION_SETTINGS_BU`;
DELIMITER //
CREATE TRIGGER `NOTIFICATION_SETTINGS_BU` BEFORE UPDATE ON `notification_settings`
 FOR EACH ROW BEGIN
    IF (OLD.USER_ID <> NEW.USER_ID) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'User profile cannot be altered.';
    END IF;
    IF OLD.EMAIL_SETTINGS <> NEW.EMAIL_SETTINGS THEN
        INSERT INTO NOTIFICATION_SETTING_MOD_DET 
        SET 
        NOTIFICATION_SETTING_ID = OLD.NOTIFICATION_SETTING_ID,
        COLUMN_NAME = 'EMAIL_SETTINGS',
        COLUMN_VALUE = OLD.EMAIL_SETTINGS,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.HEIGHT_UNIT <> NEW.HEIGHT_UNIT THEN
        INSERT INTO NOTIFICATION_SETTING_MOD_DET 
        SET 
        NOTIFICATION_SETTING_ID = OLD.NOTIFICATION_SETTING_ID,
        COLUMN_NAME = 'HEIGHT_UNIT',
        COLUMN_VALUE = OLD.HEIGHT_UNIT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.WEIGHT_UNIT <> NEW.WEIGHT_UNIT THEN
        INSERT INTO NOTIFICATION_SETTING_MOD_DET 
        SET 
        NOTIFICATION_SETTING_ID = OLD.NOTIFICATION_SETTING_ID,
        COLUMN_NAME = 'WEIGHT_UNIT',
        COLUMN_VALUE = OLD.WEIGHT_UNIT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.TEMP_UNIT <> NEW.TEMP_UNIT THEN
        INSERT INTO NOTIFICATION_SETTING_MOD_DET 
        SET 
        NOTIFICATION_SETTING_ID = OLD.NOTIFICATION_SETTING_ID,
        COLUMN_NAME = 'TEMP_UNIT',
        COLUMN_VALUE = OLD.TEMP_UNIT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.NOTIFICATION_COUNT <> NEW.NOTIFICATION_COUNT THEN
        INSERT INTO NOTIFICATION_SETTING_MOD_DET 
        SET 
        NOTIFICATION_SETTING_ID = OLD.NOTIFICATION_SETTING_ID,
        COLUMN_NAME = 'NOTIFICATION_COUNT',
        COLUMN_VALUE = OLD.NOTIFICATION_COUNT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.NOTIFICATION_LAST_VIEWED <> NEW.NOTIFICATION_LAST_VIEWED THEN
        INSERT INTO NOTIFICATION_SETTING_MOD_DET 
        SET 
        NOTIFICATION_SETTING_ID = OLD.NOTIFICATION_SETTING_ID,
        COLUMN_NAME = 'NOTIFICATION_LAST_VIEWED',
        COLUMN_VALUE = DATE_FORMAT(OLD.NOTIFICATION_LAST_VIEWED,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.LAST_RECOMMENDED <> NEW.LAST_RECOMMENDED THEN
        INSERT INTO NOTIFICATION_SETTING_MOD_DET 
        SET 
        NOTIFICATION_SETTING_ID = OLD.NOTIFICATION_SETTING_ID,
        COLUMN_NAME = 'LAST_RECOMMENDED',
        COLUMN_VALUE = DATE_FORMAT(OLD.LAST_RECOMMENDED,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO NOTIFICATION_SETTING_MOD_DET 
        SET 
        NOTIFICATION_SETTING_ID = OLD.NOTIFICATION_SETTING_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.NOTIFICATION_FREQUENCY_ID <> NEW.NOTIFICATION_FREQUENCY_ID THEN
        INSERT INTO NOTIFICATION_SETTING_MOD_DET 
        SET 
        NOTIFICATION_SETTING_ID = OLD.NOTIFICATION_SETTING_ID,
        COLUMN_NAME = 'NOTIFICATION_FREQUENCY_ID',
        COLUMN_VALUE = OLD.NOTIFICATION_FREQUENCY_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `NOTIFICATION_SETTING_MOD_DET`
--

DROP TABLE IF EXISTS `NOTIFICATION_SETTING_MOD_DET`;
CREATE TABLE IF NOT EXISTS `NOTIFICATION_SETTING_MOD_DET` (
`NOTIFICATION_SETTING_MOD_DET_ID` int(10) unsigned NOT NULL,
  `NOTIFICATION_SETTING_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of notification settings' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `NOTIFIED_USERS`
--

DROP TABLE IF EXISTS `NOTIFIED_USERS`;
CREATE TABLE IF NOT EXISTS `NOTIFIED_USERS` (
`NOTIFIED_USER_ID` int(10) unsigned NOT NULL,
  `NOTIFICATION_SETTING_ID` int(10) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Notified user list' AUTO_INCREMENT=1 ;

--
-- Triggers `NOTIFIED_USERS`
--
DROP TRIGGER IF EXISTS `NOTIFIED_USERS_BU`;
DELIMITER //
CREATE TRIGGER `NOTIFIED_USERS_BU` BEFORE UPDATE ON `notified_users`
 FOR EACH ROW BEGIN
    IF (OLD.USER_ID <> NEW.USER_ID) OR (OLD.NOTIFICATION_SETTING_ID <> NEW.NOTIFICATION_SETTING_ID) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'User or notification setting identifier cannot be altered.';
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO NOTIFIED_USER_MOD_DET 
        SET 
        NOTIFIED_USER_ID = OLD.NOTIFIED_USER_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `NOTIFIED_USER_MOD_DET`
--

DROP TABLE IF EXISTS `NOTIFIED_USER_MOD_DET`;
CREATE TABLE IF NOT EXISTS `NOTIFIED_USER_MOD_DET` (
`NOTIFIED_USER_MOD_DET_ID` int(10) unsigned NOT NULL,
  `NOTIFIED_USER_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of notified users' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `PAGE_MASTER`
--

DROP TABLE IF EXISTS `PAGE_MASTER`;
CREATE TABLE IF NOT EXISTS `PAGE_MASTER` (
`PAGE_ID` int(10) unsigned NOT NULL,
  `PAGE_TYPE_ID` int(10) unsigned NOT NULL,
  `PAGE_DESCR` varchar(500) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Page descriptions' AUTO_INCREMENT=1 ;

--
-- Triggers `PAGE_MASTER`
--
DROP TRIGGER IF EXISTS `PAGE_BU`;
DELIMITER //
CREATE TRIGGER `PAGE_BU` BEFORE UPDATE ON `page_master`
 FOR EACH ROW BEGIN
	IF (OLD.PAGE_TYPE_ID <> NEW.PAGE_TYPE_ID) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Page type cannot be altered.';
    END IF;
    IF EXISTS (SELECT 1 FROM PAGE_MASTER WHERE PAGE_TYPE_ID = NEW.PAGE_TYPE_ID AND PAGE_DESCR = NEW.PAGE_DESCR AND NEW.PAGE_DESCR <> OLD.PAGE_DESCR) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical page description already exists, please revise.';
    END IF;
    IF OLD.PAGE_DESCR <> NEW.PAGE_DESCR THEN
        INSERT INTO PAGE_MOD_DET 
        SET 
        PAGE_ID = OLD.PAGE_ID,
        COLUMN_NAME = 'PAGE_DESCR',
        COLUMN_VALUE = OLD.PAGE_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO PAGE_MOD_DET 
        SET 
        PAGE_ID = OLD.PAGE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `PAGE_MOD_DET`
--

DROP TABLE IF EXISTS `PAGE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `PAGE_MOD_DET` (
`PAGE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `PAGE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(500) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of pages' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `PAGE_TYPE_MASTER`
--

DROP TABLE IF EXISTS `PAGE_TYPE_MASTER`;
CREATE TABLE IF NOT EXISTS `PAGE_TYPE_MASTER` (
`PAGE_TYPE_ID` int(10) unsigned NOT NULL,
  `PAGE_TYPE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Page type' AUTO_INCREMENT=1 ;

--
-- Triggers `PAGE_TYPE_MASTER`
--
DROP TRIGGER IF EXISTS `PAGE_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `PAGE_TYPE_BU` BEFORE UPDATE ON `page_type_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM PAGE_TYPE_MASTER WHERE PAGE_TYPE_DESCR = NEW.PAGE_TYPE_DESCR AND NEW.PAGE_TYPE_DESCR <> OLD.PAGE_TYPE_DESCR) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical page type already exists, please revise.';
    END IF;
    IF OLD.PAGE_TYPE_DESCR <> NEW.PAGE_TYPE_DESCR THEN
        INSERT INTO PAGE_TYPE_MOD_DET 
        SET 
        PAGE_TYPE_ID = OLD.PAGE_TYPE_ID,
        COLUMN_NAME = 'PAGE_TYPE_DESCR',
        COLUMN_VALUE = OLD.PAGE_TYPE_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO PAGE_TYPE_MOD_DET 
        SET 
        PAGE_TYPE_ID = OLD.PAGE_TYPE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `PAGE_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `PAGE_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `PAGE_TYPE_MOD_DET` (
`PAGE_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `PAGE_TYPE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of page type' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `PAIN_LEVELS_MASTER`
--

DROP TABLE IF EXISTS `PAIN_LEVELS_MASTER`;
CREATE TABLE IF NOT EXISTS `PAIN_LEVELS_MASTER` (
`PAIN_LEVEL_ID` int(10) unsigned NOT NULL,
  `PAIN_ID` int(10) unsigned NOT NULL COMMENT 'Pain type from pain master',
  `PAIN_LEVEL_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last edited on',
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for pain levels' AUTO_INCREMENT=1 ;

--
-- Triggers `PAIN_LEVELS_MASTER`
--
DROP TRIGGER IF EXISTS `PAIN_LEVEL_BU`;
DELIMITER //
CREATE TRIGGER `PAIN_LEVEL_BU` BEFORE UPDATE ON `pain_levels_master`
 FOR EACH ROW BEGIN
    IF OLD.PAIN_ID <> NEW.PAIN_ID THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Pain type cannot be altered.';
    END IF;
    IF EXISTS (SELECT 1 FROM PAIN_LEVELS_MASTER WHERE PAIN_ID = OLD.PAIN_ID AND PAIN_LEVEL_DESCR = NEW.PAIN_LEVEL_DESCR AND NEW.PAIN_LEVEL_DESCR <> OLD.PAIN_LEVEL_DESCR) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate record with identical pain type and pain level description exists, please revise.';
    END IF;
    IF OLD.PAIN_LEVEL_DESCR <> NEW.PAIN_LEVEL_DESCR THEN
        INSERT INTO PAIN_LEVEL_MOD_DET 
        SET 
        PAIN_LEVEL_ID = OLD.PAIN_LEVEL_ID,
        COLUMN_NAME = 'PAIN_LEVEL_DESCR',
        COLUMN_VALUE = OLD.PAIN_LEVEL_DESCR,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO PAIN_LEVEL_MOD_DET 
        SET 
        PAIN_LEVEL_ID = OLD.PAIN_LEVEL_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `PAIN_LEVEL_MOD_DET`
--

DROP TABLE IF EXISTS `PAIN_LEVEL_MOD_DET`;
CREATE TABLE IF NOT EXISTS `PAIN_LEVEL_MOD_DET` (
`PAIN_LEVEL_MOD_DET_ID` int(10) unsigned NOT NULL,
  `PAIN_LEVEL_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of pain levels' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `PAIN_MASTER`
--

DROP TABLE IF EXISTS `PAIN_MASTER`;
CREATE TABLE IF NOT EXISTS `PAIN_MASTER` (
`PAIN_ID` int(10) unsigned NOT NULL,
  `PAIN_DESCR` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all pain types' AUTO_INCREMENT=8 ;

--
-- Triggers `PAIN_MASTER`
--
DROP TRIGGER IF EXISTS `PAIN_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `PAIN_TYPE_BU` BEFORE UPDATE ON `pain_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM PAIN_MASTER WHERE PAIN_DESCR = NEW.PAIN_DESCR AND NEW.PAIN_DESCR <> OLD.PAIN_DESCR) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical description for pain type exists, please revise.';
    END IF;
    IF OLD.PAIN_DESCR <> NEW.PAIN_DESCR THEN
        INSERT INTO PAIN_TYPE_MOD_DET 
        SET 
        PAIN_ID = OLD.PAIN_ID,
        COLUMN_NAME = 'PAIN_DESCR',
        COLUMN_VALUE = OLD.PAIN_DESCR,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO PAIN_TYPE_MOD_DET 
        SET 
        PAIN_ID = OLD.PAIN_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `PAIN_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `PAIN_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `PAIN_TYPE_MOD_DET` (
`PAIN_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `PAIN_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for pain types' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `PATIENT_CARE_GIVERS`
--

DROP TABLE IF EXISTS `PATIENT_CARE_GIVERS`;
CREATE TABLE IF NOT EXISTS `PATIENT_CARE_GIVERS` (
`PATIENT_CARE_GIVER_ID` int(10) unsigned NOT NULL,
  `RELATIONSHIP_ID` int(10) unsigned NOT NULL,
  `USER_ID` int(10) unsigned DEFAULT NULL,
  `PATIENT_ID` int(10) unsigned NOT NULL,
  `FIRST_NAME` varchar(100) COLLATE latin1_general_cs DEFAULT NULL,
  `LAST_NAME` varchar(100) COLLATE latin1_general_cs DEFAULT NULL,
  `DATE_OF_BIRTH` datetime DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `GENDER` varchar(1) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Details of patient care givers' AUTO_INCREMENT=1 ;

--
-- Triggers `PATIENT_CARE_GIVERS`
--
DROP TRIGGER IF EXISTS `PATIENT_CARE_GIVERS_BU`;
DELIMITER //
CREATE TRIGGER `PATIENT_CARE_GIVERS_BU` BEFORE UPDATE ON `patient_care_givers`
 FOR EACH ROW BEGIN
IF (OLD.RELATIONSHIP_ID <> NEW.RELATIONSHIP_ID) OR (OLD.PATIENT_ID <> NEW.PATIENT_ID) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Care giver relationship or patient cannot be altered.';
END IF;
IF OLD.USER_ID <> NEW.USER_ID THEN
INSERT INTO PATIENT_CARE_GIVER_MOD_DET 
SET 
PATIENT_CARE_GIVER_ID = OLD.PATIENT_CARE_GIVER_ID,
COLUMN_NAME = 'USER_ID',
COLUMN_VALUE = OLD.USER_ID,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
IF OLD.FIRST_NAME <> NEW.FIRST_NAME THEN
INSERT INTO PATIENT_CARE_GIVER_MOD_DET 
SET 
PATIENT_CARE_GIVER_ID = OLD.PATIENT_CARE_GIVER_ID,
COLUMN_NAME = 'FIRST_NAME',
COLUMN_VALUE = OLD.FIRST_NAME,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
IF OLD.LAST_NAME <> NEW.LAST_NAME THEN
INSERT INTO PATIENT_CARE_GIVER_MOD_DET 
SET 
PATIENT_CARE_GIVER_ID = OLD.PATIENT_CARE_GIVER_ID,
COLUMN_NAME = 'LAST_NAME',
COLUMN_VALUE = OLD.LAST_NAME,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
IF OLD.DATE_OF_BIRTH <> NEW.DATE_OF_BIRTH THEN
INSERT INTO INVITED_USERS_MOD_DET 
SET 
PATIENT_CARE_GIVER_ID = OLD.PATIENT_CARE_GIVER_ID,
COLUMN_NAME = 'DATE_OF_BIRTH',
COLUMN_VALUE = DATE_FORMAT(OLD.DATE_OF_BIRTH,'YYYYMMDDHH24MISS'),
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
INSERT INTO PATIENT_CARE_GIVER_MOD_DET 
SET 
PATIENT_CARE_GIVER_ID = OLD.PATIENT_CARE_GIVER_ID,
COLUMN_NAME = 'STATUS_ID',
COLUMN_VALUE = OLD.STATUS_ID,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
IF OLD.GENDER <> NEW.GENDER THEN
INSERT INTO PATIENT_CARE_GIVER_MOD_DET 
SET 
PATIENT_CARE_GIVER_ID = OLD.PATIENT_CARE_GIVER_ID,
COLUMN_NAME = 'GENDER',
COLUMN_VALUE = OLD.GENDER,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `PATIENT_CARE_GIVER_MOD_DET`
--

DROP TABLE IF EXISTS `PATIENT_CARE_GIVER_MOD_DET`;
CREATE TABLE IF NOT EXISTS `PATIENT_CARE_GIVER_MOD_DET` (
`PATIENT_CARE_GIVER_MOD_DET_ID` int(10) unsigned NOT NULL,
  `PATIENT_CARE_GIVER_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of patient care giver records' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `PHOTO_TYPE_MASTER`
--

DROP TABLE IF EXISTS `PHOTO_TYPE_MASTER`;
CREATE TABLE IF NOT EXISTS `PHOTO_TYPE_MASTER` (
`PHOTO_TYPE_ID` int(10) unsigned NOT NULL,
  `PHOTO_TYPE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all photo types' AUTO_INCREMENT=3 ;

--
-- Triggers `PHOTO_TYPE_MASTER`
--
DROP TRIGGER IF EXISTS `PHOTO_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `PHOTO_TYPE_BU` BEFORE UPDATE ON `photo_type_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM PHOTO_TYPE_MASTER WHERE PHOTO_TYPE = NEW.PHOTO_TYPE AND NEW.PHOTO_TYPE <> OLD.PHOTO_TYPE) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate record with identical photo type exists, please revise.';
    END IF;
    IF OLD.PHOTO_TYPE <> NEW.PHOTO_TYPE THEN
        INSERT INTO PHOTO_TYPE_MOD_DET 
        SET 
        PHOTO_TYPE_ID = OLD.PHOTO_TYPE_ID,
        COLUMN_NAME = 'PHOTO_TYPE',
        COLUMN_VALUE = OLD.PHOTO_TYPE,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO PHOTO_TYPE_MOD_DET 
        SET 
        PHOTO_TYPE_ID = OLD.PHOTO_TYPE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `PHOTO_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `PHOTO_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `PHOTO_TYPE_MOD_DET` (
`PHOTO_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `PHOTO_TYPE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for photo types' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `POLLS`
--

DROP TABLE IF EXISTS `POLLS`;
CREATE TABLE IF NOT EXISTS `POLLS` (
`POLL_ID` int(10) unsigned NOT NULL,
  `POLL_TITLE` text COLLATE latin1_general_cs NOT NULL,
  `POLL_SECTION_TYPE_ID` int(10) unsigned DEFAULT NULL,
  `POSTED_IN` int(10) unsigned NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='List of all polls on H4L' AUTO_INCREMENT=1 ;

--
-- Triggers `POLLS`
--
DROP TRIGGER IF EXISTS `POLL_BU`;
DELIMITER //
CREATE TRIGGER `POLL_BU` BEFORE UPDATE ON `polls`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM POLLS WHERE POLL_TITLE = NEW.POLL_TITLE AND NEW.POLL_TITLE <> OLD.POLL_TITLE) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical poll title exists, please revise.';
    END IF;
    IF OLD.POLL_TITLE <> NEW.POLL_TITLE THEN
        INSERT INTO POLL_MOD_DET 
        SET 
        POLL_ID = OLD.POLL_ID,
        COLUMN_NAME = 'POLL_TITLE',
        COLUMN_VALUE = OLD.POLL_TITLE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.POLL_SECTION_TYPE_ID <> NEW.POLL_SECTION_TYPE_ID THEN
        INSERT INTO POLL_MOD_DET 
        SET 
        POLL_ID = OLD.POLL_ID,
        COLUMN_NAME = 'POLL_SECTION_TYPE_ID',
        COLUMN_VALUE = OLD.POLL_SECTION_TYPE_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.POSTED_IN <> NEW.POSTED_IN THEN
        INSERT INTO POLL_MOD_DET 
        SET 
        POLL_ID = OLD.POLL_ID,
        COLUMN_NAME = 'POSTED_IN',
        COLUMN_VALUE = OLD.POSTED_IN,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_BY <> NEW.CREATED_BY THEN
        INSERT INTO POLL_MOD_DET 
        SET 
        POLL_ID = OLD.POLL_ID,
        COLUMN_NAME = 'CREATED_BY',
        COLUMN_VALUE = OLD.CREATED_BY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CREATED_ON <> NEW.CREATED_ON THEN
        INSERT INTO POLL_MOD_DET 
        SET 
        POLL_ID = OLD.POLL_ID,
        COLUMN_NAME = 'CREATED_ON',
        COLUMN_VALUE = DATE_FORMAT(OLD.CREATED_ON,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO POLL_MOD_DET 
        SET 
        POLL_ID = OLD.POLL_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `POLL_CHOICES`
--

DROP TABLE IF EXISTS `POLL_CHOICES`;
CREATE TABLE IF NOT EXISTS `POLL_CHOICES` (
`POLL_CHOICE_ID` int(10) unsigned NOT NULL,
  `POLL_ID` int(10) unsigned NOT NULL,
  `POLL_OPTION` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `VOTES` int(10) unsigned NOT NULL DEFAULT '0',
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Poll choices' AUTO_INCREMENT=1 ;

--
-- Triggers `POLL_CHOICES`
--
DROP TRIGGER IF EXISTS `POLL_CHOICE_BU`;
DELIMITER //
CREATE TRIGGER `POLL_CHOICE_BU` BEFORE UPDATE ON `poll_choices`
 FOR EACH ROW BEGIN
	IF (NEW.POLL_ID <> OLD.POLL_ID) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Poll identifier cannot be altered.';
    END IF;
    IF EXISTS (SELECT 1 FROM POLL_CHOICES WHERE POLL_ID = OLD.POLL_ID AND POLL_OPTION = NEW.POLL_OPTION AND NEW.POLL_OPTION <> OLD.POLL_OPTION) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical option exists for this poll, please revise.';
    END IF;
    IF OLD.POLL_OPTION <> NEW.POLL_OPTION THEN
        INSERT INTO POLL_CHOICE_MOD_DET 
        SET 
        POLL_CHOICE_ID = OLD.POLL_CHOICE_ID,
        COLUMN_NAME = 'POLL_OPTION',
        COLUMN_VALUE = OLD.POLL_OPTION,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.VOTES <> NEW.VOTES THEN
        INSERT INTO POLL_CHOICE_MOD_DET 
        SET 
        POLL_CHOICE_ID = OLD.POLL_CHOICE_ID,
        COLUMN_NAME = 'VOTES',
        COLUMN_VALUE = OLD.VOTES,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO POLL_CHOICE_MOD_DET 
        SET 
        POLL_CHOICE_ID = OLD.POLL_CHOICE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `POLL_CHOICE_MOD_DET`
--

DROP TABLE IF EXISTS `POLL_CHOICE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `POLL_CHOICE_MOD_DET` (
`POLL_CHOICE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `POLL_CHOICE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of poll choices' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `POLL_MOD_DET`
--

DROP TABLE IF EXISTS `POLL_MOD_DET`;
CREATE TABLE IF NOT EXISTS `POLL_MOD_DET` (
`POLL_MOD_DET_ID` int(10) unsigned NOT NULL,
  `POLL_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of polls' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `POSTS`
--

DROP TABLE IF EXISTS `POSTS`;
CREATE TABLE IF NOT EXISTS `POSTS` (
  `POST_ID` int(10) unsigned NOT NULL,
  `CREATED_BY` int(10) unsigned NOT NULL,
  `CREATED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last edited on',
  `IP_ADDRESS` varchar(15) COLLATE latin1_general_cs DEFAULT NULL,
  `LIKE_COUNT` int(10) unsigned DEFAULT NULL,
  `COMMENT_COUNT` int(10) unsigned DEFAULT NULL,
  `IS_DELETED` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `POST_TYPE_ID` int(11) NOT NULL,
  `IS_ANONYMOUS` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Triggers `POSTS`
--
DROP TRIGGER IF EXISTS `POSTS_BU`;
DELIMITER //
CREATE TRIGGER `POSTS_BU` BEFORE UPDATE ON `posts`
 FOR EACH ROW BEGIN
IF OLD.CREATED_BY <> NEW.CREATED_BY THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Post owner cannot be altered.';
END IF;
IF OLD.IP_ADDRESS <> NEW.IP_ADDRESS THEN
INSERT INTO POST_MOD_DET 
SET 
POST_ID = OLD.POST_ID,
COLUMN_NAME = 'IP_ADDRESS',
COLUMN_VALUE = OLD.IP_ADDRESS,
MODIFIED_BY = NULL;
END IF;
IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
INSERT INTO POST_MOD_DET 
SET 
POST_ID = OLD.POST_ID,
COLUMN_NAME = 'STATUS_ID',
COLUMN_VALUE = OLD.STATUS_ID,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
IF OLD.LIKE_COUNT <> NEW.LIKE_COUNT THEN
INSERT INTO POST_MOD_DET 
SET 
POST_ID = OLD.POST_ID,
COLUMN_NAME = 'LIKE_COUNT',
COLUMN_VALUE = OLD.LIKE_COUNT,
MODIFIED_BY = NULL;
END IF;
IF OLD.COMMENT_COUNT <> NEW.COMMENT_COUNT THEN
INSERT INTO POST_MOD_DET 
SET 
POST_ID = OLD.POST_ID,
COLUMN_NAME = 'COMMENT_COUNT',
COLUMN_VALUE = OLD.COMMENT_COUNT,
MODIFIED_BY = NULL;
END IF;
IF OLD.IS_DELETED <> NEW.IS_DELETED THEN
INSERT INTO POST_MOD_DET 
SET 
POST_ID = OLD.POST_ID,
COLUMN_NAME = 'IS_DELETED',
COLUMN_VALUE = OLD.IS_DELETED,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
IF OLD.POST_TYPE_ID <> NEW.POST_TYPE_ID THEN
INSERT INTO POST_MOD_DET 
SET 
POST_ID = OLD.POST_ID,
COLUMN_NAME = 'POST_TYPE_ID',
COLUMN_VALUE = OLD.POST_TYPE_ID,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
IF OLD.IS_ANONYMOUS <> NEW.IS_ANONYMOUS THEN
INSERT INTO POST_MOD_DET 
SET 
POST_ID = OLD.POST_ID,
COLUMN_NAME = 'IS_ANONYMOUS',
COLUMN_VALUE = OLD.IS_ANONYMOUS,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `POST_COMMENTS`
--

DROP TABLE IF EXISTS `POST_COMMENTS`;
CREATE TABLE IF NOT EXISTS `POST_COMMENTS` (
`POST_COMMENT_ID` int(11) unsigned NOT NULL,
  `POST_ID` int(10) unsigned NOT NULL,
  `COMMENT_TEXT` text COLLATE latin1_general_cs,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Child table for posts that tracks all comments' AUTO_INCREMENT=1 ;

--
-- Triggers `POST_COMMENTS`
--
DROP TRIGGER IF EXISTS `POST_COMMENTS_BU`;
DELIMITER //
CREATE TRIGGER `POST_COMMENTS_BU` BEFORE UPDATE ON `post_comments`
 FOR EACH ROW BEGIN
IF OLD.POST_ID <> NEW.POST_ID THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Post id cannot be altered.';
END IF;
IF OLD.COMMENT_TEXT <> NEW.COMMENT_TEXT THEN
INSERT INTO POST_COMMENTS_MOD_DET 
SET 
POST_COMMENT_ID = OLD.POST_COMMENT_ID,
COLUMN_NAME = 'COMMENT_TEXT',
COLUMN_VALUE = OLD.COMMENT_TEXT,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
INSERT INTO POST_COMMENTS_MOD_DET 
SET 
POST_COMMENT_ID = OLD.POST_COMMENT_ID,
COLUMN_NAME = 'STATUS_ID',
COLUMN_VALUE = OLD.STATUS_ID,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `POST_COMMENTS_MOD_DET`
--

DROP TABLE IF EXISTS `POST_COMMENTS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `POST_COMMENTS_MOD_DET` (
`POST_COMMENT_MOD_DET_ID` int(10) unsigned NOT NULL,
  `POST_COMMENT_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of comments for user posts' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `POST_CONTENT_DETAILS`
--

DROP TABLE IF EXISTS `POST_CONTENT_DETAILS`;
CREATE TABLE IF NOT EXISTS `POST_CONTENT_DETAILS` (
`POST_CONTENT_ID` int(11) unsigned NOT NULL,
  `POST_ID` int(10) unsigned NOT NULL,
  `CONTENT_ATTRIBUTE_TEXT` text COLLATE latin1_general_cs,
  `CONTENT_ATTRIBUTE_ID` int(11) unsigned NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Child table to posts with content details' AUTO_INCREMENT=1 ;

--
-- Triggers `POST_CONTENT_DETAILS`
--
DROP TRIGGER IF EXISTS `POST_CONTENT_BU`;
DELIMITER //
CREATE TRIGGER `POST_CONTENT_BU` BEFORE UPDATE ON `post_content_details`
 FOR EACH ROW BEGIN
IF OLD.POST_ID <> NEW.POST_ID THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Post id cannot be altered.';
END IF;
IF OLD.CONTENT_ATTRIBUTE_ID <> NEW.CONTENT_ATTRIBUTE_ID THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Content attribute type cannot be altered.';
END IF;
IF OLD.CONTENT_ATTRIBUTE_TEXT <> NEW.CONTENT_ATTRIBUTE_TEXT THEN
INSERT INTO POST_CONTENT_MOD_DET 
SET 
POST_CONTENT_ID = OLD.POST_CONTENT_ID,
COLUMN_NAME = 'CONTENT_ATTRIBUTE_TEXT',
COLUMN_VALUE = OLD.CONTENT_ATTRIBUTE_TEXT,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
INSERT INTO POST_CONTENT_MOD_DET 
SET 
POST_CONTENT_ID = OLD.POST_CONTENT_ID,
COLUMN_NAME = 'STATUS_ID',
COLUMN_VALUE = OLD.STATUS_ID,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `POST_CONTENT_MOD_DET`
--

DROP TABLE IF EXISTS `POST_CONTENT_MOD_DET`;
CREATE TABLE IF NOT EXISTS `POST_CONTENT_MOD_DET` (
`POST_CONTENT_MOD_DET_ID` int(10) unsigned NOT NULL,
  `POST_CONTENT_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of post content details' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `POST_LIKES`
--

DROP TABLE IF EXISTS `POST_LIKES`;
CREATE TABLE IF NOT EXISTS `POST_LIKES` (
`POST_LIKE_ID` int(10) unsigned NOT NULL,
  `POST_ID` int(10) unsigned NOT NULL,
  `LIKED_BY` int(10) unsigned DEFAULT NULL,
  `LIKED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `IP_ADDRESS` varchar(20) COLLATE latin1_general_cs DEFAULT NULL,
  `POST_LIKE_STATUS` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Records of likes by users' AUTO_INCREMENT=1 ;

--
-- Triggers `POST_LIKES`
--
DROP TRIGGER IF EXISTS `POST_LIKES_BU`;
DELIMITER //
CREATE TRIGGER `POST_LIKES_BU` BEFORE UPDATE ON `post_likes`
 FOR EACH ROW BEGIN
IF (OLD.POST_ID <> NEW.POST_ID) OR (OLD.LIKED_BY <> NEW.LIKED_BY) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Post or user id cannot be altered.';
END IF;
IF OLD.IP_ADDRESS <> NEW.IP_ADDRESS THEN
INSERT INTO POST_LIKES_MOD_DET 
SET 
POST_LIKE_ID = OLD.POST_LIKE_ID,
COLUMN_NAME = 'IP_ADDRESS',
COLUMN_VALUE = OLD.IP_ADDRESS,
MODIFIED_BY = NEW.LIKED_BY;
END IF;
IF OLD.POST_LIKE_STATUS <> NEW.POST_LIKE_STATUS THEN
INSERT INTO POST_LIKES_MOD_DET 
SET 
POST_LIKE_ID = OLD.POST_LIKE_ID,
COLUMN_NAME = 'POST_LIKE_STATUS',
COLUMN_VALUE = OLD.POST_LIKE_STATUS,
MODIFIED_BY = NEW.LIKED_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `POST_LIKES_MOD_DET`
--

DROP TABLE IF EXISTS `POST_LIKES_MOD_DET`;
CREATE TABLE IF NOT EXISTS `POST_LIKES_MOD_DET` (
`POST_LIKE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `POST_LIKE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of user likes for posts' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `POST_LOCATION`
--

DROP TABLE IF EXISTS `POST_LOCATION`;
CREATE TABLE IF NOT EXISTS `POST_LOCATION` (
`POST_LOCATION_ID` int(10) unsigned NOT NULL,
  `POST_ID` int(10) unsigned NOT NULL,
  `POST_LOCATION` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(11) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing details of where the post was published' AUTO_INCREMENT=1 ;

--
-- Triggers `POST_LOCATION`
--
DROP TRIGGER IF EXISTS `POST_LOCATION_BU`;
DELIMITER //
CREATE TRIGGER `POST_LOCATION_BU` BEFORE UPDATE ON `post_location`
 FOR EACH ROW BEGIN
IF OLD.POST_ID <> NEW.POST_ID THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Post id cannot be altered.';
END IF;
IF EXISTS (SELECT 1 FROM POST_LOCATION WHERE POST_LOCATION = NEW.POST_LOCATION AND POST_ID = OLD.POST_ID AND NEW.POST_LOCATION <> OLD.POST_LOCATION) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'This post already exists in the updated location.';
END IF;
IF OLD.POST_LOCATION <> NEW.POST_LOCATION THEN
INSERT INTO POST_LOCATION_MOD_DET 
SET 
POST_LOCATION_ID = OLD.POST_LOCATION_ID,
COLUMN_NAME = 'POST_LOCATION',
COLUMN_VALUE = OLD.POST_LOCATION,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
INSERT INTO POST_LOCATION_MOD_DET 
SET 
POST_COMMENT_ID = OLD.POST_LOCATION_ID,
COLUMN_NAME = 'STATUS_ID',
COLUMN_VALUE = OLD.STATUS_ID,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `POST_LOCATION_MASTER`
--

DROP TABLE IF EXISTS `POST_LOCATION_MASTER`;
CREATE TABLE IF NOT EXISTS `POST_LOCATION_MASTER` (
`POST_LOCATION_ID` int(11) NOT NULL,
  `POST_LOCATION_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for posting location' AUTO_INCREMENT=6 ;

--
-- Triggers `POST_LOCATION_MASTER`
--
DROP TRIGGER IF EXISTS `POST_LOCATION_MASTER_BU`;
DELIMITER //
CREATE TRIGGER `POST_LOCATION_MASTER_BU` BEFORE UPDATE ON `post_location_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM POST_LOCATION_MASTER WHERE POST_LOCATION_DESCR = NEW.POST_LOCATION_DESCR AND NEW.POST_LOCATION_DESCR <> OLD.POST_LOCATION_DESCR) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate record with identical post location description exists, please revise.';
    END IF;
    IF OLD.POST_LOCATION_DESCR <> NEW.POST_LOCATION_DESCR THEN
        INSERT INTO POST_LOCATION_MASTER_MOD_DET 
        SET 
        POST_LOCATION_ID = OLD.POST_LOCATION_ID,
        COLUMN_NAME = 'POST_LOCATION_DESCR',
        COLUMN_VALUE = OLD.POST_LOCATION_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO POST_LOCATION_MASTER_MOD_DET 
        SET 
        POST_LOCATION_ID = OLD.POST_LOCATION_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `POST_LOCATION_MASTER_MOD_DET`
--

DROP TABLE IF EXISTS `POST_LOCATION_MASTER_MOD_DET`;
CREATE TABLE IF NOT EXISTS `POST_LOCATION_MASTER_MOD_DET` (
`POST_LOCATION_MASTER_MOD_DET_ID` int(10) unsigned NOT NULL,
  `POST_LOCATION_ID` int(10) NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for post locations' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `POST_LOCATION_MOD_DET`
--

DROP TABLE IF EXISTS `POST_LOCATION_MOD_DET`;
CREATE TABLE IF NOT EXISTS `POST_LOCATION_MOD_DET` (
`POST_LOCATION_MOD_DET_ID` int(10) unsigned NOT NULL,
  `POST_LOCATION_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for post location' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `POST_MOD_DET`
--

DROP TABLE IF EXISTS `POST_MOD_DET`;
CREATE TABLE IF NOT EXISTS `POST_MOD_DET` (
`POST_MOD_DET_ID` int(10) unsigned NOT NULL,
  `POST_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of user posts' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `POST_PRIVACY_MOD_DET`
--

DROP TABLE IF EXISTS `POST_PRIVACY_MOD_DET`;
CREATE TABLE IF NOT EXISTS `POST_PRIVACY_MOD_DET` (
`POST_PRIVACY_MOD_DET_ID` int(10) unsigned NOT NULL,
  `POST_PRIVACY_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for privacy settings for individual posts' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `POST_PRIVACY_SETTINGS`
--

DROP TABLE IF EXISTS `POST_PRIVACY_SETTINGS`;
CREATE TABLE IF NOT EXISTS `POST_PRIVACY_SETTINGS` (
`POST_PRIVACY_ID` int(10) unsigned NOT NULL,
  `POST_ID` int(10) unsigned NOT NULL,
  `USER_TYPE_ID` int(11) DEFAULT NULL,
  `PRIVACY_ID` int(11) NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned zerofill DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs AUTO_INCREMENT=1 ;

--
-- Triggers `POST_PRIVACY_SETTINGS`
--
DROP TRIGGER IF EXISTS `POST_PRIVACY_BU`;
DELIMITER //
CREATE TRIGGER `POST_PRIVACY_BU` BEFORE UPDATE ON `post_privacy_settings`
 FOR EACH ROW BEGIN
IF OLD.POST_ID <> NEW.POST_ID THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Post id cannot be altered.';
END IF;
IF EXISTS (SELECT 1 FROM POST_PRIVACY_SETTINGS WHERE USER_TYPE_ID = NEW.USER_TYPE_ID AND POST_ID = OLD.POST_ID AND NEW.USER_TYPE_ID <> OLD.USER_TYPE_ID) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Privacy settings for this post and user type already exists.';
END IF;
IF OLD.USER_TYPE_ID <> NEW.USER_TYPE_ID THEN
INSERT INTO POST_PRIVACY_MOD_DET 
SET 
POST_PRIVACY_ID = OLD.POST_PRIVACY_ID,
COLUMN_NAME = 'USER_TYPE_ID',
COLUMN_VALUE = OLD.USER_TYPE_ID,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
IF OLD.PRIVACY_ID <> NEW.PRIVACY_ID THEN
INSERT INTO POST_PRIVACY_MOD_DET 
SET 
POST_PRIVACY_ID = OLD.POST_PRIVACY_ID,
COLUMN_NAME = 'PRIVACY_ID',
COLUMN_VALUE = OLD.PRIVACY_ID,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
INSERT INTO POST_PRIVACY_MOD_DET 
SET 
POST_PRIVACY_ID = OLD.POST_PRIVACY_ID,
COLUMN_NAME = 'STATUS_ID',
COLUMN_VALUE = OLD.STATUS_ID,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `POST_TYPE_MASTER`
--

DROP TABLE IF EXISTS `POST_TYPE_MASTER`;
CREATE TABLE IF NOT EXISTS `POST_TYPE_MASTER` (
`POST_TYPE_ID` int(11) NOT NULL,
  `POST_TYPE_TEXT` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs AUTO_INCREMENT=1 ;

--
-- Triggers `POST_TYPE_MASTER`
--
DROP TRIGGER IF EXISTS `POST_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `POST_TYPE_BU` BEFORE UPDATE ON `post_type_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM POST_TYPE_MASTER WHERE POST_TYPE_TEXT = NEW.POST_TYPE_TEXT AND NEW.POST_TYPE_TEXT <> OLD.POST_TYPE_TEXT) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical post type already exists, please revise.';
    END IF;
    IF OLD.POST_TYPE_TEXT <> NEW.POST_TYPE_TEXT THEN
        INSERT INTO POST_TYPE_MOD_DET 
        SET 
        POST_TYPE_ID = OLD.POST_TYPE_ID,
        COLUMN_NAME = 'POST_TYPE_TEXT',
        COLUMN_VALUE = OLD.POST_TYPE_TEXT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO POST_TYPE_MOD_DET 
        SET 
        POST_TYPE_ID = OLD.POST_TYPE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `POST_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `POST_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `POST_TYPE_MOD_DET` (
`POST_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `POST_TYPE_ID` int(10) NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of post types' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `PRIVACY_MASTER`
--

DROP TABLE IF EXISTS `PRIVACY_MASTER`;
CREATE TABLE IF NOT EXISTS `PRIVACY_MASTER` (
`PRIVACY_ID` int(11) NOT NULL,
  `PRIVACY_TEXT` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for privacy levels' AUTO_INCREMENT=4 ;

--
-- Triggers `PRIVACY_MASTER`
--
DROP TRIGGER IF EXISTS `PRIVACY_BU`;
DELIMITER //
CREATE TRIGGER `PRIVACY_BU` BEFORE UPDATE ON `privacy_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM PRIVACY_MASTER WHERE PRIVACY_TEXT = NEW.PRIVACY_TEXT AND NEW.PRIVACY_TEXT <> OLD.PRIVACY_TEXT) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical privacy level description exists, please revise.';
    END IF;
    IF OLD.PRIVACY_TEXT <> NEW.PRIVACY_TEXT THEN
        INSERT INTO PRIVACY_MOD_DET 
        SET 
        PRIVACY_ID = OLD.PRIVACY_ID,
        COLUMN_NAME = 'PRIVACY_TEXT',
        COLUMN_VALUE = OLD.PRIVACY_TEXT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO PRIVACY_MOD_DET 
        SET 
        PRIVACY_ID = OLD.PRIVACY_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `PRIVACY_MOD_DET`
--

DROP TABLE IF EXISTS `PRIVACY_MOD_DET`;
CREATE TABLE IF NOT EXISTS `PRIVACY_MOD_DET` (
`PRIVACY_MOD_DET_ID` int(10) unsigned NOT NULL,
  `PRIVACY_ID` int(11) NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of privacy levels' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `PUBLISH_TYPE_MASTER`
--

DROP TABLE IF EXISTS `PUBLISH_TYPE_MASTER`;
CREATE TABLE IF NOT EXISTS `PUBLISH_TYPE_MASTER` (
`PUBLISH_TYPE_ID` int(10) unsigned NOT NULL,
  `PUBLISH_TYPE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table containing event publish types' AUTO_INCREMENT=1 ;

--
-- Triggers `PUBLISH_TYPE_MASTER`
--
DROP TRIGGER IF EXISTS `PUBLISH_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `PUBLISH_TYPE_BU` BEFORE UPDATE ON `publish_type_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM PUBLISH_TYPE_MASTER WHERE PUBLISH_TYPE_DESCR = NEW.PUBLISH_TYPE_DESCR AND NEW.PUBLISH_TYPE_DESCR <> OLD.PUBLISH_TYPE_DESCR) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate record with identical publish type exists, please revise.';
    END IF;
    IF OLD.PUBLISH_TYPE_DESCR <> NEW.PUBLISH_TYPE_DESCR THEN
        INSERT INTO PUBLISH_TYPE_MOD_DET 
        SET 
        PUBLISH_TYPE_ID = OLD.PUBLISH_TYPE_ID,
        COLUMN_NAME = 'PUBLISH_TYPE_DESCR',
        COLUMN_VALUE = OLD.PUBLISH_TYPE_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO PUBLISH_TYPE_MOD_DET 
        SET 
        PUBLISH_TYPE_ID = OLD.PUBLISH_TYPE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `PUBLISH_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `PUBLISH_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `PUBLISH_TYPE_MOD_DET` (
`PUBLISH_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `PUBLISH_TYPE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of event publish type' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `QUESTION_GROUP_MASTER`
--

DROP TABLE IF EXISTS `QUESTION_GROUP_MASTER`;
CREATE TABLE IF NOT EXISTS `QUESTION_GROUP_MASTER` (
`QUESTION_GROUP_ID` int(10) unsigned NOT NULL,
  `QUESTION_GROUP` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all question groups' AUTO_INCREMENT=3 ;

--
-- Triggers `QUESTION_GROUP_MASTER`
--
DROP TRIGGER IF EXISTS `QUESTION_GROUP_BU`;
DELIMITER //
CREATE TRIGGER `QUESTION_GROUP_BU` BEFORE UPDATE ON `question_group_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM QUESTION_GROUP_MASTER WHERE QUESTION_GROUP = NEW.QUESTION_GROUP AND NEW.QUESTION_GROUP <> OLD.QUESTION_GROUP) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical question group exists, please revise.';
    END IF;
    IF OLD.QUESTION_GROUP <> NEW.QUESTION_GROUP THEN
        INSERT INTO QUESTION_GROUP_MOD_DET 
        SET 
        QUESTION_GROUP_ID = OLD.QUESTION_GROUP_ID,
        COLUMN_NAME = 'QUESTION_GROUP',
        COLUMN_VALUE = OLD.QUESTION_GROUP,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO QUESTION_GROUP_MOD_DET 
        SET 
        QUESTION_GROUP_ID = OLD.QUESTION_GROUP_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `QUESTION_GROUP_MOD_DET`
--

DROP TABLE IF EXISTS `QUESTION_GROUP_MOD_DET`;
CREATE TABLE IF NOT EXISTS `QUESTION_GROUP_MOD_DET` (
`QUESTION_GROUP_MOD_DET_ID` int(10) unsigned NOT NULL,
  `QUESTION_GROUP_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of question groups' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `QUESTION_MASTER`
--

DROP TABLE IF EXISTS `QUESTION_MASTER`;
CREATE TABLE IF NOT EXISTS `QUESTION_MASTER` (
`QUESTION_ID` int(10) unsigned NOT NULL,
  `QUESTION_TEXT` varchar(500) COLLATE latin1_general_cs NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `QUESTION_GROUP_ID` int(10) unsigned NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for questions' AUTO_INCREMENT=1 ;

--
-- Triggers `QUESTION_MASTER`
--
DROP TRIGGER IF EXISTS `QUESTION_BU`;
DELIMITER //
CREATE TRIGGER `QUESTION_BU` BEFORE UPDATE ON `question_master`
 FOR EACH ROW BEGIN
    IF OLD.QUESTION_GROUP_ID <> NEW.QUESTION_GROUP_ID THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Question group cannot be altered.';
    END IF;
    IF EXISTS (SELECT 1 FROM QUESTION_MASTER WHERE QUESTION_GROUP_ID = OLD.QUESTION_GROUP_ID AND QUESTION_TEXT = NEW.QUESTION_TEXT AND NEW.QUESTION_TEXT <> OLD.QUESTION_TEXT) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical question for the same question group exists, please revise.';
    END IF;
    IF OLD.QUESTION_TEXT <> NEW.QUESTION_TEXT THEN
        INSERT INTO QUESTION_MOD_DET 
        SET 
        QUESTION_ID = OLD.QUESTION_ID,
        COLUMN_NAME = 'QUESTION_TEXT',
        COLUMN_VALUE = OLD.QUESTION_TEXT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO QUESTION_MOD_DET 
        SET 
        QUESTION_ID = OLD.QUESTION_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `QUESTION_MOD_DET`
--

DROP TABLE IF EXISTS `QUESTION_MOD_DET`;
CREATE TABLE IF NOT EXISTS `QUESTION_MOD_DET` (
`QUESTION_MOD_DET_ID` int(10) unsigned NOT NULL,
  `QUESTION_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(500) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of questions' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `REPEAT_BY_TYPE_MASTER`
--

DROP TABLE IF EXISTS `REPEAT_BY_TYPE_MASTER`;
CREATE TABLE IF NOT EXISTS `REPEAT_BY_TYPE_MASTER` (
`REPEAT_BY_TYPE_ID` int(10) unsigned NOT NULL,
  `REPEAT_BY_TYPE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Event repeat-by types' AUTO_INCREMENT=1 ;

--
-- Triggers `REPEAT_BY_TYPE_MASTER`
--
DROP TRIGGER IF EXISTS `REPEAT_BY_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `REPEAT_BY_TYPE_BU` BEFORE UPDATE ON `repeat_by_type_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM REPEAT_BY_TYPE_MASTER WHERE REPEAT_BY_TYPE_DESCR = NEW.REPEAT_BY_TYPE_DESCR AND NEW.REPEAT_BY_TYPE_DESCR <> OLD.REPEAT_BY_TYPE_DESCR) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate record with identical repeat-by type exists, please revise.';
    END IF;
    IF OLD.REPEAT_BY_TYPE_DESCR <> NEW.REPEAT_BY_TYPE_DESCR THEN
        INSERT INTO REPEAT_BY_TYPE_MOD_DET 
        SET 
        REPEAT_BY_TYPE_ID = OLD.REPEAT_BY_TYPE_ID,
        COLUMN_NAME = 'REPEAT_BY_TYPE_DESCR',
        COLUMN_VALUE = OLD.REPEAT_BY_TYPE_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO REPEAT_BY_TYPE_MOD_DET 
        SET 
        REPEAT_BY_TYPE_ID = OLD.REPEAT_BY_TYPE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `REPEAT_BY_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `REPEAT_BY_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `REPEAT_BY_TYPE_MOD_DET` (
`REPEAT_BY_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `REPEAT_BY_TYPE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of event repeat-by types' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `REPEAT_END_TYPE_MASTER`
--

DROP TABLE IF EXISTS `REPEAT_END_TYPE_MASTER`;
CREATE TABLE IF NOT EXISTS `REPEAT_END_TYPE_MASTER` (
`REPEAT_END_TYPE_ID` int(10) unsigned NOT NULL,
  `REPEAT_END_TYPE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Repeat end types' AUTO_INCREMENT=1 ;

--
-- Triggers `REPEAT_END_TYPE_MASTER`
--
DROP TRIGGER IF EXISTS `REPEAT_END_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `REPEAT_END_TYPE_BU` BEFORE UPDATE ON `repeat_end_type_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM REPEAT_END_TYPE_MASTER WHERE REPEAT_END_TYPE_DESCR = NEW.REPEAT_END_TYPE_DESCR AND NEW.REPEAT_END_TYPE_DESCR <> OLD.REPEAT_END_TYPE_DESCR) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate record with identical repeat end type exists, please revise.';
    END IF;
    IF OLD.REPEAT_END_TYPE_DESCR <> NEW.REPEAT_END_TYPE_DESCR THEN
        INSERT INTO REPEAT_END_TYPE_MOD_DET 
        SET 
        REPEAT_END_TYPE_ID = OLD.REPEAT_END_TYPE_ID,
        COLUMN_NAME = 'REPEAT_END_TYPE_DESCR',
        COLUMN_VALUE = OLD.REPEAT_END_TYPE_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO REPEAT_END_TYPE_MOD_DET 
        SET 
        REPEAT_END_TYPE_ID = OLD.REPEAT_END_TYPE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `REPEAT_END_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `REPEAT_END_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `REPEAT_END_TYPE_MOD_DET` (
`REPEAT_END_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `REPEAT_END_TYPE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of event repeat end type' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `REPEAT_MODE_TYPE_MASTER`
--

DROP TABLE IF EXISTS `REPEAT_MODE_TYPE_MASTER`;
CREATE TABLE IF NOT EXISTS `REPEAT_MODE_TYPE_MASTER` (
`REPEAT_MODE_TYPE_ID` int(10) unsigned NOT NULL,
  `REPEAT_MODE_TYPE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of event repeat modes' AUTO_INCREMENT=1 ;

--
-- Triggers `REPEAT_MODE_TYPE_MASTER`
--
DROP TRIGGER IF EXISTS `REPEAT_MODE_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `REPEAT_MODE_TYPE_BU` BEFORE UPDATE ON `repeat_mode_type_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM REPEAT_MODE_TYPE_MASTER WHERE REPEAT_MODE_TYPE_DESCR = NEW.REPEAT_MODE_TYPE_DESCR AND NEW.REPEAT_MODE_TYPE_DESCR <> OLD.REPEAT_MODE_TYPE_DESCR) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate record with identical repeat mode type exists, please revise.';
    END IF;
    IF OLD.REPEAT_MODE_TYPE_DESCR <> NEW.REPEAT_MODE_TYPE_DESCR THEN
        INSERT INTO REPEAT_MODE_TYPE_MOD_DET 
        SET 
        REPEAT_MODE_TYPE_ID = OLD.REPEAT_MODE_TYPE_ID,
        COLUMN_NAME = 'REPEAT_MODE_TYPE_DESCR',
        COLUMN_VALUE = OLD.REPEAT_MODE_TYPE_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO REPEAT_MODE_TYPE_MOD_DET 
        SET 
        REPEAT_MODE_TYPE_ID = OLD.REPEAT_MODE_TYPE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `REPEAT_MODE_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `REPEAT_MODE_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `REPEAT_MODE_TYPE_MOD_DET` (
`REPEAT_MODE_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `REPEAT_MODE_TYPE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of repeat mode types' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `REPEAT_TYPE_MASTER`
--

DROP TABLE IF EXISTS `REPEAT_TYPE_MASTER`;
CREATE TABLE IF NOT EXISTS `REPEAT_TYPE_MASTER` (
`REPEAT_TYPE_ID` int(10) unsigned NOT NULL,
  `REPEAT_TYPE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='List of different repeating frequency for events' AUTO_INCREMENT=1 ;

--
-- Triggers `REPEAT_TYPE_MASTER`
--
DROP TRIGGER IF EXISTS `REPEAT_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `REPEAT_TYPE_BU` BEFORE UPDATE ON `repeat_type_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM REPEAT_TYPE_MASTER WHERE REPEAT_TYPE_DESCR = NEW.REPEAT_TYPE_DESCR AND NEW.REPEAT_TYPE_DESCR <> OLD.REPEAT_TYPE_DESCR) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate record with identical repeat type exists, please revise.';
    END IF;
    IF OLD.REPEAT_TYPE_DESCR <> NEW.REPEAT_TYPE_DESCR THEN
        INSERT INTO REPEAT_TYPE_MOD_DET 
        SET 
        REPEAT_TYPE_ID = OLD.REPEAT_TYPE_ID,
        COLUMN_NAME = 'REPEAT_TYPE_DESCR',
        COLUMN_VALUE = OLD.REPEAT_TYPE_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO REPEAT_TYPE_MOD_DET 
        SET 
        REPEAT_TYPE_ID = OLD.REPEAT_TYPE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `REPEAT_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `REPEAT_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `REPEAT_TYPE_MOD_DET` (
`REPEAT_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `REPEAT_TYPE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of repeat type frequencies' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `SECTION_TYPE_MASTER`
--

DROP TABLE IF EXISTS `SECTION_TYPE_MASTER`;
CREATE TABLE IF NOT EXISTS `SECTION_TYPE_MASTER` (
`SECTION_TYPE_ID` int(10) unsigned NOT NULL,
  `SECTION_TYPE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Event section type' AUTO_INCREMENT=1 ;

--
-- Triggers `SECTION_TYPE_MASTER`
--
DROP TRIGGER IF EXISTS `SECTION_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `SECTION_TYPE_BU` BEFORE UPDATE ON `section_type_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM SECTION_TYPE_MASTER WHERE SECTION_TYPE_DESCR = NEW.SECTION_TYPE_DESCR AND NEW.SECTION_TYPE_DESCR <> OLD.SECTION_TYPE_DESCR) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate record with identical section type exists, please revise.';
    END IF;
    IF OLD.SECTION_TYPE_DESCR <> NEW.SECTION_TYPE_DESCR THEN
        INSERT INTO SECTION_TYPE_MOD_DET 
        SET 
        SECTION_TYPE_ID = OLD.SECTION_TYPE_ID,
        COLUMN_NAME = 'SECTION_TYPE_DESCR',
        COLUMN_VALUE = OLD.SECTION_TYPE_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO SECTION_TYPE_MOD_DET 
        SET 
        SECTION_TYPE_ID = OLD.SECTION_TYPE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `SECTION_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `SECTION_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `SECTION_TYPE_MOD_DET` (
`SECTION_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `SECTION_TYPE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of event section types' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `STATES_MASTER`
--

DROP TABLE IF EXISTS `STATES_MASTER`;
CREATE TABLE IF NOT EXISTS `STATES_MASTER` (
`STATE_ID` int(11) NOT NULL,
  `COUNTRY_ID` int(11) DEFAULT NULL,
  `DESCRIPTION` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `SHORT_DESCRIPTION` varchar(15) COLLATE latin1_general_cs DEFAULT NULL,
  `CREATED_DATETIME` datetime DEFAULT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for states' AUTO_INCREMENT=3721 ;

--
-- Triggers `STATES_MASTER`
--
DROP TRIGGER IF EXISTS `STATES_BU`;
DELIMITER //
CREATE TRIGGER `STATES_BU` BEFORE UPDATE ON `states_master`
 FOR EACH ROW BEGIN
    IF OLD.COUNTRY_ID <> NEW.COUNTRY_ID THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Assigned country cannot be altered.';
    END IF;
    IF EXISTS (SELECT 1 FROM STATES_MASTER WHERE COUNTRY_ID = OLD.COUNTRY_ID AND DESCRIPTION = NEW.DESCRIPTION AND NEW.DESCRIPTION <> OLD.DESCRIPTION) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical state already exists for the assigned country, please revise.';
    END IF;
    IF OLD.DESCRIPTION <> NEW.DESCRIPTION THEN
        INSERT INTO STATES_MOD_DET 
        SET 
        STATE_ID = OLD.STATE_ID,
        COLUMN_NAME = 'DESCRIPTION',
        COLUMN_VALUE = OLD.DESCRIPTION,
        MODIFIED_BY = NEW.MODIFIED_BY;
    END IF;
    IF OLD.SHORT_DESCRIPTION <> NEW.SHORT_DESCRIPTION THEN
        INSERT INTO STATES_MOD_DET 
        SET 
        STATE_ID = OLD.STATE_ID,
        COLUMN_NAME = 'SHORT_DESCRIPTION',
        COLUMN_VALUE = OLD.SHORT_DESCRIPTION,
        MODIFIED_BY = NEW.MODIFIED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO STATES_MOD_DET 
        SET 
        STATE_ID = OLD.STATE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.MODIFIED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `STATES_MOD_DET`
--

DROP TABLE IF EXISTS `STATES_MOD_DET`;
CREATE TABLE IF NOT EXISTS `STATES_MOD_DET` (
`STATES_MOD_DET_ID` int(10) unsigned NOT NULL,
  `STATE_ID` int(11) NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of states' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `STATUS`
--

DROP TABLE IF EXISTS `STATUS`;
CREATE TABLE IF NOT EXISTS `STATUS` (
`STATUS_ID` int(11) NOT NULL,
  `STATUS` varchar(100) COLLATE latin1_general_cs NOT NULL COMMENT 'Status text',
  `STATUS_TYPE_ID` int(11) NOT NULL COMMENT 'Foreign key to Status Type',
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for status' AUTO_INCREMENT=53 ;

--
-- Triggers `STATUS`
--
DROP TRIGGER IF EXISTS `STATUS_BU`;
DELIMITER //
CREATE TRIGGER `STATUS_BU` BEFORE UPDATE ON `status`
 FOR EACH ROW BEGIN
    IF OLD.STATUS_TYPE_ID <> NEW.STATUS_TYPE_ID THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Status type cannot be altered.';
    END IF;
    IF EXISTS (SELECT 1 FROM STATUS WHERE STATUS_TYPE_ID = OLD.STATUS_TYPE_ID AND STATUS = NEW.STATUS AND NEW.STATUS <> OLD.STATUS) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical status exists for the same status type, please revise.';
    END IF;
    IF OLD.STATUS <> NEW.STATUS THEN
        INSERT INTO STATUS_MOD_DET 
        SET 
        STATUS_ID = OLD.STATUS_ID,
        COLUMN_NAME = 'STATUS',
        COLUMN_VALUE = OLD.STATUS,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `STATUS_MOD_DET`
--

DROP TABLE IF EXISTS `STATUS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `STATUS_MOD_DET` (
`STATUS_MOD_DET_ID` int(10) unsigned NOT NULL,
  `STATUS_ID` int(11) NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of status' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `STATUS_TYPE`
--

DROP TABLE IF EXISTS `STATUS_TYPE`;
CREATE TABLE IF NOT EXISTS `STATUS_TYPE` (
`STATUS_TYPE_ID` int(11) NOT NULL,
  `STATUS_TYPE` varchar(100) COLLATE latin1_general_cs DEFAULT NULL COMMENT 'Patient Status Type or Active Status Type or some other status type',
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='It will contain the type of status, could be patient status, disease status, etc' AUTO_INCREMENT=26 ;

--
-- Triggers `STATUS_TYPE`
--
DROP TRIGGER IF EXISTS `STATUS_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `STATUS_TYPE_BU` BEFORE UPDATE ON `status_type`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM STATUS_TYPE WHERE STATUS_TYPE = NEW.STATUS_TYPE AND NEW.STATUS_TYPE <> OLD.STATUS_TYPE) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical status type exists, please revise.';
    END IF;
    IF OLD.STATUS_TYPE <> NEW.STATUS_TYPE THEN
        INSERT INTO STATUS_TYPE_MOD_DET 
        SET 
        STATUS_TYPE_ID = OLD.STATUS_TYPE_ID,
        COLUMN_NAME = 'STATUS_TYPE',
        COLUMN_VALUE = OLD.STATUS_TYPE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `STATUS_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `STATUS_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `STATUS_TYPE_MOD_DET` (
`STATUS_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `STATUS_TYPE_ID` int(11) NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of status type' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `SURVEY_MASTER`
--

DROP TABLE IF EXISTS `SURVEY_MASTER`;
CREATE TABLE IF NOT EXISTS `SURVEY_MASTER` (
`SURVEY_ID` int(10) unsigned NOT NULL,
  `SURVEY_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `SURVEY_DESCR` text COLLATE latin1_general_cs,
  `SURVEY_KEY` varchar(100) COLLATE latin1_general_cs DEFAULT NULL,
  `SURVEY_TYPE` int(10) unsigned DEFAULT NULL,
  `SURVEY_STATUS` int(10) DEFAULT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for H4L surveys' AUTO_INCREMENT=1 ;

--
-- Triggers `SURVEY_MASTER`
--
DROP TRIGGER IF EXISTS `SURVEY_BU`;
DELIMITER //
CREATE TRIGGER `SURVEY_BU` BEFORE UPDATE ON `survey_master`
 FOR EACH ROW BEGIN
IF EXISTS (SELECT 1 FROM SURVEY_MASTER WHERE SURVEY_NAME = NEW.SURVEY_NAME AND NEW.SURVEY_NAME <> OLD.SURVEY_NAME) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'A survey with identical name already exists, please revise the survey name.';
END IF;
IF OLD.SURVEY_NAME <> NEW.SURVEY_NAME THEN
INSERT INTO SURVEY_MOD_DET 
SET 
SURVEY_ID = OLD.SURVEY_ID,
COLUMN_NAME = 'SURVEY_NAME',
COLUMN_VALUE = OLD.SURVEY_NAME,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
IF OLD.SURVEY_DESCR <> NEW.SURVEY_DESCR THEN
INSERT INTO SURVEY_MOD_DET 
SET 
SURVEY_ID = OLD.SURVEY_ID,
COLUMN_NAME = 'SURVEY_DESCR',
COLUMN_VALUE = OLD.SURVEY_DESCR,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
IF OLD.SURVEY_KEY <> NEW.SURVEY_KEY THEN
INSERT INTO SURVEY_MOD_DET 
SET 
SURVEY_ID = OLD.SURVEY_ID,
COLUMN_NAME = 'SURVEY_KEY',
COLUMN_VALUE = OLD.SURVEY_KEY,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
IF OLD.SURVEY_TYPE <> NEW.SURVEY_TYPE THEN
INSERT INTO SURVEY_MOD_DET 
SET 
SURVEY_ID = OLD.SURVEY_ID,
COLUMN_NAME = 'SURVEY_TYPE',
COLUMN_VALUE = OLD.SURVEY_TYPE,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
IF OLD.SURVEY_STATUS <> NEW.SURVEY_STATUS THEN
INSERT INTO SURVEY_MOD_DET 
SET 
SURVEY_ID = OLD.SURVEY_ID,
COLUMN_NAME = 'SURVEY_STATUS',
COLUMN_VALUE = OLD.SURVEY_STATUS,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `SURVEY_MOD_DET`
--

DROP TABLE IF EXISTS `SURVEY_MOD_DET`;
CREATE TABLE IF NOT EXISTS `SURVEY_MOD_DET` (
`SURVEY_MOD_DET_ID` int(10) unsigned NOT NULL,
  `SURVEY_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing modification history of surveys' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `SURVEY_QUESTIONS`
--

DROP TABLE IF EXISTS `SURVEY_QUESTIONS`;
CREATE TABLE IF NOT EXISTS `SURVEY_QUESTIONS` (
`SURVEY_QUESTION_ID` int(10) unsigned NOT NULL,
  `SURVEY_ID` int(10) unsigned NOT NULL,
  `QUESTION_ID` int(10) unsigned NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL,
  `IS_SINGLE_CHOICE` tinyint(3) unsigned DEFAULT '1',
  `IS_MULTIPLE_CHOICE` tinyint(3) unsigned DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing all questions for all surveys hosted on H4L' AUTO_INCREMENT=1 ;

--
-- Triggers `SURVEY_QUESTIONS`
--
DROP TRIGGER IF EXISTS `SURVEY_QUESTIONS_BU`;
DELIMITER //
CREATE TRIGGER `SURVEY_QUESTIONS_BU` BEFORE UPDATE ON `survey_questions`
 FOR EACH ROW BEGIN
IF OLD.SURVEY_ID <> NEW.SURVEY_ID THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Survey id cannot be altered.';
END IF;
IF EXISTS (SELECT 1 FROM SURVEY_QUESTIONS WHERE SURVEY_ID = OLD.SURVEY_ID AND QUESTION_ID = NEW.QUESTION_ID AND NEW.QUESTION_ID <> OLD.QUESTION_ID) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'The selected question is already included in the survey.';
END IF;
IF OLD.QUESTION_ID <> NEW.QUESTION_ID THEN
INSERT INTO SURVEY_QUESTIONS_MOD_DET 
SET 
SURVEY_QUESTION_ID = OLD.SURVEY_QUESTION_ID,
COLUMN_NAME = 'QUESTION_ID',
COLUMN_VALUE = OLD.QUESTION_ID,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
INSERT INTO SURVEY_QUESTIONS_MOD_DET 
SET 
SURVEY_QUESTION_ID = OLD.SURVEY_QUESTION_ID,
COLUMN_NAME = 'STATUS_ID',
COLUMN_VALUE = OLD.STATUS_ID,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
IF OLD.IS_SINGLE_CHOICE <> NEW.IS_SINGLE_CHOICE THEN
INSERT INTO SURVEY_QUESTIONS_MOD_DET 
SET 
SURVEY_QUESTION_ID = OLD.SURVEY_QUESTION_ID,
COLUMN_NAME = 'IS_SINGLE_CHOICE',
COLUMN_VALUE = OLD.IS_SINGLE_CHOICE,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
IF OLD.IS_MULTIPLE_CHOICE <> NEW.IS_MULTIPLE_CHOICE THEN
INSERT INTO SURVEY_QUESTIONS_MOD_DET 
SET 
SURVEY_QUESTION_ID = OLD.SURVEY_QUESTION_ID,
COLUMN_NAME = 'IS_MULTIPLE_CHOICE',
COLUMN_VALUE = OLD.IS_MULTIPLE_CHOICE,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `SURVEY_QUESTIONS_ANSWER_CHOICES`
--

DROP TABLE IF EXISTS `SURVEY_QUESTIONS_ANSWER_CHOICES`;
CREATE TABLE IF NOT EXISTS `SURVEY_QUESTIONS_ANSWER_CHOICES` (
`SURVEY_QUESTIONS_ANSWER_CHOICE_ID` int(10) unsigned NOT NULL,
  `SURVEY_QUESTION_ID` int(10) unsigned NOT NULL,
  `ANSWER_CHOICE_TEXT` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing all answer choices for survey questions' AUTO_INCREMENT=1 ;

--
-- Triggers `SURVEY_QUESTIONS_ANSWER_CHOICES`
--
DROP TRIGGER IF EXISTS `SURVEY_QUES_ANSWR_CHOICE_BU`;
DELIMITER //
CREATE TRIGGER `SURVEY_QUES_ANSWR_CHOICE_BU` BEFORE UPDATE ON `survey_questions_answer_choices`
 FOR EACH ROW BEGIN
IF OLD.SURVEY_QUESTION_ID <> NEW.SURVEY_QUESTION_ID THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Survey question id cannot be altered.';
END IF;
IF EXISTS (SELECT 1 FROM SURVEY_QUESTIONS_ANSWER_CHOICES WHERE SURVEY_QUESTION_ID = OLD.SURVEY_QUESTION_ID AND ANSWER_CHOICE_TEXT = NEW.ANSWER_CHOICE_TEXT AND NEW.ANSWER_CHOICE_TEXT <> OLD.ANSWER_CHOICE_TEXT) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Similar answer choice already exists for the survey question, please revise your answer choice text.';
END IF;
IF OLD.ANSWER_CHOICE_TEXT <> NEW.ANSWER_CHOICE_TEXT THEN
INSERT INTO SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET 
SET 
SURVEY_QUESTIONS_ANSWER_CHOICE_ID = OLD.SURVEY_QUESTIONS_ANSWER_CHOICE_ID,
COLUMN_NAME = 'ANSWER_CHOICE_TEXT',
COLUMN_VALUE = OLD.ANSWER_CHOICE_TEXT,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
INSERT INTO SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET 
SET 
SURVEY_QUESTIONS_ANSWER_CHOICE_ID = OLD.SURVEY_QUESTIONS_ANSWER_CHOICE_ID,
COLUMN_NAME = 'STATUS_ID',
COLUMN_VALUE = OLD.STATUS_ID,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET`
--

DROP TABLE IF EXISTS `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET`;
CREATE TABLE IF NOT EXISTS `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET` (
`SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET_ID` int(10) unsigned NOT NULL,
  `SURVEY_QUESTIONS_ANSWER_CHOICE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned zerofill DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing modification history of answer choices for survey questions' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `SURVEY_QUESTIONS_MOD_DET`
--

DROP TABLE IF EXISTS `SURVEY_QUESTIONS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `SURVEY_QUESTIONS_MOD_DET` (
`SURVEY_QUESTIONS_MOD_DET_ID` int(10) unsigned NOT NULL,
  `SURVEY_QUESTION_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Survey question modification history' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `SURVEY_RESULTS_ANSWER_CHOICES`
--

DROP TABLE IF EXISTS `SURVEY_RESULTS_ANSWER_CHOICES`;
CREATE TABLE IF NOT EXISTS `SURVEY_RESULTS_ANSWER_CHOICES` (
`SURVEY_RESULTS_ANSWER_CHOICE_ID` int(10) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `SURVEY_QUESTIONS_ANSWER_CHOICE_ID` int(10) unsigned NOT NULL,
  `RECORDED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing all user responses to choice questions' AUTO_INCREMENT=1 ;

--
-- Triggers `SURVEY_RESULTS_ANSWER_CHOICES`
--
DROP TRIGGER IF EXISTS `SURVEY_RESULT_ANSWR_CHOICE_BU`;
DELIMITER //
CREATE TRIGGER `SURVEY_RESULT_ANSWR_CHOICE_BU` BEFORE UPDATE ON `survey_results_answer_choices`
 FOR EACH ROW BEGIN
IF OLD.USER_ID <> NEW.USER_ID THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Survey responder id cannot be altered.';
END IF;
IF OLD.SURVEY_QUESTIONS_ANSWER_CHOICE_ID <> NEW.SURVEY_QUESTIONS_ANSWER_CHOICE_ID THEN
INSERT INTO SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET 
SET 
SURVEY_RESULTS_ANSWER_CHOICE_ID = OLD.SURVEY_RESULTS_ANSWER_CHOICE_ID,
COLUMN_NAME = 'SURVEY_QUESTIONS_ANSWER_CHOICE_ID',
COLUMN_VALUE = OLD.SURVEY_QUESTIONS_ANSWER_CHOICE_ID,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
INSERT INTO SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET 
SET 
SURVEY_RESULTS_ANSWER_CHOICE_ID = OLD.SURVEY_RESULTS_ANSWER_CHOICE_ID,
COLUMN_NAME = 'STATUS_ID',
COLUMN_VALUE = OLD.STATUS_ID,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET`
--

DROP TABLE IF EXISTS `SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET`;
CREATE TABLE IF NOT EXISTS `SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET` (
`SURVEY_RESULTS_ANSWER_CHOICE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `SURVEY_RESULTS_ANSWER_CHOICE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(11) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for survey answer choices' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `SURVEY_RESULTS_DETAILED_ANSWERS`
--

DROP TABLE IF EXISTS `SURVEY_RESULTS_DETAILED_ANSWERS`;
CREATE TABLE IF NOT EXISTS `SURVEY_RESULTS_DETAILED_ANSWERS` (
`SURVEY_RESULTS_DETAILED_ANSWER_ID` int(10) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `RECORDED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `ANSWER_TEXT` text COLLATE latin1_general_cs,
  `IS_SKIPPED` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `SURVEY_QUESTION_ID` int(10) unsigned NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing detailed answers to survey questions' AUTO_INCREMENT=1 ;

--
-- Triggers `SURVEY_RESULTS_DETAILED_ANSWERS`
--
DROP TRIGGER IF EXISTS `SURVEY_RESULT_DETAILED_ANSWR_BU`;
DELIMITER //
CREATE TRIGGER `SURVEY_RESULT_DETAILED_ANSWR_BU` BEFORE UPDATE ON `survey_results_detailed_answers`
 FOR EACH ROW BEGIN
IF OLD.USER_ID <> NEW.USER_ID THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Survey responder id cannot be altered.';
END IF;
IF EXISTS (SELECT 1 FROM SURVEY_RESULTS_DETAILED_ANSWERS WHERE USER_ID = OLD.USER_ID AND SURVEY_QUESTION_ID = NEW.SURVEY_QUESTION_ID AND NEW.SURVEY_QUESTION_ID <> OLD.SURVEY_QUESTION_ID) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'The selected question has already been responded by the responder.';
END IF;
IF OLD.SURVEY_QUESTION_ID <> NEW.SURVEY_QUESTION_ID THEN
INSERT INTO SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET 
SET 
SURVEY_RESULTS_DETAILED_ANSWER_ID = OLD.SURVEY_RESULTS_DETAILED_ANSWER_ID,
COLUMN_NAME = 'SURVEY_QUESTION_ID',
COLUMN_VALUE = OLD.SURVEY_QUESTION_ID,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
INSERT INTO SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET 
SET 
SURVEY_RESULTS_DETAILED_ANSWER_ID = OLD.SURVEY_RESULTS_DETAILED_ANSWER_ID,
COLUMN_NAME = 'STATUS_ID',
COLUMN_VALUE = OLD.STATUS_ID,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
IF OLD.ANSWER_TEXT <> NEW.ANSWER_TEXT THEN
INSERT INTO SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET 
SET 
SURVEY_RESULTS_DETAILED_ANSWER_ID = OLD.SURVEY_RESULTS_DETAILED_ANSWER_ID,
COLUMN_NAME = 'ANSWER_TEXT',
COLUMN_VALUE = OLD.ANSWER_TEXT,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
IF OLD.IS_SKIPPED <> NEW.IS_SKIPPED THEN
INSERT INTO SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET 
SET 
SURVEY_RESULTS_DETAILED_ANSWER_ID = OLD.SURVEY_RESULTS_DETAILED_ANSWER_ID,
COLUMN_NAME = 'IS_SKIPPED',
COLUMN_VALUE = OLD.IS_SKIPPED,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET`
--

DROP TABLE IF EXISTS `SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET` (
`SURVEY_RESULTS_DETAILED_ANSWER_MOD_DET_ID` int(10) unsigned NOT NULL,
  `SURVEY_RESULTS_DETAILED_ANSWER_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for detailed answers to survey questions' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `SURVEY_TYPE_MASTER`
--

DROP TABLE IF EXISTS `SURVEY_TYPE_MASTER`;
CREATE TABLE IF NOT EXISTS `SURVEY_TYPE_MASTER` (
`SURVEY_TYPE_ID` int(10) unsigned NOT NULL,
  `SURVEY_TYPE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for survey types' AUTO_INCREMENT=3 ;

--
-- Triggers `SURVEY_TYPE_MASTER`
--
DROP TRIGGER IF EXISTS `SURVEY_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `SURVEY_TYPE_BU` BEFORE UPDATE ON `survey_type_master`
 FOR EACH ROW BEGIN
	IF EXISTS (SELECT 1 FROM SURVEY_TYPE_MASTER WHERE SURVEY_TYPE = NEW.SURVEY_TYPE AND NEW.SURVEY_TYPE <> OLD.SURVEY_TYPE) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical survey type exists, please revise.';
    END IF;
    IF OLD.SURVEY_TYPE <> NEW.SURVEY_TYPE THEN
        INSERT INTO SURVEY_TYPE_MOD_DET 
        SET 
        SURVEY_TYPE_ID = OLD.SURVEY_TYPE_ID,
        COLUMN_NAME = 'SURVEY_TYPE',
        COLUMN_VALUE = OLD.SURVEY_TYPE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO SURVEY_TYPE_MOD_DET 
        SET 
        SURVEY_TYPE_ID = OLD.SURVEY_TYPE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `SURVEY_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `SURVEY_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `SURVEY_TYPE_MOD_DET` (
`SURVEY_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `SURVEY_TYPE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of survey type' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `SYMPTOMS_MASTER`
--

DROP TABLE IF EXISTS `SYMPTOMS_MASTER`;
CREATE TABLE IF NOT EXISTS `SYMPTOMS_MASTER` (
`SYMPTOM_ID` int(10) unsigned NOT NULL,
  `SYMPTOM` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `SYMPTOM_DESCR` text COLLATE latin1_general_cs,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for symptoms' AUTO_INCREMENT=1 ;

--
-- Triggers `SYMPTOMS_MASTER`
--
DROP TRIGGER IF EXISTS `SYMPTOMS_BU`;
DELIMITER //
CREATE TRIGGER `SYMPTOMS_BU` BEFORE UPDATE ON `symptoms_master`
 FOR EACH ROW BEGIN
IF EXISTS (SELECT 1 FROM SYMPTOMS_MASTER WHERE SYMPTOM = NEW.SYMPTOM AND NEW.SYMPTOM <> OLD.SYMPTOM) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Duplicate record, please revise symptom name.';
END IF;
IF OLD.SYMPTOM <> NEW.SYMPTOM THEN
INSERT INTO SYMPTOMS_MOD_DET 
SET 
SYMPTOM_ID = OLD.SYMPTOM_ID,
COLUMN_NAME = 'SYMPTOM',
COLUMN_VALUE = OLD.SYMPTOM,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
IF OLD.SYMPTOM_DESCR <> NEW.SYMPTOM_DESCR THEN
INSERT INTO SYMPTOMS_MOD_DET 
SET 
SYMPTOM_ID = OLD.SYMPTOM_ID,
COLUMN_NAME = 'SYMPTOM_DESCR',
COLUMN_VALUE = OLD.SYMPTOM_DESCR,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
INSERT INTO SYMPTOMS_MOD_DET 
SET 
SYMPTOM_ID = OLD.SYMPTOM_ID,
COLUMN_NAME = 'STATUS_ID',
COLUMN_VALUE = OLD.STATUS_ID,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `SYMPTOMS_MOD_DET`
--

DROP TABLE IF EXISTS `SYMPTOMS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `SYMPTOMS_MOD_DET` (
`SYMPTOM_MOD_DET_ID` int(10) unsigned NOT NULL,
  `SYMPTOM_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Symptom modification history' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `TEAMS`
--

DROP TABLE IF EXISTS `TEAMS`;
CREATE TABLE IF NOT EXISTS `TEAMS` (
`TEAM_ID` int(10) unsigned NOT NULL,
  `TEAM_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `TEAM_DESCR` text COLLATE latin1_general_cs,
  `MEMBER_COUNT` int(10) unsigned DEFAULT NULL,
  `PATIENT_ID` int(11) unsigned NOT NULL,
  `CREATED_BY` int(11) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `TEAM_STATUS` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Team information' AUTO_INCREMENT=1 ;

--
-- Triggers `TEAMS`
--
DROP TRIGGER IF EXISTS `TEAM_BU`;
DELIMITER //
CREATE TRIGGER `TEAM_BU` BEFORE UPDATE ON `teams`
 FOR EACH ROW BEGIN
IF OLD.PATIENT_ID <> NEW.PATIENT_ID THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Team owner cannot be altered.';
END IF;
IF EXISTS (SELECT 1 FROM TEAMS WHERE TEAM_NAME = NEW.TEAM_NAME AND PATIENT_ID = OLD.PATIENT_ID AND NEW.TEAM_NAME <> OLD.TEAM_NAME) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Identical team name exists for the team owner, please revise team name.';
END IF;
IF OLD.TEAM_NAME <> NEW.TEAM_NAME THEN
INSERT INTO TEAM_MOD_DET 
SET 
TEAM_ID = OLD.TEAM_ID,
COLUMN_NAME = 'TEAM_NAME',
COLUMN_VALUE = OLD.TEAM_NAME,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
IF OLD.TEAM_DESCR <> NEW.TEAM_DESCR THEN
INSERT INTO TEAM_MOD_DET 
SET 
TEAM_ID = OLD.TEAM_ID,
COLUMN_NAME = 'TEAM_DESCR',
COLUMN_VALUE = OLD.TEAM_DESCR,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
IF OLD.TEAM_STATUS <> NEW.TEAM_STATUS THEN
INSERT INTO TEAM_MOD_DET 
SET 
TEAM_ID = OLD.TEAM_ID,
COLUMN_NAME = 'TEAM_STATUS',
COLUMN_VALUE = OLD.TEAM_STATUS,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
IF OLD.MEMBER_COUNT <> NEW.MEMBER_COUNT THEN
INSERT INTO TEAM_MOD_DET 
SET 
TEAM_ID = OLD.TEAM_ID,
COLUMN_NAME = 'MEMBER_COUNT',
COLUMN_VALUE = OLD.MEMBER_COUNT,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `TEAM_MEMBERS`
--

DROP TABLE IF EXISTS `TEAM_MEMBERS`;
CREATE TABLE IF NOT EXISTS `TEAM_MEMBERS` (
`TEAM_MEMBER_ID` int(10) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `TEAM_ID` int(10) unsigned NOT NULL,
  `MEMBER_STATUS` int(11) DEFAULT NULL,
  `USER_ROLE_ID` int(11) NOT NULL,
  `INVITED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `JOINED_ON` datetime DEFAULT NULL,
  `EMAIL_NOTIFICATION` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `SITE_NOTIFICATION` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='List of team members' AUTO_INCREMENT=1 ;

--
-- Triggers `TEAM_MEMBERS`
--
DROP TRIGGER IF EXISTS `TEAM_MEMBER_BU`;
DELIMITER //
CREATE TRIGGER `TEAM_MEMBER_BU` BEFORE UPDATE ON `team_members`
 FOR EACH ROW BEGIN
IF (OLD.TEAM_ID <> NEW.TEAM_ID) OR (OLD.USER_ID <> NEW.USER_ID) OR (OLD.USER_ROLE_ID <> NEW.USER_ROLE_ID) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Team/team member/member role cannot be altered.';
END IF;
IF OLD.MEMBER_STATUS <> NEW.MEMBER_STATUS THEN
INSERT INTO TEAM_MEMBERS_MOD_DET 
SET 
TEAM_MEMBER_ID = OLD.TEAM_MEMBER_ID,
COLUMN_NAME = 'MEMBER_STATUS',
COLUMN_VALUE = OLD.MEMBER_STATUS,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
IF OLD.EMAIL_NOTIFICATION <> NEW.EMAIL_NOTIFICATION THEN
INSERT INTO TEAM_MEMBERS_MOD_DET 
SET 
TEAM_MEMBER_ID = OLD.TEAM_MEMBER_ID,
COLUMN_NAME = 'EMAIL_NOTIFICATION',
COLUMN_VALUE = OLD.EMAIL_NOTIFICATION,
MODIFIED_BY = NEW.LAST_EDITED_BY;
END IF;
IF OLD.SITE_NOTIFICATION <> NEW.SITE_NOTIFICATION THEN
INSERT INTO TEAM_MEMBERS_MOD_DET 
SET 
TEAM_MEMBER_ID = OLD.TEAM_MEMBER_ID,
COLUMN_NAME = 'SITE_NOTIFICATION',
COLUMN_VALUE = OLD.SITE_NOTIFICATION,
MODIFIED_BY = NEW.SITE_NOTIFICATION;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `TEAM_MEMBERS_MOD_DET`
--

DROP TABLE IF EXISTS `TEAM_MEMBERS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `TEAM_MEMBERS_MOD_DET` (
`TEAM_MEMBER_MOD_DET_ID` int(10) unsigned NOT NULL,
  `TEAM_MEMBER_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing modification history for team members' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `TEAM_MOD_DET`
--

DROP TABLE IF EXISTS `TEAM_MOD_DET`;
CREATE TABLE IF NOT EXISTS `TEAM_MOD_DET` (
`TEAM_MOD_DET_ID` int(10) unsigned NOT NULL,
  `TEAM_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for Teams table' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `TEAM_PRIVACY_SETTINGS`
--

DROP TABLE IF EXISTS `TEAM_PRIVACY_SETTINGS`;
CREATE TABLE IF NOT EXISTS `TEAM_PRIVACY_SETTINGS` (
`TEAM_PRIVACY_SETTING_ID` int(10) unsigned NOT NULL,
  `TEAM_ID` int(10) unsigned NOT NULL,
  `USER_TYPE_ID` int(10) NOT NULL,
  `PRIVACY_ID` int(10) NOT NULL,
  `PRIVACY_SET_BY` int(10) unsigned DEFAULT NULL,
  `PRIVACY_SET_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Privacy settings for individual teams' AUTO_INCREMENT=1 ;

--
-- Triggers `TEAM_PRIVACY_SETTINGS`
--
DROP TRIGGER IF EXISTS `TEAM_PRIVACY_BU`;
DELIMITER //
CREATE TRIGGER `TEAM_PRIVACY_BU` BEFORE UPDATE ON `team_privacy_settings`
 FOR EACH ROW BEGIN
IF (OLD.TEAM_ID <> NEW.TEAM_ID) OR (OLD.USER_TYPE_ID <> NEW.USER_TYPE_ID) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Team/user groups cannot be altered.';
END IF;
IF OLD.PRIVACY_ID <> NEW.PRIVACY_ID THEN
INSERT INTO TEAM_PRIVACY_SETTING_MOD_DET 
SET 
TEAM_PRIVACY_SETTING_ID = OLD.TEAM_PRIVACY_SETTING_ID,
COLUMN_NAME = 'PRIVACY_ID',
COLUMN_VALUE = OLD.PRIVACY_ID,
MODIFIED_BY = NEW.PRIVACY_SET_BY;
END IF;
IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
INSERT INTO TEAM_PRIVACY_SETTING_MOD_DET 
SET 
TEAM_PRIVACY_SETTING_ID = OLD.TEAM_PRIVACY_SETTING_ID,
COLUMN_NAME = 'STATUS_ID',
COLUMN_VALUE = OLD.STATUS_ID,
MODIFIED_BY = NEW.PRIVACY_SET_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `TEAM_PRIVACY_SETTING_MOD_DET`
--

DROP TABLE IF EXISTS `TEAM_PRIVACY_SETTING_MOD_DET`;
CREATE TABLE IF NOT EXISTS `TEAM_PRIVACY_SETTING_MOD_DET` (
`TEAM_PRIVACY_SETTING_MOD_DET_ID` int(10) unsigned NOT NULL,
  `TEAM_PRIVACY_SETTING_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of privacy settings for teams' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `TIMEZONE_MASTER`
--

DROP TABLE IF EXISTS `TIMEZONE_MASTER`;
CREATE TABLE IF NOT EXISTS `TIMEZONE_MASTER` (
`TIMEZONE_ID` int(11) NOT NULL,
  `TIMEZONE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `TIMEZONE_VALUE` varchar(100) COLLATE latin1_general_cs DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for timezones' AUTO_INCREMENT=1 ;

--
-- Triggers `TIMEZONE_MASTER`
--
DROP TRIGGER IF EXISTS `TIMEZONE_BU`;
DELIMITER //
CREATE TRIGGER `TIMEZONE_BU` BEFORE UPDATE ON `timezone_master`
 FOR EACH ROW BEGIN
	IF EXISTS (SELECT 1 FROM TIMEZONE_MASTER WHERE TIMEZONE = NEW.TIMEZONE AND NEW.TIMEZONE <> OLD.TIMEZONE) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical timezone exists, please revise.';
    END IF;
    IF OLD.TIMEZONE <> NEW.TIMEZONE THEN
        INSERT INTO TIMEZONE_MOD_DET 
        SET 
        TIMEZONE_ID = OLD.TIMEZONE_ID,
        COLUMN_NAME = 'TIMEZONE',
        COLUMN_VALUE = OLD.TIMEZONE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.TIMEZONE_VALUE <> NEW.TIMEZONE_VALUE THEN
        INSERT INTO TIMEZONE_MOD_DET 
        SET 
        TIMEZONE_ID = OLD.TIMEZONE_ID,
        COLUMN_NAME = 'TIMEZONE_VALUE',
        COLUMN_VALUE = OLD.TIMEZONE_VALUE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO TIMEZONE_MOD_DET 
        SET 
        TIMEZONE_ID = OLD.TIMEZONE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `TIMEZONE_MOD_DET`
--

DROP TABLE IF EXISTS `TIMEZONE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `TIMEZONE_MOD_DET` (
`TIMEZONE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `TIMEZONE_ID` int(11) NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of timezones' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `TREATMENT_MASTER`
--

DROP TABLE IF EXISTS `TREATMENT_MASTER`;
CREATE TABLE IF NOT EXISTS `TREATMENT_MASTER` (
`TREATMENT_ID` int(10) unsigned NOT NULL,
  `TREATMENT_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `LAST_EDITED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for treatments' AUTO_INCREMENT=1 ;

--
-- Triggers `TREATMENT_MASTER`
--
DROP TRIGGER IF EXISTS `TREATMENT_BU`;
DELIMITER //
CREATE TRIGGER `TREATMENT_BU` BEFORE UPDATE ON `treatment_master`
 FOR EACH ROW BEGIN
IF EXISTS (SELECT 1 FROM TREATMENT_MASTER WHERE TREATMENT_DESCR = NEW.TREATMENT_DESCR AND NEW.TREATMENT_DESCR <> OLD.TREATMENT_DESCR) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Identical treatment description already exists, please revise treatment description.';
END IF;
IF OLD.TREATMENT_DESCR <> NEW.TREATMENT_DESCR THEN
INSERT INTO TREATMENT_MASTER_MOD_DET 
SET 
TREATMENT_ID = OLD.TREATMENT_ID,
COLUMN_NAME = 'TREATMENT_DESCR',
COLUMN_VALUE = OLD.TREATMENT_DESCR,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
INSERT INTO TREATMENT_MASTER_MOD_DET 
SET 
TREATMENT_ID = OLD.TREATMENT_ID,
COLUMN_NAME = 'STATUS_ID',
COLUMN_VALUE = OLD.STATUS_ID,
MODIFIED_BY = NEW.CREATED_BY;
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `TREATMENT_MASTER_MOD_DET`
--

DROP TABLE IF EXISTS `TREATMENT_MASTER_MOD_DET`;
CREATE TABLE IF NOT EXISTS `TREATMENT_MASTER_MOD_DET` (
`TREATMENT_MASTER_MOD_DET_ID` int(10) unsigned NOT NULL,
  `TREATMENT_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for treatments' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `UNIT_OF_MEASUREMENT_MASTER`
--

DROP TABLE IF EXISTS `UNIT_OF_MEASUREMENT_MASTER`;
CREATE TABLE IF NOT EXISTS `UNIT_OF_MEASUREMENT_MASTER` (
`UNIT_ID` int(10) unsigned NOT NULL,
  `UNIT_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `CONV_FACTOR_ENGLISH` float unsigned DEFAULT NULL,
  `CONV_FACTOR_METRIC` float unsigned DEFAULT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for unit of measurement' AUTO_INCREMENT=1 ;

--
-- Triggers `UNIT_OF_MEASUREMENT_MASTER`
--
DROP TRIGGER IF EXISTS `UOM_BU`;
DELIMITER //
CREATE TRIGGER `UOM_BU` BEFORE UPDATE ON `unit_of_measurement_master`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM UNIT_OF_MEASUREMENT_MASTER WHERE UNIT_DESCR = NEW.UNIT_DESCR AND NEW.UNIT_DESCR <> OLD.UNIT_DESCR) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical unit description already exists, please consider revising the unit description.';
    END IF;
    IF OLD.UNIT_DESCR <> NEW.UNIT_DESCR THEN
        INSERT INTO UNIT_OF_MEASUREMENT_MOD_DET 
        SET 
        UNIT_ID = OLD.UNIT_ID,
        COLUMN_NAME = 'UNIT_DESCR',
        COLUMN_VALUE = OLD.UNIT_DESCR,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.CONV_FACTOR_ENGLISH <> NEW.CONV_FACTOR_ENGLISH THEN
        INSERT INTO UNIT_OF_MEASUREMENT_MOD_DET 
        SET 
        UNIT_ID = OLD.UNIT_ID,
        COLUMN_NAME = 'CONV_FACTOR_ENGLISH',
        COLUMN_VALUE = OLD.CONV_FACTOR_ENGLISH,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
        IF OLD.CONV_FACTOR_METRIC <> NEW.CONV_FACTOR_METRIC THEN
        INSERT INTO UNIT_OF_MEASUREMENT_MOD_DET 
        SET 
        UNIT_ID = OLD.UNIT_ID,
        COLUMN_NAME = 'CONV_FACTOR_METRIC',
        COLUMN_VALUE = OLD.CONV_FACTOR_METRIC,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO UNIT_OF_MEASUREMENT_MOD_DET 
        SET 
        UNIT_ID = OLD.UNIT_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `UNIT_OF_MEASUREMENT_MOD_DET`
--

DROP TABLE IF EXISTS `UNIT_OF_MEASUREMENT_MOD_DET`;
CREATE TABLE IF NOT EXISTS `UNIT_OF_MEASUREMENT_MOD_DET` (
`UNIT_OF_MEASUREMENT_MOD_DET_ID` int(10) unsigned NOT NULL,
  `UNIT_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of units table' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `USERS`
--

DROP TABLE IF EXISTS `USERS`;
CREATE TABLE IF NOT EXISTS `USERS` (
`USER_ID` int(10) unsigned NOT NULL,
  `IS_ADMIN` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'If the user is admin?',
  `USERNAME` varchar(30) COLLATE latin1_general_cs NOT NULL,
  `EMAIL` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `PASSWORD` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `PROFILE_PICTURE` varchar(150) COLLATE latin1_general_cs DEFAULT NULL,
  `FIRST_NAME` varchar(50) COLLATE latin1_general_cs DEFAULT NULL,
  `LAST_NAME` varchar(50) COLLATE latin1_general_cs DEFAULT NULL,
  `GENDER` varchar(1) COLLATE latin1_general_cs DEFAULT NULL,
  `DATE_OF_BIRTH` date DEFAULT NULL,
  `ABOUT_ME` varchar(150) COLLATE latin1_general_cs DEFAULT NULL,
  `ZIP` varchar(10) COLLATE latin1_general_cs DEFAULT NULL COMMENT 'Present zip code of residence',
  `STATE` int(11) DEFAULT NULL COMMENT 'Present state of residence',
  `CITY` int(11) DEFAULT NULL COMMENT 'Present city of residence',
  `CREATED` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `NEWSLETTER` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'If the user subscribed for newsletter?',
  `LAST_LOGIN` datetime DEFAULT NULL COMMENT 'Datetime when he/she last logged in',
  `LAST_ACTIVITY` datetime DEFAULT NULL COMMENT 'Datetime of last activity on site',
  `REMEMBER_ME_CODE` varchar(100) COLLATE latin1_general_cs DEFAULT NULL,
  `FORGOT_PASSWORD_CODE` varchar(100) COLLATE latin1_general_cs DEFAULT NULL COMMENT 'Forgot Password Code',
  `LANGUAGE` int(11) unsigned DEFAULT NULL COMMENT 'First language',
  `COUNTRY` int(11) DEFAULT NULL COMMENT 'Present country of residence',
  `USER_TYPE` int(11) DEFAULT NULL,
  `TIMEZONE` int(11) DEFAULT NULL COMMENT 'Timezone of current residence',
  `DASHBOARD_SLIDESHOW_ENABLED` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `HAS_ANONYMOUS_PERMISSION` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `COVER_SLIDESHOW_ENABLED` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `STATUS_ID` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs AUTO_INCREMENT=1 ;

--
-- Triggers `USERS`
--
DROP TRIGGER IF EXISTS `USER_BU`;
DELIMITER //
CREATE TRIGGER `USER_BU` BEFORE UPDATE ON `users`
 FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM USERS WHERE USERNAME = NEW.USERNAME) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'The particular username is already taken, please revise username.';
    END IF;
    IF EXISTS (SELECT 1 FROM USERS WHERE EMAIL = NEW.EMAIL AND NEW.EMAIL <> OLD.EMAIL) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'The email address is already registered with H4L, please login with the provided email or revise your email address.';
    END IF;
    IF OLD.IS_ADMIN <> NEW.IS_ADMIN THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'IS_ADMIN',
        COLUMN_VALUE = OLD.IS_ADMIN,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.USERNAME <> NEW.USERNAME THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'USERNAME',
        COLUMN_VALUE = OLD.USERNAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.EMAIL <> NEW.EMAIL THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'EMAIL',
        COLUMN_VALUE = OLD.EMAIL,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.PASSWORD <> NEW.PASSWORD THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'PASSWORD',
        COLUMN_VALUE = OLD.PASSWORD,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.PROFILE_PICTURE <> NEW.PROFILE_PICTURE THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'PROFILE_PICTURE',
        COLUMN_VALUE = OLD.PROFILE_PICTURE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.FIRST_NAME <> NEW.FIRST_NAME THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'FIRST_NAME',
        COLUMN_VALUE = OLD.FIRST_NAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.LAST_NAME <> NEW.LAST_NAME THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'LAST_NAME',
        COLUMN_VALUE = OLD.LAST_NAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.GENDER <> NEW.GENDER THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'GENDER',
        COLUMN_VALUE = OLD.GENDER,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.DATE_OF_BIRTH <> NEW.DATE_OF_BIRTH THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'DATE_OF_BIRTH',
        COLUMN_VALUE = OLD.DATE_OF_BIRTH,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.ABOUT_ME <> NEW.ABOUT_ME THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'ABOUT_ME',
        COLUMN_VALUE = OLD.ABOUT_ME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.ZIP <> NEW.ZIP THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'ZIP',
        COLUMN_VALUE = OLD.ZIP,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATE <> NEW.STATE THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'STATE',
        COLUMN_VALUE = OLD.STATE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CITY <> NEW.CITY THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'CITY',
        COLUMN_VALUE = OLD.CITY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.NEWSLETTER <> NEW.NEWSLETTER THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'NEWSLETTER',
        COLUMN_VALUE = OLD.NEWSLETTER,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.REMEMBER_ME_CODE <> NEW.REMEMBER_ME_CODE THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'REMEMBER_ME_CODE',
        COLUMN_VALUE = OLD.REMEMBER_ME_CODE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.FORGOT_PASSWORD_CODE <> NEW.FORGOT_PASSWORD_CODE THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'FORGOT_PASSWORD_CODE',
        COLUMN_VALUE = OLD.FORGOT_PASSWORD_CODE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.LANGUAGE <> NEW.LANGUAGE THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'LANGUAGE',
        COLUMN_VALUE = OLD.LANGUAGE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.COUNTRY <> NEW.COUNTRY THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'COUNTRY',
        COLUMN_VALUE = OLD.COUNTRY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.TIMEZONE <> NEW.TIMEZONE THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'TIMEZONE',
        COLUMN_VALUE = OLD.TIMEZONE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.DASHBOARD_SLIDESHOW_ENABLED <> NEW.DASHBOARD_SLIDESHOW_ENABLED THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'DASHBOARD_SLIDESHOW_ENABLED',
        COLUMN_VALUE = OLD.DASHBOARD_SLIDESHOW_ENABLED,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.HAS_ANONYMOUS_PERMISSION <> NEW.HAS_ANONYMOUS_PERMISSION THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'HAS_ANONYMOUS_PERMISSION',
        COLUMN_VALUE = OLD.HAS_ANONYMOUS_PERMISSION,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.COVER_SLIDESHOW_ENABLED <> NEW.COVER_SLIDESHOW_ENABLED THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'COVER_SLIDESHOW_ENABLED',
        COLUMN_VALUE = OLD.COVER_SLIDESHOW_ENABLED,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO USER_MOD_DET 
        SET 
        USER_ID = OLD.USER_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_ACTIVITY_LOGS`
--

DROP TABLE IF EXISTS `USER_ACTIVITY_LOGS`;
CREATE TABLE IF NOT EXISTS `USER_ACTIVITY_LOGS` (
`USER_ACTIVITY_ID` int(10) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `USER_IP_ADDRESS` varchar(20) COLLATE latin1_general_cs NOT NULL,
  `BROWSER_DESCR` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `CONTROLLER_DESCR` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `ACTION_DESCR` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `URL` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(11) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='User activity log' AUTO_INCREMENT=1 ;

--
-- Triggers `USER_ACTIVITY_LOGS`
--
DROP TRIGGER IF EXISTS `ACTIVITY_LOG_BU`;
DELIMITER //
CREATE TRIGGER `ACTIVITY_LOG_BU` BEFORE UPDATE ON `user_activity_logs`
 FOR EACH ROW BEGIN
	IF OLD.USER_ID <> NEW.USER_ID THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'User id cannot be altered.';
    END IF;
    IF OLD.USER_IP_ADDRESS <> NEW.USER_IP_ADDRESS THEN
        INSERT INTO USER_ACTIVITY_MOD_DET 
        SET 
        USER_ACTIVITY_ID = OLD.USER_ACTIVITY_ID,
        COLUMN_NAME = 'USER_IP_ADDRESS',
        COLUMN_VALUE = OLD.USER_IP_ADDRESS,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.BROWSER_DESCR <> NEW.BROWSER_DESCR THEN
        INSERT INTO USER_ACTIVITY_MOD_DET 
        SET 
        USER_ACTIVITY_ID = OLD.USER_ACTIVITY_ID,
        COLUMN_NAME = 'BROWSER_DESCR',
        COLUMN_VALUE = OLD.BROWSER_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.CONTROLLER_DESCR <> NEW.CONTROLLER_DESCR THEN
        INSERT INTO USER_ACTIVITY_MOD_DET 
        SET 
        USER_ACTIVITY_ID = OLD.USER_ACTIVITY_ID,
        COLUMN_NAME = 'CONTROLLER_DESCR',
        COLUMN_VALUE = OLD.CONTROLLER_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.ACTION_DESCR <> NEW.ACTION_DESCR THEN
        INSERT INTO USER_ACTIVITY_MOD_DET 
        SET 
        USER_ACTIVITY_ID = OLD.USER_ACTIVITY_ID,
        COLUMN_NAME = 'ACTION_DESCR',
        COLUMN_VALUE = OLD.ACTION_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.URL <> NEW.URL THEN
        INSERT INTO USER_ACTIVITY_MOD_DET 
        SET 
        USER_ACTIVITY_ID = OLD.USER_ACTIVITY_ID,
        COLUMN_NAME = 'URL',
        COLUMN_VALUE = OLD.URL,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO USER_ACTIVITY_MOD_DET 
        SET 
        USER_ACTIVITY_ID = OLD.USER_ACTIVITY_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_ACTIVITY_MOD_DET`
--

DROP TABLE IF EXISTS `USER_ACTIVITY_MOD_DET`;
CREATE TABLE IF NOT EXISTS `USER_ACTIVITY_MOD_DET` (
`USER_ACTIVITY_MOD_DET_ID` int(10) unsigned NOT NULL,
  `USER_ACTIVITY_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of user activity logs' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_ATTRIBUTES`
--

DROP TABLE IF EXISTS `USER_ATTRIBUTES`;
CREATE TABLE IF NOT EXISTS `USER_ATTRIBUTES` (
`USER_ATTRIBUTE_ID` int(11) NOT NULL,
  `ATTRIBUTE_ID` int(10) unsigned NOT NULL,
  `VALUE` varchar(500) COLLATE latin1_general_cs NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `STATUS_ID` int(11) DEFAULT NULL,
  `EFF_DATE_FROM` datetime DEFAULT CURRENT_TIMESTAMP,
  `EFF_DATE_TO` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='User attribute table' AUTO_INCREMENT=1 ;

--
-- Triggers `USER_ATTRIBUTES`
--
DROP TRIGGER IF EXISTS `USER_ATTRIBUTES_BU`;
DELIMITER //
CREATE TRIGGER `USER_ATTRIBUTES_BU` BEFORE UPDATE ON `user_attributes`
 FOR EACH ROW BEGIN
    IF (OLD.USER_ID <> NEW.USER_ID) OR (OLD.ATTRIBUTE_ID <> NEW.ATTRIBUTE_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'User id or attribute type cannot be altered.';
    END IF;
    IF OLD.VALUE <> NEW.VALUE THEN
        INSERT INTO USER_ATTRIBUTE_MOD_HISTORY 
        SET 
        USER_ATTRIBUTE_ID = OLD.USER_ATTRIBUTE_ID,
        COLUMN_NAME = 'VALUE',
        COLUMN_VALUE = OLD.VALUE,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO USER_ATTRIBUTE_MOD_HISTORY 
        SET 
        USER_ATTRIBUTE_ID = OLD.USER_ATTRIBUTE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.EFF_DATE_FROM <> NEW.EFF_DATE_FROM THEN
        INSERT INTO USER_ATTRIBUTE_MOD_HISTORY 
        SET 
        USER_ATTRIBUTE_ID = OLD.USER_ATTRIBUTE_ID,
        COLUMN_NAME = 'EFF_DATE_FROM',
        COLUMN_VALUE = DATE_FORMAT(OLD.EFF_DATE_FROM,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.EFF_DATE_TO <> NEW.EFF_DATE_TO THEN
        INSERT INTO USER_ATTRIBUTE_MOD_HISTORY 
        SET 
        USER_ATTRIBUTE_ID = OLD.USER_ATTRIBUTE_ID,
        COLUMN_NAME = 'EFF_DATE_FROM',
        COLUMN_VALUE = DATE_FORMAT(OLD.EFF_DATE_TO,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_ATTRIBUTE_MOD_HISTORY`
--

DROP TABLE IF EXISTS `USER_ATTRIBUTE_MOD_HISTORY`;
CREATE TABLE IF NOT EXISTS `USER_ATTRIBUTE_MOD_HISTORY` (
`USER_ATTRIBUTE_MOD_HISTORY_ID` int(11) NOT NULL,
  `MODIFIED_BY` int(10) unsigned NOT NULL,
  `MODIFIED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `USER_ATTRIBUTE_ID` int(11) NOT NULL,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(500) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='User attribute modification history' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_DISEASES`
--

DROP TABLE IF EXISTS `USER_DISEASES`;
CREATE TABLE IF NOT EXISTS `USER_DISEASES` (
`USER_DISEASE_ID` int(10) unsigned NOT NULL,
  `DISEASE_ID` int(10) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `DIAGNOSED_ON` datetime DEFAULT NULL,
  `DIAGNOSED_BY` varchar(200) COLLATE latin1_general_cs DEFAULT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for user''s diseases' AUTO_INCREMENT=1 ;

--
-- Triggers `USER_DISEASES`
--
DROP TRIGGER IF EXISTS `USER_DISEASES_BU`;
DELIMITER //
CREATE TRIGGER `USER_DISEASES_BU` BEFORE UPDATE ON `user_diseases`
 FOR EACH ROW BEGIN
    IF OLD.USER_ID <> NEW.USER_ID THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'User id cannot be altered.';
    END IF;
    IF EXISTS (SELECT 1 FROM USER_DISEASES WHERE USER_ID = OLD.USER_ID AND DISEASE_ID = NEW.DISEASE_ID AND NEW.DISEASE_ID <> OLD.DISEASE_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'There is an existing record for the user with the same disease.';
    END IF;
    IF OLD.DISEASE_ID <> NEW.DISEASE_ID THEN
        INSERT INTO USER_DISEASES_MOD_DET 
        SET 
        USER_DISEASE_ID = OLD.USER_DISEASE_ID,
        COLUMN_NAME = 'DISEASE_ID',
        COLUMN_VALUE = OLD.DISEASE_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.DIAGNOSED_BY <> NEW.DIAGNOSED_BY THEN
        INSERT INTO USER_DISEASES_MOD_DET 
        SET 
        USER_DISEASE_ID = OLD.USER_DISEASE_ID,
        COLUMN_NAME = 'DIAGNOSED_BY',
        COLUMN_VALUE = OLD.DIAGNOSED_BY,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.DIAGNOSED_ON <> NEW.DIAGNOSED_ON THEN
        INSERT INTO USER_DISEASES_MOD_DET 
        SET 
        USER_DISEASE_ID = OLD.USER_DISEASE_ID,
        COLUMN_NAME = 'DIAGNOSED_ON',
        COLUMN_VALUE = DATE_FORMAT(OLD.DIAGNOSED_ON,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO USER_DISEASES_MOD_DET 
        SET 
        USER_DISEASE_ID = OLD.USER_DISEASE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_DISEASES_MOD_DET`
--

DROP TABLE IF EXISTS `USER_DISEASES_MOD_DET`;
CREATE TABLE IF NOT EXISTS `USER_DISEASES_MOD_DET` (
`USER_DISEASES_MOD_DET_ID` int(10) unsigned NOT NULL,
  `USER_DISEASE_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing modification history for user''s diseases' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_FAVORITE_POSTS`
--

DROP TABLE IF EXISTS `USER_FAVORITE_POSTS`;
CREATE TABLE IF NOT EXISTS `USER_FAVORITE_POSTS` (
`USER_FAVORITE_POST_ID` int(11) NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `POST_ID` int(10) unsigned NOT NULL,
  `LIKED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs AUTO_INCREMENT=1 ;

--
-- Triggers `USER_FAVORITE_POSTS`
--
DROP TRIGGER IF EXISTS `USER_FAV_POSTS_BU`;
DELIMITER //
CREATE TRIGGER `USER_FAV_POSTS_BU` BEFORE UPDATE ON `user_favorite_posts`
 FOR EACH ROW BEGIN
    IF (OLD.USER_ID <> NEW.USER_ID) OR (OLD.POST_ID <> NEW.POST_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'User or post id cannot be altered.';
    END IF;
    IF OLD.LIKED_ON <> NEW.LIKED_ON THEN
        INSERT INTO USER_FAV_POSTS_MOD_DET 
        SET 
        USER_FAVORITE_POST_ID = OLD.USER_FAVORITE_POST_ID,
        COLUMN_NAME = 'LIKED_ON',
        COLUMN_VALUE = DATE_FORMAT(OLD.LIKED_ON,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO USER_FAV_POSTS_MOD_DET 
        SET 
        USER_FAVORITE_POST_ID = OLD.USER_FAVORITE_POST_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_FAV_POSTS_MOD_DET`
--

DROP TABLE IF EXISTS `USER_FAV_POSTS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `USER_FAV_POSTS_MOD_DET` (
`USER_FAV_POST_MOD_DET_ID` int(10) unsigned NOT NULL,
  `USER_FAVORITE_POST_ID` int(10) NOT NULL,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for user''s favorite posts' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_HEALTH_HISTORY_DET`
--

DROP TABLE IF EXISTS `USER_HEALTH_HISTORY_DET`;
CREATE TABLE IF NOT EXISTS `USER_HEALTH_HISTORY_DET` (
`USER_HEALTH_HISTORY_DET_ID` int(11) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `NOTES` text COLLATE latin1_general_cs,
  `FROM_DATE` datetime DEFAULT NULL,
  `TO_DATE` datetime DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `HEALTH_CONDITION_ID` int(11) NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='User health history details' AUTO_INCREMENT=1 ;

--
-- Triggers `USER_HEALTH_HISTORY_DET`
--
DROP TRIGGER IF EXISTS `USER_HEALTH_HISTORY_BU`;
DELIMITER //
CREATE TRIGGER `USER_HEALTH_HISTORY_BU` BEFORE UPDATE ON `user_health_history_det`
 FOR EACH ROW BEGIN
    IF (OLD.USER_ID <> NEW.USER_ID) OR (OLD.HEALTH_CONDITION_ID <> NEW.HEALTH_CONDITION_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'User or health condition cannot be altered.';
    END IF;
    IF OLD.NOTES <> NEW.NOTES THEN
        INSERT INTO USER_HEALTH_HISTORY_MOD_DET 
        SET 
        USER_HEALTH_HISTORY_DET_ID = OLD.USER_HEALTH_HISTORY_DET_ID,
        COLUMN_NAME = 'NOTES',
        COLUMN_VALUE = OLD.NOTES,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.FROM_DATE <> NEW.FROM_DATE THEN
        INSERT INTO USER_HEALTH_HISTORY_MOD_DET 
        SET 
        USER_HEALTH_HISTORY_DET_ID = OLD.USER_HEALTH_HISTORY_DET_ID,
        COLUMN_NAME = 'FROM_DATE',
        COLUMN_VALUE = DATE_FORMAT(OLD.FROM_DATE,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.TO_DATE <> NEW.TO_DATE THEN
        INSERT INTO USER_HEALTH_HISTORY_MOD_DET 
        SET 
        USER_HEALTH_HISTORY_DET_ID = OLD.USER_HEALTH_HISTORY_DET_ID,
        COLUMN_NAME = 'TO_DATE',
        COLUMN_VALUE = DATE_FORMAT(OLD.TO_DATE,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO USER_HEALTH_HISTORY_MOD_DET 
        SET 
        USER_HEALTH_HISTORY_DET_ID = OLD.USER_HEALTH_HISTORY_DET_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_HEALTH_HISTORY_MOD_DET`
--

DROP TABLE IF EXISTS `USER_HEALTH_HISTORY_MOD_DET`;
CREATE TABLE IF NOT EXISTS `USER_HEALTH_HISTORY_MOD_DET` (
`USER_HEALTH_HISTORY_MOD_DET_ID` int(10) unsigned NOT NULL,
  `USER_HEALTH_HISTORY_DET_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned NOT NULL,
  `MODIFIED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for individual records in user health history' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_HEALTH_READING`
--

DROP TABLE IF EXISTS `USER_HEALTH_READING`;
CREATE TABLE IF NOT EXISTS `USER_HEALTH_READING` (
`USER_HEALTH_READING_ID` int(10) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `ATTRIBUTE_TYPE_ID` int(10) unsigned NOT NULL,
  `ATTRIBUTE_VALUE` int(11) NOT NULL,
  `UNIT_ID` int(10) unsigned NOT NULL,
  `RECORD_DESCR` text COLLATE latin1_general_cs,
  `DATE_RECORDED_ON` int(10) unsigned DEFAULT NULL,
  `MONTH_RECORDED_ON` int(10) unsigned DEFAULT NULL,
  `YEAR_RECORDED_ON` int(10) unsigned DEFAULT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Details of user health readings' AUTO_INCREMENT=1 ;

--
-- Triggers `USER_HEALTH_READING`
--
DROP TRIGGER IF EXISTS `USER_HEALTH_READING_BU`;
DELIMITER //
CREATE TRIGGER `USER_HEALTH_READING_BU` BEFORE UPDATE ON `user_health_reading`
 FOR EACH ROW BEGIN
    IF (OLD.USER_ID <> NEW.USER_ID) OR (OLD.ATTRIBUTE_TYPE_ID <> NEW.ATTRIBUTE_TYPE_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'User or attribute type cannot be altered.';
    END IF;
    IF OLD.ATTRIBUTE_VALUE <> NEW.ATTRIBUTE_VALUE THEN
        INSERT INTO USER_HEALTH_READING_MOD_DET 
        SET 
        USER_HEALTH_READING_ID = OLD.USER_HEALTH_READING_ID,
        COLUMN_NAME = 'ATTRIBUTE_VALUE',
        COLUMN_VALUE = OLD.ATTRIBUTE_VALUE,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.UNIT_ID <> NEW.UNIT_ID THEN
        INSERT INTO USER_HEALTH_READING_MOD_DET 
        SET 
        USER_HEALTH_READING_ID = OLD.USER_HEALTH_READING_ID,
        COLUMN_NAME = 'UNIT_ID',
        COLUMN_VALUE = OLD.UNIT_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.RECORD_DESCR <> NEW.RECORD_DESCR THEN
        INSERT INTO USER_HEALTH_READING_MOD_DET 
        SET 
        USER_HEALTH_READING_ID = OLD.USER_HEALTH_READING_ID,
        COLUMN_NAME = 'RECORD_DESCR',
        COLUMN_VALUE = OLD.RECORD_DESCR,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.DATE_RECORDED_ON <> NEW.DATE_RECORDED_ON THEN
        INSERT INTO USER_HEALTH_READING_MOD_DET 
        SET 
        USER_HEALTH_READING_ID = OLD.USER_HEALTH_READING_ID,
        COLUMN_NAME = 'DATE_RECORDED_ON',
        COLUMN_VALUE = OLD.DATE_RECORDED_ON,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.MONTH_RECORDED_ON <> NEW.MONTH_RECORDED_ON THEN
        INSERT INTO USER_HEALTH_READING_MOD_DET 
        SET 
        USER_HEALTH_READING_ID = OLD.USER_HEALTH_READING_ID,
        COLUMN_NAME = 'MONTH_RECORDED_ON',
        COLUMN_VALUE = OLD.MONTH_RECORDED_ON,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.YEAR_RECORDED_ON <> NEW.YEAR_RECORDED_ON THEN
        INSERT INTO USER_HEALTH_READING_MOD_DET 
        SET 
        USER_HEALTH_READING_ID = OLD.USER_HEALTH_READING_ID,
        COLUMN_NAME = 'YEAR_RECORDED_ON',
        COLUMN_VALUE = OLD.YEAR_RECORDED_ON,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO USER_HEALTH_READING_MOD_DET 
        SET 
        USER_HEALTH_READING_ID = OLD.USER_HEALTH_READING_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_HEALTH_READING_MOD_DET`
--

DROP TABLE IF EXISTS `USER_HEALTH_READING_MOD_DET`;
CREATE TABLE IF NOT EXISTS `USER_HEALTH_READING_MOD_DET` (
`USER_HEALTH_READING_MOD_DET_ID` int(10) unsigned NOT NULL,
  `USER_HEALTH_READING_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of user health readings' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_MEDIA`
--

DROP TABLE IF EXISTS `USER_MEDIA`;
CREATE TABLE IF NOT EXISTS `USER_MEDIA` (
`USER_MEDIA_ID` int(10) unsigned NOT NULL,
  `MEDIA_TYPE_ID` int(10) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL,
  `IS_DELETED` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all user generated media' AUTO_INCREMENT=1 ;

--
-- Triggers `USER_MEDIA`
--
DROP TRIGGER IF EXISTS `USER_MEDIA_BU`;
DELIMITER //
CREATE TRIGGER `USER_MEDIA_BU` BEFORE UPDATE ON `user_media`
 FOR EACH ROW BEGIN
    IF (OLD.USER_ID <> NEW.USER_ID) OR (OLD.MEDIA_TYPE_ID <> NEW.MEDIA_TYPE_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'User or media type cannot be altered.';
    END IF;
    IF OLD.IS_DELETED <> NEW.IS_DELETED THEN
        INSERT INTO USER_MEDIA_MOD_DET 
        SET 
        USER_MEDIA_ID = OLD.USER_MEDIA_ID,
        COLUMN_NAME = 'IS_DELETED',
        COLUMN_VALUE = OLD.IS_DELETED,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO USER_MEDIA_MOD_DET 
        SET 
        USER_MEDIA_ID = OLD.USER_MEDIA_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_MEDIA_MOD_DET`
--

DROP TABLE IF EXISTS `USER_MEDIA_MOD_DET`;
CREATE TABLE IF NOT EXISTS `USER_MEDIA_MOD_DET` (
`USER_MEDIA_MOD_DET_ID` int(10) unsigned NOT NULL,
  `USER_MEDIA_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for user media' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_MESSAGES`
--

DROP TABLE IF EXISTS `USER_MESSAGES`;
CREATE TABLE IF NOT EXISTS `USER_MESSAGES` (
`MESSAGE_ID` int(10) unsigned NOT NULL,
  `SENDER_USER_ID` int(10) unsigned NOT NULL,
  `MESSAGE_TEXT` text COLLATE latin1_general_cs,
  `MESSAGE_SUBJECT` varchar(500) COLLATE latin1_general_cs DEFAULT NULL,
  `MESSAGE_DATE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL,
  `HAS_ATTACHMENTS` tinyint(3) unsigned DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='User message table' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_MESSAGE_RECIPIENTS`
--

DROP TABLE IF EXISTS `USER_MESSAGE_RECIPIENTS`;
CREATE TABLE IF NOT EXISTS `USER_MESSAGE_RECIPIENTS` (
`USER_MESSAGE_RECIPIENT_ID` int(10) unsigned NOT NULL,
  `MESSAGE_ID` int(10) unsigned NOT NULL,
  `RECIPIENT_USER_ID` int(10) unsigned NOT NULL,
  `RECIPIENT_ROLE_ID` int(10) unsigned NOT NULL,
  `IS_MESSAGE_READ` tinyint(3) unsigned DEFAULT '0',
  `IS_MESSAGE_DELETED` tinyint(3) unsigned DEFAULT '0',
  `MESSAGE_READ_TIME` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='List of all recipients for any message' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_MOD_DET`
--

DROP TABLE IF EXISTS `USER_MOD_DET`;
CREATE TABLE IF NOT EXISTS `USER_MOD_DET` (
  `USER_MOD_DET_ID` int(10) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(150) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of user table';

-- --------------------------------------------------------

--
-- Table structure for table `USER_MOOD_HISTORY`
--

DROP TABLE IF EXISTS `USER_MOOD_HISTORY`;
CREATE TABLE IF NOT EXISTS `USER_MOOD_HISTORY` (
`USER_MOOD_HISTORY_ID` int(11) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `USER_MOOD_LONG_DESCR` text COLLATE latin1_general_cs,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `USER_MOOD_ID` int(11) NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='User mood history' AUTO_INCREMENT=1 ;

--
-- Triggers `USER_MOOD_HISTORY`
--
DROP TRIGGER IF EXISTS `USER_MOOD_HIST_BU`;
DELIMITER //
CREATE TRIGGER `USER_MOOD_HIST_BU` BEFORE UPDATE ON `user_mood_history`
 FOR EACH ROW BEGIN
    IF (OLD.USER_ID <> NEW.USER_ID) OR (OLD.USER_MOOD_ID <> NEW.USER_MOOD_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'User or mood category cannot be altered.';
    END IF;
    IF OLD.USER_MOOD_LONG_DESCR <> NEW.USER_MOOD_LONG_DESCR THEN
        INSERT INTO USER_MOOD_HISTORY_MOD_DET 
        SET 
        USER_MOOD_HISTORY_ID = OLD.USER_MOOD_HISTORY_ID,
        COLUMN_NAME = 'USER_MOOD_LONG_DESCR',
        COLUMN_VALUE = OLD.USER_MOOD_LONG_DESCR,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO USER_MOOD_HISTORY_MOD_DET 
        SET 
        USER_MOOD_HISTORY_ID = OLD.USER_MOOD_HISTORY_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_MOOD_HISTORY_MOD_DET`
--

DROP TABLE IF EXISTS `USER_MOOD_HISTORY_MOD_DET`;
CREATE TABLE IF NOT EXISTS `USER_MOOD_HISTORY_MOD_DET` (
`USER_MOOD_HISTORY_MOD_DET_ID` int(10) unsigned NOT NULL,
  `USER_MOOD_HISTORY_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of user mood history' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_PAIN_TRACKER`
--

DROP TABLE IF EXISTS `USER_PAIN_TRACKER`;
CREATE TABLE IF NOT EXISTS `USER_PAIN_TRACKER` (
`USER_PAIN_ID` int(10) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `PAIN_ID` int(10) unsigned NOT NULL,
  `PAIN_LEVEL_ID` int(10) unsigned NOT NULL,
  `USER_DESCR` text COLLATE latin1_general_cs,
  `DATE_EXPERIENCED_ON` int(10) unsigned DEFAULT NULL,
  `MONTH_EXPERIENCED_ON` int(10) unsigned DEFAULT NULL,
  `YEAR_EXPERIENCED_ON` int(10) unsigned DEFAULT NULL,
  `RECORDED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing user pain history' AUTO_INCREMENT=1 ;

--
-- Triggers `USER_PAIN_TRACKER`
--
DROP TRIGGER IF EXISTS `USER_PAIN_TRACKER_BU`;
DELIMITER //
CREATE TRIGGER `USER_PAIN_TRACKER_BU` BEFORE UPDATE ON `user_pain_tracker`
 FOR EACH ROW BEGIN
    IF (OLD.USER_ID <> NEW.USER_ID) OR (OLD.PAIN_ID <> NEW.PAIN_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'User or pain type cannot be altered.';
    END IF;
    IF OLD.PAIN_LEVEL_ID <> NEW.PAIN_LEVEL_ID THEN
        INSERT INTO USER_PAIN_TRACKER_MOD_DET 
        SET 
        USER_PAIN_ID = OLD.USER_PAIN_ID,
        COLUMN_NAME = 'PAIN_LEVEL_ID',
        COLUMN_VALUE = OLD.PAIN_LEVEL_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.USER_DESCR <> NEW.USER_DESCR THEN
        INSERT INTO USER_PAIN_TRACKER_MOD_DET 
        SET 
        USER_PAIN_ID = OLD.USER_PAIN_ID,
        COLUMN_NAME = 'USER_DESCR',
        COLUMN_VALUE = OLD.USER_DESCR,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.DATE_EXPERIENCED_ON <> NEW.DATE_EXPERIENCED_ON THEN
        INSERT INTO USER_PAIN_TRACKER_MOD_DET 
        SET 
        USER_PAIN_ID = OLD.USER_PAIN_ID,
        COLUMN_NAME = 'DATE_EXPERIENCED_ON',
        COLUMN_VALUE = OLD.DATE_EXPERIENCED_ON,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.MONTH_EXPERIENCED_ON <> NEW.MONTH_EXPERIENCED_ON THEN
        INSERT INTO USER_PAIN_TRACKER_MOD_DET 
        SET 
        USER_PAIN_ID = OLD.USER_PAIN_ID,
        COLUMN_NAME = 'MONTH_EXPERIENCED_ON',
        COLUMN_VALUE = OLD.MONTH_EXPERIENCED_ON,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.YEAR_EXPERIENCED_ON <> NEW.YEAR_EXPERIENCED_ON THEN
        INSERT INTO USER_PAIN_TRACKER_MOD_DET 
        SET 
        USER_PAIN_ID = OLD.USER_PAIN_ID,
        COLUMN_NAME = 'YEAR_EXPERIENCED_ON',
        COLUMN_VALUE = OLD.YEAR_EXPERIENCED_ON,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO USER_PAIN_TRACKER_MOD_DET 
        SET 
        USER_PAIN_ID = OLD.USER_PAIN_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_PAIN_TRACKER_MOD_DET`
--

DROP TABLE IF EXISTS `USER_PAIN_TRACKER_MOD_DET`;
CREATE TABLE IF NOT EXISTS `USER_PAIN_TRACKER_MOD_DET` (
`USER_PAIN_TRACKER_MOD_DET_ID` int(10) unsigned NOT NULL,
  `USER_PAIN_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of user pain history' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_PHOTOS`
--

DROP TABLE IF EXISTS `USER_PHOTOS`;
CREATE TABLE IF NOT EXISTS `USER_PHOTOS` (
`USER_PHOTO_ID` int(10) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `FILE_NAME` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `PHOTO_TYPE_ID` int(10) unsigned NOT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL,
  `IS_DEFAULT` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing records of all user photos' AUTO_INCREMENT=1 ;

--
-- Triggers `USER_PHOTOS`
--
DROP TRIGGER IF EXISTS `USER_PHOTOS_BU`;
DELIMITER //
CREATE TRIGGER `USER_PHOTOS_BU` BEFORE UPDATE ON `user_photos`
 FOR EACH ROW BEGIN
    IF OLD.USER_ID <> NEW.USER_ID THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'User cannot be altered.';
    END IF;
    IF OLD.FILE_NAME <> NEW.FILE_NAME THEN
        INSERT INTO USER_PHOTOS_MOD_DET 
        SET 
        USER_PHOTO_ID = OLD.USER_PHOTO_ID,
        COLUMN_NAME = 'FILE_NAME',
        COLUMN_VALUE = OLD.FILE_NAME,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.PHOTO_TYPE_ID <> NEW.PHOTO_TYPE_ID THEN
        INSERT INTO USER_PHOTOS_MOD_DET 
        SET 
        USER_PHOTO_ID = OLD.USER_PHOTO_ID,
        COLUMN_NAME = 'PHOTO_TYPE_ID',
        COLUMN_VALUE = OLD.PHOTO_TYPE_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO USER_PHOTOS_MOD_DET 
        SET 
        USER_PHOTO_ID = OLD.USER_PHOTO_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
    IF OLD.IS_DEFAULT <> NEW.IS_DEFAULT THEN
        INSERT INTO USER_PHOTOS_MOD_DET 
        SET 
        USER_PHOTO_ID = OLD.USER_PHOTO_ID,
        COLUMN_NAME = 'IS_DEFAULT',
        COLUMN_VALUE = OLD.IS_DEFAULT,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_PHOTOS_MOD_DET`
--

DROP TABLE IF EXISTS `USER_PHOTOS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `USER_PHOTOS_MOD_DET` (
`USER_PHOTOS_MOD_DET_ID` int(10) unsigned NOT NULL,
  `USER_PHOTO_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of user photos' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_PRIVACY_MOD_DET`
--

DROP TABLE IF EXISTS `USER_PRIVACY_MOD_DET`;
CREATE TABLE IF NOT EXISTS `USER_PRIVACY_MOD_DET` (
`USER_PRIVACY_MOD_DET_ID` int(10) unsigned NOT NULL,
  `USER_PRIVACY_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of user privacy settings' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_PRIVACY_SETTINGS`
--

DROP TABLE IF EXISTS `USER_PRIVACY_SETTINGS`;
CREATE TABLE IF NOT EXISTS `USER_PRIVACY_SETTINGS` (
`USER_PRIVACY_ID` int(11) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `USER_TYPE_ID` int(11) NOT NULL,
  `ACTIVITY_SECTION_ID` int(11) unsigned NOT NULL,
  `PRIVACY_ID` int(11) NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='User privacy settings' AUTO_INCREMENT=1 ;

--
-- Triggers `USER_PRIVACY_SETTINGS`
--
DROP TRIGGER IF EXISTS `USER_PRIVACY_BU`;
DELIMITER //
CREATE TRIGGER `USER_PRIVACY_BU` BEFORE UPDATE ON `user_privacy_settings`
 FOR EACH ROW BEGIN
    IF (OLD.USER_ID <> NEW.USER_ID) OR (OLD.USER_TYPE_ID <> NEW.USER_TYPE_ID) OR (OLD.MODULE_ID <> NEW.MODULE_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'User or user role or module cannot be altered.';
    END IF;
    IF OLD.PRIVACY_ID <> NEW.PRIVACY_ID THEN
        INSERT INTO USER_PRIVACY_MOD_DET 
        SET 
        USER_PRIVACY_ID = OLD.USER_PRIVACY_ID,
        COLUMN_NAME = 'PRIVACY_ID',
        COLUMN_VALUE = OLD.PRIVACY_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO USER_PRIVACY_MOD_DET 
        SET 
        USER_PRIVACY_ID = OLD.USER_PRIVACY_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_PSSWRD_CHALLENGE_QUES`
--

DROP TABLE IF EXISTS `USER_PSSWRD_CHALLENGE_QUES`;
CREATE TABLE IF NOT EXISTS `USER_PSSWRD_CHALLENGE_QUES` (
`USER_PSSWRD_QUES_ID` int(11) unsigned NOT NULL,
  `PSSWRD_QUES_ID` int(10) unsigned NOT NULL COMMENT 'Reference master table',
  `USER_ID` int(10) unsigned NOT NULL COMMENT 'References master table of users',
  `QUES_ANSWR_TEXT` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `ORDER_ID` int(10) unsigned NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='User''s password questions and answers' AUTO_INCREMENT=1 ;

--
-- Triggers `USER_PSSWRD_CHALLENGE_QUES`
--
DROP TRIGGER IF EXISTS `PSSWRD_CHALLENGE_QUES_BU`;
DELIMITER //
CREATE TRIGGER `PSSWRD_CHALLENGE_QUES_BU` BEFORE UPDATE ON `user_psswrd_challenge_ques`
 FOR EACH ROW BEGIN
    IF (OLD.USER_ID <> NEW.USER_ID) OR (OLD.PSSWRD_QUES_ID <> NEW.PSSWRD_QUES_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'User or password challenge question cannot be altered.';
    END IF;
    IF OLD.QUES_ANSWR_TEXT <> NEW.QUES_ANSWR_TEXT THEN
        INSERT INTO USER_PSSWRD_CHALLENGE_QUES_MOD_DET 
        SET 
        USER_PSSWRD_QUES_ID = OLD.USER_PSSWRD_QUES_ID,
        COLUMN_NAME = 'QUES_ANSWR_TEXT',
        COLUMN_VALUE = OLD.QUES_ANSWR_TEXT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.ORDER_ID <> NEW.ORDER_ID THEN
        INSERT INTO USER_PSSWRD_CHALLENGE_QUES_MOD_DET 
        SET 
        USER_PSSWRD_QUES_ID = OLD.USER_PSSWRD_QUES_ID,
        COLUMN_NAME = 'ORDER_ID',
        COLUMN_VALUE = OLD.ORDER_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO USER_PSSWRD_CHALLENGE_QUES_MOD_DET 
        SET 
        USER_PSSWRD_QUES_ID = OLD.USER_PSSWRD_QUES_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_PSSWRD_CHALLENGE_QUES_MOD_DET`
--

DROP TABLE IF EXISTS `USER_PSSWRD_CHALLENGE_QUES_MOD_DET`;
CREATE TABLE IF NOT EXISTS `USER_PSSWRD_CHALLENGE_QUES_MOD_DET` (
`USER_PSSWRD_CHALLENGE_QUES_MOD_DET_ID` int(10) unsigned NOT NULL,
  `USER_PSSWRD_QUES_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of user password challenge questions' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_SYMPTOMS`
--

DROP TABLE IF EXISTS `USER_SYMPTOMS`;
CREATE TABLE IF NOT EXISTS `USER_SYMPTOMS` (
`USER_SYMPTOM_ID` int(10) unsigned NOT NULL,
  `USER_ID` int(10) unsigned NOT NULL,
  `SYMPTOM_ID` int(10) unsigned NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `CREATED_BY` int(10) unsigned DEFAULT NULL COMMENT 'Last edited by',
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing all symptoms for individual users' AUTO_INCREMENT=1 ;

--
-- Triggers `USER_SYMPTOMS`
--
DROP TRIGGER IF EXISTS `USER_SYMPTOMS_BU`;
DELIMITER //
CREATE TRIGGER `USER_SYMPTOMS_BU` BEFORE UPDATE ON `user_symptoms`
 FOR EACH ROW BEGIN
    IF (OLD.USER_ID <> NEW.USER_ID) OR (OLD.SYMPTOM_ID <> NEW.SYMPTOM_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'User or user symptom cannot be altered.';
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO USER_SYMPTOMS_MOD_DET 
        SET 
        USER_SYMPTOM_ID = OLD.USER_SYMPTOM_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.CREATED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_SYMPTOMS_MOD_DET`
--

DROP TABLE IF EXISTS `USER_SYMPTOMS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `USER_SYMPTOMS_MOD_DET` (
`USER_SYMPTOMS_MOD_DET_ID` int(10) unsigned NOT NULL,
  `USER_SYMPTOM_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing modification details for user symptoms' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_SYMPTOM_RECORDS`
--

DROP TABLE IF EXISTS `USER_SYMPTOM_RECORDS`;
CREATE TABLE IF NOT EXISTS `USER_SYMPTOM_RECORDS` (
`USER_SYMPTOM_RECORD_ID` int(10) unsigned NOT NULL,
  `UNIT_ID` int(10) unsigned NOT NULL,
  `RECORD_VALUE` int(11) NOT NULL,
  `RECORDED_BY` varchar(100) COLLATE latin1_general_cs DEFAULT NULL COMMENT 'Healthcare professional who took the reading',
  `DATE_RECORDED_ON` int(10) unsigned DEFAULT NULL,
  `MONTH_RECORDED_ON` int(10) unsigned DEFAULT NULL,
  `YEAR_RECORDED_ON` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing all recorded measures for user symptoms' AUTO_INCREMENT=1 ;

--
-- Triggers `USER_SYMPTOM_RECORDS`
--
DROP TRIGGER IF EXISTS `SYMPTOM_RECORDS_BU`;
DELIMITER //
CREATE TRIGGER `SYMPTOM_RECORDS_BU` BEFORE UPDATE ON `user_symptom_records`
 FOR EACH ROW BEGIN
    IF OLD.UNIT_ID <> NEW.UNIT_ID THEN
        INSERT INTO USER_SYMPTOM_RECORDS_MOD_DET 
        SET 
        USER_SYMPTOM_RECORD_ID = OLD.USER_SYMPTOM_RECORD_ID,
        COLUMN_NAME = 'UNIT_ID',
        COLUMN_VALUE = OLD.UNIT_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.RECORD_VALUE <> NEW.RECORD_VALUE THEN
        INSERT INTO USER_SYMPTOM_RECORDS_MOD_DET 
        SET 
        USER_SYMPTOM_RECORD_ID = OLD.USER_SYMPTOM_RECORD_ID,
        COLUMN_NAME = 'RECORD_VALUE',
        COLUMN_VALUE = OLD.RECORD_VALUE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.RECORDED_BY <> NEW.RECORDED_BY THEN
        INSERT INTO USER_SYMPTOM_RECORDS_MOD_DET 
        SET 
        USER_SYMPTOM_RECORD_ID = OLD.USER_SYMPTOM_RECORD_ID,
        COLUMN_NAME = 'RECORDED_BY',
        COLUMN_VALUE = OLD.RECORDED_BY,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.DATE_RECORDED_ON <> NEW.DATE_RECORDED_ON THEN
        INSERT INTO USER_SYMPTOM_RECORDS_MOD_DET 
        SET 
        USER_SYMPTOM_RECORD_ID = OLD.USER_SYMPTOM_RECORD_ID,
        COLUMN_NAME = 'DATE_RECORDED_ON',
        COLUMN_VALUE = OLD.DATE_RECORDED_ON,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.MONTH_RECORDED_ON <> NEW.MONTH_RECORDED_ON THEN
        INSERT INTO USER_SYMPTOM_RECORDS_MOD_DET 
        SET 
        USER_SYMPTOM_RECORD_ID = OLD.USER_SYMPTOM_RECORD_ID,
        COLUMN_NAME = 'MONTH_RECORDED_ON',
        COLUMN_VALUE = OLD.MONTH_RECORDED_ON,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.YEAR_RECORDED_ON <> NEW.YEAR_RECORDED_ON THEN
        INSERT INTO USER_SYMPTOM_RECORDS_MOD_DET 
        SET 
        USER_SYMPTOM_RECORD_ID = OLD.USER_SYMPTOM_RECORD_ID,
        COLUMN_NAME = 'YEAR_RECORDED_ON',
        COLUMN_VALUE = OLD.YEAR_RECORDED_ON,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO USER_SYMPTOM_RECORDS_MOD_DET 
        SET 
        USER_SYMPTOM_RECORD_ID = OLD.USER_SYMPTOM_RECORD_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_SYMPTOM_RECORDS_MOD_DET`
--

DROP TABLE IF EXISTS `USER_SYMPTOM_RECORDS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `USER_SYMPTOM_RECORDS_MOD_DET` (
`USER_SYMPTOM_RECORDS_MOD_DET_ID` int(10) unsigned NOT NULL,
  `USER_SYMPTOM_RECORD_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details for measurement of user symptoms' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_TYPE`
--

DROP TABLE IF EXISTS `USER_TYPE`;
CREATE TABLE IF NOT EXISTS `USER_TYPE` (
  `USER_TYPE` varchar(200) COLLATE latin1_general_cs NOT NULL,
`USER_TYPE_ID` int(11) NOT NULL,
  `STATUS` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs AUTO_INCREMENT=9 ;

--
-- Triggers `USER_TYPE`
--
DROP TRIGGER IF EXISTS `USER_TYPE_BU`;
DELIMITER //
CREATE TRIGGER `USER_TYPE_BU` BEFORE UPDATE ON `user_type`
 FOR EACH ROW BEGIN
	IF EXISTS (SELECT 1 FROM USER_TYPE WHERE USER_TYPE = NEW.USER_TYPE AND NEW.USER_TYPE <> OLD.USER_TYPE) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical user type exists, please revise.';
    END IF;
    IF OLD.USER_TYPE <> NEW.USER_TYPE THEN
        INSERT INTO USER_TYPE_MOD_DET 
        SET 
        USER_TYPE_ID = OLD.USER_TYPE_ID,
        COLUMN_NAME = 'USER_TYPE',
        COLUMN_VALUE = OLD.USER_TYPE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS <> NEW.STATUS THEN
        INSERT INTO USER_TYPE_MOD_DET 
        SET 
        USER_TYPE_ID = OLD.USER_TYPE_ID,
        COLUMN_NAME = 'STATUS',
        COLUMN_VALUE = OLD.STATUS,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `USER_TYPE_MOD_DET`
--

DROP TABLE IF EXISTS `USER_TYPE_MOD_DET`;
CREATE TABLE IF NOT EXISTS `USER_TYPE_MOD_DET` (
`USER_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL,
  `USER_TYPE_ID` int(11) NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of user types' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `WEEK_DAYS_MASTER`
--

DROP TABLE IF EXISTS `WEEK_DAYS_MASTER`;
CREATE TABLE IF NOT EXISTS `WEEK_DAYS_MASTER` (
`WEEK_DAY_ID` int(10) unsigned NOT NULL,
  `WEEK_DAY_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Week day names' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `WEEK_DAYS_MOD_DET`
--

DROP TABLE IF EXISTS `WEEK_DAYS_MOD_DET`;
CREATE TABLE IF NOT EXISTS `WEEK_DAYS_MOD_DET` (
`WEEK_DAYS_MOD_DET_ID` int(10) unsigned NOT NULL,
  `WEEK_DAY_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of week days' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `YEARS_MASTER`
--

DROP TABLE IF EXISTS `YEARS_MASTER`;
CREATE TABLE IF NOT EXISTS `YEARS_MASTER` (
`YEAR_ID` int(10) unsigned NOT NULL,
  `YEAR_VALUE` varchar(10) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) unsigned DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all years' AUTO_INCREMENT=201 ;

--
-- Triggers `YEARS_MASTER`
--
DROP TRIGGER IF EXISTS `YEAR_BU`;
DELIMITER //
CREATE TRIGGER `YEAR_BU` BEFORE UPDATE ON `years_master`
 FOR EACH ROW BEGIN
	IF EXISTS (SELECT 1 FROM YEARS_MASTER WHERE YEAR_VALUE = NEW.YEAR_VALUE AND NEW.YEAR_VALUE <> OLD.YEAR_VALUE) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical year entry exists, please revise.';
    END IF;
    IF OLD.YEAR_VALUE <> NEW.YEAR_VALUE THEN
        INSERT INTO YEAR_MOD_DET 
        SET 
        YEAR_ID = OLD.YEAR_ID,
        COLUMN_NAME = 'YEAR_VALUE',
        COLUMN_VALUE = OLD.YEAR_VALUE,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO YEAR_MOD_DET 
        SET 
        YEAR_ID = OLD.YEAR_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `YEAR_MOD_DET`
--

DROP TABLE IF EXISTS `YEAR_MOD_DET`;
CREATE TABLE IF NOT EXISTS `YEAR_MOD_DET` (
`YEAR_MOD_DET_ID` int(10) unsigned NOT NULL,
  `YEAR_ID` int(10) unsigned NOT NULL,
  `MODIFIED_BY` int(10) unsigned DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of years' AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ACTION_TOKENS_MASTER`
--
ALTER TABLE `ACTION_TOKENS_MASTER`
 ADD PRIMARY KEY (`ACTION_TOKEN_ID`), ADD UNIQUE KEY `ACTION_TOKEN_DESCR` (`ACTION_TOKEN_DESCR`), ADD KEY `ACTION_TOKENS_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `ACTION_TOKENS_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `ACTION_TOKENS_MOD_DET`
--
ALTER TABLE `ACTION_TOKENS_MOD_DET`
 ADD PRIMARY KEY (`ACTION_TOKENS_MOD_DET_ID`), ADD KEY `ACTION_TOKENS_MOD_DET_FK1` (`ACTION_TOKEN_ID`), ADD KEY `ACTION_TOKENS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `ACTIVITY_SECTION_MASTER`
--
ALTER TABLE `ACTIVITY_SECTION_MASTER`
 ADD PRIMARY KEY (`ACTIVITY_SECTION_ID`), ADD UNIQUE KEY `NOTIFICATION_ACTIVITY_SECTION_NAME` (`ACTIVITY_SECTION_NAME`), ADD KEY `NOTIFICATION_ACTIVITY_SECTION_FK1` (`LAST_EDITED_BY`), ADD KEY `NOTIFICATION_ACTIVITY_SECTION_FK2` (`STATUS_ID`);

--
-- Indexes for table `ACTIVITY_SECTION_MOD_DET`
--
ALTER TABLE `ACTIVITY_SECTION_MOD_DET`
 ADD PRIMARY KEY (`ACTIVITY_SECTION_MOD_DET_ID`), ADD KEY `NOTIFICATION_ACTIVITY_SECTION_MOD_DET_FK2` (`MODIFIED_BY`), ADD KEY `ACTIVITY_SECTION_MOD_DET_FK1` (`ACTIVITY_SECTION_ID`);

--
-- Indexes for table `arrowchat`
--
ALTER TABLE `arrowchat`
 ADD PRIMARY KEY (`id`), ADD KEY `to` (`to`), ADD KEY `read` (`read`), ADD KEY `user_read` (`user_read`), ADD KEY `from` (`from`);

--
-- Indexes for table `arrowchat_admin`
--
ALTER TABLE `arrowchat_admin`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `arrowchat_applications`
--
ALTER TABLE `arrowchat_applications`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `arrowchat_banlist`
--
ALTER TABLE `arrowchat_banlist`
 ADD PRIMARY KEY (`ban_id`);

--
-- Indexes for table `arrowchat_chatroom_banlist`
--
ALTER TABLE `arrowchat_chatroom_banlist`
 ADD KEY `user_id` (`user_id`), ADD KEY `chatroom_id` (`chatroom_id`);

--
-- Indexes for table `arrowchat_chatroom_messages`
--
ALTER TABLE `arrowchat_chatroom_messages`
 ADD PRIMARY KEY (`id`), ADD KEY `chatroom_id` (`chatroom_id`), ADD KEY `user_id` (`user_id`), ADD KEY `sent` (`sent`);

--
-- Indexes for table `arrowchat_chatroom_rooms`
--
ALTER TABLE `arrowchat_chatroom_rooms`
 ADD PRIMARY KEY (`id`), ADD KEY `session_time` (`session_time`), ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `arrowchat_chatroom_users`
--
ALTER TABLE `arrowchat_chatroom_users`
 ADD PRIMARY KEY (`user_id`), ADD KEY `chatroom_id` (`chatroom_id`), ADD KEY `is_admin` (`is_admin`), ADD KEY `is_mod` (`is_mod`), ADD KEY `session_time` (`session_time`);

--
-- Indexes for table `arrowchat_config`
--
ALTER TABLE `arrowchat_config`
 ADD UNIQUE KEY `config_name` (`config_name`);

--
-- Indexes for table `arrowchat_graph_log`
--
ALTER TABLE `arrowchat_graph_log`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `date` (`date`);

--
-- Indexes for table `arrowchat_notifications`
--
ALTER TABLE `arrowchat_notifications`
 ADD PRIMARY KEY (`id`), ADD KEY `to_id` (`to_id`), ADD KEY `alert_read` (`alert_read`), ADD KEY `user_read` (`user_read`), ADD KEY `alert_time` (`alert_time`);

--
-- Indexes for table `arrowchat_notifications_markup`
--
ALTER TABLE `arrowchat_notifications_markup`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `arrowchat_smilies`
--
ALTER TABLE `arrowchat_smilies`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `arrowchat_status`
--
ALTER TABLE `arrowchat_status`
 ADD PRIMARY KEY (`userid`), ADD KEY `hash_id` (`hash_id`), ADD KEY `session_time` (`session_time`);

--
-- Indexes for table `arrowchat_themes`
--
ALTER TABLE `arrowchat_themes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `arrowchat_trayicons`
--
ALTER TABLE `arrowchat_trayicons`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ATTRIBUTES_MASTER`
--
ALTER TABLE `ATTRIBUTES_MASTER`
 ADD PRIMARY KEY (`ATTRIBUTE_ID`), ADD UNIQUE KEY `ATTRIBUTE_VAL` (`ATTRIBUTE_DESCR`,`ATTRIBUTE_TYPE_ID`), ADD KEY `ATTRIBUTES_MASTER_FK1` (`ATTRIBUTE_TYPE_ID`), ADD KEY `ATTRIBUTES_MASTER_FK2` (`CREATED_BY`), ADD KEY `ATTRIBUTES_MASTER_FK3` (`STATUS_ID`);

--
-- Indexes for table `ATTRIBUTE_MASTER_MOD_DET`
--
ALTER TABLE `ATTRIBUTE_MASTER_MOD_DET`
 ADD PRIMARY KEY (`ATTRIBUTE_MASTER_MOD_DET_ID`), ADD KEY `ATTRIBUTE_MASTER_MOD_DET_FK1` (`ATTRIBUTE_ID`), ADD KEY `ATTRIBUTE_MASTER_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `ATTRIBUTE_TYPE_MASTER`
--
ALTER TABLE `ATTRIBUTE_TYPE_MASTER`
 ADD PRIMARY KEY (`ATTRIBUTE_TYPE_ID`), ADD UNIQUE KEY `ATTRIBUTE_TYPE_DESCR` (`ATTRIBUTE_TYPE_DESCR`), ADD KEY `ATTRIBUTE_TYPE_MASTER_FK1` (`CREATED_BY`), ADD KEY `ATTRIBUTE_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `ATTRIBUTE_TYPE_MOD_DET`
--
ALTER TABLE `ATTRIBUTE_TYPE_MOD_DET`
 ADD PRIMARY KEY (`ATTRIBUTE_TYPE_MOD_DET_ID`), ADD KEY `ATTRIBUTE_TYPE_MOD_DET_FK1` (`ATTRIBUTE_TYPE_ID`), ADD KEY `ATTRIBUTE_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `BLOCKED_USERS`
--
ALTER TABLE `BLOCKED_USERS`
 ADD PRIMARY KEY (`BLOCKED_USER_ID`), ADD UNIQUE KEY `USER_ID` (`USER_ID`,`BLOCKED_USER`), ADD KEY `BLOCKED_USERS_FK2` (`BLOCKED_USER`), ADD KEY `BLOCKED_USERS_FK3` (`BLOCKED_BY`), ADD KEY `BLOCKED_USERS_FK4` (`STATUS_ID`);

--
-- Indexes for table `BLOCKED_USER_MOD_DET`
--
ALTER TABLE `BLOCKED_USER_MOD_DET`
 ADD PRIMARY KEY (`BLOCKED_USER_MOD_DET_ID`), ADD KEY `BLOCKED_USER_MOD_DET_FK1` (`BLOCKED_USER_ID`), ADD KEY `BLOCKED_USER_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `CAREGIVER_RELATIONSHIP_MASTER`
--
ALTER TABLE `CAREGIVER_RELATIONSHIP_MASTER`
 ADD PRIMARY KEY (`RELATIONSHIP_ID`), ADD UNIQUE KEY `RELATIONSHIP_DESCR` (`RELATIONSHIP_DESCR`), ADD KEY `CAREGIVER_RELATIONSHIP_FK1` (`LAST_EDITED_BY`), ADD KEY `CAREGIVER_RELATIONSHIP_FK2` (`STATUS_ID`);

--
-- Indexes for table `CAREGIVER_RELATIONSHIP_MOD_DET`
--
ALTER TABLE `CAREGIVER_RELATIONSHIP_MOD_DET`
 ADD PRIMARY KEY (`CAREGIVER_RELATIONSHIP_MOD_DET_ID`), ADD KEY `CAREGIVER_RELATIONSHIP_MOD_DET_FK1` (`RELATIONSHIP_ID`), ADD KEY `CAREGIVER_RELATIONSHIP_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `CARE_CALENDAR_EVENTS`
--
ALTER TABLE `CARE_CALENDAR_EVENTS`
 ADD PRIMARY KEY (`CARE_EVENT_ID`), ADD KEY `CARE_CALENDAR_EVENTS_FK1` (`ASSIGNED_TO`), ADD KEY `CARE_CALENDAR_EVENTS_FK2` (`STATUS_ID`), ADD KEY `CARE_CALENDAR_EVENTS_FK3` (`CARE_EVENT_TYPE_ID`), ADD KEY `CARE_CALENDAR_EVENTS_FK4` (`LAST_EDITED_BY`);

--
-- Indexes for table `CARE_EVENTS_MOD_DET`
--
ALTER TABLE `CARE_EVENTS_MOD_DET`
 ADD PRIMARY KEY (`CARE_EVENTS_MOD_DET_ID`), ADD KEY `CARE_EVENTS_MOD_DET_FK1` (`CARE_EVENT_ID`), ADD KEY `CARE_EVENTS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `CARE_GIVER_ATTRIBUTES`
--
ALTER TABLE `CARE_GIVER_ATTRIBUTES`
 ADD PRIMARY KEY (`CARE_GIVER_ATTRIBUTE_ID`), ADD KEY `CARE_GIVER_ATTRIBUTES_FK1` (`PATIENT_CARE_GIVER_ID`), ADD KEY `CARE_GIVER_ATTRIBUTES_FK2` (`ATTRIBUTE_ID`), ADD KEY `CARE_GIVER_ATTRIBUTES_FK3` (`LAST_EDITED_BY`), ADD KEY `CARE_GIVER_ATTRIBUTES_FK4` (`STATUS_ID`);

--
-- Indexes for table `CARE_GIVER_ATTRIBUTE_MOD_DET`
--
ALTER TABLE `CARE_GIVER_ATTRIBUTE_MOD_DET`
 ADD PRIMARY KEY (`CARE_GIVER_ATTRIBUTE_MOD_DET_ID`), ADD KEY `CARE_GIVER_ATTRIBUTE_MOD_DET_FK1` (`CARE_GIVER_ATTRIBUTE_ID`), ADD KEY `CARE_GIVER_ATTRIBUTE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `CITIES_MASTER`
--
ALTER TABLE `CITIES_MASTER`
 ADD PRIMARY KEY (`CITY_ID`), ADD UNIQUE KEY `DESCRIPTION` (`DESCRIPTION`,`STATE_ID`), ADD KEY `CITIES_FK2` (`CREATED_BY`), ADD KEY `CITIES_FK1` (`STATE_ID`), ADD KEY `CITIES_FK4` (`STATUS_ID`);

--
-- Indexes for table `CITIES_MOD_DET`
--
ALTER TABLE `CITIES_MOD_DET`
 ADD PRIMARY KEY (`CITIES_MOD_DET_ID`), ADD KEY `CITIES_MOD_DET_FK1` (`CITY_ID`), ADD KEY `CITIES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `COMMUNITIES`
--
ALTER TABLE `COMMUNITIES`
 ADD PRIMARY KEY (`COMMUNITY_ID`), ADD UNIQUE KEY `COMMUNITY_NAME` (`COMMUNITY_NAME`), ADD KEY `COMMUNITY_FK1` (`COMMUNITY_TYPE_ID`), ADD KEY `COMMUNITY_FK2` (`CREATED_BY`), ADD KEY `COMMUNITY_FK3` (`LAST_EDITED_BY`), ADD KEY `COMMUNITY_FK4` (`STATUS_ID`);

--
-- Indexes for table `COMMUNITY_ATTRIBUTES`
--
ALTER TABLE `COMMUNITY_ATTRIBUTES`
 ADD PRIMARY KEY (`COMMUNITY_ATTRIBUTE_ID`), ADD KEY `COMMUNITY_ATTRIBUTES_FK1` (`COMMUNITY_ID`), ADD KEY `COMMUNITY_ATTRIBUTES_FK2` (`ATTRIBUTE_ID`), ADD KEY `COMMUNITY_ATTRIBUTES_FK3` (`LAST_EDITED_BY`), ADD KEY `COMMUNITY_ATTRIBUTES_FK4` (`STATUS_ID`);

--
-- Indexes for table `COMMUNITY_ATTRIBUTES_MOD_DET`
--
ALTER TABLE `COMMUNITY_ATTRIBUTES_MOD_DET`
 ADD PRIMARY KEY (`COMMUNITY_ATTRIBUTE_MOD_DET_ID`), ADD KEY `COMMUNITY_ATTRIBUTES_MOD_DET_FK1` (`COMMUNITY_ATTRIBUTE_ID`), ADD KEY `COMMUNITY_ATTRIBUTES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `COMMUNITY_DISEASES`
--
ALTER TABLE `COMMUNITY_DISEASES`
 ADD PRIMARY KEY (`COMMUNITY_DISEASE_ID`), ADD UNIQUE KEY `COMMUNITY_ID` (`COMMUNITY_ID`,`DISEASE_ID`), ADD KEY `COMMUNITY_DISEASES_FK2` (`DISEASE_ID`), ADD KEY `COMMUNITY_DISEASES_FK3` (`LAST_EDITED_BY`), ADD KEY `COMMUNITY_DISEASES_FK4` (`STATUS_ID`);

--
-- Indexes for table `COMMUNITY_DISEASES_MOD_DET`
--
ALTER TABLE `COMMUNITY_DISEASES_MOD_DET`
 ADD PRIMARY KEY (`COMMUNITY_DISEASE_MOD_DET_ID`), ADD KEY `COMMUNITY_DISEASES_MOD_DET_FK1` (`COMMUNITY_DISEASE_ID`), ADD KEY `COMMUNITY_DISEASES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `COMMUNITY_MEMBERS`
--
ALTER TABLE `COMMUNITY_MEMBERS`
 ADD PRIMARY KEY (`COMMUNITY_MEMBER_ID`), ADD UNIQUE KEY `COMMUNITY_ID` (`COMMUNITY_ID`,`USER_ID`,`USER_TYPE_ID`), ADD KEY `COMMUNITY_MEMBERS_FK2` (`USER_ID`), ADD KEY `COMMUNITY_MEMBERS_FK3` (`USER_TYPE_ID`), ADD KEY `COMMUNITY_MEMBERS_FK4` (`INVITED_BY`), ADD KEY `COMMUNITY_MEMBERS_FK5` (`LAST_EDITED_BY`), ADD KEY `COMMUNITY_MEMBERS_FK6` (`STATUS_ID`);

--
-- Indexes for table `COMMUNITY_MEMBERS_MOD_DET`
--
ALTER TABLE `COMMUNITY_MEMBERS_MOD_DET`
 ADD PRIMARY KEY (`COMMUNITY_MEMBER_MOD_DET_ID`), ADD KEY `COMMUNITY_MEMBERS_MOD_DET_FK1` (`COMMUNITY_MEMBER_ID`), ADD KEY `COMMUNITY_MEMBERS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `COMMUNITY_MOD_DET`
--
ALTER TABLE `COMMUNITY_MOD_DET`
 ADD PRIMARY KEY (`COMMUNITY_MOD_DET_ID`), ADD KEY `COMMUNITY_MOD_DET_FK1` (`COMMUNITY_ID`), ADD KEY `COMMUNITY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `COMMUNITY_PHOTOS`
--
ALTER TABLE `COMMUNITY_PHOTOS`
 ADD PRIMARY KEY (`COMMUNITY_PHOTO_ID`), ADD KEY `COMMUNITY_PHOTOS_FK1` (`COMMUNITY_ID`), ADD KEY `COMMUNITY_PHOTOS_FK2` (`PHOTO_TYPE_ID`), ADD KEY `COMMUNITY_PHOTOS_FK3` (`LAST_EDITED_BY`), ADD KEY `COMMUNITY_PHOTOS_FK4` (`STATUS_ID`);

--
-- Indexes for table `COMMUNITY_PHOTOS_MOD_DET`
--
ALTER TABLE `COMMUNITY_PHOTOS_MOD_DET`
 ADD PRIMARY KEY (`COMMUNITY_PHOTO_MOD_DET_ID`), ADD KEY `COMMUNITY_PHOTOS_MOD_DET_FK1` (`COMMUNITY_PHOTO_ID`), ADD KEY `COMMUNITY_PHOTOS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `COMMUNITY_TYPE_MASTER`
--
ALTER TABLE `COMMUNITY_TYPE_MASTER`
 ADD PRIMARY KEY (`COMMUNITY_TYPE_ID`), ADD UNIQUE KEY `COMMUNITY_TYPE_NAME` (`COMMUNITY_TYPE_NAME`), ADD KEY `COMMUNITY_TYPE_FK1` (`LAST_EDITED_BY`), ADD KEY `COMMUNITY_TYPE_FK2` (`STATUS_ID`);

--
-- Indexes for table `COMMUNITY_TYPE_MOD_DET`
--
ALTER TABLE `COMMUNITY_TYPE_MOD_DET`
 ADD PRIMARY KEY (`COMMUNITY_TYPE_MOD_DET_ID`), ADD KEY `COMMUNITY_TYPE_MOD_DET_FK1` (`COMMUNITY_TYPE_ID`), ADD KEY `COMMUNITY_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `CONFIGURATIONS`
--
ALTER TABLE `CONFIGURATIONS`
 ADD PRIMARY KEY (`CONFIGURATION_ID`), ADD UNIQUE KEY `CONFIGURATION_NAME` (`CONFIGURATION_NAME`), ADD KEY `CONFIGURATIONS_FK1` (`LAST_EDITED_BY`), ADD KEY `CONFIGURATIONS_FK2` (`STATUS_ID`);

--
-- Indexes for table `CONFIGURATIONS_MOD_DET`
--
ALTER TABLE `CONFIGURATIONS_MOD_DET`
 ADD PRIMARY KEY (`CONFIGURATION_MOD_DET_ID`), ADD KEY `CONFIGURATIONS_MOD_DET_FK1` (`CONFIGURATION_ID`), ADD KEY `CONFIGURATIONS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `COUNTRY_MASTER`
--
ALTER TABLE `COUNTRY_MASTER`
 ADD PRIMARY KEY (`COUNTRY_ID`), ADD UNIQUE KEY `SHORT_NAME` (`SHORT_NAME`), ADD KEY `COUNTRY_FK1` (`STATUS_ID`), ADD KEY `COUNTRY_FK2` (`LAST_EDITED_BY`);

--
-- Indexes for table `COUNTRY_MOD_DET`
--
ALTER TABLE `COUNTRY_MOD_DET`
 ADD PRIMARY KEY (`COUNTRY_MOD_DET_ID`), ADD KEY `COUNTRY_MOD_DET_FK1` (`COUNTRY_ID`), ADD KEY `COUNTRY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `CRON_TASKS`
--
ALTER TABLE `CRON_TASKS`
 ADD PRIMARY KEY (`TASK_ID`), ADD UNIQUE KEY `TASK_TITLE` (`TASK_TITLE`), ADD KEY `CRON_TASKS_FK1` (`CREATED_BY`), ADD KEY `CRON_TASKS_FK2` (`LAST_EDITED_BY`), ADD KEY `CRON_TASKS_FK3` (`STATUS_ID`);

--
-- Indexes for table `CRON_TASKS_MOD_DET`
--
ALTER TABLE `CRON_TASKS_MOD_DET`
 ADD PRIMARY KEY (`CRON_TASK_MOD_DET_ID`), ADD KEY `CRON_TASKS_MOD_DET_FK1` (`TASK_ID`), ADD KEY `CRON_TASKS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `CRON_TASK_EXEC_LOG`
--
ALTER TABLE `CRON_TASK_EXEC_LOG`
 ADD PRIMARY KEY (`CRON_TASK_EXEC_LOG_ID`), ADD KEY `CRON_TASK_EXEC_LOG_FK1` (`TASK_ID`), ADD KEY `CRON_TASK_EXEC_LOG_FK2` (`LAST_EDITED_BY`), ADD KEY `CRON_TASK_EXEC_LOG_FK3` (`STATUS_ID`);

--
-- Indexes for table `CRON_TASK_EXEC_LOG_MOD_DET`
--
ALTER TABLE `CRON_TASK_EXEC_LOG_MOD_DET`
 ADD PRIMARY KEY (`CRON_TASK_EXEC_LOG_MOD_DET_ID`), ADD KEY `CRON_TASK_EXEC_LOG_MOD_DET_FK1` (`CRON_TASK_EXEC_LOG_ID`), ADD KEY `CRON_TASK_EXEC_LOG_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `DATES`
--
ALTER TABLE `DATES`
 ADD PRIMARY KEY (`DATE_ID`), ADD UNIQUE KEY `DATE_VALUE` (`DATE_VALUE`), ADD KEY `DATE_FK1` (`LAST_EDITED_BY`), ADD KEY `DATE_FK2` (`STATUS_ID`);

--
-- Indexes for table `DATES_MOD_DET`
--
ALTER TABLE `DATES_MOD_DET`
 ADD PRIMARY KEY (`DATES_MOD_DET_ID`), ADD KEY `DATES_MOD_DET_FK1` (`DATE_ID`), ADD KEY `DATES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `DISEASE_MASTER`
--
ALTER TABLE `DISEASE_MASTER`
 ADD PRIMARY KEY (`DISEASE_ID`), ADD UNIQUE KEY `DISEASE` (`DISEASE`), ADD KEY `DISEASE_MASTER_FK1` (`PARENT_DISEASE_ID`), ADD KEY `DISEASE_MASTER_FK2` (`STATUS_ID`), ADD KEY `DISEASE_MASTER_FK3` (`DISEASE_SURVEY_ID`), ADD KEY `DISEASE_MASTER_FK4` (`CREATED_BY`);

--
-- Indexes for table `DISEASE_MOD_DET`
--
ALTER TABLE `DISEASE_MOD_DET`
 ADD PRIMARY KEY (`DISEASE_MOD_DET_ID`), ADD KEY `DISEASE_MOD_DET_FK1` (`DISEASE_ID`), ADD KEY `DISEASE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `DISEASE_SYMPTOMS`
--
ALTER TABLE `DISEASE_SYMPTOMS`
 ADD PRIMARY KEY (`DISEASE_SYMPTOM_ID`), ADD UNIQUE KEY `DISEASE_ID` (`DISEASE_ID`,`SYMPTOM_ID`), ADD KEY `DISEASE_SYMPTOMS_FK2` (`CREATED_BY`), ADD KEY `DISEASE_SYMPTOMS_FK3` (`STATUS_ID`), ADD KEY `DISEASE_SYMPTOMS_FK4` (`SYMPTOM_ID`);

--
-- Indexes for table `DISEASE_SYMPTOMS_MOD_DET`
--
ALTER TABLE `DISEASE_SYMPTOMS_MOD_DET`
 ADD PRIMARY KEY (`DISEASE_SYMPTOM_MOD_DET_ID`), ADD KEY `DISEASE_SYMPTOMS_MOD_DET_FK1` (`DISEASE_SYMPTOM_ID`), ADD KEY `DISEASE_SYMPTOMS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `DISEASE_TYPE_MASTER`
--
ALTER TABLE `DISEASE_TYPE_MASTER`
 ADD PRIMARY KEY (`DISEASE_TYPE_ID`), ADD UNIQUE KEY `DISEASE_TYPE` (`DISEASE_TYPE`), ADD KEY `DISEASE_TYPE_MASTER_FK1` (`CREATED_BY`), ADD KEY `DISEASE_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `DISEASE_TYPE_MOD_DET`
--
ALTER TABLE `DISEASE_TYPE_MOD_DET`
 ADD PRIMARY KEY (`DISEASE_TYPE_MOD_DET_ID`), ADD KEY `DISEASE_TYPE_MOD_DET_FK1` (`DISEASE_TYPE_ID`), ADD KEY `DISEASE_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `EMAILS`
--
ALTER TABLE `EMAILS`
 ADD PRIMARY KEY (`EMAIL_ID`), ADD KEY `EMAIL_FK1` (`EMAIL_TEMPLATE_ID`), ADD KEY `EMAIL_FK2` (`CREATED_BY`), ADD KEY `EMAIL_FK3` (`PRIORITY_ID`), ADD KEY `EMAIL_FK4` (`LAST_EDITED_BY`), ADD KEY `EMAIL_FK5` (`STATUS_ID`);

--
-- Indexes for table `EMAIL_ATTRIBUTES`
--
ALTER TABLE `EMAIL_ATTRIBUTES`
 ADD PRIMARY KEY (`EMAIL_ATTRIBUTE_ID`), ADD KEY `EMAIL_ATTRIBUTES_FK1` (`EMAIL_ID`), ADD KEY `EMAIL_ATTRIBUTES_FK2` (`ATTRIBUTE_ID`), ADD KEY `EMAIL_ATTRIBUTES_FK3` (`LAST_EDITED_BY`), ADD KEY `EMAIL_ATTRIBUTES_FK4` (`STATUS_ID`);

--
-- Indexes for table `EMAIL_ATTRIBUTES_MOD_DET`
--
ALTER TABLE `EMAIL_ATTRIBUTES_MOD_DET`
 ADD PRIMARY KEY (`EMAIL_ATTRIBUTES_MOD_DET_ID`), ADD KEY `EMAIL_ATTRIBUTES_MOD_DET_FK1` (`EMAIL_ATTRIBUTE_ID`), ADD KEY `EMAIL_ATTRIBUTES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `EMAIL_HISTORY`
--
ALTER TABLE `EMAIL_HISTORY`
 ADD PRIMARY KEY (`EMAIL_HISTORY_ID`), ADD KEY `EMAIL_HISTORY_FK1` (`EMAIL_TEMPLATE_ID`), ADD KEY `EMAIL_HISTORY_FK2` (`CREATED_BY`), ADD KEY `EMAIL_HISTORY_FK3` (`PRIORITY_ID`), ADD KEY `EMAIL_HISTORY_FK4` (`LAST_EDITED_BY`), ADD KEY `EMAIL_HISTORY_FK5` (`STATUS_ID`);

--
-- Indexes for table `EMAIL_HISTORY_ATTRIBUTES`
--
ALTER TABLE `EMAIL_HISTORY_ATTRIBUTES`
 ADD PRIMARY KEY (`EMAIL_HISTORY_ATTRIBUTE_ID`), ADD KEY `EMAIL_HISTORY_ATTRIBUTES_FK1` (`EMAIL_HISTORY_ID`), ADD KEY `EMAIL_HISTORY_ATTRIBUTES_FK2` (`ATTRIBUTE_ID`), ADD KEY `EMAIL_HISTORY_ATTRIBUTES_FK3` (`LAST_EDITED_BY`), ADD KEY `EMAIL_HISTORY_ATTRIBUTES_FK4` (`STATUS_ID`);

--
-- Indexes for table `EMAIL_HISTORY_ATTRIBUTES_MOD_DET`
--
ALTER TABLE `EMAIL_HISTORY_ATTRIBUTES_MOD_DET`
 ADD PRIMARY KEY (`EMAIL_HISTORY_ATTRIBUTES_MOD_DET_ID`), ADD KEY `EMAIL_HISTORY_ATTRIBUTES_MOD_DET_FK1` (`EMAIL_HISTORY_ATTRIBUTE_ID`), ADD KEY `EMAIL_HISTORY_ATTRIBUTES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `EMAIL_HISTORY_MOD_DET`
--
ALTER TABLE `EMAIL_HISTORY_MOD_DET`
 ADD PRIMARY KEY (`EMAIL_HISTORY_MOD_DET_ID`), ADD KEY `EMAIL_HISTORY_MOD_DET_FK1` (`EMAIL_HISTORY_ID`), ADD KEY `EMAIL_HISTORY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `EMAIL_MOD_DET`
--
ALTER TABLE `EMAIL_MOD_DET`
 ADD PRIMARY KEY (`EMAIL_MOD_DET_ID`), ADD KEY `EMAIL_MOD_DET_FK1` (`EMAIL_ID`), ADD KEY `EMAIL_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `EMAIL_PRIORITY_MASTER`
--
ALTER TABLE `EMAIL_PRIORITY_MASTER`
 ADD PRIMARY KEY (`EMAIL_PRIORITY_ID`), ADD UNIQUE KEY `EMAIL_PRIORITY_DESCR` (`EMAIL_PRIORITY_DESCR`), ADD KEY `EMAIL_PRIORITY_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `EMAIL_PRIORITY_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `EMAIL_PRIORITY_MOD_DET`
--
ALTER TABLE `EMAIL_PRIORITY_MOD_DET`
 ADD PRIMARY KEY (`EMAIL_PRIORITY_MOD_DET_ID`), ADD KEY `EMAIL_PRIORITY_MOD_DET_FK1` (`EMAIL_PRIORITY_ID`), ADD KEY `EMAIL_PRIORITY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `EMAIL_TEMPLATES`
--
ALTER TABLE `EMAIL_TEMPLATES`
 ADD PRIMARY KEY (`TEMPLATE_ID`), ADD KEY `EMAIL_TEMPLATES_FK1` (`CREATED_BY`), ADD KEY `EMAIL_TEMPLATES_FK2` (`LAST_EDITED_BY`), ADD KEY `EMAIL_TEMPLATES_FK3` (`STATUS_ID`);

--
-- Indexes for table `EMAIL_TEMPLATES_MOD_DET`
--
ALTER TABLE `EMAIL_TEMPLATES_MOD_DET`
 ADD PRIMARY KEY (`EMAIL_TEMPLATE_MOD_DET_ID`), ADD KEY `EMAIL_TEMPLATE_MOD_DET_FK1` (`TEMPLATE_ID`), ADD KEY `EMAIL_TEMPLATE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `EVENTS`
--
ALTER TABLE `EVENTS`
 ADD PRIMARY KEY (`EVENT_ID`), ADD UNIQUE KEY `EVENT_NAME` (`EVENT_NAME`), ADD KEY `EVENT_FK1` (`EVENT_TYPE_ID`), ADD KEY `EVENT_FK2` (`COMMUNITY_ID`), ADD KEY `EVENT_FK3` (`REPEAT_TYPE_ID`), ADD KEY `EVENT_FK4` (`CREATED_BY`), ADD KEY `EVENT_FK5` (`PUBLISH_TYPE_ID`), ADD KEY `EVENT_FK6` (`SECTION_TYPE_ID`), ADD KEY `EVENT_FK7` (`SECTION_TEAM_ID`), ADD KEY `EVENT_FK8` (`SECTION_COMMUNITY_ID`), ADD KEY `EVENT_FK9` (`REPEAT_MODE_TYPE_ID`), ADD KEY `EVENT_FK10` (`REPEAT_BY_TYPE_ID`), ADD KEY `EVENT_FK11` (`REPEAT_END_TYPE_ID`), ADD KEY `EVENT_FK12` (`LAST_EDITED_BY`), ADD KEY `EVENT_FK13` (`STATUS_ID`);

--
-- Indexes for table `EVENT_ATTRIBUTES`
--
ALTER TABLE `EVENT_ATTRIBUTES`
 ADD PRIMARY KEY (`EVENT_ATTRIBUTE_ID`), ADD KEY `EVENT_ATTRIBUTES_FK2` (`ATTRIBUTE_ID`), ADD KEY `EVENT_ATTRIBUTES_FK3` (`LAST_EDITED_BY`), ADD KEY `EVENT_ATTRIBUTES_FK4` (`STATUS_ID`), ADD KEY `EVENT_ATTRIBUTES_FK1` (`EVENT_ID`);

--
-- Indexes for table `EVENT_ATTRIBUTES_MOD_DET`
--
ALTER TABLE `EVENT_ATTRIBUTES_MOD_DET`
 ADD PRIMARY KEY (`EVENT_ATTRIBUTE_MOD_DET_ID`), ADD KEY `EVENT_ATTRIBUTES_MOD_DET_FK1` (`EVENT_ATTRIBUTE_ID`), ADD KEY `EVENT_ATTRIBUTES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `EVENT_DISEASES`
--
ALTER TABLE `EVENT_DISEASES`
 ADD PRIMARY KEY (`EVENT_DISEASE_ID`), ADD UNIQUE KEY `EVENT_ID` (`EVENT_ID`,`DISEASE_ID`), ADD KEY `EVENT_DISEASES_FK2` (`DISEASE_ID`), ADD KEY `EVENT_DISEASES_FK3` (`CREATED_BY`), ADD KEY `EVENT_DISEASES_FK4` (`LAST_EDITED_BY`), ADD KEY `EVENT_DISEASES_FK5` (`STATUS_ID`);

--
-- Indexes for table `EVENT_DISEASES_MOD_DET`
--
ALTER TABLE `EVENT_DISEASES_MOD_DET`
 ADD PRIMARY KEY (`EVENT_DISEASE_MOD_DET_ID`), ADD KEY `EVENT_DISEASES_MOD_DET_FK1` (`EVENT_DISEASE_ID`), ADD KEY `EVENT_DISEASES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `EVENT_MEMBERS`
--
ALTER TABLE `EVENT_MEMBERS`
 ADD PRIMARY KEY (`EVENT_MEMBER_ID`), ADD UNIQUE KEY `EVENT_ID` (`EVENT_ID`,`MEMBER_ID`,`MEMBER_ROLE_ID`), ADD KEY `EVENT_MEMBERS_FK2` (`MEMBER_ID`), ADD KEY `EVENT_MEMBERS_FK3` (`MEMBER_ROLE_ID`), ADD KEY `EVENT_MEMBERS_FK4` (`INVITED_BY`), ADD KEY `EVENT_MEMBERS_FK5` (`CREATED_BY`), ADD KEY `EVENT_MEMBERS_FK6` (`LAST_EDITED_BY`), ADD KEY `EVENT_MEMBERS_FK7` (`STATUS_ID`);

--
-- Indexes for table `EVENT_MEMBERS_MOD_DET`
--
ALTER TABLE `EVENT_MEMBERS_MOD_DET`
 ADD PRIMARY KEY (`EVENT_MEMBER_MOD_DET_ID`), ADD KEY `EVENT_MEMBERS_MOD_DET_FK1` (`EVENT_MEMBER_ID`), ADD KEY `EVENT_MEMBERS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `EVENT_MOD_DET`
--
ALTER TABLE `EVENT_MOD_DET`
 ADD PRIMARY KEY (`EVENT_MOD_DET_ID`), ADD KEY `EVENT_MOD_DET_FK1` (`EVENT_ID`), ADD KEY `EVENT_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `EVENT_TYPE_MASTER`
--
ALTER TABLE `EVENT_TYPE_MASTER`
 ADD PRIMARY KEY (`EVENT_TYPE_ID`), ADD UNIQUE KEY `EVENT_TYPE_DESCR` (`EVENT_TYPE_DESCR`), ADD KEY `EVENT_TYPE_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `EVENT_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `EVENT_TYPE_MOD_DET`
--
ALTER TABLE `EVENT_TYPE_MOD_DET`
 ADD PRIMARY KEY (`EVENT_TYPE_MOD_DET_ID`), ADD KEY `EVENT_TYPE_MOD_DET_FK1` (`EVENT_TYPE_ID`), ADD KEY `EVENT_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `FOLLOWING_PAGES`
--
ALTER TABLE `FOLLOWING_PAGES`
 ADD PRIMARY KEY (`FOLLOWING_PAGE_ID`), ADD UNIQUE KEY `PAGE_ID` (`PAGE_ID`,`USER_ID`), ADD KEY `FOLLOWING_PAGES_FK2` (`USER_ID`), ADD KEY `FOLLOWING_PAGES_FK3` (`CREATED_BY`), ADD KEY `FOLLOWING_PAGES_FK4` (`LAST_EDITED_BY`), ADD KEY `FOLLOWING_PAGES_FK5` (`STATUS_ID`);

--
-- Indexes for table `FOLLOWING_PAGES_MOD_DET`
--
ALTER TABLE `FOLLOWING_PAGES_MOD_DET`
 ADD PRIMARY KEY (`FOLLOWING_PAGE_MOD_DET_ID`), ADD KEY `FOLLOWING_PAGES_MOD_DET_FK1` (`FOLLOWING_PAGE_ID`), ADD KEY `FOLLOWING_PAGES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `HEALTH_CONDITION_GROUPS`
--
ALTER TABLE `HEALTH_CONDITION_GROUPS`
 ADD PRIMARY KEY (`HEALTH_CONDITION_GROUP_ID`), ADD UNIQUE KEY `HEALTH_CONDITION_GROUP_DESCR` (`HEALTH_CONDITION_GROUP_DESCR`), ADD KEY `HEALTH_CONDITION_GROUPS_FK1` (`STATUS_ID`), ADD KEY `HEALTH_CONDITION_GROUPS_FK2` (`LAST_EDITED_BY`);

--
-- Indexes for table `HEALTH_CONDITION_MASTER`
--
ALTER TABLE `HEALTH_CONDITION_MASTER`
 ADD PRIMARY KEY (`HEALTH_CONDITION_ID`), ADD UNIQUE KEY `HEALTH_CONDITION_DESCR` (`HEALTH_CONDITION_DESCR`,`HEALTH_CONDITION_GROUP_ID`), ADD KEY `HEALTH_CONDITION_FK1` (`HEALTH_CONDITION_GROUP_ID`), ADD KEY `HEALTH_CONDITION_FK2` (`STATUS_ID`), ADD KEY `HEALTH_CONDITION_FK3` (`LAST_EDITED_BY`);

--
-- Indexes for table `HEALTH_CONDITION_MOD_DET`
--
ALTER TABLE `HEALTH_CONDITION_MOD_DET`
 ADD PRIMARY KEY (`HEALTH_CONDITION_MOD_DET_ID`), ADD KEY `HEALTH_CONDITION_MOD_DET_FK1` (`HEALTH_CONDITION_ID`), ADD KEY `HEALTH_CONDITION_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `HEALTH_COND_GROUP_MOD_DET`
--
ALTER TABLE `HEALTH_COND_GROUP_MOD_DET`
 ADD PRIMARY KEY (`HEALTH_COND_GROUP_MOD_DET_ID`), ADD KEY `HEALTH_COND_GROUP_MOD_DET_FK1` (`HEALTH_CONDITION_GROUP_ID`), ADD KEY `HEALTH_COND_GROUP_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `INVITED_USERS`
--
ALTER TABLE `INVITED_USERS`
 ADD PRIMARY KEY (`INVITED_USER_ID`), ADD UNIQUE KEY `INVITED_USER_EMAIL` (`INVITED_USER_EMAIL`), ADD UNIQUE KEY `INVITED_USER_EMAIL_2` (`INVITED_USER_EMAIL`,`INVITED_BY`), ADD KEY `INVITED_USERS_FK1` (`INVITED_BY`), ADD KEY `INVITED_USERS_FK2` (`STATUS_ID`);

--
-- Indexes for table `INVITED_USERS_MOD_DET`
--
ALTER TABLE `INVITED_USERS_MOD_DET`
 ADD PRIMARY KEY (`INVITED_USERS_MOD_DET_ID`), ADD KEY `INVITED_USERS_MOD_DET_FK1` (`INVITED_USER_ID`), ADD KEY `INVITED_USERS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `LANGUAGES`
--
ALTER TABLE `LANGUAGES`
 ADD PRIMARY KEY (`LANGUAGE_ID`), ADD UNIQUE KEY `LANGUAGE` (`LANGUAGE`), ADD KEY `LANGUAGE_FK1` (`LAST_EDITED_BY`), ADD KEY `LANGUAGE_FK2` (`STATUS_ID`);

--
-- Indexes for table `LANGUAGE_MOD_DET`
--
ALTER TABLE `LANGUAGE_MOD_DET`
 ADD PRIMARY KEY (`LANGUAGE_MOD_DET_ID`), ADD KEY `LANGUAGE_MOD_DET_FK1` (`LANGUAGE_ID`), ADD KEY `LANGUAGE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `MEDIA_TYPE_MASTER`
--
ALTER TABLE `MEDIA_TYPE_MASTER`
 ADD PRIMARY KEY (`MEDIA_TYPE_ID`), ADD UNIQUE KEY `MEDIA_TYPE_DESCR` (`MEDIA_TYPE_DESCR`), ADD KEY `MEDIA_TYPE_MASTER_FK1` (`CREATED_BY`), ADD KEY `MEDIA_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `MEDIA_TYPE_MOD_DET`
--
ALTER TABLE `MEDIA_TYPE_MOD_DET`
 ADD PRIMARY KEY (`MEDIA_TYPE_MOD_DET_ID`), ADD KEY `MEDIA_TYPE_MOD_DET_FK1` (`MEDIA_TYPE_ID`), ADD KEY `MEDIA_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `MESSAGE_RECIPIENT_ROLES`
--
ALTER TABLE `MESSAGE_RECIPIENT_ROLES`
 ADD PRIMARY KEY (`MESSAGE_RECIPIENT_ROLE_ID`), ADD UNIQUE KEY `ROLE_DESCR` (`ROLE_DESCR`), ADD KEY `MESSAGE_RECIPIENT_ROLES_FK1` (`STATUS_ID`), ADD KEY `MESSAGE_RECIPIENT_ROLES_FK2` (`LAST_EDITED_BY`);

--
-- Indexes for table `MESSAGE_ROLE_MOD_DET`
--
ALTER TABLE `MESSAGE_ROLE_MOD_DET`
 ADD PRIMARY KEY (`MESSAGE_ROLE_MOD_DET_ID`), ADD KEY `MESSAGE_ROLE_MOD_DET_FK1` (`MESSAGE_RECIPIENT_ROLE_ID`), ADD KEY `MESSAGE_ROLE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `MODULE_MASTER`
--
ALTER TABLE `MODULE_MASTER`
 ADD PRIMARY KEY (`MODULE_ID`), ADD UNIQUE KEY `MODULE_DESCR` (`MODULE_DESCR`), ADD KEY `MODULE_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `MODULE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `MODULE_MOD_DET`
--
ALTER TABLE `MODULE_MOD_DET`
 ADD PRIMARY KEY (`MODULE_MOD_DET_ID`), ADD KEY `MODULE_MOD_DET_FK1` (`MODULE_ID`), ADD KEY `MODULE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `MONTHS_MASTER`
--
ALTER TABLE `MONTHS_MASTER`
 ADD PRIMARY KEY (`MONTH_ID`), ADD UNIQUE KEY `MONTH_NAME` (`MONTH_NAME`), ADD UNIQUE KEY `MONTH_ABBREV` (`MONTH_ABBREV`), ADD KEY `MONTH_FK1` (`LAST_EDITED_BY`), ADD KEY `MONTH_FK2` (`STATUS_ID`);

--
-- Indexes for table `MONTH_MOD_DET`
--
ALTER TABLE `MONTH_MOD_DET`
 ADD PRIMARY KEY (`MONTH_MOD_DET_ID`), ADD KEY `MONTH_MOD_DET_FK1` (`MONTH_ID`), ADD KEY `MONTH_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `MOOD_MASTER`
--
ALTER TABLE `MOOD_MASTER`
 ADD PRIMARY KEY (`USER_MOOD_ID`), ADD UNIQUE KEY `USER_MOOD_DESCR` (`USER_MOOD_DESCR`), ADD KEY `USER_MOOD_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `USER_MOOD_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `MOOD_MOD_DET`
--
ALTER TABLE `MOOD_MOD_DET`
 ADD PRIMARY KEY (`MOOD_MOD_DET_ID`), ADD KEY `MOOD_MOD_DET_FK1` (`MOOD_ID`), ADD KEY `MOOD_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `MY_FRIENDS`
--
ALTER TABLE `MY_FRIENDS`
 ADD PRIMARY KEY (`MY_FRIEND_ID`), ADD KEY `MY_FRIENDS_FK1` (`MY_USER_ID`), ADD KEY `MY_FRIENDS_FK2` (`LAST_EDITED_BY`), ADD KEY `MY_FRIENDS_FK3` (`STATUS_ID`);

--
-- Indexes for table `MY_FRIENDS_DETAILS`
--
ALTER TABLE `MY_FRIENDS_DETAILS`
 ADD PRIMARY KEY (`MY_FRIENDS_DETAIL_ID`), ADD UNIQUE KEY `MY_FRIEND_ID` (`MY_FRIEND_ID`,`FRIEND_USER_ID`), ADD KEY `MY_FRIENDS_DETAILS_FK2` (`FRIEND_USER_ID`), ADD KEY `MY_FRIENDS_DETAILS_FK3` (`LAST_EDITED_BY`), ADD KEY `MY_FRIENDS_DETAILS_FK4` (`STATUS_ID`);

--
-- Indexes for table `MY_FRIENDS_DETAIL_MOD_DET`
--
ALTER TABLE `MY_FRIENDS_DETAIL_MOD_DET`
 ADD PRIMARY KEY (`MY_FRIENDS_DETAIL_MOD_DET_ID`), ADD KEY `MY_FRIENDS_DETAIL_MOD_DET_FK1` (`MY_FRIENDS_DETAIL_ID`), ADD KEY `MY_FRIENDS_DETAIL_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `MY_FRIEND_MOD_DET`
--
ALTER TABLE `MY_FRIEND_MOD_DET`
 ADD PRIMARY KEY (`MY_FRIEND_MOD_DET_ID`), ADD KEY `MY_FRIEND_MOD_DET_FK1` (`MY_FRIEND_ID`), ADD KEY `MY_FRIEND_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `NEWSLETTERS`
--
ALTER TABLE `NEWSLETTERS`
 ADD PRIMARY KEY (`NEWSLETTER_ID`), ADD KEY `NEWSLETTERS_FK1` (`CREATED_BY`), ADD KEY `NEWSLETTERS_FK2` (`LAST_EDITED_BY`), ADD KEY `NEWSLETTERS_FK3` (`STATUS_ID`);

--
-- Indexes for table `NEWSLETTER_MOD_DET`
--
ALTER TABLE `NEWSLETTER_MOD_DET`
 ADD PRIMARY KEY (`NEWSLETTER_MOD_DET_ID`), ADD KEY `NEWSLETTER_MOD_DET_FK1` (`NEWSLETTER_ID`), ADD KEY `NEWSLETTER_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `NEWSLETTER_QUEUE_MOD_DET`
--
ALTER TABLE `NEWSLETTER_QUEUE_MOD_DET`
 ADD PRIMARY KEY (`NEWSLETTER_QUEUE_MOD_DET_ID`), ADD KEY `NEWSLETTER_QUEUE_MOD_DET_FK1` (`NEWSLETTER_QUEUE_ID`), ADD KEY `NEWSLETTER_QUEUE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `NEWSLETTER_QUEUE_STATUS`
--
ALTER TABLE `NEWSLETTER_QUEUE_STATUS`
 ADD PRIMARY KEY (`NEWSLETTER_QUEUE_ID`), ADD KEY `NEWSLETTER_QUEUE_STATUS_FK1` (`NEWSLETTER_ID`), ADD KEY `NEWSLETTER_QUEUE_STATUS_FK2` (`CREATED_BY`), ADD KEY `NEWSLETTER_QUEUE_STATUS_FK3` (`LAST_EDITED_BY`), ADD KEY `NEWSLETTER_QUEUE_STATUS_FK4` (`STATUS_ID`);

--
-- Indexes for table `NEWSLETTER_TEMPLATES`
--
ALTER TABLE `NEWSLETTER_TEMPLATES`
 ADD PRIMARY KEY (`NEWSLETTER_TEMPLATE_ID`), ADD KEY `NEWSLETTER_TEMPLATES_FK1` (`CREATED_BY`), ADD KEY `NEWSLETTER_TEMPLATES_FK2` (`LAST_EDITED_BY`), ADD KEY `NEWSLETTER_TEMPLATES_FK3` (`STATUS_ID`);

--
-- Indexes for table `NEWSLETTER_TEMPLATE_MOD_DET`
--
ALTER TABLE `NEWSLETTER_TEMPLATE_MOD_DET`
 ADD PRIMARY KEY (`NEWSLETTER_TEMPLATE_MOD_DET_ID`), ADD KEY `NEWSLETTER_TEMPLATE_MOD_DET_FK1` (`NEWSLETTER_TEMPLATE_ID`), ADD KEY `NEWSLETTER_TEMPLATE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `NOTIFICATIONS`
--
ALTER TABLE `NOTIFICATIONS`
 ADD PRIMARY KEY (`NOTIFICATION_ID`), ADD KEY `NOTIFICATION_FK1` (`NOTIFICATION_ACTIVITY_TYPE_ID`), ADD KEY `NOTIFICATION_FK2` (`NOTIFICATION_OBJECT_TYPE_ID`), ADD KEY `NOTIFICATION_FK4` (`SENDER_ID`), ADD KEY `NOTIFICATION_FK5` (`OBJECT_OWNER_ID`), ADD KEY `NOTIFICATION_FK6` (`CREATED_BY`), ADD KEY `NOTIFICATION_FK7` (`LAST_EDITED_BY`), ADD KEY `NOTIFICATION_FK8` (`STATUS_ID`), ADD KEY `NOTIFICATION_FK3` (`NOTIFICATION_ACTIVITY_SECTION_TYPE_ID`);

--
-- Indexes for table `NOTIFICATION_ACTIVITY_MOD_DET`
--
ALTER TABLE `NOTIFICATION_ACTIVITY_MOD_DET`
 ADD PRIMARY KEY (`NOTIFICATION_ACTIVITY_MOD_DET_ID`), ADD KEY `NOTIFICATION_ACTIVITY_MOD_DET_FK1` (`NOTIFICATION_ACTIVITY_TYPE_ID`), ADD KEY `NOTIFICATION_ACTIVITY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `NOTIFICATION_ACTIVITY_TYPE_MASTER`
--
ALTER TABLE `NOTIFICATION_ACTIVITY_TYPE_MASTER`
 ADD PRIMARY KEY (`NOTIFICATION_ACTIVITY_TYPE_ID`), ADD UNIQUE KEY `NOTIFICATION_ACTIVITY_TYPE_NAME` (`NOTIFICATION_ACTIVITY_TYPE_NAME`), ADD KEY `NOTIFICATION_ACTIVITY_TYPE_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `NOTIFICATION_ACTIVITY_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `NOTIFICATION_FREQUENCY_MASTER`
--
ALTER TABLE `NOTIFICATION_FREQUENCY_MASTER`
 ADD PRIMARY KEY (`NOTIFICATION_FREQUENCY_ID`), ADD UNIQUE KEY `NOTIFICATION_FREQUENCY_NAME` (`NOTIFICATION_FREQUENCY_NAME`), ADD KEY `NOTIFICATION_FREQUENCY_FK1` (`LAST_EDITED_BY`), ADD KEY `NOTIFICATION_FREQUENCY_FK2` (`STATUS_ID`);

--
-- Indexes for table `NOTIFICATION_FREQUENCY_MOD_DET`
--
ALTER TABLE `NOTIFICATION_FREQUENCY_MOD_DET`
 ADD PRIMARY KEY (`NOTIFICATION_FREQUENCY_MOD_DET_ID`), ADD KEY `NOTIFICATION_FREQUENCY_MOD_DET_FK1` (`NOTIFICATION_FREQUENCY_ID`), ADD KEY `NOTIFICATION_FREQUENCY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `NOTIFICATION_MOD_DET`
--
ALTER TABLE `NOTIFICATION_MOD_DET`
 ADD PRIMARY KEY (`NOTIFICATION_MOD_DET_ID`), ADD KEY `NOTIFICATION_MOD_DET_FK1` (`NOTIFICATION_ID`), ADD KEY `NOTIFICATION_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `NOTIFICATION_OBJECT_TYPE_MASTER`
--
ALTER TABLE `NOTIFICATION_OBJECT_TYPE_MASTER`
 ADD PRIMARY KEY (`NOTIFICATION_OBJECT_TYPE_ID`), ADD UNIQUE KEY `NOTIFICATION_OBJECT_TYPE_NAME` (`NOTIFICATION_OBJECT_TYPE_NAME`), ADD KEY `NOTIFICATION_OBJECT_TYPE_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `NOTIFICATION_OBJECT_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `NOTIFICATION_OBJECT_TYPE_MOD_DET`
--
ALTER TABLE `NOTIFICATION_OBJECT_TYPE_MOD_DET`
 ADD PRIMARY KEY (`NOTIFICATION_OBJECT_TYPE_MOD_DET_ID`), ADD KEY `NOTIFICATION_OBJECT_TYPE_MOD_DET_FK1` (`NOTIFICATION_OBJECT_TYPE_ID`), ADD KEY `NOTIFICATION_OBJECT_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `NOTIFICATION_RECIPIENTS`
--
ALTER TABLE `NOTIFICATION_RECIPIENTS`
 ADD PRIMARY KEY (`NOTIFICATION_RECIPIENT_ID`), ADD KEY `NOTIFICATION_RECIPIENT_FK1` (`NOTIFICATION_ID`), ADD KEY `NOTIFICATION_RECIPIENT_FK2` (`RECIPIENT_ID`), ADD KEY `NOTIFICATION_RECIPIENT_FK3` (`LAST_EDITED_BY`), ADD KEY `NOTIFICATION_RECIPIENT_FK4` (`STATUS_ID`);

--
-- Indexes for table `NOTIFICATION_RECIPIENT_MOD_DET`
--
ALTER TABLE `NOTIFICATION_RECIPIENT_MOD_DET`
 ADD PRIMARY KEY (`NOTIFICATION_RECIPIENT_MOD_DET_ID`), ADD KEY `NOTIFICATION_RECIPIENT_MOD_DET_FK1` (`NOTIFICATION_RECIPIENT_ID`), ADD KEY `NOTIFICATION_RECIPIENT_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `NOTIFICATION_SETTINGS`
--
ALTER TABLE `NOTIFICATION_SETTINGS`
 ADD PRIMARY KEY (`NOTIFICATION_SETTING_ID`), ADD KEY `NOTIFICATION_SETTING_FK1` (`USER_ID`), ADD KEY `NOTIFICATION_SETTING_FK2` (`HEIGHT_UNIT`), ADD KEY `NOTIFICATION_SETTING_FK3` (`WEIGHT_UNIT`), ADD KEY `NOTIFICATION_SETTING_FK4` (`TEMP_UNIT`), ADD KEY `NOTIFICATION_SETTING_FK5` (`NOTIFICATION_FREQUENCY_ID`), ADD KEY `NOTIFICATION_SETTING_FK6` (`LAST_EDITED_BY`), ADD KEY `NOTIFICATION_SETTING_FK7` (`STATUS_ID`);

--
-- Indexes for table `NOTIFICATION_SETTING_MOD_DET`
--
ALTER TABLE `NOTIFICATION_SETTING_MOD_DET`
 ADD PRIMARY KEY (`NOTIFICATION_SETTING_MOD_DET_ID`), ADD KEY `NOTIFICATION_SETTING_MOD_DET_FK1` (`NOTIFICATION_SETTING_ID`), ADD KEY `NOTIFICATION_SETTING_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `NOTIFIED_USERS`
--
ALTER TABLE `NOTIFIED_USERS`
 ADD PRIMARY KEY (`NOTIFIED_USER_ID`), ADD UNIQUE KEY `NOTIFICATION_SETTING_ID` (`NOTIFICATION_SETTING_ID`,`USER_ID`), ADD KEY `NOTIFIED_USER_FK1` (`USER_ID`), ADD KEY `NOTIFIED_USER_FK3` (`LAST_EDITED_BY`), ADD KEY `NOTIFIED_USER_FK4` (`STATUS_ID`);

--
-- Indexes for table `NOTIFIED_USER_MOD_DET`
--
ALTER TABLE `NOTIFIED_USER_MOD_DET`
 ADD PRIMARY KEY (`NOTIFIED_USER_MOD_DET_ID`), ADD KEY `NOTIFIED_USER_MOD_DET_FK1` (`NOTIFIED_USER_ID`), ADD KEY `NOTIFIED_USER_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `PAGE_MASTER`
--
ALTER TABLE `PAGE_MASTER`
 ADD PRIMARY KEY (`PAGE_ID`), ADD UNIQUE KEY `PAGE_TYPE_ID` (`PAGE_TYPE_ID`,`PAGE_DESCR`), ADD KEY `PAGE_MASTER_FK2` (`LAST_EDITED_BY`), ADD KEY `PAGE_MASTER_FK3` (`STATUS_ID`);

--
-- Indexes for table `PAGE_MOD_DET`
--
ALTER TABLE `PAGE_MOD_DET`
 ADD PRIMARY KEY (`PAGE_MOD_DET_ID`), ADD KEY `PAGE_MOD_DET_FK1` (`PAGE_ID`), ADD KEY `PAGE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `PAGE_TYPE_MASTER`
--
ALTER TABLE `PAGE_TYPE_MASTER`
 ADD PRIMARY KEY (`PAGE_TYPE_ID`), ADD UNIQUE KEY `PAGE_TYPE_DESCR` (`PAGE_TYPE_DESCR`), ADD KEY `PAGE_TYPE_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `PAGE_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `PAGE_TYPE_MOD_DET`
--
ALTER TABLE `PAGE_TYPE_MOD_DET`
 ADD PRIMARY KEY (`PAGE_TYPE_MOD_DET_ID`), ADD KEY `PAGE_TYPE_MOD_DET_FK1` (`PAGE_TYPE_ID`), ADD KEY `PAGE_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `PAIN_LEVELS_MASTER`
--
ALTER TABLE `PAIN_LEVELS_MASTER`
 ADD PRIMARY KEY (`PAIN_LEVEL_ID`), ADD UNIQUE KEY `PAIN_ID` (`PAIN_ID`,`PAIN_LEVEL_DESCR`), ADD KEY `PAIN_LEVELS_MASTER_FK2` (`CREATED_BY`), ADD KEY `PAIN_LEVELS_MASTER_FK3` (`STATUS_ID`);

--
-- Indexes for table `PAIN_LEVEL_MOD_DET`
--
ALTER TABLE `PAIN_LEVEL_MOD_DET`
 ADD PRIMARY KEY (`PAIN_LEVEL_MOD_DET_ID`), ADD KEY `PAIN_LEVEL_MOD_DET_FK1` (`PAIN_LEVEL_ID`), ADD KEY `PAIN_LEVEL_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `PAIN_MASTER`
--
ALTER TABLE `PAIN_MASTER`
 ADD PRIMARY KEY (`PAIN_ID`), ADD UNIQUE KEY `PAIN_DESCR` (`PAIN_DESCR`), ADD KEY `PAIN_MASTER_FK1` (`CREATED_BY`), ADD KEY `PAIN_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `PAIN_TYPE_MOD_DET`
--
ALTER TABLE `PAIN_TYPE_MOD_DET`
 ADD PRIMARY KEY (`PAIN_TYPE_MOD_DET_ID`), ADD KEY `PAIN_TYPE_MOD_DET_FK1` (`PAIN_ID`), ADD KEY `PAIN_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `PATIENT_CARE_GIVERS`
--
ALTER TABLE `PATIENT_CARE_GIVERS`
 ADD PRIMARY KEY (`PATIENT_CARE_GIVER_ID`), ADD KEY `PATIENT_CARE_GIVERS_FK1` (`RELATIONSHIP_ID`), ADD KEY `PATIENT_CARE_GIVERS_FK2` (`USER_ID`), ADD KEY `PATIENT_CARE_GIVERS_FK3` (`PATIENT_ID`), ADD KEY `PATIENT_CARE_GIVERS_FK4` (`LAST_EDITED_BY`), ADD KEY `PATIENT_CARE_GIVERS_FK5` (`STATUS_ID`);

--
-- Indexes for table `PATIENT_CARE_GIVER_MOD_DET`
--
ALTER TABLE `PATIENT_CARE_GIVER_MOD_DET`
 ADD PRIMARY KEY (`PATIENT_CARE_GIVER_MOD_DET_ID`), ADD KEY `PATIENT_CARE_GIVER_MOD_DET_FK1` (`PATIENT_CARE_GIVER_ID`), ADD KEY `PATIENT_CARE_GIVER_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `PHOTO_TYPE_MASTER`
--
ALTER TABLE `PHOTO_TYPE_MASTER`
 ADD PRIMARY KEY (`PHOTO_TYPE_ID`), ADD UNIQUE KEY `PHOTO_TYPE` (`PHOTO_TYPE`), ADD KEY `PHOTO_TYPE_MASTER_FK1` (`CREATED_BY`), ADD KEY `PHOTO_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `PHOTO_TYPE_MOD_DET`
--
ALTER TABLE `PHOTO_TYPE_MOD_DET`
 ADD PRIMARY KEY (`PHOTO_TYPE_MOD_DET_ID`), ADD KEY `PHOTO_TYPE_MOD_DET_FK1` (`PHOTO_TYPE_ID`), ADD KEY `PHOTO_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `POLLS`
--
ALTER TABLE `POLLS`
 ADD PRIMARY KEY (`POLL_ID`), ADD KEY `POLL_FK1` (`POLL_SECTION_TYPE_ID`), ADD KEY `POLL_FK2` (`CREATED_BY`), ADD KEY `POLL_FK3` (`LAST_EDITED_BY`), ADD KEY `POLL_FK4` (`STATUS_ID`);

--
-- Indexes for table `POLL_CHOICES`
--
ALTER TABLE `POLL_CHOICES`
 ADD PRIMARY KEY (`POLL_CHOICE_ID`), ADD KEY `POLL_CHOICE_FK1` (`POLL_ID`), ADD KEY `POLL_CHOICE_FK2` (`LAST_EDITED_BY`), ADD KEY `POLL_CHOICE_FK3` (`STATUS_ID`);

--
-- Indexes for table `POLL_CHOICE_MOD_DET`
--
ALTER TABLE `POLL_CHOICE_MOD_DET`
 ADD PRIMARY KEY (`POLL_CHOICE_MOD_DET_ID`), ADD KEY `POLL_CHOICE_MOD_DET_FK1` (`POLL_CHOICE_ID`), ADD KEY `POLL_CHOICE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `POLL_MOD_DET`
--
ALTER TABLE `POLL_MOD_DET`
 ADD PRIMARY KEY (`POLL_MOD_DET_ID`), ADD KEY `POLL_MOD_DET_FK1` (`POLL_ID`), ADD KEY `POLL_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `POSTS`
--
ALTER TABLE `POSTS`
 ADD PRIMARY KEY (`POST_ID`), ADD KEY `POSTS_FK2` (`CREATED_BY`), ADD KEY `POSTS_FK5` (`POST_TYPE_ID`), ADD KEY `POSTS_FK1` (`STATUS_ID`);

--
-- Indexes for table `POST_COMMENTS`
--
ALTER TABLE `POST_COMMENTS`
 ADD PRIMARY KEY (`POST_COMMENT_ID`), ADD KEY `POST_COMMENTS_FK1` (`POST_ID`), ADD KEY `POST_COMMENTS_FK2` (`LAST_EDITED_BY`), ADD KEY `POST_COMMENTS_FK3` (`STATUS_ID`);

--
-- Indexes for table `POST_COMMENTS_MOD_DET`
--
ALTER TABLE `POST_COMMENTS_MOD_DET`
 ADD PRIMARY KEY (`POST_COMMENT_MOD_DET_ID`), ADD KEY `POST_COMMENTS_MOD_DET_FK1` (`POST_COMMENT_ID`), ADD KEY `POST_COMMENTS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `POST_CONTENT_DETAILS`
--
ALTER TABLE `POST_CONTENT_DETAILS`
 ADD PRIMARY KEY (`POST_CONTENT_ID`), ADD KEY `POST_CONTENT_DETAILS_FK1` (`POST_ID`), ADD KEY `POST_CONTENT_DETAILS_FK2` (`CONTENT_ATTRIBUTE_ID`);

--
-- Indexes for table `POST_CONTENT_MOD_DET`
--
ALTER TABLE `POST_CONTENT_MOD_DET`
 ADD PRIMARY KEY (`POST_CONTENT_MOD_DET_ID`), ADD KEY `POST_CONTENT_MOD_DET_FK1` (`POST_CONTENT_ID`), ADD KEY `POST_CONTENT_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `POST_LIKES`
--
ALTER TABLE `POST_LIKES`
 ADD PRIMARY KEY (`POST_LIKE_ID`), ADD UNIQUE KEY `POST_ID` (`POST_ID`,`LIKED_BY`), ADD KEY `POST_LIKES_FK2` (`LIKED_BY`), ADD KEY `POST_LIKES_FK3` (`POST_LIKE_STATUS`);

--
-- Indexes for table `POST_LIKES_MOD_DET`
--
ALTER TABLE `POST_LIKES_MOD_DET`
 ADD PRIMARY KEY (`POST_LIKE_MOD_DET_ID`), ADD KEY `POST_LIKES_MOD_DET_FK1` (`POST_LIKE_ID`), ADD KEY `POST_LIKES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `POST_LOCATION`
--
ALTER TABLE `POST_LOCATION`
 ADD PRIMARY KEY (`POST_LOCATION_ID`), ADD UNIQUE KEY `POST_ID` (`POST_ID`,`POST_LOCATION`), ADD KEY `POST_LOCATION_FK2` (`POST_LOCATION`), ADD KEY `POST_LOCATION_FK3` (`LAST_EDITED_BY`), ADD KEY `POST_LOCATION_FK4` (`STATUS_ID`);

--
-- Indexes for table `POST_LOCATION_MASTER`
--
ALTER TABLE `POST_LOCATION_MASTER`
 ADD PRIMARY KEY (`POST_LOCATION_ID`), ADD UNIQUE KEY `POST_LOCATION_DESCR` (`POST_LOCATION_DESCR`), ADD KEY `POST_LOCATION_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `POST_LOCATION_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `POST_LOCATION_MASTER_MOD_DET`
--
ALTER TABLE `POST_LOCATION_MASTER_MOD_DET`
 ADD PRIMARY KEY (`POST_LOCATION_MASTER_MOD_DET_ID`), ADD KEY `POST_LOCATION_MASTER_MOD_DET_FK1` (`POST_LOCATION_ID`), ADD KEY `POST_LOCATION_MASTER_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `POST_LOCATION_MOD_DET`
--
ALTER TABLE `POST_LOCATION_MOD_DET`
 ADD PRIMARY KEY (`POST_LOCATION_MOD_DET_ID`), ADD KEY `POST_LOCATION_MOD_DET_FK1` (`POST_LOCATION_ID`), ADD KEY `POST_LOCATION_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `POST_MOD_DET`
--
ALTER TABLE `POST_MOD_DET`
 ADD PRIMARY KEY (`POST_MOD_DET_ID`), ADD KEY `POST_MOD_DET_FK1` (`POST_ID`), ADD KEY `POST_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `POST_PRIVACY_MOD_DET`
--
ALTER TABLE `POST_PRIVACY_MOD_DET`
 ADD PRIMARY KEY (`POST_PRIVACY_MOD_DET_ID`), ADD KEY `POST_PRIVACY_MOD_DET_FK1` (`POST_PRIVACY_ID`), ADD KEY `POST_PRIVACY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `POST_PRIVACY_SETTINGS`
--
ALTER TABLE `POST_PRIVACY_SETTINGS`
 ADD PRIMARY KEY (`POST_PRIVACY_ID`), ADD UNIQUE KEY `POST_ID` (`POST_ID`,`USER_TYPE_ID`), ADD KEY `POST_PRIVACY_FK2` (`USER_TYPE_ID`), ADD KEY `POST_PRIVACY_FK3` (`PRIVACY_ID`), ADD KEY `POST_PRIVACY_FK4` (`LAST_EDITED_BY`), ADD KEY `POST_PRIVACY_FK5` (`STATUS_ID`);

--
-- Indexes for table `POST_TYPE_MASTER`
--
ALTER TABLE `POST_TYPE_MASTER`
 ADD PRIMARY KEY (`POST_TYPE_ID`), ADD UNIQUE KEY `POST_TYPE_TEXT` (`POST_TYPE_TEXT`), ADD KEY `POST_TYPE_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `POST_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `POST_TYPE_MOD_DET`
--
ALTER TABLE `POST_TYPE_MOD_DET`
 ADD PRIMARY KEY (`POST_TYPE_MOD_DET_ID`), ADD KEY `POST_TYPE_MOD_DET_FK1` (`POST_TYPE_ID`), ADD KEY `POST_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `PRIVACY_MASTER`
--
ALTER TABLE `PRIVACY_MASTER`
 ADD PRIMARY KEY (`PRIVACY_ID`), ADD UNIQUE KEY `PRIVAVCY_TEXT` (`PRIVACY_TEXT`), ADD KEY `PRIVACY_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `PRIVACY_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `PRIVACY_MOD_DET`
--
ALTER TABLE `PRIVACY_MOD_DET`
 ADD PRIMARY KEY (`PRIVACY_MOD_DET_ID`), ADD KEY `PRIVACY_MOD_DET_FK1` (`PRIVACY_ID`), ADD KEY `PRIVACY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `PUBLISH_TYPE_MASTER`
--
ALTER TABLE `PUBLISH_TYPE_MASTER`
 ADD PRIMARY KEY (`PUBLISH_TYPE_ID`), ADD UNIQUE KEY `PUBLISH_TYPE_DESCR` (`PUBLISH_TYPE_DESCR`), ADD KEY `PUBLISH_TYPE_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `PUBLISH_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `PUBLISH_TYPE_MOD_DET`
--
ALTER TABLE `PUBLISH_TYPE_MOD_DET`
 ADD PRIMARY KEY (`PUBLISH_TYPE_MOD_DET_ID`), ADD KEY `PUBLISH_TYPE_MOD_DET_FK1` (`PUBLISH_TYPE_ID`), ADD KEY `PUBLISH_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `QUESTION_GROUP_MASTER`
--
ALTER TABLE `QUESTION_GROUP_MASTER`
 ADD PRIMARY KEY (`QUESTION_GROUP_ID`), ADD UNIQUE KEY `QUESTION_GROUP` (`QUESTION_GROUP`), ADD KEY `QUESTION_GROUP_FK1` (`STATUS_ID`), ADD KEY `QUESTION_GROUP_FK2` (`LAST_EDITED_BY`);

--
-- Indexes for table `QUESTION_GROUP_MOD_DET`
--
ALTER TABLE `QUESTION_GROUP_MOD_DET`
 ADD PRIMARY KEY (`QUESTION_GROUP_MOD_DET_ID`), ADD KEY `QUESTION_GROUP_MOD_DET_FK1` (`QUESTION_GROUP_ID`), ADD KEY `QUESTION_GROUP_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `QUESTION_MASTER`
--
ALTER TABLE `QUESTION_MASTER`
 ADD PRIMARY KEY (`QUESTION_ID`), ADD UNIQUE KEY `QUESTION_TEXT` (`QUESTION_TEXT`,`QUESTION_GROUP_ID`), ADD KEY `QUESTION_FK1` (`STATUS_ID`), ADD KEY `QUESTION_FK2` (`QUESTION_GROUP_ID`), ADD KEY `QUESTION_FK3` (`LAST_EDITED_BY`);

--
-- Indexes for table `QUESTION_MOD_DET`
--
ALTER TABLE `QUESTION_MOD_DET`
 ADD PRIMARY KEY (`QUESTION_MOD_DET_ID`), ADD KEY `QUESTION_MOD_DET_FK1` (`QUESTION_ID`), ADD KEY `QUESTION_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `REPEAT_BY_TYPE_MASTER`
--
ALTER TABLE `REPEAT_BY_TYPE_MASTER`
 ADD PRIMARY KEY (`REPEAT_BY_TYPE_ID`), ADD KEY `REPEAT_BY_TYPE_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `REPEAT_BY_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `REPEAT_BY_TYPE_MOD_DET`
--
ALTER TABLE `REPEAT_BY_TYPE_MOD_DET`
 ADD PRIMARY KEY (`REPEAT_BY_TYPE_MOD_DET_ID`), ADD KEY `REPEAT_BY_TYPE_MOD_DET_FK1` (`REPEAT_BY_TYPE_ID`), ADD KEY `REPEAT_BY_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `REPEAT_END_TYPE_MASTER`
--
ALTER TABLE `REPEAT_END_TYPE_MASTER`
 ADD PRIMARY KEY (`REPEAT_END_TYPE_ID`), ADD UNIQUE KEY `REPEAT_END_TYPE_DESCR` (`REPEAT_END_TYPE_DESCR`), ADD KEY `REPEAT_END_TYPE_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `REPEAT_END_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `REPEAT_END_TYPE_MOD_DET`
--
ALTER TABLE `REPEAT_END_TYPE_MOD_DET`
 ADD PRIMARY KEY (`REPEAT_END_TYPE_MOD_DET_ID`), ADD KEY `REPEAT_END_TYPE_MOD_DET_FK1` (`REPEAT_END_TYPE_ID`), ADD KEY `REPEAT_END_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `REPEAT_MODE_TYPE_MASTER`
--
ALTER TABLE `REPEAT_MODE_TYPE_MASTER`
 ADD PRIMARY KEY (`REPEAT_MODE_TYPE_ID`), ADD UNIQUE KEY `REPEAT_MODE_TYPE_DESCR` (`REPEAT_MODE_TYPE_DESCR`), ADD KEY `REPEAT_MODE_TYPE_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `REPEAT_MODE_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `REPEAT_MODE_TYPE_MOD_DET`
--
ALTER TABLE `REPEAT_MODE_TYPE_MOD_DET`
 ADD PRIMARY KEY (`REPEAT_MODE_TYPE_MOD_DET_ID`), ADD KEY `REPEAT_MODE_TYPE_MOD_DET_FK1` (`REPEAT_MODE_TYPE_ID`), ADD KEY `REPEAT_MODE_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `REPEAT_TYPE_MASTER`
--
ALTER TABLE `REPEAT_TYPE_MASTER`
 ADD PRIMARY KEY (`REPEAT_TYPE_ID`), ADD UNIQUE KEY `REPEAT_TYPE_DESCR` (`REPEAT_TYPE_DESCR`), ADD KEY `REPEAT_TYPE_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `REPEAT_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `REPEAT_TYPE_MOD_DET`
--
ALTER TABLE `REPEAT_TYPE_MOD_DET`
 ADD PRIMARY KEY (`REPEAT_TYPE_MOD_DET_ID`), ADD KEY `REPEAT_TYPE_MOD_DET_FK1` (`REPEAT_TYPE_ID`), ADD KEY `REPEAT_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `SECTION_TYPE_MASTER`
--
ALTER TABLE `SECTION_TYPE_MASTER`
 ADD PRIMARY KEY (`SECTION_TYPE_ID`), ADD UNIQUE KEY `SECTION_TYPE_DESCR` (`SECTION_TYPE_DESCR`), ADD KEY `SECTION_TYPE_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `SECTION_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `SECTION_TYPE_MOD_DET`
--
ALTER TABLE `SECTION_TYPE_MOD_DET`
 ADD PRIMARY KEY (`SECTION_TYPE_MOD_DET_ID`), ADD KEY `SECTION_TYPE_MOD_DET_FK1` (`SECTION_TYPE_ID`), ADD KEY `SECTION_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `STATES_MASTER`
--
ALTER TABLE `STATES_MASTER`
 ADD PRIMARY KEY (`STATE_ID`), ADD UNIQUE KEY `COUNTRY_ID` (`COUNTRY_ID`,`DESCRIPTION`), ADD KEY `STATES_FK2` (`CREATED_BY`), ADD KEY `STATES_FK3` (`MODIFIED_BY`), ADD KEY `STATES_FK4` (`STATUS_ID`);

--
-- Indexes for table `STATES_MOD_DET`
--
ALTER TABLE `STATES_MOD_DET`
 ADD PRIMARY KEY (`STATES_MOD_DET_ID`), ADD KEY `STATES_MOD_DET_FK1` (`STATE_ID`), ADD KEY `STATES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `STATUS`
--
ALTER TABLE `STATUS`
 ADD PRIMARY KEY (`STATUS_ID`), ADD UNIQUE KEY `STATUS` (`STATUS`,`STATUS_TYPE_ID`), ADD KEY `STATUS_FK1` (`STATUS_TYPE_ID`), ADD KEY `STATUS_FK2` (`LAST_EDITED_BY`);

--
-- Indexes for table `STATUS_MOD_DET`
--
ALTER TABLE `STATUS_MOD_DET`
 ADD PRIMARY KEY (`STATUS_MOD_DET_ID`), ADD KEY `STATUS_MOD_DET_FK1` (`STATUS_ID`), ADD KEY `STATUS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `STATUS_TYPE`
--
ALTER TABLE `STATUS_TYPE`
 ADD PRIMARY KEY (`STATUS_TYPE_ID`), ADD UNIQUE KEY `STATUS_TYPE` (`STATUS_TYPE`), ADD KEY `STATUS_TYPE_FK1` (`LAST_EDITED_BY`);

--
-- Indexes for table `STATUS_TYPE_MOD_DET`
--
ALTER TABLE `STATUS_TYPE_MOD_DET`
 ADD PRIMARY KEY (`STATUS_TYPE_MOD_DET_ID`), ADD KEY `STATUS_TYPE_MOD_DET_FK1` (`STATUS_TYPE_ID`), ADD KEY `STATUS_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `SURVEY_MASTER`
--
ALTER TABLE `SURVEY_MASTER`
 ADD PRIMARY KEY (`SURVEY_ID`), ADD UNIQUE KEY `SURVEY_NAME` (`SURVEY_NAME`), ADD KEY `SURVEY_MASTER_FK1` (`SURVEY_TYPE`), ADD KEY `SURVEY_MASTER_FK2` (`SURVEY_STATUS`), ADD KEY `SURVEY_MASTER_FK3` (`CREATED_BY`);

--
-- Indexes for table `SURVEY_MOD_DET`
--
ALTER TABLE `SURVEY_MOD_DET`
 ADD PRIMARY KEY (`SURVEY_MOD_DET_ID`), ADD KEY `SURVEY_MOD_DET_FK1` (`SURVEY_ID`), ADD KEY `SURVEY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `SURVEY_QUESTIONS`
--
ALTER TABLE `SURVEY_QUESTIONS`
 ADD PRIMARY KEY (`SURVEY_QUESTION_ID`), ADD UNIQUE KEY `SURVEY_ID` (`SURVEY_ID`,`QUESTION_ID`), ADD KEY `SURVEY_QUESTIONS_FK2` (`QUESTION_ID`), ADD KEY `SURVEY_QUESTIONS_FK3` (`CREATED_BY`), ADD KEY `SURVEY_QUESTIONS_FK4` (`STATUS_ID`);

--
-- Indexes for table `SURVEY_QUESTIONS_ANSWER_CHOICES`
--
ALTER TABLE `SURVEY_QUESTIONS_ANSWER_CHOICES`
 ADD PRIMARY KEY (`SURVEY_QUESTIONS_ANSWER_CHOICE_ID`), ADD UNIQUE KEY `SURVEY_QUESTION_ID` (`SURVEY_QUESTION_ID`,`ANSWER_CHOICE_TEXT`), ADD KEY `SURVEY_QUESTIONS_ANSWER_CHOICES_FK2` (`STATUS_ID`), ADD KEY `SURVEY_QUESTIONS_ANSWER_CHOICES_FK3` (`CREATED_BY`);

--
-- Indexes for table `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET`
--
ALTER TABLE `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET`
 ADD PRIMARY KEY (`SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET_ID`), ADD KEY `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET_FK1` (`SURVEY_QUESTIONS_ANSWER_CHOICE_ID`), ADD KEY `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `SURVEY_QUESTIONS_MOD_DET`
--
ALTER TABLE `SURVEY_QUESTIONS_MOD_DET`
 ADD PRIMARY KEY (`SURVEY_QUESTIONS_MOD_DET_ID`), ADD KEY `SURVEY_QUESTIONS_MOD_DET_FK1` (`SURVEY_QUESTION_ID`), ADD KEY `SURVEY_QUESTIONS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `SURVEY_RESULTS_ANSWER_CHOICES`
--
ALTER TABLE `SURVEY_RESULTS_ANSWER_CHOICES`
 ADD PRIMARY KEY (`SURVEY_RESULTS_ANSWER_CHOICE_ID`), ADD UNIQUE KEY `USER_ID` (`USER_ID`,`SURVEY_QUESTIONS_ANSWER_CHOICE_ID`), ADD KEY `SURVEY_RESULTS_ANSWER_CHOICES_FK1` (`SURVEY_QUESTIONS_ANSWER_CHOICE_ID`), ADD KEY `SURVEY_RESULTS_ANSWER_CHOICES_FK3` (`STATUS_ID`), ADD KEY `SURVEY_RESULTS_ANSWER_CHOICES_FK4` (`LAST_EDITED_BY`);

--
-- Indexes for table `SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET`
--
ALTER TABLE `SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET`
 ADD PRIMARY KEY (`SURVEY_RESULTS_ANSWER_CHOICE_MOD_DET_ID`), ADD KEY `SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET_FK1` (`SURVEY_RESULTS_ANSWER_CHOICE_ID`), ADD KEY `SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `SURVEY_RESULTS_DETAILED_ANSWERS`
--
ALTER TABLE `SURVEY_RESULTS_DETAILED_ANSWERS`
 ADD PRIMARY KEY (`SURVEY_RESULTS_DETAILED_ANSWER_ID`), ADD UNIQUE KEY `USER_ID` (`USER_ID`,`SURVEY_QUESTION_ID`), ADD KEY `SURVEY_RESULTS_DETAILED_ANSWERS_FK1` (`SURVEY_QUESTION_ID`), ADD KEY `SURVEY_RESULTS_DETAILED_ANSWERS_FK3` (`STATUS_ID`), ADD KEY `SURVEY_RESULTS_DETAILED_ANSWERS_FK4` (`LAST_EDITED_BY`);

--
-- Indexes for table `SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET`
--
ALTER TABLE `SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET`
 ADD PRIMARY KEY (`SURVEY_RESULTS_DETAILED_ANSWER_MOD_DET_ID`), ADD KEY `SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET_FK1` (`SURVEY_RESULTS_DETAILED_ANSWER_ID`), ADD KEY `SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `SURVEY_TYPE_MASTER`
--
ALTER TABLE `SURVEY_TYPE_MASTER`
 ADD PRIMARY KEY (`SURVEY_TYPE_ID`), ADD UNIQUE KEY `SURVEY_TYPE` (`SURVEY_TYPE`), ADD KEY `SURVEY_TYPE_MASTER_FK1` (`STATUS_ID`), ADD KEY `SURVEY_TYPE_MASTER_FK2` (`LAST_EDITED_BY`);

--
-- Indexes for table `SURVEY_TYPE_MOD_DET`
--
ALTER TABLE `SURVEY_TYPE_MOD_DET`
 ADD PRIMARY KEY (`SURVEY_TYPE_MOD_DET_ID`), ADD KEY `SURVEY_TYPE_MOD_DET_FK1` (`SURVEY_TYPE_ID`), ADD KEY `SURVEY_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `SYMPTOMS_MASTER`
--
ALTER TABLE `SYMPTOMS_MASTER`
 ADD PRIMARY KEY (`SYMPTOM_ID`), ADD UNIQUE KEY `SYMPTOM` (`SYMPTOM`), ADD KEY `SYMPTOMS_MASTER_FK1` (`CREATED_BY`), ADD KEY `SYMPTOMS_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `SYMPTOMS_MOD_DET`
--
ALTER TABLE `SYMPTOMS_MOD_DET`
 ADD PRIMARY KEY (`SYMPTOM_MOD_DET_ID`), ADD KEY `SYMPTOM_MOD_DET_FK1` (`SYMPTOM_ID`), ADD KEY `SYMPTOM_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `TEAMS`
--
ALTER TABLE `TEAMS`
 ADD PRIMARY KEY (`TEAM_ID`), ADD UNIQUE KEY `TEAM_NAME` (`TEAM_NAME`,`PATIENT_ID`), ADD KEY `TEAMS_FK1` (`PATIENT_ID`), ADD KEY `TEAMS_FK2` (`CREATED_BY`), ADD KEY `TEAMS_FK3` (`TEAM_STATUS`);

--
-- Indexes for table `TEAM_MEMBERS`
--
ALTER TABLE `TEAM_MEMBERS`
 ADD PRIMARY KEY (`TEAM_MEMBER_ID`), ADD UNIQUE KEY `USER_ID` (`USER_ID`,`TEAM_ID`,`USER_ROLE_ID`), ADD KEY `TEAM_MEMBERS_FK1` (`TEAM_ID`), ADD KEY `TEAM_MEMBERS_FK3` (`USER_ROLE_ID`), ADD KEY `TEAM_MEMBERS_FK4` (`MEMBER_STATUS`), ADD KEY `TEAM_MEMBERS_FK5` (`LAST_EDITED_BY`);

--
-- Indexes for table `TEAM_MEMBERS_MOD_DET`
--
ALTER TABLE `TEAM_MEMBERS_MOD_DET`
 ADD PRIMARY KEY (`TEAM_MEMBER_MOD_DET_ID`), ADD KEY `TEAM_MEMBERS_MOD_DET_FK1` (`TEAM_MEMBER_ID`), ADD KEY `TEAM_MEMBERS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `TEAM_MOD_DET`
--
ALTER TABLE `TEAM_MOD_DET`
 ADD PRIMARY KEY (`TEAM_MOD_DET_ID`), ADD KEY `TEAM_MOD_DET_FK1` (`TEAM_ID`), ADD KEY `TEAM_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `TEAM_PRIVACY_SETTINGS`
--
ALTER TABLE `TEAM_PRIVACY_SETTINGS`
 ADD PRIMARY KEY (`TEAM_PRIVACY_SETTING_ID`), ADD UNIQUE KEY `TEAM_ID` (`TEAM_ID`,`USER_TYPE_ID`), ADD KEY `TEAM_PRIVACY_SETTINGS_FK2` (`USER_TYPE_ID`), ADD KEY `TEAM_PRIVACY_SETTINGS_FK3` (`PRIVACY_ID`), ADD KEY `TEAM_PRIVACY_SETTINGS_FK4` (`PRIVACY_SET_BY`), ADD KEY `TEAM_PRIVACY_SETTINGS_FK5` (`STATUS_ID`);

--
-- Indexes for table `TEAM_PRIVACY_SETTING_MOD_DET`
--
ALTER TABLE `TEAM_PRIVACY_SETTING_MOD_DET`
 ADD PRIMARY KEY (`TEAM_PRIVACY_SETTING_MOD_DET_ID`), ADD KEY `TEAM_PRIVACY_SETTING_MOD_DET_FK1` (`TEAM_PRIVACY_SETTING_ID`), ADD KEY `TEAM_PRIVACY_SETTING_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `TIMEZONE_MASTER`
--
ALTER TABLE `TIMEZONE_MASTER`
 ADD PRIMARY KEY (`TIMEZONE_ID`), ADD UNIQUE KEY `TIMEZONE` (`TIMEZONE`), ADD KEY `TIMEZONE_FK1` (`LAST_EDITED_BY`), ADD KEY `TIMEZONE_FK2` (`STATUS_ID`);

--
-- Indexes for table `TIMEZONE_MOD_DET`
--
ALTER TABLE `TIMEZONE_MOD_DET`
 ADD PRIMARY KEY (`TIMEZONE_MOD_DET_ID`), ADD KEY `TIMEZONE_MOD_DET_FK1` (`TIMEZONE_ID`), ADD KEY `TIMEZONE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `TREATMENT_MASTER`
--
ALTER TABLE `TREATMENT_MASTER`
 ADD PRIMARY KEY (`TREATMENT_ID`), ADD UNIQUE KEY `TREATMENT_DESCR` (`TREATMENT_DESCR`), ADD KEY `TREATMENT_MASTER_FK1` (`CREATED_BY`), ADD KEY `TREATMENT_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `TREATMENT_MASTER_MOD_DET`
--
ALTER TABLE `TREATMENT_MASTER_MOD_DET`
 ADD PRIMARY KEY (`TREATMENT_MASTER_MOD_DET_ID`), ADD KEY `TREATMENT_MASTER_MOD_DET_FK1` (`TREATMENT_ID`), ADD KEY `TREATMENT_MASTER_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `UNIT_OF_MEASUREMENT_MASTER`
--
ALTER TABLE `UNIT_OF_MEASUREMENT_MASTER`
 ADD PRIMARY KEY (`UNIT_ID`), ADD UNIQUE KEY `UNIT_DESCR` (`UNIT_DESCR`), ADD KEY `UOM_FK1` (`STATUS_ID`), ADD KEY `UOM_FK2` (`CREATED_BY`);

--
-- Indexes for table `UNIT_OF_MEASUREMENT_MOD_DET`
--
ALTER TABLE `UNIT_OF_MEASUREMENT_MOD_DET`
 ADD PRIMARY KEY (`UNIT_OF_MEASUREMENT_MOD_DET_ID`), ADD KEY `UOM_MOD_DET_FK1` (`UNIT_ID`), ADD KEY `UOM_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `USERS`
--
ALTER TABLE `USERS`
 ADD PRIMARY KEY (`USER_ID`), ADD KEY `USERS_FK3` (`COUNTRY`), ADD KEY `USERS_FK5` (`CITY`), ADD KEY `USERS_FK4` (`STATE`), ADD KEY `USERS_FK6` (`USER_TYPE`), ADD KEY `USERS_FK7` (`TIMEZONE`), ADD KEY `USERS_FK1` (`STATUS_ID`), ADD KEY `USERS_FK2` (`LANGUAGE`), ADD KEY `USERS_FK8` (`LAST_EDITED_BY`);

--
-- Indexes for table `USER_ACTIVITY_LOGS`
--
ALTER TABLE `USER_ACTIVITY_LOGS`
 ADD PRIMARY KEY (`USER_ACTIVITY_ID`), ADD KEY `USER_ACTIVITY_LOGS_FK1` (`USER_ID`), ADD KEY `USER_ACTIVITY_LOGS_FK2` (`LAST_EDITED_BY`), ADD KEY `USER_ACTIVITY_LOGS_FK3` (`STATUS_ID`);

--
-- Indexes for table `USER_ACTIVITY_MOD_DET`
--
ALTER TABLE `USER_ACTIVITY_MOD_DET`
 ADD PRIMARY KEY (`USER_ACTIVITY_MOD_DET_ID`), ADD KEY `USER_ACTIVITY_MOD_DET_FK1` (`USER_ACTIVITY_ID`), ADD KEY `USER_ACTIVITY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `USER_ATTRIBUTES`
--
ALTER TABLE `USER_ATTRIBUTES`
 ADD PRIMARY KEY (`USER_ATTRIBUTE_ID`), ADD KEY `USER_ATTRIBUTES_FK2` (`USER_ID`), ADD KEY `USER_ATTRIBUTES_FK3` (`CREATED_BY`), ADD KEY `USER_ATTRIBUTES_FK4` (`STATUS_ID`), ADD KEY `ATTRIBUTE_ID` (`ATTRIBUTE_ID`);

--
-- Indexes for table `USER_ATTRIBUTE_MOD_HISTORY`
--
ALTER TABLE `USER_ATTRIBUTE_MOD_HISTORY`
 ADD PRIMARY KEY (`USER_ATTRIBUTE_MOD_HISTORY_ID`), ADD KEY `USER_ATTRIBUTE_MOD_FK1` (`USER_ATTRIBUTE_ID`), ADD KEY `USER_ATTRIBUTE_MOD_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `USER_DISEASES`
--
ALTER TABLE `USER_DISEASES`
 ADD PRIMARY KEY (`USER_DISEASE_ID`), ADD UNIQUE KEY `DISEASE_ID` (`DISEASE_ID`,`USER_ID`), ADD KEY `USER_DISEASES_FK2` (`USER_ID`), ADD KEY `USER_DISEASES_FK3` (`CREATED_BY`), ADD KEY `USER_DISEASES_FK4` (`STATUS_ID`);

--
-- Indexes for table `USER_DISEASES_MOD_DET`
--
ALTER TABLE `USER_DISEASES_MOD_DET`
 ADD PRIMARY KEY (`USER_DISEASES_MOD_DET_ID`), ADD KEY `USER_DISEASES_MOD_DET_FK1` (`USER_DISEASE_ID`), ADD KEY `USER_DISEASES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `USER_FAVORITE_POSTS`
--
ALTER TABLE `USER_FAVORITE_POSTS`
 ADD PRIMARY KEY (`USER_FAVORITE_POST_ID`), ADD UNIQUE KEY `USER_ID` (`USER_ID`,`POST_ID`), ADD KEY `USER_FAVORITE_POSTS_FK2` (`POST_ID`);

--
-- Indexes for table `USER_FAV_POSTS_MOD_DET`
--
ALTER TABLE `USER_FAV_POSTS_MOD_DET`
 ADD PRIMARY KEY (`USER_FAV_POST_MOD_DET_ID`), ADD KEY `USER_FAV_POSTS_MOD_DET_FK1` (`USER_FAVORITE_POST_ID`), ADD KEY `USER_FAV_POSTS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `USER_HEALTH_HISTORY_DET`
--
ALTER TABLE `USER_HEALTH_HISTORY_DET`
 ADD PRIMARY KEY (`USER_HEALTH_HISTORY_DET_ID`), ADD KEY `USER_HEALTH_HISTORY_FK1` (`USER_ID`), ADD KEY `USER_HEALTH_HISTORY_FK2` (`HEALTH_CONDITION_ID`), ADD KEY `USER_HEALTH_HISTORY_FK3` (`STATUS_ID`), ADD KEY `USER_HEALTH_HISTORY_FK4` (`CREATED_BY`);

--
-- Indexes for table `USER_HEALTH_HISTORY_MOD_DET`
--
ALTER TABLE `USER_HEALTH_HISTORY_MOD_DET`
 ADD PRIMARY KEY (`USER_HEALTH_HISTORY_MOD_DET_ID`), ADD KEY `HEALTH_HISTORY_MOD_DET_FK1` (`USER_HEALTH_HISTORY_DET_ID`), ADD KEY `HEALTH_HISTORY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `USER_HEALTH_READING`
--
ALTER TABLE `USER_HEALTH_READING`
 ADD PRIMARY KEY (`USER_HEALTH_READING_ID`), ADD KEY `USER_HEALTH_READING_FK1` (`USER_ID`), ADD KEY `USER_HEALTH_READING_FK2` (`ATTRIBUTE_TYPE_ID`), ADD KEY `USER_HEALTH_READING_FK3` (`UNIT_ID`), ADD KEY `USER_HEALTH_READING_FK4` (`DATE_RECORDED_ON`), ADD KEY `USER_HEALTH_READING_FK5` (`MONTH_RECORDED_ON`), ADD KEY `USER_HEALTH_READING_FK6` (`YEAR_RECORDED_ON`), ADD KEY `USER_HEALTH_READING_FK7` (`CREATED_BY`), ADD KEY `USER_HEALTH_READING_FK8` (`STATUS_ID`);

--
-- Indexes for table `USER_HEALTH_READING_MOD_DET`
--
ALTER TABLE `USER_HEALTH_READING_MOD_DET`
 ADD PRIMARY KEY (`USER_HEALTH_READING_MOD_DET_ID`), ADD KEY `USER_HEALTH_READING_MOD_DET_FK1` (`USER_HEALTH_READING_ID`), ADD KEY `USER_HEALTH_READING_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `USER_MEDIA`
--
ALTER TABLE `USER_MEDIA`
 ADD PRIMARY KEY (`USER_MEDIA_ID`), ADD KEY `USER_MEDIA_FK1` (`MEDIA_TYPE_ID`), ADD KEY `USER_MEDIA_FK2` (`USER_ID`), ADD KEY `USER_MEDIA_FK3` (`CREATED_BY`), ADD KEY `USER_MEDIA_FK4` (`STATUS_ID`);

--
-- Indexes for table `USER_MEDIA_MOD_DET`
--
ALTER TABLE `USER_MEDIA_MOD_DET`
 ADD PRIMARY KEY (`USER_MEDIA_MOD_DET_ID`), ADD KEY `USER_MEDIA_MOD_DET_FK1` (`USER_MEDIA_ID`), ADD KEY `USER_MEDIA_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `USER_MESSAGES`
--
ALTER TABLE `USER_MESSAGES`
 ADD PRIMARY KEY (`MESSAGE_ID`), ADD KEY `USER_MESSAGES_FK1` (`SENDER_USER_ID`), ADD KEY `USER_MESSAGES_FK2` (`STATUS_ID`);

--
-- Indexes for table `USER_MESSAGE_RECIPIENTS`
--
ALTER TABLE `USER_MESSAGE_RECIPIENTS`
 ADD PRIMARY KEY (`USER_MESSAGE_RECIPIENT_ID`), ADD UNIQUE KEY `MESSAGE_ID` (`MESSAGE_ID`,`RECIPIENT_USER_ID`,`RECIPIENT_ROLE_ID`), ADD KEY `MESSAGE_RECIPIENTS_FK2` (`RECIPIENT_USER_ID`), ADD KEY `MESSAGE_RECIPIENTS_FK3` (`RECIPIENT_ROLE_ID`);

--
-- Indexes for table `USER_MOOD_HISTORY`
--
ALTER TABLE `USER_MOOD_HISTORY`
 ADD PRIMARY KEY (`USER_MOOD_HISTORY_ID`), ADD KEY `USER_MOOD_HISTORY_FK1` (`USER_MOOD_ID`), ADD KEY `USER_MOOD_HISTORY_FK2` (`USER_ID`), ADD KEY `USER_MOOD_HISTORY_FK3` (`CREATED_BY`), ADD KEY `USER_MOOD_HISTORY_FK4` (`STATUS_ID`);

--
-- Indexes for table `USER_MOOD_HISTORY_MOD_DET`
--
ALTER TABLE `USER_MOOD_HISTORY_MOD_DET`
 ADD PRIMARY KEY (`USER_MOOD_HISTORY_MOD_DET_ID`), ADD KEY `USER_MOOD_HISTORY_MOD_DET_FK1` (`USER_MOOD_HISTORY_ID`), ADD KEY `USER_MOOD_HISTORY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `USER_PAIN_TRACKER`
--
ALTER TABLE `USER_PAIN_TRACKER`
 ADD PRIMARY KEY (`USER_PAIN_ID`), ADD KEY `USER_PAIN_TRACKER_FK1` (`USER_ID`), ADD KEY `USER_PAIN_TRACKER_FK2` (`PAIN_ID`), ADD KEY `USER_PAIN_TRACKER_FK3` (`PAIN_LEVEL_ID`), ADD KEY `USER_PAIN_TRACKER_FK4` (`DATE_EXPERIENCED_ON`), ADD KEY `USER_PAIN_TRACKER_FK5` (`MONTH_EXPERIENCED_ON`), ADD KEY `USER_PAIN_TRACKER_FK6` (`YEAR_EXPERIENCED_ON`), ADD KEY `USER_PAIN_TRACKER_FK8` (`STATUS_ID`), ADD KEY `USER_PAIN_TRACKER_FK7` (`LAST_EDITED_BY`);

--
-- Indexes for table `USER_PAIN_TRACKER_MOD_DET`
--
ALTER TABLE `USER_PAIN_TRACKER_MOD_DET`
 ADD PRIMARY KEY (`USER_PAIN_TRACKER_MOD_DET_ID`), ADD KEY `USER_PAIN_TRACKER_MOD_DET_FK1` (`USER_PAIN_ID`), ADD KEY `USER_PAIN_TRACKER_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `USER_PHOTOS`
--
ALTER TABLE `USER_PHOTOS`
 ADD PRIMARY KEY (`USER_PHOTO_ID`), ADD KEY `USER_PHOTOS_FK1` (`USER_ID`), ADD KEY `USER_PHOTOS_FK2` (`PHOTO_TYPE_ID`), ADD KEY `USER_PHOTOS_FK3` (`CREATED_BY`), ADD KEY `USER_PHOTOS_FK4` (`STATUS_ID`);

--
-- Indexes for table `USER_PHOTOS_MOD_DET`
--
ALTER TABLE `USER_PHOTOS_MOD_DET`
 ADD PRIMARY KEY (`USER_PHOTOS_MOD_DET_ID`), ADD KEY `USER_PHOTOS_MOD_DET_FK1` (`USER_PHOTO_ID`), ADD KEY `USER_PHOTOS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `USER_PRIVACY_MOD_DET`
--
ALTER TABLE `USER_PRIVACY_MOD_DET`
 ADD PRIMARY KEY (`USER_PRIVACY_MOD_DET_ID`), ADD KEY `USER_PRIVACY_MOD_DET_FK1` (`MODIFIED_BY`), ADD KEY `USER_PRIVACY_MOD_DET_FK2` (`USER_PRIVACY_ID`);

--
-- Indexes for table `USER_PRIVACY_SETTINGS`
--
ALTER TABLE `USER_PRIVACY_SETTINGS`
 ADD PRIMARY KEY (`USER_PRIVACY_ID`), ADD UNIQUE KEY `USER_ID` (`USER_ID`,`USER_TYPE_ID`,`ACTIVITY_SECTION_ID`), ADD KEY `USER_PRIVACY_FK2` (`USER_TYPE_ID`), ADD KEY `USER_PRIVACY_FK4` (`PRIVACY_ID`), ADD KEY `USER_PRIVACY_SETTINGS_FK5` (`LAST_EDITED_BY`), ADD KEY `USER_PRIVACY_SETTINGS_FK6` (`STATUS_ID`), ADD KEY `USER_PRIVACY_FK3` (`ACTIVITY_SECTION_ID`);

--
-- Indexes for table `USER_PSSWRD_CHALLENGE_QUES`
--
ALTER TABLE `USER_PSSWRD_CHALLENGE_QUES`
 ADD PRIMARY KEY (`USER_PSSWRD_QUES_ID`), ADD UNIQUE KEY `PSSWRD_QUES_ID` (`PSSWRD_QUES_ID`,`USER_ID`), ADD KEY `CHALLENGE_QUES_FK2` (`USER_ID`);

--
-- Indexes for table `USER_PSSWRD_CHALLENGE_QUES_MOD_DET`
--
ALTER TABLE `USER_PSSWRD_CHALLENGE_QUES_MOD_DET`
 ADD PRIMARY KEY (`USER_PSSWRD_CHALLENGE_QUES_MOD_DET_ID`), ADD KEY `USER_PSSWRD_CHALLENGE_QUES_MOD_DET_FK1` (`USER_PSSWRD_QUES_ID`), ADD KEY `USER_PSSWRD_CHALLENGE_QUES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `USER_SYMPTOMS`
--
ALTER TABLE `USER_SYMPTOMS`
 ADD PRIMARY KEY (`USER_SYMPTOM_ID`), ADD UNIQUE KEY `USER_ID` (`USER_ID`,`SYMPTOM_ID`), ADD KEY `USER_SYMPTOMS_FK2` (`SYMPTOM_ID`), ADD KEY `USER_SYMPTOMS_FK3` (`STATUS_ID`), ADD KEY `USER_SYMPTOMS_FK4` (`CREATED_BY`);

--
-- Indexes for table `USER_SYMPTOMS_MOD_DET`
--
ALTER TABLE `USER_SYMPTOMS_MOD_DET`
 ADD PRIMARY KEY (`USER_SYMPTOMS_MOD_DET_ID`), ADD KEY `USER_SYMPTOMS_MOD_DET_FK1` (`USER_SYMPTOM_ID`), ADD KEY `USER_SYMPTOMS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `USER_SYMPTOM_RECORDS`
--
ALTER TABLE `USER_SYMPTOM_RECORDS`
 ADD PRIMARY KEY (`USER_SYMPTOM_RECORD_ID`), ADD KEY `USER_SYMPTOM_RECORDS_FK1` (`UNIT_ID`), ADD KEY `USER_SYMPTOM_RECORDS_FK2` (`LAST_EDITED_BY`), ADD KEY `USER_SYMPTOM_RECORDS_FK3` (`DATE_RECORDED_ON`), ADD KEY `USER_SYMPTOM_RECORDS_FK4` (`MONTH_RECORDED_ON`), ADD KEY `USER_SYMPTOM_RECORDS_FK5` (`YEAR_RECORDED_ON`), ADD KEY `USER_SYMPTOM_RECORDS_FK6` (`STATUS_ID`);

--
-- Indexes for table `USER_SYMPTOM_RECORDS_MOD_DET`
--
ALTER TABLE `USER_SYMPTOM_RECORDS_MOD_DET`
 ADD PRIMARY KEY (`USER_SYMPTOM_RECORDS_MOD_DET_ID`), ADD KEY `USER_SYMPTOM_RECORDS_MOD_DET_FK1` (`USER_SYMPTOM_RECORD_ID`), ADD KEY `USER_SYMPTOM_RECORDS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `USER_TYPE`
--
ALTER TABLE `USER_TYPE`
 ADD PRIMARY KEY (`USER_TYPE_ID`), ADD UNIQUE KEY `USER_TYPE` (`USER_TYPE`), ADD KEY `USER_TYPE_FK1` (`STATUS`), ADD KEY `USER_TYPE_FK2` (`LAST_EDITED_BY`);

--
-- Indexes for table `USER_TYPE_MOD_DET`
--
ALTER TABLE `USER_TYPE_MOD_DET`
 ADD PRIMARY KEY (`USER_TYPE_MOD_DET_ID`), ADD KEY `USER_TYPE_MOD_DET_FK1` (`USER_TYPE_ID`), ADD KEY `USER_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `WEEK_DAYS_MASTER`
--
ALTER TABLE `WEEK_DAYS_MASTER`
 ADD PRIMARY KEY (`WEEK_DAY_ID`), ADD UNIQUE KEY `WEEK_DAY_DESCR` (`WEEK_DAY_DESCR`), ADD KEY `WEEK_DAYS_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `WEEK_DAYS_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `WEEK_DAYS_MOD_DET`
--
ALTER TABLE `WEEK_DAYS_MOD_DET`
 ADD PRIMARY KEY (`WEEK_DAYS_MOD_DET_ID`), ADD KEY `WEEK_DAYS_MOD_DET_FK1` (`WEEK_DAY_ID`), ADD KEY `WEEK_DAYS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `YEARS_MASTER`
--
ALTER TABLE `YEARS_MASTER`
 ADD PRIMARY KEY (`YEAR_ID`), ADD UNIQUE KEY `YEAR_VALUE` (`YEAR_VALUE`), ADD KEY `YEARS_MASTER_FK1` (`LAST_EDITED_BY`), ADD KEY `YEARS_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `YEAR_MOD_DET`
--
ALTER TABLE `YEAR_MOD_DET`
 ADD PRIMARY KEY (`YEAR_MOD_DET_ID`), ADD KEY `YEAR_MOD_DET_FK1` (`YEAR_ID`), ADD KEY `YEAR_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ACTION_TOKENS_MASTER`
--
ALTER TABLE `ACTION_TOKENS_MASTER`
MODIFY `ACTION_TOKEN_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ACTION_TOKENS_MOD_DET`
--
ALTER TABLE `ACTION_TOKENS_MOD_DET`
MODIFY `ACTION_TOKENS_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ACTIVITY_SECTION_MASTER`
--
ALTER TABLE `ACTIVITY_SECTION_MASTER`
MODIFY `ACTIVITY_SECTION_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ACTIVITY_SECTION_MOD_DET`
--
ALTER TABLE `ACTIVITY_SECTION_MOD_DET`
MODIFY `ACTIVITY_SECTION_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `arrowchat`
--
ALTER TABLE `arrowchat`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `arrowchat_admin`
--
ALTER TABLE `arrowchat_admin`
MODIFY `id` int(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `arrowchat_applications`
--
ALTER TABLE `arrowchat_applications`
MODIFY `id` int(3) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `arrowchat_banlist`
--
ALTER TABLE `arrowchat_banlist`
MODIFY `ban_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `arrowchat_chatroom_messages`
--
ALTER TABLE `arrowchat_chatroom_messages`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `arrowchat_chatroom_rooms`
--
ALTER TABLE `arrowchat_chatroom_rooms`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `arrowchat_graph_log`
--
ALTER TABLE `arrowchat_graph_log`
MODIFY `id` int(6) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `arrowchat_notifications`
--
ALTER TABLE `arrowchat_notifications`
MODIFY `id` int(25) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `arrowchat_notifications_markup`
--
ALTER TABLE `arrowchat_notifications_markup`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `arrowchat_smilies`
--
ALTER TABLE `arrowchat_smilies`
MODIFY `id` int(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `arrowchat_themes`
--
ALTER TABLE `arrowchat_themes`
MODIFY `id` int(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `arrowchat_trayicons`
--
ALTER TABLE `arrowchat_trayicons`
MODIFY `id` int(3) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ATTRIBUTES_MASTER`
--
ALTER TABLE `ATTRIBUTES_MASTER`
MODIFY `ATTRIBUTE_ID` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT for table `ATTRIBUTE_MASTER_MOD_DET`
--
ALTER TABLE `ATTRIBUTE_MASTER_MOD_DET`
MODIFY `ATTRIBUTE_MASTER_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ATTRIBUTE_TYPE_MASTER`
--
ALTER TABLE `ATTRIBUTE_TYPE_MASTER`
MODIFY `ATTRIBUTE_TYPE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `ATTRIBUTE_TYPE_MOD_DET`
--
ALTER TABLE `ATTRIBUTE_TYPE_MOD_DET`
MODIFY `ATTRIBUTE_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `BLOCKED_USERS`
--
ALTER TABLE `BLOCKED_USERS`
MODIFY `BLOCKED_USER_ID` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `BLOCKED_USER_MOD_DET`
--
ALTER TABLE `BLOCKED_USER_MOD_DET`
MODIFY `BLOCKED_USER_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CAREGIVER_RELATIONSHIP_MASTER`
--
ALTER TABLE `CAREGIVER_RELATIONSHIP_MASTER`
MODIFY `RELATIONSHIP_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CAREGIVER_RELATIONSHIP_MOD_DET`
--
ALTER TABLE `CAREGIVER_RELATIONSHIP_MOD_DET`
MODIFY `CAREGIVER_RELATIONSHIP_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CARE_CALENDAR_EVENTS`
--
ALTER TABLE `CARE_CALENDAR_EVENTS`
MODIFY `CARE_EVENT_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CARE_EVENTS_MOD_DET`
--
ALTER TABLE `CARE_EVENTS_MOD_DET`
MODIFY `CARE_EVENTS_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CARE_GIVER_ATTRIBUTES`
--
ALTER TABLE `CARE_GIVER_ATTRIBUTES`
MODIFY `CARE_GIVER_ATTRIBUTE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CARE_GIVER_ATTRIBUTE_MOD_DET`
--
ALTER TABLE `CARE_GIVER_ATTRIBUTE_MOD_DET`
MODIFY `CARE_GIVER_ATTRIBUTE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CITIES_MASTER`
--
ALTER TABLE `CITIES_MASTER`
MODIFY `CITY_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=113990;
--
-- AUTO_INCREMENT for table `CITIES_MOD_DET`
--
ALTER TABLE `CITIES_MOD_DET`
MODIFY `CITIES_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `COMMUNITIES`
--
ALTER TABLE `COMMUNITIES`
MODIFY `COMMUNITY_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `COMMUNITY_ATTRIBUTES`
--
ALTER TABLE `COMMUNITY_ATTRIBUTES`
MODIFY `COMMUNITY_ATTRIBUTE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `COMMUNITY_ATTRIBUTES_MOD_DET`
--
ALTER TABLE `COMMUNITY_ATTRIBUTES_MOD_DET`
MODIFY `COMMUNITY_ATTRIBUTE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `COMMUNITY_DISEASES`
--
ALTER TABLE `COMMUNITY_DISEASES`
MODIFY `COMMUNITY_DISEASE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `COMMUNITY_DISEASES_MOD_DET`
--
ALTER TABLE `COMMUNITY_DISEASES_MOD_DET`
MODIFY `COMMUNITY_DISEASE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `COMMUNITY_MEMBERS`
--
ALTER TABLE `COMMUNITY_MEMBERS`
MODIFY `COMMUNITY_MEMBER_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `COMMUNITY_MEMBERS_MOD_DET`
--
ALTER TABLE `COMMUNITY_MEMBERS_MOD_DET`
MODIFY `COMMUNITY_MEMBER_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `COMMUNITY_MOD_DET`
--
ALTER TABLE `COMMUNITY_MOD_DET`
MODIFY `COMMUNITY_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `COMMUNITY_PHOTOS`
--
ALTER TABLE `COMMUNITY_PHOTOS`
MODIFY `COMMUNITY_PHOTO_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `COMMUNITY_PHOTOS_MOD_DET`
--
ALTER TABLE `COMMUNITY_PHOTOS_MOD_DET`
MODIFY `COMMUNITY_PHOTO_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `COMMUNITY_TYPE_MASTER`
--
ALTER TABLE `COMMUNITY_TYPE_MASTER`
MODIFY `COMMUNITY_TYPE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `COMMUNITY_TYPE_MOD_DET`
--
ALTER TABLE `COMMUNITY_TYPE_MOD_DET`
MODIFY `COMMUNITY_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CONFIGURATIONS`
--
ALTER TABLE `CONFIGURATIONS`
MODIFY `CONFIGURATION_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CONFIGURATIONS_MOD_DET`
--
ALTER TABLE `CONFIGURATIONS_MOD_DET`
MODIFY `CONFIGURATION_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `COUNTRY_MASTER`
--
ALTER TABLE `COUNTRY_MASTER`
MODIFY `COUNTRY_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=246;
--
-- AUTO_INCREMENT for table `COUNTRY_MOD_DET`
--
ALTER TABLE `COUNTRY_MOD_DET`
MODIFY `COUNTRY_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CRON_TASKS`
--
ALTER TABLE `CRON_TASKS`
MODIFY `TASK_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CRON_TASKS_MOD_DET`
--
ALTER TABLE `CRON_TASKS_MOD_DET`
MODIFY `CRON_TASK_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CRON_TASK_EXEC_LOG`
--
ALTER TABLE `CRON_TASK_EXEC_LOG`
MODIFY `CRON_TASK_EXEC_LOG_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CRON_TASK_EXEC_LOG_MOD_DET`
--
ALTER TABLE `CRON_TASK_EXEC_LOG_MOD_DET`
MODIFY `CRON_TASK_EXEC_LOG_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `DATES`
--
ALTER TABLE `DATES`
MODIFY `DATE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `DATES_MOD_DET`
--
ALTER TABLE `DATES_MOD_DET`
MODIFY `DATES_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `DISEASE_MASTER`
--
ALTER TABLE `DISEASE_MASTER`
MODIFY `DISEASE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `DISEASE_MOD_DET`
--
ALTER TABLE `DISEASE_MOD_DET`
MODIFY `DISEASE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `DISEASE_SYMPTOMS`
--
ALTER TABLE `DISEASE_SYMPTOMS`
MODIFY `DISEASE_SYMPTOM_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `DISEASE_SYMPTOMS_MOD_DET`
--
ALTER TABLE `DISEASE_SYMPTOMS_MOD_DET`
MODIFY `DISEASE_SYMPTOM_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `DISEASE_TYPE_MASTER`
--
ALTER TABLE `DISEASE_TYPE_MASTER`
MODIFY `DISEASE_TYPE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `DISEASE_TYPE_MOD_DET`
--
ALTER TABLE `DISEASE_TYPE_MOD_DET`
MODIFY `DISEASE_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EMAILS`
--
ALTER TABLE `EMAILS`
MODIFY `EMAIL_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EMAIL_ATTRIBUTES`
--
ALTER TABLE `EMAIL_ATTRIBUTES`
MODIFY `EMAIL_ATTRIBUTE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EMAIL_ATTRIBUTES_MOD_DET`
--
ALTER TABLE `EMAIL_ATTRIBUTES_MOD_DET`
MODIFY `EMAIL_ATTRIBUTES_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EMAIL_HISTORY`
--
ALTER TABLE `EMAIL_HISTORY`
MODIFY `EMAIL_HISTORY_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EMAIL_HISTORY_ATTRIBUTES`
--
ALTER TABLE `EMAIL_HISTORY_ATTRIBUTES`
MODIFY `EMAIL_HISTORY_ATTRIBUTE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EMAIL_HISTORY_ATTRIBUTES_MOD_DET`
--
ALTER TABLE `EMAIL_HISTORY_ATTRIBUTES_MOD_DET`
MODIFY `EMAIL_HISTORY_ATTRIBUTES_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EMAIL_HISTORY_MOD_DET`
--
ALTER TABLE `EMAIL_HISTORY_MOD_DET`
MODIFY `EMAIL_HISTORY_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EMAIL_MOD_DET`
--
ALTER TABLE `EMAIL_MOD_DET`
MODIFY `EMAIL_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EMAIL_PRIORITY_MASTER`
--
ALTER TABLE `EMAIL_PRIORITY_MASTER`
MODIFY `EMAIL_PRIORITY_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EMAIL_PRIORITY_MOD_DET`
--
ALTER TABLE `EMAIL_PRIORITY_MOD_DET`
MODIFY `EMAIL_PRIORITY_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EMAIL_TEMPLATES`
--
ALTER TABLE `EMAIL_TEMPLATES`
MODIFY `TEMPLATE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EMAIL_TEMPLATES_MOD_DET`
--
ALTER TABLE `EMAIL_TEMPLATES_MOD_DET`
MODIFY `EMAIL_TEMPLATE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EVENTS`
--
ALTER TABLE `EVENTS`
MODIFY `EVENT_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EVENT_ATTRIBUTES`
--
ALTER TABLE `EVENT_ATTRIBUTES`
MODIFY `EVENT_ATTRIBUTE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EVENT_ATTRIBUTES_MOD_DET`
--
ALTER TABLE `EVENT_ATTRIBUTES_MOD_DET`
MODIFY `EVENT_ATTRIBUTE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EVENT_DISEASES`
--
ALTER TABLE `EVENT_DISEASES`
MODIFY `EVENT_DISEASE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EVENT_DISEASES_MOD_DET`
--
ALTER TABLE `EVENT_DISEASES_MOD_DET`
MODIFY `EVENT_DISEASE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EVENT_MEMBERS`
--
ALTER TABLE `EVENT_MEMBERS`
MODIFY `EVENT_MEMBER_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EVENT_MEMBERS_MOD_DET`
--
ALTER TABLE `EVENT_MEMBERS_MOD_DET`
MODIFY `EVENT_MEMBER_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EVENT_MOD_DET`
--
ALTER TABLE `EVENT_MOD_DET`
MODIFY `EVENT_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EVENT_TYPE_MASTER`
--
ALTER TABLE `EVENT_TYPE_MASTER`
MODIFY `EVENT_TYPE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EVENT_TYPE_MOD_DET`
--
ALTER TABLE `EVENT_TYPE_MOD_DET`
MODIFY `EVENT_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `FOLLOWING_PAGES`
--
ALTER TABLE `FOLLOWING_PAGES`
MODIFY `FOLLOWING_PAGE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `FOLLOWING_PAGES_MOD_DET`
--
ALTER TABLE `FOLLOWING_PAGES_MOD_DET`
MODIFY `FOLLOWING_PAGE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `HEALTH_CONDITION_GROUPS`
--
ALTER TABLE `HEALTH_CONDITION_GROUPS`
MODIFY `HEALTH_CONDITION_GROUP_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `HEALTH_CONDITION_MASTER`
--
ALTER TABLE `HEALTH_CONDITION_MASTER`
MODIFY `HEALTH_CONDITION_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `HEALTH_CONDITION_MOD_DET`
--
ALTER TABLE `HEALTH_CONDITION_MOD_DET`
MODIFY `HEALTH_CONDITION_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `HEALTH_COND_GROUP_MOD_DET`
--
ALTER TABLE `HEALTH_COND_GROUP_MOD_DET`
MODIFY `HEALTH_COND_GROUP_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `INVITED_USERS`
--
ALTER TABLE `INVITED_USERS`
MODIFY `INVITED_USER_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `INVITED_USERS_MOD_DET`
--
ALTER TABLE `INVITED_USERS_MOD_DET`
MODIFY `INVITED_USERS_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `LANGUAGES`
--
ALTER TABLE `LANGUAGES`
MODIFY `LANGUAGE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `LANGUAGE_MOD_DET`
--
ALTER TABLE `LANGUAGE_MOD_DET`
MODIFY `LANGUAGE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `MEDIA_TYPE_MASTER`
--
ALTER TABLE `MEDIA_TYPE_MASTER`
MODIFY `MEDIA_TYPE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `MEDIA_TYPE_MOD_DET`
--
ALTER TABLE `MEDIA_TYPE_MOD_DET`
MODIFY `MEDIA_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `MESSAGE_RECIPIENT_ROLES`
--
ALTER TABLE `MESSAGE_RECIPIENT_ROLES`
MODIFY `MESSAGE_RECIPIENT_ROLE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `MESSAGE_ROLE_MOD_DET`
--
ALTER TABLE `MESSAGE_ROLE_MOD_DET`
MODIFY `MESSAGE_ROLE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `MODULE_MASTER`
--
ALTER TABLE `MODULE_MASTER`
MODIFY `MODULE_ID` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `MODULE_MOD_DET`
--
ALTER TABLE `MODULE_MOD_DET`
MODIFY `MODULE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `MONTHS_MASTER`
--
ALTER TABLE `MONTHS_MASTER`
MODIFY `MONTH_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `MONTH_MOD_DET`
--
ALTER TABLE `MONTH_MOD_DET`
MODIFY `MONTH_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `MOOD_MASTER`
--
ALTER TABLE `MOOD_MASTER`
MODIFY `USER_MOOD_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `MOOD_MOD_DET`
--
ALTER TABLE `MOOD_MOD_DET`
MODIFY `MOOD_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `MY_FRIENDS`
--
ALTER TABLE `MY_FRIENDS`
MODIFY `MY_FRIEND_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `MY_FRIENDS_DETAILS`
--
ALTER TABLE `MY_FRIENDS_DETAILS`
MODIFY `MY_FRIENDS_DETAIL_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `MY_FRIENDS_DETAIL_MOD_DET`
--
ALTER TABLE `MY_FRIENDS_DETAIL_MOD_DET`
MODIFY `MY_FRIENDS_DETAIL_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `MY_FRIEND_MOD_DET`
--
ALTER TABLE `MY_FRIEND_MOD_DET`
MODIFY `MY_FRIEND_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NEWSLETTERS`
--
ALTER TABLE `NEWSLETTERS`
MODIFY `NEWSLETTER_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NEWSLETTER_MOD_DET`
--
ALTER TABLE `NEWSLETTER_MOD_DET`
MODIFY `NEWSLETTER_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NEWSLETTER_QUEUE_MOD_DET`
--
ALTER TABLE `NEWSLETTER_QUEUE_MOD_DET`
MODIFY `NEWSLETTER_QUEUE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NEWSLETTER_QUEUE_STATUS`
--
ALTER TABLE `NEWSLETTER_QUEUE_STATUS`
MODIFY `NEWSLETTER_QUEUE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NEWSLETTER_TEMPLATES`
--
ALTER TABLE `NEWSLETTER_TEMPLATES`
MODIFY `NEWSLETTER_TEMPLATE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NEWSLETTER_TEMPLATE_MOD_DET`
--
ALTER TABLE `NEWSLETTER_TEMPLATE_MOD_DET`
MODIFY `NEWSLETTER_TEMPLATE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NOTIFICATIONS`
--
ALTER TABLE `NOTIFICATIONS`
MODIFY `NOTIFICATION_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NOTIFICATION_ACTIVITY_MOD_DET`
--
ALTER TABLE `NOTIFICATION_ACTIVITY_MOD_DET`
MODIFY `NOTIFICATION_ACTIVITY_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NOTIFICATION_ACTIVITY_TYPE_MASTER`
--
ALTER TABLE `NOTIFICATION_ACTIVITY_TYPE_MASTER`
MODIFY `NOTIFICATION_ACTIVITY_TYPE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NOTIFICATION_FREQUENCY_MASTER`
--
ALTER TABLE `NOTIFICATION_FREQUENCY_MASTER`
MODIFY `NOTIFICATION_FREQUENCY_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NOTIFICATION_FREQUENCY_MOD_DET`
--
ALTER TABLE `NOTIFICATION_FREQUENCY_MOD_DET`
MODIFY `NOTIFICATION_FREQUENCY_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NOTIFICATION_MOD_DET`
--
ALTER TABLE `NOTIFICATION_MOD_DET`
MODIFY `NOTIFICATION_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NOTIFICATION_OBJECT_TYPE_MASTER`
--
ALTER TABLE `NOTIFICATION_OBJECT_TYPE_MASTER`
MODIFY `NOTIFICATION_OBJECT_TYPE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NOTIFICATION_OBJECT_TYPE_MOD_DET`
--
ALTER TABLE `NOTIFICATION_OBJECT_TYPE_MOD_DET`
MODIFY `NOTIFICATION_OBJECT_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NOTIFICATION_RECIPIENTS`
--
ALTER TABLE `NOTIFICATION_RECIPIENTS`
MODIFY `NOTIFICATION_RECIPIENT_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NOTIFICATION_RECIPIENT_MOD_DET`
--
ALTER TABLE `NOTIFICATION_RECIPIENT_MOD_DET`
MODIFY `NOTIFICATION_RECIPIENT_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NOTIFICATION_SETTINGS`
--
ALTER TABLE `NOTIFICATION_SETTINGS`
MODIFY `NOTIFICATION_SETTING_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NOTIFICATION_SETTING_MOD_DET`
--
ALTER TABLE `NOTIFICATION_SETTING_MOD_DET`
MODIFY `NOTIFICATION_SETTING_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NOTIFIED_USERS`
--
ALTER TABLE `NOTIFIED_USERS`
MODIFY `NOTIFIED_USER_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `NOTIFIED_USER_MOD_DET`
--
ALTER TABLE `NOTIFIED_USER_MOD_DET`
MODIFY `NOTIFIED_USER_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `PAGE_MASTER`
--
ALTER TABLE `PAGE_MASTER`
MODIFY `PAGE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `PAGE_MOD_DET`
--
ALTER TABLE `PAGE_MOD_DET`
MODIFY `PAGE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `PAGE_TYPE_MASTER`
--
ALTER TABLE `PAGE_TYPE_MASTER`
MODIFY `PAGE_TYPE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `PAGE_TYPE_MOD_DET`
--
ALTER TABLE `PAGE_TYPE_MOD_DET`
MODIFY `PAGE_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `PAIN_LEVELS_MASTER`
--
ALTER TABLE `PAIN_LEVELS_MASTER`
MODIFY `PAIN_LEVEL_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `PAIN_LEVEL_MOD_DET`
--
ALTER TABLE `PAIN_LEVEL_MOD_DET`
MODIFY `PAIN_LEVEL_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `PAIN_MASTER`
--
ALTER TABLE `PAIN_MASTER`
MODIFY `PAIN_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `PAIN_TYPE_MOD_DET`
--
ALTER TABLE `PAIN_TYPE_MOD_DET`
MODIFY `PAIN_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `PATIENT_CARE_GIVERS`
--
ALTER TABLE `PATIENT_CARE_GIVERS`
MODIFY `PATIENT_CARE_GIVER_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `PATIENT_CARE_GIVER_MOD_DET`
--
ALTER TABLE `PATIENT_CARE_GIVER_MOD_DET`
MODIFY `PATIENT_CARE_GIVER_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `PHOTO_TYPE_MASTER`
--
ALTER TABLE `PHOTO_TYPE_MASTER`
MODIFY `PHOTO_TYPE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `PHOTO_TYPE_MOD_DET`
--
ALTER TABLE `PHOTO_TYPE_MOD_DET`
MODIFY `PHOTO_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `POLLS`
--
ALTER TABLE `POLLS`
MODIFY `POLL_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `POLL_CHOICES`
--
ALTER TABLE `POLL_CHOICES`
MODIFY `POLL_CHOICE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `POLL_CHOICE_MOD_DET`
--
ALTER TABLE `POLL_CHOICE_MOD_DET`
MODIFY `POLL_CHOICE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `POLL_MOD_DET`
--
ALTER TABLE `POLL_MOD_DET`
MODIFY `POLL_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `POST_COMMENTS`
--
ALTER TABLE `POST_COMMENTS`
MODIFY `POST_COMMENT_ID` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `POST_COMMENTS_MOD_DET`
--
ALTER TABLE `POST_COMMENTS_MOD_DET`
MODIFY `POST_COMMENT_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `POST_CONTENT_DETAILS`
--
ALTER TABLE `POST_CONTENT_DETAILS`
MODIFY `POST_CONTENT_ID` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `POST_CONTENT_MOD_DET`
--
ALTER TABLE `POST_CONTENT_MOD_DET`
MODIFY `POST_CONTENT_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `POST_LIKES`
--
ALTER TABLE `POST_LIKES`
MODIFY `POST_LIKE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `POST_LIKES_MOD_DET`
--
ALTER TABLE `POST_LIKES_MOD_DET`
MODIFY `POST_LIKE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `POST_LOCATION`
--
ALTER TABLE `POST_LOCATION`
MODIFY `POST_LOCATION_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `POST_LOCATION_MASTER`
--
ALTER TABLE `POST_LOCATION_MASTER`
MODIFY `POST_LOCATION_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `POST_LOCATION_MASTER_MOD_DET`
--
ALTER TABLE `POST_LOCATION_MASTER_MOD_DET`
MODIFY `POST_LOCATION_MASTER_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `POST_LOCATION_MOD_DET`
--
ALTER TABLE `POST_LOCATION_MOD_DET`
MODIFY `POST_LOCATION_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `POST_MOD_DET`
--
ALTER TABLE `POST_MOD_DET`
MODIFY `POST_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `POST_PRIVACY_MOD_DET`
--
ALTER TABLE `POST_PRIVACY_MOD_DET`
MODIFY `POST_PRIVACY_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `POST_PRIVACY_SETTINGS`
--
ALTER TABLE `POST_PRIVACY_SETTINGS`
MODIFY `POST_PRIVACY_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `POST_TYPE_MASTER`
--
ALTER TABLE `POST_TYPE_MASTER`
MODIFY `POST_TYPE_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `POST_TYPE_MOD_DET`
--
ALTER TABLE `POST_TYPE_MOD_DET`
MODIFY `POST_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `PRIVACY_MASTER`
--
ALTER TABLE `PRIVACY_MASTER`
MODIFY `PRIVACY_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `PRIVACY_MOD_DET`
--
ALTER TABLE `PRIVACY_MOD_DET`
MODIFY `PRIVACY_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `PUBLISH_TYPE_MASTER`
--
ALTER TABLE `PUBLISH_TYPE_MASTER`
MODIFY `PUBLISH_TYPE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `PUBLISH_TYPE_MOD_DET`
--
ALTER TABLE `PUBLISH_TYPE_MOD_DET`
MODIFY `PUBLISH_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `QUESTION_GROUP_MASTER`
--
ALTER TABLE `QUESTION_GROUP_MASTER`
MODIFY `QUESTION_GROUP_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `QUESTION_GROUP_MOD_DET`
--
ALTER TABLE `QUESTION_GROUP_MOD_DET`
MODIFY `QUESTION_GROUP_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `QUESTION_MASTER`
--
ALTER TABLE `QUESTION_MASTER`
MODIFY `QUESTION_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `QUESTION_MOD_DET`
--
ALTER TABLE `QUESTION_MOD_DET`
MODIFY `QUESTION_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `REPEAT_BY_TYPE_MASTER`
--
ALTER TABLE `REPEAT_BY_TYPE_MASTER`
MODIFY `REPEAT_BY_TYPE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `REPEAT_BY_TYPE_MOD_DET`
--
ALTER TABLE `REPEAT_BY_TYPE_MOD_DET`
MODIFY `REPEAT_BY_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `REPEAT_END_TYPE_MASTER`
--
ALTER TABLE `REPEAT_END_TYPE_MASTER`
MODIFY `REPEAT_END_TYPE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `REPEAT_END_TYPE_MOD_DET`
--
ALTER TABLE `REPEAT_END_TYPE_MOD_DET`
MODIFY `REPEAT_END_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `REPEAT_MODE_TYPE_MASTER`
--
ALTER TABLE `REPEAT_MODE_TYPE_MASTER`
MODIFY `REPEAT_MODE_TYPE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `REPEAT_MODE_TYPE_MOD_DET`
--
ALTER TABLE `REPEAT_MODE_TYPE_MOD_DET`
MODIFY `REPEAT_MODE_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `REPEAT_TYPE_MASTER`
--
ALTER TABLE `REPEAT_TYPE_MASTER`
MODIFY `REPEAT_TYPE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `REPEAT_TYPE_MOD_DET`
--
ALTER TABLE `REPEAT_TYPE_MOD_DET`
MODIFY `REPEAT_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `SECTION_TYPE_MASTER`
--
ALTER TABLE `SECTION_TYPE_MASTER`
MODIFY `SECTION_TYPE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `SECTION_TYPE_MOD_DET`
--
ALTER TABLE `SECTION_TYPE_MOD_DET`
MODIFY `SECTION_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `STATES_MASTER`
--
ALTER TABLE `STATES_MASTER`
MODIFY `STATE_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3721;
--
-- AUTO_INCREMENT for table `STATES_MOD_DET`
--
ALTER TABLE `STATES_MOD_DET`
MODIFY `STATES_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `STATUS`
--
ALTER TABLE `STATUS`
MODIFY `STATUS_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `STATUS_MOD_DET`
--
ALTER TABLE `STATUS_MOD_DET`
MODIFY `STATUS_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `STATUS_TYPE`
--
ALTER TABLE `STATUS_TYPE`
MODIFY `STATUS_TYPE_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `STATUS_TYPE_MOD_DET`
--
ALTER TABLE `STATUS_TYPE_MOD_DET`
MODIFY `STATUS_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `SURVEY_MASTER`
--
ALTER TABLE `SURVEY_MASTER`
MODIFY `SURVEY_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `SURVEY_MOD_DET`
--
ALTER TABLE `SURVEY_MOD_DET`
MODIFY `SURVEY_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `SURVEY_QUESTIONS`
--
ALTER TABLE `SURVEY_QUESTIONS`
MODIFY `SURVEY_QUESTION_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `SURVEY_QUESTIONS_ANSWER_CHOICES`
--
ALTER TABLE `SURVEY_QUESTIONS_ANSWER_CHOICES`
MODIFY `SURVEY_QUESTIONS_ANSWER_CHOICE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET`
--
ALTER TABLE `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET`
MODIFY `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `SURVEY_QUESTIONS_MOD_DET`
--
ALTER TABLE `SURVEY_QUESTIONS_MOD_DET`
MODIFY `SURVEY_QUESTIONS_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `SURVEY_RESULTS_ANSWER_CHOICES`
--
ALTER TABLE `SURVEY_RESULTS_ANSWER_CHOICES`
MODIFY `SURVEY_RESULTS_ANSWER_CHOICE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET`
--
ALTER TABLE `SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET`
MODIFY `SURVEY_RESULTS_ANSWER_CHOICE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `SURVEY_RESULTS_DETAILED_ANSWERS`
--
ALTER TABLE `SURVEY_RESULTS_DETAILED_ANSWERS`
MODIFY `SURVEY_RESULTS_DETAILED_ANSWER_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET`
--
ALTER TABLE `SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET`
MODIFY `SURVEY_RESULTS_DETAILED_ANSWER_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `SURVEY_TYPE_MASTER`
--
ALTER TABLE `SURVEY_TYPE_MASTER`
MODIFY `SURVEY_TYPE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `SURVEY_TYPE_MOD_DET`
--
ALTER TABLE `SURVEY_TYPE_MOD_DET`
MODIFY `SURVEY_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `SYMPTOMS_MASTER`
--
ALTER TABLE `SYMPTOMS_MASTER`
MODIFY `SYMPTOM_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `SYMPTOMS_MOD_DET`
--
ALTER TABLE `SYMPTOMS_MOD_DET`
MODIFY `SYMPTOM_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `TEAMS`
--
ALTER TABLE `TEAMS`
MODIFY `TEAM_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `TEAM_MEMBERS`
--
ALTER TABLE `TEAM_MEMBERS`
MODIFY `TEAM_MEMBER_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `TEAM_MEMBERS_MOD_DET`
--
ALTER TABLE `TEAM_MEMBERS_MOD_DET`
MODIFY `TEAM_MEMBER_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `TEAM_MOD_DET`
--
ALTER TABLE `TEAM_MOD_DET`
MODIFY `TEAM_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `TEAM_PRIVACY_SETTINGS`
--
ALTER TABLE `TEAM_PRIVACY_SETTINGS`
MODIFY `TEAM_PRIVACY_SETTING_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `TEAM_PRIVACY_SETTING_MOD_DET`
--
ALTER TABLE `TEAM_PRIVACY_SETTING_MOD_DET`
MODIFY `TEAM_PRIVACY_SETTING_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `TIMEZONE_MASTER`
--
ALTER TABLE `TIMEZONE_MASTER`
MODIFY `TIMEZONE_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `TIMEZONE_MOD_DET`
--
ALTER TABLE `TIMEZONE_MOD_DET`
MODIFY `TIMEZONE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `TREATMENT_MASTER`
--
ALTER TABLE `TREATMENT_MASTER`
MODIFY `TREATMENT_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `TREATMENT_MASTER_MOD_DET`
--
ALTER TABLE `TREATMENT_MASTER_MOD_DET`
MODIFY `TREATMENT_MASTER_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `UNIT_OF_MEASUREMENT_MASTER`
--
ALTER TABLE `UNIT_OF_MEASUREMENT_MASTER`
MODIFY `UNIT_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `UNIT_OF_MEASUREMENT_MOD_DET`
--
ALTER TABLE `UNIT_OF_MEASUREMENT_MOD_DET`
MODIFY `UNIT_OF_MEASUREMENT_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USERS`
--
ALTER TABLE `USERS`
MODIFY `USER_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_ACTIVITY_LOGS`
--
ALTER TABLE `USER_ACTIVITY_LOGS`
MODIFY `USER_ACTIVITY_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_ACTIVITY_MOD_DET`
--
ALTER TABLE `USER_ACTIVITY_MOD_DET`
MODIFY `USER_ACTIVITY_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_ATTRIBUTES`
--
ALTER TABLE `USER_ATTRIBUTES`
MODIFY `USER_ATTRIBUTE_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_ATTRIBUTE_MOD_HISTORY`
--
ALTER TABLE `USER_ATTRIBUTE_MOD_HISTORY`
MODIFY `USER_ATTRIBUTE_MOD_HISTORY_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_DISEASES`
--
ALTER TABLE `USER_DISEASES`
MODIFY `USER_DISEASE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_DISEASES_MOD_DET`
--
ALTER TABLE `USER_DISEASES_MOD_DET`
MODIFY `USER_DISEASES_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_FAVORITE_POSTS`
--
ALTER TABLE `USER_FAVORITE_POSTS`
MODIFY `USER_FAVORITE_POST_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_FAV_POSTS_MOD_DET`
--
ALTER TABLE `USER_FAV_POSTS_MOD_DET`
MODIFY `USER_FAV_POST_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_HEALTH_HISTORY_DET`
--
ALTER TABLE `USER_HEALTH_HISTORY_DET`
MODIFY `USER_HEALTH_HISTORY_DET_ID` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_HEALTH_HISTORY_MOD_DET`
--
ALTER TABLE `USER_HEALTH_HISTORY_MOD_DET`
MODIFY `USER_HEALTH_HISTORY_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_HEALTH_READING`
--
ALTER TABLE `USER_HEALTH_READING`
MODIFY `USER_HEALTH_READING_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_HEALTH_READING_MOD_DET`
--
ALTER TABLE `USER_HEALTH_READING_MOD_DET`
MODIFY `USER_HEALTH_READING_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_MEDIA`
--
ALTER TABLE `USER_MEDIA`
MODIFY `USER_MEDIA_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_MEDIA_MOD_DET`
--
ALTER TABLE `USER_MEDIA_MOD_DET`
MODIFY `USER_MEDIA_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_MESSAGES`
--
ALTER TABLE `USER_MESSAGES`
MODIFY `MESSAGE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_MESSAGE_RECIPIENTS`
--
ALTER TABLE `USER_MESSAGE_RECIPIENTS`
MODIFY `USER_MESSAGE_RECIPIENT_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_MOOD_HISTORY`
--
ALTER TABLE `USER_MOOD_HISTORY`
MODIFY `USER_MOOD_HISTORY_ID` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_MOOD_HISTORY_MOD_DET`
--
ALTER TABLE `USER_MOOD_HISTORY_MOD_DET`
MODIFY `USER_MOOD_HISTORY_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_PAIN_TRACKER`
--
ALTER TABLE `USER_PAIN_TRACKER`
MODIFY `USER_PAIN_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_PAIN_TRACKER_MOD_DET`
--
ALTER TABLE `USER_PAIN_TRACKER_MOD_DET`
MODIFY `USER_PAIN_TRACKER_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_PHOTOS`
--
ALTER TABLE `USER_PHOTOS`
MODIFY `USER_PHOTO_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_PHOTOS_MOD_DET`
--
ALTER TABLE `USER_PHOTOS_MOD_DET`
MODIFY `USER_PHOTOS_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_PRIVACY_MOD_DET`
--
ALTER TABLE `USER_PRIVACY_MOD_DET`
MODIFY `USER_PRIVACY_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_PRIVACY_SETTINGS`
--
ALTER TABLE `USER_PRIVACY_SETTINGS`
MODIFY `USER_PRIVACY_ID` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_PSSWRD_CHALLENGE_QUES`
--
ALTER TABLE `USER_PSSWRD_CHALLENGE_QUES`
MODIFY `USER_PSSWRD_QUES_ID` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_PSSWRD_CHALLENGE_QUES_MOD_DET`
--
ALTER TABLE `USER_PSSWRD_CHALLENGE_QUES_MOD_DET`
MODIFY `USER_PSSWRD_CHALLENGE_QUES_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_SYMPTOMS`
--
ALTER TABLE `USER_SYMPTOMS`
MODIFY `USER_SYMPTOM_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_SYMPTOMS_MOD_DET`
--
ALTER TABLE `USER_SYMPTOMS_MOD_DET`
MODIFY `USER_SYMPTOMS_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_SYMPTOM_RECORDS`
--
ALTER TABLE `USER_SYMPTOM_RECORDS`
MODIFY `USER_SYMPTOM_RECORD_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_SYMPTOM_RECORDS_MOD_DET`
--
ALTER TABLE `USER_SYMPTOM_RECORDS_MOD_DET`
MODIFY `USER_SYMPTOM_RECORDS_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `USER_TYPE`
--
ALTER TABLE `USER_TYPE`
MODIFY `USER_TYPE_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `USER_TYPE_MOD_DET`
--
ALTER TABLE `USER_TYPE_MOD_DET`
MODIFY `USER_TYPE_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `WEEK_DAYS_MASTER`
--
ALTER TABLE `WEEK_DAYS_MASTER`
MODIFY `WEEK_DAY_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `WEEK_DAYS_MOD_DET`
--
ALTER TABLE `WEEK_DAYS_MOD_DET`
MODIFY `WEEK_DAYS_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `YEARS_MASTER`
--
ALTER TABLE `YEARS_MASTER`
MODIFY `YEAR_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=201;
--
-- AUTO_INCREMENT for table `YEAR_MOD_DET`
--
ALTER TABLE `YEAR_MOD_DET`
MODIFY `YEAR_MOD_DET_ID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `ACTION_TOKENS_MASTER`
--
ALTER TABLE `ACTION_TOKENS_MASTER`
ADD CONSTRAINT `ACTION_TOKENS_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `ACTION_TOKENS_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `ACTION_TOKENS_MOD_DET`
--
ALTER TABLE `ACTION_TOKENS_MOD_DET`
ADD CONSTRAINT `ACTION_TOKENS_MOD_DET_FK1` FOREIGN KEY (`ACTION_TOKEN_ID`) REFERENCES `ACTION_TOKENS_MASTER` (`ACTION_TOKEN_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `ACTION_TOKENS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `ACTIVITY_SECTION_MOD_DET`
--
ALTER TABLE `ACTIVITY_SECTION_MOD_DET`
ADD CONSTRAINT `ACTIVITY_SECTION_MOD_DET_FK1` FOREIGN KEY (`ACTIVITY_SECTION_ID`) REFERENCES `ACTIVITY_SECTION_MASTER` (`ACTIVITY_SECTION_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NOTIFICATION_ACTIVITY_SECTION_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `ATTRIBUTES_MASTER`
--
ALTER TABLE `ATTRIBUTES_MASTER`
ADD CONSTRAINT `ATTRIBUTES_MASTER_FK1` FOREIGN KEY (`ATTRIBUTE_TYPE_ID`) REFERENCES `ATTRIBUTE_TYPE_MASTER` (`ATTRIBUTE_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `ATTRIBUTES_MASTER_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `ATTRIBUTES_MASTER_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `ATTRIBUTE_MASTER_MOD_DET`
--
ALTER TABLE `ATTRIBUTE_MASTER_MOD_DET`
ADD CONSTRAINT `ATTRIBUTE_MASTER_MOD_DET_FK1` FOREIGN KEY (`ATTRIBUTE_ID`) REFERENCES `ATTRIBUTES_MASTER` (`ATTRIBUTE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `ATTRIBUTE_MASTER_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `ATTRIBUTE_TYPE_MASTER`
--
ALTER TABLE `ATTRIBUTE_TYPE_MASTER`
ADD CONSTRAINT `ATTRIBUTE_TYPE_MASTER_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `ATTRIBUTE_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `ATTRIBUTE_TYPE_MOD_DET`
--
ALTER TABLE `ATTRIBUTE_TYPE_MOD_DET`
ADD CONSTRAINT `ATTRIBUTE_TYPE_MOD_DET_FK1` FOREIGN KEY (`ATTRIBUTE_TYPE_ID`) REFERENCES `ATTRIBUTE_TYPE_MASTER` (`ATTRIBUTE_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `ATTRIBUTE_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `BLOCKED_USERS`
--
ALTER TABLE `BLOCKED_USERS`
ADD CONSTRAINT `BLOCKED_USERS_FK1` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `BLOCKED_USERS_FK2` FOREIGN KEY (`BLOCKED_USER`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `BLOCKED_USERS_FK3` FOREIGN KEY (`BLOCKED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `BLOCKED_USERS_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `BLOCKED_USER_MOD_DET`
--
ALTER TABLE `BLOCKED_USER_MOD_DET`
ADD CONSTRAINT `BLOCKED_USER_MOD_DET_FK1` FOREIGN KEY (`BLOCKED_USER_ID`) REFERENCES `BLOCKED_USERS` (`BLOCKED_USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `BLOCKED_USER_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `CAREGIVER_RELATIONSHIP_MASTER`
--
ALTER TABLE `CAREGIVER_RELATIONSHIP_MASTER`
ADD CONSTRAINT `CAREGIVER_RELATIONSHIP_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `CAREGIVER_RELATIONSHIP_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `CAREGIVER_RELATIONSHIP_MOD_DET`
--
ALTER TABLE `CAREGIVER_RELATIONSHIP_MOD_DET`
ADD CONSTRAINT `CAREGIVER_RELATIONSHIP_MOD_DET_FK1` FOREIGN KEY (`RELATIONSHIP_ID`) REFERENCES `CAREGIVER_RELATIONSHIP_MASTER` (`RELATIONSHIP_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `CAREGIVER_RELATIONSHIP_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `CARE_CALENDAR_EVENTS`
--
ALTER TABLE `CARE_CALENDAR_EVENTS`
ADD CONSTRAINT `CARE_CALENDAR_EVENTS_FK1` FOREIGN KEY (`ASSIGNED_TO`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `CARE_CALENDAR_EVENTS_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `CARE_CALENDAR_EVENTS_FK3` FOREIGN KEY (`CARE_EVENT_TYPE_ID`) REFERENCES `EVENT_TYPE_MASTER` (`EVENT_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `CARE_CALENDAR_EVENTS_FK4` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `CARE_EVENTS_MOD_DET`
--
ALTER TABLE `CARE_EVENTS_MOD_DET`
ADD CONSTRAINT `CARE_EVENTS_MOD_DET_FK1` FOREIGN KEY (`CARE_EVENT_ID`) REFERENCES `CARE_CALENDAR_EVENTS` (`CARE_EVENT_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `CARE_EVENTS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `CARE_GIVER_ATTRIBUTES`
--
ALTER TABLE `CARE_GIVER_ATTRIBUTES`
ADD CONSTRAINT `CARE_GIVER_ATTRIBUTES_FK1` FOREIGN KEY (`PATIENT_CARE_GIVER_ID`) REFERENCES `PATIENT_CARE_GIVERS` (`PATIENT_CARE_GIVER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `CARE_GIVER_ATTRIBUTES_FK2` FOREIGN KEY (`ATTRIBUTE_ID`) REFERENCES `ATTRIBUTES_MASTER` (`ATTRIBUTE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `CARE_GIVER_ATTRIBUTES_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `CARE_GIVER_ATTRIBUTES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `CARE_GIVER_ATTRIBUTE_MOD_DET`
--
ALTER TABLE `CARE_GIVER_ATTRIBUTE_MOD_DET`
ADD CONSTRAINT `CARE_GIVER_ATTRIBUTE_MOD_DET_FK1` FOREIGN KEY (`CARE_GIVER_ATTRIBUTE_ID`) REFERENCES `CARE_GIVER_ATTRIBUTES` (`CARE_GIVER_ATTRIBUTE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `CARE_GIVER_ATTRIBUTE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `CITIES_MASTER`
--
ALTER TABLE `CITIES_MASTER`
ADD CONSTRAINT `CITIES_FK1` FOREIGN KEY (`STATE_ID`) REFERENCES `STATES_MASTER` (`STATE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `CITIES_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `CITIES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `CITIES_MOD_DET`
--
ALTER TABLE `CITIES_MOD_DET`
ADD CONSTRAINT `CITIES_MOD_DET_FK1` FOREIGN KEY (`CITY_ID`) REFERENCES `CITIES_MASTER` (`CITY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `CITIES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `COMMUNITIES`
--
ALTER TABLE `COMMUNITIES`
ADD CONSTRAINT `COMMUNITY_FK1` FOREIGN KEY (`COMMUNITY_TYPE_ID`) REFERENCES `COMMUNITY_TYPE_MASTER` (`COMMUNITY_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `COMMUNITY_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `COMMUNITY_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `COMMUNITY_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `COMMUNITY_ATTRIBUTES`
--
ALTER TABLE `COMMUNITY_ATTRIBUTES`
ADD CONSTRAINT `COMMUNITY_ATTRIBUTES_FK1` FOREIGN KEY (`COMMUNITY_ID`) REFERENCES `COMMUNITIES` (`COMMUNITY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `COMMUNITY_ATTRIBUTES_FK2` FOREIGN KEY (`ATTRIBUTE_ID`) REFERENCES `ATTRIBUTES_MASTER` (`ATTRIBUTE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `COMMUNITY_ATTRIBUTES_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `COMMUNITY_ATTRIBUTES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `COMMUNITY_ATTRIBUTES_MOD_DET`
--
ALTER TABLE `COMMUNITY_ATTRIBUTES_MOD_DET`
ADD CONSTRAINT `COMMUNITY_ATTRIBUTES_MOD_DET_FK1` FOREIGN KEY (`COMMUNITY_ATTRIBUTE_ID`) REFERENCES `COMMUNITY_ATTRIBUTES` (`COMMUNITY_ATTRIBUTE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `COMMUNITY_ATTRIBUTES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `COMMUNITY_DISEASES`
--
ALTER TABLE `COMMUNITY_DISEASES`
ADD CONSTRAINT `COMMUNITY_DISEASES_FK1` FOREIGN KEY (`COMMUNITY_ID`) REFERENCES `COMMUNITIES` (`COMMUNITY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `COMMUNITY_DISEASES_FK2` FOREIGN KEY (`DISEASE_ID`) REFERENCES `DISEASE_MASTER` (`DISEASE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `COMMUNITY_DISEASES_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `COMMUNITY_DISEASES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `COMMUNITY_DISEASES_MOD_DET`
--
ALTER TABLE `COMMUNITY_DISEASES_MOD_DET`
ADD CONSTRAINT `COMMUNITY_DISEASES_MOD_DET_FK1` FOREIGN KEY (`COMMUNITY_DISEASE_ID`) REFERENCES `COMMUNITY_DISEASES` (`COMMUNITY_DISEASE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `COMMUNITY_DISEASES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `COMMUNITY_MEMBERS`
--
ALTER TABLE `COMMUNITY_MEMBERS`
ADD CONSTRAINT `COMMUNITY_MEMBERS_FK1` FOREIGN KEY (`COMMUNITY_ID`) REFERENCES `COMMUNITIES` (`COMMUNITY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `COMMUNITY_MEMBERS_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `COMMUNITY_MEMBERS_FK3` FOREIGN KEY (`USER_TYPE_ID`) REFERENCES `USER_TYPE` (`USER_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `COMMUNITY_MEMBERS_FK4` FOREIGN KEY (`INVITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `COMMUNITY_MEMBERS_FK5` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `COMMUNITY_MEMBERS_FK6` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `COMMUNITY_MEMBERS_MOD_DET`
--
ALTER TABLE `COMMUNITY_MEMBERS_MOD_DET`
ADD CONSTRAINT `COMMUNITY_MEMBERS_MOD_DET_FK1` FOREIGN KEY (`COMMUNITY_MEMBER_ID`) REFERENCES `COMMUNITY_MEMBERS` (`COMMUNITY_MEMBER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `COMMUNITY_MEMBERS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `COMMUNITY_MOD_DET`
--
ALTER TABLE `COMMUNITY_MOD_DET`
ADD CONSTRAINT `COMMUNITY_MOD_DET_FK1` FOREIGN KEY (`COMMUNITY_ID`) REFERENCES `COMMUNITIES` (`COMMUNITY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `COMMUNITY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `COMMUNITY_PHOTOS`
--
ALTER TABLE `COMMUNITY_PHOTOS`
ADD CONSTRAINT `COMMUNITY_PHOTOS_FK1` FOREIGN KEY (`COMMUNITY_ID`) REFERENCES `COMMUNITIES` (`COMMUNITY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `COMMUNITY_PHOTOS_FK2` FOREIGN KEY (`PHOTO_TYPE_ID`) REFERENCES `PHOTO_TYPE_MASTER` (`PHOTO_TYPE_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `COMMUNITY_PHOTOS_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `COMMUNITY_PHOTOS_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `COMMUNITY_PHOTOS_MOD_DET`
--
ALTER TABLE `COMMUNITY_PHOTOS_MOD_DET`
ADD CONSTRAINT `COMMUNITY_PHOTOS_MOD_DET_FK1` FOREIGN KEY (`COMMUNITY_PHOTO_ID`) REFERENCES `COMMUNITY_PHOTOS` (`COMMUNITY_PHOTO_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `COMMUNITY_PHOTOS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `COMMUNITY_TYPE_MASTER`
--
ALTER TABLE `COMMUNITY_TYPE_MASTER`
ADD CONSTRAINT `COMMUNITY_TYPE_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `COMMUNITY_TYPE_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `COMMUNITY_TYPE_MOD_DET`
--
ALTER TABLE `COMMUNITY_TYPE_MOD_DET`
ADD CONSTRAINT `COMMUNITY_TYPE_MOD_DET_FK1` FOREIGN KEY (`COMMUNITY_TYPE_ID`) REFERENCES `COMMUNITY_TYPE_MASTER` (`COMMUNITY_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `COMMUNITY_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `CONFIGURATIONS`
--
ALTER TABLE `CONFIGURATIONS`
ADD CONSTRAINT `CONFIGURATIONS_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `CONFIGURATIONS_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `CONFIGURATIONS_MOD_DET`
--
ALTER TABLE `CONFIGURATIONS_MOD_DET`
ADD CONSTRAINT `CONFIGURATIONS_MOD_DET_FK1` FOREIGN KEY (`CONFIGURATION_ID`) REFERENCES `CONFIGURATIONS` (`CONFIGURATION_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `CONFIGURATIONS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `COUNTRY_MASTER`
--
ALTER TABLE `COUNTRY_MASTER`
ADD CONSTRAINT `COUNTRY_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `COUNTRY_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `COUNTRY_MOD_DET`
--
ALTER TABLE `COUNTRY_MOD_DET`
ADD CONSTRAINT `COUNTRY_MOD_DET_FK1` FOREIGN KEY (`COUNTRY_ID`) REFERENCES `COUNTRY_MASTER` (`COUNTRY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `COUNTRY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `CRON_TASKS`
--
ALTER TABLE `CRON_TASKS`
ADD CONSTRAINT `CRON_TASKS_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `CRON_TASKS_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `CRON_TASKS_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `CRON_TASKS_MOD_DET`
--
ALTER TABLE `CRON_TASKS_MOD_DET`
ADD CONSTRAINT `CRON_TASKS_MOD_DET_FK1` FOREIGN KEY (`TASK_ID`) REFERENCES `CRON_TASKS` (`TASK_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `CRON_TASKS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `CRON_TASK_EXEC_LOG`
--
ALTER TABLE `CRON_TASK_EXEC_LOG`
ADD CONSTRAINT `CRON_TASK_EXEC_LOG_FK1` FOREIGN KEY (`TASK_ID`) REFERENCES `CRON_TASKS` (`TASK_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `CRON_TASK_EXEC_LOG_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `CRON_TASK_EXEC_LOG_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `CRON_TASK_EXEC_LOG_MOD_DET`
--
ALTER TABLE `CRON_TASK_EXEC_LOG_MOD_DET`
ADD CONSTRAINT `CRON_TASK_EXEC_LOG_MOD_DET_FK1` FOREIGN KEY (`CRON_TASK_EXEC_LOG_ID`) REFERENCES `CRON_TASK_EXEC_LOG` (`CRON_TASK_EXEC_LOG_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `CRON_TASK_EXEC_LOG_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `DATES`
--
ALTER TABLE `DATES`
ADD CONSTRAINT `DATE_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `DATE_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `DATES_MOD_DET`
--
ALTER TABLE `DATES_MOD_DET`
ADD CONSTRAINT `DATES_MOD_DET_FK1` FOREIGN KEY (`DATE_ID`) REFERENCES `DATES` (`DATE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `DATES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `DISEASE_MASTER`
--
ALTER TABLE `DISEASE_MASTER`
ADD CONSTRAINT `DISEASE_MASTER_FK1` FOREIGN KEY (`PARENT_DISEASE_ID`) REFERENCES `DISEASE_MASTER` (`DISEASE_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `DISEASE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `DISEASE_MASTER_FK3` FOREIGN KEY (`DISEASE_SURVEY_ID`) REFERENCES `SURVEY_MASTER` (`SURVEY_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `DISEASE_MASTER_FK4` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `DISEASE_MOD_DET`
--
ALTER TABLE `DISEASE_MOD_DET`
ADD CONSTRAINT `DISEASE_MOD_DET_FK1` FOREIGN KEY (`DISEASE_ID`) REFERENCES `DISEASE_MASTER` (`DISEASE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `DISEASE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `DISEASE_SYMPTOMS`
--
ALTER TABLE `DISEASE_SYMPTOMS`
ADD CONSTRAINT `DISEASE_SYMPTOMS_FK1` FOREIGN KEY (`DISEASE_ID`) REFERENCES `DISEASE_MASTER` (`DISEASE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `DISEASE_SYMPTOMS_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `DISEASE_SYMPTOMS_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `DISEASE_SYMPTOMS_FK4` FOREIGN KEY (`SYMPTOM_ID`) REFERENCES `SYMPTOMS_MASTER` (`SYMPTOM_ID`) ON DELETE CASCADE;

--
-- Constraints for table `DISEASE_SYMPTOMS_MOD_DET`
--
ALTER TABLE `DISEASE_SYMPTOMS_MOD_DET`
ADD CONSTRAINT `DISEASE_SYMPTOMS_MOD_DET_FK1` FOREIGN KEY (`DISEASE_SYMPTOM_ID`) REFERENCES `DISEASE_SYMPTOMS` (`DISEASE_SYMPTOM_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `DISEASE_SYMPTOMS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `DISEASE_TYPE_MASTER`
--
ALTER TABLE `DISEASE_TYPE_MASTER`
ADD CONSTRAINT `DISEASE_TYPE_MASTER_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `DISEASE_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `DISEASE_TYPE_MOD_DET`
--
ALTER TABLE `DISEASE_TYPE_MOD_DET`
ADD CONSTRAINT `DISEASE_TYPE_MOD_DET_FK1` FOREIGN KEY (`DISEASE_TYPE_ID`) REFERENCES `DISEASE_TYPE_MASTER` (`DISEASE_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `DISEASE_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EMAILS`
--
ALTER TABLE `EMAILS`
ADD CONSTRAINT `EMAIL_FK1` FOREIGN KEY (`EMAIL_TEMPLATE_ID`) REFERENCES `EMAIL_TEMPLATES` (`TEMPLATE_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EMAIL_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EMAIL_FK3` FOREIGN KEY (`PRIORITY_ID`) REFERENCES `EMAIL_PRIORITY_MASTER` (`EMAIL_PRIORITY_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EMAIL_FK4` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EMAIL_FK5` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EMAIL_ATTRIBUTES`
--
ALTER TABLE `EMAIL_ATTRIBUTES`
ADD CONSTRAINT `EMAIL_ATTRIBUTES_FK1` FOREIGN KEY (`EMAIL_ID`) REFERENCES `EMAILS` (`EMAIL_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EMAIL_ATTRIBUTES_FK2` FOREIGN KEY (`ATTRIBUTE_ID`) REFERENCES `ATTRIBUTES_MASTER` (`ATTRIBUTE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EMAIL_ATTRIBUTES_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EMAIL_ATTRIBUTES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EMAIL_ATTRIBUTES_MOD_DET`
--
ALTER TABLE `EMAIL_ATTRIBUTES_MOD_DET`
ADD CONSTRAINT `EMAIL_ATTRIBUTES_MOD_DET_FK1` FOREIGN KEY (`EMAIL_ATTRIBUTE_ID`) REFERENCES `EMAIL_ATTRIBUTES` (`EMAIL_ATTRIBUTE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EMAIL_ATTRIBUTES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EMAIL_HISTORY`
--
ALTER TABLE `EMAIL_HISTORY`
ADD CONSTRAINT `EMAIL_HISTORY_FK1` FOREIGN KEY (`EMAIL_TEMPLATE_ID`) REFERENCES `EMAIL_TEMPLATES` (`TEMPLATE_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EMAIL_HISTORY_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EMAIL_HISTORY_FK3` FOREIGN KEY (`PRIORITY_ID`) REFERENCES `EMAIL_PRIORITY_MASTER` (`EMAIL_PRIORITY_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EMAIL_HISTORY_FK4` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EMAIL_HISTORY_FK5` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EMAIL_HISTORY_ATTRIBUTES`
--
ALTER TABLE `EMAIL_HISTORY_ATTRIBUTES`
ADD CONSTRAINT `EMAIL_HISTORY_ATTRIBUTES_FK1` FOREIGN KEY (`EMAIL_HISTORY_ID`) REFERENCES `EMAIL_HISTORY` (`EMAIL_HISTORY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EMAIL_HISTORY_ATTRIBUTES_FK2` FOREIGN KEY (`ATTRIBUTE_ID`) REFERENCES `ATTRIBUTES_MASTER` (`ATTRIBUTE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EMAIL_HISTORY_ATTRIBUTES_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EMAIL_HISTORY_ATTRIBUTES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EMAIL_HISTORY_ATTRIBUTES_MOD_DET`
--
ALTER TABLE `EMAIL_HISTORY_ATTRIBUTES_MOD_DET`
ADD CONSTRAINT `EMAIL_HISTORY_ATTRIBUTES_MOD_DET_FK1` FOREIGN KEY (`EMAIL_HISTORY_ATTRIBUTE_ID`) REFERENCES `EMAIL_HISTORY_ATTRIBUTES` (`EMAIL_HISTORY_ATTRIBUTE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EMAIL_HISTORY_ATTRIBUTES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EMAIL_HISTORY_MOD_DET`
--
ALTER TABLE `EMAIL_HISTORY_MOD_DET`
ADD CONSTRAINT `EMAIL_HISTORY_MOD_DET_FK1` FOREIGN KEY (`EMAIL_HISTORY_ID`) REFERENCES `EMAIL_HISTORY` (`EMAIL_HISTORY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EMAIL_HISTORY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EMAIL_MOD_DET`
--
ALTER TABLE `EMAIL_MOD_DET`
ADD CONSTRAINT `EMAIL_MOD_DET_FK1` FOREIGN KEY (`EMAIL_ID`) REFERENCES `EMAILS` (`EMAIL_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EMAIL_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EMAIL_PRIORITY_MASTER`
--
ALTER TABLE `EMAIL_PRIORITY_MASTER`
ADD CONSTRAINT `EMAIL_PRIORITY_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EMAIL_PRIORITY_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EMAIL_PRIORITY_MOD_DET`
--
ALTER TABLE `EMAIL_PRIORITY_MOD_DET`
ADD CONSTRAINT `EMAIL_PRIORITY_MOD_DET_FK1` FOREIGN KEY (`EMAIL_PRIORITY_ID`) REFERENCES `EMAIL_PRIORITY_MASTER` (`EMAIL_PRIORITY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EMAIL_PRIORITY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EMAIL_TEMPLATES`
--
ALTER TABLE `EMAIL_TEMPLATES`
ADD CONSTRAINT `EMAIL_TEMPLATES_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EMAIL_TEMPLATES_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EMAIL_TEMPLATES_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EMAIL_TEMPLATES_MOD_DET`
--
ALTER TABLE `EMAIL_TEMPLATES_MOD_DET`
ADD CONSTRAINT `EMAIL_TEMPLATE_MOD_DET_FK1` FOREIGN KEY (`TEMPLATE_ID`) REFERENCES `EMAIL_TEMPLATES` (`TEMPLATE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EMAIL_TEMPLATE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EVENTS`
--
ALTER TABLE `EVENTS`
ADD CONSTRAINT `EVENT_FK1` FOREIGN KEY (`EVENT_TYPE_ID`) REFERENCES `EVENT_TYPE_MASTER` (`EVENT_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EVENT_FK10` FOREIGN KEY (`REPEAT_BY_TYPE_ID`) REFERENCES `REPEAT_BY_TYPE_MASTER` (`REPEAT_BY_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EVENT_FK11` FOREIGN KEY (`REPEAT_END_TYPE_ID`) REFERENCES `REPEAT_END_TYPE_MASTER` (`REPEAT_END_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EVENT_FK12` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EVENT_FK13` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EVENT_FK2` FOREIGN KEY (`COMMUNITY_ID`) REFERENCES `COMMUNITIES` (`COMMUNITY_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EVENT_FK3` FOREIGN KEY (`REPEAT_TYPE_ID`) REFERENCES `REPEAT_TYPE_MASTER` (`REPEAT_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EVENT_FK4` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EVENT_FK5` FOREIGN KEY (`PUBLISH_TYPE_ID`) REFERENCES `PUBLISH_TYPE_MASTER` (`PUBLISH_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EVENT_FK6` FOREIGN KEY (`SECTION_TYPE_ID`) REFERENCES `SECTION_TYPE_MASTER` (`SECTION_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EVENT_FK7` FOREIGN KEY (`SECTION_TEAM_ID`) REFERENCES `TEAMS` (`TEAM_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EVENT_FK8` FOREIGN KEY (`SECTION_COMMUNITY_ID`) REFERENCES `COMMUNITIES` (`COMMUNITY_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EVENT_FK9` FOREIGN KEY (`REPEAT_MODE_TYPE_ID`) REFERENCES `REPEAT_MODE_TYPE_MASTER` (`REPEAT_MODE_TYPE_ID`) ON DELETE CASCADE;

--
-- Constraints for table `EVENT_ATTRIBUTES`
--
ALTER TABLE `EVENT_ATTRIBUTES`
ADD CONSTRAINT `EVENT_ATTRIBUTES_FK1` FOREIGN KEY (`EVENT_ID`) REFERENCES `EVENTS` (`EVENT_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EVENT_ATTRIBUTES_FK2` FOREIGN KEY (`ATTRIBUTE_ID`) REFERENCES `ATTRIBUTES_MASTER` (`ATTRIBUTE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EVENT_ATTRIBUTES_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EVENT_ATTRIBUTES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EVENT_ATTRIBUTES_MOD_DET`
--
ALTER TABLE `EVENT_ATTRIBUTES_MOD_DET`
ADD CONSTRAINT `EVENT_ATTRIBUTES_MOD_DET_FK1` FOREIGN KEY (`EVENT_ATTRIBUTE_ID`) REFERENCES `EVENT_ATTRIBUTES` (`EVENT_ATTRIBUTE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EVENT_ATTRIBUTES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EVENT_DISEASES`
--
ALTER TABLE `EVENT_DISEASES`
ADD CONSTRAINT `EVENT_DISEASES_FK1` FOREIGN KEY (`EVENT_ID`) REFERENCES `EVENTS` (`EVENT_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EVENT_DISEASES_FK2` FOREIGN KEY (`DISEASE_ID`) REFERENCES `DISEASE_MASTER` (`DISEASE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EVENT_DISEASES_FK3` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EVENT_DISEASES_FK4` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EVENT_DISEASES_FK5` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EVENT_DISEASES_MOD_DET`
--
ALTER TABLE `EVENT_DISEASES_MOD_DET`
ADD CONSTRAINT `EVENT_DISEASES_MOD_DET_FK1` FOREIGN KEY (`EVENT_DISEASE_ID`) REFERENCES `EVENT_DISEASES` (`EVENT_DISEASE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EVENT_DISEASES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EVENT_MEMBERS`
--
ALTER TABLE `EVENT_MEMBERS`
ADD CONSTRAINT `EVENT_MEMBERS_FK1` FOREIGN KEY (`EVENT_ID`) REFERENCES `EVENTS` (`EVENT_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EVENT_MEMBERS_FK2` FOREIGN KEY (`MEMBER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EVENT_MEMBERS_FK3` FOREIGN KEY (`MEMBER_ROLE_ID`) REFERENCES `USER_TYPE` (`USER_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EVENT_MEMBERS_FK4` FOREIGN KEY (`INVITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EVENT_MEMBERS_FK5` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EVENT_MEMBERS_FK6` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EVENT_MEMBERS_FK7` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EVENT_MEMBERS_MOD_DET`
--
ALTER TABLE `EVENT_MEMBERS_MOD_DET`
ADD CONSTRAINT `EVENT_MEMBERS_MOD_DET_FK1` FOREIGN KEY (`EVENT_MEMBER_ID`) REFERENCES `EVENT_MEMBERS` (`EVENT_MEMBER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EVENT_MEMBERS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EVENT_MOD_DET`
--
ALTER TABLE `EVENT_MOD_DET`
ADD CONSTRAINT `EVENT_MOD_DET_FK1` FOREIGN KEY (`EVENT_ID`) REFERENCES `EVENTS` (`EVENT_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EVENT_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EVENT_TYPE_MASTER`
--
ALTER TABLE `EVENT_TYPE_MASTER`
ADD CONSTRAINT `EVENT_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `EVENT_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `EVENT_TYPE_MOD_DET`
--
ALTER TABLE `EVENT_TYPE_MOD_DET`
ADD CONSTRAINT `EVENT_TYPE_MOD_DET_FK1` FOREIGN KEY (`EVENT_TYPE_ID`) REFERENCES `EVENT_TYPE_MASTER` (`EVENT_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `EVENT_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `FOLLOWING_PAGES`
--
ALTER TABLE `FOLLOWING_PAGES`
ADD CONSTRAINT `FOLLOWING_PAGES_FK1` FOREIGN KEY (`PAGE_ID`) REFERENCES `PAGE_MASTER` (`PAGE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `FOLLOWING_PAGES_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `FOLLOWING_PAGES_FK3` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `FOLLOWING_PAGES_FK4` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `FOLLOWING_PAGES_FK5` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `FOLLOWING_PAGES_MOD_DET`
--
ALTER TABLE `FOLLOWING_PAGES_MOD_DET`
ADD CONSTRAINT `FOLLOWING_PAGES_MOD_DET_FK1` FOREIGN KEY (`FOLLOWING_PAGE_ID`) REFERENCES `FOLLOWING_PAGES` (`FOLLOWING_PAGE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `FOLLOWING_PAGES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `HEALTH_CONDITION_GROUPS`
--
ALTER TABLE `HEALTH_CONDITION_GROUPS`
ADD CONSTRAINT `HEALTH_CONDITION_GROUPS_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `HEALTH_CONDITION_GROUPS_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `HEALTH_CONDITION_MASTER`
--
ALTER TABLE `HEALTH_CONDITION_MASTER`
ADD CONSTRAINT `HEALTH_CONDITION_FK1` FOREIGN KEY (`HEALTH_CONDITION_GROUP_ID`) REFERENCES `HEALTH_CONDITION_GROUPS` (`HEALTH_CONDITION_GROUP_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `HEALTH_CONDITION_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `HEALTH_CONDITION_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `HEALTH_CONDITION_MOD_DET`
--
ALTER TABLE `HEALTH_CONDITION_MOD_DET`
ADD CONSTRAINT `HEALTH_CONDITION_MOD_DET_FK1` FOREIGN KEY (`HEALTH_CONDITION_ID`) REFERENCES `HEALTH_CONDITION_MASTER` (`HEALTH_CONDITION_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `HEALTH_CONDITION_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `HEALTH_COND_GROUP_MOD_DET`
--
ALTER TABLE `HEALTH_COND_GROUP_MOD_DET`
ADD CONSTRAINT `HEALTH_COND_GROUP_MOD_DET_FK1` FOREIGN KEY (`HEALTH_CONDITION_GROUP_ID`) REFERENCES `HEALTH_CONDITION_GROUPS` (`HEALTH_CONDITION_GROUP_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `HEALTH_COND_GROUP_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `INVITED_USERS`
--
ALTER TABLE `INVITED_USERS`
ADD CONSTRAINT `INVITED_USERS_FK1` FOREIGN KEY (`INVITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `INVITED_USERS_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `INVITED_USERS_MOD_DET`
--
ALTER TABLE `INVITED_USERS_MOD_DET`
ADD CONSTRAINT `INVITED_USERS_MOD_DET_FK1` FOREIGN KEY (`INVITED_USER_ID`) REFERENCES `INVITED_USERS` (`INVITED_USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `INVITED_USERS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `LANGUAGES`
--
ALTER TABLE `LANGUAGES`
ADD CONSTRAINT `LANGUAGE_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `LANGUAGE_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `LANGUAGE_MOD_DET`
--
ALTER TABLE `LANGUAGE_MOD_DET`
ADD CONSTRAINT `LANGUAGE_MOD_DET_FK1` FOREIGN KEY (`LANGUAGE_ID`) REFERENCES `LANGUAGES` (`LANGUAGE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `LANGUAGE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `MEDIA_TYPE_MASTER`
--
ALTER TABLE `MEDIA_TYPE_MASTER`
ADD CONSTRAINT `MEDIA_TYPE_MASTER_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `MEDIA_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `MEDIA_TYPE_MOD_DET`
--
ALTER TABLE `MEDIA_TYPE_MOD_DET`
ADD CONSTRAINT `MEDIA_TYPE_MOD_DET_FK1` FOREIGN KEY (`MEDIA_TYPE_ID`) REFERENCES `MEDIA_TYPE_MASTER` (`MEDIA_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `MEDIA_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `MESSAGE_RECIPIENT_ROLES`
--
ALTER TABLE `MESSAGE_RECIPIENT_ROLES`
ADD CONSTRAINT `MESSAGE_RECIPIENT_ROLES_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `MESSAGE_RECIPIENT_ROLES_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `MESSAGE_ROLE_MOD_DET`
--
ALTER TABLE `MESSAGE_ROLE_MOD_DET`
ADD CONSTRAINT `MESSAGE_ROLE_MOD_DET_FK1` FOREIGN KEY (`MESSAGE_RECIPIENT_ROLE_ID`) REFERENCES `MESSAGE_RECIPIENT_ROLES` (`MESSAGE_RECIPIENT_ROLE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `MESSAGE_ROLE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `MODULE_MASTER`
--
ALTER TABLE `MODULE_MASTER`
ADD CONSTRAINT `MODULE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `MODULE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `MODULE_MOD_DET`
--
ALTER TABLE `MODULE_MOD_DET`
ADD CONSTRAINT `MODULE_MOD_DET_FK1` FOREIGN KEY (`MODULE_ID`) REFERENCES `MODULE_MASTER` (`MODULE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `MODULE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `MONTHS_MASTER`
--
ALTER TABLE `MONTHS_MASTER`
ADD CONSTRAINT `MONTH_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `MONTH_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `MONTH_MOD_DET`
--
ALTER TABLE `MONTH_MOD_DET`
ADD CONSTRAINT `MONTH_MOD_DET_FK1` FOREIGN KEY (`MONTH_ID`) REFERENCES `MONTHS_MASTER` (`MONTH_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `MONTH_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `MOOD_MASTER`
--
ALTER TABLE `MOOD_MASTER`
ADD CONSTRAINT `USER_MOOD_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_MOOD_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `MOOD_MOD_DET`
--
ALTER TABLE `MOOD_MOD_DET`
ADD CONSTRAINT `MOOD_MOD_DET_FK1` FOREIGN KEY (`MOOD_ID`) REFERENCES `MOOD_MASTER` (`USER_MOOD_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `MOOD_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `MY_FRIENDS`
--
ALTER TABLE `MY_FRIENDS`
ADD CONSTRAINT `MY_FRIENDS_FK1` FOREIGN KEY (`MY_USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `MY_FRIENDS_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `MY_FRIENDS_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `MY_FRIENDS_DETAILS`
--
ALTER TABLE `MY_FRIENDS_DETAILS`
ADD CONSTRAINT `MY_FRIENDS_DETAILS_FK1` FOREIGN KEY (`MY_FRIEND_ID`) REFERENCES `MY_FRIENDS` (`MY_FRIEND_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `MY_FRIENDS_DETAILS_FK2` FOREIGN KEY (`FRIEND_USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `MY_FRIENDS_DETAILS_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `MY_FRIENDS_DETAILS_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `MY_FRIENDS_DETAIL_MOD_DET`
--
ALTER TABLE `MY_FRIENDS_DETAIL_MOD_DET`
ADD CONSTRAINT `MY_FRIENDS_DETAIL_MOD_DET_FK1` FOREIGN KEY (`MY_FRIENDS_DETAIL_ID`) REFERENCES `MY_FRIENDS_DETAILS` (`MY_FRIENDS_DETAIL_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `MY_FRIENDS_DETAIL_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `MY_FRIEND_MOD_DET`
--
ALTER TABLE `MY_FRIEND_MOD_DET`
ADD CONSTRAINT `MY_FRIEND_MOD_DET_FK1` FOREIGN KEY (`MY_FRIEND_ID`) REFERENCES `MY_FRIENDS` (`MY_FRIEND_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `MY_FRIEND_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NEWSLETTERS`
--
ALTER TABLE `NEWSLETTERS`
ADD CONSTRAINT `NEWSLETTERS_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `NEWSLETTERS_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `NEWSLETTERS_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NEWSLETTER_MOD_DET`
--
ALTER TABLE `NEWSLETTER_MOD_DET`
ADD CONSTRAINT `NEWSLETTER_MOD_DET_FK1` FOREIGN KEY (`NEWSLETTER_ID`) REFERENCES `NEWSLETTERS` (`NEWSLETTER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NEWSLETTER_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NEWSLETTER_QUEUE_MOD_DET`
--
ALTER TABLE `NEWSLETTER_QUEUE_MOD_DET`
ADD CONSTRAINT `NEWSLETTER_QUEUE_MOD_DET_FK1` FOREIGN KEY (`NEWSLETTER_QUEUE_ID`) REFERENCES `NEWSLETTER_QUEUE_STATUS` (`NEWSLETTER_QUEUE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NEWSLETTER_QUEUE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NEWSLETTER_QUEUE_STATUS`
--
ALTER TABLE `NEWSLETTER_QUEUE_STATUS`
ADD CONSTRAINT `NEWSLETTER_QUEUE_STATUS_FK1` FOREIGN KEY (`NEWSLETTER_ID`) REFERENCES `NEWSLETTERS` (`NEWSLETTER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NEWSLETTER_QUEUE_STATUS_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `NEWSLETTER_QUEUE_STATUS_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `NEWSLETTER_QUEUE_STATUS_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NEWSLETTER_TEMPLATES`
--
ALTER TABLE `NEWSLETTER_TEMPLATES`
ADD CONSTRAINT `NEWSLETTER_TEMPLATES_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `NEWSLETTER_TEMPLATES_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `NEWSLETTER_TEMPLATES_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NEWSLETTER_TEMPLATE_MOD_DET`
--
ALTER TABLE `NEWSLETTER_TEMPLATE_MOD_DET`
ADD CONSTRAINT `NEWSLETTER_TEMPLATE_MOD_DET_FK1` FOREIGN KEY (`NEWSLETTER_TEMPLATE_ID`) REFERENCES `NEWSLETTER_TEMPLATES` (`NEWSLETTER_TEMPLATE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NEWSLETTER_TEMPLATE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NOTIFICATIONS`
--
ALTER TABLE `NOTIFICATIONS`
ADD CONSTRAINT `NOTIFICATION_FK1` FOREIGN KEY (`NOTIFICATION_ACTIVITY_TYPE_ID`) REFERENCES `NOTIFICATION_ACTIVITY_TYPE_MASTER` (`NOTIFICATION_ACTIVITY_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NOTIFICATION_FK2` FOREIGN KEY (`NOTIFICATION_OBJECT_TYPE_ID`) REFERENCES `NOTIFICATION_OBJECT_TYPE_MASTER` (`NOTIFICATION_OBJECT_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NOTIFICATION_FK3` FOREIGN KEY (`NOTIFICATION_ACTIVITY_SECTION_TYPE_ID`) REFERENCES `ACTIVITY_SECTION_MASTER` (`ACTIVITY_SECTION_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NOTIFICATION_FK4` FOREIGN KEY (`SENDER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NOTIFICATION_FK5` FOREIGN KEY (`OBJECT_OWNER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `NOTIFICATION_FK6` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `NOTIFICATION_FK7` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `NOTIFICATION_FK8` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NOTIFICATION_ACTIVITY_MOD_DET`
--
ALTER TABLE `NOTIFICATION_ACTIVITY_MOD_DET`
ADD CONSTRAINT `NOTIFICATION_ACTIVITY_MOD_DET_FK1` FOREIGN KEY (`NOTIFICATION_ACTIVITY_TYPE_ID`) REFERENCES `NOTIFICATION_ACTIVITY_TYPE_MASTER` (`NOTIFICATION_ACTIVITY_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NOTIFICATION_ACTIVITY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NOTIFICATION_ACTIVITY_TYPE_MASTER`
--
ALTER TABLE `NOTIFICATION_ACTIVITY_TYPE_MASTER`
ADD CONSTRAINT `NOTIFICATION_ACTIVITY_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `NOTIFICATION_ACTIVITY_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NOTIFICATION_FREQUENCY_MASTER`
--
ALTER TABLE `NOTIFICATION_FREQUENCY_MASTER`
ADD CONSTRAINT `NOTIFICATION_FREQUENCY_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `NOTIFICATION_FREQUENCY_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NOTIFICATION_FREQUENCY_MOD_DET`
--
ALTER TABLE `NOTIFICATION_FREQUENCY_MOD_DET`
ADD CONSTRAINT `NOTIFICATION_FREQUENCY_MOD_DET_FK1` FOREIGN KEY (`NOTIFICATION_FREQUENCY_ID`) REFERENCES `NOTIFICATION_FREQUENCY_MASTER` (`NOTIFICATION_FREQUENCY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NOTIFICATION_FREQUENCY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NOTIFICATION_MOD_DET`
--
ALTER TABLE `NOTIFICATION_MOD_DET`
ADD CONSTRAINT `NOTIFICATION_MOD_DET_FK1` FOREIGN KEY (`NOTIFICATION_ID`) REFERENCES `NOTIFICATIONS` (`NOTIFICATION_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NOTIFICATION_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NOTIFICATION_OBJECT_TYPE_MASTER`
--
ALTER TABLE `NOTIFICATION_OBJECT_TYPE_MASTER`
ADD CONSTRAINT `NOTIFICATION_OBJECT_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `NOTIFICATION_OBJECT_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NOTIFICATION_OBJECT_TYPE_MOD_DET`
--
ALTER TABLE `NOTIFICATION_OBJECT_TYPE_MOD_DET`
ADD CONSTRAINT `NOTIFICATION_OBJECT_TYPE_MOD_DET_FK1` FOREIGN KEY (`NOTIFICATION_OBJECT_TYPE_ID`) REFERENCES `NOTIFICATION_OBJECT_TYPE_MASTER` (`NOTIFICATION_OBJECT_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NOTIFICATION_OBJECT_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NOTIFICATION_RECIPIENTS`
--
ALTER TABLE `NOTIFICATION_RECIPIENTS`
ADD CONSTRAINT `NOTIFICATION_RECIPIENT_FK1` FOREIGN KEY (`NOTIFICATION_ID`) REFERENCES `NOTIFICATIONS` (`NOTIFICATION_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NOTIFICATION_RECIPIENT_FK2` FOREIGN KEY (`RECIPIENT_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NOTIFICATION_RECIPIENT_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `NOTIFICATION_RECIPIENT_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NOTIFICATION_RECIPIENT_MOD_DET`
--
ALTER TABLE `NOTIFICATION_RECIPIENT_MOD_DET`
ADD CONSTRAINT `NOTIFICATION_RECIPIENT_MOD_DET_FK1` FOREIGN KEY (`NOTIFICATION_RECIPIENT_ID`) REFERENCES `NOTIFICATION_RECIPIENTS` (`NOTIFICATION_RECIPIENT_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NOTIFICATION_RECIPIENT_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NOTIFICATION_SETTINGS`
--
ALTER TABLE `NOTIFICATION_SETTINGS`
ADD CONSTRAINT `NOTIFICATION_SETTING_FK1` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NOTIFICATION_SETTING_FK2` FOREIGN KEY (`HEIGHT_UNIT`) REFERENCES `UNIT_OF_MEASUREMENT_MASTER` (`UNIT_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `NOTIFICATION_SETTING_FK3` FOREIGN KEY (`WEIGHT_UNIT`) REFERENCES `UNIT_OF_MEASUREMENT_MASTER` (`UNIT_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `NOTIFICATION_SETTING_FK4` FOREIGN KEY (`TEMP_UNIT`) REFERENCES `UNIT_OF_MEASUREMENT_MASTER` (`UNIT_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `NOTIFICATION_SETTING_FK5` FOREIGN KEY (`NOTIFICATION_FREQUENCY_ID`) REFERENCES `NOTIFICATION_FREQUENCY_MASTER` (`NOTIFICATION_FREQUENCY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NOTIFICATION_SETTING_FK6` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `NOTIFICATION_SETTING_FK7` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NOTIFICATION_SETTING_MOD_DET`
--
ALTER TABLE `NOTIFICATION_SETTING_MOD_DET`
ADD CONSTRAINT `NOTIFICATION_SETTING_MOD_DET_FK1` FOREIGN KEY (`NOTIFICATION_SETTING_ID`) REFERENCES `NOTIFICATION_SETTINGS` (`NOTIFICATION_SETTING_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NOTIFICATION_SETTING_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NOTIFIED_USERS`
--
ALTER TABLE `NOTIFIED_USERS`
ADD CONSTRAINT `NOTIFIED_USER_FK1` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NOTIFIED_USER_FK2` FOREIGN KEY (`NOTIFICATION_SETTING_ID`) REFERENCES `NOTIFICATION_SETTINGS` (`NOTIFICATION_SETTING_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NOTIFIED_USER_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `NOTIFIED_USER_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `NOTIFIED_USER_MOD_DET`
--
ALTER TABLE `NOTIFIED_USER_MOD_DET`
ADD CONSTRAINT `NOTIFIED_USER_MOD_DET_FK1` FOREIGN KEY (`NOTIFIED_USER_ID`) REFERENCES `NOTIFIED_USERS` (`NOTIFIED_USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `NOTIFIED_USER_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `PAGE_MASTER`
--
ALTER TABLE `PAGE_MASTER`
ADD CONSTRAINT `PAGE_MASTER_FK1` FOREIGN KEY (`PAGE_TYPE_ID`) REFERENCES `PAGE_TYPE_MASTER` (`PAGE_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `PAGE_MASTER_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `PAGE_MASTER_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `PAGE_MOD_DET`
--
ALTER TABLE `PAGE_MOD_DET`
ADD CONSTRAINT `PAGE_MOD_DET_FK1` FOREIGN KEY (`PAGE_ID`) REFERENCES `PAGE_MASTER` (`PAGE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `PAGE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `PAGE_TYPE_MASTER`
--
ALTER TABLE `PAGE_TYPE_MASTER`
ADD CONSTRAINT `PAGE_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `PAGE_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `PAGE_TYPE_MOD_DET`
--
ALTER TABLE `PAGE_TYPE_MOD_DET`
ADD CONSTRAINT `PAGE_TYPE_MOD_DET_FK1` FOREIGN KEY (`PAGE_TYPE_ID`) REFERENCES `PAGE_TYPE_MASTER` (`PAGE_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `PAGE_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `PAIN_LEVELS_MASTER`
--
ALTER TABLE `PAIN_LEVELS_MASTER`
ADD CONSTRAINT `PAIN_LEVELS_MASTER_FK1` FOREIGN KEY (`PAIN_ID`) REFERENCES `PAIN_MASTER` (`PAIN_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `PAIN_LEVELS_MASTER_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `PAIN_LEVELS_MASTER_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `PAIN_LEVEL_MOD_DET`
--
ALTER TABLE `PAIN_LEVEL_MOD_DET`
ADD CONSTRAINT `PAIN_LEVEL_MOD_DET_FK1` FOREIGN KEY (`PAIN_LEVEL_ID`) REFERENCES `PAIN_LEVELS_MASTER` (`PAIN_LEVEL_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `PAIN_LEVEL_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `PAIN_MASTER`
--
ALTER TABLE `PAIN_MASTER`
ADD CONSTRAINT `PAIN_MASTER_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `PAIN_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `PAIN_TYPE_MOD_DET`
--
ALTER TABLE `PAIN_TYPE_MOD_DET`
ADD CONSTRAINT `PAIN_TYPE_MOD_DET_FK1` FOREIGN KEY (`PAIN_ID`) REFERENCES `PAIN_MASTER` (`PAIN_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `PAIN_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `PATIENT_CARE_GIVERS`
--
ALTER TABLE `PATIENT_CARE_GIVERS`
ADD CONSTRAINT `PATIENT_CARE_GIVERS_FK1` FOREIGN KEY (`RELATIONSHIP_ID`) REFERENCES `CAREGIVER_RELATIONSHIP_MASTER` (`RELATIONSHIP_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `PATIENT_CARE_GIVERS_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `PATIENT_CARE_GIVERS_FK3` FOREIGN KEY (`PATIENT_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `PATIENT_CARE_GIVERS_FK4` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `PATIENT_CARE_GIVERS_FK5` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `PATIENT_CARE_GIVER_MOD_DET`
--
ALTER TABLE `PATIENT_CARE_GIVER_MOD_DET`
ADD CONSTRAINT `PATIENT_CARE_GIVER_MOD_DET_FK1` FOREIGN KEY (`PATIENT_CARE_GIVER_ID`) REFERENCES `PATIENT_CARE_GIVERS` (`PATIENT_CARE_GIVER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `PATIENT_CARE_GIVER_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `PHOTO_TYPE_MASTER`
--
ALTER TABLE `PHOTO_TYPE_MASTER`
ADD CONSTRAINT `PHOTO_TYPE_MASTER_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `PHOTO_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `PHOTO_TYPE_MOD_DET`
--
ALTER TABLE `PHOTO_TYPE_MOD_DET`
ADD CONSTRAINT `PHOTO_TYPE_MOD_DET_FK1` FOREIGN KEY (`PHOTO_TYPE_ID`) REFERENCES `PHOTO_TYPE_MASTER` (`PHOTO_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `PHOTO_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `POLLS`
--
ALTER TABLE `POLLS`
ADD CONSTRAINT `POLL_FK1` FOREIGN KEY (`POLL_SECTION_TYPE_ID`) REFERENCES `ACTIVITY_SECTION_MASTER` (`ACTIVITY_SECTION_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `POLL_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `POLL_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `POLL_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `POLL_CHOICES`
--
ALTER TABLE `POLL_CHOICES`
ADD CONSTRAINT `POLL_CHOICE_FK1` FOREIGN KEY (`POLL_ID`) REFERENCES `POLLS` (`POLL_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POLL_CHOICE_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `POLL_CHOICE_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `POLL_CHOICE_MOD_DET`
--
ALTER TABLE `POLL_CHOICE_MOD_DET`
ADD CONSTRAINT `POLL_CHOICE_MOD_DET_FK1` FOREIGN KEY (`POLL_CHOICE_ID`) REFERENCES `POLL_CHOICES` (`POLL_CHOICE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POLL_CHOICE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `POLL_MOD_DET`
--
ALTER TABLE `POLL_MOD_DET`
ADD CONSTRAINT `POLL_MOD_DET_FK1` FOREIGN KEY (`POLL_ID`) REFERENCES `POLLS` (`POLL_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POLL_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `POSTS`
--
ALTER TABLE `POSTS`
ADD CONSTRAINT `POSTS_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `POSTS_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POSTS_FK5` FOREIGN KEY (`POST_TYPE_ID`) REFERENCES `POST_TYPE_MASTER` (`POST_TYPE_ID`) ON DELETE CASCADE;

--
-- Constraints for table `POST_COMMENTS`
--
ALTER TABLE `POST_COMMENTS`
ADD CONSTRAINT `POST_COMMENTS_FK1` FOREIGN KEY (`POST_ID`) REFERENCES `POSTS` (`POST_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POST_COMMENTS_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `POST_COMMENTS_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `POST_COMMENTS_MOD_DET`
--
ALTER TABLE `POST_COMMENTS_MOD_DET`
ADD CONSTRAINT `POST_COMMENTS_MOD_DET_FK1` FOREIGN KEY (`POST_COMMENT_ID`) REFERENCES `POST_COMMENTS` (`POST_COMMENT_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POST_COMMENTS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `POST_CONTENT_DETAILS`
--
ALTER TABLE `POST_CONTENT_DETAILS`
ADD CONSTRAINT `POST_CONTENT_DETAILS_FK1` FOREIGN KEY (`POST_ID`) REFERENCES `POSTS` (`POST_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POST_CONTENT_DETAILS_FK2` FOREIGN KEY (`CONTENT_ATTRIBUTE_ID`) REFERENCES `ATTRIBUTES_MASTER` (`ATTRIBUTE_ID`) ON DELETE CASCADE;

--
-- Constraints for table `POST_CONTENT_MOD_DET`
--
ALTER TABLE `POST_CONTENT_MOD_DET`
ADD CONSTRAINT `POST_CONTENT_MOD_DET_FK1` FOREIGN KEY (`POST_CONTENT_ID`) REFERENCES `POST_CONTENT_DETAILS` (`POST_CONTENT_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POST_CONTENT_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `POST_LIKES`
--
ALTER TABLE `POST_LIKES`
ADD CONSTRAINT `POST_LIKES_FK1` FOREIGN KEY (`POST_ID`) REFERENCES `POSTS` (`POST_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POST_LIKES_FK2` FOREIGN KEY (`LIKED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `POST_LIKES_FK3` FOREIGN KEY (`POST_LIKE_STATUS`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `POST_LIKES_MOD_DET`
--
ALTER TABLE `POST_LIKES_MOD_DET`
ADD CONSTRAINT `POST_LIKES_MOD_DET_FK1` FOREIGN KEY (`POST_LIKE_ID`) REFERENCES `POST_LIKES` (`POST_LIKE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POST_LIKES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `POST_LOCATION`
--
ALTER TABLE `POST_LOCATION`
ADD CONSTRAINT `POST_LOCATION_FK1` FOREIGN KEY (`POST_ID`) REFERENCES `POSTS` (`POST_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POST_LOCATION_FK2` FOREIGN KEY (`POST_LOCATION`) REFERENCES `POST_LOCATION_MASTER` (`POST_LOCATION_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POST_LOCATION_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `POST_LOCATION_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `POST_LOCATION_MASTER`
--
ALTER TABLE `POST_LOCATION_MASTER`
ADD CONSTRAINT `POST_LOCATION_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `POST_LOCATION_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `POST_LOCATION_MASTER_MOD_DET`
--
ALTER TABLE `POST_LOCATION_MASTER_MOD_DET`
ADD CONSTRAINT `POST_LOCATION_MASTER_MOD_DET_FK1` FOREIGN KEY (`POST_LOCATION_ID`) REFERENCES `POST_LOCATION_MASTER` (`POST_LOCATION_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POST_LOCATION_MASTER_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `POST_LOCATION_MOD_DET`
--
ALTER TABLE `POST_LOCATION_MOD_DET`
ADD CONSTRAINT `POST_LOCATION_MOD_DET_FK1` FOREIGN KEY (`POST_LOCATION_ID`) REFERENCES `POST_LOCATION` (`POST_LOCATION_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POST_LOCATION_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `POST_MOD_DET`
--
ALTER TABLE `POST_MOD_DET`
ADD CONSTRAINT `POST_MOD_DET_FK1` FOREIGN KEY (`POST_ID`) REFERENCES `POSTS` (`POST_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POST_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `POST_PRIVACY_MOD_DET`
--
ALTER TABLE `POST_PRIVACY_MOD_DET`
ADD CONSTRAINT `POST_PRIVACY_MOD_DET_FK1` FOREIGN KEY (`POST_PRIVACY_ID`) REFERENCES `POST_PRIVACY_SETTINGS` (`POST_PRIVACY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POST_PRIVACY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `POST_PRIVACY_SETTINGS`
--
ALTER TABLE `POST_PRIVACY_SETTINGS`
ADD CONSTRAINT `POST_PRIVACY_FK1` FOREIGN KEY (`POST_ID`) REFERENCES `POSTS` (`POST_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POST_PRIVACY_FK2` FOREIGN KEY (`USER_TYPE_ID`) REFERENCES `USER_TYPE` (`USER_TYPE_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `POST_PRIVACY_FK3` FOREIGN KEY (`PRIVACY_ID`) REFERENCES `PRIVACY_MASTER` (`PRIVACY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POST_PRIVACY_FK4` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `POST_PRIVACY_FK5` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `POST_TYPE_MASTER`
--
ALTER TABLE `POST_TYPE_MASTER`
ADD CONSTRAINT `POST_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `POST_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `POST_TYPE_MOD_DET`
--
ALTER TABLE `POST_TYPE_MOD_DET`
ADD CONSTRAINT `POST_TYPE_MOD_DET_FK1` FOREIGN KEY (`POST_TYPE_ID`) REFERENCES `POST_TYPE_MASTER` (`POST_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `POST_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `PRIVACY_MASTER`
--
ALTER TABLE `PRIVACY_MASTER`
ADD CONSTRAINT `PRIVACY_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `PRIVACY_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `PRIVACY_MOD_DET`
--
ALTER TABLE `PRIVACY_MOD_DET`
ADD CONSTRAINT `PRIVACY_MOD_DET_FK1` FOREIGN KEY (`PRIVACY_ID`) REFERENCES `PRIVACY_MASTER` (`PRIVACY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `PRIVACY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `PUBLISH_TYPE_MASTER`
--
ALTER TABLE `PUBLISH_TYPE_MASTER`
ADD CONSTRAINT `PUBLISH_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `PUBLISH_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `PUBLISH_TYPE_MOD_DET`
--
ALTER TABLE `PUBLISH_TYPE_MOD_DET`
ADD CONSTRAINT `PUBLISH_TYPE_MOD_DET_FK1` FOREIGN KEY (`PUBLISH_TYPE_ID`) REFERENCES `PUBLISH_TYPE_MASTER` (`PUBLISH_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `PUBLISH_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `QUESTION_GROUP_MASTER`
--
ALTER TABLE `QUESTION_GROUP_MASTER`
ADD CONSTRAINT `QUESTION_GROUP_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `QUESTION_GROUP_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `QUESTION_GROUP_MOD_DET`
--
ALTER TABLE `QUESTION_GROUP_MOD_DET`
ADD CONSTRAINT `QUESTION_GROUP_MOD_DET_FK1` FOREIGN KEY (`QUESTION_GROUP_ID`) REFERENCES `QUESTION_GROUP_MASTER` (`QUESTION_GROUP_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `QUESTION_GROUP_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `QUESTION_MASTER`
--
ALTER TABLE `QUESTION_MASTER`
ADD CONSTRAINT `QUESTION_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `QUESTION_FK2` FOREIGN KEY (`QUESTION_GROUP_ID`) REFERENCES `QUESTION_GROUP_MASTER` (`QUESTION_GROUP_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `QUESTION_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `QUESTION_MOD_DET`
--
ALTER TABLE `QUESTION_MOD_DET`
ADD CONSTRAINT `QUESTION_MOD_DET_FK1` FOREIGN KEY (`QUESTION_ID`) REFERENCES `QUESTION_MASTER` (`QUESTION_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `QUESTION_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `REPEAT_BY_TYPE_MASTER`
--
ALTER TABLE `REPEAT_BY_TYPE_MASTER`
ADD CONSTRAINT `REPEAT_BY_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `REPEAT_BY_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `REPEAT_BY_TYPE_MOD_DET`
--
ALTER TABLE `REPEAT_BY_TYPE_MOD_DET`
ADD CONSTRAINT `REPEAT_BY_TYPE_MOD_DET_FK1` FOREIGN KEY (`REPEAT_BY_TYPE_ID`) REFERENCES `REPEAT_BY_TYPE_MASTER` (`REPEAT_BY_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `REPEAT_BY_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `REPEAT_END_TYPE_MASTER`
--
ALTER TABLE `REPEAT_END_TYPE_MASTER`
ADD CONSTRAINT `REPEAT_END_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `REPEAT_END_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `REPEAT_END_TYPE_MOD_DET`
--
ALTER TABLE `REPEAT_END_TYPE_MOD_DET`
ADD CONSTRAINT `REPEAT_END_TYPE_MOD_DET_FK1` FOREIGN KEY (`REPEAT_END_TYPE_ID`) REFERENCES `REPEAT_END_TYPE_MASTER` (`REPEAT_END_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `REPEAT_END_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `REPEAT_MODE_TYPE_MASTER`
--
ALTER TABLE `REPEAT_MODE_TYPE_MASTER`
ADD CONSTRAINT `REPEAT_MODE_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `REPEAT_MODE_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `REPEAT_MODE_TYPE_MOD_DET`
--
ALTER TABLE `REPEAT_MODE_TYPE_MOD_DET`
ADD CONSTRAINT `REPEAT_MODE_TYPE_MOD_DET_FK1` FOREIGN KEY (`REPEAT_MODE_TYPE_ID`) REFERENCES `REPEAT_MODE_TYPE_MASTER` (`REPEAT_MODE_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `REPEAT_MODE_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `REPEAT_TYPE_MASTER`
--
ALTER TABLE `REPEAT_TYPE_MASTER`
ADD CONSTRAINT `REPEAT_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `REPEAT_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `REPEAT_TYPE_MOD_DET`
--
ALTER TABLE `REPEAT_TYPE_MOD_DET`
ADD CONSTRAINT `REPEAT_TYPE_MOD_DET_FK1` FOREIGN KEY (`REPEAT_TYPE_ID`) REFERENCES `REPEAT_TYPE_MASTER` (`REPEAT_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `REPEAT_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `SECTION_TYPE_MASTER`
--
ALTER TABLE `SECTION_TYPE_MASTER`
ADD CONSTRAINT `SECTION_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `SECTION_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `SECTION_TYPE_MOD_DET`
--
ALTER TABLE `SECTION_TYPE_MOD_DET`
ADD CONSTRAINT `SECTION_TYPE_MOD_DET_FK1` FOREIGN KEY (`SECTION_TYPE_ID`) REFERENCES `SECTION_TYPE_MASTER` (`SECTION_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `SECTION_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `STATES_MASTER`
--
ALTER TABLE `STATES_MASTER`
ADD CONSTRAINT `STATES_FK1` FOREIGN KEY (`COUNTRY_ID`) REFERENCES `COUNTRY_MASTER` (`COUNTRY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `STATES_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `STATES_FK3` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `STATES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `STATES_MOD_DET`
--
ALTER TABLE `STATES_MOD_DET`
ADD CONSTRAINT `STATES_MOD_DET_FK1` FOREIGN KEY (`STATE_ID`) REFERENCES `STATES_MASTER` (`STATE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `STATES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `STATUS`
--
ALTER TABLE `STATUS`
ADD CONSTRAINT `STATUS_FK1` FOREIGN KEY (`STATUS_TYPE_ID`) REFERENCES `STATUS_TYPE` (`STATUS_TYPE_ID`),
ADD CONSTRAINT `STATUS_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `STATUS_MOD_DET`
--
ALTER TABLE `STATUS_MOD_DET`
ADD CONSTRAINT `STATUS_MOD_DET_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `STATUS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `STATUS_TYPE`
--
ALTER TABLE `STATUS_TYPE`
ADD CONSTRAINT `STATUS_TYPE_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `STATUS_TYPE_MOD_DET`
--
ALTER TABLE `STATUS_TYPE_MOD_DET`
ADD CONSTRAINT `STATUS_TYPE_MOD_DET_FK1` FOREIGN KEY (`STATUS_TYPE_ID`) REFERENCES `STATUS_TYPE` (`STATUS_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `STATUS_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `SURVEY_MASTER`
--
ALTER TABLE `SURVEY_MASTER`
ADD CONSTRAINT `SURVEY_MASTER_FK1` FOREIGN KEY (`SURVEY_TYPE`) REFERENCES `SURVEY_TYPE_MASTER` (`SURVEY_TYPE_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `SURVEY_MASTER_FK2` FOREIGN KEY (`SURVEY_STATUS`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `SURVEY_MASTER_FK3` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `SURVEY_MOD_DET`
--
ALTER TABLE `SURVEY_MOD_DET`
ADD CONSTRAINT `SURVEY_MOD_DET_FK1` FOREIGN KEY (`SURVEY_ID`) REFERENCES `SURVEY_MASTER` (`SURVEY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `SURVEY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `SURVEY_QUESTIONS`
--
ALTER TABLE `SURVEY_QUESTIONS`
ADD CONSTRAINT `SURVEY_QUESTIONS_FK1` FOREIGN KEY (`SURVEY_ID`) REFERENCES `SURVEY_MASTER` (`SURVEY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `SURVEY_QUESTIONS_FK2` FOREIGN KEY (`QUESTION_ID`) REFERENCES `QUESTION_MASTER` (`QUESTION_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `SURVEY_QUESTIONS_FK3` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `SURVEY_QUESTIONS_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `SURVEY_QUESTIONS_ANSWER_CHOICES`
--
ALTER TABLE `SURVEY_QUESTIONS_ANSWER_CHOICES`
ADD CONSTRAINT `SURVEY_QUESTIONS_ANSWER_CHOICES_FK1` FOREIGN KEY (`SURVEY_QUESTION_ID`) REFERENCES `SURVEY_QUESTIONS` (`SURVEY_QUESTION_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `SURVEY_QUESTIONS_ANSWER_CHOICES_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `SURVEY_QUESTIONS_ANSWER_CHOICES_FK3` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET`
--
ALTER TABLE `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET`
ADD CONSTRAINT `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET_FK1` FOREIGN KEY (`SURVEY_QUESTIONS_ANSWER_CHOICE_ID`) REFERENCES `SURVEY_QUESTIONS_ANSWER_CHOICES` (`SURVEY_QUESTIONS_ANSWER_CHOICE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `SURVEY_QUESTIONS_MOD_DET`
--
ALTER TABLE `SURVEY_QUESTIONS_MOD_DET`
ADD CONSTRAINT `SURVEY_QUESTIONS_MOD_DET_FK1` FOREIGN KEY (`SURVEY_QUESTION_ID`) REFERENCES `SURVEY_QUESTIONS` (`SURVEY_QUESTION_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `SURVEY_QUESTIONS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `SURVEY_RESULTS_ANSWER_CHOICES`
--
ALTER TABLE `SURVEY_RESULTS_ANSWER_CHOICES`
ADD CONSTRAINT `SURVEY_RESULTS_ANSWER_CHOICES_FK1` FOREIGN KEY (`SURVEY_QUESTIONS_ANSWER_CHOICE_ID`) REFERENCES `SURVEY_QUESTIONS_ANSWER_CHOICES` (`SURVEY_QUESTIONS_ANSWER_CHOICE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `SURVEY_RESULTS_ANSWER_CHOICES_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `SURVEY_RESULTS_ANSWER_CHOICES_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `SURVEY_RESULTS_ANSWER_CHOICES_FK4` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET`
--
ALTER TABLE `SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET`
ADD CONSTRAINT `SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET_FK1` FOREIGN KEY (`SURVEY_RESULTS_ANSWER_CHOICE_ID`) REFERENCES `SURVEY_RESULTS_ANSWER_CHOICES` (`SURVEY_RESULTS_ANSWER_CHOICE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `SURVEY_RESULTS_DETAILED_ANSWERS`
--
ALTER TABLE `SURVEY_RESULTS_DETAILED_ANSWERS`
ADD CONSTRAINT `SURVEY_RESULTS_DETAILED_ANSWERS_FK1` FOREIGN KEY (`SURVEY_QUESTION_ID`) REFERENCES `SURVEY_QUESTIONS` (`SURVEY_QUESTION_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `SURVEY_RESULTS_DETAILED_ANSWERS_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `SURVEY_RESULTS_DETAILED_ANSWERS_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `SURVEY_RESULTS_DETAILED_ANSWERS_FK4` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET`
--
ALTER TABLE `SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET`
ADD CONSTRAINT `SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET_FK1` FOREIGN KEY (`SURVEY_RESULTS_DETAILED_ANSWER_ID`) REFERENCES `SURVEY_RESULTS_DETAILED_ANSWERS` (`SURVEY_RESULTS_DETAILED_ANSWER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `SURVEY_TYPE_MASTER`
--
ALTER TABLE `SURVEY_TYPE_MASTER`
ADD CONSTRAINT `SURVEY_TYPE_MASTER_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `SURVEY_TYPE_MASTER_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `SURVEY_TYPE_MOD_DET`
--
ALTER TABLE `SURVEY_TYPE_MOD_DET`
ADD CONSTRAINT `SURVEY_TYPE_MOD_DET_FK1` FOREIGN KEY (`SURVEY_TYPE_ID`) REFERENCES `SURVEY_TYPE_MASTER` (`SURVEY_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `SURVEY_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `SYMPTOMS_MASTER`
--
ALTER TABLE `SYMPTOMS_MASTER`
ADD CONSTRAINT `SYMPTOMS_MASTER_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `SYMPTOMS_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `SYMPTOMS_MOD_DET`
--
ALTER TABLE `SYMPTOMS_MOD_DET`
ADD CONSTRAINT `SYMPTOM_MOD_DET_FK1` FOREIGN KEY (`SYMPTOM_ID`) REFERENCES `SYMPTOMS_MASTER` (`SYMPTOM_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `SYMPTOM_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `TEAMS`
--
ALTER TABLE `TEAMS`
ADD CONSTRAINT `TEAMS_FK1` FOREIGN KEY (`PATIENT_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `TEAMS_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `TEAMS_FK3` FOREIGN KEY (`TEAM_STATUS`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `TEAM_MEMBERS`
--
ALTER TABLE `TEAM_MEMBERS`
ADD CONSTRAINT `TEAM_MEMBERS_FK1` FOREIGN KEY (`TEAM_ID`) REFERENCES `TEAMS` (`TEAM_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `TEAM_MEMBERS_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `TEAM_MEMBERS_FK3` FOREIGN KEY (`USER_ROLE_ID`) REFERENCES `USER_TYPE` (`USER_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `TEAM_MEMBERS_FK4` FOREIGN KEY (`MEMBER_STATUS`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `TEAM_MEMBERS_FK5` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `TEAM_MEMBERS_MOD_DET`
--
ALTER TABLE `TEAM_MEMBERS_MOD_DET`
ADD CONSTRAINT `TEAM_MEMBERS_MOD_DET_FK1` FOREIGN KEY (`TEAM_MEMBER_ID`) REFERENCES `TEAM_MEMBERS` (`TEAM_MEMBER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `TEAM_MEMBERS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `TEAM_MOD_DET`
--
ALTER TABLE `TEAM_MOD_DET`
ADD CONSTRAINT `TEAM_MOD_DET_FK1` FOREIGN KEY (`TEAM_ID`) REFERENCES `TEAMS` (`TEAM_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `TEAM_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `TEAM_PRIVACY_SETTINGS`
--
ALTER TABLE `TEAM_PRIVACY_SETTINGS`
ADD CONSTRAINT `TEAM_PRIVACY_SETTINGS_FK1` FOREIGN KEY (`TEAM_ID`) REFERENCES `TEAMS` (`TEAM_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `TEAM_PRIVACY_SETTINGS_FK2` FOREIGN KEY (`USER_TYPE_ID`) REFERENCES `USER_TYPE` (`USER_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `TEAM_PRIVACY_SETTINGS_FK3` FOREIGN KEY (`PRIVACY_ID`) REFERENCES `PRIVACY_MASTER` (`PRIVACY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `TEAM_PRIVACY_SETTINGS_FK4` FOREIGN KEY (`PRIVACY_SET_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `TEAM_PRIVACY_SETTINGS_FK5` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `TEAM_PRIVACY_SETTING_MOD_DET`
--
ALTER TABLE `TEAM_PRIVACY_SETTING_MOD_DET`
ADD CONSTRAINT `TEAM_PRIVACY_SETTING_MOD_DET_FK1` FOREIGN KEY (`TEAM_PRIVACY_SETTING_ID`) REFERENCES `TEAM_PRIVACY_SETTINGS` (`TEAM_PRIVACY_SETTING_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `TEAM_PRIVACY_SETTING_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `TIMEZONE_MASTER`
--
ALTER TABLE `TIMEZONE_MASTER`
ADD CONSTRAINT `TIMEZONE_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `TIMEZONE_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `TIMEZONE_MOD_DET`
--
ALTER TABLE `TIMEZONE_MOD_DET`
ADD CONSTRAINT `TIMEZONE_MOD_DET_FK1` FOREIGN KEY (`TIMEZONE_ID`) REFERENCES `TIMEZONE_MASTER` (`TIMEZONE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `TIMEZONE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `TREATMENT_MASTER`
--
ALTER TABLE `TREATMENT_MASTER`
ADD CONSTRAINT `TREATMENT_MASTER_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `TREATMENT_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `TREATMENT_MASTER_MOD_DET`
--
ALTER TABLE `TREATMENT_MASTER_MOD_DET`
ADD CONSTRAINT `TREATMENT_MASTER_MOD_DET_FK1` FOREIGN KEY (`TREATMENT_ID`) REFERENCES `TREATMENT_MASTER` (`TREATMENT_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `TREATMENT_MASTER_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `UNIT_OF_MEASUREMENT_MASTER`
--
ALTER TABLE `UNIT_OF_MEASUREMENT_MASTER`
ADD CONSTRAINT `UOM_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `UOM_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `UNIT_OF_MEASUREMENT_MOD_DET`
--
ALTER TABLE `UNIT_OF_MEASUREMENT_MOD_DET`
ADD CONSTRAINT `UOM_MOD_DET_FK1` FOREIGN KEY (`UNIT_ID`) REFERENCES `UNIT_OF_MEASUREMENT_MASTER` (`UNIT_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `UOM_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USERS`
--
ALTER TABLE `USERS`
ADD CONSTRAINT `USERS_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USERS_FK2` FOREIGN KEY (`LANGUAGE`) REFERENCES `LANGUAGES` (`LANGUAGE_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USERS_FK3` FOREIGN KEY (`COUNTRY`) REFERENCES `COUNTRY_MASTER` (`COUNTRY_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USERS_FK4` FOREIGN KEY (`STATE`) REFERENCES `STATES_MASTER` (`STATE_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USERS_FK5` FOREIGN KEY (`CITY`) REFERENCES `CITIES_MASTER` (`CITY_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USERS_FK6` FOREIGN KEY (`USER_TYPE`) REFERENCES `USER_TYPE` (`USER_TYPE_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USERS_FK7` FOREIGN KEY (`TIMEZONE`) REFERENCES `TIMEZONE_MASTER` (`TIMEZONE_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USERS_FK8` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_ACTIVITY_LOGS`
--
ALTER TABLE `USER_ACTIVITY_LOGS`
ADD CONSTRAINT `USER_ACTIVITY_LOGS_FK1` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_ACTIVITY_LOGS_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_ACTIVITY_LOGS_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_ACTIVITY_MOD_DET`
--
ALTER TABLE `USER_ACTIVITY_MOD_DET`
ADD CONSTRAINT `USER_ACTIVITY_MOD_DET_FK1` FOREIGN KEY (`USER_ACTIVITY_ID`) REFERENCES `USER_ACTIVITY_LOGS` (`USER_ACTIVITY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_ACTIVITY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_ATTRIBUTES`
--
ALTER TABLE `USER_ATTRIBUTES`
ADD CONSTRAINT `USER_ATTRIBUTES_FK1` FOREIGN KEY (`ATTRIBUTE_ID`) REFERENCES `ATTRIBUTES_MASTER` (`ATTRIBUTE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_ATTRIBUTES_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_ATTRIBUTES_FK3` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_ATTRIBUTES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_ATTRIBUTE_MOD_HISTORY`
--
ALTER TABLE `USER_ATTRIBUTE_MOD_HISTORY`
ADD CONSTRAINT `USER_ATTRIBUTE_MOD_FK1` FOREIGN KEY (`USER_ATTRIBUTE_ID`) REFERENCES `USER_ATTRIBUTES` (`USER_ATTRIBUTE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_ATTRIBUTE_MOD_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE;

--
-- Constraints for table `USER_DISEASES`
--
ALTER TABLE `USER_DISEASES`
ADD CONSTRAINT `USER_DISEASES_FK1` FOREIGN KEY (`DISEASE_ID`) REFERENCES `DISEASE_MASTER` (`DISEASE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_DISEASES_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_DISEASES_FK3` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_DISEASES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_DISEASES_MOD_DET`
--
ALTER TABLE `USER_DISEASES_MOD_DET`
ADD CONSTRAINT `USER_DISEASES_MOD_DET_FK1` FOREIGN KEY (`USER_DISEASE_ID`) REFERENCES `USER_DISEASES` (`USER_DISEASE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_DISEASES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_FAVORITE_POSTS`
--
ALTER TABLE `USER_FAVORITE_POSTS`
ADD CONSTRAINT `USER_FAVORITE_POSTS_FK1` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_FAVORITE_POSTS_FK2` FOREIGN KEY (`POST_ID`) REFERENCES `POSTS` (`POST_ID`) ON DELETE CASCADE;

--
-- Constraints for table `USER_FAV_POSTS_MOD_DET`
--
ALTER TABLE `USER_FAV_POSTS_MOD_DET`
ADD CONSTRAINT `USER_FAV_POSTS_MOD_DET_FK1` FOREIGN KEY (`USER_FAVORITE_POST_ID`) REFERENCES `USER_FAVORITE_POSTS` (`USER_FAVORITE_POST_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_FAV_POSTS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_HEALTH_HISTORY_DET`
--
ALTER TABLE `USER_HEALTH_HISTORY_DET`
ADD CONSTRAINT `USER_HEALTH_HISTORY_FK1` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_HEALTH_HISTORY_FK2` FOREIGN KEY (`HEALTH_CONDITION_ID`) REFERENCES `HEALTH_CONDITION_MASTER` (`HEALTH_CONDITION_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_HEALTH_HISTORY_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_HEALTH_HISTORY_FK4` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_HEALTH_HISTORY_MOD_DET`
--
ALTER TABLE `USER_HEALTH_HISTORY_MOD_DET`
ADD CONSTRAINT `HEALTH_HISTORY_MOD_DET_FK1` FOREIGN KEY (`USER_HEALTH_HISTORY_DET_ID`) REFERENCES `USER_HEALTH_HISTORY_DET` (`USER_HEALTH_HISTORY_DET_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `HEALTH_HISTORY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE;

--
-- Constraints for table `USER_HEALTH_READING`
--
ALTER TABLE `USER_HEALTH_READING`
ADD CONSTRAINT `USER_HEALTH_READING_DET_FK1` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_HEALTH_READING_DET_FK2` FOREIGN KEY (`ATTRIBUTE_TYPE_ID`) REFERENCES `ATTRIBUTES_MASTER` (`ATTRIBUTE_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_HEALTH_READING_DET_FK3` FOREIGN KEY (`UNIT_ID`) REFERENCES `UNIT_OF_MEASUREMENT_MASTER` (`UNIT_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_HEALTH_READING_DET_FK4` FOREIGN KEY (`DATE_RECORDED_ON`) REFERENCES `DATES` (`DATE_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_HEALTH_READING_DET_FK5` FOREIGN KEY (`MONTH_RECORDED_ON`) REFERENCES `MONTHS_MASTER` (`MONTH_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_HEALTH_READING_DET_FK6` FOREIGN KEY (`YEAR_RECORDED_ON`) REFERENCES `YEARS_MASTER` (`YEAR_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_HEALTH_READING_DET_FK7` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_HEALTH_READING_DET_FK8` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_HEALTH_READING_MOD_DET`
--
ALTER TABLE `USER_HEALTH_READING_MOD_DET`
ADD CONSTRAINT `USER_HEALTH_READING_MOD_DET_FK1` FOREIGN KEY (`USER_HEALTH_READING_ID`) REFERENCES `USER_HEALTH_READING` (`USER_HEALTH_READING_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_HEALTH_READING_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_MEDIA`
--
ALTER TABLE `USER_MEDIA`
ADD CONSTRAINT `USER_MEDIA_FK1` FOREIGN KEY (`MEDIA_TYPE_ID`) REFERENCES `MEDIA_TYPE_MASTER` (`MEDIA_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_MEDIA_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_MEDIA_FK3` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_MEDIA_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_MEDIA_MOD_DET`
--
ALTER TABLE `USER_MEDIA_MOD_DET`
ADD CONSTRAINT `USER_MEDIA_MOD_DET_FK1` FOREIGN KEY (`USER_MEDIA_ID`) REFERENCES `USER_MEDIA` (`USER_MEDIA_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_MEDIA_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_MESSAGES`
--
ALTER TABLE `USER_MESSAGES`
ADD CONSTRAINT `USER_MESSAGES_FK1` FOREIGN KEY (`SENDER_USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_MESSAGES_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_MESSAGE_RECIPIENTS`
--
ALTER TABLE `USER_MESSAGE_RECIPIENTS`
ADD CONSTRAINT `MESSAGE_RECIPIENTS_FK1` FOREIGN KEY (`MESSAGE_ID`) REFERENCES `USER_MESSAGES` (`MESSAGE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `MESSAGE_RECIPIENTS_FK2` FOREIGN KEY (`RECIPIENT_USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `MESSAGE_RECIPIENTS_FK3` FOREIGN KEY (`RECIPIENT_ROLE_ID`) REFERENCES `MESSAGE_RECIPIENT_ROLES` (`MESSAGE_RECIPIENT_ROLE_ID`) ON DELETE CASCADE;

--
-- Constraints for table `USER_MOOD_HISTORY`
--
ALTER TABLE `USER_MOOD_HISTORY`
ADD CONSTRAINT `USER_MOOD_HISTORY_FK1` FOREIGN KEY (`USER_MOOD_ID`) REFERENCES `USER_MOOD_MASTER` (`USER_MOOD_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_MOOD_HISTORY_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_MOOD_HISTORY_FK3` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_MOOD_HISTORY_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_MOOD_HISTORY_MOD_DET`
--
ALTER TABLE `USER_MOOD_HISTORY_MOD_DET`
ADD CONSTRAINT `USER_MOOD_HISTORY_MOD_DET_FK1` FOREIGN KEY (`USER_MOOD_HISTORY_ID`) REFERENCES `USER_MOOD_HISTORY` (`USER_MOOD_HISTORY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_MOOD_HISTORY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_PAIN_TRACKER`
--
ALTER TABLE `USER_PAIN_TRACKER`
ADD CONSTRAINT `USER_PAIN_TRACKER_FK1` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_PAIN_TRACKER_FK2` FOREIGN KEY (`PAIN_ID`) REFERENCES `PAIN_MASTER` (`PAIN_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_PAIN_TRACKER_FK3` FOREIGN KEY (`PAIN_LEVEL_ID`) REFERENCES `PAIN_LEVELS_MASTER` (`PAIN_LEVEL_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_PAIN_TRACKER_FK4` FOREIGN KEY (`DATE_EXPERIENCED_ON`) REFERENCES `DATES` (`DATE_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_PAIN_TRACKER_FK5` FOREIGN KEY (`MONTH_EXPERIENCED_ON`) REFERENCES `MONTHS_MASTER` (`MONTH_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_PAIN_TRACKER_FK6` FOREIGN KEY (`YEAR_EXPERIENCED_ON`) REFERENCES `YEARS_MASTER` (`YEAR_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_PAIN_TRACKER_FK7` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_PAIN_TRACKER_FK8` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_PAIN_TRACKER_MOD_DET`
--
ALTER TABLE `USER_PAIN_TRACKER_MOD_DET`
ADD CONSTRAINT `USER_PAIN_TRACKER_MOD_DET_FK1` FOREIGN KEY (`USER_PAIN_ID`) REFERENCES `USER_PAIN_TRACKER` (`USER_PAIN_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_PAIN_TRACKER_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_PHOTOS`
--
ALTER TABLE `USER_PHOTOS`
ADD CONSTRAINT `USER_PHOTOS_FK1` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_PHOTOS_FK2` FOREIGN KEY (`PHOTO_TYPE_ID`) REFERENCES `PHOTO_TYPE_MASTER` (`PHOTO_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_PHOTOS_FK3` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_PHOTOS_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_PHOTOS_MOD_DET`
--
ALTER TABLE `USER_PHOTOS_MOD_DET`
ADD CONSTRAINT `USER_PHOTOS_MOD_DET_FK1` FOREIGN KEY (`USER_PHOTO_ID`) REFERENCES `USER_PHOTOS` (`USER_PHOTO_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_PHOTOS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_PRIVACY_MOD_DET`
--
ALTER TABLE `USER_PRIVACY_MOD_DET`
ADD CONSTRAINT `USER_PRIVACY_MOD_DET_FK1` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_PRIVACY_MOD_DET_FK2` FOREIGN KEY (`USER_PRIVACY_ID`) REFERENCES `USER_PRIVACY_SETTINGS` (`USER_PRIVACY_ID`) ON DELETE CASCADE;

--
-- Constraints for table `USER_PRIVACY_SETTINGS`
--
ALTER TABLE `USER_PRIVACY_SETTINGS`
ADD CONSTRAINT `USER_PRIVACY_FK1` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_PRIVACY_FK2` FOREIGN KEY (`USER_TYPE_ID`) REFERENCES `USER_TYPE` (`USER_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_PRIVACY_FK3` FOREIGN KEY (`ACTIVITY_SECTION_ID`) REFERENCES `ACTIVITY_SECTION_MASTER` (`ACTIVITY_SECTION_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_PRIVACY_FK4` FOREIGN KEY (`PRIVACY_ID`) REFERENCES `PRIVACY_MASTER` (`PRIVACY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_PRIVACY_SETTINGS_FK5` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_PRIVACY_SETTINGS_FK6` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_PSSWRD_CHALLENGE_QUES`
--
ALTER TABLE `USER_PSSWRD_CHALLENGE_QUES`
ADD CONSTRAINT `CHALLENGE_QUES_FK1` FOREIGN KEY (`PSSWRD_QUES_ID`) REFERENCES `QUESTION_MASTER` (`QUESTION_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `CHALLENGE_QUES_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`);

--
-- Constraints for table `USER_PSSWRD_CHALLENGE_QUES_MOD_DET`
--
ALTER TABLE `USER_PSSWRD_CHALLENGE_QUES_MOD_DET`
ADD CONSTRAINT `USER_PSSWRD_CHALLENGE_QUES_MOD_DET_FK1` FOREIGN KEY (`USER_PSSWRD_QUES_ID`) REFERENCES `USER_PSSWRD_CHALLENGE_QUES` (`USER_PSSWRD_QUES_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_PSSWRD_CHALLENGE_QUES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_SYMPTOMS`
--
ALTER TABLE `USER_SYMPTOMS`
ADD CONSTRAINT `USER_SYMPTOMS_FK1` FOREIGN KEY (`USER_ID`) REFERENCES `USERS` (`USER_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_SYMPTOMS_FK2` FOREIGN KEY (`SYMPTOM_ID`) REFERENCES `SYMPTOMS_MASTER` (`SYMPTOM_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_SYMPTOMS_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_SYMPTOMS_FK4` FOREIGN KEY (`CREATED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_SYMPTOMS_MOD_DET`
--
ALTER TABLE `USER_SYMPTOMS_MOD_DET`
ADD CONSTRAINT `USER_SYMPTOMS_MOD_DET_FK1` FOREIGN KEY (`USER_SYMPTOM_ID`) REFERENCES `USER_SYMPTOMS` (`USER_SYMPTOM_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_SYMPTOMS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_SYMPTOM_RECORDS`
--
ALTER TABLE `USER_SYMPTOM_RECORDS`
ADD CONSTRAINT `USER_SYMPTOM_RECORDS_FK1` FOREIGN KEY (`UNIT_ID`) REFERENCES `UNIT_OF_MEASUREMENT_MASTER` (`UNIT_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_SYMPTOM_RECORDS_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_SYMPTOM_RECORDS_FK3` FOREIGN KEY (`DATE_RECORDED_ON`) REFERENCES `DATES` (`DATE_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_SYMPTOM_RECORDS_FK4` FOREIGN KEY (`MONTH_RECORDED_ON`) REFERENCES `MONTHS_MASTER` (`MONTH_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_SYMPTOM_RECORDS_FK5` FOREIGN KEY (`YEAR_RECORDED_ON`) REFERENCES `YEARS_MASTER` (`YEAR_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_SYMPTOM_RECORDS_FK6` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_SYMPTOM_RECORDS_MOD_DET`
--
ALTER TABLE `USER_SYMPTOM_RECORDS_MOD_DET`
ADD CONSTRAINT `USER_SYMPTOM_RECORDS_MOD_DET_FK1` FOREIGN KEY (`USER_SYMPTOM_RECORD_ID`) REFERENCES `USER_SYMPTOM_RECORDS` (`USER_SYMPTOM_RECORD_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_SYMPTOM_RECORDS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_TYPE`
--
ALTER TABLE `USER_TYPE`
ADD CONSTRAINT `USER_TYPE_FK1` FOREIGN KEY (`STATUS`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `USER_TYPE_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `USER_TYPE_MOD_DET`
--
ALTER TABLE `USER_TYPE_MOD_DET`
ADD CONSTRAINT `USER_TYPE_MOD_DET_FK1` FOREIGN KEY (`USER_TYPE_ID`) REFERENCES `USER_TYPE` (`USER_TYPE_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `USER_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `WEEK_DAYS_MASTER`
--
ALTER TABLE `WEEK_DAYS_MASTER`
ADD CONSTRAINT `WEEK_DAYS_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `WEEK_DAYS_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `WEEK_DAYS_MOD_DET`
--
ALTER TABLE `WEEK_DAYS_MOD_DET`
ADD CONSTRAINT `WEEK_DAYS_MOD_DET_FK1` FOREIGN KEY (`WEEK_DAY_ID`) REFERENCES `WEEK_DAYS_MASTER` (`WEEK_DAY_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `WEEK_DAYS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `YEARS_MASTER`
--
ALTER TABLE `YEARS_MASTER`
ADD CONSTRAINT `YEARS_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL,
ADD CONSTRAINT `YEARS_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `STATUS` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `YEAR_MOD_DET`
--
ALTER TABLE `YEAR_MOD_DET`
ADD CONSTRAINT `YEAR_MOD_DET_FK1` FOREIGN KEY (`YEAR_ID`) REFERENCES `YEARS_MASTER` (`YEAR_ID`) ON DELETE CASCADE,
ADD CONSTRAINT `YEAR_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `USERS` (`USER_ID`) ON DELETE SET NULL;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
