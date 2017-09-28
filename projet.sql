-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  jeu. 28 sep. 2017 à 08:51
-- Version du serveur :  10.1.22-MariaDB
-- Version de PHP :  7.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `projet`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `ID_category` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`ID_category`, `category_name`) VALUES
(1, 'skate'),
(2, 'parkour'),
(3, 'bmx'),
(4, 'roller');

-- --------------------------------------------------------

--
-- Structure de la table `event`
--

CREATE TABLE `event` (
  `ID_event` int(11) NOT NULL,
  `event_title` varchar(150) NOT NULL,
  `start_date` int(11) NOT NULL,
  `end_date` int(11) NOT NULL,
  `event_description` text NOT NULL,
  `street_name` varchar(200) NOT NULL,
  `zip_code` varchar(5) NOT NULL,
  `city` varchar(100) NOT NULL,
  `creation_date` int(11) NOT NULL,
  `ID_category` int(11) NOT NULL,
  `ID_topic` int(11) DEFAULT NULL,
  `ID_user` int(11) DEFAULT NULL,
  `coordinates` varchar(50) NOT NULL,
  `url_1` varchar(500) NOT NULL,
  `url_2` varchar(500) NOT NULL,
  `url_3` varchar(500) NOT NULL,
  `image` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `mail` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `event`
--

INSERT INTO `event` (`ID_event`, `event_title`, `start_date`, `end_date`, `event_description`, `street_name`, `zip_code`, `city`, `creation_date`, `ID_category`, `ID_topic`, `ID_user`, `coordinates`, `url_1`, `url_2`, `url_3`, `image`, `phone`, `mail`) VALUES
(11, 'event_title_1', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_1', '57285', 'city_1', 1506520807, 3, 2, 4, '6.5', 'image1.jpg', 'image1.jpg', 'image1.jpg', 'image1.jpg', '10 01 27 19 14', 'mail1@gmail.com'),
(12, 'event_title_2', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_2', '34708', 'city_2', 1506520807, 4, 7, 6, '769', 'image2.jpg', 'image2.jpg', 'image2.jpg', 'image2.jpg', '19 40 32 37 37', 'mail2@gmail.com'),
(13, 'event_title_3', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_3', '57783', 'city_3', 1506520807, 2, 4, 6, '9', 'image3.jpg', 'image3.jpg', 'image3.jpg', 'image3.jpg', '84 32 87 76 22', 'mail3@gmail.com'),
(14, 'event_title_4', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_4', '89899', 'city_4', 1506520807, 1, 1, 9, '45.1', 'image4.jpg', 'image4.jpg', 'image4.jpg', 'image4.jpg', '83 64 96 15 92', 'mail4@gmail.com'),
(15, 'event_title_5', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_5', '41874', 'city_5', 1506520807, 3, 4, 7, '258.8', 'image5.jpg', 'image5.jpg', 'image5.jpg', 'image5.jpg', '97 19 90 69 17', 'mail5@gmail.com'),
(16, 'event_title_6', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_6', '24258', 'city_6', 1506520807, 4, 5, 9, '330.7', 'image6.jpg', 'image6.jpg', 'image6.jpg', 'image6.jpg', '40 38 73 39 10', 'mail6@gmail.com'),
(17, 'event_title_7', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_7', '74652', 'city_7', 1506520807, 1, 1, 5, '7.8', 'image7.jpg', 'image7.jpg', 'image7.jpg', 'image7.jpg', '72 27 33 89 82', 'mail7@gmail.com'),
(18, 'event_title_8', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_8', '01638', 'city_8', 1506520807, 4, 6, 3, '9.6', 'image8.jpg', 'image8.jpg', 'image8.jpg', 'image8.jpg', '05 37 93 66 79', 'mail8@gmail.com'),
(19, 'event_title_9', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_9', '39776', 'city_9', 1506520807, 2, 5, 8, '50', 'image9.jpg', 'image9.jpg', 'image9.jpg', 'image9.jpg', '39 56 47 33 55', 'mail9@gmail.com'),
(20, 'event_title_10', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_10', '80101', 'city_10', 1506520807, 1, 1, 1, '0.7', 'image10.jpg', 'image10.jpg', 'image10.jpg', 'image10.jpg', '42 74 15 90 20', 'mail10@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `ID_post` int(11) NOT NULL,
  `ID_user` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `post_date` int(11) NOT NULL,
  `ID_topic` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`ID_post`, `ID_user`, `content`, `post_date`, `ID_topic`) VALUES
(1, NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 1506521658, NULL),
(2, NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 1506521658, NULL),
(3, NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 1506521658, NULL),
(4, NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 1506521658, NULL),
(5, NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 1506521658, NULL),
(6, NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 1506521658, NULL),
(7, NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 1506521658, NULL),
(8, NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 1506521658, NULL),
(9, NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 1506521658, NULL),
(10, NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 1506521658, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `ID_product` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `brand` varchar(150) NOT NULL,
  `ID_user` int(11) DEFAULT NULL,
  `ID_category` int(11) NOT NULL,
  `sub_category` varchar(250) NOT NULL,
  `price` float NOT NULL,
  `description` text NOT NULL,
  `image_1` varchar(200) NOT NULL,
  `image_2` varchar(200) NOT NULL,
  `image_3` varchar(200) NOT NULL,
  `creation_date` int(8) NOT NULL,
  `tracking_number` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`ID_product`, `name`, `brand`, `ID_user`, `ID_category`, `sub_category`, `price`, `description`, `image_1`, `image_2`, `image_3`, `creation_date`, `tracking_number`) VALUES
(1, 'name_1', 'brand_1', 6, 3, 'sub_category_1', 5, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'image1.jpg', 'image1.jpg', 'image1.jpg', 1506521407, '2680_6096_4820'),
(2, 'name_2', 'brand_2', 8, 2, 'sub_category_2', 89, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'image2.jpg', 'image2.jpg', 'image2.jpg', 1506521407, '4769_5445_6846'),
(3, 'name_3', 'brand_3', 8, 4, 'sub_category_3', 47.3, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'image3.jpg', 'image3.jpg', 'image3.jpg', 1506521407, '0392_7589_1731'),
(4, 'name_4', 'brand_4', 2, 1, 'sub_category_4', 937, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'image4.jpg', 'image4.jpg', 'image4.jpg', 1506521407, '0495_3673_7236'),
(5, 'name_5', 'brand_5', 8, 3, 'sub_category_5', 46, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'image5.jpg', 'image5.jpg', 'image5.jpg', 1506521407, '1440_9000_1172'),
(6, 'name_6', 'brand_6', 3, 4, 'sub_category_6', 103.3, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'image6.jpg', 'image6.jpg', 'image6.jpg', 1506521407, '8380_0195_9491'),
(7, 'name_7', 'brand_7', 1, 3, 'sub_category_7', 90.5, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'image7.jpg', 'image7.jpg', 'image7.jpg', 1506521407, '5259_1110_6696'),
(8, 'name_8', 'brand_8', 7, 3, 'sub_category_8', 42.8, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'image8.jpg', 'image8.jpg', 'image8.jpg', 1506521407, '0086_8798_1140'),
(9, 'name_9', 'brand_9', 2, 2, 'sub_category_9', 61.6, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'image9.jpg', 'image9.jpg', 'image9.jpg', 1506521407, '2488_9841_7140'),
(10, 'name_10', 'brand_10', 5, 4, 'sub_category_10', 505.8, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'image10.jpg', 'image10.jpg', 'image10.jpg', 1506521407, '6990_9475_1493');

-- --------------------------------------------------------

--
-- Structure de la table `topic`
--

CREATE TABLE `topic` (
  `ID_topic` int(11) NOT NULL,
  `ID_category` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `creation_date` int(11) NOT NULL,
  `ID_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `topic`
--

INSERT INTO `topic` (`ID_topic`, `ID_category`, `title`, `creation_date`, `ID_user`) VALUES
(1, 1, 'title_1', 1506521530, 1),
(2, 2, 'title_2', 1506521530, 1),
(3, 3, 'title_3', 1506521530, 6),
(4, 4, 'title_4', 1506521530, 1),
(5, 2, 'title_5', 1506521530, 1),
(6, 3, 'title_6', 1506521530, 1),
(7, 3, 'title_7', 1506521530, 8),
(8, 1, 'title_8', 1506521530, 5),
(9, 4, 'title_9', 1506521530, 10),
(10, 2, 'title_10', 1506521530, 10);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `ID_user` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `surname` varchar(150) NOT NULL,
  `password` varchar(60) NOT NULL,
  `pseudo` varchar(100) NOT NULL,
  `street` varchar(250) NOT NULL,
  `zip_code` varchar(5) NOT NULL,
  `city` varchar(150) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `society_name` varchar(100) NOT NULL,
  `creation_date` int(11) NOT NULL,
  `connexion_date` int(11) NOT NULL,
  `last_connexion` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `avatar` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`ID_user`, `name`, `surname`, `password`, `pseudo`, `street`, `zip_code`, `city`, `mail`, `phone`, `society_name`, `creation_date`, `connexion_date`, `last_connexion`, `type`, `avatar`) VALUES
(1, 'name_1', 'surname_1', 'password_1', 'pseudo_1', 'street_1', '31629', 'city_1', 'mail1@gmail.com', '87 61 24 59 07', 'society_name_1', 1506521151, 1506521151, 1506521151, 0, 'image1.jpg'),
(2, 'name_2', 'surname_2', 'password_2', 'pseudo_2', 'street_2', '86312', 'city_2', 'mail2@gmail.com', '51 44 10 05 34', 'society_name_2', 1506521151, 1506521151, 1506521151, 0, 'image2.jpg'),
(3, 'name_3', 'surname_3', 'password_3', 'pseudo_3', 'street_3', '47765', 'city_3', 'mail3@gmail.com', '96 48 11 73 14', 'society_name_3', 1506521151, 1506521151, 1506521151, 0, 'image3.jpg'),
(4, 'name_4', 'surname_4', 'password_4', 'pseudo_4', 'street_4', '34424', 'city_4', 'mail4@gmail.com', '94 59 91 61 69', 'society_name_4', 1506521151, 1506521151, 1506521151, 0, 'image4.jpg'),
(5, 'name_5', 'surname_5', 'password_5', 'pseudo_5', 'street_5', '25196', 'city_5', 'mail5@gmail.com', '92 95 60 58 19', 'society_name_5', 1506521151, 1506521151, 1506521151, 0, 'image5.jpg'),
(6, 'name_6', 'surname_6', 'password_6', 'pseudo_6', 'street_6', '02982', 'city_6', 'mail6@gmail.com', '48 57 68 04 39', 'society_name_6', 1506521151, 1506521151, 1506521151, 0, 'image6.jpg'),
(7, 'name_7', 'surname_7', 'password_7', 'pseudo_7', 'street_7', '98228', 'city_7', 'mail7@gmail.com', '30 67 74 75 86', 'society_name_7', 1506521151, 1506521151, 1506521151, 0, 'image7.jpg'),
(8, 'name_8', 'surname_8', 'password_8', 'pseudo_8', 'street_8', '61607', 'city_8', 'mail8@gmail.com', '25 61 03 09 98', 'society_name_8', 1506521151, 1506521151, 1506521151, 0, 'image8.jpg'),
(9, 'name_9', 'surname_9', 'password_9', 'pseudo_9', 'street_9', '48421', 'city_9', 'mail9@gmail.com', '13 87 48 31 02', 'society_name_9', 1506521151, 1506521151, 1506521151, 0, 'image9.jpg'),
(10, 'name_10', 'surname_10', 'password_10', 'pseudo_10', 'street_10', '45180', 'city_10', 'mail10@gmail.com', '42 18 49 86 76', 'society_name_10', 1506521151, 1506521151, 1506521151, 0, 'image10.jpg'),
(11, 'Admin', 'Lucie', '$2y$10$kiJVdRgpPnHd74SMgU/oKO8LZmNAXaQeRklF6D8BtgLVmW.4L.Wnq', 'Lucie', '', '', '', '', '', '', 0, 0, 0, 1, '');

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `view_products`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `view_products` (
`ID_product` int(11)
,`name` varchar(250)
,`brand` varchar(150)
,`ID_user` int(11)
,`ID_category` int(11)
,`sub_category` varchar(250)
,`price` float
,`description` text
,`image_1` varchar(200)
,`image_2` varchar(200)
,`image_3` varchar(200)
,`creation_date` int(8)
,`tracking_number` varchar(50)
,`category_name` varchar(50)
);

-- --------------------------------------------------------

--
-- Structure de la vue `view_products`
--
DROP TABLE IF EXISTS `view_products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`gouly`@`%` SQL SECURITY DEFINER VIEW `view_products`  AS  select `products`.`ID_product` AS `ID_product`,`products`.`name` AS `name`,`products`.`brand` AS `brand`,`products`.`ID_user` AS `ID_user`,`products`.`ID_category` AS `ID_category`,`products`.`sub_category` AS `sub_category`,`products`.`price` AS `price`,`products`.`description` AS `description`,`products`.`image_1` AS `image_1`,`products`.`image_2` AS `image_2`,`products`.`image_3` AS `image_3`,`products`.`creation_date` AS `creation_date`,`products`.`tracking_number` AS `tracking_number`,`category`.`category_name` AS `category_name` from (`products` join `category`) where (`products`.`ID_category` = `category`.`ID_category`) ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`ID_category`);

--
-- Index pour la table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`ID_event`),
  ADD KEY `categorie` (`ID_category`),
  ADD KEY `user` (`ID_user`),
  ADD KEY `topic` (`ID_topic`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`ID_post`),
  ADD KEY `userpost` (`ID_user`),
  ADD KEY `topicpost` (`ID_topic`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ID_product`),
  ADD KEY `userprod` (`ID_user`),
  ADD KEY `cateprod` (`ID_category`);

--
-- Index pour la table `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`ID_topic`),
  ADD KEY `usertopic` (`ID_user`),
  ADD KEY `catetopic` (`ID_category`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID_user`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `ID_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `event`
--
ALTER TABLE `event`
  MODIFY `ID_event` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `ID_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `ID_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pour la table `topic`
--
ALTER TABLE `topic`
  MODIFY `ID_topic` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `ID_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `categorie` FOREIGN KEY (`ID_category`) REFERENCES `category` (`ID_category`),
  ADD CONSTRAINT `topic` FOREIGN KEY (`ID_topic`) REFERENCES `topic` (`ID_topic`),
  ADD CONSTRAINT `user` FOREIGN KEY (`ID_user`) REFERENCES `users` (`ID_user`);

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `topicpost` FOREIGN KEY (`ID_topic`) REFERENCES `topic` (`ID_topic`),
  ADD CONSTRAINT `userpost` FOREIGN KEY (`ID_user`) REFERENCES `users` (`ID_user`);

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `cateprod` FOREIGN KEY (`ID_category`) REFERENCES `category` (`ID_category`),
  ADD CONSTRAINT `userprod` FOREIGN KEY (`ID_user`) REFERENCES `users` (`ID_user`);

--
-- Contraintes pour la table `topic`
--
ALTER TABLE `topic`
  ADD CONSTRAINT `catetopic` FOREIGN KEY (`ID_category`) REFERENCES `category` (`ID_category`),
  ADD CONSTRAINT `usertopic` FOREIGN KEY (`ID_user`) REFERENCES `users` (`ID_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
