-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 17 déc. 2025 à 15:53
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `zest`
--

-- --------------------------------------------------------

--
-- Structure de la table `adhesion`
--

DROP TABLE IF EXISTS `adhesion`;
CREATE TABLE IF NOT EXISTS `adhesion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date_adhesion` datetime NOT NULL,
  `attentes_texte` longtext,
  `competences_texte` longtext,
  `paiement` tinyint(1) NOT NULL,
  `user_id` int DEFAULT NULL,
  `groupe_id` int DEFAULT NULL,
  `montant_adhesion_id` int DEFAULT NULL,
  `saison_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C50CA65AA76ED395` (`user_id`),
  KEY `IDX_C50CA65A7A45358C` (`groupe_id`),
  KEY `IDX_C50CA65ABBAAE0A4` (`montant_adhesion_id`),
  KEY `IDX_C50CA65AF965414C` (`saison_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `adhesion`
--

INSERT INTO `adhesion` (`id`, `date_adhesion`, `attentes_texte`, `competences_texte`, `paiement`, `user_id`, `groupe_id`, `montant_adhesion_id`, `saison_id`) VALUES
(1, '2025-12-17 15:52:08', NULL, NULL, 0, 7, 7, 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `adhesion_dispo`
--

DROP TABLE IF EXISTS `adhesion_dispo`;
CREATE TABLE IF NOT EXISTS `adhesion_dispo` (
  `adhesion_id` int NOT NULL,
  `dispo_id` int NOT NULL,
  PRIMARY KEY (`adhesion_id`,`dispo_id`),
  KEY `IDX_4DC30B07F68139D7` (`adhesion_id`),
  KEY `IDX_4DC30B07A18C1CC9` (`dispo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `adhesion_motivation`
--

DROP TABLE IF EXISTS `adhesion_motivation`;
CREATE TABLE IF NOT EXISTS `adhesion_motivation` (
  `adhesion_id` int NOT NULL,
  `motivation_id` int NOT NULL,
  PRIMARY KEY (`adhesion_id`,`motivation_id`),
  KEY `IDX_690EC6E4F68139D7` (`adhesion_id`),
  KEY `IDX_690EC6E48EDBCD4E` (`motivation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `adhesion_pole`
--

DROP TABLE IF EXISTS `adhesion_pole`;
CREATE TABLE IF NOT EXISTS `adhesion_pole` (
  `adhesion_id` int NOT NULL,
  `pole_id` int NOT NULL,
  PRIMARY KEY (`adhesion_id`,`pole_id`),
  KEY `IDX_5717926F68139D7` (`adhesion_id`),
  KEY `IDX_5717926419C3385` (`pole_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `dispo`
--

DROP TABLE IF EXISTS `dispo`;
CREATE TABLE IF NOT EXISTS `dispo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dispo`
--

INSERT INTO `dispo` (`id`, `libelle`) VALUES
(1, 'Très très disponible > 30h'),
(2, 'Très disponible 10–30h'),
(3, 'Disponible 10h'),
(4, 'Peu disponible < 5h'),
(5, 'Impossible de donner 10h cette saison'),
(6, 'Disponible ponctuellement'),
(7, 'Disponible à un seul moment dans la saison '),
(8, 'Disponible régulièrement (tous les mois)');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20251126155602', '2025-11-26 15:56:19', 401),
('DoctrineMigrations\\Version20251128102621', '2025-11-28 10:27:13', 208),
('DoctrineMigrations\\Version20251128110953', '2025-11-28 11:10:02', 80),
('DoctrineMigrations\\Version20251128123244', '2025-11-28 12:32:53', 327),
('DoctrineMigrations\\Version20251128125256', '2025-11-28 12:53:02', 170),
('DoctrineMigrations\\Version20251128134040', '2025-11-28 13:40:45', 74),
('DoctrineMigrations\\Version20251128135031', '2025-11-28 13:50:38', 113),
('DoctrineMigrations\\Version20251128141536', '2025-11-28 14:15:42', 76),
('DoctrineMigrations\\Version20251128141919', '2025-11-28 14:19:26', 259),
('DoctrineMigrations\\Version20251128142952', '2025-11-28 14:30:00', 105),
('DoctrineMigrations\\Version20251202113012', '2025-12-02 11:30:24', 257),
('DoctrineMigrations\\Version20251202140317', '2025-12-02 14:03:26', 80),
('DoctrineMigrations\\Version20251203102506', '2025-12-03 10:25:23', 253),
('DoctrineMigrations\\Version20251203152956', '2025-12-03 15:30:04', 183),
('DoctrineMigrations\\Version20251203155258', '2025-12-03 15:53:10', 107),
('DoctrineMigrations\\Version20251205155733', '2025-12-05 15:57:43', 176),
('DoctrineMigrations\\Version20251210105031', '2025-12-10 11:21:54', 1168),
('DoctrineMigrations\\Version20251210131927', '2025-12-10 13:19:36', 98),
('DoctrineMigrations\\Version20251210150716', '2025-12-10 15:07:25', 155),
('DoctrineMigrations\\Version20251211104329', '2025-12-11 10:43:47', 288),
('DoctrineMigrations\\Version20251212154451', '2025-12-12 15:45:20', 120),
('DoctrineMigrations\\Version20251215113354', '2025-12-15 11:34:02', 188),
('DoctrineMigrations\\Version20251215153248', '2025-12-15 15:32:55', 93),
('DoctrineMigrations\\Version20251215154025', '2025-12-15 15:40:30', 44),
('DoctrineMigrations\\Version20251216102448', '2025-12-16 10:25:06', 184),
('DoctrineMigrations\\Version20251216102847', '2025-12-16 10:28:54', 77),
('DoctrineMigrations\\Version20251216103532', '2025-12-16 10:35:38', 89),
('DoctrineMigrations\\Version20251216112512', '2025-12-16 11:25:26', 204),
('DoctrineMigrations\\Version20251216143813', '2025-12-16 14:42:48', 196),
('DoctrineMigrations\\Version20251217114322', '2025-12-17 11:50:08', 200),
('DoctrineMigrations\\Version20251217115042', '2025-12-17 11:50:46', 44);

-- --------------------------------------------------------

--
-- Structure de la table `groupe`
--

DROP TABLE IF EXISTS `groupe`;
CREATE TABLE IF NOT EXISTS `groupe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) NOT NULL,
  `is_referent` tinyint(1) NOT NULL,
  `is_open` tinyint(1) NOT NULL,
  `date_creation` datetime NOT NULL,
  `adresse_distrib` varchar(255) DEFAULT NULL,
  `ville` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `groupe`
--

INSERT INTO `groupe` (`id`, `nom`, `is_referent`, `is_open`, `date_creation`, `adresse_distrib`, `ville`) VALUES
(1, 'yoyo', 0, 1, '0000-00-00 00:00:00', NULL, 'mont'),
(2, 'rrrrr', 0, 0, '0000-00-00 00:00:00', NULL, 'tata'),
(3, 'mmm', 0, 0, '2025-12-12 10:33:50', NULL, 'montreuil'),
(7, 'BEFANA', 0, 0, '2010-12-16 13:05:00', 'plateau', 'Bagnolet'),
(8, '37 et +', 0, 1, '2020-02-16 13:05:00', '37 rue de Vincennes', 'Montreuil'),
(9, 'ALEP', 0, 1, '2011-01-20 09:30:00', '7 rue Alexis Lepère', 'Montreuil'),
(10, 'hh', 0, 0, '2025-12-17 10:15:09', NULL, 'montreuil');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `montant_adhesion`
--

DROP TABLE IF EXISTS `montant_adhesion`;
CREATE TABLE IF NOT EXISTS `montant_adhesion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `montant` int NOT NULL,
  `libelle` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `montant_adhesion`
--

INSERT INTO `montant_adhesion` (`id`, `montant`, `libelle`) VALUES
(1, 10, 'adhésion 10'),
(2, 5, 'adhésion solidaire 5'),
(3, 15, 'adhésion de soutien 15 et +');

-- --------------------------------------------------------

--
-- Structure de la table `motivation`
--

DROP TABLE IF EXISTS `motivation`;
CREATE TABLE IF NOT EXISTS `motivation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `motivation`
--

INSERT INTO `motivation` (`id`, `libelle`) VALUES
(1, 'Accéder à des produits de qualité'),
(2, 'Soutenir une agriculture écoresponsable'),
(3, 'Lutter contre la grande distribution'),
(4, 'Créer du lien social');

-- --------------------------------------------------------

--
-- Structure de la table `photos`
--

DROP TABLE IF EXISTS `photos`;
CREATE TABLE IF NOT EXISTS `photos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `ressource_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_876E0D9FC6CD52A` (`ressource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pole`
--

DROP TABLE IF EXISTS `pole`;
CREATE TABLE IF NOT EXISTS `pole` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `descriptif` longtext NOT NULL,
  `descriptif_pdf` varchar(255) DEFAULT NULL,
  `volume_horaire` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `pole`
--

INSERT INTO `pole` (`id`, `nom`, `descriptif`, `descriptif_pdf`, `volume_horaire`) VALUES
(1, 'CA', '', '', 1000),
(2, 'pôle gestion administrative', '', '', 300),
(3, 'pôle communication', '', '', 200),
(4, 'pôle orga et médiation', '', '', 200),
(5, 'pôle adhésion', '', '', 100),
(6, 'pôle commandes', '', '', 60),
(7, 'pôle produit - relations producteurs', '', '', 40),
(8, 'pôle logistique -transport livraison distribution', '', '', 100),
(9, 'pôle relations extérieures - projets', '', '', 60);

-- --------------------------------------------------------

--
-- Structure de la table `producteurice`
--

DROP TABLE IF EXISTS `producteurice`;
CREATE TABLE IF NOT EXISTS `producteurice` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `produits` varchar(255) NOT NULL,
  `is_coop` tinyint(1) NOT NULL,
  `site` varchar(255) NOT NULL,
  `lien_produits` varchar(255) DEFAULT NULL,
  `logo` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `producteurice_produit`
--

DROP TABLE IF EXISTS `producteurice_produit`;
CREATE TABLE IF NOT EXISTS `producteurice_produit` (
  `producteurice_id` int NOT NULL,
  `produit_id` int NOT NULL,
  PRIMARY KEY (`producteurice_id`,`produit_id`),
  KEY `IDX_FCA015B5EE5BE958` (`producteurice_id`),
  KEY `IDX_FCA015B5F347EFB` (`produit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `description` longtext NOT NULL,
  `photo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `recette`
--

DROP TABLE IF EXISTS `recette`;
CREATE TABLE IF NOT EXISTS `recette` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date_publication` datetime NOT NULL,
  `titre` varchar(255) NOT NULL,
  `nombre_mangeurs` int NOT NULL,
  `ingredients` varchar(500) NOT NULL,
  `description` longtext NOT NULL,
  `photo` varchar(255) NOT NULL,
  `auteurice_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_49BB639055D7EF5A` (`auteurice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `recette_produit`
--

DROP TABLE IF EXISTS `recette_produit`;
CREATE TABLE IF NOT EXISTS `recette_produit` (
  `recette_id` int NOT NULL,
  `produit_id` int NOT NULL,
  PRIMARY KEY (`recette_id`,`produit_id`),
  KEY `IDX_EDDD365D89312FE9` (`recette_id`),
  KEY `IDX_EDDD365DF347EFB` (`produit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reset_password_request`
--

DROP TABLE IF EXISTS `reset_password_request`;
CREATE TABLE IF NOT EXISTS `reset_password_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `selector` varchar(20) NOT NULL,
  `hashed_token` varchar(100) NOT NULL,
  `requested_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7CE748AA76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ressource`
--

DROP TABLE IF EXISTS `ressource`;
CREATE TABLE IF NOT EXISTS `ressource` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date_publication` datetime NOT NULL,
  `titre` varchar(150) NOT NULL,
  `sous_titre` varchar(200) NOT NULL,
  `ressource_texte` longtext NOT NULL,
  `categorie_id` int NOT NULL,
  `pole_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `photo_principale_id` int DEFAULT NULL,
  `lien_externe1` varchar(500) DEFAULT NULL,
  `lien_externe2` varchar(500) DEFAULT NULL,
  `lien_externe3` varchar(500) DEFAULT NULL,
  `statut` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_939F454451C718BE` (`photo_principale_id`),
  KEY `IDX_939F4544BCF5E72D` (`categorie_id`),
  KEY `IDX_939F4544419C3385` (`pole_id`),
  KEY `IDX_939F4544A76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `saison`
--

DROP TABLE IF EXISTS `saison`;
CREATE TABLE IF NOT EXISTS `saison` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(9) NOT NULL,
  `date_creation` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C0D0D5866C6E55B5` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `saison`
--

INSERT INTO `saison` (`id`, `nom`, `date_creation`) VALUES
(1, '2024/2025', '2024-08-12 14:28:00'),
(2, '2025/2026', '2025-09-12 14:29:00');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) NOT NULL,
  `prenom` varchar(45) NOT NULL,
  `email` varchar(180) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telephone` varchar(10) NOT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `code_postal` varchar(5) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `date_de_naissance` date DEFAULT NULL,
  `composition_foyer` int UNSIGNED DEFAULT NULL,
  `nombre_enfants` int UNSIGNED DEFAULT NULL,
  `roles` json NOT NULL,
  `groupe_id` int NOT NULL,
  `is_referent` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`),
  KEY `IDX_8D93D6497A45358C` (`groupe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `code_postal`, `ville`, `date_de_naissance`, `composition_foyer`, `nombre_enfants`, `roles`, `groupe_id`, `is_referent`) VALUES
(1, 'ehl', 'muri', 'murielehlinger@gmail.com', '$2y$13$diIjwGFCdgVEzYNI5TxA9.33Do97Xhe1MRW8Oehr/p1ZjL6CpSmd.', '0686420135', NULL, '93100', 'montreuil', NULL, NULL, NULL, '[]', 1, 0),
(2, 'hhh', 'hhhref', 'muriel@gmail.com', '$2y$13$25OSDzF/FTvy2uqHGpMzeeDcWzcQk8lJx.XBCzEfC8x5/EBf5wf/W', '0686420135', NULL, '93100', 'montreuil', NULL, NULL, NULL, '[\"ROLE_USER\", \"ROLE_ADMIN\"]', 2, 0),
(3, 'chromosores', 'chromosores', 'ehlinger@gmail.com', '$2y$13$FWt58qx.NhKEMfeHvCH3I.OHi9I9.H/nhlcIMc3tBquFk0mzXYclK', '0686420135', NULL, '93100', 'montreuil', NULL, NULL, NULL, '[\"ROLE_ADMIN\"]', 2, 0),
(7, 'hh', 'hh', 'murcccccciel.ehlinger@lepoles.org', '$2y$13$v7hfaOOt8xpzzOS571jcv.eFQnUhvhGRyW63Yf0aM7aJnVL8uTQ/.', '0686420135', NULL, '12457', 'montreuil', NULL, NULL, NULL, '[]', 7, 0);

-- --------------------------------------------------------

--
-- Structure de la table `user_pole`
--

DROP TABLE IF EXISTS `user_pole`;
CREATE TABLE IF NOT EXISTS `user_pole` (
  `user_id` int NOT NULL,
  `pole_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`pole_id`),
  KEY `IDX_87E10E28A76ED395` (`user_id`),
  KEY `IDX_87E10E28419C3385` (`pole_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `adhesion`
--
ALTER TABLE `adhesion`
  ADD CONSTRAINT `FK_C50CA65A7A45358C` FOREIGN KEY (`groupe_id`) REFERENCES `groupe` (`id`),
  ADD CONSTRAINT `FK_C50CA65AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_C50CA65ABBAAE0A4` FOREIGN KEY (`montant_adhesion_id`) REFERENCES `montant_adhesion` (`id`),
  ADD CONSTRAINT `FK_C50CA65AF965414C` FOREIGN KEY (`saison_id`) REFERENCES `saison` (`id`);

--
-- Contraintes pour la table `adhesion_motivation`
--
ALTER TABLE `adhesion_motivation`
  ADD CONSTRAINT `FK_690EC6E48EDBCD4E` FOREIGN KEY (`motivation_id`) REFERENCES `motivation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_690EC6E4F68139D7` FOREIGN KEY (`adhesion_id`) REFERENCES `adhesion` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `adhesion_pole`
--
ALTER TABLE `adhesion_pole`
  ADD CONSTRAINT `FK_5717926419C3385` FOREIGN KEY (`pole_id`) REFERENCES `pole` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_5717926F68139D7` FOREIGN KEY (`adhesion_id`) REFERENCES `adhesion` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `FK_876E0D9FC6CD52A` FOREIGN KEY (`ressource_id`) REFERENCES `ressource` (`id`);

--
-- Contraintes pour la table `producteurice_produit`
--
ALTER TABLE `producteurice_produit`
  ADD CONSTRAINT `FK_FCA015B5EE5BE958` FOREIGN KEY (`producteurice_id`) REFERENCES `producteurice` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_FCA015B5F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `recette`
--
ALTER TABLE `recette`
  ADD CONSTRAINT `FK_49BB639055D7EF5A` FOREIGN KEY (`auteurice_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `recette_produit`
--
ALTER TABLE `recette_produit`
  ADD CONSTRAINT `FK_EDDD365D89312FE9` FOREIGN KEY (`recette_id`) REFERENCES `recette` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_EDDD365DF347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `ressource`
--
ALTER TABLE `ressource`
  ADD CONSTRAINT `FK_939F4544419C3385` FOREIGN KEY (`pole_id`) REFERENCES `pole` (`id`),
  ADD CONSTRAINT `FK_939F454451C718BE` FOREIGN KEY (`photo_principale_id`) REFERENCES `photos` (`id`),
  ADD CONSTRAINT `FK_939F4544A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_939F4544BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D6497A45358C` FOREIGN KEY (`groupe_id`) REFERENCES `groupe` (`id`);

--
-- Contraintes pour la table `user_pole`
--
ALTER TABLE `user_pole`
  ADD CONSTRAINT `FK_87E10E28419C3385` FOREIGN KEY (`pole_id`) REFERENCES `pole` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_87E10E28A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
