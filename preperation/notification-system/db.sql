DROP DATABASE IF EXISTS EGS;
CREATE DATABASE EGS CHARACTER SET utf8 COLLATE utf8_general_ci;
USE EGS;

CREATE TABLE PN_Subscriptions
(
    endpoint        VARCHAR(255) NOT NULL PRIMARY KEY,
    publicKey       VARCHAR(88)  NOT NULL,
    authToken       VARCHAR(24)  NOT NULL,
    contentEncoding VARCHAR(100) NOT NULL
);
