
DROP TABLE IF EXISTS tinylink_websites;

CREATE TABLE tinylink_websites (
  wid int(11) NOT NULL auto_increment,
  fullurl mediumtext NOT NULL,
  ip varchar(20) NOT NULL default '',
  tinylink varchar(128) NOT NULL default '',
  showsplash int(1) NOT NULL default '1',
  PRIMARY KEY  (wid),
  UNIQUE KEY wid (wid)
);

DROP TABLE IF EXISTS tinylink_statistics;

CREATE TABLE tinylink_statistics (
  sid int(11) NOT NULL auto_increment,
  wid int(11) NOT NULL default '0',
  numb bigint(20) NOT NULL default '0',
  PRIMARY KEY  (sid),
  UNIQUE KEY sid (sid)
);

DROP TABLE IF EXISTS tinylink_ad;

CREATE TABLE tinylink_ad (
  aid int(11) NOT NULL auto_increment,
  ad_text text NOT NULL,
  PRIMARY KEY  (aid),
  UNIQUE KEY aid (aid)
);

DROP TABLE IF EXISTS tinylink_total;

CREATE TABLE tinylink_total (
  tid int(11) NOT NULL auto_increment,
  date datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (tid),
  UNIQUE KEY tid (tid)
);

INSERT INTO tinylink_ad (aid, ad_text) VALUES (1,'<div align=center><a href="#"><img src=images/banner1.gif width=468 height=60 border=0></a><br><img src=images/spacer.gif width=1 height=16><br><a href="#"><img src=images/banner1.gif width=468 height=60 border=0></a><br></div>');
