-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 15, 2022 at 07:58 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(300) NOT NULL,
  `id_parent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`, `id_parent`) VALUES
(1, 'mięsa, wędliny', NULL),
(2, 'ryby, owoce morza', NULL),
(3, 'pieczywo', NULL),
(4, 'sery, jogurty i mleczne', NULL),
(5, 'chleb', 3),
(6, 'masło', 4),
(7, 'bułki', 3),
(8, 'Mięso', 1),
(9, 'Wędliny', 1),
(10, 'Ryby', 2),
(11, 'Owoce morza', 2),
(12, 'Mleko', 4);

-- --------------------------------------------------------

--
-- Table structure for table `incorrect_logins`
--

CREATE TABLE `incorrect_logins` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `session_id` varchar(255) DEFAULT NULL,
  `id_address` bigint(20) NOT NULL,
  `computer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ip_address`
--

CREATE TABLE `ip_address` (
  `id` bigint(20) NOT NULL,
  `ok_login_num` bigint(20) NOT NULL,
  `bad_login_num` int(11) NOT NULL,
  `last_bad_login_num` smallint(6) DEFAULT NULL,
  `permanent_lock` tinyint(4) NOT NULL,
  `temp_lock` timestamp NULL DEFAULT NULL,
  `address_ip` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ip_address`
--

INSERT INTO `ip_address` (`id`, `ok_login_num`, `bad_login_num`, `last_bad_login_num`, `permanent_lock`, `temp_lock`, `address_ip`) VALUES
(1, 0, 0, 0, 0, NULL, 'IP'),
(2, 0, 0, 0, 0, NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_polish_ci NOT NULL COMMENT 'name of the message',
  `type` varchar(20) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'type of the message\\\\\\\\r\\\\\\\\n(private/public)',
  `message` varchar(2000) COLLATE utf8_polish_ci NOT NULL COMMENT 'message text',
  `deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'existing message - 0, deleted - 1',
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `name`, `type`, `message`, `deleted`, `id_user`) VALUES
(14, 'stary tytuł', 'public', 'stara wiadomość                ', 0, 5),
(17, 'Niewidzialna', 'public', '                wiadomość      3                          ', 0, 6),
(18, 'Witaj ', 'private', 'świecie', 0, 7),
(21, 'bvvvv', 'public', 'edycja                                     ', 0, 5),
(24, 'aaaaaaa', 'public', '                                                               aaaaa    5                                          ', 0, 5),
(25, 'New message', 'public', 'My brand new message', 0, 5),
(26, 'a', 'public', '      a          ', 0, 5),
(27, 'a', 'public', '      a          ', 0, 5),
(28, 'vb', 'public', '          b      ', 0, 5),
(29, 'nowy', 'public', 'stara treść                                ', 0, 5),
(30, 'stary tytuł', 'public', 'stara wiadomość                ', 1, 5),
(31, 'Dodana wiadomość', 'public', 'dodana treść      ', 0, 5);

-- --------------------------------------------------------

--
-- Table structure for table `mqtt`
--

CREATE TABLE `mqtt` (
  `id` int(11) NOT NULL,
  `topic` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `qos` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mqtt`
--

INSERT INTO `mqtt` (`id`, `topic`, `message`, `qos`) VALUES
(1, 'zamów produkty', 'Marchewka 500 kg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `privilege`
--

CREATE TABLE `privilege` (
  `id` int(11) NOT NULL,
  `id_parent_privilege` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `active` tinyint(4) DEFAULT NULL,
  `asset_url` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `privilege`
--

INSERT INTO `privilege` (`id`, `id_parent_privilege`, `name`, `active`, `asset_url`) VALUES
(105, NULL, 'manage user activity', 1, NULL),
(106, NULL, 'register users', 1, NULL),
(107, NULL, 'edit cart', 1, NULL),
(109, NULL, 'order products', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `count` decimal(10,3) DEFAULT NULL,
  `id_category` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `count`, `id_category`, `price`) VALUES
(1, 'masło', '0.200', 6, '2.50'),
(2, 'chleb pszenny', '0.500', 5, '3.49'),
(3, 'chleb żytni', '0.350', 5, '2.89');

-- --------------------------------------------------------

--
-- Table structure for table `product_transaction`
--

CREATE TABLE `product_transaction` (
  `id_product` int(11) NOT NULL,
  `id_transaction` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_transaction`
--

INSERT INTO `product_transaction` (`id_product`, `id_transaction`) VALUES
(1, 5),
(1, 6),
(1, 7),
(2, 5),
(2, 6),
(2, 7),
(2, 8),
(2, 12),
(2, 13),
(2, 14),
(2, 15),
(2, 16),
(3, 9),
(3, 12);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `role_name` varchar(30) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `role_name`, `description`) VALUES
(3, 'admin', ''),
(17, 'kasjer', 'kasjer'),
(18, 'kierownik', NULL),
(19, 'sprzątacz', 'sprząta');

-- --------------------------------------------------------

--
-- Table structure for table `role_privilege`
--

