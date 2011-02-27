-- CODERTROVE DATABASE --

-- USERS --

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `username` char(35) NOT NULL,
  `password` char(35) NOT NULL,
  `email` char(80) NOT NULL,
  `firstname` char(35) NOT NULL,
  `lastname` char(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- USER INTERESTS --

DROP TABLE IF EXISTS `userinterests`;
CREATE TABLE `userinterests` (
  `userid` bigint NOT NULL,
  `skillid` bigint NOT NULL
  PRIMARY KEY (`userid`, `skillid`)
) ENGINE=MyISAM;

-- PAYMENTS --

DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `paymentid` bigint NOT NULL AUTO_INCREMENT,
  `userid` bigint NOT NULL,
  `amount` double NOT NULL,
  `paymenttimestamp` datetime NOT NULL,
  `packagetype` int NOT NULL
  PRIMARY KEY (`paymentid`)
) ENGINE=MyISAM;

-- CODERS --

DROP TABLE IF EXISTS `coders`;
CREATE TABLE `coders` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `shorthandle` char(20) NOT NULL,
  `picURL` char(256) DEFAULT '',
  `fullname` char(70) DEFAULT '',
  `email` char(80) DEFAULT '',
  `linkedinURL` char(256) DEFAULT ''
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- CODER/SOURCE PROFILES --

DROP TABLE IF EXISTS `codersourceprofiles`;
CREATE TABLE `codersourceprofiles` (
  `coderid` bigint NOT NULL,
  `sourceid` bigint NOT NULL,
  `username` char(80) DEFAULT '',
  `datejoined` date,
  `ranking` int,
  `karma` int
  PRIMARY KEY (`coderid`, `sourceid`)
) ENGINE=MyISAM;

-- CODER ACTIVITY --

DROP TABLE IF EXISTS `coderactivity`;
CREATE TABLE `coderactivity` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `coderid` bigint NOT NULL,
  `sourceid` bigint NOT NULL,
  `commenttitle` char(256) DEFAULT '',
  `commentbody` text DEFAULT '',
  `likes` int DEFAULT 0,
  `commentURL` char(256) DEFAULT ''
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- CODER MESSAGES --

DROP TABLE IF EXISTS `codermessages`;
CREATE TABLE `codermessages` (
  `userid` bigint NOT NULL,
  `coderid` bigint NOT NULL,
  `contents` text DEFAULT ''
  PRIMARY KEY (`userid`, `coderid`)
) ENGINE=MyISAM;

-- SKILLS --

DROP TABLE IF EXISTS `skills`;
CREATE TABLE `skills` (
  `id` bigint NOT NULL,
  `name` char(40) NOT NULL,
  `alternatenames` varchar DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `skills` VALUES (1,'PHP');
INSERT INTO `skills` VALUES (2,'MySQL');
INSERT INTO `skills` VALUES (3,'Apache');
INSERT INTO `skills` VALUES (4,'Linux');
INSERT INTO `skills` VALUES (5,'AppEngine');
INSERT INTO `skills` VALUES (5,'MemCached', 'memcache');
INSERT INTO `skills` VALUES (6,'C++');
INSERT INTO `skills` VALUES (7,'C#');
INSERT INTO `skills` VALUES (8,'Visual Basic', 'vb');

-- SOURCES --

DROP TABLE IF EXISTS `sources`;
CREATE TABLE `sources` (
  `id` bigint NOT NULL,
  `name` char(40) NOT NULL,
  `URL` char(256) NOT NULL,
  `logoURL` char(256) NOT NULL
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `sources` VALUES (1,'Hacker News Network', 'http://www.hackernews.com/', 'hackernews.png');
INSERT INTO `sources` VALUES (2,'stackoverflow', 'http://www.stackoverflow.com/', 'stackoverflow.png');
INSERT INTO `sources` VALUES (3,'GitHub', 'http://www.github.com/', 'github.png');
INSERT INTO `sources` VALUES (4,'Elance', '', '');
INSERT INTO `sources` VALUES (5,'reddit', '', '');
INSERT INTO `sources` VALUES (6,'Quora', '', '');
INSERT INTO `sources` VALUES (7,'Facebook', '', '');

