-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2013 at 08:31 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

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
(15, 'Потребителски полета', 'Custom fields', 'categories/modules', 14, 'sub_action', 'no', 'yes', 1),
(16, 'Компоненти', 'Components', 'components', NULL, 'general', 'no', 'yes', 7),
(17, 'Настройки', 'Settings', 'settings', NULL, 'general', 'no', 'yes', 8),
(18, 'Основни', 'General', 'settings/general', 17, 'general_sub sub_action', 'no', 'yes', 1),
(19, 'Майл', 'Mail', 'settings/mail', 17, 'sub_action', 'no', 'yes', 2),
(20, 'Анкети', 'Pulls', 'components/pulls', 16, 'general_sub', 'yes', 'yes', 1),
(21, 'Галерия', 'Gallery', 'components/gallery', 16, 'general_sub', 'yes', 'yes', 2),
(22, 'Контактни форми', 'Contact forms', 'components/contact_forms', 16, 'general_sub', 'yes', 'yes', 3),
(23, 'Албуми', 'Albums', 'components/gallery/albums', 21, 'sub_action', 'no', 'no', 1),
(24, 'Снимки', 'Images', 'components/gallery/images', 21, 'sub_action', 'no', 'no', 2);

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
  `params` varchar(1000) NOT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` enum('yes','no','trash') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `language_id` (`show_in_language`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `category_id`, `alias`, `show_in_language`, `start_publishing`, `end_publishing`, `params`, `created_by`, `created_on`, `updated_by`, `updated_on`, `status`, `order`) VALUES
(7, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', '{"images":["media\\/images\\/Koala.jpg","media\\/images\\/Chrysanthemum.jpg","media\\/images\\/Desert.jpg"]}', 1, '2013-01-19 16:14:37', 1, '2013-01-27 20:22:31', 'yes', 1),
(8, 1, 'aricle2', NULL, NULL, NULL, '', 1, '2013-01-19 22:46:15', NULL, NULL, 'yes', 2);

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

--
-- Dumping data for table `articles_data`
--

INSERT INTO `articles_data` (`article_id`, `language_id`, `title`, `text`) VALUES
(7, 1, 'Статия 1', '<p>Текст 1</p>'),
(7, 2, 'Article 1', '<p><a href="article:aricle1">Article 1</a>Text 1<img src="media/iconPull_25.png" alt="" /></p>'),
(8, 1, 'Статия 2', '');

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
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `title` varchar(500) NOT NULL,
  `text` text NOT NULL,
  `status` enum('yes','no','trash') NOT NULL,
  `order` int(4) NOT NULL,
  KEY `category_id` (`category_id`),
  KEY `language_id` (`show_in_language`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  KEY `article_id` (`article_id`),
  KEY `language_id_2` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `articles_history`
--

INSERT INTO `articles_history` (`article_id`, `language_id`, `category_id`, `alias`, `show_in_language`, `start_publishing`, `end_publishing`, `created_by`, `created_on`, `updated_by`, `updated_on`, `title`, `text`, `status`, `order`) VALUES
(7, 1, 1, 'aricle1', NULL, NULL, NULL, 1, '2013-01-19 16:14:37', 1, '2013-01-19 20:07:28', 'Статия 1', '<p>Текст 1 1 1 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', NULL, NULL, NULL, 1, '2013-01-19 16:14:37', 1, '2013-01-19 20:08:30', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', NULL, NULL, '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-19 21:15:53', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', NULL, NULL, '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-19 21:16:08', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', NULL, NULL, '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-19 21:16:25', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', 1, NULL, '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-19 21:16:44', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-19 21:25:19', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 2, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-19 21:49:15', 'Article 1', '<p>Text 1</p>', 'yes', 1),
(7, 2, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-19 21:50:29', 'Article 1', '<p>Text 1</p>', 'yes', 1),
(7, 2, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-19 22:03:10', 'Article 1', '<p><a href="article:aricle1">Article 1</a>Text 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-19 22:03:52', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-27 15:29:37', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-27 15:49:40', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-27 15:52:03', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-27 16:11:08', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-27 16:25:50', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-27 18:09:47', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-27 20:04:16', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-27 20:04:32', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-27 20:04:43', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-27 20:16:26', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-27 20:16:36', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-27 20:17:41', 'Статия 1', '<p>Текст 1</p>', 'yes', 1),
(7, 1, 1, 'aricle1', 1, '2013-01-01', '2013-01-23', 1, '2013-01-19 16:14:37', 1, '2013-01-27 20:22:27', 'Статия 1', '<p>Текст 1</p>', 'yes', 1);

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `position` varchar(50) NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `show_in_language` int(4) DEFAULT NULL,
  `params` varchar(1000) NOT NULL,
  `start_publishing` date DEFAULT NULL,
  `end_publishing` date DEFAULT NULL,
  `css_class_sufix` varchar(50) NOT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` enum('yes','no','trash') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`show_in_language`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `position`, `title`, `description`, `show_in_language`, `params`, `start_publishing`, `end_publishing`, `css_class_sufix`, `created_by`, `created_on`, `updated_by`, `updated_on`, `status`, `order`) VALUES
