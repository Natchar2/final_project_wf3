-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  lun. 09 oct. 2017 à 13:25
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
  `end_date` int(11) DEFAULT NULL,
  `event_description` text NOT NULL,
  `street_name` varchar(200) NOT NULL,
  `zip_code` varchar(5) NOT NULL,
  `city` varchar(100) NOT NULL,
  `creation_date` int(11) NOT NULL,
  `ID_category` int(11) NOT NULL,
  `ID_user` int(11) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `url_1` varchar(500) NOT NULL,
  `url_2` varchar(500) NOT NULL,
  `url_3` varchar(500) NOT NULL,
  `image` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `mail` varchar(255) NOT NULL,
  `ontop` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `event`
--

INSERT INTO `event` (`ID_event`, `event_title`, `start_date`, `end_date`, `event_description`, `street_name`, `zip_code`, `city`, `creation_date`, `ID_category`, `ID_user`, `latitude`, `longitude`, `url_1`, `url_2`, `url_3`, `image`, `phone`, `mail`, `ontop`) VALUES
(12, 'event_title_2', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_2', '34708', 'city_2', 1506520807, 4, 6, '769', '', 'image2.jpg', 'image2.jpg', 'image2.jpg', 'image2.jpg', '19 40 32 37 37', 'mail2@gmail.com', 0),
(13, 'event_title_3', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_3', '57783', 'city_3', 1506520807, 2, 6, '9', '', 'image3.jpg', 'image3.jpg', 'image3.jpg', 'image3.jpg', '84 32 87 76 22', 'mail3@gmail.com', 0),
(14, 'event_title_4', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_4', '89899', 'city_4', 1506520807, 1, 9, '45.1', '', 'image4.jpg', 'https://www.instagram.com/explore/tags/rollerderby/ ', 'image4.jpg', 'image4.jpg', '83 64 96 15 92', 'mail4@gmail.com', 0),
(15, 'event_title_5', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_5', '41874', 'city_5', 1506520807, 3, 7, '45.770586', '4.805064', 'https://www.facebook.com/generationsroller/', 'https://twitter.com/Eurosport_FR?lang=fr', 'https://www.facebook.com/generationsroller/', 'image5.jpg', '97 19 90 69 17', 'mail5@gmail.com', 1),
(16, 'event_title_6', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_6', '24258', 'city_6', 1506520807, 4, 9, '330.7', '', 'image6.jpg', 'image6.jpg', 'image6.jpg', 'image6.jpg', '40 38 73 39 10', 'mail6@gmail.com', 0),
(17, 'event_title_7', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_7', '74652', 'city_7', 1506520807, 1, 5, '7.8', '', 'image7.jpg', 'image7.jpg', 'image7.jpg', 'image7.jpg', '72 27 33 89 82', 'mail7@gmail.com', 0),
(18, 'event_title_8', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_8', '01638', 'city_8', 1506520807, 4, 3, '9.6', '', 'image8.jpg', 'image8.jpg', 'image8.jpg', 'image8.jpg', '05 37 93 66 79', 'mail8@gmail.com', 0),
(19, 'event_title_9', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_9', '39776', 'city_9', 1506520807, 2, 8, '50', '', 'image9.jpg', 'image9.jpg', 'image9.jpg', 'image9.jpg', '39 56 47 33 55', 'mail9@gmail.com', 0),
(20, 'event_title_10', 1506520807, 1506520807, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'street_name_10', '80101', 'city_10', 1506520807, 1, 1, '0.7', '', 'image10.jpg', 'image10.jpg', 'image10.jpg', 'image10.jpg', '42 74 15 90 20', 'mail10@gmail.com', 0);

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `ID_order` int(11) NOT NULL,
  `ID_seller` int(11) NOT NULL,
  `ID_buyer` int(11) NOT NULL,
  `ID_customer` varchar(200) NOT NULL,
  `ID_product` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` float NOT NULL,
  `seller_status` tinyint(4) NOT NULL,
  `buyer_status` tinyint(4) NOT NULL,
  `order_date` int(11) NOT NULL,
  `validation_date` int(11) NOT NULL,
  `send_date` int(11) NOT NULL,
  `tracking_number` varchar(50) NOT NULL,
  `reception_date` int(11) NOT NULL,
  `ipadress` varchar(20) NOT NULL,
  `payment_token` varchar(300) NOT NULL,
  `error_code` int(11) NOT NULL,
  `used` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`ID_order`, `ID_seller`, `ID_buyer`, `ID_customer`, `ID_product`, `name`, `quantity`, `total_price`, `seller_status`, `buyer_status`, `order_date`, `validation_date`, `send_date`, `tracking_number`, `reception_date`, `ipadress`, `payment_token`, `error_code`, `used`) VALUES
(1, 1, 2, '0', 8, 'name_8', 1, 42.8, 2, 0, 1499328735, 1499328735, 1499328735, '45g4regv6fer56g4rt', 1507277535, '192.168.72.42', 'qsdfghjklmlokijhgtfrds', 0, 0),
(2, 2, 1, 'cus_BX0HUkYI96DlxS', 13, 'dqjkljm', 0, 112, 0, 0, 1507298027, 0, 0, '', 0, '', 'tok_1B9wGwHZWZk0L7DHksEgVnY5', 0, 0),
(3, 2, 1, 'cus_BX0HUkYI96DlxS', 10, 'gjhj', 0, 112, 0, 0, 1507298027, 0, 0, '', 0, '', 'tok_1B9wGwHZWZk0L7DHksEgVnY5', 0, 0),
(4, 2, 1, 'cus_BX0HUkYI96DlxS', 4, 'name_4', 1, 112, 0, 0, 1507298027, 0, 0, '', 0, '', 'tok_1B9wGwHZWZk0L7DHksEgVnY5', 0, 0),
(5, 2, 1, 'cus_BX0HUkYI96DlxS', 17, 'Fyfy', 0, 112, 0, 0, 1507298027, 0, 0, '', 0, '', 'tok_1B9wGwHZWZk0L7DHksEgVnY5', 0, 0),
(6, 2, 1, 'cus_BX0IaCBanoqT0J', 13, 'dqjkljm', 0, 112, 0, 0, 1507298064, 0, 0, '', 0, '', 'tok_1B9wHXHZWZk0L7DH25eAmrxN', 0, 0),
(7, 2, 1, 'cus_BX0IaCBanoqT0J', 10, 'gjhj', 0, 112, 0, 0, 1507298064, 0, 0, '', 0, '', 'tok_1B9wHXHZWZk0L7DH25eAmrxN', 0, 0),
(8, 2, 1, 'cus_BX0IaCBanoqT0J', 4, 'name_4', 1, 112, 0, 0, 1507298064, 0, 0, '', 0, '', 'tok_1B9wHXHZWZk0L7DH25eAmrxN', 0, 0),
(9, 2, 1, 'cus_BX0IaCBanoqT0J', 17, 'Fyfy', 0, 112, 0, 0, 1507298064, 0, 0, '', 0, '', 'tok_1B9wHXHZWZk0L7DH25eAmrxN', 0, 0);

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
(1, NULL, 'premier', 1506521658, 2),
(2, NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 1506521658, 2),
(3, NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 1506521658, 2),
(4, NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 1506521658, 2),
(5, NULL, 't\'as vu', 1506521658, 4),
(6, NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 1506521658, 4),
(7, NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 1506521658, 4),
(8, NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 1506521658, 2),
(9, NULL, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 1506521658, 5),
(10, NULL, 'le tt tt premier', 1506521658, 5),
(25, NULL, '<p>reuioaurioajrkljrklajrklajklr</p>', 1507189741, 2),
(26, NULL, '<p>jfekjqsdfkljQKLFJL</p>', 1507190897, 2),
(27, NULL, '<p>OLKLMKLMKLPKLKLMKLMKMLMK</p>', 1507190905, 2),
(29, 13, '<p>pk diantre</p>', 1507532111, 5);

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
  `image_1` varchar(200) DEFAULT NULL,
  `image_2` varchar(200) DEFAULT NULL,
  `image_3` varchar(200) DEFAULT NULL,
  `creation_date` int(8) NOT NULL,
  `tracking_number` varchar(50) NOT NULL,
  `shipping_charges` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`ID_product`, `name`, `brand`, `ID_user`, `ID_category`, `sub_category`, `price`, `description`, `image_1`, `image_2`, `image_3`, `creation_date`, `tracking_number`, `shipping_charges`) VALUES
(1, 'name_1', 'brand_1', 6, 3, 'sub_category_1', 5, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'image1.jpg', 'image1.jpg', 'image1.jpg', 1506521407, '2680_6096_4820', 0),
(2, 'name_2', 'brand_2', 8, 2, 'sub_category_2', 89, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'image2.jpg', 'image2.jpg', 'image2.jpg', 1506521407, '4769_5445_6846', 0),
(4, 'name_4', 'brand_4', 2, 1, 'sub_category_4', 937, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'image5.jpg', 'image4.jpg', 'image7.jpg', 1506521407, '0495_3673_7236', 0),
(6, 'name_6', 'brand_6', 3, 4, 'sub_category_6', 103.3, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'image6.jpg', 'image6.jpg', 'image6.jpg', 1506521407, '8380_0195_9491', 0),
(7, 'name_7', 'brand_7', 1, 3, 'sub_category_7', 90.5, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'image7.jpg', 'image7.jpg', 'image7.jpg', 1506521407, '5259_1110_6696', 0),
(8, 'name_8', 'brand_8', 7, 3, 'sub_category_8', 42.8, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'image8.jpg', 'image8.jpg', 'image8.jpg', 1506521407, '0086_8798_1140', 0),
(9, 'name_9', 'brand_9', 2, 2, 'sub_category_9', 61.6, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti maxime expedita magni. Nam sapiente repellat quo eos voluptatibus est dolorem! Expedita sapiente placeat minus quod impedit cum, molestias in perspiciatis!', 'image9.jpg', 'image9.jpg', 'image9.jpg', 1506521407, '2488_9841_7140', 0),
(10, 'gjhj', 'ghjghj', 2, 1, '', 12, 'tutu', 'ih8413kq553.jpg', NULL, NULL, 1507186040, '', 0),
(13, 'dqjkljm', 'klmfjqklf', 2, 1, '', 46654, '<p>oerjmqkghmhf</p>', 'h661x3b1qlh.jpg', NULL, NULL, 1507196353, '', 465465),
(17, 'Fyfy', 'burton', 2, 2, '', 20, '<p>jioqdjfrJQZKOL%J</p>', '0677d661aj2.jpg', '536ogclu0mf.jpg', NULL, 1507196829, '', 0);

-- --------------------------------------------------------

--
-- Structure de la table `topic`
--

CREATE TABLE `topic` (
  `ID_topic` int(11) NOT NULL,
  `ID_category` int(11) NOT NULL,
  `ID_product` int(11) NOT NULL DEFAULT '0',
  `ID_event` int(11) NOT NULL DEFAULT '0',
  `title` varchar(150) NOT NULL,
  `creation_date` int(11) NOT NULL,
  `ID_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `topic`
--

INSERT INTO `topic` (`ID_topic`, `ID_category`, `ID_product`, `ID_event`, `title`, `creation_date`, `ID_user`) VALUES
(1, 1, 0, 0, 'title_1', 1506521530, 1),
(2, 2, 13, 0, 'title_2', 1506521530, 1),
(3, 3, 0, 0, 'title_3', 1506521530, 6),
(4, 4, 0, 0, 'title_4', 1506521530, 1),
(5, 2, 0, 15, 'title_5', 1506521530, 1),
(6, 3, 0, 0, 'title_6', 1506521530, 1),
(7, 3, 0, 0, 'title_7', 1506521530, 8),
(8, 1, 0, 0, 'title_8', 1506521530, 13),
(9, 4, 0, 0, 'title_9', 1506521530, 10),
(10, 2, 0, 0, 'title_10', 1506521530, 10),
(11, 2, 0, 0, 'les catherinette', 1507535422, 13),
(12, 3, 0, 0, 'les catherinette', 1507536187, 13),
(13, 3, 0, 0, 'sainte catherine', 1507537266, 13),
(14, 1, 0, 0, 'les catherinette', 1507537467, 13),
(15, 3, 0, 0, 'les catherinette', 1507542061, 13),
(16, 3, 0, 0, 'les catherinette', 1507542307, 13);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `ID_user` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `surname` varchar(150) NOT NULL,
  `password` varchar(100) NOT NULL,
  `pseudo` varchar(100) NOT NULL,
  `street` varchar(250) NOT NULL,
  `zip_code` varchar(5) NOT NULL,
  `city` varchar(150) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `society_name` varchar(100) DEFAULT NULL,
  `creation_date` int(11) NOT NULL,
  `connexion_date` int(11) NOT NULL,
  `last_connexion` int(11) NOT NULL,
  `type` varchar(200) NOT NULL DEFAULT 'ROLE_USER',
  `avatar` varchar(200) NOT NULL,
  `reset_password_date` int(11) NOT NULL,
  `reset_password_token` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`ID_user`, `name`, `surname`, `password`, `pseudo`, `street`, `zip_code`, `city`, `mail`, `phone`, `society_name`, `creation_date`, `connexion_date`, `last_connexion`, `type`, `avatar`, `reset_password_date`, `reset_password_token`) VALUES
(1, 'name_1', 'surname_1', 'password_1', 'pseudo_1', 'street_1', '31629', 'city_1', 'root@localhost', '87 61 24 59 07', 'society_name_1', 1506521151, 1506521151, 1506521151, '0', 'image1.jpg', 0, ''),
(2, 'name_2', 'surname_2', 'password_2', 'pseudo_2', 'street_2', '86312', 'city_2', 'root@localhost1', '51 44 10 05 34', 'society_name_2', 1506521151, 1506521151, 1506521151, '0', 'image2.jpg', 0, ''),
(3, 'name_3', 'surname_3', 'password_3', 'pseudo_3', 'street_3', '47765', 'city_3', 'root@localhost2', '96 48 11 73 14', 'society_name_3', 1506521151, 1506521151, 1506521151, '0', 'image3.jpg', 0, ''),
(4, 'name_4', 'surname_4', 'password_4', 'pseudo_4', 'street_4', '34424', 'city_4', 'root@localhost3', '94 59 91 61 69', 'society_name_4', 1506521151, 1506521151, 1506521151, '0', 'image4.jpg', 0, ''),
(5, 'name_5', 'surname_5', 'password_5', 'pseudo_5', 'street_5', '25196', 'city_5', 'root@localhost4', '92 95 60 58 19', 'society_name_5', 1506521151, 1506521151, 1506521151, '0', 'image5.jpg', 0, ''),
(6, 'name_6', 'surname_6', 'password_6', 'pseudo_6', 'street_6', '02982', 'city_6', 'root@localhost5', '48 57 68 04 39', 'society_name_6', 1506521151, 1506521151, 1506521151, '0', 'image6.jpg', 0, ''),
(7, 'name_7', 'surname_7', 'password_7', 'pseudo_7', 'street_7', '98228', 'city_7', 'root@localhost6', '30 67 74 75 86', 'society_name_7', 1506521151, 1506521151, 1506521151, '0', 'image7.jpg', 0, ''),
(8, 'name_8', 'surname_8', 'password_8', 'pseudo_8', 'street_8', '61607', 'city_8', 'root@localhost7', '25 61 03 09 98', 'society_name_8', 1506521151, 1506521151, 1506521151, '0', 'image8.jpg', 0, ''),
(9, 'name_9', 'surname_9', 'password_9', 'pseudo_9', 'street_9', '48421', 'city_9', 'root@localhost8', '13 87 48 31 02', 'society_name_9', 1506521151, 1506521151, 1506521151, '0', 'image9.jpg', 0, ''),
(10, 'name_10', 'surname_10', 'password_10', 'pseudo_10', 'street_10', '45180', 'city_10', 'root@localhost9', '42 18 49 86 76', 'society_name_10', 1506521151, 1506521151, 1506521151, '0', 'image10.jpg', 0, ''),
(11, 'Admin', 'Lucie', '$2y$10$kiJVdRgpPnHd74SMgU/oKO8LZmNAXaQeRklF6D8BtgLVmW.4L.Wnq', 'Lucie', '', '', '', 'test@gmail.com', '', '', 0, 0, 0, 'ROLE_ADMIN', '', 0, ''),
(12, 'test', 'test', 'stHGdg4MhYOm/OVTWjpMJievIvJqafsQQ3WpWlUNDT6WfHupVWjBQaxdppMQ', 'test', 'test', '12345', 'test', 'test@test.fr', '1234567890', 'test', 1507274023, 0, 0, 'ROLE_USER', 'c6hg33t9wg3.jpg', 0, ''),
(13, 'Lucie', 'Lucie', 'nhDr7OyKlXQju+Ge/WKGrPQ9lPBSUFfpK+B1xqx/+8zLZqRNX0+5G1zBQklXUFy86lCpkAofsExlXiorUcKSNQ==', 'Lucie', '30', '69005', 'Lyon', 'bou@gmail.com', '06-92-35-17-89', 'mouahahaha', 1507366533, 0, 0, 'ROLE_ADMIN', 'kj736ukgu62.jpg', 0, ''),
(14, 'Lucie', 'lalmal', 'nhDr7OyKlXQju+Ge/WKGrPQ9lPBSUFfpK+B1xqx/+8zLZqRNX0+5G1zBQklXUFy86lCpkAofsExlXiorUcKSNQ==', 'lalmam', '55 jjiodjqsjiod', '05600', 'iuofiosdhfioh', 'retest@gmail.com', '0492452546', 'lolololo', 1507541756, 0, 0, 'ROLE_USER', '23e750fv4ua.jpg', 0, '');

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `view_accueil_forum`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `view_accueil_forum` (
`ID_topic` int(11)
,`ID_category` int(11)
,`ID_product` int(11)
,`ID_event` int(11)
,`title` varchar(150)
,`creation_date` int(11)
,`ID_user` int(11)
,`category_name` varchar(50)
,`event_title` varchar(150)
,`name` varchar(250)
,`pseudo` varchar(100)
,`content` text
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `view_events`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `view_events` (
`ID_event` int(11)
,`event_title` varchar(150)
,`start_date` int(11)
,`end_date` int(11)
,`event_description` text
,`street_name` varchar(200)
,`zip_code` varchar(5)
,`city` varchar(100)
,`creation_date` int(11)
,`ID_category` int(11)
,`ID_user` int(11)
,`latitude` varchar(50)
,`longitude` varchar(50)
,`url_1` varchar(500)
,`url_2` varchar(500)
,`url_3` varchar(500)
,`image` varchar(255)
,`phone` varchar(15)
,`mail` varchar(255)
,`ontop` tinyint(1)
,`category_name` varchar(50)
,`pseudo` varchar(100)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `view_posts`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `view_posts` (
`creation_date` int(11)
,`title` varchar(150)
,`pseudo` varchar(100)
,`ID_post` int(11)
,`ID_user` int(11)
,`content` text
,`post_date` int(11)
,`ID_topic` int(11)
);

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
,`shipping_charges` float
,`category_name` varchar(50)
,`pseudo` varchar(100)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `view_topics`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `view_topics` (
`ID_topic` int(11)
,`ID_category` int(11)
,`ID_product` int(11)
,`ID_event` int(11)
,`title` varchar(150)
,`creation_date` int(11)
,`ID_user` int(11)
,`category_name` varchar(50)
,`event_title` varchar(150)
,`name` varchar(250)
,`pseudo` varchar(100)
);

-- --------------------------------------------------------

--
-- Structure de la vue `view_accueil_forum`
--
DROP TABLE IF EXISTS `view_accueil_forum`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_accueil_forum`  AS  select `view_topics`.`ID_topic` AS `ID_topic`,`view_topics`.`ID_category` AS `ID_category`,`view_topics`.`ID_product` AS `ID_product`,`view_topics`.`ID_event` AS `ID_event`,`view_topics`.`title` AS `title`,`view_topics`.`creation_date` AS `creation_date`,`view_topics`.`ID_user` AS `ID_user`,`view_topics`.`category_name` AS `category_name`,`view_topics`.`event_title` AS `event_title`,`view_topics`.`name` AS `name`,`view_topics`.`pseudo` AS `pseudo`,`post`.`content` AS `content` from (`view_topics` left join `post` on((`view_topics`.`ID_topic` = `post`.`ID_topic`))) group by `view_topics`.`ID_topic` order by `view_topics`.`creation_date` ;

-- --------------------------------------------------------

--
-- Structure de la vue `view_events`
--
DROP TABLE IF EXISTS `view_events`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_events`  AS  select `event`.`ID_event` AS `ID_event`,`event`.`event_title` AS `event_title`,`event`.`start_date` AS `start_date`,`event`.`end_date` AS `end_date`,`event`.`event_description` AS `event_description`,`event`.`street_name` AS `street_name`,`event`.`zip_code` AS `zip_code`,`event`.`city` AS `city`,`event`.`creation_date` AS `creation_date`,`event`.`ID_category` AS `ID_category`,`event`.`ID_user` AS `ID_user`,`event`.`latitude` AS `latitude`,`event`.`longitude` AS `longitude`,`event`.`url_1` AS `url_1`,`event`.`url_2` AS `url_2`,`event`.`url_3` AS `url_3`,`event`.`image` AS `image`,`event`.`phone` AS `phone`,`event`.`mail` AS `mail`,`event`.`ontop` AS `ontop`,`category`.`category_name` AS `category_name`,`users`.`pseudo` AS `pseudo` from ((`event` left join `category` on((`event`.`ID_category` = `category`.`ID_category`))) left join `users` on((`event`.`ID_user` = `users`.`ID_user`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `view_posts`
--
DROP TABLE IF EXISTS `view_posts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_posts`  AS  select `topic`.`creation_date` AS `creation_date`,`topic`.`title` AS `title`,`users`.`pseudo` AS `pseudo`,`post`.`ID_post` AS `ID_post`,`post`.`ID_user` AS `ID_user`,`post`.`content` AS `content`,`post`.`post_date` AS `post_date`,`post`.`ID_topic` AS `ID_topic` from ((`topic` left join `users` on((`topic`.`ID_user` = `users`.`ID_user`))) left join `post` on((`topic`.`ID_topic` = `post`.`ID_topic`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `view_products`
--
DROP TABLE IF EXISTS `view_products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_products`  AS  select `products`.`ID_product` AS `ID_product`,`products`.`name` AS `name`,`products`.`brand` AS `brand`,`products`.`ID_user` AS `ID_user`,`products`.`ID_category` AS `ID_category`,`products`.`sub_category` AS `sub_category`,`products`.`price` AS `price`,`products`.`description` AS `description`,`products`.`image_1` AS `image_1`,`products`.`image_2` AS `image_2`,`products`.`image_3` AS `image_3`,`products`.`creation_date` AS `creation_date`,`products`.`tracking_number` AS `tracking_number`,`products`.`shipping_charges` AS `shipping_charges`,`category`.`category_name` AS `category_name`,`users`.`pseudo` AS `pseudo` from ((`products` join `category`) join `users`) where ((`products`.`ID_category` = `category`.`ID_category`) and (`products`.`ID_user` = `users`.`ID_user`)) order by `products`.`creation_date` desc ;

-- --------------------------------------------------------

--
-- Structure de la vue `view_topics`
--
DROP TABLE IF EXISTS `view_topics`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_topics`  AS  select `topic`.`ID_topic` AS `ID_topic`,`topic`.`ID_category` AS `ID_category`,`topic`.`ID_product` AS `ID_product`,`topic`.`ID_event` AS `ID_event`,`topic`.`title` AS `title`,`topic`.`creation_date` AS `creation_date`,`topic`.`ID_user` AS `ID_user`,`category`.`category_name` AS `category_name`,`event`.`event_title` AS `event_title`,`products`.`name` AS `name`,`users`.`pseudo` AS `pseudo` from ((((`topic` left join `category` on((`category`.`ID_category` = `topic`.`ID_category`))) left join `event` on((`topic`.`ID_event` = `event`.`ID_event`))) left join `products` on((`topic`.`ID_product` = `products`.`ID_product`))) left join `users` on((`topic`.`ID_user` = `users`.`ID_user`))) ;

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
  ADD KEY `user` (`ID_user`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID_order`);

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
  ADD PRIMARY KEY (`ID_user`),
  ADD UNIQUE KEY `mail` (`mail`);

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
  MODIFY `ID_event` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `ID_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `ID_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `ID_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT pour la table `topic`
--
ALTER TABLE `topic`
  MODIFY `ID_topic` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `ID_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `categorie` FOREIGN KEY (`ID_category`) REFERENCES `category` (`ID_category`),
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
