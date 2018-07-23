/*create new DB "GSC"*/
DROP DATABASE IF EXISTS GSC;

CREATE DATABASE GSC;

GRANT ALL PRIVILEGES ON GSC.* to zack@localhost IDENTIFIED BY 'rossman';

USE GSC;

/*create a table holding info on the girl scouts who refer customers to orders */
CREATE TABLE GirlScout (
  gsid INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(64) NOT NULL,
  troop_name VARCHAR(64) NOT NULL
);

/*create a table holding info on the orders made by customers, referred by girl scouts */
CREATE TABLE Orders (
  gsid INT UNSIGNED NOT NULL,
  orderid INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  cid INT UNSIGNED NOT NULL
);

/*create a table holding info on the customers making the orders*/
CREATE TABLE Customer (
  cid INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(64) NOT NULL,
  street_address VARCHAR(64) NOT NULL,
  city VARCHAR(64) NOT NULL,
  state VARCHAR(64) NOT NULL,
  zip INT NOT NULL
);

/*create a table holding info on the type/quanitity of each cookie associated with each order*/
CREATE TABLE Cookies (
  orderid INT NOT NULL,
  type VARCHAR(64) NOT NULL,
  quantity INT NOT NULL,
  price INT NOT NULL
);
