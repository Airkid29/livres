-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 26 mai 2025 à 03:44
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bibliotheque_en_ligne`
--

-- --------------------------------------------------------

--
-- Structure de la table `emprunts`
--

CREATE TABLE `emprunts` (
  `id_emprunt` int(11) NOT NULL,
  `id_livre` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `date_emprunt` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_retour_prevue` date DEFAULT NULL,
  `date_retour_effective` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `emprunts`
--

INSERT INTO `emprunts` (`id_emprunt`, `id_livre`, `id_utilisateur`, `date_emprunt`, `date_retour_prevue`, `date_retour_effective`) VALUES
(1, 1, 1, '2025-05-25 23:32:46', '2025-06-26', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `livres`
--

CREATE TABLE `livres` (
  `id_livre` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `auteur` varchar(255) NOT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `date_publication` date DEFAULT NULL,
  `nombre_exemplaires` int(11) NOT NULL DEFAULT 1,
  `nombre_disponibles` int(11) NOT NULL DEFAULT 1,
  `couverture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `livres`
--

INSERT INTO `livres` (`id_livre`, `titre`, `auteur`, `isbn`, `date_publication`, `nombre_exemplaires`, `nombre_disponibles`, `couverture`) VALUES
(1, 'Le Seigneur des Anneaux', 'J.R.R. Tolkien', '978-0547928227', '1954-07-29', 5, 4, 'couverture_seigneur_anneaux.jpg'),
(2, 'Harry Potter à l\'école des sorciers', 'J.K. Rowling', '978-0747532699', '1997-06-26', 10, 8, 'couverture_harry_potter1.jpg'),
(3, 'Orgueil et Préjugés', 'Jane Austen', '978-0141439518', '1813-01-28', 3, 3, 'couverture_orgueil_prejuges.jpg'),
(4, '1984', 'George Orwell', '978-0451524935', '1949-06-08', 7, 6, 'couverture_1984.jpg'),
(5, 'Le Petit Prince', 'Antoine de Saint-Exupéry', '978-2070612746', '1943-04-06', 4, 4, 'couverture_petit_prince.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('eleve','enseignant','admin') NOT NULL DEFAULT 'eleve'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`) VALUES
(1, 'BAWA', 'Rachid', 'rachidbawa80@gmail.com', '$2y$12$9Jf1gsv4CHVHXNV926/4qOWKK.Dp0anMzeeGNHdanmvSTuWrMA2vm', 'eleve'),
(2, 'SEBOu', 'Farid', 'seb@gmail.com', '12345678', 'admin');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `emprunts`
--
ALTER TABLE `emprunts`
  ADD PRIMARY KEY (`id_emprunt`),
  ADD KEY `id_livre` (`id_livre`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `livres`
--
ALTER TABLE `livres`
  ADD PRIMARY KEY (`id_livre`),
  ADD UNIQUE KEY `isbn` (`isbn`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `emprunts`
--
ALTER TABLE `emprunts`
  MODIFY `id_emprunt` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `livres`
--
ALTER TABLE `livres`
  MODIFY `id_livre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `emprunts`
--
ALTER TABLE `emprunts`
  ADD CONSTRAINT `emprunts_ibfk_1` FOREIGN KEY (`id_livre`) REFERENCES `livres` (`id_livre`),
  ADD CONSTRAINT `emprunts_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
