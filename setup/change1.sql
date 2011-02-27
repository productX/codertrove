-- CHANGE FILE --

ALTER TABLE `sources` ADD (`statusblob` text);
ALTER TABLE `coderactivity` ADD (`numreplies` int, `ballsrating` boolean, `posttime` datetime);
ALTER TABLE `coderactivity` CHANGE `likes` `numlikes` int DEFAULT 0;
ALTER TABLE `users` ADD (`registertime` datetime NOT NULL);
ALTER TABLE `skills` ADD (`skilltype` int NOT NULL DEFAULT 0);
ALTER TABLE `skills` CHANGE `alternatenames` `alternatenames` varchar(300) DEFAULT '';
ALTER TABLE `payments` CHANGE `paymenttimestamp` `paymenttime` datetime NOT NULL;
ALTER TABLE `coders` DROP `shorthandle`;
ALTER TABLE `coders` ADD (`twitterURL` char(255), `fbURL` char(255), `otherURL` char(255), `handle` char(70));
ALTER TABLE `codersourceprofiles` CHANGE `datejoined` `joindate` datetime;
ALTER TABLE `codersourceprofiles` ADD (`sourcesiteuserid` bigint, `about` text);

