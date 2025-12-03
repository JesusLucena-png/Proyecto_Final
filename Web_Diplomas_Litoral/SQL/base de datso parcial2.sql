-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para diplomas_litoral
CREATE DATABASE IF NOT EXISTS `diplomas_litoral` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci */;
USE `diplomas_litoral`;

-- Volcando estructura para tabla diplomas_litoral.academic_group
CREATE TABLE IF NOT EXISTS `academic_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_id` int(11) NOT NULL DEFAULT 0,
  `group_name` varchar(255) NOT NULL DEFAULT '0',
  `max_students` int(11) NOT NULL DEFAULT 0,
  `min_students` int(11) NOT NULL DEFAULT 0,
  `start_period` varchar(255) NOT NULL DEFAULT '0',
  `end_period` varchar(255) NOT NULL DEFAULT '0',
  `schedule` varchar(255) NOT NULL DEFAULT '0',
  `status` enum('En Creacion','En Proceso','Finalizado','Cancelado') NOT NULL DEFAULT 'En Creacion',
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `program_id` (`program_id`),
  CONSTRAINT `FK_academic_group_programs` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla diplomas_litoral.academic_group: ~0 rows (aproximadamente)
INSERT INTO `academic_group` (`id`, `program_id`, `group_name`, `max_students`, `min_students`, `start_period`, `end_period`, `schedule`, `status`, `last_update`) VALUES
	(1, 10, 'PW-01-2025', 35, 10, '2025-1', '2025-2', 'Lunes a Viernes', 'En Creacion', '2025-12-03 15:49:03'),
	(2, 1, 'TCE-N-2024-1', 30, 10, '2024-01', '2024-02', 'Noche', 'En Creacion', '2025-12-03 15:49:03');

-- Volcando estructura para tabla diplomas_litoral.diplomas
CREATE TABLE IF NOT EXISTS `diplomas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enrollment_id` int(11) NOT NULL DEFAULT 0,
  `diploma_tammplates_id` int(11) NOT NULL DEFAULT 0,
  `diploma_number_generation` int(11) NOT NULL DEFAULT 0,
  `issue_date` datetime NOT NULL DEFAULT current_timestamp(),
  `certificate_file` varchar(255) NOT NULL DEFAULT '0',
  `status` enum('Pendiente','Expedido','Revocado') NOT NULL DEFAULT 'Pendiente',
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  KEY `Id_matricula` (`enrollment_id`) USING BTREE,
  KEY `diploma_tammplates_id` (`diploma_tammplates_id`),
  CONSTRAINT `FK_diploma_diploma_templates` FOREIGN KEY (`diploma_tammplates_id`) REFERENCES `diploma_templates` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_diploma_enrollments` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla diplomas_litoral.diplomas: ~0 rows (aproximadamente)
INSERT INTO `diplomas` (`id`, `enrollment_id`, `diploma_tammplates_id`, `diploma_number_generation`, `issue_date`, `certificate_file`, `status`, `last_update`) VALUES
	(2, 14, 1, 0, '2025-11-27 17:48:18', '/uploads/diplomas/diploma_14_1764283698.png', 'Expedido', '2025-12-03 15:48:49'),
	(3, 16, 1, 0, '2025-12-02 21:40:07', '/uploads/diplomas/diploma_16_1764729250.png', 'Expedido', '2025-12-03 15:48:49');

-- Volcando estructura para tabla diplomas_litoral.diploma_templates
CREATE TABLE IF NOT EXISTS `diploma_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `template_file` varchar(255) NOT NULL,
  `program_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `program_id` (`program_id`),
  CONSTRAINT `diploma_templates_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Volcando datos para la tabla diplomas_litoral.diploma_templates: ~12 rows (aproximadamente)
INSERT INTO `diploma_templates` (`id`, `name`, `description`, `template_file`, `program_id`, `created_at`, `updated_at`) VALUES
	(1, 'Plantilla Diplomas TCE', 'Plantilla temporal utilizada para todos los programas mientras se diseñan las personalizadas.', 'uploads/diplomas/Tecnico.php', 1, '2025-11-27 15:27:23', '2025-11-27 15:30:56'),
	(6, 'Plantilla Diploma TPP', 'Plantilla temporal para Técn. Profesional de Procesos Administrativos Portuarios', 'uploads/diplomas/Tecnico.php', 2, '2025-11-27 15:30:26', '2025-11-27 15:30:26'),
	(7, 'Plantilla Diploma TLO', 'Plantilla temporal para Técn. Profesional en Operaciones Logísticas', 'uploads/diplomas/Tecnico.php', 3, '2025-11-27 15:30:26', '2025-11-27 15:30:26'),
	(8, 'Plantilla Diploma SST', 'Plantilla temporal para Técn. Profesional en Seguridad y Salud en el Trabajo', 'uploads/diplomas/Tecnico.php', 4, '2025-11-27 15:30:26', '2025-11-27 15:30:26'),
	(9, 'Plantilla Diploma TCF', 'Plantilla temporal para Técn. Profesional en Operaciones Contables y Financieras', 'uploads/diplomas/Tecnico.php', 5, '2025-11-27 15:30:26', '2025-11-27 15:30:26'),
	(10, 'Plantilla Diploma THT', 'Plantilla temporal para Técn. Profesional en Hotelería y Turismo', 'uploads/diplomas/Tecnico.php', 6, '2025-11-27 15:30:26', '2025-11-27 15:30:26'),
	(11, 'Plantilla Diploma TPS', 'Plantilla temporal para Técn. Profesional en Administración en Salud', 'uploads/diplomas/Tecnico.php', 7, '2025-11-27 15:30:26', '2025-11-27 15:30:26'),
	(12, 'Plantilla Diploma TPA', 'Plantilla temporal para Técn. Profesional en Procesos Administrativos', 'uploads/diplomas/Tecnico.php', 8, '2025-11-27 15:30:26', '2025-11-27 15:30:26'),
	(13, 'Plantilla Diploma TPW', 'Plantilla temporal para Técn. Profesional en Programación Web', 'uploads/diplomas/Tecnico.php', 9, '2025-11-27 15:30:26', '2025-11-27 15:30:26'),
	(14, 'Plantilla Diploma TMD', 'Plantilla temporal para Técn. Profesional en Marketing Digital', 'uploads/diplomas/Tecnico.php', 10, '2025-11-27 15:30:26', '2025-11-27 15:30:26'),
	(15, 'Plantilla Diploma TPD', 'Plantilla temporal para Técn. Profesional en Producción Publicitaria Digital', 'uploads/diplomas/Tecnico.php', 11, '2025-11-27 15:30:26', '2025-11-27 15:30:26'),
	(16, 'Plantilla Diploma TIR', 'Plantilla temporal para Técn. Profesional en Instalación y Configuración de Redes', 'uploads/diplomas/Tecnico.php', 12, '2025-11-27 15:30:26', '2025-11-27 15:30:26');

