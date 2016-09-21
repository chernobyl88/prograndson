-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mer 21 Septembre 2016 à 12:58
-- Version du serveur :  5.7.11
-- Version de PHP :  7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `new_prograndson`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresses`
--

CREATE TABLE `adresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `entreprise` varchar(100) NOT NULL,
  `rue` varchar(100) NOT NULL,
  `case_postale` varchar(100) NOT NULL,
  `localite` varchar(100) NOT NULL,
  `code_postal` varchar(15) NOT NULL,
  `no_rue` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `adresses`
--

INSERT INTO `adresses` (`id`, `user_id`, `entreprise`, `rue`, `case_postale`, `localite`, `code_postal`, `no_rue`) VALUES
(1, 0, '', '', 'test', 'test', '1234', ''),
(2, 0, '', '', 'test', 'test', '1234', '');

-- --------------------------------------------------------

--
-- Structure de la table `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `valeur` varchar(100) NOT NULL,
  `clef` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `config`
--

INSERT INTO `config` (`id`, `valeur`, `clef`) VALUES
(1, 'vincentzellweger@hotmail.com', 'BASE_EMAIL_TO'),
(2, 'vincentzellweger@hotmail.com', 'BASE_EMAIL_FROM');

-- --------------------------------------------------------

--
-- Structure de la table `connexion_log`
--

CREATE TABLE `connexion_log` (
  `id` int(11) NOT NULL,
  `session_id` varchar(50) NOT NULL,
  `ip_adresse` varchar(30) NOT NULL,
  `match_access` tinyint(4) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `date_co` datetime NOT NULL,
  `date_clk` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `connexion_log`
--

INSERT INTO `connexion_log` (`id`, `session_id`, `ip_adresse`, `match_access`, `user_name`, `date_co`, `date_clk`) VALUES
(1, '258uqtmngpru59nf3to9oo6vl0', '127.0.0.1', 1, 'admin', '2016-08-09 15:54:12', '2016-08-09 15:54:40'),
(2, 'n557qpkki48p18mf6dk6kdcm93', '127.0.0.1', 1, 'admin', '2016-08-09 19:38:37', '2016-08-09 20:08:58'),
(3, 'n557qpkki48p18mf6dk6kdcm93', '127.0.0.1', 1, 'admin', '2016-08-10 13:44:50', '2016-08-10 14:56:40'),
(4, '5gfmnqqjlvj0g71gu2olr30es7', '127.0.0.1', 1, 'admin', '2016-08-11 13:51:16', '2016-08-11 14:08:54'),
(5, '5gfmnqqjlvj0g71gu2olr30es7', '127.0.0.1', 1, 'admin', '2016-08-11 23:43:29', '2016-08-12 00:13:38'),
(6, '5gfmnqqjlvj0g71gu2olr30es7', '127.0.0.1', 1, 'admin', '2016-08-12 13:37:18', '2016-08-12 13:49:13'),
(7, 'smrqufgh7bh8li0tljikpm3ih7', '127.0.0.1', 1, 'admin', '2016-08-12 14:25:41', '2016-08-12 14:26:40'),
(8, 'g9kcre40iqb869p31epns63q13', '127.0.0.1', 1, 'admin', '2016-08-12 15:19:59', '2016-08-12 16:40:00'),
(9, 'g9kcre40iqb869p31epns63q13', '127.0.0.1', 1, 'admin', '2016-08-13 01:01:54', '2016-08-13 02:45:22'),
(10, 'g9kcre40iqb869p31epns63q13', '127.0.0.1', 1, 'admin', '2016-08-13 14:11:31', '2016-08-13 14:50:26'),
(11, 'g9kcre40iqb869p31epns63q13', '127.0.0.1', 1, 'admin', '2016-08-14 00:56:56', '2016-08-14 01:24:10'),
(12, 'g9kcre40iqb869p31epns63q13', '127.0.0.1', 1, 'admin', '2016-08-14 02:12:23', '2016-08-14 02:21:12'),
(13, 'g9kcre40iqb869p31epns63q13', '127.0.0.1', 1, 'admin', '2016-08-14 13:54:18', '2016-08-14 13:57:11'),
(14, 'g9kcre40iqb869p31epns63q13', '127.0.0.1', 1, 'admin', '2016-08-14 16:15:46', '2016-08-14 16:50:10'),
(15, 'g9kcre40iqb869p31epns63q13', '127.0.0.1', 1, 'admin', '2016-08-14 17:24:01', '2016-08-14 19:03:06'),
(16, 'g9kcre40iqb869p31epns63q13', '127.0.0.1', 1, 'admin', '2016-08-15 02:50:34', '2016-08-15 02:54:26'),
(17, 'g9kcre40iqb869p31epns63q13', '127.0.0.1', 1, 'admin', '2016-08-15 15:47:31', '2016-08-15 17:00:37'),
(18, 'g9kcre40iqb869p31epns63q13', '127.0.0.1', 1, 'admin', '2016-08-15 22:40:46', '2016-08-16 00:50:39'),
(19, 'ortv4j7r7ibr383ib0cmlgn4l2', '127.0.0.1', 1, 'admin', '2016-08-17 00:27:54', '2016-08-17 01:16:07'),
(20, '3pteb1bj9t88pkqpi7v2tdt2s2', '127.0.0.1', 1, 'admin', '2016-08-17 16:04:06', '2016-08-17 16:04:06'),
(21, '3pteb1bj9t88pkqpi7v2tdt2s2', '127.0.0.1', 1, 'admin', '2016-08-19 14:49:12', '2016-08-19 14:57:52'),
(22, 'n9vbpgc0i9c6if16utc3ump7g1', '127.0.0.1', 0, NULL, '2016-08-22 18:11:27', '2016-08-22 18:11:27'),
(23, 'n9vbpgc0i9c6if16utc3ump7g1', '127.0.0.1', 0, NULL, '2016-08-22 18:11:46', '2016-08-22 18:11:46'),
(24, 'n9vbpgc0i9c6if16utc3ump7g1', '127.0.0.1', 1, 'admin', '2016-08-22 18:11:52', '2016-08-22 18:11:52'),
(25, 'n9vbpgc0i9c6if16utc3ump7g1', '127.0.0.1', 1, 'admin', '2016-08-22 18:16:57', '2016-08-22 18:16:57'),
(26, 'n9vbpgc0i9c6if16utc3ump7g1', '127.0.0.1', 0, 'test234', '2016-08-23 17:54:45', '2016-08-23 17:54:45'),
(27, 'n9vbpgc0i9c6if16utc3ump7g1', '127.0.0.1', 1, 'test234', '2016-08-23 17:54:55', '2016-08-23 17:54:55'),
(28, 'n9vbpgc0i9c6if16utc3ump7g1', '127.0.0.1', 1, 'admin', '2016-08-24 13:40:20', '2016-08-24 14:20:03'),
(29, 'n9vbpgc0i9c6if16utc3ump7g1', '127.0.0.1', 1, 'admin', '2016-08-24 15:13:29', '2016-08-24 16:10:05'),
(30, 'vo8dipaoackhit8ck4vc8fe931', '127.0.0.1', 1, 'admin', '2016-08-24 19:24:55', '2016-08-24 20:52:37'),
(31, 'vo8dipaoackhit8ck4vc8fe931', '127.0.0.1', 1, 'admin', '2016-08-25 09:54:18', '2016-08-25 11:52:00'),
(32, 'vo8dipaoackhit8ck4vc8fe931', '127.0.0.1', 1, 'admin', '2016-08-25 14:28:48', '2016-08-25 14:54:56'),
(33, 'vo8dipaoackhit8ck4vc8fe931', '127.0.0.1', 1, 'admin', '2016-08-25 16:48:56', '2016-08-25 17:34:10'),
(34, 'vo8dipaoackhit8ck4vc8fe931', '127.0.0.1', 1, 'admin', '2016-08-25 18:30:54', '2016-08-25 19:07:37'),
(35, 'vo8dipaoackhit8ck4vc8fe931', '127.0.0.1', 1, 'admin', '2016-08-26 13:58:38', '2016-08-26 13:59:19'),
(36, 'vo8dipaoackhit8ck4vc8fe931', '127.0.0.1', 1, 'admin', '2016-08-26 14:29:43', '2016-08-26 14:35:32'),
(37, 'vo8dipaoackhit8ck4vc8fe931', '127.0.0.1', 1, 'admin', '2016-08-26 15:10:00', '2016-08-26 15:17:00'),
(38, 'vo8dipaoackhit8ck4vc8fe931', '127.0.0.1', 1, 'admin', '2016-08-26 15:50:11', '2016-08-26 18:19:53'),
(39, 'vo8dipaoackhit8ck4vc8fe931', '127.0.0.1', 1, 'admin', '2016-08-26 21:45:59', '2016-08-26 21:56:38'),
(40, 'vo8dipaoackhit8ck4vc8fe931', '127.0.0.1', 1, 'admin', '2016-08-26 21:48:34', '2016-08-26 21:48:34'),
(41, 'v1hijc4vephutqvi091tcl4kp6', '::1', 1, 'admin', '2016-09-21 14:37:13', '2016-09-21 14:42:51'),
(42, 'v1hijc4vephutqvi091tcl4kp6', '::1', 1, 'admin', '2016-09-21 14:38:26', '2016-09-21 14:38:26');

-- --------------------------------------------------------

--
-- Structure de la table `document_access`
--

CREATE TABLE `document_access` (
  `id` int(11) NOT NULL,
  `groupe_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `dynamic_page`
