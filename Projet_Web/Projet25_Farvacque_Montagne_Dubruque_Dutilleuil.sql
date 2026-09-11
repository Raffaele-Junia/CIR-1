-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : dim. 27 avr. 2025 à 10:47
-- Version du serveur : 5.7.24
-- Version de PHP : 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `Projet25_Farvacque_Montagne_Dubruque_Dutilleul`
--

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `genre` varchar(10) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `objet` varchar(100) DEFAULT NULL,
  `precision_demande` varchar(255) DEFAULT NULL,
  `message` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `contact`
--

INSERT INTO `contact` (`id`, `genre`, `nom`, `prenom`, `mail`, `telephone`, `objet`, `precision_demande`, `message`) VALUES
(1, 'Homme', 'Dupont', 'Jean', 'jean.dupont@example.com', '+33 6 12 34 56 78', 'Question sur le logement', 'Demande de renseignement sur la location', 'Bonjour, j\'ai des questions concernant les logements disponibles.'),
(2, 'Femme', 'Martin', 'Claire', 'claire.martin@example.com', '+33 6 23 45 67 89', 'Prendre rendez-vous avec municipalité', 'Demande de rendez-vous pour discuter des projets urbains', 'Je souhaite prendre un rendez-vous pour discuter des projets de rénovation.'),
(3, 'Homme', 'Bernard', 'Lucas', 'lucas.bernard@example.com', '+33 6 34 56 78 90', 'Question sur la propreté de la ville', 'Propreté des rues autour du centre-ville', 'Les rues autour du centre-ville sont sales, pourriez-vous les nettoyer ?'),
(4, 'Femme', 'Petit', 'Sophie', 'sophie.petit@example.com', '+33 6 45 67 89 01', 'Autres demandes (à préciser)', 'Demande d\'information sur les événements culturels', 'J\'aimerais connaître les événements culturels prévus pour le mois prochain.'),
(5, 'Homme', 'Robert', 'Antoine', 'antoine.robert@example.com', '+33 6 56 78 90 12', 'Demande d\'emploi', 'Recherche d\'emploi dans le secteur de l\'informatique', 'Je suis à la recherche d\'une opportunité dans le domaine de l\'informatique.'),
(6, 'Femme', 'Richard', 'Emma', 'emma.richard@example.com', '+33 6 67 89 01 23', 'Question sur le logement', 'Logements disponibles pour étudiants', 'Je cherche un logement pour un étudiant à proximité du centre-ville.'),
(7, 'Homme', 'Durand', 'Mathieu', 'mathieu.durand@example.com', '+33 6 78 90 12 34', 'Prendre rendez-vous avec municipalité', 'Rendez-vous pour discuter des projets écologiques', 'Je souhaite rencontrer quelqu\'un pour discuter de projets écologiques.'),
(8, 'Femme', 'Lefevre', 'Camille', 'camille.lefevre@example.com', '+33 6 89 01 23 45', 'Autres demandes (à préciser)', 'Questions sur les transports publics', 'J\'aimerais plus d\'informations sur les horaires des bus.'),
(9, 'Homme', 'Lemoine', 'Paul', 'paul.lemoine@example.com', '+33 6 90 12 34 56', 'Question sur la propreté de la ville', 'Propreté des parcs publics', 'Les parcs publics sont parfois mal entretenus, est-il possible d\'intervenir ?'),
(10, 'Femme', 'Fournier', 'Julie', 'julie.fournier@example.com', '+33 6 01 23 45 67', 'Prendre rendez-vous avec municipalité', 'Demande de rendez-vous pour une discussion sur les activités familiales', 'Je souhaite un rendez-vous pour discuter des activités pour les familles.');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `genre` varchar(10) DEFAULT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `mail` varchar(255) NOT NULL,
  `identifiant` varchar(100) NOT NULL,
  `mdp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `genre`, `nom`, `prenom`, `date_naissance`, `mail`, `identifiant`, `mdp`) VALUES
(1, 'Homme', 'Dupont', 'Jean', '1990-05-15', 'jean.dupont@example.com', 'jeanD', 'Jean2025!'),
(2, 'Femme', 'Martin', 'Claire', '1995-08-22', 'claire.martin@example.com', 'claireM', 'Claire1234!'),
(3, 'Homme', 'Bernard', 'Lucas', '1988-12-03', 'lucas.bernard@example.com', 'lucasB', 'Lucas1988*'),
(4, 'Femme', 'Petit', 'Sophie', '2000-01-09', 'sophie.petit@example.com', 'sophieP', 'Sophie2025@'),
(5, 'Homme', 'Robert', 'Antoine', '1992-07-19', 'antoine.robert@example.com', 'antoineR', 'Antoine123@'),
(6, 'Femme', 'Richard', 'Emma', '1999-03-25', 'emma.richard@example.com', 'emmaR', 'Emma2025$'),
(7, 'Homme', 'Durand', 'Mathieu', '1985-11-30', 'mathieu.durand@example.com', 'mathieuD', 'Mathieu88#'),
(8, 'Femme', 'Lefevre', 'Camille', '1993-06-17', 'camille.lefevre@example.com', 'camilleL', 'Camille#2025'),
(9, 'Homme', 'Lemoine', 'Paul', '1986-10-14', 'paul.lemoine@example.com', 'paulL', 'Paul2025!'),
(10, 'Femme', 'Fournier', 'Julie', '1997-04-11', 'julie.fournier@example.com', 'julieF', 'Julie1234!');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mail` (`mail`),
  ADD UNIQUE KEY `identifiant` (`identifiant`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
