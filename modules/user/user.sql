CREATE TABLE IF NOT EXISTS user( 
    login varchar(12) NOT NULL, 
    pass varchar(80) NOT NULL, 
    firstName varchar(40) NOT NULL, 
    lastName varchar(60) NOT NULL, 
    email varchar(80) NOT NULL,
    appRole int NOT NULL, 
    disabled boolean,
    PRIMARY KEY (login)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
CREATE TABLE IF NOT EXISTS `accessLog` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `login` varchar(12) NOT NULL,
  `accessTime` datetime,
  `accessType` varchar(40),
   KEY (login)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

ALTER TABLE `accessLog` 
    ADD CONSTRAINT `fk_access_login` 
    FOREIGN KEY (`login`) 
    REFERENCES `valtusagestion`.`user`(`login`) 
    ON DELETE RESTRICT 
    ON UPDATE CASCADE;