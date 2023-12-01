DROP DATABASE IF EXISTS student_passwords;

CREATE DATABASE student_passwords;

SET block_encryption_mode = 'aes-256-cbc';
SET @key_str = 0xBD2B1AAF7EF4F09BE9F52CE2D8D599674D81AA9D6A4421696DC4D93DD0619D682CE56B4D64A9EF097761CED99E0F67265B5F76085E5B0EE7CA4696B2AD6FE2B2;
SET @init_vector = 0xB80A3AC16DFA07C430ABFCF0A7463F77;

USE student_passwords;

DROP USER IF EXISTS 'passwords_user'@'localhost';

CREATE USER 'passwords_user'@'localhost' IDENTIFIED BY 'pR4w3jL8mO';
GRANT ALL ON student_passwords.* TO 'passwords_user'@'localhost';

CREATE TABLE websites(
  site_id INT AUTO_INCREMENT PRIMARY KEY,
  site_name VARCHAR(255) NOT NULL,
  site_url VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE users(
  user_id INT PRIMARY KEY AUTO_INCREMENT,
  first_name VARCHAR(100) NOT NULL ,
  last_name VARCHAR(100) NOT NULL ,
  username VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL,
  comment TEXT,
  time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES websites(site_id) ON DELETE CASCADE
);

CREATE TABLE passwords(
  password_id INT PRIMARY KEY AUTO_INCREMENT,
  password BLOB NOT NULL,
  FOREIGN KEY (password_id) REFERENCES users(user_id) ON DELETE CASCADE
);

INSERT INTO websites (site_name, site_url) VALUES
  ('Facebook', 'http://www.facebook.com'),
  ('Youtube', 'https://www.youtube.com');

INSERT INTO users (first_name, last_name, username, email, comment) VALUES
  ('Aden', 'Docchio', 'adocchio', 'john@comcast.net', 'Good for keeping up with friends'),
  ('Aden', 'Docchio', 'adocchio', 'jane@gmail.com', 'Like to watch videos');

INSERT INTO passwords (password) VALUES
  (AES_ENCRYPT('pR4w3jL8mOc', @key_str, @init_vector)),
  (AES_ENCRYPT('yE6&uX2!tV1zG', @key_str, @init_vector));

