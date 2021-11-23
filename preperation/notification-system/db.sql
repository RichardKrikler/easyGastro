DROP DATABASE IF EXISTS EGS;
CREATE DATABASE EGS CHARACTER SET utf8 COLLATE utf8_general_ci;
USE EGS;

CREATE TABLE PN_Subscriptions
(
    endpoint  VARCHAR(255) NOT NULL PRIMARY KEY,
    publicKey VARCHAR(88)  NOT NULL,
    authToken VARCHAR(24)  NOT NULL
);


INSERT INTO PN_Subscriptions (endpoint, publicKey, authToken) VALUE ('1', '2', '2');
DELETE FROM PN_Subscriptions WHERE endpoint = '';
UPDATE PN_Subscriptions SET publicKey = '';
UPDATE PN_Subscriptions SET authToken = '';
