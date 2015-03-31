CREATE TABLE xoopsecure_files (
  id int(8) NOT NULL auto_increment,
  filename text NOT NULL,
  filesize text NOT NULL,
  lastdate text default '',
  hashvalue text default '',
  file_ignore int(1) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;

CREATE TABLE xoopsecure_issues (
  id int(11) NOT NULL auto_increment,
  scantype int(1) NOT NULL,
  inittime TEXT NOT NULL,
  `time` text NOT NULL,
  filename text NOT NULL,
  filetype text NOT NULL,
  accessed text NOT NULL,
  changed text NOT NULL,
  modified text NOT NULL,
  permission int(11) NOT NULL,
  issuecat text NOT NULL,
  issuetype text NOT NULL,
  issuedesc text NOT NULL,
  linenumber int(11) NOT NULL,
  issuecode text NOT NULL,
  tag text NOT NULL,
  ignored int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE xoopsecure_ignores (
  id int(11) NOT NULL auto_increment,
  url text NOT NULL,
  linenumber int(11) NOT NULL,
  isfile int(11) not null default 0,
  isdir int(11) not null default 0,
  val text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE xoopsecure_stats (
  id int(11) NOT NULL auto_increment,
  typenr text NOT NULL,
  inittime text NOT NULL,
  issuenr int(11) NOT NULL,
  issues text NOT NULL,
  badusers text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM AUTO_INCREMENT=1;
