DELETE FROM casted; DELETE FROM actors; DELETE FROM films; DELETE FROM users;

DROP TABLE IF EXISTS `films`;
CREATE TABLE `films` (
                         `id` int NOT NULL AUTO_INCREMENT,
                         `title` varchar(255) NOT NULL,
                         `release_year` int DEFAULT NULL,
                         `format` varchar(50) DEFAULT NULL,
                         `user_id` int DEFAULT NULL,
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `actors`;
CREATE TABLE `actors` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `fullname` varchar(100) DEFAULT NULL,
                          PRIMARY KEY (`id`),
                          UNIQUE KEY `fullname` (`fullname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE UNIQUE INDEX idx_film_actor ON casted (film_id, actor_id);

DROP TABLE IF EXISTS `casted`;
CREATE TABLE `casted` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `film_id` int NOT NULL,
                          `actor_id` int NOT NULL,
                          PRIMARY KEY (`id`),
                          UNIQUE KEY `idx_film_actor` (`film_id`,`actor_id`),
                          KEY `actor_id` (`actor_id`),
                          CONSTRAINT `casted_ibfk_1` FOREIGN KEY (`film_id`) REFERENCES `films` (`id`) ON DELETE CASCADE,
                          CONSTRAINT `casted_ibfk_2` FOREIGN KEY (`actor_id`) REFERENCES `actors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;