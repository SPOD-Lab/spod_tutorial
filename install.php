<?php
/**
 * Created by PhpStorm.
 * User: Dario
 * Date: 10/01/2017
 * Time: 11:28
 */

$path = OW::getPluginManager()->getPlugin('spodtutorial')->getRootDir() . 'langs.zip';
BOL_LanguageService::getInstance()->importPrefixFromZip($path, 'spodtutorial');

/*BOL_LanguageService::getInstance()->addPrefix('spodtutorial', 'SPOD Tutorial');
OW::getLanguage()->importPluginLangs(OW::getPluginManager()->getPlugin('spodtutorial')->getRootDir().'langs.zip', 'spodtutorial');*/

$sql = 'CREATE TABLE IF NOT EXISTS `' . OW_DB_PREFIX . 'spodtutorial_progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `passedChallengesId` varchar(500),
  `assignedChallengesId` varchar(500),
  `timestamp` timestamp,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;';

OW::getDbo()->query($sql);

$sql2 = 'CREATE TABLE IF NOT EXISTS `' . OW_DB_PREFIX . 'spodtutorial_challenge` (
  `id` int(11) NOT NULL,
  `title` varchar(500),
  `body` varchar(500),
  `dependencies` int(11),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;';

OW::getDbo()->query($sql2);

OW::getDbo()->query(
    'INSERT INTO `' . OW_DB_PREFIX . 'spodtutorial_challenge` (`id`, `title`, `body`, `dependencies`) VALUES
(1, "new_datalet_title", "new_datalet_body", ""),
(2, "filter_title", "filter_body", "1"),
(3, "group_title", "group_body", "1"),
(4, "groupfilter_title", "groupfilter_body", "3"),
(5, "datatable_title", "datatable_body", ""),
(6, "barchart_title", "barchart_body", ""),
(7, "map_title", "map_body", ""),
(8, "copy_title", "copy_body", ""),
(9, "reuse_title", "reuse_body", "8"),
(10, "export_title", "export_body", ""),
(11, "new_link_title", "new_link_body", ""),
(12, "reuse_link_title", "reuse_link_body", "11"),
(13, "attach_title", "attach_body", ""),
(14, "new_text_title", "new_text_body", ""),
(15, "cocreation_title", "cocreation_body", ""),
(16, "publish_title", "publish_body", "15"),
(17, "post_title", "post_body", ""),
(18, "like_title", "like_body", ""),
(19, "comment_title", "comment_body", "1");'
);