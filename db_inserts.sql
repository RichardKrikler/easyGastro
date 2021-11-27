# PW: 1234abcd
INSERT INTO User(pk_user_id, name, passwort, typ)
VALUES (1, 'Benedikt', '$2y$10$mC069U2NWIRxcBYoEV.IqOPPpx8JnMAuvS4wlHl2l2D9AmBmZnMT2', 'Admin'),
       (2, 'Julian', '$2y$10$mC069U2NWIRxcBYoEV.IqOPPpx8JnMAuvS4wlHl2l2D9AmBmZnMT2', 'Kellner'),
       (3, 'Richard', '$2y$10$mC069U2NWIRxcBYoEV.IqOPPpx8JnMAuvS4wlHl2l2D9AmBmZnMT2', 'Küchenmitarbeiter');

INSERT INTO Tischgruppe(pk_tischgrp_id, bezeichnung)
VALUES (1, 'Alle');

INSERT INTO Kellner(pk_fk_pk_user_id, fk_pk_tischgrp_id)
VALUES (2, 1);

INSERT INTO Admin(pk_fk_pk_user_id)
VALUES (1);

INSERT INTO Kuechenmitarbeiter(pk_fk_pk_user_id)
VALUES (3);

INSERT INTO Tisch(pk_tischnr_id, tischcode, fk_pk_tischgrp_id)
VALUES (1, 'A530B', 1),
       (2, 'B60LK', 1),
       (3, 'AAA3H', 1),
       (4, 'PLK7G', 1),
       (5, 'WSDF4', 1),
       (6, 'TZ889', 1),
       (7, 'QBV10', 1),
       (8, 'A578B', 1),
       (9, '5T0LK', 1),
       (10, 'OK56H', 1),
       (11, 'P08IG', 1),
       (12, 'RED67', 1),
       (13, 'ZU964', 1),
       (14, 'V34CX', 1);

INSERT INTO Bestellung(pk_bestellung_id, pk_timestamp_von, timestamp_bis, status, fk_pk_tischnr_id)
VALUES (1, '2021-05-10 18:16:33', '2021-05-10 20:35:26', 'Bezahlt', 1),
       (2, '2021-05-10 18:45:58', NULL, 'Serviert', 2),
       (3, '2021-05-10 18:58:39', NULL, 'Abholbereit', 3),
       (4, '2021-05-10 19:01:07', NULL, 'In-Bearbeitung', 4),
       (5, '2021-05-10 19:10:32', NULL, 'Offen', 5),
       (6, '2021-05-10 19:31:50', NULL, 'Offen', 6);

INSERT INTO Speisegruppe(pk_speisegrp_id, bezeichnung)
VALUES (1, 'Suppen'),
       (2, 'Hauptspeisen'),
       (3, 'Beilagen'),
       (4, 'Nachspeise');

INSERT INTO Speise(pk_speise_id, bezeichnung, preis, fk_pk_speisegrp_id)
VALUES (1, 'Frittatensuppe', 2.57, 1),
       (2, 'Kaspressknödelsuppe', 2.60, 1),
       (3, 'Wiener Schnitzel', 9.50, 2),
       (4, 'Lasagne', 8.70, 2),
       (5, 'Erdäpfelsalat', 1.50, 3),
       (6, 'Pommes', 2.00, 3),
       (7, 'Palatschinke', 5.15, 4),
       (8, 'Nutellaschnitte', 2.15, 4);

INSERT INTO bestellung_speise(pk_fk_pk_bestellung_id, pk_fk_pk_speise, anzahl)
VALUES (1, 1, 2),
       (1, 3, 1),
       (1, 4, 1),
       (1, 6, 1),
       (2, 7, 2),
       (3, 3, 1),
       (3, 5, 1),
       (4, 2, 1),
       (4, 4, 1),
       (4, 8, 1),
       (5, 2, 2),
       (5, 7, 1),
       (6, 1, 2),
       (6, 2, 3),
       (6, 3, 5),
       (6, 5, 4),
       (6, 6, 1),
       (6, 7, 2),
       (6, 8, 1);

INSERT INTO Getraenkegruppe(pk_getraenkegrp_id, bezeichnung)
VALUES (1, 'Alkoholfrei'),
       (2, 'Bier'),
       (3, 'Wein'),
       (4, 'Warme Getränke');

INSERT INTO Getraenk(pk_getraenk_id, bezeichnung, fk_pk_getraenkegrp_id)
VALUES (1, 'Leitungswasser', 1),
       (2, 'Eistee', 1),
       (3, 'Frucade', 1),
       (4, 'Gösser', 2),
       (5, 'Stiegl', 2),
       (6, 'Weisswein', 3),
       (7, 'Rotwein', 3),
       (8, 'Spritzer', 3),
       (9, 'Kaffee', 4),
       (10, 'Kakao', 4);

INSERT INTO Menge(pk_menge_id, wert)
VALUES (1, 0.125),
       (2, 0.25),
       (3, 0.33),
       (4, 0.50),
       (5, 1.00);

INSERT INTO Getraenk_Menge(pk_getraenkmg_id, preis, fk_pk_getraenk_id, fk_pk_menge_id)
VALUES (1, 0.15, 1, 2),
       (2, 0.20, 1, 4),
       (3, 2.00, 2, 3),
       (4, 1.80, 3, 3),
       (5, 2.50, 4, 3),
       (6, 3.00, 4, 4),
       (7, 2.50, 5, 3),
       (8, 3.00, 5, 4),
       (9, 2.80, 6, 1),
       (10, 2.80, 7, 1),
       (11, 2.20, 8, 3),
       (12, 2.70, 8, 4),
       (13, 2.00, 9, 1),
       (14, 1.90, 10, 1);

INSERT INTO bestellung_getraenkmenge(pk_fk_pk_bestellung_id, pk_fk_pk_getraenkmg_id, anzahl)
VALUES (1, 1, 1),
       (1, 4, 1),
       (2, 12, 1),
       (3, 8, 1),
       (4, 7, 1),
       (5, 3, 2),
       (6, 2, 2),
       (6, 5, 3);