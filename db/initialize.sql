CREATE TABLE IF NOT EXISTS `body`
(
    `id`   int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` set ('Non renseignée','Bouche','Bras','Buste','Cheveux','Contour des yeux','Corps','Dos','Jambes','Longueurs','Mains','Paupières','Pieds','Pointes','Racines','Ventre','Visage') DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

REPLACE INTO `body` (`id`, `name`)
VALUES (28, 'Racines'),
       (27, 'Pointes'),
       (26, 'Pieds'),
       (25, 'Paupières'),
       (24, 'Mains'),
       (23, 'Longueurs'),
       (22, 'Jambes'),
       (21, 'Dos'),
       (20, 'Corps'),
       (19, 'Contour des yeux'),
       (18, 'Cheveux'),
       (17, 'Buste'),
       (16, 'Bras'),
       (15, 'Bouche'),
       (29, 'Ventre'),
       (30, 'Visage'),
       (31, 'Non renseignée');

CREATE TABLE IF NOT EXISTS `recipe`
(
    `id`          int(10)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name`        varchar(100) NULL DEFAULT NULL,
    `time`        int(5)       NULL DEFAULT NULL,
    `ingredients` int(10)      NULL DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

REPLACE INTO `recipe` (`id`, `name`, `time`, `ingredients`)
VALUES (1, 'Composition à diffuser \"Synergie Concentration\"', 5, 5),
       (2, 'Composition à diffuser \"Persévérance & Volonté\" au Basilic', 5, 5),
       (3, 'Poudre de maquillage', 10, 14),
       (4, 'Gel douche', 15, 15),
       (5, 'Masque nutrition', 5, 19),
       (6, 'Shampoing purifiant', 7, 20),
       (7, 'Baume à lèvres nourrissant', 25, 22),
       (8, 'Masque nutrition', 5, 21),
       (9, 'Eau micellaire', 6, 17),
       (10, 'Eau micellaire', 6, 29),
       (11, 'Composition à diffuser', 5, 2),
       (12, 'Composition à diffuser', 5, 3),
       (13, 'Composition à diffuser', 5, 4),
       (14, 'Composition à diffuser', 5, 5),
       (15, 'Composition à diffuser', 5, 6),
       (16, 'Composition à diffuser', 5, 7),
       (17, 'Composition à diffuser', 5, 8),
       (18, 'Composition à diffuser', 5, 9),
       (19, 'Composition à diffuser', 5, 10),
       (20, 'Composition à diffuser', 5, 11),
       (21, 'Composition à diffuser', 5, 12),
       (22, 'Composition à diffuser', 5, 13),
       (23, 'Inhalation anti-infection', 5, 1),
       (24, 'Parfum bien-être', 10, 16),
       (25, 'Huile protectrice cheveux', 17, 18),
       (26, 'Colorant capillaire végétal roux', 7, 23),
       (27, 'Crème de jour hydratante', 15, 24),
       (28, 'Crème de jour hydratante', 15, 25),
       (29, 'Crème de jour hydratante', 15, 26),
       (30, 'Huile hydratante corps', 5, 27),
       (31, 'Eau micellaire', 6, 28);

CREATE TABLE IF NOT EXISTS `type`
(
    `id`   int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` set ('Non renseigné','Après-shampoing','Base','Beurre végétal','Cire','Colorant','Conservateur','Crème','Extrait de fruit','Extrait de plante','Gel douche','Huile essentielle','Huile végétale','Hydrolat','Lait','Lotion','Masque','Shampoing','Soin') DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

REPLACE INTO `type` (`id`, `name`)
VALUES (22, 'Huile essentielle'),
       (21, 'Gel douche'),
       (20, 'Extrait de plante'),
       (19, 'Extrait de fruit'),
       (18, 'Crème'),
       (17, 'Conservateur'),
       (16, 'Colorant'),
       (15, 'Cire'),
       (14, 'Beurre végétal'),
       (13, 'Base'),
       (12, 'Après-shampoing'),
       (23, 'Huile végétale'),
       (24, 'Hydrolat'),
       (25, 'Lait'),
       (26, 'Lotion'),
       (27, 'Masque'),
       (28, 'Shampoing'),
       (29, 'Soin'),
       (30, 'Non renseigné');

CREATE TABLE IF NOT EXISTS `user`
(
    `id`       int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `login`    varchar(30)  DEFAULT NULL,
    `password` varchar(100) DEFAULT NULL,
    `avatar`   varchar(30)  DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

REPLACE INTO `user` (`id`, `login`, `password`, `avatar`)
VALUES (1, 'Cecile', 'motdepassececile', 'dessin feebis.jpg'),
       (21, 'gargouille', 'gargouille', 'dessin dragon.jpg'),
       (14, 'Hello', 'Hello', 'Cerveau jpg.jpg'),
       (11, 'Cerveau', 'Cerveau', 'Cerveau jpg.jpg'),
       (12, 'Test4', 'Test4', 'Cerveau jpg.jpg'),
       (20, 'Hey', 'Hey', '20190525_174739.jpg');

CREATE TABLE IF NOT EXISTS `article`
(
    `id`      int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name`    varchar(50) DEFAULT NULL,
    `price`   float       DEFAULT NULL,
    `photo`   varchar(30) DEFAULT NULL,
    `id_body` int(11)     DEFAULT NULL,
    `id_type` int(10)     DEFAULT NULL,
    FOREIGN KEY (id_body) REFERENCES body(id),
    FOREIGN KEY (id_type) REFERENCES type(id)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

REPLACE INTO `article` (`id`, `name`, `price`, `photo`, `id_body`, `id_type`)
VALUES (1, 'Huille essentielle d\'Ail', 3.9, NULL, 31, 22),
       (2, 'Huile essentielle d\'Achillée Millefeuille', 29.7, NULL, 29, 22),
       (3, 'Huile essentielle d\'Aneth', 3.5, 'heaneth.jpg', 21, 22),
       (4, 'Huile essentielle d\'Angélique', 29.1, 'heangelique.jpg', 22, 22),
       (5, 'Huile essentielle de Basilic Grand Vert', 2.15, 'hebasilic.jpg', 29, 22),
       (6, 'Huile essentielle de Bay Saint Thomas', 15, NULL, 18, 22),
       (7, 'Huile essentielle de Benjoin', 5.9, NULL, 30, 22),
       (8, 'Huile essentielle de Bergamote', 7.5, NULL, 30, 22),
       (9, 'Huile essentielle de Bois de Hô', 2.9, NULL, 21, 22),
       (10, 'Huile essentielle de Bois de Rose', 11.9, NULL, 30, 22),
       (11, 'Huile essentielle de Bois de Santal', 4.69, NULL, 31, 22),
       (12, 'Huile essentielle de Rose', 65, 'herose.jpg', 26, 22),
       (13, 'Huile essentielle de Sauge Sclarée', 5.9, 'hesauge.jpg', 29, 22),
       (14, 'Base poudre de maquillage', 3.5, 'basepoudre.jpg', 30, 13),
       (15, 'Base lavante neutre', 4.5, 'baselavante.jpg', 20, 13),
       (16, 'Base parfum biologique', 3.5, NULL, 31, 13),
       (17, 'Base micellaire', 3.9, NULL, 30, 13),
       (18, 'Huile de soin cheveux', 7.9, 'basehuile.jpg', 23, 29),
       (19, 'Base masque capillaire', 2.2, NULL, 27, 27),
       (20, 'Base shampoing neutre', 4.9, 'baseshampoing.jpg', 28, 28),
       (21, 'Beurre végétal karité', 3.9, 'beurrekarite.jpg', 27, 14),
       (22, 'Cire d\'Abeille', 2.5, NULL, 15, 15),
       (23, 'Colorant capillaire végétal Henné du Rajasthan ', 4.7, NULL, 18, 16),
       (24, 'Conservateur Cosgard', 1.5, NULL, 31, 17),
       (25, 'Poudre de cranberry', 5.5, NULL, 30, 19),
       (26, 'The vert en poudre', 3.5, NULL, 30, 20),
       (27, 'Huile végétale d\'abricot', 1.25, 'hvabricot.jpg', 20, 23),
       (28, 'Hydrolat de Palmarosa', 4.5, 'hypalmarosa.jpg', 30, 24),
       (29, 'Hydrolat de Pamplemousse', 4.9, NULL, 30, 24);

CREATE TABLE IF NOT EXISTS `review`
(
    `id`         int(11)                        NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `id_user`    int(11)                        NOT NULL,
    `id_article` int(11)                        NOT NULL,
    `score`      enum ('0','1','2','3','4','5') NOT NULL,
    FOREIGN KEY (id_user) REFERENCES user(id),
    FOREIGN KEY (id_article) REFERENCES article(id)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

REPLACE INTO `review` (`id`, `id_user`, `id_article`, `score`)
VALUES (1, 1, 23, '5'),
       (2, 14, 8, '3'),
       (3, 1, 8, '4'),
       (4, 21, 15, '4'),
       (5, 11, 20, '5'),
       (6, 11, 20, '0'),
       (7, 1, 5, '5'),
       (8, 1, 15, '0'),
       (9, 1, 19, '0'),
       (10, 1, 17, '0'),
       (11, 1, 16, '0'),
       (12, 1, 20, '0'),
       (13, 1, 14, '4'),
       (14, 1, 21, '0'),
       (15, 1, 22, '0'),
       (16, 1, 23, '0'),
       (17, 1, 18, '0'),
       (18, 1, 24, '3'),
       (19, 1, 2, '4'),
       (20, 1, 3, '5'),
       (21, 1, 4, '4'),
       (22, 1, 6, '4'),
       (23, 1, 7, '2'),
       (24, 1, 9, '5'),
       (25, 1, 10, '5'),
       (26, 1, 11, '1'),
       (27, 1, 12, '5'),
       (28, 1, 13, '3'),
       (29, 1, 27, '4'),
       (30, 1, 1, '1'),
       (31, 1, 28, '4'),
       (32, 1, 29, '5'),
       (33, 14, 25, '4'),
       (34, 1, 26, '2'),
       (35, 1, 5, '3'),
       (36, 1, 3, '5');
