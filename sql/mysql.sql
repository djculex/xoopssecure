# SQL Dump for xoops iscanner module
# PhpMyAdmin Version: 4.0.4
# https://www.phpmyadmin.net
#
# Host: localhost
# Generated on: Fri Nov 04, 2022 to 12:31:31
# Server version: 8.0.29
# PHP Version: 7.4.30

#
# Structure table for `xoopssecure_issues`
#
CREATE TABLE `xoopssecure_issues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` text NOT NULL,
  `scantype` text NOT NULL,
  `value` text NOT NULL,
  `title` text NOT NULL,
  `filename` text NOT NULL,
  `dirname` text NOT NULL,
  `linenumber` text NOT NULL,
  `desc` text NOT NULL,
  `rating` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


#
# Structure table for `xoopssecure_stats`
#

CREATE TABLE `xoopssecure_stats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` text NOT NULL,
  `scanstart` text NOT NULL,
  `scanfinished` text NOT NULL,
  `permissues` text NOT NULL,
  `perfilestotal` text NOT NULL,
  `indexissues` text NOT NULL,
  `indexfilestotal` text NOT NULL,
  `malissues` text NOT NULL,
  `malfilestotal` text NOT NULL,
  `csissues` text NOT NULL,
  `csfilestotal` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure table for `xoopssecure_log`
#

CREATE TABLE `xoopssecure_log` (
  `id` int NOT NULL,
  `permissionscan` text NOT NULL,
  `indexfilesscan` text NOT NULL,
  `fullscan` text NOT NULL,
  `codestandardscan` text NOT NULL,
  `cronscan` text NOT NULL,
  `mallwarescan` text NOT NULL,
  `backup` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;