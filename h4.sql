-- phpMyAdmin SQL Dump
-- version 4.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Oct 04, 2017 at 11:34 AM
-- Server version: 5.6.26
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `health_4_life`
--
CREATE DATABASE IF NOT EXISTS `health_4_life` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `health_4_life`;

-- --------------------------------------------------------

--
-- Table structure for table `abuse_report_mod_det`
--

CREATE TABLE `abuse_report_mod_det` (
  `ABUSE_REPORT_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `ABUSE_REPORT_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of abuse reports';

-- --------------------------------------------------------

--
-- Table structure for table `abuse_report_object_type_master`
--

CREATE TABLE `abuse_report_object_type_master` (
  `ABUSE_REPORT_OBJECT_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `ABUSE_REPORT_OBJECT_TYPE_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Object types for abuse reports';

--
-- Triggers `abuse_report_object_type_master`
--
DELIMITER $$
CREATE TRIGGER `ABUSE_REPORT_OBJECT_TYPE_BU` BEFORE UPDATE ON `abuse_report_object_type_master` FOR EACH ROW BEGIN
	IF EXISTS (SELECT 1 FROM ABUSE_REPORT_OBJECT_TYPE_MASTER WHERE ABUSE_REPORT_OBJECT_TYPE_NAME = NEW.ABUSE_REPORT_OBJECT_TYPE_NAME AND NEW.ABUSE_REPORT_OBJECT_TYPE_NAME <> OLD.ABUSE_REPORT_OBJECT_TYPE_NAME) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Identical abuse object type exists, please revise.';
    END IF;
    IF OLD.ABUSE_REPORT_OBJECT_TYPE_NAME <> NEW.ABUSE_REPORT_OBJECT_TYPE_NAME THEN
        INSERT INTO ABUSE_REPORT_OBJECT_TYPE_MOD_DET 
        SET 
        ABUSE_REPORT_OBJECT_TYPE_ID = OLD.ABUSE_REPORT_OBJECT_TYPE_ID,
        COLUMN_NAME = 'ABUSE_REPORT_OBJECT_TYPE_NAME',
        COLUMN_VALUE = OLD.ABUSE_REPORT_OBJECT_TYPE_NAME,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO ABUSE_REPORT_OBJECT_TYPE_MOD_DET 
        SET 
        ABUSE_REPORT_OBJECT_TYPE_ID = OLD.ABUSE_REPORT_OBJECT_TYPE_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = OLD.STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `abuse_report_object_type_mod_det`
--

CREATE TABLE `abuse_report_object_type_mod_det` (
  `ABUSE_REPORT_OBJECT_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `ABUSE_REPORT_OBJECT_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(11) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

-- --------------------------------------------------------

--
-- Table structure for table `abuse_reports`
--

CREATE TABLE `abuse_reports` (
  `ABUSE_REPORT_ID` int(10) UNSIGNED NOT NULL,
  `OBJECT_ID` int(10) UNSIGNED NOT NULL,
  `OBJECT_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `REPORTED_USER_ID` int(10) UNSIGNED NOT NULL,
  `OBJECT_OWNER_USER_ID` int(10) UNSIGNED NOT NULL,
  `REASON` text COLLATE latin1_general_cs,
  `ADMIN_COMMENT` text COLLATE latin1_general_cs,
  `ACTION_TAKEN_DATE` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Triggers `abuse_reports`
--
DELIMITER $$
CREATE TRIGGER `ABUSE_REPORT_BU` BEFORE UPDATE ON `abuse_reports` FOR EACH ROW BEGIN
    IF (OLD.OBJECT_ID <> NEW.OBJECT_ID) OR (OLD.OBJECT_TYPE_ID <> NEW.OBJECT_TYPE_ID) OR (OLD.REPORTED_USER_ID <> NEW.REPORTED_USER_ID) OR (OLD.OBJECT_OWNER_USER_ID <> NEW.OBJECT_OWNER_USER_ID) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Object or object type or reported user or object owner cannot be altered.';
    END IF;
    IF OLD.REASON <> NEW.REASON THEN
        INSERT INTO ABUSE_REPORT_MOD_DET 
        SET 
        ABUSE_REPORT_ID = OLD.ABUSE_REPORT_ID,
        COLUMN_NAME = 'REASON',
        COLUMN_VALUE = OLD.REASON,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.ADMIN_COMMENT <> NEW.ADMIN_COMMENT THEN
        INSERT INTO ABUSE_REPORT_MOD_DET 
        SET 
        ABUSE_REPORT_ID = OLD.ABUSE_REPORT_ID,
        COLUMN_NAME = 'ADMIN_COMMENT',
        COLUMN_VALUE = OLD.ADMIN_COMMENT,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.ACTION_TAKEN_DATE <> NEW.ACTION_TAKEN_DATE THEN
        INSERT INTO ABUSE_REPORT_MOD_DET 
        SET 
        ABUSE_REPORT_ID = OLD.ABUSE_REPORT_ID,
        COLUMN_NAME = 'ACTION_TAKEN_DATE',
        COLUMN_VALUE = DATE_FORMAT(OLD.ACTION_TAKEN_DATE,'YYYYMMDDHH24MISS'),
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
    IF OLD.STATUS_ID <> NEW.STATUS_ID THEN
        INSERT INTO ABUSE_REPORT_MOD_DET 
        SET 
        ABUSE_REPORT_ID = OLD.ABUSE_REPORT_ID,
        COLUMN_NAME = 'STATUS_ID',
        COLUMN_VALUE = STATUS_ID,
        MODIFIED_BY = NEW.LAST_EDITED_BY;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `action_tokens_master`
--

CREATE TABLE `action_tokens_master` (
  `ACTION_TOKEN_ID` int(10) UNSIGNED NOT NULL,
  `ACTION_TOKEN_DESCR` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for action tokens';

-- --------------------------------------------------------

--
-- Table structure for table `action_tokens_mod_det`
--

CREATE TABLE `action_tokens_mod_det` (
  `ACTION_TOKENS_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `ACTION_TOKEN_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of action tokens';

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `User` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`User`, `user_id`) VALUES
(0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `activity_section_master`
--

CREATE TABLE `activity_section_master` (
  `ACTIVITY_SECTION_ID` int(10) UNSIGNED NOT NULL,
  `ACTIVITY_SECTION_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table of notification activity section';

--
-- Triggers `activity_section_master`
--
DELIMITER $$
CREATE TRIGGER `ACTIVITY_SECTION_BU` BEFORE UPDATE ON `activity_section_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_section_mod_det`
--

CREATE TABLE `activity_section_mod_det` (
  `ACTIVITY_SECTION_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `ACTIVITY_SECTION_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of notification activity section';

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat`
--

CREATE TABLE `arrowchat` (
  `id` int(10) UNSIGNED NOT NULL,
  `from` varchar(25) NOT NULL,
  `to` varchar(25) NOT NULL,
  `message` text NOT NULL,
  `sent` int(10) UNSIGNED NOT NULL,
  `read` int(10) UNSIGNED NOT NULL,
  `user_read` tinyint(1) NOT NULL DEFAULT '0',
  `direction` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_admin`
--

CREATE TABLE `arrowchat_admin` (
  `id` int(3) UNSIGNED NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_applications`
--

CREATE TABLE `arrowchat_applications` (
  `id` int(3) UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `folder` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `icon` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `width` int(4) UNSIGNED NOT NULL,
  `height` int(4) UNSIGNED NOT NULL,
  `bar_width` int(3) UNSIGNED DEFAULT NULL,
  `bar_name` varchar(100) DEFAULT NULL,
  `dont_reload` tinyint(1) UNSIGNED DEFAULT '0',
  `default_bookmark` tinyint(1) UNSIGNED DEFAULT '1',
  `show_to_guests` tinyint(1) UNSIGNED DEFAULT '1',
  `link` varchar(255) DEFAULT NULL,
  `update_link` varchar(255) DEFAULT NULL,
  `version` varchar(20) DEFAULT NULL,
  `active` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_banlist`
--

CREATE TABLE `arrowchat_banlist` (
  `ban_id` int(10) UNSIGNED NOT NULL,
  `ban_userid` varchar(25) DEFAULT NULL,
  `ban_ip` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_chatroom_banlist`
--

CREATE TABLE `arrowchat_chatroom_banlist` (
  `user_id` varchar(25) COLLATE utf8_bin NOT NULL,
  `chatroom_id` int(10) UNSIGNED NOT NULL,
  `ban_length` int(10) UNSIGNED NOT NULL,
  `ban_time` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_chatroom_messages`
--

CREATE TABLE `arrowchat_chatroom_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `chatroom_id` int(10) UNSIGNED NOT NULL,
  `user_id` varchar(25) COLLATE utf8_bin NOT NULL,
  `username` varchar(100) COLLATE utf8_bin NOT NULL,
  `message` text COLLATE utf8_bin NOT NULL,
  `global_message` tinyint(1) UNSIGNED DEFAULT '0',
  `is_mod` tinyint(1) UNSIGNED DEFAULT '0',
  `is_admin` tinyint(1) UNSIGNED DEFAULT '0',
  `sent` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_chatroom_rooms`
--

CREATE TABLE `arrowchat_chatroom_rooms` (
  `id` int(10) UNSIGNED NOT NULL,
  `author_id` varchar(25) COLLATE utf8_bin NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `type` tinyint(1) UNSIGNED NOT NULL,
  `password` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `length` int(10) UNSIGNED NOT NULL,
  `max_users` int(10) NOT NULL DEFAULT '0',
  `session_time` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_chatroom_users`
--

CREATE TABLE `arrowchat_chatroom_users` (
  `user_id` varchar(25) COLLATE utf8_bin NOT NULL,
  `chatroom_id` int(10) UNSIGNED NOT NULL,
  `is_admin` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `is_mod` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `block_chats` tinyint(4) UNSIGNED NOT NULL DEFAULT '0',
  `session_time` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_config`
--

CREATE TABLE `arrowchat_config` (
  `config_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `config_value` text,
  `is_dynamic` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_graph_log`
--

CREATE TABLE `arrowchat_graph_log` (
  `id` int(6) UNSIGNED NOT NULL,
  `date` varchar(30) NOT NULL,
  `user_messages` int(10) UNSIGNED DEFAULT '0',
  `chat_room_messages` int(10) UNSIGNED DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_notifications`
--

CREATE TABLE `arrowchat_notifications` (
  `id` int(25) UNSIGNED NOT NULL,
  `to_id` varchar(25) NOT NULL,
  `author_id` varchar(25) NOT NULL,
  `author_name` varchar(100) NOT NULL,
  `misc1` varchar(255) DEFAULT NULL,
  `misc2` varchar(255) DEFAULT NULL,
  `misc3` varchar(255) DEFAULT NULL,
  `type` int(3) UNSIGNED NOT NULL,
  `alert_read` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `user_read` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `alert_time` int(15) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_notifications_markup`
--

CREATE TABLE `arrowchat_notifications_markup` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` int(3) UNSIGNED NOT NULL,
  `markup` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_smilies`
--

CREATE TABLE `arrowchat_smilies` (
  `id` int(3) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_status`
--

CREATE TABLE `arrowchat_status` (
  `userid` varchar(25) NOT NULL,
  `guest_name` varchar(50) DEFAULT NULL,
  `message` text,
  `status` varchar(10) DEFAULT NULL,
  `theme` int(3) UNSIGNED DEFAULT NULL,
  `popout` int(11) UNSIGNED DEFAULT NULL,
  `typing` text,
  `hide_bar` tinyint(1) UNSIGNED DEFAULT NULL,
  `play_sound` tinyint(1) UNSIGNED DEFAULT '1',
  `window_open` tinyint(1) UNSIGNED DEFAULT NULL,
  `only_names` tinyint(1) UNSIGNED DEFAULT NULL,
  `chatroom_window` varchar(2) NOT NULL DEFAULT '-1',
  `chatroom_stay` varchar(2) NOT NULL DEFAULT '-1',
  `chatroom_block_chats` tinyint(1) UNSIGNED DEFAULT NULL,
  `chatroom_sound` tinyint(1) UNSIGNED DEFAULT NULL,
  `announcement` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `unfocus_chat` text,
  `focus_chat` varchar(50) DEFAULT NULL,
  `last_message` text,
  `clear_chats` text,
  `apps_bookmarks` text,
  `apps_other` text,
  `apps_open` int(10) UNSIGNED DEFAULT NULL,
  `apps_load` text,
  `block_chats` text,
  `session_time` int(20) UNSIGNED NOT NULL DEFAULT '0',
  `is_admin` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `hash_id` varchar(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_themes`
--

CREATE TABLE `arrowchat_themes` (
  `id` int(3) UNSIGNED NOT NULL,
  `folder` varchar(25) NOT NULL,
  `name` varchar(100) NOT NULL,
  `active` tinyint(1) UNSIGNED NOT NULL,
  `update_link` varchar(255) DEFAULT NULL,
  `version` varchar(20) DEFAULT NULL,
  `default` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `arrowchat_trayicons`
--

CREATE TABLE `arrowchat_trayicons` (
  `id` int(3) UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `icon` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `target` varchar(25) DEFAULT NULL,
  `width` int(4) UNSIGNED DEFAULT NULL,
  `height` int(4) UNSIGNED DEFAULT NULL,
  `tray_width` int(3) UNSIGNED DEFAULT NULL,
  `tray_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `tray_location` int(3) UNSIGNED NOT NULL,
  `active` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `attribute_master_mod_det`
--

CREATE TABLE `attribute_master_mod_det` (
  `ATTRIBUTE_MASTER_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of attribute master';

-- --------------------------------------------------------

--
-- Table structure for table `attribute_type_master`
--

CREATE TABLE `attribute_type_master` (
  `ATTRIBUTE_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `ATTRIBUTE_TYPE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for attribute types';

--
-- Triggers `attribute_type_master`
--
DELIMITER $$
CREATE TRIGGER `ATTRIBUTE_TYPE_BU` BEFORE UPDATE ON `attribute_type_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `attribute_type_mod_det`
--

CREATE TABLE `attribute_type_mod_det` (
  `ATTRIBUTE_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `ATTRIBUTE_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of attribute types';

-- --------------------------------------------------------

--
-- Table structure for table `attributes_master`
--

CREATE TABLE `attributes_master` (
  `ATTRIBUTE_ID` int(11) UNSIGNED NOT NULL,
  `ATTRIBUTE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `ATTRIBUTE_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all user attributes';

--
-- Triggers `attributes_master`
--
DELIMITER $$
CREATE TRIGGER `ATTRIBUTE_MASTER_BU` BEFORE UPDATE ON `attributes_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `blocked_user_mod_det`
--

CREATE TABLE `blocked_user_mod_det` (
  `BLOCKED_USER_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `BLOCKED_USER_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for blocked users';

-- --------------------------------------------------------

--
-- Table structure for table `blocked_users`
--

CREATE TABLE `blocked_users` (
  `BLOCKED_USER_ID` int(11) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `BLOCKED_USER` int(10) UNSIGNED NOT NULL,
  `BLOCKED_ON` datetime DEFAULT CURRENT_TIMESTAMP COMMENT AS `Last blocked on`,
  `BLOCKED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last blocked by`,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='List of all blocked users for a particular user';

--
-- Triggers `blocked_users`
--
DELIMITER $$
CREATE TRIGGER `BLOCKED_USERS_BU` BEFORE UPDATE ON `blocked_users` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cake_sessions`
--

CREATE TABLE `cake_sessions` (
  `id` int(11) NOT NULL,
  `date` text NOT NULL,
  `expires` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cake_sessions`
--

INSERT INTO `cake_sessions` (`id`, `date`, `expires`) VALUES
(0, '', '1507136325'),
(72, '', '1507139191');

-- --------------------------------------------------------

--
-- Table structure for table `care_calendar_events`
--

CREATE TABLE `care_calendar_events` (
  `CARE_EVENT_ID` int(10) UNSIGNED NOT NULL,
  `ASSIGNED_TO` int(10) UNSIGNED NOT NULL COMMENT 'Patient/user id',
  `STATUS_ID` int(11) DEFAULT NULL,
  `CARE_EVENT_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `CARE_EVENT_FREQUENCY` float UNSIGNED NOT NULL,
  `ADDITIONAL_NOTES` text COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Care events';

--
-- Triggers `care_calendar_events`
--
DELIMITER $$
CREATE TRIGGER `CARE_EVENTS_BU` BEFORE UPDATE ON `care_calendar_events` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `care_events_mod_det`
--

CREATE TABLE `care_events_mod_det` (
  `CARE_EVENTS_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `CARE_EVENT_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of care calendar events';

-- --------------------------------------------------------

--
-- Table structure for table `care_giver_attribute_mod_det`
--

CREATE TABLE `care_giver_attribute_mod_det` (
  `CARE_GIVER_ATTRIBUTE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `CARE_GIVER_ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of care giver attributes';

-- --------------------------------------------------------

--
-- Table structure for table `care_giver_attributes`
--

CREATE TABLE `care_giver_attributes` (
  `CARE_GIVER_ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL,
  `PATIENT_CARE_GIVER_ID` int(10) UNSIGNED NOT NULL,
  `ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL,
  `ATTRIBUTE_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `EFF_DATE_FROM` datetime DEFAULT CURRENT_TIMESTAMP,
  `EFF_DATE_TO` datetime DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Attributes of patient care givers';

--
-- Triggers `care_giver_attributes`
--
DELIMITER $$
CREATE TRIGGER `CARE_GIVER_ATTRIBUTES_BU` BEFORE UPDATE ON `care_giver_attributes` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `caregiver_relationship_master`
--

CREATE TABLE `caregiver_relationship_master` (
  `RELATIONSHIP_ID` int(10) UNSIGNED NOT NULL,
  `RELATIONSHIP_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for care giver relationships';

--
-- Triggers `caregiver_relationship_master`
--
DELIMITER $$
CREATE TRIGGER `CAREGIVER_RELATIONSHIP_BU` BEFORE UPDATE ON `caregiver_relationship_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `caregiver_relationship_mod_det`
--

CREATE TABLE `caregiver_relationship_mod_det` (
  `CAREGIVER_RELATIONSHIP_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `RELATIONSHIP_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of caregiver relationships';

-- --------------------------------------------------------

--
-- Table structure for table `cities_master`
--

CREATE TABLE `cities_master` (
  `CITY_ID` int(11) NOT NULL,
  `DESCRIPTION` varchar(250) COLLATE latin1_general_cs NOT NULL,
  `SHORT_DESCRIPTION` varchar(15) COLLATE latin1_general_cs DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `STATE_ID` int(11) DEFAULT NULL,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for states';

--
-- Triggers `cities_master`
--
DELIMITER $$
CREATE TRIGGER `CITIES_BU` BEFORE UPDATE ON `cities_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cities_mod_det`
--

CREATE TABLE `cities_mod_det` (
  `CITIES_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `CITY_ID` int(10) NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(250) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of cities';

-- --------------------------------------------------------

--
-- Table structure for table `communities`
--

CREATE TABLE `communities` (
  `COMMUNITY_ID` int(10) UNSIGNED NOT NULL,
  `COMMUNITY_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COMMUNITY_DESCR` varchar(500) COLLATE latin1_general_cs NOT NULL,
  `COMMUNITY_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MEMBER_CAN_INVITE` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' AS `COMMENT`
) ;

--
-- Triggers `communities`
--
DELIMITER $$
CREATE TRIGGER `COMMUNITY_BU` BEFORE UPDATE ON `communities` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `community_attributes`
--

CREATE TABLE `community_attributes` (
  `COMMUNITY_ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL,
  `COMMUNITY_ID` int(10) UNSIGNED NOT NULL,
  `ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL,
  `ATTRIBUTE_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `EFF_DATE_FROM` datetime DEFAULT NULL,
  `EFF_DATE_TO` datetime DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Attribute values for the community';

--
-- Triggers `community_attributes`
--
DELIMITER $$
CREATE TRIGGER `COMMUNITY_ATTRIBUTES_BU` BEFORE UPDATE ON `community_attributes` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `community_attributes_mod_det`
--

CREATE TABLE `community_attributes_mod_det` (
  `COMMUNITY_ATTRIBUTE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `COMMUNITY_ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of community attributes';

-- --------------------------------------------------------

--
-- Table structure for table `community_diseases`
--

CREATE TABLE `community_diseases` (
  `COMMUNITY_DISEASE_ID` int(10) UNSIGNED NOT NULL,
  `COMMUNITY_ID` int(10) UNSIGNED NOT NULL,
  `DISEASE_ID` int(10) UNSIGNED NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Community diseases';

--
-- Triggers `community_diseases`
--
DELIMITER $$
CREATE TRIGGER `COMMUNITY_DISEASES_BU` BEFORE UPDATE ON `community_diseases` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `community_diseases_mod_det`
--

CREATE TABLE `community_diseases_mod_det` (
  `COMMUNITY_DISEASE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `COMMUNITY_DISEASE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of community diseases';

-- --------------------------------------------------------

--
-- Table structure for table `community_members`
--

CREATE TABLE `community_members` (
  `COMMUNITY_MEMBER_ID` int(10) UNSIGNED NOT NULL,
  `COMMUNITY_ID` int(10) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `USER_TYPE_ID` int(10) NOT NULL,
  `INVITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `INVITED_ON` datetime DEFAULT CURRENT_TIMESTAMP COMMENT AS `Invitation or request date`,
  `JOINED_ON` datetime DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Community members';

--
-- Triggers `community_members`
--
DELIMITER $$
CREATE TRIGGER `COMMUNITY_MEMBERS_BU` BEFORE UPDATE ON `community_members` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `community_members_mod_det`
--

CREATE TABLE `community_members_mod_det` (
  `COMMUNITY_MEMBER_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `COMMUNITY_MEMBER_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of community members';

-- --------------------------------------------------------

--
-- Table structure for table `community_mod_det`
--

CREATE TABLE `community_mod_det` (
  `COMMUNITY_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `COMMUNITY_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of communities';

-- --------------------------------------------------------

--
-- Table structure for table `community_photos`
--

CREATE TABLE `community_photos` (
  `COMMUNITY_PHOTO_ID` int(10) UNSIGNED NOT NULL,
  `COMMUNITY_ID` int(10) UNSIGNED NOT NULL,
  `FILE_NAME` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `PHOTO_TYPE_ID` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Community photos';

--
-- Triggers `community_photos`
--
DELIMITER $$
CREATE TRIGGER `COMMUNITY_PHOTOS_BU` BEFORE UPDATE ON `community_photos` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `community_photos_mod_det`
--

CREATE TABLE `community_photos_mod_det` (
  `COMMUNITY_PHOTO_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `COMMUNITY_PHOTO_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of community photos';

-- --------------------------------------------------------

--
-- Table structure for table `community_type_master`
--

CREATE TABLE `community_type_master` (
  `COMMUNITY_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `COMMUNITY_TYPE_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for community types';

--
-- Triggers `community_type_master`
--
DELIMITER $$
CREATE TRIGGER `COMMUNITY_TYPE_BU` BEFORE UPDATE ON `community_type_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `community_type_mod_det`
--

CREATE TABLE `community_type_mod_det` (
  `COMMUNITY_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `COMMUNITY_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of community types';

-- --------------------------------------------------------

--
-- Table structure for table `configurations`
--

CREATE TABLE `configurations` (
  `CONFIGURATION_ID` int(10) UNSIGNED NOT NULL,
  `CONFIGURATION_NAME` varchar(300) COLLATE latin1_general_cs NOT NULL,
  `CONFIGURATION_VALUE` text COLLATE latin1_general_cs NOT NULL,
  `CONFIGURATION_LABEL` varchar(300) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Configuration items';

--
-- Triggers `configurations`
--
DELIMITER $$
CREATE TRIGGER `CONFIGURATIONS_BU` BEFORE UPDATE ON `configurations` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `configurations_mod_det`
--

CREATE TABLE `configurations_mod_det` (
  `CONFIGURATION_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `CONFIGURATION_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of configuration items';

-- --------------------------------------------------------

--
-- Table structure for table `country_master`
--

CREATE TABLE `country_master` (
  `COUNTRY_ID` int(11) NOT NULL,
  `ISO2` char(2) COLLATE latin1_general_cs DEFAULT NULL,
  `SHORT_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LONG_NAME` varchar(250) COLLATE latin1_general_cs DEFAULT NULL,
  `ISO3` char(3) COLLATE latin1_general_cs DEFAULT NULL,
  `NUMCODE` varchar(6) COLLATE latin1_general_cs DEFAULT NULL,
  `UN_MEMBER` tinyint(3) UNSIGNED DEFAULT '0',
  `CALLING_CODE` varchar(8) COLLATE latin1_general_cs DEFAULT NULL,
  `CCTLD` varchar(5) COLLATE latin1_general_cs DEFAULT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all countries';

--
-- Triggers `country_master`
--
DELIMITER $$
CREATE TRIGGER `COUNTRY_BU` BEFORE UPDATE ON `country_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `country_mod_det`
--

CREATE TABLE `country_mod_det` (
  `COUNTRY_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `COUNTRY_ID` int(11) NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(250) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of countries';

-- --------------------------------------------------------

--
-- Table structure for table `cron_task_exec_log`
--

CREATE TABLE `cron_task_exec_log` (
  `CRON_TASK_EXEC_LOG_ID` int(10) UNSIGNED NOT NULL,
  `TASK_ID` int(10) UNSIGNED NOT NULL,
  `START_TIME` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FINISH_TIME` datetime NOT NULL,
  `MESSAGE_DETAILS` text COLLATE latin1_general_cs,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Cron job execution log';

--
-- Triggers `cron_task_exec_log`
--
DELIMITER $$
CREATE TRIGGER `CRON_TASK_EXEC_LOG_BU` BEFORE UPDATE ON `cron_task_exec_log` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cron_task_exec_log_mod_det`
--

CREATE TABLE `cron_task_exec_log_mod_det` (
  `CRON_TASK_EXEC_LOG_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `CRON_TASK_EXEC_LOG_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of cron job execution log';

-- --------------------------------------------------------

--
-- Table structure for table `cron_tasks`
--

CREATE TABLE `cron_tasks` (
  `TASK_ID` int(10) UNSIGNED NOT NULL,
  `TASK_TYPE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `TASK_TITLE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `TASK_PARAMS` text COLLATE latin1_general_cs,
  `TASK_NAME` varchar(300) COLLATE latin1_general_cs DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL,
  `WORKER_KEY` varchar(100) COLLATE latin1_general_cs DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL,
  `EFF_DATE_FROM` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `EFF_DATE_TO` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Cron jobs';

--
-- Triggers `cron_tasks`
--
DELIMITER $$
CREATE TRIGGER `CRON_TASKS_BU` BEFORE UPDATE ON `cron_tasks` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cron_tasks_mod_det`
--

CREATE TABLE `cron_tasks_mod_det` (
  `CRON_TASK_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `TASK_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details for cron taks';

-- --------------------------------------------------------

--
-- Table structure for table `dates`
--

CREATE TABLE `dates` (
  `DATE_ID` int(10) UNSIGNED NOT NULL,
  `DATE_VALUE` varchar(5) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='List of all possible date values';

--
-- Triggers `dates`
--
DELIMITER $$
CREATE TRIGGER `CALENDAR_DATE_BU` BEFORE UPDATE ON `dates` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `dates_mod_det`
--

CREATE TABLE `dates_mod_det` (
  `DATES_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `DATE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of calendar dates';

-- --------------------------------------------------------

--
-- Table structure for table `disease_master`
--

CREATE TABLE `disease_master` (
  `DISEASE_ID` int(10) UNSIGNED NOT NULL,
  `DISEASE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `PARENT_DISEASE_ID` int(10) UNSIGNED DEFAULT NULL,
  `DISEASE_DESCR` text COLLATE latin1_general_cs,
  `DISEASE_LIBRARY` text COLLATE latin1_general_cs,
  `DISEASE_DASHBOARD_DATA` text COLLATE latin1_general_cs,
  `STATUS_ID` int(11) DEFAULT NULL,
  `DISEASE_SURVEY_ID` int(10) UNSIGNED DEFAULT NULL,
  `FOLLOWER_COUNT` int(10) UNSIGNED DEFAULT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last edited on'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for diseases';

--
-- Triggers `disease_master`
--
DELIMITER $$
CREATE TRIGGER `DISEASE_BU` BEFORE UPDATE ON `disease_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `disease_mod_det`
--

CREATE TABLE `disease_mod_det` (
  `DISEASE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `DISEASE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of diseases';

-- --------------------------------------------------------

--
-- Table structure for table `disease_symptoms`
--

CREATE TABLE `disease_symptoms` (
  `DISEASE_SYMPTOM_ID` int(10) UNSIGNED NOT NULL,
  `DISEASE_ID` int(10) UNSIGNED NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last edited on',
  `STATUS_ID` int(11) DEFAULT NULL,
  `SYMPTOM_ID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all disease symptoms';

--
-- Triggers `disease_symptoms`
--
DELIMITER $$
CREATE TRIGGER `DISEASE_SYMPTOMS_BU` BEFORE UPDATE ON `disease_symptoms` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `disease_symptoms_mod_det`
--

CREATE TABLE `disease_symptoms_mod_det` (
  `DISEASE_SYMPTOM_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `DISEASE_SYMPTOM_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for disease symptoms';

-- --------------------------------------------------------

--
-- Table structure for table `disease_type_master`
--

CREATE TABLE `disease_type_master` (
  `DISEASE_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `DISEASE_TYPE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for disease types';

--
-- Triggers `disease_type_master`
--
DELIMITER $$
CREATE TRIGGER `DISEASE_TYPE_BU` BEFORE UPDATE ON `disease_type_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `disease_type_mod_det`
--

CREATE TABLE `disease_type_mod_det` (
  `DISEASE_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `DISEASE_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of diseases';

-- --------------------------------------------------------

--
-- Table structure for table `email_attributes`
--

CREATE TABLE `email_attributes` (
  `EMAIL_ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL,
  `EMAIL_ID` int(10) UNSIGNED NOT NULL,
  `ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL,
  `ATTRIBUTE_VALUE` varchar(300) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Email attributes';

--
-- Triggers `email_attributes`
--
DELIMITER $$
CREATE TRIGGER `EMAIL_ATTRIBUTES_BU` BEFORE UPDATE ON `email_attributes` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `email_attributes_mod_det`
--

CREATE TABLE `email_attributes_mod_det` (
  `EMAIL_ATTRIBUTES_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `EMAIL_ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(300) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of email attributes';

-- --------------------------------------------------------

--
-- Table structure for table `email_history`
--

CREATE TABLE `email_history` (
  `EMAIL_HISTORY_ID` int(10) UNSIGNED NOT NULL,
  `EMAIL_TEMPLATE_ID` int(10) UNSIGNED DEFAULT NULL,
  `INSTANCE_ID` varchar(300) COLLATE latin1_general_cs NOT NULL,
  `CONTENT` text COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `SENT_DATE` datetime NOT NULL,
  `MODULE_INFO` varchar(200) COLLATE latin1_general_cs DEFAULT NULL,
  `PRIORITY_ID` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Triggers `email_history`
--
DELIMITER $$
CREATE TRIGGER `EMAIL_HISTORY_BU` BEFORE UPDATE ON `email_history` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `email_history_attributes`
--

CREATE TABLE `email_history_attributes` (
  `EMAIL_HISTORY_ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL,
  `EMAIL_HISTORY_ID` int(10) UNSIGNED NOT NULL,
  `ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL,
  `ATTRIBUTE_VALUE` varchar(300) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Triggers `email_history_attributes`
--
DELIMITER $$
CREATE TRIGGER `EMAIL_HISTORY_ATTRIBUTES_BU` BEFORE UPDATE ON `email_history_attributes` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `email_history_attributes_mod_det`
--

CREATE TABLE `email_history_attributes_mod_det` (
  `EMAIL_HISTORY_ATTRIBUTES_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `EMAIL_HISTORY_ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(300) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

-- --------------------------------------------------------

--
-- Table structure for table `email_history_mod_det`
--

CREATE TABLE `email_history_mod_det` (
  `EMAIL_HISTORY_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `EMAIL_HISTORY_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

-- --------------------------------------------------------

--
-- Table structure for table `email_mod_det`
--

CREATE TABLE `email_mod_det` (
  `EMAIL_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `EMAIL_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Email modification history';

-- --------------------------------------------------------

--
-- Table structure for table `email_priority_master`
--

CREATE TABLE `email_priority_master` (
  `EMAIL_PRIORITY_ID` int(10) UNSIGNED NOT NULL,
  `EMAIL_PRIORITY_DESCR` varchar(50) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for email priority';

--
-- Triggers `email_priority_master`
--
DELIMITER $$
CREATE TRIGGER `EMAIL_PRIORITY_BU` BEFORE UPDATE ON `email_priority_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `email_priority_mod_det`
--

CREATE TABLE `email_priority_mod_det` (
  `EMAIL_PRIORITY_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `EMAIL_PRIORITY_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(50) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of email priority';

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `TEMPLATE_ID` int(10) UNSIGNED NOT NULL,
  `TEMPLATE_NAME` varchar(350) COLLATE latin1_general_cs NOT NULL,
  `TEMPLATE_SUBJECT` varchar(500) COLLATE latin1_general_cs DEFAULT NULL,
  `TEMPLATE_BODY` text COLLATE latin1_general_cs,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Email templates';

--
-- Triggers `email_templates`
--
DELIMITER $$
CREATE TRIGGER `EMAIL_TEMPLATES_BU` BEFORE UPDATE ON `email_templates` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates_mod_det`
--

CREATE TABLE `email_templates_mod_det` (
  `EMAIL_TEMPLATE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `TEMPLATE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Email template modification history';

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `EMAIL_ID` int(10) UNSIGNED NOT NULL,
  `EMAIL_TEMPLATE_ID` int(10) UNSIGNED DEFAULT NULL,
  `INSTANCE_ID` varchar(300) COLLATE latin1_general_cs NOT NULL,
  `CONTENT` text COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `SENT_DATE` datetime NOT NULL,
  `MODULE_INFO` varchar(200) COLLATE latin1_general_cs DEFAULT NULL,
  `PRIORITY_ID` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Emails';

--
-- Triggers `emails`
--
DELIMITER $$
CREATE TRIGGER `EMAIL_BU` BEFORE UPDATE ON `emails` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `event_attributes`
--

CREATE TABLE `event_attributes` (
  `EVENT_ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL,
  `EVENT_ID` int(10) UNSIGNED NOT NULL,
  `ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL,
  `ATTRIBUTE_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL,
  `EFF_DATE_FROM` datetime DEFAULT CURRENT_TIMESTAMP,
  `EFF_DATE_TO` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Event attributes';

--
-- Triggers `event_attributes`
--
DELIMITER $$
CREATE TRIGGER `EVENT_ATTRIBUTES_BU` BEFORE UPDATE ON `event_attributes` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `event_attributes_mod_det`
--

CREATE TABLE `event_attributes_mod_det` (
  `EVENT_ATTRIBUTE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `EVENT_ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of event attributes';

-- --------------------------------------------------------

--
-- Table structure for table `event_diseases`
--

CREATE TABLE `event_diseases` (
  `EVENT_DISEASE_ID` int(10) UNSIGNED NOT NULL,
  `EVENT_ID` int(10) UNSIGNED NOT NULL,
  `DISEASE_ID` int(10) UNSIGNED NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Event diseases';

--
-- Triggers `event_diseases`
--
DELIMITER $$
CREATE TRIGGER `EVENT_DISEASES_BU` BEFORE UPDATE ON `event_diseases` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `event_diseases_mod_det`
--

CREATE TABLE `event_diseases_mod_det` (
  `EVENT_DISEASE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `EVENT_DISEASE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT NULL,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of event diseases';

-- --------------------------------------------------------

--
-- Table structure for table `event_members`
--

CREATE TABLE `event_members` (
  `EVENT_MEMBER_ID` int(10) UNSIGNED NOT NULL,
  `EVENT_ID` int(10) UNSIGNED NOT NULL,
  `MEMBER_ID` int(10) UNSIGNED NOT NULL,
  `MEMBER_ROLE_ID` int(11) NOT NULL,
  `INVITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL,
  `CREATED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Event members';

--
-- Triggers `event_members`
--
DELIMITER $$
CREATE TRIGGER `EVENT_MEMBERS_BU` BEFORE UPDATE ON `event_members` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `event_members_mod_det`
--

CREATE TABLE `event_members_mod_det` (
  `EVENT_MEMBER_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `EVENT_MEMBER_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of event members';

-- --------------------------------------------------------

--
-- Table structure for table `event_mod_det`
--

CREATE TABLE `event_mod_det` (
  `EVENT_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `EVENT_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of events';

-- --------------------------------------------------------

--
-- Table structure for table `event_type_master`
--

CREATE TABLE `event_type_master` (
  `EVENT_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `EVENT_TYPE_DESCR` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for event types';

--
-- Triggers `event_type_master`
--
DELIMITER $$
CREATE TRIGGER `EVENT_TYPE_BU` BEFORE UPDATE ON `event_type_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `event_type_mod_det`
--

CREATE TABLE `event_type_mod_det` (
  `EVENT_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `EVENT_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of event types';

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `EVENT_ID` int(10) UNSIGNED NOT NULL,
  `EVENT_NAME` varchar(250) COLLATE latin1_general_cs NOT NULL,
  `EVENT_DESCR` text COLLATE latin1_general_cs NOT NULL,
  `EVENT_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `COMMUNITY_ID` int(10) UNSIGNED DEFAULT NULL,
  `GUEST_CAN_INVITE` tinyint(3) UNSIGNED NOT NULL,
  `REPEAT_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `START_DATE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `END_DATE` datetime DEFAULT CURRENT_TIMESTAMP,
  `VIRTUAL_EVENT` tinyint(3) UNSIGNED NOT NULL,
  `ONLINE_EVENT_DETAILS` varchar(500) COLLATE latin1_general_cs NOT NULL,
  `PUBLISH_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `SECTION_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `SECTION_TEAM_ID` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Section team id`,
  `SECTION_COMMUNITY_ID` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Section community id`,
  `REPEAT_MODE_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `REPEAT_INTERVAL` tinyint(3) UNSIGNED NOT NULL COMMENT '1 to 30',
  `REPEAT_BY_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `REPEAT_END_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `REPEAT_OCCURENCES` int(10) UNSIGNED NOT NULL,
  `INVITED_COUNT` int(10) UNSIGNED NOT NULL,
  `ATTENDING_COUNT` int(10) UNSIGNED NOT NULL,
  `MAYBE_COUNT` int(10) UNSIGNED NOT NULL,
  `NOT_ATTENDING_COUNT` int(10) UNSIGNED NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `IS_SLIDESHOW_ENABLED` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Triggers `events`
--
DELIMITER $$
CREATE TRIGGER `EVENT_BU` BEFORE UPDATE ON `events` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `following_pages`
--

CREATE TABLE `following_pages` (
  `FOLLOWING_PAGE_ID` int(10) UNSIGNED NOT NULL,
  `PAGE_ID` int(10) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `NOTIFICATION_REQUESTED` int(10) UNSIGNED DEFAULT '1' AS `COMMENT`
) ;

--
-- Triggers `following_pages`
--
DELIMITER $$
CREATE TRIGGER `FOLLOWING_PAGES_BU` BEFORE UPDATE ON `following_pages` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `following_pages_mod_det`
--

CREATE TABLE `following_pages_mod_det` (
  `FOLLOWING_PAGE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `FOLLOWING_PAGE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of pages followed by users';

-- --------------------------------------------------------

--
-- Table structure for table `health_cond_group_mod_det`
--

CREATE TABLE `health_cond_group_mod_det` (
  `HEALTH_COND_GROUP_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `HEALTH_CONDITION_GROUP_ID` int(10) NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details for health condition groups';

-- --------------------------------------------------------

--
-- Table structure for table `health_condition_groups`
--

CREATE TABLE `health_condition_groups` (
  `HEALTH_CONDITION_GROUP_ID` int(11) NOT NULL,
  `HEALTH_CONDITION_GROUP_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='User health condition groups';

--
-- Triggers `health_condition_groups`
--
DELIMITER $$
CREATE TRIGGER `HEALTH_COND_GROUP_BU` BEFORE UPDATE ON `health_condition_groups` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `health_condition_master`
--

CREATE TABLE `health_condition_master` (
  `HEALTH_CONDITION_ID` int(11) NOT NULL,
  `HEALTH_CONDITION_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `HEALTH_CONDITION_GROUP_ID` int(11) NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for health conditions';

--
-- Triggers `health_condition_master`
--
DELIMITER $$
CREATE TRIGGER `HEALTH_CONDITION_BU` BEFORE UPDATE ON `health_condition_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `health_condition_mod_det`
--

CREATE TABLE `health_condition_mod_det` (
  `HEALTH_CONDITION_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `HEALTH_CONDITION_ID` int(10) NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of health condition';

-- --------------------------------------------------------

--
-- Table structure for table `invited_users`
--

CREATE TABLE `invited_users` (
  `INVITED_USER_ID` int(10) UNSIGNED NOT NULL,
  `INVITED_USER_EMAIL` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `INVITED_BY` int(10) UNSIGNED NOT NULL,
  `INVITED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT AS `Last invited on`,
  `JOINED_ON` datetime DEFAULT NULL,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all user invitations';

--
-- Triggers `invited_users`
--
DELIMITER $$
CREATE TRIGGER `INVITED_USERS_BU` BEFORE UPDATE ON `invited_users` FOR EACH ROW BEGIN
IF (OLD.INVITED_USER_EMAIL <> NEW.INVITED_USER_EMAIL) OR (OLD.INVITED_BY <> NEW.INVITED_BY) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Invitee email or senders id cannot be altered.';
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `invited_users_mod_det`
--

CREATE TABLE `invited_users_mod_det` (
  `INVITED_USERS_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `INVITED_USER_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for invited users';

-- --------------------------------------------------------

--
-- Table structure for table `language_mod_det`
--

CREATE TABLE `language_mod_det` (
  `LANGUAGE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `LANGUAGE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of languages';

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `LANGUAGE_ID` int(10) UNSIGNED NOT NULL,
  `LANGUAGE_ABBREV` varchar(10) COLLATE latin1_general_cs DEFAULT NULL,
  `LANGUAGE` varchar(100) COLLATE latin1_general_cs DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Triggers `languages`
--
DELIMITER $$
CREATE TRIGGER `LANGUAGE_BU` BEFORE UPDATE ON `languages` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `media_type_master`
--

CREATE TABLE `media_type_master` (
  `MEDIA_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MEDIA_TYPE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all media types';

--
-- Triggers `media_type_master`
--
DELIMITER $$
CREATE TRIGGER `MEDIA_TYPE_BU` BEFORE UPDATE ON `media_type_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `media_type_mod_det`
--

CREATE TABLE `media_type_mod_det` (
  `MEDIA_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `MEDIA_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of media type';

-- --------------------------------------------------------

--
-- Table structure for table `message_recipient_roles`
--

CREATE TABLE `message_recipient_roles` (
  `MESSAGE_RECIPIENT_ROLE_ID` int(10) UNSIGNED NOT NULL,
  `ROLE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='List of message recipient roles - To, CC, BCC';

--
-- Triggers `message_recipient_roles`
--
DELIMITER $$
CREATE TRIGGER `MESSAGE_ROLE_BU` BEFORE UPDATE ON `message_recipient_roles` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `message_role_mod_det`
--

CREATE TABLE `message_role_mod_det` (
  `MESSAGE_ROLE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `MESSAGE_RECIPIENT_ROLE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details for message recipient roles';

-- --------------------------------------------------------

--
-- Table structure for table `module_master`
--

CREATE TABLE `module_master` (
  `MODULE_ID` int(11) UNSIGNED NOT NULL,
  `MODULE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all Health4Life user modules';

--
-- Triggers `module_master`
--
DELIMITER $$
CREATE TRIGGER `MODULE_BU` BEFORE UPDATE ON `module_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `module_mod_det`
--

CREATE TABLE `module_mod_det` (
  `MODULE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `MODULE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of module details';

-- --------------------------------------------------------

--
-- Table structure for table `month_mod_det`
--

CREATE TABLE `month_mod_det` (
  `MONTH_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `MONTH_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of months table';

-- --------------------------------------------------------

--
-- Table structure for table `months_master`
--

CREATE TABLE `months_master` (
  `MONTH_ID` int(10) UNSIGNED NOT NULL,
  `MONTH_NAME` varchar(20) COLLATE latin1_general_cs NOT NULL,
  `MONTH_ABBREV` varchar(5) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for months';

--
-- Triggers `months_master`
--
DELIMITER $$
CREATE TRIGGER `MONTH_BU` BEFORE UPDATE ON `months_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `mood_master`
--

CREATE TABLE `mood_master` (
  `USER_MOOD_ID` int(11) NOT NULL,
  `USER_MOOD_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all user moods';

--
-- Triggers `mood_master`
--
DELIMITER $$
CREATE TRIGGER `MOOD_BU` BEFORE UPDATE ON `mood_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `mood_mod_det`
--

CREATE TABLE `mood_mod_det` (
  `MOOD_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `MOOD_ID` int(11) NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of mood master';

-- --------------------------------------------------------

--
-- Table structure for table `my_friend_mod_det`
--

CREATE TABLE `my_friend_mod_det` (
  `MY_FRIEND_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `MY_FRIEND_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of my-friends table';

-- --------------------------------------------------------

--
-- Table structure for table `my_friends`
--

CREATE TABLE `my_friends` (
  `MY_FRIEND_ID` int(10) UNSIGNED NOT NULL,
  `MY_USER_ID` int(10) UNSIGNED NOT NULL,
  `PENDING_REQUEST_COUNT` int(10) UNSIGNED NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='My friends and pending friend requests';

--
-- Triggers `my_friends`
--
DELIMITER $$
CREATE TRIGGER `MY_FRIENDS_BU` BEFORE UPDATE ON `my_friends` FOR EACH ROW BEGIN
    IF (OLD.MY_USER_ID <> NEW.MY_USER_ID) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Owners user id cannot be altered.';
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `my_friends_detail_mod_det`
--

CREATE TABLE `my_friends_detail_mod_det` (
  `MY_FRIENDS_DETAIL_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `MY_FRIENDS_DETAIL_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of my friends user id';

-- --------------------------------------------------------

--
-- Table structure for table `my_friends_details`
--

CREATE TABLE `my_friends_details` (
  `MY_FRIENDS_DETAIL_ID` int(10) UNSIGNED NOT NULL,
  `MY_FRIEND_ID` int(10) UNSIGNED NOT NULL,
  `FRIEND_USER_ID` int(10) UNSIGNED NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Details of my friends user id';

--
-- Triggers `my_friends_details`
--
DELIMITER $$
CREATE TRIGGER `MY_FRIENDS_DETAIL_BU` BEFORE UPDATE ON `my_friends_details` FOR EACH ROW BEGIN
    IF (OLD.MY_FRIEND_ID <> NEW.MY_FRIEND_ID) OR (OLD.FRIEND_USER_ID <> NEW.FRIEND_USER_ID) THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Record identifier or friends user id cannot be altered (referencing columns).';
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_mod_det`
--

CREATE TABLE `newsletter_mod_det` (
  `NEWSLETTER_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `NEWSLETTER_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of newsletter details';

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_queue_mod_det`
--

CREATE TABLE `newsletter_queue_mod_det` (
  `NEWSLETTER_QUEUE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `NEWSLETTER_QUEUE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(300) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of newsletter queue';

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_queue_status`
--

CREATE TABLE `newsletter_queue_status` (
  `NEWSLETTER_QUEUE_ID` int(10) UNSIGNED NOT NULL,
  `INSTANCE_ID` varchar(300) COLLATE latin1_general_cs NOT NULL,
  `NEWSLETTER_ID` int(10) UNSIGNED NOT NULL,
  `SUBJECT` varchar(300) COLLATE latin1_general_cs NOT NULL,
  `TOTAL_COUNT` int(10) UNSIGNED NOT NULL,
  `SENT_COUNT` int(10) UNSIGNED NOT NULL,
  `FAIL_COUNT` int(10) UNSIGNED NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Newsletter queue';

--
-- Triggers `newsletter_queue_status`
--
DELIMITER $$
CREATE TRIGGER `NEWSLETTER_QUEUE_BU` BEFORE UPDATE ON `newsletter_queue_status` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_template_mod_det`
--

CREATE TABLE `newsletter_template_mod_det` (
  `NEWSLETTER_TEMPLATE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `NEWSLETTER_TEMPLATE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of newsletter templates';

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_templates`
--

CREATE TABLE `newsletter_templates` (
  `NEWSLETTER_TEMPLATE_ID` int(10) UNSIGNED NOT NULL,
  `TEMPLATE_NAME` varchar(350) COLLATE latin1_general_cs NOT NULL,
  `TEMPLATE_BODY` text COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Newsletter templates';

--
-- Triggers `newsletter_templates`
--
DELIMITER $$
CREATE TRIGGER `NEWSLETTER_TEMPLATES_BU` BEFORE UPDATE ON `newsletter_templates` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

CREATE TABLE `newsletters` (
  `NEWSLETTER_ID` int(10) UNSIGNED NOT NULL,
  `SUBJECT` varchar(300) COLLATE latin1_general_cs NOT NULL,
  `CONTENT` text COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Newsletter details';

--
-- Triggers `newsletters`
--
DELIMITER $$
CREATE TRIGGER `NEWSLETTERS_BU` BEFORE UPDATE ON `newsletters` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notification_activity_mod_det`
--

CREATE TABLE `notification_activity_mod_det` (
  `NOTIFICATION_ACTIVITY_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `NOTIFICATION_ACTIVITY_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of notification activity types';

-- --------------------------------------------------------

--
-- Table structure for table `notification_activity_type_master`
--

CREATE TABLE `notification_activity_type_master` (
  `NOTIFICATION_ACTIVITY_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `NOTIFICATION_ACTIVITY_TYPE_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for notification activity types';

--
-- Triggers `notification_activity_type_master`
--
DELIMITER $$
CREATE TRIGGER `NOTIFICATION_ACTIVITY_TYPE_BU` BEFORE UPDATE ON `notification_activity_type_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notification_frequency_master`
--

CREATE TABLE `notification_frequency_master` (
  `NOTIFICATION_FREQUENCY_ID` int(10) UNSIGNED NOT NULL,
  `NOTIFICATION_FREQUENCY_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for notification frequency';

--
-- Triggers `notification_frequency_master`
--
DELIMITER $$
CREATE TRIGGER `NOTIFICATION_FREQUENCY_BU` BEFORE UPDATE ON `notification_frequency_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notification_frequency_mod_det`
--

CREATE TABLE `notification_frequency_mod_det` (
  `NOTIFICATION_FREQUENCY_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `NOTIFICATION_FREQUENCY_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of notification frequency';

-- --------------------------------------------------------

--
-- Table structure for table `notification_mod_det`
--

CREATE TABLE `notification_mod_det` (
  `NOTIFICATION_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `NOTIFICATION_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of notifications';

-- --------------------------------------------------------

--
-- Table structure for table `notification_object_type_master`
--

CREATE TABLE `notification_object_type_master` (
  `NOTIFICATION_OBJECT_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `NOTIFICATION_OBJECT_TYPE_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for notification object types';

--
-- Triggers `notification_object_type_master`
--
DELIMITER $$
CREATE TRIGGER `NOTIFICATION_OBJECT_TYPE_BU` BEFORE UPDATE ON `notification_object_type_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notification_object_type_mod_det`
--

CREATE TABLE `notification_object_type_mod_det` (
  `NOTIFICATION_OBJECT_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `NOTIFICATION_OBJECT_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of notification object types';

-- --------------------------------------------------------

--
-- Table structure for table `notification_recipient_mod_det`
--

CREATE TABLE `notification_recipient_mod_det` (
  `NOTIFICATION_RECIPIENT_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `NOTIFICATION_RECIPIENT_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of notification recipients';

-- --------------------------------------------------------

--
-- Table structure for table `notification_recipients`
--

CREATE TABLE `notification_recipients` (
  `NOTIFICATION_RECIPIENT_ID` int(10) UNSIGNED NOT NULL,
  `NOTIFICATION_ID` int(10) UNSIGNED NOT NULL,
  `RECIPIENT_ID` int(10) UNSIGNED NOT NULL,
  `IS_READ` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'COMMENT AS `0: False; 1: True`,
  `ADDITIONAL_INFO` text COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Notification recipients';

--
-- Triggers `notification_recipients`
--
DELIMITER $$
CREATE TRIGGER `NOTIFICATION_RECIPIENT_BU` BEFORE UPDATE ON `notification_recipients` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notification_setting_mod_det`
--

CREATE TABLE `notification_setting_mod_det` (
  `NOTIFICATION_SETTING_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `NOTIFICATION_SETTING_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of notification settings';

-- --------------------------------------------------------

--
-- Table structure for table `notification_settings`
--

CREATE TABLE `notification_settings` (
  `NOTIFICATION_SETTING_ID` int(10) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `EMAIL_SETTINGS` text COLLATE latin1_general_cs NOT NULL,
  `HEIGHT_UNIT` int(10) UNSIGNED DEFAULT NULL,
  `WEIGHT_UNIT` int(10) UNSIGNED DEFAULT NULL,
  `TEMP_UNIT` int(10) UNSIGNED DEFAULT NULL,
  `NOTIFICATION_COUNT` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `NOTIFICATION_LAST_VIEWED` datetime NOT NULL,
  `NOTIFICATION_FREQUENCY_ID` int(10) UNSIGNED NOT NULL,
  `LAST_RECOMMENDED` datetime DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Notification settings';

--
-- Triggers `notification_settings`
--
DELIMITER $$
CREATE TRIGGER `NOTIFICATION_SETTINGS_BU` BEFORE UPDATE ON `notification_settings` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `NOTIFICATION_ID` int(10) UNSIGNED NOT NULL,
  `NOTIFICATION_ACTIVITY_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `ACTIVITY_ID` int(10) UNSIGNED NOT NULL,
  `OBJECT_ID` int(10) UNSIGNED NOT NULL,
  `NOTIFICATION_OBJECT_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `SENDER_ID` int(10) UNSIGNED NOT NULL,
  `ADDITIONAL_INFO` text COLLATE latin1_general_cs NOT NULL,
  `ACTIVITY_SECTION_ID` int(10) UNSIGNED NOT NULL,
  `NOTIFICATION_ACTIVITY_SECTION_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `OBJECT_OWNER_ID` int(10) UNSIGNED DEFAULT NULL,
  `IS_ANONYMOUS` int(11) NOT NULL DEFAULT '0'COMMENT AS `0: No; 1: Yes`,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table of notifications';

--
-- Triggers `notifications`
--
DELIMITER $$
CREATE TRIGGER `NOTIFICATION_BU` BEFORE UPDATE ON `notifications` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notified_user_mod_det`
--

CREATE TABLE `notified_user_mod_det` (
  `NOTIFIED_USER_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `NOTIFIED_USER_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of notified users';

-- --------------------------------------------------------

--
-- Table structure for table `notified_users`
--

CREATE TABLE `notified_users` (
  `NOTIFIED_USER_ID` int(10) UNSIGNED NOT NULL,
  `NOTIFICATION_SETTING_ID` int(10) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Notified user list';

--
-- Triggers `notified_users`
--
DELIMITER $$
CREATE TRIGGER `NOTIFIED_USERS_BU` BEFORE UPDATE ON `notified_users` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `page_master`
--

CREATE TABLE `page_master` (
  `PAGE_ID` int(10) UNSIGNED NOT NULL,
  `PAGE_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `PAGE_DESCR` varchar(500) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Page descriptions';

--
-- Triggers `page_master`
--
DELIMITER $$
CREATE TRIGGER `PAGE_BU` BEFORE UPDATE ON `page_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `page_mod_det`
--

CREATE TABLE `page_mod_det` (
  `PAGE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `PAGE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(500) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of pages';

-- --------------------------------------------------------

--
-- Table structure for table `page_type_master`
--

CREATE TABLE `page_type_master` (
  `PAGE_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `PAGE_TYPE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Page type';

--
-- Triggers `page_type_master`
--
DELIMITER $$
CREATE TRIGGER `PAGE_TYPE_BU` BEFORE UPDATE ON `page_type_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `page_type_mod_det`
--

CREATE TABLE `page_type_mod_det` (
  `PAGE_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `PAGE_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of page type';

-- --------------------------------------------------------

--
-- Table structure for table `pain_level_mod_det`
--

CREATE TABLE `pain_level_mod_det` (
  `PAIN_LEVEL_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `PAIN_LEVEL_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of pain levels';

-- --------------------------------------------------------

--
-- Table structure for table `pain_levels_master`
--

CREATE TABLE `pain_levels_master` (
  `PAIN_LEVEL_ID` int(10) UNSIGNED NOT NULL,
  `PAIN_ID` int(10) UNSIGNED NOT NULL COMMENT 'Pain type from pain master',
  `PAIN_LEVEL_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last edited on',
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for pain levels';

--
-- Triggers `pain_levels_master`
--
DELIMITER $$
CREATE TRIGGER `PAIN_LEVEL_BU` BEFORE UPDATE ON `pain_levels_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pain_master`
--

CREATE TABLE `pain_master` (
  `PAIN_ID` int(10) UNSIGNED NOT NULL,
  `PAIN_DESCR` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all pain types';

--
-- Triggers `pain_master`
--
DELIMITER $$
CREATE TRIGGER `PAIN_TYPE_BU` BEFORE UPDATE ON `pain_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pain_type_mod_det`
--

CREATE TABLE `pain_type_mod_det` (
  `PAIN_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `PAIN_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for pain types';

-- --------------------------------------------------------

--
-- Table structure for table `patient_care_giver_mod_det`
--

CREATE TABLE `patient_care_giver_mod_det` (
  `PATIENT_CARE_GIVER_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `PATIENT_CARE_GIVER_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of patient care giver records';

-- --------------------------------------------------------

--
-- Table structure for table `patient_care_givers`
--

CREATE TABLE `patient_care_givers` (
  `PATIENT_CARE_GIVER_ID` int(10) UNSIGNED NOT NULL,
  `RELATIONSHIP_ID` int(10) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED DEFAULT NULL,
  `PATIENT_ID` int(10) UNSIGNED NOT NULL,
  `FIRST_NAME` varchar(100) COLLATE latin1_general_cs DEFAULT NULL,
  `LAST_NAME` varchar(100) COLLATE latin1_general_cs DEFAULT NULL,
  `DATE_OF_BIRTH` datetime DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `GENDER` varchar(1) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Details of patient care givers';

--
-- Triggers `patient_care_givers`
--
DELIMITER $$
CREATE TRIGGER `PATIENT_CARE_GIVERS_BU` BEFORE UPDATE ON `patient_care_givers` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `photo_type_master`
--

CREATE TABLE `photo_type_master` (
  `PHOTO_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `PHOTO_TYPE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all photo types';

--
-- Triggers `photo_type_master`
--
DELIMITER $$
CREATE TRIGGER `PHOTO_TYPE_BU` BEFORE UPDATE ON `photo_type_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `photo_type_mod_det`
--

CREATE TABLE `photo_type_mod_det` (
  `PHOTO_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `PHOTO_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for photo types';

-- --------------------------------------------------------

--
-- Table structure for table `poll_choice_mod_det`
--

CREATE TABLE `poll_choice_mod_det` (
  `POLL_CHOICE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `POLL_CHOICE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of poll choices';

-- --------------------------------------------------------

--
-- Table structure for table `poll_choices`
--

CREATE TABLE `poll_choices` (
  `POLL_CHOICE_ID` int(10) UNSIGNED NOT NULL,
  `POLL_ID` int(10) UNSIGNED NOT NULL,
  `POLL_OPTION` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `VOTES` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Poll choices';

--
-- Triggers `poll_choices`
--
DELIMITER $$
CREATE TRIGGER `POLL_CHOICE_BU` BEFORE UPDATE ON `poll_choices` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `poll_mod_det`
--

CREATE TABLE `poll_mod_det` (
  `POLL_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `POLL_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of polls';

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE `polls` (
  `POLL_ID` int(10) UNSIGNED NOT NULL,
  `POLL_TITLE` text COLLATE latin1_general_cs NOT NULL,
  `POLL_SECTION_TYPE_ID` int(10) UNSIGNED DEFAULT NULL,
  `POSTED_IN` int(10) UNSIGNED NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='List of all polls on H4L';

--
-- Triggers `polls`
--
DELIMITER $$
CREATE TRIGGER `POLL_BU` BEFORE UPDATE ON `polls` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `post_comments`
--

CREATE TABLE `post_comments` (
  `POST_COMMENT_ID` int(11) UNSIGNED NOT NULL,
  `POST_ID` int(10) UNSIGNED NOT NULL,
  `COMMENT_TEXT` text COLLATE latin1_general_cs,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Child table for posts that tracks all comments';

--
-- Triggers `post_comments`
--
DELIMITER $$
CREATE TRIGGER `POST_COMMENTS_BU` BEFORE UPDATE ON `post_comments` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `post_comments_mod_det`
--

CREATE TABLE `post_comments_mod_det` (
  `POST_COMMENT_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `POST_COMMENT_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of comments for user posts';

-- --------------------------------------------------------

--
-- Table structure for table `post_content_details`
--

CREATE TABLE `post_content_details` (
  `POST_CONTENT_ID` int(11) UNSIGNED NOT NULL,
  `POST_ID` int(10) UNSIGNED NOT NULL,
  `CONTENT_ATTRIBUTE_TEXT` text COLLATE latin1_general_cs,
  `CONTENT_ATTRIBUTE_ID` int(11) UNSIGNED NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Child table to posts with content details';

--
-- Triggers `post_content_details`
--
DELIMITER $$
CREATE TRIGGER `POST_CONTENT_BU` BEFORE UPDATE ON `post_content_details` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `post_content_mod_det`
--

CREATE TABLE `post_content_mod_det` (
  `POST_CONTENT_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `POST_CONTENT_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of post content details';

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `POST_LIKE_ID` int(10) UNSIGNED NOT NULL,
  `POST_ID` int(10) UNSIGNED NOT NULL,
  `LIKED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LIKED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `IP_ADDRESS` varchar(20) COLLATE latin1_general_cs DEFAULT NULL,
  `POST_LIKE_STATUS` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Records of likes by users';

--
-- Triggers `post_likes`
--
DELIMITER $$
CREATE TRIGGER `POST_LIKES_BU` BEFORE UPDATE ON `post_likes` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `post_likes_mod_det`
--

CREATE TABLE `post_likes_mod_det` (
  `POST_LIKE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `POST_LIKE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of user likes for posts';

-- --------------------------------------------------------

--
-- Table structure for table `post_location`
--

CREATE TABLE `post_location` (
  `POST_LOCATION_ID` int(10) UNSIGNED NOT NULL,
  `POST_ID` int(10) UNSIGNED NOT NULL,
  `POST_LOCATION` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(11) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing details of where the post was published';

--
-- Triggers `post_location`
--
DELIMITER $$
CREATE TRIGGER `POST_LOCATION_BU` BEFORE UPDATE ON `post_location` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `post_location_master`
--

CREATE TABLE `post_location_master` (
  `POST_LOCATION_ID` int(11) NOT NULL,
  `POST_LOCATION_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for posting location';

--
-- Triggers `post_location_master`
--
DELIMITER $$
CREATE TRIGGER `POST_LOCATION_MASTER_BU` BEFORE UPDATE ON `post_location_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `post_location_master_mod_det`
--

CREATE TABLE `post_location_master_mod_det` (
  `POST_LOCATION_MASTER_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `POST_LOCATION_ID` int(10) NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for post locations';

-- --------------------------------------------------------

--
-- Table structure for table `post_location_mod_det`
--

CREATE TABLE `post_location_mod_det` (
  `POST_LOCATION_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `POST_LOCATION_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for post location';

-- --------------------------------------------------------

--
-- Table structure for table `post_mod_det`
--

CREATE TABLE `post_mod_det` (
  `POST_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `POST_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of user posts';

-- --------------------------------------------------------

--
-- Table structure for table `post_privacy_mod_det`
--

CREATE TABLE `post_privacy_mod_det` (
  `POST_PRIVACY_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `POST_PRIVACY_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for privacy settings for individual posts';

-- --------------------------------------------------------

--
-- Table structure for table `post_privacy_settings`
--

CREATE TABLE `post_privacy_settings` (
  `POST_PRIVACY_ID` int(10) UNSIGNED NOT NULL,
  `POST_ID` int(10) UNSIGNED NOT NULL,
  `USER_TYPE_ID` int(11) DEFAULT NULL,
  `PRIVACY_ID` int(11) NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Triggers `post_privacy_settings`
--
DELIMITER $$
CREATE TRIGGER `POST_PRIVACY_BU` BEFORE UPDATE ON `post_privacy_settings` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `post_type_master`
--

CREATE TABLE `post_type_master` (
  `POST_TYPE_ID` int(11) NOT NULL,
  `POST_TYPE_TEXT` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Triggers `post_type_master`
--
DELIMITER $$
CREATE TRIGGER `POST_TYPE_BU` BEFORE UPDATE ON `post_type_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `post_type_mod_det`
--

CREATE TABLE `post_type_mod_det` (
  `POST_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `POST_TYPE_ID` int(10) NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of post types';

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `POST_ID` int(10) UNSIGNED NOT NULL,
  `CREATED_BY` int(10) UNSIGNED NOT NULL,
  `CREATED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last edited on',
  `IP_ADDRESS` varchar(15) COLLATE latin1_general_cs DEFAULT NULL,
  `LIKE_COUNT` int(10) UNSIGNED DEFAULT NULL,
  `COMMENT_COUNT` int(10) UNSIGNED DEFAULT NULL,
  `IS_DELETED` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `POST_TYPE_ID` int(11) NOT NULL,
  `IS_ANONYMOUS` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Triggers `posts`
--
DELIMITER $$
CREATE TRIGGER `POSTS_BU` BEFORE UPDATE ON `posts` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `privacy_master`
--

CREATE TABLE `privacy_master` (
  `PRIVACY_ID` int(11) NOT NULL,
  `PRIVACY_TEXT` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for privacy levels';

--
-- Triggers `privacy_master`
--
DELIMITER $$
CREATE TRIGGER `PRIVACY_BU` BEFORE UPDATE ON `privacy_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `privacy_mod_det`
--

CREATE TABLE `privacy_mod_det` (
  `PRIVACY_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `PRIVACY_ID` int(11) NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of privacy levels';

-- --------------------------------------------------------

--
-- Table structure for table `publish_type_master`
--

CREATE TABLE `publish_type_master` (
  `PUBLISH_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `PUBLISH_TYPE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table containing event publish types';

--
-- Triggers `publish_type_master`
--
DELIMITER $$
CREATE TRIGGER `PUBLISH_TYPE_BU` BEFORE UPDATE ON `publish_type_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `publish_type_mod_det`
--

CREATE TABLE `publish_type_mod_det` (
  `PUBLISH_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `PUBLISH_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of event publish type';

-- --------------------------------------------------------

--
-- Table structure for table `question_group_master`
--

CREATE TABLE `question_group_master` (
  `QUESTION_GROUP_ID` int(10) UNSIGNED NOT NULL,
  `QUESTION_GROUP` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all question groups';

--
-- Triggers `question_group_master`
--
DELIMITER $$
CREATE TRIGGER `QUESTION_GROUP_BU` BEFORE UPDATE ON `question_group_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `question_group_mod_det`
--

CREATE TABLE `question_group_mod_det` (
  `QUESTION_GROUP_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `QUESTION_GROUP_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of question groups';

-- --------------------------------------------------------

--
-- Table structure for table `question_master`
--

CREATE TABLE `question_master` (
  `QUESTION_ID` int(10) UNSIGNED NOT NULL,
  `QUESTION_TEXT` varchar(500) COLLATE latin1_general_cs NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `QUESTION_GROUP_ID` int(10) UNSIGNED NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for questions';

--
-- Triggers `question_master`
--
DELIMITER $$
CREATE TRIGGER `QUESTION_BU` BEFORE UPDATE ON `question_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `question_mod_det`
--

CREATE TABLE `question_mod_det` (
  `QUESTION_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `QUESTION_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(500) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of questions';

-- --------------------------------------------------------

--
-- Table structure for table `repeat_by_type_master`
--

CREATE TABLE `repeat_by_type_master` (
  `REPEAT_BY_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `REPEAT_BY_TYPE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Event repeat-by types';

--
-- Triggers `repeat_by_type_master`
--
DELIMITER $$
CREATE TRIGGER `REPEAT_BY_TYPE_BU` BEFORE UPDATE ON `repeat_by_type_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `repeat_by_type_mod_det`
--

CREATE TABLE `repeat_by_type_mod_det` (
  `REPEAT_BY_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `REPEAT_BY_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of event repeat-by types';

-- --------------------------------------------------------

--
-- Table structure for table `repeat_end_type_master`
--

CREATE TABLE `repeat_end_type_master` (
  `REPEAT_END_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `REPEAT_END_TYPE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Repeat end types';

--
-- Triggers `repeat_end_type_master`
--
DELIMITER $$
CREATE TRIGGER `REPEAT_END_TYPE_BU` BEFORE UPDATE ON `repeat_end_type_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `repeat_end_type_mod_det`
--

CREATE TABLE `repeat_end_type_mod_det` (
  `REPEAT_END_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `REPEAT_END_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of event repeat end type';

-- --------------------------------------------------------

--
-- Table structure for table `repeat_mode_type_master`
--

CREATE TABLE `repeat_mode_type_master` (
  `REPEAT_MODE_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `REPEAT_MODE_TYPE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of event repeat modes';

--
-- Triggers `repeat_mode_type_master`
--
DELIMITER $$
CREATE TRIGGER `REPEAT_MODE_TYPE_BU` BEFORE UPDATE ON `repeat_mode_type_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `repeat_mode_type_mod_det`
--

CREATE TABLE `repeat_mode_type_mod_det` (
  `REPEAT_MODE_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `REPEAT_MODE_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of repeat mode types';

-- --------------------------------------------------------

--
-- Table structure for table `repeat_type_master`
--

CREATE TABLE `repeat_type_master` (
  `REPEAT_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `REPEAT_TYPE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='List of different repeating frequency for events';

--
-- Triggers `repeat_type_master`
--
DELIMITER $$
CREATE TRIGGER `REPEAT_TYPE_BU` BEFORE UPDATE ON `repeat_type_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `repeat_type_mod_det`
--

CREATE TABLE `repeat_type_mod_det` (
  `REPEAT_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `REPEAT_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of repeat type frequencies';

-- --------------------------------------------------------

--
-- Table structure for table `section_type_master`
--

CREATE TABLE `section_type_master` (
  `SECTION_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `SECTION_TYPE_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Event section type';

--
-- Triggers `section_type_master`
--
DELIMITER $$
CREATE TRIGGER `SECTION_TYPE_BU` BEFORE UPDATE ON `section_type_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `section_type_mod_det`
--

CREATE TABLE `section_type_mod_det` (
  `SECTION_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `SECTION_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of event section types';

-- --------------------------------------------------------

--
-- Table structure for table `states_master`
--

CREATE TABLE `states_master` (
  `STATE_ID` int(11) NOT NULL,
  `COUNTRY_ID` int(11) DEFAULT NULL,
  `DESCRIPTION` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `SHORT_DESCRIPTION` varchar(15) COLLATE latin1_general_cs DEFAULT NULL,
  `CREATED_DATETIME` datetime DEFAULT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for states';

--
-- Triggers `states_master`
--
DELIMITER $$
CREATE TRIGGER `STATES_BU` BEFORE UPDATE ON `states_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `states_mod_det`
--

CREATE TABLE `states_mod_det` (
  `STATES_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `STATE_ID` int(11) NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of states';

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `STATUS_ID` int(11) NOT NULL,
  `STATUS` varchar(100) COLLATE latin1_general_cs NOT NULL COMMENT 'Status text',
  `STATUS_TYPE_ID` int(11) NOT NULL COMMENT 'Foreign key to Status Type',
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for status';

--
-- Triggers `status`
--
DELIMITER $$
CREATE TRIGGER `STATUS_BU` BEFORE UPDATE ON `status` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `status_mod_det`
--

CREATE TABLE `status_mod_det` (
  `STATUS_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `STATUS_ID` int(11) NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of status';

-- --------------------------------------------------------

--
-- Table structure for table `status_type`
--

CREATE TABLE `status_type` (
  `STATUS_TYPE_ID` int(11) NOT NULL,
  `STATUS_TYPE` varchar(100) COLLATE latin1_general_cs DEFAULT NULL COMMENT AS `Patient Status Type or Active Status Type or some other status type`,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='It will contain the type of status, could be patient status, disease status, etc';

--
-- Triggers `status_type`
--
DELIMITER $$
CREATE TRIGGER `STATUS_TYPE_BU` BEFORE UPDATE ON `status_type` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `status_type_mod_det`
--

CREATE TABLE `status_type_mod_det` (
  `STATUS_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `STATUS_TYPE_ID` int(11) NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of status type';

-- --------------------------------------------------------

--
-- Table structure for table `survey_master`
--

CREATE TABLE `survey_master` (
  `SURVEY_ID` int(10) UNSIGNED NOT NULL,
  `SURVEY_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `SURVEY_DESCR` text COLLATE latin1_general_cs,
  `SURVEY_KEY` varchar(100) COLLATE latin1_general_cs DEFAULT NULL,
  `SURVEY_TYPE` int(10) UNSIGNED DEFAULT NULL,
  `SURVEY_STATUS` int(10) DEFAULT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for H4L surveys';

--
-- Triggers `survey_master`
--
DELIMITER $$
CREATE TRIGGER `SURVEY_BU` BEFORE UPDATE ON `survey_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `survey_mod_det`
--

CREATE TABLE `survey_mod_det` (
  `SURVEY_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `SURVEY_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing modification history of surveys';

-- --------------------------------------------------------

--
-- Table structure for table `survey_questions`
--

CREATE TABLE `survey_questions` (
  `SURVEY_QUESTION_ID` int(10) UNSIGNED NOT NULL,
  `SURVEY_ID` int(10) UNSIGNED NOT NULL,
  `QUESTION_ID` int(10) UNSIGNED NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL,
  `CREATED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL,
  `IS_SINGLE_CHOICE` tinyint(3) UNSIGNED DEFAULT '1',
  `IS_MULTIPLE_CHOICE` tinyint(3) UNSIGNED DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing all questions for all surveys hosted on H4L';

--
-- Triggers `survey_questions`
--
DELIMITER $$
CREATE TRIGGER `SURVEY_QUESTIONS_BU` BEFORE UPDATE ON `survey_questions` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `survey_questions_answer_choices`
--

CREATE TABLE `survey_questions_answer_choices` (
  `SURVEY_QUESTIONS_ANSWER_CHOICE_ID` int(10) UNSIGNED NOT NULL,
  `SURVEY_QUESTION_ID` int(10) UNSIGNED NOT NULL,
  `ANSWER_CHOICE_TEXT` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing all answer choices for survey questions';

--
-- Triggers `survey_questions_answer_choices`
--
DELIMITER $$
CREATE TRIGGER `SURVEY_QUES_ANSWR_CHOICE_BU` BEFORE UPDATE ON `survey_questions_answer_choices` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `survey_questions_answer_choices_mod_det`
--

CREATE TABLE `survey_questions_answer_choices_mod_det` (
  `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `SURVEY_QUESTIONS_ANSWER_CHOICE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing modification history of answer choices for survey questions';

-- --------------------------------------------------------

--
-- Table structure for table `survey_questions_mod_det`
--

CREATE TABLE `survey_questions_mod_det` (
  `SURVEY_QUESTIONS_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `SURVEY_QUESTION_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Survey question modification history';

-- --------------------------------------------------------

--
-- Table structure for table `survey_results_answer_choices`
--

CREATE TABLE `survey_results_answer_choices` (
  `SURVEY_RESULTS_ANSWER_CHOICE_ID` int(10) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `SURVEY_QUESTIONS_ANSWER_CHOICE_ID` int(10) UNSIGNED NOT NULL,
  `RECORDED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing all user responses to choice questions';

--
-- Triggers `survey_results_answer_choices`
--
DELIMITER $$
CREATE TRIGGER `SURVEY_RESULT_ANSWR_CHOICE_BU` BEFORE UPDATE ON `survey_results_answer_choices` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `survey_results_answer_choices_mod_det`
--

CREATE TABLE `survey_results_answer_choices_mod_det` (
  `SURVEY_RESULTS_ANSWER_CHOICE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `SURVEY_RESULTS_ANSWER_CHOICE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(11) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for survey answer choices';

-- --------------------------------------------------------

--
-- Table structure for table `survey_results_detailed_answers`
--

CREATE TABLE `survey_results_detailed_answers` (
  `SURVEY_RESULTS_DETAILED_ANSWER_ID` int(10) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `RECORDED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `ANSWER_TEXT` text COLLATE latin1_general_cs,
  `IS_SKIPPED` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `SURVEY_QUESTION_ID` int(10) UNSIGNED NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing detailed answers to survey questions';

--
-- Triggers `survey_results_detailed_answers`
--
DELIMITER $$
CREATE TRIGGER `SURVEY_RESULT_DETAILED_ANSWR_BU` BEFORE UPDATE ON `survey_results_detailed_answers` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `survey_results_detailed_answers_mod_det`
--

CREATE TABLE `survey_results_detailed_answers_mod_det` (
  `SURVEY_RESULTS_DETAILED_ANSWER_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `SURVEY_RESULTS_DETAILED_ANSWER_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for detailed answers to survey questions';

-- --------------------------------------------------------

--
-- Table structure for table `survey_type_master`
--

CREATE TABLE `survey_type_master` (
  `SURVEY_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `SURVEY_TYPE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for survey types';

--
-- Triggers `survey_type_master`
--
DELIMITER $$
CREATE TRIGGER `SURVEY_TYPE_BU` BEFORE UPDATE ON `survey_type_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `survey_type_mod_det`
--

CREATE TABLE `survey_type_mod_det` (
  `SURVEY_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `SURVEY_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of survey type';

-- --------------------------------------------------------

--
-- Table structure for table `symptoms_master`
--

CREATE TABLE `symptoms_master` (
  `SYMPTOM_ID` int(10) UNSIGNED NOT NULL,
  `SYMPTOM` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `SYMPTOM_DESCR` text COLLATE latin1_general_cs,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for symptoms';

--
-- Triggers `symptoms_master`
--
DELIMITER $$
CREATE TRIGGER `SYMPTOMS_BU` BEFORE UPDATE ON `symptoms_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `symptoms_mod_det`
--

CREATE TABLE `symptoms_mod_det` (
  `SYMPTOM_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `SYMPTOM_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Symptom modification history';

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `TEAM_MEMBER_ID` int(10) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `TEAM_ID` int(10) UNSIGNED NOT NULL,
  `MEMBER_STATUS` int(11) DEFAULT NULL,
  `USER_ROLE_ID` int(11) NOT NULL,
  `INVITED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `JOINED_ON` datetime DEFAULT NULL,
  `EMAIL_NOTIFICATION` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `SITE_NOTIFICATION` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='List of team members';

--
-- Triggers `team_members`
--
DELIMITER $$
CREATE TRIGGER `TEAM_MEMBER_BU` BEFORE UPDATE ON `team_members` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `team_members_mod_det`
--

CREATE TABLE `team_members_mod_det` (
  `TEAM_MEMBER_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `TEAM_MEMBER_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing modification history for team members';

-- --------------------------------------------------------

--
-- Table structure for table `team_mod_det`
--

CREATE TABLE `team_mod_det` (
  `TEAM_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `TEAM_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for Teams table';

-- --------------------------------------------------------

--
-- Table structure for table `team_privacy_setting_mod_det`
--

CREATE TABLE `team_privacy_setting_mod_det` (
  `TEAM_PRIVACY_SETTING_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `TEAM_PRIVACY_SETTING_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of privacy settings for teams';

-- --------------------------------------------------------

--
-- Table structure for table `team_privacy_settings`
--

CREATE TABLE `team_privacy_settings` (
  `TEAM_PRIVACY_SETTING_ID` int(10) UNSIGNED NOT NULL,
  `TEAM_ID` int(10) UNSIGNED NOT NULL,
  `USER_TYPE_ID` int(10) NOT NULL,
  `PRIVACY_ID` int(10) NOT NULL,
  `PRIVACY_SET_BY` int(10) UNSIGNED DEFAULT NULL,
  `PRIVACY_SET_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Privacy settings for individual teams';

--
-- Triggers `team_privacy_settings`
--
DELIMITER $$
CREATE TRIGGER `TEAM_PRIVACY_BU` BEFORE UPDATE ON `team_privacy_settings` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `TEAM_ID` int(10) UNSIGNED NOT NULL,
  `TEAM_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `TEAM_DESCR` text COLLATE latin1_general_cs,
  `MEMBER_COUNT` int(10) UNSIGNED DEFAULT NULL,
  `PATIENT_ID` int(11) UNSIGNED NOT NULL,
  `CREATED_BY` int(11) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `TEAM_STATUS` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Team information';

--
-- Triggers `teams`
--
DELIMITER $$
CREATE TRIGGER `TEAM_BU` BEFORE UPDATE ON `teams` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `timezone_master`
--

CREATE TABLE `timezone_master` (
  `TIMEZONE_ID` int(11) NOT NULL,
  `TIMEZONE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `TIMEZONE_VALUE` varchar(100) COLLATE latin1_general_cs DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for timezones';

--
-- Triggers `timezone_master`
--
DELIMITER $$
CREATE TRIGGER `TIMEZONE_BU` BEFORE UPDATE ON `timezone_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `timezone_mod_det`
--

CREATE TABLE `timezone_mod_det` (
  `TIMEZONE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `TIMEZONE_ID` int(11) NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of timezones';

-- --------------------------------------------------------

--
-- Table structure for table `treatment_master`
--

CREATE TABLE `treatment_master` (
  `TREATMENT_ID` int(10) UNSIGNED NOT NULL,
  `TREATMENT_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `LAST_EDITED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for treatments';

--
-- Triggers `treatment_master`
--
DELIMITER $$
CREATE TRIGGER `TREATMENT_BU` BEFORE UPDATE ON `treatment_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `treatment_master_mod_det`
--

CREATE TABLE `treatment_master_mod_det` (
  `TREATMENT_MASTER_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `TREATMENT_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for treatments';

-- --------------------------------------------------------

--
-- Table structure for table `unit_of_measurement_master`
--

CREATE TABLE `unit_of_measurement_master` (
  `UNIT_ID` int(10) UNSIGNED NOT NULL,
  `UNIT_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `CONV_FACTOR_ENGLISH` float UNSIGNED DEFAULT NULL,
  `CONV_FACTOR_METRIC` float UNSIGNED DEFAULT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for unit of measurement';

--
-- Triggers `unit_of_measurement_master`
--
DELIMITER $$
CREATE TRIGGER `UOM_BU` BEFORE UPDATE ON `unit_of_measurement_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `unit_of_measurement_mod_det`
--

CREATE TABLE `unit_of_measurement_mod_det` (
  `UNIT_OF_MEASUREMENT_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `UNIT_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of units table';

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_logs`
--

CREATE TABLE `user_activity_logs` (
  `USER_ACTIVITY_ID` int(10) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `USER_IP_ADDRESS` varchar(20) COLLATE latin1_general_cs NOT NULL,
  `BROWSER_DESCR` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `CONTROLLER_DESCR` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `ACTION_DESCR` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `URL` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(11) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='User activity log';

--
-- Triggers `user_activity_logs`
--
DELIMITER $$
CREATE TRIGGER `ACTIVITY_LOG_BU` BEFORE UPDATE ON `user_activity_logs` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_mod_det`
--

CREATE TABLE `user_activity_mod_det` (
  `USER_ACTIVITY_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `USER_ACTIVITY_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of user activity logs';

-- --------------------------------------------------------

--
-- Table structure for table `user_attribute_mod_history`
--

CREATE TABLE `user_attribute_mod_history` (
  `USER_ATTRIBUTE_MOD_HISTORY_ID` int(11) NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED NOT NULL,
  `MODIFIED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `USER_ATTRIBUTE_ID` int(11) NOT NULL,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(500) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='User attribute modification history';

-- --------------------------------------------------------

--
-- Table structure for table `user_attributes`
--

CREATE TABLE `user_attributes` (
  `USER_ATTRIBUTE_ID` int(11) NOT NULL,
  `ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL,
  `VALUE` varchar(500) COLLATE latin1_general_cs NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `STATUS_ID` int(11) DEFAULT NULL,
  `EFF_DATE_FROM` datetime DEFAULT CURRENT_TIMESTAMP,
  `EFF_DATE_TO` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='User attribute table';

--
-- Triggers `user_attributes`
--
DELIMITER $$
CREATE TRIGGER `USER_ATTRIBUTES_BU` BEFORE UPDATE ON `user_attributes` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_diseases`
--

CREATE TABLE `user_diseases` (
  `USER_DISEASE_ID` int(10) UNSIGNED NOT NULL,
  `DISEASE_ID` int(10) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `DIAGNOSED_ON` datetime DEFAULT NULL,
  `DIAGNOSED_BY` varchar(200) COLLATE latin1_general_cs DEFAULT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for user''s diseases';

--
-- Triggers `user_diseases`
--
DELIMITER $$
CREATE TRIGGER `USER_DISEASES_BU` BEFORE UPDATE ON `user_diseases` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_diseases_mod_det`
--

CREATE TABLE `user_diseases_mod_det` (
  `USER_DISEASES_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `USER_DISEASE_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing modification history for user''s diseases';

-- --------------------------------------------------------

--
-- Table structure for table `user_fav_posts_mod_det`
--

CREATE TABLE `user_fav_posts_mod_det` (
  `USER_FAV_POST_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `USER_FAVORITE_POST_ID` int(10) NOT NULL,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for user''s favorite posts';

-- --------------------------------------------------------

--
-- Table structure for table `user_favorite_posts`
--

CREATE TABLE `user_favorite_posts` (
  `USER_FAVORITE_POST_ID` int(11) NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `POST_ID` int(10) UNSIGNED NOT NULL,
  `LIKED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Triggers `user_favorite_posts`
--
DELIMITER $$
CREATE TRIGGER `USER_FAV_POSTS_BU` BEFORE UPDATE ON `user_favorite_posts` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_health_history_det`
--

CREATE TABLE `user_health_history_det` (
  `USER_HEALTH_HISTORY_DET_ID` int(11) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `NOTES` text COLLATE latin1_general_cs,
  `FROM_DATE` datetime DEFAULT NULL,
  `TO_DATE` datetime DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `HEALTH_CONDITION_ID` int(11) NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='User health history details';

--
-- Triggers `user_health_history_det`
--
DELIMITER $$
CREATE TRIGGER `USER_HEALTH_HISTORY_BU` BEFORE UPDATE ON `user_health_history_det` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_health_history_mod_det`
--

CREATE TABLE `user_health_history_mod_det` (
  `USER_HEALTH_HISTORY_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `USER_HEALTH_HISTORY_DET_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED NOT NULL,
  `MODIFIED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for individual records in user health history';

-- --------------------------------------------------------

--
-- Table structure for table `user_health_reading`
--

CREATE TABLE `user_health_reading` (
  `USER_HEALTH_READING_ID` int(10) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `ATTRIBUTE_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `ATTRIBUTE_VALUE` int(11) NOT NULL,
  `UNIT_ID` int(10) UNSIGNED NOT NULL,
  `RECORD_DESCR` text COLLATE latin1_general_cs,
  `DATE_RECORDED_ON` int(10) UNSIGNED DEFAULT NULL,
  `MONTH_RECORDED_ON` int(10) UNSIGNED DEFAULT NULL,
  `YEAR_RECORDED_ON` int(10) UNSIGNED DEFAULT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Details of user health readings';

--
-- Triggers `user_health_reading`
--
DELIMITER $$
CREATE TRIGGER `USER_HEALTH_READING_BU` BEFORE UPDATE ON `user_health_reading` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_health_reading_mod_det`
--

CREATE TABLE `user_health_reading_mod_det` (
  `USER_HEALTH_READING_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `USER_HEALTH_READING_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of user health readings';

-- --------------------------------------------------------

--
-- Table structure for table `user_media`
--

CREATE TABLE `user_media` (
  `USER_MEDIA_ID` int(10) UNSIGNED NOT NULL,
  `MEDIA_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL,
  `IS_DELETED` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all user generated media';

--
-- Triggers `user_media`
--
DELIMITER $$
CREATE TRIGGER `USER_MEDIA_BU` BEFORE UPDATE ON `user_media` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_media_mod_det`
--

CREATE TABLE `user_media_mod_det` (
  `USER_MEDIA_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `USER_MEDIA_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history for user media';

-- --------------------------------------------------------

--
-- Table structure for table `user_message_recipients`
--

CREATE TABLE `user_message_recipients` (
  `USER_MESSAGE_RECIPIENT_ID` int(10) UNSIGNED NOT NULL,
  `MESSAGE_ID` int(10) UNSIGNED NOT NULL,
  `RECIPIENT_USER_ID` int(10) UNSIGNED NOT NULL,
  `RECIPIENT_ROLE_ID` int(10) UNSIGNED NOT NULL,
  `IS_MESSAGE_READ` tinyint(3) UNSIGNED DEFAULT '0',
  `IS_MESSAGE_DELETED` tinyint(3) UNSIGNED DEFAULT '0',
  `MESSAGE_READ_TIME` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='List of all recipients for any message';

-- --------------------------------------------------------

--
-- Table structure for table `user_messages`
--

CREATE TABLE `user_messages` (
  `MESSAGE_ID` int(10) UNSIGNED NOT NULL,
  `SENDER_USER_ID` int(10) UNSIGNED NOT NULL,
  `MESSAGE_TEXT` text COLLATE latin1_general_cs,
  `MESSAGE_SUBJECT` varchar(500) COLLATE latin1_general_cs DEFAULT NULL,
  `MESSAGE_DATE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL,
  `HAS_ATTACHMENTS` tinyint(3) UNSIGNED DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='User message table';

-- --------------------------------------------------------

--
-- Table structure for table `user_mod_det`
--

CREATE TABLE `user_mod_det` (
  `USER_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(150) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of user table';

-- --------------------------------------------------------

--
-- Table structure for table `user_mood_history`
--

CREATE TABLE `user_mood_history` (
  `USER_MOOD_HISTORY_ID` int(11) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `USER_MOOD_LONG_DESCR` text COLLATE latin1_general_cs,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `USER_MOOD_ID` int(11) NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='User mood history';

--
-- Triggers `user_mood_history`
--
DELIMITER $$
CREATE TRIGGER `USER_MOOD_HIST_BU` BEFORE UPDATE ON `user_mood_history` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_mood_history_mod_det`
--

CREATE TABLE `user_mood_history_mod_det` (
  `USER_MOOD_HISTORY_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `USER_MOOD_HISTORY_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of user mood history';

-- --------------------------------------------------------

--
-- Table structure for table `user_pain_tracker`
--

CREATE TABLE `user_pain_tracker` (
  `USER_PAIN_ID` int(10) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `PAIN_ID` int(10) UNSIGNED NOT NULL,
  `PAIN_LEVEL_ID` int(10) UNSIGNED NOT NULL,
  `USER_DESCR` text COLLATE latin1_general_cs,
  `DATE_EXPERIENCED_ON` int(10) UNSIGNED DEFAULT NULL,
  `MONTH_EXPERIENCED_ON` int(10) UNSIGNED DEFAULT NULL,
  `YEAR_EXPERIENCED_ON` int(10) UNSIGNED DEFAULT NULL,
  `RECORDED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing user pain history';

--
-- Triggers `user_pain_tracker`
--
DELIMITER $$
CREATE TRIGGER `USER_PAIN_TRACKER_BU` BEFORE UPDATE ON `user_pain_tracker` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_pain_tracker_mod_det`
--

CREATE TABLE `user_pain_tracker_mod_det` (
  `USER_PAIN_TRACKER_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `USER_PAIN_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` text COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of user pain history';

-- --------------------------------------------------------

--
-- Table structure for table `user_photos`
--

CREATE TABLE `user_photos` (
  `USER_PHOTO_ID` int(10) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `FILE_NAME` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `PHOTO_TYPE_ID` int(10) UNSIGNED NOT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL,
  `IS_DEFAULT` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing records of all user photos';

--
-- Triggers `user_photos`
--
DELIMITER $$
CREATE TRIGGER `USER_PHOTOS_BU` BEFORE UPDATE ON `user_photos` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_photos_mod_det`
--

CREATE TABLE `user_photos_mod_det` (
  `USER_PHOTOS_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `USER_PHOTO_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification history of user photos';

-- --------------------------------------------------------

--
-- Table structure for table `user_privacy_mod_det`
--

CREATE TABLE `user_privacy_mod_det` (
  `USER_PRIVACY_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `USER_PRIVACY_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of user privacy settings';

-- --------------------------------------------------------

--
-- Table structure for table `user_privacy_settings`
--

CREATE TABLE `user_privacy_settings` (
  `USER_PRIVACY_ID` int(11) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `USER_TYPE_ID` int(11) NOT NULL,
  `ACTIVITY_SECTION_ID` int(11) UNSIGNED NOT NULL,
  `PRIVACY_ID` int(11) NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='User privacy settings';

-- --------------------------------------------------------

--
-- Table structure for table `user_psswrd_challenge_ques`
--

CREATE TABLE `user_psswrd_challenge_ques` (
  `USER_PSSWRD_QUES_ID` int(11) UNSIGNED NOT NULL,
  `PSSWRD_QUES_ID` int(10) UNSIGNED NOT NULL COMMENT 'Reference master table',
  `USER_ID` int(10) UNSIGNED NOT NULL COMMENT 'References master table of users',
  `QUES_ANSWR_TEXT` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `ORDER_ID` int(10) UNSIGNED NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='User''s password questions and answers';

--
-- Triggers `user_psswrd_challenge_ques`
--
DELIMITER $$
CREATE TRIGGER `PSSWRD_CHALLENGE_QUES_BU` BEFORE UPDATE ON `user_psswrd_challenge_ques` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_psswrd_challenge_ques_mod_det`
--

CREATE TABLE `user_psswrd_challenge_ques_mod_det` (
  `USER_PSSWRD_CHALLENGE_QUES_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `USER_PSSWRD_QUES_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of user password challenge questions';

-- --------------------------------------------------------

--
-- Table structure for table `user_symptom_records`
--

CREATE TABLE `user_symptom_records` (
  `USER_SYMPTOM_RECORD_ID` int(10) UNSIGNED NOT NULL,
  `UNIT_ID` int(10) UNSIGNED NOT NULL,
  `RECORD_VALUE` int(11) NOT NULL,
  `RECORDED_BY` varchar(100) COLLATE latin1_general_cs DEFAULT NULL COMMENT AS `Healthcare professional who took the reading`,
  `DATE_RECORDED_ON` int(10) UNSIGNED DEFAULT NULL,
  `MONTH_RECORDED_ON` int(10) UNSIGNED DEFAULT NULL,
  `YEAR_RECORDED_ON` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing all recorded measures for user symptoms';

--
-- Triggers `user_symptom_records`
--
DELIMITER $$
CREATE TRIGGER `SYMPTOM_RECORDS_BU` BEFORE UPDATE ON `user_symptom_records` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_symptom_records_mod_det`
--

CREATE TABLE `user_symptom_records_mod_det` (
  `USER_SYMPTOM_RECORDS_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `USER_SYMPTOM_RECORD_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details for measurement of user symptoms';

-- --------------------------------------------------------

--
-- Table structure for table `user_symptoms`
--

CREATE TABLE `user_symptoms` (
  `USER_SYMPTOM_ID` int(10) UNSIGNED NOT NULL,
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `SYMPTOM_ID` int(10) UNSIGNED NOT NULL,
  `STATUS_ID` int(11) DEFAULT NULL,
  `CREATED_BY` int(10) UNSIGNED DEFAULT NULL COMMENT AS `Last edited by`,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing all symptoms for individual users';

--
-- Triggers `user_symptoms`
--
DELIMITER $$
CREATE TRIGGER `USER_SYMPTOMS_BU` BEFORE UPDATE ON `user_symptoms` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_symptoms_mod_det`
--

CREATE TABLE `user_symptoms_mod_det` (
  `USER_SYMPTOMS_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `USER_SYMPTOM_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Table containing modification details for user symptoms';

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `USER_TYPE` varchar(200) COLLATE latin1_general_cs NOT NULL,
  `USER_TYPE_ID` int(11) NOT NULL,
  `STATUS` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Triggers `user_type`
--
DELIMITER $$
CREATE TRIGGER `USER_TYPE_BU` BEFORE UPDATE ON `user_type` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_type_mod_det`
--

CREATE TABLE `user_type_mod_det` (
  `USER_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `USER_TYPE_ID` int(11) NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(200) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of user types';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `USER_ID` int(10) UNSIGNED NOT NULL,
  `IS_ADMIN` tinyint(4) NOT NULL DEFAULT '0'COMMENT AS `If the user is admin?`,
  `USERNAME` varchar(30) COLLATE latin1_general_cs NOT NULL,
  `EMAIL` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `PASSWORD` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `PROFILE_PICTURE` varchar(150) COLLATE latin1_general_cs DEFAULT NULL,
  `FIRST_NAME` varchar(50) COLLATE latin1_general_cs DEFAULT NULL,
  `LAST_NAME` varchar(50) COLLATE latin1_general_cs DEFAULT NULL,
  `GENDER` varchar(1) COLLATE latin1_general_cs DEFAULT NULL,
  `DATE_OF_BIRTH` date DEFAULT NULL,
  `ABOUT_ME` varchar(150) COLLATE latin1_general_cs DEFAULT NULL,
  `ZIP` varchar(10) COLLATE latin1_general_cs DEFAULT NULL COMMENT AS `Present zip code of residence`,
  `STATE` int(11) DEFAULT NULL COMMENT AS `Present state of residence`,
  `CITY` int(11) DEFAULT NULL COMMENT AS `Present city of residence`,
  `CREATED` datetime DEFAULT CURRENT_TIMESTAMP,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `NEWSLETTER` tinyint(4) NOT NULL DEFAULT '0'COMMENT AS `If the user subscribed for newsletter?`,
  `LAST_LOGIN` datetime DEFAULT NULL COMMENT AS `Datetime when he/she last logged in`,
  `LAST_ACTIVITY` datetime DEFAULT NULL COMMENT AS `Datetime of last activity on site`,
  `REMEMBER_ME_CODE` varchar(100) COLLATE latin1_general_cs DEFAULT NULL,
  `FORGOT_PASSWORD_CODE` varchar(100) COLLATE latin1_general_cs DEFAULT NULL COMMENT AS `Forgot Password Code`,
  `LANGUAGE` int(11) UNSIGNED DEFAULT NULL COMMENT AS `First language`,
  `COUNTRY` int(11) DEFAULT NULL COMMENT AS `Present country of residence`,
  `USER_TYPE` int(11) DEFAULT NULL,
  `TIMEZONE` int(11) DEFAULT NULL COMMENT AS `Timezone of current residence`,
  `DASHBOARD_SLIDESHOW_ENABLED` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `HAS_ANONYMOUS_PERMISSION` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `COVER_SLIDESHOW_ENABLED` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `STATUS_ID` int(11) DEFAULT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `USER_BU` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `week_days_master`
--

CREATE TABLE `week_days_master` (
  `WEEK_DAY_ID` int(10) UNSIGNED NOT NULL,
  `WEEK_DAY_DESCR` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Week day names';

-- --------------------------------------------------------

--
-- Table structure for table `week_days_mod_det`
--

CREATE TABLE `week_days_mod_det` (
  `WEEK_DAYS_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `WEEK_DAY_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of week days';

-- --------------------------------------------------------

--
-- Table structure for table `year_mod_det`
--

CREATE TABLE `year_mod_det` (
  `YEAR_MOD_DET_ID` int(10) UNSIGNED NOT NULL,
  `YEAR_ID` int(10) UNSIGNED NOT NULL,
  `MODIFIED_BY` int(10) UNSIGNED DEFAULT NULL,
  `MODIFIED_ON` datetime DEFAULT CURRENT_TIMESTAMP,
  `COLUMN_NAME` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `COLUMN_VALUE` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Modification details of years';

-- --------------------------------------------------------

--
-- Table structure for table `years_master`
--

CREATE TABLE `years_master` (
  `YEAR_ID` int(10) UNSIGNED NOT NULL,
  `YEAR_VALUE` varchar(10) COLLATE latin1_general_cs NOT NULL,
  `LAST_EDITED_BY` int(10) UNSIGNED DEFAULT NULL,
  `LAST_EDITED_ON` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `STATUS_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs COMMENT='Master table for all years';

--
-- Triggers `years_master`
--
DELIMITER $$
CREATE TRIGGER `YEAR_BU` BEFORE UPDATE ON `years_master` FOR EACH ROW BEGIN
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
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `action_tokens_master`
--
ALTER TABLE `action_tokens_master`
  ADD PRIMARY KEY (`ACTION_TOKEN_ID`),
  ADD UNIQUE KEY `ACTION_TOKEN_DESCR` (`ACTION_TOKEN_DESCR`),
  ADD KEY `ACTION_TOKENS_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `ACTION_TOKENS_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `action_tokens_mod_det`
--
ALTER TABLE `action_tokens_mod_det`
  ADD PRIMARY KEY (`ACTION_TOKENS_MOD_DET_ID`),
  ADD KEY `ACTION_TOKENS_MOD_DET_FK1` (`ACTION_TOKEN_ID`),
  ADD KEY `ACTION_TOKENS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `activity_section_master`
--
ALTER TABLE `activity_section_master`
  ADD PRIMARY KEY (`ACTIVITY_SECTION_ID`),
  ADD UNIQUE KEY `NOTIFICATION_ACTIVITY_SECTION_NAME` (`ACTIVITY_SECTION_NAME`),
  ADD KEY `NOTIFICATION_ACTIVITY_SECTION_FK1` (`LAST_EDITED_BY`),
  ADD KEY `NOTIFICATION_ACTIVITY_SECTION_FK2` (`STATUS_ID`);

--
-- Indexes for table `activity_section_mod_det`
--
ALTER TABLE `activity_section_mod_det`
  ADD PRIMARY KEY (`ACTIVITY_SECTION_MOD_DET_ID`),
  ADD KEY `NOTIFICATION_ACTIVITY_SECTION_MOD_DET_FK2` (`MODIFIED_BY`),
  ADD KEY `ACTIVITY_SECTION_MOD_DET_FK1` (`ACTIVITY_SECTION_ID`);

--
-- Indexes for table `arrowchat`
--
ALTER TABLE `arrowchat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `to` (`to`),
  ADD KEY `read` (`read`),
  ADD KEY `user_read` (`user_read`),
  ADD KEY `from` (`from`);

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
  ADD KEY `user_id` (`user_id`),
  ADD KEY `chatroom_id` (`chatroom_id`);

--
-- Indexes for table `arrowchat_chatroom_messages`
--
ALTER TABLE `arrowchat_chatroom_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chatroom_id` (`chatroom_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `sent` (`sent`);

--
-- Indexes for table `arrowchat_chatroom_rooms`
--
ALTER TABLE `arrowchat_chatroom_rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_time` (`session_time`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `arrowchat_chatroom_users`
--
ALTER TABLE `arrowchat_chatroom_users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `chatroom_id` (`chatroom_id`),
  ADD KEY `is_admin` (`is_admin`),
  ADD KEY `is_mod` (`is_mod`),
  ADD KEY `session_time` (`session_time`);

--
-- Indexes for table `arrowchat_config`
--
ALTER TABLE `arrowchat_config`
  ADD UNIQUE KEY `config_name` (`config_name`);

--
-- Indexes for table `arrowchat_graph_log`
--
ALTER TABLE `arrowchat_graph_log`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `date` (`date`);

--
-- Indexes for table `arrowchat_notifications`
--
ALTER TABLE `arrowchat_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `to_id` (`to_id`),
  ADD KEY `alert_read` (`alert_read`),
  ADD KEY `user_read` (`user_read`),
  ADD KEY `alert_time` (`alert_time`);

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
  ADD PRIMARY KEY (`userid`),
  ADD KEY `hash_id` (`hash_id`),
  ADD KEY `session_time` (`session_time`);

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
-- Indexes for table `attribute_master_mod_det`
--
ALTER TABLE `attribute_master_mod_det`
  ADD PRIMARY KEY (`ATTRIBUTE_MASTER_MOD_DET_ID`),
  ADD KEY `ATTRIBUTE_MASTER_MOD_DET_FK1` (`ATTRIBUTE_ID`),
  ADD KEY `ATTRIBUTE_MASTER_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `attribute_type_master`
--
ALTER TABLE `attribute_type_master`
  ADD PRIMARY KEY (`ATTRIBUTE_TYPE_ID`),
  ADD UNIQUE KEY `ATTRIBUTE_TYPE_DESCR` (`ATTRIBUTE_TYPE_DESCR`),
  ADD KEY `ATTRIBUTE_TYPE_MASTER_FK1` (`CREATED_BY`),
  ADD KEY `ATTRIBUTE_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `attribute_type_mod_det`
--
ALTER TABLE `attribute_type_mod_det`
  ADD PRIMARY KEY (`ATTRIBUTE_TYPE_MOD_DET_ID`),
  ADD KEY `ATTRIBUTE_TYPE_MOD_DET_FK1` (`ATTRIBUTE_TYPE_ID`),
  ADD KEY `ATTRIBUTE_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `attributes_master`
--
ALTER TABLE `attributes_master`
  ADD PRIMARY KEY (`ATTRIBUTE_ID`),
  ADD UNIQUE KEY `ATTRIBUTE_VAL` (`ATTRIBUTE_DESCR`,`ATTRIBUTE_TYPE_ID`),
  ADD KEY `ATTRIBUTES_MASTER_FK1` (`ATTRIBUTE_TYPE_ID`),
  ADD KEY `ATTRIBUTES_MASTER_FK2` (`CREATED_BY`),
  ADD KEY `ATTRIBUTES_MASTER_FK3` (`STATUS_ID`);

--
-- Indexes for table `blocked_user_mod_det`
--
ALTER TABLE `blocked_user_mod_det`
  ADD PRIMARY KEY (`BLOCKED_USER_MOD_DET_ID`),
  ADD KEY `BLOCKED_USER_MOD_DET_FK1` (`BLOCKED_USER_ID`),
  ADD KEY `BLOCKED_USER_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `blocked_users`
--
ALTER TABLE `blocked_users`
  ADD PRIMARY KEY (`BLOCKED_USER_ID`),
  ADD UNIQUE KEY `USER_ID` (`USER_ID`,`BLOCKED_USER`),
  ADD KEY `BLOCKED_USERS_FK2` (`BLOCKED_USER`),
  ADD KEY `BLOCKED_USERS_FK3` (`BLOCKED_BY`),
  ADD KEY `BLOCKED_USERS_FK4` (`STATUS_ID`);

--
-- Indexes for table `care_calendar_events`
--
ALTER TABLE `care_calendar_events`
  ADD PRIMARY KEY (`CARE_EVENT_ID`),
  ADD KEY `CARE_CALENDAR_EVENTS_FK1` (`ASSIGNED_TO`),
  ADD KEY `CARE_CALENDAR_EVENTS_FK2` (`STATUS_ID`),
  ADD KEY `CARE_CALENDAR_EVENTS_FK3` (`CARE_EVENT_TYPE_ID`),
  ADD KEY `CARE_CALENDAR_EVENTS_FK4` (`LAST_EDITED_BY`);

--
-- Indexes for table `care_events_mod_det`
--
ALTER TABLE `care_events_mod_det`
  ADD PRIMARY KEY (`CARE_EVENTS_MOD_DET_ID`),
  ADD KEY `CARE_EVENTS_MOD_DET_FK1` (`CARE_EVENT_ID`),
  ADD KEY `CARE_EVENTS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `care_giver_attribute_mod_det`
--
ALTER TABLE `care_giver_attribute_mod_det`
  ADD PRIMARY KEY (`CARE_GIVER_ATTRIBUTE_MOD_DET_ID`),
  ADD KEY `CARE_GIVER_ATTRIBUTE_MOD_DET_FK1` (`CARE_GIVER_ATTRIBUTE_ID`),
  ADD KEY `CARE_GIVER_ATTRIBUTE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `care_giver_attributes`
--
ALTER TABLE `care_giver_attributes`
  ADD PRIMARY KEY (`CARE_GIVER_ATTRIBUTE_ID`),
  ADD KEY `CARE_GIVER_ATTRIBUTES_FK1` (`PATIENT_CARE_GIVER_ID`),
  ADD KEY `CARE_GIVER_ATTRIBUTES_FK2` (`ATTRIBUTE_ID`),
  ADD KEY `CARE_GIVER_ATTRIBUTES_FK3` (`LAST_EDITED_BY`),
  ADD KEY `CARE_GIVER_ATTRIBUTES_FK4` (`STATUS_ID`);

--
-- Indexes for table `caregiver_relationship_master`
--
ALTER TABLE `caregiver_relationship_master`
  ADD PRIMARY KEY (`RELATIONSHIP_ID`),
  ADD UNIQUE KEY `RELATIONSHIP_DESCR` (`RELATIONSHIP_DESCR`),
  ADD KEY `CAREGIVER_RELATIONSHIP_FK1` (`LAST_EDITED_BY`),
  ADD KEY `CAREGIVER_RELATIONSHIP_FK2` (`STATUS_ID`);

--
-- Indexes for table `caregiver_relationship_mod_det`
--
ALTER TABLE `caregiver_relationship_mod_det`
  ADD PRIMARY KEY (`CAREGIVER_RELATIONSHIP_MOD_DET_ID`),
  ADD KEY `CAREGIVER_RELATIONSHIP_MOD_DET_FK1` (`RELATIONSHIP_ID`),
  ADD KEY `CAREGIVER_RELATIONSHIP_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `cities_master`
--
ALTER TABLE `cities_master`
  ADD PRIMARY KEY (`CITY_ID`),
  ADD UNIQUE KEY `DESCRIPTION` (`DESCRIPTION`,`STATE_ID`),
  ADD KEY `CITIES_FK2` (`CREATED_BY`),
  ADD KEY `CITIES_FK1` (`STATE_ID`),
  ADD KEY `CITIES_FK4` (`STATUS_ID`);

--
-- Indexes for table `cities_mod_det`
--
ALTER TABLE `cities_mod_det`
  ADD PRIMARY KEY (`CITIES_MOD_DET_ID`),
  ADD KEY `CITIES_MOD_DET_FK1` (`CITY_ID`),
  ADD KEY `CITIES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `community_attributes`
--
ALTER TABLE `community_attributes`
  ADD PRIMARY KEY (`COMMUNITY_ATTRIBUTE_ID`),
  ADD KEY `COMMUNITY_ATTRIBUTES_FK1` (`COMMUNITY_ID`),
  ADD KEY `COMMUNITY_ATTRIBUTES_FK2` (`ATTRIBUTE_ID`),
  ADD KEY `COMMUNITY_ATTRIBUTES_FK3` (`LAST_EDITED_BY`),
  ADD KEY `COMMUNITY_ATTRIBUTES_FK4` (`STATUS_ID`);

--
-- Indexes for table `community_attributes_mod_det`
--
ALTER TABLE `community_attributes_mod_det`
  ADD PRIMARY KEY (`COMMUNITY_ATTRIBUTE_MOD_DET_ID`),
  ADD KEY `COMMUNITY_ATTRIBUTES_MOD_DET_FK1` (`COMMUNITY_ATTRIBUTE_ID`),
  ADD KEY `COMMUNITY_ATTRIBUTES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `community_diseases`
--
ALTER TABLE `community_diseases`
  ADD PRIMARY KEY (`COMMUNITY_DISEASE_ID`),
  ADD UNIQUE KEY `COMMUNITY_ID` (`COMMUNITY_ID`,`DISEASE_ID`),
  ADD KEY `COMMUNITY_DISEASES_FK2` (`DISEASE_ID`),
  ADD KEY `COMMUNITY_DISEASES_FK3` (`LAST_EDITED_BY`),
  ADD KEY `COMMUNITY_DISEASES_FK4` (`STATUS_ID`);

--
-- Indexes for table `community_diseases_mod_det`
--
ALTER TABLE `community_diseases_mod_det`
  ADD PRIMARY KEY (`COMMUNITY_DISEASE_MOD_DET_ID`),
  ADD KEY `COMMUNITY_DISEASES_MOD_DET_FK1` (`COMMUNITY_DISEASE_ID`),
  ADD KEY `COMMUNITY_DISEASES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `community_members`
--
ALTER TABLE `community_members`
  ADD PRIMARY KEY (`COMMUNITY_MEMBER_ID`),
  ADD UNIQUE KEY `COMMUNITY_ID` (`COMMUNITY_ID`,`USER_ID`,`USER_TYPE_ID`),
  ADD KEY `COMMUNITY_MEMBERS_FK2` (`USER_ID`),
  ADD KEY `COMMUNITY_MEMBERS_FK3` (`USER_TYPE_ID`),
  ADD KEY `COMMUNITY_MEMBERS_FK4` (`INVITED_BY`),
  ADD KEY `COMMUNITY_MEMBERS_FK5` (`LAST_EDITED_BY`),
  ADD KEY `COMMUNITY_MEMBERS_FK6` (`STATUS_ID`);

--
-- Indexes for table `community_members_mod_det`
--
ALTER TABLE `community_members_mod_det`
  ADD PRIMARY KEY (`COMMUNITY_MEMBER_MOD_DET_ID`),
  ADD KEY `COMMUNITY_MEMBERS_MOD_DET_FK1` (`COMMUNITY_MEMBER_ID`),
  ADD KEY `COMMUNITY_MEMBERS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `community_mod_det`
--
ALTER TABLE `community_mod_det`
  ADD PRIMARY KEY (`COMMUNITY_MOD_DET_ID`),
  ADD KEY `COMMUNITY_MOD_DET_FK1` (`COMMUNITY_ID`),
  ADD KEY `COMMUNITY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `community_photos`
--
ALTER TABLE `community_photos`
  ADD PRIMARY KEY (`COMMUNITY_PHOTO_ID`),
  ADD KEY `COMMUNITY_PHOTOS_FK1` (`COMMUNITY_ID`),
  ADD KEY `COMMUNITY_PHOTOS_FK2` (`PHOTO_TYPE_ID`),
  ADD KEY `COMMUNITY_PHOTOS_FK3` (`LAST_EDITED_BY`),
  ADD KEY `COMMUNITY_PHOTOS_FK4` (`STATUS_ID`);

--
-- Indexes for table `community_photos_mod_det`
--
ALTER TABLE `community_photos_mod_det`
  ADD PRIMARY KEY (`COMMUNITY_PHOTO_MOD_DET_ID`),
  ADD KEY `COMMUNITY_PHOTOS_MOD_DET_FK1` (`COMMUNITY_PHOTO_ID`),
  ADD KEY `COMMUNITY_PHOTOS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `community_type_master`
--
ALTER TABLE `community_type_master`
  ADD PRIMARY KEY (`COMMUNITY_TYPE_ID`),
  ADD UNIQUE KEY `COMMUNITY_TYPE_NAME` (`COMMUNITY_TYPE_NAME`),
  ADD KEY `COMMUNITY_TYPE_FK1` (`LAST_EDITED_BY`),
  ADD KEY `COMMUNITY_TYPE_FK2` (`STATUS_ID`);

--
-- Indexes for table `community_type_mod_det`
--
ALTER TABLE `community_type_mod_det`
  ADD PRIMARY KEY (`COMMUNITY_TYPE_MOD_DET_ID`),
  ADD KEY `COMMUNITY_TYPE_MOD_DET_FK1` (`COMMUNITY_TYPE_ID`),
  ADD KEY `COMMUNITY_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `configurations`
--
ALTER TABLE `configurations`
  ADD PRIMARY KEY (`CONFIGURATION_ID`),
  ADD UNIQUE KEY `CONFIGURATION_NAME` (`CONFIGURATION_NAME`),
  ADD KEY `CONFIGURATIONS_FK1` (`LAST_EDITED_BY`),
  ADD KEY `CONFIGURATIONS_FK2` (`STATUS_ID`);

--
-- Indexes for table `configurations_mod_det`
--
ALTER TABLE `configurations_mod_det`
  ADD PRIMARY KEY (`CONFIGURATION_MOD_DET_ID`),
  ADD KEY `CONFIGURATIONS_MOD_DET_FK1` (`CONFIGURATION_ID`),
  ADD KEY `CONFIGURATIONS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `country_master`
--
ALTER TABLE `country_master`
  ADD PRIMARY KEY (`COUNTRY_ID`),
  ADD UNIQUE KEY `SHORT_NAME` (`SHORT_NAME`),
  ADD KEY `COUNTRY_FK1` (`STATUS_ID`),
  ADD KEY `COUNTRY_FK2` (`LAST_EDITED_BY`);

--
-- Indexes for table `country_mod_det`
--
ALTER TABLE `country_mod_det`
  ADD PRIMARY KEY (`COUNTRY_MOD_DET_ID`),
  ADD KEY `COUNTRY_MOD_DET_FK1` (`COUNTRY_ID`),
  ADD KEY `COUNTRY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `cron_task_exec_log`
--
ALTER TABLE `cron_task_exec_log`
  ADD PRIMARY KEY (`CRON_TASK_EXEC_LOG_ID`),
  ADD KEY `CRON_TASK_EXEC_LOG_FK1` (`TASK_ID`),
  ADD KEY `CRON_TASK_EXEC_LOG_FK2` (`LAST_EDITED_BY`),
  ADD KEY `CRON_TASK_EXEC_LOG_FK3` (`STATUS_ID`);

--
-- Indexes for table `cron_task_exec_log_mod_det`
--
ALTER TABLE `cron_task_exec_log_mod_det`
  ADD PRIMARY KEY (`CRON_TASK_EXEC_LOG_MOD_DET_ID`),
  ADD KEY `CRON_TASK_EXEC_LOG_MOD_DET_FK1` (`CRON_TASK_EXEC_LOG_ID`),
  ADD KEY `CRON_TASK_EXEC_LOG_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `cron_tasks`
--
ALTER TABLE `cron_tasks`
  ADD PRIMARY KEY (`TASK_ID`),
  ADD UNIQUE KEY `TASK_TITLE` (`TASK_TITLE`),
  ADD KEY `CRON_TASKS_FK1` (`CREATED_BY`),
  ADD KEY `CRON_TASKS_FK2` (`LAST_EDITED_BY`),
  ADD KEY `CRON_TASKS_FK3` (`STATUS_ID`);

--
-- Indexes for table `cron_tasks_mod_det`
--
ALTER TABLE `cron_tasks_mod_det`
  ADD PRIMARY KEY (`CRON_TASK_MOD_DET_ID`),
  ADD KEY `CRON_TASKS_MOD_DET_FK1` (`TASK_ID`),
  ADD KEY `CRON_TASKS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `dates`
--
ALTER TABLE `dates`
  ADD PRIMARY KEY (`DATE_ID`),
  ADD UNIQUE KEY `DATE_VALUE` (`DATE_VALUE`),
  ADD KEY `DATE_FK1` (`LAST_EDITED_BY`),
  ADD KEY `DATE_FK2` (`STATUS_ID`);

--
-- Indexes for table `dates_mod_det`
--
ALTER TABLE `dates_mod_det`
  ADD PRIMARY KEY (`DATES_MOD_DET_ID`),
  ADD KEY `DATES_MOD_DET_FK1` (`DATE_ID`),
  ADD KEY `DATES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `disease_master`
--
ALTER TABLE `disease_master`
  ADD PRIMARY KEY (`DISEASE_ID`),
  ADD UNIQUE KEY `DISEASE` (`DISEASE`),
  ADD KEY `DISEASE_MASTER_FK1` (`PARENT_DISEASE_ID`),
  ADD KEY `DISEASE_MASTER_FK2` (`STATUS_ID`),
  ADD KEY `DISEASE_MASTER_FK3` (`DISEASE_SURVEY_ID`),
  ADD KEY `DISEASE_MASTER_FK4` (`CREATED_BY`);

--
-- Indexes for table `disease_mod_det`
--
ALTER TABLE `disease_mod_det`
  ADD PRIMARY KEY (`DISEASE_MOD_DET_ID`),
  ADD KEY `DISEASE_MOD_DET_FK1` (`DISEASE_ID`),
  ADD KEY `DISEASE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `disease_symptoms`
--
ALTER TABLE `disease_symptoms`
  ADD PRIMARY KEY (`DISEASE_SYMPTOM_ID`),
  ADD UNIQUE KEY `DISEASE_ID` (`DISEASE_ID`,`SYMPTOM_ID`),
  ADD KEY `DISEASE_SYMPTOMS_FK2` (`CREATED_BY`),
  ADD KEY `DISEASE_SYMPTOMS_FK3` (`STATUS_ID`),
  ADD KEY `DISEASE_SYMPTOMS_FK4` (`SYMPTOM_ID`);

--
-- Indexes for table `disease_symptoms_mod_det`
--
ALTER TABLE `disease_symptoms_mod_det`
  ADD PRIMARY KEY (`DISEASE_SYMPTOM_MOD_DET_ID`),
  ADD KEY `DISEASE_SYMPTOMS_MOD_DET_FK1` (`DISEASE_SYMPTOM_ID`),
  ADD KEY `DISEASE_SYMPTOMS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `disease_type_master`
--
ALTER TABLE `disease_type_master`
  ADD PRIMARY KEY (`DISEASE_TYPE_ID`),
  ADD UNIQUE KEY `DISEASE_TYPE` (`DISEASE_TYPE`),
  ADD KEY `DISEASE_TYPE_MASTER_FK1` (`CREATED_BY`),
  ADD KEY `DISEASE_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `disease_type_mod_det`
--
ALTER TABLE `disease_type_mod_det`
  ADD PRIMARY KEY (`DISEASE_TYPE_MOD_DET_ID`),
  ADD KEY `DISEASE_TYPE_MOD_DET_FK1` (`DISEASE_TYPE_ID`),
  ADD KEY `DISEASE_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `email_attributes`
--
ALTER TABLE `email_attributes`
  ADD PRIMARY KEY (`EMAIL_ATTRIBUTE_ID`),
  ADD KEY `EMAIL_ATTRIBUTES_FK1` (`EMAIL_ID`),
  ADD KEY `EMAIL_ATTRIBUTES_FK2` (`ATTRIBUTE_ID`),
  ADD KEY `EMAIL_ATTRIBUTES_FK3` (`LAST_EDITED_BY`),
  ADD KEY `EMAIL_ATTRIBUTES_FK4` (`STATUS_ID`);

--
-- Indexes for table `email_attributes_mod_det`
--
ALTER TABLE `email_attributes_mod_det`
  ADD PRIMARY KEY (`EMAIL_ATTRIBUTES_MOD_DET_ID`),
  ADD KEY `EMAIL_ATTRIBUTES_MOD_DET_FK1` (`EMAIL_ATTRIBUTE_ID`),
  ADD KEY `EMAIL_ATTRIBUTES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `email_history`
--
ALTER TABLE `email_history`
  ADD PRIMARY KEY (`EMAIL_HISTORY_ID`),
  ADD KEY `EMAIL_HISTORY_FK1` (`EMAIL_TEMPLATE_ID`),
  ADD KEY `EMAIL_HISTORY_FK2` (`CREATED_BY`),
  ADD KEY `EMAIL_HISTORY_FK3` (`PRIORITY_ID`),
  ADD KEY `EMAIL_HISTORY_FK4` (`LAST_EDITED_BY`),
  ADD KEY `EMAIL_HISTORY_FK5` (`STATUS_ID`);

--
-- Indexes for table `email_history_attributes`
--
ALTER TABLE `email_history_attributes`
  ADD PRIMARY KEY (`EMAIL_HISTORY_ATTRIBUTE_ID`),
  ADD KEY `EMAIL_HISTORY_ATTRIBUTES_FK1` (`EMAIL_HISTORY_ID`),
  ADD KEY `EMAIL_HISTORY_ATTRIBUTES_FK2` (`ATTRIBUTE_ID`),
  ADD KEY `EMAIL_HISTORY_ATTRIBUTES_FK3` (`LAST_EDITED_BY`),
  ADD KEY `EMAIL_HISTORY_ATTRIBUTES_FK4` (`STATUS_ID`);

--
-- Indexes for table `email_history_attributes_mod_det`
--
ALTER TABLE `email_history_attributes_mod_det`
  ADD PRIMARY KEY (`EMAIL_HISTORY_ATTRIBUTES_MOD_DET_ID`),
  ADD KEY `EMAIL_HISTORY_ATTRIBUTES_MOD_DET_FK1` (`EMAIL_HISTORY_ATTRIBUTE_ID`),
  ADD KEY `EMAIL_HISTORY_ATTRIBUTES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `email_history_mod_det`
--
ALTER TABLE `email_history_mod_det`
  ADD PRIMARY KEY (`EMAIL_HISTORY_MOD_DET_ID`),
  ADD KEY `EMAIL_HISTORY_MOD_DET_FK1` (`EMAIL_HISTORY_ID`),
  ADD KEY `EMAIL_HISTORY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `email_mod_det`
--
ALTER TABLE `email_mod_det`
  ADD PRIMARY KEY (`EMAIL_MOD_DET_ID`),
  ADD KEY `EMAIL_MOD_DET_FK1` (`EMAIL_ID`),
  ADD KEY `EMAIL_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `email_priority_master`
--
ALTER TABLE `email_priority_master`
  ADD PRIMARY KEY (`EMAIL_PRIORITY_ID`),
  ADD UNIQUE KEY `EMAIL_PRIORITY_DESCR` (`EMAIL_PRIORITY_DESCR`),
  ADD KEY `EMAIL_PRIORITY_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `EMAIL_PRIORITY_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `email_priority_mod_det`
--
ALTER TABLE `email_priority_mod_det`
  ADD PRIMARY KEY (`EMAIL_PRIORITY_MOD_DET_ID`),
  ADD KEY `EMAIL_PRIORITY_MOD_DET_FK1` (`EMAIL_PRIORITY_ID`),
  ADD KEY `EMAIL_PRIORITY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`TEMPLATE_ID`),
  ADD KEY `EMAIL_TEMPLATES_FK1` (`CREATED_BY`),
  ADD KEY `EMAIL_TEMPLATES_FK2` (`LAST_EDITED_BY`),
  ADD KEY `EMAIL_TEMPLATES_FK3` (`STATUS_ID`);

--
-- Indexes for table `email_templates_mod_det`
--
ALTER TABLE `email_templates_mod_det`
  ADD PRIMARY KEY (`EMAIL_TEMPLATE_MOD_DET_ID`),
  ADD KEY `EMAIL_TEMPLATE_MOD_DET_FK1` (`TEMPLATE_ID`),
  ADD KEY `EMAIL_TEMPLATE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`EMAIL_ID`),
  ADD KEY `EMAIL_FK1` (`EMAIL_TEMPLATE_ID`),
  ADD KEY `EMAIL_FK2` (`CREATED_BY`),
  ADD KEY `EMAIL_FK3` (`PRIORITY_ID`),
  ADD KEY `EMAIL_FK4` (`LAST_EDITED_BY`),
  ADD KEY `EMAIL_FK5` (`STATUS_ID`);

--
-- Indexes for table `event_attributes`
--
ALTER TABLE `event_attributes`
  ADD PRIMARY KEY (`EVENT_ATTRIBUTE_ID`),
  ADD KEY `EVENT_ATTRIBUTES_FK2` (`ATTRIBUTE_ID`),
  ADD KEY `EVENT_ATTRIBUTES_FK3` (`LAST_EDITED_BY`),
  ADD KEY `EVENT_ATTRIBUTES_FK4` (`STATUS_ID`),
  ADD KEY `EVENT_ATTRIBUTES_FK1` (`EVENT_ID`);

--
-- Indexes for table `event_attributes_mod_det`
--
ALTER TABLE `event_attributes_mod_det`
  ADD PRIMARY KEY (`EVENT_ATTRIBUTE_MOD_DET_ID`),
  ADD KEY `EVENT_ATTRIBUTES_MOD_DET_FK1` (`EVENT_ATTRIBUTE_ID`),
  ADD KEY `EVENT_ATTRIBUTES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `event_diseases`
--
ALTER TABLE `event_diseases`
  ADD PRIMARY KEY (`EVENT_DISEASE_ID`),
  ADD UNIQUE KEY `EVENT_ID` (`EVENT_ID`,`DISEASE_ID`),
  ADD KEY `EVENT_DISEASES_FK2` (`DISEASE_ID`),
  ADD KEY `EVENT_DISEASES_FK3` (`CREATED_BY`),
  ADD KEY `EVENT_DISEASES_FK4` (`LAST_EDITED_BY`),
  ADD KEY `EVENT_DISEASES_FK5` (`STATUS_ID`);

--
-- Indexes for table `event_diseases_mod_det`
--
ALTER TABLE `event_diseases_mod_det`
  ADD PRIMARY KEY (`EVENT_DISEASE_MOD_DET_ID`),
  ADD KEY `EVENT_DISEASES_MOD_DET_FK1` (`EVENT_DISEASE_ID`),
  ADD KEY `EVENT_DISEASES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `event_members`
--
ALTER TABLE `event_members`
  ADD PRIMARY KEY (`EVENT_MEMBER_ID`),
  ADD UNIQUE KEY `EVENT_ID` (`EVENT_ID`,`MEMBER_ID`,`MEMBER_ROLE_ID`),
  ADD KEY `EVENT_MEMBERS_FK2` (`MEMBER_ID`),
  ADD KEY `EVENT_MEMBERS_FK3` (`MEMBER_ROLE_ID`),
  ADD KEY `EVENT_MEMBERS_FK4` (`INVITED_BY`),
  ADD KEY `EVENT_MEMBERS_FK5` (`CREATED_BY`),
  ADD KEY `EVENT_MEMBERS_FK6` (`LAST_EDITED_BY`),
  ADD KEY `EVENT_MEMBERS_FK7` (`STATUS_ID`);

--
-- Indexes for table `event_members_mod_det`
--
ALTER TABLE `event_members_mod_det`
  ADD PRIMARY KEY (`EVENT_MEMBER_MOD_DET_ID`),
  ADD KEY `EVENT_MEMBERS_MOD_DET_FK1` (`EVENT_MEMBER_ID`),
  ADD KEY `EVENT_MEMBERS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `event_mod_det`
--
ALTER TABLE `event_mod_det`
  ADD PRIMARY KEY (`EVENT_MOD_DET_ID`),
  ADD KEY `EVENT_MOD_DET_FK1` (`EVENT_ID`),
  ADD KEY `EVENT_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `event_type_master`
--
ALTER TABLE `event_type_master`
  ADD PRIMARY KEY (`EVENT_TYPE_ID`),
  ADD UNIQUE KEY `EVENT_TYPE_DESCR` (`EVENT_TYPE_DESCR`),
  ADD KEY `EVENT_TYPE_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `EVENT_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `event_type_mod_det`
--
ALTER TABLE `event_type_mod_det`
  ADD PRIMARY KEY (`EVENT_TYPE_MOD_DET_ID`),
  ADD KEY `EVENT_TYPE_MOD_DET_FK1` (`EVENT_TYPE_ID`),
  ADD KEY `EVENT_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`EVENT_ID`),
  ADD UNIQUE KEY `EVENT_NAME` (`EVENT_NAME`),
  ADD KEY `EVENT_FK1` (`EVENT_TYPE_ID`),
  ADD KEY `EVENT_FK2` (`COMMUNITY_ID`),
  ADD KEY `EVENT_FK3` (`REPEAT_TYPE_ID`),
  ADD KEY `EVENT_FK4` (`CREATED_BY`),
  ADD KEY `EVENT_FK5` (`PUBLISH_TYPE_ID`),
  ADD KEY `EVENT_FK6` (`SECTION_TYPE_ID`),
  ADD KEY `EVENT_FK7` (`SECTION_TEAM_ID`),
  ADD KEY `EVENT_FK8` (`SECTION_COMMUNITY_ID`),
  ADD KEY `EVENT_FK9` (`REPEAT_MODE_TYPE_ID`),
  ADD KEY `EVENT_FK10` (`REPEAT_BY_TYPE_ID`),
  ADD KEY `EVENT_FK11` (`REPEAT_END_TYPE_ID`),
  ADD KEY `EVENT_FK12` (`LAST_EDITED_BY`),
  ADD KEY `EVENT_FK13` (`STATUS_ID`);

--
-- Indexes for table `following_pages_mod_det`
--
ALTER TABLE `following_pages_mod_det`
  ADD PRIMARY KEY (`FOLLOWING_PAGE_MOD_DET_ID`),
  ADD KEY `FOLLOWING_PAGES_MOD_DET_FK1` (`FOLLOWING_PAGE_ID`),
  ADD KEY `FOLLOWING_PAGES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `health_cond_group_mod_det`
--
ALTER TABLE `health_cond_group_mod_det`
  ADD PRIMARY KEY (`HEALTH_COND_GROUP_MOD_DET_ID`),
  ADD KEY `HEALTH_COND_GROUP_MOD_DET_FK1` (`HEALTH_CONDITION_GROUP_ID`),
  ADD KEY `HEALTH_COND_GROUP_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `health_condition_groups`
--
ALTER TABLE `health_condition_groups`
  ADD PRIMARY KEY (`HEALTH_CONDITION_GROUP_ID`),
  ADD UNIQUE KEY `HEALTH_CONDITION_GROUP_DESCR` (`HEALTH_CONDITION_GROUP_DESCR`),
  ADD KEY `HEALTH_CONDITION_GROUPS_FK1` (`STATUS_ID`),
  ADD KEY `HEALTH_CONDITION_GROUPS_FK2` (`LAST_EDITED_BY`);

--
-- Indexes for table `health_condition_master`
--
ALTER TABLE `health_condition_master`
  ADD PRIMARY KEY (`HEALTH_CONDITION_ID`),
  ADD UNIQUE KEY `HEALTH_CONDITION_DESCR` (`HEALTH_CONDITION_DESCR`,`HEALTH_CONDITION_GROUP_ID`),
  ADD KEY `HEALTH_CONDITION_FK1` (`HEALTH_CONDITION_GROUP_ID`),
  ADD KEY `HEALTH_CONDITION_FK2` (`STATUS_ID`),
  ADD KEY `HEALTH_CONDITION_FK3` (`LAST_EDITED_BY`);

--
-- Indexes for table `health_condition_mod_det`
--
ALTER TABLE `health_condition_mod_det`
  ADD PRIMARY KEY (`HEALTH_CONDITION_MOD_DET_ID`),
  ADD KEY `HEALTH_CONDITION_MOD_DET_FK1` (`HEALTH_CONDITION_ID`),
  ADD KEY `HEALTH_CONDITION_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `invited_users`
--
ALTER TABLE `invited_users`
  ADD PRIMARY KEY (`INVITED_USER_ID`),
  ADD UNIQUE KEY `INVITED_USER_EMAIL` (`INVITED_USER_EMAIL`),
  ADD UNIQUE KEY `INVITED_USER_EMAIL_2` (`INVITED_USER_EMAIL`,`INVITED_BY`),
  ADD KEY `INVITED_USERS_FK1` (`INVITED_BY`),
  ADD KEY `INVITED_USERS_FK2` (`STATUS_ID`);

--
-- Indexes for table `invited_users_mod_det`
--
ALTER TABLE `invited_users_mod_det`
  ADD PRIMARY KEY (`INVITED_USERS_MOD_DET_ID`),
  ADD KEY `INVITED_USERS_MOD_DET_FK1` (`INVITED_USER_ID`),
  ADD KEY `INVITED_USERS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `language_mod_det`
--
ALTER TABLE `language_mod_det`
  ADD PRIMARY KEY (`LANGUAGE_MOD_DET_ID`),
  ADD KEY `LANGUAGE_MOD_DET_FK1` (`LANGUAGE_ID`),
  ADD KEY `LANGUAGE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`LANGUAGE_ID`),
  ADD UNIQUE KEY `LANGUAGE` (`LANGUAGE`),
  ADD KEY `LANGUAGE_FK1` (`LAST_EDITED_BY`),
  ADD KEY `LANGUAGE_FK2` (`STATUS_ID`);

--
-- Indexes for table `media_type_master`
--
ALTER TABLE `media_type_master`
  ADD PRIMARY KEY (`MEDIA_TYPE_ID`),
  ADD UNIQUE KEY `MEDIA_TYPE_DESCR` (`MEDIA_TYPE_DESCR`),
  ADD KEY `MEDIA_TYPE_MASTER_FK1` (`CREATED_BY`),
  ADD KEY `MEDIA_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `media_type_mod_det`
--
ALTER TABLE `media_type_mod_det`
  ADD PRIMARY KEY (`MEDIA_TYPE_MOD_DET_ID`),
  ADD KEY `MEDIA_TYPE_MOD_DET_FK1` (`MEDIA_TYPE_ID`),
  ADD KEY `MEDIA_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `message_recipient_roles`
--
ALTER TABLE `message_recipient_roles`
  ADD PRIMARY KEY (`MESSAGE_RECIPIENT_ROLE_ID`),
  ADD UNIQUE KEY `ROLE_DESCR` (`ROLE_DESCR`),
  ADD KEY `MESSAGE_RECIPIENT_ROLES_FK1` (`STATUS_ID`),
  ADD KEY `MESSAGE_RECIPIENT_ROLES_FK2` (`LAST_EDITED_BY`);

--
-- Indexes for table `message_role_mod_det`
--
ALTER TABLE `message_role_mod_det`
  ADD PRIMARY KEY (`MESSAGE_ROLE_MOD_DET_ID`),
  ADD KEY `MESSAGE_ROLE_MOD_DET_FK1` (`MESSAGE_RECIPIENT_ROLE_ID`),
  ADD KEY `MESSAGE_ROLE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `module_master`
--
ALTER TABLE `module_master`
  ADD PRIMARY KEY (`MODULE_ID`),
  ADD UNIQUE KEY `MODULE_DESCR` (`MODULE_DESCR`),
  ADD KEY `MODULE_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `MODULE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `module_mod_det`
--
ALTER TABLE `module_mod_det`
  ADD PRIMARY KEY (`MODULE_MOD_DET_ID`),
  ADD KEY `MODULE_MOD_DET_FK1` (`MODULE_ID`),
  ADD KEY `MODULE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `month_mod_det`
--
ALTER TABLE `month_mod_det`
  ADD PRIMARY KEY (`MONTH_MOD_DET_ID`),
  ADD KEY `MONTH_MOD_DET_FK1` (`MONTH_ID`),
  ADD KEY `MONTH_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `months_master`
--
ALTER TABLE `months_master`
  ADD PRIMARY KEY (`MONTH_ID`),
  ADD UNIQUE KEY `MONTH_NAME` (`MONTH_NAME`),
  ADD UNIQUE KEY `MONTH_ABBREV` (`MONTH_ABBREV`),
  ADD KEY `MONTH_FK1` (`LAST_EDITED_BY`),
  ADD KEY `MONTH_FK2` (`STATUS_ID`);

--
-- Indexes for table `mood_master`
--
ALTER TABLE `mood_master`
  ADD PRIMARY KEY (`USER_MOOD_ID`),
  ADD UNIQUE KEY `USER_MOOD_DESCR` (`USER_MOOD_DESCR`),
  ADD KEY `USER_MOOD_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `USER_MOOD_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `mood_mod_det`
--
ALTER TABLE `mood_mod_det`
  ADD PRIMARY KEY (`MOOD_MOD_DET_ID`),
  ADD KEY `MOOD_MOD_DET_FK1` (`MOOD_ID`),
  ADD KEY `MOOD_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `my_friend_mod_det`
--
ALTER TABLE `my_friend_mod_det`
  ADD PRIMARY KEY (`MY_FRIEND_MOD_DET_ID`),
  ADD KEY `MY_FRIEND_MOD_DET_FK1` (`MY_FRIEND_ID`),
  ADD KEY `MY_FRIEND_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `my_friends`
--
ALTER TABLE `my_friends`
  ADD PRIMARY KEY (`MY_FRIEND_ID`),
  ADD KEY `MY_FRIENDS_FK1` (`MY_USER_ID`),
  ADD KEY `MY_FRIENDS_FK2` (`LAST_EDITED_BY`),
  ADD KEY `MY_FRIENDS_FK3` (`STATUS_ID`);

--
-- Indexes for table `my_friends_detail_mod_det`
--
ALTER TABLE `my_friends_detail_mod_det`
  ADD PRIMARY KEY (`MY_FRIENDS_DETAIL_MOD_DET_ID`),
  ADD KEY `MY_FRIENDS_DETAIL_MOD_DET_FK1` (`MY_FRIENDS_DETAIL_ID`),
  ADD KEY `MY_FRIENDS_DETAIL_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `my_friends_details`
--
ALTER TABLE `my_friends_details`
  ADD PRIMARY KEY (`MY_FRIENDS_DETAIL_ID`),
  ADD UNIQUE KEY `MY_FRIEND_ID` (`MY_FRIEND_ID`,`FRIEND_USER_ID`),
  ADD KEY `MY_FRIENDS_DETAILS_FK2` (`FRIEND_USER_ID`),
  ADD KEY `MY_FRIENDS_DETAILS_FK3` (`LAST_EDITED_BY`),
  ADD KEY `MY_FRIENDS_DETAILS_FK4` (`STATUS_ID`);

--
-- Indexes for table `newsletter_mod_det`
--
ALTER TABLE `newsletter_mod_det`
  ADD PRIMARY KEY (`NEWSLETTER_MOD_DET_ID`),
  ADD KEY `NEWSLETTER_MOD_DET_FK1` (`NEWSLETTER_ID`),
  ADD KEY `NEWSLETTER_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `newsletter_queue_mod_det`
--
ALTER TABLE `newsletter_queue_mod_det`
  ADD PRIMARY KEY (`NEWSLETTER_QUEUE_MOD_DET_ID`),
  ADD KEY `NEWSLETTER_QUEUE_MOD_DET_FK1` (`NEWSLETTER_QUEUE_ID`),
  ADD KEY `NEWSLETTER_QUEUE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `newsletter_queue_status`
--
ALTER TABLE `newsletter_queue_status`
  ADD PRIMARY KEY (`NEWSLETTER_QUEUE_ID`),
  ADD KEY `NEWSLETTER_QUEUE_STATUS_FK1` (`NEWSLETTER_ID`),
  ADD KEY `NEWSLETTER_QUEUE_STATUS_FK2` (`CREATED_BY`),
  ADD KEY `NEWSLETTER_QUEUE_STATUS_FK3` (`LAST_EDITED_BY`),
  ADD KEY `NEWSLETTER_QUEUE_STATUS_FK4` (`STATUS_ID`);

--
-- Indexes for table `newsletter_template_mod_det`
--
ALTER TABLE `newsletter_template_mod_det`
  ADD PRIMARY KEY (`NEWSLETTER_TEMPLATE_MOD_DET_ID`),
  ADD KEY `NEWSLETTER_TEMPLATE_MOD_DET_FK1` (`NEWSLETTER_TEMPLATE_ID`),
  ADD KEY `NEWSLETTER_TEMPLATE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `newsletter_templates`
--
ALTER TABLE `newsletter_templates`
  ADD PRIMARY KEY (`NEWSLETTER_TEMPLATE_ID`),
  ADD KEY `NEWSLETTER_TEMPLATES_FK1` (`CREATED_BY`),
  ADD KEY `NEWSLETTER_TEMPLATES_FK2` (`LAST_EDITED_BY`),
  ADD KEY `NEWSLETTER_TEMPLATES_FK3` (`STATUS_ID`);

--
-- Indexes for table `newsletters`
--
ALTER TABLE `newsletters`
  ADD PRIMARY KEY (`NEWSLETTER_ID`),
  ADD KEY `NEWSLETTERS_FK1` (`CREATED_BY`),
  ADD KEY `NEWSLETTERS_FK2` (`LAST_EDITED_BY`),
  ADD KEY `NEWSLETTERS_FK3` (`STATUS_ID`);

--
-- Indexes for table `notification_activity_mod_det`
--
ALTER TABLE `notification_activity_mod_det`
  ADD PRIMARY KEY (`NOTIFICATION_ACTIVITY_MOD_DET_ID`),
  ADD KEY `NOTIFICATION_ACTIVITY_MOD_DET_FK1` (`NOTIFICATION_ACTIVITY_TYPE_ID`),
  ADD KEY `NOTIFICATION_ACTIVITY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `notification_activity_type_master`
--
ALTER TABLE `notification_activity_type_master`
  ADD PRIMARY KEY (`NOTIFICATION_ACTIVITY_TYPE_ID`),
  ADD UNIQUE KEY `NOTIFICATION_ACTIVITY_TYPE_NAME` (`NOTIFICATION_ACTIVITY_TYPE_NAME`),
  ADD KEY `NOTIFICATION_ACTIVITY_TYPE_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `NOTIFICATION_ACTIVITY_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `notification_frequency_master`
--
ALTER TABLE `notification_frequency_master`
  ADD PRIMARY KEY (`NOTIFICATION_FREQUENCY_ID`),
  ADD UNIQUE KEY `NOTIFICATION_FREQUENCY_NAME` (`NOTIFICATION_FREQUENCY_NAME`),
  ADD KEY `NOTIFICATION_FREQUENCY_FK1` (`LAST_EDITED_BY`),
  ADD KEY `NOTIFICATION_FREQUENCY_FK2` (`STATUS_ID`);

--
-- Indexes for table `notification_frequency_mod_det`
--
ALTER TABLE `notification_frequency_mod_det`
  ADD PRIMARY KEY (`NOTIFICATION_FREQUENCY_MOD_DET_ID`),
  ADD KEY `NOTIFICATION_FREQUENCY_MOD_DET_FK1` (`NOTIFICATION_FREQUENCY_ID`),
  ADD KEY `NOTIFICATION_FREQUENCY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `notification_mod_det`
--
ALTER TABLE `notification_mod_det`
  ADD PRIMARY KEY (`NOTIFICATION_MOD_DET_ID`),
  ADD KEY `NOTIFICATION_MOD_DET_FK1` (`NOTIFICATION_ID`),
  ADD KEY `NOTIFICATION_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `notification_object_type_master`
--
ALTER TABLE `notification_object_type_master`
  ADD PRIMARY KEY (`NOTIFICATION_OBJECT_TYPE_ID`),
  ADD UNIQUE KEY `NOTIFICATION_OBJECT_TYPE_NAME` (`NOTIFICATION_OBJECT_TYPE_NAME`),
  ADD KEY `NOTIFICATION_OBJECT_TYPE_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `NOTIFICATION_OBJECT_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `notification_object_type_mod_det`
--
ALTER TABLE `notification_object_type_mod_det`
  ADD PRIMARY KEY (`NOTIFICATION_OBJECT_TYPE_MOD_DET_ID`),
  ADD KEY `NOTIFICATION_OBJECT_TYPE_MOD_DET_FK1` (`NOTIFICATION_OBJECT_TYPE_ID`),
  ADD KEY `NOTIFICATION_OBJECT_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `notification_recipient_mod_det`
--
ALTER TABLE `notification_recipient_mod_det`
  ADD PRIMARY KEY (`NOTIFICATION_RECIPIENT_MOD_DET_ID`),
  ADD KEY `NOTIFICATION_RECIPIENT_MOD_DET_FK1` (`NOTIFICATION_RECIPIENT_ID`),
  ADD KEY `NOTIFICATION_RECIPIENT_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `notification_recipients`
--
ALTER TABLE `notification_recipients`
  ADD PRIMARY KEY (`NOTIFICATION_RECIPIENT_ID`),
  ADD KEY `NOTIFICATION_RECIPIENT_FK1` (`NOTIFICATION_ID`),
  ADD KEY `NOTIFICATION_RECIPIENT_FK2` (`RECIPIENT_ID`),
  ADD KEY `NOTIFICATION_RECIPIENT_FK3` (`LAST_EDITED_BY`),
  ADD KEY `NOTIFICATION_RECIPIENT_FK4` (`STATUS_ID`);

--
-- Indexes for table `notification_setting_mod_det`
--
ALTER TABLE `notification_setting_mod_det`
  ADD PRIMARY KEY (`NOTIFICATION_SETTING_MOD_DET_ID`),
  ADD KEY `NOTIFICATION_SETTING_MOD_DET_FK1` (`NOTIFICATION_SETTING_ID`),
  ADD KEY `NOTIFICATION_SETTING_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `notification_settings`
--
ALTER TABLE `notification_settings`
  ADD PRIMARY KEY (`NOTIFICATION_SETTING_ID`),
  ADD KEY `NOTIFICATION_SETTING_FK1` (`USER_ID`),
  ADD KEY `NOTIFICATION_SETTING_FK2` (`HEIGHT_UNIT`),
  ADD KEY `NOTIFICATION_SETTING_FK3` (`WEIGHT_UNIT`),
  ADD KEY `NOTIFICATION_SETTING_FK4` (`TEMP_UNIT`),
  ADD KEY `NOTIFICATION_SETTING_FK5` (`NOTIFICATION_FREQUENCY_ID`),
  ADD KEY `NOTIFICATION_SETTING_FK6` (`LAST_EDITED_BY`),
  ADD KEY `NOTIFICATION_SETTING_FK7` (`STATUS_ID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`NOTIFICATION_ID`),
  ADD KEY `NOTIFICATION_FK1` (`NOTIFICATION_ACTIVITY_TYPE_ID`),
  ADD KEY `NOTIFICATION_FK2` (`NOTIFICATION_OBJECT_TYPE_ID`),
  ADD KEY `NOTIFICATION_FK4` (`SENDER_ID`),
  ADD KEY `NOTIFICATION_FK5` (`OBJECT_OWNER_ID`),
  ADD KEY `NOTIFICATION_FK6` (`CREATED_BY`),
  ADD KEY `NOTIFICATION_FK7` (`LAST_EDITED_BY`),
  ADD KEY `NOTIFICATION_FK8` (`STATUS_ID`),
  ADD KEY `NOTIFICATION_FK3` (`NOTIFICATION_ACTIVITY_SECTION_TYPE_ID`);

--
-- Indexes for table `notified_user_mod_det`
--
ALTER TABLE `notified_user_mod_det`
  ADD PRIMARY KEY (`NOTIFIED_USER_MOD_DET_ID`),
  ADD KEY `NOTIFIED_USER_MOD_DET_FK1` (`NOTIFIED_USER_ID`),
  ADD KEY `NOTIFIED_USER_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `notified_users`
--
ALTER TABLE `notified_users`
  ADD PRIMARY KEY (`NOTIFIED_USER_ID`),
  ADD UNIQUE KEY `NOTIFICATION_SETTING_ID` (`NOTIFICATION_SETTING_ID`,`USER_ID`),
  ADD KEY `NOTIFIED_USER_FK1` (`USER_ID`),
  ADD KEY `NOTIFIED_USER_FK3` (`LAST_EDITED_BY`),
  ADD KEY `NOTIFIED_USER_FK4` (`STATUS_ID`);

--
-- Indexes for table `page_master`
--
ALTER TABLE `page_master`
  ADD PRIMARY KEY (`PAGE_ID`),
  ADD UNIQUE KEY `PAGE_TYPE_ID` (`PAGE_TYPE_ID`,`PAGE_DESCR`),
  ADD KEY `PAGE_MASTER_FK2` (`LAST_EDITED_BY`),
  ADD KEY `PAGE_MASTER_FK3` (`STATUS_ID`);

--
-- Indexes for table `page_mod_det`
--
ALTER TABLE `page_mod_det`
  ADD PRIMARY KEY (`PAGE_MOD_DET_ID`),
  ADD KEY `PAGE_MOD_DET_FK1` (`PAGE_ID`),
  ADD KEY `PAGE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `page_type_master`
--
ALTER TABLE `page_type_master`
  ADD PRIMARY KEY (`PAGE_TYPE_ID`),
  ADD UNIQUE KEY `PAGE_TYPE_DESCR` (`PAGE_TYPE_DESCR`),
  ADD KEY `PAGE_TYPE_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `PAGE_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `page_type_mod_det`
--
ALTER TABLE `page_type_mod_det`
  ADD PRIMARY KEY (`PAGE_TYPE_MOD_DET_ID`),
  ADD KEY `PAGE_TYPE_MOD_DET_FK1` (`PAGE_TYPE_ID`),
  ADD KEY `PAGE_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `pain_level_mod_det`
--
ALTER TABLE `pain_level_mod_det`
  ADD PRIMARY KEY (`PAIN_LEVEL_MOD_DET_ID`),
  ADD KEY `PAIN_LEVEL_MOD_DET_FK1` (`PAIN_LEVEL_ID`),
  ADD KEY `PAIN_LEVEL_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `pain_levels_master`
--
ALTER TABLE `pain_levels_master`
  ADD PRIMARY KEY (`PAIN_LEVEL_ID`),
  ADD UNIQUE KEY `PAIN_ID` (`PAIN_ID`,`PAIN_LEVEL_DESCR`),
  ADD KEY `PAIN_LEVELS_MASTER_FK2` (`CREATED_BY`),
  ADD KEY `PAIN_LEVELS_MASTER_FK3` (`STATUS_ID`);

--
-- Indexes for table `pain_master`
--
ALTER TABLE `pain_master`
  ADD PRIMARY KEY (`PAIN_ID`),
  ADD UNIQUE KEY `PAIN_DESCR` (`PAIN_DESCR`),
  ADD KEY `PAIN_MASTER_FK1` (`CREATED_BY`),
  ADD KEY `PAIN_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `pain_type_mod_det`
--
ALTER TABLE `pain_type_mod_det`
  ADD PRIMARY KEY (`PAIN_TYPE_MOD_DET_ID`),
  ADD KEY `PAIN_TYPE_MOD_DET_FK1` (`PAIN_ID`),
  ADD KEY `PAIN_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `patient_care_giver_mod_det`
--
ALTER TABLE `patient_care_giver_mod_det`
  ADD PRIMARY KEY (`PATIENT_CARE_GIVER_MOD_DET_ID`),
  ADD KEY `PATIENT_CARE_GIVER_MOD_DET_FK1` (`PATIENT_CARE_GIVER_ID`),
  ADD KEY `PATIENT_CARE_GIVER_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `patient_care_givers`
--
ALTER TABLE `patient_care_givers`
  ADD PRIMARY KEY (`PATIENT_CARE_GIVER_ID`),
  ADD KEY `PATIENT_CARE_GIVERS_FK1` (`RELATIONSHIP_ID`),
  ADD KEY `PATIENT_CARE_GIVERS_FK2` (`USER_ID`),
  ADD KEY `PATIENT_CARE_GIVERS_FK3` (`PATIENT_ID`),
  ADD KEY `PATIENT_CARE_GIVERS_FK4` (`LAST_EDITED_BY`),
  ADD KEY `PATIENT_CARE_GIVERS_FK5` (`STATUS_ID`);

--
-- Indexes for table `photo_type_master`
--
ALTER TABLE `photo_type_master`
  ADD PRIMARY KEY (`PHOTO_TYPE_ID`),
  ADD UNIQUE KEY `PHOTO_TYPE` (`PHOTO_TYPE`),
  ADD KEY `PHOTO_TYPE_MASTER_FK1` (`CREATED_BY`),
  ADD KEY `PHOTO_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `photo_type_mod_det`
--
ALTER TABLE `photo_type_mod_det`
  ADD PRIMARY KEY (`PHOTO_TYPE_MOD_DET_ID`),
  ADD KEY `PHOTO_TYPE_MOD_DET_FK1` (`PHOTO_TYPE_ID`),
  ADD KEY `PHOTO_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `poll_choice_mod_det`
--
ALTER TABLE `poll_choice_mod_det`
  ADD PRIMARY KEY (`POLL_CHOICE_MOD_DET_ID`),
  ADD KEY `POLL_CHOICE_MOD_DET_FK1` (`POLL_CHOICE_ID`),
  ADD KEY `POLL_CHOICE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `poll_choices`
--
ALTER TABLE `poll_choices`
  ADD PRIMARY KEY (`POLL_CHOICE_ID`),
  ADD KEY `POLL_CHOICE_FK1` (`POLL_ID`),
  ADD KEY `POLL_CHOICE_FK2` (`LAST_EDITED_BY`),
  ADD KEY `POLL_CHOICE_FK3` (`STATUS_ID`);

--
-- Indexes for table `poll_mod_det`
--
ALTER TABLE `poll_mod_det`
  ADD PRIMARY KEY (`POLL_MOD_DET_ID`),
  ADD KEY `POLL_MOD_DET_FK1` (`POLL_ID`),
  ADD KEY `POLL_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `polls`
--
ALTER TABLE `polls`
  ADD PRIMARY KEY (`POLL_ID`),
  ADD KEY `POLL_FK1` (`POLL_SECTION_TYPE_ID`),
  ADD KEY `POLL_FK2` (`CREATED_BY`),
  ADD KEY `POLL_FK3` (`LAST_EDITED_BY`),
  ADD KEY `POLL_FK4` (`STATUS_ID`);

--
-- Indexes for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`POST_COMMENT_ID`),
  ADD KEY `POST_COMMENTS_FK1` (`POST_ID`),
  ADD KEY `POST_COMMENTS_FK2` (`LAST_EDITED_BY`),
  ADD KEY `POST_COMMENTS_FK3` (`STATUS_ID`);

--
-- Indexes for table `post_comments_mod_det`
--
ALTER TABLE `post_comments_mod_det`
  ADD PRIMARY KEY (`POST_COMMENT_MOD_DET_ID`),
  ADD KEY `POST_COMMENTS_MOD_DET_FK1` (`POST_COMMENT_ID`),
  ADD KEY `POST_COMMENTS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `post_content_details`
--
ALTER TABLE `post_content_details`
  ADD PRIMARY KEY (`POST_CONTENT_ID`),
  ADD KEY `POST_CONTENT_DETAILS_FK1` (`POST_ID`),
  ADD KEY `POST_CONTENT_DETAILS_FK2` (`CONTENT_ATTRIBUTE_ID`);

--
-- Indexes for table `post_content_mod_det`
--
ALTER TABLE `post_content_mod_det`
  ADD PRIMARY KEY (`POST_CONTENT_MOD_DET_ID`),
  ADD KEY `POST_CONTENT_MOD_DET_FK1` (`POST_CONTENT_ID`),
  ADD KEY `POST_CONTENT_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`POST_LIKE_ID`),
  ADD UNIQUE KEY `POST_ID` (`POST_ID`,`LIKED_BY`),
  ADD KEY `POST_LIKES_FK2` (`LIKED_BY`),
  ADD KEY `POST_LIKES_FK3` (`POST_LIKE_STATUS`);

--
-- Indexes for table `post_likes_mod_det`
--
ALTER TABLE `post_likes_mod_det`
  ADD PRIMARY KEY (`POST_LIKE_MOD_DET_ID`),
  ADD KEY `POST_LIKES_MOD_DET_FK1` (`POST_LIKE_ID`),
  ADD KEY `POST_LIKES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `post_location`
--
ALTER TABLE `post_location`
  ADD PRIMARY KEY (`POST_LOCATION_ID`),
  ADD UNIQUE KEY `POST_ID` (`POST_ID`,`POST_LOCATION`),
  ADD KEY `POST_LOCATION_FK2` (`POST_LOCATION`),
  ADD KEY `POST_LOCATION_FK3` (`LAST_EDITED_BY`),
  ADD KEY `POST_LOCATION_FK4` (`STATUS_ID`);

--
-- Indexes for table `post_location_master`
--
ALTER TABLE `post_location_master`
  ADD PRIMARY KEY (`POST_LOCATION_ID`),
  ADD UNIQUE KEY `POST_LOCATION_DESCR` (`POST_LOCATION_DESCR`),
  ADD KEY `POST_LOCATION_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `POST_LOCATION_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `post_location_master_mod_det`
--
ALTER TABLE `post_location_master_mod_det`
  ADD PRIMARY KEY (`POST_LOCATION_MASTER_MOD_DET_ID`),
  ADD KEY `POST_LOCATION_MASTER_MOD_DET_FK1` (`POST_LOCATION_ID`),
  ADD KEY `POST_LOCATION_MASTER_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `post_location_mod_det`
--
ALTER TABLE `post_location_mod_det`
  ADD PRIMARY KEY (`POST_LOCATION_MOD_DET_ID`),
  ADD KEY `POST_LOCATION_MOD_DET_FK1` (`POST_LOCATION_ID`),
  ADD KEY `POST_LOCATION_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `post_mod_det`
--
ALTER TABLE `post_mod_det`
  ADD PRIMARY KEY (`POST_MOD_DET_ID`),
  ADD KEY `POST_MOD_DET_FK1` (`POST_ID`),
  ADD KEY `POST_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `post_privacy_mod_det`
--
ALTER TABLE `post_privacy_mod_det`
  ADD PRIMARY KEY (`POST_PRIVACY_MOD_DET_ID`),
  ADD KEY `POST_PRIVACY_MOD_DET_FK1` (`POST_PRIVACY_ID`),
  ADD KEY `POST_PRIVACY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `post_privacy_settings`
--
ALTER TABLE `post_privacy_settings`
  ADD PRIMARY KEY (`POST_PRIVACY_ID`),
  ADD UNIQUE KEY `POST_ID` (`POST_ID`,`USER_TYPE_ID`),
  ADD KEY `POST_PRIVACY_FK2` (`USER_TYPE_ID`),
  ADD KEY `POST_PRIVACY_FK3` (`PRIVACY_ID`),
  ADD KEY `POST_PRIVACY_FK4` (`LAST_EDITED_BY`),
  ADD KEY `POST_PRIVACY_FK5` (`STATUS_ID`);

--
-- Indexes for table `post_type_master`
--
ALTER TABLE `post_type_master`
  ADD PRIMARY KEY (`POST_TYPE_ID`),
  ADD UNIQUE KEY `POST_TYPE_TEXT` (`POST_TYPE_TEXT`),
  ADD KEY `POST_TYPE_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `POST_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `post_type_mod_det`
--
ALTER TABLE `post_type_mod_det`
  ADD PRIMARY KEY (`POST_TYPE_MOD_DET_ID`),
  ADD KEY `POST_TYPE_MOD_DET_FK1` (`POST_TYPE_ID`),
  ADD KEY `POST_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`POST_ID`),
  ADD KEY `POSTS_FK2` (`CREATED_BY`),
  ADD KEY `POSTS_FK5` (`POST_TYPE_ID`),
  ADD KEY `POSTS_FK1` (`STATUS_ID`);

--
-- Indexes for table `privacy_master`
--
ALTER TABLE `privacy_master`
  ADD PRIMARY KEY (`PRIVACY_ID`),
  ADD UNIQUE KEY `PRIVAVCY_TEXT` (`PRIVACY_TEXT`),
  ADD KEY `PRIVACY_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `PRIVACY_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `privacy_mod_det`
--
ALTER TABLE `privacy_mod_det`
  ADD PRIMARY KEY (`PRIVACY_MOD_DET_ID`),
  ADD KEY `PRIVACY_MOD_DET_FK1` (`PRIVACY_ID`),
  ADD KEY `PRIVACY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `publish_type_master`
--
ALTER TABLE `publish_type_master`
  ADD PRIMARY KEY (`PUBLISH_TYPE_ID`),
  ADD UNIQUE KEY `PUBLISH_TYPE_DESCR` (`PUBLISH_TYPE_DESCR`),
  ADD KEY `PUBLISH_TYPE_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `PUBLISH_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `publish_type_mod_det`
--
ALTER TABLE `publish_type_mod_det`
  ADD PRIMARY KEY (`PUBLISH_TYPE_MOD_DET_ID`),
  ADD KEY `PUBLISH_TYPE_MOD_DET_FK1` (`PUBLISH_TYPE_ID`),
  ADD KEY `PUBLISH_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `question_group_master`
--
ALTER TABLE `question_group_master`
  ADD PRIMARY KEY (`QUESTION_GROUP_ID`),
  ADD UNIQUE KEY `QUESTION_GROUP` (`QUESTION_GROUP`),
  ADD KEY `QUESTION_GROUP_FK1` (`STATUS_ID`),
  ADD KEY `QUESTION_GROUP_FK2` (`LAST_EDITED_BY`);

--
-- Indexes for table `question_group_mod_det`
--
ALTER TABLE `question_group_mod_det`
  ADD PRIMARY KEY (`QUESTION_GROUP_MOD_DET_ID`),
  ADD KEY `QUESTION_GROUP_MOD_DET_FK1` (`QUESTION_GROUP_ID`),
  ADD KEY `QUESTION_GROUP_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `question_master`
--
ALTER TABLE `question_master`
  ADD PRIMARY KEY (`QUESTION_ID`),
  ADD UNIQUE KEY `QUESTION_TEXT` (`QUESTION_TEXT`,`QUESTION_GROUP_ID`),
  ADD KEY `QUESTION_FK1` (`STATUS_ID`),
  ADD KEY `QUESTION_FK2` (`QUESTION_GROUP_ID`),
  ADD KEY `QUESTION_FK3` (`LAST_EDITED_BY`);

--
-- Indexes for table `question_mod_det`
--
ALTER TABLE `question_mod_det`
  ADD PRIMARY KEY (`QUESTION_MOD_DET_ID`),
  ADD KEY `QUESTION_MOD_DET_FK1` (`QUESTION_ID`),
  ADD KEY `QUESTION_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `repeat_by_type_master`
--
ALTER TABLE `repeat_by_type_master`
  ADD PRIMARY KEY (`REPEAT_BY_TYPE_ID`),
  ADD KEY `REPEAT_BY_TYPE_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `REPEAT_BY_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `repeat_by_type_mod_det`
--
ALTER TABLE `repeat_by_type_mod_det`
  ADD PRIMARY KEY (`REPEAT_BY_TYPE_MOD_DET_ID`),
  ADD KEY `REPEAT_BY_TYPE_MOD_DET_FK1` (`REPEAT_BY_TYPE_ID`),
  ADD KEY `REPEAT_BY_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `repeat_end_type_master`
--
ALTER TABLE `repeat_end_type_master`
  ADD PRIMARY KEY (`REPEAT_END_TYPE_ID`),
  ADD UNIQUE KEY `REPEAT_END_TYPE_DESCR` (`REPEAT_END_TYPE_DESCR`),
  ADD KEY `REPEAT_END_TYPE_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `REPEAT_END_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `repeat_end_type_mod_det`
--
ALTER TABLE `repeat_end_type_mod_det`
  ADD PRIMARY KEY (`REPEAT_END_TYPE_MOD_DET_ID`),
  ADD KEY `REPEAT_END_TYPE_MOD_DET_FK1` (`REPEAT_END_TYPE_ID`),
  ADD KEY `REPEAT_END_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `repeat_mode_type_master`
--
ALTER TABLE `repeat_mode_type_master`
  ADD PRIMARY KEY (`REPEAT_MODE_TYPE_ID`),
  ADD UNIQUE KEY `REPEAT_MODE_TYPE_DESCR` (`REPEAT_MODE_TYPE_DESCR`),
  ADD KEY `REPEAT_MODE_TYPE_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `REPEAT_MODE_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `repeat_mode_type_mod_det`
--
ALTER TABLE `repeat_mode_type_mod_det`
  ADD PRIMARY KEY (`REPEAT_MODE_TYPE_MOD_DET_ID`),
  ADD KEY `REPEAT_MODE_TYPE_MOD_DET_FK1` (`REPEAT_MODE_TYPE_ID`),
  ADD KEY `REPEAT_MODE_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `repeat_type_master`
--
ALTER TABLE `repeat_type_master`
  ADD PRIMARY KEY (`REPEAT_TYPE_ID`),
  ADD UNIQUE KEY `REPEAT_TYPE_DESCR` (`REPEAT_TYPE_DESCR`),
  ADD KEY `REPEAT_TYPE_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `REPEAT_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `repeat_type_mod_det`
--
ALTER TABLE `repeat_type_mod_det`
  ADD PRIMARY KEY (`REPEAT_TYPE_MOD_DET_ID`),
  ADD KEY `REPEAT_TYPE_MOD_DET_FK1` (`REPEAT_TYPE_ID`),
  ADD KEY `REPEAT_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `section_type_master`
--
ALTER TABLE `section_type_master`
  ADD PRIMARY KEY (`SECTION_TYPE_ID`),
  ADD UNIQUE KEY `SECTION_TYPE_DESCR` (`SECTION_TYPE_DESCR`),
  ADD KEY `SECTION_TYPE_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `SECTION_TYPE_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `section_type_mod_det`
--
ALTER TABLE `section_type_mod_det`
  ADD PRIMARY KEY (`SECTION_TYPE_MOD_DET_ID`),
  ADD KEY `SECTION_TYPE_MOD_DET_FK1` (`SECTION_TYPE_ID`),
  ADD KEY `SECTION_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `states_master`
--
ALTER TABLE `states_master`
  ADD PRIMARY KEY (`STATE_ID`),
  ADD UNIQUE KEY `COUNTRY_ID` (`COUNTRY_ID`,`DESCRIPTION`),
  ADD KEY `STATES_FK2` (`CREATED_BY`),
  ADD KEY `STATES_FK3` (`MODIFIED_BY`),
  ADD KEY `STATES_FK4` (`STATUS_ID`);

--
-- Indexes for table `states_mod_det`
--
ALTER TABLE `states_mod_det`
  ADD PRIMARY KEY (`STATES_MOD_DET_ID`),
  ADD KEY `STATES_MOD_DET_FK1` (`STATE_ID`),
  ADD KEY `STATES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`STATUS_ID`),
  ADD UNIQUE KEY `STATUS` (`STATUS`,`STATUS_TYPE_ID`),
  ADD KEY `STATUS_FK1` (`STATUS_TYPE_ID`),
  ADD KEY `STATUS_FK2` (`LAST_EDITED_BY`);

--
-- Indexes for table `status_mod_det`
--
ALTER TABLE `status_mod_det`
  ADD PRIMARY KEY (`STATUS_MOD_DET_ID`),
  ADD KEY `STATUS_MOD_DET_FK1` (`STATUS_ID`),
  ADD KEY `STATUS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `status_type`
--
ALTER TABLE `status_type`
  ADD PRIMARY KEY (`STATUS_TYPE_ID`),
  ADD UNIQUE KEY `STATUS_TYPE` (`STATUS_TYPE`),
  ADD KEY `STATUS_TYPE_FK1` (`LAST_EDITED_BY`);

--
-- Indexes for table `status_type_mod_det`
--
ALTER TABLE `status_type_mod_det`
  ADD PRIMARY KEY (`STATUS_TYPE_MOD_DET_ID`),
  ADD KEY `STATUS_TYPE_MOD_DET_FK1` (`STATUS_TYPE_ID`),
  ADD KEY `STATUS_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `survey_master`
--
ALTER TABLE `survey_master`
  ADD PRIMARY KEY (`SURVEY_ID`),
  ADD UNIQUE KEY `SURVEY_NAME` (`SURVEY_NAME`),
  ADD KEY `SURVEY_MASTER_FK1` (`SURVEY_TYPE`),
  ADD KEY `SURVEY_MASTER_FK2` (`SURVEY_STATUS`),
  ADD KEY `SURVEY_MASTER_FK3` (`CREATED_BY`);

--
-- Indexes for table `survey_mod_det`
--
ALTER TABLE `survey_mod_det`
  ADD PRIMARY KEY (`SURVEY_MOD_DET_ID`),
  ADD KEY `SURVEY_MOD_DET_FK1` (`SURVEY_ID`),
  ADD KEY `SURVEY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `survey_questions`
--
ALTER TABLE `survey_questions`
  ADD PRIMARY KEY (`SURVEY_QUESTION_ID`),
  ADD UNIQUE KEY `SURVEY_ID` (`SURVEY_ID`,`QUESTION_ID`),
  ADD KEY `SURVEY_QUESTIONS_FK2` (`QUESTION_ID`),
  ADD KEY `SURVEY_QUESTIONS_FK3` (`CREATED_BY`),
  ADD KEY `SURVEY_QUESTIONS_FK4` (`STATUS_ID`);

--
-- Indexes for table `survey_questions_answer_choices`
--
ALTER TABLE `survey_questions_answer_choices`
  ADD PRIMARY KEY (`SURVEY_QUESTIONS_ANSWER_CHOICE_ID`),
  ADD UNIQUE KEY `SURVEY_QUESTION_ID` (`SURVEY_QUESTION_ID`,`ANSWER_CHOICE_TEXT`),
  ADD KEY `SURVEY_QUESTIONS_ANSWER_CHOICES_FK2` (`STATUS_ID`),
  ADD KEY `SURVEY_QUESTIONS_ANSWER_CHOICES_FK3` (`CREATED_BY`);

--
-- Indexes for table `survey_questions_answer_choices_mod_det`
--
ALTER TABLE `survey_questions_answer_choices_mod_det`
  ADD PRIMARY KEY (`SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET_ID`),
  ADD KEY `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET_FK1` (`SURVEY_QUESTIONS_ANSWER_CHOICE_ID`),
  ADD KEY `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `survey_questions_mod_det`
--
ALTER TABLE `survey_questions_mod_det`
  ADD PRIMARY KEY (`SURVEY_QUESTIONS_MOD_DET_ID`),
  ADD KEY `SURVEY_QUESTIONS_MOD_DET_FK1` (`SURVEY_QUESTION_ID`),
  ADD KEY `SURVEY_QUESTIONS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `survey_results_answer_choices`
--
ALTER TABLE `survey_results_answer_choices`
  ADD PRIMARY KEY (`SURVEY_RESULTS_ANSWER_CHOICE_ID`),
  ADD UNIQUE KEY `USER_ID` (`USER_ID`,`SURVEY_QUESTIONS_ANSWER_CHOICE_ID`),
  ADD KEY `SURVEY_RESULTS_ANSWER_CHOICES_FK1` (`SURVEY_QUESTIONS_ANSWER_CHOICE_ID`),
  ADD KEY `SURVEY_RESULTS_ANSWER_CHOICES_FK3` (`STATUS_ID`),
  ADD KEY `SURVEY_RESULTS_ANSWER_CHOICES_FK4` (`LAST_EDITED_BY`);

--
-- Indexes for table `survey_results_answer_choices_mod_det`
--
ALTER TABLE `survey_results_answer_choices_mod_det`
  ADD PRIMARY KEY (`SURVEY_RESULTS_ANSWER_CHOICE_MOD_DET_ID`),
  ADD KEY `SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET_FK1` (`SURVEY_RESULTS_ANSWER_CHOICE_ID`),
  ADD KEY `SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `survey_results_detailed_answers`
--
ALTER TABLE `survey_results_detailed_answers`
  ADD PRIMARY KEY (`SURVEY_RESULTS_DETAILED_ANSWER_ID`),
  ADD UNIQUE KEY `USER_ID` (`USER_ID`,`SURVEY_QUESTION_ID`),
  ADD KEY `SURVEY_RESULTS_DETAILED_ANSWERS_FK1` (`SURVEY_QUESTION_ID`),
  ADD KEY `SURVEY_RESULTS_DETAILED_ANSWERS_FK3` (`STATUS_ID`),
  ADD KEY `SURVEY_RESULTS_DETAILED_ANSWERS_FK4` (`LAST_EDITED_BY`);

--
-- Indexes for table `survey_results_detailed_answers_mod_det`
--
ALTER TABLE `survey_results_detailed_answers_mod_det`
  ADD PRIMARY KEY (`SURVEY_RESULTS_DETAILED_ANSWER_MOD_DET_ID`),
  ADD KEY `SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET_FK1` (`SURVEY_RESULTS_DETAILED_ANSWER_ID`),
  ADD KEY `SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `survey_type_master`
--
ALTER TABLE `survey_type_master`
  ADD PRIMARY KEY (`SURVEY_TYPE_ID`),
  ADD UNIQUE KEY `SURVEY_TYPE` (`SURVEY_TYPE`),
  ADD KEY `SURVEY_TYPE_MASTER_FK1` (`STATUS_ID`),
  ADD KEY `SURVEY_TYPE_MASTER_FK2` (`LAST_EDITED_BY`);

--
-- Indexes for table `survey_type_mod_det`
--
ALTER TABLE `survey_type_mod_det`
  ADD PRIMARY KEY (`SURVEY_TYPE_MOD_DET_ID`),
  ADD KEY `SURVEY_TYPE_MOD_DET_FK1` (`SURVEY_TYPE_ID`),
  ADD KEY `SURVEY_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `symptoms_master`
--
ALTER TABLE `symptoms_master`
  ADD PRIMARY KEY (`SYMPTOM_ID`),
  ADD UNIQUE KEY `SYMPTOM` (`SYMPTOM`),
  ADD KEY `SYMPTOMS_MASTER_FK1` (`CREATED_BY`),
  ADD KEY `SYMPTOMS_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `symptoms_mod_det`
--
ALTER TABLE `symptoms_mod_det`
  ADD PRIMARY KEY (`SYMPTOM_MOD_DET_ID`),
  ADD KEY `SYMPTOM_MOD_DET_FK1` (`SYMPTOM_ID`),
  ADD KEY `SYMPTOM_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`TEAM_MEMBER_ID`),
  ADD UNIQUE KEY `USER_ID` (`USER_ID`,`TEAM_ID`,`USER_ROLE_ID`),
  ADD KEY `TEAM_MEMBERS_FK1` (`TEAM_ID`),
  ADD KEY `TEAM_MEMBERS_FK3` (`USER_ROLE_ID`),
  ADD KEY `TEAM_MEMBERS_FK4` (`MEMBER_STATUS`),
  ADD KEY `TEAM_MEMBERS_FK5` (`LAST_EDITED_BY`);

--
-- Indexes for table `team_members_mod_det`
--
ALTER TABLE `team_members_mod_det`
  ADD PRIMARY KEY (`TEAM_MEMBER_MOD_DET_ID`),
  ADD KEY `TEAM_MEMBERS_MOD_DET_FK1` (`TEAM_MEMBER_ID`),
  ADD KEY `TEAM_MEMBERS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `team_mod_det`
--
ALTER TABLE `team_mod_det`
  ADD PRIMARY KEY (`TEAM_MOD_DET_ID`),
  ADD KEY `TEAM_MOD_DET_FK1` (`TEAM_ID`),
  ADD KEY `TEAM_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `team_privacy_setting_mod_det`
--
ALTER TABLE `team_privacy_setting_mod_det`
  ADD PRIMARY KEY (`TEAM_PRIVACY_SETTING_MOD_DET_ID`),
  ADD KEY `TEAM_PRIVACY_SETTING_MOD_DET_FK1` (`TEAM_PRIVACY_SETTING_ID`),
  ADD KEY `TEAM_PRIVACY_SETTING_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `team_privacy_settings`
--
ALTER TABLE `team_privacy_settings`
  ADD PRIMARY KEY (`TEAM_PRIVACY_SETTING_ID`),
  ADD UNIQUE KEY `TEAM_ID` (`TEAM_ID`,`USER_TYPE_ID`),
  ADD KEY `TEAM_PRIVACY_SETTINGS_FK2` (`USER_TYPE_ID`),
  ADD KEY `TEAM_PRIVACY_SETTINGS_FK3` (`PRIVACY_ID`),
  ADD KEY `TEAM_PRIVACY_SETTINGS_FK4` (`PRIVACY_SET_BY`),
  ADD KEY `TEAM_PRIVACY_SETTINGS_FK5` (`STATUS_ID`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`TEAM_ID`),
  ADD UNIQUE KEY `TEAM_NAME` (`TEAM_NAME`,`PATIENT_ID`),
  ADD KEY `TEAMS_FK1` (`PATIENT_ID`),
  ADD KEY `TEAMS_FK2` (`CREATED_BY`),
  ADD KEY `TEAMS_FK3` (`TEAM_STATUS`);

--
-- Indexes for table `timezone_master`
--
ALTER TABLE `timezone_master`
  ADD PRIMARY KEY (`TIMEZONE_ID`),
  ADD UNIQUE KEY `TIMEZONE` (`TIMEZONE`),
  ADD KEY `TIMEZONE_FK1` (`LAST_EDITED_BY`),
  ADD KEY `TIMEZONE_FK2` (`STATUS_ID`);

--
-- Indexes for table `timezone_mod_det`
--
ALTER TABLE `timezone_mod_det`
  ADD PRIMARY KEY (`TIMEZONE_MOD_DET_ID`),
  ADD KEY `TIMEZONE_MOD_DET_FK1` (`TIMEZONE_ID`),
  ADD KEY `TIMEZONE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `treatment_master`
--
ALTER TABLE `treatment_master`
  ADD PRIMARY KEY (`TREATMENT_ID`),
  ADD UNIQUE KEY `TREATMENT_DESCR` (`TREATMENT_DESCR`),
  ADD KEY `TREATMENT_MASTER_FK1` (`CREATED_BY`),
  ADD KEY `TREATMENT_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `treatment_master_mod_det`
--
ALTER TABLE `treatment_master_mod_det`
  ADD PRIMARY KEY (`TREATMENT_MASTER_MOD_DET_ID`),
  ADD KEY `TREATMENT_MASTER_MOD_DET_FK1` (`TREATMENT_ID`),
  ADD KEY `TREATMENT_MASTER_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `unit_of_measurement_master`
--
ALTER TABLE `unit_of_measurement_master`
  ADD PRIMARY KEY (`UNIT_ID`),
  ADD UNIQUE KEY `UNIT_DESCR` (`UNIT_DESCR`),
  ADD KEY `UOM_FK1` (`STATUS_ID`),
  ADD KEY `UOM_FK2` (`CREATED_BY`);

--
-- Indexes for table `unit_of_measurement_mod_det`
--
ALTER TABLE `unit_of_measurement_mod_det`
  ADD PRIMARY KEY (`UNIT_OF_MEASUREMENT_MOD_DET_ID`),
  ADD KEY `UOM_MOD_DET_FK1` (`UNIT_ID`),
  ADD KEY `UOM_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  ADD PRIMARY KEY (`USER_ACTIVITY_ID`),
  ADD KEY `USER_ACTIVITY_LOGS_FK1` (`USER_ID`),
  ADD KEY `USER_ACTIVITY_LOGS_FK2` (`LAST_EDITED_BY`),
  ADD KEY `USER_ACTIVITY_LOGS_FK3` (`STATUS_ID`);

--
-- Indexes for table `user_activity_mod_det`
--
ALTER TABLE `user_activity_mod_det`
  ADD PRIMARY KEY (`USER_ACTIVITY_MOD_DET_ID`),
  ADD KEY `USER_ACTIVITY_MOD_DET_FK1` (`USER_ACTIVITY_ID`),
  ADD KEY `USER_ACTIVITY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `user_attribute_mod_history`
--
ALTER TABLE `user_attribute_mod_history`
  ADD PRIMARY KEY (`USER_ATTRIBUTE_MOD_HISTORY_ID`),
  ADD KEY `USER_ATTRIBUTE_MOD_FK1` (`USER_ATTRIBUTE_ID`),
  ADD KEY `USER_ATTRIBUTE_MOD_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `user_attributes`
--
ALTER TABLE `user_attributes`
  ADD PRIMARY KEY (`USER_ATTRIBUTE_ID`),
  ADD KEY `USER_ATTRIBUTES_FK2` (`USER_ID`),
  ADD KEY `USER_ATTRIBUTES_FK3` (`CREATED_BY`),
  ADD KEY `USER_ATTRIBUTES_FK4` (`STATUS_ID`),
  ADD KEY `ATTRIBUTE_ID` (`ATTRIBUTE_ID`);

--
-- Indexes for table `user_diseases`
--
ALTER TABLE `user_diseases`
  ADD PRIMARY KEY (`USER_DISEASE_ID`),
  ADD UNIQUE KEY `DISEASE_ID` (`DISEASE_ID`,`USER_ID`),
  ADD KEY `USER_DISEASES_FK2` (`USER_ID`),
  ADD KEY `USER_DISEASES_FK3` (`CREATED_BY`),
  ADD KEY `USER_DISEASES_FK4` (`STATUS_ID`);

--
-- Indexes for table `user_diseases_mod_det`
--
ALTER TABLE `user_diseases_mod_det`
  ADD PRIMARY KEY (`USER_DISEASES_MOD_DET_ID`),
  ADD KEY `USER_DISEASES_MOD_DET_FK1` (`USER_DISEASE_ID`),
  ADD KEY `USER_DISEASES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `user_fav_posts_mod_det`
--
ALTER TABLE `user_fav_posts_mod_det`
  ADD PRIMARY KEY (`USER_FAV_POST_MOD_DET_ID`),
  ADD KEY `USER_FAV_POSTS_MOD_DET_FK1` (`USER_FAVORITE_POST_ID`),
  ADD KEY `USER_FAV_POSTS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `user_favorite_posts`
--
ALTER TABLE `user_favorite_posts`
  ADD PRIMARY KEY (`USER_FAVORITE_POST_ID`),
  ADD UNIQUE KEY `USER_ID` (`USER_ID`,`POST_ID`),
  ADD KEY `USER_FAVORITE_POSTS_FK2` (`POST_ID`);

--
-- Indexes for table `user_health_history_det`
--
ALTER TABLE `user_health_history_det`
  ADD PRIMARY KEY (`USER_HEALTH_HISTORY_DET_ID`),
  ADD KEY `USER_HEALTH_HISTORY_FK1` (`USER_ID`),
  ADD KEY `USER_HEALTH_HISTORY_FK2` (`HEALTH_CONDITION_ID`),
  ADD KEY `USER_HEALTH_HISTORY_FK3` (`STATUS_ID`),
  ADD KEY `USER_HEALTH_HISTORY_FK4` (`CREATED_BY`);

--
-- Indexes for table `user_health_history_mod_det`
--
ALTER TABLE `user_health_history_mod_det`
  ADD PRIMARY KEY (`USER_HEALTH_HISTORY_MOD_DET_ID`),
  ADD KEY `HEALTH_HISTORY_MOD_DET_FK1` (`USER_HEALTH_HISTORY_DET_ID`),
  ADD KEY `HEALTH_HISTORY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `user_health_reading`
--
ALTER TABLE `user_health_reading`
  ADD PRIMARY KEY (`USER_HEALTH_READING_ID`),
  ADD KEY `USER_HEALTH_READING_FK1` (`USER_ID`),
  ADD KEY `USER_HEALTH_READING_FK2` (`ATTRIBUTE_TYPE_ID`),
  ADD KEY `USER_HEALTH_READING_FK3` (`UNIT_ID`),
  ADD KEY `USER_HEALTH_READING_FK4` (`DATE_RECORDED_ON`),
  ADD KEY `USER_HEALTH_READING_FK5` (`MONTH_RECORDED_ON`),
  ADD KEY `USER_HEALTH_READING_FK6` (`YEAR_RECORDED_ON`),
  ADD KEY `USER_HEALTH_READING_FK7` (`CREATED_BY`),
  ADD KEY `USER_HEALTH_READING_FK8` (`STATUS_ID`);

--
-- Indexes for table `user_health_reading_mod_det`
--
ALTER TABLE `user_health_reading_mod_det`
  ADD PRIMARY KEY (`USER_HEALTH_READING_MOD_DET_ID`),
  ADD KEY `USER_HEALTH_READING_MOD_DET_FK1` (`USER_HEALTH_READING_ID`),
  ADD KEY `USER_HEALTH_READING_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `user_media`
--
ALTER TABLE `user_media`
  ADD PRIMARY KEY (`USER_MEDIA_ID`),
  ADD KEY `USER_MEDIA_FK1` (`MEDIA_TYPE_ID`),
  ADD KEY `USER_MEDIA_FK2` (`USER_ID`),
  ADD KEY `USER_MEDIA_FK3` (`CREATED_BY`),
  ADD KEY `USER_MEDIA_FK4` (`STATUS_ID`);

--
-- Indexes for table `user_media_mod_det`
--
ALTER TABLE `user_media_mod_det`
  ADD PRIMARY KEY (`USER_MEDIA_MOD_DET_ID`),
  ADD KEY `USER_MEDIA_MOD_DET_FK1` (`USER_MEDIA_ID`),
  ADD KEY `USER_MEDIA_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `user_message_recipients`
--
ALTER TABLE `user_message_recipients`
  ADD PRIMARY KEY (`USER_MESSAGE_RECIPIENT_ID`),
  ADD UNIQUE KEY `MESSAGE_ID` (`MESSAGE_ID`,`RECIPIENT_USER_ID`,`RECIPIENT_ROLE_ID`),
  ADD KEY `MESSAGE_RECIPIENTS_FK2` (`RECIPIENT_USER_ID`),
  ADD KEY `MESSAGE_RECIPIENTS_FK3` (`RECIPIENT_ROLE_ID`);

--
-- Indexes for table `user_messages`
--
ALTER TABLE `user_messages`
  ADD PRIMARY KEY (`MESSAGE_ID`),
  ADD KEY `USER_MESSAGES_FK1` (`SENDER_USER_ID`),
  ADD KEY `USER_MESSAGES_FK2` (`STATUS_ID`);

--
-- Indexes for table `user_mood_history`
--
ALTER TABLE `user_mood_history`
  ADD PRIMARY KEY (`USER_MOOD_HISTORY_ID`),
  ADD KEY `USER_MOOD_HISTORY_FK1` (`USER_MOOD_ID`),
  ADD KEY `USER_MOOD_HISTORY_FK2` (`USER_ID`),
  ADD KEY `USER_MOOD_HISTORY_FK3` (`CREATED_BY`),
  ADD KEY `USER_MOOD_HISTORY_FK4` (`STATUS_ID`);

--
-- Indexes for table `user_mood_history_mod_det`
--
ALTER TABLE `user_mood_history_mod_det`
  ADD PRIMARY KEY (`USER_MOOD_HISTORY_MOD_DET_ID`),
  ADD KEY `USER_MOOD_HISTORY_MOD_DET_FK1` (`USER_MOOD_HISTORY_ID`),
  ADD KEY `USER_MOOD_HISTORY_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `user_pain_tracker`
--
ALTER TABLE `user_pain_tracker`
  ADD PRIMARY KEY (`USER_PAIN_ID`),
  ADD KEY `USER_PAIN_TRACKER_FK1` (`USER_ID`),
  ADD KEY `USER_PAIN_TRACKER_FK2` (`PAIN_ID`),
  ADD KEY `USER_PAIN_TRACKER_FK3` (`PAIN_LEVEL_ID`),
  ADD KEY `USER_PAIN_TRACKER_FK4` (`DATE_EXPERIENCED_ON`),
  ADD KEY `USER_PAIN_TRACKER_FK5` (`MONTH_EXPERIENCED_ON`),
  ADD KEY `USER_PAIN_TRACKER_FK6` (`YEAR_EXPERIENCED_ON`),
  ADD KEY `USER_PAIN_TRACKER_FK8` (`STATUS_ID`),
  ADD KEY `USER_PAIN_TRACKER_FK7` (`LAST_EDITED_BY`);

--
-- Indexes for table `user_pain_tracker_mod_det`
--
ALTER TABLE `user_pain_tracker_mod_det`
  ADD PRIMARY KEY (`USER_PAIN_TRACKER_MOD_DET_ID`),
  ADD KEY `USER_PAIN_TRACKER_MOD_DET_FK1` (`USER_PAIN_ID`),
  ADD KEY `USER_PAIN_TRACKER_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `user_photos`
--
ALTER TABLE `user_photos`
  ADD PRIMARY KEY (`USER_PHOTO_ID`),
  ADD KEY `USER_PHOTOS_FK1` (`USER_ID`),
  ADD KEY `USER_PHOTOS_FK2` (`PHOTO_TYPE_ID`),
  ADD KEY `USER_PHOTOS_FK3` (`CREATED_BY`),
  ADD KEY `USER_PHOTOS_FK4` (`STATUS_ID`);

--
-- Indexes for table `user_photos_mod_det`
--
ALTER TABLE `user_photos_mod_det`
  ADD PRIMARY KEY (`USER_PHOTOS_MOD_DET_ID`),
  ADD KEY `USER_PHOTOS_MOD_DET_FK1` (`USER_PHOTO_ID`),
  ADD KEY `USER_PHOTOS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `user_privacy_mod_det`
--
ALTER TABLE `user_privacy_mod_det`
  ADD PRIMARY KEY (`USER_PRIVACY_MOD_DET_ID`),
  ADD KEY `USER_PRIVACY_MOD_DET_FK1` (`MODIFIED_BY`),
  ADD KEY `USER_PRIVACY_MOD_DET_FK2` (`USER_PRIVACY_ID`);

--
-- Indexes for table `user_privacy_settings`
--
ALTER TABLE `user_privacy_settings`
  ADD PRIMARY KEY (`USER_PRIVACY_ID`),
  ADD UNIQUE KEY `USER_ID` (`USER_ID`,`USER_TYPE_ID`,`ACTIVITY_SECTION_ID`),
  ADD KEY `USER_PRIVACY_FK2` (`USER_TYPE_ID`),
  ADD KEY `USER_PRIVACY_FK4` (`PRIVACY_ID`),
  ADD KEY `USER_PRIVACY_SETTINGS_FK5` (`LAST_EDITED_BY`),
  ADD KEY `USER_PRIVACY_SETTINGS_FK6` (`STATUS_ID`),
  ADD KEY `USER_PRIVACY_FK3` (`ACTIVITY_SECTION_ID`);

--
-- Indexes for table `user_psswrd_challenge_ques`
--
ALTER TABLE `user_psswrd_challenge_ques`
  ADD PRIMARY KEY (`USER_PSSWRD_QUES_ID`),
  ADD UNIQUE KEY `PSSWRD_QUES_ID` (`PSSWRD_QUES_ID`,`USER_ID`),
  ADD KEY `CHALLENGE_QUES_FK2` (`USER_ID`);

--
-- Indexes for table `user_psswrd_challenge_ques_mod_det`
--
ALTER TABLE `user_psswrd_challenge_ques_mod_det`
  ADD PRIMARY KEY (`USER_PSSWRD_CHALLENGE_QUES_MOD_DET_ID`),
  ADD KEY `USER_PSSWRD_CHALLENGE_QUES_MOD_DET_FK1` (`USER_PSSWRD_QUES_ID`),
  ADD KEY `USER_PSSWRD_CHALLENGE_QUES_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `user_symptom_records`
--
ALTER TABLE `user_symptom_records`
  ADD PRIMARY KEY (`USER_SYMPTOM_RECORD_ID`),
  ADD KEY `USER_SYMPTOM_RECORDS_FK1` (`UNIT_ID`),
  ADD KEY `USER_SYMPTOM_RECORDS_FK2` (`LAST_EDITED_BY`),
  ADD KEY `USER_SYMPTOM_RECORDS_FK3` (`DATE_RECORDED_ON`),
  ADD KEY `USER_SYMPTOM_RECORDS_FK4` (`MONTH_RECORDED_ON`),
  ADD KEY `USER_SYMPTOM_RECORDS_FK5` (`YEAR_RECORDED_ON`),
  ADD KEY `USER_SYMPTOM_RECORDS_FK6` (`STATUS_ID`);

--
-- Indexes for table `user_symptom_records_mod_det`
--
ALTER TABLE `user_symptom_records_mod_det`
  ADD PRIMARY KEY (`USER_SYMPTOM_RECORDS_MOD_DET_ID`),
  ADD KEY `USER_SYMPTOM_RECORDS_MOD_DET_FK1` (`USER_SYMPTOM_RECORD_ID`),
  ADD KEY `USER_SYMPTOM_RECORDS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `user_symptoms`
--
ALTER TABLE `user_symptoms`
  ADD PRIMARY KEY (`USER_SYMPTOM_ID`),
  ADD UNIQUE KEY `USER_ID` (`USER_ID`,`SYMPTOM_ID`),
  ADD KEY `USER_SYMPTOMS_FK2` (`SYMPTOM_ID`),
  ADD KEY `USER_SYMPTOMS_FK3` (`STATUS_ID`),
  ADD KEY `USER_SYMPTOMS_FK4` (`CREATED_BY`);

--
-- Indexes for table `user_symptoms_mod_det`
--
ALTER TABLE `user_symptoms_mod_det`
  ADD PRIMARY KEY (`USER_SYMPTOMS_MOD_DET_ID`),
  ADD KEY `USER_SYMPTOMS_MOD_DET_FK1` (`USER_SYMPTOM_ID`),
  ADD KEY `USER_SYMPTOMS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`USER_TYPE_ID`),
  ADD UNIQUE KEY `USER_TYPE` (`USER_TYPE`),
  ADD KEY `USER_TYPE_FK1` (`STATUS`),
  ADD KEY `USER_TYPE_FK2` (`LAST_EDITED_BY`);

--
-- Indexes for table `user_type_mod_det`
--
ALTER TABLE `user_type_mod_det`
  ADD PRIMARY KEY (`USER_TYPE_MOD_DET_ID`),
  ADD KEY `USER_TYPE_MOD_DET_FK1` (`USER_TYPE_ID`),
  ADD KEY `USER_TYPE_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`USER_ID`),
  ADD KEY `USERS_FK3` (`COUNTRY`),
  ADD KEY `USERS_FK5` (`CITY`),
  ADD KEY `USERS_FK4` (`STATE`),
  ADD KEY `USERS_FK6` (`USER_TYPE`),
  ADD KEY `USERS_FK7` (`TIMEZONE`),
  ADD KEY `USERS_FK1` (`STATUS_ID`),
  ADD KEY `USERS_FK2` (`LANGUAGE`),
  ADD KEY `USERS_FK8` (`LAST_EDITED_BY`);

--
-- Indexes for table `week_days_master`
--
ALTER TABLE `week_days_master`
  ADD PRIMARY KEY (`WEEK_DAY_ID`),
  ADD UNIQUE KEY `WEEK_DAY_DESCR` (`WEEK_DAY_DESCR`),
  ADD KEY `WEEK_DAYS_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `WEEK_DAYS_MASTER_FK2` (`STATUS_ID`);

--
-- Indexes for table `week_days_mod_det`
--
ALTER TABLE `week_days_mod_det`
  ADD PRIMARY KEY (`WEEK_DAYS_MOD_DET_ID`),
  ADD KEY `WEEK_DAYS_MOD_DET_FK1` (`WEEK_DAY_ID`),
  ADD KEY `WEEK_DAYS_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `year_mod_det`
--
ALTER TABLE `year_mod_det`
  ADD PRIMARY KEY (`YEAR_MOD_DET_ID`),
  ADD KEY `YEAR_MOD_DET_FK1` (`YEAR_ID`),
  ADD KEY `YEAR_MOD_DET_FK2` (`MODIFIED_BY`);

--
-- Indexes for table `years_master`
--
ALTER TABLE `years_master`
  ADD PRIMARY KEY (`YEAR_ID`),
  ADD UNIQUE KEY `YEAR_VALUE` (`YEAR_VALUE`),
  ADD KEY `YEARS_MASTER_FK1` (`LAST_EDITED_BY`),
  ADD KEY `YEARS_MASTER_FK2` (`STATUS_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `action_tokens_master`
--
ALTER TABLE `action_tokens_master`
  MODIFY `ACTION_TOKEN_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `action_tokens_mod_det`
--
ALTER TABLE `action_tokens_mod_det`
  MODIFY `ACTION_TOKENS_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `activity_section_master`
--
ALTER TABLE `activity_section_master`
  MODIFY `ACTIVITY_SECTION_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `activity_section_mod_det`
--
ALTER TABLE `activity_section_mod_det`
  MODIFY `ACTIVITY_SECTION_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `arrowchat`
--
ALTER TABLE `arrowchat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `arrowchat_admin`
--
ALTER TABLE `arrowchat_admin`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `arrowchat_applications`
--
ALTER TABLE `arrowchat_applications`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `arrowchat_banlist`
--
ALTER TABLE `arrowchat_banlist`
  MODIFY `ban_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `arrowchat_chatroom_messages`
--
ALTER TABLE `arrowchat_chatroom_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `arrowchat_chatroom_rooms`
--
ALTER TABLE `arrowchat_chatroom_rooms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `arrowchat_graph_log`
--
ALTER TABLE `arrowchat_graph_log`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `arrowchat_notifications`
--
ALTER TABLE `arrowchat_notifications`
  MODIFY `id` int(25) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `arrowchat_notifications_markup`
--
ALTER TABLE `arrowchat_notifications_markup`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `arrowchat_smilies`
--
ALTER TABLE `arrowchat_smilies`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `arrowchat_themes`
--
ALTER TABLE `arrowchat_themes`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `arrowchat_trayicons`
--
ALTER TABLE `arrowchat_trayicons`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `attribute_master_mod_det`
--
ALTER TABLE `attribute_master_mod_det`
  MODIFY `ATTRIBUTE_MASTER_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `attribute_type_master`
--
ALTER TABLE `attribute_type_master`
  MODIFY `ATTRIBUTE_TYPE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `attribute_type_mod_det`
--
ALTER TABLE `attribute_type_mod_det`
  MODIFY `ATTRIBUTE_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `attributes_master`
--
ALTER TABLE `attributes_master`
  MODIFY `ATTRIBUTE_ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT for table `blocked_user_mod_det`
--
ALTER TABLE `blocked_user_mod_det`
  MODIFY `BLOCKED_USER_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `blocked_users`
--
ALTER TABLE `blocked_users`
  MODIFY `BLOCKED_USER_ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `care_calendar_events`
--
ALTER TABLE `care_calendar_events`
  MODIFY `CARE_EVENT_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `care_events_mod_det`
--
ALTER TABLE `care_events_mod_det`
  MODIFY `CARE_EVENTS_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `care_giver_attribute_mod_det`
--
ALTER TABLE `care_giver_attribute_mod_det`
  MODIFY `CARE_GIVER_ATTRIBUTE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `care_giver_attributes`
--
ALTER TABLE `care_giver_attributes`
  MODIFY `CARE_GIVER_ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `caregiver_relationship_master`
--
ALTER TABLE `caregiver_relationship_master`
  MODIFY `RELATIONSHIP_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `caregiver_relationship_mod_det`
--
ALTER TABLE `caregiver_relationship_mod_det`
  MODIFY `CAREGIVER_RELATIONSHIP_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cities_master`
--
ALTER TABLE `cities_master`
  MODIFY `CITY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113990;
--
-- AUTO_INCREMENT for table `cities_mod_det`
--
ALTER TABLE `cities_mod_det`
  MODIFY `CITIES_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `communities`
--
ALTER TABLE `communities`
  MODIFY `COMMUNITY_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `community_attributes`
--
ALTER TABLE `community_attributes`
  MODIFY `COMMUNITY_ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `community_attributes_mod_det`
--
ALTER TABLE `community_attributes_mod_det`
  MODIFY `COMMUNITY_ATTRIBUTE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `community_diseases`
--
ALTER TABLE `community_diseases`
  MODIFY `COMMUNITY_DISEASE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `community_diseases_mod_det`
--
ALTER TABLE `community_diseases_mod_det`
  MODIFY `COMMUNITY_DISEASE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `community_members`
--
ALTER TABLE `community_members`
  MODIFY `COMMUNITY_MEMBER_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `community_members_mod_det`
--
ALTER TABLE `community_members_mod_det`
  MODIFY `COMMUNITY_MEMBER_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `community_mod_det`
--
ALTER TABLE `community_mod_det`
  MODIFY `COMMUNITY_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `community_photos`
--
ALTER TABLE `community_photos`
  MODIFY `COMMUNITY_PHOTO_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `community_photos_mod_det`
--
ALTER TABLE `community_photos_mod_det`
  MODIFY `COMMUNITY_PHOTO_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `community_type_master`
--
ALTER TABLE `community_type_master`
  MODIFY `COMMUNITY_TYPE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `community_type_mod_det`
--
ALTER TABLE `community_type_mod_det`
  MODIFY `COMMUNITY_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `configurations`
--
ALTER TABLE `configurations`
  MODIFY `CONFIGURATION_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `configurations_mod_det`
--
ALTER TABLE `configurations_mod_det`
  MODIFY `CONFIGURATION_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `country_master`
--
ALTER TABLE `country_master`
  MODIFY `COUNTRY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;
--
-- AUTO_INCREMENT for table `country_mod_det`
--
ALTER TABLE `country_mod_det`
  MODIFY `COUNTRY_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cron_task_exec_log`
--
ALTER TABLE `cron_task_exec_log`
  MODIFY `CRON_TASK_EXEC_LOG_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cron_task_exec_log_mod_det`
--
ALTER TABLE `cron_task_exec_log_mod_det`
  MODIFY `CRON_TASK_EXEC_LOG_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cron_tasks`
--
ALTER TABLE `cron_tasks`
  MODIFY `TASK_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cron_tasks_mod_det`
--
ALTER TABLE `cron_tasks_mod_det`
  MODIFY `CRON_TASK_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dates`
--
ALTER TABLE `dates`
  MODIFY `DATE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `dates_mod_det`
--
ALTER TABLE `dates_mod_det`
  MODIFY `DATES_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `disease_master`
--
ALTER TABLE `disease_master`
  MODIFY `DISEASE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `disease_mod_det`
--
ALTER TABLE `disease_mod_det`
  MODIFY `DISEASE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `disease_symptoms`
--
ALTER TABLE `disease_symptoms`
  MODIFY `DISEASE_SYMPTOM_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `disease_symptoms_mod_det`
--
ALTER TABLE `disease_symptoms_mod_det`
  MODIFY `DISEASE_SYMPTOM_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `disease_type_master`
--
ALTER TABLE `disease_type_master`
  MODIFY `DISEASE_TYPE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `disease_type_mod_det`
--
ALTER TABLE `disease_type_mod_det`
  MODIFY `DISEASE_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_attributes`
--
ALTER TABLE `email_attributes`
  MODIFY `EMAIL_ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_attributes_mod_det`
--
ALTER TABLE `email_attributes_mod_det`
  MODIFY `EMAIL_ATTRIBUTES_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_history`
--
ALTER TABLE `email_history`
  MODIFY `EMAIL_HISTORY_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_history_attributes`
--
ALTER TABLE `email_history_attributes`
  MODIFY `EMAIL_HISTORY_ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_history_attributes_mod_det`
--
ALTER TABLE `email_history_attributes_mod_det`
  MODIFY `EMAIL_HISTORY_ATTRIBUTES_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_history_mod_det`
--
ALTER TABLE `email_history_mod_det`
  MODIFY `EMAIL_HISTORY_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_mod_det`
--
ALTER TABLE `email_mod_det`
  MODIFY `EMAIL_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_priority_master`
--
ALTER TABLE `email_priority_master`
  MODIFY `EMAIL_PRIORITY_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_priority_mod_det`
--
ALTER TABLE `email_priority_mod_det`
  MODIFY `EMAIL_PRIORITY_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `TEMPLATE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_templates_mod_det`
--
ALTER TABLE `email_templates_mod_det`
  MODIFY `EMAIL_TEMPLATE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `EMAIL_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `event_attributes`
--
ALTER TABLE `event_attributes`
  MODIFY `EVENT_ATTRIBUTE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `event_attributes_mod_det`
--
ALTER TABLE `event_attributes_mod_det`
  MODIFY `EVENT_ATTRIBUTE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `event_diseases`
--
ALTER TABLE `event_diseases`
  MODIFY `EVENT_DISEASE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `event_diseases_mod_det`
--
ALTER TABLE `event_diseases_mod_det`
  MODIFY `EVENT_DISEASE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `event_members`
--
ALTER TABLE `event_members`
  MODIFY `EVENT_MEMBER_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `event_members_mod_det`
--
ALTER TABLE `event_members_mod_det`
  MODIFY `EVENT_MEMBER_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `event_mod_det`
--
ALTER TABLE `event_mod_det`
  MODIFY `EVENT_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `event_type_master`
--
ALTER TABLE `event_type_master`
  MODIFY `EVENT_TYPE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `event_type_mod_det`
--
ALTER TABLE `event_type_mod_det`
  MODIFY `EVENT_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `EVENT_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `following_pages`
--
ALTER TABLE `following_pages`
  MODIFY `FOLLOWING_PAGE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `following_pages_mod_det`
--
ALTER TABLE `following_pages_mod_det`
  MODIFY `FOLLOWING_PAGE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `health_cond_group_mod_det`
--
ALTER TABLE `health_cond_group_mod_det`
  MODIFY `HEALTH_COND_GROUP_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `health_condition_groups`
--
ALTER TABLE `health_condition_groups`
  MODIFY `HEALTH_CONDITION_GROUP_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `health_condition_master`
--
ALTER TABLE `health_condition_master`
  MODIFY `HEALTH_CONDITION_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `health_condition_mod_det`
--
ALTER TABLE `health_condition_mod_det`
  MODIFY `HEALTH_CONDITION_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `invited_users`
--
ALTER TABLE `invited_users`
  MODIFY `INVITED_USER_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `invited_users_mod_det`
--
ALTER TABLE `invited_users_mod_det`
  MODIFY `INVITED_USERS_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `language_mod_det`
--
ALTER TABLE `language_mod_det`
  MODIFY `LANGUAGE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `LANGUAGE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `media_type_master`
--
ALTER TABLE `media_type_master`
  MODIFY `MEDIA_TYPE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `media_type_mod_det`
--
ALTER TABLE `media_type_mod_det`
  MODIFY `MEDIA_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `message_recipient_roles`
--
ALTER TABLE `message_recipient_roles`
  MODIFY `MESSAGE_RECIPIENT_ROLE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `message_role_mod_det`
--
ALTER TABLE `message_role_mod_det`
  MODIFY `MESSAGE_ROLE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `module_master`
--
ALTER TABLE `module_master`
  MODIFY `MODULE_ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `module_mod_det`
--
ALTER TABLE `module_mod_det`
  MODIFY `MODULE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `month_mod_det`
--
ALTER TABLE `month_mod_det`
  MODIFY `MONTH_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `months_master`
--
ALTER TABLE `months_master`
  MODIFY `MONTH_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `mood_master`
--
ALTER TABLE `mood_master`
  MODIFY `USER_MOOD_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mood_mod_det`
--
ALTER TABLE `mood_mod_det`
  MODIFY `MOOD_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `my_friend_mod_det`
--
ALTER TABLE `my_friend_mod_det`
  MODIFY `MY_FRIEND_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `my_friends`
--
ALTER TABLE `my_friends`
  MODIFY `MY_FRIEND_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `my_friends_detail_mod_det`
--
ALTER TABLE `my_friends_detail_mod_det`
  MODIFY `MY_FRIENDS_DETAIL_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `my_friends_details`
--
ALTER TABLE `my_friends_details`
  MODIFY `MY_FRIENDS_DETAIL_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `newsletter_mod_det`
--
ALTER TABLE `newsletter_mod_det`
  MODIFY `NEWSLETTER_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `newsletter_queue_mod_det`
--
ALTER TABLE `newsletter_queue_mod_det`
  MODIFY `NEWSLETTER_QUEUE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `newsletter_queue_status`
--
ALTER TABLE `newsletter_queue_status`
  MODIFY `NEWSLETTER_QUEUE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `newsletter_template_mod_det`
--
ALTER TABLE `newsletter_template_mod_det`
  MODIFY `NEWSLETTER_TEMPLATE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `newsletter_templates`
--
ALTER TABLE `newsletter_templates`
  MODIFY `NEWSLETTER_TEMPLATE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `newsletters`
--
ALTER TABLE `newsletters`
  MODIFY `NEWSLETTER_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notification_activity_mod_det`
--
ALTER TABLE `notification_activity_mod_det`
  MODIFY `NOTIFICATION_ACTIVITY_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notification_activity_type_master`
--
ALTER TABLE `notification_activity_type_master`
  MODIFY `NOTIFICATION_ACTIVITY_TYPE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notification_frequency_master`
--
ALTER TABLE `notification_frequency_master`
  MODIFY `NOTIFICATION_FREQUENCY_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notification_frequency_mod_det`
--
ALTER TABLE `notification_frequency_mod_det`
  MODIFY `NOTIFICATION_FREQUENCY_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notification_mod_det`
--
ALTER TABLE `notification_mod_det`
  MODIFY `NOTIFICATION_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notification_object_type_master`
--
ALTER TABLE `notification_object_type_master`
  MODIFY `NOTIFICATION_OBJECT_TYPE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notification_object_type_mod_det`
--
ALTER TABLE `notification_object_type_mod_det`
  MODIFY `NOTIFICATION_OBJECT_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notification_recipient_mod_det`
--
ALTER TABLE `notification_recipient_mod_det`
  MODIFY `NOTIFICATION_RECIPIENT_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notification_recipients`
--
ALTER TABLE `notification_recipients`
  MODIFY `NOTIFICATION_RECIPIENT_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notification_setting_mod_det`
--
ALTER TABLE `notification_setting_mod_det`
  MODIFY `NOTIFICATION_SETTING_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notification_settings`
--
ALTER TABLE `notification_settings`
  MODIFY `NOTIFICATION_SETTING_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `NOTIFICATION_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notified_user_mod_det`
--
ALTER TABLE `notified_user_mod_det`
  MODIFY `NOTIFIED_USER_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notified_users`
--
ALTER TABLE `notified_users`
  MODIFY `NOTIFIED_USER_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `page_master`
--
ALTER TABLE `page_master`
  MODIFY `PAGE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `page_mod_det`
--
ALTER TABLE `page_mod_det`
  MODIFY `PAGE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `page_type_master`
--
ALTER TABLE `page_type_master`
  MODIFY `PAGE_TYPE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `page_type_mod_det`
--
ALTER TABLE `page_type_mod_det`
  MODIFY `PAGE_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pain_level_mod_det`
--
ALTER TABLE `pain_level_mod_det`
  MODIFY `PAIN_LEVEL_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pain_levels_master`
--
ALTER TABLE `pain_levels_master`
  MODIFY `PAIN_LEVEL_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pain_master`
--
ALTER TABLE `pain_master`
  MODIFY `PAIN_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `pain_type_mod_det`
--
ALTER TABLE `pain_type_mod_det`
  MODIFY `PAIN_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `patient_care_giver_mod_det`
--
ALTER TABLE `patient_care_giver_mod_det`
  MODIFY `PATIENT_CARE_GIVER_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `patient_care_givers`
--
ALTER TABLE `patient_care_givers`
  MODIFY `PATIENT_CARE_GIVER_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `photo_type_master`
--
ALTER TABLE `photo_type_master`
  MODIFY `PHOTO_TYPE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `photo_type_mod_det`
--
ALTER TABLE `photo_type_mod_det`
  MODIFY `PHOTO_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `poll_choice_mod_det`
--
ALTER TABLE `poll_choice_mod_det`
  MODIFY `POLL_CHOICE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `poll_choices`
--
ALTER TABLE `poll_choices`
  MODIFY `POLL_CHOICE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `poll_mod_det`
--
ALTER TABLE `poll_mod_det`
  MODIFY `POLL_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `polls`
--
ALTER TABLE `polls`
  MODIFY `POLL_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `POST_COMMENT_ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `post_comments_mod_det`
--
ALTER TABLE `post_comments_mod_det`
  MODIFY `POST_COMMENT_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `post_content_details`
--
ALTER TABLE `post_content_details`
  MODIFY `POST_CONTENT_ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `post_content_mod_det`
--
ALTER TABLE `post_content_mod_det`
  MODIFY `POST_CONTENT_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `POST_LIKE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `post_likes_mod_det`
--
ALTER TABLE `post_likes_mod_det`
  MODIFY `POST_LIKE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `post_location`
--
ALTER TABLE `post_location`
  MODIFY `POST_LOCATION_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `post_location_master`
--
ALTER TABLE `post_location_master`
  MODIFY `POST_LOCATION_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `post_location_master_mod_det`
--
ALTER TABLE `post_location_master_mod_det`
  MODIFY `POST_LOCATION_MASTER_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `post_location_mod_det`
--
ALTER TABLE `post_location_mod_det`
  MODIFY `POST_LOCATION_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `post_mod_det`
--
ALTER TABLE `post_mod_det`
  MODIFY `POST_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `post_privacy_mod_det`
--
ALTER TABLE `post_privacy_mod_det`
  MODIFY `POST_PRIVACY_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `post_privacy_settings`
--
ALTER TABLE `post_privacy_settings`
  MODIFY `POST_PRIVACY_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `post_type_master`
--
ALTER TABLE `post_type_master`
  MODIFY `POST_TYPE_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `post_type_mod_det`
--
ALTER TABLE `post_type_mod_det`
  MODIFY `POST_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `privacy_master`
--
ALTER TABLE `privacy_master`
  MODIFY `PRIVACY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `privacy_mod_det`
--
ALTER TABLE `privacy_mod_det`
  MODIFY `PRIVACY_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `publish_type_master`
--
ALTER TABLE `publish_type_master`
  MODIFY `PUBLISH_TYPE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `publish_type_mod_det`
--
ALTER TABLE `publish_type_mod_det`
  MODIFY `PUBLISH_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `question_group_master`
--
ALTER TABLE `question_group_master`
  MODIFY `QUESTION_GROUP_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `question_group_mod_det`
--
ALTER TABLE `question_group_mod_det`
  MODIFY `QUESTION_GROUP_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `question_master`
--
ALTER TABLE `question_master`
  MODIFY `QUESTION_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `question_mod_det`
--
ALTER TABLE `question_mod_det`
  MODIFY `QUESTION_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `repeat_by_type_master`
--
ALTER TABLE `repeat_by_type_master`
  MODIFY `REPEAT_BY_TYPE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `repeat_by_type_mod_det`
--
ALTER TABLE `repeat_by_type_mod_det`
  MODIFY `REPEAT_BY_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `repeat_end_type_master`
--
ALTER TABLE `repeat_end_type_master`
  MODIFY `REPEAT_END_TYPE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `repeat_end_type_mod_det`
--
ALTER TABLE `repeat_end_type_mod_det`
  MODIFY `REPEAT_END_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `repeat_mode_type_master`
--
ALTER TABLE `repeat_mode_type_master`
  MODIFY `REPEAT_MODE_TYPE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `repeat_mode_type_mod_det`
--
ALTER TABLE `repeat_mode_type_mod_det`
  MODIFY `REPEAT_MODE_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `repeat_type_master`
--
ALTER TABLE `repeat_type_master`
  MODIFY `REPEAT_TYPE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `repeat_type_mod_det`
--
ALTER TABLE `repeat_type_mod_det`
  MODIFY `REPEAT_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `section_type_master`
--
ALTER TABLE `section_type_master`
  MODIFY `SECTION_TYPE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `section_type_mod_det`
--
ALTER TABLE `section_type_mod_det`
  MODIFY `SECTION_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `states_master`
--
ALTER TABLE `states_master`
  MODIFY `STATE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3721;
--
-- AUTO_INCREMENT for table `states_mod_det`
--
ALTER TABLE `states_mod_det`
  MODIFY `STATES_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `STATUS_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `status_mod_det`
--
ALTER TABLE `status_mod_det`
  MODIFY `STATUS_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `status_type`
--
ALTER TABLE `status_type`
  MODIFY `STATUS_TYPE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `status_type_mod_det`
--
ALTER TABLE `status_type_mod_det`
  MODIFY `STATUS_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `survey_master`
--
ALTER TABLE `survey_master`
  MODIFY `SURVEY_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `survey_mod_det`
--
ALTER TABLE `survey_mod_det`
  MODIFY `SURVEY_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `survey_questions`
--
ALTER TABLE `survey_questions`
  MODIFY `SURVEY_QUESTION_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `survey_questions_answer_choices`
--
ALTER TABLE `survey_questions_answer_choices`
  MODIFY `SURVEY_QUESTIONS_ANSWER_CHOICE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `survey_questions_answer_choices_mod_det`
--
ALTER TABLE `survey_questions_answer_choices_mod_det`
  MODIFY `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `survey_questions_mod_det`
--
ALTER TABLE `survey_questions_mod_det`
  MODIFY `SURVEY_QUESTIONS_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `survey_results_answer_choices`
--
ALTER TABLE `survey_results_answer_choices`
  MODIFY `SURVEY_RESULTS_ANSWER_CHOICE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `survey_results_answer_choices_mod_det`
--
ALTER TABLE `survey_results_answer_choices_mod_det`
  MODIFY `SURVEY_RESULTS_ANSWER_CHOICE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `survey_results_detailed_answers`
--
ALTER TABLE `survey_results_detailed_answers`
  MODIFY `SURVEY_RESULTS_DETAILED_ANSWER_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `survey_results_detailed_answers_mod_det`
--
ALTER TABLE `survey_results_detailed_answers_mod_det`
  MODIFY `SURVEY_RESULTS_DETAILED_ANSWER_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `survey_type_master`
--
ALTER TABLE `survey_type_master`
  MODIFY `SURVEY_TYPE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `survey_type_mod_det`
--
ALTER TABLE `survey_type_mod_det`
  MODIFY `SURVEY_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `symptoms_master`
--
ALTER TABLE `symptoms_master`
  MODIFY `SYMPTOM_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `symptoms_mod_det`
--
ALTER TABLE `symptoms_mod_det`
  MODIFY `SYMPTOM_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `TEAM_MEMBER_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `team_members_mod_det`
--
ALTER TABLE `team_members_mod_det`
  MODIFY `TEAM_MEMBER_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `team_mod_det`
--
ALTER TABLE `team_mod_det`
  MODIFY `TEAM_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `team_privacy_setting_mod_det`
--
ALTER TABLE `team_privacy_setting_mod_det`
  MODIFY `TEAM_PRIVACY_SETTING_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `team_privacy_settings`
--
ALTER TABLE `team_privacy_settings`
  MODIFY `TEAM_PRIVACY_SETTING_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `TEAM_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timezone_master`
--
ALTER TABLE `timezone_master`
  MODIFY `TIMEZONE_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `timezone_mod_det`
--
ALTER TABLE `timezone_mod_det`
  MODIFY `TIMEZONE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `treatment_master`
--
ALTER TABLE `treatment_master`
  MODIFY `TREATMENT_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `treatment_master_mod_det`
--
ALTER TABLE `treatment_master_mod_det`
  MODIFY `TREATMENT_MASTER_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `unit_of_measurement_master`
--
ALTER TABLE `unit_of_measurement_master`
  MODIFY `UNIT_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `unit_of_measurement_mod_det`
--
ALTER TABLE `unit_of_measurement_mod_det`
  MODIFY `UNIT_OF_MEASUREMENT_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  MODIFY `USER_ACTIVITY_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_activity_mod_det`
--
ALTER TABLE `user_activity_mod_det`
  MODIFY `USER_ACTIVITY_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_attribute_mod_history`
--
ALTER TABLE `user_attribute_mod_history`
  MODIFY `USER_ATTRIBUTE_MOD_HISTORY_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_attributes`
--
ALTER TABLE `user_attributes`
  MODIFY `USER_ATTRIBUTE_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_diseases`
--
ALTER TABLE `user_diseases`
  MODIFY `USER_DISEASE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_diseases_mod_det`
--
ALTER TABLE `user_diseases_mod_det`
  MODIFY `USER_DISEASES_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_fav_posts_mod_det`
--
ALTER TABLE `user_fav_posts_mod_det`
  MODIFY `USER_FAV_POST_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_favorite_posts`
--
ALTER TABLE `user_favorite_posts`
  MODIFY `USER_FAVORITE_POST_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_health_history_det`
--
ALTER TABLE `user_health_history_det`
  MODIFY `USER_HEALTH_HISTORY_DET_ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_health_history_mod_det`
--
ALTER TABLE `user_health_history_mod_det`
  MODIFY `USER_HEALTH_HISTORY_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_health_reading`
--
ALTER TABLE `user_health_reading`
  MODIFY `USER_HEALTH_READING_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_health_reading_mod_det`
--
ALTER TABLE `user_health_reading_mod_det`
  MODIFY `USER_HEALTH_READING_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_media`
--
ALTER TABLE `user_media`
  MODIFY `USER_MEDIA_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_media_mod_det`
--
ALTER TABLE `user_media_mod_det`
  MODIFY `USER_MEDIA_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_message_recipients`
--
ALTER TABLE `user_message_recipients`
  MODIFY `USER_MESSAGE_RECIPIENT_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_messages`
--
ALTER TABLE `user_messages`
  MODIFY `MESSAGE_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_mood_history`
--
ALTER TABLE `user_mood_history`
  MODIFY `USER_MOOD_HISTORY_ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_mood_history_mod_det`
--
ALTER TABLE `user_mood_history_mod_det`
  MODIFY `USER_MOOD_HISTORY_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_pain_tracker`
--
ALTER TABLE `user_pain_tracker`
  MODIFY `USER_PAIN_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_pain_tracker_mod_det`
--
ALTER TABLE `user_pain_tracker_mod_det`
  MODIFY `USER_PAIN_TRACKER_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_photos`
--
ALTER TABLE `user_photos`
  MODIFY `USER_PHOTO_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_photos_mod_det`
--
ALTER TABLE `user_photos_mod_det`
  MODIFY `USER_PHOTOS_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_privacy_mod_det`
--
ALTER TABLE `user_privacy_mod_det`
  MODIFY `USER_PRIVACY_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_privacy_settings`
--
ALTER TABLE `user_privacy_settings`
  MODIFY `USER_PRIVACY_ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_psswrd_challenge_ques`
--
ALTER TABLE `user_psswrd_challenge_ques`
  MODIFY `USER_PSSWRD_QUES_ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_psswrd_challenge_ques_mod_det`
--
ALTER TABLE `user_psswrd_challenge_ques_mod_det`
  MODIFY `USER_PSSWRD_CHALLENGE_QUES_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_symptom_records`
--
ALTER TABLE `user_symptom_records`
  MODIFY `USER_SYMPTOM_RECORD_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_symptom_records_mod_det`
--
ALTER TABLE `user_symptom_records_mod_det`
  MODIFY `USER_SYMPTOM_RECORDS_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_symptoms`
--
ALTER TABLE `user_symptoms`
  MODIFY `USER_SYMPTOM_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_symptoms_mod_det`
--
ALTER TABLE `user_symptoms_mod_det`
  MODIFY `USER_SYMPTOMS_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `USER_TYPE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `user_type_mod_det`
--
ALTER TABLE `user_type_mod_det`
  MODIFY `USER_TYPE_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `USER_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `week_days_master`
--
ALTER TABLE `week_days_master`
  MODIFY `WEEK_DAY_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `week_days_mod_det`
--
ALTER TABLE `week_days_mod_det`
  MODIFY `WEEK_DAYS_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `year_mod_det`
--
ALTER TABLE `year_mod_det`
  MODIFY `YEAR_MOD_DET_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `years_master`
--
ALTER TABLE `years_master`
  MODIFY `YEAR_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `action_tokens_master`
--
ALTER TABLE `action_tokens_master`
  ADD CONSTRAINT `ACTION_TOKENS_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `ACTION_TOKENS_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `action_tokens_mod_det`
--
ALTER TABLE `action_tokens_mod_det`
  ADD CONSTRAINT `ACTION_TOKENS_MOD_DET_FK1` FOREIGN KEY (`ACTION_TOKEN_ID`) REFERENCES `action_tokens_master` (`ACTION_TOKEN_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `ACTION_TOKENS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `activity_section_mod_det`
--
ALTER TABLE `activity_section_mod_det`
  ADD CONSTRAINT `ACTIVITY_SECTION_MOD_DET_FK1` FOREIGN KEY (`ACTIVITY_SECTION_ID`) REFERENCES `activity_section_master` (`ACTIVITY_SECTION_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NOTIFICATION_ACTIVITY_SECTION_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `attribute_master_mod_det`
--
ALTER TABLE `attribute_master_mod_det`
  ADD CONSTRAINT `ATTRIBUTE_MASTER_MOD_DET_FK1` FOREIGN KEY (`ATTRIBUTE_ID`) REFERENCES `attributes_master` (`ATTRIBUTE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `ATTRIBUTE_MASTER_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `attribute_type_master`
--
ALTER TABLE `attribute_type_master`
  ADD CONSTRAINT `ATTRIBUTE_TYPE_MASTER_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `ATTRIBUTE_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `attribute_type_mod_det`
--
ALTER TABLE `attribute_type_mod_det`
  ADD CONSTRAINT `ATTRIBUTE_TYPE_MOD_DET_FK1` FOREIGN KEY (`ATTRIBUTE_TYPE_ID`) REFERENCES `attribute_type_master` (`ATTRIBUTE_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `ATTRIBUTE_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `attributes_master`
--
ALTER TABLE `attributes_master`
  ADD CONSTRAINT `ATTRIBUTES_MASTER_FK1` FOREIGN KEY (`ATTRIBUTE_TYPE_ID`) REFERENCES `attribute_type_master` (`ATTRIBUTE_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `ATTRIBUTES_MASTER_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `ATTRIBUTES_MASTER_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `blocked_user_mod_det`
--
ALTER TABLE `blocked_user_mod_det`
  ADD CONSTRAINT `BLOCKED_USER_MOD_DET_FK1` FOREIGN KEY (`BLOCKED_USER_ID`) REFERENCES `blocked_users` (`BLOCKED_USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `BLOCKED_USER_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `blocked_users`
--
ALTER TABLE `blocked_users`
  ADD CONSTRAINT `BLOCKED_USERS_FK1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `BLOCKED_USERS_FK2` FOREIGN KEY (`BLOCKED_USER`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `BLOCKED_USERS_FK3` FOREIGN KEY (`BLOCKED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `BLOCKED_USERS_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `care_calendar_events`
--
ALTER TABLE `care_calendar_events`
  ADD CONSTRAINT `CARE_CALENDAR_EVENTS_FK1` FOREIGN KEY (`ASSIGNED_TO`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `CARE_CALENDAR_EVENTS_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `CARE_CALENDAR_EVENTS_FK3` FOREIGN KEY (`CARE_EVENT_TYPE_ID`) REFERENCES `event_type_master` (`EVENT_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `CARE_CALENDAR_EVENTS_FK4` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `care_events_mod_det`
--
ALTER TABLE `care_events_mod_det`
  ADD CONSTRAINT `CARE_EVENTS_MOD_DET_FK1` FOREIGN KEY (`CARE_EVENT_ID`) REFERENCES `care_calendar_events` (`CARE_EVENT_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `CARE_EVENTS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `care_giver_attribute_mod_det`
--
ALTER TABLE `care_giver_attribute_mod_det`
  ADD CONSTRAINT `CARE_GIVER_ATTRIBUTE_MOD_DET_FK1` FOREIGN KEY (`CARE_GIVER_ATTRIBUTE_ID`) REFERENCES `care_giver_attributes` (`CARE_GIVER_ATTRIBUTE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `CARE_GIVER_ATTRIBUTE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `care_giver_attributes`
--
ALTER TABLE `care_giver_attributes`
  ADD CONSTRAINT `CARE_GIVER_ATTRIBUTES_FK1` FOREIGN KEY (`PATIENT_CARE_GIVER_ID`) REFERENCES `patient_care_givers` (`PATIENT_CARE_GIVER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `CARE_GIVER_ATTRIBUTES_FK2` FOREIGN KEY (`ATTRIBUTE_ID`) REFERENCES `attributes_master` (`ATTRIBUTE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `CARE_GIVER_ATTRIBUTES_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `CARE_GIVER_ATTRIBUTES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `caregiver_relationship_master`
--
ALTER TABLE `caregiver_relationship_master`
  ADD CONSTRAINT `CAREGIVER_RELATIONSHIP_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `CAREGIVER_RELATIONSHIP_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `caregiver_relationship_mod_det`
--
ALTER TABLE `caregiver_relationship_mod_det`
  ADD CONSTRAINT `CAREGIVER_RELATIONSHIP_MOD_DET_FK1` FOREIGN KEY (`RELATIONSHIP_ID`) REFERENCES `caregiver_relationship_master` (`RELATIONSHIP_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `CAREGIVER_RELATIONSHIP_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `cities_master`
--
ALTER TABLE `cities_master`
  ADD CONSTRAINT `CITIES_FK1` FOREIGN KEY (`STATE_ID`) REFERENCES `states_master` (`STATE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `CITIES_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `CITIES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `cities_mod_det`
--
ALTER TABLE `cities_mod_det`
  ADD CONSTRAINT `CITIES_MOD_DET_FK1` FOREIGN KEY (`CITY_ID`) REFERENCES `cities_master` (`CITY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `CITIES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `community_attributes`
--
ALTER TABLE `community_attributes`
  ADD CONSTRAINT `COMMUNITY_ATTRIBUTES_FK1` FOREIGN KEY (`COMMUNITY_ID`) REFERENCES `communities` (`COMMUNITY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `COMMUNITY_ATTRIBUTES_FK2` FOREIGN KEY (`ATTRIBUTE_ID`) REFERENCES `attributes_master` (`ATTRIBUTE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `COMMUNITY_ATTRIBUTES_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `COMMUNITY_ATTRIBUTES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `community_attributes_mod_det`
--
ALTER TABLE `community_attributes_mod_det`
  ADD CONSTRAINT `COMMUNITY_ATTRIBUTES_MOD_DET_FK1` FOREIGN KEY (`COMMUNITY_ATTRIBUTE_ID`) REFERENCES `community_attributes` (`COMMUNITY_ATTRIBUTE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `COMMUNITY_ATTRIBUTES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `community_diseases`
--
ALTER TABLE `community_diseases`
  ADD CONSTRAINT `COMMUNITY_DISEASES_FK1` FOREIGN KEY (`COMMUNITY_ID`) REFERENCES `communities` (`COMMUNITY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `COMMUNITY_DISEASES_FK2` FOREIGN KEY (`DISEASE_ID`) REFERENCES `disease_master` (`DISEASE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `COMMUNITY_DISEASES_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `COMMUNITY_DISEASES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `community_diseases_mod_det`
--
ALTER TABLE `community_diseases_mod_det`
  ADD CONSTRAINT `COMMUNITY_DISEASES_MOD_DET_FK1` FOREIGN KEY (`COMMUNITY_DISEASE_ID`) REFERENCES `community_diseases` (`COMMUNITY_DISEASE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `COMMUNITY_DISEASES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `community_members`
--
ALTER TABLE `community_members`
  ADD CONSTRAINT `COMMUNITY_MEMBERS_FK1` FOREIGN KEY (`COMMUNITY_ID`) REFERENCES `communities` (`COMMUNITY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `COMMUNITY_MEMBERS_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `COMMUNITY_MEMBERS_FK3` FOREIGN KEY (`USER_TYPE_ID`) REFERENCES `user_type` (`USER_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `COMMUNITY_MEMBERS_FK4` FOREIGN KEY (`INVITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `COMMUNITY_MEMBERS_FK5` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `COMMUNITY_MEMBERS_FK6` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `community_members_mod_det`
--
ALTER TABLE `community_members_mod_det`
  ADD CONSTRAINT `COMMUNITY_MEMBERS_MOD_DET_FK1` FOREIGN KEY (`COMMUNITY_MEMBER_ID`) REFERENCES `community_members` (`COMMUNITY_MEMBER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `COMMUNITY_MEMBERS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `community_mod_det`
--
ALTER TABLE `community_mod_det`
  ADD CONSTRAINT `COMMUNITY_MOD_DET_FK1` FOREIGN KEY (`COMMUNITY_ID`) REFERENCES `communities` (`COMMUNITY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `COMMUNITY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `community_photos`
--
ALTER TABLE `community_photos`
  ADD CONSTRAINT `COMMUNITY_PHOTOS_FK1` FOREIGN KEY (`COMMUNITY_ID`) REFERENCES `communities` (`COMMUNITY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `COMMUNITY_PHOTOS_FK2` FOREIGN KEY (`PHOTO_TYPE_ID`) REFERENCES `photo_type_master` (`PHOTO_TYPE_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `COMMUNITY_PHOTOS_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `COMMUNITY_PHOTOS_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `community_photos_mod_det`
--
ALTER TABLE `community_photos_mod_det`
  ADD CONSTRAINT `COMMUNITY_PHOTOS_MOD_DET_FK1` FOREIGN KEY (`COMMUNITY_PHOTO_ID`) REFERENCES `community_photos` (`COMMUNITY_PHOTO_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `COMMUNITY_PHOTOS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `community_type_master`
--
ALTER TABLE `community_type_master`
  ADD CONSTRAINT `COMMUNITY_TYPE_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `COMMUNITY_TYPE_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `community_type_mod_det`
--
ALTER TABLE `community_type_mod_det`
  ADD CONSTRAINT `COMMUNITY_TYPE_MOD_DET_FK1` FOREIGN KEY (`COMMUNITY_TYPE_ID`) REFERENCES `community_type_master` (`COMMUNITY_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `COMMUNITY_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `configurations`
--
ALTER TABLE `configurations`
  ADD CONSTRAINT `CONFIGURATIONS_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `CONFIGURATIONS_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `configurations_mod_det`
--
ALTER TABLE `configurations_mod_det`
  ADD CONSTRAINT `CONFIGURATIONS_MOD_DET_FK1` FOREIGN KEY (`CONFIGURATION_ID`) REFERENCES `configurations` (`CONFIGURATION_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `CONFIGURATIONS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `country_master`
--
ALTER TABLE `country_master`
  ADD CONSTRAINT `COUNTRY_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `COUNTRY_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `country_mod_det`
--
ALTER TABLE `country_mod_det`
  ADD CONSTRAINT `COUNTRY_MOD_DET_FK1` FOREIGN KEY (`COUNTRY_ID`) REFERENCES `country_master` (`COUNTRY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `COUNTRY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `cron_task_exec_log`
--
ALTER TABLE `cron_task_exec_log`
  ADD CONSTRAINT `CRON_TASK_EXEC_LOG_FK1` FOREIGN KEY (`TASK_ID`) REFERENCES `cron_tasks` (`TASK_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `CRON_TASK_EXEC_LOG_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `CRON_TASK_EXEC_LOG_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `cron_task_exec_log_mod_det`
--
ALTER TABLE `cron_task_exec_log_mod_det`
  ADD CONSTRAINT `CRON_TASK_EXEC_LOG_MOD_DET_FK1` FOREIGN KEY (`CRON_TASK_EXEC_LOG_ID`) REFERENCES `cron_task_exec_log` (`CRON_TASK_EXEC_LOG_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `CRON_TASK_EXEC_LOG_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `cron_tasks`
--
ALTER TABLE `cron_tasks`
  ADD CONSTRAINT `CRON_TASKS_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `CRON_TASKS_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `CRON_TASKS_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `cron_tasks_mod_det`
--
ALTER TABLE `cron_tasks_mod_det`
  ADD CONSTRAINT `CRON_TASKS_MOD_DET_FK1` FOREIGN KEY (`TASK_ID`) REFERENCES `cron_tasks` (`TASK_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `CRON_TASKS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `dates`
--
ALTER TABLE `dates`
  ADD CONSTRAINT `DATE_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `DATE_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `dates_mod_det`
--
ALTER TABLE `dates_mod_det`
  ADD CONSTRAINT `DATES_MOD_DET_FK1` FOREIGN KEY (`DATE_ID`) REFERENCES `dates` (`DATE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `DATES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `disease_master`
--
ALTER TABLE `disease_master`
  ADD CONSTRAINT `DISEASE_MASTER_FK1` FOREIGN KEY (`PARENT_DISEASE_ID`) REFERENCES `disease_master` (`DISEASE_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `DISEASE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `DISEASE_MASTER_FK3` FOREIGN KEY (`DISEASE_SURVEY_ID`) REFERENCES `survey_master` (`SURVEY_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `DISEASE_MASTER_FK4` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `disease_mod_det`
--
ALTER TABLE `disease_mod_det`
  ADD CONSTRAINT `DISEASE_MOD_DET_FK1` FOREIGN KEY (`DISEASE_ID`) REFERENCES `disease_master` (`DISEASE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `DISEASE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `disease_symptoms`
--
ALTER TABLE `disease_symptoms`
  ADD CONSTRAINT `DISEASE_SYMPTOMS_FK1` FOREIGN KEY (`DISEASE_ID`) REFERENCES `disease_master` (`DISEASE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `DISEASE_SYMPTOMS_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `DISEASE_SYMPTOMS_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `DISEASE_SYMPTOMS_FK4` FOREIGN KEY (`SYMPTOM_ID`) REFERENCES `symptoms_master` (`SYMPTOM_ID`) ON DELETE CASCADE;

--
-- Constraints for table `disease_symptoms_mod_det`
--
ALTER TABLE `disease_symptoms_mod_det`
  ADD CONSTRAINT `DISEASE_SYMPTOMS_MOD_DET_FK1` FOREIGN KEY (`DISEASE_SYMPTOM_ID`) REFERENCES `disease_symptoms` (`DISEASE_SYMPTOM_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `DISEASE_SYMPTOMS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `disease_type_master`
--
ALTER TABLE `disease_type_master`
  ADD CONSTRAINT `DISEASE_TYPE_MASTER_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `DISEASE_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `disease_type_mod_det`
--
ALTER TABLE `disease_type_mod_det`
  ADD CONSTRAINT `DISEASE_TYPE_MOD_DET_FK1` FOREIGN KEY (`DISEASE_TYPE_ID`) REFERENCES `disease_type_master` (`DISEASE_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `DISEASE_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `email_attributes`
--
ALTER TABLE `email_attributes`
  ADD CONSTRAINT `EMAIL_ATTRIBUTES_FK1` FOREIGN KEY (`EMAIL_ID`) REFERENCES `emails` (`EMAIL_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EMAIL_ATTRIBUTES_FK2` FOREIGN KEY (`ATTRIBUTE_ID`) REFERENCES `attributes_master` (`ATTRIBUTE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EMAIL_ATTRIBUTES_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EMAIL_ATTRIBUTES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `email_attributes_mod_det`
--
ALTER TABLE `email_attributes_mod_det`
  ADD CONSTRAINT `EMAIL_ATTRIBUTES_MOD_DET_FK1` FOREIGN KEY (`EMAIL_ATTRIBUTE_ID`) REFERENCES `email_attributes` (`EMAIL_ATTRIBUTE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EMAIL_ATTRIBUTES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `email_history`
--
ALTER TABLE `email_history`
  ADD CONSTRAINT `EMAIL_HISTORY_FK1` FOREIGN KEY (`EMAIL_TEMPLATE_ID`) REFERENCES `email_templates` (`TEMPLATE_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EMAIL_HISTORY_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EMAIL_HISTORY_FK3` FOREIGN KEY (`PRIORITY_ID`) REFERENCES `email_priority_master` (`EMAIL_PRIORITY_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EMAIL_HISTORY_FK4` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EMAIL_HISTORY_FK5` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `email_history_attributes`
--
ALTER TABLE `email_history_attributes`
  ADD CONSTRAINT `EMAIL_HISTORY_ATTRIBUTES_FK1` FOREIGN KEY (`EMAIL_HISTORY_ID`) REFERENCES `email_history` (`EMAIL_HISTORY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EMAIL_HISTORY_ATTRIBUTES_FK2` FOREIGN KEY (`ATTRIBUTE_ID`) REFERENCES `attributes_master` (`ATTRIBUTE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EMAIL_HISTORY_ATTRIBUTES_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EMAIL_HISTORY_ATTRIBUTES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `email_history_attributes_mod_det`
--
ALTER TABLE `email_history_attributes_mod_det`
  ADD CONSTRAINT `EMAIL_HISTORY_ATTRIBUTES_MOD_DET_FK1` FOREIGN KEY (`EMAIL_HISTORY_ATTRIBUTE_ID`) REFERENCES `email_history_attributes` (`EMAIL_HISTORY_ATTRIBUTE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EMAIL_HISTORY_ATTRIBUTES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `email_history_mod_det`
--
ALTER TABLE `email_history_mod_det`
  ADD CONSTRAINT `EMAIL_HISTORY_MOD_DET_FK1` FOREIGN KEY (`EMAIL_HISTORY_ID`) REFERENCES `email_history` (`EMAIL_HISTORY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EMAIL_HISTORY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `email_mod_det`
--
ALTER TABLE `email_mod_det`
  ADD CONSTRAINT `EMAIL_MOD_DET_FK1` FOREIGN KEY (`EMAIL_ID`) REFERENCES `emails` (`EMAIL_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EMAIL_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `email_priority_master`
--
ALTER TABLE `email_priority_master`
  ADD CONSTRAINT `EMAIL_PRIORITY_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EMAIL_PRIORITY_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `email_priority_mod_det`
--
ALTER TABLE `email_priority_mod_det`
  ADD CONSTRAINT `EMAIL_PRIORITY_MOD_DET_FK1` FOREIGN KEY (`EMAIL_PRIORITY_ID`) REFERENCES `email_priority_master` (`EMAIL_PRIORITY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EMAIL_PRIORITY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD CONSTRAINT `EMAIL_TEMPLATES_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EMAIL_TEMPLATES_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EMAIL_TEMPLATES_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `email_templates_mod_det`
--
ALTER TABLE `email_templates_mod_det`
  ADD CONSTRAINT `EMAIL_TEMPLATE_MOD_DET_FK1` FOREIGN KEY (`TEMPLATE_ID`) REFERENCES `email_templates` (`TEMPLATE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EMAIL_TEMPLATE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `emails`
--
ALTER TABLE `emails`
  ADD CONSTRAINT `EMAIL_FK1` FOREIGN KEY (`EMAIL_TEMPLATE_ID`) REFERENCES `email_templates` (`TEMPLATE_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EMAIL_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EMAIL_FK3` FOREIGN KEY (`PRIORITY_ID`) REFERENCES `email_priority_master` (`EMAIL_PRIORITY_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EMAIL_FK4` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EMAIL_FK5` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `event_attributes`
--
ALTER TABLE `event_attributes`
  ADD CONSTRAINT `EVENT_ATTRIBUTES_FK1` FOREIGN KEY (`EVENT_ID`) REFERENCES `events` (`EVENT_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EVENT_ATTRIBUTES_FK2` FOREIGN KEY (`ATTRIBUTE_ID`) REFERENCES `attributes_master` (`ATTRIBUTE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EVENT_ATTRIBUTES_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EVENT_ATTRIBUTES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `event_attributes_mod_det`
--
ALTER TABLE `event_attributes_mod_det`
  ADD CONSTRAINT `EVENT_ATTRIBUTES_MOD_DET_FK1` FOREIGN KEY (`EVENT_ATTRIBUTE_ID`) REFERENCES `event_attributes` (`EVENT_ATTRIBUTE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EVENT_ATTRIBUTES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `event_diseases`
--
ALTER TABLE `event_diseases`
  ADD CONSTRAINT `EVENT_DISEASES_FK1` FOREIGN KEY (`EVENT_ID`) REFERENCES `events` (`EVENT_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EVENT_DISEASES_FK2` FOREIGN KEY (`DISEASE_ID`) REFERENCES `disease_master` (`DISEASE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EVENT_DISEASES_FK3` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EVENT_DISEASES_FK4` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EVENT_DISEASES_FK5` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `event_diseases_mod_det`
--
ALTER TABLE `event_diseases_mod_det`
  ADD CONSTRAINT `EVENT_DISEASES_MOD_DET_FK1` FOREIGN KEY (`EVENT_DISEASE_ID`) REFERENCES `event_diseases` (`EVENT_DISEASE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EVENT_DISEASES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `event_members`
--
ALTER TABLE `event_members`
  ADD CONSTRAINT `EVENT_MEMBERS_FK1` FOREIGN KEY (`EVENT_ID`) REFERENCES `events` (`EVENT_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EVENT_MEMBERS_FK2` FOREIGN KEY (`MEMBER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EVENT_MEMBERS_FK3` FOREIGN KEY (`MEMBER_ROLE_ID`) REFERENCES `user_type` (`USER_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EVENT_MEMBERS_FK4` FOREIGN KEY (`INVITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EVENT_MEMBERS_FK5` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EVENT_MEMBERS_FK6` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EVENT_MEMBERS_FK7` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `event_members_mod_det`
--
ALTER TABLE `event_members_mod_det`
  ADD CONSTRAINT `EVENT_MEMBERS_MOD_DET_FK1` FOREIGN KEY (`EVENT_MEMBER_ID`) REFERENCES `event_members` (`EVENT_MEMBER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EVENT_MEMBERS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `event_mod_det`
--
ALTER TABLE `event_mod_det`
  ADD CONSTRAINT `EVENT_MOD_DET_FK1` FOREIGN KEY (`EVENT_ID`) REFERENCES `events` (`EVENT_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EVENT_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `event_type_master`
--
ALTER TABLE `event_type_master`
  ADD CONSTRAINT `EVENT_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EVENT_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `event_type_mod_det`
--
ALTER TABLE `event_type_mod_det`
  ADD CONSTRAINT `EVENT_TYPE_MOD_DET_FK1` FOREIGN KEY (`EVENT_TYPE_ID`) REFERENCES `event_type_master` (`EVENT_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EVENT_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `EVENT_FK1` FOREIGN KEY (`EVENT_TYPE_ID`) REFERENCES `event_type_master` (`EVENT_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EVENT_FK10` FOREIGN KEY (`REPEAT_BY_TYPE_ID`) REFERENCES `repeat_by_type_master` (`REPEAT_BY_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EVENT_FK11` FOREIGN KEY (`REPEAT_END_TYPE_ID`) REFERENCES `repeat_end_type_master` (`REPEAT_END_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EVENT_FK12` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EVENT_FK13` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EVENT_FK2` FOREIGN KEY (`COMMUNITY_ID`) REFERENCES `communities` (`COMMUNITY_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EVENT_FK3` FOREIGN KEY (`REPEAT_TYPE_ID`) REFERENCES `repeat_type_master` (`REPEAT_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EVENT_FK4` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EVENT_FK5` FOREIGN KEY (`PUBLISH_TYPE_ID`) REFERENCES `publish_type_master` (`PUBLISH_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EVENT_FK6` FOREIGN KEY (`SECTION_TYPE_ID`) REFERENCES `section_type_master` (`SECTION_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `EVENT_FK7` FOREIGN KEY (`SECTION_TEAM_ID`) REFERENCES `teams` (`TEAM_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EVENT_FK8` FOREIGN KEY (`SECTION_COMMUNITY_ID`) REFERENCES `communities` (`COMMUNITY_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `EVENT_FK9` FOREIGN KEY (`REPEAT_MODE_TYPE_ID`) REFERENCES `repeat_mode_type_master` (`REPEAT_MODE_TYPE_ID`) ON DELETE CASCADE;

--
-- Constraints for table `following_pages_mod_det`
--
ALTER TABLE `following_pages_mod_det`
  ADD CONSTRAINT `FOLLOWING_PAGES_MOD_DET_FK1` FOREIGN KEY (`FOLLOWING_PAGE_ID`) REFERENCES `following_pages` (`FOLLOWING_PAGE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `FOLLOWING_PAGES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `health_cond_group_mod_det`
--
ALTER TABLE `health_cond_group_mod_det`
  ADD CONSTRAINT `HEALTH_COND_GROUP_MOD_DET_FK1` FOREIGN KEY (`HEALTH_CONDITION_GROUP_ID`) REFERENCES `health_condition_groups` (`HEALTH_CONDITION_GROUP_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `HEALTH_COND_GROUP_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `health_condition_groups`
--
ALTER TABLE `health_condition_groups`
  ADD CONSTRAINT `HEALTH_CONDITION_GROUPS_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `HEALTH_CONDITION_GROUPS_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `health_condition_master`
--
ALTER TABLE `health_condition_master`
  ADD CONSTRAINT `HEALTH_CONDITION_FK1` FOREIGN KEY (`HEALTH_CONDITION_GROUP_ID`) REFERENCES `health_condition_groups` (`HEALTH_CONDITION_GROUP_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `HEALTH_CONDITION_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `HEALTH_CONDITION_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `health_condition_mod_det`
--
ALTER TABLE `health_condition_mod_det`
  ADD CONSTRAINT `HEALTH_CONDITION_MOD_DET_FK1` FOREIGN KEY (`HEALTH_CONDITION_ID`) REFERENCES `health_condition_master` (`HEALTH_CONDITION_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `HEALTH_CONDITION_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `invited_users`
--
ALTER TABLE `invited_users`
  ADD CONSTRAINT `INVITED_USERS_FK1` FOREIGN KEY (`INVITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `INVITED_USERS_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `invited_users_mod_det`
--
ALTER TABLE `invited_users_mod_det`
  ADD CONSTRAINT `INVITED_USERS_MOD_DET_FK1` FOREIGN KEY (`INVITED_USER_ID`) REFERENCES `invited_users` (`INVITED_USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `INVITED_USERS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `language_mod_det`
--
ALTER TABLE `language_mod_det`
  ADD CONSTRAINT `LANGUAGE_MOD_DET_FK1` FOREIGN KEY (`LANGUAGE_ID`) REFERENCES `languages` (`LANGUAGE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `LANGUAGE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `languages`
--
ALTER TABLE `languages`
  ADD CONSTRAINT `LANGUAGE_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `LANGUAGE_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `media_type_master`
--
ALTER TABLE `media_type_master`
  ADD CONSTRAINT `MEDIA_TYPE_MASTER_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `MEDIA_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `media_type_mod_det`
--
ALTER TABLE `media_type_mod_det`
  ADD CONSTRAINT `MEDIA_TYPE_MOD_DET_FK1` FOREIGN KEY (`MEDIA_TYPE_ID`) REFERENCES `media_type_master` (`MEDIA_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `MEDIA_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `message_recipient_roles`
--
ALTER TABLE `message_recipient_roles`
  ADD CONSTRAINT `MESSAGE_RECIPIENT_ROLES_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `MESSAGE_RECIPIENT_ROLES_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `message_role_mod_det`
--
ALTER TABLE `message_role_mod_det`
  ADD CONSTRAINT `MESSAGE_ROLE_MOD_DET_FK1` FOREIGN KEY (`MESSAGE_RECIPIENT_ROLE_ID`) REFERENCES `message_recipient_roles` (`MESSAGE_RECIPIENT_ROLE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `MESSAGE_ROLE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `module_master`
--
ALTER TABLE `module_master`
  ADD CONSTRAINT `MODULE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `MODULE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `module_mod_det`
--
ALTER TABLE `module_mod_det`
  ADD CONSTRAINT `MODULE_MOD_DET_FK1` FOREIGN KEY (`MODULE_ID`) REFERENCES `module_master` (`MODULE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `MODULE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `month_mod_det`
--
ALTER TABLE `month_mod_det`
  ADD CONSTRAINT `MONTH_MOD_DET_FK1` FOREIGN KEY (`MONTH_ID`) REFERENCES `months_master` (`MONTH_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `MONTH_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `months_master`
--
ALTER TABLE `months_master`
  ADD CONSTRAINT `MONTH_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `MONTH_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `mood_master`
--
ALTER TABLE `mood_master`
  ADD CONSTRAINT `USER_MOOD_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `USER_MOOD_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `mood_mod_det`
--
ALTER TABLE `mood_mod_det`
  ADD CONSTRAINT `MOOD_MOD_DET_FK1` FOREIGN KEY (`MOOD_ID`) REFERENCES `mood_master` (`USER_MOOD_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `MOOD_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `my_friend_mod_det`
--
ALTER TABLE `my_friend_mod_det`
  ADD CONSTRAINT `MY_FRIEND_MOD_DET_FK1` FOREIGN KEY (`MY_FRIEND_ID`) REFERENCES `my_friends` (`MY_FRIEND_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `MY_FRIEND_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `my_friends`
--
ALTER TABLE `my_friends`
  ADD CONSTRAINT `MY_FRIENDS_FK1` FOREIGN KEY (`MY_USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `MY_FRIENDS_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `MY_FRIENDS_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `my_friends_detail_mod_det`
--
ALTER TABLE `my_friends_detail_mod_det`
  ADD CONSTRAINT `MY_FRIENDS_DETAIL_MOD_DET_FK1` FOREIGN KEY (`MY_FRIENDS_DETAIL_ID`) REFERENCES `my_friends_details` (`MY_FRIENDS_DETAIL_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `MY_FRIENDS_DETAIL_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `my_friends_details`
--
ALTER TABLE `my_friends_details`
  ADD CONSTRAINT `MY_FRIENDS_DETAILS_FK1` FOREIGN KEY (`MY_FRIEND_ID`) REFERENCES `my_friends` (`MY_FRIEND_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `MY_FRIENDS_DETAILS_FK2` FOREIGN KEY (`FRIEND_USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `MY_FRIENDS_DETAILS_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `MY_FRIENDS_DETAILS_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `newsletter_mod_det`
--
ALTER TABLE `newsletter_mod_det`
  ADD CONSTRAINT `NEWSLETTER_MOD_DET_FK1` FOREIGN KEY (`NEWSLETTER_ID`) REFERENCES `newsletters` (`NEWSLETTER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NEWSLETTER_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `newsletter_queue_mod_det`
--
ALTER TABLE `newsletter_queue_mod_det`
  ADD CONSTRAINT `NEWSLETTER_QUEUE_MOD_DET_FK1` FOREIGN KEY (`NEWSLETTER_QUEUE_ID`) REFERENCES `newsletter_queue_status` (`NEWSLETTER_QUEUE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NEWSLETTER_QUEUE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `newsletter_queue_status`
--
ALTER TABLE `newsletter_queue_status`
  ADD CONSTRAINT `NEWSLETTER_QUEUE_STATUS_FK1` FOREIGN KEY (`NEWSLETTER_ID`) REFERENCES `newsletters` (`NEWSLETTER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NEWSLETTER_QUEUE_STATUS_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `NEWSLETTER_QUEUE_STATUS_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `NEWSLETTER_QUEUE_STATUS_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `newsletter_template_mod_det`
--
ALTER TABLE `newsletter_template_mod_det`
  ADD CONSTRAINT `NEWSLETTER_TEMPLATE_MOD_DET_FK1` FOREIGN KEY (`NEWSLETTER_TEMPLATE_ID`) REFERENCES `newsletter_templates` (`NEWSLETTER_TEMPLATE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NEWSLETTER_TEMPLATE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `newsletter_templates`
--
ALTER TABLE `newsletter_templates`
  ADD CONSTRAINT `NEWSLETTER_TEMPLATES_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `NEWSLETTER_TEMPLATES_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `NEWSLETTER_TEMPLATES_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `newsletters`
--
ALTER TABLE `newsletters`
  ADD CONSTRAINT `NEWSLETTERS_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `NEWSLETTERS_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `NEWSLETTERS_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `notification_activity_mod_det`
--
ALTER TABLE `notification_activity_mod_det`
  ADD CONSTRAINT `NOTIFICATION_ACTIVITY_MOD_DET_FK1` FOREIGN KEY (`NOTIFICATION_ACTIVITY_TYPE_ID`) REFERENCES `notification_activity_type_master` (`NOTIFICATION_ACTIVITY_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NOTIFICATION_ACTIVITY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `notification_activity_type_master`
--
ALTER TABLE `notification_activity_type_master`
  ADD CONSTRAINT `NOTIFICATION_ACTIVITY_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `NOTIFICATION_ACTIVITY_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `notification_frequency_master`
--
ALTER TABLE `notification_frequency_master`
  ADD CONSTRAINT `NOTIFICATION_FREQUENCY_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `NOTIFICATION_FREQUENCY_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `notification_frequency_mod_det`
--
ALTER TABLE `notification_frequency_mod_det`
  ADD CONSTRAINT `NOTIFICATION_FREQUENCY_MOD_DET_FK1` FOREIGN KEY (`NOTIFICATION_FREQUENCY_ID`) REFERENCES `notification_frequency_master` (`NOTIFICATION_FREQUENCY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NOTIFICATION_FREQUENCY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `notification_mod_det`
--
ALTER TABLE `notification_mod_det`
  ADD CONSTRAINT `NOTIFICATION_MOD_DET_FK1` FOREIGN KEY (`NOTIFICATION_ID`) REFERENCES `notifications` (`NOTIFICATION_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NOTIFICATION_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `notification_object_type_master`
--
ALTER TABLE `notification_object_type_master`
  ADD CONSTRAINT `NOTIFICATION_OBJECT_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `NOTIFICATION_OBJECT_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `notification_object_type_mod_det`
--
ALTER TABLE `notification_object_type_mod_det`
  ADD CONSTRAINT `NOTIFICATION_OBJECT_TYPE_MOD_DET_FK1` FOREIGN KEY (`NOTIFICATION_OBJECT_TYPE_ID`) REFERENCES `notification_object_type_master` (`NOTIFICATION_OBJECT_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NOTIFICATION_OBJECT_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `notification_recipient_mod_det`
--
ALTER TABLE `notification_recipient_mod_det`
  ADD CONSTRAINT `NOTIFICATION_RECIPIENT_MOD_DET_FK1` FOREIGN KEY (`NOTIFICATION_RECIPIENT_ID`) REFERENCES `notification_recipients` (`NOTIFICATION_RECIPIENT_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NOTIFICATION_RECIPIENT_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `notification_recipients`
--
ALTER TABLE `notification_recipients`
  ADD CONSTRAINT `NOTIFICATION_RECIPIENT_FK1` FOREIGN KEY (`NOTIFICATION_ID`) REFERENCES `notifications` (`NOTIFICATION_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NOTIFICATION_RECIPIENT_FK2` FOREIGN KEY (`RECIPIENT_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NOTIFICATION_RECIPIENT_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `NOTIFICATION_RECIPIENT_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `notification_setting_mod_det`
--
ALTER TABLE `notification_setting_mod_det`
  ADD CONSTRAINT `NOTIFICATION_SETTING_MOD_DET_FK1` FOREIGN KEY (`NOTIFICATION_SETTING_ID`) REFERENCES `notification_settings` (`NOTIFICATION_SETTING_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NOTIFICATION_SETTING_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `notification_settings`
--
ALTER TABLE `notification_settings`
  ADD CONSTRAINT `NOTIFICATION_SETTING_FK1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NOTIFICATION_SETTING_FK2` FOREIGN KEY (`HEIGHT_UNIT`) REFERENCES `unit_of_measurement_master` (`UNIT_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `NOTIFICATION_SETTING_FK3` FOREIGN KEY (`WEIGHT_UNIT`) REFERENCES `unit_of_measurement_master` (`UNIT_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `NOTIFICATION_SETTING_FK4` FOREIGN KEY (`TEMP_UNIT`) REFERENCES `unit_of_measurement_master` (`UNIT_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `NOTIFICATION_SETTING_FK5` FOREIGN KEY (`NOTIFICATION_FREQUENCY_ID`) REFERENCES `notification_frequency_master` (`NOTIFICATION_FREQUENCY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NOTIFICATION_SETTING_FK6` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `NOTIFICATION_SETTING_FK7` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `NOTIFICATION_FK1` FOREIGN KEY (`NOTIFICATION_ACTIVITY_TYPE_ID`) REFERENCES `notification_activity_type_master` (`NOTIFICATION_ACTIVITY_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NOTIFICATION_FK2` FOREIGN KEY (`NOTIFICATION_OBJECT_TYPE_ID`) REFERENCES `notification_object_type_master` (`NOTIFICATION_OBJECT_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NOTIFICATION_FK3` FOREIGN KEY (`NOTIFICATION_ACTIVITY_SECTION_TYPE_ID`) REFERENCES `activity_section_master` (`ACTIVITY_SECTION_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NOTIFICATION_FK4` FOREIGN KEY (`SENDER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NOTIFICATION_FK5` FOREIGN KEY (`OBJECT_OWNER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `NOTIFICATION_FK6` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `NOTIFICATION_FK7` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `NOTIFICATION_FK8` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `notified_user_mod_det`
--
ALTER TABLE `notified_user_mod_det`
  ADD CONSTRAINT `NOTIFIED_USER_MOD_DET_FK1` FOREIGN KEY (`NOTIFIED_USER_ID`) REFERENCES `notified_users` (`NOTIFIED_USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NOTIFIED_USER_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `notified_users`
--
ALTER TABLE `notified_users`
  ADD CONSTRAINT `NOTIFIED_USER_FK1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NOTIFIED_USER_FK2` FOREIGN KEY (`NOTIFICATION_SETTING_ID`) REFERENCES `notification_settings` (`NOTIFICATION_SETTING_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `NOTIFIED_USER_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `NOTIFIED_USER_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `page_master`
--
ALTER TABLE `page_master`
  ADD CONSTRAINT `PAGE_MASTER_FK1` FOREIGN KEY (`PAGE_TYPE_ID`) REFERENCES `page_type_master` (`PAGE_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `PAGE_MASTER_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `PAGE_MASTER_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `page_mod_det`
--
ALTER TABLE `page_mod_det`
  ADD CONSTRAINT `PAGE_MOD_DET_FK1` FOREIGN KEY (`PAGE_ID`) REFERENCES `page_master` (`PAGE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `PAGE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `page_type_master`
--
ALTER TABLE `page_type_master`
  ADD CONSTRAINT `PAGE_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `PAGE_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `page_type_mod_det`
--
ALTER TABLE `page_type_mod_det`
  ADD CONSTRAINT `PAGE_TYPE_MOD_DET_FK1` FOREIGN KEY (`PAGE_TYPE_ID`) REFERENCES `page_type_master` (`PAGE_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `PAGE_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `pain_level_mod_det`
--
ALTER TABLE `pain_level_mod_det`
  ADD CONSTRAINT `PAIN_LEVEL_MOD_DET_FK1` FOREIGN KEY (`PAIN_LEVEL_ID`) REFERENCES `pain_levels_master` (`PAIN_LEVEL_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `PAIN_LEVEL_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `pain_levels_master`
--
ALTER TABLE `pain_levels_master`
  ADD CONSTRAINT `PAIN_LEVELS_MASTER_FK1` FOREIGN KEY (`PAIN_ID`) REFERENCES `pain_master` (`PAIN_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `PAIN_LEVELS_MASTER_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `PAIN_LEVELS_MASTER_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `pain_master`
--
ALTER TABLE `pain_master`
  ADD CONSTRAINT `PAIN_MASTER_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `PAIN_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `pain_type_mod_det`
--
ALTER TABLE `pain_type_mod_det`
  ADD CONSTRAINT `PAIN_TYPE_MOD_DET_FK1` FOREIGN KEY (`PAIN_ID`) REFERENCES `pain_master` (`PAIN_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `PAIN_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `patient_care_giver_mod_det`
--
ALTER TABLE `patient_care_giver_mod_det`
  ADD CONSTRAINT `PATIENT_CARE_GIVER_MOD_DET_FK1` FOREIGN KEY (`PATIENT_CARE_GIVER_ID`) REFERENCES `patient_care_givers` (`PATIENT_CARE_GIVER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `PATIENT_CARE_GIVER_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `patient_care_givers`
--
ALTER TABLE `patient_care_givers`
  ADD CONSTRAINT `PATIENT_CARE_GIVERS_FK1` FOREIGN KEY (`RELATIONSHIP_ID`) REFERENCES `caregiver_relationship_master` (`RELATIONSHIP_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `PATIENT_CARE_GIVERS_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `PATIENT_CARE_GIVERS_FK3` FOREIGN KEY (`PATIENT_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `PATIENT_CARE_GIVERS_FK4` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `PATIENT_CARE_GIVERS_FK5` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `photo_type_master`
--
ALTER TABLE `photo_type_master`
  ADD CONSTRAINT `PHOTO_TYPE_MASTER_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `PHOTO_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `photo_type_mod_det`
--
ALTER TABLE `photo_type_mod_det`
  ADD CONSTRAINT `PHOTO_TYPE_MOD_DET_FK1` FOREIGN KEY (`PHOTO_TYPE_ID`) REFERENCES `photo_type_master` (`PHOTO_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `PHOTO_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `poll_choice_mod_det`
--
ALTER TABLE `poll_choice_mod_det`
  ADD CONSTRAINT `POLL_CHOICE_MOD_DET_FK1` FOREIGN KEY (`POLL_CHOICE_ID`) REFERENCES `poll_choices` (`POLL_CHOICE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POLL_CHOICE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `poll_choices`
--
ALTER TABLE `poll_choices`
  ADD CONSTRAINT `POLL_CHOICE_FK1` FOREIGN KEY (`POLL_ID`) REFERENCES `polls` (`POLL_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POLL_CHOICE_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `POLL_CHOICE_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `poll_mod_det`
--
ALTER TABLE `poll_mod_det`
  ADD CONSTRAINT `POLL_MOD_DET_FK1` FOREIGN KEY (`POLL_ID`) REFERENCES `polls` (`POLL_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POLL_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `polls`
--
ALTER TABLE `polls`
  ADD CONSTRAINT `POLL_FK1` FOREIGN KEY (`POLL_SECTION_TYPE_ID`) REFERENCES `activity_section_master` (`ACTIVITY_SECTION_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `POLL_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `POLL_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `POLL_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD CONSTRAINT `POST_COMMENTS_FK1` FOREIGN KEY (`POST_ID`) REFERENCES `posts` (`POST_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POST_COMMENTS_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `POST_COMMENTS_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `post_comments_mod_det`
--
ALTER TABLE `post_comments_mod_det`
  ADD CONSTRAINT `POST_COMMENTS_MOD_DET_FK1` FOREIGN KEY (`POST_COMMENT_ID`) REFERENCES `post_comments` (`POST_COMMENT_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POST_COMMENTS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `post_content_details`
--
ALTER TABLE `post_content_details`
  ADD CONSTRAINT `POST_CONTENT_DETAILS_FK1` FOREIGN KEY (`POST_ID`) REFERENCES `posts` (`POST_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POST_CONTENT_DETAILS_FK2` FOREIGN KEY (`CONTENT_ATTRIBUTE_ID`) REFERENCES `attributes_master` (`ATTRIBUTE_ID`) ON DELETE CASCADE;

--
-- Constraints for table `post_content_mod_det`
--
ALTER TABLE `post_content_mod_det`
  ADD CONSTRAINT `POST_CONTENT_MOD_DET_FK1` FOREIGN KEY (`POST_CONTENT_ID`) REFERENCES `post_content_details` (`POST_CONTENT_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POST_CONTENT_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `POST_LIKES_FK1` FOREIGN KEY (`POST_ID`) REFERENCES `posts` (`POST_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POST_LIKES_FK2` FOREIGN KEY (`LIKED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `POST_LIKES_FK3` FOREIGN KEY (`POST_LIKE_STATUS`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `post_likes_mod_det`
--
ALTER TABLE `post_likes_mod_det`
  ADD CONSTRAINT `POST_LIKES_MOD_DET_FK1` FOREIGN KEY (`POST_LIKE_ID`) REFERENCES `post_likes` (`POST_LIKE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POST_LIKES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `post_location`
--
ALTER TABLE `post_location`
  ADD CONSTRAINT `POST_LOCATION_FK1` FOREIGN KEY (`POST_ID`) REFERENCES `posts` (`POST_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POST_LOCATION_FK2` FOREIGN KEY (`POST_LOCATION`) REFERENCES `post_location_master` (`POST_LOCATION_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POST_LOCATION_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `POST_LOCATION_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `post_location_master`
--
ALTER TABLE `post_location_master`
  ADD CONSTRAINT `POST_LOCATION_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `POST_LOCATION_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `post_location_master_mod_det`
--
ALTER TABLE `post_location_master_mod_det`
  ADD CONSTRAINT `POST_LOCATION_MASTER_MOD_DET_FK1` FOREIGN KEY (`POST_LOCATION_ID`) REFERENCES `post_location_master` (`POST_LOCATION_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POST_LOCATION_MASTER_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `post_location_mod_det`
--
ALTER TABLE `post_location_mod_det`
  ADD CONSTRAINT `POST_LOCATION_MOD_DET_FK1` FOREIGN KEY (`POST_LOCATION_ID`) REFERENCES `post_location` (`POST_LOCATION_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POST_LOCATION_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `post_mod_det`
--
ALTER TABLE `post_mod_det`
  ADD CONSTRAINT `POST_MOD_DET_FK1` FOREIGN KEY (`POST_ID`) REFERENCES `posts` (`POST_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POST_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `post_privacy_mod_det`
--
ALTER TABLE `post_privacy_mod_det`
  ADD CONSTRAINT `POST_PRIVACY_MOD_DET_FK1` FOREIGN KEY (`POST_PRIVACY_ID`) REFERENCES `post_privacy_settings` (`POST_PRIVACY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POST_PRIVACY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `post_privacy_settings`
--
ALTER TABLE `post_privacy_settings`
  ADD CONSTRAINT `POST_PRIVACY_FK1` FOREIGN KEY (`POST_ID`) REFERENCES `posts` (`POST_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POST_PRIVACY_FK2` FOREIGN KEY (`USER_TYPE_ID`) REFERENCES `user_type` (`USER_TYPE_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `POST_PRIVACY_FK3` FOREIGN KEY (`PRIVACY_ID`) REFERENCES `privacy_master` (`PRIVACY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POST_PRIVACY_FK4` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `POST_PRIVACY_FK5` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `post_type_master`
--
ALTER TABLE `post_type_master`
  ADD CONSTRAINT `POST_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `POST_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `post_type_mod_det`
--
ALTER TABLE `post_type_mod_det`
  ADD CONSTRAINT `POST_TYPE_MOD_DET_FK1` FOREIGN KEY (`POST_TYPE_ID`) REFERENCES `post_type_master` (`POST_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POST_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `POSTS_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `POSTS_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `POSTS_FK5` FOREIGN KEY (`POST_TYPE_ID`) REFERENCES `post_type_master` (`POST_TYPE_ID`) ON DELETE CASCADE;

--
-- Constraints for table `privacy_master`
--
ALTER TABLE `privacy_master`
  ADD CONSTRAINT `PRIVACY_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `PRIVACY_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `privacy_mod_det`
--
ALTER TABLE `privacy_mod_det`
  ADD CONSTRAINT `PRIVACY_MOD_DET_FK1` FOREIGN KEY (`PRIVACY_ID`) REFERENCES `privacy_master` (`PRIVACY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `PRIVACY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `publish_type_master`
--
ALTER TABLE `publish_type_master`
  ADD CONSTRAINT `PUBLISH_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `PUBLISH_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `publish_type_mod_det`
--
ALTER TABLE `publish_type_mod_det`
  ADD CONSTRAINT `PUBLISH_TYPE_MOD_DET_FK1` FOREIGN KEY (`PUBLISH_TYPE_ID`) REFERENCES `publish_type_master` (`PUBLISH_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `PUBLISH_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `question_group_master`
--
ALTER TABLE `question_group_master`
  ADD CONSTRAINT `QUESTION_GROUP_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `QUESTION_GROUP_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `question_group_mod_det`
--
ALTER TABLE `question_group_mod_det`
  ADD CONSTRAINT `QUESTION_GROUP_MOD_DET_FK1` FOREIGN KEY (`QUESTION_GROUP_ID`) REFERENCES `question_group_master` (`QUESTION_GROUP_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `QUESTION_GROUP_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `question_master`
--
ALTER TABLE `question_master`
  ADD CONSTRAINT `QUESTION_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `QUESTION_FK2` FOREIGN KEY (`QUESTION_GROUP_ID`) REFERENCES `question_group_master` (`QUESTION_GROUP_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `QUESTION_FK3` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `question_mod_det`
--
ALTER TABLE `question_mod_det`
  ADD CONSTRAINT `QUESTION_MOD_DET_FK1` FOREIGN KEY (`QUESTION_ID`) REFERENCES `question_master` (`QUESTION_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `QUESTION_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `repeat_by_type_master`
--
ALTER TABLE `repeat_by_type_master`
  ADD CONSTRAINT `REPEAT_BY_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `REPEAT_BY_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `repeat_by_type_mod_det`
--
ALTER TABLE `repeat_by_type_mod_det`
  ADD CONSTRAINT `REPEAT_BY_TYPE_MOD_DET_FK1` FOREIGN KEY (`REPEAT_BY_TYPE_ID`) REFERENCES `repeat_by_type_master` (`REPEAT_BY_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `REPEAT_BY_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `repeat_end_type_master`
--
ALTER TABLE `repeat_end_type_master`
  ADD CONSTRAINT `REPEAT_END_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `REPEAT_END_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `repeat_end_type_mod_det`
--
ALTER TABLE `repeat_end_type_mod_det`
  ADD CONSTRAINT `REPEAT_END_TYPE_MOD_DET_FK1` FOREIGN KEY (`REPEAT_END_TYPE_ID`) REFERENCES `repeat_end_type_master` (`REPEAT_END_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `REPEAT_END_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `repeat_mode_type_master`
--
ALTER TABLE `repeat_mode_type_master`
  ADD CONSTRAINT `REPEAT_MODE_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `REPEAT_MODE_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `repeat_mode_type_mod_det`
--
ALTER TABLE `repeat_mode_type_mod_det`
  ADD CONSTRAINT `REPEAT_MODE_TYPE_MOD_DET_FK1` FOREIGN KEY (`REPEAT_MODE_TYPE_ID`) REFERENCES `repeat_mode_type_master` (`REPEAT_MODE_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `REPEAT_MODE_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `repeat_type_master`
--
ALTER TABLE `repeat_type_master`
  ADD CONSTRAINT `REPEAT_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `REPEAT_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `repeat_type_mod_det`
--
ALTER TABLE `repeat_type_mod_det`
  ADD CONSTRAINT `REPEAT_TYPE_MOD_DET_FK1` FOREIGN KEY (`REPEAT_TYPE_ID`) REFERENCES `repeat_type_master` (`REPEAT_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `REPEAT_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `section_type_master`
--
ALTER TABLE `section_type_master`
  ADD CONSTRAINT `SECTION_TYPE_MASTER_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `SECTION_TYPE_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `section_type_mod_det`
--
ALTER TABLE `section_type_mod_det`
  ADD CONSTRAINT `SECTION_TYPE_MOD_DET_FK1` FOREIGN KEY (`SECTION_TYPE_ID`) REFERENCES `section_type_master` (`SECTION_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `SECTION_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `states_master`
--
ALTER TABLE `states_master`
  ADD CONSTRAINT `STATES_FK1` FOREIGN KEY (`COUNTRY_ID`) REFERENCES `country_master` (`COUNTRY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `STATES_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `STATES_FK3` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `STATES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `states_mod_det`
--
ALTER TABLE `states_mod_det`
  ADD CONSTRAINT `STATES_MOD_DET_FK1` FOREIGN KEY (`STATE_ID`) REFERENCES `states_master` (`STATE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `STATES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `status`
--
ALTER TABLE `status`
  ADD CONSTRAINT `STATUS_FK1` FOREIGN KEY (`STATUS_TYPE_ID`) REFERENCES `status_type` (`STATUS_TYPE_ID`),
  ADD CONSTRAINT `STATUS_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `status_mod_det`
--
ALTER TABLE `status_mod_det`
  ADD CONSTRAINT `STATUS_MOD_DET_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `STATUS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `status_type`
--
ALTER TABLE `status_type`
  ADD CONSTRAINT `STATUS_TYPE_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `status_type_mod_det`
--
ALTER TABLE `status_type_mod_det`
  ADD CONSTRAINT `STATUS_TYPE_MOD_DET_FK1` FOREIGN KEY (`STATUS_TYPE_ID`) REFERENCES `status_type` (`STATUS_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `STATUS_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `survey_master`
--
ALTER TABLE `survey_master`
  ADD CONSTRAINT `SURVEY_MASTER_FK1` FOREIGN KEY (`SURVEY_TYPE`) REFERENCES `survey_type_master` (`SURVEY_TYPE_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `SURVEY_MASTER_FK2` FOREIGN KEY (`SURVEY_STATUS`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `SURVEY_MASTER_FK3` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `survey_mod_det`
--
ALTER TABLE `survey_mod_det`
  ADD CONSTRAINT `SURVEY_MOD_DET_FK1` FOREIGN KEY (`SURVEY_ID`) REFERENCES `survey_master` (`SURVEY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `SURVEY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `survey_questions`
--
ALTER TABLE `survey_questions`
  ADD CONSTRAINT `SURVEY_QUESTIONS_FK1` FOREIGN KEY (`SURVEY_ID`) REFERENCES `survey_master` (`SURVEY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `SURVEY_QUESTIONS_FK2` FOREIGN KEY (`QUESTION_ID`) REFERENCES `question_master` (`QUESTION_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `SURVEY_QUESTIONS_FK3` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `SURVEY_QUESTIONS_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `survey_questions_answer_choices`
--
ALTER TABLE `survey_questions_answer_choices`
  ADD CONSTRAINT `SURVEY_QUESTIONS_ANSWER_CHOICES_FK1` FOREIGN KEY (`SURVEY_QUESTION_ID`) REFERENCES `survey_questions` (`SURVEY_QUESTION_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `SURVEY_QUESTIONS_ANSWER_CHOICES_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `SURVEY_QUESTIONS_ANSWER_CHOICES_FK3` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `survey_questions_answer_choices_mod_det`
--
ALTER TABLE `survey_questions_answer_choices_mod_det`
  ADD CONSTRAINT `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET_FK1` FOREIGN KEY (`SURVEY_QUESTIONS_ANSWER_CHOICE_ID`) REFERENCES `survey_questions_answer_choices` (`SURVEY_QUESTIONS_ANSWER_CHOICE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `SURVEY_QUESTIONS_ANSWER_CHOICES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `survey_questions_mod_det`
--
ALTER TABLE `survey_questions_mod_det`
  ADD CONSTRAINT `SURVEY_QUESTIONS_MOD_DET_FK1` FOREIGN KEY (`SURVEY_QUESTION_ID`) REFERENCES `survey_questions` (`SURVEY_QUESTION_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `SURVEY_QUESTIONS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `survey_results_answer_choices`
--
ALTER TABLE `survey_results_answer_choices`
  ADD CONSTRAINT `SURVEY_RESULTS_ANSWER_CHOICES_FK1` FOREIGN KEY (`SURVEY_QUESTIONS_ANSWER_CHOICE_ID`) REFERENCES `survey_questions_answer_choices` (`SURVEY_QUESTIONS_ANSWER_CHOICE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `SURVEY_RESULTS_ANSWER_CHOICES_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `SURVEY_RESULTS_ANSWER_CHOICES_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `SURVEY_RESULTS_ANSWER_CHOICES_FK4` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `survey_results_answer_choices_mod_det`
--
ALTER TABLE `survey_results_answer_choices_mod_det`
  ADD CONSTRAINT `SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET_FK1` FOREIGN KEY (`SURVEY_RESULTS_ANSWER_CHOICE_ID`) REFERENCES `survey_results_answer_choices` (`SURVEY_RESULTS_ANSWER_CHOICE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `SURVEY_RESULTS_ANSWER_CHOICES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `survey_results_detailed_answers`
--
ALTER TABLE `survey_results_detailed_answers`
  ADD CONSTRAINT `SURVEY_RESULTS_DETAILED_ANSWERS_FK1` FOREIGN KEY (`SURVEY_QUESTION_ID`) REFERENCES `survey_questions` (`SURVEY_QUESTION_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `SURVEY_RESULTS_DETAILED_ANSWERS_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `SURVEY_RESULTS_DETAILED_ANSWERS_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `SURVEY_RESULTS_DETAILED_ANSWERS_FK4` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `survey_results_detailed_answers_mod_det`
--
ALTER TABLE `survey_results_detailed_answers_mod_det`
  ADD CONSTRAINT `SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET_FK1` FOREIGN KEY (`SURVEY_RESULTS_DETAILED_ANSWER_ID`) REFERENCES `survey_results_detailed_answers` (`SURVEY_RESULTS_DETAILED_ANSWER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `SURVEY_RESULTS_DETAILED_ANSWERS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `survey_type_master`
--
ALTER TABLE `survey_type_master`
  ADD CONSTRAINT `SURVEY_TYPE_MASTER_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `SURVEY_TYPE_MASTER_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `survey_type_mod_det`
--
ALTER TABLE `survey_type_mod_det`
  ADD CONSTRAINT `SURVEY_TYPE_MOD_DET_FK1` FOREIGN KEY (`SURVEY_TYPE_ID`) REFERENCES `survey_type_master` (`SURVEY_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `SURVEY_TYPE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `symptoms_master`
--
ALTER TABLE `symptoms_master`
  ADD CONSTRAINT `SYMPTOMS_MASTER_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `SYMPTOMS_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `symptoms_mod_det`
--
ALTER TABLE `symptoms_mod_det`
  ADD CONSTRAINT `SYMPTOM_MOD_DET_FK1` FOREIGN KEY (`SYMPTOM_ID`) REFERENCES `symptoms_master` (`SYMPTOM_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `SYMPTOM_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `TEAM_MEMBERS_FK1` FOREIGN KEY (`TEAM_ID`) REFERENCES `teams` (`TEAM_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `TEAM_MEMBERS_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `TEAM_MEMBERS_FK3` FOREIGN KEY (`USER_ROLE_ID`) REFERENCES `user_type` (`USER_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `TEAM_MEMBERS_FK4` FOREIGN KEY (`MEMBER_STATUS`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `TEAM_MEMBERS_FK5` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `team_members_mod_det`
--
ALTER TABLE `team_members_mod_det`
  ADD CONSTRAINT `TEAM_MEMBERS_MOD_DET_FK1` FOREIGN KEY (`TEAM_MEMBER_ID`) REFERENCES `team_members` (`TEAM_MEMBER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `TEAM_MEMBERS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `team_mod_det`
--
ALTER TABLE `team_mod_det`
  ADD CONSTRAINT `TEAM_MOD_DET_FK1` FOREIGN KEY (`TEAM_ID`) REFERENCES `teams` (`TEAM_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `TEAM_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `team_privacy_setting_mod_det`
--
ALTER TABLE `team_privacy_setting_mod_det`
  ADD CONSTRAINT `TEAM_PRIVACY_SETTING_MOD_DET_FK1` FOREIGN KEY (`TEAM_PRIVACY_SETTING_ID`) REFERENCES `team_privacy_settings` (`TEAM_PRIVACY_SETTING_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `TEAM_PRIVACY_SETTING_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `team_privacy_settings`
--
ALTER TABLE `team_privacy_settings`
  ADD CONSTRAINT `TEAM_PRIVACY_SETTINGS_FK1` FOREIGN KEY (`TEAM_ID`) REFERENCES `teams` (`TEAM_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `TEAM_PRIVACY_SETTINGS_FK2` FOREIGN KEY (`USER_TYPE_ID`) REFERENCES `user_type` (`USER_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `TEAM_PRIVACY_SETTINGS_FK3` FOREIGN KEY (`PRIVACY_ID`) REFERENCES `privacy_master` (`PRIVACY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `TEAM_PRIVACY_SETTINGS_FK4` FOREIGN KEY (`PRIVACY_SET_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `TEAM_PRIVACY_SETTINGS_FK5` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `TEAMS_FK1` FOREIGN KEY (`PATIENT_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `TEAMS_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `TEAMS_FK3` FOREIGN KEY (`TEAM_STATUS`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `timezone_master`
--
ALTER TABLE `timezone_master`
  ADD CONSTRAINT `TIMEZONE_FK1` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `TIMEZONE_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `timezone_mod_det`
--
ALTER TABLE `timezone_mod_det`
  ADD CONSTRAINT `TIMEZONE_MOD_DET_FK1` FOREIGN KEY (`TIMEZONE_ID`) REFERENCES `timezone_master` (`TIMEZONE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `TIMEZONE_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `treatment_master`
--
ALTER TABLE `treatment_master`
  ADD CONSTRAINT `TREATMENT_MASTER_FK1` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `TREATMENT_MASTER_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `treatment_master_mod_det`
--
ALTER TABLE `treatment_master_mod_det`
  ADD CONSTRAINT `TREATMENT_MASTER_MOD_DET_FK1` FOREIGN KEY (`TREATMENT_ID`) REFERENCES `treatment_master` (`TREATMENT_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `TREATMENT_MASTER_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `unit_of_measurement_master`
--
ALTER TABLE `unit_of_measurement_master`
  ADD CONSTRAINT `UOM_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `UOM_FK2` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `unit_of_measurement_mod_det`
--
ALTER TABLE `unit_of_measurement_mod_det`
  ADD CONSTRAINT `UOM_MOD_DET_FK1` FOREIGN KEY (`UNIT_ID`) REFERENCES `unit_of_measurement_master` (`UNIT_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `UOM_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  ADD CONSTRAINT `USER_ACTIVITY_LOGS_FK1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_ACTIVITY_LOGS_FK2` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `USER_ACTIVITY_LOGS_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `user_activity_mod_det`
--
ALTER TABLE `user_activity_mod_det`
  ADD CONSTRAINT `USER_ACTIVITY_MOD_DET_FK1` FOREIGN KEY (`USER_ACTIVITY_ID`) REFERENCES `user_activity_logs` (`USER_ACTIVITY_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_ACTIVITY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `user_attribute_mod_history`
--
ALTER TABLE `user_attribute_mod_history`
  ADD CONSTRAINT `USER_ATTRIBUTE_MOD_FK1` FOREIGN KEY (`USER_ATTRIBUTE_ID`) REFERENCES `user_attributes` (`USER_ATTRIBUTE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_ATTRIBUTE_MOD_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE;

--
-- Constraints for table `user_attributes`
--
ALTER TABLE `user_attributes`
  ADD CONSTRAINT `USER_ATTRIBUTES_FK1` FOREIGN KEY (`ATTRIBUTE_ID`) REFERENCES `attributes_master` (`ATTRIBUTE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_ATTRIBUTES_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_ATTRIBUTES_FK3` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_ATTRIBUTES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `user_diseases`
--
ALTER TABLE `user_diseases`
  ADD CONSTRAINT `USER_DISEASES_FK1` FOREIGN KEY (`DISEASE_ID`) REFERENCES `disease_master` (`DISEASE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_DISEASES_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_DISEASES_FK3` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `USER_DISEASES_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `user_diseases_mod_det`
--
ALTER TABLE `user_diseases_mod_det`
  ADD CONSTRAINT `USER_DISEASES_MOD_DET_FK1` FOREIGN KEY (`USER_DISEASE_ID`) REFERENCES `user_diseases` (`USER_DISEASE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_DISEASES_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `user_fav_posts_mod_det`
--
ALTER TABLE `user_fav_posts_mod_det`
  ADD CONSTRAINT `USER_FAV_POSTS_MOD_DET_FK1` FOREIGN KEY (`USER_FAVORITE_POST_ID`) REFERENCES `user_favorite_posts` (`USER_FAVORITE_POST_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_FAV_POSTS_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `user_favorite_posts`
--
ALTER TABLE `user_favorite_posts`
  ADD CONSTRAINT `USER_FAVORITE_POSTS_FK1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_FAVORITE_POSTS_FK2` FOREIGN KEY (`POST_ID`) REFERENCES `posts` (`POST_ID`) ON DELETE CASCADE;

--
-- Constraints for table `user_health_history_det`
--
ALTER TABLE `user_health_history_det`
  ADD CONSTRAINT `USER_HEALTH_HISTORY_FK1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_HEALTH_HISTORY_FK2` FOREIGN KEY (`HEALTH_CONDITION_ID`) REFERENCES `health_condition_master` (`HEALTH_CONDITION_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_HEALTH_HISTORY_FK3` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `USER_HEALTH_HISTORY_FK4` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `user_health_history_mod_det`
--
ALTER TABLE `user_health_history_mod_det`
  ADD CONSTRAINT `HEALTH_HISTORY_MOD_DET_FK1` FOREIGN KEY (`USER_HEALTH_HISTORY_DET_ID`) REFERENCES `user_health_history_det` (`USER_HEALTH_HISTORY_DET_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `HEALTH_HISTORY_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE;

--
-- Constraints for table `user_health_reading`
--
ALTER TABLE `user_health_reading`
  ADD CONSTRAINT `USER_HEALTH_READING_DET_FK1` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_HEALTH_READING_DET_FK2` FOREIGN KEY (`ATTRIBUTE_TYPE_ID`) REFERENCES `attributes_master` (`ATTRIBUTE_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_HEALTH_READING_DET_FK3` FOREIGN KEY (`UNIT_ID`) REFERENCES `unit_of_measurement_master` (`UNIT_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_HEALTH_READING_DET_FK4` FOREIGN KEY (`DATE_RECORDED_ON`) REFERENCES `dates` (`DATE_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `USER_HEALTH_READING_DET_FK5` FOREIGN KEY (`MONTH_RECORDED_ON`) REFERENCES `months_master` (`MONTH_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `USER_HEALTH_READING_DET_FK6` FOREIGN KEY (`YEAR_RECORDED_ON`) REFERENCES `years_master` (`YEAR_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `USER_HEALTH_READING_DET_FK7` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `USER_HEALTH_READING_DET_FK8` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `user_health_reading_mod_det`
--
ALTER TABLE `user_health_reading_mod_det`
  ADD CONSTRAINT `USER_HEALTH_READING_MOD_DET_FK1` FOREIGN KEY (`USER_HEALTH_READING_ID`) REFERENCES `user_health_reading` (`USER_HEALTH_READING_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_HEALTH_READING_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `user_media`
--
ALTER TABLE `user_media`
  ADD CONSTRAINT `USER_MEDIA_FK1` FOREIGN KEY (`MEDIA_TYPE_ID`) REFERENCES `media_type_master` (`MEDIA_TYPE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_MEDIA_FK2` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_MEDIA_FK3` FOREIGN KEY (`CREATED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `USER_MEDIA_FK4` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `user_media_mod_det`
--
ALTER TABLE `user_media_mod_det`
  ADD CONSTRAINT `USER_MEDIA_MOD_DET_FK1` FOREIGN KEY (`USER_MEDIA_ID`) REFERENCES `user_media` (`USER_MEDIA_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_MEDIA_MOD_DET_FK2` FOREIGN KEY (`MODIFIED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

--
-- Constraints for table `user_message_recipients`
--
ALTER TABLE `user_message_recipients`
  ADD CONSTRAINT `MESSAGE_RECIPIENTS_FK1` FOREIGN KEY (`MESSAGE_ID`) REFERENCES `user_messages` (`MESSAGE_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `MESSAGE_RECIPIENTS_FK2` FOREIGN KEY (`RECIPIENT_USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `MESSAGE_RECIPIENTS_FK3` FOREIGN KEY (`RECIPIENT_ROLE_ID`) REFERENCES `message_recipient_roles` (`MESSAGE_RECIPIENT_ROLE_ID`) ON DELETE CASCADE;

--
-- Constraints for table `user_messages`
--
ALTER TABLE `user_messages`
  ADD CONSTRAINT `USER_MESSAGES_FK1` FOREIGN KEY (`SENDER_USER_ID`) REFERENCES `users` (`USER_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `USER_MESSAGES_FK2` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `USERS_FK1` FOREIGN KEY (`STATUS_ID`) REFERENCES `status` (`STATUS_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `USERS_FK2` FOREIGN KEY (`LANGUAGE`) REFERENCES `languages` (`LANGUAGE_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `USERS_FK3` FOREIGN KEY (`COUNTRY`) REFERENCES `country_master` (`COUNTRY_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `USERS_FK4` FOREIGN KEY (`STATE`) REFERENCES `states_master` (`STATE_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `USERS_FK5` FOREIGN KEY (`CITY`) REFERENCES `cities_master` (`CITY_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `USERS_FK6` FOREIGN KEY (`USER_TYPE`) REFERENCES `user_type` (`USER_TYPE_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `USERS_FK7` FOREIGN KEY (`TIMEZONE`) REFERENCES `timezone_master` (`TIMEZONE_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `USERS_FK8` FOREIGN KEY (`LAST_EDITED_BY`) REFERENCES `users` (`USER_ID`) ON DELETE SET NULL;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
