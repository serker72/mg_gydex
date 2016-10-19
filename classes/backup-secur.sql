#SKD101|vengo|7|2009.10.12 13:44:10|157|123|3|23|4|2|2

DROP TABLE IF EXISTS `discr_group_rights`;
CREATE TABLE `discr_group_rights` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` bigint(20) unsigned DEFAULT NULL,
  `right_id` bigint(20) unsigned DEFAULT NULL,
  `object_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=209 ;

INSERT INTO `discr_group_rights` VALUES
(2, 4, 1, 5),
(3, 4, 4, 5),
(101, 4, 1, 11),
(121, 8, 1, 1),
(6, 4, 2, 5),
(7, 5, 1, 5),
(52, 4, 4, 10),
(120, 8, 2, 2),
(21, 4, 1, 2),
(74, 5, 1, 1),
(51, 4, 3, 10),
(35, 8, 1, 5),
(20, 5, 1, 2),
(22, 4, 3, 2),
(23, 4, 2, 2),
(24, 4, 4, 2),
(25, 4, 3, 5),
(71, 4, 4, 1),
(70, 4, 3, 1),
(69, 4, 2, 1),
(68, 4, 1, 1),
(73, 4, 2, 10),
(72, 4, 1, 10),
(67, 4, 4, 9),
(98, 4, 4, 11),
(97, 4, 3, 11),
(96, 4, 2, 11),
(47, 4, 3, 9),
(66, 4, 2, 9),
(62, 4, 1, 9),
(138, 4, 1, 14),
(90, 4, 1, 12),
(91, 4, 2, 12),
(84, 4, 3, 12),
(85, 4, 4, 12),
(102, 4, 1, 13),
(105, 4, 2, 13),
(88, 4, 3, 13),
(106, 4, 4, 13),
(107, 4, 1, 4),
(108, 4, 2, 4),
(109, 4, 3, 4),
(110, 4, 4, 4),
(111, 8, 2, 1),
(112, 8, 3, 1),
(113, 8, 4, 1),
(123, 8, 1, 4),
(115, 8, 1, 9),
(116, 8, 1, 10),
(117, 8, 1, 11),
(118, 8, 1, 12),
(137, 8, 1, 13),
(122, 8, 1, 2),
(124, 4, 1, 3),
(125, 4, 2, 3),
(126, 4, 3, 3),
(127, 4, 4, 3),
(128, 5, 1, 3),
(136, 8, 1, 3),
(130, 4, 1, 6),
(131, 4, 2, 6),
(132, 4, 3, 6),
(133, 4, 4, 6),
(135, 8, 1, 6),
(139, 4, 2, 14),
(140, 4, 3, 14),
(141, 4, 4, 14),
(181, 8, 1, 14),
(143, 4, 1, 15),
(144, 4, 2, 15),
(145, 4, 3, 15),
(146, 4, 4, 15),
(182, 8, 1, 15),
(154, 8, 1, 16),
(150, 4, 1, 16),
(151, 4, 2, 16),
(152, 4, 3, 16),
(153, 4, 4, 16),
(155, 4, 1, 17),
(156, 4, 2, 17),
(157, 4, 3, 17),
(158, 4, 4, 17),
(159, 8, 1, 17),
(160, 4, 1, 18),
(161, 4, 2, 18),
(162, 4, 3, 18),
(163, 4, 4, 18),
(183, 8, 1, 18),
(166, 4, 1, 19),
(167, 4, 2, 19),
(168, 4, 3, 19),
(169, 4, 4, 19),
(184, 8, 1, 19),
(171, 4, 1, 20),
(172, 4, 2, 20),
(173, 4, 3, 20),
(174, 4, 4, 20),
(185, 8, 1, 20),
(176, 4, 1, 21),
(177, 4, 2, 21),
(178, 4, 3, 21),
(179, 4, 4, 21),
(186, 8, 1, 21),
(191, 4, 1, 23),
(192, 4, 2, 23),
(189, 4, 3, 23),
(190, 4, 4, 23),
(193, 4, 1, 24),
(194, 4, 2, 24),
(195, 4, 3, 24),
(196, 4, 4, 24),
(197, 4, 1, 22),
(198, 4, 2, 22),
(199, 4, 3, 22),
(200, 4, 4, 22),
(201, 8, 1, 22),
(202, 8, 1, 23),
(203, 8, 1, 24),
(204, 4, 1, 25),
(205, 4, 2, 25),
(206, 4, 3, 25),
(207, 4, 4, 25),
(208, 8, 1, 25);

