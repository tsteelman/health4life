-- -----------------------------------------------------------------------------
-- Added disease tables
-- 28-10-2013
-- -----------------------------------------------------------------------------

ALTER TABLE `users` CHANGE `id` `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

-- -----------------------------------------------------------------------------

DROP TABLE IF EXISTS `diseases`;

-- --------------------------------------------------------

--
-- Table structure for table `diseases`
--

CREATE TABLE IF NOT EXISTS `diseases` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `diseases`
--

INSERT INTO `diseases` (`id`, `name`, `created`, `modified`) VALUES
(1, 'Asthma', '2013-10-28 14:54:38', '2013-10-28 14:54:38'),
(2, 'Bronchiectasis', '2013-10-28 14:54:38', '2013-10-28 14:54:38'),
(3, 'Cardiac failure', '2013-10-28 14:54:38', '2013-10-28 14:54:38'),
(4, 'Bone Cancer', '2013-10-28 14:54:38', '2013-10-28 14:54:38'),
(5, 'Diabetes', '2013-10-28 14:54:38', '2013-10-28 14:54:38'),
(6, 'Cardiomyopathy', '2013-10-28 14:54:38', '2013-10-28 14:54:38'),
(7, 'Chronic obstructive pulmonary disorder', '2013-10-28 14:54:38', '2013-10-28 14:54:38'),
(8, 'Chronic renal disease', '2013-10-28 14:54:38', '2013-10-28 14:54:38'),
(9, 'Coronary artery disease', '2013-10-28 14:54:38', '2013-10-28 14:54:38'),
(10, 'Crohn''s disease', '2013-10-28 14:54:38', '2013-10-28 14:54:38'),
(11, 'Aspergersyndrome', '2013-10-28 14:54:38', '2013-10-28 14:54:38'),
(12, 'Botulism', '2013-10-28 14:54:38', '2013-10-28 14:54:38'),
(13, 'Keratitis', '2013-10-28 14:54:38', '2013-10-28 14:54:38'),
(14, 'Cancer', '2013-10-28 14:54:38', '2013-10-28 14:54:38'),
(15, 'Leucoderma', '2013-10-28 14:54:38', '2013-10-28 14:54:38'),
(16, 'Cellulitis', '2013-10-28 14:54:38', '2013-10-28 14:54:38'),
(17, 'Parkinson''s', '2013-10-28 14:54:38', '2013-10-28 14:54:38'),
(18, 'Alzheimer', '2013-10-28 14:54:38', '2013-10-28 14:54:38');

-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `patient_diseases`;

--
-- Table structure for table `patient_diseases`
--
CREATE TABLE `patient_diseases` (
`id` INT NOT NULL AUTO_INCREMENT ,
`disease_id` INT(11) UNSIGNED NOT NULL ,
`patient_id` INT(11) UNSIGNED NOT NULL ,
`symptoms_date` DATETIME NOT NULL ,
`is_diagnosed` INT( 1 ) NOT NULL ,
`diagnosis_date` DATETIME NOT NULL ,
`created` DATETIME NOT NULL ,
`modified` DATETIME NOT NULL ,
PRIMARY KEY ( `id` ),
CONSTRAINT `fk_patient_diseases_disease`
    FOREIGN KEY (`disease_id`)
    REFERENCES `diseases` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
CONSTRAINT `fk_patient_diseases_patient`
    FOREIGN KEY (`patient_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;


-- -----------------------------------------------------------------------------
-- Made `diagnosis_date` Nullable in `patient_diseases` table
-- 29-10-2013
-- -----------------------------------------------------------------------------
ALTER TABLE `patient_diseases` CHANGE `diagnosis_date` `diagnosis_date` DATETIME NULL;

-- -----------------------------------------------------------------------------
-- Added `city` field in `users` table
-- 29-10-2013
-- -----------------------------------------------------------------------------
ALTER TABLE `users` ADD `city` INT NULL AFTER `state`;

-- -----------------------------------------------------------------------------
-- Added `care_giver_patients` table
-- 31-10-2013
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `care_giver_patients`;
CREATE TABLE `care_giver_patients` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `relationship` int(1) NOT NULL,
    `care_giver_id` int(11) UNSIGNED NOT NULL,
    `first_name` varchar(50) NULL,
    `last_name` varchar(50) NULL,
    `date_of_birth` date NULL,
    `gender` varchar(1) NULL,
    `country` int(11) NULL,
    `state` int(11) NULL,
    `city` int(11) NULL,
    `zip` varchar(20) NULL,
    `created` datetime NULL,
    `modified` datetime NULL,
    PRIMARY KEY  (`id`)
)ENGINE=InnoDB;



-- -----------------------------------------------------------------------------
-- Added `events` table
-- 31-10-2013
-- -----------------------------------------------------------------------------
CREATE TABLE `events` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(250) NOT NULL,
 `descrition` text NOT NULL,
 `event_type` tinyint(4) NOT NULL COMMENT '1 - Public, 2- Private  (Only invited can mark attendance )',
 `guest_can_invite` tinyint(4) NOT NULL COMMENT '1- private and allow guest to invite,  0 - not allowed guest to invite ',
 `repeat` int(11) NOT NULL COMMENT ' 0- one day event, 1- repeat event',
 `created_by` int(11) NOT NULL COMMENT 'user_id',
 `start_date` datetime NOT NULL,
 `end_date` datetime NOT NULL,
 `virtual_event` tinyint(4) NOT NULL COMMENT '0- ordinary event, 1- Virtual event',
 `medium_of_event` tinyint(4) NOT NULL COMMENT '1-skype, 2-google hangout, 3- live stream, 4-other',
 `location` varchar(250) NOT NULL,
 `country` int(11) NOT NULL,
 `zip` varchar(30) NOT NULL,
 `state` int(11) NOT NULL,
 `city` int(11) NOT NULL,
 `image` varchar(250) NOT NULL,
 `desease_id` int(11) NOT NULL,
 `tag` varchar(250) NOT NULL,
 `published` tinyint(4) NOT NULL COMMENT '0 - unpublished, 1- published, 2- blocked',
 `section` tinyint(4) NOT NULL COMMENT '0- normal event, 1- event in group , 2- event in team',
 `section_id` int(11) NOT NULL COMMENT 'group_id/team_id',
 PRIMARY KEY (`id`),
 KEY `name` (`name`)
) ENGINE=InnoDB;


-- -----------------------------------------------------------------------------
-- Added `event_members` table
-- 31-10-2013
-- -----------------------------------------------------------------------------

CREATE TABLE `event_members` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `event_id` int(11) NOT NULL,
 `user_id` int(11) NOT NULL,
 `status` int(11) NOT NULL COMMENT ' 0 - pending approval, 1 - join,  2 - not join, 3- may be ',
 `invited_by` int(11) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- -----------------------------------------------------------------------------
-- Corrected spelling of `description` in `events` table
-- 04-11-2013
-- -----------------------------------------------------------------------------
ALTER TABLE `events` CHANGE `descrition` `description` TEXT NOT NULL;

-- -----------------------------------------------------------------------------
-- Renamed `tag` to `tags` in `events` table
-- 05-11-2013
-- -----------------------------------------------------------------------------
ALTER TABLE `events` CHANGE `tag` `tags` VARCHAR( 250 ) NOT NULL;

-- -----------------------------------------------------------------------------
-- Added `event_diseases` table
-- 05-11-2013
-- -----------------------------------------------------------------------------
ALTER TABLE `events` CHANGE `id` `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

CREATE TABLE `event_diseases` (
    `id` INT(11) NOT NULL AUTO_INCREMENT ,
    `event_id` INT(11) UNSIGNED NOT NULL ,
    `disease_id` INT(11) UNSIGNED NOT NULL ,
    `created` DATETIME NOT NULL ,
    `modified` DATETIME NOT NULL ,
    PRIMARY KEY ( `id` ),
    CONSTRAINT `fk_event_diseases_event`
        FOREIGN KEY (`event_id`)
        REFERENCES `events` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `fk_event_diseases_disease`
        FOREIGN KEY (`disease_id`)
        REFERENCES `diseases` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;
ALTER TABLE `events` CHANGE `desease_id` `disease_id` int(11);

-- -----------------------------------------------------------------------------
-- Added `events` table changes
-- 06-11-2013
-- -----------------------------------------------------------------------------
ALTER TABLE `events` 
    CHANGE `medium_of_event` `online_event_details` VARCHAR(500) NULL,
    CHANGE `country` `country` INT(11) NULL ,
    CHANGE `zip` `zip` VARCHAR(30) NULL ,
    CHANGE `state` `state` INT(11) NULL ,
    CHANGE `city` `city` INT(11) NULL;

-- -----------------------------------------------------------------------------
-- Added repeat event related fields in `events` table
-- 06-11-2013
-- -----------------------------------------------------------------------------
ALTER TABLE `events` ADD `repeat_mode` TINYINT NOT NULL COMMENT '1:Daily, 2:Every weekday (Monday to Friday), 3:Every Monday, Wednesday and Friday, 4:Every Tuesday, And Thursday, 5:Weekly, 6:Monthly, 7:Yearly',
ADD `repeat_interval` TINYINT NOT NULL COMMENT '1 to 30',
ADD `repeats_on` VARCHAR( 100 ) NOT NULL COMMENT 'comma separated list of MON, TUE, WED, THU, FRI, SAT, SUN',
ADD `repeats_by` TINYINT NOT NULL COMMENT '1:Day of the month, 2: Day of the week ',
ADD `repeat_end_type` TINYINT NOT NULL COMMENT '1:never, 2:after, 3:date',
ADD `repeat_occurrences` INT NOT NULL;

-- -----------------------------------------------------------------------------
-- Removed `desease_id` field from `events` table
-- 07-11-2013
-- -----------------------------------------------------------------------------
ALTER TABLE `events` DROP `desease_id`;

-- Added unique user for an event
-- 07-11-2013
-- -----------------------------------------------------------------------------
ALTER TABLE  `event_members` ADD UNIQUE  `unique_invited_user` (  `event_id` ,  `user_id` );

-- -----------------------------------------------------------------------------
-- Adding my_friends table
-- 07-11-2013
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `my_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `my_id` int(11) NOT NULL,
  `friends` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- -----------------------------------------------------------------------------
-- Set default timezone as 'Asia/Kolkata' for all users
-- 13-11-2013
-- -----------------------------------------------------------------------------
UPDATE `users`SET `timezone`='Asia/Kolkata';

-- -----------------------------------------------------------------------------
-- Adding new column to users table for checking activation status
-- 15-11-2013
-- -----------------------------------------------------------------------------

ALTER TABLE `users` ADD COLUMN activation_token VARCHAR(100) NULL AFTER forgot_password_code;