-- Volcando estructura para tabla diplomas_litoral.enrollments
CREATE TABLE IF NOT EXISTS `enrollments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `people_id` int(11) NOT NULL,
  `academic_group_id` int(11) NOT NULL,
  `enrollment_status` enum('En Proceso Matrícula','Matriculado','Egresado','Suspendido','Retirado','En Renovación') NOT NULL DEFAULT 'Matriculado',
  `start_time` datetime NOT NULL DEFAULT current_timestamp(),
  `end_time` datetime DEFAULT current_timestamp(),
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `people_id` (`people_id`),
  KEY `group_id` (`academic_group_id`) USING BTREE,
  CONSTRAINT `FK_enrollments_academic_group_2` FOREIGN KEY (`academic_group_id`) REFERENCES `academic_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_enrollments_people` FOREIGN KEY (`people_id`) REFERENCES `people` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla diplomas_litoral.enrollments: ~25 rows (aproximadamente)
INSERT INTO `enrollments` (`id`, `people_id`, `academic_group_id`, `enrollment_status`, `start_time`, `end_time`, `last_update`) VALUES
	(3, 1, 1, 'Egresado', '2025-11-20 17:33:00', '2025-11-20 17:33:00', '2025-12-03 15:50:00'),
	(4, 1, 2, 'Matriculado', '2025-11-20 17:33:03', '2025-11-20 17:33:03', '2025-12-03 15:50:00'),
	(5, 2, 1, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(6, 4, 1, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(7, 6, 1, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(8, 8, 1, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(9, 10, 1, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(10, 12, 1, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(11, 14, 1, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(12, 17, 1, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(13, 18, 1, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(14, 26, 1, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(15, 27, 1, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(16, 28, 1, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(17, 3, 2, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(18, 5, 2, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(19, 7, 2, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(20, 9, 2, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(21, 11, 2, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(22, 13, 2, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(23, 19, 2, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(24, 20, 2, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(25, 15, 2, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(26, 21, 2, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00'),
	(27, 16, 2, 'Matriculado', '2025-11-25 03:22:19', '2025-11-25 03:22:19', '2025-12-03 15:50:00');

-- Volcando estructura para tabla diplomas_litoral.graduation_requirements
CREATE TABLE IF NOT EXISTS `graduation_requirements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `is_required` binary(1) NOT NULL DEFAULT '1',
  `is_active` binary(1) NOT NULL DEFAULT '1',
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla diplomas_litoral.graduation_requirements: ~12 rows (aproximadamente)
INSERT INTO `graduation_requirements` (`id`, `name`, `description`, `is_required`, `is_active`, `last_update`) VALUES
	(1, 'Aprobación del plan de estudios', 'El estudiante debe haber aprobado y finalizado todas las asignaturas del plan de estudios correspondiente.', _binary 0x31, _binary 0x31, '2025-12-03 15:50:06'),
	(2, 'Notas registradas en historia académica', 'Todas las notas deben estar cargadas oficialmente en el sistema después del cierre académico del último periodo cursado.', _binary 0x31, _binary 0x31, '2025-12-03 15:50:06'),
	(3, 'Cumplimiento de opción de grado', 'Debe haber aprobado la opción de grado correspondiente: diplomado, práctica empresarial o proyecto de investigación sustentado y aprobado.', _binary 0x31, _binary 0x31, '2025-12-03 15:50:06'),
	(4, 'Presentación de Pruebas Saber', 'Debe haber presentado las pruebas Saber correspondientes a su nivel de formación.', _binary 0x31, _binary 0x31, '2025-12-03 15:50:06'),
	(5, 'No tener multas o libros pendientes', 'El estudiante no debe presentar multas pendientes ni libros sin devolver en la biblioteca.', _binary 0x31, _binary 0x31, '2025-12-03 15:50:06'),
	(6, 'Requisito de idioma', 'Debe demostrar competencia en idioma extranjero correspondiente al nivel: Técnico Profesional = A2. Se aceptan TOEFL ITP/IBT, TOEIC, IELTS, CAE, FCE, APTIS (4 skills) y los idiomas Inglés, Francés, Alemán, Mandarín o Portugués.', _binary 0x31, _binary 0x31, '2025-12-03 15:50:06'),
	(7, 'No tener multas tecnológicas', 'El estudiante no debe tener multas relacionadas con daños o pérdidas de recursos tecnológicos.', _binary 0x31, _binary 0x31, '2025-12-03 15:50:06'),
	(8, 'Encuestas obligatorias MEN e institucionales', 'Debe diligenciar las encuestas del Ministerio de Educación y la encuesta institucional de egresados.', _binary 0x31, _binary 0x31, '2025-12-03 15:50:06'),
	(9, 'Aprobación de Cátedra Litoralista para la Paz', 'Debe haber cursado y aprobado la asignatura de Cátedra Litoralista para la Paz.', _binary 0x31, _binary 0x31, '2025-12-03 15:50:06'),
	(10, 'Paz y salvo por implementos e indumentaria', 'Debe estar a paz y salvo por devolución de implementos, instrumentos o indumentaria del área correspondiente.', _binary 0x31, _binary 0x31, '2025-12-03 15:50:06'),
	(11, 'Paz y salvo financiero', 'Debe estar a paz y salvo en todos los conceptos financieros con la institución.', _binary 0x31, _binary 0x31, '2025-12-03 15:50:06'),
	(12, 'Pago de derechos de grado', 'Debe haber cancelado el valor correspondiente al derecho de grado (colectivo o privado).', _binary 0x31, _binary 0x31, '2025-12-03 15:50:06');

-- Volcando estructura para tabla diplomas_litoral.identity_types
CREATE TABLE IF NOT EXISTS `identity_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `is_active` binary(1) NOT NULL DEFAULT '1',
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla diplomas_litoral.identity_types: ~6 rows (aproximadamente)
INSERT INTO `identity_types` (`id`, `code`, `name`, `description`, `is_active`, `last_update`) VALUES
	(1, 'CC', 'Cédula de Ciudadanía', 'Documento nacional colombiano para mayores de edad', _binary 0x31, '2025-12-03 15:50:10'),
	(2, 'TI', 'Tarjeta de Identidad', 'Documento colombiano para menores de edad', _binary 0x31, '2025-12-03 15:50:10'),
	(3, 'CE', 'Cédula de Extranjería', 'Documento para extranjeros residentes en Colombia', _binary 0x31, '2025-12-03 15:50:10'),
	(4, 'PAS', 'Pasaporte', 'Documento internacional de identificación', _binary 0x31, '2025-12-03 15:50:10'),
	(5, 'PPT', 'Permiso por Protección Temporal', 'Documento para migrantes venezolanos', _binary 0x31, '2025-12-03 15:50:10'),
	(6, 'NIT', 'Número de Identificación Tributaria', 'Identificador tributario de entidades', _binary 0x31, '2025-12-03 15:50:10');

-- Volcando estructura para tabla diplomas_litoral.people
CREATE TABLE IF NOT EXISTS `people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_type_id` int(11) NOT NULL,
  `document_id` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `second_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `second_last_name` varchar(50) NOT NULL,
  `email_primary` varchar(50) NOT NULL,
  `email_secondary` varchar(50) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_id` (`document_id`),
  UNIQUE KEY `email_primary` (`email_primary`),
  UNIQUE KEY `email_secondary` (`email_secondary`),
  KEY `document_type_id` (`document_type_id`),
  CONSTRAINT `FK_people_identity_types` FOREIGN KEY (`document_type_id`) REFERENCES `identity_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla diplomas_litoral.people: ~24 rows (aproximadamente)