DROP TABLE IF EXISTS `discr_groups`;
CREATE TABLE `discr_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `info` text ,
  `is_blocked` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 ;

INSERT INTO `discr_groups` VALUES
(4, 'Администраторы', 'проба \nпроба', 0),
(5, 'Гости', 'sdgds\r\nаппра', 0),
(8, 'Пользователи', '', 0);

DROP TABLE IF EXISTS `discr_objects`;
CREATE TABLE `discr_objects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `info` text ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 ;

INSERT INTO `discr_objects` VALUES
(9, 'Языки сайта', ''),
(2, 'Списки групп пользователей', ''),
(10, 'Глобальные настройки', ''),
(5, 'Списки пользователей', ''),
(11, 'Дерево сайта', ''),
(1, 'Списки объектов системы', ''),
(12, 'Текстовые разделы', ''),
(13, 'Библиотека языковых ресурсов', ''),
(4, 'Управление правами пользователей', ''),
(3, 'Управление правами групп', ''),
(6, 'Пользователи в группах', ''),
(14, 'Заказы по каталогу', ''),
(15, 'Позиции заказов', ''),
(16, 'Покупатели сайта', ''),
(17, 'Список фирм-производителей', ''),
(18, 'Новости сайта', ''),
(19, 'Ссылки', ''),
(20, 'Статьи', ''),
(21, 'Фотогалереи', ''),
(22, 'Товары и словари свойств', ''),
(23, 'Управление валютами', ''),
(24, 'Управление ценами', ''),
(25, 'Загрузка медиа', 'загрузка и управление фото, csv-файлами и ...');

DROP TABLE IF EXISTS `discr_rights`;
CREATE TABLE `discr_rights` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255)  DEFAULT NULL,
  `info` text ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 /*!40101 DEFAULT CHARSET=cp1251 */;

INSERT INTO `discr_rights` VALUES
(1, 'r', 'чтение'),
(2, 'a', 'изменение'),
(3, 'w', 'создание'),
(4, 'd', 'удаление');

DROP TABLE IF EXISTS `discr_user_rights`;
CREATE TABLE `discr_user_rights` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `right_id` bigint(20) unsigned DEFAULT NULL,
  `object_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 /*!40101 DEFAULT CHARSET=cp1251 */;

DROP TABLE IF EXISTS `discr_users`;
CREATE TABLE `discr_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255)  DEFAULT NULL,
  `passw` varchar(255)  DEFAULT NULL,
  `username` varchar(255)  DEFAULT NULL,
  `address` text,
  `email` varchar(255)  DEFAULT NULL,
  `phone` varchar(255)  DEFAULT NULL,
  `is_blocked` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 /*!40101 DEFAULT CHARSET=cp1251 */;

INSERT INTO `discr_users` VALUES
(10, 'test', '12e086066892a311b752673a28583d3f', '', '', '', '', 0),
(9, 'admin', '12e086066892a311b752673a28583d3f', '', '', '', '', 0);

DROP TABLE IF EXISTS `discr_users_by_groups`;
CREATE TABLE `discr_users_by_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `group_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 /*!40101 DEFAULT CHARSET=cp1251 */;

INSERT INTO `discr_users_by_groups` VALUES
(22, 10, 8),
(16, 9, 4);

