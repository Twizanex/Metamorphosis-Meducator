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
-- Table constraints for table `frameref`
--

	ALTER TABLE `frameref` ADD CONSTRAINT `fk_frameref_frameid` FOREIGN KEY (`frameid`) REFERENCES `framedef`(`frameid`);
	ALTER TABLE `frameref` ADD CONSTRAINT `fk_frameref_synsetid` FOREIGN KEY (`synsetid`) REFERENCES `synset`(`synsetid`);
	ALTER TABLE `frameref` ADD CONSTRAINT `fk_frameref_wordid` FOREIGN KEY (`wordid`) REFERENCES `word`(`wordid`);

--
-- Table constraints for table `lexlinkref`
--

	ALTER TABLE `lexlinkref` ADD INDEX `k_lexlinkref_synset1id_word1id` (`synset1id`,`word1id`);
	ALTER TABLE `lexlinkref` ADD INDEX `k_lexlinkref_synset2id_word2id` (`synset2id`,`word2id`);
	ALTER TABLE `lexlinkref` ADD CONSTRAINT `fk_lexlinkref_synset1id` FOREIGN KEY (`synset1id`) REFERENCES `synset` (`synsetid`);
	ALTER TABLE `lexlinkref` ADD CONSTRAINT `fk_lexlinkref_synset2id` FOREIGN KEY (`synset2id`) REFERENCES `synset` (`synsetid`);
	ALTER TABLE `lexlinkref` ADD CONSTRAINT `fk_lexlinkref_word1id` FOREIGN KEY (`word1id`) REFERENCES `word` (`wordid`);
	ALTER TABLE `lexlinkref` ADD CONSTRAINT `fk_lexlinkref_word2id` FOREIGN KEY (`word2id`) REFERENCES `word` (`wordid`);
	ALTER TABLE `lexlinkref` ADD CONSTRAINT `fk_lexlinkref_linkid` FOREIGN KEY (`linkid`) REFERENCES `linkdef` (`linkid`);

--
-- Table constraints for table `morphdef`
--

	ALTER TABLE `morphdef` ADD CONSTRAINT `unq_morphdef_lemma` UNIQUE KEY (`lemma`);

--
-- Table constraints for table `morphref`
--

	ALTER TABLE `morphref` ADD INDEX `k_morphref_wordid` (`wordid`);
	ALTER TABLE `morphref` ADD INDEX `k_morphref_morphid` (`morphid`);
	ALTER TABLE `morphref` ADD CONSTRAINT `fk_morphref_wordid` FOREIGN KEY (`wordid`) REFERENCES `word`(`wordid`);
	ALTER TABLE `morphref` ADD CONSTRAINT `fk_morphref_morphid` FOREIGN KEY (`morphid`) REFERENCES `morphdef`(`morphid`);


--
-- Table constraints for table `sample`
--

	ALTER TABLE `sample` ADD INDEX `k_sample_synsetid` (`synsetid`);
	ALTER TABLE `sample` ADD CONSTRAINT `fk_sample_synsetid` FOREIGN KEY (`synsetid`) REFERENCES `synset`(`synsetid`);

--
-- Table constraints for table `samples`
--

	ALTER TABLE `samples` ADD CONSTRAINT `fk_samples_synsetid` FOREIGN KEY (`synsetid`) REFERENCES `synset`(`synsetid`);

--
-- Table constraints for table `semlinkref`
--

	ALTER TABLE `semlinkref` ADD INDEX `k_semlinkref_synset1id` (`synset1id`);
	ALTER TABLE `semlinkref` ADD INDEX `k_semlinkref_synset2id` (`synset2id`);
	ALTER TABLE `semlinkref` ADD CONSTRAINT `fk_semlinkref_synset1id` FOREIGN KEY (`synset1id`) REFERENCES `synset` (`synsetid`);
	ALTER TABLE `semlinkref` ADD CONSTRAINT `fk_semlinkref_synset2id` FOREIGN KEY (`synset2id`) REFERENCES `synset` (`synsetid`);
	ALTER TABLE `semlinkref` ADD CONSTRAINT `fk_semlinkref_linkid` FOREIGN KEY (`linkid`) REFERENCES `linkdef` (`linkid`);

--
-- Table constraints for table `sense`
--

	ALTER TABLE `sense` ADD INDEX `k_sense_wordid` (`wordid`);
	ALTER TABLE `sense` ADD INDEX `k_sense_synsetid` (`synsetid`);
	ALTER TABLE `sense` ADD CONSTRAINT `fk_sense_wordid` FOREIGN KEY (`wordid`) REFERENCES `word`(`wordid`);
	ALTER TABLE `sense` ADD CONSTRAINT `fk_sense_synsetid` FOREIGN KEY (`synsetid`) REFERENCES `synset`(`synsetid`);

--
-- Table constraints for table `sentenceref`
--
	ALTER TABLE `sentenceref` ADD CONSTRAINT `fk_sentenceref_sentenceid` FOREIGN KEY (`sentenceid`) REFERENCES `sentencedef`(`sentenceid`);
	ALTER TABLE `sentenceref` ADD CONSTRAINT `fk_sentenceref_synsetid` FOREIGN KEY (`synsetid`) REFERENCES `synset`(`synsetid`);
	ALTER TABLE `sentenceref` ADD CONSTRAINT `fk_sentenceref_wordid` FOREIGN KEY (`wordid`) REFERENCES `word`(`wordid`);

--
-- Table constraints for table `synset`
--

	ALTER TABLE `synset` ADD CONSTRAINT `fk_synset_categoryid` FOREIGN KEY (`categoryid`) REFERENCES `categorydef` (`categoryid`);

--
-- Table constraints for table `word`
--

	ALTER TABLE `word` ADD CONSTRAINT `unq_word_lemma` UNIQUE KEY (`lemma`);

--
-- Table constraints for table `wordposition`
--

	ALTER TABLE `wordposition` ADD CONSTRAINT `fk_wordposition_synsetid` FOREIGN KEY (`synsetid`) REFERENCES `synset` (`synsetid`);
	ALTER TABLE `wordposition` ADD CONSTRAINT `fk_wordposition_wordid` FOREIGN KEY (`wordid`) REFERENCES `word` (`wordid`);
