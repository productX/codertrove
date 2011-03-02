#
#  Table structure for active users table
#
DROP TABLE IF EXISTS activeusers;
CREATE TABLE activeusers (
  `username` char(35) NOT NULL PRIMARY KEY,
  usagetimestamp int(11) unsigned not null
);


#
#  Table structure for active guests table
#
DROP TABLE IF EXISTS activeguests;
CREATE TABLE activeguests (
  `ip` char(15) primary key,
  usagetimestamp int(11) unsigned not null
);


#
#  Table structure for banned users table
#
DROP TABLE IF EXISTS bannedusers;
CREATE TABLE bannedusers (
  `username` char(35) NOT NULL PRIMARY KEY,
  usagetimestamp int(11) unsigned not null
);