CREATE TABLE `role_privilege` (
  `id` int(11) NOT NULL,
  `id_role` int(11) NOT NULL,
  `id_privilege` int(11) NOT NULL,
  `issue_time` date NOT NULL,
  `expire_time` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role_privilege`
--

INSERT INTO `role_privilege` (`id`, `id_role`, `id_privilege`, `issue_time`, `expire_time`) VALUES
(36, 3, 107, '2022-06-15', NULL),
(37, 3, 105, '2022-06-15', NULL),
(38, 3, 106, '2022-06-15', NULL),
(39, 18, 109, '2022-06-15', NULL),
(40, 18, 107, '2022-06-15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `id_cashier` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `datetime`, `id_cashier`) VALUES
(1, '2022-06-14 14:00:56', 6),
(2, '2022-06-14 14:01:21', 6),
(3, '2022-06-14 14:01:25', 6),
(4, '2022-06-14 14:02:00', 6),
(5, '2022-06-14 14:02:25', 6),
(6, '2022-06-14 14:03:13', 6),
(7, '2022-06-14 20:56:16', 6),
(8, '2022-06-14 21:10:20', 6),
(9, '2022-06-15 09:21:44', 6),
(10, '2022-06-15 13:07:54', 8),
(11, '2022-06-15 13:09:02', 8),
(12, '2022-06-15 13:09:29', 8),
(13, '2022-06-15 13:33:55', 8),
(14, '2022-06-15 18:40:47', 8),
(15, '2022-06-15 18:42:57', 8),
(16, '2022-06-15 18:47:09', 9);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `login` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_polish_ci NOT NULL,
  `hash` varchar(255) COLLATE utf8_polish_ci NOT NULL COMMENT 'password hash or HMAC value',
  `salt` blob DEFAULT NULL COMMENT 'salt to use in password hashing',
  `sms_code` varchar(6) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'security code sent via\\\\\\\\r\\\\\\\\nsms or e-mail',
  `code_timelife` timestamp NULL DEFAULT NULL COMMENT 'timelife of security code',
  `security_question` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'additional\\\\\\\\r\\\\\\\\nsecurity question used while password recovering',
  `answer` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'security question answer',
  `temp_lock` timestamp NULL DEFAULT NULL COMMENT 'time to which user account is blocked',
  `session_id` blob DEFAULT NULL COMMENT 'user session identifier',
  `id_status` int(11) NOT NULL COMMENT 'account status',
  `password_form` int(11) NOT NULL DEFAULT 1 COMMENT '1- SHA512, 2-SHA512+salt,3- HMAC',
  `two_factor_authentication` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `login`, `email`, `hash`, `salt`, `sms_code`, `code_timelife`, `security_question`, `answer`, `temp_lock`, `session_id`, `id_status`, `password_form`, `two_factor_authentication`) VALUES
(-1, 'incorrect', 'incorrect@gmail.com', 'incorrect', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1),
(5, 'aaa', 'aaa@gmail.com', 'ec48fa0b053eb0a1e2a8c5e1a7be09578da8a828fa5ee957396959102a4f0754f5059f5e4514d56ee36889d138c6c9f6f6a506cee8969e42fff1a29d69add923', 0x90e47a76415a3fe6e1a03e566ace2f1a, '297114', '2022-06-15 11:15:46', NULL, NULL, NULL, NULL, 1, 1, 1),
(6, 'bbb', 'bbb@gmail.com', '7fa98a49b60e2ffb6cf199775263c0dea5138fe7ec83800f680fe0ce5700644fa1cc417ab7e57f0c53a4993b693d2f425198b46dee237f2782697e5045025961', 0xa52bcb7f7b8d3942d196bc65b8718069, '537858', '2022-06-15 11:12:42', NULL, NULL, NULL, NULL, 1, 1, 1),
(7, 'ccc', 'ccc@gmail.com', 'a22f890330c6af47d347383aa67d79032f4800f6cd673f9c9ba56a8b0ab73eb64ca7ce630c765c3bc399a3da7c70ea3e1311b4109a88481e295c79a4ff43a1d4', 0x78c058535a6949b834ea84aa34d57b9d, '271253', '2022-05-16 11:19:24', NULL, NULL, NULL, NULL, 1, 1, 1),
(8, 'basia', 'basia@gmail.com', 'd772d6b2d9b2064bfc25b9fe8f304cc7a6aa8808d60763e2616413e37be26698768da03be8b981a07ea184c17f27e39c4efcd9fad19a70aca26af7ca53c4dd5a', 0xdf57464a49667ed45205600668b886b8, '703369', '2022-06-15 16:56:32', NULL, NULL, NULL, NULL, 1, 1, 1),
(9, 'admin', 'admin@gmail.com', '5e27e37f6c4b72232294bae32b563fb102188e916c853d99d0c91afa6092e0236d49d8b1f98f071e0beb8455b0a9f2f86a374723ac6cb31aeb13935dde172e4d', 0x66b17835f9bef4c2774991170296948a, '698575', '2022-06-15 16:57:38', NULL, NULL, NULL, NULL, 1, 1, 1),
(10, 'kierownik', 'kierownik@gmail.com', '10a1e152290330ea67d4920a0e91564ddb397b1b435d5d67605e3a7a30c91225ac8c4fa226dafb5e0d6e10812ebf2cff5cadab244510cdc37157bae831cf3c6e', 0x596b7cc0d1822c600ba14930d31e8336, '791601', '2022-06-15 16:52:51', NULL, NULL, NULL, NULL, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `id` bigint(20) NOT NULL,
  `id_user` int(11) NOT NULL,
  `time` timestamp NULL DEFAULT NULL,
  `action_taken` varchar(255) NOT NULL,
  `table_affected` varchar(255) DEFAULT NULL,
  `row_number` int(11) DEFAULT NULL,
  `previous_data` text DEFAULT NULL,
  `new_data` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`id`, `id_user`, `time`, `action_taken`, `table_affected`, `row_number`, `previous_data`, `new_data`) VALUES
(1, 5, NULL, 'edit', 'message', 24, '24|aaaaaaa|public|                                               aaaaa    4                           ', '1'),
(2, 5, NULL, 'edit', 'message', 14, '14|aab|public|                                                                    ddd                                                                     ', '1'),
(3, 5, NULL, 'edit', 'message', 21, '21|bvvvv|public|          vvv      ', '21|bvvvv|public|'),
(4, 5, NULL, 'edit', 'message', 21, '21|bvvvv|public|                          vv7v                      ', '21|bvvvv|public|                          vv7v                      '),
(5, 5, NULL, 'edit', 'message', 21, '21|bvvvv|public|                          vv7v                      ', '21|bvvvv|public|edycja                                     '),
(6, 5, NULL, 'delete', 'message', 24, '24|aaaaaaa|public|                                                               aaaaa    5                                          |0', '24|aaaaaaa|public|                                                               aaaaa    5                                          |0'),
(7, 5, NULL, 'delete', 'message', 21, '21|bvvvv|public|edycja                                     |0', '21|bvvvv|public|edycja                                     |1'),
(8, 5, '2022-05-23 09:07:50', 'edit', 'message', 14, '14|aab|public|                                                                                    ddd5                                                                                     ', '14|aab|public|                                                                                                    ddd56                                                                                                     '),
(9, 5, '2022-05-23 09:25:06', 'add', 'message', 28, '', '28|vb|public|          b      |0|5'),
(10, 5, '2022-05-29 18:17:15', 'edit', 'message', 29, '29|vb|public|          b      ', '29|nowy|public|nowa treść                '),
(11, 5, '2022-05-29 18:18:18', 'undo changes', 'message', 29, '29|nowy|public|nowa treść                ', '29|vb|public|          b      '),
(12, 5, '2022-05-29 18:18:42', 'undo changes', 'message', 29, '29|nowy|public|nowa treść                ', '29|vb|public|          b      '),
(13, 5, '2022-05-29 18:19:56', 'add', 'message', 30, '', '30|stary tytuł|public|stara wiadomość                |0|5'),
(14, 5, '2022-05-29 18:20:09', 'edit', 'message', 30, '30|stary tytuł|public|stara wiadomość                ', '30|nowy tytuł|public|nowa wiadomość                                '),
(23, 5, '2022-05-29 19:28:06', 'undo changes', 'message', 30, '30|nowy tytuł|public|nowa wiadomość                                ', '30|stary tytuł|public|stara wiadomość                '),
(24, 5, '2022-05-29 20:39:00', 'undo changes', 'message', 21, '21|bvvvv|public|edycja                                     |1', '21|bvvvv|public|edycja                                     |0'),
(25, 5, '2022-05-29 20:39:15', 'delete', 'message', 21, '21|bvvvv|public|edycja                                     |0', '21|bvvvv|public|edycja                                     |1'),
(26, 5, '2022-05-29 20:39:21', 'undo changes', 'message', 21, '21|bvvvv|public|edycja                                     |1', '21|bvvvv|public|edycja                                     |0'),
(27, 5, '2022-05-29 20:40:44', 'undo changes', 'message', 30, '30|stary tytuł|public|stara wiadomość                |0|5', ''),
(28, 5, '2022-05-30 06:49:11', 'edit', 'message', 29, '29|nowy|public|nowa treść                |0', '29|nowy|public|stara treść                                |0'),
(29, 5, '2022-05-30 06:49:16', 'undo changes', 'message', 29, '29|nowy|public|stara treść                                |0', '29|nowy|public|nowa treść                |0'),
(30, 5, '2022-05-30 07:12:36', 'edit', 'message', 29, '29|nowy|public|nowa treść                |0', '29|nowy|public|stara treść                                |0'),
(31, 5, '2022-05-30 07:12:41', 'undo changes', 'message', 29, '29|nowy|public|stara treść                                |0', '29|nowy|public|nowa treść                |0'),
(32, 5, '2022-05-30 07:13:02', 'undo changes', 'message', 29, '29|nowy|public|nowa treść                |0', '29|nowy|public|stara treść                                |0'),
(33, 5, '2022-05-30 07:13:10', 'delete', 'message', 29, '29|nowy|public|stara treść                                |0', '29|nowy|public|stara treść                                |1'),
(34, 5, '2022-05-30 07:13:14', 'undo changes', 'message', 29, '29|nowy|public|stara treść                                |1', '29|nowy|public|stara treść                                |0'),
(35, 5, '2022-05-30 07:13:33', 'add', 'message', 31, '31|Dodana wiadomość|public|dodana treść      |1|5', '31|Dodana wiadomość|public|dodana treść      |0|5'),
(36, 5, '2022-05-30 07:13:40', 'undo changes', 'message', 31, '31|Dodana wiadomość|public|dodana treść      |0|5', '31|Dodana wiadomość|public|dodana treść      |1|5'),
(37, 5, '2022-05-30 07:13:45', 'undo changes', 'message', 31, '31|Dodana wiadomość|public|dodana treść      |1|5', '31|Dodana wiadomość|public|dodana treść      |0|5'),
(38, 5, '2022-05-30 07:20:18', 'show data', 'messages', 1, '', ''),
(39, 5, '2022-05-30 07:20:31', 'show data', 'messages', 1, '', ''),
(40, 5, '2022-05-30 07:21:12', 'show data', 'messages', 1, '', ''),
(41, 5, '2022-05-30 07:31:08', 'show data', 'messages', 0, '', 'messages = '),
(42, 5, '2022-05-30 07:35:16', 'show data', 'messages', 0, '', 'messages  14,17,18,21,24,25,26,27,28,29,31,'),
(43, 5, '2022-05-30 07:35:18', 'show data', 'messages', 0, '', 'messages  14,17,18,21,24,25,26,27,28,29,31,'),
(44, 5, '2022-05-30 07:35:19', 'show data', 'messages', 0, '', 'messages  14,21,24,25,26,27,28,29,31,'),
(45, 5, '2022-05-30 07:35:31', 'show data', 'messages', 0, '', 'messages  14,17,18,21,24,25,26,27,28,29,31,'),
(46, 5, '2022-05-30 07:35:35', 'show data', 'messages', 0, '', 'messages  14,21,24,25,26,27,28,29,31,'),
(47, 5, '2022-05-30 07:41:56', 'show data', 'messages', 0, '', 'messages  14,17,18,21,24,25,26,27,28,29,31,');

-- --------------------------------------------------------

--
-- Table structure for table `user_login`
--

CREATE TABLE `user_login` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_address` bigint(20) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `computer` varchar(255) DEFAULT NULL,
  `session` varchar(255) DEFAULT NULL,
  `correct` tinyint(4) NOT NULL,
  `log_out` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_login`
--

INSERT INTO `user_login` (`id`, `id_user`, `id_address`, `time`, `computer`, `session`, `correct`, `log_out`) VALUES
(1, 5, 1, '2022-05-22 16:41:07', 'undetected', NULL, 1, NULL),
(2, 5, 1, '2022-05-22 17:32:19', 'undetected', NULL, 1, NULL),
(3, 5, 2, '2022-05-23 08:34:51', 'undetected', NULL, 1, NULL),
(4, 5, 1, '2022-05-23 08:35:03', 'undetected', NULL, 1, NULL),
(5, 5, 1, '2022-05-23 08:35:49', 'undetected', NULL, 1, NULL),
(6, 5, 1, '2022-05-23 08:35:57', 'undetected', NULL, 1, NULL),
(7, 5, 1, '2022-05-23 08:39:06', 'PC', 'Array', 0, NULL),
(8, 5, 1, '2022-05-23 08:40:12', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 0, NULL),
(9, 5, 1, '2022-05-23 08:44:03', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 0, 0),
(10, 5, 1, '2022-05-23 08:44:15', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 1),
(11, 6, 1, '2022-05-23 08:46:18', 'PC', '', 1, 1),
(12, 5, 1, '2022-05-23 08:47:20', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 0),
(13, 5, 1, '2022-05-23 08:48:36', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 0),
(14, 6, 1, '2022-05-23 08:49:59', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 1),
(15, 6, 1, '2022-05-23 08:50:23', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 1),
(16, 6, 1, '2022-05-23 08:56:32', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 0),
(17, 6, 1, '2022-05-23 08:57:02', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 1),
(18, 5, 1, '2022-05-23 08:58:41', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 0),
(19, 5, 1, '2022-05-23 09:02:49', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 1),
(20, 5, 1, '2022-05-23 09:02:50', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 0),
(21, 5, 1, '2022-05-23 09:05:28', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 1),
(22, 5, 1, '2022-05-23 09:05:33', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 0),
(23, 5, 1, '2022-05-23 09:19:02', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 1),
(24, 5, 1, '2022-05-23 09:19:04', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 0),
(25, 5, 1, '2022-05-23 09:22:48', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 1),
(26, 5, 1, '2022-05-23 09:22:49', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 0),
(27, 5, 1, '2022-05-23 12:59:07', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 1),
(28, 5, 1, '2022-05-23 12:59:09', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 0),
(29, 5, 1, '2022-05-23 12:59:27', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 1),
(30, 5, 1, '2022-05-23 13:24:09', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 0),
(31, 5, 1, '2022-05-23 13:33:38', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 1),
(32, 5, 1, '2022-05-23 13:33:39', 'PC', 'dmfb9tsric1eveb7ehphltuqiv', 1, 0),
(33, 5, 1, '2022-05-29 17:00:14', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(34, 5, 1, '2022-05-29 17:14:41', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 1),
(35, 5, 1, '2022-05-29 17:14:42', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(36, 5, 1, '2022-05-29 17:16:30', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 1),
(37, 5, 1, '2022-05-29 17:16:31', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(38, 5, 1, '2022-05-29 17:19:21', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 1),
(39, 5, 1, '2022-05-29 17:19:22', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(40, 5, 1, '2022-05-29 17:35:15', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 1),
(41, 6, 1, '2022-05-29 17:36:10', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(42, 5, 1, '2022-05-29 17:36:15', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(43, 5, 1, '2022-05-29 17:41:05', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 1),
(44, 5, 1, '2022-05-29 17:41:11', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(45, 5, 1, '2022-05-29 17:42:01', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 1),
(46, 5, 1, '2022-05-29 17:42:04', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(47, 5, 1, '2022-05-29 18:12:26', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 1),
(48, 5, 1, '2022-05-29 18:12:27', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(49, 5, 1, '2022-05-29 18:16:40', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 1),
(50, 5, 1, '2022-05-29 18:16:41', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(51, 5, 1, '2022-05-29 18:16:53', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 1),
(52, 5, 1, '2022-05-29 18:17:03', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(53, 5, 1, '2022-05-29 18:22:30', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 1),
(54, 5, 1, '2022-05-29 18:22:31', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(55, 5, 1, '2022-05-29 18:24:50', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 1),
(56, 5, 1, '2022-05-29 18:24:51', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(57, 5, 1, '2022-05-29 18:27:02', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 1),
(58, 5, 1, '2022-05-29 18:27:03', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(59, 5, 1, '2022-05-29 18:28:16', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 1),
(60, 5, 1, '2022-05-29 18:28:18', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(61, 5, 1, '2022-05-29 18:29:22', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 1),
(62, 5, 1, '2022-05-29 18:29:23', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(63, 5, 1, '2022-05-29 18:31:40', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 1),
(64, 5, 1, '2022-05-29 18:31:42', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(65, 5, 1, '2022-05-29 18:33:20', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 1),
(66, 5, 1, '2022-05-29 18:33:22', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(67, 5, 1, '2022-05-29 18:37:22', 'PC', '7igg9mkn2ivkpk386k3s5atn88', 1, 0),
(68, 5, 1, '2022-05-30 06:41:07', 'PC', 'p9glotu5coajadbomaqq13jecd', 1, 0),
(69, 5, 1, '2022-05-30 06:48:55', 'PC', 'p9glotu5coajadbomaqq13jecd', 1, 0),
(70, 5, 1, '2022-05-30 07:30:18', 'PC', 'p9glotu5coajadbomaqq13jecd', 1, 0),
(71, 5, 1, '2022-05-30 07:40:43', 'PC', 'p9glotu5coajadbomaqq13jecd', 1, 1),
(72, 5, 1, '2022-05-30 07:41:28', 'PC', 'p9glotu5coajadbomaqq13jecd', 1, 0),
(73, 5, 1, '2022-05-30 07:41:30', 'PC', 'p9glotu5coajadbomaqq13jecd', 1, 1),
(74, 5, 1, '2022-05-30 07:41:31', 'PC', 'p9glotu5coajadbomaqq13jecd', 1, 0),
(75, 5, 1, '2022-05-30 07:41:38', 'PC', 'p9glotu5coajadbomaqq13jecd', 1, 1),
(76, 5, 1, '2022-05-30 07:41:48', 'PC', 'p9glotu5coajadbomaqq13jecd', 1, 0),
(77, 5, 1, '2022-05-30 11:29:05', 'PC', 'p9glotu5coajadbomaqq13jecd', 1, 1),
(78, 5, 1, '2022-05-30 11:29:10', 'PC', 'p9glotu5coajadbomaqq13jecd', 1, 0),
(79, 5, 1, '2022-05-30 11:38:47', 'PC', 'p9glotu5coajadbomaqq13jecd', 0, 0),
(80, 5, 1, '2022-05-30 11:39:08', 'PC', 'p9glotu5coajadbomaqq13jecd', 0, 0),
(81, 5, 1, '2022-05-30 11:40:28', 'PC', 'p9glotu5coajadbomaqq13jecd', 1, 0),
(82, 5, 1, '2022-06-01 18:40:18', 'PC', 'o90ttemci9uf9lmvmenrie5696', 1, 0),
(83, 5, 1, '2022-06-01 18:42:23', 'PC', 'o90ttemci9uf9lmvmenrie5696', 1, 1),
(84, 5, 1, '2022-06-01 18:42:28', 'PC', 'o90ttemci9uf9lmvmenrie5696', 1, 0),
(85, 5, 1, '2022-06-01 18:50:05', 'PC', 'o90ttemci9uf9lmvmenrie5696', 1, 1),
(86, 5, 1, '2022-06-01 18:50:09', 'PC', 'o90ttemci9uf9lmvmenrie5696', 1, 0),
(87, 5, 1, '2022-06-01 18:50:42', 'PC', 'o90ttemci9uf9lmvmenrie5696', 0, 0),
(88, 5, 1, '2022-06-01 18:51:40', 'PC', 'o90ttemci9uf9lmvmenrie5696', 1, 0),
(89, 5, 1, '2022-06-01 19:22:23', 'PC', 'o90ttemci9uf9lmvmenrie5696', 1, 1),
(90, -1, 1, '2022-06-01 19:23:30', 'PC', 'o90ttemci9uf9lmvmenrie5696', 0, 0),
(91, 5, 1, '2022-06-01 19:24:22', 'PC', 'o90ttemci9uf9lmvmenrie5696', 1, 0),
(92, 5, 1, '2022-06-09 07:08:36', 'PC', 'qcdpo97f3o3f8ii6dq899raivs', 1, 0),
(93, 5, 1, '2022-06-13 10:36:13', 'PC', '288jm3ve6169d8u51g343sooji', 1, 0),
(94, 5, 1, '2022-06-13 10:37:13', 'PC', '288jm3ve6169d8u51g343sooji', 1, 1),
(95, 5, 1, '2022-06-13 10:37:17', 'PC', '288jm3ve6169d8u51g343sooji', 1, 0),
(96, 5, 1, '2022-06-13 10:37:23', 'PC', '288jm3ve6169d8u51g343sooji', 1, 1),
(97, 5, 1, '2022-06-13 10:38:33', 'PC', '288jm3ve6169d8u51g343sooji', 1, 0),
(98, 5, 1, '2022-06-13 10:42:26', 'PC', '288jm3ve6169d8u51g343sooji', 1, 1),
(99, 5, 1, '2022-06-13 10:42:28', 'PC', '288jm3ve6169d8u51g343sooji', 1, 0),
(100, 5, 1, '2022-06-13 12:35:12', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(101, 5, 1, '2022-06-13 12:35:51', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(102, 5, 1, '2022-06-13 12:35:57', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(103, 5, 1, '2022-06-13 12:36:19', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(104, 5, 1, '2022-06-13 12:36:36', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(105, 5, 1, '2022-06-13 12:37:06', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(106, 5, 1, '2022-06-13 12:37:10', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(107, 5, 1, '2022-06-13 12:37:46', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(108, 5, 1, '2022-06-13 12:37:51', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(109, 5, 1, '2022-06-13 12:38:21', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(110, 5, 1, '2022-06-13 12:38:23', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(111, 5, 1, '2022-06-13 12:38:47', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(112, 5, 1, '2022-06-13 12:40:17', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(113, 5, 1, '2022-06-13 12:40:49', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(114, 5, 1, '2022-06-13 12:40:56', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(115, 5, 1, '2022-06-13 12:41:44', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(116, 5, 1, '2022-06-13 12:41:58', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(117, 5, 1, '2022-06-13 12:42:15', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(118, 5, 1, '2022-06-13 12:42:18', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(119, 5, 1, '2022-06-13 12:42:44', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(120, 5, 1, '2022-06-13 12:43:10', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(121, 5, 1, '2022-06-13 13:42:23', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(122, 5, 1, '2022-06-13 13:42:55', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(123, 5, 1, '2022-06-13 13:44:16', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(124, 5, 1, '2022-06-13 13:44:30', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(125, 5, 1, '2022-06-13 13:44:32', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(126, 5, 1, '2022-06-13 13:44:59', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(127, 5, 1, '2022-06-13 13:46:45', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(128, 5, 1, '2022-06-13 13:46:48', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(129, 5, 1, '2022-06-13 13:48:53', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(130, 5, 1, '2022-06-13 13:48:56', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(131, 5, 1, '2022-06-13 14:12:31', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(132, 5, 1, '2022-06-13 14:12:33', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(133, 5, 1, '2022-06-13 14:19:55', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(134, 5, 1, '2022-06-13 14:19:58', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(135, 5, 1, '2022-06-13 14:49:16', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(136, 6, 1, '2022-06-13 14:49:39', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(137, 6, 1, '2022-06-13 14:49:54', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(138, 5, 1, '2022-06-13 14:49:58', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(139, 5, 1, '2022-06-13 14:50:28', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(140, 5, 1, '2022-06-13 15:01:36', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(141, 5, 1, '2022-06-13 15:01:39', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(142, 5, 1, '2022-06-13 15:08:19', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(143, 5, 1, '2022-06-13 15:14:25', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(144, 6, 1, '2022-06-13 15:14:37', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(145, 6, 1, '2022-06-13 15:14:51', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(146, 6, 1, '2022-06-13 15:14:54', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(147, 5, 1, '2022-06-13 15:15:53', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(148, 5, 1, '2022-06-13 15:17:07', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 1),
(149, 6, 1, '2022-06-13 15:17:10', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(150, 5, 1, '2022-06-13 15:18:03', 'PC', '73ud9i40j8ru8l6k87lsbp3c0k', 1, 0),
(151, 6, 1, '2022-06-14 07:30:26', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 0),
(152, 5, 1, '2022-06-14 07:31:18', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 0),
(153, 5, 1, '2022-06-14 07:44:18', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 1),
(154, 5, 1, '2022-06-14 07:44:22', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 0),
(155, 5, 1, '2022-06-14 07:44:31', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 0),
(156, 5, 1, '2022-06-14 07:44:36', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 1),
(157, 5, 1, '2022-06-14 07:44:43', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 0),
(158, 5, 1, '2022-06-14 07:45:46', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 1),
(159, 6, 1, '2022-06-14 07:45:49', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 0),
(160, 6, 1, '2022-06-14 07:48:28', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 0),
(161, 6, 1, '2022-06-14 07:48:43', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 0),
(162, 6, 1, '2022-06-14 08:18:13', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 0),
(163, 6, 1, '2022-06-14 09:16:07', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 1),
(164, 5, 1, '2022-06-14 09:16:10', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 0),
(165, 5, 1, '2022-06-14 11:44:03', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 1),
(166, 6, 1, '2022-06-14 11:44:07', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 0),
(167, 6, 1, '2022-06-14 12:08:53', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 1),
(168, 5, 1, '2022-06-14 12:08:57', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 0),
(169, 5, 1, '2022-06-14 12:09:01', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 1),
(170, 6, 1, '2022-06-14 12:09:04', 'PC', 'upigj42fvv8jvea33pooliu3ba', 1, 0),
(171, 6, 1, '2022-06-14 18:53:05', 'PC', 'pmljc428tcoov4jras4deqev6v', 1, 0),
(172, 6, 1, '2022-06-14 18:56:36', 'PC', 'pmljc428tcoov4jras4deqev6v', 1, 0),
(173, 6, 1, '2022-06-14 19:10:37', 'PC', 'pmljc428tcoov4jras4deqev6v', 1, 0),
(174, 6, 1, '2022-06-14 19:12:45', 'PC', 'pmljc428tcoov4jras4deqev6v', 1, 1),
(175, 5, 1, '2022-06-14 19:12:48', 'PC', 'pmljc428tcoov4jras4deqev6v', 1, 0),
(176, 5, 1, '2022-06-14 19:13:22', 'PC', 'pmljc428tcoov4jras4deqev6v', 1, 1),
(177, -1, 1, '2022-06-14 19:13:26', 'PC', 'pmljc428tcoov4jras4deqev6v', 0, 0),
(178, 5, 1, '2022-06-14 19:13:45', 'PC', 'pmljc428tcoov4jras4deqev6v', 1, 0),
(179, 5, 1, '2022-06-14 19:14:01', 'PC', 'pmljc428tcoov4jras4deqev6v', 1, 1),
(180, 6, 1, '2022-06-14 19:14:04', 'PC', 'pmljc428tcoov4jras4deqev6v', 1, 0),
(181, 6, 1, '2022-06-14 19:29:44', 'PC', 'pmljc428tcoov4jras4deqev6v', 1, 1),
(182, 6, 1, '2022-06-14 19:29:51', 'PC', 'pmljc428tcoov4jras4deqev6v', 1, 0),
(183, 6, 1, '2022-06-14 19:30:02', 'PC', 'pmljc428tcoov4jras4deqev6v', 1, 1),
(184, 5, 1, '2022-06-14 19:30:04', 'PC', 'pmljc428tcoov4jras4deqev6v', 1, 0),
(185, 5, 1, '2022-06-14 19:39:50', 'PC', 'pmljc428tcoov4jras4deqev6v', 1, 1),
(186, 5, 1, '2022-06-14 19:39:57', 'PC', 'pmljc428tcoov4jras4deqev6v', 1, 0),
(187, 5, 1, '2022-06-15 07:16:29', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(188, 5, 1, '2022-06-15 07:17:10', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(189, 6, 1, '2022-06-15 07:17:14', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(190, 6, 1, '2022-06-15 07:21:34', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(191, 6, 1, '2022-06-15 07:21:45', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(192, 5, 1, '2022-06-15 07:21:48', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(193, 5, 1, '2022-06-15 09:32:19', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(194, 5, 1, '2022-06-15 09:32:25', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(195, 5, 1, '2022-06-15 09:36:17', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(196, 5, 1, '2022-06-15 09:36:21', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(197, 5, 1, '2022-06-15 10:52:25', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(198, 5, 1, '2022-06-15 10:52:28', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(199, 5, 1, '2022-06-15 11:05:08', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(200, 5, 1, '2022-06-15 11:07:31', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(201, 5, 1, '2022-06-15 11:07:38', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(202, 6, 1, '2022-06-15 11:07:42', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(203, 6, 1, '2022-06-15 11:07:44', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(204, 8, 1, '2022-06-15 11:07:49', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(205, 8, 1, '2022-06-15 11:09:33', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(206, 9, 1, '2022-06-15 11:09:37', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(207, 9, 1, '2022-06-15 11:10:43', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(208, 5, 1, '2022-06-15 11:10:46', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(209, 5, 1, '2022-06-15 11:12:01', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(210, 9, 1, '2022-06-15 11:12:04', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(211, 9, 1, '2022-06-15 11:21:03', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(212, 9, 1, '2022-06-15 11:21:06', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(213, 9, 1, '2022-06-15 11:25:44', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(214, 9, 1, '2022-06-15 11:25:48', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(215, 9, 1, '2022-06-15 11:26:41', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(216, 9, 1, '2022-06-15 11:26:44', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(217, 9, 1, '2022-06-15 11:27:14', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(218, 9, 1, '2022-06-15 11:27:17', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(219, 9, 1, '2022-06-15 11:28:22', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(220, 9, 1, '2022-06-15 11:28:26', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(221, 9, 1, '2022-06-15 11:32:58', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(222, 9, 1, '2022-06-15 11:33:02', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(223, 9, 1, '2022-06-15 11:33:41', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(224, 8, 1, '2022-06-15 11:33:45', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(225, 8, 1, '2022-06-15 11:33:59', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(226, 9, 1, '2022-06-15 11:34:03', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(227, 9, 1, '2022-06-15 14:28:32', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(228, 8, 1, '2022-06-15 14:28:37', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(229, 8, 1, '2022-06-15 14:40:09', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(230, 8, 1, '2022-06-15 14:46:53', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(231, 8, 1, '2022-06-15 14:49:32', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(232, 8, 1, '2022-06-15 14:49:37', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(233, 8, 1, '2022-06-15 14:49:41', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(234, 8, 1, '2022-06-15 14:50:15', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(235, 8, 1, '2022-06-15 15:01:36', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(236, 9, 1, '2022-06-15 15:10:45', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(237, 9, 1, '2022-06-15 15:17:19', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(238, 8, 1, '2022-06-15 15:17:24', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(239, 8, 1, '2022-06-15 15:19:05', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(240, 9, 1, '2022-06-15 15:19:10', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(241, 9, 1, '2022-06-15 15:19:29', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 1),
(242, 10, 1, '2022-06-15 15:19:50', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(243, 10, 1, '2022-06-15 15:25:20', 'PC', 'oobmfsukr8heanrnvje5h076b5', 1, 0),
(244, 8, 1, '2022-06-15 15:32:10', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 0),
(245, 8, 1, '2022-06-15 15:32:33', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 1),
(246, 9, 1, '2022-06-15 15:32:36', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 0),
(247, 9, 1, '2022-06-15 15:32:46', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 1),
(248, 10, 1, '2022-06-15 15:32:51', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 0),
(249, 10, 1, '2022-06-15 15:42:45', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 1),
(250, 9, 1, '2022-06-15 16:08:40', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 0),
(251, 9, 1, '2022-06-15 16:23:57', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 1),
(252, 8, 1, '2022-06-15 16:39:25', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 0),
(253, 8, 1, '2022-06-15 16:40:49', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 1),
(254, 8, 1, '2022-06-15 16:41:52', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 0),
(255, 8, 1, '2022-06-15 16:42:38', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 0),
(256, 8, 1, '2022-06-15 16:43:31', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 1),
(257, 9, 1, '2022-06-15 16:43:36', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 0),
(258, 9, 1, '2022-06-15 16:47:46', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 1),
(259, 10, 1, '2022-06-15 16:47:51', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 0),
(260, 10, 1, '2022-06-15 16:51:29', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 1),
(261, 8, 1, '2022-06-15 16:51:32', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 0),
(262, 8, 1, '2022-06-15 16:52:34', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 1),
(263, 9, 1, '2022-06-15 16:52:38', 'PC', 'c3etjd8i5mnp27oedf8a7ks6br', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_privilege`
--

CREATE TABLE `user_privilege` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_privilege` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_privilege`
--

INSERT INTO `user_privilege` (`id`, `id_user`, `id_privilege`) VALUES
(113, 5, 106),
(117, 5, 107),
(119, 9, 107),
(120, 9, 105),
(121, 9, 106),
(125, 10, 109),
(126, 10, 107),
(129, 8, 106),
(130, 8, 109);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `id_role` int(11) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `issue_time` date NOT NULL DEFAULT current_timestamp(),
  `expire_time` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `id_role`, `id_user`, `issue_time`, `expire_time`) VALUES
