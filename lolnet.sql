-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 18, 2018 at 10:49 PM
-- Server version: 5.7.19
-- PHP Version: 7.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lolnet`
--

-- --------------------------------------------------------

--
-- Table structure for table `forum_contents`
--

DROP TABLE IF EXISTS `forum_contents`;
CREATE TABLE IF NOT EXISTS `forum_contents` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `server_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `tid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `mid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `date` varchar(25) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `timer` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `see` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `server_id` (`server_id`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM AUTO_INCREMENT=5561 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forum_topics`
--

DROP TABLE IF EXISTS `forum_topics`;
CREATE TABLE IF NOT EXISTS `forum_topics` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `server_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `mid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `sticky` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `replies` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `views` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `last` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `first` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `timer` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `see` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `server_id` (`server_id`),
  KEY `fid` (`fid`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=1340 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_aservers`
--

DROP TABLE IF EXISTS `lol_aservers`;
CREATE TABLE IF NOT EXISTS `lol_aservers` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(32) NOT NULL DEFAULT '',
  `admin_email` varchar(64) NOT NULL DEFAULT '',
  `world_name` varchar(16) NOT NULL DEFAULT '',
  `world_title` varchar(64) NOT NULL DEFAULT '',
  `world_description` varchar(128) NOT NULL DEFAULT '',
  `world_date` varchar(32) NOT NULL DEFAULT '',
  `game_mode` tinyint(4) NOT NULL DEFAULT '0',
  `killing_spree_max` smallint(6) NOT NULL DEFAULT '0',
  `max_player_per_ip` smallint(6) NOT NULL DEFAULT '0',
  `fp_bonus_max` smallint(6) NOT NULL DEFAULT '0',
  `max_muted` int(11) NOT NULL DEFAULT '0',
  `max_jailed` int(11) NOT NULL DEFAULT '0',
  `chat_timer` smallint(6) NOT NULL DEFAULT '0',
  `reset_days` smallint(6) NOT NULL DEFAULT '0',
  `menu_type` tinyint(4) NOT NULL DEFAULT '0',
  `rules` mediumtext NOT NULL,
  `updated` int(11) NOT NULL DEFAULT '0',
  `timer` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `world_name` (`world_name`),
  KEY `killing_spree_max` (`killing_spree_max`),
  KEY `max_player_per_ip` (`max_player_per_ip`),
  KEY `fp_bonus_max` (`fp_bonus_max`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_board`
--

DROP TABLE IF EXISTS `lol_board`;
CREATE TABLE IF NOT EXISTS `lol_board` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `star` tinyint(4) NOT NULL DEFAULT '0',
  `clan` varchar(5) NOT NULL DEFAULT '',
  `sex` varchar(15) NOT NULL DEFAULT '',
  `charname` varchar(15) NOT NULL DEFAULT '',
  `race` varchar(15) NOT NULL DEFAULT '',
  `level` bigint(20) NOT NULL DEFAULT '0',
  `message` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `timer` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `server_id` (`server_id`)
) ENGINE=MyISAM AUTO_INCREMENT=357692 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_charms`
--

DROP TABLE IF EXISTS `lol_charms`;
CREATE TABLE IF NOT EXISTS `lol_charms` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `charname` varchar(15) NOT NULL DEFAULT '',
  `finder` varchar(15) NOT NULL DEFAULT '',
  `name` varchar(15) NOT NULL DEFAULT '',
  `str` tinyint(3) NOT NULL DEFAULT '0',
  `dex` tinyint(3) NOT NULL DEFAULT '0',
  `agi` tinyint(3) NOT NULL DEFAULT '0',
  `intel` tinyint(3) NOT NULL DEFAULT '0',
  `conc` tinyint(3) NOT NULL DEFAULT '0',
  `cont` tinyint(3) NOT NULL DEFAULT '0',
  `timer` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `charname` (`charname`)
) ENGINE=MyISAM AUTO_INCREMENT=475499 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_clans`
--

DROP TABLE IF EXISTS `lol_clans`;
CREATE TABLE IF NOT EXISTS `lol_clans` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sex` varchar(15) NOT NULL DEFAULT '',
  `charname` varchar(15) NOT NULL DEFAULT '',
  `password` varchar(15) NOT NULL DEFAULT '',
  `clan` varchar(5) NOT NULL DEFAULT '',
  `name` varchar(15) NOT NULL DEFAULT '',
  `won` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `lost` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `tied` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `points` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `tourney` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `timer` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `charname` (`charname`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `clan` (`clan`)
) ENGINE=MyISAM AUTO_INCREMENT=1103 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_councils`
--

DROP TABLE IF EXISTS `lol_councils`;
CREATE TABLE IF NOT EXISTS `lol_councils` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL,
  `sex` varchar(15) NOT NULL DEFAULT '',
  `apply` varchar(15) NOT NULL DEFAULT '',
  `charname` varchar(15) NOT NULL DEFAULT '',
  `admin` int(11) NOT NULL DEFAULT '0',
  `cop` int(11) NOT NULL DEFAULT '0',
  `mod` int(11) NOT NULL DEFAULT '0',
  `support` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(11) NOT NULL DEFAULT '',
  `timer` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=507 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_credits`
--

DROP TABLE IF EXISTS `lol_credits`;
CREATE TABLE IF NOT EXISTS `lol_credits` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(15) NOT NULL DEFAULT '',
  `charname` varchar(15) NOT NULL DEFAULT '',
  `credits` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `charname` (`charname`)
) ENGINE=MyISAM AUTO_INCREMENT=428 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_duel`
--

DROP TABLE IF EXISTS `lol_duel`;
CREATE TABLE IF NOT EXISTS `lol_duel` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `challenger` varchar(15) NOT NULL DEFAULT '',
  `opponent` varchar(15) NOT NULL DEFAULT '',
  `kind` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `timer` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `challenger` (`challenger`),
  KEY `opponent` (`opponent`)
) ENGINE=MyISAM AUTO_INCREMENT=401801 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_history`
--

