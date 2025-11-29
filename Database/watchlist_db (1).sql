-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/11/2025 às 07:59
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `watchlist_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `generos`
--

CREATE TABLE `generos` (
  `id_genero` int(11) NOT NULL,
  `nome_genero` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `generos`
--

INSERT INTO `generos` (`id_genero`, `nome_genero`) VALUES
(12, 'Aventura'),
(14, 'Fantasia'),
(16, 'Animação'),
(18, 'Drama'),
(27, 'Terror'),
(28, 'Ação'),
(35, 'Comédia'),
(36, 'História'),
(37, 'Faroeste'),
(53, 'Thriller'),
(80, 'Crime'),
(99, 'Documentário'),
(878, 'Ficção Científica'),
(9648, 'Mistério'),
(10402, 'Música'),
(10749, 'Romance'),
(10751, 'Família'),
(10752, 'Guerra'),
(10759, 'Action & Adventure'),
(10762, 'Kids'),
(10763, 'News'),
(10764, 'Reality'),
(10765, 'Sci-Fi & Fantasy'),
(10766, 'Soap'),
(10767, 'Talk'),
(10768, 'War & Politics');

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_genero`
--

CREATE TABLE `item_genero` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `genero_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `item_genero`
--

INSERT INTO `item_genero` (`id`, `item_id`, `genero_id`) VALUES
(27, 17, 9648),
(28, 17, 18),
(29, 17, 10759),
(30, 18, 16),
(31, 18, 10759),
(32, 18, 35),
(33, 18, 10765),
(37, 20, 27),
(38, 21, 16),
(39, 21, 10759),
(40, 21, 18),
(41, 21, 10765),
(42, 22, 10765),
(43, 22, 9648),
(44, 22, 10759),
(45, 23, 16),
(46, 23, 35),
(47, 23, 18),
(48, 23, 10765),
(54, 26, 18),
(55, 26, 10759),
(56, 27, 10759),
(57, 27, 80),
(58, 27, 18),
(59, 28, 16),
(60, 28, 35),
(61, 28, 10759),
(62, 29, 10765),
(63, 29, 18),
(64, 29, 9648),
(65, 30, 18),
(66, 30, 9648);

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens`
--

CREATE TABLE `itens` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `tipo` enum('Filme','Desenho','Document','Outro') DEFAULT 'Outro',
  `prioridade` enum('Alta','Média','Baixa') DEFAULT 'Baixa',
  `status` enum('Para Ver','Assistido') DEFAULT 'Para Ver',
  `nota` decimal(3,1) DEFAULT NULL,
  `tmdb_id` int(11) DEFAULT NULL,
  `poster_path` varchar(255) DEFAULT NULL,
  `sinopse` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `itens`
--

