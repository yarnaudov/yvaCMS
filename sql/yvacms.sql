-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2013 at 11:06 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yvacms_new`
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
-- Table structure for table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `category_id` int(4) NOT NULL,
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
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `language_id` (`show_in_language`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `articles_data`
--

CREATE TABLE IF NOT EXISTS `articles_data` (
  `article_id` int(4) NOT NULL,
  `language_id` int(4) NOT NULL,
  `title` varchar(500) NOT NULL,
  `text` text NOT NULL,
  UNIQUE KEY `article_id_language_id` (`article_id`,`language_id`),
  KEY `language_id` (`language_id`),
  KEY `article_id` (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `articles_history`
--

CREATE TABLE IF NOT EXISTS `articles_history` (
  `article_id` int(4) NOT NULL,
  `language_id` int(4) NOT NULL,
  `category_id` int(4) NOT NULL,
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
  `order` int(4) NOT NULL,
  `custom_fields` mediumtext, 
  KEY `category_id` (`category_id`),
  KEY `language_id` (`show_in_language`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  KEY `article_id` (`article_id`),
  KEY `language_id_2` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `position` varchar(50) NOT NULL,
  `type` VARCHAR( 50 ) NOT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `extension`, `created_by`, `created_on`, `updated_by`, `updated_on`, `status`, `order`) VALUES
(1, 'articles', 1, NOW(), NULL, NULL, 'yes', 1),
(2, 'menus', 1, NOW(), NULL, NULL, 'yes', 1);

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
(2, 2, 'Uncategorized', '<p>default</p>');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `title`, `abbreviation`, `description`, `default`, `image`, `created_by`, `created_on`, `updated_by`, `updated_on`, `status`, `order`) VALUES
(1, 'Български', 'bg', '', 'yes', '', 1,  NOW(), NULL, NULL, 'yes', 1),
(2, 'English', 'en', '', 'no', '', 1,  NOW(), NULL, NULL, 'yes', 2);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `category_id` int(4) NOT NULL,
  `parent_id` int(4) DEFAULT NULL,
  `type` VARCHAR( 50 ) NOT NULL,
  `show_in_language` int(4) DEFAULT NULL,
  `show_title` ENUM( 'yes', 'no' ) NOT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `position` varchar(50) NOT NULL,
  `type` VARCHAR( 50 ) NOT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
(19, NULL, 'ssmt_host', '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_group_id`, `description`, `name`, `user`, `pass`, `created_by`, `created_on`, `updated_by`, `updated_on`, `status`, `order`) VALUES
(1, 3, '', 'Yordan Arnaudov', 'yordan', '2601b64458cb69fa40d70e85f4ec835b', 1, NOW(), NULL, NULL, 'yes', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `title`, `description`, `access`, `status`, `order`) VALUES
(1, 'Users', '', '{"articles":"on","categories\/articles":"on","statistics\/articles":"on","menus":"on","categories\/menus":"on","banners":"on","statistics\/banners":"on","modules":"on","components":"on","components\/polls":"on","components\/gallery":"on","components\/contact_forms":"on"}', 'yes', 2),
(2, 'Administrators', '', '{"articles":"on","categories\/articles":"on","statistics\/articles":"on","menus":"on","categories\/menus":"on","banners":"on","statistics\/banners":"on","languages":"on","users":"on","groups\/users":"on","modules":"on","components":"on","components\/polls":"on","components\/gallery":"on","components\/contact_forms":"on","settings":"on","settings\/general":"on","settings\/mail":"on"}', 'yes', 3),
(3, 'Super Administrators', '', '*', 'yes', 4);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `articles_ibfk_4` FOREIGN KEY (`show_in_language`) REFERENCES `languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `articles_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `articles_ibfk_6` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `articles_comments`
--
ALTER TABLE `articles_comments` 
  ADD CONSTRAINT `articles_comments_ibfk_1` FOREIGN KEY ( `article_id` ) REFERENCES `articles` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `articles_comments_ibfk_2` FOREIGN KEY ( `created_by` ) REFERENCES `users` (`id`) ON UPDATE CASCADE ;

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
  ADD CONSTRAINT `articles_history_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE,
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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
