-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2026 at 07:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eldebate`
--

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE `korisnik` (
  `id` int(11) NOT NULL,
  `ime` varchar(32) NOT NULL,
  `prezime` varchar(32) NOT NULL,
  `korisnicko_ime` varchar(32) NOT NULL,
  `lozinka` varchar(255) NOT NULL,
  `razina` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`id`, `ime`, `prezime`, `korisnicko_ime`, `lozinka`, `razina`) VALUES
(1, 'admin', 'admin', 'admin', '$2y$10$ljMai2KxmeyGvglsqRH0VuzetUmTGgKy7NrWM18z74iwoAzfak5Au', 1),
(2, 'Kristijan', 'Mlinar', 'test', '$2y$10$EQmaBhD47VnKkgdwnRHeR.y6sLoAGgzkH9ua66WtQGRV7UQ/P7Rbe', 0),
(3, 'Patrik', 'Poldrugač', 'test2', '$2y$10$w9OXFsX7TbgQn1IXM8ewIuT4i4dIjmE.v4IlXmAC9tT9RULD3jpUq', 0),
(4, 'Novi', 'Korisnik', 'test55', '$2y$10$EVoQxqc3jYZARg/HMJWSHeecAmp87fi1QkwT1yntQhXGdwPCsXfjK', 0),
(5, 'Novi', 'Korisnik', 'test11', '$2y$10$nQDUMiQhlJo0r/hSK.VfU.1uOnpxocltSevI2e9z6AivCYRDOrThi', 0);

-- --------------------------------------------------------

--
-- Table structure for table `vijesti`
--

CREATE TABLE `vijesti` (
  `id` int(10) UNSIGNED NOT NULL,
  `datum` varchar(20) NOT NULL,
  `naslov` varchar(255) NOT NULL,
  `sazetak` text NOT NULL,
  `tekst` text NOT NULL,
  `slika` varchar(255) DEFAULT NULL,
  `kategorija` varchar(50) NOT NULL,
  `arhiva` tinyint(1) NOT NULL DEFAULT 0,
  `tag` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_croatian_ci;

--
-- Dumping data for table `vijesti`
--

INSERT INTO `vijesti` (`id`, `datum`, `naslov`, `sazetak`, `tekst`, `slika`, `kategorija`, `arhiva`, `tag`) VALUES
(1, '09.06.2026.', 'Hrvatski gradovi uvode „Pametne parkove“: Klupe koje same proizvode energiju i pročišćavaju zrak', 'Novi projekt urbane regeneracije donosi revoluciju u domaće parkove. Zahvaljujući spoju solarne tehnologije i sustava za filtraciju, zelene površine postaju samoodržive stanice za odmor i punjenje gadgeta.', 'U sklopu inicijative \"Zelena budućnost\", nekoliko većih hrvatskih gradova započelo je s instalacijom najmodernijih pametnih klupa domaće proizvodnje. Za razliku od standardnih modela, ove klupe opremljene su naprednim fotonaponskim panelima koji ne samo da omogućuju besplatno punjenje mobilnih uređaja, već napajaju i ugrađene pročišćivače zraka.\r\n\r\nPrema podacima izvođača, jedna ovakva klupa može neutralizirati količinu ugljičnog dioksida koju inače apsorbiraju dva odrasla stabla. Osim toga, klupe su opremljene senzorima za kvalitetu zraka, vlažnost i temperaturu, a podaci su građanima dostupni u realnom vremenu putem mobilne aplikacije.\r\n\r\nOvaj potez pozdravili su i građani i ekološki aktivisti, ističući kako je ovo idealan način za spajanje moderne tehnologije s potrebom za očuvanjem okoliša u urbanim sredinama. Prve „pametne zone“ već su aktivne, a plan je da do kraja godine svaki veći grad dobije barem jednu ovakvu tehnološku oazu.', '2_pametna_klupa_qben_nebula_s_naslonom.jpg', 'ZABAVA', 0, 'Ekologija'),
(2, '09.06.2026.', 'Nova knjižnica', 'Otvorena nova knjižnica u Zagrebu', 'U Zagrebu je otvorena najnovija knjižnica', 'LibraryLogo.png', 'KULTURA', 0, 'Knjige'),
(3, '18.05.2019.', 'Tornados dejan daños en casas del sur de Estados Unidos', 'Varias familias reportaron daños materiales después del paso de fuertes tormentas.', 'Fuertes tornados provocaron daños en viviendas, árboles caídos y cortes de energía en distintas zonas del sur de Estados Unidos.\r\n\r\nLas autoridades locales recomendaron a los habitantes mantenerse atentos a los avisos meteorológicos y evitar circular por áreas afectadas.\r\n\r\nEquipos de emergencia comenzaron los trabajos de limpieza y revisión de estructuras para garantizar la seguridad de las familias.', 'tornado.jpg', 'MUNDO', 0, 'Estados unidos'),
(4, '18.05.2019.', 'Boeing reconoce defectos en software del simulador', 'La compañía informó que revisará el sistema utilizado para entrenamientos de pilotos.', 'Boeing reconoció la existencia de defectos en el software de uno de sus simuladores de vuelo, utilizado para preparar a pilotos.\r\n\r\nLa empresa señaló que trabaja en una actualización y que colaborará con las autoridades para reforzar los protocolos de seguridad.\r\n\r\nEl anuncio generó nuevas preguntas sobre los procesos de certificación y capacitación vinculados a sus aeronaves.', 'boeing.jpg', 'MUNDO', 0, 'Boeing'),
(5, '18.05.2019.', 'Mujer logra increíble transformación al bajar más de 200 kilos', 'Su historia se volvió viral por el esfuerzo, la disciplina y el cambio de hábitos.', 'Una mujer sorprendió a miles de personas al compartir su proceso de transformación después de bajar más de 200 kilos.\r\n\r\nLa protagonista explicó que el cambio fue posible gracias a una combinación de apoyo médico, actividad física y una alimentación más equilibrada.\r\n\r\nSu historia fue destacada como un ejemplo de constancia y motivación para quienes buscan mejorar su calidad de vida.', 'obesity.jpg', 'MUNDO', 0, 'Obesidad'),
(6, '18.05.2019.', 'Joven que le sacaron su bebé en Chicago fue asesinada', 'El caso conmocionó a la comunidad y continúa bajo investigación policial.', 'Una joven fue encontrada sin vida en Chicago después de un caso que generó gran impacto entre familiares y vecinos.\r\n\r\nLas autoridades informaron que la investigación continúa abierta y que se analizan distintas pruebas para esclarecer lo ocurrido.\r\n\r\nLa comunidad pidió justicia y mayor protección para las personas vulnerables ante hechos de violencia extrema.', 'murder.jpg', 'MUNDO', 0, 'Asesinato'),
(7, '18.05.2019.', 'Tigres vs Monterrey, minuto a minuto semifinales Liga MX', 'Medio tiempo, Tigres vence 1-0 a Monterrey con un gol de Pizarro', 'Tigres recibe al Monterrey con una espina clavada y una desventaja de un gol que al momento lo tiene fuera de su sexta final en los últimos 10 torneos.\r\n\r\nDespués de perder 1-0 ante Rayados en su visita al Estadio BBVA Bancomer, Tigres llega herido y con la esperanza de darle la vuelta a las semifinales.\r\n\r\nLos Felinos estuvieron escasos de posibilidades en el primer encuentro y no pudieron anotar el valioso gol de visitante.', 'tigres.jpg', 'DEPORTE', 0, 'Tigres de la uanl'),
(8, '18.05.2019.', 'María del Rosario Espinoza comparte amargo adiós', 'La taekwondoína habló sobre una despedida difícil dentro de su carrera deportiva.', 'María del Rosario Espinoza compartió un mensaje emotivo después de vivir una despedida amarga en una etapa importante de su carrera.\r\n\r\nLa deportista agradeció el apoyo recibido y recordó los momentos que marcaron su trayectoria dentro del taekwondo.\r\n\r\nSus seguidores destacaron su disciplina, entrega y el legado que deja como una de las atletas más reconocidas de su disciplina.', 'taekwondo.jpg', 'DEPORTE', 0, 'Taekwondo'),
(9, '18.05.2019.', 'Yo decido en mi equipo, si no me marcharía', 'Zinedine Zidane defendió su autoridad en las decisiones deportivas del club.', 'Zinedine Zidane fue claro al señalar que las decisiones deportivas de su equipo deben pasar por su criterio como entrenador.\r\n\r\nEl técnico explicó que su continuidad depende de tener libertad para construir el plantel y definir el rumbo competitivo.\r\n\r\nSus declaraciones provocaron debate entre aficionados y medios, especialmente por el futuro de algunos jugadores importantes.', 'zidane.jpg', 'DEPORTE', 0, 'Zinedine Zidane'),
(10, '18.05.2019.', 'Lyon vence al Barcelona y gana Champions', 'El conjunto francés volvió a demostrar su dominio en la máxima competición europea.', 'Lyon venció al Barcelona y conquistó nuevamente la Champions, confirmando su papel como uno de los clubes más fuertes de Europa.\r\n\r\nEl equipo francés mostró intensidad, orden y contundencia en los momentos clave del encuentro.\r\n\r\nBarcelona intentó reaccionar, pero no logró frenar el ritmo de Lyon, que celebró un nuevo título internacional.', 'lyon.jpg', 'DEPORTE', 0, 'Lyon'),
(17, '11.06.2026.', 'Nova', 'sadas', 'aaa', 'boeing.jpg', 'POLITIKA', 0, 'aaa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `korisnicko_ime` (`korisnicko_ime`);

--
-- Indexes for table `vijesti`
--
ALTER TABLE `vijesti`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `korisnik`
--
ALTER TABLE `korisnik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vijesti`
--
ALTER TABLE `vijesti`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