--

CREATE TABLE `dynamic_page` (
  `id` int(11) NOT NULL,
  `date_add` datetime DEFAULT NULL,
  `date_modif` datetime DEFAULT NULL,
  `page_content` text,
  `date_end` datetime DEFAULT NULL,
  `visible` tinyint(1) DEFAULT NULL,
  `routes_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `file`
--

CREATE TABLE `file` (
  `id` int(11) NOT NULL,
  `file_name` varchar(50) DEFAULT NULL,
  `file_src` varchar(200) DEFAULT NULL,
  `file_pub_name` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `dynamic` tinyint(1) NOT NULL DEFAULT '0',
  `cst_name` varchar(100) DEFAULT NULL,
  `date_upload` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `file`
--

INSERT INTO `file` (`id`, `file_name`, `file_src`, `file_pub_name`, `user_id`, `dynamic`, `cst_name`, `date_upload`) VALUES
(1, 'test.png', 'test', 'test', 1, 0, NULL, '0000-00-00 00:00:00'),
(2, 'ouaf_57add9efd55be.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Document\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-12 16:15:11'),
(3, 'ouaf_57addedf294c2.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Document\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-12 16:36:15'),
(4, 'ouaf_57addeef56503.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Document\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-12 16:36:31'),
(5, 'ouaf_57addef9ec539.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Document\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-12 16:36:41'),
(6, 'ouaf_57addfc01c49a.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Document\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-12 16:40:00'),
(7, 'ouaf_57ae584cc3e33.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 01:14:20'),
(8, 'ouaf_57ae58d322fdb.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 01:16:35'),
(9, 'ouaf_57ae58ec4855e.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 01:17:00'),
(10, 'ouaf_57ae5a1d3b56d.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 01:22:05'),
(11, 'ouaf_57ae5a29d9d0f.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 01:22:17'),
(12, 'ouaf_57ae5a7dd7db7.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 01:23:41'),
(13, 'ouaf_57ae5b2333d42.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 01:26:27'),
(14, 'ouaf_57ae5b99109f1.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 01:28:25'),
(15, 'ouaf_57ae5be82bb1a.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 01:29:44'),
(16, 'KDS - bladensoul.html', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 01:36:56'),
(17, 'KDS - bladensoul_fichiers/', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 01:36:56'),
(18, 'KDS - bladensoul_fichiers/WBIframeHandlerClient.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 01:36:56'),
(19, 'KDS - bladensoul_fichiers/analytics.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 01:36:56'),
(20, 'KDS - bladensoul_fichiers/fingerprint.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 01:36:56'),
(21, 'KDS - bladensoul_fichiers/jquery-1.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 01:36:56'),
(22, 'KDS - bladensoul_fichiers/jwplayer.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 01:36:56'),
(23, 'KDS - bladensoul_fichiers/logo_bnsw.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 01:36:56'),
(24, 'KDS - bladensoul_fichiers/logo_jv_white.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 01:36:56'),
(25, 'KDS - bladensoul_fichiers/logo_ncsoft.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 01:36:56'),
(26, 'KDS - bladensoul_fichiers/main.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 01:36:56'),
(27, 'KDS - bladensoul_fichiers/nr-892.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 01:36:56'),
(28, 'KDS - bladensoul_fichiers/reset.css', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 01:36:56'),
(29, 'KDS - bladensoul_fichiers/style.css', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 01:36:56'),
(30, 'ouaf_57ae62c6b8ec1.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 01:59:02'),
(31, 'ouaf_57ae62d45112d.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 01:59:16'),
(32, 'ouaf_57ae62e9e2f7a.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 01:59:37'),
(33, 'ouaf_57ae63421c567.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 02:01:06'),
(34, 'ouaf_57ae634a2acec.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 02:01:14'),
(35, 'KDS - bladensoul.html', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:17:44'),
(36, 'KDS - bladensoul_fichiers/', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:17:44'),
(37, 'KDS - bladensoul_fichiers/WBIframeHandlerClient.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:17:44'),
(38, 'KDS - bladensoul_fichiers/analytics.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:17:44'),
(39, 'KDS - bladensoul_fichiers/fingerprint.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:17:44'),
(40, 'KDS - bladensoul_fichiers/jquery-1.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:17:44'),
(41, 'KDS - bladensoul_fichiers/jwplayer.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:17:44'),
(42, 'KDS - bladensoul_fichiers/logo_bnsw.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:17:44'),
(43, 'KDS - bladensoul_fichiers/logo_jv_white.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:17:44'),
(44, 'KDS - bladensoul_fichiers/logo_ncsoft.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:17:44'),
(45, 'KDS - bladensoul_fichiers/main.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:17:44'),
(46, 'KDS - bladensoul_fichiers/nr-892.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:17:44'),
(47, 'KDS - bladensoul_fichiers/reset.css', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:17:44'),
(48, 'KDS - bladensoul_fichiers/style.css', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:17:44'),
(49, 'KDS - bladensoul.html', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:18:43'),
(50, 'KDS - bladensoul_fichiers/', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:18:43'),
(51, 'KDS - bladensoul_fichiers/WBIframeHandlerClient.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:18:43'),
(52, 'KDS - bladensoul_fichiers/analytics.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:18:43'),
(53, 'KDS - bladensoul_fichiers/fingerprint.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:18:43'),
(54, 'KDS - bladensoul_fichiers/jquery-1.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:18:43'),
(55, 'KDS - bladensoul_fichiers/jwplayer.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:18:43'),
(56, 'KDS - bladensoul_fichiers/logo_bnsw.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:18:43'),
(57, 'KDS - bladensoul_fichiers/logo_jv_white.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:18:43'),
(58, 'KDS - bladensoul_fichiers/logo_ncsoft.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:18:43'),
(59, 'KDS - bladensoul_fichiers/main.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:18:43'),
(60, 'KDS - bladensoul_fichiers/nr-892.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:18:43'),
(61, 'KDS - bladensoul_fichiers/reset.css', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:18:43'),
(62, 'KDS - bladensoul_fichiers/style.css', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:18:43'),
(63, 'KDS - bladensoul.html', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:19:31'),
(64, 'KDS - bladensoul_fichiers/', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:19:31'),
(65, 'KDS - bladensoul_fichiers/WBIframeHandlerClient.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:19:31'),
(66, 'KDS - bladensoul_fichiers/analytics.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:19:31'),
(67, 'KDS - bladensoul_fichiers/fingerprint.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:19:31'),
(68, 'KDS - bladensoul_fichiers/jquery-1.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:19:31'),
(69, 'KDS - bladensoul_fichiers/jwplayer.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:19:31'),
(70, 'KDS - bladensoul_fichiers/logo_bnsw.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:19:31'),
(71, 'KDS - bladensoul_fichiers/logo_jv_white.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:19:31'),
(72, 'KDS - bladensoul_fichiers/logo_ncsoft.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:19:31'),
(73, 'KDS - bladensoul_fichiers/main.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:19:31'),
(74, 'KDS - bladensoul_fichiers/nr-892.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:19:31'),
(75, 'KDS - bladensoul_fichiers/reset.css', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:19:31'),
(76, 'KDS - bladensoul_fichiers/style.css', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:19:31'),
(77, 'KDS - bladensoul.html', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:18'),
(78, 'KDS - bladensoul_fichiers/', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:18'),
(79, 'KDS - bladensoul_fichiers/WBIframeHandlerClient.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:18'),
(80, 'KDS - bladensoul_fichiers/analytics.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:18'),
(81, 'KDS - bladensoul_fichiers/fingerprint.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:18'),
(82, 'KDS - bladensoul_fichiers/jquery-1.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:18'),
(83, 'KDS - bladensoul_fichiers/jwplayer.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:18'),
(84, 'KDS - bladensoul_fichiers/logo_bnsw.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:18'),
(85, 'KDS - bladensoul_fichiers/logo_jv_white.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:18'),
(86, 'KDS - bladensoul_fichiers/logo_ncsoft.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:18'),
(87, 'KDS - bladensoul_fichiers/main.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:18'),
(88, 'KDS - bladensoul_fichiers/nr-892.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:18'),
(89, 'KDS - bladensoul_fichiers/reset.css', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:18'),
(90, 'KDS - bladensoul_fichiers/style.css', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:18'),
(91, 'KDS - bladensoul.html', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:28'),
(92, 'KDS - bladensoul_fichiers/', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:28'),
(93, 'KDS - bladensoul_fichiers/WBIframeHandlerClient.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:28'),
(94, 'KDS - bladensoul_fichiers/analytics.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:28'),
(95, 'KDS - bladensoul_fichiers/fingerprint.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:28'),
(96, 'KDS - bladensoul_fichiers/jquery-1.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:28'),
(97, 'KDS - bladensoul_fichiers/jwplayer.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:28'),
(98, 'KDS - bladensoul_fichiers/logo_bnsw.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:28'),
(99, 'KDS - bladensoul_fichiers/logo_jv_white.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:28'),
(100, 'KDS - bladensoul_fichiers/logo_ncsoft.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:28'),
(101, 'KDS - bladensoul_fichiers/main.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:28'),
(102, 'KDS - bladensoul_fichiers/nr-892.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:28'),
(103, 'KDS - bladensoul_fichiers/reset.css', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:28'),
(104, 'KDS - bladensoul_fichiers/style.css', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:22:28'),
(105, 'KDS - bladensoul.html', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:30:39'),
(106, 'KDS - bladensoul_fichiers', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', NULL, 1, 0, NULL, '2016-08-13 02:30:39'),
(107, 'WBIframeHandlerClient.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', NULL, 1, 0, NULL, '2016-08-13 02:30:39'),
(108, 'analytics.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', NULL, 1, 0, NULL, '2016-08-13 02:30:39'),
(109, 'fingerprint.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', NULL, 1, 0, NULL, '2016-08-13 02:30:39'),
(110, 'jquery-1.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', NULL, 1, 0, NULL, '2016-08-13 02:30:39'),
(111, 'jwplayer.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', NULL, 1, 0, NULL, '2016-08-13 02:30:39'),
(112, 'logo_bnsw.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', NULL, 1, 0, NULL, '2016-08-13 02:30:39'),
(113, 'logo_jv_white.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', NULL, 1, 0, NULL, '2016-08-13 02:30:39'),
(114, 'logo_ncsoft.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', NULL, 1, 0, NULL, '2016-08-13 02:30:39'),
(115, 'main.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', NULL, 1, 0, NULL, '2016-08-13 02:30:39'),
(116, 'nr-892.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', NULL, 1, 0, NULL, '2016-08-13 02:30:39'),
(117, 'reset.css', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', NULL, 1, 0, NULL, '2016-08-13 02:30:39'),
(118, 'style.css', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', NULL, 1, 0, NULL, '2016-08-13 02:30:39'),
(119, 'KDS - bladensoul.html', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', 'KDS - bladensoul.html', 1, 0, NULL, '2016-08-13 02:31:50'),
(120, 'KDS - bladensoul_fichiers', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\', 'KDS - bladensoul_fichiers', 1, 0, NULL, '2016-08-13 02:31:50'),
(121, 'WBIframeHandlerClient.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', 'WBIframeHandlerClient.js', 1, 0, NULL, '2016-08-13 02:31:50'),
(122, 'analytics.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', 'analytics.js', 1, 0, NULL, '2016-08-13 02:31:50'),
(123, 'fingerprint.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', 'fingerprint.js', 1, 0, NULL, '2016-08-13 02:31:50'),
(124, 'jquery-1.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', 'jquery-1.js', 1, 0, NULL, '2016-08-13 02:31:50'),
(125, 'jwplayer.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', 'jwplayer.js', 1, 0, NULL, '2016-08-13 02:31:50'),
(126, 'logo_bnsw.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', 'logo_bnsw.png', 1, 0, NULL, '2016-08-13 02:31:50'),
(127, 'logo_jv_white.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', 'logo_jv_white.png', 1, 0, NULL, '2016-08-13 02:31:50'),
(128, 'logo_ncsoft.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', 'logo_ncsoft.png', 1, 0, NULL, '2016-08-13 02:31:50'),
(129, 'main.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', 'main.js', 1, 0, NULL, '2016-08-13 02:31:50'),
(130, 'nr-892.js', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', 'nr-892.js', 1, 0, NULL, '2016-08-13 02:31:50'),
(131, 'reset.css', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', 'reset.css', 1, 0, NULL, '2016-08-13 02:31:50'),
(132, 'style.css', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\KDS - bladensoul.zip\\KDS - bladensoul_fichiers\\', 'style.css', 1, 0, NULL, '2016-08-13 02:31:50'),
(133, 'ouaf_57ae6da2cd4d8.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 02:45:22'),
(134, 'ouaf_57ae6da2f1178.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 02:45:22'),
(135, 'ouaf_57af0e98ed36e.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 14:12:08'),
(136, 'ouaf_57af1749d63bd.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 14:49:13'),
(137, 'ouaf_57af177fbb766.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 14:50:07'),
(138, 'ouaf_57af1792471df.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-13 14:50:26'),
(139, 'ouaf_57afa5beef184.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-14 00:57:02'),
(140, 'ouaf_57afa5dbe3409.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-14 00:57:31'),
(141, 'ouaf_57afa60790dcb.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-14 00:58:15'),
(142, 'ouaf_57afa61838eb0.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-14 00:58:32'),
(143, 'ouaf_57afa63ccbd88.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-14 00:59:08'),
(144, 'ouaf_57afa852f1704.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-14 01:08:02'),
(152, 'ouaf_57afb7eb315fe.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'ouaf.jpeg', 1, 0, NULL, '2016-08-14 02:14:35'),
(153, 'drow_57afb91399e9d.jpg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\3\\', 'test', 1, 0, NULL, '2016-08-14 02:19:31'),
(154, 'ouaf_57bd8a8f8a750.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Concours\\1\\', 'ouaf.jpeg', 1, 0, NULL, '2016-08-24 13:52:47'),
(155, 'ouaf_57bd8a8fb7535.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Concours\\1\\', 'ouaf.jpeg', 1, 0, NULL, '2016-08-24 13:52:47'),
(156, 'ouaf_57bd8a9cbe87f.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Concours\\1\\', 'ouaf.jpeg', 1, 0, NULL, '2016-08-24 13:53:00'),
(157, '57bde844ac83d.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\temp', 'ouaf.jpeg', 0, 0, NULL, '2016-08-24 20:32:36'),
(158, '57bde90780ada.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\temp', 'ouaf.jpeg', 0, 0, NULL, '2016-08-24 20:35:51'),
(159, '57bde985de3c8.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\temp', 'ouaf.jpeg', 0, 0, NULL, '2016-08-24 20:37:57'),
(160, '57bdea3608d0d.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\temp', 'ouaf.jpeg', 0, 0, NULL, '2016-08-24 20:40:54'),
(161, '57bdeac8ae63f.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\temp', 'ouaf.jpeg', 0, 0, NULL, '2016-08-24 20:43:20'),
(162, '57bdeb143b3ca.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\temp', 'ouaf.jpeg', 0, 0, NULL, '2016-08-24 20:44:36'),
(163, '57bdeb5c2a854.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\Croped\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-24 20:45:48'),
(164, '57bea5df1bdb8.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\event_img\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-25 10:01:35'),
(165, '57bea675b19b2.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\event_img\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-25 10:04:05'),
(167, '57bea931d2159.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\event_img\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-25 10:15:45'),
(168, '57bea940b6bfa.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\Croped\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-25 10:16:00'),
(174, '57beaf4302d02.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\Croped\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-25 10:41:39'),
(175, '57beaf507f531.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\Croped\\', 'bdd.png', 0, 0, NULL, '2016-08-25 10:41:52'),
(176, '57beb1046745f.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\Croped\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-25 10:49:08'),
(177, '57beb12664597.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\Croped\\', 'bdd.png', 0, 0, NULL, '2016-08-25 10:49:42'),
(178, '57bf098c59481.jpg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\Croped\\', '25232.jpg', 0, 0, NULL, '2016-08-25 17:06:52'),
(179, '57bf09b652eab.jpg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\Croped\\', 'ski-free-2016-welcome.jpg', 0, 0, NULL, '2016-08-25 17:07:34'),
(180, '57bf09c198aea.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\Croped\\', 'zenit-ski-racing-schule.jpeg', 0, 0, NULL, '2016-08-25 17:07:45'),
(181, '57bf09ce687c9.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\Croped\\', 'skier-glenshee-centre-ski.jpeg', 0, 0, NULL, '2016-08-25 17:07:58'),
(182, '57bf0c0ad26b6.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\Croped\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-25 17:17:30'),
(183, '57bf0d0f96867.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\temp', 'ouaf.jpeg', 0, 0, NULL, '2016-08-25 17:21:51'),
(184, '57bf0d364b1da.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\temp', 'ouaf.jpeg', 0, 0, NULL, '2016-08-25 17:22:30'),
(185, '57bf0d952edfb.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\temp', 'ouaf.jpeg', 0, 0, NULL, '2016-08-25 17:24:05'),
(186, '57bf0d9eef5f9.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\temp', 'ouaf.jpeg', 0, 0, NULL, '2016-08-25 17:24:14'),
(187, '57bf0de1a7160.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\temp', 'data.png', 0, 0, NULL, '2016-08-25 17:25:21'),
(188, '57bf0e2bea645.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\Croped\\', 'ouaf.jpeg', 0, 0, NULL, '2016-08-25 17:26:35'),
(189, 'ouaf_57c0541823d80.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\News\\Img\\', 'ouaf.jpeg', 1, 0, NULL, '2016-08-26 16:37:12'),
(190, 'ouaf_57c0546b49e5e.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\News\\Img\\', 'ouaf.jpeg', 1, 0, NULL, '2016-08-26 16:38:35'),
(191, 'ouaf_57c0565573174.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\News\\Img\\', 'ouaf.jpeg', 1, 0, NULL, '2016-08-26 16:46:45'),
(192, 'ouaf_57c05b7e8252c.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\News\\Img\\', 'ouaf.jpeg', 1, 0, NULL, '2016-08-26 17:08:46'),
(193, 'ouaf_57c05eff66468.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\News\\Img\\', 'ouaf.jpeg', 1, 0, NULL, '2016-08-26 17:23:43'),
(194, 'data_57c05f356adc1.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\News\\Img\\', 'data.png', 1, 0, NULL, '2016-08-26 17:24:37'),
(195, 'Capture du 2016-04-01 14:59:20_57c05f8aacb76.png', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\News\\Img\\', 'Capture du 2016-04-01 14:59:20.png', 1, 0, NULL, '2016-08-26 17:26:02'),
(196, '57c09d6a1f739.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Image\\event_img\\', 'ouaf.jpeg', 1, 0, NULL, '2016-08-26 21:50:02'),
(197, 'ouaf_57c05b7e8252c_57c09e54c78d6.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\5\\', 'ouaf_57c05b7e8252c.jpeg', 1, 0, NULL, '2016-08-26 21:53:56'),
(198, 'ouaf_57c0541823d80_57c09e54e8dcd.jpeg', 'D:\\wamp64\\www\\prograndson\\prograndson\\root\\Upload\\Galerie\\5\\', 'ouaf_57c0541823d80.jpeg', 1, 0, NULL, '2016-08-26 21:53:56');

-- --------------------------------------------------------

--
-- Structure de la table `galerie_concours_result`
--

CREATE TABLE `galerie_concours_result` (
  `id` int(11) NOT NULL,
  `rang` int(11) DEFAULT NULL,
  `galerie_main_id` int(11) NOT NULL,
  `galerie_main_file_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `galerie_concours_result`
--

INSERT INTO `galerie_concours_result` (`id`, `rang`, `galerie_main_id`, `galerie_main_file_id`) VALUES
(6, 1, 1, 12),
(8, 2, 1, 13);

-- --------------------------------------------------------

--
-- Structure de la table `galerie_groupe`
--

CREATE TABLE `galerie_groupe` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `galerie_main`
--

CREATE TABLE `galerie_main` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `date_crea` datetime DEFAULT NULL,
  `visible` tinyint(1) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `description` text,
  `concours` tinyint(1) DEFAULT '0',
  `show_result` tinyint(1) DEFAULT '0',
  `date_result` datetime DEFAULT NULL,
  `date_fin` datetime NOT NULL,
  `date_deb` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `galerie_main`
--

INSERT INTO `galerie_main` (`id`, `nom`, `date_crea`, `visible`, `user_id`, `parent_id`, `description`, `concours`, `show_result`, `date_result`, `date_fin`, `date_deb`) VALUES
(1, 'Test de concours', '2016-04-01 00:00:00', 1, 1, 0, '<p>un super <a href="http://hotmail.com" target="_blank">concours</a> avec un prix &agrave; la clef</p>\r\n<ul>\r\n<li>1er prix: <strong>500.- CHF</strong></li>\r\n<li>2ème au 20ème prix: 100.- CHF</li>\r\n</ul>\r\n<p>&nbsp;</p>', 1, 1, '2016-08-26 21:52:59', '2016-12-31 21:52:59', '2016-01-01 21:52:59'),
(3, 'test 23', NULL, 1, 0, 0, '<p>Une jolie galerie représentant ce que <strong>vous</strong> voulez</p>', 0, 0, NULL, '2016-08-14 18:13:38', '2016-08-14 18:13:38'),
(4, 'second concours', '2016-08-15 16:49:43', NULL, 0, 0, NULL, 1, 1, '2016-08-17 00:53:07', '2016-08-31 00:53:07', '2016-08-09 00:53:07'),
(5, 'gkjfdlgkjfdgld', '2016-08-26 21:53:33', 1, 1, 0, NULL, 0, 0, NULL, '2016-08-26 21:53:33', '2016-08-26 21:53:33');

-- --------------------------------------------------------

--
-- Structure de la table `galerie_main_file`
--

CREATE TABLE `galerie_main_file` (
  `id` int(11) NOT NULL,
  `galerie_main_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `accepted` tinyint(1) DEFAULT NULL,
  `nom` varchar(150) NOT NULL,
  `galerie_groupe_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `galerie_main_file`
--

INSERT INTO `galerie_main_file` (`id`, `galerie_main_id`, `file_id`, `accepted`, `nom`, `galerie_groupe_id`) VALUES
(2, 1, 1, 0, '', 0),
(10, 3, 152, NULL, '', 0),
(11, 3, 153, NULL, '', 0),
(12, 1, 154, 1, '', 0),
(13, 1, 156, 1, '', 0),
(14, 5, 197, NULL, '', 0);

-- --------------------------------------------------------

--
-- Structure de la table `galerie_visite`
--

CREATE TABLE `galerie_visite` (
  `id` int(11) NOT NULL,
  `date_visite` datetime DEFAULT NULL,
  `ip_adresse` varchar(25) DEFAULT NULL,
  `galerie_main_file_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `galerie_visite`
--

INSERT INTO `galerie_visite` (`id`, `date_visite`, `ip_adresse`, `galerie_main_file_id`) VALUES
(1, '2016-08-15 02:05:04', '127.0.0.1', 11),
(2, '2016-08-15 02:05:05', '127.0.0.1', 10),
(3, '2016-08-15 15:47:12', '127.0.0.1', 11),
(4, '2016-08-15 15:47:19', '127.0.0.1', 10),
(5, '2016-08-24 16:06:58', '127.0.0.1', 12),
(6, '2016-08-24 16:09:14', '127.0.0.1', 13),
(7, '2016-08-26 21:46:50', '127.0.0.1', 12),
(8, '2016-08-26 21:46:52', '127.0.0.1', 13),
(9, '2016-09-21 14:07:06', '::1', 12),
(10, '2016-09-21 14:37:29', '::1', 13);

-- --------------------------------------------------------

--
-- Structure de la table `galerie_vote`
--

CREATE TABLE `galerie_vote` (
  `id` int(11) NOT NULL,
  `note_total` int(11) DEFAULT NULL,
  `ip_adresse` varchar(25) DEFAULT NULL,
  `date_vote` datetime DEFAULT NULL,
  `galerie_main_file_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `galerie_vote`
--

INSERT INTO `galerie_vote` (`id`, `note_total`, `ip_adresse`, `date_vote`, `galerie_main_file_id`) VALUES
(1, 5, '127.0.0.1', '2016-08-15 02:44:14', 11),
(2, 4, '127.0.0.1', '2016-08-15 02:44:22', 10),
(3, 4, '127.0.0.1', '2016-08-26 21:47:00', 13);

-- --------------------------------------------------------

--
-- Structure de la table `groupe`
--

CREATE TABLE `groupe` (
  `id` int(11) NOT NULL,
  `txt_cst` varchar(150) NOT NULL,
  `def_val` varchar(150) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `parent_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `groupe_access`
--

CREATE TABLE `groupe_access` (
  `id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `groupe_id` int(11) NOT NULL,
  `admin_lvl` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `language`
--

CREATE TABLE `language` (
  `id` int(11) NOT NULL,
  `clef` varchar(100) NOT NULL,
  `lang` varchar(15) NOT NULL,
  `valeur` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `news_main`
--

CREATE TABLE `news_main` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(45) NOT NULL,
  `date_crea` datetime NOT NULL,
  `visible` tinyint(4) NOT NULL,
  `txt_content` text NOT NULL,
  `file_id` int(11) DEFAULT NULL,
  `date_for` datetime NOT NULL,
  `chapeau` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `news_main`
--

INSERT INTO `news_main` (`id`, `user_id`, `title`, `date_crea`, `visible`, `txt_content`, `file_id`, `date_for`, `chapeau`) VALUES
(1, 1, 'Ma news', '2016-04-21 00:00:00', 0, '<p>texte de contenu</p>', 1, '2016-04-20 00:00:00', 'Text de description de base de la news'),
(2, 1, 'Ma news num&eacute;ro 2', '2016-04-13 14:39:15', 1, '&lt;p&gt;du texte de contenu sans aucun doute super important et impï¿½ratif pour pouvoir comprendre le truc... sinon &#039;est trï¿½s triste.&lt;/p&gt;', 2, '2016-04-21 00:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ac volutpat tellus. Etiam pharetra placerat sapien, suscipit consectetur metus. Cras nulla massa, eleifend placerat leo nec, rhoncus fermentum nisl. Vivamus aliquam erat magna, in ornare elit elementum id. Pellentesque ultricies a tellus at hendrerit. Ut in purus vel orci malesuada viverra. Vivamus rutrum nec arcu ac fermentum. Nulla nec laoreet leo. Donec sit amet suscipit leo, in dictum leo. Suspendisse tincidunt eleifend ullamcorper. Nulla tincidunt quam in orci rhoncus, ac pellentesque neque aliquam. Vestibulum pulvinar commodo mi, vitae consectetur odio venenatis vel. Sed non dignissim odio. Aliquam lobortis rhoncus fringilla.\r\n\r\nInteger ante velit, porta nec porttitor vel, vehicula in sapien. Vivamus nec justo sed mi consectetur tincidunt. Vivamus eleifend orci eu lacus consectetur ullamcorper. Ut ut commodo ante. Donec id ornare odio. Sed consectetur lacinia ipsum. Ut vulputate pretium libero, eu hendrerit lectus facilisis vel. Nulla facilisi. Vivamus accumsan nunc at leo lobortis pretium pellentesque quis mi. In ac sagittis ipsum, malesuada viverra velit. Pellentesque at ligula suscipit, malesuada magna in, accumsan odio. Etiam odio augue, condimentum ut sapien maximus, accumsan elementum mi. Cras volutpat mi eu tortor blandit, ut volutpat neque dapibus. Pellentesque tempor lobortis quam, eget scelerisque nibh.\r\n\r\nDuis tincidunt orci et arcu efficitur bibendum. Duis cursus ex in sagittis porttitor. Fusce eu cursus arcu. Nullam quis dolor feugiat est bibendum ultrices in ac enim. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Quisque tempus ut risus eget ultrices. Donec maximus, eros quis sollicitudin ullamcorper, purus dui interdum massa, non facilisis velit nisi sed neque. Pellentesque eu mauris consequat, fermentum diam ac, hendrerit est. Donec nec leo purus. Curabitur vitae nulla laoreet, laoreet tortor ut, eleifend urna. In eu enim a velit sagittis vulputate a eu nibh.\r\n\r\nMorbi at lacus ex. Etiam justo dui, finibus in ultricies id, consequat a ipsum. Sed et semper sapien, eget ullamcorper justo. Duis dapibus urna eget ligula fermentum, venenatis ultrices turpis porttitor. Nam lectus ipsum, tempor a nibh a, rhoncus ultricies orci. Etiam at tempor leo. Fusce imperdiet massa vitae ornare pellentesque. Pellentesque fringilla elementum orci vel posuere.\r\n\r\nProin blandit lacinia dapibus. Morbi auctor neque euismod suscipit aliquam. Sed quis auctor diam. Nunc vitae dolor egestas, scelerisque neque in, lacinia neque. Fusce urna ligula, vulputate in magna et, interdum ornare velit. Sed venenatis, arcu eu venenatis aliquam, lorem massa aliquam metus, a lacinia dolor felis pellentesque massa. Phasellus feugiat nulla nec finibus ullamcorper. Fusce id faucibus ligula, a luctus mi. '),
(3, 1, 'fgdfgdgfd', '2016-08-26 21:50:54', 1, '', NULL, '2016-08-26 14:32:51', ''),
(4, 1, 'gfdgdsgdsgds', '2016-08-02 17:41:31', 1, '<p>Un peu de texte que l&#039;on peut au moins formatter un minimum</p>\r\n<ul>\r\n<li>1</li>\r\n<li>2</li>\r\n<li>3</li>\r\n<li>4</li>\r\n</ul>\r\n<p>Et aussi <strong>du texte en gras</strong> et m&ecirc;me <a href="http://www.google.ch">un liens</a></p>', 195, '2016-08-26 15:57:32', ' fsd fdsfds fds');

-- --------------------------------------------------------

--
-- Structure de la table `presentation_access`
--

CREATE TABLE `presentation_access` (
  `id` int(11) NOT NULL,
  `groupe_id` int(11) NOT NULL,
  `presentation_main_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `presentation_categorie`
--

CREATE TABLE `presentation_categorie` (
  `id` int(11) NOT NULL COMMENT '	',
  `cst_var` varchar(50) DEFAULT NULL,
  `default_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `presentation_categorie_main`
--

CREATE TABLE `presentation_categorie_main` (
  `id` int(11) NOT NULL,
  `presentation_main_id` int(11) NOT NULL,
  `presentation_categorie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `presentation_date`
--

CREATE TABLE `presentation_date` (
  `id` int(11) NOT NULL,
  `val` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `presentation_date`
--

INSERT INTO `presentation_date` (`id`, `val`) VALUES
(1, '2016-08-02 11:20:26'),
(2, '2016-08-18 11:15:39'),
(3, '2016-08-09 14:40:27'),
(4, '2016-08-27 14:40:28'),
(5, '2016-12-18 17:04:00'),
(6, '2016-12-22 17:03:54');

-- --------------------------------------------------------

--
-- Structure de la table `presentation_item`
--

CREATE TABLE `presentation_item` (
  `id` int(11) NOT NULL,
  `presentation_main_id` int(11) DEFAULT NULL,
  `val` varchar(150) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `item` enum('elem','text','img','list','date') CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `liste_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `presentation_item`
--

INSERT INTO `presentation_item` (`id`, `presentation_main_id`, `val`, `item`, `liste_id`, `name`) VALUES
(1, 4, '1', 'date', NULL, 'date_event'),
(2, 4, '2', 'date', NULL, 'end_date'),
(3, 4, '1', 'text', NULL, 'base_txt'),
(4, 4, '163', 'img', NULL, 'cover_img'),
(5, 4, NULL, 'list', NULL, 'list_information'),
(6, 4, '2', 'text', 5, 'head'),
(7, 4, NULL, 'list', 5, 'tail'),
(8, 4, '167', 'img', 7, 'head'),
(9, 4, NULL, 'list', 7, 'tail'),
(16, 4, NULL, 'list', 9, 'head'),
(17, 4, '174', 'img', 16, 'galerie_item'),
(18, 4, '175', 'img', 16, 'galerie_item'),
(19, 4, NULL, 'list', 9, 'tail'),
(21, 4, NULL, 'list', 19, 'head'),
(22, 4, '176', 'img', 21, 'galerie_item'),
(23, 4, '177', 'img', 21, 'galerie_item'),
(24, 5, '3', 'date', NULL, 'date_event'),
(25, 5, '4', 'date', NULL, 'end_date'),
(26, 5, '3', 'text', NULL, 'base_txt'),
(27, 6, '5', 'date', NULL, 'date_event'),
(28, 6, '6', 'date', NULL, 'end_date'),
(29, 6, '4', 'text', NULL, 'base_txt'),
(30, 6, '178', 'img', NULL, 'cover_img'),
(31, 6, NULL, 'list', NULL, 'list_information'),
(32, 6, NULL, 'list', 31, 'head'),
(33, 6, '179', 'img', 32, 'galerie_item'),
(34, 6, '180', 'img', 32, 'galerie_item'),
(35, 6, '181', 'img', 32, 'galerie_item'),
(36, 6, '182', 'img', 32, 'galerie_item'),
(37, 6, '188', 'img', 32, 'galerie_item'),
(44, NULL, NULL, 'list', NULL, 'liste_liens'),
(45, NULL, 'http://www.google.ch', 'elem', 44, 'liens_url'),
(46, NULL, 'google', 'elem', 44, 'liens_name'),
(47, 6, NULL, 'list', 31, 'tail'),
(48, 6, '5', 'text', 47, 'head'),
(49, 6, NULL, 'list', 47, 'tail'),
(50, 6, '196', 'img', 49, 'head');

-- --------------------------------------------------------

--
-- Structure de la table `presentation_main`
--

CREATE TABLE `presentation_main` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `date_crea` datetime NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `date_fin` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `presentation_main`
--

INSERT INTO `presentation_main` (`id`, `nom`, `type`, `date_crea`, `published`, `date_fin`, `deleted`) VALUES
(1, 'test', 0, '2016-08-24 19:40:51', 0, NULL, 0),
(2, 'toto', 0, '2016-08-24 19:41:35', 0, NULL, 0),
(3, 'fdsf', 0, '2016-08-24 19:44:36', 0, NULL, 0),
(4, 'test fds fsd', 0, '2016-08-24 19:45:35', 0, NULL, 0),
(5, 'test', 1, '2016-08-25 14:34:04', 1, NULL, 0),
(6, 'sortie &agrave; ski', 0, '2016-08-25 17:03:38', 1, NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `presentation_payement`
--

CREATE TABLE `presentation_payement` (
  `id` int(11) NOT NULL COMMENT '		',
  `date_payement` datetime DEFAULT NULL,
  `presentation_main_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `presentation_sended_mail`
--

CREATE TABLE `presentation_sended_mail` (
  `id` int(11) NOT NULL,
  `date_sent` datetime DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `presentation_main_id` int(11) DEFAULT NULL,
  `used_mail` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `presentation_texte`
--

CREATE TABLE `presentation_texte` (
  `id` int(11) NOT NULL,
  `val` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `presentation_texte`
--

INSERT INTO `presentation_texte` (`id`, `val`) VALUES
(1, '<p>fds fds fsd</p>\n<p>&nbsp;fds</p>\n<p>fdsfds</p>\n<p>fdsfsd</p>\n<p>fsd</p>'),
(2, ''),
(3, '<p>fds fds</p>\n<p>&nbsp;fds</p>\n<p>&nbsp;fds</p>\n<p>&nbsp;fds</p>\n<p>&nbsp;fds</p>'),
(4, '<p>Grands sortie &agrave; ski pour faire la f&ecirc;te</p>\n<p>&nbsp;</p>\n<p>Pensez bien &agrave; prendre:</p>\n<ul>\n<li>Vos ski</li>\n<li>Vos bonnets</li>\n<li>Des protections</li>\n</ul>\n<p>Voici quelques images de l&#039;année passée</p>'),
(5, '<p>gfd</p>\n<p>gfd</p>\n<p>gfd</p>\n<p>g</p>\n<p>&nbsp;</p>\n<p>g</p>\n<p>fdgfdgfdgfd</p>');

-- --------------------------------------------------------

--
-- Structure de la table `routes`
--

CREATE TABLE `routes` (
  `id` int(11) NOT NULL,
  `url` varchar(100) NOT NULL,
  `module` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `vars` varchar(150) NOT NULL,
  `admin_lvl` int(11) NOT NULL,
  `changeable` tinyint(1) NOT NULL DEFAULT '0',
  `page_type` enum('html','xml','json','pdf','file','img') NOT NULL DEFAULT 'html',
  `title` varchar(150) DEFAULT NULL,
  `description` text,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `on_menu` tinyint(1) NOT NULL DEFAULT '0',
  `only_dyn` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `date_crea` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `routes`
--

INSERT INTO `routes` (`id`, `url`, `module`, `action`, `vars`, `admin_lvl`, `changeable`, `page_type`, `title`, `description`, `parent_id`, `on_menu`, `only_dyn`, `user_id`, `date_crea`) VALUES
(1, '/', 'News', 'index', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(2, '/News/liste.html', 'News', 'liste', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(3, '/admin.html', 'Admin', 'index', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(4, '/News/([0-9]+)/', 'News', 'show', 'news_id', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(5, '/Concours/([0-9]+)/result.html', 'Galerie', 'result', 'main_id', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(6, '/loadActivite.html', 'Presentation', 'loadActivite', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(7, '/Presentation/([0-9]+)/show.html', 'Presentation', 'show', 'presentation_id', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(8, '/galerie.html', 'Galerie', 'index', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(9, '/galerie-([0-9]+).html', 'Galerie', 'index', 'gal_id', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(10, '/changeMenuStatus.html', 'Accueil', 'changeMenuStatus', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(11, '/Admin/Galerie/getList.html', 'Galerie', 'getList', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(12, '/Admin/Galerie/getList-([0-9]+).html', 'Galerie', 'getList', 'gal_id', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(13, '/Admin/Galerie/send.html', 'Galerie', 'send', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(14, '/Admin/Galerie/det-([0-9]+).html', 'Galerie', 'det', 'id', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(15, '/Admin/Galerie/modif-([0-9]+).html', 'Galerie', 'modif', 'id', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(27, '/activites.html', 'Presentation', 'liste', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(28, '/contact.html', 'Contact', 'index', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(29, '/galerie.bkp.html', 'Accueil', 'galerie', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(30, '/resultats.html', 'Accueil', 'resultats', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(31, '/boutique.html', 'Accueil', 'boutique', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(32, '/divers.html', 'Accueil', 'divers', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(33, '/liens.html', 'Presentation', 'liens', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(34, '/rapports.html', 'Accueil', 'rapports', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(35, '/statuts.html', 'Accueil', 'statuts', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(36, '/agenda.html', 'Accueil', 'agenda', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(37, '/Concours/cg.html', 'Galerie', 'cg', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(38, '/Img/std-([0-9]+).jpg', 'Upload', 'getFile', 'file_id', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(39, '/Img/min-([0-9]+).jpg', 'Upload', 'getMinFile', 'file_id', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(40, '/Upload/upload.html', 'Upload', 'upload', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(41, '/Galerie/addImage.html', 'Galerie', 'addImage', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(42, '/Admin/Galerie/changeImgName-([0-9]+).html', 'Galerie', 'changeImgName', 'fileId', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(43, '/Admin/Galerie/removeImg-([0-9]+).html', 'Galerie', 'removeImg', 'fileId', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(44, '/Galerie/showImg-([0-9]+).html', 'Galerie', 'showImg', 'imgId', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(45, '/Galerie/vote-([0-9]+).html', 'Galerie', 'vote', '\r\nimgId', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(46, '/Admin/Concours/getList.html', 'Galerie', 'getList', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(47, '/Admin/Concours/modif-([0-9]+).html', 'Galerie', 'modif', 'id', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(48, '/Concours/participer.html', 'Galerie', 'participer', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(49, '/Connexion/', 'Connexion', 'index', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(50, '/postConnexion/', 'Connexion', 'postConnexion', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(51, '/User/sendParticipant.html', 'User', 'send', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(52, '/User/Valid/([0-9]+)/([0-9a-zA-Z]+)/', 'User', 'validate', 'user_id,user_code', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(53, '/Admin/Galerie/rankSelect.html', 'Galerie', 'rankSelect', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(54, '/Admin/Galerie/validSelect.html', 'Galerie', 'validSelect', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(55, '/Concours/result.html', 'Galerie', 'result', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(56, '/Contact/send.html', 'Contact', 'send', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(57, '/Activites/', 'Presentation', 'activites', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(58, '/Actualite/new.html', 'Presentation', 'addEvent', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(72, '/Event/show-([0-9]+).html', 'Presentation', 'showEvent', 'pres_id', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(73, '/Actualite/show-([0-9]+).html', 'Presentation', 'showEvent', 'pres_id', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(74, '/Event/sendCouv-([0-9]+)-([0-9]+).html', 'Presentation', 'sendCouv', 'file_id,pres_id', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(75, '/Event/addElem-([0-9]+).html', 'Presentation', 'addEventElem', 'pres_id', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(76, '/Event/([0-9]+)/send.html', 'Presentation', 'sendEvent', 'event_id', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(77, '/Event/([0-9]+)/', 'Presentation', 'newEvent', 'event_id', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(78, '/Actualite/([0-9]+)/send.html', 'Presentation', 'sendEvent', 'event_id', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(79, '/Actualite/([0-9]+)/', 'Presentation', 'newEvent', 'event_id', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(80, '/Event/send.html', 'Presentation', 'sendEvent', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(81, '/Event/new.html', 'Presentation', 'addEvent', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(82, '/Event/', 'Presentation', 'event', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(83, '/Actualite/send.html', 'Presentation', 'sendEvent', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(84, '/Actualite/', 'Presentation', 'event', '', 0, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(85, '/Presentation/modifItem-([0-9]+).php', 'Presentation', 'modifItem', 'pres_id', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(86, '/Presentation/changeDesc-([0-9]+).html', 'Presentation', 'changeDesc', 'text_id', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(87, '/Upload/uploadImg.php', 'Upload', 'uploadImg', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(88, '/Upload/cropImg.php', 'Upload', 'cropImg', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(90, '/Presentation/sendImg-([0-9]+).html', 'Presentation', 'sendImg', 'item_id', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(91, '/Presentation/sendGalerie-([0-9]+)-([0-9]+)-([0-9]+).php', 'Presentation', 'sendGalerie', 'file_id,pres_id,galerie_id', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(92, '/Presentation/removeItem-([0-9]+).html', 'Presentation', 'removeItem', 'item_id', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(93, '/sendLiens.html', 'Presentation', 'sendLiens', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(94, '/removeLiens.html', 'Presentation', 'removeLiens', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(95, '/Admin/News/', 'News', 'admin', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(96, '/Admin/News/changeVisibility.html', 'News', 'changeVisibility', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(97, '/Admin/News/delete.html', 'News', 'delete', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(98, '/Admin/News/modif.html', 'News', 'modif', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(99, '/Admin/News/add.html', 'News', 'add', '', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL),
(100, '/Admin/News/modif-([0-9]+).html', 'News', 'modif', 'id', 1, 0, 'html', NULL, NULL, 0, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `routes_def_val`
--

CREATE TABLE `routes_def_val` (
  `id` int(11) NOT NULL,
  `route_key` varchar(50) NOT NULL,
  `route_val` varchar(50) NOT NULL,
  `force_val` tinyint(4) NOT NULL DEFAULT '0',
  `route_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `routes_def_val`
--

INSERT INTO `routes_def_val` (`id`, `route_key`, `route_val`, `force_val`, `route_id`) VALUES
(1, 'concours', '1', 0, 46),
(2, 'concours', '1', 0, 47),
(3, 'userType', '0', 0, 51),
(4, 'actualite', '1', 0, 58),
(5, 'actualite', '1', 0, 73),
(6, 'actualite', '1', 0, 78),
(7, 'actualite', '1', 0, 79),
(8, 'actualite', '1', 0, 83),
(9, 'actualite', '1', 0, 84);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `language` varchar(10) NOT NULL DEFAULT 'fr',
  `email` varchar(150) NOT NULL,
  `inscr_date` datetime NOT NULL,
  `civilite` enum('M.','Mme.','Mlle.') NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `no_tel` varchar(100) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `reference_user` int(11) NOT NULL,
  `fonction` varchar(100) NOT NULL,
  `fax` varchar(100) NOT NULL,
  `skype` varchar(100) NOT NULL,
  `validation_code` char(15) NOT NULL,
  `validated` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `login`, `password`, `language`, `email`, `inscr_date`, `civilite`, `prenom`, `nom`, `no_tel`, `admin`, `reference_user`, `fonction`, `fax`, `skype`, `validation_code`, `validated`) VALUES
(1, 'admin', '$2a$07$3hxP2Txwr0wWw7LnVmnz0ekhV6MIF/aOiuAQ4yPOU/XLVVd3iJmn2', 'fr', 'vz@paragp.ch', '2014-04-02 17:01:38', 'M.', 'Vincent 1', 'Zellweger 1', '-', 10, 0, 'Administrateur', '', '', '', 1),
(2, 'test', '$2a$07$ZTF9uJybfHjhDh4o4kJ0POlNorcBtMVH43FcT3Ap9OdAPrSoVXQ4K', 'fr', 'test@test.com', '2016-08-23 16:59:52', 'M.', 'test', 'test', '', 0, -1, '', '', '', '', 1),
(3, 'test2', '$2a$07$ZoREFzPhwmJPzDSjmJdo8eXsb/E7Hg6N.QwhnXawgAi87RXKykjNu', 'fr', 'test@test.com', '2016-08-23 17:02:06', 'M.', 'a', 'b', '', 0, -1, '', '', '', '', 1),
(4, 'test23', '$2a$07$EAwUDXCBYmdmRpBuRTzmHOAUqOjYYCaMUfogHp9/SAkW7ZZCAwpya', 'fr', 'test@test.com', '2016-08-23 17:04:11', 'M.', 'x', 'y', '', 0, -1, '', '', '', '', 1),
(5, 'test234', '$2a$07$OCNzBm3OurOk5zvQRAZxH.2Jn8zTk2zQU.xtLjI3BXY8bMQcygWsC', 'fr', 'test@test.com', '2016-08-23 17:15:27', 'M.', 'test', 'test', '', 0, -1, '', '', '', 'w0t9D5bSx21vXlX', 1);

-- --------------------------------------------------------

--
-- Structure de la table `user_access`
--

CREATE TABLE `user_access` (
  `id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_lvl` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `user_attr`
--

CREATE TABLE `user_attr` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `user_attr` varchar(150) DEFAULT NULL,
  `user_val` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `user_groupe`
--

CREATE TABLE `user_groupe` (
  `id` int(11) NOT NULL,
  `groupe_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `user_groupe`
--

INSERT INTO `user_groupe` (`id`, `groupe_id`, `user_id`) VALUES
(1, 0, 4),
(2, 0, 5);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `adresses`
--
ALTER TABLE `adresses`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `connexion_log`
--
ALTER TABLE `connexion_log`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `document_access`
--
ALTER TABLE `document_access`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_document_access_groupe1_idx` (`groupe_id`),
  ADD KEY `fk_document_access_file1_idx` (`file_id`);

--
-- Index pour la table `dynamic_page`
--
ALTER TABLE `dynamic_page`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dynamique_page_routes1_idx` (`routes_id`);

--
-- Index pour la table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cst_name` (`cst_name`);

--
-- Index pour la table `galerie_concours_result`
--
ALTER TABLE `galerie_concours_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_galerie_concours_result_galerie_main1_idx` (`galerie_main_id`),
  ADD KEY `fk_galerie_concours_result_galerie_main_file1_idx` (`galerie_main_file_id`);

--
-- Index pour la table `galerie_groupe`
--
ALTER TABLE `galerie_groupe`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `galerie_main`
--
ALTER TABLE `galerie_main`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_galerie_main_user1_idx` (`user_id`),
  ADD KEY `fk_galerie_main_galerie_main1_idx` (`parent_id`);

--
-- Index pour la table `galerie_main_file`
--
ALTER TABLE `galerie_main_file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_galerie_main_image_galerie_main1_idx` (`galerie_main_id`),
  ADD KEY `fk_galerie_main_image_file1_idx` (`file_id`);

--
-- Index pour la table `galerie_visite`
--
ALTER TABLE `galerie_visite`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `galerie_vote`
--
ALTER TABLE `galerie_vote`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_galerie_vote_galerie_main_file1_idx` (`galerie_main_file_id`);

--
-- Index pour la table `groupe`
--
ALTER TABLE `groupe`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `groupe_access`
--
ALTER TABLE `groupe_access`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clef` (`clef`,`lang`);

--
-- Index pour la table `news_main`
--
ALTER TABLE `news_main`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_news_user1_idx` (`user_id`);

--
-- Index pour la table `presentation_access`
--
ALTER TABLE `presentation_access`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `presentation_categorie`
--
ALTER TABLE `presentation_categorie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `presentation_categorie_main`
--
ALTER TABLE `presentation_categorie_main`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `presentation_date`
--
ALTER TABLE `presentation_date`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `presentation_item`
--
ALTER TABLE `presentation_item`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `presentation_main`
--
ALTER TABLE `presentation_main`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `presentation_payement`
--
ALTER TABLE `presentation_payement`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `presentation_sended_mail`
--
ALTER TABLE `presentation_sended_mail`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `presentation_texte`
--
ALTER TABLE `presentation_texte`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `url` (`url`);

--
-- Index pour la table `routes_def_val`
--
ALTER TABLE `routes_def_val`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user_access`
--
ALTER TABLE `user_access`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user_attr`
--
ALTER TABLE `user_attr`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user_groupe`
--
ALTER TABLE `user_groupe`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `adresses`
--
ALTER TABLE `adresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `connexion_log`
--
ALTER TABLE `connexion_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT pour la table `document_access`
--
ALTER TABLE `document_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `dynamic_page`
--
ALTER TABLE `dynamic_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `file`
--
ALTER TABLE `file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;
--
-- AUTO_INCREMENT pour la table `galerie_concours_result`
--
ALTER TABLE `galerie_concours_result`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `galerie_groupe`
--
ALTER TABLE `galerie_groupe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `galerie_main`
--
ALTER TABLE `galerie_main`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `galerie_main_file`
--
ALTER TABLE `galerie_main_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT pour la table `galerie_visite`
--
ALTER TABLE `galerie_visite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pour la table `galerie_vote`
--
ALTER TABLE `galerie_vote`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `groupe`
--
ALTER TABLE `groupe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `groupe_access`
--
ALTER TABLE `groupe_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `news_main`
--
ALTER TABLE `news_main`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `presentation_access`
--
ALTER TABLE `presentation_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `presentation_categorie`
--
ALTER TABLE `presentation_categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '	';
--
-- AUTO_INCREMENT pour la table `presentation_categorie_main`
--
ALTER TABLE `presentation_categorie_main`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `presentation_date`
--
ALTER TABLE `presentation_date`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `presentation_item`
--
ALTER TABLE `presentation_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT pour la table `presentation_main`
--
ALTER TABLE `presentation_main`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `presentation_payement`
--
ALTER TABLE `presentation_payement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '		';
--
-- AUTO_INCREMENT pour la table `presentation_sended_mail`
--
ALTER TABLE `presentation_sended_mail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `presentation_texte`
--
ALTER TABLE `presentation_texte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
--
-- AUTO_INCREMENT pour la table `routes_def_val`
--
ALTER TABLE `routes_def_val`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `user_access`
--
ALTER TABLE `user_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `user_attr`
--
ALTER TABLE `user_attr`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `user_groupe`
--
ALTER TABLE `user_groupe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `galerie_concours_result`
--
ALTER TABLE `galerie_concours_result`
  ADD CONSTRAINT `fk_galerie_concours_result_galerie_main1` FOREIGN KEY (`galerie_main_id`) REFERENCES `galerie_main` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_galerie_concours_result_galerie_main_file1` FOREIGN KEY (`galerie_main_file_id`) REFERENCES `galerie_main_file` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `galerie_main_file`
--
ALTER TABLE `galerie_main_file`
  ADD CONSTRAINT `fk_galerie_main_image_file1` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_galerie_main_image_galerie_main1` FOREIGN KEY (`galerie_main_id`) REFERENCES `galerie_main` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `galerie_vote`
--
ALTER TABLE `galerie_vote`
  ADD CONSTRAINT `fk_galerie_vote_galerie_main_file1` FOREIGN KEY (`galerie_main_file_id`) REFERENCES `galerie_main_file` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
