CREATE TABLE IF NOT EXISTS `blog` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `lastEdit` datetime NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `isHtml` tinyint(1) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `date` (`date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=60 ;

CREATE TABLE IF NOT EXISTS `blogcomments` (
  `id` mediumint(8) unsigned NOT NULL,
  `blogPost` smallint(5) unsigned NOT NULL,
  `parentComment` mediumint(8) unsigned DEFAULT NULL,
  `posterName` varchar(32) NOT NULL,
  `date` datetime NOT NULL,
  `title` varchar(128) NOT NULL,
  `comment` text NOT NULL,
  `ip` decimal(39,0) unsigned zerofill NOT NULL,
  `visible` tinyint(1) NOT NULL,
  `method` varchar(255) NOT NULL,
  `GET` text NOT NULL,
  `POST` text NOT NULL,
  `SERVER` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `blogpost` (`blogPost`),
  KEY `parentcomment` (`parentComment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `galleryalbums` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `dirName` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dirName` (`dirName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

CREATE TABLE IF NOT EXISTS `galleryimages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `album` smallint(5) unsigned NOT NULL DEFAULT '0',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `caption` text,
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `width` smallint(5) unsigned NOT NULL DEFAULT '0',
  `tHeight` smallint(5) unsigned NOT NULL DEFAULT '0',
  `tWidth` smallint(5) unsigned NOT NULL DEFAULT '0',
  `make` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `dateTime` datetime DEFAULT NULL,
  `exposureTime` varchar(255) DEFAULT NULL,
  `fNumber` double unsigned DEFAULT NULL,
  `isoSpeed` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `filename` (`album`,`filename`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=76 ;

ALTER TABLE `blogcomments`
  ADD CONSTRAINT `blogcomments_ibfk_1` FOREIGN KEY (`blogPost`) REFERENCES `blog` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blogcomments_ibfk_2` FOREIGN KEY (`parentComment`) REFERENCES `blogcomments` (`id`) ON DELETE CASCADE;

ALTER TABLE `galleryimages`
  ADD CONSTRAINT `galleryimages_ibfk_1` FOREIGN KEY (`album`) REFERENCES `galleryalbums` (`id`) ON DELETE CASCADE;