DROP TABLE IF EXISTS `lol_history`;
CREATE TABLE IF NOT EXISTS `lol_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `charname` varchar(15) NOT NULL DEFAULT '',
  `kills` bigint(20) NOT NULL DEFAULT '0',
  `deads` bigint(20) NOT NULL DEFAULT '0',
  `duelsw` bigint(20) NOT NULL DEFAULT '0',
  `duelsl` bigint(20) NOT NULL DEFAULT '0',
  `timer` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `charname` (`charname`)
) ENGINE=MyISAM AUTO_INCREMENT=9419 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_index`
--

DROP TABLE IF EXISTS `lol_index`;
CREATE TABLE IF NOT EXISTS `lol_index` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` varchar(10) NOT NULL DEFAULT '',
  `fights` bigint(20) NOT NULL DEFAULT '0',
  `timer` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`),
  KEY `fights` (`fights`)
) ENGINE=MyISAM AUTO_INCREMENT=3628 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_items`
--

DROP TABLE IF EXISTS `lol_items`;
CREATE TABLE IF NOT EXISTS `lol_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL DEFAULT '0',
  `kind` tinyint(4) NOT NULL DEFAULT '0',
  `sub` tinyint(4) NOT NULL DEFAULT '0',
  `value` tinyint(4) NOT NULL DEFAULT '0',
  `timer` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`),
  KEY `kind` (`kind`),
  KEY `sub` (`sub`),
  KEY `value` (`value`),
  KEY `timer` (`timer`)
) ENGINE=MyISAM AUTO_INCREMENT=653947 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_market`
--

