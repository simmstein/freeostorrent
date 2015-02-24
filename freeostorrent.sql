-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 24 Février 2015 à 11:56
-- Version du serveur: 5.5.41
-- Version de PHP: 5.6.5-1~dotdeb.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `freeostorrent`
--

-- --------------------------------------------------------

--
-- Structure de la table `blog_cats`
--

CREATE TABLE IF NOT EXISTS `blog_cats` (
  `catID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catTitle` varchar(255) DEFAULT NULL,
  `catSlug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`catID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

--
-- Contenu de la table `blog_cats`
--

--
-- Structure de la table `blog_licences`
--

CREATE TABLE IF NOT EXISTS `blog_licences` (
  `licenceID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `licenceTitle` varchar(255) NOT NULL,
  `licenceSlug` varchar(255) NOT NULL,
  PRIMARY KEY (`licenceID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Contenu de la table `blog_licences`
--


--
-- Structure de la table `blog_members`
--

CREATE TABLE IF NOT EXISTS `blog_members` (
  `memberID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `pid` varchar(32) NOT NULL,
  `memberDate` datetime NOT NULL,
  `avatar` varchar(255) NOT NULL,
  PRIMARY KEY (`memberID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

--
-- Contenu de la table `blog_members`
--


--
-- Structure de la table `blog_messages`
--

CREATE TABLE IF NOT EXISTS `blog_messages` (
  `messages_id` int(11) NOT NULL AUTO_INCREMENT,
  `messages_id_expediteur` int(11) NOT NULL DEFAULT '0',
  `messages_id_destinataire` int(11) NOT NULL DEFAULT '0',
  `messages_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `messages_titre` text NOT NULL,
  `messages_message` text NOT NULL,
  `messages_lu` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`messages_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `blog_messages`
--


-- Structure de la table `blog_posts_comments`
--

CREATE TABLE IF NOT EXISTS `blog_posts_comments` (
  `cid` int(10) NOT NULL AUTO_INCREMENT,
  `cid_torrent` int(10) NOT NULL,
  `cadded` datetime NOT NULL,
  `ctext` text NOT NULL,
  `cuser` varchar(25) NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `blog_posts_comments`
--


--
-- Structure de la table `blog_posts_seo`
--

CREATE TABLE IF NOT EXISTS `blog_posts_seo` (
  `postID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `postTitle` varchar(255) DEFAULT NULL,
  `postAuthor` varchar(255) NOT NULL,
  `postSlug` varchar(255) DEFAULT NULL,
  `postDesc` text,
  `postCont` text,
  `postTaille` bigint(20) NOT NULL DEFAULT '0',
  `postDate` datetime DEFAULT NULL,
  `postTorrent` varchar(150) NOT NULL,
  `postImage` varchar(255) NOT NULL,
  `postViews` int(11) NOT NULL,
  PRIMARY KEY (`postID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

--
-- Contenu de la table `blog_posts_seo`
--

--
-- Structure de la table `blog_post_cats`
--

CREATE TABLE IF NOT EXISTS `blog_post_cats` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `postID` int(11) DEFAULT NULL,
  `catID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=275 ;

--
-- Contenu de la table `blog_post_cats`
--

--
-- Structure de la table `blog_post_licences`
--

CREATE TABLE IF NOT EXISTS `blog_post_licences` (
  `id_BPL` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `postID_BPL` int(11) NOT NULL,
  `licenceID_BPL` int(11) NOT NULL,
  PRIMARY KEY (`id_BPL`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=285 ;

--
-- Contenu de la table `blog_post_licences`
--

--
-- Structure de la table `compteur`
--

CREATE TABLE IF NOT EXISTS `compteur` (
  `ip` varchar(15) NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `compteur`
--

-- Structure de la table `connectes`
--

CREATE TABLE IF NOT EXISTS `connectes` (
  `ip` varchar(15) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `connectes`
--

--
-- Structure de la table `xbt_announce_log`
--

CREATE TABLE IF NOT EXISTS `xbt_announce_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ipa` int(10) unsigned NOT NULL,
  `port` int(11) NOT NULL,
  `event` int(11) NOT NULL,
  `info_hash` binary(20) NOT NULL,
  `peer_id` binary(20) NOT NULL,
  `downloaded` bigint(20) unsigned NOT NULL,
  `left0` bigint(20) unsigned NOT NULL,
  `uploaded` bigint(20) unsigned NOT NULL,
  `uid` int(11) NOT NULL,
  `mtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=771411 ;

--
-- Contenu de la table `xbt_announce_log`
--

--
-- Structure de la table `xbt_config`
--

CREATE TABLE IF NOT EXISTS `xbt_config` (
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `value` varchar(255) CHARACTER SET latin1 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `xbt_config`
--

INSERT INTO `xbt_config` (`name`, `value`) VALUES
('redirect_url', ''),
('query_log', '1'),
('pid_file', '/var/run/xbt_tracker.pid'),
('offline_message', ''),
('column_users_uid', 'uid'),
('column_files_seeders', 'seeders'),
('column_files_leechers', 'leechers'),
('column_files_fid', 'fid'),
('column_files_completed', 'completed'),
('write_db_interval', '15'),
('scrape_interval', '0'),
('read_db_interval', '60'),
('read_config_interval', '60'),
('clean_up_interval', '60'),
('log_scrape', '0'),
('log_announce', '1'),
('log_access', '0'),
('gzip_scrape', '1'),
('full_scrape', '0'),
('debug', '1'),
('daemon', '1'),
('anonymous_scrape', '0'),
('announce_interval', '200'),
('torrent_pass_private_key', 'fU89bPMBZpDW1ePG3TltT9F2wMa'),
('table_announce_log', 'xbt_announce_log'),
('table_files', 'xbt_files'),
('table_files_users', 'xbt_files_users'),
('table_scrape_log', 'xbt_scrape_log'),
('table_users', 'xbt_users'),
('listen_ipa', '*'),
('listen_port', 'xxxxx'),
('anonymous_announce', '0'),
('auto_register', '0');

-- --------------------------------------------------------

--
-- Structure de la table `xbt_deny_from_hosts`
--

CREATE TABLE IF NOT EXISTS `xbt_deny_from_hosts` (
  `begin` int(10) unsigned NOT NULL,
  `end` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `xbt_files`
--

CREATE TABLE IF NOT EXISTS `xbt_files` (
  `fid` int(11) NOT NULL AUTO_INCREMENT,
  `info_hash` binary(20) NOT NULL,
  `leechers` int(11) NOT NULL DEFAULT '0',
  `seeders` int(11) NOT NULL DEFAULT '0',
  `completed` int(11) NOT NULL DEFAULT '0',
  `flags` int(11) NOT NULL DEFAULT '0',
  `mtime` int(11) NOT NULL,
  `ctime` int(11) NOT NULL,
  PRIMARY KEY (`fid`),
  UNIQUE KEY `info_hash` (`info_hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

--
-- Contenu de la table `xbt_files`
--

--
-- Structure de la table `xbt_files_users`
--

CREATE TABLE IF NOT EXISTS `xbt_files_users` (
  `fid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `announced` int(11) NOT NULL,
  `completed` int(11) NOT NULL,
  `downloaded` bigint(20) unsigned NOT NULL,
  `left` bigint(20) unsigned NOT NULL,
  `uploaded` bigint(20) unsigned NOT NULL,
  `mtime` int(11) NOT NULL,
  `down_rate` int(10) unsigned NOT NULL,
  `up_rate` int(10) unsigned NOT NULL,
  UNIQUE KEY `fid` (`fid`,`uid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `xbt_files_users`
--

--
-- Structure de la table `xbt_scrape_log`
--

CREATE TABLE IF NOT EXISTS `xbt_scrape_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ipa` int(10) unsigned NOT NULL,
  `info_hash` binary(20) DEFAULT NULL,
  `uid` int(11) NOT NULL,
  `mtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=123 ;

-- --------------------------------------------------------

--
-- Structure de la table `xbt_users`
--

CREATE TABLE IF NOT EXISTS `xbt_users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `torrent_pass_version` int(11) NOT NULL DEFAULT '0',
  `downloaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `uploaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `torrent_pass` char(32) CHARACTER SET latin1 NOT NULL,
  `torrent_pass_secret` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

--
-- Contenu de la table `xbt_users`
--

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `xbt_files_users`
--
ALTER TABLE `xbt_files_users`
  ADD CONSTRAINT `xbt_files_users_ibfk_1` FOREIGN KEY (`fid`) REFERENCES `xbt_files` (`fid`) ON DELETE CASCADE,
  ADD CONSTRAINT `xbt_files_users_ibfk_2` FOREIGN KEY (`fid`) REFERENCES `xbt_files` (`fid`) ON DELETE CASCADE,
  ADD CONSTRAINT `xbt_files_users_ibfk_3` FOREIGN KEY (`fid`) REFERENCES `xbt_files` (`fid`) ON DELETE CASCADE,
  ADD CONSTRAINT `xbt_files_users_ibfk_4` FOREIGN KEY (`uid`) REFERENCES `xbt_users` (`uid`) ON DELETE CASCADE,
  ADD CONSTRAINT `xbt_files_users_ibfk_5` FOREIGN KEY (`uid`) REFERENCES `xbt_users` (`uid`) ON DELETE CASCADE,
  ADD CONSTRAINT `xbt_files_users_ibfk_6` FOREIGN KEY (`uid`) REFERENCES `xbt_users` (`uid`) ON DELETE CASCADE,
  ADD CONSTRAINT `xbt_files_users_ibfk_7` FOREIGN KEY (`uid`) REFERENCES `xbt_users` (`uid`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