(4, 'top', 'Банер 1', '', NULL, '{"type":"html","html":"<div>\\r\\n  <a href=\\"#\\" ><\\/a>\\r\\n<\\/div>","display_in":"on_selected","display_menus":["5"],"display_rules":["(.*)modules$","http:\\/\\/vbox7.com\\/play:470f315928","http:\\/\\/localhost\\/phpmyadmin\\/index.php","http:\\/\\/yvaweb.eu$"]}', NULL, NULL, '', 1, '2013-01-24 21:54:09', 1, '2013-01-27 20:20:54', 'yes', 1);

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
  `status` enum('yes','no','trash') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `extension`, `created_by`, `created_on`, `updated_by`, `updated_on`, `status`, `order`) VALUES
(1, 'articles', 1, '2013-01-19 00:00:00', 1, '2013-01-19 21:11:46', 'yes', 1),
(2, 'articles', 1, '2013-01-19 21:13:22', 1, '2013-01-19 21:13:38', 'yes', 2),
(3, 'menus', 1, '2013-01-20 00:04:21', 1, '2013-01-20 00:04:49', 'yes', 1),
(4, 'menus', 1, '2013-01-20 16:11:35', NULL, NULL, 'yes', 2);

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
(2, 1, 'Категория 1', ''),
(2, 2, 'Category 1', ''),
(3, 1, 'Некатегоризирани', ''),
(3, 2, 'Uncategorized', ''),
(4, 1, 'Категория 1', '');

-- --------------------------------------------------------

--
-- Table structure for table `com_contacts_forms`
--

