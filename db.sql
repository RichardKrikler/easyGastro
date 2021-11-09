DROP DATABASE IF EXISTS EGS;
CREATE DATABASE EGS CHARACTER SET utf8 COLLATE utf8_general_ci;
USE EGS;

CREATE TABLE User
(
    pk_user_id INTEGER NOT NULL PRIMARY KEY,
    name       VARCHAR(255),
    passwort   VARCHAR(128),
    typ        VARCHAR(255)
);

CREATE TABLE Kellner
(
    pk_fk_pk_user_id  INTEGER NOT NULL PRIMARY KEY,
    fk_pk_tischgrp_id INTEGER NOT NULL
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
    pk_tischgrp_id INTEGER NOT NULL PRIMARY KEY,
    bezeichnung    VARCHAR(255)
);

CREATE TABLE Tisch
(
    pk_tischnr_id     INTEGER NOT NULL PRIMARY KEY,
    bezeichnung       VARCHAR(255),
    fk_pk_tischgrp_id INTEGER NOT NULL
);

CREATE TABLE Bestellung
(
    pk_bestellung_id INTEGER   NOT NULL,
    pk_timestamp_von TIMESTAMP NOT NULL,
    timestamp_bis    TIMESTAMP,
    status           VARCHAR(255),
    fk_pk_tischnr_id INTEGER,
    CONSTRAINT PRIMARY KEY (pk_bestellung_id, pk_timestamp_von)
);

CREATE TABLE Speisen
(
    pk_speise_id       INTEGER NOT NULL PRIMARY KEY,
    bezeichnung        VARCHAR(255),
    preis              DOUBLE,
    fk_pk_speisegrp_id INTEGER NOT NULL
);

CREATE TABLE Speisegruppe
(
    pk_speisegrp_id INTEGER NOT NULL PRIMARY KEY,
    bezeichnung     VARCHAR(255)
);

CREATE TABLE Getraenke
(
    pk_getraenk_id       INTEGER NOT NULL PRIMARY KEY,
    bezeichnung          VARCHAR(255),
    fk_pk_getraenkgrp_id INTEGER NOT NULL
);

CREATE TABLE Getaenkegruppe
(
    pk_getraenkgrp_id INTEGER NOT NULL PRIMARY KEY,
    bezeichnung       VARCHAR(255)
);

CREATE TABLE Menge
(
    pk_menge_id INTEGER NOT NULL PRIMARY KEY,
    wert        DOUBLE
);

CREATE TABLE getraenke_menge
(
    pk_fk_pk_getraenk_id INTEGER NOT NULL,
    pk_fk_pk_menge_id    INTEGER NOT NULL,
    preis                DOUBLE
);

CREATE TABLE bestellung_getraenke
(
    pk_fk_pk_bestellung_id INTEGER NOT NULL,
    pk_fk_pk_getraenke_id  INTEGER NOT NULL,
    anzahl                 INTEGER
);

CREATE TABLE bestellung_speisen
(
    pk_fk_pk_bestellung_id INTEGER NOT NULL,
    pk_fk_pk_speisen       INTEGER NOT NULL,
    anzahl                 INTEGER
);

ALTER TABLE Kellner
    ADD CONSTRAINT FOREIGN KEY (pk_fk_pk_user_id) REFERENCES User (pk_user_id) ON DELETE CASCADE,
    ADD CONSTRAINT FOREIGN KEY (fk_pk_tischgrp_id) REFERENCES Tischgruppe (pk_tischgrp_id);

ALTER TABLE Admin
    ADD CONSTRAINT FOREIGN KEY (pk_fk_pk_user_id) REFERENCES User (pk_user_id) ON DELETE CASCADE;

ALTER TABLE Kuechenmitarbeiter
    ADD CONSTRAINT FOREIGN KEY (pk_fk_pk_user_id) REFERENCES User (pk_user_id) ON DELETE CASCADE;

ALTER TABLE Tisch
    ADD CONSTRAINT FOREIGN KEY (fk_pk_tischgrp_id) REFERENCES Tischgruppe (pk_tischgrp_id);

ALTER TABLE Bestellung
    ADD CONSTRAINT FOREIGN KEY (fk_pk_tischnr_id) REFERENCES Tisch (pk_tischnr_id);

ALTER TABLE Getraenke
    ADD CONSTRAINT FOREIGN KEY (fk_pk_getraenkgrp_id) REFERENCES Getaenkegruppe (pk_getraenkgrp_id) ON DELETE CASCADE;

ALTER TABLE Speisen
    ADD CONSTRAINT FOREIGN KEY (fk_pk_speisegrp_id) REFERENCES Speisegruppe (pk_speisegrp_id) ON DELETE CASCADE;

ALTER TABLE bestellung_getraenke
    ADD CONSTRAINT FOREIGN KEY (pk_fk_pk_bestellung_id) REFERENCES Bestellung (pk_bestellung_id),
    ADD CONSTRAINT FOREIGN KEY (pk_fk_pk_getraenke_id) REFERENCES Getraenke (pk_getraenk_id);

ALTER TABLE bestellung_speisen
    ADD CONSTRAINT FOREIGN KEY (pk_fk_pk_bestellung_id) REFERENCES Bestellung (pk_bestellung_id),
    ADD CONSTRAINT FOREIGN KEY (pk_fk_pk_speisen) REFERENCES Speisen (pk_speise_id);

ALTER TABLE getraenke_menge
    ADD CONSTRAINT FOREIGN KEY (pk_fk_pk_getraenk_id) REFERENCES Getraenke (pk_getraenk_id),
    ADD CONSTRAINT FOREIGN KEY (pk_fk_pk_menge_id) REFERENCES Menge (pk_menge_id);
