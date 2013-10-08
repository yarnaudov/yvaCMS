-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 30, 2013 at 09:29 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `yvacms`
--

-- --------------------------------------------------------

--
-- Table structure for table `ap_menus`
--

CREATE TABLE IF NOT EXISTS `ap_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_bg` varchar(500) NOT NULL,
  `title_en` varchar(500) NOT NULL,
  `alias` varchar(500) NOT NULL,
  `parent_id` int(4) DEFAULT NULL,
  `type` enum('general','general_sub','sub_action','general_sub sub_action') NOT NULL,
  `component` enum('yes','no') NOT NULL,
  `check_access` enum('yes','no') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `ap_menus`
--

INSERT INTO `ap_menus` (`id`, `title_bg`, `title_en`, `alias`, `parent_id`, `type`, `component`, `check_access`, `order`) VALUES
(1, 'Статии', 'Articles', 'articles', NULL, 'general', 'no', 'yes', 1),
(2, 'Категории', 'Categories', 'categories/articles', 1, 'sub_action', 'no', 'yes', 2),
(3, 'Потребителски полета', 'Custom fields', 'custom_fields/articles', 1, 'sub_action', 'no', 'yes', 3),
(4, 'Менюта', 'Menus', 'menus', NULL, 'general', 'no', 'yes', 2),
(5, 'Категории', 'Categories', 'categories/menus', 4, 'sub_action', 'no', 'yes', 2),
(6, 'Потребителски полета', 'Custom fields', 'custom_fields/menus', 4, 'sub_action', 'no', 'yes', 3),
(7, 'Банери', 'Banners', 'banners', NULL, 'general', 'no', 'yes', 3),
(8, 'Потребителски полета', 'Custom fields', 'custom_fields/banners', 7, 'sub_action', 'no', 'yes', 2),
(9, 'Езици', 'Languages', 'languages', NULL, 'general', 'no', 'yes', 4),
(10, 'Потребителски полета', 'Custom fields', 'custom_fields/languages', 9, 'sub_action', 'no', 'yes', 1),
(11, 'Потребители', 'Users', 'users', NULL, 'general', 'no', 'yes', 5),
(12, 'Групи', 'Groups', 'groups/users', 11, 'sub_action', 'no', 'yes', 1),
(13, 'Потребителски полета', 'Custom fields', 'custom_fields/users', 11, 'sub_action', 'no', 'yes', 2),
(14, 'Модули', 'Modules', 'modules', NULL, 'general', 'no', 'yes', 6),
(15, 'Потребителски полета', 'Custom fields', 'custom_fields/modules', 14, 'sub_action', 'no', 'yes', 1),
(16, 'Компоненти', 'Components', 'components', NULL, 'general', 'no', 'yes', 7),
(17, 'Настройки', 'Settings', 'settings', NULL, 'general', 'no', 'yes', 8),
(18, 'Основни', 'General', 'settings/general', 17, 'general_sub sub_action', 'no', 'yes', 1),
(19, 'Майл', 'Mail', 'settings/mail', 17, 'sub_action', 'no', 'yes', 2),
(20, 'Анкети', 'Polls', 'components/polls', 16, 'general_sub', 'yes', 'yes', 1),
(21, 'Галерия', 'Gallery', 'components/gallery', 16, 'general_sub', 'yes', 'yes', 2),
(22, 'Контактни форми', 'Contact forms', 'components/contact_forms', 16, 'general_sub', 'yes', 'yes', 3),
(23, 'Албуми', 'Albums', 'components/gallery/albums', 21, 'sub_action', 'no', 'no', 1),
(24, 'Снимки', 'Images', 'components/gallery/images', 21, 'sub_action', 'no', 'no', 2),
(25, 'Бързо добавяне', 'Quick add', 'components/gallery/images/quickadd', 21, 'sub_action', 'no', 'yes', 3),
(26, 'Статистики', 'Statistics', 'statistics/articles', 1, 'sub_action', 'no', 'yes', 4),
(27, 'Статистики', 'Statistics', 'statistics/banners', 7, 'sub_action', 'no', 'yes', 4);

-- --------------------------------------------------------

--
-- Table structure for table `ap_sessions`
--

