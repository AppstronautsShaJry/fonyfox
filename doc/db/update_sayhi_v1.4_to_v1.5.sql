DROP TABLE IF EXISTS `pin`;
CREATE TABLE IF NOT EXISTS `pin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reference_id` int NOT NULL,
  `type` int NOT NULL DEFAULT '1' COMMENT '1=post, 2=comment',
  `user_id` int NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
DROP TABLE IF EXISTS `collaborate`;
CREATE TABLE IF NOT EXISTS `collaborate` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reference_id` int NOT NULL,
  `type` int NOT NULL DEFAULT '1' COMMENT '1=post, 2=comment',
  `collaborator_id` int NOT NULL,
  `author_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1=pending,2 =reject,4=cancelled,active=10',
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`collaborator_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


