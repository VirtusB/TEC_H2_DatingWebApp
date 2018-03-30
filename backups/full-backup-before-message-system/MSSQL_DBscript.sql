-- CTRL + SHIFT + E = Execute script

USE master

GO

IF EXISTS(select * from sys.databases where name='OOP_Login_Register')
ALTER DATABASE OOP_Login_Register SET SINGLE_USER WITH ROLLBACK IMMEDIATE

IF EXISTS(select * from sys.databases where name='OOP_Login_Register')
DROP DATABASE OOP_Login_Register

CREATE DATABASE OOP_Login_Register

GO

USE OOP_Login_Register

GO

CREATE TABLE Regions (
	regionID int IDENTITY(1,1) PRIMARY KEY,
	regionName nvarchar(50)
)

GO

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
		('Uden for Danmark')

CREATE TABLE Countries (
	countryID int IDENTITY(1,1) PRIMARY KEY,
	countryName nvarchar(50)
)

GO

INSERT INTO Countries (countryName)
VALUES 	('Danmark'),
		('Finland'),
		('Norge'),
		('Sverige'),
		('Island'),
		('Åland'),
		('Grønland'),
		('Færøerne')

GO

CREATE TABLE RS_ProfileInterests (
    interestId int,
    userId int
)

CREATE TABLE Interests (
    interestID int IDENTITY(1,1) PRIMARY KEY,
    interestName nvarchar(180) NOT NULL,
)

GO

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
		('Fitness')
		
CREATE TABLE Users(
    id int IDENTITY(1,1) primary key,
    username NVARCHAR(20) NOT NULL,
    userpassword NVARCHAR(255),
    name nvarchar(50),
    joined datetime,
    usergroup int,
	countryId int,
    regionId int,
	city nvarchar(50),
	sex bit,
	age date,
	imageFile varchar(max),
	email nvarchar(320),
	profileBio nvarchar(280),
	active bit NOT NULL default 1
)

CREATE TABLE groups(
    id int IDENTITY(1,1) PRIMARY KEY,
    groupName NVARCHAR(20),
    permissions text
)

CREATE TABLE UserSession(
    id int IDENTITY(1,1) PRIMARY KEY,
    userID int,
    hash NVARCHAR(150)
)

GO

INSERT INTO groups (groupName, permissions) values ('Standard user', '{"standard": 1}') 
INSERT INTO groups (groupName, permissions) values ('Administrator', '{"admin": 1}')
INSERT INTO Users (username, userpassword, name, joined, usergroup, countryId, regionId) values ('virtus', '$2y$10$Moic2ANbBqyJJwQ3hJCVU.lvYdUtPH64jyXb99XB39HCYRCR7CAIy', 'virtus opstrup', GETDATE(), 1, 1, 11)



 