(15, 3, 5, '2022-06-15', NULL),
(17, 18, 10, '2022-06-15', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_parent` (`id_parent`);

--
-- Indexes for table `incorrect_logins`
--
ALTER TABLE `incorrect_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_address` (`id_address`);

--
-- Indexes for table `ip_address`
--
ALTER TABLE `ip_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user_3` (`id_user`);

--
-- Indexes for table `mqtt`
--
ALTER TABLE `mqtt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `privilege`
--
ALTER TABLE `privilege`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`),
  ADD KEY `fk_privilege_privilege1_idx` (`id_parent_privilege`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategoria` (`id_category`);

--
-- Indexes for table `product_transaction`
--
ALTER TABLE `product_transaction`
  ADD PRIMARY KEY (`id_product`,`id_transaction`),
  ADD KEY `id_transaction` (`id_transaction`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name_UNIQUE` (`role_name`);

--
-- Indexes for table `role_privilege`
--
ALTER TABLE `role_privilege`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_role_privilege_privilege1_idx` (`id_privilege`),
  ADD KEY `fk_role_privilege_role1_idx` (`id_role`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cashier` (`id_cashier`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `FKuser674283` (`id_status`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `user_login`
--
ALTER TABLE `user_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`,`id_address`),
  ADD KEY `id_address` (`id_address`);

--
-- Indexes for table `user_privilege`
--
ALTER TABLE `user_privilege`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_privilege_privilege1_idx` (`id_privilege`),
  ADD KEY `fk_user_privilege_user2_idx` (`id_user`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_role_role1_idx` (`id_role`),
  ADD KEY `fk_user_role_user2_idx` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `incorrect_logins`
--
ALTER TABLE `incorrect_logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ip_address`
--
ALTER TABLE `ip_address`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `mqtt`
--
ALTER TABLE `mqtt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `privilege`
--
ALTER TABLE `privilege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `role_privilege`
--
ALTER TABLE `role_privilege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `user_login`
--
ALTER TABLE `user_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=264;

--
-- AUTO_INCREMENT for table `user_privilege`
--
ALTER TABLE `user_privilege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`id_parent`) REFERENCES `category` (`id`);

--
-- Constraints for table `incorrect_logins`
--
ALTER TABLE `incorrect_logins`
  ADD CONSTRAINT `incorrect_logins_ibfk_1` FOREIGN KEY (`id_address`) REFERENCES `ip_address` (`id`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`);

--
-- Constraints for table `privilege`
--
ALTER TABLE `privilege`
  ADD CONSTRAINT `fk_privilege_privilege1` FOREIGN KEY (`id_parent_privilege`) REFERENCES `privilege` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `category` (`id`);

--
-- Constraints for table `product_transaction`
--
ALTER TABLE `product_transaction`
  ADD CONSTRAINT `product_transaction_ibfk_1` FOREIGN KEY (`id_transaction`) REFERENCES `transaction` (`id`),
  ADD CONSTRAINT `product_transaction_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `product` (`id`);

--
-- Constraints for table `role_privilege`
--
ALTER TABLE `role_privilege`
  ADD CONSTRAINT `fk_role_privilege_privilege1` FOREIGN KEY (`id_privilege`) REFERENCES `privilege` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_role_privilege_role1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`id_cashier`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_login`
--
ALTER TABLE `user_login`
  ADD CONSTRAINT `user_login_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `user_login_ibfk_2` FOREIGN KEY (`id_address`) REFERENCES `ip_address` (`id`);

--
-- Constraints for table `user_privilege`
--
ALTER TABLE `user_privilege`
  ADD CONSTRAINT `fk_user_privilege_privilege1` FOREIGN KEY (`id_privilege`) REFERENCES `privilege` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_privilege_user2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `fk_user_role_role1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_role_user2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
