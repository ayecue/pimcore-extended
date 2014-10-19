CREATE TABLE `blog_references` (
  `blogDocumentId` int(11) unsigned NOT NULL,
  `blogComponentName` var(11) unsigned NOT NULL,
  `postObjectId` varchar(255) unsigned NOT NULL,
  `postDocumentId` int(11) unsigned NOT NULL,
  UNIQUE (`blogDocumentId`,`blogComponentName`,`postObjectId`,`postDocumentId`)
) DEFAULT CHARSET=utf8;