-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 12, 2013 at 04:13 PM
-- Server version: 5.5.9
-- PHP Version: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `site_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `acl_access`
--

CREATE TABLE `acl_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(20) NOT NULL,
  `resource_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role`),
  KEY `resource_id` (`resource_id`),
  KEY `role_id_2` (`role`,`resource_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `acl_menu_main`
--

CREATE TABLE `acl_menu_main` (
  `main_id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `sort_seq` int(11) NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `resource_id` (`resource_id`),
  KEY `sort_seq` (`sort_seq`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `acl_menu_resource`
--

CREATE TABLE `acl_menu_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `main_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `sort_seq` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `main_id` (`main_id`),
  KEY `resource_id` (`resource_id`),
  KEY `sort_seq` (`sort_seq`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `acl_resource`
--

CREATE TABLE `acl_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_name` varchar(120) NOT NULL,
  `module` varchar(40) NOT NULL,
  `controller` varchar(40) NOT NULL,
  `action` varchar(40) NOT NULL,
  `link_image` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `resource_name` (`resource_name`),
  KEY `module` (`module`,`controller`,`action`),
  KEY `module_2` (`module`),
  KEY `controller` (`controller`),
  KEY `action` (`action`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `acl_roles`
--

CREATE TABLE `acl_roles` (
  `role` varchar(20) NOT NULL,
  `descr` varchar(40) NOT NULL,
  `parent` varchar(20) DEFAULT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`role`),
  KEY `level` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `base_users`
--

CREATE TABLE `base_users` (
  `user_id` varchar(40) NOT NULL,
  `fst_name` varchar(20) NOT NULL,
  `lst_name` varchar(20) NOT NULL,
  `pswrd` varchar(40) NOT NULL,
  `role` varchar(20) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `ctrl_user` varchar(10) NOT NULL DEFAULT 'SYS',
  `mod_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
