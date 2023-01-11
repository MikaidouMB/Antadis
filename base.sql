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
  PRIMARY KEY (`id_user`),
  `is_loggedin` TINYINT(1)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


INSERT INTO `user` (`id_user`, `username`, `email`, `password`, `last_login`, `is_loggedin`)
 VALUES ('1', 'user1', 'user1@hotmail.fr', '$argon2id$v=19$m=65536,t=4,p=1$dzlMNS9uMzdFUnA2ZGRJMA$6q2/3eHVo4lvOaJdJyomhkyD8ADO1fK+68VFvvA5ujw', NULL, '0'),
 ('2', 'user2', 'user2@hotmail.fr', '$argon2id$v=19$m=65536,t=4,p=1$dzlMNS9uMzdFUnA2ZGRJMA$6q2/3eHVo4lvOaJdJyomhkyD8ADO1fK+68VFvvA5ujw', NULL, '0'),
('3', 'user3', 'user3@hotmail.fr', '$argon2id$v=19$m=65536,t=4,p=1$dzlMNS9uMzdFUnA2ZGRJMA$6q2/3eHVo4lvOaJdJyomhkyD8ADO1fK+68VFvvA5ujw', NULL, '0')
;

CREATE TABLE `user_group` (
  `id_user` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  PRIMARY KEY (`id_user`,`id_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user_group` (`id_user`, `id_group`) VALUES ('1', '1'), ('2', '2'), ('3', '3');
CREATE TABLE `user_group` (
  `id_user` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  PRIMARY KEY (`id_user`,`id_group`),
  FOREIGN KEY (`id_user`) REFERENCES `user`(`id_user`),
  FOREIGN KEY (`id_group`) REFERENCES `groups`(`id_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user_group` (`id_user`, `id_group`) VALUES ('1', '1'), ('2', '2'), ('3', '3');

CREATE TABLE `session` (
  `id` char(64) NOT NULL,
  `userid` int(11) NOT NULL,
  FOREIGN KEY (`userid`) REFERENCES `user`(`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
