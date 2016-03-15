CREATE TABLE IF NOT EXISTS company(
    tradeName varchar(40) NOT NULL,
    CIF varchar(9) NOT NULL,
    address varchar(80),
    city varchar(30),
    region varchar(30),
    country varchar(30),
    phone varchar(15),
    fax varchar(15),
    mobile varchar(15),
    email varchar(40),
    logoUrl varchar(40),
    PRIMARY KEY (CIF)
)ENGINE=INNODB
 DEFAULT CHARACTER SET=utf8;

CREATE TABLE IF NOT EXISTS modules(
    id int AUTO_INCREMENT, 
    modName varchar(20),
    active boolean,    
    PRIMARY KEY (id)
)ENGINE=INNODB
 DEFAULT CHARACTER SET=utf8;

CREATE TABLE IF NOT EXISTS menus(
    idModule int  NOT NULL, 
    idMenu int,
    menuName varchar(15),
    pos int,
    PRIMARY KEY (idModule, idMenu),
    FOREIGN KEY (idModule) REFERENCES modules (id)
)ENGINE=INNODB
 DEFAULT CHARACTER SET=utf8;

CREATE TABLE IF NOT EXISTS roles(
    id int  NOT NULL, 
    rolName varchar(20),   
    PRIMARY KEY (id)
)ENGINE=INNODB
 DEFAULT CHARACTER SET=utf8;

INSERT INTO roles VALUES(1, 'Admin');






