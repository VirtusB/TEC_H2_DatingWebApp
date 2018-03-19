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

CREATE TABLE Users(
    id int IDENTITY(1,1) primary key,
    username NVARCHAR(20) NOT NULL,
    userpassword NVARCHAR(255),
    name nvarchar(50),
    joined datetime,
    usergroup int
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

INSERT INTO groups (groupName, permissions) values ('Standard user', '{"standard": 1}') 
INSERT INTO groups (groupName, permissions) values ('Administrator', '{"admin": 1}')
INSERT INTO Users (username, userpassword, name, joined, usergroup) values ('virtus', '$2y$10$Moic2ANbBqyJJwQ3hJCVU.lvYdUtPH64jyXb99XB39HCYRCR7CAIy', 'virtus opstrup', GETDATE(), 1)
 