DROP TABLE IF EXISTS `lol_market`;
CREATE TABLE IF NOT EXISTS `lol_market` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `server_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `cid` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `charname` varchar(15) NOT NULL DEFAULT '',
  `gold` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `credits` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `timer` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cid` (`cid`),
  KEY `server_id` (`server_id`),
  KEY `charname` (`charname`)
) ENGINE=MyISAM AUTO_INCREMENT=742 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_members`
--

DROP TABLE IF EXISTS `lol_members`;
CREATE TABLE IF NOT EXISTS `lol_members` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sid` varchar(64) NOT NULL,
  `server_id` int(11) NOT NULL DEFAULT '0',
  `username` varchar(10) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `clan` varchar(5) NOT NULL,
  `sex` varchar(15) NOT NULL,
  `charname` varchar(15) NOT NULL,
  `race` varchar(15) NOT NULL,
  `level` decimal(65,0) NOT NULL DEFAULT '0',
  `xp` decimal(65,0) NOT NULL DEFAULT '0',
  `gold` decimal(65,0) NOT NULL DEFAULT '0',
  `stash` decimal(65,0) NOT NULL DEFAULT '0',
  `life` decimal(65,0) NOT NULL DEFAULT '0',
  `str` decimal(65,0) NOT NULL DEFAULT '0',
  `dex` decimal(65,0) NOT NULL DEFAULT '0',
  `agi` decimal(65,0) NOT NULL DEFAULT '0',
  `intel` decimal(65,0) NOT NULL DEFAULT '0',
  `conc` decimal(65,0) NOT NULL DEFAULT '0',
  `cont` decimal(65,0) NOT NULL DEFAULT '0',
  `weapon` decimal(65,0) NOT NULL DEFAULT '0',
  `spell` decimal(65,0) NOT NULL DEFAULT '0',
  `heal` decimal(65,0) NOT NULL DEFAULT '0',
  `helm` decimal(65,0) NOT NULL DEFAULT '0',
  `shield` decimal(65,0) NOT NULL DEFAULT '0',
  `amulet` decimal(65,0) NOT NULL DEFAULT '0',
  `ring` decimal(65,0) NOT NULL DEFAULT '0',
  `armor` decimal(65,0) NOT NULL DEFAULT '0',
  `belt` decimal(65,0) NOT NULL DEFAULT '0',
  `pants` decimal(65,0) NOT NULL DEFAULT '0',
  `hand` decimal(65,0) NOT NULL DEFAULT '0',
  `feet` decimal(65,0) NOT NULL DEFAULT '0',
  `jail` bigint(20) NOT NULL DEFAULT '0',
  `stealth` bigint(20) NOT NULL DEFAULT '0',
  `twin` tinyint(4) NOT NULL DEFAULT '0',
  `fp` bigint(20) NOT NULL DEFAULT '0',
  `mute` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `vote` varchar(15) NOT NULL,
  `timer` decimal(20,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `fail` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `friend` varchar(15) NOT NULL,
  `onoff` tinyint(4) NOT NULL DEFAULT '0',
  `rounds` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `charname` (`charname`),
  UNIQUE KEY `sid` (`sid`),
  KEY `sex` (`sex`),
  KEY `race` (`race`),
  KEY `level` (`level`),
  KEY `xp` (`xp`),
  KEY `gold` (`gold`),
  KEY `timer` (`timer`),
  KEY `clan` (`clan`),
  KEY `str` (`str`),
  KEY `life` (`life`),
  KEY `dex` (`dex`),
  KEY `agi` (`agi`),
  KEY `intel` (`intel`),
  KEY `conc` (`conc`),
  KEY `cont` (`cont`)
) ENGINE=MyISAM AUTO_INCREMENT=10833 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_messages`
--

DROP TABLE IF EXISTS `lol_messages`;
CREATE TABLE IF NOT EXISTS `lol_messages` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `charname` varchar(15) NOT NULL DEFAULT '',
  `receiver` varchar(15) NOT NULL DEFAULT '',
  `message` mediumtext NOT NULL,
  `timer` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=MyISAM AUTO_INCREMENT=19886 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_papers`
--

DROP TABLE IF EXISTS `lol_papers`;
CREATE TABLE IF NOT EXISTS `lol_papers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `server_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pid` tinyint(4) NOT NULL DEFAULT '0',
  `news` text NOT NULL,
  `timer` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `server_id` (`server_id`)
) ENGINE=MyISAM AUTO_INCREMENT=864358 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_paypal`
--

DROP TABLE IF EXISTS `lol_paypal`;
CREATE TABLE IF NOT EXISTS `lol_paypal` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server` varchar(15) NOT NULL DEFAULT '',
  `amount` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `day` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `month` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `year` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `server` (`server`)
) ENGINE=MyISAM AUTO_INCREMENT=320 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_pets`
--

