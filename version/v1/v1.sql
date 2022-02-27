-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         5.7.33 - MySQL Community Server (GPL)
-- SO del servidor:              Win64
-- HeidiSQL Versión:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando estructura para tabla app.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla app.failed_jobs: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

-- Volcando estructura para tabla app.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla app.migrations: ~7 rows (aproximadamente)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
	(3, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
	(4, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
	(5, '2016_06_01_000004_create_oauth_clients_table', 1),
	(6, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
	(7, '2019_08_19_000000_create_failed_jobs_table', 1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Volcando estructura para tabla app.oauth_access_tokens
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla app.oauth_access_tokens: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
	('0efee866884660e78bc1b54617bbf14e9775ee8cadb0914ca16f420c70de6102ad175acc5cf8602f', 64, 1, 'Personal Access Token', '[]', 0, '2022-01-29 22:01:55', '2022-01-29 22:01:55', '2022-02-05 22:01:55'),
	('13df135a8719c1a6cc6ebc9e33283ab66a5ae13c201e5b9e8dd7d4badbac10e0074b924306c00434', 38, 1, 'Personal Access Token', '[]', 0, '2022-01-16 02:36:42', '2022-01-16 02:36:42', '2022-01-23 02:36:42'),
	('5d08b68f8d053b727dd49a782e4f1bd1bb5f27eeb6b9cfe405e379dc34273f00d131293919d7a4eb', 50, 1, 'Personal Access Token', '[]', 0, '2022-01-28 01:18:26', '2022-01-28 01:18:26', '2022-02-04 01:18:26'),
	('9004c307fd827905169154eada5333b91dfc878e07e987d4e0eaf188c1e469b861729a15b528697c', 49, 1, 'Personal Access Token', '[]', 0, '2022-01-27 23:56:39', '2022-01-27 23:56:39', '2022-02-03 23:56:39'),
	('dab53140f162da38b96fc9a850bd85fa45a02ef40f820d10bb275883730020a472fa4ed889291f7c', 62, 1, 'Personal Access Token', '[]', 0, '2022-01-28 04:00:49', '2022-01-28 04:00:49', '2022-02-04 04:00:49');
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;

-- Volcando estructura para tabla app.oauth_auth_codes
CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla app.oauth_auth_codes: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;

-- Volcando estructura para tabla app.oauth_clients
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla app.oauth_clients: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
	(1, NULL, 'Laravel Personal Access Client', 'Ed9hnysJu2XdgrNshduDoxkBIq6sLzMzWmC6pG8H', NULL, 'http://localhost', 1, 0, 0, '2022-01-11 22:45:03', '2022-01-11 22:45:03'),
	(2, NULL, 'Laravel Password Grant Client', 'IL3m95JINuwXj1vinCvMHNVxRMPLOsZlnuYTi7om', 'users', 'http://localhost', 0, 1, 0, '2022-01-11 22:45:03', '2022-01-11 22:45:03');
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;

-- Volcando estructura para tabla app.oauth_personal_access_clients
CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla app.oauth_personal_access_clients: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `oauth_personal_access_clients` DISABLE KEYS */;
INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
	(1, 1, '2022-01-11 22:45:03', '2022-01-11 22:45:03');
/*!40000 ALTER TABLE `oauth_personal_access_clients` ENABLE KEYS */;

-- Volcando estructura para tabla app.oauth_refresh_tokens
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla app.oauth_refresh_tokens: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;

-- Volcando estructura para tabla app.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `driver_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `client_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `other_user_id` bigint(20) unsigned DEFAULT NULL,
  `transaction` enum('ADD','DISCOUNT','RECHARGE','TRANSFER','RETURN') NOT NULL DEFAULT 'ADD',
  `amount` float(20,2) unsigned DEFAULT NULL,
  `invoice` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice` (`invoice`),
  KEY `other_user_id` (`other_user_id`),
  KEY `driver_id` (`driver_id`),
  KEY `user_id` (`client_id`) USING BTREE,
  CONSTRAINT `FK1_transactios_user_driver_users` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK2_transactios_user_client_users` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK3_transactios_other_user_driver_users` FOREIGN KEY (`other_user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla app.transactions: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` (`id`, `driver_id`, `client_id`, `other_user_id`, `transaction`, `amount`, `invoice`, `created_at`, `updated_at`) VALUES
	(1, 64, 63, NULL, 'DISCOUNT', 1.00, '349704321213', '2022-01-29 22:57:23', '2022-01-29 22:57:23'),
	(2, 64, 63, NULL, 'DISCOUNT', 1.00, '349966574816', '2022-01-29 23:41:05', '2022-01-29 23:41:05'),
	(3, 64, 63, NULL, 'DISCOUNT', 2.43, '349968941881', '2022-01-29 23:41:29', '2022-01-29 23:41:29');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;

-- Volcando estructura para tabla app.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id_card` enum('V','E','P','J','G') COLLATE utf8mb4_unicode_ci DEFAULT 'V',
  `id_card` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_image` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_user` int(11) unsigned NOT NULL DEFAULT '1',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('FEMALE','MALE','NO ESPECIFICO') COLLATE utf8mb4_unicode_ci DEFAULT 'NO ESPECIFICO',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idShow` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `idShow` (`idShow`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla app.users: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `full_name`, `type_id_card`, `id_card`, `profile_image`, `type_user`, `password`, `gender`, `email`, `idShow`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
	(63, 'Dionelys Orea', 'V', '26918394', NULL, 1, '$2y$10$Rj1Q.QgVscfbYttKHDUdg.dSFOrd5ljjZVJgQ/WbSnpSj8okvr10S', 'NO ESPECIFICO', 'dionelys.orea@gmail.com', 'c753067b750a2ab114d25dc722b95795', NULL, NULL, '2022-01-29 21:58:57', '2022-01-29 21:58:57'),
	(64, 'Albert Charmelo', 'V', '27168802', NULL, 1, '$2y$10$771Q.XlHqiPFKnVaBJWKT.NPVQ9UZzoaDqFeLqIb56hbKrw5MVwgu', 'NO ESPECIFICO', 'albertcharmelocontacto@gmail.com', '658e2bcd017cb85711c7852031e7f684', NULL, NULL, '2022-01-29 22:00:23', '2022-01-29 22:00:23');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Volcando estructura para tabla app.users_qr
CREATE TABLE IF NOT EXISTS `users_qr` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` bigint(100) unsigned NOT NULL,
  `qr_name` varchar(255) DEFAULT NULL,
  `qr_image` varchar(255) NOT NULL DEFAULT '0',
  `qr_idShow` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `qr_idGoogle` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `qr_idShow` (`qr_idShow`),
  UNIQUE KEY `qr_idGoogle` (`qr_idGoogle`),
  UNIQUE KEY `qr_name` (`qr_name`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `FK_users_qr_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 COMMENT='Qr''s asociado a los usuarios creados en la app, de los cuales se descuentan los creditos';

-- Volcando datos para la tabla app.users_qr: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `users_qr` DISABLE KEYS */;
INSERT INTO `users_qr` (`id`, `users_id`, `qr_name`, `qr_image`, `qr_idShow`, `created_at`, `updated_at`, `qr_idGoogle`) VALUES
	(9, 63, '1c425fced8ecd750f09003f50ecb88b81739f416372993181017f016e9c78f57', '/qr_api_transporte/1c425fced8ecd750f09003f50ecb88b81739f416372993181017f016e9c78f57.png', '141e55cdd220c01d59e7f31600c404d1', '2022-01-29 21:58:57', '2022-01-29 21:58:57', NULL),
	(10, 64, 'd89a1a0ac6bafb4314db77e0299f38851f095184229f52a6856f3c2461d1c9fd', '/qr_api_transporte/d89a1a0ac6bafb4314db77e0299f38851f095184229f52a6856f3c2461d1c9fd.png', '07846c80b336375af65b8c18cc99b87d', '2022-01-29 22:00:23', '2022-01-29 22:00:23', NULL);
/*!40000 ALTER TABLE `users_qr` ENABLE KEYS */;

-- Volcando estructura para tabla app.users_tipo
CREATE TABLE IF NOT EXISTS `users_tipo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `permiso` varchar(10) DEFAULT '0',
  `descripcion` varchar(100) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='tipo de usuario';

-- Volcando datos para la tabla app.users_tipo: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `users_tipo` DISABLE KEYS */;
INSERT INTO `users_tipo` (`id`, `permiso`, `descripcion`, `created_at`, `updated_at`) VALUES
	(1, 'REGULAR', 'Permisos basicos de la app, como el gps y acceso a su qr', '2022-01-15 22:27:03', '2022-01-15 22:27:05'),
	(2, 'CHOFER', 'Permisos basicos, Escaneo de Qr , Liquidación', '2022-01-27 17:50:58', '2022-01-27 17:50:59'),
	(3, 'ADMIN', 'Todos los permisos', '2022-01-27 17:51:25', '2022-01-27 17:51:26');
/*!40000 ALTER TABLE `users_tipo` ENABLE KEYS */;

-- Volcando estructura para tabla app.users_wallet
CREATE TABLE IF NOT EXISTS `users_wallet` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `creditos` float(20,2) NOT NULL DEFAULT '0.00',
  `idShow` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idShow` (`idShow`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `FK_user_wallet_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla app.users_wallet: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `users_wallet` DISABLE KEYS */;
INSERT INTO `users_wallet` (`id`, `user_id`, `creditos`, `idShow`, `created_at`, `updated_at`) VALUES
	(10, 63, 38.57, '0d31827b295e250300b8f59587834f95', '2022-01-29 21:58:57', '2022-01-29 23:41:29'),
	(11, 64, 26.43, '22e1be87701ce7a91702963d41458f3d', '2022-01-29 22:00:23', '2022-01-29 23:41:29');
/*!40000 ALTER TABLE `users_wallet` ENABLE KEYS */;

-- Volcando estructura para disparador app.users_BEFORE_INSERT
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `users_BEFORE_INSERT` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
SET NEW.idShow = MD5(CONCAT(NOW(),RAND()));
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Volcando estructura para disparador app.users_qr_BEFORE_INSERT
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `users_qr_BEFORE_INSERT` BEFORE INSERT ON `users_qr` FOR EACH ROW BEGIN
SET NEW.qr_idShow = MD5(CONCAT(NOW(),RAND()));
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Volcando estructura para disparador app.users_wallet_BEFORE_INSERT
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `users_wallet_BEFORE_INSERT` BEFORE INSERT ON `users_wallet` FOR EACH ROW BEGIN
SET NEW.idShow = MD5(CONCAT(NOW(),RAND()));
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
