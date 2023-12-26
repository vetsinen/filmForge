DROP TABLE IF EXISTS `films`;

CREATE TABLE `films` (
                          `id` int NOT NULL AUTO_INCREMENT,
                          `title` varchar(255) NOT NULL,
                          `release_year` int DEFAULT NULL,
                          `format` varchar(50) DEFAULT NULL,
                          PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE UNIQUE INDEX idx_film_actor ON casted (film_id, actor_id);
