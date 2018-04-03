-- semi-kolon er det samme som GO i MySQL
-- DROP DATABASE IF EXISTS `TEC_H2_DatingWeb`;
-- CREATE DATABASE TEC_H2_DatingWeb;

USE `virtusbc_tec-dating`;

DROP TABLE IF EXISTS `Regions`;
DROP TABLE IF EXISTS `Countries`;
DROP TABLE IF EXISTS `RS_ProfileInterests`;
DROP TABLE IF EXISTS `Interests`;
DROP TABLE IF EXISTS `Users`;
DROP TABLE IF EXISTS `groups`;
DROP TABLE IF EXISTS `UserSession`;




CREATE TABLE Regions (
    regionID int(11) AUTO_INCREMENT PRIMARY KEY,
    regionName nvarchar(50)
);

INSERT INTO Regions (regionName)
VALUES 	('Nordjylland'),
		('Midtjylland'),
		('Sønderjylland'),
		('Fyn'),
		('Lolland'),
		('Falster'),
		('Møn'),
		('Sydsjælland'),
		('Vestsjælland'),
		('Nordsjælland'),
		('København'),
		('Uden for Danmark');

CREATE TABLE Countries (
    countryID int(11) AUTO_INCREMENT PRIMARY KEY,
    countryName nvarchar(50)
);

INSERT INTO Countries (countryName)
VALUES 	('Danmark'),
		('Finland'),
		('Norge'),
		('Sverige'),
		('Island'),
		('Åland'),
		('Grønland'),
		('Færøerne');

CREATE TABLE RS_ProfileInterests (
    interestId int(11),
    userId int(11)
);

CREATE TABLE Interests (
    interestID int(11) AUTO_INCREMENT PRIMARY KEY,
    interestName nvarchar(180)
);

INSERT INTO Interests (InterestName)
VALUES 	('Musik'),
		('Mad'),
		('Rejser'),
		('Mad Moneyz'),
		('Biler'),
		('Netflix'),
		('Lange gåture på stranden'),
		('Bjergbestigning'),
		('Ekstrem-sport'),
		('Surfing'),
		('En times offentlig trasnport i en kold bus mandag morgen'),
		('Mode'),
		('Gaming'),
		('Brætspil'),
		('Fitness');
    
CREATE TABLE Users (
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    username nvarchar(20) NOT NULL,
    userpassword nvarchar(255),
    name nvarchar(50),
    joined datetime,
    usergroup int(11),
    countryId int(11),
    regionId int(11),
    city nvarchar(50),
    sex bit(1),
    age date,
    imageFile longtext,
    email nvarchar(320),
    profileBio nvarchar(280),
    active bit(1) default 1
);

CREATE TABLE groups (
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    groupName NVARCHAR(20),
    permissions text
);

CREATE TABLE UserSession (
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    userID int(11),
    hash NVARCHAR(150)
);

CREATE TABLE Messages (
	id int(11) AUTO_INCREMENT PRIMARY KEY,
	msg_from_id int(11),
	msg_to_id int(11),
	msg_body text,
	msg_date datetime
);

CREATE TABLE Matches (
	matchid int(11) AUTO_INCREMENT PRIMARY KEY,
	matchdate datetime,
	match_from_id int(11),
	match_to_id int(11),
	status int(11)
);

INSERT INTO groups (groupName, permissions) values ('Standard user', '{"standard": 1}'); 
INSERT INTO groups (groupName, permissions) values ('Administrator', '{"admin": 1}');
INSERT INTO Users (username, userpassword, name, joined, usergroup, countryId, regionId, city, sex, age, email, profileBio, active) values ('virtus', '$2y$10$Moic2ANbBqyJJwQ3hJCVU.lvYdUtPH64jyXb99XB39HCYRCR7CAIy', 'virtus opstrup', NOW(), 1, 1, 11, 'Snekkersten', 0, '2018-03-07', 'virtusbradder@gmail.com', 'Velkommen til min profil', 1);

-- Kommando til at vise om et table har en PRIMARY KEY
-- SHOW INDEXES FROM $table WHERE Key_name = 'PRIMARY'
