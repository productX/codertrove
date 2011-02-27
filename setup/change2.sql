
-- CODERSKILLS --

DROP TABLE IF EXISTS `coderskills`;
CREATE TABLE `coderskills` (
  `coderid` bigint NOT NULL,
  `skillid` bigint NOT NULL,
  `expertise` int NOT NULL,
  `numposts` int NOT NULL,
  PRIMARY KEY (`coderid`, `skillid`)
) ENGINE=MyISAM;

-- SKILLTYPES --

DROP TABLE IF EXISTS `skilltypes`;
CREATE TABLE `skilltypes` (
  `id` bigint NOT NULL,
  `name` char(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;
