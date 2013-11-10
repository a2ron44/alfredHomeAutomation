-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 12, 2013 at 04:14 PM
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

--
-- Dumping data for table `acl_access`
--

INSERT INTO `acl_access` (`id`, `role`, `resource_id`) VALUES(2, 'AA_ANY', 2);
INSERT INTO `acl_access` (`id`, `role`, `resource_id`) VALUES(3, 'AA_ANY', 3);
INSERT INTO `acl_access` (`id`, `role`, `resource_id`) VALUES(4, 'AA_ANY', 6);
INSERT INTO `acl_access` (`id`, `role`, `resource_id`) VALUES(5, 'AA_ANY', 7);
INSERT INTO `acl_access` (`id`, `role`, `resource_id`) VALUES(1, 'AB_ALL', 1);

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

--
-- Dumping data for table `acl_menu_main`
--

INSERT INTO `acl_menu_main` (`main_id`, `resource_id`, `sort_seq`) VALUES(1, 1, 1);
INSERT INTO `acl_menu_main` (`main_id`, `resource_id`, `sort_seq`) VALUES(2, 18, 20);

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

--
-- Dumping data for table `acl_menu_resource`
--


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

--
-- Dumping data for table `acl_resource`
--

INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(1, 'Home', 'default', 'index', 'index', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(2, 'Default Error Error', 'default', 'error', 'error', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(3, 'Default Error Accessdenied', 'default', 'error', 'accessdenied', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(6, 'Default Login Login', 'default', 'login', 'login', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(7, 'Default Login Logout', 'default', 'login', 'logout', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(8, 'Admin Acl Resources', 'admin', 'acl', 'resources', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(9, 'Admin Acl Addresource', 'admin', 'acl', 'addresource', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(10, 'Admin Acl Editresource', 'admin', 'acl', 'editresource', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(14, 'Admin Acl Index', 'admin', 'acl', 'index', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(15, 'Admin Acl Roles', 'admin', 'acl', 'roles', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(16, 'Admin Acl Addrole', 'admin', 'acl', 'addrole', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(17, 'Admin Acl Editrole', 'admin', 'acl', 'editrole', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(18, 'Admin', 'admin', 'index', 'index', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(19, 'Admin Acl Delaccess', 'admin', 'acl', 'delaccess', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(20, 'Admin Acl Addaccess', 'admin', 'acl', 'addaccess', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(21, 'Admin Acl Delrole', 'admin', 'acl', 'delrole', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(22, 'Admin Menu Index', 'admin', 'menu', 'index', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(23, 'Admin Menu Ajaxmenuchildren', 'admin', 'menu', 'ajaxmenuchildren', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(24, 'Admin Menu ajaxmenueditchild', 'admin', 'menu', 'ajaxmenueditchild', NULL);
INSERT INTO `acl_resource` (`id`, `resource_name`, `module`, `controller`, `action`, `link_image`) VALUES(25, 'Admin Menu ajaxmenutab', 'admin', 'menu', 'ajaxmenutab', NULL);

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

--
-- Dumping data for table `acl_roles`
--

INSERT INTO `acl_roles` (`role`, `descr`, `parent`, `level`) VALUES('AA_ANY', 'Guest and Above', NULL, 0);
INSERT INTO `acl_roles` (`role`, `descr`, `parent`, `level`) VALUES('AB_ALL', 'All logged in', 'AA_ANY', 0);
INSERT INTO `acl_roles` (`role`, `descr`, `parent`, `level`) VALUES('GUEST', 'Before Login', 'AA_ANY', 0);
INSERT INTO `acl_roles` (`role`, `descr`, `parent`, `level`) VALUES('SUPER', 'Super User', 'AB_ALL', 99);
INSERT INTO `acl_roles` (`role`, `descr`, `parent`, `level`) VALUES('TEST', 'Testing', 'AB_ALL', 0);

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

--
-- Dumping data for table `base_users`
--

INSERT INTO `base_users` (`user_id`, `fst_name`, `lst_name`, `pswrd`, `role`, `last_login`, `ctrl_user`, `mod_dt`) VALUES('admin@gmail.com', 'admin', 'user', '96630e2b13cf2e62a52b4787f7d5da13', 'SUPER', NULL, 'SYS', NULL);
INSERT INTO `base_users` (`user_id`, `fst_name`, `lst_name`, `pswrd`, `role`, `last_login`, `ctrl_user`, `mod_dt`) VALUES('test@gmail.com', 'test', 'user', '0562b36c3c5a3925dbe3c4d32a4f2ba2', 'TEST', NULL, 'SYS', NULL);
