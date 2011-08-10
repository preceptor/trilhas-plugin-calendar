CREATE TABLE IF NOT EXISTS `calendar` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `classroom_id` bigint(20) DEFAULT NULL,
  `description` text NOT NULL,
  `begin` date NOT NULL,
  `end` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `classroom_id` (`classroom_id`)
);

ALTER TABLE `calendar`
  ADD CONSTRAINT `calendar_ibfk_1` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`),
  ADD CONSTRAINT `calendar_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