-- -----------------------------------------------------------------------------
-- Adding tables for the groups
-- 15-11-2013
-- -----------------------------------------------------------------------------
CREATE TABLE `groups` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `name` varchar(100) NOT NULL,
 `description` varchar(300) DEFAULT NULL,
 `type` int(1) DEFAULT NULL COMMENT '1: open, 2: closed',
 `created_by` int(10) unsigned NOT NULL,
 `created` datetime DEFAULT NULL,
 `modified_by` int(10) DEFAULT NULL,
 `modified` datetime DEFAULT NULL,
 `member_count` int(11) NOT NULL DEFAULT '0',
 `zip` varchar(30) DEFAULT NULL,
 `state` int(11) DEFAULT NULL,
 `city` int(11) DEFAULT NULL,
 `tags` varchar(250) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `fk_group_1` (`created_by`),
 KEY `fk_group_2` (`tags`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


CREATE TABLE `group_members` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `group_id` int(10) unsigned NOT NULL,
 `user_id` int(10) unsigned NOT NULL,
 `user_type` int(1) NOT NULL DEFAULT '1' COMMENT '1: member, 2: admin',
 `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=invited, 1=not-approved, 1=approved',
 `created` datetime NOT NULL COMMENT 'join request/invited date',
 `joined_on` datetime NOT NULL COMMENT 'joined/approved date',
 PRIMARY KEY (`id`),
 UNIQUE KEY `group_id` (`group_id`,`user_id`),
 KEY `fk_group_member_1` (`group_id`),
 KEY `fk_group_member_2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `group_diseases` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `group_id` int(11) unsigned NOT NULL,
 `disease_id` int(11) unsigned NOT NULL,
 `created` datetime NOT NULL,
 `modified` datetime NOT NULL,
 PRIMARY KEY (`id`),
 KEY `fk_group_diseases_group` (`group_id`),
 KEY `fk_group_diseases_disease` (`disease_id`),
 CONSTRAINT `fk_group_diseases_disease` FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `fk_group_diseases_group` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- -----------------------------------------------------------------------------
-- Adding created and modified fields to the event_members table
-- 21-11-2013
-- -----------------------------------------------------------------------------
ALTER TABLE  `event_members` ADD  `created` DATETIME NOT NULL AFTER  `invited_by` ,
ADD  `modified` DATETIME NOT NULL AFTER  `created`;

-- -----------------------------------------------------------------------------
-- Adding contents to created and modified fields.
-- 21-11-2013
-- -----------------------------------------------------------------------------
UPDATE event_members
SET event_members.created = (SELECT events.start_date
                     FROM events
                     WHERE event_members.event_id = events.id),
event_members.modified = (SELECT events.start_date
                     FROM events
                     WHERE event_members.event_id = events.id);
-- -----------------------------------------------------------------------------
-- Adding discussions count field to the groups table
-- 22-11-2013
-- -----------------------------------------------------------------------------
ALTER TABLE  `groups` ADD  `discussion_count` INT NOT NULL DEFAULT  '0' AFTER  `member_count`;

-- -----------------------------------------------------------------------------
-- Adding `country` and `member_can_invite` fields to the groups table
-- 22-11-2013
-- -----------------------------------------------------------------------------
ALTER TABLE `groups` ADD COLUMN `country` int(11) NOT NULL AFTER `zip`;
ALTER TABLE `groups` ADD COLUMN `member_can_invite` tinyint(4) NOT NULL COMMENT '1- allow group members to invite members,  0 - members not allowed to invite' AFTER `type`;

-- -----------------------------------------------------------------------------
-- Changing comment for `status` field in `group_members` table
-- 23-11-2013
-- -----------------------------------------------------------------------------
ALTER TABLE `group_members` CHANGE `status` `status` TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '0=invited, 1=approved, 2=not-approved';
-- -----------------------------------------------------------------------------
-- Changing discussion_count to event_count in groups table
-- 27-11-2013
-- -----------------------------------------------------------------------------
ALTER TABLE  `groups` CHANGE  `discussion_count`  `event_count` INT( 11 ) NOT NULL DEFAULT  '0';
-- -----------------------------------------------------------------------------
-- Adding discussion_count after member_count in groups table
-- 27-11-2013
-- -----------------------------------------------------------------------------
ALTER TABLE  `groups` ADD  `discussion_count` INT( 11 ) NOT NULL DEFAULT  '0' AFTER  `member_count`;

-- -----------------------------------------------------------------------------
-- Adding `group_id` field in `events` table
-- 27-11-2013
-- -----------------------------------------------------------------------------
ALTER TABLE `events` ADD `group_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `event_type`;
ALTER TABLE `events` ADD CONSTRAINT `fk_event_group`
    FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;
-- -----------------------------------------------------------------------------
-- Adding `invited_by` field in `group_members` table
-- 4-12-2013
-- -----------------------------------------------------------------------------
ALTER TABLE `group_members` ADD `invited_by` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `status`;

-- -----------------------------------------------------------------------------
-- Changing 'group' to 'community'
-- 09-12-2013
-- -----------------------------------------------------------------------------

-- Drop keys

ALTER TABLE `events` 
    DROP FOREIGN KEY `fk_event_group`,
    DROP KEY `fk_event_group`;

ALTER TABLE `groups` 
    DROP KEY `fk_group_1`,
    DROP KEY `fk_group_2`;

ALTER TABLE `group_diseases`
    DROP FOREIGN KEY `fk_group_diseases_group`,
    DROP FOREIGN KEY `fk_group_diseases_disease`,
    DROP KEY `fk_group_diseases_group`,
    DROP KEY `fk_group_diseases_disease`;

ALTER TABLE `group_members`
    DROP KEY `group_id`,
    DROP KEY `fk_group_member_1`,
    DROP KEY `fk_group_member_2`;

-- Rename tables

RENAME TABLE `groups` TO `communities`;
RENAME TABLE `group_diseases` TO `community_diseases`;
RENAME TABLE `group_members` TO `community_members`;

-- Rename columns

ALTER TABLE `events` 
    CHANGE `group_id` `community_id` INT( 10 ) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `community_diseases` 
    CHANGE `group_id` `community_id` INT( 11 ) UNSIGNED NOT NULL;
ALTER TABLE `community_members` 
    CHANGE `group_id` `community_id` INT( 10 ) UNSIGNED NOT NULL;


-- Add indexes

ALTER TABLE `events`
    ADD CONSTRAINT `fk_event_community` 
    FOREIGN KEY (`community_id`) REFERENCES `communities` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `communities` 
    ADD KEY `fk_community_1` (`created_by`),
    ADD KEY `fk_community_2` (`tags`);

ALTER TABLE `community_diseases`
    ADD CONSTRAINT `fk_community_diseases_disease` 
    FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`id`) 
    ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_community_diseases_community` 
    FOREIGN KEY (`community_id`) REFERENCES `communities` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `community_members` 
    ADD UNIQUE `community_id` ( `community_id` , `user_id` ),
    ADD KEY `fk_community_member_1` ( `community_id` ),
    ADD KEY `fk_community_member_2` ( `user_id` );


-- Change comments

ALTER TABLE `events` 
    CHANGE `section` `section` TINYINT( 4 ) NOT NULL 
    COMMENT '0- normal event, 1- event in community , 2- event in team',
    CHANGE `section_id` `section_id` INT( 11 ) NOT NULL 
    COMMENT 'community_id/team_id';

ALTER TABLE `communities`
    CHANGE `member_can_invite` `member_can_invite` TINYINT( 4 ) NOT NULL 
    COMMENT '1- allow community members to invite members, 0 - members not allowed to invite';


-- -----------------------------------------------------------------------------
-- Adding keys for search group
-- 6-12-2013
-- -----------------------------------------------------------------------------
ALTER TABLE  `groups` ADD INDEX  `group_search_index` (  `name` ,  `description` ( 255 ) ,  `zip` );
ALTER TABLE  `diseases` ADD INDEX  `disease_name` (  `name` );
ALTER TABLE  `states` ADD INDEX  `state_name` (  `description` );
ALTER TABLE  `cities` ADD INDEX  `city_name` (  `description` );
ALTER TABLE  `cities` ADD INDEX  `city_name` (  `description` ,  `short_description` );

-- -----------------------------------------------------------------------------
-- Adding new columns to events table
-- 10-12-2013
-- -----------------------------------------------------------------------------
ALTER TABLE `events`  
    ADD `invited_count` INT NOT NULL DEFAULT '0' AFTER `repeat_occurrences`,  
    ADD `attending_count` INT NOT NULL DEFAULT '0' AFTER `invited_count`,  
    ADD `maybe_count` INT NOT NULL DEFAULT '0' AFTER `attending_count`,  
    ADD `not_attending_count` INT NOT NULL DEFAULT '0' AFTER `maybe_count`;


-- -----------------------------------------------------------------------------
-- Adding new column to events table
-- 11-12-2013
-- -----------------------------------------------------------------------------

ALTER TABLE  `diseases` ADD  `parent_id` INT NOT NULL DEFAULT  '0' AFTER  `name`;


-- -----------------------------------------------------------------------------
-- Adding new columns to events table
-- 11-12-2013
-- -----------------------------------------------------------------------------
ALTER TABLE  `events` ADD  `created` DATETIME NOT NULL AFTER  `not_attending_count` ,
ADD  `modified` DATETIME NOT NULL AFTER  `created`;

-- -----------------------------------------------------------------------------
-- Updating created, modified columns of events table.
-- 11-12-2013
-- -----------------------------------------------------------------------------
UPDATE  `events` SET  `created` =  NOW(),
`modified` =  NOW() WHERE 1;
-- -----------------------------------------------------------------------------
-- Changing column type of counts in events table
-- 11-12-2013
-- -----------------------------------------------------------------------------
ALTER TABLE  `events` CHANGE  `invited_count`  `invited_count` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE  `events` CHANGE  `attending_count`  `attending_count` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE  `events` CHANGE  `maybe_count`  `maybe_count` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE  `events` CHANGE  `not_attending_count`  `not_attending_count` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0';

-- -----------------------------------------------------------------------------
-- Adding `posts` table
-- 11-12-2013
-- -----------------------------------------------------------------------------
CREATE TABLE `posts` (
    `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
    `post_by` INT( 11 ) UNSIGNED NOT NULL ,
    `created` DATETIME NOT NULL ,
    `modified` DATETIME DEFAULT NULL ,
    `modified_by` INT( 11 ) UNSIGNED DEFAULT NULL ,
    `status` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT '0: normal post,\n1: waiting for abuse clearance,\n2: blocked',
    `ip` VARCHAR( 30 ) DEFAULT NULL ,
    `privacy` TINYINT( 1 ) UNSIGNED DEFAULT NULL ,
    `content` TEXT DEFAULT NULL COMMENT '{title, description, additional_info}',
    `post_type_id` VARCHAR( 100 ) DEFAULT NULL COMMENT 'comma separated list of photo id, video id or poll id',
    `post_type` ENUM('text', 'link', 'video', 'image', 'poll') DEFAULT 'text',
    `posted_in` INT( 11 ) UNSIGNED NOT NULL COMMENT 'ID reference to the place where the post is made',
    `posted_in_type` ENUM('communities', 'events', 'users', 'diseases') NOT NULL,
    `comment_json_content` TEXT DEFAULT NULL ,
    `like_count` INT( 11 ) DEFAULT NULL ,
    `comment_count` INT( 11 ) DEFAULT NULL ,
    PRIMARY KEY ( `id` ),
    CONSTRAINT `fk_posts_post_by`
        FOREIGN KEY (`post_by`)
        REFERENCES `users` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    INDEX `fk_posts_post_type_id` (`post_type_id`),
    INDEX `fk_posts_posted_in` (`posted_in`)
) ENGINE = InnoDB;

-- -----------------------------------------------------------------------------
-- Adding `comments` table
-- 11-12-2013
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `comments` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` INT(11) UNSIGNED NOT NULL,
  `created_by` INT(11) UNSIGNED NOT NULL,
  `created` DATETIME DEFAULT NULL,
  `modified` DATETIME DEFAULT NULL,
  `comment_text` TEXT,
  `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0: normal,\n1: waiting for abuse clearance,\n2: blocked',
  `ip` VARCHAR(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_comments_post_id`
        FOREIGN KEY (`post_id`)
        REFERENCES `posts` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
CONSTRAINT `fk_comments_created_by`
        FOREIGN KEY (`created_by`)
        REFERENCES `users` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------------------------------
-- Adding `likes` table
-- 11-12-2013
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `likes` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` INT(11) UNSIGNED NOT NULL,
  `created_by` INT(11) UNSIGNED NOT NULL,
  `created` DATETIME DEFAULT NULL,
  `ip` VARCHAR(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_likes_post_id`
        FOREIGN KEY (`post_id`)
        REFERENCES `posts` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
CONSTRAINT `fk_likes_created_by`
        FOREIGN KEY (`created_by`)
        REFERENCES `users` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------------------------------
-- Making Comunity events to Public
-- 13-12-2013
-- -----------------------------------------------------------------------------
UPDATE `events` SET event_type=1 WHERE `community_id` != 'NULL' AND event_type!=1;

-- -----------------------------------------------------------------------------
-- Adding 'community', 'event' options for `post_type`
-- 13-12-2013
-- -----------------------------------------------------------------------------
ALTER TABLE `posts` CHANGE `post_type` `post_type` ENUM( 'text', 'link', 'video', 'image', 'poll', 'community', 'event' ) DEFAULT 'text';
-- -----------------------------------------------------------------------------
-- Creating table for polls
-- 13-12-2013
-- -----------------------------------------------------------------------------
CREATE TABLE `polls` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `title` varchar(250) NOT NULL,
 `status` tinyint(4) NOT NULL ,
`created_by` int(11) NOT NULL,
 `created` datetime NOT NULL,
 `posted_in_type` tinyint(4) NOT NULL COMMENT '1 = community ',
 `posted_in` int(11) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB;



CREATE TABLE `poll_choices` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
`poll_id` int(11) NOT NULL,
 `option` varchar(250) NOT NULL,
`votes` int(11) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS `poll_vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `choice_id` int(11) NOT NULL,
  `poll_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(16) NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- -----------------------------------------------------------------------------
-- Added unique constraint for (`post_id`, and `created_by`) in `likes` table
-- 16-12-2013
-- -----------------------------------------------------------------------------
ALTER TABLE  `likes` ADD UNIQUE  `unique_post_user_like` (`post_id`, `created_by`);

-- -----------------------------------------------------------------------------
-- Set default `like_count` and `comment_count` in `posts` table as 0
-- 16-12-2013
-- -----------------------------------------------------------------------------
ALTER TABLE `posts` 
CHANGE `like_count` `like_count` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `comment_count` `comment_count` INT( 11 ) NOT NULL DEFAULT '0';

-- -----------------------------------------------------------------------------
-- Set positive values for count fields in the events table
-- 16-12-2013
-- -----------------------------------------------------------------------------
ALTER TABLE  `events` CHANGE  `attending_count`  `attending_count` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE  `events` CHANGE  `not_attending_count`  `not_attending_count` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE  `events` CHANGE  `invited_count`  `invited_count` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0';

-- -----------------------------------------------------------------------------
-- Set default location for the users with no location
-- 18-12-2013
-- -----------------------------------------------------------------------------
UPDATE `users` SET `country` = 100, `state` = 1254, `city` = 25301 WHERE country IS NULL;

-- -----------------------------------------------------------------------------
-- Adding new email templates
-- 18-12-2013
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(13, 'Add friend Template', 'Friends Request', '\r\n\r\n                        \r\n\r\n                                            <div><br></div><div>Hi,</div><div><br></div><div>|@username@| has send you a friends request. For more details click <a href="|@link@|" style="text-decoration: none; cursor: default;">here</a>.<br></div><div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Thanks,</div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Patients4life Team</div></div>                    ', '2013-12-18 08:26:46', '2013-12-18 08:27:46', '1', '1'),
(14, 'Approve friends request', 'Friends Request Approved', '<div><br class="Apple-interchange-newline">Hi,</div><div><br></div><div>|@username@| has approved your friends request. For more details click&nbsp;<a href="|@link@|" style="cursor: default;">here</a>.<br></div><div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Thanks,</div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Patients4life Team</div></div>\r\n\r\n                                            ', '2013-12-18 08:29:09', '2013-12-18 08:29:09', '1', '1'),
(15, 'Reject Friends Request', 'Rejected Friends Request', '<div><br class="Apple-interchange-newline">Hi,</div><div><br></div><div>|@username@| has rejected your friends request. For more details click&nbsp;<a href="|@link@|" style="cursor: default;">here</a>.<br></div><div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Thanks,</div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Patients4life Team</div></div>\r\n\r\n                                            ', '2013-12-18 08:30:14', '2013-12-18 08:39:33', '1', '1'),
(16, 'Remove friend', 'Friend Removed', '<div><br class="Apple-interchange-newline">Hi,</div><div><br></div><div>|@username@| has removed you from his friends list. For more details click&nbsp;<a href="|@link@|" style="cursor: default;">here</a>.<br></div><div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Thanks,</div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Patients4life Team</div></div>\r\n\r\n                                            ', '2013-12-18 08:31:19', '2013-12-18 08:31:19', '1', '1');

-- -----------------------------------------------------------------------------
-- Modifing email templates for friends module
-- 20-12-2013
-- -----------------------------------------------------------------------------
DELETE FROM `email_templates` WHERE `email_templates`.`id` = 13;
DELETE FROM `email_templates` WHERE `email_templates`.`id` = 14;
DELETE FROM `email_templates` WHERE `email_templates`.`id` = 15;
DELETE FROM `email_templates` WHERE `email_templates`.`id` = 16;

INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES 
(13, 'Add friend Template', 'Friends Request', '\r\n\r\n                        \r\n\r\n                        \r\n\r\n                                            <div><br></div><div>Hi |@username@|,</div><div><br></div><div>|@friend-username@| has send you a friends request. For more details click <a style="text-decoration: none; cursor: default;">here</a>.<br></div><div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Thanks,</div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Patients4life Team</div></div>                                        ', '2013-12-18 08:26:46', '2013-12-20 10:38:15', '1', '1'),
(14, 'Approve friends request', 'Friends Request Approved', '\r\n\r\n                        <div><br class="Apple-interchange-newline">Hi |@username@|,</div><div><br></div><div><span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">|@friend-username@|&nbsp;</span>has approved your friends request. For more details click&nbsp;<a style="cursor: default;">here</a>.</div><div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Thanks,</div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Patients4life Team</div></div>\r\n\r\n                                                                ', '2013-12-18 08:29:09', '2013-12-20 10:38:59', '1', '1'),
(15, 'Reject Friends Request', 'Rejected Friends Request', '\r\n                        <div><br class="Apple-interchange-newline">Hi |@username@|,</div><div><br></div><div><span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">|@friend-username@|</span>&nbsp;has rejected your friends request. For more details click&nbsp;<a style="cursor: default;">here</a>.</div><div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Thanks,</div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Patients4life Team</div></div>\r\n\r\n                                                                ', '2013-12-18 08:30:14', '2013-12-20 10:40:42', '1', '1'),
(16, 'Remove friend', 'Friend Removed', '\r\n\r\n                        <div><br class="Apple-interchange-newline">Hi |@username@|,</div><div><br></div><div><span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">|@friend-username@|</span>&nbsp;has removed you from his friends list. For more details click&nbsp;<a style="cursor: default;">here</a>.</div><div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Thanks,</div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Patients4life Team</div></div>\r\n\r\n                                                                ', '2013-12-18 08:31:19', '2013-12-20 10:41:11', '1', '1');

-- -----------------------------------------------------------------------------
-- Set the database and tables -> character to utf8
-- 23-12-2013
-- -----------------------------------------------------------------------------
ALTER DATABASE patients4life CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE users CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE posts CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE poll_choices CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE polls CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;	
ALTER TABLE likes CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE events CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE email_templates CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE diseases CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE communities CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE comments CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE care_giver_patients CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;

-- -----------------------------------------------------------------------------
-- Change email templates and removed two email templates
-- 26-12-2013
-- -----------------------------------------------------------------------------
DELETE FROM `email_templates` WHERE `email_templates`.`id` = 13;
DELETE FROM `email_templates` WHERE `email_templates`.`id` = 15;
DELETE FROM `email_templates` WHERE `email_templates`.`id` = 16;

INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(13, 'Add friend Template', 'Friends Request', '\r\n\r\n                        \r\n\r\n                        \r\n\r\n                        \r\n\r\n                        \r\n\r\n                                            <div><br></div><div>Hi |@username@|,</div><div><br></div><div>|@friend-username@| has send you a friends request. For more details click <span style="cursor: default;"><a href="|@link@|">here</a></span>.<br></div><div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><a href="|@accept-link@|" style="color: white; background-color: rgb(66, 139, 202); display: inline-block; padding: 6px 12px; margin-bottom: 0px; font-size: 14px; font-weight: normal; line-height: 1.428571429; text-align: center; white-space: nowrap; vertical-align: middle; cursor: default; border: 1px solid transparent; border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; outline: none !important; text-decoration: none;">Approve</a> <a href="|@reject-link@|" style="color: rgb(51, 51, 51); background-color: white; display: inline-block; padding: 6px 12px; margin-bottom: 0px; font-size: 14px; font-weight: normal; line-height: 1.428571429; text-align: center; white-space: nowrap; vertical-align: middle; cursor: default; border: 1px solid transparent; border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; outline: none !important; text-decoration: none;">Reject</a></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Thanks,</div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Patients4life Team</div></div>                                                                                ', '2013-12-18 08:26:46', '2013-12-26 09:20:20', '1', '1');
-- -----------------------------------------------------------------------------
-- changes in poll tables
-- 26-12-2013
-- -----------------------------------------------------------------------------

ALTER TABLE `polls` CHANGE `posted_in_type` `posted_in_type` ENUM( 'communities', 'events', 'users', 'diseases' ) NOT NULL;
ALTER TABLE `polls` CHANGE `status` `status` TINYINT( 4 ) NOT NULL DEFAULT '0';
ALTER TABLE `poll_choices` CHANGE `votes` `votes` INT( 11 ) NOT NULL DEFAULT '0';
RENAME TABLE `poll_vote` TO `poll_votes` ;
ALTER TABLE `poll_votes` CHANGE `timestamp` `created` INT( 11 ) NOT NULL;
ALTER TABLE `poll_votes` CHANGE `created` `created` DATETIME NOT NULL;

-- -----------------------------------------------------------------------------
-- Updating users table for last_login_datetime
-- 26-12-2013
-- -----------------------------------------------------------------------------
UPDATE `users` SET `last_login_datetime` = `created` where 1;

-- -----------------------------------------------------------------------------
-- Set default location for the users with no location
-- 18-12-2013
-- -----------------------------------------------------------------------------
UPDATE `users` SET `country` = 100, `state` = 1254, `city` = 25301 WHERE `country` IS NULL OR `state` IS NULL OR `city` IS NULL;
UPDATE `users` SET `country` = 100, `state` = 1254, `city` = 25301 WHERE `country` = 0 OR `state` = 0 OR `city` = 0;

-- -----------------------------------------------------------------------------
-- Adding 'question' field to 'polls' table.
-- 30-12-2013
-- -----------------------------------------------------------------------------
ALTER TABLE `polls` ADD `question` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `title`;

-- -----------------------------------------------------------------------------
-- Remove 'question' field from 'polls' table.
-- 31-12-2013
-- -----------------------------------------------------------------------------
ALTER TABLE `polls` DROP `question`;

-- -----------------------------------------------------------------------------
-- Create 'action_tokens'table.
-- 31-12-2013
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `action_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `action` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- -----------------------------------------------------------------------------
-- Create 'invited_useres'table.
-- 31-12-2013
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `invited_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `invited_user_list` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

-- -----------------------------------------------------------------------------
-- Add new email templates
-- 31-12-2013
-- ----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(15, 'Invitation to a non member friend', 'Invitation to Join Patients4Life ', '\r\n                        <div><br></div><div>Hi |@username@|,</div><div><br></div><div><span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">|@friend-username@| has invited you to the&nbsp;</span><span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">&nbsp;</span><strong style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Patients4Life&nbsp;</strong><span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">&nbsp;network</span>. For more details click <span style="cursor: default;"><a href="|@link@|">here</a></span>.<br></div><div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><a href="|@accept-link@|" style="color: #ffffff;border: 1px solid #004f7f;border-radius: 4px;background-color: #2c589e; display: inline-block; padding: 6px 12px; margin-bottom: 0px; font-size: 14px; font-weight: normal; line-height: 1.428571429; text-align: center; white-space: nowrap; vertical-align: middle; cursor: default; outline: none !important; text-decoration: none;">Approve</a> <a href="|@reject-link@|" style="color: #2c589e;border: 1px solid #cecece;border-radius: 4px;background-color: #f1f1f1;display: inline-block; padding: 6px 12px; margin-bottom: 0px; font-size: 14px; font-weight: normal; line-height: 1.428571429; text-align: center; white-space: nowrap; vertical-align: middle; cursor: default; outline: none !important; text-decoration: none;">Reject</a></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Thanks,</div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Patients4life Team</div></div>', '2013-12-30 05:11:02', '2013-12-30 05:23:23', '', '1');

-- -----------------------------------------------------------------------------
-- update title filed in polls table.
-- 3-01-2014
-- ----------------------------------------------------------------------------	
ALTER TABLE  `polls` CHANGE  `title`  `title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

-- -----------------------------------------------------------------------------
-- Add new emails tables for storing emails queues and email log
-- 7-01-2014
-- ----------------------------------------------------------------------------	
CREATE TABLE IF NOT EXISTS `emails` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `subject` varchar(256) NOT NULL,
  `to_name` varchar(256) NOT NULL,
  `to_email` varchar(256) NOT NULL,
  `from_email` varchar(100) DEFAULT NULL,
  `from_name` varchar(100) DEFAULT NULL,
  `email_template_id` int(11) NOT NULL,
  `content` mediumtext NOT NULL,
  `created` datetime NOT NULL,
  `sent_date` datetime DEFAULT NULL,  
  `module_info` varchar(200) DEFAULT NULL,
  `priority` int(10) NOT NULL DEFAULT '0',
  `attachment` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `emails_histories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `subject` varchar(256) NOT NULL,
  `to_name` varchar(256) NOT NULL,
  `to_email` varchar(256) NOT NULL,
  `from_email` varchar(100) DEFAULT NULL,
  `from_name` varchar(100) DEFAULT NULL,
  `email_template_id` int(11) NOT NULL,
  `content` mediumtext NOT NULL,
  `created` datetime NOT NULL,
  `sent_date` datetime DEFAULT NULL,
  `status` int(10) DEFAULT NULL COMMENT '0=added, 1=selected for sending, 2=sent, 3=failed',
  `module_info` varchar(200) DEFAULT NULL,
  `priority` int(10) NOT NULL DEFAULT '0',
  `attachment` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `emails`
  ADD CONSTRAINT `emails_ibfk_1` FOREIGN KEY (`email_template_id`) REFERENCES `email_templates` (`id`);

ALTER TABLE `emails_histories`
  ADD CONSTRAINT `emails_histories_ibfk_1` FOREIGN KEY (`email_template_id`) REFERENCES `email_templates` (`id`);


-- -----------------------------------------------------------------------------
-- Added `media` table to save photo and video details
-- 7-01-2014
-- ----------------------------------------------------------------------------	
CREATE TABLE `media` (
    `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
    `type` ENUM('photo', 'video') NOT NULL,
    `content` TEXT NOT NULL,
    `created_by` INT( 11 ) UNSIGNED NOT NULL,
    `created` DATETIME NOT NULL ,
    `modified` DATETIME DEFAULT NULL ,
    `modified_by` INT( 11 ) UNSIGNED DEFAULT NULL ,
    `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0: processing, 1:ready',
    PRIMARY KEY ( `id` ),
    CONSTRAINT `fk_media_created_by`
        FOREIGN KEY (`created_by`)
        REFERENCES `users` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE = InnoDB;

-- -----------------------------------------------------------------------------
-- Changed email template 13 and 15
-- 8-01-2014
-- ----------------------------------------------------------------------------	
DELETE FROM `email_templates` WHERE `email_templates`.`id` = 13;
DELETE FROM `email_templates` WHERE `email_templates`.`id` = 15;

INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(13, 'Add friend Template', 'Friends Request', '\r\n\r\n                        <div><a href="|@xx@|">|@xx@|</a><br></div><div>Hi |@username@|,</div><div><br></div><div>|@friend-username@| has send you a friends request. View |@friend-username@|''s <span style="cursor: default;"><a href="|@link@|">profile</a></span>.<br></div><div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><a href="|@accept-link@|" style="color: #ffffff;border: 1px solid #004f7f;border-radius: 4px;background-color: #2c589e; display: inline-block; padding: 6px 12px; margin-bottom: 0px; font-size: 14px; font-weight: normal; line-height: 1.428571429; text-align: center; white-space: nowrap; vertical-align: middle; cursor: default; outline: none !important; text-decoration: none;">Approve</a> <a href="|@reject-link@|" style="color: #2c589e;border: 1px solid #cecece;border-radius: 4px;background-color: #f1f1f1;display: inline-block; padding: 6px 12px; margin-bottom: 0px; font-size: 14px; font-weight: normal; line-height: 1.428571429; text-align: center; white-space: nowrap; vertical-align: middle; cursor: default; outline: none !important; text-decoration: none;">Reject</a></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Thanks,</div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Patients4life Team</div></div>', '2013-12-18 08:26:46', '2013-12-30 05:43:15', '1', '1'),

(15, 'Invitation to a non member friend', 'Invitation to Join Patients4Life ', '\r\n                        <div><br></div><div>Hi |@username@|,</div><div><br></div><div><span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">|@friend-username@| has invited you to the&nbsp;</span><span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">&nbsp;</span><strong style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Patients4Life&nbsp;</strong><span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">&nbsp;network</span>. View |@friend-username@|''s <span style="cursor: default;"><a href="|@link@|"> profile </a></span>.<br></div><div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><a href="|@accept-link@|" style="color: #ffffff;border: 1px solid #004f7f;border-radius: 4px;background-color: #2c589e; display: inline-block; padding: 6px 12px; margin-bottom: 0px; font-size: 14px; font-weight: normal; line-height: 1.428571429; text-align: center; white-space: nowrap; vertical-align: middle; cursor: default; outline: none !important; text-decoration: none;">Approve</a> <a href="|@reject-link@|" style="color: #2c589e;border: 1px solid #cecece;border-radius: 4px;background-color: #f1f1f1;display: inline-block; padding: 6px 12px; margin-bottom: 0px; font-size: 14px; font-weight: normal; line-height: 1.428571429; text-align: center; white-space: nowrap; vertical-align: middle; cursor: default; outline: none !important; text-decoration: none;">Reject</a></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Thanks,</div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Patients4life Team</div></div>', '2013-12-30 05:11:02', '2013-12-30 05:23:23', '', '1');

-- -----------------------------------------------------------------------------
-- Adding disease removed notification mail template.
-- 8-01-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(16, 'disease removed notification mail', 'Disease has been removed.', 'Hi&nbsp;|@username@|,\r\n\r\n                                            <div><br></div><div>|@disease-name@| has been removed from the site by the admin. The deleted disease was used in |@page-type@| <a href="|@link@|">|@page-name@|</a>&nbsp;which is owned by you. Please do the necessary updates.<br></div><div><br></div><div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Thanks,</div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Patients4life Team</div></div>', '2014-01-07 16:56:11', '2014-01-07 16:56:11', '1', '1');


-- -----------------------------------------------------------------------------
-- Changed email template 13 
-- 8-01-2014
-- ----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '

                        <div><br></div><div>Hi |@username@|,</div><div><br></div><div>|@friend-username@| has send you a friends request. View |@friend-username@|''s <span style="cursor: default;"><a href="|@link@|">profile</a></span>.<br></div><div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><a href="|@accept-link@|" style="color: #ffffff;border: 1px solid #004f7f;border-radius: 4px;background-color: #2c589e; display: inline-block; padding: 6px 12px; margin-bottom: 0px; font-size: 14px; font-weight: normal; line-height: 1.428571429; text-align: center; white-space: nowrap; vertical-align: middle; cursor: default; outline: none !important; text-decoration: none;">Approve</a> <a href="|@reject-link@|" style="color: #2c589e;border: 1px solid #cecece;border-radius: 4px;background-color: #f1f1f1;display: inline-block; padding: 6px 12px; margin-bottom: 0px; font-size: 14px; font-weight: normal; line-height: 1.428571429; text-align: center; white-space: nowrap; vertical-align: middle; cursor: default; outline: none !important; text-decoration: none;">Reject</a></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br></div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Thanks,</div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Patients4life Team</div></div>' WHERE `email_templates`.`id` = 13;

-- -----------------------------------------------------------------------------
-- Changed email template 16
-- 9-01-2014
-- ----------------------------------------------------------------------------	
DELETE FROM `email_templates` WHERE `email_templates`.`id` = 16;

INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(16, 'disease removed notification mail', 'Disease has been removed', '\r\n\r\n                        Hi&nbsp;|@username@|,\r\n\r\n                                            <div><br></div><div><span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">The disease&nbsp;</span>|@disease-name@| has been&nbsp;<span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">replaced&nbsp;</span><span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">with</span><span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">&nbsp;</span>|@replace-disease-name@|&nbsp;<span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">by the Administrator.</span>&nbsp;<span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">This disease was used in&nbsp;</span><font color="#454a4d" face="Helvetica Neue, Arial, Helvetica, Geneva, sans-serif"><span style="line-height: normal;">the</span></font> |@page-type@|&nbsp;<span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">you have created.&nbsp;</span><span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">For more details please visit</span>&nbsp;<span style="cursor: default;"><a href="|@link@|">|@page-name@|</a></span><a><span style="cursor: default;">&nbsp;</span></a>.</div><div><br></div><div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Thanks,</div><div style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">Patients4life Team</div></div>                    ', '2014-01-07 16:56:11', '2014-01-09 05:44:33', '1', '1');


-- -----------------------------------------------------------------------------
-- New tables for Symptoms
-- 09-01-2014
-- ----------------------------------------------------------------------------	
CREATE TABLE `symptoms` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `name` varchar(100) NOT NULL,
 `created` datetime NOT NULL,
 `modified` datetime NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- -----------------------------------------------------------------------------
-- Dumping data for table `symptoms`
-- 09-01-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `symptoms` (`id`, `name`, `created`, `modified`) VALUES
(1, 'Anxious mood', '2014-01-09 10:23:23', '2014-01-09 10:23:23'),
(2, 'Depressed mood', '2014-01-09 10:23:23', '2014-01-09 10:23:23'),
(3, 'Fatigue', '2014-01-09 10:23:23', '2014-01-09 10:23:23'),
(4, 'Gas', '2014-01-09 10:23:23', '2014-01-09 10:23:23'),
(5, 'Insomnia', '2014-01-09 10:23:23', '2014-01-09 10:23:23'),
(6, 'Pain', '2014-01-09 10:23:23', '2014-01-09 10:23:23'),
(7, 'Jaundice', '2014-01-09 10:23:23', '2014-01-09 10:23:23'),
(8, 'Back pain', '2014-01-09 10:23:23', '2014-01-09 10:23:23'),
(9, 'Vitamin C deficiency', '2014-01-09 10:23:23', '2014-01-09 10:23:23'),
(10, 'Taste changed', '2014-01-09 10:23:23', '2014-01-09 10:23:23');

-- -----------------------------------------------------------------------------
-- New tables for Treatment
-- 09-01-2014
-- ----------------------------------------------------------------------------	
CREATE TABLE `treatments` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `name` varchar(100) NOT NULL,
 `created` datetime NOT NULL,
 `modified` datetime NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- -----------------------------------------------------------------------------
-- Dumping data for table `treatments`
-- 09-01-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `treatments` (`id`, `name`, `created`, `modified`) VALUES
(1, 'Penicillin V', '2014-01-09 10:23:23', '2014-01-09 10:23:23'),
(2, 'Venlafaxine', '2014-01-09 10:23:23', '2014-01-09 10:23:23'),
(3, ' B Complex 50', '2014-01-09 10:23:23', '2014-01-09 10:23:23'),
(4, 'Ester-C', '2014-01-09 10:23:23', '2014-01-09 10:23:23'),
(5, 'Vitamin A', '2014-01-09 10:23:23', '2014-01-09 10:23:23'),
(6, 'Vitamin B', '2014-01-09 10:23:23', '2014-01-09 10:23:23'),
(7, 'Vitamin C', '2014-01-09 10:23:23', '2014-01-09 10:23:23'),
(8, 'Vitamin D', '2014-01-09 10:23:23', '2014-01-09 10:23:23'),
(9, ' Vitamin E', '2014-01-09 10:23:23', '2014-01-09 10:23:23'),
(10, 'Cataplex F', '2014-01-09 10:23:23', '2014-01-09 10:23:23');

-- -----------------------------------------------------------------------------
-- Dumping data for fields to relate user and treatment
-- 09-01-2014
-- ----------------------------------------------------------------------------
ALTER TABLE  `patient_diseases` ADD  `user_treatments` TEXT NULL 
COMMENT  'comma separated value of treatment id' AFTER  `diagnosis_date`;
ALTER TABLE  `patient_diseases` ADD  `treatment_details` TEXT NULL 
COMMENT  'json format of the treatment details' AFTER  `user_treatments`;

-- -----------------------------------------------------------------------------
-- Dumping data for table `user_symptoms`
-- 09-01-2014
-- ----------------------------------------------------------------------------
CREATE TABLE `user_symptoms` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `user_id` int(11) unsigned NOT NULL,
 `user_symptoms` text COMMENT 'comma separated symptoms',
 `symptom_details` text COMMENT 'json format of the symptoms added',
 PRIMARY KEY (`id`),
 UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -----------------------------------------------------------------------------
-- Added Status field to 'emails' table
-- 10-01-2014
-- ----------------------------------------------------------------------------
 ALTER TABLE `emails` ADD `status` INT( 10 ) NOT NULL DEFAULT '0' COMMENT '0=not sent, 1=sent' AFTER `priority`;

-- -----------------------------------------------------------------------------
-- Removed `symptoms_date` and `is_diagnosed` fields from `patient_diseases` table
-- 14-01-2014
-- ----------------------------------------------------------------------------
ALTER TABLE `patient_diseases`
  DROP `symptoms_date`,
  DROP `is_diagnosed`;
-- -----------------------------------------------------------------------------
-- Including new template for change password notification
-- 23-01-2014
-- ----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(17, 'Change Password Email', 'Password Changed', '<div><br></div>\r\n\r\n                        Hi |@username@|,<div><br></div><div>Just confirming that your Patients4Life password has been reset.</div><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>                    ', '2014-01-23 06:42:02', '2014-01-23 06:42:22', '1', '1');

-- -----------------------------------------------------------------------------
-- Added new field in user table for saving language.
-- 24-01-2014
-- ----------------------------------------------------------------------------
ALTER TABLE  `users` ADD  `language` VARCHAR( 5 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'en' AFTER  `timezone`;

-- -----------------------------------------------------------------------------
-- Added new user_messages table for saving messages.
-- 28-01-2014
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_messages` (
  `id` int(128) NOT NULL AUTO_INCREMENT,
  `from_id` int(11) DEFAULT NULL,
  `to_id` int(11) DEFAULT NULL,
  `subject` text,
  `message` text,
  `created` datetime DEFAULT NULL,
  `view` enum('0','1') DEFAULT NULL,
  `outbox_delete` enum('0','1') DEFAULT NULL,
  `inbox_delete` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `user_messages` CHANGE `from_id` `from_id` INT( 11 ) UNSIGNED NULL DEFAULT NULL ,
CHANGE `to_id` `to_id` INT( 11 ) UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `user_messages` ADD INDEX ( `from_id` );
ALTER TABLE `user_messages` ADD INDEX ( `to_id` );

ALTER TABLE `user_messages` ADD FOREIGN KEY ( `from_id` ) REFERENCES `users` (
`id`
) ON DELETE SET NULL ON UPDATE RESTRICT ;

ALTER TABLE `user_messages` ADD FOREIGN KEY ( `to_id` ) REFERENCES `users` (
`id`
) ON DELETE SET NULL ON UPDATE RESTRICT ;

-- -----------------------------------------------------------------------------
-- Changed enum fields in `user_messages` table to TINYINT(1)
-- 28-01-2014
-- ----------------------------------------------------------------------------
ALTER TABLE `user_messages` CHANGE `view` `view` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0:unread, 1:read',
CHANGE `outbox_delete` `outbox_delete` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0:not deleted, 1:deleted',
CHANGE `inbox_delete` `inbox_delete` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0:not deleted, 1:deleted';

-- -----------------------------------------------------------------------------
-- Table structure for table `saved_messages`
-- 29-01-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `user_messages` CHANGE `id` `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT;
CREATE TABLE `saved_messages` (
`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`conversation_id` INT(11) UNSIGNED NOT NULL,
`user_message_id` INT(11) UNSIGNED NOT NULL,
`user_id` INT(11) UNSIGNED NULL DEFAULT NULL,
`created` datetime DEFAULT NULL,
PRIMARY KEY ( `id` ),
CONSTRAINT `fk_saved_messages_message_id`
    FOREIGN KEY (`user_message_id`)
    REFERENCES `user_messages` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
CONSTRAINT `fk_saved_messages_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------------------------------
-- added 'timezone' to event table
-- 29-01-2014
-- ----------------------------------------------------------------------------
ALTER TABLE `events` ADD ` timezone` VARCHAR( 50 ) NOT NULL AFTER `city` ;

-- -----------------------------------------------------------------------------
-- Including new template for user message notification
-- 29-01-2014
-- ----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(18, 'Message Notification', '|@username@| sent you a message on Patients4Life', '\r\n\r\n                        Hi |@username@|,<br><br>&lt;a href="|@sender_profile_link@|"&gt;|@sender_username@|&lt;/a&gt; wrote: |@sender_message@|<br><br><br>&lt;a href="|@message_link@|"&gt;See message in Patients4Life&lt;/a&gt;<br><br>', '2014-01-29 11:12:25', '2014-01-29 11:20:24', '1', '1');

-- -----------------------------------------------------------------------------
-- modification of user message notification template
-- 29-01-2014
-- ----------------------------------------------------------------------------

DELETE FROM `email_templates` WHERE `email_templates`.`id` = 18;

INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(18, 'Message Notification', '|@username@| sent you a message on Patients4Life', '\r\n\r\n                        \r\n\r\n                        Hi |@username@|,<br><br>&nbsp;<a href="|@sender_profile_link@|">|@sender_username@|</a><br>wrote: |@sender_message@|<br><br><a href="|@message_link@|">See message in Patients4Life</a><br><br>                    ', '2014-01-29 11:12:25', '2014-01-29 14:23:13', '1', '1');

-- -----------------------------------------------------------------------------
-- Table structure change in `user_messages`, and `saved_messages`
-- 30-01-2014
-- ----------------------------------------------------------------------------
DROP TABLE `saved_messages`;

DROP TABLE `user_messages`;

CREATE TABLE `user_messages` (
`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`current_user_id` INT(11) UNSIGNED NOT NULL,
`other_user_id` INT(11) UNSIGNED NOT NULL,
`message` TEXT NOT NULL,
`direction` TINYINT(1) NOT NULL COMMENT '0:incoming, 1:outgoing ',
`is_read` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0:unread, 1:read',
`is_deleted` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0:not deleted, 1:deleted',
`created` DATETIME DEFAULT NULL,
PRIMARY KEY (`id`),
CONSTRAINT `fk_user_messages_user1`
    FOREIGN KEY (`current_user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
CONSTRAINT `fk_user_messages_user2`
    FOREIGN KEY (`other_user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `saved_messages` (
`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`user_message_id` INT(11) UNSIGNED NOT NULL COMMENT 'last saved user_message_id with the other user',
`saved_user_id` INT(11) UNSIGNED NOT NULL,
`other_user_id` INT(11) UNSIGNED NOT NULL,
`is_deleted` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0:not deleted, 1:deleted',
`created` datetime DEFAULT NULL,
PRIMARY KEY ( `id` ),
CONSTRAINT `fk_saved_messages_message_id`
    FOREIGN KEY (`user_message_id`)
    REFERENCES `user_messages` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
CONSTRAINT `fk_saved_messages_saved_user`
    FOREIGN KEY (`saved_user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
CONSTRAINT `fk_saved_messages_other_user`
    FOREIGN KEY (`other_user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- renaming the ' timezone' field to 'timezone'
-- 30-01-2014
-- ----------------------------------------------------------------------------
ALTER TABLE  `events` CHANGE  ` timezone`  `timezone` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

-- -----------------------------------------------------------------------------
-- set the event timezone as created user timzone, add created date to action tokens	
-- 30-01-2014
-- ----------------------------------------------------------------------------
UPDATE `events` AS `Event` LEFT JOIN `users` AS `Users` ON ( `Event`.`created_by` = `Users`.`id` ) SET `Event`.`timezone` = `Users`.`timezone` WHERE `Event`.`timezone` = '';

UPDATE `events`   SET `timezone` = 'Asia/Kolkata' WHERE `timezone` = '';

ALTER TABLE `action_tokens` ADD `created` DATETIME NOT NULL ;

-- -----------------------------------------------------------------------------
-- Removed `is_deleted` field from `saved_messages` table
-- 31-01-2014
-- ----------------------------------------------------------------------------
ALTER TABLE `saved_messages` DROP `is_deleted`;
-- -----------------------------------------------------------------------------
-- modification of user message notification template
-- 4-02-2014
-- ----------------------------------------------------------------------------

DELETE FROM `email_templates` WHERE `email_templates`.`id` = 18;

INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(18, 'Message Notification', '|@sender_username@| sent you a message on Patients4Life', '\r\n                        \r\n\r\n                        Hi |@username@|,<br><br>&nbsp;<a href="|@sender_profile_link@|">|@sender_username@|</a><br>wrote: |@sender_message@|<br><br><a href="|@message_link@|">See message in Patients4Life</a><br><br>                    ', '2014-01-29 11:12:25', '2014-02-04 13:50:54', '1', '1');
-- -----------------------------------------------------------------------------
-- Creating configuration table
-- 6-02-2014
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `configurations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO `configurations` (`id`, `name`, `value`, `label`) VALUES
(1, 'contact_email_id', 'p4l.qburst@gmail.com,ajay@qburst.com', 'Contact Email Address');

INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(19, 'Contact Request', ' Patients4Life : Contact enquiry from |@sender_username@|', '\r\n\r\n                        \r\n\r\n                        <div><br></div><div>Hi,</div><div><br></div><div><span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal;">You have received a contact request from Patients4Life Contact form. See the details below:</span><br></div><div><br></div><div>|@sender_message@|<br></div>                                        ', '2014-02-05 11:41:23', '2014-02-06 04:51:35', '1', '1');
-- -----------------------------------------------------------------------------
-- Adding new email templates for reminder mail notification
-- 6-02-2014
-- ----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(20, 'Invitation Reminder', 'Reminder: |@inviter-subject@| invited you to join Patients4Life.', '\r\n\r\n                        \r\n\r\n                        <div style="color: #252525; font-family: ''Open Sans'', sans-serif; font-size: 30px; line-height: normal; padding-top: 40px;">\r\n	|@inviter-body@| <br></div>\r\n<div style="padding-top: 20px; padding-bottom: 20px; color: #444444; font-family: ''Open Sans'', sans-serif; font-size: 14px; line-height: normal;">\r\n	<strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">Patients4Life</strong> is a niche social network and life management tool for people with chronic illnesses. \r\nEngaging with <strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">Patients4Life</strong> provides them a platform to help them manage their lives and build their support community.</div>\r\n|@invitation-reminder-body@|\r\n\r\n<div style="border-top: 1px solid #EBEBEB; padding-top: 40px; text-align: center;">\r\n	<a href="|@link@|" style="background-image: url(&quot;http://patients4life.qburst.com/theme/App//img/nxt_arow.png&quot;); background-position: 98% 50%; background-repeat: no-repeat; border-radius: 4px; cursor: default; font-family: ''Open Sans'', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 28px 6px 12px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; border: 1px solid rgb(246, 134, 31); font-size: 18px;">Join\r\n		Patients4Life</a></div>         ', '2014-02-03 06:46:22', '2014-02-05 10:26:47', '1', '1'),
(21, 'Pending Friend Request Reminder', 'Reminder: |@inviter-subject@| added you as a friend on Patients4Life.', '\r\n\r\n                        \r\n\r\n                        <div style="color: #252525; font-family: ''Open Sans'', sans-serif; font-size: 30px; line-height: normal; padding-top: 40px;">\r\n	|@inviter-body@| <br></div>\r\n<div style="padding-top: 20px; padding-bottom: 20px; color: #444444; font-family: ''Open Sans'', sans-serif; font-size: 14px; line-height: normal;">\r\n	<strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">Patients4Life</strong> is a niche social network and life management tool for people with chronic illnesses. \r\nEngaging with <strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">Patients4Life</strong> provides them a platform to help them manage their lives and build their support community.</div>\r\n|@invitation-reminder-body@|\r\n\r\n<div style="border-top: 1px solid #EBEBEB; padding-top: 40px; text-align: center;">\r\n	<a href="|@link@|" style="background-image: url(&quot;http://patients4life.qburst.com/theme/App//img/nxt_arow.png&quot;); background-position: 98% 50%; background-repeat: no-repeat; border-radius: 4px; cursor: default; font-family: ''Open Sans'', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 28px 6px 12px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; border: 1px solid rgb(246, 134, 31); font-size: 18px;">Visit Now</a></div>         ', '2014-02-04 08:48:44', '2014-02-05 09:23:01', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding new new field (pending_request_count) to my_friends table
-- 6-02-2014
-- ----------------------------------------------------------------------------
ALTER TABLE `my_friends` ADD `pending_request_count` INT( 11 ) UNSIGNED NOT NULL 

-- ----------------------------------------------------------------------------
-- Added latitude and longitude fields to cities table.
-- 06 -02-2014
-- ----------------------------------------------------------------------------
ALTER TABLE  `cities` ADD  `latitude` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `description` ,
ADD  `longitude` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `latitude`;

--
-- Table structure for table `my_healths`
--
CREATE TABLE IF NOT EXISTS `my_healths` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `health_status` INT(1) UNSIGNED NOT NULL COMMENT '5:very good, 4:good, 3:neutral, 2:bad, 1:very bad',
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
CONSTRAINT `fk_my_health_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------------------------------------------------
-- Added fields for the weather information in the city table
-- 07 -02-2014
-- ----------------------------------------------------------------------------
ALTER TABLE  `cities` ADD  `modified_date` DATETIME NOT NULL;
ALTER TABLE  `cities` ADD  `content` TEXT NOT NULL;

-- -----------------------------------------------------------------------------
-- Adding the 'Health Status Update Reminder' email template
-- 10-02-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(22, 'Health Status Update Reminder', 'How are you feeling today?', '<span style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); display: inline !important; float: none; "><br>Hi</span><span style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); display: inline !important; float: none; ">  |@username@|,<span class="Apple-converted-space"> </span></span><br><br style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); "><span style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); display: inline !important; float: none; ">How are you feeling today?<span class="Apple-converted-space">&nbsp;</span></span><br style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); "><span style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); display: inline !important; float: none; ">Just one click is all it takes to answer. And the more times you use Patients4Life, the more you’ll learn about your own health over time.<span class="Apple-converted-space">&nbsp;</span></span><br style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); "><h4 style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); font-size: 15px; font-weight: bold; ">Today I’m feeling<span class="Apple-converted-space">&nbsp;</span><span style="font-size: 12px; font-weight: normal; ">(click to update instantly)</span></h4><p>\r\n<a href="|@health_status_update_url@||@health_status_very_good@|" style="text-decoration: none;" title="Very good">\r\n	<img src="https://lh4.googleusercontent.com/-R96N5qRI1rU/Uvhy0yXoEnI/AAAAAAAAAGs/8QuQepwZr54/s60-no/very_good_smiley.png" alt="">\r\n</a>\r\n<a href="|@health_status_update_url@||@health_status_good@|" style="text-decoration: none;" title="Good">\r\n	<img src="https://lh5.googleusercontent.com/-s9ZHBvuuqnU/UvhyzwJXuEI/AAAAAAAAAGg/Jh0CKWPtv60/s60-no/good_smiley.png" alt="">\r\n</a>\r\n<a href="|@health_status_update_url@||@health_status_neutral@|" style="text-decoration: none;" title="Neutral">\r\n	<img src="https://lh3.googleusercontent.com/-Yk_9PZrSaZ8/Uvhyz5LfFkI/AAAAAAAAAGY/TXXt_1eqWOg/s60-no/neutral_smiley.png" alt="">\r\n</a>\r\n<a href="|@health_status_update_url@||@health_status_bad@|" style="text-decoration: none;" title="Bad">\r\n	<img src="https://lh4.googleusercontent.com/-cNeWZ-a8PXA/Uvhyzw84H2I/AAAAAAAAAGQ/QmwgWB1N-VU/s60-no/bad_smiley.png" alt="">\r\n</a>\r\n<a href="|@health_status_update_url@||@health_status_very_bad@|" style="text-decoration: none;" title="Very bad">\r\n	<img src="https://lh5.googleusercontent.com/-netzhlQjIEI/Uvhy0hYKOtI/AAAAAAAAAGk/tOSMTD3nB3w/s60-no/very_bad_smiley.png" alt="">\r\n</a>\r\n</p>', '2014-02-10 05:45:42', '2014-02-10 06:47:03', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding the latitude and longitude for Austin
-- 12-02-2014
-- -----------------------------------------------------------------------------
update `cities` set `latitude`='30.392126', `longitude` = '-97.670372' WHERE `description` LIKE 'Austin';
-- -----------------------------------------------------------------------------
-- Adding about_me field for user table
-- 13-02-2014
-- -----------------------------------------------------------------------------
ALTER TABLE  `users` ADD  `about_me` VARCHAR( 150 ) NULL DEFAULT NULL AFTER  `date_of_birth`;

-- -----------------------------------------------------------------------------
-- Adding fields for the Disease Videos
-- 13-02-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `diseases` ADD `description` TEXT NOT NULL DEFAULT '' AFTER `parent_id` ,
ADD `library` TEXT NOT NULL DEFAULT '' AFTER `description`;

--
-- Table structure for `notifications`
-- 18-02-2014
--
CREATE TABLE `notifications` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`activity_type` ENUM( 'event_invite', 'event_update', 'event_delete', 'event_post', 'community_invite', 'community_update', 'community_delete', 'community_post', 'attending_event', 'made_community_admin' ) NOT NULL,
	`activity_id` INT( 11 ) UNSIGNED NOT NULL,
	`object_id` INT( 11 ) UNSIGNED NOT NULL,
	`object_type` ENUM( 'event', 'community' ) NOT NULL,
	`sender_id` INT( 11 ) UNSIGNED NOT NULL,
	`recipient_id` INT( 11 ) UNSIGNED NOT NULL,
	`additional_info` TEXT NULL COMMENT 'JSON',
	`is_read` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'unread:0, read:1',
	`created` DATETIME NOT NULL,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `fk_notification_sender`
		FOREIGN KEY (`sender_id`)
		REFERENCES `users` (`id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	CONSTRAINT `fk_notification_recipient`
		FOREIGN KEY (`recipient_id`)
		REFERENCES `users` (`id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE
) ENGINE = InnoDB;

-- -----------------------------------------------------------------------------
-- Adding fields for the user activity
-- 17-02-2014
-- -----------------------------------------------------------------------------
ALTER TABLE  `users` ADD  `last_activity` DATETIME NULL DEFAULT NULL AFTER  `last_login_datetime`;

--
-- Added 'event_reminder' `activity_type` in `notifications` table
-- 19-02-2014
--
ALTER TABLE `notifications` CHANGE `activity_type`
	 `activity_type` ENUM('event_invite', 'event_update', 'event_delete', 
	'event_reminder', 'event_post', 'community_invite', 'community_update', 
	'community_delete', 'community_post', 'attending_event', 'made_community_admin') NOT NULL;

-- -----------------------------------------------------------------------------
-- Adding onother type of event.(calendar reminder)
-- 18-02-2014
-- -----------------------------------------------------------------------------
ALTER TABLE  `events` CHANGE  `event_type`  `event_type` TINYINT( 4 ) NOT NULL COMMENT '1 - Public, 2- Private  (Only invited can mark attendance ), 3 - Calendar Reminder';
-- -----------------------------------------------------------------------------
-- Adding email template for change email notification
-- 20-02-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(23, 'Change Email', 'Email Change Notification', '<div><br></div>Hi&nbsp;|@username@|,<div><br></div><div><span style="color: rgb(72, 72, 72); font-family: Verdana, sans-serif; font-size: 12px; line-height: normal;">Your email address has be changed from |@old-email@| to |@new-email@| and you will be receiving future updated from Patients4Life in this email address.</span><br></div><div><span style="color: rgb(72, 72, 72); font-family: Verdana, sans-serif; font-size: 12px; line-height: normal;"><br></span></div><div><span style="color: rgb(72, 72, 72); font-family: Verdana, sans-serif; font-size: 12px; line-height: normal;">Thanks,</span></div><div><span style="color: rgb(72, 72, 72); font-family: Verdana, sans-serif; font-size: 12px; line-height: normal;">Patients4Life Team</span></div>', '2014-02-20 09:16:02', '2014-02-20 09:16:02', '1', '1');

-- -----------------------------------------------------------------------------
-- changing type number of calendar reminder event type.
-- 18-02-2014
-- -----------------------------------------------------------------------------

ALTER TABLE  `events` CHANGE  `event_type`  `event_type` TINYINT( 4 ) NOT NULL COMMENT '1 - Public, 2- Private  (Only invited can mark attendance ), 4 - Calendar Reminder';

-- -----------------------------------------------------------------------------
-- Adding new column in User table to save privacy_settings.
-- 24-02-2014
-- -----------------------------------------------------------------------------

ALTER TABLE `users` ADD `privacy_settings` TEXT NULL; 

-- -----------------------------------------------------------------------------
-- Table structure for table `activity_logs`
-- 24-02-2014
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_ip` varchar(50) NOT NULL,
  `browser` varchar(100) NOT NULL,
  `controller` varchar(30) NOT NULL,
  `action` varchar(30) NOT NULL,
  `url` varchar(50) NOT NULL,	
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -----------------------------------------------------------------------------
-- Table structure for table `notification_settings`
-- 25-02-2014
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `notification_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `news_letter` tinyint(1) NOT NULL DEFAULT '0',
  `how_am_i_feeling` tinyint(1) NOT NULL DEFAULT '0',
  `friends_request_reminder` tinyint(1) NOT NULL DEFAULT '0',
  `event_invitation` tinyint(1) NOT NULL DEFAULT '0',
  `event_cancelation` tinyint(1) NOT NULL DEFAULT '0',
  `event_update` tinyint(1) NOT NULL DEFAULT '0',
  `friend_request` tinyint(1) NOT NULL DEFAULT '0',
  `friend_request_approval` tinyint(1) NOT NULL DEFAULT '0',
  `community_invitation` tinyint(1) NOT NULL DEFAULT '0',
  `community_removed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;


ALTER TABLE `notification_settings`
  ADD CONSTRAINT `notification_settings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- -----------------------------------------------------------------------------
-- Added `activity_in`, `activity_in_type`, `object_owner_id` fields in `notifications`
-- 21-02-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` 
	ADD `activity_in` INT( 11 ) UNSIGNED NOT NULL,
	ADD `activity_in_type` INT( 11 ) UNSIGNED NOT NULL,
	ADD `object_owner_id` INT( 11 ) UNSIGNED NOT NULL;
TRUNCATE TABLE `notifications`;
ALTER TABLE `notifications`
	ADD CONSTRAINT `fk_notification_object_owner` 
	FOREIGN KEY (`object_owner_id`) REFERENCES `users` (`id`) 
	ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------------------------------------------------
-- Changed the values of `activity_type`, `object_type`, `activity_in_type` in `notifications` table
-- 24-02-2014
-- --------------------------------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM('invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question') NOT NULL;
ALTER TABLE `notifications` CHANGE `object_type` `object_type` ENUM('event', 'community', 'profile', 'post', 'poll_post')  NOT NULL;
ALTER TABLE `notifications` CHANGE `activity_in_type` `activity_in_type` ENUM('event', 'community', 'profile') NOT NULL;

-- --------------------------------------------------------------------------------------------------
-- Added  `modified` field in `notifications` table
-- 24-02-2014
-- --------------------------------------------------------------------------------------------------
ALTER TABLE `notifications` ADD `modified` DATETIME NOT NULL AFTER `created`;

-- ----------------------------------------------------------------------------------
-- Changed `notifications` table `recipient_id` field to hold comma separated values
-- 24-02-2014
-- ----------------------------------------------------------------------------------
ALTER TABLE `notifications` DROP FOREIGN KEY `fk_notification_recipient`;
ALTER TABLE notifications DROP INDEX fk_notification_recipient;
ALTER TABLE `notifications` CHANGE `recipient_id` `recipient_id` TEXT NOT NULL COMMENT 'recipient id or comma separated list of recipient ids';

-- --------------------------------------------------------------------------------
-- Changed `is_read` field in `notifications` table to hold comma separated values
-- 25-02-2014
-- --------------------------------------------------------------------------------
ALTER TABLE `notifications` DROP `is_read`;
ALTER TABLE `notifications` ADD `read_recipients` TEXT NOT NULL AFTER `recipient_id`;

-- --------------------------------------------------------------------------------
-- Made `activity_in_type` field in `notifications` table nullable
-- 25-02-2014
-- --------------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_in_type` `activity_in_type` ENUM('event', 'community', 'profile') NULL DEFAULT NULL;

-- -----------------------------------------------------------------------------
-- Table structure for table `queued_tasks`
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `queued_tasks` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `jobtype` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `group` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `notbefore` datetime DEFAULT NULL,
  `fetched` datetime DEFAULT NULL,
  `completed` datetime DEFAULT NULL,
  `failed` int(3) NOT NULL DEFAULT '0',
  `failure_message` text COLLATE utf8_unicode_ci,
  `workerkey` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- -----------------------------------------------------------------------------
-- Table structure for table `cron_tasks`
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `cron_tasks` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `jobtype` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'task / method',
  `created` datetime NOT NULL,
  `notbefore` datetime DEFAULT NULL,
  `fetched` datetime DEFAULT NULL,
  `completed` datetime DEFAULT NULL,
  `failed` int(3) NOT NULL DEFAULT '0',
  `failure_message` text COLLATE utf8_unicode_ci,
  `workerkey` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `interval` int(10) NOT NULL DEFAULT '0' COMMENT 'in minutes',
  `status` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- --------------------------------------------------------------------------------
-- Added `comment` field in `my_healths` table
-- 03-03-2014
-- --------------------------------------------------------------------------------
ALTER TABLE `my_healths` ADD `comment` TEXT NULL AFTER `health_status`;

-- --------------------------------------------------------------------------------
-- Added 'health' `post_type` in `posts` table
-- 03-03-2014
-- --------------------------------------------------------------------------------
ALTER TABLE `posts` CHANGE `post_type` `post_type` ENUM( 'text', 'link', 'video', 'image', 'poll', 'community', 'event', 'health' ) NULL DEFAULT 'text';

-- -----------------------------------------------------------------------------
-- Adding email template for "Community Join Request" notification
-- 5-3-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(24, 'Community Join Request', 'Request to join community', '<br>Hi   |@username@|,<br><br>|@name@| wants to join your community &lt;a href="|@link@|"&gt;|@community-name@|&lt;/a&gt;.<br>\r\n\r\n', '2014-03-05 04:16:36', '2014-03-05 04:16:36', '1', '1');
UPDATE `email_templates` SET `template_body` = '<br>Hi |@username@|,<br><br>|@name@| wants to join your community <a href="|@link@|">|@community-name@|</a>.<br> ' WHERE `email_templates`.`id` =24;

-- --------------------------------------------------------------------------------
-- created favorite_posts field in users table.
-- 05-03-2014
-- --------------------------------------------------------------------------------
ALTER TABLE  `users` ADD  `favorite_posts` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'Json_format' AFTER `privacy_settings`;

-- --------------------------------------------------------------------------------
-- created is_deleted field in posts table.
-- 05-03-2014
-- --------------------------------------------------------------------------------
ALTER TABLE  `posts` ADD  `is_deleted` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `comment_count`;
ALTER TABLE  `polls` ADD  `is_deleted` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `posted_in`;
ALTER TABLE  `poll_votes` ADD  `is_deleted` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `ip_address`;
ALTER TABLE  `media` ADD  `is_deleted` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `status`;

-- --------------------------------------------------------------------------------
- created email_settings field in notifications table.
-- 05-03-2014
-- --------------------------------------------------------------------------------
ALTER TABLE  `notification_settings` ADD  `email_settings` TEXT NULL AFTER  `user_id`;
ALTER TABLE `notification_settings`
  DROP `news_letter`,
  DROP `how_am_i_feeling`,
  DROP `friends_request_reminder`,
  DROP `event_invitation`,
  DROP `event_cancelation`,
  DROP `event_update`,
  DROP `friend_request`,
  DROP `friend_request_approval`,
  DROP `community_invitation`,
  DROP `community_removed`;

-- --------------------------------------------------------------------------------
-- Added user log and status for disease table.
-- 05-03-2014
-- --------------------------------------------------------------------------------

ALTER TABLE `diseases` ADD `status` INT( 1 ) NOT NULL AFTER `library` ,
ADD `user_id` INT( 11 ) NOT NULL AFTER `status`;

-- -----------------------------------------------------------------------------
-- Adding email template for "New Post" notification
-- 5-3-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(25, 'New Post Notification', '|@username@| added a post', '<br>Hi&nbsp;\r\n\r\n|@username@|,<br><br> |@name@|  |@content@|.<br><br><a style="background-color: transparent;" href="|@post_link@|">See Post</a> &nbsp;&nbsp; <a style="background-color: transparent;" href="|@link|">|@link_text@|</a>', '2014-03-05 11:45:32', '2014-03-05 11:45:32', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding email template for "Post Comment" notification
-- 6-3-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(26, 'Comment post notification', '|@username@| commented on a post', '\r\n\r\n<br>Hi&nbsp;\r\n\r\n|@username@|,<br><br> |@name@|  |@content@|.<br><br> |@name@| wrote:<br> |@comment@|<br><br><a style="background-color: transparent; text-decoration: none; cursor: default;">See Comment</a>\r\n\r\n', '2014-03-06 05:27:28', '2014-03-06 05:27:28', '1', '1');

-- --------------------------------------------------------------------------------
-- Changing email template buttons
-- 05-03-2014
-- --------------------------------------------------------------------------------


UPDATE `email_templates` SET `template_body` = '                        

                        <div style="color: #252525; font-family: ''Open Sans'', sans-serif; font-size: 30px; line-height: normal; padding-top: 40px;">
	|@inviter-body@| <br></div>
<div style="padding-top: 20px; padding-bottom: 20px; color: #444444; font-family: ''Open Sans'', sans-serif; font-size: 14px; line-height: normal;">
	<strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">Patients4Life</strong> is a niche social network and life management tool for people with chronic illnesses. 
Engaging with <strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">Patients4Life</strong> provides them a platform to help them manage their lives and build their support community.</div>
|@invitation-reminder-body@|

<div style="border-top: 1px solid #EBEBEB; padding-top: 40px; text-align: center;">
	<a href="|@link@|" style="background-image: url(&quot;http://patients4life.qburst.com/theme/App//img//email_arrow.png&quot;); background-position: 86% 50%; background-repeat: no-repeat; border-radius: 4px; cursor: default; font-family: ''Open Sans'', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 41px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; border: 1px solid rgb(246, 134, 31); font-size: 18px;">Visit Now</a></div>         ' WHERE `email_templates`.`id` = 21;

UPDATE `email_templates` SET `template_body` = '                        

                        <div style="color: #252525; font-family: ''Open Sans'', sans-serif; font-size: 30px; line-height: normal; padding-top: 40px;">
	|@inviter-body@| <br></div>
<div style="padding-top: 20px; padding-bottom: 20px; color: #444444; font-family: ''Open Sans'', sans-serif; font-size: 14px; line-height: normal;">
	<strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">Patients4Life</strong> is a niche social network and life management tool for people with chronic illnesses. 
Engaging with <strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">Patients4Life</strong> provides them a platform to help them manage their lives and build their support community.</div>
|@invitation-reminder-body@|

<div style="border-top: 1px solid #EBEBEB; padding-top: 40px; text-align: center;">
	<a href="|@link@|" style="background-image: url(&quot;http://patients4life.qburst.com/theme/App//img/email_arrow.png&quot;); background-position: 92% 50%; background-repeat: no-repeat; border-radius: 4px; cursor: default; font-family: ''Open Sans'', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 32px 6px 16px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; border: 1px solid rgb(246, 134, 31); font-size: 18px;">Join
		Patients4Life</a></div>         ' WHERE `email_templates`.`id` = 20;

UPDATE `email_templates` SET `template_body` = '                        

                        <h2 style="font-size: 24px; margin: 30px 0px 25px 0px;  font-weight: normal;">Welcome to Patients4Life</h2>Hi |@username@|,<br><br>Congrats! You have successfully registered for Patients4Life. Your credentials are:<br>Username:  |@username@|<br>Email:  |@email@|<br><br><div style="text-align:center">  <a href="|@site-url@|"  style="background-image: url(&quot;http://patients4life.qburst.com/theme/App//img//email_arrow.png&quot;); background-position: 86% 50%; background-repeat: no-repeat; border-radius: 4px; cursor: default; font-family: ''''Open Sans'''', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 41px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; border: 1px solid rgb(246, 134, 31); font-size: 18px;">Visit Now</a> </div> <br><p style="margin: 30px 0px 0px 0px;">Thanks,</p>
<p style="margin: 1px 0px 0px 0px;">Patients4Life Team</p>                                                            ' WHERE `email_templates`.`id` = 3;

-- -----------------------------------------------------------------------------
-- Changed email template subject
-- 6-3-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_subject` = '|@name@| added a post' WHERE `email_templates`.`id` =25;
UPDATE `email_templates` SET `template_subject` = '|@name@| commented on a post' WHERE `email_templates`.`id` =26;

-- -----------------------------------------------------------------------------
-- Adding email template for "Poll Vote" notification
-- 6-3-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(27, 'Poll vote notification', '|@name@| answered a question', 'Hi  |@username@|,<br><br> |@name@| answered  |@answer@| to |@poll_user@| question  |@question@|.<br><br><a style="background-color: transparent;" href="|@post_link@|">See Post</a><br>\r\n\r\n', '2014-03-06 07:40:08', '2014-03-06 07:40:08', '1', '1');


-- -----------------------------------------------------------------------------
-- Changed comment notification email template
-- 6-3-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_subject` = '|@name@| commented on a post',
`template_body` = ' <br>Hi&nbsp; |@username@|,<br><br> |@name@| |@content@|.<br><br> |@name@| wrote:<br> |@comment@|<br><br><a style="background-color: transparent; text-decoration: none; cursor: default;" href="|@post_link@|">See Comment</a> ' WHERE `email_templates`.`id` =26;

-- -----------------------------------------------------------------------------
-- Changed post notification email template
-- 6-3-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '<br>Hi&nbsp; |@username@|,<br><br> |@name@| |@content@|.<br><br><a style="background-color: transparent;" href="|@post_link@|">See Post</a> &nbsp;&nbsp; <a style="background-color: transparent;" href="|@link@|">|@link_text@|</a>' WHERE `email_templates`.`id` =25;

-- --------------------------------------------------------------------------------
-- Changing email template buttons
-- 06-03-2014
-- --------------------------------------------------------------------------------

UPDATE `email_templates` SET `template_body` = '                        

                        <h2 style="font-size: 24px; margin: 30px 0px 25px 0px;  font-weight: normal;">Welcome to Patients4Life</h2>Hi |@username@|,<br><br>Congrats! You have successfully registered for Patients4Life. Your credentials are:<br>Username:  |@username@|<br>Email:  |@email@|<br><br><div style="text-align:center">  <a href="|@site-url@|" style="border-radius: 4px; cursor: default; font-family: ''''Open Sans'''', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; border: 1px solid rgb(246, 134, 31); font-size: 18px;">Visit Now <img alt="arrow" src="http://patients4life.qburst.com/theme/App//img/email_arrow.png" style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;"></a> </div> <br><p style="margin: 30px 0px 0px 0px;">Thanks,</p>
<p style="margin: 1px 0px 0px 0px;">Patients4Life Team</p>                                                            ' WHERE `email_templates`.`id` = 3;



UPDATE `email_templates` SET `template_body` = '                        

                        <div style="color: #252525; font-family: ''Open Sans'', sans-serif; font-size: 30px; line-height: normal; padding-top: 40px;">
	|@inviter-body@| <br></div>
<div style="padding-top: 20px; padding-bottom: 20px; color: #444444; font-family: ''Open Sans'', sans-serif; font-size: 14px; line-height: normal;">
	<strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">Patients4Life</strong> is a niche social network and life management tool for people with chronic illnesses. 
Engaging with <strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">Patients4Life</strong> provides them a platform to help them manage their lives and build their support community.</div>
|@invitation-reminder-body@|

<div style="border-top: 1px solid #EBEBEB; padding-top: 40px; text-align: center;">
	<a href="|@link@|" style="border-radius: 4px; cursor: default; font-family: ''Open Sans'', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 16px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; border: 1px solid rgb(246, 134, 31); font-size: 18px;">Join Patients4Life <img alt="arrow" src="http://patients4life.qburst.com/theme/App//img/email_arrow.png" style="padding-left: 10px; vertical-align: middle; margin-bottom: 4px;" /></a></div>         ' WHERE `email_templates`.`id` = 20;



UPDATE `email_templates` SET `template_body` = '                        

                        <div style="color: #252525; font-family: ''Open Sans'', sans-serif; font-size: 30px; line-height: normal; padding-top: 40px;">
	|@inviter-body@| <br></div>
<div style="padding-top: 20px; padding-bottom: 20px; color: #444444; font-family: ''Open Sans'', sans-serif; font-size: 14px; line-height: normal;">
	<strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">Patients4Life</strong> is a niche social network and life management tool for people with chronic illnesses. 
Engaging with <strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">Patients4Life</strong> provides them a platform to help them manage their lives and build their support community.</div>
|@invitation-reminder-body@|

<div style="border-top: 1px solid #EBEBEB; padding-top: 40px; text-align: center;">
	<a href="|@link@|" style="border-radius: 4px; cursor: default; font-family: ''Open Sans'', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; border: 1px solid rgb(246, 134, 31); font-size: 18px;">Visit Now<img alt= "arrow" src="http://patients4life.qburst.com/theme/App//img//email_arrow.png" style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;" ></a></div>  ' WHERE `email_templates`.`id` = 21;


-- -----------------------------------------------------------------------------
-- Adding email template for "Event reminder" notification
-- 7-3-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(28, 'Event Reminder', 'Patients4Life: Event Reminders for |@date@|', 'Hi |@username@|,<br><br>A gentle reminder for the events happening today:<br><br>|@event_reminder_body@|<br>', '2014-03-07 08:49:11', '2014-03-07 08:49:11', '1', '1');

-- --------------------------------------------------------------------------------
-- Changing the 'Health Status Update Reminder' email template
-- 10-03-2014
-- --------------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '<span style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); display: inline !important; float: none; "><br>Hi</span><span style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); display: inline !important; float: none; ">  |@username@|,<span class="Apple-converted-space"> </span></span><br><br style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); "><span style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); display: inline !important; float: none; ">How are you feeling today?<span class="Apple-converted-space">&nbsp;</span></span><br style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); "><span style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); display: inline !important; float: none; ">Just one click is all it takes to answer. And the more times you use Patients4Life, the more you’ll learn about your own health over time.<span class="Apple-converted-space">&nbsp;</span></span><br style="color: rgb(34, 34, 34); font-family: arial, sans-serif; font-size: 13px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; background-color: rgb(34, 34, 34); "><br><a href="|@auto_login_url@|">Click Here</a> to update your health status.<br>'
WHERE `id`=22;

-- -----------------------------------------------------------------------------
-- Changing activation email template
-- 10-3-2014
-- -----------------------------------------------------------------------------

UPDATE `email_templates` SET `template_body` = '

                        <h2 style="font-size: 24px; margin: 30px 0px 25px 0px;  font-weight: normal; font-family: ''Open Sans'', sans-serif; color: #000;">Activate Account</h2>

<table>
    <tbody><tr>
        <td>Hi |@username@|,<br><br></td>
    </tr>
    <tr>
        <td>Please click <a href="|@link@|" style="text-decoration: none; cursor: default; font-family: ''Open Sans'', sans-serif;">here</a> to active your account.</td>
    </tr>
    <tr><td><br>If you are not able to click the link above, please copy and paste the link below to your browser.</td></tr></tbody></table>
<a style="text-decoration: none; cursor: default;">|@link@|</a>
<p style="margin: 30px 0px 0px 0px;">Thanks,</p>
<p style="margin: 1px 0px 0px 0px;">Patients4Life Team</p>                    ' WHERE `email_templates`.`id` = 2;

-- -----------------------------------------------------------------------------
-- Changing new post notification email template
-- 11-3-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET
`template_subject` = '|@name@| added a |@post_type@|',
`template_body` = '<br>Hi |@username@|,<br><br> |@name@| |@content@|<br><br><a style="background-color: transparent;" href="|@post_link@|">|@post_link_text@|</a> &nbsp;&nbsp; <a style="background-color: transparent;" href="|@link@|">|@link_text@|</a>'
 WHERE `email_templates`.`id` = 25;

-- -----------------------------------------------------------------------------
-- Created table for health records.
-- 7-3-2014
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `health_readings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `record_type` int(1) NOT NULL COMMENT '1 = temperature, 2= weight, 3= height, 4= pressure',
  `record_value` longtext NOT NULL COMMENT 'json content with timestamp as the key',
  `record_year` text NOT NULL COMMENT 'year in YYYY format',
  `latest_record_value` text NOT NULL COMMENT 'single value of one record in the record value field',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- -----------------------------------------------------------------------------
-- updated health_readings table.
-- 7-3-2014
-- -----------------------------------------------------------------------------
ALTER TABLE  `health_readings` CHANGE  `record_year`  `record_year` VARCHAR( 4 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'year in YYYY format';

-- -----------------------------------------------------------------------------
-- Added 'health status' `record_type` in `health_readings` table
-- 11-3-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `health_readings` CHANGE `record_type` `record_type` INT( 1 ) NOT NULL COMMENT '1 = temperature, 2= weight, 3= height, 4= pressure, 5=health status';

-- -----------------------------------------------------------------------------
-- Changing mail template designs
-- 11-3-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '<table width="578" align="center" style="table-layout: fixed;">
	<tbody>
		<tr>
			<td><h2
					style="font-size: 30px; margin-top: 30px; margin-left: 0px; margin-bottom: 25px; margin-right: 0px; font-weight: normal; font-family: '''' Open Sans '''', sans-serif; color: #000;">Activate
					Account</h2></td>
		</tr>
		<tr>
			<td style="padding-bottom: 10px; font-size: 14px;">Hi |@username@|,</td>
		</tr>
		<tr>
			<td style="padding-bottom: 20px; font-size: 14px;">Please click the
				button below to active your account.</td>
		</tr>
		<tr>
			<td align="center" style="padding-bottom: 30px; ">
				<a href="|@link@|"
				style="border-radius: 4px; cursor: default; font-family: '''' Open Sans '''', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; border: 1px solid rgb(246, 134, 31); font-size: 18px;">Activate
					Account<img alt="arrow"
					src="http://patients4life.qburst.com/theme/App//img/email_arrow.png"
					style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;">
				</a>
			</td>
		</tr>
		<tr>
			<td style="padding-bottom: 5px; font-size:13px;">If you are not able to click the
				link above, please copy and paste the link below to your browser.</td>
		</tr>
		<tr>
			<td  style="padding-bottom: 25px; word-wrap: break-word;"><a
				style="text-decoration: none; cursor: default; color: #3c9cd7; font-size:13px;">|@link@|</a></td>
		</tr>
		<tr>
			<td style="font-size: 14px; padding-bottom:5px;">Thanks,</td>
		</tr>
		<tr>			<td style="font-size: 14px;">Patients4Life Team</td>
		</tr>
	</tbody>
</table>' WHERE `email_templates`.`id` = 2;

---------------------

UPDATE `email_templates` SET `template_body` = '<table width="578" align="center" style="table-layout: fixed;">
	<tbody>
		<tr>
			<td><h2
					style="font-size: 30px; margin-top: 30px; margin-left: 0px; margin-bottom: 25px; margin-right: 0px; font-weight: normal; font-family: '''' Open Sans '''', sans-serif; color: #000;">Reset Password</h2></td>
		</tr>
		<tr>
			<td style="padding-bottom: 10px; font-size: 14px;">Hi |@username@|,</td>
		</tr>
        <tr>
			<td style="padding-bottom: 10px; font-size: 14px;">You recently asked to reset your password in Patients4Life. </td>
		</tr>
		<tr>
			<td style="padding-bottom: 20px; font-size: 14px;">Please click the
				button below to reset your password.</td>
		</tr>
		<tr>
			<td align="center" style="padding-bottom: 30px; ">
				<a href="|@link@|"
				style="border-radius: 4px; cursor: default; font-family: '''' Open Sans '''', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; border: 1px solid rgb(246, 134, 31); font-size: 18px;">Reset Password<img alt="arrow"
					src="http://patients4life.qburst.com/theme/App//img/email_arrow.png"
					style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;">
				</a>
			</td>
		</tr>
		<tr>
			<td style="padding-bottom: 5px; font-size:13px;">If you are not able to click the
				link above, please copy and paste the link below to your browser.</td>
		</tr>
		<tr>
			<td  style="padding-bottom: 25px; word-wrap: break-word;"><a
				style="text-decoration: none; cursor: default; color: #3c9cd7; font-size:13px;">|@link@|</a></td>
		</tr>
		<tr>
			<td style="font-size: 14px; padding-bottom:5px;">Thanks,</td>
		</tr>
		<tr>
			<td style="font-size: 14px;">Patients4Life Team</td>
		</tr>
	</tbody>
</table>' WHERE `email_templates`.`id` = 1;

-- -----------------------------------------------------------------------------
-- Drop `my_healths` table
-- 11-3-2014
-- -----------------------------------------------------------------------------
DROP TABLE `my_healths`;

-- -----------------------------------------------------------------------------
-- changing Update health status template
-- 12-3-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '<table width="578" align="center" style="table-layout: fixed;">
	<tbody>
		<tr>
            <td style="padding-bottom: 40px;"></td>
		</tr>
		<tr>
			<td style="padding-bottom: 10px; font-size: 14px;">Hi |@username@|,</td>
		</tr>
        <tr>
			<td style="padding-bottom: 10px; font-size: 14px;">How are you feeling today?</td>
		</tr>
        <tr>
			<td style="padding-bottom: 10px; font-size: 14px;">Just one click is all it takes to answer. And the more times you use Patients4Life, the more you’ll learn about your own health over time. </td>
		</tr>
		<tr>
			<td style="padding-bottom: 20px; font-size: 13px;">Please click the
				button below to update your health status.</td>
		</tr>
		<tr>
			<td align="center" style="padding-bottom: 30px;">
				<a href="|@auto_login_url@|" style="border-radius: 4px; cursor: default; font-family: '''' Open Sans '''', sans-serif; font-weight: normal; line-height: 1.42857; padding-left: 21px; padding-bottom: 6px; padding-right: 18px; padding-top: 6px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin-top: 0px; margin-right: auto; margin-left: auto; margin-bottom: 0px; background-color: rgb(246, 134, 31); color: white; border-top-color: rgb(246,134,31); border-left-width: 1px; border-right-color: rgb(246,134,31); border-bottom-color: rgb(246,134,31); border-left-color: rgb(246,134,31); border-top-style: solid; border-right-style: solid; border-bottom-width: 1px; border-left-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-style: solid; font-size: 18px;">Update Health Status<img alt="arrow" src="http://patients4life.qburst.com/theme/App//img/email_arrow.png" style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;" />
				</a>
			</td>
		</tr>		
		<tr>
			<td style="font-size: 14px; padding-bottom: 5px;">Thanks,</td>
		</tr>
		<tr>
			<td style="font-size: 14px;">Patients4Life Team</td>
		</tr>
	</tbody>
</table>' WHERE `email_templates`.`id` = 22
-- -----------------------------------------------------------------------------
-- Adding new settings feilds for tracking units of measurement
-- 12-3-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notification_settings` ADD `height_unit` TINYINT DEFAULT 1 COMMENT '1 - Imperial, 2- Metric';
ALTER TABLE `notification_settings` ADD `weight_unit` TINYINT DEFAULT 1 COMMENT '1 - Imperial, 2- Metric';
ALTER TABLE `notification_settings` ADD `temp_unit` TINYINT DEFAULT 1 COMMENT '1 - Celsius, 2- Fahrenheit';
-- -----------------------------------------------------------------------------
-- Changing default unit type for temperature
-- 12-3-2014
-- -----------------------------------------------------------------------------
ALTER TABLE  `notification_settings` CHANGE  `temp_unit`  `temp_unit` TINYINT( 4 ) NULL DEFAULT  '2' COMMENT  '1 - Celsius, 2- Fahrenheit'

-- -----------------------------------------------------------------------------
-- Added 'health status' `record_type` in `health_readings` table
-- 13-3-2014
-- -----------------------------------------------------------------------------
ALTER TABLE  `health_readings` CHANGE  `record_type`  `record_type` INT( 1 ) NOT NULL COMMENT  '1 = weight, 2= height, 3= pressure, 4= temperature';

-- -----------------------------------------------------------------------------
-- changing message notification email template
-- 14-3-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '<table width="578" align="center" style="table-layout: fixed;">
	<tbody>
		<tr>
            <td style="padding-bottom: 40px;"></td>
		</tr>
		<tr>
			<td style="padding-bottom: 10px; font-size: 14px;">Hi |@username@|,</td>
		</tr>
        <tr>
          <td style="padding-bottom: 10px; font-size: 14px;"><a href="|@sender_profile_link@|" style="text-decoration: none; cursor: default; color: #3c9cd7;">|@sender_username@|</a> wrote:</td>
		</tr>
		<tr>
			<td style="padding-bottom: 20px; font-size: 14px;">|@sender_message@|</td>
		</tr>
		<tr>
			<td align="center" style="padding-bottom: 30px; ">
				<a href="|@message_link@|"
				style="border-radius: 4px; cursor: default; font-family: '''' Open Sans '''', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; border: 1px solid rgb(246, 134, 31); font-size: 18px;">See message in Patients4Life<img alt="arrow"
					src="http://patients4life.qburst.com/theme/App//img/email_arrow.png"
					style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;">
				</a>
			</td>
		</tr>		
		<tr>
			<td style="font-size: 14px; padding-bottom:5px;">Thanks,</td>
		</tr>
		<tr>
			<td style="font-size: 14px;">Patients4Life Team</td>
		</tr>
	</tbody>
</table>' WHERE `email_templates`.`id` = 18;

-- -----------------------------------------------------------------------------
-- Added `notification_count` field in `notification_settings` table
-- 17-3-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notification_settings` ADD `notification_count` INT NOT NULL DEFAULT '0';

-- -----------------------------------------------------------------------------
-- Added `notification_last_viewed` field in `notification_settings` table
-- 18-3-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notification_settings` ADD `notification_last_viewed` DATETIME NOT NULL;

-- -----------------------------------------------------------------------------
-- changing message notification email template
-- 17-3-2014
-- -----------------------------------------------------------------------------
ALTER TABLE  `treatments` ADD  `count` BIGINT NOT NULL DEFAULT  '0' AFTER  `name`;

-- -----------------------------------------------------------------------------
-- Table structure for 'Survey' at admin side
-- 18-3-2014
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `surveys` (
  `id` int(11) UNSIGNED NOT NULL auto_increment,
  `name` varchar(100)  NOT NULL,
  `description` varchar(100)  NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `survey_questions` (
  `id` int(11) NOT NULL auto_increment,
  `survey_id` int(11)  NOT NULL,
  `question_text` varchar(100)  NOT NULL,
  `answers` text  NOT NULL COMMENT 'json',
  `created_time` datetime NOT NULL,
  `created_by` int(11)  NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `survey_results` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11)  NOT NULL,
  `survey_id` int(11)  NOT NULL,
  `question_id` int(11)  NOT NULL,
  `selected_answers` text  NOT NULL ,
  `attended_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -----------------------------------------------------------------------------
-- Changed the event reminder email template
-- 19-03-2014
-- ----------------------------------------------------------------------------	
UPDATE `email_templates` SET 
`template_subject` = 'Reminder: |@event-name@| @ |@event_datetime@| (|@username@|)',
`template_body` = '<br />\r\n<table cellspacing="0" cellpadding="8" border="0" summary="" style="width:100%;font-family:Arial,Sans-serif;border-width:1px 2px 2px 1px;border:1px Solid #ccc">\r\n	<tbody>\r\n		<tr>\r\n			<td>\r\n				<div style="padding:2px">\r\n					<div style="float:right;font-weight:bold;font-size:13px"> \r\n						<a href="|@link@|" style="color:#20c;white-space:nowrap" target="_blank">more details Â»</a>\r\n						<br>\r\n					</div>\r\n					<h3 style="padding:0 0 6px 0;margin:0;font-family:Arial,Sans-serif;font-size:16px;font-weight:bold;color:#222">\r\n						|@event-name@|\r\n					</h3>\r\n					<table cellpadding="0" cellspacing="0" border="0" summary="Event details">\r\n						<tbody>\r\n							<tr>\r\n								<td style="padding:0 1em 10px 0;font-family:Arial,Sans-serif;font-size:13px;color:#888;white-space:nowrap" valign="top">\r\n									<div><i style="font-style:normal">When</i></div>\r\n								</td>\r\n								<td style="padding-bottom:10px;font-family:Arial,Sans-serif;font-size:13px;color:#222" valign="top">\r\n									|@event_datetime@| <span style="color:#888">|@timezone_offset@|</span>\r\n								</td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n				</div>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>'
 WHERE `id` = 28;

-- -----------------------------------------------------------------------------
-- Table structure for 'user_symptoms' 
-- 19-3-2014
-- -----------------------------------------------------------------------------
DROP TABLE  `user_symptoms`;

CREATE TABLE IF NOT EXISTS `user_symptoms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `symtom_id` int(11) unsigned NOT NULL,
  `record_value` longtext NOT NULL COMMENT 'json format of the symptoms added',
  `record_year` varchar(4) NOT NULL COMMENT 'year in YYYY format',
  `latest_record_value` text NOT NULL COMMENT 'single value of one record in the record value field',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- -----------------------------------------------------------------------------
-- Table structure for table `disease_symptoms`
-- 19-3-2014
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `disease_symptoms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `disease_id` int(11) NOT NULL,
  `symptom_ids` text NOT NULL COMMENT ' comma separated value of symptom id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- -----------------------------------------------------------------------------
-- Changed the event reminder email template
-- 19-03-2014
-- ----------------------------------------------------------------------------	
UPDATE `email_templates` SET 
`template_body` = '<br />\r\n<table cellspacing="0" cellpadding="8" border="0" summary="" style="width:100%;font-family:Arial,Sans-serif;border-width:1px 2px 2px 1px;border:1px Solid #ccc">\r\n	<tbody>\r\n		<tr>\r\n			<td>\r\n				<div style="padding:2px">\r\n					<div style="float:right;font-weight:bold;font-size:13px"> \r\n						<a href="|@link@|" style="color:#20c;white-space:nowrap" target="_blank">more details »</a>\r\n						<br>\r\n					</div>\r\n					<h3 style="padding:0 0 6px 0;margin:0;font-family:Arial,Sans-serif;font-size:16px;font-weight:bold;color:#222">\r\n						|@event-name@|\r\n					</h3>\r\n					<table cellpadding="0" cellspacing="0" border="0" summary="Event details">\r\n						<tbody>\r\n							<tr>\r\n								<td style="padding:0 1em 10px 0;font-family:Arial,Sans-serif;font-size:13px;color:#888;white-space:nowrap" valign="top">\r\n									<div><i style="font-style:normal">When</i></div>\r\n								</td>\r\n								<td style="padding-bottom:10px;font-family:Arial,Sans-serif;font-size:13px;color:#222" valign="top">\r\n									|@event_datetime@| <span style="color:#888">|@timezone_offset@|</span>\r\n								</td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n				</div>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>' 
WHERE `id` = 28;

-- -----------------------------------------------------------------------------
-- Adding the table for the photos
-- 20-03-2014
-- ----------------------------------------------------------------------------	
CREATE TABLE `photos` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `user_id` int(11) unsigned NOT NULL,
 `file_name` varchar(500) NOT NULL,
 `type` int(1) NOT NULL COMMENT '1=post, 2=dashboard',
 `created_by` int(10) unsigned NOT NULL,
 `created` datetime NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -----------------------------------------------------------------------------
-- Added `survey_key` field in `surveys` table
-- 20-3-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `surveys` ADD `survey_key` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `description`;

-- -----------------------------------------------------------------------------
-- Removed `created_by` field from `photos` table because `user_id` is present
-- 20-03-2014
-- ----------------------------------------------------------------------------	
ALTER TABLE `photos` DROP `created_by`;

-- -----------------------------------------------------------------------------
-- Added `is_dashboard_slideshow_enabled` field in `users` table
-- 20-03-2014
-- ----------------------------------------------------------------------------	
ALTER TABLE `users` ADD `is_dashboard_slideshow_enabled` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT '1: enabled, 0:disabled';

-- -----------------------------------------------------------------------------
-- Changed the event reminder email template
-- 20-03-2014
-- ----------------------------------------------------------------------------	
 
ALTER TABLE `user_symptoms`
  DROP INDEX `user_id`;
ALTER TABLE `user_symptoms` CHANGE `symtom_id` `symptom_id` INT( 11 ) UNSIGNED NOT NULL;

-- -----------------------------------------------------------------------------
-- add `description` to `symptoms` table
-- 21-03-2014
-- ----------------------------------------------------------------------------	

ALTER TABLE  `symptoms` ADD  `description` TEXT NULL AFTER  `name`

-- -----------------------------------------------------------------------------
-- add `survey_id` to `diseases` table
-- 21-03-2014
-- ----------------------------------------------------------------------------	

ALTER TABLE  `diseases` ADD  `survey_id` int AFTER `user_id`

-- -----------------------------------------------------------------------------
-- Removed `created_by` field from `photos` table because `user_id` is present
-- 20-03-2014
-- -----------------------------------------------------------------------------	
ALTER TABLE `photos` DROP `created_by`;

-- -----------------------------------------------------------------------------
-- Added `is_dashboard_slideshow_enabled` field in `users` table
-- 20-03-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `users` ADD `is_dashboard_slideshow_enabled` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT '1: enabled, 0:disabled';

-- -----------------------------------------------------------------------------
-- Added `is_default` field in `photos` table
-- 24-03-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `photos` ADD `is_default` TINYINT( 1 ) NOT NULL DEFAULT '0';

-- -----------------------------------------------------------------------------
-- Removed `survey_id` field from `survey_results` table
-- 26-03-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `survey_results` DROP `survey_id`;

-- -----------------------------------------------------------------------------
-- Created pain_trackers table.
-- 26-03-2014
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pain_trackers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` int(1) NOT NULL COMMENT '1 = Numbness, 2 = Pins & Needles, 3= Burning, 4 = Stabbing, 5 = Throbbing',
  `year` varchar(4) NOT NULL,
  `value` longtext NOT NULL COMMENT 'json',
  `latest_value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -----------------------------------------------------------------------------
-- Table structure for table `user_health_histories`
-- 04-04-2014
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_health_histories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `gender` char(1) NOT NULL,
  `age` int(11) NOT NULL,
  `height` float NOT NULL,
  `weight` float NOT NULL,
  `occupation` varchar(50) DEFAULT NULL,
  `marital_status` char(1) NOT NULL,
  `race` char(2) NOT NULL,
  `city_id` int(11) NOT NULL,
  `smoking_status` int(1) NOT NULL,
  `drinking_status` int(1) NOT NULL,
  `conditions` text,
  `allergic_medicines` text,
  `other_allergic_medicines` text,
  `allergic_food_items` text,
  `other_allergic_food_items` text,
  `environmental_allergies` text,
  `other_environmental_allergies` text,
  `childhood_illnesses` text,
  `vaccinations` text,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY ( `id` ),
  CONSTRAINT `fk_user_health_histories_user`
	FOREIGN KEY (`user_id`)
	REFERENCES `users` (`id`)
	ON DELETE CASCADE
	ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------------------------------
-- Created foreign key relations for user_symptoms table
-- 4-04-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `user_symptoms` ADD INDEX ( `user_id` ) ;
ALTER TABLE `user_symptoms` ADD INDEX ( `symptom_id` ) ;

ALTER TABLE `user_symptoms` ADD FOREIGN KEY ( `user_id` ) REFERENCES `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE `user_symptoms` ADD FOREIGN KEY ( `symptom_id` ) REFERENCES `symptoms` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-- -----------------------------------------------------------------------------
-- Added `surgeries_json` field in `user_health_histories` table
-- 07-04-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `user_health_histories` ADD `surgeries_json` TEXT NULL AFTER `vaccinations`;

-- -----------------------------------------------------------------------------
-- Added `injuries_json` field in `user_health_histories` table
-- 07-04-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `user_health_histories` ADD `injuries_json` TEXT NULL AFTER `surgeries_json`;

-- -----------------------------------------------------------------------------
-- Added 'New Admin Mail' and 'Admin Password Changed Email' email_templates.
-- 09-04-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(29, 'New Admin Mail', 'Welcome to Patients4Life Admin Panel', '\r\n\r\n                        \r\n\r\n                        \r\n\r\n                        \r\n\r\n                        \r\n\r\n                                                \r\n\r\n                        <h2 style="margin: 30px 0px 25px; font-weight: normal; font-size: 24px; ">Welcome to Patients4Life Admin Panel</h2>Hi |@username@|,<br><br>Congrats!&nbsp;Patients4Life Administrator has added you as an admin of&nbsp;Patients4Life.<div><br><div>Your credentials are:<br></div>Username:  |@username@|<br>Email:  |@email@|<div>Password:&nbsp;|@password@|</div><div><br><div>Click <span style="cursor: default; background-color: transparent;"><a href="|@link@|">here</a></span>&nbsp;to login to the admin panel.</div><div><p style="margin: 30px 0px 0px 0px;">Thanks,</p>\r\n<p style="margin: 1px 0px 0px 0px;">Patients4Life Team</p>                                                                                </div></div>                    </div>                                                            ', '0000-00-00 00:00:00', '2014-04-09 06:40:26', '', '1'),
(30, 'Admin Password Changed Email', 'Password Changed', '\r\n\r\n                        <div><br></div>Hi&nbsp;|@username@|,<div><br></div><div>Patients4Life Administrator has changed your login password.<br></div><div><br></div><div>New password:&nbsp;|@password@|</div><div>Please use this password hereafter.</div><div><span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal; "><br></span></div><div><span style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal; ">Thanks,</span><br></div><div><p style="color: rgb(69, 74, 77); font-family: ''Helvetica Neue'', Arial, Helvetica, Geneva, sans-serif; line-height: normal; margin-top: 1px; margin-bottom: 0px; ">Patients4Life Team</p></div>                    ', '2014-04-09 07:52:16', '2014-04-09 07:57:26', '1', '1');

-- -----------------------------------------------------------------------------
-- Made the first user as super admin
-- 09-04-2014
-- -----------------------------------------------------------------------------
UPDATE `users` SET `type` = '6' , `status`=1 WHERE `id` = 1;

-- -----------------------------------------------------------------------------
-- Adding new table to manage the user treatments
-- 08-04-2014
-- -----------------------------------------------------------------------------
CREATE TABLE `user_treatments` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `user_id` int(11) unsigned NOT NULL,
 `treatment_id` int(11) unsigned NOT NULL,
 `patient_disease_id` text COMMENT 'Comma separated value of primary key in the patient_diseases table',
 PRIMARY KEY (`id`),
 UNIQUE KEY `user_treatment_unique` (`user_id`,`treatment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-----------------------------------------------------------------------------
-- Added `survey_id` field in `survey_results` table
-- 08-04-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `survey_results` ADD `survey_id` int NOT NULL AFTER `user_id`;
-----------------------------------------------------------------------------
-- Changing user_treatment table structure
-- 09-04-2014
-- -----------------------------------------------------------------------------
DROP TABLE `user_treatments`;

CREATE TABLE `user_treatments` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `user_id` int(11) unsigned NOT NULL,
 `treatment_id` int(11) unsigned NOT NULL,
 `patient_disease_id` int(11) unsigned NOT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `user_treatment_disease` (`user_id`,`treatment_id`,`patient_disease_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- -----------------------------------------------------------------------------
-- Added 'site_event' `activity_type` in `notifications` table
-- 10-04-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM('invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event') NOT NULL;

-----------------------------------------------------------------------------
-- Removing unwanted fields for the treatment
-- 09-04-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `patient_diseases`  DROP `user_treatments`,  DROP `treatment_details`;

-- -----------------------------------------------------------------------------
-- Added 'Site wide event' notification email_template
-- 10-04-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(31, 'Site wide event', 'Admin Event', '\r\n\r\n                                                \r\n                        \r\n\r\n                        <div><br></div>Greetings |@username@|,<div><br></div><div>A new event is added by the Patients4Life Administrator.<br>Event name: |@event-name@|.<br></div><div>To know more about the event, click <span style="cursor: default;"><a style="text-decoration: none; cursor: default;">here</a></span>.<br></div><div><br></div><div>If you are not able to click the above link, please copy and paste the link below in your preferred browser.</div><div>|@link@|<br></div><div><br></div><div><div>Thanks,</div><div>Patients4Life Team</div></div>                                                                                ', '2014-04-10 08:59:12', '2014-04-10 08:59:12', '', '1');

-- -----------------------------------------------------------------------------
-- 'Site wide event' notification email_template link fix
-- 10-04-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '<div><br></div>Greetings |@username@|,<div><br></div><div>A new event is added by the Patients4Life Administrator.<br>Event name: |@event-name@|.<br></div><div>To know more about the event, click <span style="cursor: default; background-color: transparent;"><a href="|@link@|">here</a></span>.<br></div><div><br></div><div>If you are not able to click the above link, please copy and paste the link below in your preferred browser.</div><div>|@link@|<br></div><div><br></div><div><div>Thanks,</div><div>Patients4Life Team</div></div>' WHERE id=31;

-- -----------------------------------------------------------------------------
-- Added 'Site wide' type in `communities` table
-- 11-04-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `communities` CHANGE `type` `type` INT( 1 ) NULL DEFAULT NULL COMMENT '1: open, 2: closed, 3: site wide';

-- -----------------------------------------------------------------------------
-- Added 'site_community' `activity_type` in `notifications` table
-- 11-04-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM('invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community') NOT NULL;

-- -----------------------------------------------------------------------------
-- Added 'Site wide community' notification email_template
-- 11-04-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(32, 'Site wide community', 'Admin Community', '<div><br></div>Greetings |@username@|,<div><br></div><div>A new community has been added by the Patients4Life Administrator.<br>Community name: |@community-name@|.<br></div><div>To know more about the community, click <span style="cursor: default; background-color: transparent;"><a href="|@link@|">here</a></span>.<br></div><div><br></div><div>If you are not able to click the above link, please copy and paste the link below in your preferred browser.</div><div>|@link@|<br></div><div><br></div><div><div>Thanks,</div><div>Patients4Life Team</div></div>', '2014-04-11 04:59:56', '2014-04-11 04:59:56', '1', '1');

-----------------------------------------------------------------------------
-- Added `type` field in `surveys` table
-- 11-04-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `surveys` ADD `type` TINYINT(1) NOT NULL  COMMENT '0: Disease information, 1:Medication' AFTER `survey_key`

-----------------------------------------------------------------------------
-- Added `status` field in `survey_results` table
-- 11-04-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `survey_results` ADD `status` TINYINT(1) NOT NULL  COMMENT '0: Skipped, 1:Attended' AFTER `question_id`;

-----------------------------------------------------------------------------
-- Added ON DELETE CASCADE ON UPDATE CASCADE on `user_id` field in
-- `notification_settings` table, to avoid error on deleting an entry from `user`
-- 11-04-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notification_settings` DROP FOREIGN KEY `notification_settings_ibfk_2` ;
ALTER TABLE `notification_settings` ADD FOREIGN KEY ( `user_id` ) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;

-----------------------------------------------------------------------------
-- Tables for newsletter templates
-- 14-04-2014
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `newsletter_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(350) NOT NULL,  
  `template_body` mediumtext NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_by` varchar(200) NOT NULL,
  `modified_by` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8; 

-----------------------------------------------------------------------------
-- Tables for newsletters
-- 14-04-2014
-- -----------------------------------------------------------------------------
CREATE TABLE `newsletters` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `subject` varchar(255) NOT NULL, 
 `template_id` int(11) NOT NULL,
 `content` text NOT NULL, 
 `created` datetime NOT NULL,
 `modified` datetime NOT NULL,
 `created_by` varchar(200) NOT NULL,
 `modified_by` varchar(200) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0;

-----------------------------------------------------------------------------
-- Updating tables for newsletters
-- 14-04-2014
-- -----------------------------------------------------------------------------
ALTER TABLE  `newsletters` CHANGE  `created_by`  `created_by` INT( 11 ) NOT NULL;
ALTER TABLE  `newsletters` CHANGE  `modified_by`  `modified_by` INT( 11 ) NOT NULL;

-- -----------------------------------------------------------------------------
-- Added 'Admin Account Removal' notification email_template
-- 16-04-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(33, 'Delete Admin', 'Admin Account Removal', '<table width="578" align="center" style="table-layout: fixed; "><tbody><tr><td><h2 style="margin-top: 30px; margin-bottom: 25px; font-family: '''', ''Open Sans'', '''', sans-serif; color: rgb(0, 0, 0); font-size: 30px; "></h2></td></tr><tr><td style="padding-bottom: 10px; font-size: 14px; ">Hi |@username@|,<br><br></td></tr><tr><td style="padding-bottom: 20px; font-size: 14px; ">We are sorry to inform that your admin account with Patients4Life is removed by the administrator. You are no longer a member of Patients4life.</td></tr><tr><td style="font-size: 14px; padding-bottom: 5px; ">Thanks,</td></tr><tr><td style="font-size: 14px; ">Patients4Life Team</td></tr></tbody></table>', '2014-04-16 08:42:15', '2014-04-16 08:42:15', '1', '1');

-----------------------------------------------------------------------------
-- adding table for newsletters queue status
-- 16-04-2014
-- -----------------------------------------------------------------------------
ALTER TABLE  `newsletter_templates` CHANGE  `created_by`  `created_by` INT( 11 ) NOT NULL;
ALTER TABLE  `newsletter_templates` CHANGE  `modified_by`  `modified_by` INT( 11 ) NOT NULL;

CREATE TABLE `newsletter_queue_status` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `instance_id` varchar(255) NOT NULL,
 `newsletter_id` int(11) NOT NULL,
 `subject` varchar(255) NOT NULL,
 `total_count` int(11) NOT NULL,
 `sent_count` int(11) NOT NULL,
 `fail_count` int(11) NOT NULL,
 `created` datetime NOT NULL,
 `modified` datetime NOT NULL,
 `status` enum('0','1') NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`),
 UNIQUE KEY `instance_id` (`instance_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0;

-----------------------------------------------------------------------------
-- modifying tables for tracking newsletter queue in email queue
-- 17-04-2014
-- -----------------------------------------------------------------------------

ALTER TABLE `emails` ADD `instance_id` VARCHAR( 255 ) NOT NULL AFTER `email_template_id`;
ALTER TABLE `emails_histories` ADD `instance_id` VARCHAR( 255 ) NOT NULL AFTER `email_template_id`;
ALTER TABLE `newsletters`
  DROP `template_id`;

-- -----------------------------------------------------------------------------
-- Modified the datatype of `weight` and `height` in `user_health_histories` table
-- 21-04-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `user_health_histories` 
CHANGE `weight` `weight` DECIMAL(6, 2) NOT NULL,
CHANGE `height` `height` VARCHAR(10) NOT NULL;

-- -----------------------------------------------------------------------------
-- Reset existing `weight` and `height` values in `user_health_histories` table
-- 21-04-2014
-- -----------------------------------------------------------------------------
UPDATE `user_health_histories` SET `height`=NULL, `weight`=NULL;

-- -----------------------------------------------------------------------------
-- Drop foreign key template_id from emails
-- 22-04-2014
-- -----------------------------------------------------------------------------
alter table  emails drop foreign key emails_ibfk_1;

ALTER TABLE  `emails` DROP INDEX  `emails_ibfk_1`;

-- -----------------------------------------------------------------------------
-- Added `other_conditions` field in `user_health_histories` table
-- 23-04-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `user_health_histories` ADD `other_conditions` TEXT NULL AFTER `conditions`;

-- -----------------------------------------------------------------------------
-- Added `middle_name`, `ssn` fields and changed `age` to `dob` in `user_health_histories` table
-- 23-04-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `user_health_histories` ADD `middle_name` VARCHAR( 10 ) NULL AFTER `first_name`;
ALTER TABLE `user_health_histories` ADD `ssn` VARCHAR( 15 ) NOT NULL AFTER `user_id`;
ALTER TABLE `user_health_histories` CHANGE `age` `dob` DATE NOT NULL;

-- -----------------------------------------------------------------------------
-- Changed 'Admin Account Deleted' email template to 'Admin Account Deactivated'
-- Added 'Admin Account Activated' email template
-- 28-04-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_name` = 'Deactivate Admin',
`template_subject` = 'Admin Account Deactivated',
`template_body` = '<table width="578" align="center" style="table-layout: fixed; "><tbody><tr><td><h2 style="margin-top: 30px; margin-bottom: 25px; font-family: '''', ''Open Sans'', '''', sans-serif; color: rgb(0, 0, 0); font-size: 30px; "></h2></td></tr><tr><td style="padding-bottom: 10px; font-size: 14px; ">Hi |@username@|,<br><br></td></tr><tr><td style="padding-bottom: 20px; font-size: 14px; ">We are sorry to inform that your admin account with Patients4Life has been deactivated by the administrator. You are no longer an active member of Patients4life.</td></tr><tr><td style="font-size: 14px; padding-bottom: 5px; ">Thanks,</td></tr><tr><td style="font-size: 14px; ">Patients4Life Team</td></tr></tbody></table>' WHERE `email_templates`.`id` =33;

INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(34, 'Activate Admin', 'Admin Account Activated', '<table width="578" align="center" style="table-layout: fixed; "><tbody><tr><td><h2 style="margin-top: 30px; margin-bottom: 25px; font-family: '''', ''Open Sans'', '''', sans-serif; color: rgb(0, 0, 0); font-size: 30px; "></h2></td></tr><tr><td style="padding-bottom: 10px; font-size: 14px; ">Greetings |@username@|,<br><br></td></tr><tr><td style="padding-bottom: 20px; font-size: 14px; ">We are happy to inform that your admin account with Patients4Life has been activated by the administrator. You are now an active member of Patients4life.</td></tr><tr><td style="font-size: 14px; padding-bottom: 5px; ">Thanks,</td></tr><tr><td style="font-size: 14px; ">Patients4Life Team</td></tr></tbody></table>', '2014-04-16 08:42:15', '2014-04-16 08:42:15', '1', '1');

-- -----------------------------------------------------------------------------
-- Removed `ssn` field from `user_health_histories`
-- 29-04-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `user_health_histories` DROP `ssn`;

-----------------------------------------------------------------------------
-- Added `status` field in `surveys` table
-- 29-04-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `surveys` ADD `status` TINYINT(1) NOT NULL  COMMENT '0: Penting, 1:Published' AFTER `type`;

-- -----------------------------------------------------------------------------
-- Removed `height`, and `weight` fields from `user_health_histories`
-- 02-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `user_health_histories` DROP `height`,  DROP `weight`;

-----------------------------------------------------------------------------
-- Added `dashboard_data` field in `diseases` table
-- 02-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE  `diseases` ADD  `dashboard_data` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `library`;

-- -----------------------------------------------------------------------------
-- Added `is_anonymous` field in `comments` table
-- 06-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `comments` ADD `is_anonymous` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT '0: not anonymous, 1: anonymous' AFTER `comment_text`;

-- -----------------------------------------------------------------------------
-- Added `is_anonymous` field in `posts` table
-- 07-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `posts` ADD `is_anonymous` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT '0: not anonymous, 1: anonymous';

-- -----------------------------------------------------------------------------
-- Removed NULL constraint from `smoking_status` and `drinking_status` fields in `user_health_histories` table
-- 07-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `user_health_histories` 
CHANGE `smoking_status` `smoking_status` INT(1) NULL DEFAULT NULL,
CHANGE `drinking_status` `drinking_status` INT(1) NULL DEFAULT NULL;

-- -----------------------------------------------------------------------------
-- Added `is_anonymous` field in `notifications` table
-- 09-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` ADD `is_anonymous` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT '0: not anonymous, 1: anonymous';

-- -----------------------------------------------------------------------------
-- Created `abuse_reports` table
-- 07-05-2014
-- -----------------------------------------------------------------------------
CREATE TABLE `abuse_reports` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`object_id` INT NOT NULL,
`object_type` ENUM('post', 'comment') NOT NULL,
`reported_user_id` INT NOT NULL,
`object_owner_id` INT NOT NULL,
`reason` TEXT NULL,
`status` ENUM('new', 'rejected', 'deleted') NOT NULL DEFAULT 'new',
`created` DATETIME NOT NULL,
`admin_comment` TEXT NULL,
`action_taken_date` DATETIME NULL
) ENGINE = InnoDB;

-- -----------------------------------------------------------------------------
-- Set default value in `status` field in `comments` table as 0
-- 12-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `comments` CHANGE `status` `status` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT '0: normal,1: waiting for abuse clearance,2: blocked';

-- -----------------------------------------------------------------------------
-- Removing unused table timezones
-- 09-05-2014
-- -----------------------------------------------------------------------------
DROP TABLE timezones;

-- -----------------------------------------------------------------------------
-- Config value for new user's default friend
-- 13-05-2014
-- -----------------------------------------------------------------------------
INSERT INTO `configurations`(`id`, `name`, `label`) VALUES (2,"new_users_friend_id","Friend for New User");

UPDATE  `configurations` SET  `name` =  'contact_email' WHERE  `configurations`.`id` =1;

-- -----------------------------------------------------------------------------
-- Added missing link in post comment notification email template
-- 15-05-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = ' <br>Greetings |@username@|,<br><br> |@name@| |@content@|.<br><br> |@name@| wrote:<br> |@comment@|<br><br><a style="background-color: transparent; text-decoration: none; cursor: pointer;" href="|@post_link@|">See Comment</a> <div><a style="background-color: transparent; text-decoration: none; cursor: default;"><br></a></div><div><span style="cursor: default;">Thanks,&nbsp;</span></div><div><span style="cursor: default;">Patients4Life Team</span></div>' WHERE `id` = 26;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
-- 16-05-2014
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `about` varchar(250) DEFAULT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0 = not approved, 1= approved',
  `patient_id` int(11) unsigned NOT NULL,
  `created_by` int(11) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
-- 16-05-2014
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `team_members` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `team_id` int(11) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0 = not approved, 1= approved',
  `role` tinyint(1) NOT NULL COMMENT ' 0 = member ( default ), 1 = patient, 2 = organizer, 3 = both patient & organizer',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table ` volunteer`
-- 16-05-2014
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `volunteers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT ' 0 = normal volunteer, 1= volunteered for team leader',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


ALTER TABLE `teams` ADD INDEX ( `patient_id` );

ALTER TABLE `teams` ADD INDEX ( `created_by` );

ALTER TABLE `teams` ADD FOREIGN KEY ( `patient_id` ) REFERENCES `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE `teams` ADD FOREIGN KEY ( `created_by` ) REFERENCES `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE `team_members` ADD INDEX ( `user_id` );

ALTER TABLE `team_members` ADD INDEX ( `team_id` );

ALTER TABLE `team_members` ADD FOREIGN KEY ( `user_id` ) REFERENCES `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE `team_members` ADD FOREIGN KEY ( `team_id` ) REFERENCES `teams` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE `volunteers` CHANGE `id` `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT;

-- -----------------------------------------------------------------------------
-- Removed unwanted space from field names in `teams` and `team_members` tables
-- 16-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `teams` CHANGE ` modified` `modified` DATETIME NOT NULL;
ALTER TABLE `team_members` CHANGE ` status` `status` TINYINT( 1 ) NOT NULL COMMENT '0 = not approved, 1= approved',
CHANGE ` role` `role` TINYINT( 1 ) NOT NULL COMMENT ' 0 = member ( default ), 1 = patient, 2 = organizer, 3 = both patient & organizer';

-- -----------------------------------------------------------------------------
-- Added 'team' in `posted_in_type` in `posts` and `polls` tables
-- 16-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `posts` CHANGE `posted_in_type` `posted_in_type` ENUM( 'communities', 'events', 'users', 'diseases', 'team' ) NOT NULL;
ALTER TABLE `polls` CHANGE `posted_in_type` `posted_in_type` ENUM( 'communities', 'events', 'users', 'diseases', 'team' ) NOT NULL;

-- -----------------------------------------------------------------------------
-- Added 'team' in `object_type`, `activity_in_type` fields in `notifications` table
-- 16-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `object_type` `object_type` ENUM( 'event', 'community', 'profile', 'post', 'poll_post', 'team' ) NOT NULL ,
CHANGE `activity_in_type` `activity_in_type` ENUM( 'event', 'community', 'profile', 'team' ) NULL DEFAULT NULL;

--------------------------------------------------------------------------------
-- Table structure for table care_calendar_events.
-- 16-05-2014
--------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `care_calendar_events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `asigned_to` int(11) NOT NULL,
  `status` int(4) NOT NULL,
  `type` int(4) NOT NULL,
  `additional_notes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

--------------------------------------------------------------------------------
-- Updating comment in events table that a new type 'care calendar event' has been added.
-- 16-05-2014
--------------------------------------------------------------------------------
ALTER TABLE  `events` CHANGE  `event_type`  `event_type` TINYINT( 4 ) NOT NULL COMMENT '1 - Public, 2- Private  (Only invited can mark attendance ), 4 - Calendar Reminder, 5 - team event';

--------------------------------------------------------------------------------
-- Updating  'care calendar event' .
-- 20-05-2014
--------------------------------------------------------------------------------
ALTER TABLE  `care_calendar_events` CHANGE  `asigned_to`  `assigned_to` INT( 11 ) NOT NULL;

--------------------------------------------------------------------------------
-- Changed the datatype of `role` field to 'int' instead of 'tinyint' in `team_members` table
-- 21-05-2014
--------------------------------------------------------------------------------
ALTER TABLE `team_members` CHANGE `role` `role` INT( 1 ) NOT NULL COMMENT ' 0 = member ( default ), 1 = patient, 2 = organizer, 3 = both patient & organizer';

-- -----------------------------------------------------------------------------
-- Adding Team Approval notification email template.
-- 21-05-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(35, 'Team Approval', 'Patients4Life : "|@team-name@|" Team Approved', '<br>Greetings |@username@|,<br><br>|@name@| has approved the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a>.<br>                     <div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-05-21 03:45:36', '2014-05-21 03:45:36', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding Team Decline notification email template.
-- 21-05-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(36, 'Team Decline', 'Patients4Life : "|@team-name@|" Team Declined', '<br>Greetings |@username@|,<br><br>We are sorry to inform that |@name@| has declined the team "|@team-name@|".<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-05-21 04:25:36', '2014-05-21 04:25:36', '1', '1');

--------------------------------------------------------------------------------
-- Added field 'invited_by' in `team_members` table to track who invited the user
-- 21-05-2014
--------------------------------------------------------------------------------
ALTER TABLE `team_members` ADD `invited_by` INT( 11 ) UNSIGNED NOT NULL AFTER `user_id` ,
ADD INDEX ( `invited_by` );

TRUNCATE TABLE `team_members`;

ALTER TABLE `team_members` ADD FOREIGN KEY ( `invited_by` ) REFERENCES `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

--------------------------------------------------------------------------------
-- Made 'invited_by' field in `team_members` table nullable
-- 21-05-2014
--------------------------------------------------------------------------------
ALTER TABLE `team_members` CHANGE `invited_by` `invited_by` INT( 11 ) UNSIGNED NULL DEFAULT NULL;


-- -----------------------------------------------------------------------------
-- Adding Team Invitation Approval notification email template.
-- 22-05-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(37, 'Team Invitation Approval', 'Patients4Life : Accepted Team Invitation', '<br>Greetings |@username@|,<br><br>|@name@| has accepted the invitation to join the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a>.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-05-22 10:25:00', '2014-05-22 10:25:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding Team Invitation Decline notification email template.
-- 22-05-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(38, 'Team Invitation Decline', 'Patients4Life : Declined Team Invitation', '<br>Greetings |@username@|,<br><br>We are sorry to inform that |@name@| has declined the invitation to join the team "|@team-name@|".<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-05-22 11:10:00', '2014-05-22 11:10:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Added 'accept_team_join_invitation' `activity_type` in `notifications` table
-- 22-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM('invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'accept_team_join_invitation') NOT NULL;

-- -----------------------------------------------------------------------------
-- Added unique constraint to (`team_id`,`user_id`) in `team_members` table
-- 22-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `team_members` ADD UNIQUE `unique_team_member`(`team_id`, `user_id`);

-- -----------------------------------------------------------------------------
-- Adding Team Invitation notification email template.
-- 22-05-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(39, 'Team Invitation', 'Patients4Life : Team Invitation', '<br>Greetings |@username@|,<br><br>You have been invited to join the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a>.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-05-22 12:55:00', '2014-05-22 12:55:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding member count in team table
-- 22-05-2014
-- ----------------------------------------------------------------------------

 ALTER TABLE `teams` ADD `member_count` INT( 11 ) NOT NULL DEFAULT '0' AFTER `status`;

-- -----------------------------------------------------------------------------
-- Added 'team_join_invitation' `activity_type` in `notifications` table
-- 23-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM( 'invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation') NOT NULL;

-- ----------------------------------------------------------------------------
-- Changing care calendar events table
-- 23-05-2014
-- ----------------------------------------------------------------------------
ALTER TABLE  `care_calendar_events` ADD  `history` TEXT NULL COMMENT  'json';
ALTER TABLE  `care_calendar_events` ADD  `times_per_day` INT( 4 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `type`;
ALTER TABLE  `care_calendar_events` CHANGE  `status`  `status` INT( 4 ) NOT NULL COMMENT  '0:Open, 1:Waiting for approval, 2:Assigned, 3:Completed';

-- -----------------------------------------------------------------------------
-- Added 'removed_from_team' `activity_type` in `notifications` table
-- 23-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM( 'invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'removed_from_team') NOT NULL;

-- -----------------------------------------------------------------------------
-- Added 'care_request' `activity_type` in `notifications` table
-- 23-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM( 'invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'removed_from_team', 'care_request') NOT NULL;

-- -----------------------------------------------------------------------------
-- Added 'care_request_change' `activity_type` in `notifications` table
-- 23-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM( 'invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change') NOT NULL;

-- -----------------------------------------------------------------------------
-- Added 'health_status_change' `activity_type` in `notifications` table
-- 23-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM( 'invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change', 'health_status_change') NOT NULL;

-- -----------------------------------------------------------------------------
-- Adding 'Team Patient Health State Changed' notification email template.
-- 23-05-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(40, 'Team Patient Health State Changed Notification', 'Patients4Life : Team |@team-name@| : Patient Health State Changed', '<br>Greetings |@username@|,<br><br>The patient "|@name@|" of the team "|@team-name@|" has changed the health status from "|@healthStatus@|" to "|@newHealthStatus@|".<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-05-23 16:35:00', '2014-05-23 16:35:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding team link in 'Team Patient Health State Changed' email template.
-- 23-05-2014
-- ----------------------------------------------------------------------------
UPDATE `email_templates`
SET `template_body` = '<br>Greetings |@username@|,<br><br>The patient "|@name@|" of the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a> has changed the health status from "|@healthStatus@|" to "|@newHealthStatus@|".<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>'
WHERE `id` = 40;

-- -----------------------------------------------------------------------------
-- Adding 'Team Care Request' notification email template.
-- 23-05-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(41, 'Team Care Request Notification', 'Patients4Life : Team |@team-name@| : Care Request', '<br>Greetings |@username@|,<br><br>"|@name@|" has requested for care "|@careType@|" in the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a>.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-05-23 18:15:00', '2014-05-23 18:15:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding 'Team Care Request Change' notification email template.
-- 26-05-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(42, 'Team Care Request Change Notification', 'Patients4Life : Team |@team-name@| : Care Request Changed', '<br>Greetings |@username@|,<br><br>"|@name@|" has changed the request for care "|@careType@|" in the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a>.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-05-26 10:15:00', '2014-05-26 10:15:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Added 'create_team' `activity_type` in `notifications` table
-- 26-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM('invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change', 'health_status_change', 'create_team') NOT NULL;

-- -----------------------------------------------------------------------------
-- Adding 'Team Created' notification email template.
-- 26-05-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(43, 'Team Created Notification', 'Patients4Life : New team to support you', '<br>Greetings |@username@|,<br><br>"|@name@|" has created a team "|@team-name@|" to support you. Please click <a style="text-decoration: none; cursor: pointer;" href="|@link@|">here</a> to know more details.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-05-26 11:05:00', '2014-05-26 11:05:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding 'Removed from team' notification email template.
-- 26-05-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(44, 'Removed from team notification', 'Patients4Life : Removed from team "|@team-name@|"', '<br>Greetings |@username@|,<br><br>We are sorry to inform that you have been removed from the team "|@team-name@|".<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-05-26 11:20:00', '2014-05-26 11:20:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Care calendar events table `type` changed from int to varchar
-- 26-05-2014
-- ----------------------------------------------------------------------------	
ALTER TABLE  `care_calendar_events` CHANGE  `type`  `type` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE  `care_calendar_events` CHANGE  `additional_notes`  `additional_notes` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `care_calendar_events` CHANGE  `history`  `history` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT  'json';

-- -----------------------------------------------------------------------------
-- Added 'decline_team_join_invitation' `activity_type` in `notifications` table
-- 26-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM('invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'decline_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change', 'health_status_change', 'create_team') NOT NULL;

-- -----------------------------------------------------------------------------
-- Adding 'Team Member role status' to team member table.
-- 26-05-2014
-- ----------------------------------------------------------------------------
ALTER TABLE `team_members` ADD `role_status` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT '0 = Not approved, 1 = approved' AFTER `role`;

-- -----------------------------------------------------------------------------
-- Adding Team Invitation Reminder notification email template.
-- 27-05-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(45, 'Team Invitation Reminder', 'Patients4Life : Team Invitation Reminder','<br>Greetings |@username@|,<br><br>You had been invited by |@name@| to join the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a> one week ago. You have not responded yet. Please click <a style="text-decoration: none; cursor: pointer;" href="|@link@|">here</a> to respond.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-05-27 12:05:00', '2014-05-27 12:05:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Removed role_status and added new_role field
-- 27-05-2014
-- ----------------------------------------------------------------------------
ALTER TABLE `team_members` DROP `role_status`;

ALTER TABLE `team_members` ADD `new_role` INT( 1 ) NULL AFTER `role`;

-- -----------------------------------------------------------------------------
-- Adding Team delete notification email template.
-- 27-05-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` ( `id` , `template_name` , `template_subject` , `template_body` , `created` , `modified` , `created_by` , `modified_by` )
VALUES ( 46, 'Team Delete Notification', 'Patients4Life : |@team-name@| Deleted', '<div><br></div>Hi&nbsp;|@username@|,<div><div style="color: rgb(69, 74, 77); font-family: Helvetica Neue, Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br><div>The Team "|@team-name@|" has been removed by the owner.</div><div><br></div><div>Thanks,</div><div>Patients4Life Team</div></div></div>', '2014-05-27 12:05:00', '2014-05-27 12:05:00', '1', '1' ) 

-- -----------------------------------------------------------------------------
-- Adding removed from team with reason notification email template.
-- 27-05-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(47, 'Removed from Team Notification', 'Patients4Life : Removed from Team |@team-name@|','<div><br></div>Hi&nbsp;|@username@|,<div><div style="color: rgb(69, 74, 77); font-family: Helvetica Neue, Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br><div>You are removed from the team |@team-name@|.<br> Reason: |@reason@|</div><div><br></div><div>Thanks,</div><div>Patients4Life Team</div></div></div> ', '2014-05-27 12:05:00', '2014-05-27 12:05:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding Care Calendar Reminder notification email template.
-- 27-05-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(48, 'Care Calendar Reminder', 'Patients4Life : Tasks for |@date@|','<br>Greetings |@username@|,<br><br>Here is the task list for today:|@care-calendar-reminder-body@|<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-05-27 04:15:00', '2014-05-27 04:15:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Added 'team_task_reminder' `activity_type` in `notifications` table
-- 28-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM( 'invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'decline_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change', 'health_status_change', 'create_team', 'team_task_reminder' ) NOT NULL;

-- -----------------------------------------------------------------------------
-- Set the `assigned_to` field in `notifications` table default to NULL
-- 28-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `care_calendar_events` CHANGE `assigned_to` `assigned_to` INT( 11 ) NULL DEFAULT NULL;

-- -----------------------------------------------------------------------------
-- Adding care calendar daily digest email template.
-- 28-05-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(49, 'Care Calendar Daily Digest', 'Patients4Life : Care Calendar Daily Digest of team "|@team-name@|" for |@date@|','<br>Greetings |@username@|,<br><br>Here is the Daily Digest of team "|@team-name@|" for today:|@team-task-list@|<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-05-28 19:05:00', '2014-05-28 19:05:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Added 'role_assigned_by' field to team_members table
-- 28-05-2014
-- -----------------------------------------------------------------------------

ALTER TABLE `team_members` ADD `role_invited_by` INT( 11 ) UNSIGNED NULL DEFAULT NULL AFTER `new_role`;

ALTER TABLE `team_members` ADD INDEX ( `role_invited_by` );

ALTER TABLE `team_members` ADD FOREIGN KEY ( `role_invited_by` ) REFERENCES `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-- -----------------------------------------------------------------------------
-- Added role approval & decline notification email templates.
-- 28-05-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(50, 'Role Invitation Approval', 'Patients4Life : Accepted Organizer role Invitation', '<br>Greetings |@username@|,<br><br>|@name@| has accepted the invitation to serve as an organizer for the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a>.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-05-22 10:25:00', '2014-05-22 10:25:00', '1', '1');


INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(51, 'Role Invitation Decline', 'Patients4Life : Declined Organizer role Invitation', '<br>Greetings |@username@|,<br><br>We are sorry to inform that |@name@| has declined the invitation to serve as an organizer for the team "|@team-name@|".<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-05-22 11:10:00', '2014-05-22 11:10:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Added 'team_role_approved' `activity_type` in `notifications` table
-- 30-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM( 'invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'decline_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change', 'health_status_change', 'create_team', 'team_task_reminder',  'team_role_approved' ) NOT NULL;

-- -----------------------------------------------------------------------------
-- Added 'team_role_declined' `activity_type` in `notifications` table
-- 30-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM( 'invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'decline_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change', 'health_status_change', 'create_team', 'team_task_reminder',  'team_role_approved', 'team_role_declined' ) NOT NULL;

-- -----------------------------------------------------------------------------
-- Added 'team_approved', and 'team_declined' `activity_type` in `notifications` table
-- 30-05-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM( 'invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'decline_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change', 'health_status_change', 'create_team', 'team_task_reminder',  'team_role_approved', 'team_role_declined', 'team_approved', 'team_declined' ) NOT NULL;

-- -----------------------------------------------------------------------------
-- Added 'team_role_invitation' `activity_type` in `notifications` table
-- 02-06-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM( 'invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'decline_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change', 'health_status_change', 'create_team', 'team_task_reminder',  'team_role_approved', 'team_role_declined', 'team_approved', 'team_declined', 'team_role_invitation') NOT NULL;

-- -----------------------------------------------------------------------------
-- Adding 'Team Role Invitation' email template.
-- 02-06-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(52, 'Team Role Invitation', 'Patients4Life : Team |@role@| invitation', '<br>Greetings |@username@|,<br><br>"|@name@|" has invited you to become the |@role@| of team "|@team-name@|". Please click <a style="text-decoration: none; cursor: pointer;" href="|@link@|">here</a> to respond.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-06-02 11:20:00', '2014-06-02 11:20:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Added 'joined_on' in `team_members` table
-- 02-06-2014
-- -----------------------------------------------------------------------------
ALTER TABLE  `team_members` ADD  `joined_on` DATETIME NULL DEFAULT NULL AFTER  `created`;
-- -----------------------------------------------------------------------------
-- INserting value for 'joined_on' in `team_members` table
-- 02-06-2014
-- -----------------------------------------------------------------------------
UPDATE `team_members` SET `joined_on` = `modified` WHERE `status` = 1;

--------------------------------------------------------------------------------
-- Updating  'care calendar event' .
-- 2-06-2014
--------------------------------------------------------------------------------
ALTER TABLE  `care_calendar_events` CHANGE  `assigned_to`  `assigned_to` INT( 11 ) NOT NULL;
--------------------------------------------------------------------------------
-- Updating  'email templates' .
-- 2-06-2014
--------------------------------------------------------------------------------
UPDATE  `email_templates` SET  `template_subject` =  'Patients4Life : Team |@team-name@| : New Task : |@task-name@| ' WHERE  `email_templates`.`id` =41;
UPDATE  `email_templates` SET  `template_subject` =  'Patients4Life : Team |@team-name@| : Task modified : |@task-name@|' WHERE  `email_templates`.`id` =42;
--------------------------------------------------------------------------------
-- Updating  'email templates' for providing link to team name.
-- 4-06-2014
--------------------------------------------------------------------------------

UPDATE `email_templates` SET `template_body` = '<br>Greetings |@username@|,<br><br>We are sorry to inform that |@name@| has declined the invitation to serve as an organizer for the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">"|@team-name@|"</a>.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>' WHERE `email_templates`.`id` = 51;

UPDATE `email_templates` SET `template_body` = '<div><br></div>Hi&nbsp;|@username@|,<div><div style="color: rgb(69, 74, 77); font-family: Helvetica Neue, Arial, Helvetica, Geneva, sans-serif; line-height: normal;"><br><div>You are removed from the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a>.<br> Reason: |@reason@|</div><div><br></div><div>Thanks,</div><div>Patients4Life Team</div></div></div> ' WHERE `email_templates`.`id` = 47;

UPDATE `email_templates` SET `template_name` = 'Removed from team notification - without reason', `template_body` = '<br>Greetings |@username@|,<br><br>We are sorry to inform that you have been removed from the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">"|@team-name@|"</a>.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>' WHERE `email_templates`.`id` = 44;

UPDATE `email_templates` SET `template_body` = '<br>Greetings |@username@|,<br><br>We are sorry to inform that |@name@| has declined the invitation to join the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">"|@team-name@|"</a>.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>' WHERE `email_templates`.`id` = 38;

-- -----------------------------------------------------------------------------
-- Truncating `queued_tasks` table
-- 05-06-2014
-- -----------------------------------------------------------------------------
TRUNCATE TABLE `queued_tasks`;
-- -----------------------------------------------------------------------------
-- Updating email template for daily task notification
-- 05-06-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET  `template_body` = 'Greetings |@username@|,<br><br>Here is the Daily Digest of team "<a href="|@link@|">|@team-name@|</a>"&nbsp;for today:|@team-task-list@|<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>' WHERE  `email_templates`.`id` =49;
-- -----------------------------------------------------------------------------
-- Added 'friend_request_approved' `activity_type` in `notifications` table
-- 05-06-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM( 'invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'decline_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change', 'health_status_change', 'create_team', 'team_task_reminder',  'team_role_approved', 'team_role_declined', 'team_approved', 'team_declined', 'team_role_invitation', 'friend_request_approved') NOT NULL;

-- -----------------------------------------------------------------------------
-- Added `email_notification` and  `site_notification` fields in `team_members` table
-- 05-06-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `team_members` ADD `email_notification` TINYINT( 1 ) NOT NULL DEFAULT '1' COMMENT '1: ON, 0: OFF';
ALTER TABLE `team_members` ADD `site_notification` TINYINT( 1 ) NOT NULL DEFAULT '1' COMMENT '1: ON, 0: OFF';

-- -----------------------------------------------------------------------------
-- Adding 'Team Approval Reminder' notification email template.
-- 05-06-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(53, 'Team Approval Reminder', 'Patients4Life : Team Approval Reminder', '<br>Greetings |@username@|,<br><br>|@name@| has created a team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a> to support you one week ago. You have not responded yet. Please click <a style="text-decoration: none; cursor: pointer;" href="|@link@|">here</a> to respond.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-06-05 11:05:00', '2014-06-05 11:05:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding 'Role Promotion Reminder' notification email template.
-- 05-06-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(54, 'Team Role Promotion Reminder', 'Patients4Life : Team Role Promotion Reminder', '<br>Greetings |@username@|,<br><br>|@name@| has invited you to become the |@role@| of team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a> one week ago. You have not responded yet. Please click <a style="text-decoration: none; cursor: pointer;" href="|@link@|">here</a> to respond.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-06-05 11:05:00', '2014-06-05 11:05:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding 'Demote Organizer' notification email template.
-- 05-06-2014
-- ----------------------------------------------------------------------------

INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(55, 'Demote from Organizer to Member', 'Patients4Life : Removed Organizer role from team "|@team-name@|"', '<br>Greetings |@username@|,<br><br>We are sorry to inform that you have been removed as Organizer from the team "|@team-name@|".<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-06-05 11:20:00', '2014-06-05 11:20:00', '1', '1');

UPDATE `email_templates` SET `template_body` = '<br>Greetings |@username@|,<br><br>We are sorry to inform that you have been removed as Organizer from the team " <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a>".<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>' WHERE `email_templates`.`id` = 55;


-- -----------------------------------------------------------------------------
-- Added 'demote_organizer' `activity_type` in `notifications` table
-- 05-06-2014
-- -----------------------------------------------------------------------------

ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM( 'invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'decline_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change', 'health_status_change', 'create_team', 'team_task_reminder',  'team_role_approved', 'team_role_declined', 'team_approved', 'team_declined', 'team_role_invitation', 'friend_request_approved','demote_organizer') NOT NULL;

-- -----------------------------------------------------------------------------
-- Updating email template for Team Approval Reminder
-- 06-06-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET  `template_body` = '<br>Greetings |@username@|,<br><br>|@name@| has created a team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a> to support you |@week_count@| week(s) ago. You have not responded yet. Please click <a style="text-decoration: none; cursor: pointer;" href="|@link@|">here</a> to respond.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>' WHERE  `email_templates`.`id` =53;

-- -----------------------------------------------------------------------------
-- Updating email template for Role Promotion Reminder
-- 06-06-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET  `template_body` = '<br>Greetings |@username@|,<br><br>|@name@| has invited you to become the |@role@| of team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a> |@week_count@| week(s) ago. You have not responded yet. Please click <a style="text-decoration: none; cursor: pointer;" href="|@link@|">here</a> to respond.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>' WHERE  `email_templates`.`id` =54;
-- -----------------------------------------------------------------------------
-- Updating email template for Team Invitation Reminder
-- 06-06-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET  `template_body` = '<br>Greetings |@username@|,<br><br>You had been invited by |@name@| to join the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a> |@week_count@| week(s) ago. You have not responded yet. Please click <a style="text-decoration: none; cursor: pointer;" href="|@link@|">here</a> to respond.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>' WHERE  `email_templates`.`id` =45;

-- -----------------------------------------------------------------------------
-- Query for updating state name
-- 05-06-2014
-- -----------------------------------------------------------------------------
UPDATE `countries` SET `short_name` = 'Bolivia' WHERE `countries`.`id` =27;
UPDATE `states` SET `description` = 'Nana-Grebizi' WHERE `states`.`id` =546;
UPDATE `states` SET `description` = 'Fokida' WHERE `states`.`id` =1036;

-- -----------------------------------------------------------------------------
-- Query for updating country name
-- 05-06-2014
-- -----------------------------------------------------------------------------
UPDATE `countries` SET `short_name` = 'Cocos (Keeling) Islands' WHERE `countries`.`id` =46;
UPDATE `countries` SET `short_name` = 'Côte d’Ivoire' WHERE `countries`.`id` =53;

-- -----------------------------------------------------------------------------
-- Adding new column to team member table to save the permission settings.
-- 05-06-2014
-- -----------------------------------------------------------------------------
ALTER TABLE  `team_members` ADD  `can_view_medical_data` TINYINT( 4 ) NOT NULL DEFAULT  '0' COMMENT '0 : not allowed to view medical data, 1: requested to view the medical data, 2: allowed to view the medical data' AFTER  `role_invited_by`;

-- -----------------------------------------------------------------------------
-- Adding new city 'Queens' to cities table.
-- 09-06-2014
-- -----------------------------------------------------------------------------
INSERT INTO  `cities` (`id` ,`state_id` ,`description` ,`latitude` ,`longitude` ,`short_description` ,`created_datetime` ,`created_by` ,`modified_datetime` ,`modified_by` ,`modified_date` ,`content`)VALUES (113990 ,  '3538',  'Queens',  '40.729896',  '-73.800811',  'Queens',  '2014-06-02 00:00:00', NULL ,  '2014-06-01 00:00:00', NULL ,  '2014-06-01 00:00:00',  '');

-- -----------------------------------------------------------------------------
-- Adding 'Report Abuse Review' email template.
-- 09-06-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(56, 'Report Abuse Review', 'Patients4Life : Report Abuse Review', '<br>Greetings |@username@|,<br>We are sorry for the inconvenience caused. Your report abuse is currently under review and it will be looked at by an admin as soon as possible.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-06-09 15:50:00', '2014-06-09 15:50:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Changing 'Report Abuse Review' email template.
-- 09-06-2014
-- ----------------------------------------------------------------------------	
UPDATE `email_templates` SET `template_body` = '<br>Greetings |@username@|,<br><br>We are sorry for the inconvenience caused. Your report abuse is currently under review and it will be looked at by an admin as soon as possible.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>' WHERE `id` =56;
-- -----------------------------------------------------------------------------
-- Changing 'Care Calendar Daily Digest' email template.
-- 10-06-2014
-- ----------------------------------------------------------------------------	
UPDATE `email_templates` SET `template_body` = '<br>Greetings |@username@|,<br><br>Here is the Daily Digest of team "<a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a>" for today:|@team-task-list@|<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>' WHERE `email_templates`.`id` = 49;

-- -----------------------------------------------------------------------------
-- Adding new 'pain tracker' type.
-- 10-06-2014
-- ----------------------------------------------------------------------------	
ALTER TABLE  `pain_trackers` CHANGE  `type`  `type` INT( 1 ) NOT NULL COMMENT '1 = Numbness, 2 = Pins & Needles, 3= Burning, 4 = Stabbing, 5 = Throbbing, 6 = Aching, 7 = Cramping';

-- -----------------------------------------------------------------------------
-- Truncate 'pain tracker' table
-- 10-06-2014
-- ----------------------------------------------------------------------------	
TRUNCATE TABLE  `pain_trackers`;

-- -----------------------------------------------------------------------------
-- Adding new record type in health_readings (bmi).
-- 10-06-2014
-- ----------------------------------------------------------------------------	
ALTER TABLE  `health_readings` CHANGE  `record_type`  `record_type` INT( 1 ) NOT NULL COMMENT '1 = weight, 2= height, 3= pressure, 4= temperature,  6= body mass index (bmi)';

-- -----------------------------------------------------------------------------
-- Adding new record types in health_readings for saving tracker slider values.
-- 10-06-2014
-- ----------------------------------------------------------------------------	
ALTER TABLE  `health_readings` CHANGE  `record_type`  `record_type` INT( 1 ) NOT NULL COMMENT '1 = weight, 2= height, 3= pressure, 4= temperature,  5 = health status. 6 = body mass index (bmi), 7 = general pain, 8 = quality of life, 9 = Sleeping habit';

-- -----------------------------------------------------------------------------
-- Chanign disease table.
-- 1-06-2014
-- ----------------------------------------------------------------------------	
ALTER TABLE diseases ADD UNIQUE (`name`);

-- --------------------------------------------------------------------------------
-- Changing 'Care Calendar Daily Digest' email template to fix alignment issues
-- 12-06-2014
-- --------------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '<br>Greetings |@username@|,<br><br>Here is the Daily Digest of team "<a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a>" for today: <br> |@team-task-list@|<br clear="all"><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>' WHERE `id` =49;

--------------------------------------------------------------------------------
-- Changed the datatype of `status` field to 'int' instead of 'tinyint' in 
-- `posts` and `comments` table to solve CakePHP issue in saving value > 1
-- 10-06-2014
--------------------------------------------------------------------------------
ALTER TABLE `posts` CHANGE `status` `status` INT( 1 ) NOT NULL DEFAULT '0' COMMENT '0: normal post,1: waiting for abuse clearance,2: blocked';
ALTER TABLE `comments` CHANGE `status` `status` INT( 1 ) NOT NULL DEFAULT '0' COMMENT '0: normal,1: waiting for abuse clearance,2: blocked';

-- -----------------------------------------------------------------------------
-- Adding 'Post Abuse Report Rejected mail to posted user' email template.
-- 12-06-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(57, 'Post Abuse Report Rejected mail to posted user', 'Patients4Life : Post Abuse Report Rejected',  '<br>Greetings |@username@|,<div><br></div>The abuse report againt your <a style="text-decoration: none; cursor: pointer;" href="|@link@|">post</a> has been rejected by the admin.<br>Admin Comment: |@admin-comment@|<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-06-12 19:10:00', '2014-06-12 19:10:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding 'Comment Abuse Report Rejected mail to commented user' email template.
-- 12-06-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(58, 'Comment Abuse Report Rejected mail to commented user', 'Patients4Life : Comment Abuse Report Rejected',  '<br>Greetings |@username@|,<div><br></div>The abuse report againt your comment "|@comment@|" in <a style="text-decoration: none; cursor: pointer;" href="|@link@|">post</a> has been rejected by the admin.<br>Admin Comment: |@admin-comment@|<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-06-12 19:15:00', '2014-06-12 19:15:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding 'Post Abuse Report Rejected mail to reported user' email template.
-- 12-06-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(59, 'Post Abuse Report Rejected mail to reported user', 'Patients4Life : Post Abuse Report Rejected',  '<br>Greetings |@username@|,<div><br></div>The abuse reported by you againt <a style="text-decoration: none; cursor: pointer;" href="|@link@|">post</a> has been rejected by the admin.<br>Admin Comment: |@admin-comment@|<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-06-12 19:45:00', '2014-06-12 19:45:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding 'Comment Abuse Report Rejected mail to reported user' email template.
-- 12-06-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(60, 'Comment Abuse Report Rejected mail to reported user', 'Patients4Life : Comment Abuse Report Rejected',  '<br>Greetings |@username@|,<div><br></div>The abuse reported by you againt the comment "|@comment@|" in <a style="text-decoration: none; cursor: pointer;" href="|@link@|">post</a> has been rejected by the admin.<br>Admin Comment: |@admin-comment@|<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-06-12 19:50:00', '2014-06-12 19:50:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding 'Abuse Reported Post Deleted mail to posted user' email template.
-- 13-06-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(61, 'Abuse Reported Post Deleted mail to posted user', 'Patients4Life : Abuse Reported Post Deleted',  '<br>Greetings |@username@|,<div><br></div>Your post which was reported as abuse has been deleted by the admin.<br>Admin Comment: |@admin-comment@|<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-06-13 09:50:00', '2014-06-13 09:50:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding 'Abuse Reported Comment Deleted mail to commented user' email template.
-- 13-06-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(62, 'Abuse Reported Comment Deleted mail to commented user', 'Patients4Life : Abuse Reported Comment Deleted',  '<br>Greetings |@username@|,<div><br></div>Your comment "|@comment@|" which was reported as abuse has been deleted by the admin.<br>Admin Comment: |@admin-comment@|<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-06-13 13:35:00', '2014-06-13 13:35:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding 'Abuse Reported Post Deleted mail to reported user' email template.
-- 13-06-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(63, 'Abuse Reported Post Deleted mail to reported user', 'Patients4Life : Abuse Reported Post Deleted',  '<br>Greetings |@username@|,<div><br></div>The post which you reported as abuse has been deleted by the admin.<br>Admin Comment: |@admin-comment@|<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-06-13 13:45:00', '2014-06-13 13:45:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding 'Abuse Reported Comment Deleted mail to reported user' email template.
-- 13-06-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(64, 'Abuse Reported Comment Deleted mail to reported user', 'Patients4Life : Abuse Reported Comment Deleted',  '<br>Greetings |@username@|,<div><br></div>The comment "|@comment@|" which you reported as abuse has been deleted by the admin.<br>Admin Comment: |@admin-comment@|<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-06-13 14:00:00', '2014-06-13 14:00:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Updating email template for Team Approval Reminder
-- 13-06-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET  `template_body` = '<br>Greetings |@username@|,<br><br>|@name@| has created a team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a> to support you |@weekcount@| week(s) ago. You have not responded yet. Please click <a style="text-decoration: none; cursor: pointer;" href="|@link@|">here</a> to respond.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>' WHERE  `email_templates`.`id` =53;

-- -----------------------------------------------------------------------------
-- Updating email template for Role Promotion Reminder
-- 13-06-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET  `template_body` = '<br>Greetings |@username@|,<br><br>|@name@| has invited you to become the |@role@| of team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a> |@weekcount@| week(s) ago. You have not responded yet. Please click <a style="text-decoration: none; cursor: pointer;" href="|@link@|">here</a> to respond.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>' WHERE  `email_templates`.`id` =54;
-- -----------------------------------------------------------------------------
-- Updating email template for Team Invitation Reminder
-- 13-06-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET  `template_body` = '<br>Greetings |@username@|,<br><br>You had been invited by |@name@| to join the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a> |@weekcount@| week(s) ago. You have not responded yet. Please click <a style="text-decoration: none; cursor: pointer;" href="|@link@|">here</a> to respond.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>' WHERE  `email_templates`.`id` =45;

-- -----------------------------------------------------------------------------
-- Adding `has_anonymous_permission` field in `users` table
-- 16-06-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `users` ADD `has_anonymous_permission` TINYINT( 1 ) NOT NULL DEFAULT '1' COMMENT '1: Yes, 0: No';

-- -----------------------------------------------------------------------------
-- Added 'register' `activity_type` in `notifications` table
-- 16-06-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM( 'invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'decline_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change', 'health_status_change', 'create_team', 'team_task_reminder', 'team_role_approved', 'team_role_declined', 'team_approved', 'team_declined', 'team_role_invitation', 'friend_request_approved', 'register' ) NOT NULL;

-- ---------------------------------------------------------------------------------------------
-- Made `object_type`, `sender_id`, `object_owner_id` fields in `notifications` table nullable
-- 16-06-2014
-- ---------------------------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `object_type` `object_type` ENUM( 'event', 'community', 'profile', 'post', 'poll_post', 'team' ) NULL DEFAULT NULL;
ALTER TABLE `notifications` CHANGE `sender_id` `sender_id` INT( 11 ) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `notifications` CHANGE `object_owner_id` `object_owner_id` INT( 11 ) UNSIGNED NULL DEFAULT NULL;

-- -----------------------------------------------------------------------------
-- Modified 'Account Activation' email template to add expiry info
-- 17-06-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '<table width="578" align="center" style="table-layout: fixed;">\r\n<tbody>\r\n<tr>\r\n<td><h2 style="font-size: 30px; margin-top: 30px; margin-left: 0px; margin-bottom: 25px; margin-right: 0px; font-weight: normal; font-family: '''' Open Sans '''', sans-serif; color: #000;">Account Activation</h2></td>\r\n</tr>\r\n<tr>\r\n<td style="padding-bottom: 10px; font-size: 14px;">Greetings |@username@|,<br><br></td>\r\n</tr>\r\n<tr>\r\n<td style="padding-bottom: 20px; font-size: 14px;">Thank you for registering an account with Patients4Life. In order to use your account fully, please complete your registration by activating your account.\r\n<br /><br />Please note that the activation link will expire in 24 hours.</td>\r\n</tr>\r\n<tr>\r\n<td align="center" style="padding-bottom: 30px; ">\r\n<a style="border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; cursor: pointer; font-family: '''', ''Open Sans'', '''', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; border: 1px solid rgb(246, 134, 31); font-size: 18px;" href="|@link@|">Activate\r\nAccount<img alt="arrow" src="http://patients4life.qburst.com/theme/App/img/email_arrow.png" style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;">\r\n</a>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="padding-bottom: 5px; font-size:13px;">If you are not able to click the above link, please copy and paste the link below in your preferred browser.</td>\r\n</tr>\r\n<tr>\r\n<td style="padding-bottom: 25px; word-wrap: break-word;"><a style="text-decoration: none; cursor: pointer; color: rgb(60, 156, 215); font-size: 13px;" href="|@link@|">|@link@|</a></td>\r\n</tr>\r\n<tr>\r\n<td style="font-size: 14px; padding-bottom:5px;">Thanks,</td>\r\n</tr>\r\n<tr><td style="font-size: 14px;">Patients4Life Team</td>\r\n</tr>\r\n</tbody>\r\n</table>' WHERE `id` = 2;

-- -----------------------------------------------------------------------------
-- Added 'Account Activation Reminder' email template
-- 17-06-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(65, 'Account Activation Reminder Mail', 'Patients4Life : Account Activation Reminder', '<table width="578" align="center" style="table-layout: fixed;">\r\n<tbody>\r\n<tr>\r\n<td><h2 style="font-size: 30px; margin-top: 30px; margin-left: 0px; margin-bottom: 25px; margin-right: 0px; font-weight: normal; font-family: '''' Open Sans '''', sans-serif; color: #000;">Account Activation Reminder</h2></td>\r\n</tr>\r\n<tr>\r\n<td style="padding-bottom: 10px; font-size: 14px;">Greetings |@username@|,<br><br></td>\r\n</tr>\r\n<tr>\r\n<td style="padding-bottom: 20px; font-size: 14px;">Thank you for registering an account with Patients4Life. Seems like you have not activated your account yet. In order to use your account fully, please complete your registration by activating your account.\r\n<br /><br />Please note that the activation link will expire in 24 hours.</td>\r\n</tr>\r\n<tr>\r\n<td align="center" style="padding-bottom: 30px; ">\r\n<a style="border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; cursor: pointer; font-family: '''', ''Open Sans'', '''', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; border: 1px solid rgb(246, 134, 31); font-size: 18px;" href="|@link@|">Activate\r\nAccount<img alt="arrow" src="http://patients4life.qburst.com/theme/App/img/email_arrow.png" style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;">\r\n</a>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style="padding-bottom: 5px; font-size:13px;">If you are not able to click the above link, please copy and paste the link below in your preferred browser.</td>\r\n</tr>\r\n<tr>\r\n<td style="padding-bottom: 25px; word-wrap: break-word;"><a style="text-decoration: none; cursor: pointer; color: rgb(60, 156, 215); font-size: 13px;" href="|@link@|">|@link@|</a></td>\r\n</tr>\r\n<tr>\r\n<td style="font-size: 14px; padding-bottom:5px;">Thanks,</td>\r\n</tr>\r\n<tr><td style="font-size: 14px;">Patients4Life Team</td>\r\n</tr>\r\n</tbody>\r\n</table>', '2014-06-17 12:00:00', '2014-06-17 12:00:00', 'admin', '1');

-- -----------------------------------------------------------------------------
-- Modified 'Team Patient Health State Changed Notification' email template
-- 17-06-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '<br>Greetings |@username@|,<br><br>The patient "|@name@|" of the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a> has changed the health status |@healthStatusText@|.<br><div><br></div><div>Thanks,</div><div>|@site-name@| Team</div>' WHERE `id` = 40;

-- -----------------------------------------------------------------------------
-- Added `blocked_users` field in `users` table
-- 18-06-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `users` ADD `blocked_users` TEXT NULL DEFAULT NULL COMMENT 'json';

-- -----------------------------------------------------------------------------
-- Updating states and countries table.
-- Adding some corrections to the names.
-- 18-06-2014
-- -----------------------------------------------------------------------------
UPDATE `countries` SET `short_name` = 'Laos' WHERE `id` =120;

UPDATE `states` SET `description` = 'Altayskiy kray' WHERE `id` =2685;

UPDATE `states` SET `description` = 'Primorsky Krai' WHERE `id` =2739;​

UPDATE `states` SET `description` = 'As Suwayda' WHERE `id` =3109;​​

UPDATE  `states` SET  `description` =  "Kyivs'ka oblast" WHERE  `id` =3480;

-- -----------------------------------------------------------------------------
-- Add privacy settings to team.
-- 19-06-2014
-- -----------------------------------------------------------------------------
ALTER TABLE  `teams` ADD  `privacy` TINYINT( 1 ) NOT NULL DEFAULT  '2' COMMENT  '1 = Public, 2 = Private, 3 = Private to public';

-- -----------------------------------------------------------------------------
-- Added 'team_privacy_change', and 'team_privacy_change_request' `activity_type` in `notifications` table
-- 20-06-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM( 'invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'decline_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change', 'health_status_change', 'create_team', 'team_task_reminder', 'team_role_approved', 'team_role_declined', 'team_approved', 'team_declined', 'team_role_invitation', 'friend_request_approved', 'register', 'team_privacy_change', 'team_privacy_change_request') NOT NULL;

-- -----------------------------------------------------------------------------
-- Adding 'Team Privacy Change' email template.
-- 20-06-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(66, 'Team Privacy Change', 'Patients4Life : Team: |@team-name@| Privacy Changed', '<br>Greetings |@username@|,<br><br>"|@name@|" has changed the privacy of the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a> from |@old-privacy@| to |@new-privacy@|.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-06-20 11:00:00', '2014-06-20 11:00:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding 'Team Privacy Change Request' email template.
-- 20-06-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(67, 'Team Privacy Change Request', 'Patients4Life : Team: |@team-name@| Privacy Change Request', '<br>Greetings |@username@|,<br><br>"|@name@|" has requested to change the privacy of the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a> from |@old-privacy@| to |@new-privacy@|. Click <a style="text-decoration: none; cursor: pointer;" href="|@link@|">here</a> to respond.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-06-20 11:00:00', '2014-06-20 11:00:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Changed the datatype of `privacy` field to 'int' instead of 'tinyint' in 
-- `teams` table to solve CakePHP issue in returning the value
-- 20-06-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `teams` CHANGE `privacy` `privacy` INT( 1 ) NOT NULL DEFAULT '2' COMMENT '1 = Public, 2 = Private, 3 = Private to public';

-- -----------------------------------------------------------------------------
-- Spelling correction 'againt' -> 'against'
-- 20-06-2014
-- ----------------------------------------------------------------------------	
UPDATE `email_templates` SET `template_body` = '<br>Greetings |@username@|,<div><br></div>The abuse report against your <a style="text-decoration: none; cursor: pointer;" href="|@link@|">post</a> has been rejected by the admin.<br>Admin Comment: |@admin-comment@|<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>' WHERE `id` = 57;
UPDATE `email_templates` SET `template_body` = '<br>Greetings |@username@|,<div><br></div>The abuse report against your comment "|@comment@|" in <a style="text-decoration: none; cursor: pointer;" href="|@link@|">post</a> has been rejected by the admin.<br>Admin Comment: |@admin-comment@|<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>' WHERE `id` = 58;
UPDATE `email_templates` SET `template_body` = '<br>Greetings |@username@|,<div><br></div>The abuse reported by you against <a style="text-decoration: none; cursor: pointer;" href="|@link@|">post</a> has been rejected by the admin.<br>Admin Comment: |@admin-comment@|<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>' WHERE `id` = 59;
UPDATE `email_templates` SET `template_body` = '<br>Greetings |@username@|,<div><br></div>The abuse reported by you against the comment "|@comment@|" in <a style="text-decoration: none; cursor: pointer;" href="|@link@|">post</a> has been rejected by the admin.<br>Admin Comment: |@admin-comment@|<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>' WHERE `id` = 60;

-- -----------------------------------------------------------------------------
-- Added 'team_privacy_change_request_rejected' `activity_type` in `notifications` table
-- 20-06-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM( 'invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'decline_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change', 'health_status_change', 'create_team', 'team_task_reminder', 'team_role_approved', 'team_role_declined', 'team_approved', 'team_declined', 'team_role_invitation', 'friend_request_approved', 'register', 'team_privacy_change', 'team_privacy_change_request', 'team_privacy_change_request_rejected') NOT NULL;

-- -----------------------------------------------------------------------------
-- Adding 'Team Privacy Change Request Rejected' email template.
-- 20-06-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(68, 'Team Privacy Change Request Rejected', 'Patients4Life : Team: |@team-name@| Privacy Change Request Rejected', '<br>Greetings |@username@|,<br><br>"|@name@|" has rejected the request to change the privacy of the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a> from |@old-privacy@| to |@new-privacy@|.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-06-20 13:30:00', '2014-06-20 13:30:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Add privacy settings to team.
-- 19-06-2014
-- -----------------------------------------------------------------------------
ALTER TABLE  `teams` CHANGE  `privacy`  `privacy` TINYINT( 2 ) NOT NULL DEFAULT  '2' COMMENT  '1 = Public, 2 = Private, 3 = Private to public';


ALTER TABLE  `teams` ADD  `privacy_requester_id` INT( 11 ) NOT NULL;

-- -----------------------------------------------------------------------------
-- Adding 'privacy_change' post_type to posts
-- 23-06-2014
-- -----------------------------------------------------------------------------
ALTER TABLE  `posts` CHANGE  `post_type`  `post_type` ENUM(  'text',  'link',  'video',  'image',  'poll',  'community',  'event',  'health',  'privacy_change' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT  'text';


-- -----------------------------------------------------------------------------
-- Solving the issue with the large muber of image upload not shown in the posts
-- 24-06-2014
-- -----------------------------------------------------------------------------
ALTER TABLE posts DROP INDEX fk_posts_post_type_id;
ALTER TABLE  `posts` CHANGE  `post_type_id`  `post_type_id` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'comma separated list of photo id, video id or poll id';
ALTER TABLE   `posts` ADD INDEX  `fk_posts_post_type_id` (  `posted_in` );

-- -----------------------------------------------------------------------------
-- Added 'team_join_request' `activity_type` in `notifications` table
-- 01-07-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM('invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'decline_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change', 'health_status_change', 'create_team', 'team_task_reminder', 'team_role_approved', 'team_role_declined', 'team_approved', 'team_declined', 'team_role_invitation', 'friend_request_approved', 'register', 'team_privacy_change', 'team_privacy_change_request', 'team_privacy_change_request_rejected', 'team_join_request') NOT NULL;

-- -----------------------------------------------------------------------------
-- Adding 'Team Join Request' email template.
-- 02-07-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(69, 'Team Join Request', 'Patients4Life : Team: |@team-name@| Join Request', '<br>Greetings |@username@|,<br><br>"|@name@|" has requested to join the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a>. Click <a style="text-decoration: none; cursor: pointer;" href="|@link@|/members">here</a> to respond.<br><div><br></div><div>Thanks,</div><div>Patients4Life Team</div>', '2014-07-02 09:20:00', '2014-07-02 09:20:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding unique to username field of user
-- 2-07-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `users` ADD UNIQUE (
`username`
);

-- -----------------------------------------------------------------------------
-- Added 'accept_team_join_request', and 'decline_team_join_request' 
-- `activity_type` in `notifications` table
-- 02-07-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM('invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'decline_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change', 'health_status_change', 'create_team', 'team_task_reminder', 'team_role_approved', 'team_role_declined', 'team_approved', 'team_declined', 'team_role_invitation', 'friend_request_approved', 'register', 'team_privacy_change', 'team_privacy_change_request', 'team_privacy_change_request_rejected', 'team_join_request', 'accept_team_join_request', 'decline_team_join_request' ) NOT NULL;

-- -----------------------------------------------------------------------------
-- Adding 'Team Join Request Accepted' email template.
-- 02-07-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(70, 'Team Join Request Accepted', '|@site-name@| : Team: |@team-name@| Join Request Accepted', '<br>Greetings |@username@|,<br><br>"|@name@|" has accepted your request to join the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a>.<br><div><br></div><div>Thanks,</div><div>|@site-name@| Team</div>', '2014-07-02 11:30:00', '2014-07-02 11:30:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding 'Team Join Request Declined' email template.
-- 02-07-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(71, 'Team Join Request Declined', '|@site-name@| : Team: |@team-name@| Join Request Declined', '<br>Greetings |@username@|,<br><br>"|@name@|" has declined your request to join the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a>.<br><div><br></div><div>Thanks,</div><div>|@site-name@| Team</div>', '2014-07-02 11:50:00', '2014-07-02 11:50:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Replace 'Patients4Life' with '|@site-name@|' in email templates.
-- 02-07-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET  
`template_body` = REPLACE(`template_body`, 'Patients4Life', '|@site-name@|'),
`template_subject` = REPLACE(`template_subject`, 'Patients4Life', '|@site-name@|');


-- Truncate 'pain tracker' table - As we updated the body image, the positions has been changed and hence we have to clear all teh old data.
-- 02-07-2014
-- -----------------------------------------------------------------------------
TRUNCATE TABLE  `pain_trackers`;

-----------------------------------
--- Adding index to fields
--- 03-07-2014
-----------------------------------

ALTER TABLE `diseases` ADD INDEX ( `name` );
ALTER TABLE `users` ADD INDEX ( `username`,  `email`, `first_name`, `last_name`);
ALTER TABLE `communities` ADD INDEX ( `name`, `description` );
ALTER TABLE `cities` ADD INDEX ( `description` );
ALTER TABLE `states` ADD INDEX ( `description` );
ALTER TABLE `communities` ADD INDEX ( `zip` );
ALTER TABLE `countries` ADD INDEX ( `short_name`, `iso2`, `long_name`, `iso3`, `calling_code` ); 

-----------------------------------
--- Adding `feeling_popup_datetime` field to user table to track feeling popup for
--- other users
--- 04-07-2014
-----------------------------------
ALTER TABLE `users` ADD `feeling_popup_datetime` DATETIME NULL DEFAULT NULL;

-- -----------------------------------------------------------------------------
-- Adding `recommended_users` field in `notification_settings` table.
-- 03-07-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notification_settings` ADD `recommended_users` LONGTEXT NOT NULL;

-- -----------------------------------------------------------------------------
-- Adding 'Friend Recommendation' email template.
-- 03-07-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(72, 'Friend Recommendation', 'Do you know |@recommended_names@|?', '<div><br /></div>Greetings |@username@|,<div style="color: #252525; font-family: ''Open Sans'', sans-serif; font-size: 20px; line-height: normal; padding-top: 20px;">Do you know |@recommended_names@|?<br></div>\r\n<div style="padding-top: 20px; padding-bottom: 20px; color: #444444; font-family: ''Open Sans'', sans-serif; font-size: 14px; line-height: normal;">\r\nHere are some people you may know on <strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">|@site-name@|</strong>. Connect with friends, family, and care givers to see their updates, photos and help them manage their lives and build their support community.</div>\r\n|@friend_recommendation_email_body@|\r\n\r\n<div style="border-top: 1px solid #EBEBEB; padding-top: 40px; text-align: center;">\r\n	<a href="|@link@|" style=" border-radius: 4px; cursor: default; font-family: ''Open Sans'', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 28px 6px 12px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; border: 1px solid rgb(246, 134, 31); font-size: 18px;">Find More Friends</a></div>', '2014-07-03 16:00:00', '2014-02-04 12:20:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding `recommend_friends_frequency`, and `last_recommended_datetime` fields 
-- in `notification_settings` table.
-- 04-07-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notification_settings` 
ADD `recommend_friends_frequency` ENUM('daily', 'weekly', 'monthly', 'yearly') NOT NULL DEFAULT 'weekly',
ADD `last_recommended_datetime` DATETIME NULL,
ADD `frequency_changed_datetime` DATETIME NULL;

-- -----------------------------------------------------------------------------
-- Truncate 'pain tracker' table.
-- 02-07-2014
-- -----------------------------------------------------------------------------
TRUNCATE TABLE  `pain_trackers`;


-- -----------------------------------------------------------------------------
-- Adding cover images to events, communities table
-- 03-07-2014
-- -----------------------------------------------------------------------------
ALTER TABLE  `events` ADD  `cover_images` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'comma separated value',
ADD  `is_cover_slideshow_enabled` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '1: enabled, 0:disabled';

ALTER TABLE  `communities` ADD  `cover_images` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'comma separated value',
ADD  `is_cover_slideshow_enabled` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '1: enabled, 0:disabled';

ALTER TABLE  `users` ADD  `cover_images` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'comma separated value',
ADD  `is_cover_slideshow_enabled` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '1: enabled, 0:disabled';

-- -----------------------------------------------------------------------------
-- Adding unique constraint to email field in user table
-- 08-07-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `users` ADD UNIQUE (`email`);

-- -----------------------------------------------------------------------------
-- Adding unique constraint to user_id field in notification_settings table
-- 08-07-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notification_settings` ADD UNIQUE (`user_id`);


-- --------------------------------------------------------
--
-- Table structure for table `following_pages`
-- 10-07-2014
-- --------------------------------------------
CREATE TABLE IF NOT EXISTS `following_pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(1) NOT NULL COMMENT '1 = Disease,',
  `page_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `notification` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 = Off, 1 = On',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `following_pages` ADD INDEX ( `user_id` );

ALTER TABLE `following_pages` ADD FOREIGN KEY ( `user_id` ) REFERENCES `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-- -----------------------------------------------------------------------------
-- Added `medication_schedules` table
-- 16-07-2014
-- -----------------------------------------------------------------------------
CREATE TABLE `medication_schedules` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`treatment_id` INT(11) UNSIGNED NOT NULL,
	`user_id` INT(11) UNSIGNED NOT NULL,
	`indication` VARCHAR(100) NULL,
    `dosage` VARCHAR( 10 ) NOT NULL,
    `dosage_unit` VARCHAR( 10 ) NOT NULL,
	`form` INT NULL,
	`amount` VARCHAR(10) NOT NULL,
	`route` INT NULL,
	`additional_instructions` VARCHAR(100) NULL,
	`prescribed_by` VARCHAR(100) NULL,
	`rrule` VARCHAR(200) NULL,
	`start_date` DATETIME  NULL,
	`start_date_json` VARCHAR( 50 ) NULL,
	`end_date` DATETIME NULL,
	`created` datetime NOT NULL,
	`modified` datetime NOT NULL,
	`is_deleted` TINYINT( 1 ) NOT NULL DEFAULT '0',
	 PRIMARY KEY (`id`),
CONSTRAINT `fk_medication_schedules_treatment`
    FOREIGN KEY (`treatment_id`)
    REFERENCES `treatments` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
CONSTRAINT `fk_medication_schedules_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB;

-- ---------------------------------------------
--
-- `following_pages` change type field to tinyint and
-- added followers_count in disease table.
-- 15-07-2014
-- --------------------------------------------
ALTER TABLE `following_pages` CHANGE `type` `type` TINYINT( 1 ) NOT NULL COMMENT '1 = Disease, 2 = Users';

ALTER TABLE `diseases` ADD `followers_count` INT( 11 ) NOT NULL DEFAULT '0' AFTER `survey_id`;

-- ---------------------------------------------
--
-- Adding disease notification type
-- 
-- 16-07-2014
-- --------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_in_type` `activity_in_type` ENUM( 'event', 'community', 'profile', 'disease' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `notifications` CHANGE `object_type` `object_type` ENUM( 'event', 'community', 'profile', 'post', 'poll_post', 'team', 'disease' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;


-- ---------------------------------------------
--
-- Changed type to size tinyint 2 , since tiny int(1) considered as boolean
-- 
-- 16-07-2014
-- --------------------------------------------
ALTER TABLE `following_pages` CHANGE `type` `type` TINYINT( 2 ) NOT NULL COMMENT '1 = Disease, 2 = Users';

-- -----------------------------------------------------------------------------
-- Adding medication reminder email template.
-- 16-07-2014
-- ----------------------------------------------------------------------------	
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(73, 'Medication Reminder', '|@site-name@| : Medication Reminder for |@time@|','<br>Greetings |@username@|,<br><br>Please take the following medications at "|@time@|" today:<br>|@medication_reminder_email_body@|<br><div><br></div><div>Thanks,</div><div>|@site-name@| Team</div>', '2014-07-16 21:55:00', '2014-07-16 21:55:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Added 'medication_reminder' `activity_type` in `notifications` table
-- 17-07-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM('invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'decline_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change', 'health_status_change', 'create_team', 'team_task_reminder', 'team_role_approved', 'team_role_declined', 'team_approved', 'team_declined', 'team_role_invitation', 'friend_request_approved', 'register', 'team_privacy_change', 'team_privacy_change_request', 'team_privacy_change_request_rejected', 'team_join_request', 'accept_team_join_request', 'decline_team_join_request', 'medication_reminder') NOT NULL;

-- -----------------------------------------------------------------------------
-- Changing medication reminder email template.
-- 17-07-2014
-- ----------------------------------------------------------------------------	
UPDATE `email_templates` SET `template_body` = '<br>Greetings |@username@|,<br><br>Please take the following medications at "|@time@|" today: <br>|@medication_reminder_email_body@|<br clear="all"><div><br></div><div>Thanks,</div><div>|@site-name@| Team</div>' WHERE `id` =73;

-- -----------------------------------------------------------------------------
-- Added `reminder_stopped_date` field in `medication_schedules` table
-- 18-07-2014
-- ----------------------------------------------------------------------------	
ALTER TABLE `medication_schedules` ADD `reminder_stopped_date` DATETIME NULL;

-- -----------------------------------------------------------------------------
-- Added `other_profile` type in activity_in_type
-- 22-07-2014
-- ----------------------------------------------------------------------------	
ALTER TABLE `notifications` CHANGE `activity_in_type` `activity_in_type` ENUM( 'event', 'community', 'profile', 'disease', 'other_profile' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `following_pages` CHANGE `type` `type` TINYINT( 2 ) NOT NULL COMMENT '1 = Disease, 2 = Users, 3 = Event, 4 = Community';

-- -----------------------------------------------------------------------------
-- Updating existing posts content to remove empty health_status_comment from JSON
-- 29-07-2014
-- -----------------------------------------------------------------------------
UPDATE `posts` SET content = REPLACE(content, ',"health_status_comment":""', '');

-- --------------------------------------------------------
-- Table structure for table `prelaunch_users`
-- 30-07-2014
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `prelaunch_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- -----------------------------------------------------------------------------
-- Added `searchable_by` field in `users` table
-- 31-07-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `users` ADD `searchable_by` SMALLINT( 1 ) NOT NULL DEFAULT '3' COMMENT '1:self, 2:friends, 3:public';

-- --------------------------------------------------------
--
-- Table structure for table `hashtags`
-- 1-8-2014 
-----------------------------------------------------------

DROP TABLE IF EXISTS `hashtags`;
CREATE TABLE IF NOT EXISTS `hashtags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(100) NOT NULL,
  `total_posts` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


ALTER TABLE `posts` ADD `hashtag_ids` TEXT NOT NULL COMMENT 'comma seperated id';

-- -----------------------------------------------------------------------------
-- Adding 'question' in `post_type` field of `posts` table
-- 06-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `posts` CHANGE `post_type` `post_type` ENUM( 'text', 'link', 'video', 'image', 'poll', 'community', 'event', 'health', 'privacy_change', 'question' ) NULL DEFAULT 'text';

-- -----------------------------------------------------------------------------
-- Adding `answers` table
-- 07-08-2014
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `answers` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` INT(11) UNSIGNED NOT NULL,
  `created_by` INT(11) UNSIGNED NOT NULL,
  `created` DATETIME DEFAULT NULL,
  `modified` DATETIME DEFAULT NULL,
  `answer` TEXT,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: not anonymous, 1: anonymous',
  `ip` VARCHAR(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_answer_post_id`
        FOREIGN KEY (`post_id`)
        REFERENCES `posts` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
CONSTRAINT `fk_answer_created_by`
        FOREIGN KEY (`created_by`)
        REFERENCES `users` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------------------------------
-- Adding `answer_count` in `posts` table
-- 07-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `posts` ADD `answer_count` INT(11) NOT NULL DEFAULT '0';

-- -----------------------------------------------------------------------------
-- Added 'question' `activity_type` in `notifications` table
-- 08-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM( 'invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'decline_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change', 'health_status_change', 'create_team', 'team_task_reminder', 'team_role_approved', 'team_role_declined', 'team_approved', 'team_declined', 'team_role_invitation', 'friend_request_approved', 'register', 'team_privacy_change', 'team_privacy_change_request', 'team_privacy_change_request_rejected', 'team_join_request', 'accept_team_join_request', 'decline_team_join_request', 'medication_reminder', 'question' ) NOT NULL;

-- -----------------------------------------------------------------------------
-- Adding email template for "New Question" notification
-- 08-08-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(74, 'New Question Notification', '|@name@| asked a question', '<br>Hi |@username@|,<br><br> |@name@| asked a question "|@question@|" in "|@disease-name@|".<br><br><a style="background-color: transparent;" href="|@post_link@|">View Question</a>', '2014-08-08 11:00:00', '2014-08-08 11:00:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Adding email template for "Question Answer" notification
-- 08-08-2014
-- -----------------------------------------------------------------------------
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(75, 'Question Answer Notification', '|@name@| answered your question', 'Hi |@username@|,<br><br> |@name@| answered "|@answer@|" to your question "|@question@|" in "|@disease-name@|".<br><br><a style="background-color: transparent;" href="|@post_link@|">View Question</a><br>\r\n\r\n', '2014-08-08 12:00:00', '2014-08-08 12:00:00', '1', '1');

-- -----------------------------------------------------------------------------
-- Added field for dumping smartystreet_data
-- 08-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `cities` ADD `smartystreet_data` TEXT NOT NULL;
ALTER TABLE `posts` ADD `hashtag_ids` TEXT NOT NULL COMMENT 'comma seperated id'

-- --------------------------------------------------------
--
-- changing email template : friend request reminder
-- 8-8-2014 
-----------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '<div><br /></div>Greetings |@username@|,                 
                        <div style="color: #252525; font-family: ''Open Sans'', sans-serif; font-size: 30px; line-height: normal; padding-top: 20px;">
	|@inviter-body@| <br></div>
<div style="padding-top: 20px; padding-bottom: 20px; color: #444444; font-family: ''Open Sans'', sans-serif; font-size: 14px; line-height: normal;">
	<strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">|@site-name@|</strong> is a niche social network and life management tool for people with chronic illnesses. 
Engaging with <strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">|@site-name@|</strong> provides them a platform to help them manage their lives and build their support community.</div>
|@invitation-reminder-body@|

<div style="border-top: 1px solid #EBEBEB; padding-top: 40px; text-align: center;">
	<a href="|@link@|" style="border-radius: 4px; cursor: pointer; font-family: ''Open Sans'', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; border: 1px solid rgb(246, 134, 31); font-size: 18px;">Visit Now<img alt= "arrow" src="|@site-url@|theme/App//img//email_arrow.png" style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;" ></a></div>  ' WHERE `email_templates`.`id` = 21;

-- --------------------------------------------------------
--
-- Adding one more field to events table.
-- 6-8-2014 
-----------------------------------------------------------
ALTER TABLE  `events` ADD  `span_date` DATETIME NOT NULL AFTER  `start_date`;

-- -----------------------------------------------------------------------------
-- Changing "Team Patient Health State Changed Notification" email template
-- 11-08-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET
`template_subject` = '|@site-name@| : Health state of the patient of your team has changed',
`template_body` = '<br>Greetings |@username@|,<br><br>The patient "|@name@|" of |@team-text@| |@team-name@| has changed the health status |@healthStatusText@|.<br><div><br></div><div>Thanks,</div><div>|@site-name@| Team</div>'
WHERE `id`=40;

-- --------------------------------------------------------
--
-- Updating 'Organizer' to 'Team Lead' in Email Templates.
-- 11-8-2014 
-----------------------------------------------------------
UPDATE `email_templates` SET `template_subject` = '|@site-name@| : Accepted |@role@| role Invitation' , `template_body` = '<br>Greetings |@username@|,<br><br>|@name@| has accepted the invitation to serve as a |@role@| for the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a>.<br><div><br></div><div>Thanks,</div><div>|@site-name@| Team</div>' WHERE `email_templates`.`id` = 50;

UPDATE `email_templates` SET `template_subject` = '|@site-name@| : Declined |@role@| role Invitation' , `template_body` = '<br>Greetings |@username@|,<br><br>We are sorry to inform that |@name@| has declined the invitation to serve as a |@role@| for the team <a style="text-decoration: none; cursor: pointer;" href="|@link@|">"|@team-name@|"</a>.<br><div><br></div><div>Thanks,</div><div>|@site-name@| Team</div>' WHERE `email_templates`.`id` = 51;

UPDATE `email_templates` SET `template_name` = 'Demote from Team Lead to Member' , `template_subject` = '|@site-name@| : Removed |@role@| role from team "|@team-name@|"' , `template_body` = '<br>Greetings |@username@|,<br><br>We are sorry to inform that you have been removed as |@role@| from the team " <a style="text-decoration: none; cursor: pointer;" href="|@link@|">|@team-name@|</a>".<br><div><br></div><div>Thanks,</div><div>|@site-name@| Team</div> ' WHERE `email_templates`.`id` = 55;

-- --------------------------------------------------------
--
-- Deleting Aland Island from states table
-- 13-8-2014 
-----------------------------------------------------------
DELETE FROM `states` WHERE `states`.`id` = 3721;

-- --------------------------------------------------------
--
-- Adding sates for Aland Island Country
-- 13-8-2014 
-----------------------------------------------------------
INSERT INTO `states` (`id`, `country_id` ,`description` ,`short_description` ,`created_datetime` ,`created_by` ,`modified_datetime` ,`modified_by` ,`status_code`)
VALUES
('3721','2', 'Vardoe', 'Vardoe', NULL , NULL , NULL , NULL , NULL),
('3722','2', 'Sund', 'Sund', NULL , NULL , NULL , NULL , NULL),
('3723','2', 'Sottunga', 'Sottunga', NULL , NULL , NULL , NULL , NULL),
('3724','2', 'Saltvik', 'Saltvik', NULL , NULL , NULL , NULL , NULL),
('3725','2', 'Lumparland', 'Lumparland', NULL , NULL , NULL , NULL , NULL),
('3726','2', 'Lemland', 'Lemland', NULL , NULL , NULL , NULL , NULL),
('3727','2', 'Kumlinge', 'Kumlinge', NULL , NULL , NULL , NULL , NULL),
('3728','2', 'Koekar', 'Koekar', NULL , NULL , NULL , NULL , NULL),
('3729','2', 'Foegloe', 'Foegloe', NULL , NULL , NULL , NULL , NULL),
('3730','2', 'Braendoe', 'Braendoe', NULL , NULL , NULL , NULL , NULL),
('3731','2', 'Mariehamn', 'Mariehamn', NULL , NULL , NULL , NULL , NULL),
('3732','2', 'Jomala', 'Jomala', NULL , NULL , NULL , NULL , NULL),
('3733','2', 'Hammarland', 'Hammarland', NULL , NULL , NULL , NULL , NULL),
('3734','2', 'Geta', 'Geta', NULL , NULL , NULL , NULL , NULL),
('3735','2', 'Finstroem', 'Finstroem', NULL , NULL , NULL , NULL , NULL),
('3736','2', 'Eckeroe', 'Eckeroe', NULL , NULL , NULL , NULL , NULL),
('3737','2', 'Aland', 'Aland', NULL , NULL , NULL , NULL , NULL);


-- -----------------------------------------------------------------------------
-- Adding FK for `abuse_reports` table
-- 14-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `abuse_reports` 
CHANGE `reported_user_id` `reported_user_id` INT(11) UNSIGNED NOT NULL,
CHANGE `object_owner_id` `object_owner_id` INT(11) UNSIGNED NOT NULL,
ADD CONSTRAINT `fk_abuse_reported_user`
    FOREIGN KEY (`reported_user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_abuse_reported_object_owner`
    FOREIGN KEY (`object_owner_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Adding FK for `communities` table
-- 14-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `communities` DROP INDEX `fk_community_1`;
DELETE FROM `communities` WHERE `created_by` NOT IN (SELECT id FROM users);

ALTER TABLE `communities` 
ADD CONSTRAINT `fk_community_created_user`
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Adding FK for `photos` table
-- 14-08-2014
-- -----------------------------------------------------------------------------
DELETE FROM `photos` WHERE `user_id`=0;
ALTER TABLE `photos` 
ADD CONSTRAINT `fk_photo_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Adding FK for `community_members` table
-- 14-08-2014
-- -----------------------------------------------------------------------------
DELETE FROM `community_members` WHERE `community_id` NOT IN (SELECT id FROM communities);
ALTER TABLE `community_members` 
DROP INDEX `community_id`,
DROP INDEX `fk_community_member_1`,
DROP INDEX `fk_community_member_2`,
ADD UNIQUE `community_member` (`community_id`,`user_id`),
ADD CONSTRAINT `fk_community_member_community`
    FOREIGN KEY (`community_id`) REFERENCES `communities` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_community_member_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `community_members` CHANGE `invited_by` `invited_by` INT( 11 ) UNSIGNED NULL DEFAULT NULL;
UPDATE `community_members` SET `invited_by`=null  WHERE `invited_by`=0;
ALTER TABLE `community_members` 
ADD CONSTRAINT `fk_community_member_invited_user`
    FOREIGN KEY (`invited_by`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `community_members` DROP FOREIGN KEY `fk_community_member_invited_user`;
ALTER TABLE `community_members` DROP INDEX fk_community_member_invited_user;
ALTER TABLE `community_members` CHANGE `invited_by` `invited_by` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0';

-- -----------------------------------------------------------------------------
-- Adding new settings field for Notification sound
-- 12-3-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notification_settings` ADD `sound_settings` TINYINT DEFAULT 1 COMMENT '1 - On, 2- Off' AFTER `temp_unit`;

-- -----------------------------------------------------------------------------
-- Adding FK for `disease_symptoms` table
-- 14-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `disease_symptoms` 
CHANGE `disease_id` `disease_id` INT(11) UNSIGNED NOT NULL,
ADD CONSTRAINT `fk_disease_symptoms_disease`
    FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Adding FK for `events` table
-- 14-08-2014
-- -----------------------------------------------------------------------------
DELETE FROM `events` WHERE `created_by`=0;
DELETE FROM `events` WHERE `created_by` NOT IN (SELECT id FROM users);
ALTER TABLE `events` 
CHANGE `created_by` `created_by` INT(11) UNSIGNED NOT NULL,
ADD CONSTRAINT `fk_event_created_user`
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Adding FK for `event_members` table
-- 14-08-2014
-- -----------------------------------------------------------------------------
DELETE FROM `event_members` WHERE `event_id` NOT IN (SELECT id FROM events);
DELETE FROM `event_members` WHERE `user_id` NOT IN (SELECT id FROM users);
ALTER TABLE `event_members` 
CHANGE `event_id` `event_id` INT( 11 ) UNSIGNED NOT NULL,
CHANGE `user_id` `user_id` INT( 11 ) UNSIGNED NOT NULL,
ADD CONSTRAINT `fk_event_member_event`
    FOREIGN KEY (`event_id`) REFERENCES `events` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_event_member_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Adding FK for `my_friends` table
-- 14-08-2014
-- -----------------------------------------------------------------------------
DELETE FROM  `my_friends` WHERE (`my_id`=0) OR (`my_id` NOT IN(SELECT id FROM users));
ALTER TABLE `my_friends` 
CHANGE `my_id` `my_id` INT( 11 ) UNSIGNED NOT NULL,
ADD CONSTRAINT `fk_my_friends_user`
    FOREIGN KEY (`my_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

-------------------------------------------------------------------------------
-- Adding FK for `survey_questions` table
-- 14-08-2014
-- -----------------------------------------------------------------------------
DELETE FROM `survey_questions` WHERE `survey_id` NOT IN (SELECT id FROM surveys);
ALTER TABLE `surveys` 
CHANGE `id` `id` INT(11) UNSIGNED NOT NULL;
ALTER TABLE `survey_questions` 
CHANGE `survey_id` `survey_id` INT(11) UNSIGNED NOT NULL,
ADD CONSTRAINT `fk_survey_id`
    FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Adding FK for `survey_results` table
-- 14-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `survey_results` 
CHANGE `user_id` `user_id` INT(11) UNSIGNED NOT NULL,
ADD CONSTRAINT `fk_survey_user_id`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

--------------------------------------------------------------------------------
-- Deleting duplicate unique key from `notification_settings` table
-- 14-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notification_settings` DROP INDEX `user_id_3`;


-------------------------------------------------------------------------------
-- Adding FK 'questionid' for `survey_result` table
-- 14-08-2014
-- -----------------------------------------------------------------------------
DELETE FROM `survey_results` WHERE `question_id` NOT IN (SELECT id FROM survey_questions);
ALTER TABLE `survey_results` 
ADD CONSTRAINT `fk_survey_question_id`
    FOREIGN KEY (`question_id`) REFERENCES `survey_questions` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Adding FK for `volunteers` table
-- 14-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `volunteers` 
CHANGE `user_id` `user_id` INT(11) UNSIGNED NOT NULL,
ADD CONSTRAINT `fk_volunteer_user_id`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------
--
-- Adding forign key for tables cities.
-- 14-8-2014 
-----------------------------------------------------------
ALTER TABLE  `cities` ADD CONSTRAINT `fk_city_state` FOREIGN KEY (  `state_id` ) REFERENCES  `states` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-- --------------------------------------------------------
--
-- Adding forign key for tables health_readings .
-- 14-8-2014 
-- --------------------------------------------------------
DELETE FROM  `health_readings` WHERE  `user_id` NOT IN ( SELECT  `id` FROM  `users`);

ALTER TABLE  `health_readings` ADD INDEX (  `user_id` );

ALTER TABLE  `health_readings` ADD CONSTRAINT `fk_health_reading_user` FOREIGN KEY (  `user_id` ) REFERENCES  `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-- --------------------------------------------------------
--
-- Adding forign key for tables pain_trackers .
-- 14-8-2014 
-- --------------------------------------------------------
DELETE FROM  `pain_trackers` WHERE  `user_id` NOT IN ( SELECT  `id` FROM  `users`);

ALTER TABLE  `pain_trackers` ADD INDEX (  `user_id` );
ALTER TABLE  `pain_trackers` CHANGE  `user_id`  `user_id` INT( 11 ) UNSIGNED NOT NULL;
ALTER TABLE  `pain_trackers` ADD CONSTRAINT `fk_pain_tracker_user` FOREIGN KEY (  `user_id` ) REFERENCES  `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-- --------------------------------------------------------
--
-- Adding forign key for tables polls .
-- 14-8-2014 
-- --------------------------------------------------------
DELETE FROM  `polls` WHERE  `created_by` NOT IN ( SELECT  `id` FROM  `users`);

ALTER TABLE  `polls` ADD INDEX (  `created_by` );
ALTER TABLE  `polls` CHANGE  `created_by`  `created_by` INT( 11 ) UNSIGNED NOT NULL;
ALTER TABLE  `polls` ADD CONSTRAINT `fk_poll_created_user` FOREIGN KEY (  `created_by` ) REFERENCES  `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-- --------------------------------------------------------
--
-- Adding forign key for tables poll_choices .
-- 14-8-2014 
-- --------------------------------------------------------
DELETE FROM  `poll_choices` WHERE  `poll_id` NOT IN ( SELECT  `id` FROM  `polls`);

ALTER TABLE  `poll_choices` ADD INDEX (  `poll_id` );
ALTER TABLE  `poll_choices` ADD CONSTRAINT `fk_poll_choice_poll` FOREIGN KEY (  `poll_id` ) REFERENCES `polls` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-- --------------------------------------------------------
--
-- Adding forign key for tables poll_votes .
-- 14-8-2014 
-- --------------------------------------------------------
DELETE FROM `poll_votes` WHERE  `poll_id` NOT IN ( SELECT  `id` FROM  `polls`);

ALTER TABLE  `poll_votes` ADD INDEX (  `poll_id` );
ALTER TABLE  `poll_votes` ADD INDEX (  `user_id` );
ALTER TABLE  `poll_votes` CHANGE  `user_id`  `user_id` INT( 11 ) UNSIGNED NOT NULL;
ALTER TABLE  `poll_votes` ADD CONSTRAINT `fk_poll_vote_poll` FOREIGN KEY (  `poll_id` ) REFERENCES  `polls` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
ALTER TABLE  `poll_votes` ADD CONSTRAINT `fk_poll_vote_user` FOREIGN KEY (  `user_id` ) REFERENCES `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-- --------------------------------------------------------
--
-- Adding forign key for tables user_treatments .
-- 14-8-2014 
-- --------------------------------------------------------
DELETE FROM  `user_treatments` WHERE  `patient_disease_id` NOT IN ( SELECT  `id` FROM  `patient_diseases`);

ALTER TABLE  `patient_diseases` CHANGE  `id`  `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE  `user_treatments` ADD CONSTRAINT `fk_user_treatment_user` FOREIGN KEY (  `user_id` ) REFERENCES  `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
ALTER TABLE  `user_treatments` ADD INDEX (  `treatment_id` );
ALTER TABLE  `user_treatments` ADD INDEX (  `patient_disease_id` );
ALTER TABLE  `user_treatments` ADD CONSTRAINT `fk_user_treatment_treatment` FOREIGN KEY (  `treatment_id` ) REFERENCES  `treatments` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
ALTER TABLE  `user_treatments` ADD CONSTRAINT `fk_user_treatment_patient_disease` FOREIGN KEY (  `patient_disease_id` ) REFERENCES  `patient_diseases` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-- --------------------------------------------------------
--
-- Adding forign key for tables countries .
-- 14-8-2014 
-- --------------------------------------------------------
ALTER TABLE  `states` ADD CONSTRAINT `fk_state_country` FOREIGN KEY (  `country_id` ) REFERENCES  `countries` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-- --------------------------------------------------------
--
-- Adding forign key for tables care_calendar_events .
-- 14-8-2014 
-- --------------------------------------------------------
DELETE FROM `care_calendar_events` WHERE `event_id` NOT IN (SELECT id FROM `events`);
DELETE FROM `care_calendar_events` WHERE `assigned_to` NOT IN (SELECT id FROM `users`);

ALTER TABLE  `care_calendar_events` ADD INDEX (  `assigned_to` );
ALTER TABLE  `care_calendar_events` ADD INDEX (  `event_id` );

ALTER TABLE  `care_calendar_events` CHANGE  `event_id`  `event_id` INT( 11 ) UNSIGNED NOT NULL;
ALTER TABLE  `care_calendar_events` CHANGE  `assigned_to`  `assigned_to` INT( 11 ) UNSIGNED NOT NULL;

ALTER TABLE  `care_calendar_events` ADD CONSTRAINT `fk_care_calendar_event_event` FOREIGN KEY (  `event_id` ) REFERENCES  `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;
ALTER TABLE  `care_calendar_events` ADD CONSTRAINT `fk_care_calendar_event_created_by` FOREIGN KEY ( `assigned_to`) REFERENCES  `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;

--------------------------------------------------------------------------------
-- Adding FK for `care_giver_patients` table
-- 14-08-2014
-- -----------------------------------------------------------------------------
DELETE FROM `care_giver_patients` WHERE `care_giver_id` NOT IN (SELECT id FROM users);
ALTER TABLE `care_giver_patients` 
ADD CONSTRAINT `fk_care_giver_id`
    FOREIGN KEY (`care_giver_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

-------------------------------------------------------------------------------
-- Adding FK for `survey_results` table
-- 14-08-2014
-- -----------------------------------------------------------------------------
DELETE FROM `survey_results` WHERE `survey_id` NOT IN (SELECT id FROM surveys);
ALTER TABLE `survey_results` 
CHANGE `survey_id` `survey_id` INT(11) UNSIGNED NOT NULL,
ADD CONSTRAINT `fk_surveys_id`
    FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

-------------------------------------------------------------------------------
-- Adding FK for arrowchat tables
-- 14-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `arrowchat` CHANGE `from` `from` INT( 11 ) UNSIGNED NOT NULL ,
CHANGE `to` `to` INT( 11 ) UNSIGNED NOT NULL;

DELETE FROM arrowchat WHERE `from` NOT IN (
SELECT id
FROM users
);

DELETE FROM arrowchat WHERE `to` NOT IN (
SELECT id
FROM users
);

ALTER TABLE `arrowchat` ADD FOREIGN KEY ( `from` ) REFERENCES `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE `arrowchat` ADD FOREIGN KEY ( `to` ) REFERENCES `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
------
ALTER TABLE `arrowchat_banlist` CHANGE `ban_userid` `ban_userid` INT( 11 ) UNSIGNED NOT NULL;

ALTER TABLE `arrowchat_banlist` ADD INDEX ( `ban_userid` );

ALTER TABLE `arrowchat_banlist` ADD FOREIGN KEY ( `ban_userid` ) REFERENCES `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-------

ALTER TABLE `arrowchat_chatroom_banlist` CHANGE `user_id` `user_id` INT( 11 ) UNSIGNED NOT NULL;

ALTER TABLE `arrowchat_chatroom_banlist` ADD FOREIGN KEY ( `user_id` ) REFERENCES `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;
-------

ALTER TABLE `arrowchat_chatroom_messages` CHANGE `user_id` `user_id` INT( 11 ) UNSIGNED NOT NULL;

ALTER TABLE `arrowchat_chatroom_messages` ADD FOREIGN KEY ( `user_id` ) REFERENCES `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-------

ALTER TABLE `arrowchat_chatroom_users` CHANGE `user_id` `user_id` INT( 11 ) UNSIGNED NOT NULL;

ALTER TABLE `arrowchat_chatroom_users` ADD FOREIGN KEY ( `user_id` ) REFERENCES `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-----

ALTER TABLE `arrowchat_notifications` CHANGE `to_id` `to_id` INT( 11 ) UNSIGNED NOT NULL ,
CHANGE `author_id` `author_id` INT( 11 ) UNSIGNED NOT NULL;

----

ALTER TABLE `arrowchat_status` CHANGE `userid` `userid` INT( 11 ) UNSIGNED NOT NULL;

DELETE FROM arrowchat_status WHERE userid NOT IN (
SELECT id
FROM users
);

ALTER TABLE `arrowchat_status` ADD FOREIGN KEY ( `userid` ) REFERENCES `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-- -----------------------------------------------------------------------------
-- Adding/removing index on `communities` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `communities` 
CHANGE `country` `country` INT(11) UNSIGNED NULL,
CHANGE `state` `state` INT(11) UNSIGNED NULL,
CHANGE `city` `city` INT(11) UNSIGNED NULL,
ADD CONSTRAINT `fk_community_country`
    FOREIGN KEY (`country`) REFERENCES `countries` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE,
ADD CONSTRAINT `fk_community_state`
    FOREIGN KEY (`state`) REFERENCES `states` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE,
ADD CONSTRAINT `fk_community_city`
    FOREIGN KEY (`city`) REFERENCES `cities` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE,
DROP INDEX name,
DROP INDEX zip;

-- -----------------------------------------------------------------------------
-- Renaming indexes on `abuse_reports` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `abuse_reports` 
DROP INDEX `fk_abuse_reported_user`,
ADD INDEX `fk_abuse_reports_users_reported_user_id`(`reported_user_id`),
DROP INDEX `fk_abuse_reported_object_owner`,
ADD INDEX `fk_abuse_reports_users_object_owner_id`(`object_owner_id`);

-- -----------------------------------------------------------------------------
-- Renaming indexes on `comments` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `comments` 
DROP INDEX `fk_comments_post_id`,
ADD INDEX `fk_comments_posts_post_id`(`post_id`),
DROP INDEX `fk_comments_created_by`,
ADD INDEX `fk_comments_users_created_by`(`created_by`);

-- -----------------------------------------------------------------------------
-- Removing unwanted index, and renaming other indexes on `communities` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `communities` DROP INDEX `fk_community_2`;
ALTER TABLE `communities` 
DROP INDEX `group_search_index`,
ADD INDEX `idx_communities_name_description_zip`(`name`, `description` (255), `zip`),
DROP INDEX `fk_community_created_user`,
ADD INDEX `fk_communities_users_created_by`(`created_by`),
DROP INDEX `fk_community_country`,
ADD INDEX `fk_communities_countries_country`(`country`),
DROP INDEX `fk_community_state`,
ADD INDEX `fk_communities_states_state`(`state`),
DROP INDEX `fk_community_city`,
ADD INDEX `fk_communities_cities_city`(`city`);

-- -----------------------------------------------------------------------------
-- Renaming indexes on `community_diseases` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `community_diseases` 
DROP INDEX `fk_community_diseases_disease`,
ADD INDEX `fk_community_diseases_diseases_disease_id`(`disease_id`),
DROP INDEX `fk_community_diseases_community`,
ADD INDEX `fk_community_diseases_communities_community_id`(`community_id`);

-- -----------------------------------------------------------------------------
-- Renaming indexes on `community_members` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `community_members` 
DROP INDEX `community_member`,
DROP INDEX `fk_community_member_user`,
DROP FOREIGN KEY `fk_community_member_community`,  
DROP FOREIGN KEY `fk_community_member_user`,
ADD CONSTRAINT `fk_community_members_communities_community_id`
    FOREIGN KEY (`community_id`) REFERENCES `communities` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_community_members_users_user_id`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
ADD UNIQUE `uq_community_members_community_id_user_id` (`community_id`,`user_id`);

-- -----------------------------------------------------------------------------
-- Renaming foreign keys on `abuse_reports` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `abuse_reports`
	DROP FOREIGN KEY `fk_abuse_reported_object_owner`,  
	DROP FOREIGN KEY `fk_abuse_reported_user`,
	ADD CONSTRAINT `fk_abuse_reports_users_object_owner_id` 
		FOREIGN KEY (`object_owner_id`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `fk_abuse_reports_users_reported_user_id`
		FOREIGN KEY (`reported_user_id`) REFERENCES `users` (`id`)
		ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming foreign keys on `comments` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `comments`
	DROP FOREIGN KEY `fk_comments_created_by`,  
	DROP FOREIGN KEY `fk_comments_post_id`,
	ADD CONSTRAINT `fk_comments_users_created_by` 
		FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `fk_comments_posts_post_id`
		FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming foreign keys on `communities` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `communities`
	DROP FOREIGN KEY `fk_community_created_user`,
	DROP FOREIGN KEY `fk_community_country`,
	DROP FOREIGN KEY `fk_community_state`,
	DROP FOREIGN KEY `fk_community_city`,  
	ADD CONSTRAINT `fk_communities_users_created_by`
		FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `fk_communities_countries_country` 
		FOREIGN KEY (`country`) REFERENCES `countries` (`id`) 
		ON DELETE SET NULL ON UPDATE CASCADE,
	ADD CONSTRAINT `fk_communities_states_state` 
		FOREIGN KEY (`state`) REFERENCES `states` (`id`) 
		ON DELETE SET NULL ON UPDATE CASCADE,
	ADD CONSTRAINT `fk_communities_cities_city` 
		FOREIGN KEY (`city`) REFERENCES `cities` (`id`) 
		ON DELETE SET NULL ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming foreign keys on `community_diseases` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `community_diseases`
	DROP FOREIGN KEY `fk_community_diseases_community`,
	DROP FOREIGN KEY `fk_community_diseases_disease`,
	ADD CONSTRAINT `fk_community_diseases_communities_community_id` 
		FOREIGN KEY (`community_id`) REFERENCES `communities` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `fk_community_diseases_diseases_disease_id` 
		FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming indexes on `care_giver_patients` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `care_giver_patients` 
DROP FOREIGN KEY `fk_care_giver_id`,
ADD CONSTRAINT `fk_care_giver_patients_users_care_giver_id`
    FOREIGN KEY (`care_giver_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `care_giver_patients` 
CHANGE `country` `country` INT(11) UNSIGNED NULL,
CHANGE `state` `state` INT(11) UNSIGNED NULL,
CHANGE `city` `city` INT(11) UNSIGNED NULL,
ADD CONSTRAINT `fk_care_giver_patients_countries_country`
    FOREIGN KEY (`country`) REFERENCES `countries` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE,
ADD CONSTRAINT `fk_care_giver_patients_states_state`
    FOREIGN KEY (`state`) REFERENCES `states` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE,
ADD CONSTRAINT `fk_care_giver_patients_cities_city`
    FOREIGN KEY (`city`) REFERENCES `cities` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming indexes on `disease_symptoms` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `disease_symptoms`
  DROP FOREIGN KEY  `fk_disease_symptoms_disease`,
  ADD CONSTRAINT `fk_disease_symptoms_diseases_disease_id` 
	FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`id`) 
	ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming indexes on `events` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `events`
	DROP INDEX `name`,
	DROP FOREIGN KEY `fk_event_community`,
	DROP FOREIGN KEY `fk_event_created_user`,
	ADD INDEX `idx_events_name`(`name`),
	ADD CONSTRAINT `fk_events_communities_community_id` 
		FOREIGN KEY (`community_id`) REFERENCES `communities` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `fk_events_users_created_by` 
		FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `events` DROP INDEX `fk_event_community`,
ADD INDEX `fk_events_communities_community_id`(`community_id`);
111
-- -----------------------------------------------------------------------------
-- Renaming indexes on `event_diseases` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `event_diseases`
	DROP INDEX `fk_event_diseases_disease`,
	DROP INDEX `fk_event_diseases_event`,
	DROP FOREIGN KEY `fk_event_diseases_disease`,  
	DROP FOREIGN KEY `fk_event_diseases_event`,
	ADD CONSTRAINT `fk_event_diseases_diseases_disease_id` 
		FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `fk_event_diseases_events_event_id` 
		FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming indexes on `event_members` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `event_members` 
	DROP INDEX `unique_invited_user`,
	DROP INDEX `fk_event_member_user`,
	DROP FOREIGN KEY `fk_event_member_event`,  
	DROP FOREIGN KEY `fk_event_member_user`,
	ADD UNIQUE `uq_event_members_event_id_user_id`(`event_id`, `user_id`),
	ADD CONSTRAINT `fk_event_members_events_event_id` 
		FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `fk_event_members_users_user_id` 
		FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming indexes on `likes` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `likes`
	DROP INDEX `unique_post_user_like`,
	DROP INDEX `fk_likes_created_by`,
	DROP FOREIGN KEY `fk_likes_created_by`,  
	DROP FOREIGN KEY `fk_likes_post_id`,
	ADD UNIQUE `uq_likes_post_id_created_by` (`post_id`,`created_by`),
	ADD CONSTRAINT `fk_likes_users_created_by` 
		FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `fk_likes_posts_post_id` 
		FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming indexes on `media` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `media`
	DROP INDEX `fk_media_created_by`,  
	DROP FOREIGN KEY `fk_media_created_by`,  
	ADD CONSTRAINT `fk_media_users_created_by` 
		FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming indexes on `medication_schedules` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `medication_schedules`
	DROP INDEX `fk_medication_schedules_treatment`,
	DROP INDEX `fk_medication_schedules_user`,
	DROP FOREIGN KEY `fk_medication_schedules_treatment`,
	DROP FOREIGN KEY `fk_medication_schedules_user`,
	ADD CONSTRAINT `fk_medication_schedules_treatments_treatment_id` 
		FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `fk_medication_schedules_users_user_id` 
		FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming indexes on `my_friends` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `my_friends`
	DROP INDEX `fk_my_friends_user`,  
	DROP FOREIGN KEY `fk_my_friends_user`,
	ADD CONSTRAINT `fk_my_friends_users_my_id` 
		FOREIGN KEY (`my_id`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming indexes on `patient_diseases` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `patient_diseases`
	DROP INDEX `fk_patient_diseases_disease`,
	DROP INDEX `fk_patient_diseases_patient`,
	DROP FOREIGN KEY `fk_patient_diseases_disease`,
	DROP FOREIGN KEY `fk_patient_diseases_patient`,
	ADD CONSTRAINT `fk_patient_diseases_diseases_disease_id` 
		FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `fk_patient_diseases_users_patient_id` 
		FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming indexes on `notification_settings` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notification_settings` 
DROP INDEX `user_id`,
ADD CONSTRAINT `fk_notification_settings_users_user_id`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming indexes on `survey_questions` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `survey_questions` 
DROP FOREIGN KEY `fk_survey_id`,
ADD CONSTRAINT `fk_survey_questions_surveys_survey_id`
    FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming indexes on `survey_results` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `survey_results` 
DROP FOREIGN KEY `fk_survey_user_id`,
DROP FOREIGN KEY `fk_survey_question_id`,
DROP FOREIGN KEY `fk_surveys_id`,
ADD CONSTRAINT `fk_survey_results_users_user_id`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_survey_results_survey_questions_question_id`
    FOREIGN KEY (`question_id`) REFERENCES `survey_questions` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_survey_results_surveys_survey_id`
    FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming indexes on `volunteers` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `volunteers` 
DROP FOREIGN KEY `fk_volunteer_user_id`,
ADD CONSTRAINT `fk_volunteers_users_user_id`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming indexes on `photos` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `photos`
	DROP INDEX `fk_photo_user`,  
	DROP FOREIGN KEY `fk_photo_user`,
	ADD CONSTRAINT `fk_photos_users_user_id` 
		FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming indexes on `user_health_histories` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `user_health_histories`
	DROP INDEX `fk_user_health_histories_user`,  
	DROP FOREIGN KEY `fk_user_health_histories_user`,
	ADD CONSTRAINT `fk_user_health_histories_users_user_id` 
		FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Renaming indexes on `user_messages` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `user_messages`
	DROP INDEX `fk_user_messages_user1`,  
	DROP INDEX `fk_user_messages_user2`,  
	DROP FOREIGN KEY `fk_user_messages_user1`,
	DROP FOREIGN KEY `fk_user_messages_user2`,
	ADD CONSTRAINT `fk_user_messages_users_current_user_id` 
		FOREIGN KEY (`current_user_id`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `fk_user_messages_users_other_user_id` 
		FOREIGN KEY (`other_user_id`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;
-- -----------------------------------------------------------------------------
-- Adding FK indexes on `events` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `events`
	CHANGE `country` `country` INT(11) UNSIGNED NULL,
	CHANGE `state` `state` INT(11) UNSIGNED NULL,
	CHANGE `city` `city` INT(11) UNSIGNED NULL,
	ADD CONSTRAINT `fk_events_countries_country` 
		FOREIGN KEY (`country`) REFERENCES `countries` (`id`) 
		ON DELETE SET NULL ON UPDATE CASCADE,
	ADD CONSTRAINT `fk_events_states_state` 
		FOREIGN KEY (`state`) REFERENCES `states` (`id`) 
		ON DELETE SET NULL ON UPDATE CASCADE,
	ADD CONSTRAINT `fk_events_cities_city` 
		FOREIGN KEY (`city`) REFERENCES `cities` (`id`) 
		ON DELETE SET NULL ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------

-- dropped unwanted indexes from `arrowchat` table
-- 18-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE arrowchat DROP INDEX `read`;
ALTER TABLE arrowchat DROP INDEX `user_read`;

-- -----------------------------------------------------------------------------
-- Renaming indexes, addingn new indexes, and removing duplicate indexes on 
-- `diseases` table
-- 19-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `diseases` 
	DROP INDEX `name`,
	DROP INDEX `disease_name`,
	ADD UNIQUE `uq_diseases_name`(`name`),
	CHANGE `parent_id` `parent_id` INT(11) UNSIGNED NULL DEFAULT NULL,
	CHANGE `user_id` `user_id` INT(11) UNSIGNED NULL DEFAULT NULL,
	CHANGE `survey_id` `survey_id` INT(11) UNSIGNED NULL DEFAULT NULL;
UPDATE `diseases` SET `parent_id`=NULL WHERE `parent_id`=0;
UPDATE `diseases` SET `user_id`=NULL WHERE `user_id`=0;
UPDATE `diseases` SET `survey_id`=NULL WHERE `survey_id`=0;
UPDATE `diseases` SET `survey_id`=NULL WHERE (`survey_id` IS NOT NULL) AND (`survey_id` NOT IN (SELECT id FROM surveys));
ALTER TABLE `diseases` 
	ADD CONSTRAINT `fk_diseases_diseases_parent_id` 
		FOREIGN KEY (`parent_id`) REFERENCES `diseases` (`id`) 
		ON DELETE SET NULL ON UPDATE CASCADE,
	ADD CONSTRAINT `fk_diseases_users_user_id` 
		FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) 
		ON DELETE SET NULL ON UPDATE CASCADE,
	ADD CONSTRAINT `fk_diseases_surveys_survey_id` 
		FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`) 
		ON DELETE SET NULL ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Changing `zip` fields from varchar(20) to varchar(15)
-- 19-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `care_giver_patients` CHANGE `zip` `zip` varchar(15);
ALTER TABLE `users` CHANGE `zip` `zip` varchar(15);
ALTER TABLE `communities` CHANGE `zip` `zip` varchar(15);
ALTER TABLE `events` CHANGE `zip` `zip` varchar(15);

--
-- --------------------------------------------------------
--
-- Removing suplicated entries form cities table
-- 19-8-2014 
-- --------------------------------------------------------
DELETE FROM  `cities` WHERE  `id` = 114006;
DELETE FROM  `cities` WHERE  `id` = 114289;

-- --------------------------------------------------------
--
-- Renaming foreign key for tables cities.
-- 19-8-2014 
-----------------------------------------------------------
ALTER TABLE  `cities` 
DROP FOREIGN KEY `fk_city_state`,
ADD CONSTRAINT `fk_cities_states_state_id` FOREIGN KEY (  `state_id` ) REFERENCES  `states` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE `cities` 
DROP INDEX `state_id`,
DROP INDEX `city_name`,
DROP INDEX `description`,
ADD UNIQUE `uq_cities_state_id_city_name` (`state_id`, `description`);
-- --------------------------------------------------------
--
-- Renamingforign key for tables health_readings .
-- 19-8-2014 
-- --------------------------------------------------------
ALTER TABLE  `health_readings` 
DROP INDEX `user_id`,
DROP FOREIGN KEY `fk_health_reading_user`,
ADD CONSTRAINT `fk_health_readings_users_user_id` FOREIGN KEY (  `user_id` ) REFERENCES  `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------
--
-- Renaming foreign key for tables pain_trackers .
-- 19-8-2014 
-- --------------------------------------------------------

ALTER TABLE  `pain_trackers`
DROP INDEX `user_id`,
DROP FOREIGN KEY `fk_pain_tracker_user`,
ADD CONSTRAINT `fk_pain_trackers_users_user_id` FOREIGN KEY (  `user_id` ) REFERENCES  `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-- --------------------------------------------------------
--
-- Renaming foreign key for tables polls .
-- 19-8-2014 
-- --------------------------------------------------------
ALTER TABLE  `polls`
DROP INDEX `created_by`,
DROP FOREIGN KEY `fk_poll_created_user`,
ADD CONSTRAINT `fk_polls_users_created_by` FOREIGN KEY (  `created_by` ) REFERENCES  `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-- --------------------------------------------------------
--
-- Renaming foreign key for tables poll_choices .
-- 19-8-2014 
-- --------------------------------------------------------

ALTER TABLE  `poll_choices`
DROP INDEX `poll_id`,
DROP FOREIGN KEY `fk_poll_choice_poll`,
ADD CONSTRAINT `fk_poll_choices_polls_poll_id` FOREIGN KEY (  `poll_id` ) REFERENCES `polls` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-- --------------------------------------------------------
--
-- Renaming foreign key for tables poll_votes .
-- 19-8-2014 
-- --------------------------------------------------------
ALTER TABLE  `poll_votes`
DROP INDEX `poll_id`,
DROP INDEX `user_id`,
DROP FOREIGN KEY `fk_poll_vote_user`,
ADD CONSTRAINT `fk_poll_votes_polls_poll_id` FOREIGN KEY (  `poll_id` ) REFERENCES  `polls` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_poll_votes_users_user_id` FOREIGN KEY (  `user_id` ) REFERENCES `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-- --------------------------------------------------------
--
-- Renaming foreign key for tables user_treatments .
-- 19-8-2014 
-- --------------------------------------------------------

ALTER TABLE  `user_treatments` DROP FOREIGN KEY  `fk_user_treatment_user` ;

ALTER TABLE  `user_treatments` DROP FOREIGN KEY  `fk_user_treatment_treatment` ;

ALTER TABLE  `user_treatments` DROP FOREIGN KEY  `fk_user_treatment_patient_disease` ;

ALTER TABLE  `user_treatments` DROP INDEX `patient_disease_id`;
ALTER TABLE  `user_treatments` DROP INDEX `treatment_id`;

ALTER TABLE  `user_treatments` 
ADD CONSTRAINT `fk_user_treatments_users_user_id` FOREIGN KEY (  `user_id` ) REFERENCES  `users` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_user_treatments_treatments_treatment_id` FOREIGN KEY (  `treatment_id` ) REFERENCES  `treatments` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_user_treatments_patient_diseases_patient_disease_id` FOREIGN KEY (  `patient_disease_id` ) REFERENCES  `patient_diseases` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-- --------------------------------------------------------
--
-- Renaming foreign key for tables countries .
-- 19-8-2014 
-- --------------------------------------------------------
ALTER TABLE  `states` 
DROP FOREIGN KEY  `fk_state_country`,
ADD CONSTRAINT `fk_states_countries_country_id` FOREIGN KEY (  `country_id` ) REFERENCES  `countries` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

-- --------------------------------------------------------
--
-- Renaming foreign key for tables care_calendar_events .
-- 19-8-2014 
-- --------------------------------------------------------
ALTER TABLE  `care_calendar_events` 
DROP FOREIGN KEY  `fk_care_calendar_event_event`,
DROP FOREIGN KEY  `fk_care_calendar_event_created_by`,
DROP INDEX `assigned_to`,
DROP INDEX `event_id`,
ADD CONSTRAINT `fk_care_calendar_events_events_event_id` FOREIGN KEY (  `event_id` ) REFERENCES  `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_care_calendar_events_users_assigned_to` FOREIGN KEY ( `assigned_to`) REFERENCES  `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;

-- -----------------------------------------------------------------------------
-- Changing newsletter table to innodb and relating to user table.
-- 19-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `newsletters` ENGINE=InnoDB;
ALTER TABLE `newsletter_queue_status` ENGINE=InnoDB;

ALTER TABLE `newsletters`
CHANGE `created_by` `created_by` INT(11) UNSIGNED NULL,
CHANGE `modified_by` `modified_by` INT(11) UNSIGNED NULL,
	ADD CONSTRAINT `fk_newsletters_users_created_by`
		FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT `fk_newsletters_users_modified_by` 
		FOREIGN KEY (`modified_by`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------
--
-- Renaming foreign key for tables saved_messages .
-- 19-8-2014 
-- --------------------------------------------------------
ALTER TABLE  `saved_messages` 
DROP FOREIGN KEY  `fk_saved_messages_message_id`,
DROP FOREIGN KEY  `fk_saved_messages_saved_user`,
DROP FOREIGN KEY  `fk_saved_messages_other_user`,
ADD CONSTRAINT `fk_saved_messages_user_messages_user_message_id` FOREIGN KEY (`user_message_id`) REFERENCES `user_messages` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_saved_messages_users_saved_user_id` FOREIGN KEY (`saved_user_id`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_saved_messages_users_other_user_id` FOREIGN KEY (`other_user_id`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------
--
-- Renaming foreign key for tables teams .
-- 19-8-2014 
-- --------------------------------------------------------
ALTER TABLE teams DROP INDEX created_by_1;
ALTER TABLE teams DROP INDEX created_by_2;

ALTER TABLE `teams`
DROP FOREIGN KEY teams_ibfk_1,
DROP FOREIGN KEY teams_ibfk_2,
DROP INDEX `patient_id`,
DROP INDEX `created_by`,
ADD CONSTRAINT `fk_teams_users_patient_id` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_teams_users_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------
--
-- Renaming foreign key for tables `team_members`.
-- 19-8-2014 
-- --------------------------------------------------------
ALTER TABLE `team_members`
DROP FOREIGN KEY `team_members_ibfk_1`,
DROP FOREIGN KEY `team_members_ibfk_2`,
DROP FOREIGN KEY `team_members_ibfk_3`,
DROP FOREIGN KEY `team_members_ibfk_4`,
DROP INDEX `user_id`,
DROP INDEX `team_id`,
DROP INDEX `invited_by`,
DROP INDEX `role_invited_by`,
ADD CONSTRAINT `fk_team_members_users_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_team_members_teams_team_id` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_team_members_users_invited_by` FOREIGN KEY (`invited_by`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_team_members_users_role_invited_by` FOREIGN KEY (`role_invited_by`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `team_members` DROP INDEX `unique_team_member` ,
ADD UNIQUE `uq_team_members_team_id_user_id` ( `team_id` , `user_id` );

-- --------------------------------------------------------
-- Updating 'Friend Recommendation' email template.
-- Changing email template 72
-- 8-01-2014
-- ----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '<div><br /></div>Greetings |@username@|,<div style="color: #252525; font-family: ''Open Sans'', sans-serif; font-size: 20px; line-height: normal; padding-top: 20px;">Do you know |@recommended_names@|?<br></div>\r\n<div style="padding-top: 20px; padding-bottom: 20px; color: #444444; font-family: ''Open Sans'', sans-serif; font-size: 14px; line-height: normal;">\r\nHere are some people you may know on <strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">|@site-name@|</strong>. Connect with friends, family, and care givers to see their updates, photos and help them manage their lives and build their support community.</div>\r\n|@friend_recommendation_email_body@|\r\n\r\n<div style="border-top: 1px solid #EBEBEB; padding-top: 40px; text-align: center;display: inline-block;width: 100%;">\r\n	<a href="|@link@|" style=" border-radius: 4px; cursor: default; font-family: ''Open Sans'', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 28px 6px 12px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; border: 1px solid rgb(246, 134, 31); font-size: 18px;">Find More Friends</a></div>' WHERE `email_templates`.`id` = 72;

-- --------------------------------------------------------
--
-- Adding foreign key for tables `posts`.
-- 20-8-2014 
-- --------------------------------------------------------
ALTER TABLE  `posts` 
ADD CONSTRAINT `fk_posts_users_modified_by` FOREIGN KEY (`modified_by`) REFERENCES `users` (`id`) 
		ON DELETE NO ACTION ON UPDATE CASCADE;

-- --------------------------------------------------------
--
-- changing data type of longitude & latitude to decimal
-- 20-8-2014 
-- --------------------------------------------------------
ALTER TABLE `cities` 
	MODIFY latitude DECIMAL(11,7),
	MODIFY longitude DECIMAL(11,7);

-- --------------------------------------------------------
--
-- Renaming foreign key for tables `answers`, `following_pages`, `user_symptoms`
-- 21-8-2014 
-- --------------------------------------------------------
ALTER TABLE `answers`
DROP FOREIGN KEY `fk_answer_post_id`,
DROP FOREIGN KEY `fk_answer_created_by`,
DROP INDEX `fk_answer_post_id`,
DROP INDEX `fk_answer_created_by`,
ADD CONSTRAINT `fk_answers_posts_post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_answers_users_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `following_pages`
DROP FOREIGN KEY `user_id`,
DROP INDEX `user_id`,
ADD CONSTRAINT `fk_following_pages_users_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `user_symptoms`
DROP FOREIGN KEY `user_symptoms_ibfk_1`,
DROP FOREIGN KEY `user_symptoms_ibfk_2`,
DROP INDEX `user_id`,
DROP INDEX `symptom_id`,
ADD CONSTRAINT `fk_user_symptoms_users_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_user_symptoms_symptoms_symptom_id` FOREIGN KEY (`symptom_id`) REFERENCES `symptoms` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `emails_histories` DROP FOREIGN KEY `emails_histories_ibfk_1` ;

ALTER TABLE `poll_votes`
DROP FOREIGN KEY `fk_poll_vote_poll`;

-- -----------------------------------------------------------------------------
-- Adding `health_status` field in `comments` table
-- 26-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `comments` ADD `health_status` SMALLINT NULL DEFAULT NULL AFTER `comment_text`;

-- -----------------------------------------------------------------------------
-- Adding `health_status` field in `answers` table
-- 26-08-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `answers` ADD `health_status` SMALLINT NULL DEFAULT NULL AFTER `answer`;

-- -----------------------------------------------------------------------------
-- Updating email teamplate to resolve button style issue in health update reminder mail.
-- 29-08-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates`
 SET `template_body` = '
                <table width="578" align="center" style="table-layout: fixed;"><tbody><tr><td style="padding-bottom: 40px;"></td></tr>
		<tr><td style="padding-bottom: 10px; font-size: 14px;">Greetings |@username@|,</td></tr><tr><td style="padding-bottom: 10px; font-size: 14px;">How are you feeling today ?</td>
		</tr><tr><td style="padding-bottom: 10px; font-size: 14px;">Just one click is all it takes to answer. The more you use |@site-name@|, the more you’ll learn about your own health over time and provide you with better insights. </td>
		</tr><tr><td style="padding-bottom: 20px; font-size: 13px;">Please click thebutton below to update your health status.</td>
		</tr><tr><td align="center" style="padding-bottom: 30px;"><a style="border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; cursor: default; font-family: '''', ''Open Sans'', '''', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; font-size: 18px;" href="|@auto_login_url@|">Update Health Status<img alt="arrow" src="|@site-url@|theme/App//img/email_arrow.png" style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;">
                </a></td></tr><tr><td style="font-size: 14px; padding-bottom: 5px;">Thanks,</td></tr><tr><td style="font-size: 14px;">|@site-name@| Team</td>
		</tr></tbody></table>'
WHERE `id` = 22;

-- -----------------------------------------------------------------------------
-- Updating email teamplate to resolve button style issue in reminder mail.
-- 09-09-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '<div><br /></div>Greetings |@username@|,<div style="color: #252525; font-family: ''Open Sans'', sans-serif; font-size: 20px; line-height: normal; padding-top: 20px;">Do you know |@recommended_names@|?<br></div>
<div style="padding-top: 20px; padding-bottom: 20px; color: #444444; font-family: ''Open Sans'', sans-serif; font-size: 14px; line-height: normal;">
Here are some people you may know on <strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">|@site-name@|</strong>. Connect with friends, family, and care givers to see their updates, photos and help them manage their lives and build their support community.</div>
|@friend_recommendation_email_body@|

<div style="border-top: 1px solid #EBEBEB; padding-top: 40px; text-align: center;display: inline-block;width: 100%;">
	<a href="|@link@|" style=" border-radius: 4px; cursor: default; font-family: ''Open Sans'', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 28px 6px 12px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; font-size: 18px;">Find More Friends</a></div>' WHERE `email_templates`.`id` = 72;
-- -----------------------------------------------------------------------------
-- Updating email teamplate to resolve button style issue in reset password mail.
-- 10-09-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '<table width="578" align="center" style="table-layout: fixed;">
	<tbody>
		<tr>
			<td><h2 style="font-size: 30px; margin-top: 30px; margin-left: 0px; margin-bottom: 25px; margin-right: 0px; font-weight: normal; font-family: '''' Open Sans '''', sans-serif; color: #000;">Reset Password</h2></td>
		</tr>
		<tr>
			<td style="padding-bottom: 10px; font-size: 14px;">Greetings |@username@|,<br><br></td>
		</tr>
        <tr>
			<td style="padding-bottom: 10px; font-size: 14px;">You recently requested to reset your password in |@site-name@|.&nbsp;<br><br>To complete your request, please click on the button below.</td>
		</tr>
		<tr>
			<td style="padding-bottom: 20px; font-size: 14px;"><br></td>
		</tr>
		<tr>
			<td align="center" style="padding-bottom: 30px; ">
				<a  href="|@link@|" style="border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; cursor: pointer; font-family: '''', ''Open Sans'', '''', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; font-size: 18px;">Reset Password<img alt="arrow" src="|@site-url@|theme/App//img/email_arrow.png" style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;">
				</a>
			</td>
		</tr>
		<tr>
			<td style="padding-bottom: 5px; font-size:13px;">If you are not able to click the above link, please copy and paste the link below in your preferred browser.</td>
		</tr>
		<tr>
			<td style="padding-bottom: 25px; word-wrap: break-word;"><a style="text-decoration: none; cursor: pointer; color: rgb(60, 156, 215); font-size: 13px;" href="|@link@|">|@link@|</a></td>
		</tr>
		<tr>
			<td style="font-size: 14px; padding-bottom:5px;">Thank You,</td>
		</tr>
		<tr>
			<td style="font-size: 14px;">|@site-name@| Team</td>
		</tr>
	</tbody>
</table>' WHERE `email_templates`.`id` = 1;

Account Activation Mail
Welcome Mail
Message Notification
Invitation Reminder
Pending Friend Request Reminder
Account Activation Reminder Mail
-- -----------------------------------------------------------------------------
-- Updating email teamplate to resolve button style issue in Account Activation Mail.
-- 10-09-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '<table width="578" align="center" style="table-layout: fixed;">
<tbody>
<tr>
<td><h2 style="font-size: 30px; margin-top: 30px; margin-left: 0px; margin-bottom: 25px; margin-right: 0px; font-weight: normal; font-family: '''' Open Sans '''', sans-serif; color: #000;">Account Activation</h2></td>
</tr>
<tr>
<td style="padding-bottom: 10px; font-size: 14px;">Greetings |@username@|,<br><br></td>
</tr>
<tr>
<td style="padding-bottom: 20px; font-size: 14px;">Thank you for registering an account with |@site-name@|. In order to use your account fully, please complete your registration by activating your account.
<br /><br />Please note that the activation link will expire in 24 hours.</td>
</tr>
<tr>
<td align="center" style="padding-bottom: 30px; ">
<a style="border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; cursor: pointer; font-family: '''', ''Open Sans'', '''', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; font-size: 18px;" href="|@link@|">Activate
Account<img alt="arrow" src="|@site-url@|theme/App/img/email_arrow.png" style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;">
</a>
</td>
</tr>
<tr>
<td style="padding-bottom: 5px; font-size:13px;">If you are not able to click the above link, please copy and paste the link below in your preferred browser.</td>
</tr>
<tr>
<td style="padding-bottom: 25px; word-wrap: break-word;"><a style="text-decoration: none; cursor: pointer; color: rgb(60, 156, 215); font-size: 13px;" href="|@link@|">|@link@|</a></td>
</tr>
<tr>
<td style="font-size: 14px; padding-bottom:5px;">Thanks,</td>
</tr>
<tr><td style="font-size: 14px;">|@site-name@| Team</td>
</tr>
</tbody>
</table>' WHERE `email_templates`.`id` = 2;

-- -----------------------------------------------------------------------------
-- Updating email teamplate to resolve button style issue in Welcome Mail.
-- 10-09-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '  <h2 style="font-size: 24px; margin: 30px 0px 25px 0px;  font-weight: normal;">Welcome to |@site-name@| !</h2>Greetings |@username@|,<br><br>Thank you for your interest in |@site-name@| and for creating an account with us. We are pleased to have you with us on our journey forward. Please find the credentials with which you registered below:<br><div><br>Username:  |@username@|<br>Email:  |@email@|<br><br><div style="text-align:center">  <a href="|@link@|" style="border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; cursor: pointer; font-family: '''', ''Open Sans'', '''', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; font-size: 18px;">Visit Now <img alt="arrow" src="|@site-url@|theme/App//img/email_arrow.png" style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;"></a> </div> <br><p style="margin: 30px 0px 0px 0px;">Looking forward for your active participation in |@site-name@|.</p><p style="margin: 30px 0px 0px 0px;">Thank You,</p>
            <p style="margin: 1px 0px 0px 0px;">|@site-name@| Team</p>    ' WHERE `email_templates`.`id` = 3;

-- -----------------------------------------------------------------------------
-- Updating email teamplate to resolve button style issue in Message Notification.
-- 10-09-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '<table width="578" align="center" style="table-layout: fixed;">
            <tbody>
                <tr>
                    <td style="padding-bottom: 40px;"></td>
                </tr>
                <tr>
                    <td style="padding-bottom: 10px; font-size: 14px;">Greetings |@username@|,</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 10px; font-size: 14px;"><a style="text-decoration: none; cursor: pointer; color: rgb(60, 156, 215);" href="|@sender_profile_link@|">|@sender_username@|</a> wrote:</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 20px; font-size: 14px;">|@sender_message@|</td>
                </tr>
                <tr>
                    <td align="center" style="padding-bottom: 30px; ">
                        <a style="border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; cursor: pointer; font-family: '''', ''Open Sans'', '''', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; font-size: 18px;" href="|@message_link@|">View in |@site-name@|<img alt="arrow" src="|@site-url@|theme/App//img/email_arrow.png" style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;">
                        </a>
                    </td>
                </tr>		
                <tr>
                    <td style="font-size: 14px; padding-bottom:5px;">Thanks,</td>
                </tr>
                <tr>
                    <td style="font-size: 14px;">|@site-name@| Team</td>
                </tr>
            </tbody>
        </table>' WHERE `email_templates`.`id` = 18;

-- -----------------------------------------------------------------------------
-- Updating email teamplate to resolve button style issue in Invitation Reminder.
-- 10-09-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '
        <div style="color: #252525; font-family: ''Open Sans'', sans-serif; font-size: 30px; line-height: normal; padding-top: 40px;">
            |@inviter-body@| <br></div>
        <div style="padding-top: 20px; padding-bottom: 20px; color: #444444; font-family: ''Open Sans'', sans-serif; font-size: 14px; line-height: normal;">
            <strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">|@site-name@|</strong> is a niche social network and life management tool for people with chronic illnesses. 
            Engaging with <strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">|@site-name@|</strong> provides them a platform to help them manage their lives and build their support community.</div>
        |@invitation-reminder-body@|

        <div style="border-top: 1px solid #EBEBEB; padding-top: 40px; text-align: center;">
            <a href="|@link@|" style="border-radius: 4px; cursor: pointer; font-family: ''Open Sans'', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 16px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; font-size: 18px;">Join |@site-name@| <img alt="arrow" src="|@site-url@|theme/App//img/email_arrow.png" style="padding-left: 10px; vertical-align: middle; margin-bottom: 4px;" /></a></div>         ' WHERE `email_templates`.`id` = 20;

-- -----------------------------------------------------------------------------
-- Updating email teamplate to resolve button style issue in Pending Friend Request Reminder.
-- 10-09-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '<div><br /></div>Greetings |@username@|,                 
                        <div style="color: #252525; font-family: ''Open Sans'', sans-serif; font-size: 30px; line-height: normal; padding-top: 20px;">
	|@inviter-body@| <br></div>
<div style="padding-top: 20px; padding-bottom: 20px; color: #444444; font-family: ''Open Sans'', sans-serif; font-size: 14px; line-height: normal;">
	<strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">|@site-name@|</strong> is a niche social network and life management tool for people with chronic illnesses. 
Engaging with <strong style="color: rgb(69, 74, 77); font-family: ''Open Sans'', sans-serif; line-height: normal;">|@site-name@|</strong> provides them a platform to help them manage their lives and build their support community.</div>
|@invitation-reminder-body@|

<div style="border-top: 1px solid #EBEBEB; padding-top: 40px; text-align: center;">
	<a href="|@link@|" style="border-radius: 4px; cursor: pointer; font-family: ''Open Sans'', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; font-size: 18px;">Visit Now<img alt= "arrow" src="|@site-url@|theme/App//img//email_arrow.png" style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;" ></a></div>  ' WHERE `email_templates`.`id` = 21;
-- -----------------------------------------------------------------------------
-- Updating email teamplate to resolve button style issue in Account Activation Reminder Mail.
-- 10-09-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '<table width="578" align="center" style="table-layout: fixed;">
<tbody>
<tr>
<td><h2 style="font-size: 30px; margin-top: 30px; margin-left: 0px; margin-bottom: 25px; margin-right: 0px; font-weight: normal; font-family: '' Open Sans '', sans-serif; color: #000;">Account Activation Reminder</h2></td>
</tr>
<tr>
<td style="padding-bottom: 10px; font-size: 14px;">Greetings |@username@|,<br><br></td>
</tr>
<tr>
<td style="padding-bottom: 20px; font-size: 14px;">Thank you for registering an account with |@site-name@|. Seems like you have not activated your account yet. In order to use your account fully, please complete your registration by activating your account.
<br /><br />Please note that the activation link will expire in 24 hours.</td>
</tr>
<tr>
<td align="center" style="padding-bottom: 30px; ">
<a style="border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; cursor: pointer; font-family: '', 'Open Sans', '', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white;font-size: 18px;" href="|@link@|">Activate
Account<img alt="arrow" src="|@site-url@|theme/App/img/email_arrow.png" style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;">
</a>
</td>
</tr>
<tr>
<td style="padding-bottom: 5px; font-size:13px;">If you are not able to click the above link, please copy and paste the link below in your preferred browser.</td>
</tr>
<tr>
<td style="padding-bottom: 25px; word-wrap: break-word;"><a style="text-decoration: none; cursor: pointer; color: rgb(60, 156, 215); font-size: 13px;" href="|@link@|">|@link@|</a></td>
</tr>
<tr>
<td style="font-size: 14px; padding-bottom:5px;">Thanks,</td>
</tr>
<tr><td style="font-size: 14px;">|@site-name@| Team</td>
</tr>
</tbody>
</table>' 
WHERE `email_templates`.`id` = 65;

-- -----------------------------------------------------------------------------
-- Renamed `user_id` to `created_by` in `photos` table
-- 12-09-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `photos`
	DROP INDEX `fk_photos_users_user_id`,  
	DROP FOREIGN KEY `fk_photos_users_user_id`;
ALTER TABLE `photos` CHANGE `user_id` `created_by` INT( 11 ) UNSIGNED NOT NULL;
ALTER TABLE `photos` 
	ADD CONSTRAINT `fk_photos_users_created_by` 
		FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) 
		ON DELETE CASCADE ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- Added `type_id` field in `photos` table
-- 12-09-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `photos` ADD `type_id` INT( 11 ) UNSIGNED NULL DEFAULT NULL COMMENT 'event_id, community_id etc' AFTER `type`;

-- -----------------------------------------------------------------------------
-- Deleted `cover_images` field from `users`, `events`, and `communities` tables
-- 12-09-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `users` DROP `cover_images`;
ALTER TABLE `events` DROP `cover_images`;
ALTER TABLE `communities` DROP `cover_images`;
UPDATE `photos` SET `type_id`=`created_by` WHERE `type` IN (3, 4, 5) AND `created`<= '2014-09-12 09:39:23';

-- -----------------------------------------------------------------------------
-- Updating email teamplate to resolve template invisible issue at admin side.
-- 12-09-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates` SET `template_body` = '<table>  <h2 style="font-size: 24px; margin: 30px 0px 25px 0px;  font-weight: normal;">Welcome to |@site-name@| !</h2>Greetings |@username@|,<br><br>Thank you for your interest in |@site-name@| and for creating an account with us. We are pleased to have you with us on our journey forward. Please find the credentials with which you registered below:<br><div><br>Username:  |@username@|<br>Email:  |@email@|<br><br><div style="text-align:center">  <a href="|@link@|" style="border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; cursor: pointer; font-family: '''', ''Open Sans'', '''', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; font-size: 18px;">Visit Now <img alt="arrow" src="|@site-url@|theme/App//img/email_arrow.png" style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;"></a> </div> <br><p style="margin: 30px 0px 0px 0px;">Looking forward for your active participation in |@site-name@|.</p><p style="margin: 30px 0px 0px 0px;">Thank You,</p>
            <p style="margin: 1px 0px 0px 0px;">|@site-name@| Team</p>  </table>  ' WHERE `email_templates`.`id` = 3;

-- -----------------------------------------------------------------------------
-- Adding new Post type options
-- 30-10-2014
-- -----------------------------------------------------------------------------
ALTER TABLE  `posts` CHANGE  `post_type`  `post_type` ENUM(  'text',  'link',  'video',  'image',  'poll',  'community',  'event',  'health', 'privacy_change',  'question',  'blog',  'ecard' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT  'text';


ALTER TABLE  `photos` CHANGE  `type_id`  `posted_in` INT( 11 ) UNSIGNED NULL DEFAULT NULL COMMENT 'reference to the place where the post is made - eventid, community_id,user_id';
ALTER TABLE  `photos` ADD  `posted_in_type` ENUM(  'communities',  'events',  'users',  'diseases',  'team' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'where the photos is posted' AFTER  `type` ;

-- -----------------------------------------------------------------------------
-- Added 'team_request_cancel' `activity_type` in `notifications` table
-- 02-12-2014
-- -----------------------------------------------------------------------------
ALTER TABLE `notifications` CHANGE `activity_type` `activity_type` ENUM( 'invite', 'update', 'delete', 'reminder', 'community_join_request', 'post', 'like', 'comment', 'event_rsvp', 'answered_question', 'site_event', 'site_community', 'team_join_invitation', 'accept_team_join_invitation', 'decline_team_join_invitation', 'removed_from_team', 'care_request', 'care_request_change', 'health_status_change', 'create_team', 'team_task_reminder', 'team_role_approved', 'team_role_declined', 'team_approved', 'team_declined', 'team_role_invitation', 'friend_request_approved', 'register', 'team_privacy_change', 'team_privacy_change_request', 'team_privacy_change_request_rejected', 'team_join_request', 'accept_team_join_request', 'decline_team_join_request', 'medication_reminder', 'question', 'team_request_cancel' ) NOT NULL;

-- -----------------------------------------------------------------------------
-- Updating health update reminder email teamplate.
-- 15-12-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates`
 SET `template_body` = '
                <table width="578" align="center" style="table-layout: fixed;"><tbody><tr><td style="padding-bottom: 40px;"></td></tr>
		<tr><td style="padding-bottom: 10px; font-size: 14px;">Greetings |@username@|,</td></tr><tr><td style="padding-bottom: 10px; font-size: 14px;">How are you feeling today ?</td>
		</tr><tr><td style="padding-bottom: 20px; font-size: 14px;">Just one click is all it takes to answer. The more you use |@site-name@|, the more you’ll learn about your own health over time and provide you with better insights. </td>
		</tr><tr><td align="center" style="padding-bottom: 30px;"><a style="border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; cursor: default; font-family: '''', ''Open Sans'', '''', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; font-size: 18px;" href="|@auto_login_url@|">Update Health Status<img alt="arrow" src="|@site-url@|theme/App//img/email_arrow.png" style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;">
                </a></td></tr><tr><td style="font-size: 14px; padding-bottom: 5px;">Thanks,</td></tr><tr><td style="font-size: 14px;">|@site-name@| Team</td>
		</tr></tbody></table>'
WHERE `id` = 22;

-- -----------------------------------------------------------------------------
-- Updating health update reminder email teamplate.
-- 16-12-2014
-- -----------------------------------------------------------------------------
UPDATE `email_templates`
 SET `template_body` = '
                <table width="578" align="center" style="table-layout: fixed;"><tbody><tr><td style="padding-bottom: 40px;"></td></tr>
		<tr><td style="padding-bottom: 10px; font-size: 14px;">Greetings |@username@|,</td></tr><tr><td style="padding-bottom: 10px; font-size: 14px;">How are you feeling today ?</td>
		</tr><tr><td style="padding-bottom: 10px; font-size: 14px;">The more you use |@site-name@|, the more you’ll learn about your own health over time and provide you with better insights. </td>
		</tr><tr><td style="padding-bottom: 20px; font-size: 14px;">Please click the button below to update your health status.</td>
		</tr><tr><td align="center" style="padding-bottom: 30px;"><a style="border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; cursor: default; font-family: '''', ''Open Sans'', '''', sans-serif; font-weight: normal; line-height: 1.42857; padding: 6px 18px 6px 21px; text-align: center; vertical-align: middle; white-space: nowrap; text-decoration: none; margin: 0px auto; background-color: rgb(246, 134, 31); color: white; font-size: 18px;" href="|@auto_login_url@|">Update Health Status<img alt="arrow" src="|@site-url@|theme/App//img/email_arrow.png" style="padding-left: 14px; vertical-align: middle; margin-bottom: 4px;">
                </a></td></tr><tr><td style="font-size: 14px; padding-bottom: 5px;">Thanks,</td></tr><tr><td style="font-size: 14px;">|@site-name@| Team</td>
		</tr></tbody></table>'
WHERE `id` = 22;