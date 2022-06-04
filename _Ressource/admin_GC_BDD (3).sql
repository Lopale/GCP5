-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : sam. 04 juin 2022 à 09:25
-- Version du serveur :  5.7.33-0ubuntu0.16.04.1
-- Version de PHP : 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `admin_GC_BDD`
--

-- --------------------------------------------------------

--
-- Structure de la table `choice`
--

CREATE TABLE `choice` (
  `id_choice` int(11) NOT NULL,
  `id_paragraphe_come` int(11) NOT NULL,
  `texte_choice` text NOT NULL,
  `id_paragraphe_out` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `choice`
--

INSERT INTO `choice` (`id_choice`, `id_paragraphe_come`, `texte_choice`, `id_paragraphe_out`) VALUES
(1, 1, 'Faire demi-tour', 2),
(2, 1, 'Avancer', 3),
(3, 4, 'Vous allez à droite', 5),
(4, 4, 'Vous allez au milieu', 6),
(5, 4, 'Vous allez à gauche', 7),
(6, 3, 'Avancer', 4),
(7, 3, 'Reculer', 2);

-- --------------------------------------------------------

--
-- Structure de la table `game_in_progress`
--

CREATE TABLE `game_in_progress` (
  `id_game_in_progress` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_save` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `game_in_progress`
--

INSERT INTO `game_in_progress` (`id_game_in_progress`, `id_user`, `id_save`) VALUES
(1, 1, 1),
(2, 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `paragraph`
--

CREATE TABLE `paragraph` (
  `id_paragraph` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `paragraph`
--

INSERT INTO `paragraph` (`id_paragraph`, `title`, `content`) VALUES
(1, 'Le début de l\'aventure', 'Après avoir rencontrer un homme mystérieux, une quête vous a été confié.\r\nVous êtes maintenant devant le château'),
(2, 'Bonne nuit', 'Vous vous dites que finalement cela n\'en vaut pas la peine et vous retourner à la taverne dépenser l\'avance que vous avez eu'),
(3, 'La porte du château', 'Vous vous décidez à entrer avec hésitation'),
(4, 'premier choix', 'Dans le couloir, trois couloirs sont devant vous.\r\n'),
(5, 'Premier combat', 'Vous tomber sur deux petits goblins'),
(6, 'le trésor', 'En allant tout droit vous tombé directement dans la salle du trésor, mais le maitre des lieux est la pour le surveiller'),
(7, 'une porte', 'Vous êtes devant une porte qui refuse de s\'ouvrir');

-- --------------------------------------------------------

--
-- Structure de la table `save`
--

CREATE TABLE `save` (
  `id_save` int(11) NOT NULL,
  `id_story` int(11) NOT NULL,
  `id_game_in_progress` int(11) NOT NULL,
  `id_paragraphe` int(11) NOT NULL,
  `date_save` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `save`
--

INSERT INTO `save` (`id_save`, `id_story`, `id_game_in_progress`, `id_paragraphe`, `date_save`) VALUES
(1, 1, 1, 1, '2022-05-03 10:09:22'),
(2, 1, 2, 4, '2022-05-03 13:09:22'),
(3, 1, 1, 3, '2022-05-03 07:33:22'),
(4, 1, 1, 5, '2022-05-03 15:09:22');

-- --------------------------------------------------------

--
-- Structure de la table `story`
--

CREATE TABLE `story` (
  `id_story` int(11) NOT NULL,
  `name` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `story`
--

INSERT INTO `story` (`id_story`, `name`) VALUES
(1, 'A l\'aventure compagnon');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `date_inscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mail` varchar(150) DEFAULT NULL,
  `login` varchar(30) NOT NULL,
  `pass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `date_inscription`, `mail`, `login`, `pass`) VALUES
(1, '2022-05-14 09:54:12', 'grillon.d@gmail.com', 'AAA', 'd9f9566e08176f1272db2f502522662442108b7954907c255251cb8a5b09eced'),
(2, '2022-05-14 09:31:17', 'mail@mail.fr', 'lelogin', 'lepass'),
(6, '2022-05-14 09:42:55', 'ii@ff.dr', 'Crypté', '9834876dcfb05cb167a5c24953eba58c4ac89b1adf57f28f2f9d09af107ee8f0'),
(7, '2022-05-14 09:48:52', 'zzz@zzz.fr', 'Gnia', '9834876dcfb05cb167a5c24953eba58c4ac89b1adf57f28f2f9d09af107ee8f0');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `choice`
--
ALTER TABLE `choice`
  ADD PRIMARY KEY (`id_choice`),
  ADD KEY `id_paragraphe_come` (`id_paragraphe_come`);

--
-- Index pour la table `game_in_progress`
--
ALTER TABLE `game_in_progress`
  ADD PRIMARY KEY (`id_game_in_progress`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_save` (`id_save`);

--
-- Index pour la table `paragraph`
--
ALTER TABLE `paragraph`
  ADD PRIMARY KEY (`id_paragraph`);

--
-- Index pour la table `save`
--
ALTER TABLE `save`
  ADD PRIMARY KEY (`id_save`),
  ADD KEY `id_game_in_progress` (`id_game_in_progress`);

--
-- Index pour la table `story`
--
ALTER TABLE `story`
  ADD PRIMARY KEY (`id_story`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `choice`
--
ALTER TABLE `choice`
  MODIFY `id_choice` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `game_in_progress`
--
ALTER TABLE `game_in_progress`
  MODIFY `id_game_in_progress` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `paragraph`
--
ALTER TABLE `paragraph`
  MODIFY `id_paragraph` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `save`
--
ALTER TABLE `save`
  MODIFY `id_save` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `story`
--
ALTER TABLE `story`
  MODIFY `id_story` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `choice`
--
ALTER TABLE `choice`
  ADD CONSTRAINT `id_paragraphe` FOREIGN KEY (`id_paragraphe_come`) REFERENCES `paragraph` (`id_paragraph`) ON DELETE CASCADE;

--
-- Contraintes pour la table `game_in_progress`
--
ALTER TABLE `game_in_progress`
  ADD CONSTRAINT `id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Contraintes pour la table `save`
--
ALTER TABLE `save`
  ADD CONSTRAINT `id_game_in_progress` FOREIGN KEY (`id_game_in_progress`) REFERENCES `game_in_progress` (`id_game_in_progress`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