INSERT INTO `itens` (`id`, `titulo`, `tipo`, `prioridade`, `status`, `nota`, `tmdb_id`, `poster_path`, `sinopse`) VALUES
(17, 'Alice in Borderland', '', 'Média', 'Assistido', 8.4, 110316, '/i0i7kGDrArtM1sCd8niZDC7iboV.jpg', 'Um gamer e seus dois amigos são transportados para uma versão paralela de Tóquio, onde precisam participar de diversos jogos mortais caso queiram sobreviver.'),
(18, 'DAN DA DAN', '', 'Média', 'Assistido', 7.6, 240411, '/vtQug1eOyeU2VXIpNoDF1lTlcH4.jpg', 'Esta é uma história sobre Momo, uma garota do ensino médio que vem de uma família de médiuns espirituais, e seu colega de classe Okarun, um garoto fanático pelo ocultismo. Depois que Momo resgata Okarun de uns valentões, eles começam a conversar... No entanto, surge uma discussão entre eles, já que Momo acredita em fantasmas, mas nega a existência de alienígenas, e Okarun acredita em alienígenas, mas nega a existência de fantasmas. Visando provar que o que acreditam é real, Momo vai a um hospital abandonado onde um OVNI foi avistado e Okarun vai a um túnel que dizem ser assombrado. Para surpresa deles, cada um se depara com atividades paranormais avassaladoras que transcendem a compreensão. Em meio a isso tudo, Momo desperta seu poder oculto e Okarun ganha o poder de uma maldição para superar esses novos perigos! Será que o amor deles destinado também começa aqui!?'),
(20, 'Invocação do Mal - O Último Ritual', '', 'Baixa', 'Para Ver', NULL, 1038392, '/40nHGUfypLhlr7gJx8At1IbYkaK.jpg', 'Neste último capítulo, os Warren enfrentam mais um caso aterrorizante, desta vez envolvendo entidades misteriosas que desafiam sua experiência. Ed (Patrick Wilson) e Lorraine (Vera Farmiga) se veem obrigados a encarar seus maiores medos, colocando suas vidas em risco em uma batalha final contra forças malignas.'),
(21, 'Gachiakuta', '', 'Média', 'Para Ver', NULL, 256721, '/RxuL7KuFoOzHC0htjtLGxJEGGx.jpg', 'Em uma cidade flutuante onde os ricos descartam seu lixo (e pessoas), Rudo é acusado falsamente de homicídio e jogado no Abismo, um lugar infernal onde vivem monstros de lixo mutantes. Para sobreviver, ele precisa conquistar um novo poder e se unir aos errantes Zeladores. Rudo não quer apenas lutar com os monstros, mas também contra os corruptos que o jogaram no Inferno.'),
(22, 'Stranger Things', '', 'Média', 'Para Ver', NULL, 66732, '/7EZcuL3GuiNvwn5RlKLaWazzekL.jpg', 'Quando um garoto desaparece, a cidade toda participa nas buscas. Mas o que encontram são segredos, forças sobrenaturais e uma menina.'),
(23, 'Hotel Hazbin', '', 'Baixa', 'Assistido', 7.5, 94954, '/hdOeeEVqhvKR5sh8gDAp4WwSiHQ.jpg', 'Charlie Morningstar, a Princesa do Inferno, tem dificuldades para convencer anjos e demônios de que toda alma pode ser redimida. Cante e xingue junto nessa comédia animada e musical para maiores sobre segundas chances.'),
(26, 'Arcanjo Renegado', '', 'Média', 'Para Ver', NULL, 99125, '/p7gsnKeSQsyNYO7QrW5xedd3QTe.jpg', 'Mikhael é o líder da principal equipe do BOPE. Quando um dos seus amigos é ferido em uma operação, ele busca vingança e acaba em conflito com a alta cúpula política do estado.'),
(27, 'Prison Break: Em Busca da Verdade', '', 'Média', 'Para Ver', NULL, 2288, '/rK3Vwe0Wm0VXxf4IJCdlHeEREYx.jpg', 'Lincoln Burrows é condenado injustamente à pena de morte. Só lhe resta confiar no irmão Michael Scofield, que executa um plano de fuga e se vê no meio de uma perigosa conspiração.'),
(28, 'SAKAMOTO DAYS', '', 'Alta', 'Para Ver', NULL, 207332, '/qTNIxDIzKjLLuS17fxdXjcKAlW7.jpg', 'Taro Sakamoto já foi o maior assassino de aluguel de todos os tempos, mas largou tudo por amor. Agora, com o passado à espreita, ele vai ter que lutar como nunca.'),
(29, 'Black Mirror', '', 'Baixa', 'Para Ver', NULL, 42009, '/aCTL24B8ZuiI2osMwoUI5rqBXoF.jpg', 'As histórias bizarras não têm limite nesta série antológica que revela o pior da humanidade, suas maiores invenções e muito mais.'),
(30, 'IT: Bem-Vindos a Derry', '', 'Alta', 'Para Ver', NULL, 200875, '/gMTfrLvrDaD0zrhpLZ7zXIIpKfJ.jpg', 'Durante as férias de 1958, em uma pacata cidadezinha chamada Derry, um grupo de sete amigos começa a ver coisas bizarras. Um conta que viu um palhaço, outro que viu uma múmia. Finalmente, acabam descobrindo que estavam todos vendo a mesma coisa: um ser sobrenatural e maligno que pode assumir várias formas. Ele mesmo. O Pennywise.');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `generos`
--
ALTER TABLE `generos`
  ADD PRIMARY KEY (`id_genero`);

--
-- Índices de tabela `item_genero`
--
ALTER TABLE `item_genero`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_item` (`item_id`),
  ADD KEY `fk_genero` (`genero_id`);

--
-- Índices de tabela `itens`
--
ALTER TABLE `itens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `item_genero`
--
ALTER TABLE `item_genero`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT de tabela `itens`
--
ALTER TABLE `itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `item_genero`
--
ALTER TABLE `item_genero`
  ADD CONSTRAINT `fk_genero` FOREIGN KEY (`genero_id`) REFERENCES `generos` (`id_genero`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_item` FOREIGN KEY (`item_id`) REFERENCES `itens` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