CREATE TABLE IF NOT EXISTS `com_contacts_forms` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `to` varchar(500) NOT NULL,
  `cc` varchar(500) NOT NULL,
  `bcc` varchar(500) NOT NULL,
  `fields` varchar(1000) NOT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` enum('yes','no','trash') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `com_contacts_forms_data`
--

CREATE TABLE IF NOT EXISTS `com_contacts_forms_data` (
  `contact_form_id` int(4) NOT NULL,
  `language_id` int(4) NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `msg_success` varchar(1000) NOT NULL,
  `msg_error` varchar(1000) NOT NULL,
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
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` enum('yes','no','trash') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  KEY `show_in_language` (`show_in_language`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `com_gallery_albums`
--

INSERT INTO `com_gallery_albums` (`id`, `show_in_language`, `created_by`, `created_on`, `updated_by`, `updated_on`, `status`, `order`) VALUES
(1, NULL, 1, '2013-01-26 18:02:41', 1, '2013-01-26 18:12:26', 'yes', 1);

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

--
-- Dumping data for table `com_gallery_albums_data`
--

INSERT INTO `com_gallery_albums_data` (`album_id`, `language_id`, `title`, `description`) VALUES
(1, 1, 'Албум 1', '<p>Описание 1</p>'),
(1, 2, 'Album 1', '<p>Description 1</p>');

-- --------------------------------------------------------

--
-- Table structure for table `com_gallery_images`
--

CREATE TABLE IF NOT EXISTS `com_gallery_images` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `album_id` int(4) NOT NULL,
  `show_in_language` int(4) DEFAULT NULL,
  `ext` varchar(5) NOT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` enum('yes','no','trash') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `show_in_language` (`show_in_language`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  KEY `album_id` (`album_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `com_gallery_images`
--

INSERT INTO `com_gallery_images` (`id`, `album_id`, `show_in_language`, `ext`, `created_by`, `created_on`, `updated_by`, `updated_on`, `status`, `order`) VALUES
(1, 1, 1, 'jpg', 1, '2013-01-26 19:29:05', 1, '2013-01-26 19:37:10', 'yes', 1),
(2, 1, NULL, 'jpg', 1, '2013-01-27 20:01:08', NULL, NULL, 'yes', 2);

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

--
-- Dumping data for table `com_gallery_images_data`
--

INSERT INTO `com_gallery_images_data` (`image_id`, `language_id`, `title`, `description`) VALUES
(1, 1, 'Снимка 1', ''),
(1, 2, 'Image 1', ''),
(2, 1, 'Снимка 2', '');

-- --------------------------------------------------------

--
-- Table structure for table `com_pulls`
--

CREATE TABLE IF NOT EXISTS `com_pulls` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `start_publishing` date DEFAULT NULL,
  `end_publishing` date DEFAULT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `title` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `status` enum('yes','no','trash') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `com_pulls`
--

INSERT INTO `com_pulls` (`id`, `start_publishing`, `end_publishing`, `created_by`, `created_on`, `updated_by`, `updated_on`, `title`, `description`, `status`, `order`) VALUES
(1, '2013-01-26', '2013-03-02', 1, '2013-01-26 15:17:45', 1, '2013-01-26 15:27:41', 'Анкета 1', '', 'yes', 1);

-- --------------------------------------------------------

--
-- Table structure for table `com_pull_answers`
--

CREATE TABLE IF NOT EXISTS `com_pull_answers` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `pull_id` int(4) NOT NULL,
  `title` varchar(500) NOT NULL,
  `votes` int(4) NOT NULL,
  `status` enum('yes','no','trash') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pull_id` (`pull_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `com_pull_answers`
--

INSERT INTO `com_pull_answers` (`id`, `pull_id`, `title`, `votes`, `status`) VALUES
(5, 1, 'Отговор 1', 0, 'yes'),
(6, 1, 'Отговор 2', 0, 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE IF NOT EXISTS `custom_fields` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `multilang` enum('yes','no') NOT NULL,
  `extension` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `type` varchar(50) NOT NULL,
  `value` varchar(500) NOT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` enum('yes','no','trash') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

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
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` enum('yes','no','trash') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `title`, `abbreviation`, `description`, `default`, `created_by`, `created_on`, `updated_by`, `updated_on`, `status`, `order`) VALUES
(1, 'Български', 'bg', '', 'yes', 1, '2013-01-19 00:00:00', 1, '2013-01-19 15:02:36', 'yes', 1),
(2, 'English', 'en', '', 'no', 1, '2013-01-19 00:00:00', 1, '2013-01-19 15:02:24', 'yes', 2),
(3, 'Руский', 'ru', '', 'no', 1, '2013-01-19 15:35:39', NULL, NULL, 'yes', 3);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `category_id` int(4) NOT NULL,
  `parent_id` int(4) DEFAULT NULL,
  `show_in_language` int(4) DEFAULT NULL,
  `alias` varchar(500) CHARACTER SET ucs2 NOT NULL,
  `default` enum('yes','no') NOT NULL,
  `access` enum('public','registred') NOT NULL,
  `created_by` int(4) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `params` varchar(1000) NOT NULL,
  `target` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL,
  `template` varchar(100) NOT NULL,
  `description_as_page_title` enum('yes','no') NOT NULL DEFAULT 'no',
  `status` enum('yes','no','trash') NOT NULL,
  `order` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  KEY `category_id` (`category_id`),
  KEY `parent_id` (`parent_id`),
  KEY `language_id` (`show_in_language`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `category_id`, `parent_id`, `show_in_language`, `alias`, `default`, `access`, `created_by`, `created_on`, `updated_by`, `updated_on`, `params`, `target`, `image`, `template`, `description_as_page_title`, `status`, `order`) VALUES
(4, 3, NULL, 1, 'menu1', 'yes', 'public', 1, '2013-01-20 11:56:49', 1, '2013-01-24 22:47:43', '{"type":"article","article_id":"7"}', '_parent', 'media/iconPull_25.png', 'default', 'yes', 'yes', 1),
(5, 3, NULL, NULL, 'menu2', 'no', 'public', 1, '2013-01-20 12:30:26', 1, '2013-01-22 00:44:07', '{"type":"external_url","url":""}', '_parent', '', 'default', '', 'yes', 1),
(6, 3, NULL, NULL, 'menu3', 'no', 'public', 1, '2013-01-22 00:52:07', 1, '2013-01-22 19:15:26', '{"type":"external_url","url":"www.abv.bg"}', '_parent', '', 'default', '', 'yes', 1);

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
(4, 1, 'Меню 1', '<p>в 12</p>', 'а', 'б'),
(4, 2, 'Menu 1', '<p>2ррф гфе</p>', '', ''),
(5, 1, 'Меню 2', '', '', ''),
(6, 1, 'Меню 3', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `position` varchar(50) NOT NULL,
  `show_in_language` int(4) DEFAULT NULL,
  `start_publishing` date DEFAULT NULL,
  `end_publishing` date DEFAULT NULL,
  `params` varchar(1000) NOT NULL,
  `access` enum('public','registred') NOT NULL,
  `css_class_sufix` varchar(50) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(4) NOT NULL,
  `updated_by` int(4) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` enum('yes','no','trash') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`show_in_language`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `position`, `show_in_language`, `start_publishing`, `end_publishing`, `params`, `access`, `css_class_sufix`, `created_on`, `created_by`, `updated_by`, `updated_on`, `status`, `order`) VALUES
(1, 'main2', 1, '2013-01-02', '2013-01-30', '{"type":"mod_html","html":"<html style=\\"color: green\\">\\r\\n  <!-- this is a comment -->\\r\\n  <head>\\r\\n    <title>Mixed HTML Example<\\/title>\\r\\n    <style type=\\"text\\/css\\">\\r\\n      h1 {font-family: comic sans; color: #f0f;}\\r\\n      div {background: yellow !important;}\\r\\n      body {\\r\\n        max-width: 50em;\\r\\n        margin: 1em 2em 1em 5em;\\r\\n      }\\r\\n    <\\/style>\\r\\n  <\\/head>\\r\\n  <body>\\r\\n    <h1>Mixed HTML Example<\\/h1>\\r\\n    <script>\\r\\n      function jsFunc(arg1, arg2) {\\r\\n        if (arg1 && arg2) document.body.innerHTML = \\"achoo\\";\\r\\n      }\\r\\n    <\\/script>\\r\\n  <\\/body>\\r\\n<\\/html>","display_in":"on_selected","display_menus":["5","6"],"display_rules":["(.*)modules$","(.*)modules\\/edit[0-9]+"]}', 'registred', '_main', '2013-01-22 22:24:27', 1, 1, '2013-01-27 20:20:25', 'yes', 1),
(2, 'asasa', NULL, NULL, NULL, '{"type":"mod_article","article_id":"7","display_in":"all"}', 'public', '', '2013-01-23 00:50:50', 1, 1, '2013-01-23 22:21:36', 'yes', 2);

-- --------------------------------------------------------

--
-- Table structure for table `modules_data`
--

CREATE TABLE IF NOT EXISTS `modules_data` (
  `module_id` int(4) NOT NULL AUTO_INCREMENT,
  `language_id` int(4) NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  KEY `language_id` (`language_id`),
  KEY `module_id` (`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `modules_data`
--

INSERT INTO `modules_data` (`module_id`, `language_id`, `title`, `description`) VALUES
(1, 1, 'Модул 1', ''),
(1, 2, 'Module 1', ''),
(1, 3, 'Модул 1', ''),
(2, 1, 'Модул 2', '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `language_id`, `name`, `value`) VALUES
(2, NULL, 'template', 'dynamic');

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
  `status` enum('yes','no','trash') NOT NULL,
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
(1, 4, '', 'Yordan Arnaudov', 'yordan', '2601b64458cb69fa40d70e85f4ec835b', 1, '2013-01-19 08:13:26', NULL, NULL, 'yes', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `access` varchar(1000) NOT NULL,
  `status` enum('yes','no','trash') NOT NULL,
  `order` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `title`, `description`, `access`, `status`, `order`) VALUES
(1, 'Guests', '', '{"articles":"on","categories\\/articles":"on","custom_fields\\/articles":"on"}', 'yes', 1),
(2, 'Users', '', '', 'yes', 2),
(3, 'Administrators', '', '', 'yes', 3),
(4, 'Super Administrators', '', '*', 'yes', 4);

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
  ADD CONSTRAINT `com_gallery_albums_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `com_gallery_albums_ibfk_1` FOREIGN KEY (`show_in_language`) REFERENCES `languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `com_gallery_albums_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `com_gallery_images_ibfk_4` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `com_gallery_images_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `com_gallery_albums` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `com_gallery_images_ibfk_2` FOREIGN KEY (`show_in_language`) REFERENCES `languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `com_gallery_images_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `com_gallery_images_data`
--
ALTER TABLE `com_gallery_images_data`
  ADD CONSTRAINT `com_gallery_images_data_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `com_gallery_images` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `com_gallery_images_data_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `com_pull_answers`
--
ALTER TABLE `com_pull_answers`
  ADD CONSTRAINT `com_pull_answers_ibfk_1` FOREIGN KEY (`pull_id`) REFERENCES `com_pulls` (`id`) ON UPDATE CASCADE;

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
