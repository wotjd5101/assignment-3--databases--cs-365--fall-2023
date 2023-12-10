DROP USER IF EXISTS 'passwords_user'@'localhost';
CREATE USER 'passwords_user'@'localhost' IDENTIFIED BY 'summer19';
GRANT ALL privileges on *.* to 'passwords_user'@'localhost';

DROP database IF EXISTS student_passwords;
CREATE database student_passwords;
use student_passwords;

DROP TABLE IF EXISTS has;
DROP table IF EXISTS ID;
DROP TABLE IF EXISTS website;

CREATE TABLE ID (
  Personid INT auto_increment PRIMARY KEY,
  website varchar(30) not null,
  UserName varchar(15) not null,
  PW varbinary(200) default null,
  Comment text default null,
  UNIQUE KEY (UserName, website, Personid),
  INDEX idx_UserName (UserName),
  INDEX idx_website (website),
  INDEX idx_Personid (Personid)
);

CREATE TABLE website (
  Personid INT AUTO_INCREMENT PRIMARY KEY,
  Site VARCHAR(30) NOT NULL,
  URL TEXT NOT NULL,
  Email_Address VARCHAR(50) NOT NULL,
  UNIQUE KEY (Site, Email_Address, Personid)
);

CREATE TABLE has (
  UserID VARCHAR(15),
  Site_Name varchar(30),
  FOREIGN KEY (UserID) REFERENCES ID(UserName)
      ON UPDATE CASCADE
      ON DELETE SET NULL,
  FOREIGN KEY (Site_Name) REFERENCES ID(website)
      ON UPDATE CASCADE
      ON DELETE SET NULL
);
