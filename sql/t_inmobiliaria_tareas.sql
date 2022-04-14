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

-- Volcando estructura para tabla sistemastw-2.t_inmobiliaria_task
CREATE TABLE IF NOT EXISTS `t_inmobiliaria_task` (
  `idt_inmobiliaria_task` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_task` varchar(255) DEFAULT NULL,
  `tipo_task` enum('CREADA','ASIGNADA','COMPLETADA') DEFAULT 'CREADA',
  `descripcion_task` mediumtext,
  `responsable_id` bigint(20) unsigned NOT NULL,
  `asignado_user_id` bigint(20) unsigned DEFAULT NULL,
  `recordatorio` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idt_inmobiliaria_task`),
  KEY `asignado_user_id` (`asignado_user_id`),
  KEY `responsable_id` (`responsable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1 COMMENT='tareas de los usuarios de la inmobiliaria\r\n';

-- La exportación de datos fue deseleccionada.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
