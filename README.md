# CRUD_Books

CREATE TABLE `author` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
 `surname` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8

CREATE TABLE `book` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `id_author` int(11) NOT NULL,
 `title` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 UNIQUE KEY `id` (`id`),
 KEY `id_author` (`id_author`),
 CONSTRAINT `book_ibfk_1` FOREIGN KEY (`id_author`) REFERENCES `author` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8


INSERT INTO author (id,name, surname) VALUES ('1','Jarosław','Grzędowicz');
INSERT INTO author (id,name, surname) VALUES ('2','Mark','Twain');
INSERT INTO book (id_author,title) VALUES ('1','Pan Lodowego Ogrodu tom 1');
INSERT INTO book (id_author,title) VALUES ('1','Pan Lodowego Ogrodu tom 2');
INSERT INTO book (id_author,title) VALUES ('1','Pan Lodowego Ogrodu tom 3');
INSERT INTO book (id_author,title) VALUES ('2','Przygody Tomka Sawyera');
INSERT INTO book (id_author,title) VALUES ('2','Przygody Hucka');
INSERT INTO book (id_author,title) VALUES ('2','Książę i żebrak');

