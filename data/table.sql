
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `real_name` varchar(150) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `users` (`id`, `username`, `password`, `real_name`, `status`) VALUES
(1,	'admin',	'21232f297a57a5a743894a0e4a801fc3',	'Administrador',	1),
(2,	'teste',	'698dc19d489c4e4db73e28a713eab07b',	'teste',	1);