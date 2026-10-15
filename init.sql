-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 16 juin 2026 à 11:53
-- Version du serveur : 11.4.2-MariaDB
-- Version de PHP : 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `site_marchand_e`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresse`
--

CREATE TABLE `adresse` (
  `IDADRESSE` int(11) NOT NULL,
  `RUE` varchar(32) DEFAULT NULL,
  `CP` bigint(20) DEFAULT NULL,
  `VILLE` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `IDCATEGORIE` int(11) NOT NULL,
  `LIBELLECATEGORIE` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`IDCATEGORIE`, `LIBELLECATEGORIE`) VALUES
(1, 'Vêtements'),
(2, 'Accessoires'),
(3, 'Produits');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `IDCLIENT` int(11) NOT NULL,
  `NOMCLI` varchar(32) NOT NULL,
  `PRENOM` varchar(32) NOT NULL,
  `PSEUDO` varchar(32) NOT NULL,
  `MDP` varchar(32) NOT NULL,
  `MAIL` varchar(50) DEFAULT NULL,
  `TEL` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`IDCLIENT`, `NOMCLI`, `PRENOM`, `PSEUDO`, `MDP`, `MAIL`, `TEL`) VALUES
(2, 'DURAND', 'Emy', 'aemulus_', '1234', 'philippe-e@saint-louis29.net', '0611223344'),
(3, 'sfg', 'sfgs', 'oui', '1234', 'philippe-e@saint-louis29.net', '0611223344');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `IDCOMMANDE` int(11) NOT NULL,
  `IDCLIENT` int(11) NOT NULL,
  `IDSTATUT` char(3) NOT NULL,
  `DATECOMMANDE` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`IDCOMMANDE`, `IDCLIENT`, `IDSTATUT`, `DATECOMMANDE`) VALUES
(1, 2, '2', '2026-05-04'),
(2, 2, '1', '2026-05-04');

-- --------------------------------------------------------

--
-- Structure de la table `contenir`
--

CREATE TABLE `contenir` (
  `IDCOMMANDE` int(11) NOT NULL,
  `IDPRODUIT` int(11) NOT NULL,
  `QUANTITE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `habiter`
--

CREATE TABLE `habiter` (
  `IDCLIENT` int(11) NOT NULL,
  `IDADRESSE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `IDPRODUIT` int(11) NOT NULL,
  `IDCATEGORIE` int(11) NOT NULL,
  `NOMPROD` varchar(32) DEFAULT NULL,
  `PRIX` decimal(8,2) NOT NULL,
  `STOCK` smallint(6) DEFAULT NULL,
  `DESCRIPTION` varchar(100) DEFAULT ' ',
  `NOMIMAGE` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`IDPRODUIT`, `IDCATEGORIE`, `NOMPROD`, `PRIX`, `STOCK`, `DESCRIPTION`, `NOMIMAGE`) VALUES
(1, 1, 'Blazer', 15.00, 5, 'Description', 'blazer.jpg'),
(2, 1, 'Jupe', 15.99, 3, 'Description', 'jupe.jpg'),
(3, 1, 'Robe', 20.00, 15, 'Description', 'robe.jpg'),
(4, 2, 'Ceinture', 5.99, 3, 'Description', 'ceinture.jpg'),
(5, 2, 'Lunettes de soleils', 3.99, 20, 'Description', 'sunglasses.jpg'),
(6, 2, 'Faux ongles', 1.70, 25, 'Description', 'fakesnails.jpg'),
(7, 3, 'Eyeliner', 4.99, 15, ' ', 'liner.png');

-- --------------------------------------------------------

--
-- Structure de la table `statut`
--

CREATE TABLE `statut` (
  `IDSTATUT` char(3) NOT NULL,
  `LIBELLESTATUT` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `statut`
--

INSERT INTO `statut` (`IDSTATUT`, `LIBELLESTATUT`) VALUES
('1', 'Commande en cours'),
('2', 'Livraison en cours'),
('3', 'Produits livrés');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `codeUser` int(11) NOT NULL,
  `login` varchar(15) NOT NULL,
  `mdp` varchar(32) DEFAULT NULL,
  `mdp_hash` varchar(64) NOT NULL,
  `nom` varchar(32) NOT NULL,
  `prenom` varchar(32) NOT NULL,
  `role` varchar(3) NOT NULL DEFAULT 'usr'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`codeUser`, `login`, `mdp`, `mdp_hash`, `nom`, `prenom`, `role`) VALUES
(1, 'leroy-a', '123456', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'Leroy', 'Achille', 'adm'),
(2, 'denis-e', '654321', '481f6cc0511143ccdd7e2d1b1b94faf0a700a8b49cd13922a70b5ae28acaa8c5', 'Denis', 'Emma', 'usr'),
(3, 'olsen-g', 'azerty', 'f2d81a260dea8a100dd517984e53c56a7523d96942a834b9cdc249bd4e8c7aa9', 'Olsen', 'Gabriel', 'usr'),
(7, 'mymy', NULL, '5858b0a718088012ce11c1cd053067033560818f84935dddd7f88cd6db4a7533', 'Dudu', 'Mymy', 'usr');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adresse`
--
ALTER TABLE `adresse`
  ADD PRIMARY KEY (`IDADRESSE`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`IDCATEGORIE`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`PSEUDO`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`IDCOMMANDE`),
  ADD KEY `FK_ETRE` (`IDSTATUT`),
  ADD KEY `FK_PASSER` (`IDCLIENT`);

--
-- Index pour la table `contenir`
--
ALTER TABLE `contenir`
  ADD PRIMARY KEY (`IDCOMMANDE`,`IDPRODUIT`),
  ADD KEY `FK_CONTENIR` (`IDPRODUIT`);

--
-- Index pour la table `habiter`
--
ALTER TABLE `habiter`
  ADD PRIMARY KEY (`IDCLIENT`,`IDADRESSE`),
  ADD KEY `FK_HABITER` (`IDADRESSE`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`IDPRODUIT`),
  ADD KEY `FK_APPARTIENT` (`IDCATEGORIE`);

--
-- Index pour la table `statut`
--
ALTER TABLE `statut`
  ADD PRIMARY KEY (`IDSTATUT`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`codeUser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adresse`
--
ALTER TABLE `adresse`
  MODIFY `IDADRESSE` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `IDCATEGORIE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `IDCLIENT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `IDCOMMANDE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `IDPRODUIT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `codeUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_ETRE` FOREIGN KEY (`IDSTATUT`) REFERENCES `statut` (`IDSTATUT`),
  ADD CONSTRAINT `FK_PASSER` FOREIGN KEY (`IDCLIENT`) REFERENCES `client` (`IDCLIENT`);

--
-- Contraintes pour la table `contenir`
--
ALTER TABLE `contenir`
  ADD CONSTRAINT `FK_CONTENIR` FOREIGN KEY (`IDPRODUIT`) REFERENCES `produit` (`IDPRODUIT`),
  ADD CONSTRAINT `FK_CONTENIR2` FOREIGN KEY (`IDCOMMANDE`) REFERENCES `commande` (`IDCOMMANDE`);

--
-- Contraintes pour la table `habiter`
--
ALTER TABLE `habiter`
  ADD CONSTRAINT `FK_HABITER` FOREIGN KEY (`IDADRESSE`) REFERENCES `adresse` (`IDADRESSE`),
  ADD CONSTRAINT `FK_HABITER2` FOREIGN KEY (`IDCLIENT`) REFERENCES `client` (`IDCLIENT`);

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `FK_APPARTIENT` FOREIGN KEY (`IDCATEGORIE`) REFERENCES `categories` (`IDCATEGORIE`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