INSERT INTO `people` (`id`, `document_type_id`, `document_id`, `first_name`, `second_name`, `last_name`, `second_last_name`, `email_primary`, `email_secondary`, `address`, `last_update`) VALUES
	(1, 1, '1000123456', 'Pedro', 'Pérez', 'Pérez', 'Pérez', 'juan.perez@example.com', 'juan.sec@example.com', 'Cra 12 #45-67', '2025-12-03 15:50:14'),
	(2, 1, '10000001', 'Juan', 'Carlos', 'Pérez', 'Gómez', 'jun.perez@example.com', 'jc.perez@gmail.com', 'Calle 1 #1-0122', '2025-12-03 15:50:14'),
	(3, 2, '10000002', 'María', 'Lucía', 'Rodríguez', 'Sánchez', 'maria.rodriguez@example.com', NULL, 'Carrera 2 #2-02', '2025-12-03 15:50:14'),
	(4, 1, '10000003', 'Pedro', NULL, 'Martínez', 'López', 'pedro.martinez@example.com', 'pmartinez@yahoo.com', 'Calle 3 #3-03', '2025-12-03 15:50:14'),
	(5, 2, '10000004', 'Ana', 'Sofía', 'García', 'Hernández', 'ana.garcia@example.com', NULL, 'Carrera 4 #4-04', '2025-12-03 15:50:14'),
	(6, 1, '10000005', 'Luis', NULL, 'Ramírez', 'Torres', 'luis.ramirez@example.com', 'lramirez@gmail.com', 'Calle 5 #5-05', '2025-12-03 15:50:14'),
	(7, 2, '10000006', 'Carolina', 'Isabel', 'Jiménez', 'Vargas', 'carolina.jimenez@example.com', NULL, 'Carrera 6 #6-06', '2025-12-03 15:50:14'),
	(8, 1, '10000007', 'Andrés', NULL, 'Castillo', 'Morales', 'andres.castillo@example.com', 'acastillo@hotmail.com', 'Calle 7 #7-07', '2025-12-03 15:50:14'),
	(9, 2, '10000008', 'Laura', 'Valentina', 'Torres', 'Rojas', 'laura.torres@example.com', NULL, 'Carrera 8 #8-08', '2025-12-03 15:50:14'),
	(10, 1, '10000009', 'Diego', NULL, 'Suárez', 'Cárdenas', 'diego.suarez@example.com', 'dsuarez@gmail.com', 'Calle 9 #9-09', '2025-12-03 15:50:14'),
	(11, 2, '10000010', 'Natalia', 'Paola', 'Ramírez', 'Quintero', 'natalia.ramirez@example.com', NULL, 'Carrera 10 #10-10', '2025-12-03 15:50:14'),
	(12, 1, '10000011', 'Santiago', NULL, 'Gómez', 'Ramírez', 'santiago.gomez@example.com', NULL, 'Calle 11 #11-01', '2025-12-03 15:50:14'),
	(13, 2, '10000012', 'Valentina', 'María', 'López', 'Pineda', 'valentina.lopez@example.com', 'v.lopez@gmail.com', 'Carrera 12 #12-02', '2025-12-03 15:50:14'),
	(14, 1, '10000013', 'Mateo', NULL, 'Castillo', 'Cárdenas', 'mateo.castillo@example.com', '', 'Calle 13 #13-03', '2025-12-03 15:50:14'),
	(15, 4, '10000014', 'Isabella', 'Sofía', 'Torres', 'García', 'isabella.torres@example.com', 'i.torres@yahoo.com', 'Carrera 14 #14-04', '2025-12-03 15:50:14'),
	(16, 5, '10000015', 'Daniel', NULL, 'Ramírez', 'Vargas', 'daniel.ramirez@example.com', NULL, 'Calle 15 #15-05', '2025-12-03 15:50:14'),
	(17, 1, '10000016', 'Pérez', 'Pérez', 'Pérez', 'Pérez', 'camila.jimenez@example.com', 'c.jimenez@gmail.com', 'Carrera 16 #16-06', '2025-12-03 15:50:14'),
	(18, 1, '10000017', 'Sebastián', NULL, 'Martínez', 'Hernández', 'sebastian.martinez@example.com', NULL, 'Calle 17 #17-07', '2025-12-03 15:50:14'),
	(19, 2, '10000018', 'Mariana', 'Lucía', 'Suárez', 'Quintero', 'mariana.suarez@example.com', NULL, 'Carrera 18 #18-08', '2025-12-03 15:50:14'),
	(20, 3, '10000019', 'Alejandro', NULL, 'Pérez', 'Castro', 'alejandro.perez@example.com', 'a.perez@hotmail.com', 'Calle 19 #19-09', '2025-12-03 15:50:14'),
	(21, 4, '10000020', 'Gabriela', 'Isabel', 'Vargas', 'Mora', 'gabriela.vargas@example.com', NULL, 'Carrera 20 #20-10', '2025-12-03 15:50:14'),
	(26, 1, '1047041299', 'Jesus', 'David', 'Lucena', 'Quintero', 'lucenajesus@litoral.edu.co', 'lucenajesus@litoral.edu.co', 'calle 46 #46-191', '2025-12-03 15:50:14'),
	(27, 1, '12354789', 'laura', NULL, 'canpo', 'oca', 'lucenaesus@litoral.edu.co', 'lucen@litoral.edu.co', 'calle 46 #46-191', '2025-12-03 15:50:14'),
	(28, 1, '123123212313', 'Leon', NULL, 'Perez', 'Lopez', 'juan.sec@example.com', NULL, NULL, '2025-12-03 15:50:14');

-- Volcando estructura para tabla diplomas_litoral.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `is_active` binary(1) NOT NULL DEFAULT '1',
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla diplomas_litoral.permissions: ~26 rows (aproximadamente)
INSERT INTO `permissions` (`id`, `name`, `description`, `is_active`, `last_update`) VALUES
	(1, 'user.view', 'Permite ver la lista de usuarios', _binary 0x31, '2025-12-03 15:50:19'),
	(2, 'user.create', 'Permite crear nuevos usuarios', _binary 0x31, '2025-12-03 15:50:19'),
	(3, 'user.update', 'Permite editar usuarios existentes', _binary 0x31, '2025-12-03 15:50:19'),
	(4, 'user.delete', 'Permite eliminar usuarios', _binary 0x31, '2025-12-03 15:50:19'),
	(5, 'role.view', 'Permite ver la lista de roles', _binary 0x31, '2025-12-03 15:50:19'),
	(6, 'role.create', 'Permite crear nuevos roles', _binary 0x31, '2025-12-03 15:50:19'),
	(7, 'role.update', 'Permite editar roles existentes', _binary 0x31, '2025-12-03 15:50:19'),
	(8, 'role.delete', 'Permite eliminar roles', _binary 0x31, '2025-12-03 15:50:19'),
	(9, 'permission.view', 'Permite ver la lista de permisos', _binary 0x31, '2025-12-03 15:50:19'),
	(10, 'permission.assign', 'Permite asignar permisos a los roles', _binary 0x31, '2025-12-03 15:50:19'),
	(11, 'program.view', 'Permite ver todos los programas académicos', _binary 0x31, '2025-12-03 15:50:19'),
	(12, 'program.create', 'Permite crear programas académicos', _binary 0x31, '2025-12-03 15:50:19'),
	(13, 'program.update', 'Permite actualizar programas académicos', _binary 0x31, '2025-12-03 15:50:19'),
	(14, 'program.delete', 'Permite eliminar programas académicos', _binary 0x31, '2025-12-03 15:50:19'),
	(15, 'group.view', 'Permite ver grupos académicos', _binary 0x31, '2025-12-03 15:50:19'),
	(16, 'group.create', 'Permite crear grupos académicos', _binary 0x31, '2025-12-03 15:50:19'),
	(17, 'group.update', 'Permite actualizar grupos académicos', _binary 0x31, '2025-12-03 15:50:19'),
	(18, 'group.delete', 'Permite eliminar grupos académicos', _binary 0x31, '2025-12-03 15:50:19'),
	(19, 'enrollment.view', 'Permite ver matrículas', _binary 0x31, '2025-12-03 15:50:19'),
	(20, 'enrollment.create', 'Permite matricular estudiantes', _binary 0x31, '2025-12-03 15:50:19'),
	(21, 'enrollment.update', 'Permite actualizar matrículas', _binary 0x31, '2025-12-03 15:50:19'),
	(22, 'enrollment.delete', 'Permite eliminar matrículas', _binary 0x31, '2025-12-03 15:50:19'),
	(23, 'diploma.view', 'Permite ver diplomas', _binary 0x31, '2025-12-03 15:50:19'),
	(24, 'diploma.create', 'Permite crear diplomas', _binary 0x31, '2025-12-03 15:50:19'),
	(25, 'diploma.update', 'Permite actualizar diplomas', _binary 0x31, '2025-12-03 15:50:19'),
	(26, 'diploma.revoke', 'Permite revocar diplomas', _binary 0x31, '2025-12-03 15:50:19');

