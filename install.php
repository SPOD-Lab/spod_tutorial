<?php
/**
 * Created by PhpStorm.
 * User: Dario
 * Date: 10/01/2017
 * Time: 11:28
 */

BOL_LanguageService::getInstance()->addPrefix('spodtutorial', 'SPOD Tutorial');
OW::getLanguage()->importPluginLangs(OW::getPluginManager()->getPlugin('spodtutorial')->getRootDir().'langs.zip', 'spodtutorial');

$sql = 'CREATE TABLE IF NOT EXISTS `' . OW_DB_PREFIX . 'spodtutorial_assigned_challenges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `firstChallengeId` int(11),
  `secondChallengeId` int(11),
  `thirdChallengeId` int(11),
  `fourthChallengeId` int(11),
  `fifthChallengeId` int(11),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;';

OW::getDbo()->query($sql);

$sql2 = 'CREATE TABLE IF NOT EXISTS `' . OW_DB_PREFIX . 'spodtutorial_challenge` (
  `id` int(11) NOT NULL,
  `title` varchar(500),
  `body` varchar(500),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;';

OW::getDbo()->query($sql2);

OW::getDbo()->query(
    'INSERT INTO `' . OW_DB_PREFIX . 'spodtutorial_challenge` (`id`, `title`, `body`) VALUES
(1, "titolo1", "corpo1"),
(2, "titolo2", "corpo2"),
(3, "titolo3", "corpo3"),
(4, "titolo4", "corpo4"),
(5, "titolo5", "corpo5"),
(6, "titolo6", "corpo6"),
(7, "titolo7", "corpo7"),
(8, "titolo8", "corpo8"),
(9, "titolo9", "corpo9"),
(10, "titolo10", "corpo10"),
(11, "titolo11", "corpo11");'
);