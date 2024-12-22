-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.40 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para incubadora
CREATE DATABASE IF NOT EXISTS `incubadora` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `incubadora`;

-- Volcando estructura para tabla incubadora.alumno
CREATE TABLE IF NOT EXISTS `alumno` (
  `no_control` char(8) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `carrera` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `correo_institucional` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `telefono` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `semestre` int NOT NULL DEFAULT '0',
  `fecha_agregado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`no_control`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla incubadora.alumno: ~192 rows (aproximadamente)
INSERT INTO `alumno` (`no_control`, `nombre`, `carrera`, `correo_institucional`, `telefono`, `semestre`, `fecha_agregado`) VALUES
	('00000001', 'Francisco Alberto Pérez Hernández', 'Ingeniería Electrónica', 'L000000002@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:10:40'),
	('01270526', 'Julio Cesar Martinez Morgan', 'Doctorado en Ciencias de la Ingeniería', 'D01270526@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 12:56:56'),
	('01270777', 'Luis Enrique Guillen Ruiz', 'Doctorado en Ciencias de la Ingeniería', 'D01270777@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 13:00:09'),
	('09270506', 'Pedro Marcos Velasco Bolom', 'Maestría en Administración', 'M09270506@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:48:52'),
	('10270792', 'Jaime Jiménez Pérez', 'Maestría en Ciencias en Ingeniería Mecatrónica', 'M10270792@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:50:56'),
	('11270019', 'María Candelaria Morales Ruiz', 'Doctorado en Ciencias de los Alimentos y Biotecnología', 'D11270019@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 07:37:56'),
	('13270738', 'Fermín Jonapa Hernández', 'Doctorado en Ciencias de los Alimentos y Biotecnología', 'D13270738@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 07:38:13'),
	('14270621', 'Marco Antonio Ramírez Morales', 'Maestría en Administración', 'M14270621@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:49:06'),
	('14270826', 'Luis Adan Sanchez Mejia', 'Maestría en Ciencias en Ingeniería Mecatrónica', 'M14270826@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:17:50'),
	('15270437', 'Luis Daniel López Cancino', 'Maestría en Ciencias en Ingeniería Mecatrónica', 'M15270437@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:50:33'),
	('15270455', 'José Enrique Moreno Araujo', 'Maestría en Ciencias en Ingeniería Mecatrónica', 'M15270455@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:19:37'),
	('15270885', 'Valeria Nava Gómez', 'Maestría en Ciencias en Ingeniería Mecatrónica', 'M15270885@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:20:19'),
	('16270670', 'Leonardo Gomez Coronel', 'Doctorado en Ciencias de la Ingeniería', 'D16270670@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:18:27'),
	('16270697', 'Ramón Armando Najera Cortois', 'Doctorado en Ciencias de la Ingeniería', 'D16270697@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:47:19'),
	('16270739', 'Ramiro de Jesús Balam López', 'Ingeniería en Sistemas Computacionales', 'L16270739@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:54:47'),
	('16270882', 'Wendy Paulet Moreno Cordova', 'Maestría en Ciencias en Ingeniería Bioquimica', 'M16270882@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:21:25'),
	('17270537', 'Leslie García Gálvez', 'Maestría en Ciencias en Ingeniería Bioquimica', 'M17270537@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:21:06'),
	('17270661', 'Andres Eduardo De Paz Martinez', 'Maestría en Ciencias en Ingeniería Mecatrónica', 'M17270661@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 12:56:14'),
	('17270901', 'Perla Judith Vázquez González', 'Maestría en Ciencias en Ingeniería Mecatrónica', 'M17270901@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:20:51'),
	('18270127', 'Noé Moisés Luna Aguilar', 'Doctorado en Ciencias de la Ingeniería', 'D18270127@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:50:09'),
	('18270140', 'Dalia Margarita Ferrer Sánchez', 'Maestría en Ciencias en Ingeniería Bioquimica', 'M18270140@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-29 11:47:16'),
	('18270404', 'Fernando Rafael Martínez Algarin', 'Maestría en Ciencias en Ingeniería Mecatrónica', 'M18270404@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 11:01:17'),
	('18270870', 'Daniel de Jesús Ballinas Guerra', 'Ingeniería en Sistemas Computacionales', 'L18270870@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:55:29'),
	('18270876', 'Carlos Alexis López Ramírez', 'Ingeniería en Sistemas Computacionales', 'L18270876@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:44:32'),
	('18270887', 'Yovani Santiz Jiménez', 'Ingeniería en Sistemas Computacionales', 'L18270887@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:43:58'),
	('18270914', 'Eduardo Asunción Pérez Hernández', 'Ingeniería en Sistemas Computacionales', 'L18270914@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:54:22'),
	('19270126', 'Emma Yuridia de la Cruz Alamilla', 'Ingeniería en Sistemas Computacionales', 'L19270126@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:41:02'),
	('19270131', 'Carlos Daniel Estrada Juárez', 'Ingeniería en Sistemas Computacionales', 'L19270131@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:42:12'),
	('19270145', 'Jesús Emmanuel Herrera de los Santos', 'Ingeniería en Sistemas Computacionales', 'L19270145@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:55:08'),
	('19270167', 'Erick Martín Pereyra Herrera', 'Ingeniería en Sistemas Computacionales', 'L19270167@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:41:50'),
	('19270168', 'Heriberto Asunción Pérez Cabrera', 'Ingeniería en Sistemas Computacionales', 'L19270168@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:42:52'),
	('19270175', 'José Luis Sánchez Roman', 'Ingeniería en Sistemas Computacionales', 'L19270175@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:57:51'),
	('19270183', 'Óscar Gerardo Vázquez López', 'Ingeniería en Sistemas Computacionales', 'L19270183@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:43:14'),
	('19270529', 'Pablo Moisés Núñez Pérez', 'Ingeniería Bioquímica', 'L19270529@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:30:18'),
	('19270550', 'Maritza Castillejo Santiago', 'Ingeniería en Sistemas Computacionales', 'L19270550@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:44:56'),
	('19270555', 'Pedro Cristian Espinosa Espinosa', 'Ingeniería en Sistemas Computacionales', 'L19270555@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:45:23'),
	('19270579', 'Ana Gabriela Sánchez Maciel', 'Ingeniería en Sistemas Computacionales', 'L19270579@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:57:29'),
	('20270034', 'Valeria Mumenthey Zorrilla', 'Ingeniería Bioquímica', 'L20270034@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:30:45'),
	('20270236', 'Nelson Fabián Cabrera Vázquez', 'Ingeniería en Sistemas Computacionales', 'L20270236@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:35:30'),
	('20270241', 'Francisco Eduardo García Cifuentes', 'Ingeniería en Sistemas Computacionales', 'L20270241@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:37:31'),
	('20270242', 'Carlos Alexander Gómez Santis', 'Ingeniería en Sistemas Computacionales', 'L20270242@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:37:05'),
	('20270243', 'Gerardo Guzmán Silvestre', 'Ingeniería en Sistemas Computacionales', 'L20270243@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:38:42'),
	('20270246', 'Jorge Luis Hernández Ruíz', 'Ingeniería en Sistemas Computacionales', 'L20270246@tuxtla.tecnm.mx', '9611234567', 3, '2024-06-01 13:37:56'),
	('20270278', 'Luis Alejandro García Hernández', 'Ingeniería en Sistemas Computacionales', 'L20270278@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:25:38'),
	('20270284', 'Daniel Hernández Dominguez', 'Ingeniería en Sistemas Computacionales', 'L20270284@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:26:27'),
	('20270341', 'Manuel Alejandro Juárez Alfaro', 'Ingeniería en Gestión Empresarial', 'L20270341@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-29 11:43:16'),
	('20270435', 'Lenin Garrido Ortiz', 'Ingeniería Bioquímica', 'L20270435@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-29 11:42:01'),
	('20270449', 'Jocelyn Ivette Ozuna Moreno', 'Ingeniería Bioquímica', 'L20270449@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-29 11:42:20'),
	('20270470', 'Mazdal Flores Farrera', 'Ingeniería Química', 'L20270470@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:35:08'),
	('20270485', 'Luis Antonio Márquez Alonso', 'Ingeniería Química', 'L20270485@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:49:47'),
	('20270517', 'Luis Roberto Hernández Gómez', 'Ingeniería Bioquímica', 'L20270517@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:32:05'),
	('20270617', 'Ludy Valentín Gordillo Vázquez', 'Ingeniería Electrónica', 'L20270617@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:59:43'),
	('20270621', 'Alan Lopez Orozco', 'Ingeniería Electrónica', 'L20270621@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:33:20'),
	('20270656', 'Arturo Gael Lievano López', 'Ingeniería Electrónica', 'L20270656@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 07:45:26'),
	('20270670', 'Evelyn Ariadna Viza Alegría', 'Ingeniería Electrónica', 'L20270670@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:27:04'),
	('20270672', 'Mariana Estefanía Cortes Lopez', 'Ingeniería en Logística', 'L20270672@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:22:55'),
	('20270683', 'Betzabe Yamille Perez Corzo', 'Ingeniería en Logística', 'L20270683@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:21:59'),
	('20270750', 'Ximena Lizeth Sánchez López', 'Ingeniería Industrial', 'L20270750@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:31:40'),
	('20270760', 'Oscar Adrián Ballinas Moguel', 'Ingeniería en Sistemas Computacionales', 'L20270760@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:58:42'),
	('20270769', 'Jose Alexander Gomez Lopez', 'Ingeniería en Sistemas Computacionales', 'L20270769@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:39:23'),
	('20270794', 'Jesús Emmanuel Barrios Gutiérrez', 'Ingeniería en Sistemas Computacionales', 'L20270794@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 18:47:04'),
	('20270807', 'Fabian Hernandez Zambrano', 'Ingeniería en Gestión Empresarial', 'L20270807@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:40:39'),
	('20270812', 'Efraín Marroquín Pérez', 'Ingeniería en Sistemas Computacionales', 'L20270812@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:31:11'),
	('20270852', 'Yetkkan Ochoa Ovando', 'Ingeniería Mecánica', 'L20270852@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:29:44'),
	('20270915', 'Valentina Garduño Fernández', 'Ingeniería en Sistemas Computacionales', 'L20270915@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:40:15'),
	('20270947', 'Héctor Manuel Hernández Díaz', 'Ingeniería en Gestión Empresarial', 'L20270947@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:35:28'),
	('21270045', 'Valeria Ramon Camacho', 'Ingeniería Eléctrica', 'L21270045@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:22:26'),
	('21270077', 'Karla Salim López Córdova', 'Ingeniería Química', 'L21270077@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 07:44:34'),
	('21270089', 'Santos Pérez Hernández', 'Ingeniería Electrónica', 'L21270089@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:47:43'),
	('21270090', 'Pablo César Ruiz Valencia', 'Ingeniería Electrónica', 'L21270090@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:42:00'),
	('21270094', 'José Favian Cruz Pérez', 'Ingeniería en Logística', 'L21270094@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:24:25'),
	('21270115', 'Alejandra Isabel Núñez Sánchez', 'Ingeniería en Gestión Empresarial', 'L21270115@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:50:41'),
	('21270120', 'Camila Ruiz Sánchez', 'Ingeniería en Gestión Empresarial', 'L21270120@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-31 12:28:17'),
	('21270123', 'Diana Yanet Yat Cu', 'Ingeniería en Gestión Empresarial', 'L21270123@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:50:01'),
	('21270128', 'Adriana Carolina Dardón López', 'Ingeniería en Gestión Empresarial', 'L21270128@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-31 12:28:38'),
	('21270135', 'Diana Belén Martínez Pérez', 'Ingeniería en Gestión Empresarial', 'L21270135@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:39:14'),
	('21270141', 'María Fernanda Salvaterra Yeo', 'Ingeniería en Gestión Empresarial', 'L21270141@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 18:47:45'),
	('21270151', 'Pablo Jesús Gómez Pérez', 'Ingeniería en Sistemas Computacionales', 'L21270151@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:59:05'),
	('21270166', 'Armando de Jesús Arizmendis Nucamendi', 'Ingeniería en Sistemas Computacionales', 'L21270166@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 18:48:50'),
	('21270175', 'Ángel de Jesús López', 'Ingeniería en Sistemas Computacionales', 'L21270175@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-31 12:30:55'),
	('21270178', 'Luis Fernando Niño Morales', 'Ingeniería en Sistemas Computacionales', 'L21270178@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 18:48:26'),
	('21270188', 'Yair Alejandro Ruiz Rodriguez', 'Ingeniería en Sistemas Computacionales', 'L21270188@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:39:46'),
	('21270193', 'Angel Eduardo Solano Gamboa', 'Ingeniería en Sistemas Computacionales', 'L21270193@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:32:37'),
	('21270229', 'José Gustavo Molina', 'Ingeniería Mecánica', 'L21270229@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:30:07'),
	('21270241', 'Yadira Solis Serrano', 'Ingeniería Mecánica', 'L21270241@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:28:01'),
	('21270244', 'Diana Ofelia Alfaro Pérez', 'Ingeniería Industrial', 'L21270244@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-29 11:45:53'),
	('21270279', 'Luis Julián Vicente Gómez', 'Ingeniería Industrial', 'L21270279@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-29 11:47:02'),
	('21270280', 'Alejandro Zenteno Velasco', 'Ingeniería Industrial', 'L21270280@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-29 11:46:49'),
	('21270339', 'Joseline Darlene Hernández Ramírez', 'Ingeniería en Gestión Empresarial', 'L21270339@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:32:59'),
	('21270375', 'Emmanuel De Jesús De La Rosa De Paz', 'Ingeniería en Gestión Empresarial', 'L21270375@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:59:56'),
	('21270378', 'Kimberly Itzel Figueroa Torres', 'Ingeniería en Gestión Empresarial', 'L21270378@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:26:06'),
	('21270381', 'Héctor Gerardo García Escobar', 'Ingeniería en Gestión Empresarial', 'L21270381@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:34:12'),
	('21270382', 'Brayan De Jesús García Vazquez', 'Ingeniería en Gestión Empresarial', 'L21270382@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:30:14'),
	('21270383', 'Heidi Guadalupe Gómez Cruz', 'Ingeniería en Gestión Empresarial', 'L21270383@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 11:34:35'),
	('21270387', 'Ana Lucía Hernández Guruga', 'Ingeniería en Gestión Empresarial', 'L21270387@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 11:24:10'),
	('21270389', 'Kevin Bryan López Matia', 'Ingeniería en Gestión Empresarial', 'L21270389@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:26:06'),
	('21270394', 'Cesar Antonio Mayorga Moreno', 'Ingeniería en Gestión Empresarial', 'L21270394@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 13:01:38'),
	('21270396', 'Carlo Emiliano Paredes Armenta', 'Ingeniería en Gestión Empresarial', 'L21270396@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-31 12:24:08'),
	('21270400', 'Carolina Ramírez Estrada', 'Ingeniería en Gestión Empresarial', 'L21270400@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 13:02:24'),
	('21270401', 'Elsy Michelle Ramírez Jiménez', 'Ingeniería en Gestión Empresarial', 'L21270401@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 11:00:20'),
	('21270404', 'Frida Marisol Trejo Trujillo', 'Ingeniería en Gestión Empresarial', 'L21270404@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:22:46'),
	('21270410', 'Itxel Alejandra Coello García', 'Ingeniería en Gestión Empresarial', 'L21270410@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:57:52'),
	('21270414', 'Mónica de los Ángeles Díaz Flores', 'Ingeniería en Gestión Empresarial', 'L21270414@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-31 12:31:59'),
	('21270416', 'Triana Camila Fernández Lopez', 'Ingeniería en Gestión Empresarial', 'L21270416@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:37:21'),
	('21270417', 'Francisco Yael Gálvez Ruiz', 'Ingeniería en Gestión Empresarial', 'L21270417@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-29 11:43:03'),
	('21270422', 'Jesús Alejandro Gordillo Maza', 'Ingeniería en Gestión Empresarial', 'L21270422@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-31 12:36:14'),
	('21270425', 'Jonathan de Jesús Hernández López', 'Ingeniería en Gestión Empresarial', 'L21270425@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 11:21:09'),
	('21270428', 'Dennisse del Carmen García López', 'Ingeniería en Gestión Empresarial', 'L21270428@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-31 12:22:22'),
	('21270433', 'Fausto Horacio Pérez Vázquez', 'Ingeniería en Gestión Empresarial', 'L21270433@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:21:59'),
	('21270434', 'Yazmin Natali Pérez Ventura', 'Ingeniería en Gestión Empresarial', 'L21270434@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:21:35'),
	('21270440', 'Fátima Guadalupe Saturno Hernández', 'Ingeniería en Gestión Empresarial', 'L21270440@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-31 12:24:38'),
	('21270442', 'Candy Guadalupe Vázquez Robles', 'Ingeniería en Gestión Empresarial', 'L21270442@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-29 11:46:28'),
	('21270450', 'Alexia Guadalupe Esquipulas Salinas', 'Ingeniería en Gestión Empresarial', 'L21270450@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:48:49'),
	('21270452', 'Christian Michelle Gálvez Zamorano', 'Ingeniería en Gestión Empresarial', 'L21270452@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:22:25'),
	('21270455', 'Hannia González Cerna', 'Ingeniería en Gestión Empresarial', 'L21270455@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:59:27'),
	('21270458', 'Neli Patricia Hidalgo Méndez', 'Ingeniería en Gestión Empresarial', 'L21270458@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-31 12:23:33'),
	('21270461', 'Beatriz Adriana López Vicente', 'Ingeniería en Gestión Empresarial', 'L21270461@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:11:33'),
	('21270462', 'Mari Elena Marroquin Navarrete', 'Ingeniería en Gestión Empresarial', 'L21270462@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 13:01:04'),
	('21270466', 'Carlos Ulises Mendoza Toledo', 'Ingeniería en Gestión Empresarial', 'L21270466@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-29 11:44:57'),
	('21270467', 'Elva Mariana Moreno Martínez', 'Ingeniería en Gestión Empresarial', 'L21270467@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:29:14'),
	('21270468', 'Alondra Marivi Natarén Pérez', 'Ingeniería en Gestión Empresarial', 'L21270468@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:33:34'),
	('21270469', 'Viridiana Yandeli Nataren Santiago', 'Ingeniería en Gestión Empresarial', 'L21270469@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 13:02:06'),
	('21270470', 'Alondra Lisseth Ochoa Villareal', 'Ingeniería en Gestión Empresarial', 'L21270470@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:49:27'),
	('21270472', 'Marvin Javier Pérez Astudillo', 'Ingeniería en Gestión Empresarial', 'L21270472@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:24:47'),
	('21270474', 'Taré Fernanda Ramirez Dolores', 'Ingeniería en Gestión Empresarial', 'L21270474@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:31:37'),
	('21270505', 'Óscar Salvador Trujillo Abarca', 'Ingeniería Industrial', 'L21270505@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-29 11:45:20'),
	('21270506', 'Endrich Anthony Trujillo Bautista', 'Ingeniería Industrial', 'L21270506@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:29:46'),
	('21270527', 'Jesús Eduardo León Cancino', 'Ingeniería Industrial', 'L21270527@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 11:29:52'),
	('21270530', 'Gloria Magdaleno Sánchez', 'Ingeniería Industrial', 'L21270530@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 11:28:55'),
	('21270540', 'Amairany Suriano Natarén', 'Ingeniería Industrial', 'L21270540@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 11:28:14'),
	('21270667', 'Osvani Daniel Flores Hernández', 'Ingeniería en Gestión Empresarial', 'L21270667@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:25:50'),
	('21270703', 'Abel Domínguez Romero', 'Ingeniería Mecánica', 'L21270703@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:34:48'),
	('21270706', 'Sahian García Gálvez', 'Ingeniería Mecánica', 'L21270706@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:34:33'),
	('21270723', 'Belén Iveth Ricárdez Uribe', 'Ingeniería Mecánica', 'L21270723@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:37:58'),
	('21270761', 'Valeria Melissa Camacho Verdín', 'Ingeniería Bioquímica', 'L21270761@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-29 11:43:36'),
	('21270787', 'Renata Isabel Zaldivar Salinas', 'Ingeniería Bioquímica', 'L21270787@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:29:14'),
	('21270821', 'Lucía Del Carmen Vivar Arias', 'Ingeniería Bioquímica', 'L21270821@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:49:07'),
	('21270902', 'Erick Jhovanny Gomez Lopez', 'Ingeniería Electrónica', 'L21270902@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 13:02:47'),
	('21270922', 'Alan Gabriel Viamonte Cancino', 'Ingeniería Electrónica', 'L21270922@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-31 12:37:24'),
	('21270928', 'Adrián Avendaño Herrera', 'Ingeniería Electrónica', 'L21270928@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 07:45:50'),
	('21270929', 'Ronaldo Donacimento Balcázar de la Cruz', 'Ingeniería Electrónica', 'L21270929@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-29 11:46:09'),
	('21270930', 'Angel Emmanuel Ballinas Mendez', 'Ingeniería Electrónica', 'L21270930@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 13:04:39'),
	('21270934', 'Diego Cruz Rodriguez', 'Ingeniería Electrónica', 'L21270934@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:28:14'),
	('21270937', 'Angel Jhair Del Porte Lopez', 'Ingeniería Electrónica', 'L21270937@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 13:05:07'),
	('21270938', 'Edder Vladimir Diaz Gutiérrez', 'Ingeniería Electrónica', 'L21270938@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:28:38'),
	('21270951', 'Gerardo Jesús Ramos Gómez', 'Ingeniería Electrónica', 'L21270951@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:27:23'),
	('21270954', 'Wendy Jaqueline Santiz Aquino', 'Ingeniería Electrónica', 'L21270954@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:29:53'),
	('21270963', 'Alejandra del Rocío Chanona Gómez', 'Ingeniería en Logística', 'L21270963@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-31 12:27:53'),
	('21270985', 'Aracely Trinidad Robles', 'Ingeniería en Logística', 'L21270985@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-31 12:31:17'),
	('21271144', 'Alejandro Alvarado Algarin', 'Doctorado en Ciencias de la Ingeniería', 'D21271144@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:33:09'),
	('21271150', 'Etna Guadalupe Courtois Sánchez', 'Ingeniería Industrial', 'L21271150@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 07:43:15'),
	('21271151', 'Cristina del Carmen Espinosa Espinosa', 'Ingeniería Industrial', 'L21271151@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 07:39:51'),
	('21271156', 'Nadia Rubí Najera Ramírez', 'Ingeniería Industrial', 'L21271156@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 07:38:28'),
	('21271160', 'Erika Yarith Solano Vázquez', 'Ingeniería Industrial', 'L21271160@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 07:40:04'),
	('21271166', 'Álvaro Darwin Cruz Nafate', 'Ingeniería en Sistemas Computacionales', 'L21271166@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 07:40:20'),
	('21271248', 'Alexia Montserrath Alfaro Cordero', 'Ingeniería Bioquímica', 'L21271248@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-29 11:45:35'),
	('22270089', 'Arturo Heberto Luna Gutierrez', 'Ingeniería Mecánica', 'L22270089@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:25:43'),
	('22270092', 'Juan Jose Meléndez Díaz', 'Ingeniería Mecánica', 'L22270092@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:25:10'),
	('22270106', 'Marco Felix Vazquez Vazquez', 'Ingeniería Mecánica', 'L22270106@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:26:28'),
	('22270176', 'Wendy Citlalli Corzo Cantoral', 'Ingeniería en Gestión Empresarial', 'L22270176@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:28:42'),
	('22270190', 'Marisol Vega Villanueva', 'Ingeniería en Gestión Empresarial', 'L22270190@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 07:46:43'),
	('22270194', 'Jeretzy Zarate Toledo', 'Ingeniería en Gestión Empresarial', 'L22270194@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 07:47:13'),
	('22270200', 'Marijose Álvarez Hernández', 'Ingeniería Industrial', 'L22270200@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:23:18'),
	('22270244', 'Guillermo Solís Hernández', 'Ingeniería Electrónica', 'L22270244@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:29:33'),
	('22270349', 'Caleb Fuentes Rodríguez', 'Ingeniería Bioquímica', 'L22270349@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-29 11:43:57'),
	('22270354', 'Luis Armando Velázquez López', 'Ingeniería Eléctrica', 'L22270354@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:27:14'),
	('22270355', 'Isaac Montejo Díaz', 'Ingeniería Eléctrica', 'L22270355@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:28:41'),
	('22270357', 'Jorge Alexander Pérez Solano', 'Ingeniería Eléctrica', 'L22270357@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:50:14'),
	('22270367', 'Roger Antonio Díaz Maza', 'Ingeniería Eléctrica', 'L22270367@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:50:27'),
	('22270371', 'Gabriela Silva Gaspar', 'Ingeniería Eléctrica', 'L22270371@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:26:43'),
	('22270373', 'Carlos Neptali Ramos Hernández', 'Ingeniería Eléctrica', 'L22270373@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:49:50'),
	('22270382', 'Juan Francisco Peña Magaña', 'Ingeniería Eléctrica', 'L22270382@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:25:21'),
	('22270435', 'Rodrigo Zúñiga Castro', 'Ingeniería en Sistemas Computacionales', 'L22270435@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 11:25:43'),
	('22270446', 'Rodrigo de Jesús Interiano Zúñiga', 'Ingeniería en Sistemas Computacionales', 'L22270446@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-31 12:29:54'),
	('22270512', 'Cristian Vazquez Vázquez Gómez', 'Ingeniería en Sistemas Computacionales', 'L22270512@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 11:27:43'),
	('22270597', 'Montserrat Gordillo Tejada', 'Ingeniería Industrial', 'L22270597@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:32:09'),
	('22270600', 'Ilse Abigail Martinez Rojas', 'Ingeniería Industrial', 'L22270600@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:28:22'),
	('22270674', 'Sergio Iván Díaz Hernández', 'Ingeniería Electrónica', 'L22270674@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-30 11:29:23'),
	('22270684', 'Evelyn Del Carmen Bailón Diaz', 'Ingeniería en Gestión Empresarial', 'L22270684@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:35:35'),
	('22270738', 'Abril Bastida Laguna', 'Ingeniería en Gestión Empresarial', 'L22270738@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 15:51:44'),
	('22270856', 'María Fernanda Moguel Sánchez', 'Ingeniería en Logística', 'L22270856@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-31 12:30:19'),
	('22271094', 'Jose Francisco Molina Santiago', 'Maestría en Ciencias en Ingeniería Mecatrónica', 'M22271094@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:39:00'),
	('23270361', 'Jorge Alexis Velázquez Lara', 'Ingeniería Electrónica', 'L23270361@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:11:09'),
	('23270367', 'Jose Luis López Saynes', 'Maestría en Ciencias en Ingeniería Mecatrónica', 'M23270367@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 22:51:15'),
	('23270369', 'Jesús Pérez Toalá', 'Doctorado en Ciencias de la Ingeniería', 'D23270369@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:49:37'),
	('23270705', 'Iván Alberto Minor Esquinca', 'Ingeniería Mecatrónica', 'L23270705@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 11:00:08'),
	('23270980', 'Jesús Alexander Morales Meza', 'Ingeniería Electrónica', 'L23270980@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 11:28:46'),
	('23271370', 'Lianet Consuegra Morales', 'Maestría en Ciencias en Ingeniería Mecatrónica', 'M23271370@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 11:33:40'),
	('23271371', 'David Fernández Rodríguez', 'Maestría en Ciencias en Ingeniería Mecatrónica', 'M23271371@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-27 12:20:01'),
	('23271374', 'Felisa Vanessa Lopez Pineda', 'Doctorado en Ciencias de la Ingeniería', 'D23271374@tuxtla.tecnm.mx', '9611234567', 3, '2024-09-01 12:56:37'),
	('78270406', 'José Ángel Zepeda Hernández', 'Doctorado en Ciencias de la Ingeniería', 'D78270406@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 11:01:27'),
	('97270383', 'Martha Luz Paniagua Chávez', 'Doctorado en Ciencias de la Ingeniería', 'D97270383@tuxtla.tecnm.mx', '9611234567', 3, '2024-08-28 10:51:58');

-- Volcando estructura para tabla incubadora.alumno_semestre
CREATE TABLE IF NOT EXISTS `alumno_semestre` (
  `idSemestre` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`idSemestre`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla incubadora.alumno_semestre: ~10 rows (aproximadamente)
INSERT INTO `alumno_semestre` (`idSemestre`, `nombre`) VALUES
	(1, 'Primer semestre'),
	(2, 'Segundo semestre'),
	(3, 'Tercer semestre'),
	(4, 'Cuarto semestre'),
	(5, 'Quinto semestre'),
	(6, 'Sexto semestre'),
	(7, 'Séptimo semestre'),
	(8, 'Octavo semestre'),
	(9, 'Noveno semestre'),
	(10, 'Décimo semestre');

-- Volcando estructura para tabla incubadora.asesor
CREATE TABLE IF NOT EXISTS `asesor` (
  `idAsesor` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `telefono` char(10) NOT NULL,
  `correo_electronico` varchar(50) NOT NULL,
  `fecha_agregado` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idAsesor`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla incubadora.asesor: ~53 rows (aproximadamente)
INSERT INTO `asesor` (`idAsesor`, `nombre`, `telefono`, `correo_electronico`, `fecha_agregado`) VALUES
	(2, 'Jorge William Figueroa Corzo', '9619876543', 'ejemplo@gmail.com', '2024-06-01 14:16:07'),
	(3, 'Jesús Carlos Sánchez Guzmán', '9619876543', 'ejemplo@gmail.com', '2024-06-01 14:16:28'),
	(4, 'Octavio Ariosto Ríos Tercero', '9619876543', 'ejemplo@gmail.com', '2024-06-01 14:16:54'),
	(5, 'Rosy Ilda Basave Torres', '9619876543', 'ejemplo@gmail.com', '2024-06-01 14:17:09'),
	(6, 'Nestor Antonio Morales Navarro', '9619876543', 'ejemplo@gmail.com', '2024-06-01 14:17:25'),
	(7, 'María Guadalupe Monjaras Velasco', '9619876543', 'ejemplo@gmail.com', '2024-06-01 14:17:49'),
	(8, 'Germán Ríos Toledo', '9619876543', 'ejemplo@gmail.com', '2024-06-01 14:18:03'),
	(9, 'Ciclalli Cabrera García', '9619876543', 'ejemplo@gmail.com', '2024-06-01 14:18:26'),
	(10, 'Francisco de Jesús Suárez Ruíz', '9619876543', 'ejemplo@gmail.com', '2024-06-01 14:18:46'),
	(11, 'Enrique Abel Sánchez Velázquez', '9619876543', 'ejemplo@gmail.com', '2024-06-01 14:19:03'),
	(12, 'Elfer Isaías Clemente Camacho', '9619876543', 'ejemplo@gmail.com', '2024-06-01 14:19:18'),
	(13, 'Miguel Arturo Vázquez Velázquez', '9619876543', 'ejemplo@gmail.com', '2024-06-01 14:19:35'),
	(14, 'Roberto Cruz Gordillo', '9619876543', 'ejemplo@gmail.com', '2024-06-01 14:19:47'),
	(15, 'Walter Torres Roblero', '9619876543', 'ejemplo@gmail.com', '2024-08-27 12:31:13'),
	(16, 'Yaneth Abril Espinoza Corzo', '9619876543', 'ejemplo@gmail.com', '2024-08-27 12:31:32'),
	(17, 'Ruth Madeine Roblero López', '9619876543', 'ejemplo@gmail.com', '2024-08-27 12:32:05'),
	(18, 'Álvaro Hernández Sol', '9619876543', 'ejemplo@gmail.com', '2024-08-27 12:32:22'),
	(19, 'José Humberto Castañón Gonzales', '9619876543', 'ejemplo@gmail.com', '2024-08-27 12:36:16'),
	(20, 'María Leticia Vázquez Ruíz', '9619876543', 'ejemplo@gmail.com', '2024-08-27 12:36:47'),
	(21, 'Alexis de Jesús López Trujillo', '9619876543', 'ejemplo@gmail.com', '2024-08-27 12:37:06'),
	(22, 'Sheyla Karina Flores Guirao', '9619876543', 'ejemplo@gmail.com', '2024-08-27 12:37:23'),
	(23, 'José Armando Fragoso Mandujano', '9619876543', 'ejemplo@gmail.com', '2024-08-27 12:37:42'),
	(24, 'Carlos Alberto Hernández Gutiérrez', '9619876543', 'ejemplo@gmail.com', '2024-08-27 12:37:57'),
	(25, 'Gustavo Méndez Lambarén', '9619876543', 'ejemplo@gmail.com', '2024-08-27 12:38:39'),
	(26, 'Miguel Ángel Lastra Pascacio', '9619876543', 'ejemplo@gmail.com', '2024-08-27 12:38:56'),
	(27, 'Lisandro Gutiérrez Gonzáles', '9619876543', 'ejemplo@gmail.com', '2024-08-27 12:39:10'),
	(28, 'Reiner Rincón Rosales', '9619876543', 'ejemplo@gmail.com', '2024-08-27 12:40:24'),
	(29, 'Eduardo Chandomi Castellanos', '9619876543', 'ejemplo@gmail.com', '2024-08-27 12:40:43'),
	(30, 'Naghieli De Jesús Pascasio Solorzano', '9619876543', 'ejemplo@gmail.com', '2024-08-28 11:02:54'),
	(31, 'Jorge Luis Camas Anzueto', '9619876543', 'ejemplo@gmail.com', '2024-08-29 11:01:02'),
	(32, 'Rubén Grajales Coutiño', '9619876543', 'ejemplo@gmail.com', '2024-08-29 11:01:15'),
	(34, 'Ildeberto de los Santos Ruíz', '9619876543', 'ejemplo@gmail.com', '2024-08-29 11:02:13'),
	(35, 'Maria Laura Porraz Ruiz', '9619876543', 'ejemplo@gmail.com', '2024-08-30 07:47:39'),
	(36, 'Adriana Meza León', '9619876543', 'ejemplo@gmail.com', '2024-08-30 07:48:00'),
	(37, 'Jorge Arturo Sarmiento Torres', '9619876543', 'ejemplo@gmail.com', '2024-08-30 07:48:11'),
	(38, 'Liliana Patricia Moreno Cancino', '9619876543', 'ejemplo@gmail.com', '2024-08-30 07:48:20'),
	(39, 'Maria Celina Lujan Hidalgo', '9619876543', 'ejemplo@gmail.com', '2024-08-30 07:48:26'),
	(40, 'Rodrigo Ferrer González', '9619876543', 'ejemplo@gmail.com', '2024-08-30 07:48:35'),
	(41, 'Katty Nallely Fuentes Castro', '9619876543', 'ejemplo@gmail.com', '2024-08-30 07:49:06'),
	(42, 'Romeo Alejandro Velasco La Flor', '9619876543', 'ejemplo@gmail.com', '2024-08-30 07:49:12'),
	(43, 'Jorge Iván Bermúdez Rodríguez', '9619876543', 'ejemplo@gmail.com', '2024-08-30 07:49:25'),
	(44, 'Ana María de la Rosa Flores', '9619876543', 'ejemplo@gmail.com', '2024-08-30 07:49:33'),
	(45, 'Marco Antonio Zuñiga Reyes', '9619876543', 'ejemplo@gmail.com', '2024-08-31 12:37:59'),
	(46, 'Juan Humberto Carpio Tovilla', '9619876543', 'ejemplo@gmail.com', '2024-08-31 12:38:08'),
	(47, 'Gilbert Francis Pérez García', '9619876543', 'ejemplo@gmail.com', '2024-08-31 12:38:15'),
	(50, 'Alfredo Gómez Meoño', '9619876543', 'ejemplo@gmail.com', '2024-09-01 11:56:12'),
	(51, 'Alonso Juarez Ontiveros', '9619876543', 'ejemplo@gmail.com', '2024-09-01 11:56:26'),
	(52, 'Salvador Hernández Garduza', '9619876543', 'ejemplo@gmail.com', '2024-09-01 11:56:40'),
	(53, 'Francisco Ronay López Estrada', '9619876543', 'ejemplo@gmail.com', '2024-09-01 11:57:02'),
	(55, 'Roberto Ibañez Cordova', '9619876543', 'ejemplo@gmail.com', '2024-09-01 22:52:09'),
	(56, 'Tania Carpio Reyes', '9619876543', 'ejemplo@gmail.com', '2024-09-01 22:52:39'),
	(57, 'Rony Obed Suchiapa Díaz', '9619876543', 'ejemplo@gmail.com', '2024-09-01 22:52:48'),
	(58, 'Samuel Gómez Peñate', '9611111111', 'ejemplos@gmail.com', '2024-09-01 22:52:58');

-- Volcando estructura para tabla incubadora.carrera
CREATE TABLE IF NOT EXISTS `carrera` (
  `clave` char(13) NOT NULL,
  `nombre` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fecha_agregado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`clave`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla incubadora.carrera: ~16 rows (aproximadamente)
INSERT INTO `carrera` (`clave`, `nombre`, `fecha_agregado`) VALUES
	('0000000000000', 'PENDIENTE', '2024-06-01 18:42:16'),
	('DALB-2010-12', 'Doctorado en Ciencias de los Alimentos y Biotecnología', '2024-08-28 10:42:27'),
	('DING-2010-13', 'Doctorado en Ciencias de la Ingeniería', '2024-08-28 10:42:09'),
	('IBQA-2010-207', 'Ingeniería Bioquímica', '2024-03-18 15:32:56'),
	('IELC-2010-211', 'Ingeniería Electrónica', '2024-03-18 15:32:10'),
	('IELE-2010-209', 'Ingeniería Eléctrica', '2024-03-18 15:32:35'),
	('IGEM-2009-201', 'Ingeniería en Gestión Empresarial', '2024-03-18 15:33:32'),
	('IIND-2010-227', 'Ingeniería Industrial', '2024-03-18 15:31:50'),
	('ILOG-2009-202', 'Ingeniería en Logística', '2024-03-18 15:36:22'),
	('IMCT-2010-229', 'Ingeniería Mecatrónica', '2024-03-18 15:29:54'),
	('IMEC-2010-228', 'Ingeniería Mecánica', '2024-03-18 15:31:03'),
	('IQUI-2010-232', 'Ingeniería Química', '2024-03-18 15:33:10'),
	('ISIC-2010-224', 'Ingeniería en Sistemas Computacionales', '2024-03-17 20:56:12'),
	('MCIBQ-2011-20', 'Maestría en Ciencias en Ingeniería Bioquimica', '2024-08-27 12:19:01'),
	('MCIMC-2011-21', 'Maestría en Ciencias en Ingeniería Mecatrónica', '2024-08-27 11:39:40'),
	('MPADM-2011-26', 'Maestría en Administración', '2024-08-28 10:47:58');

-- Volcando estructura para tabla incubadora.categoria
CREATE TABLE IF NOT EXISTS `categoria` (
  `idCategoria` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`idCategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla incubadora.categoria: ~8 rows (aproximadamente)
INSERT INTO `categoria` (`idCategoria`, `nombre`) VALUES
	(12, 'Cambio climático'),
	(13, 'Industria eléctrica y electrónica'),
	(14, 'Sector agroalimentario'),
	(15, 'Industrias creativas'),
	(16, 'Electromovilidad y ciudades inteligentes'),
	(17, 'Servicios para la salud'),
	(18, 'PENDIENTE'),
	(19, 'NODESS');

-- Volcando estructura para tabla incubadora.color
CREATE TABLE IF NOT EXISTS `color` (
  `idColor` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `clase` varchar(50) NOT NULL,
  PRIMARY KEY (`idColor`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla incubadora.color: ~8 rows (aproximadamente)
INSERT INTO `color` (`idColor`, `nombre`, `clase`) VALUES
	(1, 'Azul', 'primary'),
	(2, 'Verde', 'success'),
	(3, 'Rojo', 'danger'),
	(4, 'Amarillo', 'warning'),
	(5, 'Celeste', 'info'),
	(6, 'Gris', 'secondary'),
	(7, 'Gris claro', 'light'),
	(8, 'Gris oscuro', 'dark');

-- Volcando estructura para tabla incubadora.especialidades
CREATE TABLE IF NOT EXISTS `especialidades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla incubadora.especialidades: ~0 rows (aproximadamente)

-- Volcando estructura para tabla incubadora.etapas
CREATE TABLE IF NOT EXISTS `etapas` (
  `idEtapa` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `descripcion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `color` varchar(50) NOT NULL,
  PRIMARY KEY (`idEtapa`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla incubadora.etapas: ~4 rows (aproximadamente)
INSERT INTO `etapas` (`idEtapa`, `nombre`, `descripcion`, `color`) VALUES
	(1, 'Inicio', 'Proyecto en fase de inicio.', 'Verde'),
	(2, 'Desarrollo', 'Proyecto en fase de desarrollo.', 'Amarillo'),
	(3, 'Final', 'Proyecto en fase final.', 'Rojo'),
	(4, 'PENDIENTE', 'Proyecto pendiente de revisión o acción.', 'Gris');

-- Volcando estructura para tabla incubadora.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla incubadora.failed_jobs: ~0 rows (aproximadamente)

-- Volcando estructura para tabla incubadora.habilidad
CREATE TABLE IF NOT EXISTS `habilidad` (
  `idHabilidad` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `descripcion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`idHabilidad`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla incubadora.habilidad: ~0 rows (aproximadamente)

-- Volcando estructura para tabla incubadora.habilidad_asesor
CREATE TABLE IF NOT EXISTS `habilidad_asesor` (
  `idHabilidad` int unsigned NOT NULL,
  `idAsesor` int NOT NULL,
  KEY `FK_habilidad_asesor_habilidad` (`idHabilidad`) USING BTREE,
  KEY `FK_habilidad_asesor_asesor` (`idAsesor`) USING BTREE,
  CONSTRAINT `FK_habilidad_asesor_asesor` FOREIGN KEY (`idAsesor`) REFERENCES `asesor` (`idAsesor`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_habilidad_asesor_habilidad` FOREIGN KEY (`idHabilidad`) REFERENCES `habilidad` (`idHabilidad`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla incubadora.habilidad_asesor: ~0 rows (aproximadamente)

-- Volcando estructura para tabla incubadora.mentor
CREATE TABLE IF NOT EXISTS `mentor` (
  `idMentor` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `fecha_agregado` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idMentor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla incubadora.mentor: ~0 rows (aproximadamente)

-- Volcando estructura para tabla incubadora.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla incubadora.migrations: ~6 rows (aproximadamente)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2014_10_12_100000_create_password_resets_table', 1),
	(4, '2019_08_19_000000_create_failed_jobs_table', 1),
	(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(6, '2024_12_17_174702_create_permission_tables', 1);

-- Volcando estructura para tabla incubadora.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla incubadora.model_has_permissions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla incubadora.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla incubadora.model_has_roles: ~2 rows (aproximadamente)
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(2, 'App\\Models\\User', 2);

-- Volcando estructura para tabla incubadora.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla incubadora.password_resets: ~0 rows (aproximadamente)

-- Volcando estructura para tabla incubadora.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla incubadora.password_reset_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla incubadora.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla incubadora.permissions: ~5 rows (aproximadamente)
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'ver proyectos', 'web', '2024-12-19 06:36:56', '2024-12-19 06:36:56'),
	(2, 'crear proyectos', 'web', '2024-12-19 06:36:56', '2024-12-19 06:36:56'),
	(3, 'editar proyectos', 'web', '2024-12-19 06:36:56', '2024-12-19 06:36:56'),
	(4, 'eliminar proyectos', 'web', '2024-12-19 06:36:56', '2024-12-19 06:36:56'),
	(5, 'gestionar usuarios', 'web', '2024-12-19 06:36:56', '2024-12-19 06:36:56');

-- Volcando estructura para tabla incubadora.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla incubadora.personal_access_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla incubadora.proyecto
CREATE TABLE IF NOT EXISTS `proyecto` (
  `clave_proyecto` char(15) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `nombre_descriptivo` varchar(100) NOT NULL,
  `descripcion` varchar(800) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `categoria` int NOT NULL,
  `tipo` int NOT NULL,
  `etapa` int DEFAULT '4',
  `video` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'No',
  `area_aplicacion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `naturaleza_tecnica` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `objetivo` varchar(600) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fecha_agregado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`clave_proyecto`),
  KEY `FK_proyecto_categoria` (`categoria`),
  KEY `FK_proyecto_tipo` (`tipo`),
  CONSTRAINT `FK_proyecto_categoria` FOREIGN KEY (`categoria`) REFERENCES `categoria` (`idCategoria`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_proyecto_tipo` FOREIGN KEY (`tipo`) REFERENCES `tipo` (`idTipo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla incubadora.proyecto: ~49 rows (aproximadamente)
INSERT INTO `proyecto` (`clave_proyecto`, `nombre`, `nombre_descriptivo`, `descripcion`, `categoria`, `tipo`, `etapa`, `video`, `area_aplicacion`, `naturaleza_tecnica`, `objetivo`, `fecha_agregado`) VALUES
	('0000000000001', 'NaturArma', 'Sin asignar', 'NaturArma', 12, 12, 2, 'https://www.youtube.com/watch?v=fOW8Y09GVek', 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-05-30 11:50:56'),
	('0000000000002', 'BioGe', 'Sin asignar', 'BioGe', 12, 12, 1, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-05-30 11:51:27'),
	('0000000000003', 'BIOPELL', 'Sin asignar', 'BIOPELL', 12, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-05-30 11:51:41'),
	('0000000000004', 'Water Save', 'Sin asignar', 'Water Save', 12, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-05-30 11:52:07'),
	('0000000000005', 'Purificador de agua ambiental', 'Sin asignar', 'Purificador de agua ambiental', 12, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-05-30 11:54:40'),
	('0000000000006', 'Anisof', 'Sin asignar', 'Anisof', 12, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-05-30 11:56:55'),
	('0000000000007', 'EVIE', 'Sin asignar', 'EVIE', 12, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-05-30 11:57:47'),
	('0000000000008', 'Ecokre', 'Sin asignar', 'Ecokre', 12, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-05-30 11:58:46'),
	('0000000000009', 'L - UV', 'Sin asignar', 'L UV', 12, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-05-30 11:59:36'),
	('0000000000010', 'ARQMX 5000', 'Sin asignar', 'ARQMX 5000', 13, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 14:20:54'),
	('0000000000011', 'SPACE 5000', 'Sin asignar', 'SPACE 5000', 13, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 14:21:27'),
	('0000000000012', 'SEPEMEX', 'Sin asignar', 'SEPEMEX', 13, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 14:21:55'),
	('0000000000013', 'PHYTOBOT', 'Sin asignar', 'PHYTOBOT', 13, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 14:22:31'),
	('0000000000014', 'OptTemp', 'Sin asignar', 'OptTemp', 13, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 14:22:54'),
	('0000000000015', 'Robot Silar', 'Sin asignar', 'Robot Silar', 13, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 14:23:12'),
	('0000000000016', 'Tux AuNat', 'Sin asignar', 'Tux AuNat', 13, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:09:58'),
	('0000000000017', 'RA - AH', 'Sin asignar', 'RA AH', 16, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:16:00'),
	('0000000000018', 'NeuProc', 'Sin asignar', 'NeuProc', 16, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:16:24'),
	('0000000000019', 'Snappy', 'Sin asignar', 'Snappy', 16, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:17:02'),
	('0000000000020', 'SmartFuel', 'Sin asignar', 'SmartFuel', 16, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:17:22'),
	('0000000000021', 'DigiTurix', 'Sin asignar', 'DigiTurix', 16, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:17:41'),
	('0000000000022', 'T - Rover', 'Sin asignar', 'T Rover', 16, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:17:57'),
	('0000000000023', 'Ruta Mexicana', 'Sin asignar', 'Ruta Mexicana', 15, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:13:10'),
	('0000000000024', 'Raccoon Didactics', 'Sin asignar', 'Raccoon Didactics', 15, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:13:38'),
	('0000000000025', 'Maestro', 'Sin asignar', 'Maestro', 15, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:14:03'),
	('0000000000026', 'Culturizatec', 'Sin asignar', 'Culturizatec', 15, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:14:29'),
	('0000000000027', 'SURCA', 'Sin asignar', 'SURCA', 15, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:15:22'),
	('0000000000028', 'VISIONARIA', 'Sin asignar', 'VISIONARIA', 15, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:15:40'),
	('0000000000029', 'Balam Selecto', 'Sin asignar', 'Balam Selecto', 14, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:10:24'),
	('0000000000030', 'Salsa Picante La Brava', 'Sin asignar', 'Salsa Picante La Brava', 14, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:11:10'),
	('0000000000031', 'Fungi - Tec', 'Sin asignar', 'Seed Matic', 14, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:11:35'),
	('0000000000032', 'NixtaFort', 'Sin asignar', 'NixtaFort', 14, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:12:34'),
	('0000000000033', 'LOA - AUTOMATIZACION', 'Sin asignar', 'LOA AUTOMATIZACION', 14, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:10:47'),
	('0000000000034', 'Seed Matic', 'Sin asignar', 'Seed Matic', 14, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:12:09'),
	('0000000000035', 'SHIELDmind', 'Sin asignar', 'SHIELDmind', 17, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:19:16'),
	('0000000000036', 'Kinetica', 'Sin asignar', 'Kinetica', 17, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:19:43'),
	('0000000000037', 'TOOP', 'Sin asignar', 'TOOP', 17, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:20:12'),
	('0000000000038', 'Justic - IA', 'Sin asignar', 'Justic IA', 17, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:20:40'),
	('0000000000039', 'EcoVision', 'Sin asignar', 'EcoVision', 17, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:21:01'),
	('0000000000040', 'Turix MEWO', 'Sin asignar', 'Turix MEWO', 17, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:21:20'),
	('0000000000041', 'LanHand', 'Sin asignar', 'LanHand', 17, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:18:52'),
	('0000000000042', 'Space Guide', 'Sin asignar', 'Space Guide', 13, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:27:58'),
	('0000000000043', 'Turix - AuNaMa', 'Sin asignar', 'Turix AuNaMa', 13, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:28:31'),
	('0000000000044', 'SeedMatiC', 'Sin asignar', 'SeedMatiC', 14, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:29:09'),
	('0000000000045', 'Pagina web para ventas EG', 'Sin asignar', 'Pagina web para ventas de la empresa distribuidora Eléctrica Grijalva', 18, 13, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:40:31'),
	('0000000000046', 'Sistema de Gestión CO', 'Sin asignar', 'Sistema de gestión de miembros del Club Orcas', 18, 13, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-01 18:45:10'),
	('0000000000047', 'Sistema de Inventario Pemex', 'Sin asignar', 'Sistema de inventario de herramientas del área de mantenimiento de Pemex Logística', 18, 13, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-07 13:35:14'),
	('0000000000048', 'Implementación de tecnologías Didactic Electronic', 'Sin asignar', 'Implementación de tecnologías para el desarrollo de mesa didáctica Didactic Electronic', 18, 13, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-06-07 13:37:00'),
	('0000000000049', 'Phytobot', 'Sin asignar', 'Phytobot', 13, 12, 4, NULL, 'Sin asignar', 'Sin asignar', 'Sin asignar', '2024-08-29 11:04:16');

-- Volcando estructura para tabla incubadora.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla incubadora.roles: ~6 rows (aproximadamente)
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'web', '2024-12-19 06:36:56', '2024-12-19 06:36:56'),
	(2, 'alumno', 'web', '2024-12-19 06:36:56', '2024-12-19 06:36:56'),
	(3, 'asesor', 'web', '2024-12-19 06:36:56', '2024-12-19 06:36:56'),
	(4, 'mentor', 'web', '2024-12-19 06:36:56', '2024-12-19 06:36:56'),
	(5, 'emprendedor', 'web', '2024-12-19 06:36:56', '2024-12-19 06:36:56'),
	(6, 'inversionista', 'web', '2024-12-19 06:36:56', '2024-12-19 06:36:56');

-- Volcando estructura para tabla incubadora.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla incubadora.role_has_permissions: ~12 rows (aproximadamente)
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(1, 2),
	(2, 2),
	(1, 3),
	(3, 3),
	(1, 4),
	(1, 5),
	(1, 6);

-- Volcando estructura para tabla incubadora.servicio
CREATE TABLE IF NOT EXISTS `servicio` (
  `idServicio` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idServicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla incubadora.servicio: ~0 rows (aproximadamente)

-- Volcando estructura para tabla incubadora.tipo
CREATE TABLE IF NOT EXISTS `tipo` (
  `idTipo` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`idTipo`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla incubadora.tipo: ~4 rows (aproximadamente)
INSERT INTO `tipo` (`idTipo`, `nombre`) VALUES
	(12, 'Innovación'),
	(13, 'Residencia profesional'),
	(14, 'Servicio social'),
	(15, 'Gobierno');

-- Volcando estructura para tabla incubadora.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla incubadora.users: ~2 rows (aproximadamente)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin User', 'admin@g.com', NULL, '$2y$12$Whop/ZfGM5e.yaFcyZP51.MOLMCO2uJd39JStLONfwH88wJVCXnv.', NULL, '2024-12-19 06:38:24', '2024-12-19 06:38:24'),
	(2, 'alumno', 'alumno@g.com', NULL, '$2y$12$MBYMEQOzB5eijaR47NiMpOOwu33ehPttH2p095QF6K0..acB04R4e', NULL, '2024-12-19 06:38:24', '2024-12-19 06:38:24');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