-- Volcando estructura para tabla diplomas_litoral.phones
CREATE TABLE IF NOT EXISTS `phones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `people_id` int(11) NOT NULL,
  `phone_number` varchar(100) NOT NULL,
  `priority` binary(1) NOT NULL DEFAULT '1',
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `people_id` (`people_id`),
  CONSTRAINT `FK_phones_people` FOREIGN KEY (`people_id`) REFERENCES `people` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla diplomas_litoral.phones: ~6 rows (aproximadamente)
INSERT INTO `phones` (`id`, `people_id`, `phone_number`, `priority`, `last_update`) VALUES
	(1, 1, '+573001234567', _binary 0x31, '2025-12-03 15:50:24'),
	(2, 1, '+573118459909', _binary 0x30, '2025-12-03 15:50:24'),
	(4, 14, '3001234567', _binary 0x31, '2025-12-03 15:50:24'),
	(5, 26, '+573118459909', _binary 0x31, '2025-12-03 15:50:24'),
	(6, 27, '+573118459909', _binary 0x31, '2025-12-03 15:50:24'),
	(7, 27, '+573118459909', _binary 0x30, '2025-12-03 15:50:24'),
	(8, 28, '+573118459909', _binary 0x31, '2025-12-03 15:50:24'),
	(9, 2, '+573118459908', _binary 0x31, '2025-12-03 15:50:24');

-- Volcando estructura para tabla diplomas_litoral.programs
CREATE TABLE IF NOT EXISTS `programs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_type_id` int(11) NOT NULL,
  `code` varchar(3) NOT NULL,
  `name` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `schedule_id` int(11) NOT NULL DEFAULT 0,
  `mode` enum('Presencial','Virtual','Hibrida') NOT NULL DEFAULT 'Presencial',
  `number_of_semesters` int(2) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `code` (`code`),
  KEY `program_type_id` (`program_type_id`),
  KEY `schedule_id` (`schedule_id`),
  CONSTRAINT `FK_programs_program_types` FOREIGN KEY (`program_type_id`) REFERENCES `program_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_programs_work_shifts` FOREIGN KEY (`schedule_id`) REFERENCES `work_shifts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla diplomas_litoral.programs: ~12 rows (aproximadamente)
INSERT INTO `programs` (`id`, `program_type_id`, `code`, `name`, `details`, `schedule_id`, `mode`, `number_of_semesters`, `last_update`) VALUES
	(1, 3, 'TCE', 'Técnica Profesional de Operaciones en Comercio Exterior', NULL, 1, 'Presencial', 4, '2025-12-03 15:50:28'),
	(2, 3, 'TPP', 'Técnica Profesional de Procesos Administrativos Portuarios', NULL, 1, 'Presencial', 4, '2025-12-03 15:50:28'),
	(3, 3, 'TLO', 'Técnica Profesional en Operaciones Logísticas', NULL, 1, 'Presencial', 4, '2025-12-03 15:50:28'),
	(4, 3, 'SST', 'Técnica Profesional en Seguridad y Salud en el Trabajo', NULL, 1, 'Presencial', 4, '2025-12-03 15:50:28'),
	(5, 3, 'TCF', 'Técnica Profesional en Operaciones Contables y Financieras', NULL, 1, 'Presencial', 4, '2025-12-03 15:50:28'),
	(6, 3, 'THT', 'Técnica Profesional en Procesos Administrativos en Hoteleria y Turismo', NULL, 1, 'Presencial', 4, '2025-12-03 15:50:28'),
	(7, 3, 'TPS', 'Técnica Profesional de Procesos Administrativos en Salud', NULL, 1, 'Presencial', 4, '2025-12-03 15:50:28'),
	(8, 3, 'TPA', 'Técnica Profesional en Procesos Administrativos', NULL, 1, 'Presencial', 4, '2025-12-03 15:50:28'),
	(9, 3, 'TPW', 'Técnica Profesional en Programación Web', NULL, 1, 'Presencial', 4, '2025-12-03 15:50:28'),
	(10, 3, 'TMD', 'Técnica Profesional en Procesos de Marketing Digital', NULL, 1, 'Presencial', 4, '2025-12-03 15:50:28'),
	(11, 3, 'TPD', 'Técnica Profesional en Producción Publicitaria Digital', NULL, 1, 'Presencial', 4, '2025-12-03 15:50:28'),
	(12, 3, 'TIR', 'Técnica Profesional en Instalación y Configuración de Redes', NULL, 1, 'Presencial', 4, '2025-12-03 15:50:28');

