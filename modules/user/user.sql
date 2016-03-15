CREATE TABLE IF NOT EXISTS user( 
    id int AUTO_INCREMENT, 
    login varchar(12) NOT NULL, 
    pass varchar(80) NOT NULL, 
    firstName varchar(40) NOT NULL, 
    lastName varchar(60) NOT NULL, 
    email varchar(80) NOT NULL,
    appRole int NOT NULL, 
    disabled boolean,
    PRIMARY KEY (id),
    UNIQUE KEY (login)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
CREATE TABLE IF NOT EXISTS `accessLog` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `login` int(11) NOT NULL,
  `accessTime` datetime,
  `accessType` varchar(40),
   KEY (login)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

ALTER TABLE `accessLog` 
    ADD CONSTRAINT `fk_access_login` 
    FOREIGN KEY (`login`) 
    REFERENCES `valtusagestion`.`user`(`id`) 
    ON DELETE RESTRICT 
    ON UPDATE CASCADE;