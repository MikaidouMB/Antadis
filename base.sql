CREATE TABLE `groups` (
  `id_group` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_group`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `groups` VALUES (1,'Groupe 1'),(2,'Groupe 2'),(3,'Groupe 3');

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `user` (`username`, `email`, `password`, `last_login`) 
VALUES ('testuser1', 'testuser1@example.com', 'hashedpassword', '2022-01-01 12:00:00'),
       ('testuser2', 'testuser2@example.com', 'hashedpassword', '2022-01-01 12:00:00')
       ('testuser3', 'testuser3@example.com', 'hashedpassword', '2022-01-01 12:00:00')
       ;

CREATE TABLE `user_group` (
  `id_user` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  PRIMARY KEY (`id_user`,`id_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user_group` VALUES (1,2);

CREATE TABLE `session` (
  `id` char(64) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO user_group (id_user, id_group)
SELECT u.id_user, g.id_group
FROM user u
JOIN groups g ON u.id_user = 1 AND g.id_group = 2;

ALTER TABLE `user_group`
ADD FOREIGN KEY (`id_user`)
REFERENCES `user`(`id_user`),
ADD FOREIGN KEY (`id_group`)
REFERENCES `groups`(`id_group`);

ALTER TABLE user ADD is_loggedin TINYINT(1);