-- Volcando estructura para tabla diplomas_litoral.program_requirements
CREATE TABLE IF NOT EXISTS `program_requirements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_id` int(11) NOT NULL,
  `requirement_id` int(11) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `program_id` (`program_id`),
  KEY `requirement_id` (`requirement_id`),
  CONSTRAINT `FK_validacion_programa_graduation_requirements` FOREIGN KEY (`requirement_id`) REFERENCES `graduation_requirements` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_validacion_programa_programs` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla diplomas_litoral.program_requirements: ~144 rows (aproximadamente)
INSERT INTO `program_requirements` (`id`, `program_id`, `requirement_id`, `last_update`) VALUES
	(1, 10, 1, '2025-12-03 15:50:34'),
	(2, 10, 2, '2025-12-03 15:50:34'),
	(3, 10, 3, '2025-12-03 15:50:34'),
	(4, 10, 4, '2025-12-03 15:50:34'),
	(5, 10, 5, '2025-12-03 15:50:34'),
	(6, 10, 6, '2025-12-03 15:50:34'),
	(7, 10, 7, '2025-12-03 15:50:34'),
	(8, 10, 8, '2025-12-03 15:50:34'),
	(9, 10, 9, '2025-12-03 15:50:34'),
	(10, 10, 10, '2025-12-03 15:50:34'),
	(11, 10, 11, '2025-12-03 15:50:34'),
	(12, 10, 12, '2025-12-03 15:50:34'),
	(13, 1, 9, '2025-12-03 15:50:34'),
	(14, 2, 9, '2025-12-03 15:50:34'),
	(15, 3, 9, '2025-12-03 15:50:34'),
	(16, 4, 9, '2025-12-03 15:50:34'),
	(17, 5, 9, '2025-12-03 15:50:34'),
	(18, 6, 9, '2025-12-03 15:50:34'),
	(19, 7, 9, '2025-12-03 15:50:34'),
	(20, 8, 9, '2025-12-03 15:50:34'),
	(21, 9, 9, '2025-12-03 15:50:34'),
	(22, 11, 9, '2025-12-03 15:50:34'),
	(23, 12, 9, '2025-12-03 15:50:34'),
	(24, 1, 1, '2025-12-03 15:50:34'),
	(25, 2, 1, '2025-12-03 15:50:34'),
	(26, 3, 1, '2025-12-03 15:50:34'),
	(27, 4, 1, '2025-12-03 15:50:34'),
	(28, 5, 1, '2025-12-03 15:50:34'),
	(29, 6, 1, '2025-12-03 15:50:34'),
	(30, 7, 1, '2025-12-03 15:50:34'),
	(31, 8, 1, '2025-12-03 15:50:34'),
	(32, 9, 1, '2025-12-03 15:50:34'),
	(33, 11, 1, '2025-12-03 15:50:34'),
	(34, 12, 1, '2025-12-03 15:50:34'),
	(35, 1, 3, '2025-12-03 15:50:34'),
	(36, 2, 3, '2025-12-03 15:50:34'),
	(37, 3, 3, '2025-12-03 15:50:34'),
	(38, 4, 3, '2025-12-03 15:50:34'),
	(39, 5, 3, '2025-12-03 15:50:34'),
	(40, 6, 3, '2025-12-03 15:50:34'),
	(41, 7, 3, '2025-12-03 15:50:34'),
	(42, 8, 3, '2025-12-03 15:50:34'),
	(43, 9, 3, '2025-12-03 15:50:34'),
	(44, 11, 3, '2025-12-03 15:50:34'),
	(45, 12, 3, '2025-12-03 15:50:34'),
	(46, 1, 8, '2025-12-03 15:50:34'),
	(47, 2, 8, '2025-12-03 15:50:34'),
	(48, 3, 8, '2025-12-03 15:50:34'),
	(49, 4, 8, '2025-12-03 15:50:34'),
	(50, 5, 8, '2025-12-03 15:50:34'),
	(51, 6, 8, '2025-12-03 15:50:34'),
	(52, 7, 8, '2025-12-03 15:50:34'),
	(53, 8, 8, '2025-12-03 15:50:34'),
	(54, 9, 8, '2025-12-03 15:50:34'),
	(55, 11, 8, '2025-12-03 15:50:34'),
	(56, 12, 8, '2025-12-03 15:50:34'),
	(57, 1, 5, '2025-12-03 15:50:34'),
	(58, 2, 5, '2025-12-03 15:50:34'),
	(59, 3, 5, '2025-12-03 15:50:34'),
	(60, 4, 5, '2025-12-03 15:50:34'),
	(61, 5, 5, '2025-12-03 15:50:34'),
	(62, 6, 5, '2025-12-03 15:50:34'),
	(63, 7, 5, '2025-12-03 15:50:34'),
	(64, 8, 5, '2025-12-03 15:50:34'),
	(65, 9, 5, '2025-12-03 15:50:34'),
	(66, 11, 5, '2025-12-03 15:50:34'),
	(67, 12, 5, '2025-12-03 15:50:34'),
	(68, 1, 7, '2025-12-03 15:50:34'),
	(69, 2, 7, '2025-12-03 15:50:34'),
	(70, 3, 7, '2025-12-03 15:50:34'),
	(71, 4, 7, '2025-12-03 15:50:34'),
	(72, 5, 7, '2025-12-03 15:50:34'),
	(73, 6, 7, '2025-12-03 15:50:34'),
	(74, 7, 7, '2025-12-03 15:50:34'),
	(75, 8, 7, '2025-12-03 15:50:34'),
	(76, 9, 7, '2025-12-03 15:50:34'),
	(77, 11, 7, '2025-12-03 15:50:34'),
	(78, 12, 7, '2025-12-03 15:50:34'),
	(79, 1, 2, '2025-12-03 15:50:34'),
	(80, 2, 2, '2025-12-03 15:50:34'),
	(81, 3, 2, '2025-12-03 15:50:34'),
	(82, 4, 2, '2025-12-03 15:50:34'),
	(83, 5, 2, '2025-12-03 15:50:34'),
	(84, 6, 2, '2025-12-03 15:50:34'),
	(85, 7, 2, '2025-12-03 15:50:34'),
	(86, 8, 2, '2025-12-03 15:50:34'),
	(87, 9, 2, '2025-12-03 15:50:34'),
	(88, 11, 2, '2025-12-03 15:50:34'),
	(89, 12, 2, '2025-12-03 15:50:34'),
	(90, 1, 12, '2025-12-03 15:50:34'),
	(91, 2, 12, '2025-12-03 15:50:34'),
	(92, 3, 12, '2025-12-03 15:50:34'),
	(93, 4, 12, '2025-12-03 15:50:34'),
	(94, 5, 12, '2025-12-03 15:50:34'),
	(95, 6, 12, '2025-12-03 15:50:34'),
	(96, 7, 12, '2025-12-03 15:50:34'),
	(97, 8, 12, '2025-12-03 15:50:34'),
	(98, 9, 12, '2025-12-03 15:50:34'),
	(99, 11, 12, '2025-12-03 15:50:34'),
	(100, 12, 12, '2025-12-03 15:50:34'),
	(101, 1, 11, '2025-12-03 15:50:34'),
	(102, 2, 11, '2025-12-03 15:50:34'),
	(103, 3, 11, '2025-12-03 15:50:34'),
	(104, 4, 11, '2025-12-03 15:50:34'),
	(105, 5, 11, '2025-12-03 15:50:34'),
	(106, 6, 11, '2025-12-03 15:50:34'),
	(107, 7, 11, '2025-12-03 15:50:34'),
	(108, 8, 11, '2025-12-03 15:50:34'),
	(109, 9, 11, '2025-12-03 15:50:34'),
	(110, 11, 11, '2025-12-03 15:50:34'),
	(111, 12, 11, '2025-12-03 15:50:34'),
	(112, 1, 10, '2025-12-03 15:50:34'),
	(113, 2, 10, '2025-12-03 15:50:34'),
	(114, 3, 10, '2025-12-03 15:50:34'),
	(115, 4, 10, '2025-12-03 15:50:34'),
	(116, 5, 10, '2025-12-03 15:50:34'),
	(117, 6, 10, '2025-12-03 15:50:34'),
	(118, 7, 10, '2025-12-03 15:50:34'),
	(119, 8, 10, '2025-12-03 15:50:34'),
	(120, 9, 10, '2025-12-03 15:50:34'),
	(121, 11, 10, '2025-12-03 15:50:34'),
	(122, 12, 10, '2025-12-03 15:50:34'),
	(123, 1, 4, '2025-12-03 15:50:34'),
	(124, 2, 4, '2025-12-03 15:50:34'),
	(125, 3, 4, '2025-12-03 15:50:34'),
	(126, 4, 4, '2025-12-03 15:50:34'),
	(127, 5, 4, '2025-12-03 15:50:34'),
	(128, 6, 4, '2025-12-03 15:50:34'),
	(129, 7, 4, '2025-12-03 15:50:34'),
	(130, 8, 4, '2025-12-03 15:50:34'),
	(131, 9, 4, '2025-12-03 15:50:34'),
	(132, 11, 4, '2025-12-03 15:50:34'),
	(133, 12, 4, '2025-12-03 15:50:34'),
	(134, 1, 6, '2025-12-03 15:50:34'),
	(135, 2, 6, '2025-12-03 15:50:34'),
	(136, 3, 6, '2025-12-03 15:50:34'),
	(137, 4, 6, '2025-12-03 15:50:34'),
	(138, 5, 6, '2025-12-03 15:50:34'),
	(139, 6, 6, '2025-12-03 15:50:34'),
	(140, 7, 6, '2025-12-03 15:50:34'),
	(141, 8, 6, '2025-12-03 15:50:34'),
	(142, 9, 6, '2025-12-03 15:50:34'),
	(143, 11, 6, '2025-12-03 15:50:34'),
	(144, 12, 6, '2025-12-03 15:50:34');

-- Volcando estructura para tabla diplomas_litoral.program_types
CREATE TABLE IF NOT EXISTS `program_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` binary(1) NOT NULL DEFAULT '1',
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla diplomas_litoral.program_types: ~3 rows (aproximadamente)
INSERT INTO `program_types` (`id`, `name`, `code`, `description`, `is_active`, `last_update`) VALUES
	(1, 'Curso', 'CUR', 'Programas cortos de formación.', _binary 0x31, '2025-12-03 15:50:45'),
	(2, 'Posgrado', 'POS', 'Programas de especialización, maestría o doctorado.', _binary 0x31, '2025-12-03 15:50:45'),
	(3, 'Técnico Profesional', 'TEC', 'Programas técnicos profesionales orientados al trabajo.', _binary 0x31, '2025-12-03 15:50:45');

-- Volcando estructura para tabla diplomas_litoral.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_default` binary(1) NOT NULL DEFAULT '0',
  `is_active` binary(1) NOT NULL DEFAULT '1',
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla diplomas_litoral.roles: ~7 rows (aproximadamente)
INSERT INTO `roles` (`id`, `name`, `description`, `is_default`, `is_active`, `last_update`) VALUES
	(1, 'Admin', 'Usuario con control total del sistema', _binary 0x30, _binary 0x31, '2025-12-03 15:50:48'),
	(2, 'Student', 'Usuario estudiante con acceso limitado', _binary 0x31, _binary 0x31, '2025-12-03 15:50:48'),
	(3, 'Teacher', 'Instructor del sistema académico', _binary 0x30, _binary 0x31, '2025-12-03 15:50:48'),
	(4, 'ProgramCoordinator', 'Coordinador de programas académicos', _binary 0x30, _binary 0x31, '2025-12-03 15:50:48'),
	(5, 'Registrar', 'Encargado de matrículas y certificados', _binary 0x30, _binary 0x31, '2025-12-03 15:50:48'),
	(6, 'DiplomaManager', 'Administrador de diplomas y certificados', _binary 0x30, _binary 0x31, '2025-12-03 15:50:48'),
	(7, 'Viewer', 'Usuario con permisos de solo lectura', _binary 0x30, _binary 0x31, '2025-12-03 15:50:48');