CREATE TABLE IF NOT EXISTS `ap_sessions` (
  `user_id` int(4) NOT NULL,
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  UNIQUE KEY `user_id` (`user_id`),
  KEY `last_activity_idx` (`last_activity`),
  KEY `user_id_2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ap_sessions`
--

INSERT INTO `ap_sessions` (`user_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
(1, '::1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:23.0) Gecko/20100101 Firefox/23.0', 1377847461, '');

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `alias` varchar(500) NOT NULL,
  `show_in_language` int(4) DEFAULT NULL,
  `start_publishing` date DEFAULT NULL,
  `end_publishing` date DEFAULT NULL,
  `show_title` enum('yes','no') DEFAULT 'yes',
  `params` text NOT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` enum('yes','no','trash','deleted') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`show_in_language`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `alias`, `show_in_language`, `start_publishing`, `end_publishing`, `show_title`, `params`, `created_by`, `created_on`, `updated_by`, `updated_on`, `status`) VALUES
(1, 'home', NULL, NULL, NULL, 'yes', '{"show_comments":"no"}', 1, '2013-08-29 16:38:48', 1, '2013-08-30 09:19:14', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `articles_categories`
--

CREATE TABLE IF NOT EXISTS `articles_categories` (
  `article_id` int(4) NOT NULL,
  `category_id` int(4) NOT NULL,
  `order` int(4) NOT NULL,
  UNIQUE KEY `article_category` (`article_id`,`category_id`),
  KEY `article_id` (`article_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `articles_categories`
--

INSERT INTO `articles_categories` (`article_id`, `category_id`, `order`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `articles_comments`
--

CREATE TABLE IF NOT EXISTS `articles_comments` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `article_id` int(4) NOT NULL,
  `name` varchar(100) NOT NULL,
  `comment` text NOT NULL,
  `created_by` int(4) DEFAULT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `articles_comments_ibfk_1` (`article_id`),
  KEY `articles_comments_ibfk_2` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `articles_data`
--

CREATE TABLE IF NOT EXISTS `articles_data` (
  `article_id` int(4) NOT NULL,
  `language_id` int(4) NOT NULL,
  `title` varchar(500) NOT NULL,
  `text` text NOT NULL,
  `meta_keywords` varchar(1000) NOT NULL,
  `meta_description` varchar(1000) NOT NULL,
  UNIQUE KEY `article_id_language_id` (`article_id`,`language_id`),
  KEY `language_id` (`language_id`),
  KEY `article_id` (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `articles_data`
--

INSERT INTO `articles_data` (`article_id`, `language_id`, `title`, `text`, `meta_keywords`, `meta_description`) VALUES
(1, 1, 'Начало', '<p>Това е примерна статия!</p>', '', ''),
(1, 2, 'Home', '<p>This is an example article!</p>', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `articles_history`
--

CREATE TABLE IF NOT EXISTS `articles_history` (
  `article_id` int(4) NOT NULL,
  `language_id` int(4) NOT NULL,
  `alias` varchar(500) NOT NULL,
  `show_in_language` int(4) DEFAULT NULL,
  `start_publishing` date DEFAULT NULL,
  `end_publishing` date DEFAULT NULL,
  `show_title` enum('yes','no') NOT NULL,
  `params` text NOT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `title` varchar(500) NOT NULL,
  `text` text NOT NULL,
  `status` enum('yes','no','trash') NOT NULL,
  `categories` text NOT NULL,
  `custom_fields` mediumtext,
  KEY `language_id` (`show_in_language`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  KEY `article_id` (`article_id`),
  KEY `language_id_2` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `articles_history`
--

INSERT INTO `articles_history` (`article_id`, `language_id`, `alias`, `show_in_language`, `start_publishing`, `end_publishing`, `show_title`, `params`, `created_by`, `created_on`, `updated_by`, `updated_on`, `title`, `text`, `status`, `categories`, `custom_fields`) VALUES
(1, 1, 'home', NULL, NULL, NULL, 'yes', '{"show_comments":"yes"}', 1, '2013-08-29 16:38:48', 1, '2013-08-29 16:39:09', 'Начало', '<p>Това е примерна статия!</p>', 'yes', '[{"article_id":"1","category_id":"1","order":"1"}]', '[]');

-- --------------------------------------------------------

--
-- Table structure for table `articles_statistics`
--

CREATE TABLE IF NOT EXISTS `articles_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  `user_agent` varchar(500) NOT NULL,
  `user_referrer` varchar(1000) NOT NULL,
  `page_url` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=127 ;

--
-- Dumping data for table `articles_statistics`
--

INSERT INTO `articles_statistics` (`id`, `article_id`, `created_on`, `ip`, `user_agent`, `user_referrer`, `page_url`) VALUES
(1, 1, '2013-08-29 16:39:49', '::1', 'Firefox 23.0', '', 'http://localhost/yvaCMS/bg'),
(2, 1, '2013-08-29 16:49:28', '::1', 'Firefox 23.0', '', 'http://localhost/yvaCMS/bg'),
(3, 1, '2013-08-29 16:49:41', '::1', 'Firefox 23.0', '', 'http://localhost/yvaCMS/bg'),
(4, 1, '2013-08-29 16:49:45', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg', 'http://localhost/yvaCMS/bg/home'),
(5, 1, '2013-08-29 16:49:46', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(6, 1, '2013-08-29 16:51:11', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg', 'http://localhost/yvaCMS/bg/home'),
(7, 1, '2013-08-29 16:51:12', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(8, 1, '2013-08-29 16:54:15', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg', 'http://localhost/yvaCMS/bg/home'),
(9, 1, '2013-08-29 16:54:16', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(10, 1, '2013-08-29 16:54:18', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(11, 1, '2013-08-29 16:54:37', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(12, 1, '2013-08-29 16:56:36', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(13, 1, '2013-08-29 16:56:37', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(14, 1, '2013-08-30 08:14:57', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/home', 'http://localhost/yvaCMS/bg/oralno_zdrave/zaboliavania_saveti_i_lechenie'),
(15, 1, '2013-08-30 08:15:00', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/oralno_zdrave/images/audio_icon.gif'),
(16, 1, '2013-08-30 08:15:14', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(17, 1, '2013-08-30 08:15:16', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(18, 1, '2013-08-30 08:15:16', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(19, 1, '2013-08-30 08:18:04', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(20, 1, '2013-08-30 08:18:04', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(21, 1, '2013-08-30 08:18:23', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(22, 1, '2013-08-30 08:18:24', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(23, 1, '2013-08-30 08:20:12', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(24, 1, '2013-08-30 08:20:13', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(25, 1, '2013-08-30 08:20:13', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(26, 1, '2013-08-30 08:20:14', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(27, 1, '2013-08-30 08:21:30', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(28, 1, '2013-08-30 08:21:31', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(29, 1, '2013-08-30 08:23:25', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(30, 1, '2013-08-30 08:23:26', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(31, 1, '2013-08-30 08:23:26', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(32, 1, '2013-08-30 08:23:27', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(33, 1, '2013-08-30 08:26:11', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(34, 1, '2013-08-30 08:26:11', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(35, 1, '2013-08-30 08:27:24', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(36, 1, '2013-08-30 08:27:25', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(37, 1, '2013-08-30 08:27:38', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(38, 1, '2013-08-30 08:28:46', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(39, 1, '2013-08-30 08:31:28', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(40, 1, '2013-08-30 08:31:34', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(41, 1, '2013-08-30 08:31:34', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(42, 1, '2013-08-30 08:31:36', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(43, 1, '2013-08-30 08:31:38', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg'),
(44, 1, '2013-08-30 08:31:42', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(45, 1, '2013-08-30 08:31:53', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(46, 1, '2013-08-30 08:45:50', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif'),
(47, 1, '2013-08-30 08:47:24', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/en/contacts', 'http://localhost/yvaCMS/en/home'),
(48, 1, '2013-08-30 08:47:26', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif'),
(49, 1, '2013-08-30 08:47:27', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif'),
(50, 1, '2013-08-30 08:50:26', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif'),
(51, 1, '2013-08-30 08:50:38', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif'),
(52, 1, '2013-08-30 08:51:00', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif'),
(53, 1, '2013-08-30 08:55:50', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif'),
(54, 1, '2013-08-30 08:55:54', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(55, 1, '2013-08-30 08:56:12', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(56, 1, '2013-08-30 08:57:44', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(57, 1, '2013-08-30 08:57:46', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(58, 1, '2013-08-30 08:57:54', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(59, 1, '2013-08-30 08:58:27', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(60, 1, '2013-08-30 09:01:53', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/search?query=%D0%BD%D0%B0%D1%87%D0%B0', 'http://localhost/yvaCMS/bg/home'),
(61, 1, '2013-08-30 09:01:54', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(62, 1, '2013-08-30 09:02:11', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(63, 1, '2013-08-30 09:03:05', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(64, 1, '2013-08-30 09:03:07', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif'),
(65, 1, '2013-08-30 09:03:10', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(66, 1, '2013-08-30 09:04:27', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(67, 1, '2013-08-30 09:04:31', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(68, 1, '2013-08-30 09:04:32', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(69, 1, '2013-08-30 09:04:33', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(70, 1, '2013-08-30 09:09:26', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(71, 1, '2013-08-30 09:09:26', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(72, 1, '2013-08-30 09:09:30', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(73, 1, '2013-08-30 09:10:21', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(74, 1, '2013-08-30 09:11:10', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(75, 1, '2013-08-30 09:11:19', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(76, 1, '2013-08-30 09:11:21', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(77, 1, '2013-08-30 09:11:23', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/home', 'http://localhost/yvaCMS/bg/home'),
(78, 1, '2013-08-30 09:11:24', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(79, 1, '2013-08-30 09:11:29', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/home', 'http://localhost/yvaCMS/bg/home'),
(80, 1, '2013-08-30 09:11:31', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(81, 1, '2013-08-30 09:12:50', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/home', 'http://localhost/yvaCMS/bg/home'),
(82, 1, '2013-08-30 09:12:52', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(83, 1, '2013-08-30 09:14:03', '::1', 'Firefox 23.0', '', 'http://localhost/yvaCMS/bg/article:home'),
(84, 1, '2013-08-30 09:14:04', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(85, 1, '2013-08-30 09:15:15', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/article:home', 'http://localhost/yvaCMS/bg/home'),
(86, 1, '2013-08-30 09:15:16', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(87, 1, '2013-08-30 09:16:54', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/home', 'http://localhost/yvaCMS/bg/home'),
(88, 1, '2013-08-30 09:16:58', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/article:home', 'http://localhost/yvaCMS/bg/home'),
(89, 1, '2013-08-30 09:16:59', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(90, 1, '2013-08-30 09:17:43', '::1', 'Firefox 23.0', '', 'http://localhost/yvaCMS/bg/home'),
(91, 1, '2013-08-30 09:17:44', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(92, 1, '2013-08-30 09:18:14', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/home', 'http://localhost/yvaCMS/bg/home'),
(93, 1, '2013-08-30 09:18:14', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(94, 1, '2013-08-30 09:18:42', '::1', 'Firefox 23.0', '', 'http://localhost/yvaCMS/bg/home'),
(95, 1, '2013-08-30 09:18:45', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(96, 1, '2013-08-30 09:19:18', '::1', 'Firefox 23.0', '', 'http://localhost/yvaCMS/bg/home'),
(97, 1, '2013-08-30 09:19:31', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(98, 1, '2013-08-30 09:19:32', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(99, 1, '2013-08-30 09:19:36', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(100, 1, '2013-08-30 09:20:11', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(101, 1, '2013-08-30 09:21:52', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(102, 1, '2013-08-30 09:21:56', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(103, 1, '2013-08-30 09:22:19', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(104, 1, '2013-08-30 09:22:21', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(105, 1, '2013-08-30 09:22:25', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(106, 1, '2013-08-30 09:22:29', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(107, 1, '2013-08-30 09:22:38', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg/home'),
(108, 1, '2013-08-30 09:22:40', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/home', 'http://localhost/yvaCMS/bg'),
(109, 1, '2013-08-30 09:22:47', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/bg/images/audio_icon.gif'),
(110, 1, '2013-08-30 09:22:48', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/bg/contacts', 'http://localhost/yvaCMS/bg'),
(111, 1, '2013-08-30 09:22:57', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/en/contacts', 'http://localhost/yvaCMS/en'),
(112, 1, '2013-08-30 09:22:57', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif'),
(113, 1, '2013-08-30 09:23:22', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif'),
(114, 1, '2013-08-30 09:23:31', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif'),
(115, 1, '2013-08-30 09:23:36', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/en/contacts', 'http://localhost/yvaCMS/en/home'),
(116, 1, '2013-08-30 09:23:40', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif'),
(117, 1, '2013-08-30 09:23:52', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif'),
(118, 1, '2013-08-30 09:23:54', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/en/contacts', 'http://localhost/yvaCMS/en/home'),
(119, 1, '2013-08-30 09:23:57', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif'),
(120, 1, '2013-08-30 09:24:37', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif'),
(121, 1, '2013-08-30 09:24:42', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif'),
(122, 1, '2013-08-30 09:24:47', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/en/contacts', 'http://localhost/yvaCMS/en/home'),
(123, 1, '2013-08-30 09:25:11', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS/en/contacts', 'http://localhost/yvaCMS/en/home'),
(124, 1, '2013-08-30 09:25:26', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif'),
(125, 1, '2013-08-30 09:27:23', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif'),
(126, 1, '2013-08-30 09:27:34', '::1', 'Firefox 23.0', 'http://localhost/yvaCMS//plugins/securimage/securimage_play.swf?bgcol=', 'http://localhost/yvaCMS/en/images/audio_icon.gif');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `position` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `show_in_language` int(4) DEFAULT NULL,
  `params` text NOT NULL,
  `start_publishing` date DEFAULT NULL,
  `end_publishing` date DEFAULT NULL,
  `show_title` enum('yes','no') DEFAULT 'yes',
  `css_class_sufix` varchar(50) NOT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` enum('yes','no','trash','deleted') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`show_in_language`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `banners_statistics`
--

CREATE TABLE IF NOT EXISTS `banners_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1 - impresion, 2 - click',
  `created_on` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  `user_agent` varchar(500) NOT NULL,
  `user_referrer` varchar(1000) NOT NULL,
  `page_url` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `banner_id` (`banner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `extension` varchar(50) DEFAULT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` enum('yes','no','trash','deleted') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `extension`, `created_by`, `created_on`, `updated_by`, `updated_on`, `status`, `order`) VALUES
(1, 'articles', 1, '2013-08-29 13:52:05', NULL, NULL, 'yes', 1),
(2, 'menus', 1, '2013-08-29 13:52:05', NULL, NULL, 'yes', 1),
(3, 'menus', 1, '2013-08-29 16:50:27', 1, '2013-08-29 16:50:51', 'yes', 2);

-- --------------------------------------------------------

--
-- Table structure for table `categories_data`
--

CREATE TABLE IF NOT EXISTS `categories_data` (
  `category_id` int(4) NOT NULL,
  `language_id` int(4) NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  KEY `language_id` (`language_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories_data`
--

INSERT INTO `categories_data` (`category_id`, `language_id`, `title`, `description`) VALUES
(1, 1, 'Некатегоризирани', '<p>default</p>'),
(1, 2, 'Uncategorized', '<p>default</p>'),
(2, 1, 'Некатегоризирани', '<p>default</p>'),
(2, 2, 'Uncategorized', '<p>default</p>'),
(3, 1, 'Главно меню', ''),
(3, 2, 'Main menu', '');

-- --------------------------------------------------------

--
-- Table structure for table `com_contacts_forms`
--

CREATE TABLE IF NOT EXISTS `com_contacts_forms` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `to` varchar(500) NOT NULL,
  `cc` varchar(500) NOT NULL,
  `bcc` varchar(500) NOT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` enum('yes','no','trash','deleted') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `com_contacts_forms`
--

INSERT INTO `com_contacts_forms` (`id`, `to`, `cc`, `bcc`, `created_by`, `created_on`, `updated_by`, `updated_on`, `status`, `order`) VALUES
(1, 'ofice@yvacms.com', '', '', 1, '2013-08-29 16:54:07', 1, NULL, 'yes', 1);

-- --------------------------------------------------------

--
-- Table structure for table `com_contacts_forms_data`
--

CREATE TABLE IF NOT EXISTS `com_contacts_forms_data` (
  `contact_form_id` int(4) NOT NULL,
  `language_id` int(4) NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `text_above` text NOT NULL,
  `text_under` text NOT NULL,
  `msg_success` varchar(1000) NOT NULL,
  `msg_error` varchar(1000) NOT NULL,
  `fields` varchar(1000) NOT NULL,
  KEY `contact_form_id` (`contact_form_id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `com_contacts_forms_data`
--

INSERT INTO `com_contacts_forms_data` (`contact_form_id`, `language_id`, `title`, `description`, `text_above`, `text_under`, `msg_success`, `msg_error`, `fields`) VALUES
(1, 1, 'Контактна форма', '', '', '', '', '', '{"1":{"label":"\\u0422\\u0435\\u043c\\u0430","type":"text","value":"","mandatory":"no"},"2":{"label":"\\u0421\\u044a\\u043e\\u0431\\u0449\\u0435\\u043d\\u0438\\u0435","type":"textarea","value":"","mandatory":"no"},"captcha":{"enabled":"yes"}}'),
(1, 2, 'Contact form', '', '', '', '', '', '{"1":{"label":"Subject","type":"text","value":"","mandatory":"no"},"2":{"label":"Message","type":"textarea","value":"","mandatory":"no"},"captcha":{"enabled":"yes"}}');

-- --------------------------------------------------------

--
-- Table structure for table `com_contacts_forms_messages`
--

CREATE TABLE IF NOT EXISTS `com_contacts_forms_messages` (
  `contact_form_id` int(11) NOT NULL,
  `user_agent` varchar(500) NOT NULL,
  `page_url` varchar(1000) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `message` text NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


--
-- Table structure for table `com_gallery_albums`
--

CREATE TABLE IF NOT EXISTS `com_gallery_albums` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `show_in_language` int(4) DEFAULT NULL,
  `params` text NOT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` enum('yes','no','trash','deleted') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  KEY `show_in_language` (`show_in_language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `com_gallery_albums_data`
--

CREATE TABLE IF NOT EXISTS `com_gallery_albums_data` (
  `album_id` int(4) NOT NULL,
  `language_id` int(4) NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  KEY `language_id` (`language_id`),
  KEY `album_id` (`album_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `com_gallery_images`
--

CREATE TABLE IF NOT EXISTS `com_gallery_images` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `album_id` int(4) NOT NULL,
  `show_in_language` int(4) DEFAULT NULL,
  `croped` tinyint(1) NOT NULL,
  `ext` varchar(5) NOT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` enum('yes','no','trash','deleted') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `show_in_language` (`show_in_language`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  KEY `album_id` (`album_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `com_gallery_images_data`
--

CREATE TABLE IF NOT EXISTS `com_gallery_images_data` (
  `image_id` int(4) NOT NULL,
  `language_id` int(4) NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  KEY `language_id` (`language_id`),
  KEY `image_id` (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `com_polls`
--

CREATE TABLE IF NOT EXISTS `com_polls` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `start_publishing` date DEFAULT NULL,
  `end_publishing` date DEFAULT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `title` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `status` enum('yes','no','trash','deleted') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `com_poll_answers`
--

CREATE TABLE IF NOT EXISTS `com_poll_answers` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `poll_id` int(4) NOT NULL,
  `title` varchar(500) NOT NULL,
  `votes` int(4) NOT NULL,
  `status` enum('yes','no','trash') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE IF NOT EXISTS `custom_fields` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `extension_keys` varchar(500) DEFAULT NULL,
  `title` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `multilang` enum('yes','no') NOT NULL,
  `mandatory` enum('yes','no','email','date') NOT NULL,
  `extension` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `params` text NOT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` enum('yes','no','trash','deleted') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields_values`
--

CREATE TABLE IF NOT EXISTS `custom_fields_values` (
  `custom_field_id` int(4) NOT NULL,
  `element_id` int(4) NOT NULL,
  `language_id` int(4) DEFAULT NULL,
  `value` varchar(1000) NOT NULL,
  UNIQUE KEY `unique key` (`custom_field_id`,`element_id`,`language_id`),
  KEY `custom_field_id` (`custom_field_id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `abbreviation` varchar(5) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `default` varchar(5) NOT NULL,
  `image` varchar(100) NOT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` enum('yes','no','trash','deleted') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `title`, `abbreviation`, `description`, `default`, `image`, `created_by`, `created_on`, `updated_by`, `updated_on`, `status`, `order`) VALUES
(1, 'Български', 'bg', '', 'yes', '', 1, '2013-08-29 13:52:06', NULL, NULL, 'yes', 1),
(2, 'English', 'en', '', 'no', '', 1, '2013-08-29 13:52:06', NULL, NULL, 'yes', 2);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `category_id` int(4) NOT NULL,
  `parent_id` int(4) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `show_in_language` int(4) DEFAULT NULL,
  `show_title` enum('yes','no') NOT NULL,
  `alias` varchar(500) CHARACTER SET ucs2 NOT NULL,
  `default` enum('yes','no') NOT NULL,
  `access` enum('public','registred') NOT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `params` text NOT NULL,
  `target` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL,
  `main_template` varchar(100) NOT NULL,
  `content_template` varchar(100) NOT NULL,
  `description_as_page_title` enum('yes','no') NOT NULL DEFAULT 'no',
  `status` enum('yes','no','trash','deleted') NOT NULL,
  `order` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  KEY `category_id` (`category_id`),
  KEY `parent_id` (`parent_id`),
  KEY `language_id` (`show_in_language`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `category_id`, `parent_id`, `type`, `show_in_language`, `show_title`, `alias`, `default`, `access`, `created_by`, `created_on`, `updated_by`, `updated_on`, `params`, `target`, `image`, `main_template`, `content_template`, `description_as_page_title`, `status`, `order`) VALUES
(1, 3, NULL, 'article', NULL, 'yes', 'home', 'yes', 'public', 1, '2013-08-29 16:37:08', 1, '2013-08-29 16:51:00', '{"article_id":"1"}', '_parent', '', 'default', 'default', '', 'yes', 1),
(2, 3, NULL, 'components/contact_forms', NULL, 'yes', 'contacts', 'no', 'public', 1, '2013-08-29 16:52:39', 1, '2013-08-30 08:45:03', '{"contact_form_id":"1"}', '_parent', '', 'default', 'default', '', 'yes', 2);

-- --------------------------------------------------------

--
-- Table structure for table `menus_data`
--

CREATE TABLE IF NOT EXISTS `menus_data` (
  `menu_id` int(4) NOT NULL,
  `language_id` int(4) NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `meta_keywords` varchar(1000) NOT NULL,
  `meta_description` varchar(1000) NOT NULL,
  KEY `language_id` (`language_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menus_data`
--

INSERT INTO `menus_data` (`menu_id`, `language_id`, `title`, `description`, `meta_keywords`, `meta_description`) VALUES
(1, 1, 'Начало', '', '', ''),
(1, 2, 'Home', '', '', ''),
(2, 1, 'Контакти', '', '', ''),
(2, 2, 'Contacts', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `position` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `show_in_language` int(4) DEFAULT NULL,
  `start_publishing` date DEFAULT NULL,
  `end_publishing` date DEFAULT NULL,
  `params` mediumtext NOT NULL,
  `access` enum('public','registred') NOT NULL,
  `show_title` enum('yes','no') DEFAULT 'yes',
  `css_class_sufix` varchar(50) NOT NULL,
  `template` varchar(100) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(4) NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` enum('yes','no','trash','deleted') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`show_in_language`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `position`, `type`, `show_in_language`, `start_publishing`, `end_publishing`, `params`, `access`, `show_title`, `css_class_sufix`, `template`, `created_on`, `created_by`, `updated_by`, `updated_on`, `status`, `order`) VALUES
(1, 'menu', 'mod_menu', NULL, NULL, NULL, '{"category_id":"3","display_in":"all"}', 'public', 'no', '', 'default', '2013-08-29 16:49:03', 1, 1, '2013-08-29 16:51:09', 'yes', 1),
(2, 'navigation', 'mod_breadcrumb', NULL, NULL, NULL, '{"separator":"\\/","display_in":"all","text":{"2":"You are here:","1":"\\u0412\\u0438\\u0435 \\u0441\\u0442\\u0435 \\u0442\\u0443\\u043a:"}}', 'public', 'no', '', 'default', '2013-08-30 08:21:25', 1, 1, '2013-08-30 08:55:43', 'yes', 1),
(3, 'search', 'mod_search_form', NULL, NULL, NULL, '{"show_button":"yes","display_in":"all","label":{"2":"","1":""},"field_text":{"2":"Search text","1":"\\u041d\\u0430\\u043c\\u0435\\u0440\\u0438"},"button_text":{"2":"Search","1":"\\u0422\\u044a\\u0440\\u0441\\u0438"}}', 'public', 'no', '', 'default', '2013-08-30 08:50:21', 1, 1, '2013-08-30 08:58:38', 'yes', 1),
(4, 'language-switch', 'mod_language_switch', NULL, NULL, NULL, '{"images":"yes","menu_type":"list","display_in":"all"}', 'public', 'no', '', 'default', '2013-08-30 09:02:45', 1, 1, '2013-08-30 09:27:30', 'yes', 1);

-- --------------------------------------------------------

--
-- Table structure for table `modules_data`
--

CREATE TABLE IF NOT EXISTS `modules_data` (
  `module_id` int(4) NOT NULL,
  `language_id` int(4) NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  KEY `language_id` (`language_id`),
  KEY `module_id` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `modules_data`
--

INSERT INTO `modules_data` (`module_id`, `language_id`, `title`, `description`) VALUES
(1, 1, 'Главно меню', ''),
(1, 2, 'Main menu', ''),
(2, 1, 'Навигация', ''),
(2, 2, 'Breadcrumb', ''),
(3, 1, 'Форма за търсене', ''),
(3, 2, 'Search form', ''),
(4, 1, 'Смяна на език', ''),
(4, 2, 'Language switch', '');

-- --------------------------------------------------------

--
-- Table structure for table `mod_google_map_markers`
--

CREATE TABLE IF NOT EXISTS `mod_google_map_markers` (
  `module_id` int(4) DEFAULT NULL,
  `lat` varchar(100) NOT NULL,
  `lng` varchar(100) NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(4) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `language_id`, `name`, `value`) VALUES
(1, NULL, 'template', 'dynamic/index'),
(2, NULL, 'site_name_in_title', 'yes'),
(3, NULL, 'site_name_in_title_separator', '-'),
(4, NULL, 'robots', ''),
(5, 1, 'site_name', 'yvaCMS - BG'),
(6, 1, 'meta_description', ''),
(7, 1, 'meta_keywords', ''),
(8, 2, 'site_name', 'yvaCMS - ENG'),
(9, 2, 'meta_description', ''),
(10, 2, 'meta_keywords', ''),
(11, NULL, 'from_email', 'site@yva.cms'),
(12, NULL, 'from_name', 'yvaCMS'),
(13, NULL, 'mailer', 'mail'),
(14, NULL, 'sendmail', '/usr/sbin/sendmail'),
(15, NULL, 'ssmt_security', ''),
(16, NULL, 'ssmt_port', '25'),
(17, NULL, 'ssmt_user', ''),
(18, NULL, 'ssmt_pass', ''),
(19, NULL, 'ssmt_host', ''),
(20, NULL, 'environment', 'development'),
(21, NULL, 'default_language_in_url', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `user_group_id` int(4) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `name` varchar(50) NOT NULL,
  `user` varchar(50) NOT NULL,
  `pass` varchar(50) NOT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` enum('yes','no','trash','deleted') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`user_group_id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_group_id`, `description`, `name`, `user`, `pass`, `created_by`, `created_on`, `updated_by`, `updated_on`, `status`, `order`) VALUES
(1, 3, '', 'Yordan Arnaudov', 'yordan', '2601b64458cb69fa40d70e85f4ec835b', 1, '2013-08-29 13:52:07', NULL, NULL, 'yes', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `access` varchar(1000) NOT NULL,
  `status` enum('yes','no','trash','deleted') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `title`, `description`, `access`, `status`, `order`) VALUES
(1, 'Users', '', '{"articles":"on","categories/articles":"on","statistics/articles":"on","menus":"on","categories/menus":"on","banners":"on","statistics/banners":"on","modules":"on","components":"on","components/polls":"on","components/gallery":"on","components/contact_forms":"on"}', 'yes', 2),
(2, 'Administrators', '', '{"articles":"on","categories/articles":"on","statistics/articles":"on","menus":"on","categories/menus":"on","banners":"on","statistics/banners":"on","languages":"on","users":"on","groups/users":"on","modules":"on","components":"on","components/polls":"on","components/gallery":"on","components/contact_forms":"on","settings":"on","settings/general":"on","settings/mail":"on"}', 'yes', 3),
(3, 'Super Administrators', '', '*', 'yes', 4);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_4` FOREIGN KEY (`show_in_language`) REFERENCES `languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `articles_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `articles_ibfk_6` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `articles_categories`
--
ALTER TABLE `articles_categories`
  ADD CONSTRAINT `articles_categories_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `articles_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `articles_comments`
--
ALTER TABLE `articles_comments`
  ADD CONSTRAINT `articles_comments_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `articles_comments_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `articles_data`
--
ALTER TABLE `articles_data`
  ADD CONSTRAINT `articles_data_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `articles_data_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `articles_history`
--
ALTER TABLE `articles_history`
  ADD CONSTRAINT `articles_history_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `articles_history_ibfk_3` FOREIGN KEY (`show_in_language`) REFERENCES `languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `articles_history_ibfk_4` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `articles_history_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `articles_history_ibfk_6` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `articles_statistics`
--
ALTER TABLE `articles_statistics`
  ADD CONSTRAINT `articles_statistics_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `banners_statistics`
--
ALTER TABLE `banners_statistics`
  ADD CONSTRAINT `banners_statistics_ibfk_1` FOREIGN KEY (`banner_id`) REFERENCES `banners` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `categories_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `categories_data`
--
ALTER TABLE `categories_data`
  ADD CONSTRAINT `categories_data_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `categories_data_ibfk_3` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `com_contacts_forms_data`
--
ALTER TABLE `com_contacts_forms_data` 
  ADD FOREIGN KEY ( `contact_form_id` ) REFERENCES `com_contacts_forms` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD FOREIGN KEY ( `language_id` ) REFERENCES `languages` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE ;

--
-- Constraints for table `com_contacts_forms_messages`
--
ALTER TABLE `com_contacts_forms_messages` 
  ADD FOREIGN KEY ( `contact_form_id` ) REFERENCES `com_contacts_forms` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE ;

--
-- Constraints for table `com_gallery_albums`
--
ALTER TABLE `com_gallery_albums`
  ADD CONSTRAINT `com_gallery_albums_ibfk_1` FOREIGN KEY (`show_in_language`) REFERENCES `languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `com_gallery_albums_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `com_gallery_albums_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `com_gallery_albums_data`
--
ALTER TABLE `com_gallery_albums_data`
  ADD CONSTRAINT `com_gallery_albums_data_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `com_gallery_albums` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `com_gallery_albums_data_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `com_gallery_images`
--
ALTER TABLE `com_gallery_images`
  ADD CONSTRAINT `com_gallery_images_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `com_gallery_albums` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `com_gallery_images_ibfk_2` FOREIGN KEY (`show_in_language`) REFERENCES `languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `com_gallery_images_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `com_gallery_images_ibfk_4` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `com_gallery_images_data`
--
ALTER TABLE `com_gallery_images_data`
  ADD CONSTRAINT `com_gallery_images_data_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `com_gallery_images` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `com_gallery_images_data_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `com_poll_answers`
--
ALTER TABLE `com_poll_answers`
  ADD CONSTRAINT `com_poll_answers_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `com_polls` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `custom_fields_values`
--
ALTER TABLE `custom_fields_values`
  ADD CONSTRAINT `custom_fields_values_ibfk_1` FOREIGN KEY (`custom_field_id`) REFERENCES `custom_fields` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `custom_fields_values_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `languages`
--
ALTER TABLE `languages`
  ADD CONSTRAINT `languages_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `languages_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `menus_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `menus_ibfk_3` FOREIGN KEY (`show_in_language`) REFERENCES `languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `menus_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `menus_ibfk_5` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `menus_data`
--
ALTER TABLE `menus_data`
  ADD CONSTRAINT `menus_data_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `menus_data_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `modules_ibfk_1` FOREIGN KEY (`show_in_language`) REFERENCES `languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `modules_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `modules_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `modules_data`
--
ALTER TABLE `modules_data`
  ADD CONSTRAINT `modules_data_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `modules_data_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`user_group_id`) REFERENCES `users_groups` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
