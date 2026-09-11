-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 23 mai 2025 à 08:37
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
-- Base de données : `blockmirrordata`
--

-- --------------------------------------------------------

--
-- Structure de la table `niveau`
--

CREATE TABLE `niveau` (
  `idJoueur` int(11) NOT NULL,
  `numNiveau` int(11) NOT NULL,
  `duréeDeJeu` int(11) NOT NULL,
  `bestTime` int(11) NOT NULL,
  `nbrMorts` int(11) NOT NULL,
  `nbrCoin` int(11) NOT NULL,
  `niveauFini` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `niveau`
--

INSERT INTO `niveau` (`idJoueur`, `numNiveau`, `duréeDeJeu`, `bestTime`, `nbrMorts`, `nbrCoin`, `niveauFini`) VALUES
(1, 1, 453, 15, 3, 1, 1),
(1, 2, 928, 27, 8, 0, 1),
(1, 3, 502, 0, 3, 0, 0),
(2, 1, 354, 433, 5, 1, 1),
(2, 2, 742, 14, 12, 0, 1),
(2, 3, 1502, 334, 8, 0, 1),
(2, 4, 0, 0, 0, 0, 0),
(2, 5, 421, 0, 3, 1, 0),
(2, 6, 7, 0, 1, 0, 0),
(2, 9, 736, 361, 6, 0, 1),
(2, 10, 9, 0, 1, 0, 0),
(4, 2, 294, 333, 5, 0, 1),
(4, 3, 4771, 0, 7, 0, 0),
(4, 4, 2162, 0, 8, 0, 0),
(4, 5, 3684, 0, 6, 0, 0),
(4, 6, 15, 0, 1, 0, 0),
(4, 8, 7, 0, 1, 0, 0),
(4, 10, 686, 0, 1, 0, 0),
(5, 2, 73, 10, 1, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `user_type` enum('normal','admin') DEFAULT 'normal',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `first_name`, `last_name`, `user_type`, `created_at`) VALUES
(1, 'badis@gmail.com', 'motdepasse', 'Badis', 'Dahi', 'normal', '2025-05-13 07:04:14'),
(2, 'maxlamax@gmail.com', 'maxlamax', 'Max', 'LaMax', 'normal', '2025-05-13 07:08:51'),
(3, 'tom@example.com', 'cacacaca', 'TomDutilleul', '', 'normal', '2025-05-13 07:48:18'),
(4, 'jacobbut@gmail.com', 'jacobbut', 'Jacob', 'But', 'normal', '2025-05-19 11:52:59'),
(5, 'caca@feur.com', 'cacacaca', 'JEMANGEMONCACA', '', 'normal', '2025-05-21 22:14:15');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `niveau`
--
ALTER TABLE `niveau`
  ADD PRIMARY KEY (`idJoueur`,`numNiveau`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `niveau`
--
ALTER TABLE `niveau`
  ADD CONSTRAINT `fk_niveau_users` FOREIGN KEY (`idJoueur`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
