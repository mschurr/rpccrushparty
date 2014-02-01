SET NAMES utf8;
SET CHARACTER SET utf8;
SET character_set_connection=utf8;
SET collation_connection = utf8_unicode_ci;

DROP TABLE IF EXISTS `surveys`;

CREATE TABLE `surveys` (
  `id` int(64) UNSIGNED NOT NULL auto_increment,
  `student_id` varchar(48) NOT NULL,
  `net_id` varchar(16) NOT NULL,
  `first_name` varchar(48) NOT NULL,
  `last_name` varchar(48) NOT NULL,
  `email_address` varchar(128) NOT NULL,
  `college` int(32) UNSIGNED NOT NULL,
  `gender` int(32) UNSIGNED NOT NULL,
  `year` int(32) UNSIGNED NOT NULL,
  `major` int(32) UNSIGNED NOT NULL,
  `send_results` int(32) UNSIGNED NOT NULL,
  `interested_0` int(32) UNSIGNED NOT NULL,
  `interested_1` int(32) UNSIGNED NOT NULL,
  `question_0` int(32) UNSIGNED NOT NULL,
  `question_1` int(32) UNSIGNED NOT NULL,
  `question_2` int(32) UNSIGNED NOT NULL,
  `question_3` int(32) UNSIGNED NOT NULL,
  `question_4` int(32) UNSIGNED NOT NULL,
  `question_5` int(32) UNSIGNED NOT NULL,
  `question_6` int(32) UNSIGNED NOT NULL,
  `question_7` int(32) UNSIGNED NOT NULL,
  `question_8` int(32) UNSIGNED NOT NULL,
  `question_9` int(32) UNSIGNED NOT NULL,
  `question_10` int(32) UNSIGNED NOT NULL,
  `question_11` int(32) UNSIGNED NOT NULL,
  `question_12` int(32) UNSIGNED NOT NULL,
  `question_13` int(32) UNSIGNED NOT NULL,
  `question_14` int(32) UNSIGNED NOT NULL,
  `question_15` int(32) UNSIGNED NOT NULL,
  `question_16` int(32) UNSIGNED NOT NULL,
  `question_17` int(32) UNSIGNED NOT NULL,
  `question_18` int(32) UNSIGNED NOT NULL,
  `question_19` int(32) UNSIGNED NOT NULL,
  `question_20` int(32) UNSIGNED NOT NULL,
  PRIMARY KEY  (`id`)
);