DROP TABLE IF EXISTS `lol_pets`;
CREATE TABLE IF NOT EXISTS `lol_pets` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `charname` varchar(15) NOT NULL DEFAULT '',
  `petname` varchar(15) NOT NULL DEFAULT '',
  `petrace` varchar(15) NOT NULL DEFAULT '',
  `mother` varchar(15) NOT NULL DEFAULT '',
  `level` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `xp` decimal(65,0) UNSIGNED NOT NULL DEFAULT '0',
  `str` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `dex` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `agi` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `intel` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `conc` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `cont` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `mood` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `hunger` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `age` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `timer` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `charname` (`charname`)
) ENGINE=MyISAM AUTO_INCREMENT=728 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_races`
--

DROP TABLE IF EXISTS `lol_races`;
CREATE TABLE IF NOT EXISTS `lol_races` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `server_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `race` varchar(12) NOT NULL,
  `ap` tinyint(4) NOT NULL DEFAULT '0',
  `dp` tinyint(4) NOT NULL DEFAULT '0',
  `mp` tinyint(4) NOT NULL DEFAULT '0',
  `tp` tinyint(4) NOT NULL DEFAULT '0',
  `rp` tinyint(4) NOT NULL DEFAULT '0',
  `pp` tinyint(4) NOT NULL DEFAULT '0',
  `description` blob NOT NULL,
  `timer` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `race` (`race`)
) ENGINE=MyISAM AUTO_INCREMENT=153 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_runes`
--

DROP TABLE IF EXISTS `lol_runes`;
CREATE TABLE IF NOT EXISTS `lol_runes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `charname` varchar(25) NOT NULL DEFAULT '',
  `name` char(3) NOT NULL DEFAULT '',
  `timer` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_save`
--

DROP TABLE IF EXISTS `lol_save`;
CREATE TABLE IF NOT EXISTS `lol_save` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL DEFAULT '0',
  `charname` varchar(15) NOT NULL,
  `level` decimal(65,0) NOT NULL DEFAULT '0',
  `xp` decimal(65,0) NOT NULL DEFAULT '0',
  `gold` decimal(65,0) NOT NULL DEFAULT '0',
  `stash` decimal(65,0) NOT NULL DEFAULT '0',
  `life` decimal(65,0) NOT NULL DEFAULT '0',
  `str` decimal(65,0) NOT NULL DEFAULT '0',
  `dex` decimal(65,0) NOT NULL DEFAULT '0',
  `agi` decimal(65,0) NOT NULL DEFAULT '0',
  `intel` decimal(65,0) NOT NULL DEFAULT '0',
  `conc` decimal(65,0) NOT NULL DEFAULT '0',
  `cont` decimal(65,0) NOT NULL DEFAULT '0',
  `weapon` decimal(65,0) NOT NULL DEFAULT '0',
  `spell` decimal(65,0) NOT NULL DEFAULT '0',
  `heal` decimal(65,0) NOT NULL DEFAULT '0',
  `helm` decimal(65,0) NOT NULL DEFAULT '0',
  `shield` decimal(65,0) NOT NULL DEFAULT '0',
  `amulet` decimal(65,0) NOT NULL DEFAULT '0',
  `ring` decimal(65,0) NOT NULL DEFAULT '0',
  `armor` decimal(65,0) NOT NULL DEFAULT '0',
  `belt` decimal(65,0) NOT NULL DEFAULT '0',
  `pants` decimal(65,0) NOT NULL DEFAULT '0',
  `hand` decimal(65,0) NOT NULL DEFAULT '0',
  `feet` decimal(65,0) NOT NULL DEFAULT '0',
  `timer` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `timer` (`timer`),
  KEY `server_id` (`server_id`),
  KEY `charname` (`charname`)
) ENGINE=MyISAM AUTO_INCREMENT=18624 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_steals`
--

DROP TABLE IF EXISTS `lol_steals`;
CREATE TABLE IF NOT EXISTS `lol_steals` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `sex` varchar(15) NOT NULL DEFAULT '',
  `charname` varchar(15) NOT NULL DEFAULT '',
  `item` varchar(32) NOT NULL DEFAULT '',
  `amount` bigint(20) NOT NULL DEFAULT '0',
  `timer` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `server_id` (`server_id`),
  KEY `charname` (`charname`)
) ENGINE=MyISAM AUTO_INCREMENT=133116 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_tourney`
--

DROP TABLE IF EXISTS `lol_tourney`;
CREATE TABLE IF NOT EXISTS `lol_tourney` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL DEFAULT '0',
  `clana` varchar(5) NOT NULL DEFAULT '',
  `clanb` varchar(5) NOT NULL DEFAULT '',
  `timer` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `server_id` (`server_id`),
  KEY `clana` (`clana`),
  KEY `clanb` (`clanb`)
) ENGINE=MyISAM AUTO_INCREMENT=103975 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_tourprice`
--

DROP TABLE IF EXISTS `lol_tourprice`;
CREATE TABLE IF NOT EXISTS `lol_tourprice` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL DEFAULT '0',
  `clan` varchar(5) NOT NULL DEFAULT '',
  `xp` decimal(255,0) UNSIGNED NOT NULL DEFAULT '0',
  `gold` decimal(255,0) UNSIGNED NOT NULL DEFAULT '0',
  `timer` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `server_id` (`server_id`),
  KEY `clan` (`clan`)
) ENGINE=MyISAM AUTO_INCREMENT=2586 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lol_zlogs`
--

DROP TABLE IF EXISTS `lol_zlogs`;
CREATE TABLE IF NOT EXISTS `lol_zlogs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `charname` varchar(15) NOT NULL DEFAULT '',
  `logs` mediumtext NOT NULL,
  `file` varchar(15) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `timer` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `charname` (`charname`)
) ENGINE=MyISAM AUTO_INCREMENT=906941 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `forum_contents`
--
ALTER TABLE `forum_contents` ADD FULLTEXT KEY `body` (`body`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
