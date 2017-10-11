
-- -----------------------------------------------------------------------------
-- ArrowChat tables
-- 11-3-2014
-- -----------------------------------------------------------------------------


CREATE TABLE IF NOT EXISTS `arrowchat` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `from` varchar(25) character set utf8 NOT NULL,
  `to` varchar(25) character set utf8 NOT NULL,
  `message` text character set utf8 NOT NULL,
  `sent` int(10) unsigned NOT NULL,
  `read` int(10) unsigned NOT NULL,
  `user_read` tinyint(1) NOT NULL default '0',
  `direction` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `to` (`to`),
  KEY `read` (`read`),
  KEY `user_read` (`user_read`),
  KEY `from` (`from`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `arrowchat_admin` (
  `id` int(3) unsigned NOT NULL auto_increment,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `arrowchat_applications` (
  `id` int(3) unsigned NOT NULL auto_increment,
  `name` varchar(100) character set utf8 collate utf8_bin NOT NULL,
  `folder` varchar(100) character set utf8 collate utf8_bin NOT NULL,
  `icon` varchar(100) character set utf8 collate utf8_bin NOT NULL,
  `width` int(4) unsigned NOT NULL,
  `height` int(4) unsigned NOT NULL,
  `bar_width` int(3) unsigned default NULL,
  `bar_name` varchar(100) default NULL,
  `dont_reload` tinyint(1) unsigned default '0',
  `default_bookmark` tinyint(1) unsigned default '1',
  `show_to_guests` tinyint(1) unsigned default '1',
  `link` varchar(255) default NULL,
  `update_link` varchar(255) default NULL,
  `version` varchar(20) default NULL,
  `active` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `arrowchat_banlist` (
  `ban_id` int(10) unsigned NOT NULL auto_increment,
  `ban_userid` varchar(25) default NULL,
  `ban_ip` varchar(50) default NULL,
  PRIMARY KEY  (`ban_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `arrowchat_chatroom_banlist` (
  `user_id` varchar(25) NOT NULL,
  `chatroom_id` int(10) unsigned NOT NULL,
  `ban_length` int(10) unsigned NOT NULL,
  `ban_time` int(10) unsigned NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `chatroom_id` (`chatroom_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `arrowchat_chatroom_messages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `chatroom_id` int(10) unsigned NOT NULL,
  `user_id` varchar(25) NOT NULL,
  `username` varchar(100) collate utf8_bin NOT NULL,
  `message` text collate utf8_bin NOT NULL,
  `global_message` tinyint(1) unsigned default '0',
  `is_mod` tinyint(1) unsigned default '0',
  `is_admin` tinyint(1) unsigned default '0',
  `sent` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `chatroom_id` (`chatroom_id`),
  KEY `user_id` (`user_id`),
  KEY `sent` (`sent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=30;

CREATE TABLE IF NOT EXISTS `arrowchat_chatroom_rooms` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `author_id` varchar(25) NOT NULL,
  `name` varchar(100) collate utf8_bin NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `password` varchar(25) collate utf8_bin default NULL,
  `length` int(10) unsigned NOT NULL,
  `max_users` int(10) NOT NULL default '0',
  `session_time` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `session_time` (`session_time`),
  KEY `author_id` (`author_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `arrowchat_chatroom_users` (
  `user_id` varchar(25) NOT NULL,
  `chatroom_id` int(10) unsigned NOT NULL,
  `is_admin` tinyint(1) unsigned NOT NULL default '0',
  `is_mod` tinyint(1) unsigned NOT NULL default '0',
  `block_chats` tinyint(4) unsigned NOT NULL default '0',
  `session_time` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`user_id`),
  KEY `chatroom_id` (`chatroom_id`),
  KEY `is_admin` (`is_admin`),
  KEY `is_mod` (`is_mod`),
  KEY `session_time` (`session_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `arrowchat_config` (
  `config_name` varchar(255) character set utf8 collate utf8_bin NOT NULL,
  `config_value` text,
  `is_dynamic` tinyint(1) unsigned NOT NULL default '0',
  UNIQUE KEY `config_name` (`config_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `arrowchat_graph_log` (
  `id` int(6) unsigned NOT NULL auto_increment,
  `date` varchar(30) NOT NULL,
  `user_messages` int(10) unsigned default '0',
  `chat_room_messages` int(10) unsigned default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `arrowchat_notifications` (
  `id` int(25) unsigned NOT NULL auto_increment,
  `to_id` varchar(25) NOT NULL,
  `author_id` varchar(25) NOT NULL,
  `author_name` varchar(100) NOT NULL,
  `misc1` varchar(255) default NULL,
  `misc2` varchar(255) default NULL,
  `misc3` varchar(255) default NULL,
  `type` int(3) unsigned NOT NULL,
  `alert_read` int(1) unsigned NOT NULL default '0',
  `user_read` int(1) unsigned NOT NULL default '0',
  `alert_time` int(15) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `to_id` (`to_id`),
  KEY `alert_read` (`alert_read`),
  KEY `user_read` (`user_read`),
  KEY `alert_time` (`alert_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `arrowchat_notifications_markup` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `type` int(3) unsigned NOT NULL,
  `markup` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `arrowchat_smilies` (
  `id` int(3) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `code` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `arrowchat_status` (
  `userid` varchar(25) NOT NULL,
  `guest_name` varchar(50) default NULL,
  `message` text,
  `status` varchar(10) default NULL,
  `theme` int(3) unsigned default NULL,
  `popout` int(11) unsigned default NULL,
  `typing` text,
  `hide_bar` tinyint(1) unsigned default NULL,
  `play_sound` tinyint(1) unsigned default '1',
  `window_open` tinyint(1) unsigned default NULL,
  `only_names` tinyint(1) unsigned default NULL,
  `chatroom_window` varchar(2) NOT NULL default '-1',
  `chatroom_stay` varchar(2) NOT NULL default '-1',
  `chatroom_block_chats` tinyint(1) unsigned default NULL,
  `chatroom_sound` tinyint(1) unsigned default NULL,
  `announcement` tinyint(1) unsigned NOT NULL default '1',
  `unfocus_chat` text,
  `focus_chat` varchar(50) default NULL,
  `last_message` text,
  `clear_chats` text,
  `apps_bookmarks` text,
  `apps_other` text,
  `apps_open` int(10) unsigned default NULL,
  `apps_load` text,
  `block_chats` text,
  `session_time` int(20) unsigned NOT NULL default '0',
  `is_admin` tinyint(1) unsigned NOT NULL default '0',
  `hash_id` varchar(20) NOT NULL default '0',
  PRIMARY KEY  (`userid`),
  KEY `hash_id` (`hash_id`),
  KEY `session_time` (`session_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `arrowchat_themes` (
  `id` int(3) unsigned NOT NULL auto_increment,
  `folder` varchar(25) NOT NULL,
  `name` varchar(100) NOT NULL,
  `active` tinyint(1) unsigned NOT NULL,
  `update_link` varchar(255) default NULL,
  `version` varchar(20) default NULL,
  `default` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `arrowchat_trayicons` (
  `id` int(3) unsigned NOT NULL auto_increment,
  `name` varchar(100) character set utf8 collate utf8_bin NOT NULL,
  `icon` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `target` varchar(25) default NULL,
  `width` int(4) unsigned default NULL,
  `height` int(4) unsigned default NULL,
  `tray_width` int(3) unsigned default NULL,
  `tray_name` varchar(100) character set utf8 collate utf8_bin default NULL,
  `tray_location` int(3) unsigned NOT NULL,
  `active` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Dumping data for table `arrowchat_admin`
--

INSERT INTO `arrowchat_admin` (`id`, `username`, `password`, `email`) VALUES
(1, 'admin', 'bfd59291e825b5f2bbf1eb76569f8fe7', 'nithina@qburst.com');

--
-- Dumping data for table `arrowchat_config`
--

INSERT INTO `arrowchat_config` (`config_name`, `config_value`, `is_dynamic`) VALUES
('admin_background_color', '', 0),
('admin_chat_all', '0', 0),
('admin_text_color', '', 0),
('admin_view_maintenance', '0', 0),
('announcement', '', 0),
('applications_guests', '1', 0),
('applications_on', '0', 0),
('auto_popup_chatbox', '1', 0),
('bar_fixed', '0', 0),
('bar_fixed_alignment', 'center', 0),
('bar_fixed_width', '900', 0),
('bar_padding', '15', 0),
('base_url', '/arrowchat/', 0),
('blocked_words', 'fuck,[shit],nigger,[cunt],[ass],asshole', 0),
('buddy_list_heart_beat', '60', 0),
('chat_maintenance', '0', 0),
('chatroom_auto_join', '0', 0),
('chatroom_history_length', '60', 0),
('chatrooms_on', '0', 0),
('desktop_notifications', '0', 0),
('disable_arrowchat', '0', 0),
('disable_avatars', '0', 0),
('disable_buddy_list', '0', 0),
('disable_smilies', '0', 0),
('enable_chat_animations', '1', 0),
('enable_mobile', '0', 0),
('facebook_app_id', '', 0),
('file_transfer_on', '0', 0),
('guest_name_bad_words', 'fuck,cunt,nigger,shit,admin,administrator,mod,moderator,support', 0),
('guest_name_change', '1', 0),
('guest_name_duplicates', '0', 0),
('guests_can_chat', '0', 0),
('guests_can_view', '0', 0),
('guests_chat_with', '1', 0),
('heart_beat', '3', 0),
('hide_admins_buddylist', '0', 0),
('hide_applications_menu', '0', 0),
('hide_bar_on', '1', 0),
('idle_time', '3', 0),
('install_time', '1394523248', 0),
('language', 'en', 0),
('login_url', '', 0),
('notifications_on', '0', 0),
('online_timeout', '120', 0),
('popout_chat_on', '1', 0),
('push_on', '0', 0),
('push_publish', '', 0),
('push_subscribe', '', 0),
('search_number', '10', 0),
('show_bar_links_right', '0', 0),
('show_full_username', '0', 0),
('theme', 'new_facebook_full', 0),
('theme_change_on', '0', 0),
('us_time', '1', 0),
('user_chatrooms', '0', 0),
('user_chatrooms_flood', '10', 0),
('user_chatrooms_length', '30', 0),
('users_chat_with', '3', 0),
('video_chat', '1', 0),
('width_applications', '16', 0),
('width_buddy_list', '189', 0),
('width_chatrooms', '16', 0);

--
-- Dumping data for table `arrowchat_notifications_markup`
--

INSERT INTO `arrowchat_notifications_markup` (`id`, `name`, `type`, `markup`) VALUES
(1, 'Private Messages', 1, '<div class="arrowchat_notification_box arrowchat_blue_link"><img src="/arrowchat/themes/new_facebook_full/images/icons/notification_message_icon.png" class="arrowchat_notification_icon" /><a href="#">{author_name}</a> has sent you a new message.<br /><div class="arrowchat_received">Received {longago}</div></div><div class="arrowchat_notifications_divider"></div>'),
(2, 'Friend Requests', 2, '<div class="arrowchat_notification_box arrowchat_blue_link"><img src="/arrowchat/themes/new_facebook_full/images/icons/notification_friend_icon.png" class="arrowchat_notification_icon" /><a href="#">{author_name}</a> has sent you a friend request.<br /><div class="arrowchat_received">Received {longago}</div></div><div class="arrowchat_notifications_divider"></div>'),
(3, 'Wall Post', 3, '<div class="arrowchat_notification_box arrowchat_blue_link"><img src="/arrowchat/themes/new_facebook_full/images/icons/notification_wall_post.png" class="arrowchat_notification_icon" /><a href="#">{author_name}</a> has wrote on your wall.<br /><div class="arrowchat_received">Received {longago}</div></div><div class="arrowchat_notifications_divider"></div>'),
(4, 'Event Invite', 4, '<div class="arrowchat_notification_box arrowchat_blue_link"><img src="/arrowchat/themes/new_facebook_full/images/icons/notification_event.png" class="arrowchat_notification_icon" /><a href="#">{author_name}</a> has invited you to an event.<br /><div class="arrowchat_received">Received {longago}</div></div><div class="arrowchat_notifications_divider"></div>'),
(5, 'Group Invite', 5, '<div class="arrowchat_notification_box arrowchat_blue_link"><img src="/arrowchat/themes/new_facebook_full/images/icons/notification_group.png" class="arrowchat_notification_icon" /><a href="#">{author_name}</a> has invited you to a group.<br />	<div class="arrowchat_received">Received {longago}</div></div><div class="arrowchat_notifications_divider"></div>'),
(6, 'Birthday', 6, '<div class="arrowchat_notification_box arrowchat_blue_link"><img src="/arrowchat/themes/new_facebook_full/images/icons/notification_birthday.png" class="arrowchat_notification_icon" />It is <a href="#">{author_name}</a>''s birthday!<br /><div class="arrowchat_received">Received {longago}</div></div><div class="arrowchat_notifications_divider"></div>'),
(7, 'Comment', 7, '<div class="arrowchat_notification_box arrowchat_blue_link"><img src="/arrowchat/themes/new_facebook_full/images/icons/notification_comment.png" class="arrowchat_notification_icon" /><a href="#">{author_name}</a> has left you a comment.<br /><div class="arrowchat_received">Received {longago}</div></div><div class="arrowchat_notifications_divider"></div>'),
(8, 'Reply', 8, '<div class="arrowchat_notification_box arrowchat_blue_link"><img src="/arrowchat/themes/new_facebook_full/images/icons/notification_reply.png" class="arrowchat_notification_icon" /><a href="#">{author_name}</a> has replied to you.<br /><div class="arrowchat_received">Received {longago}</div></div><div class="arrowchat_notifications_divider"></div>'),
(9, 'Like Post', 9, '<div class="arrowchat_notification_box arrowchat_blue_link"><img src="/arrowchat/themes/new_facebook_full/images/icons/notification_like.png" class="arrowchat_notification_icon" /><a href="#">{author_name}</a> has liked your post.<br /><div class="arrowchat_received">Received {longago}</div></div><div class="arrowchat_notifications_divider"></div>'),
(10, 'Like Comment', 10, '<div class="arrowchat_notification_box arrowchat_blue_link"><img src="/arrowchat/themes/new_facebook_full/images/icons/notification_like.png" class="arrowchat_notification_icon" /><a href="#">{author_name}</a> has liked your comment.<br /><div class="arrowchat_received">Received {longago}</div></div><div class="arrowchat_notifications_divider"></div>'),
(11, 'Like Photo', 11, '<div class="arrowchat_notification_box arrowchat_blue_link"><img src="/arrowchat/themes/new_facebook_full/images/icons/notification_like.png" class="arrowchat_notification_icon" /><a href="#">{author_name}</a> has liked your photo.<br /><div class="arrowchat_received">Received {longago}</div></div><div class="arrowchat_notifications_divider"></div>');

--
-- Dumping data for table `arrowchat_smilies`
--

INSERT INTO `arrowchat_smilies` (`id`, `name`, `code`) VALUES
(1, 'smiley', ':)'),
(2, 'smiley-mad', '>:('),
(3, 'smiley-lol', ':D'),
(4, 'smiley-wink', ';)'),
(5, 'smiley-surprise', ':o'),
(6, 'smiley-cool', '8)'),
(7, 'smiley-neutral', ':|'),
(8, 'smiley-cry', ':''('),
(9, 'smiley-razz', ':p'),
(10, 'smiley-confuse', ':s'),
(11, 'smiley', ':-)'),
(12, 'smiley-sad', ':-('),
(13, 'smiley-wink', ';-)'),
(14, 'smiley-surprise', ':-o'),
(15, 'smiley-cool', '8-)'),
(16, 'smiley-neutral', ':-|'),
(17, 'smiley-razz', ':-p'),
(18, 'smiley-confuse', ':-s'),
(20, 'smiley-sad', ':(');

--
-- Dumping data for table `arrowchat_themes`
--

INSERT INTO `arrowchat_themes` (`id`, `folder`, `name`, `active`, `update_link`, `version`, `default`) VALUES
(1, 'new_facebook_full', 'New Facebook Full', 1, 'http://www.arrowchat.com/updatecheck.php?id=8', '4.0', 1);


