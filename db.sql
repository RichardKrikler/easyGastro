DROP DATABASE IF EXISTS EGS;
CREATE DATABASE EGS CHARACTER SET utf8 COLLATE utf8_general_ci;
USE EGS;

CREATE TABLE User
(
    pk_user_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name       VARCHAR(255),
    passwort   VARCHAR(128),
    typ        VARCHAR(255)
);

CREATE TABLE Kellner
(
    pk_fk_pk_user_id  INTEGER NOT NULL PRIMARY KEY,
    fk_pk_tischgrp_id INTEGER
);

CREATE TABLE Admin
(
    pk_fk_pk_user_id INTEGER NOT NULL PRIMARY KEY
);

CREATE TABLE Kuechenmitarbeiter
(
    pk_fk_pk_user_id INTEGER NOT NULL PRIMARY KEY
);

CREATE TABLE Tischgruppe
(
    pk_tischgrp_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    bezeichnung    VARCHAR(255)
);

CREATE TABLE Tisch
(
    pk_tischnr_id     INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    tischcode         VARCHAR(255),
    fk_pk_tischgrp_id INTEGER
);

CREATE TABLE Bestellung
(
    pk_bestellung_id INTEGER  NOT NULL,
    pk_timestamp_von DATETIME NOT NULL,
    timestamp_bis    DATETIME,
    status           VARCHAR(255),
    fk_pk_tischnr_id INTEGER,
    CONSTRAINT PRIMARY KEY (pk_bestellung_id, pk_timestamp_von)
);

CREATE TABLE Speise
(
    pk_speise_id       INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    bezeichnung        VARCHAR(255),
    preis              DOUBLE(10, 2),
    fk_pk_speisegrp_id INTEGER
);

CREATE TABLE Speisegruppe
(
    pk_speisegrp_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    bezeichnung     VARCHAR(255)
);

CREATE TABLE Getraenk
(
    pk_getraenk_id        INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    bezeichnung           VARCHAR(255),
    fk_pk_getraenkegrp_id INTEGER
);

CREATE TABLE Getraenkegruppe
(
    pk_getraenkegrp_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    bezeichnung        VARCHAR(255)
);

CREATE TABLE Menge
(
    pk_menge_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    wert        FLOAT
);

CREATE TABLE Getraenk_Menge
(
    pk_getraenkmg_id  INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    preis             DOUBLE(10, 2),
    fk_pk_getraenk_id INTEGER,
    fk_pk_menge_id    INTEGER
);

CREATE TABLE bestellung_getraenkmenge
(
    pk_fk_pk_bestellung_id INTEGER NOT NULL,
    pk_fk_pk_getraenkmg_id INTEGER,
    anzahl                 INTEGER
);

CREATE TABLE bestellung_speise
(
    pk_fk_pk_bestellung_id INTEGER NOT NULL,
    pk_fk_pk_speise        INTEGER,
    anzahl                 INTEGER
);

CREATE TABLE PN_Subscriptions
(
    endpoint        VARCHAR(255) NOT NULL PRIMARY KEY,
    publicKey       VARCHAR(88)  NOT NULL,
    authToken       VARCHAR(24)  NOT NULL,
    contentEncoding VARCHAR(100) NOT NULL,
    fk_pk_user_id   INTEGER      NOT NULL
);

# -----

ALTER TABLE Kellner
    ADD CONSTRAINT FOREIGN KEY (pk_fk_pk_user_id) REFERENCES User (pk_user_id) ON DELETE CASCADE,
    ADD CONSTRAINT FOREIGN KEY (fk_pk_tischgrp_id) REFERENCES Tischgruppe (pk_tischgrp_id) ON DELETE SET NULL;

ALTER TABLE Admin
    ADD CONSTRAINT FOREIGN KEY (pk_fk_pk_user_id) REFERENCES User (pk_user_id) ON DELETE CASCADE;

ALTER TABLE Kuechenmitarbeiter
    ADD CONSTRAINT FOREIGN KEY (pk_fk_pk_user_id) REFERENCES User (pk_user_id) ON DELETE CASCADE;

ALTER TABLE Tisch
    ADD CONSTRAINT FOREIGN KEY (fk_pk_tischgrp_id) REFERENCES Tischgruppe (pk_tischgrp_id) ON DELETE SET NULL;

ALTER TABLE Bestellung
    ADD CONSTRAINT FOREIGN KEY (fk_pk_tischnr_id) REFERENCES Tisch (pk_tischnr_id);

ALTER TABLE Getraenk
    ADD CONSTRAINT FOREIGN KEY (fk_pk_getraenkegrp_id) REFERENCES Getraenkegruppe (pk_getraenkegrp_id) ON DELETE SET NULL;

ALTER TABLE Speise
    ADD CONSTRAINT FOREIGN KEY (fk_pk_speisegrp_id) REFERENCES Speisegruppe (pk_speisegrp_id) ON DELETE SET NULL;

ALTER TABLE bestellung_getraenkmenge
    ADD CONSTRAINT FOREIGN KEY (pk_fk_pk_bestellung_id) REFERENCES Bestellung (pk_bestellung_id),
    ADD CONSTRAINT FOREIGN KEY (pk_fk_pk_getraenkmg_id) REFERENCES Getraenk_Menge (pk_getraenkmg_id) ON DELETE SET NULL;

ALTER TABLE bestellung_speise
    ADD CONSTRAINT FOREIGN KEY (pk_fk_pk_bestellung_id) REFERENCES Bestellung (pk_bestellung_id),
    ADD CONSTRAINT FOREIGN KEY (pk_fk_pk_speise) REFERENCES Speise (pk_speise_id) ON DELETE SET NULL;

ALTER TABLE Getraenk_Menge
    ADD CONSTRAINT FOREIGN KEY (fk_pk_getraenk_id) REFERENCES Getraenk (pk_getraenk_id) ON DELETE SET NULL,
    ADD CONSTRAINT FOREIGN KEY (fk_pk_menge_id) REFERENCES Menge (pk_menge_id) ON DELETE SET NULL;

ALTER TABLE PN_Subscriptions
    ADD CONSTRAINT FOREIGN KEY (fk_pk_user_id) REFERENCES User (pk_user_id) ON DELETE CASCADE;
