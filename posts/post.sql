CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `published` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `published` (`published`)
) ENGINE=InnoDB AUTO_INCREMENT=8415 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