-- Volcando estructura para tabla diplomas_litoral.role_permissions
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` int(11) NOT NULL DEFAULT 0,
  `permission_id` int(11) NOT NULL DEFAULT 0,
  `is_active` binary(1) NOT NULL DEFAULT '1',
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  KEY `role_id` (`role_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `FK_role_permissions_permissions` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_role_permissions_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla diplomas_litoral.role_permissions: ~26 rows (aproximadamente)
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `is_active`, `last_update`) VALUES
	(1, 1, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 2, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 3, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 4, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 5, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 6, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 7, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 8, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 9, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 10, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 11, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 12, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 13, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 14, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 15, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 16, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 17, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 18, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 19, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 20, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 21, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 22, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 23, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 24, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 25, _binary 0x31, '2025-12-03 15:50:53'),
	(1, 26, _binary 0x31, '2025-12-03 15:50:53');

-- Volcando estructura para tabla diplomas_litoral.student_validations
CREATE TABLE IF NOT EXISTS `student_validations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enrollment_id` int(11) NOT NULL DEFAULT 0,
  `program_requirement_id` int(11) NOT NULL DEFAULT 0,
  `status` enum('Pending','Submitted','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `validated_by` int(11) NOT NULL,
  `validated_at` datetime NOT NULL,
  `observation` varchar(255) NOT NULL DEFAULT '',
  `documen` varchar(255) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY ` enrollment_id` (`enrollment_id`) USING BTREE,
  KEY ` program_requirement_id` (`program_requirement_id`) USING BTREE,
  CONSTRAINT `FK_student_validations_enrollments` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_student_validations_program_requirements` FOREIGN KEY (`program_requirement_id`) REFERENCES `program_requirements` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=301 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla diplomas_litoral.student_validations: ~300 rows (aproximadamente)
INSERT INTO `student_validations` (`id`, `enrollment_id`, `program_requirement_id`, `status`, `validated_by`, `validated_at`, `observation`, `documen`, `last_update`) VALUES
	(1, 4, 13, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(2, 4, 24, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(3, 4, 35, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(4, 4, 46, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(5, 4, 57, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(6, 4, 68, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(7, 4, 79, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(8, 4, 90, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(9, 4, 101, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(10, 4, 112, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(11, 4, 123, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(12, 4, 134, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(13, 17, 13, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(14, 17, 24, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(15, 17, 35, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(16, 17, 46, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(17, 17, 57, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(18, 17, 68, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(19, 17, 79, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(20, 17, 90, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(21, 17, 101, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(22, 17, 112, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(23, 17, 123, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(24, 17, 134, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(25, 18, 13, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(26, 18, 24, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(27, 18, 35, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(28, 18, 46, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(29, 18, 57, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(30, 18, 68, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(31, 18, 79, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(32, 18, 90, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(33, 18, 101, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(34, 18, 112, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(35, 18, 123, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(36, 18, 134, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(37, 19, 13, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(38, 19, 24, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(39, 19, 35, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(40, 19, 46, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(41, 19, 57, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(42, 19, 68, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(43, 19, 79, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(44, 19, 90, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(45, 19, 101, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(46, 19, 112, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(47, 19, 123, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(48, 19, 134, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(49, 20, 13, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(50, 20, 24, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(51, 20, 35, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(52, 20, 46, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(53, 20, 57, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(54, 20, 68, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(55, 20, 79, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(56, 20, 90, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(57, 20, 101, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(58, 20, 112, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(59, 20, 123, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(60, 20, 134, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(61, 21, 13, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(62, 21, 24, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(63, 21, 35, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(64, 21, 46, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(65, 21, 57, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(66, 21, 68, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(67, 21, 79, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(68, 21, 90, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(69, 21, 101, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(70, 21, 112, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(71, 21, 123, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(72, 21, 134, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(73, 22, 13, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(74, 22, 24, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(75, 22, 35, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(76, 22, 46, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(77, 22, 57, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(78, 22, 68, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(79, 22, 79, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(80, 22, 90, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(81, 22, 101, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(82, 22, 112, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(83, 22, 123, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(84, 22, 134, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(85, 23, 13, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(86, 23, 24, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(87, 23, 35, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(88, 23, 46, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(89, 23, 57, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(90, 23, 68, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(91, 23, 79, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(92, 23, 90, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(93, 23, 101, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(94, 23, 112, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(95, 23, 123, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(96, 23, 134, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(97, 24, 13, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(98, 24, 24, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(99, 24, 35, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(100, 24, 46, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(101, 24, 57, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(102, 24, 68, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(103, 24, 79, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(104, 24, 90, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(105, 24, 101, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(106, 24, 112, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(107, 24, 123, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(108, 24, 134, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(109, 25, 13, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(110, 25, 24, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(111, 25, 35, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(112, 25, 46, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(113, 25, 57, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(114, 25, 68, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(115, 25, 79, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(116, 25, 90, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(117, 25, 101, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(118, 25, 112, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(119, 25, 123, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(120, 25, 134, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(121, 26, 13, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(122, 26, 24, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(123, 26, 35, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(124, 26, 46, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(125, 26, 57, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(126, 26, 68, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(127, 26, 79, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(128, 26, 90, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(129, 26, 101, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(130, 26, 112, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(131, 26, 123, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(132, 26, 134, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(133, 27, 13, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(134, 27, 24, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(135, 27, 35, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(136, 27, 46, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(137, 27, 57, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(138, 27, 68, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(139, 27, 79, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(140, 27, 90, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(141, 27, 101, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(142, 27, 112, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(143, 27, 123, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(144, 27, 134, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(145, 3, 1, 'Submitted', 1, '2025-11-25 03:23:08', '', 'uploads/validaciones/1764773820_CERTIFICADO (1).pdf', '2025-12-03 15:50:57'),
	(146, 3, 2, 'Submitted', 1, '2025-11-25 03:23:08', '', 'uploads/validaciones/1764774741_CERTIFICADO (1).pdf', '2025-12-03 15:50:57'),
	(147, 3, 3, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(148, 3, 4, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(149, 3, 5, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(150, 3, 6, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(151, 3, 7, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(152, 3, 8, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(153, 3, 9, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(154, 3, 10, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(155, 3, 11, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(156, 3, 12, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(157, 5, 1, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(158, 5, 2, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(159, 5, 3, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(160, 5, 4, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(161, 5, 5, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(162, 5, 6, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(163, 5, 7, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(164, 5, 8, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(165, 5, 9, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(166, 5, 10, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(167, 5, 11, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(168, 5, 12, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(169, 6, 1, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(170, 6, 2, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(171, 6, 3, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(172, 6, 4, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(173, 6, 5, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(174, 6, 6, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(175, 6, 7, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(176, 6, 8, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(177, 6, 9, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(178, 6, 10, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(179, 6, 11, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(180, 6, 12, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(181, 7, 1, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(182, 7, 2, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(183, 7, 3, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(184, 7, 4, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(185, 7, 5, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(186, 7, 6, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(187, 7, 7, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(188, 7, 8, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(189, 7, 9, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(190, 7, 10, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(191, 7, 11, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(192, 7, 12, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(193, 8, 1, 'Pending', 1, '2025-11-25 03:23:08', '111111111111111111111111111111111111111111111111111', '', '2025-12-03 15:50:57'),
	(194, 8, 2, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(195, 8, 3, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(196, 8, 4, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(197, 8, 5, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(198, 8, 6, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(199, 8, 7, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(200, 8, 8, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(201, 8, 9, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(202, 8, 10, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(203, 8, 11, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(204, 8, 12, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(205, 9, 1, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(206, 9, 2, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(207, 9, 3, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(208, 9, 4, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(209, 9, 5, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(210, 9, 6, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(211, 9, 7, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(212, 9, 8, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(213, 9, 9, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(214, 9, 10, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(215, 9, 11, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(216, 9, 12, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(217, 10, 1, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(218, 10, 2, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(219, 10, 3, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(220, 10, 4, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(221, 10, 5, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(222, 10, 6, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(223, 10, 7, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(224, 10, 8, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(225, 10, 9, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(226, 10, 10, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(227, 10, 11, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(228, 10, 12, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(229, 11, 1, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(230, 11, 2, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(231, 11, 3, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(232, 11, 4, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(233, 11, 5, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(234, 11, 6, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(235, 11, 7, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(236, 11, 8, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(237, 11, 9, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(238, 11, 10, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(239, 11, 11, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(240, 11, 12, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(241, 12, 1, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(242, 12, 2, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(243, 12, 3, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(244, 12, 4, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(245, 12, 5, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(246, 12, 6, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(247, 12, 7, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(248, 12, 8, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(249, 12, 9, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(250, 12, 10, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(251, 12, 11, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(252, 12, 12, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(253, 13, 1, 'Approved', 1, '2025-12-03 15:36:30', '', 'uploads/validaciones/1764734136_CERTIFICADO.pdf', '2025-12-03 15:50:57'),
	(254, 13, 2, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(255, 13, 3, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(256, 13, 4, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(257, 13, 5, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(258, 13, 6, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(259, 13, 7, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(260, 13, 8, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(261, 13, 9, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(262, 13, 10, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(263, 13, 11, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(264, 13, 12, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(265, 14, 1, 'Approved', 1, '2025-11-27 14:47:25', '', 'uploads/validaciones/v_692760434ed07.pdf', '2025-12-03 15:50:57'),
	(266, 14, 2, 'Approved', 1, '2025-11-27 22:04:14', '', 'uploads/validaciones/1764277397_CERTIFICADO (1).pdf', '2025-12-03 15:50:57'),
	(267, 14, 3, 'Approved', 1, '2025-11-27 22:04:17', '', 'uploads/validaciones/v_692761c43a386.pdf', '2025-12-03 15:50:57'),
	(268, 14, 4, 'Approved', 1, '2025-11-27 22:04:21', '', 'uploads/validaciones/1764188968_Infografía historia de la mitología antigua scrapbook ilustrativo gris.pdf', '2025-12-03 15:50:57'),
	(269, 14, 5, 'Approved', 1, '2025-11-27 22:04:24', '', 'uploads/validaciones/1764189128_Plantilla 1 corte (1).pdf.pdf', '2025-12-03 15:50:57'),
	(270, 14, 6, 'Approved', 1, '2025-11-27 22:04:26', '', 'uploads/validaciones/1764191240_Proyecto (1).pdf', '2025-12-03 15:50:57'),
	(271, 14, 7, 'Approved', 1, '2025-11-27 22:04:29', '', 'uploads/validaciones/1764196308_Infografía historia de la mitología antigua scrapbook ilustrativo gris.pdf', '2025-12-03 15:50:57'),
	(272, 14, 8, 'Approved', 1, '2025-11-27 22:04:32', '', 'uploads/validaciones/1764277404_CERTIFICADO (1).pdf', '2025-12-03 15:50:57'),
	(273, 14, 9, 'Approved', 1, '2025-11-27 22:04:37', '', 'uploads/validaciones/1764277409_CERTIFICADO (1).pdf', '2025-12-03 15:50:57'),
	(274, 14, 10, 'Approved', 1, '2025-11-27 22:04:40', '', 'uploads/validaciones/1764277414_CERTIFICADO (1).pdf', '2025-12-03 15:50:57'),
	(275, 14, 11, 'Approved', 1, '2025-11-27 22:04:42', '', 'uploads/validaciones/1764277420_DiplomasLitoral.pdf', '2025-12-03 15:50:57'),
	(276, 14, 12, 'Approved', 1, '2025-11-27 22:04:45', '', 'uploads/validaciones/1764277426_CERTIFICADO (1).pdf', '2025-12-03 15:50:57'),
	(277, 15, 1, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(278, 15, 2, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(279, 15, 3, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(280, 15, 4, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(281, 15, 5, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(282, 15, 6, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(283, 15, 7, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(284, 15, 8, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(285, 15, 9, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(286, 15, 10, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(287, 15, 11, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(288, 15, 12, 'Pending', 1, '2025-11-25 03:23:08', '', '', '2025-12-03 15:50:57'),
	(289, 16, 1, 'Approved', 1, '2025-12-03 02:06:34', '', 'uploads/validaciones/1764723868_CERTIFICADO (1).pdf', '2025-12-03 15:50:57'),
	(290, 16, 2, 'Approved', 1, '2025-11-29 00:33:43', '', 'uploads/validaciones/1764372758_Proyecto (1).pdf', '2025-12-03 15:50:57'),
	(291, 16, 3, 'Approved', 1, '2025-12-03 02:06:38', '', 'uploads/validaciones/1764723874_CERTIFICADO (1).pdf', '2025-12-03 15:50:57'),
	(292, 16, 4, 'Approved', 1, '2025-12-03 02:06:42', '', 'uploads/validaciones/1764723883_CERTIFICADO (1).pdf', '2025-12-03 15:50:57'),
	(293, 16, 5, 'Approved', 1, '2025-12-03 02:06:50', '', 'uploads/validaciones/1764723889_CERTIFICADO (1).pdf', '2025-12-03 15:50:57'),
	(294, 16, 6, 'Approved', 1, '2025-12-03 02:07:00', '', 'uploads/validaciones/1764723896_CERTIFICADO (1).pdf', '2025-12-03 15:50:57'),
	(295, 16, 7, 'Approved', 1, '2025-12-03 02:07:05', '', 'uploads/validaciones/1764723901_CERTIFICADO.pdf', '2025-12-03 15:50:57'),
	(296, 16, 8, 'Approved', 1, '2025-12-03 02:07:53', '', 'uploads/validaciones/1764723907_CERTIFICADO.pdf', '2025-12-03 15:50:57'),
	(297, 16, 9, 'Approved', 1, '2025-12-03 02:07:15', '', 'uploads/validaciones/1764723920_CERTIFICADO.pdf', '2025-12-03 15:50:57'),
	(298, 16, 10, 'Approved', 1, '2025-12-03 02:07:18', '', 'uploads/validaciones/1764723927_CERTIFICADO (1).pdf', '2025-12-03 15:50:57'),
	(299, 16, 11, 'Approved', 1, '2025-12-03 02:07:21', '', 'uploads/validaciones/1764723940_CERTIFICADO.pdf', '2025-12-03 15:50:57'),
	(300, 16, 12, 'Approved', 1, '2025-12-03 02:07:24', '', 'uploads/validaciones/1764723949_CERTIFICADO.pdf', '2025-12-03 15:50:57');

-- Volcando estructura para tabla diplomas_litoral.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `people_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `people_id` (`people_id`),
  CONSTRAINT `FK_people_id` FOREIGN KEY (`people_id`) REFERENCES `people` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla diplomas_litoral.users: ~24 rows (aproximadamente)
INSERT INTO `users` (`id`, `people_id`, `username`, `last_update`) VALUES
	(1, 1, 'carlos.perez', '2025-12-03 15:51:02'),
	(2, 2, '10000001', '2025-12-03 15:51:02'),
	(3, 3, '10000002', '2025-12-03 15:51:02'),
	(4, 4, '10000003', '2025-12-03 15:51:02'),
	(5, 5, '10000004', '2025-12-03 15:51:02'),
	(6, 6, '10000005', '2025-12-03 15:51:02'),
	(7, 7, '10000006', '2025-12-03 15:51:02'),
	(8, 8, '10000007', '2025-12-03 15:51:02'),
	(9, 9, '10000008', '2025-12-03 15:51:02'),
	(10, 10, '10000009', '2025-12-03 15:51:02'),
	(11, 11, '10000010', '2025-12-03 15:51:02'),
	(12, 12, '10000011', '2025-12-03 15:51:02'),
	(13, 13, '10000012', '2025-12-03 15:51:02'),
	(14, 14, '10000013', '2025-12-03 15:51:02'),
	(15, 15, '10000014', '2025-12-03 15:51:02'),
	(16, 16, '10000015', '2025-12-03 15:51:02'),
	(17, 17, '10000016', '2025-12-03 15:51:02'),
	(18, 18, '10000017', '2025-12-03 15:51:02'),
	(19, 19, '10000018', '2025-12-03 15:51:02'),
	(20, 20, '10000019', '2025-12-03 15:51:02'),
	(21, 21, '10000020', '2025-12-03 15:51:02'),
	(22, 26, '1047041299', '2025-12-03 15:51:02'),
	(23, 28, '123123212313', '2025-12-03 15:51:02'),
	(24, 27, '12354789', '2025-12-03 15:51:02');

-- Volcando estructura para tabla diplomas_litoral.user_roles
CREATE TABLE IF NOT EXISTS `user_roles` (
  `users_id` int(11) NOT NULL DEFAULT 0,
  `roles_id` int(11) NOT NULL DEFAULT 0,
  `password` varchar(255) NOT NULL DEFAULT '',
  `user_status` enum('Active','Inactive','Bloqueado') NOT NULL DEFAULT 'Active',
  `start_time` datetime NOT NULL DEFAULT current_timestamp(),
  `end_time` datetime DEFAULT current_timestamp(),
  `token` int(1) NOT NULL DEFAULT 0,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  KEY `Rol_id` (`roles_id`) USING BTREE,
  KEY `Usuario_id` (`users_id`) USING BTREE,
  CONSTRAINT `FK_user_roles_roles` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_user_roles_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla diplomas_litoral.user_roles: ~25 rows (aproximadamente)
INSERT INTO `user_roles` (`users_id`, `roles_id`, `password`, `user_status`, `start_time`, `end_time`, `token`, `last_update`) VALUES
	(1, 1, '$2y$10$7mrj47dpFQKGdWxBAofP3.Tk9vrQTFeDkVAJ0hIva6OAdxV9VV0yi', 'Active', '2025-11-18 17:48:41', NULL, 0, '2025-12-03 15:51:06'),
	(1, 2, '$2y$10$7mrj47dpFQKGdWxBAofP3.Tk9vrQTFeDkVAJ0hIva6OAdxV9VV0yi', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '2025-12-03 15:51:06'),
	(2, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(3, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(4, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(5, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(6, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(7, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(8, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(9, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(10, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(11, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(12, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(13, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(14, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(15, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(16, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(17, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(18, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(19, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(20, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(21, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(22, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(24, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06'),
	(23, 2, '$2y$10$HYfG803Q5YwTN3.7bSJvh.QHOYeX0mXPp96N0zNWjYZ.iaXS1tyoi', 'Active', '2025-11-25 03:21:16', '2025-11-25 03:21:16', 0, '2025-12-03 15:51:06');

-- Volcando estructura para tabla diplomas_litoral.work_shifts
CREATE TABLE IF NOT EXISTS `work_shifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_active` binary(1) NOT NULL DEFAULT '1',
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla diplomas_litoral.work_shifts: ~4 rows (aproximadamente)
INSERT INTO `work_shifts` (`id`, `name`, `code`, `description`, `start_time`, `end_time`, `is_active`, `last_update`) VALUES
	(1, 'Mañana', 'MAN', 'Jornada de la mañana', '06:00:00', '12:00:00', _binary 0x31, '2025-12-03 15:51:10'),
	(2, 'Tarde', 'TAR', 'Jornada de la tarde', '12:00:00', '18:00:00', _binary 0x31, '2025-12-03 15:51:10'),
	(3, 'Noche', 'NOC', 'Jornada nocturna', '18:00:00', '22:00:00', _binary 0x31, '2025-12-03 15:51:10'),
	(4, 'Fin de semana', 'FDS', 'Jornada de fin de semana', '08:00:00', '17:00:00', _binary 0x31, '2025-12-03 15:51:10');

-- Volcando estructura para vista diplomas_litoral.view_people_info
-- Creando tabla temporal para superar errores de dependencia de VIEW
CREATE TABLE `view_people_info` (
	`id` INT(11) NOT NULL,
	`document_id` VARCHAR(1) NOT NULL COLLATE 'utf8_spanish_ci',
	`document_type_code` VARCHAR(1) NOT NULL COLLATE 'utf8_spanish_ci',
	`document_type_name` VARCHAR(1) NOT NULL COLLATE 'utf8_spanish_ci',
	`first_name` VARCHAR(1) NOT NULL COLLATE 'utf8_spanish_ci',
	`second_name` VARCHAR(1) NULL COLLATE 'utf8_spanish_ci',
	`last_name` VARCHAR(1) NOT NULL COLLATE 'utf8_spanish_ci',
	`second_last_name` VARCHAR(1) NOT NULL COLLATE 'utf8_spanish_ci',
	`email_primary` VARCHAR(1) NOT NULL COLLATE 'utf8_spanish_ci',
	`email_secondary` VARCHAR(1) NULL COLLATE 'utf8_spanish_ci',
	`address` VARCHAR(1) NULL COLLATE 'utf8_spanish_ci'
);

-- Volcando estructura para vista diplomas_litoral.vw_student_management
-- Creando tabla temporal para superar errores de dependencia de VIEW
CREATE TABLE `vw_student_management` 
);

-- Volcando estructura para procedimiento diplomas_litoral.insert_user_role
DELIMITER //
CREATE PROCEDURE `insert_user_role`(
    IN p_users_id INT,
    IN p_roles_id INT,
    IN p_password VARCHAR(255),
    IN p_user_status ENUM('Active','Inactive','Bloqueado'),
    IN p_start_time DATETIME,
    IN p_end_time DATETIME
)
BEGIN
    INSERT INTO user_roles (
        users_id,
        roles_id,
        password,
        user_status,
        start_time,
        end_time
    ) VALUES (
        p_users_id,
        p_roles_id,
        p_password,
        p_user_status,
        p_start_time,
        p_end_time
    );
END//
DELIMITER ;

-- Eliminando tabla temporal y crear estructura final de VIEW
DROP TABLE IF EXISTS `view_people_info`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `view_people_info` AS SELECT 
    p.id,
    p.document_id,
    it.code AS document_type_code,
    it.name AS document_type_name,
    p.first_name,
    p.second_name,
    p.last_name,
    p.second_last_name,
    p.email_primary,
    p.email_secondary,
    p.address
FROM people p
JOIN identity_types it ON p.document_type_id = it.id 
;

-- Eliminando tabla temporal y crear estructura final de VIEW
DROP TABLE IF EXISTS `vw_student_management`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vw_student_management` AS SELECT
    it.code AS identity_type_code,
    p.document_id AS identity_number,
    CONCAT(p.first_name, ' ', COALESCE(p.second_name, ''), ' ', p.last_name, ' ', p.second_last_name) AS full_name,
    p.email_primary,
    p.email_secondary,
    u.password,
    ph.phone_number AS primary_phone,
    ur.user_status AS student_status
FROM users u
JOIN people p ON u.people_id = p.id
JOIN identity_types it ON p.document_type_id = it.id
LEFT JOIN phones ph ON ph.people_id = p.id AND ph.priority = _binary 0x31 -- teléfono principal
JOIN user_roles ur ON u.id = ur.users_id
JOIN roles r ON ur.roles_id = r.id
WHERE r.name = 'Estudiante' 
;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
