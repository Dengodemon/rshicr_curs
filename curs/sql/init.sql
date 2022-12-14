CREATE DATABASE IF NOT EXISTS appDB;
CREATE USER IF NOT EXISTS 'user'@'%' IDENTIFIED BY 'password';
GRANT SELECT,UPDATE,INSERT,DELETE ON appDB.* TO 'user'@'%';
FLUSH PRIVILEGES;

USE appDB;
CREATE TABLE IF NOT EXISTS users (
  ID INT(11) NOT NULL AUTO_INCREMENT,
  dat VARCHAR(20) NOT NULL, 
  player VARCHAR(20) NOT NULL,
  PRIMARY KEY (ID)
);

INSERT INTO users (dat, player)
SELECT * FROM (SELECT '03-12-2022', 'X') AS tmp
WHERE NOT EXISTS (
    SELECT dat FROM users WHERE dat = '03-12-2022' AND player = 'X'
) LIMIT 1;

