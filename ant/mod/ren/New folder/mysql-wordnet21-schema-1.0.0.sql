-- MySQL dump 10.9
--
-- Host: localhost    Database: wordnet21
-- ------------------------------------------------------
-- Server version	4.1.9-nt
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE="NO_AUTO_VALUE_ON_ZERO,MYSQL323" */;

--
-- Current Database: `wordnet21`
--

USE `wordnet21`;

--
-- Table structure for table `categorydef`
--

DROP TABLE IF EXISTS `categorydef`;
CREATE TABLE `categorydef` (
  `categoryid` decimal(2,0) NOT NULL default '0',
  `name` varchar(32) default NULL,
  `pos` enum('n','v','a','r','s') default NULL,
  PRIMARY KEY  (`categoryid`)
) TYPE=InnoDB;

--
-- Table structure for table `framedef`
--

DROP TABLE IF EXISTS `framedef`;
CREATE TABLE `framedef` (
  `frameid` decimal(2,0) NOT NULL default '0',
  `frame` varchar(50) default NULL,
  PRIMARY KEY  (`frameid`)
) TYPE=InnoDB;

--
-- Table structure for table `frameref`
--

DROP TABLE IF EXISTS `frameref`;
CREATE TABLE `frameref` (
  `synsetid` decimal(9,0) NOT NULL default '0',
  `wordid` decimal(6,0) NOT NULL default '0',
  `frameid` decimal(2,0) NOT NULL default '0',
  PRIMARY KEY  (`synsetid`,`wordid`,`frameid`)
) TYPE=InnoDB;

--
-- Table structure for table `lexlinkref`
--

DROP TABLE IF EXISTS `lexlinkref`;
CREATE TABLE `lexlinkref` (
  `synset1id` decimal(9,0) NOT NULL default '0',
  `word1id` decimal(6,0) NOT NULL default '0',
  `synset2id` decimal(9,0) NOT NULL default '0',
  `word2id` decimal(6,0) NOT NULL default '0',
  `linkid` decimal(2,0) NOT NULL default '0',
  PRIMARY KEY  (`word1id`,`synset1id`,`word2id`,`synset2id`,`linkid`)
) TYPE=InnoDB;

--
-- Table structure for table `linkdef`
--

DROP TABLE IF EXISTS `linkdef`;
CREATE TABLE `linkdef` (
  `linkid` decimal(2,0) NOT NULL default '0',
  `name` varchar(50) default NULL,
  `recurses` enum('Y','N') NOT NULL default 'N',
  PRIMARY KEY  (`linkid`)
) TYPE=InnoDB;

--
-- Table structure for table `morphdef`
--

DROP TABLE IF EXISTS `morphdef`;
CREATE TABLE `morphdef` (
  `morphid` decimal(6,0) NOT NULL default '0',
  `lemma` varchar(70) NOT NULL default '',
  PRIMARY KEY  (`morphid`)
) TYPE=InnoDB;

--
-- Table structure for table `morphref`
--

DROP TABLE IF EXISTS `morphref`;
CREATE TABLE `morphref` (
  `wordid` decimal(6,0) NOT NULL default '0',
  `pos` enum('n','v','a','r','s') NOT NULL default 'n',
  `morphid` decimal(6,0) NOT NULL default '0',
  PRIMARY KEY  (`morphid`,`pos`,`wordid`)
) TYPE=InnoDB;

--
-- Table structure for table `sample`
--

DROP TABLE IF EXISTS `sample`;
CREATE TABLE `sample` (
  `synsetid` decimal(9,0) NOT NULL default '0',
  `sampleid` decimal(2,0) NOT NULL default '0',
  `sample` mediumtext NOT NULL,
  PRIMARY KEY  (`synsetid`,`sampleid`)
) TYPE=InnoDB;

--
-- Table structure for table `samples`
--

DROP TABLE IF EXISTS `samples`;
CREATE TABLE `samples` (
  `synsetid` decimal(9,0) NOT NULL default '0',
  `samples` mediumtext NOT NULL,
  PRIMARY KEY  (`synsetid`)
) TYPE=InnoDB;

--
-- Table structure for table `semlinkref`
--

DROP TABLE IF EXISTS `semlinkref`;
CREATE TABLE `semlinkref` (
  `synset1id` decimal(9,0) NOT NULL default '0',
  `synset2id` decimal(9,0) NOT NULL default '0',
  `linkid` decimal(2,0) NOT NULL default '0',
  PRIMARY KEY  (`synset1id`,`synset2id`,`linkid`)
) TYPE=InnoDB;

--
-- Table structure for table `sense`
--

DROP TABLE IF EXISTS `sense`;
CREATE TABLE `sense` (
  `wordid` decimal(6,0) NOT NULL default '0',
  `synsetid` decimal(9,0) NOT NULL default '0',
  `rank` decimal(2,0) NOT NULL default '0',
  `lexid` decimal(2,0) NOT NULL default '0',
  `tagcount` decimal(5,0) default NULL,
  PRIMARY KEY  (`synsetid`,`wordid`)
) TYPE=InnoDB;

--
-- Table structure for table `sentencedef`
--

DROP TABLE IF EXISTS `sentencedef`;
CREATE TABLE `sentencedef` (
  `sentenceid` decimal(3,0) NOT NULL default '0',
  `sentence` mediumtext,
  PRIMARY KEY  (`sentenceid`)
) TYPE=InnoDB;

--
-- Table structure for table `sentenceref`
--

DROP TABLE IF EXISTS `sentenceref`;
CREATE TABLE `sentenceref` (
  `synsetid` decimal(9,0) NOT NULL default '0',
  `wordid` decimal(6,0) NOT NULL default '0',
  `sentenceid` decimal(3,0) NOT NULL default '0',
  PRIMARY KEY  (`synsetid`,`wordid`,`sentenceid`)
) TYPE=InnoDB;

--
-- Table structure for table `synset`
--

DROP TABLE IF EXISTS `synset`;
CREATE TABLE `synset` (
  `synsetid` decimal(9,0) NOT NULL default '0',
  `pos` enum('n','v','a','r','s') default NULL,
  `categoryid` decimal(2,0) NOT NULL default '0',
  `definition` mediumtext,
  PRIMARY KEY  (`synsetid`)
) TYPE=InnoDB;

--
-- Table structure for table `word`
--

DROP TABLE IF EXISTS `word`;
CREATE TABLE `word` (
  `wordid` decimal(6,0) NOT NULL default '0',
  `lemma` varchar(80) NOT NULL default '',
  PRIMARY KEY  (`wordid`)
) TYPE=InnoDB;

--
-- Table structure for table `wordposition`
--

DROP TABLE IF EXISTS `wordposition`;
CREATE TABLE `wordposition` (
  `synsetid` decimal(9,0) NOT NULL default '0',
  `wordid` decimal(6,0) NOT NULL default '0',
  `positionid` enum('a','p','ip') NOT NULL default 'a',
  PRIMARY KEY  (`synsetid`,`wordid`)
) TYPE=InnoDB;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
